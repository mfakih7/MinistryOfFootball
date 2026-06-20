<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomepageSlide;
use App\Models\League;
use App\Models\Product;
use App\Models\Setting;
use App\Services\RecentlyViewedService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected RecentlyViewedService $recentlyViewed
    ) {}

    public function index(): View
    {
        $slides = HomepageSlide::query()->active()->ordered()->get();

        $bestSellers = Product::query()->active()->bestSeller()->with(['images', 'team', 'league'])->ordered()->limit(4)->get();
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::query()->active()->with(['images', 'team', 'league'])->ordered()->limit(4)->get();
        }

        $newArrivals = Product::query()->active()->newArrival()->with(['images', 'team', 'league'])->ordered()->limit(4)->get();
        $featuredProducts = Product::query()->active()->featured()->with(['images', 'team', 'league'])->ordered()->limit(4)->get();
        $categories = Category::query()->active()->ordered()->limit(8)->get();
        $leagues = League::query()->active()->ordered()->limit(8)->get();
        $recentlyViewed = $this->recentlyViewed->products(4);

        $heroFallbackProduct = Product::query()->active()->with('images')->bestSeller()->ordered()->first()
            ?? Product::query()->active()->with('images')->ordered()->first();
        $heroFallbackImage = $heroFallbackProduct?->large_image_url ?? $heroFallbackProduct?->thumbnail_url;
        $heroDescription = Setting::getValue('seo_description');

        return view('home', [
            'title' => Setting::getValue('seo_title', 'Home'),
            'metaDescription' => Setting::getValue('seo_description', 'Shop official-style football jerseys, NBA shirts, and premium accessories at Ministry Of Football. Wear your passion.'),
            'slides' => $slides,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'leagues' => $leagues,
            'recentlyViewed' => $recentlyViewed,
            'heroFallbackImage' => $heroFallbackImage,
            'heroDescription' => $heroDescription,
        ]);
    }
}
