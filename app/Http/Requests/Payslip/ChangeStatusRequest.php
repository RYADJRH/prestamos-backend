<?php

namespace App\Http\Requests\Payslip;

use App\Enum\StatePaymentEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ChangeStatusRequest extends FormRequest
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
            'status'        => __('attributes.status'),
            'id_payment'    => __('attributes.id_payment')
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
            'id_payment'                    => ['required', 'integer', 'exists:payments,id_payment'],
            'status'                        => ['required', 'string', new Enum(StatePaymentEnum::class)],
        ];
    }
}
