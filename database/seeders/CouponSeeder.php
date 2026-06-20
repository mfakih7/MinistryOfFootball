<?php

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::query()->updateOrCreate(
            ['code' => 'MOF10'],
            [
                'type' => CouponType::Percentage,
                'value' => 10,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addYear(),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ]
        );

        Coupon::query()->updateOrCreate(
            ['code' => 'WELCOME5'],
            [
                'type' => CouponType::Fixed,
                'value' => 5,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ]
        );
    }
}
