@component('mail::message')

Hi,
ACHTUNG - TEST - ES WURDE NICHTS GEÄNDERT, ES WIRD NUR GETESTET...
wir haben dich für eine Schicht auf der OlyLust ausgetragen.<br />
Du hast hierzu bereits Informationen erhalten, falls nicht gib uns bitte sofort Bescheid.<br />
Bitte überprüfe deine Schichten und gib uns Bescheid, falls etwas nicht stimmt.
@component('mail::button', ['url' => $link])

Meine Schichten <br/>

@endcomponent
<br />

<b>Wie geht es jetzt weiter?</b>
<br />
Die Schicht wurde freigegeben. Falls du noch andere Schichten hast, sind diese davon nicht betroffen. Du kannst dich natürlich weiterhin auf alle anderen Schichten regulär bewerben. 

Bis Bald, Liebe Grüße, <br />
Katja & Nina <br />
<small>Crew Leitung der #OlyLust19</small>

@endcomponent