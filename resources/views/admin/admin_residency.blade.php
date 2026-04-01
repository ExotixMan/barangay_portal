<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residency Applications - Admin</title>
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
            content: 'âš ï¸';
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
            min-width: 1200px;
            overflow: visible !important;
        }

        @media (max-width: 768px) {
            .table {
                min-width: 1000px;
            }

            .dropdown-menu {
                max-width: 90vw;
                max-height: 300px;
                overflow-y: auto;
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
            overflow: visible !important;
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
            background: #e5f4ff !important;
            color: #0288d1;
        }

        .badge.bg-secondary-subtle {
            background: #f1f3f4 !important;
            color: #5f6368;
        }

        .badge.bg-primary-subtle {
            background: #e8eaf6 !important;
            color: #3949ab;
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

        /* Activity Log Styling */
        .activity-item {
            border-left: 3px solid var(--primary);
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
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

        .table td .d-flex {
            overflow: visible !important;
        }

        .table .dropdown-menu {
            margin-top: 0.25rem;
            z-index: 1055;
            max-height: 250px;
            overflow-y: auto;
        }

        .table td .btn-group {
            position: relative;
            display: inline-flex;
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

            .table .dropdown-menu {
                max-width: 90vw;
                max-height: 300px;
                overflow-y: auto;
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
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-users"></i>
            <span>Registry</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="registrySubmenu">
            @admin_can('view_residents')
            <a href="{{ route('admin.residents.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
            @endadmin_can
            
            @admin_can('view_residency')
            <a href="{{ route('admin.residency.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-file-alt"></i> <span>Residency Applications</span></a>
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
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <i class="fas fa-file-alt me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Residency Applications
                </h1>
            </div>
            <div class="profile-badge dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                   id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fs-4 me-2"></i>
                    <span>{{ Auth::guard('admin')->user()->full_name }}</span>
                    <span class="role-badge ms-2">{{ Auth::guard('admin')->user()->getRoleDisplayName() }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <span class="dropdown-item-text">
                            <small class="text-muted">Logged in as</small><br>
                            <strong>{{ Auth::guard('admin')->user()->email }}</strong>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-3 p-lg-4">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
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

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4">
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
                            <i class="fas fa-file-alt"></i>
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
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.residency.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Applications
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_residency')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Residency Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.residency.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by name, reference, email..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="ready_pickup" {{ request('status') == 'ready_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                    <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="residency_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="owner" {{ request('residency_type') == 'owner' ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                    <option value="renter" {{ request('residency_type') == 'renter' ? 'selected' : '' }}>Renter / Tenant</option>
                                    <option value="family" {{ request('residency_type') == 'family' ? 'selected' : '' }}>Living with Family</option>
                                    <option value="relative" {{ request('residency_type') == 'relative' ? 'selected' : '' }}>Living with Relative</option>
                                    <option value="boarder" {{ request('residency_type') == 'boarder' ? 'selected' : '' }}>Boarder / Lodger</option>
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
                    @if(auth('admin')->user()->hasAnyPermission(['delete_residency', 'export_residency']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_residency')
                        <form id="bulkForm" method="POST" action="{{ route('admin.residency.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Delete</span>
                            </button>
                        </form>
                        @endadmin_can
                        
                        @admin_can('export_residency')
                        <form id="exportForm" method="POST" action="{{ route('admin.residency.export') }}" style="display: inline;">
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

            <!-- Residency Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="residencyTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Reference #
                                            @if(request('sort') == 'created_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Full Name
                                            @if(request('sort') == 'first_name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Birthdate</th>
                                    <th class="d-none d-md-table-cell">Gender</th>
                                    <th class="d-none d-xl-table-cell">Civil Status</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'years_residing', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Years
                                            @if(request('sort') == 'years_residing')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Type</th>
                                    <th class="d-none d-md-table-cell">Contact</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($applications as $app)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $app->id }}" class="application-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <span class="fw-semibold">{{ $app->reference_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $app->first_name }} {{ $app->last_name }}</div>
                                                @if($app->suffix)
                                                    <small class="text-muted d-lg-none">{{ $app->suffix }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($app->birthdate)->format('M d, Y') }}</td>
                                    <td class="d-none d-md-table-cell">{{ ucfirst($app->gender) }}</td>
                                    <td class="d-none d-xl-table-cell">{{ ucfirst($app->civil_status) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $app->years_residing }}</span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">{{ ucfirst($app->residency_type) }}</td>
                                    <td class="d-none d-md-table-cell">
                                        <small>{{ $app->contact_number }}</small>
                                    </td>
                                    <td>
                                        @if($app->status == 'approved')
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
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View Button - Always visible -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $app->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Status Change Dropdown - Improved design from Clearance -->
                                            @if(auth('admin')->user()->hasPermission('update_residency') || 
                                                auth('admin')->user()->hasPermission('approve_residency') || 
                                                auth('admin')->user()->hasPermission('reject_residency'))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Change Status">
                                                    <i class="fas fa-tag"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <!-- Pending -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'pending' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Pending?')">
                                                                <i class="fas fa-clock me-2 text-secondary"></i>Pending
                                                                @if($app->status == 'pending')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <!-- Under Review -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="under_review">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'under_review' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Under Review?')">
                                                                <i class="fas fa-search me-2 text-primary"></i>Under Review
                                                                @if($app->status == 'under_review')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <!-- Processing -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="processing">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'processing' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Processing?')">
                                                                <i class="fas fa-spinner me-2 text-warning"></i>Processing
                                                                @if($app->status == 'processing')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <!-- Approved -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'approved' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Approved?')">
                                                                <i class="fas fa-check-circle me-2 text-success"></i>Approved
                                                                @if($app->status == 'approved')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <!-- Ready for Pickup -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready_pickup">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'ready_pickup' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Ready for Pickup?')">
                                                                <i class="fas fa-store me-2 text-info"></i>Ready for Pickup
                                                                @if($app->status == 'ready_pickup')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <!-- Claimed -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="claimed">
                                                            <button type="submit" class="dropdown-item {{ $app->status == 'claimed' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Change status to Claimed?')">
                                                                <i class="fas fa-hand-peace me-2 text-success"></i>Claimed
                                                                @if($app->status == 'claimed')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    
                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <!-- Rejected -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="dropdown-item text-danger {{ $app->status == 'rejected' ? 'active' : '' }}" onclick="return confirmDelete(event, 'Reject this application?')">
                                                                <i class="fas fa-times-circle me-2"></i>Rejected
                                                                @if($app->status == 'rejected')
                                                                    <i class="fas fa-check ms-2 text-success"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif

                                            <!-- Edit Button -->
                                            @if(auth('admin')->user()->hasPermission('update_residency') && 
                                                !in_array($app->status, ['rejected', 'claimed']))
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editApplicationModal{{ $app->id }}" title="Edit Application">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif

                                            <!-- Document Actions Dropdown -->
                                            @if(auth('admin')->user()->hasPermission('generate_residency_document') && 
                                                in_array($app->status, ['approved', 'ready_pickup', 'claimed']))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Document Actions">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form method="GET" action="{{ route('admin.residency.document.residency_only') }}" target="_blank" class="dropdown-item p-0">
                                                            <input type="hidden" name="id" value="{{ $app->id }}">
                                                            <button type="submit" name="action" value="download" class="dropdown-item">
                                                                <i class="fas fa-download me-2"></i>Download Word
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="GET" action="{{ route('admin.residency.document.residency_only') }}" target="_blank" class="dropdown-item p-0">
                                                            <input type="hidden" name="id" value="{{ $app->id }}">
                                                            <button type="submit" name="action" value="print" class="dropdown-item">
                                                                <i class="fas fa-print me-2"></i>Print PDF
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif

                                            <!-- Communication Dropdown -->
                                            @if((auth('admin')->user()->hasPermission('send_email') || auth('admin')->user()->hasPermission('send_sms')) && 
                                                in_array($app->status, ['approved', 'rejected', 'ready_pickup','claimed']))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Send Notification">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if(auth('admin')->user()->hasPermission('send_email'))
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.notifications.sendEmail') }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="email" value="{{ $app->email }}">
                                                            <input type="hidden" name="name" value="{{ $app->first_name }} {{ $app->last_name }}">
                                                            <input type="hidden" name="status" value="{{ $app->status }}">
                                                            <input type="hidden" name="remarks" value="">
                                                            <input type="hidden" name="request_type" value="residency">
                                                            <input type="hidden" name="request_id" value="{{ $app->id }}">
                                                            <input type="hidden" name="reference_number" value="{{ $app->reference_number }}">
                                                            <input type="hidden" name="message" value="Your barangay residency application (Ref: {{ $app->reference_number }}) status: {{ ucfirst(str_replace('_', ' ', $app->status)) }}. Please check the barangay office for updates.">
                                                            <button type="submit" class="dropdown-item" onclick="return prepareRemarksAndConfirm(event, this.form, 'Send email notification to {{ $app->email }}?')">
                                                                <i class="fas fa-envelope me-2"></i>Send Email
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                                    @if(auth('admin')->user()->hasPermission('send_sms'))
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.notifications.sendSMS') }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="phone" value="+63{{ ltrim($app->contact_number, '0') }}">
                                                            <input type="hidden" name="status" value="{{ $app->status }}">
                                                            <input type="hidden" name="remarks" value="">
                                                            <input type="hidden" name="request_type" value="residency">
                                                            <input type="hidden" name="request_id" value="{{ $app->id }}">
                                                            <input type="hidden" name="reference_number" value="{{ $app->reference_number }}">
                                                            <input type="hidden" name="message" value="Barangay update: Your residency application {{ $app->reference_number }} status: {{ ucfirst(str_replace('_', ' ', $app->status)) }}.">
                                                            <button type="submit" class="dropdown-item" onclick="return prepareRemarksAndConfirm(event, this.form, 'Send SMS to {{ $app->contact_number }}?')">
                                                                <i class="fas fa-sms me-2"></i>Send SMS
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @endif

                                            @if(in_array($app->status, ['rejected']) && (auth('admin')->user()->hasPermission('send_email') || auth('admin')->user()->hasPermission('send_sms')))
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="View Remarks History" onclick='viewRemarksHistory("residency", {{ $app->id }}, @js($app->reference_number))'>
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </button>
                                            @endif

                                            <!-- Delete Button -->
                                            @if(auth('admin')->user()->hasPermission('delete_residency'))
                                            <form method="POST" action="{{ route('admin.residency.destroy', $app->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this application permanently?')">
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
                                    <td colspan="11" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-file-alt fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No applications found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            @admin_can('create_residency')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
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
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $applications->firstItem() ?? 0 }}-{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $applications->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Add Application Modal with Validation - Only if user has create_residency permission -->
            @admin_can('create_residency')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-alt me-2"></i>
                                New Residency Application
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.residency.store') }}" enctype="multipart/form-data" id="addApplicationForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Personal Information -->
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Personal Information</h6>
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
                                            <option value="III" {{ (session('form_type') == 'add' && old('suffix') == 'III') ? 'selected' : '' }}>III</option>
                                            <option value="IV" {{ (session('form_type') == 'add' && old('suffix') == 'IV') ? 'selected' : '' }}>IV</option>
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
                                        <label class="form-label">Birth Place</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('birth_place') is-invalid @enderror @endif" 
                                               name="birth_place" value="{{ session('form_type') == 'add' ? old('birth_place') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('birth_place')
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
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('civil_status') is-invalid @enderror @endif" 
                                                name="civil_status" required>
                                            <option value="">Select status</option>
                                            <option value="single" {{ (session('form_type') == 'add' && old('civil_status') == 'single') ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ (session('form_type') == 'add' && old('civil_status') == 'married') ? 'selected' : '' }}>Married</option>
                                            <option value="widowed" {{ (session('form_type') == 'add' && old('civil_status') == 'widowed') ? 'selected' : '' }}>Widowed</option>
                                            <option value="separated" {{ (session('form_type') == 'add' && old('civil_status') == 'separated') ? 'selected' : '' }}>Separated</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('civil_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Contact Information</h6>
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

                                    <!-- Residency Details -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Residency Details</h6>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Years Residing <span class="text-danger">*</span></label>
                                        <select class="form-control @if(session('form_type') == 'add') @error('years_residing') is-invalid @enderror @endif" 
                                               name="years_residing" value="{{ session('form_type') == 'add' ? old('years_residing') : '' }}" placeholder="Years" required>
                                            <option value="">Select length</option>
                                            <option value="less1" {{ (session('form_type') == 'add' && old('years_residing') == 'less1') ? 'selected' : '' }}>Less than 1 year</option>
                                            <option value="1-3" {{ (session('form_type') == 'add' && old('years_residing') == '1-3') ? 'selected' : '' }}>1 - 3 years</option>
                                            <option value="3-5" {{ (session('form_type') == 'add' && old('years_residing') == '3-5') ? 'selected' : '' }}>3 - 5 years</option>
                                            <option value="5-10" {{ (session('form_type') == 'add' && old('years_residing') == '5-10') ? 'selected' : '' }}>5 - 10 years</option>
                                            <option value="10-20" {{ (session('form_type') == 'add' && old('years_residing') == '10-20') ? 'selected' : '' }}>10 - 20 years</option>
                                            <option value="20+" {{ (session('form_type') == 'add' && old('years_residing') == '20+') ? 'selected' : '' }}>More than 20 years</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('years_residing')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Residency Type <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('residency_type') is-invalid @enderror @endif" 
                                                name="residency_type" required>
                                            <option value="">Select status</option>
                                            <option value="owner" {{ (session('form_type') == 'add' && old('residency_type') == 'owner') ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                            <option value="renter" {{ (session('form_type') == 'add' && old('residency_type') == 'renter') ? 'selected' : '' }}>Renter / Tenant</option>
                                            <option value="family" {{ (session('form_type') == 'add' && old('residency_type') == 'family') ? 'selected' : '' }}>Living with Family</option>
                                            <option value="relative" {{ (session('form_type') == 'add' && old('residency_type') == 'relative') ? 'selected' : '' }}>Living with Relative</option>
                                            <option value="boarder" {{ (session('form_type') == 'add' && old('residency_type') == 'boarder') ? 'selected' : '' }}>Boarder / Lodger</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('residency_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Household Members</label>
                                        <input type="number" class="form-control @if(session('form_type') == 'add') @error('household_members') is-invalid @enderror @endif" 
                                               name="household_members" value="{{ session('form_type') == 'add' ? old('household_members') : '' }}" min="1" max="20" placeholder="e.g., 4" required>
                                        @if(session('form_type') == 'add')
                                            @error('household_members')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Purpose -->
                                    <div class="col-12">
                                        <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('purpose') is-invalid @enderror @endif" 
                                                name="purpose" id="purpose" required>
                                            <option value="">Select purpose</option>
                                            <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government Transaction (PSA, DFA, Passport)</option>
                                            <option value="school" {{ (session('form_type') == 'add' && old('purpose') == 'school') ? 'selected' : '' }}>School Enrollment</option>
                                            <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment</option>
                                            <option value="legal" {{ (session('form_type') == 'add' && old('purpose') == 'legal') ? 'selected' : '' }}>Legal/Court Requirement</option>
                                            <option value="bank" {{ (session('form_type') == 'add' && old('purpose') == 'bank') ? 'selected' : '' }}>Bank Transaction</option>
                                            <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship</option>
                                            <option value="pwd" {{ (session('form_type') == 'add' && old('purpose') == 'pwd') ? 'selected' : '' }}>PWD Application</option>
                                            <option value="senior" {{ (session('form_type') == 'add' && old('purpose') == 'senior') ? 'selected' : '' }}>Senior Citizen ID Application</option>
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

                                    <!-- Documents -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Required Documents</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Primary Proof of Residency <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('primary_proof') is-invalid @enderror @endif" 
                                               name="primary_proof" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('primary_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Government ID <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('government_id') is-invalid @enderror @endif" 
                                               name="government_id" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('government_id')
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
                                <button type="submit" form="addApplicationForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Application
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Application Modals with Validation - Only if user has update_residency permission -->
            @if(auth('admin')->user()->hasPermission('update_residency'))
                @foreach($applications as $app)
                <div class="modal fade" id="editApplicationModal{{ $app->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Residency Application - {{ $app->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.residency.update', $app->id) }}" enctype="multipart/form-data" id="editApplicationForm{{ $app->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $app->id }}">

                                    <div class="row g-3">
                                        <!-- Personal Information -->
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
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
                                                <option value="III" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
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
                                            <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('birth_place') is-invalid @enderror @endif" 
                                                name="birth_place" id="edit_birth_place_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('birth_place', $app->birth_place) : $app->birth_place }}" 
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birth_place')
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
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('civil_status') is-invalid @enderror @endif" 
                                                    name="civil_status" id="edit_civil_status_{{ $app->id }}" required>
                                                <option value="">Select status</option>
                                                <option value="single" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="widowed" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('civil_status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Contact Information -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Contact Information</h6>
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

                                        <!-- Residency Details -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Residency Details</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Years Residing <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('years_residing') is-invalid @enderror @endif" 
                                                name="years_residing" id="edit_years_residing_{{ $app->id }}" required>
                                                <option value="">Select length</option>
                                                <option value="less1" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == 'less1' ? 'selected' : '' }}>Less than 1 year</option>
                                                <option value="1-3" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '1-3' ? 'selected' : '' }}>1 - 3 years</option>
                                                <option value="3-5" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '3-5' ? 'selected' : '' }}>3 - 5 years</option>
                                                <option value="5-10" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '5-10' ? 'selected' : '' }}>5 - 10 years</option>
                                                <option value="10-20" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '10-20' ? 'selected' : '' }}>10 - 20 years</option>
                                                <option value="20+" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '20+' ? 'selected' : '' }}>More than 20 years</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('years_residing')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Residency Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('residency_type') is-invalid @enderror @endif" 
                                                    name="residency_type" id="edit_residency_type_{{ $app->id }}" required>
                                                <option value="">Select type</option>
                                                <option value="owner" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'owner' ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                                <option value="renter" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'renter' ? 'selected' : '' }}>Renter / Tenant</option>
                                                <option value="family" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'family' ? 'selected' : '' }}>Living with Family</option>
                                                <option value="relative" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'relative' ? 'selected' : '' }}>Living with Relative</option>
                                                <option value="boarder" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'boarder' ? 'selected' : '' }}>Boarder / Lodger</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('residency_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Household Members <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('household_members') is-invalid @enderror @endif" 
                                                name="household_members" id="edit_household_members_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('household_members', $app->household_members) : $app->household_members }}" 
                                                min="1" max="20" placeholder="e.g., 4" required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('household_members')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Purpose -->
                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('purpose') is-invalid @enderror @endif" 
                                                    name="purpose" id="edit_purpose_{{ $app->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="government" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'government' ? 'selected' : '' }}>Government Transaction (PSA, DFA, Passport)</option>
                                                <option value="school" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'school' ? 'selected' : '' }}>School Enrollment</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'employment' ? 'selected' : '' }}>Employment</option>
                                                <option value="legal" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'legal' ? 'selected' : '' }}>Legal/Court Requirement</option>
                                                <option value="bank" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                <option value="pwd" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'pwd' ? 'selected' : '' }}>PWD Application</option>
                                                <option value="senior" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'senior' ? 'selected' : '' }}>Senior Citizen ID Application</option>
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

                                        <!-- Documents -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current files)</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Primary Proof of Residency</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('primary_proof') is-invalid @enderror @endif" 
                                                name="primary_proof" id="edit_primary_proof_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('primary_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Government ID</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('government_id') is-invalid @enderror @endif" 
                                                name="government_id" id="edit_government_id_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('government_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        
                                        @if($app->primary_proof)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current primary proof: {{ basename($app->primary_proof) }}
                                            </small>
                                        </div>
                                        @endif
                                        
                                        @if($app->government_id)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current government ID: {{ basename($app->government_id) }}
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editApplicationForm{{ $app->id }}" class="btn btn-primary" id="submitEditForm{{ $app->id }}">
                                        <i class="fas fa-save me-2"></i>Update Application
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($applications as $app)
            <div class="modal fade" id="viewModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-alt me-2"></i>
                                Application Details - {{ $app->reference_number }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="fw-semibold text-primary">Personal Information</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Full Name</label>
                                    <p class="fw-semibold">{{ $app->first_name }} {{ $app->middle_name }} {{ $app->last_name }} {{ $app->suffix }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Birthdate</label>
                                    <p>{{ \Carbon\Carbon::parse($app->birthdate)->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Birth Place</label>
                                    <p>{{ $app->birth_place ?: 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Gender / Civil Status</label>
                                    <p>{{ ucfirst($app->gender) }} / {{ ucfirst($app->civil_status) }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary">Contact Information</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Email</label>
                                    <p>{{ $app->email ?: 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Number</label>
                                    <p>{{ $app->contact_number ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Address</label>
                                    <p>{{ $app->address ?: 'N/A' }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary">Residency Details</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Years Residing</label>
                                    <p>{{ $app->years_residing ?: 'N/A' }} years</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Residency Type</label>
                                    <p>{{ ucfirst($app->residency_type) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Household Members</label>
                                    <p>{{ $app->household_members ?: 'N/A' }}</p>
                                </div>
                                <!-- Proof Image -->
                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary">Proof of Residency</h6>

                                    @if($app->primary_proof)
                                        <div class="mt-2">
                                            <img src="{{ asset($app->primary_proof) }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height:300px; cursor:pointer;"
                                                onclick="openZoomModal('{{ asset($app->primary_proof) }}')">
                                        </div>
                                    @else
                                        <p class="text-muted">No proof uploaded</p>
                                    @endif
                                </div>

                                <!-- Valid ID Image -->
                                <div class="col-12 mt-3">
                                    <h6 class="fw-semibold text-primary">Valid ID</h6>

                                    @if($app->government_id)
                                        <div class="mt-2">
                                            <img src="{{ asset($app->government_id) }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height:300px; cursor:pointer;"
                                                onclick="openZoomModal('{{ asset($app->government_id) }}')">
                                        </div>
                                    @else
                                        <p class="text-muted">No valid ID uploaded</p>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Purpose</label>
                                    <p>{{ $app->purpose }} {{ $app->purpose_other ? '(' . $app->purpose_other . ')' : '' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($app->status == 'approved')
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
                                    </p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Submitted At</label>
                                    <p>{{ $app->created_at ? $app->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
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
                        var editModal = new bootstrap.Modal(document.getElementById('editApplicationModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk delete function
        function bulkDelete() {

            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one application to delete.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Delete',
                text: `Are you sure you want to delete ${checkboxes.length} selected application(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete'
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

            // If nothing selected â†’ Ask to export all
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

            // If selected â†’ Confirm export selected
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

        // Confirm action with SweetAlert when available, fallback to native confirm.
        function confirmDelete(event, message) {
            const text = message || 'You won\'t be able to revert this!';

            if (event && typeof event.preventDefault === 'function') {
                event.preventDefault();
            }

            const form = event && event.target && typeof event.target.closest === 'function'
                ? event.target.closest('form')
                : null;

            if (typeof Swal === 'undefined') {
                return window.confirm(text);
            }

            // If no form context exists, fallback to native confirm to preserve behavior.
            if (!form) {
                return window.confirm(text);
            }

            Swal.fire({
                title: 'Are you sure?',
                text: text,
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

            // Re-open add modal for non-validation errors from controller try/catch.
            @if (session('error') && session('form_type') == 'add')
                var addModalOnError = new bootstrap.Modal(document.getElementById('addApplicationModal'));
                addModalOnError.show();
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
                        else if (this.value.length !== 11) {
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

                // Years residing validation
                const years = document.querySelector('input[name="years_residing"]');
                if (years) {
                    years.addEventListener('input', function() {
                        const val = parseInt(this.value);
                        if (val < 0) {
                            this.setCustomValidity('Years cannot be negative');
                            this.classList.add('is-invalid');
                        } else if (val > 120) {
                            this.setCustomValidity('Years cannot exceed 120');
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
        @if(auth('admin')->user()->hasPermission('update_residency'))
            @foreach($applications as $app)
            (function(editId) {
                const editForm = document.getElementById('editApplicationForm' + editId);
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

                    // Household members validation
                    const household = document.getElementById('edit_household_members_' + editId);
                    if (household) {
                        household.addEventListener('input', function() {
                            const val = parseInt(this.value);
                            if (val < 1) {
                                this.setCustomValidity('Household members must be at least 1');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Household members must be at least 1';
                            } else if (val > 20) {
                                this.setCustomValidity('Household members cannot exceed 20');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Household members cannot exceed 20';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // File size validation
                    const primaryProof = document.getElementById('edit_primary_proof_' + editId);
                    if (primaryProof) {
                        primaryProof.addEventListener('change', function() {
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

                    const govId = document.getElementById('edit_government_id_' + editId);
                    if (govId) {
                        govId.addEventListener('change', function() {
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
        document.addEventListener('DOMContentLoaded', function() {
            var tableResponsive = document.querySelector('.table-responsive');
            if (!tableResponsive) return;

            // Initialize all dropdown toggles inside the table with Popper fixed strategy
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
                if (remarks.trim() === '') {
                    event.preventDefault();
                    alert('Remarks are required for rejected or dropped notifications.');
                    return false;
                }
                remarksInput.value = remarks.trim();
            }

            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
    
</body>
</html>

