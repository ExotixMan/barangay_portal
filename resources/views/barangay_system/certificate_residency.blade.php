@php
    $pageTitle = 'Certificate of Residency';
    $activePage = 'residency';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/service-overview.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-house-user"></i> {{ __('messages.nav_certificate_residency') }}</span>
                <h1>{{ __('messages.nav_certificate_residency') }}</h1>
                <p>{{ __('messages.residency_hero_subtitle') }}</p>
                <div class="bhd-page-actions">
                    <a href="{{ route('residency.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-file-circle-plus"></i> {{ __('messages.residency_apply_btn') }}</a>
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-house-user"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.residency_steps_title') }}</h2>
                <p>{{ __('messages.blade_certificate_residency_blade_request_proof_of_residency_with_clear_requirements_and_') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-clipboard-list"></i></span><h3>{{ __('messages.residency_step1_title') }}</h3><p>{{ __('messages.residency_step1_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-location-dot"></i></span><h3>{{ __('messages.residency_step2_title') }}</h3><p>{{ __('messages.residency_step2_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-user-check"></i></span><h3>{{ __('messages.residency_step3_title') }}</h3><p>{{ __('messages.residency_step3_desc') }}</p></article>
            <article class="bhd-step-card"><span class="bhd-card-icon"><i class="fas fa-bell"></i></span><h3>{{ __('messages.residency_step4_title') }}</h3><p>{{ __('messages.residency_step4_desc') }}</p></article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell bhd-two-grid">
        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
            <h3>{{ __('messages.residency_requirements_title') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_upload_or_present_a_clear_valid_identification_card') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.register_address_label') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_include_house_number_street_purok_and_landmark_if_neede') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.residency_form_step2_label') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_provide_information_needed_to_verify_your_residency') }}</span></div></div>
            </div>
        </article>

        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-circle-info"></i></span>
            <h3>{{ __('messages.residency_important_info_title') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-map"></i><div><strong>{{ __('messages.blade_certificate_residency_blade_barangay_coverage') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_this_certificate_is_for_residents_of_barangay_hulong_du') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-clock"></i><div><strong>{{ __('messages.blade_certificate_residency_blade_review_process') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_staff_may_verify_address_and_identity_details_before_ap') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-magnifying-glass"></i><div><strong>{{ __('messages.blade_certificate_residency_blade_tracking') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_use_your_reference_number_to_check_request_status_anyti') }}</span></div></div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section" id="apply-form">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.residency_apply_title') }}</h2>
                <p>{{ __('messages.residency_apply_subtitle') }}</p>
                <div class="bhd-tags"><span>{{ __('messages.blade_certificate_residency_blade_proof_of_residency') }}</span><span>{{ __('messages.blade_certificate_residency_blade_address_details') }}</span><span>{{ __('messages.blade_certificate_residency_blade_online_request') }}</span><span>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</span></div>
            </div>
            <a href="{{ route('residency.form') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-house-user"></i> {{ __('messages.residency_apply_btn') }}</a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
