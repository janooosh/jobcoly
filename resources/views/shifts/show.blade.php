<?php

use \App\Http\Controllers\EvaluationsController;
use \App\Http\Controllers\ShiftsController;
use \App\Http\Controllers\PrivilegeController;
use \App\Http\Controllers\AssignmentsController;
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>

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

<div class="row">
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-4">
                <a href="/shifts" title="Zurück" type="button" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Alle Schichten</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Schicht ansehen
                </h1>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-12">
                <h5>Informationen zu dieser Schicht</h5>
                <p>{{$shift->description == '' ? '-':$shift->description}}</p>
            </div>
            <div class="col-md-12">
                <h5>Informationen zum {{$shift->shiftgroup->name}}</h5>
                <p>{{$shift->shiftgroup->description == '' ? '-':$shift->shiftgroup->description}}</p>
            </div>
            <div class="col-md-12">
                <h5>Allgemeine Informationen als {{$shift->job->name}} ({{$shift->job->short}})</h5>
                <p>{{$shift->job->description == '' ? '-':$shift->job->description}}</p>
            </div>
        </div>
        <hr />

        <div class="row">
            <div class="col-md-12">
                <div class="panel-group" id="accordion">
                    {{-- Schichtpannel --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseShifts" aria-expanded="false" class="collapsed">
                                    Zugewiesene Arbeiter ({{count($shift->activeAssignments)}})
                                </a>
                            </h4>
                        </div>
                        <div id="collapseShifts" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                @if(count($shift->activeAssignments)<1) Keine zugewiesenen Arbeiter. @else <ul class="listgroup">
                                    @foreach($shift->activeAssignments as $assignment)
                                    <li class="list-group-item">{{$assignment->user->firstname}} {{$assignment->user->surname}} (<a href='mailto:{{$assignment->user->email}}' title='E-Mail schreiben'>{{$assignment->user->email}}</a>)
                                        @if(Auth::user()->is_admin==1 || PrivilegeController::isManager(Auth::user()->id, $shift->id))
                                        <button data-toggle="modal" data-target="#krankmelden{{$assignment->id}}" type="button" class="btn btn-danger btn-xs"><i class="fa fa-ambulance"></i> Krank Melden</button>
                                        <button data-toggle="modal" data-target="#absagen{{$assignment->id}}" type="button" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Absagen</button>

                                        @endif
                                    </li>
                                    {{-- KRANKMELDUNG --}}
                                    <div class="modal fade" id="krankmelden{{$assignment->id}}" tabindex="-1" role="dialog" aria-labelledby="managers" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    <h4 class="modal-title" id="myModalLabel">{{$assignment->user->firstname}} wirklich krankmelden?</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Möchtest du {{$assignment->user->firstname}} als {{$assignment->shift->job->name}} ({{$assignment->shift->shiftgroup->name}}) wirklich krankmelden? <br />
                                                        {{$assignment->user->firstname}} wird per E-Mail über die Krankmeldung informiert. Die Schicht ist danach wieder freigegeben. Bitte halte mit {{$assignment->user->firstname}} Rücksprache bzgl. Ersatzschichten usw. <br />
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
                                                    <a href="{{route('assignments.krankmeldung', $assignment->id)}}" type="button" class="btn btn-danger btn-xs"><i class="fa fa-ambulance"></i> Krankmelden</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    {{-- ABSAGE --}}
                                    <div class="modal fade" id="absagen{{$assignment->id}}" tabindex="-1" role="dialog" aria-labelledby="managers" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post" action="{{ route('assignments.absagen') }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title" id="myModalLabel">{{$assignment->user->firstname}} wirklich absagen?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            Möchtest du {{$assignment->user->firstname}} als {{$assignment->shift->job->name}} ({{$assignment->shift->shiftgroup->name}}) wirklich krankmelden? <br />
                                                            {{$assignment->user->firstname}} wird per E-Mail über die Absage informiert. Die Schicht ist danach wieder freigegeben. Bitte halte mit {{$assignment->user->firstname}} Rücksprache bzgl. Ersatzschichten usw. <br />
                                                        </p>
                                                        <input required type="text" id="absagengrund" class="form-control" name="absagengrund" placeholder="Grund für die Absage">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="assignment_id" value="{{$assignment->id}}" />
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Absagen</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    @endforeach
                                    </ul>
                                    @endif
                            </div>
                        </div>
                    </div>



                    {{-- Aktive Bewerbungen --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseApplications" aria-expanded="false" class="collapsed">
                                    Aktive Bewerbungen ({{count($shift->activeApplications)}})
                                </a>
                            </h4>
                        </div>
                        <div id="collapseApplications" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                @if(count($shift->activeApplications)<1) Keine weiteren Bewerbungen. @else <ul class="listgroup">
                                    @foreach($shift->activeApplications as $application)
                                    <li class="list-group-item">{{$application->applicant->firstname}} {{$application->applicant->surname}} (<a href='{{$application->applicant->email}}' title='E-Mail schreiben'>{{$application->applicant->email}}</a>)&nbsp;&nbsp;
                                        @if(PrivilegeController::isManager(Auth::user()->id,$shift->id))
                                        <a href="{{route('evaluation.show', $application->id)}}" type="button" class="btn btn-default btn-xs"><i class="fa fa-suitcase"></i> Zur Bewerbung</a>
                                        @endif
                                    </li>
                                    @endforeach
                                    </ul>
                                    @endif
                            </div>
                        </div>
                    </div>

                    {{-- Gelöschte Zuweisungen --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseKrankmeldungen" aria-expanded="false" class="collapsed">
                                    Gelöschte Zuweisungen ({{count($shift->deleteds)}})
                                </a>
                            </h4>
                        </div>
                        <div id="collapseKrankmeldungen" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                @if(count($shift->deleteds)<1) Keine gelöschten Zuweisungen @else <ul class="listgroup">
                                    @foreach($shift->deleteds as $deleted)
                                    <li class="list-group-item">{{$deleted->user->firstname}} {{$deleted->user->surname}} (<a href='{{$deleted->user->email}}' title='E-Mail schreiben'>{{$deleted->user->email}}</a>)&nbsp;&nbsp;
                                        <b>{{$deleted->status}}</b>
                                    </li>
                                    @endforeach
                                    </ul>
                                    @endif
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>

    <div class="col-md-5" id="jobinfo">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{$shift->job->short}} {{$shift->area == ''?'':'('.$shift->area.')'}}</h3>
                        {{$shift->job->name}}
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <span><b>{{$shift->shiftgroup->name}}</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="progress" style="margin-bottom: 0px;">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="{{EvaluationsController::countAssignments($shift->id)}}" aria-valuemin="0" aria-valuemax="{{$shift->anzahl}}" style="width:{{EvaluationsController::countAssignments($shift->id) / $shift->anzahl *100}}%">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        {{EvaluationsController::countAssignments($shift->id)}} von {{$shift->anzahl}} vergeben
                    </div>
                </div>

                <br />
                <div class="row">
                    <div class="col-xs-6">
                        <b>Schicht-ID</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->id}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <b>Start</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->starts_at}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <b>Ende</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->ends_at}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <b>Dauer</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->duration}}
                    </div>
                </div>
                <hr />
                @if($shift->gutscheine >0)
                <div class="row">
                    <div class="col-xs-6">
                        <b>Gutscheine</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->gutscheine}} / Stunde
                    </div>
                </div>
                @endif

                @if($shift->awe >0)
                <div class="row">
                    <div class="col-xs-6">
                        <b>AWE</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->awe}} € / Stunde
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <b>AWE nach</b>
                    </div>
                    <div class="col-xs-6">
                        {{$shift->p}} Stunden
                    </div>
                </div>
                @endif
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <span>{{count($shift->comanager)}} <b>Co-Manager</b></span>
                        @if(count($shift->comanager)>0)
                        <ul>
                            @foreach($shift->comanager as $comanager)
                            <li>{{$comanager->firstname}} {{$comanager->surname}} ({{$comanager->email}})</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span>{{count($shift->supervisor)}} <b>Supervisor</b></span>
                        @if(count($shift->supervisor)>0)
                        <ul>
                            @foreach($shift->supervisor as $supervisor)
                            <li>{{$supervisor->firstname}} {{$supervisor->surname}} ({{$supervisor->email}})</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div> {{-- Ende Pannel --}}
        <br />
        <div class="row">
            <div class="col-md-6">
                <a href="/shifts/{{$shift->id}}/edit" target="_blank" title="Schicht Bearbeiten"><button type="button" class="btn btn-outline btn-primary"><span class="fa fa-pencil"></span> Schicht Bearbeiten</button></a>
            </div>
            <div class="col-md-6">
                <button type="button" data-toggle="modal" data-target="#assign" title="Mitarbeiter zur Schicht hinzufügen" class="btn btn-outline btn-primary"><span class="fa fa-user"></span> Mitarbeiter Hinzufügen</button>
            </div>
        </div>
        <br />


    </div> {{-- Ende Rechte Seite --}}
