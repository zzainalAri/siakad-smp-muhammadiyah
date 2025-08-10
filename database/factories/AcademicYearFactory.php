<?php

namespace Database\Factories;

use App\Enums\AcademicYearSemester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYears = [2024, 2025, 2026, 2027];
        $startYear = $this->faker->unique()->randomElement($startYears);
        $endYear = $startYear + 1;
        $name = "$startYear/$endYear";

        $startDate = $this->faker->dateTimeBetween("$startYear-07-01", "$startYear-09-30");
        $endDate = (clone $startDate)->modify('+9 months');

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'semester' => $this->faker->randomElement(array_map(fn($item) => $item->value, AcademicYearSemester::cases())),
            'is_active' => false,
        ];
    }
}
