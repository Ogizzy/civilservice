<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'previous_level',
        'previous_step',
        'current_level',
        'current_step',
        'promotion_type',
        'effective_date',
        'supporting_document',
        'user_id',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function previousLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'previous_level');
    }

    public function previousStep()
    {
        return $this->belongsTo(Step::class, 'previous_step');
    }

    public function currentLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'current_level');
    }

    public function currentStep()
    {
        return $this->belongsTo(Step::class, 'current_step');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'supporting_document');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function previousGradeLevel()
{
    return $this->belongsTo(GradeLevel::class, 'previous_grade_level_id');
}

public function currentGradeLevel()
{
    return $this->belongsTo(GradeLevel::class); // Adjust foreign key if needed
}

}
