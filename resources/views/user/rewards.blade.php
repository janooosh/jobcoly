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
        <h1 class="page-header"><i class="fa fa-trophy"></i> Meine Rewards</h1>
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
        <a href="{{route('assignments.my')}}" title="Meine Schichten" type="button" class="btn btn-default"><i class="fa fa-beer"></i> Meine Schichten</a>
    </div>
</div>
<br />
<div class="row">
    <div class="col-md-12">
        @if($openFlag==0 && $pflichtstunden>=8 && Auth::user()->is_pflichtschicht==1)
                <div class="alert alert-success">
                        <i class="fa fa-check"></i> Solidaritätsschicht erfüllt.
                </div>
        
        @elseif($openFlag>0 && $pflichtstunden>=8 && Auth::user()->is_pflichtschicht==1)
        <div class="alert alert-info">
            Solidaritätsschichten nach erfolgreicher Bestätigung erfüllt.
        </div>
        @elseif($openFlag==0 && $pflichtstunden<8 && Auth::user()->is_pflichtschicht==1)
            <div class="alert alert-danger">
                <i class="fa fa-frown-o"></i> Solidaritätsschichten nicht erreicht.
            </div>
        @elseif($openFlag>0 && $pflichtstunden<8 && Auth::user()->is_pflichtschicht==1)
            <div class="alert alert-warning">
                <i class="fa fa-warning"></i> 8 Solidaritätsschichten noch nicht erreicht, aktuell bist du bei {{$pflichtstunden}} (Bestätigung vorausgesetzt)
            </div>
        @endif
    </div>
    <div class="col-md-12">
        <p>Für die Berechnung der Solidaritätsstunden werden die geplanten Stunden berücksichtigt, auch wenn die Schicht nacher kürzer ist.
    </div>
</div>


<br />
<table class="table">
  <thead>
    <tr>
      <th scope="col">Schicht</th>
      <th scope="col">Datum</th>
      <th scope="col">Geplant</th>
      <th scope="col"></th>
      <th scope="col">Bestätigt</th>
      <th scope="col"></th>
      @if($gutscheine)
        <th scope="col">Gutscheine / h</th>
      @endif
      @if($awe)
      <th scope="col">AWE / h</th>
      @endif
    </tr>
  </thead>
  <tbody>
@foreach($assignments as $a)
    <tr>
      <td>{{$a->shift->job->short}} | {{$a->shift->job->name}} {{$a->shift->area==''?'':'('.$a->shift->area.')'}}</td>
      <td>{{$a->datum}}</td>
      <td>{{$a->start_plan}} - {{$a->end_plan}}</td>
      <td>{{$a->dauer_plan}} h</td>
      <td>
          @if($a->shift->confirmed==1)

          @if($a->confirmed==0)
            <span style="color:red;">Nicht bestätigt</span>
          @elseif($a->confirmed==1)
           {{$a->start_real}} - {{$a->end_real}}
          @else
          <span style="font-decoration:italic;">Fehler</span>
          @endif

          @else
          <span style="font-decoration:italic;">Ausstehend</span>
          @endif
      </td>
      <td>
        @if($a->shift->confirmed==1 && $a->confirmed==1)
            {{$a->dauer_real}} h
        @endif
      </td>
      @if($gutscheine)
      <td>{{$a->shift->gutscheine}}</td>
      @endif

      @if($awe)
      <td>{{$a->shift->awe}}</td>
      @endif
      
    </tr>
@endforeach
  </tbody>
  <tfoot>
    <tr>
        <td colspan="3"><b>SUMME</b></td>
        <td><b>{{$plan_return}} h</b></td>
        <td></td>
        <td><b>{{$ist_return}} h</b></td>
        @if($gutscheine)
        <td></td>
        @endif
        @if($awe)
        <td></td>
        @endif
    </tr>
  </tfoot>
</table>

<br />
@if($gutscheine)
<div class="row">
    <div class="col-md-12">
        <h4>Verfügbare Gutscheine</h4>
    </div>
</div>

<br />
<div class="row">
    <div class="col-xs-4">
        <b>Summe Gutscheine</b>
    </div>
    <div class="col-xs-4">
        <b>{{$realGutscheine}}</b>
       
    </div>
