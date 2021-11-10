@extends('layouts.app')

@section('title', __('Manage Product'))

@section('content')
<div class="container">
	<h2>{{ __('Manage Product') }}</h2>

	<div class="row float-right">
		<a href="{{ route('products.create') }}" class="btn btn-sm btn-primary mb-2">{{ __('Register A New Product') }}</a>
	</div>

	<div class="col-sm-5">
		<form class="form-inline" action="{{ route('products.index') }}" method="GET">
			<div class="form-group">
				<input type="text" name="search_word" value="{{ $search_word }}" class="form-control form-control-sm" placeholder="{{ __('Enter Your Search Terms Here') }}">
			</div>
			<input type="submit" name="submit_btn" value="{{ __('Search') }}" class="btn btn-primary btn-sm">

			<fieldset class="form-group">
				<dl>
					<dt>{{ __('Standard Price') }}</dt>
					<dd>
						<input type="number" name="search_min_standard_price" value="{{ $search_min_standard_price }}" class="form-control form-control-sm" placeholder="{{ __('Lower Limit') }}" step="0.001">
						～
						<input type="number" name="search_max_standard_price" value="{{ $search_max_standard_price }}" class="form-control form-control-sm" placeholder="{{ __('Upper Limit') }}" step="0.001">
					</dd>
				</dl>
			</fieldset>
		</form>
	</div>

	<table class="table table-sm">
		<tr>
			<th>{{ __('Product Name') }}</th>
			<th>{{ __('Standard Price') }}</th>
			<th></th>
			<th></th>
		</tr>
		@foreach($products as $product)
		<tr>
			<td><a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->name }}</a></td>
			<td>{{ $product->standard_price }}</td>
			<td><a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-info">{{ __('Editing') }}</a></td>
			<td>
				<form method="POST" action="{{ route('products.destroy', ['product' => $product->id]) }}">
					@csrf
					@method('delete')
					<input type="submit" value="{{ __('Deletion') }}" onClick="return confirm('{{ __("Do You Want To Delete?") }}')" class="btn btn-sm btn-danger">
				</form>
			</td>
		</tr>
		@endforeach
	</table>

	{{ $products->links() }}

</div>
@endsection
