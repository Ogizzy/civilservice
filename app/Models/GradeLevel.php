<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeLevel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'level',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'level_id');
    }

    public function previousPromotions()
    {
        return $this->hasMany(PromotionHistory::class, 'previous_level');
    }

    public function currentPromotions()
    {
        return $this->hasMany(PromotionHistory::class, 'current_level');
    }
}
