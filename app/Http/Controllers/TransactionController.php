<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use App\Shirt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
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

    public function auszahlung_start() {
        if(!Auth::user()->is_buchhalter) {
            return redirect('home')->with('danger','Keine Berechtigung');
        }
        return view('transactions.awe_start');
    }


    public function auszahlung_entry(Request $request) {
        $request->validate([
            'start'=>'required|date',
            'ende'=>'required|date',
        ]);

        $start = Carbon::parse($request->get('start'));
        $ende = Carbon::parse($request->get('ende'));

        if(!$ende->greaterThanOrEqualTo($start)) {
            return view('transactions.awe_start')->with('warning','Ende muss vor dem Anfang liegen.');
        }
        
        return TransactionController::auszahlung($start,$ende);
    }

     /** AWE Funktion -> Zeige alle Auszahlungen an */
    public function auszahlung($from,$to) {
        if(!Auth::user()->is_buchhalter) {
            return redirect('home')->with('danger','Keine Berechtigung');
        }
        $user = User::all();
        $awe_summe = 0;

        foreach($user as $u) {
            $u->awe = 0;

            $payout_candidates = $u->acceptedAssignments;
            foreach($payout_candidates as $p) {
                //Zeitraum passt?
                if(Carbon::parse($p->payout_created)->greaterThanOrEqualTo($from) && Carbon::parse($p->payout_created)->lessThanOrEqualTo($to)) {
                    $u->awe += $p->t_a/60*$p->shift->awe;
                }
            }
            $u->awe = round($u->awe,2);
            $awe_summe+=$u->awe;
        }
        $paid_user = array();
        foreach($user as $u) {
            if($u->awe>0 && !$u->is_admin) {
                $paid_user[] = $u;
            }
        }
        return view('transactions.awe', compact('paid_user','awe_summe','from','to'));
    }

    public function index()
    {   
        if(!Auth::user()->is_admin) {
            return redirect('home')->with('warning','Kein Zugriff');
        }
        //Show all Users
        $user = User::all();
        return view('transactions.index',compact('user'));
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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
    /**
     * shirtPost = T-Shirt Ausgabe
     * Es werden nur feste Ausgaben gewertet.
     * Receiver = User ID vom Empfänger
     */
    public function shirtPost (Request $request) {
        if(!Auth::user()->is_admin) {
            return redirect('home')->with('warning','Kein Zugriff');
        }

        $user = User::find($request->get('receiver'));

        if ($user->is_praside == '0' && ($user->ausschuss == '' || $user->ausschuss == '0')) {
            // Nur Ausschussmitglieder und Präsiden müssen Solidaritätsstunden leisten
            $shirt_value = 3;
        } else {
            $soli_hours = 8;
            $worked_hours = 0;
            foreach($user->activeAssignments as $a) {
                $worked_hours += Carbon::parse($a->start)->diffInHours(Carbon::parse($a->end));
            }
            $shirt_value = $worked_hours > $soli_hours ? 3 : 1.5;
        }

        $shirt_beschreibung = 'Schnitt: '.$request->get('shirtcut').', Größe: '.$request->get('shirtsize');

        $request->validate([
            'receiver'=>'required|int|exists:users,id',
            'shirtcut'=>'required|in:M,W',
            'shirtsize'=>'required|in:S,M,L,XL,XX',
        ]);

        //New Transaction
        $transaction = new Transaction([
            'user_id'=>$request->get('receiver'),
            'amount'=>$shirt_value,
            'issuer_id'=>Auth::user()->id,
            'datetime'=>Carbon::now(),
            'beschreibung_short'=>'T-Shirt',
            'beschreibung'=>$shirt_beschreibung,
            'ausgabe'=>0,
        ]);
        $transaction->save();

        //New Shirt
        $shirt = new Shirt([
            'user_id'=>$request->get('receiver'),
            'shirt_cut'=>$request->get('shirtcut'),
            'shirt_size'=>$request->get('shirtsize'),
            'ausgeber'=>Auth::user()->id,
            'transaction_id'=>$transaction->id,
        ]);
        $shirt->save();

        return redirect('users/'.$request->get('receiver'))->with('success','T-Shirt Ausgabe gespeichert.');
    }

        /**
     * shirtPost = T-Shirt Ausgabe
     * Es werden nur feste Ausgaben gewertet.
     * Receiver = User ID vom Empfänger
     */
    public function ticketPost (Request $request) {
        if(!Auth::user()->is_admin) {
            return redirect('home')->with('warning','Kein Zugriff');
        }

        $request->validate([
            'receiver'=>'required|int|exists:users,id',
            'ticketday'=>'required|in:do,fr,sa,mo,fm',
        ]);
        $value=0;
        $day = $request->get('ticketday');

        if($day==='do'||$day==='fr') {
            $value=3;
        }
        elseif($day==='sa') {
            $value=6;
        }
        elseif($day==='mo') {
            $value=2;
        }
        else{
            $value=10; //Full Madness
        }

        $transaction = new Transaction([
            'user_id'=>$request->get('receiver'),
            'amount'=>$value,
            'issuer_id'=>Auth::user()->id,
            'datetime'=>Carbon::now(),
            'beschreibung_short'=>'Ticket',
            'beschreibung'=>$day,
            'ausgabe'=>0,
        ]);
        $transaction->save();

        return redirect('users/'.$request->get('receiver'))->with('success','Ausgabe der Eintrittskarte gespeichert.'); 
    }

    public function gutscheinPost(Request $request) {
        if(!Auth::user()->is_admin) {
            return redirect('home')->with('warning','Kein Zugriff');
        }
        $request->validate([
            'receiver'=>'required|int|exists:users,id',
            'gutscheinanzahl'=>'required|int|max:99|min:1',
        ]);

        $transaction = new Transaction([
            'user_id'=>$request->get('receiver'),
            'amount'=>$request->get('gutscheinanzahl'),
            'issuer_id'=>Auth::user()->id,
            'datetime'=>Carbon::now(),
            'beschreibung_short'=>'Guscheine',
            'beschreibung'=>$request->get('gutscheinbeschreibung'),
            'ausgabe'=>1,
        ]);
        $transaction->save();

        return redirect('users/'.$request->get('receiver'))->with('success','Gutscheinausgabe erfasst.');
    }

    /**
     * Counts how many shirts a user (user_id) already received.
     */
    public static function countShirts($user_id) {
        $shirts = Shirt::all();
        $counter=0;
        foreach($shirts as $shirt) {
            if($shirt->user_id==$user_id) {
                $counter++;
            }
        }
        return $counter;
    }
}
