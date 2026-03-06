<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Indigency Applications - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .badge.bg-danger-subtle {
            background: var(--primary-light) !important;
            color: var(--primary);
        }

        .badge.bg-warning-subtle {
            background: var(--warning-light) !important;
            color: var(--warning);
        }

        .income-badge {
            background: var(--info-light);
            color: var(--info);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
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
            <a href="{{ route('admin.residency.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-alt"></i> <span>Residency Applications</span></a>
            @endadmin_can
            
            @admin_can('view_indigency')
            <a href="{{ route('admin.indigency.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-file-invoice"></i> <span>Indigency</span></a>
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
            <span>Users</span>
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
                    <i class="fas fa-file-invoice me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Indigency Applications
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
                            <i class="fas fa-file-invoice"></i>
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
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.indigency.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Applications
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_indigency')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Indigency Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.indigency.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="monthly_income" class="form-select">
                                    <option value="">All Income</option>
                                    <option value="below 5k" {{ request('monthly_income') == 'below 5k' ? 'selected' : '' }}>Below ₱5,000</option>
                                    <option value="5k-8k" {{ request('monthly_income') == '5k-8k' ? 'selected' : '' }}>₱5,000 – ₱8,000</option>
                                    <option value="8k-10k" {{ request('monthly_income') == '8k-10k' ? 'selected' : '' }}>₱8,001 – ₱10,000</option>
                                    <option value="10k-15k" {{ request('monthly_income') == '10k-15k' ? 'selected' : '' }}>₱10,001 – ₱15,000</option>
                                    <option value="no income" {{ request('monthly_income') == 'no income' ? 'selected' : '' }}>No fixed income</option>
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
                    @if(auth('admin')->user()->hasAnyPermission(['delete_indigency', 'export_indigency']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_indigency')
                        <form id="bulkForm" method="POST" action="{{ route('admin.indigency.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Delete</span>
                            </button>
                        </form>
                        @endadmin_can
                        
                        @admin_can('export_indigency')
                        <form id="exportForm" method="POST" action="{{ route('admin.indigency.export') }}" style="display: inline;">
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

            <!-- Indigency Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="indigencyTable">
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
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'monthly_income', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Monthly Income
                                            @if(request('sort') == 'monthly_income')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'household_members', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Household
                                            @if(request('sort') == 'household_members')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($indigency as $ind)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $ind->id }}" class="application-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <span class="fw-semibold">{{ $ind->reference_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $ind->first_name }} {{ $ind->last_name }}</div>
                                                @if($ind->suffix)
                                                    <small class="text-muted d-lg-none">{{ $ind->suffix }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($ind->birthdate)->format('M d, Y') }}</td>
                                    <td class="d-none d-md-table-cell">{{ ucfirst($ind->gender) }}</td>
                                    <td>
                                        <span class="income-badge">{{ $ind->monthly_income }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="badge bg-light text-dark">{{ $ind->household_members }}</span>
                                    </td>
                                    <td>
                                        @if($ind->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($ind->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_indigency can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $ind->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (only for processing and if user has update_indigency permission) -->
                                            @if($ind->status == 'processing' && auth('admin')->user()->hasPermission('update_indigency'))
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editIndigencyModal{{ $ind->id }}" title="Edit Application">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif

                                            <!-- Document Actions Dropdown (only for approved and if user has generate_indigency_document permission) -->
                                            @if($ind->status == 'approved' && auth('admin')->user()->hasPermission('generate_indigency_document'))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Document Actions">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form method="GET" action="{{ route('admin.indigency.document') }}" target="_blank" class="dropdown-item p-0">
                                                            <input type="hidden" name="id" value="{{ $ind->id }}">
                                                            <button type="submit" name="action" value="download" class="dropdown-item">
                                                                <i class="fas fa-download me-2"></i>Download Word
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="GET" action="{{ route('admin.indigency.document') }}" target="_blank" class="dropdown-item p-0">
                                                            <input type="hidden" name="id" value="{{ $ind->id }}">
                                                            <button type="submit" name="action" value="print" class="dropdown-item">
                                                                <i class="fas fa-print me-2"></i>Print PDF
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif

                                            <!-- Communication Dropdown (only for approved/rejected and if user has notification permissions) -->
                                            @if(($ind->status == 'approved' || $ind->status == 'rejected') && 
                                                (auth('admin')->user()->hasPermission('send_email') || auth('admin')->user()->hasPermission('send_sms')))
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Send Notification">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if(auth('admin')->user()->hasPermission('send_email'))
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.notifications.sendEmail') }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="email" value="{{ $ind->email }}">
                                                            <input type="hidden" name="name" value="{{ $ind->first_name }} {{ $ind->last_name }}">
                                                            @if($ind->status == 'approved')
                                                            <input type="hidden" name="message" value="Your indigency application (Ref: {{ $ind->reference_number }}) has been APPROVED. You may claim your certificate at the barangay hall.">
                                                            @elseif($ind->status == 'rejected')
                                                            <input type="hidden" name="message" value="Your indigency application (Ref: {{ $ind->reference_number }}) has been REJECTED. You may check your request at the website.">
                                                            @endif
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Send approval email to {{ $ind->email }}?')">
                                                                <i class="fas fa-envelope me-2"></i>Send Email
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                                    @if(auth('admin')->user()->hasPermission('send_sms'))
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.notifications.sendSMS') }}" class="dropdown-item p-0">
                                                            @csrf
                                                            <input type="hidden" name="phone" value="+63{{ ltrim($ind->contact_number, '0') }}">
                                                            <input type="hidden" name="message" value="Good news! Your indigency application {{ $ind->reference_number }} has been APPROVED. Please visit the barangay hall to claim your certificate.">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Send approval SMS to {{ $ind->contact_number }}?')">
                                                                <i class="fas fa-sms me-2"></i>Send SMS
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @endif

                                            <!-- Approve (only for processing and if user has approve_indigency permission) -->
                                            @if($ind->status == 'processing' && auth('admin')->user()->hasPermission('approve_indigency'))
                                            <form method="POST" action="{{ route('admin.indigency.approve', $ind->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Approve this application?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Reject (only for processing and if user has reject_indigency permission) -->
                                            @if($ind->status == 'processing' && auth('admin')->user()->hasPermission('reject_indigency'))
                                            <form method="POST" action="{{ route('admin.indigency.reject', $ind->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Reject this application?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Delete (always available if user has delete_indigency permission) -->
                                            @if(auth('admin')->user()->hasPermission('delete_indigency'))
                                            <form method="POST" action="{{ route('admin.indigency.destroy', $ind->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this application permanently?')">
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
                                    <td colspan="9" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-file-invoice fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No applications found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            @admin_can('create_indigency')
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
                    @if(isset($indigency) && $indigency->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $indigency->firstItem() ?? 0 }}-{{ $indigency->lastItem() ?? 0 }} of {{ $indigency->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $indigency->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Application Modal with Validation - Only if user has create_indigency permission -->
            @admin_can('create_indigency')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-invoice me-2"></i>
                                New Indigency Application
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.indigency.store') }}" enctype="multipart/form-data" id="addApplicationForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                            <div class="modal-body">
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

                                    <!-- Indigency Details -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Indigency Details</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                        <select class="form-control @if(session('form_type') == 'add') @error('monthly_income') is-invalid @enderror @endif" 
                                               name="monthly_income" value="{{ session('form_type') == 'add' ? old('monthly_income') : '' }}" required>
                                               <option value="">Select income bracket</option>
                                                <option value="below 5k" {{ (session('form_type') == 'add' && old('monthly_income') == 'below 5k') ? 'selected' : '' }}>Below ₱5,000</option>
                                                <option value="5k-8k" {{ (session('form_type') == 'add' && old('monthly_income') == '5k-8k') ? 'selected' : '' }}>₱5,000 – ₱8,000</option>
                                                <option value="8k-10k" {{ (session('form_type') == 'add' && old('monthly_income') == '8k-10k') ? 'selected' : '' }}>₱8,001 – ₱10,000</option>
                                                <option value="10k-15k" {{ (session('form_type') == 'add' && old('monthly_income') == '10k-15k') ? 'selected' : '' }}>₱10,001 – ₱15,000</option>
                                                <option value="no income" {{ (session('form_type') == 'add' && old('monthly_income') == 'no income') ? 'selected' : '' }}>No fixed income</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('monthly_income')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Household Members <span class="text-danger">*</span></label>
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
                                            <option value="medical" {{ (session('form_type') == 'add' && old('purpose') == 'medical') ? 'selected' : '' }}>Medical / Hospital assistance</option>
                                            <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship / Financial aid</option>
                                            <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government program (4Ps, AICS)</option>
                                            <option value="legal" {{ (session('form_type') == 'add' && old('purpose') == 'legal') ? 'selected' : '' }}>Legal assistance (PAO, etc.)</option>
                                            <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment requirement (low income proof)</option>
                                            <option value="burial" {{ (session('form_type') == 'add' && old('purpose') == 'burial') ? 'selected' : '' }}>Burial assistance</option>
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

            <!-- Edit Application Modals with Validation - Only if user has update_indigency permission -->
            @if(auth('admin')->user()->hasPermission('update_indigency'))
                @foreach($indigency as $ind)
                <div class="modal fade" id="editIndigencyModal{{ $ind->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Indigency Application - {{ $ind->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.indigency.update', $ind->id) }}" enctype="multipart/form-data" id="editIndigencyForm{{ $ind->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $ind->id }}">

                                <div class="modal-body">
                                    <div class="row g-3">
                                        <!-- Personal Information -->
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('first_name') is-invalid @enderror @endif" 
                                                name="first_name" id="edit_first_name_{{ $ind->id }}" 
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('first_name', $ind->first_name) : $ind->first_name }}" 
                                                required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('middle_name') is-invalid @enderror @endif" 
                                                name="middle_name" id="edit_middle_name_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('middle_name', $ind->middle_name) : $ind->middle_name }}">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('last_name') is-invalid @enderror @endif" 
                                                name="last_name" id="edit_last_name_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('last_name', $ind->last_name) : $ind->last_name }}" 
                                                required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('suffix') is-invalid @enderror @endif" 
                                                    name="suffix" id="edit_suffix_{{ $ind->id }}">
                                                <option value="">None</option>
                                                <option value="Jr." {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                <option value="Sr." {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                <option value="III" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('birthdate') is-invalid @enderror @endif" 
                                                name="birthdate" id="edit_birthdate_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('birthdate', $ind->birthdate) : $ind->birthdate }}" 
                                                required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('gender') is-invalid @enderror @endif" 
                                                    name="gender" id="edit_gender_{{ $ind->id }}" required>
                                                <option value="">Select gender</option>
                                                <option value="male" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('gender')
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
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('email') is-invalid @enderror @endif" 
                                                name="email" id="edit_email_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('email', $ind->email) : $ind->email }}" 
                                                placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('contact_number') is-invalid @enderror @endif" 
                                                name="contact_number" id="edit_contact_number_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('contact_number', $ind->contact_number) : $ind->contact_number }}" 
                                                placeholder="09XXXXXXXXX" maxlength="11" required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('contact_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('address') is-invalid @enderror @endif" 
                                                    name="address" id="edit_address_{{ $ind->id }}"
                                                    rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $ind->id ? old('address', $ind->address) : $ind->address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Indigency Details -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Indigency Details</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                            <select class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('monthly_income') is-invalid @enderror @endif" 
                                                name="monthly_income" id="edit_monthly_income_{{ $ind->id }}" required>
                                                <option value="">Select income bracket</option>
                                                <option value="below 5k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == 'below 5k' ? 'selected' : '' }}>Below ₱5,000</option>
                                                <option value="5k-8k" {{ (session('form_type') == 'edit_'. $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '5k-8k' ? 'selected' : '' }}>₱5,000 – ₱8,000</option>
                                                <option value="8k-10k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '8k-10k' ? 'selected' : '' }}>₱8,001 – ₱10,000</option>
                                                <option value="10k-15k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '10k-15k' ? 'selected' : '' }}>₱10,001 – ₱15,000</option>
                                                <option value="no income" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income)  == 'no income' ? 'selected' : '' }}>No fixed income</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('monthly_income')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Household Members <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('household_members') is-invalid @enderror @endif" 
                                                name="household_members" id="edit_household_members_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('household_members', $ind->household_members) : $ind->household_members }}" 
                                                min="1" max="20" required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('household_members')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Purpose -->
                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('purpose') is-invalid @enderror @endif" 
                                                    name="purpose" id="edit_purpose_{{ $ind->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="medical" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'medical' ? 'selected' : '' }}>Medical Assistance</option>
                                                <option value="financial" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'financial' ? 'selected' : '' }}>Financial Assistance</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'employment' ? 'selected' : '' }}>Employment</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('purpose')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12" id="edit_otherPurposeField_{{ $ind->id }}" style="{{ (session('form_type') == 'edit_' . $ind->id && old('purpose', $ind->purpose) == 'other') ? 'display:block;' : 'display:none;' }}">
                                            <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('purpose_other') is-invalid @enderror @endif" 
                                                name="purpose_other" id="edit_purpose_other_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('purpose_other', $ind->purpose_other) : $ind->purpose_other }}" 
                                                placeholder="Please specify">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('purpose_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Documents -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current file)</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('valid_id_path') is-invalid @enderror @endif" 
                                                name="valid_id_path" id="edit_valid_id_path_{{ $ind->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('valid_id_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        
                                        @if($ind->valid_id_path)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current file: {{ basename($ind->valid_id_path) }}
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitEditForm{{ $ind->id }}">
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
            @foreach($indigency as $ind)
            <div class="modal fade" id="viewModal{{ $ind->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-invoice me-2"></i>
                                Indigency Details - {{ $ind->reference_number }}
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
                                    <p class="fw-semibold">{{ $ind->first_name }} {{ $ind->middle_name }} {{ $ind->last_name }} {{ $ind->suffix }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Birthdate</label>
                                    <p>{{ \Carbon\Carbon::parse($ind->birthdate)->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Gender</label>
                                    <p>{{ ucfirst($ind->gender) }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary">Contact Information</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Email</label>
                                    <p>{{ $ind->email ?: 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Contact Number</label>
                                    <p>{{ $ind->contact_number ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Address</label>
                                    <p>{{ $ind->address ?: 'N/A' }}</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary">Indigency Details</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Monthly Income</label>
                                    <p class="fw-semibold text-info">{{ $ind->monthly_income }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Household Members</label>
                                    <p>{{ $ind->household_members ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Purpose</label>
                                    <p>{{ $ind->purpose }} {{ $ind->purpose_other ? '(' . $ind->purpose_other . ')' : '' }}</p>
                                </div>
                                @if($ind->valid_id_path)
                                <div class="col-12">
                                    <label class="form-label text-muted">Valid ID</label>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openZoomModal('{{ asset($ind->valid_id_path) }}')">
                                            <i class="fas fa-id-card me-2"></i>View ID Document
                                        </button>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($ind->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($ind->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Submitted At</label>
                                    <p>{{ $ind->created_at ? $ind->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
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
                        var editModal = new bootstrap.Modal(document.getElementById('editIndigencyModal{{ $editId }}'));
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

                // Monthly income validation
                const income = document.querySelector('input[name="monthly_income"]');
                if (income) {
                    income.addEventListener('input', function() {
                        const val = parseFloat(this.value);
                        if (val < 0) {
                            this.setCustomValidity('Monthly income cannot be negative');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // Household members validation
                const household = document.querySelector('input[name="household_members"]');
                if (household) {
                    household.addEventListener('input', function() {
                        const val = parseInt(this.value);
                        if (val < 1) {
                            this.setCustomValidity('Household members must be at least 1');
                            this.classList.add('is-invalid');
                        } else if (val > 20) {
                            this.setCustomValidity('Household members cannot exceed 20');
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
        @if(auth('admin')->user()->hasPermission('update_indigency'))
            @foreach($indigency as $ind)
            (function(editId) {
                const editForm = document.getElementById('editIndigencyForm' + editId);
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

                    // Monthly income validation
                    const income = document.getElementById('edit_monthly_income_' + editId);
                    if (income) {
                        income.addEventListener('input', function() {
                            const val = parseFloat(this.value);
                            if (val < 0) {
                                this.setCustomValidity('Monthly income cannot be negative');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Monthly income cannot be negative';
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
            })('{{ $ind->id }}');
            @endforeach
        @endif
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>