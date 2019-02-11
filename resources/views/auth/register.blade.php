@extends('layouts.reg')

@section('content')

<div class="container">
        <div class="py-5 text-center">
          <img class="d-block mx-auto mb-4" src="{{ asset('img/vereinlogo.png') }}" alt="" width="72" height="auto">
          <h2>{{ __('Neu hier?') }}</h2>
          <p class="lead">Hier kannst du deinen Crew-Account für die OlyLust 2019 erstellen.<br/>Bei Fragen stehen wir dir jederzeit unter crew@olylust.de zur Verfügung.</p>
        </div>
  
        <div class="row">
          <div class="col-md-12 order-md-1">
            <form method="POST" action="{{ route('register') }}">
              @csrf
                <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="firstName">Vorname *</label>
                  <input id="firstname" type="text" placeholder="{{ __('Max') }}" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" name="firstname" value="{{ old('firstname') }}" required autofocus>
                  <div class="invalid-feedback">
                    {{ __('Erforderlich') }}
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="surname">Nachname *</label>
                    <input id="surname" type="text" placeholder="{{ __('Mustermann') }}" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autofocus>
                    <div class="invalid-feedback">
                        {{ __('Erforderlich') }}
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email">E-Mail *</label>
                    <input id="email" type="email" placeholder="{{ __('max@muster.de') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>   
              </div>
  
              <div class="row">
                  <div class="col-md-2 mb-3">
                      <label for="shirtCut">T-Shirt Schnitt *</label>
                      <select class="custom-select d-block w-100" id="shirtCut" name="shirtCut" required autofocus>
                          <option value="">Auswählen...</option>
                          <option value="M">Männlich</option>
                          <option value="W">Weiblich</option>
                      </select>
                  </div>
                  <div class="col-md-2 mb-3">
                      <label for="shirtSize">T-Shirt Größe *</label>
                      <select class="custom-select d-block w-100" id="shirtSize" name="shirtSize" required autofocus>
                          <option value="">Auswählen...</option>
                          <option value="XS">XS</option>
                          <option value="S">S</option>
                          <option value="M">M</option>
                          <option value="L">L</option>
                          <option value="XL">XL</option>
                          <option value="XX">XXL</option>
                      </select>
                  </div>
                    <div class="col-md-2 mb-3">
                        <label for="student">Bist du Student? *</label>
                        <select class="custom-select d-block w-100" id="student" name="student" required>
                            <option value="" selected disabled>Auswählen...</option>
                            <option value="1">Ja</option>
                            <option value="0">Nein</option>
                        </select>
                        <div class="invalid-feedback">
                                Bitte auswählen (Ja/Nein)
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="birthday">Geburtstag</label>
                        <input id="birthday" type="date" placeholder="" class="form-control" name="birthday" autofocus>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="phone">Mobil</label>
                        <input id="phone" type="tel" placeholder="" class="form-control" name="phone" autofocus>
                    </div>    
              </div>
              <div class="row" id="rowStudent">
                <div class="col-md-5" name="ifStudent"></div>
                <div class="col-md-5" name="ifStudent"></div>
                <div class="col-md-2" name="ifStudent"></div>
              </div>
              <hr/>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="olydorf">Wohnst du im OlyDorf? *</label>
                        <select class="custom-select d-block w-100" id="olydorf" name="olydorf" required>
                            <option value="" selected disabled>Auswählen...</option>
                            <option value="1">Ja</option>
                            <option value="0">Nein</option>
                        </select>
                        <div class="invalid-feedback">
                            Bitte auswählen (Ja/Nein)
                        </div>
                </div>
                
                <div class="col-md-2 mb-3" id="olycatDiv">
                </div>
                
                <div class="col-md-2 mb-3" id="olycatForDetails">
                </div>
                <div class="col-md-3 mb-3" id="vereinDiv">
                </div>
                <div class="col-md-2 mb-3" id="vereinInfoDiv">
                    <div id="vereinInfoDivButton"></div>  
                <!-- MODAL VEREIN INFO -->
                <div class="modal fade" id="VereinInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  
                  <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                
                            </div>
                            <div class="modal-body">
                                Du bist Mitglied im Verein der Studenten im Olympiazentrum e.V. wenn du einem Ausschuss als <b>ordentliches Mitglied</b> angehörst und/oder in einem <b>Betrieb</b> arbeitest, als <b>Dauerjobber</b> tätig bist und/oder im <b>Präsidium</b> sitzt. Als Bewohner des OlyDorfs (Studentenwohnheim) kannst du jederzeit kostenlos Mitglied werden, auch ohne ein Amt zu belegen. Mit deinem Einzug ins Wohnheim bist du jedoch nicht automatisch Mitglied. <br />
                                <br />Falls du dir unsicher über deinen Status bist, kontaktiere bitte Madeleine (<a href="mailto:madeleine.kimmig@oly-dorf.de">madeleine.kimmig@oly-dorf.de</a>).<br />Bitte gebe im Folgenden unbedingt deinen Ausschuss bzw. deinen Betrieb an, damit dir die <b>Pflichtstunden</b> angerechnet werden können.
                            </div>
                            <div class="modal-footer">
                              
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Verstanden</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    
                    <!-- /.modal-dialog -->
                </div>
                <!-- ENDE MODAL -->
              </div> 
            </div>
            <div class="row" id="rowIsVerein">
              <div class="col-md-3" id="betriebDiv">
              </div>
              <div class="col-md-3" id="bistduDiv">
              </div>
              <div class="col-md-3" id="ausschussDiv">
              </div>
              <div class="col-md-3" id="ausschusshinweisDiv">
              </div>
            </div>
            <div class="row" id="rowAdress">
                    <div class="col-md-3" name="ifnoOlydorf"></div>
                    <div class="col-md-1" name="ifnoOlydorf">
                        <div class="invalid-feedback">
                            {{ $errors->first('hausnummer') }}
                        </div>
                    </div>
                    <div class="col-md-2" name="ifnoOlydorf"></div>
                    <div class="col-md-3" name="ifnoOlydorf"></div>
                    <div class="col-md-3" name="ifnoOlydorf"></div>
            </div>
              <hr />
              <div class="row">
                  <div class="col-md-4 mb-3">
                      <label for="password">Passwort *</label>
                      <input id="password" type="password" placeholder="Mindestens 6 Zeichen" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                      <div class="invalid-feedback">
                          {{ $errors->first('password') }}
                      </div>
                  </div> 
                  <div class="col-md-4 mb-3">
                      <label for="password-confirm">Passwort bestätigen *</label>
                      <input id="password-confirm" type="password" placeholder="Mindestens 6 Zeichen" class="form-control" name="password_confirmation" required>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label for="gesundheitszeugnis">Hast du ein Gesundheitszeugnis?</label>
                      <select id="gesundheitszeugnis" type="selection" class="custom-select d-block w-100" name="gesundheitszeugnis">
                        <option value="">Auswählen...</option>
                        <option value="1">Ja</option>
                        <option value="0">Nein</option>
                      </select>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12 form-group">
                      <label for="aboutyou">Anmerkungen/Bemerkungen/Über Dich</label>
                      <textarea id="aboutyou" name="aboutyou" placeholder ="Erzähl uns etwas über dich! Warst du bereits auf früheren OlyLüsten?" class="form-control" rows="3" maxlength="255"></textarea>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                            <label for="confirmsec">
                            <input type="checkbox" id="confirmsec" name="confirmsec" value="1" required autofocus/>
                                Ich habe die <a href="{{route('datenschutz')}}" target="_blank"> Datenschutzerklärung </a> gelesen und verstanden.
                            </label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">
                    {{ __('Registrieren') }}
                </button><p><small>* = Pflichtangaben</small></p>
            </form>
          </div>
        </div>
  
        <footer class="my-5 pt-5 text-muted text-center text-small">
          <p class="mb-1">&copy; 2019 Studenten im Olympiazentrum e.V.</p>
          <ul class="list-inline">
            <li class="list-inline-item"><a href="{{route('impressum')}}">Impressum</a></li>
            <li class="list-inline-item"><a href="{{route('datenschutz')}}">Datenschutz</a></li>
          </ul>
        </footer>
      </div>
@endsection