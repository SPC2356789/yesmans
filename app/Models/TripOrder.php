<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripOrder extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    /**
     * Get the trip times associated with this model.
     * Note: trip_uuid是tripTimes 的id.
     *
     * @return HasMany
     */
    public function trip_times(): HasMany
    {
        return $this->hasMany(TripTime::class, 'uuid', 'trip_uuid');
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
        return $this->belongsToMany(TripApply::class, 'order_has_apply', 'trip_order_id', 'trip_apply_id');
    }
}
