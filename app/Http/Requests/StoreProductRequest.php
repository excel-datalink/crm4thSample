<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'name' => 'required|max:80',
			'standard_price' => 'required|numeric|min:0|max:9999999999.999',
        ];
    }

	public function messages() {
		return [
			'name.required' => __('product.name.required'),
			'name.max' => __('product.name.max'),
            'standard_price.numeric' => __('product.standard_price.numeric'),
			'standard_price.required' => __('product.standard_price.required'),
			'standard_price.min' => __('product.standard_price.min'),
			'standard_price.max' => __('product.standard_price.max'),
		];
	}

	// バリデーションのエラーを返す
    protected function failedValidation(Validator $validator)
    {
        // API jwt-auth でのログインユーザーをチェック
        if (Auth::guard('api')->check() == true) {
            // バリデーションのエラーをJSONで返す
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(response()->json([
                'message' => __('Failed Validation.'),
                'errors' => $errors,
            ], 422, [], JSON_UNESCAPED_UNICODE));

        } else {
            // バリデーションのエラーを Web に返す
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
