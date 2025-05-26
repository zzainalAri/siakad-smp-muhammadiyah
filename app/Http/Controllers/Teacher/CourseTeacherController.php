<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\CourseScheduleResource;
use App\Http\Resources\Teacher\CourseTeacherResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseTeacherController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->where('teacher_id', auth()->user()->teacher->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'departement', 'schedules'])
            ->paginate(request()->load ?? 9);

        return inertia('Teachers/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Kuliah',
                'subtitle' => 'Menampilkan semua data matakuliah yang anda ampu'
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
                'title' => "Detail Mata Kuliah {$course->name}",
                'subtitle' => 'Menampilkan Detail Mata Kuliah yang anda ampu'
            ],
            'course' => new CourseScheduleResource($course->load(['schedules', 'departement', 'academicYear', 'faculty']))
        ]);
    }
}
