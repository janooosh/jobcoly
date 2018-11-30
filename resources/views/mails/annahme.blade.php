@component('mail::message')

Hi <b>{{$user}}</b>,

du wurdest für eine Schicht auf der OlyLust zugelassen - <b>Herzlichen Glückwunsch!</b> <br /><br />

Alle Infos zu deinen Schichten findest du auf www.crew.olylust.de.<br />
@component('mail::button', ['url' => $link])

Meine Schichten <br/>

@endcomponent
<br />
<b>Wie geht es jetzt weiter?</b>
<br />
Bitte erscheine pünktlich zu deiner Schicht und ggf. auch zum Personalmeeting. Falls du deine Schicht aus tiftigen Gründen (z.B. Krankheit) nicht antreten kannst,
informiere uns bitte so früh wie möglich.<br />

Bei Fragen erreichst du uns jederzeit unter crew@olylust.de. <br /><br />
Bis Bald, Liebe Grüße, <br />
Katja & Nina <br />
<small>Crew Leitung der OlyLust19</small>

@endcomponent