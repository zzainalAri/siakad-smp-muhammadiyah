<?php

use App\Http\Controllers\Teacher\CourseClassroomController;
use App\Http\Controllers\Teacher\CourseTeacherController;
use App\Http\Controllers\Teacher\DashboardTeacherController;
use App\Http\Controllers\Teacher\ScheduleTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->middleware(['auth', 'role:Teacher'])->group(function () {


    // course
    Route::controller(CourseTeacherController::class)->group(function () {
        Route::get('courses', 'index')
            ->name('teachers.courses.index')
            ->middleware('permission:teachers.courses.index');

        Route::get('courses/{course:code}/detail', 'show')
            ->name('teachers.courses.show')
            ->middleware('permission:teachers.courses.show');
    });

    // course class room
    Route::controller(CourseClassroomController::class)->group(function () {
        Route::get('courses/{course}/classrooms/{classroom}', 'index')
            ->name('teachers.classrooms.index')
            ->middleware('permission:teachers.classrooms.index');

        Route::put('courses/{course}/classrooms/{classroom}/synchronize', 'sync')
            ->name('teachers.classrooms.sync')
            ->middleware('permission:teachers.classrooms.sync');
    });

    // schedule
    Route::get('schedules', ScheduleTeacherController::class)
        ->name('teachers.schedules.index')
        ->middleware('permission:teachers.schedules.index');
});
