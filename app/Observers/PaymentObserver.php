<?php

namespace App\Observers;

use App\Enum\StatePaymentEnum;
use App\Models\Payment;

class PaymentObserver
{
    public function updated(Payment $payment)
    {
        $group_borrower                 = $payment->groupBorrower;
        $number_payments                = $group_borrower->number_payments;
        $number_payments_paids          = $group_borrower->paymentsPaid->count();
        $number_payments_unpaids        = $group_borrower->paymentsUnPaid->count();
        $number_payments_inProccess     = $group_borrower->paymentsInProccess->count();

        $status =  StatePaymentEnum::STATUS_INPROCCESS;
        if ($number_payments_unpaids > 0) {
            $status = StatePaymentEnum::STATUS_UNPAID;
        } else if ($number_payments_paids == $number_payments) {
            $status = StatePaymentEnum::STATUS_PAID;
        } else if ($number_payments_inProccess == $number_payments) {
            $status =  StatePaymentEnum::STATUS_INPROCCESS;
        }

        $group_borrower->state_borrow = $status;
        $group_borrower->save();
    }
}
