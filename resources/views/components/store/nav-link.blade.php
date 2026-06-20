@props([
    'href',
    'active' => false,
    'mobile' => false,
])

<a
    href="{{ $href }}"
    @class([
        'nav-link' => ! $mobile,
        'nav-link-active' => ! $mobile && $active,
        'nav-link-mobile' => $mobile,
        'nav-link-mobile-active' => $mobile && $active,
    ])
    @if($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
