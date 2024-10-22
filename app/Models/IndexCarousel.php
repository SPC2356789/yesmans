<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndexCarousel extends Model
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
    protected $casts = [
        'image_path' => 'array',//轉URL
    ];
    protected $dates = ['deleted_at']; // 必須加這行才有軟刪除

    // 可選：定義關聯
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 取出輪播.
     */
    public function Carousels()
    {
//        $url = Carousel::where('status', '=', 1)->pluck('image_path')->toArray();//抓有開啟的
        $data = self::selectRaw('*')
            ->orderBy('orderby', 'asc')
            ->where('status', 1)
            ->get()
            ->toArray();//抓有開啟的
        return $data;
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
