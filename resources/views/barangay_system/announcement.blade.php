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
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/announcement.css') }}">
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
@include('shared.js-config', [
    'id' => 'js-config-barangay-system-announcement-1',
    'data' => [
        'v1' => e(url('/announcements')),
        'v2' => e(csrf_token()),
    ],
])
<script src="{{ asset('js/pages/barangay_system/announcement-dynamic1.js') }}"></script>
@endpush

@include('barangay_system.partials.public_layout_end')
