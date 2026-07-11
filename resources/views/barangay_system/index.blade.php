@php
    $pageTitle = 'Home';
    $activePage = 'home';
@endphp

@php
    $announcementSource = $announcements ?? collect();
    $announcementCollection = $announcementSource instanceof \Illuminate\Pagination\AbstractPaginator
        ? collect($announcementSource->items())
        : collect($announcementSource);
    $announcementList = $announcementCollection->take(3);
    $announcementCount = $announcementSource instanceof \Illuminate\Pagination\AbstractPaginator
        ? $announcementSource->total()
        : $announcementCollection->count();

    $eventSource = $events ?? collect();
    $eventCollection = $eventSource instanceof \Illuminate\Pagination\AbstractPaginator
        ? collect($eventSource->items())
        : collect($eventSource);
    $eventList = $eventCollection->take(3);
    $eventCount = $eventSource instanceof \Illuminate\Pagination\AbstractPaginator
        ? $eventSource->total()
        : $eventCollection->count();
@endphp

@include('barangay_system.partials.public_layout_start')

<section class="bhd-hero">
        <div class="bhd-shell bhd-hero-grid">
            <div>
                <h1 class="bhd-title">{{ __('messages.blade_index_blade_barangay_services_made_clearer') }}</h1>
                <p class="bhd-lead">{{ __('messages.blade_index_blade_request_documents_track_your_application_read_important') }}</p>

                <div class="bhd-hero-actions">
                    <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-file-circle-plus"></i> {{ __('messages.blade_history_blade_request_a_service') }}</a>
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                </div>

                <div class="bhd-mini-stats" aria-label="{{ __('messages.blade_index_blade_barangay_quick_facts') }}">
                    <div class="bhd-mini-stat">
                        <strong>{{ __('messages.blade_index_blade_0_5483_km2') }}</strong>
                        <span>{{ __('messages.blade_index_blade_land_area') }}</span>
                    </div>
                    <div class="bhd-mini-stat">
                        <strong>12,265</strong>
                        <span>{{ __('messages.blade_index_blade_total_population_2024_psa') }}</span>
                    </div>
                    <div class="bhd-mini-stat">
                        <strong>{{ __('messages.blade_index_blade_urban') }}</strong>
                        <span>{{ __('messages.blade_index_blade_barangay_classification') }}</span>
                    </div>
                </div>
            </div>

            <aside class="bhd-pass" aria-label="{{ __('messages.blade_index_blade_request_tracking_preview') }}">
                <div class="bhd-pass-top">
                    <div>
                        <span class="bhd-pass-label">{{ __('messages.blade_index_blade_sample_request') }}</span>
                        <span class="bhd-pass-name">{{ __('messages.nav_barangay_clearance') }}</span>
                    </div>
                    <div class="text-end">
                        <span class="bhd-pass-label">{{ __('messages.blade_index_blade_ref_no') }}</span>
                        <span class="bhd-pass-number">{{ __('messages.blade_index_blade_cl_123') }}</span>
                    </div>
                </div>

                <div class="bhd-process">
                    <div class="bhd-step">
                        <span class="bhd-step-dot"><i class="fas fa-check"></i></span>
                        <div><strong>{{ __('messages.blade_index_blade_submitted') }}</strong><span>{{ __('messages.blade_index_blade_your_form_was_received') }}</span></div>
                        <span class="bhd-status">{{ __('messages.blade_index_blade_done') }}</span>
                    </div>
                    <div class="bhd-step">
                        <span class="bhd-step-dot"><i class="fas fa-spinner"></i></span>
                        <div><strong>{{ __('messages.track_timeline_review') }}</strong><span>{{ __('messages.blade_index_blade_barangay_staff_is_checking_details') }}</span></div>
                        <span class="bhd-status">{{ __('messages.blade_index_blade_now') }}</span>
                    </div>
                    <div class="bhd-step">
                        <span class="bhd-step-dot"><i class="fas fa-flag-checkered"></i></span>
                        <div><strong>{{ __('messages.blade_index_blade_ready_for_release') }}</strong><span>{{ __('messages.blade_index_blade_you_will_be_notified_when_ready') }}</span></div>
                        <span class="bhd-status" style="background:var(--warning-soft);color:var(--warning);">{{ __('messages.blade_index_blade_next') }}</span>
                    </div>
                </div>

                <div class="bhd-pass-note">
                    <i class="fas fa-circle-info me-1"></i>
                    {{ __('messages.blade_index_blade_residents_can_check_status_anytime_using_their_referenc') }}
                </div>
            </aside>
        </div>
    </section>

    <section class="bhd-section" id="services">
        <div class="bhd-shell">
            <div class="bhd-section-head">
                <div>
                    <h2>{{ __('messages.blade_index_blade_what_do_you_need_today') }}</h2>
                    <p>{{ __('messages.blade_index_blade_access_essential_barangay_services_in_one_place_with_cl') }}</p>
                </div>
                <a href="{{ route('services') }}" class="bhd-link">{{ __('messages.blade_index_blade_view_all_services') }} <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="bhd-task-grid">
                <a href="{{ route('clearance') }}" class="bhd-task-card">
                    <span class="bhd-task-icon"><i class="fas fa-file-lines"></i></span>
                    <h3>{{ __('messages.nav_barangay_clearance') }}</h3>
                    <p>{{ __('messages.blade_index_blade_request_your_clearance_for_general_requirements') }}</p>
                    <span class="bhd-task-bottom">{{ __('messages.blade_index_blade_start_request') }} <i class="fas fa-arrow-right"></i></span>
                </a>

                <a href="{{ route('residency') }}" class="bhd-task-card">
                    <span class="bhd-task-icon"><i class="fas fa-house-user"></i></span>
                    <h3>{{ __('messages.nav_certificate_residency') }}</h3>
                    <p>{{ __('messages.blade_index_blade_get_proof_that_you_live_in_barangay_hulong_duhat') }}</p>
                    <span class="bhd-task-bottom">{{ __('messages.blade_index_blade_start_request') }} <i class="fas fa-arrow-right"></i></span>
                </a>

                <a href="{{ route('indigency') }}" class="bhd-task-card">
                    <span class="bhd-task-icon"><i class="fas fa-hands-helping"></i></span>
                    <h3>{{ __('messages.nav_certificate_indigency') }}</h3>
                    <p>{{ __('messages.blade_index_blade_request_assistance_related_certification_online') }}</p>
                    <span class="bhd-task-bottom">{{ __('messages.blade_index_blade_start_request') }} <i class="fas fa-arrow-right"></i></span>
                </a>

                <a href="{{ route('incident') }}" class="bhd-task-card">
                    <span class="bhd-task-icon"><i class="fas fa-triangle-exclamation"></i></span>
                    <h3>{{ __('messages.index_report_title') }}</h3>
                    <p>{{ __('messages.blade_index_blade_send_incident_blotter_or_neighborhood_concerns') }}</p>
                    <span class="bhd-task-bottom">{{ __('messages.incident_form_submit_btn') }} <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </section>

    <section class="bhd-section bhd-section-muted" id="updates">
        <div class="bhd-shell">
            <div class="bhd-updates-panel">
                <div class="bhd-section-head">
                    <div>
                        <h2>{{ __('messages.index_latest_updates_title') }}</h2>
                        <p>{{ __('messages.blade_index_blade_view_important_updates_upcoming_events_and_community_no') }}</p>
                    </div>
                    <a href="{{ route('announcements') }}" class="bhd-link">{{ __('messages.blade_index_blade_view_all_updates') }} <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="bhd-updates-grid">
                    <div>
                        <div class="bhd-list-head">
                            <h3><i class="fas fa-bullhorn"></i> {{ __('messages.nav_announcements') }}</h3>
                            <span class="bhd-count">{{ $announcementCount }} {{ Str::plural('update', $announcementCount) }}</span>
                        </div>

                        @forelse($announcementList as $item)
                            <article class="bhd-news-item">
                                <time class="bhd-date-text">{{ \Carbon\Carbon::parse($item->published_at ?? $item->created_at ?? now())->format('M d') }}</time>
                                <div class="bhd-news-body">
                                    <h4>{{ $item->title ?? __('messages.blade_index_barangay_announcement') }}</h4>
                                    <p>{{ \Illuminate\Support\Str::limit($item->content ?? $item->description ?? __('messages.blade_index_stay_tuned_for_important_updates_from_your_barangay'), 105) }}</p>
                                    <span class="bhd-badge">{{ ucfirst($item->category ?? __('messages.blade_announcement_blade_announcement')) }}</span>
                                </div>
                            </article>
                        @empty
                            <article class="bhd-news-item">
                                <time class="bhd-date-text">{{ __('messages.blade_index_blade_today') }}</time>
                                <div class="bhd-news-body">
                                    <h4>{{ __('messages.blade_index_blade_no_new_announcements') }}</h4>
                                    <p>{{ __('messages.blade_index_blade_important_barangay_updates_will_appear_here_once_availa') }}</p>
                                </div>
                            </article>
                        @endforelse

                        @if($announcementCount > 0)
                            <a href="{{ route('announcements') }}" class="bhd-list-more">
                                {{ __('messages.blade_index_blade_view_more_announcements') }} <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    <div>
                        <div class="bhd-list-head">
                            <h3><i class="fas fa-calendar-days"></i> {{ __('messages.blade_announcement_blade_events') }}</h3>
                            <span class="bhd-count">{{ $eventCount }} {{ Str::plural('event', $eventCount) }}</span>
                        </div>

                        @forelse($eventList as $event)
                            @php
                                $eventDate = \Carbon\Carbon::parse($event->event_date ?? now());
                                $startTime = !empty($event->start_time) ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : null;
                                $endTime = !empty($event->end_time) ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : null;
                            @endphp
                            <article class="bhd-event-item">
                                <time class="bhd-event-date" datetime="{{ $eventDate->toDateString() }}">
                                    <span>{{ $eventDate->format('M') }}</span>
                                    <strong>{{ $eventDate->format('d') }}</strong>
                                </time>
                                <div class="bhd-event-body">
                                    <h4>{{ $event->title ?? __('messages.blade_index_barangay_event') }}</h4>
                                    <p>{{ \Illuminate\Support\Str::limit($event->description ?? __('messages.blade_index_upcoming_barangay_activity'), 95) }}</p>
                                    <div class="bhd-event-meta">
                                        <span><i class="fas fa-location-dot"></i> {{ $event->location ?? __('messages.blade_index_blade_barangay_hall') }}</span>
                                        @if($startTime)
                                            <span><i class="fas fa-clock"></i> {{ $startTime }}@if($endTime) - {{ $endTime }}@endif</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="bhd-event-item">
                                <time class="bhd-event-date">
                                    <span>--</span>
                                    <strong>--</strong>
                                </time>
                                <div class="bhd-event-body">
                                    <h4>{{ __('messages.blade_index_blade_no_upcoming_events') }}</h4>
                                    <p>{{ __('messages.blade_index_blade_events_and_community_activities_will_be_posted_here_onc') }}</p>
                                </div>
                            </article>
                        @endforelse

                        @if($eventCount > 0)
                            <a href="{{ route('events_project') }}" class="bhd-list-more">
                                {{ __('messages.blade_index_blade_view_more_events') }} <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bhd-section" id="about">
        <div class="bhd-shell bhd-about-grid">
            <div class="bhd-photo-card">
                <img src="{{ asset('Images/Barangay_Hall.jpg') }}" alt="{{ __('messages.blade_index_blade_barangay_hall') }}" loading="lazy">
                <div class="bhd-photo-caption"><i class="fas fa-location-dot"></i> {{ __('messages.blade_index_blade_visit_the_barangay_hall_for_releasing_and_personal_assi') }}</div>
            </div>

            <div>
                <div class="bhd-section-head mb-0">
                    <div>
                        <h2>{{ __('messages.index_about_title') }}</h2>
                        <p>{{ __('messages.learn_more') }}</p>
                    </div>
                </div>

                <div class="bhd-feature-list">
                    <div class="bhd-feature">
                        <i class="fas fa-history"></i>
                        <div><strong>{{ __('messages.blade_index_blade_rich_history_since_1861') }}</strong><span>{{ __('messages.blade_index_blade_know_the_background_of_barangay_hulong_duhat') }}</span></div>
                    </div>
                    <div class="bhd-feature">
                        <i class="fas fa-award"></i>
                        <div><strong>{{ __('messages.blade_index_blade_clean_opinion_on_audit_malabon_office_2021') }}</strong><span>{{ __('messages.blade_index_blade_a_highlight_of_public_service_and_accountability') }}</span></div>
                    </div>
                    <div class="bhd-feature">
                        <i class="fas fa-user-friends"></i>
                        <div><strong>{{ __('messages.blade_index_blade_11_dedicated_officials') }}</strong><span>{{ __('messages.blade_index_blade_meet_the_officials_serving_the_community') }}</span></div>
                    </div>
                </div>

                <a href="{{ route('history') }}" class="bhd-link">{{ __('messages.blade_index_blade_learn_more_about_barangay_hulong_duhat') }} <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="bhd-section pt-0">
        <div class="bhd-shell bhd-support-grid">
            <div class="bhd-panel-card">
                <span class="bhd-task-icon"><i class="fas fa-folder-open"></i></span>
                <h3>{{ __('messages.index_services_card_title') }}</h3>
                <p>{{ __('messages.services_info') }}</p>
                <div class="bhd-checks">
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_online_document_requests') }}</span>
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_real_time_request_tracking') }}</span>
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_clear_resident_instructions') }}</span>
                </div>
                <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_request_services') }}</a>
            </div>

            <div class="bhd-panel-card">
                <span class="bhd-task-icon"><i class="fas fa-bell"></i></span>
                <h3>{{ __('messages.index_community_card_title') }}</h3>
                <p>{{ __('messages.community_info') }}</p>
                <div class="bhd-checks">
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_barangay_announcements') }}</span>
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_upcoming_events_and_projects') }}</span>
                    <span><i class="fas fa-check-circle"></i> {{ __('messages.blade_index_blade_useful_reminders_for_residents') }}</span>
                </div>
                <a href="{{ route('announcements') }}" class="bhd-btn bhd-btn-line"><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_stay_updated') }}</a>
            </div>
        </div>
    </section>

    <section class="bhd-section pt-0" id="report">
        <div class="bhd-shell">
            <div class="bhd-report-band">
                <div>
                    <h2>{{ __('messages.index_report_title') }}</h2>
                    <p>{{ __('messages.incident_info') }}</p>
                    <div class="bhd-tags">
                        <span>{{ __('messages.blade_incident_blade_noise_complaint') }}</span>
                        <span>{{ __('messages.blade_index_blade_street_lights') }}</span>
                        <span>{{ __('messages.blade_index_blade_garbage_issue') }}</span>
                        <span>{{ __('messages.blade_incident_blade_safety_concern') }}</span>
                    </div>
                </div>
                <a href="{{ route('incident') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-paper-plane"></i> {{ __('messages.index_report_now_btn') }}</a>
            </div>
        </div>
    </section>

@include('barangay_system.partials.public_layout_end')
