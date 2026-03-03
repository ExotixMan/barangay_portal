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
            min-width: 1300px;
        }

        @media (max-width: 768px) {
            .table {
                min-width: 1100px;
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
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Incident Report</span>
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
                                <select name="confidentiality" class="form-select">
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
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        <form id="bulkForm" method="POST" action="{{ route('blotter.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Delete">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Delete</span>
                            </button>
                        </form>
                        <form id="exportForm" method="POST" action="{{ route('blotter.export') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="exportCSV()" class="btn btn-outline-success d-flex align-items-center gap-2" title="Export CSV">
                                <i class="fas fa-file-csv"></i>
                                <span class="d-none d-sm-inline">Export</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Incident Reports Table - Mobile Responsive with Horizontal Scroll -->
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="blotterTable">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'report_type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Report Type
                                            @if(request('sort') == 'report_type')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'incident_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Incident Date
                                            @if(request('sort') == 'incident_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'complainant_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Complainant
                                            @if(request('sort') == 'complainant_name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">Respondent</th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'confidentiality', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Confidentiality
                                            @if(request('sort') == 'confidentiality')
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
                                @forelse($incidents as $blotter)
                                <tr class="{{ $blotter->deleted_at ? 'table-danger' : '' }}">
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $blotter->id }}" class="application-checkbox">
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
                                        @if($blotter->confidentiality == 'public')
                                            <span class="badge bg-danger-subtle text-danger">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @elseif($blotter->confidentiality == 'medium')
                                            <span class="badge bg-warning-subtle text-warning">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info">{{ ucfirst($blotter->confidentiality) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($blotter->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Deleted</span>
                                        @else
                                            @if($blotter->status == 'resolved')
                                                <span class="badge bg-success-subtle text-success">Resolved</span>
                                            @elseif($blotter->status == 'dropped')
                                                <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View Button -->
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $blotter->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if(!$blotter->deleted_at)
                                                @if($blotter->status == 'processing')
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editIncidentModal{{ $blotter->id }}" title="Edit Report">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @endif

                                                @if($blotter->status == 'resolved' || $blotter->status == 'dropped')
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Send Notification">
                                                        <i class="fas fa-bell"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <form method="POST" action="{{ route('sendEmail') }}" class="dropdown-item p-0">
                                                                @csrf
                                                                <input type="hidden" name="email" value="{{ $blotter->complainant_email ?? '' }}">
                                                                <input type="hidden" name="name" value="{{ $blotter->complainant_name }}">
                                                                @if($blotter->status == 'resolved')
                                                                <input type="hidden" name="message" value="Your incident report (Ref: {{ $blotter->reference_number }}) has been RESOLVED. Please visit the barangay hall for further details.">
                                                                @else
                                                                <input type="hidden" name="message" value="Your incident report (Ref: {{ $blotter->reference_number }}) has been DROPPED. Please visit the barangay hall for further details or clarification.">
                                                                @endif
                                                                <button type="submit" class="dropdown-item" onclick="return confirm('Send {{ $blotter->status }} notification email to complainant?')">
                                                                    <i class="fas fa-envelope me-2"></i>Send Email
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="{{ route('sendSMS') }}" class="dropdown-item p-0">
                                                                @csrf
                                                                <input type="hidden" name="phone" value="+63{{ ltrim($blotter->complainant_contact ?? '', '0') }}">
                                                                @if($blotter->status == 'resolved')
                                                                <input type="hidden" name="message" value="Your incident report {{ $blotter->reference_number }} has been RESOLVED. Please visit the barangay hall for details.">
                                                                @else
                                                                <input type="hidden" name="message" value="Your incident report {{ $blotter->reference_number }} has been DROPPED. Please visit the barangay hall for details or clarification.">
                                                                @endif
                                                                <button type="submit" class="dropdown-item" onclick="return confirm('Send {{ $blotter->status }} notification SMS to complainant?')">
                                                                    <i class="fas fa-sms me-2"></i>Send SMS
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif

                                                @if($blotter->status == 'processing')
                                                <form method="POST" action="{{ route('blotter.approve', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Resolve this incident report?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Resolve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                @if($blotter->status == 'processing')
                                                <form method="POST" action="{{ route('blotter.reject', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Drop this case?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Drop Case">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('blotter.destroy', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Delete this incident report? It can be restored later.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Restore Button for deleted records -->
                                                <form method="POST" action="{{ route('blotter.restore', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this incident report?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>

                                                {{-- <!-- Permanent Delete Button (optional) -->
                                                <form method="POST" action="{{ route('blotter.forceDelete', $blotter->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Permanently delete this incident report? This action cannot be undone!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Permanently Delete">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form> --}}
                                            @endif
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

            <!-- Add Incident Modal with Witnesses and Evidence -->
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
                        <form method="POST" action="{{ route('blotter.store') }}" id="addIncidentForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                            <div class="modal-body">
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
                                            name="complainantName" value="{{ session('form_type') == 'add' ? old('complainantName') : '' }}" placeholder="Full name" required>
                                        @if(session('form_type') == 'add')
                                            @error('complainantName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('complainantContact') is-invalid @enderror @endif" 
                                            name="complainantContact" value="{{ session('form_type') == 'add' ? old('complainantContact') : '' }}" placeholder="09XXXXXXXXX" maxlength="11" required>
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
                                        <label class="form-label">Email (Optional)</label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('complainantEmail') is-invalid @enderror @endif" 
                                            name="complainantEmail" value="{{ session('form_type') == 'add' ? old('complainantEmail') : '' }}" placeholder="email@example.com">
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
                                            name="respondentName" value="{{ session('form_type') == 'add' ? old('respondentName') : '' }}" placeholder="Full name">
                                        @if(session('form_type') == 'add')
                                            @error('respondentName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Contact</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('respondentContact') is-invalid @enderror @endif" 
                                            name="respondentContact" value="{{ session('form_type') == 'add' ? old('respondentContact') : '' }}" placeholder="09XXXXXXXXX" maxlength="11">
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
                                    <div class="col-12 mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="fw-semibold text-primary mb-0">Witnesses</h6>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addWitnessField()">
                                                <i class="fas fa-plus me-1"></i>Add Witness
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mb-3">Add witnesses to support this incident report</small>
                                        
                                        <div id="witnesses-container">
                                            <!-- Witness fields will be added here dynamically -->
                                        </div>
                                    </div>

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
                                <button type="submit" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Incident Modals with Validation -->
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
                        <form method="POST" action="{{ route('blotter.update', $blotter->id) }}" id="editIncidentForm{{ $blotter->id }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="edit_{{ $blotter->id }}">

                            <div class="modal-body">
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
                                            name="incidentDate" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentDate', $blotter->incident_date) : $blotter->incident_date }}" required max="{{ date('Y-m-d') }}">
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
                                            name="complainantName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantName', $blotter->complainant_name) : $blotter->complainant_name }}" placeholder="Full name" required>
                                        @if(session('form_type') == 'edit_' . $blotter->id)
                                            @error('complainantName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantContact') is-invalid @enderror @endif" 
                                            name="complainantContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantContact', $blotter->complainant_contact) : $blotter->complainant_contact }}" placeholder="09XXXXXXXXX" maxlength="11" required>
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
                                        <label class="form-label">Email (Optional)</label>
                                        <input type="email" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantEmail') is-invalid @enderror @endif" 
                                            name="complainantEmail" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantEmail', $blotter->complainant_email) : $blotter->complainant_email }}" placeholder="email@example.com">
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
                                            name="respondentName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentName', $blotter->respondent_name) : $blotter->respondent_name }}" placeholder="Full name">
                                        @if(session('form_type') == 'edit_' . $blotter->id)
                                            @error('respondentName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Contact</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentContact') is-invalid @enderror @endif" 
                                            name="respondentContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentContact', $blotter->respondent_contact) : $blotter->respondent_contact }}" placeholder="09XXXXXXXXX" maxlength="11">
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
                                    <div class="col-12 mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="fw-semibold text-primary mb-0">Witnesses</h6>
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
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditWitnessField({{ $blotter->id }}, {{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Witness Name</label>
                                                            <input type="text" class="form-control" name="witnesses[{{ $index }}][name]" value="{{ $witness->name }}" placeholder="Full name">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Contact Number</label>
                                                            <input type="text" class="form-control" name="witnesses[{{ $index }}][contact]" value="{{ $witness->contact }}" placeholder="09XXXXXXXXX" maxlength="11">
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
                                                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" onclick="removeFile({{ $file->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
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
                                <button type="submit" class="btn btn-primary" id="submitEditForm{{ $blotter->id }}">
                                    <i class="fas fa-save me-2"></i>Update Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

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
                                    <p>{{ ucfirst($blotter->report_type) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Confidentiality:</strong>
                                    <p>
                                        @if($blotter->confidentiality == 'high')
                                            <span class="badge bg-danger-subtle text-danger">Anonymous Report</span>
                                        @elseif($blotter->confidentiality == 'medium')
                                            <span class="badge bg-warning-subtle text-warning">Confidential Report</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info">Public Report</span>
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
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p>{{ $blotter->complainant_email ?: 'N/A' }}</p>
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
                                @if($blotter->respondent_description)
                                <div class="col-12">
                                    <strong>Description:</strong>
                                    <p>{{ $blotter->respondent_description }}</p>
                                </div>
                                @endif
                            </div>
                            @else
                                <p class="text-muted">No respondent information provided.</p>
                            @endif

                            <hr>

                            <!-- Witnesses Section -->
                            @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                            <h6 class="fw-semibold text-primary mb-3">Witnesses</h6>
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
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                            @endif

                            <!-- Files/Evidence Section -->
                            @if($blotter->files && $blotter->files->count() > 0)
                            <h6 class="fw-semibold text-primary mb-3">Files / Evidence</h6>
                            <div class="row g-3 mb-3">
                                @foreach($blotter->files as $file)
                                <div class="col-md-4">
                                    <div class="border rounded p-2">
                                        @php
                                            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                            $fileUrl = asset($file->file_path);
                                        @endphp
                                        
                                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <strong>Photo:</strong>
                                            <div class="mt-2">
                                                <img src="{{ $fileUrl }}" class="img-fluid rounded" style="max-height: 150px; cursor: pointer;" onclick="openZoomModal('{{ $fileUrl }}')">
                                            </div>
                                        @elseif(in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'mkv']))
                                            <strong>Video:</strong>
                                            <div class="mt-2 text-center">
                                                <i class="fas fa-video fa-4x text-muted"></i>
                                            </div>
                                        @else
                                            <strong>Document:</strong>
                                            <div class="mt-2 text-center">
                                                <i class="fas fa-file-pdf fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2 small">
                                            <span class="text-truncate d-block">{{ basename($file->file_path) }}</span>
                                            <span class="badge bg-info">{{ $file->file_type }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                                <i class="fas fa-download"></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <hr>
                            @endif

                            @if($blotter->additional_info)
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <h6 class="fw-semibold text-primary mb-2">Additional Information</h6>
                                    <div class="border rounded p-3 bg-light">
                                        <p class="mb-0">{{ $blotter->additional_info }}</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @endif
                            
                            <!-- Status -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        @if($blotter->status == 'resolved')
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @elseif($blotter->status == 'dropped')
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
                    text: 'Please select at least one incident report to delete.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Delete',
                text: `Are you sure you want to delete ${checkboxes.length} selected incident report(s)?`,
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
                var addModal = new bootstrap.Modal(document.getElementById('addIncidentModal'));
                addModal.show();
            @endif

            // Real-time validation for add form
            const addForm = document.getElementById('addIncidentForm');
            if (addForm) {
                // Contact number validation
                const contact = document.querySelector('input[name="complainant_contact"]');
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

                // Incident date validation
                const incidentDate = document.querySelector('input[name="incident_date"]');
                if (incidentDate) {
                    incidentDate.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const today = new Date();
                        if (selectedDate > today) {
                            this.setCustomValidity('Incident date cannot be in the future');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }
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
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][name]" placeholder="Full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][contact]" placeholder="09XXXXXXXXX" maxlength="11">
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
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
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
        @foreach($incidents as $blotter)
        (function(editId) {
            const editForm = document.getElementById('editIncidentForm' + editId);
            if (editForm) {
                // Contact number validation
                const contact = document.getElementById('edit_complainant_contact_' + editId);
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
                        const namePattern = /^[a-zA-Z\s\-]+$/;
                        if (!namePattern.test(this.value) && this.value.length > 0) {
                            this.setCustomValidity('Name can only contain letters, spaces, and hyphens');
                            this.classList.add('is-invalid');
                            
                            let feedback = this.nextElementSibling;
                            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                this.parentNode.appendChild(feedback);
                            }
                            feedback.textContent = 'Name can only contain letters, spaces, and hyphens';
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                const respondentName = document.getElementById('edit_respondent_name_' + editId);
                if (respondentName) {
                    respondentName.addEventListener('input', function() {
                        const namePattern = /^[a-zA-Z\s\-]+$/;
                        if (!namePattern.test(this.value) && this.value.length > 0) {
                            this.setCustomValidity('Name can only contain letters, spaces, and hyphens');
                            this.classList.add('is-invalid');
                            
                            let feedback = this.nextElementSibling;
                            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                this.parentNode.appendChild(feedback);
                            }
                            feedback.textContent = 'Name can only contain letters, spaces, and hyphens';
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }
            }
        })('{{ $blotter->id }}');
        @endforeach

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
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][name]" placeholder="Full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][contact]" placeholder="09XXXXXXXXX" maxlength="11">
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