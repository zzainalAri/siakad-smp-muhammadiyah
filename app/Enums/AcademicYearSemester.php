<?php

namespace App\Enums;

enum AcademicYearSemester: string
{
    case ODD = 'Ganjil';
    case EVEN = 'GENAP';


    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
