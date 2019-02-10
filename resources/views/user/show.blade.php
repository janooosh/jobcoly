<?php
use \App\Http\Controllers\ShiftsController;
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
                                        <li class="list-group-item"> <a type="button" class="btn btn-default" title="Zur Schicht" target="_blank" href="{{route('shifts.show',$other->shift->id)}}"><span class="fa fa-info"></span></a> {{$other->shift->job->short}} {{$other->shift->area == '' ? '':'('.$other->shift->area.')' }} | {{$other->shift->shiftgroup->name}} <small>{{Carbon::parse($other->shift->starts_at)->format('H:i')}} - {{Carbon::parse($other->shift->ends_at)->format('H:i')}}</small> <span class="badge">{{ShiftsController::getDuration($other->shift->id, '%H:%I')}}</span></li>
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
                            </div>


                    </div>
            </div>
        </div>
    </div>

</div>
@endsection