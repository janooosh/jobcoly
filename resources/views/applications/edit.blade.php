@extends('layouts.app')

@section('content')

{{-- Errors --}}

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Schicht bearbeiten</h1>
    </div>
</div>
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

{{-- Form --}}
<form method="post" action="{{ route('shifts.update', $shift->id)}}">
    @method('PATCH')
    @csrf
    <div class="row">
        <!-- Jobauswahl-->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftjob">Job *</label>
            <select class="form-control" id="shiftjob" name="shiftjob" required autofocus>
                <option value="" selected disabled>Bitte auswählen</option>
                @foreach($jobs as $job)
                <option value="{{$job->id}}" {{$shift->job_id == $job->id ? "selected='selected'" : '' }} >{{$job->short}} | {{$job->name}}</option>
                @endforeach
                
            </select>
        </div>
        <!-- Gruppe -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftgroup">Gruppe *</label>
            <select class="form-control" id="shiftgroup" name="shiftgroup" required autofocus>
                <option value="" disabled>Bitte auswählen</option>
                @foreach($shiftgroups as $shiftgroup)
                <option value="{{$shiftgroup->id}}" {{$shift->shiftgroup_id == $shiftgroup->id ? 'selected' : '' }} >{{$shiftgroup->name}}</option>
                @endforeach
            </select>
        </div>
        <!-- Areaauswahl -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftarea">Area</label>
            <select class="form-control" id="shiftarea" name="shiftarea" autofocus>
                <option value="" {{$shift->area == "" ? 'selected' : '' }}>Keine Area</option>
                <option value="bierstube" {{$shift->area == 'bierstube' ? 'selected' : '' }}>Bierstube</option>
                <option value="lounge" {{$shift->area == 'lounge' ? 'selected' : '' }}>Lounge</option>
                <option value="disco" {{$shift->area == 'disco' ? 'selected' : '' }}>Disco</option>
                <option value="saal" {{$shift->area == 'saal' ? 'selected' : '' }}>Saal</option>
                <option value="shot" {{$shift->area == 'shot' ? 'selected' : '' }}>Shotbar</option>
                <option value="haupteingang" {{$shift->area == 'haupteingang' ? 'selected' : '' }}>Haupteingang</option>
                <option value="foyer" {{$shift->area == 'foyer' ? 'selected' : '' }}>Foyer</option>
            </select>
        </div>

    </div>
    <div class="row">
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftanzahl">Anzahl *</label>
            <input class="form-control" id="shiftanzahl" name="shiftanzahl" type="number" value="{{$shift->anzahl}}" required autofocus />
        </div>
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftstart">Start *</label>
            <input class="form-control" id="shiftstart" name="shiftstart" type="datetime-local" value="{{$shift->starts_at}}" required autofocus />
        </div>
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftend">Ende *</label>
            <input class="form-control" id="shiftend" name="shiftend" type="datetime-local" value="{{$shift->ends_at}}" required autofocus />
        </div>
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftstatus">Status</label>
            <select class="form-control" id="shiftstatus" name="shiftstatus">
                <option value="1" {{$shift->active == '1' ? 'selected' : '' }}>Aktiv</option>
                <option value="0" {{$shift->active == '0' ? 'selected' : '' }}>Inaktiv</option>
            </select>
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
@endsection