<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Assignment;
use App\Salarygroup;
use Carbon\Carbon;

class BenutzerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Displays shirt survey
     */
    public function shirtSurveyDisplay() {
        //Get current user
        $user = Auth::user();
        /*//Already filled out survey? (is facebook column, didnt know how to rename)
        if($user->facebook) {
            return redirect('home')->with('warning','Du hast die Umfrage bereits ausgefüllt.');
        }*/
        //Return survey
        return view('specials.shirtsurvey',compact('user'));
    }

    /**
     * Update shirt
     */
    public function shirtSurveyPost(Request $request) {
        //Validate

        $request->validate([
            'shirtCut' => 'required|in:M,W',
            'shirtSize' => 'required|in:S,M,L,XL,xx',
            'shirtDes' => 'required|boolean'
        ]);

        $user = Auth::user();
        $user->shirt_cut = $request->get('shirtCut');
        $user->shirt_size = $request->get('shirtSize');
        $willShirt = "";
        if($request->get('shirtDes')==1) {
            $willShirt = "ja";
        }
        else {
            $willShirt = "nein";
        }
        $user->facebook = $willShirt;

        $user->save();
        return redirect('home')->with('success','T-Shirt Gespeichert!');
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request) {

        
        //Validate
        $request->validate([
            'firstname' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'phone' => 'max:30',
            'gesundheitszeugnis' => 'boolean',
            'olycat'=>'in:Hochhaus,Bungalow,HJK',
            'oly_room'=>'max:10',
            'plz'=>'max:5',
            'ort'=>'string|max:50',
            'semester'=>'max:2',
            'uni'=>'in:TUM,LMU,HM,Andere',
            'aboutyou'=>'max:500',
            'shirtCut'=>'in:M,W',
            'shirtSize'=>'in:XS,S,M,L,XL,xx',
            'ausschuss'=>'in:CTA,WA,FOTO,KULT,FTA,GRAS,VA,MTA,KOMITEE,FA,TA,KICKER'
        ]);

        //Update User in DB
        $user = User::find(Auth::user()->id);

        $user->firstname=$request->get('firstname');
        $user->birthday=$request->get('birthday');
        $user->mobile=$request->get('phone');
        $user->surname=$request->get('surname');
        $user->oly_cat=$request->get('olycat');
        $user->oly_room=$request->get('oly_room');
        $user->street=$request->get('strasse');
        $user->hausnummer=$request->get('hausnummer');
        $user->plz=$request->get('plz');
        $user->ort=$request->get('ort');
        $user->uni=$request->get('uni');
        $user->semester=$request->get('semester');
        $user->studiengang=$request->get('studiengang');
        $user->about_you=$request->get('aboutyou');
        //$user->shirt_cut=$request->get('shirtCut');
        //$user->shirt_size=$request->get('shirtSize');
        $user->has_gesundheitszeugnis=$request->get('gesundheitszeugnis');
        
        $user->save();
        echo("passt");
        return redirect('profil')->with('success','Profil aktualisiert');
    }

    /**
     * Returns view for all users
     */
    public function showUsers() {
        if(Auth::user()->is_admin!=1) {
            return redirect('home')->with('danger','Kein Zugriff');
        }

        $user = User::all();
        foreach($user as $u) {
            $u->registriert = Carbon::parse($u->created_at)->format('D d.m.y');
            //ZUGESAGTE STUNDEN
            $duration = 0;
            foreach($u->activeAssignments as $x) {
                $duration += Carbon::parse($x->shift->starts_at)->diffInHours(Carbon::parse($x->shift->ends_at));
            
            }
            $u->working = $duration;
        }
        return view('user.index', compact('user'));
    }
    /**
     * Returns view for a specific user
     */
    public function showSingleUser($id) {
        if(Auth::user()->is_admin!=1) {
            return redirect('home')->with('danger','Kein Zugriff');
        }
        $user = User::find($id);
        $user->registriert = Carbon::parse($user->created_at)->format('D d.m.y H:i');
        
        //ZUGESAGTE STUNDEN
        $duration = 0;
        foreach($user->activeAssignments as $x) {
            $duration += Carbon::parse($x->shift->starts_at)->diffInHours(Carbon::parse($x->shift->ends_at));
            
        }
        $user->working = $duration;
        return view('user.show', compact('user'));
    }

    /**
     * changepw
     * Changes the password of the current user
     */
    public function changepw(Request $request, $user_id) {
        if(Auth::user()->is_admin!=1) {
            return redirect('home')->with('danger','Kein Zugriff');
        }
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        if($request->get('password')!=$request->get('password_confirmation')){
            return redirect('users')->with('danger','Passwörter stimmen nicht überein.');
        }
        
        $user = User::find($user_id);
        $user->password = Hash::make($request->get('password'));
        $user->save();
        return redirect('users')->with('success','Passwort geändert.');
    }

    /**
     * Returns overview for rewards
     * (soll von jedem aufgerufen werden können)
     */
    public function rewards() {
        //Get assignments from current user
        $filter = ['user_id'=>Auth::user()->id,'status'=>'Aktiv'];
        $assignments = Assignment::where($filter)->get();

        $awe = false;
        $gutscheine = false;
        //Do time calculations

        $ist =  Carbon::createFromTimestamp(0);
        $plan = Carbon::createFromTimestamp(0);
        $gutscheinTimer = Carbon::createFromTimestamp(0);
        $anzahlGutscheine = 0;
        $zero = Carbon::createFromTimestamp(0);

        $openFlag = 0;

        $pflichtstunden = 0;

        $istStunden=0;
        $planStunden=0;

        foreach($assignments as $assignment) {
            $assignment->datum = Carbon::parse($assignment->shift->starts_at)->format('D d.m.y');
            $assignment->start_plan = Carbon::parse($assignment->shift->starts_at)->format('H:i');
            $assignment->end_plan = Carbon::parse($assignment->shift->ends_at)->format('H:i');
            
            $assignment->start_real = Carbon::parse($assignment->start)->format('H:i');
            $assignment->end_real = Carbon::parse($assignment->end)->format('H:i');
            
            $dauer_real = Carbon::parse($assignment->start)->diff(Carbon::parse($assignment->end));
            $dauer_plan = Carbon::parse($assignment->shift->starts_at)->diff(Carbon::parse($assignment->shift->ends_at));

            //Pflichtschicht?
            if($assignment->shift->confirmed!=1) {
                $pflichtstunden += Carbon::parse($assignment->shift->starts_at)->diffInHours(Carbon::parse($assignment->shift->ends_at));
            }
            if($assignment->shift->confirmed==1 && $assignment->confirmed==1) {
                $pflichtstunden = Carbon::parse($assignment->shift->starts_at)->diffInHours(Carbon::parse($assignment->shift->ends_at));
            }
            if($assignment->shift->confirmed==1 && $assignment->confirmed==1) {
                $ist->add($dauer_real);
                $gutscheinTimer->add($dauer_real);
                $anzahlGutscheine+= Carbon::parse($assignment->start)->diffInHours(Carbon::parse($assignment->end)) * $assignment->shift->gutscheine;
            }
            elseif($assignment->shift->confirmed!=1) {
                $gutscheinTimer->add($dauer_plan);
                $anzahlGutscheine+= Carbon::parse($assignment->shift->starts_at)->diffInHours(Carbon::parse($assignment->shift->ends_at)) * $assignment->shift->gutscheine;
                $openFlag++;
            }
            $plan->add($dauer_plan);
            
            $assignment->dauer_real = $dauer_real->format('%H:%I');
            $assignment->dauer_plan = $dauer_plan->format('%H:%I');

            if($assignment->shift->gutscheine>0) {
                $gutscheine=true;
            }
            if($assignment->shift->awe>0) {
                $awe=true;
            }
        }
        
        //
        $ist_h = $ist->diffInHours($zero);
        $ist_m = $ist->minute;
        $shirt_ist_h = ($ist_h-2);
        if(strlen($ist_m)==1) {
            $ist_m = '0'.$ist_m;
        }
        if(strlen($ist_h)==1) {
            $ist_h = '0'.$ist_h;
        }
        $ist_return = $ist_h.':'.$ist_m;

        $plan_h = $plan->diffInHours($zero);
        $plan_m = $plan->minute;
        $shirt_plan_h = $plan_h -2;
        if(strlen($plan_m)==1) {
            $plan_m = '0'.$plan_m   ;
        }
        if(strlen($plan_h)==1) {
            $plan_h = '0'.$plan_h   ;
        }
        $plan_return = $plan_h.':'.$plan_m;


        //$pflichtstunden = $plan->diffInHours($zero);
        if($shirt_ist_h<2) {
            $shirt_ist = '00:00';
        }
        else {
            if(strlen($shirt_ist_h)==1){
                $shirt_ist_h = '0'.$shirt_ist_h;
            }
            $shirt_ist = $shirt_ist_h.':'.$ist_m;
        }

        if($shirt_plan_h<2) {
            $shirt_plan = '00:00';
        }
        else {
            if(strlen($shirt_plan_h)==1){
                $shirt_plan_h = '0'.$shirt_plan_h;
            }
            $shirt_plan = $shirt_plan_h.':'.$plan_m;
        }
        $gutscheinTimer_h=$gutscheinTimer->diffInHours($zero);
        if(strlen($gutscheinTimer_h)==1) {
            $temp_h='0'.$gutscheinTimer_h;
        }
        else {
            $temp_h = $gutscheinTimer_h;
        }
        $temp_m;
        if(strlen($gutscheinTimer->minute)==1) {
            $temp_m = '0'.$gutscheinTimer->minute;
        }
        else {
            $temp_m = $gutscheinTimer->minute;
        }
        $timer_final = $temp_h.':'.$temp_m;

        $timer_post_shirt;
        if($temp_h <2) {
            $timer_post_shirt = '00';
        }
        else {
            $timer_post_shirt = $temp_h-2;
        }

        $gutscheine_ist = $ist_h;

        $gutscheine_post_shirt=0;

        if($anzahlGutscheine-6>=0) {
            $gutscheine_post_shirt = $anzahlGutscheine - 6;
        }
        //$anzahlGutscheine = $anzahlGutscheine-6; //6 Gutscheine für T-Shirt

        $realGutscheine = BenutzerController::gutscheinAnspruch(Auth::user()->id);

        //Create view & return
        //return view('user.rewards',compact('assignments'));
        return view('user.rewards', compact('assignments','realGutscheine','pflichtstunden','gutscheine','awe','plan_return','ist_return','timer_final','openFlag','anzahlGutscheine','gutscheine_post_shirt'));
    }

    /**
     * Returns number (Gutscheine auf die user mit user_id Anrecht hat)
     */
    public static function gutscheinAnspruch($user_id) {
        $aFilter = ['status'=>'Aktiv','user_id'=>$user_id];
        $assignmentsRaw = Assignment::where($aFilter)->get();
        $gutscheine = 0;
        $zeit= Carbon::createFromTimestamp(0);
        $flag=0; //Flags schichten die noch nicht bestätigt sind
        foreach($assignmentsRaw as $ar) {
            //Gibts Gutscheine?
            if($ar->shift->gutscheine>0) {
                //Schicht schon bestätigt?
                if($ar->shift->confirmed==1) {
                    //Diff
                    $diff = Carbon::parse($ar->start)->diffInMinutes(Carbon::parse($ar->end));
                    $gutscheine += ($diff/60)*$ar->shift->gutscheine;
                }
                else {
                    $diff = Carbon::parse($ar->shift->starts_at)->diffInMinutes(Carbon::parse($ar->shift->ends_at));
                    $gutscheine += ($diff/60)*$ar->shift->gutscheine;
                    $flag++;
                }
            }

        }
        //T-Shirt abziehen

        if($gutscheine-6<0) {
            return 0;
        }
        $gutscheine = $gutscheine - 6;

        if($flag>0) {
            $gutscheine = 0.7*$gutscheine;
        }
        $gutscheine = round($gutscheine);
        return $gutscheine;
    }

