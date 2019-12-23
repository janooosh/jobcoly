<?php
use Carbon\Carbon;
use \App\Http\Controllers\TimecalcController;
?>

@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>


<div class="row">
    <div class="col-md-12">
        <h1 class="page-header"><i class="fa fa-trophy"></i> Meine Rewards</h1>
    </div>
</div>

{{-- Messages --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('info')) 
<div class="row">
    <div class="alert alert-info">
        <span class="fa fa-info"></span> {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('danger')) 
<div class="row">
    <div class="alert alert-danger">
        <span class='fa fa-warning'></span> {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        <span class='fa fa-warning'></span> {{$message}}</span>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-9">
<div class="panel panel-success">
        <div class="panel-heading">
            <b>Entlohnungsmodell</b>
        </div>
        <div class="panel-body">
            <p>In diesem Dokument wird erklärt, wie du für deine Schicht entlohnt werden kannst und was dir die Gutscheine bringen. Bei Fragen kannst du uns jederzeit unter crew@olylust.de kontaktieren.</p>
        </div>
</div>
    </div>
    <div class="col-md-3">
            <a type="button" href="{{asset('doc/rewards.pdf')}}" target="_blank" class="btn btn-outline btn-primary btn-lg btn-block"><span class="fa fa-file-pdf-o "></span> Entlohnung</a>
    </div>
</div>
<hr />
@if(count($assignments)<1)
<div class="row">
    <div class="col-md-12">
        <p>Du hast noch keine aktiven Schichten.</p>
        <a href="{{route('assignments.my')}}" title="Meine Schichten" type="button" class="btn btn-default"><i class="fa fa-beer"></i> Meine Schichten</a>
    </div>
</div>
@else

{{-- Personalbogen--}}
<div class="row">
    <div class="col-md-9">
<div class="panel panel-info">
        <div class="panel-body">
            <p>Falls du eine <b>Aufwandsentschädigung (AWE)</b> erhälst, sind weitere Angaben zur Auszahlung erforderlich. Bitte fülle nebenstehenden Personalfragebogen aus und bringe ihn bei einer Gutscheinausgabe vorbei. Alternativ kannst du ihn auch per Post an Studenten im Olympiazentrum e.V., Helene-Mayer-Ring 9, 80809 München senden. Leider ist es aktuell nicht möglich, den Bogen zu digitalisieren. Mitarbeiter der Betriebe und Dauerjobber haben den Bogen wahrscheinlich schon ausgefüllt und müssen diesen natürlich nicht erneut abgeben.</p>
        </div>
</div>
    </div>
    <div class="col-md-3">
            <a type="button" href="{{asset('doc/personalfragebogen-v21.pdf')}}" target="_blank" class="btn btn-outline btn-primary btn-lg btn-block"><span class="fa fa-file-pdf-o "></span> Personalfragebogen</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Übersicht</h4>
        <p>Es wurden {{count($assignments)}} Schichten gefunden. Du hast bereits {{$gutscheine_gesamt}} Gutscheine in Form von Gutscheinen, Shirts und/oder Tickets erhalten (siehe unten).</p>
        @if(Auth::user()->is_pflichtschicht)
        <p style="color:{{$t_for_pflicht>='480'?'green':'black'}};">Du hast bereits {{TimecalcController::MinToString($t_for_pflicht)}} Solidaritätsstunden erfolgreich absolviert.</p>
        @endif
        <hr />
    </div>
</div>

{{-- Not Yet Confirmed --}}
@if(count($not_yet_confirmed)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($not_yet_confirmed)}} Offene Schichten</h4>
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> <b>Aufwandsentschädigung (AWE)</b> kann ausgewählt werden, sobald die Schicht bestätigt wurde.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">Job</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Geplant</th>
                    <th scope="col">Dauer [h]</th>
                    <th scope="col">Gutscheine /h</th>
                    <th scope="col">AWE /h <small>(ab ... h)</small></th>
                    <th scope="col" class="linie_t" data-toggle="tooltip" data-placement="top" title="Gutscheine können bereits im Vorraus ausgegeben werden :)">Σ Gutscheine</th>
                </tr>
            </thead>
            <tbody>
                @foreach($not_yet_confirmed as $a)
                <tr>
                    <td>{{$a->shift->job->name}}</td>
                    <td>{{Carbon::parse($a->start)->format('D d.m.Y')}}</td>
                    <td>{{Carbon::parse($a->start)->format('H:i').' - '.Carbon::parse($a->end)->format('H:i')}}</td>
                    <td>{{Carbon::parse($a->start)->diff(Carbon::parse($a->end))->format('%H:%I')}}</td>
                    <td>{{$a->shift->gutscheine}}</td>
                    <td>{{$a->shift->awe}} <small>(nach {{$a->shift->p}} Stunden)</small></td>
                    <td class="linie_t">{{round($a->shift->gutscheine * Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end))/60,2)}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top: solid 2px #aaa!important;">
                    <td colspan="6" style="text-align:right;"><b>Σ Summe (Erwarteter Anspruch aus Gutscheinen)</b></td>
                    <td class="linie_t"><b>{{round($gutscheine_aus_assignments)}}</b><small><i>{{' ('.$gutscheine_aus_assignments.')'}}</i></small></td>
                </tr>
                {{--<tr>
                    <td colspan="6" style="text-align:right;">Freigegeben</td>
                    <td class="linie_t">{{round(0.7*$gutscheine_aus_assignments)}}</td>
                </tr>--}}
            </tfoot>
        </table>
    </div>
