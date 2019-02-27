<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shirt extends Model
{
    protected $fillable = [
        'user_id','shirt_cut','shirt_size','ausgeber','transaction_id'
    ];

    public function user() {
        return $this->belongsTo('App\User'); //Owner
    }
}
