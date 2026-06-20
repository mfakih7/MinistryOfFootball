@props([
    'slides' => collect(),
    'fallbackImage' => null,
    'heroDescription' => null,
])

@php
    $hasSlides = $slides->isNotEmpty();
    $defaultBg = $fallbackImage ?? 'https://placehold.co/1920x650/0a0a0a/333333?text=Ministry+Of+Football';
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

                <div class="container-store home-hero-inner">
                    <div class="home-hero-content">
                        @if ($slide->subtitle)
                            <p class="home-hero-label">{{ $slide->subtitle }}</p>
                        @endif
                        <h1 class="home-hero-title">{{ $slide->title }}</h1>
                        @if ($heroDescription)
                            <p class="home-hero-description">{{ $heroDescription }}</p>
                        @endif
                        <div class="home-hero-actions">
                            <a href="{{ $shopUrl }}" @if($isExternal) target="_blank" rel="noopener" @endif class="btn-primary home-hero-btn-primary">
                                {{ $ctaLabel }}
                            </a>
                            <a href="{{ route('shop') }}" class="btn-secondary home-hero-btn-secondary">Browse Shop</a>
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

            <div class="container-store home-hero-inner">
                <div class="home-hero-content">
                    <p class="home-hero-label">Official-Style Kits</p>
                    <h1 class="home-hero-title">Wear Your Passion</h1>
                    <p class="home-hero-description">{{ $heroDescription ?? 'Premium football jerseys from top clubs and leagues. NBA shirts and accessories delivered to your door.' }}</p>
                    <div class="home-hero-actions">
                        <a href="{{ route('shop') }}" class="btn-primary home-hero-btn-primary">Shop Now</a>
                        <a href="{{ route('shop') }}" class="btn-secondary home-hero-btn-secondary">Browse Shop</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
