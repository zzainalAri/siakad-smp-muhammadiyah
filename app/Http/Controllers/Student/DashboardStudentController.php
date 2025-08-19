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
            ],
            'count' => [
                'total_payments' => Fee::query()->where('student_id', auth()->user()->student->id)->where('status', FeeStatus::SUCCESS->value)
                    ->with('feeGroup')->get()->sum(fn($fee) => $fee->feeGroup->amount),
            ]
        ]);
    }
}