</div>
@if($openFlag>0)
<div class="row">
    <div class="col-xs-12">
        <span style="color:red;"><small><i class="fa fa-warning"></i> 30% Vorbehalt mit einberechnet, da Schichten noch nicht bestätigt sind, und sich die Anzahl der Gutscheine nach den bestätigten (= tatsächlichen) Stunden richtet, und nicht nach den geplanten Stunden.</small></span>
        <br />
        <small>6 Gutscheine wurden für das T-Shirt bereits abgezogen.</small>
    </div>
</div>

@endif

@endif

<hr />

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseshirt" aria-expanded="false" class="collapsed">
                            T-Shirt
                        </a>
                    </h4>
                </div>
                <div id="collapseshirt" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <p>
                                Jedes Crew Mitglied erhält ein offizielles OlyLust T-Shirt. Dieses kann an allen Personalmeetings abgeholt werden. Die Vorbereitungs-/Promoschichten erhalten ihre T-Shirts nach Absprache auch bereits vorher. <br />
                                <b>Dein T-Shirt:</b> Schnitt: {{Auth::user()->shirt_cut=='M'?'Männlich':'Weiblich'}}, Größe: {{Auth::user()->shirt_size}}
                        </p>
                    </div>
                </div>
            </div>
        @if($gutscheine)
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseGutscheine" aria-expanded="false" class="collapsed">
                            Gutscheine
                        </a>
                    </h4>
                </div>
                <div id="collapseGutscheine" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <p>
                            Gutscheine sind übertragbar, daher kannst du sie gerne auch an Freunde weitergeben! Mit einer von dir unterzeichneten Vollmacht kann die Gutscheine auch deine Freundin/dein Freund abholen. Zudem kannst du sie bei den Treffen auch in Eintrittskarten umtauschen lassen.<br />
                            Ein Gutschein hat folgenden Wert: 1 Gutschein = Antialkoholisches Getränk (außer RedBull), Shot oder Klopfer; 2 Gutscheine = Longdrink oder Bier (Helles/Radler/Alkoholfreies/Weißbier/Desperados), RedBull, 3 Gutscheine = Cocktail.
                            Für eine Eintrittskarte am <b>Weiberfasching</b> (Donnerstag), <b>DER Studentenfasching</b> (Freitag) und <b>Scavenger Monday</b> (Montag) fallen 3 Gutscheine an. Für einen Eintritt am <b>Legendären Samstagsfasching</b> (Samstag) fallen 6 Gutscheine an.
                            <br />
                            Die Gutscheine ersetzen zudem das bisher bekannte Streifenkartenmodell und können 1:1 im Nachhinein in den Betrieben eingesetzt werden.
                            Gutscheine kannst du dir an allen Veranstaltungstagen jeweils zwischen 18:00h - 19:00h in der Bierstube abholen. Zudem kannst du auch bereits am Mittwoch, 27.02.2019 zwischen 20:00h - 21:00h in der Bierstube vorbeikommen.
                            Unmittelbar nach Fasching sowie etwa 2 Wochen nach der Veranstaltung bieten wir weitere Termine an, die wir per E-Mail kommunizieren werden. <br />
                            Solange nicht alle Schichten bestätigt wurden kannst du dir bereits 75% der Gutscheine (basierend auf den geplanten Stunden und den bereits durchgeführten) bereits <b>im Vorraus</b> auszahlen lassen. 
                            Die Schichtbestätigung erfolgt normalerweise innerhalb von 24 Stunden nach Schichtende. <br />
                            Für die Berechnung der Gutscheine ist die tatsächlich bestätigte Zeit verantwortlich. Die Gutscheine werden zunächst pro Schicht (minutengenau) berechnet, anschließend addiert und auf eine ganze Zahl gerundet.<br />
                            Das T-Shirt (6 Gutscheine) wird automatisch abgezogen (auch noch vor dem Vorbehalt).
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if($awe)
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseawe" aria-expanded="false" class="collapsed">
                            Aufwandsentschädigung (AWE) 
                        </a>
                    </h4>
                </div>
                <div id="collapseawe" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <p>
                                Deine Aufwandsentschädigung (AWE) wird dir nach dem Fasching ausbezahlt, bis spätestens 15. April 2019. Bitte fülle, falls noch nicht geschehen (sämtliche Betriebsmitarbeiter die bereits AWE bekommen haben brauchen es nicht erneut auszufüllen), folgendes Formular zur steuerlichen Erfassung aus. <br />
                                Datei folgt.
                                <br />
                                Bei Fragen stehen wir dir unter <a href="mailto:crew@olylust.de">crew@olylust.de</a> zur Verfügung.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>  
@endif

@endsection
