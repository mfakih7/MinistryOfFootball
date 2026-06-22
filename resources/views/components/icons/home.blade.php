@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 9.5V20a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V9.5"/>
</svg>
