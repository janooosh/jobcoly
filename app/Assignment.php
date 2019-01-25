<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'shift_id','user_id','application_id','status','start','end','confirmed','notes_manager'
    ];

    public function shift() {
        return $this->belongsTo('App\Shift');
    }
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function salarygroup() {
        return $this->belongsTo('App\Salarygroup');
    }
}
