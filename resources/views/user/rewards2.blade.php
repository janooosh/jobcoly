<?php
use Carbon\Carbon;
?>

@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>


<div class="row">
    <div class="col-md-12">
        <h1 class="page-header"><i class="fa fa-trophy"></i> Meine Rewards</h1>
    </div>
</div>

{{-- Messages --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('info')) 
<div class="row">
    <div class="alert alert-info">
        <span class="fa fa-info"></span> {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('danger')) 
<div class="row">
    <div class="alert alert-danger">
        <span class='fa fa-warning'></span> {{$message}}
    </div>
</div>
@endif

@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        <span class='fa fa-warning'></span> {{$message}}</span>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-9">
<div class="panel panel-success">
        <div class="panel-heading">
            <b>Entlohnungsmodell</b>
        </div>
        <div class="panel-body">
            <p>In diesem Dokument wird erklärt, wie du für deine Schicht entlohnt werden kannst und was dir die Gutscheine bringen. Bei Fragen kannst du uns jederzeit unter crew@olylust.de kontaktieren.</p>
        </div>
</div>
    </div>
    <div class="col-md-3">
            <a type="button" href="{{asset('doc/rewards.pdf')}}" target="_blank" class="btn btn-outline btn-primary btn-lg btn-block"><span class="fa fa-file-pdf-o "></span> Entlohnung</a>
    </div>
</div>
<hr />
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
                {{--<tr>
                    <td colspan="6" style="text-align:right;">Freigegeben</td>
                    <td class="linie_t">{{round(0.7*$ausstehend_gutscheine)}}</td>
                </tr>--}}
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
    <div class="col-md-6">
        <p>Alle Stunden: <b><span id='ttotal'>{{$t_total}}</span></b> || Offene Stunden: <b><span id='tmaxcontainer'>{{$t_max_readable}}</span></b>, Vergebene Stunden: <b><span id='tvergebencontainer'>{{$t_vergeben_readable}}</span></b></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="{{route('rewards.save')}}">
            @csrf
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">ID</th>
                    <th scope="col">Schicht(en)</th>
                    <th scope="col">Zeit</th>
                    <th scope="col">Verfügbar</th>
                    <th scope="col">Gutscheine</th>
                    <th scope="col">AWE</th>
                    <th scope="col">AWE ab... [h]</th>
                    <th scope="col">#Gutscheine</th>
                    <th scope="col">#AWE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salarygroups as $s)
                <tr name='slg' id='slg{{$s->number}}'>
                    <td>{{$s->id}}</td>
                    <td>
                        <ul class="list-group">
                            @foreach($s->assignments as $a)
                            @if($a->shift->confirmed)
                                <li class="list-group-item">
                                    <b>{{$a->shift->job->short}}</b> <small>{{$a->shift->shiftgroup->name}}, {{Carbon::parse($a->start)->format('H:i').'-'.Carbon::parse($a->end)->format('H:i')}}</small>
                                </li>
                            @endif
                            @endforeach
                            </ul>
                    </td>
                    <td><span id='tma{{$s->number}}' name='tma'>{{$s->t_max_readable}}</span></td>
                    <td><span id='tve{{$s->number}}' name='tve'>{{$s->t_verfuegbar}}</span></td>
                    <td><input id='gut{{$s->number}}' name='gut[]' type='time' class='form-control' value='{{$s->t_g_nice}}'/> * <span id='seg{{$s->number}}'>{{$s->g}}</span> Gutscheine / h</td>
                    <td><input id='awe{{$s->number}}' name='awe[]' type='time' class='form-control' value='{{$s->t_a_nice}}' {{$s->awe_available ? '':'disabled'}}/> * <span id='sea{{$s->number}}'>{{$s->a}}</span> € / h</td>
                    <td><span id='pfl{{$s->number}}' name='pfl'>{{$s->p}}</span></td>
                    
                    
                    <td><span id='azg{{$s->number}}' name='azg'>{{$s->azg}}</span></td>
                    <td><span id='aza{{$s->number}}' name='aza'>{{$s->aza}} €</span></td>
                    <input type="hidden" name='salgroupid[]' value="{{$s->id}}"/>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top: solid 2px #aaa;">
                    <td colspan="7" style="text-align:right;"><b>Σ Summe</b></td>
                    <td><b><span id='gutscheine_summe'>{{$g_sum_rounded}}</span></b></td>
                    <td><b><span id='awe_summe'>{{$a_sum_rounded}}</span> €</b></td>
                </tr>
            </tfoot>
            
        </table>
        <p><small>Es können Rundungsfehler in Höhe von 0,01 € oder 0,01 Gutscheinen auftreten. Nachdem du die jeweilige Gruppe gespeichert hast, wird mit den genauen Werten gerechnet. Bitte kontaktiere uns unter crew@olylust.de, falls etwas deiner Meinung nach nicht stimmt.</small></p>
        <input type="submit" name='saver[]' class='btn btn-primary' id='save' value="Speichern" alt='Eingaben Speichern'/>
        <input type="submit" name='saver[]' class='btn btn-success' id='submit' value="Abschließen" alt='Eingaben Abschließen'/>

        <p>Du kannst deine Eingaben für später <b>Speichern</b> oder deine Auswahl <b>Abschließen</b>. Bitte beachte, dass du nach dem Abschließen die Daten an die Buchhaltung weitergegeben werden und deine Eingaben nicht mehr ändern kannst. </p>
       
    </form>
    </div>
</div>


@endif {{-- Ende Bestätigte --}}

{{-- Transaktionen --}}
@if(count($transactions)>0)
<div class="row">
    <div class="col-md-12">
        <h4>{{count($transactions)}} Ausgaben</h4>
        <p>Bisher hast du Folgendes erhalten:</p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead>
                <tr style="background-color:#eee;">
                    <th scope="col">ID</th>
                    <th scope="col">Beschreibung</th>
                    <th scope="col">Ausgabe am...</th>
                    <th scope="col">Gutscheine</th>
                </tr>
            </thead>
            @foreach($transactions as $t)
                <tr>
                    <td>{{$t->id}}</td>
                    <td>{{$t->beschreibung_short==''? '-':$t->beschreibung_short}}</td>
                    <td>{{$t->datetime}}</td>
                    <td>{{$t->amount}}</td>
                </tr>
            @endforeach
            <tbody>
            <tfoot>
                <tr style="border-top: solid 2px #aaa;">
                    <td colspan="3" style="text-align:right;"><b>Σ Summe</b></td>
                    <td><b>{{$gutscheine_erhalten_sum}}</b></td>
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>
</div> {{-- Ende Transatktionen --}}
@else

@endif

<br />
@endif


<script src="{{asset('js/rewards.js')}}"></script>
@endsection
