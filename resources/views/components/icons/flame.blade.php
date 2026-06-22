@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c1.5 2.5 4 3.5 4 7a4 4 0 11-8 0c0-3.5 2.5-4.5 4-7z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a2 2 0 100 4 2 2 0 000-4z"/>
</svg>
