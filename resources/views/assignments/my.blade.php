@extends('layouts.app')

@section('content')

<script src="{{ asset('js/evaluations.js')}}"></script>
{{-- Headline --}}
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Meine Schichten</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <p>Hier werden deine Schichten dargestellt.</p>
    </div>
</div>

{{-- Messages --}}
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

{{-- End Messages --}}
@if($actives<1 && $others<1)
<div class="row">
    <div class="col-md-12">
        <p>Du hast noch keine Schichten.</p>
    </div>
</div>
@endif

@if(count($actives)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($actives)}} Zugesagte Schichten</h4>
        <p class="alert alert-success">Diese Schichten wurden verbindlich zugesagt.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeAssignments')" placeholder="Durchsuchen"/>
    </div>
</div>

<br /> 

<table class="table table-hover table-bordered" id="activeAssignments">
        <thead>
            <tr>
                <th>Id</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Datum</th>
                <th>Uhrzeit</th>
                <th>Dauer</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
        @foreach($actives as $active) 
            <tr>
                <td>{{$active->shift->id}}</td>
                <td>{{$active->shift->job->name}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                <td>{{$active->datum}}</td>
                <td>{{$active->uhrzeit}}</td>
                <td>{{$active->duration}}</td>
                <td>
                    <a href="/assignments/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Details</a>
                </td>
            </tr>
            <script>
                jayCounter("{{$active->expiration}}", "timer{{$active->id}}");
            </script>
        @endforeach
        </tbody>
        
</table>

@endif {{-- Ende aktive --}}

@if(count($others)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($others)}} Zurückgzogene Schichten</h4>
        <p class="alert alert-danger">Diese Schichten werden nicht angetreten.</p>
    </div>
    
</div>

<div class="row">
    <div class="col-md-12">
        <input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeAssignments')" placeholder="Durchsuchen"/>
    </div>
</div>

<br /> 
<table class="table table-hover table-bordered" id="activeAssignments">
        <thead>
            <tr>
                <th>Id</th>
                <th>Status</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Datum</th>
                <th>Uhrzeit</th>
                <th>Dauer</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
        @foreach($others as $other) 
            <tr>
                <td>{{$other->shift->id}}</td>
                <td><b>{{$other->status}}</b></td>
                <td>{{$other->shift->job->name}}</td>
                <td>{{$other->shift->shiftgroup->name}}</td>
                <td>{{$other->datum}}</td>
                <td>{{$other->uhrzeit}}</td>
                <td>{{$other->duration}}</td>
                <td>
                    <a href="/assignments/{{$other->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Details</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        
</table>
@endif



@endsection
