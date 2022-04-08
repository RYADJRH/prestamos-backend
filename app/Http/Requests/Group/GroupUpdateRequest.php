<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\DayWeekEnum;
use Illuminate\Validation\Rules\Enum;

class GroupUpdateRequest extends FormRequest
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
            'name_group'    => __('attributes.name_group'),
            'created_group' => __('attributes.created_group'),
            'day_payment'   => __('attributes.day_payment'),
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
            'name_group'        => ['required', 'string', 'max:100'],
            'created_group'     => ['required', 'date_format:Y-m-d'],
            'day_payment'       => ['required', 'integer', new Enum(DayWeekEnum::class)],
        ];
    }
}
