<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Level;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function __invoke()

    {
        return inertia('Admin/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => array_merge([
                'levels' => Level::count(),
                'classrooms' => Classroom::count(),
                'courses' => Course::count(),
                'students' => Student::count(),
                'teachers' => Teacher::count(),
            ], Auth::user()->hasRole('Teacher') ? [
                'teacher_courses' => Course::where('teacher_id', Auth::user()->teacher->id)->count(),
                'teacher_classrooms' => Classroom::whereHas(
                    'schedules.course',
                    fn($query) =>
                    $query->where('teacher_id', Auth::user()->teacher->id)
                )->count(),
                'teacher_schedules' => Schedule::whereHas(
                    'course',
                    fn($query) =>
                    $query->where('teacher_id', Auth::user()->teacher->id)
                )->count(),
            ] : [])
        ]);
    }
}
