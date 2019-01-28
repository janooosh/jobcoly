<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Ausgabe (Gutscheine/T-Shirts)</h1>
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
    <div class="row">
        <div class="alert alert-warning">
            <span class='fa fa-warning'></span> Die Ausgabe ist ab dem 1. Personalmeeting möglich.</span>
        </div>
    </div>
<p>Es sind {{count($user)}} Benutzer registriert.</p>
<input type="text" class="form-control" id="searchUser" oninput="searchTable('searchUser','userTable')" placeholder="Benutzer durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="userTable">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Schichten</th>
                <th scope="col">Offene Bewerbungen</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $u)
            <tr>
                <th scope="row">{{$u->id}}</th>
                <td>{{$u->firstname}} {{$u->surname}}</td>
                <td>{{count($u->activeAssignments)}}</td>
                <td>{{count($u->activeApplications)}}</td>
                <td><a href="#" type="button" class="btn btn-default btn-success" title='Ausgabe'><i class="fa fa-plus"></i> Anzeigen</a></td>
            </td>
                

                
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