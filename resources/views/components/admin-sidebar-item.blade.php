@props([
    'label',
    'href' => '#',
    'active' => false,
    'icon' => null,
])

<a
    href="{{ $href }}"
    @class([
        'admin-sidebar-link',
        'admin-sidebar-link-active' => $active,
    ])
    @if ($active) aria-current="page" @endif
>
    @if ($icon)
        <span class="admin-sidebar-link-icon">
            <x-dynamic-component :component="'icons.'.$icon" class="h-[18px] w-[18px]" />
        </span>
    @endif
    <span class="truncate">{{ $label }}</span>
</a>
