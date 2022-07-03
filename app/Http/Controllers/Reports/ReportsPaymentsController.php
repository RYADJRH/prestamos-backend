<?php

namespace App\Http\Controllers\Reports;

use App\Enum\StatePaymentEnum;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Fpdf\ReportsPayments;
use App\Http\Requests\Report\PaymentBeneficiaryPlRequest;
use App\Models\Beneficiary;
use App\Models\Borrower;
use App\Models\IndividualBorrow;
use App\Traits\DatesTraits;
use App\Traits\MoneyTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportsPaymentsController extends Controller
{

    use DatesTraits, MoneyTraits;

    private $font_size_report = 18;

    public function paymentsPastDueGroup(Group $group)
    {
        $title      = $group->name_group;
        $subTitle   = 'Pagos vencidos';
        $headers    = ['Nombre', 'No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $payments = $group->paymentsPastDueGroup('')->get();

        $totalAmount    = $this->convertToMoney(($payments->sum('amount_payment_period') / 100));
        $totalPayments  = count($payments);

        $payments = $payments->map(function ($payment) {

            return [
                $payment->borrower->full_name,
                $payment->num_payment,
                $this->formatDate($payment->date_payment),
                $this->convertToMoney($payment->amount_payment_period_decimal),
                $this->convertToMoney($payment->remaining_balance_decimal),
                StatePaymentEnum::getLabel($payment->state_payment)
            ];
        });

        $pdf = new  ReportsPayments();
        $pdf->setData($title, $subTitle, $headers);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Courier', 'B', $this->font_size_report);
        $pdf->SetWidths([60, 15, 30, 30, 30, 25]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['J', 'C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }


        $pdf->Ln(5);
        $pdf->cell(190, 5, "TOTAL DEL MONTO:" . $totalAmount, 0, 1, 'R', false);
        $pdf->cell(190, 5, "TOTAL DE PAGOS:" . $totalPayments, 0, 1, 'R', false);

        return $pdf->output('S');
    }

    public function paymentsNextDueGroup(Group $group)
    {
        $title      = $group->name_group;
        $subTitle   = 'Pagos siguientes';
        $headers    = ['Nombre', 'No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $payments = $group->paymentsNextDueGroup('')->get();

        $totalAmount    = $this->convertToMoney(($payments->sum('amount_payment_period') / 100));
        $totalPayments  = count($payments);

        $payments = $payments->map(function ($payment) {

            return [
                $payment->borrower->full_name,
                $payment->num_payment,
                $this->formatDate($payment->date_payment),
                $this->convertToMoney($payment->amount_payment_period_decimal),
                $this->convertToMoney($payment->remaining_balance_decimal),
                StatePaymentEnum::getLabel($payment->state_payment)
            ];
        });

        $pdf = new  ReportsPayments();
        $pdf->setData($title, $subTitle, $headers);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Courier', 'B', $this->font_size_report);
        $pdf->SetWidths([60, 15, 30, 30, 30, 25]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['J', 'C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }


        $pdf->Ln(5);
        $pdf->cell(190, 5, "TOTAL DEL MONTO:" . $totalAmount, 0, 1, 'R', false);
        $pdf->cell(190, 5, "TOTAL DE PAGOS:" . $totalPayments, 0, 1, 'R', false);
        return $pdf->output('S');
    }

    public function paymentsBorrowerGroup(Group $group, Borrower $borrower)
    {
        $title      = $borrower->full_name;
        $subTitle   = 'Pagos';
        $headers    = ['No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $group_borrower = $group->groupBorrowers()->where('id_borrower', $borrower->id_borrower)->first();
        $payments       = $group_borrower->payments()->get();

        $totalAmount    = $this->convertToMoney(($payments->sum('amount_payment_period') / 100));
        $totalPayments  = count($payments);

        $payments = $payments->map(function ($payment) {
            return [
                $payment->num_payment,
                $this->formatDate($payment->date_payment),
                $this->convertToMoney($payment->amount_payment_period_decimal),
                $this->convertToMoney($payment->remaining_balance_decimal),
                StatePaymentEnum::getLabel($payment->state_payment)
            ];
        });

        $pdf = new  ReportsPayments();
        $pdf->setData($title, $subTitle, $headers);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Courier', 'B', $this->font_size_report);
        $pdf->SetWidths([15, 50, 45, 45, 35]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }


        $pdf->Ln(5);
        $pdf->cell(190, 5, "TOTAL DEL MONTO:" . $totalAmount, 0, 1, 'R', false);
        $pdf->cell(190, 5, "TOTAL DE PAGOS:" . $totalPayments, 0, 1, 'R', false);

        return $pdf->output('S');
    }

    public function paymentsBorrowerPersonalLoan(Borrower $borrower, IndividualBorrow $individualBorrow)
    {
        $loan = $borrower->individualLoans()->where('id_borrow', $individualBorrow->id_borrow)->first();
        if (!$loan)
            abort(404);

        $title      = $borrower->full_name;
        $subTitle   = 'Pagos';
        $headers    = ['No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $payments       = $loan->individualPayments;

        $totalAmount    = $this->convertToMoney(($payments->sum('amount_payment_period') / 100));
        $totalPayments  = count($payments);


        $payments = $payments->map(function ($payment) {
            return [
                $payment->num_payment,
                $this->formatDate($payment->date_payment),
                $this->convertToMoney($payment->amount_payment_period_decimal),
                $this->convertToMoney($payment->remaining_balance_decimal),
                StatePaymentEnum::getLabel($payment->state_payment)
            ];
        });

        $pdf = new  ReportsPayments();
        $pdf->setData($title, $subTitle, $headers);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Courier', 'B', $this->font_size_report);
        $pdf->SetWidths([15, 50, 45, 45, 35]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }


        $pdf->Ln(5);
        $pdf->cell(190, 5, "TOTAL DEL MONTO:" . $totalAmount, 0, 1, 'R', false);
        $pdf->cell(190, 5, "TOTAL DE PAGOS:" . $totalPayments, 0, 1, 'R', false);

        return $pdf->output('S');
    }

    public function paymentsBeneficiaryPersonalLoans(PaymentBeneficiaryPlRequest $request, Beneficiary $beneficiary)
    {

        $this->authorize('view', $beneficiary);
        $date   = $request->date('date');
        $date   = $date ? $date : Carbon::now();
        $status = $request->input('status', StatePaymentEnum::STATUS_INPROCCESS->value);

        if ($status == 'unpaid') {
            $date_ant   = clone $date;
            $date_ant   = $date_ant->subMonth(5);

            $fechaReport = $this->formatDate($date_ant) . " al " . $this->formatDate($date);
            $subTitle   = 'No pagados';

            $payments   = [];
            foreach ($beneficiary->individualLoans as  $loan) {
                $individual_payments = $loan->individualPayments()
                    ->whereBetween('date_payment', [$date_ant, $date])
                    ->where(['state_payment' => $status])->get();
                if (count($individual_payments) > 0) {
                    $borrower = $loan->borrower;
                    $name_borrower = $borrower->full_name;
                    foreach ($individual_payments as $individual_payment) {
                        $individual_payment['full_name'] = $name_borrower;
                        array_push($payments, $individual_payment);
                    }
                }
            }
            $payments = collect($payments);
        } else {
            $fechaReport = $this->formatDate($date);
            $subTitle   = 'En proceso';
            $payments  = $beneficiary->individualLoans->map(function ($loan, $key) use ($date, $status) {
                $payment = $loan->individualPayments()
                    ->where(['date_payment' => $date, 'state_payment' => $status])->first();
                if ($payment) {
                    $borrower = $loan->borrower;
                    $payment['full_name'] = $borrower->full_name;
                    return $payment;
                }
                return;
            })->filter(function ($payment, $key) {
                return $payment !== null;
            });
        }


        $totalAmount    = $this->convertToMoney(($payments->sum('amount_payment_period') / 100));
        $totalPayments  = count($payments);
        $title      = "Reporte de pagos - Fecha {$fechaReport}";


        $headers    = ['Nombre', 'Pago', 'Monto abono', '[x]'];

        $pdf = new  ReportsPayments();
        $pdf->setData($title, $subTitle, $headers);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Courier', 'B', $this->font_size_report);
        $pdf->SetWidths([98.66, 26.66, 46.66, 18]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1,true);

        $pdf->SetAligns(['L', 'C', 'R', 'C', 'C']);

        foreach ($payments as $payment) {
            $pdf->Row(
                [
                    $payment->full_name,
                    $payment->num_payment,
                    $this->convertToMoney($payment->amount_payment_period_decimal),
                    ""
                ]
            );
        }


        $pdf->Ln(5);
        $pdf->cell(190, 5, "TOTAL DEL MONTO:" . $totalAmount, 0, 1, 'R', false);
        $pdf->cell(190, 5, "TOTAL DE PAGOS:" . $totalPayments, 0, 1, 'R', false);

        return $pdf->output('S');
    }
}
