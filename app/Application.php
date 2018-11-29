<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'shift_id','user_id','status','notes', 'motivation','expiration','experience','notes'
    ];

    public function shift() {
        return $this->belongsTo('App\Shift');
    }
    public function applicant() {
        return $this->belongsTo('App\User','user_id');
    }

}
