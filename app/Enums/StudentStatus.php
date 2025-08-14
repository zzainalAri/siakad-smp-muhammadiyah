<?php

namespace App\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'Aktif';
    case INACTIVE = 'Tidak Aktif';
    case TRANSFER = 'Pindahan dari sekolah lain';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}