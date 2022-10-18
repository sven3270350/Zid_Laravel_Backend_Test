<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Item::factory()->zid()->create(['created_at' => now()->subMonths(3)]);
        Item::factory()->steam()->create(['created_at' => now()->subMonths(3)]);
        Item::factory()->amazon()->create(['created_at' => now()->subMonths(2)]);

        Item::factory()->count(5)->amazon()->create();
        Item::factory()->count(5)->steam()->create();
        Item::factory()->count(5)->zid()->create();
    }
}
