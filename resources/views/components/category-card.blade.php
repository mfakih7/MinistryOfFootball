@props([
    'name',
    'href' => '#',
    'image' => null,
])

<a href="{{ $href }}" class="group relative block overflow-hidden rounded-lg">
    <div class="aspect-[4/3] overflow-hidden bg-gray-900">
        <img
            src="{{ $image ?? 'https://placehold.co/600x450/171717/dc2626?text=' . urlencode($name) }}"
            alt="{{ $name }}"
            loading="lazy"
            class="h-full w-full object-cover opacity-80 transition duration-300 group-hover:scale-105 group-hover:opacity-100"
        >
    </div>
    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/80 via-black/20 to-transparent p-6">
        <h3 class="text-lg font-bold text-white sm:text-xl">{{ $name }}</h3>
    </div>
</a>
