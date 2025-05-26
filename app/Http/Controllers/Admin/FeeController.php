<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $fees = Fee::query()->select(['fees.id', 'fees.student_id', 'fees.fee_group_id', 'fees.semester', 'fees.status', 'fees.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Fees/Index', [
            'page_setting' => [
                'title' => 'Uang Kuliah Tunggal',
                'subtitle' => 'Menampilkan semua data Uang Kuliah Tunggal yang tersedia pada universitas ini'
            ],
            'fees' => FeeResource::collection($fees)->additional([
                'meta' => [
                    'has_pages' => $fees->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }
}
