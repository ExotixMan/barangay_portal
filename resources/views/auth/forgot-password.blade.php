<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Barangay Hulo Portal</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/auth/forgot-password.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form-feedback.css') }}">
    <script src="{{ asset('js/form-feedback.js') }}" defer></script>
    @include('shared.public-theme')
</head>
<body>
    <div class="forgot-card">

        <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo-img">

        <h1 class="forgot-title">Forgot Password</h1>
        <div class="barangay-name">Barangay Hulo • Malabon City</div>

        <p class="forgot-subtitle">
            <i class="fas fa-lock text-muted me-2"></i>
            Enter your email address and we'll send you a link to reset your password.
        </p>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('resident.password.email') }}" id="forgotForm">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter your email"
                           required
                           autofocus>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text">Send Password Reset Link</span>
                <div class="spinner">
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                </div>
            </button>

            <a href="{{ route('login') }}" class="back-login">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>
        </form>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/pages/auth/forgot-password.js') }}"></script>
</body>
</html>
