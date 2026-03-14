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
        .header {
            padding: 1rem 1.5rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0;
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
                    <i class="fas fa-users-cog me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Users & Roles Management
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

            <!-- Stats Cards -->
            <div class="row g-3 g-lg-4 mb-4">
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
            </div>

            <!-- USERS TAB -->
            <div id="usersTab" class="tab-content active">
                <!-- Filters and Actions -->
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm">
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-12 col-md-6">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-filter me-2 text-primary"></i>Filter Users
                                    </h6>
                                </div>
                                <div class="col-12 col-md-6 text-md-end">
                                    <div class="d-flex gap-2 justify-content-md-end">
                                        @admin_can('create_users')
                                        <button type="button" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                            <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add User</span>
                                        </button>
                                        @endadmin_can
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
                                            <i class="fas fa-rotate"></i><span class="d-none d-sm-inline ms-2">Reset</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Search and Filter Row -->
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by name, email..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search d-sm-none"></i>
                                            <span class="d-none d-sm-inline">Search</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="role" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Roles</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                                {{ $role->display_name ?? $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-3">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-filter me-2"></i>Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card border-0">
                    <div class="card-body p-0">
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
                                                    <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
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
                                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
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
                        <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" id="resetPermissionsForm" style="display: inline;">
                            @csrf
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
                                            {{ ($selectedRole->name === 'super_admin' || !auth('admin')->user()->hasPermission('update_permissions')) ? 'disabled' : '' }}>
                                        <label for="perm_{{ $permission->id }}">{{ $permission->display_name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($selectedRole->name !== 'super_admin' && auth('admin')->user()->hasPermission('update_permissions'))
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
                        <form method="POST" action="{{ route('admin.users.store') }}" id="addUserForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Personal Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                    <div class="col-12 col-md-6">
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
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="send_welcome_email" id="sendWelcomeEmail" checked>
                                            <label class="form-check-label" for="sendWelcomeEmail">
                                                Send welcome email with login credentials
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create User
                                </button>
                            </div>
                        </form>
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
                            <form method="POST" action="" id="resetPasswordForm">
                                @csrf
                                <div class="modal-body">
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
                                            <input type="password" class="form-control" name="password" id="resetPassword" required minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('resetPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation" id="resetPasswordConfirm" required>
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Reset Password
                                    </button>
                                </div>
                            </form>
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
                        <form method="POST" action="{{ route('admin.roles.store') }}" id="addRoleForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="e.g., staff" required>
                                        <small class="text-muted">System name (lowercase, no spaces)</small>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Display Name</label>
                                        <input type="text" class="form-control" name="display_name" placeholder="e.g., Staff Member">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="2" placeholder="Role description"></textarea>
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
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create Role
                                </button>
                            </div>
                        </form>
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
                                <h5>{{ $user->first_name ?? 'Juan' }} {{ $user->last_name ?? 'Dela Cruz' }}</h5>
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
                                        <strong>{{ $user->last_login_at ?? 'Today 9:30 AM' }}</strong>
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
                            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name ?? 'Juan' }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                            </form>
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
                            <form method="POST" action="{{ route('admin.users.permissions', $user->id) }}" id="userPermissionsForm{{ $user->id }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
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
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="user_perm_{{ $permission->id }}_{{ $user->id }}">
                                                    <label for="user_perm_{{ $permission->id }}_{{ $user->id }}">{{ $permission->display_name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Save Permissions
                                    </button>
                                </div>
                            </form>
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
                                <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary" {{ $role->is_system_role ? 'disabled' : '' }}>
                                            <i class="fas fa-save me-2"></i>Update Role
                                        </button>
                                    </div>
                                </form>
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
                                                                <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
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
                                <form method="POST" action="{{ route('admin.roles.members.add', $role->id) }}">
                                    @csrf
                                    <div class="modal-body">
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-2"></i>Add Member
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                @endif
            @endif

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</body>
</html>