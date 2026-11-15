<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.otp_title') }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/verify_otp.css') }}">
</head>
@include('chatbot.embed')
<body>

    <div class="otp-card">
        @php
            $otpPurpose = session('otp_purpose', 'login');
            $cooldownSeconds = (int) ($otpCooldownSeconds ?? 0);
        @endphp
        <div class="otp-badge">{{ __('messages.blade_verify_otp_blade_otp_verification') }}</div>

        <h3 class="otp-title">
            @if ($otpPurpose === 'register')
                {{ __('messages.blade_otp_verify_your_mobile_number') }}
            @else
                {{ __('messages.blade_otp_enter_otp_to_login') }}
            @endif
        </h3>

        <p class="otp-subtitle">
            @if ($otpPurpose === 'register')
                {{ __('messages.blade_otp_we_sent_a_6_digit_code_to_your_mobile_number_enter_it_belo') }}
            @else
                {{ __('messages.blade_otp_a_6_digit_otp_was_sent_to_your_mobile_number_enter_it_belo') }}
            @endif
        </p>

        @if(!empty($maskedContact))
            <p class="otp-contact">{{ __('messages.blade_verify_otp_blade_sent_to') }} <strong>{{ $maskedContact }}</strong></p>
        @endif

        @if (session('success'))
            <div class="otp-status success show">{{ session('success') }}</div>
        @endif

        @if (session('fail'))
            <div class="otp-status error show">{{ session('fail') }}</div>
        @endif

        <div id="otpStatus" class="otp-status" role="status" aria-live="polite"></div>

        <form method="POST" action="{{ route('otp.verify') }}" class="d-grid gap-3">
            @csrf
            <input type="hidden" name="resident_id" value="{{ session('resident_id') }}">

            <div>
                <label for="otp" class="otp-label">{{ __('messages.blade_verify_otp_blade_one_time_password') }}</label>
                <input
                    id="otp"
                    type="text"
                    name="otp"
                    class="otp-input"
                    placeholder="{{ __('messages.otp_placeholder') }}"
                    maxlength="6"
                    required
                    autocomplete="one-time-code"
                    inputmode="numeric"
                    value="{{ old('otp') }}"
                >
                <div class="otp-help">{{ __('messages.blade_verify_otp_blade_otp_expires_in_5_minutes_from_the_last_sent_code') }}</div>
                @error('otp')
                    <div class="otp-status error show mt-2">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="otp-btn">{{ __('messages.blade_verify_otp_blade_verify') }}</button>
        </form>

        <div class="resend-wrap">
            <button type="button" id="resendBtn" class="btn btn-link p-0" data-click-call="resendOtp" disabled>
                {{ __('messages.blade_verify_otp_blade_resend_otp') }}<span id="countdown">05:00</span>)
            </button>
            <div class="resend-note">{{ __('messages.blade_verify_otp_blade_you_can_request_a_new_code_only_after_the_timer_ends') }}</div>
        </div>
    </div>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-verify-otp-1',
    'data' => [
        'v1' => $cooldownSeconds,
        'v2' => __('messages.blade_otp_sending_new_otp'),
        'v3' => e(route('otp.resend')),
        'v4' => e(csrf_token()),
        'v5' => __('messages.blade_js_failed_to_resend_otp'),
        'v6' => __('messages.blade_js_a_new_otp_has_been_sent'),
        'v7' => __('messages.blade_js_failed_to_resend_otp_please_try_again'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/verify_otp-dynamic1.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>
</html>
