<?php
use \App\Http\Controllers\PrivilegeController;
?>

@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
        <div class="col-lg-12">
            <h1 class="page-header">Schicht bearbeiten</h1>
        </div>
    </div>

{{-- Warning --}}
@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        {{$message}}
    </div>
</div>
@endif

{{-- Error --}}
@if($errors->any())
<div class="alert alert-danger">
    <p><b>Bitte korrigiere die Fehler:</b></p>
    <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <b>ACHTUNG:</b> Deine Änderungen haben Auswirkungen auf ALLE bestehenden Schichtzuweisungen und Bewerbungen dieser Schicht.<br />
            Änderungen an den Manager-/Supervisorrollen, der Schichtgruppe, der Jobbezeichnung und der Area werden nur auf Anfrage bearbeitet.
        </div>
    </div>
</div>

@if(count($jobs)>0 and count($shiftgroups)>0)
{{-- Form --}}
<form method="POST" action="{{ route('shifts.update', $shift->id)}}">
@method('PATCH')
@csrf
<!-- Jobauswahl-->
    <div class="row" >  
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftjob">Job *</label>        
            <select class="form-control" id="shiftjob" name="shiftjob" disabled required autofocus>
                <option value="" disabled>Bitte auswählen</option>
                @foreach($jobs as $job)
                    <option value="{{$job->id}}" {{$shift->job->id == $job->id ? 'selected' : ''}} >{{$job->short}} | {{$job->name}}</option>
                    @endforeach
            </select>
        </div>
        <!-- Gruppe -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
                <label for="shiftgroup">Gruppe *</label>        
                <select class="form-control" id="shiftgroup" name="shiftgroup" disabled required autofocus>
                    <option value="" disabled>Bitte auswählen</option>
                    @foreach($shiftgroups as $shiftgroup)
                        <option value="{{$shiftgroup->id}}" {{$shift->shiftgroup->id == $shiftgroup->id ? 'selected' : ''}}>{{$shiftgroup->name}}</option>
                    @endforeach
                </select>
        </div>
        <!-- Areaauswahl -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftarea">Area</label>
            <select class="form-control" id="shiftarea" name="shiftarea" disabled autofocus>
                <option value="" {{$shift->area == '' ? 'selected' : ''}}>Keine Area</option>
                <option value="Bierstube" {{$shift->area == 'Bierstube' ? 'selected' : ''}}>Bierstube</option>
                <option value="Lounge" {{$shift->area == 'Lounge' ? 'selected' : ''}}>Lounge</option>
                <option value="Disco" {{$shift->area == 'Disco' ? 'selected' : ''}}>Disco</option>
                <option value="Saal" {{$shift->area == 'Saal' ? 'selected' : ''}}>Saal</option>
                <option value="Taverne" {{$shift->area == 'Taverne' ? 'selected' : ''}}>Shotbar</option>
                <option value="Haupteingang" {{$shift->area == 'Haupteingang' ? 'selected' : ''}}>Haupteingang</option>
                <option value="Foyer" {{$shift->area == 'Foyer' ? 'selected' : ''}}>Foyer</option>
            </select>
        </div>

    </div>
    <div class="row">

    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstart">Start *</label>
    <input class="form-control datepicker" id="shiftstart" name="shiftstart" type="text" value="{{$shift->shiftstart}}" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstarttime">Zeit *</label>
            <input class="form-control" id="shiftstarttime" name="shiftstarttime" type="time" value="{{$shift->shiftstarttime}}" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftend">Ende *</label>
            <input class="form-control datepicker" id="shiftend" name="shiftend" type="text" value="{{$shift->shiftend}}" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftendtime">Zeit *</label>
            <input class="form-control" id="shiftendtime" name="shiftendtime" type="time" value="{{$shift->shiftendtime}}" required autofocus/>
    </div>

    </div>

    <div class="row">
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftanzahl">Anzahl *</label>
        <input class="form-control" id="shiftanzahl" name="shiftanzahl" type="number" value="{{$shift->anzahl}}" required autofocus/>
        </div>
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftgutscheine">Gutscheine (/h)</label>
            <input class="form-control" id="shiftgutscheine" name="shiftgutscheine" type="number" value="{{$shift->gutscheine}}" autofocus/>
        </div>
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftawe">AWE (/h)</label>
            <input class="form-control" id="shiftawe" name="shiftawe" type="number" value="{{$shift->awe}}" autofocus/>
        </div>
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftstatus">Status</label>
            <select class="form-control" id="shiftstatus" name="shiftstatus">
                <option value="Aktiv" {{$shift->active == 'Aktiv' ? 'selected' : ''}}>Aktiv</option>
                <option value="Inaktiv" {{$shift->status == 'Inaktiv' ? 'selected' : ''}}>Inaktiv</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <h4>Managers</h4>
            <ul>
                @foreach($shift->managers as $manager)
                    <li>{{$manager->user->firstname}} {{$manager->user->surname}} ({{$manager->user->email}})</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <h4>Supervisors</h4>
            <ul>
                @foreach($shift->supervisors as $supervisor)
                    <li>{{$supervisor->user->firstname}} {{$supervisor->user->surname}} ({{$supervisor->user->email}})</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 form-group" style="padding-bottom: 20px;">
            <label for="shiftdescription">Beschreibung</label>
                <textarea class="form-control" id="shiftdescription" name="shiftdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier etwas Informatives/Lustiges rein.">{{$shift->description}}</textarea>
            <p class="help-block">Maximal 500 Zeichen.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Speichern') }}
            </button>
        </div>
    </div>
</form>
@else
<p>Du hast noch keine Jobs erstellt. Ohne Jobs kannst du keine Schichten erstellen.</p>
<a href="{{route('jobs.create')}}" class="btn btn-default" >Job erstellen</a>
@endif
@endsection