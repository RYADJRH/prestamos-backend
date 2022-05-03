<?php

namespace App\Http\Controllers;

use App\Enum\StatePaymentEnum;
use App\Http\Requests\Payslip\ChangeStatusRequest;
use App\Http\Requests\Payslip\RegisterPaymentsRequest;
use App\Http\Requests\Payslip\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Payslip;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PayslipController extends Controller
{
    public function getPayslip(Payslip $payslip): JsonResponse
    {
        $this->authorize('view', $payslip);
        $payslip_data =
            $payslip::with('group')
            ->with('payments')
            ->first();

        $payslip_data->number_payments  = count($payslip_data->payments);
        $payslip_data->amount_payments  = round($payslip_data->payments()->where('state_payment', StatePaymentEnum::STATUS_PAID)->sum('amount_payment') / 100, 2);
        $payslip_data->slug_group       = $payslip_data->group->slug;

        $payslip_data->unsetRelations();

        return new JsonResponse(['payslip' => $payslip_data]);
    }

    public function getPayments(Request $request, Payslip $payslip): JsonResponse
    {
        $search = $request->input('search', '');
        $this->authorize('view', $payslip);
        $payments =  $payslip->payments()->with('borrower')
            ->whereRelation('borrower', DB::raw("concat(name_borrower, ' ', last_name_borrower)"), 'LIKE', "%" . $search . "%")
            ->paginate(20)
            ->through(function ($payment) {
                return [
                    'full_name'                 => $payment->borrower->full_name,
                    'id_payment'                => $payment->id_payment,
                    'created_payment'           => $payment->created_payment,
                    'amount_payment'            => $payment->amount_payment,
                    'amount_payment_decimal'    => $payment->amount_payment_decimal,
                    'state_payment'             => $payment->state_payment
                ];
            });

        return new JsonResponse(['payments' => $payments]);
    }

    public function addPaymentsMemberPayslip(Payslip $payslip): JsonResponse
    {
        $this->authorize('view', $payslip);

        $id_payslip = $payslip->id_payslip;
        $borrowers  = $payslip->group->borrowers()
            ->withCount(['payments' => function (Builder $query) use ($id_payslip) {
                $query->where('id_payslip', '=', $id_payslip);
            }])
            ->get();

        $borrowers = $borrowers->where('payments_count', 0);
        $borrowers = $borrowers->map(function ($borrower) {
            return [
                'id_borrower'       => $borrower->id_borrower,
                'id_group_borrower' => $borrower->group_borrower->id_group_borrower,
                'full_name'         => $borrower->full_name,
            ];
        });

        return new JsonResponse(['addPaymentsMembers' => $borrowers->values()]);
    }

    public function registerPaymentsPayslip(RegisterPaymentsRequest $request): JsonResponse
    {
        $payments       = $request->payments;
        $slug_payslip   = $request->slug_payslip;
        $payslip  = Payslip::where('slug', $slug_payslip)->first();
        $this->authorize('view', $payslip);

        $createPayments = array_map(function ($payment) {
            return new Payment([
                'id_group_borrower' => $payment['id_group_borrower'],
                'amount_payment'    => $payment['amount_payment'],
                'state_payment'     => StatePaymentEnum::STATUS_PAID,
                'created_payment'   => Carbon::now()
            ]);
        }, $payments);

        $payslip->payments()->saveMany($createPayments);

        return new JsonResponse(['payslip' => $createPayments]);
    }

    public function deletePaymentPasyslip(Payment $payments): JsonResponse
    {
        $payslip = $payments->payslip;
        $this->authorize('view', $payslip);

        $isDeleted = $payments->delete();
        return new JsonResponse(['isDeleted' => $isDeleted]);
    }

    public function changeStatusPayment(ChangeStatusRequest $request): JsonResponse
    {
        $status     = $request->status;
        $id_payment = $request->id_payment;

        $payment = Payment::find($id_payment);
        $payslip = $payment->payslip;
        $this->authorize('view', $payslip);

        $payment->update(['state_payment' => $status]);

        return new JsonResponse(['state_payment' => $payment->state_payment]);
    }

    public function updatePaymentPayslip(UpdatePaymentRequest $request, Payment $payments): JsonResponse
    {
        $amount_payment = $request->amount_payment;
        $payslip = $payments->payslip;
        $this->authorize('view', $payslip);

        $payments->update(['amount_payment' => $amount_payment]);
        
        return new JsonResponse([
            'amount_payment' => $payments->amount_payment,
            'amount_payment_decimal' => $payments->amount_payment_decimal
        ]);
    }
}
