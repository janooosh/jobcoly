<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Crew OlyLust">
    <meta name="author" content="Jan Hähl">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OlyLust Crew') }}</title>

    <!-- START TEMPLATE IMPORTS -->

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="{{ asset('template/vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('template/dist/css/sb-admin-2.css') }}" rel="stylesheet">
    <!-- Data Table -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

    <!-- Custom Fonts -->
    <link href="{{ asset('template/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Data Table -->
  
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- END TEMPLATE IMPORTS -->

    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}


</head>
<body>
    <div id='app'></div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">{{ config('app.name', 'Laravel') }}</a>
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
                                    <li class="divider"></li>
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
                            <li>
                                <a href="{{ route('home') }}"><i class="fa fa-home fa-fw"></i>Dashboard</a>
                            </li>
                            <li>
                                <a href="/applications"><i class="fa fa-briefcase fa-fw"></i>Bewerbungen</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-clock-o fa-fw"></i>Meine Schichten</a>
                            </li>
                            <li>
                                <a href="/shifts"><i class="fa fa-clock-o fa-fw"></i>Schichten bearbeiten</a>
                            </li>
                            <li>
                                <a href="/jobs"><i class="fa fa-beer fa-fw"></i>Jobs</a>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-trophy fa-fw"></i>Bestätigungen</a>
                            </li>
                            <li>
                                <a href="/shiftgroups"><i class="fa fa-clock-o fa-fw"></i>Schichtgruppen</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
         </nav>
         
        <div id="page-wrapper" style="min-height: 301px;">
        @yield('content')
        </div>
        <footer>
            
        </footer>
    </div>

    <!-- TEMPLATE JSCRIPTS -->
        <!-- jQuery -->

        <!-- Jquery for autocomplete -->
        
        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ asset('template/vendor/metisMenu/metisMenu.min.js') }}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{ asset('template/dist/js/sb-admin-2.js') }}"></script>
    <!-- ENDE TEMPLATE JSCRIPTS -->
</body>
</html>
