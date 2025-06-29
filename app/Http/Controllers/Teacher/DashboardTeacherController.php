<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardTeacherController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia('Teachers/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => [
                'courses' => Course::query()
                    ->where('teacher_id', auth()->user()->teacher->id)->count(),
                'classrooms' => Classroom::query()
                    ->whereHas('schedules.course', fn($query) => $query->where('teacher_id', auth()->user()->teacher->id))->count(),
                'schedules' => Schedule::query()
                    ->whereHas('course', fn($query) => $query->where('teacher_id', auth()->user()->teacher->id))->count(),
            ]
        ]);
    }
}
