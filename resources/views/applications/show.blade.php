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
    <div class="col-md-7" id="applicantinfo">
        <div class="row">
            <div class="col-md-4">
                <a href="/applications" title="Zurück" type="button" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Zurück</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Bewerbung ansehen
                </h1>
            </div>
        </div>

            <hr />

            <div class="row" id="shiftReference">
                @if($a->status=="Aktiv")
                {{--<div class="col-md-12">
                    
                    <i class="fa fa-time"></i>Ablauf der Bewerbung (Automatische Zusage): {{$a->ablauf}} (<span id="ablaufApplication"></span>)<br />
                    <script>
                        jayCounter("{{$a->expiration}}", "ablaufApplication");
                    </script>
                </div> --}}
                @endif
                <div class="col-md-12">
                    <h5>Meine Motivation</h5>
                    <p>{{$a->motivation == '' ? '-':$a->motivation}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Bisherige Erfahrungen</h5>
                    <p>{{$a->experience == '' ? '-':$a->experience}}</p>
                </div>
                <div class="col-md-12">
                    <h5>Bemerkungen/Kommentare</h5>
                    <p>{{$a->notes == '' ? '-':$a->notes}}</p>
                </div>
            </div>
            <hr />

            {{-- Shift Description --}}
            @if($a->shift->description)
            <div class="row">
                <div class="col-md-12">
                    <b>Infos zur Schicht</b>
                </div>
                <div class="col-md-12">
                    <p>{{$a->shift->description}}</p>
                </div>
            </div>
            @endif
            
            {{-- Job Description --}}
            @if($a->shift->job->description)
            <div class="row">
                <div class="col-md-12">
                    <b>Allgemeine Infos als {{$a->shift->job->name}}</b>
                </div>
                <div class="col-md-12">
                    <p>{{$a->shift->job->description}}</p>
                </div>
            </div>
            @endif

            {{-- Shiftgroup Description --}}
            @if($a->shift->shiftgroup->description)
            <div class="row">
                <div class="col-md-12">
                    <b>Allgemeine Infos zum {{$a->shift->shiftgroup->name}}</b>
                </div>
                <div class="col-md-12">
                    <p>{{$a->shift->shiftgroup->description}}</p>
                </div>
            </div>
            @endif
 

    </div>

    <div class="col-md-5" id="jobinfo">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{$a->shift->job->short}} {{$a->shift->area == ''?'':'('.$a->shift->area.')'}}</h3>
                    {{$a->shift->job->name}}
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <span><b>{{$a->shift->shiftgroup->name}}</b></span>
                </div>
            </div>


            <br />
            <div class="row">
                <div class="col-xs-6">
                    <b>Schicht-ID</b>
                </div>
                <div class="col-xs-6">
                    {{$a->shift->id}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Start</b>
                </div>
                <div class="col-xs-6">
                    {{$a->start}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Ende</b>
                </div>
                <div class="col-xs-6">
                    {{$a->end}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Dauer</b>
                </div>
                <div class="col-xs-6">
                    {{$a->duration}}
                </div>
            </div>
            @if($a->shift->gutscheine >0)
            <div class="row">
                <div class="col-xs-6">
                    <b>Gutscheine</b>
                </div>
                <div class="col-xs-6">
                    {{$a->shift->gutscheine}} / Stunde
                </div>
            </div>
            @endif

            @if($a->shift->awe >0)
            <div class="row">
                <div class="col-xs-6">
                    <b>AWE</b>
                </div>
                <div class="col-xs-6">
                    {{$a->shift->awe}} € / Stunde
                </div>
            </div>
            @endif
        </div>
    </div> {{-- Ende Pannel --}}
    

    <div class="row">
        <div class="col-md-12">
            {{-- Schon entschieden? --}}
            @if($a->status=='Cancelled')
            <div class="alert alert-warning"><h4>Bewerbung zurückgezogen.</h4></div>
            @elseif($a->status=='Accepted')
            <div class="alert alert-success"><h4>Bewerbung akzeptiert.</h4></div>
            @elseif($a->status=='Rejected')
            <div class="alert alert-warning"><h4>Bewerbung abgelehnt.</h4></div>
            @elseif($a->status!='Aktiv')
            <div class="alert alert-warning"><h4>Sonderstatus: {{$a->status}}</h4></div>
            @else
            <div class="alert alert-info">Deine Bewerbung wird bearbeitet.</div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-outline btn-danger btn-lg" data-toggle="modal" data-target="#cancel"><i class="fa fa-times"></i> Zurückziehen</button>
                </div>
                <div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('applications.reject')}}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">{{Auth::user()->firstname}}, bist du sicher?</h4>
                            </div>
                            <div class="modal-body">
                            Möchtest du deine Bewerbung als <b>{{$a->shift->job->name}} ({{$a->shift->job->short}}) {{$a->shift->area == ''?'':'('.$a->shift->area.')'}} </b> wirklich zurückziehen? <br />
                                Du kannst dich danach zwar erneut auf diese Schicht bewerben, aber nur falls sie bis dahin nicht belegt ist.
                                <input type="hidden" id="application" name="application" value="{{$a->id}}"/> {{-- Application ID --}}
                            </div>
                            <div class="modal-footer">
                                     <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                                    <button type="submit" class="btn btn-primary btn-danger"><i class="fa fa-times"></i> Zurückziehen</button>
                            </div>
                        </div>
                        </form>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                </div><!-- End Modal -->
            </div>
        </div>
    </div>
 
    @endif
    <hr />
    <div class="row">
        <div class="col-md-12">
             Bitte wende dich bei Fragen oder Unklarheiten an <a href="mailto:crew@olylust.de">crew@olylust.de</a>.
        </div>
    </div>

    </div> {{-- Ende Rechte Seite --}}
</div>




@endsection
