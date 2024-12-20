<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static  function getData()
    {
        return self::selectRaw('*')
            ->orderBy('orderby', 'asc')
            ->where('status', 1)
            ->get()
            ->toArray();
    }
}
