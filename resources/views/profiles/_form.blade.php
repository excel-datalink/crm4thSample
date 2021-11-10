<div class="form-group">
	<label for="company_name">{{ __('Company Name') }}</label>
	<input type="text" class="form-control form-control-sm" id="company_name" name="company_name" value="{{ old('company_name', $profile->company_name) }}">
</div>

<div class="form-group">
	<label for="postal_code">{{ __('Postal Code') }}</label>
	<input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
</div>

<div class="form-group">
	<label for="address1">{{ __('Address1') }}</label>
	<input type="text" class="form-control form-control-sm" id="address1" name="address1" value="{{ old('address1', $profile->address1) }}">
</div>

<div class="form-group">
	<label for="address2">{{ __('Address2') }}</label>
	<input type="text" class="form-control form-control-sm" id="address2" name="address2" value="{{ old('address2', $profile->address2) }}">
</div>

<div class="form-group">
	<label for="tel">{{ __('Phone') }}</label>
	<input type="tel" class="form-control form-control-sm" id="tel" name="tel" value="{{ old('tel', $profile->tel) }}">
</div>

<div class="form-group">
	<label for="fax">{{ __('Fax') }}</label>
	<input type="tel" class="form-control form-control-sm" id="fax" name="fax" value="{{ old('fax', $profile->fax) }}">
</div>

