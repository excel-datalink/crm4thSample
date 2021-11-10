@extends('layouts.app')

@section('title', __("Estimates Management System"))

@section('content')
  <div class="jumbotron">
    <h1 class="display-5">{{ __("Estimate Management System with Excel Data Link") }}</h1>
    <p class="lead">{{ __("This is a web system that can link data with Excel.") }}</p>
    <hr class="my-4">
    <p>{{ __("This application is a sample. Let's register and try it out first.") }}</p>
    <div class="content">
      <div class="links">
        @if (Route::has('login'))
          @auth
          @else
            <a href="{{ route('login') }}" class="btn btn-success">{{ __("Login") }}</a>
            <a href="{{ route('register') }}" class="btn btn-primary">{{ __("Register Now") }}</a>
          @endauth
        @endif
      </div>
    </div>
  </div>
@endsection

