<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeAcademicYear) {
            throw new \Exception('No active academic year found. Please seed academic years first.');
        }

        $grade = $this->faker->randomElement(['Kelas 7', 'Kelas 8', 'Kelas 9']);

        $letter = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']);

        $className = $grade . $letter;

        $level = Level::where('name', $grade)->first();

        if (!$level) {
            throw new \Exception("Level not found for {$grade}. Please seed levels.");
        }

        return [
            'name' => $className,
            'slug' => Str::slug($className),
            'academic_year_id' => $activeAcademicYear->id,
            'level_id' => $level->id,
        ];
    }
}
