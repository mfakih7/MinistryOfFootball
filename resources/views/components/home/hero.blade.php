@props([
    'slides' => collect(),
    'fallbackImage' => null,
    'heroDescription' => null,
])

@php
    $hasSlides = $slides->isNotEmpty();
    $defaultBg = $fallbackImage ?? 'https://images.unsplash.com/photo-1459865264687-595d652de67e?auto=format&fit=crop&w=1600&q=80';
    $defaultDescription = $heroDescription ?? 'Official jerseys from the world\'s biggest clubs. Premium quality. Unmatched style.';
@endphp

<section
    class="home-hero"
    @if ($hasSlides && $slides->count() > 1)
        x-data="{
            active: 0,
            total: {{ $slides->count() }},
            timer: null,
            start() {
                this.timer = setInterval(() => { this.active = (this.active + 1) % this.total; }, 7000);
            },
            stop() { clearInterval(this.timer); },
            go(i) { this.active = i; this.stop(); this.start(); }
        }"
        x-init="start()"
        @mouseenter="stop()"
        @mouseleave="start()"
    @elseif ($hasSlides)
        x-data="{ active: 0 }"
    @endif
    aria-label="Featured collections"
>
    @if ($hasSlides)
        @foreach ($slides as $index => $slide)
            @php
                $bgUrl = $slide->image ? asset('storage/'.$slide->image) : $defaultBg;
                $shopUrl = $slide->button_url ?: route('shop');
                $isExternal = $shopUrl && (str_starts_with($shopUrl, 'http://') || str_starts_with($shopUrl, 'https://'));
                $ctaLabel = $slide->button_text ?: 'Shop Now';
                $titleLines = preg_split('/\R/', $slide->title, 2);
            @endphp
            <div
                x-show="active === {{ $index }}"
                x-cloak
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="home-hero-slide"
                style="background-image: url('{{ $bgUrl }}');"
            >
                <div class="home-hero-overlay" aria-hidden="true"></div>

                <div class="home-hero-inner">
                    <div class="home-hero-content">
                        <h1 class="home-hero-title">
                            @if (count($titleLines) > 1)
                                <span class="home-hero-title-line">{{ $titleLines[0] }}</span>
                                <span class="home-hero-title-line">{{ $titleLines[1] }}</span>
                            @else
                                @php
                                    $words = explode(' ', $slide->title, 3);
                                    $line1 = implode(' ', array_slice($words, 0, 2));
                                    $line2 = implode(' ', array_slice($words, 2));
                                @endphp
                                <span class="home-hero-title-line">{{ $line1 ?: $slide->title }}</span>
                                @if ($line2)
                                    <span class="home-hero-title-line">{{ $line2 }}</span>
                                @endif
                            @endif
                        </h1>
                        @if ($defaultDescription)
                            <p class="home-hero-description">{{ $defaultDescription }}</p>
                        @endif
                        <div class="home-hero-actions">
                            <a href="{{ $shopUrl }}" @if($isExternal) target="_blank" rel="noopener" @endif class="home-hero-btn-primary">
                                Shop Collection
                                <x-icons.arrow-up-right class="h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($slides->count() > 1)
            <div class="home-hero-dots">
                @foreach ($slides as $index => $slide)
                    <button
                        type="button"
                        @click="go({{ $index }})"
                        :class="active === {{ $index }} ? 'home-hero-dot-active' : ''"
                        class="home-hero-dot"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    @else
        <div class="home-hero-slide" style="background-image: url('{{ $defaultBg }}');">
            <div class="home-hero-overlay" aria-hidden="true"></div>

            <div class="home-hero-inner">
                <div class="home-hero-content">
                    <h1 class="home-hero-title">
                        <span class="home-hero-title-line">Wear Your</span>
                        <span class="home-hero-title-line">Passion</span>
                    </h1>
                    <p class="home-hero-description">{{ $defaultDescription }}</p>
                    <div class="home-hero-actions">
                        <a href="{{ route('shop') }}" class="home-hero-btn-primary">
                            Shop Collection
                            <x-icons.arrow-up-right class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
