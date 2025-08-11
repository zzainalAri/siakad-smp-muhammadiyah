<?php

namespace App\Enums;

enum StudentRegistrationStatus: string
{
    case PENDING = 'Menunggu Konfirmasi';
    case APPROVED = 'Disetujui';
    case REJECTED = 'Ditolak';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
