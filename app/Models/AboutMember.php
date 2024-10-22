<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutMember extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at']; // 必須加這行才有軟刪除
    protected $fillable = ['status', 'name','orderby','image_path','original_image_names','introduce'];
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
