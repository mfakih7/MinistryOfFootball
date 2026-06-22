@props(['class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 21h16.5M4.5 3h15l-.75 18H5.25L4.5 3zm3 3v.75M9 6v.75m2.25-2.25v.75M13.5 6v.75m2.25-2.25v.75M18 6v.75M7.5 9.75v.75m2.25-2.25v.75M12 9.75v.75m2.25-2.25v.75M16.5 9.75v.75m-9 3v.75m2.25-2.25v.75M12 12.75v.75m2.25-2.25v.75M16.5 12.75v.75"/>
</svg>
