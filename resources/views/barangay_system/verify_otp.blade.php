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
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('/Images/bgnew(3).png') center/cover no-repeat;
            padding: 16px;
        }

        .otp-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 14px;
            padding: 28px 22px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .otp-title {
            color: #c4161c;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }

        .otp-input {
            border: 1px solid #d9d9d9;
            border-radius: 8px;
            padding: 10px 12px;
        }

        .otp-btn {
            border: none;
            background: #c4161c;
            color: #ffffff;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 14px;
            width: 100%;
        }

        .otp-btn:hover {
            background: #a10f14;
        }
    </style>
</head>
@include('chatbot.embed')
<body>
    <div class="otp-card">
        <h3 class="otp-title">{{ __('messages.otp_title') }}</h3>

        <form method="POST" action="{{ route('otp.verify') }}" class="d-grid gap-3">
            @csrf
            <input type="hidden" name="resident_id" value="{{ session('resident_id') }}">

            <input
                type="text"
                name="otp"
                class="otp-input"
                placeholder="{{ __('messages.otp_placeholder') }}"
                required
                autocomplete="one-time-code"
                inputmode="numeric"
            >

            <button type="submit" class="otp-btn">{{ __('messages.otp_verify_btn') }}</button>
        </form>
    </div>

    <script src="{{ asset('js/dark-mode.js') }}"></script>
</body>
</html>