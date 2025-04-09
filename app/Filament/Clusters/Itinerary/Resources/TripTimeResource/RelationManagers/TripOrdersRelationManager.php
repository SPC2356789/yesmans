<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;


use App\Models\TripTime;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

use Filament\Tables\Enums\ActionsPosition;

use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class TripOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'Orders';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('訂單編號 copied')

                ,
                Tables\Columns\TextColumn::make('applies.name')
                    ->label('團員')
                    ->searchable()
                    ->html() // 啟用 HTML 渲染
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                ,

                Tables\Columns\TextColumn::make('amount')
                    ->label('每人金額')
                    ->copyable()
                    ->copyMessage('copied')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('total_amount') // 新增總金額欄位
                ->label('總金額')
                ,
                Tables\Columns\TextColumn::make('original_amount')
                    ->label('每位原價')
                    ->getStateUsing(function ($record) {
                        return TripTime::where('uuid', $record->trip_time_uuid)->first()->fake_amount;
                    })
                ,

                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('已付金額')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'white-space: pre-wrap;width: 120px;', // 保留換行和空白
                    ])
//                    ->formatStateUsing(fn ($state) => nl2br(e($state))) // 顯示時轉換換行
                ,

                Tables\Columns\TextInputColumn::make('account_last_five')
                    ->label('帳戶後五碼')
                    ->searchable()
                ,

                Tables\Columns\SelectColumn::make('status')
                    ->options(function () {
                        // 動態生成下拉選單選項
                        return collect(config('order_statuses'))->mapWithKeys(function ($status, $key) {
                            return [$key => $status['text'] . "\n" . $status['note']];
                        })->all();
                    })
                    ->label('狀態')
                    ->afterStateUpdated(function ($record, $state) {
                        // 從 $record 或外部上下文獲取相關數據
                        $original_amount = TripTime::where('uuid', $record->trip_time_uuid)->first()->fake_amount ?? null; // 原價
                        $original_total = $original_amount ? $original_amount * $record->appliesCount : null; // 總額(原價)

                        $paid = $record->paid_amount; // 當前 paid_amount
                        $today = date('Y-m-d'); // 今天的日期
                        $setOP = "\n-";

                        switch ($state) {
                            case '41': // 已匯訂
                                $record->update(['paid_amount' => $record->total_amount]);
                                break;
                            case '42': // 已完款
                                if (is_numeric($paid)) {
                                    $additionalAmount = $original_total ? $original_total - $paid : $paid; // 這次要增加的金額
                                    $history = "\n{$today}\n(+{$additionalAmount})";
                                    $record->update(['paid_amount' => $paid . $history . $setOP]);
                                }
                                break;
                            case '98': // 已退訂
                                if (is_numeric($paid)) {
                                    $history = "\n{$today}\n(退訂 {$paid})";
                                    $record->update(['paid_amount' => $paid . $history . $setOP]);
                                }
                                break;
                            case '99': // 已退全款
                                $total = $original_total ?? $paid;
                                $history = "\n{$today}\n(退全款 {$total})";
                                $record->update(['paid_amount' => $paid . $history . $setOP]);
                                break;
                            default:
                                break;
                        }
                    })
                ,

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null) // 禁用整行點擊導航
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return "/yes-admin/order/trip-orders/{$record->id}/edit";
                    })
                    ->openUrlInNewTab()
                ,


            ], position: ActionsPosition::BeforeColumns)

            ->bulkActions([
                // 分組 1：報名階段
                Tables\Actions\BulkActionGroup::make(
                    collect(config('order_statuses'))
                        ->only(['10'])
                        ->map(function ($status, $key) {
                            return Tables\Actions\BulkAction::make("set_status_{$key}")
                                ->label("設為{$status['note']}")
                                ->icon($this->getIconForStatus($key))
                                ->action(function (Collection $records) use ($key) {
                                    foreach ($records as $record) {
                                        $record->update(['status' => $key]);
                                        $this->updatePaidAmount($record, $key);
                                    }
                                })
                                ->color($this->getColorForStatus($key))
                                ->requiresConfirmation()
                                ->deselectRecordsAfterCompletion();
                        })
                        ->all()
                )->label('報名完成'),

                // 分組 2：付款處理
                Tables\Actions\BulkActionGroup::make(
                    collect(config('order_statuses'))
                        ->only(['11', '12', '41', '42'])
                        ->map(function ($status, $key) {
                            return Tables\Actions\BulkAction::make("set_status_{$key}")
                                ->label("設為{$status['note']}")
                                ->icon($this->getIconForStatus($key))
                                ->action(function (Collection $records) use ($key) {
                                    foreach ($records as $record) {
                                        $record->update(['status' => $key]);
                                        $this->updatePaidAmount($record, $key);
                                    }
                                })
                                ->color($this->getColorForStatus($key))
                                ->requiresConfirmation()
                                ->deselectRecordsAfterCompletion();
                        })
                        ->all()
                )->label('付款處理'),

                // 分組 3：問題處理
                Tables\Actions\BulkActionGroup::make(
                    collect(config('order_statuses'))
                        ->only(['14', '15'])
                        ->map(function ($status, $key) {
                            return Tables\Actions\BulkAction::make("set_status_{$key}")
                                ->label("設為{$status['note']}")
                                ->icon($this->getIconForStatus($key))
                                ->action(function (Collection $records) use ($key) {
                                    foreach ($records as $record) {
                                        $record->update(['status' => $key]);
                                        $this->updatePaidAmount($record, $key);
                                    }
                                })
                                ->color($this->getColorForStatus($key))
                                ->requiresConfirmation()
                                ->deselectRecordsAfterCompletion();
                        })
                        ->all()
                )->label('問題處理'),

                // 分組 4：取消處理
                Tables\Actions\BulkActionGroup::make(
                    collect(config('order_statuses'))
                        ->only(['91', '92', '93', '94'])
                        ->map(function ($status, $key) {
                            return Tables\Actions\BulkAction::make("set_status_{$key}")
                                ->label("設為{$status['note']}")
                                ->icon($this->getIconForStatus($key))
                                ->action(function (Collection $records) use ($key) {
                                    foreach ($records as $record) {
                                        $record->update(['status' => $key]);
                                        $this->updatePaidAmount($record, $key);
                                    }
                                })
                                ->color($this->getColorForStatus($key))
                                ->requiresConfirmation()
                                ->deselectRecordsAfterCompletion();
                        })
                        ->all()
                )->label('取消處理'),

                // 分組 5：終止狀態
                Tables\Actions\BulkActionGroup::make(
                    collect(config('order_statuses'))
                        ->only(['98', '99', '1'])
                        ->map(function ($status, $key) {
                            return Tables\Actions\BulkAction::make("set_status_{$key}")
                                ->label("設為{$status['text']} {$status['note']}")
                                ->icon($this->getIconForStatus($key))
                                ->action(function (Collection $records) use ($key) {
                                    foreach ($records as $record) {
                                        $record->update(['status' => $key]);
                                        $this->updatePaidAmount($record, $key);
                                    }
                                })
                                ->color($this->getColorForStatus($key))
                                ->requiresConfirmation()
                                ->deselectRecordsAfterCompletion();
                        })
                        ->all()
                )->label('終止狀態'),
// 新增分組：複製操作
                // 新增分組：複製操作
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('copy_yushan_xueba')
                        ->label('玉山、雪霸複製')
                        ->color('info')
                        ->extraAttributes(function () use ($table) {
                            $records = $table->getLivewire()->getSelectedTableRecords();
                            $applies = $this->getAppliesFromRecords($records);
                            if ($applies->isEmpty()) {
                                $this->sendWarningNotification();
                                return [];
                            }
                            $data = $this->formatAppliesData($applies);
                            $jsCode = $this->national_park($data, 1);
                            return [
                                'data-copy' => $jsCode,
                                'onclick' => 'copyToClipboard(this); showSuccessNotification("玉山、雪山 複製成功")',
                            ];
                        }),

                    Tables\Actions\BulkAction::make('copy_taroko')
                        ->label('太魯閣複製')
                        ->color('gray')
                        ->extraAttributes(function () use ($table) {
                            $records = $table->getLivewire()->getSelectedTableRecords();
                            $applies = $this->getAppliesFromRecords($records);
                            if ($applies->isEmpty()) {
                                $this->sendWarningNotification();
                                return [];
                            }
                            $data = $this->formatAppliesData($applies);
                            $jsCode = $this->national_park($data, 2);
                            return [
                                'data-copy' => $jsCode,
                                'onclick' => 'copyToClipboard(this); showSuccessNotification("太魯閣複製成功")',
                            ];
                        }),

                    Tables\Actions\BulkAction::make('copy_mianyue')
                        ->label('眠月線複製')
                        ->color('success')
                        ->extraAttributes(function () use ($table) {
                            $records = $table->getLivewire()->getSelectedTableRecords();
                            $applies = $this->getAppliesFromRecords($records);
                            if ($applies->isEmpty()) {
                                $this->sendWarningNotification();
                                return [];
                            }
                            $data = $this->formatAppliesData($applies);
                            $jsCode = $this->reserved_area($data, 1);
                            return [
                                'data-copy' => $jsCode,
                                'onclick' => 'copyToClipboard(this); showSuccessNotification("眠月線複製成功")',
                            ];
                        }),

                    Tables\Actions\BulkAction::make('copy_insurance')
                        ->label('保險語法複製')
                        ->color('primary')
                        ->extraAttributes(function () use ($table) {
                            $records = $table->getLivewire()->getSelectedTableRecords();
                            $applies = $this->getAppliesFromRecords($records);
                            if ($applies->isEmpty()) {
                                $this->sendWarningNotification();
                                return [];
                            }
                            $data = $this->Insurance($applies);
                            return [
                                'data-copy' => $data,
                                'onclick' => 'copyToClipboard(this); showSuccessNotification("保險語法複製")',
                            ];
                        }),
                ])->label('複製操作'),
                // 其他操作
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->label('其他操作'),
            ]);
    }

