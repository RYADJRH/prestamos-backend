<?php

namespace App\Http\Requests\Payments;

use App\Rules\MinAndMaxAmountAdjustPayment;
use Illuminate\Foundation\Http\FormRequest;

class AdjustIndividualPaymentRequest extends FormRequest
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
        $individualPayment                  = $this->route('individualPayment');
        $individualLoan                     = $individualPayment->individualLoan;
        $totalInPaymentsExceptCurrent       = $individualLoan->individualPayments
            ->where('num_payment', '!=', $individualPayment->num_payment)
            ->sum('amount_payment_period');

        $totalAmountPay                     = $individualLoan->amount_pay;

        return [
            "amount_payment" => ['required', 'numeric','min:0', new MinAndMaxAmountAdjustPayment($totalAmountPay, $totalInPaymentsExceptCurrent)]
        ];
    }
}
