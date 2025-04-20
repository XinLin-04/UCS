<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UTAR Complainsion</title>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/registerValidation/passwordValidation.js') }}" defer></script>
    <script src="{{ asset('js/registerValidation/emailValidation.js') }}" defer></script>
    <script src="{{ asset('js/session.js') }}" defer></script>
    <script src="{{ asset('js/search.js') }}" defer></script>
    <script src="{{ asset('js/complaint.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mainPage.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Page-specific styles -->
    @yield('head')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div class="logo-container">
                    <a class="navbar-brand" href="{{ url('/') }}">

                        <div class="logo">
                            <img src="{{ asset('images/UCS_Logo.png') }}" alt="">
                        </div>
                        <div class="site-title">
                            {{ config('app.name', 'Laravel') }}
                            {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button> --}}
                        </div>
                    </a>
                </div>
                <!-- Show Search Icon -->
                <div class="searchContainer">
                    <form action="" class="search" id="search-bar">
                        <input type="search" placeholder="Enter to Search" class="search__input" id="search">
                        <div class="search__button" id="search-button">
                            <i class='bx bx-search'></i>
                            <i class='bx bx-x'></i>
                        </div>
                    </form>
                    <div id="search_result"></div> <!-- Moved outside the form -->
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Page-specific scripts -->
    @yield('scripts')
</body>

</html>