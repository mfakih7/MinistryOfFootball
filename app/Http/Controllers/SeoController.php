<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\League;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/shop'), 'priority' => '0.9'],
            ['loc' => url('/contact'), 'priority' => '0.7'],
            ['loc' => url('/track-order'), 'priority' => '0.7'],
            ['loc' => url('/shipping-policy'), 'priority' => '0.5'],
            ['loc' => url('/return-policy'), 'priority' => '0.5'],
            ['loc' => url('/privacy-policy'), 'priority' => '0.5'],
            ['loc' => url('/terms'), 'priority' => '0.5'],
        ]);

        Product::query()->active()->select('slug', 'updated_at')->each(function ($product) use ($urls) {
            $urls->push([
                'loc' => url('/product/'.$product->slug),
                'lastmod' => $product->updated_at?->toAtomString(),
                'priority' => '0.8',
            ]);
        });

        Category::query()->active()->select('slug', 'updated_at')->each(function ($category) use ($urls) {
            $urls->push([
                'loc' => url('/shop?category='.$category->slug),
                'lastmod' => $category->updated_at?->toAtomString(),
                'priority' => '0.7',
            ]);
        });

        League::query()->active()->select('slug', 'updated_at')->each(function ($league) use ($urls) {
            $urls->push([
                'loc' => url('/shop?league='.$league->slug),
                'lastmod' => $league->updated_at?->toAtomString(),
                'priority' => '0.7',
            ]);
        });

        Team::query()->active()->select('slug', 'updated_at')->each(function ($team) use ($urls) {
            $urls->push([
                'loc' => url('/shop?team='.$team->slug),
                'lastmod' => $team->updated_at?->toAtomString(),
                'priority' => '0.6',
            ]);
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e($entry['loc'])."</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= '    <lastmod>'.e($entry['lastmod'])."</lastmod>\n";
            }
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= '    <priority>'.e($entry['priority'] ?? '0.5')."</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
