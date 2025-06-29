<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(LevelSeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(ClassroomSeeder::class);
   



        User::factory()->create([
            'name' => 'Monkey D Luffy',
            'email' => 'luffy@gmail.com',
        ])->assignRole(Role::create([
            'name' => 'Admin',
        ]));

        $operator = User::factory()->create([
            'name' => 'Zoro',
            'email' => 'Zoro@gmail.com',
        ])->assignRole(Role::create([
            'name' => 'Operator',
        ]));

        $operator->operator()->create([
            'level_id' => 1,
            'employee_number' => str()->padLeft(mt_rand(0, 999999), 6, '0')
        ]);


        $teacher = User::factory()->create([
            'name' => 'Sanji',
            'email' => 'sanji@gmail.com',
        ])->assignRole(Role::create([
            'name' => 'Teacher',
        ]));

        $teacher->teacher()->create([
            'level_id' => 1,
            'nip' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'academic_title' => 'Asisten Ahli'
        ]);

        $student = User::factory()->create([
            'name' => 'Usop',
            'email' => 'usop@gmail.com',
        ])->assignRole(Role::create([
            'name' => 'Student',
        ]));

        $student->student()->create([
            'level_id' => 1,
            'address' => '',
            'gender' => Gender::MALE->value,
            'nisn' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'batch' => 2025,
            'status' => StudentStatus::ACTIVE->value,
            'classroom_id' => 1,
        ]);
    }
}
