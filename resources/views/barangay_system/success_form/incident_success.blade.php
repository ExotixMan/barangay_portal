<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.blade_incident_success_blade_incident_report_submitted') }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/success_form/incident_success.css') }}">
    @include('shared.public-theme')
</head>
<body>
@php
    $submittedDateText = $date_submitted ?? now()->format('M d, Y');
@endphp

<main class="main-layout" id="mainContent">
    <div class="page-shell success-grid">
        <section class="success-card" aria-labelledby="successTitle">
            <div class="status-row">
                <div class="status-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
                <div>
                    <span class="status-kicker">{{ __('messages.track_timeline_report_submitted') }}</span>
                    <p>{{ __('messages.blade_incident_success_blade_your_report_is_now_recorded_for_barangay_review') }}</p>
                </div>
            </div>

            <h1 class="title" id="successTitle">{{ __('messages.blade_incident_success_blade_your_incident_report_was_received') }}</h1>
            <p class="lead">{{ __('messages.blade_incident_success_blade_save_your_reference_number_barangay_staff_may_contact_y') }}</p>

            <div class="reference-panel" aria-label="{{ __('messages.incident_form_bring_reference') }}">
                <div>
                    <span class="ref-label">{{ __('messages.incident_form_bring_reference') }}</span>
                    <strong class="reference-number" id="referenceNumber">{{ $reference }}</strong>
                </div>
                <button type="button" class="copy-ref" id="copyReference" data-copy-target="referenceNumber">
                    <i class="fas fa-copy"></i> {{ __('messages.blade_incident_success_blade_copy') }}
                </button>
            </div>

            <div class="details-list" aria-label="{{ __('messages.blade_incident_success_blade_report_summary') }}">
                <div class="mini-detail">
                    <span>{{ __('messages.blade_incident_success_blade_complainant') }}</span>
                    <strong>{{ $complaint_name ?? __('messages.blade_success_fallback_complainant') }}</strong>
                </div>
                <div class="mini-detail">
                    <span>{{ __('messages.profile_table_date') }}</span>
                    <strong>{{ $submittedDateText }}</strong>
                </div>
            </div>

            <div class="privacy-note">
                <strong>{{ __('messages.blade_incident_success_blade_reminder') }}</strong> {{ __('messages.blade_incident_success_blade_keep_your_reference_number_private_and_use_it_only_when') }}
            </div>

            <div class="actions" aria-label="{{ __('messages.blade_incident_success_blade_next_actions') }}" style="margin-top: 18px;">
                <a href="{{ route('track_request') }}" class="action-btn action-primary">
                    <i class="fas fa-search"></i> {{ __('messages.blade_incident_blade_track_report') }}
                </a>
                <button type="button" class="action-btn action-secondary" id="printReport">
                    <i class="fas fa-print"></i> {{ __('messages.blade_incident_success_blade_print_summary') }}
                </button>
                <a href="{{ route('incident') }}" class="action-btn action-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.blade_incident_success_blade_back_to_incident_page') }}
                </a>
            </div>
        </section>

        <aside class="side-card" aria-label="{{ __('messages.blade_incident_success_blade_helpful_information') }}">
            <div class="side-section">
                <h2>{{ __('messages.track_timeline_current_status') }}</h2>
                <span class="status-pill"><i class="fas fa-shield-halved"></i> {{ __('messages.blade_incident_success_blade_submitted_for_review') }}</span>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_incident_success_blade_what_happens_next') }}</h2>
                <div class="timeline">
                    <div class="timeline-item done">
                        <span class="timeline-dot"><i class="fas fa-check"></i></span>
                        <div><strong>{{ __('messages.blade_incident_success_blade_recorded') }}</strong><span>{{ __('messages.blade_incident_success_blade_your_report_was_submitted_successfully') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">2</span>
                        <div><strong>{{ __('messages.blade_clearance_form_blade_review') }}</strong><span>{{ __('messages.blade_incident_success_blade_barangay_staff_reviews_your_report_details') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">3</span>
                        <div><strong>{{ __('messages.blade_incident_success_blade_follow_up') }}</strong><span>{{ __('messages.blade_incident_success_blade_you_may_be_contacted_for_clarification_mediation_or_hea') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_services_blade_need_help') }}</h2>
                <p class="help-text"><strong>{{ __('messages.blade_index_blade_barangay_hall') }}</strong><br>{{ __('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city') }}<br><br><strong>{{ __('messages.contacts_hours_title') }}</strong><br>{{ __('messages.blade_incident_success_blade_monday_to_friday_8_00_am_5_00_pm') }}<br>{{ __('messages.blade_incident_success_blade_saturday_8_00_am_12_00_pm') }}<br><br><strong>{{ __('messages.nav_contact') }}</strong><br>8-281-1373</p>
            </div>
        </aside>
    </div>
</main>
<div class="toast-copy" id="copyToast">{{ __('messages.blade_incident_success_blade_reference_number_copied') }}</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
@include('shared.js-config', [
    'id' => 'js-config-barangay-system-success-form-incident-success-1',
    'data' => [
        'v1' => $complaint_name ?? __('messages.blade_success_fallback_complainant'),
        'v2' => $reference ?? '',
        'v3' => $submittedDateText,
        'v4' => __('messages.blade_jsdyn_reference_reference_ncomplainant_name_ndate_date_ntype_inc'),
        'v5' => e(__('messages.blade_incident_success_blade_incident_report_summary')),
        'v6' => e(__('messages.blade_print_track_request_blade_barangay_hulong_duhat_malabon_city')),
        'v7' => e(__('messages.blade_incident_success_blade_submitted_for_review')),
        'v8' => e(__('messages.incident_form_bring_reference')),
        'v9' => e(__('messages.blade_incident_success_blade_complainant_name')),
        'v10' => e(__('messages.profile_table_date')),
        'v11' => e(__('messages.profile_table_status')),
        'v12' => e(__('messages.blade_incident_success_blade_scan_to_verify_report_status')),
        'v13' => e(__('messages.blade_print_template_qr_code_for')),
        'v14' => e(__('messages.blade_print_template_reference')),
        'v15' => e(__('messages.print_next_steps_title')),
        'v16' => e(__('messages.blade_incident_success_blade_barangay_staff_will_review_your_report')),
        'v17' => e(__('messages.blade_incident_success_blade_you_may_be_contacted_for_clarification_or_additional_in')),
        'v18' => e(__('messages.blade_incident_success_blade_check_your_email_phone_or_tracking_page_for_updates')),
        'v19' => e(__('messages.blade_incident_success_blade_visit_the_barangay_hall_if_summoned_for_mediation')),
        'v20' => e(__('messages.blade_incident_success_blade_important')),
        'v21' => e(__('messages.blade_incident_success_blade_keep_this_summary_for_reference_present_the_reference_n')),
        'v22' => e(__('messages.blade_print_track_request_blade_barangay_hall')),
        'v23' => e(__('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city')),
        'v24' => e(__('messages.blade_incident_success_blade_office_hours')),
        'v25' => e(__('messages.form_hours_weekdays')),
        'v26' => e(__('messages.form_hours_saturday')),
        'v27' => e(__('messages.blade_incident_success_blade_this_is_an_electronically_generated_report_summary_no_s')),
        'v28' => __('messages.blade_print_template_barangay_hulong_duhat_online_services_year'),
        'v29' => e(__('messages.blade_incident_success_blade_print_summary')),
        'v30' => e(__('messages.blade_incident_success_blade_close_window')),
        'v31' => __('messages.blade_incident_success_blade_please_allow_pop_ups_to_print_the_report_summary'),
        'v32' => __('messages.blade_js_error_generating_qr_code'),
        'v33' => __('messages.blade_incident_success_blade_error_generating_qr_code_please_try_again'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/success_form/incident_success-dynamic1.js') }}"></script>
</body>
</html>
