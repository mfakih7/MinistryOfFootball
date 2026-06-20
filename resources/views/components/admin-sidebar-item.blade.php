@props([
    'label',
    'href' => '#',
    'active' => false,
    'icon' => null,
])

<a
    href="{{ $href }}"
    @class([
        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
        'bg-brand-red text-white' => $active,
        'text-gray-400 hover:bg-gray-900 hover:text-white' => ! $active,
    ])
>
    @if ($icon)
        <span class="flex h-5 w-5 shrink-0 items-center justify-center text-current opacity-70">
            {!! $icon !!}
        </span>
    @endif
    <span>{{ $label }}</span>
</a>
