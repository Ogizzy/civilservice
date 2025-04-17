<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommendationAward extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'award',
        'awarding_body',
        'award_date',
        'supporting_document',
        'user_id',
    ];

    protected $casts = [
        'award_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'supporting_document');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
