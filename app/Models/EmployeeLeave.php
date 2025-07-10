<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeLeave extends Model implements Auditable

{
   use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'leave_number',
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'contact_address',
        'contact_phone',
        'emergency_contact',
        'emergency_phone',
        'supporting_document_url',
        'supporting_document_name',
        'status',
        'remarks',
        'applied_date',
        'approved_by',
        'approved_at',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applied_date' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->leave_number = self::generateLeaveNumber();
            $model->applied_date = now()->toDateString();
            $model->total_days = self::calculateTotalDays($model->start_date, $model->end_date);
        });
    }

    public static function generateLeaveNumber()
    {
        $year = date('Y');
        $lastLeave = self::whereYear('created_at', $year)->latest()->first();
        $sequence = $lastLeave ? (int)substr($lastLeave->leave_number, -4) + 1 : 1;
        
        return 'LV' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function calculateTotalDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        return $start->diffInDays($end) + 1;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

        public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

        
}
