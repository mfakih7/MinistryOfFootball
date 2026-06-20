<?php

namespace App\Http\Controllers;

use App\Services\ShopQueryService;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __construct(
        protected ShopQueryService $shopQuery
    ) {}

    public function index(): View
    {
        $request = request();
        $filterOptions = $this->shopQuery->filterOptions();

        return view('shop.index', [
            'title' => $this->shopQuery->pageTitle($request),
            'metaDescription' => 'Browse football jerseys, NBA shirts, and accessories at Ministry Of Football. Filter by league, club, size, and more.',
            'products' => $this->shopQuery->paginate($request),
            'filterOptions' => $filterOptions,
            'activeFilters' => $this->shopQuery->activeFilters($request),
            'filters' => $request->all(),
        ]);
    }
}
