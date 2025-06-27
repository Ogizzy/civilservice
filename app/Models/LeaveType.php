<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class LeaveType extends Model implements Auditable
{
     use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'code',
        'description',
        'max_days_per_year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employeeLeaves()
    {
        return $this->hasMany(EmployeeLeave::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(EmployeeLeaveBalance::class);
    }
    
}
