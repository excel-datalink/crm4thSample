@extends('layouts.app')

@section('title', __('Register A New Product'))

@section('content')
<div class="container">
	<h2>{{ __('Register A New Product') }}</h2>

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
	
	<form method="POST" action="{{ route('products.store') }}">
		@csrf
		@include('products._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Registration') }}">
	</form>
</div>
@endsection
