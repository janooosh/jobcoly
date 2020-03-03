<?php
use \App\Http\Controllers\ShiftsController;
use \App\Http\Controllers\AssignmentsController;
use \App\Http\Controllers\TransactionController;
use \App\Http\Controllers\BenutzerController;
use Carbon\Carbon;
?>

@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
</script>
<div class="row">
    <div class="col-md-12">
        <a href="{{route('users')}}" type="button" class="btn btn-default" title="Zu den Usern"><i class="fa fa-hand-o-left "></i> Zurück zu den Usern</a>
    </div>
    <div class="col-lg-8 page-header">
        <h1 >{{$user->firstname.' '.$user->surname}}
            @if($user->facebook!='')
                <a type="button" class="btn btn-default btn-circle"><i class="fa fa-facebook"></i></a>
            @endif
            @if($user->instagram!='')
                <a type="button" class="btn btn-default btn-circle"><i class="fa fa-instagram"></i></a>
            @endif
        </h1>
        <div class="row">
            <div class="col-xs-6">
                <a href="mailto:{{$user->email}}" title="E-Mail schreiben"><i class="fa fa-envelope"></i> {{$user->email}}</a>
            </div>
            @if($user->mobile)
            <div class="col-xs-6">
                <a href="tel:{{$user->mobile}}"><i class="fa fa-phone"></i> {{$user->mobile}}</a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Message --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('danger')) 
