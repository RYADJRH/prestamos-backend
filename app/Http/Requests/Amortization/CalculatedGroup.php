<?php

namespace App\Http\Requests\Amortization;

use App\Rules\MinAmountPaymentPeriod;
use Illuminate\Foundation\Http\FormRequest;

class CalculatedGroup extends FormRequest
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
            'amount_borrow'         => __('attributes.amount_borrow'),
            'amount_interest'       => __('attributes.amount_interest'),
            'amount_payment_period' => __('attributes.amount_payment_period'),
            'date_init_payment'     => __('attributes.date_init_payment'),
            'payment_every_n_weeks' => __('attributes.payment_every_n_weeks')
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
            'amount_borrow'         => ['required', 'numeric', 'min:1'],
            'amount_interest'       => ['required', 'numeric', 'min:0'],
            'amount_payment_period' => ['required', 'numeric', 'min:1', new MinAmountPaymentPeriod],
            'date_init_payment'     => ['required', 'date_format:Y-m-d'],
            'payment_every_n_weeks' => ['required', 'integer', 'min:1', 'max:4'],
        ];
    }
}
