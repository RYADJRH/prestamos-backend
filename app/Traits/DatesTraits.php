<?php

namespace App\Traits;

use Carbon\Carbon;

trait DatesTraits
{
    function formatDate($date, $conector = " ")
    {
        $date_aux = Carbon::parse($date);
        return $date_aux->format('d') . $conector . ucfirst($date_aux->monthName) . $conector . $date_aux->year;
    }
}
