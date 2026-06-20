<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandingUpdateRequest;
use App\Models\Setting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function __construct(
        protected ImageUploadService $images
    ) {}

    public function edit(): View
    {
        $keys = ['store_logo', 'admin_logo', 'favicon', 'store_name', 'tagline'];
        $settings = Setting::query()->whereIn('key', $keys)->pluck('value', 'key');

        return view('admin.branding.edit', [
            'title' => 'Branding',
            'settings' => $settings,
        ]);
    }

    public function update(BrandingUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Setting::setValue('store_name', $data['store_name'] ?? '', 'text', 'branding');
        Setting::setValue('tagline', $data['tagline'] ?? '', 'text', 'branding');

        $this->handleImageUpload($request, 'store_logo', 'branding/store-logo', 400);
        $this->handleImageUpload($request, 'admin_logo', 'branding/admin-logo', 400);
        $this->handleImageUpload($request, 'favicon', 'branding/favicon', 128);

        return back()->with('success', 'Branding saved successfully.');
    }

    protected function handleImageUpload(BrandingUpdateRequest $request, string $key, string $directory, int $maxWidth): void
    {
        if (! $request->hasFile($key)) {
            return;
        }

        $oldPath = Setting::getValue($key);

        if ($oldPath) {
            $this->images->deletePaths([$oldPath]);
        }

        $path = $this->images->storeSimpleImage($request->file($key), $directory, $maxWidth);
        Setting::setValue($key, $path, 'image', 'branding');
    }
}
