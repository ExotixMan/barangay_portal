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

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --red:       #b30000;
            --dark-red:  #800000;
            --success:   #1a7a3c;
            --warning:   #b45309;
            --muted:     #6c757d;
        }

        body {
            min-height: 100vh;
            background: url('/Images/bgnew(3).png') center/cover no-repeat fixed;
            font-family: 'Assistant', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* ── Card ── */
        .reset-card {
            max-width: 480px;
            width: 100%;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.18);
        }

        /* ── Card header band ── */
        .card-header-band {
            background: linear-gradient(135deg, var(--red) 0%, var(--dark-red) 100%);
            padding: 30px 35px 24px;
            text-align: center;
            color: #fff;
        }

        .logo-ring {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.5);
            padding: 3px;
            background: #fff;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,0,0,.25);
        }

        .logo-ring img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .card-title {
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: .3px;
            margin-bottom: 2px;
        }

        .card-subtitle {
            font-size: .82rem;
            opacity: .85;
        }

        /* ── Card body ── */
        .card-body-pad {
            padding: 28px 35px 32px;
        }

        .intro-text {
            text-align: center;
            color: #555;
            font-size: .93rem;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        /* ── Alerts ── */
        .alert {
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            border-left: 4px solid;
            font-size: .88rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-success {
            background: #ecfdf5;
            border-left-color: var(--success);
            color: #065f46;
        }

        .alert-danger {
            background: #fff2f2;
            border-left-color: var(--red);
            color: #7f1d1d;
        }

        .alert i { margin-top: 2px; flex-shrink: 0; }

        /* ── Form groups ── */
        .form-group { margin-bottom: 20px; }

        .form-label {
            font-weight: 700;
            color: #333;
            font-size: .88rem;
            margin-bottom: 7px;
            display: block;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--red);
            font-size: 15px;
            pointer-events: none;
            z-index: 5;
        }

        .field-status {
            position: absolute;
            right: 42px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            display: none;
        }

        .field-status.valid   { color: var(--success); display: block; }
        .field-status.invalid { color: var(--red);     display: block; }

        .toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 4px 6px;
            z-index: 5;
            transition: color .2s;
        }

        .toggle-btn:hover { color: var(--red); }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 10px 82px 10px 42px;
            border: 1.5px solid #dde0e7;
            border-radius: 10px;
            font-family: 'Assistant', sans-serif;
            font-size: .95rem;
            color: #222;
            background: #fafafa;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--red);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(179,0,0,.1);
        }

        .form-control.is-valid {
            border-color: var(--success);
            background: #f0fdf4;
        }

        .form-control.is-invalid {
            border-color: var(--red);
            background: #fff5f5;
        }

        /* readonly email field */
        .form-control[readonly] {
            background: #f3f4f6;
            color: #555;
            cursor: default;
        }

        .form-control[readonly]:focus {
            border-color: #dde0e7;
            box-shadow: none;
        }

        .field-error {
            color: var(--red);
            font-size: .78rem;
            margin-top: 5px;
            display: none;
            align-items: center;
            gap: 4px;
        }

        /* ── Strength bar ── */
        .strength-wrap { margin-top: 8px; }

        .strength-bar-bg {
            height: 5px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: width .35s ease, background .35s ease;
        }

        .strength-label {
            font-size: .75rem;
            margin-top: 4px;
            text-align: right;
            color: var(--muted);
            font-weight: 600;
        }

        /* ── Requirements checklist ── */
        .req-box {
            background: #f8f9fb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 10px;
        }

        .req-box p {
            font-size: .8rem;
            font-weight: 700;
            color: #444;
            margin-bottom: 8px;
        }

        .req-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 10px;
        }

        .req-list li {
            font-size: .8rem;
            color: #888;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: color .25s;
        }

        .req-list li i {
            font-size: .65rem;
            width: 14px;
            text-align: center;
            transition: color .25s;
        }

        .req-list li.met {
            color: var(--success);
            font-weight: 600;
        }

        .req-list li.met i { color: var(--success); }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--red) 0%, var(--dark-red) 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin: 26px 0 16px;
            transition: transform .2s, box-shadow .2s, opacity .2s;
            letter-spacing: .3px;
        }

        .btn-submit:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(179,0,0,.35);
        }

        .btn-submit:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-submit.loading { pointer-events: none; opacity: .7; }
        .btn-submit.loading .btn-label { opacity: 0; }

        .spinner-row {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit.loading .spinner-row { display: flex; }

        .dot {
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
            animation: bounce 1.3s ease-in-out infinite;
        }

        .dot:nth-child(2) { animation-delay: .18s; }
        .dot:nth-child(3) { animation-delay: .36s; }

        @keyframes bounce {
            0%,100% { transform: scale(.7); opacity: .5; }
            50%      { transform: scale(1.2); opacity: 1; }
        }

        /* ── Back link ── */
        .back-link {
            display: block;
            text-align: center;
            color: var(--red);
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
            transition: color .2s, gap .2s;
        }

        .back-link:hover { color: var(--dark-red); text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .card-body-pad { padding: 22px 20px 28px; }
            .card-header-band { padding: 24px 20px 20px; }
            .req-list { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="reset-card">

    {{-- Red header band --}}
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

        {{-- Server success --}}
        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Server errors (non-field) --}}
        @if($errors->has('email') || $errors->has('token'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first('email') ?: $errors->first('token') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('resident.password.update') }}" id="resetForm" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email (display-only) --}}
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

            {{-- New Password --}}
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
                           onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                    <button type="button" class="toggle-btn" id="togglePw" tabindex="-1" title="Show / hide">
                        <i class="far fa-eye"></i>
                    </button>
                </div>

                {{-- Server-side password error --}}
                @error('password')
                    <div style="color:var(--red);font-size:.78rem;margin-top:5px;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </div>
                @enderror

                {{-- Strength bar --}}
                <div class="strength-wrap">
                    <div class="strength-bar-bg">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel">Enter a password</div>
                </div>

                {{-- Requirements checklist --}}
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

            {{-- Confirm Password --}}
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
                           onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                    <button type="button" class="toggle-btn" id="toggleCf" tabindex="-1" title="Show / hide">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="field-error" id="cfError">
                    <i class="fas fa-circle-exclamation"></i> Passwords do not match.
                </div>
            </div>

            {{-- Submit --}}
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
<script>
(function () {
    'use strict';

    const pwInput  = document.getElementById('password');
    const cfInput  = document.getElementById('password_confirmation');
    const pwStatus = document.getElementById('pwStatus');
    const cfStatus = document.getElementById('cfStatus');
    const cfError  = document.getElementById('cfError');
    const fillBar  = document.getElementById('strengthFill');
    const fillLabel= document.getElementById('strengthLabel');
    const submitBtn= document.getElementById('submitBtn');

    const reqs = {
        len: { el: document.getElementById('req-len'), test: v => v.length >= 8 },
        up:  { el: document.getElementById('req-up'),  test: v => /[A-Z]/.test(v) },
        low: { el: document.getElementById('req-low'), test: v => /[a-z]/.test(v) },
        num: { el: document.getElementById('req-num'), test: v => /[0-9]/.test(v) },
        sym: { el: document.getElementById('req-sym'), test: v => /[^A-Za-z0-9]/.test(v) },
    };

    /* ── Toggle visibility ── */
    function bindToggle(btnId, inputEl) {
        document.getElementById(btnId).addEventListener('click', function () {
            const show = inputEl.type === 'password';
            inputEl.type = show ? 'text' : 'password';
            this.querySelector('i').className = show ? 'far fa-eye-slash' : 'far fa-eye';
        });
    }

    bindToggle('togglePw', pwInput);
    bindToggle('toggleCf', cfInput);

    /* ── Strength colours ── */
    const levels = [
        { pct: 20, colour: '#dc2626', label: 'Very weak',  labelClass: '#dc2626' },
        { pct: 40, colour: '#ea580c', label: 'Weak',       labelClass: '#ea580c' },
        { pct: 60, colour: '#ca8a04', label: 'Fair',       labelClass: '#ca8a04' },
        { pct: 80, colour: '#16a34a', label: 'Good',       labelClass: '#16a34a' },
        { pct:100, colour: '#15803d', label: 'Strong ✓',   labelClass: '#15803d' },
    ];

    /* ── Validate password requirements ── */
    function checkPassword() {
        const val = pwInput.value;
        let met = 0;

        Object.values(reqs).forEach(r => {
            const ok = r.test(val);
            r.el.className = ok ? 'met' : '';
            r.el.querySelector('i').className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
            if (ok) met++;
        });

        if (val.length === 0) {
            fillBar.style.width = '0';
            fillBar.style.background = '';
            fillLabel.textContent = 'Enter a password';
            fillLabel.style.color = '#6c757d';
            pwStatus.className = 'field-status';
            pwInput.classList.remove('is-valid', 'is-invalid');
        } else {
            const lvl = levels[met - 1] || levels[0];
            fillBar.style.width  = lvl.pct + '%';
            fillBar.style.background = lvl.colour;
            fillLabel.textContent    = lvl.label;
            fillLabel.style.color    = lvl.labelClass;

            const allMet = met === 5;
            pwInput.classList.toggle('is-valid',   allMet);
            pwInput.classList.toggle('is-invalid', !allMet && val.length > 0);
            pwStatus.innerHTML   = allMet
                ? '<i class="fas fa-circle-check"></i>'
                : '<i class="fas fa-circle-xmark"></i>';
            pwStatus.className   = 'field-status ' + (allMet ? 'valid' : 'invalid');
        }

        checkConfirm();
        updateSubmit();
    }

    /* ── Validate confirm password ── */
    function checkConfirm() {
        const pw = pwInput.value;
        const cf = cfInput.value;

        if (cf.length === 0) {
            cfInput.classList.remove('is-valid', 'is-invalid');
            cfStatus.className = 'field-status';
            cfError.style.display = 'none';
            return;
        }

        const match = pw === cf;
        cfInput.classList.toggle('is-valid',   match);
        cfInput.classList.toggle('is-invalid', !match);
        cfStatus.innerHTML   = match
            ? '<i class="fas fa-circle-check"></i>'
            : '<i class="fas fa-circle-xmark"></i>';
        cfStatus.className   = 'field-status ' + (match ? 'valid' : 'invalid');
        cfError.style.display = match ? 'none' : 'flex';
    }

    /* ── Enable submit only when all rules met + passwords match ── */
    function updateSubmit() {
        const allReqsMet = Object.values(reqs).every(r => r.test(pwInput.value));
        const pwMatch    = pwInput.value === cfInput.value && cfInput.value.length > 0;
        submitBtn.disabled = !(allReqsMet && pwMatch);
    }

    pwInput.addEventListener('input', function() {
        // Remove spaces from password
        pwInput.value = pwInput.value.replace(/\s/g, "");
        checkPassword();
    });
    cfInput.addEventListener('input', function () {
        // Remove spaces from confirm password
        cfInput.value = cfInput.value.replace(/\s/g, "");
        checkConfirm();
        updateSubmit();
    });

    /* ── Loading state on submit ── */
    document.getElementById('resetForm').addEventListener('submit', function (e) {
        /* Final guard: should never be false since button is disabled, but just in case */
        const allReqsMet = Object.values(reqs).every(r => r.test(pwInput.value));
        const pwMatch    = pwInput.value === cfInput.value;
        if (!allReqsMet || !pwMatch) {
            e.preventDefault();
            return;
        }
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
    });

    /* ── Auto-dismiss alerts ── */
    document.querySelectorAll('.alert').forEach(function (a) {
        setTimeout(function () {
            a.style.transition = 'opacity .5s';
            a.style.opacity = '0';
            setTimeout(function () { a.style.display = 'none'; }, 500);
        }, 6000);
    });
})();
</script>
</body>
</html>