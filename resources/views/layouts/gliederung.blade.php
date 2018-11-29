@extends('layouts.app')

@section('gliederung')
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
                                <a href="/manage/applications"><i class="fa fa-clock-o fa-fw"></i>Bewerbungen verwalten</a>

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
         


@endsection