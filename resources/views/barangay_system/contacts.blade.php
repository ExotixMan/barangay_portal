@php
    $pageTitle = 'Contact';
    $activePage = 'contacts';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/contacts.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-phone"></i> {{ __('messages.blade_contacts_blade_contact_barangay') }}</span>
                <h1>{{ __('messages.blade_contacts_blade_contact_barangay_hulong_duhat') }}</h1>
                <p>{{ __('messages.blade_contacts_blade_reach_the_barangay_office_for_resident_services_concern') }}</p>
                <div class="bhd-page-actions">
                    <a href="tel:82811373" class="bhd-btn bhd-btn-primary"><i class="fas fa-phone"></i> {{ __('messages.blade_contacts_blade_call_office') }}</a>
                    <a href="mailto:barangayhulongduhat@gmail.com" class="bhd-btn bhd-btn-soft"><i class="fas fa-envelope"></i> {{ __('messages.blade_clearance_form_blade_send_email') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-headset"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_contacts_blade_contact_information') }}</h2>
                <p>{{ __('messages.blade_contacts_blade_use_the_official_contact_details_below_for_questions_re') }}</p>
            </div>
        </div>

        <div class="bhd-contact-grid">
            <article class="bhd-simple-card bhd-contact-card">
                <span class="bhd-card-icon"><i class="fas fa-location-dot"></i></span>
                <h3>{{ __('messages.blade_contacts_blade_office_address') }}</h3>
                <p>{{ __('messages.blade_contacts_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city_metro_manil') }}</p>
            </article>

            <article class="bhd-simple-card bhd-contact-card">
                <span class="bhd-card-icon"><i class="fas fa-phone"></i></span>
                <h3>{{ __('messages.blade_contacts_blade_phone') }}</h3>
                <p><a href="tel:82811373">8-281-1373</a></p>
            </article>

            <article class="bhd-simple-card bhd-contact-card">
                <span class="bhd-card-icon"><i class="fas fa-envelope"></i></span>
                <h3>{{ __('messages.form_review_label_email') }}</h3>
                <p><a href="mailto:barangayhulongduhat@gmail.com">{{ __('messages.blade_contacts_blade_barangayhulongduhat_gmail_com') }}</a></p>
            </article>

            <article class="bhd-simple-card bhd-contact-card">
                <span class="bhd-card-icon"><i class="fas fa-clock"></i></span>
                <h3>{{ __('messages.contacts_hours_title') }}</h3>
                <p>{{ __('messages.footer_hours') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell bhd-two-grid">
        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-circle-info"></i></span>
            <h3>{{ __('messages.blade_contacts_blade_before_visiting') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-id-card"></i><div><strong>{{ __('messages.blade_contacts_blade_bring_valid_id') }}</strong><span>{{ __('messages.blade_contacts_blade_prepare_identification_for_document_requests_or_verific') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-hashtag"></i><div><strong>{{ __('messages.blade_contacts_blade_bring_reference_number') }}</strong><span>{{ __('messages.blade_contacts_blade_for_submitted_online_requests_bring_or_show_your_refere') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-clock"></i><div><strong>{{ __('messages.blade_contacts_blade_visit_during_office_hours') }}</strong><span>{{ __('messages.blade_contacts_blade_releasing_and_office_assistance_are_available_on_weekda') }}</span></div></div>
            </div>
        </article>

        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-folder-open"></i></span>
            <h3>{{ __('messages.blade_contacts_blade_common_concerns') }}</h3>
            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-certificate"></i><div><strong>{{ __('messages.blade_contacts_blade_document_requests') }}</strong><span>{{ __('messages.blade_contacts_blade_clearance_residency_indigency_and_other_barangay_certif') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-bullhorn"></i><div><strong>{{ __('messages.nav_announcements') }}</strong><span>{{ __('messages.blade_contacts_blade_ask_about_updates_events_projects_and_schedules') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-triangle-exclamation"></i><div><strong>{{ __('messages.blade_contacts_blade_community_concerns') }}</strong><span>{{ __('messages.blade_contacts_blade_report_incidents_safety_concerns_or_neighborhood_issues') }}</span></div></div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_contacts_blade_need_a_barangay_service') }}</h2>
                <p>{{ __('messages.blade_contacts_blade_you_can_request_documents_report_concerns_or_track_appl') }}</p>
                <div class="bhd-tags"><span>{{ __('messages.blade_contacts_blade_clearance') }}</span><span>{{ __('messages.profile_counter_residency') }}</span><span>{{ __('messages.nav_blotter_report') }}</span><span>{{ __('messages.nav_track_request') }}</span></div>
            </div>
            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-folder-open"></i> {{ __('messages.blade_contacts_blade_view_services') }}</a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
