@props(['href', 'label' => 'Edit'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'admin-action-btn admin-action-btn-edit']) }}>
    <x-admin.icon.pencil />
    <span>{{ $label }}</span>
</a>
