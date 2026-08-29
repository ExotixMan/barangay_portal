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

    $civilStatusDisplay = filled($resident?->civil_status)
        ? ucfirst(str_replace('_', ' ', (string) $resident->civil_status))
        : __('messages.blade_announcement_blade_not_provided');

    $placeOfBirthDisplay = filled($resident?->place_of_birth)
        ? (string) $resident->place_of_birth
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
        'civil_status' => $civilStatusDisplay,
        'place_of_birth' => $placeOfBirthDisplay,
        'address' => $resident?->address ?: __('messages.blade_announcement_blade_not_provided'),
        'contact' => $resident?->contact ?: __('messages.blade_announcement_blade_not_provided'),
        'email' => $resident?->email ?: __('messages.blade_announcement_blade_not_provided'),
    ];

    $savedValidIdLabel = $residentValidIdPath ? basename((string) $residentValidIdPath) : __('messages.blade_fallback_saved_verified_valid_id');
    $savedResidencyProofLabel = 'Residency already verified in your resident profile';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('messages.blade_indigency_form_blade_barangay_indigency_application_form') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/indigency_form.css') }}">
</head>
@include('chatbot.embed')
<body class="cf-adopted-design cf-steps-3">
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('indigency') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.indigency_form_back_btn') }}
        </a>
    </div>

    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-hand-holding-heart"></i> {{ __('messages.indigency_form_page_title') }}</h1>
        <p class="mb-0">{{ __('messages.indigency_form_page_subtitle') }}</p>
    </div>

    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            <form id="requirementForm" method="POST" action="{{ route('indigency.store') }}" enctype="multipart/form-data" class="requirement-form" novalidate>
                @csrf
                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.form_step_personal_info') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.clearance_requirements_title') }}</span>
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
                                    {{ __('messages.blade_indigency_form_blade_these_details_came_from_your_registered_account_they_ar') }}
                                </p>
                            </div>

                            <span class="locked-pill">
                                <i class="fas fa-lock"></i>
                                {{ __('messages.blade_clearance_form_blade_locked') }}
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
                                <span>{{ __('messages.blade_indigency_form_blade_civil_status') }}</span>
                                <strong>{{ $profileReviewData['civil_status'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</span>
                                <strong>{{ $profileReviewData['place_of_birth'] }}</strong>
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
                            <input type="checkbox" id="profileConfirmed" name="profile_confirmed" required>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_confirm_that_my_saved_resident_profile_information_is') }}
                        </label>

                        <div class="cf-hidden-profile-fields" aria-hidden="true">

                        </div>
                    </div>

                    <div class="locked-correction-card">
                        <div>
                            <strong>{{ __('messages.blade_clearance_form_blade_is_something_incorrect') }}</strong>
                            <p>
                                {{ __('messages.blade_clearance_form_blade_do_not_edit_your_name_or_identity_details_here_send_a_c') }}
                            </p>
                        </div>

                        <button type="button" class="btn-report-info" data-bs-toggle="modal" data-bs-target="#incorrectProfileModal">
                            <i class="fas fa-envelope"></i>
                            {{ __('messages.blade_clearance_form_blade_email_correction_concern') }}
                        </button>
                    </div>

                    <div class="form-grid row g-3 mt-3">
                        <div class="form-group col-md-12">
                            <label for="monthlyIncome"><i class="fas fa-coins"></i> {{ __('messages.indigency_form_income_label') }}</label>
                            <select id="monthlyIncome" name="monthly_income" required class="form-control">
                                <option value="">{{ __('messages.indigency_form_income_placeholder') }}</option>
                                <option value="below 5k" {{ old('monthly_income') === 'below 5k' ? 'selected' : '' }}>{{ __('messages.income_below_5k') }}</option>
                                <option value="5k-8k" {{ old('monthly_income') === '5k-8k' ? 'selected' : '' }}>₱5,000 – ₱8,000</option>
                                <option value="8k-10k" {{ old('monthly_income') === '8k-10k' ? 'selected' : '' }}>₱8,001 – ₱10,000</option>
                                <option value="10k-15k" {{ old('monthly_income') === '10k-15k' ? 'selected' : '' }}>₱10,001 – ₱15,000</option>
                                <option value="no income" {{ old('monthly_income') === 'no income' ? 'selected' : '' }}>{{ __('messages.income_none') }}</option>
                            </select>
                            <div class="form-hint">{{ __('messages.indigency_form_income_hint') }}</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="householdMembers"><i class="fas fa-users"></i> {{ __('messages.form_household_members_label') }}</label>
                            <input type="number" id="householdMembers" name="household_members"
                                min="1" max="20" step="1" inputmode="numeric" pattern="[0-9]*"
                                required value="{{ old('household_members') }}" placeholder="{{ __('messages.form_household_placeholder') }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-actions row g-2 btns">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('indigency') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" data-confirm="{{ __('messages.blade_clearance_form_blade_are_you_sure_you_want_to_cancel_any_unsaved_changes_wil') }}">
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
                    <h3><i class="fas fa-file-upload"></i> {{ __('messages.clearance_step2_title') }}</h3>

                    <div class="upload-section row g-4">
                        @if ($needsPrimaryProof)
                            <div class="upload-group col-12">
                                <label for="primaryProof">
                                    <i class="fas fa-id-card"></i> {{ __('messages.residency_form_primary_proof_label') }}
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="primaryProofUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">{{ __('messages.blade_clearance_form_blade_drag_drop_your_proof_of_residency_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <p class="small text-muted mt-2">{{ __('messages.residency_form_proof_types') }}</p>
                                    <input type="file" id="primaryProof" name="primary_proof" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="primaryProofPreview"></div>
                            </div>
                        @else
                            <div class="upload-group col-12">
                                <div class="verified-requirement-card">
                                    <i class="fas fa-house-circle-check"></i>
                                    <div>
                                        <strong>{{ __('messages.blade_indigency_form_blade_residency_proof_already_verified') }}</strong>
                                        <p>{{ __('messages.blade_clearance_form_blade_you_do_not_need_to_upload_proof_of_residency_again_beca') }}</p>
                                        <span class="verified-file-name">
                                            <i class="fas fa-lock"></i>
                                            {{ $savedResidencyProofLabel }}
                                        </span>
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
                            <div class="upload-group col-12">
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

                    <div class="form-group mt-4">
                        <label for="purpose"><i class="fas fa-bullseye"></i> {{ __('messages.indigency_form_purpose_label') }}</label>
                        <select id="purpose" name="purpose" required class="form-control">
                            <option value="">{{ __('messages.blade_clearance_form_blade_select_purpose') }}</option>
                            <option value="medical">{{ __('messages.indigency_purpose_medical') }}</option>
                            <option value="scholarship">{{ __('messages.indigency_purpose_scholarship') }}</option>
                            <option value="government">{{ __('messages.indigency_purpose_government') }}</option>
                            <option value="legal">{{ __('messages.indigency_purpose_legal') }}</option>
                            <option value="employment">{{ __('messages.indigency_purpose_employment') }}</option>
                            <option value="burial">{{ __('messages.indigency_purpose_burial') }}</option>
                            <option value="other">{{ __('messages.clearance_purpose_other') }}</option>
                        </select>
                        <textarea id="purposeOther" name="purpose_other" placeholder="{{ __('messages.form_purpose_other_placeholder') }}" rows="2" style="{{ old('purpose') === 'other' ? '' : 'display: none;' }} margin-top: 10px;" class="form-control">{{ old('purpose_other') }}</textarea>
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
                    <h3><i class="fas fa-check-circle"></i> {{ __('messages.form_step_review_submit') }}</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-user-check"></i> {{ __('messages.blade_indigency_form_blade_locked_resident_profile') }}</h4>
                            <div class="review-content" id="reviewPersonal"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-check"></i> {{ __('messages.blade_indigency_form_blade_uploaded_documents') }}</h4>
                            <div class="review-content" id="reviewDocuments"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> {{ __('messages.indigency_form_fee_card_title') }}</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>{{ __('messages.indigency_form_fee_label') }}</span>
                                        <span style="text-align: end;">{{ __('messages.indigency_form_fee_amount') }}</span>
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
                                            <h5><i class="fas fa-building"></i> {{ __('messages.blade_clearance_form_blade_payment_pickup_location') }}</h5>
                                            <p>{{ __('messages.blade_clearance_form_blade_barangay_hulong_duhat_hall') }}<br>{{ __('messages.footer_address') }}</p>
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
                                                <li>{{ __('messages.blade_indigency_form_blade_valid_id_same_as_uploaded') }}</li>
                                                <li>{{ __('messages.indigency_form_bring_reference') }}</li>
                                                <li>{{ __('messages.indigency_form_bring_no_payment') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> {{ __('messages.indigency_form_benefits_title') }}</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>{{ __('messages.blade_clearance_form_blade_no_long_lines') }}</h5>
                                <p class="mb-0">{{ __('messages.blade_indigency_form_blade_skip_the_queue_when_you_arrive') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>{{ __('messages.form_benefit_2_title') }}</h5>
                                <p class="mb-0">{{ __('messages.indigency_form_benefit_2_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>{{ __('messages.indigency_form_benefit_3_title') }}</h5>
                                <p class="mb-0">{{ __('messages.indigency_form_benefit_3_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkmark"></span>
                            {{ __('messages.indigency_form_terms_1') }}
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="privacy" name="privacy" required>
                            <span class="checkmark"></span>
                            <p style="margin-bottom: 0;">{{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" data-stop-propagation>{{ __('messages.register_privacy_title') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditionsModal" data-stop-propagation>{{ __('messages.terms_modal_title') }}</a> {{ __('messages.blade_indigency_form_blade_of_barangay_hulo_online_services') }}</p>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="pickup" name="pickup" required>
                            <span class="checkmark"></span>
                            {{ __('messages.indigency_form_terms_pickup') }}
                        </label>
                    </div>
                    <div id="termsError" style="display:none;color:#ff4444;font-size:0.85rem;margin-top:8px;padding:8px 12px;background:rgba(255,68,68,0.05);border-radius:6px;border:1px solid rgba(255,68,68,0.3);">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.form_terms_error') }}
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
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
                        <i class="fas fa-envelope me-2"></i>
                        {{ __('messages.blade_clearance_form_blade_email_profile_correction_concern') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>

                <form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile') ? route('indigency.reportIncorrectProfile') : '#' }}" data-confirm="{{ __('messages.blade_indigency_form_blade_this_will_send_a_correction_concern_to_the_barangay_off') }}">
                    @csrf

                    <div class="modal-body">
                        @if (! \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile'))
                            <div class="alert alert-warning">
                                {{ __('messages.blade_indigency_form_blade_add_the_route') }} <strong>{{ __('messages.blade_indigency_form_blade_indigency_reportincorrectprofile') }}</strong> {{ __('messages.blade_indigency_form_blade_first_before_using_this_correction_form') }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                {{ __('messages.blade_clearance_form_blade_your_identity_details_cannot_be_edited_directly_the_bar') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="fieldName">
                                <i class="fas fa-list"></i>
                                {{ __('messages.blade_clearance_form_blade_which_information_is_incorrect') }}
                            </label>
                            <select id="fieldName" name="field_name" class="form-control" required>
                                <option value="">{{ __('messages.blade_clearance_form_blade_select_information') }}</option>
                                <option value="Full name">{{ __('messages.form_review_label_full_name') }}</option>
                                <option value="Birthdate">{{ __('messages.blade_clearance_form_blade_birthdate') }}</option>
                                <option value="Gender">{{ __('messages.form_review_label_gender') }}</option>
                                <option value="Civil Status">{{ __('messages.blade_indigency_form_blade_civil_status') }}</option>
                                <option value="Place of Birth">{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</option>
                                <option value="Complete address">{{ __('messages.register_address_label') }}</option>
                                <option value="Contact number">{{ __('messages.register_contact_label') }}</option>
                                <option value="Email address">{{ __('messages.register_email_label') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="correctValue">
                                <i class="fas fa-pen"></i>
                                {{ __('messages.blade_clearance_form_blade_correct_information') }}
                            </label>
                            <input type="text" id="correctValue" name="correct_value" class="form-control" required placeholder="{{ __('messages.blade_clearance_form_blade_enter_the_correct_information') }}">
                        </div>

                        <div class="form-group">
                            <label for="correctionMessage">
                                <i class="fas fa-message"></i>
                                {{ __('messages.blade_clearance_form_blade_message') }}
                            </label>
                            <textarea id="correctionMessage" name="message" class="form-control" rows="3" placeholder="{{ __('messages.blade_clearance_form_blade_explain_what_needs_to_be_corrected') }}"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-prev" data-bs-dismiss="modal">{{ __('messages.register_cancel_btn') }}</button>
                        <button type="submit" class="btn-correction-submit" @if (! \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile')) disabled @endif>
                            <i class="fas fa-paper-plane"></i>
                            {{ __('messages.blade_clearance_form_blade_send_email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('barangay_system.partials.policy_modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

        @include('shared.js-config', [
    'id' => 'js-config-barangay-system-indigency-form-1',
    'data' => [
        'v1' => $needsPrimaryProof,
        'v2' => $needsValidIdUpload,
        'v3' => $savedValidIdLabel,
        'v4' => $savedResidencyProofLabel,
        'v5' => $profileReviewData,
        'v6' => __('messages.blade_js_household_members_must_be_between_1_and_20'),
        'v7' => __('messages.blade_jsdyn_please_upload_label'),
        'v8' => __('messages.blade_js_invalid_file_type_please_upload_jpg_png_or_pdf_only'),
        'v9' => __('messages.blade_js_file_is_too_large_maximum_file_size_is_5mb'),
        'v10' => __('messages.blade_js_please_confirm_your_locked_resident_profile_before_continu'),
        'v11' => __('messages.blade_js_please_select_your_estimated_monthly_income'),
        'v12' => __('messages.blade_upload_label_a_primary_proof_of_residency'),
        'v13' => __('messages.blade_upload_label_your_valid_id'),
        'v14' => __('messages.blade_js_please_select_the_purpose_of_your_indigency_certificate'),
        'v15' => __('messages.blade_js_please_specify_the_other_purpose'),
        'v16' => __('messages.blade_runtime_remove_file'),
        'v17' => __('messages.blade_upload_label_the_selected_file'),
        'v18' => e(__('messages.form_review_label_full_name')),
        'v19' => __('messages.blade_announcement_blade_not_provided'),
        'v20' => e(__('messages.blade_clearance_form_blade_birthdate')),
        'v21' => e(__('messages.form_review_label_gender')),
        'v22' => e(__('messages.form_review_label_address')),
        'v23' => e(__('messages.form_review_label_contact')),
        'v24' => e(__('messages.form_review_label_email')),
        'v25' => e(__('messages.blade_review_label_monthly_income')),
        'v26' => e(__('messages.blade_review_label_household_members')),
        'v27' => __('messages.blade_js_no_file_uploaded'),
        'v28' => __('messages.blade_jsdyn_already_verified_label'),
        'v29' => e(__('messages.blade_review_label_other_purpose_details')),
        'v30' => e(__('messages.track_result_purpose_label')),
        'v31' => e(__('messages.blade_certificate_residency_blade_proof_of_residency')),
        'v32' => e(__('messages.blade_certificate_indigency_blade_valid_id')),
    ],
])
<script src="{{ asset('js/pages/barangay_system/indigency_form-dynamic1.js') }}"></script>

<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>
</html>
