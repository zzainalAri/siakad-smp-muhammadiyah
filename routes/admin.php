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
use App\Http\Controllers\Admin\StudentRegistrationController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', DashboardAdminController::class)->name('admin.dashboard');

    // levels
    Route::controller(LevelController::class)->group(function () {
        Route::get('levels', 'index')->name('admin.levels.index')->middleware('permission:levels.index');
    });

    // academic year
    Route::controller(AcademicYearController::class)->group(function () {
        Route::get('academic-years', 'index')->name('admin.academic-years.index')->middleware('permission:academic-years.index');
        Route::get('academic-years/create', 'create')->name('admin.academic-years.create')->middleware('permission:academic-years.create');
        Route::post('academic-years/create', 'store')->name('admin.academic-years.store')->middleware('permission:academic-years.create');
        Route::get('academic-years/edit/{academicYear:slug}', 'edit')->name('admin.academic-years.edit')->middleware('permission:academic-years.update');
        Route::put('academic-years/edit/{academicYear:slug}', 'update')->name('admin.academic-years.update')->middleware('permission:academic-years.update');
        Route::delete('academic-years/destroy/{academicYear:slug}', 'destroy')->name('admin.academic-years.destroy')->middleware('permission:academic-years.delete');
    });

    // classroom
    Route::controller(ClassroomController::class)->group(function () {
        Route::get('classrooms', 'index')->name('admin.classrooms.index')->middleware('permission:classrooms.index');
        Route::get('classrooms/create', 'create')->name('admin.classrooms.create')->middleware('permission:classrooms.create');
        Route::post('classrooms/create', 'store')->name('admin.classrooms.store')->middleware('permission:classrooms.create');
        Route::get('classrooms/edit/{classroom:slug}', 'edit')->name('admin.classrooms.edit')->middleware('permission:classrooms.update');
        Route::put('classrooms/edit/{classroom:slug}', 'update')->name('admin.classrooms.update')->middleware('permission:classrooms.update');
        Route::delete('classrooms/destroy/{classroom:slug}', 'destroy')->name('admin.classrooms.destroy')->middleware('permission:classrooms.delete');
    });

    // Role
    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('admin.roles.index')->middleware('permission:roles.index');
        Route::get('roles/create', 'create')->name('admin.roles.create')->middleware('permission:roles.create');
        Route::post('roles/create', 'store')->name('admin.roles.store')->middleware('permission:roles.create');
        Route::get('roles/edit/{role}', 'edit')->name('admin.roles.edit')->middleware('permission:roles.update');
        Route::put('roles/edit/{role}', 'update')->name('admin.roles.update')->middleware('permission:roles.update');
        Route::delete('roles/destroy/{role}', 'destroy')->name('admin.roles.destroy')->middleware('permission:roles.delete');
    });

    // FeeGroup
    Route::controller(FeeGroupController::class)->group(function () {
        Route::get('fee-groups', 'index')->name('admin.fee-groups.index')->middleware('permission:fee-groups.index');
        Route::get('fee-groups/create', 'create')->name('admin.fee-groups.create')->middleware('permission:fee-groups.update');
        Route::post('fee-groups/create', 'store')->name('admin.fee-groups.store')->middleware('permission:fee-groups.update');
        Route::get('fee-groups/edit/{feeGroup}', 'edit')->name('admin.fee-groups.edit')->middleware('permission:fee-groups.update');
        Route::put('fee-groups/edit/{feeGroup}', 'update')->name('admin.fee-groups.update')->middleware('permission:fee-groups.update');
        Route::delete('fee-groups/destroy/{feeGroup}', 'destroy')->name('admin.fee-groups.destroy')->middleware('permission:fee-groups.update');
    });

    // Student
    Route::controller(StudentController::class)->group(function () {
        Route::get('students', 'index')->name('admin.students.index')->middleware('permission:students.index');
        Route::get('students/create', 'create')->name('admin.students.create')->middleware('permission:students.create');
        Route::post('students/create', 'store')->name('admin.students.store')->middleware('permission:students.create');
        Route::get('students/edit/{student:nisn}', 'edit')->name('admin.students.edit')->middleware('permission:students.update');
        Route::put('students/edit/{student:nisn}', 'update')->name('admin.students.update')->middleware('permission:students.update');
        Route::delete('students/destroy/{student:nisn}', 'destroy')->name('admin.students.destroy')->middleware('permission:students.delete');
    });

    // assign student to classroom
    Route::controller(ClassroomStudentController::class)->group(function () {
        Route::get('classrooms/students/{classroom:slug}', 'index')->name('admin.classroom-students.index')->middleware('permission:classrooms.detail');
        Route::put('classrooms/students/{classroom:slug}/sync', 'sync')->name('admin.classroom-students.sync')->middleware('permission:classrooms.assign-student');
        Route::delete('classrooms/students/{classroom:slug}/destroy/{student:nisn}', 'destroy')->name('admin.classroom-students.destroy')->middleware('permission:classrooms.remove-student');
    });

    // Teacher
    Route::controller(TeacherController::class)->group(function () {
        Route::get('teachers', 'index')->name('admin.teachers.index')->middleware('permission:teachers.index');
        Route::get('teachers/create', 'create')->name('admin.teachers.create')->middleware('permission:teachers.create');
        Route::post('teachers/create', 'store')->name('admin.teachers.store')->middleware('permission:teachers.create');
        Route::get('teachers/edit/{teacher:nip}', 'edit')->name('admin.teachers.edit')->middleware('permission:teachers.update');
        Route::put('teachers/edit/{teacher:nip}', 'update')->name('admin.teachers.update')->middleware('permission:teachers.update');
        Route::delete('teachers/destroy/{teacher:nip}', 'destroy')->name('admin.teachers.destroy')->middleware('permission:teachers.delete');
    });

    // Course
    Route::controller(CourseController::class)->group(function () {
        Route::get('courses', 'index')->name('admin.courses.index')->middleware('permission:courses.index');
        Route::get('courses/create', 'create')->name('admin.courses.create')->middleware('permission:courses.create');
        Route::post('courses/create', 'store')->name('admin.courses.store')->middleware('permission:courses.create');
        Route::get('courses/edit/{course:code}', 'edit')->name('admin.courses.edit')->middleware('permission:courses.update');
        Route::put('courses/edit/{course:code}', 'update')->name('admin.courses.update')->middleware('permission:courses.update');
        Route::delete('courses/destroy/{course:code}', 'destroy')->name('admin.courses.destroy')->middleware('permission:courses.delete');
    });

    // student-registrations (PPDB)
    Route::controller(StudentRegistrationController::class)->group(function () {
        Route::get('student-registrations', 'index')->name('admin.student-registrations.index')->middleware('permission:ppdb.index');
        Route::get('student-registrations/create', 'create')->name('admin.student-registrations.create')->middleware('permission:ppdb.create');
        Route::post('student-registrations/create', 'store')->name('admin.student-registrations.store')->middleware('permission:ppdb.create');
        Route::get('student-registrations/edit/{studentRegistration:nisn}', 'edit')->name('admin.student-registrations.edit')->middleware('permission:ppdb.update');
        Route::put('student-registrations/edit/{studentRegistration:nisn}', 'update')->name('admin.student-registrations.update')->middleware('permission:ppdb.update');
        Route::put('student-registrations/approve/{studentRegistration:nisn}', 'approve')->name('admin.student-registrations.approve')->middleware('permission:ppdb.update-status');
        Route::delete('student-registrations/destroy/{studentRegistration:nisn}', 'destroy')->name('admin.student-registrations.destroy')->middleware('permission:ppdb.delete');
    });

    // Schedules
    Route::controller(ScheduleController::class)->group(function () {
        Route::get('schedules', 'index')->name('admin.schedules.index')->middleware('permission:schedules.index');
        Route::get('schedules/create', 'create')->name('admin.schedules.create')->middleware('permission:schedules.create');
        Route::post('schedules/create', 'store')->name('admin.schedules.store')->middleware('permission:schedules.create');
        Route::get('schedules/edit/{schedule}', 'edit')->name('admin.schedules.edit')->middleware('permission:schedules.update');
        Route::put('schedules/edit/{schedule}', 'update')->name('admin.schedules.update')->middleware('permission:schedules.update');
        Route::delete('schedules/destroy/{schedule}', 'destroy')->name('admin.schedules.destroy')->middleware('permission:schedules.delete');
    });

    // fee
    Route::controller(FeeController::class)->group(function () {
        Route::get('fees', 'index')->name('admin.fees.index')->middleware('permission:fees.index');
        Route::get('fees/create', 'create')->name('admin.fees.create')->middleware('permission:fees.index');
        Route::post('fees/create', 'store')->name('admin.fees.store')->middleware('permission:fees.index');
        Route::get('fees/edit/{fee}', 'edit')->name('admin.fees.edit')->middleware('permission:fees.index');
        Route::put('fees/edit/{fee}', 'update')->name('admin.fees.update')->middleware('permission:fees.index');
        Route::delete('fees/destroy/{fee}', 'destroy')->name('admin.fees.destroy')->middleware('permission:fees.index');
    });

    // users
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('admin.users.index')->middleware('permission:users.index');
        Route::get('users/create', 'create')->name('admin.users.create')->middleware('permission:users.index');
        Route::post('users/create', 'store')->name('admin.users.store')->middleware('permission:users.index');
        Route::get('users/edit/{user}', 'edit')->name('admin.users.edit')->middleware('permission:users.index');
        Route::put('users/edit/{user}', 'update')->name('admin.users.update')->middleware('permission:users.index');
        Route::delete('users/destroy/{user}', 'destroy')->name('admin.users.destroy')->middleware('permission:users.index');
    });
});
