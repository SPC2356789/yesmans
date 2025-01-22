<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class IndexCarousel extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'image_path',
        'original_image_names',
        'status',
        'alt',
        // Add other fillable attributes here if any
    ];

    public function getTitleAttribute()
    {
        return json_decode($this->custom_properties)->title ?? null;
    }

    protected $casts = [
        'image_path' => 'array',//轉URL
        'custom_properties' => 'array',
    ];
    protected $dates = ['deleted_at']; // 必須加這行才有軟刪除

    public function medias(): BelongsTo
    {
        return $this->belongsTo(media::class, 'image_path', 'uuid');
    }

    // 可選：定義關聯
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }



    // 設置創建和更新者
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id(); // 設置創建者
        });
        static::deleting(function ($model) {
            $model->updated_by = auth()->id(); // 設置更新者為刪除者
            $model->saveQuietly(); // 防止觸發其他事件
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id(); // 設置更新者
        });

    }
}
