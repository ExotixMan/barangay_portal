<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Clearance Applications - Admin</title>
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

        .quick-edit-form .form-control,
        .quick-edit-form .form-select {
            min-height: 44px;
        }

        .quick-edit-form textarea.form-control {
            min-height: 96px;
            resize: vertical;
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
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed var(--border-color);
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

        /* Purpose Badge */
        .purpose-badge {
            background: var(--info-light);
            color: var(--info);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* File Upload Styling */
        .form-control[type="file"] {
            padding: 1rem;
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
                flex-wrap: nowrap !important;
                justify-content: flex-end !important;
                align-items: center;
            }
            
            .btn-group {
                margin-bottom: 0;
            }

            .d-flex.gap-1.gap-sm-2.justify-content-end > * {
                flex: 0 0 auto;
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

        .table .dropdown-menu {
            margin-top: 0.25rem;
            z-index: 1055;
            max-height: 250px;
            overflow-y: auto;
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

        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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

        .status-option .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-option.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.14);
            color: var(--primary-dark);
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
        
        /* Permission denied message */
        .permission-denied {
            background: white;
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
        }
        
        .permission-denied i {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }
        
        .permission-denied h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .permission-denied p {
            color: #6c757d;
            max-width: 400px;
            margin: 0 auto;
        }
    /* ==========================================================
           UI/UX REDESIGN OVERRIDE - Clearance Applications adopted from Residency design
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
           SIMPLIFIED CLEARANCE MANAGEMENT UX PATCH
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

    

        /* Clearance-specific consistency adjustments */
        .income-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            width: fit-content;
            padding: .42rem .62rem;
            color: var(--info) !important;
            background: var(--info-soft) !important;
            border: 1px solid #dbeafe;
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 850;
            white-space: nowrap;
        }

        #clearanceTable {
            min-width: 820px !important;
        }

        @media (max-width: 768px) {
            #clearanceTable {
                min-width: 720px !important;
            }
        }


        /* Modern edit modal layout - matched with residency application design */
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

        .edit-info-banner .application-avatar { width: 46px; height: 46px; border-radius: 15px; }
        .edit-info-title { color: var(--text); font-size: .98rem; font-weight: 850; line-height: 1.25; letter-spacing: -.02em; }
        .edit-info-subtitle { margin-top: .18rem; color: var(--muted); font-size: .8rem; font-weight: 650; line-height: 1.45; }
        .modern-edit-form { display: grid; gap: .85rem; padding: .95rem; background: #fbfdff; border: 1px solid var(--line); border-radius: 20px; }
        .edit-section-card { overflow: hidden; background: #ffffff; border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow-xs); }
        .edit-section-head { display: flex; align-items: flex-start; gap: .65rem; padding: .85rem .9rem; background: linear-gradient(180deg, #ffffff, #fbfdff); border-bottom: 1px solid var(--line); }
        .edit-section-icon { flex: 0 0 auto; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 13px; }
        .edit-section-title { margin: .05rem 0 .12rem; color: var(--text); font-size: .92rem; font-weight: 850; letter-spacing: -.018em; }
        .edit-section-help { margin: 0; color: var(--muted); font-size: .78rem; font-weight: 650; line-height: 1.45; }
        .edit-section-body { padding: .9rem; }
        .edit-form-grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: .75rem; }
        .edit-field { min-width: 0; }
        .edit-col-3 { grid-column: span 3; }
        .edit-col-4 { grid-column: span 4; }
        .edit-col-6 { grid-column: span 6; }
        .edit-col-12 { grid-column: span 12; }
        .edit-field .form-label { display: flex; align-items: center; gap: .35rem; margin-bottom: .38rem; color: #344054; font-size: .78rem; font-weight: 850 !important; letter-spacing: -.005em; }
        .edit-field .form-label i { color: var(--primary); font-size: .8rem; }
        .edit-field .form-control, .edit-field .form-select { width: 100%; min-height: 44px; border-radius: 13px !important; }
        .edit-field textarea.form-control { min-height: 96px; }
        .edit-field small { display: block; margin-top: .35rem; color: var(--muted); font-size: .74rem; font-weight: 650; line-height: 1.35; }
        .edit-file-card { display: grid; grid-template-columns: minmax(0, 1fr); gap: .45rem; padding: .75rem; background: #f9fafb; border: 1px dashed var(--line-strong); border-radius: 16px; }
        .edit-current-file { display: inline-flex; align-items: center; gap: .38rem; width: fit-content; max-width: 100%; padding: .38rem .58rem; color: var(--info); background: var(--info-soft); border: 1px solid #dbeafe; border-radius: 999px; font-size: .74rem; font-weight: 850; overflow-wrap: anywhere; }
        .edit-required-note { display: inline-flex; align-items: center; gap: .42rem; width: fit-content; margin-top: .15rem; padding: .5rem .65rem; color: #9a3412; background: var(--warning-soft); border: 1px solid #fed7aa; border-radius: 999px; font-size: .76rem; font-weight: 760; }
        .modal-footer[id^="viewFooterEdit"] .btn-primary { min-width: 150px; }
        @media (max-width: 991px) { .edit-col-3, .edit-col-4 { grid-column: span 6; } }
        @media (max-width: 768px) { .edit-info-banner, .modern-edit-form, .edit-section-card { border-radius: 16px; } .modern-edit-form { padding: .75rem; } .edit-section-head, .edit-section-body { padding: .8rem; } .edit-col-3, .edit-col-4, .edit-col-6, .edit-col-12 { grid-column: span 12; } }

    

        /* Clearance-specific consistency helpers */
        .purpose-badge,
        .fee-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            width: fit-content;
            max-width: 100%;
            padding: .38rem .62rem !important;
            color: var(--info) !important;
            background: var(--info-soft) !important;
            border: 1px solid rgba(3,105,161,.12) !important;
            border-radius: 999px !important;
            font-size: .76rem !important;
            font-weight: 850 !important;
            line-height: 1.2;
            white-space: nowrap;
        }

        .fee-pill.fee-pending {
            color: var(--warning) !important;
            background: var(--warning-soft) !important;
            border-color: rgba(217,119,6,.15) !important;
        }

        #clearanceTable {
            min-width: 780px !important;
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

        {{-- Dashboard Link --}}
        @admin_can('view_dashboard')
        <a href="{{ route('admin.dashboard.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        @endadmin_can

        <div class="menu-section">Administrative</div>
        
        {{-- Registry Dropdown --}}
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
            <a href="{{ route('admin.residents.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-user"></i> <span>Residents</span>
            </a>
            @endadmin_can
            
            @admin_can('view_residency')
            <a href="{{ route('admin.residency.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-alt"></i> <span>Residency Applications</span>
            </a>
            @endadmin_can
            
            @admin_can('view_indigency')
            <a href="{{ route('admin.indigency.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-invoice"></i> <span>Indigency</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Legal</div>
        
        {{-- Records Dropdown --}}
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
            <a href="{{ route('admin.clearance.index') }}" class="active" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-contract"></i> <span>Clearances</span>
            </a>
            @endadmin_can
            
            @admin_can('view_blotter')
            <a href="{{ route('admin.blotter.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-book"></i> <span>Incident Reports</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Community</div>
        
        {{-- Community Dropdown --}}
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
            <a href="{{ route('admin.announcements.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-bullhorn"></i> <span>Announcements</span>
            </a>
            @endadmin_can
            
            @admin_can('view_events')
            <a href="{{ route('admin.events.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-calendar"></i> <span>Events</span>
            </a>
            @endadmin_can
            
            @admin_can('view_projects')
            <a href="{{ route('admin.projects.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-project-diagram"></i> <span>Projects</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">System</div>
        
        {{-- Users Link --}}
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
                    <span class="title-icon"><i class="fas fa-file-contract"></i></span>
                    Clearance Applications
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="residency-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show residency-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show residency-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show residency-alert" role="alert">
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
                        <div class="residency-hero-icon"><i class="fas fa-file-contract"></i></div>
                        <div class="residency-kicker"><i class="fas fa-circle-check"></i> Clearance application management</div>
                        <h2>Review clearance requests with a cleaner, focused workspace.</h2>
                        <p>Keep the table easy to scan while still giving access to full applicant details, uploaded documents, status updates, and certificate generation from the action modals.</p>
                    </div>

                    <div class="residency-hero-actions">
                        @admin_can('create_clearance')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                            <i class="fas fa-plus"></i>
                            New Application
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.clearance.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset View
                        </a>
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Approved: <strong>{{ $approved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Rejected: <strong>{{ $rejected_count ?? 0 }}</strong></span>
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
                            <i class="fas fa-file-contract"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Processing</div>
                            <div class="stat-number">{{ $processing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Needs review
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
                            <div class="stat-label text-muted mb-1">Approved</div>
                            <div class="stat-number">{{ $approved_count ?? 0 }}</div>
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
                            <div class="stat-label text-muted mb-1">Rejected</div>
                            <div class="stat-number">{{ $rejected_count ?? 0 }}</div>
                            <small class="text-danger mt-2 d-none d-sm-block">
                                <i class="fas fa-times-circle me-1"></i>Not approved
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
                    <form method="GET" action="{{ route('admin.clearance.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Applications</h6>
                                    <p>Search by applicant, reference number, status, or purpose.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_clearance')
                                    <a href="#" class="btn btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">New Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.clearance.index') }}" class="btn btn-light-soft flex-fill flex-md-grow-0">
                                        <i class="fas fa-rotate"></i><span class="d-none d-sm-inline ms-2">Reset View</span>
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
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by name, reference, email..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="ready_pickup" {{ request('status') == 'ready_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                    <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="purpose" class="form-select">
                                    <option value="">All Purposes</option>
                                    <option value="employment"  {{ request('purpose') == 'employment' ? 'selected' : '' }}>Employment</option>
                                    <option value="business" {{ request('purpose') == 'business' ? 'selected' : '' }}>Business Permit</option>
                                    <option value="scholarship" {{ request('purpose') == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                    <option value="travel" {{ request('purpose') == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                    <option value="bank" {{ request('purpose') == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                    <option value="government" {{ request('purpose') == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                    <option value="school" {{ request('purpose') == 'school' ? 'selected' : '' }}>School Requirement</option>
                                    <option value="other" {{ request('purpose') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions - Only show if user has delete permission -->
                    @if(auth('admin')->user()->hasPermission('delete_clearance') || auth('admin')->user()->hasPermission('export_clearance'))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_clearance')
                        <form id="bulkForm" method="POST" action="{{ route('admin.clearance.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Archive</span>
                            </button>
                        </form>
                        @endadmin_can
                        
                        @admin_can('export_clearance')
                        <form id="exportForm" method="POST" action="{{ route('admin.clearance.export') }}" style="display: inline;">
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

            <!-- Clearance Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0 residency-table-card">
                <div class="residency-table-head">
                    <div class="table-head-title">
                        <span class="table-head-icon"><i class="fas fa-table-list"></i></span>
                        <div class="min-w-0">
                            <span class="table-kicker"><i class="fas fa-database"></i> Application records</span>
                            <h3>Clearance application queue</h3>
                            <p>Use the action buttons to view details, update status, generate documents, or archive records.</p>
                        </div>
                    </div>
                    <span class="table-count-pill"><i class="fas fa-list"></i>{{ $clearances->total() ?? 0 }} records</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="clearanceTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Applicant / Reference
                                            @if(request('sort') == 'created_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Applicant details</th>
                                    <th>Purpose / Fee</th>
                                    <th>Status / Submitted</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($clearances as $app)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $app->id }}" class="application-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="application-primary">
                                            <div class="application-avatar">
                                                {{ strtoupper(substr($app->first_name ?? '', 0, 1) . substr($app->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="application-name">
                                                    {{ $app->first_name }} {{ $app->last_name }}{{ $app->suffix ? ' ' . $app->suffix : '' }}
                                                </div>
                                                <div class="application-ref">
                                                    <span class="ref-pill"><i class="fas fa-hashtag"></i>{{ $app->reference_number }}</span>
                                                    <span>{{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}</span>
                                                </div>
                                                <div class="application-muted-line d-lg-none">
                                                    <i class="fas fa-phone"></i>{{ $app->contact_number ?: 'No contact' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Applicant details</span>
                                            <span class="info-main">
                                                {{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('M d, Y') : 'N/A' }}
                                            </span>
                                            <span class="info-sub">
                                                <i class="fas fa-venus-mars me-1"></i>{{ $app->gender ? ucfirst($app->gender) : 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Purpose</span>
                                            <span class="info-main">
                                                {{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }}
                                                @if($app->purpose_other)
                                                    <span class="info-sub">({{ $app->purpose_other }})</span>
                                                @endif
                                            </span>
                                            <span class="info-sub">
                                                <i class="fas fa-receipt me-1"></i>
                                                @if(is_null($app->fee))
                                                    Depending on purpose
                                                @else
                                                    PHP {{ number_format((float) $app->fee, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-cell">
                                            @if($app->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger">Archived</span>
                                            @elseif($app->status == 'approved')
                                                <span class="badge bg-success-subtle text-success">Approved</span>
                                            @elseif($app->status == 'ready_pickup')
                                                <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                            @elseif($app->status == 'claimed')
                                                <span class="badge bg-success-subtle text-success">Claimed</span>
                                            @elseif($app->status == 'rejected')
                                                <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                            @elseif($app->status == 'pending')
                                                <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                                            @elseif($app->status == 'under_review')
                                                <span class="badge bg-primary-subtle text-primary">Under Review</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                                            @endif
                                            <span class="status-date"><i class="fas fa-calendar-day me-1"></i>{{ $app->created_at ? $app->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm action-btn action-view icon-only" data-bs-toggle="modal" data-bs-target="#viewModal{{ $app->id }}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Status Change Button -->
                                            @if(!$app->deleted_at && (auth('admin')->user()->hasPermission('update_clearance') || 
                                                auth('admin')->user()->hasPermission('approve_clearance') || 
                                                auth('admin')->user()->hasPermission('reject_clearance')))
                                            <button type="button" class="btn btn-sm action-btn action-update" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $app->id }}" title="Update">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-update" disabled title="Update (No permission)">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @endif

                                            <!-- Document Button -->
                                            @if(!$app->deleted_at && auth('admin')->user()->hasPermission('generate_clearance_document') && 
                                                in_array($app->status, ['approved', 'ready_pickup', 'claimed']))
                                            <button type="button" class="btn btn-sm action-btn action-docs icon-only" data-bs-toggle="modal" data-bs-target="#docsModal{{ $app->id }}" title="Docs">
                                                <i class="fas fa-file-lines"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-docs icon-only" disabled title="Docs (Unavailable)">
                                                <i class="fas fa-file-lines"></i>
                                            </button>
                                            @endif

                                            <!-- Archive / Restore Button -->
                                            @if($app->deleted_at && auth('admin')->user()->hasPermission('delete_clearance'))
                                            <form method="POST" action="{{ route('admin.clearance.restore', $app->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this application?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm action-btn action-update icon-only" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @elseif(auth('admin')->user()->hasPermission('delete_clearance'))
                                            <form method="POST" action="{{ route('admin.clearance.destroy', $app->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this application?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm action-btn action-archive icon-only" title="Archive">
                                                    <i class="fas fa-box-archive"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-archive icon-only" disabled title="Archive (No permission)">
                                                <i class="fas fa-box-archive"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state py-4">
                                            <span class="empty-state-icon mb-3"><i class="fas fa-file-contract"></i></span>
                                            <h5>No clearance applications found</h5>
                                            <p>Try adjusting your search/filter or create a new clearance application.</p>
                                            @admin_can('create_clearance')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                                <i class="fas fa-plus me-2"></i>New Application
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
                    @if(isset($clearances) && $clearances->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $clearances->firstItem() ?? 0 }}-{{ $clearances->lastItem() ?? 0 }} of {{ $clearances->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $clearances->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Application Modal with Validation - Only show if user has create_clearance permission -->
            @admin_can('create_clearance')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-contract me-2"></i>
                                New Barangay Clearance Application
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.clearance.store') }}" enctype="multipart/form-data" id="addApplicationForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="row g-3">
                                    <!-- Personal Information -->
                                    <div class="col-12">
                                        <h6 class="modal-section-title"><i class="fas fa-user"></i> Personal Information</h6>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('first_name') is-invalid @enderror @endif" 
                                               name="first_name" value="{{ session('form_type') == 'add' ? old('first_name') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('middle_name') is-invalid @enderror @endif" 
                                               name="middle_name" value="{{ session('form_type') == 'add' ? old('middle_name') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('last_name') is-invalid @enderror @endif" 
                                               name="last_name" value="{{ session('form_type') == 'add' ? old('last_name') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Suffix</label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('suffix') is-invalid @enderror @endif" 
                                                name="suffix">
                                            <option value="">None</option>
                                            <option value="Jr." {{ (session('form_type') == 'add' && old('suffix') == 'Jr.') ? 'selected' : '' }}>Jr.</option>
                                            <option value="Sr." {{ (session('form_type') == 'add' && old('suffix') == 'Sr.') ? 'selected' : '' }}>Sr.</option>
                                            <option value="II" {{ (session('form_type') == 'add' && old('suffix') == 'II') ? 'selected' : '' }}>II</option>
                                            <option value="III" {{ (session('form_type') == 'add' && old('suffix') == 'III') ? 'selected' : '' }}>III</option>
                                            <option value="IV" {{ (session('form_type') == 'add' && old('suffix') == 'IV') ? 'selected' : '' }}>IV</option>
                                            <option value="V" {{ (session('form_type') == 'add' && old('suffix') == 'V') ? 'selected' : '' }}>V</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('suffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" 
                                               name="birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ date('Y-m-d') }}">
                                        @if(session('form_type') == 'add')
                                            @error('birthdate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('gender') is-invalid @enderror @endif" 
                                                name="gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male" {{ (session('form_type') == 'add' && old('gender') == 'male') ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ (session('form_type') == 'add' && old('gender') == 'female') ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('gender') == 'other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="modal-section-title"><i class="fas fa-address-book"></i> Contact Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('email') is-invalid @enderror @endif" 
                                               name="email" value="{{ session('form_type') == 'add' ? old('email') : '' }}" placeholder="email@example.com" required>
                                        @if(session('form_type') == 'add')
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('contact_number') is-invalid @enderror @endif" 
                                               name="contact_number" value="{{ session('form_type') == 'add' ? old('contact_number') : '' }}" placeholder="09XXXXXXXXX" maxlength="11" required>
                                        <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                        @if(session('form_type') == 'add')
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('address') is-invalid @enderror @endif" 
                                                  name="address" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'add' ? old('address') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Clearance Details -->
                                    <div class="col-12 mt-3">
                                        <h6 class="modal-section-title"><i class="fas fa-file-contract"></i> Clearance Details</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('purpose') is-invalid @enderror @endif" 
                                                name="purpose" id="purpose" required>
                                            <option value="">Select purpose</option>
                                            <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment</option>
                                            <option value="business" {{ (session('form_type') == 'add' && old('purpose') == 'business') ? 'selected' : '' }}>Business Permit</option>
                                            <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship</option>
                                            <option value="travel" {{ (session('form_type') == 'add' && old('purpose') == 'travel') ? 'selected' : '' }}>Travel/Abroad</option>
                                            <option value="bank" {{ (session('form_type') == 'add' && old('purpose') == 'bank') ? 'selected' : '' }}>Bank Transaction</option>
                                            <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government Transaction</option>
                                            <option value="school" {{ (session('form_type') == 'add' && old('purpose') == 'school') ? 'selected' : '' }}>School Requirement</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('purpose') == 'other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('purpose')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12" id="otherPurposeField" style="{{ (session('form_type') == 'add' && old('purpose') == 'other') ? 'display:block;' : 'display:none;' }}">
                                        <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('purpose_other') is-invalid @enderror @endif" 
                                               name="purpose_other" value="{{ session('form_type') == 'add' ? old('purpose_other') : '' }}" placeholder="Please specify">
                                        @if(session('form_type') == 'add')
                                            @error('purpose_other')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Fee (PHP)</label>
                                        <input type="number" class="form-control @if(session('form_type') == 'add') @error('fee') is-invalid @enderror @endif"
                                               name="fee" value="{{ session('form_type') == 'add' ? old('fee') : '' }}"
                                               min="0" step="0.01" placeholder="Leave empty = Depending on purpose">
                                        <small class="text-muted">Leave blank if fee depends on purpose.</small>
                                        @if(session('form_type') == 'add')
                                            @error('fee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Proof of Residency <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('primary_proof') is-invalid @enderror @endif" 
                                               name="primary_proof" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('primary_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Valid ID <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('valid_id_path') is-invalid @enderror @endif" 
                                               name="valid_id_path" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('valid_id_path')
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
                                <button type="submit" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Application Modals with Validation - Only show for users with update_clearance permission -->
            @if(auth('admin')->user()->hasPermission('update_clearance'))
                @foreach($clearances as $app)
                <div class="modal fade" id="editClearanceModal{{ $app->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Clearance Application - {{ $app->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.clearance.update', $app->id) }}" enctype="multipart/form-data" id="editClearanceForm{{ $app->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $app->id }}">

                                    <div class="row g-3">
                                        <!-- Personal Information -->
                                        <div class="col-12">
                                            <h6 class="modal-section-title"><i class="fas fa-user"></i> Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('first_name') is-invalid @enderror @endif" 
                                                name="first_name" id="edit_first_name_{{ $app->id }}" 
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('first_name', $app->first_name) : $app->first_name }}" 
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('middle_name') is-invalid @enderror @endif" 
                                                name="middle_name" id="edit_middle_name_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('middle_name', $app->middle_name) : $app->middle_name }}">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('last_name') is-invalid @enderror @endif" 
                                                name="last_name" id="edit_last_name_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('last_name', $app->last_name) : $app->last_name }}" 
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('suffix') is-invalid @enderror @endif" 
                                                    name="suffix" id="edit_suffix_{{ $app->id }}">
                                                <option value="">None</option>
                                                <option value="Jr." {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                <option value="Sr." {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                <option value="II" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                                <option value="III" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                                <option value="V" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'V' ? 'selected' : '' }}>V</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('birthdate') is-invalid @enderror @endif" 
                                                name="birthdate" id="edit_birthdate_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('birthdate', $app->birthdate) : $app->birthdate }}" 
                                                required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('gender') is-invalid @enderror @endif" 
                                                    name="gender" id="edit_gender_{{ $app->id }}" required>
                                                <option value="">Select gender</option>
                                                <option value="male" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Contact Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="modal-section-title"><i class="fas fa-address-book"></i> Contact Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('email') is-invalid @enderror @endif" 
                                                name="email" id="edit_email_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('email', $app->email) : $app->email }}" 
                                                placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('contact_number') is-invalid @enderror @endif" 
                                                name="contact_number" id="edit_contact_number_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('contact_number', $app->contact_number) : $app->contact_number }}" 
                                                placeholder="09XXXXXXXXX" maxlength="11" required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('contact_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('address') is-invalid @enderror @endif" 
                                                    name="address" id="edit_address_{{ $app->id }}"
                                                    rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $app->id ? old('address', $app->address) : $app->address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Clearance Details -->
                                        <div class="col-12 mt-3">
                                            <h6 class="modal-section-title"><i class="fas fa-file-contract"></i> Clearance Details</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('purpose') is-invalid @enderror @endif" 
                                                    name="purpose" id="edit_purpose_{{ $app->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'employment' ? 'selected' : '' }}>Employment</option>
                                                <option value="business" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'business' ? 'selected' : '' }}>Business Permit</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                <option value="travel" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                                <option value="bank" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="government" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                                <option value="school" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'school' ? 'selected' : '' }}>School Requirement</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('purpose')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12" id="edit_otherPurposeField_{{ $app->id }}" style="{{ (session('form_type') == 'edit_' . $app->id && old('purpose', $app->purpose) == 'other') ? 'display:block;' : 'display:none;' }}">
                                            <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('purpose_other') is-invalid @enderror @endif" 
                                                name="purpose_other" id="edit_purpose_other_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('purpose_other', $app->purpose_other) : $app->purpose_other }}" 
                                                placeholder="Please specify">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('purpose_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Fee (PHP)</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('fee') is-invalid @enderror @endif"
                                                name="fee" id="edit_fee_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('fee', $app->fee) : $app->fee }}"
                                                min="0" step="0.01" placeholder="Leave empty = Depending on purpose">
                                            <small class="text-muted">Leave blank if fee depends on purpose.</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Documents -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current file)</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Proof of Residency</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('primary_proof') is-invalid @enderror @endif" 
                                                name="primary_proof" id="edit_primary_proof_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('primary_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('valid_id_path') is-invalid @enderror @endif" 
                                                name="valid_id_path" id="edit_valid_id_path_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('valid_id_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        
                                        @if($app->primary_proof)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current proof of residency: {{ basename($app->primary_proof) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($app->valid_id_path)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current file: {{ basename($app->valid_id_path) }}
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitEditForm{{ $app->id }}">
                                        <i class="fas fa-save me-2"></i>Update Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($clearances as $app)
            <div class="modal fade" id="viewModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">{{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}</div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $app->first_name }} {{ $app->last_name }}</div>
                                    <div class="view-modal-sub">{{ $app->reference_number }} - Clearance Certificate</div>
                                    <div class="mt-1">
                                        @if($app->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @elseif($app->status == 'ready_pickup')
                                            <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                        @elseif($app->status == 'claimed')
                                            <span class="badge bg-primary-subtle text-primary">Claimed</span>
                                        @elseif($app->status == 'under_review')
                                            <span class="badge bg-secondary-subtle text-secondary">Under Review</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $app->id }}" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active" id="details-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $app->id }}" type="button">Details</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="edit-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $app->id }}" type="button">Edit info</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $app->id }}" type="button">History</button></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details-panel-{{ $app->id }}" role="tabpanel" aria-labelledby="details-tab-{{ $app->id }}">
                                    <div class="application-modal-summary">
                                        <div class="modal-summary-card is-primary">
                                            <span class="modal-summary-label">Applicant</span>
                                            <span class="modal-summary-value">{{ $app->first_name }} {{ $app->middle_name }} {{ $app->last_name }} {{ $app->suffix }}</span>
                                            <span class="modal-summary-help"><i class="fas fa-hashtag me-1"></i>{{ $app->reference_number }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Current Status</span>
                                            <span class="modal-summary-value">
                                                @if($app->deleted_at) Archived
                                                @elseif($app->status == 'ready_pickup') Ready for Pickup
                                                @else {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                                @endif
                                            </span>
                                            <span class="modal-summary-help">Application progress</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Purpose</span>
                                            <span class="modal-summary-value">{{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $app->purpose_other ?: 'Barangay clearance' }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Submitted</span>
                                            <span class="modal-summary-value">{{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $app->created_at ? $app->created_at->format('h:i A') : '' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-user-check"></i>Important applicant information</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Birthdate</span>
                                                <div class="detail-value">{{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('F d, Y') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Gender</span>
                                                <div class="detail-value">{{ ucfirst($app->gender) }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Email</span>
                                                <div class="detail-value">{{ $app->email ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Contact number</span>
                                                <div class="detail-value">{{ $app->contact_number ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Address</span>
                                                <div class="detail-value">{{ $app->address ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-file-contract"></i>Clearance request</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Purpose</span>
                                                <div class="detail-value">{{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }} {{ $app->purpose_other ? '(' . $app->purpose_other . ')' : '' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Fee</span>
                                                <div class="detail-value">{{ is_null($app->fee) ? 'Depending on purpose' : 'PHP ' . number_format((float) $app->fee, 2) }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Reference number</span>
                                                <div class="detail-value">{{ $app->reference_number }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Date submitted</span>
                                                <div class="detail-value">{{ $app->created_at ? $app->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-folder-open"></i>Required documents</div>
                                        <div class="document-preview-grid">
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-house me-1"></i>Proof of Residency</span></div>
                                                <div class="doc-body">
                                                    @if($app->primary_proof)
                                                        <img src="{{ asset($app->primary_proof) }}" alt="Proof of residency" onclick="openZoomModal('{{ asset($app->primary_proof) }}')">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No proof uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID</span></div>
                                                <div class="doc-body">
                                                    @if($app->valid_id_path)
                                                        <img src="{{ asset($app->valid_id_path) }}" alt="Valid ID" onclick="openZoomModal('{{ asset($app->valid_id_path) }}')">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No valid ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade edit-info-pane" id="edit-panel-{{ $app->id }}" role="tabpanel" aria-labelledby="edit-tab-{{ $app->id }}">
                                    <div class="edit-info-banner">
                                        <div class="application-avatar">
                                            {{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="edit-info-title">Edit information for {{ $app->first_name }} {{ $app->last_name }}</div>
                                            <div class="edit-info-subtitle">
                                                Reference {{ $app->reference_number }} · Update applicant details, clearance purpose, fee, and replacement documents.
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.clearance.update', $app->id) }}" id="quickEditForm{{ $app->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
                                        @csrf
                                        @method('PUT')

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Personal Information</div>
                                                    <p class="edit-section-help">Keep the applicant identity accurate for document generation.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>First Name</label>
                                                        <input type="text" name="first_name" class="form-control" value="{{ $app->first_name }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>Middle Name</label>
                                                        <input type="text" name="middle_name" class="form-control" value="{{ $app->middle_name }}" maxlength="255">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" value="{{ $app->last_name }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-signature"></i>Suffix</label>
                                                        <select class="form-select" name="suffix">
                                                            <option value="">None</option>
                                                            <option value="Jr." {{ $app->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                            <option value="Sr." {{ $app->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                            <option value="II" {{ $app->suffix == 'II' ? 'selected' : '' }}>II</option>
                                                            <option value="III" {{ $app->suffix == 'III' ? 'selected' : '' }}>III</option>
                                                            <option value="IV" {{ $app->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                                            <option value="V" {{ $app->suffix == 'V' ? 'selected' : '' }}>V</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-cake-candles"></i>Birthdate</label>
                                                        <input type="date" name="birthdate" class="form-control" value="{{ $app->birthdate }}" required max="{{ date('Y-m-d') }}">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label>
                                                        <select class="form-select" name="gender" required>
                                                            <option value="male" {{ $app->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $app->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ $app->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Contact Information</div>
                                                    <p class="edit-section-help">These details are used for follow-up and notifications.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $app->email }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Contact Number</label>
                                                        <input type="text" name="contact_number" class="form-control" value="{{ $app->contact_number }}" maxlength="11" inputmode="numeric" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Address</label>
                                                        <textarea name="address" class="form-control" rows="2" maxlength="500" required>{{ $app->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-file-contract"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Clearance Details</div>
                                                    <p class="edit-section-help">Update the purpose and fee shown in the request queue and certificate workflow.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-clipboard-check"></i>Purpose</label>
                                                        <select class="form-select" name="purpose" required>
                                                            <option value="employment" {{ $app->purpose == 'employment' ? 'selected' : '' }}>Employment</option>
                                                            <option value="business" {{ $app->purpose == 'business' ? 'selected' : '' }}>Business Permit</option>
                                                            <option value="scholarship" {{ $app->purpose == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                            <option value="travel" {{ $app->purpose == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                                            <option value="bank" {{ $app->purpose == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                            <option value="government" {{ $app->purpose == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                                            <option value="school" {{ $app->purpose == 'school' ? 'selected' : '' }}>School Requirement</option>
                                                            <option value="other" {{ $app->purpose == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4 quick-purpose-other-field {{ $app->purpose === 'other' ? '' : 'd-none' }}">
                                                        <label class="form-label"><i class="fas fa-pen-line"></i>Purpose (Other)</label>
                                                        <input type="text" name="purpose_other" class="form-control" value="{{ $app->purpose_other }}" maxlength="255">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-peso-sign"></i>Fee (PHP)</label>
                                                        <input type="number" name="fee" class="form-control" min="0" step="0.01" inputmode="decimal" value="{{ $app->fee }}" placeholder="Leave empty if depends on purpose">
                                                        <small>Leave empty when the fee depends on purpose.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-folder-open"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Documents</div>
                                                    <p class="edit-section-help">Upload only when replacing the current file. Existing uploads remain if left empty.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-house"></i>Proof of Residency</label>
                                                        <div class="edit-file-card">
                                                            <input type="file" name="primary_proof" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF, maximum 5MB.</small>
                                                            @if($app->primary_proof)
                                                                <span class="edit-current-file"><i class="fas fa-paperclip"></i>{{ basename($app->primary_proof) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-id-card"></i>Valid ID</label>
                                                        <div class="edit-file-card">
                                                            <input type="file" name="valid_id_path" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF, maximum 5MB.</small>
                                                            @if($app->valid_id_path)
                                                                <span class="edit-current-file"><i class="fas fa-paperclip"></i>{{ basename($app->valid_id_path) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="edit-required-note"><i class="fas fa-circle-info"></i>Leave file fields empty to keep current documents.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history-panel-{{ $app->id }}" role="tabpanel">
                                    <div class="mb-2">Recent status: @if($app->status == 'approved')<span class="badge bg-success-subtle text-success">Approved</span>@elseif($app->status == 'rejected')<span class="badge bg-danger-subtle text-danger">Rejected</span>@elseif($app->status == 'ready_pickup')<span class="badge bg-info-subtle text-info">Ready for Pickup</span>@elseif($app->status == 'claimed')<span class="badge bg-primary-subtle text-primary">Claimed</span>@elseif($app->status == 'under_review')<span class="badge bg-secondary-subtle text-secondary">Under Review</span>@else<span class="badge bg-warning-subtle text-warning">Processing</span>@endif</div>
                                    <div id="historyList{{ $app->id }}" class="small text-muted">Loading history...</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="viewFooterDetails{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-switch-tab="edit" data-app-id="{{ $app->id }}">Edit info</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterEdit{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-switch-tab="details" data-app-id="{{ $app->id }}">Cancel</button>
                            <button type="submit" form="quickEditForm{{ $app->id }}" class="btn btn-primary">Save changes</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterHistory{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="docsModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-file-lines me-2"></i>Document Actions</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">@if(in_array($app->status, ['approved', 'ready_pickup', 'claimed']))<div class="d-grid gap-2"><form method="GET" action="{{ route('admin.clearance.document.clearance_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $app->id }}"><button type="submit" name="action" value="download" class="btn btn-outline-primary w-100">Download Word</button></form><form method="GET" action="{{ route('admin.clearance.document.clearance_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $app->id }}"><button type="submit" name="action" value="print" class="btn btn-outline-success w-100">Print PDF</button></form></div>@else<p class="mb-0 text-muted">Documents are available only when status is Approved, Ready for Pickup, or Claimed.</p>@endif</div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div></div></div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update application status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.clearance.status', $app->id) }}" onsubmit="return handleUpdateStatusSubmit(event, this)">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-2 small text-muted">{{ $app->first_name }} {{ $app->last_name }} - {{ $app->reference_number }}</div>
                                <div class="mb-2 small"><span class="text-muted">Purpose:</span> <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $app->purpose)) }}{{ $app->purpose_other ? ' (' . $app->purpose_other . ')' : '' }}</span></div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Fee (PHP)</label>
                                    <input type="number" class="form-control" name="fee" min="0" step="0.01" value="{{ $app->fee }}" placeholder="Leave empty if depends on purpose">
                                    <small class="text-muted">This fee will be saved when updating status.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">New status</label>
                                    <input type="hidden" name="status" value="{{ $app->status }}" class="selected-status-input">
                                    <div class="status-grid" data-current-status="{{ $app->status }}">
                                        <button type="button" class="status-option" data-status="pending"><span class="dot" style="background:#ed6c02;"></span>Pending</button>
                                        <button type="button" class="status-option" data-status="under_review"><span class="dot" style="background:#0288d1;"></span>Under review</button>
                                        <button type="button" class="status-option" data-status="processing"><span class="dot" style="background:#5e35b1;"></span>Processing</button>
                                        <button type="button" class="status-option" data-status="approved"><span class="dot" style="background:#2e7d32;"></span>Approved</button>
                                        <button type="button" class="status-option" data-status="ready_pickup"><span class="dot" style="background:#00acc1;"></span>Ready for pickup</button>
                                        <button type="button" class="status-option" data-status="claimed"><span class="dot" style="background:#43a047;"></span>Claimed</button>
                                        <button type="button" class="status-option" data-status="rejected" style="grid-column: span 3;"><span class="dot" style="background:#d32f2f;"></span>Rejected</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Remarks <span class="text-muted fw-normal">(optional - required if rejected, max 40 chars)</span></label>
                                    <textarea class="form-control" name="remarks" rows="3" maxlength="40" placeholder="Add a note about this status change..."></textarea>
                                </div>
                                <div class="{{ in_array($app->status, ['approved', 'ready_pickup', 'rejected']) ? '' : 'd-none' }}" data-notify-wrap data-has-email="{{ $app->email ? '1' : '0' }}" data-has-sms="{{ $app->contact_number ? '1' : '0' }}">
                                    <label class="form-label text-uppercase fw-semibold small">Notify applicant</label>
                                    <div class="notify-row">
                                        <div class="notify-label">
                                            <strong>Send email</strong>
                                            <small>{{ $app->email ?: 'No email available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" checked {{ !$app->email ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="notify-row mb-0">
                                        <div class="notify-label">
                                            <strong>Send SMS</strong>
                                            <small>{{ $app->contact_number ?: 'No mobile number available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_sms" value="1" checked {{ !$app->contact_number ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Notifications are available for Approved, Ready for Pickup, and Rejected status only.</small>
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
                    var addModal = new bootstrap.Modal(document.getElementById('addApplicationModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editClearanceModal{{ $editId }}'));
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
                    text: 'Please select at least one application to archive.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Archive',
                text: `Are you sure you want to archive ${checkboxes.length} selected application(s)?`,
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
                    text: 'No applications selected. Export all records?',
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
                text: `Export ${checkboxes.length} selected application(s)?`,
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

        // Show/hide other purpose field
        document.getElementById('purpose')?.addEventListener('change', function() {
            const otherField = document.getElementById('otherPurposeField');
            if (this.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }
        });

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
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
                var addModal = new bootstrap.Modal(document.getElementById('addApplicationModal'));
                addModal.show();
            @endif

            // Real-time validation for add form
            const addForm = document.getElementById('addApplicationForm');
            if (addForm) {
                // Contact number validation
                const contact = document.querySelector('input[name="contact_number"]');
                if (contact) {
                    contact.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '');
                        if (this.value.length > 11) {
                            this.value = this.value.slice(0, 11);
                        }
                        if (this.value.length > 0 && !this.value.startsWith('09')) {
                            this.setCustomValidity('Contact number must start with 09');
                            this.classList.add('is-invalid');
                        }
                        else if (this.value.length > 0 && this.value.length !== 11) {
                            this.setCustomValidity('Contact number must be exactly 11 digits');
                            this.classList.add('is-invalid');
                        }
                        else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // Birthdate validation
                const birthdate = document.querySelector('input[name="birthdate"]');
                if (birthdate) {
                    birthdate.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const today = new Date();
                        if (selectedDate > today) {
                            this.setCustomValidity('Birthdate cannot be in the future');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // File size validation
                const fileInputs = addForm.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const fileSize = this.files[0].size / 1024 / 1024; // in MB
                            if (fileSize > 5) {
                                this.setCustomValidity('File size must not exceed 5MB');
                                this.classList.add('is-invalid');
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        }
                    });
                });
            }
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
            zoomImage.src = imageUrl;

            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        // Real-time validation for edit forms
        @if(auth('admin')->user()->hasPermission('update_clearance'))
            @foreach($clearances as $app)
            (function(editId) {
                const editForm = document.getElementById('editClearanceForm' + editId);
                if (editForm) {
                    // Purpose other field toggle
                    const purposeSelect = document.getElementById('edit_purpose_' + editId);
                    const otherField = document.getElementById('edit_otherPurposeField_' + editId);
                    if (purposeSelect && otherField) {
                        purposeSelect.addEventListener('change', function() {
                            if (this.value === 'other') {
                                otherField.style.display = 'block';
                            } else {
                                otherField.style.display = 'none';
                            }
                        });
                    }

                    // Contact number validation
                    const contact = document.getElementById('edit_contact_number_' + editId);
                    if (contact) {
                        contact.addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                            if (this.value.length > 11) {
                                this.value = this.value.slice(0, 11);
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

                    // Birthdate validation
                    const birthdate = document.getElementById('edit_birthdate_' + editId);
                    if (birthdate) {
                        birthdate.addEventListener('change', function() {
                            const selectedDate = new Date(this.value);
                            const today = new Date();
                            if (selectedDate > today) {
                                this.setCustomValidity('Birthdate cannot be in the future');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Birthdate cannot be in the future';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Email validation
                    const email = document.getElementById('edit_email_' + editId);
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

                    // File size validation
                    const validId = document.getElementById('edit_valid_id_path_' + editId);
                    if (validId) {
                        validId.addEventListener('change', function() {
                            if (this.files && this.files[0]) {
                                const fileSize = this.files[0].size / 1024 / 1024; // in MB
                                if (fileSize > 5) {
                                    this.setCustomValidity('File size must not exceed 5MB');
                                    this.classList.add('is-invalid');
                                    
                                    let feedback = this.nextElementSibling;
                                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        this.parentNode.appendChild(feedback);
                                    }
                                    feedback.textContent = 'File size must not exceed 5MB';
                                } else {
                                    this.setCustomValidity('');
                                    this.classList.remove('is-invalid');
                                }
                            }
                        });
                    }

                    // Name validation (letters, spaces, hyphens only)
                    const firstName = document.getElementById('edit_first_name_' + editId);
                    if (firstName) {
                        firstName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('First name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'First name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const lastName = document.getElementById('edit_last_name_' + editId);
                    if (lastName) {
                        lastName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Last name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Last name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }
                }
            })('{{ $app->id }}');
            @endforeach
        @endif
    </script>

    <!-- Fix dropdown clipping inside table-responsive -->
    <script>
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

        function setViewFooter(appId, tab) {
            ['Details', 'Edit', 'History'].forEach(function(label) {
                const el = document.getElementById('viewFooter' + label + appId);
                if (el) el.classList.add('d-none');
            });

            const map = {
                details: 'viewFooterDetails' + appId,
                edit: 'viewFooterEdit' + appId,
                history: 'viewFooterHistory' + appId
            };
            const target = document.getElementById(map[tab] || map.details);
            if (target) target.classList.remove('d-none');
        }

        function renderHistoryList(container, payload) {
            if (!container) return;
            const timeline = Array.isArray(payload.timeline) ? payload.timeline : [];
            if (timeline.length === 0) {
                container.innerHTML = '<div class="text-muted">No activity found for this application yet.</div>';
                return;
            }
            container.innerHTML = timeline.map(function(item) {
                const title = (item.title || item.label || 'Activity').toString();
                const details = item.details ? '<div class="text-muted mt-1">' + item.details + '</div>' : (item.description ? '<div class="text-muted mt-1">' + item.description + '</div>' : '');
                const actor = item.actor ? '<div class="small text-muted">' + item.actor + ' - ' + (item.created_at || item.date || '') + '</div>' : '<div class="small text-muted">' + (item.created_at || item.date || '') + '</div>';
                return '<div class="activity-item py-2 border-bottom">' +
                    '<div class="fw-semibold">' + title + '</div>' +
                    actor +
                    details +
                    '</div>';
            }).join('');
        }

        async function loadHistoryForClearance(appId, referenceNumber) {
            const container = document.getElementById('historyList' + appId);
            if (!container) return;

            container.innerHTML = 'Loading history...';
            try {
                const params = new URLSearchParams({
                    request_type: 'clearance',
                    request_id: String(appId)
                });

                const response = await fetch("{{ route('admin.notifications.remarksHistory') }}?" + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Failed to load history.');
                const payload = await response.json();
                renderHistoryList(container, payload || {});
            } catch (error) {
                container.innerHTML = '<div class="text-danger">' + (error.message || 'Unable to load history.') + '</div>';
            }
        }

        function handleUpdateStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-status-input');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';
            const remarks = remarksInput ? String(remarksInput.value || '').trim() : '';

            if (remarks.length > 40) {
                event.preventDefault();
                alert('Remarks must not exceed 40 characters.');
                return false;
            }

            if (status === 'rejected' && remarks.length === 0) {
                event.preventDefault();
                alert('Remarks are required when status is Rejected.');
                return false;
            }
            return true;
        }

        function toggleNotifyOptions(form, status) {
            const notifyWrap = form.querySelector('[data-notify-wrap]');
            if (!notifyWrap) return;

            const emailToggle = form.querySelector('input[name="notify_email"]');
            const smsToggle = form.querySelector('input[name="notify_sms"]');
            const hasEmail = notifyWrap.getAttribute('data-has-email') === '1';
            const hasSms = notifyWrap.getAttribute('data-has-sms') === '1';
            const allowNotify = ['approved', 'ready_pickup', 'rejected'].includes(String(status || '').toLowerCase());

            notifyWrap.classList.toggle('d-none', !allowNotify);

            if (emailToggle) {
                emailToggle.disabled = !allowNotify || !hasEmail;
                emailToggle.checked = allowNotify && hasEmail;
            }
            if (smsToggle) {
                smsToggle.disabled = !allowNotify || !hasSms;
                smsToggle.checked = allowNotify && hasSms;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            function setQuickEditValidity(field, message) {
                if (!field) {
                    return;
                }

                let feedback = field.parentNode ? field.parentNode.querySelector('.invalid-feedback.quick-edit-feedback') : null;
                field.setCustomValidity(message || '');

                if (message) {
                    field.classList.add('is-invalid');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback quick-edit-feedback';
                        field.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = message;
                } else {
                    field.classList.remove('is-invalid');
                    if (feedback) {
                        feedback.remove();
                    }
                }
            }

            function attachClearanceQuickEditValidation(form) {
                const firstName = form.querySelector('input[name="first_name"]');
                const middleName = form.querySelector('input[name="middle_name"]');
                const lastName = form.querySelector('input[name="last_name"]');
                const birthdate = form.querySelector('input[name="birthdate"]');
                const email = form.querySelector('input[name="email"]');
                const contact = form.querySelector('input[name="contact_number"]');
                const address = form.querySelector('textarea[name="address"]');
                const purpose = form.querySelector('select[name="purpose"]');
                const purposeOtherWrap = form.querySelector('.quick-purpose-other-field');
                const purposeOther = form.querySelector('input[name="purpose_other"]');
                const fee = form.querySelector('input[name="fee"]');
                const files = form.querySelectorAll('input[type="file"]');

                const namePattern = /^[A-Za-z][A-Za-z .'-]*$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const allowedMime = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                const allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

                function validateName(field, label, required) {
                    if (!field) {
                        return true;
                    }

                    const value = field.value.trim();

                    if (!value) {
                        if (required) {
                            setQuickEditValidity(field, label + ' is required.');
                            return false;
                        }

                        setQuickEditValidity(field, '');
                        return true;
                    }

                    if (value.length < 2 || value.length > 255 || !namePattern.test(value)) {
                        setQuickEditValidity(field, label + ' can only contain letters, spaces, apostrophes, periods, and hyphens.');
                        return false;
                    }

                    setQuickEditValidity(field, '');
                    return true;
                }

                function validateBirthdate() {
                    if (!birthdate) {
                        return true;
                    }

                    if (!birthdate.value) {
                        setQuickEditValidity(birthdate, 'Birthdate is required.');
                        return false;
                    }

                    const selectedDate = new Date(birthdate.value + 'T00:00:00');
                    const today = new Date();

                    if (selectedDate > today) {
                        setQuickEditValidity(birthdate, 'Birthdate cannot be in the future.');
                        return false;
                    }

                    setQuickEditValidity(birthdate, '');
                    return true;
                }

                function validateEmail() {
                    if (!email) {
                        return true;
                    }

                    const value = email.value.trim();

                    if (!value) {
                        setQuickEditValidity(email, 'Email is required.');
                        return false;
                    }

                    if (!emailPattern.test(value)) {
                        setQuickEditValidity(email, 'Please enter a valid email address.');
                        return false;
                    }

                    setQuickEditValidity(email, '');
                    return true;
                }

                function validateContact() {
                    if (!contact) {
                        return true;
                    }

                    contact.value = contact.value.replace(/\D/g, '').slice(0, 11);

                    if (!contact.value) {
                        setQuickEditValidity(contact, 'Contact number is required.');
                        return false;
                    }

                    if (!/^09\d{9}$/.test(contact.value)) {
                        setQuickEditValidity(contact, 'Contact number must start with 09 and contain exactly 11 digits.');
                        return false;
                    }

                    setQuickEditValidity(contact, '');
                    return true;
                }

                function validateAddress() {
                    if (!address) {
                        return true;
                    }

                    const value = address.value.trim();

                    if (!value) {
                        setQuickEditValidity(address, 'Address is required.');
                        return false;
                    }

                    if (value.length < 8 || value.length > 500) {
                        setQuickEditValidity(address, 'Address must be between 8 and 500 characters.');
                        return false;
                    }

                    setQuickEditValidity(address, '');
                    return true;
                }

                function validatePurposeOther() {
                    if (!purpose || !purposeOther) {
                        return true;
                    }

                    const isOther = purpose.value === 'other';
                    const value = purposeOther.value.trim();

                    if (!isOther) {
                        setQuickEditValidity(purposeOther, '');
                        return true;
                    }

                    if (!value) {
                        setQuickEditValidity(purposeOther, 'Purpose (Other) is required.');
                        return false;
                    }

                    if (value.length > 255) {
                        setQuickEditValidity(purposeOther, 'Purpose (Other) must not exceed 255 characters.');
                        return false;
                    }

                    setQuickEditValidity(purposeOther, '');
                    return true;
                }

                function validateFee() {
                    if (!fee) {
                        return true;
                    }

                    const value = fee.value.trim();

                    if (!value) {
                        setQuickEditValidity(fee, '');
                        return true;
                    }

                    if (!/^(?:0|[1-9]\d{0,4})(?:\.\d{1,2})?$/.test(value)) {
                        setQuickEditValidity(fee, 'Fee must be a plain number with up to 2 decimals.');
                        return false;
                    }

                    setQuickEditValidity(fee, '');
                    return true;
                }

                function validateFile(fileInput) {
                    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                        setQuickEditValidity(fileInput, '');
                        return true;
                    }

                    const file = fileInput.files[0];
                    const sizeMb = file.size / (1024 * 1024);
                    const extension = (file.name.split('.').pop() || '').toLowerCase();
                    const mimeOk = file.type ? allowedMime.includes(file.type) : true;
                    const extOk = allowedExt.includes(extension);

                    if (!mimeOk || !extOk) {
                        setQuickEditValidity(fileInput, 'Only PDF, JPG, PNG, GIF, or WEBP files are allowed.');
                        return false;
                    }

                    if (sizeMb > 5) {
                        setQuickEditValidity(fileInput, 'File size must not exceed 5MB.');
                        return false;
                    }

                    setQuickEditValidity(fileInput, '');
                    return true;
                }

                function togglePurposeOther() {
                    if (!purpose || !purposeOtherWrap || !purposeOther) {
                        return;
                    }

                    const isOther = purpose.value === 'other';
                    purposeOtherWrap.classList.toggle('d-none', !isOther);
                    purposeOther.required = isOther;
                    if (!isOther) {
                        purposeOther.value = '';
                        setQuickEditValidity(purposeOther, '');
                    }
                }

                firstName?.addEventListener('input', function() { validateName(firstName, 'First name', true); });
                middleName?.addEventListener('input', function() { validateName(middleName, 'Middle name', false); });
                lastName?.addEventListener('input', function() { validateName(lastName, 'Last name', true); });
                birthdate?.addEventListener('change', validateBirthdate);
                email?.addEventListener('input', validateEmail);
                contact?.addEventListener('input', validateContact);
                address?.addEventListener('input', validateAddress);
                purpose?.addEventListener('change', function() {
                    togglePurposeOther();
                    validatePurposeOther();
                });
                purposeOther?.addEventListener('input', validatePurposeOther);
                fee?.addEventListener('input', validateFee);
                files.forEach(function(fileInput) {
                    fileInput.addEventListener('change', function() {
                        validateFile(fileInput);
                    });
                });

                togglePurposeOther();

                form.addEventListener('submit', function(event) {
                    const isValid = [
                        validateName(firstName, 'First name', true),
                        validateName(middleName, 'Middle name', false),
                        validateName(lastName, 'Last name', true),
                        validateBirthdate(),
                        validateEmail(),
                        validateContact(),
                        validateAddress(),
                        validatePurposeOther(),
                        validateFee(),
                        ...Array.from(files).map(validateFile)
                    ].every(Boolean);

                    if (!isValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            }

            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                attachClearanceQuickEditValidation(form);
            });

            document.querySelectorAll('[id^="viewModal"]').forEach(function(modalEl) {
                modalEl.addEventListener('shown.bs.modal', function() {
                    const appId = modalEl.id.replace('viewModal', '');
                    setViewFooter(appId, 'details');
                });
            });

            document.querySelectorAll('[data-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const appId = button.getAttribute('data-app-id');
                    const targetTab = button.getAttribute('data-switch-tab');
                    const tabTrigger = document.querySelector('#' + targetTab + '-tab-' + appId);
                    if (tabTrigger) {
                        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                    }
                });
            });

            document.querySelectorAll('[id^="details-tab-"], [id^="edit-tab-"], [id^="history-tab-"]').forEach(function(tabBtn) {
                tabBtn.addEventListener('shown.bs.tab', function(event) {
                    const id = event.target.id;
                    const appId = id.split('-').pop();
                    if (id.indexOf('details-tab-') === 0) setViewFooter(appId, 'details');
                    if (id.indexOf('edit-tab-') === 0) setViewFooter(appId, 'edit');
                    if (id.indexOf('history-tab-') === 0) {
                        setViewFooter(appId, 'history');
                        loadHistoryForClearance(appId, '');
                    }
                });
            });

            document.querySelectorAll('.status-grid').forEach(function(grid) {
                const input = grid.closest('form').querySelector('.selected-status-input');
                const form = grid.closest('form');
                const current = (grid.getAttribute('data-current-status') || '').toLowerCase();

                const activate = function(value) {
                    grid.querySelectorAll('.status-option').forEach(function(option) {
                        option.classList.toggle('active', option.getAttribute('data-status') === value);
                    });
                    if (input) input.value = value;
                    if (form) toggleNotifyOptions(form, value);
                };

                if (current) activate(current);

                grid.querySelectorAll('.status-option').forEach(function(option) {
                    option.addEventListener('click', function() {
                        activate(option.getAttribute('data-status'));
                    });
                });
            });

            var tableResponsive = document.querySelector('.table-responsive');
            if (!tableResponsive) return;

            tableResponsive.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(toggle) {
                new bootstrap.Dropdown(toggle, {
                    popperConfig: function(defaultConfig) {
                        defaultConfig.strategy = 'fixed';
                        return defaultConfig;
                    }
                });
            });
        });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

