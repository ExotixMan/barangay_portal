<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Dashboard - Admin</title>
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
        :root {
            --primary: #d32f2f;
            --primary-light: #ffebee;
            --primary-dark: #b71c1c;
            --success: #2e7d32;
            --warning: #ed6c02;
            --info: #0288d1;
            --gray-bg: #f4f4f4;
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
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.023);
            transition: transform 0.2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.1);
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            color: black;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-change {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        /* Chart Containers */
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #e0e0e0;
            transition: all 0.2s ease;
            height: 100%;
            position: relative;
        }

        .chart-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-title i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .chart-container {
            position: relative;
            height: 280px;
            width: 100%;
            margin-top: 0.5rem;
        }

        /* Summary Badges */
        .summary-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .trend-up {
            color: var(--success);
            font-size: 0.85rem;
        }

        .trend-down {
            color: var(--warning);
            font-size: 0.85rem;
        }

        /* Forecast Summary Cards */
        .forecast-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .forecast-summary small {
            opacity: 0.9;
            font-size: 0.75rem;
        }

        .forecast-summary .value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Section Headers */
        .section-header {
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .section-header i {
            color: var(--primary);
            font-size: 1.3rem;
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

        #userDropdown{
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.8rem;
            }

            .chart-container {
                height: 220px;
            }

            .profile-badge span {
                display: none;
            }

            .profile-badge {
                padding: 0.5rem;
                border-radius: 50%;
            }

            .section-header h3 {
                font-size: 1.1rem;
            }
        }

        /* Tooltip Customization */
        .chart-tooltip {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        /* Permission-based visibility */
        .permission-hidden {
            display: none !important;
        }
        
        /* Role badges */
        .role-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* No permission message */
        .no-permission-card {
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 10px;
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
        
        .no-permission-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }

        .chart-loading {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            color: #64748b;
        }

        .chart-loading::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            border-top-color: var(--primary);
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .chart-alert {
            margin: 0 0 1rem 0;
            border-radius: 10px;
        }

        .dashboard-controls {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.6rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .refresh-btn {
            border: 1px solid var(--primary);
            background: #fff;
            color: var(--primary);
            border-radius: 8px;
            padding: 0.45rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .refresh-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        .refresh-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .auto-toggle-btn {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #475569;
            border-radius: 8px;
            padding: 0.45rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .auto-toggle-btn.auto-on {
            border-color: #2e7d32;
            color: #2e7d32;
            background: #f2fbf4;
        }

        .auto-toggle-btn.auto-off {
            border-color: #ed6c02;
            color: #ed6c02;
            background: #fff8f1;
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

        {{-- Dashboard Link --}}
        @if(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast']))
        <a href="{{ route('admin.dashboard.index') }}" class="active" onclick="handleLinkClick(event, this)">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        @endif

        <div class="menu-section">Administrative</div>
        
        {{-- Registry Dropdown --}}
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
            <a href="{{ route('admin.residents.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-user"></i> <span>Residents</span>
            </a>
            @endadmin_can
            
            @admin_can('view_residency')
            <a href="{{ route('admin.residency.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-alt"></i> <span>Residency Applications</span>
            </a>
            @endadmin_can
            
            @admin_can('view_indigency')
            <a href="{{ route('admin.indigency.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-invoice"></i> <span>Indigency</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Legal</div>
        
        {{-- Records Dropdown --}}
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
            <a href="{{ route('admin.clearance.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-file-contract"></i> <span>Clearances</span>
            </a>
            @endadmin_can
            
            @admin_can('view_blotter')
            <a href="{{ route('admin.blotter.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-book"></i> <span>Incident Reports</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">Community</div>
        
        {{-- Community Dropdown --}}
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
            <a href="{{ route('admin.announcements.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-bullhorn"></i> <span>Announcements</span>
            </a>
            @endadmin_can
            
            @admin_can('view_events')
            <a href="{{ route('admin.events.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-calendar"></i> <span>Events</span>
            </a>
            @endadmin_can
            
            @admin_can('view_projects')
            <a href="{{ route('admin.projects.index') }}" onclick="handleSubmenuClick(event)">
                <i class="fas fa-project-diagram"></i> <span>Projects</span>
            </a>
            @endadmin_can
        </div>
        @endif

        <div class="menu-section">System</div>
        
        {{-- Users Link --}}
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
        @endadmin_can    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Barangay Dashboard</h1>
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="dashboard-controls">
                <button type="button" class="refresh-btn" id="refreshNowBtn" title="Refresh dashboard now">
                    <i class="fas fa-rotate-right"></i>
                    <span id="refreshNowBtnText">Refresh Now</span>
                </button>
                @admin_can('view_forecast')
                <button type="button" class="refresh-btn" id="refreshForecastBtn" title="Regenerate forecast from latest database records">
                    <i class="fas fa-bolt"></i>
                    <span id="refreshForecastBtnText">Refresh Forecast</span>
                </button>
                @endadmin_can
            </div>

            {{-- Stats Cards (always visible - they contain no sensitive data) --}}
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-users me-2"></i>Total Residents
                        </div>
                        <div class="stat-number" id="totalResidents">-</div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up text-success me-1"></i>Registered residents
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-clock me-2"></i>Pending Requests
                        </div>
                        <div class="stat-number" id="pendingRequests">-</div>
                        <div class="stat-change">
                            <span class="summary-badge">Needs attention</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-exclamation-triangle me-2"></i>Open Reports
                        </div>
                        <div class="stat-number" id="openReports">-</div>
                        <div class="stat-change">
                            <span class="summary-badge">Under investigation</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-calendar-check me-2"></i>Upcoming Events
                        </div>
                        <div class="stat-number" id="upcomingEvents">-</div>
                        <div class="stat-change">
                            <i class="fas fa-calendar me-1"></i>Scheduled activities
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Overview Charts - Only show if user has permission --}}
            @if(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast']))
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-bar"></i>
                            Daily Service Requests
                            <span class="summary-badge ms-auto">Last 7 days</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="dailyRequestsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Request Types Distribution
                            <span class="summary-badge ms-auto">Current</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="requestsTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Applications Forecast Section - Only with view_forecast permission --}}
            @admin_can('view_forecast')
            <div class="section-header">
                <i class="fas fa-file-signature"></i>
                <h3>Applications Forecast (Next 30 Days)</h3>
                <span class="summary-badge ms-auto">Predictions based on historical data</span>
            </div>
            <div id="applicationsForecast" class="row g-3 g-lg-4 mb-4"></div>
            @endadmin_can

            {{-- Incident Reports & Announcements - Conditional sections --}}
            <div class="row g-3 g-lg-4 mb-4">
                @admin_can('view_blotter')
                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-book"></i>
                            Incident Reports Forecast
                            <span class="summary-badge ms-auto">Trending</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="blotterForecastChart"></canvas>
                        </div>
                        <div class="mt-3 small text-muted" id="blotterSummary"></div>
                    </div>
                </div>
                @endadmin_can

                @admin_can('view_announcements')
                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-bullhorn"></i>
                            Announcements Forecast
                            <span class="summary-badge ms-auto">Stable</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="announcementsForecastChart"></canvas>
                        </div>
                        <div class="mt-3 small text-muted" id="announcementsSummary"></div>
                    </div>
                </div>
                @endadmin_can
            </div>

            {{-- Community Insights Section - Show only if user has any community permission --}}
            @if(auth('admin')->user()->hasAnyPermission(['view_events', 'view_projects', 'view_residents']))
            <div class="section-header">
                <i class="fas fa-users"></i>
                <h3>Community Insights</h3>
            </div>
            <div class="row g-3 g-lg-4">
                @admin_can('view_events')
                <div class="col-md-4">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-calendar"></i>
                            Events
                        </div>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="eventsChart"></canvas>
                        </div>
                        <div class="mt-2 text-center" id="eventsSummary"></div>
                    </div>
                </div>
                @endadmin_can

                @admin_can('view_projects')
                <div class="col-md-4">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-progress"></i>
                            Projects Status
                        </div>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="projectsChart"></canvas>
                        </div>
                        <div class="mt-2 text-center" id="projectsSummary"></div>
                    </div>
                </div>
                @endadmin_can

                @admin_can('view_residents')
                <div class="col-md-4">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-line"></i>
                            Age Distribution
                        </div>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="ageChart"></canvas>
                        </div>
                        <div class="mt-2 text-center" id="ageSummary"></div>
                    </div>
                </div>
                @endadmin_can
            </div>
            @endif

            {{-- Dynamic Forecast Charts Container - Only with view_forecast permission --}}
            @admin_can('view_forecast')
            <div id="autoForecastCharts" class="row g-3 g-lg-4 mt-2"></div>
            @endadmin_can
            
            {{-- No Permission Message - Show if user has no dashboard permissions --}}
            @unless(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast', 'view_blotter', 'view_announcements', 'view_events', 'view_projects', 'view_residents']))
            <div class="no-permission-card">
                <i class="fas fa-lock"></i>
                <h4>No Dashboard Access</h4>
                <p>You don't have permission to view any dashboard data.</p>
                <p class="small">Contact your administrator if you need access.</p>
            </div>
            @endunless
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    
    <script>
        // Store forecast data globally for access
        let forecastData = null;
        const chartInstances = {};
        const STATS_REFRESH_MS = 120000;
        const FORECAST_REFRESH_MS = 180000;
        let statsRefreshTimer = null;
        let forecastRefreshTimer = null;
        let isRefreshingStats = false;
        let isManualRefreshInProgress = false;
        let isRefreshingForecastCommand = false;
        let autoRefreshEnabled = false;

        function parseForecastDate(dateStr) {
            // Supports format from forecast.json, e.g. "Mar 23, 2026"
            const parsed = new Date(dateStr);
            return Number.isNaN(parsed.getTime()) ? null : parsed;
        }

        function sortForecastEntries(forecastMap) {
            return Object.entries(forecastMap || {})
                .map(([date, value]) => ({
                    date,
                    dateObj: parseForecastDate(date),
                    value: Number(value) || 0,
                }))
                .filter((item) => item.dateObj)
                .sort((a, b) => a.dateObj - b.dateObj);
        }

        function shortDate(dateStr) {
            const dateObj = parseForecastDate(dateStr);
            if (!dateObj) {
                return dateStr;
            }
            return dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }

        function formatPhtDateTime(dateInput) {
            const value = new Date(dateInput);
            if (Number.isNaN(value.getTime())) {
                return null;
            }
            const formatted = value.toLocaleString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
            return `${formatted} PHT`;
        }

        function renderChart(id, config) {
            const ctx = document.getElementById(id)?.getContext('2d');
            if (!ctx) {
                return null;
            }
            if (chartInstances[id]) {
                chartInstances[id].destroy();
            }
            chartInstances[id] = new Chart(ctx, config);
            return chartInstances[id];
        }

        function setChartLoadingState(isLoading, message = '') {
            const firstChartTitle = document.querySelector('#dailyRequestsChart')?.closest('.chart-card')?.querySelector('.summary-badge');
            if (!firstChartTitle) {
                return;
            }
            if (isLoading) {
                firstChartTitle.innerHTML = '<span class="chart-loading">Loading forecast</span>';
            } else if (message) {
                firstChartTitle.textContent = message;
            } else {
                firstChartTitle.textContent = 'Last 7 days';
            }
        }

        async function loadForecastData() {
            setChartLoadingState(true);
            try {
                const response = await fetch("{{ route('admin.dashboard.forecast') }}");
                const data = await response.json();

                if (data.error) {
                    console.error("Forecast error:", data.error);
                    setChartLoadingState(false, 'Forecast unavailable');
                    return;
                }

                forecastData = data;
                
                // Build charts based on permissions (using Blade to determine which JS to run)
                @if(auth('admin')->user()->hasPermission('view_forecast'))
                    try { buildApplicationsCharts(data.applications); } catch (e) { console.error('Applications chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasPermission('view_blotter'))
                    try { buildBlotterChart(data.blotter_reports); } catch (e) { console.error('Blotter chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasPermission('view_announcements'))
                    try { buildAnnouncementsChart(data.announcements); } catch (e) { console.error('Announcements chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasPermission('view_events'))
                    try { buildEventsChart(data.events); } catch (e) { console.error('Events chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasPermission('view_projects'))
                    try { buildProjectsChart(data.projects); } catch (e) { console.error('Projects chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasPermission('view_residents'))
                    try { buildAgeChart(data.residents?.age_distribution); } catch (e) { console.error('Age chart error:', e); }
                @endif

                @if(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast']))
                    try { buildRequestTypeChart(data); } catch (e) { console.error('Request types chart error:', e); }
                    try { buildDailyRequestsChart(data); } catch (e) { console.error('Daily requests chart error:', e); }
                @endif

                setChartLoadingState(false);

            } catch (error) {
                console.error("Error loading forecast:", error);
                setChartLoadingState(false, 'Failed to load');
            }
        }

        async function loadLiveStats() {
            if (isRefreshingStats) {
                return;
            }

            isRefreshingStats = true;
            try {
                const response = await fetch("{{ route('admin.dashboard.live_stats') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const payload = await response.json();
                if (payload?.stats) {
                    initializeStaticStats(payload.stats);
                }

                if (payload?.serverTime) {
                    const liveSyncEl = document.getElementById('liveSyncTimeText');
                    const formattedServerTime = formatPhtDateTime(payload.serverTime);
                    if (liveSyncEl && formattedServerTime) {
                        liveSyncEl.textContent = formattedServerTime;
                    }
                }

                if (payload?.forecastLastUpdated) {
                    const lastUpdatedEl = document.getElementById('forecastLastUpdatedText');
                    if (lastUpdatedEl) {
                        lastUpdatedEl.textContent = payload.forecastLastUpdated;
                    }
                }
            } catch (error) {
                console.error('Realtime stats refresh failed:', error);
            } finally {
                isRefreshingStats = false;
            }
        }

        async function refreshDashboardNow() {
            if (isManualRefreshInProgress) {
                return;
            }

            const btn = document.getElementById('refreshNowBtn');
            const btnText = document.getElementById('refreshNowBtnText');
            isManualRefreshInProgress = true;

            if (btn) {
                btn.disabled = true;
            }
            if (btnText) {
                btnText.textContent = 'Refreshing...';
            }

            try {
                await Promise.allSettled([
                    loadLiveStats(),
                    loadForecastData()
                ]);
            } finally {
                if (btnText) {
                    btnText.textContent = 'Refresh Now';
                }
                if (btn) {
                    btn.disabled = false;
                }
                isManualRefreshInProgress = false;
            }
        }

        async function refreshForecastNow() {
            if (isRefreshingForecastCommand) {
                return;
            }

            const btn = document.getElementById('refreshForecastBtn');
            const btnText = document.getElementById('refreshForecastBtnText');

            isRefreshingForecastCommand = true;
            if (btn) {
                btn.disabled = true;
            }
            if (btnText) {
                btnText.textContent = 'Running...';
            }

            try {
                const response = await fetch("{{ route('admin.dashboard.refresh_forecast') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });

                const payload = await response.json();
                if (!response.ok || !payload?.success) {
                    throw new Error(payload?.message || 'Failed to refresh forecast.');
                }

                await Promise.allSettled([
                    loadForecastData(),
                    loadLiveStats()
                ]);
            } catch (error) {
                console.error('Forecast refresh failed:', error);
                alert(error?.message || 'Failed to refresh forecast.');
            } finally {
                if (btnText) {
                    btnText.textContent = 'Refresh Forecast';
                }
                if (btn) {
                    btn.disabled = false;
                }
                isRefreshingForecastCommand = false;
            }
        }

        function updateAutoRefreshButtonState() {
            const btn = document.getElementById('autoRefreshToggleBtn');
            const text = document.getElementById('autoRefreshToggleText');
            if (!btn || !text) {
                return;
            }

            if (autoRefreshEnabled) {
                btn.classList.remove('auto-off');
                btn.classList.add('auto-on');
                text.textContent = 'Auto Refresh: ON';
            } else {
                btn.classList.remove('auto-on');
                btn.classList.add('auto-off');
                text.textContent = 'Auto Refresh: OFF';
            }
        }

        function stopRealtimeDashboardRefresh() {
            if (statsRefreshTimer) {
                clearInterval(statsRefreshTimer);
                statsRefreshTimer = null;
            }
            if (forecastRefreshTimer) {
                clearInterval(forecastRefreshTimer);
                forecastRefreshTimer = null;
            }
        }

        function startRealtimeDashboardRefresh() {
            stopRealtimeDashboardRefresh();

            if (!autoRefreshEnabled) {
                return;
            }

            statsRefreshTimer = setInterval(() => {
                if (document.hidden) {
                    return;
                }
                loadLiveStats();
            }, STATS_REFRESH_MS);

            forecastRefreshTimer = setInterval(() => {
                if (document.hidden) {
                    return;
                }
                loadForecastData();
            }, FORECAST_REFRESH_MS);
        }

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            updateAutoRefreshButtonState();

            if (autoRefreshEnabled) {
                startRealtimeDashboardRefresh();
            } else {
                stopRealtimeDashboardRefresh();
            }
        }

            function initializeStaticStats(stats) {
                // Display initial stats immediately from server data
                const totalResidentsEl = document.getElementById('totalResidents');
                if (totalResidentsEl) {
                    totalResidentsEl.innerText = (stats.totalResidents || 0).toLocaleString();
                }

                const pendingEl = document.getElementById('pendingRequests');
                if (pendingEl) {
                    pendingEl.innerText = stats.pendingRequests || 0;
                }

                const openReportsEl = document.getElementById('openReports');
                if (openReportsEl) {
                    openReportsEl.innerText = stats.openReports || 0;
                }

                const upcomingEventsEl = document.getElementById('upcomingEvents');
                if (upcomingEventsEl) {
                    upcomingEventsEl.innerText = stats.upcomingEvents || 0;
                }
            }

        function buildApplicationsCharts(applications) {
            const container = document.getElementById('applicationsForecast');
            if (!container || !applications) return;
            
            container.innerHTML = '';

            // Create a card for each application type
            Object.entries(applications).forEach(([key, value]) => {
                if (value.daily_forecast) {
                    const entries = sortForecastEntries(value.daily_forecast).slice(0, 15);
                    if (!entries.length) {
                        return;
                    }
                    const dates = entries.map((item) => item.date);
                    const values = entries.map((item) => item.value);
                    
                    // Calculate summary
                    const avgForecast = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);
                    const maxForecast = Math.max(...values).toFixed(1);
                    const trend = values[values.length - 1] > values[0] ? 'up' : 'down';
                    
                    const col = document.createElement('div');
                    col.className = 'col-md-4';
                    col.innerHTML = `
                        <div class="chart-card">
                            <div class="chart-title">
                                <i class="fas fa-file-alt"></i>
                                ${formatModuleName(key)}
                                <span class="summary-badge ms-auto">30-day forecast</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <small class="text-muted">Daily average</small>
                                    <div class="fw-bold">${avgForecast}</div>
                                </div>
                                <div>
                                    <small class="text-muted">Peak</small>
                                    <div class="fw-bold">${maxForecast}</div>
                                </div>
                                <div>
                                    <small class="text-muted">Trend</small>
                                    <div class="${trend === 'up' ? 'trend-up' : 'trend-down'}">
                                        <i class="fas fa-arrow-${trend}"></i> ${trend}
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container" style="height: 180px;">
                                <canvas id="chart-${key}"></canvas>
                            </div>
                            <div class="mt-2 small text-muted">
                                <i class="fas fa-info-circle"></i>
                                Based on ${Number.isFinite(Number(value.historical_count)) ? Number(value.historical_count) : Object.values(value.status_distribution || {}).reduce((a, b) => a + b, 0)} historical applications
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(col);
                    
                    // Create chart
                    setTimeout(() => {
                        renderChart(`chart-${key}`, {
                                type: 'line',
                                data: {
                                    labels: dates.map(shortDate),
                                    datasets: [{
                                        label: 'Forecast',
                                        data: values,
                                        borderColor: '#d32f2f',
                                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                                        borderWidth: 2,
                                        pointRadius: 3,
                                        pointHoverRadius: 5,
                                        tension: 0.4,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: { 
                                            backgroundColor: 'rgba(0,0,0,0.8)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff'
                                        }
                                    },
                                    scales: {
                                        x: { 
                                            grid: { display: false },
                                            ticks: { maxRotation: 45, minRotation: 45 }
                                        },
                                        y: { 
                                            beginAtZero: true,
                                            grid: { color: '#f0f0f0' },
                                            ticks: { stepSize: 1 }
                                        }
                                    }
                                }
                            });
                    }, 100);
                }
            });
        }

        function buildBlotterChart(blotter) {
            if (!blotter?.daily_forecast) return;
            
            const entries = sortForecastEntries(blotter.daily_forecast).slice(0, 20);
            const dates = entries.map((item) => item.date);
            const values = entries.map((item) => item.value);

            renderChart('blotterForecastChart', {
                type: 'line',
                data: {
                    labels: dates.map(shortDate),
                    datasets: [{
                        label: 'Predicted Incidents',
                        data: values,
                        borderColor: '#d32f2f',
                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#d32f2f',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.raw.toFixed(1)} reports expected`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f0f0f0' },
                            title: { display: true, text: 'Number of Reports' }
                        }
                    }
                }
            });
            
            // Update summary using parsed forecast values
            const total = values.reduce((sum, value) => sum + value, 0);
            const avg = values.length ? (total / values.length) : 0;
            const summaryEl = document.getElementById('blotterSummary');
            if (summaryEl) {
                summaryEl.innerHTML = 
                    `<i class="fas fa-chart-line me-1"></i> Expected total: ${total.toFixed(1)} incidents | Daily average: ${avg.toFixed(1)}`;
            }
        }

        function buildAnnouncementsChart(announcements) {
            if (!announcements?.daily_forecast) return;
            
            const entries = sortForecastEntries(announcements.daily_forecast).slice(0, 20);
            const dates = entries.map((item) => item.date);
            const values = entries.map((item) => item.value);

            renderChart('announcementsForecastChart', {
                type: 'line',
                data: {
                    labels: dates.map(shortDate),
                    datasets: [{
                        label: 'Predicted Announcements',
                        data: values,
                        borderColor: '#0288d1',
                        backgroundColor: 'rgba(2, 136, 209, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0288d1',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.raw.toFixed(1)} announcements expected`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f0f0f0' }
                        }
                    }
                }
            });
            
            // Update summary using parsed forecast values
            const total = values.reduce((sum, value) => sum + value, 0);
            const summaryEl = document.getElementById('announcementsSummary');
            if (summaryEl) {
                summaryEl.innerHTML = 
                    `<i class="fas fa-info-circle me-1"></i> Expected total: ${total.toFixed(1)} announcements over 30 days`;
            }
        }

        function buildEventsChart(events) {
            if (!events) return;

            renderChart('eventsChart', {
                type: 'doughnut',
                data: {
                    labels: ['Upcoming', 'Past'],
                    datasets: [{
                        data: [events.upcoming_vs_past?.upcoming || 0, events.upcoming_vs_past?.past || 0],
                        backgroundColor: ['#2e7d32', '#9e9e9e'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { enabled: true }
                    },
                    cutout: '65%'
                }
            });
            
            const summaryEl = document.getElementById('eventsSummary');
            if (summaryEl) {
                summaryEl.innerHTML = 
                    `<span class="badge bg-success">${events.upcoming_vs_past?.upcoming || 0} upcoming</span> ` +
                    `<span class="badge bg-secondary">${events.total_events || 0} total</span>`;
            }
        }

        function buildProjectsChart(projects) {
            if (!projects?.status_distribution) return;

            renderChart('projectsChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(projects.status_distribution),
                    datasets: [{
                        data: Object.values(projects.status_distribution),
                        backgroundColor: ['#0288d1', '#2e7d32', '#ed6c02'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '65%'
                }
            });
            
            const summaryEl = document.getElementById('projectsSummary');
            if (summaryEl) {
                summaryEl.innerHTML = 
                    `Average progress: ${projects.average_progress || 0}% <i class="fas fa-arrow-up text-success"></i>`;
            }
        }

        function buildAgeChart(ageDist) {
            if (!ageDist) return;
            
            // Group ages into ranges for better readability
            const ranges = {
                '0-17': 0,
                '18-30': 0,
                '31-45': 0,
                '46-60': 0,
                '60+': 0
            };
            
            Object.entries(ageDist).forEach(([age, count]) => {
                const a = parseInt(age);
                if (a < 18) ranges['0-17'] += count;
                else if (a <= 30) ranges['18-30'] += count;
                else if (a <= 45) ranges['31-45'] += count;
                else if (a <= 60) ranges['46-60'] += count;
                else ranges['60+'] += count;
            });
            
            renderChart('ageChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(ranges),
                    datasets: [{
                        data: Object.values(ranges),
                        backgroundColor: '#d32f2f',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f0f0f0' },
                            ticks: { stepSize: 5 }
                        }
                    }
                }
            });
            
            const total = Object.values(ageDist).reduce((a, b) => a + b, 0);
            const summaryEl = document.getElementById('ageSummary');
            if (summaryEl) {
                summaryEl.innerHTML = 
                    `Total residents: ${total} | Senior (60+): ${ranges['60+']}`;
            }
        }

        function buildRequestTypeChart(data) {
            // Combine all application status distributions
            const types = {};
            
            if (data.applications?.barangay_clearances?.status_distribution) {
                types['Clearances'] = Object.values(data.applications.barangay_clearances.status_distribution).reduce((a, b) => a + b, 0);
            }
            if (data.applications?.indigency_applications?.status_distribution) {
                types['Indigency'] = Object.values(data.applications.indigency_applications.status_distribution).reduce((a, b) => a + b, 0);
            }
            if (data.applications?.residency_applications?.status_distribution) {
                types['Residency'] = Object.values(data.applications.residency_applications.status_distribution).reduce((a, b) => a + b, 0);
            }
            
            renderChart('requestsTypeChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(types),
                    datasets: [{
                        data: Object.values(types),
                        backgroundColor: ['#d32f2f', '#0288d1', '#2e7d32'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '60%'
                }
            });
        }

        function buildDailyRequestsChart(data) {
            const applications = data?.applications || {};
            const dailyMaps = [
                applications.barangay_clearances?.daily_forecast || {},
                applications.indigency_applications?.daily_forecast || {},
                applications.residency_applications?.daily_forecast || {},
            ];

            const mergedByDate = {};
            dailyMaps.forEach((map) => {
                Object.entries(map).forEach(([date, value]) => {
                    mergedByDate[date] = (mergedByDate[date] || 0) + (Number(value) || 0);
                });
            });

            const entries = sortForecastEntries(mergedByDate)
                .slice(0, 7)
                .map((item) => ({
                    date: item.date,
                    value: Math.round(item.value * 10) / 10,
                }));

            if (!entries.length) {
                return;
            }

            const days = entries.map((item) => shortDate(item.date));
            const requests = entries.map((item) => item.value);

            renderChart('dailyRequestsChart', {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Requests',
                        data: requests,
                        backgroundColor: '#d32f2f',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f0f0f0' }
                        }
                    }
                }
            });
        }

        function formatModuleName(name) {
            return name
                .replace(/_/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase())
                .replace('Applications', '');
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function () {
                // Initialize static stats from server immediately
                initializeStaticStats({
                    totalResidents: {{ $stats['totalResidents'] ?? 0 }},
                    pendingRequests: {{ $stats['pendingRequests'] ?? 0 }},
                    openReports: {{ $stats['openReports'] ?? 0 }},
                    upcomingEvents: {{ $stats['upcomingEvents'] ?? 0 }}
                });

            loadForecastData();
            loadLiveStats();
            startRealtimeDashboardRefresh();
            updateAutoRefreshButtonState();

            const refreshNowBtn = document.getElementById('refreshNowBtn');
            if (refreshNowBtn) {
                refreshNowBtn.addEventListener('click', refreshDashboardNow);
            }

            const refreshForecastBtn = document.getElementById('refreshForecastBtn');
            if (refreshForecastBtn) {
                refreshForecastBtn.addEventListener('click', refreshForecastNow);
            }

            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) {
                    loadLiveStats();
                    loadForecastData();
                }
            });
        });
    </script>
</body>
</html>

