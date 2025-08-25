<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FeeStatus;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FeeResource;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {

        $students = Student::query()
            ->with([
                'fees' => function ($query) {
                    $query->whereHas('academicYear', fn($query) => $query->where('is_active', true))
                        ->with(['academicYear' => fn($query) => $query->where('is_active', true)]);
                },

                'user',
                'classroom'
            ])
            ->where('status', StudentStatus::ACTIVE->value)
            ->withSum('fees as total_fees', 'amount') // total semua tagihan
            ->withSum([
                'fees as paid_fees_sum' => fn($q) => $q->where('status', FeeStatus::PAID->value)
            ], 'amount') // total yg sudah dibayar
            ->withSum([
                'fees as unpaid_fees_sum' => fn($q) => $q->where('status', FeeStatus::UNPAID->value)
            ], 'amount') // total yg belum dibayar
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);


        return inertia('Admin/Fees/Index', [
            'page_setting' => [
                'title' => 'SPP',
                'subtitle' => 'Menampilkan semua informasi SPP siswa yang tersedia di SMP Muhammadiyah'
            ],
            'students' => StudentResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
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
