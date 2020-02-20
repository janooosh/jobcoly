<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Assignment;
use App\Shift;
use App\Salarygroup;
use App\Transaction;
use Carbon\Carbon;

class BenutzerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

public function saveRewardsNew(Request $request) {
    $aktion = $request->get('saver')[0];
    //return $aktion;
    //Validate Inputs
    $selecter = $request->get('selecter');
    $checker = $request->get('checker'); //IDs (hidden field)
    $gutscheine_fields = $request->get('gutscheine');
    $awe_fields = $request->get('awe');
    //Überprüfung: 1.) Empty, 2.) AWE Fields gleiche Länge Gutschein Fields? 3.) Selecter Gleiche Länge Gutschein Fields?
    if(empty($selecter)) {
        return redirect('rewards')->with('warning','Keine Schicht ausgewählt. Ich hab nix gespeichert.');
    }
    $assignments_selected = array(); //Assignments that are selected
    foreach($selecter as $s) {
        try {
            $assignment = Assignment::find($s);
            if($assignment->accepted || !$assignment->shift->confirmed) {
                return redirect('rewards')->with('danger','Unzulässige Aktion, die Schicht kann nicht weiter bearbeitet werden. Bitte kontaktiere jan.haehl@olylust.de bei Fragen.');
            }
        }
        catch(Exception $e) {
            return redirect('rewards')->with('danger','Du Seggel, das ist unzulässsig.');
        }
        $assignments_selected[] = $assignment;
    }

    //+++ ÜBERPRÜFUNG +++ yeah...
    $fehler = array();

    //Wie viel Gutscheine hat er schon bekommen?
    $gutscheine_gesamt = 0;
    $t_filter = ['user_id'=>Auth::user()->id];
    $transactions = Transaction::where($t_filter)->get();

    foreach($transactions as $t) {
        $gutscheine_gesamt+=$t->amount;
    }

    //Zeiten
    $t_ausgegeben=0; //Zeit ausgegeben
    $t_available=0; //Zeit Verfügbar
    $t_awe=0; //Zeit für AWE ausgegeben
    $t_gutscheine=0; //Zeit für Gutscheine ausgegeben
    $gutscheine_temp = 0; //Wie viele Gutscheine lässt er sich hie auszahlen? Brauchen wir um später zu checken ob er genug Zeit in Gutscheine gesteckt hat, die er ggf. schon erhalten hat

    $accepted = array();
    $confirmed = array();

    $a_filter = ['user_id'=>Auth::user()->id,'accepted'=>true];
    $accepted_assignments = Assignment::where($a_filter)->get();
    foreach($accepted_assignments as $a) {
        if($a->shift->confirmed && $a->confirmed && $a->accepted) {
            $accepted[] = $a;
            $t_available += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
            $t_awe+=$a->t_a;
            $t_gutscheine+=$a->t_g;
            $gutscheine_temp += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end))/60*$a->shift->gutscheine;
        }
        /*elseif($a->shift->confirmed && $a->confirmed && !$a->accepted) {
            $confirmed[] = $a;
            $t_available += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
            $t_awe+=$a->t_a;
            $t_gutscheine+=$a->t_g;
        } */
    }
    //Gehe durch jede Reihe
    
    for($r = 0;$r<count($assignments_selected);$r++) {
        
        $a = $assignments_selected[$r];
        $z = array_search($a->id,$checker);
        $gutscheine_selectedMins =  TimecalcController::StringToMin($gutscheine_fields[$z]);
        $awe_selectedMins = TimecalcController::StringToMin($awe_fields[$z]);
        //Zeit ausgegeben für Reihe <= Zeit verfügbar für Reihe
        $t_ausgegeben = 0;
        $t_ausgegeben += $gutscheine_selectedMins;
        $t_ausgegeben += $awe_selectedMins;
        $t_available = Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
        if($t_ausgegeben > $t_available) {
            $fehler[] = "Du hast mindestens für eine Schicht zu viel Zeit ausgegeben";
        } 

        //Collect t_gutscheine & t_awe
        $t_gutscheine += $gutscheine_selectedMins;
        $t_awe += $awe_selectedMins;

        $gutscheine_temp += $gutscheine_selectedMins * $a->shift->gutscheine;
    }

    //Gehe erneut durch jede Reihe - zweite Iteration nötig, da beim ersten Mal erst die Daten gesammelt werden müssen (t_gutscheine, t_awe...)
    for($x = 0;$x<count($assignments_selected);$x++) {
        $a = $assignments_selected[$x];
        $z = array_search($a->id, $checker);
        $gutscheine_selectedMins =  TimecalcController::StringToMin($gutscheine_fields[$z]);
        $awe_selectedMins = TimecalcController::StringToMin($awe_fields[$z]);

        //If AWE, Pflicht an Gutscheinen erfüllt?
        if($t_awe>0 && $t_gutscheine<($a->shift->p*60)) {
            $fehler[] = "Du hast mindestens für eine Schicht zu früh AWE ausgewählt.";
        }
    }

    //Gesamtzeit ausgegeben <= Gesamtzeit verfügbar
    /*if($t_ausgegeben>$t_available) {
        $fehler[] = "Du hast zu viel Zeit ausgegeben";
    }*/

    //Genug Gutscheine ausgewählt, da er ggf. schon welche hat?
    if($gutscheine_temp<$gutscheine_gesamt) {
        $fehler[] = "Du hast bereits Gutscheine erhalten und zu wenig Zeit für Gutscheine ausgewählt.";
    }

    //Pflichtschicht
    if(Auth::user()->is_pflichtschicht) {
        if($t_awe > 0 && $t_gutscheine<480) {
            $fehler[] = "Als Teil deiner Solidaritätsschicht musst du mindestens 28h auf Gutscheine auswählen, bevor du AWE erhalten kannst.";
        }
    }

    //Save
    for($x = 0;$x<count($assignments_selected);$x++) {
        try {
            $a = $assignments_selected[$x];
            $z = array_search($a->id,$checker);
            $a->t_g = SalarygroupsController::StringToMin($gutscheine_fields[$z]);
            $a->t_a = SalarygroupsController::StringToMin($awe_fields[$z]);
            
            if(empty($fehler) && $aktion=='Abschließen') {
                $a->accepted = 1;
                $a->payout_created = Carbon::now()->format('Y-m-d');
            }
            $a->save();
        }
        catch(Exception $e) {
            return redirect('rewards')->with('danger','Es ist ein Fehler aufgetreten. Bitte folgende Fehlermeldung an jan.haehl@olylust.de senden.'.$e);
        }
    }
    if($aktion=='Abschließen' && count($fehler)>0) {
        $fehlerliste = "Es sind folgende Fehler aufgetreten: ";
        for($t = 0;$t<count($fehler);$t++) {
            $fehlerliste = $fehlerliste.$fehler[$t].", ";
        }
        $fehlerliste = $fehlerliste." Bitte überprüfe deine Angaben in der Tabelle!";
        return redirect('rewards')->with('danger',$fehlerliste);
    }
    return redirect('rewards')->with('success','Erledigt.');
}


