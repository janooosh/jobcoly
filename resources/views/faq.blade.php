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
            <h1 class="page-header">FAQs</h1>
        </div>
    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


{{-- START FAQs--}}
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#faq0">Kann ich mit meinen Freunden gemeinsam in eine Schicht?</a>
          </h4>
        </div>
        <div id="faq0" class="panel-collapse collapse">
          <div class="panel-body">
              Aber natürlich! Allerdings muss sich deine Freundin/dein Freund auch über dieses Tool bewerben, am besten bewerbt ihr euch gleichzeitig.<br />
              Gebt dann bei den Kommentaren in der Bewerbung unbedingt an, dass ihr gemeinsam arbeiten möchtet, dann geben wir unser Bestes dass das klappt. Versprechen können wir natürlich nichts, falls ihr eine größere Gruppe seid, kündigt ihr uns das am besten auch schon im Voraus unter <a href="mailto:crew@olylust.de">crew@olylust.de</a> an.

          </div>
        </div>
      </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#faq1">Wann erfahre ich, ob ich zu einer Schicht zugelassen wurde?</a>
      </h4>
    </div>
    <div id="faq1" class="panel-collapse collapse">
      <div class="panel-body">
          Wir versuchen, die Bewerbungen innerhalb weniger Tage zu beantworten.<br />
          Je nach Schicht und Anforderungen an die Schicht kann dies leider auch ein bisschen länger dauern, da wir bei einigen stressigen Schichten mit den Interessenten Rücksprache halten möchten.
      </div>
    </div>
  </div>

    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#faq2">Wer/Was sind Solidaritätsstunden?</a>
      </h4>
    </div>
    <div id="faq2" class="panel-collapse collapse">
      <div class="panel-body">
          Solidaritätsschichten betreffen nur ordentliche Ausschussmitglieder und Präsiden.<br />
          Ausgenommen davon sind Ressortleiter.
      </div>
    </div>
  </div>


    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#faq3">Kann ich meine Solidaritätsstunden auch aufteilen?</a>
      </h4>
    </div>
    <div id="faq3" class="panel-collapse collapse">
      <div class="panel-body">Klar! Du kannst z.B. zwei Vorbereitungsschichten mit je 4h machen. Es zählt nur die Summe. Eine Übersicht findest du auch unter "Rewards".</div>
    </div>
  </div>


<div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#faq4">Was passiert mit meinen Solidaritätsschichten, wenn eine Schicht kürzer ist als geplant?</a>
          </h4>
        </div>
        <div id="faq4" class="panel-collapse collapse">
          <div class="panel-body">Zur Berechnung der Solidaritätsschichten werden immer der geplanten Stunden herangezogen. Konkret heißt das: Wenn eine Schicht mit 4h geplant ist, sie aber nur 3h geht, bekommst du nach der Bestätigung trotzdem 4 Solidaritätsstunden gutgeschrieben. <br />
            
        </div>
        </div>
      </div>

    <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#faq5">Was hat es mit den Gutscheinen auf sich?</a>
              </h4>
            </div>
            <div id="faq5" class="panel-collapse collapse">
              <div class="panel-body">
                  Die meisten Schichten bieten Gutscheine als Entlohnung, i.d.R. 3 Gutscheine/h. Wie viel das bei der jeweiligen Schicht genau ist, findest du auf der letzten Bewerbungsseite, unter "Schichten" oder auch unter "Rewards".<br />
                  Die Gutscheine werden minutengenau berechnet, dadurch also im Zweifelsfall auch aufgerundet. Du erhältst Gutscheine stets für den tatsächlich geleisteten Stundenaufwand. Falls eine Schicht also länger geht als geplant, erhältst du natürlich auch mehr Gutscheine.<br />
                  Die Gutscheine werden nach der OlyLust auch in den Betrieben (Bierstube, Lounge, Disco) einsetzbar sein und ersetzen damit die Streifenkarten.
                  <ul>
                      <li>1 Gutschein = 1 Bier, 1 Softdrink, 1 Shot, kleine Pommes</li>
                      <li>2 Gutscheine = 1 große Portion Pommes (Bierstube), Longdrink (Lounge/Disco)</li>
                      <li>3 Gutscheine = 1 Cocktail (Lounge)</li>
                      <li>4 Gutscheine = 1 Tagesessen (Bierstube), inkl. Burgermontag sowie Schnitzel/Currywurst etc.</li>
                  </ul>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#faq99">AWE?</a>
                </h4>
              </div>
              <div id="faq99" class="panel-collapse collapse">
                <div class="panel-body">Du kannst eine Aufwandsentschädigung (AWE) für deine Tätigkeit erhalten, i.d.R. 7€/h nach 28 Stunden Tätigkeit. Genauere Infos findest du unter "Rewards".</div>
              </div>
            </div>
          

          <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" href="#faq6">Kann ich Gutscheine an meine Freunde weitergeben?</a>
                  </h4>
                </div>
                <div id="faq6" class="panel-collapse collapse">
                  <div class="panel-body">
                     Ja!
                </div>
                </div>
              </div>

              <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#faq7">Kann ich statt Gutscheinen auch Eintrittskarten erhalten?</a>
                      </h4>
                    </div>
                    <div id="faq7" class="panel-collapse collapse">
                      <div class="panel-body">
                          Ja!<br />
                          Für 2 Gutscheine erhältst du eine Eintrittskarte für den Krückenmontag.<br />
                          Für 3 Gutscheine erhältst du eine Eintrittskarte für den Weiberfasching (Donnerstag) und Studentenfasching (Freitag).<br />
                          Für 6 Gutscheine erhältst du eine Eintrittskarte für den Samstagsfasching (Samstag).
                      </div>
                    </div>
                  </div>

          <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a data-toggle="collapse" href="#faq8">Wie läuft das mit dem T-Shirt?</a>
                  </h4>
                </div>
                <div id="faq8" class="panel-collapse collapse">
                  <div class="panel-body">
                      Damit es es einheitlich bleibt, erhält jeder der arbeitet ein T-Shirt von uns. Im Gegensatz zu den letzten Jahren berechnen wir hierfür jedoch nur eine Arbeitsstunde.

                  </div>
                </div>
              </div>

    </div>

@endsection