// 提取共用的 paid_amount 更新邏輯
    protected function updatePaidAmount($record, $state): void
    {
        $original_amount = TripTime::where('uuid', $record->trip_time_uuid)->first()->fake_amount ?? null;
        $original_total = $original_amount ? $original_amount * $record->appliesCount : null;

        $paid = $record->paid_amount;
        $today = date('Y-m-d');
        $setOP = "\n-";

        switch ($state) {
            case '41': // 已匯訂
                $record->update(['paid_amount' => $record->total_amount]);
                break;
            case '42': // 已完款
                if (is_numeric($paid)) {
                    $additionalAmount = $original_total ? $original_total - $paid : $paid;
                    $history = "\n{$today}\n(+{$additionalAmount})";
                    $record->update(['paid_amount' => $paid . $history . $setOP]);
                }
                break;
            case '98': // 已退訂
                if (is_numeric($paid)) {
                    $history = "\n{$today}\n(退訂 {$paid})";
                    $record->update(['paid_amount' => $paid . $history . $setOP]);
                }
                break;
            case '99': // 已退全款
                $total = $original_total ?? $paid;
                $history = "\n{$today}\n(退全款 {$total})";
                $record->update(['paid_amount' => $paid . $history . $setOP]);
                break;
            default:
                break;
        }
    }
