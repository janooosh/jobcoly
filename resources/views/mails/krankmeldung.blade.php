@component('mail::message')

Hi <b>{{$user}}</b>,

wir haben dich für eine Schicht auf der OlyLust krankgemeldet und wünschen Gute Besserung!<br />
Bitte überprüfe deine Schichten und gib uns Bescheid, falls etwas nicht stimmt.
@component('mail::button', ['url' => $link])

Meine Schichten <br/>

@endcomponent
<br />

<b>Wie geht es jetzt weiter?</b>
<br />
Die Schicht wurde freigegeben. Falls du noch andere Schichten hast, sind diese davon nicht betroffen. Du kannst dich natürlich weiterhin auf alle anderen Schichten regulär bewerben. 
Wir kontaktieren dich persönlich, um ggf. eine Ersatzschicht auszumachen. <br/><br />
Gute Besserung, Liebe Grüße, <br />
Katja & Nina <br />
<small>Crew Leitung der OlyLust19</small>

@endcomponent