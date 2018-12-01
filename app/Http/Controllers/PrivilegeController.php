<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shift;
use App\Privilege;

class PrivilegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Updates Privileges
     * 
     * $shift id as input, all other inputs are from checkboxes in a table
     * $role = Manager oder Supervisor
     */
    public function update(Request $request)
    {
        //Darf er das? Admin Rights required
        if(Auth::user()->is_admin==0) {
            return redirect('home')->with('danger','Administrator-Rechte erforderlich.');
        }

        //Validate inputs
        $request->validate([   
            'shiftid'=>'required|exists:shifts,id',
            'role'=>'required|in:Manager,Supervisor',
            'userselect.*'=>'required|exists:users,id'
        ]);

        //Get Shift
        $shift = Shift::find($request->get('shiftid'));

        //Get role
        $role = $request->get('role');

        //Get users
        $users = $request->get('userselect');

        //Get Privileges
        $filter = ['role'=>$role,'shift_id'=>$shift->id];
        $privileges = Privilege::where($filter)->get();

        //Remove Privileges die nicht mehr drin sind
        //Gehe über alle Privileges der Schicht
        foreach($privileges as $p) {
            //Gehe über alle user. Lösche wenn kein Match in der neuen Auswahl gefunden (counter)
            $match=0;
            foreach($users as $u) { //Achtung, users besteht aus ids
                if($p->user_id==$u) {
                    $match++;
                }
            }

            if($match==0) {
                //Löschen, keine Übereinstimmung zu dem Privileg
                $p->delete();
            }
        }

        foreach($users as $user) {
            //Gibts dich noch nicht in Privileges? Hinzufügen
            $pcounter=0;
            foreach($privileges as $p) {
                if($user == $p->user_id) {
                    $pcounter++;
                }
            }
            if($pcounter<1) {
                //Hinzufügen
                $neu = new Privilege([
                    'shift_id'=>$shift->id,
                    'user_id'=>$user,
                    'role'=>$role
                ]);
                $neu->save();
            }
        }

        return redirect('shifts/'.$shift->id.'/edit')->with('success','Rollen gespeichert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * isManager
     * Checks if a given user (user_id) is one of the managers of the given shift (Shift_id)
     * True if yes, false if no
     */
    public static function isManager($user_id,$shift_id) {
        //Find Shift
        $shift = Shift::find($shift_id);
        //Get Managers of shift
        $managers = $shift->managers;
        //Loop through managers, check if one of them is the current user
        foreach($managers as $m) {
            if($m->user->id == Auth::user()->id) {
                //Success, true
                return true;
            }
        }
        //Nix gefunden, false. Kriegt kein Zugriff der Halunke!
        return false;
    }
    /**
     * isManager checks auth user (aus Versehen...)
     * will aber nicht alles ändern, deswegen die jetzt richtig...
     */
    public static function isRealManager($user_id,$shift_id) {
        //Find Shift
        $shift = Shift::find($shift_id);
        //Get Managers of shift
        $managers = $shift->managers;
        //Loop through managers, check if one of them is the current user
        foreach($managers as $m) {
            if($m->user->id == $user_id) {
                //Success, true
                return true;
            }
        }
        //Nix gefunden, false. Kriegt kein Zugriff der Halunke!
        return false;
    }
        /**
     * isSupervisor
     * Checks if a given user (user_id) is one of the supervisors of the given shift (Shift_id)
     * True if yes, false if no
     */
    public static function isSupervisor($user_id,$shift_id) {
        //Find Shift
        $shift = Shift::find($shift_id);
        //Get Managers of shift
        $supervisors = $shift->supervisors;
        //Loop through managers, check if one of them is the current user
        foreach($supervisors as $s) {
            if($s->user->id == Auth::user()->id) {
                //Success, true
                return true;
            }
        }
        //Nix gefunden, false. Kriegt kein Zugriff der Halunke!
        return false;
    }
        /**
     * isSupervisor checks auth user (aus Versehen...)
     * will aber nicht alles ändern, deswegen die jetzt richtig...
     */
    public static function isRealSupervisor($user_id,$shift_id) {
        //Find Shift
        $shift = Shift::find($shift_id);
        //Get Managers of shift
        $managers = $shift->supervisors;
        //Loop through managers, check if one of them is the current user
        foreach($managers as $m) {
            if($m->user->id == $user_id) {
                //Success, true
                return true;
            }
        }
        //Nix gefunden, false. Kriegt kein Zugriff der Halunke!
        return false;
    }

    /**
     * countManagerRole
     * For a given user_id this will return the number of manager roles the user has.
     */
    public static function countManagerRole($user_id) {
        $filter=['role'=>'Manager','user_id'=>$user_id];
        $privileges = Privilege::where($filter)->get();
        return count($privileges);
    }
}
