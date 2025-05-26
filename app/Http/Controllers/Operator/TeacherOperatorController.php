<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\TeacherOperatorRequest;
use App\Http\Resources\Operator\TeacherOperatorResource;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;


class TeacherOperatorController extends Controller
{
    use HasFile;
    public function index()
    {
        $teachers = Teacher::query()
            ->select(['teachers.id', 'teachers.teacher_number', 'teachers.faculty_id', 'teachers.departement_id', 'teachers.user_id', 'teachers.academic_title', 'teachers.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('teachers.departement_id', auth()->user()->operator->departement_id)
            ->where('teachers.faculty_id', auth()->user()->operator->faculty_id)
            ->with(['user'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Teacher'));
            })
            ->paginate(request()->load ?? 10);


        $faculty_name = auth()->user()->operator->faculty->name;
        $departement_name = auth()->user()->operator->departement->name;

        return inertia('Operators/Teachers/Index', [
            'page_setting' => [
                'title' => 'Dosen',
                'subtitle' => "Menampilkan Dosen yang ada di {$faculty_name} dan program studi {$departement_name}",
            ],
            'teachers' => TeacherOperatorResource::collection($teachers)->additional([
                'meta' => [
                    'has_pages' => $teachers->hasPages(),
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
        return inertia('Operators/Teachers/Create', [
            'page_setting' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Buat Dosen baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.teachers.store')
            ]
        ]);
    }

    public function store(TeacherOperatorRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->upload_file($request, 'avatar', 'teachers'),
            ]);

            $user->teacher()->create([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,

            ]);


            DB::commit();
            $user->assignRole('Teacher');

            flashMessage(MessageType::CREATED->message('Dosen'));
            return to_route('operators.teachers.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }

    public function edit(Teacher $teacher)
    {
        return inertia('Operators/Teachers/Edit', [
            'page_setting' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Edit Dosen disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.teachers.update', $teacher)
            ],
            'teacher' => $teacher->load(['user']),
        ]);
    }

    public function update(TeacherOperatorRequest $request, Teacher $teacher)
    {
        DB::beginTransaction();
        try {

            $teacher->update([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'departement_id' => auth()->user()->operator->departement_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,

            ]);

            $teacher->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $teacher->user->password,
                'avatar' => $this->update_file($request, $teacher->user, 'avatar', 'teachers'),
            ]);



            DB::commit();

            flashMessage(MessageType::UPDATED->message('Dosen'));
            return to_route('operators.teachers.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }


    public function destroy(Teacher $teacher)
    {
        try {
            $this->delete_file($teacher->user, 'avatar');

            $teacher->delete();
            flashMessage(MessageType::DELETED->message('Dosen'));
            return to_route('operators.teachers.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }
}
