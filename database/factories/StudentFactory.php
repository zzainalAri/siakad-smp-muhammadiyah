<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Level;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Enums\Gender;
use App\Enums\StudentStatus;
use Spatie\Permission\Models\Permission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $user = User::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $role = Role::firstOrCreate(['name' => 'Student']);
        $user->assignRole($role);

        $studentPermissions = Permission::whereIn('name', [
            'students.fees.index',
            'students.schedules.index',
        ])->get();


        $role->syncPermissions($studentPermissions);

        $classroom = \App\Models\Classroom::inRandomOrder()->first();

        if (!$classroom) {
            throw new \Exception('No classrooms found. Please seed classrooms first.');
        }

        $batch = ['2020', '2021', '2022', '2023', '2024', '2025'];

        return [
            'user_id' => $user->id,
            'nisn' => $this->faker->unique()->numerify('##########'),
            'address' => $this->faker->address,
            'gender' => $this->faker->randomElement([\App\Enums\Gender::MALE->value, \App\Enums\Gender::FEMALE->value]),
            'status' => \App\Enums\StudentStatus::ACTIVE->value,
            'batch' => $this->faker->randomElement($batch),
            'classroom_id' => $classroom->id,
            'level_id' => $classroom->level_id,
        ];
    }
}
