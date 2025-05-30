<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Classroom;
use App\Models\Faculty;
use App\Models\FeeGroup;
use App\Models\Student;
use App\Models\User;
use App\Traits\HasFile;
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
            ->select(['students.id', 'students.student_number', 'students.faculty_id','students.fee_group_id', 'students.classroom_id', 'students.user_id', 'students.semester', 'students.batch', 'students.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'faculty','feeGroup', 'classroom'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Student'));
            })
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Students/Index', [
            'page_setting' => [
                'title' => 'Mahasiswa',
                'subtitle' => 'Menampilkan semua data Mahasiswa yang tersedia pada universitas ini'
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
                'title' => 'Tambah Mahasiswa',
                'subtitle' => 'Buat Mahasiswa baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.students.store')
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . '-' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
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
                'faculty_id' => $request->faculty_id,
                'classroom_id' => $request->classroom_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'semester' => $request->semester,
                'batch' => $request->batch,

            ]);


            DB::commit();
            $user->assignRole('Student');

            flashMessage(MessageType::CREATED->message('Mahasiswa'));
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
                'title' => 'Edut Mahasiswa',
                'subtitle' => 'Edit Mahasiswa disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.students.update', $student)
            ],
            'student' => $student->load(['user']),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'feeGroups' => FeeGroup::query()->select(['id', 'group', 'amount'])->orderBy('group')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => 'Golongan ' . $item->group . '-' . number_format($item->amount, 0, ',', '.'),
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function update(StudentRequest $request, Student $student)
    {
        DB::beginTransaction();
        try {

            $student->update([
                'faculty_id' => $request->faculty_id,
                'classroom_id' => $request->classroom_id,
                'fee_group_id' => $request->fee_group_id,
                'student_number' => $request->student_number,
                'semester' => $request->semester,
                'batch' => $request->batch,

            ]);

            $student->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
                'avatar' => $this->update_file($request, $student->user, 'avatar', 'students'),
            ]);



            DB::commit();

            flashMessage(MessageType::UPDATED->message('Mahasiswa'));
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
            flashMessage(MessageType::DELETED->message('Mahasiswa'));
            return to_route('admin.students.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.students.index');
        }
    }
}
