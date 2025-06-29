<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicYear::create([
            'name' => '2024/2025 Ganjil',
            'slug' => Str::slug('2024/2025 Ganjil'),
            'start_date' => '2024-07-15',
            'end_date' => '2024-12-20',
            'semester' => 'Ganjil',
            'is_active' => true 
        ]);

        AcademicYear::create([
            'name' => '2024/2025 Genap',
            'slug' => Str::slug('2024/2025 Genap'),
            'start_date' => '2025-01-13',
            'end_date' => '2025-06-27',
            'semester' => 'Genap',
            'is_active' => false
        ]);
    }
}