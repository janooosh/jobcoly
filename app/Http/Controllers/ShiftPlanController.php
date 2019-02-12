<?php

namespace App\Http\Controllers;
use App\Shiftgroup;

use Illuminate\Http\Request;

class ShiftPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * index
     * 
     * Zeigt nur Übersicht an
     */
    public function index() {
        $shiftgroups = Shiftgroup::all();

        return view('shiftplan.index',compact('shiftgroups'));
    }

    public function banane(Request $request) {
        $shiftgroups = Shiftgroup::all();
        //Validate Request
        $request->validate([
            'shiftgroup' =>'required|exists:shiftgroups,id'
        ]);

        //Find Shiftgroup
        $group = Shiftgroup::find($request->get('shiftgroup'));
        if(!$group) {
            return redirect('shiftplan.index')->with('warning','Gruppe wurde nicht gefunden');
        }

        //Ziehe Schichten
        $shifts = $group->shifts;

        //Baue Array aus Areas & ziehe Assignments
        $areas = array();
        //$assignments = array();
        foreach($shifts as $shift) {
            //Areas Array
            if(!in_array($shift->area,$areas)) {
                $areas[] = $shift->area;
            }
            /*
            foreach($shift->activeAssignments as $assignment) {
                $assignments[] = $assignment;
            } */
        }
        
        sort($areas);

        return view('shiftplan.plan',compact('areas','shifts','group','shiftgroups'));
    }
}
