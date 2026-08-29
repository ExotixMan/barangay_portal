@php
    $pageTitle = 'Mission & Vision';
    $activePage = 'mission_vision';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/mission_vision.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker">
                    <i class="fas fa-bullseye"></i>
                    {{ __('messages.mv_hero_title') }}
                </span>

                <h1>{{ __('messages.mv_hero_title') }}</h1>
                <p>{{ __('messages.mv_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-file-circle-plus"></i>
                        {{ __('messages.blade_history_blade_request_a_service') }}
                    </a>

                    <a href="{{ route('officials') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-users"></i>
                        {{ __('messages.blade_history_blade_view_officials') }}
                    </a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-bullseye"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_our_direction') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_the_mission_and_vision_below_guide_how_the_barangay_ser') }}</p>
            </div>
        </div>

        <div class="bhd-mv-grid">
            <article class="bhd-mv-card">
                <div class="bhd-mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>

                <h2>{{ __('messages.mv_mission_title') }}</h2>
                <p>{{ __('messages.mv_mission_subtitle') }}</p>
            </article>

            <article class="bhd-mv-card">
                <div class="bhd-mv-icon">
                    <i class="fas fa-eye"></i>
                </div>

                <h2>{{ __('messages.mv_vision_title') }}</h2>
                <p>{{ __('messages.mv_vision_subtitle') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.mv_values_title') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_simple_principles_that_support_better_resident_service_') }}</p>
            </div>
        </div>

        <div class="bhd-values-grid">
            <article class="bhd-value-card">
                <i class="fas fa-handshake"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_service') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_resident_needs_are_handled_with_care_respect_and_clear_') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-shield-heart"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_integrity') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_public_service_should_remain_transparent_honest_and_acc') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-people-group"></i>
                <h3>{{ __('messages.community') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_barangay_progress_depends_on_cooperation_between_offici') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-laptop"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_accessibility') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_services_should_be_easier_to_understand_request_and_tra') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_service_goals') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_these_goals_connect_the_mission_and_vision_to_practical') }}</p>
            </div>
        </div>

        <div class="bhd-goal-list">
            <article class="bhd-goal-item">
                <div class="bhd-goal-number">01</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_make_barangay_services_easier_to_access') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_residents_can_find_services_submit_requests_and_follow_') }}</p>
                </div>
            </article>

            <article class="bhd-goal-item">
                <div class="bhd-goal-number">02</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_improve_transparency_in_request_processing') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_residents_can_track_request_status_and_receive_updates_') }}</p>
                </div>
            </article>

            <article class="bhd-goal-item">
                <div class="bhd-goal-number">03</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_support_faster_communication') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_announcements_events_and_important_reminders_are_shown_') }}</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-quote-card">
            <blockquote>
                {{ __('messages.blade_mission_vision_blade_a_clearer_and_more_accessible_barangay_service_helps_re') }}
            </blockquote>

            <span>
                <i class="fas fa-location-dot"></i>
                {{ __('messages.blade_mission_vision_blade_barangay_hulong_duhat_resident_service_portal') }}
            </span>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_need_help_from_the_barangay') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_use_the_online_portal_to_request_a_service_track_your_a') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_contacts_blade_clearance') }}</span>
                    <span>{{ __('messages.profile_counter_residency') }}</span>
                    <span>{{ __('messages.profile_counter_indigency') }}</span>
                    <span>{{ __('messages.nav_blotter_report') }}</span>
                </div>
            </div>

            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-file-circle-plus"></i>
                {{ __('messages.blade_contacts_blade_view_services') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
