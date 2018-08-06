<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
          content="">
    <meta name="keywords"
          content="">
    <meta name="author" content="Olakunle">
    <title>{{ $setting->app_name }} | @yield('page-name')</title>
    <link rel="apple-touch-icon" href="{{ asset('/') }}uploads/{{ $setting->favicon }}">
    <link rel="shortcut icon" type="image/x-icon"
          href="{{ asset('/') }}uploads/{{ $setting->favicon }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
          rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/fonts/feather/style.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/') }}app-assets/fonts/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/extensions/pace.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/app.min.css">

    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/') }}app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/') }}app-assets/css/core/menu/menu-types/vertical-overlay-menu.min.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/custom.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <style>
        .main-menu ul {
            margin: 30px auto;
        }
    </style>
@stack('general-css')
<!-- END Custom CSS-->

</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns"
      class="vertical-layout vertical-menu 2-columns  fixed-navbar">
<!-- - var navbarShadow = true-->
<!-- navbar-fixed-top-->
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-light bg-gradient-x-grey-blue">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a href="#"
                                                                               class="nav-link nav-menu-main menu-toggle hidden-xs"><i
                                class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a href="{{ route('dashboards.index') }}" class="navbar-brand">
                        <img alt="app logo" src="{{ asset('/') }}uploads/{{ $setting->logo }}"
                             class="brand-logo" alt="{{ $setting->app_name }}" width="200">
                        <h5 class="brand-text">&nbsp;</h5>
                    </a>
                </li>
                <li class="nav-item hidden-md-up float-xs-right">
                    <a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i
                                class="fa fa-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">

                <ul class="nav navbar-nav float-xs-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                <span class="avatar avatar-online">
                  <img src="{{ asset('/') }}app-assets/images/no-image.png" alt="avatar"><i></i></span>
                            <span class="user-name">{{ $user->first_name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"><a href="#" class="dropdown-item"><i
                                        class="ft-user"></i> Edit Profile</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" class="form-inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="dropdown-item"><i class="ft-power"></i> Logout</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div data-scroll-to-active="true" class="main-menu menu-fixed menu-light menu-accordion menu-shadow">
    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class=" navigation-header">
                <span>Menu</span><i data-toggle="tooltip" data-placement="right" data-original-title="General"
                                    class=" ft-minus"></i>
            </li>
            @include('layouts._partials.admin-nav', ['links' => config('navigations.'.$user_type.'_menu')])
        </ul>
    </div>
</div>
<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-xs-12 mb-2">
                <h3 class="content-header-title mb-0">@yield('page-name')</h3>

            </div>
            <div class="content-header-right col-md-6 col-xs-12">
                <div role="group" aria-label="Button group with nested dropdown" class="btn-group float-md-right">
                    <div role="group" class="btn-group">
                        @yield('actions')
                    </div>

                </div>
            </div>
        </div>
        <div class="content-body">
            @include('layouts._partials.notify')
            @yield('content')
        </div>
    </div>
</div>

<footer class="footer navbar-fixed-bottom footer-dark navbar-border">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
      <span class="float-md-left d-xs-block d-md-inline-block" id="copyright">Copyright &copy; {{ date('Y') }} <a
                  href="{{ url('/') }}" class="text-bold-800 grey darken-2">{{ $setting->app_name }} </a>, All rights
        reserved. </span>
    </p>
</footer>

<div class="modal fade bs-example-modal-sm" id="loading-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ asset('project/loading.gif') }}" alt="Loading..." width="280">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- BEGIN VENDOR JS-->
<script src="{{ asset('/') }}app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN STACK JS-->
<script src="{{ asset('/') }}app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
<script src="{{ asset('/') }}app-assets/js/core/app.min.js" type="text/javascript"></script>
<!-- END STACK JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="{{ asset('/') }}app-assets/vendors/js/ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="{{ asset('/') }}project/js/essentials.js" type="text/javascript"></script>
@stack('general-js')
<!-- END PAGE LEVEL JS-->
<script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.js" type="text/javascript"></script>
<script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.date.js" type="text/javascript"></script>

</body>

</html>
