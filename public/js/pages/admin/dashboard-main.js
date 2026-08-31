const __cfg_dashboard = JSON.parse(document.getElementById('js-config-admin-dashboard')?.dataset.jsConfig || '{}');

const DASHBOARD_URLS = __cfg_dashboard.urls;

        let forecastData = null;
        const chartInstances = {};
        let chartFocusInstance = null;
        const STATS_REFRESH_MS = 120000;
        const FORECAST_REFRESH_MS = 180000;
        let statsRefreshTimer = null;
        let forecastRefreshTimer = null;
        let isRefreshingStats = false;
        let isManualRefreshInProgress = false;
        let isRefreshingForecastCommand = false;
        let autoRefreshEnabled = true;

        let latestLiveStats = {
            totalResidents: 0,
            pendingRequests: 0,
            openReports: 0,
            upcomingEvents: 0
        };

        function parseForecastDate(dateStr) {

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
            const firstChartTitle = document.querySelector('#dailyRequestsChart')
                ?.closest('.dashboard-card, .chart-card')
                ?.querySelector('.summary-badge');

            if (!firstChartTitle) {
                return;
            }

            if (isLoading) {
                firstChartTitle.innerHTML = '<span class="chart-loading">Loading forecast</span>';
            } else if (message) {
                firstChartTitle.textContent = message;
            } else {
                firstChartTitle.textContent = `${window.dashboardRangeDays || 7}-day forecast`;
            }
        }

        function unwrapForecastPayload(payload) {
            if (!payload || typeof payload !== 'object') {
                return null;
            }

            if (payload.data && typeof payload.data === 'object' && !payload.applications) {
                return payload.data;
            }

            if (payload.forecast && typeof payload.forecast === 'object' && !payload.applications) {
                return payload.forecast;
            }

            return payload;
        }

        function updateForecastMetadata(data = {}) {
            const metadata = data?.forecast_metadata || {};
            const lastUpdatedEl = document.getElementById('forecastLastUpdatedText');

            if (!lastUpdatedEl) {
                return;
            }

            const trainingEnd = metadata.training_end_date || data?.request_forecast?.daily_forecast?.training_end_date;
            const model = metadata.primary_model || data?.request_forecast?.daily_forecast?.model;
            const details = [];

            if (trainingEnd) {
                const date = new Date(`${trainingEnd}T00:00:00`);
                details.push(Number.isNaN(date.getTime())
                    ? `Training through ${trainingEnd}`
                    : `Training through ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`);
            }

            if (model) {
                details.push(String(model).replace(/_/g, ' ').toUpperCase());
            }

            if (details.length) {
                lastUpdatedEl.textContent = details.join(' • ');
            }
        }

        async function loadForecastData() {
            setChartLoadingState(true);
            try {
                const response = await fetch(`${DASHBOARD_URLS.forecast}?v=${Date.now()}`, {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Forecast endpoint returned HTTP ${response.status}`);
                }

                const payload = await response.json();
                const data = unwrapForecastPayload(payload);

                if (!data || data.error) {
                    console.error('Forecast error:', data?.error || 'Invalid JSON response');
                    setChartLoadingState(false, 'Forecast unavailable');
                    return;
                }

                forecastData = data;
                updateForecastMetadata(data);

                try {
                    renderServicePipelineChart(latestLiveStats);
                    renderReadinessChart(latestLiveStats);
                } catch (e) {
                    console.error('Overview chart data error:', e);
                }

                if (__cfg_dashboard.permissions.view_forecast) {
                    try { buildApplicationsCharts(data.applications); } catch (e) { console.error('Applications chart error:', e); }
                }
                if (__cfg_dashboard.permissions.view_blotter) {
                    try { buildBlotterChart(data.blotter_reports); } catch (e) { console.error('Blotter chart error:', e); }
                }
                if (__cfg_dashboard.permissions.view_announcements) {
                    try { buildAnnouncementsChart(data.announcements); } catch (e) { console.error('Announcements chart error:', e); }
                }
                if (__cfg_dashboard.permissions.view_events) {
                    try { buildEventsChart(data.events); } catch (e) { console.error('Events chart error:', e); }
                }
                if (__cfg_dashboard.permissions.view_projects) {
                    try { buildProjectsChart(data.projects); } catch (e) { console.error('Projects chart error:', e); }
                }
                if (__cfg_dashboard.permissions.view_residents) {
                    try { buildAgeChart(data.residents?.age_distribution); } catch (e) { console.error('Age chart error:', e); }
                }
                if (__cfg_dashboard.permissions.viewDashboardOrForecast) {
                    try { buildRequestTypeChart(data); } catch (e) { console.error('Request types chart error:', e); }
                    try { buildDailyRequestsChart(data); } catch (e) { console.error('Daily requests chart error:', e); }
                }

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
                const response = await fetch(DASHBOARD_URLS.liveStats, {
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
                const response = await fetch(DASHBOARD_URLS.refreshForecast, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': __cfg_dashboard.csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const payload = await response.json();

                if (!response.ok || !payload?.success) {
                    throw new Error(
                        payload?.error ||
                        payload?.output ||
                        payload?.message ||
                        'Failed to refresh forecast.'
                    );
                }

                if (payload?.forecastLastUpdated) {
                    const lastUpdatedEl = document.getElementById('forecastLastUpdatedText');

                    if (lastUpdatedEl) {
                        lastUpdatedEl.textContent = payload.forecastLastUpdated;
                    }
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

        function formatModuleName(name) {
            return name
                .replace(/_/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase())
                .replace('Applications', '');
        }

        window.dashboardRangeDays = 7;

        function numberOrZero(value) {
            const num = Number(value);
            return Number.isFinite(num) ? num : 0;
        }

        function getRangeLabel(days = window.dashboardRangeDays || 7) {
            return `${Number(days) || 7} days`;
        }

        function getForecastMap(source = {}, days = window.dashboardRangeDays || 7) {
            const normalizedDays = Math.min(Math.max(Number(days) || 7, 7), 90);
            const key = `${normalizedDays}_days`;

            if (!source || typeof source !== 'object' || Array.isArray(source)) {
                return {};
            }

            if (source.daily_forecast && typeof source.daily_forecast === 'object') {
                return getForecastMap(source.daily_forecast, normalizedDays);
            }

            if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
                return source[key];
            }

            return Object.fromEntries(
                Object.entries(source).filter(([date, value]) => {
                    return parseForecastDate(date) && Number.isFinite(Number(value));
                })
            );
        }

        function getForecastRangeSummary(source = {}, days = window.dashboardRangeDays || 7) {
            const normalizedDays = Math.min(Math.max(Number(days) || 7, 7), 90);
            const key = `${normalizedDays}_days`;
            const provided = source?.forecast_ranges?.[key] || source?.[key] || null;

            if (provided && ('total' in provided || 'daily_average' in provided || 'peak' in provided)) {
                return provided;
            }

            const dailyForecast = source?.daily_forecast || source || {};
            const map = getForecastMap(dailyForecast, normalizedDays);
            const values = Object.values(map).map(numberOrZero);

            if (!values.length) {
                return null;
            }

            const total = values.reduce((sum, value) => sum + value, 0);
            const configuredAverage = dailyForecast?.average?.[key];

            return {
                total,
                daily_average: Number.isFinite(Number(configuredAverage))
                    ? Number(configuredAverage)
                    : total / values.length,
                peak: Math.max(...values)
            };
        }

        function getXAxisMaxTicks() {
            const days = Number(window.dashboardRangeDays || 7);
            if (days >= 90) return 9;
            if (days >= 30) return 10;
            return 7;
        }

        function getPointRadius() {
            const days = Number(window.dashboardRangeDays || 7);
            if (days >= 90) return 0;
            if (days >= 30) return 1.5;
            return 3;
        }

        function getOverviewStatsFromForecast(data = {}) {
            const pipeline = data?.dashboard_overview?.service_pipeline || {};
            const applications = data?.applications || {};

            const pendingFromApplications = Object.values(applications).reduce((total, app) => {
                const statuses = app?.status_distribution || {};
                return total + Object.entries(statuses).reduce((sum, [status, count]) => {
                    const label = String(status || '').toLowerCase().replace(/_/g, ' ');
                    const isPending = ['pending', 'submitted', 'processing', 'review'].some((word) => label.includes(word));
                    return sum + (isPending ? numberOrZero(count) : 0);
                }, 0);
            }, 0);

            const reportStatuses = data?.blotter_reports?.analytics?.by_status || {};
            const openReportsFromAnalytics = Object.entries(reportStatuses).reduce((sum, [status, count]) => {
                const label = String(status || '').toLowerCase().replace(/_/g, ' ');
                const isOpen = ['open', 'pending', 'ongoing', 'investigation', 'active'].some((word) => label.includes(word));
                return sum + (isOpen ? numberOrZero(count) : 0);
            }, 0);

            return {
                totalResidents: numberOrZero(pipeline.residents ?? data?.residents?.total_residents),
                pendingRequests: numberOrZero(pipeline.pending_requests ?? pendingFromApplications),
                openReports: numberOrZero(pipeline.open_reports ?? openReportsFromAnalytics),
                upcomingEvents: numberOrZero(pipeline.upcoming_events ?? data?.events?.upcoming_vs_past?.upcoming)
            };
        }

        function hasMeaningfulData(values) {
            return Array.isArray(values) && values.some((value) => numberOrZero(value) > 0);
        }

        function nextDateLabels(days) {
            const labels = [];
            const count = Math.min(Math.max(days || 7, 7), 90);
            const now = new Date();
            for (let i = 0; i < count; i++) {
                const date = new Date(now);
                date.setDate(now.getDate() + i);
                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            }
            return labels;
        }

        function ensureChartOverlay(chartId) {
            const canvas = document.getElementById(chartId);
            const container = canvas?.closest('.chart-container');
            if (!container) {
                return null;
            }
            let overlay = container.querySelector('.chart-empty-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'chart-empty-overlay';
                overlay.innerHTML = '<div class="chart-empty-content"><strong>No data yet</strong><span>This chart is ready once records are added.</span></div>';
                container.appendChild(overlay);
            }
            return overlay;
        }

        function setChartEmptyState(chartId, isEmpty, title = 'No data yet', message = 'This chart is ready once records are added.') {
            const overlay = ensureChartOverlay(chartId);
            if (!overlay) {
                return;
            }
            overlay.classList.toggle('show', Boolean(isEmpty));
            const titleEl = overlay.querySelector('strong');
            const msgEl = overlay.querySelector('span');
            if (titleEl) titleEl.textContent = title;
            if (msgEl) msgEl.textContent = message;
        }

        function baseChartOptions(extra = {}) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false, labels: { usePointStyle: true, boxWidth: 8 } },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, .92)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    },
                    ...(extra.plugins || {})
                },
                scales: extra.scales || {
                    x: { grid: { display: false }, ticks: { color: '#667085', maxTicksLimit: getXAxisMaxTicks() } },
                    y: { beginAtZero: true, grid: { color: '#f2f4f7' }, ticks: { color: '#667085', precision: 0 } }
                },
                ...extra
            };
        }

        function renderServicePipelineChart(stats = {}) {
            const pipeline = forecastData?.dashboard_overview?.service_pipeline || {};
            const rows = [
                { label: 'Residents', value: numberOrZero(pipeline.residents ?? stats.totalResidents) },
                { label: 'Pending requests', value: numberOrZero(pipeline.pending_requests ?? stats.pendingRequests) },
                { label: 'Open reports', value: numberOrZero(pipeline.open_reports ?? stats.openReports) },
                { label: 'Upcoming events', value: numberOrZero(pipeline.upcoming_events ?? stats.upcomingEvents) }
            ];
            const values = rows.map((row) => row.value);
            setChartEmptyState('pipelineSnapshotChart', !hasMeaningfulData(values), 'Dashboard is clear', 'No active records yet. New activity will appear here automatically.');
            renderChart('pipelineSnapshotChart', {
                type: 'bar',
                data: {
                    labels: rows.map((row) => row.label),
                    datasets: [{
                        label: 'Count',
                        data: hasMeaningfulData(values) ? values : [0, 0, 0, 0],
                        backgroundColor: ['#0369a1', '#d97706', '#dc2626', '#16803c'],
                        borderRadius: 10,
                        maxBarThickness: 44
                    }]
                },
                options: baseChartOptions({
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#667085', font: { weight: 700 } } },
                        y: { beginAtZero: true, grid: { color: '#f2f4f7' }, ticks: { precision: 0, color: '#667085' } }
                    }
                })
            });
        }

        function renderReadinessChart(stats = {}) {
            const readiness = forecastData?.dashboard_overview?.data_readiness || {};
            const rows = [
                { label: 'Residents', ready: numberOrZero(readiness.residents?.score ?? (numberOrZero(stats.totalResidents) > 0 ? 100 : 0)) },
                { label: 'Requests', ready: numberOrZero(readiness.requests?.score ?? (numberOrZero(stats.pendingRequests) > 0 ? 100 : 0)) },
                { label: 'Reports', ready: numberOrZero(readiness.reports?.score ?? (numberOrZero(stats.openReports) > 0 ? 100 : 0)) },
                { label: 'Events', ready: numberOrZero(readiness.events?.score ?? (numberOrZero(stats.upcomingEvents) > 0 ? 100 : 0)) },
                { label: 'Projects', ready: numberOrZero(readiness.projects?.score ?? 0) },
                { label: 'Announcements', ready: numberOrZero(readiness.announcements?.score ?? 0) }
            ];
            const values = rows.map((row) => row.ready);
            setChartEmptyState('readinessChart', !hasMeaningfulData(values), 'Ready for first records', 'Your modules are connected. Add records to activate insights.');
            renderChart('readinessChart', {
                type: 'doughnut',
                data: {
                    labels: rows.map((row) => row.label),
                    datasets: [{
                        data: hasMeaningfulData(values) ? values : [1, 1, 1, 1],
                        backgroundColor: hasMeaningfulData(values) ? ['#0369a1', '#d97706', '#dc2626', '#16803c'] : ['#e5e7eb', '#e5e7eb', '#e5e7eb', '#e5e7eb'],
                        borderWidth: 0
                    }]
                },
                options: baseChartOptions({
                    cutout: '68%',
                    plugins: { legend: { display: true, position: 'bottom' } },
                    scales: {}
                })
            });
        }

        function initializeStaticStats(stats = {}) {
            latestLiveStats = {
                totalResidents: numberOrZero(stats.totalResidents),
                pendingRequests: numberOrZero(stats.pendingRequests),
                openReports: numberOrZero(stats.openReports),
                upcomingEvents: numberOrZero(stats.upcomingEvents)
            };

            const totalResidentsEl = document.getElementById('totalResidents');
            if (totalResidentsEl) {
                totalResidentsEl.textContent = latestLiveStats.totalResidents.toLocaleString();
            }

            const pendingEl = document.getElementById('pendingRequests');
            if (pendingEl) {
                pendingEl.textContent = latestLiveStats.pendingRequests.toLocaleString();
            }

            const openReportsEl = document.getElementById('openReports');
            if (openReportsEl) {
                openReportsEl.textContent = latestLiveStats.openReports.toLocaleString();
            }

            const upcomingEventsEl = document.getElementById('upcomingEvents');
            if (upcomingEventsEl) {
                upcomingEventsEl.textContent = latestLiveStats.upcomingEvents.toLocaleString();
            }

            renderServicePipelineChart(latestLiveStats);
            renderReadinessChart(latestLiveStats);
        }

        function limitedForecastEntries(source, fallbackDays = window.dashboardRangeDays || 7) {
            const days = Math.min(Math.max(Number(fallbackDays) || 7, 7), 90);
            const forecastMap = getForecastMap(source || {}, days);
            const entries = sortForecastEntries(forecastMap).slice(0, days);

            return entries.map((item) => ({
                date: item.date,
                label: shortDate(item.date),
                value: Math.round(numberOrZero(item.value) * 10) / 10
            }));
        }

        function buildDailyRequestsChart(data) {
            const selectedDays = window.dashboardRangeDays || 7;
            const applications = data?.applications || {};
            const mergedByDate = {};

            const requestForecastMap = getForecastMap(data?.request_forecast, selectedDays);
            const dashboardCombinedMap = getForecastMap(
                data?.dashboard_overview?.combined_application_forecast || {},
                selectedDays
            );

            const preferredMap = Object.keys(requestForecastMap).length
                ? requestForecastMap
                : dashboardCombinedMap;

            if (Object.keys(preferredMap).length) {
                Object.assign(mergedByDate, preferredMap);
            } else {
                [
                    applications.barangay_clearances,
                    applications.indigency_applications,
                    applications.residency_applications
                ].forEach((module) => {
                    const map = getForecastMap(module, selectedDays);
                    Object.entries(map).forEach(([date, value]) => {
                        mergedByDate[date] = (mergedByDate[date] || 0) + numberOrZero(value);
                    });
                });
            }

            let entries = limitedForecastEntries(mergedByDate, selectedDays);
            const isEmpty = !entries.length || !hasMeaningfulData(entries.map((item) => item.value));

            if (!entries.length) {
                entries = nextDateLabels(selectedDays).map((label) => ({ label, value: 0 }));
            }

            setChartEmptyState('dailyRequestsChart', isEmpty, 'No request trend yet', 'Submit service requests to activate daily trend insights.');

            const canvas = document.getElementById('dailyRequestsChart');
            const currentType = canvas?.dataset.chartTypeCurrent || 'bar';

            renderChart('dailyRequestsChart', {
                type: currentType,
                data: {
                    labels: entries.map((item) => item.label),
                    datasets: [{
                        label: 'Predicted requests',
                        data: entries.map((item) => item.value),
                        backgroundColor: 'rgba(211, 47, 47, .18)',
                        borderColor: '#d32f2f',
                        borderWidth: 3,
                        borderRadius: 8,
                        pointRadius: getPointRadius(),
                        pointHoverRadius: 5,
                        tension: .35,
                        fill: currentType === 'line'
                    }]
                },
                options: baseChartOptions()
            });

            setChartLoadingState(false);
        }

        function buildRequestTypeChart(data) {
            const types = {
                'Clearances': 0,
                'Indigency': 0,
                'Residency': 0
            };
            if (data?.applications?.barangay_clearances?.status_distribution) {
                types['Clearances'] = Object.values(data.applications.barangay_clearances.status_distribution).reduce((a, b) => a + numberOrZero(b), 0);
            }
            if (data?.applications?.indigency_applications?.status_distribution) {
                types['Indigency'] = Object.values(data.applications.indigency_applications.status_distribution).reduce((a, b) => a + numberOrZero(b), 0);
            }
            if (data?.applications?.residency_applications?.status_distribution) {
                types['Residency'] = Object.values(data.applications.residency_applications.status_distribution).reduce((a, b) => a + numberOrZero(b), 0);
            }
            const values = Object.values(types);
            const isEmpty = !hasMeaningfulData(values);
            setChartEmptyState('requestsTypeChart', isEmpty, 'No request types yet', 'Request categories will appear after residents submit applications.');
            renderChart('requestsTypeChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(types),
                    datasets: [{
                        data: isEmpty ? [1, 1, 1] : values,
                        backgroundColor: isEmpty ? ['#e5e7eb', '#e5e7eb', '#e5e7eb'] : ['#d32f2f', '#0369a1', '#16803c'],
                        borderWidth: 0
                    }]
                },
                options: baseChartOptions({
                    cutout: '64%',
                    plugins: { legend: { display: true, position: 'bottom' } },
                    scales: {}
                })
            });
        }

        function buildApplicationsCharts(applications) {
            const container = document.getElementById('applicationsForecast');
            if (!container) return;
            container.innerHTML = '';

            const defaultKeys = ['barangay_clearances', 'indigency_applications', 'residency_applications'];
            const entriesSource = applications && Object.keys(applications).length ? Object.entries(applications) : defaultKeys.map((key) => [key, {}]);

            entriesSource.forEach(([key, value]) => {
                let entries = limitedForecastEntries(value, window.dashboardRangeDays || 7);
                const isEmpty = !entries.length || !hasMeaningfulData(entries.map((item) => item.value));
                if (!entries.length) {
                    entries = nextDateLabels(window.dashboardRangeDays || 7).map((label) => ({ label, value: 0 }));
                }

                const values = entries.map((item) => item.value);
                const rangeSummary = getForecastRangeSummary(value, window.dashboardRangeDays || 7);
                const totalForecast = rangeSummary ? numberOrZero(rangeSummary.total).toFixed(1) : values.reduce((a, b) => a + b, 0).toFixed(1);
                const avgForecast = rangeSummary ? numberOrZero(rangeSummary.daily_average).toFixed(1) : (values.length ? (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1) : '0.0');
                const maxForecast = rangeSummary ? numberOrZero(rangeSummary.peak).toFixed(1) : (values.length ? Math.max(...values).toFixed(1) : '0.0');
                const trend = values.length > 1 && values[values.length - 1] > values[0] ? 'up' : 'stable';
                const chartId = `chart-${key}`;

                const col = document.createElement('article');
                col.className = 'dashboard-card';
                col.innerHTML = `
                    <div class="card-head">
                        <div>
                            <h4><i class="fas fa-file-lines"></i>${formatModuleName(key)}</h4>
                            <p>${isEmpty ? 'Waiting for historical records.' : `${getRangeLabel()} forecast from historical records.`}</p>
                        </div>
                        <button type="button" class="icon-action" data-chart-expand="${chartId}" data-chart-title="${formatModuleName(key)} Forecast"><i class="fas fa-up-right-and-down-left-from-center"></i></button>
                    </div>
                    <div class="d-flex justify-content-between gap-2 mb-2 small">
                        <div><span class="text-muted d-block">Total</span><strong>${totalForecast}</strong></div>
                        <div><span class="text-muted d-block">Daily avg</span><strong>${avgForecast}</strong></div>
                        <div><span class="text-muted d-block">Peak</span><strong>${maxForecast}</strong></div>
                    </div>
                    <div class="chart-container small-chart"><canvas id="${chartId}"></canvas></div>
                    <div class="dashboard-summary-line"><i class="fas fa-database me-1"></i>${numberOrZero(value?.historical_count || Object.values(value?.status_distribution || {}).reduce((sum, count) => sum + numberOrZero(count), 0))} historical records</div>
                `;
                container.appendChild(col);
                setTimeout(() => {
                    setChartEmptyState(chartId, isEmpty, 'No forecast yet', 'Add historical records to generate a reliable prediction.');
                    renderChart(chartId, {
                        type: 'line',
                        data: {
                            labels: entries.map((item) => item.label),
                            datasets: [{
                                label: 'Forecast',
                                data: values,
                                borderColor: '#d32f2f',
                                backgroundColor: 'rgba(211, 47, 47, .12)',
                                borderWidth: 3,
                                pointRadius: getPointRadius(),
                                pointHoverRadius: 5,
                                tension: .35,
                                fill: true
                            }]
                        },
                        options: baseChartOptions()
                    });
                    bindChartExpandButtons();
                }, 50);
            });
        }

        function buildBlotterChart(blotter) {
            let entries = limitedForecastEntries(blotter, window.dashboardRangeDays || 7);
            const isEmpty = !entries.length || !hasMeaningfulData(entries.map((item) => item.value));
            if (!entries.length) entries = nextDateLabels(window.dashboardRangeDays || 7).map((label) => ({ label, value: 0 }));
            setChartEmptyState('blotterForecastChart', isEmpty, 'No incident trend yet', 'Incident forecasts will appear when reports are recorded.');

            renderChart('blotterForecastChart', {
                type: 'line',
                data: { labels: entries.map((item) => item.label), datasets: [{ label: 'Predicted incidents', data: entries.map((item) => item.value), borderColor: '#dc2626', backgroundColor: 'rgba(220, 38, 38, .12)', borderWidth: 3, tension: .35, fill: true }] },
                options: baseChartOptions()
            });
            const total = entries.reduce((sum, item) => sum + item.value, 0);
            const avg = entries.length ? total / entries.length : 0;
            const summaryEl = document.getElementById('blotterSummary');
            if (summaryEl) summaryEl.innerHTML = `<i class="fas fa-chart-line me-1"></i>${getRangeLabel()} expected total: ${total.toFixed(1)} | Daily average: ${avg.toFixed(1)}`;
        }

        function buildAnnouncementsChart(announcements) {
            let entries = limitedForecastEntries(announcements, window.dashboardRangeDays || 7);
            const isEmpty = !entries.length || !hasMeaningfulData(entries.map((item) => item.value));
            if (!entries.length) entries = nextDateLabels(window.dashboardRangeDays || 7).map((label) => ({ label, value: 0 }));
            setChartEmptyState('announcementsForecastChart', isEmpty, 'No publishing trend yet', 'Announcement forecasts will appear after publishing activity is recorded.');

            renderChart('announcementsForecastChart', {
                type: 'line',
                data: { labels: entries.map((item) => item.label), datasets: [{ label: 'Predicted announcements', data: entries.map((item) => item.value), borderColor: '#0369a1', backgroundColor: 'rgba(3, 105, 161, .12)', borderWidth: 3, tension: .35, fill: true }] },
                options: baseChartOptions()
            });
            const total = entries.reduce((sum, item) => sum + item.value, 0);
            const summaryEl = document.getElementById('announcementsSummary');
            if (summaryEl) summaryEl.innerHTML = `<i class="fas fa-info-circle me-1"></i>${getRangeLabel()} expected total: ${total.toFixed(1)} announcements`;
        }

        function buildEventsChart(events) {
            const upcoming = numberOrZero(events?.upcoming_vs_past?.upcoming);
            const past = numberOrZero(events?.upcoming_vs_past?.past);
            const isEmpty = upcoming + past === 0;
            setChartEmptyState('eventsChart', isEmpty, 'No events yet', 'Upcoming and past events will appear here.');
            renderChart('eventsChart', {
                type: 'doughnut',
                data: { labels: ['Upcoming', 'Past'], datasets: [{ data: isEmpty ? [1, 1] : [upcoming, past], backgroundColor: isEmpty ? ['#e5e7eb', '#f3f4f6'] : ['#16803c', '#667085'], borderWidth: 0 }] },
                options: baseChartOptions({ cutout: '68%', plugins: { legend: { display: true, position: 'bottom' } }, scales: {} })
            });
            const summaryEl = document.getElementById('eventsSummary');
            if (summaryEl) summaryEl.innerHTML = `<span class="summary-badge">${upcoming} upcoming</span> <span class="summary-badge">${numberOrZero(events?.total_events)} total</span>`;
        }

        function buildProjectsChart(projects) {
            const dist = projects?.status_distribution || { ongoing: 0, completed: 0, archived: 0 };
            const labels = Object.keys(dist).map((label) => label.charAt(0).toUpperCase() + label.slice(1));
            const values = Object.values(dist).map(numberOrZero);
            const isEmpty = !hasMeaningfulData(values);
            setChartEmptyState('projectsChart', isEmpty, 'No projects yet', 'Project progress will show after creating project records.');
            renderChart('projectsChart', {
                type: 'doughnut',
                data: { labels, datasets: [{ data: isEmpty ? [1, 1, 1] : values, backgroundColor: isEmpty ? ['#e5e7eb', '#f3f4f6', '#e5e7eb'] : ['#0369a1', '#16803c', '#d97706'], borderWidth: 0 }] },
                options: baseChartOptions({ cutout: '68%', plugins: { legend: { display: true, position: 'bottom' } }, scales: {} })
            });
            const summaryEl = document.getElementById('projectsSummary');
            if (summaryEl) summaryEl.innerHTML = `Average progress: ${numberOrZero(projects?.average_progress)}%`;
        }

        function buildAgeChart(ageDist) {
            const ranges = { '0-17': 0, '18-30': 0, '31-45': 0, '46-60': 0, '60+': 0 };
            Object.entries(ageDist || {}).forEach(([age, count]) => {
                const a = parseInt(age, 10);
                const n = numberOrZero(count);
                if (Number.isNaN(a)) return;
                if (a < 18) ranges['0-17'] += n;
                else if (a <= 30) ranges['18-30'] += n;
                else if (a <= 45) ranges['31-45'] += n;
                else if (a <= 60) ranges['46-60'] += n;
                else ranges['60+'] += n;
            });
            const values = Object.values(ranges);
            const isEmpty = !hasMeaningfulData(values);
            setChartEmptyState('ageChart', isEmpty, 'No age data yet', 'Resident demographics will show after profiles are completed.');
            renderChart('ageChart', {
                type: 'bar',
                data: { labels: Object.keys(ranges), datasets: [{ data: values, backgroundColor: '#d32f2f', borderRadius: 8, maxBarThickness: 42 }] },
                options: baseChartOptions()
            });
            const total = values.reduce((a, b) => a + b, 0);
            const summaryEl = document.getElementById('ageSummary');
            if (summaryEl) summaryEl.innerHTML = `Total residents: ${total} | Senior: ${ranges['60+']}`;
        }

        function bindDashboardInteractions() {
            document.querySelectorAll('[data-dashboard-tab]').forEach((tab) => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.dashboardTab;
                    document.querySelectorAll('[data-dashboard-tab]').forEach((btn) => btn.classList.toggle('active', btn === tab));
                    document.querySelectorAll('[data-dashboard-section]').forEach((section) => {
                        section.classList.toggle('d-none', section.dataset.dashboardSection !== target);
                    });
                    setTimeout(() => Object.values(chartInstances).forEach((chart) => chart?.resize?.()), 80);
                });
            });

            document.querySelectorAll('[data-range-days]').forEach((chip) => {
                chip.addEventListener('click', () => {
                    window.dashboardRangeDays = parseInt(chip.dataset.rangeDays, 10) || 7;
                    document.querySelectorAll('[data-range-days]').forEach((btn) => btn.classList.toggle('active', btn === chip));
                    setChartLoadingState(false);
                    if (forecastData) {

                        renderServicePipelineChart(latestLiveStats);
                        renderReadinessChart(latestLiveStats);
                        buildDailyRequestsChart(forecastData);
                        buildRequestTypeChart(forecastData);
                        buildApplicationsCharts(forecastData.applications);
                        buildBlotterChart(forecastData.blotter_reports);
                        buildAnnouncementsChart(forecastData.announcements);
                        setTimeout(() => Object.values(chartInstances).forEach((chart) => chart?.resize?.()), 80);
                    }
                });
            });

            document.querySelectorAll('[data-chart-type]').forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    const target = toggle.dataset.chartTarget;
                    const canvas = document.getElementById(target);
                    if (!canvas) return;
                    canvas.dataset.chartTypeCurrent = toggle.dataset.chartType || 'bar';
                    document.querySelectorAll(`[data-chart-target="${target}"]`).forEach((btn) => btn.classList.toggle('active', btn === toggle));
                    if (forecastData) {
                        buildDailyRequestsChart(forecastData);
                    }
                });
            });

            bindChartExpandButtons();
        }

        function copyChartDataset(dataset) {
            const copied = {};

            Object.keys(dataset || {}).forEach((key) => {
                const value = dataset[key];

                if (Array.isArray(value)) {
                    copied[key] = value.map((item) => {
                        if (item && typeof item === 'object') {
                            return { ...item };
                        }
                        return item;
                    });
                    return;
                }

                if (value && typeof value === 'object') {
                    copied[key] = { ...value };
                    return;
                }

                copied[key] = value;
            });

            return copied;
        }

        function getExpandedChartWidth(labelCount) {
            if (labelCount >= 80) return Math.max(4200, labelCount * 50);
            if (labelCount >= 25) return Math.max(1900, labelCount * 58);
            if (labelCount >= 14) return Math.max(1250, labelCount * 64);
            return 1100;
        }

        function getExpandedChartOptions(sourceChart, labelCount) {
            const chartType = sourceChart?.config?.type || sourceChart?.config?._config?.type || 'line';
            const circularTypes = ['pie', 'doughnut', 'polarArea', 'radar'];
            const isCircular = circularTypes.includes(chartType);
            const hasLongRange = labelCount >= 20;

            const options = {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                interaction: {
                    intersect: false,
                    mode: isCircular ? 'nearest' : 'index'
                },
                plugins: {
                    legend: {
                        display: sourceChart?.options?.plugins?.legend?.display ?? isCircular,
                        position: sourceChart?.options?.plugins?.legend?.position || 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10,
                            padding: 16
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(17, 24, 39, .94)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    }
                }
            };

            if (!isCircular) {
                options.scales = {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#667085',
                            autoSkip: false,
                            maxRotation: hasLongRange ? 45 : 0,
                            minRotation: hasLongRange ? 45 : 0,
                            padding: 8,
                            font: { size: 11, weight: '600' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f2f4f7' },
                        ticks: {
                            color: '#667085',
                            precision: 0,
                            padding: 8
                        }
                    }
                };
            }

            return options;
        }

        function openExpandedChart(chartId, chartTitle = 'Chart Preview') {
            const sourceChart = chartInstances[chartId];
            const modalEl = document.getElementById('chartFocusModal');
            const titleEl = document.getElementById('chartFocusTitle');
            const canvasWrap = document.getElementById('chartFocusCanvasWrap');
            const canvas = document.getElementById('chartFocusCanvas');
            const scrollEl = document.getElementById('chartFocusScroll');
            const fallbackEl = document.getElementById('chartFocusFallback');

            if (titleEl) {
                titleEl.innerHTML = `<i class="fas fa-chart-line me-2"></i>${chartTitle}<span class="chart-focus-range-note" id="chartFocusRangeNote"></span>`;
            }

            const refreshedRangeNote = document.getElementById('chartFocusRangeNote');

            if (!sourceChart || !canvas || !canvasWrap) {
                canvasWrap?.classList.add('d-none');
                fallbackEl?.classList.remove('d-none');
                if (modalEl && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
                return;
            }

            const labels = Array.isArray(sourceChart.data?.labels)
                ? [...sourceChart.data.labels]
                : [];
            const datasets = Array.isArray(sourceChart.data?.datasets)
                ? sourceChart.data.datasets.map(copyChartDataset)
                : [];
            const labelCount = labels.length;
            const chartWidth = getExpandedChartWidth(labelCount);

            canvasWrap.classList.remove('d-none');
            fallbackEl?.classList.add('d-none');
            canvasWrap.style.width = `${chartWidth}px`;
            canvasWrap.style.minWidth = `${chartWidth}px`;

            if (refreshedRangeNote) {
                const selectedDays = Number(window.dashboardRangeDays || 7);
                refreshedRangeNote.innerHTML = labelCount >= 20
                    ? `<i class="fas fa-arrows-left-right"></i>${labelCount} dates · scroll horizontally`
                    : `<i class="fas fa-calendar-days"></i>${Math.max(labelCount, selectedDays)} data points`;
            }

            const renderFocusedChart = () => {
                if (chartFocusInstance) {
                    chartFocusInstance.destroy();
                    chartFocusInstance = null;
                }

                const chartType = sourceChart.config?.type || sourceChart.config?._config?.type || 'line';
                chartFocusInstance = new Chart(canvas.getContext('2d'), {
                    type: chartType,
                    data: { labels, datasets },
                    options: getExpandedChartOptions(sourceChart, labelCount)
                });

                if (scrollEl) {
                    scrollEl.scrollLeft = 0;
                    scrollEl.scrollTop = 0;
                }
            };

            if (modalEl && window.bootstrap) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                if (modalEl.classList.contains('show')) {
                    requestAnimationFrame(renderFocusedChart);
                } else {
                    modalEl.addEventListener('shown.bs.modal', () => {
                        requestAnimationFrame(renderFocusedChart);
                    }, { once: true });
                    modal.show();
                }
            } else {
                renderFocusedChart();
            }
        }

        function bindChartExpandButtons() {
            document.querySelectorAll('[data-chart-expand]').forEach((button) => {
                if (button.dataset.boundExpand === '1') return;
                button.dataset.boundExpand = '1';
                button.addEventListener('click', () => {
                    openExpandedChart(
                        button.dataset.chartExpand,
                        button.dataset.chartTitle || 'Chart Preview'
                    );
                });
            });

            const modalEl = document.getElementById('chartFocusModal');
            if (modalEl && modalEl.dataset.cleanupBound !== '1') {
                modalEl.dataset.cleanupBound = '1';
                modalEl.addEventListener('hidden.bs.modal', () => {
                    if (chartFocusInstance) {
                        chartFocusInstance.destroy();
                        chartFocusInstance = null;
                    }

                    const wrap = document.getElementById('chartFocusCanvasWrap');
                    if (wrap) {
                        wrap.style.width = '100%';
                        wrap.style.minWidth = '100%';
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

                initializeStaticStats(__cfg_dashboard.stats);

            bindDashboardInteractions();
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
