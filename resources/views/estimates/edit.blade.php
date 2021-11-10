@extends('layouts.app')

@section('title', __('Change Estimate'))

@section('content')
<div class="container">
	<h2>{{ __('Change Estimate') }}</h2>

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

	<form method="POST" action="{{ route('estimates.update', ['estimate' => $estimate->id]) }}">
		@csrf
		@method('patch')
		@include('estimates._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Update') }}">
	</form>
</div>
@endsection
