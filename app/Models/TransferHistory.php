<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'previous_mda',
        'current_mda',
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

    public function previousMDA()
    {
        return $this->belongsTo(MDA::class, 'previous_mda');
    }

    public function currentMDA()
    {
        return $this->belongsTo(MDA::class, 'current_mda');
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
