<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faculties = [
            'Fakultas Ilmu Komputer',
            'Fakultas Teknik',
            'Fakultas Ilmu Sosial dan Ilmu Politik'
        ];

        return [
            'name' => $name = $this->faker->unique()->randomElement($faculties),
            'slug' => str()->slug($name),
            'code' => str()->random(6),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($faculty) {
            $departements = match ($faculty->name) {
                'Fakultas Ilmu Komputer' => [
                    [
                        'name' => $name = 'Teknik Informatika',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                    [
                        'name' => $name = 'Sistem Informasi',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ]
                ],
                'Fakultas Teknik' => [
                    [
                        'name' => $name = 'Teknik Industri',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                    [
                        'name' => $name = 'Teknik Mesin',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                    [
                        'name' => $name = 'Teknik Elektro',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                ],
                'Fakultas Ilmu Sosial dan Ilmu Politik' => [
                    [
                        'name' => $name = 'Ilmu Komunikasi',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                    [
                        'name' => $name = 'Ilmu Pemerintahan',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                    [
                        'name' => $name = 'Hubungan Internasional',
                        'slug' => str()->slug($name),
                        'code' => str()->random(6),
                    ],
                ],
                default => [],
            };

            foreach ($departements as $departement) {
                $faculty->departements()->create([
                    'name' => $departement['name'],
                    'slug' => $departement['slug'],
                    'code' => $departement['code'],
                ]);
            }
        });
    }
}
