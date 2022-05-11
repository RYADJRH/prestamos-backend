<?php

namespace App\Http\Controllers;

use App\Enum\StatePaymentEnum;
use App\Http\Requests\Payments\ChangeStatusRequest;
use App\Models\Borrower;
use App\Models\Group;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
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

    public function updateStatePayment(ChangeStatusRequest $request): JsonResponse
    {
        $status     = $request->status;
        $id_payment = $request->id_payment;
        $payment = Payment::find($id_payment);
        $borrower = $payment->borrower;
        $this->authorize('updateStatusPayment', $borrower);
        $payment->state_payment = $status;
        $payment->save();

        return new JsonResponse(['state_payment' => $payment->state_payment]);
    }
}
