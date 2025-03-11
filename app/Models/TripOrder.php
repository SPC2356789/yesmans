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

            $applies = json_decode($model->applies, true); // 將 JSON 轉為陣列
            $amount = $model->amount; // 獲取表單中用戶輸入的 amount
            $model->total_amount = is_array($applies) ? count($applies) * $amount : $amount;
            $model->lave_amount = $model->total_amount  - $model->paid_amount;
        });
        static::saving(function ($model) {
            unset($model->total_amount);
            unset($model->lave_amount);
        });

    }
//    /**
//     * Get the trip times associated with this model.
//     * Note: trip_uuid是tripTimes 的id.
//     *
//     * @return HasMany
//     */
//    public function trip_times(): HasMany
//    {
//        return $this->hasMany(TripTime::class, 'uuid', 'trip_uuid');
//    }

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

    public function times():belongsToMany
    {
        return $this->belongsToMany(
            TripTime::class,
            'time_has_order',
            'trip_order_id',
            'trip_times_uuid',
            'id',
            'uuid'
        );
    }
}
