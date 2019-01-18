<?php
use \App\Http\Controllers\ApplicationsController;
use \App\Http\Controllers\AssignmentsController;
use \App\Http\Controllers\EvaluationsController;
use \App\Http\Controllers\ShiftsController;
use \App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
?>

@extends('layouts.app')

@section('content')
<script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
</script>
<script src="{{ asset('js/evaluations.js')}}"></script>
{{-- Message --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

{{-- Warning --}}
@if($message = Session::get('warning')) 
<div class="row">
    <div class="alert alert-warning">
        {{$message}}
    </div>
</div>
@endif

{{-- Error --}}
@if($errors->any())
<div class="alert alert-danger">
    <p><b>Bitte korrigiere die Fehler:</b></p>
    <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
    </ul>
</div>
@endif


<form method="POST" action="{{route('profil.update')}}">
    @csrf
<div class="row">
    <div class="col-md-7" id="applicantinfo">
        <div class="row" style="padding-bottom:20px;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="firstname">Vorname *</label>
                        <input id="firstname" type="text" placeholder="Vorname" class="form-control" name="firstname" value="{{$user->firstname}}" required autofocus>
                    </div>
                    <div class="col-md-6">
                        <label for="surname">Nachname *</label>
                        <input id="surname" type="text" placeholder="Nachname" class="form-control" name="surname" value="{{$user->surname}}" required autofocus>
                    </div>
                </div>
        </div>
        <div class="row" style="padding-bottom:20px;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="email">E-Mail *</label>
                        <input id="email" type="email" placeholder="E-Mail Adresse" class="form-control" name="email" value="{{$user->email}}" disabled required autofocus>
                    </div>
                    <div class="col-md-6">
                        <label for="phone">Mobil</label>
                        <input id="phone" type="tel" placeholder="Mobil" class="form-control" name="phone" value="{{$user->mobile}}" autofocus>
                    </div>
                </div>
        </div>
        <div class="row" style="padding-bottom:20px;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="birthday">Geburtsdatum</label>
                        <input id="birthday" type="date" placeholder="Geburtstag" class="form-control" name="birthday" value="{{$user->birthday}}" autofocus>
                    </div>
                    <div class="col-md-6">
                        <label for="gesundheitszeugnis">Gesundheitszeugnis?</label>
                        <select id="gesundheitszeugnis" type="selection" class="form-control d-block w-100" name="gesundheitszeugnis">
                            <option value="" disabled {{$user->has_gesundheitszeugnis==''?'selected':''}}>Auswählen...</option>
                            <option value="1" {{$user->has_gesundheitszeugnis=='1'?'selected':''}}>Ja</option>
                            <option value="0" {{$user->has_gesundheitszeugnis=='0'?'selected':''}}>Nein</option>
                        </select>
                    </div>
                </div>
        </div>
        <hr />
        <div class="row" style="padding-bottom:20px;">
            <div class="row">
                <div class="col-md-12">
                    <b>Anschrift</b> <br /><br />
                </div>
            </div>
                <div class="row">
                    <div class="col-md-5 col-xs-8">
                        <input id="strasse" type="text" placeholder="Straße" class="form-control" name="strasse" value="{{$user->street}}" autofocus>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <input id="hausnummer" type="string" placeholder="Nr." class="form-control" name="hausnummer" value="{{$user->hausnummer}}" autofocus>
                    </div>
                    @if($user->is_olydorf)
                    <div class="col-md-3 col-xs-6">
                        <select name="olycat" id="olycat" class="form-control d-block w-100" required>
                            <option value="" {{$user->oly_cat==''?'selected':''}} disabled>Auswählen...</option>
                            <option value="Hochhaus" {{$user->oly_cat=='Hochhaus'?'selected':''}}>Hochhaus</option>
                            <option value="Bungalow" {{$user->oly_cat=='Bungalow'?'selected':''}}>Bungalow</option>
                            <option value="HJK"{{$user->oly_cat=='HJK'?'selected':''}}>HJK</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <input id="oly_room" type="text" placeholder="N 44, A 1122...." class="form-control" name="oly_room" value="{{$user->oly_room}}" required autofocus>
                    </div>
                    @endif
                </div>
        </div>
        <div class="row">
                <div class="row">
                    <div class="col-xs-4">
                        <input id="plz" type="number" placeholder="PLZ" class="form-control" name="plz" value="{{$user->plz}}" autofocus>
                    </div>
                    <div class="col-xs-8">
                        <input id="ort" type="text" placeholder="Ort" class="form-control" name="ort" value="{{$user->ort}}" autofocus>
                    </div>
                </div>
        </div>
        <hr />
        @if($user->is_student)
        <div class="row" style="padding-bottom:20px;">
                <div class="row">
                        <div class="col-md-12">
                            <b>Studium</b> <br /><br />
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-5 col-xs-8">
                        <input id="studiengang" type="text" placeholder="M.Sc. Partyographie" class="form-control" name="studiengang" value="{{$user->studiengang}}" autofocus>
                    </div>
                    <div class="col-md-3 col-xs-4">
                        <input id="semester" type="number" placeholder="" class="form-control" name="semester" value="{{$user->semester}}" autofocus>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <select name="uni" id="uni" class="form-control d-block w-100" required>
                            <option value="" {{$user->uni==''?'selected':''}} disabled>Auswählen...</option>
                            <option value="TUM" {{$user->uni=='TUM'?'selected':''}}>TU München</option>
                            <option value="LMU" {{$user->uni=='LMU'?'selected':''}}>LMU München</option>
                            <option value="HM" {{$user->uni=='HM'?'selected':''}}>Hochschule München</option>
                            <option value="Andere"{{$user->uni=='Andere'?'selected':''}}>Andere</option>
                        </select>
                    </div>
                </div>
        </div>
        @endif
        <div class="row" style="padding-bottom:20px;">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="aboutyou">Anmerkungen/Bemerkungen/Über Dich</label>
                    <textarea id="aboutyou" name="aboutyou" placeholder="Erzähl uns etwas über dich! Warst du bereits auf früheren OlyLüsten?" class="form-control" rows="3">{{$user->about_you}}</textarea>
                </div>
            </div>
        </div>



    </div>

    <div class="col-md-5" id="jobinfo">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3>Mein Profil</h3>
                </div>
            </div>
        </div>
        <div class="panel-body">

            @if($user->is_verein)
            <div class="row">
                <div class="col-xs-6">
                    <b>Ordentliches Mitglied</b>
                </div>
                <div class="col-xs-6">
                    <select name="ausschussSelect" id="ausschussSelect" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->ausschuss==''?'selected':''}}>Kein Ausschuss</option>
                        <option value="CTA" {{$user->ausschuss=='CTA' ? 'selected':''}}>Controlling/Consulting</option>
                        <option value="FOTO" {{$user->ausschuss=='FOTO'?'selected':''}}>Fotoclub</option>
                        <option value="KULT" {{$user->ausschuss=='KULT' ? 'selected':''}}>Kult</option>
                        <option value="FTA" {{$user->ausschuss=='FTA'?'selected':''}}>Film & Theater</option>
                        <option value="GRAS" {{$user->ausschuss=='GRAS'?'selected':''}}>Gras</option>
                        <option value="WA" {{$user->ausschuss=='WA' ? 'selected':''}}>Werkstattausschuss</option>
                        
                        <option value="VA" {{$user->ausschuss=='VA' ? 'selected':''}}>Veranstaltungsausschuss</option>
                        <option value="MTA" {{$user->ausschuss=='MTA'?'selected':''}}>Miet - & Ausländerausschuss</option>
                        <option value="KOMITEE" {{$user->ausschuss=='KOMITEE' ? 'selected':''}}>Olympisches Komitee</option>
                        <option value="FA" {{$user->ausschuss=='FA'?'selected':''}}>Finanzausschuss</option>
                        <option value="TA" {{$user->ausschuss=='TA' ? 'selected':''}}>Töpferausschuss</option>
                        <option value="KICKER" {{$user->ausschuss=='KICKER'?'selected':''}}>Kicker-Ausschuss</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Bierstube</b>
                </div>
                <div class="col-xs-6">
                    <select name="isBierstube" id="isBierstube" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->is_bierstube==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="1" {{$user->is_bierstube ? 'selected':''}}>Ja</option>
                        <option value="0" {{!$user->is_bierstube ? 'selected':''}}>Nein</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Disco/Lounge</b>
                </div>
                <div class="col-xs-6">
                    <select name="isDisco" id="isDisco" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->is_disco==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="1" {{$user->is_disco ? 'selected':''}}>Ja</option>
                        <option value="0" {{!$user->is_disco ? 'selected':''}}>Nein</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Präside</b>
                </div>
                <div class="col-xs-6">
                    <select name="isPraside" id="isPraside" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->is_praside==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="1" {{$user->is_praside ? 'selected':''}}>Ja</option>
                        <option value="0" {{!$user->is_praside ? 'selected':''}}>Nein</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>Dauerjob</b>
                </div>
                <div class="col-xs-6">
                    <select name="isDauerjob" id="isDauerjob" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->is_dauerjob==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="1" {{$user->is_dauerjob ? 'selected':''}}>Ja</option>
                        <option value="0" {{!$user->is_dauerjob ? 'selected':''}}>Nein</option>
                    </select>
                </div>
            </div>
            <hr />
            @endif

            <div class="row">
                <div class="col-xs-6">
                    <b>T-Shirt Schnitt</b>
                </div>
                <div class="col-xs-6">
                    <select name="shirtCut" id="shirtCut" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->shirt_cut==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="M" {{$user->shirt_cut=='M' ? 'selected':''}}>Männlich</option>
                        <option value="W" {{$user->shirt_cut=='W' ? 'selected':''}}>Weiblich</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <b>T-Shirt Größe</b>
                </div>
                <div class="col-xs-6">
                    <select name="shirtSize" id="shirtSize" class="form-control d-block w-100" disabled required>
                        <option value="" {{$user->shirt_size==''?'selected':''}} disabled>Bitte auswählen...</option>
                        <option value="XS" {{$user->shirt_size=='XS' ? 'selected':''}}>S</option>
                        <option value="S" {{$user->shirt_size=='S' ? 'selected':''}}>S</option>
                        <option value="M" {{$user->shirt_size=='M' ? 'selected':''}}>M</option>
                        <option value="L" {{$user->shirt_size=='L' ? 'selected':''}}>L</option>
                        <option value="XL" {{$user->shirt_size=='XL' ? 'selected':''}}>XL</option>
                        <option value="xx" {{$user->shirt_size=='xx' ? 'selected':''}}>XXL</option>
                    </select>
                </div>
            </div>


        </div>
    </div> {{-- Ende Pannel --}}
   <div class="row">
    <div class="col-md-6">
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Speichern</button><br /><p><small>* = Pflichtangaben</small></p>
    </div>
   </div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            Bitte wende dich bei Fragen oder Unklarheiten an die RL Personal, <a href="mailto:crew@olylust.de">crew@olylust.de</a>.
        </div>
    </div>

    </div> {{-- Ende Rechte Seite --}}
</div>
</form>



@endsection
