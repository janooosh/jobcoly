@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
        <div class="col-lg-12">
            <h1 class="page-header">Neue Schicht anlegen</h1>
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

@if(count($jobs)>0 and count($shiftgroups)>0)
{{-- Form --}}
<form method="POST" action="{{ route('shifts.store')}}">
@csrf
    <div class="row" >
        <!-- Jobauswahl-->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftjob">Job *</label>        
            <select class="form-control" id="shiftjob" name="shiftjob" required autofocus>
                <option value="" selected disabled>Bitte auswählen</option>
                @foreach($jobs as $job)
                    <option value="{{$job->id}}">{{$job->short}} | {{$job->name}}</option>
                @endforeach
            </select>
        </div>
        <!-- Gruppe -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
                <label for="shiftgroup">Gruppe *</label>        
                <select class="form-control" id="shiftgroup" name="shiftgroup" required autofocus>
                    <option value="" selected disabled>Bitte auswählen</option>
                    @foreach($shiftgroups as $shiftgroup)
                        <option value="{{$shiftgroup->id}}">{{$shiftgroup->name}}</option>
                    @endforeach
                </select>
        </div>
        <!-- Areaauswahl -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftarea">Area</label>
            <select class="form-control" id="shiftarea" name="shiftarea" autofocus>
                <option value="" selected>Keine Area</option>
                <option value="Bierstube">Bierstube</option>
                <option value="Lounge">Lounge</option>
                <option value="Disco">Disco</option>
                <option value="Saal">Saal</option>
                <option value="Taverne">Taverne</option>
                <option value="Haupteingang">Haupteingang</option>
                <option value="Foyer">Foyer</option>
            </select>
        </div>

    </div>
    <div class="row">

    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstart">Start *</label>
            <input class="form-control datepicker" id="shiftstart" name="shiftstart" type="text" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstarttime">Zeit *</label>
            <input class="form-control" id="shiftstarttime" name="shiftstarttime" type="time" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftend">Ende *</label>
            <input class="form-control datepicker" id="shiftend" name="shiftend" type="text" required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftendtime">Zeit *</label>
            <input class="form-control" id="shiftendtime" name="shiftendtime" type="time" required autofocus/>
    </div>

    </div>

    <div class="row">
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftanzahl">Anzahl *</label>
            <input class="form-control" id="shiftanzahl" name="shiftanzahl" type="number" required autofocus/>
        </div>
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstatus">Status</label>
            <select class="form-control" id="shiftstatus" name="shiftstatus">
                <option value="Aktiv" selected>Aktiv</option>
                <option value="Inaktiv">Inaktiv</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group" style="padding-bottom: 20px;">
            <label for="shiftmanager">Manager *</label>
            <select multiple class="form-control" id="shiftmanager" name="shiftmanager[]" required autofocus>
                    <option disabled>Bitte auswählen...</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->firstname}} {{$user->surname}} ({{$user->email}})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group" style="padding-bottom: 20px;">
                <label for="shiftsupervisor">Supervisor *</label>
            <select multiple class="form-control" id="shiftsupervisor" name="shiftsupervisor[]" autofocus>
                <option disabled>Bitte auswählen...</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->firstname}} {{$user->surname}} ({{$user->email}})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10" style="padding-bottom: 20px;">
            <label for="shiftdescription">Beschreibung</label>
            <textarea class="form-control" id="shiftdescription" name="shiftdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier etwas Informatives/Lustiges rein."></textarea>
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