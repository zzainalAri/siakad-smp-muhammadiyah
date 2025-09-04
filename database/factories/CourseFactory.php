<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Level;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = \App\Models\Course::class;

    public function definition(): array
    {
        $subjects = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'PKN',
            'Seni Budaya',
            'PJOK',
            'TIK',
            'Bahasa Arab',
            'Prakarya',
            'Pendidikan Agama',
        ];

        $level = Level::inRandomOrder()->first();

        if (!$level) {
            throw new \Exception('No levels found. Please seed levels first.');
        }

        $teacher = Teacher::where('level_id', $level->id)->inRandomOrder()->first();

        if (!$teacher) {
            throw new \Exception("No teacher found for level_id {$level->id}. Please seed teachers.");
        }

        $name = $this->faker->unique()->randomElement($subjects);

        return [
            'name' => $name,
            'code' => strtoupper(substr($name, 0, 3)) . '-' . $this->faker->unique()->randomNumber(3),
            'level_id' => $level->id,
            'teacher_id' => $teacher->id,
        ];
    }
}
