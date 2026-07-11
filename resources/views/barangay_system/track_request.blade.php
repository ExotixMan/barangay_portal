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
<style>
    .bhd-track-page {
        --track-radius-lg: 28px;
        --track-radius: 18px;
        --track-focus: color-mix(in srgb, var(--brand) 18%, transparent);
    }

    .bhd-track-hero {
        padding: 42px 0 18px;
    }

    .bhd-track-hero-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
        gap: clamp(20px, 4vw, 40px);
        align-items: center;
        background:
            radial-gradient(circle at top left, color-mix(in srgb, var(--brand) 11%, transparent), transparent 36%),
            var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--track-radius-lg);
        padding: clamp(22px, 4vw, 42px);
        box-shadow: var(--shadow);
    }

    .bhd-track-kicker {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        width: fit-content;
        margin-bottom: 14px;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 0.76rem;
        font-weight: 950;
    }

    .bhd-track-hero-card h1 {
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-size: clamp(2rem, 4.6vw, 3.75rem);
        line-height: 1;
        letter-spacing: -0.055em;
        margin: 0 0 14px;
        color: var(--ink);
        max-width: 760px;
    }

    .bhd-track-hero-card p {
        max-width: 610px;
        color: var(--muted);
        font-size: clamp(0.96rem, 1.45vw, 1.08rem);
        margin: 0;
    }

    .bhd-track-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 22px;
    }

    .bhd-track-search-panel {
        background: color-mix(in srgb, var(--surface-soft) 78%, var(--surface));
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 18px;
    }

    .bhd-track-search-panel h2 {
        color: var(--ink);
        font-size: 1.03rem;
        font-weight: 950;
        margin: 0 0 5px;
    }

    .bhd-track-search-panel p {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0 0 14px;
    }

    .bhd-reference-input-wrap {
        position: relative;
    }

    .bhd-reference-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .bhd-reference-input {
        width: 100%;
        min-height: 50px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
        color: var(--ink);
        padding: 12px 44px 12px 40px;
        font: inherit;
        font-weight: 850;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        transition: border-color .16s ease, background .16s ease, box-shadow .16s ease;
    }

    .bhd-reference-input:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 4px var(--track-focus);
    }

    .bhd-clear-input {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        display: none;
        place-items: center;
        border: 0;
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--muted);
    }

    .bhd-clear-input.show {
        display: grid;
    }

    .bhd-search-hint {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 10px;
        color: var(--muted);
        font-size: 0.78rem;
        line-height: 1.45;
    }

    .bhd-track-mini-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-top: 16px;
    }

    .bhd-mini-stat {
        padding: 12px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .bhd-mini-stat strong {
        display: block;
        color: var(--ink);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.25rem;
        line-height: 1;
    }

    .bhd-mini-stat span {
        display: block;
        color: var(--muted);
        font-size: 0.74rem;
        font-weight: 850;
        margin-top: 5px;
    }

    .bhd-track-section {
        padding: 30px 0;
    }

    .bhd-track-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: clamp(18px, 3vw, 30px);
        box-shadow: var(--shadow);
    }

    .bhd-track-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid color-mix(in srgb, #dc2626 28%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
        font-weight: 850;
        margin-bottom: 16px;
    }

    .bhd-result-card {
        border: 1px solid var(--line);
        border-radius: 26px;
        background: var(--surface);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .bhd-result-top {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 18px;
        align-items: start;
        padding: clamp(18px, 3vw, 28px);
        border-bottom: 1px solid var(--line);
        background:
            linear-gradient(135deg, color-mix(in srgb, var(--brand) 9%, transparent), transparent),
            var(--surface);
    }

    .bhd-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        width: fit-content;
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 0.74rem;
        font-weight: 950;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
    }

    .bhd-status-pill.success {
        color: var(--success);
        background: var(--success-soft);
        border-color: color-mix(in srgb, var(--success) 28%, var(--line));
    }

    .bhd-status-pill.warning {
        color: var(--brand);
        background: var(--brand-soft);
        border-color: color-mix(in srgb, var(--brand) 26%, var(--line));
    }

    .bhd-status-pill.danger {
        color: #b91c1c;
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        border-color: color-mix(in srgb, #dc2626 26%, var(--line));
    }

    .bhd-result-title {
        margin-top: 12px;
    }

    .bhd-result-title h2 {
        color: var(--ink);
        font-size: clamp(1.35rem, 3vw, 2rem);
        font-weight: 950;
        margin: 0 0 5px;
        letter-spacing: -0.035em;
    }

    .bhd-result-title p {
        color: var(--muted);
        margin: 0;
        font-size: 0.9rem;
    }

    .bhd-result-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .bhd-ref-ticket {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-top: 14px;
        padding: 10px 12px;
        border-radius: 14px;
        background: var(--surface);
        border: 1px dashed var(--line-strong);
        color: var(--ink);
        font-weight: 950;
        word-break: break-word;
    }

    .bhd-copy-ref {
        min-width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: var(--surface-soft);
        color: var(--brand);
    }

    .bhd-result-body {
        display: grid;
        grid-template-columns: minmax(0, 0.92fr) minmax(300px, 1.08fr);
        gap: 20px;
        padding: clamp(18px, 3vw, 28px);
    }

    .bhd-progress-card,
    .bhd-next-card {
        border: 1px solid var(--line);
        border-radius: 20px;
        background: var(--surface-soft);
        padding: 16px;
    }

    .bhd-progress-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-bottom: 10px;
        color: var(--ink);
        font-weight: 950;
    }

    .bhd-progress-head span {
        color: var(--brand);
        font-family: 'Space Grotesk', sans-serif;
    }

    .bhd-progress-track {
        width: 100%;
        height: 10px;
        background: var(--line);
        border-radius: 999px;
        overflow: hidden;
    }

    .bhd-progress-fill {
        height: 100%;
        width: var(--progress, 0%);
        border-radius: inherit;
        background: var(--brand);
    }

    .bhd-next-card {
        margin-top: 14px;
    }

    .bhd-next-card strong {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--ink);
        font-size: 0.92rem;
        font-weight: 950;
        margin-bottom: 8px;
    }

    .bhd-next-card p {
        color: var(--muted);
        margin: 0;
        font-size: 0.88rem;
        line-height: 1.55;
    }

    .bhd-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 14px;
    }

    .bhd-detail-box {
        padding: 13px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .bhd-detail-box span {
        display: block;
        color: var(--muted);
        font-size: 0.7rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 5px;
    }

    .bhd-detail-box strong {
        display: block;
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 950;
        overflow-wrap: anywhere;
    }

    .bhd-timeline {
        display: grid;
        gap: 10px;
    }

    .bhd-timeline-item {
        display: grid;
        grid-template-columns: 36px minmax(0, 1fr);
        gap: 12px;
        align-items: start;
    }

    .bhd-timeline-marker {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        background: var(--surface-soft);
        border: 1px solid var(--line);
        color: var(--muted);
        font-size: 0.78rem;
    }

    .bhd-timeline-item.completed .bhd-timeline-marker {
        background: var(--success-soft);
        color: var(--success);
        border-color: color-mix(in srgb, var(--success) 24%, var(--line));
    }

    .bhd-timeline-item.active .bhd-timeline-marker {
        background: var(--brand-soft);
        color: var(--brand);
        border-color: color-mix(in srgb, var(--brand) 24%, var(--line));
    }

    .bhd-timeline-body {
        padding: 12px 14px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .bhd-timeline-body h4 {
        color: var(--ink);
        font-size: 0.9rem;
        font-weight: 950;
        margin: 0 0 4px;
    }

    .bhd-timeline-body p {
        color: var(--muted);
        font-size: 0.82rem;
        margin: 0;
    }

    .bhd-requests-head {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: flex-start;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .bhd-requests-head h2 {
        color: var(--ink);
        font-size: clamp(1.2rem, 2.4vw, 1.55rem);
        font-weight: 950;
        margin: 0 0 5px;
    }

    .bhd-requests-head p {
        color: var(--muted);
        margin: 0;
        font-size: 0.9rem;
    }

    .bhd-filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .bhd-filter-btn {
        min-height: 38px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-soft);
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 900;
    }

    .bhd-filter-btn:hover,
    .bhd-filter-btn.active {
        border-color: var(--brand);
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-request-list {
        display: grid;
        gap: 10px;
    }

    .bhd-request-row {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(0, 0.9fr) 140px 110px;
        gap: 12px;
        align-items: center;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-request-row:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--brand) 38%, var(--line));
        box-shadow: 0 14px 30px rgba(184, 50, 42, 0.08);
    }

    .bhd-request-main strong {
        display: block;
        color: var(--ink);
        font-size: 0.92rem;
        font-weight: 950;
        overflow-wrap: anywhere;
    }

    .bhd-request-main span,
    .bhd-request-meta span {
        display: block;
        color: var(--muted);
        font-size: 0.78rem;
        font-weight: 800;
        margin-top: 3px;
    }

    .bhd-request-type {
        color: var(--ink-soft);
        font-size: 0.84rem;
        font-weight: 900;
    }

    .bhd-status-small {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        border-radius: 999px;
        padding: 6px 9px;
        font-size: 0.7rem;
        font-weight: 950;
        background: var(--surface-soft);
        color: var(--ink-soft);
        border: 1px solid var(--line);
    }

    .bhd-status-small.success {
        color: var(--success);
        background: var(--success-soft);
        border-color: color-mix(in srgb, var(--success) 24%, var(--line));
    }

    .bhd-status-small.warning {
        color: var(--brand);
        background: var(--brand-soft);
        border-color: color-mix(in srgb, var(--brand) 24%, var(--line));
    }

    .bhd-status-small.danger {
        color: #b91c1c;
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        border-color: color-mix(in srgb, #dc2626 24%, var(--line));
    }

    .bhd-no-data {
        text-align: center;
        padding: 34px 18px;
        color: var(--muted);
    }

    .bhd-no-data i {
        font-size: 2rem;
        color: var(--brand);
        margin-bottom: 10px;
    }

    .bhd-no-data strong {
        display: block;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .bhd-quick-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .bhd-quick-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        transition: transform .16s ease, border-color .16s ease;
    }

    .bhd-quick-card:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--brand) 40%, var(--line));
    }

    .bhd-quick-card i {
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border-radius: 13px;
        background: var(--brand-soft);
        color: var(--brand);
        flex: 0 0 auto;
    }

    .bhd-quick-card strong {
        display: block;
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 950;
        margin-bottom: 3px;
    }

    .bhd-quick-card span {
        display: block;
        color: var(--muted);
        font-size: 0.76rem;
        line-height: 1.4;
    }

    .bhd-empty-filter {
        display: none;
        text-align: center;
        padding: 22px;
        border: 1px dashed var(--line-strong);
        border-radius: 18px;
        background: var(--surface-soft);
        color: var(--muted);
        margin-top: 12px;
        font-weight: 850;
    }

    @media (max-width: 980px) {
        .bhd-track-hero-card,
        .bhd-result-body {
            grid-template-columns: 1fr;
        }

        .bhd-result-actions {
            justify-content: flex-start;
        }

        .bhd-request-row {
            grid-template-columns: 1fr 1fr;
        }

        .bhd-quick-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .bhd-track-hero {
            padding-top: 28px;
        }

        .bhd-track-hero-card,
        .bhd-result-card,
        .bhd-track-card {
            border-radius: 22px;
        }

        .bhd-track-actions .bhd-btn,
        .bhd-track-search-panel .bhd-btn,
        .bhd-result-actions .bhd-btn,
        .bhd-request-row .bhd-link {
            width: 100%;
            justify-content: center;
        }

        .bhd-track-mini-stats,
        .bhd-detail-grid,
        .bhd-request-row,
        .bhd-quick-grid {
            grid-template-columns: 1fr;
        }

        .bhd-result-top {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bhd-request-row,
        .bhd-quick-card,
        .bhd-reference-input,
        .bhd-filter-btn {
            transition: none !important;
        }
    }
</style>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const referenceInput = document.getElementById('referenceNumber');
        const clearReference = document.getElementById('clearReference');
        const copyButtons = document.querySelectorAll('[data-copy-target]');
        const filterButtons = document.querySelectorAll('#filterTabs .bhd-filter-btn');
        const rows = document.querySelectorAll('#requestsList .request-row');
        const emptyFilterMessage = document.getElementById('emptyFilterMessage');

        if (referenceInput && clearReference) {
            const toggleClear = () => {
                clearReference.classList.toggle('show', referenceInput.value.trim().length > 0);
            };

            referenceInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase().replace(/\s+/g, '');
                toggleClear();
            });

            clearReference.addEventListener('click', function () {
                referenceInput.value = '';
                referenceInput.focus();
                toggleClear();
            });

            toggleClear();
        }

        copyButtons.forEach(function (button) {
            button.addEventListener('click', async function () {
                const target = document.getElementById(button.dataset.copyTarget);
                if (!target) return;

                const value = target.textContent.trim();

                try {
                    await navigator.clipboard.writeText(value);
                    const original = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => button.innerHTML = original, 1400);
                } catch (error) {
                    const range = document.createRange();
                    range.selectNodeContents(target);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            });
        });

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                button.classList.add('active');

                const filter = button.dataset.filter;
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const type = row.dataset.type || '';
                    const status = row.dataset.status || '';

                    const show =
                        filter === 'all' ||
                        filter === type ||
                        filter === status;

                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                if (emptyFilterMessage) {
                    emptyFilterMessage.style.display = visibleCount === 0 && rows.length > 0 ? 'block' : 'none';
                }
            });
        });

        if (window.location.hash === '#trackResult') {
            document.getElementById('trackResult')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endpush

@include('barangay_system.partials.public_layout_end')
