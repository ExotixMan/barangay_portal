@php
    $pageTitle = 'Services';
    $activePage = 'services';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/service-overview.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-folder-open"></i> {{ __('messages.blade_services_blade_online_services') }}</span>
                <h1>{{ __('messages.services_hero_title') }}</h1>
                <p>{{ __('messages.services_hero_subtitle') }}</p>
                <div class="bhd-page-actions">
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                    <a href="#available-services" class="bhd-btn bhd-btn-soft"><i class="fas fa-list"></i> {{ __('messages.blade_contacts_blade_view_services') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-file-circle-plus"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section" id="available-services">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_service_title') }}</h2>
                <p>{{ __('messages.services_service_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-four-grid">
            <a href="{{ route('clearance') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-certificate"></i></span>
                <h3>{{ __('messages.nav_barangay_clearance') }}</h3>
                <p>{{ __('messages.services_clearance_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.services_clearance_duration') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('residency') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-house-user"></i></span>
                <h3>{{ __('messages.nav_certificate_residency') }}</h3>
                <p>{{ __('messages.services_residency_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.services_residency_duration') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('indigency') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hands-helping"></i></span>
                <h3>{{ __('messages.nav_certificate_indigency') }}</h3>
                <p>{{ __('messages.blade_services_blade_request_an_assistance_related_certification_with_clear_') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.blade_services_blade_standard_processing') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('incident') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-triangle-exclamation"></i></span>
                <h3>{{ __('messages.nav_blotter_report') }}</h3>
                <p>{{ __('messages.services_blotter_card_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-shield-heart"></i> {{ __('messages.blade_services_blade_report_concern') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.incident_form_submit_btn') }}</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_how_it_works_title') }}</h2>
                <p>{{ __('messages.services_how_it_works_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
                <h3>{{ __('messages.services_step1_title') }}</h3>
                <p>{{ __('messages.services_step1_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-keyboard"></i></span>
                <h3>{{ __('messages.services_step2_title') }}</h3>
                <p>{{ __('messages.services_step2_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-user-check"></i></span>
                <h3>{{ __('messages.services_step3_title') }}</h3>
                <p>{{ __('messages.services_step3_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-bell"></i></span>
                <h3>{{ __('messages.services_step4_title') }}</h3>
                <p>{{ __('messages.services_step4_desc') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_other_title') }}</h2>
                <p>{{ __('messages.services_other_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-simple-grid">
            <a href="{{ route('track_request') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-magnifying-glass"></i></span>
                <h3>{{ __('messages.nav_track_request') }}</h3>
                <p>{{ __('messages.services_track_card_desc') }}</p>
            </a>
            <a href="{{ route('contacts') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-headset"></i></span>
                <h3>{{ __('messages.blade_services_blade_need_help') }}</h3>
                <p>{{ __('messages.services_help_card_desc') }}</p>
            </a>
            <a href="{{ route('announcements') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-bullhorn"></i></span>
                <h3>{{ __('messages.nav_announcements') }}</h3>
                <p>{{ __('messages.blade_services_blade_read_important_reminders_schedules_and_barangay_advisor') }}</p>
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
