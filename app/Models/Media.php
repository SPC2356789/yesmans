<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends BaseModel
{

    use HasFactory;

    public static function getMedia($search = '', $class = 'full'): array
    {
        //抓有開啟的
        return self::selectRaw('*')
            ->where('title', 'like', '%' . $search . '%') // 查詢 title 包含 "icon"
            ->get()
            ->mapWithKeys(function ($item) use ($class) {
                $array = '';
                if ($class == 'full') {
                    $array = [$item->id => "<span class='text-xs'>{$item->title}</span><div class='relative w-full' style='padding-top: 100%;'><img class='absolute top-0 left-0 w-{$class} h-{$class} object-cover object-center rounded-t-md' src='" . Storage::url($item->path) . "'></div>"];
                } else {
                    $array = [$item->id => "<div class='w-50 flex flex-row'><img class=' w-{$class} h-{$class} ' src='" . Storage::url($item->path) . "'><span class='text-xs'>{$item->title}</span></div>"];
                }
                return $array;
            })
            ->toArray();
    }
}
