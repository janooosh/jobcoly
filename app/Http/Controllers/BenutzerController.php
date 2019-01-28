<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Assignment;
use App\Salarygroup;
use App\Transaction;
use Carbon\Carbon;

class BenutzerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }



/**
 * Save Rewards
 * Zentrale Stelle um Eingaben zu SPEICHERN oder ABZUSCHLIESSEN
 * Wird über das Formular auf Rewards getriggert.
 */
public function saveRewards(Request $request) {
    //Get Data
        $g_fields = $request->get('gut');
        $a_fields = $request->get('awe');
        if(empty($g_fields)) {
            $g_fields = [];
        }
        if(empty($a_fields)) {
            $a_fields = [];
        }
        $si_fields = $request->get('salgroupid'); //Salarygroup-ID
        $aktion = $request->get('saver')[0];

        /**
         * 1.) Gesamtzeit ausgegeben <= Gesamtzeit verfügbar
         * 2.) PRO REIHE:
         *  a) Zeit ausgegeben für Reihe <= Zeit verfügbar für Reihe
         *  b) if(AWE): Gesamtzeit ausgegeben > Pflicht der Reihe
         * 3.) if(Pflichtschicht): Gutscheinzeit >= 8h
         */

         //WERTE
            //Gesamtzeit verfügbar [MINUTEN]
            $t_gesamt_available = 0;

            //Gesamtzeit ausgegeben [MINUTEN] && Gutscheinzeit [MINUTEN]
            $t_g = 0;
            $t_a = 0;
            foreach($g_fields as $g) {
                $t_g+=SalarygroupsController::StringToMin($g);
            }
            foreach($a_fields as $a) {
                $t_a+=SalarygroupsController::StringToMin($a);
            }
            $t_gesamt_spent = $t_g + $t_a;
            
            $counter=0;
            foreach($si_fields as $si) {
                $salarygroup = Salarygroup::find($si);
                $salarygroup->available += AssignmentsController::getDurationInMinutesOfConfirmedAssignments($salarygroup->assignments);
                $t_gesamt_available += $salarygroup->available;

                
            }

            //1.) Gesamtzeit ausgegeben <= Gesamtzeit verfügbar?
            if($t_gesamt_spent>$t_gesamt_available) {
                return redirect('rewards/')->with('danger','Du hast zu viel Zeit verteilt.');
            }

            


            return $t_gesamt_spent;
            



        if($t_a>0&&$t_g<480) {
            return redirect('rewards/')->with('danger','Mind. 8 Gutscheinstunden erforderlich.');
        }

            
            
            foreach($si_fields as $si) {

            }
            

    
        //return $request->input();

    //Decide weather save or submit

    //Perform action
}   

//Neue Rewards
public static function rewarder() {
    //All Assignments
        $filter = ['user_id'=>Auth::user()->id,'status'=>'Aktiv'];
        $assignments = Assignment::where($filter)->orderBy('salarygroup_id')->get();

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
        $t_vergeben = 0; //Vergebene Zeit
        $t_total = 0;

        $g_sum = 0; //Gutschein Summe
        $a_sum = 0; //AWE Summe

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

            //t (Verfügbare Zeit zum Aufteilen)
            $s->t = AssignmentsController::getDurationInMinutesOfConfirmedAssignments($s->assignments);
            $s->t_max_readable = date('H:i',mktime(0,$s->t));
            //print_r($s->t_max_readable);
            $t_total += $s->t;
            $s->t_vergeben = $s->t_a + $s->t_g;
            $t_max += $s->t - $s->t_vergeben;
            $t_vergeben += $s->t_vergeben;

            $s->t_verfuegbar = $s->t - $s->t_vergeben;
            $s->t_verfuegbar = date('H:i',mktime(0,$s->t_verfuegbar));
            
            $s->t_vergeben = date('H:i',mktime(0,$s->t_vergeben));

            //Gutscheine & AWE
            $gutschein_faktor = $s->t_g/60;
            $s->azg = number_format($gutschein_faktor * $s->g,2);
            $g_sum += $s->azg;

            $awe_faktor = $s->t_a/60;
            $s->aza = number_format($awe_faktor * $s->a,2);
            $a_sum += $s->aza;

            //Felder Verteilung G/AWE
            $s->t_g_nice = date('H:i',mktime(0,$s->t_g));
            $s->t_a_nice = date('H:i',mktime(0,$s->t_a));
            
            $s->awe_available = false;
            //return SalarygroupsController::countAttemptGutscheine($salarygroups);
            if($t_vergeben>=$s->p*60 && $s->t_g<$s->t && SalarygroupsController::countAttemptGutscheine($salarygroups)>=SalarygroupsController::getLowestp($salarygroups) ) {
                $s->awe_available=true;
            }

            $g_sum_rounded = round($g_sum);
            $a_sum_rounded = number_format(round($a_sum,2),2,',','.');

        }

        //Generate nice reading of tmax
        $t_max_readable = date('H:i',mktime(0,$t_max));
        $t_vergeben_readable = date('H:i',mktime(0,$t_vergeben));
        $t_total = date('H:i',mktime(0,$t_total));
        

        //Transaktionen
        $transaction_filter = ['user_id'=>Auth::user()->id];
        $transactions = Transaction::where($transaction_filter)->get();
        $gutscheine_erhalten_sum = 0;
        foreach($transactions as $t) {
            $t->datetime = Carbon::parse($t->datetime)->format('d.m. H:i');
            $gutscheine_erhalten_sum += $t->amount;
        }
        //return null;


    //RETURN
    return view('user.rewards2',compact('ausstehend','ausstehend_gutscheine','salarygroups','t_total','t_max','t_max_readable','t_vergeben_readable','confirmed','transactions','assignments','gutscheine_erhalten_sum','g_sum','a_sum','g_sum_rounded','a_sum_rounded'))->with('danger','so ebbes');
}


/**
 * AB HIER: ALTES MODELL - NICHT MEHR RELEVANT
 */

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

}
