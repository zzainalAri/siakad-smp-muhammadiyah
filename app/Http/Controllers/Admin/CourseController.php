<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\Course;
use App\Models\Level;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class CourseController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return []; 
    }

    public function index()
    {
        $courses = Course::query()
            ->select(['courses.id', 'courses.level_id', 'courses.teacher_id', 'courses.code', 'courses.semester', 'courses.name', 'courses.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['level', 'teacher',])
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Pelajaran',
                'subtitle' => 'Menampilkan semua data Mata Pelajaran yang tersedia di SMP Muhammadiyah ini'
            ],
            'courses' => CourseResource::collection($courses)->additional([
                'meta' => [
                    'has_pages' => $courses->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Courses/Create', [
            'page_setting' => [
                'title' => 'Tambah Mata pelajaran',
                'subtitle' => 'Buat Mata pelajaran baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.courses.store')
            ],
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user?->name,
                ]),
        ]);
    }

    public function store(CourseRequest $request)
    {
        try {
            Course::create([
                'level_id' => $request->level_id,
                'teacher_id' => $request->teacher_id,
                'code' => str()->random(10),
                'name' => $request->name,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::CREATED->message('Mata Pelajaran'));
            return to_route('admin.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }

    public function edit(Course $course)
    {
        return inertia('Admin/Courses/Edit', [
            'page_setting' => [
                'title' => 'Edit Mata Pelajaran',
                'subtitle' => 'Edit Mata Pelajaran disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.courses.update', $course)
            ],
            'course' => $course,
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user?->name,
                ]),
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            $course->update([
                'level_id' => $request->level_id,
                'teacher_id' => $request->teacher_id,
                'code' => str()->random(10),
                'name' => $request->name,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::UPDATED->message('Mata Pelajaran'));
            return to_route('admin.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }

    public function destroy(Course $course)
    {
        try {

            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata Pelajaran'));
            return to_route('admin.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }
}