<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Http\Resources\Admin\TeacherResource;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class TeacherController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only: ['store', 'update']),
        ];
    }



    use HasFile;
    public function index()
    {
        $teachers = Teacher::query()
            ->select(['teachers.id', 'teachers.teacher_number', 'teachers.faculty_id', 'teachers.departement_id', 'teachers.user_id', 'teachers.academic_title', 'teachers.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'faculty', 'departement'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Teacher'));
            })
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Teachers/Index', [
            'page_setting' => [
                'title' => 'Dosen',
                'subtitle' => 'Menampilkan semua data Dosen yang tersedia pada universitas ini'
            ],
            'teachers' => TeacherResource::collection($teachers)->additional([
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
        return inertia('Admin/Teachers/Create', [
            'page_setting' => [
                'title' => 'Tambah Dosen',
                'subtitle' => 'Buat Dosen baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.teachers.store')
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

    public function store(TeacherRequest $request)
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
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'teacher_number' => $request->teacher_number,
                'academic_title' => $request->academic_title,

            ]);


            DB::commit();
            $user->assignRole('Teacher');

            flashMessage(MessageType::CREATED->message('Dosen'));
            return to_route('admin.teachers.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }

    public function edit(Teacher $teacher)
    {
        return inertia('Admin/Teachers/Edit', [
            'page_setting' => [
                'title' => 'Edit Dosen',
                'subtitle' => 'Edit Dosen disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.teachers.update', $teacher)
            ],
            'teacher' => $teacher->load(['user']),
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

    public function update(TeacherRequest $request, Teacher $teacher)
    {
        DB::beginTransaction();
        try {

            $teacher->update([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
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
            return to_route('admin.teachers.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }


    public function destroy(Teacher $teacher)
    {
        try {
            $this->delete_file($teacher->user, 'avatar');

            $teacher->delete();
            flashMessage(MessageType::DELETED->message('Dosen'));
            return to_route('admin.teachers.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.teachers.index');
        }
    }
}
