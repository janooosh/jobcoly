<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
    'job_id','shiftgroup_id','manager','supervisor','starts_at','status','ends_at','area','anzahl','active','gutscheine','awe','description','is_extern','p'
    ];

    public function job() {
        return $this->belongsTo('App\Job');
    }
    public function shiftgroup() {
        return $this->belongsTo('App\Shiftgroup');
    }
    public function activeApplications() {
        return $this->hasMany('App\Application')->where('status','Aktiv');
    }
    public function applications() {
        return $this->hasMany('App\Application');
    }
    /**
     * Careful! Following two functions return Privilege object, not User object! Can get there with foreach:: manager->user / supervisor->user
     */
    public function managers() {
        return $this->hasMany('App\Privilege')->where('role','Manager');
    }
    public function supervisors() {
        return $this->hasMany('App\Privilege')->where('role','Supervisor');
    }
    
    public function activeAssignments() {
        return $this->hasMany('App\Assignment')->where('status','Aktiv');
    }
    public function Assignments() {
        return $this->hasMany('App\Assignment');
    }   
}
