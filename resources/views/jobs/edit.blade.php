@extends('layouts.app')

@section('content')

{{-- Errors --}}

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Job bearbeiten</h1>
        <div class="alert alert-warning">
            Wenn du den Namen und/oder die Abkürzung änderst, ändern sich die Bezeichnungen auch bei jeder bereits zugewiesener Schicht.
        </div>
        <div class="alert alert-info">
            Änderungen an den Gutscheinen bzw. der AWE ändern NUR die Vorschläge bei neuen Schichten, nicht aber bestehende Schichten.
        </div>
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
<p>Bitte beachte, dass Jobnamen und auch Abkürzungen nur einmal vorkommen dürfen.<br />
Abkürzungen müssen 2 Zeichen haben, es werden keine Zahlen akzeptiert.</p>
@endif

{{-- Form --}}
<form method="post" action="{{ route('jobs.update', $job->id)}}">
 @method('PATCH')
@csrf
    <div class="row" >
    <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="jobname">Name *</label>
            
    <input class="form-control" type="text" id="jobname" name="jobname" placeholder="Thekenkraft" value="{{$job->name}}" required autofocus/>
        </div>
        <div class="col-md-3" style="padding-bottom: 20px;">
            <label for="jobshort">Abkürzung *</label>
        <input class="form-control" type="text" id="jobshort" name="jobshort" placeholder="TK" value="{{$job->short}}" required autofocus/>
        </div>
        <div class="col-md-3" style="padding-bottom: 20px;">
            <label for="jobgesundheitszeugnis">Gesundheitszeugnis benötigt?</label>
            <select class="form-control" id="jobgesundheitszeugnis" name="jobgesundheitszeugnis" autofocus>
                    <option value="" {{$job->gesundheitszeugnis == "" ? 'selected' : '' }} disabled>Bitte auswählen...</option>
                    <option value="1" {{$job->gesundheitszeugnis == "1" ? 'selected' : '' }}>Ja</option>
                    <option value="0" {{$job->gesundheitszeugnis == "0" ? 'selected' : '' }}>Nein</option>
            </select>
        </div>
    </div>
    <div class="row">
            <div class="col-md-5 form-group" style="padding-bottom: 20px">
                <label for="jobgutscheine">Gutscheine (/h) *</label>
                <input class="form-control" type="number" id="jobgutscheine" name="jobgutscheine" value="{{$job->gutscheine}}" required autofocus/>
            </div>
            <div class="col-md-5 form-group" style="padding-bottom: 20px">
                <label for="jobawe">AWE (/h) *</label>
                <input class="form-control" type="number" id="jobawe" name="jobawe" value="{{$job->awe}}" required autofocus/>
            </div>
    </div>
    <div class="row p-10">
        <div class="col-lg-10" style="padding-bottom: 20px;">
            <label for="jobdescription">Beschreibung</label>
            <textarea class="form-control" id="jobdescription" name="jobdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier doch was Lustiges rein :)">{{$job->description}}</textarea>
            <p class="help-block">Maximal 255 Zeichen.</p>
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