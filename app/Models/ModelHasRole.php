<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;
    // 定義可批量賦值的欄位
    protected $fillable = ['model_id', 'role_id', 'model_type']; // 確保 model_type 在這裡
    public $timestamps = false; // 關閉時間戳記
}
