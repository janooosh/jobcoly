<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shift;

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
     * countManagerRole
     * For a given user_id this will return the number of manager roles the user has.
     */
    public static function countManagerRole($user_id) {
        $filter=['role'=>'Manager','user_id'=>$user_id];
        $privileges = Privilege::where($filter)->get();
        return count($privileges);
    }
}
