<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Operator\FeeOperatorResource;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;

class FeeOperatorController extends Controller
{
    public function __invoke(Student $student)
    {
        $fees = Fee::query()
            ->select(['fees.id', 'fees.fee_code', 'fees.student_id', 'fees.fee_group_id', 'fees.academic_year_id', 'fees.semester', 'fees.status', 'fees.created_at'])
            ->where('student_id', $student->id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['student', 'feeGroup', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return inertia('Operators/Students/Fees/Index', [
            'page_setting' => [
                'title' => 'Pembayaran',
                'subtitle' => "Menampilkan semua pembayaran Siswa {$student->name}"
            ],
            'fees' => FeeOperatorResource::collection($fees)->additional([
                'meta' => [
                    'has_pages' => $fees->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ],
            'student' => $student,
        ]);
    }
}
