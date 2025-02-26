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
    protected static function booted()
    {
//        static::updating(function ($tripOrder) {
//            if ($tripOrder->isDirty('order_number')) {
//                $order_on =$tripOrder->getOriginal('order_number');
//                $New_order_on =$tripOrder->order_number;
////
////                DB::table('order_has_apply')
////                    ->where('trip_order_on', $order_on)
////                    ->update(['trip_order_on' => $New_order_on]);
//            }
//        });
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
