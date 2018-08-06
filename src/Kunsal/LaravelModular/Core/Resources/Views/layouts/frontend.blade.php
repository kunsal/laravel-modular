<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $setting->app_name }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/') }}uploads/{{ $setting->favicon }}">
    <meta name="description" content="@yield('meta-description')"><!-- Add your Company short description -->
    <meta name="keywords" content="@yield('meta-keywords')"><!-- Add some Keywords based on your Company and your business and separate them by comma -->
    <meta name="author" content="Olakunle Salami, Okey Nwabufoh">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=no">

    <link href="https://fonts.googleapis.com/css?family=Scada:400,700|Open+Sans:400,300,700" rel="stylesheet" type="text/css">
    <link id="main-style-file-css" rel="stylesheet" href="{{ frontend() }}/assets/css/style.css"/>
    <link id="main-style-file-css" rel="stylesheet" href="{{ frontend() }}/assets/css/custom.css"/>
    <style>

    </style>
    @stack('page-css')
</head>
<body class="@yield('body-class')">
<header id="main-header">
    <div id="header-top">
        <div class="header-top-content container">

                {{--<ul id="language-switcher" class="list-inline">
                    <li><a href="{{ $setting->facebook }}" class="fa fa-facebook"></a></li>
                    <li><a href="{{ $setting->twitter }}" class="fa fa-twitter"></a></li>
                    <li><a href="{{ $setting->skype }}" class="fa fa-skype"></a></li>
                    <li><a href="{{ $setting->google_plus }}" class="fa fa-google-plus"></a></li>
                    <li><a href="{{ $setting->youtube }}" class="fa fa-youtube"></a></li>
                </ul>
                --}}
            <!-- Login Links -->
            <ul id="login-boxes" class="list-inline">

                @if(customer_logged_in() || developer_logged_in() || support_logged_in())
                    @if(customer_logged_in())
                        <li><a href="{{ route('customer.profile') }}">Hi, {{ customer()->name }}</a></li>
                        <li>
                            <a href="{{ route('customer.logout') }}" role="button">
                                <i class="fa fa-logout"></i> Logout
                            </a>
                        </li>
                    @endif
                    @if(developer_logged_in())
                        <li><a href="{{ route('developer.profile') }}">Hi, {{ developer()->company_name }}</a></li>
                            <li>
                                <a href="{{ route('developers.logout') }}" role="button">
                                    <i class="fa fa-logout"></i> Logout
                                </a>
                            </li>
                    @endif
                    @if(support_logged_in())
                        <li><a href="{{ route('support.profile') }}">Hi, {{ support()->company_name }}</a></li>
                        <li>
                            <a href="{{ route('supports.logout') }}" role="button">
                                <i class="fa fa-logout"></i> Logout
                            </a>
                        </li>
                    @endif
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" role="button" id="login-menu" data-toggle="dropdown">Login As</a>
                        <ul class="dropdown-menu" aria-labelledby="login-menu">
                            <li><a href="{{ route('customers.login.page') }}" class="dropdown-item"><i class="ft-user"></i> Individual</a></li>
                            <li><a href="{{ route('developers.login.page') }}" class="dropdown-item"><i class="ft-mail"></i> Builder/Developer</a></li>
                            <li><a href="{{ route('supports.login.page') }}" class="dropdown-item"><i class="ft-mail"></i> Support Services</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" role="button" id="login-menu" data-toggle="dropdown">Register As</a>
                        <ul class="dropdown-menu" aria-labelledby="login-menu">
                            <li><a href="{{ route('customers.registration.page') }}" class="dropdown-item"><i class="ft-user"></i> Individual</a></li>
                            <li><a href="{{ route('developer.registration') }}" class="dropdown-item"><i class="ft-mail"></i> Builder/Developer</a></li>
                            <li><a href="{{ route('support.registration') }}" class="dropdown-item"><i class="ft-mail"></i> Support Services</a></li>
                        </ul>
                    </li>
                @endif
                    <li><a href="{{ $setting->facebook }}" class="fa fa-facebook"> Facebook</a></li>
                    <li><a href="{{ $setting->twitter }}" class="fa fa-twitter"> Twitter</a></li>
                    <li><a href="{{ $setting->google_plus }}" class="fa fa-google-plus"> Google+</a></li>
                    <li><a href="{{ $setting->youtube }}" class="fa fa-youtube"> Youtube</a></li>
            </ul>
            <!-- End of Login Links -->
        </div>
    </div>
    <div class="main-header-cont container">
        <!-- Top Logo -->
        <div class="logo-main-box col-xs-6 col-sm-4 col-md-3">
            <img src="{{ asset('uploads/'.$setting->logo) }}" alt="RealEstateMarketplace">

        </div>
        <!-- End of Top Logo -->
        <!-- Main Menu -->
        <div class="menu-container col-xs-6 col-sm-8 col-md-9">
            <!-- Main Menu -->
            <nav id="main-menu" class="hidden-xs hidden-sm">
                <ul class="main-menu list-inline">
                    @include('layouts._partials.navigation', ['pages' => $pages])

                </ul>
            </nav>
            <!-- END of Main Menu -->

            <div id="main-menu-handle" class="hidden-md hidden-lg"><i class="fa fa-bars"></i></div><!-- Mobile Menu handle -->
            <a id="submit-property-link" class="btn" href="{!! route('search.title') !!}">
                <span>Search Title</span>
            </a>
        </div>
        <!-- End of Main Menu -->
    </div>
    <div id="mobile-menu-container" class="hidden-md hidden-lg"></div>
</header>

@yield('content')

<!-- JS Include Section -->
<script type="text/javascript" src="{{ frontend() }}/assets/js/jquery-1.11.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ frontend() }}/assets/js/helper.js"></script>
<script type="text/javascript" src="{{ frontend() }}/assets/js/select2.min.js"></script>
<script type="text/javascript" src="{{ frontend() }}/assets/js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="{{ frontend() }}/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyAVwe5cOczSy3FKN4hrGxSDm3Xdxi_C9f0"></script>
<script type="text/javascript" src="{{ frontend() }}/assets/js/template.js"></script>
<!-- End of JS Include Section -->
@stack('page-js')

</body>


</html>