<?php

namespace Database\Seeders;

use App\Enums\StockStatus;
use App\Models\Category;
use App\Models\Color;
use App\Models\League;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'slug');
        $leagues = League::query()->pluck('id', 'slug');
        $teams = Team::query()->pluck('id', 'slug');
        $types = ProductType::query()->pluck('id', 'slug');
        $sizes = Size::query()->whereIn('slug', ['s', 'm', 'l', 'xl'])->pluck('id', 'slug');
        $colors = Color::query()->pluck('id', 'slug');

        $products = [
            [
                'name' => 'Arsenal Home Jersey 24/25',
                'slug' => 'arsenal-home-jersey-24-25',
                'sku' => 'MOF-ARS-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'premier-league',
                'team' => 'arsenal',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => null,
                'is_best_seller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'color_slugs' => ['red', 'white'],
            ],
            [
                'name' => 'Manchester United Away Jersey 24/25',
                'slug' => 'manchester-united-away-jersey-24-25',
                'sku' => 'MOF-MUN-AWAY-2425',
                'category' => 'football-jerseys',
                'league' => 'premier-league',
                'team' => 'manchester-united',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => 84.99,
                'is_best_seller' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['white', 'black'],
            ],
            [
                'name' => 'Liverpool Home Jersey 24/25',
                'slug' => 'liverpool-home-jersey-24-25',
                'sku' => 'MOF-LIV-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'premier-league',
                'team' => 'liverpool',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => true,
                'is_new_arrival' => true,
                'color_slugs' => ['red'],
            ],
            [
                'name' => 'Chelsea Third Jersey 24/25',
                'slug' => 'chelsea-third-jersey-24-25',
                'sku' => 'MOF-CHE-THIRD-2425',
                'category' => 'football-jerseys',
                'league' => 'premier-league',
                'team' => 'chelsea',
                'type' => 'football-jersey',
                'price' => 89.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['blue', 'black'],
            ],
            [
                'name' => 'Real Madrid Home Jersey 24/25',
                'slug' => 'real-madrid-home-jersey-24-25',
                'sku' => 'MOF-RMA-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'la-liga',
                'team' => 'real-madrid',
                'type' => 'football-jersey',
                'price' => 99.99,
                'sale_price' => null,
                'is_best_seller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'color_slugs' => ['white'],
            ],
            [
                'name' => 'Barcelona Home Jersey 24/25',
                'slug' => 'barcelona-home-jersey-24-25',
                'sku' => 'MOF-FCB-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'la-liga',
                'team' => 'barcelona',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => null,
                'is_best_seller' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['blue', 'red'],
            ],
            [
                'name' => 'Juventus Home Jersey 24/25',
                'slug' => 'juventus-home-jersey-24-25',
                'sku' => 'MOF-JUV-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'serie-a',
                'team' => 'juventus',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => 79.99,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['black', 'white'],
            ],
            [
                'name' => 'Inter Milan Home Jersey 24/25',
                'slug' => 'inter-milan-home-jersey-24-25',
                'sku' => 'MOF-INT-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'serie-a',
                'team' => 'inter-milan',
                'type' => 'football-jersey',
                'price' => 89.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['blue', 'black'],
            ],
            [
                'name' => 'Bayern Munich Home Jersey 24/25',
                'slug' => 'bayern-munich-home-jersey-24-25',
                'sku' => 'MOF-BAY-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'bundesliga',
                'team' => 'bayern-munich',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => true,
                'is_new_arrival' => false,
                'color_slugs' => ['red'],
            ],
            [
                'name' => 'PSG Home Jersey 24/25',
                'slug' => 'psg-home-jersey-24-25',
                'sku' => 'MOF-PSG-HOME-2425',
                'category' => 'football-jerseys',
                'league' => 'ligue-1',
                'team' => 'psg',
                'type' => 'football-jersey',
                'price' => 94.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['blue', 'red'],
            ],
            [
                'name' => 'Brazil National Home Jersey',
                'slug' => 'brazil-national-home-jersey',
                'sku' => 'MOF-BRA-HOME',
                'category' => 'football-jerseys',
                'league' => 'national-teams',
                'team' => 'brazil',
                'type' => 'football-jersey',
                'price' => 89.99,
                'sale_price' => null,
                'is_best_seller' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['yellow', 'green'],
            ],
            [
                'name' => 'Argentina National Away Jersey',
                'slug' => 'argentina-national-away-jersey',
                'sku' => 'MOF-ARG-AWAY',
                'category' => 'football-jerseys',
                'league' => 'national-teams',
                'team' => 'argentina',
                'type' => 'football-jersey',
                'price' => 89.99,
                'sale_price' => 74.99,
                'is_best_seller' => false,
                'is_featured' => true,
                'is_new_arrival' => false,
                'color_slugs' => ['blue', 'white'],
            ],
            [
                'name' => 'France National Home Jersey',
                'slug' => 'france-national-home-jersey',
                'sku' => 'MOF-FRA-HOME',
                'category' => 'football-jerseys',
                'league' => 'national-teams',
                'team' => 'france',
                'type' => 'football-jersey',
                'price' => 89.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['blue'],
            ],
            [
                'name' => 'LA Lakers Statement Jersey',
                'slug' => 'la-lakers-statement-jersey',
                'sku' => 'MOF-LAL-STMT',
                'category' => 'nba-shirts',
                'league' => 'nba',
                'team' => 'los-angeles-lakers',
                'type' => 'nba-shirt',
                'price' => 79.99,
                'sale_price' => null,
                'is_best_seller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'color_slugs' => ['yellow', 'purple'],
                'color_slugs_fix' => ['yellow', 'navy'],
            ],
            [
                'name' => 'Chicago Bulls Classic Jersey',
                'slug' => 'chicago-bulls-classic-jersey',
                'sku' => 'MOF-CHI-CLASSIC',
                'category' => 'nba-shirts',
                'league' => 'nba',
                'team' => 'chicago-bulls',
                'type' => 'nba-shirt',
                'price' => 79.99,
                'sale_price' => 69.99,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['red', 'black'],
            ],
            [
                'name' => 'Arsenal Club Scarf',
                'slug' => 'arsenal-club-scarf',
                'sku' => 'MOF-ARS-SCARF',
                'category' => 'accessories',
                'league' => 'premier-league',
                'team' => 'arsenal',
                'type' => 'scarf',
                'price' => 24.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['red', 'white'],
                'has_sizes' => false,
            ],
            [
                'name' => 'Football Fan Mug',
                'slug' => 'football-fan-mug',
                'sku' => 'MOF-MUG-001',
                'category' => 'accessories',
                'league' => null,
                'team' => null,
                'type' => 'mug',
                'price' => 14.99,
                'sale_price' => null,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => false,
                'color_slugs' => ['white', 'black'],
                'has_sizes' => false,
            ],
            [
                'name' => 'Team Spirit Bracelet Set',
                'slug' => 'team-spirit-bracelet-set',
                'sku' => 'MOF-BRC-001',
                'category' => 'accessories',
                'league' => null,
                'team' => null,
                'type' => 'bracelet',
                'price' => 19.99,
                'sale_price' => 15.99,
                'is_best_seller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'color_slugs' => ['red', 'blue', 'black'],
                'has_sizes' => false,
            ],
        ];

        foreach ($products as $index => $data) {
            $imageBase = "images/products/{$data['slug']}";
            $colorSlugs = $data['color_slugs_fix'] ?? $data['color_slugs'];

            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'league_id' => $data['league'] ? ($leagues[$data['league']] ?? null) : null,
                    'team_id' => $data['team'] ? ($teams[$data['team']] ?? null) : null,
                    'product_type_id' => $types[$data['type']] ?? null,
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'short_description' => "Premium quality {$data['name']} — official-style design and comfortable fit.",
                    'description' => "Show your passion with the {$data['name']}. High-quality materials, detailed crest printing, and a comfortable athletic fit perfect for match days and everyday wear.",
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'cost_price' => round($data['price'] * 0.45, 2),
                    'weight' => in_array($data['type'], ['football-jersey', 'nba-shirt'], true) ? 0.35 : 0.20,
                    'main_image' => "{$imageBase}/original.jpg",
                    'thumbnail_image' => "{$imageBase}/thumbnail.jpg",
                    'medium_image' => "{$imageBase}/medium.jpg",
                    'large_image' => "{$imageBase}/large.jpg",
                    'is_featured' => $data['is_featured'],
                    'is_new_arrival' => $data['is_new_arrival'],
                    'is_best_seller' => $data['is_best_seller'],
                    'is_customizable' => $data['type'] === 'football-jersey',
                    'is_active' => true,
                    'stock_status' => StockStatus::InStock,
                    'sort_order' => $index + 1,
                    'meta_title' => $data['name'].' | Ministry Of Football',
                    'meta_description' => "Shop {$data['name']} at Ministry Of Football.",
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id, 'original_path' => "{$imageBase}/original.jpg"],
                [
                    'thumbnail_path' => "{$imageBase}/thumbnail.jpg",
                    'medium_path' => "{$imageBase}/medium.jpg",
                    'large_path' => "{$imageBase}/large.jpg",
                    'alt_text' => $data['name'],
                    'sort_order' => 1,
                ]
            );

            $hasSizes = $data['has_sizes'] ?? true;

            if ($hasSizes) {
                foreach ($sizes as $sizeSlug => $sizeId) {
                    foreach ($colorSlugs as $colorSlug) {
                        if (! isset($colors[$colorSlug])) {
                            continue;
                        }

                        $variantSku = strtoupper("{$data['sku']}-".strtoupper($sizeSlug).'-'.strtoupper(substr($colorSlug, 0, 3)));

                        ProductVariant::query()->updateOrCreate(
                            ['sku' => $variantSku],
                            [
                                'product_id' => $product->id,
                                'size_id' => $sizeId,
                                'color_id' => $colors[$colorSlug],
                                'price_adjustment' => 0,
                                'stock_quantity' => random_int(5, 30),
                                'is_active' => true,
                            ]
                        );
                    }
                }
            } else {
                foreach ($colorSlugs as $colorSlug) {
                    if (! isset($colors[$colorSlug])) {
                        continue;
                    }

                    $variantSku = strtoupper("{$data['sku']}-".strtoupper(substr($colorSlug, 0, 3)));

                    ProductVariant::query()->updateOrCreate(
                        ['sku' => $variantSku],
                        [
                            'product_id' => $product->id,
                            'size_id' => null,
                            'color_id' => $colors[$colorSlug],
                            'price_adjustment' => 0,
                            'stock_quantity' => random_int(10, 50),
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
