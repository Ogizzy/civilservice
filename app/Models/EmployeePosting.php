<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeePosting extends Model
{
    protected $fillable = [
        'employee_id',
        'mda_id',
        'department_id',
        'unit_id',
        'posting_type',
        'posted_at',
        'ended_at',
        'remarks',
        'posted_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function fromMda()
    {
        return $this->belongsTo(MDA::class, 'mda_id');
    }

    public function toMda()
    {
        return $this->belongsTo(MDA::class, 'mda_id');
    }

    public function fromDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function postings()
    {
        return $this->hasMany(EmployeePosting::class);
    }


    // Relationship to MDA
    public function mda()
    {
        return $this->belongsTo(Mda::class);
    }

    // Relationship to user who posted
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    // Optional: relationships to department/unit
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    
}
