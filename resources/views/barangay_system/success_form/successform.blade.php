<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.blade_success_service_application_submitted', ['service' => $service]) }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/success_form/successform.css') }}">
    @include('shared.public-theme')
</head>
<body>
@php
    $submittedDateText = $date_submitted ?? now()->format('M d, Y');
    $feeLabelText = session('fee_label', '');
    $backRoute = is_string($route ?? null) && \Illuminate\Support\Facades\Route::has($route)
        ? $route
        : 'services';
@endphp

<main class="main-layout" id="mainContent">
    <div class="page-shell success-grid">
        <section class="success-card" aria-labelledby="successTitle">
            <div class="status-row">
                <div class="status-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
                <div>
                    <span class="status-kicker">{{ __('messages.blade_successform_blade_submitted_successfully') }}</span>
                    <p>{{ __('messages.blade_successform_blade_no_need_to_submit_another_application') }}</p>
                </div>
            </div>

            <h1 class="title" id="successTitle">{{ __('messages.blade_success_your_service_application_was_received', ['service' => $service]) }}</h1>
            <p class="lead">{{ __('messages.blade_successform_blade_save_your_reference_number_then_track_your_request_anyt') }}</p>

            <div class="reference-panel" aria-label="{{ __('messages.incident_form_bring_reference') }}">
                <div>
                    <span class="ref-label">{{ __('messages.incident_form_bring_reference') }}</span>
                    <strong class="reference-number" id="referenceNumber">{{ $reference_number }}</strong>
                </div>
                <button type="button" class="copy-ref" id="copyReference" data-copy-target="referenceNumber">
                    <i class="fas fa-copy"></i> {{ __('messages.blade_incident_success_blade_copy') }}
                </button>
            </div>

            <div class="details-list" aria-label="{{ __('messages.blade_successform_blade_application_summary') }}">
                <div class="mini-detail">
                    <span>{{ __('messages.blade_successform_blade_applicant') }}</span>
                    <strong>{{ $applicant_name ?? __('messages.blade_successform_blade_applicant') }}</strong>
                </div>
                <div class="mini-detail">
                    <span>{{ __('messages.profile_table_date') }}</span>
                    <strong>{{ $submittedDateText }}</strong>
                </div>
                <div class="mini-detail">
                    <span>{{ __('messages.blade_successform_blade_fee') }}</span>
                    <strong>
                        @php
                            $feeLabel = session('fee_label', null);
                            $numericAmount = is_numeric($amount ?? null) ? (float) $amount : null;
                        @endphp
                        {{ $feeLabel ?: (($numericAmount && $numericAmount > 0) ? 'P' . number_format($numericAmount, 2) : __('messages.residency_form_fee_amount')) }}
                    </strong>
                </div>
            </div>

            <div class="actions" aria-label="{{ __('messages.blade_incident_success_blade_next_actions') }}">
                <a href="{{ route('track_request') }}" class="action-btn action-primary">
                    <i class="fas fa-search"></i> {{ __('messages.success_track_btn') }}
                </a>
                <button type="button" class="action-btn action-secondary" id="printReceipt">
                    <i class="fas fa-print"></i> {{ __('messages.blade_successform_blade_print_receipt') }}
                </button>
                <a href="{{ route($backRoute) }}" class="action-btn action-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.blade_success_back_to_service_page', ['service' => $service]) }}
                </a>
            </div>
        </section>

        <aside class="side-card" aria-label="{{ __('messages.blade_incident_success_blade_helpful_information') }}">
            <div class="side-section">
                <h2>{{ __('messages.track_timeline_current_status') }}</h2>
                <span class="status-pill"><i class="fas fa-clock"></i> {{ __('messages.blade_successform_blade_waiting_for_review') }}</span>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_incident_success_blade_what_happens_next') }}</h2>
                <div class="timeline">
                    <div class="timeline-item done">
                        <span class="timeline-dot"><i class="fas fa-check"></i></span>
                        <div><strong>{{ __('messages.blade_index_blade_submitted') }}</strong><span>{{ __('messages.blade_successform_blade_your_application_was_saved_successfully') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">2</span>
                        <div><strong>{{ __('messages.blade_clearance_form_blade_review') }}</strong><span>{{ __('messages.blade_successform_blade_staff_checks_your_details_and_uploaded_requirements') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">3</span>
                        <div><strong>{{ __('messages.blade_successform_blade_release') }}</strong><span>{{ __('messages.blade_successform_blade_bring_this_receipt_and_your_valid_id_when_claiming') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_services_blade_need_help') }}</h2>
                <p class="help-text"><strong>{{ __('messages.blade_index_blade_barangay_hall') }}</strong><br>{{ __('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city') }}<br><br><strong>{{ __('messages.contacts_hours_title') }}</strong><br>{{ __('messages.blade_incident_success_blade_monday_to_friday_8_00_am_5_00_pm') }}<br><br><strong>{{ __('messages.nav_contact') }}</strong><br>8-281-1373</p>
            </div>
        </aside>
    </div>
</main>

<div class="toast-copy" id="copyToast">{{ __('messages.blade_incident_success_blade_reference_number_copied') }}</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
@include('shared.js-config', [
    'id' => 'js-config-barangay-system-success-form-successform-1',
    'data' => [
        'v1' => $service ?? __('messages.blade_mission_vision_blade_service'),
        'v2' => $applicant_name ?? __('messages.blade_successform_blade_applicant'),
        'v3' => $reference_number ?? '',
        'v4' => $submittedDateText,
        'v5' => $amount ?? null,
        'v6' => $feeLabelText,
        'v7' => __('messages.residency_form_fee_amount'),
        'v8' => __('messages.blade_js_no_payment_is_required_for_this_service'),
        'v9' => __('messages.blade_jsdyn_pay_amount_at_barangay_cashier_before_claiming'),
        'v10' => __('messages.blade_success_application_receipt'),
        'v11' => __('messages.blade_jsdyn_reference_reference_nname_name_nservice_service_ndate_date'),
        'v12' => e(__('messages.blade_print_track_request_blade_barangay_hulong_duhat_malabon_city')),
        'v13' => e(__('messages.blade_index_blade_submitted')),
        'v14' => e(__('messages.incident_form_bring_reference')),
        'v15' => e(__('messages.track_result_name_label')),
        'v16' => e(__('messages.blade_mission_vision_blade_service')),
        'v17' => e(__('messages.profile_table_date')),
        'v18' => e(__('messages.profile_table_status')),
        'v19' => e(__('messages.blade_successform_blade_submitted_for_processing')),
        'v20' => e(__('messages.blade_successform_blade_amount_to_pay')),
        'v21' => e(__('messages.blade_successform_blade_scan_to_verify_application_status')),
        'v22' => e(__('messages.blade_print_template_qr_code_for')),
        'v23' => e(__('messages.blade_print_template_reference')),
        'v24' => e(__('messages.print_next_steps_title')),
        'v25' => e(__('messages.blade_successform_blade_wait_for_barangay_review_and_confirmation')),
        'v26' => e(__('messages.blade_successform_blade_check_your_email_sms_or_tracking_page_for_updates')),
        'v27' => e(__('messages.blade_successform_blade_bring_this_receipt_and_the_same_valid_id_used_in_your_a')),
        'v28' => e(__('messages.blade_incident_success_blade_important')),
        'v29' => __('messages.blade_success_template_keep_this_receipt_until_your_service_is_released'),
        'v30' => e(__('messages.blade_print_track_request_blade_barangay_hall')),
        'v31' => e(__('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city')),
        'v32' => e(__('messages.blade_incident_success_blade_office_hours')),
        'v33' => e(__('messages.form_hours_weekdays')),
        'v34' => e(__('messages.blade_print_track_request_blade_contact')),
        'v35' => e(__('messages.blade_successform_blade_this_is_an_electronically_generated_receipt_no_signatur')),
        'v36' => __('messages.blade_print_template_barangay_hulong_duhat_online_services_year'),
        'v37' => e(__('messages.blade_successform_blade_print_receipt')),
        'v38' => e(__('messages.blade_incident_success_blade_close_window')),
        'v39' => __('messages.blade_successform_blade_please_allow_pop_ups_to_print_the_receipt'),
        'v40' => __('messages.blade_js_error_generating_qr_code'),
        'v41' => __('messages.blade_incident_success_blade_error_generating_qr_code_please_try_again'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/success_form/successform-dynamic1.js') }}"></script>
</body>
</html>
