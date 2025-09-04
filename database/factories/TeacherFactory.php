<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $role = Role::firstOrCreate(['name' => 'Teacher']);
        $user->assignRole($role);

        $teacherPermissions = Permission::whereIn('name', [
            'teachers.classrooms.index',
            'teachers.classrooms.sync',
            'teachers.courses.index',
            'teachers.schedules.index',
            'teachers.dashboard',
            'teachers.courses.show',
        ])->get();


        $role->syncPermissions($teacherPermissions);

        $level = \App\Models\Level::inRandomOrder()->first();

        if (!$level) {
            throw new \Exception('No levels found. Please seed levels first.');
        }

        return [
            'user_id' => $user->id,
            'nip' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'academic_title' => $this->faker->randomElement(['Asisten Ahli', 'Lektor', 'Guru Besar']),
            'level_id' => $level->id,
        ];
    }
}
