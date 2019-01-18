@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>

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
    <div class="col-lg-8">
        <h1 class="page-header">T-Shirt</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <p>
            <b>Vielen Dank dass du an dieser Umfrage teilnimmst!</b>
            <br/>
            Du hast als Crew Mitglied die Möglichkeit, eines der begehrten Crew T-Shirts zu ergattern! 😃 Die Designs findest du unten.
        </p>
        <p>Das T-Shirt kannst du gegen 6 Gutscheine eintauschen. Abendschichten erhalten andernfalls ein Leih-Shirt gestellt. Um jedoch nicht zu viele Shirts zu bestellen, brauchen wir bereits deine Meinung ob du das T-Shirt behalten willst oder nicht.</p>
        <button type="button" class="btn btn-outline btn-info" data-toggle="modal" data-target="#sizes"><i class="fa fa-info-circle"></i> Infos zu den Größen</button>
        
        <hr/>
    </div>
</div>
<form method="POST" action="{{route('shirtSurvey.save')}}">
    @csrf 
<div class="row" >
    <div class="col-md-4" style="padding-bottom: 10px;">
        <label for="shirtCut">Schnitt *</label>
        <select class="form-control" name="shirtCut" id="shirtCut" required>
            <option value="{{$user->shirt_cut==''?'selected':''}}" disabled>Bitte auswählen...</option>
            <option value="M" {{$user->shirt_cut=='M' ? 'selected':''}}>Männlich</option>
            <option value="W" {{$user->shirt_cut=='W' ? 'selected':''}}>Weiblich</option>
        </select>
    </div>
    <div class="col-md-4" style="padding-bottom: 10px;">
        <label for="shirtSize">Größe *</label>
        <select class="form-control" name="shirtSize" id="shirtSize" required>
            <option value="" {{$user->shirt_cut=='' ? 'selected':''}} disabled>Bitte auswählen...</option>
            <option value="" {{$user->shirt_size=='xs' ? 'selected':''}} disabled>XS (nicht verfügbar)</option>
            <option value="S" {{$user->shirt_size=='S' ? 'selected':''}}>S</option>
            <option value="M" {{$user->shirt_size=='M' ? 'selected':''}}>M</option>
            <option value="L" {{$user->shirt_size=='L' ? 'selected':''}}>L</option>
            <option value="XL" {{$user->shirt_size=='XL' ? 'selected':''}}>XL</option>
            <option value="xx" {{$user->shirt_size=='xx' ? 'selected':''}}>XXL</option>
        </select>
    </div>
    <div class="col-md-4" style="padding-bottom: 10px;">
        <label for="shirtDes">Möchtest du dein T-Shirt behalten? *</label>
        <select class="form-control" name="shirtDes" id="shirtDes" required>
            <option value="" selected disabled>Bitte auswählen...</option>
            <option value="1">Ja</option>
            <option value="0">Nein</option> 
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <button type="submit" class="btn btn-outline btn-success"><i class="fa fa-save"></i> Speichern</button>
    </div>
</div>
</form>
<hr />

<div class="row">
    <div class="col-md-12">
        <p>Hier ist sie, die diesjährige <b>#Crew Kollektion</b>. Jeweils links die Vorderseite, Rechts die Rückseite.</p>
    </div>
</div>
<div class="row" style="padding-bottom:10px;">
    <div class="col-md-4">
        <h3>Herren</h3>
        <small>🔍 Klicken zum Vergrößern</small>
    </div>
    <div class="col-md-4">
        <a data-fancybox="gallery" href="{{asset('img/hv.png')}}"><img src="{{asset('img/hv.png')}}" width="200"></a>
    </div>
    <div class="col-md-4">
        <a data-fancybox="gallery" href="{{asset('img/hh.png')}}"><img src="{{asset('img/hh.png')}}" width="200"></a>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <h3>Damen</h3>
        <small>🔍 Klicken zum Vergrößern</small>
    </div>
    <div class="col-md-4">
        <a data-fancybox="gallery" href="{{asset('img/dv.png')}}"><img src="{{asset('img/dv.png')}}" width="200"></a>
    </div>
    <div class="col-md-4">
        <a data-fancybox="gallery" href="{{asset('img/dh.png')}}"><img src="{{asset('img/dh.png')}}" width="200"></a>
    </div>
</div>

                <!-- MODAL Größen -->
                <div class="modal fade" id="sizes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  
                  <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b>Größen</b>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                
                            </div>
                            <div class="modal-body">
                                <p>Zum Vergrößern bitte auf die Bilder klicken.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Herren</h4>
                                        <a data-fancybox="size" href="{{asset('img/herren-size.png')}}"><img src="{{asset('img/herren-size.png')}}" width="200"></a>
                                        <br />
                                        Passform: Normal
                                        <br />
                                        Größen: S, M, L, XL, XXL
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Damen</h4>
                                        <a data-fancybox="size2" href="{{asset('img/damen-size.png')}}"><img src="{{asset('img/damen-size.png')}}" width="200"></a>
                                        <br />
                                        Passform: Normal
                                        <br />
                                        Größen: S, M, L, XL, XXL
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    
                    <!-- /.modal-dialog -->
                </div>
                <!-- ENDE MODAL -->


@endsection