<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ClassroomOperatorRequest;
use App\Http\Resources\Operator\ClassroomOperatorResource;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Throwable;

class ClassroomOperatorController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::query()
            ->select(['id', 'name', 'academic_year_id', 'level_id', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('level_id', auth()->user()->operator->level_id)
            ->with(['academicYear', 'level'])
            ->paginate(request()->load ?? 10);

        $level_name = auth()->user()->operator->level->name;

        return inertia('Operators/Classrooms/Index', [
            'page_setting' => [
                'title' => 'Kelas',
                'subtitle' => "Menampilkan Kelas yang ada di {$level_name} "
            ],
            'classrooms' => ClassroomOperatorResource::collection($classrooms)->additional([
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
        return inertia('Operators/Classrooms/Create', [
            'page_setting' => [
                'title' => 'Tambah Kelas',
                'subtitle' => 'Buat Kelas baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.classrooms.store')
            ],
            'academic_year' => activeAcademicYear(),
            'level' => auth()->user()->operator->level,
        ]);
    }


    public function store(ClassroomOperatorRequest $request)
    {
        try {
            $levelName = auth()->user()->operator->level->name;
            $fullClassName = $levelName . ' ' . $request->name;

            Classroom::create([
                'name' =>  $fullClassName,
                'academic_year_id' => activeAcademicYear()->id,
                'level_id' => auth()->user()->operator->level_id,
            ]);

            flashMessage(MessageType::CREATED->message('Kelas'));
            return to_route('operators.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }

    public function edit(Classroom $classroom)
    {
        $classroom->load(['academicYear', 'level']);
        $levelName = $classroom->level->name . ' ';
        $suffix = str_replace($levelName, '', $classroom->name);

        return inertia('Operators/Classrooms/Edit', [
            'page_setting' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Edit Kelas disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.classrooms.update', $classroom)
            ],
            'classroom' => $classroom,
            'suffix' => $suffix,
        ]);
    }

    public function update(ClassroomOperatorRequest $request, Classroom $classroom)
    {
        try {
            $levelName = auth()->user()->operator->level->name;
            $fullClassName = $levelName . ' ' . $request->name;

            $classroom->update([
                'name' =>  $fullClassName,
                'academic_year_id' => activeAcademicYear()->id,
                'level_id' => auth()->user()->operator->level_id,
            ]);

            flashMessage(MessageType::UPDATED->message('Kelas'));
            return to_route('operators.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }

    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();
            flashMessage(MessageType::DELETED->message('Kelas'));
            return to_route('operators.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }
}