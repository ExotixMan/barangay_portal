<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use App\Services\SemaphoreService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class SystemSettingsController extends Controller
{
    public function __construct(private AdminSettingsService $settings)
    {
    }

    public function index()
    {
        $settings = $this->settings->all();

        $settings['usage'] = array_replace_recursive(
            data_get($settings, 'usage', []),
            $this->getRealUsageStats()
        );

        return view('admin.admin_settings', [
            'settings' => $settings,
            'templatePreview' => $this->renderTemplatePreview($settings),
            'configScore' => data_get($settings, 'health.score', 0),
            'configChecks' => data_get($settings, 'health.checks', []),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            
            'general.portal_name' => ['required', 'string', 'max:150'],
            'general.barangay_name' => ['required', 'string', 'max:150'],
            'general.contact_email' => ['required', 'email', 'max:150'],
            'general.contact_phone' => ['required', 'string', 'max:40'],
            'general.website' => ['nullable', 'url', 'max:255'],
            'general.primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'general.secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],

            
            'document' => ['nullable', 'array'],
            'document.request_title' => ['nullable', 'string', 'max:255'],
            'document.request_template' => ['nullable', 'string'],
            'document.request_footer' => ['nullable', 'string', 'max:255'],

            
            'sms.provider' => ['required', 'string', 'max:50'],
            'sms.api_base_url' => ['required', 'url', 'max:255'],
            'sms.api_key' => ['nullable', 'string', 'max:255'],
            'sms.sender_name' => ['nullable', 'string', 'max:50'],
            'sms.daily_limit' => ['required', 'integer', 'min:0', 'max:100000'],
            'sms.monthly_limit' => ['required', 'integer', 'min:0', 'max:100000'],

            
            'email.mailer' => ['required', 'string', 'in:smtp,resend,postmark,mailgun,sendgrid,log'],
            'email.host' => ['nullable', 'string', 'max:255'],
            'email.port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'email.username' => ['nullable', 'string', 'max:255'],
            'email.password' => ['nullable', 'string', 'max:255'],
            'email.encryption' => ['nullable', 'string', 'in:tls,ssl,starttls,none'],
            'email.api_key' => ['nullable', 'string', 'max:1000'],
            'email.domain' => ['nullable', 'string', 'max:255'],
            'email.endpoint' => ['nullable', 'url', 'max:255'],
            'email.message_stream' => ['nullable', 'string', 'max:100'],
            'email.from_address' => ['nullable', 'email', 'max:255'],
            'email.from_name' => ['nullable', 'string', 'max:255'],

            
            'ai.provider' => ['required', 'string', 'in:gemini,groq,cloudflare'],
            'ai.fallback_providers' => ['nullable', 'string', 'max:255'],
            'ai.cloudflare_account_id' => ['nullable', 'string', 'max:100'],
            'ai.keys.gemini' => ['nullable', 'string', 'max:500'],
            'ai.keys.groq' => ['nullable', 'string', 'max:500'],
            'ai.keys.cloudflare' => ['nullable', 'string', 'max:500'],

            
            'security.captcha_enabled' => ['nullable', 'boolean'],
            'security.recaptcha_site_key' => ['nullable', 'string', 'max:255'],
            'security.recaptcha_secret_key' => ['nullable', 'string', 'max:255'],
            'security.login_otp_enabled' => ['nullable', 'boolean'],
            'security.session_timeout' => ['required', 'integer', 'min:300', 'max:86400'],
        ]);

        
        $validated['security']['captcha_enabled'] = $request->boolean('security.captcha_enabled');
        $validated['security']['login_otp_enabled'] = $request->boolean('security.login_otp_enabled');

        
        $validated['general']['primary_color'] = strtolower($validated['general']['primary_color']);
        $validated['general']['secondary_color'] = strtolower($validated['general']['secondary_color']);

        $validated = $this->removeBlankSecretInputs($validated);

        $oldSettings = $this->settings->all();

        $this->settings->update($validated);

        $this->recordAudit($request, $oldSettings, $validated);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'System settings updated successfully.');
    }

    private function getRealUsageStats(): array
    {
        if (!Schema::hasTable('notification_logs')) {
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
    }

    private function renderTemplatePreview(array $settings): string
    {
        $template = (string) data_get($settings, 'document.request_template', '');

        $sample = [
            '{{portal_name}}' => (string) data_get($settings, 'general.portal_name', 'Barangay Portal'),
            '{{barangay_name}}' => (string) data_get($settings, 'general.barangay_name', 'Barangay Hulong Duhat'),
            '{{full_name}}' => 'Juan Dela Cruz',
            '{{request_type}}' => 'Barangay Clearance',
            '{{request_id}}' => 'REQ-2026-0001',
            '{{date_submitted}}' => now('Asia/Manila')->format('F d, Y'),
            '{{complete_address}}' => '123 Main Street, Barangay Hulong Duhat, Malabon City',
            '{{contact_number}}' => '+63 912 345 6789',
            '{{email_address}}' => 'juan@example.com',
            '{{purpose_of_request}}' => 'Employment requirement',
            '{{remarks}}' => 'For release after verification',
        ];

        return strtr($template, $sample);
    }

    private function recordAudit(Request $request, array $oldSettings, array $newPayload): void
    {
        if (!Schema::hasTable('system_setting_audits')) {
            return;
        }

        $changedKeys = $this->getChangedKeys($oldSettings, $newPayload);

        if (empty($changedKeys)) {
            return;
        }

        DB::table('system_setting_audits')->insert([
            'admin_id' => auth('admin')->id(),
            'section' => 'system_settings',
            'changed_keys' => json_encode($changedKeys),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function getChangedKeys(array $oldSettings, array $newPayload, string $prefix = ''): array
    {
        $changed = [];

        foreach ($newPayload as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $changed = array_merge(
                    $changed,
                    $this->getChangedKeys($oldSettings, $value, $fullKey)
                );

                continue;
            }

            
            $oldValue = data_get($oldSettings, $fullKey);

            if ((string) $oldValue !== (string) $value) {
                $changed[] = $fullKey;
            }
        }

        return $changed;
    }

    private const DOCUMENT_TEMPLATES = [
        'clearance' => [
            'label' => 'Barangay Clearance',
            'filename' => 'Clearance_Template.docx',
        ],
        'indigency' => [
            'label' => 'Certificate of Indigency',
            'filename' => 'Indigency_Template.docx',
        ],
        'residency' => [
            'label' => 'Certificate of Residency',
            'filename' => 'Residency_Template.docx',
        ],
    ];

    private function resolveDocumentTemplate(string $type): array
    {
        if (! array_key_exists($type, self::DOCUMENT_TEMPLATES)) {
            abort(404);
        }

        return self::DOCUMENT_TEMPLATES[$type];
    }

    public function downloadDocumentTemplate(string $type)
    {
        $template = $this->resolveDocumentTemplate($type);

        $path = public_path('document_template/' . $template['filename']);

        if (! File::exists($path)) {
            return back()->withErrors([
                'template_file' => $template['label'] . ' file is missing.',
            ]);
        }

        return response()->download($path, $template['filename'], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function uploadDocumentTemplate(Request $request, string $type)
    {
        $template = $this->resolveDocumentTemplate($type);

        $request->validate([
            'template_file' => [
                'required',
                'file',
                'mimes:docx',
                'max:10240',
            ],
        ], [
            'template_file.required' => 'Please choose a DOCX file first.',
            'template_file.mimes' => 'Only .docx files are allowed.',
            'template_file.max' => 'The template must not be larger than 10MB.',
        ]);

        $directory = public_path('document_template');
        File::ensureDirectoryExists($directory);

        $targetPath = $directory . DIRECTORY_SEPARATOR . $template['filename'];

        if (File::exists($targetPath)) {
            $backupDirectory = $directory . DIRECTORY_SEPARATOR . 'backups';
            File::ensureDirectoryExists($backupDirectory);

            $backupName = pathinfo($template['filename'], PATHINFO_FILENAME)
                . '_backup_'
                . now()->format('Ymd_His')
                . '.docx';

            File::copy($targetPath, $backupDirectory . DIRECTORY_SEPARATOR . $backupName);
        }

        $request->file('template_file')->move($directory, $template['filename']);

        return back()->with(
            'success',
            $template['label'] . ' template uploaded successfully. The old file was backed up.'
        );
    }

    public function refreshSemaphoreStats(SemaphoreService $semaphore)
    {
        $settings = $this->settings->all();

        $apiKey = trim((string) data_get($settings, 'sms.api_key'));

        if ($apiKey === '') {
            return redirect()
                ->route('admin.settings.index')
                ->withErrors([
                    'sms.api_key' => 'Semaphore API key is missing.',
                ]);
        }

        $cacheKey = 'semaphore_account_balance_' . md5($apiKey);
        $lockKey = 'semaphore_account_sync_lock_' . md5($apiKey);

        if (! Cache::add($lockKey, now('Asia/Manila')->toDateTimeString(), 60)) {
            return redirect()
                ->route('admin.settings.index')
                ->withErrors([
                    'semaphore' => 'Please wait at least 60 seconds before syncing Semaphore credits again.',
                ]);
        }

        Cache::forget($cacheKey);

        $account = $semaphore->getAccount($apiKey);

        if (! data_get($account, 'success')) {
            return redirect()
                ->route('admin.settings.index')
                ->withErrors([
                    'semaphore' => data_get($account, 'message', 'Unable to sync Semaphore account.'),
                ]);
        }

        $accountData = data_get($account, 'data', []);

        $settings['sms']['remaining_credits'] = (float) data_get($accountData, 'credit_balance', 0);
        $settings['sms']['account_status'] = data_get($accountData, 'status', 'Unknown');
        $settings['sms']['account_name'] = data_get($accountData, 'account_name', 'Semaphore Account');
        $settings['sms']['last_synced_at'] = now('Asia/Manila')->toDateTimeString();

        $this->settings->update($settings);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Semaphore credits synced successfully.');
    }

    private function removeBlankSecretInputs(array $payload): array
    {
        $secretFields = [
            'sms.api_key',
            'email.password',
            'email.api_key',
            'ai.keys.gemini',
            'ai.keys.groq',
            'ai.keys.cloudflare',
            'security.recaptcha_secret_key',
        ];

        foreach ($secretFields as $field) {
            $value = data_get($payload, $field);

            if ($value === null || $value === '') {
                Arr::forget($payload, $field);
            }
        }

        return $payload;
    }

}