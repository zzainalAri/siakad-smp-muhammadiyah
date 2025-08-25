<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AcademicYearSemester;
use App\Enums\FeeStatus;
use App\Enums\MessageType;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicYearRequest;
use App\Http\Resources\Admin\AcademicYearResource;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\FeeGroup;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            DB::beginTransaction();
            $academicYear = AcademicYear::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'semester' => $request->semester,
                'is_active' => $request->is_active,
            ]);

            $overlap = AcademicYear::where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
                ->when($academicYear ?? null, fn($q) => $q->where('id', '!=', $academicYear->id))
                ->exists();

            if ($overlap) {
                throw new \Exception('Rentang tahun ajaran ini bertabrakan dengan data yang sudah ada.');
            }

            if ($request->is_active) {
                AcademicYear::where('id', '!=', $academicYear->id)
                    ->update(['is_active' => false]);

                $students = Student::where('status', StudentStatus::ACTIVE->value)->get();
                $period = CarbonPeriod::create(
                    Carbon::parse($request->start_date)->addMonthNoOverflow()->startOfMonth(),
                    '1 month',
                    Carbon::parse($request->end_date)->startOfMonth()

                );

                foreach ($students as $student) {
                    $feeGroup = FeeGroup::where('level_id', $student->level_id)->first();

                    foreach ($period as $date) {
                        Fee::firstOrCreate([
                            'student_id' => $student->id,
                            'academic_year_id' => $academicYear->id,
                            'semester' => $request->semester,
                            'billing_date' => $date->toDateString(),
                        ], [
                            'fee_code' => 'INV-' . $date->format('Ym') . '-' . $student->id,
                            'amount' => $feeGroup->amount,
                            'due_date' => $date->copy()->day(10)->toDateString(),
                            'status' => FeeStatus::UNPAID->value,
                            'fee_group_id' => $feeGroup->id
                        ]);
                    }
                }
            }

            DB::commit();

            flashMessage(MessageType::CREATED->message('Tahun Ajaran'));
            return to_route('admin.academic-years.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
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
            DB::beginTransaction();

            // cek overlap (kecuali tahun ajaran yang sedang diupdate)
            $overlap = AcademicYear::where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
                ->where('id', '!=', $academicYear->id)
                ->exists();

            if ($overlap) {
                throw new \Exception('Rentang tahun ajaran ini bertabrakan dengan data yang sudah ada.');
            }

            // update data academic year
            $academicYear->update([
                'name'       => $request->name,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'semester'   => $request->semester,
                'is_active'  => $request->is_active,
            ]);

            // kalau di-set aktif, nonaktifkan tahun ajaran lain + generate/update fee
            if ($request->is_active) {
                AcademicYear::where('id', '!=', $academicYear->id)
                    ->update(['is_active' => false]);

                Fee::where('academic_year_id', '!=', $academicYear->id)->delete();


                $students = Student::where('status', StudentStatus::ACTIVE->value)->get();
                $period = CarbonPeriod::create(
                    Carbon::parse($request->start_date)->addMonthNoOverflow()->startOfMonth(),
                    '1 month',
                    Carbon::parse($request->end_date)->startOfMonth()
                );

                foreach ($students as $student) {
                    $feeGroup = FeeGroup::where('level_id', $student->level_id)->first();

                    // kumpulkan semua tanggal tagihan yg valid (berdasarkan periode baru)
                    $validDates = [];

                    foreach ($period as $date) {
                        $validDates[] = $date->toDateString();

                        Fee::updateOrCreate(
                            [
                                'student_id'       => $student->id,
                                'academic_year_id' => $academicYear->id,
                                'semester'         => $request->semester,
                                'billing_date'     => $date->toDateString(),
                            ],
                            [
                                'fee_code'     => 'INV-' . $date->format('Ym') . '-' . $student->id,
                                'amount'       => $feeGroup->amount,
                                'due_date'     => $date->copy()->day(10)->toDateString(),
                                'status' => FeeStatus::UNPAID->value,
                                'fee_group_id' => $feeGroup->id
                            ]
                        );
                    }

                    // hapus fee yang tidak ada dalam periode baru
                    Fee::where('student_id', $student->id)
                        ->where('academic_year_id', $academicYear->id)
                        ->where('semester', $request->semester)
                        ->whereNotIn('billing_date', $validDates)
                        ->delete();
                }
            }

            DB::commit();

            flashMessage(MessageType::UPDATED->message('Tahun Ajaran'));
            return to_route('admin.academic-years.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
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
