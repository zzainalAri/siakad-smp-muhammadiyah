<?php

namespace App\Http\Controllers\Student;

use App\Enums\FeeStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentResource;
use App\Http\Resources\Student\FeeStudentResource;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeStudentController extends Controller
{
    public function __invoke()
    {

        $student = Student::query()
            ->with([
                'fees' => function ($query) {
                    $query->whereHas('academicYear', fn($query) => $query->where('is_active', true))
                        ->with(['payments', 'academicYear' => fn($query) => $query->where('is_active', true)]);
                },

                'user' => fn($query) => $query->where('id', Auth::user()->id),
                'classroom'
            ])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Student'))->where('id', Auth::user()->id);
            })
            ->withSum('fees as total_fees', 'amount') // total semua tagihan
            ->withSum([
                'fees as paid_fees_sum' => fn($q) => $q->where('status', FeeStatus::PAID->value)
            ], 'amount') // total yg sudah dibayar
            ->withSum([
                'fees as unpaid_fees_sum' => fn($q) => $q->where('status', FeeStatus::UNPAID->value)
            ], 'amount') // total yg belum dibayar
            ->get();

        $payments = Payment::query()
            ->where('payments.student_id', Auth::user()->student->id)
            ->with(['fee'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->paginate ?? 10);



        return inertia('Students/Fees/Index', [
            'page_setting' => [
                'title' => 'Pembayaran',
                'subtitle' => 'Menampilkan semua data pembayaran spp yang tersedia'
            ],
            'payments' => PaymentResource::collection($payments)->additional([
                'meta' => [
                    'has_pages' => $payments->hasPages(),
                ],
            ]),
            'students' => $student,
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }
}
