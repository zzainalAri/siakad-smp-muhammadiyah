<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClassroomRequest;
use App\Http\Resources\Admin\ClassroomResource;
use App\Models\Classroom;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class ClassroomController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return []; 
    }

    public function index()
    {
        $classrooms = Classroom::query()
            ->select(['id', 'name', 'level_id', 'academic_year_id', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['level', 'academicYear'])
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Classrooms/Index', [
            'page_setting' => [
                'title' => 'Kelas',
                'subtitle' => 'Menampilkan semua data Kelas yang tersedia di SMP Muhammadiyah ini'
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
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
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
                'academic_year_id' => activeAcademicYear()->id,
                'level_id' => $request->level_id,
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
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
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
                'level_id' => $request->level_id,
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
