<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category', 'id');
    }
//    public function tags(): BelongsToMany
//    {
//        return $this->belongsToMany(Categories::class, 'trips', 'tags', 'id');
//    }

    public function trip_times(): HasMany
    {
        return $this->hasMany(TripTime::class, 'mould_id', 'id'); // TripTime 表的 mould_id 對應 Trip 的 id
    }
// 在 Trip 模型中新增 carousel 關聯
//    public function carousel():BelongsToMany
//    {
//        return $this->belongsToMany(Media::class, 'trips', 'carousel', 'id'); // 加上別名;
//    }
    public static function getData($cate = '*', $term = '')
    {
        return Trip::when($cate !== '*', function ($query, $term) use ($cate) {
            // 假設 Trip 中的 category 字段是 category_id，對應 Categories 的 id
            $query->whereHas('category', function ($query) use ($cate) {
                // 根據 slug 過濾 Categories
                $query->where('slug', $cate);
            });

        })
            ->whereHas('trip_times', function ($query) {
                // 檢查是否有符合條件的 TripTime 資料
                $query->whereDate('date_start', '>=', now()->toDateString())
                    ->Where('is_published', 1);; // 只選擇今天或以後的日期
            })
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('subtitle', 'LIKE', '%' . $term . '%');
                });
            })
            ->where('is_published', 1)
//            ->whereDate('date_start', '>=', now()->toDateString())// 選擇今天或以後的日期
//            ->orderBy('orderby', 'asc')
//            ->leftJoin('categories', 'blog_items.category_id', '=', 'categories.id') // JOIN categories 表
//            ->select('blog_items.*', 'categories.slug as category_slug') // 選擇 blog_items 的所有欄位並加上 slug
//            ->with(['carousel', 'tags']) // 這裡提前加載 carousel 和 tags 關聯
//            ->get()
//            ->toarray()
            ;

    }

    public static function getData_form()
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
