<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\CourseOperatorRequest;
use App\Http\Resources\Operator\CourseOperatorResource;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Throwable;

class CourseOperatorController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->select(['courses.id', 'courses.teacher_id', 'courses.code', 'courses.semester', 'courses.name', 'courses.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('courses.level_id', auth()->user()->operator->level_id)
            ->with(['teacher'])
            ->paginate(request()->load ?? 10);

        $level_name = auth()->user()->operator->level->name;

        return inertia('Operators/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Pelajaran',
                'subtitle' => "Menampilkan Mata Pelajaran yang ada di {$level_name} "
            ],
            'courses' => CourseOperatorResource::collection($courses)->additional([
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
        return inertia('Operators/Courses/Create', [
            'page_setting' => [
                'title' => 'Tambah Mata Pelajaran',
                'subtitle' => 'Buat Mata Pelajaran baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.courses.store')
            ],
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->where('level_id', auth()->user()->operator->level_id)
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user?->name,
                ]),
        ]);
    }

    public function store(CourseOperatorRequest $request)
    {
        try {
            Course::create([
                'level_id' => auth()->user()->operator->level_id,
                'teacher_id' => $request->teacher_id,
                'code' => str()->random(10),
                'name' => $request->name,
                'semester' => $request->semester
            ]);

            flashMessage(MessageType::CREATED->message('Mata Pelajaran'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }

    public function edit(Course $course)
    {
        return inertia('Operators/Courses/Edit', [
            'page_setting' => [
                'title' => 'Edit Mata Pelajaran',
                'subtitle' => 'Edit Mata Pelajaran disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.courses.update', $course)
            ],
            'course' => $course,
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->where('level_id', auth()->user()->operator->level_id)
                ->whereHas('user', function ($query) {
                    $query->whereHas('roles', fn($query) => $query->where('name', 'Teacher'))->orderBy('name');
                })
                ->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->user?->name,
                ]),
        ]);
    }

    public function update(CourseOperatorRequest $request, Course $course)
    {
        try {
            $course->update([
                'level_id' => auth()->user()->operator->level_id,
                'teacher_id' => $request->teacher_id,
                'code' => str()->random(10),
                'name' => $request->name,
                'semester' => $request->semester
            ]);

            flashMessage(MessageType::UPDATED->message('Mata Pelajaran'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
            flashMessage(MessageType::DELETED->message('Mata Pelajaran'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }
}