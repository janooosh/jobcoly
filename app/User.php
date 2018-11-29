<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'firstname', 'surname', 'is_student','is_olydorf','shirt_cut','shirt_size','birthday','mobile','studiengang','uni','semester','oly_cat','oly_room','is_verein','is_bierstube','is_disco','is_praside','is_dauerjob','ausschuss','street','hausnummer','plz','ort','is_ehemalig','about_you','has_gesundheitszeugnis'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function applications() {
        return $this->hasMany('App\Application');
    }
    public function manager_shifts() {
        return $this->hasMany('App\Privilege')->where('role','Manager');
    }
    public function supervisor_shifts() {
        return $this->hasMany('App\Privilege')->where('role','Supervisor');
    }
    public function activeAssignments() {
        return $this->hasMany('App\Assignment')->where('status','Aktiv');
    }
    public function activeApplications() {
        return $this->hasMany('App\Application')->where('status','Aktiv');
    }
    public function rejectedApplications() {
        return $this->hasMany('App\Application')->where('status','Rejected');
    }
}
