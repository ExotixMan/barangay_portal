@php
    $resident = $resident ?? auth()->user();
    $reporterFullName = trim(collect([
        $resident?->firstname,
        $resident?->middlename,
        $resident?->lastname,
        $resident?->suffix,
    ])->filter()->implode(' '));
    $reporterContact = $resident?->contact ?? '';
    $reporterAddress = $resident?->address ?? '';
    $reporterEmail = $resident?->email ?? '';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('messages.blade_incident_form_blade_barangay_incident_report_application_form') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    
        <style>
        :root {
            --cf-brand: #C62828;
            --cf-brand-dark: #8e0000;
            --cf-brand-soft: #fff1f2;
            --cf-ink: #101828;
            --cf-ink-soft: #344054;
            --cf-muted: #667085;
            --cf-line: #e4e7ec;
            --cf-line-strong: #d0d5dd;
            --cf-surface: #ffffff;
            --cf-surface-soft: #f8fafc;
            --cf-page: #f3f5f8;
            --cf-success: #15803d;
            --cf-success-soft: #f0fdf4;
            --cf-warning: #b45309;
            --cf-warning-soft: #fffbeb;
            --cf-danger: #dc2626;
            --cf-danger-soft: #fff1f2;
            --cf-radius-xl: 32px;
            --cf-radius-lg: 24px;
            --cf-radius: 18px;
            --cf-shadow: 0 24px 70px rgba(16, 24, 40, 0.10);
            --cf-shadow-soft: 0 12px 28px rgba(16, 24, 40, 0.07);
        }

        * { box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--cf-ink);
            background:
                radial-gradient(circle at 7% 0%, rgba(198, 40, 40, 0.13), transparent 34rem),
                radial-gradient(circle at 93% 12%, rgba(142, 0, 0, 0.08), transparent 30rem),
                linear-gradient(180deg, #ffffff 0%, var(--cf-page) 45%, #edf1f6 100%) !important;
            font-family: 'Inter', 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            line-height: 1.5;
        }

        img, svg { max-width: 100%; }

        .back-button-container,
        .form-page-header,
        .application-form-section {
            width: min(100% - 32px, 1120px);
            margin-inline: auto;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .back-button-container {
            padding-top: 22px;
            margin-bottom: 14px;
        }

        .btn-back {
            min-height: 44px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0.72rem 1rem;
            border: 1px solid rgba(208, 213, 221, 0.92);
            border-radius: 999px;
            color: var(--cf-ink-soft) !important;
            background: rgba(255, 255, 255, 0.82);
            text-decoration: none !important;
            font-size: 0.9rem;
            font-weight: 850;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
            backdrop-filter: blur(12px);
            transition: transform .18s ease, border-color .18s ease, color .18s ease, background .18s ease;
        }

        .btn-back:hover {
            transform: translateX(-2px);
            border-color: rgba(198, 40, 40, 0.34);
            background: #fff;
            color: var(--cf-brand) !important;
        }

        .form-page-header {
            position: relative;
            overflow: hidden;
            margin-bottom: 18px;
            padding: clamp(24px, 4vw, 42px) !important;
            border: 1px solid rgba(228, 231, 236, 0.96);
            border-radius: var(--cf-radius-xl);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: var(--cf-shadow);
            backdrop-filter: blur(16px);
        }

        .form-page-header::before {
            content: "";
            position: absolute;
            inset: -180px auto auto -120px;
            width: 360px;
            height: 360px;
            border-radius: 999px;
            background: rgba(198, 40, 40, 0.12);
            pointer-events: none;
        }

        .form-page-header::after {
            content: "Incident filing";
            position: absolute;
            right: clamp(18px, 3vw, 34px);
            bottom: clamp(18px, 3vw, 34px);
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 0.5rem 0.8rem;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, var(--cf-brand), var(--cf-brand-dark));
            box-shadow: 0 12px 24px rgba(198, 40, 40, 0.22);
            font-size: 0.78rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .form-page-header > * {
            position: relative;
            z-index: 1;
        }

        .form-page-header h1 {
            max-width: 760px;
            margin: 0 0 14px !important;
            color: var(--cf-ink);
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: clamp(2rem, 5vw, 4rem);
            line-height: 0.98;
            letter-spacing: -0.055em;
            font-weight: 700;
        }

        .form-page-header h1 i {
            width: 58px;
            height: 58px;
            display: inline-grid;
            place-items: center;
            margin-right: 12px;
            border-radius: 20px;
            color: var(--cf-brand);
            background: var(--cf-brand-soft);
            border: 1px solid rgba(198, 40, 40, 0.18);
            font-size: 1.25rem;
            vertical-align: middle;
        }

        .form-page-header p {
            max-width: 650px;
            color: var(--cf-muted);
            font-size: clamp(0.98rem, 1.5vw, 1.08rem);
            font-weight: 560;
        }

        .application-form-section { margin-bottom: 46px; }

        .form-container {
            overflow: hidden;
            border: 1px solid rgba(228, 231, 236, 0.96);
            border-radius: var(--cf-radius-xl) !important;
            background: rgba(255, 255, 255, 0.92) !important;
            box-shadow: var(--cf-shadow);
            backdrop-filter: blur(16px);
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-radius: 18px !important;
            padding: 14px 16px !important;
            border-width: 1px !important;
            font-size: 0.9rem;
            font-weight: 750;
        }

        .form-progress {
            margin: 0 !important;
            padding: clamp(16px, 3vw, 24px);
            border-bottom: 1px solid var(--cf-line);
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.96), rgba(255, 255, 255, 0.96));
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
            padding: 12px;
            border-radius: 18px;
            border: 1px solid transparent;
            color: var(--cf-muted);
            background: var(--cf-surface-soft);
            transition: background .18s ease, color .18s ease, border-color .18s ease;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            border: 1px solid var(--cf-line);
            background: var(--cf-surface);
            color: var(--cf-ink-soft);
            font-weight: 950;
        }

        .step span {
            min-width: 0;
            color: inherit;
            font-size: 0.88rem;
            font-weight: 900;
            line-height: 1.15;
        }

        .step.active {
            color: var(--cf-brand);
            background: var(--cf-brand-soft);
            border-color: rgba(198, 40, 40, 0.18);
        }

        .step.active .step-circle {
            color: #fff;
            background: var(--cf-brand);
            border-color: var(--cf-brand);
        }

        .progress-bar {
            height: 8px !important;
            overflow: hidden;
            border-radius: 999px;
            background: #edf0f3 !important;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--cf-brand), var(--cf-brand-dark));
            transition: width .28s ease;
        }

        .form-step {
            display: none;
            margin: 0 !important;
            padding: clamp(18px, 3vw, 28px) !important;
        }

        .form-step.active {
            display: block;
            animation: cfFadeIn .18s ease;
        }

        @keyframes cfFadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-step > h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 18px !important;
            padding: 15px;
            border: 1px solid var(--cf-line);
            border-radius: 20px;
            background: var(--cf-surface);
            color: var(--cf-ink);
            font-size: 1.08rem;
            font-weight: 950;
            letter-spacing: -0.02em;
        }

        .form-step > h3 i {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            flex: 0 0 40px;
            border-radius: 14px;
            color: var(--cf-brand);
            background: var(--cf-brand-soft);
            border: 1px solid rgba(198, 40, 40, 0.16);
            font-size: 0.95rem;
        }

        .form-grid.row,
        .upload-section.row,
        .form-actions.row,
        .benefits-grid.row { --bs-gutter-x: 0; --bs-gutter-y: 0; }

        .form-grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 0 0 14px !important;
        }

        .form-grid > [class*="col-"],
        .upload-section > [class*="col-"],
        .form-actions > [class*="col-"],
        .benefits-grid > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .form-group.full-width,
        .full-width { grid-column: 1 / -1; }

        .form-group { margin-bottom: 18px; min-width: 0; }

        .form-group label,
        .upload-group > label,
        .witness-item label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: var(--cf-ink);
            font-size: 0.86rem;
            font-weight: 900;
        }

        .form-group label i,
        .upload-group > label i,
        .party-section h4 i { color: var(--cf-brand); }

        .form-hint {
            margin-top: 6px;
            color: var(--cf-muted);
            font-size: 0.78rem;
            font-weight: 650;
        }

        .form-control {
            width: 100%;
            min-height: 48px;
            border: 1px solid var(--cf-line-strong) !important;
            border-radius: 15px !important;
            background: var(--cf-surface) !important;
            color: var(--cf-ink) !important;
            padding: 0.78rem 0.9rem;
            font: inherit;
            font-size: 0.94rem;
            font-weight: 600;
            box-shadow: none !important;
        }

        .form-control::placeholder { color: #98a2b3; opacity: 1; }

        .form-control:focus {
            border-color: var(--cf-brand) !important;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12) !important;
        }

        textarea.form-control { min-height: 96px; resize: vertical; }

        .report-type-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            width: 100%;
            margin-bottom: 16px;
        }

        .report-type-option,
        .confidentiality-option {
            width: 100%;
            display: block;
            cursor: pointer;
        }

        .report-type-option input[type="radio"],
        .confidentiality-option input[type="radio"] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .type-card,
        .confidentiality-option .option-content {
            height: 100%;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            border: 1.6px solid var(--cf-line);
            border-radius: 20px;
            background: var(--cf-surface-soft);
            text-align: left;
            transition: transform .18s ease, border-color .18s ease, background .18s ease, box-shadow .18s ease;
        }

        .type-card:hover,
        .confidentiality-option .option-content:hover {
            transform: translateY(-1px);
            border-color: rgba(198, 40, 40, 0.30);
            background: #fff;
            box-shadow: var(--cf-shadow-soft);
        }

        .report-type-option input[type="radio"]:checked + .type-card,
        .confidentiality-option input[type="radio"]:checked + .option-content {
            border-color: rgba(198, 40, 40, 0.50);
            background: var(--cf-brand-soft);
            box-shadow: 0 14px 28px rgba(198, 40, 40, 0.10);
        }

        .type-card > i,
        .option-content h4 i {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            background: #fff;
            color: var(--cf-brand);
            border: 1px solid rgba(198, 40, 40, 0.16);
            font-size: 1rem;
            margin: 0;
        }

        .type-card h4,
        .option-content h4 {
            margin: 0 0 4px;
            color: var(--cf-ink);
            font-size: 0.95rem;
            font-weight: 950;
            line-height: 1.2;
        }

        .type-card p,
        .option-content p {
            margin: 0;
            color: var(--cf-muted);
            font-size: 0.82rem;
            font-weight: 620;
            line-height: 1.4;
        }

        .confidentiality-options {
            display: grid;
            gap: 12px;
            margin-bottom: 22px;
            width: 100%;
        }

        .confidentiality-option .option-content { align-items: center; }
        .option-content h4 { display: flex; align-items: center; gap: 12px; }
        .option-content h4 i { margin: 0; }

        .party-section,
        .review-card,
        .benefits-section,
        .terms-section {
            border: 1px solid var(--cf-line) !important;
            border-radius: var(--cf-radius-lg) !important;
            background: var(--cf-surface) !important;
            padding: clamp(16px, 2.4vw, 22px) !important;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
            margin-bottom: 16px;
        }

        .party-section h4,
        .review-card h4,
        .benefits-section h4 {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 16px !important;
            color: var(--cf-ink) !important;
            font-size: 1.02rem !important;
            font-weight: 950 !important;
            letter-spacing: -0.02em;
        }

        .add-party-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0.72rem 1rem;
            border: 1px solid rgba(198, 40, 40, 0.20);
            border-radius: 16px;
            background: var(--cf-brand-soft);
            color: var(--cf-brand);
            font-size: 0.88rem;
            font-weight: 950;
            cursor: pointer;
            transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
            margin-bottom: 14px;
        }

        .add-party-btn:hover {
            transform: translateY(-1px);
            background: #fff;
            box-shadow: var(--cf-shadow-soft);
        }

        .add-party-btn:disabled {
            opacity: .62;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .witness-rule-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 14px;
            border: 1px solid rgba(198, 40, 40, 0.14);
            border-radius: 18px;
            background: var(--cf-brand-soft);
            color: var(--cf-ink-soft);
            font-size: 0.86rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .witness-rule-card i {
            margin-top: 2px;
            color: var(--cf-brand);
        }

        .witness-rule-card strong {
            color: var(--cf-ink);
            font-weight: 950;
        }

        .witnesses-list { display: grid; gap: 12px; }

        .witness-item {
            border: 1px solid var(--cf-line);
            border-radius: 20px;
            background: var(--cf-surface-soft);
            padding: 16px;
            margin-bottom: 0;
        }

        .witness-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .witness-number {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 0.38rem 0.65rem;
            border-radius: 999px;
            color: var(--cf-brand);
            background: var(--cf-brand-soft);
            font-size: 0.8rem;
            font-weight: 950;
        }

        .remove-witness,
        .remove-file {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 12px;
            background: var(--cf-danger-soft);
            color: var(--cf-danger);
            cursor: pointer;
            transition: transform .18s ease, background .18s ease;
        }

        .remove-witness:hover,
        .remove-file:hover { transform: scale(1.03); }

        .upload-section {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .upload-group { width: 100%; min-width: 0; }

        .upload-size {
            margin-left: auto;
            color: var(--cf-muted);
            font-size: 0.74rem;
            font-weight: 850;
        }

        .upload-area {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 176px;
            padding: 22px;
            border: 1.8px dashed var(--cf-line-strong) !important;
            border-radius: 22px;
            background: var(--cf-surface-soft) !important;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease, transform .18s ease;
        }

        .upload-area:hover,
        .upload-area.highlight {
            transform: translateY(-1px);
            border-color: var(--cf-brand) !important;
            background: var(--cf-brand-soft) !important;
        }

        .upload-area.error {
            border-color: var(--cf-danger) !important;
            background: var(--cf-danger-soft) !important;
        }

        .upload-area i {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            margin: 0 auto 12px;
            border-radius: 18px;
            background: #fff;
            color: var(--cf-brand);
            border: 1px solid rgba(198, 40, 40, 0.16);
            font-size: 1.35rem;
        }

        .upload-area p { margin: 0; color: var(--cf-ink-soft); font-weight: 800; }
        .upload-area span { color: var(--cf-brand); font-weight: 950; text-decoration: underline; }

        .upload-preview.active { margin-top: 10px; }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 12px;
            border: 1px solid var(--cf-line);
            border-radius: 18px;
            background: var(--cf-surface);
            margin-bottom: 8px;
        }

        .file-preview > i {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: var(--cf-brand-soft);
            color: var(--cf-brand);
        }

        .file-info { min-width: 0; flex: 1; }
        .file-name { overflow: hidden; color: var(--cf-ink); font-size: 0.86rem; font-weight: 900; text-overflow: ellipsis; white-space: nowrap; }
        .file-size { color: var(--cf-muted); font-size: 0.78rem; font-weight: 700; }

        .review-section { display: grid; gap: 14px; }
        .review-content { display: grid; gap: 10px; margin-top: 14px; }

        .review-item {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 12px;
            padding: 12px;
            border: 1px solid var(--cf-line);
            border-radius: 16px;
            background: var(--cf-surface-soft);
        }

        .review-label {
            color: var(--cf-muted);
            font-size: 0.76rem;
            font-weight: 950;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .review-value {
            color: var(--cf-ink);
            font-size: 0.9rem;
            font-weight: 800;
            word-break: break-word;
        }

        .payment-summary { display: grid; gap: 8px; }

        .payment-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid var(--cf-line);
            color: var(--cf-ink-soft);
            font-size: 0.9rem;
            font-weight: 750;
        }

        .payment-item.total { border-bottom: 0; color: var(--cf-ink); font-weight: 950; }

        .pickup-instructions { display: grid; gap: 10px; margin-top: 16px; }

        .pickup-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px;
            border: 1px solid var(--cf-line);
            border-radius: 18px;
            background: var(--cf-surface-soft);
        }

        .pickup-info > i {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: var(--cf-brand-soft);
            color: var(--cf-brand);
        }

        .pickup-info h5 { margin: 0 0 4px; color: var(--cf-ink); font-size: 0.92rem; font-weight: 950; }
        .pickup-info p, .pickup-info ul { margin: 0; color: var(--cf-muted); font-size: 0.86rem; font-weight: 600; }
        .pickup-info ul { padding-left: 18px; }

        .benefits-section { margin-top: 16px; }

        .benefits-grid {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 14px;
        }

        .benefit-item {
            width: auto !important;
            min-width: 0;
            padding: 15px;
            border: 1px solid var(--cf-line);
            border-radius: 18px;
            background: var(--cf-surface-soft);
        }

        .benefit-item i {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            margin-bottom: 12px !important;
            border-radius: 14px;
            background: var(--cf-brand-soft);
            color: var(--cf-brand);
        }

        .benefit-item h5 { margin: 0 0 4px; color: var(--cf-ink); font-size: 0.92rem; font-weight: 950; }
        .benefit-item p { margin: 0; color: var(--cf-muted); font-size: 0.82rem; font-weight: 600; }

        .terms-section { display: grid; gap: 12px; margin-top: 16px; }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 0 !important;
            color: var(--cf-ink-soft);
            font-size: 0.9rem;
            font-weight: 650;
            cursor: pointer;
        }

        .terms-checkbox input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .checkmark {
            position: relative;
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            margin-top: 1px;
            border: 1.6px solid var(--cf-line-strong);
            border-radius: 8px;
            background: #fff;
        }

        .terms-checkbox input:checked ~ .checkmark {
            background: var(--cf-brand);
            border-color: var(--cf-brand);
        }

        .checkmark::after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .terms-checkbox input:checked ~ .checkmark::after { display: block; }
        .terms-checkbox a { color: var(--cf-brand); font-weight: 950; text-decoration: none; }
        .terms-checkbox a:hover { text-decoration: underline; }

        .btn-prev,
        .btn-next,
        .btn-submit {
            min-height: 46px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 16px !important;
            border: 1px solid transparent;
            padding: 0.78rem 1.05rem;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 950;
            line-height: 1;
            cursor: pointer;
            text-decoration: none !important;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        }

        .btn-prev {
            color: var(--cf-ink-soft) !important;
            background: #fff;
            border-color: var(--cf-line-strong);
        }

        .btn-prev:hover {
            color: var(--cf-brand) !important;
            border-color: rgba(198, 40, 40, 0.28);
            background: var(--cf-brand-soft);
        }

        .btn-next,
        .btn-submit {
            color: #fff !important;
            background: linear-gradient(135deg, var(--cf-brand), var(--cf-brand-dark)) !important;
            box-shadow: 0 12px 24px rgba(198, 40, 40, 0.20);
        }

        .btn-next:hover,
        .btn-submit:hover {
            color: #fff !important;
            transform: translateY(-1px);
            box-shadow: 0 16px 32px rgba(198, 40, 40, 0.26);
        }

        .form-actions {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid var(--cf-line);
        }

        .error-message {
            width: fit-content;
            max-width: 100%;
            margin-top: 8px !important;
            padding: 8px 10px;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            background: var(--cf-danger-soft);
            color: #991b1b !important;
            font-size: 0.8rem !important;
            font-weight: 850;
        }

        input.error,
        select.error,
        textarea.error {
            border-color: var(--cf-danger) !important;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.10) !important;
        }

        #termsError {
            display: none;
            margin-top: 12px !important;
            padding: 12px 14px !important;
            border: 1px solid #fecdd3 !important;
            border-radius: 14px !important;
            background: var(--cf-danger-soft) !important;
            color: #991b1b !important;
            font-size: 0.86rem !important;
            font-weight: 850;
        }

        .spinner { display: none; gap: 4px; }
        .btn-submit.loading .spinner { display: inline-flex; }

        .spinner-dot {
            width: 5px;
            height: 5px;
            border-radius: 999px;
            background: #fff;
            animation: cfBounce 1.1s infinite ease-in-out both;
        }

        .spinner-dot:nth-child(1) { animation-delay: -0.24s; }
        .spinner-dot:nth-child(2) { animation-delay: -0.12s; }

        @keyframes cfBounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        .modal-content {
            overflow: hidden;
            border: 1px solid var(--cf-line) !important;
            border-radius: 22px !important;
            box-shadow: var(--cf-shadow);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--cf-line) !important;
            background: var(--cf-surface-soft) !important;
        }

        .modal-title { color: var(--cf-ink) !important; font-weight: 950 !important; }
        .modal-body { color: var(--cf-ink-soft); font-size: 0.92rem; }

        @media (max-width: 992px) {
            .progress-steps { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .benefits-grid { grid-template-columns: 1fr; }
            .form-page-header::after { position: static; margin-top: 18px; width: fit-content; }
        }

        @media (max-width: 820px) {
            html, body { width: 100%; max-width: 100%; overflow-x: hidden !important; }
            body { -webkit-text-size-adjust: 100%; }

            .back-button-container,
            .form-page-header,
            .application-form-section {
                width: min(100% - 20px, 1120px) !important;
                max-width: 100% !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .container,
            .container-fluid,
            .form-container { max-width: 100% !important; }

            .back-button-container { padding-top: 14px; }

            .form-page-header {
                margin-bottom: 14px !important;
                padding: 20px !important;
                border-radius: 24px !important;
            }

            .form-page-header::after {
                position: static !important;
                display: inline-flex !important;
                width: fit-content;
                margin-top: 16px;
                font-size: 0.72rem;
            }

            .form-page-header h1 {
                display: block !important;
                font-size: clamp(1.9rem, 10vw, 2.45rem) !important;
                line-height: 1 !important;
                letter-spacing: -0.05em !important;
            }

            .form-page-header h1 i {
                display: grid !important;
                width: 46px !important;
                height: 46px !important;
                margin: 0 0 12px !important;
                border-radius: 16px !important;
            }

            .form-page-header p { max-width: 100% !important; font-size: 0.92rem !important; }
            .form-container { border-radius: 22px !important; }
            .form-progress { padding: 14px !important; }
            .progress-steps { grid-template-columns: 1fr !important; gap: 8px !important; }

            .step { min-height: 54px !important; padding: 10px !important; border-radius: 16px !important; }
            .step-circle { width: 34px !important; height: 34px !important; flex-basis: 34px !important; font-size: 0.82rem !important; }
            .step span { font-size: 0.84rem !important; }
            .progress-bar { height: 7px !important; }
            .form-step { padding: 16px !important; }

            .form-step > h3 {
                align-items: flex-start !important;
                gap: 10px !important;
                padding: 12px !important;
                border-radius: 17px !important;
                font-size: 0.98rem !important;
                line-height: 1.2 !important;
            }

            .form-step > h3 i {
                width: 36px !important;
                height: 36px !important;
                flex-basis: 36px !important;
                border-radius: 13px !important;
                font-size: 0.86rem !important;
            }

            .form-grid,
            .report-type-grid { grid-template-columns: 1fr !important; gap: 10px !important; }
            .form-group.full-width, .full-width { grid-column: auto !important; }

            .party-section,
            .review-card,
            .benefits-section,
            .terms-section { padding: 14px !important; border-radius: 18px !important; }

            .type-card,
            .confidentiality-option .option-content { padding: 13px !important; border-radius: 17px !important; gap: 12px !important; }
            .type-card > i,
            .option-content h4 i { width: 38px !important; height: 38px !important; flex-basis: 38px !important; border-radius: 13px !important; }
            .type-card h4, .option-content h4 { font-size: 0.9rem !important; }
            .type-card p, .option-content p { font-size: 0.78rem !important; }

            .form-group label,
            .upload-group > label,
            .witness-item label { display: block !important; font-size: 0.82rem !important; }
            .form-group label i, .upload-group > label i { margin-right: 6px !important; }
            .upload-size { display: block !important; margin: 4px 0 0 !important; font-size: 0.7rem !important; }
            .form-control { min-height: 48px !important; font-size: 0.92rem !important; border-radius: 14px !important; }
            textarea.form-control { min-height: 92px !important; }

            .upload-area { min-height: 138px !important; padding: 16px !important; border-radius: 18px !important; }
            .upload-area i { width: 46px !important; height: 46px !important; margin-bottom: 10px !important; border-radius: 15px !important; font-size: 1.1rem !important; }
            .upload-area p { font-size: 0.86rem !important; line-height: 1.35 !important; }
            .upload-area .small { font-size: 0.75rem !important; }

            .file-preview { align-items: flex-start !important; gap: 10px !important; padding: 10px !important; border-radius: 15px !important; }
            .file-preview > i { width: 38px !important; height: 38px !important; flex-basis: 38px !important; border-radius: 13px !important; }
            .file-name { max-width: 100% !important; white-space: normal !important; word-break: break-word !important; font-size: 0.8rem !important; }
            .file-size { font-size: 0.72rem !important; }
            .remove-file, .remove-witness { width: 34px !important; height: 34px !important; flex-basis: 34px !important; }

            .review-item { grid-template-columns: 1fr !important; gap: 4px !important; padding: 11px !important; border-radius: 15px !important; }
            .review-label { font-size: 0.68rem !important; }
            .review-value { font-size: 0.84rem !important; }
            .payment-item { flex-direction: column !important; align-items: flex-start !important; gap: 3px !important; font-size: 0.84rem !important; }
            .pickup-info { flex-direction: column !important; gap: 9px !important; padding: 12px !important; border-radius: 15px !important; }
            .pickup-info > i { width: 34px !important; height: 34px !important; flex-basis: 34px !important; border-radius: 12px !important; }
            .pickup-info h5 { font-size: 0.86rem !important; }
            .pickup-info p, .pickup-info ul { font-size: 0.8rem !important; }

            .benefits-grid { grid-template-columns: 1fr !important; gap: 10px !important; }
            .benefit-item { width: 100% !important; padding: 12px !important; border-radius: 15px !important; }
            .benefit-item i { width: 38px !important; height: 38px !important; margin-bottom: 10px !important; border-radius: 13px !important; }
            .benefit-item h5 { font-size: 0.86rem !important; }
            .benefit-item p { font-size: 0.78rem !important; }

            .terms-section { gap: 10px !important; }
            .terms-checkbox { gap: 10px !important; font-size: 0.8rem !important; line-height: 1.4 !important; }
            .checkmark { width: 21px !important; height: 21px !important; flex-basis: 21px !important; }

            .form-actions,
            .form-actions.row {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 10px !important;
                align-items: stretch !important;
            }

            .form-actions > [class*="col-"] {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin-left: 0 !important;
            }

            .btn-prev,
            .btn-next,
            .btn-submit {
                width: 100% !important;
                min-height: 48px !important;
                border-radius: 15px !important;
                font-size: 0.86rem !important;
            }

            .modal-dialog { margin: 8px !important; }
            .modal-content { border-radius: 18px !important; max-height: calc(100dvh - 16px) !important; }
            .modal-body { overflow-y: auto !important; -webkit-overflow-scrolling: touch !important; }
            .modal-header, .modal-footer { padding: 12px 14px !important; }
            .modal-title { font-size: 0.96rem !important; line-height: 1.2 !important; }
            #termsError { font-size: 0.8rem !important; border-radius: 13px !important; }
        }

        @media (max-width: 430px) {
            .back-button-container,
            .form-page-header,
            .application-form-section { width: min(100% - 14px, 1120px) !important; }

            .btn-back { width: 100% !important; min-height: 44px !important; font-size: 0.84rem !important; }
            .form-page-header { padding: 16px !important; }
            .form-page-header h1 { font-size: 1.85rem !important; }
            .form-page-header::after { width: 100% !important; justify-content: center !important; }
            .form-progress, .form-step { padding: 13px !important; }
            .step { border-radius: 14px !important; }
            .form-step > h3 { font-size: 0.92rem !important; }
            .review-value { font-size: 0.82rem !important; }
            .terms-checkbox { font-size: 0.78rem !important; }
        }


        /* Final consistency patch for incident report */
        .form-step > .form-group,
        .form-step > .form-grid,
        .form-step > .upload-section,
        .form-step > .party-section { margin-bottom: 16px !important; }
        .report-type-grid, .confidentiality-options { gap: 12px !important; }
        .type-card, .confidentiality-option .option-content {
            position: relative;
            align-items: start !important;
            gap: 14px !important;
            width: 100% !important;
            padding: 16px 18px !important;
            border-radius: 20px !important;
        }
        .type-card > i, .confidentiality-option .option-content > i {
            width: 46px !important;
            height: 46px !important;
            display: grid !important;
            place-items: center !important;
            border-radius: 16px !important;
            background: #fff !important;
            color: var(--cf-brand) !important;
            border: 1px solid rgba(198, 40, 40, 0.16) !important;
            font-size: 1rem !important;
            margin: 0 !important;
        }
        .type-card h4, .confidentiality-option .option-content h4 { margin: 0 0 5px !important; line-height: 1.2 !important; }
        .type-card p, .confidentiality-option .option-content p { max-width: 58ch; }
        .report-type-option input[type="radio"]:checked + .type-card::after,
        .confidentiality-option input[type="radio"]:checked + .option-content::after {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: 12px;
            right: 12px;
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            color: #fff;
            background: var(--cf-brand);
            font-size: 0.72rem;
        }
        .party-section { background: linear-gradient(180deg, #fff, #fbfcfe) !important; }
        .party-section h4 { padding-bottom: 12px; border-bottom: 1px solid var(--cf-line); }
        .witness-rule-card { margin-top: -2px; }
        .witness-item .form-grid, .witness-item .row { margin: 0 !important; }
        .witness-item .row > [class*="col-"] { padding-left: 0 !important; padding-right: 0 !important; }
        .terms-checkbox span:not(.checkmark) { line-height: 1.45; }
        .form-actions, .form-actions.row { margin-left: 0 !important; margin-right: 0 !important; }
        .btn-prev, .btn-next, .btn-submit, .add-party-btn { white-space: nowrap; }
        @media (max-width: 820px) {
            .type-card, .confidentiality-option .option-content { grid-template-columns: 42px minmax(0, 1fr) !important; min-height: auto !important; padding: 14px !important; }
            .type-card > i, .confidentiality-option .option-content > i { width: 40px !important; height: 40px !important; border-radius: 14px !important; }
        }

        /* Center benefit card icons */
        .benefit-item {
            text-align: center !important;
        }

        .benefit-item i {
            margin: 0 auto 12px !important;
        }

        @media (max-width: 820px) {
            .benefit-item i {
                margin: 0 auto 10px !important;
            }
        }

    </style>
</head>
@include('chatbot.embed')
<body>
    <!-- Back Button - Matching residency format exactly -->
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('incident') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.incident_form_back_btn') }}
        </a>
    </div>

    <!-- Page Header - Matching residency format -->
    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-clipboard-list"></i> {{ __('messages.incident_form_page_title') }}</h1>
        <p class="mb-0">{{ __('messages.blade_incident_form_blade_file_an_official_incident_report_with_a_clean_step_by_s') }}</p>
    </div>

    <!-- Application Form -->
    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            @if (session('success'))
                <div class="alert alert-success m-3">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <i class="fas fa-triangle-exclamation"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="blotterForm" method="POST" action="{{ route('incident.store') }}" enctype="multipart/form-data" class="requirement-form" novalidate>
                @csrf
                <!-- Progress Indicator - Exactly matching residency -->
                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.incident_form_step1_label') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.incident_form_step2_label') }}</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>{{ __('messages.incident_form_step3_label') }}</span>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-circle">4</div>
                            <span>{{ __('messages.form_step_review_submit') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <!-- Step 1: Incident Information -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-info-circle"></i> {{ __('messages.incident_form_step1_title') }}</h3>

                    <!-- Report Type -->
                    <div class="form-group full-width">
                        <label><i class="fas fa-tag"></i> {{ __('messages.incident_form_type_label') }}</label>
                        <div class="report-type-grid">
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="dispute" required {{ old('reportType') === 'dispute' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-users"></i>
                                    <h4>{{ __('messages.incident_type_dispute_title') }}</h4>
                                    <p>{{ __('messages.incident_type_dispute_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="security" {{ old('reportType') === 'security' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-shield-alt"></i>
                                    <h4>{{ __('messages.incident_type_security_title') }}</h4>
                                    <p>{{ __('messages.incident_type_security_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="public" {{ old('reportType') === 'public' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-road"></i>
                                    <h4>{{ __('messages.incident_type_public_title') }}</h4>
                                    <p>{{ __('messages.incident_type_public_desc') }}</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="other" {{ old('reportType') === 'other' ? 'checked' : '' }}>
                                <div class="type-card">
                                    <i class="fas fa-question-circle"></i>
                                    <h4>{{ __('messages.incident_type_other_title') }}</h4>
                                    <p>{{ __('messages.incident_type_other_desc') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="incidentDate"><i class="fas fa-calendar-day"></i> {{ __('messages.incident_form_date_label') }}</label>
                            <input type="date" id="incidentDate" name="incidentDate" required value="{{ old('incidentDate') }}" class="form-control">
                            <div class="form-hint">{{ __('messages.incident_form_date_hint') }}</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="incidentTime"><i class="fas fa-clock"></i> {{ __('messages.incident_form_time_label') }}</label>
                            <input type="time" id="incidentTime" name="incidentTime" required value="{{ old('incidentTime') }}" class="form-control">
                            <div class="form-hint">{{ __('messages.incident_form_time_hint') }}</div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentLocation"><i class="fas fa-map-marker-alt"></i> {{ __('messages.incident_form_location_label') }}</label>
                        <input type="text" id="incidentLocation" name="incidentLocation" required 
                               placeholder="{{ __('messages.blade_incident_form_blade_specific_location_within_barangay_hulong_duhat') }}" minlength="8" maxlength="255" value="{{ old('incidentLocation') }}" class="form-control">
                        <div class="form-hint">{{ __('messages.incident_form_location_hint') }}</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentDescription"><i class="fas fa-align-left"></i> {{ __('messages.incident_form_description_label') }}</label>
                        <textarea id="incidentDescription" name="incidentDescription" required rows="4" 
                                  placeholder="{{ __('messages.incident_form_description_placeholder') }}" minlength="20" maxlength="5000" class="form-control">{{ old('incidentDescription') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_description_hint') }}</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="immediateAction"><i class="fas fa-first-aid"></i> {{ __('messages.incident_form_actions_label') }}</label>
                        <textarea id="immediateAction" name="immediateAction" rows="3" 
                                  placeholder="{{ __('messages.incident_form_actions_placeholder') }}" maxlength="2000" class="form-control">{{ old('immediateAction') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_actions_hint') }}</div>
                    </div>

                    <div class="form-actions row g-2 btns" style="margin-top: 3px;">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('incident') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" onclick="return confirm(@json(__('messages.blade_clearance_form_blade_are_you_sure_you_want_to_cancel_any_unsaved_changes_wil')));">
                                <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-auto ms-md-auto btns">
                            <button type="button" class="btn-next" data-next="step2">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Parties Involved -->
                <div class="form-step" id="step2">
                    <h3><i class="fas fa-user-friends"></i> {{ __('messages.incident_form_step2_label') }}</h3>

                    <!-- Complainant (Your Information) -->
                    <div class="party-section">
                        <h4><i class="fas fa-user-check"></i> {{ __('messages.incident_form_complainant_title') }}</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="complainantName"><i class="fas fa-user"></i> {{ __('messages.form_full_name_label') }}</label>
                                <input type="text" id="complainantName" name="complainantName" required 
                                       placeholder="{{ __('messages.blade_incident_form_blade_enter_your_full_name') }}" minlength="2" maxlength="120" value="{{ old('complainantName', $reporterFullName) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.form_name_hint') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantContact"><i class="fas fa-phone"></i> {{ __('messages.form_contact_label') }}</label>
                                <input type="tel" id="complainantContact" name="complainantContact" required 
                                       placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" maxlength="11" value="{{ old('complainantContact', $reporterContact) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.incident_form_contact_hint') }}</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantAddress"><i class="fas fa-home"></i> {{ __('messages.form_address_label') }}</label>
                                <input type="text" id="complainantAddress" name="complainantAddress" required 
                                       placeholder="{{ __('messages.blade_incident_form_blade_house_no_street_barangay_hulong_duhat_malabon_city') }}" minlength="10" maxlength="255" value="{{ old('complainantAddress', $reporterAddress) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantEmail"><i class="fas fa-envelope"></i> {{ __('messages.register_email_label') }}</label>
                                <input type="email" id="complainantEmail" name="complainantEmail" 
                                       placeholder="{{ __('messages.blade_incident_form_blade_your_email_example_com') }}" maxlength="255" value="{{ old('complainantEmail', $reporterEmail) }}" class="form-control">
                                <div class="form-hint">{{ __('messages.incident_form_email_hint') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Respondent -->
                    <div class="party-section">
                        <h4><i class="fas fa-user-times"></i> {{ __('messages.incident_form_respondent_title') }}</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="respondentName"><i class="fas fa-user"></i> {{ __('messages.incident_form_respondent_name_label') }}</label>
                                <input type="text" id="respondentName" name="respondentName" 
                                       placeholder="{{ __('messages.incident_form_respondent_name_placeholder') }}" maxlength="120" value="{{ old('respondentName') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentContact"><i class="fas fa-phone"></i> {{ __('messages.incident_form_respondent_contact_label') }}</label>
                                <input type="tel" id="respondentContact" name="respondentContact" maxlength="11" value="{{ old('respondentContact') }}"
                                       placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentAddress"><i class="fas fa-home"></i> {{ __('messages.incident_form_respondent_address_label') }}</label>
                                <input type="text" id="respondentAddress" name="respondentAddress" 
                                       placeholder="{{ __('messages.incident_form_respondent_address_placeholder') }}" maxlength="255" value="{{ old('respondentAddress') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="respondentDescription"><i class="fas fa-id-card"></i> {{ __('messages.incident_form_respondent_desc_label') }}</label>
                            <textarea id="respondentDescription" name="respondentDescription" rows="2" 
                                      placeholder="{{ __('messages.incident_form_respondent_desc_placeholder') }}" maxlength="1000" class="form-control">{{ old('respondentDescription') }}</textarea>
                            <div class="form-hint">{{ __('messages.incident_form_respondent_desc_hint') }}</div>
                        </div>
                    </div>

                    <!-- Witnesses -->
                    <div class="party-section">
                        <h4><i class="fas fa-eye"></i> {{ __('messages.incident_form_witnesses_title') }}</h4>
                        <div class="witness-rule-card">
                            <i class="fas fa-circle-info"></i>
                            <div>
                                <strong>{{ __('messages.blade_incident_form_blade_witnesses_are_optional') }}</strong> {{ __('messages.blade_incident_form_blade_you_may_add_up_to') }} <strong>{{ __('messages.blade_incident_form_blade_5_witnesses') }}</strong>.
                                {{ __('messages.blade_incident_if_you_add_a_witness_provide_at_least_the_witness_name_or__2') }}
                                {{ __('messages.blade_incident_contact_number_is_optional_but_must_be_valid_if_provided') }}
                            </div>
                        </div>
                        <button type="button" class="add-party-btn" id="addWitnessBtn">
                            <i class="fas fa-user-plus"></i> {{ __('messages.incident_form_add_witness_btn') }}
                        </button>
                        <div class="witnesses-list" id="witnessesList"></div>
                        <div class="form-hint" id="witnessCountHint">{{ __('messages.blade_incident_form_blade_0_of_5_witnesses_added') }}</div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step1"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step3">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Evidence & Confidentiality -->
                <div class="form-step" id="step3">
                    <h3><i class="fas fa-file-upload"></i> {{ __('messages.incident_form_step3_title') }}</h3>

                    <div class="upload-section row g-4">
                        <!-- Photos -->
                        <div class="upload-group col-12">
                            <label for="photos">
                                <i class="fas fa-image"></i> {{ __('messages.incident_form_photos_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_photos_size') }}</span>
                            </label>
                            <div class="upload-area" id="photoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_photos_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_photos_hint') }}</p>
                                <input type="file" id="photos" name="photos[]" accept=".jpg,.jpeg,.png" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="photoPreview"></div>
                        </div>

                        <!-- Videos -->
                        <div class="upload-group col-12">
                            <label for="videos">
                                <i class="fas fa-file-video"></i> {{ __('messages.incident_form_videos_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_videos_size') }}</span>
                            </label>
                            <div class="upload-area" id="videoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_videos_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_videos_hint') }}</p>
                                <input type="file" id="videos" name="videos[]" accept=".mp4,.avi,.mov" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="videoPreview"></div>
                        </div>

                        <!-- Documents -->
                        <div class="upload-group col-12">
                            <label for="documents">
                                <i class="fas fa-file-alt"></i> {{ __('messages.incident_form_docs_label') }}
                                <span class="upload-size">{{ __('messages.incident_form_docs_size') }}</span>
                            </label>
                            <div class="upload-area" id="docUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">{{ __('messages.blade_incident_form_blade_drag_drop_documents_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                <p class="small text-muted mt-2">{{ __('messages.incident_form_docs_hint') }}</p>
                                <input type="file" id="documents" name="documents[]" accept=".pdf,.doc,.docx" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="docPreview"></div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="additionalInfo"><i class="fas fa-sticky-note"></i> {{ __('messages.incident_form_additional_label') }}</label>
                        <textarea id="additionalInfo" name="additionalInfo" rows="3" 
                                  placeholder="{{ __('messages.incident_form_additional_placeholder') }}" maxlength="2000" class="form-control">{{ old('additionalInfo') }}</textarea>
                        <div class="form-hint">{{ __('messages.incident_form_additional_hint') }}</div>
                    </div>

                    <!-- Confidentiality Options -->
                    <div class="form-group full-width">
                        <label><i class="fas fa-user-shield"></i> {{ __('messages.incident_form_confidentiality_label') }}</label>
                        <div class="confidentiality-options">
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="low" required {{ old('confidentiality') === 'low' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-eye"></i>
                                    <div>
                                        <h4>{{ __('messages.blade_incident_form_blade_standard_report') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_barangay_officials_may_use_your_details_for_normal_proc') }}</p>
                                    </div>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="medium" {{ old('confidentiality') === 'medium' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-user-secret"></i>
                                    <div>
                                        <h4>{{ __('messages.incident_confidentiality_confidential_title') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_limit_access_to_authorized_barangay_officials_handling_') }}</p>
                                    </div>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="high" {{ old('confidentiality') === 'high' ? 'checked' : '' }}>
                                <div class="option-content">
                                    <i class="fas fa-mask"></i>
                                    <div>
                                        <h4>{{ __('messages.blade_incident_form_blade_identity_protected_report') }}</h4>
                                        <p>{{ __('messages.blade_incident_form_blade_hide_your_identity_from_non_authorized_parties_as_much_') }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step4">{{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <div class="form-step" id="step4">
                    <h3><i class="fas fa-check-circle"></i> {{ __('messages.form_step_review_submit') }}</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-info-circle"></i> {{ __('messages.incident_form_step1_title') }}</h4>
                            <div class="review-content" id="reviewIncident"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-user-friends"></i> {{ __('messages.incident_form_step2_label') }}</h4>
                            <div class="review-content" id="reviewParties"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-upload"></i> {{ __('messages.incident_form_review_evidence_card') }}</h4>
                            <div class="review-content" id="reviewEvidence"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> {{ __('messages.incident_form_processing_card') }}</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>{{ __('messages.incident_form_fee_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item">
                                        <span>{{ __('messages.form_processing_fee_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item total">
                                        <span>{{ __('messages.form_total_label') }}</span>
                                        <span>₱0.00</span>
                                    </div>
                                </div>

                                <div class="pickup-instructions">
                                    <div class="pickup-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5><i class="fas fa-building"></i> {{ __('messages.incident_form_process_location_label') }}</h5>
                                            <p>{{ __('messages.blade_clearance_form_blade_barangay_hulong_duhat_hall') }}<br>
                                                {{ __('messages.footer_address') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <h5><i class="fas fa-calendar-alt"></i> {{ __('messages.contacts_hours_title') }}</h5>
                                            <p>{{ __('messages.form_hours_weekdays') }}<br>
                                                {{ __('messages.form_hours_saturday') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-id-card"></i>
                                        <div>
                                            <h5><i class="fas fa-clipboard-check"></i> {{ __('messages.incident_form_what_to_bring_label') }}</h5>
                                            <ul>
                                                <li>{{ __('messages.incident_form_bring_id') }}</li>
                                                <li>{{ __('messages.incident_form_bring_reference') }}</li>
                                                <li>{{ __('messages.incident_form_bring_evidence') }}</li>
                                                <li>{{ __('messages.incident_form_bring_witnesses') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> {{ __('messages.incident_form_benefits_title') }}</h4>
                        <div class="benefits-grid row g-12">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_1_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_1_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_2_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_2_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>{{ __('messages.incident_form_benefit_3_title') }}</h5>
                                <p class="mb-0">{{ __('messages.incident_form_benefit_3_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="declareTruth" name="declareTruth" required {{ old('declareTruth') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.incident_form_terms_1') }}
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="agreePrivacy" name="agreePrivacy" required {{ old('agreePrivacy') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            <span>{{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" onclick="event.stopPropagation();">{{ __('messages.register_privacy_title') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditionsModal" onclick="event.stopPropagation();">{{ __('messages.terms_modal_title') }}</a> {{ __('messages.blade_clearance_form_blade_of_barangay_hulong_duhat_online_services') }}</span>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="consentProcessing" name="consentProcessing" required {{ old('consentProcessing') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.incident_form_terms_consent') }}
                        </label>
                    </div>
                    <div id="termsError">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.form_terms_error') }}
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step3"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit" id="submitBlotter">
                                <span class="btn-text">{{ __('messages.incident_form_submit_btn') }}</span>
                                <div class="spinner">
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @include('barangay_system.partials.policy_modals')

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('blotterForm');
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const progressFill = document.getElementById('progressFill');
            const progressSteps = Array.from(document.querySelectorAll('.step'));
            const nextButtons = Array.from(document.querySelectorAll('.btn-next'));
            const prevButtons = Array.from(document.querySelectorAll('.btn-prev[data-prev]'));
            const declareTruth = document.getElementById('declareTruth');
            const agreePrivacy = document.getElementById('agreePrivacy');
            const consentProcessing = document.getElementById('consentProcessing');
            const termsError = document.getElementById('termsError');
            const addWitnessBtn = document.getElementById('addWitnessBtn');
            const witnessesList = document.getElementById('witnessesList');
            const witnessCountHint = document.getElementById('witnessCountHint');
            const totalSteps = 4;
            const MAX_WITNESSES = 5;
            let currentStep = 1;

            const FILE_RULES = {
                photos: { uploadAreaId: 'photoUpload', previewId: 'photoPreview', maxSizeMB: 10, allowedExtensions: ['jpg', 'jpeg', 'png'], allowedMimeTypes: ['image/jpeg', 'image/jpg', 'image/png'], label: 'photo' },
                videos: { uploadAreaId: 'videoUpload', previewId: 'videoPreview', maxSizeMB: 50, allowedExtensions: ['mp4', 'avi', 'mov'], allowedMimeTypes: ['video/mp4', 'video/avi', 'video/x-msvideo', 'video/quicktime'], label: 'video' },
                documents: { uploadAreaId: 'docUpload', previewId: 'docPreview', maxSizeMB: 5, allowedExtensions: ['pdf', 'doc', 'docx'], allowedMimeTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], label: 'document' }
            };

            initializeDateTimeDefaults();
            initializeRealtimeValidation();
            initializeWitnesses();
            initializeFileUploads();
            initializeNavigation();
            initializeSubmit();
            updateProgress();

            function byId(id) { return document.getElementById(id); }
            function getValue(id) { const field = byId(id); return field ? field.value.trim() : ''; }

            function initializeDateTimeDefaults() {
                const now = new Date();
                const todayIso = toDateInputValue(now);
                const incidentDateInput = byId('incidentDate');
                const incidentTimeInput = byId('incidentTime');
                if (incidentDateInput) { incidentDateInput.max = todayIso; if (!incidentDateInput.value) incidentDateInput.value = todayIso; }
                if (incidentTimeInput && !incidentTimeInput.value) incidentTimeInput.value = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            }

            function initializeRealtimeValidation() {
                ['incidentDate','incidentTime','incidentLocation','incidentDescription','immediateAction','complainantName','complainantContact','complainantAddress','complainantEmail','respondentName','respondentContact','respondentAddress','respondentDescription','additionalInfo'].forEach(id => {
                    const field = byId(id);
                    if (!field) return;
                    field.addEventListener('input', () => clearFieldError(field));
                    field.addEventListener('change', () => clearFieldError(field));
                });
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        const group = radio.closest('.form-group')?.querySelector('.report-type-grid, .confidentiality-options');
                        if (group) clearFieldError(group);
                    });
                });
            }

            function initializeWitnesses() {
                updateWitnessUi();
                if (!addWitnessBtn) return;
                addWitnessBtn.addEventListener('click', function() {
                    const currentWitnesses = getWitnessItems();
                    if (currentWitnesses.length >= MAX_WITNESSES) {
                        showFieldError(witnessesList || addWitnessBtn, @json(__('messages.blade_jsdyn_you_can_add_up_to_max_witnesses_only')).replace(':max', MAX_WITNESSES));
                        updateWitnessUi();
                        return;
                    }
                    const witnessId = `witness-${Date.now()}`;
                    const witnessNumber = currentWitnesses.length + 1;
                    const witnessHtml = `
                        <div class="witness-item" id="${witnessId}">
                            <div class="witness-header">
                                <span class="witness-number">${@json(__('messages.blade_jsdyn_witness_number')).replace(':number', witnessNumber)}</span>
                                <button type="button" class="remove-witness" data-remove-witness="${witnessId}" aria-label="${@json(__('messages.blade_dynamic_remove_witness'))}"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="form-grid row g-3">
                                <div class="form-group col-md-12">
                                    <label><i class="fas fa-user"></i> ${@json(__('messages.blade_dynamic_witness_name'))}</label>
                                    <input name="witnesses[][name]" type="text" class="form-control witness-name" maxlength="120" placeholder="${@json(__('messages.blade_dynamic_witness_name_placeholder'))}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label><i class="fas fa-phone"></i> ${@json(__('messages.register_contact_label'))}</label>
                                    <input name="witnesses[][contact]" type="text" class="form-control witness-contact" maxlength="11" placeholder="09XX XXX XXXX">
                                </div>
                                <div class="form-group full-width">
                                    <label><i class="fas fa-comment-dots"></i> ${@json(__('messages.blade_dynamic_witness_statement'))}</label>
                                    <textarea name="witnesses[][statement]" class="form-control witness-statement" maxlength="1000" placeholder="${@json(__('messages.blade_dynamic_witness_statement_placeholder'))}"></textarea>
                                    <div class="form-hint">${@json(__('messages.blade_dynamic_witness_hint'))}</div>
                                </div>
                            </div>
                        </div>`;
                    witnessesList.insertAdjacentHTML('beforeend', witnessHtml);
                    const newWitness = byId(witnessId);
                    newWitness.querySelector('[data-remove-witness]').addEventListener('click', function() { newWitness.remove(); renumberWitnesses(); updateWitnessUi(); });
                    newWitness.querySelectorAll('input, textarea').forEach(field => field.addEventListener('input', () => validateWitnessItem(newWitness, false)));
                    updateWitnessUi();
                });
            }

            function getWitnessItems() { return Array.from(document.querySelectorAll('.witness-item')); }
            function renumberWitnesses() { getWitnessItems().forEach((item, index) => { const label = item.querySelector('.witness-number'); if (label) label.textContent = @json(__('messages.blade_jsdyn_witness_number')).replace(':number', index + 1); }); }
            function updateWitnessUi() {
                const count = getWitnessItems().length;
                if (witnessCountHint) witnessCountHint.textContent = @json(__('messages.blade_jsdyn_count_of_max_witnesses_added')).replace(':count', count).replace(':max', MAX_WITNESSES);
                if (addWitnessBtn) {
                    addWitnessBtn.disabled = count >= MAX_WITNESSES;
                    addWitnessBtn.innerHTML = count >= MAX_WITNESSES ? `<i class="fas fa-circle-check"></i> ${@json(__('messages.blade_dynamic_maximum_witnesses_added'))}` : `<i class="fas fa-user-plus"></i> ${@json(__('messages.incident_form_add_witness_btn'))}`;
                }
            }

            function initializeFileUploads() { Object.entries(FILE_RULES).forEach(([inputId, rules]) => setupFileUpload(inputId, rules)); }
            function setupFileUpload(fileInputId, rules) {
                const uploadArea = byId(rules.uploadAreaId), fileInput = byId(fileInputId), previewArea = byId(rules.previewId);
                if (!uploadArea || !fileInput || !previewArea) return;
                uploadArea.addEventListener('click', () => fileInput.click());
                ['dragenter','dragover','dragleave','drop'].forEach(eventName => uploadArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }));
                ['dragenter','dragover'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.add('highlight')));
                ['dragleave','drop'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('highlight')));
                uploadArea.addEventListener('drop', e => { if (e.dataTransfer.files.length > 0) { fileInput.files = e.dataTransfer.files; handleFiles(fileInput, rules); } });
                fileInput.addEventListener('change', () => handleFiles(fileInput, rules));
            }
            function handleFiles(fileInput, rules) {
                const uploadArea = byId(rules.uploadAreaId), previewArea = byId(rules.previewId), files = Array.from(fileInput.files || []);
                clearFieldError(uploadArea); uploadArea.classList.remove('error'); previewArea.innerHTML = '';
                const validationErrors = validateFiles(files, rules);
                if (validationErrors.length) { fileInput.value = ''; uploadArea.classList.add('error'); showFieldError(uploadArea, validationErrors[0]); previewArea.classList.remove('active'); return; }
                if (!files.length) { previewArea.classList.remove('active'); return; }
                previewArea.classList.add('active');
                files.forEach(file => {
                    const filePreview = document.createElement('div');
                    filePreview.className = 'file-preview';
                    filePreview.innerHTML = `<i class="fas ${getFileIcon(file)}"></i><div class="file-info"><div class="file-name">${escapeHtml(file.name)}</div><div class="file-size">${formatFileSize(file.size)}</div></div><button type="button" class="remove-file" aria-label="${@json(__('messages.blade_dynamic_remove_selected_files'))}"><i class="fas fa-times"></i></button>`;
                    filePreview.querySelector('.remove-file').addEventListener('click', e => { e.stopPropagation(); fileInput.value = ''; previewArea.innerHTML = ''; previewArea.classList.remove('active'); uploadArea.classList.remove('error','highlight'); clearFieldError(uploadArea); });
                    previewArea.appendChild(filePreview);
                });
            }
            function validateFiles(files, rules) {
                const errors = [], maxSize = rules.maxSizeMB * 1024 * 1024;
                files.forEach(file => {
                    const extension = file.name.split('.').pop().toLowerCase();
                    const validExtension = rules.allowedExtensions.includes(extension);
                    const validMime = !file.type || rules.allowedMimeTypes.includes(file.type);
                    if (!validExtension || !validMime) errors.push(@json(__('messages.blade_jsdyn_invalid_label_file_type_file')).replace(':label', rules.label).replace(':file', file.name));
                    if (file.size > maxSize) errors.push(@json(__('messages.blade_jsdyn_file_exceeds_the_sizemb_limit')).replace(':file', file.name).replace(':size', rules.maxSizeMB));
                });
                return errors;
            }

            function initializeNavigation() {
                nextButtons.forEach(button => button.addEventListener('click', function(e) { e.preventDefault(); const nextStep = this.getAttribute('data-next'); if (validateStep(currentStep, true)) goToStep(nextStep); }));
                prevButtons.forEach(button => button.addEventListener('click', function(e) { e.preventDefault(); goToStep(this.getAttribute('data-prev')); }));
            }
            function initializeSubmit() {
                if (!form) return;
                form.addEventListener('submit', function(e) {
                    for (let step = 1; step <= totalSteps; step++) {
                        if (!validateStep(step, false)) { e.preventDefault(); goToStep(`step${step}`); validateStep(step, true); return; }
                    }
                    const submitButton = byId('submitBlotter');
                    if (submitButton) { submitButton.classList.add('loading'); submitButton.disabled = true; }
                });
                [declareTruth, agreePrivacy, consentProcessing].forEach(cb => { if (cb) cb.addEventListener('change', validateTermsQuietly); });
            }

            function validateStep(stepNumber, shouldScroll = true) {
                const stepElement = byId(`step${stepNumber}`);
                if (!stepElement) return true;
                clearStepErrors(stepElement);
                let isValid = true;
                if (stepNumber === 1) isValid = validateIncidentInfo() && isValid;
                if (stepNumber === 2) isValid = validateParties() && isValid;
                if (stepNumber === 3) isValid = validateEvidenceAndPreference() && isValid;
                if (stepNumber === 4) isValid = validateTerms() && isValid;
                if (!isValid && shouldScroll) scrollToFirstError(stepElement);
                return isValid;
            }
            function validateIncidentInfo() {
                let valid = true;
                const selectedReportType = document.querySelector('input[name="reportType"]:checked');
                const reportTypeGrid = document.querySelector('.report-type-grid');
                const incidentDate = byId('incidentDate'), incidentTime = byId('incidentTime'), incidentLocation = byId('incidentLocation'), incidentDescription = byId('incidentDescription'), immediateAction = byId('immediateAction');
                if (!selectedReportType) { showFieldError(reportTypeGrid, @json(__('messages.blade_js_please_select_the_type_of_report'))); valid = false; }
                if (!requireField(incidentDate, @json(__('messages.blade_js_incident_date_is_required')))) valid = false;
                if (!requireField(incidentTime, @json(__('messages.blade_js_approximate_incident_time_is_required')))) valid = false;
                if (incidentDate.value) {
                    const selectedDate = parseLocalDate(incidentDate.value), now = new Date(), todayOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    if (selectedDate > todayOnly) { showFieldError(incidentDate, @json(__('messages.blade_js_incident_date_cannot_be_in_the_future'))); valid = false; }
                    if (incidentTime.value && sameDate(selectedDate, todayOnly)) {
                        const selectedDateTime = new Date(`${incidentDate.value}T${incidentTime.value}`);
                        if (selectedDateTime > now) { showFieldError(incidentTime, @json(__('messages.blade_js_incident_time_cannot_be_later_than_the_current_time'))); valid = false; }
                    }
                }
                if (!validateTextLength(incidentLocation, 8, 255, @json(__('messages.blade_js_location_must_be_at_least_8_characters')), @json(__('messages.blade_js_location_is_too_long')))) valid = false;
                if (!validateTextLength(incidentDescription, 20, 5000, @json(__('messages.blade_js_description_must_be_at_least_20_characters_so_officials_ca')), @json(__('messages.blade_js_description_is_too_long')))) valid = false;
                if (immediateAction.value.trim().length > 2000) { showFieldError(immediateAction, @json(__('messages.blade_js_immediate_actions_must_not_exceed_2_000_characters'))); valid = false; }
                return valid;
            }
            function validateParties() {
                let valid = true;
                const complainantName = byId('complainantName'), complainantContact = byId('complainantContact'), complainantAddress = byId('complainantAddress'), complainantEmail = byId('complainantEmail');
                const respondentName = byId('respondentName'), respondentContact = byId('respondentContact'), respondentAddress = byId('respondentAddress'), respondentDescription = byId('respondentDescription');
                if (!validatePersonName(complainantName, true, @json(__('messages.blade_js_complainant_name_is_required')))) valid = false;
                if (!validatePhone(complainantContact, true)) valid = false;
                if (!validateTextLength(complainantAddress, 10, 255, @json(__('messages.blade_js_please_enter_a_complete_address')), @json(__('messages.blade_js_address_is_too_long')))) valid = false;
                if (!validateEmail(complainantEmail, false)) valid = false;
                if (!validatePersonName(respondentName, false)) valid = false;
                if (!validatePhone(respondentContact, false)) valid = false;
                if (respondentAddress.value.trim() && !validateTextLength(respondentAddress, 5, 255, @json(__('messages.blade_js_respondent_address_must_be_at_least_5_characters')), @json(__('messages.blade_js_respondent_address_is_too_long')))) valid = false;
                if (respondentDescription.value.trim().length > 1000) { showFieldError(respondentDescription, @json(__('messages.blade_js_respondent_description_must_not_exceed_1_000_characters'))); valid = false; }
                const respondentHasSupportingDetails = respondentContact.value.trim() || respondentAddress.value.trim();
                const respondentHasIdentity = respondentName.value.trim() || respondentDescription.value.trim();
                if (respondentHasSupportingDetails && !respondentHasIdentity) { showFieldError(respondentDescription, @json(__('messages.blade_js_if_you_add_respondent_contact_or_address_also_enter_the_re'))); valid = false; }
                const witnessItems = getWitnessItems();
                if (witnessItems.length > MAX_WITNESSES) { showFieldError(witnessesList, @json(__('messages.blade_jsdyn_you_can_add_up_to_max_witnesses_only')).replace(':max', MAX_WITNESSES)); valid = false; }
                witnessItems.forEach(item => { if (!validateWitnessItem(item, true)) valid = false; });
                return valid;
            }
            function validateEvidenceAndPreference() {
                let valid = true;
                const selectedConfidentiality = document.querySelector('input[name="confidentiality"]:checked'), confidentialityOptions = document.querySelector('.confidentiality-options'), additionalInfo = byId('additionalInfo');
                Object.entries(FILE_RULES).forEach(([inputId, rules]) => {
                    const input = byId(inputId), uploadArea = byId(rules.uploadAreaId);
                    if (!input || !uploadArea) return;
                    const errors = validateFiles(Array.from(input.files || []), rules);
                    if (errors.length) { uploadArea.classList.add('error'); showFieldError(uploadArea, errors[0]); valid = false; }
                });
                if (additionalInfo.value.trim().length > 2000) { showFieldError(additionalInfo, @json(__('messages.blade_js_additional_information_must_not_exceed_2_000_characters'))); valid = false; }
                if (!selectedConfidentiality) { showFieldError(confidentialityOptions, @json(__('messages.blade_js_please_select_a_confidentiality_preference'))); valid = false; }
                return valid;
            }
            function validateTerms() {
                const accepted = declareTruth?.checked && agreePrivacy?.checked && consentProcessing?.checked;
                if (!accepted) { if (termsError) termsError.style.display = 'block'; return false; }
                if (termsError) termsError.style.display = 'none'; return true;
            }
            function validateTermsQuietly() { if (termsError) termsError.style.display = (declareTruth?.checked && agreePrivacy?.checked && consentProcessing?.checked) ? 'none' : 'block'; }
            function validateWitnessItem(item, showEmptyErrors) {
                const nameInput = item.querySelector('.witness-name'), contactInput = item.querySelector('.witness-contact'), statementInput = item.querySelector('.witness-statement');
                const name = nameInput.value.trim(), contact = contactInput.value.trim(), statement = statementInput.value.trim();
                let valid = true;
                clearFieldError(nameInput); clearFieldError(contactInput); clearFieldError(statementInput);
                if (!name && !statement) { if (showEmptyErrors || contact) { showFieldError(nameInput, @json(__('messages.blade_js_enter_witness_name_or_witness_statement'))); showFieldError(statementInput, @json(__('messages.blade_js_enter_witness_statement_or_witness_name'))); } return false; }
                if (name && !validatePersonName(nameInput, false, '', @json(__('messages.blade_js_witness_name_can_only_contain_letters_spaces_apostrophes_p')))) valid = false;
                if (statement && statement.length < 5) { showFieldError(statementInput, @json(__('messages.blade_js_witness_statement_must_be_at_least_5_characters'))); valid = false; }
                if (statement.length > 1000) { showFieldError(statementInput, @json(__('messages.blade_js_witness_statement_must_not_exceed_1_000_characters'))); valid = false; }
                if (!validatePhone(contactInput, false)) valid = false;
                return valid;
            }
            function requireField(field, message) { if (!field || !field.value.trim()) { showFieldError(field, message); return false; } return true; }
            function validateTextLength(field, min, max, minMessage, maxMessage) {
                const value = field.value.trim();
                if (!value) { showFieldError(field, @json(__('messages.blade_js_this_field_is_required'))); return false; }
                if (value.length < min) { showFieldError(field, minMessage); return false; }
                if (value.length > max) { showFieldError(field, maxMessage); return false; }
                return true;
            }
            function validatePersonName(field, required = false, requiredMessage = @json(__('messages.blade_js_name_is_required')), invalidMessage = @json(__('messages.blade_js_name_can_only_contain_letters_spaces_apostrophes_periods_c'))) {
                const value = field.value.trim(), nameRegex = /^[\p{L}\s'.,-]+$/u;
                if (!value) { if (required) showFieldError(field, requiredMessage); return !required; }
                if (value.length < 2) { showFieldError(field, @json(__('messages.blade_runtime_name_min_2'))); return false; }
                if (value.length > 120) { showFieldError(field, @json(__('messages.blade_runtime_name_max_120'))); return false; }
                if (!nameRegex.test(value)) { showFieldError(field, invalidMessage); return false; }
                return true;
            }
            function validatePhone(field, required = false) {
                const value = field.value.replace(/\s|-/g, '').trim(), phoneRegex = /^09\d{9}$/;
                if (!value) { if (required) showFieldError(field, @json(__('messages.blade_runtime_contact_required'))); return !required; }
                if (!phoneRegex.test(value)) { showFieldError(field, @json(__('messages.blade_runtime_mobile_format'))); return false; }
                field.value = value; return true;
            }
            function validateEmail(field, required = false) {
                const value = field.value.trim(), emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) { if (required) showFieldError(field, @json(__('messages.blade_runtime_email_required'))); return !required; }
                if (value.length > 255 || !emailRegex.test(value)) { showFieldError(field, @json(__('messages.validation_email_format'))); return false; }
                return true;
            }
            function clearStepErrors(stepElement) {
                stepElement.querySelectorAll('.error-message').forEach(error => error.remove());
                stepElement.querySelectorAll('.error').forEach(field => { field.classList.remove('error'); field.style.borderColor = ''; field.style.boxShadow = ''; });
                if (termsError && stepElement.contains(termsError)) termsError.style.display = 'none';
            }
            function clearFieldError(field) {
                if (!field) return;
                field.classList.remove('error'); field.style.borderColor = ''; field.style.boxShadow = '';
                const container = getErrorContainer(field);
                if (container) container.querySelectorAll(':scope > .error-message').forEach(error => error.remove());
            }
            function showFieldError(field, message) {
                if (!field) return;
                field.classList.add('error'); field.style.borderColor = '#dc2626'; field.style.boxShadow = '0 0 0 4px rgba(220, 38, 38, 0.10)';
                const container = getErrorContainer(field); if (!container) return;
                const duplicate = Array.from(container.querySelectorAll(':scope > .error-message')).some(error => error.textContent === message);
                if (duplicate) return;
                const errorMessage = document.createElement('div'); errorMessage.className = 'error-message'; errorMessage.textContent = message; container.appendChild(errorMessage);
            }
            function getErrorContainer(field) {
                if (!field) return null;
                if (field.classList.contains('report-type-grid') || field.classList.contains('confidentiality-options') || field.classList.contains('upload-area') || field.classList.contains('witnesses-list')) return field.parentElement;
                return field.closest('.form-group') || field.parentElement;
            }
            function scrollToFirstError(stepElement) { const firstError = stepElement.querySelector('.error-message, .error'); if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
            function goToStep(stepId) { steps.forEach(step => step.classList.remove('active')); const targetStep = byId(stepId); if (!targetStep) return; targetStep.classList.add('active'); currentStep = parseInt(stepId.replace('step',''),10); updateProgress(); if (currentStep === 4) updateReview(); targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            function updateProgress() { const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100; if (progressFill) progressFill.style.width = `${progressPercentage}%`; progressSteps.forEach(step => { const stepNumber = parseInt(step.getAttribute('data-step'),10); step.classList.toggle('active', stepNumber === currentStep); }); }
            function updateReview() {
                const selectedReport = document.querySelector('input[name="reportType"]:checked'), selectedConfidentiality = document.querySelector('input[name="confidentiality"]:checked');
                const reportType = selectedReport ? selectedReport.closest('.report-type-option')?.querySelector('h4')?.textContent.trim() || selectedReport.value : @json(__('messages.blade_incident_review_not_selected'));
                const confidentialityMap = { low: @json(__('messages.blade_incident_form_blade_standard_report')), medium: 'Confidential Report', high: @json(__('messages.blade_incident_form_blade_identity_protected_report')) };
                const incidentReview = byId('reviewIncident'), partiesReview = byId('reviewParties'), evidenceReview = byId('reviewEvidence');
                if (incidentReview) incidentReview.innerHTML = reviewRows([[@json(__('messages.blade_incident_review_report_type')), reportType], [@json(__('messages.blade_incident_review_incident_date_time')), @json(__('messages.blade_incident_review_date_at_time')).replace(':date', formatDate(getValue('incidentDate'))).replace(':time', getValue('incidentTime') || @json(__('messages.blade_js_not_specified')))], [@json(__('messages.events_location_label')), getValue('incidentLocation') || @json(__('messages.blade_js_not_specified'))], [@json(__('messages.blade_incident_review_description')), summarize(getValue('incidentDescription'), 140)]]);
                if (partiesReview) partiesReview.innerHTML = reviewRows([[@json(__('messages.blade_incident_success_blade_complainant')), getValue('complainantName') || @json(__('messages.blade_js_not_specified'))], [@json(__('messages.register_contact_label')), getValue('complainantContact') || @json(__('messages.blade_js_not_specified'))], [@json(__('messages.blade_incident_review_respondent')), getValue('respondentName') || getValue('respondentDescription') || @json(__('messages.blade_js_not_specified'))], [@json(__('messages.incident_form_witnesses_title')), @json(__('messages.blade_incident_review_count_witness_es_added')).replace(':count', getWitnessItems().length)]]);
                if (evidenceReview) evidenceReview.innerHTML = reviewRows([[@json(__('messages.blade_incident_review_photos')), @json(__('messages.blade_incident_review_count_file_s_uploaded')).replace(':count', byId('photos')?.files.length || 0)], [@json(__('messages.blade_incident_review_videos')), @json(__('messages.blade_incident_review_count_file_s_uploaded')).replace(':count', byId('videos')?.files.length || 0)], [@json(__('messages.track_filter_documents')), @json(__('messages.blade_incident_review_count_file_s_uploaded')).replace(':count', byId('documents')?.files.length || 0)], [@json(__('messages.blade_incident_review_confidentiality')), selectedConfidentiality ? confidentialityMap[selectedConfidentiality.value] : @json(__('messages.blade_incident_review_not_selected'))]]);
            }
            function reviewRows(rows) { return rows.map(([label, value]) => `<div class="review-item"><div class="review-label">${escapeHtml(label)}</div><div class="review-value">${escapeHtml(value)}</div></div>`).join(''); }
            function summarize(text, limit) { if (!text) return @json(__('messages.blade_js_not_specified')); return text.length > limit ? `${text.substring(0, limit)}...` : text; }
            function formatDate(dateString) { if (!dateString) return @json(__('messages.blade_js_not_specified')); const date = parseLocalDate(dateString); if (Number.isNaN(date.getTime())) return 'Invalid date'; return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }); }
            function parseLocalDate(dateString) { const [year, month, day] = dateString.split('-').map(Number); return new Date(year, month - 1, day); }
            function sameDate(a, b) { return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate(); }
            function toDateInputValue(date) { return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`; }
            function getFileIcon(file) { const name = file.name.toLowerCase(); if (file.type.includes('image') || name.match(/\.(jpg|jpeg|png)$/)) return 'fa-file-image'; if (file.type.includes('video') || name.match(/\.(mp4|avi|mov)$/)) return 'fa-file-video'; if (name.endsWith('.pdf')) return 'fa-file-pdf'; if (name.match(/\.(doc|docx)$/)) return 'fa-file-word'; return 'fa-file'; }
            function formatFileSize(bytes) { if (bytes === 0) return '0 Bytes'; const k = 1024, sizes = ['Bytes','KB','MB','GB'], i = Math.floor(Math.log(bytes) / Math.log(k)); return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`; }
            function escapeHtml(value) { return String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }
        });
    </script>

</body>
</html>