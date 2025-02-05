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

    public function categories(): BelongsTo
    {
        return $this->belongsTo(categories::class, 'category', 'id');
    }

    public function trip_times(): HasMany
    {
        return $this->hasMany(TripTime::class, 'mould_id', 'id'); // TripTime 表的 mould_id 對應 Trip 的 id
    }

    /**
     * 999為recent，998為upcoming
     */

    public static function getData($cate = '', $term = '', $tags = '', $month = '')
    {
        return Trip::selectRaw('id, title,subtitle, category,carousel,tags,slug,icon')
            ->with(['trip_times' => function ($query) use ($cate) {
                $query
                    ->select('uuid', 'mould_id', 'date_start', 'date_end', 'quota', 'applied_count')
                    ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
//                    ->whereDate('date_start', '>=', now()->toDateString()) // 只選擇今天或以後的
                    ->when($cate == 999, function ($query) {
                        $query->whereBetween('date_start', [now()->toDateString(), now()->addMonth()->toDateString()]); // 未來一個月
                    })
                    ->when($cate == 998, function ($query) {
                        // 只有當 $A = 1 時才加這個篩選條件
                        $query->whereRaw('(quota - applied_count) <= 3');
                    })
                    ->selectRaw(TripTime::getDateLogic())
                    ->where('is_published', 1);
            }])
            ->with(['categories' => function ($query) {
                $query->select('id', 'slug', 'name'); // 只取得需要的字段
            }])
//            ->when(!in_array($cate, ['*', '999', '998']), function ($query) use ($cate) {
//                $query->whereHas('categories', fn($query) => $query->where('id', $cate));
//            })
            ->when($cate === 999, function ($query) {
                $query->whereHas('trip_times', fn($query) => $query->whereDate('date_start', '>=', now()->subMonth()->toDateString()) // 最近一個月
                );
            })
            ->when($cate === 998, function ($query) {
                $query->whereHas('trip_times', fn($query) => $query->whereRaw('(quota - applied_count) <= 3') // 剩餘名額小於等於 3
                );
            })
            ->whereHas('trip_times', function ($query) use ($cate) {
                // 檢查是否有符合條件的 TripTime 資料
                $query
                    ->whereDate('date_start', '>=', now()->toDateString()) // 只選擇今天或以後的日期
                    ->when($cate == 999, function ($query) {
                        $query->whereBetween('date_start', [now()->toDateString(), now()->addMonth()->toDateString()]); // 未來一個月
                    })
                    ->when($cate == 998, function ($query) {
                        // 只有當 $A = 1 時才加這個篩選條件
                        $query->whereRaw('(quota - applied_count) <= 3');
                    })
                    ->Where('is_published', 1)//                    ->selectRaw(TripTime::getDateLogic())
                ;
            })
            ->when($term !== '', function ($query) use ($term, $tags) {
                $query->where(function ($query) use ($term, $tags) {
                    $query->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('subtitle', 'LIKE', '%' . $term . '%');
                });
            })
            ->when($tags !== '', function ($query) use ($tags) {

                foreach ($tags as $index => $tag) {

                    $query->whereRaw("JSON_CONTAINS(tags, ?)", ['"' . $tag . '"']);

                }

            })
            ->when(
                !in_array($cate, [998, 999]),
                fn($query) => $query->where('category', $cate),
            )
            ->where('is_published', 1)

//            ->whereDate('date_start', '>=', now()->toDateString())// 選擇今天或以後的日期
//            ->orderBy('orderby', 'asc')
//            ->leftJoin('categories', 'trips.category', '=', 'categories.id') // JOIN categories 表
//            ->select('id','title', 'categories.slug as category_slug') // 選擇 blog_items 的所有欄位並加上 slug
//            ->with(['carousel', 'tags']) // 這裡提前加載 carousel 和 tags 關聯
//            ->get()
//            ->toarray()
            ;

    }

    public static function getTrip($trip, $term = '')
    {
//      dd($trip);
        return self::selectRaw('*')
            ->with(['trip_times' => function ($query) {
                $query
                    ->select('*')
                    ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
                    ->selectRaw(TripTime::getDateLogic())
//                    ->whereDate('date_start', '>=', now()->toDateString()) // 只選擇今天或以後的
                    ->where('is_published', 1);

            }])
            ->where('slug', $trip)
            ->get()
            ->first();

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
