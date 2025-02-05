<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TripTime extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

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

    public static function getData($cate = '*')
    {
        return TripTime::selectRaw('uuid, mould_id,quota,applied_count')
            ->with(['Trip' => function ($query) {
                $query->select('id', 'slug', 'title', 'subtitle', 'icon', 'slug', 'carousel', 'tags', 'is_published'); // 只取得需要的字段
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
                $query->orderByRaw('(quota - applied_count) ASC');
//                $query->whereRaw('(quota - applied_count) <= 3');
            })
            ->orderBy('date_start', 'asc') // 按照時間由早到晚排序
            ->orderBy('mould_id', 'asc')
//                    ->whereDate('date_start', '>=', now()->toDateString()) // 只選擇今天或以後的
            ->where('is_published', 1)
//            ->whereDate('date_start', '>=', now()->toDateString())// 選擇今天或以後的日期
//            ->orderBy('date_start', 'asc')

            ->selectRaw(self::getDateLogic());

    }

    public static function getDateLogic(): string
    {
        return 'CONCAT(DATE_FORMAT(date_start, "%Y-%m-%d")," (",
                                   CASE DAYOFWEEK(date_start)
                                      WHEN 1 THEN "週日"
                                     WHEN 2 THEN "週一"
                                     WHEN 3 THEN "週二"
                                     WHEN 4 THEN "週三"
                                      WHEN 5 THEN "週四"
                                      WHEN 6 THEN "週五"
                                       WHEN 7 THEN "週六"
                                   END,
                                   ")",
                                   CASE
                                      WHEN DATE(date_start) = DATE(date_end) THEN " 單攻"
                                       ELSE CONCAT(" to ",
                                           DATE_FORMAT(date_end, "%Y-%m-%d"),
                                           " (",
                                         CASE DAYOFWEEK(date_end)
                                               WHEN 1 THEN "週日"
                                              WHEN 2 THEN "週一"
                                             WHEN 3 THEN "週二"
                                              WHEN 4 THEN "週三"
                                              WHEN 5 THEN "週四"
                                               WHEN 6 THEN "週五"
                                               WHEN 7 THEN "週六"
                                           END,
                                            ")"
                                        )
                                    END
                                ) as date';
    }

    protected static function booted()
    {
//        static::saving(function ($model) {
//          dd($model);
//        });
    }
}
