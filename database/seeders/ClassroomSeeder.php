<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // if (!$activeAcademicYear) {
        //     $this->command->error('Tidak ada Tahun Ajaran (Academic Year) yang aktif. Silakan buat satu terlebih dahulu.');
        //     return;
        // }

        // Classroom::create(['level_id' => 1, 'name' => 'Kelas 7A', 'academic_year_id' => $activeAcademicYear->id]);
        // Classroom::create(['level_id' => 1, 'name' => 'Kelas 7B', 'academic_year_id' => $activeAcademicYear->id]);

        Classroom::factory()->count(15)->create();
    }
}
