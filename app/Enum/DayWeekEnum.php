<?php

namespace App\Enum;

enum DayWeekEnum: int
{
    case LUNES     = 1;
    case MARTES    = 2;
    case MIERCOLES = 3;
    case JUEVES    = 4;
    case VIERNES   = 5;
    case SABADO    = 6;
    case DOMINGO   = 0;

    public static function getLabel(self $value)
    {
        return match ($value) {
            DayWeekEnum::LUNES      => 'Lunes',
            DayWeekEnum::MARTES     => 'Martes',
            DayWeekEnum::MIERCOLES  => 'Miercoles',
            DayWeekEnum::JUEVES     => 'Jueves',
            DayWeekEnum::VIERNES    => 'Viernes',
            DayWeekEnum::SABADO     => 'Sabado',
            DayWeekEnum::DOMINGO    => 'Domingo',

        };
    }
}
