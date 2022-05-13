<?php

namespace App\Http\Controllers;

use App\Enum\StatePaymentEnum;
use App\Http\Requests\Loans\AddLoansRequest;
use App\Models\Beneficiary;
use App\Models\Borrower;
use App\Models\IndividualBorrow;
use App\Traits\AmortizationTraits;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoansController extends Controller
{
    use AmortizationTraits;

    function amountsLoansBeneficiary(Beneficiary $beneficiary): JsonResponse
    {
        $this->authorize('view', $beneficiary);

        $loans              = $beneficiary->individualLoans;
        $number_loans       = $loans->count();
        $amount_borrow      = round($loans->sum('amount_borrow') / 100, 2);
        $amount_interest    = round($loans->sum('amount_interest') / 100, 2);
        $amount_pay         = round($loans->sum('amount_pay') / 100, 2);
        $amount_charged     = round(5000 / 100, 2);

        $amount_charged = $beneficiary->individualLoans
            ->sum(function ($individualLoans) {
                return $individualLoans->individualPayments
                    ->where('state_payment', '=', StatePaymentEnum::STATUS_PAID)
                    ->sum('amount_payment_period');
            });
        $amount_charged = round($amount_charged / 100, 2);

        $amounts = [
            'number_loans'      => $number_loans,
            'amount_borrow'     => $amount_borrow,
            'amount_interest'   => $amount_interest,
            'amount_pay'        => $amount_pay,
            'amount_charged'    => $amount_charged,
        ];

        return new JsonResponse(['amounts_loans' => $amounts]);
    }

    public function addLoans(AddLoansRequest $request): JsonResponse
    {
        $id_borrower            = $request->id_borrower;
        $amount_borrow          = $request->amount_borrow;
        $amount_interest        = $request->amount_interest;
        $amount_payment_period  = $request->amount_payment_period;
        $date_init_payment      = $request->date_init_payment;
        $type_period            = $request->type_period;
        $payment_every_n        = $request->payment_every_n;

        $borrower    = Borrower::find($id_borrower);
        $beneficiary = $borrower->beneficiary;
        $this->authorize('view', $beneficiary);


        DB::beginTransaction();
        try {
            $amortization = $this->calculatedAmortization($amount_borrow, $amount_interest, $amount_payment_period, $date_init_payment, $type_period, $payment_every_n);

            $loan =  $borrower->individualLoans()
                ->save(
                    new IndividualBorrow(
                        [
                            'number_payments'   => count($amortization),
                            'amount_borrow'     => $amount_borrow,
                            'amount_interest'   => $amount_interest,
                            'state_borrow'      => StatePaymentEnum::STATUS_INPROCCESS->value
                        ]
                    )
                );

            $loan->individualPayments()->createMany($amortization);

            $loan = IndividualBorrow::find($loan->id_borrow)->with('borrower')->first();

            $loan = [
                "id_borrow"             => $loan->id_borrow,
                "id_borrower"           => $loan->id_borrow,
                "full_name"             => $loan->borrower->full_name,
                "amount_borrow"         => $loan->amount_borrow_decimal,
                "amount_interest"       => $loan->amount_interest_decimal,
                "amount_pay"            => $loan->amount_pay_decimal,
                "amount_payment_total"  => 0,
                "number_payments"       => "0 / {$loan->number_payments}",
                "state_borrow"          => $loan->state_borrow,
            ];

            DB::commit();
            return new JsonResponse(['loan' => $loan]);
        } catch (Exception $e) {
            DB::rollBack();
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    public function getLoansBeneficiary(Request $request, Beneficiary $beneficiary): JsonResponse
    {
        $this->authorize('view', $beneficiary);
        $search = $request->input('search', '');
        Log::info($search);
        $loans = $beneficiary->individualLoans()
            ->with('borrower')
            ->whereHas('borrower', function ($query) use ($search) {
                $query->where(DB::raw("concat(borrowers.name_borrower, ' ', borrowers.last_name_borrower)"), 'LIKE',  $search . "%");
            })
            ->paginate(20)
            ->through(function ($loan) {

                $individualPayments = $loan->individualPayments
                    ->where('state_payment', '=', StatePaymentEnum::STATUS_PAID);

                $amount_payment_total = $individualPayments->sum('amount_payment_period');

                $amount_payment_total = round($amount_payment_total / 100, 2);
                return [
                    "id_borrow"             => $loan->id_borrow,
                    "id_borrower"           => $loan->id_borrow,
                    "full_name"             => $loan->borrower->full_name,
                    "amount_borrow"         => $loan->amount_borrow_decimal,
                    "amount_interest"       => $loan->amount_interest_decimal,
                    "amount_pay"            => $loan->amount_pay_decimal,
                    "amount_payment_total"  => $amount_payment_total,
                    "number_payments"       =>  count($individualPayments) . " / {$loan->number_payments}",
                    "state_borrow"          => $loan->state_borrow,
                ];
            });

        return new JsonResponse(['loans' => $loans]);
    }
}
