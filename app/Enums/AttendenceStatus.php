<?php

namespace App\Enums;

enum AttendenceStatus: string
{
    case ATTENDED = 'Hadir';
    case PERMIT = 'Izin';
    case SICK = 'Sakit';
    case ALPA = 'Alpha';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