// 自訂狀態圖標
    protected function getIconForStatus(string $state): string
    {
        return match ($state) {
            '41' => 'heroicon-o-check',
            '42' => 'heroicon-o-check-circle',
            '98' => 'heroicon-o-x-mark',
            '99' => 'heroicon-o-x-circle',
            default => 'heroicon-o-pencil',
        };
    }

    // 自訂狀態顏色
    protected function getColorForStatus(string $state): string
    {
        return match ($state) {
            '41' => 'success',
            '42' => 'success',
            '98' => 'danger',
            '99' => 'danger',
            default => 'gray',
        };
    }
    protected function isTableRecordClickEnabled(): bool
    {
        return false;
    }

    protected function getTableDefaultAction(): ?string
    {
        return null;
    }
    // 從多條記錄中獲取 applies
    protected function getAppliesFromRecords(Collection $records): Collection
    {
        return $records->flatMap(function ($record) {
            return $record->applies;
        });
    }

    // 格式化 applies 數據
    protected function formatAppliesData($applies): string
    {
        return $applies->map(function ($apply) {
            $nation = ($apply->country == '台灣(TWN)' ? '中華民國' : '外國');
            return "  {\n" .
                "    name: \"{$apply->name}\",\n" .
                "    tel: \"{$apply->phone}\",\n" .
                "    addr: \"{$apply->address}\",\n" .
                "    mobile: \"{$apply->phone}\",\n" .
                "    email: \"{$apply->email}\",\n" .
                "    nation: \"{$nation}\",\n" .
                "    sid: \"{$apply->id_card}\",\n" .
                "    sex: \"{$apply->gender}\",\n" .
                "    birthday: \"{$apply->birthday}\",\n" .
                "    contactName: \"{$apply->emContact}\",\n" .
                "    contactTel: \"{$apply->emContactPh}\"\n" .
                "  }";
        })->implode(",\n");
    }

    // 保險語法格式化
    protected function Insurance($applies): string
    {
        return $applies->map(function ($apply) {
            return
                "    姓名: \"{$apply->name}\",\n" .
                "    電話: \"{$apply->phone}\",\n" .
                "    生日: \"{$apply->birthday}\",\n" .
                "    身分證: \"{$apply->id_card}\",\n";
        })->implode(",\n");
    }

    // 國家公園複製邏輯
    protected function national_park($data, $mod): string
    {
        $mods = match ($mod) {
            1 => 'con_lisMem_member', // 玉山、雪山
            2 => 'con_step2_lisMem_member', // 太魯閣
        };

        $jsCode = <<<JS
(function() {
    const members = [
       $data
    ];

    members.forEach((member, index) => {
        const memberDivs = document.querySelectorAll('.card.mb-0');
        const memberDiv = memberDivs[index];

        if (!memberDiv) {
            console.warn(`成員區塊 card mb-0（索引 \${index}）不存在，請先新增成員`);
            return;
        }

        const setValue = (id, value) => {
            const element = document.getElementById(id);
            if (element) {
                element.value = value;
            } else {
                console.warn(`表單欄位 \${id} 不存在`);
            }
        };

        setValue(`{$mods}_name_\${index}`, member.name || '');
        setValue(`{$mods}_tel_\${index}`, member.tel || '');
        setValue(`{$mods}_addr_\${index}`, member.addr || '');
        setValue(`{$mods}_mobile_\${index}`, member.mobile || '');
        setValue(`{$mods}_email_\${index}`, member.email || '');
        setValue(`{$mods}_sid_\${index}`, member.sid || '');
        setValue(`{$mods}_contactname_\${index}`, member.contactName || '');
        setValue(`{$mods}_contacttel_\${index}`, member.contactTel || '');

        setValue(`{$mods}_nation_\${index}`, member.nation || '');
        setValue(`{$mods}_sex_\${index}`, member.sex || '');

        const birthdayInput = document.getElementById(`{$mods}_birthday_\${index}`);
        if (birthdayInput) {
            birthdayInput.value = member.birthday || '';
            birthdayInput.dispatchEvent(new Event('change'));
        } else {
            console.warn(`生日欄位 {$mods}_birthday_\${index} 不存在`);
        }

        const inputs = memberDiv.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.dispatchEvent(new Event('blur'));
        });
    });
})();
JS;

        return $jsCode;
    }

    // 眠月線複製邏輯
    protected function reserved_area($data, $mod): string
    {
        $mods = match ($mod) {
            1 => 'Step03', // 眠月線
        };

        $jsCode = <<<JS
(function() {
    const members = [
       $data
    ];

    members.forEach((member, index) => {
        const memberDivs = document.querySelectorAll('.card.mb-0');
        const memberDiv = memberDivs[index];

        if (!memberDiv) {
            console.warn(`成員區塊 card mb-0（索引 \${index}）不存在，請先新增成員`);
            return;
        }

        const memberIndex = index + 1;

        const setValue = (name, value) => {
            const element = document.querySelector(`[name="\${name}"]`);
            if (element) {
                if (element.tagName === 'SELECT') {
                    const options = element.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].text === value || options[i].value === value) {
                            element.value = options[i].value;
                            break;
                        }
                    }
                } else {
                    element.value = value;
                }
            } else {
                console.warn(`表單欄位 \${name} 不存在`);
            }
        };

        setValue(`{$mods}_\${memberIndex}_Name`, member.name || '');
        setValue(`{$mods}_\${memberIndex}_Tel`, member.tel || '');
        setValue(`{$mods}_\${memberIndex}_Address`, member.addr || '');
        setValue(`{$mods}_\${memberIndex}_Mobile`, member.mobile || '');
        setValue(`{$mods}_\${memberIndex}_Email`, member.email || '');
        setValue(`{$mods}_\${memberIndex}_IdCard`, member.sid || '');
        setValue(`{$mods}_\${memberIndex}_SOSName`, member.contactName || '');
        setValue(`{$mods}_\${memberIndex}_SOSTel`, member.contactTel || '');

        const countryKind = member.nation === '中華民國' ? '1' : '0';
        setValue(`{$mods}_\${memberIndex}_CountryKind`, countryKind);
        if (countryKind === '0') {
            setValue(`{$mods}_\${memberIndex}_Country`, member.nation || '');
        }

        const sexValue = member.sex === '男' ? '1' : member.sex === '女' ? '0' : '';
        setValue(`{$mods}_\${memberIndex}_Sex`, sexValue);

        const birthdayInput = document.querySelector(`[name="{$mods}_\${memberIndex}_Birthday"]`);
        if (birthdayInput) {
            birthdayInput.value = member.birthday || '';
            birthdayInput.dispatchEvent(new Event('change'));
        } else {
            console.warn(`生日欄位 {$mods}_\${memberIndex}_Birthday 不存在`);
        }

        const inputs = memberDiv.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.dispatchEvent(new Event('blur'));
        });
    });
})();
JS;

        return $jsCode;
    }

    // 發送警告通知
    protected function sendWarningNotification(): void
    {
        Notification::make()
            ->title('沒有申請資料')
            ->warning()
            ->body('目前沒有可複製的申請資料，請先新增申請。')
            ->send();
    }
}
