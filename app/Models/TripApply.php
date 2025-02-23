<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripApply extends BaseModel
{
    use HasFactory;
    // 定義可以進行批量賦值的欄位
    use SoftDeletes;


    public function Trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'mould_id', 'id');
    }
    public function orders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TripOrder::class, 'order_has_apply', 'trip_apply_id', 'trip_order_id');
    }

}
