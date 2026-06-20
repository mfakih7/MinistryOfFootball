<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\CartService;
use App\Services\WhatsAppOrderService;
use App\Support\StorageUrl;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $storeViewComposer = function ($view): void {
            $cart = app(CartService::class);
            $whatsapp = app(WhatsAppOrderService::class);

            $view->with([
                'cartCount' => $cart->count(),
                'faviconUrl' => StorageUrl::publicUrl(Setting::getValue('favicon')),
                'storeSettings' => [
                    'store_name' => Setting::getValue('store_name', 'Ministry Of Football'),
                    'tagline' => Setting::getValue('tagline', 'Wear Your Passion'),
                    'store_logo_url' => StorageUrl::publicUrl(Setting::getValue('store_logo')),
                    'store_phone' => Setting::getValue('store_phone'),
                    'store_email' => Setting::getValue('store_email'),
                    'store_address' => Setting::getValue('store_address'),
                    'whatsapp_number' => Setting::getValue('whatsapp_number'),
                    'instagram_url' => Setting::getValue('instagram_url'),
                    'facebook_url' => Setting::getValue('facebook_url'),
                    'tiktok_url' => Setting::getValue('tiktok_url'),
                    'free_shipping_threshold' => Setting::getValue('free_shipping_threshold'),
                    'currency_symbol' => Setting::getValue('currency_symbol', '$'),
                ],
                'whatsappFloatUrl' => $whatsapp->buildInquiryUrl('Hello Ministry Of Football, I would like some help with my order.'),
            ]);
        };

        $adminViewComposer = function ($view): void {
            $view->with([
                'faviconUrl' => StorageUrl::publicUrl(Setting::getValue('favicon')),
                'adminLogoUrl' => StorageUrl::publicUrl(Setting::getValue('admin_logo')),
                'brandingStoreName' => Setting::getValue('store_name', config('app.name')),
            ]);
        };

        View::composer([
            'layouts.app',
            'components.store.header',
            'components.store.footer',
            'components.store.logo-mark',
        ], $storeViewComposer);

        View::composer([
            'layouts.admin',
            'admin.auth.login',
        ], $adminViewComposer);
    }
}
