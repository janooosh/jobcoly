@extends('layouts.app')

@section('content')

{{-- Errors --}}

{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Schichtgruppe bearbeiten</h1>
    </div>
</div>
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

{{-- Form --}}
<form method="post" action="{{ route('shiftgroups.update', $shiftgroup->id)}}">
 @method('PATCH')
@csrf
    <div class="row" >
    <div class="col-md-6 form-group" style="padding-bottom: 20px;">
            <label for="shiftgroupname">Name *</label>
             <input class="form-control" type="text" id="shiftgroupname" name="shiftgroupname" placeholder="Weiberfasching" value="{{$shiftgroup->name}}" required autofocus/>
        </div>
        <div class="col-md-6 form-group" style="padding-bottom: 20px;">
            <label for="shiftgroupsubtitle">Untertitel</label>
        <input class="form-control" type="text" id="shiftgroupsubtitle" name="shiftgroupsubtitle" placeholder="Donnerstag, 20.02.2020" value="{{$shiftgroup->subtitle}}" autofocus/>
        </div>
    </div>
    <div class="row p-10">
        <div class="col-lg-10" style="padding-bottom: 20px;">
            <label for="shiftgroupdescription">Beschreibung</label>
            <textarea class="form-control" id="shiftgroupdescription" name="shiftgroupdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier doch was Lustiges rein :)">{{$shiftgroup->description}}</textarea>
            <p class="help-block">Maximal 255 Zeichen.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Speichern') }}
            </button>
        </div>
    </div>
</form>
@endsection