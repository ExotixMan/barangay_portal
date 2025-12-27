<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Barangay Real-Time Forecast Dashboard</title>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Zoom & Pan Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}
canvas {
    max-width: 100%;
    margin-bottom: 40px;
}
hr {
    margin: 60px 0;
}
</style>
</head>

<body>

<h1>Barangay Real-Time Forecast Dashboard</h1>

<h2>Daily Requests Forecast</h2>
<canvas id="dailyRequestsChart"></canvas>

<h2>Daily Approvals Forecast</h2>
<canvas id="dailyApprovalsChart"></canvas>

<h2>Weekly Requests Forecast by Type</h2>
<canvas id="weeklyRequestsChart"></canvas>

<hr>

<h2>Daily Incident Forecast</h2>
<canvas id="incidentDailyChart"></canvas>

<h2>Weekly Incident Forecast by Type</h2>
<canvas id="incidentWeeklyChart"></canvas>

<hr>

<h2>Requests by Type</h2>
<canvas id="requestsTypeChart"></canvas>

<h2>Missing Documents</h2>
<canvas id="missingDocsChart"></canvas>

<h2>Age Distribution</h2>
<canvas id="ageDistributionChart"></canvas>

<hr>

<h2>Incidents by Type</h2>
<canvas id="incTypeChart"></canvas>

<h2>Incidents by Location</h2>
<canvas id="incLocationChart"></canvas>

<h2>Status Distribution</h2>
<canvas id="statusChart"></canvas>

<script>
// =====================================================
// GLOBAL STORAGE FOR CHART INSTANCES
// =====================================================
const charts = {};

const COLORS = [
    'rgba(255,99,132,0.6)',
    'rgba(54,162,235,0.6)',
    'rgba(255,206,86,0.6)',
    'rgba(75,192,192,0.6)',
    'rgba(153,102,255,0.6)',
    'rgba(255,159,64,0.6)'
];

// =====================================================
// COMMON OPTIONS (ZOOM + INTERACTION)
// =====================================================
function baseOptions(horizontal = false) {
    return {
        responsive: true,
        indexAxis: horizontal ? 'y' : 'x',
        interaction: { mode: 'index', intersect: false },
        plugins: {
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
        }
    };
}

// =====================================================
// GENERIC CHART CREATOR / UPDATER
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
// FETCH & UPDATE DATA (REAL-TIME)
// =====================================================
async function fetchAndUpdate() {
    const res = await fetch('/api/forecast-requests');
    const data = await res.json();

    // ---------------- REQUEST FORECASTS ----------------
    renderOrUpdateChart(
        'dailyRequestsChart',
        'bar',
        Object.keys(data.requests.daily_requests_forecast),
        [{
            label: 'Daily Requests',
            data: Object.values(data.requests.daily_requests_forecast),
            backgroundColor: COLORS[1]
        }],
        baseOptions()
    );

    renderOrUpdateChart(
        'dailyApprovalsChart',
        'line',
        Object.keys(data.requests.daily_approvals_forecast),
        [{
            label: 'Daily Approvals',
            data: Object.values(data.requests.daily_approvals_forecast),
            fill: true,
            tension: 0.4,
            backgroundColor: 'rgba(0,200,83,0.3)',
            borderColor: 'rgb(0,200,83)'
        }],
        baseOptions()
    );

    // Weekly Requests by Type
    const wr = data.requests.weekly_requests_forecast;
    const wrLabels = [...new Set(Object.values(wr).flatMap(o => Object.keys(o)))].sort();
    const wrDatasets = Object.keys(wr).map((k, i) => ({
        label: k,
        data: wrLabels.map(d => wr[k][d] || 0),
        backgroundColor: COLORS[i % COLORS.length]
    }));

    renderOrUpdateChart('weeklyRequestsChart', 'bar', wrLabels, wrDatasets, baseOptions());

    // ---------------- INCIDENT FORECASTS ----------------
    renderOrUpdateChart(
        'incidentDailyChart',
        'bar',
        Object.keys(data.incidents.daily_incidents_forecast_es),
        [{
            label: 'Daily Incidents',
            data: Object.values(data.incidents.daily_incidents_forecast_es),
            backgroundColor: COLORS[0]
        }],
        baseOptions()
    );

    const wi = data.incidents.weekly_incident_type_forecast;
    const wiLabels = [...new Set(Object.values(wi).flatMap(o => Object.keys(o)))].sort();
    const wiDatasets = Object.keys(wi).map((k, i) => ({
        label: k,
        data: wiLabels.map(d => wi[k][d] || 0),
        backgroundColor: COLORS[i % COLORS.length]
    }));

    renderOrUpdateChart('incidentWeeklyChart', 'bar', wiLabels, wiDatasets, baseOptions());

    // ---------------- REQUEST ANALYTICS ----------------
    renderOrUpdateChart(
        'requestsTypeChart',
        'pie',
        Object.keys(data.requests.analytics.requests_by_type),
        [{
            data: Object.values(data.requests.analytics.requests_by_type),
            backgroundColor: COLORS
        }]
    );

    renderOrUpdateChart(
        'missingDocsChart',
        'bar',
        Object.keys(data.requests.analytics.requests_missing_docs),
        [{
            label: 'Missing Documents',
            data: Object.values(data.requests.analytics.requests_missing_docs),
            backgroundColor: COLORS[5]
        }],
        baseOptions()
    );

    renderOrUpdateChart(
        'ageDistributionChart',
        'bar',
        Object.keys(data.requests.analytics.age_distribution),
        [{
            label: 'Age Count',
            data: Object.values(data.requests.analytics.age_distribution),
            backgroundColor: COLORS[1]
        }],
        baseOptions()
    );

    // ---------------- INCIDENT ANALYTICS ----------------
    renderOrUpdateChart(
        'incTypeChart',
        'pie',
        Object.keys(data.incidents.analytics.incidents_by_type),
        [{
            data: Object.values(data.incidents.analytics.incidents_by_type),
            backgroundColor: COLORS
        }]
    );

    renderOrUpdateChart(
        'incLocationChart',
        'bar',
        Object.keys(data.incidents.analytics.incidents_by_location),
        [{
            label: 'Incidents',
            data: Object.values(data.incidents.analytics.incidents_by_location),
            backgroundColor: COLORS[3]
        }],
        baseOptions(true)
    );

    renderOrUpdateChart(
        'statusChart',
        'doughnut',
        Object.keys(data.incidents.analytics.status_distribution),
        [{
            data: Object.values(data.incidents.analytics.status_distribution),
            backgroundColor: COLORS
        }]
    );
}

// INITIAL LOAD
fetchAndUpdate();

// REAL-TIME AUTO REFRESH (10 seconds)
setInterval(fetchAndUpdate, 10000);
</script>

</body>
</html>
