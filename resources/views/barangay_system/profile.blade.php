@php
    $pageTitle = 'My Profile';
    $activePage = 'profile';

    $profileCorrectionConcerns = collect();

    if (auth()->check()) {
        $profileCorrectionConcerns = \App\Models\ResidentProfileCorrectionConcern::query()
            ->where('resident_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();
    }

    $pendingProfileCorrection = $profileCorrectionConcerns->first(
        fn ($concern) => in_array(strtolower(trim((string) $concern->status)), \App\Models\ResidentProfileCorrectionConcern::OPEN_STATUSES, true)
    );

    $latestProfileCorrection = $profileCorrectionConcerns->first();
    $activeCorrection = $pendingProfileCorrection ?: $latestProfileCorrection;
    $pastProfileCorrections = $profileCorrectionConcerns->filter(
        fn ($concern) => !$activeCorrection || $concern->id !== $activeCorrection->id
    );

    $correctionStatusMeta = function (?string $status): array {
        $normalizedStatus = strtolower(trim((string) $status));

        return match ($normalizedStatus) {
            'pending', '' => [
                'class'   => 'warning',
                'icon'    => 'fa-clock',
                'label'   => 'Pending Review',
                'message' => 'Waiting for barangay review.',
            ],
            'reviewed', 'under_review', 'in_review', 'reviewing' => [
                'class'   => 'warning',
                'icon'    => 'fa-eye',
                'label'   => 'Under Review',
                'message' => 'Barangay staff is checking the details.',
            ],
            'resolved', 'corrected' => [
                'class'   => 'success',
                'icon'    => 'fa-check-circle',
                'label'   => 'Resolved / Approved',
                'message' => 'Approved. You may submit service requests again.',
            ],
            'rejected' => [
                'class'   => 'danger',
                'icon'    => 'fa-circle-xmark',
                'label'   => 'Rejected',
                'message' => 'Not approved. Review the remarks below or visit the barangay office.',
            ],
            default => [
                'class'   => 'neutral',
                'icon'    => 'fa-circle-info',
                'label'   => 'No Active Concern',
                'message' => 'You do not have a pending profile correction concern.',
            ],
        };
    };

    $activeCorrectionMeta = $correctionStatusMeta($activeCorrection?->status);

    $formatConcernId = function (?\App\Models\ResidentProfileCorrectionConcern $concern) {
        $year = $concern
            ? (optional($concern->created_at)->format('Y') ?: now()->format('Y'))
            : now()->format('Y');
        $id = $concern->id ?? 0;

        return 'PCC-' . $year . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    };

    $shouldAutoExpand = $activeCorrection
        && in_array(strtolower(trim((string) $activeCorrection->status)), [
            'pending',
            'reviewed',
            'under_review',
            'in_review',
            'reviewing',
            'rejected',
        ], true);

    $sessionAlerts = collect([
        'success_info'     => 'success',
        'success_account'  => 'success',
        'success_password' => 'success',
        'success_photo'    => 'success',
        'success_id'       => 'success',
        'success'          => 'success',
        'warning'          => 'warning',
        'error'            => 'danger',
    ])->map(fn ($type, $key) => ['message' => session($key), 'type' => $type])
      ->filter(fn ($alert) => filled($alert['message']));

    $alertIcon = fn ($type) => match ($type) {
        'success' => 'fa-check-circle',
        'warning' => 'fa-exclamation-triangle',
        default   => 'fa-exclamation-circle',
    };

    $hasSessionAlertAlready = $sessionAlerts->isNotEmpty();
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-user-circle"></i> {{ __('messages.blade_profile_blade_resident_account') }}</span>
                <h1>{{ __('messages.nav_my_profile') }}</h1>
                <p>{{ __('messages.blade_profile_blade_manage_your_personal_information_account_settings_reque') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-folder-open"></i> {{ __('messages.blade_contacts_blade_view_services') }}</a>
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        @foreach($sessionAlerts as $alert)
            <div class="bhd-alert {{ $alert['type'] }}">
                <i class="fas {{ $alertIcon($alert['type']) }}"></i> {{ $alert['message'] }}

                @if($alert['type'] === 'warning' && session('pending_correction_id'))
                    <div class="small mt-1">
                        Concern reference: {{ $formatConcernId($pendingProfileCorrection) }}
                    </div>
                @endif
            </div>
        @endforeach

        @php $checkerClass = $activeCorrectionMeta['class'] ?? 'neutral'; @endphp

        <article class="bhd-correction-card {{ $checkerClass }} {{ $shouldAutoExpand ? '' : 'is-collapsed' }}" id="profileCorrectionCard">
            <div class="bhd-correction-hero">
                <div class="bhd-correction-main">
                    <span class="bhd-correction-icon">
                        <i class="fas {{ $activeCorrectionMeta['icon'] }}"></i>
                    </span>

                    <div>
                        @if($activeCorrection)
                            <h2>
                                {{ str_replace('_', ' ', ucfirst($activeCorrection->field_name)) }} correction —
                                {{ $activeCorrectionMeta['label'] }}
                            </h2>
                        @else
                            <h2>{{ __('messages.blade_profile_blade_profile_correction_concern_status') }}</h2>
                        @endif
                        <p>{{ $activeCorrectionMeta['message'] }}</p>

                        @if($pendingProfileCorrection)
                            <span class="bhd-correction-lock-pill">
                                <i class="fas fa-lock"></i>
                                {{ $hasSessionAlertAlready ? 'Forms locked' : 'Request forms are locked while this is pending' }}
                            </span>
                        @endif
                    </div>
                </div>

                <button type="button"
                        class="bhd-correction-status bhd-correction-toggle {{ $checkerClass }}"
                        id="profileCorrectionToggle"
                        aria-expanded="{{ $shouldAutoExpand ? 'true' : 'false' }}"
                        aria-controls="profileCorrectionBody">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div class="bhd-correction-body" id="profileCorrectionBody" {{ $shouldAutoExpand ? '' : 'hidden' }}>
                @if(!$pendingProfileCorrection)
                    <div class="bhd-correction-note">
                        <i class="fas fa-unlock"></i>
                        <span>{{ __('messages.blade_profile_blade_no_pending_correction_is_blocking_your_service_request_') }}</span>
                    </div>
                @endif

                @if($latestProfileCorrection)
                    <div class="bhd-correction-details">
                        @php $latestCorrectionMeta = $correctionStatusMeta($latestProfileCorrection->status); @endphp
                        <div class="bhd-correction-box-head">
                            <span class="bhd-correction-status {{ $latestCorrectionMeta['class'] }}">
                                <i class="fas {{ $latestCorrectionMeta['icon'] }}"></i>
                                {{ $latestCorrectionMeta['label'] }}
                            </span>
                            <strong>{{ $formatConcernId($latestProfileCorrection) }}</strong>
                        </div>

                        <div class="bhd-correction-correcting">
                            <span class="fw-bold">You're correcting</span>
                            <strong>{{ str_replace('_', ' ', ucfirst($latestProfileCorrection->field_name)) }} on your {{ $latestProfileCorrection->service }}</strong>
                        </div>

                        <div class="bhd-correction-compare">
                            <div class="bhd-correction-compare-box">
                                <span>{{ __('messages.blade_profile_blade_current_value') }}</span>
                                <strong class="text-strike">{{ $latestProfileCorrection->current_value ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            </div>
                            <div class="bhd-correction-compare-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="bhd-correction-compare-box">
                                <span>{{ $latestProfileCorrection->resolved_value !== null ? 'Saved correction' : __('messages.blade_profile_blade_correct_value') }}</span>
                                <strong>{{ ($latestProfileCorrection->resolved_value ?? $latestProfileCorrection->correct_value) ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                                @if($latestProfileCorrection->resolved_value !== null && $latestProfileCorrection->resolved_value !== $latestProfileCorrection->correct_value)
                                    <small>Originally requested: {{ $latestProfileCorrection->correct_value }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="bhd-correction-note justify-content-between">
                            @if(!empty($latestProfileCorrection->message))
                                <div class="bhd-correction-note bhd-correction-remarks">
                                    <i class="fas fa-message"></i>
                                    <span>{{ $latestProfileCorrection->message }}</span>
                                </div>
                            @endif
                            <span>
                                {{ __('messages.blade_index_blade_submitted') }} {{ optional($latestProfileCorrection->created_at)->format('M d, Y') }}.
                                {{ $latestProfileCorrection->reviewed_at ? $latestProfileCorrection->reviewed_at->format('M d, Y h:i A') . ' reviewed' : __('messages.blade_fallback_not_yet') . ' reviewed' }} by barangay staff.
                            </span>
                        </div>

                        @if(strtolower(trim((string) $latestProfileCorrection->status)) === 'rejected')
                            <div class="bhd-correction-resubmit">
                                <div class="bhd-correction-resubmit-copy">
                                    <i class="fas fa-circle-info"></i>
                                    <span>This request wasn't approved. You can submit a new one with the correct details.</span>
                                </div>
                                <button type="button" class="bhd-btn bhd-btn-primary" data-bs-toggle="modal" data-bs-target="#profileCorrectionModal">
                                    <i class="fas fa-rotate-right"></i> Submit again
                                </button>
                            </div>
                        @endif

                        @if($pastProfileCorrections->isNotEmpty())
                            <button type="button"
                                    class="bhd-correction-history-toggle"
                                    id="profileCorrectionHistoryToggle"
                                    aria-expanded="false"
                                    aria-controls="profileCorrectionHistory">
                                <i class="fas fa-chevron-down"></i>
                                {{ $pastProfileCorrections->count() }} past concern{{ $pastProfileCorrections->count() > 1 ? 's' : '' }}
                            </button>

                            <div class="bhd-correction-history" id="profileCorrectionHistory">
                                @foreach($pastProfileCorrections as $concern)
                                    @php $historyMeta = $correctionStatusMeta($concern->status); @endphp
                                    <div class="bhd-correction-history-row">
                                        <div>
                                            <strong>{{ str_replace('_', ' ', ucfirst($concern->field_name)) }}</strong>
                                            <span>{{ optional($concern->created_at)->format('M d, Y') }} · {{ $formatConcernId($concern) }}</span>
                                        </div>
                                        <button type="button"
                                                class="bhd-correction-history-status {{ $historyMeta['class'] }}"
                                                data-history-target="profileCorrectionHistoryDetail{{ $concern->id }}"
                                                aria-expanded="false"
                                                aria-controls="profileCorrectionHistoryDetail{{ $concern->id }}">
                                            <i class="fas {{ $historyMeta['icon'] }}"></i>
                                            {{ $historyMeta['label'] }}
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="bhd-correction-history-detail" id="profileCorrectionHistoryDetail{{ $concern->id }}">
                                            <div>
                                                <strong>{{ __('messages.blade_profile_blade_current_value') }}:</strong>
                                                {{ $concern->current_value ?: __('messages.blade_announcement_blade_n_a') }}
                                                <strong>{{ __('messages.blade_profile_blade_correct_value') }}:</strong>
                                                {{ ($concern->resolved_value ?? $concern->correct_value) ?: __('messages.blade_announcement_blade_n_a') }}
                                                @if($concern->resolved_value !== null && $concern->resolved_value !== $concern->correct_value)
                                                    <span>Originally requested: {{ $concern->correct_value }}</span>
                                                @endif
                                            </div>
                                            <div class="remarks">
                                                <strong>Admin remarks:</strong>
                                                {{ $concern->remarks ?: 'No admin remarks were added.' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bhd-correction-note">
                        <i class="fas fa-circle-info"></i>
                        <span>{{ __('messages.blade_profile_blade_no_profile_correction_concern_has_been_submitted_yet') }}</span>
                    </div>
                @endif
            </div>
        </article>

        <div class="bhd-profile-layout">
            <aside class="bhd-profile-sidebar">
                <div class="bhd-avatar-wrap">
                    @if($resident->profile_photo)
                        <img src="{{ str_starts_with($resident->profile_photo, 'private/') ? route('secure.profile-photo') : asset($resident->profile_photo) }}" alt="{{ __('messages.blade_profile_blade_profile_photo') }}" class="bhd-avatar-img" loading="lazy">
                    @else
                        <div class="bhd-avatar-initials">
                            {{ strtoupper(substr($resident->firstname,0,1)) }}{{ strtoupper(substr($resident->lastname,0,1)) }}
                        </div>
                    @endif

                    <label for="quickPhotoInput" class="bhd-avatar-edit" title="{{ __('messages.blade_profile_blade_change_photo') }}" role="button" tabindex="0" aria-label="{{ __('messages.blade_profile_blade_change_photo') }}">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>

                @error('profile_photo')
                    <div class="text-danger small fw-bold text-center mb-2">{{ $message }}</div>
                @enderror

                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" id="quickPhotoForm">
                    @csrf
                    <input type="file" id="quickPhotoInput" name="profile_photo" accept="image/jpeg,image/png,image/webp" class="d-none">
                </form>

                <div class="bhd-profile-name">{{ $resident->firstname }} {{ $resident->lastname }}{{ $resident->suffix ? ', '.$resident->suffix : '' }}</div>
                <div class="bhd-profile-username">{{ $resident->username }}</div>

                @if($resident->email_verified_at)
                    <span class="bhd-verified-badge verified"><i class="fas fa-check-circle me-1"></i> {{ __('messages.profile_badge_verified') }}</span>
                @else
                    <span class="bhd-verified-badge unverified"><i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.profile_badge_unverified') }}</span>
                @endif

                <div class="bhd-profile-meta">
                    <div class="bhd-profile-stat"><span>{{ __('messages.profile_stat_member_since') }}</span><strong>{{ $resident->created_at->format('M Y') }}</strong></div>
                    <div class="bhd-profile-stat"><span>{{ __('messages.profile_stat_total_requests') }}</span><strong>{{ $totalRequests }}</strong></div>
                </div>

                <form method="POST" action="{{ route('logout.res') }}" class="bhd-profile-signout-form">@csrf
                    <button type="submit" class="bhd-btn bhd-btn-line w-100 mt-3 bhd-profile-signout-btn">
                        <i class="fas fa-right-from-bracket"></i> {{ __('messages.profile_signout_btn') }}
                    </button>
                </form>
            </aside>

            <div class="bhd-form-card bhd-profile-content">

                <nav class="bhd-profile-tabs" id="profileTabs" role="tablist" aria-label="{{ __('messages.nav_my_profile') }}">
                    <button class="bhd-profile-tab active" id="profile-tab-info" type="button" role="tab" aria-selected="true" aria-controls="tab-info" tabindex="0" data-tab="info"><i class="fas fa-user"></i> {{ __('messages.form_step_personal_info') }}</button>
                    <button class="bhd-profile-tab" id="profile-tab-account" type="button" role="tab" aria-selected="false" aria-controls="tab-account" tabindex="-1" data-tab="account"><i class="fas fa-cog"></i> {{ __('messages.blade_public_nav_blade_account') }}</button>
                    <button class="bhd-profile-tab" id="profile-tab-requests" type="button" role="tab" aria-selected="false" aria-controls="tab-requests" tabindex="-1" data-tab="requests"><i class="fas fa-file-alt"></i> {{ __('messages.blade_profile_blade_requests') }}</button>
                    <button class="bhd-profile-tab" id="profile-tab-classification" type="button" role="tab" aria-selected="false" aria-controls="tab-classification" tabindex="-1" data-tab="classification"><i class="fas fa-user-tag"></i> Classification</button>
                    <button class="bhd-profile-tab" id="profile-tab-valid-id" type="button" role="tab" aria-selected="false" aria-controls="tab-valid-id" tabindex="-1" data-tab="valid-id"><i class="fas fa-id-card"></i> {{ __('messages.blade_certificate_indigency_blade_valid_id') }}</button>
                </nav>

                <div class="bhd-profile-panel active" id="tab-info" role="tabpanel" aria-labelledby="profile-tab-info">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-user me-2"></i>{{ __('messages.register_step1_heading') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_identity_details_are_locked_for_certificate_security_on') }}</p>
                        </div>

                        @if(! $pendingProfileCorrection)
                            <button type="button" class="bhd-btn bhd-btn-soft" data-bs-toggle="modal" data-bs-target="#profileCorrectionModal">
                                <i class="fas fa-flag"></i> {{ __('messages.blade_profile_blade_request_correction') }}
                            </button>
                        @else
                            <button type="button" class="bhd-btn bhd-btn-line" disabled>
                                <i class="fas fa-lock"></i> {{ __('messages.blade_profile_blade_correction_pending') }}
                            </button>
                        @endif
                    </div>

                    <div class="bhd-sensitive-notice">
                        <i class="fas fa-shield-halved"></i>
                        <div>
                            <strong>{{ __('messages.blade_profile_blade_protected_profile_fields') }}</strong>
                            {{ __('messages.blade_profile_blade_name_birthdate_and_address_are_used_on_official_baranga') }}
                        </div>
                    </div>

                    @if($errors->hasAny(['contact', 'contact_otp']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach(array_merge($errors->get('contact'), $errors->get('contact_otp')) as $e)
                                    <div>{{ $e }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($errors->hasAny(['field_name','correct_value','message']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach(['field_name','correct_value','message'] as $field)
                                    @foreach($errors->get($field) as $e)
                                        <div>{{ $e }}</div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="bhd-section-label"><i class="fas fa-lock"></i> Identity details (read-only)</div>

                    <div class="bhd-locked-grid">
                        <div class="bhd-locked-box">
                            <span>{{ __('messages.register_first_name_label') }}</span>
                            <strong>{{ $resident->firstname ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>

                        <div class="bhd-locked-box">
                            <span>{{ __('messages.register_middle_name_label') }}</span>
                            <strong>{{ $resident->middlename ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>

                        <div class="bhd-locked-box">
                            <span>{{ __('messages.register_last_name_label') }}</span>
                            <strong>{{ $resident->lastname ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>

                        <div class="bhd-locked-box">
                            <span>{{ __('messages.register_suffix_label') }}</span>
                            <strong>{{ $resident->suffix ?: __('messages.blade_register_blade_none') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>

                        <div class="bhd-locked-box">
                            <span>{{ __('messages.blade_clearance_form_blade_birthdate') }}</span>
                            <strong>
                                @if($resident->birthdate)
                                    {{ optional($resident->birthdate)->format('M d, Y') ?? \Carbon\Carbon::parse($resident->birthdate)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </strong>
                            <i class="fas fa-lock"></i>
                        </div>

                        @if(!blank($resident->gender ?? null))
                            <div class="bhd-locked-box">
                                <span>{{ __('messages.form_review_label_gender') }}</span>
                                <strong>{{ ucfirst((string) $resident->gender) }}</strong>
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif

                        @if(!blank($resident->civil_status ?? null))
                            <div class="bhd-locked-box">
                                <span>{{ __('messages.blade_indigency_form_blade_civil_status') }}</span>
                                <strong>{{ ucfirst(str_replace('_', ' ', (string) $resident->civil_status)) }}</strong>
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif

                        @if(!blank($resident->place_of_birth ?? null))
                            <div class="bhd-locked-box">
                                <span>{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</span>
                                <strong>{{ $resident->place_of_birth }}</strong>
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif

                        <div class="bhd-locked-box" style="grid-column: 1 / -1;">
                            <span>{{ __('messages.form_review_label_address') }}</span>
                            <strong>{{ $resident->address ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="bhd-section-label"><i class="fas fa-pen"></i> Editable</div>

                    <div class="bhd-contact-card">
                        <h3><i class="fas fa-phone me-2"></i>{{ __('messages.register_contact_label') }}</h3>
                        <p>We will send an SMS code to verify your new number before saving it.</p>

                        <form method="POST" action="{{ route('profile.info') }}" id="infoForm">
                            @csrf

                            <div class="bhd-form-grid">
                                <div class="bhd-form-group full">
                                    <label class="bhd-profile-label">{{ __('messages.form_contact_label') }}</label>
                                    <div class="bhd-phone-input @error('contact') is-invalid @enderror">
                                        <span class="bhd-phone-prefix">+63</span>
                                        <input type="text"
                                               name="contact"
                                               id="contactInput"
                                               value="{{ preg_replace('/^0/', '', (string) old('contact', $resident->contact)) }}"
                                               class="bhd-profile-control bhd-phone-field"
                                               inputmode="numeric"
                                               pattern="[0-9]{10}"
                                               maxlength="10"
                                               placeholder="9123456789"
                                               autocomplete="tel-national"
                                               required>
                                    </div>
                                    <small class="text-muted">{{ __('messages.blade_profile_blade_enter_the_10_digits_after_63_example_9123456789') }}</small>
                                </div>
                            </div>

                            <div class="bhd-form-actions">
                                <button type="submit" class="bhd-btn bhd-btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.blade_profile_blade_update_contact') }}
                                </button>
                                <button type="reset" class="bhd-btn bhd-btn-line">{{ __('messages.profile_reset_btn') }}</button>
                            </div>
                        </form>
                        @if(session('contact_change.resident_id') === $resident->id)
                            <form method="POST" action="{{ route('profile.contact.verify') }}" class="bhd-contact-verification">
                                @csrf
                                <p>A code was sent to the number ending in {{ substr(session('contact_change.contact'), -4) }}. It expires after 5 minutes.</p>
                                <label for="contactOtp">Verification code</label>
                                <input id="contactOtp" name="contact_otp" class="bhd-profile-control" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required>
                                @error('contact_otp')<p class="portal-field-feedback" role="alert">{{ $message }}</p>@enderror
                                <button type="submit" class="bhd-btn bhd-btn-primary">Verify and save number</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="bhd-profile-panel" id="tab-account" role="tabpanel" aria-labelledby="profile-tab-account">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-cog me-2"></i>{{ __('messages.profile_nav_account_settings') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_update_your_username_email_or_password') }}</p>
                        </div>
                    </div>

                    @if($errors->hasAny(['username','email']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach(['username','email'] as $field)
                                    @foreach($errors->get($field) as $e)
                                        <div>{{ $e }}</div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="bhd-section-label"><i class="fas fa-id-badge"></i> Account details</div>

                    <div class="bhd-contact-card">
                        <form method="POST" action="{{ route('profile.account') }}">
                            @csrf

                            <div class="bhd-form-grid">
                                <div>
                                    <label class="bhd-profile-label">{{ __('messages.form_username_label') }}</label>
                                    <input type="text" name="username" value="{{ old('username', $resident->username) }}" class="bhd-profile-control @error('username') is-invalid @enderror" required>
                                </div>

                                <div>
                                    <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_email') }}</label>
                                    <input type="email" name="email" value="{{ old('email', $resident->email) }}" class="bhd-profile-control @error('email') is-invalid @enderror" required>
                                </div>
                            </div>

                            <div class="bhd-form-actions">
                                <button type="submit" class="bhd-btn bhd-btn-primary"><i class="fas fa-save"></i> {{ __('messages.blade_profile_blade_update_account') }}</button>

                                @unless($resident->email_verified_at)
                                    <button type="button" class="bhd-btn bhd-btn-soft" data-submit-target="resendVerifyForm">
                                        <i class="fas fa-envelope"></i> {{ __('messages.blade_profile_blade_verify_email') }}
                                    </button>
                                @endunless
                            </div>
                        </form>
                    </div>

                    <form id="resendVerifyForm" method="POST" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>

                    <div class="bhd-section-label"><i class="fas fa-key"></i> Change password</div>

                    @if($errors->hasAny(['current_password','new_password']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach(['current_password','new_password'] as $field)
                                    @foreach($errors->get($field) as $e)
                                        <div>{{ $e }}</div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="bhd-contact-card">
                        <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                            @csrf

                            <div class="bhd-form-grid">
                                <div>
                                    <label class="bhd-profile-label">{{ __('messages.profile_current_password_label') }}</label>
                                    <div class="position-relative">
                                        <input type="password" name="current_password" id="currentPw" class="bhd-profile-control @error('current_password') is-invalid @enderror" autocomplete="current-password" placeholder="{{ __('messages.blade_profile_blade_enter_current_password') }}" style="padding-right:48px;">
                                        <button type="button" class="bhd-toggle-pw" data-target="currentPw"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>

                                <div>
                                <label class="bhd-profile-label">{{ __('messages.profile_new_password_label') }}</label>
                                <div class="position-relative">
                                    <input type="password" name="new_password" id="newPw" class="bhd-profile-control @error('new_password') is-invalid @enderror" autocomplete="new-password" placeholder="{{ __('messages.blade_profile_blade_create_a_stronger_password') }}" style="padding-right:48px;">
                                    <button type="button" class="bhd-toggle-pw" data-target="newPw"><i class="fas fa-eye"></i></button>
                                </div>
                                <div class="bhd-pw-meter"><div class="bhd-pw-meter-fill" id="pwMeterFill"></div></div>
                                <span class="small text-muted" id="pwStrengthHint">{{ __('messages.profile_password_strength_label') }}</span>
                            </div>

                            <div>
                                <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_confirm_password') }}</label>
                                <div class="position-relative">
                                    <input type="password" name="new_password_confirmation" id="confirmPw" class="bhd-profile-control" autocomplete="new-password" placeholder="{{ __('messages.blade_profile_blade_re_enter_new_password') }}" style="padding-right:48px;">
                                    <button type="button" class="bhd-toggle-pw" data-target="confirmPw"><i class="fas fa-eye"></i></button>
                                </div>
                                <span class="small" id="pwMatchHint"></span>
                            </div>
                        </div>

                        <div class="bhd-form-actions">
                            <button type="submit" class="bhd-btn bhd-btn-primary"><i class="fas fa-lock"></i> {{ __('messages.profile_change_password_subheading') }}</button>
                        </div>
                    </form>
                    </div>
                </div>

                <div class="bhd-profile-panel" id="tab-requests" role="tabpanel" aria-labelledby="profile-tab-requests">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-file-alt me-2"></i>{{ __('messages.profile_nav_request_history') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_all_document_requests_linked_to_your_account') }}</p>
                        </div>
                    </div>

                    <div class="bhd-request-summary">
                        <article class="bhd-request-stat">
                            <span class="bhd-request-stat-icon"><i class="fas fa-certificate"></i></span>
                            <div class="bhd-request-stat-copy">
                                <span>{{ __('messages.profile_counter_clearances') }}</span>
                                <strong>{{ $clearanceCount }}</strong>
                            </div>
                        </article>

                        <article class="bhd-request-stat">
                            <span class="bhd-request-stat-icon"><i class="fas fa-house-user"></i></span>
                            <div class="bhd-request-stat-copy">
                                <span>{{ __('messages.profile_counter_residency') }}</span>
                                <strong>{{ $residencyCount }}</strong>
                            </div>
                        </article>

                        <article class="bhd-request-stat">
                            <span class="bhd-request-stat-icon"><i class="fas fa-hands-helping"></i></span>
                            <div class="bhd-request-stat-copy">
                                <span>{{ __('messages.profile_counter_indigency') }}</span>
                                <strong>{{ $indigencyCount }}</strong>
                            </div>
                        </article>

                        <article class="bhd-request-stat">
                            <span class="bhd-request-stat-icon"><i class="fas fa-triangle-exclamation"></i></span>
                            <div class="bhd-request-stat-copy">
                                <span>{{ __('messages.profile_counter_blotter') }}</span>
                                <strong>{{ $blotterCount }}</strong>
                            </div>
                        </article>
                    </div>

                    @if(count($recentRequests))
                        <section class="bhd-request-history">
                            <div class="bhd-request-history-head">
                                <strong><i class="fas fa-clock-rotate-left me-2"></i>{{ __('messages.profile_nav_request_history') }}</strong>
                                <span>{{ count($recentRequests) }} recent</span>
                            </div>

                            <div class="bhd-table-wrap">
                                <table class="bhd-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.blade_profile_blade_reference') }}</th>
                                            <th>{{ __('messages.profile_table_type') }}</th>
                                            <th>{{ __('messages.profile_table_status') }}</th>
                                            <th>{{ __('messages.blade_profile_blade_date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentRequests as $req)
                                            @php
                                                $referenceNumber = data_get($req, 'reference')
                                                    ?? data_get($req, 'reference_number')
                                                    ?? data_get($req, 'incident_number')
                                                    ?? __('messages.blade_announcement_blade_n_a');

                                                $requestType = data_get($req, 'type')
                                                    ?? data_get($req, 'request_type')
                                                    ?? 'Request';

                                                $requestStatus = data_get($req, 'status') ?: 'Pending';

                                                $rawDate = data_get($req, 'date')
                                                    ?? data_get($req, 'created_at')
                                                    ?? data_get($req, 'submitted_at')
                                                    ?? null;

                                                $displayDate = 'N/A';

                                                if ($rawDate) {
                                                    try {
                                                        $displayDate = $rawDate instanceof \Carbon\CarbonInterface
                                                            ? $rawDate->format('M d, Y')
                                                            : \Carbon\Carbon::parse($rawDate)->format('M d, Y');
                                                    } catch (\Throwable $e) {
                                                        $displayDate = (string) $rawDate;
                                                    }
                                                }
                                            @endphp

                                            <tr>
                                                <td data-label="{{ __('messages.profile_table_ref') }}">
                                                    <div class="bhd-request-ref">
                                                        <strong>{{ $referenceNumber }}</strong>
                                                        <span>{{ __('messages.blade_profile_blade_reference') }}</span>
                                                    </div>
                                                </td>
                                                <td data-label="{{ __('messages.profile_table_type') }}">
                                                    <span class="bhd-request-type">
                                                        <i class="fas fa-file-lines"></i>
                                                        {{ $requestType }}
                                                    </span>
                                                </td>
                                                <td data-label="{{ __('messages.profile_table_status') }}">
                                                    <span class="bhd-request-status">{{ ucfirst((string) $requestStatus) }}</span>
                                                </td>
                                                <td data-label="{{ __('messages.profile_table_date') }}">{{ $displayDate }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @else
                        <div class="bhd-empty-state">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>{{ __('messages.profile_history_empty') }}</p>
                            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary mt-2"><i class="fas fa-folder-open"></i> {{ __('messages.profile_request_document_link') }}</a>
                        </div>
                    @endif
                </div>

                <div class="bhd-profile-panel" id="tab-classification" role="tabpanel" aria-labelledby="profile-tab-classification">
                    @php
                        $pwdStatus = strtolower((string) ($resident->pwd_status ?? 'not_declared'));
                        $studentStatus = strtolower((string) ($resident->student_status ?? 'not_declared'));
                        $isSenior = (int) ($resident->age ?? 0) >= 60;

                        $classificationLabel = function (string $status): string {
                            return match ($status) {
                                'verified' => 'Verified',
                                'pending' => 'Pending verification',
                                'rejected' => 'Rejected',
                                default => 'Not declared',
                            };
                        };

                        $classificationClass = function (string $status): string {
                            return match ($status) {
                                'verified' => 'is-verified',
                                'pending' => 'is-pending',
                                'rejected' => 'is-rejected',
                                default => 'is-neutral',
                            };
                        };
                    @endphp

                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-user-tag me-2"></i>Resident Classification</h2>
                            <p>Student and PWD classifications require evidence and barangay verification. Senior Citizen status is determined automatically from your birthdate.</p>
                        </div>
                    </div>

                    @if($errors->hasAny(['student_proof', 'pwd_id_front', 'pwd_id_back', 'classification']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach(['classification', 'student_proof', 'pwd_id_front', 'pwd_id_back'] as $field)
                                    @foreach($errors->get($field) as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="classification-summary-grid">
                        <article class="classification-card">
                            <div class="classification-card-head">
                                <span class="classification-icon senior"><i class="fas fa-person-cane"></i></span>
                                <div>
                                    <strong>Senior Citizen</strong>
                                    <span>Based on verified profile birthdate</span>
                                </div>
                            </div>
                            <span class="classification-status {{ $isSenior ? 'is-verified' : 'is-neutral' }}">
                                <i class="fas {{ $isSenior ? 'fa-circle-check' : 'fa-circle-minus' }}"></i>
                                {{ $isSenior ? 'Qualified automatically' : 'Not qualified by age' }}
                            </span>
                            <p>{{ $isSenior ? 'Your profile age is 60 or above, so the system recognizes you as a Senior Citizen.' : 'This becomes active automatically when your age reaches 60.' }}</p>
                        </article>

                        <article class="classification-card">
                            <div class="classification-card-head">
                                <span class="classification-icon student"><i class="fas fa-graduation-cap"></i></span>
                                <div>
                                    <strong>Student</strong>
                                    <span>School proof required</span>
                                </div>
                            </div>
                            <span class="classification-status {{ $classificationClass($studentStatus) }}">
                                <i class="fas {{ $studentStatus === 'verified' ? 'fa-circle-check' : ($studentStatus === 'pending' ? 'fa-clock' : ($studentStatus === 'rejected' ? 'fa-circle-xmark' : 'fa-circle-minus')) }}"></i>
                                {{ $classificationLabel($studentStatus) }}
                            </span>
                            <p>Accepted proof: current school ID, registration form, or certificate of enrollment.</p>
                            @if($studentStatus === 'rejected' && $resident->student_rejection_reason)
                                <div class="classification-rejection"><strong>Reason:</strong> {{ $resident->student_rejection_reason }}</div>
                            @endif
                        </article>

                        <article class="classification-card">
                            <div class="classification-card-head">
                                <span class="classification-icon pwd"><i class="fas fa-wheelchair"></i></span>
                                <div>
                                    <strong>Person with Disability (PWD)</strong>
                                    <span>Official PWD ID card required</span>
                                </div>
                            </div>
                            <span class="classification-status {{ $classificationClass($pwdStatus) }}">
                                <i class="fas {{ $pwdStatus === 'verified' ? 'fa-circle-check' : ($pwdStatus === 'pending' ? 'fa-clock' : ($pwdStatus === 'rejected' ? 'fa-circle-xmark' : 'fa-circle-minus')) }}"></i>
                                {{ $classificationLabel($pwdStatus) }}
                            </span>
                            <p>Submit clear front and back images of your official PWD ID card for barangay review.</p>
                            @if($pwdStatus === 'rejected' && $resident->pwd_rejection_reason)
                                <div class="classification-rejection"><strong>Reason:</strong> {{ $resident->pwd_rejection_reason }}</div>
                            @endif
                        </article>
                    </div>

                    <div class="bhd-section-label"><i class="fas fa-file-shield"></i> Verification evidence</div>

                    <div class="classification-proof-grid">
                        <section class="classification-proof-card">
                            <div class="classification-proof-head">
                                <div>
                                    <strong><i class="fas fa-graduation-cap me-2"></i>Student verification</strong>
                                    <span>Your proof is reviewed by authorized barangay staff.</span>
                                </div>
                                <span class="classification-status {{ $classificationClass($studentStatus) }}">{{ $classificationLabel($studentStatus) }}</span>
                            </div>

                            @if($studentStatus === 'verified')
                                <div class="classification-message success"><i class="fas fa-shield-check"></i><span>Your Student classification is verified. No new proof is needed.</span></div>
                            @elseif($studentStatus === 'pending')
                                <div class="classification-message pending"><i class="fas fa-clock"></i><span>Your Student proof is waiting for barangay verification.</span></div>
                            @else
                                <form method="POST" action="{{ route('profile.classification') }}" enctype="multipart/form-data" class="classification-form">
                                    @csrf
                                    <input type="hidden" name="classification" value="student">
                                    <div class="bhd-form-group">
                                        <label for="profile_student_proof">Student proof <span class="text-danger">*</span></label>
                                        <input type="file"
                                               id="profile_student_proof"
                                               name="student_proof"
                                               class="bhd-profile-control @error('student_proof') is-invalid @enderror"
                                               accept="image/jpeg,image/png,application/pdf"
                                               required>
                                        <small>JPG, JPEG, PNG, or PDF · maximum 5 MB.</small>
                                    </div>
                                    <button type="submit" class="bhd-btn bhd-btn-primary">
                                        <i class="fas fa-paper-plane"></i>{{ $studentStatus === 'rejected' ? 'Resubmit Student Proof' : 'Submit Student Proof' }}
                                    </button>
                                </form>
                            @endif
                        </section>

                        <section class="classification-proof-card">
                            <div class="classification-proof-head">
                                <div>
                                    <strong><i class="fas fa-wheelchair me-2"></i>PWD verification</strong>
                                    <span>Both sides must be different images of the same PWD card.</span>
                                </div>
                                <span class="classification-status {{ $classificationClass($pwdStatus) }}">{{ $classificationLabel($pwdStatus) }}</span>
                            </div>

                            @if($pwdStatus === 'verified')
                                <div class="classification-message success"><i class="fas fa-shield-check"></i><span>Your PWD classification is verified. No new proof is needed.</span></div>
                            @elseif($pwdStatus === 'pending')
                                <div class="classification-message pending"><i class="fas fa-clock"></i><span>Your PWD ID card is waiting for barangay verification.</span></div>
                            @else
                                <form method="POST" action="{{ route('profile.classification') }}" enctype="multipart/form-data" class="classification-form" id="profilePwdClassificationForm">
                                    @csrf
                                    <input type="hidden" name="classification" value="pwd">
                                    <div class="classification-pwd-files">
                                        <div class="bhd-form-group">
                                            <label for="profile_pwd_id_front">PWD ID front <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   id="profile_pwd_id_front"
                                                   name="pwd_id_front"
                                                   class="bhd-profile-control @error('pwd_id_front') is-invalid @enderror"
                                                   accept="image/jpeg,image/png,application/pdf"
                                                   required>
                                        </div>
                                        <div class="bhd-form-group">
                                            <label for="profile_pwd_id_back">PWD ID back <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   id="profile_pwd_id_back"
                                                   name="pwd_id_back"
                                                   class="bhd-profile-control @error('pwd_id_back') is-invalid @enderror"
                                                   accept="image/jpeg,image/png,application/pdf"
                                                   required>
                                        </div>
                                    </div>
                                    <small class="classification-file-help">JPG, JPEG, PNG, or PDF · maximum 5 MB each. The front and back cannot be the same picture.</small>
                                    <button type="submit" class="bhd-btn bhd-btn-primary">
                                        <i class="fas fa-paper-plane"></i>{{ $pwdStatus === 'rejected' ? 'Resubmit PWD Card' : 'Submit PWD Card' }}
                                    </button>
                                </form>
                            @endif
                        </section>
                    </div>
                </div>

                <div class="bhd-profile-panel" id="tab-valid-id" role="tabpanel" aria-labelledby="profile-tab-valid-id">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-id-card me-2"></i>{{ __('messages.profile_nav_valid_id') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_update_both_the_front_and_back_of_your_valid_id_the_sca') }}</p>
                        </div>
                    </div>

                    <div class="bhd-valid-id-intro">
                        <span class="bhd-valid-id-intro-icon"><i class="fas fa-shield-halved"></i></span>
                        <div>
                            <strong>Keep both sides of your ID clear and readable.</strong>
                            <span>Check your current ID first. If you need to replace it, upload or scan the front and back below, then submit once.</span>
                        </div>
                    </div>

                    <div class="bhd-section-label"><i class="fas fa-eye"></i> Currently on file</div>

                    <div class="bhd-current-id-grid">
                        <article class="bhd-current-id-card">
                            <div class="bhd-current-id-preview"
                                @if($resident->valid_id && in_array(strtolower(pathinfo($resident->valid_id, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))
                                    data-id-viewer
                                    data-img-src="{{ route('secure.resident-id', ['side' => 'front']) }}"
                                    data-img-label="{{ __('messages.blade_profile_blade_front') }} — {{ __('messages.blade_profile_blade_current_front') }}"
                                    role="button"
                                    tabindex="0"
                                @endif
                            >
                                @if($resident->valid_id)
                                    @php $frontExt = pathinfo($resident->valid_id, PATHINFO_EXTENSION); @endphp
                                    @if(in_array(strtolower($frontExt), ['jpg','jpeg','png','webp']))
                                        <img src="{{ route('secure.resident-id', ['side' => 'front']) }}" alt="{{ __('messages.blade_profile_blade_current_valid_id_front') }}" loading="lazy">
                                        <span class="bhd-id-view-hint"><i class="fas fa-magnifying-glass-plus"></i></span>
                                    @else
                                        <div class="bhd-id-empty">
                                            <i class="fas fa-file-pdf"></i>
                                            <p class="mb-0">{{ __('messages.blade_profile_blade_front_id_file_uploaded') }}</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="bhd-id-empty">
                                        <i class="fas fa-id-card"></i>
                                        <p class="mb-0">{{ __('messages.blade_profile_blade_no_front_id_uploaded_yet') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="bhd-current-id-copy">
                                <span class="eyebrow">{{ __('messages.blade_profile_blade_front') }}</span>
                                <strong>{{ __('messages.blade_profile_blade_current_front') }}</strong>
                                <span>{{ $resident->valid_id ? 'A front-side ID is currently saved.' : __('messages.blade_profile_blade_no_front_id_uploaded_yet') }}</span>
                            </div>
                        </article>

                        <article class="bhd-current-id-card">
                            <div class="bhd-current-id-preview"
                                @if(!empty($resident->valid_id_back) && in_array(strtolower(pathinfo($resident->valid_id_back, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))
                                    data-id-viewer
                                    data-img-src="{{ route('secure.resident-id', ['side' => 'back']) }}"
                                    data-img-label="{{ __('messages.blade_profile_blade_back') }} — {{ __('messages.blade_profile_blade_current_back') }}"
                                    role="button"
                                    tabindex="0"
                                @endif
                            >
                                @if(!empty($resident->valid_id_back))
                                    @php $backExt = pathinfo($resident->valid_id_back, PATHINFO_EXTENSION); @endphp
                                    @if(in_array(strtolower($backExt), ['jpg','jpeg','png','webp']))
                                        <img src="{{ route('secure.resident-id', ['side' => 'back']) }}" alt="{{ __('messages.blade_profile_blade_current_valid_id_back') }}" loading="lazy">
                                        <span class="bhd-id-view-hint"><i class="fas fa-magnifying-glass-plus"></i></span>
                                    @else
                                        <div class="bhd-id-empty">
                                            <i class="fas fa-file-pdf"></i>
                                            <p class="mb-0">{{ __('messages.blade_profile_blade_back_id_file_uploaded') }}</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="bhd-id-empty">
                                        <i class="fas fa-id-card-clip"></i>
                                        <p class="mb-0">{{ __('messages.blade_profile_blade_no_back_id_uploaded_yet') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="bhd-current-id-copy">
                                <span class="eyebrow">{{ __('messages.blade_profile_blade_back') }}</span>
                                <strong>{{ __('messages.blade_profile_blade_current_back') }}</strong>
                                <span>{{ !empty($resident->valid_id_back) ? 'A back-side ID is currently saved.' : __('messages.blade_profile_blade_no_back_id_uploaded_yet') }}</span>
                            </div>
                        </article>
                    </div>

                    @php
                        $validIdStatus = strtolower(trim((string) ($resident->valid_id_status ?? 'for_verification')));
                        $validIdRejected = $validIdStatus === 'rejected';
                        $validIdVerified = ($resident->valid_id_verified ?? false) && $validIdStatus === 'verified';
                        $validIdMessage = $validIdRejected
                            ? ($resident->valid_id_rejection_reason ?: 'Your valid ID was rejected. Please submit a clearer front and back copy.')
                            : ($validIdVerified ? 'Your valid ID has been verified.' : 'Your uploaded ID is waiting for barangay verification.');
                    @endphp
                    <div class="bhd-id-verification {{ $validIdVerified ? 'is-verified' : 'is-unverified' }}">
                        <div class="bhd-id-verification-copy">
                            <i class="fas fa-shield-halved"></i>
                            <div>
                                <strong>{{ __('messages.blade_profile_blade_verification_status') }}</strong>
                                <span>{{ $validIdMessage }}</span>
                            </div>
                        </div>
                        @if($validIdVerified)
                            <span class="bhd-status-pill is-verified"><i class="fas fa-check-circle"></i> {{ __('messages.blade_profile_blade_verified') }}</span>
                        @elseif($validIdRejected)
                            <span class="bhd-status-pill is-unverified"><i class="fas fa-circle-xmark"></i> Rejected</span>
                        @else
                            <span class="bhd-status-pill is-unverified"><i class="fas fa-clock"></i> For verification</span>
                        @endif
                    </div>

                    @if($errors->has('valid_id') || $errors->has('valid_id_back'))
                        <div class="bhd-alert danger mt-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @error('valid_id') <div>{{ $message }}</div> @enderror
                                @error('valid_id_back') <div>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="bhd-section-label"><i class="fas fa-arrow-up-from-bracket"></i> Replace it</div>

                    <article class="bhd-id-update-card mt-3">
                        <div class="bhd-id-update-head">
                            <div class="bhd-id-update-title">
                                <span class="bhd-id-update-title-icon"><i class="fas fa-camera"></i></span>
                                <div>
                                    <strong>Replace your valid ID</strong>
                                    <span>Choose one method, then make sure both sides are attached.</span>
                                </div>
                            </div>
                            <span class="bhd-id-required">FRONT + BACK REQUIRED</span>
                        </div>

                        <div class="bhd-id-update-body">
                            <div class="bhd-id-steps">
                                <div class="bhd-id-step">
                                    <span class="bhd-id-step-number">1</span>
                                    <div>
                                        <strong>Capture or choose</strong>
                                        <span>Scan the ID or select images manually.</span>
                                    </div>
                                </div>
                                <div class="bhd-id-step">
                                    <span class="bhd-id-step-number">2</span>
                                    <div>
                                        <strong>Check both sides</strong>
                                        <span>Make sure front and back previews are correct.</span>
                                    </div>
                                </div>
                                <div class="bhd-id-step">
                                    <span class="bhd-id-step-number">3</span>
                                    <div>
                                        <strong>Submit once</strong>
                                        <span>Update both sides together for verification.</span>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('profile.id') }}" enctype="multipart/form-data" id="idUploadForm">
                                @csrf

                                <div class="bhd-scan-panel">
                                    <div>
                                        <strong><i class="fas fa-wand-magic-sparkles me-1"></i>Guided front and back ID capture</strong>
                                        <span>Open the camera, align the front side, wait for Ready to capture, then press Capture. Repeat for the back side.</span>
                                    </div>
                                    <button type="button" class="bhd-btn bhd-btn-primary" id="scanBothSidesBtn">
                                        <i class="fas fa-camera-retro"></i>
                                        Open guided camera
                                    </button>
                                </div>

                                <div class="bhd-scan-status" id="idAutoScanStatus">
                                    <i class="fas fa-id-card-clip"></i>
                                    <div>
                                        <strong id="idAutoScanStatusTitle">No new ID captured yet</strong>
                                        <span id="idAutoScanStatusText">Use the guided camera or choose both front and back images manually.</span>
                                        <span class="d-none" id="validIdFrontText">{{ __('messages.blade_profile_blade_no_front_file_selected') }}</span>
                                        <span class="d-none" id="validIdBackText">{{ __('messages.blade_profile_blade_no_back_file_selected') }}</span>
                                    </div>
                                </div>

                                <div class="bhd-upload-choice-grid">
                                    <label class="bhd-file-choice" for="valid_id">
                                        <button type="button" class="bhd-file-remove" data-remove-file="valid_id" aria-label="Remove front ID"><i class="fas fa-xmark"></i></button>
                                        <span class="bhd-file-icon"><i class="fas fa-image"></i></span>
                                        <span class="bhd-file-copy">
                                            <strong>{{ __('messages.blade_profile_blade_choose_front_manually') }}</strong>
                                            <span id="manualFrontText">{{ __('messages.blade_profile_blade_no_front_image_selected') }}</span>
                                        </span>
                                        <input type="file"
                                               id="valid_id"
                                               name="valid_id"
                                               accept="image/*"
                                               data-file-text="manualFrontText"
                                               data-preview="validIdFrontPreview"
                                               data-preview-shell="validIdFrontPreviewShell"
                                               required>
                                    </label>

                                    <label class="bhd-file-choice" for="valid_id_back">
                                        <button type="button" class="bhd-file-remove" data-remove-file="valid_id_back" aria-label="Remove back ID"><i class="fas fa-xmark"></i></button>
                                        <span class="bhd-file-icon"><i class="fas fa-image"></i></span>
                                        <span class="bhd-file-copy">
                                            <strong>{{ __('messages.blade_profile_blade_choose_back_manually') }}</strong>
                                            <span id="manualBackText">{{ __('messages.blade_profile_blade_no_back_image_selected') }}</span>
                                        </span>
                                        <input type="file"
                                               id="valid_id_back"
                                               name="valid_id_back"
                                               accept="image/*"
                                               data-file-text="manualBackText"
                                               data-preview="validIdBackPreview"
                                               data-preview-shell="validIdBackPreviewShell"
                                               required>
                                    </label>
                                </div>

                                <div class="bhd-id-new-grid">
                                    <article class="bhd-id-new-card">
                                        <div class="bhd-id-new-card-head">
                                            <strong><i class="fas fa-camera me-1"></i>{{ __('messages.blade_profile_blade_new_front_preview') }}</strong>
                                            <span>{{ __('messages.blade_profile_blade_required') }}</span>
                                        </div>
                                        <div class="bhd-id-preview-shell" id="validIdFrontPreviewShell">
                                            <img src="" alt="{{ __('messages.blade_profile_blade_new_front_id_preview') }}" id="validIdFrontPreview" class="d-none">
                                            <div class="bhd-id-empty">
                                                <i class="fas fa-image"></i>
                                                <p class="mb-0">{{ __('messages.blade_profile_blade_front_preview_will_appear_here') }}</p>
                                            </div>
                                        </div>
                                    </article>

                                    <article class="bhd-id-new-card">
                                        <div class="bhd-id-new-card-head">
                                            <strong><i class="fas fa-camera me-1"></i>{{ __('messages.blade_profile_blade_new_back_preview') }}</strong>
                                            <span>{{ __('messages.blade_profile_blade_required') }}</span>
                                        </div>
                                        <div class="bhd-id-preview-shell" id="validIdBackPreviewShell">
                                            <img src="" alt="{{ __('messages.blade_profile_blade_new_back_id_preview') }}" id="validIdBackPreview" class="d-none">
                                            <div class="bhd-id-empty">
                                                <i class="fas fa-image"></i>
                                                <p class="mb-0">{{ __('messages.blade_profile_blade_back_preview_will_appear_here') }}</p>
                                            </div>
                                        </div>
                                    </article>
                                </div>

                                <div class="bhd-id-update-actions">
                                    <small>
                                        {{ __('messages.blade_profile_blade_jpg_png_or_webp_only_uploading_a_new_id_should_reset_the_v') }}
                                    </small>
                                    <button type="submit" class="bhd-btn bhd-btn-primary">
                                        <i class="fas fa-upload"></i>
                                        {{ __('messages.blade_profile_blade_update_front_and_back_id') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </article>
                </div>            </div>
        </div>
    </div>
</section>

<div class="modal fade id-scanner-modal" id="idScannerModal" tabindex="-1" aria-labelledby="idScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bhd-modal-card">
            <div class="modal-header">
                <h5 class="modal-title" id="idScannerModalLabel">Guided Valid ID Camera</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>

            <div class="modal-body">
                <div class="scanner-help">
                    <i class="fas fa-lightbulb"></i>
                    <span>Place the correct side fully inside the guide. Hold it steady in good lighting. The system will tell you when it is ready; you must press Capture yourself.</span>
                </div>

                <div class="scanner-stage">
                    <video id="idScannerVideo" autoplay playsinline muted></video>
                    <canvas id="idScannerOverlay"></canvas>
                    <div class="scanner-guide" aria-hidden="true"></div>

                    <div class="scanner-status" id="idScannerStatus">
                        <span class="d-inline-flex align-items-center gap-2">
                            <span class="status-dot"></span>
                            <span id="idScannerStatusText">{{ __('messages.blade_profile_blade_camera_is_starting') }}</span>
                        </span>
                        <span id="idScannerTargetText">{{ __('messages.blade_profile_blade_front_side') }}</span>
                    </div>
                </div>

                <canvas id="idScannerWorkCanvas"></canvas>
                <canvas id="idScannerResultCanvas"></canvas>

                <div class="scanner-controls">
                    <button type="button" class="btn-scanner-secondary" id="idScannerRetakeBtn">
                        <i class="fas fa-rotate-right"></i>
                        {{ __('messages.blade_profile_blade_restart_camera') }}
                    </button>

                    <button type="button" class="btn-scanner-primary" id="idScannerManualCaptureBtn">
                        <i class="fas fa-camera"></i>
                        Capture when ready
                    </button>

                    <button type="button" class="btn-scanner-danger" data-bs-dismiss="modal">
                        <i class="fas fa-xmark"></i>
                        {{ __('messages.register_cancel_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="idImageViewModal" tabindex="-1" aria-labelledby="idImageViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bhd-modal-card">
            <div class="modal-header">
                <h5 class="modal-title" id="idImageViewModalLabel">{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>
            <div class="modal-body text-center">
                <img id="idImageViewModalImg" src="" alt="{{ __('messages.blade_certificate_indigency_blade_valid_id') }}" style="max-width:100%;max-height:75vh;border-radius:16px;object-fit:contain;">
            </div>
        </div>
    </div>
</div>

@php
    $profileCorrectionRoute = \Illuminate\Support\Facades\Route::has('profile.reportIncorrectProfile')
        ? route('profile.reportIncorrectProfile')
        : null;

    $profileCorrectionFields = [
        ['value' => 'full_name', 'label' => 'Full Name', 'current' => $resident->full_name],
        ['value' => 'firstname', 'label' => 'First Name', 'current' => $resident->firstname ?? ''],
        ['value' => 'middlename', 'label' => 'Middle Name', 'current' => $resident->middlename ?? ''],
        ['value' => 'lastname', 'label' => 'Last Name', 'current' => $resident->lastname ?? ''],
        ['value' => 'suffix', 'label' => 'Suffix', 'current' => $resident->suffix ?? ''],
        [
            'value' => 'birthdate',
            'label' => 'Birthdate',
            'current' => $resident->birthdate
                ? (optional($resident->birthdate)->format('Y-m-d') ?? (string) $resident->birthdate)
                : '',
        ],
        ['value' => 'address', 'label' => 'Address', 'current' => $resident->address ?? ''],
        ['value' => 'gender', 'label' => 'Gender', 'current' => $resident->gender ?? ''],
        ['value' => 'civil_status', 'label' => 'Civil Status', 'current' => $resident->civil_status ?? ''],
        ['value' => 'place_of_birth', 'label' => 'Place of Birth', 'current' => $resident->place_of_birth ?? ''],
    ];
@endphp

<div class="modal fade" id="profileCorrectionModal" tabindex="-1" aria-labelledby="profileCorrectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bhd-modal-card">
            <div class="modal-header">
                <h5 class="modal-title" id="profileCorrectionModalLabel">
                    <i class="fas fa-flag me-2"></i> {{ __('messages.blade_profile_blade_request_profile_correction') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>

            @if($profileCorrectionRoute)
                <form method="POST" action="{{ $profileCorrectionRoute }}" id="profileCorrectionForm">
                    @csrf

                    <div class="modal-body">
                        <div class="bhd-modal-note">
                            <i class="fas fa-circle-info"></i>
                            <span>
                                {{ __('messages.blade_profile_blade_submit_only_one_correction_at_a_time_while_your_concern') }}
                            </span>
                        </div>

                        <input type="hidden" name="service" value="Profile">
                        <input type="hidden" name="current_value" id="correctionCurrentValueHidden" value="{{ old('current_value') }}">

                        <div class="bhd-form-grid">
                            <div class="bhd-form-group">
                                <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_field_to_correct') }}</label>
                                <select name="field_name" id="correctionFieldSelect" class="bhd-profile-control @error('field_name') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.blade_profile_blade_select_field') }}</option>
                                    @foreach($profileCorrectionFields as $field)
                                        <option value="{{ $field['value'] }}"
                                                data-current="{{ $field['current'] }}"
                                                {{ old('field_name') === $field['value'] ? 'selected' : '' }}>
                                            {{ $field['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bhd-form-group">
                                <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_current_value') }}</label>
                                <input type="text" id="correctionCurrentValuePreview" class="bhd-profile-control" value="{{ old('current_value') }}" readonly>
                            </div>

                            <div class="bhd-form-group full">
                                <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_correct_value_2') }}</label>
                                <input type="text"
                                       name="correct_value"
                                       value="{{ old('correct_value') }}"
                                       class="bhd-profile-control @error('correct_value') is-invalid @enderror"
                                       placeholder="{{ __('messages.blade_clearance_form_blade_enter_the_correct_information') }}"
                                       required>
                            </div>

                            <div class="bhd-form-group full">
                                <label class="bhd-profile-label">{{ __('messages.blade_profile_blade_message_explanation') }}</label>
                                <textarea name="message"
                                          rows="3"
                                          class="bhd-profile-control @error('message') is-invalid @enderror"
                                          placeholder="{{ __('messages.blade_profile_blade_optional_explain_why_this_information_should_be_correct') }}">{{ old('message') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="bhd-btn bhd-btn-line" data-bs-dismiss="modal">
                            {{ __('messages.register_cancel_btn') }}
                        </button>
                        <button type="submit" class="bhd-btn bhd-btn-primary">
                            <i class="fas fa-paper-plane"></i> {{ __('messages.blade_profile_blade_submit_correction') }}
                        </button>
                    </div>
                </form>
            @else
                <div class="modal-body">
                    <div class="bhd-alert danger mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ __('messages.blade_profile_blade_missing_route') }} <strong>{{ __('messages.blade_profile_blade_profile_reportincorrectprofile') }}</strong>{{ __('messages.blade_profile_blade_add_the_route_and_controller_method_first') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@include('shared.js-config', [
    'id' => 'js-config-profile-tabs',
    'data' => [
        'initialTab' => $errors->hasAny(['username','email','current_password','new_password'])
            ? 'account'
            : ($errors->hasAny(['contact','contact_otp','field_name','correct_value','message'])
                ? 'info'
                : ($errors->hasAny(['classification','student_proof','pwd_id_front','pwd_id_back'])
                    ? 'classification'
                    : (($errors->has('valid_id') || $errors->has('valid_id_back'))
                        ? 'valid-id'
                        : session('open_tab')))), 
        'showCorrectionModal' => $errors->hasAny(['field_name','correct_value','message']),
        'notAvailable' => __('messages.blade_announcement_blade_n_a'),
        'v1' => __('messages.blade_profile_blade_profile_photo_must_not_exceed_5mb'),
        'v2' => __('messages.blade_runtime_strength_weak'),
        'v3' => __('messages.blade_runtime_strength_fair'),
        'v4' => __('messages.blade_runtime_strength_good'),
        'v5' => __('messages.blade_runtime_strength_strong'),
        'v6' => __('messages.blade_runtime_passwords_match'),
        'v7' => __('messages.blade_runtime_passwords_do_not_match'),
        'v8' => __('messages.blade_runtime_strength_value'),
        'v9' => __('messages.profile_password_strength_label'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/profile-tabs.js') }}?v={{ filemtime(public_path('js/pages/barangay_system/profile-tabs.js')) }}"></script>

<script src="{{ asset('js/pages/barangay_system/profile.js') }}"></script>
<script src="{{ asset('js/opencv-loader.js') }}"></script>

@include('shared.js-config', [
    'id' => 'js-config-barangay-system-profile-1',
    'data' => [
        'v1' => __('messages.blade_profile_blade_front_side'),
        'v2' => __('messages.blade_profile_blade_back_side'),
        'v3' => __('messages.blade_profile_blade_new_front_and_back_id_ready'),
        'v4' => __('messages.blade_profile_blade_both_sides_are_attached_you_can_now_update_your_valid_i'),
        'v5' => __('messages.blade_profile_blade_front_side_ready'),
        'v6' => __('messages.blade_profile_blade_front_side_is_attached_scan_or_choose_the_back_side_to_'),
        'v7' => __('messages.blade_profile_blade_back_side_ready'),
        'v8' => __('messages.blade_profile_blade_back_side_is_attached_scan_or_choose_the_front_side_to_'),
        'v9' => __('messages.blade_profile_blade_no_new_id_scan_yet'),
        'v10' => __('messages.blade_profile_blade_use_the_scanner_or_manually_choose_both_front_and_back_'),
        'v11' => __('messages.blade_runtime_rescan_id'),
        'v12' => __('messages.blade_profile_blade_start_id_scan'),
        'v13' => __('messages.blade_profile_blade_no_back_image_selected'),
        'v14' => __('messages.blade_profile_blade_no_front_image_selected'),
        'v15' => __('messages.blade_profile_blade_id_image_must_not_exceed_5mb'),
        'v16' => __('messages.blade_profile_blade_please_choose_an_image_file_only'),
        'v17' => __('messages.blade_profile_blade_please_attach_both_front_and_back_valid_id_images_befor'),
        'v18' => __('messages.blade_js_capture_failed_please_try_again'),
        'v19' => __('messages.blade_js_the_detected_shape_does_not_look_like_an_id_card_align_the'),
        'v20' => __('messages.blade_js_the_id_photo_is_too_dark_move_to_better_lighting'),
        'v21' => __('messages.blade_js_the_id_photo_is_too_bright_or_has_glare'),
        'v22' => __('messages.blade_runtime_id_details_unclear'),
        'v23' => __('messages.blade_js_the_id_photo_is_blurry_hold_steady_and_scan_again'),
        'v24' => __('messages.blade_js_too_much_of_the_photo_is_dark_move_to_better_lighting'),
        'v25' => __('messages.blade_js_too_much_glare_was_detected_tilt_the_id_slightly_away_from'),
        'v26' => __('messages.blade_js_this_looks_like_the_front_side_again_flip_the_id_and_scan_'),
        'v27' => __('messages.blade_js_quality_passed'),
        'v28' => __('messages.blade_js_front_side_captured_flip_the_id_now_back_side_scanning_sta'),
        'v29' => __('messages.blade_js_now_scanning_back_side_show_the_back_of_the_id_inside_the_'),
        'v30' => __('messages.blade_js_front_and_back_sides_captured_successfully'),
        'v31' => __('messages.blade_profile_scanner_side_failed_message'),
        'v32' => __('messages.blade_runtime_place_side_clear'),
        'v33' => __('messages.blade_profile_scanner_side_quality_passed_saving_image'),
        'v34' => __('messages.blade_runtime_side_detected_hold'),
        'v35' => __('messages.blade_runtime_scanner_engine_capture'),
        'v36' => __('messages.blade_runtime_place_side'),
        'v37' => __('messages.blade_js_camera_is_not_supported_by_this_browser_please_choose_imag'),
        'v38' => __('messages.blade_profile_blade_camera_is_starting'),
        'v39' => __('messages.blade_js_scanning_front_side_first_put_the_photo_name_side_inside_t'),
        'v40' => __('messages.blade_js_camera_permission_denied_or_unavailable_please_choose_imag'),
        'v41' => __('messages.blade_js_scanner_ready_place_the_id_inside_the_guide'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/profile-dynamic1.js') }}"></script>

@endpush

@include('barangay_system.partials.public_layout_end')
