@php
    $heroMeta = $meta ?? [];
@endphp

<section class="admin-hero">
    <div class="admin-hero-content">
        <div class="min-w-0">
            <div class="admin-hero-icon">
                <i class="{{ $icon ?? 'fas fa-layer-group' }}"></i>
            </div>

            <div class="admin-kicker">
                {{ $kicker ?? 'Administration' }}
            </div>

            <h2>{{ $heading }}</h2>
            <p>{{ $description }}</p>
        </div>

        @hasSection('hero_actions')
            <div class="admin-hero-actions">
                @yield('hero_actions')
            </div>
        @endif
    </div>

    @if(count($heroMeta))
        <div class="admin-hero-meta">
            @foreach($heroMeta as $item)
                <span>
                    <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                    {{ $item['label'] ?? '' }}:
                    <strong>{{ $item['value'] ?? '0' }}</strong>
                </span>
            @endforeach
        </div>
    @endif
</section>
