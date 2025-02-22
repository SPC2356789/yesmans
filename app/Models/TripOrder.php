<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function tripTimes(): HasMany
    {
        return $this->hasMany(TripTime::class, 'uuid', 'trip_uuid');
    }

}
