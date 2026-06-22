@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l1.2 3.8L17 8l-3.8 1.2L12 13l-1.2-3.8L7 8l3.8-1.2L12 3z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 16l.8 2.5L8.5 19l-2.7.8L5 22.5l-.8-2.7L1.5 19l2.7-.5L5 16z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M18 14l.9 2.8L21.7 18l-2.8.9L18 21.7l-.9-2.8L14.3 18l2.8-.9L18 14z"/>
</svg>
