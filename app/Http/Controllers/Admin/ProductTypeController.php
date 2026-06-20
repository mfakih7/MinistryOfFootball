<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductTypeRequest;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductTypeController extends Controller
{
    public function index(Request $request): View
    {
        $productTypes = ProductType::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.product-types.index', compact('productTypes'));
    }

    public function create(): View
    {
        return view('admin.product-types.create');
    }

    public function store(ProductTypeRequest $request): RedirectResponse
    {
        ProductType::query()->create($request->validated());

        return redirect()->route('admin.product-types.index')->with('success', 'Product type created successfully.');
    }

    public function edit(ProductType $productType): View
    {
        return view('admin.product-types.edit', compact('productType'));
    }

    public function update(ProductTypeRequest $request, ProductType $productType): RedirectResponse
    {
        $productType->update($request->validated());

        return redirect()->route('admin.product-types.index')->with('success', 'Product type updated successfully.');
    }

    public function destroy(ProductType $productType): RedirectResponse
    {
        $productType->delete();

        return redirect()->route('admin.product-types.index')->with('success', 'Product type deleted successfully.');
    }
}
