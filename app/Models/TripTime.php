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

    public static function getData($cate = '*', $term = '')
    {
        return TripTime::selectRaw('uuid, mould_id')
            ->with(['Trip' => function ($query) {
                $query->select('id','slug','title','subtitle','icon','slug','carousel','tags','is_published'); // 只取得需要的字段
            }])
            ->when(!in_array($cate, ['*', 'recent', 'upcoming']), function ($query, $term) use ($cate) {
                // 根據 Categories 的 slug 查找對應的 Trip
                $query->whereHas('Trip', function ($query) use ($cate) {
                    // 假設 Trip 中的 category 字段是 category_id，對應 Categories 的 id
                    $query->whereHas('category', function ($query) use ($cate) {
                        // 根據 slug 過濾 Categories
                        $query->where('slug', $cate);
                    });
                });
            })
            ->when($cate === 'recent', function ($query) {
                $query->whereDate('date_start', '>=', now()->subMonth()->toDateString() // 最近一個月
                );
            })
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('title', 'LIKE', '%' . $term . '%')
                        ->orWhere('content', 'LIKE', '%' . $term . '%');
                });
            })
            ->whereDate('date_start', '>=', now()->toDateString())// 選擇今天或以後的日期
            ->orderBy('date_start', 'asc')
            ->orderBy('mould_id', 'asc')
            ->selectRaw('CONCAT(DATE_FORMAT(date_start, "%Y-%m-%d")," (",
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
                                ) as date')
//            ->leftJoin('categories', 'blog_items.category_id', '=', 'categories.id') // JOIN categories 表
//            ->select('blog_items.*', 'categories.slug as category_slug') // 選擇 blog_items 的所有欄位並加上 slug

//            ->toarray()
            ;

    }

    protected static function booted()
    {
//        static::saving(function ($model) {
//          dd($model);
//        });
    }
}
