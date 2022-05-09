<?php

namespace App\Traits;

trait MoneyTraits
{

    function convertToMoney($number)
    {
        return "$ " . number_format($number, 2);
    }
}
