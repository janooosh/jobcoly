
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">AWE Auszahlungen</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    <div class="alert alert-info">
        Es werden die Auszahlungsbeträge basierend auf den bestätigten und abgeschlossenen Schichten angezeigt. Um in dieser Liste zu erscheinen, müssen die Mitarbeiter unter 'Rewards' ihre Schichten <b>abgeschlossen</b> haben.
    </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        Bitte wähle einen Zeitraum aus. Es gilt stets der Zeitpunkt, an dem der Mitarbeiter die Schichten bestätigt ("unterschrieben") hat (unter 'Rewards'), nicht an dem die Schicht stattgefunden hat.
    </div>
</div>
<br />
<div class="row">
    <form method="POST" action="{{route('auszahlung.post')}}">
    @csrf
    <div class="col-xs-4">
        <input class="form-control" id="start" name="start" type="date" value="{{$from->format('Y-m-d')}}" required autofocus/>
    </div>
    <div class="col-xs-4">
        <input class="form-control" id="ende" name="ende" type="date" value="{{$to->format('Y-m-d')}}" required autofocus/>
    </div>
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary"><span class="fa fa-rocket"></span> Los!</button>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <h5>Gewählter Zeitraum: {{$from->format('d.m.Y'.' - '.$to->format('d.m.Y'))}}</h5>
    </div>
    {{--<div class="col-md-6">
        <form method="POST" action="{{route('auszahlung.export')}}">
        @csrf
            <input type="hidden" id="from" name="from" value="{{$from->format('Y-m-d')}}"/>
            <input type="hidden" id="to" name="to" value="{{$to->format('Y-m-d')}}"/>
            <button type="submit" class="btn btn-outline btn-success btn-xs"><span class="fa fa-download"></span> .csv Export</button>   
        </form>
    </div>--}}
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered" id="userTable">
            <thead>
                <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">AWE (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paid_user as $p)
                    <tr>
                        <td>{{$p->id}}</td>
                        <td>{{$p->firstname.' '.$p->surname}}</td>
                        <td>{{number_format($p->awe,2)}} €</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Summe</td>
                    <td>{{number_format(round($awe_summe,2),2)}} €</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection