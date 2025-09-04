<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::all()->each(function ($classroom) {
            Student::factory()->count(10)->create([
                'classroom_id' => $classroom->id,
                'level_id' => $classroom->level_id,
            ]);
        });
    }
}
