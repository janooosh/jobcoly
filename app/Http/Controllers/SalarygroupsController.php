<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Salarygroup;

class SalarygroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public static function newAssignment($assignment) {
        //Entscheidung: Anhängen oder Neu Erstellen?
        $s_p = $assignment->shift->p;
        $s_a = $assignment->shift->awe;
        $s_g = $assignment->shift->gutscheine;

        $salarygroups = Salarygroup::all();
        foreach($salarygroups as $sgroup) {
            if($sgroup->p===$s_p && $sgroup->a===$s_a && $sgroup->g===$s_g && $sgroup->user_id===$assignment->user->id) {
                //Füge Salarygroup hinzu
                $assignment->salarygroup_id = $sgroup->id;
                $assignment->save();
                return;
            }
        }
        //Neue Salarygroup
        $sg = new Salarygroup([
            'g'=>$assignment->shift->gutscheine,
            'a'=>$assignment->shift->awe,
            'p'=>$assignment->shift->p,
            't_a'=>0,
            't_g'=>0,
            'confirmed'=>0
        ]);
        $sg->save();
        $assignment->salarygroup_id = $sg->id;
        $assignment->save();
        return;
    }

    //Returns the Salarygroups of a given user id (as array of salarygroup IDs)
    public static function findSalaryGroups($uid) {
        $user = User::find($uid);

        $assignments = $user->activeAssignments;
        $salarygroups = array(); //ID of salarygroup
        //Finde salarygroup ids in assignment tabelle, füge sie dem array hinzu falls sie noch nicht drin ist
        foreach($assignments as $a) {
            if($a->salarygroup_id && $a->shift->confirmed) {
                if(!in_array($a->salarygroup_id,$salarygroups)) {
                    $salarygroups[] = $a->salarygroup_id;
                }
            }
        }

        //Return array of salarygroup ids
        return $salarygroups;
    }

    /**
     * Gets the amount of time in given salarygroups that a worker spends on gutscheine.
     * return in minutes
     */
    public static function countAttemptGutscheine($salarygroups) {
        $out = 0;
        foreach($salarygroups as $s) {
            $out+=$s->t_g;
        }
        return $out;
    }

    /**
     * gets the lowest p (Awe ab...) from a given set of salarygroups
     */
    public static function getLowestp($salarygroups) {
        $ps = array();
        foreach($salarygroups as $s) {
            $ps[]=$s->p;
        }
        return min($ps)*60; //Return in Minuten
    }

    /**
     * H:i to Minute
     */
    public static function StringToMin($str) {

        if($str=="") {
            return 0;
        }
        $h = (int)substr($str,0,2);
        $m = (int)substr($str,3,2);
        return $h*60+$m;

    }


    /**
     * Take min and display in hh:mm format (hh > 24 allowed)
     */
    public static function MinToString($min) {
       

        //$stunden = number_format($min/60,0);
        $stunden = floor($min/60);
        $minuten = $min%60;
        $negativ='';
        if($min<0) {
            $negativ = '- ';
        }
        if($stunden<0) {
            $stunden = substr($stunden,1,strlen($stunden));
        }
        if($minuten<0) {
            $minuten = substr($minuten,1,strlen($minuten));
        }

        if(strlen($stunden)==1) {
            $stunden = '0'.$stunden;
        }
        if(strlen($minuten)==1) {
            $minuten = '0'.$minuten;
        }
       
        $ausgabe = $negativ.$stunden.':'.$minuten;
        return $ausgabe;
    }
}
