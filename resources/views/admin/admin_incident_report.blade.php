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
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
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

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
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
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="recordsSubmenu">
            <a href="{{ route('clearance.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-file-contract"></i> <span>Clearances</span></a>
            <a href="{{ route('blotter.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-book"></i> <span>Incident Reports</span></a>
        </div>

        <div class="menu-section">Community</div>
        
        <!-- Community Dropdown -->
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="communitySubmenu">
            <a href="{{ route('announcements.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            <a href="{{ route('events.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            <a href="{{ route('projects.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
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
                    <i class="fas fa-book me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Incident Reports
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

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $incidents->total() ?? 0 }}</div>
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
                            <div class="stat-label text-muted mb-1">Dropped</div>
                            <div class="stat-number">{{ $rejected_count ?? 0 }}</div>
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
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('blotter.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter Incident Reports
                                </h6>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    <a href="" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add</span>
                                    </a>
                                    <a href="{{ route('blotter.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search reference or complainant..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Dropped</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="confidentiality" class="form-select">
                                    <option value="">Confidentiality</option>
                                    <option value="low" {{ request('confidentiality') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('confidentiality') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('confidentiality') == 'high' ? 'selected' : '' }}>High</option>
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
                            <form id="bulkForm" method="POST" action="{{ route('blotter.bulkDelete') }}" style="display: inline;">
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

            <!-- Incident Reports Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="blotterTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">Reference #</th>
                                    <th>Report Type</th>
                                    <th class="d-none d-lg-table-cell">Incident Date</th>
                                    <th>Complainant</th>
                                    <th class="d-none d-md-table-cell">Respondent</th>
                                    <th class="d-none d-md-table-cell">Confidentiality</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($incidents as $blotter)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" name="ids[]" value="{{ $blotter->id }}" form="bulkForm" class="application-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <span class="fw-semibold">{{ $blotter->reference_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                <i class="fas fa-exclamation-triangle text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $blotter->report_type }}</div>
                                                <small class="text-muted d-lg-none">{{ \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">{{ \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') }}</td>
                                    <td>{{ $blotter->complainant_name }}</td>
                                    <td class="d-none d-md-table-cell">{{ $blotter->respondent_name ?? 'N/A' }}</td>
                                    <td class="d-none d-md-table-cell">
                                        @if($blotter->confidentiality == 'high')
                                            <span class="badge bg-danger-subtle text-danger">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @elseif($blotter->confidentiality == 'medium')
                                            <span class="badge bg-warning-subtle text-warning">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blotter->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @elseif($blotter->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View Button -->
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $blotter->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if($blotter->status == 'processing')
                                            <form method="POST" action="{{ route('blotter.approve', $blotter->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Resolve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('blotter.reject', $blotter->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to drop this case?')" title="Drop Case">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('blotter.destroy', $blotter->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this incident report?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-book fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No incident reports found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                                <i class="fas fa-plus me-2"></i>Add New
                                            </button>
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

            <!-- Add Incident Modal -->
            <div class="modal fade" id="addIncidentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                New Incident Report
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <!-- Incident Details -->
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Incident Details</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Report Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="report_type" required>
                                            <option value="Blotter">Blotter</option>
                                            <option value="Complaint">Complaint</option>
                                            <option value="Incident">Incident</option>
                                            <option value="Accident">Accident</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Confidentiality</label>
                                        <select class="form-select" name="confidentiality">
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="incident_date" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Time</label>
                                        <input type="time" class="form-control" name="incident_time">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control" name="location" placeholder="Incident location">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Describe the incident..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Immediate Action Taken</label>
                                        <textarea class="form-control" name="immediate_action" rows="2" placeholder="Any immediate action taken..."></textarea>
                                    </div>

                                    <!-- Complainant Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Complainant Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="complainant_name" required placeholder="Full name">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" name="complainant_contact" placeholder="09XXXXXXXXX">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="complainant_address" rows="2" placeholder="Complete address"></textarea>
                                    </div>

                                    <!-- Respondent Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Respondent Information (Optional)</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Name</label>
                                        <input type="text" class="form-control" name="respondent_name" placeholder="Full name">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Contact</label>
                                        <input type="text" class="form-control" name="respondent_contact" placeholder="09XXXXXXXXX">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Respondent Address</label>
                                        <textarea class="form-control" name="respondent_address" rows="2" placeholder="Complete address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Save Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Modals -->
            @foreach($incidents as $blotter)
            <div class="modal fade" id="viewModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-book me-2"></i>
                                Incident Details - {{ $blotter->reference_number }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Incident Details -->
                            <h6 class="fw-semibold text-primary mb-3">Incident Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Report Type:</strong>
                                    <p>{{ $blotter->report_type }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Confidentiality:</strong>
                                    <p>
                                        @if($blotter->confidentiality == 'high')
                                            <span class="badge bg-danger-subtle text-danger">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @elseif($blotter->confidentiality == 'medium')
                                            <span class="badge bg-warning-subtle text-warning">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Incident Date:</strong>
                                    <p>{{ \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Incident Time:</strong>
                                    <p>{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <strong>Location:</strong>
                                    <p>{{ $blotter->location ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <strong>Description:</strong>
                                    <p>{{ $blotter->description ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <strong>Immediate Action:</strong>
                                    <p>{{ $blotter->immediate_action ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Complainant Information -->
                            <h6 class="fw-semibold text-primary mb-3">Complainant Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Name:</strong>
                                    <p>{{ $blotter->complainant_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Contact:</strong>
                                    <p>{{ $blotter->complainant_contact ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p>{{ $blotter->complainant_address ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Respondent Information -->
                            <h6 class="fw-semibold text-primary mb-3">Respondent Information</h6>
                            @if($blotter->respondent_name)
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Name:</strong>
                                    <p>{{ $blotter->respondent_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Contact:</strong>
                                    <p>{{ $blotter->respondent_contact ?: 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p>{{ $blotter->respondent_address ?: 'N/A' }}</p>
                                </div>
                            </div>
                            @else
                                <p class="text-muted">No respondent information provided.</p>
                            @endif

                            <hr>

                            <!-- Status -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        @if($blotter->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @elseif($blotter->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Submitted At:</strong>
                                    <p>{{ $blotter->created_at ? $blotter->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Witnesses Section -->
                            <h6 class="fw-semibold text-primary mb-3">Witnesses</h6>
                            
                            @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                @foreach($blotter->witnesses as $witness)
                                    <div class="witness-card">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Name:</strong>
                                                <p>{{ $witness->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Contact:</strong>
                                                <p>{{ $witness->contact ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-12">
                                                <strong>Statement:</strong>
                                                <p>{{ $witness->statement ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-12 text-end">
                                                <form method="POST" action="{{ route('witness.destroy', $witness->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this witness?')">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-3">No witnesses added yet.</p>
                            @endif

                            <hr>

                            <!-- Add Witness Form -->
                            <h6 class="fw-semibold text-primary mb-3">Add Witness</h6>
                            <form method="POST" action="{{ route('witness.store', $blotter->id) }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Witness Name">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="contact" class="form-control" placeholder="Contact Number">
                                    </div>
                                    <div class="col-12">
                                        <textarea name="statement" class="form-control" rows="2" placeholder="Witness Statement"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-2"></i>Add Witness
                                        </button>
                                    </div>
                                </div>
                            </form>
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
            
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk delete function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one incident report to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${checkboxes.length} incident report(s)?`)) {
                document.getElementById('bulkForm').submit();
            }
        }

        // Export CSV function
        function exportCSV() {
            document.getElementById('exportForm').submit();
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.application-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
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

            // Close mobile sidebar when clicking a link
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeMobileSidebar();
                    }
                });
            });
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