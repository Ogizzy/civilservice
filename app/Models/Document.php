<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    // protected $fillable = [
    //     'employee_id',
    //     'document_type',
    //     'document',
    //     'user_id',
    // ];
    
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transferHistories()
    {
        return $this->hasMany(TransferHistory::class, 'supporting_document');
    }

    public function promotionHistories()
    {
        return $this->hasMany(PromotionHistory::class, 'supporting_document');
    }

    public function commendationAward()
    {
        return $this->hasMany(CommendationAward::class, 'supporting_document');
    }

    public function queriesMisconduct()
    {
        return $this->hasMany(QueriesMisconduct::class, 'supporting_document');
    }
}
