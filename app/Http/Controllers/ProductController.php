<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RecentlyViewedService;
use App\Services\WhatsAppOrderService;
use App\Support\StorageUrl;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected WhatsAppOrderService $whatsapp,
        protected RecentlyViewedService $recentlyViewed
    ) {}

    public function show(string $slug): View
    {
        $product = Product::query()
            ->with(['images', 'variants.size', 'variants.color', 'team', 'league', 'category'])
            ->where('slug', $slug)
            ->active()
            ->first();

        if ($product) {
            $this->recentlyViewed->add($product->id);
        }

        $productName = $product?->name ?? ucwords(str_replace('-', ' ', $slug));
        $galleryImages = $product
            ? $product->images->filter(fn ($image) => StorageUrl::exists($image->original_path))->values()
            : collect();
        $mainImage = $product?->large_image_url ?? $product?->main_image_url;

        $relatedProducts = collect();
        if ($product) {
            $relatedQuery = Product::query()
                ->active()
                ->with('images')
                ->where('id', '!=', $product->id);

            if ($product->team_id || $product->category_id || $product->league_id) {
                $relatedQuery->where(function ($q) use ($product) {
                    if ($product->team_id) {
                        $q->orWhere('team_id', $product->team_id);
                    }
                    if ($product->category_id) {
                        $q->orWhere('category_id', $product->category_id);
                    }
                    if ($product->league_id) {
                        $q->orWhere('league_id', $product->league_id);
                    }
                });
            }

            $relatedProducts = $relatedQuery->ordered()->limit(4)->get();
        }

        $whatsappInquiryUrl = $product
            ? $this->whatsapp->buildInquiryUrl("Hello Ministry Of Football,\n\nI have a question about: {$product->name}\n\nThank you.")
            : null;

        return view('product.show', [
            'title' => $product?->meta_title ?? $productName,
            'metaDescription' => $product?->meta_description ?? $product?->short_description ?? "Shop {$productName} at Ministry Of Football. Premium quality jerseys and sportswear.",
            'ogImage' => $mainImage,
            'ogType' => 'product',
            'canonicalUrl' => route('product.show', $slug),
            'slug' => $slug,
            'product' => $product,
            'productName' => $productName,
            'galleryImages' => $galleryImages,
            'mainImage' => $mainImage,
            'relatedProducts' => $relatedProducts,
            'whatsappInquiryUrl' => $whatsappInquiryUrl,
        ]);
    }
}
