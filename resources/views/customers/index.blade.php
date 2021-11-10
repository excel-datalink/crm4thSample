@extends('layouts.app')

@section('title', __('Manage Customer Information'))

@section('content')
<div class="container">
	<h2>{{ __('Manage Customer Information') }}</h2>

	<div class="row float-right">
		<a class="btn btn-primary btn-sm mb-2" href="{{ url('/customers/create') }}">{{ __('Register A New Customer') }}</a>
	</div>

	<div class="col-sm-5">
		<form class="form-inline" action="{{ route('customers.index') }}" method="GET">
			<div class="form-group">
				<input type="text" name="search_word" value="{{ $search_word }}" class="form-control form-control-sm" placeholder="{{ __('Enter Your Search Terms Here') }}">
			</div>
			<input type="submit" value="{{ __('Search') }}" class="btn btn-primary btn-sm">
		</form>
	</div>

	<table class="table table-sm">
		<tr>
			<th>{{ __('Customer Name') }}</th>
			<th>{{ __('Address1') }}</th>
			<th>{{ __('Phone') }}</th>
			<th>{{ __('Fax') }}</th>
			<th></th>
			<th></th>
		</tr>

		@foreach($customers as $customer)
		<tr>
			<td><a href="{{ route('customers.show', ['customer' => $customer->id]) }}">{{ $customer->name }}</a></td>
			<td>{{ $customer->address1 }}</td>
			<td>{{ $customer->tel }}</td>
			<td>{{ $customer->fax }}</td>
			<td><a href="{{ route('customers.edit', ['customer' => $customer->id]) }}" class="btn btn-sm btn-info">{{ __('Editing') }}</a></td>
			<td>
				<form method="POST" action="{{ route('customers.destroy', ['customer' => $customer->id]) }}">
					@csrf
					@method('delete')
					<input type="submit" value="{{ __('Deletion') }}"onclick="return confirm('{{ __("Do You Want To Delete?") }}')" class="btn btn-sm btn-danger" />
				</form>
			</td>
		</tr>
		@endforeach
	</table>

	{{ $customers->links() }}
</div>
@endsection
