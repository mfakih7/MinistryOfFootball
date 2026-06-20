<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function __construct(
        protected ProductImageService $productImages
    ) {}

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $this->productImages->upload($product, $request->file('image'), $request->input('alt_text'));

        return back()->with('success', 'Image uploaded successfully.');
    }

    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $this->productImages->delete($image);

        return back()->with('success', 'Image deleted successfully.');
    }

    public function setMain(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $this->productImages->setAsMain($product, $image);

        return back()->with('success', 'Main image updated.');
    }
}
