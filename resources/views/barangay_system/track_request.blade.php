@php
    $pageTitle = 'Track Request';
    $activePage = 'track_request';

    $requestCount = is_countable($userRequests ?? []) ? count($userRequests ?? []) : 0;

    $latestRequests = collect($userRequests ?? []);
    $pendingCount = $latestRequests->filter(function ($request) {
        $status = strtolower($request['status_lower'] ?? $request['status'] ?? '');
        return !str_contains($status, 'claimed')
            && !str_contains($status, 'released')
            && !str_contains($status, 'completed')
            && !str_contains($status, 'closed')
            && !str_contains($status, 'rejected')
            && !str_contains($status, 'denied');
    })->count();

    $completedCount = $latestRequests->filter(function ($request) {
        $status = strtolower($request['status_lower'] ?? $request['status'] ?? '');
        return str_contains($status, 'claimed')
            || str_contains($status, 'released')
            || str_contains($status, 'completed')
            || str_contains($status, 'closed');
    })->count();
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/track_request.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<div class="bhd-track-page">
    <section class="bhd-track-hero">
        <div class="bhd-shell">
            <div class="bhd-track-hero-card">
                <div>
                    <span class="bhd-track-kicker">
                        <i class="fas fa-magnifying-glass"></i>
                        {{ __('messages.blade_track_request_blade_request_tracker') }}
                    </span>
                    <h1>{{ __('messages.blade_track_request_blade_know_exactly_what_happens_next') }}</h1>
                    <p>
                        {{ __('messages.blade_track_request_blade_enter_your_reference_number_to_check_your_status_expect') }}
                    </p>

                    <div class="bhd-track-actions">
                        <a href="#track-form" class="bhd-btn bhd-btn-primary">
                            <i class="fas fa-search"></i> {{ __('messages.blade_track_request_blade_track_now') }}
                        </a>
                        <a href="{{ route('services') }}" class="bhd-btn bhd-btn-soft">
                            <i class="fas fa-folder-open"></i> {{ __('messages.blade_contacts_blade_view_services') }}
                        </a>
                    </div>
                </div>

                <div class="bhd-track-search-panel" id="track-form">
                    <h2>{{ __('messages.blade_track_request_blade_track_by_reference_number') }}</h2>
                    <p>{{ __('messages.blade_track_request_blade_use_the_code_shown_after_submitting_your_request') }}</p>

                    @if(session('error'))
                        <div class="bhd-track-alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('track_request') }}" autocomplete="off">
                        @csrf

                        <label class="visually-hidden" for="referenceNumber">{{ __('messages.incident_form_bring_reference') }}</label>
                        <div class="bhd-reference-input-wrap">
                            <i class="fas fa-hashtag"></i>
                            <input
                                type="text"
                                class="bhd-reference-input @error('reference_number') is-invalid @enderror"
                                id="referenceNumber"
                                name="reference_number"
                                placeholder="{{ __('messages.blade_track_request_blade_e_g_bhd_2026_001') }}"
                                value="{{ old('reference_number', request('view')) }}"
                                inputmode="text"
                                maxlength="60"
                                required
                            >
                            <button type="button" class="bhd-clear-input" id="clearReference" aria-label="{{ __('messages.blade_track_request_blade_clear_reference_number') }}">
                                <i class="fas fa-xmark"></i>
                            </button>
                        </div>

                        @error('reference_number')
                            <div class="text-danger small fw-bold mt-2">{{ $message }}</div>
                        @enderror

                        <div class="bhd-search-hint">
                            <i class="fas fa-circle-info mt-1"></i>
                            <span>{{ __('messages.blade_track_request_blade_tip_you_can_copy_and_paste_the_reference_number_from_yo') }}</span>
                        </div>

                        <button type="submit" class="bhd-btn bhd-btn-primary w-100 mt-3">
                            <i class="fas fa-search"></i> {{ __('messages.blade_track_request_blade_check_status') }}
                        </button>
                    </form>

                    <div class="bhd-track-mini-stats">
                        <div class="bhd-mini-stat">
                            <strong>{{ $requestCount }}</strong>
                            <span>{{ __('messages.blade_track_request_blade_total') }}</span>
                        </div>
                        <div class="bhd-mini-stat">
                            <strong>{{ $pendingCount }}</strong>
                            <span>{{ __('messages.blade_track_request_blade_in_progress') }}</span>
                        </div>
                        <div class="bhd-mini-stat">
                            <strong>{{ $completedCount }}</strong>
                            <span>{{ __('messages.events_badge_completed') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($trackedRequest))
        @php
            $statusTone = $trackedRequest['status_tone'] ?? 'neutral';
            $statusLabel = $trackedRequest['status_label'] ?? $trackedRequest['status'] ?? 'Processing';
            $progress = (int) ($trackedRequest['progress_percent'] ?? 0);
            $steps = $trackedRequest['steps'] ?? [];

            $statusIcon = match ($statusTone) {
                'success' => 'fa-check-circle',
                'danger' => 'fa-circle-xmark',
                'warning' => 'fa-hourglass-half',
                default => 'fa-circle-info',
            };
        @endphp

        <section class="bhd-track-section bhd-section-muted" id="trackResult">
            <div class="bhd-shell">
                <div class="bhd-result-card">
                    <div class="bhd-result-top">
                        <div>
                            <span class="bhd-status-pill {{ $statusTone }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ $statusLabel }}
                            </span>

                            <div class="bhd-result-title">
                                <h2>{{ $trackedRequest['type'] ?? 'Request' }}</h2>
                                <p>
                                    {{ __('messages.blade_track_request_blade_submitted_by') }} <strong>{{ $trackedRequest['name'] ?? 'Resident' }}</strong>
                                    @if(!empty($trackedRequest['date'])) {{ __('messages.blade_track_on_date', ['date' => $trackedRequest['date']]) }} @endif
                                </p>
                            </div>

                            <div class="bhd-ref-ticket">
                                <i class="fas fa-barcode"></i>
                                <span id="trackedReference">{{ $trackedRequest['reference'] }}</span>
                                <button type="button" class="bhd-copy-ref" data-copy-target="trackedReference" title="{{ __('messages.blade_track_request_blade_copy_reference_number') }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="bhd-result-actions">
                            <a href="{{ route('track.request.print', ['reference' => $trackedRequest['reference']]) }}" class="bhd-btn bhd-btn-soft" target="_blank">
                                <i class="fas fa-print"></i> {{ __('messages.blade_print_track_request_blade_print') }}
                            </a>
                            <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-line">
                                <i class="fas fa-redo"></i> {{ __('messages.track_new_btn') }}
                            </a>
                        </div>
                    </div>

                    <div class="bhd-result-body">
                        <div>
                            <div class="bhd-progress-card">
                                <div class="bhd-progress-head">
                                    <strong>{{ __('messages.events_progress_label') }}</strong>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="bhd-progress-track" aria-label="{{ __('messages.track_result_timeline_title') }}">
                                    <div class="bhd-progress-fill" style="--progress: {{ max(0, min(100, $progress)) }}%;"></div>
                                </div>
                            </div>

                            @if(!empty($trackedRequest['next_action']))
                                <div class="bhd-next-card">
                                    <strong><i class="fas fa-person-walking-arrow-right"></i> {{ __('messages.blade_track_request_blade_what_to_do_next') }}</strong>
                                    <p>{{ $trackedRequest['next_action'] }}</p>
                                </div>
                            @endif

                            <div class="bhd-detail-grid">
                                <div class="bhd-detail-box">
                                    <span>{{ __('messages.blade_profile_blade_reference') }}</span>
                                    <strong>{{ $trackedRequest['reference'] }}</strong>
                                </div>
                                <div class="bhd-detail-box">
                                    <span>{{ __('messages.profile_table_status') }}</span>
                                    <strong>{{ $statusLabel }}</strong>
                                </div>
                                @if(!empty($trackedRequest['purpose']))
                                    <div class="bhd-detail-box">
                                        <span>{{ __('messages.blade_track_request_blade_purpose_type') }}</span>
                                        <strong>{{ $trackedRequest['purpose'] }}</strong>
                                    </div>
                                @endif
                                @if(!empty($trackedRequest['expected_completion']))
                                    <div class="bhd-detail-box">
                                        <span>{{ __('messages.track_result_expected_label') }}</span>
                                        <strong>{{ $trackedRequest['expected_completion'] }}</strong>
                                    </div>
                                @endif
                                @if(isset($trackedRequest['fee_label']))
                                    <div class="bhd-detail-box">
                                        <span>{{ __('messages.blade_successform_blade_fee') }}</span>
                                        <strong>{{ $trackedRequest['fee_label'] }}</strong>
                                    </div>
                                @endif
                                @if(!empty($trackedRequest['last_updated']))
                                    <div class="bhd-detail-box">
                                        <span>{{ __('messages.track_table_last_updated') }}</span>
                                        <strong>{{ $trackedRequest['last_updated'] }}</strong>
                                    </div>
                                @endif
                                @if(!empty($trackedRequest['remarks']))
                                    <div class="bhd-detail-box" style="grid-column: 1 / -1;">
                                        <span>{{ __('messages.track_result_remarks_label') }}</span>
                                        <strong>{{ $trackedRequest['remarks'] }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bhd-timeline" aria-label="{{ __('messages.blade_track_request_blade_request_timeline') }}">
                            @forelse($steps as $step)
                                <div class="bhd-timeline-item {{ $step['status'] ?? 'pending' }}">
                                    <div class="bhd-timeline-marker">
                                        @if(($step['status'] ?? '') === 'completed')
                                            <i class="fas fa-check"></i>
                                        @elseif(($step['status'] ?? '') === 'active')
                                            <i class="fas fa-spinner"></i>
                                        @else
                                            <i class="fas fa-circle"></i>
                                        @endif
                                    </div>
                                    <div class="bhd-timeline-body">
                                        <h4>{{ $step['label'] ?? 'Step' }}</h4>
                                        <p>{{ $step['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="bhd-no-data">
                                    <i class="fas fa-list-check"></i>
                                    <strong>{{ __('messages.blade_track_request_blade_no_timeline_available_yet') }}</strong>
                                    <span>{{ __('messages.blade_track_request_blade_please_check_again_later') }}</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="bhd-track-section">
        <div class="bhd-shell">
            <div class="bhd-track-card">
                <div class="bhd-requests-head">
                    <div>
                        <h2>{{ __('messages.blade_track_request_blade_my_requests_and_reports') }}</h2>
                        <p>{{ __('messages.blade_track_request_blade_recent_submissions_connected_to_your_account') }}</p>
                    </div>

                    <div class="bhd-filter-tabs" id="filterTabs" aria-label="{{ __('messages.blade_track_request_blade_request_filters') }}">
                        <button type="button" class="bhd-filter-btn active" data-filter="all">{{ __('messages.track_filter_all') }}</button>
                        <button type="button" class="bhd-filter-btn" data-filter="documents">{{ __('messages.track_filter_documents') }}</button>
                        <button type="button" class="bhd-filter-btn" data-filter="incident">{{ __('messages.track_filter_incidents') }}</button>
                        <button type="button" class="bhd-filter-btn" data-filter="pending">{{ __('messages.blade_track_request_blade_in_progress') }}</button>
                        <button type="button" class="bhd-filter-btn" data-filter="completed">{{ __('messages.events_badge_completed') }}</button>
                    </div>
                </div>

                @auth
                    <div class="bhd-request-list" id="requestsList">
                        @forelse(($userRequests ?? []) as $request)
                            @php
                                $statusLower = strtolower($request['status_lower'] ?? $request['status'] ?? '');
                                $statusLabel = $request['status_label'] ?? $request['status'] ?? 'Processing';
                                $statusTone = $request['status_tone'] ?? 'neutral';
                                $rowFilter = ($request['type_code'] ?? '') === 'incident' ? 'incident' : 'documents';

                                $isCompleted = str_contains($statusLower, 'claimed')
                                    || str_contains($statusLower, 'released')
                                    || str_contains($statusLower, 'completed')
                                    || str_contains($statusLower, 'closed');

                                $statusFilter = $isCompleted ? 'completed' : 'pending';
                            @endphp

                            <div class="bhd-request-row request-row"
                                 data-type="{{ $rowFilter }}"
                                 data-status="{{ $statusFilter }}">
                                <div class="bhd-request-main">
                                    <strong>{{ $request['reference'] }}</strong>
                                    <span>{{ $request['date'] ?? 'No date' }}</span>
                                </div>

                                <div>
                                    <div class="bhd-request-type">{{ $request['type'] }}</div>
                                    <div class="bhd-request-meta">
                                        <span>{{ __('messages.blade_track_request_blade_updated') }} {{ $request['last_updated'] ?? __('messages.blade_announcement_blade_n_a') }}</span>
                                    </div>
                                </div>

                                <span class="bhd-status-small {{ $statusTone }}">
                                    {{ $statusLabel }}
                                </span>

                                <a href="{{ route('track_request', ['view' => $request['reference']]) }}" class="bhd-link">
                                    {{ __('messages.track_table_view_btn') }} <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @empty
                            <div class="bhd-no-data">
                                <i class="fas fa-inbox"></i>
                                <strong>{{ __('messages.track_empty_title') }}</strong>
                                <span>{{ __('messages.blade_track_request_blade_you_have_not_submitted_any_requests_yet') }}</span>
                            </div>
                        @endforelse
                    </div>

                    <div class="bhd-empty-filter" id="emptyFilterMessage">
                        {{ __('messages.blade_track_request_blade_no_requests_match_this_filter') }}
                    </div>
                @else
                    <div class="bhd-no-data">
                        <i class="fas fa-right-to-bracket"></i>
                        <strong>{{ __('messages.track_login_required_title') }}</strong>
                        <span>{{ __('messages.blade_track_request_blade_you_can_still_track_using_a_reference_number_above') }}</span>
                        <div class="mt-3">
                            <a href="{{ route('login.res') }}" class="bhd-btn bhd-btn-primary">
                                <i class="fas fa-right-to-bracket"></i> {{ __('messages.nav_login') }}
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <section class="bhd-track-section bhd-section-muted">
        <div class="bhd-shell">
            <div class="bhd-requests-head">
                <div>
                    <h2>{{ __('messages.blade_track_request_blade_start_another_request') }}</h2>
                    <p>{{ __('messages.blade_track_request_blade_quick_access_to_the_most_used_barangay_services') }}</p>
                </div>
            </div>

            <div class="bhd-quick-grid">
                <a href="{{ route('clearance') }}" class="bhd-quick-card">
                    <i class="fas fa-certificate"></i>
                    <span><strong>{{ __('messages.blade_contacts_blade_clearance') }}</strong><span>{{ __('messages.blade_track_request_blade_request_barangay_clearance') }}</span></span>
                </a>

                <a href="{{ route('residency') }}" class="bhd-quick-card">
                    <i class="fas fa-house-user"></i>
                    <span><strong>{{ __('messages.profile_counter_residency') }}</strong><span>{{ __('messages.blade_certificate_residency_blade_proof_of_residency') }}</span></span>
                </a>

                <a href="{{ route('indigency') }}" class="bhd-quick-card">
                    <i class="fas fa-hands-helping"></i>
                    <span><strong>{{ __('messages.profile_counter_indigency') }}</strong><span>{{ __('messages.blade_track_request_blade_request_certification') }}</span></span>
                </a>

                <a href="{{ route('incident') }}" class="bhd-quick-card">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span><strong>{{ __('messages.blade_track_request_blade_incident') }}</strong><span>{{ __('messages.index_report_title') }}</span></span>
                </a>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('js/pages/barangay_system/track_request.js') }}"></script>
@endpush

@include('barangay_system.partials.public_layout_end')
