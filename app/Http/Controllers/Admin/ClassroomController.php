<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomRequest;
use App\Http\Resources\Admin\ClassroomResource;
use App\Models\Classroom;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class ClassroomController extends Controller implements HasMiddleware


{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only: ['store', 'update']),
        ];
    }

    public function index()
    {
        $classrooms = Classroom::query()
            ->select(['id', 'name', 'faculty_id', 'departement_id', 'academic_year_id', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['faculty', 'departement', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Classrooms/Index', [
            'page_setting' => [
                'title' => 'Kelas',
                'subtitle' => 'Menampilkan semua data Kelas yang tersedia pada universitas ini'
            ],
            'classrooms' => ClassroomResource::collection($classrooms)->additional([
                'meta' => [
                    'has_pages' => $classrooms->hasPages(),
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
        return inertia('Admin/Classrooms/Create', [
            'page_setting' => [
                'title' => 'Tambah Kelas',
                'subtitle' => 'Buat Kelas baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.classrooms.store')
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(ClassroomRequest $request)
    {
        try {
            Classroom::create([
                'name' =>  $request->name,
                'departement_id' => $request->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'faculty_id' => $request->faculty_id,
            ]);

            flashMessage(MessageType::CREATED->message('Kelas'));
            return to_route('admin.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');
        }
    }

    public function Edit(Classroom $classroom)
    {
        return inertia('Admin/Classrooms/Edit', [
            'page_setting' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Edit Kelas disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.classrooms.update', $classroom)
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'classroom' => $classroom,
        ]);
    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        try {
            $classroom->update([
                'name' =>  $request->name,
                'departement_id' => $request->departement_id,
                'faculty_id' => $request->faculty_id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            flashMessage(MessageType::UPDATED->message('Kelas'));
            return to_route('admin.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');
        }
    }


    public function destroy(Classroom $classroom)
    {
        try {

            $classroom->delete();
            flashMessage(MessageType::DELETED->message('Kelas'));
            return to_route('admin.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.classrooms.index');
        }
    }
}
