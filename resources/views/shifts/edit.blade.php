<?php
use \App\Http\Controllers\PrivilegeController;
?>

@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
{{-- Header --}}
<div class="row" style="margin-bottom: 10px;">
        <div class="col-lg-12">
            <h1 class="page-header">Schicht bearbeiten</h1>
        </div>
    </div>

{{-- Warning --}}
@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        {{$message}}
    </div>
</div>
@endif

{{-- Error --}}
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

{{-- Success --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <b>ACHTUNG:</b> Deine Änderungen haben Auswirkungen auf ALLE bestehenden Schichtzuweisungen und Bewerbungen dieser Schicht.<br />
          </div>
    </div>
</div>

@if(count($jobs)>0 and count($shiftgroups)>0)
{{-- Form --}}
<form method="POST" action="{{route('shifts.update',$shift->id)}}">
@method('PATCH')
@csrf
<!-- Jobauswahl-->
    <div class="row" >  
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftjob">Job *</label>        
            <select class="form-control" id="shiftjob" name="shiftjob" disabled required autofocus>
                <option value="" disabled>Bitte auswählen</option>
                @foreach($jobs as $job)
                    <option value="{{$job->id}}" {{$shift->job->id == $job->id ? 'selected' : ''}} >{{$job->short}} | {{$job->name}}</option>
                    @endforeach
            </select>
        </div>
        <!-- Gruppe -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
                <label for="shiftgroup">Gruppe *</label>        
                <select class="form-control" id="shiftgroup" name="shiftgroup" disabled required autofocus>
                    <option value="" disabled>Bitte auswählen</option>
                    @foreach($shiftgroups as $shiftgroup)
                        <option value="{{$shiftgroup->id}}" {{$shift->shiftgroup->id == $shiftgroup->id ? 'selected' : ''}}>{{$shiftgroup->name}}</option>
                    @endforeach
                </select>
        </div>
        <!-- Areaauswahl -->
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <label for="shiftarea">Area</label>
            <select class="form-control" id="shiftarea" name="shiftarea" disabled autofocus>
                <option value="" {{$shift->area == '' ? 'selected' : ''}}>Keine Area</option>
                <option value="Bierstube" {{$shift->area == 'Bierstube' ? 'selected' : ''}}>Bierstube</option>
                <option value="Lounge" {{$shift->area == 'Lounge' ? 'selected' : ''}}>Lounge</option>
                <option value="Disco" {{$shift->area == 'Disco' ? 'selected' : ''}}>Disco</option>
                <option value="Mensa" {{$shift->area == 'Mensa' ? 'selected' : ''}}>Mensa</option>
                <option value="Taverne" {{$shift->area == 'Taverne' ? 'selected' : ''}}>Shotbar</option>
                <option value="Haupteingang" {{$shift->area == 'Haupteingang' ? 'selected' : ''}}>Haupteingang</option>
                <option value="Foyer" {{$shift->area == 'Foyer' ? 'selected' : ''}}>Foyer</option>
            </select>
        </div>

    </div>
    <div class="row">

    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstart">Start *</label>
    <input class="form-control" id="shiftstart" name="shiftstart" type="date" value="{{$shift->shiftstart}}" {{Auth::user()->is_admin==1?'':'disabled'}} required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftstarttime">Zeit *</label>
            <input class="form-control" id="shiftstarttime" name="shiftstarttime" type="time" value="{{$shift->shiftstarttime}}" {{Auth::user()->is_admin==1?'':'disabled'}} required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftend">Ende *</label>
            <input class="form-control" id="shiftend" name="shiftend" type="date" value="{{$shift->shiftend}}" {{Auth::user()->is_admin==1?'':'disabled'}} required autofocus/>
    </div>
    <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftendtime">Zeit *</label>
            <input class="form-control" id="shiftendtime" name="shiftendtime" type="time" value="{{$shift->shiftendtime}}" {{Auth::user()->is_admin==1?'':'disabled'}} required autofocus/>
    </div>

    </div>

    <div class="row">
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftanzahl">Anzahl *</label>
        <input class="form-control" id="shiftanzahl" name="shiftanzahl" type="number" value="{{$shift->anzahl}}" {{Auth::user()->is_admin==1?'':'disabled'}} required autofocus/>
        </div>
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftgutscheine">Gutscheine (/h)</label>
            <input class="form-control" id="shiftgutscheine" name="shiftgutscheine" type="number" value="{{$shift->gutscheine}}" {{Auth::user()->is_admin==1?'':'disabled'}} autofocus/>
        </div>
        <div class="col-md-3 form-group" style="padding-bottom: 20px;">
            <label for="shiftawe">AWE (/h)</label>
            <input class="form-control" id="shiftawe" name="shiftawe" type="number" value="{{$shift->awe}}" {{Auth::user()->is_admin==1?'':'disabled'}} autofocus/>
        </div>
        <div class="col-md-2 form-group" style="padding-bottom: 20px;">
            <label for="shiftstatus">Status</label>
            <select class="form-control" id="shiftstatus" name="shiftstatus" {{Auth::user()->is_admin==1?'':'disabled'}}>
                <option value="Aktiv" {{$shift->active == 'Aktiv' ? 'selected' : ''}}>Aktiv</option>
                <option value="Inaktiv" {{$shift->status == 'Inaktiv' ? 'selected' : ''}}>Inaktiv</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <h4>
                Managers 
                @if(Auth::user()->is_admin==1)
                <button type="button" class="btn btn-default btn-circle" data-toggle="modal" data-target="#managers">
                    <i class="fa fa-pencil"></i>
                </button>
                @endif
            </h4>

            <ul>
                @foreach($shift->managers as $manager)
                    <li>{{$manager->user->firstname}} {{$manager->user->surname}} ({{$manager->user->email}})</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4 form-group" style="padding-bottom: 20px;">
            <h4>Supervisors
                @if(Auth::user()->is_admin==1)
                <button type="button" class="btn btn-default btn-circle" data-toggle="modal" data-target="#supervisors">
                    <i class="fa fa-pencil"></i>
                </button>
                @endif
            </h4>
            <ul>
                @foreach($shift->supervisors as $supervisor)
                    <li>{{$supervisor->user->firstname}} {{$supervisor->user->surname}} ({{$supervisor->user->email}})</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 form-group" style="padding-bottom: 20px;">
            <label for="shiftdescription">Beschreibung</label>
                <textarea class="form-control" id="shiftdescription" name="shiftdescription" rows="3" placeholder="Diese Beschreibung wird Bewerbern angezeigt. Optional. Schreibe hier etwas Informatives/Lustiges rein.">{{$shift->description}}</textarea>
            <p class="help-block">Maximal 500 Zeichen.</p>
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

@if(Auth::user()->is_admin==1)
{{-- MODAL MANAGER --}}
<div class="modal fade" id="managers" tabindex="-1" role="dialog" aria-labelledby="managers" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form method="POST" action="{{route('privilege.update')}}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Manager verwalten</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="shiftid" name="shiftid" value="{{$shift->id}}"/>
                <input type="hidden" id="role" name="role" value="Manager"/>
                <div class="row">
                    <div class="col-xs-1">
                        <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i></button>
                    </div>
                    <div class="col-xs-11">
                        <input type="text" class="form-control" id="searchmanager" oninput="searchTable('searchmanager','managerstable')" placeholder="Durchsuchen...."/>
                    </div>
                </div>
                <br />
                <table class="table table-condensed" id="managerstable">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">E-Mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user) 
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="userselect[]" value="{{$user->id}}" {{PrivilegeController::isRealManager($user->id,$shift->id)?'checked':''}}>
                                    </div>
                                </td>
                                <td>{{$user->firstname.' '.$user->surname}}</td>
                                <td>{{$user->email}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
            <div class="modal-footer">
            </div>
        </div>
        </form>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif


@if(Auth::user()->is_admin==1)
{{-- MODAL SUPERVISOR --}}
<div class="modal fade" id="supervisors" tabindex="-1" role="dialog" aria-labelledby="supervisors" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('privilege.update')}}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Supervisor verwalten</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="shiftid" name="shiftid" value="{{$shift->id}}"/>
                <input type="hidden" id="role" name="role" value="Supervisor"/>
                <div class="row">
                    <div class="col-xs-1">
                        <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i></button>
                    </div>
                    <div class="col-xs-11">
                        <input type="text" class="form-control" id="searchmanager" oninput="searchTable('searchmanager','managerstable')" placeholder="Durchsuchen...."/>
                    </div>
                </div>
                <br />
                <table class="table table-condensed" id="managerstable">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">E-Mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user) 
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="userselect[]" value="{{$user->id}}" {{PrivilegeController::isRealSupervisor($user->id,$shift->id)?'checked':''}}>
                                    </div>
                                </td>
                                <td>{{$user->firstname.' '.$user->surname}}</td>
                                <td>{{$user->email}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
            <div class="modal-footer">
            </div>
        </div>
        </form>
            <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif

@else
<p>Du hast noch keine Jobs erstellt. Ohne Jobs kannst du keine Schichten erstellen.</p>
<a href="{{route('jobs.create')}}" class="btn btn-default" >Job erstellen</a>
@endif
@endsection