<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\User;
use App\Privilege;
use App\Shift;
use App\Application;
use App\Assignment;
use Carbon\Carbon;

class EvaluationsController extends Controller
{
    //Require authenticated user
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return view, all applications (user <-> shift)
     */
    public function index() {
        return view('evaluations.index');
    }

    /**
     * Shows all applications
     */

    public function showAllApplications($status) {
        if(count(Auth::user()->manager_shifts)<1) {
            return "Keine Zugriffsberechtigung.";
        }

        //Get all applications that current user is manager on
        $applicationsRaw = Application::all();
        $applications = 0;

        //Counter (erleichtern nur den View)
        $actives = array();
        $denides = array();
        $accepteds = array();
        $others = array();
        $cancelleds = array();

        foreach($applicationsRaw as $app) {
            //Correct dates
            $app->expirationFunction = Carbon::parse($app->expiration)->format('MMM D, GGGG H:m:s'); //For countdown function
            //Get Managers
            $managers = $app->shift->managers;
            //Is current logged in user manager?
            foreach($managers as $manager) {
                if($manager->user->id == Auth::user()->id) {
                    //Set displayable times

                    //Add to applications
                    $applications++;
                    if($app->status==="Aktiv") {
                        $actives[] = $app;
                    }
                    else if($app->status==="Rejected") {
                        $denieds[] = $app;
                    }
                    else if($app->status==="Accepted") {
                        $accepteds[] = $app;
                    }
                    else if($app->status==="Cancelled") {
                        $cancelleds[] = $app;
                    }
                    else {
                        $others[] = $app;
                    }
                }
            }
        }
        //Return view with filtered applications
        return view('evaluations.index', compact('applications', 'actives', 'denides', 'accepteds','cancelleds','others','status'));
    }

    /**
     * Shows a single application
     */
    public function showSingleApplication($id) {
        //Find Application
        $a = Application::find($id);

        //Bewerbung existiert nicht
        if($a ==null) {
            return redirect('applications/evaluate')->with('warning','Bewerbung wurde nicht gefunden.');
        }
        //Keine Manager-Rechte
        if(!PrivilegeController::isManager(Auth::user()->id,$a->shift->id)) {
            return redirect('applications/evaluate')->with('danger','Keine Zugriffsrechte');
        }

        //If Shift already besetzt, return with error message
            //Erstmal auskommentiert, will ja trotzdem sehen.
        /*$spots = ShiftsController::hasFreeAssignments($a->shift->id);
        if($spots<1) {
            return redirect('applications/evaluate')->with('warning','Die Schicht hat keine aktiven Plätze mehr. Vermutlich wurde die Bewerbung von einem Co-Manager zwischenzeitlich bearbeitet. Falls diese Bewerbung weiterhin in folgender Liste erscheint, kontaktiere bitte jan.haehl@olylust.de und gebe folgende IDs durch: ShiftID: '.$a->shift->id.', Application ID: '.$a->id.'.');
        }*/
        
        //Define Shortcuts for Times
        $a->start = Carbon::parse($a->shift->starts_at)->format('D | d.m.y H:i');
        $a->end = Carbon::parse($a->shift->ends_at)->formaT('D | d.m.y H:i');
        $a->duration = Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I');
        
        //Mitbewerber
        $filter = ['status' => 'Aktiv', 'shift_id' => $a->shift->id];
        $concs = Application::where($filter)->get();
        $mitbewerber = array();
        foreach($concs as $conc) {
            if($conc->applicant->id != $a->applicant->id) {
                $mitbewerber[]=$conc;
            }
        }
        //ACHTUNG, conc ist application -> mitbewerber array of applications
        $a->mitbewerber = $mitbewerber;

        //CO-MANAGER
        $managerFilter=['role'=>'Manager','shift_id'=>$a->shift->id];
        $privileges = Privilege::where($managerFilter)->get();
        $comanager = array();
        foreach($privileges as $privilege) {
            if($privilege->user->id != Auth::user()->id) {
                $comanager[] = $privilege->user;
            }
        }
        $a->comanager = $comanager;
        //CO-MANAGER: TYPE = USER

        //ASSIGNED SHIFTS OF APPLICANTS (TYPE: ASSIGNMENT)
        $aFilter=['status'=>'Aktiv','user_id'=>$a->applicant->id];
        $assignments = Assignment::where($aFilter)->get();

        //ACTIVE APPLICATIONS of this guy
        $applications = Application::where($aFilter)->get();
        $applicationsFinal = array();
        foreach($applications as $ap) {
            if($ap->shift_id!=$a->shift_id) {
                //Countdown
                $applicationsFinal[] = $ap;
            }
        }
        
        //REJECTED APPLICATIONS of this guy
        $rejectedaFilter=['status'=>'Rejected','user_id'=>$a->applicant->id];
        $rejectedApplications = Application::where($rejectedaFilter)->get();
        /* Will ja auch zeigen, wenn er für diese Schicht bereits abgelehnt wurde.
        $rejectedFinal = array();
        foreach($rejectedApplications as $r) {
            if($r->shift_id!=$a->shift_id) {
                $rejectedFinal[]=$r;
            }
        } */

        $a->otherapplications = $applicationsFinal;
        $a->otherassignments = $assignments;
        $a->rejectedapplications = $rejectedApplications;

        //ZUGESAGTE STUNDEN
        $duration = 0;
        foreach($assignments as $x) {
            $duration += Carbon::parse($x->shift->starts_at)->diffInHours(Carbon::parse($x->shift->ends_at));
            
        }
        $a->otherassignmentsduration = $duration;
        //$a->otherassignmentsduration = Carbon::parse($duration)->format(' H:i');

        //Formatierter Ablaufzeitpunkt
        $a->ablauf = Carbon::parse($a->expiration)->format('D. d. M. H:i');

        //Return view with application
        return view('evaluations.view', compact('a'));
    }

