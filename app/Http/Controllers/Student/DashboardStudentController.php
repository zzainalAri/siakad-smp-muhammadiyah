<?php

namespace App\Http\Controllers\Student;

use App\Enums\FeeStatus;
use App\Enums\StudyPlanStatus;
use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\StudyPlan;
use Illuminate\Http\Request;

class DashboardStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia('Students/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => [
                'study_plans_approved' => StudyPlan::query()
                    ->where('status', StudyPlanStatus::APPROVED->value)
                    ->where('student_id', auth()->user()->student->id)
                    ->where('academic_year_id', activeAcademicYear()->id)
                    ->count(),
                'study_plans_reject' => StudyPlan::query()
                    ->where('status', StudyPlanStatus::REJECT->value)
                    ->where('student_id', auth()->user()->student->id)
                    ->where('academic_year_id', activeAcademicYear()->id)
                    ->count(),
                'total_payments' => Fee::query()->where('student_id', auth()->user()->student->id)->where('status', FeeStatus::SUCCESS->value)
                    ->with('feeGroup')->get()->sum(fn($fee) => $fee->feeGroup->amount),
            ]
        ]);
    }
}
