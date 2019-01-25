<?php
use Carbon\Carbon;
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
    <div class="col-md-12">
        <h1 class="page-header"><i class="fa fa-trophy"></i> Meine Rewards 2.0</h1>
    </div>
</div>

@if(count($assignments)<1)
<div class="row">
    <div class="col-md-12">
        <p>Du hast noch keine aktiven Schichten.</p>
        <a href="{{route('assignments.my')}}" title="Meine Schichten" type="button" class="btn btn-default"><i class="fa fa-beer"></i> Meine Schichten</a>
    </div>
</div>
@else

<div class="row">
    <div class="col-md-12">
        <p>Es wurden {{count($assignments)}} aktive Schichten gefunden.</p>
        <hr />
    </div>
</div>

{{-- Ausstehende --}}
@if(count($ausstehend)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($ausstehend)}} Unbestätigte Schichten</h4>
        <p>Für diese, noch unbestätigten, Schichten kannst du dir <b>bereits vor deiner Schicht</b> 70% der dir zustehenden Gutscheine auszahlen lassen!</p>
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> <b>Aufwandsentschädigung (AWE)</b> kann ausgewählt werden, sobald die Schicht bestätigt wurde.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">Schicht</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Geplant</th>
                    <th scope="col">Dauer [h]</th>
                    <th scope="col">Gutscheine /h</th>
                    <th scope="col">AWE /h</th>
                    <th scope="col" class="linie_t" data-toggle="tooltip" data-placement="top" title="Gutscheine können bereits im Vorraus ausgegeben werden :)">Σ Gutscheine</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ausstehend as $a)
                <tr>
                    <td>{{$a->shift->job->name}}</td>
                    <td>{{$a->date}}</td>
                    <td>{{$a->start.' - '.$a->end}}</td>
                    <td>{{$a->dauer}}</td>
                    <td>{{$a->gutscheine}}</td>
                    <td>{{$a->awe}}</td>
                    <td class="linie_t">{{$a->gutscheine_summe}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top: solid 2px #aaa!important;">
                    <td colspan="6" style="text-align:right;">Summe</td>
                    <td class="linie_t">{{round($ausstehend_gutscheine)}}<small><i>{{' ('.$ausstehend_gutscheine.')'}}</i></small></td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;">Freigegeben</td>
                    <td class="linie_t">{{round(0.7*$ausstehend_gutscheine)}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif {{-- Ende Ausstehende --}}

{{-- Bestätigte --}}
@if(count($confirmed)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($confirmed)}} Bestätigte Schichten</h4>
        
    </div>
</div>
<div class="row">
    <div class="col-md-12">

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">ID</th>
                    <th scope="col">Schicht(en)</th>
                    <th scope="col">Zeit</th>
                    <th scope="col">Gutscheine</th>
                    <th scope="col">AWE</th>
                    <th scope="col">AWE ab... [h]</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salarygroups as $s)
                <tr>
                    <td>{{$s->id}}</td>
                    <td>
                        <ul class="list-group">
                            @foreach($s->assignments as $a)
                                <li class="list-group-item">
                                    <b>{{$a->shift->job->short}}</b> <small>{{$a->shift->shiftgroup->name}}, {{Carbon::parse($a->start)->format('H:i').'-'.Carbon::parse($a->end)->format('H:i')}}</small>
                                </li>
                            @endforeach
                            </ul>
                    </td>
                    <td>{{$s->t_max_readable}}</td>
                    <td><input type='time' class='form-control'/> * {{$s->g}} Gutscheine / h</td>
                    <td><input type='time' class='form-control'/> * {{$s->a}} € / h</td>
                    <td>{{$s->p}}</td>
                </tr>
                @endforeach
            </tbody>
            
        </table>

        
        Zusammengerechnete Zeiten: {{$t_max}}
        @foreach($salarygroups as $s)
        <br />
        {{$s->id.' hat vong Zeit: '.$s->t}}<br />
        @endforeach
    </div>
</div>


@endif {{-- Ende Bestätigte --}}

<br />
@endif

<script>
    function fromMinToString(min) {
        var h = min/60;
        var m = min%60;

        if(h.toString().length==1) {
            h = '0'+h;
        }
        if(m.toString().length==1) {
            m = '0'+m;
        }
        var ret = h+':'+m;
        return ret;
    }
</script>

@endsection
