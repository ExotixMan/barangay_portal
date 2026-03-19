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
            min-width: 1200px;
        }

        @media (max-width: 768px) {
            .table {
                min-width: 1000px;
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
                    <i class="fas fa-bullhorn me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Announcements
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

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.announcements.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Announcements
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_announcements')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Announcement</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by title, content..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="important" {{ request('category') == 'important' ? 'selected' : '' }}>Important</option>
                                    <option value="events" {{ request('category') == 'events' ? 'selected' : '' }}>Events</option>
                                    <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>Health</option>
                                    <option value="services" {{ request('category') == 'services' ? 'selected' : '' }}>Services</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <select name="featured" class="form-select">
                                    <option value="">Featured</option>
                                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        @if(auth('admin')->user()->hasPermission('delete_announcements'))
                        <div class="mt-3 d-flex gap-2 justify-content-end">
                            <form id="bulkForm" method="POST" action="{{ route('admin.announcements.bulkDelete') }}" style="display: inline;">
                                @csrf
                                <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="d-none d-sm-inline">Bulk Delete</span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Announcements Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="announcementsTable">
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
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Category
                                            @if(request('sort') == 'category')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">Featured</th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
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
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                @if($ann->image)
                                                    <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
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
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        {{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') : '—' }}
                                    </td>
                                    <td>
                                        @if($ann->status == 'published')
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
                                            @admin_can('update_announcements')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal{{ $ann->id }}" title="Edit Announcement">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can

                                            <!-- Feature/Unfeature (requires feature_announcements permission) -->
                                            @if($ann->status == 'published' && auth('admin')->user()->hasPermission('feature_announcements'))
                                                <form method="POST" action="{{ route('admin.announcements.toggle-feature', $ann->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $ann->is_featured ? 'Remove Featured' : 'Mark as Featured' }}" onclick="return confirm('{{ $ann->is_featured ? 'Remove featured status?' : 'Mark as featured?' }}')">
                                                        <i class="fas fa-star{{ $ann->is_featured ? '' : '-o' }}"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Delete (requires delete_announcements permission) -->
                                            @admin_can('delete_announcements')
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this announcement permanently?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-bullhorn fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No announcements found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            @admin_can('create_announcements')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
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
                                <i class="fas fa-bullhorn me-2"></i>
                                New Announcement
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
                                        <div class="form-check mt-2">
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
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Announcement
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
                                            <div class="form-check mt-2">
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
                                                    <img src="{{ asset($ann->image) }}" alt="Current image" class="image-preview">
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
                                <i class="fas fa-bullhorn me-2"></i>
                                Announcement Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                @if($ann->image)
                                <div class="col-12 text-center">
                                    <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="img-fluid rounded" style="max-height: 300px;">
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
                                    <p><span class="badge bg-light text-dark">{{ number_format($ann->views) }}</span></p>
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
                                    <div class="p-3 bg-light rounded">
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
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk delete function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.announcement-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one announcement to delete.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one announcement to delete.');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirm Bulk Delete',
                    text: `Are you sure you want to delete ${checkboxes.length} selected announcement(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
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
            } else {
                if (confirm(`Are you sure you want to delete ${checkboxes.length} announcement(s)?`)) {
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            bulkForm.appendChild(input);
                        });
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
            zoomImage.src = imageUrl;
            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
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
        }
        function cf(el) { if (el) el.classList.remove('is-invalid'); }

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
    
</body>
</html>