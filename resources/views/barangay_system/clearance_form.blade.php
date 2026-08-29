@php
    $resident = $resident ?? auth()->user();

    $fullName = trim(collect([
        $resident?->firstname,
        $resident?->middlename,
        $resident?->lastname,
        $resident?->suffix,
    ])->filter()->implode(' '));

    $birthdateDisplay = $resident?->birthdate
        ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y')
        : __('messages.blade_announcement_blade_not_provided');

    $genderDisplay = filled($resident?->gender)
        ? ucfirst((string) $resident->gender)
        : __('messages.blade_announcement_blade_not_provided');

    $hasVerifiedValidId = in_array($resident?->valid_id_verified, [true, 1, '1', 'true', 'yes', 'verified'], true);
    $hasVerifiedResidency = in_array($resident?->residency_verified, [true, 1, '1', 'true', 'yes', 'verified'], true);

    $residentValidIdPath = $resident?->valid_id_path
        ?? $resident?->valid_id
        ?? $resident?->valid_id_front
        ?? null;
    $residentValidIdBackPath = $resident?->valid_id_back_path
        ?? $resident?->valid_id_back
        ?? null;

    $needsPrimaryProof = ! $hasVerifiedResidency;
    $needsValidIdUpload = ! $hasVerifiedValidId || blank($residentValidIdPath) || blank($residentValidIdBackPath);

    $missingProfile = collect([
        'Full name' => blank($resident?->firstname) || blank($resident?->lastname),
        'Birthdate' => blank($resident?->birthdate),
        'Gender' => blank($resident?->gender),
        'Civil Status' => blank($resident?->civil_status),
        'Place of Birth' => blank($resident?->place_of_birth),
        'Complete address' => blank($resident?->address),
        'Contact number' => blank($resident?->contact),
        'Email address' => blank($resident?->email),
    ])->filter()->keys();

    $profileReviewData = [
        'fullName' => $fullName ?: __('messages.blade_announcement_blade_not_provided'),
        'birthdate' => $birthdateDisplay,
        'gender' => $genderDisplay,
        'civilStatus' => $resident?->civil_status ? ucfirst((string) $resident->civil_status) : __('messages.blade_announcement_blade_not_provided'),
        'birthPlace' => $resident?->place_of_birth ?: __('messages.blade_announcement_blade_not_provided'),
        'address' => $resident?->address ?: __('messages.blade_announcement_blade_not_provided'),
        'contact' => $resident?->contact ?: __('messages.blade_announcement_blade_not_provided'),
        'email' => $resident?->email ?: __('messages.blade_announcement_blade_not_provided'),
    ];

    $savedValidIdLabel = $residentValidIdPath ? basename((string) $residentValidIdPath) : __('messages.blade_fallback_saved_verified_valid_id');
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('messages.blade_clearance_form_blade_barangay_clearance_application_form') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/clearance_form.css') }}">
</head>

@include('chatbot.embed')

