<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salarygroup extends Model
{
    protected $fillable = [
        'g','a','p','t_a','t_g','confirmed'
    ];

    public function assignments() {
        return $this->hasMany('App\Assignment');
    }
}
