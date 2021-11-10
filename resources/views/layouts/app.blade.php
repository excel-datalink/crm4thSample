<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', __('Estimates Management System')) . '| ' }}@yield('title')</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('js/this_app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', __('Estimates Management System')) }}
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        @if (Auth::check())
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link {{ Request::is('estimates', 'estimates/*') ? 'active' : '' }}"
                 href="{{ route('estimates.index') }}">{{ __('Estimates') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::is('customers', 'customers/*') ? 'active' : '' }}"
                 href="{{ route('customers.index') }}">{{ __('Customers') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::is('products', 'products/*') ? 'active' : '' }}"
                 href="{{ route('products.index') }}">{{ __('Products') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::is('profiles', 'profiles/*') ? 'active' : '' }}"
                 href="{{ route('profiles.edit', ['profile'=> Auth::user()->id]) }}">{{ __('Settings') }}</a>
            </li>
          </ul>
        @endif

      <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">

          <li class="nav-item dropdown" id="nav-lang">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              @if (array_key_exists(App::getLocale(), Config::get('languages')))
                {{ Config::get('languages')[App::getLocale()] }}
              @else
                {{ App::getLocale() }}
              @endif
              <span class="caret"></span></a>
            <ul class="dropdown-menu">
              @foreach ((array)Config::get('languages') as $lang => $language)
                @if ($lang != App::getLocale())
                  <li>
                    <a href="{{ route('lang.switch', $lang) }}">{{$language}}</a>
                  </li>
                @endif
              @endforeach
            </ul>
          </li>

          <!-- Authentication Links -->
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
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <a class="dropdown-item"
                   href="{{ route('users.delete_confirm') }}" >
                  {{ __('Unsubscribe') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    @if (Session::has('message'))
      <div class="alert alert-info">{{ session('message') }}</div>
    @endif
    @yield('content')
  </main>
</div>
</body>
</html>
