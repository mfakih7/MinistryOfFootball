@props(['href', 'label' => 'View'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'admin-action-btn admin-action-btn-view']) }}>
    <x-admin.icon.eye />
    <span>{{ $label }}</span>
</a>