<div class="row">
    <div class="alert alert-danger">
        {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        {{$message}}
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6">

        <div class="row" id="dorfsituation" style="padding-bottom: 10px;">
            <div class="col-xs-4">
                <i class="fa fa-home"></i> {{$user->oly_room == '' ? 'Extern' : $user->oly_room}}
            </div>
            <div class="col-xs-4">
                <i class="fa fa-group"></i> {{$user->is_verein == '1' ? 'Vereinsmitglied' : 'Kein Vereinsmitglied'}}
            </div>
            <div class="col-xs-4">
                <i class='fa fa-plane'></i> {{$user->is_ehemalig == '1' ? 'Ehemalige/r':'Nicht Ehemalig'}}
            </div>
        </div>

        <div class="row" id="adresse" style="padding-bottom: 10px;">
            <div class="col-md-12">
                <i class="fa fa-envelope"></i> {{$user->street}} {{$user->hausnummer}}, {{$user->plz}} {{$user->ort}}
            </div>
        </div>

        <div class="row" id="shirt" style="padding-bottom: 10px;">
            <div class="col-md-12">
                <i class="fa fa-qq"></i> Schnitt: {{$user->shirt_cut}}, Größe: {{$user->shirt_size}}
            </div>
        </div>

        <div class="row" id="unisituation" style="padding-bottom: 10px;">
            <div class="col-xs-12">
                <i class="fa fa-graduation-cap"></i> 
                @if($user->is_student)
                    Studiert
                @if($user->studiengang)
                    {{$user->studiengang}}
                @endif
                @if($user->semester)
                    im {{$user->semester}}. Semester
                @endif
                @if($user->uni)
                    an folgender Uni: {{$user->uni}}
                @endif
                @else
                    Studiert nicht
                @endif
            </div>
        </div>
        @if($user->is_verein)
        <div class="row">
            <div class="col-md-12">
                <h5 style="margin-top:0px;">Tätigkeit im Verein</h5>
            </div>
        </div>
        <div class="row">
            @if($user->is_bierstube)
            <div class="col-md-2">
                <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Bierstube"><i class="fa fa-beer"></i></button>
            </div>
            @endif

            @if($user->is_disco)
            <div class="col-md-2">
                <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Disco/Lounge"><i class="fa fa-glass"></i></button>
            </div>
            @endif

            @if($user->is_praside)
            <div class="col-md-2">
                <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Präside"><i class="fa fa-trophy"></i></button>
            </div>
            @endif

            @if($user->is_dauerjob)
            <div class="col-md-2">
                <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Dauerjob"><i class="fa fa-gears"></i></button>    
            </div>
            @endif

            @if($user->ausschuss)
            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Ordentliches Ausschussmitglied"><i class="fa fa-group"></i></button> {{$user->ausschuss}}   
            </div>
            @endif
            
        </div>
        <hr/>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h5>Über Mich</h5>
                <p>{{$user->about_you==''?'-':$user->about_you}}</p>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <i class="fa fa-clock-o"></i> Zugewiesene Stunden: {{$user->working}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <i class="fa fa-medkit"></i> Gesundheitszeugnis laut Bewerbung: 
                @if($user->has_gesundheitszeugnis===1)
                Ja
                @elseif($user->has_gesundheitszeugnis===0)
                Nein
                @else
                Keine Angabe
                @endif
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                    <div class="panel-group" id="accordion">
                        {{-- Schichtpannel --}}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseShifts" aria-expanded="false" class="collapsed">
                                        Zugewiesene Schichten ({{count($user->activeAssignments)}})
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseShifts" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    @if(count($user->activeAssignments)<1)
                                        Keine zugewiesenen Schichten.
                                    @else
                                        <ul class="listgroup">
                                            @foreach($user->activeAssignments as $other)
                                        <li class="list-group-item"> <a type="button" class="btn btn-default" title="Zur Schicht" target="_blank" href="{{route('shifts.show',$other->shift->id)}}"><span class="fa fa-info"></span></a> {{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}} <small>{{Carbon::parse($other->shift->starts_at)->format('d.m., H:i')}} - {{Carbon::parse($other->shift->ends_at)->format('H:i')}}</small> <span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Aktive Bewerbungen --}}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseApplications" aria-expanded="false" class="collapsed">
                                        Aktive Bewerbungen ({{count($user->activeApplications)}})
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseApplications" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    @if(count($user->activeApplications)<1)
                                        Keine aktiven Bewerbungen.
                                    @else
                                        <ul class="listgroup">
                                            @foreach($user->activeApplications as $other)
                                                <li class="list-group-item"><a href="{{$other->id}}" target="_blank" title="Bewerbung einsehen">{{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}}</a><small> (Ablauf: {{Carbon::parse($other->expiration)->format('D. d. M. H:i')}})</small><span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Abgelehnte Bewerbungen --}}
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseApplicationsRej" aria-expanded="false" class="collapsed">
                                            Abgelehnte Bewerbungen ({{count($user->rejectedApplications)}})
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseApplicationsRej" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        @if(count($user->rejectedApplications)<1)
                                            Keine abgelehnten Bewerbungen.
                                        @else
                                            <ul class="listgroup">
                                                @foreach($user->rejectedApplications as $other)
                                                    <li class="list-group-item">{{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}} <span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                        </div> {{-- Ende Panel --}}

                    </div>{{-- Ende Panelgroup --}}
                    <a type="button" class="btn btn-default" data-toggle="modal" data-target="#assign"><span class="fa fa-plus-square"></span> Neue Zuweisung</a>

            </div> {{-- Ende col --}}
        </div> {{-- Ende Row --}}
    </div> {{-- Ende Rechte Spalte --}}

