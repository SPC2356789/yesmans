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

    public static function getData($area = 1, $type = 1, $mlt = "*", $key = 'id')
    {
        $data = self::selectRaw($mlt)
            ->where('type', $type)
            ->where('area', $area)
            ->where('status', 1)

            ->orderBy('orderby', 'asc')
            ->get()
            ->pluck('name', $key) // 使用 pluck 获取键值对
            ->toArray();//抓有開啟的
        return $data;
    }
}
