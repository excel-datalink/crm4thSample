<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreProfileRequest extends FormRequest
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
			'company_name' => 'required|max:100',
			'postal_code' => 'max:8',
			'address1' => 'max:80',
			'address2' => 'max:80',
			'tel' => 'max:13',
			'fax' => 'max:13',
        ];
    }

	public function messages()
	{
		return [
			'company_name.required' => __('profile.company_name.required'),
			'company_name.max' => __('profile.company_name.max'),
			'postalcode.max' => __('profile.postalcode.max'),
			'address1.max' => __('profile.address1.max'),
			'address2.max' => __('profile.address2.max'),
			'tel.max' => __('profile.tel.max'),
			'fax.max' => __('profile.fax.max')
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
