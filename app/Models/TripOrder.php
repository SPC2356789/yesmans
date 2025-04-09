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
    protected $appends = ['applies_count', 'total_amount', 'lave_amount'];

    public function getAppliesCountAttribute()
    {
        $applies = json_decode($this->applies, true);
        return count($applies);
    }

    public function getTotalAmountAttribute()
    {
        $applies = json_decode($this->applies, true);
        return is_array($applies) ? $this->applies_count * $this->amount : $this->amount;
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
//    public function times():belongsToMany
//    {
//        return $this->belongsToMany(
//            TripTime::class,
//            'time_has_order',
//            'trip_order_id',
//            'trip_times_uuid',
//            'id',
//            'uuid'
//        );
//    }
}
