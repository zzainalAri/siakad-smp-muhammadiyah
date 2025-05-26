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
            ->select(['courses.id', 'courses.teacher_id', 'courses.code', 'courses.semester', 'courses.name', 'courses.credit', 'courses.created_at', 'courses.academic_year_id'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('courses.faculty_id', auth()->user()->operator->faculty_id)
            ->where('courses.departement_id', auth()->user()->operator->departement_id)
            ->with(['faculty', 'departement', 'teacher',])
            ->paginate(request()->load ?? 10);

        $faculty_name = auth()->user()->operator->faculty->name;
        $departement_name = auth()->user()->operator->departement->name;


        return inertia('Operators/Courses/Index', [
            'page_setting' => [
                'title' => 'Mata Kuliah',
                'subtitle' => "Menampilkan Mata Kuliah yang ada di {$faculty_name} dan program studi {$departement_name}"
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
                'title' => 'Tambah Matakuliah',
                'subtitle' => 'Buat Matakuliah baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.courses.store')
            ],
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
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
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => activeAcademicYear()->id,
                'code' => str()->random(10),
                'name' => $request->name,
                'credit' => $request->credit,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::CREATED->message('Mata Kuliah'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }

    public function Edit(Course $course)
    {
        return inertia('Operators/Courses/Edit', [
            'page_setting' => [
                'title' => 'Edit Matakuliah',
                'subtitle' => 'Edit Matakuliah disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.courses.update', $course)
            ],
            'course' => $course,
            'teachers' => Teacher::query()->select(['id', 'user_id'])
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->where('departement_id', auth()->user()->operator->departement_id)
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
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => activeAcademicYear()->id,
                'code' => str()->random(10),
                'name' => $request->name,
                'credit' => $request->credit,
                'semester' => $request->semester
            ]);


            flashMessage(MessageType::UPDATED->message('Mata Kuliah'));
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
            flashMessage(MessageType::DELETED->message('Mata Kuliah'));
            return to_route('operators.courses.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.courses.index');
        }
    }
}
