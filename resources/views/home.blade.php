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
            <h1 class="page-header">Hey, {{Auth::user()->firstname}}! 😃</h1>
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
                                    <i class="fa fa-briefcase fa-5x"></i>
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
                <h1>Neu hier?</h1>
                <p><b>❤️ - lich Willkommen!</b></p>
                <p>Sämtliche Schichten für die <b>#OlyLust2020</b> - Zauberwald werden in diesem Jahr über dieses Tool vergeben.
                <br />
                Schichten werden im Allgemeinen nach "First-Come-First-Serve" vergeben, allerdings müsst ihr euch für die Schichten bewerben. Keine Angst, das ist ganz einfach!<br />
                <br /><b>Lege direkt los:</b>
                <br />
                <a href="/applications/new" type="button" class="btn btn-default"><i class="fa fa-rocket"></i> Neue Bewerbung</a>
                <br /><br />
                Wir werden uns deine Bewerbung so schnell wie möglich ansehen und dir dann eine Rückmeldung geben. Mit diesem Prinzip möchten wir sicherstellen, dass im Vergleich zu den letzten Jahren jeder der eine bestimmte Schicht möchte auch eine faire Chance auf diese bekommt, und Schichten nicht sofort vergeben sind. <br />
                Du kannst den Status deiner Bewerbungen unter "Bewerbungen" verfolgen. Auch wenn es einmal nicht sofort klappt, kannst du dich zu einem späteren Zeitpunkt erneut auf diese Schicht bewerben, vorausgesetzt es sind noch freie Stellen offen.
                <br /><br />
                <hr />
                Wir stehen dir bei Fragen oder Problemen jederzeit unter <a href="mailto:crew@olylust.de">crew@olylust.de</a> zur Verfügung.

@endsection
