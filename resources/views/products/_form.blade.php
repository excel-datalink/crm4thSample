		<div class="form-group">
			<label for="name">{{ __('Product Name') }}:</label>
			<input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="{{ __('Sample Products') }}" value="{{ old('name', $product->name) }}">
		</div>

		<div class="form-group">
			<label for="standard_price">{{ __('Standard Price') }}</label>
			<input type="number" class="form-control form-control-sm" id="standard_price" name="standard_price" value="{{ old('standard_price', $product->standard_price) }}">
		</div>
