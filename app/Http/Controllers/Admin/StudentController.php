<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Student;
use App\Models\User;
use App\Traits\HasFile;
use App\Enums\Gender;
use App\Enums\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class StudentController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [];
    }


    use HasFile;
    public function index()
    {
        $students = Student::query()
            ->select(['students.id', 'students.nisn', 'students.level_id', 'students.classroom_id', 'students.user_id', 'students.gender', 'students.status', 'students.batch', 'students.address', 'students.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'level', 'classroom'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Student'));
            })
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Students/Index', [
            'page_setting' => [
                'title' => 'Siswa',
                'subtitle' => 'Menampilkan semua data Siswa yang tersedia di Sekolah ini'
            ],
            'students' => StudentResource::collection($students)->additional([
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
        return inertia('Admin/Students/Create', [
            'page_setting' => [
                'title' => 'Tambah Siswa',
                'subtitle' => 'Buat Siswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.students.store')
            ],
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'level_id' => $item->level_id,
                'label' => $item->name,
            ]),
            'genders' => Gender::options(),
            'statuses' => StudentStatus::options(),
        ]);
    }

    public function store(StudentRequest $request)
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
                'level_id' => $request->level_id,
                'classroom_id' => $request->classroom_id,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'status' => $request->status,
                'batch' => $request->batch,
                'address' => $request->address,
            ]);


            DB::commit();
            $user->assignRole('Student');

            flashMessage(MessageType::CREATED->message('Siswa'));
            return to_route('admin.students.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }

    public function edit(Student $student)
    {
        return inertia('Admin/Students/Edit', [
            'page_setting' => [
                'title' => 'Edit Siswa',
                'subtitle' => 'Edit Siswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.students.update', $student)
            ],
            'student' => $student->load(['user']),
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'level_id' => $item->level_id,
                'label' => $item->name,
            ]),
            'genders' => Gender::options(),
            'statuses' => StudentStatus::options(),
        ]);
    }

    public function update(StudentRequest $request, Student $student)
    {
        DB::beginTransaction();
        try {

            $student->update([
                'level_id' => $request->level_id,
                'classroom_id' => $request->classroom_id,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'status' => $request->status,
                'batch' => $request->batch,
                'address' => $request->address,
            ]);

            $student->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
                'avatar' => $this->update_file($request, $student->user, 'avatar', 'students'),
            ]);



            DB::commit();

            flashMessage(MessageType::UPDATED->message('Siswa'));
            return to_route('admin.students.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }


    public function destroy(Student $student)
    {
        try {
            $this->delete_file($student->user, 'avatar');

            $student->delete();
            flashMessage(MessageType::DELETED->message('Siswa'));
            return to_route('admin.students.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }
}
