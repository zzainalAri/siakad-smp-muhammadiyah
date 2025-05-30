<?php

namespace App\Http\Controllers\Student;

use App\Enums\MessageType;
use App\Enums\StudyPlanStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudyPlanStudentRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Http\Resources\Student\StudyPlanScheduleStudentResource;
use App\Http\Resources\Student\StudyPlanStudentResource;
use App\Models\Schedule;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudyPlanStudentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('checkActiveAcademicYear', except: ['index']),
            new Middleware('checkFeeStudent', except: ['index']),
        ];
    }


    public function index()
    {
        $studyPlans = StudyPlan::query()
            ->select(['id', 'student_id', 'academic_year_id', 'status', 'created_at'])
            ->where('student_id', auth()->user()->student->id)
            ->with(['academicYear'])
            ->latest()
            ->paginate(10);


        return inertia('Students/StudyPlans/Index', [
            'page_setting' => [
                'title' => 'Kartu Rencana Studi',
                'subtitle' => 'Menampilkan semua kartu rencana studi anda',
            ],
            'studyPlans' => StudyPlanStudentResource::collection($studyPlans)->additional([
                'meta' => [
                    'has_pages' => $studyPlans->hasPages()
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }

    public function create()
    {
        if (!activeAcademicYear()) return back();

        $schedules = Schedule::query()
            ->where('schedules.faculty_id', auth()->user()->student->faculty_id)
            ->where('schedules.academic_year_id', activeAcademicYear()->id)
            ->with(['course', 'classroom'])
            ->withCount(['studyPlans as taken_quota' => fn($query) => $query->where('academic_year_id', activeAcademicYear()->id)])
            ->leftJoin('classrooms', 'schedules.classroom_id', '=', 'classrooms.id')
            ->orderBy('classrooms.name')
            ->get();

        if ($schedules->isEmpty()) {
            flashMessage('Tidak ada jadwal tersedia...', 'warning');
            return to_route('students.study-plans.index');
        }

        $studyPlan = StudyPlan::query()
            ->where('student_id', auth()->user()->student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->where('semester', auth()->user()->student->semester)
            ->where('status', '!=', StudyPlanStatus::REJECT)
            ->exists();

        if ($studyPlan) {
            flashMessage('Anda sudah mengajukan KRS', 'warning');
            return to_route('students.study-plans.index');
        }


        return inertia('Students/StudyPlans/Create', [
            'page_setting' => [
                'title' => 'Tambah kartu rencana studi',
                'subtitle' => 'Harap pilih matakuliah yang sesuai dengan kelas anda',
                'method' => 'POST',
                'action' => route('students.study-plans.store')
            ],
            'schedules' => ScheduleResource::collection($schedules),
        ]);
    }

    public function store(StudyPlanStudentRequest $request)
    {
        try {
            DB::beginTransaction();
            $studyPlan = StudyPlan::create([
                'student_id' => auth()->user()->student->id,
                'semester' => auth()->user()->student->semester,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            $studyPlan->schedules()->attach($request->schedule_id);
            DB::commit();
            flashMessage('Berhasil Mengajukan KRS');
            return to_route('students.study-plans.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('students.study-plans.index');
        }
    }

    public function show(StudyPlan $studyPlan)
    {
        return inertia('Students/StudyPlans/Show', [
            'page_setting' => [
                'title' => 'Detail Kartu rencana studi',
                'subtitle' => 'Kartu rencana yang sudah anda ajukan sebelumnya'
            ],
            'studyPlan' => new StudyPlanScheduleStudentResource($studyPlan->load('schedules'))
        ]);
    }
}
