@props([
    'variant' => 'light',
    'showText' => true,
])

@php
    $storeName = $storeSettings['store_name'] ?? 'Ministry Of Football';
    $storeLogoUrl = $storeSettings['store_logo_url'] ?? null;
    $tagline = $storeSettings['tagline'] ?? 'Wear Your Passion';
    $isDark = $variant === 'dark';
@endphp

@if ($storeLogoUrl)
    <img
        src="{{ $storeLogoUrl }}"
        alt="{{ $storeName }}"
        {{ $attributes->merge(['class' => 'h-10 w-auto max-w-[160px] object-contain object-left']) }}
    >
@else
    <span @class([
        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full transition',
        'bg-brand-black text-brand-red ring-1 ring-gray-200 group-hover:ring-brand-red' => ! $isDark,
        'bg-white/10 text-brand-red ring-1 ring-white/10' => $isDark,
    ])>
        <x-icons.shield class="h-5 w-5" />
    </span>
@endif

@if ($showText)
    <span class="hidden min-w-0 sm:block">
        <span @class([
            'block truncate text-base font-bold leading-tight tracking-tight lg:text-lg',
            'text-brand-black' => ! $isDark,
            'text-white' => $isDark,
        ])>{{ $storeName }}</span>
        @if ($tagline)
            <span @class([
                'block text-[10px] font-semibold uppercase tracking-[0.2em]',
                'text-gray-500' => ! $isDark,
                'text-gray-500' => $isDark,
            ])>{{ $tagline }}</span>
        @endif
    </span>
@endif
