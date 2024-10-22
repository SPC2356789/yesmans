<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission as pms;
class RoleHasPermissions extends Model
{
    use HasFactory;
    protected $table = 'role_has_permissions'; // 确保使用正确的表名

    public function permission()
    {
        return $this->belongsTo(pms::class); // 关联到 Permission
    }

}
