<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->get('q', ''));

        $products = collect();

        if ($query !== '') {
            $products = Product::query()
                ->active()
                ->with(['images', 'team', 'league'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('sku', 'like', "%{$query}%")
                        ->orWhere('short_description', 'like', "%{$query}%")
                        ->orWhereHas('team', fn ($t) => $t->where('name', 'like', "%{$query}%"))
                        ->orWhereHas('league', fn ($l) => $l->where('name', 'like', "%{$query}%"));
                })
                ->latest()
                ->paginate(12)
                ->withQueryString();
        }

        return view('search.index', compact('query', 'products'));
    }
}
