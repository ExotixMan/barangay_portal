<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.blade_success_service_application_submitted', ['service' => $service]) }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        :root {
            --bg: #f7f4ef;
            --surface: #fffdf9;
            --surface-soft: #f1ece4;
            --text: #1e1c1b;
            --text-soft: #57524d;
            --muted: #827a72;
            --line: #e3dbcf;
            --line-strong: #d1c6b7;
            --accent: #9f2f26;
            --accent-dark: #7d211a;
            --accent-soft: #f7e7e3;
            --success: #1f7a4d;
            --success-soft: #e7f5ee;
            --warning-soft: #fff4db;
            --shadow: 0 24px 60px rgba(42, 31, 25, .10);
            --radius: 24px;
        }

        html.dark body,
        body.dark,
        body.dark-mode,
        [data-theme="dark"] body,
        [data-bs-theme="dark"] body {
            --bg: #12100f;
            --surface: #1c1a18;
            --surface-soft: #24211f;
            --text: #f7f2ec;
            --text-soft: #d3cbc2;
            --muted: #a99f95;
            --line: rgba(255,255,255,.10);
            --line-strong: rgba(255,255,255,.18);
            --accent: #ff877d;
            --accent-dark: #ffa39c;
            --accent-soft: rgba(255,135,125,.12);
            --success: #75dda9;
            --success-soft: rgba(117,221,169,.13);
            --warning-soft: rgba(255,193,7,.12);
            --shadow: 0 24px 60px rgba(0,0,0,.35);
        }

        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(159,47,38,.11), transparent 32%),
                linear-gradient(180deg, var(--bg), var(--surface-soft));
            line-height: 1.55;
        }
        a { color: inherit; text-decoration: none; }
        .page-shell { width: min(1100px, calc(100% - 32px)); margin: 0 auto; }
        .skip-link {
            position: absolute; top: -60px; left: 16px; z-index: 1000;
            background: var(--text); color: var(--surface); padding: 10px 14px;
            border-radius: 999px; font-weight: 700;
        }
        .skip-link:focus { top: 16px; }

        .topbar {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .brand-logo {
            width: 42px; height: 42px;
            border-radius: 14px;
            background: var(--surface);
            border: 1px solid var(--line);
            display: grid;
            place-items: center;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,.06);
            flex: 0 0 auto;
        }
        .brand-logo img { width: 33px; height: 33px; object-fit: contain; }
        .brand-text strong { display: block; font-size: .92rem; font-weight: 800; line-height: 1.1; }
        .brand-text span { display: block; color: var(--muted); font-size: .76rem; font-weight: 600; margin-top: 2px; }
        .main-layout {
            min-height: calc(100vh - 74px);
            display: grid;
            align-items: center;
            padding: 22px 0 50px;
        }
        .success-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            gap: 18px;
            align-items: stretch;
        }
        .success-card,
        .side-card {
            background: color-mix(in srgb, var(--surface) 96%, transparent);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .success-card {
            padding: clamp(24px, 4vw, 42px);
        }
        .status-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
        }
        .status-icon {
            width: 58px; height: 58px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: var(--success-soft);
            border: 1px solid color-mix(in srgb, var(--success) 28%, transparent);
            color: var(--success);
            font-size: 1.55rem;
            flex: 0 0 auto;
        }
        .status-kicker {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--success);
            font-size: .75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .status-row p { margin: 4px 0 0; color: var(--muted); font-size: .9rem; font-weight: 600; }
        .title {
            max-width: 680px;
            margin: 0;
            font-size: clamp(1.85rem, 4vw, 3rem);
            letter-spacing: -.045em;
            line-height: 1.05;
            font-weight: 850;
        }
        .lead {
            max-width: 660px;
            margin: 16px 0 0;
            color: var(--text-soft);
            font-size: 1rem;
        }

        .reference-panel {
            position: relative;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: center;
            margin: 28px 0 22px;
            padding: 18px;
            border: 1px dashed var(--line-strong);
            border-radius: 20px;
            background: linear-gradient(135deg, var(--surface-soft), var(--surface));
        }
        .ref-label {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 5px;
        }
        .reference-number {
            display: block;
            color: var(--accent);
            font-size: clamp(1.35rem, 4vw, 2.1rem);
            font-weight: 900;
            letter-spacing: -.02em;
            word-break: break-word;
        }
        .copy-ref {
            border: 1px solid var(--line-strong);
            background: var(--surface);
            color: var(--text-soft);
            border-radius: 999px;
            padding: 10px 13px;
            font-weight: 850;
            font-size: .84rem;
            cursor: pointer;
            white-space: nowrap;
        }
        .copy-ref:hover { color: var(--accent); border-color: var(--accent); background: var(--accent-soft); }

        .details-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 0 0 24px;
        }
        .mini-detail {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 14px;
            background: color-mix(in srgb, var(--surface) 80%, var(--surface-soft));
            min-width: 0;
        }
        .mini-detail span {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 4px;
        }
        .mini-detail strong {
            display: block;
            color: var(--text);
            font-size: .9rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 4px;
        }
        .action-btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: .9rem;
            font-weight: 900;
            cursor: pointer;
            transition: .15s ease;
        }
        .action-primary { background: var(--accent); color: #fff; }
        .action-primary:hover { background: var(--accent-dark); color: #fff; transform: translateY(-1px); }
        .action-secondary { background: var(--surface); color: var(--text-soft); border-color: var(--line-strong); }
        .action-secondary:hover { color: var(--accent); border-color: var(--accent); background: var(--accent-soft); transform: translateY(-1px); }

        .side-card {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .side-section {
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px;
            background: color-mix(in srgb, var(--surface-soft) 52%, var(--surface));
        }
        .side-section h2 {
            margin: 0 0 12px;
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: -.02em;
        }
        .status-pill {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--warning-soft);
            border: 1px solid color-mix(in srgb, var(--accent) 18%, transparent);
            color: var(--accent);
            font-size: .8rem;
            font-weight: 900;
        }
        .timeline { display: grid; gap: 14px; }
        .timeline-item {
            display: grid;
            grid-template-columns: 30px 1fr;
            gap: 10px;
            position: relative;
        }
        .timeline-item:not(:last-child)::after {
            content: "";
            position: absolute;
            left: 14px;
            top: 32px;
            bottom: -13px;
            border-left: 1px dashed var(--line-strong);
        }
        .timeline-dot {
            width: 30px; height: 30px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: .8rem;
            font-weight: 900;
            position: relative;
            z-index: 1;
        }
        .timeline-item.done .timeline-dot { background: var(--success-soft); color: var(--success); }
        .timeline-item strong { display: block; font-size: .9rem; margin-bottom: 2px; }
        .timeline-item span { color: var(--muted); font-size: .8rem; }
        .help-text { color: var(--muted); font-size: .82rem; margin: 0; }
        .help-text strong { color: var(--text); }
        .page-footer { padding: 0 0 28px; color: var(--muted); text-align: center; font-size: .8rem; }
        .toast-copy {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 2000;
            padding: 11px 14px;
            border-radius: 999px;
            background: var(--text);
            color: var(--surface);
            font-weight: 800;
            font-size: .84rem;
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: .18s ease;
        }
        .toast-copy.show { opacity: 1; transform: translateY(0); }

        @media (max-width: 940px) {
            .main-layout { align-items: start; padding-top: 14px; }
            .success-grid { grid-template-columns: 1fr; }
            .side-card { box-shadow: none; }
        }
        @media (max-width: 680px) {
            .page-shell { width: min(100% - 24px, 1100px); }
            .brand-text span { display: none; }
            .success-card, .side-card { border-radius: 20px; }
            .reference-panel { grid-template-columns: 1fr; }
            .copy-ref { width: 100%; }
            .details-list { grid-template-columns: 1fr; }
            .actions { display: grid; }
            .action-btn { width: 100%; }
        }
        @media print {
            .topbar, .actions, .page-footer, .toast-copy { display: none !important; }
            body { background: #fff; }
            .main-layout { min-height: auto; padding: 0; }
            .success-grid { display: block; }
            .success-card, .side-card { box-shadow: none; border: 1px solid #ddd; margin-bottom: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
@php
    $submittedDateText = $date_submitted ?? now()->format('M d, Y');
    $feeLabelText = session('fee_label', '');
@endphp

<header class="page-shell topbar">
    <a href="{{ route('barangay_system.index') }}" class="brand" aria-label="{{ __('messages.blade_incident_success_blade_go_to_homepage') }}">
        <span class="brand-logo"><img src="{{ asset('Images/logo.png') }}" alt="{{ __('messages.blade_incident_success_blade_barangay_hulong_duhat_logo') }}"></span>
        <span class="brand-text">
            <strong>{{ __('messages.footer_brand_name') }}</strong>
            <span>{{ __('messages.blade_incident_success_blade_online_services_portal') }}</span>
        </span>
    </a>
</header>

<main class="main-layout" id="mainContent">
    <div class="page-shell success-grid">
        <section class="success-card" aria-labelledby="successTitle">
            <div class="status-row">
                <div class="status-icon" aria-hidden="true"><i class="fas fa-check"></i></div>
                <div>
                    <span class="status-kicker">{{ __('messages.blade_successform_blade_submitted_successfully') }}</span>
                    <p>{{ __('messages.blade_successform_blade_no_need_to_submit_another_application') }}</p>
                </div>
            </div>

            <h1 class="title" id="successTitle">{{ __('messages.blade_success_your_service_application_was_received', ['service' => $service]) }}</h1>
            <p class="lead">{{ __('messages.blade_successform_blade_save_your_reference_number_then_track_your_request_anyt') }}</p>

            <div class="reference-panel" aria-label="{{ __('messages.incident_form_bring_reference') }}">
                <div>
                    <span class="ref-label">{{ __('messages.incident_form_bring_reference') }}</span>
                    <strong class="reference-number" id="referenceNumber">{{ $reference_number }}</strong>
                </div>
                <button type="button" class="copy-ref" id="copyReference" data-copy-target="referenceNumber">
                    <i class="fas fa-copy"></i> {{ __('messages.blade_incident_success_blade_copy') }}
                </button>
            </div>

            <div class="details-list" aria-label="{{ __('messages.blade_successform_blade_application_summary') }}">
                <div class="mini-detail">
                    <span>{{ __('messages.blade_successform_blade_applicant') }}</span>
                    <strong>{{ $applicant_name ?? __('messages.blade_successform_blade_applicant') }}</strong>
                </div>
                <div class="mini-detail">
                    <span>{{ __('messages.profile_table_date') }}</span>
                    <strong>{{ $submittedDateText }}</strong>
                </div>
                <div class="mini-detail">
                    <span>{{ __('messages.blade_successform_blade_fee') }}</span>
                    <strong>
                        @php
                            $feeLabel = session('fee_label', null);
                            $numericAmount = is_numeric($amount ?? null) ? (float) $amount : null;
                        @endphp
                        {{ $feeLabel ?: (($numericAmount && $numericAmount > 0) ? 'P' . number_format($numericAmount, 2) : __('messages.residency_form_fee_amount')) }}
                    </strong>
                </div>
            </div>

            <div class="actions" aria-label="{{ __('messages.blade_incident_success_blade_next_actions') }}">
                <a href="{{ route('track_request') }}" class="action-btn action-primary">
                    <i class="fas fa-search"></i> {{ __('messages.success_track_btn') }}
                </a>
                <button type="button" class="action-btn action-secondary" id="printReceipt">
                    <i class="fas fa-print"></i> {{ __('messages.blade_successform_blade_print_receipt') }}
                </button>
                <a href="{{ route($route) }}" class="action-btn action-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.blade_success_back_to_service_page', ['service' => $service]) }}
                </a>
            </div>
        </section>

        <aside class="side-card" aria-label="{{ __('messages.blade_incident_success_blade_helpful_information') }}">
            <div class="side-section">
                <h2>{{ __('messages.track_timeline_current_status') }}</h2>
                <span class="status-pill"><i class="fas fa-clock"></i> {{ __('messages.blade_successform_blade_waiting_for_review') }}</span>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_incident_success_blade_what_happens_next') }}</h2>
                <div class="timeline">
                    <div class="timeline-item done">
                        <span class="timeline-dot"><i class="fas fa-check"></i></span>
                        <div><strong>{{ __('messages.blade_index_blade_submitted') }}</strong><span>{{ __('messages.blade_successform_blade_your_application_was_saved_successfully') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">2</span>
                        <div><strong>{{ __('messages.blade_clearance_form_blade_review') }}</strong><span>{{ __('messages.blade_successform_blade_staff_checks_your_details_and_uploaded_requirements') }}</span></div>
                    </div>
                    <div class="timeline-item">
                        <span class="timeline-dot">3</span>
                        <div><strong>{{ __('messages.blade_successform_blade_release') }}</strong><span>{{ __('messages.blade_successform_blade_bring_this_receipt_and_your_valid_id_when_claiming') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="side-section">
                <h2>{{ __('messages.blade_services_blade_need_help') }}</h2>
                <p class="help-text"><strong>{{ __('messages.blade_index_blade_barangay_hall') }}</strong><br>{{ __('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city') }}<br><br><strong>{{ __('messages.contacts_hours_title') }}</strong><br>{{ __('messages.blade_incident_success_blade_monday_to_friday_8_00_am_5_00_pm') }}<br><br><strong>{{ __('messages.nav_contact') }}</strong><br>8-281-1373</p>
            </div>
        </aside>
    </div>
</main>

<div class="toast-copy" id="copyToast">{{ __('messages.blade_incident_success_blade_reference_number_copied') }}</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script src="{{ asset('js/dark-mode.js') }}"></script>
<script>

document.addEventListener('DOMContentLoaded', function(){
    const printButton = document.getElementById('printReceipt');
    if (!printButton) return;

    printButton.addEventListener('click', function(){
        const serviceName = @json($service ?? __('messages.blade_mission_vision_blade_service'));
        const applicantName = @json($applicant_name ?? __('messages.blade_successform_blade_applicant'));
        const referenceNum = @json($reference_number ?? '');
        const currentDate = @json($submittedDateText);
        const rawAmount = @json($amount ?? null);
        const feeLabel = @json($feeLabelText);
        const numericAmount = Number.parseFloat(rawAmount);
        const freeLabel = @json(__('messages.residency_form_fee_amount'));
        const normalizedFeeLabel = String(feeLabel || '').trim().toLowerCase();
        const isFree = feeLabel
            ? ['free', 'libre', 'no payment', 'walang bayad'].some(token => normalizedFeeLabel.includes(token))
            : (Number.isNaN(numericAmount) || numericAmount <= 0);
        const amountDisplay = feeLabel || (!Number.isNaN(numericAmount) && numericAmount > 0 ? `P${numericAmount.toFixed(2)}` : freeLabel);
        const paymentStepText = isFree
            ? @json(__('messages.blade_js_no_payment_is_required_for_this_service'))
            : @json(__('messages.blade_jsdyn_pay_amount_at_barangay_cashier_before_claiming')).replace(':amount', amountDisplay);
        const applicationReceiptTitle = @json(__('messages.blade_success_application_receipt')).replace(':service', serviceName);
        const qrData = @json(__('messages.blade_jsdyn_reference_reference_nname_name_nservice_service_ndate_date')).replace(':reference', referenceNum).replace(':name', applicantName).replace(':service', serviceName).replace(':date', currentDate).replace(':amount', amountDisplay);

        QRCode.toDataURL(qrData, { width: 150 }).then(function(qrDataUrl){
            const printContent = `<!DOCTYPE html>
<html>
<head>
<title>${applicationReceiptTitle}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:28px;color:#1e1c1b;background:#fff;line-height:1.5}.receipt{max-width:760px;margin:0 auto;border:1px solid #e3dbcf;border-radius:18px;padding:28px}.header{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;border-bottom:2px solid #9f2f26;padding-bottom:18px;margin-bottom:20px}.header h1{margin:0 0 6px;color:#9f2f26;font-size:22px}.subtitle{margin:0;color:#666;font-size:13px}.status{display:inline-flex;align-items:center;gap:6px;border:1px solid #dfc5bf;background:#f7e7e3;color:#9f2f26;border-radius:999px;padding:7px 12px;font-size:12px;font-weight:700;white-space:nowrap}.detail-row{display:grid;grid-template-columns:180px 1fr;gap:12px;padding:9px 0;border-bottom:1px solid #eee;font-size:14px}.detail-label{color:#666;font-weight:700}.qr{margin:22px 0;padding:18px;text-align:center;background:#f7f4ef;border:1px dashed #d1c6b7;border-radius:14px}.qr img{width:130px;height:130px;margin:10px auto;display:block;border:1px solid #e3dbcf;padding:8px;background:white}.instructions{margin-top:18px;padding:18px;background:#f7f4ef;border-radius:14px;font-size:13px}.instructions h3{color:#9f2f26;margin-top:0}.important{margin-top:14px;padding:12px;border-radius:10px;background:white;border:1px solid #e3dbcf}.footer{text-align:center;margin-top:22px;color:#666;font-size:11px;border-top:1px solid #eee;padding-top:12px}.no-print{text-align:center;margin-top:24px}.no-print button{padding:11px 18px;border-radius:999px;border:0;cursor:pointer;margin:4px;font-weight:700}.print{background:#9f2f26;color:#fff}.close{background:#555;color:#fff}@page{size:8.5in 11in;margin:.5in}@media print{body{margin:0;font-size:12px}.receipt{border:0;padding:0}.no-print{display:none!important}*{page-break-inside:avoid!important}}
</style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <div><h1><i class="fas fa-file-signature"></i> ${applicationReceiptTitle}</h1><p class="subtitle">{{ __('messages.blade_print_track_request_blade_barangay_hulong_duhat_malabon_city') }}</p></div>
        <span class="status"><i class="fas fa-clock"></i> {{ __('messages.blade_index_blade_submitted') }}</span>
    </div>
    <div class="details">
        <div class="detail-row"><div class="detail-label">{{ __('messages.incident_form_bring_reference') }}</div><div><strong>${referenceNum}</strong></div></div>
        <div class="detail-row"><div class="detail-label">{{ __('messages.track_result_name_label') }}</div><div>${applicantName}</div></div>
        <div class="detail-row"><div class="detail-label">{{ __('messages.blade_mission_vision_blade_service') }}</div><div>${serviceName}</div></div>
        <div class="detail-row"><div class="detail-label">{{ __('messages.profile_table_date') }}</div><div>${currentDate}</div></div>
        <div class="detail-row"><div class="detail-label">{{ __('messages.profile_table_status') }}</div><div><strong>{{ __('messages.blade_successform_blade_submitted_for_processing') }}</strong></div></div>
        <div class="detail-row"><div class="detail-label">{{ __('messages.blade_successform_blade_amount_to_pay') }}</div><div><strong>${amountDisplay}</strong></div></div>
    </div>
    <div class="qr"><strong><i class="fas fa-qrcode"></i> {{ __('messages.blade_successform_blade_scan_to_verify_application_status') }}</strong><img src="${qrDataUrl}" alt="{{ __('messages.blade_print_template_qr_code_for') }} ${referenceNum}"><p style="font-size:12px;color:#666;margin:0;">{{ __('messages.blade_print_template_reference') }} ${referenceNum}</p></div>
    <div class="instructions">
        <h3><i class="fas fa-list-check"></i> {{ __('messages.print_next_steps_title') }}</h3>
        <p><strong>1.</strong> {{ __('messages.blade_successform_blade_wait_for_barangay_review_and_confirmation') }}</p>
        <p><strong>2.</strong> {{ __('messages.blade_successform_blade_check_your_email_sms_or_tracking_page_for_updates') }}</p>
        <p><strong>3.</strong> {{ __('messages.blade_successform_blade_bring_this_receipt_and_the_same_valid_id_used_in_your_a') }}</p>
        <p><strong>4.</strong> ${paymentStepText}</p>
        <div class="important"><strong><i class="fas fa-triangle-exclamation"></i> {{ __('messages.blade_incident_success_blade_important') }}</strong> ${@json(__('messages.blade_success_template_keep_this_receipt_until_your_service_is_released')).replace(':service', serviceName)}</div>
        <p><strong><i class="fas fa-location-dot"></i> {{ __('messages.blade_print_track_request_blade_barangay_hall') }}</strong><br>{{ __('messages.blade_incident_success_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city') }}<br><strong><i class="fas fa-clock"></i> {{ __('messages.blade_incident_success_blade_office_hours') }}</strong><br>{{ __('messages.form_hours_weekdays') }}<br><strong><i class="fas fa-phone"></i> {{ __('messages.blade_print_track_request_blade_contact') }}</strong> 8-281-1373</p>
    </div>
    <div class="footer"><p>{{ __('messages.blade_successform_blade_this_is_an_electronically_generated_receipt_no_signatur') }}</p><p>${@json(__('messages.blade_print_template_barangay_hulong_duhat_online_services_year')).replace(':year', new Date().getFullYear())}</p></div>
    <div class="no-print"><button onclick="window.print()" class="print"><i class="fas fa-print"></i> {{ __('messages.blade_successform_blade_print_receipt') }}</button><button onclick="window.close()" class="close"><i class="fas fa-times"></i> {{ __('messages.blade_incident_success_blade_close_window') }}</button></div>
</div>
</body>
</html>`;
            const printWindow = window.open('', '_blank', 'width=820,height=700');
            if (printWindow) {
                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(function(){ printWindow.print(); }, 300);
            } else {
                alert(@json(__('messages.blade_successform_blade_please_allow_pop_ups_to_print_the_receipt')));
            }
        }).catch(function(error){
            console.error(@json(__('messages.blade_js_error_generating_qr_code')), error);
            alert(@json(__('messages.blade_incident_success_blade_error_generating_qr_code_please_try_again')));
        });
    });
});
</script>
</body>
</html>
