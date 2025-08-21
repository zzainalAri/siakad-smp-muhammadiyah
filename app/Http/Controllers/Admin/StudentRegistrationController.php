<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Gender;
use App\Enums\MessageType;
use App\Enums\Religion;
use App\Enums\StudentRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRegistrationRequest;
use App\Http\Resources\Admin\StudentRegistrationResource;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class StudentRegistrationController extends Controller
{
    public function index()
    {
        $students = StudentRegistration::filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->orderBy('created_at', 'desc')
            ->paginate(request()->load ?? 10);

        $level = Level::where('name', 'Kelas 7')->first();





        return inertia('Admin/StudentRegistrations/Index', [
            'page_setting' => [
                'title' => 'PPDB',
                'subtitle' => 'Menampilkan semua data PPDB yang tersedia di Sekolah ini'
            ],
            'students' => StudentRegistrationResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
                ],
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name', 'level_id'])->where('level_id', $level->id)->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'level_id' => $item->level_id,
                'label' => $item->name,
            ]),
            'statuses' => StudentRegistrationStatus::options(),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/StudentRegistrations/Create', [
            'page_setting' => [
                'title' => 'Tambah PPDB',
                'subtitle' => 'Buat PPDB baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.student-registrations.store')
            ],
            'statuses' => StudentRegistrationStatus::options(),
            'genders' => Gender::options(),
            'religions' => Religion::options(),
        ]);
    }

    public function store(StudentRegistrationRequest $request)
    {
        DB::beginTransaction();
        try {

            StudentRegistration::create([
                'name' => $request->name,
                'email' => $request->email,
                'mother_name' => $request->mother_name,
                'father_name' => $request->father_name,
                'father_nik' => $request->father_nik,
                'mother_nik' => $request->mother_nik,
                'religion' => $request->religion,
                'no_kk' => $request->no_kk,
                'accepted_date' => $request->accepted_date,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'previous_school' => $request->previous_school,
                'phone' => $request->phone,
                'nik' => $request->nik,
                'address' => $request->address,

            ]);


            DB::commit();

            flashMessage(MessageType::CREATED->message('PPDB'));
            return to_route('admin.student-registrations.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }

    public function edit(StudentRegistration $studentRegistration)
    {
        return inertia('Admin/StudentRegistrations/Edit', [
            'page_setting' => [
                'title' => 'Edit PPDB',
                'subtitle' => 'Edit PPDB baru disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.student-registrations.update', [$studentRegistration])
            ],
            'statuses' => StudentRegistrationStatus::options(),
            'genders' => Gender::options(),
            'student' => $studentRegistration,
            'religions' => Religion::options(),
        ]);
    }

    public function update(StudentRegistrationRequest $request, StudentRegistration $studentRegistration)
    {
        DB::beginTransaction();
        try {

            $studentRegistration->update([
                'name' => $request->name,
                'email' => $request->email,
                'mother_name' => $request->mother_name,
                'father_name' => $request->father_name,
                'father_nik' => $request->father_nik,
                'no_kk' => $request->no_kk,
                'mother_nik' => $request->mother_nik,
                'religion' => $request->religion,
                'accepted_date' => $request->accepted_date,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'previous_school' => $request->previous_school,
                'phone' => $request->phone,
                'nik' => $request->nik,
                'address' => $request->address,

            ]);


            DB::commit();

            flashMessage(MessageType::CREATED->message('PPDB'));
            return to_route('admin.student-registrations.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }

    public function destroy(StudentRegistration $studentRegistration)
    {
        try {

            $studentRegistration->delete();
            flashMessage(MessageType::DELETED->message('PPDB'));
            return to_route('admin.student-registrations.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.student-registrations.index');
        }
    }

    public function approve(Request $request, StudentRegistration $studentRegistration)
    {

        try {
            if ($request->status == StudentRegistrationStatus::APPROVED->value && !$request->classroom_id) {
                flashMessage('Kelas wajib dipilih', 'error');
                return back();
            }

            if ($request->status != StudentRegistrationStatus::APPROVED->value) {
                $studentRegistration->update([
                    'status' => $request->status,
                    'rejected_description' => $request->rejected_description

                ]);
                flashMessage(MessageType::UPDATED->message('Status PPDB'));
                return back();
            }

            DB::beginTransaction();

            $studentRegistration->update([
                'status' => StudentRegistrationStatus::APPROVED->value,
                'accepted_date' => now(),

            ]);

            $classroom = Classroom::where('id', $request->classroom_id)->with('level')->first();


            $user = User::create([
                'name' => $studentRegistration->name,
                'email' => $studentRegistration->email,
                'password' => Hash::make('password')
            ]);

            $user->student()->create([
                'classroom_id' => $request->classroom_id,
                'level_id' => $classroom->level->id,
                'nisn' => $studentRegistration->nisn,
                'gender' => $studentRegistration->gender,
                'status' => $studentRegistration->status,
                'batch' => now()->year,
                'address' => $studentRegistration->address,
                'student_registration_id' => $studentRegistration->id,
            ]);


            DB::commit();
            $user->assignRole('Student');


            flashMessage(MessageType::UPDATED->message('Status PPDB'));
            return back();
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }
}
