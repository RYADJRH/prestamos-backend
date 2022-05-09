<?php
namespace App\Enum;

enum StatePaymentEnum: string
{
    case STATUS_PAID       = 'paid';
    case STATUS_UNPAID     = 'unpaid';
    case STATUS_INPROCCESS = 'in_proccess';

    public static function getLabel(self $value)
    {
        return match ($value) {
            StatePaymentEnum::STATUS_PAID       => 'Pagado',
            StatePaymentEnum::STATUS_UNPAID     => 'No pagado',
            StatePaymentEnum::STATUS_INPROCCESS => 'En proceso',
        };
    }
}

