<?php

namespace App\Http\Controllers\Reports;

use App\Enum\StatePaymentEnum;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Fpdf\ReportsPayments;
use App\Models\Borrower;
use App\Models\IndividualBorrow;
use App\Traits\DatesTraits;
use App\Traits\MoneyTraits;
use Carbon\Carbon;

class ReportsPaymentsController extends Controller
{

    use DatesTraits, MoneyTraits;

    public function paymentsPastDueGroup(Group $group)
    {
        $title      = $group->name_group;
        $subTitle   = 'Pagos vencidos';
        $headers    = ['Nombre', 'No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $payments = $group->paymentsPastDueGroup('')->get();
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

        $pdf->SetFont('Courier', 'B', 7);
        $pdf->SetWidths([60, 15, 30, 30, 30, 25]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['J', 'C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }

        return $pdf->output('S');
    }

    public function paymentsNextDueGroup(Group $group)
    {
        $title      = $group->name_group;
        $subTitle   = 'Pagos siguientes';
        $headers    = ['Nombre', 'No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $payments = $group->paymentsNextDueGroup('')->get();
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

        $pdf->SetFont('Courier', 'B', 7);
        $pdf->SetWidths([60, 15, 30, 30, 30, 25]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['J', 'C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }

        return $pdf->output('S');
    }

    public function paymentsBorrowerGroup(Group $group, Borrower $borrower)
    {
        $title      = $borrower->full_name;
        $subTitle   = 'Pagos';
        $headers    = ['No.Pago', 'Fecha', 'Monto abono', 'Saldo restante', 'Status'];
        $group_borrower = $group->groupBorrowers()->where('id_borrower', $borrower->id_borrower)->first();
        $payments       = $group_borrower->payments()->get();
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

        $pdf->SetFont('Courier', 'B', 7);
        $pdf->SetWidths([15, 50, 45, 45, 35]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }

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

        $pdf->SetFont('Courier', 'B', 7);
        $pdf->SetWidths([15, 50, 45, 45, 35]);

        $pdf->SetAligns(['C', 'C', 'C', 'C', 'C']);
        $pdf->SetFillColor(186, 230, 253);
        $pdf->Row($headers, 1, true);

        $pdf->SetAligns(['C', 'J', 'R', 'R', 'C']);
        foreach ($payments as $payment) {
            $pdf->Row($payment);
        }

        return $pdf->output('S');
    }
}
