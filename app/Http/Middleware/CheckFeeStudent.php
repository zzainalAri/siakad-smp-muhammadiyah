<?php

namespace App\Http\Middleware;

use App\Enums\FeeStatus;
use App\Models\Fee;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeeStudent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $student = auth()->user()->student;
        $now = Carbon::now();

        $hasPaidThisMonth = Fee::query()
            ->where('student_id', $student->id)
            ->where('academic_year_id', activeAcademicYear()->id)
            ->whereMonth('updated_at', $now->month)
            ->whereYear('updated_at', $now->year)
            ->where('status', FeeStatus::PAID->value)
            ->exists();

        if (!$hasPaidThisMonth) {
            flashMessage('SPP bulan ini belum lunas. Harap melakukan pembayaran SPP bulan ini terlebih dahulu.', 'error');
            return to_route('students.fees.index');
        }

        return $next($request);
    }
}
