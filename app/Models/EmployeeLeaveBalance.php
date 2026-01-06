<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeLeaveBalance extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'entitled_days',
        'used_days',
        'remaining_days',
        'status',
        'approval_stage',
        'hod_approved_by',
        'hod_approved_at',
        'hod_remarks',
        'mda_approved_by',
        'mda_head_approved_at',
        'mda_head_remarks',
        'approved_by',
        'approved_at',
        'remarks',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->remaining_days = $model->entitled_days - $model->used_days;
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    
}
