<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use App\Enum\StatePaymentEnum;

class PaymenTypeGet implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $arr = explode(',', $value);
            $rules = [
                'status' => 'nullable', 'array',
                'status.*' => ['string', new Enum(StatePaymentEnum::class)]
            ];
            $input = ['status' => $arr];
            return  Validator::make($input, $rules)->passes();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.payment_type_get');
    }
}
