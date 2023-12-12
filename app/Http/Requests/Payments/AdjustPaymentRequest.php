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
        $payment                        = $this->route('payment');
        $group_borrower                 = $payment->groupBorrower;
        $totalInPaymentsExceptCurrent   = $group_borrower->payments
            ->where('num_payment', '!=', $payment->num_payment)
            ->sum('amount_payment_period');
        $totalAmountPay                 = $group_borrower->amount_pay;

        return [
            "amount_payment" => ['required', 'numeric', 'min:0', new MinAndMaxAmountAdjustPayment($totalAmountPay, $totalInPaymentsExceptCurrent)]
        ];
    }
}
