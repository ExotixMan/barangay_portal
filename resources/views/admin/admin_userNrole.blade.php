<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users & Roles Management - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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

        /* Table Styling */
        .table-responsive {
            border-radius: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            min-width: 1000px;
        }

        @media (max-width: 768px) {
            .table {
                min-width: 800px;
            }
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            color: #4a5568;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Badge Styling */
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

        .role-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-block;
        }

        .role-badge.super_admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .role-badge.barangay_captain {
            background: var(--primary-gradient);
            color: white;
        }

        .role-badge.staff {
            background: #17a2b8;
            color: white;
        }

        .role-badge.barangay_secretary {
            background: #fd7e14;
            color: white;
        }

        .role-badge.custom {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-link {
            padding: 0.75rem 1.5rem;
            border: none;
            background: none;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .tab-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .tab-link.active {
            background: var(--primary-gradient);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Role Cards */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .role-card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .role-header {
            padding: 1.25rem;
            color: white;
            position: relative;
        }

        .role-header.super_admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .role-header.barangay_captain {
            background: var(--primary-gradient);
        }

        .role-header.staff {
            background: #17a2b8;
        }

        .role-header.barangay_secretary {
            background: #fd7e14;;
        }

        .role-header.custom {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .role-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .role-count {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .role-body {
            padding: 1.25rem;
        }

        .permission-list {
            margin-bottom: 1rem;
        }

        .permission-item {
            padding: 0.5rem 0;
            font-size: 0.9rem;
            border-bottom: 1px dashed var(--border-color);
        }

        .permission-item:last-child {
            border-bottom: none;
        }

        .permission-item i {
            width: 24px;
            color: var(--success);
        }

        .role-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-small {
            padding: 0.4rem 1rem;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-small:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .create-new {
            text-align: center;
            padding: 1rem;
        }

        .create-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #6c757d;
        }

        .btn-create-role {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-create-role:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        /* Permissions Grid */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .permission-module {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .module-header {
            padding: 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
        }

        .module-header h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
        }

        .module-permissions {
            padding: 1rem;
        }

        .perm-item {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .perm-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .perm-item label {
            margin: 0;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .permissions-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.2s ease;
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

        .btn-outline-success {
            color: var(--success);
            border-color: var(--success);
        }

        .btn-outline-success:hover {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-outline-danger {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-danger:hover {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-success:hover {
            background: #1e5e23;
            border-color: #1e5e23;
        }

        /* Modal Styling */
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

        .modal .modal-dialog {
            max-width: min(960px, calc(100% - 2rem));
        }

        /* Modal Permission Modules */
        .modal .permission-module {
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .modal .module-header {
            background: #f8f9fa;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal .module-header h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .modal .module-permissions {
            padding: 1rem;
            background: white;
        }

        .modal .permission-checklist {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        @media (max-width: 576px) {
            .modal .permission-checklist {
                grid-template-columns: 1fr;
            }
        }

        .modal .perm-check {
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .modal .perm-check:hover {
            background: var(--primary-light);
        }

        .modal .perm-check label {
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            width: 100%;
        }

        .modal .perm-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* Permission Checklist in Modal */
        .permission-checklist {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .perm-check {
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .perm-check label {
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Header Styling */
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

            .profile-badge span {
                display: none;
            }

            .profile-badge {
                padding: 0.5rem;
                border-radius: 50%;
            }

            .roles-grid {
                grid-template-columns: 1fr;
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .permission-checklist {
                grid-template-columns: 1fr;
            }

            .modal .permission-checklist {
                grid-template-columns: 1fr;
            }

            .modal .module-header h4 {
                font-size: 0.95rem;
                line-height: 1.25;
                overflow-wrap: anywhere;
            }

            .modal .perm-check label {
                align-items: flex-start;
            }

            .modal .modal-dialog {
                margin: 0.6rem;
                max-width: calc(100% - 1.2rem);
            }

            .modal .modal-content {
                border-radius: 14px;
            }

            .modal .modal-header {
                padding: 1rem;
                border-radius: 14px 14px 0 0;
            }

            .modal .modal-title {
                font-size: 1rem;
                line-height: 1.3;
                display: flex;
                align-items: flex-start;
                gap: 0.4rem;
                flex-wrap: wrap;
            }

            .modal .modal-body {
                padding: 1rem;
                max-height: calc(100vh - 10.5rem);
            }

            .modal .modal-footer {
                padding: 0.9rem 1rem;
                flex-wrap: wrap;
                gap: 0.6rem;
            }

            .modal .modal-footer .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Form Styling */
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .modal .col-6 {
                width: 100%;
            }

            .modal .input-group {
                flex-wrap: nowrap;
            }

            .modal .input-group .btn {
                width: auto;
            }
        }

        /* Permission Denied */
        .permission-denied {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
        }

        .permission-denied i {
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .permission-denied h3 {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .permission-denied p {
            color: #6c757d;
            max-width: 400px;
            margin: 0 auto;
        }


        /* ==========================================================
           Clean Backup-style redesign for Users & Roles page
           Keeps existing sidebar, header, routes, Blade variables, and forms.
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
            margin: 0;
            background: var(--page-bg);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        .min-w-0 {
            min-width: 0 !important;
        }

        .users-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .users-alert,
        main > .alert,
        .users-page > .alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .users-alert.fade:not(.show),
        main > .alert.fade:not(.show),
        .users-page > .alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .users-hero,
        .users-panel,
        .stat-card,
        .role-card,
        .permission-module,
        .card.border-0,
        .permission-denied {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .users-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            padding: clamp(1.15rem, 2vw, 1.65rem);
        }

        .users-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(135deg, rgba(211, 47, 47, .04), transparent 42%);
            pointer-events: none;
        }

        .users-hero-content {
            position: relative;
            z-index: 1;
            max-width: 920px;
        }

        .users-hero-icon,
        .users-panel-icon,
        .stat-icon,
        .user-avatar,
        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .users-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .8rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .14);
            border-radius: 14px;
            font-size: 1.15rem;
        }

        .users-kicker,
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

        .users-hero h2 {
            margin: .55rem 0 .55rem;
            color: var(--text);
            font-size: clamp(1.75rem, 3vw, 2.55rem);
            font-weight: 850;
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .users-hero p,
        .panel-copy,
        .empty-state p,
        .permission-denied p {
            color: var(--muted);
            line-height: 1.65;
        }

        .users-hero p {
            max-width: 760px;
            margin: 0;
            font-size: .96rem;
        }

        .users-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: 1.15rem;
        }

        .users-stats-row {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .users-stats-row > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .stat-card {
            height: 100%;
            padding: 1rem !important;
            gap: .85rem;
            overflow: visible;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .stat-card::before {
            display: none !important;
        }

        .stat-card:hover,
        .role-card:hover,
        .permission-module:hover,
        .users-panel:hover,
        .card.border-0:hover {
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .stat-card:hover,
        .role-card:hover {
            transform: translateY(-1px) !important;
        }

        .stat-card > div:first-child {
            min-width: 0;
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

        .tab-navigation {
            display: inline-flex !important;
            align-items: center;
            gap: .35rem;
            margin-bottom: 1rem;
            padding: .35rem;
            max-width: 100%;
            overflow-x: auto;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 999px;
            box-shadow: var(--shadow-xs);
            scrollbar-width: thin;
        }

        .tab-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            min-height: 38px;
            padding: .55rem .85rem;
            color: var(--muted);
            background: transparent;
            border: 0;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 820;
            white-space: nowrap;
            transition: background .16s ease, color .16s ease, box-shadow .16s ease;
            cursor: pointer;
        }

        .tab-link i {
            margin-right: 0 !important;
        }

        .tab-link:hover {
            color: var(--primary-dark);
            background: var(--primary-soft);
        }

        .tab-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .users-panel {
            overflow: hidden;
        }

        .users-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .users-panel-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .users-panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .users-panel-header h3 {
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

        .users-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .users-filter-body {
            padding: 1rem;
        }

        .users-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(260px, 1.6fr) minmax(175px, .75fr) minmax(170px, .75fr) auto auto;
            gap: .65rem;
            align-items: center;
        }

        .users-search-field {
            position: relative;
            min-width: 0;
        }

        .users-search-field i {
            position: absolute;
            top: 50%;
            left: .9rem;
            transform: translateY(-50%);
            color: var(--muted-2);
            pointer-events: none;
        }

        .users-search-field .form-control {
            padding-left: 2.4rem !important;
        }

        .btn,
        .btn-small,
        .btn-create-role {
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

        .btn:hover,
        .btn-small:hover,
        .btn-create-role:hover {
            transform: translateY(-1px);
        }

        .btn:focus-visible,
        .btn-small:focus-visible,
        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none !important;
        }

        .btn-primary,
        .btn-danger,
        .btn-create-role {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-create-role:hover,
        .btn-create-role:focus {
            color: #ffffff !important;
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: 0 12px 26px rgba(211, 47, 47, .22);
        }

        .btn-light-soft,
        .btn-secondary,
        .btn-outline-secondary,
        .btn-small {
            color: var(--text) !important;
            background: var(--card) !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .btn-secondary:hover,
        .btn-outline-secondary:hover,
        .btn-small:hover {
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

        .btn-outline-success,
        .btn-success {
            color: var(--success) !important;
            background: var(--card) !important;
            border-color: rgba(22, 128, 60, .25) !important;
        }

        .btn-outline-success:hover,
        .btn-success:hover {
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

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
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

        .table tbody tr {
            transition: background .16s ease;
        }

        .table tbody tr:hover {
            background: #fcfcfd;
        }

        .user-avatar {
            flex: 0 0 auto;
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid rgba(211, 47, 47, .12);
            font-weight: 850;
            font-size: .88rem;
        }

        .badge,
        .role-badge {
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

        .role-badge,
        .role-badge.staff,
        .role-badge.barangay_secretary,
        .role-badge.custom,
        .role-badge.super_admin,
        .role-badge.barangay_captain {
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
        }

        .role-card {
            overflow: hidden;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .role-header,
        .role-header.super_admin,
        .role-header.barangay_captain,
        .role-header.staff,
        .role-header.barangay_secretary,
        .role-header.custom {
            padding: 1rem;
            color: var(--text);
            background: linear-gradient(180deg, #ffffff, #fbfdff) !important;
            border-bottom: 1px solid var(--line);
        }

        .role-header h3 {
            color: var(--text);
            font-size: 1rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .role-header h3 i {
            color: var(--primary);
        }

        .role-count {
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-weight: 820;
        }

        .role-body {
            padding: 1rem;
        }

        .permission-item {
            color: var(--muted);
            border-bottom: 1px dashed var(--line-strong);
            line-height: 1.45;
        }

        .permissions-grid,
        .roles-grid {
            gap: 1rem;
        }

        .permission-module {
            overflow: hidden;
        }

        .module-header,
        .modal .module-header {
            padding: .9rem 1rem;
            background: #f9fafb !important;
            border-bottom: 1px solid var(--line) !important;
        }

        .module-header h4,
        .modal .module-header h4 {
            color: var(--text) !important;
            font-size: .95rem;
            font-weight: 850;
            letter-spacing: -.02em;
        }

        .module-header h4 i,
        .modal .module-header h4 i {
            color: var(--primary) !important;
        }

        .module-permissions,
        .modal .module-permissions {
            padding: 1rem;
        }

        .perm-item,
        .modal .perm-check,
        .perm-check {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: .65rem;
            transition: background .16s ease, border-color .16s ease;
        }

        .perm-item:hover,
        .modal .perm-check:hover,
        .perm-check:hover {
            background: #fffafa;
            border-color: rgba(211, 47, 47, .18);
        }

        .perm-item input[type="checkbox"],
        .perm-check input[type="checkbox"],
        .modal .perm-check input[type="checkbox"] {
            accent-color: var(--primary);
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

        .modal-body {
            padding: 1rem !important;
            max-height: 72vh;
            overflow-y: auto;
        }

        .modal-footer {
            gap: .55rem;
            padding: 0 1rem 1rem !important;
            border-top: 0 !important;
        }

        .modal .permission-module {
            border-radius: var(--radius-lg) !important;
            box-shadow: none !important;
        }

        .modal .permission-checklist,
        .permission-checklist {
            gap: .65rem;
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
            .users-stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .users-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) minmax(180px, .7fr) minmax(180px, .7fr);
            }

            .users-filter-toolbar .btn {
                width: 100%;
            }
        }

        @media (max-width: 991px) {
            .users-panel-header {
                flex-direction: column;
            }

            .users-panel-actions,
            .users-panel-actions .btn {
                width: 100%;
            }

            .users-filter-toolbar {
                grid-template-columns: minmax(0, 1fr);
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

            .users-page {
                max-width: 100%;
            }

            .users-hero,
            .users-panel,
            .stat-card,
            .role-card,
            .permission-module,
            .card.border-0 {
                border-radius: 16px !important;
            }

            .users-hero,
            .users-panel-header,
            .users-filter-body,
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: .9rem !important;
            }

            .users-hero-actions,
            .users-hero-actions .btn {
                width: 100%;
            }

            .users-stats-row {
                grid-template-columns: minmax(0, 1fr);
            }

            .tab-navigation {
                width: 100%;
                border-radius: 16px;
            }

            .tab-link {
                flex: 1 0 auto;
            }
        }

        @media (max-width: 575px) {
            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .users-hero h2 {
                font-size: 1.75rem;
            }
        }

    
        /* ==========================================================
           UX pass v2: cleaner layout + modal experience
           - keeps existing routes, IDs, forms, permissions, sidebar, header
        ========================================================== */
        .users-page {
            max-width: 1440px;
        }

        .users-hero {
            margin-bottom: .9rem;
            padding: 1rem 1.1rem !important;
        }

        .users-hero-content {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            grid-template-areas:
                "icon kicker actions"
                "icon title actions"
                "icon copy actions";
            gap: .25rem .9rem;
            align-items: center;
            max-width: none;
        }

        .users-hero-icon {
            grid-area: icon;
            width: 48px;
            height: 48px;
            margin: 0;
            border-radius: 16px;
        }

        .users-kicker {
            grid-area: kicker;
        }

        .users-hero h2 {
            grid-area: title;
            margin: .08rem 0;
            font-size: clamp(1.35rem, 2vw, 2rem);
            letter-spacing: -.045em;
        }

        .users-hero p {
            grid-area: copy;
            max-width: 780px;
            font-size: .88rem;
            line-height: 1.55;
        }

        .users-hero-actions {
            grid-area: actions;
            justify-content: flex-end;
            align-items: center;
            margin: 0;
            min-width: max-content;
        }

        .users-stats-row {
            margin-bottom: 1rem !important;
        }

        .stat-card {
            align-items: center !important;
        }

        .stat-card small {
            max-width: 220px;
        }

        .tab-navigation {
            margin-bottom: .85rem;
        }

        .tab-link {
            min-width: 108px;
        }

        .users-panel {
            margin-bottom: 1rem;
        }

        .users-panel-header {
            align-items: center;
            padding: .95rem 1rem;
        }

        .users-panel-title {
            align-items: center;
        }

        .users-panel-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
        }

        .users-panel-header h3 {
            margin-top: .1rem;
            font-size: 1rem;
        }

        .users-panel-actions .btn {
            min-height: 40px;
        }

        .users-filter-body {
            background: #fbfcfd;
            border-top: 1px solid var(--line);
        }

        .users-filter-toolbar {
            grid-template-columns: minmax(330px, 2fr) minmax(165px, .72fr) minmax(160px, .7fr) auto auto;
            gap: .6rem;
        }

        .users-search-field .form-control,
        .users-filter-toolbar .form-select,
        .users-filter-toolbar .btn {
            min-height: 42px;
        }

        .users-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background: #ffffff;
            border-bottom: 1px solid var(--line);
        }

        .users-table-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 0;
        }

        .users-table-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .users-table-title h3 {
            margin: 0;
            color: var(--text);
            font-size: 1rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .users-table-title p {
            margin: .15rem 0 0;
            color: var(--muted);
            font-size: .82rem;
        }

        .users-count-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            width: fit-content;
            white-space: nowrap;
            padding: .42rem .68rem;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 820;
        }

        .table {
            min-width: 1040px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table tbody td {
            white-space: nowrap;
        }

        .table tbody td:nth-child(2),
        .table tbody td:nth-child(3) {
            white-space: normal;
        }

        .table tbody td:nth-child(3) {
            max-width: 260px;
            overflow-wrap: anywhere;
        }

        .table .d-flex.gap-1,
        .table .d-flex.gap-sm-2 {
            flex-wrap: nowrap;
        }

        .roles-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }

        .role-card {
            min-height: 100%;
        }

        .role-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .permission-list {
            display: grid;
            gap: .45rem;
        }

        .permission-item {
            padding: .55rem .65rem;
            background: #fbfcfd;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .permissions-grid {
            grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        }

        .module-permissions {
            display: grid;
            gap: .55rem;
        }

        .perm-item {
            align-items: flex-start;
        }

        .perm-item label,
        .perm-check label {
            line-height: 1.4;
        }

        .modal-backdrop.show {
            opacity: .38;
        }

        .modal-dialog {
            margin-top: 1.1rem;
            margin-bottom: 1.1rem;
        }

        .modal .modal-dialog.modal-lg,
        .modal-dialog-scrollable {
            max-width: min(920px, calc(100% - 2rem)) !important;
        }

        #addUserModal .modal-dialog,
        [id^="editUserModal"] .modal-dialog,
        #addRoleModal .modal-dialog,
        [id^="editRoleModal"] .modal-dialog,
        [id^="userPermissionsModal"] .modal-dialog {
            max-width: min(980px, calc(100% - 2rem)) !important;
        }

        #viewUserModal .modal-dialog,
        [id^="viewUserModal"] .modal-dialog,
        #resetPasswordModal .modal-dialog {
            max-width: min(620px, calc(100% - 2rem)) !important;
        }

        .modal-content {
            background: #ffffff;
            border: 1px solid rgba(208, 213, 221, .8) !important;
        }

        .modal-header {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) auto;
            grid-template-areas:
                "title close"
                "subtitle close";
            align-items: start !important;
            column-gap: .9rem;
            row-gap: .25rem;
            padding: 1.05rem 1.15rem !important;
        }

        .modal-title {
            grid-area: title;
            font-size: 1.05rem !important;
            line-height: 1.25;
        }

        .modal-title i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            margin-right: .45rem !important;
            color: var(--primary) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 12px;
        }

        .modal-header .btn-close {
            grid-area: close;
            margin: .15rem 0 0 !important;
            width: 36px;
            height: 36px;
            padding: .55rem;
            background-color: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .ux-modal-subtitle {
            grid-area: subtitle;
            margin: 0 0 0 3.28rem;
            max-width: 760px;
            color: var(--muted);
            font-size: .82rem;
            line-height: 1.45;
        }

        .modal-body {
            padding: 1.05rem 1.15rem !important;
            background:
                linear-gradient(180deg, #fbfcfd 0%, #ffffff 42%);
        }

        .modal-body form > .row.g-3,
        .modal-body > .row.g-3 {
            margin-left: 0;
            margin-right: 0;
        }

        .modal-body form > .row.g-3 > [class*="col-"],
        .modal-body > .row.g-3 > [class*="col-"] {
            padding-left: .4rem;
            padding-right: .4rem;
        }

        .modal h6.fw-semibold,
        .modal h6 {
            display: flex;
            align-items: center;
            gap: .45rem;
            width: 100%;
            margin: .1rem 0 .2rem;
            padding: .72rem .82rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
            font-size: .8rem;
            font-weight: 850 !important;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .modal h6::before {
            content: '';
            width: 8px;
            height: 8px;
            flex: 0 0 auto;
            background: var(--primary);
            border-radius: 999px;
        }

        .modal .form-label {
            margin-bottom: .38rem;
        }

        .modal .form-control,
        .modal .form-select {
            min-height: 44px;
            background: #ffffff;
        }

        .modal small.text-muted {
            display: block;
            margin-top: .35rem;
            color: var(--muted-2) !important;
            font-size: .76rem;
            line-height: 1.4;
        }

        .modal .input-group {
            border-radius: 12px;
        }

        .modal .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .modal .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .modal .alert {
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: none;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            z-index: 2;
            background: #ffffff;
            border-top: 1px solid var(--line) !important;
            padding: .85rem 1.15rem !important;
        }

        .modal-footer .btn {
            min-width: 116px;
        }

        .modal .text-center.mb-4 {
            padding: 1rem;
            background: #fbfcfd;
            border: 1px solid var(--line);
            border-radius: 18px;
        }

        .modal .user-avatar.mx-auto {
            border-radius: 22px !important;
            box-shadow: var(--shadow-xs);
        }

        .ux-permission-search {
            position: sticky;
            top: -1.05rem;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: .6rem;
            margin: -1.05rem -1.15rem 1rem;
            padding: .85rem 1.15rem;
            background: #ffffff;
            border-bottom: 1px solid var(--line);
        }

        .ux-permission-search .users-search-field {
            flex: 1 1 auto;
        }

        .ux-permission-search .users-count-pill {
            flex: 0 0 auto;
        }

        .modal .permission-checklist,
        .permission-checklist {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .modal .permission-module {
            margin-bottom: .85rem;
        }

        .modal .module-permissions {
            background: #ffffff;
        }

        .modal .perm-check,
        .modal .perm-item {
            min-height: 46px;
            display: flex;
            align-items: center;
        }

        .modal .perm-check.is-hidden,
        .modal .perm-item.is-hidden,
        .modal .permission-module.is-empty {
            display: none !important;
        }

        #auditLogDetailsModal pre {
            background: #0f172a !important;
            color: #e5e7eb;
            border: 0 !important;
            border-radius: 16px !important;
            font-size: .8rem;
        }

        @media (max-width: 1199px) {
            .users-hero-content {
                grid-template-columns: auto minmax(0, 1fr);
                grid-template-areas:
                    "icon kicker"
                    "icon title"
                    "icon copy"
                    "actions actions";
            }

            .users-hero-actions {
                justify-content: flex-start;
                margin-top: .7rem;
                min-width: 0;
            }

            .users-filter-toolbar {
                grid-template-columns: minmax(0, 1fr) minmax(180px, .6fr) minmax(180px, .6fr);
            }

            .users-filter-toolbar .search-submit-btn,
            .users-filter-toolbar .filter-submit-btn {
                grid-column: auto;
            }
        }

        @media (max-width: 991px) {
            .users-table-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .users-count-pill {
                align-self: flex-start;
            }

            .modal .permission-checklist,
            .permission-checklist {
                grid-template-columns: minmax(0, 1fr);
            }

            .ux-permission-search {
                align-items: stretch;
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .users-hero-content {
                grid-template-columns: minmax(0, 1fr);
                grid-template-areas:
                    "icon"
                    "kicker"
                    "title"
                    "copy"
                    "actions";
            }

            .users-hero-icon {
                width: 46px;
                height: 46px;
            }

            .users-hero-actions {
                width: 100%;
            }

            .users-hero-actions .btn {
                width: 100%;
            }

            .users-panel-header {
                align-items: stretch;
            }

            .users-filter-toolbar {
                grid-template-columns: minmax(0, 1fr);
            }

            .users-filter-toolbar .btn {
                width: 100%;
            }

            .users-table-title {
                align-items: flex-start;
            }

            .modal .modal-dialog,
            .modal .modal-dialog.modal-lg,
            .modal-dialog-scrollable,
            #addUserModal .modal-dialog,
            [id^="editUserModal"] .modal-dialog,
            #addRoleModal .modal-dialog,
            [id^="editRoleModal"] .modal-dialog,
            [id^="userPermissionsModal"] .modal-dialog,
            #viewUserModal .modal-dialog,
            [id^="viewUserModal"] .modal-dialog,
            #resetPasswordModal .modal-dialog {
                max-width: calc(100% - 1rem) !important;
                margin: .5rem auto;
            }

            .modal-header {
                padding: .9rem !important;
            }

            .ux-modal-subtitle {
                margin-left: 0;
            }

            .modal-title i {
                width: 32px;
                height: 32px;
            }

            .modal-footer {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

    
        /* ==========================================================
           FINAL MOBILE RESPONSIVE FIX - Users & Roles
           Added safely at the end so desktop stays unchanged.
           Targets: users table, audit logs, role member tables, tabs, filters, modals.
        ========================================================== */
        @media (max-width: 768px) {
            body {
                background: #f8fafc;
            }

            .main-content {
                min-width: 0 !important;
            }

            .header {
                position: sticky;
                top: 0;
                z-index: 900;
                min-height: 64px;
                padding: .75rem .9rem !important;
                background: rgba(255, 255, 255, .96);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--line, #e5e7eb);
            }

            .header > .d-flex,
            .header .page-title {
                min-width: 0;
            }

            .page-title {
                max-width: calc(100vw - 94px);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 1rem !important;
                line-height: 1.2;
            }

            .title-icon,
            .mobile-menu-btn {
                flex: 0 0 auto;
                width: 38px !important;
                height: 38px !important;
                border-radius: 12px !important;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .users-page {
                width: 100%;
                max-width: 100%;
            }

            .users-hero {
                padding: 1rem !important;
                border-radius: 18px !important;
            }

            .users-hero-content {
                display: grid !important;
                grid-template-columns: 44px minmax(0, 1fr) !important;
                grid-template-areas:
                    "icon kicker"
                    "icon title"
                    "copy copy"
                    "actions actions" !important;
                gap: .35rem .75rem !important;
                align-items: center !important;
            }

            .users-hero-icon {
                grid-area: icon;
                width: 44px !important;
                height: 44px !important;
                margin: 0 !important;
                border-radius: 14px !important;
            }

            .users-kicker {
                grid-area: kicker;
                min-width: 0;
                font-size: .66rem !important;
                letter-spacing: .07em !important;
                white-space: normal;
            }

            .users-hero h2 {
                grid-area: title;
                margin: 0 !important;
                font-size: 1.42rem !important;
                line-height: 1.1 !important;
                letter-spacing: -.04em !important;
            }

            .users-hero p {
                grid-area: copy;
                max-width: none !important;
                margin-top: .45rem !important;
                font-size: .86rem !important;
                line-height: 1.55 !important;
            }

            .users-hero-actions {
                grid-area: actions;
                display: grid !important;
                grid-template-columns: 1fr;
                width: 100%;
                gap: .55rem !important;
                margin-top: .55rem !important;
            }

            .users-hero-actions .btn,
            .users-panel-actions .btn,
            .users-filter-toolbar .btn {
                width: 100%;
                justify-content: center;
            }

            .users-stats-row {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .65rem !important;
                margin-bottom: .85rem !important;
            }

            .users-stats-row > [class*="col-"] {
                width: auto !important;
                max-width: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .stat-card {
                padding: .85rem !important;
                align-items: flex-start !important;
                gap: .65rem !important;
                border-radius: 16px !important;
            }

            .stat-card .stat-icon {
                width: 38px !important;
                height: 38px !important;
                border-radius: 12px !important;
                font-size: .95rem !important;
            }

            .stat-label {
                font-size: .66rem !important;
                line-height: 1.2 !important;
            }

            .stat-number {
                font-size: 1.28rem !important;
            }

            .stat-card small {
                display: none !important;
            }

            .tab-navigation {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                width: 100%;
                padding: .4rem !important;
                gap: .35rem !important;
                border-radius: 18px !important;
                overflow: visible !important;
            }

            .tab-link {
                min-width: 0 !important;
                width: 100%;
                min-height: 40px !important;
                padding: .58rem .55rem !important;
                font-size: .78rem !important;
                border-radius: 14px !important;
            }

            .tab-link i {
                margin-right: .25rem !important;
            }

            .users-panel,
            .card.border-0,
            .role-card,
            .permission-module {
                border-radius: 18px !important;
                overflow: hidden;
            }

            .users-panel-header,
            .users-table-header {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                gap: .8rem !important;
                padding: .9rem !important;
            }

            .users-panel-title,
            .users-table-title {
                align-items: flex-start !important;
                min-width: 0;
            }

            .users-panel-icon,
            .users-table-icon {
                width: 40px !important;
                height: 40px !important;
                border-radius: 13px !important;
            }

            .users-panel-header h3,
            .users-table-title h3 {
                font-size: .98rem !important;
            }

            .panel-copy,
            .users-table-title p {
                font-size: .8rem !important;
                line-height: 1.45 !important;
            }

            .users-panel-actions {
                display: grid !important;
                grid-template-columns: 1fr;
                width: 100%;
                gap: .55rem !important;
            }

            .users-filter-body {
                padding: .85rem !important;
            }

            .users-filter-toolbar {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: .65rem !important;
            }

            .users-search-field .form-control,
            .users-filter-toolbar .form-select,
            .users-filter-toolbar .btn,
            #auditTab .form-control,
            #auditTab .form-select,
            #auditTab .btn {
                min-height: 44px !important;
                border-radius: 12px !important;
            }

            .users-count-pill {
                align-self: flex-start;
                white-space: normal !important;
                line-height: 1.35;
            }

            /* Users table becomes clean cards */
            .users-table-panel .table-responsive,
            #auditTab .table-responsive,
            [id^="viewRoleMembersModal"] .table-responsive {
                overflow: visible !important;
                border-radius: 0 !important;
            }

            .users-table-panel table,
            #auditTab table,
            [id^="viewRoleMembersModal"] table {
                display: block !important;
                width: 100% !important;
                min-width: 0 !important;
            }

            .users-table-panel thead,
            #auditTab thead,
            [id^="viewRoleMembersModal"] thead {
                display: none !important;
            }

            .users-table-panel tbody,
            #auditTab tbody,
            [id^="viewRoleMembersModal"] tbody {
                display: grid !important;
                gap: .75rem;
                padding: .85rem;
                background: #f8fafc;
            }

            .users-table-panel tbody tr,
            #auditTab tbody tr,
            [id^="viewRoleMembersModal"] tbody tr {
                display: grid !important;
                width: 100% !important;
                gap: 0;
                padding: .85rem;
                background: #ffffff;
                border: 1px solid var(--line, #e5e7eb);
                border-radius: 18px;
                box-shadow: var(--shadow-xs, 0 1px 2px rgba(16,24,40,.05));
            }

            .users-table-panel tbody tr:hover,
            #auditTab tbody tr:hover,
            [id^="viewRoleMembersModal"] tbody tr:hover {
                background: #ffffff !important;
            }

            .users-table-panel tbody td,
            #auditTab tbody td,
            [id^="viewRoleMembersModal"] tbody td {
                display: flex !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                gap: .9rem;
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                padding: .58rem 0 !important;
                border: 0 !important;
                border-bottom: 1px dashed var(--line, #e5e7eb) !important;
                color: var(--text, #111827) !important;
                white-space: normal !important;
                text-align: right !important;
                overflow-wrap: anywhere;
            }

            .users-table-panel tbody td:last-child,
            #auditTab tbody td:last-child,
            [id^="viewRoleMembersModal"] tbody td:last-child {
                border-bottom: 0 !important;
                padding-bottom: 0 !important;
            }

            .users-table-panel tbody td::before,
            #auditTab tbody td::before,
            [id^="viewRoleMembersModal"] tbody td::before {
                flex: 0 0 95px;
                color: var(--muted, #667085);
                font-size: .72rem;
                font-weight: 850;
                text-align: left;
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            .users-table-panel tbody td:nth-child(1)::before { content: "Select"; }
            .users-table-panel tbody td:nth-child(2)::before { content: "User"; }
            .users-table-panel tbody td:nth-child(3)::before { content: "Email"; }
            .users-table-panel tbody td:nth-child(4)::before { content: "Role"; }
            .users-table-panel tbody td:nth-child(5)::before { content: "Department"; }
            .users-table-panel tbody td:nth-child(6)::before { content: "Joined"; }
            .users-table-panel tbody td:nth-child(7)::before { content: "Status"; }
            .users-table-panel tbody td:nth-child(8)::before { content: "Actions"; }

            #auditTab tbody td:nth-child(1)::before { content: "When"; }
            #auditTab tbody td:nth-child(2)::before { content: "User"; }
            #auditTab tbody td:nth-child(3)::before { content: "Action"; }
            #auditTab tbody td:nth-child(4)::before { content: "Module"; }
            #auditTab tbody td:nth-child(5)::before { content: "Details"; }
            #auditTab tbody td:nth-child(6)::before { content: "IP"; }

            [id^="viewRoleMembersModal"] tbody td:nth-child(1)::before { content: "User"; }
            [id^="viewRoleMembersModal"] tbody td:nth-child(2)::before { content: "Email"; }
            [id^="viewRoleMembersModal"] tbody td:nth-child(3)::before { content: "Department"; }
            [id^="viewRoleMembersModal"] tbody td:nth-child(4)::before { content: "Status"; }
            [id^="viewRoleMembersModal"] tbody td:nth-child(5)::before { content: "Actions"; }

            .users-table-panel tbody td:nth-child(2) {
                align-items: center !important;
                justify-content: flex-start !important;
                text-align: left !important;
                gap: .75rem;
                padding-top: .1rem !important;
                padding-bottom: .75rem !important;
            }

            .users-table-panel tbody td:nth-child(2)::before {
                display: none;
            }

            .users-table-panel .user-avatar {
                width: 44px !important;
                height: 44px !important;
                border-radius: 15px !important;
            }

            .users-table-panel tbody td:nth-child(3),
            #auditTab tbody td:nth-child(2) {
                font-weight: 700;
            }

            .users-table-panel tbody td .d-flex.gap-1,
            .users-table-panel tbody td .d-flex.gap-sm-2,
            #auditTab tbody td .d-flex,
            [id^="viewRoleMembersModal"] tbody td .d-flex {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                width: 100%;
                gap: .45rem !important;
                justify-content: stretch !important;
            }

            .users-table-panel tbody td:last-child {
                display: block !important;
                text-align: left !important;
            }

            .users-table-panel tbody td:last-child::before {
                display: block;
                margin-bottom: .45rem;
                flex: initial;
            }

            .users-table-panel tbody td:last-child .btn,
            .users-table-panel tbody td:last-child form,
            [id^="viewRoleMembersModal"] tbody td:last-child .btn,
            [id^="viewRoleMembersModal"] tbody td:last-child form {
                width: 100% !important;
            }

            .users-table-panel tbody td:last-child .btn[title]::after {
                content: attr(title);
                margin-left: .35rem;
                font-size: .78rem;
                font-weight: 800;
            }

            .users-table-panel tbody td:last-child form[style] {
                display: block !important;
            }

            .badge,
            .role-badge {
                max-width: 100%;
                white-space: normal !important;
                text-align: center;
                line-height: 1.25;
            }

            .role-card {
                border-radius: 18px !important;
            }

            .roles-grid,
            .permissions-grid {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: .75rem !important;
                margin-top: .85rem !important;
            }

            .role-header {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: .75rem;
                align-items: start;
                padding: .9rem !important;
            }

            .role-header h3 {
                min-width: 0;
                font-size: .96rem !important;
                line-height: 1.3;
            }

            .role-count {
                position: static !important;
                white-space: nowrap;
            }

            .role-body {
                padding: .9rem !important;
            }

            .permission-list {
                gap: .4rem !important;
            }

            .permission-item {
                display: flex;
                align-items: flex-start;
                gap: .45rem;
                padding: .58rem .65rem !important;
                line-height: 1.4;
                font-size: .82rem;
            }

            .role-actions {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .5rem !important;
            }

            .role-actions .btn-small {
                width: 100%;
            }

            #rolesTab > .d-flex,
            #permissionsTab > .d-flex {
                align-items: stretch !important;
                flex-direction: column !important;
                gap: .65rem !important;
                margin-bottom: .85rem !important;
            }

            #rolesTab > .d-flex .btn,
            #permissionsTab > .d-flex .btn,
            .permissions-footer .btn,
            #permissionsForm > .mt-4 .btn {
                width: 100%;
            }

            .permission-module {
                border-radius: 18px !important;
            }

            .module-header,
            .modal .module-header {
                padding: .75rem .85rem !important;
            }

            .module-permissions,
            .modal .module-permissions {
                display: grid;
                gap: .45rem;
                padding: .8rem !important;
            }

            .perm-item,
            .perm-check,
            .modal .perm-check {
                display: flex !important;
                align-items: flex-start !important;
                gap: .55rem;
                padding: .65rem !important;
                min-height: 44px;
            }

            .perm-item input[type="checkbox"],
            .perm-check input[type="checkbox"],
            .modal .perm-check input[type="checkbox"],
            input[type="checkbox"] {
                flex: 0 0 auto;
                width: 20px !important;
                height: 20px !important;
                margin-top: .05rem;
            }

            .perm-item label,
            .perm-check label,
            .modal .perm-check label {
                min-width: 0;
                line-height: 1.35;
            }

            #auditTab .card-body {
                padding: .85rem !important;
            }

            #auditTab .card-body.p-0 {
                padding: 0 !important;
            }

            #auditTab .row.g-3 > [class*="col-"] {
                width: 100%;
            }

            #auditTab .d-flex.justify-content-end {
                justify-content: stretch !important;
            }

            #auditTab .d-flex.justify-content-end .btn {
                width: 100%;
            }

            #auditTab tbody td:nth-child(5) .btn {
                width: auto;
                min-width: 128px;
            }

            .pagination {
                justify-content: center;
                gap: .25rem;
            }

            .page-link {
                min-height: 38px;
                min-width: 38px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: .45rem .6rem !important;
            }

            /* Modals */
            .modal-dialog,
            .modal .modal-dialog,
            .modal .modal-dialog.modal-lg,
            .modal-dialog-scrollable,
            #addUserModal .modal-dialog,
            [id^="editUserModal"] .modal-dialog,
            #addRoleModal .modal-dialog,
            [id^="editRoleModal"] .modal-dialog,
            [id^="userPermissionsModal"] .modal-dialog,
            [id^="viewRoleMembersModal"] .modal-dialog,
            #viewUserModal .modal-dialog,
            [id^="viewUserModal"] .modal-dialog,
            #resetPasswordModal .modal-dialog {
                width: auto !important;
                max-width: calc(100vw - 1rem) !important;
                margin: .5rem auto !important;
            }

            .modal-content {
                max-height: calc(100vh - 1rem);
                border-radius: 18px !important;
                overflow: hidden;
            }

            .modal-header {
                position: sticky;
                top: 0;
                z-index: 5;
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) 38px;
                gap: .65rem;
                padding: .85rem !important;
                background: #ffffff !important;
            }

            .modal-title {
                min-width: 0;
                font-size: .98rem !important;
                line-height: 1.25 !important;
            }

            .modal-title i {
                width: 30px !important;
                height: 30px !important;
                margin-right: .3rem !important;
            }

            .modal-header .btn-close {
                width: 36px !important;
                height: 36px !important;
                margin: 0 !important;
                border-radius: 12px !important;
            }

            .modal-body {
                max-height: calc(100vh - 136px) !important;
                padding: .85rem !important;
                overflow-y: auto !important;
            }

            .modal-body form > .row.g-3 > [class*="col-"],
            .modal-body > .row.g-3 > [class*="col-"],
            .modal .col-6,
            .modal .col-md-6,
            .modal .col-md-4,
            .modal .col-md-3,
            .modal .col-md-2 {
                width: 100% !important;
            }

            .modal-footer {
                position: sticky;
                bottom: 0;
                z-index: 5;
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                gap: .55rem !important;
                padding: .75rem .85rem !important;
                background: #ffffff !important;
                border-top: 1px solid var(--line, #e5e7eb) !important;
            }

            .modal-footer .btn {
                width: 100% !important;
                min-height: 44px;
            }

            .ux-permission-search {
                position: sticky;
                top: -.85rem;
                z-index: 4;
                margin: -.85rem -.85rem .85rem !important;
                padding: .75rem .85rem !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: .55rem !important;
            }

            #auditLogDetailsModal pre {
                max-width: 100%;
                overflow-x: auto !important;
                font-size: .76rem !important;
            }
        }

        @media (max-width: 420px) {
            .users-stats-row {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .tab-navigation {
                grid-template-columns: minmax(0, 1fr);
            }

            .users-table-panel tbody td,
            #auditTab tbody td,
            [id^="viewRoleMembersModal"] tbody td {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                text-align: left !important;
                gap: .25rem;
            }

            .users-table-panel tbody td::before,
            #auditTab tbody td::before,
            [id^="viewRoleMembersModal"] tbody td::before {
                flex: initial;
            }

            .users-table-panel tbody td .d-flex.gap-1,
            .users-table-panel tbody td .d-flex.gap-sm-2,
            [id^="viewRoleMembersModal"] tbody td .d-flex {
                grid-template-columns: minmax(0, 1fr);
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
        <a href="{{ route('admin.users.index') }}" class="active" onclick="handleLinkClick(event, this)">
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
                    <span class="title-icon"><i class="fas fa-users-cog"></i></span>
                    Users & Roles Management
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="users-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show users-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show users-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="users-hero">
                <div class="users-hero-content">
                    <div class="users-hero-icon"><i class="fas fa-users-cog"></i></div>
                    <div class="users-kicker"><i class="fas fa-circle-check"></i> Access Control Center</div>
                    <h2>Manage users, roles, and permissions clearly.</h2>
                    <p>Review administrator accounts, assign roles, configure permissions, and audit activity using the same clean card layout used in Backup Settings.</p>
                    <div class="users-hero-actions">
                        @admin_can('create_users')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus"></i>Add User
                        </button>
                        @endadmin_can
                        @admin_can('create_roles')
                        <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="fas fa-shield-alt"></i>Create Role
                        </button>
                        @endadmin_can
                    </div>
                </div>
            </section>

            <!-- Stats Cards -->
            <div class="row g-3 g-lg-4 mb-4 users-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total Users</div>
                            <div class="stat-number">{{ $total_users ?? 28 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-arrow-up me-1"></i>All time
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Active Users</div>
                            <div class="stat-number">{{ $active_users ?? 24 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-circle me-1"></i>Currently online
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Super Admins</div>
                            <div class="stat-number">{{ $super_admins ?? 1 }}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-crown me-1"></i>Full access
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total Roles</div>
                            <div class="stat-number">{{ $total_roles ?? 5 }}</div>
                            <small class="text-primary mt-2 d-none d-sm-block">
                                <i class="fas fa-shield-alt me-1"></i>Customizable
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-link active" onclick="switchTab('users', event)">
                    <i class="fas fa-users me-2"></i>Users
                </button>
                <button class="tab-link" onclick="switchTab('roles', event)">
                    <i class="fas fa-shield-alt me-2"></i>Roles
                </button>
                @admin_can('view_permissions')
                <button class="tab-link" onclick="switchTab('permissions', event)">
                    <i class="fas fa-lock me-2"></i>Permissions
                </button>
                @endadmin_can
                <button class="tab-link" onclick="switchTab('audit', event)">
                    <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                </button>
            </div>

            <!-- USERS TAB -->
            <div id="usersTab" class="tab-content active">

                <!-- Filters and Actions -->
                <div class="users-panel mb-4">
                    <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm">
                        <input type="hidden" name="tab" value="users">

                        <div class="users-panel-header">
                            <div class="users-panel-title">
                                <div class="users-panel-icon"><i class="fas fa-filter"></i></div>
                                <div class="min-w-0">
                                    <div class="section-kicker"><i class="fas fa-users"></i> User Directory</div>
                                    <h3>Filter Users</h3>
                                    <p class="panel-copy">Search by name or email, then narrow the list by role and account status.</p>
                                </div>
                            </div>

                            <div class="users-panel-actions">
                                @admin_can('create_users')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus"></i>Add User
                                </button>
                                @endadmin_can
                                <a href="{{ route('admin.users.index', ['tab' => 'users']) }}" class="btn btn-light-soft">
                                    <i class="fas fa-rotate"></i>Reset
                                </a>
                            </div>
                        </div>

                        <div class="users-filter-body">
                            <div class="users-filter-toolbar">
                                <div class="users-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search name, email, or username..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search users">
                                </div>

                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>

                                <select name="role" class="form-select" aria-label="Filter by role">
                                    <option value="">All roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->display_name ?? $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="status" class="form-select" aria-label="Filter by status">
                                    <option value="">All status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>

                                <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                    <i class="fas fa-filter"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="card border-0 users-table-panel">
                    <div class="card-body p-0">
                        <div class="users-table-header">
                            <div class="users-table-title">
                                <span class="users-table-icon"><i class="fas fa-users"></i></span>
                                <div class="min-w-0">
                                    <h3>User Accounts</h3>
                                    <p>Review account details, roles, status, and quick actions in one clean table.</p>
                                </div>
                            </div>
                            <span class="users-count-pill">
                                <i class="fas fa-list"></i>
                                @if(isset($users))
                                    {{ $users->total() }} users
                                @else
                                    0 users
                                @endif
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50" class="ps-4">
                                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                        </th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Joined Date</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users ?? [] as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <input type="checkbox" value="{{ $user->id }}" class="user-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ trim($user->first_name . ' ' . ($user->middle_initial ? $user->middle_initial . ' ' : '') . $user->last_name) }}</div>
                                                    <small class="text-muted">ID: {{ $user->user_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                                        </td>
                                        <td>{{ $user->department ?? 'N/A' }}</td>
                                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Jan 1, 2020' }}</td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @elseif($user->status == 'inactive')
                                                <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                                <!-- View (everyone with view_users can view) -->
                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Edit (requires update_users permission) -->
                                                @admin_can('update_users')
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endadmin_can

                                                <!-- Permissions (requires manage_user_permissions permission) -->
                                                @admin_can('manage_user_permissions')
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#userPermissionsModal{{ $user->id }}" title="Manage Permissions">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                                @endadmin_can

                                                <!-- Reset Password (requires reset_user_password permission) -->
                                                @admin_can('reset_user_password')
                                                <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" style="display: inline;" id="resetPasswordForm_{{ $user->id }}" onsubmit="return confirmResetPassword(event, this, '{{ $user->first_name }} {{ $user->last_name }}')">
                                                    @csrf
                                                    <input type="hidden" name="new_password" id="new_password_{{ $user->id }}" value="">
                                                    <button type="button" class="btn btn-sm btn-outline-warning" title="Reset Password" 
                                                            onclick="promptNewPassword('{{ $user->id }}', '{{ $user->first_name }} {{ $user->last_name }}')">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                                @endadmin_can

                                                <!-- Delete (only for non-super-admin and if user has delete_users permission) -->
                                                @if($user->role->name != 'super_admin' && auth('admin')->user()->hasPermission('delete_users'))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this user permanently?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <div>
                                                    <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                                                    <h4>No users found</h4>
                                                    <p>No user accounts match the current search and filters.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(isset($users) && $users->total() > 0)
                        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <small class="text-muted order-2 order-md-1">
                                <i class="fas fa-list me-1"></i>
                                {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                            </small>
                            <nav class="order-1 order-md-2">
                                {{ $users->appends(['tab' => 'users'])->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ROLES TAB -->
            <div id="rolesTab" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>Role Management
                    </h5>
                    @admin_can('create_roles')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>Create New Role
                    </button>
                    @endadmin_can
                </div>

                <div class="roles-grid">
                    @forelse($roles as $role)
                    <div class="role-card">
                        @php
                            $roleClass = match($role->name) {
                                'super_admin' => 'super_admin',
                                'barangay_captain' => 'barangay_captain',
                                'staff' => 'staff',
                                'barangay_secretary' => 'barangay_secretary',
                                default => 'custom'
                            };
                        @endphp
                        <div class="role-header {{ $roleClass }}">
                            <h3>
                                @if($role->name == 'super_admin')
                                    <i class="fas fa-crown"></i>
                                @elseif($role->name == 'barangay_captain')
                                    <i class="fas fa-user-tie"></i>
                                @elseif($role->name == 'staff')
                                    <i class="fas fa-user"></i>
                                @elseif($role->name == 'barangay_secretary')
                                    <i class="fas fa-file-alt"></i>
                                @else
                                    <i class="fas fa-shield-alt"></i>
                                @endif
                                {{ $role->display_name }}
                            </h3>
                            <span class="role-count">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</span>
                        </div>
                        <div class="role-body">
                            <div class="permission-list">
                                @forelse($role->permissions->take(5) as $permission)
                                <div class="permission-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>{{ $permission->display_name ?? $permission->name }}
                                </div>
                                @empty
                                    <div class="permission-item text-muted"><i class="fas fa-info-circle me-2"></i>No permissions configured</div>
                                @endforelse
                                
                                @if($role->permissions->count() > 5)
                                <div class="permission-item text-muted">
                                    <i class="fas fa-ellipsis-h me-2"></i>and {{ $role->permissions->count() - 5 }} more...
                                </div>
                                @endif
                            </div>
                            <div class="role-actions">
                                @admin_can('update_roles')
                                <button class="btn-small" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                @endadmin_can
                                @if(auth('admin')->user()->hasPermission('manage_role_permissions') || auth('admin')->user()->hasPermission('view_users'))
                                <button class="btn-small" data-bs-toggle="modal" data-bs-target="#viewRoleMembersModal{{ $role->id }}">
                                    <i class="fas fa-users me-1"></i>Members
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <div>
                                                    <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                                                    <h4>No users found</h4>
                                                    <p>No user accounts match the current search and filters.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                </div>
            </div>

            <!-- PERMISSIONS TAB -->
            @admin_can('view_permissions')
            <div id="permissionsTab" class="tab-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-semibold mb-1">
                            <i class="fas fa-lock me-2 text-primary"></i>Permission Management
                        </h5>
                        <p class="text-muted small">Configure granular permissions for each role</p>
                    </div>
                    <div class="d-flex gap-2">
                        @admin_can('reset_permission_defaults')
                        <form method="POST" action="{{ route('admin.permissions.reset-defaults') }}" id="resetPermissionsForm" style="display: inline;">
                            @csrf
                            @if($selectedRole)
                                <input type="hidden" name="role_id" value="{{ $selectedRole->id }}">
                            @endif
                            <button type="submit" class="btn btn-outline-secondary" onclick="return confirmReset()">
                                <i class="fas fa-undo me-2"></i>Reset Defaults
                            </button>
                        </form>
                        @endadmin_can
                    </div>
                </div>

                <!-- Role Selection Form -->
                <form method="GET" action="{{ route('admin.users.index') }}" id="selectRoleForm">
                    <input type="hidden" name="tab" value="permissions">
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Select Role to Configure</label>
                                    <select name="role_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Choose a role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $selectedRole && $selectedRole->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name }} ({{ $role->users_count ?? 0 }} users)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Select a role to view and edit its permissions
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if($selectedRole)
                    <!-- Current Role Indicator -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2 fs-5"></i>
                        <div>Currently configuring permissions for: <strong>{{ $selectedRole->display_name }}</strong></div>
                    </div>

                    <!-- Super Admin Warning -->
                    @if($selectedRole->name === 'super_admin')
                        <div class="alert alert-warning d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                            <div><strong>Super Admin</strong> permissions cannot be modified as they have full system access.</div>
                        </div>
                    @endif

                    <!-- Permissions Form -->
                    <form method="POST" action="{{ route('admin.roles.permissions.update', $selectedRole->id) }}" id="permissionsForm">
                        @csrf
                        <div class="permissions-grid">
                            @foreach($allPermissions as $module => $permissions)
                            <div class="permission-module" data-module="{{ $module }}">
                                <div class="module-header"> 
                                    <h4>
                                        @switch($module)
                                            @case('dashboard')
                                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                                @break
                                            @case('residents')
                                                <i class="fas fa-users me-2 text-primary"></i>
                                                @break
                                            @case('residency')
                                                <i class="fas fa-file-signature me-2 text-primary"></i>
                                                @break
                                            @case('indigency')
                                                <i class="fas fa-file-invoice me-2 text-primary"></i>
                                                @break
                                            @case('clearance')
                                                <i class="fas fa-file-contract me-2 text-primary"></i>
                                                @break
                                            @case('blotter')
                                                <i class="fas fa-book me-2 text-primary"></i>
                                                @break
                                            @case('witness')
                                                <i class="fas fa-gavel me-2 text-primary"></i>
                                                @break
                                            @case('announcements')
                                                <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                @break
                                            @case('events')
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                @break
                                            @case('projects')
                                                <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                @break
                                            @case('requests')
                                                <i class="fas fa-inbox me-2 text-primary"></i>
                                                @break
                                            @case('notifications')
                                                <i class="fas fa-bell me-2 text-primary"></i>
                                                @break
                                            @case('users')
                                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                                @break
                                            @case('roles')
                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                @break
                                            @case('permissions')
                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                @break
                                            @default
                                                <i class="fas fa-cog me-2 text-primary"></i>
                                        @endswitch
                                        {{ ucfirst($module) }}
                                    </h4>
                                </div>
                                <div class="module-permissions">
                                    @foreach($permissions as $permission)
                                    <div class="perm-item">
                                        <input type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            id="perm_{{ $permission->id }}"
                                            {{ in_array($permission->id, $selectedRolePermissionIds) ? 'checked' : '' }}
                                            {{ ($selectedRole->name === 'super_admin' || !auth('admin')->user()->hasPermission('manage_role_permissions')) ? 'disabled' : '' }}>
                                        <label for="perm_{{ $permission->id }}">{{ $permission->display_name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($selectedRole->name !== 'super_admin' && auth('admin')->user()->hasPermission('manage_role_permissions'))
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save Permissions for {{ $selectedRole->display_name }}
                            </button>
                        </div>
                        @endif
                    </form>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-hand-pointer fa-3x mb-3 text-muted"></i>
                        <h5>Select a role to configure permissions</h5>
                        <p class="text-muted">Please choose a role from the dropdown above to view and edit its permissions.</p>
                    </div>
                @endif
            </div>
            @endadmin_can

            <!-- AUDIT LOGS TAB -->
            <div id="auditTab" class="tab-content">
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('admin.users.audit-logs.export-csv', [
                                'tab' => 'audit',
                                'log_action' => request('log_action'),
                                'log_module' => request('log_module'),
                                'log_user_id' => request('log_user_id'),
                                'log_date' => request('log_date'),
                            ]) }}" class="btn btn-success">
                                <i class="fas fa-file-csv me-2"></i>Export CSV
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <input type="hidden" name="tab" value="audit">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-semibold">Action</label>
                                    <select name="log_action" class="form-select">
                                        <option value="">All Actions</option>
                                        @foreach($auditActions as $action)
                                            <option value="{{ $action }}" {{ request('log_action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-semibold">Module</label>
                                    <select name="log_module" class="form-select">
                                        <option value="">All Modules</option>
                                        @foreach($auditModules as $module)
                                            <option value="{{ $module }}" {{ request('log_module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label fw-semibold">User</label>
                                    <select name="log_user_id" class="form-select">
                                        <option value="">All Users</option>
                                        @foreach($allUsers as $adminUser)
                                            <option value="{{ $adminUser->id }}" {{ (string) request('log_user_id') === (string) $adminUser->id ? 'selected' : '' }}>
                                                {{ $adminUser->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label class="form-label fw-semibold">Date</label>
                                    <input type="date" name="log_date" class="form-control" value="{{ request('log_date') }}">
                                </div>
                                <div class="col-12 col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Apply
                                    </button>
                                    <a href="{{ route('admin.users.index', ['tab' => 'audit']) }}" class="btn btn-outline-secondary w-100">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">When</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Module</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($auditLogs as $log)
                                        <tr>
                                            <td class="ps-4">{{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-' }}</td>
                                            <td>{{ $log->user?->full_name ?? 'Unknown User' }}</td>
                                            <td>
                                                @php
                                                    $action = strtoupper((string) $log->action);
                                                    $actionBadgeClass = match(true) {
                                                        str_contains($action, 'CREATE') => 'bg-success-subtle text-success',
                                                        str_contains($action, 'UPDATE') => 'bg-warning-subtle text-warning',
                                                        str_contains($action, 'DELETE') => 'bg-danger-subtle text-danger',
                                                        str_contains($action, 'VIEW') => 'bg-info-subtle text-info',
                                                        default => 'bg-secondary-subtle text-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $actionBadgeClass }}">{{ $log->action }}</span>
                                            </td>
                                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $log->module }}</span></td>
                                            <td>
                                                @php
                                                    $details = is_array($log->details) ? $log->details : (json_decode((string) $log->details, true) ?: []);
                                                @endphp
                                                @if(!empty($details))
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary view-log-details-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#auditLogDetailsModal"
                                                            data-log-id="{{ $log->id }}"
                                                            data-log-action="{{ $log->action }}"
                                                            data-log-module="{{ $log->module }}"
                                                            data-log-user="{{ $log->user?->full_name ?? 'Unknown User' }}"
                                                            data-log-time="{{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-' }}"
                                                            data-log-details='@json($details)'>
                                                        <i class="fas fa-eye me-1"></i>View Details
                                                    </button>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>{{ $log->ip_address ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                                <div>No audit logs found for the selected filters.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(isset($auditLogs) && $auditLogs->total() > 0)
                        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <small class="text-muted order-2 order-md-1">
                                <i class="fas fa-list me-1"></i>
                                {{ $auditLogs->firstItem() ?? 0 }}-{{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }}
                            </small>
                            <nav class="order-1 order-md-2">
                                {{ $auditLogs->appends(array_merge(['tab' => 'audit'], request()->only(['log_action', 'log_module', 'log_user_id', 'log_date'])))->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal fade" id="auditLogDetailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-clipboard-list me-2"></i>Audit Log Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"><strong>User:</strong> <span id="auditDetailUser">-</span></div>
                                <div class="col-md-6"><strong>Time:</strong> <span id="auditDetailTime">-</span></div>
                                <div class="col-md-6"><strong>Action:</strong> <span id="auditDetailAction">-</span></div>
                                <div class="col-md-6"><strong>Module:</strong> <span id="auditDetailModule">-</span></div>
                            </div>
                            <label class="form-label fw-semibold">Details JSON</label>
                            <pre id="auditDetailJson" class="p-3 bg-light rounded border" style="max-height: 380px; overflow: auto; white-space: pre-wrap;">{}</pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADD USER MODAL -->
            @admin_can('create_users')
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-plus me-2"></i>
                                Add New User
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.users.store') }}" id="addUserForm">
                            @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Personal Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Middle Initial</label>
                                        <input type="text" class="form-control" name="middle_initial" maxlength="3" placeholder="A">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" placeholder="09XXXXXXXXX" maxlength="11">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Account Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" required>
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-select" name="role_id" required>
                                            <option value="">Select role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Department</label>
                                        <select class="form-select" name="department">
                                            <option value="">Select department</option>
                                            <option value="Administration">Administration</option>
                                            <option value="Records Management">Records Management</option>
                                            <option value="Health Services">Health Services</option>
                                            <option value="Events & Activities">Events & Activities</option>
                                            <option value="Public Relations">Public Relations</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="suspended">Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addUserForm" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create User
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- RESET PASSWORD MODAL -->
            @admin_can('reset_user_password')
                <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-key me-2"></i>
                                    Reset User Password
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="" id="resetPasswordForm">
                                @csrf
                                    <div class="text-center mb-4">
                                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;" id="resetUserAvatar">JD</div>
                                        <h5 id="resetUserName">Juan Dela Cruz</h5>
                                        <span class="badge bg-info-subtle text-info" id="resetUserEmail">juan@example.com</span>
                                    </div>

                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                                        <div>This will reset the user's password. They will receive an email with instructions to set a new password.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="resetPassword" required minlength="8"
                                                   onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('resetPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Minimum 8 characters. <strong>No spaces allowed.</strong></small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation" id="resetPasswordConfirm" required
                                                   onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('resetPasswordConfirm')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="send_email" id="sendResetEmail" checked>
                                        <label class="form-check-label" for="sendResetEmail">
                                            Send password reset email to user
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="force_logout" id="forceLogout" checked>
                                        <label class="form-check-label" for="forceLogout">
                                            Force logout from all devices
                                        </label>
                                    </div>
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="resetPasswordForm" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Reset Password
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            @endadmin_can

            <!-- ADD ROLE MODAL -->
            @admin_can('create_roles')
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-shield-alt me-2"></i>
                                Create New Role
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.roles.store') }}" id="addRoleForm">
                            @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add_role') @error('name') is-invalid @enderror @endif"
                                            name="name"
                                            value="{{ session('form_type') == 'add_role' ? old('name') : '' }}"
                                            placeholder="e.g., staff"
                                            pattern="[a-z_]+"
                                            title="Use lowercase letters and underscores only."
                                            required>
                                        @if(session('form_type') == 'add_role')
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                        <small class="text-muted">System name (lowercase, no spaces)</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Display Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add_role') @error('display_name') is-invalid @enderror @endif"
                                            name="display_name"
                                            value="{{ session('form_type') == 'add_role' ? old('display_name') : '' }}"
                                            placeholder="e.g., Staff Member">
                                        @if(session('form_type') == 'add_role')
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control @if(session('form_type') == 'add_role') @error('description') is-invalid @enderror @endif"
                                            name="description"
                                            rows="2"
                                            placeholder="Role description">{{ session('form_type') == 'add_role' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add_role')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Assign Permissions</h6>
                                        <p class="text-muted small">Select the permissions this role should have</p>
                                    </div>
                                    
                                    <div class="col-12">
                                        @php
                                            $allPermissions = \App\Models\AdminPermission::all()->groupBy('module');
                                        @endphp
                                        
                                        @foreach($allPermissions as $module => $permissions)
                                            <div class="permission-module mb-3">
                                                <div class="module-header">
                                                    <h4 class="mb-0">
                                                        @switch($module)
                                                            @case('dashboard')
                                                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                                                @break
                                                            @case('residents')
                                                                <i class="fas fa-users me-2 text-primary"></i>
                                                                @break
                                                            @case('residency')
                                                            @case('indigency')
                                                            @case('clearance')
                                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                                @break
                                                            @case('blotter')
                                                                <i class="fas fa-book me-2 text-primary"></i>
                                                                @break
                                                            @case('announcements')
                                                                <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                                @break
                                                            @case('events')
                                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                                @break
                                                            @case('projects')
                                                                <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                                @break
                                                            @case('requests')
                                                                <i class="fas fa-inbox me-2 text-primary"></i>
                                                                @break
                                                            @case('notifications')
                                                                <i class="fas fa-bell me-2 text-primary"></i>
                                                                @break
                                                            @case('users')
                                                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                                                @break
                                                            @case('roles')
                                                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                                @break
                                                            @case('permissions')
                                                                <i class="fas fa-lock me-2 text-primary"></i>
                                                                @break
                                                            @default
                                                                <i class="fas fa-cog me-2 text-primary"></i>
                                                        @endswitch
                                                        {{ $module }}
                                                    </h4>
                                                </div>
                                                <div class="module-permissions">
                                                    <div class="permission-checklist">
                                                        @foreach($permissions as $permission)
                                                        <div class="perm-check">
                                                            <label>
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                                    {{ session('form_type') == 'add_role' && is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                                                {{ $permission->display_name }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addRoleForm" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create Role
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- VIEW USER MODALS (Sample for each user) -->
            @forelse($users ?? [] as $user)
            <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user me-2"></i>
                                User Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ substr($user->first_name ?? 'J', 0, 1) }}{{ substr($user->last_name ?? 'D', 0, 1) }}
                                </div>
                                <h5>{{ trim(($user->first_name ?? 'Juan') . ' ' . (($user->middle_initial ?? null) ? ($user->middle_initial . ' ') : '') . ($user->last_name ?? 'Dela Cruz')) }}</h5>
                                <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">User ID</small>
                                        <strong>{{ $user->user_id ?? 'USR-001' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Status</small>
                                        @if(($user->status ?? 'active') == 'active')
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @elseif(($user->status ?? '') == 'inactive')
                                            <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ $user->email ?? 'juan.delacruz@barangay.gov.ph' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Middle Initial</small>
                                        <strong>{{ $user->middle_initial ?: 'N/A' }}</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Department</small>
                                        <strong>{{ $user->department ?? 'Administration' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Joined Date</small>
                                        <strong>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Jan 1, 2020' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Last Login</small>
                                        <strong>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i:s') : 'Never logged in' }}</strong>
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
            @empty
            @endforelse

            <!-- EDIT USER MODALS -->
            @if(auth('admin')->user()->hasPermission('update_users'))
                @forelse($users ?? [] as $user)
                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit User
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name ?? 'Juan' }}" required>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">Middle Initial</label>
                                            <input type="text" class="form-control" name="middle_initial" value="{{ $user->middle_initial }}" maxlength="3" placeholder="A">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name ?? 'Dela Cruz' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ $user->email ?? 'juan.delacruz@barangay.gov.ph' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" name="contact_number" value="{{ $user->contact_number ?? '09123456789' }}" maxlength="11">
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Account Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="username" value="{{ $user->username ?? 'juan.delacruz' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="role_id" required>
                                                <option value="">Select role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ ($user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->display_name ?? $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Department</label>
                                            <select class="form-select" name="department">
                                                <option value="">Select department</option>
                                                <option value="Administration" {{ ($user->department ?? '') == 'Administration' ? 'selected' : '' }}>Administration</option>
                                                <option value="Records Management" {{ ($user->department ?? '') == 'Records Management' ? 'selected' : '' }}>Records Management</option>
                                                <option value="Health Services" {{ ($user->department ?? '') == 'Health Services' ? 'selected' : '' }}>Health Services</option>
                                                <option value="Events & Activities" {{ ($user->department ?? '') == 'Events & Activities' ? 'selected' : '' }}>Events & Activities</option>
                                                <option value="Public Relations" {{ ($user->department ?? '') == 'Public Relations' ? 'selected' : '' }}>Public Relations</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="active" {{ ($user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ ($user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="suspended" {{ ($user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editUserForm{{ $user->id }}" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            @endif

            <!-- USER PERMISSIONS MODALS -->
            @if(auth('admin')->user()->hasPermission('manage_user_permissions'))
                @forelse($users ?? [] as $user)
                <div class="modal fade" id="userPermissionsModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Manage User Permissions
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.users.permissions', $user->id) }}" id="userPermissionsForm{{ $user->id }}">
                                @csrf
                                    @php
                                        $isSuperAdminUser = $user->role && $user->role->name === 'super_admin';
                                    @endphp
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="user-avatar">{{ substr($user->first_name ?? 'J', 0, 1) }}{{ substr($user->last_name ?? 'D', 0, 1) }}</div>
                                            <div>
                                                <h6 class="mb-1">{{ $user->first_name ?? 'Juan' }} {{ $user->last_name ?? 'Dela Cruz' }}</h6>
                                                <span class="role-badge {{ $user->role->name ?? 'staff' }}">{{ $user->role->display_name ?? 'Staff' }}</span>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">Configure additional permissions for this user beyond their role-based permissions.</p>
                                    </div>

                                    @if($isSuperAdminUser)
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="fas fa-lock me-2"></i>
                                        <div>Super admin permissions are fixed and cannot be modified.</div>
                                    </div>
                                    @endif

                                    <div class="permissions-grid" style="grid-template-columns: 1fr;">
                                        @php
                                            $allPermissions = \App\Models\AdminPermission::all()->groupBy('module');
                                        @endphp
                                        
                                        @foreach($allPermissions as $module => $permissions)
                                        <div class="permission-module">
                                            <div class="module-header">
                                                <h4>
                                                    @switch($module)
                                                        @case('dashboard')
                                                            <i class="fas fa-chart-line me-2 text-primary"></i>
                                                            @break
                                                        @case('residents')
                                                            <i class="fas fa-users me-2 text-primary"></i>
                                                            @break
                                                        @case('residency')
                                                        @case('indigency')
                                                        @case('clearance')
                                                            <i class="fas fa-file-alt me-2 text-primary"></i>
                                                            @break
                                                        @case('blotter')
                                                            <i class="fas fa-book me-2 text-primary"></i>
                                                            @break
                                                        @case('announcements')
                                                            <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                            @break
                                                        @case('events')
                                                            <i class="fas fa-calendar me-2 text-primary"></i>
                                                            @break
                                                        @case('projects')
                                                            <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                            @break
                                                        @case('requests')
                                                            <i class="fas fa-inbox me-2 text-primary"></i>
                                                            @break
                                                        @case('notifications')
                                                            <i class="fas fa-bell me-2 text-primary"></i>
                                                            @break
                                                        @case('users')
                                                            <i class="fas fa-user-cog me-2 text-primary"></i>
                                                            @break
                                                        @case('roles')
                                                            <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                            @break
                                                        @case('permissions')
                                                            <i class="fas fa-lock me-2 text-primary"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-cog me-2 text-primary"></i>
                                                    @endswitch
                                                    {{ $module }}
                                                </h4>
                                            </div>
                                            <div class="module-permissions">
                                                @foreach($permissions as $permission)
                                                <div class="perm-item">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="user_perm_{{ $permission->id }}_{{ $user->id }}"
                                                        {{ $user->hasPermission($permission->name) ? 'checked' : '' }}
                                                        {{ $isSuperAdminUser ? 'disabled' : '' }}>
                                                    <label for="user_perm_{{ $permission->id }}_{{ $user->id }}">{{ $permission->display_name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    @if(session('form_type') == 'user_permissions_' . $user->id)
                                        @error('permissions')
                                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                        @enderror
                                        @error('permissions.*')
                                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="userPermissionsForm{{ $user->id }}" class="btn btn-success" {{ $isSuperAdminUser ? 'disabled' : '' }}>
                                        <i class="fas fa-save me-2"></i>Save Permissions
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            @endif

            <!-- EDIT ROLE MODAL -->
            @if(auth('admin')->user()->hasPermission('update_roles'))
                @if(isset($roles) && $roles->count() > 0)
                    @foreach($roles as $role)
                    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-shield-edit me-2"></i>
                                        Edit Role: {{ $role->display_name ?? $role->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" id="editRoleForm{{ $role->id }}">
                                    @csrf
                                    @method('PUT')
                                        <div class="row g-3">
                                            @if($role->is_system_role)
                                            <div class="col-12">
                                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                                    <i class="fas fa-info-circle me-2 fs-5"></i>
                                                    <div>This is a system role with limited edit capabilities.</div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                    name="name" value="{{ old('name', $role->name) }}" 
                                                    {{ $role->is_system_role ? 'readonly' : '' }} required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">System name (lowercase, no spaces)</small>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Display Name</label>
                                                <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                                    name="display_name" value="{{ old('display_name', $role->display_name) }}">
                                                @error('display_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                                        name="description" rows="2">{{ old('description', $role->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @if(!$role->is_system_role && auth('admin')->user()->hasPermission('manage_role_permissions'))
                                            <div class="col-12 mt-3">
                                                <h6 class="fw-semibold text-primary">Role Permissions</h6>
                                                <p class="text-muted small">Configure which permissions this role should have</p>
                                            </div>
                                            
                                            <div class="col-12">
                                                @php
                                                    $allPermissions = \App\Models\AdminPermission::all()->groupBy('module');
                                                    $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                                                @endphp
                                                
                                                @foreach($allPermissions as $module => $permissions)
                                                    <div class="permission-module mb-3">
                                                        <div class="module-header">
                                                            <h4 class="mb-0">
                                                                @switch($module)
                                                                    @case('dashboard')
                                                                        <i class="fas fa-chart-line me-2 text-primary"></i>
                                                                        @break
                                                                    @case('residents')
                                                                        <i class="fas fa-users me-2 text-primary"></i>
                                                                        @break
                                                                    @case('residency')
                                                                    @case('indigency')
                                                                    @case('clearance')
                                                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                                                        @break
                                                                    @case('blotter')
                                                                        <i class="fas fa-book me-2 text-primary"></i>
                                                                        @break
                                                                    @case('announcements')
                                                                        <i class="fas fa-bullhorn me-2 text-primary"></i>
                                                                        @break
                                                                    @case('events')
                                                                        <i class="fas fa-calendar me-2 text-primary"></i>
                                                                        @break
                                                                    @case('projects')
                                                                        <i class="fas fa-project-diagram me-2 text-primary"></i>
                                                                        @break
                                                                    @case('requests')
                                                                        <i class="fas fa-inbox me-2 text-primary"></i>
                                                                        @break
                                                                    @case('notifications')
                                                                        <i class="fas fa-bell me-2 text-primary"></i>
                                                                        @break
                                                                    @case('users')
                                                                        <i class="fas fa-user-cog me-2 text-primary"></i>
                                                                        @break
                                                                    @case('roles')
                                                                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                                                                        @break
                                                                    @case('permissions')
                                                                        <i class="fas fa-lock me-2 text-primary"></i>
                                                                        @break
                                                                    @default
                                                                        <i class="fas fa-cog me-2 text-primary"></i>
                                                                @endswitch
                                                                {{ $module }}
                                                            </h4>
                                                        </div>
                                                        <div class="module-permissions">
                                                            <div class="permission-checklist">
                                                                @foreach($permissions as $permission)
                                                                <div class="perm-check">
                                                                    <label>
                                                                        <input type="checkbox" 
                                                                            name="permissions[]" 
                                                                            value="{{ $permission->id }}"
                                                                            {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                                        {{ $permission->display_name }}
                                                                    </label>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                        <button type="submit" form="editRoleForm{{ $role->id }}" class="btn btn-primary" {{ $role->is_system_role ? 'disabled' : '' }}>
                                            <i class="fas fa-save me-2"></i>Update Role
                                        </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endif

            <!-- VIEW ROLE MEMBERS MODAL -->
            @if(auth('admin')->user()->hasAnyPermission(['manage_role_permissions', 'view_users']))
                @if(isset($roles) && $roles->count() > 0)
                    @foreach($roles as $role)
                    <div class="modal fade" id="viewRoleMembersModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-users me-2"></i>
                                        {{ $role->display_name ?? $role->name }} Members
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $role->display_name ?? $role->name }}</h6>
                                            <span class="badge bg-primary">{{ $role->users_count ?? $role->users->count() }} members</span>
                                        </div>
                                        @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMemberToRoleModal{{ $role->id }}">
                                            <i class="fas fa-user-plus me-1"></i>Add Member
                                        </button>
                                        @endif
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                                    <th class="text-end">Actions</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($role->users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar me-2">
                                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ trim($user->first_name . ' ' . ($user->middle_initial ? $user->middle_initial . ' ' : '') . $user->last_name) }}</div>
                                                                <small class="text-muted">ID: {{ $user->user_id }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->department ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($user->status == 'active')
                                                            <span class="badge bg-success-subtle text-success">Active</span>
                                                        @elseif($user->status == 'inactive')
                                                            <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">Suspended</span>
                                                        @endif
                                                    </td>
                                                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                                                    <td class="text-end">
                                                        <form method="POST" action="{{ route('admin.roles.members.remove', [$role->id, $user->id]) }}" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirmDelete(event, 'Remove this user from the role?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from role">
                                                                <i class="fas fa-user-minus"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="{{ auth('admin')->user()->hasPermission('manage_role_permissions') ? '5' : '4' }}" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-users-slash fa-2x mb-2"></i>
                                                            <p>No members found in this role</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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

                    <!-- ADD MEMBER TO ROLE MODAL -->
                    @if(auth('admin')->user()->hasPermission('manage_role_permissions'))
                    <div class="modal fade" id="addMemberToRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Add Member to {{ $role->display_name ?? $role->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.roles.members.add', $role->id) }}" id="addMemberForm{{ $role->id }}">
                                    @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Select User</label>
                                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                                <option value="">Choose a user...</option>
                                                @foreach($allUsers ?? [] as $user)
                                                    @if(!$role->users->contains($user->id))
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->first_name }} {{ $user->last_name }} 
                                                        ({{ $user->email }}) - {{ $user->department ?? 'No Department' }}
                                                    </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                        <button type="submit" form="addMemberForm{{ $role->id }}" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Add Member
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                @endif
            @endif

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any() && session('form_type') == 'add_role')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabLinks = document.querySelectorAll('.tab-link');
            tabLinks.forEach(link => {
                if (link.textContent.toLowerCase().includes('roles')) {
                    link.click();
                }
            });

            const modalEl = document.getElementById('addRoleModal');
            if (modalEl) {
                new bootstrap.Modal(modalEl).show();
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const userSearch = document.getElementById('globalSearch');
            if (!userSearch) return;

            document.addEventListener('keydown', function(event) {
                if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                    event.preventDefault();
                    userSearch.focus();
                    userSearch.select();
                }
            });
        });

    </script>
    @endif

    @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_role_'))
        @php
            $editRoleId = str_replace('edit_role_', '', session('form_type'));
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(link => {
                    if (link.textContent.toLowerCase().includes('roles')) {
                        link.click();
                    }
                });

                const modalEl = document.getElementById('editRoleModal{{ $editRoleId }}');
                if (modalEl) {
                    new bootstrap.Modal(modalEl).show();
                }
            });
        </script>
    @endif

    @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'user_permissions_'))
        @php
            $permissionsUserId = str_replace('user_permissions_', '', session('form_type'));
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(link => {
                    if (link.textContent.toLowerCase().includes('users')) {
                        link.click();
                    }
                });

                const modalEl = document.getElementById('userPermissionsModal{{ $permissionsUserId }}');
                if (modalEl) {
                    new bootstrap.Modal(modalEl).show();
                }
            });
        </script>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
            window.isStrongPassword = function(value) {
            return value.length >= 8
                && /[A-Z]/.test(value)
                && /[a-z]/.test(value)
                && /[0-9]/.test(value)
                && /[^A-Za-z0-9]/.test(value);
            };

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
                if (!fb) {
                    fb = document.createElement('div');
                    fb.className = 'invalid-feedback';
                    fb.dataset.generated = 'true';
                    el.insertAdjacentElement('afterend', fb);
                }
            fb.textContent = msg;
        }
            function cf(el) {
                if (!el) return;
                el.classList.remove('is-invalid');
                const fb = el.parentElement.querySelector('.invalid-feedback[data-generated="true"]');
                if (fb) {
                    fb.remove();
                }
            }

        // --- User form validation (shared for add + edit) ---
        function attachUserValidation(form) {
            if (!form) return;
            const firstName = form.querySelector('[name="first_name"]');
            const middleInitial = form.querySelector('[name="middle_initial"]');
            const lastName  = form.querySelector('[name="last_name"]');
            const email     = form.querySelector('[name="email"]');
            const contact   = form.querySelector('[name="contact_number"]');
            const username  = form.querySelector('[name="username"]');
            const password  = form.querySelector('[name="password"]');
            const roleId    = form.querySelector('[name="role_id"]');

            const namePattern = /^[a-zA-Z\s\-\.]+$/;
            const middleInitialPattern = /^[A-Za-z]\.?$/;

            if (firstName) {
                firstName.addEventListener('input', function() { cf(this); }, {once: true});
                firstName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'First name is required.');
                    else if (v.length < 2) sf(this, 'First name must be at least 2 characters.');
                    else if (!namePattern.test(v)) sf(this, 'First name can only contain letters, spaces, hyphens, or periods.');
                    else cf(this);
                });
            }
            if (lastName) {
                lastName.addEventListener('input', function() { cf(this); }, {once: true});
                lastName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Last name is required.');
                    else if (v.length < 2) sf(this, 'Last name must be at least 2 characters.');
                    else if (!namePattern.test(v)) sf(this, 'Last name can only contain letters, spaces, hyphens, or periods.');
                    else cf(this);
                });
            }
            if (middleInitial) {
                middleInitial.addEventListener('input', function() {
                    this.value = this.value.replace(/[^A-Za-z\.]/g, '').slice(0, 3).toUpperCase();
                    const v = this.value.trim();
                    if (!v) cf(this);
                    else if (!middleInitialPattern.test(v)) sf(this, 'Middle initial must be one letter, optional period (e.g. A or A.).');
                    else cf(this);
                });
            }
            if (email) {
                email.addEventListener('input', function() { cf(this); }, {once: true});
                email.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Email is required.');
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) sf(this, 'Enter a valid email address.');
                    else cf(this);
                });
            }
            if (contact) {
                contact.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (this.value.length > 0) {
                        if (!this.value.startsWith('09')) sf(this, 'Contact must start with 09.');
                        else if (this.value.length !== 11) sf(this, 'Contact must be exactly 11 digits.');
                        else cf(this);
                    } else cf(this);
                });
            }
            if (username) {
                username.addEventListener('input', function() { cf(this); }, {once: true});
                username.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Username is required.');
                    else if (v.length < 3) sf(this, 'Username must be at least 3 characters.');
                    else if (!/^[a-zA-Z0-9_.\-]+$/.test(v)) sf(this, 'Username can only contain letters, numbers, underscores, dots, or hyphens.');
                    else cf(this);
                });
            }
            if (password) {
                password.addEventListener('input', function() { cf(this); }, {once: true});
                password.addEventListener('input', function() {
                    if (!this.value) sf(this, 'Password is required.');
                    else if (!window.isStrongPassword(this.value)) sf(this, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.');
                    else cf(this);
                });
            }
            if (roleId) roleId.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a role.'); else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (firstName) {
                    const v = firstName.value.trim();
                    if (!v) { sf(firstName, 'First name is required.'); valid = false; }
                    else if (v.length < 2) { sf(firstName, 'First name must be at least 2 characters.'); valid = false; }
                    else if (!namePattern.test(v)) { sf(firstName, 'First name can only contain letters, spaces, hyphens, or periods.'); valid = false; }
                }
                if (lastName) {
                    const v = lastName.value.trim();
                    if (!v) { sf(lastName, 'Last name is required.'); valid = false; }
                    else if (v.length < 2) { sf(lastName, 'Last name must be at least 2 characters.'); valid = false; }
                    else if (!namePattern.test(v)) { sf(lastName, 'Last name can only contain letters, spaces, hyphens, or periods.'); valid = false; }
                }
                if (middleInitial) {
                    const v = middleInitial.value.trim();
                    if (v && !middleInitialPattern.test(v)) {
                        sf(middleInitial, 'Middle initial must be one letter, optional period (e.g. A or A.).');
                        valid = false;
                    }
                }
                if (email) {
                    const v = email.value.trim();
                    if (!v) { sf(email, 'Email is required.'); valid = false; }
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { sf(email, 'Enter a valid email address.'); valid = false; }
                }
                if (contact && contact.value.length > 0) {
                    if (!contact.value.startsWith('09') || contact.value.length !== 11) {
                        sf(contact, 'Contact must be 11 digits starting with 09.'); valid = false;
                    }
                }
                if (username) {
                    const v = username.value.trim();
                    if (!v) { sf(username, 'Username is required.'); valid = false; }
                    else if (v.length < 3) { sf(username, 'Username must be at least 3 characters.'); valid = false; }
                    else if (!/^[a-zA-Z0-9_.\-]+$/.test(v)) { sf(username, 'Username can only contain letters, numbers, underscores, dots, or hyphens.'); valid = false; }
                }
                if (password) {
                    if (!password.value) { sf(password, 'Password is required.'); valid = false; }
                    else if (!window.isStrongPassword(password.value)) { sf(password, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.'); valid = false; }
                }
                if (roleId && !roleId.value) { sf(roleId, 'Please select a role.'); valid = false; }
                if (!valid) e.preventDefault();
            });
        }

        // --- Reset password form validation ---
        const resetForm = document.getElementById('resetPasswordForm');
        if (resetForm) {
            const pwField    = document.getElementById('resetPassword');
            const pwConfirm  = document.getElementById('resetPasswordConfirm');

            if (pwField) {
                pwField.addEventListener('input', function() { cf(this); }, {once: true});
                pwField.addEventListener('input', function() {
                    // Remove spaces from password
                    this.value = this.value.replace(/\s/g, "");
                    if (!this.value) sf(this, 'Password is required.');
                    else if (!window.isStrongPassword(this.value)) sf(this, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.');
                    else cf(this);
                    if (pwConfirm && pwConfirm.value) {
                        if (pwConfirm.value !== this.value) sf(pwConfirm, 'Passwords do not match.');
                        else cf(pwConfirm);
                    }
                });
            }
            if (pwConfirm) {
                pwConfirm.addEventListener('input', function() {
                    // Remove spaces from confirm password
                    this.value = this.value.replace(/\s/g, "");
                    if (!this.value) sf(this, 'Please confirm your password.');
                    else if (pwField && this.value !== pwField.value) sf(this, 'Passwords do not match.');
                    else cf(this);
                });
            }
            resetForm.addEventListener('submit', function(e) {
                let valid = true;
                if (pwField) {
                    if (!pwField.value) { sf(pwField, 'Password is required.'); valid = false; }
                    else if (!window.isStrongPassword(pwField.value)) { sf(pwField, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.'); valid = false; }
                }
                if (pwConfirm) {
                    if (!pwConfirm.value) { sf(pwConfirm, 'Please confirm your password.'); valid = false; }
                    else if (pwField && pwConfirm.value !== pwField.value) { sf(pwConfirm, 'Passwords do not match.'); valid = false; }
                }
                if (!valid) e.preventDefault();
            });
        }

        // --- Role form validation (shared for add + edit) ---
        function attachRoleValidation(form) {
            if (!form) return;
            const roleName = form.querySelector('[name="name"]');

            if (roleName && !roleName.readOnly) {
                roleName.addEventListener('input', function() { cf(this); }, {once: true});
                roleName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Role name is required.');
                    else if (v.length < 2) sf(this, 'Role name must be at least 2 characters.');
                    else if (/\s/.test(v)) sf(this, 'Role name must not contain spaces.');
                    else if (!/^[a-z_]+$/.test(v)) sf(this, 'Role name must be lowercase letters and underscores only.');
                    else cf(this);
                });
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (roleName && !roleName.readOnly) {
                    const v = roleName.value.trim();
                    if (!v) { sf(roleName, 'Role name is required.'); valid = false; }
                    else if (v.length < 2) { sf(roleName, 'Role name must be at least 2 characters.'); valid = false; }
                    else if (/\s/.test(v)) { sf(roleName, 'Role name must not contain spaces.'); valid = false; }
                    else if (!/^[a-z_]+$/.test(v)) { sf(roleName, 'Role name must be lowercase letters and underscores only.'); valid = false; }
                }
                if (!valid) e.preventDefault();
            });
        }

        // Attach to all user forms
        attachUserValidation(document.getElementById('addUserForm'));
        document.querySelectorAll('[id^="editUserForm"]').forEach(attachUserValidation);

        // Attach to all role forms
        attachRoleValidation(document.getElementById('addRoleForm'));
        document.querySelectorAll('[id^="editRoleForm"]').forEach(attachRoleValidation);
    });
    </script>

    <script>
        // Add to your existing script section
        function confirmReset() {
            return confirm('Are you sure you want to reset all permissions to default values? This action cannot be undone.');
        }

        // Tab Switching with query parameter
        function switchTab(tabName, event) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
            
            // Add active class to selected tab and content
            document.getElementById(tabName + 'Tab').classList.add('active');
            event.currentTarget.classList.add('active');
            
            // Update URL with tab parameter (without page reload)
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
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

        // Role Actions
        function editRole(roleId) {
            Swal.fire({
                title: 'Edit Role',
                text: 'Opening role editor for ' + roleId,
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
        }

        function viewRoleMembers(roleId) {
            Swal.fire({
                title: 'Role Members',
                text: 'Viewing members of ' + roleId + ' role',
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
        }

        // Permissions Actions
        function savePermissions() {
            Swal.fire({
                title: 'Success!',
                text: 'Permissions saved successfully',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }

        function resetPermissions() {
            Swal.fire({
                title: 'Reset Permissions?',
                text: 'This will reset all permissions to default values',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset checkboxes logic here
                    Swal.fire('Reset Complete', 'Permissions have been reset to default', 'success');
                }
            });
        }

        // Function to prompt for new password
        function promptNewPassword(userId, userName) {
            Swal.fire({
                title: 'Reset Password for ' + userName,
                html: `
                    <div class="mb-3 text-start">
                        <label class="form-label">New Password</label>
                        <input type="password" id="new_password_input" class="form-control" placeholder="Enter new password" minlength="8">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password_input" class="form-control" placeholder="Confirm new password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Reset Password',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                preConfirm: () => {
                    const password = document.getElementById('new_password_input');
                    const confirm = document.getElementById('confirm_password_input');
                    
                    if (!password || !confirm) {
                        Swal.showValidationMessage('Error finding input fields');
                        return false;
                    }
                    
                    if (!password.value) {
                        Swal.showValidationMessage('Password is required');
                        return false;
                    }
                    if (password.value.length < 8) {
                        Swal.showValidationMessage('Password must be at least 8 characters');
                        return false;
                    }
                    if (!window.isStrongPassword(password.value)) {
                        Swal.showValidationMessage('Password must include uppercase, lowercase, number, and special character');
                        return false;
                    }
                    if (password.value !== confirm.value) {
                        Swal.showValidationMessage('Passwords do not match');
                        return false;
                    }
                    return password.value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Check if the hidden input exists before setting value
                    const hiddenInput = document.getElementById('new_password_' + userId);
                    const form = document.getElementById('resetPasswordForm_' + userId);
                    
                    if (hiddenInput && form) {
                        // Set the password in the hidden input
                        hiddenInput.value = result.value;
                        
                        // Submit the form
                        form.submit();
                    } else {
                        console.error('Form elements not found:', {
                            hiddenInput: hiddenInput ? 'found' : 'not found',
                            form: form ? 'found' : 'not found',
                            userId: userId
                        });
                        
                        Swal.fire({
                            title: 'Error',
                            text: 'Could not find form elements. Please try again or refresh the page.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                }
            });
        }

        // Confirm reset password form submission
        function confirmResetPassword(event, form, userName) {
            event.preventDefault();
            
            const passwordInput = form.querySelector('input[name="new_password"]');
            
            if (!passwordInput.value) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please enter a new password',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }
            
            Swal.fire({
                title: 'Reset Password',
                html: `Are you sure you want to reset password for <strong>${userName}</strong>?<br><br>New password will be: <strong>${passwordInput.value}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            
            if (tab && tab !== 'users') {
                // Find and click the corresponding tab
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(link => {
                    if (link.textContent.toLowerCase().includes(tab)) {
                        link.click();
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.view-log-details-btn');

            detailButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const detailsRaw = this.dataset.logDetails || '{}';
                    let prettyJson = '{}';

                    try {
                        const parsed = JSON.parse(detailsRaw);
                        prettyJson = JSON.stringify(parsed, null, 2);
                    } catch (error) {
                        prettyJson = detailsRaw;
                    }

                    const userEl = document.getElementById('auditDetailUser');
                    const timeEl = document.getElementById('auditDetailTime');
                    const actionEl = document.getElementById('auditDetailAction');
                    const moduleEl = document.getElementById('auditDetailModule');
                    const jsonEl = document.getElementById('auditDetailJson');

                    if (userEl) userEl.textContent = this.dataset.logUser || '-';
                    if (timeEl) timeEl.textContent = this.dataset.logTime || '-';
                    if (actionEl) actionEl.textContent = this.dataset.logAction || '-';
                    if (moduleEl) moduleEl.textContent = this.dataset.logModule || '-';
                    if (jsonEl) jsonEl.textContent = prettyJson;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Handle password reset success message
            @if(session('success') && str_contains(session('success'), 'Password reset'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 5000
                });
            @endif
            
            @if(session('error') && str_contains(session('error'), 'password'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>

    <script>
        (function () {
            'use strict';

            const modalCopy = [
                ['addUserModal', 'Create an administrator account, assign a role, and set the first login details.'],
                ['resetPasswordModal', 'Set a secure temporary password and choose whether to send reset instructions.'],
                ['addRoleModal', 'Create a role with only the permissions needed for that responsibility.'],
                ['auditLogDetailsModal', 'Review the recorded action, actor, timestamp, module, and stored event details.']
            ];

            function resolveModalSubtitle(modal) {
                const id = modal.getAttribute('id') || '';

                for (const [key, copy] of modalCopy) {
                    if (id === key) return copy;
                }

                if (id.startsWith('viewUserModal')) {
                    return 'View account profile, role, contact, and activity information without editing anything.';
                }

                if (id.startsWith('editUserModal')) {
                    return 'Update only the account fields that need to change, then save the user record.';
                }

                if (id.startsWith('userPermissionsModal')) {
                    return 'Grant or remove direct user permissions. Use search to quickly find a permission.';
                }

                if (id.startsWith('editRoleModal')) {
                    return 'Update the role name, description, and permission set for this role.';
                }

                if (id.startsWith('viewRoleMembersModal')) {
                    return 'Review the administrator accounts currently assigned to this role.';
                }

                return 'Review the details below before saving changes.';
            }

            function enhanceModalHeaders() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    const header = modal.querySelector('.modal-header');
                    const title = modal.querySelector('.modal-title');

                    if (!header || !title || header.querySelector('.ux-modal-subtitle')) {
                        return;
                    }

                    const subtitle = document.createElement('p');
                    subtitle.className = 'ux-modal-subtitle';
                    subtitle.textContent = resolveModalSubtitle(modal);

                    const closeBtn = header.querySelector('.btn-close');
                    if (closeBtn) {
                        header.insertBefore(subtitle, closeBtn);
                    } else {
                        header.appendChild(subtitle);
                    }
                });
            }

            function addPermissionSearch() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    const id = modal.getAttribute('id') || '';
                    const hasPermissions = modal.querySelector('.permission-checklist, .module-permissions');

                    if (!hasPermissions || modal.querySelector('.ux-permission-search')) {
                        return;
                    }

                    if (!id.includes('Permission') && !id.includes('Role')) {
                        return;
                    }

                    const body = modal.querySelector('.modal-body');
                    if (!body) {
                        return;
                    }

                    const modules = Array.from(modal.querySelectorAll('.permission-module'));
                    const checks = Array.from(modal.querySelectorAll('.perm-check, .perm-item'));
                    const search = document.createElement('div');
                    search.className = 'ux-permission-search';
                    search.innerHTML = `
                        <div class="users-search-field">
                            <i class="fas fa-search"></i>
                            <input type="search" class="form-control ux-permission-input" placeholder="Search permission..." aria-label="Search permissions">
                        </div>
                        <span class="users-count-pill"><i class="fas fa-shield-alt"></i>${checks.length} permissions</span>
                    `;

                    body.insertBefore(search, body.firstElementChild);

                    const input = search.querySelector('.ux-permission-input');

                    input.addEventListener('input', () => {
                        const query = input.value.trim().toLowerCase();

                        checks.forEach((item) => {
                            const match = item.textContent.toLowerCase().includes(query);
                            item.classList.toggle('is-hidden', query !== '' && !match);
                        });

                        modules.forEach((module) => {
                            const visible = Array.from(module.querySelectorAll('.perm-check, .perm-item'))
                                .some((item) => !item.classList.contains('is-hidden'));
                            module.classList.toggle('is-empty', !visible);
                        });
                    });
                });
            }

            function keepModalFocusClean() {
                document.querySelectorAll('.modal').forEach((modal) => {
                    modal.addEventListener('shown.bs.modal', () => {
                        const first = modal.querySelector('input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
                        if (first && !modal.querySelector('.ux-permission-input')) {
                            setTimeout(() => first.focus(), 80);
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                enhanceModalHeaders();
                addPermissionSearch();
                keepModalFocusClean();
            });
        })();
    </script>

</body>
</html>

