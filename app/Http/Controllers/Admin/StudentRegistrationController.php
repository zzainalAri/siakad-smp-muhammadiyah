<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Gender;
use App\Enums\MessageType;
use App\Enums\StudentRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRegistrationRequest;
use App\Http\Resources\Admin\StudentRegistrationResource;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentRegistrationController extends Controller
{
    use HasFile;
    public function index()
    {
        $students = StudentRegistration::filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->orderBy('created_at', 'desc')
            ->paginate(request()->load ?? 10);




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
        ]);
    }

    public function store(StudentRegistrationRequest $request)
    {
        DB::beginTransaction();
        try {

            StudentRegistration::create([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'previous_school' => $request->previous_school,
                'phone' => $request->phone,
                'nik' => $request->nik,
                'status' => $request->status,
                'address' => $request->address,
                'doc_kk' => $this->upload_file($request, 'doc_kk', 'kartu_keluarga'),
                'doc_kk' => $this->upload_file($request, 'doc_kk', 'kartu_keluarga'),
                'doc_akta' => $this->upload_file($request, 'doc_akta', 'akta_kelahiran'),
                'doc_akta' => $this->upload_file($request, 'doc_akta', 'akta_kelahiran'),

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
        ]);
    }

    public function update(StudentRegistrationRequest $request, StudentRegistration $studentRegistration)
    {
        DB::beginTransaction();
        try {

            $studentRegistration->update([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'previous_school' => $request->previous_school,
                'phone' => $request->phone,
                'nik' => $request->nik,
                'status' => $request->status,
                'address' => $request->address,
                'doc_kk' => $this->update_file($request, $studentRegistration, 'doc_kk', 'kartu_keluarga'),
                'doc_akta' => $this->update_file($request, $studentRegistration, 'doc_akta', 'akta_kelahiran'),

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
            $this->delete_file($studentRegistration, 'doc_kk');
            $this->delete_file($studentRegistration, 'doc_akta');

            $studentRegistration->delete();
            flashMessage(MessageType::DELETED->message('PPDB'));
            return to_route('admin.student-registrations.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.student-registrations.index');
        }
    }
}
