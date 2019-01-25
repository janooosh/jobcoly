<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SalarygroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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


}
