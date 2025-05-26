<?php

namespace App\Enums;

enum ScheduleDay: string
{
    case SENIN = 'Senin';
    case SELASA = 'Selasa';
    case RABU =  'Rabu';
    case KAMIS = 'Kamis';
    case JUMAT = 'Jum\'at';
    case SABTU = 'Sabtu';
    case MINGGU = 'Minggu';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
