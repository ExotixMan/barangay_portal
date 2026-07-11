@php
    $pageTitle = 'Certificate of Indigency';
    $activePage = 'indigency';
@endphp

@push('styles')
<style>

    .bhd-page-hero {
        padding: 48px 0 28px;
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
        font-size: clamp(2.05rem, 5vw, 3.8rem);
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

    .bhd-two-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .bhd-three-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-four-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
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

    .bhd-alert {
        padding: 14px 16px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .bhd-alert.success {
        border-color: color-mix(in srgb, var(--success) 28%, var(--line));
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-alert.danger {
        border-color: color-mix(in srgb, #dc2626 28%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
    }

    .bhd-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .bhd-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
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

        .bhd-three-grid {
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
        html {
            overflow-x: hidden;
        }

        body {
            overflow-x: hidden;
        }

        .bhd-page-hero {
            padding-top: 28px;
        }

        .bhd-page-hero-card {
            border-radius: 22px;
            padding: 20px;
        }

        .bhd-page-hero-card h1 {
            font-size: clamp(2rem, 12vw, 2.6rem);
            letter-spacing: -0.05em;
        }

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn,
        .bhd-form-card .bhd-btn {
            width: 100%;
            justify-content: center;
        }

        .bhd-four-grid,
        .bhd-three-grid,
        .bhd-step-list,
        .bhd-form-grid {
            grid-template-columns: 1fr;
        }

        .bhd-section {
            padding-top: 46px;
            padding-bottom: 46px;
        }

        .bhd-section-head {
            gap: 14px;
            margin-bottom: 18px;
        }

        .bhd-section-head h2 {
            font-size: 1.55rem;
            line-height: 1.1;
        }

        .bhd-simple-card,
        .bhd-step-card,
        .bhd-form-card,
        .bhd-table-card {
            border-radius: 18px;
        }

        .modal-dialog {
            margin: 10px;
        }
    }

</style>
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker"><i class="fas fa-hands-helping"></i> {{ __('messages.nav_certificate_indigency') }}</span>
                <h1>{{ __('messages.nav_certificate_indigency') }}</h1>
                <p>{{ __('messages.indigency_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('indigency.form') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-file-circle-plus"></i>
                        {{ __('messages.indigency_apply_btn') }}
                    </a>

                    <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-magnifying-glass"></i>
                        {{ __('messages.nav_track_request') }}
                    </a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-hands-helping"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.indigency_steps_title') }}</h2>
                <p>{{ __('messages.blade_certificate_indigency_blade_follow_these_simple_steps_to_submit_and_track_your_indi') }}</p>
            </div>
        </div>

        <div class="bhd-step-list">
            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-clipboard-list"></i></span>
                <h3>{{ __('messages.indigency_step1_title') }}</h3>
                <p>{{ __('messages.indigency_step1_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-id-card"></i></span>
                <h3>{{ __('messages.indigency_step2_title') }}</h3>
                <p>{{ __('messages.indigency_step2_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-user-check"></i></span>
                <h3>{{ __('messages.indigency_step3_title') }}</h3>
                <p>{{ __('messages.indigency_step3_desc') }}</p>
            </article>

            <article class="bhd-step-card">
                <span class="bhd-card-icon"><i class="fas fa-bell"></i></span>
                <h3>{{ __('messages.indigency_step4_title') }}</h3>
                <p>{{ __('messages.indigency_step4_desc') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_certificate_indigency_blade_common_uses') }}</h2>
                <p>{{ __('messages.blade_certificate_indigency_blade_indigency_certificates_are_often_requested_for_assistan') }}</p>
            </div>
        </div>

        <div class="bhd-four-grid">
            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hospital"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_medical_assistance') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_hospital_medicine_or_health_related_assistance') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_medical_aid') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_hospital_support') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-graduation-cap"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_education_support') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_scholarship_school_or_student_assistance_requi') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_scholarship') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_school_aid') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-hand-holding-heart"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_financial_assistance') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_for_social_welfare_and_other_community_assistance_') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_aid_program') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_support') }}</span></div>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-card-icon"><i class="fas fa-scale-balanced"></i></span>
                <h3>{{ __('messages.blade_certificate_indigency_blade_legal_requirements') }}</h3>
                <p>{{ __('messages.blade_certificate_indigency_blade_used_when_agencies_require_proof_of_indigency_for_proce') }}</p>
                <div class="bhd-chip-list"><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_document_use') }}</span><span><i class="fas fa-check"></i> {{ __('messages.blade_certificate_indigency_blade_verification') }}</span></div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell bhd-two-grid">
        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-list-check"></i></span>
            <h3>{{ __('messages.indigency_requirements_title') }}</h3>

            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_prepare_a_clear_government_issued_or_accepted_identific') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_complete_resident_details') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_provide_accurate_personal_contact_and_address_informati') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-check"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_purpose_of_request') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_state_why_you_need_the_certificate_of_indigency') }}</span></div></div>
            </div>
        </article>

        <article class="bhd-simple-card">
            <span class="bhd-card-icon"><i class="fas fa-circle-info"></i></span>
            <h3>{{ __('messages.indigency_important_info_title') }}</h3>

            <div class="bhd-info-list mt-3">
                <div class="bhd-info-row"><i class="fas fa-user-shield"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_barangay_review') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_staff_may_verify_your_records_before_approval') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-clock"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_processing_time') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_processing_depends_on_review_and_completeness_of_submit') }}</span></div></div>
                <div class="bhd-info-row"><i class="fas fa-bell"></i><div><strong>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</strong><span>{{ __('messages.blade_certificate_indigency_blade_use_your_reference_number_to_check_the_application_stat') }}</span></div></div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.indigency_apply_title') }}</h2>
                <p>{{ __('messages.indigency_apply_subtitle') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</span>
                    <span>{{ __('messages.form_review_label_purpose') }}</span>
                    <span>{{ __('messages.blade_certificate_indigency_blade_resident_details') }}</span>
                    <span>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</span>
                </div>
            </div>

            <a href="{{ route('indigency.form') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-file-circle-plus"></i>
                {{ __('messages.indigency_apply_btn') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')
