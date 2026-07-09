<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">

    <style>
        :root {
            --primary: #d32f2f;
            --primary-light: #ffebee;
            --primary-dark: #b71c1c;
            --primary-gradient: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            --success: #2e7d32;
            --success-light: #e8f5e8;
            --warning: #ed6c02;
            --warning-light: #fff4e5;
            --info: #0288d1;
            --info-light: #e5f4ff;
            --gray-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 15px 40px rgba(211, 47, 47, 0.12);
            --border-color: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Validation Styles */
        .is-invalid {
            border-color: var(--primary) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25) !important;
        }

        .invalid-feedback {
            color: var(--primary);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .is-invalid + .invalid-feedback {
            display: block;
        }

        .alert-danger {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
            border-radius: 10px;
            padding: 1rem;
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .alert-danger li {
            padding: 0.25rem 0;
        }

        .alert-danger li::before {
            content: '';
            margin-right: 0.5rem;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .stat-number {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-icon {
            color: var(--primary);
            background: var(--primary-light);
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Table Styling - Mobile Optimized */
        .table-responsive {
            border-radius: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            width: 100%;
            min-width: 960px;
            font-size: 0.86rem;
        }

        @media (max-width: 1400px) {
            .table {
                min-width: 880px;
            }
        }

        @media (max-width: 768px) {
            .table {
                min-width: 800px;
                font-size: 0.8rem;
            }
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            border-bottom: 2px solid var(--border-color);
            white-space: normal;
            line-height: 1.2;
            padding: 0.65rem 0.5rem;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 0.65rem 0.5rem;
            color: #4a5568;
            border-bottom: 1px solid var(--border-color);
            white-space: normal;
            line-height: 1.25;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            border-radius: 8px;
            white-space: nowrap;
        }

        .badge.bg-success-subtle {
            background: var(--success-light) !important;
            color: var(--success);
        }

        .badge.bg-warning-subtle {
            background: var(--warning-light) !important;
            color: var(--warning);
        }

        .badge.bg-info-subtle {
            background: var(--info-light) !important;
            color: var(--info);
        }

        .badge.bg-secondary-subtle {
            background: #f8f9fa !important;
            color: var(--secondary);
        }

        /* Button Styling - Mobile Optimized */
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        @media (max-width: 576px) {
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .btn-sm {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-danger {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-danger:hover {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-success {
            color: var(--success);
            border-color: var(--success);
        }

        .btn-outline-success:hover {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background: #f8f9fa;
            color: #1e293b;
            border-color: #ced4da;
        }

        .btn-outline-warning {
            color: var(--warning);
            border-color: var(--warning);
        }

        .btn-outline-warning:hover {
            background: var(--warning);
            border-color: var(--warning);
            color: white;
        }

        /* Card Styling */
        .card {
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--hover-shadow);
        }

        /* Form Styling - Mobile Optimized */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.15);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1px solid var(--border-color);
        }

        /* Modal Styling - Mobile Optimized */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        @media (max-width: 576px) {
            .modal-header {
                padding: 1rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-footer {
                padding: 1rem;
            }
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        /* Pagination Styling - Mobile Optimized */
        .pagination {
            gap: 5px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--border-color);
            color: var(--primary);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
        }

        .page-link:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-color: var(--primary);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: var(--primary);
            color: white;
        }

        /* Header Styling - Mobile Optimized */
        .header {
            padding: 1rem 1.5rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.75rem 1rem;
            }
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.2rem;
            }
        }

        .profile-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 40px;
            font-weight: 500;
            color: #1e293b;
            z-index: 1;
        }

        .profile-badge i {
            font-size: 1.2rem;
            color: var(--primary);
        }
        
        .role-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        #userDropdown{
            color: var(--primary);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1e293b;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .mobile-menu-btn:hover {
            background: #f8f9fa;
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 11px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .profile-badge span {
                display: none;
            }

            .profile-badge {
                padding: 0.5rem;
                border-radius: 50%;
            }

            .d-flex.gap-2.justify-content-end {
                flex-wrap: wrap;
            }
        }

        /* Filter Section - Mobile Optimized */
        @media (max-width: 768px) {
            .row.g-3 > [class*="col-"] {
                margin-bottom: 0.5rem;
            }
            
            .btn.w-100 {
                margin-top: 0.5rem;
            }
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* Loading Animation */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Mobile Action Buttons */
        @media (max-width: 480px) {
            .btn-outline-danger.d-flex span,
            .btn-outline-success.d-flex span {
                display: none;
            }
            
            .btn-outline-danger.d-flex,
            .btn-outline-success.d-flex {
                padding: 0.5rem;
            }
            
            .btn-outline-danger.d-flex i,
            .btn-outline-success.d-flex i {
                margin: 0;
            }
        }

        /* Improved Touch Targets for Mobile */
        @media (max-width: 768px) {
            .btn, 
            .page-link,
            .form-control,
            .form-select,
            .input-group-text {
                min-height: 44px;
            }
            
            input[type="checkbox"] {
                width: 22px;
                height: 22px;
            }
            
            .table td .btn-sm {
                min-height: 36px;
                min-width: 36px;
                padding: 0.5rem;
            }
        }

        /* Container Padding for Mobile */
        @media (max-width: 768px) {
            .p-lg-4 {
                padding: 0.75rem !important;
            }
        }

        /* File Upload Styling */
        .form-control[type="file"] {
            padding: 1rem;
            background: #f9fafb;
            border-style: dashed;
            align-items: center;
        }

        .form-control[type="file"]::file-selector-button {
            border-radius: 10px;
            border: 1px solid var(--line-strong);
            background: #ffffff;
            padding: .48rem .75rem;
            margin-right: .75rem;
            color: var(--text);
            font-weight: 760;
            transition: all .16s ease;
        }

        .form-control[type="file"]::file-selector-button:hover {
            color: var(--primary-dark);
            background: #fffafa;
            border-color: rgba(211, 47, 47, .22);
        }

        @media (max-width: 768px) {
            .d-flex.gap-1.gap-sm-2.justify-content-end {
                flex-wrap: wrap;
                justify-content: flex-start !important;
            }
            
            .btn-group {
                margin-bottom: 0.25rem;
            }
            
            .dropdown-menu {
                min-width: 200px;
            }
            
            .dropdown-item {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .dropdown-item i {
                width: 20px;
                text-align: center;
            }
        }

        /* Dropdown button styling */
        .btn-group .btn-sm {
            padding: 0.3rem 0.6rem;
        }

        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-item form {
            width: 100%;
        }

        .dropdown-item button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            color: inherit;
        }

        .dropdown-item button:hover {
            background: none;
        }

        /* Image Preview */
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 10px;
            border: 1px solid var(--border-color);
        }


        /* ==========================================================
           Announcements UX redesign override
           Matches the Projects page layout, spacing, tables, and modals.
           Keeps existing sidebar/header/routes/forms/permissions intact.
        ========================================================== */
        :root {
            --primary: #d32f2f;
            --primary-dark: #a91f1f;
            --primary-light: #fff5f5;
            --primary-soft: #fff5f5;
            --success: #16803c;
            --success-light: #eefaf2;
            --warning: #d97706;
            --warning-light: #fff7ed;
            --danger: #dc2626;
            --danger-light: #fff1f2;
            --info: #0369a1;
            --info-light: #eff6ff;
            --secondary: #667085;
            --secondary-light: #f9fafb;
            --gray-bg: #f8fafc;
            --page-bg: #f8fafc;
            --card: #ffffff;
            --text: #111827;
            --muted: #667085;
            --muted-2: #98a2b3;
            --line: #e5e7eb;
            --line-strong: #d0d5dd;
            --border-color: #e5e7eb;
            --shadow-xs: 0 1px 2px rgba(16, 24, 40, .05);
            --shadow-sm: 0 8px 22px rgba(16, 24, 40, .06);
            --radius-xl: 22px;
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        html { font-size: 70%; scroll-behavior: smooth; }

        body {
            background: var(--page-bg);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .min-w-0 { min-width: 0 !important; }

        .announcements-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .announcements-alert,
        .announcements-page > .alert,
        main > .alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .announcements-alert.fade:not(.show),
        .announcements-page > .alert.fade:not(.show),
        main > .alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .alert-danger li::before { content: ''; margin-right: 0; }

        .announcements-hero,
        .announcements-panel,
        .stat-card,
        .card.border-0 {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .announcements-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            padding: clamp(1.1rem, 1.8vw, 1.5rem);
        }

        .announcements-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(135deg, rgba(211, 47, 47, .04), transparent 42%);
            pointer-events: none;
        }

        .announcements-hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
        }

        .announcements-hero-icon,
        .announcements-panel-icon,
        .stat-icon,
        .announcement-icon,
        .empty-state-icon,
        .modal-title i,
        .detail-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .announcements-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .14);
            border-radius: 14px;
            font-size: 1.12rem;
        }

        .announcements-kicker,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .announcements-hero h2 {
            margin: .48rem 0 .42rem;
            color: var(--text);
            font-size: clamp(1.65rem, 2.6vw, 2.25rem);
            font-weight: 850;
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .announcements-hero p,
        .panel-copy,
        .empty-state p,
        .modal-subtitle,
        .field-hint {
            color: var(--muted);
            line-height: 1.6;
        }

        .announcements-hero p {
            max-width: 720px;
            margin: 0;
            font-size: .93rem;
        }

        .announcements-hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .65rem;
        }

        .announcements-stats-row {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-right: 0 !important;
            margin-left: 0 !important;
            margin-bottom: 1rem !important;
        }

        .announcements-stats-row > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .notification-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
        }

        .notification-summary-card {
            padding: .9rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
        }

        .notification-summary-card span {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .notification-summary-card strong {
            display: block;
            margin-top: .25rem;
            color: var(--text);
            font-size: 1.45rem;
            font-weight: 850;
            letter-spacing: -.04em;
        }

        .notification-preview-box {
            overflow: hidden;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .notification-preview-header {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            padding: .75rem .9rem;
            color: var(--text);
            background: #ffffff;
            border-bottom: 1px solid var(--line);
            font-size: .78rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .notification-preview-header span {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .notification-preview-header span:last-child {
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            padding: .28rem .55rem;
        }

        .notification-preview-box p {
            min-height: 90px;
            margin: 0;
            padding: .95rem;
            color: var(--text);
            line-height: 1.65;
            white-space: pre-wrap;
        }

        .notification-warning-box {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .85rem;
            color: #7c2d12;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 16px;
            line-height: 1.5;
        }

        .notification-warning-box i {
            margin-top: .12rem;
        }

        @media (max-width: 991px) {
            .notification-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575px) {
            .notification-summary-grid {
                grid-template-columns: 1fr;
            }
        }

        .sms-credit-box {
            padding: .95rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 18px;
        }

        .sms-credit-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .85rem;
        }

        .sms-credit-label {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .sms-credit-main strong {
            display: block;
            margin-top: .22rem;
            color: var(--text);
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: -.045em;
        }

        .sms-credit-main small {
            display: block;
            margin-top: .18rem;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
        }

        .sms-credit-status {
            flex: 0 0 auto;
            padding: .42rem .68rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 850;
            background: #f2f4f7;
            color: #344054;
            border: 1px solid var(--line);
        }

        .sms-credit-status.is-ok {
            color: #16803c;
            background: #eefaf2;
            border-color: rgba(22, 128, 60, .18);
        }

        .sms-credit-status.is-danger {
            color: #dc2626;
            background: #fff1f2;
            border-color: rgba(220, 38, 38, .18);
        }

        .sms-credit-details {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .55rem;
            margin-top: .85rem;
        }

        .sms-credit-details span {
            padding: .68rem .75rem;
            color: var(--muted);
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 14px;
            font-size: .76rem;
            font-weight: 750;
        }

        .sms-credit-details strong {
            color: var(--text);
            font-weight: 900;
        }

        @media (max-width: 768px) {
            .sms-credit-main {
                display: grid;
            }

            .sms-credit-status {
                width: fit-content;
            }

            .sms-credit-details {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            height: 100%;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: flex-start !important;
            gap: .85rem;
            padding: 1rem !important;
            overflow: visible !important;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .stat-card::before { display: none !important; }

        .stat-card:hover,
        .announcements-panel:hover,
        .card.border-0:hover {
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stat-card:hover { transform: translateY(-1px) !important; }

        .stat-card > div:first-child { min-width: 0; order: 2; }

        .stat-icon {
            flex: 0 0 auto;
            order: 1;
            width: 42px;
            height: 42px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 13px;
            font-size: 1rem;
        }

        .stat-label {
            color: var(--muted) !important;
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .stat-number {
            display: block;
            margin-top: .26rem;
            color: var(--text);
            font-size: clamp(1.3rem, 2vw, 1.75rem);
            font-weight: 850;
            line-height: 1.1;
            letter-spacing: -.045em;
            overflow-wrap: anywhere;
        }

        .stat-card small {
            display: block !important;
            margin-top: .42rem !important;
            color: var(--muted) !important;
            font-size: .78rem;
            font-weight: 650;
            line-height: 1.45;
        }

        .announcements-panel { overflow: hidden; }

        .announcements-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .announcements-panel-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .announcements-panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .announcements-panel-header h3 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .panel-copy { margin: 0; font-size: .86rem; }

        .announcements-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .announcements-count-pill,
        .selected-count {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            width: fit-content;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .resident-picker-list {
            display: grid;
            gap: .55rem;
            max-height: 260px;
            overflow-y: auto;
            padding: .65rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .resident-picker-item {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .75rem;
            cursor: pointer;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 14px;
            transition: border-color .16s ease, background .16s ease;
        }

        .resident-picker-item:hover {
            background: #fffafa;
            border-color: rgba(211, 47, 47, .22);
        }

        .resident-picker-item input {
            flex: 0 0 auto;
            margin-top: .15rem;
        }

        .resident-picker-item span {
            display: grid;
            gap: .18rem;
            min-width: 0;
        }

        .resident-picker-item strong {
            color: var(--text);
            font-size: .88rem;
            font-weight: 850;
        }

        .resident-picker-item small {
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            overflow-wrap: anywhere;
        }

        .announcements-count-pill { padding: .42rem .68rem; }
        .selected-count { padding: .42rem .68rem; color: var(--muted); background: #f9fafb; border-color: var(--line); }

        .announcements-filter-panel { margin-bottom: .75rem !important; }
        .announcements-filter-panel .announcements-panel-header { padding-bottom: .85rem; }
        .announcements-filter-body { padding: .85rem 1rem; }

        .announcements-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(360px, 1.55fr) minmax(145px, .62fr) minmax(145px, .62fr) minmax(145px, .62fr) auto;
            gap: .55rem;
            align-items: stretch;
        }

        .announcements-search-group {
            display: flex;
            align-items: stretch;
            min-width: 0;
        }

        .announcements-search-field {
            position: relative;
            flex: 1 1 auto;
            min-width: 0;
        }

        .announcements-search-field i {
            position: absolute;
            top: 50%;
            left: .9rem;
            transform: translateY(-50%);
            color: var(--muted-2);
            pointer-events: none;
        }

        .announcements-search-group .form-control {
            height: 100%;
            padding-left: 2.4rem !important;
            border-right: 0;
            border-radius: 12px 0 0 12px;
        }

        .announcements-search-group .search-submit-btn {
            flex: 0 0 auto;
            min-width: 104px;
            border-radius: 0 12px 12px 0;
            box-shadow: none;
            white-space: nowrap;
        }

        .filter-submit-btn { white-space: nowrap; }

        .announcements-bulk-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            margin-top: .85rem;
            padding-top: .85rem;
            border-top: 1px dashed var(--line-strong);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .48rem;
            min-height: 42px;
            padding: .65rem .95rem;
            border-radius: 12px;
            font-weight: 760;
            letter-spacing: -.01em;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease, border-color .16s ease, color .16s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn:focus-visible,
        .form-control:focus,
        .form-select:focus,
        input[type="checkbox"]:focus-visible {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none !important;
        }

        .btn-primary,
        .btn-danger,
        .btn-success {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-success:hover,
        .btn-success:focus {
            color: #ffffff !important;
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: 0 12px 26px rgba(211, 47, 47, .22);
        }

        .btn-light-soft,
        .btn-secondary,
        .btn-outline-secondary {
            color: var(--text) !important;
            background: var(--card) !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .btn-secondary:hover,
        .btn-outline-secondary:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211, 47, 47, .22) !important;
        }

        .btn-outline-primary {
            color: var(--primary) !important;
            background: var(--card) !important;
            border-color: rgba(211, 47, 47, .25) !important;
        }

        .btn-outline-primary:hover {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .btn-outline-danger {
            color: var(--danger) !important;
            background: var(--card) !important;
            border-color: rgba(220, 38, 38, .25) !important;
        }

        .btn-outline-danger:hover {
            color: #ffffff !important;
            background: var(--danger) !important;
            border-color: var(--danger) !important;
        }

        .btn-outline-success {
            color: var(--success) !important;
            background: var(--card) !important;
            border-color: rgba(22, 128, 60, .25) !important;
        }

        .btn-outline-success:hover {
            color: #ffffff !important;
            background: var(--success) !important;
            border-color: var(--success) !important;
        }

        .btn-outline-warning {
            color: #9a3412 !important;
            background: var(--card) !important;
            border-color: #fed7aa !important;
        }

        .btn-outline-warning:hover {
            color: #7c2d12 !important;
            background: #fff7ed !important;
            border-color: #fdba74 !important;
        }

        .btn-sm,
        .btn-group-sm > .btn {
            min-height: 36px;
            padding: .5rem .68rem;
            border-radius: 11px;
            font-size: .82rem;
        }

        .form-control,
        .form-select {
            min-height: 44px;
            color: var(--text);
            background-color: #ffffff;
            border: 1px solid var(--line-strong);
            border-radius: 12px;
            padding: .62rem .9rem;
            font-size: .9rem;
            font-weight: 650;
        }

        .form-control::placeholder { color: var(--muted-2); }

        textarea.form-control {
            min-height: 150px;
            line-height: 1.6;
            resize: vertical;
        }

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
        }

        .field-hint {
            display: block;
            margin-top: .35rem;
            font-size: .75rem;
            font-weight: 650;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0 !important;
        }

        .table { min-width: 980px; margin-bottom: 0; }

        .table thead th {
            padding: .85rem .95rem;
            color: #475467;
            background: #f9fafb;
            border-bottom: 1px solid var(--line);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: .95rem;
            color: var(--text);
            border-bottom: 1px solid var(--line);
            font-size: .86rem;
        }

        .table tbody tr { transition: background .16s ease; }
        .table tbody tr:hover { background: #fcfcfd; }

        .announcement-icon {
            flex: 0 0 auto;
            width: 40px !important;
            height: 40px !important;
            color: var(--primary);
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px !important;
            overflow: hidden;
        }

        .announcement-icon img {
            width: 40px !important;
            height: 40px !important;
            border-radius: 14px !important;
            object-fit: cover;
        }


        .photo-view-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            color: inherit;
            background: transparent;
            border: 0;
            border-radius: inherit;
            cursor: zoom-in;
        }

        .photo-view-btn:focus-visible {
            outline: 3px solid rgba(211, 47, 47, .18);
            outline-offset: 3px;
        }

        .photo-view-btn:hover img,
        .zoomable-image:hover {
            filter: brightness(.94);
        }

        .announcement-icon .photo-view-btn {
            width: 100%;
            height: 100%;
        }

        .photo-view-main {
            width: 100%;
            overflow: hidden;
            border-radius: 18px;
        }

        .zoom-hint {
            position: absolute;
            right: .75rem;
            bottom: .75rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .42rem .68rem;
            color: #ffffff;
            background: rgba(17, 24, 39, .72);
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 800;
            backdrop-filter: blur(8px);
        }

        .image-zoom-modal .modal-dialog {
            max-width: min(1080px, calc(100% - 1.5rem));
        }

        .image-zoom-modal .modal-content {
            background: #ffffff;
        }

        .zoomed-image {
            width: auto;
            max-width: 100%;
            max-height: calc(100vh - 150px);
            object-fit: contain;
            border-radius: 16px;
        }

        .badge,
        .date-badge,
        .views-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            border-radius: 999px;
            padding: .4rem .62rem;
            font-size: .74rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .views-badge,
        .date-badge {
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
        }

        .badge.bg-success-subtle {
            color: var(--success) !important;
            background: var(--success-light) !important;
            border: 1px solid rgba(22, 128, 60, .14);
        }

        .badge.bg-danger-subtle {
            color: var(--danger) !important;
            background: var(--danger-light) !important;
            border: 1px solid rgba(220, 38, 38, .14);
        }

        .badge.bg-warning-subtle {
            color: #9a3412 !important;
            background: var(--warning-light) !important;
            border: 1px solid #fed7aa;
        }

        .badge.bg-info-subtle {
            color: var(--info) !important;
            background: var(--info-light) !important;
            border: 1px solid #dbeafe;
        }

        .badge.bg-secondary-subtle {
            color: #344054 !important;
            background: #f9fafb !important;
            border: 1px solid var(--line);
        }

        .empty-state {
            display: grid;
            place-items: center;
            min-height: 240px;
            padding: 2rem;
            text-align: center;
            color: var(--muted);
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 20px;
            font-size: 1.35rem;
        }

        .empty-state h4 {
            margin-bottom: .35rem;
            color: var(--text);
            font-size: 1rem;
            font-weight: 820;
        }

        .empty-state p {
            max-width: 360px;
            margin: 0 auto 1rem;
            font-size: .88rem;
        }

        .modal-content {
            overflow: hidden;
            border: 0 !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: 0 24px 70px rgba(16, 24, 40, .18) !important;
        }

        .modal-header {
            align-items: flex-start;
            gap: .85rem;
            padding: 1rem !important;
            color: var(--text) !important;
            background: linear-gradient(180deg, #ffffff, #fbfdff) !important;
            border-bottom: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0 !important;
        }

        .modal-header .btn-close {
            filter: none !important;
            opacity: .72;
        }

        .modal-title {
            display: flex;
            align-items: flex-start;
            gap: .72rem;
            margin: 0;
            color: var(--text) !important;
            font-size: 1rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .modal-title i {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            margin: 0 !important;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .modal-title-stack {
            display: grid;
            gap: .18rem;
        }

        .modal-subtitle {
            display: block;
            max-width: 620px;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: 0;
        }

        .modal-body {
            padding: 1rem !important;
            max-height: 72vh;
            overflow-y: auto;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 2;
            gap: .55rem;
            padding: .85rem 1rem 1rem !important;
            background: linear-gradient(180deg, rgba(255, 255, 255, .92), #ffffff);
            border-top: 1px solid var(--line) !important;
        }

        .modal-form-grid,
        .modal-body form > .row.g-3,
        .modal-body > .row.g-3 {
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
        }

        .modal-section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .85rem;
            color: var(--text);
            font-size: .9rem;
            font-weight: 850;
            letter-spacing: -.02em;
        }

        .modal-section-title i { color: var(--primary); }

        .announcement-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .detail-card {
            padding: .85rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .detail-card label {
            display: block;
            margin-bottom: .4rem;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .detail-card p { margin: 0; color: var(--text); font-weight: 700; }

        .description-box {
            padding: .95rem;
            color: var(--text);
            background: #f9fafb !important;
            border: 1px solid var(--line);
            border-radius: 14px;
            line-height: 1.65;
        }

        .image-preview,
        .announcement-image-preview {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-xs);
        }

        .modal-image-hero {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid var(--line);
        }

        .is-invalid {
            border-color: var(--danger) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            border-color: var(--danger) !important;
            outline-color: rgba(220, 38, 38, .16);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: .76rem;
            font-weight: 760;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        @media (max-width: 1199px) {
            .announcements-stats-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .announcements-filter-toolbar { grid-template-columns: minmax(0, 1fr) minmax(145px, .65fr) minmax(145px, .65fr); }
            .announcements-filter-toolbar .filter-submit-btn { width: 100%; }
        }

        @media (max-width: 991px) {
            .announcements-hero-content,
            .announcements-panel-header { grid-template-columns: minmax(0, 1fr); }
            .announcements-hero-content { display: block; }
            .announcements-hero-actions,
            .announcements-panel-actions,
            .announcements-panel-actions .btn { width: 100%; justify-content: stretch; }
            .announcements-hero-actions .btn { flex: 1 1 180px; }
            .announcements-filter-toolbar { grid-template-columns: minmax(0, 1fr); }
            .announcements-search-group { width: 100%; }
            .announcements-search-group .search-submit-btn { min-width: 96px; }
            .announcements-filter-toolbar .filter-submit-btn { width: 100%; }
            .announcements-bulk-row { flex-direction: column; align-items: stretch; }
            .table { min-width: 860px; }
        }

        @media (max-width: 768px) {
            .header { padding: .85rem 1rem; }
            .page-title { font-size: 1.02rem; }
            .announcements-page { max-width: 100%; }
            .announcements-hero,
            .announcements-panel,
            .stat-card,
            .card.border-0 { border-radius: 16px !important; }
            .announcements-hero,
            .announcements-panel-header,
            .announcements-filter-body,
            .modal-header,
            .modal-body,
            .modal-footer { padding: .9rem !important; }
            .announcements-hero-actions,
            .announcements-hero-actions .btn { width: 100%; }
            .announcements-stats-row { grid-template-columns: minmax(0, 1fr); }
            .announcement-detail-grid { grid-template-columns: minmax(0, 1fr); }
            .modal-footer .btn { width: 100%; }
        }

        @media (max-width: 575px) {
            main.p-3,
            main.p-lg-4 { padding: .85rem !important; }
            .announcements-hero h2 { font-size: 1.7rem; }
        }

    

        /* ==========================================================
           FINAL MOBILE POLISH V2 - Announcements
           Clean card UX on phones. Desktop/tablet table stays unchanged.
        ========================================================== */
        @media (max-width: 767.98px) {
            html { font-size: 78% !important; }
            body { background: #f8fafc !important; }

            .main-content { min-width: 0 !important; }
            main.p-3,
            main.p-lg-4 { padding: .75rem !important; }

            .header {
                position: sticky;
                top: 0;
                z-index: 99;
                padding: .75rem .85rem !important;
                background: rgba(255,255,255,.96) !important;
                backdrop-filter: blur(12px);
                box-shadow: 0 1px 0 var(--line);
            }

            .page-title {
                display: flex;
                align-items: center;
                gap: .65rem;
                min-width: 0;
                font-size: 1rem !important;
                font-weight: 850 !important;
                letter-spacing: -.02em;
                line-height: 1.2;
            }

            .title-icon,
            .mobile-menu-btn {
                flex: 0 0 auto;
                width: 38px !important;
                height: 38px !important;
                border-radius: 12px !important;
            }

            .mobile-menu-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                background: #ffffff !important;
                border: 1px solid var(--line) !important;
                box-shadow: var(--shadow-xs);
                font-size: 1rem !important;
            }

            .announcements-page { max-width: 100% !important; }

            .announcements-hero,
            .announcements-panel,
            .stat-card,
            .card.border-0 {
                border-radius: 18px !important;
            }

            .announcements-hero { padding: 1rem !important; margin-bottom: .75rem !important; }
            .announcements-hero-content { display: block !important; }
            .announcements-hero-icon { width: 42px !important; height: 42px !important; margin-bottom: .65rem !important; }
            .announcements-hero h2 { font-size: 1.45rem !important; line-height: 1.12 !important; letter-spacing: -.04em !important; }
            .announcements-hero p { font-size: .88rem !important; line-height: 1.55 !important; }
            .announcements-hero-actions { display: grid !important; grid-template-columns: 1fr 1fr; gap: .55rem !important; margin-top: .9rem !important; }
            .announcements-hero-actions .btn { width: 100% !important; min-height: 42px !important; padding: .62rem .65rem !important; }

            .announcements-stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .55rem !important;
                margin-bottom: .75rem !important;
            }

            .stat-card {
                min-height: 98px;
                padding: .85rem !important;
                gap: .6rem !important;
                align-items: flex-start !important;
            }

            .stat-icon {
                width: 36px !important;
                height: 36px !important;
                border-radius: 12px !important;
                font-size: .95rem !important;
            }

            .stat-label { font-size: .66rem !important; letter-spacing: .06em !important; }
            .stat-number { font-size: 1.45rem !important; }
            .stat-card small { display: none !important; }

            .announcements-panel-header {
                display: block !important;
                padding: .9rem !important;
            }

            .announcements-panel-title { gap: .7rem !important; }
            .announcements-panel-icon { width: 40px !important; height: 40px !important; border-radius: 13px !important; }
            .announcements-panel-header h3 { font-size: .98rem !important; }
            .panel-copy { font-size: .82rem !important; margin-top: .12rem !important; }
            .announcements-panel-actions { justify-content: flex-start !important; margin-top: .75rem !important; width: 100% !important; }
            .announcements-panel-actions .btn,
            .announcements-panel-actions .announcements-count-pill { width: 100% !important; justify-content: center !important; }

            .announcements-filter-body { padding: .85rem !important; }
            .announcements-filter-toolbar { display: grid !important; grid-template-columns: 1fr !important; gap: .6rem !important; }
            .announcements-search-group { display: grid !important; grid-template-columns: 1fr !important; gap: .55rem !important; width: 100% !important; }
            .announcements-search-group .form-control,
            .announcements-search-group .search-submit-btn,
            .announcements-search-field .form-control,
            .announcements-filter-toolbar .form-select,
            .filter-submit-btn {
                width: 100% !important;
                min-height: 44px !important;
                border-radius: 13px !important;
                border: 1px solid var(--line-strong) !important;
            }
            .announcements-search-group .form-control { border-right: 1px solid var(--line-strong) !important; }
            .announcements-search-group .search-submit-btn { min-width: 0 !important; }

            .announcements-bulk-row {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .6rem !important;
                margin-top: .7rem !important;
                padding-top: .7rem !important;
            }
            .announcements-bulk-row .selected-count,
            .announcements-bulk-row .btn { width: 100% !important; justify-content: center !important; }

            /* Real mobile cards for the Announcements table */
            .announcements-panel .card-body { background: #f8fafc !important; }
            #announcementsTable {
                display: block !important;
                width: 100% !important;
                min-width: 0 !important;
                border-collapse: separate !important;
            }
            #announcementsTable thead { display: none !important; }
            #announcementsTable tbody {
                display: grid !important;
                gap: .8rem !important;
                padding: .85rem !important;
                background: #f8fafc !important;
            }
            #announcementsTable tbody tr {
                display: grid !important;
                grid-template-columns: 32px minmax(0, 1fr) auto;
                grid-template-areas:
                    "select title status"
                    "category category category"
                    "featured featured featured"
                    "views views views"
                    "published published published"
                    "actions actions actions";
                gap: .62rem .65rem;
                align-items: start;
                padding: .9rem !important;
                background: #ffffff !important;
                border: 1px solid var(--line) !important;
                border-radius: 18px !important;
                box-shadow: 0 8px 20px rgba(16,24,40,.045) !important;
            }
            #announcementsTable tbody tr:hover { background: #ffffff !important; transform: none !important; }
            #announcementsTable tbody td {
                display: block !important;
                width: auto !important;
                min-width: 0 !important;
                padding: 0 !important;
                border: 0 !important;
                text-align: left !important;
                white-space: normal !important;
            }
            #announcementsTable tbody td[colspan] { grid-column: 1 / -1 !important; }

            #announcementsTable tbody td:nth-child(1) { grid-area: select; padding-top: .28rem !important; }
            #announcementsTable tbody td:nth-child(1) input { width: 22px !important; height: 22px !important; }

            #announcementsTable tbody td:nth-child(2) { grid-area: title; }
            #announcementsTable tbody td:nth-child(2) .d-flex { align-items: flex-start !important; gap: .65rem !important; }
            #announcementsTable tbody td:nth-child(2) .announcement-icon { width: 42px !important; height: 42px !important; border-radius: 14px !important; margin-right: 0 !important; }
            #announcementsTable tbody td:nth-child(2) .announcement-icon img { width: 42px !important; height: 42px !important; border-radius: 14px !important; }
            #announcementsTable tbody td:nth-child(2) .fw-semibold {
                color: var(--text) !important;
                font-size: 1rem !important;
                font-weight: 850 !important;
                line-height: 1.25 !important;
                white-space: normal !important;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            #announcementsTable tbody td:nth-child(2) small { display: none !important; }

            #announcementsTable tbody td:nth-child(7) {
                grid-area: status;
                display: flex !important;
                justify-content: flex-end !important;
                align-self: start;
            }
            #announcementsTable tbody td:nth-child(7) .badge { padding: .42rem .62rem !important; font-size: .72rem !important; }

            #announcementsTable tbody td:nth-child(3),
            #announcementsTable tbody td:nth-child(4),
            #announcementsTable tbody td:nth-child(5),
            #announcementsTable tbody td:nth-child(6) {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                gap: .75rem;
                padding: .68rem .75rem !important;
                background: #ffffff;
                border: 1px solid var(--line) !important;
                border-radius: 14px;
            }
            #announcementsTable tbody td:nth-child(3) { grid-area: category; }
            #announcementsTable tbody td:nth-child(4) { grid-area: featured; }
            #announcementsTable tbody td:nth-child(5) { grid-area: views; }
            #announcementsTable tbody td:nth-child(6) { grid-area: published; }
            #announcementsTable tbody td:nth-child(3)::before { content: "Category"; }
            #announcementsTable tbody td:nth-child(4)::before { content: "Featured"; }
            #announcementsTable tbody td:nth-child(5)::before { content: "Views"; }
            #announcementsTable tbody td:nth-child(6)::before { content: "Published"; }
            #announcementsTable tbody td:nth-child(3)::before,
            #announcementsTable tbody td:nth-child(4)::before,
            #announcementsTable tbody td:nth-child(5)::before,
            #announcementsTable tbody td:nth-child(6)::before {
                flex: 0 0 auto;
                color: var(--muted);
                font-size: .68rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .08em;
            }
            #announcementsTable tbody td:nth-child(3) .badge,
            #announcementsTable tbody td:nth-child(4) .badge,
            #announcementsTable tbody td:nth-child(5) .views-badge,
            #announcementsTable tbody td:nth-child(6) .date-badge {
                max-width: 58%;
                justify-content: flex-end;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            #announcementsTable tbody td:nth-child(8) { grid-area: actions; padding-top: .2rem !important; }
            #announcementsTable tbody td:nth-child(8) > .d-flex {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: .5rem !important;
                justify-content: stretch !important;
            }
            #announcementsTable tbody td:nth-child(8) form { display: block !important; width: 100% !important; }
            #announcementsTable tbody td:nth-child(8) .btn {
                width: 100% !important;
                min-height: 42px !important;
                padding: .58rem .42rem !important;
                border-radius: 13px !important;
                font-size: .78rem !important;
            }
            #announcementsTable tbody td:nth-child(8) .btn::after { margin-left: .25rem; font-weight: 800; }
            #announcementsTable tbody td:nth-child(8) .btn[title="View Details"]::after { content: "View"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Edit Announcement"]::after { content: "Edit"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Archive"]::after { content: "Archive"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Restore from deleted bin"]::after { content: "Restore"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Publish again"]::after { content: "Publish"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Mark as Featured"]::after { content: "Feature"; }
            #announcementsTable tbody td:nth-child(8) .btn[title="Remove Featured"]::after { content: "Unstar"; }

            .empty-state { min-height: 210px !important; padding: 1.25rem !important; }

            .border-top.d-flex.flex-column.flex-md-row {
                padding: .85rem !important;
                background: #ffffff;
                border-radius: 0 0 18px 18px;
            }
            .pagination { justify-content: center !important; gap: .35rem !important; }
            .page-link { min-width: 38px; min-height: 38px; padding: .45rem .62rem !important; }

            .modal-dialog { margin: .65rem !important; }
            .modal-content {
                max-height: calc(100vh - 1.3rem) !important;
                border-radius: 20px !important;
            }
            .modal-header {
                position: sticky;
                top: 0;
                z-index: 3;
                padding: .9rem !important;
            }
            .modal-title { font-size: .98rem !important; }
            .modal-title i { width: 38px !important; height: 38px !important; border-radius: 13px !important; }
            .modal-subtitle { font-size: .76rem !important; line-height: 1.4 !important; }
            .modal-body { padding: .85rem !important; max-height: calc(100vh - 150px) !important; }
            .modal-body .row.g-3 { padding: .8rem !important; border-radius: 16px !important; }
            .modal-footer {
                display: grid !important;
                grid-template-columns: 1fr !important;
                padding: .85rem !important;
            }
            .modal-footer .btn { width: 100% !important; }

            .image-preview,
            .modal .img-fluid.rounded,
            .photo-view-main img,
            .event-image-card img {
                max-height: 240px !important;
                object-fit: cover !important;
                border-radius: 16px !important;
            }
        }

        @media (max-width: 420px) {
            #announcementsTable tbody td:nth-child(8) > .d-flex {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 380px) {
            .announcements-hero-actions { grid-template-columns: 1fr !important; }
            .announcements-stats-row { grid-template-columns: 1fr !important; }
            #announcementsTable tbody tr {
                grid-template-columns: 28px minmax(0, 1fr);
                grid-template-areas:
                    "select status"
                    "title title"
                    "category category"
                    "featured featured"
                    "views views"
                    "published published"
                    "actions actions";
            }
        }

</style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar with Permission Checks -->
    <div class="sidebar" id="sidebar" onclick="handleSidebarClick(event)">
        <div class="brand">
            <div class="brand-left">
                <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo">
                <div class="brand-text">
                    <div class="brand-title">BISIG Hulong Duhat</div>
                    <div class="brand-sub">Administrator</div>
                </div>
            </div>
            <i class="fas fa-chevron-left toggle-btn" id="collapseBtn" title="Close sidebar" onclick="handleToggleButtonClick(event)"></i>
        </div>

        @admin_can('view_dashboard')
        <a href="{{ route('admin.dashboard.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        @endadmin_can

        <div class="menu-section">Administrative</div>
        
        <!-- Registry Dropdown -->
        @php
            $hasRegistryAccess = auth('admin')->user()->hasAnyPermission([
                'view_residents', 'view_residency', 'view_indigency'
            ]);
        @endphp
        
        @if($hasRegistryAccess)
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-users"></i>
            <span>Registry</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="registrySubmenu">
            @admin_can('view_residents')
            <a href="{{ route('admin.residents.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
            @endadmin_can
            
            @admin_can('view_residency')
            <a href="{{ route('admin.residency.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-alt"></i> <span>Residency Applications</span></a>
            @endadmin_can
            
            @admin_can('view_indigency')
            <a href="{{ route('admin.indigency.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-invoice"></i> <span>Indigency</span></a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Legal</div>
        
        <!-- Records Dropdown -->
        @php
            $hasRecordsAccess = auth('admin')->user()->hasAnyPermission([
                'view_clearance', 'view_blotter'
            ]);
        @endphp
        
        @if($hasRecordsAccess)
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="recordsSubmenu">
            @admin_can('view_clearance')
            <a href="{{ route('admin.clearance.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-contract"></i> <span>Clearances</span></a>
            @endadmin_can
            
            @admin_can('view_blotter')
            <a href="{{ route('admin.blotter.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-book"></i> <span>Incident Reports</span></a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Community</div>
        
        <!-- Community Dropdown -->
        @php
            $hasCommunityAccess = auth('admin')->user()->hasAnyPermission([
                'view_announcements', 'view_events', 'view_projects'
            ]);
        @endphp
        
        @if($hasCommunityAccess)
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="communitySubmenu">
            @admin_can('view_announcements')
            <a href="{{ route('admin.announcements.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            @endadmin_can
            
            @admin_can('view_events')
            <a href="{{ route('admin.events.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            @endadmin_can
            
            @admin_can('view_projects')
            <a href="{{ route('admin.projects.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">System</div>
        
        @admin_can('view_users')
        <a href="{{ route('admin.users.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-user"></i>
            <span>Users & Roles</span>
        </a>
        @endadmin_can

        @admin_can('manage_chatbot')
        <a href="{{ route('admin.chatbot.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
        @endadmin_can

        @admin_can('view_backup')
        <a href="{{ route('admin.backup.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-database"></i>
            <span>Backup Settings</span>
        </a>
        @endadmin_can

        @admin_can('view_settings')
        <a href="{{ route('admin.settings.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-cog"></i>
            <span>System Settings</span>
        </a>
        @endadmin_can

        <!-- Sidebar Account -->
        <div class="sidebar-account">
            <div class="sidebar-user-card">
                <div class="sidebar-user-icon">
                    <i class="fas fa-user-circle"></i>
                </div>

                <div class="sidebar-user-details">
                    <div class="sidebar-user-name">
                        {{ Auth::guard('admin')->user()->full_name }}
                        <div class="sidebar-user-role">
                            {{ Auth::guard('admin')->user()->getRoleDisplayName() }}
                        </div>
                    </div>

                    <div class="sidebar-user-email">
                        {{ Auth::guard('admin')->user()->email }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}" class="sidebar-logout-form">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-logout-text">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()" type="button" aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-bullhorn"></i></span>
                    Announcement Management
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="announcements-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif



            <section class="announcements-hero">
                <div class="announcements-hero-content">
                    <div class="min-w-0">
                        <div class="announcements-hero-icon"><i class="fas fa-bullhorn"></i></div>
                        <div class="announcements-kicker"><i class="fas fa-circle-check"></i> Announcement Center</div>
                        <h2>Publish barangay announcements with a cleaner workflow.</h2>
                        <p>Manage public advisories, featured posts, drafts, and archived records using the same compact design used in Projects.</p>
                    </div>
                    <div class="announcements-hero-actions">
                        @admin_can('create_announcements')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            <i class="fas fa-plus"></i>Add Announcement
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                        @admin_can('create_announcements')
                        <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#bulkNotifyResidentsModal">
                            <i class="fas fa-paper-plane"></i>Notify Residents
                        </button>
                        @endadmin_can
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 announcements-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-arrow-up me-1"></i>All time
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Published</div>
                            <div class="stat-number">{{ $published_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-globe me-1"></i>Live
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Drafts</div>
                            <div class="stat-number">{{ $draft_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-pen me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-pen"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Featured</div>
                            <div class="stat-number">{{ $featured_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-star me-1"></i>Important
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - UX Improved -->
            <div class="announcements-panel announcements-filter-panel">
                <form method="GET" action="{{ route('admin.announcements.index') }}" id="searchForm">
                    <div class="announcements-panel-header">
                        <div class="announcements-panel-title">
                            <div class="announcements-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-bullhorn"></i> Announcement Directory</div>
                                <h3>Search and filter announcements</h3>
                                <p class="panel-copy">Find announcements by title or content, then filter by status, category, or featured visibility.</p>
                            </div>
                        </div>

                        <div class="announcements-panel-actions">
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                <i class="fas fa-plus"></i>Add Announcement
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="announcements-filter-body">
                        <div class="announcements-filter-toolbar">
                            <div class="announcements-search-group">
                                <div class="announcements-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, content, or category..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search announcements">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted bin</option>
                            </select>

                            <select name="category" class="form-select" aria-label="Filter by category">
                                <option value="">All categories</option>
                                <option value="important" {{ request('category') == 'important' ? 'selected' : '' }}>Important</option>
                                <option value="events" {{ request('category') == 'events' ? 'selected' : '' }}>Events</option>
                                <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>Health</option>
                                <option value="services" {{ request('category') == 'services' ? 'selected' : '' }}>Services</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>

                            <select name="featured" class="form-select" aria-label="Filter by featured status">
                                <option value="">All featured</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured only</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not featured</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_announcements'))
                        <div class="announcements-bulk-row">
                            <div class="selected-count" id="selectedAnnouncementsCount">
                                <i class="fas fa-square-check me-1"></i>No announcements selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_announcements'))
            <form id="bulkForm" method="POST" action="{{ route('admin.announcements.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Announcements Table - UX Improved -->
            <div class="announcements-panel">
                <div class="announcements-panel-header">
                    <div class="announcements-panel-title">
                        <div class="announcements-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Announcement Records</div>
                            <h3>Announcements List</h3>
                            <p class="panel-copy">Sort records, view full details, edit drafts, and archive outdated announcements.</p>
                        </div>
                    </div>
                    <div class="announcements-panel-actions">
                        <span class="announcements-count-pill"><i class="fas fa-bullhorn"></i>{{ isset($announcements) ? $announcements->total() : 0 }} announcements</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="announcementsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Category
                                            @if(request('sort') == 'category')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_featured', 'direction' => request('sort') === 'is_featured' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Featured
                                            @if(request('sort') == 'is_featured')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'direction' => request('sort') === 'views' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Views
                                            @if(request('sort') == 'views')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'direction' => request('sort') === 'published_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Published At
                                            @if(request('sort') == 'published_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($announcements as $ann)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $ann->id }}" class="announcement-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="announcement-icon me-2">
                                                @if($ann->image)
                                                    <button type="button" class="photo-view-btn" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View announcement photo">
                                                        <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="zoomable-image">
                                                    </button>
                                                @else
                                                    <i class="fas fa-bullhorn text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($ann->title, 40) }}</div>
                                                <small class="text-muted d-lg-none">{{ \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($ann->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="fas fa-star me-1"></i>Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="views-badge">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        @if($ann->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($ann->status == 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @elseif($ann->status == 'draft')
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_announcements can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $ann->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_announcements permission) -->
                                            @if(!$ann->deleted_at)
                                            @admin_can('update_announcements')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal{{ $ann->id }}" title="Edit Announcement">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif

                                            <!-- Feature/Unfeature (requires feature_announcements permission) -->
                                            @if(!$ann->deleted_at && $ann->status == 'published' && auth('admin')->user()->hasPermission('feature_announcements'))
                                                <form method="POST" action="{{ route('admin.announcements.toggle-feature', $ann->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $ann->is_featured ? 'Remove Featured' : 'Mark as Featured' }}" onclick="return confirm('{{ $ann->is_featured ? 'Remove featured status?' : 'Mark as featured?' }}')">
                                                        <i class="fas fa-star{{ $ann->is_featured ? '' : '-o' }}"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Archive / Restore / Publish action -->
                                            @admin_can('delete_announcements')
                                            @if($ann->deleted_at)
                                            <form method="POST" action="{{ route('admin.announcements.restore', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this announcement from the deleted bin?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore from deleted bin" aria-label="Restore from deleted bin">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @elseif($ann->status == 'archived')
                                            <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Publish this archived announcement again?')">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="title" value="{{ $ann->title }}">
                                                <textarea name="content" class="d-none">{{ $ann->content }}</textarea>
                                                <input type="hidden" name="category" value="{{ $ann->category }}">
                                                <input type="hidden" name="status" value="published">
                                                <input type="hidden" name="published_at" value="{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}">
                                                @if($ann->is_featured)
                                                    <input type="hidden" name="is_featured" value="1">
                                                @endif
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Publish again" aria-label="Publish again">
                                                    <i class="fas fa-globe"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this announcement?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive" aria-label="Archive">
                                                    <i class="fas fa-box-archive"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div>
                                                <div class="empty-state-icon"><i class="fas fa-bullhorn"></i></div>
                                                <h4>No announcements found</h4>
                                                <p>No announcement records match the current search and filters.</p>
                                                @admin_can('create_announcements')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                                    <i class="fas fa-plus"></i>Add Announcement
                                                </button>
                                                @endadmin_can
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination - Mobile Responsive -->
                    @if(isset($announcements) && $announcements->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $announcements->firstItem() ?? 0 }}-{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $announcements->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Announcement Modal with Validation - Only if user has create_announcements permission -->
            @admin_can('create_announcements')
            <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-bullhorn"></i>
                                <span class="modal-title-stack">
                                    <span>New Announcement</span>
                                    <span class="modal-subtitle">Create a clear public advisory with category, status, publish date, featured setting, and optional image.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" id="addAnnouncementForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                        <small class="field-hint">Use a short title residents can understand quickly.</small>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('content') is-invalid @enderror @endif" 
                                                  name="content" rows="6" placeholder="Write your announcement content here..." required>{{ session('form_type') == 'add' ? old('content') : '' }}</textarea>
                                        <small class="field-hint">Keep the message direct. Include dates, requirements, or contact details when needed.</small>
                                        @if(session('form_type') == 'add')
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('category') is-invalid @enderror @endif" 
                                                name="category" required>
                                            <option value="">Select category</option>
                                            <option value="important" {{ (session('form_type') == 'add' && old('category') == 'important') ? 'selected' : '' }}>Important</option>
                                            <option value="events" {{ (session('form_type') == 'add' && old('category') == 'events') ? 'selected' : '' }}>Events</option>
                                            <option value="health" {{ (session('form_type') == 'add' && old('category') == 'health') ? 'selected' : '' }}>Health</option>
                                            <option value="services" {{ (session('form_type') == 'add' && old('category') == 'services') ? 'selected' : '' }}>Services</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('category') == 'other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" 
                                                name="status" required>
                                            <option value="">Select status</option>
                                            <option value="published" {{ (session('form_type') == 'add' && old('status') == 'published') ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ (session('form_type') == 'add' && old('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                            <option value="archived" {{ (session('form_type') == 'add' && old('status') == 'archived') ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Published Date</label>
                                        <input type="datetime-local" class="form-control @if(session('form_type') == 'add') @error('published_at') is-invalid @enderror @endif" 
                                               name="published_at" value="{{ session('form_type') == 'add' ? old('published_at') : '' }}">
                                        <small class="text-muted">Leave empty for immediate publishing</small>
                                        @if(session('form_type') == 'add')
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Featured</label>
                                        <div class="form-check mt-2 d-flex align-items-center gap-2">
                                            <input type="checkbox" class="form-check-input @if(session('form_type') == 'add') @error('is_featured') is-invalid @enderror @endif" 
                                                   name="is_featured" id="is_featured" value="1" {{ (session('form_type') == 'add' && old('is_featured')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Mark as featured announcement</label>
                                        </div>
                                        @if(session('form_type') == 'add')
                                            @error('is_featured')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Announcement Image</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Featured Image</label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif" 
                                               name="image" accept="image/*" onchange="previewImage(this, 'addImagePreview')">
                                        <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                        <div id="addImagePreview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addAnnouncementForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Announcement Modals with Validation - Only if user has update_announcements permission -->
            @if(auth('admin')->user()->hasPermission('update_announcements'))
                @foreach($announcements as $ann)
                <div class="modal fade" id="editAnnouncementModal{{ $ann->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Announcement</span>
                                        <span class="modal-subtitle">Update the content, visibility, schedule, and image without changing the existing workflow.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" enctype="multipart/form-data" id="editAnnouncementForm{{ $ann->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $ann->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $ann->id ? old('title', $ann->title) : $ann->title }}" required>
                                            <small class="field-hint">Use a clear, resident-friendly title.</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('content') is-invalid @enderror @endif" 
                                                      name="content" rows="6" required>{{ session('form_type') == 'edit_' . $ann->id ? old('content', $ann->content) : $ann->content }}</textarea>
                                            <small class="field-hint">Review the full message before saving changes.</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('content')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('category') is-invalid @enderror @endif" 
                                                    name="category" required>
                                                <option value="">Select category</option>
                                                <option value="important" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'important' ? 'selected' : '' }}>Important</option>
                                                <option value="events" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'events' ? 'selected' : '' }}>Events</option>
                                                <option value="health" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'health' ? 'selected' : '' }}>Health</option>
                                                <option value="services" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'services' ? 'selected' : '' }}>Services</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('status') is-invalid @enderror @endif" 
                                                    name="status" required>
                                                <option value="">Select status</option>
                                                <option value="published" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="archived" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Published Date</label>
                                            <input type="datetime-local" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('published_at') is-invalid @enderror @endif" 
                                                   name="published_at" value="{{ session('form_type') == 'edit_' . $ann->id ? old('published_at', $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') : ($ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') }}">
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('published_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Featured</label>
                                            <div class="form-check mt-2 d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input @if(session('form_type') == 'edit_' . $ann->id) @error('is_featured') is-invalid @enderror @endif" 
                                                       name="is_featured" id="edit_is_featured_{{ $ann->id }}" value="1" 
                                                       {{ (session('form_type') == 'edit_' . $ann->id ? old('is_featured', $ann->is_featured) : $ann->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edit_is_featured_{{ $ann->id }}">Mark as featured announcement</label>
                                            </div>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('is_featured')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Image -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Announcement Image</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Featured Image (Leave empty to keep current)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('image') is-invalid @enderror @endif" 
                                                   name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $ann->id }}')">
                                            <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="editImagePreview{{ $ann->id }}" class="mt-2">
                                                @if($ann->image)
                                                    <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View current photo">
                                                        <img src="{{ asset($ann->image) }}" alt="Current image" class="image-preview zoomable-image">
                                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                                                    </button>
                                                    <small class="d-block text-muted mt-2">Current image</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editAnnouncementForm{{ $ann->id }}" class="btn btn-primary" id="submitEditForm{{ $ann->id }}">
                                        <i class="fas fa-save me-2"></i>Update Announcement
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($announcements as $ann)
            <div class="modal fade" id="viewModal{{ $ann->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-bullhorn"></i>
                                <span class="modal-title-stack">
                                    <span>Announcement Details</span>
                                    <span class="modal-subtitle">Review the announcement content, metadata, featured status, views, and publish timeline.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                @if($ann->image)
                                <div class="col-12 text-center">
                                    <button type="button" class="photo-view-btn photo-view-main" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View full announcement photo">
                                        <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="modal-image-hero zoomable-image">
                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                                    </button>
                                </div>
                                @endif
                                
                                <div class="col-12">
                                    <h4 class="fw-bold">{{ $ann->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Category</label>
                                    <p><span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($ann->status == 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @elseif($ann->status == 'draft')
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Featured</label>
                                    <p>
                                        @if($ann->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">Featured</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Views</label>
                                    <p><span class="views-badge"><i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Published At</label>
                                    <p>{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('F d, Y h:i A') : 'Not published' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p>{{ $ann->created_at ? $ann->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Content</label>
                                    <div class="description-box">
                                        {!! nl2br(e($ann->content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @admin_can('create_announcements')
            <div class="modal fade" id="bulkNotifyResidentsModal" tabindex="-1" aria-labelledby="bulkNotifyResidentsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST"
                            action="{{ route('admin.announcements.bulkNotifyResidents') }}"
                            id="bulkNotifyResidentsForm"
                            onsubmit="return confirmBulkResidentNotification(event)">
                            @csrf
                            <input type="hidden" name="form_type" value="bulk_notify">

                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkNotifyResidentsModalLabel">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="modal-title-stack">
                                        <span>Bulk Notify Residents</span>
                                        <span class="modal-subtitle">
                                            Send one clear SMS or email blast to residents for ayuda, emergency advisories, schedules, and barangay reminders.
                                        </span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="notification-summary-grid mb-3">
                                    <div class="notification-summary-card">
                                        <span>Total residents</span>
                                        <strong>{{ number_format($residentNotificationStats['all'] ?? 0) }}</strong>
                                    </div>
                                    <div class="notification-summary-card">
                                        <span>With email</span>
                                        <strong>{{ number_format($residentNotificationStats['email'] ?? 0) }}</strong>
                                    </div>
                                    <div class="notification-summary-card">
                                        <span>With mobile</span>
                                        <strong>{{ number_format($residentNotificationStats['sms'] ?? 0) }}</strong>
                                    </div>
                                    <div class="notification-summary-card">
                                        <span>Verified</span>
                                        <strong>{{ number_format($residentNotificationStats['verified'] ?? 0) }}</strong>
                                    </div>
                                </div>

                                <div class="sms-credit-box mb-3"
                                    id="smsCreditBox"
                                    data-credit-balance="{{ $semaphoreAccount['credit_balance'] ?? 0 }}">
                                    <div class="sms-credit-main">
                                        <div>
                                            <span class="sms-credit-label">Semaphore SMS Credits</span>
                                            <strong id="smsAvailableCredits">
                                                {{ number_format((float) ($semaphoreAccount['credit_balance'] ?? 0), 2) }}
                                            </strong>
                                            <small>
                                                {{ $semaphoreAccount['success'] ?? false
                                                    ? 'Live balance from Semaphore account.'
                                                    : ($semaphoreAccount['message'] ?? 'Unable to load Semaphore balance.') }}
                                            </small>
                                        </div>

                                        <div class="sms-credit-status" id="smsCreditStatusBadge">
                                            Checking estimate
                                        </div>
                                    </div>

                                    <div class="sms-credit-details">
                                        <span>SMS recipients: <strong id="smsEstimatedRecipients">0</strong></span>
                                        <span>SMS segments: <strong id="smsEstimatedSegments">0</strong></span>
                                        <span>Credits needed: <strong id="smsEstimatedCredits">0</strong></span>
                                        <span>After send: <strong id="smsRemainingCredits">0</strong></span>
                                    </div>

                                    <input type="hidden" name="estimated_sms_credits" id="estimatedSmsCreditsInput" value="0">
                                </div>

                                <div class="row g-3 modal-form-grid">
                                    <div class="col-12">
                                        <div class="modal-section-title">
                                            <i class="fas fa-sliders"></i>Notification setup
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Send through <span class="text-danger">*</span></label>
                                        <select name="channel" id="notifyChannel" class="form-select" required>
                                            <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS only</option>
                                            <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email only</option>
                                            <option value="both" {{ old('channel') === 'both' ? 'selected' : '' }}>SMS and Email</option>
                                        </select>
                                        <small class="field-hint">Choose SMS for urgent notices. Use email for longer details.</small>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Audience <span class="text-danger">*</span></label>
                                        <select name="audience" id="notifyAudience" class="form-select" required>
                                            <option value="all" {{ old('audience') === 'all' ? 'selected' : '' }}>All residents</option>
                                            <option value="selected" {{ old('audience') === 'selected' ? 'selected' : '' }}>Selected residents only</option>
                                            <option value="verified" {{ old('audience') === 'verified' ? 'selected' : '' }}>Verified residents</option>
                                            <option value="unverified" {{ old('audience') === 'unverified' ? 'selected' : '' }}>Unverified residents</option>
                                            <option value="senior" {{ old('audience') === 'senior' ? 'selected' : '' }}>Senior citizens</option>
                                            <option value="pwd" {{ old('audience') === 'pwd' ? 'selected' : '' }}>PWD residents</option>
                                            <option value="student" {{ old('audience') === 'student' ? 'selected' : '' }}>Student residents</option>
                                            <option value="male" {{ old('audience') === 'male' ? 'selected' : '' }}>Male residents</option>
                                            <option value="female" {{ old('audience') === 'female' ? 'selected' : '' }}>Female residents</option>
                                            <option value="with_valid_id" {{ old('audience') === 'with_valid_id' ? 'selected' : '' }}>Residents with valid ID</option>
                                            <option value="email_ready" {{ old('audience') === 'email_ready' ? 'selected' : '' }}>Residents with email</option>
                                            <option value="sms_ready" {{ old('audience') === 'sms_ready' ? 'selected' : '' }}>Residents with mobile number</option>
                                        </select>
                                        <small class="field-hint">The system automatically skips missing email/mobile contacts depending on the channel.</small>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Template</label>
                                        <select id="notifyTemplate" class="form-select">
                                            <option value="">Write custom message</option>
                                            <option value="ayuda">Ayuda Distribution</option>
                                            <option value="emergency">Emergency Advisory</option>
                                            <option value="documents">Document Release Reminder</option>
                                            <option value="event">Barangay Event</option>
                                        </select>
                                        <small class="field-hint">Templates are editable after selection.</small>
                                    </div>

                                    <div class="col-12" id="selectedResidentsBox" style="display: none;">
                                        <label class="form-label">Choose residents <span class="text-danger">*</span></label>

                                        <input type="text"
                                            id="residentPickerSearch"
                                            class="form-control mb-2"
                                            placeholder="Search resident name, email, or mobile number...">

                                        <div class="resident-picker-list">
                                            @forelse($residentsForNotification ?? [] as $resident)
                                                @php
                                                    $residentFullName = trim(collect([
                                                        $resident->firstname ?? '',
                                                        $resident->middlename ?? '',
                                                        $resident->lastname ?? '',
                                                        $resident->suffix ?? '',
                                                    ])->filter()->implode(' '));

                                                    $searchText = strtolower($residentFullName . ' ' . ($resident->email ?? '') . ' ' . ($resident->contact ?? ''));
                                                @endphp

                                                <label class="resident-picker-item" data-search="{{ $searchText }}">
                                                    <input type="checkbox"
                                                        name="resident_ids[]"
                                                        value="{{ $resident->id }}"
                                                        {{ in_array($resident->id, old('resident_ids', [])) ? 'checked' : '' }}>
                                                    <span>
                                                        <strong>{{ $residentFullName ?: 'Resident #' . $resident->id }}</strong>
                                                        <small>
                                                            {{ $resident->contact ?: 'No mobile' }} · {{ $resident->email ?: 'No email' }}
                                                        </small>
                                                    </span>
                                                </label>
                                            @empty
                                                <div class="notification-warning-box">
                                                    <i class="fas fa-circle-info"></i>
                                                    <div>No residents found for manual selection.</div>
                                                </div>
                                            @endforelse
                                        </div>

                                        <small class="field-hint" id="selectedResidentCounter">0 residents selected</small>

                                        @if(session('form_type') === 'bulk_notify')
                                            @error('resident_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Email subject</label>
                                        <input type="text"
                                            name="subject"
                                            id="notifySubject"
                                            class="form-control @if(session('form_type') === 'bulk_notify') @error('subject') is-invalid @enderror @endif"
                                            value="{{ session('form_type') === 'bulk_notify' ? old('subject') : '' }}"
                                            placeholder="Example: Ayuda Distribution Notice">
                                        <small class="field-hint">Required only when sending email or both SMS and email.</small>
                                        @if(session('form_type') === 'bulk_notify')
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Message <span class="text-danger">*</span></label>
                                        <textarea name="message"
                                                id="notifyMessage"
                                                rows="7"
                                                class="form-control @if(session('form_type') === 'bulk_notify') @error('message') is-invalid @enderror @endif"
                                                required
                                                maxlength="1000"
                                                placeholder="Write a clear message for residents...">{{ session('form_type') === 'bulk_notify' ? old('message') : '' }}</textarea>

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-2">
                                            <small class="field-hint mb-0">
                                                Available placeholders: <strong>{name}</strong>, <strong>{firstname}</strong>, <strong>{barangay}</strong>
                                            </small>
                                            <small class="field-hint mb-0" id="notifyCharCounter">0 / 1000 characters</small>
                                        </div>

                                        @if(session('form_type') === 'bulk_notify')
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <div class="notification-preview-box">
                                            <div class="notification-preview-header">
                                                <span><i class="fas fa-eye"></i> Resident preview</span>
                                                <span id="notifyPreviewChannel">SMS</span>
                                            </div>
                                            <p id="notifyPreviewText">
                                                Your message preview will appear here.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="notification-warning-box">
                                            <i class="fas fa-circle-info"></i>
                                            <div>
                                                <strong>Review before sending.</strong>
                                                Bulk notifications are sent immediately and each result will be saved in notification logs.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">
                                    <i class="fas fa-xmark"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>Send Notification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Global Image Zoom Modal -->
            <div class="modal fade image-zoom-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-image"></i>
                                <span class="modal-title-stack">
                                    <span>Announcement Photo</span>
                                    <span class="modal-subtitle">View the uploaded image in a larger preview.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="zoomed-image" alt="Announcement photo preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addAnnouncementModal'));
                    addModal.show();
                });
            </script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk Archive function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.announcement-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');
            const idsContainer = document.getElementById('bulkIdsContainer');

            if (!bulkForm || !idsContainer) {
                alert('Bulk Archive form is unavailable. Please refresh the page.');
                return;
            }

            if (checkboxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one announcement to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one announcement to archive.');
                }
                return;
            }

            // Clear old ids so repeated operations do not duplicate inputs.
            idsContainer.innerHTML = '';
            checkboxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                idsContainer.appendChild(input);
            });

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirm Bulk Archive',
                    text: `Are you sure you want to archive ${checkboxes.length} selected announcement(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Archive'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkForm.submit();
                    }
                });
            } else {
                if (confirm(`Are you sure you want to archive ${checkboxes.length} announcement(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof refreshAnnouncementSelection === 'function') {
                refreshAnnouncementSelection();
            }
        }

        // Confirm delete with SweetAlert
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message || 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(message || 'Are you sure?')) {
                    form.submit();
                }
            }
            
            return false;
        }

        // Preview image before upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('${e.target.result}')" title="View selected photo">
                            <img src="${e.target.result}" class="announcement-image-preview zoomable-image" alt="Selected photo preview">
                            <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                        </button>`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                });
            }

            // Add loading animation to filter form
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                        submitBtn.disabled = true;
                    }
                });
            }

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            const zoomModalEl = document.getElementById('globalImageZoomModal');

            if (!zoomImage || !zoomModalEl || !imageUrl) {
                return;
            }

            zoomImage.src = imageUrl;
            const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalEl);
            zoomModal.show();
        }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.insertAdjacentElement('afterend', fb); }
            fb.textContent = msg;
            fb.style.display = 'block';
        }
        function cf(el) {
            if (!el) return;
            el.classList.remove('is-invalid');
            const fb = el.parentElement.querySelector('.invalid-feedback');
            if (fb) {
                fb.textContent = '';
                fb.style.display = 'none';
            }
        }

        function attachAnnouncementValidation(form) {
            if (!form) return;
            const title    = form.querySelector('[name="title"]');
            const content  = form.querySelector('[name="content"]');
            const category = form.querySelector('[name="category"]');
            const status   = form.querySelector('[name="status"]');
            const image    = form.querySelector('[name="image"]');

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (content) {
                content.addEventListener('input', function() { cf(this); }, {once: true});
                content.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Content is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Content must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (category) category.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a category.'); else cf(this);
            });
            if (status) status.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a status.'); else cf(this);
            });
            if (image) image.addEventListener('change', function() {
                if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                    sf(this, 'Image must be less than 5MB.'); this.value = '';
                } else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                }
                if (content) {
                    if (!content.value.trim()) { sf(content, 'Content is required.'); valid = false; }
                    else if (content.value.trim().length < 10) { sf(content, 'Content must be at least 10 characters.'); valid = false; }
                }
                if (category && !category.value) { sf(category, 'Please select a category.'); valid = false; }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); valid = false;
                }
                if (!valid) e.preventDefault();
            });
        }

        attachAnnouncementValidation(document.getElementById('addAnnouncementForm'));
        document.querySelectorAll('[id^="editAnnouncementForm"]').forEach(attachAnnouncementValidation);
    });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <script>
        function refreshAnnouncementSelection() {
            const checkboxes = Array.from(document.querySelectorAll('.announcement-checkbox'));
            const checked = checkboxes.filter(cb => cb.checked);
            const selectAll = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedAnnouncementsCount');

            if (selectAll) {
                selectAll.checked = checked.length > 0 && checked.length === checkboxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            if (selectedCount) {
                selectedCount.innerHTML = checked.length > 0
                    ? `<i class="fas fa-square-check me-1"></i>${checked.length} announcement${checked.length === 1 ? '' : 's'} selected`
                    : '<i class="fas fa-square-check me-1"></i>No announcements selected';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.announcement-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', refreshAnnouncementSelection);
            });
            refreshAnnouncementSelection();
        });
    </script>
    @if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bulkNotifyModal = new bootstrap.Modal(document.getElementById('bulkNotifyResidentsModal'));
        bulkNotifyModal.show();
    });
</script>
@endif

@if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('bulkNotifyResidentsModal');
    if (modalEl) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
});
</script>
@endif

<script>
window.smsAudienceCounts = @json($smsAudienceCounts ?? []);
window.semaphoreCreditBalance = Number(@json($semaphoreAccount['credit_balance'] ?? 0));

document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('notifyTemplate');
    const subjectInput = document.getElementById('notifySubject');
    const messageInput = document.getElementById('notifyMessage');
    const channelSelect = document.getElementById('notifyChannel');
    const audienceSelect = document.getElementById('notifyAudience');
    const counter = document.getElementById('notifyCharCounter');
    const previewText = document.getElementById('notifyPreviewText');
    const previewChannel = document.getElementById('notifyPreviewChannel');
    const selectedResidentsBox = document.getElementById('selectedResidentsBox');
    const residentPickerSearch = document.getElementById('residentPickerSearch');
    const selectedResidentCounter = document.getElementById('selectedResidentCounter');

    const smsEstimatedRecipients = document.getElementById('smsEstimatedRecipients');
    const smsEstimatedSegments = document.getElementById('smsEstimatedSegments');
    const smsEstimatedCredits = document.getElementById('smsEstimatedCredits');
    const smsRemainingCredits = document.getElementById('smsRemainingCredits');
    const smsCreditStatusBadge = document.getElementById('smsCreditStatusBadge');
    const estimatedSmsCreditsInput = document.getElementById('estimatedSmsCreditsInput');

    const templates = {
        ayuda: {
            subject: 'Ayuda Distribution Notice',
            message: 'Magandang araw, {name}. May ayuda distribution po ang Barangay Hulong Duhat sa [DATE], [TIME], sa [VENUE]. Magdala po ng valid ID at requirements. Salamat po.'
        },
        emergency: {
            subject: 'Emergency Barangay Advisory',
            message: 'Magandang araw, {name}. Paalala mula sa Barangay Hulong Duhat: [WRITE EMERGENCY DETAILS HERE]. Para sa agarang tulong, makipag-ugnayan sa barangay office. Ingat po.'
        },
        documents: {
            subject: 'Barangay Document Release Reminder',
            message: 'Magandang araw, {name}. Ang inyong barangay document/request ay maaari nang i-follow up o kunin sa barangay office. Magdala po ng valid ID at reference number kung meron. Salamat po.'
        },
        event: {
            subject: 'Barangay Event Announcement',
            message: 'Magandang araw, {name}. Inaanyayahan po kayo sa barangay event: [EVENT NAME] sa [DATE], [TIME], sa [VENUE]. Kita-kits po.'
        }
    };

    function smsLength(message) {
        return Array.from(message || '').length;
    }

    function smsSegments(message) {
        const length = smsLength(message);
        return Math.max(1, Math.ceil(length / 160));
    }

    function selectedResidentCount() {
        return document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    }

    function estimatedSmsRecipients() {
        const channel = channelSelect?.value || 'sms';

        if (channel === 'email') {
            return 0;
        }

        const audience = audienceSelect?.value || 'all';

        if (audience === 'selected') {
            return selectedResidentCount();
        }

        return Number(window.smsAudienceCounts?.[audience] || 0);
    }

    function refreshSelectedResidentBox() {
        if (!audienceSelect || !selectedResidentsBox) return;

        selectedResidentsBox.style.display = audienceSelect.value === 'selected' ? 'block' : 'none';
        refreshSelectedResidentCounter();
        refreshSmsCreditEstimate();
    }

    function refreshSelectedResidentCounter() {
        if (!selectedResidentCounter) return;

        const selected = selectedResidentCount();
        selectedResidentCounter.textContent = `${selected} resident${selected === 1 ? '' : 's'} selected`;
    }

    function refreshSmsCreditEstimate() {
        const message = messageInput?.value || '';
        const channel = channelSelect?.value || 'sms';
        const recipients = estimatedSmsRecipients();
        const segments = channel === 'email' ? 0 : smsSegments(message);
        const neededCredits = channel === 'email' ? 0 : recipients * segments * 2;
        const availableCredits = Number(window.semaphoreCreditBalance || 0);
        const remainingCredits = availableCredits - neededCredits;

        if (smsEstimatedRecipients) smsEstimatedRecipients.textContent = recipients.toLocaleString();
        if (smsEstimatedSegments) smsEstimatedSegments.textContent = segments.toLocaleString();
        if (smsEstimatedCredits) smsEstimatedCredits.textContent = neededCredits.toLocaleString();
        if (smsRemainingCredits) smsRemainingCredits.textContent = remainingCredits.toLocaleString();
        if (estimatedSmsCreditsInput) estimatedSmsCreditsInput.value = neededCredits;

        if (smsCreditStatusBadge) {
            smsCreditStatusBadge.classList.remove('is-ok', 'is-danger');

            if (channel === 'email') {
                smsCreditStatusBadge.textContent = 'Email only';
                smsCreditStatusBadge.classList.add('is-ok');
            } else if (recipients > 1000) {
                smsCreditStatusBadge.textContent = 'Over 1,000 SMS limit';
                smsCreditStatusBadge.classList.add('is-danger');
            } else if (neededCredits <= availableCredits) {
                smsCreditStatusBadge.textContent = 'Enough credits';
                smsCreditStatusBadge.classList.add('is-ok');
            } else {
                smsCreditStatusBadge.textContent = 'Insufficient credits';
                smsCreditStatusBadge.classList.add('is-danger');
            }
        }
    }

    function updatePreview() {
        if (!messageInput || !previewText) return;

        const message = messageInput.value.trim();
        const channel = channelSelect?.value || 'sms';

        if (counter) {
            const length = smsLength(messageInput.value);
            const segments = smsSegments(messageInput.value);
            counter.textContent = `${length} / 1000 characters · ${segments} SMS segment${segments === 1 ? '' : 's'}`;
        }

        if (previewChannel) {
            previewChannel.textContent = channel === 'both'
                ? 'SMS + Email'
                : channel.toUpperCase();
        }

        previewText.textContent = message
            ? message
                .replaceAll('{name}', 'Juan Dela Cruz')
                .replaceAll('{firstname}', 'Juan')
                .replaceAll('{lastname}', 'Dela Cruz')
                .replaceAll('{barangay}', 'Hulong Duhat')
            : 'Your message preview will appear here.';

        refreshSmsCreditEstimate();
    }

    templateSelect?.addEventListener('change', function () {
        const selected = templates[this.value];

        if (!selected) return;

        if (subjectInput) subjectInput.value = selected.subject;
        if (messageInput) messageInput.value = selected.message;

        updatePreview();
    });

    residentPickerSearch?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        document.querySelectorAll('.resident-picker-item').forEach(function (item) {
            const haystack = item.dataset.search || '';
            item.style.display = haystack.includes(keyword) ? 'flex' : 'none';
        });
    });

    document.querySelectorAll('input[name="resident_ids[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            refreshSelectedResidentCounter();
            refreshSmsCreditEstimate();
        });
    });

    messageInput?.addEventListener('input', updatePreview);
    channelSelect?.addEventListener('change', updatePreview);
    audienceSelect?.addEventListener('change', refreshSelectedResidentBox);

    refreshSelectedResidentBox();
    updatePreview();
});

function confirmBulkResidentNotification(event) {
    event.preventDefault();

    const form = event.target;
    const channel = document.getElementById('notifyChannel')?.value || 'sms';
    const audienceSelect = document.getElementById('notifyAudience');
    const audience = audienceSelect?.selectedOptions[0]?.textContent || 'selected audience';
    const audienceValue = audienceSelect?.value || 'all';
    const message = document.getElementById('notifyMessage')?.value.trim() || '';
    const selectedResidents = document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    const neededCredits = Number(document.getElementById('estimatedSmsCreditsInput')?.value || 0);
    const availableCredits = Number(window.semaphoreCreditBalance || 0);
    const estimatedRecipients = Number(document.getElementById('smsEstimatedRecipients')?.textContent.replaceAll(',', '') || 0);

    if (!message) {
        alert('Please write a message before sending.');
        return false;
    }

    if (audienceValue === 'selected' && selectedResidents === 0) {
        alert('Please select at least one resident.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && estimatedRecipients > 1000) {
        alert('SMS bulk notification is limited to 1,000 mobile numbers only.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && neededCredits > availableCredits) {
        alert(`Not enough SMS credits. Needed: ${neededCredits}, Available: ${availableCredits}`);
        return false;
    }

    const extraText = audienceValue === 'selected'
        ? `${selectedResidents} selected resident${selectedResidents === 1 ? '' : 's'}`
        : audience;

    const confirmText = `Send this ${channel.toUpperCase()} notification to ${extraText}? Estimated SMS credits: ${neededCredits}.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send bulk notification?',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#667085',
            confirmButtonText: 'Yes, send now'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    if (confirm(confirmText)) {
        form.submit();
    }

    return false;
}
</script>

</body>
</html>


