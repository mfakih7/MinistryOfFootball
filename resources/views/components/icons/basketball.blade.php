@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <circle cx="12" cy="12" r="9"/>
    <path stroke-linecap="round" d="M3.5 9h17M3.5 15h17"/>
    <path stroke-linecap="round" d="M8 4.5c2 2.5 2 12.5 0 15M16 4.5c-2 2.5-2 12.5 0 15"/>
</svg>
