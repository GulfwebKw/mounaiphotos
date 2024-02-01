<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Gulf web">

    <!-- Page Title -->
    <title>@hasSection('title') @yield('title') - @endif @if(isset($title)){{ $title }} - @endif{{ \HackerESQ\Settings\Facades\Settings::get('site_title', 'Site title') }}</title>

    <!--favicon icon-->
    <link rel="icon" href="{{ asset('assets/img/fav.png') }}" type="image/png" sizes="16x16" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css" media="all">

    <!-- Add Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- font awesome -->
    <link href="{{ asset('assets/css/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min_rtl.css') }}">

    <!-- jQuery and JS bundle w/ Popper.js -->
    <script src="{{ asset('assets/js/jquery-3.5.1.slim.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min_rtl.js') }}"></script>

    <!-- menu source start here -->
    <link href="{{ asset('assets/menu/armobile_menu.css') }}" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="{{ asset('assets/menu/menu.js') }}"></script>

    <style>
        .is-invalid{
            border-width: 1px;
            border-color: red !important;
            border: solid;
            border-radius: 5px;
        }
    </style>
    @hasSection('style')
        @yield('style')
    @endif
    @livewireStyles
</head>
<body>

<main>
    <!-- Header -->
    <header>
        <section class="top_bar">
            <div class="container hide-mob">
                <div class="row">

                    <div class="col-4 col-lg-4">
                        @if(\HackerESQ\Settings\Facades\Settings::get('logo' , false))
                            <a href="{{ route('home') }}">
                                <img src="{{ asset(Str::replaceFirst('public/' , 'storage/' , \HackerESQ\Settings\Facades\Settings::get('logo'))) }}" class="logo" title="{{ \HackerESQ\Settings\Facades\Settings::get('site_title', 'Site title') }}"
                                     alt="{{ \HackerESQ\Settings\Facades\Settings::get('site_title', 'Site title') }} Logo"/></a>
                        @endif
                    </div>

                    <div class="col-8 col-lg-8">
                        <nav>
                            <ul>
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><a href="{{ route('gallery') }}">معرض الصور</a></li>
                                <li><a href="reservation.html">متابعة الحجوزات</a></li>
                                <li><a href="{{ route('contact-us') }}">اتصل بنا</a></li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>

            <div class="container hide-desk">
                <div class="row">

                    <div class="col-2 col-lg-2">
                        <!-- Responsive menu -->
                        <div class="hide-desk">
                            <a href="#" class="slide-menu-open"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></a>
                            <div class="side-menu-overlay"></div>
                            <div class="side-menu-wrapper">
                                <a href="#" class="menu-close">&times;</a>

                                <ul>
                                    <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                    <li><a href="{{ route('gallery') }}">معرض الصور</a></li>
                                    <li><a href="reservation.html">متابعة الحجوزات</a></li>
                                    <li><a href="{{ route('contact-us') }}">اتصل بنا</a></li>
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-10 col-lg-10">
                        @if(\HackerESQ\Settings\Facades\Settings::get('logo' , false))
                            <a href="{{ route('home') }}">
                                <img src="{{ asset(Str::replaceFirst('public/' , 'storage/' , \HackerESQ\Settings\Facades\Settings::get('logo'))) }}" class="logo" title="{{ \HackerESQ\Settings\Facades\Settings::get('site_title', 'Site title') }}"
                                     alt="{{ \HackerESQ\Settings\Facades\Settings::get('site_title', 'Site title') }} Logo"/></a>
                        @endif
                    </div>

                </div>
            </div>
            @hasSection('header')
                @yield('header')
            @endif
        </section>
    </header>
@hasSection('content')
    @yield('content')
@endif
@if( isset($slot))
    {{ $slot }}
@endif


    <!-- Footer -->
    <footer class="section-gap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 offset-lg-3 text-center">
                    <ul class="social">
                        @if(\HackerESQ\Settings\Facades\Settings::get('twitter'))
                            <li><a href="{{ \HackerESQ\Settings\Facades\Settings::get('twitter') }}"><i class="fa fa-twitter"></i></a></li>
                        @endif
                        @if(\HackerESQ\Settings\Facades\Settings::get('snapchat'))
                            <li><a href="{{ \HackerESQ\Settings\Facades\Settings::get('snapchat') }}"><i class="fa fa-snapchat"></i></a></li>
                        @endif
                        @if(\HackerESQ\Settings\Facades\Settings::get('whatsapp'))
                            <li><a href="https://wa.me/{{ str_replace([' ' , '-' , '_'] ,'' , \HackerESQ\Settings\Facades\Settings::get('whatsapp')) }}"><i class="fa fa-whatsapp"></i></a></li>
                        @endif
                        @if(\HackerESQ\Settings\Facades\Settings::get('instagram'))
                            <li><a href="{{ \HackerESQ\Settings\Facades\Settings::get('instagram') }}"><i class="fa fa-instagram"></i></a></li>
                        @endif
                        @if(\HackerESQ\Settings\Facades\Settings::get('tiktok'))
                            <li><a href="{{ \HackerESQ\Settings\Facades\Settings::get('tiktok') }}" style="display: flex;height: 100%;justify-content: center;align-items: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width: 20px;height: 20px;fill: white;">
                                        <path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if(\HackerESQ\Settings\Facades\Settings::get('facebook'))
                            <li><a href="{{ \HackerESQ\Settings\Facades\Settings::get('facebook') }}"><i class="fa fa-facebook"></i></a></li>
                        @endif
                    </ul>
                        <div class="clear30x"></div>

                    @if(\HackerESQ\Settings\Facades\Settings::get('email'))
                        <p><i class="fa fa-envelope fa-lg gray-txt"></i> <a href="mailto:{{ \HackerESQ\Settings\Facades\Settings::get('email') }}">{{ \HackerESQ\Settings\Facades\Settings::get('email') }}</a></p>
                    @endif
                    @if(\HackerESQ\Settings\Facades\Settings::get('telephone'))
                        <p><i class="fa fa-phone fa-lg gray-txt"></i> <a href="tel:{{ str_replace([' ' , '-' , '_'] ,'' , \HackerESQ\Settings\Facades\Settings::get('telephone')) }}" dir="ltr">{{ \HackerESQ\Settings\Facades\Settings::get('telephone') }}</a></p>
                    @endif

                        <p>Copyright {{ now()->year }} - All Rights Reserved - {{ \HackerESQ\Settings\Facades\Settings::get('site_title_en', 'Site title') }}</p>
                </div>
            </div>
        </div>
    </footer>

</main>

<!-- All JavaScript files
================================================== -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

@livewireScripts
</body>
</html>
