@php
    $pageTitle = 'Events & Projects';
    $activePage = 'events';
@endphp

@push('styles')
<style>

    .bhd-page-hero {
        padding: 58px 0 30px;
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
        font-size: clamp(2.2rem, 5vw, 4rem);
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

    .bhd-simple-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-four-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-two-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
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

    .bhd-alert {
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .bhd-alert.danger {
        border-color: color-mix(in srgb, #dc2626 28%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
    }

    .bhd-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 999px;
        padding: 6px 10px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 0.76rem;
        font-weight: 950;
        width: fit-content;
    }

    .bhd-table-wrap {
        overflow-x: auto;
    }

    .bhd-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
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

    .bhd-table td strong {
        color: var(--ink);
    }

    .bhd-filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .bhd-filter-btn {
        min-height: 38px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-soft);
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 900;
    }

    .bhd-filter-btn:hover,
    .bhd-filter-btn.active {
        border-color: var(--brand);
        background: var(--brand-soft);
        color: var(--brand);
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


    .bhd-view-more-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        margin-top: 14px;
        padding: 8px 13px;
        border: 1px solid color-mix(in srgb, var(--brand) 32%, var(--line));
        border-radius: 12px;
        background: var(--brand-soft);
        color: var(--brand);
        font: inherit;
        font-size: 0.8rem;
        font-weight: 950;
        cursor: pointer;
        transition: transform .16s ease, border-color .16s ease, background .16s ease;
    }

    .bhd-view-more-btn:hover {
        transform: translateY(-1px);
        border-color: var(--brand);
        background: color-mix(in srgb, var(--brand) 14%, var(--surface));
        color: var(--brand);
    }

    .bhd-detail-modal-label {
        margin-bottom: 12px;
    }

    .bhd-detail-modal-description {
        margin: 0;
        color: var(--ink-soft);
        font-size: 0.95rem;
        line-height: 1.75;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .bhd-detail-modal-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 18px;
    }

    .bhd-detail-modal-meta:empty {
        display: none;
    }

    .bhd-detail-modal-meta span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-size: 0.78rem;
        font-weight: 850;
    }

    .bhd-modal-card .modal-body {
        padding: 24px;
    }

    .bhd-modal-card .btn-close {
        filter: none;
    }

    @media (max-width: 1024px) {
        .bhd-four-grid,
        .bhd-step-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-simple-grid {
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
        .bhd-page-hero {
            padding-top: 36px;
        }

        .bhd-page-hero-card {
            border-radius: 24px;
            padding: 22px;
        }

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn,
        .bhd-form-card .bhd-btn {
            width: 100%;
        }

        .bhd-four-grid,
        .bhd-simple-grid,
        .bhd-step-list,
        .bhd-form-grid {
            grid-template-columns: 1fr;
        }
    }

    .bhd-event-list {
        display: grid;
        gap: 14px;
    }

    .bhd-event-card {
        display: grid;
        grid-template-columns: 78px minmax(0, 1fr);
        gap: 16px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 16px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-event-card:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-event-date {
        width: 70px;
        height: 76px;
        border-radius: 18px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
        display: grid;
        place-items: center;
        align-content: center;
        text-align: center;
    }

    .bhd-event-date span {
        display: block;
        color: var(--brand);
        font-size: 0.7rem;
        font-weight: 950;
        line-height: 1;
        text-transform: uppercase;
    }

    .bhd-event-date strong {
        display: block;
        color: var(--ink);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.7rem;
        line-height: 1;
        margin-top: 4px;
    }

    .bhd-event-card h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-event-card p {
        color: var(--muted);
        font-size: 0.88rem;
        margin: 0;
    }

    .bhd-event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        color: var(--muted);
        font-size: 0.78rem;
        font-weight: 850;
        margin-top: 12px;
    }

    .bhd-project-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    @media (max-width: 575px) {
        .bhd-event-card {
            grid-template-columns: 1fr;
        }

        .bhd-project-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-calendar-days"></i> {{ __('messages.blade_events_blade_community_updates') }}</span>
                <h1>{{ __('messages.events_hero_title') }}</h1>
                <p>{{ __('messages.blade_events_blade_browse_upcoming_events_ongoing_projects_completed_initi') }}</p>
                <div class="bhd-page-actions">
                    <a href="#upcoming-events" class="bhd-btn bhd-btn-primary"><i class="fas fa-calendar-check"></i> {{ __('messages.index_upcoming_events_title') }}</a>
                    <a href="#projects" class="bhd-btn bhd-btn-soft"><i class="fas fa-diagram-project"></i> {{ __('messages.blade_events_blade_view_projects') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-calendar-days"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section" id="upcoming-events">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.index_upcoming_events_title') }}</h2>
                <p>{{ __('messages.blade_events_blade_mark_your_calendars_for_these_upcoming_community_activi') }}</p>
            </div>
        </div>

        <div class="bhd-event-list">
            @forelse($upcomingEvents as $event)
                <article class="bhd-event-card">
                    <time class="bhd-event-date" datetime="{{ \Carbon\Carbon::parse($event->event_date)->toDateString() }}">
                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                        <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</strong>
                    </time>
                    <div>
                        <span class="bhd-status-pill"><i class="fas fa-calendar-check"></i> {{ __('messages.events_badge_upcoming') }}</span>
                        <h3 class="mt-2">{{ $event->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($event->description, 160) }}</p>
                        <div class="bhd-event-meta">
                            @if(!empty($event->location))<span><i class="fas fa-location-dot"></i> {{ $event->location }}</span>@endif
                            @if(!empty($event->start_time))<span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}</span>@endif
                        </div>
                        <button type="button"
                                class="bhd-view-more-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#bhdDetailsModal"
                                data-detail-label="Upcoming Event"
                                data-detail-title="{{ $event->title }}"
                                data-detail-description="{{ $event->description }}"
                                data-detail-date="{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}"
                                data-detail-time="{{ !empty($event->start_time) ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '' }}"
                                data-detail-location="{{ $event->location ?? '' }}"
                                data-detail-attendees=""
                                data-detail-start=""
                                data-detail-target=""
                                data-detail-completed="">
                            <i class="fas fa-up-right-from-square"></i> View More
                        </button>
                    </div>
                </article>
            @empty
                <div class="bhd-empty-card">{{ __('messages.blade_events_blade_no_upcoming_events_are_posted_yet') }}</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted" id="projects">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.events_stat_ongoing') }}</h2>
                <p>{{ __('messages.blade_events_blade_current_initiatives_being_implemented_for_community_dev') }}</p>
            </div>
        </div>

        <div class="bhd-project-grid">
            @forelse($ongoingProjects as $project)
                <article class="bhd-simple-card">
                    <span class="bhd-card-icon"><i class="fas fa-spinner"></i></span>
                    <span class="bhd-status-pill"><i class="fas fa-circle-notch"></i> {{ __('messages.events_badge_ongoing') }}</span>
                    <h3 class="mt-3">{{ $project->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($project->description, 170) }}</p>
                    <div class="bhd-chip-list">
                        @if(!empty($project->start_date))<span><i class="fas fa-calendar-alt"></i> Started: {{ $project->start_date }}</span>@endif
                        @if(!empty($project->expected_completion))<span><i class="fas fa-flag-checkered"></i> Target: {{ $project->expected_completion }}</span>@endif
                    </div>
                    <button type="button"
                            class="bhd-view-more-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#bhdDetailsModal"
                            data-detail-label="Ongoing Project"
                            data-detail-title="{{ $project->title }}"
                            data-detail-description="{{ $project->description }}"
                            data-detail-date=""
                            data-detail-time=""
                            data-detail-location=""
                            data-detail-attendees=""
                            data-detail-start="{{ !empty($project->start_date) ? 'Started: ' . $project->start_date : '' }}"
                            data-detail-target="{{ !empty($project->expected_completion) ? 'Target: ' . $project->expected_completion : '' }}"
                            data-detail-completed="">
                        <i class="fas fa-up-right-from-square"></i> View More
                    </button>
                </article>
            @empty
                <div class="bhd-empty-card">{{ __('messages.blade_events_blade_no_ongoing_projects_are_posted_yet') }}</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.events_stat_completed') }}</h2>
                <p>{{ __('messages.blade_events_blade_successfully_completed_initiatives_that_benefited_the_c') }}</p>
            </div>
        </div>

        <div class="bhd-simple-grid">
            @forelse($completedProjects as $project)
                <article class="bhd-simple-card">
                    <span class="bhd-card-icon"><i class="fas fa-check-circle"></i></span>
                    <h3>{{ $project->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($project->description, 150) }}</p>
                    <div class="bhd-chip-list">
                        @if(!empty($project->expected_completion))<span><i class="fas fa-calendar-check"></i> Completed: {{ $project->expected_completion }}</span>@endif
                    </div>
                    <button type="button"
                            class="bhd-view-more-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#bhdDetailsModal"
                            data-detail-label="Completed Project"
                            data-detail-title="{{ $project->title }}"
                            data-detail-description="{{ $project->description }}"
                            data-detail-date=""
                            data-detail-time=""
                            data-detail-location=""
                            data-detail-attendees=""
                            data-detail-start=""
                            data-detail-target=""
                            data-detail-completed="{{ !empty($project->expected_completion) ? 'Completed: ' . $project->expected_completion : '' }}">
                        <i class="fas fa-up-right-from-square"></i> View More
                    </button>
                </article>
            @empty
                <div class="bhd-empty-card">{{ __('messages.blade_events_blade_no_completed_projects_are_posted_yet') }}</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.events_past_title') }}</h2>
                <p>{{ __('messages.blade_events_blade_a_look_back_at_recent_community_events_and_activities') }}</p>
            </div>
        </div>

        <div class="bhd-event-list">
            @forelse($pastEvents as $event)
                <article class="bhd-event-card">
                    <time class="bhd-event-date" datetime="{{ \Carbon\Carbon::parse($event->event_date)->toDateString() }}">
                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                        <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</strong>
                    </time>
                    <div>
                        <span class="bhd-status-pill"><i class="fas fa-history"></i> {{ __('messages.blade_events_blade_past_event') }}</span>
                        <h3 class="mt-2">{{ $event->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($event->description, 160) }}</p>
                        <div class="bhd-event-meta">
                            @if(!empty($event->attendees))<span><i class="fas fa-users"></i> {{ __('messages.blade_events_count_attendees', ['count' => $event->attendees]) }}</span>@endif
                            @if(!empty($event->location))<span><i class="fas fa-location-dot"></i> {{ $event->location }}</span>@endif
                        </div>
                        <button type="button"
                                class="bhd-view-more-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#bhdDetailsModal"
                                data-detail-label="Past Event"
                                data-detail-title="{{ $event->title }}"
                                data-detail-description="{{ $event->description }}"
                                data-detail-date="{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}"
                                data-detail-time="{{ !empty($event->start_time) ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '' }}"
                                data-detail-location="{{ $event->location ?? '' }}"
                                data-detail-attendees="{{ !empty($event->attendees) ? $event->attendees . ' attendees' : '' }}"
                                data-detail-start=""
                                data-detail-target=""
                                data-detail-completed="">
                            <i class="fas fa-up-right-from-square"></i> View More
                        </button>
                    </div>
                </article>
            @empty
                <div class="bhd-empty-card">{{ __('messages.blade_events_blade_no_past_events_are_posted_yet') }}</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Shared details modal for events and projects -->
<div class="modal fade" id="bhdDetailsModal" tabindex="-1" aria-labelledby="bhdDetailsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content bhd-modal-card">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="bhdDetailsModalTitle">Details</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="bhd-status-pill bhd-detail-modal-label" id="bhdDetailsModalLabel">
                    <i class="fas fa-circle-info"></i>
                    <span>Details</span>
                </span>

                <p class="bhd-detail-modal-description" id="bhdDetailsModalDescription"></p>
                <div class="bhd-detail-modal-meta" id="bhdDetailsModalMeta"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('bhdDetailsModal');

        if (!modalElement) {
            return;
        }

        modalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const titleElement = document.getElementById('bhdDetailsModalTitle');
            const labelElement = document.querySelector('#bhdDetailsModalLabel span');
            const descriptionElement = document.getElementById('bhdDetailsModalDescription');
            const metaElement = document.getElementById('bhdDetailsModalMeta');

            const details = {
                label: button.dataset.detailLabel || 'Details',
                title: button.dataset.detailTitle || 'Details',
                description: button.dataset.detailDescription || 'No description was provided.',
                date: button.dataset.detailDate || '',
                time: button.dataset.detailTime || '',
                location: button.dataset.detailLocation || '',
                attendees: button.dataset.detailAttendees || '',
                start: button.dataset.detailStart || '',
                target: button.dataset.detailTarget || '',
                completed: button.dataset.detailCompleted || ''
            };

            titleElement.textContent = details.title;
            labelElement.textContent = details.label;
            descriptionElement.textContent = details.description;
            metaElement.replaceChildren();

            const metaItems = [
                { icon: 'fa-calendar-days', value: details.date },
                { icon: 'fa-clock', value: details.time },
                { icon: 'fa-location-dot', value: details.location },
                { icon: 'fa-users', value: details.attendees },
                { icon: 'fa-calendar-plus', value: details.start },
                { icon: 'fa-flag-checkered', value: details.target },
                { icon: 'fa-circle-check', value: details.completed }
            ];

            metaItems.forEach(function (item) {
                if (!item.value) {
                    return;
                }

                const chip = document.createElement('span');
                const icon = document.createElement('i');
                const text = document.createElement('span');

                icon.className = 'fas ' + item.icon;
                text.textContent = item.value;
                chip.append(icon, text);
                metaElement.appendChild(chip);
            });
        });
    });
</script>
@endpush

@include('barangay_system.partials.public_layout_end')
