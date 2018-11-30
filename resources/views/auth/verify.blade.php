@extends('layouts.app')

@section('content')


<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Fast geschafft...</h1>
    </div>
</div>

@if (session('resent'))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">
           Ein neuer Link ist auf dem Weg zu dir! <i class="fa fa-rocket"></i><br />Bitte überprüfe auch deinen Spam-Ordner.
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <p>Bitte bestätige deine E-Mail Adresse mit dem Link, den du gerade erhalten hast. <br />
        Falls keine E-Mail ankam, kannst du hier eine neue E-Mail erhalten.</p>
        <br />
        <a href="{{route('verification.resend')}}" type="button" class="btn btn-default">Neue E-Mail schicken</a>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        Bei technischen Problemen schicke bitte eine E-Mail an <a href="mailto:webmaster@olylust.de">webmaster@olylust.de</a>.
    </div>
</div>

@endsection
