
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">AWE Auszahlungen</h1>
    </div>
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
    <div class="col-md-12">
        Bitte wähle einen Zeitraum aus. Es gilt stets der Zeitpunkt, an dem der Mitarbeiter die Schichten bestätigt hat, nicht an dem die Schicht stattgefunden hat. <br /><br />
    </div>
</div>
<div class="row">
    <form method="POST" action="{{route('auszahlung.post')}}">
    @csrf
    <div class="col-xs-4">
        <input class="form-control" id="start" name="start" type="date" required autofocus/>
    </div>
    <div class="col-xs-4">
        <input class="form-control" id="ende" name="ende" type="date" required autofocus/>
    </div>
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary"><span class="fa fa-rocket"></span> Los!</button>
    </div>
</div>
@endsection