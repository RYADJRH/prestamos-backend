<?php

namespace App\Http\Requests\Payslip;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPaymentsRequest extends FormRequest
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
            'slug_payslip'  => __('attributes.slug_payslip'),
            'payments'      => __('attributes.payments')
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
            'slug_payslip'                  => ['required', 'string', 'exists:payslips,slug'],
            'payments'                      => ['required', 'array', 'min:1'],
            'payments.*.isSelected'         => ['required', 'boolean'],
            'payments.*.amount_payment'     => ['required', 'numeric', 'min:0'],
            'payments.*.id_group_borrower'  => ['required','integer','exists:group_borrowers,id_group_borrower']
        ];
    }
}
