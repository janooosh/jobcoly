<?php
use \App\Http\Controllers\ApplicationsController;
use \App\Http\Controllers\ShiftsController;
use Carbon\Carbon;
?>
@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="page-header">
            <h1>{{$jobShort}} - Schicht auswählen</h1>
            <h4>{{$jobTitle}} | {{$groupTitle}}</h4>
        </div>
    </div>
</div>
@if(count($shifts)<1)
    <p>Keine freien Schichten gefunden.</p>
@else
    <p>Bitte wähle eine Schicht aus.</p>

    <div class="row">
        @foreach($shifts as $shift)
        {{-- Zeige Schicht nur, falls noch freie Plätze am Start sind --}}
        @if((ApplicationsController::countBuisyShift($shift->id) < $shift->anzahl) && !(ApplicationsController::alreadyBuisyShift(Auth::user()->id,$shift->id)))
        <a href="/applications/create/{{$shift->id}}">
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-8">
                            <b>{{$shift->date}}</b>
                        </div>
                        <div class="col-xs-4 text-right">
                            {{$shift->area}}
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="progress" style="margin-bottom: 0px">
                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="{{$shift->anzahl - ShiftsController::countFreeShifts($shift->id)}}" aria-valuemin="0" aria-valuemax="{{$shift->anzahl}}" style="width:{{($shift->anzahl-ShiftsController::countFreeShifts($shift->id))/$shift->anzahl*100}}%">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5 text-right">
                            <div>
                                {{ApplicationsController::countBuisyShift($shift->id).' / '. $shift->anzahl}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row" style="font-size:80%!important; color:grey!important;">
                        <div class="col-xs-6">
                            Dauer: {{$shift->duration}}
                        </div>
                        <div class="col-xs-6 text-right">
                            {{$shift->start.' - '.$shift->ende}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </a>
        @endif
        @endforeach
    </div>
    Es werden die noch freien Plätze angegeben (Frei / Verfügbar).
@endif

@endsection