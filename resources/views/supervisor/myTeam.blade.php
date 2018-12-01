<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-8">
        <a href="{{ route('supervisor')}}" type="button" class="btn btn-default"><i class="fa fa-hand-o-left "></i> Zurück zur Übersicht</a>
        <h1 class="page-header">{{$shift->job->name}} / {{$shift->shiftgroup->name}}</h1>
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
@if(count($actives)>0)
<div class="col-ld-10">

<p>Dein Team als Supervisor: <b>{{$shift->job->name}}</b> ({{$shift->job->short}}){{$shift->area==''?' ':' ('.$shift->area.') '}} am {{$shift->datum}}, Start {{$shift->start}}.</p>
<input type="text" class="form-control" id="search" oninput="searchTable('search','teammember')" placeholder="Team durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="teammember">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">E-Mail </th>
                <th scope="col">Mobil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actives as $active)
            <tr>
                <th scope="row">{{$active->firstname}} {{$active->surname}}</th>
                <td><a href="mailto:{{$active->email}}">{{$active->email}}</a></td>
                <td>{{$active->mobile==''?'-':$active->mobile}}</td>
            @endforeach
        </tbody>
</table>
<br /><br />
<h5>{{count($co_supervisors)}} Co-Supervisor</h5>
@if(count($co_supervisors)<1) Du hast aktuell keine Co-Supervisor und bist alleine für die Bestätigung verantwortlich.<br />
@else Bitte spreche dich mit deine Kollgen ab.<br />
<ul>
    @foreach($co_supervisors as $cs) 
    <li>{{$cs->firstname.' '.$cs->surname}} ({{$cs->email}})</li>
    @endforeach
</ul>
@endif
</div>
@else
<div class="col-lg-12">
<p>Noch keine Mitarbeiter.</p>
</div>
@endif
</div>
@endsection