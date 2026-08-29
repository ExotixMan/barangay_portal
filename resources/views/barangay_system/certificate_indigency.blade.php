@php
    $pageTitle = 'Certificate of Indigency';
    $activePage = 'indigency';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/certificate_indigency.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-hands-helping"></i> {{ __('messages.nav_certificate_indigency') }}</span>
                <h1>{{ __('messages.nav_certificate_indigency') }}</h1>
                <p>{{ __('messages.indigency_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('indigency.form') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-file-circle-plus"></i>
                        {{ __('messages.indigency_apply_btn') }}
                    </a>

                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-magnifying-glass"></i>
                        {{ __('messages.nav_track_request') }}
                    </a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-hands-helping"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.indigency_steps_title') }}</h2>
                <p>{{ __('messages.blade_certificate_indigency_blade_follow_these_simple_steps_to_submit_and_track_your_indi') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-clipboard-list"></i></span>
                <h3>{{ __('messages.indigency_step1_title') }}</h3>
                <p>{{ __('messages.indigency_step1_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-id-card"></i></span>
                <h3>{{ __('messages.indigency_step2_title') }}</h3>
                <p>{{ __('messages.indigency_step2_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-user-check"></i></span>
                <h3>{{ __('messages.indigency_step3_title') }}</h3>
                <p>{{ __('messages.indigency_step3_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-bell"></i></span>
                <h3>{{ __('messages.indigency_step4_title') }}</h3>
                <p>{{ __('messages.indigency_step4_desc') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_certificate_indigency_blade_common_uses') }}</h2>
                <p>{{ __('messages.blade_certificate_indigency_blade_indigency_certificates_are_often_requested_for_assistan') }}</p>
            </div>
        </div>

        <div class="bhd-four-grid">
            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hospital"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_medical_assistance') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_hospital_medicine_or_health_related_assistance') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_medical_aid') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_hospital_support') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-graduation-cap"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_education_support') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_scholarship_school_or_student_assistance_requi') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_scholarship') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_school_aid') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hand-holding-heart"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_financial_assistance') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_social_welfare_and_other_community_assistance_') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_aid_program') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_support') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-scale-balanced"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_legal_requirements') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_when_agencies_require_proof_of_indigency_for_proce') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_document_use') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_verification') }}</span></div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell bhd-two-grid">
        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
            <h3>{{ __('messages.indigency_requirements_title') }}</h3>

            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_prepare_a_clear_government_issued_or_accepted_identific') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_complete_resident_details') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_provide_accurate_personal_contact_and_address_informati') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_purpose_of_request') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_state_why_you_need_the_certificate_of_indigency') }}</span></div></div>
            </div>
        </article>

        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-circle-info"></i></span>
            <h3>{{ __('messages.indigency_important_info_title') }}</h3>

            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-user-shield"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_barangay_review') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_staff_may_verify_your_records_before_approval') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-clock"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_processing_time') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_processing_depends_on_review_and_completeness_of_submit') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-bell"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_use_your_reference_number_to_check_the_application_stat') }}</span></div></div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.indigency_apply_title') }}</h2>
                <p>{{ __('messages.indigency_apply_subtitle') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</span>
                    <span>{{ __('messages.form_review_label_purpose') }}</span>
                    <span>{{ __('messages.blade_certificate_indigency_blade_resident_details') }}</span>
                    <span>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</span>
                </div>
            </div>

            <a href="{{ route('indigency.form') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-file-circle-plus"></i>
                {{ __('messages.indigency_apply_btn') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
