@extends('admin.layouts.admin')

@section('title', 'Barangay Dashboard')
@section('page_title', 'Barangay Dashboard')
@section('page_icon', 'fas fa-chart-line')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
            <div class="dashboard-shell">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show dashboard-alert" role="alert">
                        <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show dashboard-alert" role="alert">
                        <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <section class="dashboard-hero">
                    <div class="dashboard-hero-bg"></div>
                    <div class="dashboard-hero-content">
                        <div class="min-w-0">
                            <div class="dashboard-hero-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="dashboard-kicker">Barangay command dashboard</div>
                            <h2>Monitor services, requests, incidents, and community activity in one place.</h2>
                            <p>Use the interactive tabs and 7D / 30D / 90D range controls to focus on what matters. Empty charts stay useful with setup-ready states instead of looking broken.</p>
                        </div>
                    </div>
                    <div class="dashboard-hero-meta">
                        <span><i class="fas fa-clock"></i> Live sync: <strong id="liveSyncTimeText">Waiting for refresh</strong></span>
                        @admin_can('view_forecast')
                        <span><i class="fas fa-chart-area"></i> Forecast: <strong id="forecastLastUpdatedText">Not loaded yet</strong></span>
                        @endadmin_can
                    </div>
                </section>

                <section class="dashboard-toolbar-panel dashboard-control-panel">
                    <div class="dashboard-tabs" role="tablist" aria-label="Dashboard sections">
                        <button type="button" class="dashboard-tab active" data-dashboard-tab="overview"><i class="fas fa-gauge-high"></i>Overview</button>
                        <button type="button" class="dashboard-tab" data-dashboard-tab="operations"><i class="fas fa-list-check"></i>Operations</button>
                        @admin_can('view_forecast')
                        <button type="button" class="dashboard-tab" data-dashboard-tab="forecast"><i class="fas fa-chart-line"></i>Forecast</button>
                        @endadmin_can
                        @if(auth('admin')->user()->hasAnyPermission(['view_events', 'view_projects', 'view_residents']))
                        <button type="button" class="dashboard-tab" data-dashboard-tab="community"><i class="fas fa-users"></i>Community</button>
                        @endif
                    </div>

                    <div class="dashboard-toolbar-right">
                        <div class="dashboard-range" aria-label="Chart range">
                            <button type="button" class="range-chip active" data-range-days="7">7D</button>
                            <button type="button" class="range-chip" data-range-days="30">30D</button>
                            <button type="button" class="range-chip" data-range-days="90">90D</button>
                        </div>

                        <div class="dashboard-actions-inline" aria-label="Dashboard actions">
                            <button type="button" class="btn btn-primary" id="refreshNowBtn" title="Refresh dashboard now">
                                <i class="fas fa-rotate-right"></i>
                                <span id="refreshNowBtnText">Refresh Now</span>
                            </button>
                            @admin_can('view_forecast')
                            <button type="button" class="btn btn-light-soft" id="refreshForecastBtn" title="Regenerate forecast from latest database records">
                                <i class="fas fa-bolt"></i>
                                <span id="refreshForecastBtnText">Refresh Forecast</span>
                            </button>
                            @endadmin_can
                            <button type="button" class="btn btn-light-soft auto-off" id="autoRefreshToggleBtn" data-click-call="toggleAutoRefresh">
                                <i class="fas fa-signal"></i>
                                <span id="autoRefreshToggleText">Auto Refresh: OFF</span>
                            </button>
                        </div>
                    </div>
                </section>

                <section class="dashboard-kpi-grid" aria-label="Dashboard key metrics">
                    <article class="metric-card metric-residents">
                        <div class="metric-icon"><i class="fas fa-users"></i></div>
                        <div class="metric-body">
                            <span class="metric-label">Total Residents</span>
                            <strong class="metric-value" id="totalResidents">-</strong>
                            <span class="metric-note">Registered residents</span>
                        </div>
                    </article>
                    <article class="metric-card metric-pending">
                        <div class="metric-icon"><i class="fas fa-clock"></i></div>
                        <div class="metric-body">
                            <span class="metric-label">Pending Requests</span>
                            <strong class="metric-value" id="pendingRequests">-</strong>
                            <span class="metric-note">Needs attention</span>
                        </div>
                    </article>
                    <article class="metric-card metric-reports">
                        <div class="metric-icon"><i class="fas fa-book"></i></div>
                        <div class="metric-body">
                            <span class="metric-label">Open Reports</span>
                            <strong class="metric-value" id="openReports">-</strong>
                            <span class="metric-note">Under investigation</span>
                        </div>
                    </article>
                    <article class="metric-card metric-events">
                        <div class="metric-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="metric-body">
                            <span class="metric-label">Upcoming Events</span>
                            <strong class="metric-value" id="upcomingEvents">-</strong>
                            <span class="metric-note">Scheduled activities</span>
                        </div>
                    </article>
                </section>

                @if(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast']))
                <section class="dashboard-section" data-dashboard-section="overview">
                    <div class="section-heading">
                        <div>
                            <span class="section-kicker"><i class="fas fa-chart-simple"></i> Executive overview</span>
                            <h3>What needs attention today</h3>
                        </div>
                        <span class="summary-badge"><i class="fas fa-filter"></i> Interactive</span>
                    </div>
                    <div class="dashboard-chart-grid overview-grid">
                        <article class="dashboard-card chart-card-large">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-layer-group"></i> Service Pipeline Snapshot</h4>
                                    <p>Shows active workload across requests, reports, events, and residents.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="pipelineSnapshotChart" data-chart-title="Service Pipeline Snapshot" title="Expand chart"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container chart-container-tall">
                                <canvas id="pipelineSnapshotChart"></canvas>
                            </div>
                        </article>

                        <article class="dashboard-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-database"></i> Data Readiness</h4>
                                    <p>Quick check of which modules already have records.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="readinessChart" data-chart-title="Data Readiness" title="Expand chart"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container chart-container-tall">
                                <canvas id="readinessChart"></canvas>
                            </div>
                        </article>
                    </div>

                    <div class="dashboard-chart-grid two-col mt-3">
                        <article class="dashboard-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-chart-bar"></i> Daily Service Requests</h4>
                                    <p>Predicted service demand using the selected range.</p>
                                </div>
                                <div class="card-actions">
                                    <button type="button" class="mini-toggle active" data-chart-type="bar" data-chart-target="dailyRequestsChart">Bar</button>
                                    <button type="button" class="mini-toggle" data-chart-type="line" data-chart-target="dailyRequestsChart">Line</button>
                                    <button type="button" class="icon-action" data-chart-expand="dailyRequestsChart" data-chart-title="Daily Service Requests"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="dailyRequestsChart" data-chart-type-current="bar"></canvas>
                            </div>
                        </article>

                        <article class="dashboard-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-chart-pie"></i> Request Types Distribution</h4>
                                    <p>Breakdown of barangay service requests.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="requestsTypeChart" data-chart-title="Request Types Distribution"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container">
                                <canvas id="requestsTypeChart"></canvas>
                            </div>
                        </article>
                    </div>
                </section>
                @endif

                <section class="dashboard-section d-none" data-dashboard-section="operations">
                    <div class="section-heading">
                        <div>
                            <span class="section-kicker"><i class="fas fa-clipboard-list"></i> Operations</span>
                            <h3>Daily work and communication load</h3>
                        </div>
                    </div>
                    <div class="dashboard-chart-grid two-col">
                        @admin_can('view_blotter')
                        <article class="dashboard-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-book"></i> Incident Reports Forecast</h4>
                                    <p>Expected incident reports for the selected range.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="blotterForecastChart" data-chart-title="Incident Reports Forecast"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container">
                                <canvas id="blotterForecastChart"></canvas>
                            </div>
                            <div class="dashboard-summary-line" id="blotterSummary"></div>
                        </article>
                        @endadmin_can

                        @admin_can('view_announcements')
                        <article class="dashboard-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-bullhorn"></i> Announcements Forecast</h4>
                                    <p>Publishing activity forecast for communications planning.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="announcementsForecastChart" data-chart-title="Announcements Forecast"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container">
                                <canvas id="announcementsForecastChart"></canvas>
                            </div>
                            <div class="dashboard-summary-line" id="announcementsSummary"></div>
                        </article>
                        @endadmin_can
                    </div>
                </section>

                @admin_can('view_forecast')
                <section class="dashboard-section d-none" data-dashboard-section="forecast">
                    <div class="section-heading">
                        <div>
                            <span class="section-kicker"><i class="fas fa-wand-magic-sparkles"></i> Predictive planning</span>
                            <h3>Applications Forecast</h3>
                        </div>
                        <span class="summary-badge"><i class="fas fa-calendar-days"></i> 7D / 30D / 90D</span>
                    </div>
                    <div id="applicationsForecast" class="dashboard-app-grid"></div>
                    <div id="autoForecastCharts" class="dashboard-app-grid mt-3"></div>
                </section>
                @endadmin_can

                @if(auth('admin')->user()->hasAnyPermission(['view_events', 'view_projects', 'view_residents']))
                <section class="dashboard-section d-none" data-dashboard-section="community">
                    <div class="section-heading">
                        <div>
                            <span class="section-kicker"><i class="fas fa-people-roof"></i> Community insights</span>
                            <h3>Programs, projects, and resident profile</h3>
                        </div>
                    </div>
                    <div class="dashboard-chart-grid three-col">
                        @admin_can('view_events')
                        <article class="dashboard-card compact-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-calendar"></i> Events</h4>
                                    <p>Upcoming versus past activities.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="eventsChart" data-chart-title="Events"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container small-chart">
                                <canvas id="eventsChart"></canvas>
                            </div>
                            <div class="dashboard-summary-line text-center" id="eventsSummary"></div>
                        </article>
                        @endadmin_can

                        @admin_can('view_projects')
                        <article class="dashboard-card compact-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-diagram-project"></i> Projects Status</h4>
                                    <p>Ongoing and completed project mix.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="projectsChart" data-chart-title="Projects Status"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container small-chart">
                                <canvas id="projectsChart"></canvas>
                            </div>
                            <div class="dashboard-summary-line text-center" id="projectsSummary"></div>
                        </article>
                        @endadmin_can

                        @admin_can('view_residents')
                        <article class="dashboard-card compact-card">
                            <div class="card-head">
                                <div>
                                    <h4><i class="fas fa-chart-column"></i> Age Distribution</h4>
                                    <p>Resident age groups for planning.</p>
                                </div>
                                <button type="button" class="icon-action" data-chart-expand="ageChart" data-chart-title="Age Distribution"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                            </div>
                            <div class="chart-container small-chart">
                                <canvas id="ageChart"></canvas>
                            </div>
                            <div class="dashboard-summary-line text-center" id="ageSummary"></div>
                        </article>
                        @endadmin_can
                    </div>
                </section>
                @endif

                @unless(auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast', 'view_blotter', 'view_announcements', 'view_events', 'view_projects', 'view_residents']))
                <div class="no-permission-card">
                    <i class="fas fa-lock"></i>
                    <h4>No Dashboard Access</h4>
                    <p>You do not have permission to view dashboard data.</p>
                    <p class="small">Contact your administrator if you need access.</p>
                </div>
                @endunless
            </div>

            <div class="modal fade" id="chartFocusModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content chart-focus-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="chartFocusTitle">
                                <i class="fas fa-chart-line me-2"></i>Chart Preview
                                <span class="chart-focus-range-note" id="chartFocusRangeNote"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="chart-focus-scroll" id="chartFocusScroll">
                                <div class="chart-focus-canvas-wrap" id="chartFocusCanvasWrap">
                                    <canvas id="chartFocusCanvas"></canvas>
                                </div>
                            </div>
                            <div id="chartFocusFallback" class="text-muted text-center py-5 d-none">Chart preview is not available yet.</div>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
@include('shared.js-config', [
    'id' => 'js-config-admin-dashboard',
    'data' => [
        'urls' => [
            'forecast' => route('admin.dashboard.forecast', [], false),
            'liveStats' => route('admin.dashboard.live_stats', [], false),
            'refreshForecast' => route('admin.dashboard.refresh_forecast', [], false),
        ],
        'permissions' => [
            'view_forecast' => auth('admin')->user()->hasPermission('view_forecast'),
            'view_blotter' => auth('admin')->user()->hasPermission('view_blotter'),
            'view_announcements' => auth('admin')->user()->hasPermission('view_announcements'),
            'view_events' => auth('admin')->user()->hasPermission('view_events'),
            'view_projects' => auth('admin')->user()->hasPermission('view_projects'),
            'view_residents' => auth('admin')->user()->hasPermission('view_residents'),
            'viewDashboardOrForecast' => auth('admin')->user()->hasAnyPermission(['view_dashboard', 'view_forecast']),
        ],
        'csrfToken' => csrf_token(),
        'stats' => [
            'totalResidents' => $stats['totalResidents'] ?? 0,
            'pendingRequests' => $stats['pendingRequests'] ?? 0,
            'openReports' => $stats['openReports'] ?? 0,
            'upcomingEvents' => $stats['upcomingEvents'] ?? 0,
        ],
    ],
])
<script src="{{ asset('js/pages/admin/dashboard-main.js') }}"></script>
@endpush
