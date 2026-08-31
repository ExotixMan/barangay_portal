@php
    $adminSettings = app(\App\Services\AdminSettingsService::class);

    $portalName = $adminSettings->get('general.portal_name', 'Barangay Hulo Portal');
    $primaryColor = $adminSettings->get('general.primary_color', '#c62828');
    $secondaryColor = $adminSettings->get('general.secondary_color', '#8e0000');

    $captchaEnabled = (bool) $adminSettings->get('security.captcha_enabled', false);
    $recaptchaSiteKey = $adminSettings->get('security.recaptcha_site_key', '');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="--primary: {{ $primaryColor }}; --primary-dark: {{ $secondaryColor }};">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.blade_register_register_portal', ['portal' => $portalName]) }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/register.css') }}">
</head>
@include('chatbot.embed')

<body>

    <div class="back-button-container">
        <a href="{{ route('login') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.register_back_btn') }}
        </a>
    </div>

    <div class="form-page-header">
        <h1><i class="fas fa-user-plus"></i> {{ __('messages.register_page_title') }}</h1>
        <p>{{ __('messages.register_page_subtitle') }}</p>
    </div>

    <div class="main-container">

        <div class="form-container">
            <div class="register-panel-header">
                <div>
                    <span class="eyebrow"><i class="fas fa-shield-alt"></i> {{ __('messages.blade_register_blade_resident_registration') }}</span>
                    <h2>{{ __('messages.blade_register_blade_create_your_verified_barangay_hulong_duhat_account') }}</h2>
                    <p>{{ __('messages.blade_register_blade_use_clear_personal_details_a_complete_residency_address') }}</p>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register.res') }}" id="registerForm" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.register_step1_title') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.register_step2_title') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user"></i> {{ __('messages.register_step1_heading') }}</h3>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_first_name_label') }} *
                                </label>
                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_first_name') }}" class="form-control @error('firstname') is-invalid @enderror">
                                <div class="form-hint">{{ __('messages.form_name_hint') }}</div>
                                @error('firstname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middlename">
                                    <i class="fas fa-user"></i> {{ __('messages.register_middle_name_label') }}
                                </label>
                                <input type="text" id="middlename" name="middlename" value="{{ old('middlename') }}"
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_middle_name') }}" class="form-control @error('middlename') is-invalid @enderror">
                                <div class="form-hint">{{ __('messages.blade_register_blade_optional_but_helps_with_verification') }}</div>
                                @error('middlename')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_last_name_label') }} *
                                </label>
                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_last_name') }}" class="form-control @error('lastname') is-invalid @enderror">
                                @error('lastname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="suffix">
                                    <i class="fas fa-user-tag"></i> {{ __('messages.register_suffix_label') }}
                                </label>
                                <select id="suffix" name="suffix" class="form-control @error('suffix') is-invalid @enderror">
                                    <option value="" {{ old('suffix') == '' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_none') }}</option>
                                    <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_jr') }}</option>
                                    <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_sr') }}</option>
                                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_ii') }}</option>
                                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_iii') }}</option>
                                    <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_iv') }}</option>
                                    <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_v') }}</option>
                                </select>
                                <div class="form-hint">{{ __('messages.blade_register_blade_optional_select_if_applicable') }}</div>
                                @error('suffix')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthdate">
                                    <i class="fas fa-birthday-cake"></i> {{ __('messages.register_dob_label') }} *
                                </label>
                                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                    class="form-control @error('birthdate') is-invalid @enderror">
                                @error('birthdate')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="place_of_birth">
                                    <i class="fas fa-location-dot"></i> {{ __('messages.form_birth_place_label') }}
                                </label>
                                <input type="text" id="place_of_birth" name="place_of_birth"
                                    value="{{ old('place_of_birth') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_example_malabon_city') }}"
                                    class="form-control @error('place_of_birth') is-invalid @enderror">
                                @error('place_of_birth')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">
                                    <i class="fas fa-venus-mars"></i> {{ __('messages.form_gender_label') }}
                                </label>
                                <select id="gender" name="gender" required
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">{{ __('messages.blade_register_blade_select_gender') }}</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>{{ __('messages.form_gender_male') }}</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>{{ __('messages.form_gender_female') }}</option>
                                    <option value="Prefer not to say" {{ old('gender') === 'Prefer not to say' ? 'selected' : '' }}>{{ __('messages.form_gender_other') }}</option>
                                </select>
                                @error('gender')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="civil_status">
                                    <i class="fas fa-ring"></i> {{ __('messages.form_civil_status_label') }}
                                </label>
                                <select id="civil_status" name="civil_status" required
                                    class="form-control @error('civil_status') is-invalid @enderror">
                                    <option value="">{{ __('messages.blade_register_blade_select_civil_status') }}</option>
                                    <option value="Single" {{ old('civil_status') === 'Single' ? 'selected' : '' }}>{{ __('messages.civil_status_single') }}</option>
                                    <option value="Married" {{ old('civil_status') === 'Married' ? 'selected' : '' }}>{{ __('messages.civil_status_married') }}</option>
                                    <option value="Widowed" {{ old('civil_status') === 'Widowed' ? 'selected' : '' }}>{{ __('messages.civil_status_widowed') }}</option>
                                    <option value="Separated" {{ old('civil_status') === 'Separated' ? 'selected' : '' }}>{{ __('messages.civil_status_separated') }}</option>
                                </select>
                                @error('civil_status')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact">
                                    <i class="fas fa-phone"></i> {{ __('messages.register_contact_label') }} *
                                </label>
                                <input type="tel" id="contact" name="contact" value="{{ old('contact') }}" required
                                    placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" class="form-control @error('contact') is-invalid @enderror"
                                    pattern="[0-9]{11}" maxlength="11" inputmode="numeric">
                                <div class="form-hint" id="contactHint">{{ __('messages.blade_register_blade_use_11_digits_only_e_g_09123456789_we_will_check_this_b') }}</div>
                                @error('contact')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> {{ __('messages.register_email_label') }} *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="{{ __('messages.blade_incident_form_blade_your_email_example_com') }}" class="form-control @error('email') is-invalid @enderror"
                            autocomplete="email">
                        <div class="form-hint" id="emailHint">{{ __('messages.blade_register_blade_we_will_verify_this_email_for_account_updates_and_notif') }}</div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section-card residency-card">
                        <div class="section-header">
                            <div>
                                <span class="section-kicker"><i class="fas fa-map-location-dot"></i> {{ __('messages.blade_register_blade_residency_address') }}</span>
                                <h4>{{ __('messages.blade_register_blade_tell_us_exactly_where_you_reside') }}</h4>
                                <p>{{ __('messages.blade_register_blade_split_fields_make_the_address_easier_for_residents_to_f') }}</p>
                            </div>
                            <span class="section-badge"><i class="fas fa-circle-info"></i> {{ __('messages.blade_register_blade_barangay_and_city_are_fixed') }}</span>
                        </div>

                        <input type="hidden" id="address" name="address" value="{{ old('address') }}">

                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="house_no"><i class="fas fa-house"></i> {{ __('messages.blade_register_blade_house_no_unit_no') }}</label>
                                    <input type="text" id="house_no" name="house_no" value="{{ old('house_no') }}" required
                                        placeholder="{{ __('messages.blade_register_blade_example_25') }}" class="form-control js-address-field @error('house_no') is-invalid @enderror">
                                    @error('house_no')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="street"><i class="fas fa-road"></i> {{ __('messages.blade_register_blade_street') }}</label>
                                    <input type="text" id="street" name="street" value="{{ old('street') }}" required
                                        placeholder="{{ __('messages.blade_register_blade_example_camia_street') }}" class="form-control js-address-field @error('street') is-invalid @enderror">
                                    @error('street')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purok"><i class="fas fa-location-dot"></i> {{ __('messages.blade_register_blade_purok') }}</label>
                                    <select id="purok" name="purok" required class="form-control js-address-field @error('purok') is-invalid @enderror">
                                        <option value="" {{ old('purok') == '' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_select_purok') }}</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ __('messages.blade_register_purok_number', ['number' => $i]) }}" {{ old('purok') == ('Purok ' . $i) ? 'selected' : '' }}>{{ __('messages.blade_register_purok_number', ['number' => $i]) }}</option>
                                        @endfor
                                        <option value="I don't know" {{ old('purok') == "I don't know" ? 'selected' : '' }}>{{ __('messages.blade_register_blade_i_don_t_know') }}</option>
                                    </select>
                                    @error('purok')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="barangay"><i class="fas fa-map-pin"></i> {{ __('messages.blade_register_blade_barangay') }}</label>
                                    <input type="text" id="barangay" name="barangay" value="{{ old('barangay', 'Hulong Duhat') }}" readonly required
                                        class="form-control js-address-field @error('barangay') is-invalid @enderror">
                                    @error('barangay')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city"><i class="fas fa-city"></i> {{ __('messages.blade_register_blade_city') }}</label>
                                    <input type="text" id="city" name="city" value="{{ old('city', 'Malabon City') }}" readonly required
                                        class="form-control js-address-field @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="residency_status"><i class="fas fa-user-check"></i> {{ __('messages.blade_register_blade_residency_status') }}</label>
                                    <select id="residency_status" name="residency_status" required class="form-control js-address-field @error('residency_status') is-invalid @enderror">
                                        <option value="" {{ old('residency_status') == '' ? 'selected' : '' }}>{{ __('messages.form_civil_status_placeholder') }}</option>
                                        <option value="Resident" {{ old('residency_status') == 'Resident' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_resident') }}</option>
                                        <option value="Non-resident" {{ old('residency_status') == 'Non-resident' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_non_resident') }}</option>
                                        <option value="For verification" {{ old('residency_status') == 'For verification' ? 'selected' : '' }}>{{ __('messages.blade_profile_blade_for_verification') }}</option>
                                    </select>
                                    @error('residency_status')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="address-preview">
                            <i class="fas fa-eye"></i>
                            <span><strong>{{ __('messages.blade_register_blade_address_preview') }}</strong> <span id="addressPreviewText">{{ __('messages.blade_register_blade_complete_the_fields_above_to_build_the_full_address') }}</span></span>
                        </div>

                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section-card classification-card">
                        <div class="section-header">
                            <div>
                                <span class="section-kicker"><i class="fas fa-user-tag"></i> Resident Classification</span>
                                <h4>Student or PWD verification</h4>
                                <p class="mb-0 text-muted small">Only declare a classification that applies to you. Student and PWD status remain pending until barangay staff verifies the submitted proof.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="declare_student" value="1" id="declare_student" {{ old('declare_student') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="declare_student">
                                            <i class="fas fa-graduation-cap me-1 text-primary"></i> I am currently a student
                                        </label>
                                    </div>
                                    <p class="small text-muted mb-2">Accepted proof: current school ID, registration form, or certificate of enrollment.</p>
                                    <div id="studentProofFields" class="{{ old('declare_student') ? '' : 'd-none' }}">
                                        <label for="student_proof" class="form-label">Student Proof <span class="text-danger">*</span></label>
                                        <input type="file" id="student_proof" name="student_proof" class="form-control @error('student_proof') is-invalid @enderror" accept="image/jpeg,image/png,application/pdf">
                                        @error('student_proof')<div class="error-message">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="declare_pwd" value="1" id="declare_pwd" {{ old('declare_pwd') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="declare_pwd">
                                            <i class="fas fa-wheelchair me-1 text-primary"></i> I am a registered PWD
                                        </label>
                                    </div>
                                    <p class="small text-muted mb-2">Upload the official PWD ID card. Front and back must be different sides of the card.</p>
                                    <div id="pwdProofFields" class="{{ old('declare_pwd') ? '' : 'd-none' }}">
                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <label for="pwd_id_front" class="form-label">PWD ID Front <span class="text-danger">*</span></label>
                                                <input type="file" id="pwd_id_front" name="pwd_id_front" class="form-control @error('pwd_id_front') is-invalid @enderror" accept="image/jpeg,image/png,application/pdf">
                                                @error('pwd_id_front')<div class="error-message">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="pwd_id_back" class="form-label">PWD ID Back <span class="text-danger">*</span></label>
                                                <input type="file" id="pwd_id_back" name="pwd_id_back" class="form-control @error('pwd_id_back') is-invalid @enderror" accept="image/jpeg,image/png,application/pdf">
                                                @error('pwd_id_back')<div class="error-message">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border mt-3 mb-0 small">
                            <i class="fas fa-person-cane me-1"></i> Senior Citizen status is automatic from your verified birthdate when you are 60 years old or above.
                        </div>
                    </div>

                    <div class="form-section-card verification-card">
                        <div class="section-header">
                            <div>
                                <span class="section-kicker"><i class="fas fa-id-card-clip"></i> {{ __('messages.blade_register_blade_valid_id_scanner') }}</span>
                                <h4>{{ __('messages.blade_register_blade_scan_both_sides_of_your_valid_id') }}</h4>
                            </div>
                        </div>

                        <div class="scan-both-panel scan-both-panel-single">
                            <div>
                                <strong>Guided ID camera</strong>
                                <span>Align the FRONT side inside the guide. When the camera says Ready to capture, press Capture. Then flip the card and repeat for the BACK side.</span>
                            </div>
                            <button type="button" class="btn-scan-both" id="scanBothSidesBtn">
                                <i class="fas fa-camera-retro"></i> Open guided camera
                            </button>
                        </div>

                        <div class="desktop-valid-id-upload" aria-label="Desktop valid ID upload">
                            <div class="desktop-valid-id-upload-head">
                                <i class="fas fa-upload"></i>
                                <div>
                                    <strong>Upload Valid ID from your computer</strong>
                                    <span>Desktop users can upload a clear front and back image instead of using the camera scanner.</span>
                                </div>
                            </div>
                            <div class="desktop-valid-id-upload-grid">
                                <label class="desktop-valid-id-upload-card" for="valid_id">
                                    <i class="fas fa-id-card"></i>
                                    <span class="desktop-valid-id-upload-label">Front side</span>
                                    <span class="desktop-valid-id-upload-file" data-desktop-file-name="valid_id">Choose front image or PDF</span>
                                </label>
                                <label class="desktop-valid-id-upload-card" for="valid_id_back">
                                    <i class="fas fa-id-card-clip"></i>
                                    <span class="desktop-valid-id-upload-label">Back side</span>
                                    <span class="desktop-valid-id-upload-file" data-desktop-file-name="valid_id_back">Choose back image or PDF</span>
                                </label>
                            </div>
                            <small>Accepted: JPG, JPEG, PNG, or PDF. Maximum 5 MB per file.</small>
                        </div>

                        <div class="file-upload-wrapper id-auto-scan-wrapper" id="idAutoScanWrapper">
                            <input type="file" id="valid_id" name="valid_id"
                                accept="image/jpeg,image/png,application/pdf" required
                                class="form-control js-file-input visually-hidden @error('valid_id') is-invalid @enderror"
                                data-file-text="validIdFrontText">
                            <input type="file" id="valid_id_back" name="valid_id_back"
                                accept="image/jpeg,image/png,application/pdf" required
                                class="form-control js-file-input visually-hidden @error('valid_id_back') is-invalid @enderror"
                                data-file-text="validIdBackText">

                            <div class="id-auto-scan-status" id="idAutoScanStatus">
                                <i class="fas fa-id-card-clip"></i>
                                <div>
                                    <strong id="idAutoScanStatusTitle">{{ __('messages.blade_register_blade_no_id_scan_yet') }}</strong>
                                    <span id="idAutoScanStatusText">Use the guided camera or upload both sides manually. The camera never captures automatically.</span>
                                    <span class="d-none" id="validIdFrontText">{{ __('messages.blade_profile_blade_no_front_file_selected') }}</span>
                                    <span class="d-none" id="validIdBackText">{{ __('messages.blade_profile_blade_no_back_file_selected') }}</span>
                                </div>
                            </div>

                            @error('valid_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            @error('valid_id_back')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('login') }}" class="btn-prev">
                            <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                        </a>
                        <button type="button" class="btn-next" data-next="step2" id="nextToStep2">
                            {{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <h3><i class="fas fa-lock"></i> {{ __('messages.register_step2_title') }}</h3>

                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user-circle"></i> {{ __('messages.register_username_label') }} *
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            placeholder="{{ __('messages.blade_register_blade_choose_a_username') }}" class="form-control @error('username') is-invalid @enderror"
                            autocomplete="username">
                        <div class="form-hint" id="usernameHint">{{ __('messages.blade_register_blade_this_will_be_your_login_username') }}</div>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_password_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password" name="password" required
                                        placeholder="{{ __('messages.blade_register_blade_create_a_password') }}"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password"
                                        data-no-spaces>
                                    <button type="button" class="toggle-password" data-target="password"
                                        aria-label="{{ __('messages.blade_register_blade_show_or_hide_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>

                                <div class="password-meter">
                                    <div class="password-meter-fill" id="passwordMeterFill"></div>
                                </div>
                                <small class="form-hint" id="passwordStrengthText">{{ __('messages.blade_register_blade_password_strength_too_weak') }}</small>

                                <ul class="password-checklist">
                                    <li data-rule="length">{{ __('messages.blade_register_blade_at_least_8_characters') }}</li>
                                    <li data-rule="number">{{ __('messages.blade_register_blade_contains_a_number') }}</li>
                                    <li data-rule="upper">{{ __('messages.blade_register_blade_contains_uppercase_letter') }}</li>
                                    <li data-rule="special">{{ __('messages.blade_register_blade_contains_special_character') }}</li>
                                    <li style="grid-column: 1 / -1;"><strong>{{ __('messages.blade_register_blade_no_spaces_allowed') }}</strong></li>
                                </ul>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_confirm_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required placeholder="{{ __('messages.blade_register_blade_re_enter_your_password') }}"
                                        class="form-control"
                                        autocomplete="new-password"
                                        data-no-spaces>
                                    <button type="button" class="toggle-password" data-target="password_confirmation"
                                        aria-label="{{ __('messages.blade_register_blade_show_or_hide_confirm_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-hint" id="passwordMatchText">{{ __('messages.blade_register_blade_passwords_must_match') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-circle-check"></i> {{ __('messages.blade_register_blade_after_registration') }}</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <i class="fas fa-clock"></i>
                                <h5>{{ __('messages.blade_register_blade_online_requests') }}</h5>
                                <p>{{ __('messages.blade_register_blade_submit_barangay_forms_online') }}</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <h5>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</h5>
                                <p>{{ __('messages.blade_register_blade_check_updates_from_your_account') }}</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-bolt"></i>
                                <h5>{{ __('messages.blade_register_blade_less_waiting') }}</h5>
                                <p>{{ __('messages.blade_register_blade_prepare_requests_before_visiting') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('messages.blade_register_blade_terms_of_service') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal"
                                data-bs-target="#privacyModal">{{ __('messages.register_privacy_title') }}</a>
                        </label>
                        @error('terms')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($captchaEnabled)
                        <div class="captcha-section">
                            <label for="recaptchaBox" class="mb-2">
                                <i class="fas fa-shield-alt"></i> {{ __('messages.blade_register_blade_security_check') }}
                            </label>

                            @if (!empty($recaptchaSiteKey))
                                <div class="captcha-box" id="recaptchaBox">
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                </div>
                            @else
                                <div class="error-message">{{ __('messages.blade_register_blade_captcha_is_not_configured_please_contact_support') }}</div>
                            @endif

                            @error('g-recaptcha-response')
                                <div class="error-message" id="captchaError">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="button" class="btn-prev" data-prev="step1">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}
                        </button>
                        <button type="submit" class="btn-submit" id="createAccountBtn">
                            <span class="btn-text">{{ __('messages.register_submit_btn') }}</span>
                            <div class="spinner">
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">{{ __('messages.terms_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <h6>{{ __('messages.blade_register_blade_barangay_hulo_online_portal_terms_of_service') }}</h6>
                    <p>{{ __('messages.blade_register_last_updated_date', ['date' => date('F d, Y')]) }}</p>

                    <h6>{{ __('messages.blade_register_blade_1_acceptance_of_terms') }}</h6>
                    <p>{{ __('messages.blade_register_blade_by_accessing_and_using_the_barangay_hulo_online_portal_') }}</p>

                    <h6>{{ __('messages.blade_register_blade_2_user_registration') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_must_provide_accurate_and_complete_information_duri') }}</p>

                    <h6>{{ __('messages.blade_register_blade_3_eligibility') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_must_be_at_least_18_years_old_to_register_by_regist') }}</p>

                    <h6>{{ __('messages.blade_register_blade_4_valid_id_requirement') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_are_required_to_upload_a_valid_government_issued_id') }}</p>

                    <h6>{{ __('messages.blade_register_blade_5_privacy_and_data_protection') }}</h6>
                    <p>{{ __('messages.blade_register_blade_your_personal_information_will_be_collected_stored_and_') }}</p>

                    <h6>{{ __('messages.blade_register_blade_6_acceptable_use') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_agree_to_use_the_portal_only_for_lawful_purposes_yo') }}</p>
                    <ul>
                        <li>{{ __('messages.blade_register_blade_violate_any_laws_or_regulations') }}</li>
                        <li>{{ __('messages.blade_register_blade_impersonate_any_person_or_entity') }}</li>
                        <li>{{ __('messages.blade_register_blade_submit_false_or_misleading_information') }}</li>
                        <li>{{ __('messages.blade_register_blade_interfere_with_the_proper_functioning_of_the_portal') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_7_account_termination') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_reserve_the_right_to_suspend_or_terminate_your_accou') }}</p>

                    <h6>{{ __('messages.blade_register_blade_8_changes_to_terms') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_may_modify_these_terms_at_any_time_continued_use_of_') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">{{ __('messages.register_privacy_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <h6>{{ __('messages.blade_register_blade_barangay_hulo_privacy_policy') }}</h6>
                    <p>{{ __('messages.blade_register_last_updated_date', ['date' => date('F d, Y')]) }}</p>

                    <h6>{{ __('messages.blade_register_blade_1_information_we_collect') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_collect_personal_information_including_but_not_limit') }}</p>
                    <ul>
                        <li>{{ __('messages.form_review_label_full_name') }}</li>
                        <li>{{ __('messages.register_dob_label') }}</li>
                        <li>{{ __('messages.register_contact_label') }}</li>
                        <li>{{ __('messages.register_email_label') }}</li>
                        <li>{{ __('messages.register_address_label') }}</li>
                        <li>{{ __('messages.blade_register_blade_valid_id_image_pdf') }}</li>
                        <li>{{ __('messages.blade_register_blade_username_and_password') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_2_how_we_use_your_information') }}</h6>
                    <p>{{ __('messages.blade_register_blade_your_information_is_used_to') }}</p>
                    <ul>
                        <li>{{ __('messages.blade_register_blade_process_your_requests_and_applications') }}</li>
                        <li>{{ __('messages.blade_register_blade_verify_your_identity_and_residency') }}</li>
                        <li>{{ __('messages.blade_register_blade_communicate_important_announcements') }}</li>
                        <li>{{ __('messages.blade_register_blade_improve_our_services') }}</li>
                        <li>{{ __('messages.blade_register_blade_comply_with_legal_requirements') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_3_data_protection') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_implement_appropriate_technical_and_organizational_m') }}</p>

                    <h6>{{ __('messages.blade_register_blade_4_data_sharing') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_do_not_sell_or_rent_your_personal_information_inform') }}</p>

                    <h6>{{ __('messages.blade_register_blade_5_your_rights') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_have_the_right_to_access_correct_or_request_deletio') }}</p>

                    <h6>{{ __('messages.blade_register_blade_6_contact_information') }}</h6>
                    <p>
                        {{ __('messages.blade_register_blade_barangay_hulo_hall') }}<br>
                        {{ __('messages.blade_register_blade_hulong_duhat_malabon_city') }}<br>
                        {{ __('messages.blade_register_blade_email_barangayhulongduhat_gmail_com') }}<br>
                        {{ __('messages.blade_register_blade_tel_8_281_1373') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade id-scanner-modal" id="idScannerModal" tabindex="-1" aria-labelledby="idScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idScannerModalLabel">Guided Valid ID Camera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="scanner-help">
                        <i class="fas fa-lightbulb"></i>
                        <span><strong>Nothing is captured automatically.</strong> Keep all four corners inside the guide, avoid glare, hold the card steady, and wait for the green Ready to capture message before pressing Capture.</span>
                    </div>

                    <div class="scanner-stage">
                        <video id="idScannerVideo" autoplay playsinline muted></video>
                        <canvas id="idScannerOverlay"></canvas>
                        <div class="scanner-guide" aria-hidden="true"></div>

                        <div class="scanner-side-banner" id="idScannerSideBanner">
                            <span class="scanner-step-pill" id="idScannerStepText">1 / 2</span>
                            <strong id="idScannerSideText">FRONT SIDE</strong>
                            <small id="idScannerSideHelp">Keep the entire ID visible. For laminated cards, tilt slightly to move glare away from the text.</small>
                        </div>

                        <div class="scanner-transition-overlay d-none" id="idScannerTransitionOverlay" aria-live="assertive">
                            <div class="scanner-transition-card">
                                <i class="fas fa-rotate"></i>
                                <strong id="idScannerTransitionTitle">Front captured</strong>
                                <span id="idScannerTransitionText">Flip the ID now. Align the BACK side and press Capture only when it is ready.</span>
                                <span class="scanner-transition-countdown" id="idScannerTransitionCountdown">3</span>
                            </div>
                        </div>

                        <div class="scanner-status" id="idScannerStatus">
                            <span class="d-inline-flex align-items-center gap-2">
                                <span class="status-dot"></span>
                                <span id="idScannerStatusText">{{ __('messages.blade_profile_blade_camera_is_starting') }}</span>
                            </span>
                            <span id="idScannerTargetText" class="d-none">{{ __('messages.blade_register_blade_id_scan') }}</span>
                        </div>
                    </div>

                    <canvas id="idScannerWorkCanvas"></canvas>
                    <canvas id="idScannerResultCanvas"></canvas>

                    <div class="scanner-controls">
                        <button type="button" class="btn-scanner-secondary" id="idScannerRetakeBtn">
                            <i class="fas fa-rotate-right"></i> {{ __('messages.blade_profile_blade_restart_camera') }}
                        </button>
                        <button type="button" class="btn-scanner-primary" id="idScannerManualCaptureBtn" disabled>
                            <i class="fas fa-camera"></i> Capture when ready
                        </button>
                        <button type="button" class="btn-scanner-danger" data-bs-dismiss="modal">
                            <i class="fas fa-xmark"></i> {{ __('messages.register_cancel_btn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/pages/barangay_system/register.js') }}"></script>
    <script src="{{ asset('js/opencv-loader.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-register-1',
    'data' => [
        'v1' => ($captchaEnabled && !empty($recaptchaSiteKey)) ? 'true' : 'false',
        'v2' => __('messages.blade_jsdyn_residency_status_status'),
        'v3' => __('messages.blade_register_blade_complete_the_fields_above_to_build_the_full_address'),
        'v4' => __('messages.blade_jsdyn_field_is_required'),
        'v5' => __('messages.validation_name_format'),
        'v6' => __('messages.validation_name_min_length'),
        'v7' => __('messages.blade_register_blade_checking_username_availability'),
        'v8' => __('messages.form_email_label'),
        'v9' => __('messages.register_contact_label'),
        'v10' => __('messages.blade_register_blade_this_will_be_your_login_username'),
        'v11' => __('messages.blade_runtime_username_format_invalid'),
        'v12' => __('messages.blade_js_username_must_be_at_least_4_characters_long'),
        'v13' => __('messages.blade_runtime_username_allowed_chars'),
        'v14' => __('messages.blade_runtime_username_route_invalid'),
        'v15' => __('messages.blade_runtime_username_available'),
        'v16' => __('messages.blade_js_username_is_already_taken'),
        'v17' => __('messages.blade_runtime_username_check_unavailable'),
        'v18' => __('messages.blade_runtime_contact_default_hint'),
        'v19' => __('messages.blade_runtime_email_default_hint'),
        'v20' => __('messages.validation_email_format'),
        'v21' => __('messages.validation_contact_format'),
        'v22' => __('messages.blade_runtime_complete_mobile_first'),
        'v23' => __('messages.blade_jsdyn_checking_field_availability'),
        'v24' => __('messages.blade_runtime_field_route_invalid'),
        'v25' => __('messages.blade_runtime_field_available'),
        'v26' => __('messages.blade_runtime_field_taken'),
        'v27' => __('messages.blade_runtime_field_check_failed'),
        'v28' => __('messages.blade_runtime_username_required'),
        'v29' => __('messages.blade_js_username_may_only_contain_letters_numbers_underscores_dots'),
        'v30' => __('messages.blade_register_blade_passwords_must_match'),
        'v31' => __('messages.blade_runtime_passwords_match'),
        'v32' => __('messages.blade_runtime_passwords_do_not_match'),
        'v33' => __('messages.blade_runtime_agree_continue'),
        'v34' => __('messages.blade_runtime_complete_id_scan'),
        'v35' => __('messages.blade_runtime_file_max_5mb'),
        'v36' => __('messages.blade_runtime_file_type_jpg_png_pdf'),
        'v37' => __('messages.blade_runtime_scan_clear_back'),
        'v38' => __('messages.blade_runtime_scan_clear_front'),
        'v39' => __('messages.validation_field_required'),
        'v40' => __('messages.blade_js_address_must_be_at_least_10_characters_long'),
        'v41' => __('messages.blade_js_email_is_already_taken'),
        'v42' => __('messages.blade_js_contact_number_is_already_registered'),
        'v43' => __('messages.blade_runtime_place_birth_format'),
        'v44' => __('messages.blade_runtime_place_birth_min'),
        'v45' => __('messages.validation_dob_future'),
        'v46' => __('messages.blade_runtime_age_18_register'),
        'v47' => __('messages.blade_runtime_agree_terms'),
        'v48' => __('messages.blade_profile_blade_no_back_file_selected'),
        'v49' => __('messages.blade_profile_blade_no_front_file_selected'),
        'v50' => __('messages.blade_profile_blade_front_side'),
        'v51' => 'Guided Valid ID Camera',
        'v52' => __('messages.blade_profile_blade_back_side'),
        'v53' => __('messages.blade_runtime_id_side'),
        'v54' => __('messages.blade_runtime_scanning_front_movement'),
        'v55' => __('messages.blade_runtime_scanning_id'),
        'v56' => __('messages.blade_runtime_rescan_id'),
        'v57' => 'Open guided camera',
        'v58' => __('messages.blade_register_blade_id_scan_complete'),
        'v59' => __('messages.blade_register_blade_front_side_captured_back_side_captured_both_passed_qual'),
        'v60' => __('messages.blade_register_blade_front_side_captured'),
        'v61' => __('messages.blade_register_blade_front_side_is_saved_flip_the_id_now_the_scanner_will_ca'),
        'v62' => __('messages.blade_register_blade_back_side_captured'),
        'v63' => __('messages.blade_register_blade_back_side_is_saved_but_the_front_side_is_still_missing_'),
        'v64' => 'No new ID captured yet',
        'v65' => 'Open the guided camera or upload both sides manually. You press Capture only when the status says Ready.',
        'v66' => __('messages.blade_runtime_id_not_complete'),
        'v67' => __('messages.blade_runtime_id_too_far'),
        'v68' => __('messages.blade_runtime_id_too_close'),
        'v69' => __('messages.blade_runtime_id_shape_invalid'),
        'v70' => __('messages.blade_runtime_id_crop_small'),
        'v71' => __('messages.blade_runtime_id_crop_shape'),
        'v72' => __('messages.blade_runtime_id_dark_rescan'),
        'v73' => __('messages.blade_runtime_id_bright_rescan'),
        'v74' => __('messages.blade_runtime_id_unclear_rescan'),
        'v75' => __('messages.blade_runtime_id_dark_ratio'),
        'v76' => __('messages.blade_runtime_id_glare_ratio'),
        'v77' => __('messages.blade_runtime_id_blurry_detailed'),
        'v78' => __('messages.blade_runtime_front_again_detailed'),
        'v79' => __('messages.blade_runtime_id_quality_passed'),
        'v80' => __('messages.blade_runtime_scanner_first_saved_back_only'),
        'v81' => __('messages.blade_register_scanner_place_the_front_side_inside_the_guide_and_hold_it_steady'),
        'v82' => __('messages.blade_profile_scanner_side_failed_message'),
        'v83' => __('messages.blade_js_capture_failed_please_try_again'),
        'v84' => 'Front side saved. Flip the ID and align the back side inside the guide.',
        'v85' => 'Align the back side and wait for Ready to capture, then press Capture.',
        'v86' => __('messages.blade_register_scanner_side_captured_but_the_full_front_back_scan_is_not_complete'),
        'v87' => __('messages.blade_register_scanner_front_and_back_sides_passed_id_scan_complete'),
        'v88' => __('messages.blade_register_scanner_side_captured_successfully'),
        'v89' => __('messages.blade_runtime_scanner_attach_blocked'),
        'v90' => __('messages.blade_runtime_scanner_front_already'),
        'v91' => __('messages.blade_runtime_scanner_no_id'),
        'v92' => __('messages.blade_register_scanner_using_the_clearest_side_frame_found_checking_quality'),
        'v93' => __('messages.blade_register_scanner_checking_side_quality'),
        'v94' => __('messages.blade_runtime_scanner_good_frame'),
        'v95' => __('messages.blade_register_scanner_side_detected_but_not_clear_yet_message'),
        'v96' => __('messages.blade_register_scanner_side_detected_keep_it_inside_the_guide_progress_target'),
        'v97' => __('messages.blade_runtime_scanner_engine_manual'),
        'v98' => __('messages.blade_runtime_camera_unsupported'),
        'v99' => __('messages.blade_profile_blade_camera_is_starting'),
        'v100' => __('messages.blade_runtime_scanner_place_id'),
        'v101' => __('messages.blade_runtime_camera_permission_manual'),
        'v102' => __('messages.blade_js_scanner_ready_place_the_id_inside_the_guide'),
        'v103' => __('messages.blade_runtime_password_too_weak'),
        'v104' => __('messages.blade_runtime_strength_too_weak'),
        'v105' => __('messages.blade_runtime_strength_weak'),
        'v106' => __('messages.blade_runtime_strength_fair'),
        'v107' => __('messages.blade_runtime_strength_good'),
        'v108' => __('messages.blade_runtime_strength_strong'),
        'v109' => __('messages.blade_jsdyn_password_strength_label'),
        'v110' => __('messages.blade_runtime_complete_captcha'),
        'v111' => __('messages.blade_register_blade_creating_account'),
        'v112' => $errors->hasAny(['username', 'password', 'password_confirmation', 'terms', 'g-recaptcha-response']) ? 'true' : 'false',
    ],
])
<script src="{{ asset('js/pages/barangay_system/register-dynamic1.js') }}"></script>
    @if($captchaEnabled && !empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</body>

</html>
