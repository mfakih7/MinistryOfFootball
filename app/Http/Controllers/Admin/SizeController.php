<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SizeRequest;
use App\Models\Size;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SizeController extends Controller
{
    public function index(Request $request): View
    {
        $sizes = Size::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.sizes.index', compact('sizes'));
    }

    public function create(): View
    {
        return view('admin.sizes.create');
    }

    public function store(SizeRequest $request): RedirectResponse
    {
        Size::query()->create($request->validated());

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    public function edit(Size $size): View
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(SizeRequest $request, Size $size): RedirectResponse
    {
        $size->update($request->validated());

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size): RedirectResponse
    {
        $size->delete();

        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully.');
    }
}
