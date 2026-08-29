<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'sendgrid' => [
        'key' => env('SENDGRID_API_KEY'),
    ],

    'barangay' => [
        'support_email' => env('BISIG_CONTACT_EMAIL', env('MAIL_FROM_ADDRESS')),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'enabled' => env('RECAPTCHA_ENABLED', false),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'semaphore'),
        'api_key' => env('SMS_API_KEY'),
        'sender_name' => env('SMS_SENDER_NAME', 'HULONGDUHAT'),
        'api_base_url' => env('SMS_API_BASE_URL', 'https://api.semaphore.co/api/v4/messages'),
        'custom_endpoint' => env('SMS_CUSTOM_ENDPOINT'),
        'daily_limit' => (int) env('SMS_DAILY_LIMIT', 200),
        'monthly_limit' => (int) env('SMS_MONTHLY_LIMIT', 3000),
        'otp_daily_limit_per_number' => (int) env('SMS_OTP_DAILY_LIMIT_PER_NUMBER', 5),
    ],
];
