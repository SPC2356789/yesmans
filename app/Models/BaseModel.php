<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    protected $guarded = [];

    public static  function getData()
    {
        return self::selectRaw('*')
            ->orderBy('orderby', 'asc')
            ->where('status', 1)
            ->get()
            ->toArray();
    }
}
