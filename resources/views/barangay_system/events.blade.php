@php
    $pageTitle = 'Events & Projects';
    $activePage = 'events';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/events.css') }}">
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
<script src="{{ asset('js/pages/barangay_system/events.js') }}"></script>
@endpush

@include('barangay_system.partials.public_layout_end')
