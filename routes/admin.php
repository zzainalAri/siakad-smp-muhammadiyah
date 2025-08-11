<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ClassroomStudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\FeeGroupController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'role:Admin'])->group(function () {

    //dashboard
    Route::get('dashboard', DashboardAdminController::class)->name('admin.dashboard');

    // levels
    Route::controller(LevelController::class)->group(function () {
        Route::get('levels', 'index')->name('admin.levels.index');
        Route::get('levels/create', 'create')->name('admin.levels.create');
        Route::post('levels/create', 'store')->name('admin.levels.store');
        Route::get('levels/edit/{level:slug}', 'edit')->name('admin.levels.edit');
        Route::put('levels/edit/{level:slug}', 'update')->name('admin.levels.update');
        Route::delete('levels/destroy/{level:slug}', 'destroy')->name('admin.levels.destroy');
    });

    // // departement
    // Route::controller(DepartementController::class)->group(function () {
    //     Route::get('departements', 'index')->name('admin.departements.index');
    //     Route::get('departements/create', 'create')->name('admin.departements.create');
    //     Route::post('departements/create', 'store')->name('admin.departements.store');
    //     Route::get('departements/edit/{departement:slug}', 'edit')->name('admin.departements.edit');
    //     Route::put('departements/edit/{departement:slug}', 'update')->name('admin.departements.update');
    //     Route::delete('departements/destroy/{departement:slug}', 'destroy')->name('admin.departements.destroy');
    // });

    // academic year
    Route::controller(AcademicYearController::class)->group(function () {
        Route::get('academic-years', 'index')->name('admin.academic-years.index');
        Route::get('academic-years/create', 'create')->name('admin.academic-years.create');
        Route::post('academic-years/create', 'store')->name('admin.academic-years.store');
        Route::get('academic-years/edit/{academicYear:slug}', 'edit')->name('admin.academic-years.edit');
        Route::put('academic-years/edit/{academicYear:slug}', 'update')->name('admin.academic-years.update');
        Route::delete('academic-years/destroy/{academicYear:slug}', 'destroy')->name('admin.academic-years.destroy');
    });


    // classroom
    Route::controller(ClassroomController::class)->middleware(['checkActiveAcademicYear'])->group(function () {
        Route::get('classrooms', 'index')->name('admin.classrooms.index');
        Route::get('classrooms/create', 'create')->name('admin.classrooms.create');
        Route::post('classrooms/create', 'store')->name('admin.classrooms.store');
        Route::get('classrooms/edit/{classroom:slug}', 'edit')->name('admin.classrooms.edit');
        Route::put('classrooms/edit/{classroom:slug}', 'update')->name('admin.classrooms.update');
        Route::delete('classrooms/destroy/{classroom:slug}', 'destroy')->name('admin.classrooms.destroy');
    });

    // Role
    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('admin.roles.index');
        Route::get('roles/create', 'create')->name('admin.roles.create');
        Route::post('roles/create', 'store')->name('admin.roles.store');
        Route::get('roles/edit/{role}', 'edit')->name('admin.roles.edit');
        Route::put('roles/edit/{role}', 'update')->name('admin.roles.update');
        Route::delete('roles/destroy/{role}', 'destroy')->name('admin.roles.destroy');
    });

    // FeeGroup
    Route::controller(FeeGroupController::class)->group(function () {
        Route::get('fee-groups', 'index')->name('admin.fee-groups.index');
        Route::get('fee-groups/create', 'create')->name('admin.fee-groups.create');
        Route::post('fee-groups/create', 'store')->name('admin.fee-groups.store');
        Route::get('fee-groups/edit/{feeGroup}', 'edit')->name('admin.fee-groups.edit');
        Route::put('fee-groups/edit/{feeGroup}', 'update')->name('admin.fee-groups.update');
        Route::delete('fee-groups/destroy/{feeGroup}', 'destroy')->name('admin.fee-groups.destroy');
    });

    // Student
    Route::controller(StudentController::class)->group(function () {
        Route::get('students', 'index')->name('admin.students.index');
        Route::get('students/create', 'create')->name('admin.students.create');
        Route::post('students/create', 'store')->name('admin.students.store');
        Route::get('students/edit/{student:nisn}', 'edit')->name('admin.students.edit');
        Route::put('students/edit/{student:nisn}', 'update')->name('admin.students.update');
        Route::delete('students/destroy/{student:nisn}', 'destroy')->name('admin.students.destroy');
    });

    // assign student to classroom
    Route::controller(ClassroomStudentController::class)->group(function () {
        Route::get('classrooms/students/{classroom:slug}', 'index')->name('admin.classroom-students.index');
        Route::put('classrooms/students/{classroom:slug}/sync', 'sync')->name('admin.classroom-students.sync');
        Route::delete('classrooms/students/{classroom:slug}/destroy/{student:nisn}', 'destroy')->name('admin.classroom-students.destroy');
    });

    // Teacher
    Route::controller(TeacherController::class)->group(function () {
        Route::get('teachers', 'index')->name('admin.teachers.index');
        Route::get('teachers/create', 'create')->name('admin.teachers.create');
        Route::post('teachers/create', 'store')->name('admin.teachers.store');
        Route::get('teachers/edit/{teacher:nip}', 'edit')->name('admin.teachers.edit');
        Route::put('teachers/edit/{teacher:nip}', 'update')->name('admin.teachers.update');
        Route::delete('teachers/destroy/{teacher:nip}', 'destroy')->name('admin.teachers.destroy');
    });


    // Operator
    Route::controller(OperatorController::class)->group(function () {
        Route::get('operators', 'index')->name('admin.operators.index');
        Route::get('operators/create', 'create')->name('admin.operators.create');
        Route::post('operators/create', 'store')->name('admin.operators.store');
        Route::get('operators/edit/{operator:employee_number}', 'edit')->name('admin.operators.edit');
        Route::put('operators/edit/{operator:employee_number}', 'update')->name('admin.operators.update');
        Route::delete('operators/destroy/{operator:employee_number}', 'destroy')->name('admin.operators.destroy');
    });

    // Course
    Route::controller(CourseController::class)->group(function () {
        Route::get('courses', 'index')->name('admin.courses.index');
        Route::get('courses/create', 'create')->name('admin.courses.create');
        Route::post('courses/create', 'store')->name('admin.courses.store');
        Route::get('courses/edit/{course:code}', 'edit')->name('admin.courses.edit');
        Route::put('courses/edit/{course:code}', 'update')->name('admin.courses.update');
        Route::delete('courses/destroy/{course:code}', 'destroy')->name('admin.courses.destroy');
    });

    // student-registrations
    Route::controller(StudentRegistration::class)->group(function () {
        Route::get('student-registrations', 'index')->name('admin.student-registrations.index');
        Route::get('student-registrations/create', 'create')->name('admin.student-registrations.create');
        Route::post('student-registrations/create', 'store')->name('admin.student-registrations.store');
        Route::get('student-registrations/edit/{studentRegistration:nisn}', 'edit')->name('admin.student-registrations.edit');
        Route::put('student-registrations/edit/{studentRegistration:nisn}', 'update')->name('admin.student-registrations.update');
        Route::delete('student-registrations/destroy/{studentRegistration:nisn}', 'destroy')->name('admin.student-registrations.destroy');
    });

    // Schedules
    Route::controller(ScheduleController::class)->group(function () {
        Route::get('schedules', 'index')->name('admin.schedules.index');
        Route::get('schedules/create', 'create')->name('admin.schedules.create');
        Route::post('schedules/create', 'store')->name('admin.schedules.store');
        Route::get('schedules/edit/{schedule}', 'edit')->name('admin.schedules.edit');
        Route::put('schedules/edit/{schedule}', 'update')->name('admin.schedules.update');
        Route::delete('schedules/destroy/{schedule}', 'destroy')->name('admin.schedules.destroy');
    });

    // Fee
    Route::get('fees', FeeController::class)->name('admin.fees.index');
});