</div> {{-- Ende Major Row --}}
<hr />
<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-7">
                Anspruch aus bestätigten Schichten: <br />
                Empfehlung aus offenen Schichten: <br />
                Abzug Solidaritätsstunden: <br />
                Bereits erhaltene Gutscheine: <br />
                Gutscheine aus Waren: 

            </div>
            <div class="col-md-5">
                {{BenutzerController::calculateGutscheine($user->a_confirmed)}}<br />
                {{round(0.4*BenutzerController::calculateGutscheine($user->a_not_yet_confirmed),2)}} <small>({{BenutzerController::calculateGutscheine($user->a_not_yet_confirmed)}})</small><br />
                - {{BenutzerController::calculateAbzug($user)}} <br />
                - {{$user->gutscheine_issued}} <br />
                - {{$user->gutscheine_gesamt - $user->gutscheine_issued}} <br />
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-7">
                <b>Empfohlene Ausgabe:</b> <br />
                <small>Maximal Mögliche Ausgabe:</small>
            </div>
            <div class="col-md-5">
                <b>{{ceil(BenutzerController::calculateGutscheine($user->a_confirmed) + 0.4*BenutzerController::calculateGutscheine($user->a_not_yet_confirmed) - $user->gutscheine_gesamt - BenutzerController::calculateAbzug($user))}}</b><br />
                <small>{{ceil(BenutzerController::calculateGutscheine($user->a_confirmed) + BenutzerController::calculateGutscheine($user->a_not_yet_confirmed) - $user->gutscheine_gesamt - BenutzerController::calculateAbzug($user))}}</small>
            </div>
        </div>

    </div>

    <div class="col-md-4">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#shirts"><span class="fa fa-gift"></span> T-Shirt</button>
        <a type="button" class="btn btn-default" data-toggle="modal" data-target="#tickets"><span class="fa fa-ticket"></span> Eintrittskarte</a>
        <a type="button" class="btn btn-default" data-toggle="modal" data-target="#gutscheine"><span class="fa fa-money"></span> Gutschein</a>

    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        @if(count($user->transactions)<1)
            <p>Noch keine Ausgaben erfasst.</p>
        @else
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
                @foreach($user->transactions as $t)
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
                    <td><b>{{$user->gutscheine_gesamt}}</b></td>
                    <td colspan="3"><small></small></td>
                </tr>
            </tfoot>
        </table>
        <p><small><i>* = Keine Gutscheinausgabe da Warenwert</i></small></p>
        @endif   
    </div>
</div>
<hr />

