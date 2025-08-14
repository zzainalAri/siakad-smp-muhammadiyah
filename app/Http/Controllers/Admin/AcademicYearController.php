<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AcademicYearSemester;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicYearRequest;
use App\Http\Resources\Admin\AcademicYearResource;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Throwable;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::query()
            ->select(['id', 'name', 'start_date', 'end_date', 'semester', 'slug', 'is_active', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/AcademicYears/Index', [
            'page_setting' => [
                'title' => 'Tahun Ajaran',
                'subtitle' => 'Menampilkan semua data tahun ajaran yang tersedia di sekolah ini',
            ],
            'academicYears' => AcademicYearResource::collection($academicYears)->additional([
                'meta' => [
                    'has_pages' => $academicYears->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/AcademicYears/Create', [
            'page_setting' => [
                'title' => 'Tambah Tahun Ajaran',
                'subtitle' => 'Buat Tahun Ajaran baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.academic-years.store'),
            ],
            'academicYearSemesters' => AcademicYearSemester::options(),
        ]);
    }

    public function store(AcademicYearRequest $request)
    {
        try {
            AcademicYear::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'semester' => $request->semester,
                'is_active' => $request->is_active,
            ]);

            flashMessage(MessageType::CREATED->message('Tahun Ajaran'));
            return to_route('admin.academic-years.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.academic-years.index');
        }
    }

    public function edit(AcademicYear $academicYear)
    {
        return inertia('Admin/AcademicYears/Edit', [
            'page_setting' => [
                'title' => 'Edit Tahun Ajaran',
                'subtitle' => 'Edit Tahun Ajaran di sini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.academic-years.update', $academicYear),
            ],
            'academicYear' => $academicYear,
            'academicYearSemesters' => AcademicYearSemester::options(),
        ]);
    }

    public function update(AcademicYearRequest $request, AcademicYear $academicYear)
    {
        try {
            $academicYear->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'semester' => $request->semester,
                'is_active' => $request->is_active,
            ]);

            flashMessage(MessageType::UPDATED->message('Tahun Ajaran'));
            return to_route('admin.academic-years.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.academic-years.index');
        }
    }

    public function destroy(AcademicYear $academicYear)
    {
        try {
            $academicYear->delete();
            flashMessage(MessageType::DELETED->message('Tahun Ajaran'));
            return to_route('admin.academic-years.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.academic-years.index');
        }
    }
}
