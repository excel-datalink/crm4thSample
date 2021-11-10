@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
	<h2>{{ $product->name }}</h2>

	<dl>
		<dt>{{ __('Product Name') }}</dt>
		<dd>{{ $product->name }}</dd>
		<dt>{{ __('Standard Price') }}</dt>
		<dd>{{ $product->standard_price }}</dd>
	</dl>
</div>
@endsection
