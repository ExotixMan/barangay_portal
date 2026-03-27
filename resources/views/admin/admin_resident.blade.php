<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Residents - Admin</title>
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
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

        /* Table Styling - Mobile Optimized */
        .table-responsive {
            border-radius: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            min-width: 800px;
        }

        @media (max-width: 768px) {
            .table {
                min-width: 700px;
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
        }

        .modal-body {
            padding: 1.5rem;
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

        @media (max-width: 768px) {     
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
            <a href="{{ route('admin.residents.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
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
        <a href="{{ route('admin.users.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-user"></i>
            <span>Users & Roles</span>
        </a>
        @endadmin_can

        @admin_can('view_content')
        <a href="{{ route('admin.chatbot.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
        @endadmin_can

        @admin_can('view_users')
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
                    <i class="fas fa-users me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Residents
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

        <!-- Content Area -->
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
                            <div class="stat-number">{{ number_format($totalResidents) }}</div>
                            <small class="text-success mt-2 d-none d-sm-block
                                {{ $growthPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas 
                                    {{ $growthPercentage >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} 
                                    me-1"></i> {{ $growthPercentage >= 0 ? '+' : '' }}{{ round($growthPercentage, 1) }}% from last month
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
                            <div class="stat-label text-muted mb-1">Active</div>
                            <div class="stat-number">{{ number_format($activeResidents)}}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>{{ round($activePercentage, 1)}}% active
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
                            <div class="stat-label text-muted mb-1">New</div>
                            <div class="stat-number">{{ number_format($newResidents)}}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-calendar me-1"></i>+{{ number_format($yesterdayCount)}} from yesterday
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Senior</div>
                            <div class="stat-number">{{ number_format($seniorResidents)}}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-heart me-1"></i>{{ round($seniorPercentage, 1)}}% of total
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.residents.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Residents
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_residents')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Resident</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search residents..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="age_range" class="form-select">
                                    <option value="">All Ages</option>
                                    <option value="minor" {{ request('age_range') == 'minor' ? 'selected' : '' }}>Minor (&lt;18)</option>
                                    <option value="adult" {{ request('age_range') == 'adult' ? 'selected' : '' }}>Adult (18-59)</option>
                                    <option value="senior" {{ request('age_range') == 'senior' ? 'selected' : '' }}>Senior (60+)</option>
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
                    @if(auth('admin')->user()->hasAnyPermission(['delete_residents', 'bulk_delete_residents', 'export_residents']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @if(auth('admin')->user()->hasAnyPermission(['delete_residents', 'bulk_delete_residents']))
                        <form id="bulkForm" method="POST" action="{{ route('admin.residents.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Delete</span>
                            </button>
                        </form>
                        @endif

                        @admin_can('export_residents')
                        <form id="exportForm" method="POST" action="{{ route('admin.residents.export') }}" style="display: inline;">
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

            <!-- Residents Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="residentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'firstname', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Resident
                                            @if(request('sort') == 'firstname')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'username', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Username
                                            @if(request('sort') == 'username')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Address</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'age', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Age
                                            @if(request('sort') == 'age')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="d-none d-md-table-cell">Contact</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($residents as $resident)
                                <tr class="{{ $resident->deleted_at ? 'table-danger' : '' }}">
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $resident->id }}" class="resident-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            @if($resident->profile_photo)
                                                <img src="{{ asset($resident->profile_photo) }}" alt="Photo"
                                                     class="rounded-circle me-2 object-fit-cover"
                                                     style="width:36px;height:36px;border:2px solid #e9ecef;"
                                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                <div class="bg-light rounded-circle d-none align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $resident->full_name }}</div>
                                                <small class="text-muted d-lg-none">ID: #{{ $resident->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $resident->username }}</td>
                                    <td class="d-none d-lg-table-cell">
                                        <i class="fas fa-map-marker-alt text-muted me-1" style="font-size: 0.8rem;"></i>
                                        {{ Str::limit($resident->address, 20) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $resident->age }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <small>{{ Str::limit($resident->email, 15) }}</small>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <small>{{ $resident->contact }}</small>
                                    </td>
                                    <td>
                                        @if($resident->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @endif
                                        @if($resident->valid_id)
                                            @if($resident->valid_id_verified)
                                                <span class="badge mt-1 d-block" style="background:#e8f5e8;color:#2e7d32;font-size:0.7rem;"><i class="fas fa-id-card me-1"></i>ID Verified</span>
                                            @else
                                                <span class="badge mt-1 d-block" style="background:#fff4e5;color:#ed6c02;font-size:0.7rem;"><i class="fas fa-id-card me-1"></i>ID Pending</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_residents can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewResidentModal{{ $resident->id }}" title="View">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>

                                            <!-- Edit (only for non-deleted and if user has update_residents permission) -->
                                            @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('update_residents'))
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editResidentModal{{ $resident->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif

                                            <!-- Activity (always visible) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#activityModal{{ $resident->id }}" title="Activity">
                                                <i class="fas fa-history"></i>
                                            </button>

                                            <!-- Delete (for non-deleted and if user has delete_residents permission) -->
                                            @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('delete_residents'))
                                                <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Are you sure you want to delete this resident?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Restore (for deleted and if user has restore_residents permission) -->
                                            @if($resident->deleted_at && auth('admin')->user()->hasPermission('restore_residents'))
                                                <form method="POST" action="{{ route('admin.residents.restore', $resident->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this resident?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                        <i class="fas fa-undo"></i>
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
                                            <i class="fas fa-users fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No residents found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            @admin_can('create_residents')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addResidentModal">
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
                            {{ $residents->firstItem() ?? 0 }}-{{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $residents->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Add Resident Modal with Validation - Only if user has create_residents permission -->
            @admin_can('create_residents')
            <div class="modal fade" id="addResidentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-plus me-2"></i>
                                Add New Resident
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="{{ route('admin.residents.store') }}" id="addResidentForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="add_firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('firstname') is-invalid @enderror @endif" 
                                               name="firstname" id="add_firstname" 
                                               value="{{ session('form_type') == 'add' ? old('firstname') : '' }}" 
                                               required placeholder="Enter first name">
                                        @if(session('form_type') == 'add')
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_middlename" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('middlename') is-invalid @enderror @endif" 
                                               name="middlename" id="add_middlename" 
                                               value="{{ session('form_type') == 'add' ? old('middlename') : '' }}" 
                                               placeholder="Enter middle name (optional)">
                                        @if(session('form_type') == 'add')
                                            @error('middlename')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('lastname') is-invalid @enderror @endif" 
                                               name="lastname" id="add_lastname" 
                                               value="{{ session('form_type') == 'add' ? old('lastname') : '' }}" 
                                               required placeholder="Enter last name">
                                        @if(session('form_type') == 'add')
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('suffix') is-invalid @enderror @endif" 
                                               name="suffix" id="add_suffix" 
                                               value="{{ session('form_type') == 'add' ? old('suffix') : '' }}" 
                                               placeholder="e.g., Jr., Sr., III (optional)">
                                        @if(session('form_type') == 'add')
                                            @error('suffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('username') is-invalid @enderror @endif" 
                                               name="username" id="add_username" 
                                               value="{{ session('form_type') == 'add' ? old('username') : '' }}" 
                                               required placeholder="Choose a username">
                                        @if(session('form_type') == 'add')
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('email') is-invalid @enderror @endif" 
                                               name="email" id="add_email" 
                                               value="{{ session('form_type') == 'add' ? old('email') : '' }}" 
                                               required placeholder="email@example.com">
                                        @if(session('form_type') == 'add')
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('contact') is-invalid @enderror @endif" 
                                               name="contact" id="add_contact" 
                                               value="{{ session('form_type') == 'add' ? old('contact') : '' }}" 
                                               required placeholder="09XXXXXXXXX" maxlength="11">
                                        <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                        @if(session('form_type') == 'add')
                                            @error('contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" 
                                               name="birthdate" id="add_birthdate" 
                                               value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" 
                                               required max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                        <small class="text-muted">Resident must be 18 years old or above.</small>
                                        @if(session('form_type') == 'add')
                                            @error('birthdate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label for="add_address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('address') is-invalid @enderror @endif" 
                                                  name="address" id="add_address" 
                                                  rows="2" required placeholder="House/Block/Lot No., Street, Subdivision, Barangay">{{ session('form_type') == 'add' ? old('address') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @if(session('form_type') == 'add') @error('password') is-invalid @enderror @endif" 
                                               name="password" id="add_password" 
                                               required minlength="8"
                                               onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                        <small class="text-muted">Minimum 8 characters with uppercase, lowercase, number, and special character. <strong>No spaces allowed.</strong></small>
                                        @if(session('form_type') == 'add')
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="add_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" 
                                               id="add_password_confirmation" required
                                               onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                        <div class="invalid-feedback" id="add_pwc_feedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" id="toggle_add_passwords">
                                            <label class="form-check-label" for="toggle_add_passwords">
                                                Show password and confirm password
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        </form>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addResidentForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Resident
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Activity Log Modals (always visible) -->
            @foreach($residents as $resident)
            <div class="modal fade" id="activityModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title small">
                                <i class="fas fa-history me-2"></i>
                                Activity Log - {{ $resident->full_name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            @forelse($resident->activities as $activity)
                                <div class="activity-item">
                                    <strong class="small">{{ $activity->action }}</strong>
                                    <div class="small text-muted mb-1">{{ $activity->description }}</div>
                                    <div class="activity-time small">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $activity->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted small mb-0">No activity found for this resident</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

           <!-- Edit Resident Modals with Validation - Only if user has update_residents permission -->
            @if(auth('admin')->user()->hasPermission('update_residents'))
                @foreach($residents as $resident)
                <div class="modal fade" id="editResidentModal{{ $resident->id }}" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Resident - {{ $resident->full_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                            <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" id="editResidentForm{{ $resident->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $resident->id }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('firstname') is-invalid @enderror @endif"
                                                name="firstname"
                                                id="edit_firstname_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('firstname', $resident->firstname) : $resident->firstname }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('firstname')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('middlename') is-invalid @enderror @endif"
                                                name="middlename"
                                                id="edit_middlename_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('middlename', $resident->middlename) : $resident->middlename }}">
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('middlename')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('lastname') is-invalid @enderror @endif"
                                                name="lastname"
                                                id="edit_lastname_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('lastname', $resident->lastname) : $resident->lastname }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('lastname')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Suffix</label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('suffix') is-invalid @enderror @endif"
                                                name="suffix"
                                                id="edit_suffix_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix }}"
                                                placeholder="e.g., Jr., Sr., III">
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Username <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('username') is-invalid @enderror @endif"
                                                name="username"
                                                id="edit_username_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('username', $resident->username) : $resident->username }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('email') is-invalid @enderror @endif"
                                                name="email"
                                                id="edit_email_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('email', $resident->email) : $resident->email }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('contact') is-invalid @enderror @endif"
                                                name="contact"
                                                id="edit_contact_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('contact', $resident->contact) : $resident->contact }}"
                                                required
                                                maxlength="11">
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('contact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('birthdate') is-invalid @enderror @endif"
                                                name="birthdate"
                                                id="edit_birthdate_{{ $resident->id }}"
                                                value="{{ session('form_type') == 'edit_' . $resident->id ? old('birthdate', $resident->birthdate) : $resident->birthdate }}"
                                                required
                                                max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Complete Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('address') is-invalid @enderror @endif"
                                                    name="address"
                                                    id="edit_address_{{ $resident->id }}"
                                                    rows="2"
                                                    required>{{ session('form_type') == 'edit_' . $resident->id ? old('address', $resident->address) : $resident->address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $resident->id)
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                            </form>
                            </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>

                                    <button type="submit" form="editResidentForm{{ $resident->id }}" class="btn btn-primary" id="submitEditForm{{ $resident->id }}">
                                        <i class="fas fa-save me-2"></i>Update Resident
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Resident Modals (always visible) -->
            @foreach($residents as $resident)
            <div class="modal fade" id="viewResidentModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-user me-2"></i>
                                Resident Details - {{ $resident->full_name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Personal Information -->
                                <div class="col-12">
                                    <h6 class="fw-semibold text-primary border-bottom pb-2">
                                        <i class="fas fa-id-card me-2"></i>Personal Information
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">First Name</label>
                                    <p class="fw-semibold">{{ $resident->firstname }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Middle Name</label>
                                    <p class="fw-semibold">{{ $resident->middlename ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Last Name</label>
                                    <p class="fw-semibold">{{ $resident->lastname }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Suffix</label>
                                    <p class="fw-semibold">{{ $resident->suffix ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Username</label>
                                    <p>{{ $resident->username }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Age</label>
                                    <p>{{ $resident->age }} years old</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Birthdate</label>
                                    <p>
                                        {{ $resident->birthdate 
                                            ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') 
                                            : 'N/A' }}
                                    </p>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary border-bottom pb-2">
                                        <i class="fas fa-address-card me-2"></i>Contact Information
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Email</label>
                                    <p>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        {{ $resident->email ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Contact Number</label>
                                    <p>
                                        <i class="fas fa-phone text-muted me-1"></i>
                                        {{ $resident->contact ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-muted small mb-1">Complete Address</label>
                                    <p>
                                        <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                        {{ $resident->address ?? 'N/A' }}
                                    </p>
                                </div>

                                <!-- Profile Photo & Valid ID -->
                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary border-bottom pb-2">
                                        <i class="fas fa-image me-2"></i>Profile Photo &amp; Valid ID
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Profile Photo</label>
                                    @if($resident->profile_photo)
                                        <div>
                                            <img src="{{ asset($resident->profile_photo) }}" alt="Profile Photo"
                                                 class="img-thumbnail object-fit-cover"
                                                 style="width:120px;height:120px;border-radius:12px;cursor:pointer;"
                                                 onclick="document.getElementById('profilePhotoFull{{ $resident->id }}').style.display='flex'"
                                                 onerror="this.parentElement.innerHTML='<span class=\'text-muted small\'>Photo unavailable</span>';">
                                        </div>
                                        <!-- Full-screen overlay -->
                                        <div id="profilePhotoFull{{ $resident->id }}" onclick="this.style.display='none'"
                                             style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                                            <img src="{{ asset($resident->profile_photo) }}" style="max-width:90vw;max-height:90vh;border-radius:12px;">
                                        </div>
                                    @else
                                        <p class="text-muted small fst-italic"><i class="fas fa-image me-1"></i>No profile photo uploaded</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Valid ID</label>
                                    @if($resident->valid_id)
                                        @php $idExt = strtolower(pathinfo($resident->valid_id, PATHINFO_EXTENSION)); @endphp
                                        @if(in_array($idExt, ['jpg','jpeg','png','webp']))
                                            <div>
                                                <img src="{{ asset($resident->valid_id) }}" alt="Valid ID"
                                                     class="img-thumbnail object-fit-cover"
                                                     style="width:120px;height:80px;border-radius:12px;cursor:pointer;"
                                                     onclick="document.getElementById('validIdFull{{ $resident->id }}').style.display='flex'"
                                                     onerror="this.parentElement.innerHTML='<span class=\'text-muted small\'>Image unavailable</span>';">
                                                <div id="validIdFull{{ $resident->id }}" onclick="this.style.display='none'"
                                                     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                                                    <img src="{{ asset($resident->valid_id) }}" style="max-width:90vw;max-height:90vh;border-radius:12px;">
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ asset($resident->valid_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-file-pdf me-1"></i>View PDF
                                            </a>
                                        @endif
                                        <div class="mt-2">
                                            @if($resident->valid_id_verified)
                                                <span class="badge" style="background:#e8f5e8;color:#2e7d32;">
                                                    <i class="fas fa-check-circle me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge" style="background:#fff4e5;color:#ed6c02;">
                                                    <i class="fas fa-clock me-1"></i>Pending Verification
                                                </span>
                                                @if(auth('admin')->user()->hasPermission('update_residents'))
                                                    <form method="POST" action="{{ route('admin.residents.verifyId', $resident->id) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm ms-1"
                                                                style="background:#e8f5e8;color:#2e7d32;border:1px solid #c8e6c9;border-radius:8px;"
                                                                onclick="return confirm('Approve and verify the Valid ID for {{ addslashes($resident->full_name) }}?')">
                                                            <i class="fas fa-check me-1"></i>Approve ID
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted small fst-italic"><i class="fas fa-id-card me-1"></i>No valid ID uploaded</p>
                                    @endif
                                </div>

                                <!-- Account Information -->
                                <div class="col-12 mt-2">
                                    <h6 class="fw-semibold text-primary border-bottom pb-2">
                                        <i class="fas fa-cog me-2"></i>Account Information
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Registered At</label>
                                    <p>
                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                        {{ $resident->created_at 
                                            ? $resident->created_at->format('F d, Y h:i A') 
                                            : 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Last Updated</label>
                                    <p>
                                        <i class="far fa-clock text-muted me-1"></i>
                                        {{ $resident->updated_at 
                                            ? $resident->updated_at->format('F d, Y h:i A') 
                                            : 'N/A' }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Status</label>
                                    <p>
                                        @if(!$resident->deleted_at)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="fas fa-times-circle me-1"></i>Deleted
                                            </span>
                                            <small class="text-muted d-block mt-1">
                                                Deleted at: {{ $resident->deleted_at->format('F d, Y h:i A') }}
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk delete function
        function bulkDelete() {

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one resident to delete.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Delete',
                text: `Are you sure you want to delete ${checkboxes.length} selected resident(s)?`,
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

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            // If nothing selected â†’ Ask to export all
            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No residents selected. Export all records?',
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
                text: `Export ${checkboxes.length} selected resident(s)?`,
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
            const checkboxes = document.querySelectorAll('.resident-checkbox');
            
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

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swal if not loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return confirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.resident-checkbox');
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

            // Show appropriate modal if there are validation errors
            @if ($errors->any() && session('form_type'))
                @if(session('form_type') == 'add')
                    var addModal = new bootstrap.Modal(document.getElementById('addResidentModal'));
                    addModal.show();
                @elseif(Str::startsWith(session('form_type'), 'edit_'))
                    var editId = '{{ session('form_type') }}'.replace('edit_', '');
                    var editModal = new bootstrap.Modal(document.getElementById('editResidentModal' + editId));
                    editModal.show();
                @endif
            @endif
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Real-time validation for add form
        document.addEventListener('DOMContentLoaded', function() {
            const addForm = document.getElementById('addResidentForm');
            if (!addForm) return;

            const namePattern     = /^[a-zA-Z\s\-\.]+$/;
            const usernamePattern = /^[a-zA-Z0-9_]+$/;
            const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function calculateAge(birthDateValue) {
                if (!birthDateValue) return null;
                const birthDate = new Date(birthDateValue);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            function setField(el, msg) {
                if (msg) {
                    el.setCustomValidity(msg);
                    el.classList.add('is-invalid');
                    let fb = el.parentNode.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.parentNode.appendChild(fb); }
                    fb.textContent = msg;
                } else {
                    el.setCustomValidity('');
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }
            }

            // Clear any server-side is-invalid marks as soon as the user starts typing
            addForm.querySelectorAll('.is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }, { once: true });
            });

            // First name
            const fn = document.getElementById('add_firstname');
            if (fn) fn.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'First name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'First name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'First name must be at least 2 characters.');
                setField(this, '');
            });

            // Last name
            const ln = document.getElementById('add_lastname');
            if (ln) ln.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'Last name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'Last name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'Last name must be at least 2 characters.');
                setField(this, '');
            });

            // Username
            const un = document.getElementById('add_username');
            if (un) un.addEventListener('input', function() {
                if (!this.value)                          return setField(this, 'Username is required.');
                if (!usernamePattern.test(this.value))    return setField(this, 'Username can only contain letters, numbers, and underscores.');
                if (this.value.length < 3)                return setField(this, 'Username must be at least 3 characters.');
                setField(this, '');
            });

            // Email
            const em = document.getElementById('add_email');
            if (em) em.addEventListener('input', function() {
                if (!this.value)                        return setField(this, 'Email is required.');
                if (!emailPattern.test(this.value))     return setField(this, 'Please enter a valid email address.');
                setField(this, '');
            });

            // Contact
            const contact = document.getElementById('add_contact');
            if (contact) contact.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                if (!this.value)                        return setField(this, 'Contact number is required.');
                if (!this.value.startsWith('09'))       return setField(this, 'Contact number must start with 09.');
                if (this.value.length !== 11)           return setField(this, 'Contact number must be exactly 11 digits.');
                setField(this, '');
            });

            // Birthdate
            const bd = document.getElementById('add_birthdate');
            if (bd) bd.addEventListener('change', function() {
                const sel = new Date(this.value);
                const today = new Date(); today.setHours(0,0,0,0);
                const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                const age = calculateAge(this.value);
                if (sel >= today)    return setField(this, 'Birthdate must be in the past.');
                if (sel < minDate)   return setField(this, 'Please enter a valid birthdate.');
                if (age === null || age < 18) return setField(this, 'Resident must be 18 years old or above.');
                setField(this, '');
            });

            // Password strength
            const pw  = document.getElementById('add_password');
            const pwc = document.getElementById('add_password_confirmation');

            function validatePw() {
                // Remove spaces from password
                pw.value = pw.value.replace(/\s/g, "");
                if (!pw.value)                          return setField(pw, 'Password is required.');
                if (pw.value.length < 8)                return setField(pw, 'Password must be at least 8 characters.');
                if (!/[A-Z]/.test(pw.value))            return setField(pw, 'Password must contain at least one uppercase letter.');
                if (!/[a-z]/.test(pw.value))            return setField(pw, 'Password must contain at least one lowercase letter.');
                if (!/[0-9]/.test(pw.value))            return setField(pw, 'Password must contain at least one number.');
                if (!/[^A-Za-z0-9]/.test(pw.value))    return setField(pw, 'Password must contain at least one special character.');
                setField(pw, '');
                if (pwc.value) validatePwc();
            }
            function validatePwc() {
                // Remove spaces from confirm password
                pwc.value = pwc.value.replace(/\s/g, "");
                if (pwc.value !== pw.value) return setField(pwc, 'Passwords do not match.');
                setField(pwc, '');
            }
            if (pw)  pw.addEventListener('input', validatePw);
            if (pwc) pwc.addEventListener('input', validatePwc);

            const toggleAddPasswords = document.getElementById('toggle_add_passwords');
            if (toggleAddPasswords && pw && pwc) {
                toggleAddPasswords.addEventListener('change', function() {
                    const inputType = this.checked ? 'text' : 'password';
                    pw.type = inputType;
                    pwc.type = inputType;
                });
            }
        });
        // Real-time validation for edit forms
        @if(auth('admin')->user()->hasPermission('update_residents'))
            @foreach($residents as $resident)
            (function(id) {
                const form = document.getElementById('editResidentForm' + id);
                if (!form) return;

                function sf(el, msg) {
                    if (!el) return;
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (msg) {
                        el.setCustomValidity(msg);
                        el.classList.add('is-invalid');
                        if (fb) fb.textContent = msg;
                    } else {
                        el.setCustomValidity('');
                        el.classList.remove('is-invalid');
                        if (fb) fb.textContent = '';
                    }
                }

                // Clear server-side errors on first keystroke
                form.querySelectorAll('.is-invalid').forEach(function(el) {
                    el.addEventListener('input', function() {
                        el.classList.remove('is-invalid');
                        const fb = el.parentNode.querySelector('.invalid-feedback');
                        if (fb) fb.textContent = '';
                    }, { once: true });
                });

                const namePattern     = /^[a-zA-Z\s\-\.]+$/;
                const usernamePattern = /^[a-zA-Z0-9_]+$/;
                const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const fn = document.getElementById('edit_firstname_' + id);
                if (fn) fn.addEventListener('input', function() {
                    if (!this.value.trim())            return sf(this, 'First name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'First name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'First name must be at least 2 characters.');
                    sf(this, '');
                });

                const ln = document.getElementById('edit_lastname_' + id);
                if (ln) ln.addEventListener('input', function() {
                    if (!this.value.trim())            return sf(this, 'Last name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'Last name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'Last name must be at least 2 characters.');
                    sf(this, '');
                });

                const un = document.getElementById('edit_username_' + id);
                if (un) un.addEventListener('input', function() {
                    if (!this.value)                        return sf(this, 'Username is required.');
                    if (!usernamePattern.test(this.value))  return sf(this, 'Username can only contain letters, numbers, and underscores.');
                    if (this.value.length < 3)              return sf(this, 'Username must be at least 3 characters.');
                    sf(this, '');
                });

                const em = document.getElementById('edit_email_' + id);
                if (em) em.addEventListener('input', function() {
                    if (!this.value)                    return sf(this, 'Email is required.');
                    if (!emailPattern.test(this.value)) return sf(this, 'Please enter a valid email address.');
                    sf(this, '');
                });

                const ct = document.getElementById('edit_contact_' + id);
                if (ct) ct.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (!this.value)                  return sf(this, 'Contact number is required.');
                    if (!this.value.startsWith('09')) return sf(this, 'Contact number must start with 09.');
                    if (this.value.length !== 11)     return sf(this, 'Contact number must be exactly 11 digits.');
                    sf(this, '');
                });

                const bd = document.getElementById('edit_birthdate_' + id);
                if (bd) bd.addEventListener('change', function() {
                    const sel = new Date(this.value);
                    const today = new Date(); today.setHours(0,0,0,0);
                    const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                    if (sel >= today)   return sf(this, 'Birthdate must be in the past.');
                    if (sel < minDate)  return sf(this, 'Please enter a valid birthdate.');
                    sf(this, '');
                });

            })('{{ $resident->id }}');
            @endforeach
        @endif
    </script>

    <!-- SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

