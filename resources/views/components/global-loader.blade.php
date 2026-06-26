<div
    id="global-loader"
    class="global-loader"
    role="status"
    aria-live="polite"
    aria-hidden="true"
    data-global-loader
>
    <div class="global-loader-ball-wrap">
        <i class="fa-solid fa-futbol global-loader-ball" aria-hidden="true"></i>
        <span class="global-loader-ball-fallback" aria-hidden="true"></span>
    </div>
    <p class="global-loader-text">{{ $text ?? 'Preparing your football gear...' }}</p>
    <span class="sr-only">Loading</span>
</div>
