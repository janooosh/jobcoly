<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shiftgroup extends Model
{
    protected $fillable = [
        'name','subtitle','description'
    ];

    public function shifts() {
        return $this->hasMany('App\Shift');
    }
}
