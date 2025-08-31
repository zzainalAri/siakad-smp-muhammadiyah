<?php

namespace Database\Seeders;

use App\Models\FeeGroup;
use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // FeeGroup::factory()->count(10)->create();
        $level_1 = Level::where('name', 'Kelas 7')->first();
        $level_2 = Level::where('name', 'Kelas 8')->first();
        $level_3 = Level::where('name', 'Kelas 9')->first();

        FeeGroup::create(['level_id' => $level_1->id, 'amount' => 200000]);
        FeeGroup::create(['level_id' => $level_2->id, 'amount' => 200000]);
        FeeGroup::create(['level_id' => $level_3->id, 'amount' => 200000]);
    }
}
