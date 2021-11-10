@extends('layouts.app')

@section('title', __('Create Estimate'))

@section('content')
<div class="container">
	<h2>{{ __('Create Estimate') }}</h2>

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
	
	<form method="POST" action="{{ route('estimates.store') }}">
		@csrf
		@include('estimates._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Registration') }}">
	</form>

</div>
@endsection
