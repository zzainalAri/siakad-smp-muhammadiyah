<?php

namespace App\Enums;

enum FeeStatus: string
{
    case PAID = 'Sudah Bayar';
    case UNPAID = 'Belum Bayar';
    case OVERDUE = 'Jatuh Tempo';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
