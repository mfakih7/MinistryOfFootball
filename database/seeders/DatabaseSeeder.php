<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CatalogSeeder::class,
            ProductSeeder::class,
            SettingSeeder::class,
            HomepageSlideSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
