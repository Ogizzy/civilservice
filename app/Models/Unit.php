<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
     protected $fillable = [
        'department_id',
        'unit_name',
        'unit_code',
        'unit_head_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function unitHead()
    {
        return $this->belongsTo(Employee::class, 'unit_head_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