{{-- Schicht Reihe --}}
<div class="row">
    <div class="col-md-12">
        <h5>{{count($user->activeAssignments)}} zugesagte Schichten</h5>
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Job</th>
                    <th scope="col">Gruppe</th>
                    <th scope="col">Zeit</th>
                    <th scope="col">Dauer</th>
                    <th scope="col">Entlohnung</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7"><b>{{count($user->a_confirmed)}} bestätigte Schicht(en)</b></td>
                </tr>

                {{-- Bestätigt --}}
                @foreach($user->a_confirmed as $a)
                <tr>
                    <td>{{$a->id}}</td>
                    <td><a href="{{route('shifts.show',$a->shift->id)}}" target="_blank"> {{$a->shift->job->name}} ({{$a->shift->job->short}})</td>
                    <td>{{$a->shift->shiftgroup->name}}</td>
                    <td>{{Carbon::parse($a->start)->format('d.m, H:i')}} - {{Carbon::parse($a->end)->format('H:i')}}</td>
                    <td>{{Carbon::parse($a->start)->diff(Carbon::parse($a->end))->format('%H:%I')}}</td>
                    @if($a->accepted)
                        <td><small>Abgeschlossen. {{$a->shift->gutscheine}} G /h, {{$a->shift->awe}} €/h.</small></td>
                        <td>{{$a->t_g/60*$a->shift->gutscheine}} </td>
                    @else
                        <td><small>Warte auf Auswahl. {{$a->shift->gutscheine}} G /h, {{$a->shift->awe}} €/h.</small></td>
                        <td>{{Carbon::parse($a->start)->diffInMinutes(Carbon::parse($a->end))/60*$a->shift->gutscheine}} </td>
                    @endif
                </tr>
                @endforeach
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                    <td><b>Σ Summe</b></td>
                    <td>{{BenutzerController::calculateGutscheine($user->a_confirmed)}}</td>
                </tr>

                {{-- Noch nicht bestätigt --}}
                <tr style="border-top: 3px solid grey;">
                        <td colspan="2"><b>{{count($user->a_not_yet_confirmed)}} offene Schicht(en)</b></td>
                        <td colspan="3"></td>
                        <td><small>Gutscheine /h</small></td>
                        <td><small>Gutscheine</small></td>
                </tr>
                @foreach($user->a_not_yet_confirmed as $a)
                <tr>
                    <td>{{$a->id}}</td>
                <td><a href="{{route('shifts.show',$a->shift->id)}}" target="_blank"><span class="fa fa-info"></span></a> {{$a->shift->job->name}} ({{$a->shift->job->short}}) <a href="/supervisor/team/{{$a->shift->id}}/review" target="_blank"><span class="fa fa-key"></span></a></td>
                    <td>{{$a->shift->shiftgroup->name}}</td>
                    <td>{{Carbon::parse($a->shift->starts_at)->format('d.m, H:i')}} - {{Carbon::parse($a->shift->ends_at)->format('H:i')}}</td>
                    <td>{{Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I')}}</td>
                    <td>{{$a->shift->gutscheine}}</td>
                    <td>{{round(Carbon::parse($a->shift->starts_at)->diffInMinutes(Carbon::parse($a->shift->ends_at))/60*$a->shift->gutscheine,2)}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3"><small>Empfohlene Ausgabe: {{round(0.4*BenutzerController::calculateGutscheine($user->a_not_yet_confirmed))}} Gutscheine</small></td>
                    <td><b>Σ Summe</b></td>
                    <td>{{BenutzerController::calculateGutscheine($user->a_not_yet_confirmed)}}</td>
                </tr>

                {{-- Endgültig Nicht bestätigt --}}
                <tr>
                        <td colspan="7"><b>{{count($user->a_not_confirmed)}} nicht bestätigte Schicht(en)</b></td>

                </tr>
                @foreach($user->a_not_confirmed as $a)
                <tr>
                    <td>{{$a->id}}</td>
                    <td><a href="{{route('shifts.show',$a->shift->id)}}" target="_blank"><span class="fa fa-info"></span></a> {{$a->shift->job->name}} ({{$a->shift->job->short}})</td>
                    <td>{{$a->shift->shiftgroup->name}}</td>
                    <td>{{Carbon::parse($a->shift->starts_at)->format('d.m, H:i')}} - {{Carbon::parse($a->shift->ends_at)->format('H:i')}}</td>
                    <td>{{Carbon::parse($a->shift->starts_at)->diff(Carbon::parse($a->shift->ends_at))->format('%H:%I')}}</td>
                    <td colspan="2"><i>Keine Entlohnung.</i></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

{{-- MODALS --}}
{{-- T-Shirts --}}
<div class="modal fade" id="shirts" tabindex="-1" role="dialog" aria-labelledby="shirts" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="shirt"> T-Shirt Ausgabe</h4>
            </div>
            <div class="modal-body">
                @if(TransactionController::countShirts($user->id)>0)
                <p style="color:red;"><b>{{$user->firstname}} hat bereits {{TransactionController::countShirts($user->id)}} Shirt(s) erhalten...</b></p>
                @endif
                <form method="POST" action="{{route('transaction.shirtPost')}}">
                    @csrf
                Shirt-Wunsch: <b>{{$user->shirt_cut=='M'?'Männlich':'Weiblich'}}</b>, Größe: <b>{{$user->shirt_size=='XX'?'XXL':$user->shirt_size}}</b>. <br />
                @if($user->facebook)
                    {{$user->firstname}} möchte das Shirt laut Umfrage
                    @if($user->facebook=='ja')
                        <b>behalten</b>.
                    @else 
                        <b>nicht behalten</b>.
                    @endif
                @else
                    {{$user->firstname}} hat an der Shirt-Umfrage nicht teilgenommen.
                @endif
                <br />
                <br />

                    <div class="row">
                        <input type="hidden" id="receiver" name="receiver" value="{{$user->id}}"/>
                        <div class="col-md-6">
                        <label for="shirtcut">Schnitt</label>
                        <select id="shirtcut" type="selection" class="form-control" name="shirtcut" required>
                            <option value="" disabled>Bitte auswählen...</option>
                            <option value="M" {{$user->shirt_cut=='M'?'selected':''}}>Männlich</option>
                            <option value="W" {{$user->shirt_cut=='W'?'selected':''}}>Weiblich</option>
                        </select>
                        </div>
                        <div class="col-md-6">
                            <label for="shirtcut">Größe</label>
                            <select id="shirtsize" type="selection" class="form-control" name="shirtsize" required>
                                <option value="" disabled>Bitte auswählen...</option>
                                <option value="" {{$user->shirt_size=='XS'?'selected':''}} disabled>XS</option>
                                <option value="S" {{$user->shirt_size=='S'?'selected':''}}>S</option>
                                <option value="M" {{$user->shirt_size=='M'?'selected':''}}>M</option>
                                <option value="L" {{$user->shirt_size=='L'?'selected':''}}>L</option>
                                <option value="XL" {{$user->shirt_size=='XL'?'selected':''}}>XL</option>
                                <option value="XX" {{$user->shirt_size=='XX'?'selected':''}}>XXL</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if($user->shirt_size=='XS')
                                <p style='color:red;'><small>XS ist nicht verfügbar!</small></p>
                            @endif
                            <p><small>Für das T-Shirt wird 1 Stunde berechnet. Bitte hier keine Leihshirts eintragen!</small></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichern</button>
            </div>

        </div>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- Ende Modal T-Shirts --}}

{{-- Eintritt--}}
<div class="modal fade" id="tickets" tabindex="-1" role="dialog" aria-labelledby="tickets" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="shirt"> Eintrittskarten-Ausgabe</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('transaction.ticketPost')}}">
                    @csrf
                <input type="hidden" id="receiver" name="receiver" value="{{$user->id}}"/>       
                Bitte ein Ticket auswählen. Bitte stets die 'Normalpreis' - Version des Tickets ausgeben. <br />
                <div class="row">
                    <div class="col-md-12">                                    
                        <label for="ticketday">Ticket</label>
                        <select id="ticketday" type="selection" class="form-control" name="ticketday" required>
                            <option value="" disabled selected>Bitte auswählen...</option>
                            <option value="do"> Weiberfasching (Donnerstag), 3 Gutscheine</option>
                            <option value="fr"> DER Studentenfasching (Freitag), 3 Gutscheine</option>
                            <option value="sa"> Legendärer Samstagsfasching (Samstag), 6 Gutscheine</option>
                            <option value="mo"> Krückenmontag (Montag), 2 Gutscheine</option>
                            <option value="fm"> Full Madness (4 Tage), 10 Gutscheine</option>
                        </select>
                        <small><b>Achtung, eine nachträgliche Korrektur ist nicht möglich.</b></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichern</button>
                </div>
                </form>
            </div>
        </div>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- Ende Modal Eintritt --}}

