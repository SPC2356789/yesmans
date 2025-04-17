<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;


use App\Models\TripOrder;
use App\Models\TripTime;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

use Filament\Tables\Enums\ActionsPosition;

use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

class TripOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'Orders';
    protected static ?string $model = TripOrder::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state) {
                        $parts = explode('_', $state);
                        return implode('<br>', $parts);
                    })
                    ->html() // 允許 HTML 渲染
                ,
                Tables\Columns\TextColumn::make('applies.name')
                    ->label('團員')
                    ->searchable()
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
                    ->toggleable(isToggledHiddenByDefault: false)
                ,

                Tables\Columns\TextColumn::make('total_amount') // 新增總金額欄位
                ->label('總金額')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('original_amount')
                    ->label('每位原價')
                    ->getStateUsing(function ($record) {
                        return (TripTime::where('uuid', $record->trip_time_uuid)->first()->fake_amount) ?? 0;
                    })
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('已付金額')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'white-space: pre-wrap;width: 120px;', // 保留換行和空白
                    ])

                ,

                Tables\Columns\TextInputColumn::make('account_last_five')
                    ->label('帳戶後五碼')
                    ->searchable()
                    ->extraAttributes(['class' => 'w-12']) // Tailwind 類別 w-40 表示寬度 10rem（約 160px）
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('entry_number')
                    ->label('入山編號')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

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
                // 其他操作
                Tables\Actions\BulkActionGroup::make([
                    // 新增分組：複製操作

                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\BulkAction::make('copy_yushan_xueba')
                            ->label('玉山、雪霸複製')
                            ->color('info')
                            ->action(function (Collection $records) {
                                $this->handleCopyAction($records, 'national_park', 1, '玉山、雪山 複製成功', '玉山、雪山 複製失敗');
                            }),

                        Tables\Actions\BulkAction::make('copy_taroko')
                            ->label('太魯閣複製')
                            ->color('gray')
                            ->action(function (Collection $records) {
                                $this->handleCopyAction($records, 'national_park', 2, '太魯閣複製成功', '太魯閣複製失敗');
                            }),

                        Tables\Actions\BulkAction::make('copy_mianyue')
                            ->label('眠月線複製')
                            ->color('success')
                            ->action(function (Collection $records) {
                                $this->handleCopyAction($records, 'reserved_area', 1, '眠月線複製成功', '眠月線複製失敗');
                            }),

                        Tables\Actions\BulkAction::make('copy_insurance')
                            ->label('保險語法複製')
                            ->color('primary')
                            ->action(function (Collection $records) {
                                $this->handleCopyAction($records, 'Insurance', null, '保險語法複製', '保險語法複製失敗');
                            }),
                    ])->label('複製操作'),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ])->label('其他操作'),
                Tables\Actions\BulkActionGroup::make([

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
                ])->label('狀態更改'),


            ]);
    }

// 處理複製操作的共用邏輯
    protected function handleCopyAction(Collection $records, string $method, ?int $mod, string $successMessage, string $failedMessage): void
    {
        $applies = $this->getAppliesFromRecords($records);
        if ($applies->isEmpty()) {
            $this->sendWarningNotification();
            return;
        }

        // 根據方法生成複製內容 TripOrder 存放邏輯
        if ($method === 'Insurance') {
            $data = (new \App\Models\TripOrder)->$method($applies);
        } else {
            $data = (new \App\Models\TripOrder)->formatAppliesData($applies);
            $data = (new \App\Models\TripOrder)->$method($data, $mod);
        }

        // 觸發前端複製事件
        $this->dispatch('copy-to-clipboard', content: $data, successMessage: $successMessage, failedMessage: $failedMessage);
        $this->sendSuccessNotification($applies->count());
    }

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


    // 發送警告通知
    protected function sendWarningNotification(): void
    {
        Notification::make()
            ->title('沒有申請資料')
            ->warning()
            ->body('目前沒有可複製的申請資料，請先新增申請。')
            ->send();
    }

    protected function sendSuccessNotification($count): void
    {
        Notification::make()
            ->title("複製成功")
            ->success()
            ->body("{$count}位資料，已經複製。")
            ->send();
    }
}
