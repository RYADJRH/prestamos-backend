<?php

namespace App\Traits;

use App\Enum\TypePeriodAmortizationEnum;
use Carbon\Carbon;

trait AmortizationTraits
{

    public function calculateRemaining($payments, $total_pay, $current_num_payment, $current_amount_payment)
    {
        $payments = $payments->reduce(function ($acc, $value) use ($current_num_payment, $current_amount_payment) {
            array_push($acc, [
                'id_payment'            => $value->id_payment,
                'num_payment'           => $value->num_payment,
                'amount_payment_period' => $current_num_payment === $value->num_payment ? ($current_amount_payment * 100)  : $value->amount_payment_period,
                'remaining_balance'     => 0
            ]);
            return $acc;
        }, []);

        $num_payments = count($payments);
        $total = $total_pay;
        for ($i = 1; $i <= $num_payments; $i++) {
            $paymentIndex = array_search($i, array_column($payments, 'num_payment'));
            $payment = $payments[$paymentIndex];
            $total -= $payment['amount_payment_period'];

            $payments[$paymentIndex]['amount_payment_period'] = $payments[$paymentIndex]['amount_payment_period'] / 100;
            $payments[$paymentIndex]['remaining_balance'] = $total / 100;
        }
        return $payments;
    }




    public function calculatedAmortizationGroup($amount_borrow, $amount_interest, $amount_payment_period, $date_init_payment, $payment_every_n_weeks)
    {
        $amortization = [];
        $amount_borrow          = round($amount_borrow * 100, 2);
        $amount_interest        = round($amount_interest * 100, 2);
        $amount_payment_period  = round($amount_payment_period * 100, 2);
        $sum_interest_borrow    = round($amount_borrow + $amount_interest, 2);

        $num_payments = ceil($sum_interest_borrow / $amount_payment_period);
        $period                 = $payment_every_n_weeks;
        $carbon_date_init       = Carbon::parse($date_init_payment);
        $carbon_num_day         = $carbon_date_init->dayOfWeek;
        $calculated_date        = $carbon_date_init;

        $sum_interest_borrow_remaing_balance = $sum_interest_borrow;
        for ($i = 1; $i < ($num_payments + 1); $i++) {
            if ($i != 1) {
                for ($j = 0; $j < $period; $j++) {
                    $calculated_date = $calculated_date->next($carbon_num_day);
                }
            }

            $sum_interest_borrow_remaing_balance -= $amount_payment_period;
            if ($sum_interest_borrow_remaing_balance < 0) {
                $amount_payment_period = $amount_payment_period + ($sum_interest_borrow_remaing_balance);
                $sum_interest_borrow_remaing_balance =  0;
            }
            $individualPay = [
                'num_payment'           => $i,
                'date_payment'          => $calculated_date->format('Y-m-d'),
                'amount_payment_period' => round($amount_payment_period / 100, 2),
                'remaining_balance'     => round(($sum_interest_borrow_remaing_balance / 100), 2)
            ];

            array_push($amortization, $individualPay);
        }
        return $amortization;
    }

    public function calculatedAmortization($amount_borrow, $amount_interest, $amount_payment_period, $date_init_payment, $type_period, $payment_every_n)
    {
        $amortization = [];
        $amount_borrow          = round($amount_borrow * 100, 2);
        $amount_interest        = round($amount_interest * 100, 2);
        $amount_payment_period  = round($amount_payment_period * 100, 2);
        $sum_interest_borrow    = round($amount_borrow + $amount_interest, 2);
        $num_payments = ceil($sum_interest_borrow / $amount_payment_period);

        $period                 = $payment_every_n;
        $carbon_date_init       = Carbon::parse($date_init_payment);
        $calculated_date        = clone $carbon_date_init;


        $sum_interest_borrow_remaing_balance = $sum_interest_borrow;


        for ($i = 1; $i < ($num_payments + 1); $i++) {
            if ($i != 1) {
                if ($type_period == TypePeriodAmortizationEnum::NMONTHS->value) {
                    $calculated_date = $calculated_date->addMonths($period);
                }
                if ($type_period == TypePeriodAmortizationEnum::NWEEKS->value) {
                    $calculated_date = $calculated_date->addWeeks($period);
                }
                if ($type_period == TypePeriodAmortizationEnum::NDAYS->value) {
                    $calculated_date = $calculated_date->addDays($period);
                }
            }

            $sum_interest_borrow_remaing_balance -= $amount_payment_period;
            if ($sum_interest_borrow_remaing_balance < 0) {
                $amount_payment_period = $amount_payment_period + ($sum_interest_borrow_remaing_balance);
                $sum_interest_borrow_remaing_balance =  0;
            }
            $individualPay = [
                'num_payment'           => $i,
                'date_payment'          => $calculated_date->format('Y-m-d'),
                'amount_payment_period' => round($amount_payment_period / 100, 2),
                'remaining_balance'     => round(($sum_interest_borrow_remaing_balance / 100), 2)
            ];

            array_push($amortization, $individualPay);
        }
        return $amortization;
        # code...
    }
}
