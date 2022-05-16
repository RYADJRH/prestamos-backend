<?php

namespace App\Observers;

use App\Enum\StatePaymentEnum;
use App\Models\IndividualPayment;
use Illuminate\Support\Facades\Log;

class IndvidualPaymentObserver
{
    public function updated(IndividualPayment $payment)
    {
        $individualLoan         = $payment->individualLoan;
        $number_payments        = $individualLoan->individualPayments->count();

        $number_payments                = $number_payments;
        $number_payments_paids          = $individualLoan->individualPayments->where('state_payment','=',StatePaymentEnum::STATUS_PAID)->count();
        $number_payments_unpaids        = $individualLoan->individualPayments->where('state_payment','=',StatePaymentEnum::STATUS_UNPAID)->count();
        $number_payments_inProccess     = $individualLoan->individualPayments->where('state_payment','=',StatePaymentEnum::STATUS_INPROCCESS)->count();

        $status =  StatePaymentEnum::STATUS_INPROCCESS;
        if ($number_payments_unpaids > 0) {
            $status = StatePaymentEnum::STATUS_UNPAID;
        } else if ($number_payments_paids == $number_payments) {
            $status = StatePaymentEnum::STATUS_PAID;
        } else if ($number_payments_inProccess == $number_payments) {
            $status =  StatePaymentEnum::STATUS_INPROCCESS;
        }

        $individualLoan->state_borrow = $status;
        $individualLoan->save();
    }
}
