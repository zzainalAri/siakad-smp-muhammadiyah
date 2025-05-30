<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardOperatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia('Operators/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => [
                'students' => Student::query()
                    ->where('faculty_id', auth()->user()->operator->faculty_id)
                    ->count(),
                'teachers' => Teacher::query()
                    ->where('faculty_id', auth()->user()->operator->faculty_id)
                    ->count(),
                'classrooms' => Classroom::query()
                    ->where('faculty_id', auth()->user()->operator->faculty_id)
                    ->count(),
                'courses' => Course::query()
                    ->where('faculty_id', auth()->user()->operator->faculty_id)
                    ->count(),
            ]
        ]);
    }
}
