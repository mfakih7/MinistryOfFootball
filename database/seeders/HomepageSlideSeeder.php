<?php

namespace Database\Seeders;

use App\Models\HomepageSlide;
use Illuminate\Database\Seeder;

class HomepageSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Wear Your Passion',
                'subtitle' => 'New Season Collection',
                'image' => 'images/slides/hero-wear-your-passion.jpg',
                'button_text' => 'Shop Now',
                'button_url' => '/shop',
                'sort_order' => 1,
            ],
            [
                'title' => 'New Season Kits',
                'subtitle' => '24/25 Arrivals',
                'image' => 'images/slides/new-season-kits.jpg',
                'button_text' => 'Explore New Kits',
                'button_url' => '/shop?sort=newest',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $slide) {
            HomepageSlide::query()->updateOrCreate(
                ['title' => $slide['title']],
                array_merge($slide, ['is_active' => true])
            );
        }
    }
}
