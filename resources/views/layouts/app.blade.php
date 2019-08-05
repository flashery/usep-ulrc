<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.Laravel = <?php
                            echo json_encode(
                                [
                                    'csrfToken' => csrf_token(),
                                    'lang' => \App::getLocale(),
                                    'user' => [
                                        'authenticated' => auth()->check(),
                                        'id' => auth()->check() ? auth()->user()->id : null,
                                        'name' => auth()->check() ? auth()->user()->name : null,
                                        'position_id' =>  auth()->check() ? auth()->user()->permission_id : null,
                                        'image' =>  auth()->check() ? auth()->user()->image : null,
                                    ]
                                ]
                            );
                            ?>
    </script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-primary  shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/usep-logo.png" class="logo" />
                    <div class="header-title">
                        <h1>University of Southeastern Philippines</h1>
                        <h3>University Resource Learning Center</h3>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/about">{{ __('About') }}</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="/about">{{ __('Contact') }}</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="/search">{{ __('Search') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <!-- @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif -->
                        @else
                        <profile-avatar></profile-avatar>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    @include('partials.footer')

</body>

</html>