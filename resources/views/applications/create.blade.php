<?php
use Carbon\Carbon;
?>

@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="page-header">
            <h1>{{$shift->job->name}}</h1>
            <h4>{{$shift->area}}</h4>
        </div>
    </div>
</div>

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

    <p>Hiermit bewirbst du dich für folgende Schicht: 
    <span style="color:darkgreen;">
        <b>{{$shift->job->name}}</b> ({{$shift->job->short}}) 
        am <b>{{Carbon::parse($shift->starts_at)->format('D, d.m.y')}}</b>
        von <b>{{Carbon::parse($shift->starts_at)->format('H:i')}}</b> bis <b>{{Carbon::parse($shift->ends_at)->format('H:i')}}</b>.</span></p>

    @if (Auth::user()->is_praside==1 || Auth::user()->ausschuss!="")
        <p>Der Schicht entsprechen {{Carbon::parse($shift->starts_at)->diff(Carbon::parse($shift->ends_at))->format('%H:%I')}} Pflichtstunden.
           Du erhälst folgende Entlohnung:</p>
    @else
        <p>Du sammelst keine Pflichtstunden und erhälst folgende Entlohnung für deine Schicht:</p>
    @endif
     <ul>       
    @if($shift->awe>0)
    <li>{{$shift->awe}},00 € Aufwandsentschädigung pro Stunde (optional nach {{$shift->p}} Stunden)</li>
    @endif

    @if($shift->gutscheine>0)
    <li>{{$shift->gutscheine}} Gutscheine pro Stunde</li>
    @endif

     </ul>
     <br />
     <a type="button" href="{{asset('doc/Crew.pdf')}}" target="_blank" class="btn btn-outline btn-primary "><span class="fa fa-file-pdf-o "></span> Entlohnung</a>
     <br />
    @if($shift->shiftgroup->description!="")
    <h4>{{$shift->shiftgroup->name}}<small> {{$shift->shiftgroup->subtitle}}</small></h4>
    <p>{{$shift->shiftgroup->description}}</p>
    @endif

    @if($shift->job->description!="")
    <h4>{{$shift->job->name}}</h4>
    <p>{{$shift->job->description}}</p>
    @endif

    @if($shift->description!="")
    <h4>Informationen zur Schicht</h4>
    <p>{{$shift->description}}</p>
    @endif

    <div class="alert alert-info">
    Deine Bewerbung wird von unserem Team in der Regel innerhalb von wenigen Tagen bearbeitet. Du kannst den Status hier einsehen und erhälst natürlich E-Mail Benachrichtigungen bei allen Updates :)
    Bitte beachte, dass deine Zusage zur Schicht bindend ist, eine Absage ist nicht möglich. Auch wenn es bei der ersten Bewerbung nicht klappt, kannst du dich später jederzeit erneut für die Schicht bewerben.
    </div>

    <form method="POST" action="{{route('applications.store')}}">
    @csrf
    <div class="row">
        <div class="col-md-10 form-group" style="padding-bottom: 20px;">
            <label for="shiftexperience">Hast du bereits Erfahrungen als {{$shift->job->name}}?</label>
                <textarea class="form-control" id="shiftexperience" name="shiftexperience" rows="3" placeholder="Optional. In der Kürze liegt die Würze."></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 form-group" style="padding-bottom: 20px;">
            <label for="shiftmotivation">Motivation</label>
                <textarea class="form-control" id="shiftmotivation" name="shiftmotivation" rows="3" placeholder="Optional. Warum brauchen wir DICH für diese Position?"></textarea>
        </div>
    </div>
    <div class="row">
            <div class="col-md-10 form-group" style="padding-bottom: 20px;">
            <label for="shiftcomments">Kommentare</label>
                <textarea class="form-control" id="shiftcomments" name="shiftcomments" rows="3" placeholder="Optional. Bewirbst du dich zeitgleich mit einer Freundin/einem Freund? Hast du noch etwas auf dem Herzen?"></textarea>
        </div>
    </div>
    <div class="row">
        <label for="applicationaccept">
            <input type="checkbox" id="applicationaccept" name="applicationaccept" value="1" required autofocus/>
            Hiermit bestätige ich, dass ich die Hinweise gelesen und verstanden habe.
        </label>
    </div>
    <input type="hidden" id="shiftid" name="shiftid" value="{{$shift->id}}"/>
    <div class="row">
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Speichern') }}
            </button>
        </div>
    </div>
    </form>
    

@endsection