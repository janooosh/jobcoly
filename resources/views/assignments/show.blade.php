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
                <a href="/assignments/my" title="Zurück" type="button" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Meine Schichten</a>
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
                    <p>{{$assignment->shift->description == '' ? '-':$assignment->shift->description}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Informationen zum {{$assignment->shift->shiftgroup->name}}</h5>
                    <p>{{$assignment->shift->shiftgroup->description == '' ? '-':$assignment->shift->shiftgroup->description}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Allgemeine Informationen als {{$assignment->shift->job->name}} ({{$assignment->shift->job->short}})</h5>
                    <p>{{$assignment->shift->job->description == '' ? '-':$assignment->shift->job->description}}</p>
                </div>
            </div>
            <hr />

            <div class="row">
                <div clas="col-md-12">
                    <h4>Deine Ansprechpartner</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>Schichtmanager (Allgemeine Fragen, Zulassung, ...)</h5>
                    <ul>
                    @foreach($assignment->shift->managers as $manager)
                        <li>{{$manager->user->firstname}} {{$manager->user->surname}} (<a href="mailto:{{$manager->user->email}}">{{$manager->user->email}}</a>)</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>Vor-Ort Ansprechpartner / Schichtdurchführung</h5>
                    @if(count($assignment->shift->supervisors)<1)
                    <span style="font-style:italic;">tba</span>
                    @else
                    <ul>
                    @foreach($assignment->shift->supervisors as $supervisor)
                        <li>{{$supervisor->user->firstname}} {{$supervisor->user->surname}} (<a href="mailto:{{$supervisor->user->email}}">{{$supervisor->user->email}}</a>)</li>
                    @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-12">
                     Bitte wende dich bei Fragen zunächst an deine Ansprechpartner, bei weiteren Unklarhaiten an <a href="mailto:crew@olylust.de">crew@olylust.de</a>.
                </div>
            </div>
    </div>

    <div class="col-md-5" id="jobinfo">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{$assignment->shift->job->short}} {{$assignment->shift->area == ''?'':'('.$assignment->shift->area.')'}}</h3>
                    {{$assignment->shift->job->name}}
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <span><b>{{$assignment->shift->shiftgroup->name}}</b></span>
                </div>
            </div>


            <br />
            <div class="row">
                <div class="col-xs-6">
                    <b>Schicht-ID</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->shift->id}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Datum</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->datum}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Uhrzeit</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->uhrzeit}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Ende</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->endeUhrzeit}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Dauer</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->duration}}
                </div>
            </div>
            <hr />
            @if($assignment->shift->gutscheine >0)
            <div class="row">
                <div class="col-xs-6">
                    <b>Gutscheine</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->shift->gutscheine}} / Stunde
                </div>
            </div>
            @endif

            @if($assignment->shift->awe >0)
            <div class="row">
                <div class="col-xs-6">
                    <b>AWE</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->shift->awe}} € / Stunde
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>AWE nach</b>
                </div>
                <div class="col-xs-6">
                    {{$assignment->shift->p}} Stunden
                </div>
            </div>
            @endif
        </div>
    </div> {{-- Ende Pannel --}}
    <div class="row">
        <div class="col-md-12">
            <a type="button" class="btn btn-default" href="/applications/{{$assignment->application_id}}"><i class="fa fa-key"></i> Bewerbung anzeigen</a>
        </div>
    </div>
    <br />
    <div class="row">
        @if($assignment->status=='Aktiv')
        <div class="col-md-12">
            <div class="alert alert-warning">
                Schichtbeginn um <b>{{$assignment->uhrzeit}}</b>.<br /> Bitte erscheine pünktlich, besser noch 10 Minuten vorher.
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($assignment->status=='Aktiv')
            <div class="alert alert-info">
                Status: Schicht verbindlich zugesagt.
            </div>
            @else
            <div class="alert alert-danger">
                Status: {{$assignment->status}}, du wurdest der Schicht entbunden.
            </div>
            @endif
        </div>
    </div>

    </div> {{-- Ende Rechte Seite --}}
</div>




@endsection
