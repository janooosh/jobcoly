<?php
use Illuminate\Support\Facades\Auth;
?>
@component('mail::message')

Hi <b>jan</b>,

Herzlichen Glückwunsch!

It looks neat, no?<br />

Was: {{$was}} <br />

@component('mail::button', ['url' => $link])

Take Me Back <br/>

@endcomponent

Regards,<br />
DevBlog.

@endcomponent