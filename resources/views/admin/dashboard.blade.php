<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Dashboard – Clear & Simple View</title>
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

    <style>
        :root {
            --primary: #d32f2f;
            --primary-light: #ffebee;
            --primary-dark: #b71c1c;
            --success: #2e7d32;
            --warning: #ed6c02;
            --info: #0288d1;
            --gray-bg: #f4f4f4;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
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
        }

        .stat-number {
            color: black;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.2;
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
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
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

        @media (max-width: 768px) {
            .stat-number {
                font-size: 1.8rem;
            }

            .chart-container {
                height: 200px;
            }

            .profile-badge span {
                display: none;
            }

            .profile-badge {
                padding: 0.5rem;
                border-radius: 50%;
            }
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

        <a href="#" class="active" onclick="handleLinkClick(event, this)">
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
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-file-alt"></i> <span>Residency Applications</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-file-invoice"></i> <span>Indigency</span></a>
        </div>

        <div class="menu-section">Legal</div>
        
        <!-- Records Dropdown -->
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="recordsSubmenu">
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-file-contract"></i> <span>Clearances</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-book"></i> <span>Blotter</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-exclamation-triangle"></i> <span>Incidents</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-eye"></i> <span>Witnesses</span></a>
        </div>

        <div class="menu-section">Community</div>
        
        <!-- Community Dropdown -->
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="communitySubmenu">
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            <a href="#" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
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
                <h1 class="page-title">Admin Dashboard</h1>
            </div>
            <div class="profile-badge">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <!-- Stats Cards -->
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-users me-2"></i>Total Residents
                        </div>
                        <div class="stat-number">1,245</div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up text-success me-1"></i>+45 this month
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-clock me-2"></i>Pending Requests
                        </div>
                        <div class="stat-number">87</div>
                        <div class="stat-change">
                            <span class="badge bg-danger bg-opacity-10 text-danger">12 urgent</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-exclamation-triangle me-2"></i>Open Reports
                        </div>
                        <div class="stat-number">12</div>
                        <div class="stat-change">
                            <span class="badge bg-info bg-opacity-10 text-info">3 in progress</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-calendar-check me-2"></i>Upcoming Events
                        </div>
                        <div class="stat-number">5</div>
                        <div class="stat-change">
                            <i class="fas fa-calendar me-1"></i>2 this week
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-3 g-lg-4">
                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-bar"></i>
                            Daily Requests Overview
                        </div>
                        <div class="chart-container">
                            <canvas id="dailyRequestsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-line"></i>
                            Approval Trends
                        </div>
                        <div class="chart-container">
                            <canvas id="dailyApprovalsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Types of Requests
                        </div>
                        <div class="chart-container">
                            <canvas id="requestsTypeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Common Incidents
                        </div>
                        <div class="chart-container">
                            <canvas id="incTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    
    <script>
        // ========== CHARTS INITIALIZATION ==========
        const chartData = {
            dailyRequests: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                values: [45, 52, 38, 41, 47, 25, 20]
            },
            dailyApprovals: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                values: [32, 41, 35, 38, 42, 18, 15]
            },
            requestTypes: {
                labels: ['Clearance', 'Indigency', 'Business', 'Residency', 'Others'],
                values: [320, 180, 95, 150, 75]
            },
            incidentTypes: {
                labels: ['Noise', 'Quarrel', 'Theft', 'Accident', 'Other'],
                values: [45, 38, 12, 25, 18]
            }
        };

        const chartColors = {
            primary: '#d32f2f',
            primaryLight: 'rgba(211, 47, 47, 0.1)',
            success: '#2e7d32',
            warning: '#ed6c02',
            info: '#0288d1',
            purple: '#7b1fa2'
        };

        function initCharts() {
            // Daily Requests
            new Chart(document.getElementById('dailyRequestsChart'), {
                type: 'bar',
                data: {
                    labels: chartData.dailyRequests.labels,
                    datasets: [{
                        data: chartData.dailyRequests.values,
                        backgroundColor: chartColors.primaryLight,
                        borderColor: chartColors.primary,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Daily Approvals
            new Chart(document.getElementById('dailyApprovalsChart'), {
                type: 'line',
                data: {
                    labels: chartData.dailyApprovals.labels,
                    datasets: [{
                        data: chartData.dailyApprovals.values,
                        borderColor: chartColors.success,
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Request Types
            new Chart(document.getElementById('requestsTypeChart'), {
                type: 'pie',
                data: {
                    labels: chartData.requestTypes.labels,
                    datasets: [{
                        data: chartData.requestTypes.values,
                        backgroundColor: [chartColors.primary, chartColors.success, chartColors.warning, chartColors.info, chartColors.purple]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // Incident Types
            new Chart(document.getElementById('incTypeChart'), {
                type: 'doughnut',
                data: {
                    labels: chartData.incidentTypes.labels,
                    datasets: [{
                        data: chartData.incidentTypes.values,
                        backgroundColor: [chartColors.warning, chartColors.primary, chartColors.purple, chartColors.info, chartColors.success]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '65%'
                }
            });
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>
</body>
</html>