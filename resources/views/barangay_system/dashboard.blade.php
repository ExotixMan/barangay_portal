<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Portal – Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Zoom & Pan Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <style>
        /* GLOBAL RESET */
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        }

        body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        display: flex;
        min-height: 100vh;
        color: #333;
        }

        /* SIDEBAR */
        .sidebar {
        width: 280px;
        background: linear-gradient(135deg, #d32f2f, #c62828);
        color: white;
        padding: 30px 0;
        position: fixed;
        height: 100vh;
        box-shadow: 4px 0 15px rgba(211, 47, 47, 0.2);
        }

        .brand {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 0 30px;
        margin-bottom: 40px;
        }

        .logo {
        width: 50px;
        height: 50px;
        background-image: url('image/logo.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
        }

        .brand h1 {
        font-size: 1.4rem;
        font-weight: 600;
        }

        .menu {
        display: flex;
        flex-direction: column;
        }

        .menu a {
        color: white;
        text-decoration: none;
        padding: 15px 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        }

        .menu a:hover,
        .menu a.active {
        background: rgba(255, 255, 255, 0.1);
        border-left-color: white;
        }

        .menu a.active {
        background: rgba(255, 255, 255, 0.2);
        }

        /* MAIN CONTENT */
        .wrapper {
        margin-left: 280px;
        width: calc(100% - 280px);
        display: flex;
        flex-direction: column;
        }

        /* HEADER */
        .header {
        background: white;
        padding: 25px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-bottom: 3px solid #d32f2f;
        }

        .header h2 {
        color: #d32f2f;
        font-size: 1.8rem;
        font-weight: 600;
        }

        .profile {
        display: flex;
        align-items: center;
        gap: 15px;
        background: linear-gradient(135deg, #d32f2f, #c62828);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: 500;
        }

        /* MAIN CONTENT */
        .content {
        padding: 40px;
        flex: 1;
        }

        /* STATS SECTION */
        .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
        }

        .stat-box {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #d32f2f;
        transition: transform 0.3s ease;
        }

        .stat-box:hover {
        transform: translateY(-5px);
        }

        .stat-box h3 {
        color: #666;
        font-size: 1rem;
        margin-bottom: 10px;
        font-weight: 500;
        }

        .stat-box p {
        color: #d32f2f;
        font-size: 2rem;
        font-weight: 600;
        }

        .stat-number {
        color: #d32f2f;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 15px 0 10px 0;
        }

        .stat-change {
        color: #999;
        font-size: 0.85rem;
        display: block;
        }

        /* CHARTS SECTION */
        .charts-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin: 40px 0;
        }

        .chart-container {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border-top: 4px solid #d32f2f;
        transition: all 0.3s ease;
        }

        .chart-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(211, 47, 47, 0.2);
        }

        .chart-container h3 {
        color: #d32f2f;
        font-size: 1.1rem;
        margin-bottom: 20px;
        font-weight: 600;
        }

        /* RECENT ACTIVITY SECTION */
        .recent-activity {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin: 40px 0;
        border-left: 5px solid #d32f2f;
        }

        .recent-activity h3 {
        color: #d32f2f;
        font-size: 1.3rem;
        margin-bottom: 25px;
        font-weight: 600;
        }

        .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        }

        .activity-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #d32f2f;
        transition: all 0.3s ease;
        }

        .activity-item:hover {
        background: #f0f0f0;
        transform: translateX(5px);
        }

        .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        }

        .activity-icon.pending {
        background: #fff3cd;
        color: #ff9800;
        }

        .activity-icon.resolved {
        background: #d4edda;
        color: #4caf50;
        }

        .activity-icon.new-resident {
        background: #cfe2ff;
        color: #0d6efd;
        }

        .activity-icon.event {
        background: #f8d7da;
        color: #dc3545;
        }

        .activity-content {
        flex: 1;
        }

        .activity-content p {
        color: #333;
        font-size: 0.95rem;
        margin-bottom: 5px;
        }

        .activity-time {
        color: #999;
        font-size: 0.85rem;
        }

        /* CARD ENHANCEMENTS */
        .card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-top: 4px solid #d32f2f;
        display: flex;
        flex-direction: column;
        }

        .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(211, 47, 47, 0.2);
        }

        .card-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        }

        .card h4 {
        color: #d32f2f;
        font-size: 1.2rem;
        margin-bottom: 15px;
        font-weight: 600;
        }

        .card p {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
        flex: 1;
        margin-bottom: 20px;
        }

        .card-link {
        color: #d32f2f;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        }

        .card-link:hover {
        color: #c62828;
        transform: translateX(5px);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .header {
                padding: 20px;
            }
            
            .content {
                padding: 20px;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .cards {
                grid-template-columns: 1fr;
            }
            
            .brand {
                justify-content: center;
            }
            
            .menu {
                flex-direction: row;
                overflow-x: auto;
                padding: 0 20px;
            }
            
            .menu a {
                white-space: nowrap;
                padding: 10px 20px;
                border-left: none;
                border-bottom: 3px solid transparent;
            }
            
            .menu a:hover,
            .menu a.active {
                border-left: none;
                border-bottom-color: white;
            }
        }

        /* SCROLLBAR STYLING */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #d32f2f;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c62828;
        }
    </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <div class="logo"></div>
      <h1>Barangay Portal</h1>
    </div>
    <nav class="menu">
      <a href="admin_dashboard.html" class="active">Dashboard</a>
      <a href="resident_management.html">Residents</a>
      <a href="request.html">Requests</a>
      <a href="reports.html">Reports</a>
      <a href="contents.html">Contents</a>
      <a href="user&roles.html">Users & Roles</a>
      <a href="settings.html">Settings</a>
      <a href="#">Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="wrapper">
    <!-- HEADER -->
    <header class="header">
      <h2>Admin Dashboard</h2>
      <div class="profile">
        <span class="user">Admin</span>
      </div>
    </header>

    <main class="content">
      <!-- TOP STATS -->
      <section class="stats">
        <div class="stat-box">
          <h3>Total Residents</h3>
          <p class="stat-number">1,245</p>
          <span class="stat-change">+45 this month</span>
        </div>
        <div class="stat-box">
          <h3>Pending Requests</h3>
          <p class="stat-number">87</p>
          <span class="stat-change">12 urgent</span>
        </div>
        <div class="stat-box">
          <h3>Open Reports</h3>
          <p class="stat-number">12</p>
          <span class="stat-change">3 in progress</span>
        </div>
        <div class="stat-box">
          <h3>Upcoming Events</h3>
          <p class="stat-number">5</p>
          <span class="stat-change">2 this week</span>
        </div>
      </section>

      <!-- CHARTS SECTION -->
      <section class="charts-section">
        <div class="chart-container">
          <h3>Daily Requests Forecast</h3>
          <canvas id="dailyRequestsChart"></canvas>
        </div>
        
        <div class="chart-container">
          <h3>Daily Approvals Forecast</h3>
          <canvas id="dailyApprovalsChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Weekly Requests Forecast by Type</h3>
          <canvas id="weeklyRequestsChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Daily Incident Forecast</h3>
          <canvas id="incidentDailyChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Weekly Incident Forecast by Type</h3>
          <canvas id="incidentWeeklyChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Age Distribution</h3>
          <canvas id="ageDistributionChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Missing Documents</h3>
          <canvas id="missingDocsChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Incidents by Location</h3>
          <canvas id="incLocationChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Requests by Type</h3>
          <canvas id="requestsTypeChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Incidents by Type</h3>
          <canvas id="incTypeChart"></canvas>
        </div>

        <div class="chart-container">
          <h3>Status Distribution</h3>
          <canvas id="statusChart"></canvas>
        </div>
      </section>

      <!-- RECENT ACTIVITY -->
      <section class="recent-activity">
        <h3>Recent Activity</h3>
        <div class="activity-list">
          <div class="activity-item">
            <div class="activity-icon pending"></div>
            <div class="activity-content">
              <p><strong>New Service Request</strong> from Maria Santos</p>
              <span class="activity-time">2 hours ago</span>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon resolved"></div>
            <div class="activity-content">
              <p><strong>Complaint Resolved</strong> - Noise complaint resolved</p>
              <span class="activity-time">5 hours ago</span>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon new-resident"></div>
            <div class="activity-content">
              <p><strong>New Resident Registered</strong> - Juan Dela Cruz</p>
              <span class="activity-time">1 day ago</span>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon event"></div>
            <div class="activity-content">
              <p><strong>Event Created</strong> - Barangay Fiesta 2026</p>
              <span class="activity-time">2 days ago</span>
            </div>
          </div>
        </div>
      </section>

      <!-- MODULE CARDS -->
      <section class="cards">
        <div class="card">
          <div class="card-icon">👥</div>
          <h4>Resident Management</h4>
          <p>Create, read, update, and delete resident profiles with contact info.</p>
          <a href="resident_management.html" class="card-link">Go to Module →</a>
        </div>

        <div class="card">
          <div class="card-icon">📋</div>
          <h4>Service Request Management</h4>
          <p>Approve/deny requests, generate certificates with QR codes.</p>
          <a href="#" class="card-link">Go to Module →</a>
        </div>

        <div class="card">
          <div class="card-icon">📢</div>
          <h4>Report & Complaint Handling</h4>
          <p>Track complaints, assign officers, update status and resolutions.</p>
          <a href="#" class="card-link">Go to Module →</a>
        </div>

        <div class="card">
          <div class="card-icon">📝</div>
          <h4>Content Management</h4>
          <p>Add announcements, events, attachments or posters for residents.</p>
          <a href="#" class="card-link">Go to Module →</a>
        </div>

        <div class="card">
          <div class="card-icon">🔐</div>
          <h4>User & Role Management</h4>
          <p>Manage admin accounts, roles, and system permissions.</p>
          <a href="#" class="card-link">Go to Module →</a>
        </div>
      </section>
    </main>
  </div>

  <script>
        const charts = {};

        // =====================================================
        // ENHANCED COLOR PALETTE FOR BETTER READABILITY
        // =====================================================
        const ENHANCED_COLORS = {
            primary: {
                red: 'rgba(211, 47, 47, 0.85)',
                blue: 'rgba(30, 136, 229, 0.85)',
                green: 'rgba(56, 142, 60, 0.85)',
                orange: 'rgba(245, 124, 0, 0.85)',
                purple: 'rgba(123, 31, 162, 0.85)',
                teal: 'rgba(0, 121, 107, 0.85)'
            },
            background: {
                red: 'rgba(211, 47, 47, 0.15)',
                blue: 'rgba(30, 136, 229, 0.15)',
                green: 'rgba(56, 142, 60, 0.15)',
                orange: 'rgba(245, 124, 0, 0.15)',
                purple: 'rgba(123, 31, 162, 0.15)',
                teal: 'rgba(0, 121, 107, 0.15)'
            },
            gradients: [
                'linear-gradient(135deg, #d32f2f, #ff5252)',
                'linear-gradient(135deg, #1e88e5, #42a5f5)',
                'linear-gradient(135deg, #388e3c, #66bb6a)',
                'linear-gradient(135deg, #f57c00, #ffb74d)',
                'linear-gradient(135deg, #7b1fa2, #ba68c8)',
                'linear-gradient(135deg, #00796b, #26a69a)'
            ]
        };

        // =====================================================
        // ENHANCED COMMON OPTIONS WITH BETTER READABILITY
        // =====================================================
        function enhancedBaseOptions(horizontal = false, type = 'bar') {
            const isBar = type === 'bar';
            const isLine = type === 'line';
            
            return {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: horizontal ? 'y' : 'x',
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: {
                                size: 11,
                                family: "'Poppins', sans-serif"
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#d32f2f',
                        bodyColor: '#333',
                        borderColor: '#d32f2f',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 12,
                            family: "'Poppins', sans-serif",
                            weight: '600'
                        },
                        bodyFont: {
                            size: 11,
                            family: "'Poppins', sans-serif"
                        },
                        displayColors: true,
                        boxPadding: 6
                    },
                    zoom: {
                        zoom: {
                            wheel: { enabled: true },
                            pinch: { enabled: true },
                            mode: 'x'
                        },
                        pan: {
                            enabled: true,
                            mode: 'x'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 10,
                                family: "'Poppins', sans-serif"
                            },
                            color: '#666',
                            maxRotation: 45,
                            padding: 8
                        },
                        title: {
                            display: false,
                            text: 'Date',
                            color: '#666',
                            font: {
                                size: 12,
                                family: "'Poppins', sans-serif",
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 10,
                                family: "'Poppins', sans-serif"
                            },
                            color: '#666',
                            padding: 8
                        },
                        title: {
                            display: true,
                            text: isBar ? 'Count' : (isLine ? 'Value' : ''),
                            color: '#666',
                            font: {
                                size: 12,
                                family: "'Poppins', sans-serif",
                                weight: '600'
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 4,
                        borderSkipped: false
                    },
                    line: {
                        tension: 0.3,
                        borderWidth: 2.5,
                        fill: true
                    },
                    point: {
                        radius: 4,
                        hoverRadius: 6,
                        borderWidth: 2
                    }
                },
                animation: {
                    duration: 750,
                    easing: 'easeOutQuart'
                },
                hover: {
                    intersect: false
                }
            };
        }

        // =====================================================
        // PIE/DOUGHNUT SPECIFIC OPTIONS
        // =====================================================
        function pieDoughnutOptions() {
            return {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: {
                                size: 11,
                                family: "'Poppins', sans-serif"
                            },
                            color: '#333',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#d32f2f',
                        bodyColor: '#333',
                        borderColor: '#d32f2f',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 12,
                            family: "'Poppins', sans-serif",
                            weight: '600'
                        },
                        bodyFont: {
                            size: 11,
                            family: "'Poppins', sans-serif"
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1000
                }
            };
        }

        // =====================================================
        // GENERIC CHART CREATOR / UPDATER (UNCHANGED)
        // =====================================================
        function renderOrUpdateChart(id, type, labels, datasets, options = {}) {
            if (!charts[id]) {
                charts[id] = new Chart(document.getElementById(id), {
                    type,
                    data: { labels, datasets },
                    options
                });
            } else {
                charts[id].data.labels = labels;
                charts[id].data.datasets = datasets;
                charts[id].update();
            }
        }

        // =====================================================
        // FETCH & UPDATE DATA (REAL-TIME) - ENHANCED
        // =====================================================
        async function fetchAndUpdate() {
            const res = await fetch('/api/forecast-requests');
            const data = await res.json();

            // ---------------- DAILY REQUESTS FORECAST ----------------
            renderOrUpdateChart(
                'dailyRequestsChart',
                'bar',
                Object.keys(data.requests.daily_requests_forecast),
                [{
                    label: 'Daily Requests Forecast',
                    data: Object.values(data.requests.daily_requests_forecast),
                    backgroundColor: ENHANCED_COLORS.background.blue,
                    borderColor: ENHANCED_COLORS.primary.blue,
                    borderWidth: 1.5,
                    hoverBackgroundColor: ENHANCED_COLORS.primary.blue,
                    hoverBorderWidth: 2
                }],
                enhancedBaseOptions(false, 'bar')
            );

            // ---------------- DAILY APPROVALS FORECAST ----------------
            renderOrUpdateChart(
                'dailyApprovalsChart',
                'line',
                Object.keys(data.requests.daily_approvals_forecast),
                [{
                    label: 'Daily Approvals Forecast',
                    data: Object.values(data.requests.daily_approvals_forecast),
                    fill: true,
                    tension: 0.3,
                    backgroundColor: 'rgba(56, 142, 60, 0.15)',
                    borderColor: ENHANCED_COLORS.primary.green,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: ENHANCED_COLORS.primary.green,
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }],
                enhancedBaseOptions(false, 'line')
            );

            // ---------------- WEEKLY REQUESTS BY TYPE ----------------
            const wr = data.requests.weekly_requests_forecast;
            const wrLabels = [...new Set(Object.values(wr).flatMap(o => Object.keys(o)))].sort();
            const wrDatasets = Object.keys(wr).map((k, i) => ({
                label: k,
                data: wrLabels.map(d => wr[k][d] || 0),
                backgroundColor: ENHANCED_COLORS.background[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderColor: ENHANCED_COLORS.primary[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderWidth: 1.5,
                hoverBackgroundColor: ENHANCED_COLORS.primary[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderRadius: 4
            }));

            renderOrUpdateChart(
                'weeklyRequestsChart',
                'bar',
                wrLabels,
                wrDatasets,
                {
                    ...enhancedBaseOptions(false, 'bar'),
                    scales: {
                        ...enhancedBaseOptions(false, 'bar').scales,
                        x: {
                            ...enhancedBaseOptions(false, 'bar').scales.x,
                            stacked: true
                        },
                        y: {
                            ...enhancedBaseOptions(false, 'bar').scales.y,
                            stacked: true
                        }
                    }
                }
            );

            // ---------------- DAILY INCIDENT FORECAST ----------------
            renderOrUpdateChart(
                'incidentDailyChart',
                'bar',
                Object.keys(data.incidents.daily_incidents_forecast_es),
                [{
                    label: 'Daily Incident Forecast',
                    data: Object.values(data.incidents.daily_incidents_forecast_es),
                    backgroundColor: ENHANCED_COLORS.background.red,
                    borderColor: ENHANCED_COLORS.primary.red,
                    borderWidth: 1.5,
                    hoverBackgroundColor: ENHANCED_COLORS.primary.red,
                    hoverBorderWidth: 2
                }],
                enhancedBaseOptions(false, 'bar')
            );

            // ---------------- WEEKLY INCIDENT BY TYPE ----------------
            const wi = data.incidents.weekly_incident_type_forecast;
            const wiLabels = [...new Set(Object.values(wi).flatMap(o => Object.keys(o)))].sort();
            const wiDatasets = Object.keys(wi).map((k, i) => ({
                label: k,
                data: wiLabels.map(d => wi[k][d] || 0),
                backgroundColor: ENHANCED_COLORS.background[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderColor: ENHANCED_COLORS.primary[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderWidth: 1.5,
                hoverBackgroundColor: ENHANCED_COLORS.primary[Object.keys(ENHANCED_COLORS.primary)[i % 6]],
                borderRadius: 4
            }));

            renderOrUpdateChart(
                'incidentWeeklyChart',
                'bar',
                wiLabels,
                wiDatasets,
                enhancedBaseOptions(false, 'bar')
            );

            // ---------------- REQUESTS BY TYPE (PIE) ----------------
            renderOrUpdateChart(
                'requestsTypeChart',
                'pie',
                Object.keys(data.requests.analytics.requests_by_type),
                [{
                    data: Object.values(data.requests.analytics.requests_by_type),
                    backgroundColor: Object.values(ENHANCED_COLORS.primary),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverBorderWidth: 3,
                    hoverOffset: 15
                }],
                pieDoughnutOptions()
            );

            // ---------------- MISSING DOCUMENTS ----------------
            renderOrUpdateChart(
                'missingDocsChart',
                'bar',
                Object.keys(data.requests.analytics.requests_missing_docs),
                [{
                    label: 'Missing Documents',
                    data: Object.values(data.requests.analytics.requests_missing_docs),
                    backgroundColor: ENHANCED_COLORS.gradients[4],
                    borderColor: ENHANCED_COLORS.primary.purple,
                    borderWidth: 1.5,
                    hoverBackgroundColor: ENHANCED_COLORS.primary.purple
                }],
                enhancedBaseOptions(false, 'bar')
            );

            // ---------------- AGE DISTRIBUTION ----------------
            renderOrUpdateChart(
                'ageDistributionChart',
                'bar',
                Object.keys(data.requests.analytics.age_distribution),
                [{
                    label: 'Age Distribution',
                    data: Object.values(data.requests.analytics.age_distribution),
                    backgroundColor: ENHANCED_COLORS.gradients[1],
                    borderColor: ENHANCED_COLORS.primary.blue,
                    borderWidth: 1.5,
                    hoverBackgroundColor: ENHANCED_COLORS.primary.blue
                }],
                enhancedBaseOptions(false, 'bar')
            );

            // ---------------- INCIDENTS BY TYPE ----------------
            renderOrUpdateChart(
                'incTypeChart',
                'doughnut',
                Object.keys(data.incidents.analytics.incidents_by_type),
                [{
                    data: Object.values(data.incidents.analytics.incidents_by_type),
                    backgroundColor: Object.values(ENHANCED_COLORS.primary),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverBorderWidth: 3,
                    hoverOffset: 15
                }],
                pieDoughnutOptions()
            );

            // ---------------- INCIDENTS BY LOCATION ----------------
            renderOrUpdateChart(
                'incLocationChart',
                'bar',
                Object.keys(data.incidents.analytics.incidents_by_location),
                [{
                    label: 'Incidents by Location',
                    data: Object.values(data.incidents.analytics.incidents_by_location),
                    backgroundColor: ENHANCED_COLORS.gradients[3],
                    borderColor: ENHANCED_COLORS.primary.orange,
                    borderWidth: 1.5,
                    hoverBackgroundColor: ENHANCED_COLORS.primary.orange
                }],
                enhancedBaseOptions(true, 'bar')
            );

            // ---------------- STATUS DISTRIBUTION ----------------
            renderOrUpdateChart(
                'statusChart',
                'doughnut',
                Object.keys(data.incidents.analytics.status_distribution),
                [{
                    data: Object.values(data.incidents.analytics.status_distribution),
                    backgroundColor: [
                        ENHANCED_COLORS.primary.red,      // Pending
                        ENHANCED_COLORS.primary.orange,   // In Progress
                        ENHANCED_COLORS.primary.green,    // Resolved
                        ENHANCED_COLORS.primary.blue,     // Closed
                        ENHANCED_COLORS.primary.purple    // Other
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverBorderWidth: 3,
                    hoverOffset: 15
                }],
                pieDoughnutOptions()
            );
        }

        // INITIAL LOAD
        fetchAndUpdate();

        // REAL-TIME AUTO REFRESH (10 seconds)
        setInterval(fetchAndUpdate, 10000);
    </script>
</body>
</html>