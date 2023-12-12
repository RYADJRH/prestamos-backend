<?php

namespace App\Http\Requests\Payments;

use App\Rules\MinAndMaxAmountAdjustPayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AdjustPaymentRequest extends FormRequest
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

    public function attributes()
    {
        return [
            "amount_payment" => __('attributes.amount_payment')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "amount_payment" => ['required', 'numeric', new MinAndMaxAmountAdjustPayment]
        ];
    }
}
