<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incident Reports - Admin</title>
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

        .is-invalid ~ .invalid-feedback {
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

        .badge.bg-danger-subtle {
            background: var(--primary-light) !important;
            color: var(--primary);
        }

        .badge.bg-warning-subtle {
            background: var(--warning-light) !important;
            color: var(--warning);
        }

        .badge.bg-info-subtle {
            background: var(--info-light) !important;
            color: var(--info);
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

        .btn-success {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-success:hover {
            background: #1e5e23;
            border-color: #1e5e23;
        }

        .btn-danger {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-danger:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
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

        .modal-body strong {
            color: #495057;
            font-size: 0.9rem;
        }

        .modal-body p {
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .modal-body hr {
            margin: 1rem 0;
            border-top: 1px dashed var(--border-color);
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

        /* Witness Card Styling */
        .witness-card {
            background: #f8f9fa;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .witness-card p {
            margin-bottom: 0.5rem;
        }

        .witness-card:last-child {
            margin-bottom: 0;
        }

        /* Confidentiality Badge */
        .confidentiality-badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 6px;
        }

        .confidentiality-low {
            background: var(--success-light);
            color: var(--success);
        }

        .confidentiality-medium {
            background: var(--warning-light);
            color: var(--warning);
        }

        .confidentiality-high {
            background: var(--primary-light);
            color: var(--primary);
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

        .action-buttons {
            display: flex;
            gap: 0.35rem;
            justify-content: flex-end;
            align-items: center;
        }

        .action-btn {
            min-height: 34px;
            border-radius: 10px;
            border: 1px solid #4a4f57;
            background: #2f343b;
            color: #f1f3f5;
            padding: 0 0.55rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }

        .action-btn.icon-only {
            width: 34px;
            padding: 0;
            gap: 0;
        }

        .action-label {
            font-size: 0.73rem;
            font-weight: 600;
            line-height: 1;
        }

        .action-btn.action-view {
            background: #2a2f36;
            border-color: #4a4f57;
            color: #ffffff;
        }

        .action-btn.action-update {
            background: #d5f0ad;
            border-color: #a9d772;
            color: #2f4a11;
        }

        .action-btn.action-docs {
            background: #d7ecff;
            border-color: #95c5f2;
            color: #0a4d86;
        }

        .action-btn.action-archive {
            background: #ffdfe2;
            border-color: #f1a8af;
            color: #9f1f2e;
        }

        .action-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .action-divider {
            color: #9aa0a6;
            font-size: 0.9rem;
            line-height: 1;
            user-select: none;
        }

        .view-modal-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .view-modal-avatar {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            background: #e5f4ff;
            color: #0288d1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .view-modal-meta {
            min-width: 0;
        }

        .view-modal-name {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.2;
            color: #ffffff;
        }

        .view-modal-sub {
            font-size: 0.82rem;
            opacity: 0.9;
            margin-top: 2px;
        }

        .view-modal-refrow {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .view-modal-tag {
            font-size: 0.82rem;
            font-weight: 500;
            color: inherit;
            opacity: 0.9;
            line-height: 1.2;
        }

        .view-tabs {
            border-bottom: 1px solid var(--border-color);
            margin: 0 -1.5rem 1rem;
            padding: 0 1.5rem;
            gap: 0.25rem;
        }

        .view-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            border-radius: 0;
            color: #6c757d;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            background: transparent;
        }

        .view-tabs .nav-link.active {
            color: #1e293b;
            border-bottom-color: #1e293b;
            background: transparent;
        }

        .quick-edit-form .form-control,
        .quick-edit-form .form-select {
            min-height: 44px;
        }

        .quick-edit-form textarea.form-control {
            min-height: 92px;
            resize: vertical;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .status-option {
            border: 1px solid #d0d5dd;
            background: #fff;
            border-radius: 12px;
            padding: 0.5rem 0.6rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #344054;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.4rem;
            text-align: left;
        }

        .status-option.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.14);
            color: var(--primary-dark);
        }

        .status-option .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .notify-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.5rem 0.65rem;
            margin-bottom: 0.45rem;
        }

        .notify-label {
            line-height: 1.2;
        }

        .notify-label small {
            color: #6c757d;
            display: block;
        }

        .notify-switch.form-check.form-switch {
            margin-bottom: 0;
            padding-left: 0;
        }

        .notify-switch .form-check-input {
            margin-left: 0;
            width: 2.1rem;
            height: 1.1rem;
        }
    /* ==========================================================
           UI/UX REDESIGN OVERRIDE - Incident Reports adopted from Residency design
           Consistent with the redesigned Dashboard/Residents pages.
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
            --shadow-xs: 0 1px 2px rgba(16, 24, 40, .05);
            --shadow-sm: 0 8px 22px rgba(16, 24, 40, .06);
            --shadow-md: 0 18px 42px rgba(16, 24, 40, .10);
            --radius-xl: 22px;
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 11px;
        }

        html {
            font-size: 70% !important;
            scroll-behavior: smooth;
        }

        body {
            background: var(--page-bg) !important;
            color: var(--text) !important;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }

        .min-w-0 { min-width: 0 !important; }
        .residency-hero-icon,
        .stat-icon,
        .table-head-icon,
        .modal-heading-icon,
        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            padding: 0;
            color: var(--text);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow-xs);
        }

        .mobile-menu-btn:hover {
            color: var(--primary-dark);
            background: #fffafa;
            border-color: rgba(211,47,47,.22);
        }

        .profile-badge {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .38rem .5rem .38rem .78rem !important;
            background: var(--card) !important;
            border: 1px solid var(--line);
            border-radius: 999px !important;
            box-shadow: var(--shadow-xs);
            color: var(--text) !important;
            font-weight: 700;
        }

        #userDropdown { color: var(--text) !important; }

        .role-badge {
            padding: .28rem .65rem !important;
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 999px !important;
            font-size: .72rem !important;
            font-weight: 850 !important;
        }

        .residency-page {
            width: 100%;
            max-width: 1380px;
            margin: 0 auto;
        }

        .residency-alert,
        .alert {
            border: 0 !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            color: #14532d !important;
            background: var(--success-soft) !important;
            border: 1px solid rgba(22,128,60,.14) !important;
        }

        .alert-danger {
            color: #991b1b !important;
            background: var(--danger-soft) !important;
            border: 1px solid rgba(220,38,38,.16) !important;
        }

        .residency-hero,
        .stat-card,
        .residency-filter-card,
        .residency-table-card,
        .modal-content {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .residency-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1rem, 1.8vw, 1.6rem);
            margin-bottom: 1rem;
        }

        .residency-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 96% 0%, rgba(211,47,47,.11), transparent 31%),
                radial-gradient(circle at 10% 100%, rgba(3,105,161,.08), transparent 30%),
                linear-gradient(135deg, rgba(211,47,47,.04), transparent 42%);
            pointer-events: none;
        }

        .residency-hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
        }

        .residency-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.14);
            border-radius: 15px;
            font-size: 1.05rem;
        }

        .residency-kicker,
        .table-kicker,
        .modal-section-title {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .residency-hero h2 {
            margin: .45rem 0 .4rem;
            color: var(--text);
            font-size: clamp(1.55rem, 2.4vw, 2.25rem);
            font-weight: 850;
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .residency-hero p {
            max-width: 790px;
            margin: 0;
            color: var(--muted);
            font-size: .93rem;
            line-height: 1.6;
        }

        .residency-hero-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .residency-hero-meta {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: 1rem;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 700;
        }

        .residency-hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            padding: .42rem .68rem;
            background: rgba(255,255,255,.75);
            border: 1px solid var(--line);
            border-radius: 999px;
        }

        .residency-stats {
            margin-bottom: 1rem !important;
        }

        .stat-card {
            display: flex !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            gap: .9rem;
            padding: 1rem !important;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease !important;
        }

        .stat-card::before { display: none !important; }

        .stat-card:hover,
        .residency-filter-card:hover,
        .residency-table-card:hover {
            transform: translateY(-1px) !important;
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stat-label {
            color: var(--muted) !important;
            font-size: .73rem !important;
            font-weight: 850 !important;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .stat-number {
            color: var(--text) !important;
            font-size: clamp(1.5rem, 2.2vw, 2.05rem) !important;
            font-weight: 850 !important;
            line-height: 1 !important;
            letter-spacing: -.055em;
        }

        .stat-card small {
            display: inline-flex !important;
            align-items: center;
            gap: .32rem;
            margin-top: .5rem !important;
            color: var(--muted) !important;
            font-size: .78rem !important;
            font-weight: 650;
        }

        .stat-icon {
            flex: 0 0 auto;
            width: 44px !important;
            height: 44px !important;
            color: var(--primary) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px !important;
            font-size: 1rem !important;
        }

        .residency-stats > div:nth-child(2) .stat-icon { color: var(--warning) !important; background: var(--warning-soft) !important; border-color: #fed7aa; }
        .residency-stats > div:nth-child(3) .stat-icon { color: var(--success) !important; background: var(--success-soft) !important; border-color: rgba(22,128,60,.14); }
        .residency-stats > div:nth-child(4) .stat-icon { color: var(--danger) !important; background: var(--danger-soft) !important; border-color: rgba(220,38,38,.14); }

        .residency-filter-card {
            overflow: hidden;
            margin-bottom: 1rem !important;
        }

        .residency-filter-card .card-body {
            padding: 1rem !important;
        }

        .filter-title-block h6 {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin: 0;
            color: var(--text);
            font-size: .98rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .filter-title-block p {
            margin: .25rem 0 0;
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
            line-height: 1.45;
        }

        .filter-title-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 12px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            min-height: 42px;
            color: var(--text) !important;
            background-color: #ffffff !important;
            border: 1px solid var(--line-strong) !important;
            border-radius: 12px !important;
            font-size: .88rem !important;
            font-weight: 650;
        }

        .input-group .input-group-text {
            border-radius: 12px 0 0 12px !important;
            border-right: 0 !important;
        }

        .input-group .form-control {
            border-left: 0 !important;
            border-radius: 0 !important;
        }

        .input-group .btn {
            border-radius: 0 12px 12px 0 !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(211,47,47,.45) !important;
            outline: 3px solid rgba(211,47,47,.16) !important;
            outline-offset: 1px;
            box-shadow: none !important;
        }

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
            margin-bottom: .38rem;
        }

        textarea.form-control {
            min-height: 96px;
            line-height: 1.55;
            resize: vertical;
        }

        .btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: .48rem;
            min-height: 42px;
            padding: .65rem .95rem !important;
            border-radius: 12px !important;
            font-weight: 800 !important;
            letter-spacing: -.01em;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease, border-color .16s ease, color .16s ease !important;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary,
        .btn-danger,
        .btn-success {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211,47,47,.18);
        }

        .btn-primary:hover,
        .btn-danger:hover,
        .btn-success:hover {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }

        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-light-soft {
            color: var(--text) !important;
            background: var(--card) !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-light-soft:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211,47,47,.22) !important;
        }

        .btn-outline-danger {
            color: var(--danger) !important;
            background: var(--card) !important;
            border: 1px solid rgba(220,38,38,.24) !important;
        }

        .btn-outline-danger:hover {
            color: #ffffff !important;
            background: var(--danger) !important;
            border-color: var(--danger) !important;
        }

        .btn-outline-success {
            color: var(--success) !important;
            background: var(--card) !important;
            border: 1px solid rgba(22,128,60,.24) !important;
        }

        .btn-outline-success:hover {
            color: #ffffff !important;
            background: var(--success) !important;
            border-color: var(--success) !important;
        }

        .residency-table-card {
            overflow: hidden;
        }

        .residency-table-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, #ffffff, #fbfdff);
        }

        .table-head-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .table-head-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
        }

        .residency-table-head h3 {
            margin: .15rem 0 .12rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .residency-table-head p {
            margin: 0;
            color: var(--muted);
            font-size: .84rem;
            font-weight: 600;
            line-height: 1.45;
        }

        .table-count-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            flex: 0 0 auto;
            width: fit-content;
            padding: .42rem .68rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 850;
            white-space: nowrap;
        }

        .table-responsive {
            border-radius: 0 !important;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 1060px !important;
            margin-bottom: 0 !important;
            font-size: .86rem !important;
        }

        .table thead th {
            padding: .85rem .95rem !important;
            color: #475467 !important;
            background: #f9fafb !important;
            border-bottom: 1px solid var(--line) !important;
            font-size: .72rem !important;
            font-weight: 850 !important;
            text-transform: uppercase;
            letter-spacing: .07em !important;
            white-space: nowrap !important;
        }

        .table thead th a {
            color: #475467 !important;
        }

        .table tbody td {
            vertical-align: middle;
            padding: .95rem !important;
            color: var(--text) !important;
            border-bottom: 1px solid var(--line) !important;
            font-size: .86rem !important;
            line-height: 1.45 !important;
            white-space: nowrap !important;
        }

        .table tbody tr {
            transition: background .16s ease;
        }

        .table tbody tr:hover {
            background: #fcfcfd !important;
        }

        .table .fw-semibold,
        .table .fw-bold {
            color: var(--text) !important;
            font-weight: 820 !important;
        }

        .badge {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: fit-content;
            padding: .42rem .62rem !important;
            border-radius: 999px !important;
            font-size: .73rem !important;
            font-weight: 820 !important;
            white-space: nowrap;
        }

        .badge.bg-success-subtle,
        .badge.text-success { color: var(--success) !important; background: var(--success-soft) !important; border: 1px solid rgba(22,128,60,.14); }
        .badge.bg-danger-subtle,
        .badge.text-danger { color: var(--danger) !important; background: var(--danger-soft) !important; border: 1px solid rgba(220,38,38,.14); }
        .badge.bg-warning-subtle,
        .badge.text-warning { color: #9a3412 !important; background: var(--warning-soft) !important; border: 1px solid #fed7aa; }
        .badge.bg-info-subtle,
        .badge.text-info { color: var(--info) !important; background: var(--info-soft) !important; border: 1px solid #dbeafe; }
        .badge.bg-secondary-subtle,
        .badge.text-secondary { color: #475467 !important; background: #f9fafb !important; border: 1px solid var(--line); }
        .badge.bg-primary-subtle,
        .badge.text-primary { color: var(--primary-dark) !important; background: var(--primary-soft) !important; border: 1px solid rgba(211,47,47,.12); }
        .badge.bg-light { color: #344054 !important; background: #f9fafb !important; border: 1px solid var(--line) !important; }

        .action-buttons {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            flex-wrap: nowrap !important;
            gap: .35rem !important;
            padding: .25rem;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .action-divider { display: none !important; }

        .action-btn {
            min-width: 36px !important;
            min-height: 36px !important;
            padding: 0 .62rem !important;
            border-radius: 11px !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: none !important;
            font-weight: 850 !important;
        }

        .action-btn.icon-only {
            width: 36px !important;
            padding: 0 !important;
        }

        .action-btn.action-view {
            color: var(--info) !important;
            background: var(--info-soft) !important;
            border-color: #dbeafe !important;
        }

        .action-btn.action-update {
            color: var(--success) !important;
            background: var(--success-soft) !important;
            border-color: rgba(22,128,60,.18) !important;
        }

        .action-btn.action-docs {
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border-color: rgba(211,47,47,.14) !important;
        }

        .action-btn.action-archive {
            color: var(--danger) !important;
            background: var(--danger-soft) !important;
            border-color: rgba(220,38,38,.16) !important;
        }

        .action-btn:disabled {
            opacity: .45 !important;
            filter: grayscale(.15);
            cursor: not-allowed;
        }

        .pagination {
            gap: .35rem !important;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            min-width: 36px;
            border-radius: 11px !important;
            border: 1px solid var(--line) !important;
            color: var(--muted) !important;
            background: #ffffff !important;
            font-weight: 800;
            box-shadow: var(--shadow-xs);
        }

        .page-link:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211,47,47,.22) !important;
        }

        .page-item.active .page-link {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        /* Modal redesign */
        .modal-backdrop.show { opacity: .42; }

        .modal-dialog {
            padding: .75rem;
        }

        .modal-content {
            overflow: hidden;
            border: 0 !important;
            box-shadow: 0 24px 70px rgba(16,24,40,.18) !important;
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem !important;
            color: var(--text) !important;
            background:
                radial-gradient(circle at top right, rgba(211,47,47,.08), transparent 34%),
                linear-gradient(180deg, #ffffff, #fbfdff) !important;
            border-bottom: 1px solid var(--line) !important;
            border-radius: 0 !important;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin: 0 !important;
            color: var(--text) !important;
            font-size: 1.05rem !important;
            font-weight: 850 !important;
            letter-spacing: -.025em;
        }

        .modal-title i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 13px;
        }

        .modal-header .btn-close {
            width: 38px;
            height: 38px;
            margin: 0 !important;
            padding: 0 !important;
            background-color: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            filter: none !important;
            opacity: 1 !important;
            box-shadow: var(--shadow-xs);
        }

        .modal-body {
            padding: 1rem !important;
            background: #ffffff;
            max-height: min(72vh, 760px) !important;
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: .55rem;
            padding: 1rem !important;
            background: #ffffff;
            border-top: 1px solid var(--line) !important;
        }

        .modal-body h6,
        .modal-section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 1rem 0 .75rem !important;
            padding: .72rem .85rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
            font-size: .82rem !important;
            font-weight: 850 !important;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .modal-body h6:first-child { margin-top: 0 !important; }

        .modal-body p {
            margin-bottom: .7rem !important;
            padding: .65rem .75rem !important;
            color: var(--text) !important;
            background: #f9fafb;
            border: 1px solid var(--line) !important;
            border-radius: 12px;
        }

        .modal-body strong {
            color: #344054 !important;
            font-size: .84rem !important;
            font-weight: 850 !important;
        }

        .view-modal-name { color: var(--text) !important; }
        .view-modal-sub { color: var(--muted) !important; opacity: 1 !important; }

        .view-modal-avatar {
            color: var(--primary) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211,47,47,.12);
        }

        .view-tabs {
            margin: 0 0 1rem !important;
            padding: .35rem !important;
            border: 1px solid var(--line) !important;
            border-radius: 999px;
            background: #f9fafb;
        }

        .view-tabs .nav-link {
            border: 0 !important;
            border-radius: 999px !important;
            color: var(--muted) !important;
            font-weight: 850 !important;
        }

        .view-tabs .nav-link.active {
            color: #ffffff !important;
            background: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211,47,47,.18);
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .6rem !important;
        }

        .status-option {
            min-height: 48px;
            padding: .68rem .75rem !important;
            color: var(--text) !important;
            background: #ffffff !important;
            border: 1px solid var(--line) !important;
            border-radius: 14px !important;
            font-size: .84rem !important;
            font-weight: 820 !important;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease, background .16s ease;
        }

        .status-option:hover {
            transform: translateY(-1px);
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .status-option.active {
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border-color: rgba(211,47,47,.28) !important;
            box-shadow: 0 0 0 3px rgba(211,47,47,.10) !important;
        }

        .notify-row {
            padding: .7rem .78rem !important;
            background: #ffffff;
            border: 1px solid var(--line) !important;
            border-radius: 14px !important;
        }

        .invalid-feedback {
            color: var(--danger) !important;
            font-size: .76rem !important;
            font-weight: 760;
        }

        .is-invalid {
            border-color: var(--danger) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            outline-color: rgba(220,38,38,.16) !important;
            box-shadow: none !important;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .dropdown-menu {
            border: 1px solid var(--line) !important;
            border-radius: 14px !important;
            box-shadow: var(--shadow-sm) !important;
            padding: .45rem !important;
        }

        .dropdown-item {
            border-radius: 10px !important;
            font-weight: 650;
        }

        .dropdown-item:hover {
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
        }

        @media (max-width: 1199px) {
            .status-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 991px) {
            .residency-hero-content { grid-template-columns: minmax(0, 1fr); }
            .residency-hero-actions { justify-content: flex-start; }
            .residency-table-head { flex-direction: column; }
            .table-count-pill { align-self: flex-start; }
            .profile-badge span:not(.role-badge) { display: none; }
        }

        @media (max-width: 768px) {
            html { font-size: 78% !important; }
            .mobile-menu-btn { display: inline-flex !important; }
            .title-icon, .mobile-menu-btn { width: 38px; height: 38px; }
            .profile-badge { display: none !important; }
            main.p-3, main.p-lg-4 { padding: .85rem !important; }
            .residency-page { max-width: 100%; }
            .residency-hero, .stat-card, .residency-filter-card, .residency-table-card, .modal-content { border-radius: 16px !important; }
            .residency-stats > div { margin-bottom: .75rem; }
            .residency-filter-card .d-flex.gap-2 { width: 100%; }
            .residency-filter-card .d-flex.gap-2 .btn,
            .residency-filter-card form[id="bulkForm"],
            .residency-filter-card form[id="exportForm"] { flex: 1 1 140px; }
            .residency-filter-card form[id="bulkForm"] .btn,
            .residency-filter-card form[id="exportForm"] .btn { width: 100%; }
            .table { min-width: 980px !important; }
            .modal-dialog { padding: .45rem; }
            .modal-body, .modal-header, .modal-footer { padding: .9rem !important; }
            .modal-footer, .modal-footer .btn { width: 100%; }
            .modal-footer { flex-direction: column-reverse; }
            .status-grid { grid-template-columns: minmax(0, 1fr); }
            .view-tabs { border-radius: 16px; overflow-x: auto; flex-wrap: nowrap; }
            .view-tabs .nav-link { white-space: nowrap; }
        }

        @media (max-width: 575px) {
            .residency-hero h2 { font-size: 1.55rem; }
            .residency-hero-actions .btn { width: 100%; }
            .stat-card { min-height: auto; }
            .action-label { display: none !important; }
            .action-btn.action-update { width: 36px !important; padding: 0 !important; }
        }


        /* ==========================================================
           SIMPLIFIED RESIDENCY MANAGEMENT UX PATCH
           Shows only high-value information in the table and moves
           full details into cleaner modals.
        ========================================================== */
        .residency-table-card .table {
            min-width: 780px !important;
        }

        .application-primary {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 240px;
        }

        .application-avatar {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.14);
            border-radius: 14px;
            font-weight: 850;
            letter-spacing: -.03em;
        }

        .application-name {
            color: var(--text);
            font-weight: 850;
            line-height: 1.25;
        }

        .application-ref,
        .application-muted-line {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .35rem;
            margin-top: .18rem;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.35;
        }

        .ref-pill {
            display: inline-flex;
            align-items: center;
            gap: .32rem;
            width: fit-content;
            max-width: 100%;
            padding: .28rem .5rem;
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 850;
            white-space: nowrap;
        }

        .info-stack {
            display: grid;
            gap: .25rem;
            min-width: 150px;
        }

        .info-label-mini {
            color: var(--muted-2);
            font-size: .68rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .075em;
        }

        .info-main {
            color: var(--text);
            font-size: .86rem;
            font-weight: 820;
            line-height: 1.3;
        }

        .info-sub {
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.35;
        }

        .status-cell {
            display: grid;
            gap: .34rem;
            justify-items: start;
        }

        .status-date {
            color: var(--muted);
            font-size: .74rem;
            font-weight: 650;
        }

        .action-buttons {
            gap: .42rem !important;
        }

        .action-divider {
            display: none !important;
        }

        .action-btn {
            min-height: 36px !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .action-btn.action-view {
            color: var(--text) !important;
            background: #ffffff !important;
            border-color: var(--line-strong) !important;
        }

        .action-btn.action-update {
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border-color: rgba(211,47,47,.18) !important;
        }

        .action-btn.action-docs {
            color: var(--info) !important;
            background: var(--info-soft) !important;
            border-color: #dbeafe !important;
        }

        .action-btn.action-archive {
            color: var(--danger) !important;
            background: var(--danger-soft) !important;
            border-color: rgba(220,38,38,.16) !important;
        }

        .application-modal-summary {
            display: grid;
            grid-template-columns: 1.35fr repeat(3, minmax(0, .75fr));
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .modal-summary-card,
        .detail-section-card,
        .document-preview-card,
        .status-flow-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow-xs);
        }

        .modal-summary-card {
            display: grid;
            align-content: start;
            gap: .25rem;
            padding: .85rem;
        }

        .modal-summary-card.is-primary {
            background: linear-gradient(135deg, var(--primary-soft), #ffffff 58%);
            border-color: rgba(211,47,47,.16);
        }

        .modal-summary-label {
            color: var(--muted);
            font-size: .7rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .modal-summary-value {
            color: var(--text);
            font-size: .95rem;
            font-weight: 850;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .modal-summary-help {
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.35;
        }

        .detail-section-card {
            padding: .95rem;
            margin-bottom: .85rem;
        }

        .detail-section-title {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin-bottom: .75rem;
            color: var(--text);
            font-size: .9rem;
            font-weight: 850;
        }

        .detail-section-title i {
            color: var(--primary);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
        }

        .detail-item {
            padding: .72rem;
            background: #fbfdff;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .detail-label {
            display: block;
            margin-bottom: .18rem;
            color: var(--muted);
            font-size: .69rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .075em;
        }

        .detail-value {
            color: var(--text);
            font-size: .86rem;
            font-weight: 750;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .document-preview-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .document-preview-card {
            overflow: hidden;
        }

        .document-preview-card .doc-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            padding: .72rem .8rem;
            border-bottom: 1px solid var(--line);
            font-weight: 850;
            color: var(--text);
        }

        .document-preview-card .doc-body {
            display: grid;
            place-items: center;
            min-height: 190px;
            padding: .8rem;
            background: #f9fafb;
        }

        .document-preview-card img {
            max-height: 210px;
            width: auto;
            max-width: 100%;
            border-radius: 14px;
            box-shadow: var(--shadow-sm);
            cursor: zoom-in;
            object-fit: contain;
        }

        .empty-doc-note {
            display: grid;
            place-items: center;
            gap: .35rem;
            color: var(--muted);
            text-align: center;
            min-height: 140px;
            font-size: .82rem;
            font-weight: 650;
        }

        .empty-doc-note i {
            font-size: 1.5rem;
            color: var(--muted-2);
        }

        .modal-body h6.fw-semibold.text-primary {
            display: flex;
            align-items: center;
            gap: .42rem;
            margin: .25rem 0 .65rem;
            padding: .65rem .75rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
            font-size: .84rem;
            font-weight: 850 !important;
        }

        .modal-body h6.fw-semibold.text-primary::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--primary);
        }

        .status-flow-card {
            padding: .85rem;
            margin-bottom: .85rem;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
        }

        .status-flow-person {
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .status-flow-person .application-avatar {
            width: 38px;
            height: 38px;
            border-radius: 13px;
            font-size: .78rem;
        }

        .status-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: .55rem !important;
        }

        .status-option {
            min-height: 52px;
            padding: .72rem .78rem !important;
            border-radius: 14px !important;
            transition: transform .16s ease, border-color .16s ease, background .16s ease;
        }

        .status-option:hover {
            transform: translateY(-1px);
            border-color: rgba(211,47,47,.24);
            background: #fffafa;
        }

        .status-option.active {
            color: var(--primary-dark);
            background: var(--primary-soft) !important;
            border-color: rgba(211,47,47,.36) !important;
        }

        @media (max-width: 991px) {
            .application-modal-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .detail-grid,
            .document-preview-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 768px) {
            .residency-table-card .table {
                min-width: 720px !important;
            }
            .application-primary {
                min-width: 210px;
            }
            .application-modal-summary,
            .status-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
            .modal-summary-card {
                min-height: auto;
            }
        }


        /* ==========================================================
           EDIT INFO MODAL UX PATCH
           Cleaner, card-based edit form inside the View modal.
        ========================================================== */
        .edit-info-pane {
            animation: fadeEditPanel .18s ease both;
        }

        @keyframes fadeEditPanel {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .edit-info-banner {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: .75rem;
            align-items: center;
            margin-bottom: .9rem;
            padding: .85rem;
            background:
                radial-gradient(circle at top right, rgba(211,47,47,.08), transparent 34%),
                linear-gradient(180deg, #ffffff, #fbfdff);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow-xs);
        }

        .edit-info-banner .application-avatar {
            width: 46px;
            height: 46px;
            border-radius: 15px;
        }

        .edit-info-title {
            color: var(--text);
            font-size: .98rem;
            font-weight: 850;
            line-height: 1.25;
            letter-spacing: -.02em;
        }

        .edit-info-subtitle {
            margin-top: .18rem;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 650;
            line-height: 1.45;
        }

        .modern-edit-form {
            display: grid;
            gap: .85rem;
            padding: .95rem;
            background: #fbfdff;
            border: 1px solid var(--line);
            border-radius: 20px;
        }

        .edit-section-card {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow-xs);
        }

        .edit-section-head {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .85rem .9rem;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            border-bottom: 1px solid var(--line);
        }

        .edit-section-icon {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 13px;
        }

        .edit-section-title {
            margin: .05rem 0 .12rem;
            color: var(--text);
            font-size: .92rem;
            font-weight: 850;
            letter-spacing: -.018em;
        }

        .edit-section-help {
            margin: 0;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 650;
            line-height: 1.45;
        }

        .edit-section-body {
            padding: .9rem;
        }

        .edit-form-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: .75rem;
        }

        .edit-field {
            min-width: 0;
        }

        .edit-col-3 { grid-column: span 3; }
        .edit-col-4 { grid-column: span 4; }
        .edit-col-6 { grid-column: span 6; }
        .edit-col-12 { grid-column: span 12; }

        .edit-field .form-label {
            display: flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: .38rem;
            color: #344054;
            font-size: .78rem;
            font-weight: 850 !important;
            letter-spacing: -.005em;
        }

        .edit-field .form-label i {
            color: var(--primary);
            font-size: .8rem;
        }
        
        .form-control[type="file"] {
            padding: 1rem;
        }

        .edit-field .form-control,
        .edit-field .form-select {
            width: 100%;
            min-height: 44px;
            border-radius: 13px !important;
        }

        .edit-field textarea.form-control {
            min-height: 96px;
        }

        .edit-field small {
            display: block;
            margin-top: .35rem;
            color: var(--muted);
            font-size: .74rem;
            font-weight: 650;
            line-height: 1.35;
        }

        .edit-file-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: .45rem;
            padding: .75rem;
            background: #f9fafb;
            border: 1px dashed var(--line-strong);
            border-radius: 16px;
        }

        .edit-current-file {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            width: fit-content;
            max-width: 100%;
            padding: .38rem .58rem;
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid #dbeafe;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 850;
            overflow-wrap: anywhere;
        }

        .edit-required-note {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            width: fit-content;
            margin-top: .15rem;
            padding: .5rem .65rem;
            color: #9a3412;
            background: var(--warning-soft);
            border: 1px solid #fed7aa;
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 760;
        }

        .modal-footer[id^="viewFooterEdit"] .btn-primary {
            min-width: 150px;
        }

        @media (max-width: 991px) {
            .edit-col-3,
            .edit-col-4 {
                grid-column: span 6;
            }
        }

        @media (max-width: 768px) {
            .edit-info-banner,
            .modern-edit-form,
            .edit-section-card {
                border-radius: 16px;
            }

            .modern-edit-form {
                padding: .75rem;
            }

            .edit-section-head,
            .edit-section-body {
                padding: .8rem;
            }

            .edit-col-3,
            .edit-col-4,
            .edit-col-6,
            .edit-col-12 {
                grid-column: span 12;
            }
        }

    

        /* ==========================================================
           INCIDENT REPORT MANAGEMENT UX PATCH
           Keeps the incident data, but uses the Residency-style compact table.
        ========================================================== */
        .incident-page .residency-hero-icon,
        .incident-page .table-head-icon {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .incident-table-card .table {
            min-width: 820px !important;
        }

        .incident-type-pill,
        .confidentiality-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            width: fit-content;
            max-width: 100%;
            padding: .35rem .6rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 850;
            line-height: 1.15;
            white-space: nowrap;
        }

        .incident-type-pill {
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid rgba(3,105,161,.14);
        }

        .confidentiality-pill.confidentiality-low,
        .confidentiality-pill.confidentiality-public {
            color: var(--success);
            background: var(--success-soft);
            border: 1px solid rgba(22,128,60,.14);
        }

        .confidentiality-pill.confidentiality-medium {
            color: var(--warning);
            background: var(--warning-soft);
            border: 1px solid #fed7aa;
        }

        .confidentiality-pill.confidentiality-high {
            color: var(--danger);
            background: var(--danger-soft);
            border: 1px solid rgba(220,38,38,.14);
        }

        .case-description-preview {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.4;
        }

        .party-stack {
            display: grid;
            gap: .28rem;
            min-width: 190px;
        }

        .party-line {
            display: flex;
            align-items: flex-start;
            gap: .35rem;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .party-line strong {
            color: var(--text);
            font-weight: 850;
        }

        .action-btn.action-restore {
            background: #d5f0ad;
            border-color: #a9d772;
            color: #2f4a11;
        }

        .incident-page .permission-denied {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }


        /* Incident modal cards adopted from the Residency modal design */
        .incident-file-grid,
        .incident-witness-list,
        .incident-edit-witness-list {
            display: grid;
            gap: .75rem;
        }

        .incident-file-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .incident-file-card,
        .incident-witness-card {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .78rem;
            background: #fbfdff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
        }

        .incident-file-icon,
        .incident-witness-avatar {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
            font-weight: 850;
        }

        .incident-file-info {
            flex: 1 1 auto;
        }

        .incident-file-name,
        .incident-witness-name {
            color: var(--text);
            font-size: .86rem;
            font-weight: 850;
            overflow-wrap: anywhere;
        }

        .incident-file-type,
        .incident-witness-meta,
        .incident-witness-statement {
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.4;
        }

        .incident-witness-card {
            align-items: flex-start;
        }

        .incident-edit-witness-list .witness-card {
            margin-bottom: .75rem !important;
        }

        @media (max-width: 768px) {
            .incident-file-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .incident-file-card {
                align-items: flex-start;
                flex-direction: column;
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
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="recordsSubmenu">
            @admin_can('view_clearance')
            <a href="{{ route('admin.clearance.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-contract"></i> <span>Clearances</span></a>
            @endadmin_can
            
            @admin_can('view_blotter')
            <a href="{{ route('admin.blotter.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-book"></i> <span>Incident Reports</span></a>
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
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="communitySubmenu">
            @admin_can('view_announcements')
            <a href="{{ route('admin.announcements.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
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
                <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-book"></i></span>
                    Incident Reports
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="residency-page incident-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="residency-hero">
                <div class="residency-hero-content">
                    <div class="min-w-0">
                        <div class="residency-hero-icon"><i class="fas fa-book-open"></i></div>
                        <div class="residency-kicker"><i class="fas fa-circle-check"></i> Incident report management</div>
                        <h2>Review incident reports with a cleaner, focused workspace.</h2>
                        <p>Keep the table easy to scan while still giving access to full case details, parties involved, evidence, witnesses, status updates, and archive actions from the modals.</p>
                    </div>

                    <div class="residency-hero-actions">
                        @admin_can('create_blotter')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                            <i class="fas fa-plus"></i>
                            New Incident
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.blotter.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset View
                        </a>
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Resolved: <strong>{{ $resolved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Dropped: <strong>{{ $dropped_count ?? 0 }}</strong></span>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 residency-stats">
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
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Processing</div>
                            <div class="stat-number">{{ $processing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Pending review
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Resolved</div>
                            <div class="stat-number">{{ $resolved_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>Completed
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
                            <div class="stat-label text-muted mb-1">Dropped</div>
                            <div class="stat-number">{{ $dropped_count ?? 0 }}</div>
                            <small class="text-danger mt-2 d-none d-sm-block">
                                <i class="fas fa-times-circle me-1"></i>Not pursued
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4 residency-filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.blotter.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Incident Reports</h6>
                                    <p>Search references, report type, parties involved, confidentiality, and status.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_blotter')
                                    <a href="#" class="btn btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Incident Report</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.blotter.index') }}" class="btn btn-light-soft flex-fill flex-md-grow-0">
                                        <i class="fas fa-rotate"></i><span class="d-none d-sm-inline ms-2">Reset</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search and Filter Row -->
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search reference or complainant..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="report_type" class="form-select">
                                    <option value="">All Report Type</option>
                                    <option value="dispute" {{ request('report_type') == 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                    <option value="security" {{ request('report_type') == 'security' ? 'selected' : '' }}>Security Concern</option>
                                    <option value="public" {{ request('report_type') == 'public' ? 'selected' : '' }}>Public Safety</option>
                                    <option value="other" {{ request('report_type') == 'other' ? 'selected' : '' }}>Other Concerns</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    @if(auth('admin')->user()->hasAnyPermission(['delete_blotter', 'export_blotter']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_blotter')
                        <form id="bulkForm" method="POST" action="{{ route('admin.blotter.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Archive</span>
                            </button>
                        </form>
                        @endadmin_can
                        
                        @admin_can('export_blotter')
                        <form id="exportForm" method="POST" action="{{ route('admin.blotter.export') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="exportCSV()" class="btn btn-outline-success d-flex align-items-center gap-2" title="Export CSV">
                                <i class="fas fa-file-csv"></i>
                                <span class="d-none d-sm-inline">Export</span>
                            </button>
                        </form>
                        @endadmin_can
                    </div>
                    @endif
                </div>
            </div>

            <!-- Incident Reports Table - Consistent Management View -->
            <div class="card border-0 residency-table-card incident-table-card">
                <div class="card-body p-0">
                    <div class="residency-table-head">
                        <div class="table-head-title">
                            <span class="table-head-icon"><i class="fas fa-list-check"></i></span>
                            <div>
                                <span class="table-kicker">Case queue</span>
                                <h3>Incident reports</h3>
                                <p>Only high-value case details are shown here. Open the modal for full statements, witnesses, evidence files, and complete history.</p>
                            </div>
                        </div>
                        <span class="table-count-pill"><i class="fas fa-book"></i>{{ $incidents->total() ?? 0 }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="blotterTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'reference_number', 'direction' => request('sort') === 'reference_number' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Incident / Reference
                                            @if(request('sort') == 'reference_number')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'complainant_name', 'direction' => request('sort') === 'complainant_name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Parties / Location
                                            @if(request('sort') == 'complainant_name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'confidentiality', 'direction' => request('sort') === 'confidentiality' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Classification
                                            @if(request('sort') == 'confidentiality')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Status / Submitted
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
                                @forelse($incidents as $blotter)
                                <tr class="{{ $blotter->deleted_at ? 'table-danger' : '' }}">
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $blotter->id }}" class="application-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="application-primary">
                                            <div class="application-avatar">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="application-name">{{ ucfirst(str_replace('_', ' ', $blotter->report_type)) }}</div>
                                                <div class="application-ref">
                                                    <span class="ref-pill"><i class="fas fa-hashtag"></i>{{ $blotter->reference_number }}</span>
                                                    <span>{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') : 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="party-stack">
                                            <div class="party-line"><i class="fas fa-user"></i><span>Complainant: <strong>{{ $blotter->complainant_name }}</strong></span></div>
                                            <div class="party-line"><i class="fas fa-user-shield"></i><span>Respondent: <strong>{{ $blotter->respondent_name ?: 'N/A' }}</strong></span></div>
                                            <div class="party-line"><i class="fas fa-location-dot"></i><span>{{ $blotter->location ?: 'No location set' }}</span></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Type</span>
                                            <span class="incident-type-pill"><i class="fas fa-triangle-exclamation"></i>{{ ucfirst(str_replace('_', ' ', $blotter->report_type)) }}</span>
                                            <span class="confidentiality-pill confidentiality-{{ $blotter->confidentiality }}">
                                                <i class="fas fa-lock"></i>{{ ucfirst($blotter->confidentiality) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-cell">
                                            @if($blotter->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger"><i class="fas fa-box-archive me-1"></i>Archived</span>
                                            @elseif($blotter->status == 'resolved')
                                                <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i>Resolved</span>
                                            @elseif($blotter->status == 'dropped')
                                                <span class="badge bg-danger-subtle text-danger"><i class="fas fa-times-circle me-1"></i>Dropped</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-clock me-1"></i>Processing</span>
                                            @endif
                                            <span class="status-date"><i class="fas fa-calendar-plus"></i>{{ $blotter->created_at ? $blotter->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-buttons">
                                            <button class="btn btn-sm action-btn action-view icon-only" data-bs-toggle="modal" data-bs-target="#viewModal{{ $blotter->id }}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <span class="action-divider">|</span>

                                            @if(!$blotter->deleted_at && (auth('admin')->user()->hasPermission('approve_blotter') || auth('admin')->user()->hasPermission('reject_blotter')))
                                            <button class="btn btn-sm action-btn action-update" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $blotter->id }}" title="Update">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-update" disabled title="Update (Unavailable)">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @endif

                                            <span class="action-divider">|</span>

                                            @if(!$blotter->deleted_at && auth('admin')->user()->hasPermission('delete_blotter'))
                                            <form method="POST" action="{{ route('admin.blotter.destroy', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this incident report?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm action-btn action-archive icon-only" title="Archive">
                                                    <i class="fas fa-box-archive"></i>
                                                </button>
                                            </form>
                                            @elseif($blotter->deleted_at && auth('admin')->user()->hasPermission('restore_blotter'))
                                            <form method="POST" action="{{ route('admin.blotter.restore', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this incident report?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm action-btn action-restore icon-only" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-archive icon-only" disabled title="Archive (Unavailable)">
                                                <i class="fas fa-box-archive"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state-box">
                                            <div class="empty-state-icon"><i class="fas fa-book"></i></div>
                                            <h5>No incident reports found</h5>
                                            <p>Try adjusting your search or filters, or add a new report.</p>
                                            @admin_can('create_blotter')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                                <i class="fas fa-plus me-2"></i>Add New
                                            </button>
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination - Mobile Responsive -->
                    @if(isset($incidents) && $incidents->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $incidents->firstItem() ?? 0 }}-{{ $incidents->lastItem() ?? 0 }} of {{ $incidents->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $incidents->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Incident Modal with Witnesses and Evidence - Only if user has create_blotter permission -->
            @admin_can('create_blotter')
            <div class="modal fade" id="addIncidentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                New Incident Report
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.blotter.store') }}" id="addIncidentForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Incident Details -->
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Incident Details</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Report Type <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('reportType') is-invalid @enderror @endif" 
                                                name="reportType" required>
                                            <option value="">Select type</option>
                                            <option value="dispute" {{ (session('form_type') == 'add' && old('reportType') == 'dispute') ? 'selected' : '' }}>Community Dispute</option>
                                            <option value="security" {{ (session('form_type') == 'add' && old('reportType') == 'security') ? 'selected' : '' }}>Security Concern</option>
                                            <option value="public" {{ (session('form_type') == 'add' && old('reportType') == 'public') ? 'selected' : '' }}>Public Safety</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('reportType') == 'other') ? 'selected' : '' }}>Other Concern</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('reportType')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Confidentiality <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('confidentiality') is-invalid @enderror @endif" 
                                                name="confidentiality" required>
                                            <option value="">Select confidentiality</option>
                                            <option value="low" {{ (session('form_type') == 'add' && old('confidentiality') == 'low') ? 'selected' : '' }}>Public Report</option>
                                            <option value="medium" {{ (session('form_type') == 'add' && old('confidentiality') == 'medium') ? 'selected' : '' }}>Confidential Report</option>
                                            <option value="high" {{ (session('form_type') == 'add' && old('confidentiality') == 'high') ? 'selected' : '' }}>Anonymous Report</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('confidentiality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('incidentDate') is-invalid @enderror @endif" 
                                            name="incidentDate" value="{{ session('form_type') == 'add' ? old('incidentDate') : '' }}" required max="{{ date('Y-m-d') }}">
                                        @if(session('form_type') == 'add')
                                            @error('incidentDate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('incidentTime') is-invalid @enderror @endif" 
                                            name="incidentTime" value="{{ session('form_type') == 'add' ? old('incidentTime') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('incidentTime')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('incidentLocation') is-invalid @enderror @endif" 
                                            name="incidentLocation" value="{{ session('form_type') == 'add' ? old('incidentLocation') : '' }}" placeholder="Incident location" required>
                                        @if(session('form_type') == 'add')
                                            @error('incidentLocation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('incidentDescription') is-invalid @enderror @endif" 
                                                name="incidentDescription" rows="3" placeholder="Describe the incident..." required>{{ session('form_type') == 'add' ? old('incidentDescription') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('incidentDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Immediate Action Taken</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('immediateAction') is-invalid @enderror @endif" 
                                                name="immediateAction" rows="2" placeholder="Any immediate action taken...">{{ session('form_type') == 'add' ? old('immediateAction') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('immediateAction')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Complainant Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Complainant Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('complainantName') is-invalid @enderror @endif" 
                                            name="complainantName" value="{{ session('form_type') == 'add' ? old('complainantName') : '' }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only." required>
                                        @if(session('form_type') == 'add')
                                            @error('complainantName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('complainantContact') is-invalid @enderror @endif" 
                                            name="complainantContact" value="{{ session('form_type') == 'add' ? old('complainantContact') : '' }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09." required>
                                        <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                        @if(session('form_type') == 'add')
                                            @error('complainantContact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('complainantAddress') is-invalid @enderror @endif" 
                                                name="complainantAddress" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'add' ? old('complainantAddress') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('complainantAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('complainantEmail') is-invalid @enderror @endif" 
                                            name="complainantEmail" value="{{ session('form_type') == 'add' ? old('complainantEmail') : '' }}" placeholder="email@example.com" required>
                                        @if(session('form_type') == 'add')
                                            @error('complainantEmail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Respondent Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Respondent Information (Optional)</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('respondentName') is-invalid @enderror @endif" 
                                            name="respondentName" value="{{ session('form_type') == 'add' ? old('respondentName') : '' }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                        @if(session('form_type') == 'add')
                                            @error('respondentName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Contact</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('respondentContact') is-invalid @enderror @endif" 
                                            name="respondentContact" value="{{ session('form_type') == 'add' ? old('respondentContact') : '' }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                        @if(session('form_type') == 'add')
                                            @error('respondentContact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Respondent Address</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('respondentAddress') is-invalid @enderror @endif" 
                                                name="respondentAddress" rows="2" placeholder="Complete address">{{ session('form_type') == 'add' ? old('respondentAddress') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('respondentAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Respondent Description</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('respondentDescription') is-invalid @enderror @endif" 
                                                name="respondentDescription" rows="2" placeholder="Description of respondent (if any)">{{ session('form_type') == 'add' ? old('respondentDescription') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('respondentDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Witnesses Section -->
                                    @if(auth('admin')->user()->hasPermission('add_witness'))
                                    <div class="col-12 mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="modal-section-title"><i class="fas fa-users"></i> Witnesses</div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWitnessField()">
                                                <i class="fas fa-plus me-1"></i>Add Witness
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mb-3">Add witnesses to support this incident report</small>
                                        
                                        <div id="witnesses-container">
                                            <!-- Witness fields will be added here dynamically -->
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Evidence Section -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary mb-3">Evidence Upload</h6>
                                        
                                        <!-- Photos -->
                                        <div class="mb-3">
                                            <label class="form-label">Photos (Max: 10MB each, JPG/PNG only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('photos') is-invalid @enderror @endif" 
                                                name="photos[]" accept="image/jpeg,image/jpg,image/png">
                                            <small class="text-muted">Upload photo evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('photos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Videos -->
                                        <div class="mb-3">
                                            <label class="form-label">Videos (Max: 50MB each, MP4/AVI/MOV only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('videos') is-invalid @enderror @endif" 
                                                name="videos[]" accept="video/mp4,video/avi,video/quicktime">
                                            <small class="text-muted">Upload video evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('videos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Documents -->
                                        <div class="mb-3">
                                            <label class="form-label">Documents (Max: 5MB each, PDF/DOC/DOCX only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('documents') is-invalid @enderror @endif" 
                                                name="documents[]" accept=".pdf,.doc,.docx">
                                            <small class="text-muted">Upload document evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('documents')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Additional Information -->
                                    <div class="col-12 mt-3">
                                        <label class="form-label">Additional Information (Optional)</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('additionalInfo') is-invalid @enderror @endif" 
                                                name="additionalInfo" rows="2" placeholder="Any additional information...">{{ session('form_type') == 'add' ? old('additionalInfo') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('additionalInfo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addIncidentForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Incident Modals with Validation - Only if user has update_blotter permission -->
            @if(auth('admin')->user()->hasPermission('update_blotter'))
                @foreach($incidents as $blotter)
                <div class="modal fade" id="editIncidentModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Incident Report - {{ $blotter->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.blotter.update', $blotter->id) }}" id="editIncidentForm{{ $blotter->id }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $blotter->id }}">

                                    <div class="row g-3">
                                        <!-- Incident Details -->
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Incident Details</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Report Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $blotter->id) @error('reportType') is-invalid @enderror @endif" 
                                                    name="reportType" required>
                                                <option value="">Select type</option>
                                                <option value="dispute" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                                <option value="security" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'security' ? 'selected' : '' }}>Security Concern</option>
                                                <option value="public" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'public' ? 'selected' : '' }}>Public Safety</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'other' ? 'selected' : '' }}>Other Concern</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('reportType')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Confidentiality <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $blotter->id) @error('confidentiality') is-invalid @enderror @endif" 
                                                    name="confidentiality" required>
                                                <option value="">Select confidentiality</option>
                                                <option value="low" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'low' ? 'selected' : '' }}>Public Report</option>
                                                <option value="medium" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'medium' ? 'selected' : '' }}>Confidential Report</option>
                                                <option value="high" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'high' ? 'selected' : '' }}>Anonymous Report</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('confidentiality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentDate') is-invalid @enderror @endif" 
                                                id="edit_incident_date_{{ $blotter->id }}"
                                                name="incidentDate" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentDate', \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d')) : \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentDate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Incident Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentTime') is-invalid @enderror @endif" 
                                                name="incidentTime" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentTime', $blotter->incident_time) : $blotter->incident_time }}" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentTime')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentLocation') is-invalid @enderror @endif" 
                                                name="incidentLocation" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentLocation', $blotter->location) : $blotter->location }}" placeholder="Incident location" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentLocation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentDescription') is-invalid @enderror @endif" 
                                                    name="incidentDescription" rows="3" placeholder="Describe the incident..." required>{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentDescription', $blotter->description) : $blotter->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentDescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Immediate Action Taken</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('immediateAction') is-invalid @enderror @endif" 
                                                    name="immediateAction" rows="2" placeholder="Any immediate action taken...">{{ session('form_type') == 'edit_' . $blotter->id ? old('immediateAction', $blotter->immediate_action) : $blotter->immediate_action }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('immediateAction')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Complainant Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Complainant Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantName') is-invalid @enderror @endif" 
                                                id="edit_complainant_name_{{ $blotter->id }}"
                                                name="complainantName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantName', $blotter->complainant_name) : $blotter->complainant_name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only." required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantContact') is-invalid @enderror @endif" 
                                                id="edit_complainant_contact_{{ $blotter->id }}"
                                                name="complainantContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantContact', $blotter->complainant_contact) : $blotter->complainant_contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09." required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantContact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantAddress') is-invalid @enderror @endif" 
                                                    name="complainantAddress" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantAddress', $blotter->complainant_address) : $blotter->complainant_address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantAddress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantEmail') is-invalid @enderror @endif" 
                                                id="edit_complainant_email_{{ $blotter->id }}"
                                                name="complainantEmail" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantEmail', $blotter->complainant_email) : $blotter->complainant_email }}" placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantEmail')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Respondent Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Respondent Information (Optional)</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Respondent Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentName') is-invalid @enderror @endif" 
                                                id="edit_respondent_name_{{ $blotter->id }}"
                                                name="respondentName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentName', $blotter->respondent_name) : $blotter->respondent_name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Respondent Contact</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentContact') is-invalid @enderror @endif" 
                                                name="respondentContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentContact', $blotter->respondent_contact) : $blotter->respondent_contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentContact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Respondent Address</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentAddress') is-invalid @enderror @endif" 
                                                    name="respondentAddress" rows="2" placeholder="Complete address">{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentAddress', $blotter->respondent_address) : $blotter->respondent_address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentAddress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Respondent Description</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentDescription') is-invalid @enderror @endif" 
                                                    name="respondentDescription" rows="2" placeholder="Description of respondent (if any)">{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentDescription', $blotter->respondent_description) : $blotter->respondent_description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentDescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Witnesses Section -->
                                        @if(auth('admin')->user()->hasPermission('add_witness'))
                                        <div class="col-12 mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="modal-section-title"><i class="fas fa-users"></i> Witnesses</div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEditWitnessField({{ $blotter->id }})">
                                                    <i class="fas fa-plus me-1"></i>Add Witness
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mb-3">Add witnesses to support this incident report</small>
                                            
                                            <div id="edit-witnesses-container-{{ $blotter->id }}">
                                                @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                                    @foreach($blotter->witnesses as $index => $witness)
                                                    <div class="witness-card mb-3" id="edit-witness-{{ $blotter->id }}-{{ $index }}">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-semibold">Witness</h6>
                                                            @if(auth('admin')->user()->hasPermission('delete_witness'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditWitnessField({{ $blotter->id }}, {{ $index }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Witness Name</label>
                                                                <input type="text" class="form-control" name="witnesses[{{ $index }}][name]" value="{{ $witness->name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Contact Number</label>
                                                                <input type="text" class="form-control" name="witnesses[{{ $index }}][contact]" value="{{ $witness->contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Witness Statement</label>
                                                                <textarea class="form-control" name="witnesses[{{ $index }}][statement]" rows="2" placeholder="What did the witness see/hear?">{{ $witness->statement }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Evidence Section -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary mb-3">Evidence Upload</h6>
                                            
                                            <!-- Display Existing Files (using files relationship, not evidence) -->
                                            @if($blotter->files && $blotter->files->count() > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Existing Files</label>
                                                <div class="row g-2">
                                                    @foreach($blotter->files as $file)
                                                    <div class="col-md-4">
                                                        <div class="border rounded p-2 position-relative">
                                                            @php
                                                                $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                                                $fileUrl = asset($file->file_path);
                                                            @endphp
                                                            
                                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                <img src="{{ $fileUrl }}" class="img-fluid rounded" style="max-height: 100px;" onclick="openZoomModal('{{ $fileUrl }}')">
                                                            @else
                                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                            @endif
                                                            
                                                            <div class="mt-1 small">
                                                                <span class="text-truncate d-block">{{ basename($file->file_path) }}</span>
                                                                <span class="badge bg-info">{{ $file->file_type }}</span>
                                                            </div>
                                                            @if(auth('admin')->user()->hasPermission('delete_evidence'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" onclick="removeFile({{ $file->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Upload New Files - Use array syntax for multiple files -->
                                            <div class="mb-3">
                                                <label class="form-label">Photos (Max: 10MB each, JPG/PNG only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('photos.*') is-invalid @enderror @endif" 
                                                    name="photos[]" multiple accept="image/jpeg,image/jpg,image/png">
                                                <small class="text-muted">You can select multiple photos</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('photos.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Videos (Max: 50MB each, MP4/AVI/MOV only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('videos.*') is-invalid @enderror @endif" 
                                                    name="videos[]" multiple accept="video/mp4,video/avi,video/quicktime">
                                                <small class="text-muted">You can select multiple videos</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('videos.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Documents (Max: 5MB each, PDF/DOC/DOCX only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('documents.*') is-invalid @enderror @endif" 
                                                    name="documents[]" multiple accept=".pdf,.doc,.docx">
                                                <small class="text-muted">You can select multiple documents</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('documents.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Additional Information -->
                                        <div class="col-12 mt-3">
                                            <label class="form-label">Additional Information (Optional)</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('additionalInfo') is-invalid @enderror @endif" 
                                                    name="additionalInfo" rows="2" placeholder="Any additional information...">{{ session('form_type') == 'edit_' . $blotter->id ? old('additionalInfo', $blotter->additional_info) : $blotter->additional_info }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('additionalInfo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editIncidentForm{{ $blotter->id }}" class="btn btn-primary" id="submitEditForm{{ $blotter->id }}">
                                        <i class="fas fa-save me-2"></i>Update Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($incidents as $blotter)
            <div class="modal fade" id="viewModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">
                                    {{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}
                                </div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $blotter->complainant_name }}</div>
                                    <div class="view-modal-sub">{{ $blotter->reference_number }} - Incident Report</div>
                                    <div class="mt-1 d-flex flex-wrap gap-2">
                                        @if($blotter->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($blotter->status == 'resolved')
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @elseif($blotter->status == 'dropped')
                                            <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                        <span class="badge bg-info-subtle text-info">{{ ucwords(str_replace('_', ' ', $blotter->report_type)) }}</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $blotter->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="details-panel-{{ $blotter->id }}" aria-selected="true">Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="edit-panel-{{ $blotter->id }}" aria-selected="false">Edit info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="history-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="history-panel-{{ $blotter->id }}" aria-selected="false">History</button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="details-tab-{{ $blotter->id }}">
                                    <div class="application-modal-summary">
                                        <div class="modal-summary-card is-primary">
                                            <span class="modal-summary-label">Complainant</span>
                                            <span class="modal-summary-value">{{ $blotter->complainant_name }}</span>
                                            <span class="modal-summary-help"><i class="fas fa-hashtag me-1"></i>{{ $blotter->reference_number }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Current Status</span>
                                            <span class="modal-summary-value">
                                                @if($blotter->deleted_at) Archived
                                                @elseif($blotter->status == 'resolved') Resolved
                                                @elseif($blotter->status == 'dropped') Dropped
                                                @else Processing
                                                @endif
                                            </span>
                                            <span class="modal-summary-help">Incident progress</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Incident Date</span>
                                            <span class="modal-summary-value">{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : 'No time recorded' }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Submitted</span>
                                            <span class="modal-summary-value">{{ $blotter->created_at ? $blotter->created_at->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $blotter->created_at ? $blotter->created_at->format('h:i A') : '' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-triangle-exclamation"></i>Incident details</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Report type</span>
                                                <div class="detail-value">{{ $blotter->report_type ? ucwords(str_replace('_', ' ', $blotter->report_type)) : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Confidentiality</span>
                                                <div class="detail-value">{{ $blotter->confidentiality ? ucfirst($blotter->confidentiality) : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Incident date</span>
                                                <div class="detail-value">{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Incident time</span>
                                                <div class="detail-value">{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Location</span>
                                                <div class="detail-value">{{ $blotter->location ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Description</span>
                                                <div class="detail-value">{{ $blotter->description ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Immediate action</span>
                                                <div class="detail-value">{{ $blotter->immediate_action ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-users"></i>People involved</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant</span>
                                                <div class="detail-value">{{ $blotter->complainant_name ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant contact</span>
                                                <div class="detail-value">{{ $blotter->complainant_contact ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant email</span>
                                                <div class="detail-value">{{ $blotter->complainant_email ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Respondent</span>
                                                <div class="detail-value">{{ $blotter->respondent_name ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Complainant address</span>
                                                <div class="detail-value">{{ $blotter->complainant_address ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Respondent address / description</span>
                                                <div class="detail-value">{{ $blotter->respondent_address ?: 'N/A' }} {{ $blotter->respondent_description ? ' - ' . $blotter->respondent_description : '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-user-group"></i>Witnesses</div>
                                        @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                            <div class="incident-witness-list">
                                                @foreach($blotter->witnesses as $witness)
                                                <div class="incident-witness-card">
                                                    <div class="incident-witness-avatar">{{ strtoupper(substr($witness->name ?: 'W', 0, 1)) }}</div>
                                                    <div class="min-w-0">
                                                        <div class="incident-witness-name">{{ $witness->name ?: 'Unnamed witness' }}</div>
                                                        <div class="incident-witness-meta">{{ $witness->contact ?: 'No contact' }}</div>
                                                        <div class="incident-witness-statement">{{ $witness->statement ?: 'No statement provided.' }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="empty-doc-note"><i class="fas fa-user-slash"></i><span>No witnesses recorded.</span></div>
                                        @endif
                                    </div>

                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-folder-open"></i>Evidence and documents</div>
                                        @if($blotter->files && $blotter->files->count() > 0)
                                            <div class="incident-file-grid">
                                                @foreach($blotter->files as $file)
                                                <div class="incident-file-card">
                                                    <div class="incident-file-icon"><i class="fas fa-file-lines"></i></div>
                                                    <div class="incident-file-info min-w-0">
                                                        <div class="incident-file-name">{{ basename($file->file_path) }}</div>
                                                        <div class="incident-file-type">{{ ucfirst($file->file_type) }}</div>
                                                    </div>
                                                    <a href="{{ asset($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Open
                                                    </a>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No uploaded evidence available for this incident report.</span></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade edit-info-pane" id="edit-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="edit-tab-{{ $blotter->id }}">
                                    <div class="edit-info-banner">
                                        <div class="application-avatar">{{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="edit-info-title">Edit incident report for {{ $blotter->complainant_name }}</div>
                                            <div class="edit-info-subtitle">
                                                Reference {{ $blotter->reference_number }} · Update incident classification, involved parties, witnesses, and supporting files.
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.blotter.update', $blotter->id) }}" id="quickEditForm{{ $blotter->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
                                        @csrf
                                        @method('PUT')

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-triangle-exclamation"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Incident Details</div>
                                                    <p class="edit-section-help">Keep the report classification, date, time, and narrative accurate.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-list-check"></i>Report Type</label>
                                                        <select class="form-select" name="reportType" required>
                                                            <option value="dispute" {{ $blotter->report_type === 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                                            <option value="security" {{ $blotter->report_type === 'security' ? 'selected' : '' }}>Security Concern</option>
                                                            <option value="public" {{ $blotter->report_type === 'public' ? 'selected' : '' }}>Public Safety</option>
                                                            <option value="other" {{ $blotter->report_type === 'other' ? 'selected' : '' }}>Other Concern</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-shield-halved"></i>Confidentiality</label>
                                                        <select class="form-select" name="confidentiality" required>
                                                            <option value="low" {{ $blotter->confidentiality === 'low' ? 'selected' : '' }}>Public Report</option>
                                                            <option value="medium" {{ $blotter->confidentiality === 'medium' ? 'selected' : '' }}>Confidential Report</option>
                                                            <option value="high" {{ $blotter->confidentiality === 'high' ? 'selected' : '' }}>Anonymous Report</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-calendar"></i>Incident Date</label>
                                                        <input type="date" class="form-control" name="incidentDate" value="{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d') : '' }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-clock"></i>Incident Time</label>
                                                        <input type="time" class="form-control" name="incidentTime" value="{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('H:i') : '' }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Incident Location</label>
                                                        <input type="text" class="form-control" name="incidentLocation" value="{{ $blotter->location }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-align-left"></i>Incident Description</label>
                                                        <textarea class="form-control" name="incidentDescription" rows="3" required>{{ $blotter->description }}</textarea>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-bolt"></i>Immediate Action</label>
                                                        <textarea class="form-control" name="immediateAction" rows="2">{{ $blotter->immediate_action }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Complainant Information</div>
                                                    <p class="edit-section-help">Used for follow-up, notices, and official report records.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-user"></i>Complainant Name</label>
                                                        <input type="text" class="form-control" name="complainantName" value="{{ $blotter->complainant_name }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Complainant Contact</label>
                                                        <input type="text" class="form-control" name="complainantContact" value="{{ $blotter->complainant_contact }}" maxlength="11" required>
                                                        <small>Use Philippine mobile format, for example 09XXXXXXXXX.</small>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Complainant Email</label>
                                                        <input type="email" class="form-control" name="complainantEmail" value="{{ $blotter->complainant_email }}">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Complainant Address</label>
                                                        <input type="text" class="form-control" name="complainantAddress" value="{{ $blotter->complainant_address }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user-shield"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Respondent Information</div>
                                                    <p class="edit-section-help">Add known respondent details if available.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-user"></i>Respondent Name</label>
                                                        <input type="text" class="form-control" name="respondentName" value="{{ $blotter->respondent_name }}">
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Respondent Contact</label>
                                                        <input type="text" class="form-control" name="respondentContact" value="{{ $blotter->respondent_contact }}" maxlength="11">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Respondent Address</label>
                                                        <input type="text" class="form-control" name="respondentAddress" value="{{ $blotter->respondent_address }}">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-align-left"></i>Respondent Description</label>
                                                        <textarea class="form-control" name="respondentDescription" rows="2">{{ $blotter->respondent_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-users"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Witnesses</div>
                                                    <p class="edit-section-help">Record statements and contacts for supporting testimony.</p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary ms-auto js-add-quick-witness" data-blotter-id="{{ $blotter->id }}" onclick="addQuickEditWitnessField({{ $blotter->id }}, this); return false;">
                                                    <i class="fas fa-plus me-1"></i>Add Witness
                                                </button>
                                            </div>
                                            <div class="edit-section-body">
                                                <div id="quick-edit-witnesses-container-{{ $blotter->id }}" class="incident-edit-witness-list">
                                                    @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                                        @foreach($blotter->witnesses as $witnessIndex => $witness)
                                                        <div class="witness-card edit-file-card" id="quick-edit-witness-{{ $blotter->id }}-{{ $witnessIndex }}">
                                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                                <strong>Witness {{ $witnessIndex + 1 }}</strong>
                                                                @if(auth('admin')->user()->hasPermission('delete_witness'))
                                                                <button type="button" class="btn btn-sm btn-outline-danger js-remove-quick-witness" data-blotter-id="{{ $blotter->id }}" data-witness-id="{{ $witnessIndex }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                            <div class="edit-form-grid">
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-user"></i>Name</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][name]" value="{{ $witness->name }}">
                                                                </div>
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-phone"></i>Contact</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][contact]" value="{{ $witness->contact }}" maxlength="11" inputmode="numeric">
                                                                </div>
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-comment-dots"></i>Statement</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][statement]" value="{{ $witness->statement }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    @else
                                                        <span class="edit-required-note"><i class="fas fa-circle-info"></i>No witnesses yet. Use Add Witness if needed.</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-folder-open"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Evidence Uploads</div>
                                                    <p class="edit-section-help">Leave uploads empty to keep existing files.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-note-sticky"></i>Additional Info</label>
                                                        <textarea class="form-control" name="additionalInfo" rows="2">{{ $blotter->additional_info }}</textarea>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-image"></i>Photos</label>
                                                            <input type="file" class="form-control" name="photos[]" accept="image/*" multiple>
                                                            <small>Upload images related to this incident.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-video"></i>Videos</label>
                                                            <input type="file" class="form-control" name="videos[]" accept="video/*" multiple>
                                                            <small>Upload video evidence if available.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-file-lines"></i>Documents</label>
                                                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.doc,.docx" multiple>
                                                            <small>Upload PDF or Word documents.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <span class="edit-required-note"><i class="fas fa-shield-halved"></i>Review details before saving. These records may be used for official blotter documentation.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="history-tab-{{ $blotter->id }}">
                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-clock-rotate-left"></i>Status history</div>
                                        <div class="mb-3">
                                            <span class="detail-label">Recent status</span>
                                            @if($blotter->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger">Archived</span>
                                            @elseif($blotter->status == 'resolved')
                                                <span class="badge bg-success-subtle text-success">Resolved</span>
                                            @elseif($blotter->status == 'dropped')
                                                <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                                            @endif
                                        </div>
                                        <div id="historyList{{ $blotter->id }}" class="small text-muted">Loading history...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="viewFooterDetails{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-switch-tab="edit" data-app-id="{{ $blotter->id }}">Edit info</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterEdit{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-switch-tab="details" data-app-id="{{ $blotter->id }}">Cancel</button>
                            <button type="submit" form="quickEditForm{{ $blotter->id }}" class="btn btn-primary">Save changes</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterHistory{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-pen-to-square me-2"></i>Update incident status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.blotter.approve', $blotter->id) }}" data-processing-url="{{ route('admin.blotter.processing', $blotter->id) }}" data-approve-url="{{ route('admin.blotter.approve', $blotter->id) }}" data-reject-url="{{ route('admin.blotter.reject', $blotter->id) }}" onsubmit="return handleIncidentStatusSubmit(event, this)">
                            @csrf
                            <div class="modal-body">
                                <div class="status-flow-card">
                                    <div class="status-flow-person">
                                        <div class="application-avatar">{{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="fw-bold text-truncate">{{ $blotter->complainant_name }}</div>
                                            <div class="small text-muted">{{ $blotter->reference_number }} · Current: {{ ucwords(str_replace('_', ' ', $blotter->status)) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">New status</label>
                                    <input type="hidden" name="status" value="{{ $blotter->status }}" class="selected-incident-status">
                                    <div class="status-grid" data-current-status="{{ $blotter->status }}">
                                        <button type="button" class="status-option" data-status="processing"><span class="dot" style="background:#5e35b1;"></span>Processing</button>
                                        @if(auth('admin')->user()->hasPermission('approve_blotter'))
                                        <button type="button" class="status-option" data-status="resolved"><span class="dot" style="background:#2e7d32;"></span>Resolved</button>
                                        @endif
                                        @if(auth('admin')->user()->hasPermission('reject_blotter'))
                                        <button type="button" class="status-option" data-status="dropped"><span class="dot" style="background:#d32f2f;"></span>Dropped</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Remarks <span class="text-muted fw-normal">(required if dropped, max 40 chars)</span></label>
                                    <textarea class="form-control" name="remarks" rows="3" maxlength="40" placeholder="Add a note about this status change..."></textarea>
                                </div>

                                <div data-notify-wrap data-has-email="{{ $blotter->complainant_email ? '1' : '0' }}" data-has-sms="{{ $blotter->complainant_contact ? '1' : '0' }}">
                                    <label class="form-label text-uppercase fw-semibold small">Notify complainant</label>
                                    <div class="notify-row">
                                        <div class="notify-label">
                                            <strong>Send email</strong>
                                            <small>{{ $blotter->complainant_email ?: 'No email available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" {{ auth('admin')->user()->hasPermission('send_email') ? 'checked' : '' }} {{ !$blotter->complainant_email || !auth('admin')->user()->hasPermission('send_email') ? 'disabled' : '' }} {{ !auth('admin')->user()->hasPermission('send_email') ? 'data-permission-disabled="1"' : '' }}>
                                        </div>
                                    </div>
                                    <div class="notify-row mb-0">
                                        <div class="notify-label">
                                            <strong>Send SMS</strong>
                                            <small>{{ $blotter->complainant_contact ?: 'No mobile number available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_sms" value="1" {{ auth('admin')->user()->hasPermission('send_sms') ? 'checked' : '' }} {{ !$blotter->complainant_contact || !auth('admin')->user()->hasPermission('send_sms') ? 'disabled' : '' }} {{ !auth('admin')->user()->hasPermission('send_sms') ? 'data-permission-disabled="1"' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm &amp; save</button>
                            </div>
                        </form>
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
                    var addModal = new bootstrap.Modal(document.getElementById('addIncidentModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editIncidentModal{{ $editId }}'));
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

            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one incident report to archive.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Archive',
                text: `Are you sure you want to archive ${checkboxes.length} selected incident report(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Archive'
            }).then((result) => {

                if (result.isConfirmed) {

                    // Attach selected IDs
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkForm.appendChild(input);
                    });

                    bulkForm.submit();
                }

            });
        }

        // Export CSV function
        function exportCSV() {

            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            // If nothing selected → Ask to export all
            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No incident reports selected. Export all records?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Export All'
                }).then((result) => {

                    if (result.isConfirmed) {
                        exportForm.submit();
                    }

                });

                return;
            }

            // If selected → Confirm export selected
            Swal.fire({
                title: 'Export Selected?',
                text: `Export ${checkboxes.length} selected incident report(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Export'
            }).then((result) => {

                if (result.isConfirmed) {

                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        exportForm.appendChild(input);
                    });

                    exportForm.submit();
                }

            });
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.application-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Confirm delete with SweetAlert
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
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
            
            return false;
        }

        async function viewRemarksHistory(requestType, requestId, referenceNumber) {
            try {
                const params = new URLSearchParams({
                    request_type: requestType,
                    request_id: String(requestId)
                });
                const response = await fetch("{{ route('admin.notifications.remarksHistory') }}?" + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load remarks history.');
                }

                const payload = await response.json();
                const history = Array.isArray(payload.history) ? payload.history : [];

                if (history.length === 0) {
                    alert('No saved remarks found for this request yet.');
                    return;
                }

                const details = history
                    .map(function(item, index) {
                        const channel = item.channel ? item.channel.toUpperCase() : 'N/A';
                        const status = item.status ? item.status.toUpperCase() : 'N/A';
                        return (index + 1) + '. [' + status + '] ' + item.remarks + '\n   by ' + item.admin_name + ' via ' + channel + ' on ' + item.created_at;
                    })
                    .join('\n\n');

                alert('Remarks History - ' + referenceNumber + '\n\n' + details);
            } catch (error) {
                alert(error.message || 'Unable to load remarks history right now.');
            }
        }

        function prepareRemarksAndConfirm(event, form, confirmMessage) {
            const statusInput = form.querySelector('input[name="status"]');
            const remarksInput = form.querySelector('input[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';

            if ((status === 'rejected' || status === 'dropped') && remarksInput) {
                const remarks = prompt('Please enter remarks before sending this notification:');
                if (remarks === null) {
                    event.preventDefault();
                    return false;
                }
                const trimmedRemarks = remarks.trim();
                if (trimmedRemarks === '') {
                    event.preventDefault();
                    alert('Remarks are required for rejected or dropped notifications.');
                    return false;
                }
                if (trimmedRemarks.length > 40) {
                    event.preventDefault();
                    alert('Remarks must not exceed 40 characters.');
                    return false;
                }
                remarksInput.value = trimmedRemarks;
            }

            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }

            return true;
        }

        async function loadApplicationHistory(appId) {
            const list = document.getElementById('historyList' + appId);
            if (!list) {
                return;
            }

            list.innerHTML = 'Loading history...';

            try {
                const params = new URLSearchParams({
                    request_type: 'incident',
                    request_id: String(appId)
                });

                const response = await fetch("{{ route('admin.notifications.remarksHistory') }}?" + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load history.');
                }

                const payload = await response.json();
                const timeline = Array.isArray(payload.timeline) ? payload.timeline : [];

                if (timeline.length === 0) {
                    list.innerHTML = '<div class="text-muted">No activity found for this report yet.</div>';
                    return;
                }

                list.innerHTML = timeline.map(function(item) {
                    const details = item.details ? '<div class="text-muted mt-1">' + item.details + '</div>' : '';
                    const actor = item.actor ? '<div class="small text-muted">' + item.actor + ' - ' + (item.created_at || '') + '</div>' : '<div class="small text-muted">' + (item.created_at || '') + '</div>';
                    return '<div class="activity-item py-2 border-bottom">' +
                        '<div class="fw-semibold">' + (item.title || 'Activity') + '</div>' +
                        actor +
                        details +
                        '</div>';
                }).join('');
            } catch (error) {
                list.innerHTML = '<div class="text-danger">Unable to load history right now.</div>';
            }
        }

        function toggleIncidentNotifyOptions(form, status) {
            const notifyWrap = form.querySelector('[data-notify-wrap]');
            if (!notifyWrap) {
                return;
            }

            const emailToggle = form.querySelector('input[name="notify_email"]');
            const smsToggle = form.querySelector('input[name="notify_sms"]');
            const hasEmail = notifyWrap.getAttribute('data-has-email') === '1';
            const hasSms = notifyWrap.getAttribute('data-has-sms') === '1';
            const allowNotify = ['resolved', 'dropped'].includes(String(status || '').toLowerCase());

            notifyWrap.classList.toggle('d-none', !allowNotify);

            if (emailToggle) {
                const canUseEmail = allowNotify && hasEmail && !emailToggle.hasAttribute('data-permission-disabled');
                emailToggle.disabled = !canUseEmail;
                emailToggle.checked = canUseEmail;
            }

            if (smsToggle) {
                const canUseSms = allowNotify && hasSms && !smsToggle.hasAttribute('data-permission-disabled');
                smsToggle.disabled = !canUseSms;
                smsToggle.checked = canUseSms;
            }
        }

        function handleIncidentStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-incident-status');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';
            const remarks = remarksInput ? String(remarksInput.value || '').trim() : '';

            if (!status) {
                event.preventDefault();
                alert('Please select a status.');
                return false;
            }

            if (remarks.length > 40) {
                event.preventDefault();
                alert('Remarks must not exceed 40 characters.');
                return false;
            }

            if (status === 'dropped' && remarks === '') {
                event.preventDefault();
                alert('Remarks are required when status is Dropped.');
                return false;
            }

            const processingUrl = form.getAttribute('data-processing-url');
            const approveUrl = form.getAttribute('data-approve-url');
            const rejectUrl = form.getAttribute('data-reject-url');
            if (status === 'processing') {
                form.action = processingUrl;
            } else if (status === 'dropped') {
                form.action = rejectUrl;
            } else {
                form.action = approveUrl;
            }

            return confirm('Confirm status update to ' + status.charAt(0).toUpperCase() + status.slice(1) + '?');
        }

        function switchViewTab(appId, tabName) {
            const tabButton = document.getElementById(tabName + '-tab-' + appId);
            if (tabButton) {
                bootstrap.Tab.getOrCreateInstance(tabButton).show();
            }
        }

        function toggleViewFooter(appId, activeTab) {
            const detailsFooter = document.getElementById('viewFooterDetails' + appId);
            const editFooter = document.getElementById('viewFooterEdit' + appId);
            const historyFooter = document.getElementById('viewFooterHistory' + appId);

            if (!detailsFooter || !editFooter || !historyFooter) {
                return;
            }

            detailsFooter.classList.add('d-none');
            editFooter.classList.add('d-none');
            historyFooter.classList.add('d-none');

            if (activeTab === 'details') {
                detailsFooter.classList.remove('d-none');
            } else if (activeTab === 'edit') {
                editFooter.classList.remove('d-none');
            } else {
                historyFooter.classList.remove('d-none');
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            function normalizePhoneValue(value) {
                let cleaned = (value || '').replace(/[^0-9]/g, '').slice(0, 12);
                if (cleaned.startsWith('63') && cleaned.length === 12) {
                    cleaned = '0' + cleaned.slice(2);
                }
                return cleaned.slice(0, 11);
            }

            function bindClearInvalidOnEdit(form) {
                if (!form) return;

                form.querySelectorAll('input, textarea, select').forEach(function(field) {
                    const clearState = function() {
                        field.classList.remove('is-invalid');
                        field.setCustomValidity('');
                    };

                    field.addEventListener('input', clearState);
                    field.addEventListener('change', clearState);
                });

                // Normalize all contact inputs consistently (complainant/respondent/witness).
                form.querySelectorAll('input[name$="Contact"], input[name*="[contact]"]').forEach(function(field) {
                    field.addEventListener('input', function() {
                        this.value = normalizePhoneValue(this.value);
                    });
                    field.addEventListener('blur', function() {
                        this.value = normalizePhoneValue(this.value);
                    });
                });
            }

            // Initialize Swal if not loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return confirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.application-checkbox');
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

            document.querySelectorAll('[id^="viewModal"]').forEach(function(modalEl) {
                const appId = modalEl.id.replace('viewModal', '');

                modalEl.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function() {
                        if (tabBtn.id.indexOf('details-tab-') === 0) {
                            toggleViewFooter(appId, 'details');
                        } else if (tabBtn.id.indexOf('edit-tab-') === 0) {
                            toggleViewFooter(appId, 'edit');
                        } else {
                            toggleViewFooter(appId, 'history');
                            loadApplicationHistory(appId);
                        }
                    });
                });

                modalEl.addEventListener('shown.bs.modal', function() {
                    toggleViewFooter(appId, 'details');
                });
            });

            document.querySelectorAll('[data-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const appId = button.getAttribute('data-app-id');
                    const tabName = button.getAttribute('data-switch-tab');
                    switchViewTab(appId, tabName);
                });
            });

            document.querySelectorAll('[id^="updateStatusModal"]').forEach(function(modalEl) {
                const form = modalEl.querySelector('form');
                const statusInput = form ? form.querySelector('.selected-incident-status') : null;
                if (!form || !statusInput) {
                    return;
                }

                modalEl.querySelectorAll('.status-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const nextStatus = btn.getAttribute('data-status');
                        if (!nextStatus) {
                            return;
                        }

                        modalEl.querySelectorAll('.status-option').forEach(function(other) {
                            other.classList.remove('active');
                        });
                        btn.classList.add('active');
                        statusInput.value = nextStatus;
                        toggleIncidentNotifyOptions(form, nextStatus);
                    });
                });

                modalEl.addEventListener('shown.bs.modal', function() {
                    const gridEl = modalEl.querySelector('.status-grid');
                    const current = ((gridEl ? gridEl.getAttribute('data-current-status') : '') || '').toLowerCase();
                    const buttons = Array.from(modalEl.querySelectorAll('.status-option'));
                    const activeButton = buttons.find(function(btn) {
                        return btn.getAttribute('data-status') === current;
                    }) || buttons[0];

                    if (activeButton) {
                        buttons.forEach(function(btn) { btn.classList.remove('active'); });
                        activeButton.classList.add('active');
                        statusInput.value = activeButton.getAttribute('data-status') || 'processing';
                    }

                    toggleIncidentNotifyOptions(form, statusInput.value);
                });
            });

            function setFieldValidity(field, message) {
                if (!field) return;
                let feedback = field.parentNode.querySelector('.invalid-feedback.dynamic-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback dynamic-feedback';
                    field.parentNode.appendChild(feedback);
                }

                field.setCustomValidity(message || '');
                if (message) {
                    field.classList.add('is-invalid');
                    feedback.textContent = message;
                } else {
                    field.classList.remove('is-invalid');
                    feedback.textContent = '';
                }
            }

            function attachIncidentQuickEditValidation(form) {
                if (!form) return;

                const namePattern = /^[A-Za-z\s\-\.',]+$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const complainantName = form.querySelector('input[name="complainantName"]');
                const complainantContact = form.querySelector('input[name="complainantContact"]');
                const complainantEmail = form.querySelector('input[name="complainantEmail"]');
                const complainantAddress = form.querySelector('input[name="complainantAddress"]');
                const incidentDate = form.querySelector('input[name="incidentDate"]');
                const incidentTime = form.querySelector('input[name="incidentTime"]');
                const incidentLocation = form.querySelector('input[name="incidentLocation"]');
                const incidentDescription = form.querySelector('textarea[name="incidentDescription"]');
                const respondentContact = form.querySelector('input[name="respondentContact"]');

                const validateName = function(field, label, required) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v && required) {
                        setFieldValidity(field, label + ' is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (v.length < 2 || !namePattern.test(v)) {
                        setFieldValidity(field, label + ' must be valid letters and punctuation only.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validatePhone = function(field, required) {
                    if (!field) return true;
                    field.value = (field.value || '').replace(/[^0-9]/g, '').slice(0, 12);
                    if (field.value.startsWith('63') && field.value.length === 12) {
                        field.value = '0' + field.value.slice(2);
                    }
                    const v = field.value;
                    if (!v && required) {
                        setFieldValidity(field, 'Contact number is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (!/^09[0-9]{9}$/.test(v)) {
                        setFieldValidity(field, 'Contact number must be 11 digits and start with 09.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateEmail = function(field) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v) {
                        setFieldValidity(field, 'Email is required.');
                        return false;
                    }
                    if (!emailPattern.test(v)) {
                        setFieldValidity(field, 'Please enter a valid email address.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateText = function(field, label, minLen, required) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v && required) {
                        setFieldValidity(field, label + ' is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (v.length < minLen) {
                        setFieldValidity(field, label + ' must be at least ' + minLen + ' characters.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateDate = function(field) {
                    if (!field) return true;
                    if (!field.value) {
                        setFieldValidity(field, 'Incident date is required.');
                        return false;
                    }
                    const selected = new Date(field.value + 'T00:00:00');
                    const today = new Date();
                    today.setHours(23, 59, 59, 999);
                    if (selected > today) {
                        setFieldValidity(field, 'Incident date cannot be in the future.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateTime = function(field) {
                    if (!field) return true;
                    if (!field.value) {
                        setFieldValidity(field, 'Incident time is required.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                if (complainantName) complainantName.addEventListener('input', function() { validateName(complainantName, 'Complainant name', true); });
                if (complainantContact) complainantContact.addEventListener('input', function() { validatePhone(complainantContact, true); });
                if (respondentContact) respondentContact.addEventListener('input', function() { validatePhone(respondentContact, false); });
                if (complainantEmail) complainantEmail.addEventListener('input', function() { validateEmail(complainantEmail); });
                if (complainantAddress) complainantAddress.addEventListener('input', function() { validateText(complainantAddress, 'Complainant address', 8, true); });
                if (incidentDate) incidentDate.addEventListener('change', function() { validateDate(incidentDate); });
                if (incidentTime) incidentTime.addEventListener('change', function() { validateTime(incidentTime); });
                if (incidentLocation) incidentLocation.addEventListener('input', function() { validateText(incidentLocation, 'Incident location', 3, true); });
                if (incidentDescription) incidentDescription.addEventListener('input', function() { validateText(incidentDescription, 'Incident description', 10, true); });

                form.addEventListener('input', function(event) {
                    if (event.target.matches('input[name*="[contact]"]')) {
                        validatePhone(event.target, false);
                    }
                    if (event.target.matches('input[name*="[name]"]')) {
                        validateName(event.target, 'Witness name', false);
                    }
                });

                form.addEventListener('change', function(event) {
                    const target = event.target;
                    if (!(target instanceof HTMLInputElement)) {
                        return;
                    }

                    if (!target.matches('input[type="file"]')) {
                        return;
                    }

                    const files = Array.from(target.files || []);
                    if (files.length === 0) {
                        setFieldValidity(target, '');
                        return;
                    }

                    let limitMb = 5;
                    if (target.name === 'photos[]') limitMb = 10;
                    if (target.name === 'videos[]') limitMb = 50;

                    const tooLarge = files.find(function(file) {
                        return (file.size / (1024 * 1024)) > limitMb;
                    });
                    if (tooLarge) {
                        setFieldValidity(target, 'Each file must be below ' + limitMb + 'MB.');
                        return;
                    }

                    setFieldValidity(target, '');
                });

                form.addEventListener('submit', function(event) {
                    const valid = [
                        validateName(complainantName, 'Complainant name', true),
                        validatePhone(complainantContact, true),
                        validatePhone(respondentContact, false),
                        validateEmail(complainantEmail),
                        validateText(complainantAddress, 'Complainant address', 8, true),
                        validateDate(incidentDate),
                        validateTime(incidentTime),
                        validateText(incidentLocation, 'Incident location', 3, true),
                        validateText(incidentDescription, 'Incident description', 10, true)
                    ].every(Boolean);

                    if (!valid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            }

            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                bindClearInvalidOnEdit(form);
                attachIncidentQuickEditValidation(form);
            });

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

            // Show modal if there are validation errors
            @if ($errors->any() && session('form_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addIncidentModal'));
                addModal.show();
            @endif

            // Real-time validation for add form
            const addForm = document.getElementById('addIncidentForm');
            if (addForm) {
                bindClearInvalidOnEdit(addForm);

                function sf(el, msg) {
                    if (!el) return;
                    el.classList.add('is-invalid');
                    let fb = el.parentElement.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.insertAdjacentElement('afterend', fb); }
                    fb.textContent = msg;
                }
                function cf(el) { if (el) { el.classList.remove('is-invalid'); el.setCustomValidity(''); } }

                const complainantName    = addForm.querySelector('[name="complainantName"]');
                const contact            = addForm.querySelector('[name="complainantContact"]');
                const complainantEmail   = addForm.querySelector('[name="complainantEmail"]');
                const incidentDate       = addForm.querySelector('[name="incidentDate"]');
                const incidentLocation   = addForm.querySelector('[name="incidentLocation"]');
                const incidentDescription = addForm.querySelector('[name="incidentDescription"]');

                if (complainantName) {
                    complainantName.addEventListener('input', function() { cf(this); }, {once: true});
                    complainantName.addEventListener('input', function() {
                        const v = this.value.trim();
                        if (!v) sf(this, 'Complainant name is required.');
                        else if (v.length < 2) sf(this, 'Name must be at least 2 characters.');
                        else if (!/^[A-Za-z\s\-\.',]+$/.test(v)) sf(this, 'Name must contain only letters, spaces, apostrophes, commas, hyphens, or periods.');
                        else cf(this);
                    });
                }
                if (contact) {
                    contact.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
                        if (this.value.startsWith('63') && this.value.length === 12) {
                            this.value = '0' + this.value.slice(2);
                        }
                        if (this.value.length > 0 && !this.value.startsWith('09')) sf(this, 'Contact must start with 09.');
                        else if (this.value.length > 0 && this.value.length !== 11) sf(this, 'Contact must be exactly 11 digits.');
                        else cf(this);
                    });
                }
                if (complainantEmail) {
                    complainantEmail.addEventListener('input', function() {
                        const v = this.value.trim();
                        if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) sf(this, 'Enter a valid email address.');
                        else cf(this);
                    });
                }
                if (incidentDate) {
                    incidentDate.addEventListener('change', function() {
                        const selected = new Date(this.value);
                        const today = new Date(); today.setHours(23, 59, 59, 999);
                        if (!this.value) sf(this, 'Incident date is required.');
                        else if (selected > today) sf(this, 'Incident date cannot be in the future.');
                        else cf(this);
                    });
                }
                if (incidentLocation) {
                    incidentLocation.addEventListener('input', function() { cf(this); }, {once: true});
                    incidentLocation.addEventListener('input', function() {
                        if (!this.value.trim()) sf(this, 'Incident location is required.');
                        else if (this.value.trim().length < 3) sf(this, 'Location must be at least 3 characters.');
                        else cf(this);
                    });
                }
                if (incidentDescription) {
                    incidentDescription.addEventListener('input', function() { cf(this); }, {once: true});
                    incidentDescription.addEventListener('input', function() {
                        if (!this.value.trim()) sf(this, 'Description is required.');
                        else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                        else cf(this);
                    });
                }

                addForm.addEventListener('submit', function(e) {
                    let valid = true;
                    if (complainantName) {
                        const v = complainantName.value.trim();
                        if (!v) { sf(complainantName, 'Complainant name is required.'); valid = false; }
                        else if (v.length < 2) { sf(complainantName, 'Name must be at least 2 characters.'); valid = false; }
                        else if (!/^[A-Za-z\s\-\.',]+$/.test(v)) { sf(complainantName, 'Name must contain only letters, spaces, apostrophes, commas, hyphens, or periods.'); valid = false; }
                    }
                    if (contact) {
                        if (!contact.value) { sf(contact, 'Contact number is required.'); valid = false; }
                        else if (!contact.value.startsWith('09') || contact.value.length !== 11) { sf(contact, 'Contact must be 11 digits starting with 09.'); valid = false; }
                    }
                    if (complainantEmail) {
                        const emailValue = complainantEmail.value.trim();
                        if (!emailValue) {
                            sf(complainantEmail, 'Email is required.');
                            valid = false;
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                            sf(complainantEmail, 'Enter a valid email address.'); valid = false;
                        }
                    }
                    if (incidentDate) {
                        const selected = new Date(incidentDate.value);
                        const today = new Date(); today.setHours(23, 59, 59, 999);
                        if (!incidentDate.value) { sf(incidentDate, 'Incident date is required.'); valid = false; }
                        else if (selected > today) { sf(incidentDate, 'Incident date cannot be in the future.'); valid = false; }
                    }
                    if (incidentLocation) {
                        if (!incidentLocation.value.trim()) { sf(incidentLocation, 'Incident location is required.'); valid = false; }
                        else if (incidentLocation.value.trim().length < 3) { sf(incidentLocation, 'Location must be at least 3 characters.'); valid = false; }
                    }
                    if (incidentDescription) {
                        if (!incidentDescription.value.trim()) { sf(incidentDescription, 'Description is required.'); valid = false; }
                        else if (incidentDescription.value.trim().length < 10) { sf(incidentDescription, 'Description must be at least 10 characters.'); valid = false; }
                    }
                    if (!valid) e.preventDefault();
                });
            }
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        const globalSearch = document.getElementById('globalSearch');
        if (globalSearch) {
            globalSearch.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            zoomImage.src = imageUrl;

            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        // Function to add witness fields dynamically
        let witnessCount = 0;

        function addWitnessField() {
            witnessCount++;
            
            const container = document.getElementById('witnesses-container');
            const witnessHtml = `
                <div class="witness-card mb-3" id="witness-${witnessCount}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeWitnessField(${witnessCount})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Witness Statement</label>
                            <textarea class="form-control" name="witnesses[${witnessCount}][statement]" rows="2" placeholder="What did the witness see/hear?"></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', witnessHtml);
            
            // Add validation for the new witness contact field
            const newContact = document.querySelector(`#witness-${witnessCount} input[name="witnesses[${witnessCount}][contact]"]`);
            if (newContact) {
                newContact.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 12) {
                        this.value = this.value.slice(0, 12);
                    }
                    if (this.value.startsWith('63') && this.value.length === 12) {
                        this.value = '0' + this.value.slice(2);
                    }
                });
            }
        }

        function removeWitnessField(id) {
            const witnessElement = document.getElementById(`witness-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        // File size validation
        document.addEventListener('DOMContentLoaded', function() {
            // Photo upload validation
            const photoInput = document.querySelector('input[name="photos"]');
            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024; // in MB
                        if (fileSize > 10) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Photo must be less than 10MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }

            // Video upload validation
            const videoInput = document.querySelector('input[name="videos"]');
            if (videoInput) {
                videoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024; // in MB
                        if (fileSize > 50) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Video must be less than 50MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }

            // Document upload validation
            const docInput = document.querySelector('input[name="documents"]');
            if (docInput) {
                docInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024; // in MB
                        if (fileSize > 5) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Document must be less than 5MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }
        });

        // Real-time validation for edit forms
        @if(auth('admin')->user()->hasPermission('update_blotter'))
            @foreach($incidents as $blotter)
            (function(editId) {
                const editForm = document.getElementById('editIncidentForm' + editId);
                if (editForm) {
                    bindClearInvalidOnEdit(editForm);

                    // Contact number validation
                    const contact = document.getElementById('edit_complainant_contact_' + editId);
                    if (contact) {
                        contact.addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                            if (this.value.length > 12) {
                                this.value = this.value.slice(0, 12);
                            }
                            if (this.value.startsWith('63') && this.value.length === 12) {
                                this.value = '0' + this.value.slice(2);
                            }
                            if (this.value.length > 0 && !this.value.startsWith('09')) {
                                this.setCustomValidity('Contact number must start with 09');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must start with 09';
                            }
                            else if (this.value.length > 0 && this.value.length !== 11) {
                                this.setCustomValidity('Contact number must be exactly 11 digits');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must be exactly 11 digits';
                            }
                            else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Incident date validation
                    const incidentDate = document.getElementById('edit_incident_date_' + editId);
                    if (incidentDate) {
                        incidentDate.addEventListener('change', function() {
                            const selectedDate = new Date(this.value);
                            const today = new Date();
                            if (selectedDate > today) {
                                this.setCustomValidity('Incident date cannot be in the future');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Incident date cannot be in the future';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Email validation
                    const email = document.getElementById('edit_complainant_email_' + editId);
                    if (email) {
                        email.addEventListener('input', function() {
                            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailPattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Please enter a valid email address');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Please enter a valid email address';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Name validation
                    const complainantName = document.getElementById('edit_complainant_name_' + editId);
                    if (complainantName) {
                        complainantName.addEventListener('input', function() {
                            const namePattern = /^[A-Za-z\s\-\.',]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const respondentName = document.getElementById('edit_respondent_name_' + editId);
                    if (respondentName) {
                        respondentName.addEventListener('input', function() {
                            const namePattern = /^[A-Za-z\s\-\.',]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }
                }
            })('{{ $blotter->id }}');
            @endforeach
        @endif

        // Add these functions to handle edit modal witnesses
        let editWitnessCount = {};

        function addEditWitnessField(blotterId) {
            if (!editWitnessCount[blotterId]) {
                editWitnessCount[blotterId] = document.querySelectorAll(`#edit-witnesses-container-${blotterId} .witness-card`).length;
            }
            editWitnessCount[blotterId]++;
            
            const container = document.getElementById(`edit-witnesses-container-${blotterId}`);
            const witnessHtml = `
                <div class="witness-card mb-3" id="edit-witness-${blotterId}-${editWitnessCount[blotterId]}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditWitnessField(${blotterId}, ${editWitnessCount[blotterId]})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Witness Statement</label>
                            <textarea class="form-control" name="witnesses[${editWitnessCount[blotterId]}][statement]" rows="2" placeholder="What did the witness see/hear?"></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', witnessHtml);
        }

        function removeEditWitnessField(blotterId, id) {
            const witnessElement = document.getElementById(`edit-witness-${blotterId}-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        var quickEditWitnessCount = window.quickEditWitnessCount || {};
        window.quickEditWitnessCount = quickEditWitnessCount;

        function resolveQuickEditWitnessContainer(blotterId, triggerElement) {
            let container = document.getElementById(`quick-edit-witnesses-container-${blotterId}`)
                || document.getElementById(`edit-witnesses-container-${blotterId}`);

            if (!container && triggerElement) {
                const modal = triggerElement.closest('.modal');
                if (modal) {
                    container = modal.querySelector(`#quick-edit-witnesses-container-${blotterId}`)
                        || modal.querySelector('#quick-edit-witnesses-container-' + blotterId)
                        || modal.querySelector('[id^="quick-edit-witnesses-container-"]')
                        || modal.querySelector('[id^="edit-witnesses-container-"]');
                }
            }

            if (!container) {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    container = activeModal.querySelector('[id^="quick-edit-witnesses-container-"]')
                        || activeModal.querySelector('[id^="edit-witnesses-container-"]');
                }
            }

            return container;
        }

        function addQuickEditWitnessField(blotterId, triggerElement) {
            const witnessCountState = window.quickEditWitnessCount || (window.quickEditWitnessCount = {});
            const container = resolveQuickEditWitnessContainer(blotterId, triggerElement);
            if (!container) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to add witness',
                        text: 'Witness fields container was not found. Please reopen the modal and try again.'
                    });
                }
                return false;
            }

            if (!witnessCountState[blotterId]) {
                witnessCountState[blotterId] = container.querySelectorAll('.witness-card').length;
            }
            witnessCountState[blotterId]++;

            const idx = witnessCountState[blotterId];

            const witnessHtml = `
                <div class="witness-card mb-3" id="quick-edit-witness-${blotterId}-${idx}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-quick-witness" data-blotter-id="${blotterId}" data-witness-id="${idx}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Witness Contact</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Witness Statement</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][statement]" placeholder="What did the witness see/hear?">
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', witnessHtml);
            return true;
        }

        function removeQuickEditWitnessField(blotterId, id) {
            const witnessElement = document.getElementById(`quick-edit-witness-${blotterId}-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        document.addEventListener('click', function(event) {
            const addBtn = event.target.closest('.js-add-quick-witness');
            if (addBtn) {
                event.preventDefault();
                const blotterId = Number(addBtn.getAttribute('data-blotter-id'));
                if (!Number.isNaN(blotterId) && blotterId > 0) {
                    addQuickEditWitnessField(blotterId, addBtn);
                }
                return;
            }

            const removeBtn = event.target.closest('.js-remove-quick-witness');
            if (removeBtn) {
                event.preventDefault();
                const blotterId = Number(removeBtn.getAttribute('data-blotter-id'));
                const witnessId = Number(removeBtn.getAttribute('data-witness-id'));
                if (!Number.isNaN(blotterId) && !Number.isNaN(witnessId) && blotterId > 0 && witnessId >= 0) {
                    removeQuickEditWitnessField(blotterId, witnessId);
                }
            }
        });

        function removeEvidence(evidenceId) {
            Swal.fire({
                title: 'Remove Evidence?',
                text: 'Are you sure you want to remove this evidence?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // You'll need to create a route for this
                    fetch(`/evidence/${evidenceId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
                }
            });
        }
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

