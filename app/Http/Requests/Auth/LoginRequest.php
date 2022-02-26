<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nick_name_user'    => __('attributes.nick_name_user'),
            'password'          => __('attributes.password')
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
            'nick_name_user' => ['required', 'string', 'max:25'],
            'password'       => ['required', 'string', 'min:8', 'max:255']
        ];
    }
}
