<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enum\StatePaymentEnum;

class PaymentBeneficiaryPlRequest extends FormRequest
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
            'date'          => __('attributes.date'),
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
            'status'   => ['string', new Enum(StatePaymentEnum::class)],
            'date'     => ['date_format:Y-m-d'],
        ];
    }
}
