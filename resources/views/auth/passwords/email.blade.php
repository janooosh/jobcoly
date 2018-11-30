@extends('layouts.app')

@section('content')

<div class="row" style="margin-bottom: 10px;">
    <div class="col-lg-12">
        <h1 class="page-header">Passwort zurücksetzen</h1>
    </div>
</div>

@if (session('status'))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    </div>
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
        @csrf

<div class="row">
    <div class="col-md-6">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
        <strong>{{ $errors->first('email') }}</strong>
    </div>
</div>
<br />
<div class="row">
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary">
            {{ __('Passwort Reset Link schicken') }}
        </button>
    </div>
</div>


@endsection
