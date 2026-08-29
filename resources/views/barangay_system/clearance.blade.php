@php
    $pageTitle = 'Barangay Clearance';
    $activePage = 'clearance';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/service-overview.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-certificate"></i> {{ __('messages.nav_barangay_clearance') }}</span>
                <h1>{{ __('messages.nav_barangay_clearance') }}</h1>
                <p>{{ __('messages.clearance_hero_subtitle') }}</p>
                <div class="bhd-page-actions">
                    <a href="{{ route('clearance.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-file-circle-plus"></i> {{ __('messages.clearance_apply_btn') }}</a>
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-certificate"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.clearance_steps_title') }}</h2>
                <p>{{ __('messages.blade_clearance_blade_follow_these_simple_steps_to_request_and_claim_your_bar') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-clipboard-list"></i></span><h3>{{ __('messages.clearance_step1_title') }}</h3><p>{{ __('messages.clearance_step1_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-id-card"></i></span><h3>{{ __('messages.clearance_step2_title') }}</h3><p>{{ __('messages.clearance_step2_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-user-check"></i></span><h3>{{ __('messages.clearance_step3_title') }}</h3><p>{{ __('messages.clearance_step3_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-check-double"></i></span><h3>{{ __('messages.clearance_step4_title') }}</h3><p>{{ __('messages.clearance_step4_desc') }}</p></article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell bhd-two-grid">
        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
            <h3>{{ __('messages.clearance_requirements_title') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</strong><span>{{ __('messages.blade_clearance_blade_prepare_a_government_issued_or_accepted_identification_') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_clearance_blade_resident_information') }}</strong><span>{{ __('messages.blade_clearance_blade_provide_complete_and_accurate_personal_and_address_deta') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_purpose_of_request') }}</strong><span>{{ __('messages.blade_clearance_blade_state_why_you_need_the_barangay_clearance') }}</span></div></div>
            </div>
        </article>

        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-circle-info"></i></span>
            <h3>{{ __('messages.clearance_important_info_title') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-clock"></i><div><strong>{{ __('messages.track_timeline_processing') }}</strong><span>{{ __('messages.blade_clearance_blade_application_is_checked_by_barangay_staff_before_approva') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-bell"></i><div><strong>{{ __('messages.blade_clearance_blade_notification') }}</strong><span>{{ __('messages.blade_clearance_blade_you_may_track_your_request_using_your_reference_number') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-building"></i><div><strong>{{ __('messages.blade_clearance_blade_claiming') }}</strong><span>{{ __('messages.blade_clearance_blade_visit_the_barangay_hall_when_your_document_is_ready') }}</span></div></div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section" id="apply-form">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.clearance_apply_title') }}</h2>
                <p>{{ __('messages.clearance_apply_btn_title') }}</p>
                <div class="bhd-tags"><span>{{ __('messages.blade_clearance_blade_online_form') }}</span><span>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</span><span>{{ __('messages.incident_form_bring_reference') }}</span><span>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</span></div>
            </div>
            <a href="{{ route('clearance.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-file-circle-plus"></i> {{ __('messages.clearance_apply_btn') }}</a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
