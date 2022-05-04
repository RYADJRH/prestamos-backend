<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use Illuminate\Http\Request;
use App\Http\Fpdf\Reportes;

class PayslipReports extends Controller
{


    public function reportPaymentEmpty(Payslip $payslip)
    {
        /*
        "id_payment": 25,
        "amount_payment": 0,
        "state_payment": "paid",
        "created_payment": "2022-05-03T05:00:00.000000Z",
        "id_payslip": 46,
        "id_group_borrower": 36,
        "created_at": "2022-05-03T20:56:13.000000Z",
        "updated_at": "2022-05-03T20:56:13.000000Z",
        "amount_payment_decimal": 0,
        "borrower": {
            "id_borrower": 69,
            "name_borrower": "Rafael",
            "last_name_borrower": "Rebolledo Hernandez",
            "id_beneficiary": 1,
            "created_at": "2022-04-01T03:35:48.000000Z",
            "updated_at": "2022-04-01T03:35:49.000000Z",
            "laravel_through_key": 36,
            "full_name": "Rafael Rebolledo Hernandez"
        } */

        $payments =  $payslip->payments()->with('borrower')->get();

        $fpdf = new Reportes();
        $fpdf->AddPage();

        // $fpdf->SetFont('Courier', '', 18);
        // $fpdf->Cell(190, 10, "{$payslip->name_payslip}", 0, 1, 'C', false);

        $fpdf->Output();
        exit;
        // $doc = $fpdf->Output("Reporte de pagos {$payslip->name_payslip}", 'S');
        // return $doc;
    }
}
