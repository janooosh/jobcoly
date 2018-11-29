@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Jobs</h1>
    </div>
</div>
<div class="row">
        <a href="{{route('jobs.create')}}" class="btn btn-default">Neuer Job</a>
</div>

{{-- Message --}}
@if($message = Session::get('success')) 
<div class="row">
    <div class="alert alert-success">
        {{$message}}
    </div>
</div>
@endif

<div class="row">
@if(count($jobs)>0)
<div class="col-ld-10">

<p>Es sind {{count($jobs)}} aktive Jobs registriert.</p>
<table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Abkürzung</th>
                <th scope="col">Beschreibung</th>
                <th scope="col">Gesundheitszeugnis?</th>
                <th scope="col">Schichten</th>
                <th scope="col">Arbeiter</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $job)
            <tr>
                <th scope="row">{{$job->id}}</th>
                <td>{{$job->name}}</td>
                <td>{{$job->short}}</td>
                <td>{{$job->description == "" ? '-' : $job->description }}</td>
                <td>
                    @if($job->gesundheitszeugnis=="1")
                        Ja
                    @elseif($job->gesundheitszeugnis=="0")
                    Nein
                    @else
                        k.A.
                    @endif
                </td>
                <td>{{count($job->shifts)}}</td>
                <td>{{$job->actives}}</td>
                <td><a href="{{ route('jobs.edit',$job->id)}}" class="fa fa-pencil" title="Job bearbeiten"></a></td>
                {{--<td><a class="fa fa-trash-o" href="{{ route('jobs.destroy',$job->id)}}"></a></td>--}}
            @endforeach
        </tbody>
</table>
</div>
@else
<div class="col-lg-12">
<p>Keine Jobs gefunden.</p>
</div>
@endif
</div>
@endsection