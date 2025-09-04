<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courses = Course::query()
            ->where('teacher_id', Auth::user()->teacher->id)
            ->pluck('id');

        $schedules = Schedule::query()
            ->whereIn('course_id', $courses)
            ->get();

        $days = ScheduleDay::cases();
        $scheduleTable = [];

        $mobile_schedules = [];


        foreach ($schedules as $schedule) {
            $startTime = substr($schedule->start_time, 0, 5);
            $endTime = substr($schedule->end_time, 0, 5);
            $day = $schedule->day_of_week->value;


            $mobile_schedules[] = [
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'course' => $schedule->course->name,
                'course_code' => $schedule->course->code,
                'classroom' => $schedule->classroom->name,

            ];


            $scheduleTable[$startTime][$day] = [
                'course' => $schedule->course->name,
                'classroom' => $schedule->classroom->name,
                'classroom_id' => $schedule->classroom->id,
                'course_id' => $schedule->course->id,
                'end_time' => $endTime,

            ];
        }

        $scheduleTable = collect($scheduleTable)->sortKeys();

        return inertia('Teachers/Schedules/Index', [
            'page_setting' => [
                'title' => 'Jadwal',
                'subtitle' => 'Menampilkan semua jadwal mengajar anda'
            ],
            'scheduleTable' => $scheduleTable,
            'days' => $days,
            'mobile_schedules' => $mobile_schedules,
        ]);
    }
}
