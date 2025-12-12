<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'mda_id',
        'department_name',
        'department_code',
        'hod_id'
    ];

    public function mda()
    {
        return $this->belongsTo(Mda::class);
    }

    public function hod()
    {
        return $this->belongsTo(Employee::class, 'hod_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
