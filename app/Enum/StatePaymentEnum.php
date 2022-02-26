<?php
namespace App\Enum;

enum StatePaymentEnum: string
{
    case STATUS_PAID       = 'paid';
    case STATUS_UNPAID     = 'unpaid';
    case STATUS_INPROCCESS = 'in_proccess';
}
