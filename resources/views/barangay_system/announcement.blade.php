@php
    $pageTitle = 'Announcements';
    $activePage = 'announcements';

    $announcementItems = $announcements instanceof \Illuminate\Pagination\AbstractPaginator
        ? collect($announcements->items())
        : collect($announcements ?? []);

    $featuredItems = collect($featured ?? []);
    $modalAnnouncements = $announcementItems->merge($featuredItems)->unique('id');
@endphp

@push('styles')
<style>

    .bhd-page-hero {
        padding: 48px 0 28px;
    }

    .bhd-page-hero-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 28px;
        align-items: end;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 30px;
        padding: clamp(24px, 4vw, 42px);
        box-shadow: var(--shadow);
    }

    .bhd-page-hero-card::before {
        content: '';
        position: absolute;
        inset: -140px auto auto -120px;
        width: 330px;
        height: 330px;
        border-radius: 999px;
        background: var(--brand-soft);
        pointer-events: none;
    }

    .bhd-page-kicker {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        width: fit-content;
        margin-bottom: 16px;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 0.78rem;
        font-weight: 950;
    }

    .bhd-page-hero-card h1 {
        position: relative;
        z-index: 1;
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-size: clamp(2.05rem, 5vw, 3.8rem);
        line-height: 0.98;
        letter-spacing: -0.055em;
        margin: 0 0 16px;
        color: var(--ink);
        max-width: 790px;
    }

    .bhd-page-hero-card p {
        position: relative;
        z-index: 1;
        max-width: 650px;
        color: var(--muted);
        font-size: clamp(1rem, 1.5vw, 1.1rem);
        margin: 0;
    }

    .bhd-page-actions {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .bhd-hero-symbol {
        width: 150px;
        height: 150px;
        display: grid;
        place-items: center;
        border-radius: 28px;
        border: 1px dashed var(--line-strong);
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 3rem;
    }

    .bhd-card-icon {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 15px;
        background: var(--brand-soft);
        color: var(--brand);
        margin-bottom: 16px;
    }

    .bhd-simple-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-simple-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-simple-card h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        margin: 0 0 7px;
        line-height: 1.25;
    }

    .bhd-simple-card p {
        color: var(--muted);
        font-size: 0.88rem;
        margin: 0;
    }

    .bhd-two-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .bhd-three-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-four-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-step-list {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        counter-reset: step;
    }

    .bhd-step-card {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px;
    }

    .bhd-step-card::before {
        counter-increment: step;
        content: counter(step, decimal-leading-zero);
        position: absolute;
        top: 14px;
        right: 14px;
        color: var(--brand);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.9rem;
        font-weight: 950;
    }

    .bhd-step-card h3 {
        color: var(--ink);
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-step-card p {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0;
    }

    .bhd-info-list {
        display: grid;
        gap: 12px;
    }

    .bhd-info-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .bhd-info-row i {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 12px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-info-row strong {
        display: block;
        color: var(--ink);
        font-size: 0.92rem;
        font-weight: 950;
    }

    .bhd-info-row span {
        display: block;
        color: var(--muted);
        font-size: 0.84rem;
        margin-top: 1px;
    }

    .bhd-form-card,
    .bhd-table-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: clamp(20px, 3vw, 30px);
        box-shadow: var(--shadow);
    }

    .bhd-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-form-group {
        display: grid;
        gap: 8px;
    }

    .bhd-form-group.full {
        grid-column: 1 / -1;
    }

    .bhd-form-group label {
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 900;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bhd-form-control {
        width: 100%;
        min-height: 46px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface-soft);
        color: var(--ink);
        padding: 11px 13px;
        font: inherit;
        transition: border-color .16s ease, background .16s ease, box-shadow .16s ease;
    }

    .bhd-form-control:focus {
        outline: none;
        border-color: var(--brand);
        background: var(--surface);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand) 14%, transparent);
    }

    .bhd-chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .bhd-chip-list span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
        color: var(--ink-soft);
        font-size: 0.76rem;
        font-weight: 900;
    }

    .bhd-alert {
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .bhd-alert.success {
        border-color: color-mix(in srgb, var(--success) 28%, var(--line));
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-alert.danger {
        border-color: color-mix(in srgb, #dc2626 28%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
    }

    .bhd-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .bhd-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
    }

    .bhd-table th,
    .bhd-table td {
        padding: 13px 12px;
        border-bottom: 1px solid var(--line);
        color: var(--ink-soft);
        font-size: 0.86rem;
        vertical-align: middle;
    }

    .bhd-table th {
        color: var(--muted);
        font-size: 0.74rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: var(--surface-soft);
    }

    .bhd-modal-card {
        border: 0;
        border-radius: 22px;
        overflow: hidden;
        background: var(--surface);
        color: var(--ink);
    }

    .bhd-modal-card .modal-header {
        border-bottom: 1px solid var(--line);
        background: var(--surface-soft);
    }

    .bhd-modal-card .modal-title {
        font-weight: 950;
        color: var(--ink);
    }

    @media (max-width: 1024px) {
        .bhd-four-grid,
        .bhd-step-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-three-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card,
        .bhd-two-grid {
            grid-template-columns: 1fr;
        }

        .bhd-hero-symbol {
            width: 110px;
            height: 110px;
            font-size: 2.3rem;
        }
    }

    @media (max-width: 575px) {
        html {
            overflow-x: hidden;
        }

        body {
            overflow-x: hidden;
        }

        .bhd-page-hero {
            padding-top: 28px;
        }

        .bhd-page-hero-card {
            border-radius: 22px;
            padding: 20px;
        }

        .bhd-page-hero-card h1 {
            font-size: clamp(2rem, 12vw, 2.6rem);
            letter-spacing: -0.05em;
        }

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn,
        .bhd-form-card .bhd-btn {
            width: 100%;
            justify-content: center;
        }

        .bhd-four-grid,
        .bhd-three-grid,
        .bhd-step-list,
        .bhd-form-grid {
            grid-template-columns: 1fr;
        }

        .bhd-section {
            padding-top: 46px;
            padding-bottom: 46px;
        }

        .bhd-section-head {
            gap: 14px;
            margin-bottom: 18px;
        }

        .bhd-section-head h2 {
            font-size: 1.55rem;
            line-height: 1.1;
        }

        .bhd-simple-card,
        .bhd-step-card,
        .bhd-form-card,
        .bhd-table-card {
            border-radius: 18px;
        }

        .modal-dialog {
            margin: 10px;
        }
    }

    .bhd-feature-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
    }

    .bhd-feature-slide {
        display: none;
        min-height: 370px;
        position: relative;
        overflow: hidden;
    }

    .bhd-feature-slide.active {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 0.9fr);
    }

    .bhd-feature-content {
        padding: clamp(22px, 4vw, 38px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 14px;
    }

    .bhd-feature-image {
        width: 100%;
        height: 100%;
        min-height: 340px;
        object-fit: cover;
        background: var(--surface-soft);
    }

    .bhd-announcement-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-announcement-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 22px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-announcement-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-announcement-image {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        background: var(--surface-soft);
    }

    .bhd-announcement-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .bhd-announcement-body h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        line-height: 1.25;
        margin: 10px 0 8px;
    }

    .bhd-announcement-body p {
        color: var(--muted);
        font-size: 0.86rem;
        margin: 0 0 14px;
        flex: 1;
    }

    .bhd-announcement-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid var(--line);
    }

    .bhd-filter-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 220px auto;
        gap: 10px;
        align-items: center;
    }

    .bhd-carousel-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        border-top: 1px solid var(--line);
        background: var(--surface-soft);
    }

    .bhd-carousel-btn,
    .bhd-refresh-btn {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--brand);
        display: grid;
        place-items: center;
    }

    .bhd-carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: var(--line-strong);
        border: 0;
    }

    .bhd-carousel-dot.active {
        background: var(--brand);
        transform: scale(1.2);
    }

    .bhd-feature-modal-image {
        width: 100%;
        max-height: 60vh;
        object-fit: contain;
        border-radius: 16px;
        background: var(--surface-soft);
        margin-bottom: 16px;
    }

    @media (max-width: 1024px) {
        .bhd-announcement-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-filter-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .bhd-feature-slide.active {
            grid-template-columns: 1fr;
        }

        .bhd-feature-image {
            order: -1;
            min-height: 220px;
            max-height: 260px;
        }
    }

    @media (max-width: 575px) {
        .bhd-announcement-grid {
            grid-template-columns: 1fr;
        }

        .bhd-feature-carousel {
            border-radius: 20px;
        }

        .bhd-feature-slide {
            min-height: unset;
        }

        .bhd-announcement-meta .bhd-btn {
            width: 100%;
        }
    }
