@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h3M4 12h9M4 17h5"/>
    <circle cx="10" cy="7" r="2"/>
    <circle cx="16" cy="12" r="2"/>
    <circle cx="13" cy="17" r="2"/>
</svg>
