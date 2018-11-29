@extends('layouts.app')

@section('content')

{{-- Errors --}}

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Neuer Job anlegen</h1>
        <p>
        Bitte beachte: Jobs sind keine Schichten! Ein Job (z.B. "Thekenkraft") ist notwendig, um TK-Schichten zu erstellen.

        </p>
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
Abkürzungen müssen 2 Zeichen haben, Ziffern werden nicht akzeptiert.</p>
@endif


{{-- Form --}}
<form method="POST" action="{{ route('jobs.store')}}">
@csrf
    <div class="row" >
    <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="jobname">Name *</label>
            
            <input class="form-control" type="text" id="jobname" name="jobname" placeholder="Thekenkraft" required autofocus/>
        </div>
        <div class="col-md-3" style="padding-bottom: 20px;">
            <label for="jobshort">Abkürzung *</label>
            <input class="form-control" type="text" id="jobshort" name="jobshort" placeholder="TK" required autofocus/>
        </div>
        <div class="col-md-3" style="padding-bottom: 20px;">
            <label for="jobgesundheitszeugnis">Gesundheitszeugnis benötigt?</label>
            <select class="form-control" id="jobgesundheitszeugnis" name="jobgesundheitszeugnis" autofocus>
                <option value="" selected disabled>Bitte auswählen...</option>
                <option value="1">Ja</option>
                <option value="0">Nein</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 form-group" style="padding-bottom: 20px">
            <label for="jobgutscheine">Gutscheine (/h) *</label>
            <input class="form-control" type="number" id="jobgutscheine" name="jobgutscheine" value=3 required autofocus/>
        </div>
        <div class="col-md-5 form-group" style="padding-bottom: 20px">
            <label for="jobawe">AWE (/h) *</label>
            <input class="form-control" type="number" id="jobawe" name="jobawe" value=0 required autofocus/>
        </div>
    </div>
    <div class="row p-10">
        <div class="col-lg-10" style="padding-bottom: 20px;">
            <label for="jobdescription">Beschreibung</label>
            <textarea class="form-control" id="jobdescription" name="jobdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier doch was Lustiges rein :)"></textarea>
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