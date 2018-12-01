<?php
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shift;
use App\Assignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * index
     * Shows all teams current user is supervising
     */
    public function index() {
        $privileges = Auth::user()->supervisor_shifts;
        if(count($privileges)<1) {
            return redirect('home')->with('danger','Keine Berechtigung.');
        }
        $shifts = array();
        foreach($privileges as $privilege) {
            $shift = $privilege->shift;
            $shift->datum = Carbon::parse($shift->starts_at)->format('D, d.m.y');
            $shift->start = Carbon::parse($shift->starts_at)->format('H:i');
            $shift->end = Carbon::parse($shift->ends_at)->format('H:i');
            $shift->duration = Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
            $shift->actives = count($shift->activeAssignments);
            $shifts[] = $shift;
        }

        return view('supervisor.index', compact('shifts'));
    }

    /**
     * Shows Mitarbeiter für gegebene Schicht
     */
    public function myTeam($shift_id) {
        if(!PrivilegeController::isSupervisor(Auth::user()->id,$shift_id)) {
            if(Auth::user()->is_admin!='1') {
                return redirect('home')->with('danger','Keine Berechtigung.');
            }
        }
        $shift = Shift::find($shift_id);
        //Set Times
        $shift->datum = Carbon::parse($shift->starts_at)->format('D, d.m.y');
        $shift->start = Carbon::parse($shift->starts_at)->format('H:i');
        $shift->end = Carbon::parse($shift->ends_at)->format('H:i');
        $shift->duration = Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
        $assignments = $shift->activeAssignments;
        $actives = array();
        foreach ($assignments as $a) {
            $actives[] = $a->user;
        }
        $co_supervisors = array(); //Array of users 
        foreach($shift->supervisors as $s) {
            if($s->user->id!=Auth::user()->id) {
                $co_supervisors[]=$s->user;
            }
        }
        return view('supervisor.myTeam',compact('shift','actives','co_supervisors'));
    }

    public function review($shift_id) {
        if(!PrivilegeController::isSupervisor(Auth::user()->id,$shift_id)) {
            if(Auth::user()->is_admin!='1') {
                return redirect('home')->with('danger','Keine Berechtigung.');
            }
        }
        $shift = Shift::find($shift_id);
        //Set Times
        $shift->datum = Carbon::parse($shift->starts_at)->format('D, d.m.y');
        $shift->start = Carbon::parse($shift->starts_at)->format('H:i');
        $shift->end = Carbon::parse($shift->ends_at)->format('H:i');
        $shift->duration = Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
        $actives = $shift->activeAssignments;
        foreach($actives as $a) {
            $a->duration = Carbon::parse($a->start)->diff(Carbon::parse($a->end))->format('%H:%I');
            $a->start = Carbon::parse($a->start)->format('H:i');
            $a->end = Carbon::parse($a->end)->format('H:i');
        }

        $co_supervisors = array(); //Array of users 
        foreach($shift->supervisors as $s) {
            if($s->user->id!=Auth::user()->id) {
                $co_supervisors[]=$s->user;
            }
        }
        return view('supervisor.review',compact('shift','actives','co_supervisors'));
    }

    /**
     * Saves single assignment (supervisor)
     */
    public function save(Request $request,$assignment_id) {
        //Rights am Start?
        $assignment = Assignment::find($assignment_id);
        if(!PrivilegeController::isSupervisor(Auth::user()->id,$assignment->shift->id)) {
            if(Auth::user()->is_admin!='1') {
                return redirect('home')->with('danger','Keine Berechtigung.');
            }
        }

        //Find assignment & update it
        

        //Validate Request
        $request->validate([       
            'shiftstart'=>'required|date_format:"H:i"',
            'shiftend'=>'required|date_format:"H:i"',
            'shiftapproval'=>'boolean'
        ]);

        //Check Uhrzeit
        $start = $request->get('shiftstart');
        $ende = $request->get('shiftend');
        
        $start_Hour = explode(':',$start)[0];
        $start_Min = explode(':',$start)[1];

        $end_Hour = explode(':',$ende)[0];
        $end_Min = explode(':',$ende)[1];

        $a_start = Carbon::parse($assignment->start);
        $a_end = Carbon::parse($assignment->end);

        $a_start->hour = intval($start_Hour);
        $a_start->minute = intval($start_Min);
        
        $a_end->hour = intval($end_Hour);
        $a_end->minute = intval($end_Min);

        if($a_start->greaterThan($a_end)) {
            $a_end->addDay();
        }
        if($a_start->diffInDays($a_end)>0) {
            $a_end->subDay();
        }

        $assignment->start = $a_start;
        $assignment->end = $a_end;
        $assignment->confirmed = $request->get('shiftapproval');
        $assignment->save();

        return redirect('/supervisor/team/'.$assignment->shift->id.'/review')->with('success',''.$assignment->user->firstname.' Gespeichert.');
        
    }

    /**
     * close
     * Schließt Schicht basierend auf Shift id ab (Änderungen danach nicht mehr möglich für Supervisor).
     * 
     */
    public function close($shift_id) {
        $shift = Shift::find($shift_id);
        if(!PrivilegeController::isSupervisor(Auth::user()->id,$shift->id)) {
            if(Auth::user()->is_admin!='1') {
                return redirect('home')->with('danger','Keine Berechtigung.');
            }
        }

        //Still open Bestätigungen?
        $filter = ['shift_id'=>$shift_id,'status'=>'Aktiv'];
        $assignments = Assignment::where($filter)->get();

        foreach($assignments as $assignment) {
            if($assignment->confirmed!=0 && $assignment->confirmed!=1) {
                return redirect('/supervisor/team/'.$assignment->shift->id.'/review')->with('danger','Bitte treffe eine Entscheidung über Bestätigung/Nicht-Bestätigung für Alle Mitarbeiter.');
            }
        }

        //Close Shift
        $shift->confirmed = true;
        $shift->save();

        //Return success message
        return redirect('/supervisor/team/'.$assignment->shift->id.'/review')->with('success','Schicht abgeschlossen');
    }
}