</div>
<hr />
@endif {{-- Ende Ausstehende --}}

{{-- Confirmed --}}
@if(count($confirmed)>0 || count($accepted)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($confirmed)+count($accepted)}} Bestätigte Schichten</h4>
        
    </div>
</div>
<div class="row">
        <div class="alert alert-info">
            <span class="fa fa-info"></span> Sämtliche Schichten, die vor dem 05.04., 08:00h, abgeschossen wurden, liegen der Buchhaltung zur AWE Auszahlung am 15.04.2019 vor. Alle noch nicht abgeschlossenen Schichten können bis zum 30.04.2019 bearbeitet und abgeschlossen werden (Auszahlung zum 15.05.).
        </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p>Alle Stunden: <b><span id='ttotal'>{{TimecalcController::MinToString($t_total)}}</b><small> (davon bereits abgeschlossen: {{TimecalcController::MinToString($t_total_confirmed)}})</small></span></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="POST" action='{{route('rewards.save')}}'>
            @csrf
            <table class="table table-sm table-hover table-bordered">
                <thead>
                    <th scope="col"></th>
                    <th scope="col">Job</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Von ... Bis</th>
                    <th scope="col">Dauer [h]</th>
                    <th scope="col">Gutscheine</th>
                    <th scope="col">AWE</th>
                    <th scope="col" style="border-left: solid 2px #aaa!important;">GUT</th>
                    <th scope="col">AWE</th>
                </thead>
                <tbody>
                    @foreach($accepted as $a)
                    <tr style="background-color:#dff0d8;">
                        <td><span class="fa fa-thumbs-o-up  "></span></td>
                    <td>{{$a->shift->job->name}}<br/><small>Abgeschlossen am {{Carbon::parse($a->payout_created)->format('d.m.Y')}} *</small></td>
                        <td>{{Carbon::parse($a->start)->format('D d.m.Y')}}</td>
                        <td>{{Carbon::parse($a->start)->format('H:i')}} - {{Carbon::parse($a->end)->format('H:i')}}<br /><small>Geplant: {{Carbon::parse($a->shift->starts_at)->format('H:i')}} - {{Carbon::parse($a->shift->ends_at)->format('H:i')}}</small></td>
                        <td>{{Carbon::parse($a->start)->diff(Carbon::parse($a->end))->format('%H:%I')}}<br /><small>Geplant: {{Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I')}}</small></td>
                        <td>{{TimecalcController::MinToString($a->t_g)}}<br /><small><i>* {{$a->shift->gutscheine}} Gutscheine</i></small></td>
                        <td>{{TimecalcController::MinToString($a->t_a)}}<br /><small><i>* {{$a->shift->awe}} € (verfügbar nach {{$a->shift->p}} Stunden)</i></small></td>
                        <td style="border-left: solid 2px #aaa!important;">{{round($a->shift->gutscheine*$a->t_g/60,2)}}</td>
                        <td>{{round($a->shift->awe*$a->t_a/60,2)}} €</td>
                    </tr>
                    @endforeach
                    @foreach($confirmed as $c)
                    <tr>
                        <td>
                            <input type="checkbox" name="selecter[]" value="{{$c->id}}"/>
                            <input type="hidden" name="checker[]" value="{{$c->id}}"/>
                        </td>
                        <td>{{$c->shift->job->name}}</td>
                        <td>{{Carbon::parse($c->start)->format('D d.m.Y')}}</td>
                        <td>{{Carbon::parse($c->start)->format('H:i')}} - {{Carbon::parse($c->end)->format('H:i')}}<br /><small>Geplant: {{Carbon::parse($c->shift->starts_at)->format('H:i')}} - {{Carbon::parse($c->shift->ends_at)->format('H:i')}}</small></td>
                        <td>{{Carbon::parse($c->start)->diff(Carbon::parse($c->end))->format('%H:%I')}}<br /><small>Geplant: {{Carbon::parse($c->shift->starts_at)->diff(Carbon::parse($c->shift->ends_at))->format('%H:%I')}}</small></td>
                        <td><input type="time" id="g{{$c->id}}" name="gutscheine[]" class="form-control" value="{{TimecalcController::MinToString($c->t_g)}}"><small><i>* {{$c->shift->gutscheine}} Gutscheine</i></small></td>
                        <td><input type="time" id="a{{$c->id}}" name="awe[]" class="form-control" value="{{TimecalcController::MinToString($c->t_a)}}"><small><i>* {{$c->shift->awe}} € (verfügbar nach {{$c->shift->p}} Stunden)</i></small></td>
                        <td style="border-left: solid 2px #aaa!important;"><span id="gc{{$c->id}}">{{round($c->shift->gutscheine*$c->t_g/60,2)}}</span></td>
                        <td><span id="ac{{$c->id}}">{{round($c->shift->awe*$c->t_a/60,2)}} €</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr style="border-top: solid 2px #aaa!important;">
                    <td colspan="7" style="text-align:right;"><b>Σ Summe</b></td>
                    <td>{{round($gutscheine_selected,0)}}</td>
                    <td>{{round($awe_selected,2)}} €</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align:right;"><i>Bereits erhalten</i></td>
                    <td >-{{round($gutscheine_gesamt,2)}}</td>
                    <td></td>
                </tr>
                <tr style="border-top: solid 2px #aaa!important;">
                    <td colspan="7" style="text-align:right;"><b>Offen</b></td>
                    <td><b>{{round($gutscheine_selected - $gutscheine_gesamt)}}</b></td>
                    <td></td>
                </tr>
                {{--<tr>
                    <td colspan="6" style="text-align:right;">Freigegeben</td>
                    <td class="linie_t">{{round(0.7*$gutscheine_aus_assignments)}}</td>
                </tr>--}}
            </tfoot>
            </table>
            @if(count($accepted)>0)
            <small>* Alle Schichten, die von dir <i>vor</i> dem 5. April 2019, 08:00h abgeschlossen wurden, werden lt System zum 31.3.2019 abrechnungsrelevant erfasst und sind zur Auszahlung zum 15.04.2019 an die Buchhaltung weitergegeben. Die Erfassung des Zeitstempels der Schichtabschließung wurde erst am 6.4.2019 im System implementiert.</small> <br />
            @endif
            <p>Ausgewählte Schichten ... </p>
            <input type="submit" name='saver[]' class='btn btn-primary' id='save' value="Speichern" alt='Eingaben Speichern'/>
            <input type="submit" name='saver[]' class='btn btn-success' id='submit' value="Abschließen" alt='Eingaben Abschließen'/>
            <br /><br /><p>Du kannst Zeiteingaben jederzeit <b>speichern</b>. Das ist noch keine finale Auswahl und gibt dir die Möglichkeit, auf die Bestätigung von noch offenen Schichten zu warten oder dir alles noch einmal in Ruhe zu überlegen 🙂<br/>
            Bitte schließe zum Schluss die Schichten ab (<b>Abschließen</b>). Das ist insbesondere nötig, sofern du AWE erhalten möchtest. </p>
        </form>
    </div>

</div>
<hr />
@endif {{-- Ende Bestätigte --}}

{{-- Not  Confirmed --}}
@if(count($not_confirmed)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($not_confirmed)}} Nicht-Bestätigte Schichten</h4>
        <div class="alert alert-warning">
            <i class="fa fa-info-circle"></i> Du wurdest für diese Schichten nicht bestätigt. Bitte halte bei Zwischenfragen Rücksprache mit uns. Es werden dir hierfür keine Solidaritätsstunden angerechnet.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">Job</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Geplant</th>
                    <th scope="col">Dauer [h]</th> </tr>
            </thead>
            <tbody>
                @foreach($not_confirmed as $a)
                <tr>
                    <td>{{$a->shift->job->name}}</td>
                    <td>{{Carbon::parse($a->start)->format('D d.m.Y')}}</td>
                    <td>{{Carbon::parse($a->start)->format('H:i').' - '.Carbon::parse($a->end)->format('H:i')}}</td>
                    <td>{{Carbon::parse($a->start)->diff(Carbon::parse($a->end))->format('%H:%I')}}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<hr />
@endif {{-- Ende Not Confirmed --}}

{{-- Ausgaben --}}
<div class="row">
    <div class="col-md-12">
        @if(count($transactions)<1)
            <p>Noch keine Ausgaben erfasst.</p>
        @else
            <h4>{{count($transactions)}} Ausgaben erfasst</h4>
        {{-- Tabelle Transaktionen --}}
        <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Wann?</th>
                <th scope="col">Anzahl</th>
                <th scope="col">Für...</th>
                <th scope="col">Beschreibung</th>
                <th scope="col">Ausgestellt von...</th>
              </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
              <tr>
                <td>{{$t->id}}</td>
                <td>{{Carbon::parse($t->created_at)->format('d.m., H:i')}}</td>
                <td>
                    @if(!$t->ausgabe)
                        <span style="color:grey;"><i>{{$t->amount}} *</i></span>
                    @else
                       <span style="color:green">{{$t->amount}}</span>
                    @endif
                </td>
                <td>{{$t->beschreibung_short}}</td>
                <td>{{$t->beschreibung}}</td>
                <td>{{$t->issuer->firstname.' '.$t->issuer->surname}}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid grey;">
                    <td colspan="2"><b>Σ Summe</b></td>
                    <td><b>{{$gutscheine_gesamt}}</b></td>
                    <td colspan="3"><small>davon als Gutscheine ausbezahlt: {{$gutscheine_issued}}</small></td>
                </tr>
            </tfoot>
        </table>
        <p><small><i>* = Keine Gutscheinausgabe da Warenwert</i></small></p>
        @endif   
    </div>
</div>

{{-- Transaktionen 
@if(count($transactions)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($transactions)}} Ausgaben</h4>
        <p>Bisher hast du Folgendes erhalten:</p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">ID</th>
                    <th scope="col">Beschreibung</th>
                    <th scope="col">Ausgabe am...</th>
                    <th scope="col">Gutscheine</th>
                </tr>
            </thead>
            @foreach($transactions as $t)
                <tr>
                    <td>{{$t->id}}</td>
                    <td>{{$t->beschreibung_short==''? '-':$t->beschreibung_short}}</td>
                    <td>{{$t->datetime}}</td>
                    <td>{{$t->amount}}</td>
                </tr>
            @endforeach
            <tbody>
            <tfoot>
                <tr style="border-top: solid 2px #aaa;">
                    <td colspan="3" style="text-align:right;"><b>Σ Summe</b></td>
                    <td><b>{{$gutscheine_issued}}</b></td>
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
</div> {{-- Ende Transatktionen --}}
{{--@else

@endif--}}

<br />
@endif


<script src="{{asset('js/rewards.js')}}"></script>
@endsection
