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
            ->select(['id', 'name', 'academic_year_id', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('faculty_id', auth()->user()->operator->faculty_id)
            ->where('departement_id', auth()->user()->operator->departement_id)
            ->with(['academicYear'])
            ->paginate(request()->load ?? 10);


        $faculty_name = auth()->user()->operator->faculty->name;
        $departement_name = auth()->user()->operator->departement->name;


        return inertia('Operators/Classrooms/Index', [
            'page_setting' => [
                'title' => 'Kelas',
                'subtitle' => "Menampilkan Mahasiswa yang ada di {$faculty_name} dan program studi {$departement_name}"
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
        ]);
    }

    public function store(ClassroomOperatorRequest $request)
    {
        try {
            Classroom::create([
                'name' =>  $request->name,
                'departement_id' => auth()->user()->operator->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'faculty_id' => auth()->user()->operator->faculty_id,
            ]);

            flashMessage(MessageType::CREATED->message('Kelas'));
            return to_route('operators.classrooms.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.classrooms.index');
        }
    }

    public function Edit(Classroom $classroom)
    {
        return inertia('Operators/Classrooms/Edit', [
            'page_setting' => [
                'title' => 'Edit Kelas',
                'subtitle' => 'Edit Kelas disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.classrooms.update', $classroom)
            ],
            'classroom' => $classroom,
        ]);
    }

    public function update(ClassroomOperatorRequest $request, Classroom $classroom)
    {
        try {
            $classroom->update([
                'name' =>  $request->name,
                'departement_id' => auth()->user()->operator->departement_id,
                'academic_year_id' => activeAcademicYear()->id,
                'faculty_id' => auth()->user()->operator->faculty_id,
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
