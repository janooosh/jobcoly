<?php
use \App\Http\Controllers\ApplicationsController;
use \App\Http\Controllers\AssignmentsController;
use \App\Http\Controllers\EvaluationsController;
use \App\Http\Controllers\ShiftsController;
use \App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
?>

@extends('layouts.app')

@section('content')
<script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
</script>
<script src="{{ asset('js/evaluations.js')}}"></script>
{{-- Message --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-7" id="applicantinfo">
        <div class="row">
            <h1>
                {{$a->applicant->firstname}} {{$a->applicant->surname}}
                @if($a->applicant->facebook!='')
                <a type="button" class="btn btn-default btn-circle"><i class="fa fa-facebook"></i></a>
                @endif
                @if($a->applicant->instagram!='')
                <a type="button" class="btn btn-default btn-circle"><i class="fa fa-instagram"></i></a>
                @endif
            </h1>
        </div>
            <div class="row">
                <div class="col-xs-6">
                    <a href="mailto:{{$a->applicant->email}}" title="E-Mail schreiben"><i class="fa fa-envelope"></i> {{$a->applicant->email}}</a>
                </div>
                @if($a->applicant->mobile)
                <div class="col-xs-6">
                    <a href="tel:{{$a->applicant->mobile}}"><i class="fa fa-phone"></i> {{$a->applicant->mobile}}</a>
                </div>
                @endif
            </div>
            <hr />

            <div class="row" id="dorfsituation" style="padding-bottom: 10px;">
                <div class="col-xs-4">
                    <i class="fa fa-home"></i> {{$a->applicant->is_olydorf == '1' ? 'Dorfbewohner/in' : 'Extern'}}
                </div>
                <div class="col-xs-4">
                    <i class="fa fa-group"></i> {{$a->applicant->is_verein == '1' ? 'Vereinsmitglied' : 'Kein Vereinsmitglied'}}
                </div>
                <div class="col-xs-4">
                    <i class='fa fa-plane'></i> {{$a->applicant->is_ehemalig == '1' ? 'Ehemalige/r':'Nicht Ehemalig'}}
                </div>
            </div>

            <div class="row" id="unisituation">
                <div class="col-xs-12">
                <i class="fa fa-graduation-cap"></i> 
                @if($a->applicant->is_student)
                    Studiert
                    @if($a->applicant->studiengang)
                    {{$a->applicant->studiengang}}
                    @endif
                    @if($a->applicant->semester)
                    im {{$a->applicant->semester}}. Semester
                    @endif
                    @if($a->applicant->uni)
                    an folgender Uni: {{$a->applicant->uni}}
                    @endif
                @else
                    Studiert nicht
                @endif
                </div>
            </div>
            <hr />

            @if($a->applicant->is_verein)
            <div class="row">
                <div class="col-md-12">
                    <h5 style="margin-top:0px;">Tätigkeit im Verein</h5>
                </div>
            </div>
            <div class="row">
                @if($a->applicant->is_bierstube)
                <div class="col-md-2">
                    <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Bierstube"><i class="fa fa-beer"></i></button>
                </div>
                @endif

                @if($a->applicant->is_disco)
                <div class="col-md-2">
                    <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Disco/Lounge"><i class="fa fa-glass"></i></button>
                </div>
                @endif

                @if($a->applicant->is_praside)
                <div class="col-md-2">
                    <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Präside"><i class="fa fa-trophy"></i></button>
                </div>
                @endif

                @if($a->applicant->is_dauerjob)
                <div class="col-md-2">
                    <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Dauerjob"><i class="fa fa-gears"></i></button>    
                </div>
                @endif

                @if($a->applicant->ausschuss)
                <div class="col-md-4">
                    <button type="button" class="btn btn-default btn-circle" data-toggle="tooltip" data-placement="bottom" title="Ordentliches Ausschussmitglied"><i class="fa fa-group"></i></button> {{$a->applicant->ausschuss}}   
                </div>
                @endif
                
            </div>
            <hr/>
            @endif

            @if($a->applicant->is_pflichtschicht=='1')
            <div class="row">
                <div class="col-md-12">
                    <h5 style="color:red;"><i class="fa fa-warning"></i> Pflichtschicht</h5>
                </div>
            </div>
            @endif



            <div class="row" id="shiftReference">
                <div class="col-md-12">
                    <i class="fa fa-time"></i>Automatische Zusage: {{$a->ablauf}} (<span id="ablaufApplication"></span>)<br />
                    <i class="fa fa-clock"></i>{{$a->applicant->firstname}} arbeitet bereits {{$a->otherassignmentsduration}} Stunden an der OlyLust.
                    <script>
                        jayCounter("{{$a->expiration}}", "ablaufApplication");
                    </script>
                </div>
                <div class="col-md-12">
                    <h5>Motivation</h5>
                    <p>{{$a->motivation == '' ? '-':$a->motivation}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Bisherige Erfahrungen</h5>
                    <p>{{$a->experience == '' ? '-':$a->experience}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Bemerkungen/Kommentare</h5>
                    <p>{{$a->notes == '' ? '-':$a->notes}}</p>
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
                                        Zugewiesene Schichten ({{count($a->otherassignments)}})
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseShifts" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    @if(count($a->otherassignments)<1)
                                        Keine zugewiesenen Schichten.
                                    @else
                                        <ul class="listgroup">
                                            @foreach($a->otherassignments as $other)
                                                <li class="list-group-item">{{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}} <span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
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
                                        Andere Aktive Bewerbungen ({{count($a->otherapplications)}})
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseApplications" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="panel-body">
                                    @if(count($a->otherapplications)<1)
                                        Keine weiteren Bewerbungen.
                                    @else
                                        <ul class="listgroup">
                                            @foreach($a->otherapplications as $other)
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
                                            Abgelehnte Bewerbungen ({{count($a->rejectedapplications)}})
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseApplicationsRej" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        @if(count($a->rejectedapplications)<1)
                                            Keine abgelehnten Bewerbungen.
                                        @else
                                            <ul class="listgroup">
                                                @foreach($a->rejectedapplications as $other)
                                                    <li class="list-group-item">{{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}} <span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
            </div>



    </div>

    <div class="col-md-5" id="jobinfo">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{$a->shift->job->short}} {{$a->shift->area == ''?'':'('.$a->shift->area.')'}}</h3>
                    {{$a->shift->job->name}}
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <span><b>{{$a->shift->shiftgroup->name}}</b></span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <div class="progress" style="margin-bottom: 0px;">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="{{EvaluationsController::countAssignments($a->shift->id)}}" aria-valuemin="0" aria-valuemax="{{$a->shift->anzahl}}" style="width:{{EvaluationsController::countAssignments($a->shift->id) / $a->shift->anzahl *100}}%">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    {{EvaluationsController::countAssignments($a->shift->id)}} von {{$a->shift->anzahl}} vergeben
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-xs-6">
                    <b>Schicht-ID</b>
                </div>
                <div class="col-xs-6">
                    {{$a->shift->id}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Start</b>
                </div>
                <div class="col-xs-6">
                    {{$a->start}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Ende</b>
                </div>
                <div class="col-xs-6">
                    {{$a->end}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Dauer</b>
                </div>
                <div class="col-xs-6">
                    {{$a->duration}}
                </div>
            </div>
            <hr/>

            <div class="row">
                <div class="col-md-12">
                    <span>{{count($a->mitbewerber)}} Aktive <b>Mitbewerber</b></span>
                    @if(count($a->mitbewerber)>0)
                        <ul>
                            @foreach($a->mitbewerber as $mitbewerber)
                                <li><a href="{{$mitbewerber->id}}" target="_blank" title="Bewerbung öffnen">{{$mitbewerber->applicant->firstname}} {{$mitbewerber->applicant->surname}}</a></li>
                            @endforeach
                        <ul>
                    @endif
                </div>
            </div>
            <hr />

            <div class="row">
                <div class="col-md-12">
                    <span>{{count($a->comanager)}} <b>Co-Manager</b></span>
                    @if(count($a->comanager)>0)
                        <ul>
                            @foreach($a->comanager as $comanager)
                                <li>{{$comanager->firstname}} {{$comanager->surname}} ({{$comanager->email}})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div> {{-- Ende Pannel --}}
    

    <div class="row">
        <div class="col-md-12">
            {{-- Schon entschieden? --}}
            @if($a->status=='Rejected')
            <div class="alert alert-warning"><h4>Bewerbung abgelehnt.</h4></div>
            @elseif($a->status=='Accepted')
            <div class="alert alert-success"><h4>Bewerbung akzeptiert.</h4></div>
            @elseif($a->status=='Cancelled')
            <div class="alert alert-warning"><h4>Bewerbung vom Bewerber zurückgezogen.</h4></div>
            @elseif($a->status!='Aktiv')
            <div class="alert alert-warning"><h4>Sonderstatus: {{$a->status}}</h4></div>
            @else
            <p>Bitte spreche dich stets mit deinen Co-Managern ab.</p>
        </div>
    </div>
    <div class="row" id="entscheidung">
        <div class="col-xs-6">
            <button type="button" class="btn btn-outline btn-success btn-lg" data-toggle="modal" data-target="#zusage"><i class="fa fa-check"></i> Zusagen</button>
        </div>
        <div class="col-xs-6">
            <button type="button" class="btn btn-outline btn-danger btn-lg" data-toggle="modal" data-target="#absage"><i class="fa fa-times"></i> Absagen</button>
        </div>

        {{-- MODALS --}}
        <div class="modal fade" id="zusage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('assignments.store')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">{{Auth::user()->firstname}}, bist du Sicher?</h4>
                    </div>
                    <div class="modal-body">
                       Möchtest du wirklich <b>{{$a->applicant->firstname}} {{$a->applicant->surname}}</b> als <b>{{$a->shift->job->name}}</b>{{$a->shift->area == ''?'':' ('.$a->shift->area.')'}}, <b>{{$a->shift->shiftgroup->name}}</b>, zulassen?<br />
                       Eine Zulassung ist bindend, ein Widerruf ist nicht vorgesehen. Falls du dir nicht sicher bist, kannst du {{$a->applicant->firstname}} auch absagen, dann kann er sich neu bewerben falls noch freie Plätze verfügbar sind.
                        <input type="hidden" id="application" name="application" value="{{$a->id}}"/> {{-- Application ID --}}
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                            <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-check"></i> Verbindlich Zusagen</button>
                    </div>
                </div>
                </form>
                    <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="absage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
            <form method="POST" action="{{ route('reject')}}">
            @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">{{Auth::user()->firstname}}, bist du Sicher?</h4>
                    </div>
                    <div class="modal-body">
                        Möchtest du <b>{{$a->applicant->firstname}} {{$a->applicant->surname}}</b> als {{$a->shift->job->name}} wirklich absagen? <br />
                        Er kann sich jederzeit erneut für diese Schicht bewerben, falls noch Plätze verfügbar sind.
                        <input type="hidden" id="application" name="application" value="{{$a->id}}"/> {{-- Application ID --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                        <button type="submit" class="btn btn-primary btn-danger"><i class="fa fa-times"></i> Absagen</button>
                    </div>
                </div>
            </form>
                        <!-- /.modal-content -->
            </div>
                <!-- /.modal-dialog -->
        </div>

    </div>
    @endif
    <hr />
    <div class="row">
        <div class="col-md-12">
            Bitte wende dich bei Fragen oder Unklarheiten an die RL Personal, <a href="mailto:crew@olylust.de">crew@olylust.de</a>.
        </div>
    </div>
    </div> {{-- Ende Rechte Seite --}}
</div>




@endsection
