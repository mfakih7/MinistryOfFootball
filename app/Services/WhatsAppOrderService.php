<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;

class WhatsAppOrderService
{
    public function buildMessage(Order $order, ?string $city = null): string
    {
        $storeName = Setting::getValue('store_name', 'Ministry Of Football');
        $symbol = Setting::getValue('currency_symbol', '$');
        $city = $city ?? $order->customer?->city;

        $lines = [
            "Hello {$storeName},",
            '',
            'I would like to place this order:',
            '',
            'Order Number: '.$order->order_number,
            '',
            'Customer:',
            'Name: '.$order->customer_name,
            'Phone: '.$order->customer_phone,
            'Address: '.$order->customer_address,
        ];

        if ($city) {
            $lines[] = 'City: '.$city;
        }

        $lines[] = '';
        $lines[] = 'Products:';

        foreach ($order->items as $index => $item) {
            $lines[] = '';
            $lines[] = ($index + 1).'. '.$item->quantity.'x '.$item->product_name;

            if ($item->size_name) {
                $lines[] = '   Size: '.$item->size_name;
            }

            if ($item->color_name) {
                $lines[] = '   Color: '.$item->color_name;
            }

            $lines[] = '   Price: '.$symbol.number_format((float) $item->unit_price, 2);
            $lines[] = '   Total: '.$symbol.number_format((float) $item->total_price, 2);

            if ($item->customization_requested) {
                $lines[] = '   Customization: Yes';
                $lines[] = '   Name/Number: '.$item->customization_details;
                $lines[] = '   Customization Fee: '.$symbol.number_format((float) $item->customization_fee, 2);
            }
        }

        $lines[] = '';
        $lines[] = 'Subtotal: '.$symbol.number_format((float) $order->subtotal, 2);

        if ((float) $order->discount_total > 0) {
            $lines[] = 'Discount: -'.$symbol.number_format((float) $order->discount_total, 2);
        }

        if ((float) $order->customization_total > 0) {
            $lines[] = 'Customization: '.$symbol.number_format((float) $order->customization_total, 2);
        }

        $lines[] = 'Delivery: '.$symbol.number_format((float) $order->delivery_fee, 2);
        $lines[] = 'Total: '.$symbol.number_format((float) $order->total, 2);

        if ($order->customer_notes) {
            $lines[] = '';
            $lines[] = 'Notes:';
            $lines[] = $order->customer_notes;
        }

        $lines[] = '';
        $lines[] = 'Thank you.';

        return implode("\n", $lines);
    }

    public function buildUrl(Order $order): string
    {
        $number = preg_replace('/[^0-9]/', '', (string) Setting::getValue('whatsapp_number', ''));
        $message = $order->whatsapp_message ?? $this->buildMessage($order->loadMissing(['items', 'customer']));

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }

    public function buildInquiryUrl(string $message): string
    {
        $number = preg_replace('/[^0-9]/', '', (string) Setting::getValue('whatsapp_number', ''));

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }

    public function buildCustomerChatUrl(string $phone, ?string $message = null): string
    {
        $number = preg_replace('/[^0-9]/', '', $phone);

        if ($message) {
            return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
        }

        return 'https://wa.me/'.$number;
    }
}
