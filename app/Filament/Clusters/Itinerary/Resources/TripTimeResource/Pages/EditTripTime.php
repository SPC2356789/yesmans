<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripResource;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class EditTripTime extends EditRecord
{
    protected static string $resource = TripTimeResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Y')
                ->label('玉山、雪霸複製')
                ->extraAttributes(function (Model $record) {
                    $applies = $this->getApplies($record);
                    if ($applies->isEmpty()) {
                        $this->sendWarningNotification();
                        return [];
                    }
                    $data = $this->formatAppliesData($applies);
                    return [
                        'data-copy' => self::national_park($data, 1),
                        'onclick' => 'copyToClipboard(this)',
                    ];
                })
                ->color('info')
                ->action(function () {
                    Notification::make()
                        ->title('玉山、雪山 複製成功')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('T')
                ->label('太魯閣複製')
                ->extraAttributes(function (Model $record) {
                    $applies = $this->getApplies($record);
                    if ($applies->isEmpty()) {
                        $this->sendWarningNotification();
                        return [];
                    }
                    $data = $this->formatAppliesData($applies);
                    return [
                        'data-copy' => self::national_park($data, 2),
                        'onclick' => 'copyToClipboard(this)',
                    ];
                })
                ->color('gray')
                ->action(function () {
                    Notification::make()
                        ->title('太魯閣複製成功')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('M')
                ->label('眠月線複製')
                ->extraAttributes(function (Model $record) {
                    $applies = $this->getApplies($record);
                    if ($applies->isEmpty()) {
                        $this->sendWarningNotification();
                        return [];
                    }
                    $data = $this->formatAppliesData($applies);
                    return [
                        'data-copy' => self::reserved_area($data, 1),
                        'onclick' => 'copyToClipboard(this)',
                    ];
                })
                ->color('success')
                ->action(function () {
                    Notification::make()
                        ->title('眠月線複製成功')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('BB')
                ->label('保險語法複製')
                ->extraAttributes(function (Model $record) {
                    $applies = $this->getApplies($record);
                    if ($applies->isEmpty()) {
                        $this->sendWarningNotification();
                        return [];
                    }
                    $data = $this->Insurance($applies);
                    return [
                        'data-copy' => $data,
                        'onclick' => 'copyToClipboard(this)',
                    ];
                })
                ->color('primary')
                ->action(function () {
                    Notification::make()
                        ->title('保險語法複製')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getApplies($record)
    {
        return $record->Orders->flatMap(function ($order) {
            return $order->applies;
        });
    }

    protected function sendWarningNotification()
    {
        Notification::make()
            ->title('沒有申請資料')
            ->warning()
            ->body('目前沒有可複製的申請資料，請先新增申請。')
            ->send();
    }

    protected function formatAppliesData($applies)
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

    protected function Insurance($applies)
    {
        return $applies->map(function ($apply) {
            return
                "    姓名: \"{$apply->name}\",\n" .
                "    電話: \"{$apply->phone}\",\n" .
                "    身分證: \"{$apply->id_card}\",\n";
        })->implode(",\n");
    }

    public static function national_park($data, $mod)
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

    public static function reserved_area($data, $mod)
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
}
