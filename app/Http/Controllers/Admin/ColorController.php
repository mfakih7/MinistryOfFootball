<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(Request $request): View
    {
        $colors = Color::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.colors.index', compact('colors'));
    }

    public function create(): View
    {
        return view('admin.colors.create');
    }

    public function store(ColorRequest $request): RedirectResponse
    {
        Color::query()->create($request->validated());

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    public function edit(Color $color): View
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(ColorRequest $request, Color $color): RedirectResponse
    {
        $color->update($request->validated());

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $color->delete();

        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully.');
    }
}
