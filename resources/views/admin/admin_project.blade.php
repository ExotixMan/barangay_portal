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
        }

        .profile-badge i {
            font-size: 1.2rem;
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
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
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

        <a href="{{ route('dashboard.index') }}" class="" onclick="handleLinkClick(event, this)">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-section">Administrative</div>
        
        <!-- Registry Dropdown -->
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-users"></i>
            <span>Registry</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="registrySubmenu">
            <a href="{{ route('residents.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
            <a href="{{ route('residency.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-alt"></i> <span>Residency Applications</span></a>
            <a href="{{ route('indigency.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-invoice"></i> <span>Indigency</span></a>
        </div>

        <div class="menu-section">Legal</div>
        
        <!-- Records Dropdown -->
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="recordsSubmenu">
            <a href="{{ route('clearance.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-contract"></i> <span>Clearances</span></a>
            <a href="{{ route('blotter.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-book"></i> <span>Incident Reports</span></a>
        </div>

        <div class="menu-section">Community</div>
        
        <!-- Community Dropdown -->
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="communitySubmenu">
            <a href="{{ route('announcements.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            <a href="{{ route('events.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            <a href="{{ route('projects.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
        </div>

        <div class="menu-section">System</div>
        
        <a href="#" onclick="handleLinkClick(event, this)">
            <i class="fas fa-user"></i>
            <span>Users</span>
        </a>
        <a href="#" onclick="handleLinkClick(event, this)">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
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
                    <i class="fas fa-project-diagram me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Projects
                </h1>
            </div>
            <div class="profile-badge">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
                <i class="fas fa-chevron-down ms-1 d-none d-sm-inline" style="font-size: 0.8rem;"></i>
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
                                <i class="fas fa-spinner me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-spinner"></i>
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

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('projects.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Projects
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Project</span>
                                    </a>
                                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by project title, description, location..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="progress" class="form-select">
                                    <option value="">All Progress</option>
                                    <option value="0-25" {{ request('progress') == '0-25' ? 'selected' : '' }}>0-25%</option>
                                    <option value="26-50" {{ request('progress') == '26-50' ? 'selected' : '' }}>26-50%</option>
                                    <option value="51-75" {{ request('progress') == '51-75' ? 'selected' : '' }}>51-75%</option>
                                    <option value="76-100" {{ request('progress') == '76-100' ? 'selected' : '' }}>76-100%</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-3 d-flex gap-2 justify-content-end">
                            <form id="bulkForm" method="POST" action="{{ route('projects.bulkDelete') }}" style="display: inline;">
                                @csrf
                                <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="d-none d-sm-inline">Bulk Delete</span>
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Projects Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
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
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
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
                                            {{ Str::limit($project->location, 20) ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->start_date 
                                                ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y')
                                                : '—' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->expected_completion 
                                                ? \Carbon\Carbon::parse($project->expected_completion)->format('M d, Y')
                                                : '—' }}
                                        </span>
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
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
                                            <!-- View -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $project->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit -->
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}" title="Edit Project">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Update Progress (only for ongoing) -->
                                            @if($project->status == 'ongoing')
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateProgress({{ $project->id }}, {{ $project->progress }})" title="Update Progress">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            @endif

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('projects.destroy', $project->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this project permanently?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-project-diagram fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No projects found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                                <i class="fas fa-plus me-2"></i>Add New Project
                                            </button>
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

            <!-- Add Project Modal with Validation -->
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat me-2"></i>
                                New Project
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('projects.store') }}" id="addProjectForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
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
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="Project location/address">
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
                                        <input type="range" class="form-range" name="progress" id="addProgressRange" min="0" max="100" step="1" value="{{ old('progress', 0) }}">
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Project Modals with Validation -->
            @foreach($projects as $project)
            <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>
                                Edit Project
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('projects.update', $project->id) }}" id="editProjectForm{{ $project->id }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="edit_{{ $project->id }}">

                            <div class="modal-body">
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitEditForm{{ $project->id }}">
                                    <i class="fas fa-save me-2"></i>Update Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- View Modals -->
            @foreach($projects as $project)
            <div class="modal fade" id="viewModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat me-2"></i>
                                Project Details
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

            <!-- Update Progress Modal -->
            <div class="modal fade" id="updateProgressModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-chart-line me-2"></i>
                                Update Project Progress
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="" id="updateProgressForm">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Progress Percentage</label>
                                    <input type="range" class="form-range" name="progress" id="progressRange" min="0" max="100" step="1">
                                    <div class="text-center mt-2">
                                        <span class="badge bg-primary" id="progressValue">0%</span>
                                    </div>
                                    <input type="hidden" name="progress" id="progressHidden">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" id="progressStatus">
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk delete function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.project-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one project to delete.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one project to delete.');
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirm Bulk Delete',
                    text: `Are you sure you want to delete ${checkboxes.length} selected project(s)?`,
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
                if (confirm(`Are you sure you want to delete ${checkboxes.length} project(s)?`)) {
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
            const checkboxes = document.querySelectorAll('.project-checkbox');
            
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

        // Update progress function
        function updateProgress(projectId, currentProgress) {
            const modal = new bootstrap.Modal(document.getElementById('updateProgressModal'));
            const form = document.getElementById('updateProgressForm');
            const range = document.getElementById('progressRange');
            const value = document.getElementById('progressValue');
            const hidden = document.getElementById('progressHidden');
            const statusSelect = document.getElementById('progressStatus');
            
            form.action = `/projects/${projectId}/progress`;
            range.value = currentProgress;
            value.textContent = currentProgress + '%';
            hidden.value = currentProgress;
            
            if (currentProgress == 100) {
                statusSelect.value = 'completed';
            } else {
                statusSelect.value = 'ongoing';
            }
            
            modal.show();
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

        // Update progress range display for progress modal
        document.getElementById('progressRange')?.addEventListener('input', function() {
            document.getElementById('progressValue').textContent = this.value + '%';
            document.getElementById('progressHidden').value = this.value;
            
            // Auto-select status based on progress
            const statusSelect = document.getElementById('progressStatus');
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
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>