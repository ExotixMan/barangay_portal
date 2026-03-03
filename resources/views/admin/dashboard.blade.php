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

        <a href="{{ route('dashboard.index') }}" class="active" onclick="handleLinkClick(event, this)">
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
        <div class="dropdown-btn" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu" id="communitySubmenu">
            <a href="{{ route('announcements.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a>
            <a href="{{ route('events.index') }}" onclick="handleSubmenuClick(event)"><i class="fas fa-calendar"></i> <span>Events</span></a>
            <a href="{{ route('projects.index') }}" class="" onclick="handleSubmenuClick(event)"><i class="fas fa-project-diagram"></i> <span>Projects</span></a>
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
                <h1 class="page-title">Barangay Dashboard</h1>
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

            <!-- Quick Overview Charts -->
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

            <!-- Applications Forecast Section -->
            <div class="section-header">
                <i class="fas fa-file-signature"></i>
                <h3>Applications Forecast (Next 30 Days)</h3>
                <span class="summary-badge ms-auto">Predictions based on historical data</span>
            </div>
            <div id="applicationsForecast" class="row g-3 g-lg-4 mb-4"></div>

            <!-- Blotter Reports & Announcements -->
            <div class="row g-3 g-lg-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-card">
                        <div class="chart-title">
                            <i class="fas fa-book"></i>
                            Blotter Reports Forecast
                            <span class="summary-badge ms-auto">Trending ↑</span>
                        </div>
                        <div class="chart-container">
                            <canvas id="blotterForecastChart"></canvas>
                        </div>
                        <div class="mt-3 small text-muted" id="blotterSummary"></div>
                    </div>
                </div>

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
            </div>

            <!-- Community Insights Section -->
            <div class="section-header">
                <i class="fas fa-users"></i>
                <h3>Community Insights</h3>
            </div>
            <div class="row g-3 g-lg-4">
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
            </div>

            <!-- Dynamic Forecast Charts Container -->
            <div id="autoForecastCharts" class="row g-3 g-lg-4 mt-2"></div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    
    <script>
        // Store forecast data globally for access
        let forecastData = null;

        async function loadForecastData() {
            try {
                const response = await fetch("{{ route('dashboard.forecast') }}");
                const data = await response.json();

                if (data.error) {
                    console.error("Forecast error:", data.error);
                    return;
                }

                forecastData = data;
                
                // Update Stats
                updateStats(data);
                
                // Build all charts
                buildAllCharts(data);

            } catch (error) {
                console.error("Error loading forecast:", error);
            }
        }

        function updateStats(data) {
            // Update stat cards with meaningful labels
            document.getElementById('totalResidents').innerText = 
                data.residents?.total_residents?.toLocaleString() || '0';

            // Calculate total pending from all applications
            const pendingRequests = 
                (data.applications?.barangay_clearances?.status_distribution?.processing || 0) +
                (data.applications?.indigency_applications?.status_distribution?.processing || 0) +
                (data.applications?.residency_applications?.status_distribution?.processing || 0);
            
            document.getElementById('pendingRequests').innerText = pendingRequests;

            document.getElementById('openReports').innerText = 
                data.blotter_reports?.analytics?.status_distribution?.processing || 0;

            document.getElementById('upcomingEvents').innerText = 
                data.events?.upcoming_vs_past?.upcoming || 0;
        }

        function buildAllCharts(data) {
            // Build Applications Forecast
            buildApplicationsCharts(data.applications);
            
            // Build Blotter Forecast
            buildBlotterChart(data.blotter_reports);
            
            // Build Announcements Forecast
            buildAnnouncementsChart(data.announcements);
            
            // Build Community Insights
            buildEventsChart(data.events);
            buildProjectsChart(data.projects);
            buildAgeChart(data.residents?.age_distribution);
            
            // Build existing charts
            buildRequestTypeChart(data);
            buildDailyRequestsChart(data);
        }

        function buildApplicationsCharts(applications) {
            const container = document.getElementById('applicationsForecast');
            container.innerHTML = '';

            if (!applications) return;

            // Create a card for each application type
            Object.entries(applications).forEach(([key, value]) => {
                if (value.daily_forecast) {
                    const forecast = value.daily_forecast;
                    const dates = Object.keys(forecast).slice(0, 15); // Show first 15 days for readability
                    const values = Object.values(forecast).slice(0, 15);
                    
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
                                Based on ${Object.values(value.status_distribution || {}).reduce((a, b) => a + b, 0)} historical applications
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(col);
                    
                    // Create chart
                    setTimeout(() => {
                        const ctx = document.getElementById(`chart-${key}`).getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: dates.map(d => d.split(',')[0]), // Show only month and day
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
            
            const forecast = blotter.daily_forecast;
            const dates = Object.keys(forecast).slice(0, 20);
            const values = Object.values(forecast).slice(0, 20);
            
            const ctx = document.getElementById('blotterForecastChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates.map(d => d.split(',')[0]),
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
            
            // Update summary
            const total = Object.values(forecast).reduce((a, b) => a + b, 0).toFixed(1);
            const avg = (total / Object.keys(forecast).length).toFixed(1);
            document.getElementById('blotterSummary').innerHTML = 
                `<i class="fas fa-chart-line me-1"></i> Expected total: ${total} incidents | Daily average: ${avg}`;
        }

        function buildAnnouncementsChart(announcements) {
            if (!announcements?.daily_forecast) return;
            
            const forecast = announcements.daily_forecast;
            const dates = Object.keys(forecast).slice(0, 20);
            const values = Object.values(forecast).slice(0, 20);
            
            const ctx = document.getElementById('announcementsForecastChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates.map(d => d.split(',')[0]),
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
            
            // Update summary
            const total = Object.values(forecast).reduce((a, b) => a + b, 0).toFixed(1);
            document.getElementById('announcementsSummary').innerHTML = 
                `<i class="fas fa-info-circle me-1"></i> Expected total: ${total} announcements over 30 days`;
        }

        function buildEventsChart(events) {
            if (!events) return;
            
            const ctx = document.getElementById('eventsChart').getContext('2d');
            
            new Chart(ctx, {
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
            
            document.getElementById('eventsSummary').innerHTML = 
                `<span class="badge bg-success">${events.upcoming_vs_past?.upcoming || 0} upcoming</span> ` +
                `<span class="badge bg-secondary">${events.total_events || 0} total</span>`;
        }

        function buildProjectsChart(projects) {
            if (!projects?.status_distribution) return;
            
            const ctx = document.getElementById('projectsChart').getContext('2d');
            
            new Chart(ctx, {
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
            
            document.getElementById('projectsSummary').innerHTML = 
                `Average progress: ${projects.average_progress || 0}% <i class="fas fa-arrow-up text-success"></i>`;
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
            
            const ctx = document.getElementById('ageChart').getContext('2d');
            
            new Chart(ctx, {
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
            document.getElementById('ageSummary').innerHTML = 
                `Total residents: ${total} | Senior (60+): ${ranges['60+']}`;
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
            
            const ctx = document.getElementById('requestsTypeChart').getContext('2d');
            
            new Chart(ctx, {
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
            // Create sample daily data (in real app, this would come from actual daily counts)
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const requests = [12, 19, 15, 17, 24, 8, 5];
            
            const ctx = document.getElementById('dailyRequestsChart').getContext('2d');
            
            new Chart(ctx, {
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
            loadForecastData();
        });
    </script>
</body>
</html>