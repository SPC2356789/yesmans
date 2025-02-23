<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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
                    ->update(['trip_order_on'=> $newOrderNumber]);
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
        )->withPivot('trip_apply_order_number');
    }

}
