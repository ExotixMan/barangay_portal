@php
    $pageTitle = 'Incident Report';
    $activePage = 'incident';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/service-overview.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-triangle-exclamation"></i> {{ __('messages.nav_blotter_report') }}</span>
                <h1>{{ __('messages.incident_hero_title') }}</h1>
                <p>{{ __('messages.incident_hero_subtitle') }}</p>
                <div class="bhd-page-actions">
                    <a href="{{ route('incident.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-clipboard-list"></i> {{ __('messages.incident_apply_btn') }}</a>
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-magnifying-glass"></i> {{ __('messages.blade_incident_blade_track_report') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-shield-heart"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.incident_how_to_title') }}</h2>
                <p>{{ __('messages.blade_incident_blade_send_a_concern_with_clear_details_so_barangay_staff_can') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-pen"></i></span><h3>{{ __('messages.incident_step1_title') }}</h3><p>{{ __('messages.incident_step1_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-location-dot"></i></span><h3>{{ __('messages.incident_step2_title') }}</h3><p>{{ __('messages.incident_step2_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-user-shield"></i></span><h3>{{ __('messages.incident_step3_title') }}</h3><p>{{ __('messages.incident_step3_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-bell"></i></span><h3>{{ __('messages.incident_step4_title') }}</h3><p>{{ __('messages.incident_step4_desc') }}</p></article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.incident_stats_title') }}</h2>
                <p>{{ __('messages.incident_stats_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-four-grid">
            <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-clock"></i></span><h3>{{ __('messages.incident_stat_response') }}</h3><p>{{ __('messages.incident_stat_rate_note') }}</p></article>
            <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-check-circle"></i></span><h3>{{ __('messages.incident_stat_resolved') }}</h3><p>{{ __('messages.incident_stat_resolved_note') }}</p></article>
            <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-user-secret"></i></span><h3>{{ __('messages.incident_stat_confidential') }}</h3><p>{{ __('messages.incident_stat_mediation_note') }}</p></article>
            <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-headset"></i></span><h3>{{ __('messages.incident_stat_availability') }}</h3><p>{{ __('messages.incident_stat_active_note') }}</p></article>
        </div>
    </div>
</section>

<section class="bhd-section" id="applySection">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.incident_apply_title') }}</h2>
                <p>{{ __('messages.incident_apply_subtitle') }}</p>
                <div class="bhd-tags"><span>{{ __('messages.blade_incident_blade_noise_complaint') }}</span><span>{{ __('messages.blade_incident_blade_safety_concern') }}</span><span>{{ __('messages.blade_incident_blade_neighborhood_issue') }}</span><span>{{ __('messages.blade_incident_blade_blotter_report') }}</span></div>
            </div>
            <a href="{{ route('incident.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-clipboard-list"></i> {{ __('messages.incident_apply_btn') }}</a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
