<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Operator\StudyResultOperatorResource;
use App\Models\Student;
use App\Models\StudyResult;
use Illuminate\Http\Request;

class StudyResultOperatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Student $student)
    {
        $studyResults = StudyResult::query()
            ->select(['id', 'student_id', 'academic_year_id', 'gpa', 'semester', 'created_at', 'student_id'])
            ->where('student_id', $student->id)
            ->with(['student', 'grades', 'academicYear'])
            ->paginate(10);

        return inertia('Operators/Students/StudyResults/Index', [
            'page_setting' => [
                'title' => "Kartu Hasil Studi Mahasiswa {$student->name}",
                'subtitle' => "Menampilkan semua data kartu hasil studi mahasiswa {$student->name}"
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
            'student' => $student,

        ]);
    }
}
