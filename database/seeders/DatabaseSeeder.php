<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use App\Models\Course;
use App\Models\FeeGroup;
use App\Models\Level;
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
        $this->call(TeacherSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(CourseSeeder::class);




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


        $level_1 = Level::where('name', 'Kelas 7')->first();
        $level_2 = Level::where('name', 'Kelas 8')->first();
        $level_3 = Level::where('name', 'Kelas 9')->first();

        FeeGroup::create(['level_id' => $level_1->id, 'amount' => 50000]);
        FeeGroup::create(['level_id' => $level_2->id, 'amount' => 100000]);
        FeeGroup::create(['level_id' => $level_3->id, 'amount' => 150000]);


        // $teacher = User::factory()->create([
        //     'name' => 'Sanji',
        //     'email' => 'sanji@gmail.com',
        // ])->assignRole(Role::create([
        //     'name' => 'Teacher',
        // ]));

        // $teacher->teacher()->create([
        //     'level_id' => 1,
        //     'nip' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
        //     'academic_title' => 'Asisten Ahli'
        // ]);

        // $student = User::factory()->create([
        //     'name' => 'Usop',
        //     'email' => 'usop@gmail.com',
        // ])->assignRole(Role::create([
        //     'name' => 'Student',
        // ]));

        // $student->student()->create([
        //     'level_id' => 1,
        //     'address' => '',
        //     'gender' => Gender::MALE->value,
        //     'nisn' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
        //     'batch' => 2025,
        //     'status' => StudentStatus::ACTIVE->value,
        //     'classroom_id' => 1,
        // ]);
    }
}
