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
            Aber natürlich! Allerdings muss sich deine Freundin/dein Freund auch über dieses Tool bewerben, am besten bewerbt ihr euch gleichzeitig. <br />
            Gebt dann bei den Kommentaren in der Bewerbung unbedingt an, dass ihr gemeinsam arbeiten möchtet, dann geben wir unser Bestes dass das klappt. Versprechen können wir natürlich nichts, falls ihr eine größere Gruppe seid kündigt ihr uns das am besten auch schon im Vorraus unter <a href="mailto:crew@olylust.de">crew@olylust.de</a> an.

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
      <div class="panel-body">Wir versuchen, die Bewerbungen innerhalb weniger Tage zu beantworten. 
          Je nach Schicht und Anforderungen an die Schicht kann dies leider auch ein bisschen länger dauern, 
          da wir bei einigen stressigen Schichten mit den Interessenten Rücksprache halten möchten.</div>
    </div>
  </div>

    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#faq2">Wer/Was Pflichtstunden?</a>
      </h4>
    </div>
    <div id="faq2" class="panel-collapse collapse">
      <div class="panel-body">Pflichtschichten betreffen nur ordentliche Ausschussmitglieder und Präsiden. <br />
        Ausgenommen davon sind Ressortleiter.</div>
    </div>
  </div>


    <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#faq3">Kann ich meine Pflichtstunden auch aufteilen?</a>
      </h4>
    </div>
    <div id="faq3" class="panel-collapse collapse">
      <div class="panel-body">Klar! Du kannst z.B. zwei Vorbereitungsschichten mit je 4h machen. Es zählt nur die Summe. Eine Übersicht findest du auch unter "Rewards".</div>
    </div>
  </div>


<div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#faq4">Was passiert mit meinen Pflichtschichten, wenn eine Schicht kürzer ist als geplant?</a>
          </h4>
        </div>
        <div id="faq4" class="panel-collapse collapse">
          <div class="panel-body">Zur Berechnung der Pflichtschichten werden immer der geplanten Stunden herangezogen. Konkret heißt das: Wenn eine Schicht mit 4h geplant ist, sie aber nur 3h geht, bekommst du nach der Bestätigung trotzdem 4 Pflichtstunden gutgeschrieben. <br />
            
        </div>
        </div>
      </div>

    <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#faq5">Was hat es mit den Gutscheinen auf sich? Wo sind meine Streifenkarten?</a>
              </h4>
            </div>
            <div id="faq5" class="panel-collapse collapse">
              <div class="panel-body">
                  Die meisten Schichten bieten Gutscheine als Entlohnung, i.d.R. 3 Gutscheine/h. Wie viel das bei der jeweiligen Schicht genau ist, findest du auf der letzten Bewerbungsseite, unter "Schichten" oder auch unter "Rewards". <br />
                Die Gutscheine werden minutengenau berechnet, dadurch also im Zweifelsfall auch aufgerundet. Du erhälst Gutscheine stets für den tatsächlich geleisteten Stundenaufwand. Falls eine Schicht also länger geht als geplant, 
                erhälst du natürlich auch mehr Gutscheine. <br />
                Eine Übersicht sowie weitere Informationen findest du unter "Rewards". <br /><br />
                Die Gutscheine werden nach der OlyLust auch in den Betrieben (Bierstube, Lounge, Disko) einsetzbar sein und ersetzen damit die Streifenkarten. <br />
                1 Gutschein = 1 Antialkoholisches Getränk/1 Shot <br />
                2 Gutscheine = 1 Longdrink oder Bier (Helles/Radler/Weißbier/Desperados), 1 Red Bull <br />
                3 Gutscheine = 1 Cocktail <br />
                <br />Der Wert für ein Essen in der Bierstube wird zeitnah noch ergänzt.
            </div>
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
                         Ja! <br />
                         Für 3 Gutscheine erhälst du eine Eintrittskarte für den Weiberfasching (Donnerstag), DER Studentenfasching (Freitag) und den Scavenger Monday (Montag). <br />
                         Für 6 Gutscheine erhälst du eine Eintrittskarte für den Legendären Samstagsfasching (Samstag).
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
                      Die ersten 6 Gutscheine die du "verdienst" gehen auf das T-Shirt, dieses kannst du also natürlich behalten. <br />
                </div>
                </div>
              </div>

              <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#faq9">Sind die T-Shirts 2019 cooler als 2018?</a>
                      </h4>
                    </div>
                    <div id="faq9" class="panel-collapse collapse">
                      <div class="panel-body">
                     Ja! Weniger OlyLust Werbung, besserer Schnitt (andere Druckerei) und auf jeden Fall ein cooles Design :)
                    </div>
                    </div>
                  </div>

    </div>

@endsection
