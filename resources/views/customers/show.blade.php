@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="container">
	<h2>{{ $customer->name }}</h2>

	<dl>
		<dt>{{ __('Customer Name') }}</dt>
		<dd>{{ $customer->name }}</dd>

		<dt>{{ __('Address1') }}</dt>
		<dd>{{ $customer->address1 }}</dd>

		<dt>{{ __('Address2') }}</dt>
		<dd>{{ $customer->address2 }}</dd>

		<dt>{{ __('Phone') }}</dt>
		<dd>{{ $customer->tel }}</dd>

		<dt>{{ __('Fax') }}</dt>
		<dd>{{ $customer->fax }}</dd>

		<dt>{{ __('Payment Term') }}</dt>
		<dd>{{ $customer->payment_term }}</dd>
	</dl>
</div>
@endsection
