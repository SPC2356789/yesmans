<?php

namespace App\Models;

use App\Helper\ShortCrypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripApply extends BaseModel
{
    use HasFactory;

    // 定義可以進行批量賦值的欄位
    use SoftDeletes;

    protected static function booted()
    {
        static::updating(function ($tripApply) {
            if ($tripApply->isDirty('order_number')) {
                $newOrderNumber = $tripApply->order_number;

                // 更新中間表
                DB::table('order_has_apply')
                    ->where('trip_apply_id', $tripApply->id)
                    ->update(['trip_order_on' => $newOrderNumber]);
            }
        });
    }

    public function Trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'mould_id', 'id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            TripOrder::class,
            'order_has_apply',
            'trip_apply_id',
            'trip_order_on',
            'id',
            'order_number'
        );
    }

    /**
     * 需要加密的欄位列表
     *
     * @var array
     */
    protected array $encryptedFields = [
        'email', 'phone', 'id_card', 'PassPort', 'emContactPh'
    ];

    /**
     * 設置屬性時自動加密
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value): void
    {
        if (in_array($key, $this->encryptedFields) && !empty($value)) {
            // 生成並儲存雜湊值
            $hashKey = $key . '_hash';
            $hashValue = hash('sha256', $value);
            parent::setAttribute($hashKey, $hashValue);

            $value = ShortCrypt::encrypt($value);

        }
        parent::setAttribute($key, $value);


    }

    /**
     * 獲取屬性時自動解密
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptedFields) && $value) {
            return ShortCrypt::decrypt($value);
        }
        return $value;
    }
}
