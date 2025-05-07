<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MDA extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'mdas';

    protected $fillable = [
        'mda',
        'mda_code',
        'status',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'mda_id');
    }

    public function previousTransfers()
    {
        return $this->hasMany(TransferHistory::class, 'previous_mda');
    }

    public function currentTransfers()
    {
        return $this->hasMany(TransferHistory::class, 'current_mda');
    }
}
