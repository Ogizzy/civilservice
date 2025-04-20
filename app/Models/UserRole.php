<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'role',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(UserPermission::class, 'role_id');
    }

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class, 'role_id');
    }

    // In UserRole.php
// public function permissions()
// {
//     return $this->belongsToMany(Permission::class, 'permission_role');
// }

}
