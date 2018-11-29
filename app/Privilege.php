<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $fillable = [
        'shift_id','user_id','role'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
    public function shift() {
        return $this->belongsTo('App\Shift');
    }
}
