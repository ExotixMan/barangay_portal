@php
    $adminSettings = app(\App\Services\AdminSettingsService::class);

    $portalName = $adminSettings->get('general.portal_name', 'Barangay Hulo Portal');
    $primaryColor = $adminSettings->get('general.primary_color', '#c62828');
    $secondaryColor = $adminSettings->get('general.secondary_color', '#8e0000');

    $captchaEnabled = (bool) $adminSettings->get('security.captcha_enabled', false);
    $recaptchaSiteKey = $adminSettings->get('security.recaptcha_site_key', '');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="--primary: {{ $primaryColor }}; --primary-dark: {{ $secondaryColor }};">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.blade_register_register_portal', ['portal' => $portalName]) }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #c62828;
            --primary-dark: #8e0000;
            --primary-soft: #fff1f2;
            --primary-50: #fff7f7;
            --primary-border: rgba(198, 40, 40, 0.16);
            --success: #15803d;
            --danger: #dc2626;
            --warning: #b45309;
            --info: #2563eb;
            --text: #101828;
            --text-2: #344054;
            --muted: #667085;
            --soft: #f8fafc;
            --page: #f4f6f8;
            --card: #ffffff;
            --line: #e4e7ec;
            --line-strong: #d0d5dd;
            --field: #ffffff;
            --field-soft: #f9fafb;
            --focus: rgba(198, 40, 40, 0.15);
            --radius-xl: 28px;
            --radius-lg: 22px;
            --radius: 16px;
            --radius-sm: 12px;
            --shadow: 0 24px 70px rgba(16, 24, 40, 0.10);
            --shadow-soft: 0 8px 24px rgba(16, 24, 40, 0.06);
            --transition: 0.2s ease;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            padding: 22px 16px 42px;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(198, 40, 40, 0.12), transparent 34rem),
                radial-gradient(circle at 90% 8%, rgba(142, 0, 0, 0.08), transparent 28rem),
                linear-gradient(180deg, #fff 0%, var(--page) 40%, #eef2f6 100%);
            font-family: 'Inter', 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.5;
        }

        .back-button-container,
        .form-page-header,
        .main-container {
            width: min(100%, 1120px);
            margin-inline: auto;
        }

        .back-button-container {
            margin-bottom: 14px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-height: 42px;
            padding: 0.62rem 0.95rem;
            border-radius: 999px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(208, 213, 221, 0.92);
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            backdrop-filter: blur(14px);
            transition: color var(--transition), border-color var(--transition), background var(--transition), transform var(--transition);
        }

        .btn-back:hover {
            color: var(--primary);
            border-color: var(--primary-border);
            background: #fff;
            transform: translateX(-2px);
        }

        .form-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            margin-bottom: 18px;
            padding: 10px 4px 2px;
        }

        .form-page-header h1 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.45rem;
            color: var(--text);
            font-size: clamp(1.85rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.055em;
            line-height: 1.05;
        }

        .form-page-header h1 i {
            width: 50px;
            height: 50px;
            display: inline-grid;
            place-items: center;
            flex: 0 0 50px;
            border-radius: 18px;
            color: var(--primary);
            background: #fff;
            border: 1px solid var(--primary-border);
            box-shadow: 0 10px 24px rgba(198, 40, 40, 0.10);
            font-size: 1.1rem;
        }

        .form-page-header p {
            max-width: 640px;
            margin: 0;
            color: var(--muted);
            font-size: 1rem;
            font-weight: 600;
        }

        .main-container {
            padding-bottom: 20px;
        }

        .form-container {
            overflow: hidden;
            border-radius: var(--radius-xl);
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(228, 231, 236, 0.94);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .register-panel-header {
            padding: clamp(1.05rem, 2.6vw, 1.55rem);
            color: #fff;
            background:
                linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }

        .register-panel-header > * {
            position: relative;
            z-index: 1;
        }

        .register-panel-header .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 0.7rem;
            padding: 0.42rem 0.7rem;
            border-radius: 999px;
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.22);
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .register-panel-header h2 {
            max-width: 650px;
            margin: 0 0 0.45rem;
            color: #fff;
            font-size: clamp(1.45rem, 3vw, 2.25rem);
            font-weight: 900;
            letter-spacing: -0.045em;
            line-height: 1.08;
        }

        .register-panel-header p {
            max-width: 680px;
            margin: 0;
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.98rem;
            font-weight: 550;
        }

        .panel-checklist {
            display: grid;
            gap: 0.65rem;
            align-self: stretch;
        }

        .panel-chip {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-height: 48px;
            padding: 0.72rem 0.85rem;
            border-radius: 16px;
            color: #fff;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.20);
            font-size: 0.84rem;
            font-weight: 850;
            backdrop-filter: blur(10px);
        }

        .panel-chip i {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            color: var(--primary-dark);
            background: #fff;
        }

        .form-progress,
        .form-step,
        .alert {
            margin-inline: clamp(0.95rem, 2.2vw, 1.45rem);
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
            padding: 0.9rem 1rem;
            border-radius: var(--radius);
            border: 1px solid transparent;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: none;
        }

        .alert-success {
            color: #166534;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .alert-danger {
            color: #991b1b;
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .alert ul {
            margin-left: 1rem;
            margin-bottom: 0;
        }

        .btn-close {
            margin-left: auto;
            opacity: 0.65;
        }

        .form-progress {
            margin-top: 1.2rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 20px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.7rem;
            margin-bottom: 0.7rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
            padding: 0.72rem;
            border-radius: 15px;
            color: var(--muted);
            background: var(--field-soft);
            border: 1px solid transparent;
            transition: background var(--transition), border-color var(--transition), color var(--transition);
        }

        .step-circle {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            flex: 0 0 36px;
            border-radius: 999px;
            color: var(--text-2);
            background: #fff;
            border: 1px solid var(--line);
            font-size: 0.86rem;
            font-weight: 900;
            transition: inherit;
        }

        .step span {
            min-width: 0;
            color: inherit;
            font-size: 0.9rem;
            font-weight: 850;
            text-align: left;
        }

        .step.active {
            color: var(--primary-dark);
            background: var(--primary-soft);
            border-color: var(--primary-border);
        }

        .step.active .step-circle {
            color: #fff;
            background: var(--primary);
            border-color: var(--primary);
        }

        .progress-bar {
            height: 7px;
            overflow: hidden;
            border-radius: 999px;
            background: #eef0f3;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: width 0.35s ease;
        }

        .form-step {
            display: none;
            padding-bottom: clamp(1rem, 2.4vw, 1.5rem);
        }

        .form-step.active {
            display: block;
            animation: fadeStep 0.22s ease;
        }

        @keyframes fadeStep {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-step h3 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 1rem;
            color: var(--text);
            font-size: 1.1rem;
            font-weight: 900;
            letter-spacing: -0.025em;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
        }

        .form-step h3 i {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            font-size: 0.92rem;
        }

        .row.g-3 {
            --bs-gutter-x: 0.95rem;
            --bs-gutter-y: 0.1rem;
        }

        .form-section-card {
            margin: 1rem 0;
            padding: clamp(0.95rem, 2vw, 1.15rem);
            border-radius: 22px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .section-header h4 {
            margin: 0 0 0.25rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .section-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.84rem;
            font-weight: 550;
        }

        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            margin-bottom: 0.42rem;
            color: var(--primary);
            font-size: 0.73rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            white-space: nowrap;
            padding: 0.45rem 0.65rem;
            border-radius: 999px;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            font-size: 0.76rem;
            font-weight: 850;
        }

        .form-group {
            margin-bottom: 0.95rem;
        }

        .form-group label,
        .captcha-section label {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            margin-bottom: 0.42rem;
            color: #1f2937;
            font-size: 0.86rem;
            font-weight: 800;
        }

        .form-group label i,
        .captcha-section label i {
            color: var(--primary);
            font-size: 0.82rem;
        }

        .form-control,
        select.form-control,
        textarea.form-control {
            width: 100%;
            min-height: 47px;
            padding: 0.76rem 0.86rem;
            border: 1px solid var(--line-strong);
            border-radius: 13px;
            color: var(--text);
            background: var(--field);
            font-family: inherit;
            font-size: 0.94rem;
            font-weight: 560;
            box-shadow: none;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        }

        .form-control::placeholder {
            color: #98a2b3;
            font-weight: 500;
        }

        .form-control:hover {
            border-color: #b8c0cc;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--focus);
        }

        .form-control[readonly] {
            color: var(--text-2);
            background: #f9fafb;
            cursor: not-allowed;
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-image: none;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12);
        }

        textarea.form-control {
            min-height: 96px;
            resize: vertical;
        }

        .form-hint {
            display: block;
            margin-top: 0.35rem;
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 550;
        }

        .form-hint.good { color: var(--success); }
        .form-hint.bad { color: var(--danger); }

        .error-message {
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
            width: fit-content;
            max-width: 100%;
            margin-top: 0.42rem;
            padding: 0.45rem 0.6rem;
            border-radius: 10px;
            color: #991b1b;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .error-message::before {
            content: "!";
            display: inline-grid;
            place-items: center;
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
            border-radius: 999px;
            color: #fff;
            background: var(--danger);
            font-size: 0.7rem;
            font-weight: 900;
            line-height: 1;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper .form-control {
            padding-right: 44px;
        }

        .toggle-password {
            position: absolute;
            right: 7px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 10px;
            color: var(--muted);
            background: transparent;
            cursor: pointer;
            transition: color var(--transition), background var(--transition);
        }

        .toggle-password:hover {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .password-meter {
            height: 6px;
            margin: 0.55rem 0 0.25rem;
            overflow: hidden;
            border-radius: 999px;
            background: #eef0f3;
        }

        .password-meter-fill {
            width: 10%;
            height: 100%;
            background: #d16060;
            border-radius: inherit;
            transition: width var(--transition), background-color var(--transition);
        }

        .password-checklist {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.35rem 0.8rem;
            margin: 0.55rem 0 0;
            padding: 0;
            list-style: none;
        }

        .password-checklist li {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 650;
        }

        .password-checklist li::before {
            content: "";
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 999px;
            background: #cbd5e1;
        }

        .password-checklist li.is-complete { color: var(--success); }
        .password-checklist li.is-complete::before { background: var(--success); }

        .id-upload-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .scan-card {
            padding: 0.9rem;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fbfcfd;
        }

        .scan-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .scan-card-title {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: var(--text);
            font-weight: 900;
            font-size: 0.92rem;
        }

        .scan-card-title i {
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
        }

        .required-pill {
            padding: 0.25rem 0.5rem;
            border-radius: 999px;
            color: #991b1b;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            font-size: 0.68rem;
            font-weight: 900;
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-input {
            position: relative;
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr);
            align-items: center;
            gap: 0.75rem;
            min-height: 78px;
            padding: 0.75rem;
            border: 1.6px dashed var(--line-strong);
            border-radius: 16px;
            background: #fff;
            cursor: pointer;
            transition: border-color var(--transition), background var(--transition), transform var(--transition);
        }

        .file-upload-input:hover {
            border-color: var(--primary);
            background: var(--primary-50);
            transform: translateY(-1px);
        }

        .file-upload-wrapper.is-invalid .file-upload-input {
            border-color: var(--danger);
            background: #fff1f2;
        }

        .file-upload-input input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            pointer-events: none;
        }

        .file-upload-copy {
            min-width: 0;
            pointer-events: none;
        }

        .file-upload-button {
            display: block;
            color: var(--text);
            font-size: 0.88rem;
            font-weight: 900;
        }

        .file-upload-text {
            display: block;
            min-width: 0;
            overflow: hidden;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 650;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-upload-info,
        .address-preview {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-top: 0.65rem;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .file-upload-info i,
        .address-preview i {
            color: var(--primary);
            margin-top: 0.1rem;
        }

        .address-preview {
            padding: 0.8rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid var(--line);
        }

        .address-preview strong {
            color: var(--text-2);
        }


        .verification-note {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            margin-bottom: 0.9rem;
            padding: 0.82rem 0.9rem;
            border-radius: 16px;
            color: #1e3a8a;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .verification-note i {
            margin-top: 0.08rem;
            color: var(--info);
        }

        .id-preview-shell {
            position: relative;
            min-height: 170px;
            margin-bottom: 0.75rem;
            overflow: hidden;
            border-radius: 16px;
            background:
                linear-gradient(135deg, rgba(198, 40, 40, 0.07), rgba(255, 255, 255, 0.94)),
                #fff;
            border: 1px solid var(--line);
            display: grid;
            place-items: center;
        }

        .id-scan-preview {
            width: 100%;
            height: 100%;
            max-height: 230px;
            object-fit: contain;
            background: #0f172a;
        }

        .id-preview-placeholder {
            display: grid;
            place-items: center;
            gap: 0.35rem;
            padding: 1.1rem;
            color: var(--muted);
            text-align: center;
        }

        .id-preview-placeholder i {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            font-size: 1.05rem;
        }

        .id-preview-placeholder strong {
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 900;
        }

        .id-preview-placeholder span {
            max-width: 260px;
            font-size: 0.77rem;
            font-weight: 650;
        }

        .id-preview-shell:not(.is-empty) .id-preview-placeholder {
            display: none;
        }

        .scanner-action-row {
            display: flex;
            gap: 0.55rem;
            margin-bottom: 0.75rem;
        }

        .scan-both-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 0.9rem;
            margin-bottom: 0.95rem;
            padding: 0.95rem;
            border-radius: 18px;
            background: #fff;
            border: 1px solid var(--primary-border);
            box-shadow: 0 8px 22px rgba(198, 40, 40, 0.08);
        }

        .scan-both-panel strong {
            display: block;
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: -0.01em;
        }

        .scan-both-panel span {
            display: block;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 650;
        }

        .btn-scan-both {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 44px;
            padding: 0.72rem 1rem;
            border: 0;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 10px 22px rgba(198, 40, 40, 0.20);
            font-family: inherit;
            font-size: 0.86rem;
            font-weight: 900;
            cursor: pointer;
            white-space: nowrap;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .btn-scan-both:hover {
            transform: translateY(-1px);
            box-shadow: 0 15px 28px rgba(198, 40, 40, 0.26);
        }

        .scanner-flip-countdown {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.65rem;
            padding: 0.52rem 0.7rem;
            border-radius: 999px;
            color: #92400e;
            background: #fffbeb;
            border: 1px solid #fde68a;
            font-size: 0.78rem;
            font-weight: 900;
        }

        .btn-scan-id,
        .btn-scanner-primary,
        .btn-scanner-secondary,
        .btn-scanner-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 42px;
            padding: 0.68rem 0.88rem;
            border-radius: 13px;
            border: 1px solid transparent;
            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 900;
            line-height: 1;
            cursor: pointer;
            transition: transform var(--transition), box-shadow var(--transition), background var(--transition), border-color var(--transition), color var(--transition);
        }

        .btn-scan-id,
        .btn-scanner-primary {
            width: 100%;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 9px 18px rgba(198, 40, 40, 0.18);
        }

        .btn-scan-id:hover,
        .btn-scanner-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(198, 40, 40, 0.24);
        }

        .btn-scanner-secondary {
            color: var(--text-2);
            background: #fff;
            border-color: var(--line-strong);
        }

        .btn-scanner-secondary:hover {
            color: var(--text);
            background: var(--field-soft);
        }

        .btn-scanner-danger {
            color: #991b1b;
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .btn-scanner-danger:hover {
            color: #7f1d1d;
            background: #ffe4e6;
        }



        .scan-both-panel-single {
            grid-template-columns: minmax(0, 1fr) 230px;
            margin-bottom: 0.8rem;
        }

        .id-auto-scan-wrapper {
            padding: 0.9rem;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid var(--line);
        }

        .id-auto-scan-status {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--text-2);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .id-auto-scan-status > i {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            flex: 0 0 38px;
            border-radius: 13px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
        }

        .id-auto-scan-status strong,
        .id-auto-scan-status span {
            display: block;
        }

        .id-auto-scan-status strong {
            color: var(--text);
            font-size: 0.92rem;
            font-weight: 900;
            margin-bottom: 0.15rem;
        }

        .id-auto-scan-status.is-complete {
            color: #166534;
            background: #f0fdf4;
            border-radius: 14px;
            padding: 0.75rem;
            border: 1px solid #bbf7d0;
        }

        .id-auto-scan-status.is-complete > i {
            color: #166534;
            background: #dcfce7;
            border-color: #bbf7d0;
        }

        .id-auto-scan-wrapper.is-invalid {
            background: #fff1f2;
            border-color: #fecdd3;
        }

        #idScannerTargetText {
            display: none !important;
        }

        #idScannerManualCaptureBtn,
        .scanner-auto-option {
            display: none !important;
        }

        .btn-scan-both:disabled,
        .btn-scanner-primary:disabled,
        .btn-scanner-secondary:disabled {
            opacity: 0.72;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }


        .id-scanner-modal .modal-dialog {
            max-width: 930px;
        }

        .scanner-help {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            margin-bottom: 0.85rem;
            padding: 0.82rem 0.9rem;
            border-radius: 16px;
            color: var(--text-2);
            background: #f8fafc;
            border: 1px solid var(--line);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .scanner-help i {
            color: var(--primary);
            margin-top: 0.08rem;
        }

        .scanner-stage {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: #0f172a;
            border: 1px solid rgba(15, 23, 42, 0.12);
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
            border: 2px dashed rgba(255, 255, 255, 0.82);
            border-radius: 18px;
            box-shadow: 0 0 0 999px rgba(15, 23, 42, 0.28);
            pointer-events: none;
        }

        .scanner-status {
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.72rem 0.85rem;
            border-radius: 14px;
            color: #fff;
            background: rgba(15, 23, 42, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
            font-size: 0.8rem;
            font-weight: 800;
        }

        .scanner-status .status-dot {
            width: 10px;
            height: 10px;
            flex: 0 0 10px;
            border-radius: 999px;
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.18);
        }

        .scanner-status.is-ready .status-dot {
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
        }

        .scanner-status.is-error .status-dot {
            background: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.18);
        }

        .scanner-controls {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.6rem;
            margin-top: 0.85rem;
        }

        .scanner-auto-option {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 0.7rem;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 750;
        }

        .scanner-auto-option input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        #idScannerWorkCanvas,
        #idScannerResultCanvas {
            display: none;
        }
        .benefits-section {
            margin: 1.05rem 0;
            padding: 1rem;
            border-radius: 20px;
            background: #fff;
            border: 1px solid var(--line);
        }

        .benefits-section h4 {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 0.8rem;
            color: var(--text);
            font-size: 0.98rem;
            font-weight: 900;
            letter-spacing: -0.01em;
        }

        .benefits-section h4 i {
            color: var(--primary);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .benefit-item {
            padding: 0.9rem;
            border-radius: 16px;
            background: #f9fafb;
            border: 1px solid var(--line);
        }

        .benefit-item i {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            margin-bottom: 0.65rem;
            border-radius: 12px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            font-size: 0.92rem;
        }

        .benefit-item h5 {
            margin-bottom: 0.25rem;
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 900;
        }

        .benefit-item p {
            margin: 0;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 550;
        }

        .terms-section,
        .captcha-section {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 18px;
            background: #fff;
            border: 1px solid var(--line);
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin: 0;
            color: var(--text-2);
            font-size: 0.88rem;
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
            display: inline-block;
            width: 21px;
            height: 21px;
            flex: 0 0 21px;
            margin-top: 1px;
            border-radius: 7px;
            background: #fff;
            border: 1.5px solid var(--line-strong);
            transition: background var(--transition), border-color var(--transition);
        }

        .terms-checkbox:hover .checkmark {
            border-color: var(--primary);
        }

        .terms-checkbox input:checked ~ .checkmark {
            background: var(--primary);
            border-color: var(--primary);
        }

        .checkmark::after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .terms-checkbox input:checked ~ .checkmark::after {
            display: block;
        }

        .terms-checkbox a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 900;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .captcha-box {
            display: inline-block;
            max-width: 100%;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--line);
        }

        .btn-prev,
        .btn-next,
        .btn-submit,
        .modal-footer .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 46px;
            padding: 0.76rem 1.08rem;
            border-radius: 14px;
            border: 1px solid transparent;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 900;
            line-height: 1;
            cursor: pointer;
            text-decoration: none;
            transition: background var(--transition), border-color var(--transition), color var(--transition), transform var(--transition), box-shadow var(--transition);
        }

        .btn-prev {
            color: var(--text-2);
            background: #fff;
            border-color: var(--line-strong);
        }

        .btn-prev:hover {
            color: var(--text);
            background: var(--field-soft);
            border-color: #b8c0cc;
        }

        .btn-next,
        .btn-submit {
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: transparent;
            box-shadow: 0 10px 22px rgba(198, 40, 40, 0.20);
        }

        .btn-next:hover,
        .btn-submit:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(198, 40, 40, 0.25);
        }

        .btn-submit {
            min-width: 178px;
        }

        .btn-submit .spinner {
            display: none;
            gap: 4px;
        }

        .btn-submit.loading .spinner {
            display: inline-flex;
        }

        .spinner-dot {
            width: 5px;
            height: 5px;
            border-radius: 999px;
            background: #fff;
            animation: bounce 1.2s infinite ease-in-out both;
        }

        .spinner-dot:nth-child(1) { animation-delay: -0.24s; }
        .spinner-dot:nth-child(2) { animation-delay: -0.12s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        .shake { animation: shake 0.35s ease-in-out; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            50% { transform: translateX(4px); }
            75% { transform: translateX(-2px); }
        }

        .modal-content {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: var(--shadow);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--line);
            background: var(--field-soft);
        }

        .modal-title {
            color: var(--text);
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .modal-body {
            color: var(--text-2);
            font-size: 0.92rem;
        }

        .modal-body h6 {
            margin-top: 1rem;
            color: var(--text);
            font-weight: 900;
        }

        .modal-body h6:first-child { margin-top: 0; }

        .modal-footer .btn-secondary {
            color: var(--text-2);
            background: #fff;
            border-color: var(--line-strong);
        }

        .modal-footer .btn-secondary:hover {
            color: var(--text);
            background: var(--field-soft);
        }

        @media (max-width: 900px) {
            .form-page-header {
                display: block;
            }

            .register-panel-header {
                grid-template-columns: 1fr;
            }

            .panel-checklist {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px 10px 28px;
            }

            .form-page-header {
                padding-top: 8px;
                margin-bottom: 12px;
            }

            .form-page-header h1 {
                align-items: flex-start;
            }

            .form-container {
                border-radius: 22px;
            }

            .register-panel-header {
                padding: 1rem;
            }

            .panel-checklist,
            .progress-steps,
            .benefits-grid,
            .id-upload-grid {
                grid-template-columns: 1fr;
            }

            .form-progress,
            .form-step,
            .alert {
                margin-inline: 0.85rem;
            }

            .step {
                padding: 0.62rem;
            }

            .section-header {
                flex-direction: column;
            }

            .section-badge {
                width: 100%;
                justify-content: center;
            }

            .form-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btn-prev,
            .btn-next,
            .btn-submit {
                width: 100%;
            }

            .scanner-controls {
                grid-template-columns: 1fr;
            }

            .scan-both-panel-single {
                grid-template-columns: 1fr;
            }

            .scanner-status {
                align-items: flex-start;
                flex-direction: column;
            }


            .scan-both-panel {
                grid-template-columns: 1fr;
            }

            .btn-scan-both {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .form-page-header h1 i {
                width: 42px;
                height: 42px;
                flex-basis: 42px;
                border-radius: 14px;
            }

            .register-panel-header h2 {
                font-size: 1.5rem;
            }

            .form-progress {
                padding: 0.7rem;
            }

            .step span {
                font-size: 0.82rem;
            }

            .password-checklist {
                grid-template-columns: 1fr;
            }

            .file-upload-input {
                grid-template-columns: 38px minmax(0, 1fr);
            }

            .terms-checkbox {
                font-size: 0.84rem;
            }

            .captcha-section {
                overflow: hidden;
            }

            .captcha-box {
                transform: scale(0.86);
                transform-origin: left top;
            }
        }
    

        /* Mobile-first scanner and registration fixes */
        @media (max-width: 768px) {
            html,
            body {
                width: 100%;
                overflow-x: hidden;
            }

            body {
                padding: 10px 8px 24px;
            }

            .back-button-container,
            .form-page-header,
            .main-container {
                width: 100%;
            }

            .form-page-header h1 {
                font-size: 1.55rem;
                letter-spacing: -0.035em;
            }

            .form-page-header p,
            .register-panel-header p {
                font-size: 0.88rem;
            }

            .form-container {
                border-radius: 18px;
            }

            .form-progress,
            .form-step,
            .alert {
                margin-inline: 0.55rem;
            }

            .form-section-card,
            .benefits-section,
            .terms-section,
            .captcha-section {
                padding: 0.82rem;
                border-radius: 17px;
            }

            .section-header h4 {
                font-size: 0.95rem;
            }

            .section-header p {
                font-size: 0.78rem;
            }

            .scan-both-panel,
            .scan-both-panel-single {
                grid-template-columns: 1fr !important;
                padding: 0.82rem;
            }

            .btn-scan-both {
                width: 100%;
                min-height: 48px;
                white-space: normal;
            }

            .id-auto-scan-status {
                align-items: flex-start;
                font-size: 0.78rem;
            }

            .id-scanner-modal .modal-dialog {
                width: 100%;
                max-width: none;
                height: 100%;
                margin: 0;
            }

            .id-scanner-modal .modal-content {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
            }

            .id-scanner-modal .modal-header,
            .id-scanner-modal .modal-body {
                padding: 0.75rem;
            }

            .scanner-help {
                margin-bottom: 0.65rem;
                padding: 0.65rem;
                font-size: 0.76rem;
                border-radius: 14px;
            }

            .scanner-stage {
                height: min(64vh, 560px);
                min-height: 360px;
                aspect-ratio: auto;
                border-radius: 16px;
            }

            .scanner-guide {
                left: 7%;
                right: 7%;
                top: 34%;
                bottom: 34%;
                border-radius: 13px;
            }

            .scanner-status {
                left: 0.55rem;
                right: 0.55rem;
                bottom: 0.55rem;
                align-items: flex-start;
                flex-direction: column;
                padding: 0.62rem 0.7rem;
                font-size: 0.74rem;
            }

            .scanner-controls {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                margin-top: 0.65rem;
            }

            .btn-scanner-primary,
            .btn-scanner-secondary,
            .btn-scanner-danger {
                width: 100%;
                min-height: 46px;
            }
        }

        @media (max-width: 576px) {
            .scanner-guide {
                left: 5%;
                right: 5%;
                top: 18%;
                bottom: 18%;
                border-radius: 14px;
            }

            .scanner-stage {
                min-height: 430px;
                aspect-ratio: auto;
            }
        }

        @media (max-width: 420px) {
            .progress-steps {
                gap: 0.45rem;
            }

            .step {
                gap: 0.5rem;
                padding: 0.55rem;
            }

            .step-circle {
                width: 31px;
                height: 31px;
                flex-basis: 31px;
            }

            .step span {
                font-size: 0.75rem;
            }

            .scanner-stage {
                min-height: 430px;
                aspect-ratio: auto;
            }

            .scanner-guide {
                left: 5%;
                right: 5%;
                top: 18%;
                bottom: 18%;
                border-radius: 14px;
            }
        }

        @media (orientation: landscape) and (max-height: 520px) {
            .id-scanner-modal .modal-body {
                padding: 0.55rem;
            }

            .scanner-help {
                display: none;
            }

            .scanner-stage {
                height: 62vh;
                min-height: 240px;
            }

            .scanner-controls {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
</style>
</head>
@include('chatbot.embed')

<body>
    <!-- Back Button -->
    <div class="back-button-container">
        <a href="{{ route('login') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.register_back_btn') }}
        </a>
    </div>

    <!-- Page Header -->
    <div class="form-page-header">
        <h1><i class="fas fa-user-plus"></i> {{ __('messages.register_page_title') }}</h1>
        <p>{{ __('messages.register_page_subtitle') }}</p>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Registration Form -->
        <div class="form-container">
            <div class="register-panel-header">
                <div>
                    <span class="eyebrow"><i class="fas fa-shield-alt"></i> {{ __('messages.blade_register_blade_resident_registration') }}</span>
                    <h2>{{ __('messages.blade_register_blade_create_your_verified_barangay_hulong_duhat_account') }}</h2>
                    <p>{{ __('messages.blade_register_blade_use_clear_personal_details_a_complete_residency_address') }}</p>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register.res') }}" id="registerForm" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.register_step1_title') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.register_step2_title') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <!-- Step 1: Personal Details -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user"></i> {{ __('messages.register_step1_heading') }}</h3>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_first_name_label') }} *
                                </label>
                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_first_name') }}" class="form-control @error('firstname') is-invalid @enderror">
                                <div class="form-hint">{{ __('messages.form_name_hint') }}</div>
                                @error('firstname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middlename">
                                    <i class="fas fa-user"></i> {{ __('messages.register_middle_name_label') }}
                                </label>
                                <input type="text" id="middlename" name="middlename" value="{{ old('middlename') }}"
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_middle_name') }}" class="form-control @error('middlename') is-invalid @enderror">
                                <div class="form-hint">{{ __('messages.blade_register_blade_optional_but_helps_with_verification') }}</div>
                                @error('middlename')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_last_name_label') }} *
                                </label>
                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_enter_your_last_name') }}" class="form-control @error('lastname') is-invalid @enderror">
                                @error('lastname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="suffix">
                                    <i class="fas fa-user-tag"></i> {{ __('messages.register_suffix_label') }}
                                </label>
                                <select id="suffix" name="suffix" class="form-control @error('suffix') is-invalid @enderror">
                                    <option value="" {{ old('suffix') == '' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_none') }}</option>
                                    <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_jr') }}</option>
                                    <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_sr') }}</option>
                                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_ii') }}</option>
                                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_iii') }}</option>
                                    <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_iv') }}</option>
                                    <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_v') }}</option>
                                </select>
                                <div class="form-hint">{{ __('messages.blade_register_blade_optional_select_if_applicable') }}</div>
                                @error('suffix')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthdate">
                                    <i class="fas fa-birthday-cake"></i> {{ __('messages.register_dob_label') }} *
                                </label>
                                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                    class="form-control @error('birthdate') is-invalid @enderror">
                                @error('birthdate')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="place_of_birth">
                                    <i class="fas fa-location-dot"></i> {{ __('messages.form_birth_place_label') }}
                                </label>
                                <input type="text" id="place_of_birth" name="place_of_birth"
                                    value="{{ old('place_of_birth') }}" required
                                    placeholder="{{ __('messages.blade_register_blade_example_malabon_city') }}"
                                    class="form-control @error('place_of_birth') is-invalid @enderror">
                                @error('place_of_birth')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">
                                    <i class="fas fa-venus-mars"></i> {{ __('messages.form_gender_label') }}
                                </label>
                                <select id="gender" name="gender" required
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">{{ __('messages.blade_register_blade_select_gender') }}</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>{{ __('messages.form_gender_male') }}</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>{{ __('messages.form_gender_female') }}</option>
                                    <option value="Prefer not to say" {{ old('gender') === 'Prefer not to say' ? 'selected' : '' }}>{{ __('messages.form_gender_other') }}</option>
                                </select>
                                @error('gender')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="civil_status">
                                    <i class="fas fa-ring"></i> {{ __('messages.form_civil_status_label') }}
                                </label>
                                <select id="civil_status" name="civil_status" required
                                    class="form-control @error('civil_status') is-invalid @enderror">
                                    <option value="">{{ __('messages.blade_register_blade_select_civil_status') }}</option>
                                    <option value="Single" {{ old('civil_status') === 'Single' ? 'selected' : '' }}>{{ __('messages.civil_status_single') }}</option>
                                    <option value="Married" {{ old('civil_status') === 'Married' ? 'selected' : '' }}>{{ __('messages.civil_status_married') }}</option>
                                    <option value="Widowed" {{ old('civil_status') === 'Widowed' ? 'selected' : '' }}>{{ __('messages.civil_status_widowed') }}</option>
                                    <option value="Separated" {{ old('civil_status') === 'Separated' ? 'selected' : '' }}>{{ __('messages.civil_status_separated') }}</option>
                                </select>
                                @error('civil_status')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact">
                                    <i class="fas fa-phone"></i> {{ __('messages.register_contact_label') }} *
                                </label>
                                <input type="tel" id="contact" name="contact" value="{{ old('contact') }}" required
                                    placeholder="{{ __('messages.blade_incident_form_blade_09xx_xxx_xxxx') }}" class="form-control @error('contact') is-invalid @enderror"
                                    pattern="[0-9]{11}" maxlength="11" inputmode="numeric">
                                <div class="form-hint" id="contactHint">{{ __('messages.blade_register_blade_use_11_digits_only_e_g_09123456789_we_will_check_this_b') }}</div>
                                @error('contact')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> {{ __('messages.register_email_label') }} *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="{{ __('messages.blade_incident_form_blade_your_email_example_com') }}" class="form-control @error('email') is-invalid @enderror"
                            autocomplete="email">
                        <div class="form-hint" id="emailHint">{{ __('messages.blade_register_blade_we_will_verify_this_email_for_account_updates_and_notif') }}</div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section-card residency-card">
                        <div class="section-header">
                            <div>
                                <span class="section-kicker"><i class="fas fa-map-location-dot"></i> {{ __('messages.blade_register_blade_residency_address') }}</span>
                                <h4>{{ __('messages.blade_register_blade_tell_us_exactly_where_you_reside') }}</h4>
                                <p>{{ __('messages.blade_register_blade_split_fields_make_the_address_easier_for_residents_to_f') }}</p>
                            </div>
                            <span class="section-badge"><i class="fas fa-circle-info"></i> {{ __('messages.blade_register_blade_barangay_and_city_are_fixed') }}</span>
                        </div>

                        <input type="hidden" id="address" name="address" value="{{ old('address') }}">

                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="house_no"><i class="fas fa-house"></i> {{ __('messages.blade_register_blade_house_no_unit_no') }}</label>
                                    <input type="text" id="house_no" name="house_no" value="{{ old('house_no') }}" required
                                        placeholder="{{ __('messages.blade_register_blade_example_25') }}" class="form-control js-address-field @error('house_no') is-invalid @enderror">
                                    @error('house_no')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="street"><i class="fas fa-road"></i> {{ __('messages.blade_register_blade_street') }}</label>
                                    <input type="text" id="street" name="street" value="{{ old('street') }}" required
                                        placeholder="{{ __('messages.blade_register_blade_example_camia_street') }}" class="form-control js-address-field @error('street') is-invalid @enderror">
                                    @error('street')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purok"><i class="fas fa-location-dot"></i> {{ __('messages.blade_register_blade_purok') }}</label>
                                    <select id="purok" name="purok" required class="form-control js-address-field @error('purok') is-invalid @enderror">
                                        <option value="" {{ old('purok') == '' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_select_purok') }}</option>
                                        @for ($i = 1; $i <= 15; $i++)
                                            <option value="{{ __('messages.blade_register_purok_number', ['number' => $i]) }}" {{ old('purok') == ('Purok ' . $i) ? 'selected' : '' }}>{{ __('messages.blade_register_purok_number', ['number' => $i]) }}</option>
                                        @endfor
                                        <option value="I don't know" {{ old('purok') == "I don't know" ? 'selected' : '' }}>{{ __('messages.blade_register_blade_i_don_t_know') }}</option>
                                    </select>
                                    @error('purok')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="barangay"><i class="fas fa-map-pin"></i> {{ __('messages.blade_register_blade_barangay') }}</label>
                                    <input type="text" id="barangay" name="barangay" value="{{ old('barangay', 'Hulong Duhat') }}" readonly required
                                        class="form-control js-address-field @error('barangay') is-invalid @enderror">
                                    @error('barangay')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city"><i class="fas fa-city"></i> {{ __('messages.blade_register_blade_city') }}</label>
                                    <input type="text" id="city" name="city" value="{{ old('city', 'Malabon City') }}" readonly required
                                        class="form-control js-address-field @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="residency_status"><i class="fas fa-user-check"></i> {{ __('messages.blade_register_blade_residency_status') }}</label>
                                    <select id="residency_status" name="residency_status" required class="form-control js-address-field @error('residency_status') is-invalid @enderror">
                                        <option value="" {{ old('residency_status') == '' ? 'selected' : '' }}>{{ __('messages.form_civil_status_placeholder') }}</option>
                                        <option value="Resident" {{ old('residency_status') == 'Resident' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_resident') }}</option>
                                        <option value="Non-resident" {{ old('residency_status') == 'Non-resident' ? 'selected' : '' }}>{{ __('messages.blade_register_blade_non_resident') }}</option>
                                        <option value="For verification" {{ old('residency_status') == 'For verification' ? 'selected' : '' }}>{{ __('messages.blade_profile_blade_for_verification') }}</option>
                                    </select>
                                    @error('residency_status')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="nearest_landmark"><i class="fas fa-landmark"></i> {{ __('messages.blade_register_blade_nearest_landmark') }}</label>
                            <input type="text" id="nearest_landmark" name="nearest_landmark" value="{{ old('nearest_landmark') }}" required
                                placeholder="{{ __('messages.blade_register_blade_example_near_chapel_school_or_covered_court') }}"
                                class="form-control js-address-field @error('nearest_landmark') is-invalid @enderror">
                            <div class="form-hint">{{ __('messages.blade_register_blade_use_a_place_staff_can_recognize_during_verification') }}</div>
                            @error('nearest_landmark')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="address-preview">
                            <i class="fas fa-eye"></i>
                            <span><strong>{{ __('messages.blade_register_blade_address_preview') }}</strong> <span id="addressPreviewText">{{ __('messages.blade_register_blade_complete_the_fields_above_to_build_the_full_address') }}</span></span>
                        </div>

                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section-card verification-card">
                        <div class="section-header">
                            <div>
                                <span class="section-kicker"><i class="fas fa-id-card-clip"></i> {{ __('messages.blade_register_blade_valid_id_scanner') }}</span>
                                <h4>{{ __('messages.blade_register_blade_scan_both_sides_of_your_valid_id') }}</h4>
                                <p>{{ __('messages.blade_register_blade_the_camera_scanner_detects_the_id_card_crops_only_the_i') }}</p>
                            </div>
                            <span class="section-badge"><i class="fas fa-user-shield"></i> {{ __('messages.blade_register_blade_auto_crop_admin_review') }}</span>
                        </div>

                        <div class="verification-note">
                            <i class="fas fa-circle-info"></i>
                            <span>{{ __('messages.blade_register_blade_this_scanner_helps_capture_a_clear_front_and_back_image') }}</span>
                        </div>

                        <div class="scan-both-panel scan-both-panel-single">
                            <div>
                                <strong>{{ __('messages.blade_register_blade_automatic_id_scan') }}</strong>
                                <span>{{ __('messages.blade_register_blade_click_once_only_the_scanner_captures_the_front_side_tel') }}</span>
                            </div>
                            <button type="button" class="btn-scan-both" id="scanBothSidesBtn">
                                <i class="fas fa-camera-retro"></i> {{ __('messages.blade_profile_blade_start_id_scan') }}
                            </button>
                        </div>

                        <div class="file-upload-wrapper id-auto-scan-wrapper" id="idAutoScanWrapper">
                            <input type="file" id="valid_id" name="valid_id"
                                accept="image/*" required
                                class="form-control js-file-input visually-hidden @error('valid_id') is-invalid @enderror"
                                data-file-text="validIdFrontText">
                            <input type="file" id="valid_id_back" name="valid_id_back"
                                accept="image/*" required
                                class="form-control js-file-input visually-hidden @error('valid_id_back') is-invalid @enderror"
                                data-file-text="validIdBackText">

                            <div class="id-auto-scan-status" id="idAutoScanStatus">
                                <i class="fas fa-id-card-clip"></i>
                                <div>
                                    <strong id="idAutoScanStatusTitle">{{ __('messages.blade_register_blade_no_id_scan_yet') }}</strong>
                                    <span id="idAutoScanStatusText">{{ __('messages.blade_register_blade_press_start_id_scan_the_system_will_save_both_sides_onl') }}</span>
                                    <span class="d-none" id="validIdFrontText">{{ __('messages.blade_profile_blade_no_front_file_selected') }}</span>
                                    <span class="d-none" id="validIdBackText">{{ __('messages.blade_profile_blade_no_back_file_selected') }}</span>
                                </div>
                            </div>

                            @error('valid_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            @error('valid_id_back')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('login') }}" class="btn-prev">
                            <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                        </a>
                        <button type="button" class="btn-next" data-next="step2" id="nextToStep2">
                            {{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Account Setup -->
                <div class="form-step" id="step2">
                    <h3><i class="fas fa-lock"></i> {{ __('messages.register_step2_title') }}</h3>

                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user-circle"></i> {{ __('messages.register_username_label') }} *
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            placeholder="{{ __('messages.blade_register_blade_choose_a_username') }}" class="form-control @error('username') is-invalid @enderror"
                            autocomplete="username">
                        <div class="form-hint" id="usernameHint">{{ __('messages.blade_register_blade_this_will_be_your_login_username') }}</div>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_password_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password" name="password" required
                                        placeholder="{{ __('messages.blade_register_blade_create_a_password') }}"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password"
                                        onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                    <button type="button" class="toggle-password" data-target="password"
                                        aria-label="{{ __('messages.blade_register_blade_show_or_hide_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>

                                <!-- Password Strength Meter -->
                                <div class="password-meter">
                                    <div class="password-meter-fill" id="passwordMeterFill"></div>
                                </div>
                                <small class="form-hint" id="passwordStrengthText">{{ __('messages.blade_register_blade_password_strength_too_weak') }}</small>

                                <ul class="password-checklist">
                                    <li data-rule="length">{{ __('messages.blade_register_blade_at_least_8_characters') }}</li>
                                    <li data-rule="number">{{ __('messages.blade_register_blade_contains_a_number') }}</li>
                                    <li data-rule="upper">{{ __('messages.blade_register_blade_contains_uppercase_letter') }}</li>
                                    <li data-rule="special">{{ __('messages.blade_register_blade_contains_special_character') }}</li>
                                    <li style="grid-column: 1 / -1;"><strong>{{ __('messages.blade_register_blade_no_spaces_allowed') }}</strong></li>
                                </ul>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_confirm_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required placeholder="{{ __('messages.blade_register_blade_re_enter_your_password') }}"
                                        class="form-control"
                                        autocomplete="new-password"
                                        onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                    <button type="button" class="toggle-password" data-target="password_confirmation"
                                        aria-label="{{ __('messages.blade_register_blade_show_or_hide_confirm_password') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-hint" id="passwordMatchText">{{ __('messages.blade_register_blade_passwords_must_match') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-circle-check"></i> {{ __('messages.blade_register_blade_after_registration') }}</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <i class="fas fa-clock"></i>
                                <h5>{{ __('messages.blade_register_blade_online_requests') }}</h5>
                                <p>{{ __('messages.blade_register_blade_submit_barangay_forms_online') }}</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <h5>{{ __('messages.blade_certificate_indigency_blade_status_tracking') }}</h5>
                                <p>{{ __('messages.blade_register_blade_check_updates_from_your_account') }}</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-bolt"></i>
                                <h5>{{ __('messages.blade_register_blade_less_waiting') }}</h5>
                                <p>{{ __('messages.blade_register_blade_prepare_requests_before_visiting') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">{{ __('messages.blade_register_blade_terms_of_service') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal"
                                data-bs-target="#privacyModal">{{ __('messages.register_privacy_title') }}</a>
                        </label>
                        @error('terms')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($captchaEnabled)
                        <div class="captcha-section">
                            <label for="recaptchaBox" class="mb-2">
                                <i class="fas fa-shield-alt"></i> {{ __('messages.blade_register_blade_security_check') }}
                            </label>

                            @if (!empty($recaptchaSiteKey))
                                <div class="captcha-box" id="recaptchaBox">
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                </div>
                            @else
                                <div class="error-message">{{ __('messages.blade_register_blade_captcha_is_not_configured_please_contact_support') }}</div>
                            @endif

                            @error('g-recaptcha-response')
                                <div class="error-message" id="captchaError">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="button" class="btn-prev" data-prev="step1">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}
                        </button>
                        <button type="submit" class="btn-submit" id="createAccountBtn">
                            <span class="btn-text">{{ __('messages.register_submit_btn') }}</span>
                            <div class="spinner">
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">{{ __('messages.terms_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <h6>{{ __('messages.blade_register_blade_barangay_hulo_online_portal_terms_of_service') }}</h6>
                    <p>{{ __('messages.blade_register_last_updated_date', ['date' => date('F d, Y')]) }}</p>

                    <h6>{{ __('messages.blade_register_blade_1_acceptance_of_terms') }}</h6>
                    <p>{{ __('messages.blade_register_blade_by_accessing_and_using_the_barangay_hulo_online_portal_') }}</p>

                    <h6>{{ __('messages.blade_register_blade_2_user_registration') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_must_provide_accurate_and_complete_information_duri') }}</p>

                    <h6>{{ __('messages.blade_register_blade_3_eligibility') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_must_be_at_least_18_years_old_to_register_by_regist') }}</p>

                    <h6>{{ __('messages.blade_register_blade_4_valid_id_requirement') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_are_required_to_upload_a_valid_government_issued_id') }}</p>

                    <h6>{{ __('messages.blade_register_blade_5_privacy_and_data_protection') }}</h6>
                    <p>{{ __('messages.blade_register_blade_your_personal_information_will_be_collected_stored_and_') }}</p>

                    <h6>{{ __('messages.blade_register_blade_6_acceptable_use') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_agree_to_use_the_portal_only_for_lawful_purposes_yo') }}</p>
                    <ul>
                        <li>{{ __('messages.blade_register_blade_violate_any_laws_or_regulations') }}</li>
                        <li>{{ __('messages.blade_register_blade_impersonate_any_person_or_entity') }}</li>
                        <li>{{ __('messages.blade_register_blade_submit_false_or_misleading_information') }}</li>
                        <li>{{ __('messages.blade_register_blade_interfere_with_the_proper_functioning_of_the_portal') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_7_account_termination') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_reserve_the_right_to_suspend_or_terminate_your_accou') }}</p>

                    <h6>{{ __('messages.blade_register_blade_8_changes_to_terms') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_may_modify_these_terms_at_any_time_continued_use_of_') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">{{ __('messages.register_privacy_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <h6>{{ __('messages.blade_register_blade_barangay_hulo_privacy_policy') }}</h6>
                    <p>{{ __('messages.blade_register_last_updated_date', ['date' => date('F d, Y')]) }}</p>

                    <h6>{{ __('messages.blade_register_blade_1_information_we_collect') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_collect_personal_information_including_but_not_limit') }}</p>
                    <ul>
                        <li>{{ __('messages.form_review_label_full_name') }}</li>
                        <li>{{ __('messages.register_dob_label') }}</li>
                        <li>{{ __('messages.register_contact_label') }}</li>
                        <li>{{ __('messages.register_email_label') }}</li>
                        <li>{{ __('messages.register_address_label') }}</li>
                        <li>{{ __('messages.blade_register_blade_valid_id_image_pdf') }}</li>
                        <li>{{ __('messages.blade_register_blade_username_and_password') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_2_how_we_use_your_information') }}</h6>
                    <p>{{ __('messages.blade_register_blade_your_information_is_used_to') }}</p>
                    <ul>
                        <li>{{ __('messages.blade_register_blade_process_your_requests_and_applications') }}</li>
                        <li>{{ __('messages.blade_register_blade_verify_your_identity_and_residency') }}</li>
                        <li>{{ __('messages.blade_register_blade_communicate_important_announcements') }}</li>
                        <li>{{ __('messages.blade_register_blade_improve_our_services') }}</li>
                        <li>{{ __('messages.blade_register_blade_comply_with_legal_requirements') }}</li>
                    </ul>

                    <h6>{{ __('messages.blade_register_blade_3_data_protection') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_implement_appropriate_technical_and_organizational_m') }}</p>

                    <h6>{{ __('messages.blade_register_blade_4_data_sharing') }}</h6>
                    <p>{{ __('messages.blade_register_blade_we_do_not_sell_or_rent_your_personal_information_inform') }}</p>

                    <h6>{{ __('messages.blade_register_blade_5_your_rights') }}</h6>
                    <p>{{ __('messages.blade_register_blade_you_have_the_right_to_access_correct_or_request_deletio') }}</p>

                    <h6>{{ __('messages.blade_register_blade_6_contact_information') }}</h6>
                    <p>
                        {{ __('messages.blade_register_blade_barangay_hulo_hall') }}<br>
                        {{ __('messages.blade_register_blade_hulong_duhat_malabon_city') }}<br>
                        {{ __('messages.blade_register_blade_email_barangayhulongduhat_gmail_com') }}<br>
                        {{ __('messages.blade_register_blade_tel_8_281_1373') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.modal_close_btn') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ID Scanner Modal -->
    <div class="modal fade id-scanner-modal" id="idScannerModal" tabindex="-1" aria-labelledby="idScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="idScannerModalLabel">{{ __('messages.blade_profile_blade_automatic_valid_id_scanner') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="scanner-help">
                        <i class="fas fa-lightbulb"></i>
                        <span>{{ __('messages.blade_register_blade_place_the_id_inside_the_guide_the_scanner_automatically') }}</span>
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
                            <span id="idScannerTargetText" class="d-none">{{ __('messages.blade_register_blade_id_scan') }}</span>
                        </div>
                    </div>

                    <canvas id="idScannerWorkCanvas"></canvas>
                    <canvas id="idScannerResultCanvas"></canvas>

                    <div class="scanner-controls">
                        <button type="button" class="btn-scanner-secondary" id="idScannerRetakeBtn">
                            <i class="fas fa-rotate-right"></i> {{ __('messages.blade_profile_blade_restart_camera') }}
                        </button>
                        <button type="button" class="btn-scanner-primary" id="idScannerManualCaptureBtn" disabled aria-hidden="true">
                            <i class="fas fa-camera"></i> {{ __('messages.blade_register_blade_auto_capture_enabled') }}
                        </button>
                        <button type="button" class="btn-scanner-danger" data-bs-dismiss="modal">
                            <i class="fas fa-xmark"></i> {{ __('messages.register_cancel_btn') }}
                        </button>
                    </div>

                    <label class="scanner-auto-option" for="idScannerAutoCapture">
                        <input type="checkbox" id="idScannerAutoCapture" checked>
                        {{ __('messages.blade_register_blade_auto_capture_when_the_id_is_detected_and_steady') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script>
        window.cvReady = false;
        window.onOpenCvReady = function () {
            window.cvReady = true;
            document.dispatchEvent(new Event('opencv-ready'));
        };
    </script>
    <script async src="https://docs.opencv.org/4.x/opencv.js" onload="window.onOpenCvReady()" onerror="window.cvReady = false;"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("registerForm");
            if (!form) return;

            // Get form elements
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');
            const submitBtn = document.getElementById('createAccountBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const meterFill = document.getElementById('passwordMeterFill');
            const strengthText = document.getElementById('passwordStrengthText');
            const matchText = document.getElementById('passwordMatchText');
            const termsCheckbox = document.getElementById('terms');
            const passwordChecklist = Array.from(document.querySelectorAll(".password-checklist li"));
            const usernameHint = document.getElementById('usernameHint');
            const emailHint = document.getElementById('emailHint');
            const contactHint = document.getElementById('contactHint');

            const contactInput = document.getElementById("contact");
            const emailInput = document.getElementById("email");
            const birthdateInput = document.getElementById("birthdate");
            const uploadInputs = document.querySelectorAll(".js-file-input");
            const addressInput = document.getElementById("address");
            const addressPreviewText = document.getElementById("addressPreviewText");
            const addressFieldIds = ["house_no", "street", "purok", "barangay", "city", "nearest_landmark", "residency_status"];
            const recaptchaBox = document.getElementById("recaptchaBox");
            const recaptchaEnabled = {!! ($captchaEnabled && !empty($recaptchaSiteKey)) ? 'true' : 'false' !!};

            let usernameCheckTimer = null;
            let usernameCheckSeq = 0;
            let isProgrammaticSubmit = false;
            const usernameCheckState = {
                value: '',
                status: 'idle',
                message: '',
            };

            let currentStep = 1;
            const totalSteps = 2;

            function getFieldValue(id) {
                const field = document.getElementById(id);
                return field ? field.value.trim() : '';
            }

            function buildResidentAddress() {
                if (!addressInput) return '';

                const houseNo = getFieldValue('house_no');
                const street = getFieldValue('street');
                const purok = getFieldValue('purok');
                const barangay = getFieldValue('barangay') || 'Hulong Duhat';
                const city = getFieldValue('city') || 'Malabon City';
                const landmark = getFieldValue('nearest_landmark');
                const residencyStatus = getFieldValue('residency_status');

                const addressParts = [
                    houseNo ? `${houseNo}` : '',
                    street,
                    purok,
                    barangay,
                    city,
                    landmark ? `Landmark: ${landmark}` : '',
                    residencyStatus ? @json(__('messages.blade_jsdyn_residency_status_status')).replace(':status', residencyStatus) : '',
                ].filter(Boolean);

                const completeAddress = addressParts.join(', ');
                addressInput.value = completeAddress;

                if (addressPreviewText) {
                    addressPreviewText.textContent = completeAddress || @json(__('messages.blade_register_blade_complete_the_fields_above_to_build_the_full_address'));
                }

                return completeAddress;
            }

            addressFieldIds.forEach((id) => {
                const field = document.getElementById(id);
                if (!field) return;
                field.addEventListener('input', buildResidentAddress);
                field.addEventListener('change', buildResidentAddress);
            });

            // ============= SIMPLE NAME VALIDATION - WITH ACCENTED CHARACTER SUPPORT =============
            const nameFields = ['firstname', 'middlename', 'lastname'];

            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    // Validate on blur (when user leaves the field)
                    field.addEventListener('blur', function() {
                        validateNameField(this);
                    });

                    // Clear error when user starts typing
                    field.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                        const existingError = this.parentNode.querySelector('.error-message');
                        if (existingError) {
                            existingError.remove();
                        }
                    });
                }
            });

            // SIMPLE name validation function that works with accented characters
            function validateNameField(field) {
                const fieldValue = field.value.trim();
                const fieldName = field.id.charAt(0).toUpperCase() + field.id.slice(1);
                
                // Clear any existing error
                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Check if field is required and empty
                if (field.hasAttribute('required') && !fieldValue) {
                    showFieldError(field, @json(__('messages.blade_jsdyn_field_is_required')).replace(':field', fieldName));
                    return false;
                }
                
                // If field has value, validate format
                if (fieldValue) {
                    const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;
                    
                    if (!nameRegex.test(fieldValue)) {
                        showFieldError(field, @json(__('messages.validation_name_format')));
                        return false;
                    }
                    
                    // Check minimum length
                    if (fieldValue.length < 2) {
                        showFieldError(field, @json(__('messages.validation_name_min_length')));
                        return false;
                    }
                }
                
                return true;
            }

            // ============= USERNAME VALIDATION - NO SPACES ALLOWED =============
            const usernameField = document.getElementById('username');
            if (usernameField) {
                // Automatically remove spaces while typing
                usernameField.addEventListener('input', function(e) {
                    // Replace spaces with empty string
                    this.value = this.value.replace(/\s/g, '');

                    // Clear error
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.error-message');
                    if (existingError) {
                        existingError.remove();
                    }

                    if (usernameHint) {
                        usernameHint.textContent = @json(__('messages.blade_register_blade_checking_username_availability'));
                        usernameHint.classList.remove('good', 'bad');
                    }

                    if (usernameCheckTimer) {
                        clearTimeout(usernameCheckTimer);
                    }

                    usernameCheckTimer = setTimeout(() => {
                        checkUsernameAvailability(this.value);
                    }, 350);
                });

                usernameField.addEventListener('blur', function() {
                    validateUsernameField(this);
                    checkUsernameAvailability(this.value, true);
                });
            }

            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    this.value = this.value.trim().toLowerCase();
                    checkAccountAvailability('email', this.value, emailHint, @json(__('messages.form_email_label')));
                });

                emailInput.addEventListener('blur', function() {
                    checkAccountAvailability('email', this.value, emailHint, @json(__('messages.form_email_label')), true);
                });
            }

            if (contactInput) {
                contactInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 11);
                    checkAccountAvailability('contact', this.value, contactHint, @json(__('messages.register_contact_label')));
                });

                contactInput.addEventListener('blur', function() {
                    checkAccountAvailability('contact', this.value, contactHint, @json(__('messages.register_contact_label')), true);
                });
            }

            async function checkUsernameAvailability(rawUsername, force = false) {
                const username = String(rawUsername || '').trim().toLowerCase();

                if (!force && username === usernameCheckState.value && (usernameCheckState.status === 'available' || usernameCheckState.status === 'unavailable')) {
                    return usernameCheckState.status === 'available';
                }

                if (username === '') {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'idle';
                    usernameCheckState.message = '';
                    if (usernameHint) {
                        usernameHint.textContent = @json(__('messages.blade_register_blade_this_will_be_your_login_username'));
                        usernameHint.classList.remove('good', 'bad');
                    }
                    return false;
                }

                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(username) || username.length < 4) {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'invalid';
                    usernameCheckState.message = @json(__('messages.blade_runtime_username_format_invalid'));
                    if (usernameHint) {
                        usernameHint.textContent = username.length < 4
                            ? @json(__('messages.blade_js_username_must_be_at_least_4_characters_long'))
                            : @json(__('messages.blade_runtime_username_allowed_chars'));
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }

                const requestId = ++usernameCheckSeq;
                usernameCheckState.value = username;
                usernameCheckState.status = 'checking';
                usernameCheckState.message = @json(__('messages.blade_register_blade_checking_username_availability'));

                if (usernameHint) {
                    usernameHint.textContent = @json(__('messages.blade_register_blade_checking_username_availability'));
                    usernameHint.classList.remove('good', 'bad');
                }

                try {
                    const response = await fetch(`/register/check-availability?field=username&value=${encodeURIComponent(username)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const data = contentType.includes('application/json')
                        ? await response.json()
                        : { message: @json(__('messages.blade_runtime_username_route_invalid')) };

                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    if (response.ok && data.available) {
                        usernameCheckState.status = 'available';
                        usernameCheckState.message = data.message || @json(__('messages.blade_runtime_username_available'));
                        if (usernameHint) {
                            usernameHint.textContent = usernameCheckState.message;
                            usernameHint.classList.remove('bad');
                            usernameHint.classList.add('good');
                        }
                        return true;
                    }

                    usernameCheckState.status = 'unavailable';
                    usernameCheckState.message = data.message || @json(__('messages.blade_js_username_is_already_taken'));
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                } catch (error) {
                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    usernameCheckState.status = 'error';
                    usernameCheckState.message = @json(__('messages.blade_runtime_username_check_unavailable'));
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }
            }

            const accountCheckSeq = {
                email: 0,
                contact: 0,
            };

            const accountCheckState = {
                email: { value: '', status: 'idle', message: '' },
                contact: { value: '', status: 'idle', message: '' },
            };

            async function checkAccountAvailability(field, rawValue, hintEl, label, force = false) {
                const normalized = field === 'contact'
                    ? String(rawValue || '').replace(/\D/g, '')
                    : String(rawValue || '').trim().toLowerCase();

                const defaultMessage = field === 'contact'
                    ? @json(__('messages.blade_runtime_contact_default_hint'))
                    : @json(__('messages.blade_runtime_email_default_hint'));

                if (!accountCheckState[field]) {
                    accountCheckState[field] = { value: '', status: 'idle', message: '' };
                    accountCheckSeq[field] = 0;
                }

                if (normalized === '') {
                    accountCheckState[field] = { value: normalized, status: 'idle', message: '' };
                    if (hintEl) {
                        hintEl.textContent = defaultMessage;
                        hintEl.classList.remove('good', 'bad');
                    }
                    return false;
                }

                if (field === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(normalized)) {
                    accountCheckState[field] = { value: normalized, status: 'invalid', message: @json(__('messages.validation_email_format')) };
                    if (hintEl) {
                        hintEl.textContent = @json(__('messages.validation_email_format'));
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }

                if (field === 'contact' && !/^09\d{9}$/.test(normalized)) {
                    accountCheckState[field] = { value: normalized, status: 'invalid', message: @json(__('messages.validation_contact_format')) };
                    if (hintEl) {
                        hintEl.textContent = normalized.length < 11
                            ? @json(__('messages.blade_runtime_complete_mobile_first'))
                            : @json(__('messages.validation_contact_format'));
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }

                if (!force && accountCheckState[field].value === normalized && ['available', 'unavailable'].includes(accountCheckState[field].status)) {
                    return accountCheckState[field].status === 'available';
                }

                const requestId = ++accountCheckSeq[field];
                accountCheckState[field] = { value: normalized, status: 'checking', message: @json(__('messages.blade_jsdyn_checking_field_availability')).replace(':field', label.toLowerCase()) };

                if (hintEl) {
                    hintEl.textContent = @json(__('messages.blade_jsdyn_checking_field_availability')).replace(':field', label.toLowerCase());
                    hintEl.classList.remove('good', 'bad');
                }

                try {
                    const response = await fetch(`/register/check-availability?field=${encodeURIComponent(field)}&value=${encodeURIComponent(normalized)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const data = contentType.includes('application/json')
                        ? await response.json()
                        : { message: @json(__('messages.blade_runtime_field_route_invalid')).replace(':field', label) };

                    if (requestId !== accountCheckSeq[field]) {
                        return false;
                    }

                    if (response.ok && data.available) {
                        accountCheckState[field] = { value: normalized, status: 'available', message: data.message || @json(__('messages.blade_runtime_field_available')).replace(':field', label) };
                        if (hintEl) {
                            hintEl.textContent = accountCheckState[field].message;
                            hintEl.classList.remove('bad');
                            hintEl.classList.add('good');
                        }
                        return true;
                    }

                    accountCheckState[field] = { value: normalized, status: 'unavailable', message: data.message || @json(__('messages.blade_runtime_field_taken')).replace(':field', label) };
                    if (hintEl) {
                        hintEl.textContent = accountCheckState[field].message;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                } catch (error) {
                    if (requestId !== accountCheckSeq[field]) {
                        return false;
                    }

                    accountCheckState[field] = {
                        value: normalized,
                        status: 'error',
                        message: @json(__('messages.blade_runtime_field_check_failed')).replace(':field', label),
                    };

                    if (hintEl) {
                        hintEl.textContent = accountCheckState[field].message;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }
            }

            function validateUsernameField(field) {
                const fieldValue = field.value.trim();
                
                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                if (!fieldValue) {
                    showFieldError(field, @json(__('messages.blade_runtime_username_required')));
                    return false;
                }
                
                // Check for valid characters (letters, numbers, underscore, dot, hyphen) - NO SPACES
                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(fieldValue)) {
                    showFieldError(field, @json(__('messages.blade_js_username_may_only_contain_letters_numbers_underscores_dots')));
                    return false;
                }
                
                if (fieldValue.length < 4) {
                    showFieldError(field, @json(__('messages.blade_js_username_must_be_at_least_4_characters_long')));
                    return false;
                }
                
                return true;
            }

            // ============= PASSWORD MATCH VALIDATION - NO DUPLICATE ERRORS =============
            function updatePasswordMatchUI() {
                if (!passwordInput || !confirmPasswordInput || !matchText) return true;

                const password = passwordInput.value;
                const confirm = confirmPasswordInput.value;

                // Remove any existing error messages for confirm password
                const confirmGroup = confirmPasswordInput.closest('.form-group');
                if (confirmGroup) {
                    const existingErrors = confirmGroup.querySelectorAll('.error-message:not(#passwordMatchText)');
                    existingErrors.forEach(error => error.remove());
                }

                if (!confirm) {
                    matchText.textContent = @json(__('messages.blade_register_blade_passwords_must_match'));
                    matchText.classList.remove("good", "bad");
                    confirmPasswordInput.classList.remove("is-invalid");
                    return false;
                }

                const matched = password === confirm;
                
                matchText.textContent = matched ? @json(__('messages.blade_runtime_passwords_match')) : @json(__('messages.blade_runtime_passwords_do_not_match'));
                matchText.classList.toggle("good", matched);
                matchText.classList.toggle("bad", !matched);
                confirmPasswordInput.classList.toggle("is-invalid", !matched);
                
                return matched;
            }

            // ============= MAIN VALIDATION FUNCTION =============
            function validateStep(stepNumber) {
                let isValid = true;
                const currentStepElement = document.getElementById(`step${stepNumber}`);

                // Clear previous error messages
                const existingErrors = currentStepElement.querySelectorAll('.error-message:not(.alert *):not(#passwordMatchText)');
                existingErrors.forEach(error => error.remove());

                buildResidentAddress();

                // Reset field styles
                const allFields = currentStepElement.querySelectorAll('.form-control');
                allFields.forEach(field => field.classList.remove('is-invalid'));
                currentStepElement.querySelectorAll('.file-upload-wrapper.is-invalid, .id-auto-scan-wrapper.is-invalid').forEach(wrapper => wrapper.classList.remove('is-invalid'));

                // Validate required fields
                const requiredFields = currentStepElement.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (!field.checked) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_runtime_agree_continue')));
                        }
                        return;
                    }

                    if (field.type === 'file') {
                        if (!field.files || field.files.length === 0) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_runtime_complete_id_scan')));
                        } else {
                            const fileSize = field.files[0].size;
                            if (fileSize > 5242880) {
                                isValid = false;
                                showFieldError(field, @json(__('messages.blade_runtime_file_max_5mb')));
                            }

                            const fileType = field.files[0].type;
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                            if (!allowedTypes.includes(fileType)) {
                                isValid = false;
                                showFieldError(field, @json(__('messages.blade_runtime_file_type_jpg_png_pdf')));
                            }

                            if ((field.id === 'valid_id' || field.id === 'valid_id_back')
                                && !scannerState.qualityResults[field.id]?.passed) {
                                isValid = false;
                                showFieldError(field, field.id === 'valid_id_back'
                                    ? @json(__('messages.blade_runtime_scan_clear_back'))
                                    : @json(__('messages.blade_runtime_scan_clear_front')));
                            }
                        }
                        return;
                    }

                    const fieldValue = field.value.trim();

                    if (!fieldValue) {
                        isValid = false;
                        showFieldError(field, @json(__('messages.validation_field_required')));
                        return;
                    }

                    // NAME FIELD VALIDATION
                    if (['firstname', 'middlename', 'lastname'].includes(field.id)) {
                        const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;
                        if (!nameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.validation_name_format')));
                        } else if (fieldValue.length < 2) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.validation_name_min_length')));
                        }
                    }

                    // USERNAME VALIDATION
                    if (field.id === 'username') {
                        const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                        if (!usernameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_js_username_may_only_contain_letters_numbers_underscores_dots')));
                        } else if (fieldValue.length < 4) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_js_username_must_be_at_least_4_characters_long')));
                        } else if (usernameCheckState.value === fieldValue.toLowerCase() && usernameCheckState.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_js_username_is_already_taken')));
                        }
                    }

                    // Address minimum length
                    if (field.id === 'address' && fieldValue.length < 10) {
                        isValid = false;
                        showFieldError(field, @json(__('messages.blade_js_address_must_be_at_least_10_characters_long')));
                    }

                    // Email validation
                    if (field.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.validation_email_format')));
                        } else if (accountCheckState.email?.value === fieldValue.toLowerCase() && accountCheckState.email?.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, accountCheckState.email.message || @json(__('messages.blade_js_email_is_already_taken')));
                        }
                    }

                    // Phone number validation
                    if (field.id === 'contact') {
                        const phoneRegex = /^09\d{9}$/;
                        if (!phoneRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.validation_contact_format')));
                        } else if (accountCheckState.contact?.value === fieldValue && accountCheckState.contact?.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, accountCheckState.contact.message || @json(__('messages.blade_js_contact_number_is_already_registered')));
                        }
                    }

                    // Place of birth validation
                    if (field.id === 'place_of_birth') {
                        const pobRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s.,'-]+$/;

                        if (!pobRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_runtime_place_birth_format')));
                        } else if (fieldValue.length < 2) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.blade_runtime_place_birth_min')));
                        }
                    }

                    // Birthdate validation
                    if (field.id === 'birthdate') {
                        const birthDate = new Date(fieldValue);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (birthDate > today) {
                            isValid = false;
                            showFieldError(field, @json(__('messages.validation_dob_future')));
                        } else {
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();

                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }

                            if (age < 18) {
                                isValid = false;
                                showFieldError(field, @json(__('messages.blade_runtime_age_18_register')));
                            }
                        }
                    }
                });

                // Step 2 specific validations
                if (stepNumber === 2) {
                    if (!validatePasswordStrength()) isValid = false;
                    if (!updatePasswordMatchUI()) {
                        isValid = false;
                    }
                    
                    if (termsCheckbox && !termsCheckbox.checked) {
                        isValid = false;
                        showFieldError(termsCheckbox, @json(__('messages.blade_runtime_agree_terms')));
                    }
                }

                return isValid;
            }

            // Show error message (prevents duplicates)
            function showFieldError(field, message) {
                field.classList.add('is-invalid');

                const parent = field.type === 'checkbox' ? field.closest('.terms-section') :
                            field.type === 'file' ? (field.closest('.file-upload-wrapper') || document.getElementById('idAutoScanWrapper')) :
                            field.parentNode;

                if (field.type === 'file' && parent) {
                    parent.classList.add('is-invalid');
                }

                if (parent) {
                    const existingError = parent.querySelector('.error-message:not(#passwordMatchText)');
                    if (existingError) {
                        existingError.textContent = message;
                        existingError.dataset.clientError = 'true';
                        return;
                    }

                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'error-message';
                    errorMessage.dataset.clientError = 'true';
                    errorMessage.textContent = message;
                    parent.appendChild(errorMessage);
                }
            }

            // ============= OTHER FUNCTIONS (unchanged) =============
            function clearCaptchaError() {
                const existingError = document.getElementById('captchaError');
                if (existingError) {
                    existingError.remove();
                }
            }

            function showCaptchaError(message) {
                clearCaptchaError();
                const parent = recaptchaBox ? recaptchaBox.parentNode : document.querySelector('.captcha-section');
                if (!parent) {
                    return;
                }

                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.id = 'captchaError';
                errorMessage.textContent = message;
                parent.appendChild(errorMessage);
            }
            
            // Set max date for birthdate
            if (birthdateInput) {
                const today = new Date();
                const minAge = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                birthdateInput.max = minAge.toISOString().split("T")[0];
            }

            // File upload display for valid ID front/back
            uploadInputs.forEach((input) => {
                input.addEventListener('change', function(e) {
                    const textId = this.getAttribute('data-file-text');
                    const textEl = textId ? document.getElementById(textId) : null;
                    const defaultText = this.id === 'valid_id_back' ? @json(__('messages.blade_profile_blade_no_back_file_selected')) : @json(__('messages.blade_profile_blade_no_front_file_selected'));
                    const file = e.target.files[0] || null;
                    const wrapper = this.closest('.file-upload-wrapper');

                    if (wrapper) {
                        wrapper.classList.remove('is-invalid');
                        wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                    }

                    if (textEl) {
                        textEl.textContent = file ? file.name : defaultText;
                    }

                    updateSelectedFilePreview(this, file);
                    updateAutoScanStatus();

                    if (!file) return;

                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

                    if (file.size > 5242880) {
                        this.value = '';
                        if (textEl) textEl.textContent = defaultText;
                        showFieldError(this, @json(__('messages.blade_runtime_file_max_5mb')));
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        this.value = '';
                        if (textEl) textEl.textContent = defaultText;
                        showFieldError(this, @json(__('messages.blade_runtime_file_type_jpg_png_pdf')));
                    }
                });
            });

            // ============= ID SCANNER: CAMERA + AUTO-CROP + ADMIN VERIFICATION =============
            const scannerModalElement = document.getElementById('idScannerModal');
            const scannerModal = scannerModalElement ? new bootstrap.Modal(scannerModalElement) : null;
            const scannerVideo = document.getElementById('idScannerVideo');
            const scannerOverlay = document.getElementById('idScannerOverlay');
            const scannerWorkCanvas = document.getElementById('idScannerWorkCanvas');
            const scannerResultCanvas = document.getElementById('idScannerResultCanvas');
            const scannerStatus = document.getElementById('idScannerStatus');
            const scannerStatusText = document.getElementById('idScannerStatusText');
            const scannerTargetText = document.getElementById('idScannerTargetText');
            const scannerAutoCapture = document.getElementById('idScannerAutoCapture');
            const scannerManualCaptureBtn = document.getElementById('idScannerManualCaptureBtn');
            const scannerRetakeBtn = document.getElementById('idScannerRetakeBtn');
            const scanBothSidesBtn = document.getElementById('scanBothSidesBtn');

            const scannerFlowSides = [
                { inputId: 'valid_id', label: @json(__('messages.blade_profile_blade_front_side')), title: @json(__('messages.blade_profile_blade_automatic_valid_id_scanner')) },
                { inputId: 'valid_id_back', label: @json(__('messages.blade_profile_blade_back_side')), title: @json(__('messages.blade_profile_blade_automatic_valid_id_scanner')) },
            ];

            // Movement-friendly capture settings.
            // The scanner no longer waits for the ID to be perfectly still; it collects the best sharp frame.
            const SCANNER_STABLE_FRAME_TARGET = 8;
            const SCANNER_CANDIDATE_SAMPLE_INTERVAL_MS = 150;
            const SCANNER_MIN_GOOD_CANDIDATES = 2;
            const SCANNER_FRAME_MOVEMENT_TOLERANCE = 46;

            const scannerState = {
                targetInput: null,
                targetLabel: @json(__('messages.blade_profile_blade_front_side')),
                scanMode: 'single',
                flowIndex: 0,
                stream: null,
                rafId: null,
                stableFrames: 0,
                lastPoints: null,
                detectedPoints: null,
                isCapturing: false,
                isProcessing: false,
                isFlowActive: false,
                isWaitingForFlip: false,
                completedBothSides: false,
                qualityResults: {},
                captureSignatures: {},
                candidateFrames: [],
                detectionFrames: 0,
                lastCandidateAt: 0,
                lastCandidateMessage: '',
            };

            function setScannerStatus(message, state = 'waiting') {
                if (scannerStatusText) scannerStatusText.textContent = message;
                if (!scannerStatus) return;
                scannerStatus.classList.toggle('is-ready', state === 'ready');
                scannerStatus.classList.toggle('is-error', state === 'error');
            }

            function resetScannerDetection() {
                scannerState.stableFrames = 0;
                scannerState.lastPoints = null;
                scannerState.detectedPoints = null;
                scannerState.isProcessing = false;
                scannerState.candidateFrames = [];
                scannerState.detectionFrames = 0;
                scannerState.lastCandidateAt = 0;
                scannerState.lastCandidateMessage = '';

                if (scannerOverlay) {
                    const context = scannerOverlay.getContext('2d');
                    context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
                }
            }

            function setScannerTarget(inputId, label = 'ID side') {
                const targetInput = document.getElementById(inputId);
                if (!targetInput) return false;

                scannerState.targetInput = targetInput;
                scannerState.targetLabel = label;

                if (scannerTargetText) scannerTargetText.textContent = label;

                const title = document.getElementById('idScannerModalLabel');
                if (title) title.textContent = @json(__('messages.blade_profile_blade_automatic_valid_id_scanner'));

                resetScannerDetection();
                return true;
            }

            function openScannerForSide(inputId, label = @json(__('messages.blade_runtime_id_side')), mode = 'single') {
                if (!scannerModal) return;

                scannerState.scanMode = mode;
                scannerState.flowIndex = inputId === 'valid_id_back' ? 1 : 0;

                if (!setScannerTarget(inputId, label)) return;
                scannerModal.show();
            }

            function openScannerForBothSides() {
                if (!scannerModal || scannerState.isFlowActive) return;

                resetBothIdScannerFiles();

                scannerState.scanMode = 'both';
                scannerState.flowIndex = 0;
                scannerState.isFlowActive = true;
                scannerState.isWaitingForFlip = false;
                scannerState.completedBothSides = false;
                updateScanButtonState();

                const firstSide = scannerFlowSides[0];
                if (!setScannerTarget(firstSide.inputId, firstSide.label)) {
                    scannerState.isFlowActive = false;
                    updateScanButtonState();
                    return;
                }

                updateAutoScanStatus();
                setScannerStatus(@json(__('messages.blade_runtime_scanning_front_movement')), 'waiting');

                scannerModal.show();
            }

            function updateSelectedFilePreview(input, file) {
                const previewId = input.getAttribute('data-preview');
                const shellId = input.getAttribute('data-preview-shell');
                const preview = previewId ? document.getElementById(previewId) : null;
                const shell = shellId ? document.getElementById(shellId) : null;

                if (!preview || !shell) return;

                if (!file || !file.type.startsWith('image/')) {
                    preview.removeAttribute('src');
                    preview.classList.add('d-none');
                    shell.classList.add('is-empty');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                    shell.classList.remove('is-empty');
                };
                reader.readAsDataURL(file);
            }

            function updateScanButtonState() {
                if (!scanBothSidesBtn) return;

                const isActive = scannerState.isFlowActive === true;
                scanBothSidesBtn.disabled = isActive;

                if (isActive) {
                    scanBothSidesBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${@json(__('messages.blade_runtime_scanning_id'))}`;
                    return;
                }

                if (hasValidScannerCaptures()) {
                    scanBothSidesBtn.innerHTML = `<i class="fas fa-rotate-right"></i> ${@json(__('messages.blade_runtime_rescan_id'))}`;
                    return;
                }

                scanBothSidesBtn.innerHTML = `<i class="fas fa-camera-retro"></i> ${@json(__('messages.blade_profile_blade_start_id_scan'))}`;
            }

            function updateAutoScanStatus() {
                const frontInput = document.getElementById('valid_id');
                const backInput = document.getElementById('valid_id_back');
                const wrapper = document.getElementById('idAutoScanWrapper');
                const status = document.getElementById('idAutoScanStatus');
                const title = document.getElementById('idAutoScanStatusTitle');
                const text = document.getElementById('idAutoScanStatusText');

                const frontPassed = scannerState.qualityResults.valid_id?.passed === true;
                const backPassed = scannerState.qualityResults.valid_id_back?.passed === true;
                const hasFront = !!frontInput?.files?.length && frontPassed;
                const hasBack = !!backInput?.files?.length && backPassed;

                if (wrapper && hasFront && hasBack) {
                    wrapper.classList.remove('is-invalid');
                    wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                }

                if (!status || !title || !text) {
                    updateScanButtonState();
                    return;
                }

                status.classList.toggle('is-complete', hasFront && hasBack);

                if (hasFront && hasBack) {
                    title.textContent = @json(__('messages.blade_register_blade_id_scan_complete'));
                    text.textContent = @json(__('messages.blade_register_blade_front_side_captured_back_side_captured_both_passed_qual'));
                } else if (hasFront) {
                    title.textContent = @json(__('messages.blade_register_blade_front_side_captured'));
                    text.textContent = @json(__('messages.blade_register_blade_front_side_is_saved_flip_the_id_now_the_scanner_will_ca'));
                } else if (hasBack) {
                    title.textContent = @json(__('messages.blade_register_blade_back_side_captured'));
                    text.textContent = @json(__('messages.blade_register_blade_back_side_is_saved_but_the_front_side_is_still_missing_'));
                } else {
                    title.textContent = @json(__('messages.blade_register_blade_no_id_scan_yet'));
                    text.textContent = @json(__('messages.blade_register_blade_press_start_id_scan_the_scanner_will_capture_the_front_'));
                }

                updateScanButtonState();
            }

            function distanceBetweenPoints(a, b) {
                return Math.hypot(a.x - b.x, a.y - b.y);
            }

            function orderQuadPoints(points) {
                const ordered = points.slice().sort((a, b) => a.x - b.x);
                const left = ordered.slice(0, 2).sort((a, b) => a.y - b.y);
                const right = ordered.slice(2).sort((a, b) => a.y - b.y);

                const tl = left[0];
                const bl = left[1];
                const tr = right[0];
                const br = right[1];

                return [tl, tr, br, bl];
            }

            function arePointsStable(currentPoints, previousPoints) {
                if (!currentPoints || !previousPoints) return false;

                const current = orderQuadPoints(currentPoints);
                const previous = orderQuadPoints(previousPoints);
                const averageMovement = current.reduce((total, point, index) => {
                    return total + distanceBetweenPoints(point, previous[index]);
                }, 0) / 4;

                return averageMovement < SCANNER_FRAME_MOVEMENT_TOLERANCE;
            }

            function syncScannerCanvases() {
                if (!scannerVideo || !scannerOverlay || !scannerWorkCanvas) return;

                const width = scannerVideo.videoWidth || 1280;
                const height = scannerVideo.videoHeight || 720;

                [scannerOverlay, scannerWorkCanvas].forEach((canvas) => {
                    if (canvas.width !== width || canvas.height !== height) {
                        canvas.width = width;
                        canvas.height = height;
                    }
                });
            }

            function drawScannerOverlay(points) {
                if (!scannerOverlay || !scannerVideo) return;

                syncScannerCanvases();
                const context = scannerOverlay.getContext('2d');
                context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);

                if (!points || points.length !== 4) return;

                const ordered = orderQuadPoints(points);
                context.lineWidth = Math.max(4, scannerOverlay.width * 0.004);
                context.strokeStyle = '#22c55e';
                context.fillStyle = 'rgba(34, 197, 94, 0.12)';
                context.beginPath();
                context.moveTo(ordered[0].x, ordered[0].y);
                ordered.slice(1).forEach(point => context.lineTo(point.x, point.y));
                context.closePath();
                context.fill();
                context.stroke();
            }

            function getIdCardPoints() {
                if (!window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas) return null;

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                let source = null;
                let gray = null;
                let blurred = null;
                let edges = null;
                let contours = null;
                let hierarchy = null;

                try {
                    source = cv.imread(scannerWorkCanvas);
                    gray = new cv.Mat();
                    blurred = new cv.Mat();
                    edges = new cv.Mat();
                    contours = new cv.MatVector();
                    hierarchy = new cv.Mat();

                    cv.cvtColor(source, gray, cv.COLOR_RGBA2GRAY);
                    cv.GaussianBlur(gray, blurred, new cv.Size(5, 5), 0);
                    cv.Canny(blurred, edges, 70, 180);
                    cv.dilate(edges, edges, cv.Mat.ones(3, 3, cv.CV_8U));
                    cv.findContours(edges, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);

                    let bestPoints = null;
                    let bestArea = 0;
                    const frameArea = source.rows * source.cols;
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

                            const ordered = orderQuadPoints(points);
                            const topWidth = distanceBetweenPoints(ordered[0], ordered[1]);
                            const bottomWidth = distanceBetweenPoints(ordered[3], ordered[2]);
                            const leftHeight = distanceBetweenPoints(ordered[0], ordered[3]);
                            const rightHeight = distanceBetweenPoints(ordered[1], ordered[2]);
                            const width = Math.max(topWidth, bottomWidth);
                            const height = Math.max(leftHeight, rightHeight);
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
                    if (source) source.delete();
                    if (gray) gray.delete();
                    if (blurred) blurred.delete();
                    if (edges) edges.delete();
                    if (contours) contours.delete();
                    if (hierarchy) hierarchy.delete();
                }
            }

            function cropGuideFallback() {
                if (!scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) return null;

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                const sourceWidth = scannerWorkCanvas.width;
                const sourceHeight = scannerWorkCanvas.height;
                const cropX = Math.round(sourceWidth * 0.12);
                const cropY = Math.round(sourceHeight * 0.23);
                const cropWidth = Math.round(sourceWidth * 0.76);
                const cropHeight = Math.round(sourceHeight * 0.54);

                scannerResultCanvas.width = cropWidth;
                scannerResultCanvas.height = cropHeight;
                scannerResultCanvas.getContext('2d').drawImage(
                    scannerWorkCanvas,
                    cropX,
                    cropY,
                    cropWidth,
                    cropHeight,
                    0,
                    0,
                    cropWidth,
                    cropHeight
                );

                return scannerResultCanvas;
            }

            function cropDetectedCard(points) {
                if (!points || !window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) {
                    return cropGuideFallback();
                }

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                const ordered = orderQuadPoints(points);
                const outputWidth = Math.round(Math.max(
                    distanceBetweenPoints(ordered[0], ordered[1]),
                    distanceBetweenPoints(ordered[3], ordered[2])
                ));
                const outputHeight = Math.round(Math.max(
                    distanceBetweenPoints(ordered[0], ordered[3]),
                    distanceBetweenPoints(ordered[1], ordered[2])
                ));

                let source = null;
                let destination = null;
                let transform = null;
                let sourceTriangle = null;
                let destinationTriangle = null;

                try {
                    source = cv.imread(scannerWorkCanvas);
                    destination = new cv.Mat();
                    sourceTriangle = cv.matFromArray(4, 1, cv.CV_32FC2, [
                        ordered[0].x, ordered[0].y,
                        ordered[1].x, ordered[1].y,
                        ordered[2].x, ordered[2].y,
                        ordered[3].x, ordered[3].y,
                    ]);
                    destinationTriangle = cv.matFromArray(4, 1, cv.CV_32FC2, [
                        0, 0,
                        outputWidth - 1, 0,
                        outputWidth - 1, outputHeight - 1,
                        0, outputHeight - 1,
                    ]);
                    transform = cv.getPerspectiveTransform(sourceTriangle, destinationTriangle);
                    cv.warpPerspective(source, destination, transform, new cv.Size(outputWidth, outputHeight));

                    scannerResultCanvas.width = outputWidth;
                    scannerResultCanvas.height = outputHeight;
                    cv.imshow(scannerResultCanvas, destination);
                    return scannerResultCanvas;
                } catch (error) {
                    console.warn('ID scanner crop failed, using fallback crop:', error);
                    return cropGuideFallback();
                } finally {
                    if (source) source.delete();
                    if (destination) destination.delete();
                    if (transform) transform.delete();
                    if (sourceTriangle) sourceTriangle.delete();
                    if (destinationTriangle) destinationTriangle.delete();
                }
            }

            
            function clearScannerInput(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;

                try {
                    input.value = '';
                } catch (error) {
                    const transfer = new DataTransfer();
                    input.files = transfer.files;
                }

                const textId = input.getAttribute('data-file-text');
                const textEl = textId ? document.getElementById(textId) : null;
                if (textEl) {
                    textEl.textContent = inputId === 'valid_id_back'
                        ? @json(__('messages.blade_profile_blade_no_back_file_selected'))
                        : @json(__('messages.blade_profile_blade_no_front_file_selected'));
                }
            }

            function resetBothIdScannerFiles() {
                ['valid_id', 'valid_id_back'].forEach((inputId) => clearScannerInput(inputId));
                scannerState.qualityResults = {};
                scannerState.captureSignatures = {};

                const wrapper = document.getElementById('idAutoScanWrapper');
                if (wrapper) {
                    wrapper.classList.remove('is-invalid');
                    wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                }

                updateAutoScanStatus();
            }

            function hasValidScannerCaptures() {
                const frontInput = document.getElementById('valid_id');
                const backInput = document.getElementById('valid_id_back');

                return !!frontInput?.files?.length
                    && !!backInput?.files?.length
                    && scannerState.qualityResults.valid_id?.passed === true
                    && scannerState.qualityResults.valid_id_back?.passed === true;
            }

            function polygonArea(points) {
                if (!points || points.length < 4) return 0;

                let area = 0;
                for (let i = 0; i < points.length; i++) {
                    const current = points[i];
                    const next = points[(i + 1) % points.length];
                    area += (current.x * next.y) - (next.x * current.y);
                }

                return Math.abs(area / 2);
            }

            function getCanvasAnalysis(canvas) {
                const maxWidth = 640;
                const ratio = canvas.width > 0 ? canvas.height / canvas.width : 1;
                const width = Math.min(maxWidth, Math.max(1, canvas.width));
                const height = Math.max(1, Math.round(width * ratio));

                const sampleCanvas = document.createElement('canvas');
                sampleCanvas.width = width;
                sampleCanvas.height = height;

                const sampleContext = sampleCanvas.getContext('2d', { willReadFrequently: true });
                sampleContext.imageSmoothingEnabled = true;
                sampleContext.drawImage(canvas, 0, 0, width, height);

                const imageData = sampleContext.getImageData(0, 0, width, height).data;
                const grays = new Float32Array(width * height);
                let total = 0;
                let tooDarkPixels = 0;
                let tooBrightPixels = 0;

                for (let i = 0, p = 0; i < imageData.length; i += 4, p++) {
                    const gray = (imageData[i] * 0.299) + (imageData[i + 1] * 0.587) + (imageData[i + 2] * 0.114);
                    grays[p] = gray;
                    total += gray;

                    if (gray < 28) tooDarkPixels++;
                    if (gray > 245) tooBrightPixels++;
                }

                const pixelCount = grays.length || 1;
                const brightness = total / pixelCount;

                let varianceTotal = 0;
                for (let i = 0; i < grays.length; i++) {
                    varianceTotal += Math.pow(grays[i] - brightness, 2);
                }
                const contrast = Math.sqrt(varianceTotal / pixelCount);

                // Simple gradient sharpness. Higher means details/text edges are clearer.
                let sharpnessTotal = 0;
                let sharpnessCount = 0;
                const step = 2;

                for (let y = 0; y < height - step; y += step) {
                    for (let x = 0; x < width - step; x += step) {
                        const index = y * width + x;
                        const right = y * width + (x + step);
                        const bottom = (y + step) * width + x;
                        sharpnessTotal += Math.abs(grays[index] - grays[right]) + Math.abs(grays[index] - grays[bottom]);
                        sharpnessCount += 2;
                    }
                }

                const sharpness = sharpnessCount ? sharpnessTotal / sharpnessCount : 0;

                // Laplacian variance is a stronger blur detector than simple brightness/contrast.
                // Blurry images have low variance because edges/text are smeared.
                let laplacianTotal = 0;
                let laplacianSquaredTotal = 0;
                let laplacianCount = 0;
                let edgePixels = 0;
                let centerEdgePixels = 0;
                let centerPixelCount = 0;

                const centerLeft = Math.floor(width * 0.12);
                const centerRight = Math.floor(width * 0.88);
                const centerTop = Math.floor(height * 0.16);
                const centerBottom = Math.floor(height * 0.84);

                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const index = y * width + x;
                        const laplacian =
                            (grays[index - width] + grays[index - 1] + grays[index + 1] + grays[index + width])
                            - (4 * grays[index]);

                        const absLaplacian = Math.abs(laplacian);
                        laplacianTotal += laplacian;
                        laplacianSquaredTotal += laplacian * laplacian;
                        laplacianCount++;

                        if (absLaplacian > 18) edgePixels++;

                        if (x >= centerLeft && x <= centerRight && y >= centerTop && y <= centerBottom) {
                            centerPixelCount++;
                            if (absLaplacian > 18) centerEdgePixels++;
                        }
                    }
                }

                const laplacianMean = laplacianCount ? laplacianTotal / laplacianCount : 0;
                const laplacianVariance = laplacianCount
                    ? Math.max(0, (laplacianSquaredTotal / laplacianCount) - (laplacianMean * laplacianMean))
                    : 0;

                const edgeDensity = laplacianCount ? edgePixels / laplacianCount : 0;
                const centerEdgeDensity = centerPixelCount ? centerEdgePixels / centerPixelCount : 0;
                const darkPixelRatio = tooDarkPixels / pixelCount;
                const brightPixelRatio = tooBrightPixels / pixelCount;

                return {
                    brightness,
                    contrast,
                    sharpness,
                    laplacianVariance,
                    edgeDensity,
                    centerEdgeDensity,
                    darkPixelRatio,
                    brightPixelRatio,
                    sampledWidth: width,
                    sampledHeight: height,
                };
            }

            function getCanvasSignature(canvas) {
                const size = 32;
                const signatureCanvas = document.createElement('canvas');
                signatureCanvas.width = size;
                signatureCanvas.height = size;

                const signatureContext = signatureCanvas.getContext('2d', { willReadFrequently: true });
                signatureContext.drawImage(canvas, 0, 0, size, size);

                const imageData = signatureContext.getImageData(0, 0, size, size).data;
                const pixels = [];
                const histogram = new Array(16).fill(0);
                let total = 0;

                for (let i = 0; i < imageData.length; i += 4) {
                    const gray = ((imageData[i] * 0.299) + (imageData[i + 1] * 0.587) + (imageData[i + 2] * 0.114)) / 255;
                    pixels.push(gray);
                    total += gray;
                    const bucket = Math.min(15, Math.floor(gray * 16));
                    histogram[bucket] += 1;
                }

                const mean = pixels.length ? total / pixels.length : 0;
                const bits = pixels.map(value => value >= mean ? 1 : 0);
                const normalizedHistogram = histogram.map(count => count / Math.max(1, pixels.length));

                let variance = 0;
                for (const value of pixels) {
                    variance += Math.pow(value - mean, 2);
                }
                const std = pixels.length ? Math.sqrt(variance / pixels.length) : 0;

                return {
                    pixels,
                    bits,
                    histogram: normalizedHistogram,
                    mean,
                    std,
                    width: canvas.width,
                    height: canvas.height,
                    ratio: Math.max(canvas.width, canvas.height) / Math.max(1, Math.min(canvas.width, canvas.height)),
                };
            }

            function getSignaturePixels(signature) {
                if (!signature) return null;
                return Array.isArray(signature) ? signature : signature.pixels;
            }

            function averageSignatureDifference(firstSignature, secondSignature) {
                const firstPixels = getSignaturePixels(firstSignature);
                const secondPixels = getSignaturePixels(secondSignature);

                if (!firstPixels || !secondPixels || firstPixels.length !== secondPixels.length) {
                    return 1;
                }

                let diff = 0;
                for (let i = 0; i < firstPixels.length; i++) {
                    diff += Math.abs(firstPixels[i] - secondPixels[i]);
                }

                return diff / firstPixels.length;
            }

            function signatureHammingDifference(firstSignature, secondSignature) {
                if (!firstSignature?.bits || !secondSignature?.bits || firstSignature.bits.length !== secondSignature.bits.length) {
                    return 1;
                }

                let differentBits = 0;
                for (let i = 0; i < firstSignature.bits.length; i++) {
                    if (firstSignature.bits[i] !== secondSignature.bits[i]) differentBits++;
                }

                return differentBits / firstSignature.bits.length;
            }

            function signatureCorrelation(firstSignature, secondSignature) {
                const firstPixels = getSignaturePixels(firstSignature);
                const secondPixels = getSignaturePixels(secondSignature);

                if (!firstPixels || !secondPixels || firstPixels.length !== secondPixels.length) {
                    return 0;
                }

                const firstMean = firstPixels.reduce((sum, value) => sum + value, 0) / firstPixels.length;
                const secondMean = secondPixels.reduce((sum, value) => sum + value, 0) / secondPixels.length;

                let numerator = 0;
                let firstVariance = 0;
                let secondVariance = 0;

                for (let i = 0; i < firstPixels.length; i++) {
                    const firstDelta = firstPixels[i] - firstMean;
                    const secondDelta = secondPixels[i] - secondMean;
                    numerator += firstDelta * secondDelta;
                    firstVariance += firstDelta * firstDelta;
                    secondVariance += secondDelta * secondDelta;
                }

                const denominator = Math.sqrt(firstVariance * secondVariance);
                return denominator ? numerator / denominator : 0;
            }

            function signatureHistogramDifference(firstSignature, secondSignature) {
                if (!firstSignature?.histogram || !secondSignature?.histogram || firstSignature.histogram.length !== secondSignature.histogram.length) {
                    return 1;
                }

                let diff = 0;
                for (let i = 0; i < firstSignature.histogram.length; i++) {
                    diff += Math.abs(firstSignature.histogram[i] - secondSignature.histogram[i]);
                }

                return diff / 2;
            }

            function looksLikeSameIdSide(firstSignature, secondSignature) {
                if (!firstSignature || !secondSignature) {
                    return { duplicate: false, confidence: 0, details: null };
                }

                const averageDifference = averageSignatureDifference(firstSignature, secondSignature);
                const hammingDifference = signatureHammingDifference(firstSignature, secondSignature);
                const correlation = signatureCorrelation(firstSignature, secondSignature);
                const histogramDifference = signatureHistogramDifference(firstSignature, secondSignature);
                const ratioDifference = Math.abs((firstSignature.ratio || 0) - (secondSignature.ratio || 0));

                let confidence = 0;
                if (averageDifference < 0.085) confidence += 0.35;
                if (hammingDifference < 0.18) confidence += 0.30;
                if (correlation > 0.86) confidence += 0.25;
                if (histogramDifference < 0.12) confidence += 0.10;
                if (ratioDifference > 0.18) confidence -= 0.15;

                const duplicate = confidence >= 0.65
                    || (averageDifference < 0.065 && hammingDifference < 0.24)
                    || (correlation > 0.92 && hammingDifference < 0.26);

                return {
                    duplicate,
                    confidence,
                    details: {
                        averageDifference,
                        hammingDifference,
                        correlation,
                        histogramDifference,
                        ratioDifference,
                    },
                };
            }

            function validateIdCaptureQuality(canvas, points, inputId) {
                if (!canvas || !points || points.length !== 4) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_not_complete')),
                    };
                }

                const ordered = orderQuadPoints(points);
                const detectedWidth = Math.max(
                    distanceBetweenPoints(ordered[0], ordered[1]),
                    distanceBetweenPoints(ordered[3], ordered[2])
                );
                const detectedHeight = Math.max(
                    distanceBetweenPoints(ordered[0], ordered[3]),
                    distanceBetweenPoints(ordered[1], ordered[2])
                );
                const detectedRatio = Math.max(detectedWidth, detectedHeight) / Math.max(1, Math.min(detectedWidth, detectedHeight));
                const detectedArea = polygonArea(ordered);
                const frameArea = Math.max(1, scannerWorkCanvas.width * scannerWorkCanvas.height);
                const areaRatio = detectedArea / frameArea;

                if (areaRatio < 0.10) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_too_far')),
                    };
                }

                if (areaRatio > 0.88) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_too_close')),
                    };
                }

                if (detectedRatio < 1.25 || detectedRatio > 2.35) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_shape_invalid')),
                    };
                }

                const cropShortSide = Math.min(canvas.width, canvas.height);
                const cropLongSide = Math.max(canvas.width, canvas.height);
                const cropRatio = cropLongSide / Math.max(1, cropShortSide);

                if (cropShortSide < 220 || cropLongSide < 360) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_crop_small')),
                    };
                }

                if (cropRatio < 1.25 || cropRatio > 2.35) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_crop_shape')),
                    };
                }

                const analysis = getCanvasAnalysis(canvas);

                if (analysis.brightness < 45) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_dark_rescan')),
                    };
                }

                if (analysis.brightness > 235) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_bright_rescan')),
                    };
                }

                if (analysis.contrast < 18) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_unclear_rescan')),
                        analysis,
                    };
                }

                if (analysis.darkPixelRatio > 0.36) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_dark_ratio')),
                        analysis,
                    };
                }

                if (analysis.brightPixelRatio > 0.30) {
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_glare_ratio')),
                        analysis,
                    };
                }

                const isBlurry = analysis.laplacianVariance < 90
                    || analysis.sharpness < 8
                    || analysis.edgeDensity < 0.018
                    || analysis.centerEdgeDensity < 0.014;

                if (isBlurry) {
                    console.info('ID scan rejected as blurry:', analysis);
                    return {
                        passed: false,
                        message: @json(__('messages.blade_runtime_id_blurry_detailed')),
                        analysis,
                    };
                }

                const signature = getCanvasSignature(canvas);

                if (inputId === 'valid_id_back') {
                    const frontSignature = scannerState.captureSignatures.valid_id;
                    const sameSideCheck = looksLikeSameIdSide(frontSignature, signature);

                    if (sameSideCheck.duplicate) {
                        console.info('Back ID rejected because it looks like the front side again:', sameSideCheck.details);
                        return {
                            passed: false,
                            message: @json(__('messages.blade_runtime_front_again_detailed')),
                            duplicateFront: true,
                            duplicateDetails: sameSideCheck.details,
                        };
                    }
                }

                return {
                    passed: true,
                    message: @json(__('messages.blade_runtime_id_quality_passed')),
                    analysis,
                    signature,
                    areaRatio,
                    detectedRatio,
                    cropRatio,
                };
            }

            function rejectCurrentScannerCapture(inputId, message) {
                const sideLabel = inputId === 'valid_id_back' ? @json(__('messages.blade_profile_blade_back_side')) : @json(__('messages.blade_profile_blade_front_side'));
                scannerState.qualityResults[inputId] = {
                    passed: false,
                    message,
                };
                clearScannerInput(inputId);
                updateAutoScanStatus();
                resetScannerDetection();
                const nextInstruction = inputId === 'valid_id_back'
                    ? @json(__('messages.blade_runtime_scanner_first_saved_back_only'))
                    : @json(__('messages.blade_register_scanner_place_the_front_side_inside_the_guide_and_hold_it_steady'));

                setScannerStatus(@json(__('messages.blade_profile_scanner_side_failed_message')).replace(':side', sideLabel).replace(':message', message), 'error');

                window.setTimeout(() => {
                    scannerState.isCapturing = false;
                    scannerState.isWaitingForFlip = false;
                    if (scannerState.stream) {
                        setScannerStatus(`${nextInstruction}`, 'waiting');
                        scannerFrameLoop();
                    }
                }, 1400);
            }


            function cloneScannerPoints(points) {
                return Array.isArray(points) ? points.map(point => ({ x: point.x, y: point.y })) : null;
            }

            function cloneScannerCanvas(sourceCanvas) {
                if (!sourceCanvas) return null;

                const clone = document.createElement('canvas');
                clone.width = sourceCanvas.width;
                clone.height = sourceCanvas.height;
                const cloneContext = clone.getContext('2d');
                cloneContext.drawImage(sourceCanvas, 0, 0);
                return clone;
            }

            function getScannerQualityScore(qualityResult) {
                const analysis = qualityResult?.analysis;
                if (!qualityResult?.passed || !analysis) return 0;

                const brightnessScore = Math.max(0, 1 - (Math.abs(analysis.brightness - 145) / 145));
                const contrastScore = Math.min(1, analysis.contrast / 60);
                const sharpnessScore = Math.min(1.4, analysis.sharpness / 18);
                const laplacianScore = Math.min(1.6, analysis.laplacianVariance / 260);
                const edgeScore = Math.min(1.2, analysis.edgeDensity / 0.055);
                const centerEdgeScore = Math.min(1.2, analysis.centerEdgeDensity / 0.045);
                const sizeScore = Math.min(1.15, Math.max(0, (qualityResult.areaRatio || 0) / 0.34));

                return (
                    (brightnessScore * 0.9)
                    + (contrastScore * 1.0)
                    + (sharpnessScore * 1.2)
                    + (laplacianScore * 1.55)
                    + (edgeScore * 1.0)
                    + (centerEdgeScore * 1.0)
                    + (sizeScore * 0.75)
                );
            }

            function rememberScannerCandidate(canvas, points, qualityResult) {
                if (!canvas || !points || !qualityResult?.passed) return null;

                const candidate = {
                    canvas: cloneScannerCanvas(canvas),
                    points: cloneScannerPoints(points),
                    quality: qualityResult,
                    score: getScannerQualityScore(qualityResult),
                    createdAt: performance.now(),
                };

                scannerState.candidateFrames.push(candidate);
                scannerState.candidateFrames.sort((a, b) => b.score - a.score);
                scannerState.candidateFrames = scannerState.candidateFrames.slice(0, 5);

                return candidate;
            }

            function getBestScannerCandidate() {
                if (!scannerState.candidateFrames.length) return null;
                return scannerState.candidateFrames
                    .slice()
                    .sort((a, b) => b.score - a.score)[0];
            }

            function sampleMovingScannerFrame(points) {
                if (!scannerState.targetInput || !points || scannerState.isWaitingForFlip) return null;

                const now = performance.now();
                if (now - scannerState.lastCandidateAt < SCANNER_CANDIDATE_SAMPLE_INTERVAL_MS) {
                    return getBestScannerCandidate();
                }

                scannerState.lastCandidateAt = now;

                const canvas = cropDetectedCard(points);
                const qualityResult = validateIdCaptureQuality(canvas, points, scannerState.targetInput.id);

                if (!qualityResult.passed) {
                    scannerState.lastCandidateMessage = qualityResult.message;
                    return getBestScannerCandidate();
                }

                scannerState.lastCandidateMessage = '';
                return rememberScannerCandidate(canvas, points, qualityResult);
            }


            function attachScannerImageToInput(canvas, prevalidatedQuality = null, prevalidatedPoints = null) {
                if (!canvas || !scannerState.targetInput) return;

                const currentInputId = scannerState.targetInput.id;
                const qualityResult = prevalidatedQuality || validateIdCaptureQuality(canvas, prevalidatedPoints || scannerState.detectedPoints, currentInputId);

                if (!qualityResult.passed) {
                    rejectCurrentScannerCapture(currentInputId, qualityResult.message);
                    return;
                }

                canvas.toBlob((blob) => {
                    if (!blob) {
                        setScannerStatus(@json(__('messages.blade_js_capture_failed_please_try_again')), 'error');
                        scannerState.isCapturing = false;
                        scannerState.isWaitingForFlip = false;
                        return;
                    }

                    try {
                        const side = currentInputId === 'valid_id_back' ? 'back' : 'front';
                        const sideLabel = currentInputId === 'valid_id_back' ? @json(__('messages.blade_profile_blade_back_side')) : @json(__('messages.blade_profile_blade_front_side'));
                        const file = new File([blob], `valid_id_${side}_scan_${Date.now()}.jpg`, { type: 'image/jpeg' });
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        scannerState.targetInput.files = transfer.files;

                        scannerState.qualityResults[currentInputId] = qualityResult;
                        scannerState.captureSignatures[currentInputId] = qualityResult.signature;
                        scannerState.targetInput.dispatchEvent(new Event('change', { bubbles: true }));

                        updateAutoScanStatus();

                        if (scannerState.scanMode === 'both' && currentInputId === 'valid_id') {
                            scannerState.isWaitingForFlip = true;
                            setScannerStatus(@json(__('messages.blade_runtime_scanner_front_saved_flip')), 'ready');
                            resetScannerDetection();

                            window.setTimeout(() => {
                                const nextSide = scannerFlowSides[1];
                                scannerState.flowIndex = 1;
                                setScannerTarget(nextSide.inputId, nextSide.label);
                                scannerState.isCapturing = false;
                                scannerState.isWaitingForFlip = false;
                                updateAutoScanStatus();
                                setScannerStatus(@json(__('messages.blade_runtime_scanner_back_movement')), 'waiting');

                                if (scannerState.stream) {
                                    scannerFrameLoop();
                                }
                            }, 3000);

                            return;
                        }

                        if (scannerState.scanMode === 'both') {
                            if (!hasValidScannerCaptures()) {
                                setScannerStatus(@json(__('messages.blade_register_scanner_side_captured_but_the_full_front_back_scan_is_not_complete')).replace(':side', sideLabel), 'error');
                                scannerState.isCapturing = false;
                                return;
                            }

                            scannerState.completedBothSides = true;
                            scannerState.isFlowActive = false;
                            updateAutoScanStatus();
                            setScannerStatus(@json(__('messages.blade_register_scanner_front_and_back_sides_passed_id_scan_complete')), 'ready');
                        } else {
                            setScannerStatus(@json(__('messages.blade_register_scanner_side_captured_successfully')).replace(':side', sideLabel), 'ready');
                        }

                        window.setTimeout(() => {
                            if (scannerModal) scannerModal.hide();

                            // Move to Step 2 only when the whole Step 1 is valid.
                            // This prevents the scanner from skipping required fields.
                            if (scannerState.scanMode === 'both' && currentStep === 1 && hasValidScannerCaptures() && validateStep(1)) {
                                goToStep('step2');
                            }
                        }, 750);
                    } catch (error) {
                        console.warn('Could not attach scanner file:', error);
                        setScannerStatus(@json(__('messages.blade_runtime_scanner_attach_blocked')), 'error');
                        scannerState.isFlowActive = false;
                        updateScanButtonState();
                    } finally {
                        if (!(scannerState.scanMode === 'both' && currentInputId === 'valid_id')) {
                            scannerState.isCapturing = false;
                            scannerState.isWaitingForFlip = false;
                        }
                    }
                }, 'image/jpeg', 0.94);
            }

            function captureScannerFrame(force = false) {
                if (scannerState.isCapturing) return;

                if (scannerState.isWaitingForFlip) {
                    setScannerStatus(@json(__('messages.blade_runtime_scanner_front_already')), 'waiting');
                    return;
                }

                if (!scannerState.detectedPoints) {
                    setScannerStatus(@json(__('messages.blade_runtime_scanner_no_id')), 'error');
                    return;
                }

                scannerState.isCapturing = true;
                const sideLabel = scannerState.targetInput?.id === 'valid_id_back' ? @json(__('messages.blade_profile_blade_back_side')) : @json(__('messages.blade_profile_blade_front_side'));
                const bestCandidate = getBestScannerCandidate();

                if (bestCandidate) {
                    scannerState.detectedPoints = cloneScannerPoints(bestCandidate.points);
                    setScannerStatus(@json(__('messages.blade_register_scanner_using_the_clearest_side_frame_found_checking_quality')).replace(':side', sideLabel), 'ready');
                    attachScannerImageToInput(bestCandidate.canvas, bestCandidate.quality, bestCandidate.points);
                    return;
                }

                setScannerStatus(@json(__('messages.blade_register_scanner_checking_side_quality')).replace(':side', sideLabel), 'ready');
                const canvas = cropDetectedCard(scannerState.detectedPoints);
                attachScannerImageToInput(canvas);
            }

            function scannerFrameLoop() {
                if (!scannerState.stream || !scannerVideo || scannerState.isCapturing || scannerState.completedBothSides) return;

                scannerState.rafId = requestAnimationFrame(scannerFrameLoop);

                if (scannerVideo.readyState < 2 || scannerState.isProcessing) return;

                scannerState.isProcessing = true;

                try {
                    const points = getIdCardPoints();
                    drawScannerOverlay(points);

                    if (points) {
                        const pointsAreStableEnough = arePointsStable(points, scannerState.lastPoints);
                        scannerState.stableFrames = pointsAreStableEnough
                            ? scannerState.stableFrames + 1
                            : Math.max(1, Math.floor(scannerState.stableFrames * 0.55));
                        scannerState.detectionFrames++;
                        scannerState.lastPoints = points;
                        scannerState.detectedPoints = points;

                        const sideLabel = scannerState.targetInput?.id === 'valid_id_back' ? @json(__('messages.blade_profile_blade_back_side')) : @json(__('messages.blade_profile_blade_front_side'));
                        const bestCandidate = sampleMovingScannerFrame(points);
                        const goodFrameCount = scannerState.candidateFrames.length;
                        const progress = Math.min(SCANNER_STABLE_FRAME_TARGET, Math.max(scannerState.stableFrames, goodFrameCount * 3));

                        if (bestCandidate) {
                            setScannerStatus(@json(__('messages.blade_runtime_scanner_good_frame')).replace(':side', sideLabel).replace(':progress', progress).replace(':target', SCANNER_STABLE_FRAME_TARGET), 'ready');
                        } else if (scannerState.lastCandidateMessage) {
                            setScannerStatus(@json(__('messages.blade_register_scanner_side_detected_but_not_clear_yet_message')).replace(':side', sideLabel).replace(':message', scannerState.lastCandidateMessage), 'waiting');
                        } else {
                            setScannerStatus(@json(__('messages.blade_register_scanner_side_detected_keep_it_inside_the_guide_progress_target')).replace(':side', sideLabel).replace(':progress', progress).replace(':target', SCANNER_STABLE_FRAME_TARGET), 'ready');
                        }

                        const canAutoCaptureBestFrame = scannerAutoCapture?.checked
                            && bestCandidate
                            && (
                                scannerState.stableFrames >= SCANNER_STABLE_FRAME_TARGET
                                || (goodFrameCount >= SCANNER_MIN_GOOD_CANDIDATES && scannerState.detectionFrames >= 4)
                            );

                        if (canAutoCaptureBestFrame) {
                            captureScannerFrame(true);
                        }
                    } else {
                        scannerState.stableFrames = 0;
                        scannerState.detectionFrames = 0;
                        scannerState.lastPoints = null;
                        scannerState.detectedPoints = null;

                        if (!window.cvReady || typeof cv === 'undefined') {
                            setScannerStatus(@json(__('messages.blade_runtime_scanner_engine_manual')), 'waiting');
                        } else {
                            setScannerStatus(@json(__('messages.blade_runtime_scanner_place_id')), 'waiting');
                        }
                    }
                } finally {
                    scannerState.isProcessing = false;
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
                if (scannerOverlay) {
                    const context = scannerOverlay.getContext('2d');
                    context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
                }

                scannerState.stableFrames = 0;
                scannerState.lastPoints = null;
                scannerState.detectedPoints = null;
                scannerState.candidateFrames = [];
                scannerState.detectionFrames = 0;
                scannerState.lastCandidateAt = 0;
                scannerState.lastCandidateMessage = '';
                scannerState.isCapturing = false;
                scannerState.isProcessing = false;
            }

            async function startScannerCamera() {
                if (!scannerVideo || !navigator.mediaDevices?.getUserMedia) {
                    setScannerStatus(@json(__('messages.blade_runtime_camera_unsupported')), 'error');
                    return;
                }

                stopScannerCamera();
                scannerState.completedBothSides = false;
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
                    setScannerStatus(@json(__('messages.blade_runtime_scanner_place_id')), window.cvReady ? 'waiting' : 'waiting');
                    scannerFrameLoop();
                } catch (error) {
                    console.warn('Camera access failed:', error);
                    setScannerStatus(@json(__('messages.blade_runtime_camera_permission_manual')), 'error');
                }
            }

            document.querySelectorAll('.btn-scan-id').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-scan-target');
                    const targetLabel = button.getAttribute('data-scan-label') || @json(__('messages.blade_runtime_id_side'));
                    openScannerForSide(targetId, targetLabel, 'single');
                });
            });

            if (scanBothSidesBtn) {
                scanBothSidesBtn.addEventListener('click', () => {
                    if (scannerState.isFlowActive) return;
                    openScannerForBothSides();
                });
            }

            if (scannerModalElement) {
                scannerModalElement.addEventListener('shown.bs.modal', startScannerCamera);
                scannerModalElement.addEventListener('hidden.bs.modal', () => {
                    stopScannerCamera();
                    scannerState.scanMode = 'single';
                    scannerState.flowIndex = 0;
                    scannerState.isFlowActive = false;
                    scannerState.isWaitingForFlip = false;
                    scannerState.completedBothSides = false;
                    updateScanButtonState();
                });
            }

            if (scannerManualCaptureBtn) {
                scannerManualCaptureBtn.addEventListener('click', () => captureScannerFrame(true));
            }

            if (scannerRetakeBtn) {
                scannerRetakeBtn.addEventListener('click', startScannerCamera);
            }

            document.addEventListener('opencv-ready', () => {
                if (scannerState.stream && !scannerState.isCapturing) {
                    setScannerStatus(@json(__('messages.blade_js_scanner_ready_place_the_id_inside_the_guide')), 'waiting');
                }
            });

            // Next button functionality
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = this.getAttribute('data-next');

                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            // Previous button functionality
            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const prevStep = this.getAttribute('data-prev');
                    if (!prevStep) return; // no data-prev = natural link (Cancel)
                    e.preventDefault();
                    goToStep(prevStep);
                });
            });

            function goToStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
                currentStep = parseInt(stepId.replace('step', ''));
                updateProgress();
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                if (progressFill) progressFill.style.width = `${progressPercentage}%`;

                progressSteps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active');
                    if (stepNumber === currentStep) {
                        step.classList.add('active');
                    }
                });
            }

            // Password strength evaluation
            function evaluatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score += 1;
                if (/[A-Z]/.test(password)) score += 1;
                if (/[a-z]/.test(password)) score += 1;
                if (/\d/.test(password)) score += 1;
                if (/[^A-Za-z0-9]/.test(password)) score += 1;

                if (password.length === 0) return {
                    score: 0,
                    label: "too weak",
                    color: "#d16060",
                    width: 10
                };
                if (score <= 2) return {
                    score,
                    label: "weak",
                    color: "#d16060",
                    width: 35
                };
                if (score <= 3) return {
                    score,
                    label: "fair",
                    color: "#d38e2a",
                    width: 60
                };
                if (score <= 4) return {
                    score,
                    label: "good",
                    color: "#2c7dc8",
                    width: 82
                };
                return {
                    score,
                    label: "strong",
                    color: "#1e8f55",
                    width: 100
                };
            }

            function validatePasswordStrength() {
                if (!passwordInput) return true;
                const password = passwordInput.value;
                const { label } = evaluatePasswordStrength(password);

                const existingError = passwordInput.closest('.form-group')?.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (password && (label === "too weak" || label === "weak")) {
                    showFieldError(passwordInput, @json(__('messages.blade_runtime_password_too_weak')));
                    return false;
                }
                return true;
            }

            const passwordStrengthTranslations = {
                'too weak': @json(__('messages.blade_runtime_strength_too_weak')),
                'weak': @json(__('messages.blade_runtime_strength_weak')),
                'fair': @json(__('messages.blade_runtime_strength_fair')),
                'good': @json(__('messages.blade_runtime_strength_good')),
                'strong': @json(__('messages.blade_runtime_strength_strong')),
            };

            function updatePasswordUI() {
                if (!passwordInput || !meterFill || !strengthText) return;

                const {
                    label,
                    color,
                    width
                } = evaluatePasswordStrength(passwordInput.value);
                meterFill.style.width = `${width}%`;
                meterFill.style.backgroundColor = color;
                strengthText.textContent = @json(__('messages.blade_jsdyn_password_strength_label')).replace(':label', passwordStrengthTranslations[label] || label);
                strengthText.classList.toggle("good", label === "good" || label === "strong");
                strengthText.classList.toggle("bad", label === "weak" || label === "too weak");

                passwordChecklist.forEach((item) => {
                    const rule = item.dataset.rule;
                    if (rule) {
                        const rules = {
                            length: passwordInput.value.length >= 8,
                            number: /\d/.test(passwordInput.value),
                            upper: /[A-Z]/.test(passwordInput.value),
                            special: /[^A-Za-z0-9]/.test(passwordInput.value),
                        };
                        item.classList.toggle("is-complete", rules[rule] || false);
                    }
                });
            }

            // Sanitize contact number
            if (contactInput) {
                contactInput.addEventListener("input", () => {
                    contactInput.value = contactInput.value.replace(/\D/g, "").slice(0, 11);
                });
            }

            // Password event listeners
            if (passwordInput) {
                passwordInput.addEventListener("input", () => {
                    // Remove any spaces that may have been typed or pasted
                    passwordInput.value = passwordInput.value.replace(/\s/g, "");
                    updatePasswordUI();
                    updatePasswordMatchUI();
                });
            }

            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener("input", () => {
                    // Remove any spaces that may have been typed or pasted
                    confirmPasswordInput.value = confirmPasswordInput.value.replace(/\s/g, "");
                    updatePasswordMatchUI();
                });
            }

            // Toggle password visibility
            document.querySelectorAll(".toggle-password").forEach((toggle) => {
                toggle.addEventListener("click", () => {
                    const targetId = toggle.getAttribute("data-target");
                    const input = targetId ? document.getElementById(targetId) : null;
                    if (!input) return;

                    const showing = input.type === "text";
                    input.type = showing ? "password" : "text";
                    const icon = toggle.querySelector("i");
                    if (icon) {
                        icon.classList.toggle("fa-eye", showing);
                        icon.classList.toggle("fa-eye-slash", !showing);
                    }
                });
            });

            // Form submission
            if (form) {
                form.addEventListener("submit", async (event) => {
                    if (isProgrammaticSubmit) {
                        return;
                    }

                    event.preventDefault();

                    buildResidentAddress();

                    if (!validateStep(currentStep)) {
                        document.querySelectorAll('.is-invalid').forEach(field => {
                            const group = field.closest('.form-group') || field.closest('.file-upload-wrapper');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                        });
                        return;
                    }

                    if (usernameField) {
                        const usernameAvailable = await checkUsernameAvailability(usernameField.value, true);
                        if (!usernameAvailable) {
                            showFieldError(usernameField, usernameCheckState.message || @json(__('messages.blade_js_username_is_already_taken')));
                            const group = usernameField.closest('.form-group');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                            return;
                        }
                    }

                    if (recaptchaEnabled) {
                        const captchaToken = window.grecaptcha ? window.grecaptcha.getResponse() : '';
                        if (!captchaToken) {
                            showCaptchaError(@json(__('messages.blade_runtime_complete_captcha')));
                            const captchaSection = document.querySelector('.captcha-section');
                            if (captchaSection) {
                                captchaSection.classList.add('shake');
                                setTimeout(() => captchaSection.classList.remove('shake'), 400);
                            }
                            return;
                        }

                        clearCaptchaError();
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add("loading");
                        const btnText = submitBtn.querySelector(".btn-text");
                        if (btnText) btnText.textContent = @json(__('messages.blade_register_blade_creating_account'));
                    }

                    isProgrammaticSubmit = true;
                    form.submit();
                });
            }

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            // Initialize
            buildResidentAddress();
            updateProgress();
            updatePasswordUI();
            updatePasswordMatchUI();

            // Auto-navigate to step 2 if server returned errors for step 2 fields
            const hasStep2Errors = {!! $errors->hasAny(['username', 'password', 'password_confirmation', 'terms', 'g-recaptcha-response']) ? 'true' : 'false' !!};
            if (hasStep2Errors) {
                goToStep('step2');
            }
        });
    </script>
    @if($captchaEnabled && !empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</body>

</html> 