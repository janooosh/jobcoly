<?php
use \App\Http\Controllers\ShiftsController;
use \App\Http\Controllers\TransactionController;
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
            </div> {{-- Ende col --}}
        </div> {{-- Ende Row --}}
    </div> {{-- Ende Rechte Spalte --}}

</div> {{-- Ende Major Row --}}
<hr />
<div class="row">
    <div class="col-md-8">
        Bereits erhaltene Gutscheine: <b>{{$user->gutscheine_issued}}</b>
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
                    <td colspan="3"><small>{{$user->gutscheine_issued}} ausgegeben</small></td>
                </tr>
            </tfoot>
        </table>
        <p><small><i>* = Keine Gutscheinausgabe da Warenwert</i></small></p>
        @endif   
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
                            <p><small>Für das T-Shirt werden 6 Gutscheine berechnet. Bitte hier keine Leihshirts eintragen!</small></p>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichersn</button>
            </div>
            </form>
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
                            <option value="mo"> Scavenger Monday (Montag), 3 Gutscheine</option>
                            <option value="fm"> Full Madness (4 Tage), 10 Gutscheine</option>
                        </select>
                        <small><b>Achtung, eine nachträgliche Korrektur ist nicht möglich.</b></small>
                    </div>
                </div>


                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichersn</button>
            </div>
            </form>
        </div>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- Ende Modal Eintritt --}}

{{-- Gutschein Allgemein--}}
<div class="modal fade" id="gutscheine" tabindex="-1" role="dialog" aria-labelledby="gutscheine" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="shirt"> Gutschein-Ausgabe</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('transaction.gutscheinPost')}}">
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
                        <p><small>Max 200</p>
                    </div>
                </div>
            </div>

            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i> Speichersn</button>
            </div>
            </form>
        </div>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- Ende Modal Gutschein --}}

@endsection