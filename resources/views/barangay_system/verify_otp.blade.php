<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.otp_title') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        :root {
            --otp-primary: #b31b1b;
            --otp-primary-dark: #8e1212;
            --otp-text: #212529;
            --otp-muted: #6c757d;
            --otp-surface: #ffffff;
            --otp-border: #e4e6ea;
            --otp-success-bg: #e8f7ee;
            --otp-success-text: #137a3a;
            --otp-error-bg: #fdecec;
            --otp-error-text: #9b1c1c;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(140deg, rgba(179, 27, 27, 0.2), rgba(249, 168, 37, 0.2)),
                url('/Images/bgnew(3).png') center/cover no-repeat;
            padding: 16px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .otp-card {
            width: 100%;
            max-width: 480px;
            background: var(--otp-surface);
            border-radius: 18px;
            padding: 30px 24px;
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(2px);
            animation: cardIn 0.35s ease-out;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .otp-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff5f5;
            color: var(--otp-primary);
            border: 1px solid #ffd2d2;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.82rem;
            font-weight: 700;
            margin: 0 auto 14px;
        }

        .otp-title {
            color: var(--otp-text);
            font-weight: 800;
            margin-bottom: 8px;
            text-align: center;
            letter-spacing: 0.2px;
        }

        .otp-subtitle {
            color: var(--otp-muted);
            margin-bottom: 16px;
            text-align: center;
            line-height: 1.5;
        }

        .otp-contact {
            text-align: center;
            margin-bottom: 18px;
            font-size: 0.92rem;
            color: #495057;
        }

        .otp-contact strong {
            color: var(--otp-primary);
        }

        .otp-status {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 0.92rem;
            display: none;
        }

        .otp-status.show {
            display: block;
        }

        .otp-status.success {
            background: var(--otp-success-bg);
            color: var(--otp-success-text);
            border: 1px solid #b9ebc9;
        }

        .otp-status.error {
            background: var(--otp-error-bg);
            color: var(--otp-error-text);
            border: 1px solid #f5bcbc;
        }

        .otp-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 8px;
            display: block;
        }

        .otp-input {
            border: 1px solid var(--otp-border);
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
            letter-spacing: 0.22em;
            font-size: 1.16rem;
            font-weight: 700;
            width: 100%;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .otp-input:focus {
            border-color: #f09f9f;
            box-shadow: 0 0 0 0.22rem rgba(179, 27, 27, 0.14);
            outline: none;
        }

        .otp-help {
            margin-top: 6px;
            color: #6c757d;
            font-size: 0.82rem;
        }

        .otp-btn {
            border: none;
            background: linear-gradient(140deg, var(--otp-primary), var(--otp-primary-dark));
            color: #ffffff;
            font-weight: 700;
            border-radius: 10px;
            padding: 11px 14px;
            width: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .otp-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(179, 27, 27, 0.26);
        }

        .resend-wrap {
            text-align: center;
            margin-top: 14px;
        }

        .resend-note {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 8px;
        }

        #resendBtn {
            text-decoration: none;
            font-weight: 700;
            color: var(--otp-primary);
        }

        #resendBtn[disabled] {
            color: #9aa0a6;
        }
    </style>
</head>
@include('chatbot.embed')
<body>

    <div class="otp-card">
        @php
            $otpPurpose = session('otp_purpose', 'login');
            $cooldownSeconds = (int) ($otpCooldownSeconds ?? 0);
        @endphp
        <div class="otp-badge">OTP Verification</div>

        <h3 class="otp-title">
            @if ($otpPurpose === 'register')
                {{ __('Verify your mobile number') }}
            @else
                {{ __('Enter OTP to Login') }}
            @endif
        </h3>

        <p class="otp-subtitle">
            @if ($otpPurpose === 'register')
                {{ __('We sent a 6-digit code to your mobile number. Enter it below to verify your number and complete registration.') }}
            @else
                {{ __('A 6-digit OTP was sent to your mobile number. Enter it below to continue logging in.') }}
            @endif
        </p>

        @if(!empty($maskedContact))
            <p class="otp-contact">Sent to: <strong>{{ $maskedContact }}</strong></p>
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
                <label for="otp" class="otp-label">One-Time Password</label>
                <input
                    id="otp"
                    type="text"
                    name="otp"
                    class="otp-input"
                    placeholder="Enter 6-digit OTP"
                    maxlength="6"
                    required
                    autocomplete="one-time-code"
                    inputmode="numeric"
                    value="{{ old('otp') }}"
                >
                <div class="otp-help">OTP expires in 5 minutes from the last sent code.</div>
                @error('otp')
                    <div class="otp-status error show mt-2">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="otp-btn">Verify</button>
        </form>

        <div class="resend-wrap">
            <button type="button" id="resendBtn" class="btn btn-link p-0" onclick="resendOtp()" disabled>
                Resend OTP (<span id="countdown">05:00</span>)
            </button>
            <div class="resend-note">You can request a new code only after the timer ends.</div>
        </div>
    </div>

    <script>
        let countdown = Number(@json($cooldownSeconds)) || 0;
        const countdownSpan = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const otpStatus = document.getElementById('otpStatus');

        function formatTime(totalSeconds) {
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function showStatus(message, type) {
            otpStatus.textContent = message;
            otpStatus.className = 'otp-status show ' + type;
        }

        function updateResendUi() {
            if (countdown > 0) {
                resendBtn.disabled = true;
                countdownSpan.textContent = formatTime(countdown);
            } else {
                resendBtn.disabled = false;
                countdownSpan.textContent = '00:00';
            }
        }

        updateResendUi();

        let timer = setInterval(() => {
            if (countdown > 0) {
                countdown--;
                updateResendUi();
            }
        }, 1000);

        document.getElementById('otp').addEventListener('input', (event) => {
            event.target.value = event.target.value.replace(/\D+/g, '').slice(0, 6);
        });

        function resendOtp() {
            if (countdown > 0) {
                return;
            }

            resendBtn.disabled = true;
            showStatus('Sending a new OTP...', 'success');

            fetch("{{ route('otp.resend') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    const error = new Error(data.message || 'Failed to resend OTP.');
                    error.retryAfter = Number(data.retry_after || 0);
                    throw error;
                }
                return data;
            })
            .then(data => {
                clearInterval(timer);
                countdown = Number(data.retry_after || 300);
                updateResendUi();
                timer = setInterval(() => {
                    if (countdown > 0) {
                        countdown--;
                        updateResendUi();
                    }
                }, 1000);
                showStatus(data.success || 'A new OTP has been sent.', 'success');
            })
            .catch((error) => {
                if (error.retryAfter && error.retryAfter > 0) {
                    countdown = error.retryAfter;
                    updateResendUi();
                } else {
                    resendBtn.disabled = false;
                }
                showStatus(error.message || 'Failed to resend OTP. Please try again.', 'error');
            });
        }
    </script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
</body>
</html>