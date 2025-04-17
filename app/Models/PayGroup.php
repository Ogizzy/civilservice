<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayGroup extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'paygroup',
        'paygroup_code',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'paygroup_id');
    }
}
