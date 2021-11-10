		<div class="form-group">
			<label for="name">{{ __('Customer Name') }}:</label>
			<input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="{{ __('Sample Corporation') }}" value="{{ old('name', $customer->name) }}">
		</div>
		<div class="form-group">
			<label for="address1">{{ __('Address1') }}:</label>
			<input type="text" class="form-control form-control-sm" id="address1" name="address1" placeholder="{{ __('Sample Address1') }}" value="{{ old('address1', $customer->address1) }}">
		</div>
		<div class="form-group">
			<label for="address2">{{ __('Address2') }}:</label>
			<input type="text" class="form-control form-control-sm" id="address2" name="address2" placeholder="{{ __('Sample Address2') }}" value="{{ old('address2', $customer->address2) }}">
		</div>
		<div class="form-group">
			<label for="tel">{{ __('Phone') }}:</label>
			<input type="tel" class="form-control form-control-sm" id="tel" name="tel" placeholder="03-0000-0000" value="{{ old('tel', $customer->tel) }}">
		</div>
		<div class="form-group">
			<label for="fax">{{ __('Fax') }}:</label>
			<input type="tel" class="form-control form-control-sm" id="fax" name="fax" placeholder="03-0000-0000" value="{{ old('fax', $customer->fax) }}">
		</div>
		<div class="form-group">
			<label for="payment_term">{{ __('Payment Term') }}:</label>
			<input type="text" class="form-control form-control-sm" id="payment_term" name="payment_term" placeholder="{{ __('Sample Payment Term') }}" value="{{ old('payment_term', $customer->payment_term) }}">
		</div>