/**
 * OLD!!!!!
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

        //NUR SPEICHERN??
        if($aktion=='Speichern') {
            //Keine Überprüfung
            $a_counter = 0;
            $g_counter = 0;
            //Iteriere über alle Einträge und speichere die t_g und t_a Werte OHNE Überprüfung in den Salarygroups... Go!!
            foreach($si_fields as $si) {
                //Finde Salarygroup
                $salarygroup = Salarygroup::find($si);
                $salarygroup->t_g = SalarygroupsController::StringToMin($g_fields[$g_counter]);
                $g_counter++;
                if(array_key_exists($a_counter,$a_fields)) {
                    echo $si;
                    $salarygroup->t_a = SalarygroupsController::StringToMin($a_fields[$a_counter]);
                    $a_counter++;
                }
                else {
                    $salarygroup->t_a = 0;
                }
                $salarygroup->save();
            }

            //return;
            return redirect('rewards/')->with('info','Eingaben gespeichert, jedoch ohne Überprüfung. Schließe die Schicht ab sobald du fertig bist.');
        
        }

        /**
         * 1.) Gesamtzeit ausgegeben <= Gesamtzeit verfügbar
         * 2.) PRO REIHE:
         *  a) Zeit ausgegeben für Reihe <= Zeit verfügbar für Reihe
         *  b) if(AWE): Gesamtzeit ausgegeben > Pflicht der Reihe
         * 3.) if(Pflichtschicht): Gutscheinzeit >= 8h
         */

         //WERTE
            //Gesamtzeit verfügbar [MINUTEN]
            $errors = 0; 
            $prepared_for_save = array(); //Salarygroups, bereit zu speichern wenn keine errors auftauchen
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
            
            $g_counter=0; //Iteriert, da die gut[] und awe[] Arrays nicht synchron mit den si indeze laufen
            $a_counter=0;
            $salarygroups = array();
            //Welche Zeit kann maximal verteilt werden?
            foreach($si_fields as $si) {
                $awe_used = 0; // mins / Hat er AWE genutzt?
                $salarygroup = Salarygroup::find($si);
                $salarygroup->available += AssignmentsController::getDurationInMinutesOfConfirmedAssignments($salarygroup->assignments);
                $t_gesamt_available += $salarygroup->available; 

                //2a) Zeit ausgegeben für Reihe <= Zeit verfügbar für Reihe?
                $salarygroup->ausgegeben = 0;
                $salarygroup->ausgegeben += SalarygroupsController::StringToMin($g_fields[$g_counter]); //Gutscheinfeld gibts safe!
                if(array_key_exists($a_counter,$a_fields)) {
                    $awe_used = SalarygroupsController::StringToMin($a_fields[$a_counter]);
                    $salarygroup->ausgegeben += $awe_used;
                    $a_counter;
                }
                $g_counter++; //
                $salarygroup->awe_used = $awe_used;
                if($salarygroup->ausgegeben > $salarygroup->available){
                    return redirect('rewards/')->with('danger','Du hast für mindestens eine Gruppe zu viel Zeit ausgegeben.');
                    $errors++;
                }
                else {
                    $prepared_for_save = $salarygroup;
                }
                $salarygroups[] = $salarygroup;
            }

            //2b) if(Awe): Gesamtzeit ausgegeben > Pflicht der Reihe
            foreach($salarygroups as $salarygroup) {
                if($salarygroup->awe_used>0 && $t_gesamt_spent < $salarygroup->p) {
                    return redirect('Unzulässiger Gebrauch von AWE');
                }
            }
            

            //1.) Gesamtzeit ausgegeben <= Gesamtzeit verfügbar?
            if($t_gesamt_spent>$t_gesamt_available) {
                return redirect('rewards/')->with('danger','Mööp, Du hast zu viel Zeit verteilt. Try again.');
            }



    //Perform action
}   

