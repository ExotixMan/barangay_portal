<?php

return [
    'general' => [
        'portal_name' => env('BISIG_PORTAL_NAME', 'BISIG Hulong Duhat'),
        'barangay_name' => env('BISIG_BARANGAY_NAME', 'Barangay Hulong Duhat'),
        'contact_email' => env('BISIG_CONTACT_EMAIL'),
        'contact_number' => env('BISIG_CONTACT_NUMBER'),
        'primary_color' => env('BISIG_PRIMARY_COLOR', '#d32f2f'),
        'secondary_color' => env('BISIG_SECONDARY_COLOR', '#b71c1c'),
    ],

    'ai' => [
        'provider' => env('BISIG_AI_PROVIDER', 'gemini'),
        'fallback_provider' => env('BISIG_AI_FALLBACK_PROVIDER'),

        'keys' => [
            'gemini' => env('GEMINI_API_KEY'),
            'groq' => env('GROQ_API_KEY'),
            'cloudflare' => env('CLOUDFLARE_API_TOKEN'),
        ],

        'cloudflare_account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
    ],

    'security' => [
        'captcha_enabled' => filter_var(env('BISIG_CAPTCHA_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'login_otp_enabled' => filter_var(env('BISIG_LOGIN_OTP_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'session_timeout' => env('BISIG_SESSION_TIMEOUT', 3600),
        'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
        'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
];