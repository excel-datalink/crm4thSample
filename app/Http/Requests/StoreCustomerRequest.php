<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreCustomerRequest extends FormRequest
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
        $method = $this->getMethod();

        return self::getRules($method);
    }

    public static function getRules($method)
    {
        // 共通バリデーション
        $rules = [
            'name' => 'required|max:80',
            'address1' => 'max:80',
            'address2' => 'max:80',
            'tel' => 'max:13',
            'fax' => 'max:13',
            'payment_term' => 'required|max:30',        ];

        // POST時のバリデーション
        if ($method == "POST" ) {
            $rules['name'] = 'required|unique:customers,name|max:80';
        };

        return $rules;
    }

	public function messages() {
		return [
			'name.required' => __('customer.name.required'),
            'name.unique' => __('customer.name.unique'),
			'name.max' => __('customer.name.max'),
			'address1.max' => __('customer.address1.max'),
			'address2.max' => __('customer.address2.max'),
			'tel.max' => __('customer.tel.max'),
			'fax.max' => __('customer.fax.max'),
			'payment_term.required' => __('customer.payment_term.required'),
            'payment_term.max' => __('customer.payment_term.max'),
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

    // バリデーション実行前に呼び出されるメソッド
    public function withValidator(Validator $validator) {
        $validator->after(function ($validator) {
            //
        });
    }
}
