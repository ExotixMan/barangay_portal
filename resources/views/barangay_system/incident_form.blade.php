@php
    $resident = $resident ?? auth()->user();
    $reporterFullName = trim(collect([
        $resident?->firstname,
        $resident?->middlename,
        $resident?->lastname,
        $resident?->suffix,
    ])->filter()->implode(' '));
    $reporterContact = $resident?->contact ?? '';
    $reporterAddress = $resident?->address ?? '';
    $reporterEmail = $resident?->email ?? '';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('messages.blade_incident_form_blade_barangay_incident_report_application_form') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

        <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/incident_form.css') }}">
</head>
@include('chatbot.embed')
<body>

    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('incident') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.incident_form_back_btn') }}
        </a>
    </div>

    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-clipboard-list"></i> {{ __('messages.incident_form_page_title') }}</h1>
        <p class="mb-0">{{ __('messages.blade_incident_form_blade_file_an_official_incident_report_with_a_clean_step_by_s') }}</p>
    </div>

    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            @if (session('success'))
                <div class="alert alert-success m-3">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <i class="fas fa-triangle-exclamation"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="blotterForm" method="POST" action="{{ route('incident.store') }}" enctype="multipart/form-data" class="requirement-form" novalidate>
                @csrf

                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.incident_form_step1_label') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.incident_form_step2_label') }}</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>{{ __('messages.incident_form_step3_label') }}</span>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-circle">4</div>
                            <span>{{ __('messages.form_step_review_submit') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-info-circle"></i> {{ __('messages.incident_form_step1_title') }}</h3>

                    <div class="form-group full-width">
                        <label><i class="fas fa-tag"></i> {{ __('messages.incident_form_type_label') }}</label>
                        <div class="report-type-grid">
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="dispute" required {{ old('reportType') === 'dispute' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-users"></i>
                                    <h4>{{ __('messages.incident_type_dispute_title') }}</h4>
                                    <p>{{ __('messages.incident_type_dispute_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="security" {{ old('reportType') === 'security' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-shield-alt"></i>
                                    <h4>{{ __('messages.incident_type_security_title') }}</h4>
                                    <p>{{ __('messages.incident_type_security_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="public" {{ old('reportType') === 'public' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-road"></i>
                                    <h4>{{ __('messages.incident_type_public_title') }}</h4>
                                    <p>{{ __('messages.incident_type_public_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="other" {{ old('reportType') === 'other' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-question-circle"></i>
                                    <h4>{{ __('messages.incident_type_other_title') }}</h4>
                                    <p>{{ __('messages.incident_type_other_desc') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="incidentDate"><i class="fas fa-calendar-day"></i> {{ __('messages.incident_form_date_label') }}</label>
                            <input type="date" id="incidentDate" name="incidentDate" required value="{{ old('incidentDate') }}" class="form-control">
                            <div class="form-hint">{{ __('messages.incident_form_date_hint') }}</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="incidentTime"><i class="fas fa-clock"></i> {{ __('messages.incident_form_time_label') }}</label>
                            <input type="time" id="incidentTime" name="incidentTime" required value="{{ old('incidentTime') }}" class="form-control">
                            <div class="form-hint">{{ __('messages.incident_form_time_hint') }}</div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentLocation"><i class="fas fa-map-marker-alt"></i> {{ __('messages.incident_form_location_label') }}</label>
                        <input type="text" id="incidentLocation" name="incidentLocation" required
                               placeholder="{{ __('messages.blade_incident_form_blade_specific_location_within_barangay_hulong_duhat') }}" minlength="8" maxlength="255" value="{{ old('incidentLocation') }}" class="form-control">
                        <div class="form-hint">{{ __('messages.incident_form_location_hint') }}</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentDescription"><i class="fas fa-align-left"></i> {{ __('messages.incident_form_description_label') }}</label>
                        <textarea id="incidentDescription" name="incidentDescription" required rows="4"
                                  placeholder="{{ __('messages.incident_form_description_placeholder') }}" minlength="20" maxlength="5000" class="form-control">{{ old('incidentDescription') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_description_hint') }}</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="immediateAction"><i class="fas fa-first-aid"></i> {{ __('messages.incident_form_actions_label') }}</label>
                        <textarea id="immediateAction" name="immediateAction" rows="3"
                                  placeholder="{{ __('messages.incident_form_actions_placeholder') }}" maxlength="2000" class="form-control">{{ old('immediateAction') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_actions_hint') }}</div>
                    </div>

                    <div class="form-actions row g-2 btns" style="margin-top: 3px;">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('incident') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" data-confirm="{{ __('messages.blade_clearance_form_blade_are_you_sure_you_want_to_cancel_any_unsaved_changes_wil') }}">
                                <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-auto ms-md-auto btns">
                            <button type="button" class="btn-next" data-next="step2">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <h3><i class="fas fa-user-friends"></i> {{ __('messages.incident_form_step2_label') }}</h3>

                    <div class="party-section">
                        <h4><i class="fas fa-user-check"></i> {{ __('messages.incident_form_complainant_title') }}</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="complainantName"><i class="fas fa-user"></i> {{ __('messages.form_full_name_label') }}</label>
                                <input type="text" id="complainantName" name="complainantName" required
                                       placeholder="{{ __('messages.blade_incident_form_blade_enter_your_full_name') }}" minlength="2" maxlength="120" value="{{ old('complainantName', $reporterFullName) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.form_name_hint') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantContact"><i class="fas fa-phone"></i> {{ __('messages.form_contact_label') }}</label>
                                <input type="tel" id="complainantContact" name="complainantContact" required
                                       placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" maxlength="11" value="{{ old('complainantContact', $reporterContact) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.incident_form_contact_hint') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantAddress"><i class="fas fa-home"></i> {{ __('messages.form_address_label') }}</label>
                                <input type="text" id="complainantAddress" name="complainantAddress" required
                                       placeholder="{{ __('messages.blade_incident_form_blade_house_no_street_barangay_hulong_duhat_malabon_city') }}" minlength="10" maxlength="255" value="{{ old('complainantAddress', $reporterAddress) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantEmail"><i class="fas fa-envelope"></i> {{ __('messages.register_email_label') }}</label>
                                <input type="email" id="complainantEmail" name="complainantEmail"
                                       placeholder="{{ __('messages.blade_incident_form_blade_your_email_example_com') }}" maxlength="255" value="{{ old('complainantEmail', $reporterEmail) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.incident_form_email_hint') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="party-section">
                        <h4><i class="fas fa-user-times"></i> {{ __('messages.incident_form_respondent_title') }}</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="respondentName"><i class="fas fa-user"></i> {{ __('messages.incident_form_respondent_name_label') }}</label>
                                <input type="text" id="respondentName" name="respondentName"
                                       placeholder="{{ __('messages.incident_form_respondent_name_placeholder') }}" maxlength="120" value="{{ old('respondentName') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentContact"><i class="fas fa-phone"></i> {{ __('messages.incident_form_respondent_contact_label') }}</label>
                                <input type="tel" id="respondentContact" name="respondentContact" maxlength="11" value="{{ old('respondentContact') }}"
                                       placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentAddress"><i class="fas fa-home"></i> {{ __('messages.incident_form_respondent_address_label') }}</label>
                                <input type="text" id="respondentAddress" name="respondentAddress"
                                       placeholder="{{ __('messages.incident_form_respondent_address_placeholder') }}" maxlength="255" value="{{ old('respondentAddress') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="respondentDescription"><i class="fas fa-id-card"></i> {{ __('messages.incident_form_respondent_desc_label') }}</label>
                            <textarea id="respondentDescription" name="respondentDescription" rows="2"
                                      placeholder="{{ __('messages.incident_form_respondent_desc_placeholder') }}" maxlength="1000" class="form-control">{{ old('respondentDescription') }}</textarea>
                            <div class="form-hint">{{ __('messages.incident_form_respondent_desc_hint') }}</div>
                        </div>
                    </div>

                    <div class="party-section">
                        <h4><i class="fas fa-eye"></i> {{ __('messages.incident_form_witnesses_title') }}</h4>
                        <div class="witness-rule-card">
                            <i class="fas fa-circle-info"></i>
                            <div>
                                <strong>{{ __('messages.blade_incident_form_blade_witnesses_are_optional') }}</strong> {{ __('messages.blade_incident_form_blade_you_may_add_up_to') }} <strong>{{ __('messages.blade_incident_form_blade_5_witnesses') }}</strong>.
                                {{ __('messages.blade_incident_if_you_add_a_witness_provide_at_least_the_witness_name_or__2') }}
                                {{ __('messages.blade_incident_contact_number_is_optional_but_must_be_valid_if_provided') }}
                            </div>
                        </div>
                        <button type="button" class="add-party-btn" id="addWitnessBtn">
                            <i class="fas fa-user-plus"></i> {{ __('messages.incident_form_add_witness_btn') }}
                        </button>
                        <div class="witnesses-list" id="witnessesList"></div>
                        <div class="form-hint" id="witnessCountHint">{{ __('messages.blade_incident_form_blade_0_of_5_witnesses_added') }}</div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step1"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step3">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <h3><i class="fas fa-file-upload"></i> {{ __('messages.incident_form_step3_title') }}</h3>

                    <div class="upload-section row g-4">

                        <div class="upload-group col-12">
                            <label for="photos">
                                <i class="fas fa-image"></i> {{ __('messages.incident_form_photos_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_photos_size') }}</span>
                            </label>
                            <div class="upload-area" id="photoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_photos_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_photos_hint') }}</p>
                                <input type="file" id="photos" name="photos[]" accept=".jpg,.jpeg,.png" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="photoPreview"></div>
                        </div>

                        <div class="upload-group col-12">
                            <label for="videos">
                                <i class="fas fa-file-video"></i> {{ __('messages.incident_form_videos_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_videos_size') }}</span>
                            </label>
                            <div class="upload-area" id="videoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_videos_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_videos_hint') }}</p>
                                <input type="file" id="videos" name="videos[]" accept=".mp4,.avi,.mov" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="videoPreview"></div>
                        </div>

                        <div class="upload-group col-12">
                            <label for="documents">
                                <i class="fas fa-file-alt"></i> {{ __('messages.incident_form_docs_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_docs_size') }}</span>
                            </label>
                            <div class="upload-area" id="docUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_documents_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_docs_hint') }}</p>
                                <input type="file" id="documents" name="documents[]" accept=".pdf,.doc,.docx" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="docPreview"></div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="additionalInfo"><i class="fas fa-sticky-note"></i> {{ __('messages.incident_form_additional_label') }}</label>
                        <textarea id="additionalInfo" name="additionalInfo" rows="3"
                                  placeholder="{{ __('messages.incident_form_additional_placeholder') }}" maxlength="2000" class="form-control">{{ old('additionalInfo') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_additional_hint') }}</div>
                    </div>

                    <div class="form-group full-width">
                        <label><i class="fas fa-user-shield"></i> {{ __('messages.incident_form_confidentiality_label') }}</label>
                        <div class="confidentiality-options">
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="low" required {{ old('confidentiality') === 'low' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-eye"></i>
                                    <div>
                                        <h4>{{ __('messages.blade_incident_form_blade_standard_report') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_barangay_officials_may_use_your_details_for_normal_proc') }}</p>
                                    </div>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="medium" {{ old('confidentiality') === 'medium' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-user-secret"></i>
                                    <div>
                                        <h4>{{ __('messages.incident_confidentiality_confidential_title') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_limit_access_to_authorized_barangay_officials_handling_') }}</p>
                                    </div>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="high" {{ old('confidentiality') === 'high' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-mask"></i>
                                    <div>
                                        <h4>{{ __('messages.blade_incident_form_blade_identity_protected_report') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_hide_your_identity_from_non_authorized_parties_as_much_') }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step4">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step4">
                    <h3><i class="fas fa-check-circle"></i> {{ __('messages.form_step_review_submit') }}</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-info-circle"></i> {{ __('messages.incident_form_step1_title') }}</h4>
                            <div class="review-content" id="reviewIncident"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-user-friends"></i> {{ __('messages.incident_form_step2_label') }}</h4>
                            <div class="review-content" id="reviewParties"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-upload"></i> {{ __('messages.incident_form_review_evidence_card') }}</h4>
                            <div class="review-content" id="reviewEvidence"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> {{ __('messages.incident_form_processing_card') }}</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>{{ __('messages.incident_form_fee_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item">
                                        <span>{{ __('messages.form_processing_fee_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item total">
                                        <span>{{ __('messages.form_total_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                </div>

                                <div class="pickup-instructions">
                                    <div class="pickup-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5><i class="fas fa-building"></i> {{ __('messages.incident_form_process_location_label') }}</h5>
                                            <p>{{ __('messages.blade_clearance_form_blade_barangay_hulong_duhat_hall') }}<br>
                                                {{ __('messages.footer_address') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <h5><i class="fas fa-calendar-alt"></i> {{ __('messages.contacts_hours_title') }}</h5>
                                            <p>{{ __('messages.form_hours_weekdays') }}<br>
                                                {{ __('messages.form_hours_saturday') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-id-card"></i>
                                        <div>
                                            <h5><i class="fas fa-clipboard-check"></i> {{ __('messages.incident_form_what_to_bring_label') }}</h5>
                                            <ul>
                                                <li>{{ __('messages.incident_form_bring_id') }}</li>
                                                <li>{{ __('messages.incident_form_bring_reference') }}</li>
                                                <li>{{ __('messages.incident_form_bring_evidence') }}</li>
                                                <li>{{ __('messages.incident_form_bring_witnesses') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> {{ __('messages.incident_form_benefits_title') }}</h4>
                        <div class="benefits-grid row g-12">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_1_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_1_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_2_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_2_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_3_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_3_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="declareTruth" name="declareTruth" required {{ old('declareTruth') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.incident_form_terms_1') }}
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="agreePrivacy" name="agreePrivacy" required {{ old('agreePrivacy') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span>{{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" data-stop-propagation>{{ __('messages.register_privacy_title') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditionsModal" data-stop-propagation>{{ __('messages.terms_modal_title') }}</a> {{ __('messages.blade_clearance_form_blade_of_barangay_hulong_duhat_online_services') }}</span>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="consentProcessing" name="consentProcessing" required {{ old('consentProcessing') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.incident_form_terms_consent') }}
                        </label>
                    </div>
                    <div id="termsError">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.form_terms_error') }}
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step3"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit" id="submitBlotter">
                                <span class="btn-text">{{ __('messages.incident_form_submit_btn') }}</span>
                                <div class="spinner">
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('barangay_system.partials.policy_modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-incident-form-1',
    'data' => [
        'v1' => __('messages.blade_jsdyn_you_can_add_up_to_max_witnesses_only'),
        'v2' => __('messages.blade_jsdyn_witness_number'),
        'v3' => __('messages.blade_dynamic_remove_witness'),
        'v4' => __('messages.blade_dynamic_witness_name'),
        'v5' => __('messages.blade_dynamic_witness_name_placeholder'),
        'v6' => __('messages.register_contact_label'),
        'v7' => __('messages.blade_dynamic_witness_statement'),
        'v8' => __('messages.blade_dynamic_witness_statement_placeholder'),
        'v9' => __('messages.blade_dynamic_witness_hint'),
        'v10' => __('messages.blade_jsdyn_count_of_max_witnesses_added'),
        'v11' => __('messages.blade_dynamic_maximum_witnesses_added'),
        'v12' => __('messages.incident_form_add_witness_btn'),
        'v13' => __('messages.blade_dynamic_remove_selected_files'),
        'v14' => __('messages.blade_jsdyn_invalid_label_file_type_file'),
        'v15' => __('messages.blade_jsdyn_file_exceeds_the_sizemb_limit'),
        'v16' => __('messages.blade_js_please_select_the_type_of_report'),
        'v17' => __('messages.blade_js_incident_date_is_required'),
        'v18' => __('messages.blade_js_approximate_incident_time_is_required'),
        'v19' => __('messages.blade_js_incident_date_cannot_be_in_the_future'),
        'v20' => __('messages.blade_js_incident_time_cannot_be_later_than_the_current_time'),
        'v21' => __('messages.blade_js_location_must_be_at_least_8_characters'),
        'v22' => __('messages.blade_js_location_is_too_long'),
        'v23' => __('messages.blade_js_description_must_be_at_least_20_characters_so_officials_ca'),
        'v24' => __('messages.blade_js_description_is_too_long'),
        'v25' => __('messages.blade_js_immediate_actions_must_not_exceed_2_000_characters'),
        'v26' => __('messages.blade_js_complainant_name_is_required'),
        'v27' => __('messages.blade_js_please_enter_a_complete_address'),
        'v28' => __('messages.blade_js_address_is_too_long'),
        'v29' => __('messages.blade_js_respondent_address_must_be_at_least_5_characters'),
        'v30' => __('messages.blade_js_respondent_address_is_too_long'),
        'v31' => __('messages.blade_js_respondent_description_must_not_exceed_1_000_characters'),
        'v32' => __('messages.blade_js_if_you_add_respondent_contact_or_address_also_enter_the_re'),
        'v33' => __('messages.blade_js_additional_information_must_not_exceed_2_000_characters'),
        'v34' => __('messages.blade_js_please_select_a_confidentiality_preference'),
        'v35' => __('messages.blade_js_enter_witness_name_or_witness_statement'),
        'v36' => __('messages.blade_js_enter_witness_statement_or_witness_name'),
        'v37' => __('messages.blade_js_witness_name_can_only_contain_letters_spaces_apostrophes_p'),
        'v38' => __('messages.blade_js_witness_statement_must_be_at_least_5_characters'),
        'v39' => __('messages.blade_js_witness_statement_must_not_exceed_1_000_characters'),
        'v40' => __('messages.blade_js_this_field_is_required'),
        'v41' => __('messages.blade_js_name_is_required'),
        'v42' => __('messages.blade_js_name_can_only_contain_letters_spaces_apostrophes_periods_c'),
        'v43' => __('messages.blade_runtime_name_min_2'),
        'v44' => __('messages.blade_runtime_name_max_120'),
        'v45' => __('messages.blade_runtime_contact_required'),
        'v46' => __('messages.blade_runtime_mobile_format'),
        'v47' => __('messages.blade_runtime_email_required'),
        'v48' => __('messages.validation_email_format'),
        'v49' => __('messages.blade_incident_review_not_selected'),
        'v50' => __('messages.blade_incident_form_blade_standard_report'),
        'v51' => __('messages.blade_incident_form_blade_identity_protected_report'),
        'v52' => __('messages.blade_incident_review_report_type'),
        'v53' => __('messages.blade_incident_review_incident_date_time'),
        'v54' => __('messages.blade_incident_review_date_at_time'),
        'v55' => __('messages.blade_js_not_specified'),
        'v56' => __('messages.events_location_label'),
        'v57' => __('messages.blade_incident_review_description'),
        'v58' => __('messages.blade_incident_success_blade_complainant'),
        'v59' => __('messages.blade_incident_review_respondent'),
        'v60' => __('messages.incident_form_witnesses_title'),
        'v61' => __('messages.blade_incident_review_count_witness_es_added'),
        'v62' => __('messages.blade_incident_review_photos'),
        'v63' => __('messages.blade_incident_review_count_file_s_uploaded'),
        'v64' => __('messages.blade_incident_review_videos'),
        'v65' => __('messages.track_filter_documents'),
        'v66' => __('messages.blade_incident_review_confidentiality'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/incident_form-dynamic1.js') }}"></script>

<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>
</html>
