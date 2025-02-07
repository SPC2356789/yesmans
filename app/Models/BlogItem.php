<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected array $dates = ['deleted_at']; // 必須加這行才有軟刪除
    protected $fillable = [
        'title',            // 文章標題欄位
        'subtitle',            // 文章標題欄位
        'category_id',      // 文章分類欄位
        'active',           // 文章分類欄位
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
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image', 'id');
    }
//    public function category(): BelongsTo
//    {
//        return $this->belongsTo(Categories::class, 'category_id', 'id');
//    }

    public static function getData($cate = '*', $term = '')
    {

        return BlogItem::when($cate !== 'all', function ($query, $term) use ($cate) {
            // 根據 Categories 的 slug 查找對應的 BlogItem
            $query->whereHas('Categories', function ($query) use ($cate) {
                $query->where('slug', $cate); // 使用傳入的 $cate 來過濾 slug
            });
        })
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('subtitle', 'LIKE', '%' . $term . '%')
                        ->orWhere('content', 'LIKE', '%' . $term . '%');
                });
            })
            ->where('is_published', 1)//抓有發布的文章
            ->orderBy('orderby', 'asc')
            ->leftJoin('Categories', 'blog_items.category_id', '=', 'categories.id') // JOIN categories 表
            ->select('blog_items.*', 'categories.slug as category_slug') // 選擇 blog_items 的所有欄位並加上 slug

            ;

    }

    public static function SelectDataImg(): array
    {
        $Media = Media::getData();//取照片
        //抓有開啟的
        return self::selectRaw('*')
            ->where('is_published', 1)
            ->get()
            ->mapWithKeys(function ($item) use ($Media) {
                return [$item->id => "<span class='text-xs'>{$item->title}</span><img class='w-full' src='" . Storage::url($Media[$item->featured_image]) . "'>"];
            })
            ->toArray();
    }

    public static function MapData($cate = null,): array
    {
        $data = self::selectRaw('*')
            ->where('category_id', $cate)
            ->where('is_published', 1)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->id}: {$item->title}"];
            })
            ->toArray();//抓有開啟的
        return $data;
    }

    /**
     * 取得熱門文章的SQL語法
     */
    public static function getItem($itemSlug = null)
    {
        $data = self::selectRaw('*')
//            ->where('category_id', $cate)
            ->where('slug', $itemSlug)
//            ->where('id', $id)
            ->where('is_published', 1)
            ->firstOrFail(); // 如果找不到符合條件的資料，會拋出異常
        return $data;
    }

    /**
     * 取得熱門文章的SQL語法
     */
    public static function getHot($ids)
    {
        return self::select('title', 'subtitle', 'featured_image', 'slug', 'category_id')
            ->whereIn('blog_items.id', $ids)
            ->where('blog_items.is_published', 1)
            ->leftJoin('categories', 'blog_items.category_id', '=', 'categories.id')
            ->select('blog_items.*', 'categories.slug as category_slug')
            ->orderByRaw('FIELD(blog_items.id, ' . implode(',', $ids) . ')')  // 按照 ids 的顺序排序
            ->get();
    }

    public static function active($id): void
    {
        activity()->withoutLogs(function () use ($id) {
            $item = self::find($id)->first(); // 根據 ID 查找單一項目
            if ($item) {
                $item->active += 1;  // active 欄位加 1
                $item->save();       // 儲存更新到資料庫
            }
        });

    }

}
