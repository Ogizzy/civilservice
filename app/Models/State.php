<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
     protected $fillable = ['state'];

    public function lgas()
    {
        return $this->hasMany(LGA::class);
    }

    public function employees()
{
    return $this->hasMany(Employee::class);
}

}

