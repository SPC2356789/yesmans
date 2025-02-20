<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TripTime extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    protected $casts = [
        'date' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
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
        $trips = TripTime::selectRaw('uuid, mould_id,quota,applied_count')
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
                $query->orderByRaw('(CAST(quota AS SIGNED) - CAST(applied_count AS SIGNED)) ASC');

            })
            ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
            ->orderBy('mould_id', 'asc')

            ->where('is_published', 1)


            ->selectRaw(self::getDateLogic())
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

    protected static function booted()
    {
//        static::saving(function ($model) {
//          dd($model);
//        });
    }
}
