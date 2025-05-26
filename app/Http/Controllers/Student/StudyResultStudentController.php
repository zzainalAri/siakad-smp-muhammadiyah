<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Operator\StudyResultOperatorResource;
use App\Models\Student;
use App\Models\StudyResult;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class StudyResultStudentController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('checkActiveAcademicYear'),
            new Middleware('checkFeeStudent'),

        ];
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()


    {
        $student = Auth::user()->student;

        $studyResults = StudyResult::query()
            ->select(['id', 'student_id', 'academic_year_id', 'gpa', 'semester', 'created_at', 'student_id'])
            ->where('student_id', $student->id)
            ->with(['student', 'grades', 'academicYear'])
            ->paginate(10);

        return inertia('Students/StudyResults/Index', [
            'page_setting' => [
                'title' => "Kartu Hasil Studi Saya",
                'subtitle' => "Menampilkan semua data kartu hasil anda"
            ],
            'studyResults' => StudyResultOperatorResource::collection($studyResults)->additional([
                'meta' => [
                    'has_pages' => $studyResults->hasPages()
                ],

            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'load' => 10
            ],

        ]);
    }
}
