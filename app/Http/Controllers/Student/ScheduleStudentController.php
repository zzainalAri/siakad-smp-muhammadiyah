<?php

namespace App\Http\Controllers\Student;

use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use Illuminate\Http\Request;

class ScheduleStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $studyPlan = StudyPlan::query()
            ->where('student_id', auth()->user()->student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->approved()
            ->with(['schedules'])->first();

        if (!$studyPlan) {
            flashMessage('Anda belum mengajukan krs/krs belum di setujui', 'warning');
            return to_route('students.study-plans.index');
        }

        $days = ScheduleDay::cases();
        $scheduleTable = [];


        foreach ($studyPlan->schedules as $schedule) {
            $startTime = substr($schedule->start_time, 0, 5);
            $endTime = substr($schedule->end_time, 0, 5);

            $day = $schedule->day_of_week->value;



            $scheduleTable[$startTime][$day] = [
                'course' => $schedule->course->name,
                'code' => $schedule->course->code,
                'end_time' => $endTime
            ];
        }

        $scheduleTable = collect($scheduleTable)->sortKeys();



        return inertia('Students/Schedules/Index', [
            'page_setting' => [
                'title' => 'Jadwal',
                'subtitle' => 'Menampilkan semua jadwal anda yang tersedia'
            ],
            'scheduleTable' => $scheduleTable,
            'days' => $days,
        ]);
    }
}
