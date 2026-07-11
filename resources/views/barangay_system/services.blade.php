@php
    $pageTitle = 'Services';
    $activePage = 'services';
@endphp

@push('styles')
<style>

    .bhd-page-hero {
        padding: 58px 0 30px;
    }

    .bhd-page-hero-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 28px;
        align-items: end;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 30px;
        padding: clamp(24px, 4vw, 42px);
        box-shadow: var(--shadow);
    }

    .bhd-page-hero-card::before {
        content: '';
        position: absolute;
        inset: -140px auto auto -120px;
        width: 330px;
        height: 330px;
        border-radius: 999px;
        background: var(--brand-soft);
        pointer-events: none;
    }

    .bhd-page-kicker {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        width: fit-content;
        margin-bottom: 16px;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 0.78rem;
        font-weight: 950;
    }

    .bhd-page-hero-card h1 {
        position: relative;
        z-index: 1;
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-size: clamp(2.2rem, 5vw, 4rem);
        line-height: 0.98;
        letter-spacing: -0.055em;
        margin: 0 0 16px;
        color: var(--ink);
        max-width: 790px;
    }

    .bhd-page-hero-card p {
        position: relative;
        z-index: 1;
        max-width: 650px;
        color: var(--muted);
        font-size: clamp(1rem, 1.5vw, 1.1rem);
        margin: 0;
    }

    .bhd-page-actions {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .bhd-hero-symbol {
        width: 150px;
        height: 150px;
        display: grid;
        place-items: center;
        border-radius: 28px;
        border: 1px dashed var(--line-strong);
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 3rem;
    }

    .bhd-simple-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-four-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-two-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .bhd-simple-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-simple-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-simple-card h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        margin: 0 0 7px;
        line-height: 1.25;
    }

    .bhd-simple-card p {
        color: var(--muted);
        font-size: 0.88rem;
        margin: 0;
    }

    .bhd-card-icon {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 15px;
        background: var(--brand-soft);
        color: var(--brand);
        margin-bottom: 16px;
    }

    .bhd-chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .bhd-chip-list span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
        color: var(--ink-soft);
        font-size: 0.76rem;
        font-weight: 900;
    }

    .bhd-step-list {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        counter-reset: step;
    }

    .bhd-step-card {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px;
    }

    .bhd-step-card::before {
        counter-increment: step;
        content: counter(step, decimal-leading-zero);
        position: absolute;
        top: 14px;
        right: 14px;
        color: var(--brand);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.9rem;
        font-weight: 950;
    }

    .bhd-step-card h3 {
        color: var(--ink);
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-step-card p {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0;
    }

    .bhd-info-list {
        display: grid;
        gap: 12px;
    }

    .bhd-info-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
    }

    .bhd-info-row i {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 12px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-info-row strong {
        display: block;
        color: var(--ink);
        font-size: 0.92rem;
        font-weight: 950;
    }

    .bhd-info-row span {
        display: block;
        color: var(--muted);
        font-size: 0.84rem;
        margin-top: 1px;
    }

    .bhd-form-card,
    .bhd-table-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: clamp(20px, 3vw, 30px);
        box-shadow: var(--shadow);
    }

    .bhd-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-form-group {
        display: grid;
        gap: 8px;
    }

    .bhd-form-group.full {
        grid-column: 1 / -1;
    }

    .bhd-form-group label {
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 900;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bhd-form-control {
        width: 100%;
        min-height: 46px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface-soft);
        color: var(--ink);
        padding: 11px 13px;
        font: inherit;
        transition: border-color .16s ease, background .16s ease, box-shadow .16s ease;
    }

    .bhd-form-control:focus {
        outline: none;
        border-color: var(--brand);
        background: var(--surface);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand) 14%, transparent);
    }

    .bhd-alert {
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .bhd-alert.danger {
        border-color: color-mix(in srgb, #dc2626 28%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
    }

    .bhd-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 999px;
        padding: 6px 10px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 0.76rem;
        font-weight: 950;
        width: fit-content;
    }

    .bhd-table-wrap {
        overflow-x: auto;
    }

    .bhd-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .bhd-table th,
    .bhd-table td {
        padding: 13px 12px;
        border-bottom: 1px solid var(--line);
        color: var(--ink-soft);
        font-size: 0.86rem;
        vertical-align: middle;
    }

    .bhd-table th {
        color: var(--muted);
        font-size: 0.74rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: var(--surface-soft);
    }

    .bhd-table td strong {
        color: var(--ink);
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
        font-size: 0.8rem;
        font-weight: 900;
    }

    .bhd-filter-btn:hover,
    .bhd-filter-btn.active {
        border-color: var(--brand);
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-modal-card {
        border: 0;
        border-radius: 22px;
        overflow: hidden;
        background: var(--surface);
        color: var(--ink);
    }

    .bhd-modal-card .modal-header {
        border-bottom: 1px solid var(--line);
        background: var(--surface-soft);
    }

    .bhd-modal-card .modal-title {
        font-weight: 950;
        color: var(--ink);
    }

    @media (max-width: 1024px) {
        .bhd-four-grid,
        .bhd-step-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-simple-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card,
        .bhd-two-grid {
            grid-template-columns: 1fr;
        }

        .bhd-hero-symbol {
            width: 110px;
            height: 110px;
            font-size: 2.3rem;
        }
    }

    @media (max-width: 575px) {
        .bhd-page-hero {
            padding-top: 36px;
        }

        .bhd-page-hero-card {
            border-radius: 24px;
            padding: 22px;
        }

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn,
        .bhd-form-card .bhd-btn {
            width: 100%;
        }

        .bhd-four-grid,
        .bhd-simple-grid,
        .bhd-step-list,
        .bhd-form-grid {
            grid-template-columns: 1fr;
        }
    }

</style>
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-folder-open"></i> {{ __('messages.blade_services_blade_online_services') }}</span>
                <h1>{{ __('messages.services_hero_title') }}</h1>
                <p>{{ __('messages.services_hero_subtitle') }}</p>
                <div class="bhd-page-actions">
                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-primary"><i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}</a>
                    <a href="#available-services" class="bhd-btn bhd-btn-soft"><i class="fas fa-list"></i> {{ __('messages.blade_contacts_blade_view_services') }}</a>
                </div>
            </div>
            <div class="bhd-hero-symbol"><i class="fas fa-file-circle-plus"></i></div>
        </div>
    </div>
</section>

<section class="bhd-section" id="available-services">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_service_title') }}</h2>
                <p>{{ __('messages.services_service_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-four-grid">
            <a href="{{ route('clearance') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-certificate"></i></span>
                <h3>{{ __('messages.nav_barangay_clearance') }}</h3>
                <p>{{ __('messages.services_clearance_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.services_clearance_duration') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('residency') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-house-user"></i></span>
                <h3>{{ __('messages.nav_certificate_residency') }}</h3>
                <p>{{ __('messages.services_residency_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.services_residency_duration') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('indigency') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hands-helping"></i></span>
                <h3>{{ __('messages.nav_certificate_indigency') }}</h3>
                <p>{{ __('messages.blade_services_blade_request_an_assistance_related_certification_with_clear_') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-clock"></i> {{ __('messages.blade_services_blade_standard_processing') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.blade_index_blade_start_request') }}</span>
                </div>
            </a>

            <a href="{{ route('incident') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-triangle-exclamation"></i></span>
                <h3>{{ __('messages.nav_blotter_report') }}</h3>
                <p>{{ __('messages.services_blotter_card_desc') }}</p>
                <div class="bhd-chip-list">
                    <span><i class="fas fa-shield-heart"></i> {{ __('messages.blade_services_blade_report_concern') }}</span>
                    <span><i class="fas fa-arrow-right"></i> {{ __('messages.incident_form_submit_btn') }}</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_how_it_works_title') }}</h2>
                <p>{{ __('messages.services_how_it_works_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
                <h3>{{ __('messages.services_step1_title') }}</h3>
                <p>{{ __('messages.services_step1_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-keyboard"></i></span>
                <h3>{{ __('messages.services_step2_title') }}</h3>
                <p>{{ __('messages.services_step2_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-user-check"></i></span>
                <h3>{{ __('messages.services_step3_title') }}</h3>
                <p>{{ __('messages.services_step3_desc') }}</p>
            </article>
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-bell"></i></span>
                <h3>{{ __('messages.services_step4_title') }}</h3>
                <p>{{ __('messages.services_step4_desc') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.services_other_title') }}</h2>
                <p>{{ __('messages.services_other_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-simple-grid">
            <a href="{{ route('track_request') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-magnifying-glass"></i></span>
                <h3>{{ __('messages.nav_track_request') }}</h3>
                <p>{{ __('messages.services_track_card_desc') }}</p>
            </a>
            <a href="{{ route('contacts') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-headset"></i></span>
                <h3>{{ __('messages.blade_services_blade_need_help') }}</h3>
                <p>{{ __('messages.services_help_card_desc') }}</p>
            </a>
            <a href="{{ route('announcements') }}" class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-bullhorn"></i></span>
                <h3>{{ __('messages.nav_announcements') }}</h3>
                <p>{{ __('messages.blade_services_blade_read_important_reminders_schedules_and_barangay_advisor') }}</p>
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
