@php
    $resident = $resident ?? auth()->user();

    $fullName = trim(collect([
        $resident?->firstname,
        $resident?->middlename,
        $resident?->lastname,
        $resident?->suffix,
    ])->filter()->implode(' '));

    $birthdateDisplay = $resident?->birthdate
        ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y')
        : __('messages.blade_announcement_blade_not_provided');

    $genderDisplay = filled($resident?->gender)
        ? ucfirst((string) $resident->gender)
        : __('messages.blade_announcement_blade_not_provided');

    $civilStatusDisplay = filled($resident?->civil_status)
        ? ucfirst(str_replace('_', ' ', (string) $resident->civil_status))
        : __('messages.blade_announcement_blade_not_provided');

    $placeOfBirthDisplay = filled($resident?->place_of_birth)
        ? (string) $resident->place_of_birth
        : __('messages.blade_announcement_blade_not_provided');

    $hasVerifiedValidId = in_array($resident?->valid_id_verified, [true, 1, '1', 'true', 'yes', 'verified'], true);
    $hasVerifiedResidency = in_array($resident?->residency_verified, [true, 1, '1', 'true', 'yes', 'verified'], true);

    $residentValidIdPath = $resident?->valid_id_path
        ?? $resident?->valid_id
        ?? $resident?->valid_id_front
        ?? null;

    $needsPrimaryProof = ! $hasVerifiedResidency;
    $needsValidIdUpload = ! $hasVerifiedValidId || blank($residentValidIdPath);

    $missingProfile = collect([
        'Full name' => blank($resident?->firstname) || blank($resident?->lastname),
        'Birthdate' => blank($resident?->birthdate),
        'Gender' => blank($resident?->gender),
        'Civil Status' => blank($resident?->civil_status),
        'Place of Birth' => blank($resident?->place_of_birth),
        'Complete address' => blank($resident?->address),
        'Contact number' => blank($resident?->contact),
        'Email address' => blank($resident?->email),
    ])->filter()->keys();

    $profileReviewData = [
        'fullName' => $fullName ?: __('messages.blade_announcement_blade_not_provided'),
        'birthdate' => $birthdateDisplay,
        'gender' => $genderDisplay,
        'civil_status' => $civilStatusDisplay,
        'place_of_birth' => $placeOfBirthDisplay,
        'address' => $resident?->address ?: __('messages.blade_announcement_blade_not_provided'),
        'contact' => $resident?->contact ?: __('messages.blade_announcement_blade_not_provided'),
        'email' => $resident?->email ?: __('messages.blade_announcement_blade_not_provided'),
    ];

    $savedValidIdLabel = $residentValidIdPath ? basename((string) $residentValidIdPath) : __('messages.blade_fallback_saved_verified_valid_id');
    $savedResidencyProofLabel = 'Residency already verified in your resident profile';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('messages.blade_indigency_form_blade_barangay_indigency_application_form') }}</title>
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
        .income-bracket option { 
            font-family: 'Poppins', sans-serif; 
        }
        .review-content ul { 
            padding-left: 1.2rem;
            margin-bottom: 0.5rem; 
        }
        .review-content li { 
            font-size: 0.95rem; 
            margin-bottom: 0.2rem; 
        }

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

        * {
            box-sizing: border-box;
        }

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

        img,
        svg {
            max-width: 100%;
        }

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
            content: "Online request";
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

        .application-form-section {
            margin-bottom: 46px;
        }

        .form-container {
            overflow: hidden;
            border: 1px solid rgba(228, 231, 236, 0.96);
            border-radius: var(--cf-radius-xl) !important;
            background: rgba(255, 255, 255, 0.92) !important;
            box-shadow: var(--cf-shadow);
            backdrop-filter: blur(16px);
        }

        .alert {
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
            background:
                linear-gradient(180deg, rgba(248, 250, 252, 0.96), rgba(255, 255, 255, 0.96));
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
        .benefits-grid.row {
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
        }

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
        .full-width {
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 18px;
            min-width: 0;
        }

        .form-group label,
        .upload-group > label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: var(--cf-ink);
            font-size: 0.86rem;
            font-weight: 900;
        }

        .form-group label i,
        .upload-group > label i {
            color: var(--cf-brand);
        }

        .form-hint {
            margin-top: 6px;
            color: var(--cf-muted);
            font-size: 0.78rem;
            font-weight: 650;
        }

        .upload-size {
            margin-left: auto;
            color: var(--cf-muted);
            font-size: 0.74rem;
            font-weight: 850;
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

        .form-control::placeholder {
            color: #98a2b3;
            opacity: 1;
        }

        .form-control:focus {
            border-color: var(--cf-brand) !important;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12) !important;
        }

        textarea.form-control {
            min-height: 96px;
            resize: vertical;
        }

        .upload-section {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .upload-group {
            width: 100%;
            min-width: 0;
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

        .upload-area:hover {
            transform: translateY(-1px);
            border-color: var(--cf-brand) !important;
            background: var(--cf-brand-soft) !important;
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

        .upload-area p {
            margin: 0;
            color: var(--cf-ink-soft);
            font-weight: 800;
        }

        .upload-area span {
            color: var(--cf-brand);
            font-weight: 950;
        }

        .upload-preview.active {
            margin-top: 10px;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 12px;
            border: 1px solid var(--cf-line);
            border-radius: 18px;
            background: var(--cf-surface);
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

        .file-info {
            min-width: 0;
            flex: 1;
        }

        .file-name {
            overflow: hidden;
            color: var(--cf-ink);
            font-size: 0.86rem;
            font-weight: 900;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-size {
            color: var(--cf-muted);
            font-size: 0.78rem;
            font-weight: 700;
        }

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
        }

        .review-section {
            display: grid;
            gap: 14px;
        }

        .review-card,
        .benefits-section,
        .terms-section {
            border: 1px solid var(--cf-line) !important;
            border-radius: var(--cf-radius-lg) !important;
            background: var(--cf-surface) !important;
            padding: clamp(16px, 2.4vw, 22px) !important;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .review-card h4,
        .benefits-section h4 {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 5px !important;
            color: var(--cf-ink) !important;
            font-size: 1.02rem !important;
            font-weight: 950 !important;
            letter-spacing: -0.02em;
        }

        .review-card h4 i,
        .benefits-section h4 i {
            color: var(--cf-brand);
        }

        .review-content {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .review-item {
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

        .payment-summary {
            display: grid;
            gap: 8px;
        }

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

        .payment-item.total {
            border-bottom: 0;
            color: var(--cf-ink);
            font-weight: 950;
        }

        .pickup-instructions {
            display: grid;
            gap: 10px;
            margin-top: 16px;
        }

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

        .pickup-info h5 {
            margin: 0 0 4px;
            color: var(--cf-ink);
            font-size: 0.92rem;
            font-weight: 950;
        }

        .pickup-info p,
        .pickup-info ul {
            margin: 0;
            color: var(--cf-muted);
            font-size: 0.86rem;
            font-weight: 600;
        }

        .pickup-info ul {
            padding-left: 18px;
        }

        .benefits-section {
            margin-top: 16px;
        }

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

        .benefit-item h5 {
            margin: 0 0 4px;
            color: var(--cf-ink);
            font-size: 0.92rem;
            font-weight: 950;
        }

        .benefit-item p {
            margin: 0;
            color: var(--cf-muted);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .terms-section {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

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

        .terms-checkbox input:checked ~ .checkmark::after {
            display: block;
        }

        .terms-checkbox a {
            color: var(--cf-brand);
            font-weight: 950;
            text-decoration: none;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

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

        #termsError {
            margin-top: 12px !important;
            padding: 12px 14px !important;
            border: 1px solid #fecdd3 !important;
            border-radius: 14px !important;
            background: var(--cf-danger-soft) !important;
            color: #991b1b !important;
            font-size: 0.86rem !important;
            font-weight: 850;
        }

        .spinner {
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

        .modal-title {
            color: var(--cf-ink) !important;
            font-weight: 950 !important;
        }

        .modal-body {
            color: var(--cf-ink-soft);
            font-size: 0.92rem;
        }

        @media (max-width: 992px) {
            .progress-steps {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .form-page-header::after {
                position: static;
                margin-top: 18px;
                width: fit-content;
            }
        }

        @media (max-width: 820px) {
            html,
            body {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden !important;
            }

            body {
                -webkit-text-size-adjust: 100%;
            }

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
            .form-container {
                max-width: 100% !important;
            }

            .back-button-container {
                padding-top: 14px;
            }

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

            .form-page-header p {
                max-width: 100% !important;
                font-size: 0.92rem !important;
            }

            .form-container {
                border-radius: 22px !important;
            }

            .form-progress {
                padding: 14px !important;
            }

            .progress-steps {
                grid-template-columns: 1fr !important;
                gap: 8px !important;
            }

            .step {
                min-height: 54px !important;
                padding: 10px !important;
                border-radius: 16px !important;
            }

            .step-circle {
                width: 34px !important;
                height: 34px !important;
                flex-basis: 34px !important;
                font-size: 0.82rem !important;
            }

            .step span {
                font-size: 0.84rem !important;
            }

            .progress-bar {
                height: 7px !important;
            }

            .form-step {
                padding: 16px !important;
            }

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
            .profile-summary-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }

            .form-group.full-width,
            .full-width {
                grid-column: auto !important;
            }

            .form-group label,
            .upload-group > label {
                display: block !important;
                font-size: 0.82rem !important;
            }

            .form-group label i,
            .upload-group > label i {
                margin-right: 6px !important;
            }

            .upload-size {
                display: block !important;
                margin: 4px 0 0 !important;
                font-size: 0.7rem !important;
            }

            .form-control {
                min-height: 48px !important;
                font-size: 0.92rem !important;
                border-radius: 14px !important;
            }

            textarea.form-control {
                min-height: 92px !important;
            }

            .upload-section {
                gap: 12px !important;
            }

            .upload-area {
                min-height: 138px !important;
                padding: 16px !important;
                border-radius: 18px !important;
            }

            .upload-area i {
                width: 46px !important;
                height: 46px !important;
                margin-bottom: 10px !important;
                border-radius: 15px !important;
                font-size: 1.1rem !important;
            }

            .upload-area p {
                font-size: 0.86rem !important;
                line-height: 1.35 !important;
            }

            .upload-area .small {
                font-size: 0.75rem !important;
            }

            .file-preview {
                align-items: flex-start !important;
                gap: 10px !important;
                padding: 10px !important;
                border-radius: 15px !important;
            }

            .file-preview > i {
                width: 38px !important;
                height: 38px !important;
                flex-basis: 38px !important;
                border-radius: 13px !important;
            }

            .file-name {
                max-width: 100% !important;
                white-space: normal !important;
                word-break: break-word !important;
                font-size: 0.8rem !important;
            }

            .file-size {
                font-size: 0.72rem !important;
            }

            .remove-file {
                width: 34px !important;
                height: 34px !important;
                flex-basis: 34px !important;
            }

            .review-card,
            .benefits-section,
            .terms-section {
                padding: 14px !important;
                border-radius: 18px !important;
            }

            .review-section {
                gap: 12px !important;
            }

            .review-content {
                gap: 8px !important;
            }

            .review-item {
                grid-template-columns: 1fr !important;
                gap: 4px !important;
                padding: 11px !important;
                border-radius: 15px !important;
            }

            .review-label {
                font-size: 0.68rem !important;
            }

            .review-value {
                font-size: 0.84rem !important;
            }

            .payment-item {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 3px !important;
                font-size: 0.84rem !important;
            }

            .pickup-instructions {
                gap: 8px !important;
            }

            .pickup-info {
                flex-direction: column !important;
                gap: 9px !important;
                padding: 12px !important;
                border-radius: 15px !important;
            }

            .pickup-info > i {
                width: 34px !important;
                height: 34px !important;
                flex-basis: 34px !important;
                border-radius: 12px !important;
            }

            .pickup-info h5 {
                font-size: 0.86rem !important;
            }

            .pickup-info p,
            .pickup-info ul {
                font-size: 0.8rem !important;
            }

            .benefits-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }

            .benefit-item {
                width: 100% !important;
                padding: 12px !important;
                border-radius: 15px !important;
            }

            .benefit-item i {
                width: 38px !important;
                height: 38px !important;
                margin-bottom: 10px !important;
                border-radius: 13px !important;
            }

            .benefit-item h5 {
                font-size: 0.86rem !important;
            }

            .benefit-item p {
                font-size: 0.78rem !important;
            }

            .terms-section {
                gap: 10px !important;
            }

            .terms-checkbox {
                gap: 10px !important;
                font-size: 0.8rem !important;
                line-height: 1.4 !important;
            }

            .terms-checkbox p {
                margin-bottom: 0 !important;
            }

            .checkmark {
                width: 21px !important;
                height: 21px !important;
                flex-basis: 21px !important;
            }

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

            .modal-dialog {
                margin: 8px !important;
            }

            .modal-content {
                border-radius: 18px !important;
                max-height: calc(100dvh - 16px) !important;
            }

            .modal-body {
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }

            .modal-header,
            .modal-footer {
                padding: 12px 14px !important;
            }

            .modal-title {
                font-size: 0.96rem !important;
                line-height: 1.2 !important;
            }

            #termsError {
                font-size: 0.8rem !important;
                border-radius: 13px !important;
            }
        }

        @media (max-width: 430px) {
            .back-button-container,
            .form-page-header,
            .application-form-section {
                width: min(100% - 14px, 1120px) !important;
            }

            .btn-back {
                width: 100% !important;
                min-height: 44px !important;
                font-size: 0.84rem !important;
            }

            .form-page-header {
                padding: 16px !important;
            }

            .form-page-header h1 {
                font-size: 1.85rem !important;
            }

            .form-page-header::after {
                width: 100% !important;
                justify-content: center !important;
            }

            .form-progress,
            .form-step {
                padding: 13px !important;
            }

            .step {
                border-radius: 14px !important;
            }

            .form-step > h3 {
                font-size: 0.92rem !important;
            }

            .review-value {
                font-size: 0.82rem !important;
            }

            .terms-checkbox {
                font-size: 0.78rem !important;
            }
        }

        .locked-profile-card,
        .locked-warning-card,
        .locked-correction-card {
            border: 1px solid var(--cf-line) !important;
            border-radius: var(--cf-radius-lg) !important;
            background: var(--cf-surface) !important;
            padding: clamp(16px, 2.4vw, 22px) !important;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.03);
        }

        .locked-profile-card {
            margin-bottom: 16px;
        }

        .locked-profile-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .locked-profile-head h4 {
            margin: 0 0 5px;
            color: var(--cf-ink);
            font-size: 1.02rem;
            font-weight: 950;
            letter-spacing: -0.02em;
        }

        .locked-profile-head p,
        .locked-correction-card p {
            margin: 0;
            color: var(--cf-muted);
            font-size: 0.88rem;
            font-weight: 560;
        }

        .locked-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 34px;
            padding: 0.45rem 0.72rem;
            border-radius: 999px;
            color: var(--cf-ink-soft);
            background: #f1f5f9;
            font-size: 0.76rem;
            font-weight: 950;
            white-space: nowrap;
        }

        .locked-profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .locked-profile-item {
            min-width: 0;
            padding: 14px;
            border: 1px solid var(--cf-line);
            border-radius: 18px;
            background: var(--cf-surface-soft);
        }

        .locked-profile-item.full {
            grid-column: 1 / -1;
        }

        .locked-profile-item span {
            display: block;
            margin-bottom: 5px;
            color: var(--cf-muted);
            font-size: 0.74rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .locked-profile-item strong {
            display: block;
            color: var(--cf-ink);
            font-size: 0.94rem;
            font-weight: 850;
            word-break: break-word;
        }

        .locked-warning-card {
            display: flex;
            gap: 12px;
            padding: 14px !important;
            border-color: #fed7aa !important;
            background: var(--cf-warning-soft) !important;
            color: #9a3412;
            margin-bottom: 18px;
        }

        .locked-warning-card i {
            margin-top: 2px;
        }

        .locked-warning-card strong,
        .locked-correction-card strong {
            display: block;
            margin-bottom: 4px;
            font-weight: 950;
        }

        .locked-warning-card ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .locked-correction-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 16px 0;
            background: var(--cf-surface-soft) !important;
        }

        .btn-report-info {
            min-height: 46px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 16px !important;
            border: 1px solid var(--cf-line-strong);
            padding: 0.78rem 1.05rem;
            color: var(--cf-ink-soft) !important;
            background: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 950;
            line-height: 1;
            text-decoration: none !important;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-report-info:hover {
            color: var(--cf-brand) !important;
            border-color: rgba(198, 40, 40, 0.28);
            background: var(--cf-brand-soft);
        }

        .cf-hidden-profile-fields {
            display: none !important;
        }

        .profile-confirm-checkbox {
            margin-top: 16px !important;
        }

        @media (max-width: 820px) {
            .locked-profile-head,
            .locked-correction-card {
                flex-direction: column;
                align-items: stretch;
            }

            .locked-profile-grid {
                grid-template-columns: 1fr;
            }

            .locked-profile-item.full {
                grid-column: auto;
            }

            .locked-pill,
            .btn-report-info {
                width: 100%;
            }
        }


        /* Verified / locked requirement cards */
        .verified-requirement-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            background: var(--cf-success-soft);
            color: #166534;
        }

        .verified-requirement-card > i {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: #ffffff;
            color: var(--cf-success);
            border: 1px solid #bbf7d0;
        }

        .verified-requirement-card strong {
            display: block;
            margin-bottom: 4px;
            color: #14532d;
            font-size: 0.94rem;
            font-weight: 950;
        }

        .verified-requirement-card p {
            margin: 0;
            color: #166534;
            font-size: 0.84rem;
            font-weight: 650;
        }

        .verified-requirement-card .verified-file-name {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 9px;
            padding: 0.42rem 0.65rem;
            border-radius: 999px;
            background: #ffffff;
            color: #166534;
            font-size: 0.76rem;
            font-weight: 900;
            word-break: break-word;
        }

        .btn-correction-submit {
            min-height: 46px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 16px !important;
            border: 1px solid transparent;
            padding: 0.78rem 1.05rem;
            color: #fff !important;
            background: linear-gradient(135deg, var(--cf-brand), var(--cf-brand-dark)) !important;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 950;
            line-height: 1;
            cursor: pointer;
            text-decoration: none !important;
            box-shadow: 0 12px 24px rgba(198, 40, 40, 0.20);
        }

        @media (max-width: 820px) {
            .verified-requirement-card {
                flex-direction: column;
                border-radius: 18px;
            }

            .btn-correction-submit {
                width: 100%;
            }
        }

        .is-invalid { border-color: #ff4444 !important; }

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
<body class="cf-adopted-design cf-steps-3">
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('indigency') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.indigency_form_back_btn') }}
        </a>
    </div>

    <!-- Page Header -->
    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-hand-holding-heart"></i> {{ __('messages.indigency_form_page_title') }}</h1>
        <p class="mb-0">{{ __('messages.indigency_form_page_subtitle') }}</p>
    </div>

    <!-- Application Form -->
    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            <form id="requirementForm" method="POST" action="{{ route('indigency.store') }}" enctype="multipart/form-data" class="requirement-form" novalidate>
                @csrf
                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.form_step_personal_info') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.clearance_requirements_title') }}</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>{{ __('messages.form_step_review_submit') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>
                
                <!-- STEP 1: LOCKED RESIDENT PROFILE -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user-lock"></i> {{ __('messages.blade_clearance_form_blade_confirm_resident_profile') }}</h3>

                    <div class="locked-profile-card">
                        <div class="locked-profile-head">
                            <div>
                                <h4>{{ __('messages.blade_clearance_form_blade_saved_resident_information') }}</h4>
                                <p>
                                    {{ __('messages.blade_indigency_form_blade_these_details_came_from_your_registered_account_they_ar') }}
                                </p>
                            </div>

                            <span class="locked-pill">
                                <i class="fas fa-lock"></i>
                                {{ __('messages.blade_clearance_form_blade_locked') }}
                            </span>
                        </div>

                        @if ($missingProfile->isNotEmpty())
                            <div class="locked-warning-card">
                                <i class="fas fa-triangle-exclamation"></i>
                                <div>
                                    <strong>{{ __('messages.blade_clearance_form_blade_your_profile_has_missing_information') }}</strong>
                                    <span>{{ __('messages.blade_clearance_form_blade_please_email_the_barangay_office_before_submitting_this') }}</span>
                                    <ul>
                                        @foreach ($missingProfile as $field)
                                            <li>{{ $field }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="locked-profile-grid">
                            <div class="locked-profile-item">
                                <span>{{ __('messages.form_review_label_full_name') }}</span>
                                <strong>{{ $profileReviewData['fullName'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_dob_label') }}</span>
                                <strong>{{ $profileReviewData['birthdate'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.form_review_label_gender') }}</span>
                                <strong>{{ $profileReviewData['gender'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.blade_indigency_form_blade_civil_status') }}</span>
                                <strong>{{ $profileReviewData['civil_status'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</span>
                                <strong>{{ $profileReviewData['place_of_birth'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_contact_label') }}</span>
                                <strong>{{ $profileReviewData['contact'] }}</strong>
                            </div>

                            <div class="locked-profile-item">
                                <span>{{ __('messages.register_email_label') }}</span>
                                <strong>{{ $profileReviewData['email'] }}</strong>
                            </div>

                            <div class="locked-profile-item full">
                                <span>{{ __('messages.register_address_label') }}</span>
                                <strong>{{ $profileReviewData['address'] }}</strong>
                            </div>
                        </div>

                        <label class="terms-checkbox profile-confirm-checkbox">
                            <input type="checkbox" id="profileConfirmed" name="profile_confirmed" required>
                            <span class="checkmark"></span>
                            {{ __('messages.blade_clearance_form_blade_i_confirm_that_my_saved_resident_profile_information_is') }}
                        </label>

                        <div class="cf-hidden-profile-fields" aria-hidden="true">
                            {{-- Identity fields are intentionally not submitted. The controller reads them only from the authenticated resident profile. --}}
                        </div>
                    </div>

                    <div class="locked-correction-card">
                        <div>
                            <strong>{{ __('messages.blade_clearance_form_blade_is_something_incorrect') }}</strong>
                            <p>
                                {{ __('messages.blade_clearance_form_blade_do_not_edit_your_name_or_identity_details_here_send_a_c') }}
                            </p>
                        </div>

                        <button type="button" class="btn-report-info" data-bs-toggle="modal" data-bs-target="#incorrectProfileModal">
                            <i class="fas fa-envelope"></i>
                            {{ __('messages.blade_clearance_form_blade_email_correction_concern') }}
                        </button>
                    </div>

                    <div class="form-grid row g-3 mt-3">
                        <div class="form-group col-md-12">
                            <label for="monthlyIncome"><i class="fas fa-coins"></i> {{ __('messages.indigency_form_income_label') }}</label>
                            <select id="monthlyIncome" name="monthly_income" required class="form-control">
                                <option value="">{{ __('messages.indigency_form_income_placeholder') }}</option>
                                <option value="below 5k" {{ old('monthly_income') === 'below 5k' ? 'selected' : '' }}>{{ __('messages.income_below_5k') }}</option>
                                <option value="5k-8k" {{ old('monthly_income') === '5k-8k' ? 'selected' : '' }}>₱5,000 – ₱8,000</option>
                                <option value="8k-10k" {{ old('monthly_income') === '8k-10k' ? 'selected' : '' }}>₱8,001 – ₱10,000</option>
                                <option value="10k-15k" {{ old('monthly_income') === '10k-15k' ? 'selected' : '' }}>₱10,001 – ₱15,000</option>
                                <option value="no income" {{ old('monthly_income') === 'no income' ? 'selected' : '' }}>{{ __('messages.income_none') }}</option>
                            </select>
                            <div class="form-hint">{{ __('messages.indigency_form_income_hint') }}</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="householdMembers"><i class="fas fa-users"></i> {{ __('messages.form_household_members_label') }}</label>
                            <input type="number" id="householdMembers" name="household_members"
                                min="1" max="20" step="1" inputmode="numeric" pattern="[0-9]*"
                                required value="{{ old('household_members') }}" placeholder="{{ __('messages.form_household_placeholder') }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-actions row g-2 btns">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('indigency') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;" onclick="return confirm(@json(__('messages.blade_clearance_form_blade_are_you_sure_you_want_to_cancel_any_unsaved_changes_wil')));">
                                <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-auto ms-md-auto btns">
                            @if ($missingProfile->isNotEmpty())
                                <button type="button" class="btn-next" disabled style="opacity:.65; cursor:not-allowed;">
                                    {{ __('messages.blade_clearance_form_blade_profile_incomplete') }}
                                </button>
                            @else
                                <button type="button" class="btn-next" data-next="step2">
                                    {{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="form-step" id="step2">
                    <h3><i class="fas fa-file-upload"></i> {{ __('messages.clearance_step2_title') }}</h3>

                    <div class="upload-section row g-4">
                        @if ($needsPrimaryProof)
                            <div class="upload-group col-12">
                                <label for="primaryProof">
                                    <i class="fas fa-id-card"></i> {{ __('messages.residency_form_primary_proof_label') }}
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="primaryProofUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">{{ __('messages.blade_clearance_form_blade_drag_drop_your_proof_of_residency_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <p class="small text-muted mt-2">{{ __('messages.residency_form_proof_types') }}</p>
                                    <input type="file" id="primaryProof" name="primary_proof" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="primaryProofPreview"></div>
                            </div>
                        @else
                            <div class="upload-group col-12">
                                <div class="verified-requirement-card">
                                    <i class="fas fa-house-circle-check"></i>
                                    <div>
                                        <strong>{{ __('messages.blade_indigency_form_blade_residency_proof_already_verified') }}</strong>
                                        <p>{{ __('messages.blade_clearance_form_blade_you_do_not_need_to_upload_proof_of_residency_again_beca') }}</p>
                                        <span class="verified-file-name">
                                            <i class="fas fa-lock"></i>
                                            {{ $savedResidencyProofLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($needsValidIdUpload)
                            <div class="upload-group col-12">
                                <label for="validId">
                                    <i class="fas fa-id-card"></i> {{ __('messages.form_valid_id_label') }}
                                    <span class="upload-size">{{ __('messages.blade_clearance_form_blade_max_5mb_jpg_png_pdf') }}</span>
                                </label>
                                <div class="upload-area" id="validIdUpload">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <p class="mb-0">{{ __('messages.blade_clearance_form_blade_drag_drop_your_valid_id_or') }} <span>{{ __('messages.blade_clearance_form_blade_browse') }}</span></p>
                                    <input type="file" id="validId" name="valid_id_path" accept=".jpg,.jpeg,.png,.pdf" hidden required>
                                </div>
                                <div class="upload-preview mt-2" id="validIdPreview"></div>
                            </div>
                        @else
                            <div class="upload-group col-12">
                                <div class="verified-requirement-card">
                                    <i class="fas fa-id-card-clip"></i>
                                    <div>
                                        <strong>{{ __('messages.blade_clearance_form_blade_valid_id_already_verified') }}</strong>
                                        <p>{{ __('messages.blade_clearance_form_blade_you_do_not_need_to_upload_your_valid_id_again_because_i') }}</p>
                                        <span class="verified-file-name">
                                            <i class="fas fa-lock"></i>
                                            {{ $savedValidIdLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- PURPOSE (specific for indigency) -->
                    <div class="form-group mt-4">
                        <label for="purpose"><i class="fas fa-bullseye"></i> {{ __('messages.indigency_form_purpose_label') }}</label>
                        <select id="purpose" name="purpose" required class="form-control">
                            <option value="">{{ __('messages.blade_clearance_form_blade_select_purpose') }}</option>
                            <option value="medical">{{ __('messages.indigency_purpose_medical') }}</option>
                            <option value="scholarship">{{ __('messages.indigency_purpose_scholarship') }}</option>
                            <option value="government">{{ __('messages.indigency_purpose_government') }}</option>
                            <option value="legal">{{ __('messages.indigency_purpose_legal') }}</option>
                            <option value="employment">{{ __('messages.indigency_purpose_employment') }}</option>
                            <option value="burial">{{ __('messages.indigency_purpose_burial') }}</option>
                            <option value="other">{{ __('messages.clearance_purpose_other') }}</option>
                        </select>
                        <textarea id="purposeOther" name="purpose_other" placeholder="{{ __('messages.form_purpose_other_placeholder') }}" rows="2" style="{{ old('purpose') === 'other' ? '' : 'display: none;' }} margin-top: 10px;" class="form-control">{{ old('purpose_other') }}</textarea>
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

                <!-- STEP 3: REVIEW & SUBMIT (adapted for indigency) -->
                <div class="form-step" id="step3">
                    <h3><i class="fas fa-check-circle"></i> {{ __('messages.form_step_review_submit') }}</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-user-check"></i> {{ __('messages.blade_indigency_form_blade_locked_resident_profile') }}</h4>
                            <div class="review-content" id="reviewPersonal"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-check"></i> {{ __('messages.blade_indigency_form_blade_uploaded_documents') }}</h4>
                            <div class="review-content" id="reviewDocuments"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> {{ __('messages.indigency_form_fee_card_title') }}</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>{{ __('messages.indigency_form_fee_label') }}</span>
                                        <span style="text-align: end;">{{ __('messages.indigency_form_fee_amount') }}</span>
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
                                            <h5><i class="fas fa-building"></i> {{ __('messages.blade_clearance_form_blade_payment_pickup_location') }}</h5>
                                            <p>{{ __('messages.blade_clearance_form_blade_barangay_hulong_duhat_hall') }}<br>{{ __('messages.footer_address') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <h5><i class="fas fa-calendar-alt"></i> {{ __('messages.contacts_hours_title') }}</h5>
                                            <p>{{ __('messages.form_hours_weekdays') }}</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-user-check"></i>
                                        <div>
                                            <h5><i class="fas fa-id-card"></i> {{ __('messages.form_what_to_bring_label') }}</h5>
                                            <ul>
                                                <li>{{ __('messages.blade_indigency_form_blade_valid_id_same_as_uploaded') }}</li>
                                                <li>{{ __('messages.indigency_form_bring_reference') }}</li>
                                                <li>{{ __('messages.indigency_form_bring_no_payment') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- benefits (similar, but indigency-focused) -->
                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> {{ __('messages.indigency_form_benefits_title') }}</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>{{ __('messages.blade_clearance_form_blade_no_long_lines') }}</h5>
                                <p class="mb-0">{{ __('messages.blade_indigency_form_blade_skip_the_queue_when_you_arrive') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>{{ __('messages.form_benefit_2_title') }}</h5>
                                <p class="mb-0">{{ __('messages.indigency_form_benefit_2_desc') }}</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>{{ __('messages.indigency_form_benefit_3_title') }}</h5>
                                <p class="mb-0">{{ __('messages.indigency_form_benefit_3_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- TERMS (checkbox similar, but indigency wording) -->
                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkmark"></span>
                            {{ __('messages.indigency_form_terms_1') }}
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="privacy" name="privacy" required>
                            <span class="checkmark"></span>
                            <p style="margin-bottom: 0;">{{ __('messages.blade_clearance_form_blade_i_agree_to_the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal" onclick="event.stopPropagation();">{{ __('messages.register_privacy_title') }}</a> {{ __('messages.blade_clearance_form_blade_and') }} <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditionsModal" onclick="event.stopPropagation();">{{ __('messages.terms_modal_title') }}</a> {{ __('messages.blade_indigency_form_blade_of_barangay_hulo_online_services') }}</p>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="pickup" name="pickup" required>
                            <span class="checkmark"></span>
                            {{ __('messages.indigency_form_terms_pickup') }}
                        </label>
                    </div>
                    <div id="termsError" style="display:none;color:#ff4444;font-size:0.85rem;margin-top:8px;padding:8px 12px;background:rgba(255,68,68,0.05);border-radius:6px;border:1px solid rgba(255,68,68,0.3);">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ __('messages.form_terms_error') }}
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit">
                                <span class="btn-text">{{ __('messages.form_submit_btn') }}</span>
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


    <div class="modal fade" id="incorrectProfileModal" tabindex="-1" aria-labelledby="incorrectProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-report-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="incorrectProfileModalLabel">
                        <i class="fas fa-envelope me-2"></i>
                        {{ __('messages.blade_clearance_form_blade_email_profile_correction_concern') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.modal_close_btn') }}"></button>
                </div>

                <form method="POST" action="{{ \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile') ? route('indigency.reportIncorrectProfile') : '#' }}" onsubmit="return confirm(@json(__('messages.blade_indigency_form_blade_this_will_send_a_correction_concern_to_the_barangay_off')));">
                    @csrf

                    <div class="modal-body">
                        @if (! \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile'))
                            <div class="alert alert-warning">
                                {{ __('messages.blade_indigency_form_blade_add_the_route') }} <strong>{{ __('messages.blade_indigency_form_blade_indigency_reportincorrectprofile') }}</strong> {{ __('messages.blade_indigency_form_blade_first_before_using_this_correction_form') }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                {{ __('messages.blade_clearance_form_blade_your_identity_details_cannot_be_edited_directly_the_bar') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="fieldName">
                                <i class="fas fa-list"></i>
                                {{ __('messages.blade_clearance_form_blade_which_information_is_incorrect') }}
                            </label>
                            <select id="fieldName" name="field_name" class="form-control" required>
                                <option value="">{{ __('messages.blade_clearance_form_blade_select_information') }}</option>
                                <option value="Full name">{{ __('messages.form_review_label_full_name') }}</option>
                                <option value="Birthdate">{{ __('messages.blade_clearance_form_blade_birthdate') }}</option>
                                <option value="Gender">{{ __('messages.form_review_label_gender') }}</option>
                                <option value="Civil Status">{{ __('messages.blade_indigency_form_blade_civil_status') }}</option>
                                <option value="Place of Birth">{{ __('messages.blade_indigency_form_blade_place_of_birth') }}</option>
                                <option value="Complete address">{{ __('messages.register_address_label') }}</option>
                                <option value="Contact number">{{ __('messages.register_contact_label') }}</option>
                                <option value="Email address">{{ __('messages.register_email_label') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="correctValue">
                                <i class="fas fa-pen"></i>
                                {{ __('messages.blade_clearance_form_blade_correct_information') }}
                            </label>
                            <input type="text" id="correctValue" name="correct_value" class="form-control" required placeholder="{{ __('messages.blade_clearance_form_blade_enter_the_correct_information') }}">
                        </div>

                        <div class="form-group">
                            <label for="correctionMessage">
                                <i class="fas fa-message"></i>
                                {{ __('messages.blade_clearance_form_blade_message') }}
                            </label>
                            <textarea id="correctionMessage" name="message" class="form-control" rows="3" placeholder="{{ __('messages.blade_clearance_form_blade_explain_what_needs_to_be_corrected') }}"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-prev" data-bs-dismiss="modal">{{ __('messages.register_cancel_btn') }}</button>
                        <button type="submit" class="btn-correction-submit" @if (! \Illuminate\Support\Facades\Route::has('indigency.reportIncorrectProfile')) disabled @endif>
                            <i class="fas fa-paper-plane"></i>
                            {{ __('messages.blade_clearance_form_blade_send_email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('barangay_system.partials.policy_modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('requirementForm');
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next[data-next]');
            const prevButtons = document.querySelectorAll('.btn-prev[data-prev]');

            const profileConfirmed = document.getElementById('profileConfirmed');
            const monthlyIncome = document.getElementById('monthlyIncome');
            const householdMembers = document.getElementById('householdMembers');
            const purposeSelect = document.getElementById('purpose');
            const purposeOther = document.getElementById('purposeOther');
            const termsCheckbox = document.getElementById('terms');
            const privacyCheckbox = document.getElementById('privacy');
            const pickupCheckbox = document.getElementById('pickup');

            const needsPrimaryProofUpload = @json($needsPrimaryProof);
            const needsValidIdUpload = @json($needsValidIdUpload);
            const savedValidIdLabel = @json($savedValidIdLabel);
            const savedResidencyProofLabel = @json($savedResidencyProofLabel);
            const profileReviewData = @json($profileReviewData);

            let currentStep = 1;
            const totalSteps = 3;

            function clearErrors(scope = document) {
                scope.querySelectorAll('.error-message.dynamic-error, .upload-js-error').forEach(error => error.remove());

                scope.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                });

                scope.querySelectorAll('.upload-area').forEach(area => {
                    area.style.borderColor = '';
                    area.style.backgroundColor = '';
                });
            }

            function showFieldError(field, message) {
                if (!field) {
                    return;
                }

                field.classList.add('is-invalid');
                field.style.borderColor = '#ff4444';

                const error = document.createElement('div');
                error.className = 'error-message dynamic-error';
                error.textContent = message;

                const container = field.closest('.form-group, .locked-profile-card, .upload-group') || field.parentElement;
                container.appendChild(error);
            }

            function showUploadError(uploadArea, message) {
                if (!uploadArea) {
                    return;
                }

                uploadArea.style.borderColor = '#ff4444';
                uploadArea.style.backgroundColor = 'rgba(255, 68, 68, 0.05)';

                const error = document.createElement('div');
                error.className = 'error-message dynamic-error upload-js-error';
                error.style.textAlign = 'center';
                error.textContent = message;

                const container = uploadArea.closest('.upload-group') || uploadArea.parentElement;
                container.appendChild(error);
            }

            function validateHouseholdField(input) {
                if (!input) {
                    return false;
                }

                const value = String(input.value || '').trim();
                const numberValue = Number.parseInt(value, 10);

                if (value === '' || Number.isNaN(numberValue) || numberValue < 1 || numberValue > 20) {
                    showFieldError(input, @json(__('messages.blade_js_household_members_must_be_between_1_and_20')));
                    return false;
                }

                return true;
            }

            function validateFileInput(fileInput, uploadArea, label) {
                if (!fileInput || !uploadArea) {
                    return false;
                }

                if (!fileInput.files || fileInput.files.length === 0) {
                    showUploadError(uploadArea, @json(__('messages.blade_jsdyn_please_upload_label')).replace(':label', label));
                    return false;
                }

                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024;
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                const extension = (file.name.split('.').pop() || '').toLowerCase();

                if (!allowedExtensions.includes(extension)) {
                    showUploadError(uploadArea, @json(__('messages.blade_js_invalid_file_type_please_upload_jpg_png_or_pdf_only')));
                    return false;
                }

                if (file.size > maxSize) {
                    showUploadError(uploadArea, @json(__('messages.blade_js_file_is_too_large_maximum_file_size_is_5mb')));
                    return false;
                }

                return true;
            }

            function validateStep(stepNumber) {
                const currentStepElement = document.getElementById(`step${stepNumber}`);
                clearErrors(currentStepElement || document);

                let isValid = true;

                if (stepNumber === 1) {
                    if (!profileConfirmed || !profileConfirmed.checked) {
                        showFieldError(profileConfirmed, @json(__('messages.blade_js_please_confirm_your_locked_resident_profile_before_continu')));
                        isValid = false;
                    }

                    if (!monthlyIncome || monthlyIncome.value.trim() === '') {
                        showFieldError(monthlyIncome, @json(__('messages.blade_js_please_select_your_estimated_monthly_income')));
                        isValid = false;
                    }

                    if (!validateHouseholdField(householdMembers)) {
                        isValid = false;
                    }
                }

                if (stepNumber === 2) {
                    if (needsPrimaryProofUpload) {
                        const primaryProofInput = document.getElementById('primaryProof');
                        const primaryProofUpload = document.getElementById('primaryProofUpload');

                        if (!validateFileInput(primaryProofInput, primaryProofUpload, @json(__('messages.blade_upload_label_a_primary_proof_of_residency')))) {
                            isValid = false;
                        }
                    }

                    if (needsValidIdUpload) {
                        const validIdInput = document.getElementById('validId');
                        const validIdUpload = document.getElementById('validIdUpload');

                        if (!validateFileInput(validIdInput, validIdUpload, @json(__('messages.blade_upload_label_your_valid_id')))) {
                            isValid = false;
                        }
                    }

                    if (!purposeSelect || purposeSelect.value.trim() === '') {
                        showFieldError(purposeSelect, @json(__('messages.blade_js_please_select_the_purpose_of_your_indigency_certificate')));
                        isValid = false;
                    }

                    if (purposeSelect && purposeSelect.value === 'other' && purposeOther && purposeOther.value.trim() === '') {
                        showFieldError(purposeOther, @json(__('messages.blade_js_please_specify_the_other_purpose')));
                        isValid = false;
                    }
                }

                if (stepNumber === 3) {
                    if (!termsCheckbox || !termsCheckbox.checked || !privacyCheckbox || !privacyCheckbox.checked || !pickupCheckbox || !pickupCheckbox.checked) {
                        const termsError = document.getElementById('termsError');

                        if (termsError) {
                            termsError.style.display = 'block';
                        }

                        isValid = false;
                    }
                }

                if (!isValid) {
                    const firstError = currentStepElement?.querySelector('.error-message, #termsError');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return isValid;
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;

                if (progressFill) {
                    progressFill.style.width = `${progressPercentage}%`;
                }

                progressSteps.forEach(step => {
                    const stepNumber = Number.parseInt(step.getAttribute('data-step'), 10);
                    step.classList.toggle('active', stepNumber === currentStep);
                });
            }

            function showStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));

                const targetStep = document.getElementById(stepId);

                if (!targetStep) {
                    return;
                }

                targetStep.classList.add('active');
                currentStep = Number.parseInt(stepId.replace('step', ''), 10);
                updateProgress();

                if (currentStep === 3) {
                    updateReview();
                }

                targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            function formatFileSize(bytes) {
                if (!bytes) {
                    return '0 KB';
                }

                if (bytes < 1024 * 1024) {
                    return `${(bytes / 1024).toFixed(1)} KB`;
                }

                return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
            }

            function setupFileUpload(uploadAreaId, fileInputId, previewId) {
                const uploadArea = document.getElementById(uploadAreaId);
                const fileInput = document.getElementById(fileInputId);
                const preview = document.getElementById(previewId);

                if (!uploadArea || !fileInput || !preview) {
                    return;
                }

                function renderPreview(file) {
                    const extension = (file.name.split('.').pop() || '').toLowerCase();
                    const icon = extension === 'pdf' ? 'fa-file-pdf' : 'fa-file-image';

                    preview.classList.add('active');
                    preview.innerHTML = `
                        <div class="file-preview">
                            <i class="fas ${icon}"></i>
                            <div class="file-info">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" aria-label="${@json(__('messages.blade_runtime_remove_file'))}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    const removeButton = preview.querySelector('.remove-file');

                    if (removeButton) {
                        removeButton.addEventListener('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            fileInput.value = '';
                            preview.innerHTML = '';
                            preview.classList.remove('active');
                            clearErrors(uploadArea.closest('.upload-group') || document);
                        });
                    }
                }

                function handleFile(file) {
                    clearErrors(uploadArea.closest('.upload-group') || document);

                    if (!file) {
                        return;
                    }

                    const tempValid = validateFileInput(fileInput, uploadArea, @json(__('messages.blade_upload_label_the_selected_file')));

                    if (tempValid) {
                        renderPreview(file);
                    } else {
                        fileInput.value = '';
                        preview.innerHTML = '';
                        preview.classList.remove('active');
                    }
                }

                uploadArea.addEventListener('click', function () {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function () {
                    handleFile(this.files && this.files.length ? this.files[0] : null);
                });

                uploadArea.addEventListener('dragover', function (event) {
                    event.preventDefault();
                    uploadArea.style.borderColor = 'var(--cf-brand)';
                });

                uploadArea.addEventListener('dragleave', function () {
                    uploadArea.style.borderColor = '';
                });

                uploadArea.addEventListener('drop', function (event) {
                    event.preventDefault();

                    const file = event.dataTransfer.files && event.dataTransfer.files.length
                        ? event.dataTransfer.files[0]
                        : null;

                    if (!file) {
                        return;
                    }

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    handleFile(file);
                });
            }

            function updateReview() {
                const reviewPersonal = document.getElementById('reviewPersonal');
                const reviewDocuments = document.getElementById('reviewDocuments');

                if (reviewPersonal) {
                    reviewPersonal.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.form_review_label_full_name') }}</div>
                            <div class="review-value">${profileReviewData.fullName || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.blade_clearance_form_blade_birthdate') }}</div>
                            <div class="review-value">${profileReviewData.birthdate || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.form_review_label_gender') }}</div>
                            <div class="review-value">${profileReviewData.gender || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.form_review_label_address') }}</div>
                            <div class="review-value">${profileReviewData.address || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.form_review_label_contact') }}</div>
                            <div class="review-value">${profileReviewData.contact || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.form_review_label_email') }}</div>
                            <div class="review-value">${profileReviewData.email || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.blade_review_label_monthly_income') }}</div>
                            <div class="review-value">${monthlyIncome?.options[monthlyIncome.selectedIndex]?.text || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.blade_review_label_household_members') }}</div>
                            <div class="review-value">${householdMembers?.value || @json(__('messages.blade_announcement_blade_not_provided'))}</div>
                        </div>
                    `;
                }

                if (reviewDocuments) {
                    const primaryProofInput = document.getElementById('primaryProof');
                    const validIdInput = document.getElementById('validId');

                    const primaryProofText = needsPrimaryProofUpload
                        ? (primaryProofInput?.files?.length ? primaryProofInput.files[0].name : @json(__('messages.blade_js_no_file_uploaded')))
                        : savedResidencyProofLabel;

                    const validIdText = needsValidIdUpload
                        ? (validIdInput?.files?.length ? validIdInput.files[0].name : @json(__('messages.blade_js_no_file_uploaded')))
                        : @json(__('messages.blade_jsdyn_already_verified_label')).replace(':label', savedValidIdLabel);

                    const purposeText = purposeSelect?.options[purposeSelect.selectedIndex]?.text || @json(__('messages.blade_announcement_blade_not_provided'));
                    const otherText = purposeSelect?.value === 'other' && purposeOther?.value
                        ? `<div class="review-item">
                            <div class="review-label">{{ __('messages.blade_review_label_other_purpose_details') }}</div>
                            <div class="review-value">${purposeOther.value}</div>
                        </div>`
                        : '';

                    reviewDocuments.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.track_result_purpose_label') }}</div>
                            <div class="review-value">${purposeText}</div>
                        </div>
                        ${otherText}
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.blade_certificate_residency_blade_proof_of_residency') }}</div>
                            <div class="review-value">
                                <i class="fas fa-${needsPrimaryProofUpload ? 'file-circle-check' : 'lock'}"></i>
                                ${primaryProofText}
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">{{ __('messages.blade_certificate_indigency_blade_valid_id') }}</div>
                            <div class="review-value">
                                <i class="fas fa-${needsValidIdUpload ? 'file-circle-check' : 'lock'}"></i>
                                ${validIdText}
                            </div>
                        </div>
                    `;
                }
            }

            if (purposeSelect && purposeOther) {
                purposeSelect.addEventListener('change', function () {
                    if (this.value === 'other') {
                        purposeOther.style.display = 'block';
                        purposeOther.required = true;
                    } else {
                        purposeOther.style.display = 'none';
                        purposeOther.required = false;
                        purposeOther.value = '';
                    }

                    clearErrors(purposeOther.closest('.form-group') || document);
                });
            }

            if (householdMembers) {
                householdMembers.addEventListener('keydown', function (event) {
                    if (['e', 'E', '+', '-', '.'].includes(event.key)) {
                        event.preventDefault();
                    }
                });

                householdMembers.addEventListener('input', function () {
                    this.value = this.value.replace(/[^\d]/g, '');
                    clearErrors(this.closest('.form-group') || document);
                });
            }

            [profileConfirmed, monthlyIncome, termsCheckbox, privacyCheckbox, pickupCheckbox].forEach(field => {
                if (field) {
                    field.addEventListener('change', function () {
                        clearErrors(field.closest('.form-group, .locked-profile-card, .terms-section') || document);

                        const termsError = document.getElementById('termsError');
                        if (termsError) {
                            termsError.style.display = 'none';
                        }
                    });
                }
            });

            if (document.getElementById('primaryProofUpload') && document.getElementById('primaryProof')) {
                setupFileUpload('primaryProofUpload', 'primaryProof', 'primaryProofPreview');
            }

            if (document.getElementById('validIdUpload') && document.getElementById('validId')) {
                setupFileUpload('validIdUpload', 'validId', 'validIdPreview');
            }

            nextButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const nextStep = this.getAttribute('data-next');

                    if (validateStep(currentStep)) {
                        showStep(nextStep);
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const prevStep = this.getAttribute('data-prev');
                    showStep(prevStep);
                });
            });

            if (form) {
                form.addEventListener('submit', function (event) {
                    const termsError = document.getElementById('termsError');

                    if (termsError) {
                        termsError.style.display = 'none';
                    }

                    if (!validateStep(3)) {
                        event.preventDefault();
                        return;
                    }

                    const submitButton = form.querySelector('.btn-submit');

                    if (submitButton) {
                        submitButton.classList.add('loading');
                        submitButton.disabled = true;
                    }
                });
            }

            updateProgress();
        });
    </script>

</body>
</html>