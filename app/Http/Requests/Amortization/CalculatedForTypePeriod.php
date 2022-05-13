<?php

namespace App\Http\Requests\Amortization;

use App\Enum\TypePeriodAmortizationEnum;
use App\Rules\MinAmountPaymentPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CalculatedForTypePeriod extends FormRequest
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
            'type_period'           => __('attributes.type_period'),
            'payment_every_n'       => __('attributes.payment_every_n_weeks')
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
            'type_period'           => ['required', 'string', new Enum(TypePeriodAmortizationEnum::class)],
            'payment_every_n'       => ['required', 'integer', 'min:1'],
        ];
    }
}
