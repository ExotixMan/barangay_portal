@extends('admin.layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'System Settings')
@section('page_icon', 'fas fa-sliders-h')

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
                            <p>Manage barangay identity, certificate templates, SMS credits, email transport, AI providers, and security controls in one cleaner admin workspace.</p>
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
                                    <p>Use the left menu to move between sections. Edited sections get a small dot, and the save bar below tracks everything until you save.</p>
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
<script>
        (function () {
            function autoDismissSettingsAlerts() {
                document.querySelectorAll('.settings-alert.alert-dismissible').forEach(function (alertEl) {
                    const isDanger = alertEl.classList.contains('alert-danger');
                    const delay = isDanger ? 8000 : 4500;

                    window.setTimeout(function () {
                        if (!document.body.contains(alertEl)) return;

                        if (window.bootstrap && window.bootstrap.Alert) {
                            window.bootstrap.Alert.getOrCreateInstance(alertEl).close();
                            return;
                        }

                        alertEl.classList.add('settings-alert-hiding');
                        window.setTimeout(function () {
                            if (alertEl.parentNode) {
                                alertEl.parentNode.removeChild(alertEl);
                            }
                        }, 500);
                    }, delay);
                });
            }

            autoDismissSettingsAlerts();

            const textarea = document.getElementById('requestTemplate');
            const preview = document.getElementById('templatePreview');
            const titleInput = document.getElementById('requestTitle');
            const footerInput = document.getElementById('requestFooter');
            const previewTitle = document.getElementById('templatePreviewTitle');
            const previewFooter = document.getElementById('templatePreviewFooter');
            const previewBarangay = document.getElementById('templatePreviewBarangay');
            const barangayInput = document.getElementById('general_barangay_name');
            const templateMeta = document.getElementById('templateMeta');
            const savebar = document.getElementById('settingsSavebar');
            const savebarStatusText = document.getElementById('savebarStatusText');
            const savebarHelpText = document.getElementById('savebarHelpText');
            const settingsForm = document.getElementById('settingsForm');
            const panels = Array.from(document.querySelectorAll('[data-settings-panel]'));
            const sideLinks = Array.from(document.querySelectorAll('.settings-side-link'));
            const nav = document.getElementById('settingsSideNav');
            const primaryColorInput = document.getElementById('general_primary_color');
            const secondaryColorInput = document.getElementById('general_secondary_color');
            const brandSwatchPrimary = document.getElementById('brandSwatchPrimary');
            const brandSwatchSecondary = document.getElementById('brandSwatchSecondary');
            const brandPreviewBtn = document.getElementById('brandPreviewBtn');

            const dirtyPanels = new Set();
            let isSubmitting = false;

            const brace = function (value) {
                return '{' + '{' + value + '}' + '}';
            };

            const sample = {};
            sample[brace('full_name')] = 'Juan Dela Cruz';
            sample[brace('request_type')] = 'Barangay Clearance';
            sample[brace('request_id')] = 'REQ-2026-0001';
            sample[brace('date_submitted')] = 'January 18, 2026';
            sample[brace('complete_address')] = '123 Main Street, Barangay Hulong Duhat, Malabon City';
            sample[brace('contact_number')] = '+63 912 345 6789';
            sample[brace('email_address')] = 'juan@example.com';
            sample[brace('purpose_of_request')] = 'Employment requirement';
            sample[brace('remarks')] = 'For release after verification';

            function renderTemplatePreview() {
                if (!textarea || !preview) return;

                let content = textarea.value || '';
                Object.entries(sample).forEach(function ([needle, replacement]) {
                    content = content.split(needle).join(replacement);
                });

                preview.textContent = content || 'Start typing your template body to see the live preview here.';

                if (previewTitle && titleInput) {
                    previewTitle.textContent = titleInput.value || 'Request Certificate';
                }

                if (previewFooter && footerInput) {
                    previewFooter.textContent = footerInput.value || '';
                }
            }

            function updateTemplateMeta() {
                if (!templateMeta || !textarea) return;
                const text = textarea.value || '';
                const trimmed = text.trim();
                const words = trimmed.length ? trimmed.split(/\s+/).length : 0;
                templateMeta.innerHTML = '<i class="fas fa-keyboard me-1"></i>' + words + ' words &middot; ' + text.length + ' characters';
            }

            function renderLetterhead() {
                if (previewBarangay) {
                    previewBarangay.textContent = (barangayInput && barangayInput.value) || 'Barangay';
                }
            }

            function renderBrandPreview() {
                if (!brandSwatchPrimary || !brandSwatchSecondary || !brandPreviewBtn) return;
                const primary = (primaryColorInput && primaryColorInput.value) || '#d32f2f';
                const secondary = (secondaryColorInput && secondaryColorInput.value) || '#b71c1c';
                brandSwatchPrimary.style.background = primary;
                brandSwatchSecondary.style.background = secondary;
                brandPreviewBtn.style.background = 'linear-gradient(135deg, ' + primary + ', ' + secondary + ')';
            }

            function updateNavDirtyDots() {
                sideLinks.forEach(function (link) {
                    const id = (link.getAttribute('href') || '').replace('#', '');
                    const dot = link.querySelector('.nav-dirty-dot');
                    if (dot) dot.classList.toggle('is-visible', dirtyPanels.has(id));
                });
            }

            function updateSavebarCopy() {
                if (!savebarStatusText || !savebarHelpText) return;
                const count = dirtyPanels.size;
                if (count === 0) {
                    savebarStatusText.textContent = 'All settings saved';
                    savebarHelpText.textContent = "You're all set — make edits above if you need to change anything.";
                } else {
                    savebarStatusText.textContent = count + ' section' + (count > 1 ? 's' : '') + ' with unsaved changes';
                    savebarHelpText.textContent = 'Review your edits, then save to apply them across the portal.';
                }
            }

            function markDirty(event) {
                if (savebar) savebar.classList.add('is-dirty');
                const target = event && event.target ? event.target : null;
                const panel = target ? target.closest('[data-settings-panel]') : null;
                if (panel) {
                    dirtyPanels.add(panel.id);
                }
                updateNavDirtyDots();
                updateSavebarCopy();
            }

            function setActiveLink(panelId) {
                sideLinks.forEach(function (link) {
                    const isActive = link.getAttribute('href') === '#' + panelId;
                    link.classList.toggle('active', isActive);
                    link.setAttribute('aria-current', isActive ? 'page' : 'false');

                    if (isActive && nav && window.innerWidth <= 1199) {
                        link.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                });
            }

            function showPanel(panelId, shouldScroll) {
                const selectedPanel = document.getElementById(panelId) || panels[0];
                if (!selectedPanel) return;

                panels.forEach(function (panel) {
                    panel.classList.toggle('is-active', panel === selectedPanel);
                });

                setActiveLink(selectedPanel.id);

                if (shouldScroll) {
                    selectedPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            function openFromHash(hash, shouldScroll) {
                const panelId = (hash || '').replace('#', '');
                if (!panelId) return false;

                const panel = document.getElementById(panelId);
                if (!panel || !panel.matches('[data-settings-panel]')) return false;

                showPanel(panelId, shouldScroll);
                return true;
            }

            sideLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    const hash = link.getAttribute('href');

                    if (!openFromHash(hash, true)) return;

                    event.preventDefault();
                    history.replaceState(null, '', hash);
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(function (link) {
                if (link.classList.contains('settings-side-link')) return;

                link.addEventListener('click', function (event) {
                    const hash = link.getAttribute('href');

                    if (!openFromHash(hash, true)) return;

                    event.preventDefault();
                    history.replaceState(null, '', hash);
                });
            });

            // On load, prioritize showing the section that has a validation error (if any),
            // since only one section is visible at a time and a hidden error is easy to miss.
            const firstInvalidField = document.querySelector('#settingsForm .is-invalid');
            if (firstInvalidField) {
                const panelWithError = firstInvalidField.closest('[data-settings-panel]');
                showPanel(panelWithError ? panelWithError.id : (panels[0] ? panels[0].id : ''), true);
            } else if (!openFromHash(window.location.hash, false) && panels[0]) {
                showPanel(panels[0].id, false);
            }

            window.addEventListener('hashchange', function () {
                openFromHash(window.location.hash, true);
            });

            if (textarea) {
                textarea.addEventListener('input', renderTemplatePreview);
                textarea.addEventListener('input', updateTemplateMeta);
            }
            if (titleInput) titleInput.addEventListener('input', renderTemplatePreview);
            if (footerInput) footerInput.addEventListener('input', renderTemplatePreview);
            if (barangayInput) barangayInput.addEventListener('input', renderLetterhead);
            if (primaryColorInput) primaryColorInput.addEventListener('input', renderBrandPreview);
            if (secondaryColorInput) secondaryColorInput.addEventListener('input', renderBrandPreview);

            renderTemplatePreview();
            updateTemplateMeta();
            renderLetterhead();
            renderBrandPreview();
            updateSavebarCopy();

            document.querySelectorAll('.placeholder-chip-v2[data-placeholder]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!textarea) return;

                    const insertText = button.getAttribute('data-placeholder') || '';
                    const start = textarea.selectionStart || 0;
                    const end = textarea.selectionEnd || 0;
                    const value = textarea.value || '';

                    textarea.value = value.substring(0, start) + insertText + value.substring(end);
                    textarea.focus();
                    textarea.setSelectionRange(start + insertText.length, start + insertText.length);
                    renderTemplatePreview();
                    updateTemplateMeta();
                    markDirty({ target: textarea });
                });
            });

            document.querySelectorAll('.toggle-secret').forEach(function (button) {
                button.addEventListener('click', function () {
                    const group = button.closest('.secret-group');
                    const input = group ? group.querySelector('[data-secret-field]') : null;
                    const icon = button.querySelector('i');

                    if (!input) return;

                    const shouldShow = input.type === 'password';
                    input.type = shouldShow ? 'text' : 'password';

                    if (icon) {
                        icon.classList.toggle('fa-eye', !shouldShow);
                        icon.classList.toggle('fa-eye-slash', shouldShow);
                    }
                });
            });


            /* Strong validation layer — keeps the current UI and reuses existing error styles. */
            const allowedAiProviders = ['gemini', 'groq', 'cloudflare'];

            function byId(id) {
                return document.getElementById(id);
            }

            function cleanValue(field) {
                return field ? String(field.value || '').trim() : '';
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function validationCard(field) {
                return field ? field.closest('.field-card-v2, .switch-card-v2, .template-editor-card, .doc-template-card') : null;
            }

            function clientErrorSelector(field) {
                return '[data-client-validation-for="' + CSS.escape(field.id || field.name || '') + '"]';
            }

            function clearClientError(field) {
                if (!field) return;

                const key = field.id || field.name || '';
                if (key) {
                    document.querySelectorAll('[data-client-validation-for="' + CSS.escape(key) + '"]').forEach(function (errorEl) {
                        errorEl.remove();
                    });
                }

                if (field.dataset.clientInvalid === '1') {
                    field.classList.remove('is-invalid');
                    delete field.dataset.clientInvalid;
                }

                const card = validationCard(field);
                if (card && !card.querySelector('[data-client-validation]') && !card.querySelector('.is-invalid:not([data-client-invalid])')) {
                    card.classList.remove('has-error');
                }
            }

            function setClientError(field, message) {
                if (!field || !message) return;

                clearClientError(field);

                field.dataset.clientInvalid = '1';
                field.classList.add('is-invalid');

                const card = validationCard(field);
                if (card) {
                    card.classList.add('has-error');
                }

                const key = field.id || field.name || '';
                const errorEl = document.createElement('small');
                errorEl.className = 'field-error';
                errorEl.setAttribute('data-client-validation', 'true');
                errorEl.setAttribute('data-client-validation-for', key);
                errorEl.innerHTML = '<i class="fas fa-circle-exclamation"></i>' + escapeHtml(message);

                const docUploadBox = card ? card.querySelector('.doc-upload-box') : null;
                const insertTarget = field.closest('.input-group') || docUploadBox || field;
                insertTarget.insertAdjacentElement('afterend', errorEl);
            }

            function resetClientValidation(form) {
                if (!form) return;
                form.querySelectorAll('[data-client-validation]').forEach(function (errorEl) {
                    errorEl.remove();
                });
                form.querySelectorAll('[data-client-invalid="1"]').forEach(function (field) {
                    field.classList.remove('is-invalid');
                    delete field.dataset.clientInvalid;
                });
                form.querySelectorAll('.has-error').forEach(function (card) {
                    if (!card.querySelector('.is-invalid:not([data-client-invalid])')) {
                        card.classList.remove('has-error');
                    }
                });

                const summary = document.getElementById('clientValidationSummary');
                if (summary) {
                    summary.remove();
                }
            }

            function showValidationSummary(messages) {
                const existing = document.getElementById('clientValidationSummary');
                if (existing) existing.remove();

                if (!settingsForm || !messages.length) return;

                const summary = document.createElement('div');
                summary.id = 'clientValidationSummary';
                summary.className = 'alert alert-danger settings-alert';
                summary.setAttribute('role', 'alert');

                const visibleMessages = messages.slice(0, 8).map(function (message) {
                    return '<li>' + escapeHtml(message) + '</li>';
                }).join('');

                const extraCount = messages.length > 8
                    ? '<li>And ' + (messages.length - 8) + ' more validation issue' + (messages.length - 8 > 1 ? 's' : '') + '.</li>'
                    : '';

                summary.innerHTML =
                    '<strong><i class="fas fa-shield-halved me-2"></i>Please fix these settings first:</strong>' +
                    '<ul class="mb-0 mt-2 ps-3">' + visibleMessages + extraCount + '</ul>';

                settingsForm.insertAdjacentElement('beforebegin', summary);
            }

            function addError(errors, field, message) {
                if (!field || !message) return;
                errors.push(message);
                setClientError(field, message);
            }

            function hasUnsafeText(value) {
                return /[<>]/.test(value) || /[\u0000-\u001F\u007F]/.test(value);
            }

            function validatePlainText(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = String(field.value || '');
                const trimmed = value.trim();

                if (options.required && trimmed === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (trimmed === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== trimmed) {
                    addError(errors, field, label + ' must not start or end with spaces.');
                    return false;
                }

                if (hasUnsafeText(trimmed)) {
                    addError(errors, field, label + ' contains unsafe characters.');
                    return false;
                }

                if (options.min && trimmed.length < options.min) {
                    addError(errors, field, label + ' must be at least ' + options.min + ' characters.');
                    return false;
                }

                if (options.max && trimmed.length > options.max) {
                    addError(errors, field, label + ' must not exceed ' + options.max + ' characters.');
                    return false;
                }

                if (options.pattern && !options.pattern.test(trimmed)) {
                    addError(errors, field, options.patternMessage || (label + ' format is invalid.'));
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateEmailField(errors, id, label, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== field.value || hasUnsafeText(value) || !emailPattern.test(value)) {
                    addError(errors, field, 'Enter a valid ' + label.toLowerCase() + '.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateUrlField(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = cleanValue(field);

                if (options.required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                try {
                    const url = new URL(value);

                    if (!['http:', 'https:'].includes(url.protocol)) {
                        addError(errors, field, label + ' must start with http:// or https://.');
                        return false;
                    }

                    if (options.httpsOnly && url.protocol !== 'https:') {
                        addError(errors, field, label + ' must use https:// for security.');
                        return false;
                    }

                    if (!url.hostname || /\s/.test(value) || hasUnsafeText(value)) {
                        addError(errors, field, label + ' is not a valid URL.');
                        return false;
                    }

                    clearClientError(field);
                    return true;
                } catch (error) {
                    addError(errors, field, label + ' is not a valid URL.');
                    return false;
                }
            }

            function validatePhoneField(errors, id, label) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                const digits = value.replace(/\D/g, '');
                const allowedFormat = /^[+0-9().\-\s]+$/.test(value);
                const looksLikePhMobile = /^09\d{9}$/.test(value.replace(/\s|-/g, '')) || /^\+639\d{9}$/.test(value.replace(/\s|-/g, ''));
                const validLength = digits.length >= 7 && digits.length <= 15;
                const repeatedOnly = /^(\d)\1+$/.test(digits);

                if (!allowedFormat || !validLength || repeatedOnly) {
                    addError(errors, field, label + ' must be a valid phone number.');
                    return false;
                }

                if ((digits.startsWith('09') || digits.startsWith('639')) && !looksLikePhMobile) {
                    addError(errors, field, label + ' must use a valid PH mobile format like 09XXXXXXXXX or +639XXXXXXXXX.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateColorField(errors, id, label) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                if (!/^#[0-9a-fA-F]{6}$/.test(value)) {
                    addError(errors, field, label + ' must be a valid hex color.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateIntegerField(errors, id, label, min, max, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return null;
                }

                if (value === '') {
                    clearClientError(field);
                    return null;
                }

                if (!/^\d+$/.test(value)) {
                    addError(errors, field, label + ' must be a whole number.');
                    return null;
                }

                const numberValue = Number(value);
                if (!Number.isSafeInteger(numberValue) || numberValue < min || numberValue > max) {
                    addError(errors, field, label + ' must be between ' + min + ' and ' + max + '.');
                    return null;
                }

                clearClientError(field);
                return numberValue;
            }

            function validateHostField(errors, id, label, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                const hostnamePattern = /^(localhost|(?:(?:25[0-5]|2[0-4]\d|1?\d?\d)(?:\.|$)){4}|(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,})$/;

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                if (/^https?:\/\//i.test(value) || /[\/\s<>]/.test(value) || value.length > 253 || !hostnamePattern.test(value)) {
                    addError(errors, field, label + ' must be a valid host only, for example smtp.gmail.com.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateSecretField(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = String(field.value || '');
                const trimmed = value.trim();

                if (options.required && trimmed === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (trimmed === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== trimmed || /\s/.test(trimmed)) {
                    addError(errors, field, label + ' must not contain spaces.');
                    return false;
                }

                if (options.min && trimmed.length < options.min) {
                    addError(errors, field, label + ' looks too short.');
                    return false;
                }

                if (options.max && trimmed.length > options.max) {
                    addError(errors, field, label + ' is too long.');
                    return false;
                }

                if (options.pattern && !options.pattern.test(trimmed)) {
                    addError(errors, field, options.patternMessage || (label + ' format is invalid.'));
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function parseFallbackProviders(field) {
                const value = cleanValue(field);
                if (value === '') return [];

                return value.split(',')
                    .map(function (item) { return item.trim().toLowerCase(); })
                    .filter(Boolean);
            }

            function validateSettingsForm() {
                if (!settingsForm) return true;

                settingsForm.setAttribute('novalidate', 'novalidate');
                resetClientValidation(settingsForm);

                const errors = [];

                validatePlainText(errors, 'general_portal_name', 'Portal name', {
                    required: true,
                    min: 3,
                    max: 80,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'Portal name may only contain letters, numbers, spaces, and common punctuation.'
                });
                validatePlainText(errors, 'general_barangay_name', 'Barangay name', {
                    required: true,
                    min: 3,
                    max: 100,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'Barangay name may only contain letters, numbers, spaces, and common punctuation.'
                });
                validateEmailField(errors, 'general_contact_email', 'Contact email', false);
                validatePhoneField(errors, 'general_contact_phone', 'Contact phone');
                validateUrlField(errors, 'general_website', 'Website', { required: false, httpsOnly: false });
                validateColorField(errors, 'general_primary_color', 'Primary color');
                validateColorField(errors, 'general_secondary_color', 'Secondary color');

                const smsProvider = byId('sms_provider');
                if (smsProvider && !['semaphore'].includes(cleanValue(smsProvider).toLowerCase())) {
                    addError(errors, smsProvider, 'SMS provider must be Semaphore.');
                }

                validateUrlField(errors, 'sms_api_base_url', 'SMS API endpoint', { required: true, httpsOnly: true });
                validateSecretField(errors, 'sms_api_key', 'SMS API key', { required: true, min: 20, max: 255 });
                validatePlainText(errors, 'sms_sender_name', 'SMS sender name', {
                    required: true,
                    min: 3,
                    max: 11,
                    pattern: /^[A-Z0-9]+$/,
                    patternMessage: 'SMS sender name must be 3 to 11 uppercase letters or numbers only.'
                });

                const smsDailyLimit = validateIntegerField(errors, 'sms_daily_limit', 'SMS daily limit', 0, 100000, true);
                const smsMonthlyLimit = validateIntegerField(errors, 'sms_monthly_limit', 'SMS monthly limit', 0, 1000000, true);
                if (smsDailyLimit !== null && smsMonthlyLimit !== null && smsMonthlyLimit > 0 && smsDailyLimit > smsMonthlyLimit) {
                    addError(errors, byId('sms_daily_limit'), 'SMS daily limit cannot be greater than the monthly limit.');
                }

                validatePlainText(errors, 'email_mailer', 'Mailer', {
                    required: true,
                    min: 3,
                    max: 20,
                    pattern: /^(smtp|sendmail|log|array)$/i,
                    patternMessage: 'Mailer must be smtp, sendmail, log, or array.'
                });
                validateHostField(errors, 'email_host', 'SMTP host', true);
                validateIntegerField(errors, 'email_port', 'SMTP port', 1, 65535, true);
                validatePlainText(errors, 'email_encryption', 'Email encryption', {
                    required: true,
                    max: 10,
                    pattern: /^(tls|ssl|starttls|none)$/i,
                    patternMessage: 'Email encryption must be tls, ssl, starttls, or none.'
                });
                validatePlainText(errors, 'email_username', 'SMTP username', {
                    required: true,
                    min: 3,
                    max: 255,
                    pattern: /^[^\s<>]+$/,
                    patternMessage: 'SMTP username must not contain spaces or unsafe characters.'
                });
                validateSecretField(errors, 'email_password', 'SMTP password', { required: true, min: 8, max: 255 });
                validateEmailField(errors, 'email_from_address', 'From address', true);
                validatePlainText(errors, 'email_from_name', 'From name', {
                    required: true,
                    min: 2,
                    max: 80,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'From name may only contain letters, numbers, spaces, and common punctuation.'
                });

                const aiProviderField = byId('ai_provider');
                const aiProvider = aiProviderField ? cleanValue(aiProviderField).toLowerCase() : '';
                if (aiProviderField && !allowedAiProviders.includes(aiProvider)) {
                    addError(errors, aiProviderField, 'Default AI provider is invalid.');
                }

                const fallbackField = byId('ai_fallback_providers');
                const fallbacks = fallbackField ? parseFallbackProviders(fallbackField) : [];
                if (fallbackField) {
                    const invalidFallbacks = fallbacks.filter(function (providerName) {
                        return !allowedAiProviders.includes(providerName);
                    });
                    const duplicatedFallbacks = fallbacks.filter(function (providerName, index) {
                        return fallbacks.indexOf(providerName) !== index;
                    });

                    if (invalidFallbacks.length) {
                        addError(errors, fallbackField, 'Fallback providers can only be gemini, groq, or cloudflare.');
                    } else if (duplicatedFallbacks.length) {
                        addError(errors, fallbackField, 'Fallback providers must not contain duplicates.');
                    } else {
                        clearClientError(fallbackField);
                    }
                }

                const activeKeyMap = {
                    gemini: ['ai_keys_gemini', 'Gemini key'],
                    groq: ['ai_keys_groq', 'Groq key'],
                    cloudflare: ['ai_keys_cloudflare', 'Cloudflare token']
                };

                Object.keys(activeKeyMap).forEach(function (providerName) {
                    const rule = activeKeyMap[providerName];
                    const isActive = aiProvider === providerName;
                    const isFallback = fallbacks.includes(providerName);
                    validateSecretField(errors, rule[0], rule[1], {
                        required: isActive || isFallback,
                        min: 10,
                        max: 500
                    });
                });

                const needsCloudflareAccount = aiProvider === 'cloudflare' || fallbacks.includes('cloudflare') || cleanValue(byId('ai_keys_cloudflare')) !== '';
                validatePlainText(errors, 'ai_cloudflare_account_id', 'Cloudflare Account ID', {
                    required: needsCloudflareAccount,
                    min: 32,
                    max: 32,
                    pattern: /^[a-fA-F0-9]{32}$/,
                    patternMessage: 'Cloudflare Account ID must be a 32-character hexadecimal ID.'
                });

                validateIntegerField(errors, 'security_session_timeout', 'Session timeout', 300, 86400, true);

                const captchaEnabledField = byId('security_captcha_enabled');
                const captchaEnabled = captchaEnabledField && captchaEnabledField.checked;
                validateSecretField(errors, 'security_recaptcha_site_key', 'reCAPTCHA site key', {
                    required: captchaEnabled,
                    min: 20,
                    max: 120,
                    pattern: /^[A-Za-z0-9_-]+$/,
                    patternMessage: 'reCAPTCHA site key must only contain letters, numbers, underscores, or hyphens.'
                });
                validateSecretField(errors, 'security_recaptcha_secret_key', 'reCAPTCHA secret key', {
                    required: captchaEnabled,
                    min: 20,
                    max: 120,
                    pattern: /^[A-Za-z0-9_-]+$/,
                    patternMessage: 'reCAPTCHA secret key must only contain letters, numbers, underscores, or hyphens.'
                });

                if (errors.length) {
                    showValidationSummary(errors);

                    const firstInvalid = settingsForm.querySelector('[data-client-invalid="1"]');
                    if (firstInvalid) {
                        const panelWithError = firstInvalid.closest('[data-settings-panel]');
                        if (panelWithError) {
                            showPanel(panelWithError.id, true);
                        } else {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        window.setTimeout(function () {
                            if (typeof firstInvalid.focus === 'function') {
                                firstInvalid.focus({ preventScroll: true });
                            }
                        }, 220);
                    }

                    if (savebar) {
                        savebar.classList.add('is-dirty');
                    }
                    if (savebarStatusText) {
                        savebarStatusText.textContent = 'Validation blocked saving';
                    }
                    if (savebarHelpText) {
                        savebarHelpText.textContent = 'Fix the highlighted fields first, then save again.';
                    }

                    return false;
                }

                return true;
            }

            function validateDocxUploadForm(form) {
                if (!form) return true;

                form.setAttribute('novalidate', 'novalidate');

                const input = document.querySelector('input[type="file"][form="' + CSS.escape(form.id) + '"]');
                if (!input) return true;

                clearClientError(input);

                const errors = [];
                const file = input.files && input.files.length ? input.files[0] : null;

                if (!file) {
                    addError(errors, input, 'Please choose a DOCX template before uploading.');
                    return false;
                }

                const fileName = file.name || '';
                const fileNameLower = fileName.toLowerCase();
                const validMimeTypes = [
                    '',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!fileNameLower.endsWith('.docx') || !validMimeTypes.includes(file.type)) {
                    addError(errors, input, 'Template file must be a valid .docx document.');
                    return false;
                }

                if (file.size > 5 * 1024 * 1024) {
                    addError(errors, input, 'Template file must not exceed 5 MB.');
                    return false;
                }

                if (/[<>:"/\\|?*\u0000-\u001F]/.test(fileName)) {
                    addError(errors, input, 'Template filename contains invalid characters.');
                    return false;
                }

                clearClientError(input);
                return true;
            }

            if (settingsForm) {
                settingsForm.setAttribute('novalidate', 'novalidate');
            }

            document.querySelectorAll('#settingsForm input, #settingsForm select, #settingsForm textarea').forEach(function (field) {
                field.addEventListener('change', markDirty);
                field.addEventListener('input', markDirty);
            });

            if (settingsForm) {
                settingsForm.addEventListener('submit', function (event) {
                    if (!validateSettingsForm()) {
                        event.preventDefault();
                        event.stopPropagation();
                        isSubmitting = false;
                        return;
                    }

                    isSubmitting = true;
                });
            }


            document.querySelectorAll('form[id^="templateUploadForm_"]').forEach(function (uploadForm) {
                uploadForm.setAttribute('novalidate', 'novalidate');

                uploadForm.addEventListener('submit', function (event) {
                    if (!validateDocxUploadForm(uploadForm)) {
                        event.preventDefault();
                        event.stopPropagation();

                        const input = document.querySelector('input[type="file"][form="' + CSS.escape(uploadForm.id) + '"]');
                        if (input) {
                            const panelWithError = input.closest('[data-settings-panel]');
                            if (panelWithError) {
                                showPanel(panelWithError.id, true);
                            }
                        }
                    }
                });
            });

            window.addEventListener('beforeunload', function (event) {
                if (isSubmitting) return;
                if (savebar && savebar.classList.contains('is-dirty')) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });
        })();
        
        document.querySelectorAll('.doc-template-input').forEach((input) => {
            input.addEventListener('change', function () {
                const targetId = this.dataset.fileNameTarget;
                const target = document.getElementById(targetId);

                if (!target) return;

                if (this.files && this.files.length > 0) {
                    target.textContent = this.files[0].name;
                } else {
                    target.textContent = 'No file selected';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const syncBtn = document.getElementById('semaphoreSyncBtn');
            const syncForm = document.getElementById('semaphoreSyncForm');

            if (!syncBtn || !syncForm) {
                return;
            }

            syncBtn.addEventListener('click', function () {
                syncBtn.disabled = true;
                syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Syncing...';

                syncForm.submit();
            });
        });
    </script>
@endpush
