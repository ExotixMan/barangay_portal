<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminSettingsService
{
    private string $envPath;

    private array $envMap = [
        
        'general.portal_name' => 'BISIG_PORTAL_NAME',
        'general.barangay_name' => 'BISIG_BARANGAY_NAME',
        'general.contact_email' => 'BISIG_CONTACT_EMAIL',
        'general.contact_phone' => 'BISIG_CONTACT_PHONE',
        'general.website' => 'BISIG_WEBSITE',
        'general.primary_color' => 'BISIG_PRIMARY_COLOR',
        'general.secondary_color' => 'BISIG_SECONDARY_COLOR',

        
        'document.request_title' => 'BISIG_DOCUMENT_REQUEST_TITLE',
        'document.request_footer' => 'BISIG_DOCUMENT_REQUEST_FOOTER',

        
        'sms.provider' => 'SMS_PROVIDER',
        'sms.api_base_url' => 'SMS_API_BASE_URL',
        'sms.api_key' => 'SMS_API_KEY',
        'sms.sender_name' => 'SMS_SENDER_NAME',
        'sms.daily_limit' => 'SMS_DAILY_LIMIT',
        'sms.monthly_limit' => 'SMS_MONTHLY_LIMIT',
        'sms.remaining_credits' => 'SMS_CREDITS_LEFT',
        'sms.account_status' => 'SMS_ACCOUNT_STATUS',
        'sms.account_name' => 'SMS_ACCOUNT_NAME',
        'sms.last_synced_at' => 'SMS_LAST_SYNCED_AT',

        
        'email.mailer' => 'MAIL_MAILER',
        'email.host' => 'MAIL_HOST',
        'email.port' => 'MAIL_PORT',
        'email.username' => 'MAIL_USERNAME',
        'email.password' => 'MAIL_PASSWORD',
        'email.encryption' => 'MAIL_ENCRYPTION',
        'email.api_key' => 'MAIL_API_KEY',
        'email.domain' => 'MAIL_DOMAIN',
        'email.endpoint' => 'MAIL_ENDPOINT',
        'email.message_stream' => 'MAIL_MESSAGE_STREAM',
        'email.from_address' => 'MAIL_FROM_ADDRESS',
        'email.from_name' => 'MAIL_FROM_NAME',

        
        'ai.provider' => 'AI_PROVIDER',
        'ai.fallback_providers' => 'AI_FALLBACK_PROVIDERS',
        'ai.cloudflare_account_id' => 'CLOUDFLARE_ACCOUNT_ID',
        'ai.keys.gemini' => 'GEMINI_API_KEY',
        'ai.keys.groq' => 'GROQ_API_KEY',
        'ai.keys.cloudflare' => 'CLOUDFLARE_API_TOKEN',

        
        'security.captcha_enabled' => 'RECAPTCHA_ENABLED',
        'security.recaptcha_site_key' => 'RECAPTCHA_SITE_KEY',
        'security.recaptcha_secret_key' => 'RECAPTCHA_SECRET_KEY',
        'security.login_otp_enabled' => 'LOGIN_OTP_ENABLED',
        'security.session_timeout' => 'AUTH_PASSWORD_TIMEOUT',
    ];

    
    private array $base64EnvMap = [
        'document.request_template' => 'BISIG_DOCUMENT_REQUEST_TEMPLATE_B64',
    ];

    
    private array $secretKeys = [
        'sms.api_key',
        'email.password',
        'email.api_key',
        'ai.keys.gemini',
        'ai.keys.groq',
        'ai.keys.cloudflare',
        'security.recaptcha_secret_key',
    ];

    private array $booleanKeys = [
        'security.captcha_enabled',
        'security.login_otp_enabled',
    ];

    private array $integerKeys = [
        'sms.daily_limit',
        'sms.monthly_limit',
        'email.port',
        'security.session_timeout',
    ];

    private array $floatKeys = [
        'sms.remaining_credits',
    ];

    public function __construct()
    {
        $this->envPath = base_path('.env');
    }

    public function all(): array
    {
        $env = $this->readRuntimeEnvironment();
        $settings = $this->defaults();

        foreach ($this->envMap as $settingKey => $envKey) {
            if (! array_key_exists($envKey, $env)) {
                continue;
            }

            data_set($settings, $settingKey, $this->castValue($settingKey, $env[$envKey]));
        }

        foreach ($this->base64EnvMap as $settingKey => $envKey) {
            if (! empty($env[$envKey])) {
                data_set($settings, $settingKey, base64_decode((string) $env[$envKey], true) ?: '');
            }
        }

        foreach ($this->databaseValues() as $settingKey => $value) {
            if (array_key_exists($settingKey, $this->envMap) || array_key_exists($settingKey, $this->base64EnvMap)) {
                data_set($settings, $settingKey, $this->castValue($settingKey, $value));
            }
        }

        $emailProvider = strtolower(trim((string) data_get($settings, 'email.mailer', 'smtp')));
        if (blank(data_get($settings, 'email.api_key'))) {
            $providerKey = match ($emailProvider) {
                'resend' => env('RESEND_KEY'),
                'postmark' => env('POSTMARK_TOKEN'),
                'mailgun' => env('MAILGUN_SECRET'),
                'sendgrid' => env('SENDGRID_API_KEY'),
                default => null,
            };
            if (filled($providerKey)) {
                data_set($settings, 'email.api_key', $providerKey);
            }
        }
        if ($emailProvider === 'mailgun' && blank(data_get($settings, 'email.domain')) && filled(env('MAILGUN_DOMAIN'))) {
            data_set($settings, 'email.domain', env('MAILGUN_DOMAIN'));
        }

        $settings['usage'] = array_replace_recursive(
            data_get($settings, 'usage', []),
            $this->usageFromLogs()
        );

        $settings['health'] = [
            'score' => $this->calculateHealthScore($settings),
            'checks' => $this->configurationChecks($settings),
        ];

        return $settings;
    }

    public function section(string $section): array
    {
        return (array) data_get($this->all(), $section, []);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->all(), $key, $default);
    }

    public function update(array $payload): array
    {
        unset($payload['usage'], $payload['health'], $payload['updated_at']);

        $flatPayload = Arr::dot($payload);
        $updates = [];

        foreach ($this->envMap as $settingKey => $envKey) {
            if (! array_key_exists($settingKey, $flatPayload)) {
                continue;
            }

            $value = $flatPayload[$settingKey];

            if (in_array($settingKey, $this->secretKeys, true) && blank($value)) {
                continue;
            }

            $updates[$envKey] = $value;
        }

        foreach ($this->base64EnvMap as $settingKey => $envKey) {
            if (! array_key_exists($settingKey, $flatPayload)) {
                continue;
            }

            $updates[$envKey] = base64_encode((string) $flatPayload[$settingKey]);
        }

        if ($this->settingsTableAvailable()) {
            $this->writeDatabaseValues($flatPayload);
        } else {
            $updates['BISIG_SETTINGS_UPDATED_AT'] = now('Asia/Manila')->toDateTimeString();
            $this->writeEnvValues($updates);
        }

        return $this->all();
    }

    public function incrementUsage(string $channel, int $count = 1): array
    {
        
        return $this->all();
    }

    public function canSendSms(int $count = 1): bool
    {
        $settings = $this->all();

        $count = max(1, $count);

        $sentToday = (int) data_get($settings, 'usage.sms_sent_today', 0);
        $sentMonth = (int) data_get($settings, 'usage.sms_sent_month', 0);
        $dailyLimit = (int) data_get($settings, 'sms.daily_limit', 0);
        $monthlyLimit = (int) data_get($settings, 'sms.monthly_limit', 0);
        $remainingCredits = (float) data_get($settings, 'sms.remaining_credits', 0);

        if ($dailyLimit > 0 && ($sentToday + $count) > $dailyLimit) {
            return false;
        }

        if ($monthlyLimit > 0 && ($sentMonth + $count) > $monthlyLimit) {
            return false;
        }

        if ($remainingCredits < $count) {
            return false;
        }

        return true;
    }

    public function calculateHealthScore(array $settings): int
    {
        $checks = $this->configurationChecks($settings);

        $passed = collect($checks)
            ->where('passed', true)
            ->count();

        return (int) round(($passed / max(1, count($checks))) * 100);
    }

    public function configurationChecks(array $settings): array
    {
        $provider = (string) data_get($settings, 'ai.provider', 'gemini');

        $captchaEnabled = (bool) data_get($settings, 'security.captcha_enabled');
        $loginOtpEnabled = (bool) data_get($settings, 'security.login_otp_enabled');

        $captchaReady = ! $captchaEnabled || (
            filled(data_get($settings, 'security.recaptcha_site_key')) &&
            filled(data_get($settings, 'security.recaptcha_secret_key'))
        );

        return [
            [
                'key' => 'portal_name',
                'label' => 'Portal name is set',
                'passed' => filled(data_get($settings, 'general.portal_name')),
            ],
            [
                'key' => 'barangay_name',
                'label' => 'Barangay name is set',
                'passed' => filled(data_get($settings, 'general.barangay_name')),
            ],
            [
                'key' => 'contact_email',
                'label' => 'Contact email is set',
                'passed' => filled(data_get($settings, 'general.contact_email')),
            ],
            [
                'key' => 'sms_api_key',
                'label' => 'SMS API key is set',
                'passed' => filled(data_get($settings, 'sms.api_key')),
            ],
            [
                'key' => 'sms_sender',
                'label' => 'SMS sender name is set',
                'passed' => filled(data_get($settings, 'sms.sender_name')),
            ],
            [
                'key' => 'email_host',
                'label' => 'Email SMTP host is set',
                'passed' => filled(data_get($settings, 'email.host')),
            ],
            [
                'key' => 'email_from',
                'label' => 'Email from address is set',
                'passed' => filled(data_get($settings, 'email.from_address')),
            ],
            [
                'key' => 'ai_key',
                'label' => strtoupper($provider) . ' API key is set',
                'passed' => filled(data_get($settings, 'ai.keys.' . $provider)),
            ],
            [
                'key' => 'captcha',
                'label' => 'CAPTCHA keys are ready when enabled',
                'passed' => $captchaReady,
            ],
            [
                'key' => 'security_toggle',
                'label' => 'Login OTP or CAPTCHA is enabled',
                'passed' => $loginOtpEnabled || $captchaEnabled,
            ],
        ];
    }

    private function usageFromLogs(): array
    {
        try {
            if (! Schema::hasTable('notification_logs')) {
                return [];
            }

            $todayStart = now('Asia/Manila')->startOfDay()->toDateTimeString();
            $todayEnd = now('Asia/Manila')->endOfDay()->toDateTimeString();

            $monthStart = now('Asia/Manila')->startOfMonth()->toDateTimeString();
            $monthEnd = now('Asia/Manila')->endOfMonth()->toDateTimeString();

            return [
                'sms_sent_today' => DB::table('notification_logs')
                    ->where('channel', 'sms')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$todayStart, $todayEnd])
                    ->count(),

                'sms_sent_month' => DB::table('notification_logs')
                    ->where('channel', 'sms')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$monthStart, $monthEnd])
                    ->count(),

                'email_sent_today' => DB::table('notification_logs')
                    ->where('channel', 'email')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$todayStart, $todayEnd])
                    ->count(),

                'email_sent_month' => DB::table('notification_logs')
                    ->where('channel', 'email')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$monthStart, $monthEnd])
                    ->count(),

                'ai_requests_today' => DB::table('notification_logs')
                    ->where('channel', 'ai')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$todayStart, $todayEnd])
                    ->count(),

                'ai_requests_month' => DB::table('notification_logs')
                    ->where('channel', 'ai')
                    ->where('status', 'sent')
                    ->whereBetween('sent_at', [$monthStart, $monthEnd])
                    ->count(),
            ];
        } catch (Throwable) {
            return [];
        }
    }

    private function readRuntimeEnvironment(): array
    {
        $values = $this->readEnvFile();

        foreach (array_unique(array_merge(array_values($this->envMap), array_values($this->base64EnvMap))) as $envKey) {
            if (array_key_exists($envKey, $values)) {
                continue;
            }

            $value = env($envKey);
            if ($value !== null) {
                $values[$envKey] = $value;
            }
        }

        return $values;
    }

    private function settingsTableAvailable(): bool
    {
        try {
            return Schema::hasTable('system_settings');
        } catch (Throwable) {
            return false;
        }
    }

    private function databaseValues(): array
    {
        if (! $this->settingsTableAvailable()) {
            return [];
        }

        try {
            return DB::table('system_settings')->pluck('setting_value', 'setting_key')->all();
        } catch (Throwable) {
            return [];
        }
    }

    private function writeDatabaseValues(array $flatPayload): void
    {
        $now = now();
        $allowed = array_merge(array_keys($this->envMap), array_keys($this->base64EnvMap));

        foreach ($allowed as $settingKey) {
            if (! array_key_exists($settingKey, $flatPayload)) {
                continue;
            }

            $value = $flatPayload[$settingKey];
            if (in_array($settingKey, $this->secretKeys, true) && blank($value)) {
                continue;
            }

            DB::table('system_settings')->updateOrInsert(
                ['setting_key' => $settingKey],
                [
                    'setting_value' => is_bool($value) ? ($value ? '1' : '0') : (string) ($value ?? ''),
                    // PostgreSQL has a native boolean type and rejects integer 0/1
                    // bindings for boolean columns. Use SQL boolean literals explicitly.
                    'is_secret' => DB::raw(in_array($settingKey, $this->secretKeys, true) ? 'TRUE' : 'FALSE'),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    private function emailConfigurationReady(array $email): bool
    {
        $provider = strtolower(trim((string) ($email['mailer'] ?? 'smtp')));
        $fromReady = filled($email['from_address'] ?? null);

        if (! $fromReady) {
            return false;
        }

        return match ($provider) {
            'smtp', 'mail', 'failover' => filled($email['host'] ?? null) && filled($email['port'] ?? null),
            'resend', 'postmark', 'sendgrid' => filled($email['api_key'] ?? null),
            'mailgun' => filled($email['api_key'] ?? null) && filled($email['domain'] ?? null),
            'log' => true,
            default => false,
        };
    }

    private function readEnvFile(): array
    {
        if (! File::exists($this->envPath)) {
            return [];
        }

        $lines = preg_split('/\R/', File::get($this->envPath));
        $values = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $values[trim($key)] = $this->unquoteEnvValue(trim($value));
        }

        return $values;
    }

    private function writeEnvValues(array $updates): void
    {
        if (! File::exists($this->envPath)) {
            File::put($this->envPath, '');
        }

        $content = File::get($this->envPath);

        foreach ($updates as $key => $value) {
            $line = $key . '=' . $this->formatEnvValue($value);
            $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content);
            } else {
                $content = rtrim($content) . PHP_EOL . $line . PHP_EOL;
            }
        }

        File::put($this->envPath, $content);
    }

    private function formatEnvValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return '';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $value = (string) $value;

        if ($value === '') {
            return '';
        }

        $escaped = str_replace(
            ['\\', '"'],
            ['\\\\', '\"'],
            $value
        );

        return '"' . $escaped . '"';
    }

    private function unquoteEnvValue(string $value): string
    {
        if (
            strlen($value) >= 2 &&
            str_starts_with($value, '"') &&
            str_ends_with($value, '"')
        ) {
            return stripcslashes(substr($value, 1, -1));
        }

        return $value;
    }

    private function castValue(string $settingKey, mixed $value): mixed
    {
        if (in_array($settingKey, $this->booleanKeys, true)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if (in_array($settingKey, $this->integerKeys, true)) {
            return (int) $value;
        }

        if (in_array($settingKey, $this->floatKeys, true)) {
            return (float) $value;
        }

        return $value;
    }

    private function defaults(): array
    {
        return [
            'general' => [
                'portal_name' => 'BISIG Hulong Duhat',
                'barangay_name' => 'Barangay Hulong Duhat',
                'contact_email' => '',
                'contact_phone' => '',
                'website' => config('app.url', ''),
                'primary_color' => '#d32f2f',
                'secondary_color' => '#c62828',
            ],

            'document' => [
                'request_title' => 'Barangay Request Certificate',
                'request_template' => "Republic of the Philippines\nCity of Malabon\n{{barangay_name}}\n\nTo Whom It May Concern:\n\nThis is to certify that {{full_name}}, residing at {{complete_address}}, has submitted a request for {{request_type}} with reference number {{request_id}}.\n\nDate Submitted: {{date_submitted}}\nContact Number: {{contact_number}}\nEmail Address: {{email_address}}\nPurpose: {{purpose_of_request}}\nRemarks: {{remarks}}\n\nThis certification is issued upon the request of the above-named person for whatever legal purpose it may serve.\n\nPrepared by:\nBarangay Administration",
                'request_footer' => 'Prepared by Barangay Hulong Duhat Administration',
            ],

            'sms' => [
                'provider' => 'semaphore',
                'api_base_url' => 'https://api.semaphore.co/api/v4',
                'api_key' => '',
                'sender_name' => 'HULONGDUHAT',
                'daily_limit' => 200,
                'monthly_limit' => 3000,
                'remaining_credits' => 0,
                'account_status' => '',
                'account_name' => '',
                'last_synced_at' => '',
            ],

            'email' => [
                'mailer' => 'smtp',
                'host' => '',
                'port' => 587,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'api_key' => '',
                'domain' => '',
                'endpoint' => '',
                'message_stream' => 'outbound',
                'from_address' => '',
                'from_name' => 'BISIG Hulong Duhat',
            ],

            'ai' => [
                'provider' => 'gemini',
                'fallback_providers' => 'gemini,groq,cloudflare',
                'cloudflare_account_id' => '',
                'keys' => [
                    'gemini' => '',
                    'groq' => '',
                    'cloudflare' => '',
                ],
            ],

            'security' => [
                'captcha_enabled' => false,
                'recaptcha_site_key' => '',
                'recaptcha_secret_key' => '',
                'login_otp_enabled' => false,
                'session_timeout' => 10800,
            ],

            'usage' => [
                'sms_sent_today' => 0,
                'sms_sent_month' => 0,
                'email_sent_today' => 0,
                'email_sent_month' => 0,
                'ai_requests_today' => 0,
                'ai_requests_month' => 0,
            ],

            'updated_at' => null,
        ];
    }
}