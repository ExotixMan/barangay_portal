<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backup Settings - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

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
            --warning: #ed6c02;
            --info: #0288d1;
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

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1e293b;
            padding: 0.5rem;
            border-radius: 8px;
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

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
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

        .table-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .table-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0;
            min-width: 860px;
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

        .backup-time {
            display: flex;
            flex-direction: column;
            gap: 2px;
            line-height: 1.2;
        }

        .backup-time-main {
            font-weight: 600;
            color: #1f2937;
        }

        .backup-time-sub {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .restore-upload-row .form-control {
            min-height: 46px;
        }

        .restore-upload-row .btn {
            height: 46px;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .schedule-card {
            border: 1px solid #b6dcff;
            background: #eef7ff;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }

        .schedule-title {
            color: #0b63ad;
            font-weight: 700;
        }

        .empty-state {
            padding: 2.25rem;
            text-align: center;
            color: #6c757d;
        }

        .mobile-only {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: inline-flex;
            }

            .profile-badge span {
                display: none;
            }

            .mobile-only {
                display: inline-flex;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .restore-upload-row .btn {
                margin-top: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <div class="sidebar" id="sidebar" onclick="handleSidebarClick(event)">
        <div class="brand">
            <div class="brand-left">
                <img class="logo" src="{{ asset('Images/logo.png') }}" alt="Logo">
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
        <a href="{{ route('admin.chatbot.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
        @endadmin_can

        @admin_can('view_backup')
        <a href="{{ route('admin.backup.index') }}" class="active" onclick="handleLinkClick(event, this)">
            <i class="fas fa-database"></i>
            <span>Backup Settings</span>
        </a>
        @endadmin_can
    </div>

    <div class="main-content" id="mainContent">
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <i class="fas fa-database me-2 d-none d-sm-inline" style="color: var(--primary);"></i>
                    Backup Settings
                </h1>
            </div>
            <div class="profile-badge dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('restore_status') && session('restore_message'))
                <div id="restoreResultPanel" class="alert {{ session('restore_status') === 'success' ? 'alert-success' : 'alert-danger' }} border-2 shadow-sm mb-4" role="alert">
                    <div class="d-flex flex-column gap-1">
                        <div class="fw-bold">
                            <i class="fas {{ session('restore_status') === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' }} me-2"></i>
                            Restore Result
                        </div>
                        <div>{{ session('restore_message') }}</div>
                        <small class="opacity-75">Time: {{ session('restore_time') }}</small>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h5 class="mb-1">System Backups</h5>
                    <div class="text-muted">Create and manage full backup archives.</div>
                </div>
                <form method="POST" action="{{ route('admin.backup.store') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-database me-2"></i>Create New Backup
                    </button>
                </form>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-2">Restore Backup Archive</h6>
                    <p class="text-muted mb-3">
                        Upload a backup zip and restore in one click. This will overwrite current database and backup-managed files.
                    </p>

                    <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data" onsubmit="return validateUploadAndConfirmRestore(event, this);">
                        @csrf
                        <div class="row g-2 align-items-center restore-upload-row">
                            <div class="col-12 col-md-8 col-lg-9">
                                <input type="file" name="backup_archive" id="restoreArchiveInput" accept=".zip" class="form-control" required>
                                <div class="form-text">Accepted: .zip, max 500 MB.</div>
                                <div id="restoreValidationMessage" class="form-text"></div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3 d-grid align-self-stretch">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-rotate-left me-2"></i>Upload and Restore
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-label">Total Backups</div>
                        <div class="stat-number">{{ $backups->count() }}</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-label">Backup Type</div>
                        <div class="stat-number" style="font-size:1.25rem;">Full Archive</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-label">Storage Path</div>
                        <div class="stat-number" style="font-size:1.1rem;">storage/app/backups</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-label">Included Data</div>
                        <div class="stat-number" style="font-size:1rem;">DB + uploads + docs</div>
                    </div>
                </div>
            </div>

            <div class="schedule-card mb-4">
                <div class="d-flex align-items-start gap-2">
                    <i class="fas fa-clock mt-1" style="color:#0b63ad;"></i>
                    <div>
                        <div class="schedule-title">Automatic Weekly Backup Enabled</div>
                        <div>Scheduled every Sunday at 12:00 AM (Asia/Manila).</div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <h6 class="mb-0">Available Backups</h6>
                    <span class="badge text-bg-secondary">{{ $backups->count() }}</span>
                </div>

                @if($backups->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-folder-open fa-2x mb-2 text-muted"></i>
                        <div>No backups found yet.</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Last Modified ({{ $displayTimezone ?? 'Asia/Manila' }})</th>
                                    <th>Size</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                    <tr>
                                        <td class="fw-semibold">{{ $backup['name'] }}</td>
                                        <td>
                                            <div class="backup-time">
                                                <span class="backup-time-main">{{ $backup['modified_at'] }}</span>
                                                <span class="backup-time-sub">{{ $backup['modified_relative'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                            <form method="POST" action="{{ route('admin.backup.restore') }}" class="d-inline" onsubmit="return confirmRestore(event);">
                                                @csrf
                                                <input type="hidden" name="file" value="{{ $backup['name'] }}">
                                                <button type="submit" class="btn btn-sm btn-outline-warning me-2">
                                                    <i class="fas fa-rotate-left me-1"></i>Restore
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" class="d-inline" onsubmit="return confirmDelete(event, 'Delete this backup file?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script>
        function confirmDelete(event, message) {
            if (!confirm(message || 'Are you sure?')) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function confirmRestore(event) {
            var message = 'This will overwrite current database and files with backup content. Continue restore?';
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function validateUploadAndConfirmRestore(event, form) {
            var fileInput = form.querySelector('input[name="backup_archive"]');
            var validationMessage = document.getElementById('restoreValidationMessage');

            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Please select a backup zip file first.';
                }
                alert('Please select a backup zip file first.');
                return false;
            }

            var file = fileInput.files[0];
            var maxBytes = 500 * 1024 * 1024;
            var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
            var isZipByName = /\.zip$/i.test(file.name || '');

            if (!isZipByType && !isZipByName) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                }
                alert('Invalid file type. Please upload a .zip backup file.');
                return false;
            }

            if (file.size > maxBytes) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                }
                alert('Backup file is too large. Maximum allowed size is 500 MB.');
                return false;
            }

            if (validationMessage) {
                validationMessage.className = 'form-text text-success';
                validationMessage.textContent = 'File is valid and ready to restore.';
            }

            return confirmRestore(event);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var fileInput = document.getElementById('restoreArchiveInput');
            var validationMessage = document.getElementById('restoreValidationMessage');
            var restoreResultPanel = document.getElementById('restoreResultPanel');

            if (restoreResultPanel) {
                restoreResultPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (!fileInput || !validationMessage) {
                return;
            }

            fileInput.addEventListener('change', function () {
                if (!fileInput.files || fileInput.files.length === 0) {
                    validationMessage.className = 'form-text';
                    validationMessage.textContent = '';
                    return;
                }

                var file = fileInput.files[0];
                var maxBytes = 500 * 1024 * 1024;
                var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
                var isZipByName = /\.zip$/i.test(file.name || '');

                if (!isZipByType && !isZipByName) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                    return;
                }

                if (file.size > maxBytes) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                    return;
                }

                validationMessage.className = 'form-text text-success';
                validationMessage.textContent = 'File is valid and ready to restore.';
            });

            setTimeout(function () {
                document.querySelectorAll('.alert').forEach(function (alertEl) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
                    bsAlert.close();
                });
            }, 6000);
        });

    </script>
</body>
</html>

