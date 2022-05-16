<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enum\StatePaymentEnum;
use App\Rules\PaymentDetermine;
use Illuminate\Validation\Rule;

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
            'id_payment'    => __('attributes.id_payment'),
            'type'          => __('attributes.type_status')
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
            'type'                          => ['required', 'string', Rule::in(['past-due-group', 'next-due-group', 'borrower-payments', 'personal-loans'])],
            'id_payment'                    => ['required', 'integer',new PaymentDetermine],
            'status'                        => ['required', 'string', new Enum(StatePaymentEnum::class)],
        ];
    }
}
