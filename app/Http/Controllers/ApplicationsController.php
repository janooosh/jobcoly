<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Application;
use App\Assignment;
use App\User;
use App\Shift;
use App\Shiftgroup;
use App\Job;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class ApplicationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $applicationsFilter = ['user_id' => $user->id];
        $applications = Application::where($applicationsFilter)->get();

        foreach($applications as $application) {
            $application->expiration = Carbon::parse($application->expiration)->format('d.m.y H:i');
        }
       
        return view('applications.index', compact('applications','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $shift = Shift::find($id);
        
        return view('applications.create', compact('shift','entlohnung'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        echo $request->get('shiftid');
        
        //Validate
        $request->validate([
            'shiftexperience' => 'max:500',
            'shiftmotivation' => 'max:500',
            'shiftcomments' => 'max:500',
            'shiftid' => 'exists:shifts,id'
        ]);
        
        $application = new Application([
            'shift_id' => $request->get('shiftid'),
            'user_id' => Auth::user()->id,
            'status' => 'Aktiv',
            'expiration' => Carbon::now()->addDays(3),
            'motivation'=>$request->get('shiftmotivation'),
            'experience'=>$request->get('shiftexperience'),
            'notes'=>$request->get('shiftcomments')
        ]);
        $application->save();

        return redirect('applications')->with('success','Bewerbung wurde gespeichert. Wir melden uns so schnell wie möglich =)');

        //Check if there are still places available

        //
    }

    /**
     * Display the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $a = Application::find($id);
        $a->ablauf = Carbon::parse($a->expiration)->format('D. d. M. H:i');
        $a->start = Carbon::parse($a->shift->starts_at)->format('D | d.m.y H:i');
        $a->end = Carbon::parse($a->shift->ends_at)->formaT('D | d.m.y H:i');
        $a->duration = Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I');
        
        return view('applications.show', compact('a'));
    }

    /**
     * Creates view for evaluating (accept/decline) a SINGLE application.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function evaluate() 
    {
        $applications = Application::all();
        $shifts = Shift::all();

        foreach($shifts as $shift) {
            echo($shift->managerr);
        }


        //return view('applications.evaluate');
    }

    public function getData()
    {
        $applications = Application::all();

        $applicationCollection = array();
        foreach($applications as $application) {
            //Check if Manager matches
            $tempManagers = $application->shift->managers;
            foreach($tempManagers as $tempManager) {
                //Match current user id (logged in user) with manager
                if($tempManager->user->id == Auth::user()->id) {
                    //Adding to array
                    $applicationCollection[] = $application;
                }
            }
        }
        echo("Hello ".$applicationCollection);
        return;


        $collection = collect([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
            ['id' => 3, 'name' => 'James'],
        ]);

        /*return Datatables::of($collection)->make(true);

        return Datatables::of(Application::query())->make(true); */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
     * reject
     * Called when an applicant turns it down
     */
    public function reject(Request $request) {
        $request->validate([
            'application'=>'required|exists:applications,id'
        ]);

        $application = Application::find($request->get('application'));

        if($application->applicant->id!=Auth::user()->id) {
            return redirect('applications/')->with('danger','Keine Zugriffsrechte.');
        }

        //If Status still active, turn application down
        if($application->status!='Aktiv') {
            return redirect('applications/'.$application->id)->with('warning','Deine Bewerbung konnte nicht zurückgezogen werden, da sie in der Zwischenzeit bearbeitet wurde.');
        }

        $application->status='Cancelled';
        $application->save();

        return redirect('applications')->with('success', 'Deine Bewerbung wurde zurückgezogen.');
    }

    /**
     * Changes the status of a given application 
     * Use this to change application from aktiv->zusage/absage etc.
     */
    public static function changeStatus($id,$status) {
        $application = Application::find($id);
        $application->status = $status;
        $application->save();
        return;
    }

    /**
     * Display a list of shifts.
     * Input: shiftgroup & job
     * Returns a view with all single shifts, with links to the "final" form
     *
     * @return \Illuminate\Http\Response
     */
    public function selectShift($shiftgroup, $job) {
        
        $shiftsFilter = ['shiftgroup_id' => $shiftgroup, 'job_id' => $job,'status'=>'Aktiv'];
        $shifts = Shift::where($shiftsFilter)->get();
        foreach($shifts as $shift) {
            $shift->date = Carbon::parse($shift->starts_at)->format('D d.m.y');
            $shift->start = Carbon::parse($shift->starts_at)->format('H:i');
            $shift->ende = Carbon::parse($shift->ends_at)->format('H:i');
            $shift->duration = Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I');
        }
        $groupTitle = Shiftgroup::find($shiftgroup)->name;
        $jobTitle = Job::find($job)->name;
        $jobShort = Job::find($job)->short;
        return view('applications.selectShift', compact('shifts','groupTitle','jobTitle', 'jobShort'));
    }
    /**
     * countBuisyShifts
     * Returns buisy shifts for given shiftgroup and job
     * "buisy": Aktive Bewerbungen und Aktive Assignments werden berücksichtigt.
     */
    public static function countBuisyShifts($shiftgroup_id, $job_id) {

        $shiftsFilter = ['shiftgroup_id' => $shiftgroup_id, 'job_id' => $job_id,'status'=>'Aktiv'];
        $assignmentsFilter = ['status' => 'Aktiv']; //Fordert aktive Schicht, keine gelöschten/abgesagten
        $shifts = Shift::where($shiftsFilter)->get();

        $shiftsSum = 0;
        foreach($shifts as $shift) {
            if(!ApplicationsController::alreadyBuisyShift(Auth::user()->id,$shift->id)) {
                $shiftsSum = $shiftsSum + count($shift->activeAssignments);
                $shiftsSum = $shiftsSum + count($shift->activeApplications);
            }

        }
        return $shiftsSum;
    }

    /**
     * countBuisyShift (Achtung, kein Plural!)
     * Returns Summe aus aktiven Bewerbungen und aktiven Assignments für eine gegebene Schicht
     */
    public static function countBuisyShift($shift_id) {
        $shift = Shift::find($shift_id);
        $counter = 0;
        $counter += count($shift->activeAssignments);
        $counter += count($shift->activeApplications);
        return $counter;
    }

    public static function countFreeShifts($shiftgroup_id, $job_id) {

        $shiftsFilter = ['shiftgroup_id' => $shiftgroup_id, 'job_id' => $job_id, 'status'=>'Aktiv'];
        $assignmentsFilter = ['status' => 'Aktiv']; //Fordert aktive Schicht, keine gelöschten/abgesagten
        $shifts = Shift::where($shiftsFilter)->get();

        $shiftsSum = 0;
        foreach($shifts as $shift) {
            if(!ApplicationsController::alreadyBuisyShift(Auth::user()->id,$shift->id)) {
                $shiftsSum = $shiftsSum + count($shift->activeAssignments);
                $shiftsSum = $shiftsSum + count($shift->activeApplications);
            }

        }
        return ApplicationsController::countAvailableJobs($shiftgroup_id, $job_id) - $shiftsSum;
    }

    /**
     * alreadyBuisyGroup
     * returns true: In der Kombi Shiftgroup/Job gibt es keine freie Stelle, wo er sich aktuell bewerben kann.
     * returns false: In der Kombi Shiftgroup/Job gibt es noch freie Stellen (mindestens 1)
     */
    public static function alreadyBuisyGroup($user_id,$shiftgroup_id,$job_id) {
        //Get shiftgroups
        $shiftgroup = Shiftgroup::find($shiftgroup_id);

        //Iterate through sgs, get shifts
        $shifts = $shiftgroup->shifts;
        $countBuisys = 0;
        $counterTemp = 0;
        foreach($shifts as $shift) {
            $counterTemp++;
            if($shift->job->id == $job_id) {
                if(ApplicationsController::alreadyBuisyShift($user_id,$shift->id)) {
                    $countBuisys++;
                }
            }
        }
        if($countBuisys>=$counterTemp) {
            return true;
        }
        return false;
    }

    /**
     * alreadyBuisyShift
     * true -> schon Buisy
     * false -> noch nicht buisy
     * siehe alreaadyBuisyGroup, halt nur für Schichten
     */
    public static function alreadyBuisyShift($user_id,$shift_id) {
        
        //Get user, get Shift
        $user = User::find($user_id);
        $shift = Shift::find($shift_id);

        //Get given shift's start and end
        $shiftStart = $shift->starts_at;
        $shiftEnd = $shift->ends_at;

        //Get user's active assignments & active applications, store in one array
        $actives = array(); //Hier store all assignments/applications

        foreach($user->activeAssignments as $assignment) {
            $actives[] = $assignment;
        }
        foreach($user->activeApplications as $application) {
            $actives[] = $application;
        }

        //Keine Assignments/Applications -> User ist für alles offen
        if(count($actives)<1) {
            return false; //False -> nicht buisy
        }
        //Iterate through users assignments/applications
        foreach($actives as $active) {
            //user start vor shift ende
            $shift_start = Carbon::parse($shiftStart);
            $shift_end = Carbon::parse($shiftEnd);
            $app_start = Carbon::parse($active->shift->starts_at);
            $app_end = Carbon::parse($active->shift->ends_at);
            
            
            if($app_start->lessThan($shift_end) && $shift_start->lessThan($app_end)) {
                return true;
            }
        }
        return false;
    }

    public static function countAvailableJobs($shiftgroup_id, $job_id) {
        $shiftsFilter = ['shiftgroup_id'=>$shiftgroup_id, 'job_id'=>$job_id,'status'=>'Aktiv'];
        $shifts = Shift::where($shiftsFilter)->get();
        $availableJobsSum=0;
        foreach($shifts as $shift) {
            if(!ApplicationsController::alreadyBuisyShift(Auth::user()->id,$shift->id)) {
                $availableJobsSum = $availableJobsSum + $shift->anzahl;
            }
        }
        return $availableJobsSum;
    }

    public static function countActiveApplications($shiftgroup_id, $job_id) {
        $shiftsFilter = ['shiftgroup_id'=>$shiftgroup_id, 'job_id'=>$job_id];
        $shifts = Shift::where($shiftsFilter)->get();
        $activeApplicationsSum=0;

        foreach($shifts as $shift) {
            $activeApplicationsSum = $activeApplicationsSum + count($shift->activeApplications);
        }
        return $activeApplicationsSum;
    }

    /**
     * Count number of shifts that still offer jobs in a shiftgroup
     */
    public static function FreeShiftsInGroup($shiftgroup_id) {
        $jobs = Job::get();
        $counter = 0;
        foreach($jobs as $job) {
            $freeShifts = ApplicationsController::countFreeShifts($shiftgroup_id,$job->id);
            if($freeShifts>0) {
                $counter++;
            }
        }
        return $counter;
    }

    /**
     * Unique identification of a shiftgroup: job <-> shiftgroup 
     * @ returns view to create application
    */
    public function new() {

        $shifts = Shift::where('status','Aktiv')->get();
        $assignments = Assignment::where('status','Aktiv');
        $shiftgroups = Shiftgroup::all();
        $applications = Application::where('status','Aktiv');
        $jobs = Job::all();
        $openShifts = [];

        return view('applications.new', compact('shifts', 'assignments', 'applications', 'shiftgroups','jobs'));
        
        /**
         * ACHTUNG, DAS HIER WIRD NICHT MEHR AUSGEFÜHRT!
         * HABE HIER VERSUCHT, DIE SORTIERUNG BEREITS IM VORRAUS ZU MACHEN, BIN ABER GESCHEITERT.
         * WILL STICK TO LARAVEL, just display it statically.
         */

        //Only get active applications

        $shifts = []; //will be returned, array of adapted shift objects
        //Display dimensions for shifts/groups: link, jobname, jobshort, date(*), time, duration, available, free, applications

        //Identify groups, iterate through all existing shifts (stored in shifts_unsorted)
        foreach($shifts_unsorted as $shift_u) {
            //Check if item exists several times in shifts_unsorted (all shifts), if yes it's a group, if no it's a single
            if($this->shiftCounterMaster($shifts_unsorted, $shift_u->job_id, $shift_u->shiftgroup_id)>1) {
                //It's a group

                //Is it already in the shifts array?
                if($this->shiftCounterSub($shifts,$shift_u->job_id, $shift_u->shiftgroup->id)>0) {
                    //Shift as part of a group is already included

                        //Adapt spaces stuff? #Advanced
                }

                else {
                    //Shift as part of a group is not yet included

                    //Create virtual shift
                    $newShift = [
                    //Create attributes
                    'link' => 'applications/new/'.$shift_u->shiftgroup_id.'/'.$shift_u->job_id,
                    'jobid' => $shift_u->job_id,
                    'shiftgroupid' => $shift_u->shiftgroup_id,
                    'jobname' => $shift_u->job->name,
                    'jobshort' => $shift_u->job->short,
                    'date' => '',
                    'time' => '',
                    'duration' => '',
                    'available' => $this->countAvailable($shift_u->job_id, $shift_u->shiftgroup_id),
                    'applications' => $this->countApplications($shift_u->job_id,$shift_u->shiftgroup_id)
                    ];
                    //Add to shifts
                    $shifts[] = $newShift;
                }
            }
            else {
                //It's a single
                $newShift = [
                //Create attributes
                'link' => 'applications/create/'.$shift_u->id,
                'jobid' => $shift_u->job_id,
                'shiftgroupid' => $shift_u->shiftgroup_id,
                'jobname' => $shift_u->job->name,
                'jobshort' => $shift_u->job->short,
                'date' => Carbon::parse($shift_u->starts_at)->format('d.m.Y'),
                'time' => Carbon::parse($shift_u->starts_at)->format('H:i').' - '.Carbon::parse($shift_u->ends_at)->format('H:i'),
                'duration' => Carbon::parse($shift_u->starts_at)->diff(Carbon::parse($shift_u->ends_at))->format('%H:%I'),
                'available' => $this->countAvailable($shift_u->job_id, $shift_u->shiftgroup_id),
                'free' => $this->countFree($shift_u->job_id,$shift_u->shiftgroup_id),
                'applications' => $this->countApplications($shift_u->job_id,$shift_u->shiftgroup_id),
                ];
                //Add to shifts
                $shifts[] = $newShift;
            } 
        }

        //return view('applications.new',compact('shifts'));
    }

    /*
    *Checks if a combination of job and shiftgroup (-> unique identification of a group) is already in the given array
    *and counts the occurence.
    
    public function shiftCounterMaster($shifts, $job, $shiftgroup){
        if(count($shifts)<1) {
            return 0;
        }
        $counter=0;
        foreach($shifts as $shift) {
            if($shift->job_id == $job && $shift->shiftgroup_id == $shiftgroup) {
                $counter++;
            }
        }
        return $counter;
    }

    /**
     * Checkt nur ob es schon dem output array shifts hinzugefügt wurde, kann aber nicht auf die Objektattribute zugreifen, da wir das ja neu erstellen.
     
    public function shiftCounterSub($shifts, $job, $shiftgroup) {
        if(count($shifts)<1) {
            return 0;
        }
        $counter=0;
        foreach($shifts as $shift) {
            if($shift['jobid'] == $job && $shift['shiftgroupid'] == $shiftgroup) {
                $counter++;
            }
        }
        return $counter;
    }

    public function countFree($job, $shiftgroup) {
        //TO-DO
        return 1;
    }

    public function countApplications($job, $shiftgroup) {
        //TO-DO
        return 1;
    }

    public function countAvailable($job, $shiftgroup) {
        //To-Do
        return 1;
    }
    */



    




}
