<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salarygroup extends Model
{
    public function assignments() {
        return $this->hasMany('App\Assignment');
    }
}
