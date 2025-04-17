<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PlatformFeature extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'feature',
        'description',
    ];

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class, 'feature_id');
    }
}
