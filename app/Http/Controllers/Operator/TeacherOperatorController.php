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
            ->select(['teachers.id', 'teachers.nip', 'teachers.level_id', 'teachers.user_id', 'teachers.academic_title', 'teachers.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('teachers.level_id', auth()->user()->operator->level_id)
            ->with(['user'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Teacher'));
            })
            ->paginate(request()->load ?? 10);


        $level_name = auth()->user()->operator->level->name;

        return inertia('Operators/Teachers/Index', [
            'page_setting' => [
                'title' => 'Guru',
                'subtitle' => "Menampilkan Guru yang ada di {$level_name}",
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
                'title' => 'Tambah Guru',
                'subtitle' => 'Buat Guru baru disini. Klik simpan setelah selesai',
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
                'level_id' => auth()->user()->operator->level_id,
                'nip' => $request->nip,
                'academic_title' => $request->academic_title,

            ]);


            DB::commit();
            $user->assignRole('Teacher');

            flashMessage(MessageType::CREATED->message('Guru'));
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
                'title' => 'Edit Guru',
                'subtitle' => 'Edit Guru disini. Klik simpan setelah selesai',
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
                'level_id' => auth()->user()->operator->level_id,
                'nip' => $request->nip,
                'academic_title' => $request->academic_title,

            ]);

            $teacher->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $teacher->user->password,
                'avatar' => $this->update_file($request, $teacher->user, 'avatar', 'teachers'),
            ]);



            DB::commit();

            flashMessage(MessageType::UPDATED->message('Guru'));
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
            flashMessage(MessageType::DELETED->message('Guru'));
            return to_route('operators.teachers.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.teachers.index');
        }
    }
}
