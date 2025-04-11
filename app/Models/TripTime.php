<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TripTime extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
// 當記錄從資料庫載入時觸發
        static::retrieved(function ($model) {
            $model->date_range = $model->date_start . ' to ' . $model->date_end;
            $model->applied_count=$model->orders->sum(fn ($order) => $order->applies->count())??0;
        });
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
        static::saving(function ($model) {

            $dates =$model->date_range;
            $model->date_start = $dates[0] ?? null;
            $model->date_end = $dates[1] ?? $dates[0];
            unset($model->date_range);
        });
    }

    protected static function booted()
    {


    }

//    public function Orders(): belongsToMany
//    {
//        return $this->belongsToMany(
//            TripOrder::class,
//            'time_has_order',
//            'trip_times_uuid',
//            'trip_order_id',
//            'uuid',
//            'id' // 用 trip_uuid 作為目標鍵
//        );
//    }
    public function orders(): HasMany
    {
        return $this->hasMany(TripOrder::class, 'trip_time_uuid', 'uuid');
    }

    public function Trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'mould_id', 'id');
    }

    /**
     * 這裡$cate以slug識別，與trip用slug不同
     */
    public static function getData($cate = '*')
    {
        $trips = TripTime::selectRaw('uuid, mould_id,quota')
            ->with('Orders.applies')
            ->with(['Trip' => function ($query) {
                $query->select('id', 'slug', 'title', 'subtitle', 'icon', 'slug', 'carousel', 'tags', 'is_published');
            }])
            ->when(!in_array($cate, ['*', 'recent', 'upcoming']), function ($query, $term) use ($cate) {
                // 根據 Categories 的 slug 查找對應的 Trip
                $query->whereHas('Trip', function ($query) use ($cate) {
                    // 假設 Trip 中的 category 字段是 category_id，對應 Categories 的 id
                    $query->whereHas('categories', function ($query) use ($cate) {
                        // 根據 slug 過濾 Categories
                        $query->where('slug', $cate);
                    });
                });
            })
            ->when($cate == 'recent', function ($query) {
                $query->whereBetween('date_start', [now()->toDateString(), now()->addMonth()->toDateString()]); // 未來一個月
            })
            ->when($cate == 'upcoming', function ($query) {
                $query->selectRaw(self::getAppliedCount())
                    ->orderByRaw('(CAST(quota AS SIGNED) - CAST(applied_count AS SIGNED)) ASC');
                ;
            })
            ->selectRaw(self::getAppliedCount())
            ->whereRaw('CAST(quota AS SIGNED) > ' . self::getAppliedCountRaw()) // 用純子查詢
            ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
            ->orderBy('mould_id', 'asc')
            ->where('is_published', 1)
            ->selectRaw(self::getDateLogic())
            ->where('date_start', '>=', now()->startOfDay())// 只選擇今天或以後的日期
            ->get(); // 🔥 這裡先執行查詢，獲取結果

        $trips->each(function ($trip) {
            // 查詢 `Media` 表，取得對應圖片的路徑
            $mediaCarousel = Media::whereIn('id', $trip->Trip->carousel)
                ->pluck('path', 'id')
                ->toArray();
            $newMediaCarousel = array_map(fn($id) => $mediaCarousel[$id] ? Storage::url($mediaCarousel[$id]) : null, $trip->Trip->carousel);

            $mediaIcon = Media::where('id', $trip->Trip->icon)
                ->value('path'); // 只取出 path
            $newMediaIcon = Storage::url($mediaIcon);

            $tags = Categories::whereIn('id', $trip->Trip->tags)
                ->pluck('name', 'id')
                ->toArray();
            $newTags = array_map(fn($id) => $tags[$id] ? $tags[$id] : null, $trip->Trip->tags);


            $trip->Trip->forceFill([
                'carouselSpell' => $newMediaCarousel,
                'iconSpell' => $newMediaIcon,
                'tagSpell' => $newTags
            ]);
        });
        return $trips;
    }
    /**
     * 取得報名人數（不含別名）
     */
    public static function getAppliedCountRaw(): string
    {
        return "(
        SELECT COUNT(*)
        FROM trip_applies
        WHERE trip_applies.id IN (
            SELECT trip_apply_id
            FROM order_has_apply
            WHERE order_has_apply.trip_order_on IN (
                SELECT order_number
                FROM trip_orders
                WHERE trip_orders.id IN (
                    SELECT trip_order_id
                    FROM time_has_order
                    WHERE time_has_order.trip_times_uuid = trip_times.uuid
                )
            )
        )
    )";
    }
    /**
     * 取得報名人數
     */
    public static function getAppliedCount(): string{
        return "(
            SELECT COUNT(*)
            FROM trip_applies
            WHERE trip_applies.id IN (
                SELECT trip_apply_id
                FROM order_has_apply
                WHERE order_has_apply.trip_order_on IN (
                    SELECT order_number
                    FROM trip_orders
                    WHERE trip_orders.id IN (
                        SELECT trip_order_id
                        FROM time_has_order
                        WHERE time_has_order.trip_times_uuid = trip_times.uuid
                    )
                )
            )
        ) AS applied_count";
    }
    public static function getDateLogic(): string
    {
        return 'CONCAT(
    DATE_FORMAT(IFNULL(date_start, NOW()), "%Y-%m-%d"), " (",
    CASE DAYOFWEEK(IFNULL(date_start, NOW()))
        WHEN 1 THEN "週日"
        WHEN 2 THEN "週一"
        WHEN 3 THEN "週二"
        WHEN 4 THEN "週三"
        WHEN 5 THEN "週四"
        WHEN 6 THEN "週五"
        WHEN 7 THEN "週六"
    END, ")",
    CASE
        WHEN DATE(IFNULL(date_start, NOW())) = DATE(IFNULL(date_end, NOW())) THEN " 單攻"
        ELSE CONCAT(" to ",
            DATE_FORMAT(IFNULL(date_end, NOW()), "%Y-%m-%d"), " (",
            CASE DAYOFWEEK(IFNULL(date_end, NOW()))
                WHEN 1 THEN "週日"
                WHEN 2 THEN "週一"
                WHEN 3 THEN "週二"
                WHEN 4 THEN "週三"
                WHEN 5 THEN "週四"
                WHEN 6 THEN "週五"
                WHEN 7 THEN "週六"
            END, ")"
        )
    END
) AS dateAll
';
    }


}
