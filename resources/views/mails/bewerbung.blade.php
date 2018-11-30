@component('mail::message')

Hi <b>{{$user}}</b>,

vielen Dank für deine Bewerbung! Wir freuen uns, dass du die OlyLust unterstützen möchtest :) <br />
<br />
<b>Wie geht es jetzt weiter?</b>
<br />
Wir schauen ob du an der gewünschten Stelle ins Team passt und melden uns in <b>spätestens 3 Tagen</b> zurück.
Du kannst den Status deiner Bewerbungen jederzeit unter www.crew.olylust.de verfolgen. <br />

@component('mail::button', ['url' => $link])

Meine Bewerbungen <br/>

@endcomponent

Bei Fragen erreichst du uns jederzeit unter crew@olylust.de. <br /><br />
Liebe Grüße, <br />
Katja & Nina <br />
<small>Crew Leitung der OlyLust19</small>

@endcomponent