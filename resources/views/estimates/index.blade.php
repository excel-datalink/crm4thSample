@extends('layouts.app')

@section('title', __('Manage Estimates'))

@section('content')
<div class="container">
	<h2>{{ __('Manage Estimates') }}</h2>

	<div class="row float-right">
		<a href="{{ route('estimates.create') }}" class="btn btn-primary mb-2 btn-sm">{{ __('Register A New Estimate') }}</a>
	</div>

	<div class="col-sm-5">
		<form class="form-inline" action="{{ route('estimates.index') }}" method="GET">
			<div class="form-group">
				<input type="text" name="search_word" value="{{ $search_word }}" class="form-control form-control-sm" placeholder="{{ __('Enter Your Search Terms Here') }}">
			</div>
			<input type="submit" value="{{ __('Search') }}" class="btn btn-primary btn-sm">
		</form>
	</div>

	<table class="table table-sm">
		<tr>
			<th>{{ __('Id') }}</th>
			<th>{{ __('Customer Name') }}</th>
			<th>{{ __('Title') }}</th>
			<th>{{ __('Price') }}</th>
			<th>{{ __('Issue Date') }}</th>
			<th></th>
			<th></th>
		</tr>
		@foreach($estimates as $estimate)
		<tr>
			<td>{{ $estimate->id }}</td>
			<td><a href="{{ route('customers.show', ['customer' => $estimate->customer->id]) }}">{{ $estimate->customer_name }}</a></td>
			<td><a href="{{ route('estimates.show', ['estimate' => $estimate->id]) }}">{{ $estimate->title }}</a></td>
			<td>{{ $estimate->total_price }}</td>
			<td>{{ $estimate->issue_date->format('Y/m/d') }}</td>
			<td><a href="{{ route('estimates.edit', ['estimate' => $estimate->id]) }}" class="btn btn-info btn-sm">{{ __('Editing') }}</a></td>
			<td>
				<form method="POST" action="{{ route('estimates.destroy', ['estimate' => $estimate->id]) }}">
					@csrf
					@method('delete')
					<input type="submit" value="{{ __('Deletion') }}" onClick="return confirm('{{ __("Do You Want To Delete?") }}')" class="btn btn-danger btn-sm">
				</form>
			</td>
		</tr>
		@endforeach
	</table>

	{{ $estimates->links() }}

</div>
@endsection
