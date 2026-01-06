<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'mda_id',
        'paygroup_id',
        'level_id',
        'step_id',
        'department_id',
        'unit_id',
        'employee_number',
        'surname',
        'first_name',
        'middle_name',
        'email',
        'password',
        'phone',
        'contact_address',
        'dob',
        'gender',
        'marital_status',
        'religion',
        'first_appointment_date',
        'confirmation_date',
        'present_appointment_date',
        'retirement_date',
        'rank',
        'lga',
        'qualifications',
        'net_pay',
        'passport',
        'user_id',
        'state_id',
        'password',
        'passport_public_id',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
        'next_of_kin_address',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'dob' => 'date',
        'first_appointment_date' => 'date',
        'confirmation_date' => 'date',
        'present_appointment_date' => 'date',
        'retirement_date' => 'date',
        'net_pay' => 'decimal:2',
        'password' => 'hashed',
    ];

    public function mda()
    {
        return $this->belongsTo(MDA::class, 'mda_id');
    }

    public function payGroup()
    {
        return $this->belongsTo(PayGroup::class, 'paygroup_id');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'level_id');
    }

    public function step()
    {
        return $this->belongsTo(Step::class, 'step_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function transferHistories()
    {
        return $this->hasMany(TransferHistory::class, 'employee_id');
    }

    public function promotionHistories()
    {
        return $this->hasMany(PromotionHistory::class, 'employee_id');
    }

    public function commendationAward()
    {
        return $this->hasMany(CommendationAward::class, 'employee_id');
    }

    public function queriesMisconduct()
    {
        return $this->hasMany(QueriesMisconduct::class, 'employee_id');
    }

    public function getFullNameAttribute()
{
    return "{$this->surname} {$this->first_name} {$this->middle_name}";
}

public function state()
{
    return $this->belongsTo(State::class, 'state_id');
}

public function lga()
{
    return $this->belongsTo(LGA::class);
}

public function leaves()
{
    return $this->hasMany(\App\Models\EmployeeLeave::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}

public function unit()
{
    return $this->belongsTo(Unit::class);
}


public function departmentHeaded()
    {
        return $this->hasOne(Department::class, 'hod_id');
    }

    public function unitHeaded()
    {
        return $this->hasOne(Unit::class, 'unit_head_id');
    }

    public function isHod(): bool
    {
        return $this->departmentHeaded()->exists();
    }

    public function isUnitHead(): bool
    {
        return $this->unitHeaded()->exists();

    }
    
public function postings()
{
    return $this->hasMany(EmployeePosting::class);
}



}
