<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Projects - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
            --secondary: #6c757d;
            --secondary-light: #f8f9fa;
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
            content: '⚠️';
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
            min-width: 980px;
            font-size: 0.86rem;
        }

        @media (max-width: 1400px) {
            .table {
                min-width: 900px;
            }
        }

        @media (max-width: 768px) {
            .table {
                min-width: 820px;
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
            background: var(--secondary-light) !important;
            color: var(--secondary);
        }

        /* Progress Bar Styling */
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            height: 24px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--primary-gradient);
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 24px;
            transition: width 0.3s ease;
        }

        .progress-bar.bg-success {
            background: linear-gradient(135deg, #2e7d32 0%, #1e5e23 100%);
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

        .btn-outline-info {
            color: var(--info);
            border-color: var(--info);
        }

        .btn-outline-info:hover {
            background: var(--info);
            border-color: var(--info);
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

        /* Location Badge */
        .location-badge {
            background: var(--info-light);
            color: var(--info);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Date Badge */
        .date-badge {
            background: var(--secondary-light);
            color: var(--secondary);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
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

        html {
            font-size: 70%;
            scroll-behavior: smooth;
        }

        body {
            background: var(--page-bg);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .min-w-0 { min-width: 0 !important; }

        .projects-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .projects-alert,
        .projects-page > .alert,
        main > .alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .projects-alert.fade:not(.show),
        .projects-page > .alert.fade:not(.show),
        main > .alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .projects-hero,
        .projects-panel,
        .stat-card,
        .card.border-0 {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .projects-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            padding: clamp(1.1rem, 1.8vw, 1.5rem);
        }

        .projects-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(135deg, rgba(211, 47, 47, .04), transparent 42%);
            pointer-events: none;
        }

        .projects-hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
        }

        .projects-hero-icon,
        .projects-panel-icon,
        .stat-icon,
        .project-icon,
        .empty-state-icon,
        .modal-title-icon,
        .detail-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .projects-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .14);
            border-radius: 14px;
            font-size: 1.12rem;
        }

        .projects-kicker,
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

        .projects-hero h2 {
            margin: .48rem 0 .42rem;
            color: var(--text);
            font-size: clamp(1.65rem, 2.6vw, 2.25rem);
            font-weight: 850;
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .projects-hero p,
        .panel-copy,
        .empty-state p,
        .modal-subtitle,
        .field-hint {
            color: var(--muted);
            line-height: 1.6;
        }

        .projects-hero p {
            max-width: 720px;
            margin: 0;
            font-size: .93rem;
        }

        .projects-hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .65rem;
        }

        .projects-stats-row {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-right: 0 !important;
            margin-left: 0 !important;
            margin-bottom: 1rem !important;
        }

        .projects-stats-row > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
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
        .projects-panel:hover,
        .card.border-0:hover {
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stat-card:hover { transform: translateY(-1px) !important; }

        .stat-card > div:first-child {
            min-width: 0;
            order: 2;
        }

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

        .projects-panel { overflow: hidden; }

        .projects-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .projects-panel-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .projects-panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .projects-panel-header h3 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .panel-copy {
            margin: 0;
            font-size: .86rem;
        }

        .projects-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .projects-count-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            width: fit-content;
            padding: .42rem .68rem;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .projects-filter-body { padding: 1rem; }

        .projects-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(300px, 1.45fr) minmax(160px, .7fr) minmax(175px, .75fr) auto auto;
            gap: .65rem;
            align-items: center;
        }

        .projects-search-field {
            position: relative;
            min-width: 0;
        }

        .projects-search-field i {
            position: absolute;
            top: 50%;
            left: .9rem;
            transform: translateY(-50%);
            color: var(--muted-2);
            pointer-events: none;
        }

        .projects-search-field .form-control { padding-left: 2.4rem !important; }

        .projects-filter-panel {
            margin-bottom: .75rem !important;
        }

        .projects-filter-panel .projects-panel-header {
            padding-bottom: .85rem;
        }

        .projects-filter-panel .projects-filter-body {
            padding-top: .85rem;
            padding-bottom: .85rem;
        }

        .projects-filter-toolbar {
            grid-template-columns: minmax(360px, 1.55fr) minmax(155px, .65fr) minmax(170px, .72fr) auto;
            gap: .55rem;
            align-items: stretch;
        }

        .projects-search-group {
            display: flex;
            align-items: stretch;
            min-width: 0;
        }

        .projects-search-group .projects-search-field {
            flex: 1 1 auto;
        }

        .projects-search-group .form-control {
            height: 100%;
            border-right: 0;
            border-radius: 12px 0 0 12px;
        }

        .projects-search-group .search-submit-btn {
            flex: 0 0 auto;
            min-width: 104px;
            border-radius: 0 12px 12px 0;
            box-shadow: none;
            white-space: nowrap;
        }

        .filter-submit-btn {
            white-space: nowrap;
        }

        .projects-bulk-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            margin-top: .85rem;
            padding-top: .85rem;
            border-top: 1px dashed var(--line-strong);
        }

        .selected-count {
            color: var(--muted);
            font-size: .82rem;
            font-weight: 700;
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
        .form-range:focus,
        input[type="checkbox"]:focus-visible {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none !important;
        }

        .btn-primary,
        .btn-danger {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-danger:hover,
        .btn-danger:focus {
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
            min-height: 128px;
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

        .table {
            min-width: 980px;
            margin-bottom: 0;
        }

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

        .project-icon {
            flex: 0 0 auto;
            width: 40px !important;
            height: 40px !important;
            color: var(--primary);
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px !important;
        }

        .location-badge,
        .date-badge,
        .badge {
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

        .location-badge {
            color: var(--info);
            background: var(--info-light);
            border: 1px solid #dbeafe;
        }

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

        .progress {
            height: 12px !important;
            background: #f2f4f7;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--primary) !important;
            border-radius: 999px;
            font-size: 0;
        }

        .progress-bar.bg-success { background: var(--success) !important; }

        .progress-cell {
            min-width: 175px;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .modal-section {
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
        }

        .modal-section + .modal-section { margin-top: .85rem; }

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

        .project-detail-grid {
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

        .form-range { accent-color: var(--primary); }

        .range-card {
            padding: 1rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
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
            .projects-stats-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .projects-filter-toolbar { grid-template-columns: minmax(0, 1fr) minmax(160px, .7fr) minmax(175px, .75fr); }
            .projects-filter-toolbar .filter-submit-btn { width: 100%; }
        }

        @media (max-width: 991px) {
            .projects-hero-content,
            .projects-panel-header { grid-template-columns: minmax(0, 1fr); }
            .projects-hero-content { display: block; }
            .projects-hero-actions,
            .projects-panel-actions,
            .projects-panel-actions .btn { width: 100%; justify-content: stretch; }
            .projects-hero-actions .btn { flex: 1 1 180px; }
            .projects-filter-toolbar { grid-template-columns: minmax(0, 1fr); }
            .projects-search-group { width: 100%; }
            .projects-search-group .search-submit-btn { min-width: 96px; }
            .projects-filter-toolbar .filter-submit-btn { width: 100%; }
            .projects-bulk-row { flex-direction: column; align-items: stretch; }
            .table { min-width: 860px; }
        }

        @media (max-width: 768px) {
            .projects-page { max-width: 100%; }
            .projects-hero,
            .projects-panel,
            .stat-card,
            .card.border-0 { border-radius: 16px !important; }
            .projects-hero,
            .projects-panel-header,
            .projects-filter-body,
            .modal-header,
            .modal-body,
            .modal-footer { padding: .9rem !important; }
            .projects-hero-actions,
            .projects-hero-actions .btn { width: 100%; }
            .projects-stats-row { grid-template-columns: minmax(0, 1fr); }
            .project-detail-grid { grid-template-columns: minmax(0, 1fr); }
            .modal-footer .btn { width: 100%; }
        }

        @media (max-width: 575px) {
            main.p-3,
            main.p-lg-4 { padding: .85rem !important; }
            .projects-hero h2 { font-size: 1.7rem; }
        }


        .modal-form-grid {
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
        }

    

        /* ==========================================================
           FINAL MOBILE POLISH V2 - Projects
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

            .projects-page { max-width: 100% !important; }

            .projects-hero,
            .projects-panel,
            .stat-card,
            .card.border-0 {
                border-radius: 18px !important;
            }

            .projects-hero { padding: 1rem !important; margin-bottom: .75rem !important; }
            .projects-hero-content { display: block !important; }
            .projects-hero-icon { width: 42px !important; height: 42px !important; margin-bottom: .65rem !important; }
            .projects-hero h2 { font-size: 1.45rem !important; line-height: 1.12 !important; letter-spacing: -.04em !important; }
            .projects-hero p { font-size: .88rem !important; line-height: 1.55 !important; }
            .projects-hero-actions { display: grid !important; grid-template-columns: 1fr 1fr; gap: .55rem !important; margin-top: .9rem !important; }
            .projects-hero-actions .btn { width: 100% !important; min-height: 42px !important; padding: .62rem .65rem !important; }

            .projects-stats-row {
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

            .projects-panel-header {
                display: block !important;
                padding: .9rem !important;
            }

            .projects-panel-title { gap: .7rem !important; }
            .projects-panel-icon { width: 40px !important; height: 40px !important; border-radius: 13px !important; }
            .projects-panel-header h3 { font-size: .98rem !important; }
            .panel-copy { font-size: .82rem !important; margin-top: .12rem !important; }
            .projects-panel-actions { justify-content: flex-start !important; margin-top: .75rem !important; width: 100% !important; }
            .projects-panel-actions .btn,
            .projects-panel-actions .projects-count-pill { width: 100% !important; justify-content: center !important; }

            .projects-filter-body { padding: .85rem !important; }
            .projects-filter-toolbar { display: grid !important; grid-template-columns: 1fr !important; gap: .6rem !important; }
            .projects-search-group { display: grid !important; grid-template-columns: 1fr !important; gap: .55rem !important; width: 100% !important; }
            .projects-search-group .form-control,
            .projects-search-group .search-submit-btn,
            .projects-search-field .form-control,
            .projects-filter-toolbar .form-select,
            .filter-submit-btn {
                width: 100% !important;
                min-height: 44px !important;
                border-radius: 13px !important;
                border: 1px solid var(--line-strong) !important;
            }
            .projects-search-group .form-control { border-right: 1px solid var(--line-strong) !important; }
            .projects-search-group .search-submit-btn { min-width: 0 !important; }

            .projects-bulk-row {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .6rem !important;
                margin-top: .7rem !important;
                padding-top: .7rem !important;
            }
            .projects-bulk-row .selected-count,
            .projects-bulk-row .btn { width: 100% !important; justify-content: center !important; }

            /* Real mobile cards for the Projects table */
            .projects-panel .card-body { background: #f8fafc !important; }
            #projectsTable {
                display: block !important;
                width: 100% !important;
                min-width: 0 !important;
                border-collapse: separate !important;
            }
            #projectsTable thead { display: none !important; }
            #projectsTable tbody {
                display: grid !important;
                gap: .8rem !important;
                padding: .85rem !important;
                background: #f8fafc !important;
            }
            #projectsTable tbody tr {
                display: grid !important;
                grid-template-columns: 32px minmax(0, 1fr) auto;
                grid-template-areas:
                    "select title status"
                    "progress progress progress"
                    "location location location"
                    "start start start"
                    "end end end"
                    "actions actions actions";
                gap: .62rem .65rem;
                align-items: start;
                padding: .9rem !important;
                background: #ffffff !important;
                border: 1px solid var(--line) !important;
                border-radius: 18px !important;
                box-shadow: 0 8px 20px rgba(16,24,40,.045) !important;
            }
            #projectsTable tbody tr:hover { background: #ffffff !important; transform: none !important; }
            #projectsTable tbody td {
                display: block !important;
                width: auto !important;
                min-width: 0 !important;
                padding: 0 !important;
                border: 0 !important;
                text-align: left !important;
                white-space: normal !important;
            }
            #projectsTable tbody td[colspan] { grid-column: 1 / -1 !important; }

            #projectsTable tbody td:nth-child(1) { grid-area: select; padding-top: .28rem !important; }
            #projectsTable tbody td:nth-child(1) input { width: 22px !important; height: 22px !important; }

            #projectsTable tbody td:nth-child(2) { grid-area: title; }
            #projectsTable tbody td:nth-child(2) .d-flex { align-items: flex-start !important; gap: .65rem !important; }
            #projectsTable tbody td:nth-child(2) .project-icon { width: 42px !important; height: 42px !important; border-radius: 14px !important; margin-right: 0 !important; }
            #projectsTable tbody td:nth-child(2) .fw-semibold {
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
            #projectsTable tbody td:nth-child(2) small { display: none !important; }

            #projectsTable tbody td:nth-child(6) {
                grid-area: status;
                display: flex !important;
                justify-content: flex-end !important;
                align-self: start;
            }
            #projectsTable tbody td:nth-child(6) .badge { padding: .42rem .62rem !important; font-size: .72rem !important; }

            #projectsTable tbody td:nth-child(7) {
                grid-area: progress;
                padding: .75rem !important;
                background: #f9fafb;
                border: 1px solid var(--line) !important;
                border-radius: 14px;
            }
            #projectsTable tbody td:nth-child(7)::before {
                content: "Progress";
                display: block;
                margin-bottom: .5rem;
                color: var(--muted);
                font-size: .68rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .08em;
            }
            #projectsTable tbody td:nth-child(7) .progress { height: 10px !important; }
            #projectsTable tbody td:nth-child(7) small { color: var(--text); font-size: .82rem; }

            #projectsTable tbody td:nth-child(3),
            #projectsTable tbody td:nth-child(4),
            #projectsTable tbody td:nth-child(5) {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                gap: .75rem;
                padding: .68rem .75rem !important;
                background: #ffffff;
                border: 1px solid var(--line) !important;
                border-radius: 14px;
            }
            #projectsTable tbody td:nth-child(3) { grid-area: location; }
            #projectsTable tbody td:nth-child(4) { grid-area: start; }
            #projectsTable tbody td:nth-child(5) { grid-area: end; }
            #projectsTable tbody td:nth-child(3)::before { content: "Location"; }
            #projectsTable tbody td:nth-child(4)::before { content: "Start date"; }
            #projectsTable tbody td:nth-child(5)::before { content: "Target date"; }
            #projectsTable tbody td:nth-child(3)::before,
            #projectsTable tbody td:nth-child(4)::before,
            #projectsTable tbody td:nth-child(5)::before {
                flex: 0 0 auto;
                color: var(--muted);
                font-size: .68rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .08em;
            }
            #projectsTable tbody td:nth-child(3) .location-badge,
            #projectsTable tbody td:nth-child(4) .date-badge,
            #projectsTable tbody td:nth-child(5) .date-badge {
                max-width: 58%;
                justify-content: flex-end;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            #projectsTable tbody td:nth-child(8) { grid-area: actions; padding-top: .2rem !important; }
            #projectsTable tbody td:nth-child(8) > .d-flex {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .5rem !important;
                justify-content: stretch !important;
            }
            #projectsTable tbody td:nth-child(8) form { display: block !important; width: 100% !important; }
            #projectsTable tbody td:nth-child(8) .btn {
                width: 100% !important;
                min-height: 42px !important;
                padding: .58rem .5rem !important;
                border-radius: 13px !important;
                font-size: .8rem !important;
            }
            #projectsTable tbody td:nth-child(8) .btn::after { margin-left: .28rem; font-weight: 800; }
            #projectsTable tbody td:nth-child(8) .btn[title="View Details"]::after { content: "View"; }
            #projectsTable tbody td:nth-child(8) .btn[title="Edit Project"]::after { content: "Edit"; }
            #projectsTable tbody td:nth-child(8) .btn[title="Archive"]::after { content: "Archive"; }
            #projectsTable tbody td:nth-child(8) .btn[title="Restore"]::after { content: "Restore"; }

            .empty-state { min-height: 210px !important; padding: 1.25rem !important; }

            .border-top.d-flex.flex-column.flex-md-row {
                padding: .85rem !important;
                background: #ffffff;
                border-radius: 0 0 18px 18px;
            }
            .pagination { justify-content: center !important; gap: .35rem !important; }
            .page-link { min-width: 38px; min-height: 38px; padding: .45rem .62rem !important; }

            .modal-dialog {
                margin: .65rem !important;
            }
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
        }

        @media (max-width: 380px) {
            .projects-hero-actions { grid-template-columns: 1fr !important; }
            .projects-stats-row { grid-template-columns: 1fr !important; }
            #projectsTable tbody tr {
                grid-template-columns: 28px minmax(0, 1fr);
                grid-template-areas:
                    "select status"
                    "title title"
                    "progress progress"
                    "location location"
                    "start start"
                    "end end"
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
            <a href="{{ route('admin.announcements.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            @endadmin_can
            
            @admin_can('view_events')
            <a href="{{ route('admin.events.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            @endadmin_can
            
            @admin_can('view_projects')
            <a href="{{ route('admin.projects.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
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
                    <span class="title-icon"><i class="fas fa-project-diagram"></i></span>
                    Project Management
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="projects-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="projects-hero">
                <div class="projects-hero-content">
                    <div class="min-w-0">
                        <div class="projects-hero-icon"><i class="fas fa-project-diagram"></i></div>
                        <div class="projects-kicker"><i class="fas fa-circle-check"></i> Project Control Center</div>
                        <h2>Track community projects with a cleaner workflow.</h2>
                        <p>Review project status, filter by progress, update milestones, and archive completed records without cluttering the page.</p>
                    </div>
                    <div class="projects-hero-actions">
                        @admin_can('create_projects')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                            <i class="fas fa-plus"></i>Add Project
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 projects-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-project-diagram me-1"></i>All projects
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Ongoing</div>
                            <div class="stat-number">{{ $ongoing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-hourglass me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-hourglass"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Completed</div>
                            <div class="stat-number">{{ $completed_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>Finished
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Avg Progress</div>
                            <div class="stat-number">{{ $avg_progress ?? 0 }}%</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-chart-line me-1"></i>Overall
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - UX Improved -->
            <div class="projects-panel projects-filter-panel">
                <form method="GET" action="{{ route('admin.projects.index') }}" id="searchForm">
                    <div class="projects-panel-header">
                        <div class="projects-panel-title">
                            <div class="projects-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-hard-hat"></i> Project Directory</div>
                                <h3>Search and filter projects</h3>
                                <p class="panel-copy">Find projects by title, description, or location. Use status and progress filters to narrow the table.</p>
                            </div>
                        </div>

                        <div class="projects-panel-actions">
                            @admin_can('create_projects')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                <i class="fas fa-plus"></i>Add Project
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="projects-filter-body">
                        <div class="projects-filter-toolbar">
                            <div class="projects-search-group">
                                <div class="projects-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search projects">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="progress" class="form-select" aria-label="Filter by progress">
                                <option value="">All progress</option>
                                <option value="0-25" {{ request('progress') == '0-25' ? 'selected' : '' }}>0-25%</option>
                                <option value="26-50" {{ request('progress') == '26-50' ? 'selected' : '' }}>26-50%</option>
                                <option value="51-75" {{ request('progress') == '51-75' ? 'selected' : '' }}>51-75%</option>
                                <option value="76-100" {{ request('progress') == '76-100' ? 'selected' : '' }}>76-100%</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_projects'))
                        <div class="projects-bulk-row">
                            <div class="selected-count" id="selectedProjectsCount">
                                <i class="fas fa-square-check me-1"></i>No projects selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_projects'))
            <form id="bulkForm" method="POST" action="{{ route('admin.projects.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Projects Table - UX Improved -->
            <div class="projects-panel">
                <div class="projects-panel-header">
                    <div class="projects-panel-title">
                        <div class="projects-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Project Records</div>
                            <h3>Projects List</h3>
                            <p class="panel-copy">Sort records, open details, and edit project information without leaving this page.</p>
                        </div>
                    </div>
                    <div class="projects-panel-actions">
                        <span class="projects-count-pill"><i class="fas fa-folder-open"></i>{{ isset($projects) ? $projects->total() : 0 }} projects</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="projectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Location
                                            @if(request('sort') == 'location')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Start Date
                                            @if(request('sort') == 'start_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'expected_completion', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Expected Completion
                                            @if(request('sort') == 'expected_completion')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th width="200">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Progress
                                            @if(request('sort') == 'progress')
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
                                @forelse($projects as $project)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $project->id }}" class="project-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="project-icon me-2">
                                                <i class="fas fa-hard-hat text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($project->title, 30) }}</div>
                                                <small class="text-muted d-md-none">{{ $project->location ?? 'No location' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="location-badge">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($project->location, 20) ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->start_date 
                                                ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->expected_completion 
                                                ? \Carbon\Carbon::parse($project->expected_completion)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </td>
                                    <td class="progress-cell">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                    role="progressbar"
                                                    style="width: {{ $project->progress }}%;"
                                                    aria-valuenow="{{ $project->progress }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $project->progress }}%
                                                </div>
                                            </div>
                                            <small class="fw-semibold">{{ $project->progress }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_projects can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $project->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_projects permission) -->
                                            @if(!$project->deleted_at)
                                            @admin_can('update_projects')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}" title="Edit Project">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif


                                            <!-- Delete (requires delete_projects permission) -->
                                            @admin_can('delete_projects')
                                            @if($project->deleted_at)
                                            <form method="POST" action="{{ route('admin.projects.restore', $project->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this project?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this project?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">
                                                    <i class="fas fa-trash"></i>
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
                                                <div class="empty-state-icon"><i class="fas fa-project-diagram"></i></div>
                                                <h4>No projects found</h4>
                                                <p>No project records match your current search and filters.</p>
                                                @admin_can('create_projects')
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                                    <i class="fas fa-plus"></i>Add Project
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
                    @if(isset($projects) && $projects->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $projects->firstItem() ?? 0 }}-{{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $projects->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Project Modal with Validation - Only if user has create_projects permission -->
            @admin_can('create_projects')
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat"></i>
                                <span class="modal-title-stack">
                                    <span>New Project</span>
                                    <span class="modal-subtitle">Add a clear project record with status, dates, location, and progress.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.projects.store') }}" id="addProjectForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="e.g. Drainage Rehabilitation Phase 1" required>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif" 
                                                  name="description" rows="4" placeholder="Write project description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Dates -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('start_date') is-invalid @enderror @endif" 
                                               name="start_date" value="{{ session('form_type') == 'add' ? old('start_date') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Expected Completion</label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('expected_completion') is-invalid @enderror @endif" 
                                               name="expected_completion" value="{{ session('form_type') == 'add' ? old('expected_completion') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('expected_completion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif" 
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="e.g. Zone 3, Hulong Duhat">
                                        @if(session('form_type') == 'add')
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Status and Progress -->
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" 
                                                name="status" required>
                                            <option value="">Select status</option>
                                            <option value="ongoing" {{ (session('form_type') == 'add' && old('status') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                            <option value="completed" {{ (session('form_type') == 'add' && old('status') == 'completed') ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                                        <input type="range" class="form-range" id="addProgressRange" min="0" max="100" step="1" value="{{ old('progress', 0) }}">
                                        <div class="text-center mt-2">
                                            <span class="badge bg-primary" id="addProgressValue">0%</span>
                                        </div>
                                        <input type="hidden" name="progress" id="addProgressHidden" value="{{ old('progress', 0) }}">
                                        @if(session('form_type') == 'add')
                                            @error('progress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addProjectForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Project
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Project Modals with Validation - Only if user has update_projects permission -->
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Project</span>
                                        <span class="modal-subtitle">Update project details and keep progress aligned with the selected status.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" id="editProjectForm{{ $project->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $project->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $project->id ? old('title', $project->title) : $project->title }}" required>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('description') is-invalid @enderror @endif" 
                                                      name="description" rows="4" required>{{ session('form_type') == 'edit_' . $project->id ? old('description', $project->description) : $project->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Dates -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('start_date') is-invalid @enderror @endif" 
                                                   name="start_date" value="{{ session('form_type') == 'edit_' . $project->id ? old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') : ($project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Expected Completion</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('expected_completion') is-invalid @enderror @endif" 
                                                   name="expected_completion" value="{{ session('form_type') == 'edit_' . $project->id ? old('expected_completion', $project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') : ($project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('expected_completion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Location -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Location</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('location') is-invalid @enderror @endif" 
                                                   name="location" value="{{ session('form_type') == 'edit_' . $project->id ? old('location', $project->location) : $project->location }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Status and Progress -->
                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $project->id) @error('status') is-invalid @enderror @endif" 
                                                    name="status" id="edit_status_{{ $project->id }}" required>
                                                <option value="">Select status</option>
                                                <option value="ongoing" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                                            <input type="range" class="form-range" id="editProgressRange{{ $project->id }}" min="0" max="100" step="1" value="{{ $project->progress }}">
                                            <div class="text-center mt-2">
                                                <span class="badge bg-primary" id="editProgressValue{{ $project->id }}">{{ $project->progress }}%</span>
                                            </div>
                                            <input type="hidden" name="progress" id="editProgressHidden{{ $project->id }}" value="{{ $project->progress }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('progress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editProjectForm{{ $project->id }}" class="btn btn-primary" id="submitEditForm{{ $project->id }}">
                                        <i class="fas fa-save me-2"></i>Update Project
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($projects as $project)
            <div class="modal fade" id="viewModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat"></i>
                                <span class="modal-title-stack">
                                    <span>Project Details</span>
                                    <span class="modal-subtitle">Review project timeline, location, progress, and description.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h4 class="fw-bold">{{ $project->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Progress</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                role="progressbar"
                                                style="width: {{ $project->progress }}%;">
                                                {{ $project->progress }}%
                                            </div>
                                        </div>
                                        <span class="fw-semibold">{{ $project->progress }}%</span>
                                    </div>
                                </div>

                                @if($project->location)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Location</label>
                                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $project->location }}</p>
                                </div>
                                @endif

                                @if($project->start_date)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Start Date</label>
                                    <p><i class="fas fa-calendar me-2 text-primary"></i>{{ $project->start_date->format('F d, Y') }}</p>
                                </div>
                                @endif

                                @if($project->expected_completion)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Expected Completion</label>
                                    <p>
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        {{ $project->expected_completion->format('F d, Y') }}
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-2">Overdue</span>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ $project->created_at ? $project->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <p><i class="fas fa-sync me-2 text-primary"></i>{{ $project->updated_at ? $project->updated_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-muted">Description</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($project->description)) !!}
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


            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addProjectModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editProjectModal{{ $editId }}'));
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
            const checkboxes = document.querySelectorAll('.project-checkbox:checked');
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
                        text: 'Please select at least one project to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one project to archive.');
                }
                return;
            }

            // Clear previous ids to avoid duplicate submissions.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected project(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} project(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.project-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof updateSelectedProjectsCount === 'function') {
                updateSelectedProjectsCount();
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

        // Update progress range display for add modal
        document.getElementById('addProgressRange')?.addEventListener('input', function() {
            document.getElementById('addProgressValue').textContent = this.value + '%';
            document.getElementById('addProgressHidden').value = this.value;
            
            // Auto-select status based on progress
            const statusSelect = document.querySelector('select[name="status"]');
            if (this.value == 100) {
                statusSelect.value = 'completed';
            } else {
                statusSelect.value = 'ongoing';
            }
        });

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.project-checkbox');
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

            // Initialize progress bars with gradient
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                if (!bar.classList.contains('bg-success')) {
                    bar.style.background = 'var(--primary-gradient)';
                }
            });

            // Setup edit modal progress listeners
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                (function(projectId) {
                    const range = document.getElementById('editProgressRange' + projectId);
                    const value = document.getElementById('editProgressValue' + projectId);
                    const hidden = document.getElementById('editProgressHidden' + projectId);
                    const statusSelect = document.getElementById('edit_status_' + projectId);
                    
                    if (range && value && hidden && statusSelect) {
                        range.addEventListener('input', function() {
                            value.textContent = this.value + '%';
                            hidden.value = this.value;
                            
                            // Auto-select status based on progress
                            if (this.value == 100) {
                                statusSelect.value = 'completed';
                            } else {
                                statusSelect.value = 'ongoing';
                            }
                        });
                    }
                })('{{ $project->id }}');
                @endforeach
            @endif
        });
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

        function attachProjectValidation(form) {
            if (!form) return;
            const title              = form.querySelector('[name="title"]');
            const description        = form.querySelector('[name="description"]');
            const status             = form.querySelector('[name="status"]');
            const startDate          = form.querySelector('[name="start_date"]');
            const expectedCompletion = form.querySelector('[name="expected_completion"]');
            const progressHidden     = form.querySelector('input[type="hidden"][name="progress"]');

            function hasFourDigitYear(dateValue) {
                return /^\d{4}-\d{2}-\d{2}$/.test(dateValue);
            }

            function getProgressValue() {
                const raw = progressHidden ? progressHidden.value : '0';
                const parsed = parseInt(raw, 10);
                return Number.isNaN(parsed) ? 0 : parsed;
            }

            function validateStatusVsProgress() {
                if (!status) return true;
                const progress = getProgressValue();
                if (status.value === 'completed' && progress !== 100) {
                    sf(status, 'Status can only be Completed when progress is 100%.');
                    return false;
                }
                if (status.value) {
                    cf(status);
                }
                return true;
            }

            function checkDateFieldFormat(field) {
                if (!field || !field.value) return true;
                if (!hasFourDigitYear(field.value)) {
                    sf(field, 'Date must use a 4-digit year (YYYY-MM-DD).');
                    return false;
                }
                return true;
            }

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (description) {
                description.addEventListener('input', function() { cf(this); }, {once: true});
                description.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Description is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (status) status.addEventListener('change', function() {
                if (!this.value) {
                    sf(this, 'Please select a status.');
                } else {
                    validateStatusVsProgress();
                }
            });

            if (progressHidden) {
                progressHidden.addEventListener('input', validateStatusVsProgress);
                progressHidden.addEventListener('change', validateStatusVsProgress);
            }

            if (startDate) {
                startDate.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion) {
                expectedCompletion.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion && startDate) {
                const checkDates = function() {
                    if (!checkDateFieldFormat(startDate) || !checkDateFieldFormat(expectedCompletion)) {
                        return;
                    }
                    if (expectedCompletion.value && startDate.value && expectedCompletion.value < startDate.value) {
                        sf(expectedCompletion, 'Completion date must not be before the start date.');
                    } else cf(expectedCompletion);
                };
                expectedCompletion.addEventListener('change', checkDates);
                startDate.addEventListener('change', checkDates);
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                    else { cf(title); }
                }
                if (description) {
                    if (!description.value.trim()) { sf(description, 'Description is required.'); valid = false; }
                    else if (description.value.trim().length < 10) { sf(description, 'Description must be at least 10 characters.'); valid = false; }
                    else { cf(description); }
                }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                else if (status && !validateStatusVsProgress()) { valid = false; }
                else if (status) { cf(status); }

                if (startDate && !checkDateFieldFormat(startDate)) {
                    valid = false;
                }
                if (expectedCompletion && !checkDateFieldFormat(expectedCompletion)) {
                    valid = false;
                }

                if (startDate && expectedCompletion && startDate.value && expectedCompletion.value && expectedCompletion.value < startDate.value) {
                    sf(expectedCompletion, 'Completion date must not be before the start date.'); valid = false;
                }
                else if (expectedCompletion) {
                    cf(expectedCompletion);
                }
                if (!valid) e.preventDefault();
            });
        }

        attachProjectValidation(document.getElementById('addProjectForm'));
        document.querySelectorAll('[id^="editProjectForm"]').forEach(attachProjectValidation);
    });

// UX helper: keep the selected counter updated for bulk actions.
        function updateSelectedProjectsCount() {
            const selected = document.querySelectorAll('.project-checkbox:checked').length;
            const counter = document.getElementById('selectedProjectsCount');
            if (!counter) return;
            counter.innerHTML = selected > 0
                ? `<i class="fas fa-square-check me-1"></i>${selected} project${selected === 1 ? '' : 's'} selected`
                : '<i class="fas fa-square-check me-1"></i>No projects selected';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.project-checkbox, #selectAll').forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelectedProjectsCount);
            });
            updateSelectedProjectsCount();

            document.querySelectorAll('.modal-body .row.g-3').forEach((row) => {
                if (!row.closest('.modal-body')) return;
                row.classList.add('modal-form-grid');
            });

            document.querySelectorAll('.modal-body textarea[name="description"]').forEach((textarea) => {
                if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList?.contains('field-hint')) {
                    textarea.insertAdjacentHTML('afterend', '<span class="field-hint">Use short, resident-friendly wording. Include scope, location, and expected outcome when possible.</span>');
                }
            });
        });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

