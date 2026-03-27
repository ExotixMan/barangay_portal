<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatbot Management - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

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

        #userDropdown {
            color: var(--primary);
        }

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

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
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
            transform: translateY(-4px);
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
            font-size: 1.75rem;
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
            font-size: 1.35rem;
        }

        .tab-navigation {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-link {
            padding: 0.75rem 1.2rem;
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

        .table-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .table-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .table-card-header h6 {
            margin: 0;
            font-weight: 700;
            color: #1a2540;
        }

        .table-responsive {
            border-radius: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            min-width: 900px;
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-category {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .unmatched-row td {
            color: #c0392b;
        }

        .resolved-row td {
            color: #64748b;
        }

        .unmatched-query {
            max-width: 280px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: #fff;
            border-radius: 20px 20px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.25rem 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem 1.25rem;
            border-top: 1px solid var(--border-color);
        }

        .is-invalid {
            border-color: var(--primary) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.15) !important;
        }

        .invalid-feedback {
            color: var(--primary-dark);
            font-size: 0.78rem;
            margin-top: 0.25rem;
            display: block;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .profile-badge span {
                display: none;
            }

            .profile-badge {
                padding: 0.5rem;
            }

            .stat-number {
                font-size: 1.4rem;
            }

            .table {
                min-width: 760px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

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
        <a href="{{ route('admin.chatbot.index') }}" class="active" onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
        @endadmin_can

        @admin_can('view_backup')
        <a href="{{ route('admin.backup.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-database"></i>
            <span>Backup Settings</span>
        </a>
        @endadmin_can    </div>

    <div class="main-content" id="mainContent">
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <i class="fas fa-robot me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Chatbot Management
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

        <main class="p-3 p-lg-4">
            @admin_can('view_content')
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Knowledge Items</div>
                            <div class="stat-number" id="stat-knowledge">{{ $stats['knowledge_count'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-database-fill"></i></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Conversations</div>
                            <div class="stat-number" id="stat-conv">{{ $stats['conversation_count'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total Messages</div>
                            <div class="stat-number" id="stat-msg">{{ $stats['message_count'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-send-fill"></i></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Unmatched Queries</div>
                            <div class="stat-number" id="stat-unmatched">{{ $stats['unmatched_count'] ?? 0 }}</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-question-circle-fill"></i></div>
                    </div>
                </div>
            </div>

            <div class="tab-navigation">
                <button class="tab-link active" data-tab="knowledge">
                    <i class="fas fa-database me-2"></i>Knowledge Base
                </button>
                <button class="tab-link" data-tab="unmatched">
                    <i class="fas fa-circle-question me-2"></i>Unmatched Queries
                </button>
            </div>

            <div id="tab-knowledge" class="tab-content active">
                <div class="table-card">
                    <div class="table-card-header">
                        <h6><i class="bi bi-database-fill me-2"></i>Knowledge Base <span class="badge bg-primary ms-1" id="kb-count">{{ count($knowledge) }}</span></h6>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control form-control-sm" id="kb-search" placeholder="Search..." style="width:200px">
                            @admin_can('create_content')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="bi bi-plus-lg me-1"></i>Add
                            </button>
                            @endadmin_can
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="knowledge-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:28%">Question</th>
                                    <th style="width:36%">Answer</th>
                                    <th style="width:16%">Keywords</th>
                                    <th style="width:9%">Category</th>
                                    <th style="width:5%">Uses</th>
                                    <th style="width:6%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="knowledge-tbody">
                                @foreach($knowledge as $k)
                                <tr data-id="{{ $k['id'] }}">
                                    <td class="kb-question">{{ Str::limit($k['question'], 80) }}</td>
                                    <td class="kb-answer" style="font-size:.82rem;color:#4a5568">{{ Str::limit($k['answer'], 120) }}</td>
                                    <td><small class="text-muted">{{ Str::limit($k['keywords'] ?? '', 60) }}</small></td>
                                    <td><span class="badge-category bg-primary bg-opacity-10 text-primary">{{ $k['category'] ?? 'general' }}</span></td>
                                    <td><span class="badge bg-light text-dark">{{ $k['usage_count'] ?? 0 }}</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @admin_can('edit_content')
                                            <button class="btn btn-outline-secondary btn-edit" data-id="{{ $k['id'] }}" data-question="{{ addslashes($k['question']) }}" data-answer="{{ addslashes($k['answer']) }}" data-keywords="{{ addslashes($k['keywords'] ?? '') }}" data-category="{{ $k['category'] ?? 'general' }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                            @endadmin_can
                                            @admin_can('delete_content')
                                            <button class="btn btn-outline-danger btn-delete" data-id="{{ $k['id'] }}" title="Delete"><i class="bi bi-trash"></i></button>
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-unmatched" class="tab-content">
                <div class="table-card">
                    <div class="table-card-header">
                        <h6><i class="bi bi-question-circle-fill me-2 text-danger"></i>Unmatched Queries - Train Your Bot</h6>
                        <small class="text-muted">Add these as new knowledge items to improve accuracy</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Query (what users asked)</th>
                                    <th>Session</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unmatched as $u)
                                <tr class="{{ $u['resolved'] ? 'resolved-row' : 'unmatched-row' }}" data-unmatched-id="{{ $u['id'] }}">
                                    <td class="unmatched-query" title="{{ $u['query'] }}">{{ $u['query'] }}</td>
                                    <td><small class="text-muted">{{ Str::limit($u['session_id'] ?? '', 12) }}</small></td>
                                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</small></td>
                                    <td>
                                        @if($u['resolved'])
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @admin_can('create_content')
                                            <button class="btn btn-sm btn-outline-primary btn-train" data-query="{{ addslashes($u['query']) }}">
                                                <i class="bi bi-plus me-1"></i>Train Bot
                                            </button>
                                            @endadmin_can
                                            @admin_can('edit_content')
                                            @if(!$u['resolved'])
                                                <button class="btn btn-sm btn-outline-success btn-resolve" data-id="{{ $u['id'] }}">
                                                    <i class="bi bi-check2 me-1"></i>Mark Resolved
                                                </button>
                                            @endif
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No unmatched queries. Your bot is doing great.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning mb-0">
                <i class="fas fa-lock me-2"></i>You do not have permission to view chatbot management.
            </div>
            @endadmin_can
        </main>
    </div>

    @admin_can('create_content')
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Knowledge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question / Topic <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="question" required placeholder="e.g. How do I track my Barangay Clearance request?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="answer" rows="5" required placeholder="Provide a detailed, helpful answer..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Keywords <small class="text-muted">(comma-separated)</small></label>
                                <input type="text" class="form-control" name="keywords" placeholder="e.g. clearance, reference id, request status">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select" name="category">
                                    <option value="general">General</option>
                                    <option value="clearance">Barangay Clearance</option>
                                    <option value="residency">Residency</option>
                                    <option value="indigency">Indigency</option>
                                    <option value="blotter">Incident Reports</option>
                                    <option value="tracking">Request Tracking</option>
                                    <option value="announcements">Announcements</option>
                                    <option value="events">Events</option>
                                    <option value="projects">Projects</option>
                                    <option value="portal">Portal Account</option>
                                    <option value="support">Support</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-add"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can

    @admin_can('edit_content')
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Knowledge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question *</label>
                            <input type="text" class="form-control" name="question" id="edit-question" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Answer *</label>
                            <textarea class="form-control" name="answer" id="edit-answer" rows="5" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Keywords</label>
                                <input type="text" class="form-control" name="keywords" id="edit-keywords">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select" name="category" id="edit-category">
                                    <option value="general">General</option>
                                    <option value="clearance">Barangay Clearance</option>
                                    <option value="residency">Residency</option>
                                    <option value="indigency">Indigency</option>
                                    <option value="blotter">Incident Reports</option>
                                    <option value="tracking">Request Tracking</option>
                                    <option value="announcements">Announcements</option>
                                    <option value="events">Events</option>
                                    <option value="projects">Projects</option>
                                    <option value="portal">Portal Account</option>
                                    <option value="support">Support</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-edit"><i class="bi bi-save me-1"></i>Update</button>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script>
        (function () {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const chatbotRoutes = {
                knowledgeStore: @json(route('admin.chatbot.knowledge.store')),
                knowledgeUpdateTemplate: @json(route('admin.chatbot.knowledge.update', ['id' => '__ID__'])),
                knowledgeDeleteTemplate: @json(route('admin.chatbot.knowledge.delete', ['id' => '__ID__'])),
                unmatchedResolveTemplate: @json(route('admin.chatbot.unmatched.resolve', ['id' => '__ID__']))
            };

            document.querySelectorAll('.tab-link[data-tab]').forEach(link => {
                link.addEventListener('click', function () {
                    const tab = this.dataset.tab;
                    document.querySelectorAll('.tab-link[data-tab]').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('tab-' + tab).classList.add('active');
                });
            });

            const allowedCategories = ['general', 'clearance', 'residency', 'indigency', 'blotter', 'tracking', 'announcements', 'events', 'projects', 'portal', 'support'];

            function setFieldError(input, message) {
                if (!input) {
                    return;
                }
                input.classList.add('is-invalid');
                let feedback = input.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    input.insertAdjacentElement('afterend', feedback);
                }
                feedback.textContent = message;
            }

            function clearFieldError(input) {
                if (!input) {
                    return;
                }
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }

            function clearFormErrors(form) {
                if (!form) {
                    return;
                }
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }

            function validateKnowledgeForm(form) {
                if (!form) {
                    return false;
                }

                clearFormErrors(form);

                const question = form.querySelector('[name="question"]');
                const answer = form.querySelector('[name="answer"]');
                const keywords = form.querySelector('[name="keywords"]');
                const category = form.querySelector('[name="category"]');

                let valid = true;

                const qVal = (question?.value || '').trim();
                if (!qVal) {
                    setFieldError(question, 'Question is required.');
                    valid = false;
                } else if (qVal.length < 6) {
                    setFieldError(question, 'Question must be at least 6 characters.');
                    valid = false;
                } else if (qVal.length > 500) {
                    setFieldError(question, 'Question must not exceed 500 characters.');
                    valid = false;
                }

                const aVal = (answer?.value || '').trim();
                if (!aVal) {
                    setFieldError(answer, 'Answer is required.');
                    valid = false;
                } else if (aVal.length < 10) {
                    setFieldError(answer, 'Answer must be at least 10 characters.');
                    valid = false;
                } else if (aVal.length > 5000) {
                    setFieldError(answer, 'Answer must not exceed 5000 characters.');
                    valid = false;
                }

                const kVal = (keywords?.value || '').trim();
                if (kVal.length > 500) {
                    setFieldError(keywords, 'Keywords must not exceed 500 characters.');
                    valid = false;
                } else if (kVal && !/^[a-zA-Z0-9,\s\-]+$/.test(kVal)) {
                    setFieldError(keywords, 'Keywords can only contain letters, numbers, commas, spaces, and hyphens.');
                    valid = false;
                }

                const cVal = (category?.value || '').trim();
                if (!allowedCategories.includes(cVal)) {
                    setFieldError(category, 'Please select a valid category.');
                    valid = false;
                }

                return valid;
            }

            function applyServerErrors(form, errors) {
                if (!form || !errors) {
                    return;
                }
                Object.entries(errors).forEach(([field, messages]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input && Array.isArray(messages) && messages.length) {
                        setFieldError(input, messages[0]);
                    }
                });
            }

            async function api(method, url, body) {
                const opts = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                };
                if (body) {
                    opts.body = JSON.stringify(body);
                }
                const res = await fetch(url, opts);
                const payload = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const error = new Error(payload.message || `HTTP ${res.status}`);
                    error.status = res.status;
                    error.payload = payload;
                    throw error;
                }
                return payload;
            }

            function withId(template, id) {
                return template.replace('__ID__', String(id));
            }

            function formData(formEl) {
                const fd = new FormData(formEl);
                return Object.fromEntries(fd.entries());
            }

            function toast(msg, type = 'success') {
                const el = document.createElement('div');
                el.className = `alert alert-${type} position-fixed shadow`;
                el.style.cssText = 'top:20px;right:20px;z-index:99999;min-width:280px;';
                el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'x-circle'} me-2"></i>${msg}`;
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 3500);
            }

            ['add-form', 'edit-form', 'quick-add-form'].forEach(formId => {
                const form = document.getElementById(formId);
                if (!form) {
                    return;
                }
                ['question', 'answer', 'keywords', 'category'].forEach(fieldName => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.addEventListener('input', () => clearFieldError(input));
                        input.addEventListener('change', () => clearFieldError(input));
                    }
                });
            });

            const quickAddForm = document.getElementById('quick-add-form');
            if (quickAddForm) {
            quickAddForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (!validateKnowledgeForm(this)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const data = formData(this);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge added!');
                        this.reset();
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(this, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            const saveAddBtn = document.getElementById('save-add');
            if (saveAddBtn) {
            saveAddBtn.addEventListener('click', async function () {
                const addForm = document.getElementById('add-form');
                if (!validateKnowledgeForm(addForm)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const data = formData(addForm);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge saved!');
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(addForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit-id').value = this.dataset.id;
                    document.getElementById('edit-question').value = this.dataset.question;
                    document.getElementById('edit-answer').value = this.dataset.answer;
                    document.getElementById('edit-keywords').value = this.dataset.keywords;
                    document.getElementById('edit-category').value = this.dataset.category;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
            });

            const saveEditBtn = document.getElementById('save-edit');
            if (saveEditBtn) {
            saveEditBtn.addEventListener('click', async function () {
                const editForm = document.getElementById('edit-form');
                if (!validateKnowledgeForm(editForm)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const id = document.getElementById('edit-id').value;
                const data = formData(editForm);
                try {
                    const res = await api('PUT', withId(chatbotRoutes.knowledgeUpdateTemplate, id), data);
                    if (res.success) {
                        toast('Updated!');
                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(editForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async function () {
                    if (!confirm('Delete this knowledge item? This cannot be undone.')) {
                        return;
                    }
                    const id = this.dataset.id;
                    try {
                        const res = await api('DELETE', withId(chatbotRoutes.knowledgeDeleteTemplate, id));
                        if (res.success) {
                            this.closest('tr').remove();
                            toast('Deleted.');
                        } else {
                            toast('Delete failed.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
                    }
                });
            });

            document.querySelectorAll('.btn-train').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('add-form').querySelector('[name="question"]').value = this.dataset.query;
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                });
            });

            document.querySelectorAll('.btn-resolve').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id = this.dataset.id;
                    try {
                        const res = await api('PATCH', withId(chatbotRoutes.unmatchedResolveTemplate, id));
                        if (res.success) {
                            const row = this.closest('tr');
                            if (row) {
                                row.classList.remove('unmatched-row');
                                row.classList.add('resolved-row');
                                const statusCell = row.children[3];
                                if (statusCell) {
                                    statusCell.innerHTML = '<span class="badge bg-success-subtle text-success">Resolved</span>';
                                }
                            }
                            this.remove();
                            toast('Marked as resolved.');
                        } else {
                            toast('Unable to mark as resolved.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
                    }
                });
            });

            const kbSearch = document.getElementById('kb-search');
            if (kbSearch) {
                kbSearch.addEventListener('input', function () {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll('#knowledge-tbody tr').forEach(tr => {
                        const text = tr.textContent.toLowerCase();
                        tr.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }
        })();
    </script>
</body>
</html>