</div>

{{-- MODAL SCHICHTZUWEISUNG --}}
<div class="modal fade" id="assign" tabindex="-1" role="dialog" aria-labelledby="assign" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form method="POST" action="{{route('assignment.neuer_mitarbeiter')}}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Schichtzuweisung</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                            <span class="fa fa-info"></span> Es werden nur Mitarbeiter angezeigt, die dieser Schicht noch nicht zugewiesen sind bzw. keine offene Bewerbung zu dieser Schicht haben.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-save"></i></button>
                        </div>
                        <div class="col-xs-11">
                            <input type="text" class="form-control" id="searchassignment" oninput="searchTable('searchassignment','assignmenttable')" placeholder="Durchsuchen...." />
                            <input type="hidden" name="shiftToAssign" value="{{$shift->id}}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-condensed" id="assignmenttable">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">E-Mail</th>
                                        <th scope="col">Mobile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shift->candidates as $user)
                                    @if(!AssignmentsController::isAssigned($user->id,$shift->id))
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="userselect[]" value="{{$user->id}}" {{AssignmentsController::isAssigned($user->id,$shift->id)?'checked':''}}>
                                            </div>
                                        </td>
                                        <td>{{$user->firstname}} {{$user->surname}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->mobile?$user->mobile:'-'}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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

@endsection