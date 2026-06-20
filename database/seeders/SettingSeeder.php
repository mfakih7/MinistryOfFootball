<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name', 'value' => 'Ministry Of Football', 'type' => 'text', 'group' => 'general'],
            ['key' => 'tagline', 'value' => 'Wear Your Passion', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'store_logo', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'admin_logo', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'favicon', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'whatsapp_number', 'value' => '+1234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/ministryoffootball', 'type' => 'text', 'group' => 'social'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/ministryoffootball', 'type' => 'text', 'group' => 'social'],
            ['key' => 'tiktok_url', 'value' => 'https://tiktok.com/@ministryoffootball', 'type' => 'text', 'group' => 'social'],
            ['key' => 'store_phone', 'value' => '+1234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'store_email', 'value' => 'orders@ministryoffootball.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'store_address', 'value' => '123 Football Street, Sports City', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'delivery_fee', 'value' => '5.00', 'type' => 'number', 'group' => 'orders'],
            ['key' => 'free_shipping_threshold', 'value' => '75.00', 'type' => 'number', 'group' => 'orders'],
            ['key' => 'currency', 'value' => 'USD', 'type' => 'text', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => '$', 'type' => 'text', 'group' => 'general'],
            ['key' => 'seo_title', 'value' => 'Ministry Of Football | Premium Jerseys & Sportswear', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Shop football jerseys, NBA shirts, and accessories. Wear your passion with Ministry Of Football.', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'shipping_policy_content', 'value' => "We deliver nationwide within 3–5 business days after order confirmation.\n\nDelivery fees apply on orders below the free shipping threshold. You will receive updates via WhatsApp once your order is confirmed and shipped.", 'type' => 'text', 'group' => 'policies'],
            ['key' => 'return_policy_content', 'value' => "Items may be returned within 14 days of delivery if unused and in original packaging.\n\nCustomized jerseys (name/number) are non-returnable unless defective. Contact us via WhatsApp to initiate a return.", 'type' => 'text', 'group' => 'policies'],
            ['key' => 'privacy_policy_content', 'value' => "We collect your name, phone number, and delivery address solely to process and deliver your order.\n\nWe do not sell your personal data. Order information is stored securely and used only for customer support and order fulfillment.", 'type' => 'text', 'group' => 'policies'],
            ['key' => 'terms_content', 'value' => "By placing an order on Ministry Of Football, you agree to our shipping, return, and privacy policies.\n\nPrices and availability are subject to change. Orders are confirmed after WhatsApp verification and payment arrangement.", 'type' => 'text', 'group' => 'policies'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
