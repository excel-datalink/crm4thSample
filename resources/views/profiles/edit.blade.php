@extends('layouts.app')

@section('title', __('User Settings'))

@section('content')
<div class="container">
	<h2>{{ __('User Settings') }}</h2>

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('profiles.update', $profile->user_id) }}">
		@csrf
		@method('patch')
		@include('profiles._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Update') }}">
	</form>
</div>
@endsection