//Neue Rewards
public function rewarder() {
    //All Assignments
        $filter = ['user_id'=>Auth::user()->id,'status'=>'Aktiv'];
        $assignments = Assignment::where($filter)->get();

    //Bestätigt? Fülle beide Arrays
        $ausstehend = array(); //Array of Assignments
        $confirmed = array(); //Array of Assignments
        foreach($assignments as $a) {
            if($a->shift->confirmed) {
                $confirmed[] = $a;
            }
            else {
                $ausstehend[] = $a;
            }
        }

    //Berechnungen für Ausstehend
        $ausstehend_gutscheine = 0;
        foreach($ausstehend as $a) {
            $a->date = Carbon::parse($a->shift->starts_at)->format('D d.m.');
            $a->start = Carbon::parse($a->shift->starts_at)->format('H:i');
            $a->end = Carbon::parse($a->shift->ends_at)->format('H:i');
            $a->dauer = Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I');
            $a->gutscheine = $a->shift->gutscheine;
            $a->awe = $a->shift->awe;
            $a->faktor = Carbon::parse($a->dauer)->format('H') + Carbon::parse($a->dauer)->format('i')/60;
            $a->gutscheine_summe = number_format($a->gutscheine*$a->faktor,2);
            $ausstehend_gutscheine += $a->gutscheine_summe;
        }
    
    //Berechnungen für Bestätigt
        $zero = Carbon::createFromTimestamp(0);
        $t_max = 0; //In Minutes

        //Get Salarygroups
        $salarygroups = array(); //Array of Salarygroup OBJECTS

        foreach($confirmed as $c) {
            if($c->salarygroup_id) {
                $salarygroup = Salarygroup::find($c->salarygroup_id);
                if(!in_array($salarygroup,$salarygroups)) {
                    $salarygroups[] = $salarygroup;
                }
            }
        }

        //Do calculations for each
            $salarygroup_number = 0;
        foreach($salarygroups as $s) {
            //Number (Col 1)
            $s->number = ++$salarygroup_number;

            //HTML List
            /*$s->shifts = array();
            foreach($s->assignments as $a) {
                $s->shifts[] = $a->shift;
            }*/

            //t (Verfügbare Zeit zum Aufteilen)
            $s->t = AssignmentsController::getDurationInMinutesOfAssignments($s->assignments);
            $s->t_max_readable = date('H:i',mktime(0,$s->t));
            $t_max += $s->t;

        }

        /*
        $salarygroups_ids = SalarygroupsController::findSalaryGroups(Auth::user()->id);
        $salarygroups = array(); //Wird returnt, enthält Salarygroup Objekte
        $sid_counter=0;
        foreach($salarygroups_ids as $s) {
            $salarygroup = Salarygroup::find($s);
            
            $salarygroup->i = ++$sid_counter;

            //t calculator
            $salarygroup->t = AssignmentsController::getDurationInMinutesOfAssignments($salarygroup->assignments);
        } */

        //Readable Time
        //$t_max_readable = $zero->diffInHours($t_max) . ':' . $zero->diff($t_max)->format('%H:%i');
    //Transaktionen
    $transactions = array();

    //RETURN
    return view('user.rewards2',compact('ausstehend','ausstehend_gutscheine','salarygroups','t_max','confirmed','transactions','assignments'));
}
}
