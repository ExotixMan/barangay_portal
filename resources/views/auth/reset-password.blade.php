<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – Barangay Hulong Duhat Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/auth/reset-password.css') }}">
</head>
<body>

<div class="reset-card">

    <div class="card-header-band">
        <div class="logo-ring">
            <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo">
        </div>
        <div class="card-title">Reset Password</div>
        <div class="card-subtitle">Barangay Hulong Duhat &bull; Malabon City</div>
    </div>

    <div class="card-body-pad">

        <p class="intro-text">
            <i class="fas fa-shield-halved me-1"></i>
            Create a strong new password for your account.
        </p>

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->has('email') || $errors->has('token'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first('email') ?: $errors->first('token') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('resident.password.update') }}" id="resetForm" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope me-1" style="color:var(--red)"></i> Email Address
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-envelope"></i>
                    <input type="email"
                           class="form-control"
                           id="email"
                           name="email"
                           value="{{ old('email', $email) }}"
                           readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock me-1" style="color:var(--red)"></i> New Password
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-lock"></i>
                    <span class="field-status" id="pwStatus"></span>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Create a strong password"
                           autocomplete="new-password"
                           required
                           data-no-spaces>
                    <button type="button" class="toggle-btn" id="togglePw" tabindex="-1" title="Show / hide">
                        <i class="far fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <div style="color:var(--red);font-size:.78rem;margin-top:5px;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </div>
                @enderror

                <div class="strength-wrap">
                    <div class="strength-bar-bg">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel">Enter a password</div>
                </div>

                <div class="req-box">
                    <p><i class="fas fa-list-check me-1"></i> Your password must contain:</p>
                    <ul class="req-list">
                        <li id="req-len"><i class="fas fa-circle"></i> 8+ characters</li>
                        <li id="req-up"><i class="fas fa-circle"></i>  Uppercase letter</li>
                        <li id="req-low"><i class="fas fa-circle"></i> Lowercase letter</li>
                        <li id="req-num"><i class="fas fa-circle"></i> Number</li>
                        <li id="req-sym"><i class="fas fa-circle"></i> Special character</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">
                    <i class="fas fa-lock me-1" style="color:var(--red)"></i> Confirm Password
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-lock"></i>
                    <span class="field-status" id="cfStatus"></span>
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Re-enter your password"
                           autocomplete="new-password"
                           required
                           data-no-spaces>
                    <button type="button" class="toggle-btn" id="toggleCf" tabindex="-1" title="Show / hide">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="field-error" id="cfError">
                    <i class="fas fa-circle-exclamation"></i> Passwords do not match.
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                <span class="btn-label">
                    <i class="fas fa-key me-2"></i> Reset Password
                </span>
                <div class="spinner-row">
                    <div class="dot"></div><div class="dot"></div><div class="dot"></div>
                </div>
            </button>

            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/pages/auth/reset-password.js') }}"></script>
</body>
</html>
