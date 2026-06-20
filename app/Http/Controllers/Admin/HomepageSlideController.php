<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageSlideRequest;
use App\Models\HomepageSlide;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomepageSlideController extends Controller
{
    public function __construct(
        protected ImageUploadService $images
    ) {}

    public function index(): View
    {
        $slides = HomepageSlide::query()->ordered()->paginate(15);

        return view('admin.homepage-slides.index', compact('slides'));
    }

    public function create(): View
    {
        return view('admin.homepage-slides.create');
    }

    public function store(HomepageSlideRequest $request): RedirectResponse
    {
        HomepageSlide::query()->create(
            $this->slideAttributes($request, $this->images->storeSimpleImage($request->file('image'), 'homepage-slides'))
        );

        return redirect()->route('admin.homepage-slides.index')->with('success', 'Slide created successfully.');
    }

    public function edit(HomepageSlide $homepageSlide): View
    {
        return view('admin.homepage-slides.edit', ['slide' => $homepageSlide]);
    }

    public function update(HomepageSlideRequest $request, HomepageSlide $homepageSlide): RedirectResponse
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            if ($homepageSlide->image) {
                $this->images->deletePaths([$homepageSlide->image]);
            }
            $imagePath = $this->images->storeSimpleImage($request->file('image'), 'homepage-slides');
        }

        $homepageSlide->update($this->slideAttributes($request, $imagePath));

        return redirect()->route('admin.homepage-slides.index')->with('success', 'Slide updated successfully.');
    }

    public function destroy(HomepageSlide $homepageSlide): RedirectResponse
    {
        if ($homepageSlide->image) {
            $this->images->deletePaths([$homepageSlide->image]);
        }

        $homepageSlide->delete();

        return redirect()->route('admin.homepage-slides.index')->with('success', 'Slide deleted successfully.');
    }

    protected function slideAttributes(HomepageSlideRequest $request, ?string $imagePath = null): array
    {
        $data = $request->safe()->only([
            'title',
            'subtitle',
            'button_text',
            'button_url',
            'is_active',
            'sort_order',
        ]);

        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        return $data;
    }
}
