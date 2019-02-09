<?php
use \App\Http\Controllers\ApplicationsController;
use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Neue Bewerbung</h1>
    </div>
</div>


<?php
$zaehler = 0;
?>
@foreach($shiftgroups as $shiftgroup) 
@if(count($shiftgroup->shifts)>0 && ApplicationsController::FreeShiftsInGroup($shiftgroup->id) > 0)
<div class="row">
    <div class="col-md-12">
<h3>{{$shiftgroup->name}} <small>{{$shiftgroup->subtitle}}</small></h3>
    </div>
</div>
<?php
$c = 0;
?>
<div class="row">
    @foreach($jobs as $job)
    {{-- Only show this job if applicant is still free AND more than 0 but less than max possible places are still left --}}
    {{-- Check if group, then create link --}}

    @if(count($job->shifts->where('shiftgroup_id',$shiftgroup->id))>0 && ApplicationsController::countBuisyShifts($shiftgroup->id,$job->id) < ApplicationsController::countAvailableJobs($shiftgroup->id, $job->id) && !ApplicationsController::alreadyBuisyGroup(Auth::user()->id,$shiftgroup->id,$job->id))
    <?php
        $c++;
        $zaehler++;
    ?>
    <a href="@if(count($job->shifts->where('shiftgroup_id',$shiftgroup->id))>1)new/{{$shiftgroup->id}}/{{$job->id}}
            @else create/{{$job->shifts->where('shiftgroup_id',$shiftgroup->id)->first()->id}}@endif" style="text-decoration:none!important;">  
    
            <div class="col-md-3">
            
            <div class="panel panel-info">
                <div class="panel-heading">
                        <b>{{$job->name}}</b> ({{$job->short}})
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="progress" style="margin-bottom: 0px;">
                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
                                    aria-valuenow="{{ApplicationsController::countFreeShifts($shiftgroup->id,$job->id)}}" aria-valuemin="0" aria-valuemax="{{ApplicationsController::countAvailableJobs($shiftgroup->id, $job->id)}}" style="width:{{ApplicationsController::countBuisyShifts($shiftgroup->id,$job->id) / ApplicationsController::countAvailableJobs($shiftgroup->id, $job->id) * 100}}%">
                                </div>
                            </div>
                        </div>
                    <div class="col-xs-4 text-right">
                        <div>{{ApplicationsController::countBuisyShifts($shiftgroup->id,$job->id)}} / {{ApplicationsController::countAvailableJobs($shiftgroup->id, $job->id)}}</div>
                    </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row" style="font-size:80%!important; color:grey!important;">
                        <div class="col-xs-6">
                            @if(count($job->shifts->where('shiftgroup_id',$shiftgroup->id))>1)
                                Mehrere
                            @else
                                {{Carbon::parse($job->shifts->where('shiftgroup_id',$shiftgroup->id)->first()->starts_at)->format('d.m.y') }}
                            @endif
                        </div>
                        <div class="col-xs-6 text-right">
                                @if(count($job->shifts->where('shiftgroup_id',$shiftgroup->id))==1)
                                    {{Carbon::parse($job->shifts->where('shiftgroup_id',$shiftgroup->id)->first()->starts_at)->format('H:i')}} - {{Carbon::parse($job->shifts->first()->ends_at)->format('H:i')}}
                                @endif
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </a>
    @endif
    @endforeach
</div>
@if($c<1)
Keine Schichten verfügbar.
@endif
@endif
@endforeach
@if($zaehler<1)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
    <p><b>Aktuell sind für dich leider keine freien Schichten verfügbar. <br />Du willst trotzdem mithelfen? 💪🏻  Melde dich bei crew@olylust.de</b></p>
</div>
    </div>
</div>

@endif
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
        <span class="fa fa-info-circle"></span> Es werden nur Schichten angezeigt, falls du an diesen auch Zeit hast (keine bereits zugesagte Schicht oder beworbene Schicht). <br />
        </div> 
    </div>
</div>
@endsection