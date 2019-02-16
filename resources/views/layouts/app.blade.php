<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Crew OlyLust">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OlyLust Crew</title>

    
    <link href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{--<link href="{{ asset('template/vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet"> --}}
    
    <link href="{{ asset('template/dist/css/sb-admin-2.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('template/dist/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    
    {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

    {{--<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    --}}

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <script src="//code.jquery.com/jquery.js"></script>
    {{--<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    --}}

    </head>

    <body>
        <div id='app'></div>
        <div id='wrapper'>
                <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="{{route('home')}}">{{ config('app.name', 'Laravel') }}</a>
                        </div>
                        <!-- /.navbar-header -->
            
                        <ul class="nav navbar-top-links navbar-right">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                <li class="nav-item">
                                    @if (Route::has('register'))
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    @endif
                                </li>
                            @else 
                                <li class="nav-item dropdown">
        
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                                                {{ Auth::user()->firstname }} {{ Auth::user()->surname }} <i class="fa fa-caret-down"></i>
                                        </a>
        
                                    <ul class="dropdown-menu dropdown-user">
                                            <li><a href="{{ route('profil') }}"><i class="fa fa-user fa-fw"></i> Profil</a>
                                            </li>
                                            
                                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                            </li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                    </ul>
        
        
                                </li>
                            @endguest
        
                        </ul>
                        <!-- /.navbar-top-links -->
            
                        <div class="navbar-default sidebar" role="navigation">
                            <div class="sidebar-nav navbar-collapse">
                                <ul class="nav" id="side-menu">
                                   @if(Auth::user())
                                   @if(Auth::user()->email_verified_at) 
                                    <li>
                                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="/applications"><i class="fa fa-briefcase"></i> Bewerbungen</a>
                                    </li>
                                    <li>
                                        <a href="{{route('assignments.my')}}"><i class="fa fa-beer"></i> Schichten</a>
                                    </li>
                                    <li>
                                        <a href="{{route('rewards')}}"><i class="fa fa-trophy"></i> Rewards</a>
                                    </li>
                                    <li>
                                        <a href="{{route('faq')}}"><i class="fa fa-question-circle"></i> FAQs</a>
                                    </li>
                                    {{-- START MANAGER --}}
                                    @if(count(Auth::user()->manager_shifts)>0)
                                    <li>
                                        <a style="font-style:italic; color:black; text-align:center;">MANAGER</a>
                                    </li>
                                    <li>
                                        <a href="/applications/evaluate"><i class="fa fa-puzzle-piece"></i> Bewerbungen verwalten</a>
                                    </li>
                                    <li>
                                        <a href="/shifts"><i class="fa fa-clock-o fa-fw"></i>Schichten bearbeiten</a>
                                    </li>

                                    @endif
                                    {{-- ENDE MANAGER --}}

                                    {{-- START SUPERVISOR --}}
                                    @if(count(Auth::user()->supervisor_shifts)>0)
                                    <li>
                                        <a style="font-style:italic; color:black; text-align:center;">SUPERVISOR</a>
                                    </li>
                                    <li>
                                        <a href="{{route('supervisor')}}"><i class="fa fa-users"></i> Meine Teams</a>
                                    </li>
                                    @endif
                                    {{-- ENDE MANAGER --}}

                                    {{-- START ADMIN --}}
                                    @if(Auth::user()->is_admin=='1')
                                    
                                    <li>
                                        <a style="font-style:italic; color:black; text-align:center;">ADMINISTRATOR</a>
                                    </li>
                                    <li>
                                        <a href="{{route('shiftplan.index')}}"><i class="fa fa-calendar"></i> Schichtplan</a>
                                    </li>
                                    <li>
                                        <a href="{{route('transaction.browser')}}"><i class="fa fa-money"></i> Gutscheine</a>
                                    </li>
                                    <li>
                                        <a href="{{route('shifts.all')}}"><i class="fa fa-suitcase"></i> Schichten verwalten</a>
                                    </li>
                                    <li>
                                        <a href="/shiftgroups"><i class="fa fa-cloud"></i> Schichtgruppen verwalten</a>
                                    </li>
                                    <li>
                                        <a href="/jobs"><i class="fa fa-beer"></i> Jobs verwalten</a>
                                    </li>
                                    <li>
                                        <a href="{{route('users')}}"><i class="fa fa-user"></i> Benutzer verwalten</a>
                                    </li>

                                    @endif{{-- ENDE ADMIN --}}
                                    @endif
                                    
                                    
                                @else
                                    <li>
                                        <a href="{{ route('login') }}"><i class="fa fa-home"></i> Login</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('register') }}"><i class="fa fa-beer"></i> Registrieren</a>
                                    </li>
                                @endif
                                </ul>
                            </div>
                            <!-- /.sidebar-collapse -->
                        </div>
                        <!-- /.navbar-static-side -->
                 </nav>


        <div id="page-wrapper" style="min-height: 301px;">
            @yield('content')   
        </div>
                    

        

    <footer class="fixed-bottom">
    <div style="background-color:#f5f5f5; border: 1px solid #e3e3e3; text-align:center; color:#777;">
    &copy; 2018 Studenten im Olympiazentrum e.V. | <a href="{{route('impressum')}}" title="Impressum">Impressum</a> | <a href="{{route('datenschutz')}}">Datenschutz</a>
    <br /><small><i class="fa fa-heart"></i> <i class="fa fa-beer"></i> ~ <a href="https://github.com/janooosh/jobcoly" target="_blank">jobcly v.0.2b</a> | Letztes Update: 16.02.2019 04:23</small>
    </div>
    
    </footer> 
   
    </div> <!-- Ends wrapper -->


    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    
     <!-- Metis Menu Plugin JavaScript -->
     <script src="{{ asset('template/vendor/metisMenu/metisMenu.min.js') }}"></script>

     <!-- Custom Theme JavaScript -->
     <script src="{{ asset('template/dist/js/sb-admin-2.js') }}"></script>



</body>
</html>