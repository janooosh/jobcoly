@extends('layouts.app')
@section('content')

{{-- Überschrift --}}
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><span class="fa fa-calendar"></span> Schichtplan</h1>
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
    <form method="POST" action="{{route('shiftplan.show')}}">
    @csrf
    <div class="col-xs-4">
        <p>Bitte wähle eine Gruppe aus:</p>
    </div>
    <div class="col-xs-4">

            <select class="form-control" name="shiftgroup" id="shiftgroup" required>
                <option disabled selected>Bitte auswählen...</option>
                @foreach($shiftgroups as $shiftgroup)
                    <option value="{{$shiftgroup->id}}">{{$shiftgroup->name}}</option>
                @endforeach
            </select>
           
    </div>
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary"><span class="fa fa-rocket"></span> Los!</button>
    </div>
    </form>
</div>

@endsection