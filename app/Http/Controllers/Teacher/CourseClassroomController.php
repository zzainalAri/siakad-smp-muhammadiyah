<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\AttendenceStatus;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\CourseClassroomRequest;
use App\Http\Resources\Teacher\CourseStudentClassroomResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudyResult;
use App\Models\StudyResultGrade;
use App\Traits\CalculateFinalScore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CourseClassroomController extends Controller
{
    use CalculateFinalScore;

    public function index(Course $course, Classroom $classroom)
    {
        $schedule = Schedule::query()
            ->where('classroom_id', $classroom->id)
            ->where('course_id', $course->id)
            ->where('level_id', $course->level_id)
            ->firstOrFail();

        $section = Section::query()
            ->where('schedule_id', $schedule->id)
            ->where('meeting_number', request()->get('meetingNumber', 1))
            ->first();

        $students = Student::query()
            ->with([
                'user',
                'attendances' => fn($query) => $query->where('section_id', $section?->id),
                'grades' => fn($query) => $query->with(['section'])->where('course_id', $course->id)->where('section_id', $section->id)
            ])
            ->where('level_id', $classroom->level_id)
            ->where('classroom_id', $classroom->id)
            ->filter(request()->only(['search']))
            ->whereHas('user.roles', fn($query) => $query->where('name', 'Student'))
            ->get();

        $sections = Section::query()
            ->where('schedule_id', $schedule->id)
            ->get();

        return inertia('Teachers/Classrooms/Index', [
            'page_setting' => [
                'title'    => "Kelas {$classroom->name} - Mata Pelajaran {$course->name}",
                'subtitle' => 'Menampilkan data Siswa',
                'method'   => 'PUT',
                'action'   => route('teachers.classrooms.sync', [$course, $classroom]),
            ],
            'students'          => CourseStudentClassroomResource::collection($students),
            'sections'          => $sections,
            'course'            => $course,
            'classroom'         => $classroom,
            'attendanceStatuses' => AttendenceStatus::options(),
            'state'             => [
                'search' => request()->search ?? '',
            ],
        ]);
    }



    public function calculateGPA($studentId)
    {
        $student = Student::query()
            ->where('id', $studentId)->first();


        $studyResult = StudyResult::query()
            ->where('student_id', $studentId)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', $student->semester)->first();


        if (!$studyResult) {
            return 0;
        }

        $studyResultGrades = StudyResultGrade::query()
            ->where('study_result_id', $studyResult->id)->get();

        $totalScore = 0;
        $totalWeight = 0;


        foreach ($studyResultGrades as $grade) {
            $finalScore = min($grade->grade, 100);
            $gpaScore = ($finalScore / 100) * 4;
            $weight = $grade->weight_of_value;

            $totalScore += $gpaScore * $weight;
            $totalWeight += $weight;
        }

        if ($totalWeight > 0) {
            return min(round($totalScore / $totalWeight, 2), 4);
        }

        return 0;
    }

    public function updateGPA($student_id)
    {
        $student = Student::query()
            ->where('id', $student_id)->first();


        $gpa = $this->calculateGPA($student->id);


        $studyResult = StudyResult::query()
            ->where('student_id', $student_id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', $student->semester)
            ->first();

        if ($studyResult) {
            $studyResult->update([
                'gpa' => $gpa,
            ]);
        }
    }

    public function sync(Course $course, Classroom $classroom, CourseClassroomRequest $request)
    {
        try {
            DB::beginTransaction();




            foreach ($request->attendances as $att) {
                Attendance::updateOrInsert(
                    [
                        'student_id' => $att['student_id'],
                        'section_id' => $att['section_id'],
                    ],
                    [
                        'status' => $att['status'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            foreach ($request->grades as $grade) {
                Grade::updateOrInsert(
                    [
                        'student_id' => $grade['student_id'],
                        'section_id' => $grade['section_id'],
                        'course_id' => $grade['course_id'],
                        'category'  => $grade['category'],
                    ],
                    [
                        'grade' => $grade['grade'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }


            DB::commit();

            flashMessage('Berhasil melakukan perubahan');

            return back();
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }
}
