<?php
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.app')

@section('content')
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
        <div class="col-lg-8">
            <h1 class="page-header">Willkommen, {{Auth::user()->firstname}}</h1>
        </div>
    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-beer fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{count(Auth::user()->activeAssignments)}}</div>
                                    <div>Aktive Schichten</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('assignments.my')}}">
                            <div class="panel-footer">
                                <span class="pull-left">Meine Schichten</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-beer fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{count(Auth::user()->activeApplications)}}</div>
                                    <div>Offene Bewerbungen</div>
                                </div>
                            </div>
                        </div>
                        <a href="applications">
                            <div class="panel-footer">
                                <span class="pull-left">Meine Bewerbungen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                </div>
                <p>Hier kommen noch mehr tolle Infos hin.</p>

@endsection
