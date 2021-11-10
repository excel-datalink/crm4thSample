@extends('layouts.app')

@section('title', __('Change Customer Information'))

@section('content')
<div class="container">

	<h2>{{ __('Change Customer Information') }}</h2>
	
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
	
	<form method="POST" action="{{ route('customers.update', $customer->id) }}">
		@csrf
		@method('patch')
		@include('customers._form')

		<input type="submit" class="btn btn-primary" value="{{ __('Update') }}">
	</form>
</div>
@endsection
