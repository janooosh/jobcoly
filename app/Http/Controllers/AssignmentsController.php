<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shift;
use App\User;
use App\Application;
use App\Assignment;
use Carbon\Carbon;
use App\Privilege;
use Illuminate\Support\Facades\Auth;

use App\Mail\Annahme;
use App\Mail\Krankmeldung;
use Illuminate\Support\Facades\Mail;

class AssignmentsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * View for all of my assignments
     */
    public function my() {
        $filter = ['user_id'=>Auth::user()->id];
        $assignments = Assignment::where($filter)->get();

        $actives = array();
        $others = array();
        foreach($assignments as $assignment) {
            $assignment->datum = Carbon::parse($assignment->shift->starts_at)->format('D, d.m.y');
            $assignment->uhrzeit = Carbon::parse($assignment->shift->starts_at)->formaT('H:i');
            $assignment->duration = Carbon::parse($assignment->shift->starts_at)->diff(Carbon::parse($assignment->shift->ends_at))->format('%H:%I');
            
            if($assignment->status=='Aktiv') {
                $actives[] = $assignment;
            }
            else {
                $others[] = $assignment;
            }
        }
        
        return view('assignments.my', compact('actives','others'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('assignments/my');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate both
        $request->validate([
            'application'=>'required|exists:applications,id'
        ]);
        
        //Get shift & applicant
        $application = Application::find($request->get('application'));
        $shift = $application->shift;
        $applicant = $application->applicant;
            
        //Zugriff?
        if(!PrivilegeController::isManager(Auth::user()->id,$shift->id)) {
            return redirect('applications/evaluate')->with('danger','Keine Zugriffsrechte. Bewerbung nicht zugelassen.');
        }

        //Wurde der Bewerber bereits für diese Schicht zugelassen?
        if(AssignmentsController::bereitsZugelassen($applicant->id,$shift->id)==1) {
            return redirect('applications/evaluate/active')->with('warning','Zulassung konnte nicht durchgeführt werden, '.$applicant->firstname.' wurde bereits für die Schicht zugelassen. Wahrscheinlich hat einer deiner Co-Manager den Bewerber zwischenzeitlich zugelassen. Bitte kontaktiere jan.haehl@olylust.de falls die Bewerbung (id: '.$application->id.') nun immernoch in der Liste steht.');
        }
        else if(AssignmentsController::bereitsZugelassen($applicant->id,$shift->id)==2) {
            return redirect('applications/evaluate/active')->with('warning','Zulassung konnte nicht durchgeführt werden, '.$applicant->firstname.' wurde bereits für die Schicht zugelassen, die Zuweisung ist jedoch nicht mehr aktiv. Wahrscheinlich hat er abgesagt. Kontaktiere jan.haehl@olylust.de bei Fragen.');
        }

        //Wurde der Bewerber zwischenzeitlich abgelehnt?
        if($application->status=='Rejected') {
            return redirect('applications/evaluate/active')->with('warning','Zulassung konnte nicht durchgeführt werden, die Bewerbung wurde zwischenzeitlich abgelehnt. Wahrscheinlich von einem Co-Manager? Bei Fragen wende dich an jan.haehl@olylust.de, Application ID: '.$application->id.', Shift ID: '.$shift->id.', User ID: '.$applicant->id.'');
        }

        //Check if shift still free
        $freePlaces = ShiftsController::hasFreeAssignments($shift->id);
        
        if($freePlaces<1) {
            //SHIFT NOT FREE
            return redirect('applications/evaluate/active')->with('danger','Schicht wurde in der Zwischenzeit bereits besetzt. Bitte kontaktiere deine Co-Manager.');
        }
        else if($freePlaces>0) {
            //Create Assignment
            $assignment = new Assignment([
                'shift_id'=>$shift->id,
                'user_id'=>$applicant->id,
                'application_id'=>$request->get('application'),
                'status'=>'Aktiv',
                'start'=>$shift->starts_at,
                'end'=>$shift->ends_at,
                'notes_manager'=>'Zugelassen von '.Auth::user()->firstname.' '.Auth::user()->surname.'.'
            ]);
            $assignment->save();
            //Set Application to accepted
            ApplicationsController::changeStatus($request->get('application'),'Accepted');
            Mail::to(Auth::user()->email)->send(new Annahme()); 
            return redirect('applications/evaluate/active')->with('success','Bewerbung akzeptiert, '.$applicant->firstname.' als '.$shift->job->name.' zugelassen.');
        }
        else 
        return "Es ist ein interner Fehler aufgetreten, Fehlercode: Assignment/X1. Bitte kontaktiere den Administrator.";


        

        //Redirect to eval overview with success message
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assignment = Assignment::find($id);
        if($assignment==null) {
            return redirect('assignments/my')->with('warning','Schichtzuweisung wurde nicht gefunden.');
        }
        $assignment->datum = Carbon::parse($assignment->shift->starts_at)->format('D, d.m.y');
        $assignment->uhrzeit = Carbon::parse($assignment->shift->starts_at)->format('H:i');
        $assignment->endeDatum = Carbon::parse($assignment->shift->ends_at)->format('D, d.m.y');
        $assignment->endeUhrzeit = Carbon::parse($assignment->shift->ends_at)->format('H:i');
        $assignment->duration = Carbon::parse($assignment->shift->starts_at)->diff(Carbon::parse($assignment->shift->ends_at))->format('%H:%I');

        return view('assignments.show', compact('assignment'));
    }

    /**
     * Meldet ein Assignment krank, schickt E-Mail zum Schluss
     */
    public function krankmeldung($id) { 

        //Only Admins
        if(Auth::user()->is_admin!=1) {
            return redirect('home')->with('danger','Keine Berechtigung');
        }

        $assignment = Assignment::find($id);

        $assignment->status="Krank";
        $assignment->save();
        Mail::to(Auth::user()->email)->send(new Krankmeldung()); 

        return redirect('shifts/'.$assignment->shift->id)->with('success','Krankmeldung gespeichert, Zuweisung gelöscht.');

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
     * Checks if a given user is already accepted for a certain shift.
     * Returns:
     * 0 -> Noch nicht zugelassen, keine Nicht-Aktive Bewerbung
     * 1 -> Bereits zugelassen, Assignment Aktiv
     * 2 -> Bereits zugelassen, Assignment aber nicht aktiv
     * 3 -> Something is strange.
     */
    public static function bereitsZugelassen($user_id, $shift_id) {
        $filter = ['shift_id'=>$shift_id, 'user_id'=>$user_id];
        $assignments = Assignment::where($filter)->get();

        if(count($assignments)==0) {
            return 0;
        }
        $actives = 0;
        $others = 0;
        foreach($assignments as $assignment) {
            if($assignment->status == 'Aktiv') {
                $actives++;
            }
            else {
                $others++;
            }
        }
        if($actives>0) {
            return 1;
        }
        else if($others>0) {
            return 2;
        }
        else return 3;
    }
}
