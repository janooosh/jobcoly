@extends('layouts.intro')

@section('content')
<div class="row">
    <div class="alert alert-info">
       <b>Wartungsarbeiten</b><br />
       Bitte entschuldigt die Verzögerung. Aktuell wird das neue Entlohnungsmodell implementiert. Ab Sonntag, 27.01. ist das Tool wieder erreichbar. <br />
       Bei Fragen stehen euch Katja und Nina unter crew@olylust.de jederzeit zur Verfügung.
    </div>
    
</div>
<div class="row">
    
</div>

{{--
<form class="form-signin" method="POST" action="{{ route('login') }}">
@csrf    
    <img class="mb-4" src="{{ asset('img/ananas.png') }}" alt="" width="100" height="auto">
    <h1 class="h3 mb-3 font-weight-normal">{{ __('#OlyLust19 - Login') }}</h1>
        <input id="email" type="email" placeholder="{{ __('E-Mail Adresse') }}"class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        
        <input id="password" type="password" placeholder="{{ __('Passwort') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>               
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif

        <div class="checkbox mb-3">
          <label>
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                </label>
            </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <p><a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Passwort vergessen?') }}
        </a>
        <a class="btn btn-primary btn-lg btn-block" href="{{ route('register') }}">{{ __('Neu hier?') }}</a></p>
        <p class="mt-3 mb-5 text-muted">&copy; 2018 | Studenten im Olympiazentrum e.V. <br /> <a href="{{route('impressum')}}" target="_blank">Impressum</a> | <a href="{{route('datenschutz')}}" target="_blank">Datenschutz</a></p>
</form>
--}}


@endsection