<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $keys = [
            'store_name', 'store_phone', 'store_email', 'store_address',
            'whatsapp_number',
            'instagram_url', 'facebook_url', 'tiktok_url',
            'delivery_fee', 'free_shipping_threshold', 'customization_fee',
            'currency', 'currency_symbol',
            'seo_title', 'seo_description',
            'shipping_policy_content', 'return_policy_content',
            'privacy_policy_content', 'terms_content',
        ];

        $settings = Setting::query()->whereIn('key', $keys)->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings', 'keys'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['nullable', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'store_address' => ['nullable', 'string'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            'customization_fee' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'currency_symbol' => ['nullable', 'string', 'max:5'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'shipping_policy_content' => ['nullable', 'string'],
            'return_policy_content' => ['nullable', 'string'],
            'privacy_policy_content' => ['nullable', 'string'],
            'terms_content' => ['nullable', 'string'],
        ]);

        $groups = [
            'store_name' => 'general', 'currency' => 'general', 'currency_symbol' => 'general',
            'store_phone' => 'contact', 'store_email' => 'contact', 'store_address' => 'contact',
            'whatsapp_number' => 'contact',
            'instagram_url' => 'social', 'facebook_url' => 'social', 'tiktok_url' => 'social',
            'delivery_fee' => 'orders', 'free_shipping_threshold' => 'orders', 'customization_fee' => 'orders',
            'seo_title' => 'seo', 'seo_description' => 'seo',
            'shipping_policy_content' => 'policies', 'return_policy_content' => 'policies',
            'privacy_policy_content' => 'policies', 'terms_content' => 'policies',
        ];

        foreach ($data as $key => $value) {
            Setting::setValue(
                $key,
                $value,
                in_array($key, ['delivery_fee', 'free_shipping_threshold', 'customization_fee'], true) ? 'number' : 'text',
                $groups[$key] ?? null
            );
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
