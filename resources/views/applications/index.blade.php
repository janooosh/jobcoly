@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Meine Bewerbungen</h1>
    </div>
</div>
<div class="row">
        <a href="/applications/new" class="btn btn-default">Neue Bewerbung</a>
</div>

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
@if(count($applications)>0)
<div class="col-ld-10">
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','myapplications')" placeholder="Durchsuchen"/>
<div class=".table-responsive">
<table class="table table-hover " id="myapplications">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Schicht</th>
                <th scope="col">Area</th>
                <th scope="col">Beworben</th>
                <th scope="col">Gruppe</th>
                <th scope="col">Status</th>
                <th scope="col">Ansehen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
            <tr>
                <th scope="row">{{$application->id}}</th>
                <td>{{$application->shift->job->name}}</td>
                <td>{{$application->shift->area==''?'-':$application->shift->area}}</td>
                <td>{{$application->eingang}}</td>
                <td>{{$application->shift->shiftgroup->name}}</td>
                <td>{{$application->status}}</td>
                <td><a href="applications/{{$application->id}}" title="Bewerbung ansehen/zurückziehen" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Mehr Infos</a>
            @endforeach
        </tbody>
</table>
</div>
</div>
@else
<div class="col-lg-12">
<p>Keine Bewerbung gefunden.</p>
</div>
@endif
</div>
@endsection