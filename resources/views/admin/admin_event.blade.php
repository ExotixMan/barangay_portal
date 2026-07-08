<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events - Admin</title>
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
            padding: 0.4rem 0.6rem;
        }

        .form-control[type="file"]::file-selector-button {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: #f8f9fa;
            padding: 0.4rem 0.8rem;
            margin-right: 1rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control[type="file"]::file-selector-button:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
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

        /* Attendees Badge */
        .attendees-badge {
            background: var(--info-light);
            color: var(--info);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Location Text */
        .location-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ==========================================================
           Events UX redesign - compact Backup-style layout
           Keeps existing sidebar, header, Blade routes, permissions, and forms.
        ========================================================== */
        :root {
            --primary: #d32f2f;
            --primary-dark: #a91f1f;
            --primary-soft: #fff5f5;
            --success: #16803c;
            --success-soft: #eefaf2;
            --warning: #d97706;
            --warning-soft: #fff7ed;
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --info: #0369a1;
            --info-soft: #eff6ff;
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

        .events-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .events-alert,
        .events-page > .alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .events-page > .alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .events-hero,
        .events-panel,
        .stat-card {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .events-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            padding: clamp(1.1rem, 2vw, 1.55rem);
        }

        .events-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(135deg, rgba(211, 47, 47, .04), transparent 42%);
            pointer-events: none;
        }

        .events-hero-content {
            position: relative;
            z-index: 1;
            max-width: 920px;
        }

        .events-hero-icon,
        .events-panel-icon,
        .empty-state-icon,
        .event-thumb,
        .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .events-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .14);
            border-radius: 14px;
            font-size: 1.1rem;
        }

        .events-kicker,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .events-hero h2 {
            margin: .5rem 0 .5rem;
            color: var(--text);
            font-size: clamp(1.7rem, 3vw, 2.45rem);
            font-weight: 850;
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .events-hero p,
        .events-panel-copy,
        .empty-state p {
            color: var(--muted);
            line-height: 1.65;
        }

        .events-hero p {
            max-width: 760px;
            margin: 0;
            font-size: .95rem;
        }

        .events-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: 1rem;
        }

        .events-stats-row {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-right: 0 !important;
            margin-left: 0 !important;
            margin-bottom: .9rem !important;
        }

        .events-stats-row > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .stat-card {
            height: 100%;
            padding: 1rem !important;
            gap: .85rem;
            overflow: visible !important;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .stat-card::before {
            display: none !important;
        }

        .stat-card:hover,
        .events-panel:hover {
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stat-card:hover {
            transform: translateY(-1px) !important;
        }

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
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .stat-number {
            display: block;
            margin-top: .28rem;
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
            font-weight: 600;
            line-height: 1.45;
        }

        .events-panel {
            overflow: hidden;
        }

        .events-filter-panel {
            margin-bottom: .75rem !important;
        }

        .events-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .events-panel-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .events-panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .events-panel-header h3 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .events-panel-copy {
            margin: 0;
            font-size: .86rem;
        }

        .events-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .events-filter-body {
            padding: .85rem 1rem 1rem;
        }

        .events-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(300px, 1.45fr) minmax(160px, .55fr) minmax(190px, .7fr) auto;
            gap: .65rem;
            align-items: center;
        }

        .events-search-group {
            display: flex;
            align-items: stretch;
            min-width: 0;
        }

        .events-search-control {
            position: relative;
            flex: 1 1 auto;
            min-width: 0;
        }

        .events-search-control i {
            position: absolute;
            top: 50%;
            left: .9rem;
            transform: translateY(-50%);
            color: var(--muted-2);
            pointer-events: none;
            z-index: 2;
        }

        .events-search-control .form-control {
            height: 44px;
            padding-left: 2.45rem !important;
            border-radius: 12px 0 0 12px !important;
            border-right: 0 !important;
        }

        .events-search-btn {
            height: 44px;
            border-radius: 0 12px 12px 0 !important;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .selected-count-pill,
        .events-count-pill {
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

        .events-count-pill {
            color: #344054;
            background: #f9fafb;
            border-color: var(--line);
        }

        .events-table-panel {
            margin-top: 0 !important;
        }

        .events-table-panel .card-body {
            padding: 0 !important;
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

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:focus-visible,
        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus {
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
        .btn-danger:hover,
        .btn-primary:focus,
        .btn-danger:focus {
            color: #ffffff !important;
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: 0 12px 26px rgba(211, 47, 47, .22);
        }

        .btn-light-soft,
        .btn-secondary,
        .btn-outline-secondary,
        .btn-outline-primary {
            color: var(--text) !important;
            background: var(--card) !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .btn-secondary:hover,
        .btn-outline-secondary:hover,
        .btn-outline-primary:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211, 47, 47, .22) !important;
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

        .form-control::placeholder {
            color: var(--muted-2);
        }

        textarea.form-control {
            line-height: 1.65;
            resize: vertical;
        }

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
        }

        .form-hint,
        .modal small.text-muted {
            display: block;
            margin-top: .35rem;
            color: var(--muted-2) !important;
            font-size: .75rem;
            font-weight: 650;
            line-height: 1.4;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0 !important;
        }

        .table {
            min-width: 960px;
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

        .table tbody tr {
            transition: background .16s ease;
        }

        .table tbody tr:hover {
            background: #fcfcfd;
        }

        .event-thumb {
            flex: 0 0 auto;
            width: 40px !important;
            height: 40px !important;
            color: var(--primary);
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px !important;
            overflow: hidden;
        }

        .event-thumb img {
            width: 100% !important;
            height: 100% !important;
            border-radius: 14px !important;
            object-fit: cover;
        }

        .badge,
        .role-badge,
        .attendees-badge {
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

        .badge.bg-success-subtle {
            color: var(--success) !important;
            background: var(--success-soft) !important;
            border: 1px solid rgba(22, 128, 60, .14);
        }

        .badge.bg-danger-subtle {
            color: var(--danger) !important;
            background: var(--danger-soft) !important;
            border: 1px solid rgba(220, 38, 38, .14);
        }

        .badge.bg-secondary-subtle {
            color: #344054 !important;
            background: #f9fafb !important;
            border: 1px solid var(--line);
        }

        .attendees-badge {
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid #dbeafe;
        }

        .location-text {
            display: inline-block;
            max-width: 210px;
            color: var(--text);
            font-weight: 650;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
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
            align-items: center;
            gap: .55rem;
            margin: 0;
            color: var(--text) !important;
            font-size: 1rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .modal-title i {
            color: var(--primary);
        }

        .modal-title::after {
            display: block;
            margin-left: .1rem;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            letter-spacing: 0;
        }

        #viewModal .modal-title::after,
        [id^="viewModal"] .modal-title::after {
            content: 'Quick overview of schedule, location, attendees, and description.';
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
            padding: .85rem 1rem !important;
            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(8px);
            border-top: 1px solid var(--line) !important;
        }

        .modal .row.g-3 {
            --bs-gutter-x: .9rem;
            --bs-gutter-y: .9rem;
        }

        .modal .col-12.mt-3 h6 {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin: .45rem 0 0;
            padding-top: .85rem;
            color: var(--text) !important;
            border-top: 1px dashed var(--line-strong);
            font-size: .9rem;
            font-weight: 850;
        }

        .modal .col-12.mt-3 h6::before {
            content: '';
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }

        .modal .bg-light.rounded,
        .modal .p-3.bg-light.rounded {
            background: #f9fafb !important;
            border: 1px solid var(--line);
            border-radius: var(--radius-md) !important;
            color: var(--text);
            line-height: 1.65;
        }

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

        .image-preview {
            max-width: 100%;
            max-height: 220px;
            border-radius: 16px;
            margin-top: .65rem;
            border: 1px solid var(--line);
            box-shadow: var(--shadow-xs);
            object-fit: cover;
        }

        .modal .img-fluid.rounded {
            width: 100%;
            max-height: 320px !important;
            object-fit: cover;
            border-radius: 18px !important;
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
            color: var(--danger);
            font-size: .76rem;
            font-weight: 760;
        }

        .alert-danger li::before {
            content: '•';
            color: var(--danger);
            margin-right: .45rem;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        @media (max-width: 1199px) {
            .events-stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .events-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) minmax(170px, .5fr) minmax(190px, .6fr);
            }

            .events-filter-toolbar .btn {
                width: 100%;
            }
        }

        @media (max-width: 991px) {
            .events-panel-header {
                flex-direction: column;
            }

            .events-panel-actions,
            .events-panel-actions .btn {
                width: 100%;
            }

            .events-filter-toolbar {
                grid-template-columns: minmax(0, 1fr);
            }

            .events-search-group {
                width: 100%;
            }

            .table {
                min-width: 860px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: .85rem 1rem;
            }

            .page-title {
                font-size: 1.02rem;
            }

            .events-page {
                max-width: 100%;
            }

            .events-hero,
            .events-panel,
            .stat-card {
                border-radius: 16px !important;
            }

            .events-hero,
            .events-panel-header,
            .events-filter-body,
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: .9rem !important;
            }

            .events-hero-actions,
            .events-hero-actions .btn {
                width: 100%;
            }

            .events-stats-row {
                grid-template-columns: minmax(0, 1fr);
            }

            .events-search-group {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 575px) {
            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .events-hero h2 {
                font-size: 1.75rem;
            }

            .events-search-btn span {
                display: none;
            }
        }

    
        /* ==========================================================
           Events design aligned with Projects page
           Consistent hero, filters, table, and modal UX.
        ========================================================== */
        .events-hero-content {
            position: relative;
            z-index: 1;
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
            max-width: none !important;
        }

        .events-hero {
            margin-bottom: 1rem !important;
            padding: clamp(1.1rem, 1.8vw, 1.5rem) !important;
        }

        .events-hero h2 {
            font-size: clamp(1.65rem, 2.6vw, 2.25rem) !important;
            line-height: 1.08 !important;
            margin: .48rem 0 .42rem !important;
        }

        .events-hero p {
            max-width: 720px !important;
            font-size: .93rem !important;
        }

        .events-hero-actions {
            justify-content: flex-end;
            margin-top: 0 !important;
        }

        .events-stats-row {
            margin-bottom: 1rem !important;
        }

        .events-filter-panel {
            margin-bottom: .75rem !important;
        }

        .events-filter-panel .events-panel-header {
            padding-bottom: .85rem !important;
        }

        .events-filter-panel .events-filter-body {
            padding-top: .85rem !important;
            padding-bottom: .85rem !important;
        }

        .events-filter-toolbar {
            grid-template-columns: minmax(360px, 1.55fr) minmax(155px, .65fr) minmax(170px, .72fr) auto !important;
            gap: .55rem !important;
            align-items: stretch !important;
        }

        .events-search-group {
            display: flex;
            align-items: stretch;
            min-width: 0;
        }

        .events-search-group .events-search-control {
            flex: 1 1 auto;
        }

        .events-search-group .form-control {
            height: 100%;
            border-right: 0;
            border-radius: 12px 0 0 12px;
        }

        .events-search-group .events-search-btn {
            flex: 0 0 auto;
            min-width: 104px;
            border-radius: 0 12px 12px 0;
            box-shadow: none;
            white-space: nowrap;
        }

        .events-bulk-row {
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

        .events-table-panel {
            margin-top: 0 !important;
        }

        .event-thumb {
            width: 40px !important;
            height: 40px !important;
            color: var(--primary);
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px !important;
            overflow: hidden;
        }

        .event-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px !important;
        }

        .event-date-badge,
        .event-time-badge,
        .attendees-badge,
        .location-text,
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

        .location-text {
            max-width: 220px;
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid #dbeafe;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-date-badge,
        .event-time-badge {
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
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
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
            line-height: 1.55;
            letter-spacing: 0;
        }

        .modal-body {
            padding: 1rem !important;
            max-height: 72vh;
            overflow-y: auto;
        }

        .modal-body .row.g-3 {
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
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

        .event-view-body .row.g-3 {
            border: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        .event-detail-title {
            margin-bottom: .25rem;
            color: var(--text);
            font-size: 1.35rem;
            font-weight: 850;
            letter-spacing: -.035em;
        }

        .event-view-body .col-md-6 {
            padding: .85rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .event-view-body .col-md-6 label {
            display: block;
            margin-bottom: .4rem;
            color: var(--muted) !important;
            font-size: .72rem;
            font-weight: 850 !important;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .event-view-body .col-md-6 p {
            margin: 0;
            color: var(--text);
            font-weight: 700;
        }

        .event-view-body .bg-light.rounded,
        .description-box {
            padding: .95rem !important;
            color: var(--text);
            background: #f9fafb !important;
            border: 1px solid var(--line);
            border-radius: 14px !important;
            line-height: 1.65;
        }

        .event-image-card {
            padding: .7rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
        }

        .event-image-card img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            border-radius: 14px !important;
        }

        .image-preview {
            max-width: 100%;
            max-height: 220px;
            border-radius: 14px;
            margin-top: 10px;
            border: 1px solid var(--line);
            object-fit: cover;
        }

        @media (max-width: 991px) {
            .events-hero-content {
                display: block !important;
            }

            .events-hero-actions,
            .events-panel-actions,
            .events-panel-actions .btn {
                width: 100%;
                justify-content: stretch;
            }

            .events-hero-actions {
                margin-top: 1rem !important;
            }

            .events-hero-actions .btn {
                flex: 1 1 180px;
            }

            .events-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .events-search-group {
                width: 100%;
            }

            .events-search-group .events-search-btn {
                min-width: 96px;
            }

            .events-filter-toolbar .filter-submit-btn {
                width: 100%;
            }

            .events-bulk-row {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 768px) {
            .events-hero,
            .events-panel,
            .stat-card,
            .card.border-0 {
                border-radius: 16px !important;
            }

            .events-hero,
            .events-panel-header,
            .events-filter-body,
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: .9rem !important;
            }

            .events-hero-actions,
            .events-hero-actions .btn {
                width: 100%;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }



        /* ==========================================================
           FINAL MOBILE UX PATCH - cleaner Events mobile layout
           Paste-safe: CSS only, no Blade route/form changes.
        ========================================================== */
        .title-icon {
            flex: 0 0 auto;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 12px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: .7rem;
            min-width: 0;
            line-height: 1.15;
        }

        .header > .d-flex {
            min-width: 0;
        }

        @media (max-width: 1199.98px) {
            .events-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) minmax(150px, .5fr) minmax(170px, .55fr) !important;
            }

            .events-filter-toolbar .filter-submit-btn {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 991.98px) {
            .events-hero-content {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .events-hero-actions {
                width: 100%;
                justify-content: stretch !important;
            }

            .events-hero-actions .btn,
            .events-panel-actions .btn,
            .events-panel-actions .events-count-pill {
                flex: 1 1 auto;
            }

            .events-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .events-search-group {
                width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            body {
                background: #f8fafc;
            }

            .main-content {
                min-width: 0;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .header {
                position: sticky;
                top: 0;
                z-index: 20;
                padding: .78rem .85rem !important;
                background: rgba(255, 255, 255, .96);
                backdrop-filter: blur(10px);
            }

            .mobile-menu-btn {
                flex: 0 0 auto;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                padding: 0 !important;
                color: var(--text);
                background: #ffffff;
                border: 1px solid var(--line);
                border-radius: 12px;
                box-shadow: var(--shadow-xs);
            }

            .title-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            .page-title {
                max-width: calc(100vw - 112px);
                font-size: 1rem !important;
                font-weight: 850;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .events-page {
                max-width: 100% !important;
            }

            .events-hero,
            .events-panel,
            .stat-card {
                border-radius: 18px !important;
            }

            .events-hero {
                padding: 1rem !important;
                margin-bottom: .85rem !important;
            }

            .events-hero-icon {
                width: 42px;
                height: 42px;
                margin-bottom: .65rem;
                border-radius: 13px;
            }

            .events-kicker,
            .section-kicker {
                font-size: .66rem;
                letter-spacing: .08em;
            }

            .events-hero h2 {
                max-width: 100%;
                margin: .45rem 0 .4rem !important;
                font-size: 1.55rem !important;
                line-height: 1.08 !important;
                letter-spacing: -.045em;
            }

            .events-hero p {
                font-size: .86rem !important;
                line-height: 1.55;
            }

            .events-hero-actions {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: .55rem;
                margin-top: .9rem !important;
            }

            .events-hero-actions .btn {
                width: 100%;
                min-height: 42px;
                padding: .62rem .7rem;
            }

            .events-stats-row {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .65rem !important;
                margin-bottom: .85rem !important;
            }

            .events-stats-row > [class*="col-"] {
                width: auto !important;
                max-width: none !important;
                padding: 0 !important;
            }

            .stat-card {
                align-items: center !important;
                min-height: 92px;
                padding: .82rem !important;
                gap: .65rem !important;
            }

            .stat-card > div:first-child {
                min-width: 0;
            }

            .stat-icon {
                width: 40px !important;
                height: 40px !important;
                border-radius: 13px !important;
                font-size: 1rem !important;
            }

            .stat-label {
                font-size: .65rem !important;
                letter-spacing: .06em;
            }

            .stat-number {
                margin-top: .18rem;
                font-size: 1.45rem !important;
                line-height: 1;
            }

            .stat-card small {
                display: none !important;
            }

            .events-panel-header {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                gap: .75rem;
                padding: .9rem !important;
            }

            .events-panel-title {
                gap: .65rem;
            }

            .events-panel-icon {
                width: 40px !important;
                height: 40px !important;
                border-radius: 13px !important;
            }

            .events-panel-header h3 {
                font-size: .96rem !important;
            }

            .events-panel-copy {
                font-size: .8rem !important;
                line-height: 1.5;
            }

            .events-filter-panel .events-panel-actions {
                display: none !important;
            }

            .events-filter-body {
                padding: .85rem !important;
            }

            .events-filter-toolbar {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: .6rem !important;
            }

            .events-search-group {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) 48px;
                align-items: stretch;
                min-width: 0;
            }

            .events-search-control .form-control,
            .events-search-group .form-control {
                height: 44px !important;
                min-height: 44px !important;
                border-radius: 12px 0 0 12px !important;
                border-right: 0 !important;
                font-size: .86rem;
            }

            .events-search-btn,
            .events-search-group .events-search-btn {
                min-width: 48px !important;
                width: 48px !important;
                height: 44px !important;
                padding: 0 !important;
                border-radius: 0 12px 12px 0 !important;
                box-shadow: none !important;
            }

            .events-search-btn span {
                display: none !important;
            }

            .form-control,
            .form-select {
                min-height: 44px;
                font-size: .87rem;
                border-radius: 12px;
            }

            .filter-submit-btn {
                width: 100%;
            }

            .events-bulk-row {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                gap: .55rem;
                margin-top: .75rem;
                padding-top: .75rem;
            }

            .selected-count {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                padding: .55rem .7rem;
                background: #f9fafb;
                border: 1px solid var(--line);
                border-radius: 12px;
                text-align: center;
                font-size: .78rem;
            }

            .events-bulk-row .btn {
                width: 100%;
            }

            .events-table-panel .events-panel-header {
                border-bottom: 0;
            }

            .events-table-panel .events-panel-actions {
                width: 100%;
            }

            .events-count-pill {
                width: 100%;
                justify-content: center;
                min-height: 38px;
            }

            .events-table-panel .table-responsive {
                overflow: visible !important;
                border-radius: 0 !important;
            }

            #eventsTable {
                display: block !important;
                width: 100% !important;
                min-width: 0 !important;
                border: 0 !important;
            }

            #eventsTable thead {
                display: none !important;
            }

            #eventsTable tbody {
                display: grid !important;
                gap: .75rem;
                padding: .75rem;
                background: #f8fafc;
            }

            #eventsTable tbody tr {
                display: grid !important;
                grid-template-columns: 34px minmax(0, 1fr) auto;
                grid-template-areas:
                    "check title status"
                    "date date date"
                    "time time time"
                    "location location location"
                    "attendees attendees attendees"
                    "actions actions actions";
                gap: .5rem .6rem;
                align-items: start;
                padding: .85rem;
                background: #ffffff !important;
                border: 1px solid var(--line);
                border-radius: 18px;
                box-shadow: 0 8px 20px rgba(16, 24, 40, .045);
            }

            #eventsTable tbody tr:hover {
                background: #ffffff !important;
                transform: none !important;
            }

            #eventsTable tbody td,
            #eventsTable tbody td.d-none,
            #eventsTable tbody td.d-md-table-cell,
            #eventsTable tbody td.d-lg-table-cell {
                width: 100% !important;
                padding: 0 !important;
                border: 0 !important;
                background: transparent !important;
                text-align: left !important;
                white-space: normal !important;
            }

            #eventsTable tbody td:nth-child(1) {
                grid-area: check;
                display: flex !important;
                align-items: center;
                justify-content: center;
                padding-top: .25rem !important;
            }

            #eventsTable tbody td:nth-child(1)::before,
            #eventsTable tbody td:nth-child(2)::before,
            #eventsTable tbody td:nth-child(7)::before,
            #eventsTable tbody td:nth-child(8)::before {
                display: none !important;
            }

            #eventsTable tbody td:nth-child(1) input[type="checkbox"] {
                width: 22px;
                height: 22px;
                margin: 0;
            }

            #eventsTable tbody td:nth-child(2) {
                grid-area: title;
                display: block !important;
                min-width: 0;
            }

            #eventsTable tbody td:nth-child(2) > .d-flex {
                width: 100%;
                min-width: 0;
                gap: .65rem;
                align-items: center !important;
            }

            #eventsTable tbody td:nth-child(2) .fw-semibold {
                max-width: 100%;
                color: var(--text);
                font-size: .94rem;
                font-weight: 850;
                line-height: 1.28;
                letter-spacing: -.018em;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }

            #eventsTable tbody td:nth-child(2) small.d-md-none {
                display: none !important;
            }

            #eventsTable tbody td:nth-child(7) {
                grid-area: status;
                display: flex !important;
                justify-content: flex-end;
                align-items: flex-start;
                min-width: max-content;
            }

            #eventsTable tbody td:nth-child(3) { grid-area: date; }
            #eventsTable tbody td:nth-child(4) { grid-area: time; }
            #eventsTable tbody td:nth-child(5) { grid-area: location; }
            #eventsTable tbody td:nth-child(6) { grid-area: attendees; }

            #eventsTable tbody td:nth-child(3),
            #eventsTable tbody td:nth-child(4),
            #eventsTable tbody td:nth-child(5),
            #eventsTable tbody td:nth-child(6) {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                gap: .8rem;
                min-height: 38px;
                padding: .52rem .65rem !important;
                background: #f9fafb !important;
                border: 1px solid var(--line) !important;
                border-radius: 13px;
                color: var(--text);
                font-size: .82rem;
                font-weight: 700;
            }

            #eventsTable tbody td:nth-child(3)::before,
            #eventsTable tbody td:nth-child(4)::before,
            #eventsTable tbody td:nth-child(5)::before,
            #eventsTable tbody td:nth-child(6)::before {
                flex: 0 0 auto;
                color: var(--muted);
                font-size: .66rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .08em;
            }

            #eventsTable tbody td:nth-child(3)::before { content: 'Date'; }
            #eventsTable tbody td:nth-child(4)::before { content: 'Time'; }
            #eventsTable tbody td:nth-child(5)::before { content: 'Location'; }
            #eventsTable tbody td:nth-child(6)::before { content: 'Attendees'; }

            #eventsTable tbody td:nth-child(8) {
                grid-area: actions;
                display: block !important;
                margin-top: .15rem;
                padding-top: .65rem !important;
                border-top: 1px dashed var(--line-strong) !important;
            }

            #eventsTable tbody td:nth-child(8) > .d-flex {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .45rem !important;
                width: 100%;
                justify-content: stretch !important;
            }

            #eventsTable tbody td:nth-child(8) .btn,
            #eventsTable tbody td:nth-child(8) form,
            #eventsTable tbody td:nth-child(8) button {
                width: 100% !important;
            }

            #eventsTable tbody td:nth-child(8) .btn-sm {
                min-height: 40px;
                padding: .5rem .55rem;
                border-radius: 12px;
            }

            #eventsTable tbody td[colspan] {
                display: block !important;
                grid-column: 1 / -1;
                padding: 0 !important;
            }

            .event-thumb {
                width: 44px !important;
                height: 44px !important;
                border-radius: 14px !important;
            }

            .event-thumb img {
                width: 100% !important;
                height: 100% !important;
                border-radius: 13px !important;
            }

            .badge,
            .event-date-badge,
            .event-time-badge,
            .attendees-badge,
            .location-text {
                max-width: 100%;
                min-height: 30px;
                padding: .34rem .55rem;
                font-size: .7rem;
                line-height: 1.25;
                white-space: normal;
            }

            .location-text {
                justify-content: flex-end;
                max-width: 58vw !important;
                overflow: hidden;
                text-align: right;
                text-overflow: ellipsis;
                white-space: nowrap !important;
            }

            .attendees-badge {
                flex: 0 0 auto;
            }

            .empty-state {
                min-height: 220px;
                padding: 1.4rem .75rem;
            }

            .events-table-panel .border-top.d-flex {
                padding: .85rem !important;
            }

            .events-table-panel nav,
            .events-table-panel nav .pagination {
                width: 100%;
            }

            .pagination {
                gap: .35rem;
                justify-content: center;
            }

            .page-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 38px;
                min-height: 38px;
                padding: .38rem .55rem;
                font-size: .8rem;
            }

            .modal-dialog,
            .modal-dialog.modal-lg,
            .modal-dialog.modal-dialog-centered,
            .modal-dialog.modal-dialog-scrollable {
                width: auto !important;
                max-width: none !important;
                min-height: 0 !important;
                margin: .6rem !important;
            }

            .modal-content {
                max-height: calc(100dvh - 1.2rem) !important;
                border-radius: 20px !important;
            }

            .modal-header {
                position: sticky;
                top: 0;
                z-index: 5;
                padding: .85rem !important;
            }

            .modal-title {
                align-items: flex-start;
                gap: .6rem;
                font-size: .95rem !important;
                line-height: 1.25;
            }

            .modal-title i {
                width: 38px !important;
                height: 38px !important;
                border-radius: 13px !important;
            }

            .modal-subtitle {
                display: none !important;
            }

            .modal-body {
                padding: .85rem !important;
                max-height: calc(100dvh - 142px) !important;
            }

            .modal-body .row.g-3 {
                padding: .8rem !important;
                border-radius: 16px !important;
            }

            .modal-footer {
                position: sticky;
                bottom: 0;
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                gap: .5rem !important;
                padding: .8rem !important;
                background: #ffffff !important;
            }

            .modal-footer .btn {
                width: 100%;
                min-height: 44px;
            }

            textarea.form-control {
                min-height: 112px;
            }

            .form-control[type="file"] {
                padding: .75rem !important;
            }

            .form-control[type="file"]::file-selector-button {
                display: block;
                width: 100%;
                margin: 0 0 .5rem 0 !important;
            }

            .image-preview,
            .modal .img-fluid.rounded,
            .event-image-card img {
                width: 100%;
                max-height: 230px !important;
                object-fit: cover;
            }
        }

        @media (max-width: 430px) {
            .events-hero-actions {
                grid-template-columns: minmax(0, 1fr);
            }

            .events-stats-row {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .stat-card {
                min-height: 78px;
            }

            #eventsTable tbody {
                padding: .65rem;
            }

            #eventsTable tbody tr {
                grid-template-columns: 30px minmax(0, 1fr);
                grid-template-areas:
                    "check status"
                    "title title"
                    "date date"
                    "time time"
                    "location location"
                    "attendees attendees"
                    "actions actions";
                padding: .78rem;
            }

            #eventsTable tbody td:nth-child(7) {
                justify-content: flex-end;
            }

            #eventsTable tbody td:nth-child(2) {
                padding-top: .2rem !important;
            }

            #eventsTable tbody td:nth-child(3),
            #eventsTable tbody td:nth-child(4),
            #eventsTable tbody td:nth-child(5),
            #eventsTable tbody td:nth-child(6) {
                align-items: flex-start;
                flex-direction: column;
                gap: .28rem;
            }

            .location-text {
                justify-content: flex-start;
                max-width: 100% !important;
                width: 100%;
                text-align: left;
                white-space: normal !important;
            }

            #eventsTable tbody td:nth-child(8) > .d-flex {
                grid-template-columns: repeat(3, minmax(42px, 1fr));
            }

            .modal-dialog,
            .modal-dialog.modal-lg {
                margin: .4rem !important;
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
            <a href="{{ route('admin.events.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
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
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-calendar"></i></span>
                    Event Management
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="events-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="events-hero">
                <div class="events-hero-content">
                    <div class="min-w-0">
                        <div class="events-hero-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="events-kicker"><i class="fas fa-circle-check"></i> Event Control Center</div>
                        <h2>Manage community events with a cleaner workflow.</h2>
                        <p>Review upcoming and past activities, filter schedules, manage event images, and archive records without cluttering the page.</p>
                    </div>
                    <div class="events-hero-actions">
                        @admin_can('create_events')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-plus"></i>Add Event
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 events-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-calendar me-1"></i>All events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Upcoming</div>
                            <div class="stat-number">{{ $upcoming_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Future events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Past</div>
                            <div class="stat-number">{{ $past_count ?? 0 }}</div>
                            <small class="text-secondary mt-2 d-none d-sm-block">
                                <i class="fas fa-history me-1"></i>Completed
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Attendees</div>
                            <div class="stat-number">{{ $total_attendees ?? 0 }}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-users me-1"></i>Total participants
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Compact UX -->
            <div class="events-panel events-filter-panel">
                <form method="GET" action="{{ route('admin.events.index') }}" id="searchForm">
                    <div class="events-panel-header">
                        <div class="events-panel-title">
                            <div class="events-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-calendar"></i> Event Directory</div>
                                <h3>Search and filter events</h3>
                                <p class="events-panel-copy">Search by title, description, or location. Use filters only when you need a narrower list.</p>
                            </div>
                        </div>

                        <div class="events-panel-actions">
                            @admin_can('create_events')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i>Add Event
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="events-filter-body">
                        <div class="events-filter-toolbar">
                            <div class="events-search-group">
                                <div class="events-search-control">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search events">
                                </div>
                                <button class="btn btn-primary events-search-btn" type="submit">
                                    <i class="fas fa-search"></i><span>Search</span>
                                </button>
                            </div>

                            <select name="type" class="form-select" aria-label="Filter by type">
                                <option value="">All types</option>
                                <option value="upcoming" {{ request('type') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('type') == 'past' ? 'selected' : '' }}>Past</option>
                                <option value="deleted" {{ request('type') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="month" class="form-select" aria-label="Filter by month">
                                <option value="">All months</option>
                                <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_events'))
                        <div class="events-bulk-row">
                            <div class="selected-count" id="selectedCount">
                                <i class="fas fa-square-check me-1"></i>No events selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_events'))
            <form id="bulkForm" method="POST" action="{{ route('admin.events.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Events Table - Mobile Responsive with Horizontal Scroll -->
            <div class="events-panel events-table-panel">
                <div class="events-panel-header">
                    <div class="events-panel-title">
                        <div class="events-panel-icon"><i class="fas fa-calendar-days"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-list"></i> Results</div>
                            <h3>Event List</h3>
                            <p class="events-panel-copy">View, edit, archive, or restore event records from one clean table.</p>
                        </div>
                    </div>
                    <div class="events-panel-actions">
                        <span class="events-count-pill">
                            <i class="fas fa-calendar"></i>
                            @if(isset($events) && method_exists($events, 'total'))
                                {{ $events->total() }} events
                            @else
                                {{ count($events ?? []) }} events
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="eventsTable">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'event_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Date
                                            @if(request('sort') == 'event_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Time</th>
                                    <th>
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
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'attendees', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Attendees
                                            @if(request('sort') == 'attendees')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Type
                                            @if(request('sort') == 'type')
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
                                @forelse($events as $event)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $event->id }}" class="event-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="event-thumb me-2">
                                                @if($event->image)
                                                    <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($event->title, 30) }}</div>
                                                <small class="text-muted d-md-none">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="location-text" title="{{ $event->location }}">
                                            {{ $event->location ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="attendees-badge">
                                            <i class="fas fa-users me-1"></i>{{ number_format($event->attendees ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($event->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_events can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $event->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_events permission) -->
                                            @if(!$event->deleted_at)
                                            @admin_can('update_events')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}" title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif

                                            <!-- Delete (requires delete_events permission) -->
                                            @admin_can('delete_events')
                                            @if($event->deleted_at)
                                            <form method="POST" action="{{ route('admin.events.restore', $event->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this event?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this event?')">
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
                                                <div class="empty-state-icon"><i class="fas fa-calendar-xmark"></i></div>
                                                <h4>No events found</h4>
                                                <p>No event records match the current search and filters.</p>
                                                @admin_can('create_events')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                                    <i class="fas fa-plus"></i>Add Event
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
                    @if(isset($events) && $events->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Event Modal with Validation - Only if user has create_events permission -->
            @admin_can('create_events')
            <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-plus"></i>
                                <span class="modal-title-stack">
                                    <span>New Event</span>
                                    <span class="modal-subtitle">Add a clear event record with schedule, location, attendees, type, and image.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="addEventForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif" 
                                                  name="description" rows="4" placeholder="Write event description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Date and Time -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('event_date') is-invalid @enderror @endif" 
                                               name="event_date" value="{{ session('form_type') == 'add' ? old('event_date') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('event_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('start_time') is-invalid @enderror @endif" 
                                               name="start_time" value="{{ session('form_type') == 'add' ? old('start_time') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('end_time') is-invalid @enderror @endif" 
                                               name="end_time" value="{{ session('form_type') == 'add' ? old('end_time') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif" 
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="Venue or address" required>
                                        @if(session('form_type') == 'add')
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Attendees</label>
                                        <input type="number" class="form-control @if(session('form_type') == 'add') @error('attendees') is-invalid @enderror @endif" 
                                               name="attendees" value="{{ session('form_type') == 'add' ? old('attendees', 0) : 0 }}" min="0">
                                        @if(session('form_type') == 'add')
                                            @error('attendees')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('type') is-invalid @enderror @endif" 
                                                name="type" required>
                                            <option value="">Select type</option>
                                            <option value="upcoming" {{ (session('form_type') == 'add' && old('type') == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                                            <option value="past" {{ (session('form_type') == 'add' && old('type') == 'past') ? 'selected' : '' }}>Past</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Event Image</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Event Banner/Image</label>
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
                                <button type="submit" form="addEventForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Event Modals with Validation - Only if user has update_events permission -->
            @if(auth('admin')->user()->hasPermission('update_events'))
                @foreach($events as $event)
                <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Event</span>
                                        <span class="modal-subtitle">Update event details, schedule, location, attendees, type, and image.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" id="editEventForm{{ $event->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $event->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $event->id ? old('title', $event->title) : $event->title }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('description') is-invalid @enderror @endif" 
                                                      name="description" rows="4" required>{{ session('form_type') == 'edit_' . $event->id ? old('description', $event->description) : $event->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Date and Time -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('event_date') is-invalid @enderror @endif" 
                                                   name="event_date" value="{{ session('form_type') == 'edit_' . $event->id ? old('event_date', $event->event_date->format('Y-m-d')) : $event->event_date->format('Y-m-d') }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('event_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('start_time') is-invalid @enderror @endif" 
                                                   name="start_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('start_time', $event->start_time) : $event->start_time }}">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('start_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">End Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('end_time') is-invalid @enderror @endif" 
                                                   name="end_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('end_time', $event->end_time) : $event->end_time }}">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('end_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Location -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('location') is-invalid @enderror @endif" 
                                                   name="location" value="{{ session('form_type') == 'edit_' . $event->id ? old('location', $event->location) : $event->location }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Attendees</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('attendees') is-invalid @enderror @endif" 
                                                   name="attendees" value="{{ session('form_type') == 'edit_' . $event->id ? old('attendees', $event->attendees) : $event->attendees }}" min="0">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('attendees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $event->id) @error('type') is-invalid @enderror @endif" 
                                                    name="type" required>
                                                <option value="">Select type</option>
                                                <option value="upcoming" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                <option value="past" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'past' ? 'selected' : '' }}>Past</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Image -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Event Image</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Event Banner/Image (Leave empty to keep current)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('image') is-invalid @enderror @endif" 
                                                   name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $event->id }}')">
                                            <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="editImagePreview{{ $event->id }}" class="mt-2">
                                                @if($event->image)
                                                    <img src="{{ asset($event->image) }}" alt="Current image" class="image-preview">
                                                    <small class="d-block text-muted">Current image</small>
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
                                    <button type="submit" form="editEventForm{{ $event->id }}" class="btn btn-primary" id="submitEditForm{{ $event->id }}">
                                        <i class="fas fa-save me-2"></i>Update Event
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($events as $event)
            <div class="modal fade" id="viewModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="modal-title-stack">
                                    <span>Event Details</span>
                                    <span class="modal-subtitle">Review event schedule, location, attendees, image, and description.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body event-view-body">
                            <div class="row g-3">
                                @if($event->image)
                                <div class="col-12 text-center">
                                    <div class="event-image-card">
                                        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="img-fluid rounded">
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-12">
                                    <h4 class="fw-bold event-detail-title">{{ $event->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Date</label>
                                    <p><i class="fas fa-calendar me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                                </div>
                                
                                @if($event->start_time && $event->end_time)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Time</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</p>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Location</label>
                                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $event->location }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Attendees</label>
                                    <p><span class="attendees-badge"><i class="fas fa-users me-1"></i>{{ number_format($event->attendees ?? 0) }}</span></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Type</label>
                                    <p>
                                        @if($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ $event->created_at ? $event->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-muted">Description</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($event->description)) !!}
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

            <!-- Global Image Zoom Modal -->
            <div class="modal fade" id="globalImageZoomModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editEventModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    
    <!-- SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Bulk Archive function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.event-checkbox:checked');
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
                        text: 'Please select at least one event to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one event to archive.');
                }
                return;
            }

            // Clear old ids so repeated bulk operations do not accumulate inputs.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected event(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} event(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.event-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
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
                    preview.innerHTML = `<img src="${e.target.result}" class="image-preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
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
            zoomImage.src = imageUrl;
            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { 
                fb = document.createElement('div'); 
                fb.className = 'invalid-feedback'; 
                el.insertAdjacentElement('afterend', fb); 
            }
            fb.textContent = msg;
        }
        
        function cf(el) { 
            if (el) {
                el.classList.remove('is-invalid');
                // Also remove any existing error message
                const fb = el.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.remove();
            }
        }

        function attachEventValidation(form) {
            if (!form) return;
            const title       = form.querySelector('[name="title"]');
            const description = form.querySelector('[name="description"]');
            const eventDate   = form.querySelector('[name="event_date"]');
            const location    = form.querySelector('[name="location"]');
            const type        = form.querySelector('[name="type"]');
            const image       = form.querySelector('[name="image"]');

            if (title) {
                title.removeEventListener('input', title._handler);
                title._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('input', title._handler);
                
                // Also validate on blur
                title.removeEventListener('blur', title._blurHandler);
                title._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('blur', title._blurHandler);
            }
            
            if (description) {
                description.removeEventListener('input', description._handler);
                description._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('input', description._handler);
                
                description.removeEventListener('blur', description._blurHandler);
                description._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('blur', description._blurHandler);
            }
            
            if (eventDate) {
                eventDate.removeEventListener('change', eventDate._handler);
                eventDate._handler = function() {
                    if (!this.value) {
                        sf(this, 'Event date is required.');
                    } else {
                        cf(this);
                    }
                };
                eventDate.addEventListener('change', eventDate._handler);
            }
            
            if (location) {
                location.removeEventListener('input', location._handler);
                location._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('input', location._handler);
                
                location.removeEventListener('blur', location._blurHandler);
                location._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('blur', location._blurHandler);
            }
            
            if (type) {
                type.removeEventListener('change', type._handler);
                type._handler = function() {
                    if (!this.value) {
                        sf(this, 'Please select an event type.');
                    } else {
                        cf(this);
                    }
                };
                type.addEventListener('change', type._handler);
            }
            
            if (image) {
                image.removeEventListener('change', image._handler);
                image._handler = function() {
                    if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                        sf(this, 'Image must be less than 5MB.'); 
                        this.value = '';
                    } else {
                        cf(this);
                    }
                };
                image.addEventListener('change', image._handler);
            }

            // Remove any existing submit listener
            form.removeEventListener('submit', form._submitHandler);
            
            form._submitHandler = function(e) {
                let valid = true;
                
                // Title validation
                if (title) {
                    if (!title.value.trim()) { 
                        sf(title, 'Title is required.'); 
                        valid = false; 
                    } else if (title.value.trim().length < 3) { 
                        sf(title, 'Title must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(title);
                    }
                }
                
                // Description validation
                if (description) {
                    if (!description.value.trim()) { 
                        sf(description, 'Description is required.'); 
                        valid = false; 
                    } else if (description.value.trim().length < 10) { 
                        sf(description, 'Description must be at least 10 characters.'); 
                        valid = false; 
                    } else {
                        cf(description);
                    }
                }
                
                // Event date validation
                if (eventDate && !eventDate.value) { 
                    sf(eventDate, 'Event date is required.'); 
                    valid = false; 
                } else if (eventDate && eventDate.value) {
                    cf(eventDate);
                }
                
                // Location validation
                if (location) {
                    if (!location.value.trim()) { 
                        sf(location, 'Location is required.'); 
                        valid = false; 
                    } else if (location.value.trim().length < 3) { 
                        sf(location, 'Location must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(location);
                    }
                }
                
                // Type validation
                if (type && !type.value) { 
                    sf(type, 'Please select an event type.'); 
                    valid = false; 
                } else if (type && type.value) {
                    cf(type);
                }
                
                // Image validation
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); 
                    valid = false;
                } else if (image) {
                    cf(image);
                }
                
                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };
            
            form.addEventListener('submit', form._submitHandler);
        }

        // Attach validation to add form
        attachEventValidation(document.getElementById('addEventForm'));
        
        // Attach validation to all edit forms
        document.querySelectorAll('[id^="editEventForm"]').forEach(attachEventValidation);
        
        // Also handle form reset when modal is closed
        const addModal = document.getElementById('addEventModal');
        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function () {
                const form = document.getElementById('addEventForm');
                if (form) {
                    form.reset();
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        }
        
        // Handle edit modals reset
        document.querySelectorAll('[id^="editEventModal"]').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');
            const checkboxes = () => Array.from(document.querySelectorAll('.event-checkbox'));

            function updateSelectedCount() {
                if (!selectedCount) {
                    return;
                }

                const count = checkboxes().filter(cb => cb.checked).length;
                selectedCount.innerHTML = count ? `<i class="fas fa-square-check me-1"></i>${count} event${count === 1 ? '' : 's'} selected` : '<i class="fas fa-square-check me-1"></i>No events selected';

                if (selectAll) {
                    const total = checkboxes().length;
                    selectAll.checked = total > 0 && count === total;
                    selectAll.indeterminate = count > 0 && count < total;
                }
            }

            checkboxes().forEach(cb => cb.addEventListener('change', updateSelectedCount));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes().forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            updateSelectedCount();

            document.querySelectorAll('.events-alert').forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 250);
                }, 4500);
            });
        });
    </script>
</body>
</html>

