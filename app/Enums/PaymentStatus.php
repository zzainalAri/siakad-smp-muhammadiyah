<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'Tertunda';
    case SUCCESS = 'Sukses';
    case FAILED = 'Gagal';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
