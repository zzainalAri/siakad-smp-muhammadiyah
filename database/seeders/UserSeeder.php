<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Monkey D Luffy',
            'email' => 'luffy@gmail.com',
        ])->assignRole(Role::create([
            'name' => 'Admin',
        ]));

        $role = Role::where('name', 'Admin')->first();

        $permissions = Permission::where(function ($query) {
            $query
                ->where('name', 'not like', 'students.fees.index')
                ->where('name', 'not like', 'students.schedules.index')
                ->where('name', 'not like', 'teachers.classrooms.index')
                ->where('name', 'not like', 'teachers.classrooms.sync')
                ->where('name', 'not like', 'teachers.courses.index')
                ->where('name', 'not like', 'teachers.schedules.index')
                ->where('name', 'not like', 'teachers.dashboard')
                ->where('name', 'not like', 'teachers.courses.show')

            ;
        })->get();

        $role->syncPermissions($permissions);
    }
}
