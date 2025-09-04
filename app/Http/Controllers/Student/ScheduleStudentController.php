<?php

namespace App\Http\Controllers\Student;

use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $student = Student::query()
            ->where('user_id', Auth::user()->id)
            ->first();


        $days = ScheduleDay::cases();
        $scheduleTable = [];
        $mobile_schedules = [];

        foreach ($student->classroom->schedules as $schedule) {
            $startTime = substr($schedule->start_time, 0, 5);
            $endTime = substr($schedule->end_time, 0, 5);

            $day = $schedule->day_of_week->value;



            $scheduleTable[$startTime][$day] = [
                'course' => $schedule->course->name,
                'code' => $schedule->course->code,
                'end_time' => $endTime,
                'teacher' => $schedule->course->teacher->user->name,
                'course_id' => $schedule->course->id,

            ];

            $mobile_schedules[] = [
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'course' => $schedule->course->name,
                'course_id' => $schedule->course->id,
                'code' => $schedule->course->code,
                'teacher' => $schedule->course->teacher->user->name
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
            'mobile_schedules' => $mobile_schedules
        ]);
    }
}
