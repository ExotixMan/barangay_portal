<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('messages.blade_verify_email_blade_email_verification') }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/verify_email.css') }}">

    <link rel="stylesheet" href="{{ asset('css/form-feedback.css') }}">
    <script src="{{ asset('js/form-feedback.js') }}" defer></script>
    @include('shared.public-theme')
</head>
@include('chatbot.embed')
<body>

    <div class="verify-card">

        <img src="{{ asset('Images/logo.jpg') }}" class="logo">

        <h3 class="verify-title">{{ __('messages.verify_email_title') }}</h3>

        <p class="verify-text">
        {{ __('messages.verify_email_message') }}<br>
        {{ __('messages.blade_verify_email_blade_please_check_your_inbox_and_click_the_link_to_activate_') }}
        </p>

        @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
        {{ __('messages.verify_email_resent') }}
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
            @csrf

            <button type="submit" class="resend-btn w-100" id="resendBtn">
                {{ __('messages.blade_verify_email_blade_send_verification_email') }}
            </button>

        </form>

        <a href="{{ route('login') }}" class="back-login">
        {{ __('messages.verify_email_back_btn') }}
        </a>

    </div>
    @if (session('success'))
        <script src="{{ asset('js/pages/barangay_system/verify_email.js') }}"></script>
    @endif

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-verify-email-1',
    'data' => [
        'v1' => __('messages.blade_verify_email_blade_send_verification_email'),
        'v2' => __('messages.blade_verify_email_blade_resend_email'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/verify_email-dynamic1.js') }}"></script>

</body>
</html>
