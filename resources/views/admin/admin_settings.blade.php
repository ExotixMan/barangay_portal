@extends('admin.layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'System Settings')
@section('page_icon', 'fas fa-sliders-h')

@section('header_actions')
<button type="submit" form="settingsForm" class="btn btn-primary" title="Save settings">
    <i class="fas fa-save"></i><span>Save Settings</span>
</button>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/system-settings.css') }}">

@endpush

@section('content')
            @php
                $smsSentToday = (int) data_get($settings, 'usage.sms_sent_today', 0);
                $smsRemaining = (float) data_get($settings, 'sms.remaining_credits', 0);
                $smsRemainingDisplay = number_format($smsRemaining, 2);
                $smsAccountName = data_get($settings, 'sms.account_name');
                $smsAccountStatus = data_get($settings, 'sms.account_status');
                $smsLastSyncedAt = data_get($settings, 'sms.last_synced_at');
                $smsDailyLimitRaw = (int) data_get($settings, 'sms.daily_limit', 0);
                $smsMonthlyLimitRaw = (int) data_get($settings, 'sms.monthly_limit', 0);
                $smsDailyLimit = max(1, $smsDailyLimitRaw);
                $smsMonthlyLimit = max(1, $smsMonthlyLimitRaw);
                $emailSentToday = (int) data_get($settings, 'usage.email_sent_today', 0);
                $aiRequestsToday = (int) data_get($settings, 'usage.ai_requests_today', 0);
                $smsUsagePercent = $smsDailyLimitRaw > 0
                    ? min(100, (int) round(($smsSentToday / $smsDailyLimit) * 100))
                    : 0;
                $smsRemainingPercent = $smsMonthlyLimitRaw > 0
                    ? min(100, (int) round((max($smsRemaining, 0) / $smsMonthlyLimit) * 100))
                    : ($smsRemaining > 0 ? 100 : 0);

                $provider = strtolower((string) old('ai.provider', data_get($settings, 'ai.provider', 'gemini')));
                $providerDisplay = $provider !== '' ? ucwords(str_replace(['_', '-'], ' ', $provider)) : 'Not set';
                $captchaEnabled = (bool) old('security.captcha_enabled', data_get($settings, 'security.captcha_enabled', true));
                $loginOtpEnabled = (bool) old('security.login_otp_enabled', data_get($settings, 'security.login_otp_enabled', false));
                $templatePreviewSafe = str_replace(["\r\n", "\r"], "\n", (string) $templatePreview);
                $barangayNameValue = (string) old('general.barangay_name', data_get($settings, 'general.barangay_name', 'Barangay'));

                $portalNameReady = trim((string) data_get($settings, 'general.portal_name', '')) !== '';
                $barangayNameReady = trim((string) data_get($settings, 'general.barangay_name', '')) !== '';
                $contactEmailValue = trim((string) data_get($settings, 'general.contact_email', ''));
                $contactEmailReady = $contactEmailValue === '' || filter_var($contactEmailValue, FILTER_VALIDATE_EMAIL);
                $generalHealth = (int) round((($portalNameReady ? 1 : 0) + ($barangayNameReady ? 1 : 0) + ($contactEmailReady ? 1 : 0)) / 3 * 100);

                $smsApiReady = trim((string) data_get($settings, 'sms.api_key', '')) !== '';
                $smsEndpointReady = trim((string) data_get($settings, 'sms.api_base_url', '')) !== '';
                $smsSenderReady = trim((string) data_get($settings, 'sms.sender_name', '')) !== '';
                $smsSetupHealth = (int) round((($smsApiReady ? 1 : 0) + ($smsEndpointReady ? 1 : 0) + ($smsSenderReady ? 1 : 0)) / 3 * 100);
                $smsCreditHealth = $smsRemaining > 0 ? $smsRemainingPercent : 0;

                $emailMailerReady = trim((string) data_get($settings, 'email.mailer', '')) !== '';
                $emailHostReady = trim((string) data_get($settings, 'email.host', '')) !== '';
                $emailPortReady = (int) data_get($settings, 'email.port', 0) > 0;
                $emailUsernameReady = trim((string) data_get($settings, 'email.username', '')) !== '';
                $emailPasswordReady = trim((string) data_get($settings, 'email.password', '')) !== '';
                $emailFromValue = trim((string) data_get($settings, 'email.from_address', ''));
                $emailFromReady = $emailFromValue !== '' && filter_var($emailFromValue, FILTER_VALIDATE_EMAIL);
                $emailHealth = (int) round((($emailMailerReady ? 1 : 0) + ($emailHostReady ? 1 : 0) + ($emailPortReady ? 1 : 0) + ($emailUsernameReady ? 1 : 0) + ($emailPasswordReady ? 1 : 0) + ($emailFromReady ? 1 : 0)) / 6 * 100);

                $aiKeyReady = trim((string) data_get($settings, 'ai.keys.' . $provider, '')) !== '';
                $cloudflareNeedsAccount = $provider === 'cloudflare';
                $cloudflareAccountReady = ! $cloudflareNeedsAccount || trim((string) data_get($settings, 'ai.cloudflare_account_id', '')) !== '';
                $aiHealth = ($aiKeyReady && $cloudflareAccountReady) ? 100 : 0;

                $sessionTimeout = (int) data_get($settings, 'security.session_timeout', 0);
                $sessionReady = $sessionTimeout >= 300 && $sessionTimeout <= 86400;
                $recaptchaKeysReady = ! $captchaEnabled || (
                    trim((string) data_get($settings, 'security.recaptcha_site_key', '')) !== '' &&
                    trim((string) data_get($settings, 'security.recaptcha_secret_key', '')) !== ''
                );
                $securityHealth = (int) round((($sessionReady ? 1 : 0) + ($captchaEnabled && $recaptchaKeysReady ? 1 : 0) + ($loginOtpEnabled ? 1 : 0)) / 3 * 100);
                $securityEnabledCount = ($captchaEnabled ? 1 : 0) + ($loginOtpEnabled ? 1 : 0);

                $configItems = [
                    ['label' => 'General identity', 'score' => $generalHealth, 'detail' => $portalNameReady && $barangayNameReady ? 'Portal and barangay name set' : 'Missing portal or barangay name'],
                    ['label' => 'SMS gateway', 'score' => $smsSetupHealth, 'detail' => $smsApiReady && $smsSenderReady ? 'API key and sender name set' : 'Check API key / sender name'],
                    ['label' => 'SMS credits', 'score' => $smsCreditHealth, 'detail' => $smsRemainingDisplay . ' credits left'],
                    ['label' => 'Email SMTP', 'score' => $emailHealth, 'detail' => $emailHealth >= 80 ? 'SMTP fields look complete' : 'SMTP fields incomplete'],
                    ['label' => 'AI provider', 'score' => $aiHealth, 'detail' => $aiHealth === 100 ? $providerDisplay . ' key ready' : 'Missing active provider key'],
                    ['label' => 'Security', 'score' => $securityHealth, 'detail' => $securityEnabledCount . '/2 security toggles on'],
                ];

                $configScore = (int) round(collect($configItems)->avg('score'));
                $configReadyCount = collect($configItems)->filter(fn ($item) => $item['score'] >= 80)->count();
                $configStatusText = $configScore >= 90 ? 'Healthy' : ($configScore >= 75 ? 'Needs review' : 'Action needed');
                $configStatusIcon = $configScore >= 90 ? 'fa-circle-check' : ($configScore >= 75 ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
                $configStatusClass = $configScore >= 90 ? 'is-good' : 'is-warning';

                $settingsSections = [
                    ['id' => 'general', 'label' => 'General', 'icon' => 'fas fa-sliders-h', 'desc' => 'Branding'],
                    ['id' => 'document', 'label' => 'Request Template', 'icon' => 'fas fa-file-signature', 'desc' => 'Certificates'],
                    ['id' => 'sms', 'label' => 'SMS & Credits', 'icon' => 'fas fa-sms', 'desc' => 'Gateway'],
                    ['id' => 'email', 'label' => 'Email', 'icon' => 'fas fa-envelope', 'desc' => 'SMTP'],
                    ['id' => 'ai', 'label' => 'AI Providers', 'icon' => 'fas fa-robot', 'desc' => 'Chatbot'],
                    ['id' => 'security', 'label' => 'Security', 'icon' => 'fas fa-shield-halved', 'desc' => 'Protection'],
                ];

                $placeholders = ['full_name', 'request_type', 'request_id', 'date_submitted', 'complete_address', 'contact_number', 'email_address', 'purpose_of_request', 'remarks'];
            @endphp

            <div class="settings-page-v2">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show settings-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show settings-alert" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')

                    <section class="settings-hero-v2">
                        <div class="hero-content">
                            <div class="hero-kicker"><i class="fas fa-gear me-2"></i>System Control Center</div>
                            <h1>System Settings</h1>
                            <p>Manage portal identity, document templates, messaging, AI providers, and security from one settings workspace.</p>
                            <div class="hero-actions">
                                <a href="#document" class="btn hero-btn btn-settings-secondary"><i class="fas fa-pen-to-square me-2"></i>Edit Template</a>
                                <button type="submit" class="btn hero-btn btn-settings-primary"><i class="far fa-floppy-disk me-2"></i>Save Changes</button>
                            </div>
                        </div>

                        <div class="hero-health-card {{ $configScore < 75 ? 'is-critical' : '' }}">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="hero-health-label">Setup Readiness</div>
                                    <div class="hero-score">{{ $configScore }}%</div>
                                    <div class="hero-health-summary">
                                        <i class="fas fa-list-check"></i>
                                        {{ $configReadyCount }} / {{ count($configItems) }} areas healthy
                                    </div>
                                </div>
                                <span class="hero-status-pill {{ $configStatusClass }}">
                                    <i class="fas {{ $configStatusIcon }} me-1"></i>{{ $configStatusText }}
                                </span>
                            </div>

                            <div class="hero-health-list">
                                @foreach($configItems as $item)
                                    <div class="health-metric-row">
                                        <span>{{ $item['label'] }}</span>
                                        <strong class="health-percent">{{ $item['score'] }}%</strong>
                                        <div class="thin-progress"><span style="width: {{ $item['score'] }}%"></span></div>
                                        <small>{{ $item['detail'] }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="settings-stat-grid">
                        <div class="settings-stat-card accent-sms">
                            <div class="stat-head">
                                <div>
                                    <div class="stat-label">SMS Today</div>
                                    <div class="stat-value">{{ number_format($smsSentToday) }}</div>
                                    <small>Daily limit: {{ number_format($smsDailyLimit) }}</small>
                                </div>
                                <div class="stat-icon"><i class="fas fa-sms"></i></div>
                            </div>
                            <div class="stat-footer">
                                <div class="stat-bar"><span style="width: {{ $smsUsagePercent }}%"></span></div>
                                <div class="stat-meta-row">
                                    <span>Usage</span>
                                    <span class="stat-pill">{{ $smsUsagePercent }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-stat-card accent-credits">
                            <div class="stat-head">
                                <div>
                                    <div class="stat-label">Credits Left</div>
                                    <div class="stat-value">{{ $smsRemainingDisplay }}</div>
                                    <small>{{ $smsRemainingPercent }}% monthly balance</small>
                                </div>
                                <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                            </div>
                            <div class="stat-footer">
                                <div class="stat-bar"><span style="width: {{ $smsRemainingPercent }}%"></span></div>
                                <div class="stat-meta-row">
                                    <span>Balance</span>
                                    <span class="stat-pill">{{ $smsRemainingPercent }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-stat-card accent-email">
                            <div class="stat-head">
                                <div>
                                    <div class="stat-label">Email Today</div>
                                    <div class="stat-value">{{ number_format($emailSentToday) }}</div>
                                    <small>SMTP activity</small>
                                </div>
                                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                            </div>
                            <div class="stat-footer">
                                <div class="stat-bar"><span style="width: {{ $emailHealth }}%"></span></div>
                                <div class="stat-meta-row">
                                    <span>SMTP setup</span>
                                    <span class="stat-pill">{{ $emailHealth >= 80 ? 'Ready' : 'Incomplete' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-stat-card accent-ai">
                            <div class="stat-head">
                                <div>
                                    <div class="stat-label">AI Provider</div>
                                    <div class="stat-value stat-provider">{{ $providerDisplay }}</div>
                                    <small>{{ number_format($aiRequestsToday) }} requests today</small>
                                </div>
                                <div class="stat-icon"><i class="fas fa-robot"></i></div>
                            </div>
                            <div class="stat-footer">
                                <div class="stat-bar"><span style="width: {{ $aiHealth }}%"></span></div>
                                <div class="stat-meta-row">
                                    <span>Provider status</span>
                                    <span class="stat-pill">{{ $aiHealth === 100 ? 'Connected' : 'Missing' }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="settings-workspace">
                        <aside class="settings-side-card">
                            <div class="side-head">
                                <div>
                                    <strong>Settings Menu</strong>
                                    <span>Jump to section</span>
                                </div>
                                <span class="count-pill">{{ count($settingsSections) }}</span>
                            </div>

                            <nav class="settings-side-nav" id="settingsSideNav">
                                @foreach($settingsSections as $section)
                                    <a href="#{{ $section['id'] }}" class="settings-side-link {{ $loop->first ? 'active' : '' }}">
                                        <i class="{{ $section['icon'] }}"></i>
                                        <span>{{ $section['label'] }}<span class="nav-dirty-dot" aria-hidden="true" title="Unsaved changes"></span></span>
                                        <small>{{ $section['desc'] }}</small>
                                    </a>
                                @endforeach
                            </nav>

                            <div class="settings-tip-card">
                                <div class="tip-icon"><i class="fas fa-circle-info"></i></div>
                                <div>
                                    <strong>Better workflow</strong>
                                    <p>Use the section menu to move quickly. A dot marks sections with unsaved changes.</p>
                                </div>
                            </div>
                        </aside>

                        <div class="settings-panel-stack">
                            <section id="general" class="settings-panel-card is-active" data-settings-panel>
                                <div class="panel-header">
                                    <div class="panel-title-wrap">
                                        <div class="panel-icon"><i class="fas fa-sliders-h"></i></div>
                                        <div>
                                            <h2>General</h2>
                                            <p>Portal identity, barangay contact details, and visual colors.</p>
                                        </div>
                                    </div>
                                    <div class="panel-actions">
                                        <span class="panel-badge">Core branding</span>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="form-card-grid two-cols">
                                        <div class="field-card-v2 @error('general.portal_name') has-error @enderror">
                                            <label for="general_portal_name">Portal Name</label>
                                            <input type="text" id="general_portal_name" name="general[portal_name]" value="{{ old('general.portal_name', data_get($settings, 'general.portal_name')) }}" class="form-control @error('general.portal_name') is-invalid @enderror" placeholder="BISIG Hulong Duhat">
                                            @error('general.portal_name')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('general.barangay_name') has-error @enderror">
                                            <label for="general_barangay_name">Barangay Name</label>
                                            <input type="text" id="general_barangay_name" name="general[barangay_name]" value="{{ old('general.barangay_name', data_get($settings, 'general.barangay_name')) }}" class="form-control @error('general.barangay_name') is-invalid @enderror" placeholder="Barangay Hulong Duhat">
                                            @error('general.barangay_name')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                            <small class="helper-text">Also appears on the certificate preview in the Request Template section.</small>
                                        </div>
                                        <div class="field-card-v2 @error('general.contact_email') has-error @enderror">
                                            <label for="general_contact_email">Contact Email</label>
                                            <div class="input-group clean-input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" id="general_contact_email" name="general[contact_email]" value="{{ old('general.contact_email', data_get($settings, 'general.contact_email')) }}" class="form-control @error('general.contact_email') is-invalid @enderror" placeholder="barangay@example.com">
                                            </div>
                                            @error('general.contact_email')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('general.contact_phone') has-error @enderror">
                                            <label for="general_contact_phone">Contact Phone</label>
                                            <div class="input-group clean-input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" id="general_contact_phone" name="general[contact_phone]" value="{{ old('general.contact_phone', data_get($settings, 'general.contact_phone')) }}" class="form-control @error('general.contact_phone') is-invalid @enderror" placeholder="09XX XXX XXXX">
                                            </div>
                                            @error('general.contact_phone')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('general.website') has-error @enderror">
                                            <label for="general_website">Website</label>
                                            <div class="input-group clean-input-group">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                <input type="url" id="general_website" name="general[website]" value="{{ old('general.website', data_get($settings, 'general.website')) }}" class="form-control @error('general.website') is-invalid @enderror" placeholder="https://example.com">
                                            </div>
                                            @error('general.website')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2">
                                            <label for="general_primary_color">Theme Colors</label>
                                            <div class="color-picker-grid">
                                                <div>
                                                    <input type="color" id="general_primary_color" name="general[primary_color]" value="{{ old('general.primary_color', data_get($settings, 'general.primary_color', '#d32f2f')) }}" class="form-control form-control-color settings-color-input">
                                                    <small>Primary</small>
                                                </div>
                                                <div>
                                                    <input type="color" id="general_secondary_color" name="general[secondary_color]" value="{{ old('general.secondary_color', data_get($settings, 'general.secondary_color', '#b71c1c')) }}" class="form-control form-control-color settings-color-input">
                                                    <small>Secondary</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-card-v2 full-span brand-preview-card">
                                            <label>Live Preview</label>
                                            <div class="brand-preview-row">
                                                <span class="brand-swatch" id="brandSwatchPrimary" title="Primary color"></span>
                                                <span class="brand-swatch" id="brandSwatchSecondary" title="Secondary color"></span>
                                                <button type="button" class="brand-preview-btn" id="brandPreviewBtn" tabindex="-1">Sample Button</button>
                                                <span class="brand-preview-hint">This is roughly how your chosen colors will look on public-facing pages.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="document" class="settings-panel-card" data-settings-panel>
                            @php
                                $documentTemplates = [
                                    'clearance' => [
                                        'title' => 'Barangay Clearance',
                                        'filename' => 'Clearance_Template.docx',
                                        'description' => 'Used when generating barangay clearance documents.',
                                        'icon' => 'fas fa-file-contract',
                                    ],
                                    'indigency' => [
                                        'title' => 'Certificate of Indigency',
                                        'filename' => 'Indigency_Template.docx',
                                        'description' => 'Used when generating indigency certificates.',
                                        'icon' => 'fas fa-file-invoice',
                                    ],
                                    'residency' => [
                                        'title' => 'Certificate of Residency',
                                        'filename' => 'Residency_Template.docx',
                                        'description' => 'Used when generating residency certificates.',
                                        'icon' => 'fas fa-house-user',
                                    ],
                                ];
                            @endphp

                            <div class="panel-header">
                                <div class="panel-title-wrap">
                                    <div class="panel-icon">
                                        <i class="fas fa-file-word"></i>
                                    </div>
                                    <div>
                                        <h2>Document Templates</h2>
                                        <p>Download the current DOCX file, edit it in Microsoft Word, then reupload it to replace the template format.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="panel-badge">DOCX Manager</span>
                                </div>
                            </div>

                            <div class="panel-body">
                                <div class="template-help-card mb-3">
                                    <div class="template-help-icon">
                                        <i class="fas fa-circle-info"></i>
                                    </div>
                                    <div>
                                        <strong>How to change a certificate format</strong>
                                        <p>
                                            Download the template, edit the layout in Word, keep the same placeholders used by your generator,
                                            then upload the edited DOCX file. The system will replace the correct file name automatically.
                                        </p>
                                    </div>
                                </div>

                                <div class="doc-template-grid">
                                    @foreach($documentTemplates as $key => $template)
                                        @php
                                            $templatePath = public_path('document_template/' . $template['filename']);
                                            $templateExists = file_exists($templatePath);
                                            $templateSize = $templateExists ? number_format(filesize($templatePath) / 1024, 1) . ' KB' : 'Missing';
                                            $templateModified = $templateExists ? date('M d, Y h:i A', filemtime($templatePath)) : 'File not found';
                                        @endphp

                                        <article class="doc-template-card">
                                            <div class="doc-template-top">
                                                <div class="doc-template-icon">
                                                    <i class="{{ $template['icon'] }}"></i>
                                                </div>

                                                <span class="doc-template-status {{ $templateExists ? 'is-ready' : 'is-missing' }}">
                                                    <i class="fas {{ $templateExists ? 'fa-circle-check' : 'fa-triangle-exclamation' }} me-1"></i>
                                                    {{ $templateExists ? 'Ready' : 'Missing' }}
                                                </span>
                                            </div>

                                            <div class="doc-template-body">
                                                <h3>{{ $template['title'] }}</h3>
                                                <p>{{ $template['description'] }}</p>

                                                <div class="doc-template-meta">
                                                    <div>
                                                        <span>File name</span>
                                                        <strong>{{ $template['filename'] }}</strong>
                                                    </div>
                                                    <div>
                                                        <span>Size</span>
                                                        <strong>{{ $templateSize }}</strong>
                                                    </div>
                                                    <div>
                                                        <span>Last updated</span>
                                                        <strong>{{ $templateModified }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="doc-template-actions">
                                                @if($templateExists)
                                                    <a href="{{ route('admin.settings.templates.download', $key) }}"
                                                    class="btn btn-outline-secondary w-100">
                                                        <i class="fas fa-download me-2"></i>
                                                        Download
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                                        <i class="fas fa-download me-2"></i>
                                                        Download
                                                    </button>
                                                @endif

                                                <label class="doc-upload-box" for="template_file_{{ $key }}">
                                                    <i class="fas fa-cloud-arrow-up"></i>
                                                    <span class="doc-upload-title">Choose edited DOCX</span>
                                                    <small id="file_name_{{ $key }}">No file selected</small>
                                                </label>

                                                <input
                                                    type="file"
                                                    id="template_file_{{ $key }}"
                                                    name="template_file"
                                                    class="doc-template-input visually-hidden"
                                                    accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                    form="templateUploadForm_{{ $key }}"
                                                    data-file-name-target="file_name_{{ $key }}"
                                                    required
                                                >

                                                <button type="submit"
                                                        class="btn btn-action w-100"
                                                        form="templateUploadForm_{{ $key }}">
                                                    <i class="fas fa-upload me-2"></i>
                                                    Upload Replacement
                                                </button>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                            <section id="sms" class="settings-panel-card" data-settings-panel>
                                <div class="panel-header">
                                    <div class="panel-title-wrap">
                                        <div class="panel-icon"><i class="fas fa-sms"></i></div>
                                        <div>
                                            <h2>SMS & Credits</h2>
                                            <p>Control the SMS gateway, sender name, credit tracking, and usage limits.</p>
                                        </div>
                                    </div>
                                    <div class="panel-actions">
                                        <span class="panel-badge">{{ $smsRemainingDisplay }} credits</span>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="usage-card-v2 mb-3">
                                        <div>
                                            <span>SMS usage today</span>
                                            <strong>{{ number_format($smsSentToday) }} / {{ number_format($smsDailyLimit) }}</strong>
                                        </div>
                                        <div class="usage-meter-v2"><span style="width: {{ $smsUsagePercent }}%"></span></div>
                                    </div>

                                    <div class="form-card-grid two-cols">
                                        <div class="field-card-v2">
                                            <label for="sms_provider">Provider</label>
                                            <select id="sms_provider" name="sms[provider]" class="form-select">
                                                <option value="semaphore" {{ old('sms.provider', data_get($settings, 'sms.provider')) === 'semaphore' ? 'selected' : '' }}>Semaphore</option>
                                                <option value="custom" {{ old('sms.provider', data_get($settings, 'sms.provider')) === 'custom' ? 'selected' : '' }}>Custom</option>
                                            </select>
                                        </div>
                                        <div class="field-card-v2 @error('sms.api_base_url') has-error @enderror">
                                            <label for="sms_api_base_url">API Endpoint</label>
                                            <input type="url" id="sms_api_base_url" name="sms[api_base_url]" value="{{ old('sms.api_base_url', data_get($settings, 'sms.api_base_url')) }}" class="form-control @error('sms.api_base_url') is-invalid @enderror" placeholder="https://api.semaphore.co/api/v4">
                                            @error('sms.api_base_url')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('sms.api_key') has-error @enderror">
                                            <label for="sms_api_key">API Key</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="sms_api_key" name="sms[api_key]" value="{{ old('sms.api_key', data_get($settings, 'sms.api_key')) }}" class="form-control @error('sms.api_key') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide SMS API key"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('sms.api_key')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('sms.sender_name') has-error @enderror">
                                            <label for="sms_sender_name">Sender Name</label>
                                            <input type="text" id="sms_sender_name" name="sms[sender_name]" value="{{ old('sms.sender_name', data_get($settings, 'sms.sender_name')) }}" class="form-control @error('sms.sender_name') is-invalid @enderror" placeholder="HULONGDUHAT">
                                            @error('sms.sender_name')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('sms.daily_limit') has-error @enderror">
                                            <label for="sms_daily_limit">Daily Limit</label>
                                            <input type="number" id="sms_daily_limit" name="sms[daily_limit]" min="0" value="{{ old('sms.daily_limit', data_get($settings, 'sms.daily_limit')) }}" class="form-control @error('sms.daily_limit') is-invalid @enderror">
                                            @error('sms.daily_limit')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('sms.monthly_limit') has-error @enderror">
                                            <label for="sms_monthly_limit">Monthly Limit</label>
                                            <input type="number" id="sms_monthly_limit" name="sms[monthly_limit]" min="0" value="{{ old('sms.monthly_limit', data_get($settings, 'sms.monthly_limit')) }}" class="form-control @error('sms.monthly_limit') is-invalid @enderror">
                                            @error('sms.monthly_limit')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 full-span">
                                            <label>Semaphore Credits</label>

                                            <div class="sms-credit-sync-card">
                                                <div class="sms-credit-main">
                                                    <div class="sms-credit-icon">
                                                        <i class="fas fa-wallet"></i>
                                                    </div>

                                                    <div>
                                                        <span class="sms-credit-label">Credits Left</span>
                                                        <strong class="sms-credit-value">{{ $smsRemainingDisplay }}</strong>

                                                        <div class="sms-credit-meta">
                                                            @if($smsAccountName)
                                                                <span>
                                                                    <i class="fas fa-building me-1"></i>
                                                                    {{ $smsAccountName }}
                                                                </span>
                                                            @endif

                                                            @if($smsAccountStatus)
                                                                <span>
                                                                    <i class="fas fa-circle-check me-1"></i>
                                                                    Status: {{ $smsAccountStatus }}
                                                                </span>
                                                            @endif

                                                            <span>
                                                                <i class="fas fa-clock me-1"></i>
                                                                @if($smsLastSyncedAt)
                                                                    Last synced: {{ $smsLastSyncedAt }}
                                                                @else
                                                                    Not synced yet
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn btn-settings-secondary sms-sync-btn"
                                                    id="semaphoreSyncBtn"
                                                >
                                                    <i class="fas fa-rotate me-2"></i>
                                                    Sync Semaphore Credits
                                                </button>
                                            </div>

                                            <small class="helper-text">
                                                Credits are fetched from Semaphore. SMS sent today is still counted from your local notification logs.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="email" class="settings-panel-card" data-settings-panel>
                                <div class="panel-header">
                                    <div class="panel-title-wrap">
                                        <div class="panel-icon"><i class="fas fa-envelope"></i></div>
                                        <div>
                                            <h2>Email</h2>
                                            <p>Update SMTP transport, sender address, and display name.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="form-card-grid four-cols">
                                        <div class="field-card-v2 @error('email.mailer') has-error @enderror">
                                            <label for="email_mailer">Mailer</label>
                                            <input type="text" id="email_mailer" name="email[mailer]" value="{{ old('email.mailer', data_get($settings, 'email.mailer')) }}" class="form-control @error('email.mailer') is-invalid @enderror" placeholder="smtp">
                                            @error('email.mailer')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('email.host') has-error @enderror">
                                            <label for="email_host">SMTP Host</label>
                                            <input type="text" id="email_host" name="email[host]" value="{{ old('email.host', data_get($settings, 'email.host')) }}" class="form-control @error('email.host') is-invalid @enderror" placeholder="smtp.gmail.com">
                                            @error('email.host')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('email.port') has-error @enderror">
                                            <label for="email_port">Port</label>
                                            <input type="number" id="email_port" name="email[port]" value="{{ old('email.port', data_get($settings, 'email.port')) }}" class="form-control @error('email.port') is-invalid @enderror" placeholder="587">
                                            @error('email.port')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('email.encryption') has-error @enderror">
                                            <label for="email_encryption">Encryption</label>
                                            <input type="text" id="email_encryption" name="email[encryption]" value="{{ old('email.encryption', data_get($settings, 'email.encryption')) }}" class="form-control @error('email.encryption') is-invalid @enderror" placeholder="tls">
                                            @error('email.encryption')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 span-two @error('email.username') has-error @enderror">
                                            <label for="email_username">SMTP Username</label>
                                            <input type="text" id="email_username" name="email[username]" value="{{ old('email.username', data_get($settings, 'email.username')) }}" class="form-control @error('email.username') is-invalid @enderror" autocomplete="off">
                                            @error('email.username')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 span-two @error('email.password') has-error @enderror">
                                            <label for="email_password">SMTP Password</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="email_password" name="email[password]" value="{{ old('email.password', data_get($settings, 'email.password')) }}" class="form-control @error('email.password') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide SMTP password"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('email.password')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 span-two @error('email.from_address') has-error @enderror">
                                            <label for="email_from_address">From Address</label>
                                            <input type="email" id="email_from_address" name="email[from_address]" value="{{ old('email.from_address', data_get($settings, 'email.from_address')) }}" class="form-control @error('email.from_address') is-invalid @enderror" placeholder="no-reply@example.com">
                                            @error('email.from_address')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 span-two @error('email.from_name') has-error @enderror">
                                            <label for="email_from_name">From Name</label>
                                            <input type="text" id="email_from_name" name="email[from_name]" value="{{ old('email.from_name', data_get($settings, 'email.from_name')) }}" class="form-control @error('email.from_name') is-invalid @enderror" placeholder="BISIG Hulong Duhat">
                                            @error('email.from_name')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="ai" class="settings-panel-card" data-settings-panel>
                                <div class="panel-header">
                                    <div class="panel-title-wrap">
                                        <div class="panel-icon"><i class="fas fa-robot"></i></div>
                                        <div>
                                            <h2>AI Providers</h2>
                                            <p>Select chatbot providers and manage provider credentials.</p>
                                        </div>
                                    </div>
                                    <div class="panel-actions">
                                        <span class="panel-badge">{{ strtoupper($provider) }}</span>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="form-card-grid three-cols">
                                        <div class="field-card-v2">
                                            <label for="ai_provider">Default Provider</label>
                                            <select id="ai_provider" name="ai[provider]" class="form-select">
                                                <option value="gemini" {{ old('ai.provider', $provider) === 'gemini' ? 'selected' : '' }}>Gemini</option>
                                                <option value="groq" {{ old('ai.provider', $provider) === 'groq' ? 'selected' : '' }}>Groq</option>
                                                <option value="cloudflare" {{ old('ai.provider', $provider) === 'cloudflare' ? 'selected' : '' }}>Cloudflare</option>
                                            </select>
                                        </div>
                                        <div class="field-card-v2 span-two @error('ai.fallback_providers') has-error @enderror">
                                            <label for="ai_fallback_providers">Fallback Providers</label>
                                            <input type="text" id="ai_fallback_providers" name="ai[fallback_providers]" value="{{ old('ai.fallback_providers', data_get($settings, 'ai.fallback_providers')) }}" class="form-control @error('ai.fallback_providers') is-invalid @enderror" placeholder="gemini,groq,cloudflare">
                                            @error('ai.fallback_providers')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @else
                                                <small class="helper-text">Comma-separated, tried in order if the default provider fails.</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('ai.cloudflare_account_id') has-error @enderror">
                                            <label for="ai_cloudflare_account_id">Cloudflare Account ID</label>
                                            <input type="text" id="ai_cloudflare_account_id" name="ai[cloudflare_account_id]" value="{{ old('ai.cloudflare_account_id', data_get($settings, 'ai.cloudflare_account_id')) }}" class="form-control @error('ai.cloudflare_account_id') is-invalid @enderror" autocomplete="off">
                                            @error('ai.cloudflare_account_id')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('ai.keys.gemini') has-error @enderror">
                                            <label for="ai_keys_gemini">Gemini Key</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="ai_keys_gemini" name="ai[keys][gemini]" value="{{ old('ai.keys.gemini', data_get($settings, 'ai.keys.gemini')) }}" class="form-control @error('ai.keys.gemini') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide Gemini key"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('ai.keys.gemini')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('ai.keys.groq') has-error @enderror">
                                            <label for="ai_keys_groq">Groq Key</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="ai_keys_groq" name="ai[keys][groq]" value="{{ old('ai.keys.groq', data_get($settings, 'ai.keys.groq')) }}" class="form-control @error('ai.keys.groq') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide Groq key"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('ai.keys.groq')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 full-span @error('ai.keys.cloudflare') has-error @enderror">
                                            <label for="ai_keys_cloudflare">Cloudflare Token</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="ai_keys_cloudflare" name="ai[keys][cloudflare]" value="{{ old('ai.keys.cloudflare', data_get($settings, 'ai.keys.cloudflare')) }}" class="form-control @error('ai.keys.cloudflare') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide Cloudflare token"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('ai.keys.cloudflare')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="security" class="settings-panel-card" data-settings-panel>
                                <div class="panel-header">
                                    <div class="panel-title-wrap">
                                        <div class="panel-icon"><i class="fas fa-shield-halved"></i></div>
                                        <div>
                                            <h2>Security</h2>
                                            <p>Set session limits, captcha, OTP, and reCAPTCHA credentials.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="security-grid mb-3">
                                        <div class="field-card-v2 @error('security.session_timeout') has-error @enderror">
                                            <label for="security_session_timeout">Session Timeout</label>
                                            <div class="input-group clean-input-group has-end-text">
                                                <input type="number" id="security_session_timeout" min="300" max="86400" name="security[session_timeout]" value="{{ old('security.session_timeout', data_get($settings, 'security.session_timeout')) }}" class="form-control @error('security.session_timeout') is-invalid @enderror">
                                                <span class="input-group-text">seconds</span>
                                            </div>
                                            @error('security.session_timeout')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @else
                                                <small class="helper-text">Between 5 minutes (300s) and 24 hours (86400s).</small>
                                            @enderror
                                        </div>

                                        <div class="switch-card-v2">
                                            <div>
                                                <label for="security_captcha_enabled">Captcha</label>
                                                <p>Protect registration forms from automated submissions.</p>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input type="hidden" name="security[captcha_enabled]" value="0">
                                                <input class="form-check-input" type="checkbox" id="security_captcha_enabled" name="security[captcha_enabled]" value="1" {{ $captchaEnabled ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="switch-card-v2">
                                            <div>
                                                <label for="security_login_otp_enabled">Login OTP</label>
                                                <p>Require OTP verification when admins log in.</p>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input type="hidden" name="security[login_otp_enabled]" value="0">
                                                <input class="form-check-input" type="checkbox" id="security_login_otp_enabled" name="security[login_otp_enabled]" value="1" {{ $loginOtpEnabled ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-card-grid two-cols">
                                        <div class="field-card-v2 @error('security.recaptcha_site_key') has-error @enderror">
                                            <label for="security_recaptcha_site_key">reCAPTCHA Site Key</label>
                                            <input type="text" id="security_recaptcha_site_key" name="security[recaptcha_site_key]" value="{{ old('security.recaptcha_site_key', data_get($settings, 'security.recaptcha_site_key')) }}" class="form-control @error('security.recaptcha_site_key') is-invalid @enderror" autocomplete="off">
                                            @error('security.recaptcha_site_key')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="field-card-v2 @error('security.recaptcha_secret_key') has-error @enderror">
                                            <label for="security_recaptcha_secret_key">reCAPTCHA Secret Key</label>
                                            <div class="input-group secret-group">
                                                <input type="password" id="security_recaptcha_secret_key" name="security[recaptcha_secret_key]" value="{{ old('security.recaptcha_secret_key', data_get($settings, 'security.recaptcha_secret_key')) }}" class="form-control @error('security.recaptcha_secret_key') is-invalid @enderror" data-secret-field autocomplete="off">
                                                <button type="button" class="btn btn-outline-secondary toggle-secret" aria-label="Show or hide reCAPTCHA secret key"><i class="fas fa-eye"></i></button>
                                            </div>
                                            @error('security.recaptcha_secret_key')
                                                <small class="field-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="settings-savebar-v2" id="settingsSavebar">
                        <div aria-live="polite">
                            <strong id="savebarStatusText">General settings saved</strong>
                            <small id="savebarHelpText">Use the upload buttons inside Document Templates when replacing DOCX files.</small>
                        </div>
                        <div class="savebar-actions">
                            <a href="{{ route('admin.dashboard.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-action" id="saveSettingsBtn"><i class="fas fa-save me-2"></i>Save Settings</button>
                        </div>
                    </div>
                </form>
                @foreach(['clearance', 'indigency', 'residency'] as $templateType)
                    <form
                        id="templateUploadForm_{{ $templateType }}"
                        method="POST"
                        action="{{ route('admin.settings.templates.upload', $templateType) }}"
                        enctype="multipart/form-data"
                        class="d-none">
                        @csrf
                    </form>
                @endforeach
                <form
                    id="semaphoreSyncForm"
                    method="POST"
                    action="{{ route('admin.settings.semaphore.refresh') }}"
                    class="d-none"
                >
                    @csrf
                </form>
            </div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/admin/admin_settings.js') }}"></script>
@endpush
