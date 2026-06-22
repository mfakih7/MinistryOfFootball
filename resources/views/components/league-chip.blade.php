@props([
    'name',
    'href' => '#',
    'logo' => null,
])

@php
    use App\Support\StorageUrl;

    $logoUrl = StorageUrl::publicUrl($logo);
    $initials = collect(explode(' ', $name))
        ->filter()
        ->take(2)
        ->map(fn (string $word) => strtoupper(substr($word, 0, 1)))
        ->join('');
@endphp

<a href="{{ $href }}" class="league-collection-item group">
    @if ($logoUrl)
        <div class="league-logo-circle" aria-hidden="true">
            <img
                src="{{ $logoUrl }}"
                alt=""
                loading="lazy"
                class="league-logo-circle-image"
            >
        </div>
    @else
        <div class="league-logo-circle league-logo-circle--initials" aria-hidden="true">
            <span class="league-collection-initials">{{ $initials }}</span>
        </div>
    @endif
    <span class="league-collection-name">{{ $name }}</span>
</a>
