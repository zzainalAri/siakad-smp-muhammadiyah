<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\CourseScheduleResource;
use App\Http\Resources\Teacher\CourseTeacherResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseTeacherController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->where('teacher_id', Auth::user()->teacher->id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['level', 'schedules'])
            ->paginate(request()->load ?? 9);

        return inertia('Teachers/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Pelajaran',
                'subtitle' => 'Menampilkan semua data mata pelajaran yang anda ajar'
            ],
            'courses' => CourseTeacherResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
                ],


            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 9
            ]
        ]);
    }

    public function show(Course $course)
    {
        return inertia('Teachers/Courses/Show', [
            'page_setting' => [
                'title' => "Detail Mata Pelajaran {$course->name}",
                'subtitle' => 'Menampilkan Detail Mata Pelajaran yang anda ampu'
            ],
            'course' => new CourseScheduleResource($course->load(['schedules', 'level',]))
        ]);
    }
}
