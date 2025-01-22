<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripTime extends BaseModel
{   use SoftDeletes;
    use HasFactory;
    protected $casts = [
        'date' => 'array',
    ];
    public function Trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'mould_id', 'id');
    }

    protected static function booted()
    {
//        static::saving(function ($model) {
//          dd($model);
//        });
    }
}
