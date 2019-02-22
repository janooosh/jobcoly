<?php
use Carbon\Carbon;
use \App\Http\Controllers\PrivilegeController;
?>

@extends('layouts.app')
@section('content')

{{-- Überschrift --}}
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><span class="fa fa-calendar"></span> Schichtplan</h1>
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

{{-- Gruppenauswahl --}}
<div class="row">
    <form method="POST" action="{{route('shiftplan.show')}}">
    @csrf
    <div class="col-xs-4">
        <p>Bitte wähle eine Gruppe aus:</p>
    </div>
    <div class="col-xs-4">

            <select class="form-control" name="shiftgroup" id="shiftgroup" required>
                <option value="" disabled>Bitte auswählen...</option>
                @foreach($shiftgroups as $shiftgroup)
                    <option value="{{$shiftgroup->id}}" {{$shiftgroup->id==$group->id ? 'selected':''}}>{{$shiftgroup->name}}</option>
                @endforeach
            </select>
           
    </div>
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary"><span class="fa fa-rocket"></span> Los!</button>
    </div>
    </form>
</div>

{{-- Leer und Gruppenüberschrift --}}
<hr />
<h2>{{$group->name}}</h2>
<input type="checkbox" id='oks' value=""> Unbesetzte Schichten verstecken <br />
<input type="checkbox" id='uks' value=""> Vergebene Schichten verstecken
<br /><br />
{{-- Tabellenstart --}}
<table class="table table-xs table-bordered">
    <thead>
    </thead>
    <tbody>
        {{-- Iteriere über Areas --}}
        @foreach($areas as $area)
        <tr style="border:0px;">
            <td colspan="6"><b>{{$area==''?'Keine Area':$area}}</b></td>
        </tr>
            {{-- Iteriere über Assignments in dieser Area --}}
            @foreach($shifts as $shift)
            @if($shift->area == $area)
                {{-- Vergebene --}}
                @foreach($shift->activeAssignments as $a)
                    <tr name='ok' style="background-color:rgba(0,255,0,0.04);">
                        <td>{{Carbon::parse($a->shift->starts_at)->format('D d.m.')}}</td>
                        <td>{{$a->shift->job->short}}</td>
                        <td><a href="{{route('shifts.show',$a->shift->id)}}" target="_blank" title="Zur Schicht"><button type="button" class="btn btn-outline btn-primary btn-xs"><span class="fa fa-info"></span></button></a> {{$a->shift->job->name}} </td>
                        <td><a href="{{route('users.view',$a->user->id)}}" target="_blank" title="Zu {{$a->user->firstname}}'s Profil"><button type="button" class="btn btn-outline btn-success btn-xs"><span class="fa fa-user"></span></button></a> {{$a->user->firstname.' '.$a->user->surname}} {{$a->user->has_gesundheitszeugnis?'(G)':''}}</td>
                        <td>{{Carbon::parse($a->shift->starts_at)->format('H:i')}}</td>
                        <td>{{Carbon::parse($a->shift->ends_at)->format('H:i')}}</td>
                    </tr>
                @endforeach
                {{-- Offene --}}
                @if(count($shift->activeAssignments) < $shift->anzahl)
                    <tr name='frei' style="background-color:rgba(255,0,0,0.1);">
                        <td>{{Carbon::parse($shift->starts_at)->format('D d.m.')}}</td>
                        <td>{{$shift->job->short}}</td>
                        <td><a style="color:#fff!important;" href="{{route('shifts.show',$shift->id)}}" target="_blank" title="Zur Schicht"><button type="button" class="btn btn-outline btn-primary btn-xs"><span class="fa fa-info"></span></button></a> {{$shift->job->name}}</td>
                        <td>{{$shift->anzahl-count($shift->activeAssignments)}} Frei <small>{{Carbon::parse($shift->starts_at)->format('H:i').' - '.Carbon::parse($shift->ends_at)->format('H:i')}}</small></td>
                        
                        @if(PrivilegeController::isManager(Auth::user()->id,$shift->id))
                        @if(count($shift->activeApplications)>0)
                        <td colspan="2" style="background-color:#337ab7!important; color:#fff">
                                <a style="color:#fff!important;" href="/applications/evaluate" target="_blank"><button type="button" class="btn btn-success btn-sm"><span class="fa fa-info"></span></button> {{count($shift->activeApplications)}} Bewerbung{{count($shift->activeApplications)>1?'en':''}} 🤩 </a>
                        </td>
                       @else
                       <td>
                            {{count($shift->activeApplications)}} Bewerbung{{count($shift->activeApplications)>1?'en':''}}
                        </td>
                        @endif
                        @elseif(count($shift->activeApplications)>0)
                        <td style="background-color:337ab7!important;">
                            {{count($shift->activeApplications)}} Bewerbungen
                        </td>
                        <td><small>Manager: </small>
                            @foreach($shift->managers as $privilege)
                                <small>{{$privilege->user->firstname.' '.$privilege->user->surname.', '}}</small>
                            @endforeach
                        </td>
                        @else
                        <td>
                                😔 <small>Keine Bewerbungen</small>
                        </td>
                        <td>
                                <small>Manager: </small>
                            @foreach($shift->managers as $privilege)
                                <small>{{$privilege->user->firstname.' '.$privilege->user->surname.', '}}</small>
                            @endforeach
                        </td>
                        @endif

                    </tr>
                @endif 
            @endif
            @endforeach
        @endforeach
    </tbody>
</table>
<br />

<script src="{{ asset('js/schichtplan.js')}}"></script>
@endsection