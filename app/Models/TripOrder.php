<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TripOrder extends BaseModel
{
    use SoftDeletes;
    use HasFactory;


    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($model) {
//            if ($model->isDirty() || $model->wasRecentlyCreated) {
//                return;
//            }
//            $applies = json_decode($model->applies, true); // 將 JSON 轉為陣列
//            $model->applies_count=count($applies);
//            $amount = $model->amount; // 獲取表單中用戶輸入的 amount
//            $model->total_amount = is_array($applies) ? $model->applies_count * $amount : $amount;
//            $model->lave_amount = $model->total_amount  - $model->paid_amount;
        });
        static::saving(function ($model) {
            unset($model->total_amount);
            unset($model->lave_amount);
        });

    }
    protected $appends = ['applied_count', 'total_amount', 'lave_amount'];

    public function getAppliedCountAttribute(): int
    {
        $applies = json_decode($this->applies, true);
        return count($applies);
    }

    public function getTotalAmountAttribute()
    {
        $applies = json_decode($this->applies, true);
        return is_array($applies) ? $this->applied_count * $this->amount : $this->amount;
    }

    public function getLaveAmountAttribute()
    {
        $paidAmount = $this->paid_amount;
        preg_match('/^(\d+)/m', $paidAmount, $matches); // 匹配第一行的數字
        $number = (int)($matches[1] ?? 0); // 提取匹配結果，預設為 0
        return $this->total_amount - $number;
    }

    /**
     * Get the trip times associated with this model.
     * Note: applies是trip_applies 的id.
     *
     * @return belongsToMany
     */
//    public function trip_applies(): belongsToMany
//    {
//        return $this->belongsToMany(TripApply::class, 'applies_TripApply', 'id', 'applies');
//    }
    public function applies(): BelongsToMany
    {
        return $this->belongsToMany(
            TripApply::class,
            'order_has_apply',
            'trip_order_on',  // 中間表指向 TripOrder 的欄位
            'trip_apply_id',      // 中間表指向 TripApply 的欄位
            'order_number',       // TripOrder 的關聯鍵
            'id'                  // TripApply 的關聯鍵
        );
    }
    public function times(): BelongsTo
    {
        return $this->belongsTo(
            TripTime::class,
            'trip_time_uuid', // TripOrder 表中的外鍵
            'uuid'            // TripTime 的主鍵
        );
    }
    public function formatAppliesData($applies): string
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
    public function Insurance($applies): string
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
    public function national_park($data, $mod): string
    {
        $mods = match ($mod) {
            1 => 'con_lisMem', // 玉山、雪山
            2 => 'con_step2_lisMem', // 太魯閣
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
                const changeEvent = new Event('change', { bubbles: true });
                element.dispatchEvent(changeEvent);
            } else {
                console.warn(`表單欄位 \${id} 不存在`);
            }
        };

        const triggerBlur = (id) => {
            const element = document.getElementById(id);
            if (element) {
                const blurEvent = new Event('blur', { bubbles: true });
                element.dispatchEvent(blurEvent);
            }
        };

        // 設置基本欄位
        setValue(`{$mods}_member_name_\${index}`, member.name || '');
        setValue(`{$mods}_member_tel_\${index}`, member.tel || '');
        setValue(`{$mods}_member_addr_\${index}`, member.addr || '');
        setValue(`{$mods}_member_mobile_\${index}`, member.mobile || '');
        setValue(`{$mods}_member_email_\${index}`, member.email || '');
        setValue(`{$mods}_member_sid_\${index}`, member.sid || '');
        setValue(`{$mods}_member_contactname_\${index}`, member.contactName || '');
        setValue(`{$mods}_member_contacttel_\${index}`, member.contactTel || '');

        setValue(`{$mods}_member_nation_\${index}`, member.nation || '');
        setValue(`{$mods}_member_sex_\${index}`, member.sex || '');

        const birthdayInput = document.getElementById(`{$mods}_member_birthday_\${index}`);
        if (birthdayInput) {
            birthdayInput.value = member.birthday || '';
            birthdayInput.dispatchEvent(new Event('change', { bubbles: true }));
        } else {
            console.warn(`生日欄位 {$mods}_member_birthday_\${index} 不存在`);
        }

        // 設置國家並延遲一秒設置城市

            // 先設置國家
            setValue(`{$mods}_ddlmember_country_\${index}`, '9999');

            // 延遲一秒設置城市
            setTimeout(() => {

                    setValue(`{$mods}_ddlmember_city_\${index}`, '9999');

            }, 1000); // 延遲 1000 毫秒（1 秒）


        // 觸發所有欄位的 blur 事件
        const inputs = memberDiv.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.dispatchEvent(new Event('blur', { bubbles: true }));
        });
    });
})();
JS;

        return $jsCode;
    }

    // 眠月線複製邏輯
    public function reserved_area($data, $mod): string
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
                const changeEvent = new Event('change', { bubbles: true });
                element.dispatchEvent(changeEvent);
            } else {
                console.warn(`表單欄位 \${name} 不存在`);
            }
        };

        const triggerBlur = (name) => {
            const element = document.querySelector(`[name="\${name}"]`);
            if (element) {
                const blurEvent = new Event('blur', { bubbles: true });
                element.dispatchEvent(blurEvent);
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


         // 設置國家並延遲一秒設置城市

            // 先設置國家
            setValue(`{$mods}_\${memberIndex}_City`, '9999');


            // 延遲一秒設置城市
            setTimeout(() => {
                setValue(`{$mods}_\${memberIndex}_Town`, '9999');

            }, 1000); // 延遲 1000 毫秒（1 秒）


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
                       input.dispatchEvent(new Event('blur', { bubbles: true }));
        });
    });
})();
JS;

        return $jsCode;
    }
}
