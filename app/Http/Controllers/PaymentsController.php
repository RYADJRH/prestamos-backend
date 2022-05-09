<?php

namespace App\Http\Controllers;

use App\Enum\StatePaymentEnum;
use App\Http\Requests\Payments\ChangeStatusRequest;
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
