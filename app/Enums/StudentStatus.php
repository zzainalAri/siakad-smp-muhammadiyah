<?php

namespace App\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'Aktif';
    case INACTIVE = 'Tidak Aktif';
    case TRANSFER = 'Pindah';
    case GRUADUATED = 'Lulus';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
