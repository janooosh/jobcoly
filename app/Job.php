<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'name','short','description','gesundheitszeugnis','awe','gutscheine','is_extern','p'
    ];

    public function shifts() {
        return $this->hasMany('App\Shift');
    }
}
