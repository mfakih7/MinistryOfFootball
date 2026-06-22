@extends('layouts.app')

@section('content')
    <div class="container-store py-8 lg:py-12">
        <header class="mb-8">
            <h1 class="section-title">Search</h1>
            @if ($query !== '')
                <p class="mt-2 text-gray-600">
                    @if ($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $products->total() > 0)
                        {{ $products->total() }} result(s) for &ldquo;{{ $query }}&rdquo;
                    @else
                        No results for &ldquo;{{ $query }}&rdquo;
                    @endif
                </p>
            @else
                <p class="mt-2 text-gray-600">Search products by name, SKU, team, or league.</p>
            @endif
        </header>

        <form method="GET" action="{{ route('search') }}" class="store-search-form mb-10">
            <x-icons.search class="store-search-form-icon" />
            <input
                type="search"
                name="q"
                value="{{ $query }}"
                placeholder="Search jerseys, teams, leagues..."
                class="store-search-input"
            >
            <button type="submit" class="store-search-submit">Search</button>
        </form>

        @if ($query === '')
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-16 text-center">
                <p class="text-gray-600">Enter a search term to find products.</p>
            </div>
        @elseif ($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $products->isNotEmpty())
            <div class="store-product-grid">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @else
            <div class="rounded-lg border border-gray-200 bg-white px-6 py-16 text-center">
                <p class="text-lg font-semibold text-gray-900">No products found</p>
                <p class="mt-2 text-sm text-gray-600">Try a different keyword or browse the <a href="{{ route('shop') }}" class="text-brand-red hover:underline">full shop</a>.</p>
            </div>
        @endif
    </div>
@endsection
