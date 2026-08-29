<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>{{ __('messages.blade_login_blade_login_barangay_hulo_portal') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/login.css') }}">
</head>
@include('chatbot.embed')
<body>
    <div class="login-container">

        <div class="login-panel">
            <div class="logo-section">
                <img src="{{ asset('Images/logo.jpg') }}" alt="{{ __('messages.blade_login_blade_barangay_logo') }}" class="logo-img">
                <h1>{{ __('messages.login_page_title') }}</h1>
                <p>{{ __('messages.login_brand_name') }}<br>{{ __('messages.login_brand_location') }}</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error') && !$errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div id="clientErrorAlert" class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="clientErrorMessage"></span>
            </div>

            <form method="POST" action="{{ route('login.res') }}" id="loginForm">
                @csrf
                @method('post')

                <div class="form-floating mb-3 position-relative">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control @error('login') is-invalid @enderror"
                           id="login" name="login" placeholder="{{ __('messages.login_username_label') }}"
                           value="{{ old('login') }}" required autofocus>
                    <label for="login">{{ __('messages.login_username_label') }}</label>
                </div>

                <div class="form-floating mb-2 position-relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control password @error('password') is-invalid @enderror"
                           id="password" name="password" placeholder="{{ __('messages.login_password_label') }}" required>
                    <label for="password">{{ __('messages.login_password_label') }}</label>
                    <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>

                <div class="options-row justify-content-end">
                    <a href="{{ route('resident.password.request') }}" class="forgot-link">
                        <i class="fas fa-lock me-1"></i>{{ __('messages.login_forgot_password') }}
                    </a>
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <span class="btn-text">{{ __('messages.login_submit_btn') }}</span>
                    <div class="spinner">
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                    </div>
                </button>

                <div class="register-link">
                    <p>{{ __('messages.login_no_account') }}</p>
                    <a href="#" id="createAccountLink">
                        <i class="fas fa-user-plus me-1"></i>{{ __('messages.login_create_account_link') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ __('messages.terms_modal_title') }}</h2>
                    <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>{{ __('messages.terms_modal_subtitle') }}</h3>
                    <p>{{ __('messages.terms_modal_intro') }}</p>

                    <h4>{{ __('messages.terms_section_1_title') }}</h4>
                    <p>{{ __('messages.blade_login_blade_you_are_responsible_for_maintaining_the_confidentiality') }}</p>

                    <h4>{{ __('messages.terms_section_2_title') }}</h4>
                    <p>{{ __('messages.blade_login_blade_you_must_provide_accurate_current_and_complete_informat') }}</p>

                    <h4>{{ __('messages.terms_section_3_title') }}</h4>
                    <p>{{ __('messages.blade_login_blade_your_personal_information_will_be_handled_in_accordance') }}</p>

                    <h4>{{ __('messages.terms_section_4_title') }}</h4>
                    <p>{{ __('messages.blade_login_blade_you_agree_to_use_the_portal_only_for_lawful_purposes_an') }}</p>

                    <p class="mt-3"><strong>{{ __('messages.terms_modal_footer_note') }}</strong></p>

                    <div class="privacy-check-row">
                        <input type="checkbox" id="privacyCheckbox">
                        <label for="privacyCheckbox">
                            {{ __('messages.terms_agree_checkbox') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-decline" data-bs-dismiss="modal">{{ __('messages.terms_decline_btn') }}</button>
                    <button type="button" class="btn-accept" id="acceptTerms" disabled>{{ __('messages.terms_agree_btn') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-login-1',
    'data' => [
        'v1' => e(__('messages.login_error_username_required')),
        'v2' => e(__('messages.login_error_password_required')),
        'v3' => e(route('register')),
    ],
])
<script src="{{ asset('js/pages/barangay_system/login-dynamic1.js') }}"></script>
</body>
</html>