//Ganz Neue Rewards
public static function doRewards() {
    //Muss Pflichtstunden machen?
    $pflichtstunden = false;
    $has_valid_ausschuss = false;
    if(Auth::user()->ausschuss != "0" && !is_null(Auth::user()->ausschuss)) {
        $has_valid_ausschuss = true;
    }
    if((Auth::user()->is_praside == 1) || $has_valid_ausschuss ) {
        $pflichtstunden = true;
    }
    $time_for_pflicht = 0;
    $time_basis_for_pflicht = 0;
    $time_real_for_pflicht = 0;
    $gutscheine_for_pflicht = 0;
    //Gutscheine die abgezogen werden.
    $abzug_erwartet = 0; //Vor bestätigung
    

    //Assignments
    $a_filter = ['user_id'=>Auth::user()->id];
    $assignments = Assignment::where($a_filter)->get();
    //Transaktionen
    $t_filter = ['user_id'=>Auth::user()->id];
    $transactions = Transaction::where($t_filter)->get();

    $accepted = array();
    $confirmed = array();
    $not_confirmed = array();
    $not_yet_confirmed = array();
    $unclear = array();

    $gutscheine_aus_assignments = 0;

    foreach($assignments as $a) {
        $time_basis_for_pflicht += Carbon::parse($a->shift->starts_at)->diffInMinutes($a->shift->ends_at);
        $time_real_for_pflicht += Carbon::parse($a->start)->diffInMinutes($a->end);
        if($a->shift->confirmed && $a->confirmed && $a->accepted) {
            $accepted[] = $a;
            $time_for_pflicht += Carbon::parse($a->start)->diffInMinutes($a->end);
        }
        elseif($a->shift->confirmed && $a->confirmed && !$a->accepted) {
            $confirmed[] = $a;
            $time_for_pflicht += Carbon::parse($a->start)->diffInMinutes($a->end);
        }
        elseif($a->shift->confirmed && !$a->confirmed) {
            $not_confirmed[] = $a;
            $time_for_pflicht += Carbon::parse($a->start)->diffInMinutes($a->end);
        }
        elseif(!$a->shift->confirmed) {
            $not_yet_confirmed[] = $a;
            $time_for_pflicht += Carbon::parse($a->shift->start)->diffInMinutes($a->shift->end);
            $gutscheine_aus_assignments += Carbon::parse($a->start)->diffInMinutes($a->end)/60*$a->shift->gutscheine;
        }
        else {
            $unclear[] = $a;
        }

        //Berechnung Pflicht
        if($a->shift->confirmed) {
            $gutscheine_for_pflicht += Carbon::parse($a->start)->diffInMinutes($a->end)/60*$a->shift->gutscheine;
        }
        else {
            $gutscheine_for_pflicht += Carbon::parse($a->shift->start)->diffInMinutes($a->shift->end)/60*$a->shift->gutscheine;
        }
    }
    #return $gutscheine_for_pflicht;
    $gutscheine_issued = BenutzerController::gutscheineIssued(Auth::user()->id);
    $gutscheine_gesamt = 0;
    //Gesamtanspruch Gutscheine
    foreach($transactions as $t) {
        $gutscheine_gesamt+=$t->amount;
    }

    //Berechnungen
    $t_for_pflicht = 0; //Pflichtstunden
    $t_total = 0; //Bestätigt + Abgeschlossen
    $t_total_confirmed = 0; //Bestätigte Zeit
    $gutscheine_selected = 0;
    $awe_selected = 0;

    foreach($confirmed as $c) {
       $t_total += Carbon::parse($c->start)->diffInMinutes(Carbon::parse($c->end));
        $t_for_pflicht += Carbon::parse($c->shift->starts_at)->diffInMinutes(Carbon::parse($c->shift->ends_at));
        $gutscheine_selected += $c->t_g/60 * $c->shift->gutscheine;
        $awe_selected += $c->t_a/60 * $c->shift->awe;
        //$gutscheine_for_pflicht += Carbon::parse($c->start)->diffInMinutes($->end)/60*$a->shift->gutscheine;
    }
    foreach($accepted as $a) {
        $t_total_confirmed += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
        $t_for_pflicht += Carbon::parse($a->shift->starts_at)->diffInMinutes(Carbon::parse($a->shift->ends_at));
        $t_total += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
        $gutscheine_selected += $a->t_g/60 * $a->shift->gutscheine;
        $awe_selected += $a->t_a/60 * $a->shift->awe;
    }
    //Werden Gutscheine abgezogen?
    $gutschein_grenze = 16*60;
    $abzug = false; //Asume false
    if(min($time_basis_for_pflicht, $time_real_for_pflicht) <= $gutschein_grenze && $pflichtstunden) {
        $abzug = true;
    }
    #if($time_basis_for_pflich)

    if($time_for_pflicht < (29*60) && $pflichtstunden) {
        $abzug_erwartet = $gutscheine_for_pflicht / 2;
    }
    
    //Pflichtstunde

    return view('rewards.user', compact('abzug','assignments','accepted','confirmed','not_confirmed','not_yet_confirmed','gutscheine_issued','transactions','gutscheine_aus_assignments','gutscheine_gesamt','t_total_confirmed','t_for_pflicht','t_total','gutscheine_selected','awe_selected','pflichtstunden','abzug_erwartet'));
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
                if(!in_array($salarygroup,$salarygroups) && !$salarygroup->confirmed) {
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

            //$s->t_verfuegbar = $s->t - $s->t_vergeben;
            //Verfügbare Zeit = Gesamte Zeit - Vergebene Zeit
            $s->t_verfuegbar = '';
            /*if($s->t - $s->t_vergeben < 0){
                $s->t_verfuegbar = $s->t_verfuegbar.'- ';
            }*/
            
            $s->t_verfuegbar = $s->t_verfuegbar.SalarygroupsController::MinToString($s->t - $s->t_vergeben);
            //return $s->t_verfuegbar;
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
        $t_vergeben_readable = SalarygroupsController::MinToString($t_vergeben);
        //date('H:i',mktime(0,$t_vergeben));
        $t_total = SalarygroupsController::MinToString($t_total);
        //date('H:i',mktime(0,$t_total));
        

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
        
        $time_basis_for_pflicht = 0;
        $time_real_for_pflicht = 0;

        //Pflichtmitglied?
        $is_ausschuss = false;
        $abzug = false;
        
        if($user->ausschuss && $user->ausschuss != "0") {
            $is_ausschuss = true;
        }

        //ZUGESAGTE STUNDEN
        $duration = 0;

        foreach($user->activeAssignments as $x) {
            $duration += Carbon::parse($x->shift->starts_at)->diffInHours(Carbon::parse($x->shift->ends_at));
            $time_basis_for_pflicht += Carbon::parse($x->shift->starts_at)->diffInMinutes($x->shift->ends_at);
            $time_real_for_pflicht += Carbon::parse($x->start)->diffInMinutes($x->end);
        }
        $user->working = $duration;

        /**
         * User Zeiten
         *  */
            //All Assignments
            $filter = ['user_id'=>$user->id,'status'=>'Aktiv'];
            $assignments = Assignment::where($filter)->get();

            //Bestätigt? Fülle beide Arrays
            $confirmed = array(); //Array of Assignments
            $not_yet_confirmed = array(); //Array of Assignments
            $not_confirmed = array(); //Array of Assignments
            $unclear = array(); //Array of assignments
            foreach($assignments as $a) {

                //Schicht bestätigt & Assignment Bestätigt (==1 nicht benötigt, hab ich getestet)
                if($a->shift->confirmed && $a->confirmed) {
                    $confirmed[] = $a;
                }
                //Schicht bestägigt & Assignment NICHT Bestätigt
                elseif($a->shift->confirmed && !$a->confirmed) {
                    $not_confirmed[] = $a;
                }
                //Schicht NOCH nicht bestätigt
                elseif(!$a->shift->confirmed) {
                    $not_yet_confirmed[] = $a;
                }
            }

            if($user->is_praside || $is_ausschuss) {
                $gutschein_grenze = 16*60;
                if(min($time_basis_for_pflicht,$time_real_for_pflicht) < $gutschein_grenze) {

                    $abzug = true;
                }
            }

            $user->t_confirmed = BenutzerController::durationOfAssignments($confirmed);
            $user->t_not_yet_confirmed = BenutzerController::durationOfAssignments($not_yet_confirmed);
            $user->t_not_confirmed = BenutzerController::durationOfAssignments($not_confirmed);
            $user->t_unclear = BenutzerController::durationOfAssignments($unclear);

            $user->gutscheine_issued = BenutzerController::gutscheineIssued($user->id);
            $user->gutscheine_gesamt = BenutzerController::gutscheineGesamt($user->id);

            //Assign arrays of assignments to user
            $user->a_confirmed = $confirmed;
            $user->a_not_yet_confirmed = $not_yet_confirmed;
            $user->a_not_confirmed=$not_confirmed;
            

            $shifts_all_filter = ['status'=>'Aktiv'];
            $shifts_all = Shift::where($shifts_all_filter)->orderBy('starts_at')->get();

        return view('user.show', compact('user','shifts_all','abzug'));
    }

    /**
     * Calculate Gutscheine
     * 
     * Falls Schicht, Assignment & Salarygroup bestätigt -> Grundlage salarygroup t_g
     * falls Schicht, Assignment ja aber salarygroup nicht -> Grundlage assignment
     * Falls Schicht ja aber Assignment und salarygroup nicht -> Grundlage shift
     */

    public static function calculateGutscheine($assignments) {
        $gutscheine = 0;
        
        foreach($assignments as $a) {
            if($a->shift->confirmed && $a->confirmed && $a->accepted) {
                $gutscheine += $a->t_g/60*$a->shift->gutscheine;
            }
            elseif($a->shift->confirmed && $a->confirmed && !$a->accepted) {
                $gutscheine += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end))/60*$a->shift->gutscheine;
            }
            elseif($a->shift->confirmed && !$a->confirmed) {
                $gutscheine += Carbon::parse($a->shift->starts_at)->diffInMinutes(Carbon::parse($a->shift->ends_at))/60*$a->shift->gutscheine;
            }
            elseif(!$a->shift->confirmed) {
                $gutscheine += Carbon::parse($a->shift->starts_at)->diffInMinutes(Carbon::parse($a->shift->ends_at))/60*$a->shift->gutscheine;
            }

        }   
        return $gutscheine;
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
    /**
     * durationOfAssignments
     * Adds up the durations of an array of assignments.
     * Returns time in minutes.
     */
    public static function durationOfAssignments($assignments) {
        if(empty($assignments)) {
            return 0;
        }
        $duration = 0;
        foreach($assignments as $a) {
            //If confirmed -> nehme Zeit aus Assignments, sonst aus Shift
            if($a->shift->confirmed) {
                $duration += Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end));
            }
            elseif(!$a->shift->confirmed) { //Shift nicht confirmed (abgeschlossen)
                $duration += Carbon::parse($a->shift->starts_at)->diffInMinutes(Carbon::parse($a->shift->ends_at));
            }
        }
        return 0;
    }

    /**
     * countGutscheine (user_id)
     */
    public static function gutscheineIssued($user_id) {
        $user = User::find($user_id);
        $transactions = $user->transactions;
        $gutscheine = 0;
        foreach($transactions as $t) {
            if($t->ausgabe) {
                $gutscheine += $t->amount;
            }
        }

        return $gutscheine;
    }

    public static function gutscheineGesamt($user_id) {
        $user = User::find($user_id);
        $transactions = $user->transactions;
        $gutscheine = 0;
        foreach($transactions as $t) {
            $gutscheine += $t->amount;
        }
        return $gutscheine;
    }

    /**
     * EXPORT FUNKTIONEN
     */
    public function exportAll() {
        $users = User::get(); // All users

        foreach($users as $u) {
            $duration = 0;
            foreach($u->activeAssignments as $x) {
                $duration += Carbon::parse($x->shift->starts_at)->diffInMinutes(Carbon::parse($x->shift->ends_at));
            }
            $u->working = $duration;
        }
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($users, ['firstname'=>'Vorname','surname'=>'Nachname','email'=>'E-Mail','mobile'=>'Mobil','is_pflichtschicht'=>'Pflichtschicht','is_praside'=>'Präside','ausschuss'=>'Ausschuss','working'=>'Zugewiesene Zeit (Minuten)'])->download();
    }

}
