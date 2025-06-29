<?php

namespace App\Http\Controllers\Operator;

use App\Enums\Gender;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\StudentOperatorRequest;
use App\Http\Resources\Operator\StudentOperatorResource;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Traits\HasFile;
use App\Enums\StudentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class StudentOperatorController extends Controller
{
    use HasFile;

    public function index()
    {
        $students = Student::query()
            ->select(['students.id', 'students.nisn', 'students.level_id', 'students.classroom_id', 'students.user_id', 'students.status', 'students.gender', 'students.batch', 'students.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'classroom'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) => $query->where('name', 'Student'));
            })
            ->where('students.level_id', auth()->user()->operator->level_id)
            ->paginate(request()->load ?? 10);

        $level_name = auth()->user()->operator->level->name;

        return inertia('Operators/Students/Index', [
            'page_setting' => [
                'title' => 'Siswa',
                'subtitle' => "Menampilkan Siswa yang ada di {$level_name}"
            ],
            'students' => StudentOperatorResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
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
        return inertia('Operators/Students/Create', [
            'page_setting' => [
                'title' => 'Tambah Siswa',
                'subtitle' => 'Buat Siswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.students.store')
            ],
            'classrooms' => Classroom::query()
                ->select(['id', 'name'])
                ->where('level_id', auth()->user()->operator->level_id)
                ->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'statuses' => StudentStatus::options(),
            'genders' => Gender::options(),
        ]);
    }

    public function store(StudentOperatorRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->upload_file($request, 'avatar', 'students'),
            ]);

            $user->student()->create([
                'level_id' => auth()->user()->operator->level_id,
                'classroom_id' => $request->classroom_id,
                'nisn' => $request->nisn,
                'status' => $request->status,
                'gender' => $request->gender,
                'batch' => $request->batch,
            ]);

            DB::commit();
            $user->assignRole('Student');

            flashMessage(MessageType::CREATED->message('Siswa'));
            return to_route('operators.students.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }

    public function edit(Student $student)
    {
        return inertia('Operators/Students/Edit', [
            'page_setting' => [
                'title' => 'Edit Siswa',
                'subtitle' => 'Edit Siswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.students.update', $student)
            ],
            'student' => $student->load(['user']),
            'classrooms' => Classroom::query()
                ->select(['id', 'name'])
                ->where('level_id', auth()->user()->operator->level_id)
                ->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'statuses' => StudentStatus::options(),
            'genders' => Gender::options(),
        ]);
    }

    public function update(StudentOperatorRequest $request, Student $student)
    {
        DB::beginTransaction();
        try {
            $student->update([
                'level_id' => auth()->user()->operator->level_id,
                'classroom_id' => $request->classroom_id,
                'nisn' => $request->nisn,
                'status' => $request->status,
                'gender' => $request->gender,
                'batch' => $request->batch,
            ]);

            $student->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
                'avatar' => $this->update_file($request, $student->user, 'avatar', 'students'),
            ]);

            DB::commit();

            flashMessage(MessageType::UPDATED->message('Siswa'));
            return to_route('operators.students.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }

    public function destroy(Student $student)
    {
        try {
            $this->delete_file($student->user, 'avatar');
            $student->delete();
            flashMessage(MessageType::DELETED->message('Siswa'));
            return to_route('operators.students.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.students.index');
        }
    }
}