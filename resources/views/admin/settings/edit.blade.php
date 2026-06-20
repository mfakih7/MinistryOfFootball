@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Store Settings" description="Configure your store, contact, shipping, and SEO settings." />

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Store</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="admin-label" for="store_name">Store Name</label>
                    <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="store_phone">Phone</label>
                    <input type="text" id="store_phone" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="store_email">Email</label>
                    <input type="email" id="store_email" name="store_email" value="{{ old('store_email', $settings['store_email'] ?? '') }}" class="admin-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="admin-label" for="store_address">Address</label>
                    <textarea id="store_address" name="store_address" rows="3" class="admin-input">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">WhatsApp</h2>
            <div>
                <label class="admin-label" for="whatsapp_number">WhatsApp Number</label>
                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="admin-input max-w-md" placeholder="+961...">
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Social</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label" for="instagram_url">Instagram URL</label>
                    <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="facebook_url">Facebook URL</label>
                    <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="tiktok_url">TikTok URL</label>
                    <input type="url" id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" class="admin-input">
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Shipping</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label" for="delivery_fee">Delivery Fee</label>
                    <input type="number" id="delivery_fee" name="delivery_fee" value="{{ old('delivery_fee', $settings['delivery_fee'] ?? '') }}" min="0" step="0.01" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="free_shipping_threshold">Free Shipping Threshold</label>
                    <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '') }}" min="0" step="0.01" class="admin-input">
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Currency</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label" for="currency">Currency Code</label>
                    <input type="text" id="currency" name="currency" value="{{ old('currency', $settings['currency'] ?? '') }}" class="admin-input" placeholder="USD">
                </div>
                <div>
                    <label class="admin-label" for="currency_symbol">Currency Symbol</label>
                    <input type="text" id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '') }}" class="admin-input" placeholder="$">
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">SEO</h2>
            <div class="grid gap-4">
                <div>
                    <label class="admin-label" for="seo_title">Default SEO Title</label>
                    <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label" for="seo_description">Default SEO Description</label>
                    <textarea id="seo_description" name="seo_description" rows="3" class="admin-input">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card space-y-4">
            <h2 class="text-lg font-bold text-gray-900">Policy Pages</h2>
            <p class="text-sm text-gray-500">Content shown on public shipping, return, privacy, and terms pages.</p>
            <div class="grid gap-4">
                <div>
                    <label class="admin-label" for="shipping_policy_content">Shipping Policy</label>
                    <textarea id="shipping_policy_content" name="shipping_policy_content" rows="5" class="admin-input">{{ old('shipping_policy_content', $settings['shipping_policy_content'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="admin-label" for="return_policy_content">Return Policy</label>
                    <textarea id="return_policy_content" name="return_policy_content" rows="5" class="admin-input">{{ old('return_policy_content', $settings['return_policy_content'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="admin-label" for="privacy_policy_content">Privacy Policy</label>
                    <textarea id="privacy_policy_content" name="privacy_policy_content" rows="5" class="admin-input">{{ old('privacy_policy_content', $settings['privacy_policy_content'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="admin-label" for="terms_content">Terms & Conditions</label>
                    <textarea id="terms_content" name="terms_content" rows="5" class="admin-input">{{ old('terms_content', $settings['terms_content'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="admin-btn-primary">Save Settings</button>
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
