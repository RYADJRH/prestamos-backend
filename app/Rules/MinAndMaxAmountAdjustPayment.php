<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class MinAndMaxAmountAdjustPayment implements Rule
{

    protected $maxValue = 0;
    protected $totalPayment = 0;
    protected $totalInPaymentsExceptCurrent = 0;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($totalPayment, $totalInPaymentsExceptCurrent)
    {
        $this->totalPayment                 = $totalPayment;
        $this->totalInPaymentsExceptCurrent = $totalInPaymentsExceptCurrent;
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
        $this->maxValue     =  round(($this->totalPayment - $this->totalInPaymentsExceptCurrent) / 100, 2);
        $totalwithChanges   = $this->totalInPaymentsExceptCurrent + ($value * 100);
        return $totalwithChanges <= $this->totalPayment;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.min_max_amount_adjust_payment', ['max' => number_format($this->maxValue, 2)]);
    }
}
