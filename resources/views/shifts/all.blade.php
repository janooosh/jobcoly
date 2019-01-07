<?php
use \App\Http\Controllers\ShiftsController;
?>
@extends('layouts.app')

@section('content')
<script src="{{ asset('js/evaluations.js')}}"></script>
<script src="{{ asset('js/jayfilter.js')}}"></script>
{{--<script>
$(document).ready( function () {
    $('#shiftTable').DataTable();
} );
</script> --}}
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Schichten</h1>
    </div>
</div>

<div class="row">
        <a href="{{route('shifts.create')}}" class="btn btn-default">Neue Schicht</a>
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

<div class="row">
@if(count($shifts)>0)
<div class="col-ld-10">

<p>Es gibt {{count($shifts)}} Schicht(en) im System.</p>
<input type="text" class="form-control" id="searchShifts" oninput="searchTable('searchShifts','shiftTable')" placeholder="Schichten durchsuchen...."/>
<br />
<table class="table table-hover table-bordered" id="shiftTable">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col" filter="elements">Gruppe</th>
                <th scope="col" filter="elements">Job</th>
                <th scope="col" filter="elements">Area</th>
                <th scope="col" filter="date">Datum</th>
                <th scope="col" filter="time">Start</th>
                <th scope="col" filter="time">Dauer</th>
                <th scope="col" filter="number">Frei</th>
                <th scope="col">Belegung</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
            <tr>
                <th scope="row">{{$shift->id}}</th>
                <td>{{$shift->shiftgroup['name']}}</td>
                <td>{{$shift->job['name']}}</td>
                <td>{{$shift->area==''?'-':$shift->area}}</td>
                <td>{{$shift->datum}}</td>
                <td>{{$shift->starts_at}}</td>
                <td>{{$shift->duration}}</td>
                <td>{{ShiftsController::hasFreeAssignments($shift->id)}}</td>
                <td>{{round(($shift->anzahl-ShiftsController::hasFreeAssignments($shift->id))/$shift->anzahl*100,1)}}%</td>
                <td><a href="{{ route('shifts.edit',$shift->id)}}" class="fa fa-pencil" title='Schicht bearbeiten'></a></td>
                </td>
                <td><a href="{{ route('shifts.show',$shift->id)}}" class="fa fa-eye" title='Schicht anzeigen'></a></td>
                </td>
            @endforeach
        </tbody>
</table>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="inhalt"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
              <div id="saver"></div>
            </div>
          </div>
        </div>
</div>

<script>
//Fügen IDs zu Spalten hinzu
initFilter('shiftTable');
</script>



<script>
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var filterID = button.data('filter') // Extract info from data-* attributes
  
  //Finde Filter
  var filter = findFilter(filterID);

  var container = buildFilter(filter);
  var saver = buildSaver(filter);
  
  var modal = $(this)
  modal.find('.modal-title').html('Filter')
  modal.find('.modal-body #inhalt').html(container)
  modal.find('.modal-footer #saver').html(saver)
})
</script>
</div>
@else
<div class="col-lg-12">
<p>Keine Schichten gefunden.</p>
</div>
@endif
</div>
@endsection