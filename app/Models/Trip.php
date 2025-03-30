<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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


    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category', 'id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'id', 'carousel');
    }

    public function trip_times(): HasMany
    {
        return $this->hasMany(TripTime::class, 'mould_id', 'id'); // TripTime 表的 mould_id 對應 Order 的 id
    }

    /**
     * 1為recent，2為upcoming
     * with trip_times 為資料關聯 when是資料顯示
     */

    public static function getData($cate = '', $term = '', $tags = '')
    {
        $trips = Trip::selectRaw('id, title,subtitle, category,carousel,tags,slug,icon,amount,quota,seo_description,seo_title')

            ->with(['trip_times' => function ($query) use ($cate) {
                $query
                    ->select('uuid', 'mould_id', 'date_start', 'date_end', 'quota', 'amount')
                    ->when($cate !== 2, function ($query) {
                        $query->orderBy('date_start', 'asc'); // 按照時間由早到晚排序
                    })
                    ->when($cate == 1, function ($query) {
                        $query->whereBetween('date_start', [now()->toDateString(), now()->addMonth()->toDateString()]); // 未來一個月
                    })
                    ->when($cate == 2, function ($query) {
                        $query->orderByRaw('(CAST(quota AS SIGNED) - CAST(applied_count AS SIGNED)) ASC');
                    })
                    ->where('date_start', '>=', now()->startOfDay())// 只選擇今天或以後的日期
                    ->selectRaw(TripTime::getDateLogic())
                    ->where('is_published', 1)
                    ->selectRaw(TripTime::getAppliedCount())

                ; // 預載 Orders 和 applies 關係
                ;
            }])
            ->with(['categories' => function ($query) {
                $query->select('id', 'slug', 'name'); // 只取得需要的字段
            }])
            ->whereHas('trip_times', function ($query) use ($cate) {
                // 檢查是否有符合條件的 TripTime 資料
                $query
                    ->where('date_start', '>=', now()->startOfDay())// 只選擇今天或以後的日期
                    ->when($cate == 1, function ($query) {
                        $query->whereBetween('date_start', [now()->toDateString(), now()->addMonth()->toDateString()]); // 未來一個月
                    })
                    ->when($cate == 2, function ($query) {

                    })
                    ->where('is_published', 1)

                ;
            })
            ->when($term !== '', function ($query) use ($term, $tags) {
                $query->where(function ($query) use ($term, $tags) {
                    $query->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('subtitle', 'LIKE', '%' . $term . '%');
                });
            })
            ->when($tags !== '', function ($query) use ($tags) {

                foreach ($tags as $tag) {

                    $query->whereRaw("JSON_CONTAINS(tags, ?)", ['"' . $tag . '"']);

                }

            })
            ->when(
                !in_array($cate, [2, 1]),
//                fn($query) => $query->where('category', $cate),
                fn($query) => $query->where('category', $cate),
            )
            ->where('is_published', 1)
        ;


        return $trips;
    }

    public static function getTrip($trip = null, $tripTime_uuid = null)
    {
        // 建立查詢
        $query = self::selectRaw('*')
            ->when($tripTime_uuid, function ($query, $tripTime_uuid) {
                // 只有在 $tripTime_uuid 存在時才加上 whereHas
                return $query->whereHas('trip_times', function ($query) use ($tripTime_uuid) {
                    $query
                        ->where('uuid', $tripTime_uuid)
                        ->where('is_published', 1)
                        ->where('date_start', '>=', now()->startOfDay())// 只選擇今天或以後的日
                    ;

                });
            })
            ->with(['trip_times' => function ($query) {
                $query
                    ->select('*')
                    ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
                    ->selectRaw(TripTime::getDateLogic())
                    ->where('is_published', 1)
                    ->where('date_start', '>=', now()->startOfDay())// 只選擇今天或以後的日期
                    ->selectRaw(TripTime::getAppliedCount())
                ;
            }])
            ->where('slug', $trip);

        // 檢查是否存在，但不影響原始查詢
        if ($trip !== null && !$query->exists()) {
            abort(404, 'Not Found');
        }

        // 執行查詢並返回第一筆
        return $query->first();
    }


    public static function getData_form()
    {
        $query = self::selectRaw('*')
            ->where('is_published', 1)
            ->orderBy('orderby', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->title . '-' . $item->subtitle];
            });//抓有開啟的
        return $query->toArray();

    }

}
