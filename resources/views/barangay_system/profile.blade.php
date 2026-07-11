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

    $pendingProfileCorrection = $profileCorrectionConcerns->first(function ($concern) {
        return in_array(strtolower((string) $concern->status), ['pending', 'reviewed'], true);
    });

    $latestProfileCorrection = $profileCorrectionConcerns->first();

    $correctionStatusMeta = function (?string $status): array {
        return match (strtolower((string) $status)) {
            'pending' => [
                'class' => 'warning',
                'icon' => 'fa-clock',
                'label' => 'Pending Review',
                'message' => 'Your correction request is waiting for barangay review.',
            ],
            'reviewed' => [
                'class' => 'warning',
                'icon' => 'fa-eye',
                'label' => 'Under Review',
                'message' => 'Barangay staff already opened your concern and is checking the details.',
            ],
            'corrected' => [
                'class' => 'success',
                'icon' => 'fa-check-circle',
                'label' => 'Corrected / Approved',
                'message' => 'Your profile correction has been approved. You may submit service requests again.',
            ],
            'rejected' => [
                'class' => 'danger',
                'icon' => 'fa-circle-xmark',
                'label' => 'Rejected',
                'message' => 'Your correction request was not approved. Please review the remarks or contact the barangay.',
            ],
            default => [
                'class' => 'neutral',
                'icon' => 'fa-circle-info',
                'label' => 'No Active Concern',
                'message' => 'You do not have a pending profile correction concern.',
            ],
        };
    };

    $activeCorrectionMeta = $correctionStatusMeta($pendingProfileCorrection?->status ?? $latestProfileCorrection?->status);

    $formatConcernId = function ($concern): string {
        if (! $concern) {
            return 'PCC-' . now()->format('Y') . '-000000';
        }

        $year = optional($concern->created_at)->format('Y') ?: now()->format('Y');

        return 'PCC-' . $year . '-' . str_pad((string) $concern->id, 6, '0', STR_PAD_LEFT);
    };
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

    .bhd-alert.warning {
        border-color: color-mix(in srgb, var(--warning) 34%, var(--line));
        background: var(--warning-soft);
        color: var(--warning);
    }

    .bhd-correction-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 18px;
        align-items: start;
        margin-bottom: 18px;
        padding: clamp(16px, 3vw, 22px);
        border: 1px solid var(--line);
        border-radius: 22px;
        background: var(--surface);
        box-shadow: var(--shadow);
    }

    .bhd-correction-main {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        min-width: 0;
    }

    .bhd-correction-icon {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 15px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-correction-card.success .bhd-correction-icon {
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-correction-card.warning .bhd-correction-icon {
        background: var(--warning-soft);
        color: var(--warning);
    }

    .bhd-correction-card.danger .bhd-correction-icon {
        background: color-mix(in srgb, #dc2626 10%, var(--surface));
        color: #b91c1c;
    }

    .bhd-correction-main h2 {
        color: var(--ink);
        font-size: 1.02rem;
        font-weight: 950;
        margin: 0 0 5px;
    }

    .bhd-correction-main p {
        color: var(--muted);
        font-size: 0.86rem;
        margin: 0;
        line-height: 1.55;
    }

    .bhd-correction-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        width: fit-content;
        padding: 7px 11px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-size: 0.74rem;
        font-weight: 950;
        white-space: nowrap;
    }

    .bhd-correction-status.success {
        border-color: color-mix(in srgb, var(--success) 30%, var(--line));
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-correction-status.warning {
        border-color: color-mix(in srgb, var(--warning) 34%, var(--line));
        background: var(--warning-soft);
        color: var(--warning);
    }

    .bhd-correction-status.danger {
        border-color: color-mix(in srgb, #dc2626 30%, var(--line));
        background: color-mix(in srgb, #dc2626 9%, var(--surface));
        color: #b91c1c;
    }

    .bhd-correction-details {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    .bhd-correction-box {
        min-width: 0;
        padding: 12px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface-soft);
    }

    .bhd-correction-box span {
        display: block;
        color: var(--muted);
        font-size: 0.68rem;
        font-weight: 950;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .bhd-correction-box strong {
        display: block;
        color: var(--ink);
        font-size: 0.84rem;
        font-weight: 950;
        overflow-wrap: anywhere;
    }

    .bhd-correction-note {
        grid-column: 1 / -1;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 16px;
        background: var(--surface-soft);
        color: var(--muted);
        font-size: 0.83rem;
        font-weight: 800;
        line-height: 1.5;
    }

    .bhd-correction-note i {
        color: var(--brand);
        margin-top: 2px;
    }

    .bhd-correction-history {
        grid-column: 1 / -1;
        display: grid;
        gap: 8px;
    }

    .bhd-correction-history-title {
        color: var(--ink);
        font-size: 0.86rem;
        font-weight: 950;
        margin: 2px 0 0;
    }

    .bhd-correction-history-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 10px;
        align-items: center;
        padding: 10px 12px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface-soft);
    }

    .bhd-correction-history-row strong {
        display: block;
        color: var(--ink);
        font-size: 0.82rem;
        font-weight: 950;
        overflow-wrap: anywhere;
    }

    .bhd-correction-history-row span {
        display: block;
        color: var(--muted);
        font-size: 0.74rem;
        font-weight: 800;
        margin-top: 2px;
    }



    /* Secure profile identity fields */
    .bhd-sensitive-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        border: 1px solid color-mix(in srgb, var(--brand) 26%, var(--line));
        border-radius: 18px;
        background: color-mix(in srgb, var(--brand-soft) 55%, var(--surface));
        color: var(--ink-soft);
        font-size: 0.86rem;
        font-weight: 800;
        line-height: 1.55;
        margin-bottom: 16px;
    }

    .bhd-sensitive-notice > i {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 13px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-sensitive-notice strong {
        display: block;
        color: var(--ink);
        font-weight: 950;
        margin-bottom: 2px;
    }

    .bhd-locked-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .bhd-locked-box {
        position: relative;
        min-width: 0;
        padding: 13px 42px 13px 13px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface-soft);
    }

    .bhd-locked-box span {
        display: block;
        color: var(--muted);
        font-size: 0.7rem;
        font-weight: 950;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .bhd-locked-box strong {
        display: block;
        color: var(--ink);
        font-size: 0.9rem;
        font-weight: 950;
        overflow-wrap: anywhere;
    }

    .bhd-locked-box i {
        position: absolute;
        top: 13px;
        right: 13px;
        color: var(--muted);
    }

    .bhd-contact-card {
        border: 1px solid var(--line);
        border-radius: 20px;
        background: var(--surface);
        padding: 16px;
        margin-top: 16px;
    }

    .bhd-contact-card h3 {
        color: var(--ink);
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 4px;
    }

    .bhd-contact-card p {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0 0 14px;
    }

    .bhd-modal-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: var(--surface-soft);
        color: var(--muted);
        font-size: 0.83rem;
        font-weight: 800;
        line-height: 1.5;
        margin-bottom: 14px;
    }

    .bhd-modal-note i {
        color: var(--brand);
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .bhd-locked-grid {
            grid-template-columns: 1fr;
        }
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

    .bhd-profile-layout {
        display: grid;
        grid-template-columns: 300px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .bhd-profile-sidebar {
        position: sticky;
        top: 92px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .bhd-avatar-wrap {
        position: relative;
        width: 118px;
        height: 118px;
        margin: 0 auto 16px;
    }

    .bhd-avatar-img,
    .bhd-avatar-initials {
        width: 118px;
        height: 118px;
        border-radius: 999px;
        object-fit: cover;
        display: grid;
        place-items: center;
        background: var(--brand-soft);
        color: var(--brand);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.4rem;
        font-weight: 950;
        border: 1px solid var(--line-strong);
    }

    .bhd-avatar-edit {
        position: absolute;
        right: 2px;
        bottom: 2px;
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        border: 3px solid var(--surface);
        cursor: pointer;
    }

    .bhd-profile-name {
        text-align: center;
        color: var(--ink);
        font-size: 1.05rem;
        font-weight: 950;
        line-height: 1.2;
    }

    .bhd-profile-username {
        text-align: center;
        color: var(--muted);
        font-size: 0.84rem;
        margin-top: 4px;
    }

    .bhd-verified-badge {
        display: flex;
        justify-content: center;
        width: fit-content;
        margin: 12px auto 0;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 950;
    }

    .bhd-verified-badge.verified {
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-verified-badge.unverified {
        background: var(--warning-soft);
        color: var(--warning);
    }

    .bhd-profile-stat {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 0;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 0.84rem;
        font-weight: 850;
    }

    .bhd-profile-stat strong {
        color: var(--brand);
    }

    .bhd-profile-tabs {
        display: grid;
        gap: 8px;
        margin-top: 18px;
    }

    .bhd-profile-tab {
        width: 100%;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-soft);
        border-radius: 14px;
        padding: 11px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 900;
        text-align: left;
    }

    .bhd-profile-tab.active,
    .bhd-profile-tab:hover {
        background: var(--brand-soft);
        color: var(--brand);
        border-color: color-mix(in srgb, var(--brand) 32%, var(--line));
    }

    .bhd-profile-panel {
        display: none;
    }

    .bhd-profile-panel.active {
        display: block;
    }

    .bhd-panel-head {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--line);
        padding-bottom: 16px;
        margin-bottom: 18px;
    }

    .bhd-panel-head h2 {
        color: var(--ink);
        font-size: 1.2rem;
        font-weight: 950;
        margin: 0 0 4px;
    }

    .bhd-panel-head p {
        color: var(--muted);
        font-size: 0.86rem;
        margin: 0;
    }

    .bhd-profile-control {
        width: 100%;
        min-height: 46px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--surface-soft);
        color: var(--ink);
        padding: 11px 13px;
        font: inherit;
    }

    .bhd-profile-control:focus {
        outline: none;
        border-color: var(--brand);
        background: var(--surface);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand) 14%, transparent);
    }

    .bhd-profile-label {
        color: var(--ink);
        font-size: 0.84rem;
        font-weight: 900;
        margin-bottom: 7px;
    }

    .bhd-form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .bhd-toggle-pw {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: var(--muted);
        width: 34px;
        height: 34px;
    }

    .bhd-pw-meter {
        height: 6px;
        background: var(--line);
        border-radius: 999px;
        overflow: hidden;
        margin: 8px 0 4px;
    }

    .bhd-pw-meter-fill {
        height: 100%;
        width: 0%;
        background: var(--brand);
        transition: width .2s ease, background .2s ease;
    }

    .bhd-request-type {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--ink);
        font-weight: 950;
    }

    .bhd-id-preview {
        min-height: 260px;
        border: 1px dashed var(--line-strong);
        border-radius: 18px;
        background: var(--surface-soft);
        display: grid;
        place-items: center;
        text-align: center;
        padding: 18px;
        overflow: hidden;
    }

    .bhd-id-preview img {
        max-width: 100%;
        max-height: 330px;
        object-fit: contain;
        border-radius: 14px;
    }

    .bhd-empty-state {
        text-align: center;
        color: var(--muted);
        padding: 28px;
    }

    @media (max-width: 1024px) {
        .bhd-profile-layout {
            grid-template-columns: 1fr;
        }

        .bhd-profile-sidebar {
            position: static;
        }

        .bhd-profile-tabs {
            display: flex;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
        }

        .bhd-profile-tab {
            min-width: max-content;
        }
    }

    @media (max-width: 575px) {
        .bhd-avatar-wrap,
        .bhd-avatar-img,
        .bhd-avatar-initials {
            width: 98px;
            height: 98px;
        }

        .bhd-avatar-initials {
            font-size: 2rem;
        }

        .bhd-form-actions .bhd-btn {
            width: 100%;
        }

        .bhd-profile-tabs {
            margin-left: -2px;
            margin-right: -2px;
        }
    }


    /* Improved profile phone input */
    .bhd-profile-control::placeholder {
        color: color-mix(in srgb, var(--muted) 82%, transparent);
        opacity: 1;
        font-weight: 700;
    }

    .bhd-phone-input {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        align-items: center;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface-soft);
        transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
    }

    .bhd-phone-input:focus-within {
        border-color: var(--brand);
        background: var(--surface);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand) 14%, transparent);
    }

    .bhd-phone-input.is-invalid {
        border-color: #dc2626;
    }

    .bhd-phone-prefix {
        height: 100%;
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 13px;
        border-right: 1px solid var(--line);
        background: var(--surface);
        color: var(--brand);
        font-weight: 950;
        letter-spacing: -0.01em;
    }

    .bhd-phone-input .bhd-phone-field {
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        min-height: 48px;
    }

    .bhd-phone-input .bhd-phone-field:focus {
        box-shadow: none;
        background: transparent;
    }

    .bhd-toggle-pw {
        z-index: 3;
        border-radius: 10px;
    }

    .bhd-toggle-pw:hover {
        color: var(--brand);
        background: var(--brand-soft);
    }

    /* Profile valid ID scanner */
    .bhd-valid-id-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-id-side-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 14px;
    }

    .bhd-id-side-head {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        margin-bottom: 12px;
    }

    .bhd-id-side-head strong {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ink);
        font-size: 0.92rem;
        font-weight: 950;
    }

    .bhd-id-side-head span {
        padding: 5px 9px;
        border-radius: 999px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 0.72rem;
        font-weight: 950;
        white-space: nowrap;
    }

    .bhd-id-preview-shell {
        min-height: 180px;
        border: 1px dashed var(--line-strong);
        border-radius: 16px;
        background: var(--surface-soft);
        display: grid;
        place-items: center;
        overflow: hidden;
        text-align: center;
    }

    .bhd-id-preview-shell img {
        width: 100%;
        max-height: 240px;
        object-fit: contain;
        display: block;
        background: #0f172a;
    }

    .bhd-id-empty {
        padding: 18px;
        color: var(--muted);
        font-size: 0.86rem;
        font-weight: 800;
    }

    .bhd-id-empty i {
        display: block;
        margin-bottom: 8px;
        color: var(--brand);
        font-size: 1.8rem;
    }

    .bhd-scan-panel {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        padding: 16px;
        border: 1px solid color-mix(in srgb, var(--brand) 22%, var(--line));
        border-radius: 20px;
        background: color-mix(in srgb, var(--brand-soft) 55%, var(--surface));
        margin-bottom: 14px;
    }

    .bhd-scan-panel strong {
        display: block;
        color: var(--ink);
        font-size: 0.95rem;
        font-weight: 950;
    }

    .bhd-scan-panel span {
        display: block;
        color: var(--muted);
        font-size: 0.82rem;
        font-weight: 750;
        margin-top: 2px;
    }

    .bhd-scan-status {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-size: 0.84rem;
        font-weight: 750;
        margin-bottom: 14px;
    }

    .bhd-scan-status > i {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        border-radius: 13px;
        background: var(--brand-soft);
        color: var(--brand);
        flex: 0 0 auto;
    }

    .bhd-scan-status strong,
    .bhd-scan-status span {
        display: block;
    }

    .bhd-scan-status strong {
        color: var(--ink);
        font-weight: 950;
        margin-bottom: 2px;
    }

    .bhd-scan-status.is-complete {
        border-color: color-mix(in srgb, var(--success) 30%, var(--line));
        background: var(--success-soft);
        color: var(--success);
    }

    .bhd-scan-status.is-complete > i {
        background: var(--surface);
        color: var(--success);
    }

    .bhd-upload-choice-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .bhd-file-choice {
        position: relative;
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 10px;
        align-items: center;
        min-height: 82px;
        border: 1.5px dashed var(--line-strong);
        border-radius: 18px;
        padding: 12px;
        background: var(--surface);
        cursor: pointer;
        transition: border-color .16s ease, background .16s ease, transform .16s ease;
    }

    .bhd-file-choice:hover {
        transform: translateY(-1px);
        border-color: var(--brand);
        background: var(--brand-soft);
    }

    .bhd-file-choice input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .bhd-file-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-file-copy strong,
    .bhd-file-copy span {
        display: block;
        min-width: 0;
    }

    .bhd-file-copy strong {
        color: var(--ink);
        font-size: 0.86rem;
        font-weight: 950;
    }

    .bhd-file-copy span {
        overflow: hidden;
        color: var(--muted);
        font-size: 0.76rem;
        font-weight: 750;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .id-scanner-modal .modal-dialog {
        max-width: 930px;
    }

    .scanner-help {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 14px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-size: 0.84rem;
        font-weight: 750;
        margin-bottom: 14px;
    }

    .scanner-help i {
        color: var(--brand);
        margin-top: 2px;
    }

    .scanner-stage {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: #0f172a;
        aspect-ratio: 16 / 9;
    }

    .scanner-stage video,
    .scanner-stage canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scanner-guide {
        position: absolute;
        left: 12%;
        right: 12%;
        top: 23%;
        bottom: 23%;
        border: 2px dashed rgba(255,255,255,.86);
        border-radius: 18px;
        box-shadow: 0 0 0 999px rgba(15,23,42,.28);
        pointer-events: none;
    }

    .scanner-status {
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 11px 12px;
        border-radius: 14px;
        color: #fff;
        background: rgba(15,23,42,.78);
        border: 1px solid rgba(255,255,255,.14);
        backdrop-filter: blur(10px);
        font-size: 0.8rem;
        font-weight: 850;
    }

    .scanner-status .status-dot {
        width: 10px;
        height: 10px;
        flex: 0 0 10px;
        border-radius: 999px;
        background: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245,158,11,.18);
    }

    .scanner-status.is-ready .status-dot {
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,.18);
    }

    .scanner-status.is-error .status-dot {
        background: #ef4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,.18);
    }

    .scanner-controls {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-top: 14px;
    }

    .btn-scanner-primary,
    .btn-scanner-secondary,
    .btn-scanner-danger {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 14px;
        border: 1px solid transparent;
        padding: 11px 14px;
        font-weight: 950;
        font-size: 0.86rem;
    }

    .btn-scanner-primary {
        background: var(--brand);
        color: #fff;
    }

    .btn-scanner-secondary {
        background: var(--surface);
        color: var(--ink-soft);
        border-color: var(--line);
    }

    .btn-scanner-danger {
        background: color-mix(in srgb, #dc2626 10%, var(--surface));
        color: #b91c1c;
        border-color: color-mix(in srgb, #dc2626 24%, var(--line));
    }

    #idScannerWorkCanvas,
    #idScannerResultCanvas {
        display: none;
    }

    @media (max-width: 768px) {
        .bhd-valid-id-grid,
        .bhd-upload-choice-grid {
            grid-template-columns: 1fr;
        }

        .bhd-scan-panel {
            grid-template-columns: 1fr;
        }

        .bhd-scan-panel .bhd-btn {
            width: 100%;
        }

        .scanner-controls {
            grid-template-columns: 1fr;
        }

        .scanner-stage {
            aspect-ratio: 3 / 4;
        }

        .scanner-status {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 575px) {
        .bhd-phone-input {
            grid-template-columns: 76px minmax(0, 1fr);
        }

        .bhd-id-preview-shell {
            min-height: 150px;
        }
    }



    /* =========================================================
       FINAL MOBILE RESPONSIVE OVERRIDES
       These overrides keep the current design but fix mobile layout.
       ========================================================= */

    *, *::before, *::after {
        box-sizing: border-box;
    }

    img,
    video,
    canvas,
    svg {
        max-width: 100%;
    }

    .bhd-form-card,
    .bhd-profile-sidebar,
    .bhd-simple-card,
    .bhd-id-side-card,
    .bhd-scan-panel,
    .bhd-scan-status,
    .bhd-file-choice,
    .bhd-page-hero-card {
        min-width: 0;
    }

    .bhd-profile-control,
    .bhd-phone-field,
    .bhd-form-control,
    textarea,
    select,
    input {
        max-width: 100%;
    }

    .bhd-profile-control::placeholder,
    .bhd-form-control::placeholder {
        color: color-mix(in srgb, var(--muted) 90%, #111 10%);
        opacity: 1;
        font-weight: 700;
    }

    .bhd-profile-control::-webkit-input-placeholder,
    .bhd-form-control::-webkit-input-placeholder {
        color: color-mix(in srgb, var(--muted) 90%, #111 10%);
        opacity: 1;
        font-weight: 700;
    }

    .bhd-profile-layout {
        width: 100%;
        grid-template-columns: minmax(260px, 300px) minmax(0, 1fr);
    }

    .bhd-profile-tabs {
        min-width: 0;
    }

    .bhd-profile-tab {
        min-height: 46px;
    }

    .bhd-table-wrap {
        width: 100%;
        max-width: 100%;
        border-radius: 18px;
    }

    .bhd-table {
        min-width: 680px;
    }

    .bhd-valid-id-grid,
    .bhd-upload-choice-grid,
    .bhd-form-grid,
    .bhd-four-grid,
    .bhd-three-grid,
    .bhd-two-grid {
        min-width: 0;
    }

    .bhd-id-preview-shell {
        width: 100%;
    }

    .bhd-id-preview-shell img {
        width: 100%;
        height: auto;
    }

    .id-scanner-modal .modal-content {
        max-height: calc(100dvh - 18px);
    }

    .id-scanner-modal .modal-body {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 1200px) {
        .bhd-profile-layout {
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .bhd-form-card {
            padding: 24px;
        }
    }

    @media (max-width: 991px) {
        .bhd-profile-layout {
            grid-template-columns: 1fr;
        }

        .bhd-profile-sidebar {
            position: static;
        }

        .bhd-profile-tabs {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            overflow: visible;
            padding-bottom: 0;
        }

        .bhd-profile-tab {
            justify-content: center;
            text-align: center;
            padding: 11px 8px;
            min-width: 0;
            white-space: normal;
            line-height: 1.15;
        }

        .bhd-profile-tab i {
            width: auto;
        }

        .bhd-page-hero-card {
            align-items: start;
        }
    }

    @media (max-width: 768px) {
        .bhd-page-hero {
            padding: 28px 0 18px;
        }

        .bhd-page-hero-card {
            grid-template-columns: 1fr;
            gap: 18px;
            padding: 22px;
            border-radius: 24px;
        }

        .bhd-page-hero-card h1 {
            font-size: clamp(2rem, 11vw, 2.75rem);
            line-height: 1;
            margin-bottom: 12px;
        }

        .bhd-page-hero-card p {
            font-size: 0.96rem;
        }

        .bhd-hero-symbol {
            width: 84px;
            height: 84px;
            border-radius: 22px;
            font-size: 2rem;
        }

        .bhd-page-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            width: 100%;
        }

        .bhd-page-actions .bhd-btn {
            width: 100%;
            justify-content: center;
        }

        .bhd-form-card {
            padding: 18px;
            border-radius: 22px;
        }

        .bhd-profile-sidebar {
            padding: 18px;
            border-radius: 22px;
        }

        .bhd-profile-tabs {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-profile-tab {
            min-height: 48px;
            font-size: 0.82rem;
        }

        .bhd-panel-head {
            display: block;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .bhd-panel-head h2 {
            font-size: 1.08rem;
            line-height: 1.2;
        }

        .bhd-panel-head p {
            font-size: 0.82rem;
            margin-top: 5px;
        }

        .bhd-form-grid,
        .bhd-valid-id-grid,
        .bhd-upload-choice-grid,
        .bhd-four-grid,
        .bhd-three-grid,
        .bhd-two-grid {
            grid-template-columns: 1fr !important;
        }

        .bhd-form-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .bhd-form-actions .bhd-btn {
            width: 100%;
            justify-content: center;
        }

        .bhd-phone-input {
            grid-template-columns: 72px minmax(0, 1fr);
            border-radius: 14px;
        }

        .bhd-phone-prefix {
            min-height: 50px;
            padding-inline: 10px;
            font-size: 0.9rem;
        }

        .bhd-phone-field {
            min-width: 0;
            font-size: 0.94rem;
            padding-left: 10px;
            padding-right: 10px;
        }

        .bhd-simple-card {
            padding: 16px;
            border-radius: 18px;
        }

        .bhd-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            margin-bottom: 12px;
        }

        .bhd-id-side-head {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .bhd-id-side-head span {
            align-self: flex-start;
        }

        .bhd-id-preview-shell {
            min-height: 150px;
            border-radius: 14px;
        }

        .bhd-id-preview-shell img {
            max-height: 210px;
        }

        .bhd-scan-panel {
            grid-template-columns: 1fr;
            padding: 14px;
        }

        .bhd-scan-panel .bhd-btn {
            width: 100%;
            justify-content: center;
        }

        .bhd-scan-status {
            padding: 13px;
            border-radius: 16px;
        }

        .bhd-file-choice {
            min-height: 76px;
            border-radius: 16px;
        }

        .scanner-stage {
            aspect-ratio: 3 / 4;
            border-radius: 18px;
            max-height: 68dvh;
        }

        .scanner-guide {
            left: 8%;
            right: 8%;
            top: 28%;
            bottom: 28%;
            border-radius: 14px;
        }

        .scanner-status {
            position: static;
            margin: 10px;
            align-items: flex-start;
            flex-direction: column;
            font-size: 0.78rem;
        }

        .scanner-controls {
            grid-template-columns: 1fr;
        }

        .btn-scanner-primary,
        .btn-scanner-secondary,
        .btn-scanner-danger {
            width: 100%;
            min-height: 46px;
        }

        .id-scanner-modal .modal-dialog {
            margin: 8px;
        }
    }

    @media (max-width: 640px) {
        .bhd-table-wrap {
            overflow: visible;
        }

        .bhd-table {
            min-width: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .bhd-table thead {
            display: none;
        }

        .bhd-table,
        .bhd-table tbody,
        .bhd-table tr,
        .bhd-table td {
            display: block;
            width: 100%;
        }

        .bhd-table tr {
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        }

        .bhd-table td {
            display: grid;
            grid-template-columns: 108px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
            padding: 8px 0;
            border-bottom: 1px solid var(--line);
            font-size: 0.86rem;
            word-break: break-word;
        }

        .bhd-table td:last-child {
            border-bottom: 0;
        }

        .bhd-table td::before {
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 950;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .bhd-table td:nth-child(1)::before { content: attr(data-label); }
        .bhd-table td:nth-child(2)::before { content: attr(data-label); }
        .bhd-table td:nth-child(3)::before { content: attr(data-label); }
        .bhd-table td:nth-child(4)::before { content: attr(data-label); }

        .bhd-request-type {
            align-items: flex-start;
            line-height: 1.25;
        }

        .bhd-status-pill {
            width: fit-content;
            max-width: 100%;
            white-space: normal;
        }
    }

    @media (max-width: 575px) {
        .bhd-shell {
            width: min(100% - 24px, var(--shell, 1180px));
        }

        .bhd-section {
            padding-top: 34px;
            padding-bottom: 34px;
        }

        .bhd-profile-sidebar,
        .bhd-form-card {
            margin-inline: -2px;
        }

        .bhd-avatar-wrap,
        .bhd-avatar-img,
        .bhd-avatar-initials {
            width: 92px;
            height: 92px;
        }

        .bhd-avatar-initials {
            font-size: 1.85rem;
        }

        .bhd-avatar-edit {
            width: 34px;
            height: 34px;
            right: -2px;
            bottom: -2px;
        }

        .bhd-profile-name {
            font-size: 1rem;
        }

        .bhd-profile-tabs {
            grid-template-columns: 1fr;
        }

        .bhd-profile-tab {
            justify-content: flex-start;
            text-align: left;
            font-size: 0.86rem;
            padding-inline: 12px;
        }

        .bhd-profile-stat {
            font-size: 0.8rem;
        }

        .bhd-form-card {
            padding: 16px;
        }

        .bhd-panel-head h2 {
            font-size: 1.02rem;
        }

        .bhd-profile-label {
            font-size: 0.82rem;
        }

        .bhd-profile-control {
            min-height: 48px;
            font-size: 0.92rem;
            border-radius: 13px;
        }

        textarea.bhd-profile-control {
            min-height: 96px;
        }

        .bhd-phone-input {
            grid-template-columns: 68px minmax(0, 1fr);
        }

        .bhd-file-choice {
            grid-template-columns: 38px minmax(0, 1fr);
        }

        .bhd-file-icon {
            width: 38px;
            height: 38px;
        }

        .bhd-file-copy strong {
            font-size: 0.82rem;
        }

        .bhd-file-copy span {
            font-size: 0.72rem;
        }

        .bhd-scan-status {
            gap: 10px;
        }

        .bhd-scan-status > i {
            width: 34px;
            height: 34px;
            border-radius: 12px;
        }

        .bhd-id-side-card {
            padding: 12px;
            border-radius: 18px;
        }

        .bhd-id-preview-shell {
            min-height: 136px;
        }

        .bhd-id-empty {
            padding: 14px;
            font-size: 0.8rem;
        }

        .bhd-table td {
            grid-template-columns: 92px minmax(0, 1fr);
        }

        .id-scanner-modal .modal-header {
            padding: 12px 14px;
        }

        .id-scanner-modal .modal-title {
            font-size: 0.96rem;
        }

        .id-scanner-modal .modal-body {
            padding: 14px;
        }

        .scanner-help {
            font-size: 0.78rem;
            padding: 11px 12px;
        }

        .scanner-stage {
            max-height: 62dvh;
        }
    }

    @media (max-width: 390px) {
        .bhd-shell {
            width: min(100% - 18px, var(--shell, 1180px));
        }

        .bhd-page-hero-card {
            padding: 18px;
        }

        .bhd-page-hero-card h1 {
            font-size: 2rem;
        }

        .bhd-table td {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .bhd-table td::before {
            display: block;
        }
    }


    @media (max-width: 900px) {
        .bhd-correction-card {
            grid-template-columns: 1fr;
        }

        .bhd-correction-status {
            justify-self: start;
        }

        .bhd-correction-details {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .bhd-correction-main {
            display: grid;
            gap: 10px;
        }

        .bhd-correction-details,
        .bhd-correction-history-row {
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
        @if(session('success_info'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success_info') }}</div>
        @endif
        @if(session('success_account'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success_account') }}</div>
        @endif
        @if(session('success_password'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success_password') }}</div>
        @endif
        @if(session('success_photo'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success_photo') }}</div>
        @endif
        @if(session('success_id'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success_id') }}</div>
        @endif

        @if(session('success'))
            <div class="bhd-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if(session('warning'))
            <div class="bhd-alert warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}

                @if(session('pending_correction_id'))
                    <div class="small mt-1">Concern reference:
                @if($pendingProfileCorrection)
                    {{ $formatConcernId($pendingProfileCorrection) }}
                @else
                    {{ 'PCC-' . now()->format('Y') . '-' . str_pad((string) session('pending_correction_id'), 6, '0', STR_PAD_LEFT) }}
                @endif</div>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="bhd-alert danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @php
            $checkerClass = $activeCorrectionMeta['class'] ?? 'neutral';
        @endphp

        <article class="bhd-correction-card {{ $checkerClass }}">
            <div class="bhd-correction-main">
                <span class="bhd-correction-icon">
                    <i class="fas {{ $activeCorrectionMeta['icon'] ?? 'fa-circle-info' }}"></i>
                </span>

                <div>
                    <h2>{{ __('messages.blade_profile_blade_profile_correction_concern_status') }}</h2>
                    <p>{{ $activeCorrectionMeta['message'] ?? 'You do not have a pending profile correction concern.' }}</p>
                </div>
            </div>

            <span class="bhd-correction-status {{ $checkerClass }}">
                <i class="fas {{ $activeCorrectionMeta['icon'] ?? 'fa-circle-info' }}"></i>
                {{ $activeCorrectionMeta['label'] ?? 'No Active Concern' }}
            </span>

            @if($latestProfileCorrection)
                <div class="bhd-correction-details">
                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_profile_blade_concern_id') }}</span>
                        <strong>{{ $formatConcernId($latestProfileCorrection) }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_mission_vision_blade_service') }}</span>
                        <strong>{{ $latestProfileCorrection->service ?: 'General Profile' }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_profile_blade_field') }}</span>
                        <strong>{{ str_replace('_', ' ', ucfirst($latestProfileCorrection->field_name)) }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_index_blade_submitted') }}</span>
                        <strong>{{ optional($latestProfileCorrection->created_at)->format('M d, Y h:i A') }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_profile_blade_current_value') }}</span>
                        <strong>{{ $latestProfileCorrection->current_value ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_profile_blade_correct_value') }}</span>
                        <strong>{{ $latestProfileCorrection->correct_value ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.profile_table_status') }}</span>
                        <strong>{{ ucfirst($latestProfileCorrection->status) }}</strong>
                    </div>

                    <div class="bhd-correction-box">
                        <span>{{ __('messages.blade_profile_blade_reviewed') }}</span>
                        <strong>{{ $latestProfileCorrection->reviewed_at ? $latestProfileCorrection->reviewed_at->format('M d, Y h:i A') : __('messages.blade_fallback_not_yet') }}</strong>
                    </div>

                    @if(!empty($latestProfileCorrection->message))
                        <div class="bhd-correction-note">
                            <i class="fas fa-message"></i>
                            <span>{{ $latestProfileCorrection->message }}</span>
                        </div>
                    @endif

                    @if($pendingProfileCorrection)
                        <div class="bhd-correction-note">
                            <i class="fas fa-lock"></i>
                            <span>
                                {{ __('messages.blade_profile_blade_request_forms_are_temporarily_locked_while_this_concern') }}
                            </span>
                        </div>
                    @else
                        <div class="bhd-correction-note">
                            <i class="fas fa-unlock"></i>
                            <span>{{ __('messages.blade_profile_blade_no_pending_correction_is_blocking_your_service_request_') }}</span>
                        </div>
                    @endif

                    @if($profileCorrectionConcerns->count() > 1)
                        <div class="bhd-correction-history">
                            <div class="bhd-correction-history-title">{{ __('messages.blade_profile_blade_recent_correction_concerns') }}</div>

                            @foreach($profileCorrectionConcerns->skip(1) as $concern)
                                @php $historyMeta = $correctionStatusMeta($concern->status); @endphp

                                <div class="bhd-correction-history-row">
                                    <div>
                                        <strong>{{ $formatConcernId($concern) }} · {{ str_replace('_', ' ', ucfirst($concern->field_name)) }}</strong>
                                        <span>{{ $concern->service ?: 'General Profile' }} · {{ optional($concern->created_at)->format('M d, Y h:i A') }}</span>
                                    </div>

                                    <span class="bhd-correction-status {{ $historyMeta['class'] }}">
                                        <i class="fas {{ $historyMeta['icon'] }}"></i>
                                        {{ $historyMeta['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="bhd-correction-details">
                    <div class="bhd-correction-note">
                        <i class="fas fa-circle-info"></i>
                        <span>{{ __('messages.blade_profile_blade_no_profile_correction_concern_has_been_submitted_yet') }}</span>
                    </div>
                </div>
            @endif
        </article>

        <div class="bhd-profile-layout">
            <aside class="bhd-profile-sidebar">
                <div class="bhd-avatar-wrap">
                    @if($resident->profile_photo && file_exists(public_path($resident->profile_photo)))
                        <img src="{{ asset($resident->profile_photo) }}" alt="{{ __('messages.blade_profile_blade_profile_photo') }}" class="bhd-avatar-img" loading="lazy">
                    @else
                        <div class="bhd-avatar-initials">
                            {{ strtoupper(substr($resident->firstname,0,1)) }}{{ strtoupper(substr($resident->lastname,0,1)) }}
                        </div>
                    @endif

                    <label for="quickPhotoInput" class="bhd-avatar-edit" title="{{ __('messages.blade_profile_blade_change_photo') }}">
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

                <div class="mt-3">
                    <div class="bhd-profile-stat"><span>{{ __('messages.profile_stat_member_since') }}</span><strong>{{ $resident->created_at->format('M Y') }}</strong></div>
                    <div class="bhd-profile-stat"><span>{{ __('messages.profile_stat_total_requests') }}</span><strong>{{ $totalRequests }}</strong></div>
                </div>

                <nav class="bhd-profile-tabs" id="profileTabs">
                    <button class="bhd-profile-tab active" type="button" data-tab="info"><i class="fas fa-user"></i> {{ __('messages.form_step_personal_info') }}</button>
                    <button class="bhd-profile-tab" type="button" data-tab="account"><i class="fas fa-cog"></i> {{ __('messages.blade_public_nav_blade_account') }}</button>
                    <button class="bhd-profile-tab" type="button" data-tab="requests"><i class="fas fa-file-alt"></i> {{ __('messages.blade_profile_blade_requests') }}</button>
                    <button class="bhd-profile-tab" type="button" data-tab="valid-id"><i class="fas fa-id-card"></i> {{ __('messages.blade_certificate_indigency_blade_valid_id') }}</button>
                </nav>

                <a href="{{ route('logout.res') }}" class="bhd-btn bhd-btn-line w-100 mt-3">
                    <i class="fas fa-right-from-bracket"></i> {{ __('messages.profile_signout_btn') }}
                </a>
            </aside>

            <div class="bhd-form-card">
                <div class="bhd-profile-panel active" id="tab-info">
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

                    @if($errors->hasAny(['contact']))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @foreach($errors->get('contact') as $e)
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

                        @if(!blank($resident->birth_place ?? null))
                            <div class="bhd-locked-box">
                                <span>{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</span>
                                <strong>{{ $resident->birth_place }}</strong>
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif

                        <div class="bhd-locked-box" style="grid-column: 1 / -1;">
                            <span>{{ __('messages.form_review_label_address') }}</span>
                            <strong>{{ $resident->address ?: __('messages.blade_announcement_blade_n_a') }}</strong>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="bhd-contact-card">
                        <h3><i class="fas fa-phone me-2"></i>{{ __('messages.register_contact_label') }}</h3>
                        <p>{{ __('messages.blade_profile_blade_you_can_update_your_contact_number_directly_because_thi') }}</p>

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
                                               value="{{ old('contact', preg_replace('/^0/', '', (string) $resident->contact)) }}"
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
                    </div>
                </div>

                <div class="bhd-profile-panel" id="tab-account">
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
                                <button type="button" class="bhd-btn bhd-btn-soft" onclick="document.getElementById('resendVerifyForm').submit();">
                                    <i class="fas fa-envelope"></i> {{ __('messages.blade_profile_blade_verify_email') }}
                                </button>
                            @endunless
                        </div>
                    </form>

                    <form id="resendVerifyForm" method="POST" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>

                    <hr class="my-4">

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

                    <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                        @csrf

                        <div class="bhd-form-grid">
                            <div class="bhd-form-group full">
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

                <div class="bhd-profile-panel" id="tab-requests">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-file-alt me-2"></i>{{ __('messages.profile_nav_request_history') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_all_document_requests_linked_to_your_account') }}</p>
                        </div>
                    </div>

                    <div class="bhd-four-grid mb-4">
                        <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-certificate"></i></span><h3>{{ $clearanceCount }}</h3><p>{{ __('messages.profile_counter_clearances') }}</p></article>
                        <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-house-user"></i></span><h3>{{ $residencyCount }}</h3><p>{{ __('messages.profile_counter_residency') }}</p></article>
                        <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-hands-helping"></i></span><h3>{{ $indigencyCount }}</h3><p>{{ __('messages.profile_counter_indigency') }}</p></article>
                        <article class="bhd-simple-card"><span class="bhd-card-icon"><i class="fas fa-triangle-exclamation"></i></span><h3>{{ $blotterCount }}</h3><p>{{ __('messages.blade_profile_blade_reports') }}</p></article>
                    </div>

                    @if(count($recentRequests))
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
                                            <td data-label="{{ __('messages.profile_table_ref') }}"><strong>{{ $referenceNumber }}</strong></td>
                                            <td data-label="{{ __('messages.profile_table_type') }}">
                                                <span class="bhd-request-type">
                                                    <i class="fas fa-file-alt"></i>
                                                    {{ $requestType }}
                                                </span>
                                            </td>
                                            <td data-label="{{ __('messages.profile_table_status') }}"><span class="bhd-status-pill">{{ ucfirst((string) $requestStatus) }}</span></td>
                                            <td data-label="{{ __('messages.profile_table_date') }}">{{ $displayDate }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bhd-empty-state">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>{{ __('messages.profile_history_empty') }}</p>
                            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary mt-2"><i class="fas fa-folder-open"></i> {{ __('messages.profile_request_document_link') }}</a>
                        </div>
                    @endif
                </div>

                <div class="bhd-profile-panel" id="tab-valid-id">
                    <div class="bhd-panel-head">
                        <div>
                            <h2><i class="fas fa-id-card me-2"></i>{{ __('messages.profile_nav_valid_id') }}</h2>
                            <p>{{ __('messages.blade_profile_blade_update_both_the_front_and_back_of_your_valid_id_the_sca') }}</p>
                        </div>
                    </div>

                    <div class="bhd-valid-id-grid mb-4">
                        <article class="bhd-id-side-card">
                            <div class="bhd-id-side-head">
                                <strong><i class="fas fa-id-card"></i> {{ __('messages.blade_profile_blade_current_front') }}</strong>
                                <span>{{ __('messages.blade_profile_blade_front') }}</span>
                            </div>

                            <div class="bhd-id-preview-shell">
                                @if($resident->valid_id)
                                    @php $frontExt = pathinfo($resident->valid_id, PATHINFO_EXTENSION); @endphp

                                    @if(in_array(strtolower($frontExt), ['jpg','jpeg','png','webp']))
                                        <img src="{{ asset($resident->valid_id) }}" alt="{{ __('messages.blade_profile_blade_current_valid_id_front') }}" loading="lazy">
                                    @else
                                        <div class="bhd-id-empty">
                                            <i class="fas fa-file-pdf"></i>
                                            <p class="mb-1">{{ __('messages.blade_profile_blade_front_id_file_uploaded') }}</p>
                                            <a href="{{ asset($resident->valid_id) }}" target="_blank" class="bhd-link">{{ __('messages.blade_profile_blade_open_file') }} <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    @endif
                                @else
                                    <div class="bhd-id-empty">
                                        <i class="fas fa-id-card"></i>
                                        <p class="mb-0">{{ __('messages.blade_profile_blade_no_front_id_uploaded_yet') }}</p>
                                    </div>
                                @endif
                            </div>
                        </article>

                        <article class="bhd-id-side-card">
                            <div class="bhd-id-side-head">
                                <strong><i class="fas fa-id-card-clip"></i> {{ __('messages.blade_profile_blade_current_back') }}</strong>
                                <span>{{ __('messages.blade_profile_blade_back') }}</span>
                            </div>

                            <div class="bhd-id-preview-shell">
                                @if(!empty($resident->valid_id_back))
                                    @php $backExt = pathinfo($resident->valid_id_back, PATHINFO_EXTENSION); @endphp

                                    @if(in_array(strtolower($backExt), ['jpg','jpeg','png','webp']))
                                        <img src="{{ asset($resident->valid_id_back) }}" alt="{{ __('messages.blade_profile_blade_current_valid_id_back') }}" loading="lazy">
                                    @else
                                        <div class="bhd-id-empty">
                                            <i class="fas fa-file-pdf"></i>
                                            <p class="mb-1">{{ __('messages.blade_profile_blade_back_id_file_uploaded') }}</p>
                                            <a href="{{ asset($resident->valid_id_back) }}" target="_blank" class="bhd-link">{{ __('messages.blade_profile_blade_open_file') }} <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    @endif
                                @else
                                    <div class="bhd-id-empty">
                                        <i class="fas fa-id-card-clip"></i>
                                        <p class="mb-0">{{ __('messages.blade_profile_blade_no_back_id_uploaded_yet') }}</p>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>

                    <article class="bhd-simple-card mb-3">
                        <span class="bhd-card-icon"><i class="fas fa-shield-alt"></i></span>
                        <h3>{{ __('messages.blade_profile_blade_verification_status') }}</h3>

                        @if($resident->valid_id_verified ?? false)
                            <span class="bhd-status-pill"><i class="fas fa-check-circle"></i> {{ __('messages.blade_profile_blade_verified') }}</span>
                        @else
                            <span class="bhd-status-pill"><i class="fas fa-clock"></i> {{ __('messages.blade_profile_blade_for_verification') }}</span>
                        @endif

                        @if(!empty($resident->valid_id_remarks))
                            <p class="mt-3"><strong>{{ __('messages.print_remarks_label') }}</strong> {{ $resident->valid_id_remarks }}</p>
                        @endif
                    </article>

                    @if($errors->has('valid_id') || $errors->has('valid_id_back'))
                        <div class="bhd-alert danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                @error('valid_id') <div>{{ $message }}</div> @enderror
                                @error('valid_id_back') <div>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.id') }}" enctype="multipart/form-data" id="idUploadForm">
                        @csrf

                        <div class="bhd-scan-panel">
                            <div>
                                <strong>{{ __('messages.blade_profile_blade_automatic_front_and_back_id_scan') }}</strong>
                                <span>{{ __('messages.blade_profile_blade_click_once_the_scanner_captures_the_front_side_first_th') }}</span>
                            </div>

                            <button type="button" class="bhd-btn bhd-btn-primary" id="scanBothSidesBtn">
                                <i class="fas fa-camera-retro"></i>
                                {{ __('messages.blade_profile_blade_start_id_scan') }}
                            </button>
                        </div>

                        <div class="bhd-scan-status" id="idAutoScanStatus">
                            <i class="fas fa-id-card-clip"></i>
                            <div>
                                <strong id="idAutoScanStatusTitle">{{ __('messages.blade_profile_blade_no_new_id_scan_yet') }}</strong>
                                <span id="idAutoScanStatusText">{{ __('messages.blade_profile_blade_use_the_scanner_or_manually_choose_both_front_and_back_') }}</span>
                                <span class="d-none" id="validIdFrontText">{{ __('messages.blade_profile_blade_no_front_file_selected') }}</span>
                                <span class="d-none" id="validIdBackText">{{ __('messages.blade_profile_blade_no_back_file_selected') }}</span>
                            </div>
                        </div>

                        <div class="bhd-upload-choice-grid">
                            <label class="bhd-file-choice" for="valid_id">
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

                        <div class="bhd-valid-id-grid mt-3">
                            <article class="bhd-id-side-card">
                                <div class="bhd-id-side-head">
                                    <strong><i class="fas fa-camera"></i> {{ __('messages.blade_profile_blade_new_front_preview') }}</strong>
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

                            <article class="bhd-id-side-card">
                                <div class="bhd-id-side-head">
                                    <strong><i class="fas fa-camera"></i> {{ __('messages.blade_profile_blade_new_back_preview') }}</strong>
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

                        <div class="bhd-form-actions">
                            <button type="submit" class="bhd-btn bhd-btn-primary">
                                <i class="fas fa-upload"></i>
                                {{ __('messages.blade_profile_blade_update_front_and_back_id') }}
                            </button>
                        </div>

                        <small class="text-muted d-block mt-2">
                            {{ __('messages.blade_profile_blade_jpg_png_or_webp_only_uploading_a_new_id_should_reset_the_v') }}
                        </small>
                    </form>
                </div>            </div>
        </div>
    </div>
</section>


<div class="modal fade id-scanner-modal" id="idScannerModal" tabindex="-1" aria-labelledby="idScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bhd-modal-card">
            <div class="modal-header">
                <h5 class="modal-title" id="idScannerModalLabel">{{ __('messages.blade_profile_blade_automatic_valid_id_scanner') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
            </div>

            <div class="modal-body">
                <div class="scanner-help">
                    <i class="fas fa-lightbulb"></i>
                    <span>{{ __('messages.blade_profile_blade_place_the_id_inside_the_guide_the_scanner_captures_the_') }}</span>
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
                        {{ __('messages.blade_profile_blade_capture_now') }}
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


@php
    $profileCorrectionRoute = \Illuminate\Support\Facades\Route::has('profile.reportIncorrectProfile')
        ? route('profile.reportIncorrectProfile')
        : null;

    $profileCorrectionFields = [
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
        ['value' => 'birth_place', 'label' => 'Place of Birth', 'current' => $resident->birth_place ?? ''],
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#profileTabs .bhd-profile-tab');
    const panels = document.querySelectorAll('.bhd-profile-panel');

    function activateTab(tabId) {
        panels.forEach(panel => panel.classList.remove('active'));
        tabs.forEach(tab => tab.classList.remove('active'));

        const targetPanel = document.getElementById('tab-' + tabId);
        const targetTab = document.querySelector('.bhd-profile-tab[data-tab="' + tabId + '"]');

        if (targetPanel) targetPanel.classList.add('active');
        if (targetTab) targetTab.classList.add('active');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            activateTab(tab.dataset.tab);
        });
    });

    @if($errors->hasAny(['username','email','current_password','new_password']))
        activateTab('account');
    @elseif($errors->hasAny(['contact','field_name','correct_value','message']))
        activateTab('info');
    @elseif($errors->has('valid_id') || $errors->has('valid_id_back'))
        activateTab('valid-id');
    @elseif(session('open_tab'))
        activateTab('{{ session("open_tab") }}');
    @endif


    const correctionFieldSelect = document.getElementById('correctionFieldSelect');
    const correctionCurrentValuePreview = document.getElementById('correctionCurrentValuePreview');
    const correctionCurrentValueHidden = document.getElementById('correctionCurrentValueHidden');

    function syncCorrectionCurrentValue() {
        if (!correctionFieldSelect || !correctionCurrentValuePreview || !correctionCurrentValueHidden) return;

        const selectedOption = correctionFieldSelect.options[correctionFieldSelect.selectedIndex];
        const currentValue = selectedOption ? (selectedOption.dataset.current || '') : '';

        correctionCurrentValuePreview.value = currentValue || @json(__('messages.blade_announcement_blade_n_a'));
        correctionCurrentValueHidden.value = currentValue;
    }

    if (correctionFieldSelect) {
        correctionFieldSelect.addEventListener('change', syncCorrectionCurrentValue);
        syncCorrectionCurrentValue();
    }


    @if($errors->hasAny(['field_name','correct_value','message']))
        const correctionModalElement = document.getElementById('profileCorrectionModal');
        if (correctionModalElement && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(correctionModalElement).show();
        }
    @endif

    const quickInput = document.getElementById('quickPhotoInput');
    const quickForm = document.getElementById('quickPhotoForm');

    if (quickInput && quickForm) {
        quickInput.addEventListener('change', function () {
            if (!quickInput.files.length) return;

            if (quickInput.files[0].size > 5 * 1024 * 1024) {
                alert(@json(__('messages.blade_profile_blade_profile_photo_must_not_exceed_5mb')));
                quickInput.value = '';
                return;
            }

            quickForm.submit();
        });
    }

    document.querySelectorAll('.bhd-toggle-pw').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;

            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('i').classList.toggle('fa-eye', showing);
            btn.querySelector('i').classList.toggle('fa-eye-slash', !showing);
        });
    });

    const newPwInput = document.getElementById('newPw');
    const confirmPwInput = document.getElementById('confirmPw');
    const fill = document.getElementById('pwMeterFill');
    const strengthHint = document.getElementById('pwStrengthHint');
    const matchHint = document.getElementById('pwMatchHint');

    function evalStrength(password) {
        let score = 0;

        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        if (!password) return { width: 0, label: '', color: '#e9ecef' };
        if (score <= 2) return { width: 30, label: @json(__('messages.blade_runtime_strength_weak')), color: '#ef4444' };
        if (score <= 3) return { width: 60, label: @json(__('messages.blade_runtime_strength_fair')), color: '#f59e0b' };
        if (score <= 4) return { width: 82, label: @json(__('messages.blade_runtime_strength_good')), color: '#3b82f6' };
        return { width: 100, label: @json(__('messages.blade_runtime_strength_strong')), color: '#22c55e' };
    }

    function checkMatch() {
        if (!newPwInput || !confirmPwInput || !matchHint) return;

        if (!confirmPwInput.value) {
            matchHint.textContent = '';
            return;
        }

        const ok = newPwInput.value === confirmPwInput.value;
        matchHint.textContent = ok ? `✓ ${@json(__('messages.blade_runtime_passwords_match'))}` : `✗ ${@json(__('messages.blade_runtime_passwords_do_not_match'))}`;
        matchHint.style.color = ok ? '#22c55e' : '#ef4444';
    }

    if (newPwInput && fill) {
        newPwInput.addEventListener('input', function () {
            newPwInput.value = newPwInput.value.replace(/\s/g, '');
            const result = evalStrength(newPwInput.value);
            fill.style.width = result.width + '%';
            fill.style.background = result.color;
            strengthHint.textContent = result.label ? @json(__('messages.blade_runtime_strength_value')).replace(':strength', result.label) : @json(__('messages.profile_password_strength_label'));
            strengthHint.style.color = result.color !== '#e9ecef' ? result.color : '#888';
            checkMatch();
        });
    }

    if (confirmPwInput) {
        confirmPwInput.addEventListener('input', function () {
            confirmPwInput.value = confirmPwInput.value.replace(/\s/g, '');
            checkMatch();
        });
    }

    const contactInput = document.getElementById('contactInput');

    if (contactInput) {
        if (/^0/.test(contactInput.value)) {
            contactInput.value = contactInput.value.slice(1);
        }

        contactInput.addEventListener('input', function () {
            let value = contactInput.value.replace(/\D/g, '');

            if (value.startsWith('0')) value = value.slice(1);
            contactInput.value = value.slice(0, 10);
        });

        const infoForm = document.getElementById('infoForm');

        if (infoForm) {
            infoForm.addEventListener('submit', function () {
                if (contactInput.value.length === 10) {
                    contactInput.value = '0' + contactInput.value;
                }
            });
        }
    }

    const usernameInput = document.querySelector('input[name="username"]');

    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            usernameInput.value = usernameInput.value.replace(/\s/g, '');
        });
    }

    document.querySelectorAll('.bhd-alert').forEach(alert => {
        setTimeout(function () {
            alert.style.transition = 'opacity .5s ease';
            alert.style.opacity = '0';

            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>

<script>
    window.cvReady = false;
    window.onOpenCvReady = function () {
        window.cvReady = true;
        document.dispatchEvent(new Event('opencv-ready'));
    };
</script>
<script async src="https://docs.opencv.org/4.x/opencv.js" onload="window.onOpenCvReady()" onerror="window.cvReady = false;"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scannerModalElement = document.getElementById('idScannerModal');
    const scannerModal = scannerModalElement ? new bootstrap.Modal(scannerModalElement) : null;
    const scannerVideo = document.getElementById('idScannerVideo');
    const scannerOverlay = document.getElementById('idScannerOverlay');
    const scannerWorkCanvas = document.getElementById('idScannerWorkCanvas');
    const scannerResultCanvas = document.getElementById('idScannerResultCanvas');
    const scannerStatus = document.getElementById('idScannerStatus');
    const scannerStatusText = document.getElementById('idScannerStatusText');
    const scannerTargetText = document.getElementById('idScannerTargetText');
    const scannerRetakeBtn = document.getElementById('idScannerRetakeBtn');
    const scannerManualCaptureBtn = document.getElementById('idScannerManualCaptureBtn');
    const scanBothSidesBtn = document.getElementById('scanBothSidesBtn');
    const idUploadForm = document.getElementById('idUploadForm');

    const scannerSides = [
        { inputId: 'valid_id', label: 'front side', display: @json(__('messages.blade_profile_blade_front_side')) },
        { inputId: 'valid_id_back', label: 'back side', display: @json(__('messages.blade_profile_blade_back_side')) },
    ];

    const scannerState = {
        stream: null,
        rafId: null,
        sideIndex: 0,
        detectedPoints: null,
        lastPoints: null,
        stableFrames: 0,
        isCapturing: false,
        waitingForFlip: false,
        signatures: {},
        quality: {},
        completed: false,
    };

    function setScannerStatus(message, state = 'waiting') {
        if (scannerStatusText) scannerStatusText.textContent = message;
        if (!scannerStatus) return;

        scannerStatus.classList.toggle('is-ready', state === 'ready');
        scannerStatus.classList.toggle('is-error', state === 'error');
    }

    function updateScanStatus() {
        const front = document.getElementById('valid_id');
        const back = document.getElementById('valid_id_back');
        const status = document.getElementById('idAutoScanStatus');
        const title = document.getElementById('idAutoScanStatusTitle');
        const text = document.getElementById('idAutoScanStatusText');
        const hasFront = !!front?.files?.length;
        const hasBack = !!back?.files?.length;

        if (status) status.classList.toggle('is-complete', hasFront && hasBack);

        if (!title || !text) return;

        if (hasFront && hasBack) {
            title.textContent = @json(__('messages.blade_profile_blade_new_front_and_back_id_ready'));
            text.textContent = @json(__('messages.blade_profile_blade_both_sides_are_attached_you_can_now_update_your_valid_i'));
        } else if (hasFront) {
            title.textContent = @json(__('messages.blade_profile_blade_front_side_ready'));
            text.textContent = @json(__('messages.blade_profile_blade_front_side_is_attached_scan_or_choose_the_back_side_to_'));
        } else if (hasBack) {
            title.textContent = @json(__('messages.blade_profile_blade_back_side_ready'));
            text.textContent = @json(__('messages.blade_profile_blade_back_side_is_attached_scan_or_choose_the_front_side_to_'));
        } else {
            title.textContent = @json(__('messages.blade_profile_blade_no_new_id_scan_yet'));
            text.textContent = @json(__('messages.blade_profile_blade_use_the_scanner_or_manually_choose_both_front_and_back_'));
        }

        if (scanBothSidesBtn) {
            scanBothSidesBtn.innerHTML = hasFront && hasBack
                ? `<i class="fas fa-rotate-right"></i> ${@json(__('messages.blade_runtime_rescan_id'))}`
                : `<i class="fas fa-camera-retro"></i> ${@json(__('messages.blade_profile_blade_start_id_scan'))}`;
        }
    }

    function updateSelectedFilePreview(input, file) {
        const textId = input.getAttribute('data-file-text');
        const textEl = textId ? document.getElementById(textId) : null;

        if (textEl) {
            textEl.textContent = file ? file.name : (input.id === 'valid_id_back' ? @json(__('messages.blade_profile_blade_no_back_image_selected')) : @json(__('messages.blade_profile_blade_no_front_image_selected')));
        }

        const previewId = input.getAttribute('data-preview');
        const shellId = input.getAttribute('data-preview-shell');
        const preview = previewId ? document.getElementById(previewId) : null;
        const shell = shellId ? document.getElementById(shellId) : null;

        if (!preview || !shell) return;

        if (!file || !file.type.startsWith('image/')) {
            preview.removeAttribute('src');
            preview.classList.add('d-none');
            shell.querySelector('.bhd-id-empty')?.classList.remove('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
            preview.classList.remove('d-none');
            shell.querySelector('.bhd-id-empty')?.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    document.querySelectorAll('#idUploadForm input[type="file"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = input.files?.[0] || null;

            if (file && file.size > 5 * 1024 * 1024) {
                alert(@json(__('messages.blade_profile_blade_id_image_must_not_exceed_5mb')));
                input.value = '';
                updateSelectedFilePreview(input, null);
                updateScanStatus();
                return;
            }

            if (file && !file.type.startsWith('image/')) {
                alert(@json(__('messages.blade_profile_blade_please_choose_an_image_file_only')));
                input.value = '';
                updateSelectedFilePreview(input, null);
                updateScanStatus();
                return;
            }

            updateSelectedFilePreview(input, file);
            updateScanStatus();
        });
    });

    if (idUploadForm) {
        idUploadForm.addEventListener('submit', function (event) {
            const front = document.getElementById('valid_id');
            const back = document.getElementById('valid_id_back');

            if (!front?.files?.length || !back?.files?.length) {
                event.preventDefault();
                alert(@json(__('messages.blade_profile_blade_please_attach_both_front_and_back_valid_id_images_befor')));
                return;
            }
        });
    }

    function resetScannerDetection() {
        scannerState.detectedPoints = null;
        scannerState.lastPoints = null;
        scannerState.stableFrames = 0;

        if (scannerOverlay) {
            const ctx = scannerOverlay.getContext('2d');
            ctx.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
        }
    }

    function orderPoints(points) {
        const sorted = points.slice().sort((a, b) => a.x - b.x);
        const left = sorted.slice(0, 2).sort((a, b) => a.y - b.y);
        const right = sorted.slice(2).sort((a, b) => a.y - b.y);

        return [left[0], right[0], right[1], left[1]];
    }

    function distance(a, b) {
        return Math.hypot(a.x - b.x, a.y - b.y);
    }

    function arePointsStable(current, previous) {
        if (!current || !previous) return false;

        const a = orderPoints(current);
        const b = orderPoints(previous);
        const movement = a.reduce((sum, point, index) => sum + distance(point, b[index]), 0) / 4;

        return movement < 42;
    }

    function syncScannerCanvases() {
        if (!scannerVideo || !scannerOverlay || !scannerWorkCanvas) return;

        const width = scannerVideo.videoWidth || 1280;
        const height = scannerVideo.videoHeight || 720;

        [scannerOverlay, scannerWorkCanvas].forEach(function (canvas) {
            if (canvas.width !== width || canvas.height !== height) {
                canvas.width = width;
                canvas.height = height;
            }
        });
    }

    function drawOverlay(points) {
        if (!scannerOverlay || !scannerVideo) return;

        syncScannerCanvases();
        const ctx = scannerOverlay.getContext('2d');
        ctx.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);

        if (!points || points.length !== 4) return;

        const ordered = orderPoints(points);
        ctx.lineWidth = Math.max(4, scannerOverlay.width * 0.004);
        ctx.strokeStyle = '#22c55e';
        ctx.fillStyle = 'rgba(34, 197, 94, 0.12)';
        ctx.beginPath();
        ctx.moveTo(ordered[0].x, ordered[0].y);
        ordered.slice(1).forEach(point => ctx.lineTo(point.x, point.y));
        ctx.closePath();
        ctx.fill();
        ctx.stroke();
    }

    function getCardPoints() {
        if (!window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas) return null;

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        let src = null;
        let gray = null;
        let blur = null;
        let edges = null;
        let contours = null;
        let hierarchy = null;

        try {
            src = cv.imread(scannerWorkCanvas);
            gray = new cv.Mat();
            blur = new cv.Mat();
            edges = new cv.Mat();
            contours = new cv.MatVector();
            hierarchy = new cv.Mat();

            cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
            cv.GaussianBlur(gray, blur, new cv.Size(5, 5), 0);
            cv.Canny(blur, edges, 70, 180);
            cv.dilate(edges, edges, cv.Mat.ones(3, 3, cv.CV_8U));
            cv.findContours(edges, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);

            let bestPoints = null;
            let bestArea = 0;
            const frameArea = src.rows * src.cols;
            const minimumArea = frameArea * 0.055;

            for (let i = 0; i < contours.size(); i++) {
                const contour = contours.get(i);
                const area = cv.contourArea(contour);

                if (area < minimumArea) {
                    contour.delete();
                    continue;
                }

                const perimeter = cv.arcLength(contour, true);
                const approx = new cv.Mat();
                cv.approxPolyDP(contour, approx, 0.025 * perimeter, true);

                if (approx.rows === 4 && cv.isContourConvex(approx)) {
                    const points = [];

                    for (let row = 0; row < 4; row++) {
                        points.push({
                            x: approx.data32S[row * 2],
                            y: approx.data32S[row * 2 + 1],
                        });
                    }

                    const ordered = orderPoints(points);
                    const width = Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2]));
                    const height = Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2]));
                    const ratio = width / Math.max(height, 1);

                    if (ratio >= 1.2 && ratio <= 2.35 && area > bestArea) {
                        bestArea = area;
                        bestPoints = points;
                    }
                }

                approx.delete();
                contour.delete();
            }

            return bestPoints;
        } catch (error) {
            console.warn('ID scanner detection failed:', error);
            return null;
        } finally {
            if (src) src.delete();
            if (gray) gray.delete();
            if (blur) blur.delete();
            if (edges) edges.delete();
            if (contours) contours.delete();
            if (hierarchy) hierarchy.delete();
        }
    }

    function cropGuideFallback() {
        if (!scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) return null;

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        const w = scannerWorkCanvas.width;
        const h = scannerWorkCanvas.height;
        const x = Math.round(w * 0.12);
        const y = Math.round(h * 0.23);
        const cropW = Math.round(w * 0.76);
        const cropH = Math.round(h * 0.54);

        scannerResultCanvas.width = cropW;
        scannerResultCanvas.height = cropH;
        scannerResultCanvas.getContext('2d').drawImage(scannerWorkCanvas, x, y, cropW, cropH, 0, 0, cropW, cropH);

        return scannerResultCanvas;
    }

    function cropDetectedCard(points) {
        if (!points || !window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) {
            return cropGuideFallback();
        }

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        const ordered = orderPoints(points);
        const outputWidth = Math.round(Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2])));
        const outputHeight = Math.round(Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2])));

        let src = null;
        let dst = null;
        let transform = null;
        let sourcePoints = null;
        let destinationPoints = null;

        try {
            src = cv.imread(scannerWorkCanvas);
            dst = new cv.Mat();
            sourcePoints = cv.matFromArray(4, 1, cv.CV_32FC2, [
                ordered[0].x, ordered[0].y,
                ordered[1].x, ordered[1].y,
                ordered[2].x, ordered[2].y,
                ordered[3].x, ordered[3].y,
            ]);
            destinationPoints = cv.matFromArray(4, 1, cv.CV_32FC2, [
                0, 0,
                outputWidth - 1, 0,
                outputWidth - 1, outputHeight - 1,
                0, outputHeight - 1,
            ]);

            transform = cv.getPerspectiveTransform(sourcePoints, destinationPoints);
            cv.warpPerspective(src, dst, transform, new cv.Size(outputWidth, outputHeight));

            scannerResultCanvas.width = outputWidth;
            scannerResultCanvas.height = outputHeight;
            cv.imshow(scannerResultCanvas, dst);

            return scannerResultCanvas;
        } catch (error) {
            console.warn('ID scanner crop failed:', error);
            return cropGuideFallback();
        } finally {
            if (src) src.delete();
            if (dst) dst.delete();
            if (transform) transform.delete();
            if (sourcePoints) sourcePoints.delete();
            if (destinationPoints) destinationPoints.delete();
        }
    }

    function analyzeCanvas(canvas) {
        const width = Math.min(640, canvas.width || 1);
        const height = Math.max(1, Math.round(width * ((canvas.height || 1) / (canvas.width || 1))));
        const sample = document.createElement('canvas');
        sample.width = width;
        sample.height = height;

        const ctx = sample.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(canvas, 0, 0, width, height);

        const data = ctx.getImageData(0, 0, width, height).data;
        const gray = new Float32Array(width * height);
        let total = 0;
        let dark = 0;
        let bright = 0;

        for (let i = 0, p = 0; i < data.length; i += 4, p++) {
            const value = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
            gray[p] = value;
            total += value;
            if (value < 35) dark++;
            if (value > 245) bright++;
        }

        const mean = total / gray.length;
        let variance = 0;
        let edgeTotal = 0;
        let edgeCount = 0;

        for (let y = 1; y < height - 1; y++) {
            for (let x = 1; x < width - 1; x++) {
                const idx = y * width + x;
                variance += Math.pow(gray[idx] - mean, 2);

                const gx = Math.abs(gray[idx - 1] - gray[idx + 1]);
                const gy = Math.abs(gray[idx - width] - gray[idx + width]);
                const edge = gx + gy;
                edgeTotal += edge;
                if (edge > 28) edgeCount++;
            }
        }

        const usablePixels = Math.max(1, (width - 2) * (height - 2));

        return {
            brightness: mean,
            contrast: Math.sqrt(variance / usablePixels),
            sharpness: edgeTotal / usablePixels,
            edgeDensity: edgeCount / usablePixels,
            darkRatio: dark / gray.length,
            brightRatio: bright / gray.length,
        };
    }

    function canvasSignature(canvas) {
        const size = 32;
        const sig = document.createElement('canvas');
        sig.width = size;
        sig.height = size;

        const ctx = sig.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(canvas, 0, 0, size, size);

        const data = ctx.getImageData(0, 0, size, size).data;
        const pixels = [];

        for (let i = 0; i < data.length; i += 4) {
            pixels.push((data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114) / 255);
        }

        return pixels;
    }

    function signatureDifference(a, b) {
        if (!a || !b || a.length !== b.length) return 1;

        let diff = 0;
        for (let i = 0; i < a.length; i++) {
            diff += Math.abs(a[i] - b[i]);
        }

        return diff / a.length;
    }

    function validateCapture(canvas, points, inputId) {
        if (!canvas) {
            return { passed: false, message: @json(__('messages.blade_js_capture_failed_please_try_again')) };
        }

        if (points) {
            const ordered = orderPoints(points);
            const width = Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2]));
            const height = Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2]));
            const ratio = Math.max(width, height) / Math.max(1, Math.min(width, height));

            if (ratio < 1.2 || ratio > 2.35) {
                return { passed: false, message: @json(__('messages.blade_js_the_detected_shape_does_not_look_like_an_id_card_align_the')) };
            }
        }

        const analysis = analyzeCanvas(canvas);

        if (analysis.brightness < 42) return { passed: false, message: @json(__('messages.blade_js_the_id_photo_is_too_dark_move_to_better_lighting')) };
        if (analysis.brightness > 238) return { passed: false, message: @json(__('messages.blade_js_the_id_photo_is_too_bright_or_has_glare')) };
        if (analysis.contrast < 16) return { passed: false, message: @json(__('messages.blade_runtime_id_details_unclear')) };
        if (analysis.edgeDensity < 0.010 || analysis.sharpness < 7) return { passed: false, message: @json(__('messages.blade_js_the_id_photo_is_blurry_hold_steady_and_scan_again')) };
        if (analysis.darkRatio > 0.42) return { passed: false, message: @json(__('messages.blade_js_too_much_of_the_photo_is_dark_move_to_better_lighting')) };
        if (analysis.brightRatio > 0.34) return { passed: false, message: @json(__('messages.blade_js_too_much_glare_was_detected_tilt_the_id_slightly_away_from')) };

        const signature = canvasSignature(canvas);

        if (inputId === 'valid_id_back' && scannerState.signatures.valid_id) {
            const diff = signatureDifference(scannerState.signatures.valid_id, signature);

            if (diff < 0.075) {
                return { passed: false, message: @json(__('messages.blade_js_this_looks_like_the_front_side_again_flip_the_id_and_scan_')) };
            }
        }

        return { passed: true, message: @json(__('messages.blade_js_quality_passed')), signature };
    }

    function attachCanvasToInput(canvas, inputId, quality) {
        const input = document.getElementById(inputId);
        if (!input || !canvas) return;

        canvas.toBlob(function (blob) {
            if (!blob) {
                setScannerStatus(@json(__('messages.blade_js_capture_failed_please_try_again')), 'error');
                scannerState.isCapturing = false;
                return;
            }

            const file = new File([blob], inputId === 'valid_id_back'
                ? `valid_id_back_scan_${Date.now()}.jpg`
                : `valid_id_front_scan_${Date.now()}.jpg`, { type: 'image/jpeg' });

            const transfer = new DataTransfer();
            transfer.items.add(file);
            input.files = transfer.files;
            scannerState.quality[inputId] = quality;
            scannerState.signatures[inputId] = quality.signature;
            input.dispatchEvent(new Event('change', { bubbles: true }));

            if (inputId === 'valid_id') {
                scannerState.waitingForFlip = true;
                setScannerStatus(@json(__('messages.blade_js_front_side_captured_flip_the_id_now_back_side_scanning_sta')), 'ready');

                window.setTimeout(function () {
                    scannerState.sideIndex = 1;
                    scannerState.waitingForFlip = false;
                    scannerState.isCapturing = false;
                    resetScannerDetection();

                    if (scannerTargetText) scannerTargetText.textContent = @json(__('messages.blade_profile_blade_back_side'));
                    setScannerStatus(@json(__('messages.blade_js_now_scanning_back_side_show_the_back_of_the_id_inside_the_')), 'waiting');
                    scannerLoop();
                }, 3000);

                return;
            }

            scannerState.completed = true;
            setScannerStatus(@json(__('messages.blade_js_front_and_back_sides_captured_successfully')), 'ready');
            updateScanStatus();

            window.setTimeout(function () {
                if (scannerModal) scannerModal.hide();
            }, 800);

            scannerState.isCapturing = false;
        }, 'image/jpeg', 0.94);
    }

    function captureScannerFrame(force = false) {
        if (scannerState.isCapturing || scannerState.waitingForFlip) return;

        const side = scannerSides[scannerState.sideIndex] || scannerSides[0];
        scannerState.isCapturing = true;

        const canvas = cropDetectedCard(scannerState.detectedPoints);
        const quality = validateCapture(canvas, scannerState.detectedPoints, side.inputId);

        if (!quality.passed) {
            setScannerStatus(@json(__('messages.blade_profile_scanner_side_failed_message')).replace(':side', side.display).replace(':message', quality.message), 'error');
            scannerState.isCapturing = false;

            window.setTimeout(function () {
                setScannerStatus(@json(__('messages.blade_runtime_place_side_clear')).replace(':side', side.display), 'waiting');
            }, 1600);

            return;
        }

        setScannerStatus(@json(__('messages.blade_profile_scanner_side_quality_passed_saving_image')).replace(':side', side.display), 'ready');
        attachCanvasToInput(canvas, side.inputId, quality);
    }

    function scannerLoop() {
        if (!scannerState.stream || !scannerVideo || scannerState.isCapturing || scannerState.completed) return;

        scannerState.rafId = requestAnimationFrame(scannerLoop);

        if (scannerVideo.readyState < 2) return;

        const side = scannerSides[scannerState.sideIndex] || scannerSides[0];
        const points = getCardPoints();

        drawOverlay(points);

        if (points) {
            const stable = arePointsStable(points, scannerState.lastPoints);
            scannerState.stableFrames = stable ? scannerState.stableFrames + 1 : 1;
            scannerState.lastPoints = points;
            scannerState.detectedPoints = points;

            setScannerStatus(@json(__('messages.blade_runtime_side_detected_hold')).replace(':side', side.display).replace(':progress', Math.min(scannerState.stableFrames, 9)), 'ready');

            if (scannerState.stableFrames >= 9) {
                captureScannerFrame();
            }
        } else {
            scannerState.stableFrames = 0;
            scannerState.lastPoints = null;
            scannerState.detectedPoints = null;

            if (!window.cvReady || typeof cv === 'undefined') {
                setScannerStatus(@json(__('messages.blade_runtime_scanner_engine_capture')), 'waiting');
            } else {
                setScannerStatus(@json(__('messages.blade_runtime_place_side')).replace(':side', side.display), 'waiting');
            }
        }
    }

    function stopScannerCamera() {
        if (scannerState.rafId) {
            cancelAnimationFrame(scannerState.rafId);
            scannerState.rafId = null;
        }

        if (scannerState.stream) {
            scannerState.stream.getTracks().forEach(track => track.stop());
            scannerState.stream = null;
        }

        if (scannerVideo) scannerVideo.srcObject = null;

        resetScannerDetection();
        scannerState.isCapturing = false;
        scannerState.waitingForFlip = false;
    }

    async function startScannerCamera() {
        if (!scannerVideo || !navigator.mediaDevices?.getUserMedia) {
            setScannerStatus(@json(__('messages.blade_js_camera_is_not_supported_by_this_browser_please_choose_imag')), 'error');
            return;
        }

        stopScannerCamera();
        scannerState.completed = false;
        scannerState.sideIndex = 0;
        scannerState.quality = {};
        scannerState.signatures = {};

        if (scannerTargetText) scannerTargetText.textContent = @json(__('messages.blade_profile_blade_front_side'));

        setScannerStatus(@json(__('messages.blade_profile_blade_camera_is_starting')), 'waiting');

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1920 },
                    height: { ideal: 1080 },
                },
            });

            scannerState.stream = stream;
            scannerVideo.srcObject = stream;
            await scannerVideo.play();
            syncScannerCanvases();
            setScannerStatus(@json(__('messages.blade_js_scanning_front_side_first_put_the_photo_name_side_inside_t')), 'waiting');
            scannerLoop();
        } catch (error) {
            console.warn('Camera access failed:', error);
            setScannerStatus(@json(__('messages.blade_js_camera_permission_denied_or_unavailable_please_choose_imag')), 'error');
        }
    }

    if (scanBothSidesBtn) {
        scanBothSidesBtn.addEventListener('click', function () {
            if (!scannerModal) return;

            const front = document.getElementById('valid_id');
            const back = document.getElementById('valid_id_back');

            if (front) front.value = '';
            if (back) back.value = '';
            updateSelectedFilePreview(front, null);
            updateSelectedFilePreview(back, null);
            updateScanStatus();

            scannerModal.show();
        });
    }

    if (scannerModalElement) {
        scannerModalElement.addEventListener('shown.bs.modal', startScannerCamera);
        scannerModalElement.addEventListener('hidden.bs.modal', stopScannerCamera);
    }

    if (scannerRetakeBtn) {
        scannerRetakeBtn.addEventListener('click', startScannerCamera);
    }

    if (scannerManualCaptureBtn) {
        scannerManualCaptureBtn.addEventListener('click', function () {
            captureScannerFrame(true);
        });
    }

    document.addEventListener('opencv-ready', function () {
        if (scannerState.stream && !scannerState.isCapturing) {
            setScannerStatus(@json(__('messages.blade_js_scanner_ready_place_the_id_inside_the_guide')), 'waiting');
        }
    });

    updateScanStatus();
});
</script>

@endpush

@include('barangay_system.partials.public_layout_end')
