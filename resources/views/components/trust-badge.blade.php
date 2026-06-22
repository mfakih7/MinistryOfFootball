@props([
    'icon' => 'shield',
    'title',
    'description',
])

<div class="trust-badge">
    <div class="trust-badge-icon">
        @if ($icon === 'shield')
            <x-icons.shield class="h-5 w-5" />
        @elseif ($icon === 'truck')
            <x-icons.truck class="h-5 w-5" />
        @elseif ($icon === 'whatsapp')
            <x-icons.whatsapp class="h-5 w-5" />
        @elseif ($icon === 'support')
            <x-icons.phone class="h-5 w-5" />
        @elseif ($icon === 'clock')
            <x-icons.clock class="h-5 w-5" />
        @else
            <x-icons.shield class="h-5 w-5" />
        @endif
    </div>
    <h3 class="trust-badge-title">{{ $title }}</h3>
    <p class="trust-badge-description">{{ $description }}</p>
</div>
