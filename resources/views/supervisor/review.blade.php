<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-12">
        <a href="{{ route('supervisor')}}" type="button" class="btn btn-default"><i class="fa fa-hand-o-left "></i> Zurück zur Übersicht</a>
        <h1 class="page-header"><i class="fa fa-trophy"></i> Bestätigung {{$shift->job->name}} / {{$shift->shiftgroup->name}}</h1>
    </div>
</div>
{{--
<div class="row">
        <a href="{{route('shifts.create')}}" class="btn btn-default">Neue Schicht</a>
</div>--}}

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
@if($shift->confirmed!=true)
@if(count($actives)>0)

<div class="col-ld-10">
<p>
    Bitte passe die Start - und Endzeiten der einzelnen Leute entsprechend an. Nach Möglichkeit gilt: <b>Bitte nicht runden</b> (weder auf - noch abrunden). <br/>
    Diese Zeiten dienen als Berechnungsgrundlage der Entlohnung.
    Zur Berechnung der Pflichtschichten werden die geplanten Stunden berechnet. Unabhängig von der tatsächlich gearbeiteten Zeit erhält jeder der Mitarbeiter 
    <b>{{$shift->duration}}</b> Pflichtstunden gutgeschrieben. <br /><br />
    Du kannst diese Angaben jederzeit aktualisieren. Wenn du fertig bist, klicke bitte auf "Schicht Abschließen". 
</p> 

</div>
</div>
@if($shift->area)
<div class="row">
    <div class="col-xs-4">
        <b>Area</b>
    </div>
    <div class="col-xs-8">
        {{$shift->area}}
    </div>
</div>
@endif

<div class="row">
    <div class="col-xs-4">
        <b>Datum</b>
    </div>
    <div class="col-xs-8">
        {{$shift->datum}}
    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <b>Start (Geplant)</b>
    </div>
    <div class="col-xs-8">
        {{$shift->start}}
    </div>
</div>
<div class="row" style="padding-bottom:20px;">
    <div class="col-xs-4">
        <b>Ende (Geplant)</b>
    </div>
    <div class="col-xs-8">
        {{$shift->end}}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#confirmshift"><i class="fa fa-check"></i> Schicht Abschließen</button>
    <!-- Modal -->
<div class="modal fade" id="confirmshift" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{Auth::user()->firstname}}, bist du sicher?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Möchtest du die Schicht wirklich abschließen? <br />Nachträgliche Änderungen an sind dann nicht mehr möglich.
            </div>
            <div class="modal-footer">
            <form method="POST" action="{{ route('supervisor.close', $shift->id)}}">
            @csrf
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
              <button  type="submit" class="btn btn-success"><i class="fa fa-check"></i> Schicht Abschließen</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<hr />

<input type="text" class="form-control" id="search" oninput="searchTable('search','teammember')" placeholder="Durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="teammember">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Start (Tatsächlich) </th>
                <th scope="col">Ende (Tatsächlich)</th>
                <th>Dauer</th>
                <th scope="col">Bestätigen?</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            {{-- ACTIVE = ASSIGNMENT, nicht User!!! --}}
            @foreach($actives as $active)
            <form method="POST" action="{{ route('supervisor.save',$active->id)}}">
            @csrf
            <tr>
                <th scope="row">{{$active->user->firstname}} {{$active->user->surname}}</th>
                <td><input class="form-control" id="shiftstart" name="shiftstart" type="time" value="{{$active->start}}" required autofocus/></td>
                <td><input class="form-control" id="shiftend" name="shiftend" type="time" value="{{$active->end}}" required autofocus/></td>
                <td>{{$active->duration}}</td>
                <td>
                    <select class="form-control" id="shiftapproval" name="shiftapproval" autofocus>
                        <option {{$active->confirmed == '' ? 'selected' : ''}} disabled>Auswählen...</option>
                        <option {{$active->confirmed == '1' ? 'selected' : ''}} value='1'>Ja</option>
                        <option {{$active->confirmed == '0' ? 'selected' : ''}} value='0'>Nein</option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn btn-primary"> 
                        <i class="fa fa-save"></i> {{ __('Speichern') }}
                    </button>
                </td>
            </form>
            @endforeach
        </tbody>
</table>
</div>
@else
<div class="col-lg-12">
<p>Noch keine Mitarbeiter.</p>
</div>
@endif

@else
<b>Schicht wurde abgeschlossen.</b>
<input type="text" class="form-control" id="search" oninput="searchTable('search','teammember')" placeholder="Durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="teammember">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Start (Tatsächlich) </th>
                <th scope="col">Ende (Tatsächlich)</th>
                <th>Dauer</th>
                <th scope="col">Bestätigt</th>
            </tr>
        </thead>
        <tbody>
            {{-- ACTIVE = ASSIGNMENT, nicht User!!! --}}
            @foreach($actives as $active)
            <form method="POST" action="{{ route('supervisor.save',$active->id)}}">
            @csrf
            <tr>
                <th scope="row">{{$active->user->firstname}} {{$active->user->surname}}</th>
                <td>{{$active->start}}</td>
                <td>{{$active->end}}</td>
                <td>{{$active->duration}}</td>
                <td>{{$active->confirmed=='1'?'Ja':'Nein'}}</td>
                </td>
            </form>
            @endforeach
        </tbody>
</table>

@endif
</div>
@endsection