</style>
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-bullhorn"></i> {{ __('messages.nav_announcements') }}</span>
                <h1>{{ __('messages.announcements_hero_title') }}</h1>
                <p>{{ __('messages.announcements_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="#announcements" class="bhd-btn bhd-btn-primary"><i class="fas fa-list"></i> {{ __('messages.blade_announcement_blade_browse_updates') }}</a>
                    <a href="{{ route('events_project') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-calendar-days"></i> {{ __('messages.blade_announcement_blade_events') }}</a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-bullhorn"></i>
            </div>
        </div>
    </div>
</section>

@if($featuredItems->count())
<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.announcements_featured_title') }}</h2>
                <p>{{ __('messages.announcements_featured_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-feature-carousel" id="announcementCarousel">
            @foreach($featuredItems as $key => $item)
                <article class="bhd-feature-slide {{ $key === 0 ? 'active' : '' }}">
                    <div class="bhd-feature-content">
                        <span class="bhd-status-pill"><i class="fas fa-star"></i> {{ ucfirst($item->category ?? __('messages.announcements_badge_featured')) }}</span>
                        <h2>{{ $item->title }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($item->content ?? '', 190) }}</p>

                        <div class="bhd-chip-list">
                            <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at ?? now())->format('M d, Y') }}</span>
                            <span><i class="fas fa-eye"></i> {{ __('messages.blade_shared_count_views', ['count' => $item->views ?? 0]) }}</span>
                        </div>

                        <button type="button" class="bhd-btn bhd-btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#announcementDetailModal{{ $item->id }}">
                            {{ __('messages.blade_announcement_blade_read_more') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="bhd-feature-image" loading="lazy">
                </article>
            @endforeach

            <div class="bhd-carousel-controls">
                <button class="bhd-carousel-btn prev" type="button" aria-label="{{ __('messages.blade_announcement_blade_previous_announcement') }}">
                    <i class="fas fa-chevron-left"></i>
                </button>

                @foreach($featuredItems as $key => $item)
                    <button class="bhd-carousel-dot {{ $key === 0 ? 'active' : '' }}" type="button" data-index="{{ $key }}" aria-label="{{ __('messages.blade_announcement_go_to_featured_announcement_number', ['number' => $key + 1]) }}"></button>
                @endforeach

                <button class="bhd-carousel-btn next" type="button" aria-label="{{ __('messages.blade_announcement_blade_next_announcement') }}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>
@endif

<section class="bhd-section bhd-section-muted" id="announcements">
    <div class="bhd-shell">
        <div class="bhd-table-card">
            <div class="bhd-section-head">
                <div>
                    <h2>{{ __('messages.announcements_latest_title') }}</h2>
                    <p>{{ $announcementItems->count() }} {{ \Illuminate\Support\Str::plural('announcement', $announcementItems->count()) }} available</p>
                </div>

                <button type="button" class="bhd-refresh-btn" id="refreshAnnouncements" aria-label="{{ __('messages.blade_announcement_blade_refresh_announcements') }}">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>

            <div class="bhd-filter-row mb-4">
                <div class="bhd-form-group">
                    <input type="search" class="bhd-form-control" id="announcementSearch" placeholder="{{ __('messages.announcements_search_placeholder') }}">
                </div>

                <div class="bhd-form-group">
                    <select class="bhd-form-control" id="announcementSort">
                        <option value="latest">{{ __('messages.blade_announcement_blade_latest_first') }}</option>
                        <option value="oldest">{{ __('messages.announcements_sort_oldest') }}</option>
                    </select>
                </div>

                <button type="button" class="bhd-btn bhd-btn-line" id="resetFilters">
                    <i class="fas fa-rotate-left"></i> {{ __('messages.profile_reset_btn') }}
                </button>
            </div>

            @if($announcementItems->count())
                <div class="bhd-announcement-grid" id="announcementsGrid">
                    @foreach($announcementItems as $item)
                        <article class="bhd-announcement-card"
                                 data-title="{{ strtolower($item->title ?? '') }}"
                                 data-content="{{ strtolower($item->content ?? '') }}"
                                 data-date="{{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at ?? now())->timestamp }}">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="bhd-announcement-image" loading="lazy">

                            <div class="bhd-announcement-body">
                                <span class="bhd-status-pill">
                                    <i class="fas fa-tag"></i>
                                    {{ ucfirst($item->category ?? __('messages.blade_announcement_blade_announcement')) }}
                                </span>

                                <h3>{{ $item->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($item->content ?? '', 130) }}</p>

                                <div class="bhd-announcement-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at ?? now())->format('M d, Y') }}
                                    </small>

                                    <button type="button" class="bhd-btn bhd-btn-soft btn-sm" data-bs-toggle="modal" data-bs-target="#announcementDetailModal{{ $item->id }}">
                                        {{ __('messages.track_table_view_btn') }}
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bhd-empty-card">{{ __('messages.blade_announcement_blade_no_announcements_are_posted_yet') }}</div>
            @endif

            <div class="bhd-empty-card mt-3 d-none" id="noResultsMessage">
                {{ __('messages.blade_announcement_blade_no_announcements_matched_your_search') }}
            </div>

            @if($announcements instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

@foreach($modalAnnouncements as $item)
    <div class="modal fade" id="announcementDetailModal{{ $item->id }}" data-slug="{{ $item->slug ?? '' }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bhd-modal-card">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>{{ $item->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>

                <div class="modal-body">
                    @if(!empty($item->image_url))
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="bhd-feature-modal-image" loading="lazy">
                    @endif

                    <div class="bhd-chip-list mb-3">
                        <span><i class="fas fa-tag"></i> {{ ucfirst($item->category ?? __('messages.blade_announcement_blade_announcement')) }}</span>
                        <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at ?? now())->format('M d, Y') }}</span>
                        <span><i class="fas fa-eye"></i> {{ __('messages.blade_shared_count_views', ['count' => $item->views ?? 0]) }}</span>
                    </div>

                    <p class="mb-0" style="white-space: pre-line;">{{ $item->content }}</p>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.bhd-feature-slide');
        const dots = document.querySelectorAll('.bhd-carousel-dot');
        const prevBtn = document.querySelector('.bhd-carousel-btn.prev');
        const nextBtn = document.querySelector('.bhd-carousel-btn.next');
        let current = 0;

        function showSlide(index) {
            if (!slides.length) return;

            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');

            current = index;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                showSlide((current + 1) % slides.length);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                showSlide((current - 1 + slides.length) % slides.length);
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', function () {
                showSlide(index);
            });
        });

        if (slides.length > 1) {
            setInterval(function () {
                showSlide((current + 1) % slides.length);
            }, 7000);
        }

        const refreshBtn = document.getElementById('refreshAnnouncements');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('_', Date.now());
                window.location.href = url.toString();
            });
        }

        const searchInput = document.getElementById('announcementSearch');
        const sortSelect = document.getElementById('announcementSort');
        const grid = document.getElementById('announcementsGrid');
        const noResults = document.getElementById('noResultsMessage');
        const resetFilters = document.getElementById('resetFilters');

        function filterAndSort() {
            if (!grid) return;

            const query = (searchInput?.value || '').toLowerCase().trim();
            const cards = Array.from(grid.querySelectorAll('.bhd-announcement-card'));
            let visibleCount = 0;

            cards.forEach(card => {
                const text = `${card.dataset.title || ''} ${card.dataset.content || ''}`;
                const show = text.includes(query);
                card.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            const sortedCards = cards.sort(function (a, b) {
                const ad = Number(a.dataset.date || 0);
                const bd = Number(b.dataset.date || 0);
                return (sortSelect?.value || 'latest') === 'oldest' ? ad - bd : bd - ad;
            });

            sortedCards.forEach(card => grid.appendChild(card));

            if (noResults) {
                noResults.classList.toggle('d-none', visibleCount !== 0);
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterAndSort);
        if (sortSelect) sortSelect.addEventListener('change', filterAndSort);

        if (resetFilters) {
            resetFilters.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                if (sortSelect) sortSelect.value = 'latest';
                filterAndSort();
            });
        }

        document.querySelectorAll('.modal[id^="announcementDetailModal"]').forEach(function (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function () {
                const slug = modalEl.getAttribute('data-slug');

                if (!slug) return;

                fetch(`{{ url('/announcements') }}/${encodeURIComponent(slug)}/track-view`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                }).catch(function () {});
            });
        });
    });
</script>
@endpush

@include('barangay_system.partials.public_layout_end')
