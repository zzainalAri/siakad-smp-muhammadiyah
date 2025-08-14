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

        $grades = ['Kelas 7', 'Kelas 8', 'Kelas 9'];
        $letters = ['A', 'B', 'C', 'D', 'E'];

        static $usedCombinations = [];

        do {
            $grade = $this->faker->randomElement($grades);
            $letter = $this->faker->randomElement($letters);
            $combination = $grade . $letter;
        } while (in_array($combination, $usedCombinations));

        $usedCombinations[] = $combination;

        $level = Level::where('name', $grade)->first();

        if (!$level) {
            throw new \Exception("Level not found for {$grade}. Please seed levels.");
        }

        return [
            'name' => $combination,
            'slug' => Str::slug($combination),
            'academic_year_id' => $activeAcademicYear->id,
            'level_id' => $level->id,
        ];
    }
}