    /**
     * Returns number of unassigned free shifts of a given shift with id
     */
    public static function countAssignments($id) {
        $shift = Shift::find($id);
        return count($shift->activeAssignments);
    }
    public static function countFrees($id) {
        $shift = Shift::find($id);
        $assignments = count($shift->activeAssignments);
        return $shift->anzahl - $assignments;
    }

    /**
     * REJECT FUNCTION
     * Turns applications down (sets them rejected)
     */
    public function reject(Request $request)
    {

        //Finde Applikation
        $request->validate([
            'application'=>'required|exists:applications,id'
        ]);

        $application = Application::find($request->get('application'));
        $applicant = $application->applicant;
        $shift = $application->shift;

        //Zugriffsrechte?
        if(!PrivilegeController::isManager(Auth::user()->id,$shift->id)) {
            return redirect('applications/evaluate')->with('danger','Keine Zugriffsrechte.');
        }

        if(AssignmentsController::bereitsZugelassen($applicant->id,$shift->id)==1) {
            return redirect('applications/evaluate')->with('warning','Absage konnte nicht durchgeführt werden, '.$applicant->firstname.' wurde bereits für die Schicht zugelassen. Wahrscheinlich hat einer deiner Co-Manager den Bewerber zwischenzeitlich zugelassen. Bitte kontaktiere jan.haehl@olylust.de falls die Bewerbung (id: '.$application->id.') nun immernoch in der Liste steht.');
        }
        else if(AssignmentsController::bereitsZugelassen($applicant->id,$shift->id)==2) {
            return redirect('applications/evaluate')->with('warning','Absage konnte nicht durchgeführt werden, '.$applicant->firstname.' wurde bereits für die Schicht zugelassen, die Zuweisung ist jedoch nicht mehr aktiv. Wahrscheinlich hat er abgesagt. Kontaktiere jan.haehl@olylust.de bei Fragen.');
        }
    
        //Bereits abgesagt?
        if($application->status == 'Rejected') {
            return redirect('applications/evaluate')->with('warning','Too late, einer deiner Co-Manager hat die Bewerbung bereits abgesagt. Also ist alles gut :)');
        }

        //Ändere Status
        $application->status='Rejected';
        $application->save();

        return redirect('applications/evaluate')->with('success','Absage gespeichert');
    }


}

