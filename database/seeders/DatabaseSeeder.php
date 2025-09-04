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

        $this->call(PermissionSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(ClassroomSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(
            StudentRegistrationSeeder::class,
        );
        $this->call(UserSeeder::class);
        $this->call(FeeGroupSeeder::class);
    }
}
