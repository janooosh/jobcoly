@extends('layouts.app')

@section('content')

<script src="{{ asset('js/evaluations.js')}}"></script>
{{-- Headline --}}
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Bewerbungen verwalten</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
<p>Du kannst jede Bewerbung auswählen um die Details zu sehen. Bitte verzögere deine Entscheidung nicht unnötig :)</p>
    </div>
</div>

{{-- Messages --}}
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

@if($applications<1)
<p>
    <div class="alert alert-info">
        Du hast keine offenen Bewerbungen, um die du dich kümmern musst. Zeit für <i class="fa fa-beer"></i>
    </div></p>

@else

{{-- Offene Bewerbungen --}}
@if(count($actives)>0 && $status!='active')
<a href="active" class="btn btn-default" >{{count($actives)}} Offene Bewerbung(en)</a>
@endif

{{-- Zugelassene Bewerbungen --}}
@if(count($accepteds)>0 && $status!='accepted')
<a href="accepted" class="btn btn-default" >{{count($accepteds)}} Akzeptierte Bewerbung(en)</a>
@endif

{{-- Abgelehnte Bewerbungen --}}
@if(count($denides)>0 && $status!='rejected')
<a href="rejected" class="btn btn-default" >{{count($denides)}} Abgelehnte Bewerbung(en)</a>
@endif

{{-- Zurückgezogene Bewerbungen --}}
@if(count($cancelleds)>0 && $status!='cancelled')
<a href="cancelled" class="btn btn-default" >{{count($cancelleds)}} Zurückgezogene Bewerbungen</a>
@endif

{{-- Sonderstatus Bewerbungen --}}
@if(count($others)>0 && $status!='others')
<a href="others" class="btn btn-default" >{{count($others)}} Bewerbung(en) mit Sonderstatus</a>
@endif

{{-- Aktive Bewerbungen --}}
@if(count($actives)>0 && $status=='active') 
<h4>{{count($actives)}} Aktive Bewerbung(en)</h4>
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeApplications')" placeholder="Suche nach Bewerbern, Jobs, Gruppen..."/>
<br /> 
<table class="table table-hover table-bordered" id="activeApplications">
        <thead>
            <tr>
                <th>Id</th>
                <th>Bewerber/in</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Beworben</th>
                <th>Bearbeiten</th>
            </tr>
        </thead>
        <tbody>
        @foreach($actives as $active) 
            <tr>
                <td>{{$active->id}}</td>
                <td>{{$active->applicant->firstname.' '.$active->applicant->surnamed}}</td>
                <td>{{$active->shift->job->short}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                {{--<td>{{$active->expirationString}}</td>--}}
                {{--<td id="timer{{$active->id}}"></td>--}}
                <td>{{$active->beworben_am}}</td>
                <td>
                    <a href="/applications/evaluate/view/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-rocket"></i> Bearbeiten</a>
                </td>
            </tr>
            {{--<script>
                jayCounter("{{$active->expiration}}", "timer{{$active->id}}");
            </script>--}}
        @endforeach
        </tbody>    
</table>

{{-- Akzeptierte Bewerbungen --}}
@elseif(count($accepteds)>0 && $status=='accepted') 
<h4>{{count($accepteds)}} Akzeptierte Bewerbung(en)</h4>
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeApplications')" placeholder="Suche nach Bewerbern, Jobs, Gruppen..."/>
<br /> 
<table class="table table-hover table-bordered" id="activeApplications">
        <thead>
            <tr>
                <th>Id</th>
                <th>Bewerber/in</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Ansehen</th>
            </tr>
        </thead>
        <tbody>
        @foreach($accepteds as $active) 
            <tr>
                <td>{{$active->id}}</td>
                <td>{{$active->applicant->firstname.' '.$active->applicant->lastname}}</td>
                <td>{{$active->shift->job->short}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                {{--<td>{{$active->expirationString}}</td>--}}
                <td>
                    <a href="/applications/evaluate/view/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Ansehen</a>
                </td>
            </tr>
        @endforeach
        </tbody>    
</table>

{{-- Abgelehnte Bewerbungen --}}
@elseif(count($denides)>0 && $status=='rejected') 
<h4>{{count($denides)}} Abgelehnte Bewerbung(en)</h4>
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeApplications')" placeholder="Suche nach Bewerbern, Jobs, Gruppen..."/>
<br /> 
<table class="table table-hover table-bordered" id="activeApplications">
        <thead>
            <tr>
                <th>Id</th>
                <th>Bewerber/in</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Ansehen</th>
            </tr>
        </thead>
        <tbody>
        @foreach($denides as $active) 
            <tr>
                <td>{{$active->id}}</td>
                <td>{{$active->applicant->firstname.' '.$active->applicant->lastname}}</td>
                <td>{{$active->shift->job->short}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                {{--<td>{{$active->expirationString}}</td>--}}
                <td>
                    <a href="/applications/evaluate/view/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Ansehen</a>
                </td>
            </tr>
        @endforeach
        </tbody>    
</table>

{{-- Zurückgezogene Bewerbungen --}}
@elseif(count($cancelleds)>0 && $status=='cancelled') 
<h4>{{count($cancelleds)}} Zurückgezogene Bewerbung(en)</h4>
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeApplications')" placeholder="Suche nach Bewerbern, Jobs, Gruppen..."/>
<br /> 
<table class="table table-hover table-bordered" id="activeApplications">
        <thead>
            <tr>
                <th>Id</th>
                <th>Bewerber/in</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Ansehen</th>
            </tr>
        </thead>
        <tbody>
        @foreach($cancelleds as $active) 
            <tr>
                <td>{{$active->id}}</td>
                <td>{{$active->applicant->firstname.' '.$active->applicant->lastname}}</td>
                <td>{{$active->shift->job->short}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                {{--<td>{{$active->expirationString}}</td>--}}
                <td>
                    <a href="/applications/evaluate/view/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Ansehen</a>
                </td>
            </tr>
        @endforeach
        </tbody>    
</table>

{{-- Sonstige Bewerbungen --}}
@elseif(count($others)>0 && $status=='others') 
<h4>{{count($others)}} Sonstige (Nicht-Zugelassene) Bewerbung(en)</h4>
<input type="text" class="form-control" id="searchactives" oninput="searchTable('searchactives','activeApplications')" placeholder="Suche nach Bewerbern, Jobs, Gruppen..."/>
<br /> 
<table class="table table-hover table-bordered" id="activeApplications">
        <thead>
            <tr>
                <th>Id</th>
                <th>Status</th>
                <th>Bewerber/in</th>
                <th>Job</th>
                <th>Gruppe</th>
                <th>Area</th>
                <th>Ansehen</th>
            </tr>
        </thead>
        <tbody>
        @foreach($others as $active) 
            <tr>
                <td>{{$active->id}}</td>
                <th><b>{{$active->status}}</b></th>
                <td>{{$active->applicant->firstname.' '.$active->applicant->lastname}}</td>
                <td>{{$active->shift->job->short}}</td>
                <td>{{$active->shift->shiftgroup->name}}</td>
                <td>{{$active->shift->area==''?'-':$active->shift->area}}</td>
                {{--<td>{{$active->expirationString}}</td>--}}
                <td>
                    <a href="/applications/evaluate/view/{{$active->id}}" type="button" class="btn btn-default"><i class="fa fa-eye"></i> Ansehen</a>
                </td>
            </tr>
        @endforeach
        </tbody>    
</table>

@else
<p>Keine Bewerbung gefunden.</p>

@endif {{-- Ende  --}}


@endif {{-- Ende "keine Bewerbungen zugewiesen" --}}


@endsection
