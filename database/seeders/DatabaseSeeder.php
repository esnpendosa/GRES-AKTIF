<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AssetCategorySeeder::class,
            GresikRegionSeeder::class,
            BadgeSeeder::class,
            UserSeeder::class,
            AssetSeeder::class,
        ]);
    }
}
