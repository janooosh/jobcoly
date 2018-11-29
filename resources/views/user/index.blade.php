<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Benutzer verwalten</h1>
    </div>
</div>

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

<div class="row">
@if(count($user)>0)
<div class="col-ld-10">

<p>Es sind {{count($user)}} Benutzer registriert.</p>
<input type="text" class="form-control" id="searchUser" oninput="searchTable('searchUser','userTable')" placeholder="Benutzer durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="userTable">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">E-Mail</th>
                <th scope="col">Mobil</th>
                <th scope="col">Schichten (Aktiv)</th>
                <th scope="col">Stunden (Aktiv)</th>
                <th scope="col">Registriert</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $u)
            <tr>
                <th scope="row">{{$u->id}}</th>
                <td>{{$u->firstname}} {{$u->surname}}</td>
                <td>{{$u->email}}</td>
                <td>{{$u->mobil==''?'k.A.':$u->mobil}}</td>
                <td>{{count($u->activeAssignments)}}</td>
                <td>{{$u->working}}</td>
                <td>{{$u->registriert}}</td>
                <td><a href="{{ route('users.view',$u->id)}}" type="button" class="btn btn-default" title='Profil anzeigen'><i class="fa fa-eye"></i> Profil Anzeigen</a></td>
                </td>
                <td><button type="button" data-toggle="modal" data-target="#changepw{{$u->id}}" class="btn btn-default" title='Passwort zurücksetzen'><i class="fa fa-lock"></i> Passwort Zurücksetzen</button></td>
                
                <div class="modal fade" id="changepw{{$u->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('users.password',$u->id)}}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title" id="myModalLabel">Passwort zurücksetzen</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Setze ein neues Passwort für <b>{{$u->firstname}} {{$u->surname}}</b>. Mindestens 6 Zeichen.</p>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="password">Passwort</label>
                                        <input id="password" type="password" placeholder="" class="form-control" name="password" required>
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="password-confirm">Passwort bestätigen</label>
                                        <input id="password-confirm" type="password" placeholder="" class="form-control" name="password_confirmation" required>  
                                    </div>
                                </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                                        <button type="submit" class="btn btn-primary btn-warning"><i class="fa fa-check"></i> Passwort zurücksetzen</button>
                                </div>
                            </div>
                            </form>
                                <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                
                {{--<div class="modal fade" id="changepw{{$u->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{route('users.password',$u->id)}}">
                        @csrf
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Passwort Zurücksetzen</h4>
                        </div>
                        <div class="modal-body">
                                Setze ein neues Passwort für <b>{{$u->firstname}} {{$u->surname}}</b>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="password">Passwort</label>
                                        <input id="password" type="password" placeholder="" class="form-control" name="password" required>
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="password-confirm">Passwort bestätigen</label>
                                        <input id="password-confirm" type="password" placeholder="" class="form-control" name="password_confirmation" required>  
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                                    <button type="submit" class="btn btn-primary btn-success"><i class="fa fa-check"></i>Passwort zurücksetzen</button>
                        </div>
                    </form>
                    </div>
                        
                         <!-- /.modal-content -->
                </div>
                    <!-- /.modal-dialog --> --}}
            </td>
            @endforeach
        </tbody>
</table>
</div>
@else
<div class="col-lg-12">
<p>Keine Benutzer gefunden.</p>
</div>
@endif
</div>
@endsection