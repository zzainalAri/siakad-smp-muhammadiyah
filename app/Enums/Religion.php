<?php

namespace App\Enums;

enum Religion: string
{
    case ISLAM = 'Islam';
    case KATOLIK = 'Katolik';
    case PROTESTAN = 'Protestan';
    case HINDU = 'Hindu';
    case BUDDHA = 'Buddha';
    case KONGHUCU = 'Konghucu';
    case OTHER = 'Lainnya';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
