<?php

namespace Database\Seeders;

use App\Models\StudentRegistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentRegistration::factory()->count(20)->create();
    }
}
