<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripTime extends BaseModel
{   use SoftDeletes;
    use HasFactory;
//    protected $casts = [
//        'date' => 'array',
//    ];
    public function Trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'mould_id', 'id');
    }

    public static function getData($cate = '*', $term = '')
    {
        return TripTime::when(!in_array($cate, ['*', 'recent', 'upcoming']), function ($query, $term) use ($cate) {
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
//            ->orderBy('orderby', 'asc')
//            ->leftJoin('categories', 'blog_items.category_id', '=', 'categories.id') // JOIN categories 表
//            ->select('blog_items.*', 'categories.slug as category_slug') // 選擇 blog_items 的所有欄位並加上 slug

            ->toarray()
            ;

    }
    protected static function booted()
    {
//        static::saving(function ($model) {
//          dd($model);
//        });
    }
}
