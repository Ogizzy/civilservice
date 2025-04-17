<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Step extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'step',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'step_id');
    }

    public function previousPromotions()
    {
        return $this->hasMany(PromotionHistory::class, 'previous_step');
    }

    public function currentPromotions()
    {
        return $this->hasMany(PromotionHistory::class, 'current_step');
    }
}
