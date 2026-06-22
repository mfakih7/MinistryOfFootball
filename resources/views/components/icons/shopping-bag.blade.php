@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 8h12l-1.2 11H7.2L6 8z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8V6a3 3 0 016 0v2"/>
</svg>
