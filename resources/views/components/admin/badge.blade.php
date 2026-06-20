@props([
    'variant' => 'default',
    'type' => null,
    'label' => '',
])

@php
    if ($variant !== 'default') {
        $resolved = $variant;
    } elseif ($type) {
        $resolved = match ($type) {
            'order-pending', 'stock-limited_stock' => 'warning',
            'order-whatsapp_contacted', 'order-confirmed', 'order-preparing', 'coupon-percentage', 'coupon-fixed', 'featured', 'new', 'best_seller' => 'info',
            'order-delivered', 'stock-in_stock', 'active', 'success' => 'success',
            'order-cancelled', 'stock-out_of_stock', 'danger', 'sale' => 'danger',
            'inactive' => 'default',
            default => 'default',
        };
    } else {
        $resolved = 'default';
    }

    $classes = match ($resolved) {
        'success' => 'admin-status-badge-success',
        'danger' => 'admin-status-badge-danger',
        'warning' => 'admin-status-badge-warning',
        'info' => 'admin-status-badge-info',
        default => 'admin-status-badge-default',
    };
@endphp

<span {{ $attributes->merge(['class' => 'admin-status-badge '.$classes]) }}>
    {{ $label ?: $slot }}
</span>
