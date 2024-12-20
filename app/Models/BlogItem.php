<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
//use App\Models\Categories;
class BlogItem extends BaseModel
{
    use HasFactory;

    use SoftDeletes;
    protected $casts = [
        'featured_image' => 'array',//轉URL
    ];

    protected $dates = ['deleted_at']; // 必須加這行才有軟刪除
    protected $fillable = [
        'title',            // 文章標題欄位
        'category_id',      // 文章分類欄位
        'slug',             // 項目代號欄位
        'is_published',     // 發佈狀態欄位
        'published_at',     // 發佈時間欄位
        'featured_image',   // 首圖上傳欄位
        'content',          // 文章內容欄位
        'seo_title',          // SEO
        'seo_description',          // SEO

    ];
    public function Categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public static  function getData($cate=null)
    {

        return BlogItem::whereHas('category', function ($query) use ($cate) {
            // 根據 category 的 slug 查找 blog_items
            $query->where('slug', $cate); // 使用傳入的 $cate 作為 slug 的過濾條件
        })->get();
//        return self::selectRaw('*')
//            ->orderBy('orderby', 'asc')
//            ->where('category_id', $cate)
//            ->where('is_published', 1)
//            ->with('category') // 這會自動加載 category 關聯資料
//            ->get()
//            ->toArray();
    }
    public static function MapData($cate=null,): array
    {
        $data = self::selectRaw('*')
            ->where('category_id', $cate)
            ->get()

            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->id}: {$item->title}"];
            })
            ->toArray();//抓有開啟的
        return $data;
    }

}
