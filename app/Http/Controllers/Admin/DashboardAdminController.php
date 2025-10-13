<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Fee;
use App\Models\Level;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function __invoke()

    {

        $now = Carbon::now();
        $currentMonth = $now->format('m');
        $currentYear = $now->format('Y');

        $feesThisMonth = Fee::whereMonth('billing_date', $currentMonth)
            ->whereYear('billing_date', $currentYear);

        $unpaids = (clone $feesThisMonth)
            ->where('status', 'Belum Bayar')
            ->with('student')
            ->count();

        $paids = (clone $feesThisMonth)
            ->where('status', 'Sudah Bayar')
            ->with('student')
            ->count();

        return inertia('Admin/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => array_merge([
                'levels' => Level::count(),
                'classrooms' => Classroom::count(),
                'courses' => Course::count(),
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'unpaids' => $unpaids,
                'paids' => $paids,
            ], Auth::user()->hasRole('Teacher') ? [
                'teacher_courses' => Course::query()->where('teacher_id', Auth::user()->teacher->id)->count(),
                'teacher_classrooms' => Classroom::whereHas(
                    'schedules.course',
                    fn($query) =>
                    $query->where('teacher_id', Auth::user()->teacher->id)
                )->count(),
                'teacher_schedules' => Schedule::whereHas(
                    'course',
                    fn($query) =>
                    $query->where('teacher_id', Auth::user()->teacher->id)
                )->count(),
            ] : [])
        ]);
    }
}
