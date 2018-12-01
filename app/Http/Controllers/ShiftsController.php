<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shift;
use App\Job;
use App\Shiftgroup;
use App\User;
use App\Application;
use App\Assignment;
use App\Privilege;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
class ShiftsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*
        $raw = Shift::all();
        $shifts = array();

        foreach($raw as $r) {
            foreach($r->managers as $manager) {
                if($manager==Auth::user()->id) {
                    $shifts[] = $r;
                }
            }
        }*/

        $privileges = Auth::user()->manager_shifts;
        if(count($privileges)<1) {
            return redirect('home');    
        }

        $shifts = array();
        foreach($privileges as $p) {
            $shifts[] = $p->shift;
        }

        foreach($shifts as $shift) {
            $shift->duration =  Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
            $shift->datum = Carbon::parse($shift->starts_at)->format('d.m.y');
            $shift->starts_at = Carbon::parse($shift->starts_at)->format('H:i');
            $shift->ends_at = Carbon::parse($shift->ends_at)->format('H:i');
            
        }
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = Job::all();
        $shiftgroups = Shiftgroup::all();
        $users = User::all()->sortBy('firstname');
        return view('shifts.create', compact('jobs','shiftgroups','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //TO-DO: Require end to be after start

        $request->validate([       
            'shiftjob' => 'required|exists:jobs,id',
            'shiftgroup' => 'required|exists:shiftgroups,id', 
            'shiftstart' => 'required',
            'shiftstart' => 'required|date_format:"Y-m-d"',
            'shiftend' => 'required|date_format:"Y-m-d"',
            'shiftstarttime' => 'required|date_format:"H:i"',
            'shiftend' => 'required',
            'shiftendtime' => 'required|date_format:"H:i"',
            'shiftanzahl' => 'required|integer|max:99|min:1',
            'shiftstatus' => 'required',
            'shiftstreifen' => 'integer|min:0|max:50',
            'shiftdescription' => 'max:1000'
          ]);

        //Set correct datetime Formats
        $startDate = Carbon::parse($request->get('shiftstart'))->format('Y-m-d');
        $startTime = Carbon::parse($request->get('shiftstarttime'))->format('H:i:00');
        $endDate = Carbon::parse($request->get('shiftend'))->format('Y-m-d');
        $endTime = Carbon::parse($request->get('shiftendtime'))->format('H:i:00');

        if(Carbon::parse($request->get('shiftstart'))->greaterThanOrEqualTo(Carbon::parse($request->get('shiftend')))) {
            return redirect('shifts/create')->with('warning','Schichtende muss nach Schichtbeginn sein.');
        } 
        
        $start = $startDate.' '.$startTime;
        $ende = $endDate.' '.$endTime;

        //Check if end before start
            //TO-DO!

        //Get AWE & Gutscheine

        $job = Job::find($request->get('shiftjob'));

        //Getting managerz
        $managers = array();

        foreach($request->get('shiftmanager') as $manager) {
            //Check if manager exist
                //TO-DO!!
            //Push
            $managers[] = $manager;
        }

        if($request->get('shiftsupervisor')) {
        //Getting supervisorz
        $supervisors = array();
        foreach($request->get('shiftsupervisor') as $supervisor) {
            //Check if manager exist
                //TO-DO!!
            //Push
            $supervisors[] = $supervisor;
        }
        }
        
        //Create new Shift
        $shift = new Shift([
            'job_id' => $request->get('shiftjob'),
            'shiftgroup_id'=> $request->get('shiftgroup'),
            'area'=> $request->get('shiftarea'),
            'starts_at'=> $start,
            'ends_at'=> $ende,
            'anzahl'=> $request->get('shiftanzahl'),
            'status' => $request->get('shiftstatus'),
            'awe'=>$job->awe,
            'gutscheine'=>$job->gutscheine,
            'description'=>$request->get('shiftdescription')

          ]);

          //Store Shift
          $shift->save();

          //Save Managers
        foreach($managers as $id) {
            $manager = new Privilege([
                'user_id' => $id,
                'shift_id' => $shift->id,
                'role' => 'Manager'
            ]);
            $manager->save();
        }

        //Save Supervisors
        if($request->get('shiftsupervisor')) {
            foreach($supervisors as $id) {
                $supervisor = new Privilege([
                    'user_id' => $id,
                    'shift_id' => $shift->id,
                    'role' => 'Supervisor'
                ]);
                $supervisor->save();
            }
        }

          //Return
          return redirect('/shifts/admin')->with('success', 'Schicht erstellt');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $shift = Shift::find($id);

        //No access? Admins have access to read, too.
        if(!PrivilegeController::isManager(Auth::user()->id,$id)) {
            if(Auth::user()->is_admin!='1') {
                return redirect('home')->with('danger','Kein Zugriff.');
            }
        }

        //Deleted/Kranke Shifts
        $deleteds= array();
        foreach($shift->Assignments as $a) {
            if($a->status!='Aktiv') {
                $deleteds[] = $a;
            }
        }
        $shift->deleteds = $deleteds;

        //CO-MANAGER
        $managerFilter=['role'=>'Manager','shift_id'=>$shift->id];
        $privilegesManager = Privilege::where($managerFilter)->get();
        $comanager = array();
        foreach($privilegesManager as $privilege) {
            if($privilege->user->id != Auth::user()->id) {
                $comanager[] = $privilege->user;
            }
        }
        $shift->comanager = $comanager;
        //CO-MANAGER: TYPE = USER

        //SUPERVISOR
        $supervisorFilter=['role'=>'Supervisor','shift_id'=>$shift->id];
        $privilegesSupervisor = Privilege::where($supervisorFilter)->get();
        $supervisor = array();
        foreach($privilegesSupervisor as $privilege) {
                $supervisor[] = $privilege->user;
        }
        $shift->supervisor = $supervisor;
        //SUPERVISOR: TYPE = USER

        $shift->duration =  Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
        $shift->starts_at = Carbon::parse($shift->starts_at)->format('D, d.m.y H:i');
        $shift->ends_at = Carbon::parse($shift->ends_at)->format('D, d.m.y H:i');
    
        return view('shifts.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shift = Shift::find($id);
        /* html5 datetime-local value requires YYYY-MM-DDThh:mm:ss.ms, 00 as ms bc by default carbon prints 0000 instead of 00, to lazy to change that... */
        
        $shift->shiftstart = Carbon::parse($shift->starts_at)->format('Y-m-d');
        $shift->shiftstarttime = Carbon::parse($shift->starts_at)->format('H:i');
        $shift->shiftend=Carbon::parse($shift->ends_at)->format('Y-m-d');
        $shift->shiftendtime=Carbon::parse($shift->ends_at)->format('H:i');

        $jobs = Job::all();
        $shiftgroups = Shiftgroup::all();
        $users = User::all();
        return view('shifts.edit', compact('shift','jobs','shiftgroups', 'users'));
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
        $shift = Shift::find($id);
        
        $request->validate([
            'shiftstart' => 'required|date_format:"Y-m-d"',
            'shiftend' => 'required|date_format:"Y-m-d"',
            'shiftstarttime' => 'required|date_format:"H:i"',
            'shiftendtime' => 'required|date_format:"H:i"',
            'shiftanzahl' => 'required|integer|max:99|min:1',
            'shiftstatus' => 'required',
            'shiftgutscheine'=>'required|integer|min:0|max:20',
            'shiftawe'=>'required|integer|min:0|max:20',
            'shiftdescription' => 'max:500'
        ]);
        
          //Set correct datetime Formats
        $startDate = Carbon::parse($request->get('shiftstart'))->format('Y-m-d');
        $startTime = Carbon::parse($request->get('shiftstarttime'))->format('H:i:00');
        $endDate = Carbon::parse($request->get('shiftend'))->format('Y-m-d');
        $endTime = Carbon::parse($request->get('shiftendtime'))->format('H:i:00');
        
        if(Carbon::parse($request->get('shiftstart'))->greaterThanOrEqualTo(Carbon::parse($request->get('shiftend')))) {
            return redirect('shifts/'.$id.'/edit')->with('warning','Schichtende muss nach Schichtbeginn sein.');
        } 

        $start = $startDate.' '.$startTime;
        $ende = $endDate.' '.$endTime;


        //$shift->job_id = $request->get('shiftjob');
        //$shift->shiftgroup_id = $request->get('shiftgroup');
        //$shift->area = $request->get('shiftarea');
        $shift->starts_at = $start;
        $shift->ends_at = $ende;
        $shift->awe = $request->get('shiftawe');
        $shift->gutscheine = $request->get('shiftgutscheine');
        $shift->anzahl = $request->get('shiftanzahl');
        $shift->status = $request->get('shiftstatus');
        $shift->description = $request->get('shiftdescription');
        $shift->save();
        return redirect('shifts/'.$shift->id)->with('success', 'Schicht aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->id!='1') {
            return 'Kein Zugriff';
        }
        $shift = Shift::find($id);

        //Delete Managers
        $managers = $shift->managers;
        foreach($managers as $manager) {
            $privilege = Privilege::find($manager->id);
            $privilege->delete();
        }
        
        //Delete Supervisors
        $supervisors = $shift->supervisors;
        foreach($supervisors as $supervisor) {
            $privilege=Privilege::find($supervisor->id);
            $privilege->delete();
        }

        //Set Assignments Deleted
        $assignments = $shift->Assignments;
        foreach($assignments as $a) {
            $a->status="Schicht gelöscht";
            $a->save();
        }

        //Set Applications Deleted
        $applications = $shift->applications;
        foreach($applications as $ap) {
            $ap->status="Schicht gelöscht";
            $ap->save();
        }

        $shift->status="Schicht Gelöscht";
        $shift->save();

        $n = $shift->job['short']." Schicht entfernt.";


        return redirect('shifts')->with('success', $n);
    }

    /**
     * showAll()
     * For Admin view only
     */
    public function showAll() {
        if(Auth::user()->is_admin!='1') {
            return redirect('shifts');
        }
        $shifts=Shift::all();
        foreach($shifts as $shift) {
            $shift->duration =  Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
            $shift->datum = Carbon::parse($shift->starts_at)->format('d.m.y');
            $shift->starts_at = Carbon::parse($shift->starts_at)->format('H:i');
            $shift->ends_at = Carbon::parse($shift->ends_at)->format('H:i');
        }
        return view('shifts.all',compact('shifts'));
    }

    public static function countFreeShifts($id) {
        //Find shift
        $shift = Shift::find($id);
        //Get anzahl
        $anzahl = $shift->anzahl;
        //Get bewerber & aktive zuweisungen
        $assignments = count($shift->activeAssignments);
        //$bewerber = count($shift->activeApplications);
        $bewerber=0; 
        //Calculate free shifts & return
        return $anzahl-($assignments+$bewerber);
    }

    //STATIC FUNCTIONS
    /**
     * getManagers
     */
    public static function getManagers($id) {
        $shift = Shift::find($id);
        $managerRoles = $shift->managers;
        $users = array();
        foreach($managerRoles as $managerRole) {
            $users[] = $managerRole->user;
        }
        return $users;
    }

    /**
     * Returns duration of a shift
     */
    public static function getDuration($id, $format) {
        $shift = Shift::find($id);
        return Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format($format);
    }

    /**
     * Returns number of places that are still free in this shift
     */
    public static function hasFreeAssignments($id) {
        $shift = Shift::find($id);

        $assignmentFilter=['status'=>'Aktiv','shift_id'=>$id];
        $assignments=Assignment::where($assignmentFilter)->get();
        $belegt=0;
        foreach($assignments as $assignment) {
            $belegt++;
        }
        return $shift->anzahl - $belegt;
    }

}
