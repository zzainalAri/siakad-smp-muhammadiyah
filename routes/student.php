<?php

use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\FeeStudentController;
use App\Http\Controllers\Student\ScheduleStudentController;
use App\Http\Controllers\Student\StudyPlanStudentController;
use App\Http\Controllers\Student\StudyResultStudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('students')->middleware(['auth', 'role:Student'])->group(function () {

    // dashboard
    Route::get('dashboard', DashboardStudentController::class)
        ->name('students.dashboard')
        ->middleware('permission:students.dashboard');

    // student's schedule
    Route::get('schedules', ScheduleStudentController::class)
        ->name('students.schedules.index')
        ->middleware('permission:students.schedules.index');

    // student's fee
    Route::get('fees', FeeStudentController::class)
        ->name('students.fees.index')
        ->middleware('permission:students.fees.index');
});
