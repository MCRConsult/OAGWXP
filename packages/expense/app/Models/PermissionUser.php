<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionUser extends Model
{
    protected $table = 'oagwxp_permission_user';
    protected $connection = 'oracle_oagwxp';
    protected $appends = ['perm_code'];

    public function getPermCodeAttribute()
    {
        return $this->permission->permission_code;
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(App\User::class, 'id', 'user_id');
    }
}