<body class="cf-adopted-design cf-steps-3">
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('clearance') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.clearance_form_back_btn') }}
        </a>
    </div>

    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-file-signature"></i> {{ __('messages.clearance_form_page_title') }}</h1>
        <p class="mb-0">
            {{ __('messages.clearance_form_page_subtitle') }}
        </p>
    </div>

    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">

            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('clearance.store') }}" enctype="multipart/form-data" id="requirementForm" class="requirement-form" novalidate>
                @csrf

                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.form_step_personal_info') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.blade_clearance_form_blade_request_details') }}</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>{{ __('messages.form_step_review_submit') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user-lock"></i> {{ __('messages.blade_clearance_form_blade_confirm_resident_profile') }}</h3>

                    <div class="locked-profile-card">
                        <div class="locked-profile-head">
                            <div>
                                <h4>{{ __('messages.blade_clearance_form_blade_saved_resident_information') }}</h4>
                                <p>
                                    {{ __('messages.blade_clearance_form_blade_these_details_came_from_your_registered_account_they_ar') }}
                                </p>
                            </div>
                            <span class="locked-pill">
                                <i class="fas fa-lock"></i> {{ __('messages.blade_clearance_form_blade_locked') }}
                            </span>
                        </div>

                        @if ($missingProfile->isNotEmpty())
                            <div class="locked-warning-card">
                                <i class="fas fa-triangle-exclamation"></i>
                                <div>
                                    <strong>{{ __('messages.blade_clearance_form_blade_your_profile_has_missing_information') }}</strong>
                                    <span>{{ __('messages.blade_clearance_form_blade_please_email_the_barangay_office_before_submitting_this') }}</span>
                                    <ul>
                                        @foreach ($missingProfile as $field)
                                            <li>{{ $field }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="locked-profile-grid">
                            <div class="locked-profile-item">
                                <span>{{ __('messages.form_review_label_full_name') }}</span>
                                <strong>{{ $profileReviewData['fullName'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_dob_label') }}</span>
                                <strong>{{ $profileReviewData['birthdate'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.form_review_label_gender') }}</span>
                                <strong>{{ $profileReviewData['gender'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>Civil Status</span>
                                <strong>{{ $profileReviewData['civilStatus'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>Place of Birth</span>
                                <strong>{{ $profileReviewData['birthPlace'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_contact_label') }}</span>
                                <strong>{{ $profileReviewData['contact'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_email_label') }}</span>
                                <strong>{{ $profileReviewData['email'] }}</strong>
                            </div>

                            <div class="locked-profile-item full">
                                <span>{{ __('messages.register_address_label') }}</span>
                                <strong>{{ $profileReviewData['address'] }}</strong>
                            </div>
                        </div>

                        <label class="terms-checkbox profile-confirm-checkbox">
                            <input type="checkbox" id="profileConfirmed" name="profile_confirmed" required {{ old('profile_confirmed') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_confirm_that_my_saved_resident_profile_information_is') }}
                        </label>
                    </div>

                    <div class="locked-correction-card">
                        <div>
                            <strong>{{ __('messages.blade_clearance_form_blade_is_something_incorrect') }}</strong>
                            <p>
                                {{ __('messages.blade_clearance_form_blade_do_not_edit_your_name_or_identity_details_here_send_a_c') }}
                            </p>
                        </div>
                        <button type="button" class="btn-report-info" data-bs-toggle="modal" data-bs-target="#incorrectProfileModal">
                            <i class="fas fa-envelope"></i> {{ __('messages.blade_clearance_form_blade_email_correction_concern') }}
                        </button>
                    </div>

                    <div class="form-actions row g-2 btns">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('clearance') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" data-confirm="{{ __('messages.blade_clearance_form_blade_are_you_sure_you_want_to_cancel_any_unsaved_changes_wil') }}">
                                <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                            </a>
                        </div>

                        <div class="col-12 col-md-auto ms-md-auto btns">
                            @if ($missingProfile->isNotEmpty())
                                <button type="button" class="btn-next" disabled style="opacity:.65; cursor:not-allowed;">
                                    {{ __('messages.blade_clearance_form_blade_profile_incomplete') }}
                                </button>
                            @else
                                <button type="button" class="btn-next" data-next="step2">
                                    {{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <h3><i class="fas fa-file-upload"></i> {{ __('messages.blade_clearance_form_blade_request_details') }}</h3>

                    <div class="upload-section row g-4">
                        @if ($needsPrimaryProof)
                            <div class="upload-group col-12">
                                <label for="primaryProof">
                                    <i class="fas fa-house-user"></i> {{ __('messages.blade_clearance_form_blade_proof_of_residency') }}
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="primaryProofUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">{{ __('messages.blade_clearance_form_blade_drag_drop_your_proof_of_residency_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <p class="small text-muted mt-2">
                                        {{ __('messages.blade_clearance_form_blade_barangay_id_voter_s_id_recent_utility_bill_or_cedula') }}
                                    </p>
                                    <input type="file" id="primaryProof" name="primary_proof" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="primaryProofPreview"></div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="verified-requirement-card">
                                    <i class="fas fa-circle-check"></i>
                                    <div>
                                        <strong>{{ __('messages.blade_clearance_form_blade_proof_of_residency_already_verified') }}</strong>
                                        <p>{{ __('messages.blade_clearance_form_blade_you_do_not_need_to_upload_proof_of_residency_again_beca') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($needsValidIdUpload)
                            <div class="upload-group col-12">
                                <label for="validId">
                                    <i class="fas fa-id-card"></i> Valid ID - Front <span class="text-danger">*</span>
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="validIdUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">Upload the <strong>front side</strong> of your valid ID or <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <input type="file" id="validId" name="valid_id_path" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="validIdPreview"></div>
                            </div>

                            <div class="upload-group col-12">
                                <label for="validIdBack">
                                    <i class="fas fa-id-card"></i> Valid ID - Back <span class="text-danger">*</span>
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="validIdBackUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">Upload the <strong>back side</strong> of your valid ID or <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <input type="file" id="validIdBack" name="valid_id_back_path" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="validIdBackPreview"></div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="verified-requirement-card">
                                    <i class="fas fa-id-card-clip"></i>
                                    <div>
                                        <strong>{{ __('messages.blade_clearance_form_blade_valid_id_already_verified') }}</strong>
                                        <p>Your verified Valid ID front and back are already saved in your resident profile, so you do not need to upload them again.</p>
                                        <span class="verified-file-name">
                                            <i class="fas fa-lock"></i>
                                            {{ $savedValidIdLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="purpose">
                            <i class="fas fa-bullseye"></i> {{ __('messages.clearance_form_purpose_label') }}
                        </label>
                        <select id="purpose" name="purpose" required class="form-control">
                            <option value="">{{ __('messages.blade_clearance_form_blade_select_purpose') }}</option>
                            <option value="employment" {{ old('purpose') === 'employment' ? 'selected' : '' }}>{{ __('messages.blade_clearance_form_blade_employment') }}</option>
                            <option value="business" {{ old('purpose') === 'business' ? 'selected' : '' }}>{{ __('messages.clearance_purpose_business') }}</option>
                            <option value="scholarship" {{ old('purpose') === 'scholarship' ? 'selected' : '' }}>{{ __('messages.blade_certificate_indigency_blade_scholarship') }}</option>
                            <option value="travel" {{ old('purpose') === 'travel' ? 'selected' : '' }}>{{ __('messages.blade_clearance_form_blade_travel_abroad') }}</option>
                            <option value="bank" {{ old('purpose') === 'bank' ? 'selected' : '' }}>{{ __('messages.clearance_purpose_bank') }}</option>
                            <option value="government" {{ old('purpose') === 'government' ? 'selected' : '' }}>{{ __('messages.blade_clearance_form_blade_government_transaction') }}</option>
                            <option value="school" {{ old('purpose') === 'school' ? 'selected' : '' }}>{{ __('messages.blade_clearance_form_blade_school_requirement') }}</option>
                            <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>{{ __('messages.clearance_purpose_other') }}</option>
                        </select>

                        <textarea id="purposeOther" name="purpose_other"
                            placeholder="{{ __('messages.form_purpose_other_placeholder') }}"
                            rows="2"
                            style="display: none; margin-top: 10px;"
                            class="form-control">{{ old('purpose_other') }}</textarea>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step1">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}
                            </button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step3">
                                {{ __('messages.blade_clearance_form_blade_review') }} <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <h3><i class="fas fa-check-circle"></i> {{ __('messages.form_step_review_submit') }}</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-user-check"></i> {{ __('messages.blade_clearance_form_blade_resident_profile') }}</h4>
                            <div class="review-content" id="reviewPersonal"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-check"></i> {{ __('messages.blade_clearance_form_blade_request_details_documents') }}</h4>
                            <div class="review-content" id="reviewDocuments"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> {{ __('messages.blade_clearance_form_blade_payment_pickup') }}</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>{{ __('messages.blade_clearance_form_blade_barangay_clearance_fee') }}</span>
                                        <span>{{ __('messages.blade_clearance_form_blade_depending_on_purpose') }}</span>
                                    </div>
                                    <div class="payment-item">
                                        <span>{{ __('messages.form_processing_fee_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item total">
                                        <span>{{ __('messages.form_total_label') }}</span>
                                        <span>{{ __('messages.blade_clearance_form_blade_depending_on_purpose') }}</span>
                                    </div>
                                </div>

                                <div class="pickup-instructions">
                                    <div class="pickup-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5><i class="fas fa-building"></i> {{ __('messages.blade_clearance_form_blade_payment_pickup_location') }}</h5>
                                            <p>
                                                {{ __('messages.blade_clearance_form_blade_barangay_hulong_duhat_hall') }}<br>
                                                {{ __('messages.footer_address') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <h5><i class="fas fa-calendar-alt"></i> {{ __('messages.contacts_hours_title') }}</h5>
                                            <p>{{ __('messages.form_hours_weekdays') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-user-check"></i>
                                        <div>
                                            <h5><i class="fas fa-id-card"></i> {{ __('messages.form_what_to_bring_label') }}</h5>
                                            <ul>
                                                <li>{{ __('messages.blade_clearance_form_blade_valid_id_for_verification') }}</li>
                                                <li>{{ __('messages.blade_clearance_form_blade_original_proof_of_residency_if_required') }}</li>
                                                <li>{{ __('messages.blade_clearance_form_blade_printed_or_saved_reference_number') }}</li>
                                                <li>{{ __('messages.blade_clearance_form_blade_fee_depends_on_selected_purpose') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> {{ __('messages.blade_clearance_form_blade_benefits_of_online_request') }}</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>{{ __('messages.blade_clearance_form_blade_no_long_lines') }}</h5>
                                <p class="mb-0">{{ __('messages.blade_clearance_form_blade_your_request_is_submitted_before_going_to_the_barangay_') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>{{ __('messages.blade_clearance_form_blade_profile_based') }}</h5>
                                <p class="mb-0">{{ __('messages.blade_clearance_form_blade_your_verified_resident_profile_is_used_to_avoid_repeate') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-shield-alt mb-3"></i>
                                <h5>{{ __('messages.blade_clearance_form_blade_safer_request') }}</h5>
                                <p class="mb-0">{{ __('messages.blade_clearance_form_blade_identity_details_are_locked_to_prevent_misuse') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_hereby_certify_that_all_information_provided_is_true_') }}
                        </label>

                        <label class="terms-checkbox">
                            <input type="checkbox" id="privacy" name="privacy" required {{ old('privacy') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <p style="margin-bottom: 0;">
                                {{ __('messages.blade_clearance_form_blade_i_agree_to_the') }}
                                <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" data-stop-propagation>{{ __('messages.register_privacy_title') }}</a>
                                {{ __('messages.blade_clearance_form_blade_and') }}
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditionsModal" data-stop-propagation>{{ __('messages.terms_modal_title') }}</a>
                                {{ __('messages.blade_clearance_form_blade_of_barangay_hulong_duhat_online_services') }}
                            </p>
                        </label>

                        <label class="terms-checkbox">
                            <input type="checkbox" id="pickup" name="pickup" required {{ old('pickup') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_understand_that_the_barangay_clearance_fee_depends_on') }}
                        </label>
                    </div>

                    <div id="termsError" style="display:none;color:#ff4444;font-size:0.85rem;margin-top:8px;padding:8px 12px;background:rgba(255,68,68,0.05);border-radius:6px;border:1px solid rgba(255,68,68,0.3);">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.blade_clearance_form_blade_please_accept_all_confirmations_before_submitting') }}
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}
                            </button>
                        </div>

                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit">
                                <span class="btn-text">{{ __('messages.form_submit_btn') }}</span>
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

    <div class="modal fade" id="incorrectProfileModal" tabindex="-1" aria-labelledby="incorrectProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-report-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="incorrectProfileModalLabel">
                        <i class="fas fa-envelope me-2"></i> {{ __('messages.blade_clearance_form_blade_email_profile_correction_concern') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>

                <form method="POST" action="{{ route('clearance.reportIncorrectProfile') }}">
                    @csrf

                    <div class="modal-body">
                        <div class="alert alert-info">
                            {{ __('messages.blade_clearance_form_blade_your_identity_details_cannot_be_edited_directly_the_bar') }}
                        </div>

                        <div class="form-group">
                            <label for="fieldName">
                                <i class="fas fa-list"></i> {{ __('messages.blade_clearance_form_blade_which_information_is_incorrect') }}
                            </label>
                            <select id="fieldName" name="field_name" class="form-control" required>
                                <option value="">{{ __('messages.blade_clearance_form_blade_select_information') }}</option>
                                <option value="Full name" {{ old('field_name') === 'Full name' ? 'selected' : '' }}>{{ __('messages.form_review_label_full_name') }}</option>
                                <option value="Birthdate" {{ old('field_name') === 'Birthdate' ? 'selected' : '' }}>{{ __('messages.blade_clearance_form_blade_birthdate') }}</option>
                                <option value="Gender" {{ old('field_name') === 'Gender' ? 'selected' : '' }}>{{ __('messages.form_review_label_gender') }}</option>
                                <option value="Complete address" {{ old('field_name') === 'Complete address' ? 'selected' : '' }}>{{ __('messages.register_address_label') }}</option>
                                <option value="Contact number" {{ old('field_name') === 'Contact number' ? 'selected' : '' }}>{{ __('messages.register_contact_label') }}</option>
                                <option value="Email address" {{ old('field_name') === 'Email address' ? 'selected' : '' }}>{{ __('messages.register_email_label') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="correctValue">
                                <i class="fas fa-pen"></i> {{ __('messages.blade_clearance_form_blade_correct_information') }}
                            </label>
                            <input type="text" id="correctValue" name="correct_value" class="form-control" required value="{{ old('correct_value') }}" placeholder="{{ __('messages.blade_clearance_form_blade_enter_the_correct_information') }}">
                        </div>

                        <div class="form-group">
                            <label for="correctionMessage">
                                <i class="fas fa-message"></i> {{ __('messages.blade_clearance_form_blade_message') }}
                            </label>
                            <textarea id="correctionMessage" name="message" class="form-control" rows="3" placeholder="{{ __('messages.blade_clearance_form_blade_explain_what_needs_to_be_corrected') }}">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-prev" data-bs-dismiss="modal">{{ __('messages.register_cancel_btn') }}</button>
                        <button type="submit" class="btn-next">
                            <i class="fas fa-paper-plane"></i> {{ __('messages.blade_clearance_form_blade_send_email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('barangay_system.partials.policy_modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-clearance-form-1',
    'data' => [
        'v1' => $profileReviewData,
        'v2' => $needsPrimaryProof,
        'v3' => $needsValidIdUpload,
        'v4' => $savedValidIdLabel,
        'v5' => __('messages.blade_js_this_file_is_required'),
        'v6' => __('messages.blade_js_please_upload_jpg_png_or_pdf_files_only'),
        'v7' => __('messages.blade_clearance_form_blade_file_size_must_be_less_than_5mb'),
        'v8' => __('messages.blade_js_please_confirm_this_before_continuing'),
        'v9' => __('messages.blade_clearance_form_blade_proof_of_residency'),
        'v10' => __('messages.form_valid_id_label'),
        'v11' => __('messages.blade_js_this_field_is_required'),
        'v12' => __('messages.validation_file_invalid_type'),
        'v13' => __('messages.blade_runtime_remove_file'),
        'v14' => e(__('messages.form_review_label_full_name')),
        'v15' => e(__('messages.form_review_label_dob')),
        'v16' => e(__('messages.form_review_label_gender')),
        'v17' => e(__('messages.form_review_label_address')),
        'v18' => e(__('messages.form_review_label_contact')),
        'v19' => e(__('messages.form_review_label_email')),
        'v20' => __('messages.blade_js_not_specified'),
        'v21' => __('messages.blade_js_no_proof_uploaded'),
        'v22' => __('messages.blade_js_already_verified_in_resident_profile'),
        'v23' => __('messages.blade_js_no_valid_id_uploaded'),
        'v24' => e(__('messages.blade_review_label_purpose_of_clearance')),
        'v25' => e(__('messages.blade_certificate_residency_blade_proof_of_residency')),
        'v26' => e(__('messages.blade_certificate_indigency_blade_valid_id')),
    ],
])
<script src="{{ asset('js/pages/barangay_system/clearance_form-dynamic1.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>

</html>
