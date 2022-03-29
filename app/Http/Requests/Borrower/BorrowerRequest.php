<?php

namespace App\Http\Requests\Borrower;

use Illuminate\Foundation\Http\FormRequest;

class BorrowerRequest extends FormRequest
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


    function attributes()
    {
        return [
            'name_borrower'                     => __('attributes.name_borrower'),
            'last_name_borrower'                => __('attributes.last_name_borrower'),
            'name_file_ine_borrower'            => __('attributes.name_file_ine_borrower'),
            'name_file_proof_address_borrower'  => __('attributes.name_file_proof_address_borrower'),
            'id_beneficiary'                    => __('attributes.id_beneficiary'),
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
            'name_borrower'                     => ['required', 'string', 'max:50'],
            'last_name_borrower'                => ['required', 'string', 'max:100'],
            'name_file_ine_borrower'            => ['nullable', 'mimes:pdf,jpg,png', 'file', 'max:2048',],
            'name_file_proof_address_borrower'  => ['nullable', 'mimes:pdf,jpg,png', 'file', 'max:2048',],
            'id_beneficiary'                    => ['required', 'integer'],
            'remove_file_ine_borrower'          => ['nullable', 'boolean'],
            'remove_file_proof_address_borrower' => ['nullable', 'boolean'],
        ];
    }
}
