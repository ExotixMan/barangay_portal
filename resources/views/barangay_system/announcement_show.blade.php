@php
    $pageTitle = $announcement->title;
    $activePage = 'announcements';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/announcement.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-bullhorn"></i> {{ ucfirst($announcement->category) }}</span>
                <h1>{{ $announcement->title }}</h1>
                <p>
                    <i class="fas fa-calendar"></i>
                    {{ optional($announcement->published_at ?? $announcement->created_at)->format('M d, Y') }}
                    &nbsp;&middot;&nbsp;
                    <i class="fas fa-eye"></i> {{ $announcement->views }} views
                </p>
                <div class="bhd-page-actions">
                    <a href="{{ route('announcements') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-arrow-left"></i> Back to Announcements
                    </a>
                </div>
            </div>
            <div class="bhd-hero-symbol" aria-hidden="true"><i class="fas fa-bullhorn"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <article class="bhd-feature-slide active" style="display:block;">
            @if($announcement->image)
                <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="bhd-feature-image" loading="lazy" style="width:100%;max-height:420px;object-fit:cover;margin-bottom:1.5rem;">
            @endif
            <div class="bhd-feature-content" style="max-width:none;">
                <div style="white-space:pre-line;">{{ $announcement->content }}</div>
            </div>
        </article>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
