<?php

namespace App\Http\Controllers;

use App\Enum\StatePaymentEnum;
use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TotalAmountsController extends Controller
{
    public function totalsAmount(Beneficiary $beneficiary): JsonResponse
    {
        $this->authorize('view', $beneficiary);
        $individual_loans            = $beneficiary->individualLoans;

        $individual_payments_borrow             = $individual_loans->sum('amount_borrow');
        $individual_payments_interest           = $individual_loans->sum('amount_interest');
        $individual_payments_borrow_interest    = $individual_payments_borrow + $individual_payments_interest;

        $individual_payments_charged =  $individual_loans->sum(function ($individualLoans) {
            return $individualLoans->individualPayments
                ->where('state_payment', '=', StatePaymentEnum::STATUS_PAID)
                ->sum('amount_payment_period');
        });

        $individual_payments_in_proccess =  $individual_loans->sum(function ($individualLoans) {
            return $individualLoans->individualPayments
                ->where('state_payment', '=', StatePaymentEnum::STATUS_INPROCCESS)
                ->sum('amount_payment_period');
        });

        $individual_payments_un_paid =  $individual_loans->sum(function ($individualLoans) {
            return $individualLoans->individualPayments
                ->where('state_payment', '=', StatePaymentEnum::STATUS_UNPAID)
                ->sum('amount_payment_period');
        });


        $groups = $beneficiary->groups;

        $group_payments_borrow             = $groups->sum(function ($group) {
            return $group->groupBorrowers->sum('amount_borrow');
        });

        $group_payments_interest             = $groups->sum(function ($group) {
            return $group->groupBorrowers->sum('amount_interest');
        });
        $group_payments_borrow_interest    = $group_payments_borrow + $group_payments_interest;


        $group_payments_charged             = $groups->sum(function ($group) {
            return $group->groupBorrowers->sum(function ($group_borrower) {
                return $group_borrower->paymentsPaid->sum('amount_payment_period');
            });
        });

        $group_payments_in_proccess             = $groups->sum(function ($group) {
            return $group->groupBorrowers->sum(function ($group_borrower) {
                return $group_borrower->paymentsInProccess->sum('amount_payment_period');
            });
        });

        $group_payments_un_paid             = $groups->sum(function ($group) {
            return $group->groupBorrowers->sum(function ($group_borrower) {
                return $group_borrower->paymentsUnPaid->sum('amount_payment_period');
            });
        });



        $total_payments_borrow              = round(($individual_payments_borrow + $group_payments_borrow) / 100, 2);
        $total_payments_interest            = round(($individual_payments_interest + $group_payments_interest) / 100, 2);
        $total_payments_borrow_interest     = round(($individual_payments_borrow_interest + $group_payments_borrow_interest) / 100, 2);

        $total_payments_charged             = round(($individual_payments_charged +  $group_payments_charged) / 100, 2);
        $total_payments_in_proccess         = round(($individual_payments_in_proccess + $group_payments_in_proccess) / 100, 2);
        $total_payments_un_paid             = round(($individual_payments_un_paid + $group_payments_un_paid) / 100, 2);

        return new JsonResponse([
            'grupos' => $group_payments_borrow,
            'totalAmounts' => [
                'amount_borrow'             => $total_payments_borrow,
                'amount_interest'           => $total_payments_interest,
                'amount_borrow_interest'    => $total_payments_borrow_interest,

                'amount_charged'            => $total_payments_charged,
                'amount_in_proccess'        => $total_payments_in_proccess,
                'amount_un_paid'            => $total_payments_un_paid,
            ]
        ]);
    }
}
