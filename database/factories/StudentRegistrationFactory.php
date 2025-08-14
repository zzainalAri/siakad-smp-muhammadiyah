<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentRegistration>
 */
class StudentRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'             => $this->faker->name(),
            'email'            => $this->faker->unique()->safeEmail(),
            'nisn'             => $this->faker->unique()->numerify('##########'),
            'birth_place'      => $this->faker->city(),
            'birth_date'       => $this->faker->date('Y-m-d', '2010-12-31'),
            'religion'         => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'gender'           => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'address'          => $this->faker->address(),
            'previous_school'  => $this->faker->company() . ' School',
            'phone'            => $this->faker->phoneNumber(),
            'nik'              => $this->faker->unique()->numerify('################'),
            'no_kk'            => $this->faker->unique()->numerify('################'),
            'mother_name'      => $this->faker->name('female'),
            'father_name'      => $this->faker->name('male'),
            'mother_nik'       => $this->faker->unique()->numerify('################'),
            'father_nik'       => $this->faker->unique()->numerify('################'),
        ];
    }
}
