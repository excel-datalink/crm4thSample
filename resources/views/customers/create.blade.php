@extends('layouts.app')

@section('title', __('Register A New Customer'))

@section('content')
<div class="container">

	<h2>{{ __('Register A New Customer') }}</h2>

	<!-- エラーメッセージ -->
	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
			</ul>
		</div>
	@endif
	
	<form method="POST" action="{{ route('customers.store') }}">
		@csrf
		@include('customers._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Registration') }}">
	</form>
</div>
@endsection
