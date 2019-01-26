<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id','amount','issuer_id','datetime','beschreibung_short','beschreibung'
    ];
    public function issuer() {
        return $this->belongsTo('App\User','issuer_id');
    }
    public function user() {
        return $this->belongsTo('App\User'); //Empfänger
    }
}
