<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\AdjustIndividualPaymentRequest;
use App\Http\Requests\Payments\ChangeStatusRequest;
use App\Models\AdjustIndividualPayment;
use App\Models\Borrower;
use App\Models\Group;
use App\Models\IndividualBorrow;
use App\Models\IndividualPayment;
use App\Models\Payment;
use App\Traits\AmortizationTraits;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    use AmortizationTraits;

    public function fnPaymentsPastDueGroup(Request $request, Group $group): JsonResponse
    {
        $this->authorize('view', $group);
        $search = $request->input('search', '');
        $payments_unpaid = $group->paymentsPastDueGroup($search)
            ->paginate(20);

        $total = round($group->paymentsPastDueGroup('')->sum('amount_payment_period') / 100, 2);

        return new JsonResponse(['name_group' => $group->name_group, 'payments' => $payments_unpaid, 'total' => $total]);
    }

    public function fnPaymentsNextDueGroup(Request $request, Group $group): JsonResponse
    {
        $this->authorize('view', $group);
        $search = $request->input('search', '');
        $payments_nextDue = $group->paymentsNextDueGroup($search)
            ->paginate(20);

        $total = round($group->paymentsNextDueGroup('')->sum('amount_payment_period') / 100, 2);
        return new JsonResponse(['name_group' => $group->name_group, 'payments' => $payments_nextDue, 'total' => $total]);
    }


    public function fnPaymentsBorrower(Group $group, Borrower $borrower): JsonResponse
    {
        $this->authorize('view', $borrower);
        $group_borrower = $group->groupBorrowers()->where('id_borrower', $borrower->id_borrower)->first();
        $payments       = $group_borrower->payments()->paginate(20);;
        $total          = round($group_borrower->paymentsUnPaidInProccess()->sum('amount_payment_period') / 100, 2);
        return new JsonResponse(['name_group' => $group->name_group, 'name_borrower' => $borrower->full_name, 'payments' => $payments, 'total' => $total]);
    }

    public function paymentsForIndividualLoan(Borrower $borrower, IndividualBorrow $individualBorrow): JsonResponse
    {

        $loan = $borrower->individualLoans()->where('id_borrow', $individualBorrow->id_borrow)->first();
        if (!$loan)
            abort(404);

        $payments = $loan->individualPayments()->paginate(20);
        $payments->getCollection()->transform(function ($payment) {
            $payment->adjustPayment;
            return $payment;
        });
        $total    = round($loan->paymentsUnPaidInProccess()->sum('amount_payment_period') / 100, 2);

        return new JsonResponse(['name_borrower' => $borrower->full_name, 'total' => $total, 'payments' => $payments]);
    }

    public function updateStatePayment(ChangeStatusRequest $request): JsonResponse
    {
        $status     = $request->status;
        $id_payment = $request->id_payment;
        $type       = $request->type;

        if ($type == 'personal-loans') {
            $payment    = IndividualPayment::find($id_payment);
            $borrower    = $payment->individualLoan->borrower;
        } else {
            $payment    = Payment::find($id_payment);
            $borrower = $payment->borrower;
        }

        $this->authorize('updateStatusPayment', $borrower);
        $payment->state_payment = $status;
        $payment->save();

        return new JsonResponse(['state_payment' => $payment->state_payment]);
    }

    public function adjustIndividualPayment(IndividualPayment $individualPayment, AdjustIndividualPaymentRequest $request)
    {
        $this->authorize('view', $individualPayment);
        $amount_payment_decimal = $request->amount_payment;
        $amount_payment = $amount_payment_decimal * 100;
        $before_amount = $individualPayment->amount_payment_period;

        if ($amount_payment !== $before_amount) {
            $payments = $individualPayment->individualLoan->individualPayments;
            $remainings = $this->calculateRemaining($payments, $individualPayment->individualLoan->amount_pay, $individualPayment->num_payment, $amount_payment_decimal);

            foreach ($remainings as  $value) {
                $payment = IndividualPayment::find($value['id_payment']);
                if ($payment) {
                    $payment->update([
                        'remaining_balance'     => $value['remaining_balance'],
                        'amount_payment_period' => $value['amount_payment_period']
                    ]);
                }
            }

            $adjustPayment = new AdjustIndividualPayment();
            $adjustPayment->before_amount = $before_amount / 100;
            $adjustPayment->after_amount = $amount_payment_decimal;
            $adjustPayment->date_adjust_payment = Carbon::now();
            $adjustPayment->id_payment = $individualPayment->id_payment;
            $adjustPayment->save();
        }

        return new JsonResponse(['success' => true]);
    }
}
