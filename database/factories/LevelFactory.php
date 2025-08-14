<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        return [
            'name' => $name = $this->faker->unique()->randomElement(['Kelas 7', 'Kelas 8', 'Kelas 9']),
            'slug' => str()->slug($name),
        ];
    }
}
