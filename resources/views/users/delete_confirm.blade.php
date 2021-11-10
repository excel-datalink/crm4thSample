@extends('layouts.app')

@section('title', __('Confirm unsubscribe'))

@section('content')
  <div class="container">
    <div class="card border-dark mb-3">
      <div class="card-header">
        <h3>{{ __('Confirm unsubscribe') }}</h3>
      </div>
      <div class="card-body">
        <p class="card-text">{{ __('If you unsubscribe, all registered data will be deleted.') }}</p>
        <p class="card-text">{{ __('Do you still want to unsubscribe?') }}</p>
      </div>
    </div>

    <div class="btn-group">
      {!! Form::open(['route'=>['users.destroy',Auth::user()->id],'method'=>'delete']) !!}
      {!! Form::submit(__('Unsubscribe'),['class'=>'btn btn-danger'])!!}
      {!! Form::close()!!}

      <div class="ml-3">
        <a href="/" class="btn btn-primary">{{ __('Cancel') }}</a>
      </div>
    </div>
  </div>
@endsection