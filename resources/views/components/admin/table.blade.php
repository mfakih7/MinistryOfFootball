<div {{ $attributes->merge(['class' => 'admin-table-wrap']) }}>
    <div class="overflow-x-auto">
        <table class="admin-table">
            {{ $slot }}
        </table>
    </div>
    @isset($footer)
        <div class="admin-table-footer">{{ $footer }}</div>
    @endisset
</div>
