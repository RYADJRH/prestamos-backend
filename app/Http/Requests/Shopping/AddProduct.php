<?php

namespace App\Http\Requests\Shopping;

use Illuminate\Foundation\Http\FormRequest;

class AddProduct extends FormRequest
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
            'product_name'      => __('attributes.product_name'),
            'date_shopping'     => __('attributes.date_shopping'),
            'producto_price'    => __('attributes.producto_price'),
            'id_beneficiary'    => __('attributes.id_beneficiary')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'product_name'      => ['required', 'string', 'max:255'],
            'date_shopping'     => ['required', 'date_format:Y-m-d'],
            'producto_price'    => ['required', 'numeric'],
        ];

        if ($this->_method === 'post')
            $rules['id_beneficiary'] = ['required', 'integer'];

        return $rules;
    }
}
