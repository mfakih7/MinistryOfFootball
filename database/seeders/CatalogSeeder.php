<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\League;
use App\Models\ProductType;
use App\Models\Size;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Football Jerseys', 'slug' => 'football-jerseys', 'sort_order' => 1],
            ['name' => 'NBA Shirts', 'slug' => 'nba-shirts', 'sort_order' => 2],
            ['name' => 'Accessories', 'slug' => 'accessories', 'sort_order' => 3],
            ['name' => 'Sale', 'slug' => 'sale', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['is_active' => true])
            );
        }

        $leagues = [
            ['name' => 'Premier League', 'slug' => 'premier-league', 'country' => 'England', 'sort_order' => 1],
            ['name' => 'La Liga', 'slug' => 'la-liga', 'country' => 'Spain', 'sort_order' => 2],
            ['name' => 'Serie A', 'slug' => 'serie-a', 'country' => 'Italy', 'sort_order' => 3],
            ['name' => 'Bundesliga', 'slug' => 'bundesliga', 'country' => 'Germany', 'sort_order' => 4],
            ['name' => 'Ligue 1', 'slug' => 'ligue-1', 'country' => 'France', 'sort_order' => 5],
            ['name' => 'National Teams', 'slug' => 'national-teams', 'country' => null, 'sort_order' => 6],
            ['name' => 'NBA', 'slug' => 'nba', 'country' => 'USA', 'sort_order' => 7],
        ];

        foreach ($leagues as $league) {
            League::query()->updateOrCreate(
                ['slug' => $league['slug']],
                array_merge($league, ['is_active' => true])
            );
        }

        $leagueIds = League::query()->pluck('id', 'slug');

        $teams = [
            ['name' => 'Arsenal', 'slug' => 'arsenal', 'league' => 'premier-league', 'country' => 'England', 'sort_order' => 1],
            ['name' => 'Manchester United', 'slug' => 'manchester-united', 'league' => 'premier-league', 'country' => 'England', 'sort_order' => 2],
            ['name' => 'Liverpool', 'slug' => 'liverpool', 'league' => 'premier-league', 'country' => 'England', 'sort_order' => 3],
            ['name' => 'Chelsea', 'slug' => 'chelsea', 'league' => 'premier-league', 'country' => 'England', 'sort_order' => 4],
            ['name' => 'Real Madrid', 'slug' => 'real-madrid', 'league' => 'la-liga', 'country' => 'Spain', 'sort_order' => 5],
            ['name' => 'Barcelona', 'slug' => 'barcelona', 'league' => 'la-liga', 'country' => 'Spain', 'sort_order' => 6],
            ['name' => 'Juventus', 'slug' => 'juventus', 'league' => 'serie-a', 'country' => 'Italy', 'sort_order' => 7],
            ['name' => 'Inter Milan', 'slug' => 'inter-milan', 'league' => 'serie-a', 'country' => 'Italy', 'sort_order' => 8],
            ['name' => 'Bayern Munich', 'slug' => 'bayern-munich', 'league' => 'bundesliga', 'country' => 'Germany', 'sort_order' => 9],
            ['name' => 'PSG', 'slug' => 'psg', 'league' => 'ligue-1', 'country' => 'France', 'sort_order' => 10],
            ['name' => 'Brazil', 'slug' => 'brazil', 'league' => 'national-teams', 'country' => 'Brazil', 'sort_order' => 11],
            ['name' => 'Argentina', 'slug' => 'argentina', 'league' => 'national-teams', 'country' => 'Argentina', 'sort_order' => 12],
            ['name' => 'France', 'slug' => 'france', 'league' => 'national-teams', 'country' => 'France', 'sort_order' => 13],
            ['name' => 'Los Angeles Lakers', 'slug' => 'los-angeles-lakers', 'league' => 'nba', 'country' => 'USA', 'sort_order' => 14],
            ['name' => 'Chicago Bulls', 'slug' => 'chicago-bulls', 'league' => 'nba', 'country' => 'USA', 'sort_order' => 15],
        ];

        foreach ($teams as $team) {
            Team::query()->updateOrCreate(
                ['slug' => $team['slug']],
                [
                    'name' => $team['name'],
                    'league_id' => $leagueIds[$team['league']],
                    'country' => $team['country'],
                    'sort_order' => $team['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $productTypes = [
            ['name' => 'Football Jersey', 'slug' => 'football-jersey', 'sort_order' => 1],
            ['name' => 'NBA Shirt', 'slug' => 'nba-shirt', 'sort_order' => 2],
            ['name' => 'Mug', 'slug' => 'mug', 'sort_order' => 3],
            ['name' => 'Bracelet', 'slug' => 'bracelet', 'sort_order' => 4],
            ['name' => 'Scarf', 'slug' => 'scarf', 'sort_order' => 5],
            ['name' => 'Accessory', 'slug' => 'accessory', 'sort_order' => 6],
        ];

        foreach ($productTypes as $type) {
            ProductType::query()->updateOrCreate(
                ['slug' => $type['slug']],
                array_merge($type, ['is_active' => true])
            );
        }

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($sizes as $index => $size) {
            Size::query()->updateOrCreate(
                ['slug' => Str::slug($size)],
                ['name' => $size, 'sort_order' => $index + 1, 'is_active' => true]
            );
        }

        $colors = [
            ['name' => 'Red', 'hex_code' => '#DC2626'],
            ['name' => 'Blue', 'hex_code' => '#2563EB'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Black', 'hex_code' => '#0A0A0A'],
            ['name' => 'Yellow', 'hex_code' => '#EAB308'],
            ['name' => 'Green', 'hex_code' => '#16A34A'],
            ['name' => 'Navy', 'hex_code' => '#1E3A8A'],
        ];

        foreach ($colors as $index => $color) {
            Color::query()->updateOrCreate(
                ['slug' => Str::slug($color['name'])],
                [
                    'name' => $color['name'],
                    'hex_code' => $color['hex_code'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
