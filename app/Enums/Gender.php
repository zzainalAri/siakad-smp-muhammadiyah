<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'Laki-Laki';
    case FEMALE = 'Perempuan';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
