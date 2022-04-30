<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateRequest extends FormRequest
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
            'amount_borrow'     => __('attributes.amount_borrow'),
            'amount_interest'   => __('attributes.amount_interest')
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
            'amount_borrow'     => ['required', 'numeric', 'min:0'],
            'amount_interest'   => ['required', 'numeric', 'min:0'],
        ];
    }
}
