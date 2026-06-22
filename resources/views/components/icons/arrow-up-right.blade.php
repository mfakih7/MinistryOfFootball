@props(['class' => 'h-4 w-4'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 17L17 7M17 7H9M17 7v8"/>
</svg>
