<?php

namespace App\Http\Requests\Beneficiary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BeneficiaryRequest extends FormRequest
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
            'name_beneficiary' => __('attributes.name_beneficiary')
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
            'name_beneficiary' => ['required', 'string', 'min:10', 'max:150']
        ];
    }
}
