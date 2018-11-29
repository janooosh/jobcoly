@extends('layouts.app')

@section('content')

<div class="row gap-20 filterer jrb-white">
    <div class="form-row">
        <div class="form-group col-md-3 ">
            <select id="inputJob" class="form-control">
                <option selected="selected">Job auswählen...</option>
                <option>Thekenkraft (TK)</option>
                <option>Thekenverantwortliche/r (TV)</option>
                <option>Aufbau(AuK)</option>
                <option>Abbau(AbK)</option>
                <option>Garderobenkraft (GK)</option>
                <option>Kassenkraft (KaK)</option>
                <option>Reinigungskraft (RK)</option>
                <option>Versorger (V)</option>
                <option>Abrechnung (AK)</option>
                <option>Springer (S)</option>
                <option>Technikhelfer (TH)</option>
                <option>Fotograf/in (F)</option>
                <option>Social Media Host (SH)</option>
                <option>Küchenkraft (KüK)</option>
                <option>Promo (P)</option>
            </select>
        </div>
        <div class="form-group col-md-3 ">
            <select id="inputDay" class="form-control">
                <option selected="selected">Tag auswählen...</option>
                <option>Vorbereitung</option>
                <option>Aufbau</option>
                <option>Donnerstag, 28.02. (WEIBERFASCHING)</option>
                <option>Freitag, 01.03. (DER STUDENTENFASCHING)</option>
                <option>Samstag, 02.03. (LEGENDÄRER SAMSTAGSFASCHING)</option>
                <option>Montag, 04.03. (ROSENMONTAG)</option>
                <option>Abbau</option>
            </select>
        </div>
        <div class="form-group col-md-2 ">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="exampleRadios" id="inputAWE" value="no">
                <label class="form-check-label" for="exampleRadios1">Nur AWE Schichten</label>
            </div>
        </div>
        <div class="form-group col-md-2 ">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="exampleRadios" id="inputAWE" value="no">
                <label class="form-check-label" for="exampleRadios1">Nur ohne GZ</label>
            </div>
        </div>
        <div class="col-md-2 ">
            <button type="submit" class="btn btn-primary">Filter Anwenden</button>
        </div>
    </div>

</div>

<div class="row">
   <div class="col-md-12">
        <h2>Donnerstag, 28.02.2018 <small>Weiberfasching</small></h2>
   </div> 
</div>
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading smallpad">
                <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                    <span class="fa fa-gears"></span></h5>
            </div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                        aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                    </div>
                </div>
                <div style="text-align:left; float:left;"><small>3 / 10 freie Schichten</small></div>
                <div style="text-align:right;"><small>Verschiedene Zeiten</small></div>
                <div id="demo" class="collapse">
                    Lorem ipsum dolor text....
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading smallpad">
                    <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                        <span class="fa fa-gears"></span></h5>
                </div>
                <div class="panel-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                        </div>
                    </div>
                    <div style="text-align:left; float:left;"><small>3 / 10 freie Schichten</small></div>
                    <div style="text-align:right;"><small>21:00h - 05:00h</small></div>
                    
                    <div id="demo" class="collapse">
                        Lorem ipsum dolor text....
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading smallpad">
                        <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                            <span class="fa fa-gears"></span></h5>
                    </div>
                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                            </div>
                        </div>
                        <small>3 / 10 freie Schichten</small>
                        <div id="demo" class="collapse">
                            Lorem ipsum dolor text....
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading smallpad">
                            <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                                <span class="fa fa-gears"></span></h5>
                        </div>
                        <div class="panel-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                    aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                </div>
                            </div>
                            <small>3 / 10 freie Schichten</small>
                            <div id="demo" class="collapse">
                                Lorem ipsum dolor text....
                            </div>
                        </div>
                    </div>
                </div>

</div>
<div class="row">
        <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading smallpad">
                        <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                            <span class="fa fa-gears"></span></h5>
                    </div>
                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                            </div>
                        </div>
                        <small>3 / 10 freie Schichten</small>
                        <div id="demo" class="collapse">
                            Lorem ipsum dolor text....
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading smallpad">
                            <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                                <span class="fa fa-gears"></span></h5>
                        </div>
                        <div class="panel-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                    aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                </div>
                            </div>
                            <small>3 / 10 freie Schichten</small>
                            <div id="demo" class="collapse">
                                Lorem ipsum dolor text....
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                        <div class="panel panel-info">
                            <div class="panel-heading smallpad">
                                <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                                    <span class="fa fa-gears"></span></h5>
                            </div>
                            <div class="panel-body">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                        aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                    </div>
                                </div>
                                <small>3 / 10 freie Schichten</small>
                                <div id="demo" class="collapse">
                                    Lorem ipsum dolor text....
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading smallpad">
                                    <h5 class="nomarg">Thekenkraft | TK <a data-toggle="collapse" data-target="#demo"><span class="glyphicon glyphicon-info-sign"></span></a>
                                        <span class="fa fa-gears"></span></h5>
                                </div>
                                <div class="panel-body">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                                            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                        </div>
                                    </div>
                                    <small>3 / 10 freie Schichten</small>
                                    <div id="demo" class="collapse">
                                        Lorem ipsum dolor text....
                                    </div>
                                </div>
                            </div>
                        </div>
</div>


@endsection