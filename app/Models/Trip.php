<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends BaseModel
{

    use SoftDeletes;

    // 啟用軟刪除

    use HasFactory;
    protected $casts = [
        'carousel' => 'array',
        'tags' => 'array',
    ];
    protected $dates = ['deleted_at']; // 可選，通常這會自動處理
    protected $fillable = [
        'category',       // 行程分類
        'slug',           // 唯一標識
        'title',          // 標題
        'subtitle',       // 副標題
        'carousel',       // 照片集
        'icon',           // 標籤
        'tags',           // 標籤集合
        'quota',          // 名額
        'amount',         // 價格
        'description',    // 簡介
        'content',        // 詳細內容
        'agreement_content', // 同意書內容
        'is_published',   // 發布狀態
        'orderby',        // 排序
        'seo_title',      // SEO 標題
        'seo_description' // SEO 描述
    ];
    public static  function getData()
    {
        $data = self::selectRaw('*')

            ->where('is_published', 1)
            ->orderBy('orderby', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->title . '-' . $item->subtitle];
            })
            ->toArray();//抓有開啟的
        return $data;

    }

}