{{-- Gutschein Allgemein--}}
<div class="modal fade" id="gutscheine" tabindex="-1" role="dialog" aria-labelledby="gutscheine" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form method="POST" action="{{route('transaction.gutscheinPost')}}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="shirt"> Gutschein-Ausgabe</h4>
                </div>
                <div class="modal-body">

                        @csrf
                    <input type="hidden" id="receiver" name="receiver" value="{{$user->id}}"/>
                    <div class="row">
                        <div class="col-md-4">
                            <label for='gutscheinanzahl' max='99'>Anzahl</label>
                            <input type='number' class='form-control' name='gutscheinanzahl' id='gutscheinanzahl' required>
                        </div>
                        <div class="col-md-8">
                                <label for='gutscheinbeschreibung'>Beschreibung</label>
                                <input type='text' class='form-control' name='gutscheinbeschreibung' id='gutscheinbeschreibung' placeholder='Optional'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><small>Max 200</small></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichern</button>
                </div>

            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- Ende Modal Gutschein --}}

{{-- MODAL SCHICHTZUWEISUNG --}}
<div class="modal fade" id="assign" tabindex="-1" role="dialog" aria-labelledby="assign" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form method="POST" action="{{route('assignment.assign')}}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Schichtzuweisung</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-1">
                        <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i></button>
                    </div>
                    <div class="col-xs-11">
                        <input type="text" class="form-control" id="searchassignment" oninput="searchTable('searchassignment','assignmenttable')" placeholder="Durchsuchen...."/>
                        <input type="hidden" name="userToAssign" value="{{$user->id}}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <table class="table table-condensed" id="assignmenttable">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Job</th>
                                            <th scope="col">Start</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shifts_all as $s)
                                        @if(!AssignmentsController::isAssigned($user->id,$s->id))
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="shiftselect[]" value="{{$s->id}}" {{AssignmentsController::isAssigned($user->id,$s->id)?'checked':''}}>
                                                    </div>
                                                </td>
                                                <td>{{$s->job->name}} ({{$s->job->short}})</td>
                                                <td>{{Carbon::parse($s->starts_at)->format('D d.m., H:i')}} - {{Carbon::parse($s->ends_at)->format('H:i')}}</td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        </form>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection