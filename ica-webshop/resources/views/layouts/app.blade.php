<!doctype html>
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
    <link href="{{ asset('css/myapp.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">{{ __('ICA-SHOP') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <div class="col-md-4">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <div class="nav-link">+36 12 345 6789</div>
                            </li>
                        </ul>
                    </div>

                    <!-- Center Of Navbar -->
                    <div class="col-md-4">
                        <ul class="navbar-nav">
                            <form id="search-form" action="{{ route('search') }}" method="GET" class="form mx-auto">
                                <div class="input-group">
                                    <input type="text" placeholder="Keresés..." id="search-text" class="form-control" name="search" value="@isset($search) {{ $search }} @endisset"/>
                                    <button class="btn btn-outline-primary" type="submit">🔍</button>
                                </div>
                            </form>
                        </ul>
                    </div>

                    <!-- Right Side Of Navbar -->
                    <div class="col-md-4">
                        <ul class="navbar-nav">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item ms-auto">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Bejelentkezés') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Regisztráció') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item xs-ms-auto">
                                    @if($authuser)
                                        <a class="nav-link" href="{{ route('favorites') }}">
                                            {{ __('Kedvencek') }}
                                        </a>
                                    @endif
                                </li>
                                <li class="nav-item">
                                    @if($authuser)
                                        <a class="nav-link" href="{{ route('cart.index') }}">
                                            {{ __('Kosár') }}
                                        </a>
                                    @endif 
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ __('Fiók') }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('users.show') }}">
                                            {{ __('Személyes adatok') }}
                                        </a>
                                        @if($authuser)
                                            <a class="dropdown-item" href="{{ route('orders.myorders') }}">
                                                {{ __('Megrendeléseim') }}
                                            </a>
                                        @endif
                                        @if($authadmin)
                                            <a class="dropdown-item" href="{{ route('orders.allorders') }}">
                                                {{ __('Megrendelések') }}
                                            </a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Kijelentkezés') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-white">
            <div class="col py-0 px-3 m-0" style="background-color: black;" id="footer-content">
                <div class="row border border-dark rounded-top">
                    <div class="col-4">
                        <div class="text-center py-3">
                            <a href="{{ route('aboutus') }}">{{ __('Rólunk') }}</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="py-3 text-center">© 2022 Copyright</div>
                    </div>
                    <div class="col-4 py-3">
                        <span>{{ __('Kapcsolatok') }}:</span> &nbsp;
                        <span>+36 12 345 6789</span> &nbsp;
                        <span>support@ica.hu</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @stack('product-scripts')
</body>
</html>
