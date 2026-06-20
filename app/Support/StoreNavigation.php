<?php

namespace App\Support;

use Illuminate\Http\Request;

class StoreNavigation
{
    public static function isActive(string $key, ?Request $request = null): bool
    {
        $request ??= request();

        return match ($key) {
            'home' => $request->routeIs('home'),
            'shop' => $request->routeIs('shop', 'product.show', 'search') && ! self::hasShopSectionFilter($request),
            'contact' => $request->routeIs('contact'),
            'track-order' => $request->routeIs('track-order*'),
            'cart' => $request->routeIs('cart', 'checkout*'),
            'clubs' => self::isClubsActive($request),
            'national-teams' => self::matchesFilter($request, ['category' => ['national-teams'], 'league' => ['national-teams']]),
            'nba' => self::matchesFilter($request, ['category' => ['nba', 'nba-shirts'], 'league' => ['nba']]),
            'accessories' => self::matchesFilter($request, ['category' => ['accessories']]),
            'new-arrivals' => self::isNewArrivalsActive($request),
            'sale' => $request->boolean('sale') || $request->query('category') === 'sale',
            default => false,
        };
    }

    public static function navLinkClass(string $key, string $base = 'nav-link'): string
    {
        return self::isActive($key)
            ? $base.' nav-link-active'
            : $base;
    }

    protected static function hasShopSectionFilter(Request $request): bool
    {
        return self::isActive('clubs', $request)
            || self::isActive('national-teams', $request)
            || self::isActive('nba', $request)
            || self::isActive('accessories', $request)
            || self::isActive('new-arrivals', $request)
            || self::isActive('sale', $request);
    }

    protected static function isClubsActive(Request $request): bool
    {
        if ($request->query('category') === 'clubs') {
            return true;
        }

        if ($request->filled('team') && ! self::matchesFilter($request, ['league' => ['national-teams', 'nba']])) {
            return true;
        }

        $clubLeagues = [
            'premier-league', 'la-liga', 'serie-a', 'bundesliga', 'ligue-1', 'clubs',
        ];

        if (in_array($request->query('league'), $clubLeagues, true)) {
            return true;
        }

        if ($request->query('category') === 'football-jerseys' && ! $request->filled('league')) {
            return true;
        }

        return false;
    }

    protected static function isNewArrivalsActive(Request $request): bool
    {
        if ($request->query('category') === 'new-arrivals') {
            return true;
        }

        return $request->routeIs('shop')
            && $request->query('sort') === 'newest'
            && ! $request->hasAny(['category', 'league', 'team', 'sale', 'product_type']);
    }

    /**
     * @param  array<string, array<int, string>>  $filters
     */
    protected static function matchesFilter(Request $request, array $filters): bool
    {
        foreach ($filters as $param => $values) {
            $current = $request->query($param);

            if ($current !== null && in_array($current, $values, true)) {
                return true;
            }
        }

        return false;
    }
}
