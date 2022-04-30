<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayslipRequest extends FormRequest
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
            'name_payslip'    => __('attributes.name_payslip'),
            'created_payslip' => __('attributes.created_payslip'),
        ];
        # code...
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_payslip'      => ['required','string','max:100'],
            'created_payslip'   => ['required', 'date_format:Y-m-d'],
        ];
    }
}
