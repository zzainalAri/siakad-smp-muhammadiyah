<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\Course;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class CourseController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only: ['store', 'update']),
        ];
    }

    public function index()
    {
        $courses = Course::query()
            ->select(['courses.id', 'courses.faculty_id', 'courses.departement_id', 'courses.teacher_id', 'courses.code', 'courses.semester', 'courses.name', 'courses.credit', 'courses.created_at', 'courses.academic_year_id'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'departement', 'teacher',])
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Kuliah',
                'subtitle' => 'Menampilkan semua data Mata Kuliah yang tersedia pada universitas ini'
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
                'title' => 'Tambah Matakuliah',
                'subtitle' => 'Buat Matakuliah baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.courses.store')
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
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
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => activeAcademicYear()->id,
                'code' => str()->random(10),
                'name' => $request->name,
                'credit' => $request->credit,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::CREATED->message('Mata Kuliah'));
            return to_route('admin.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }

    public function Edit(Course $course)
    {
        return inertia('Admin/Courses/Edit', [
            'page_setting' => [
                'title' => 'Edit Matakuliah',
                'subtitle' => 'Edit Matakuliah disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.courses.update', $course)
            ],
            'course' => $course,
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
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
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => activeAcademicYear()->id,
                'code' => str()->random(10),
                'name' => $request->name,
                'credit' => $request->credit,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::UPDATED->message('Mata Kuliah'));
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
            flashMessage(MessageType::DELETED->message('Mata Kuliah'));
            return to_route('admin.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.courses.index');
        }
    }
}
