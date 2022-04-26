<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class MemberAddRequest extends FormRequest
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
            'slug_group'        => __('attributes.slug_group'),
            'id_borrower'       => __('attributes.id_borrower'),
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
            'slug_group'        => ['required', 'string', 'exists:groups,slug'],
            'id_borrower'       => ['required', 'integer', 'min:1'],
            'amount_borrow'     => ['required', 'numeric', 'min:0'],
            'amount_interest'   => ['required', 'numeric', 'min:0'],
        ];
    }
}
