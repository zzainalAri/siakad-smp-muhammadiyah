<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Levels
            'levels.index',

            // Dashboard
            'dashboard.view',
            'dashboard.teachers.view',

            // Academic Years
            'academic-years.index',
            'academic-years.create',
            'academic-years.update',
            'academic-years.delete',

            // Classrooms
            'classrooms.index',
            'classrooms.create',
            'classrooms.update',
            'classrooms.detail',
            'classrooms.assign-student',
            'classrooms.remove-student',
            'classrooms.delete',

            // Roles
            'roles.index',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Fee Groups
            'fee-groups.index',
            'fee-groups.update',

            // Students
            'students.index',
            'students.create',
            'students.update',
            'students.delete',

            // Teachers
            'teachers.index',
            'teachers.create',
            'teachers.update',
            'teachers.delete',

            // Courses
            'courses.index',
            'courses.create',
            'courses.update',
            'courses.delete',

            // PPDB
            'ppdb.index',
            'ppdb.create',
            'ppdb.update',
            'ppdb.update-status',
            'ppdb.delete',

            // Schedules
            'schedules.index',
            'schedules.create',
            'schedules.update',
            'schedules.delete',

            // Fees
            'fees.index',

            //student
            'students.fees.index',
            'students.schedules.index',

            // ===== TEACHER =====
            'teachers.courses.index',
            'teachers.courses.show',
            'teachers.classrooms.index',
            'teachers.classrooms.sync',
            'teachers.schedules.index',

            // ===== USER =====
            'users.index',
            'users.update',
            'users.create',
            'users.delete',


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
