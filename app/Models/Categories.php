<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at']; // 必須加這行才有軟刪除
    protected $fillable = ['name', 'slug', 'type', 'area', 'seo_title', 'seo_description', 'seo_image'];

    public static function getData($area = 1, $type = 1, $mlt = "*", $key = 'id', $chkUrl = null)
    {
        ;

        $query = self::selectRaw($mlt)
            ->where('type', $type)
            ->where('area', $area)
            ->where('status', 1)

            ->orderBy('orderby', 'asc');

        // 檢查是否存在，但不影響原始查詢
        if ($chkUrl !== null) {
            $checkQuery = clone $query; // 複製查詢
            if (!$checkQuery->where('slug', $chkUrl)->exists()) {
                abort(404, 'Not Found');
            }
        }
//        return $query->get();
        return match (true) {
            $area && $type == 2 && $mlt == "*", $area == 2 && $type == 1 && $mlt == "*" => $query->get(),
            default => $query->get()->pluck('name', $key),
        };


    }

    public static function getData_mlt($id = null)
    {
        $data = self::selectRaw('*')
            ->where('id', $id)
            ->where('status', 1)
            ->get()
            ->firstOrFail();
        return $data;
    }
}
