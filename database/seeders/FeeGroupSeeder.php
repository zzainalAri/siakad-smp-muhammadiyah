<?php

namespace Database\Seeders;

use App\Models\FeeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeeGroup::factory()->count(10)->create();
    }
}
