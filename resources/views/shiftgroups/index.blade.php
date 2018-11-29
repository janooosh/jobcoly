@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Schichtgruppen</h1>
    </div>
</div>
<div class="row">
        <a href="{{route('shiftgroups.create')}}" class="btn btn-default">Neue Schichtgruppe</a>
</div>

{{-- Message --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

<div class="row">
@if(count($shiftgroups)>0)
<div class="col-ld-10">

<p>Es sind {{count($shiftgroups)}} aktive Schichtgruppen registriert.</p>
<table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Untertitel</th>
                <th scope="col">Beschreibung</th>
                <th scope="col">Schichten</th>
                <th scope="col">Arbeiter</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($shiftgroups as $shiftgroup)
            <tr>
                <th scope="row">{{$shiftgroup->id}}</th>
                <td>{{$shiftgroup->name}}</td>
                <td>{{$shiftgroup->subtitle}}</td>
                <td>{{$shiftgroup->description}}</td>
                <td>{{count($shiftgroup->shifts)}}</td>
                <td>{{$shiftgroup->actives}}</td>
                
                <td><a href="{{ route('shiftgroups.edit',$shiftgroup->id)}}" class="fa fa-pencil" title="Schichtgruppe bearbeiten"></a></td>
            @endforeach
        </tbody>
</table>
</div>
@else
<div class="col-lg-12">
<p>Keine Schichtgruppen gefunden.</p>
</div>
@endif
</div>
@endsection