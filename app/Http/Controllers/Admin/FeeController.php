<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FeeStatus;
use App\Enums\MessageType;
use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FeeResource;
use App\Http\Resources\Admin\StudentResource;
use App\Models\Fee;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class FeeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $feesThisMonth = Fee::query()
            ->whereMonth('billing_date', $currentMonth)
            ->whereYear('billing_date', $currentYear)
            ->get(['billing_date', 'updated_at', 'status']);

        $dates = collect(range(1, $now->daysInMonth))
            ->map(fn($day) => $now->copy()->startOfMonth()->addDays($day - 1)->toDateString());

        $chartData = $dates->map(function ($date) use ($feesThisMonth) {
            $paid = $feesThisMonth
                ->where('status', 'Sudah Bayar')
                ->filter(fn($fee) => Carbon::parse($fee->updated_at)->toDateString() === $date)
                ->count();

            $unpaid = $feesThisMonth
                ->where('status', 'Belum Bayar')
                ->filter(fn($fee) => Carbon::parse($fee->billing_date)->toDateString() === $date)
                ->count();

            return [
                'date' => $date,
                'paid' => $paid,
                'unpaid' => $unpaid,
            ];
        });


        $students = Student::query()
            ->with([
                'fees' => function ($query) {
                    $query->whereHas('academicYear', fn($query) => $query->where('is_active', true))
                        ->with(['academicYear' => fn($query) => $query->where('is_active', true)]);
                },

                'user',
                'classroom'
            ])
            ->where('status', StudentStatus::ACTIVE->value)
            ->withSum('fees as total_fees', 'amount') // total semua tagihan
            ->withSum([
                'fees as paid_fees_sum' => fn($q) => $q->where('status', FeeStatus::PAID->value)
            ], 'amount') // total yg sudah dibayar
            ->withSum([
                'fees as unpaid_fees_sum' => fn($q) => $q->where('status', FeeStatus::UNPAID->value)
            ], 'amount') // total yg belum dibayar
            ->filter(request()->only(['search', 'ispaid']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);


        return inertia('Admin/Fees/Index', [
            'page_setting' => [
                'title' => 'SPP',
                'subtitle' => 'Menampilkan semua informasi SPP siswa yang tersedia di SMP Muhammadiyah'
            ],
            'students' => StudentResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
                ],
            ]),
            'chartData' => $chartData,
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'ispaid' => request()->ispaid ?? '',
                'load' => 10
            ]
        ]);
    }

    public function update(Fee $fee)
    {
        try {
            $fee->update([
                'billing_date' => now(),
            ]);

            flashMessage('Berhasil update penetapan pembayaran. Pembayaran ditetapkan dari tanggal hari ini');
            return back();
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }
}
