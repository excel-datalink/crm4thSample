<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class StoreEstimateRequest extends FormRequest
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
            'title' => 'required|max:80',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'effective_date' => 'required|date',
            'payment_term' => 'required|max:80',
            'customer_id' => 'required',
            'submitted_flag' => 'required',
            'details.*.product_name' => 'required_without:details.*.is_delete|max:100',
            'details.*.quantity' => 'required_without:details.*.is_delete|numeric|min:1|max:99999',
            'details.*.unit_price' => 'required_without:details.*.is_delete|numeric|min:-99999|max:99999',
            'details.*.price' => 'required_without:details.*.is_delete|numeric|min:-99999|max:99999',
        ];

        // POST時のバリデーション
        if ($method == "POST" ) {
            $rules['estimate_no'] = 'unique:estimates,estimate_no';
        };

        return $rules;
    }

	/**
	 * エラーメッセージ
	 *
	 * @var array
	 **/
	public function messages()
	{
		return [
			'title.required' => __('estimate.title.required'),
			'title.max' => __('estimate.title.max'),
            'estimate_no.unique' => __('estimate.estimate_no.unique'),
			'issue_date.required' => __('estimate.issue_date.required'),
			'issue_date.date' => __('estimate.issue_date.date'),
			'due_date.date' => __('estimate.due_date.date'),
			'effective_date.required' => __('estimate.effective_date.required'),
			'effective_date.date' => __('estimate.effective_date.date'),
			'payment_term.required' => __('estimate.payment_term.required'),
            'payment_term.max' => __('estimate.payment_term.max'),
			'customer_id.required' => __('estimate.customer_id.required'),
			'submitted_flag.required' => __('estimate.submitted_flag.required'),
			'details.*.product_name.required_without' => __('estimate.details.*.product_name.required_without'),
			'details.*.product_name.max' => __('estimate.details.*.product_name.max'),
			'details.*.quantity.required_without' => __('estimate.details.*.quantity.required_without'),
			'details.*.quantity.min' => __('estimate.details.*.quantity.min'),
			'details.*.quantity.max' => __('estimate.details.*.quantity.max'),
			'details.*.quantity.numeric' => __('estimate.details.*.quantity.numeric'),
			'details.*.unit_price.required_without' => __('estimate.details.*.unit_price.required_without'),
			'details.*.unit_price.min' => __('estimate.details.*.unit_price.min'),
			'details.*.unit_price.max' => __('estimate.details.*.unit_price.max'),
			'details.*.unit_price.numeric' => __('estimate.details.*.unit_price.numeric'),
			'details.*.price.required_without' => __('estimate.details.*.price.required_without'),
			'details.*.price.min' => __('estimate.details.*.price.min'),
			'details.*.price.max' => __('estimate.details.*.price.max'),
			'details.*.price.numeric' => __('estimate.details.*.price.numeric'),
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
