<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Supervisor Schichten</h1>
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
@if(count($shifts)>0)
<div class="col-ld-10">

<p>Dir wurde(n) {{count($shifts)}} aktive Schicht(en) als Supervisor zugewiesen. <br />
Falls Schichten fehlen, melde dich bitte bei crew@olylust.de.</p>
<input type="text" class="form-control" id="searchShifts" oninput="searchTable('searchShifts','shiftTable')" placeholder="Schichten durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="shiftTable">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Gruppe</th>
                <th scope="col">Job</th>
                <th scope="col">Area</th>
                <th scope="col">Datum</th>
                <th scope="col">Start</th>
                <th scope="col">Dauer</th>
                <th scope="col">Mitarbeiter</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
            <tr>
                <th scope="row">{{$shift->id}}</th>
                <td>{{$shift->shiftgroup->name}}</td>
                <td>{{$shift->job->short}}</td>
                <td>{{$shift->area==''?'-':$shift->area}}</td>
                <td>{{$shift->datum}}</td>
                <td>{{$shift->start}}</td>
                <td>{{$shift->duration}} h</td>
                <td>{{$shift->actives}}</td>
                <td><a href="{{ route('supervisor.team',$shift->id)}}" type="button" class="btn btn-default" ><i class="fa fa-eye"></i> Mein Team</a></td>
                </td>
                <td><a href="{{ route('supervisor.review',$shift->id)}}" type="button" class="btn btn-default"><i class="fa fa-trophy"></i> Bestätigung</a></td>
                </td>
            @endforeach
        </tbody>
</table>
</div>
@else
<div class="col-lg-12">
<p>Keine Schichten gefunden.</p>
</div>
@endif
</div>
@endsection