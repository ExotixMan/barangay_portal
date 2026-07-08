<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Barangay Dashboard - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            font-size: 70%;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
    
        /* ==========================================================
           Interactive dashboard redesign override
           Cleaner, compact, and consistent with Projects page.
        ========================================================== */
        :root {
            --primary: #d32f2f;
            --primary-dark: #a91f1f;
            --primary-soft: #fff5f5;
            --success: #16803c;
            --success-soft: #eefaf2;
            --warning: #d97706;
            --warning-soft: #fff7ed;
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --info: #0369a1;
            --info-soft: #eff6ff;
            --page-bg: #f8fafc;
            --card: #ffffff;
            --text: #111827;
            --muted: #667085;
            --muted-2: #98a2b3;
            --line: #e5e7eb;
            --line-strong: #d0d5dd;
            --shadow-xs: 0 1px 2px rgba(16, 24, 40, .05);
            --shadow-sm: 0 8px 22px rgba(16, 24, 40, .06);
            --shadow-md: 0 18px 40px rgba(16, 24, 40, .10);
            --radius-xl: 22px;
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 11px;
        }

        .min-w-0 { min-width: 0 !important; }

        .dashboard-shell {
            width: 100%;
            max-width: 1380px;
            margin: 0 auto;
        }

        .dashboard-alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .dashboard-hero,
        .dashboard-toolbar-panel,
        .dashboard-card,
        .metric-card,
        .no-permission-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xs);
        }

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: clamp(1rem, 1.8vw, 1.55rem);
            margin-bottom: 1rem;
        }

        .dashboard-hero-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 95% 0%, rgba(211,47,47,.11), transparent 32%),
                radial-gradient(circle at 10% 100%, rgba(3,105,161,.08), transparent 30%),
                linear-gradient(135deg, rgba(211,47,47,.04), transparent 42%);
        }

        .dashboard-hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: center;
            gap: 1rem;
        }

        .dashboard-hero-icon,
        .metric-icon,
        .card-head h4 i,
        .section-kicker i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.14);
            border-radius: 15px;
            font-size: 1.05rem;
        }

        .dashboard-kicker,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .dashboard-hero h2 {
            margin: .45rem 0 .4rem;
            color: var(--text);
            font-size: clamp(1.55rem, 2.4vw, 2.25rem);
            font-weight: 850;
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .dashboard-hero p,
        .section-heading p,
        .card-head p,
        .metric-note,
        .dashboard-summary-line {
            color: var(--muted);
            line-height: 1.55;
        }

        .dashboard-hero p {
            max-width: 760px;
            margin: 0;
            font-size: .93rem;
        }

        .dashboard-hero-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .dashboard-hero-meta {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: 1rem;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 700;
        }

        .dashboard-hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            padding: .42rem .68rem;
            background: rgba(255,255,255,.75);
            border: 1px solid var(--line);
            border-radius: 999px;
        }

        .dashboard-toolbar-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .85rem;
            padding: .7rem;
            margin-bottom: 1rem;
        }

        .dashboard-control-panel {
            align-items: stretch;
        }

        .dashboard-toolbar-right,
        .dashboard-actions-inline {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .45rem;
        }

        .dashboard-toolbar-right {
            margin-left: auto;
        }

        .dashboard-actions-inline {
            padding-left: .55rem;
            border-left: 1px solid var(--line);
        }

        .dashboard-actions-inline .btn {
            min-height: 34px;
            padding: .43rem .7rem;
            border-radius: 999px;
            font-size: .76rem;
            white-space: nowrap;
            box-shadow: var(--shadow-xs);
        }

        .dashboard-tabs,
        .dashboard-range {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .42rem;
        }

        .dashboard-tab,
        .range-chip,
        .mini-toggle,
        .icon-action {
            border: 1px solid var(--line);
            background: #ffffff;
            color: var(--muted);
            border-radius: 999px;
            font-weight: 800;
            transition: all .16s ease;
        }

        .dashboard-tab {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            min-height: 38px;
            padding: .55rem .78rem;
            font-size: .8rem;
        }

        .range-chip,
        .mini-toggle {
            min-height: 34px;
            padding: .43rem .7rem;
            font-size: .76rem;
        }

        .dashboard-tab:hover,
        .range-chip:hover,
        .mini-toggle:hover,
        .icon-action:hover {
            color: var(--primary-dark);
            background: #fffafa;
            border-color: rgba(211,47,47,.22);
        }

        .dashboard-tab.active,
        .range-chip.active,
        .mini-toggle.active {
            color: #ffffff;
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(211,47,47,.16);
        }

        .dashboard-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-bottom: 1rem;
        }

        .metric-card {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: 1rem;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .metric-card:hover,
        .dashboard-card:hover,
        .dashboard-toolbar-panel:hover {
            transform: translateY(-1px);
            border-color: var(--line-strong);
            box-shadow: var(--shadow-sm);
        }

        .metric-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
        }

        .metric-residents .metric-icon { color: var(--info); background: var(--info-soft); border-color: #dbeafe; }
        .metric-pending .metric-icon { color: var(--warning); background: var(--warning-soft); border-color: #fed7aa; }
        .metric-reports .metric-icon { color: var(--danger); background: var(--danger-soft); border-color: rgba(220,38,38,.14); }
        .metric-events .metric-icon { color: var(--success); background: var(--success-soft); border-color: rgba(22,128,60,.14); }

        .metric-body {
            min-width: 0;
            display: grid;
            gap: .2rem;
        }

        .metric-label {
            color: var(--muted);
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .metric-value {
            color: var(--text);
            font-size: clamp(1.5rem, 2.2vw, 2.05rem);
            font-weight: 850;
            line-height: 1;
            letter-spacing: -.055em;
        }

        .metric-note { font-size: .78rem; font-weight: 650; }

        .dashboard-section { margin-top: 1rem; }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: .8rem;
            margin: .3rem 0 .8rem;
        }

        .section-heading h3 {
            margin: .25rem 0 0;
            color: var(--text);
            font-size: 1.15rem;
            font-weight: 850;
            letter-spacing: -.035em;
        }

        .dashboard-chart-grid {
            display: grid;
            gap: .9rem;
        }

        .overview-grid { grid-template-columns: 1.35fr .85fr; }
        .two-col { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .three-col { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .dashboard-app-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .9rem; }

        .dashboard-card {
            padding: 1rem;
            overflow: hidden;
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .85rem;
            margin-bottom: .75rem;
        }

        .card-head h4 {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin: 0 0 .25rem;
            color: var(--text);
            font-size: .98rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .card-head h4 i {
            width: 36px;
            height: 36px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 12px;
            font-size: .88rem;
        }

        .card-head p {
            margin: 0;
            font-size: .8rem;
            font-weight: 600;
        }

        .card-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .35rem;
        }

        .icon-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 12px;
        }

        .chart-container {
            position: relative;
            height: 285px;
            width: 100%;
            margin-top: .35rem;
        }

        .chart-container-tall { height: 320px; }
        .small-chart { height: 230px; }

        .chart-empty-overlay {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            text-align: center;
            pointer-events: none;
        }

        .chart-empty-overlay.show {
            display: flex;
        }

        .chart-empty-content {
            max-width: 310px;
            padding: .85rem 1rem;
            background: rgba(255,255,255,.86);
            border: 1px dashed var(--line-strong);
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
            backdrop-filter: blur(7px);
        }

        .chart-empty-content strong {
            display: block;
            color: var(--text);
            font-size: .9rem;
            font-weight: 850;
        }

        .chart-empty-content span {
            display: block;
            margin-top: .25rem;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 650;
        }

        .summary-badge {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            width: fit-content;
            padding: .42rem .68rem;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 850;
            white-space: nowrap;
        }

        .dashboard-summary-line {
            margin-top: .75rem;
            padding-top: .75rem;
            border-top: 1px dashed var(--line);
            font-size: .8rem;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .48rem;
            min-height: 42px;
            padding: .65rem .95rem;
            border-radius: 12px;
            font-weight: 800;
            letter-spacing: -.01em;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease, border-color .16s ease, color .16s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary,
        .refresh-btn {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211,47,47,.18);
        }

        .btn-primary:hover,
        .refresh-btn:hover {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }

        .btn-light-soft,
        .auto-toggle-btn {
            color: var(--text) !important;
            background: #ffffff !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .auto-toggle-btn:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211,47,47,.22) !important;
        }

        .auto-toggle-btn.auto-on,
        .btn.auto-on {
            color: var(--success) !important;
            background: var(--success-soft) !important;
            border-color: rgba(22,128,60,.24) !important;
        }

        .btn.auto-off {
            color: var(--warning) !important;
            background: var(--warning-soft) !important;
            border-color: #fed7aa !important;
        }

        .chart-focus-modal { border-radius: var(--radius-xl); overflow: hidden; }
        .chart-focus-image { max-height: 72vh; object-fit: contain; border-radius: 14px; }

        .no-permission-card {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--muted);
        }

        .no-permission-card i {
            width: 64px;
            height: 64px;
            display: inline-grid;
            place-items: center;
            margin-bottom: 1rem;
            color: var(--primary);
            background: var(--primary-soft);
            border-radius: 20px;
            font-size: 1.35rem;
        }

        @media (max-width: 1199px) {
            .dashboard-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .overview-grid,
            .two-col,
            .three-col,
            .dashboard-app-grid { grid-template-columns: minmax(0, 1fr); }
        }

        @media (max-width: 991px) {
            .dashboard-hero-content { grid-template-columns: minmax(0, 1fr); }
            .dashboard-toolbar-panel { align-items: stretch; flex-direction: column; }
            .dashboard-toolbar-right { width: 100%; margin-left: 0; justify-content: space-between; }
            .dashboard-tabs, .dashboard-range, .dashboard-actions-inline { width: 100%; }
            .dashboard-actions-inline { padding-left: 0; padding-top: .55rem; border-left: 0; border-top: 1px solid var(--line); }
            .dashboard-tab { flex: 1 1 160px; justify-content: center; }
            .range-chip { flex: 1 1 70px; }
            .dashboard-actions-inline .btn { flex: 1 1 160px; }
        }

        @media (max-width: 768px) {
            html { font-size: 78%; }
            .dashboard-shell { max-width: 100%; }
            .dashboard-hero,
            .dashboard-toolbar-panel,
            .dashboard-card,
            .metric-card { border-radius: 16px; }
            .dashboard-kpi-grid { grid-template-columns: minmax(0, 1fr); }
            .dashboard-actions-inline .btn { width: 100%; }
            .section-heading { align-items: flex-start; flex-direction: column; }
            .chart-container { height: 245px; }
            .chart-container-tall { height: 265px; }
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
        <a href="{{ route('admin.dashboard.index') }}" class="active" onclick="handleLinkClick(event, this)">
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
        @endadmin_can

        @admin_can('view_settings')
        <a href="{{ route('admin.settings.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-cog"></i>
            <span>System Settings</span>
        </a>
        @endadmin_can

        <!-- Sidebar Account -->
        <div class="sidebar-account">
            <div class="sidebar-user-card">
                <div class="sidebar-user-icon">
                    <i class="fas fa-user-circle"></i>
                </div>

                <div class="sidebar-user-details">
                    <div class="sidebar-user-name">
                        {{ Auth::guard('admin')->user()->full_name }}
                        <div class="sidebar-user-role">
                            {{ Auth::guard('admin')->user()->getRoleDisplayName() }}
                        </div>
                    </div>

                    <div class="sidebar-user-email">
                        {{ Auth::guard('admin')->user()->email }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}" class="sidebar-logout-form">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-logout-text">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-chart-line"></i></span>
                    Barangay Dashboard
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
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
                            <div class="dashboard-kicker"><i class="fas fa-circle-check"></i> Barangay command dashboard</div>
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
                            <button type="button" class="btn btn-light-soft auto-off" id="autoRefreshToggleBtn" onclick="toggleAutoRefresh()">
                                <i class="fas fa-signal"></i>
                                <span id="autoRefreshToggleText">Auto Refresh: OFF</span>
                            </button>
                        </div>
                    </div>
                </section>

                {{-- Stats Cards --}}
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
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content chart-focus-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="chartFocusTitle"><i class="fas fa-chart-line me-2"></i>Chart Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="chartFocusImage" alt="Expanded chart preview" class="img-fluid chart-focus-image">
                            <div id="chartFocusFallback" class="text-muted py-5 d-none">Chart preview is not available yet.</div>
                        </div>
                    </div>
                </div>
            </div>
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
                const response = await fetch("{{ route('admin.dashboard.forecast') }}?v=" + Date.now(), {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.error) {
                    console.error("Forecast error:", data.error);
                    setChartLoadingState(false, 'Forecast unavailable');
                    return;
                }

                forecastData = data;

                // Feed the added dashboard charts with analytics data from forecast.json.
                // This keeps Service Pipeline Snapshot and Data Readiness covered even before live_stats responds.
                try { initializeStaticStats(getOverviewStatsFromForecast(data)); } catch (e) { console.error('Overview chart data error:', e); }
                
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
                    `<i class="fas fa-info-circle me-1"></i> ${getRangeLabel()} expected total: ${total.toFixed(1)} announcements over 30 days`;
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


        // ===== Interactive dashboard UX overrides =====
        window.dashboardRangeDays = 7;

        function numberOrZero(value) {
            const num = Number(value);
            return Number.isFinite(num) ? num : 0;
        }

        function getRangeLabel(days = window.dashboardRangeDays || 7) {
            return `${Number(days) || 7} days`;
        }

        function getForecastRangeSummary(source = {}, days = window.dashboardRangeDays || 7) {
            const key = `${Number(days) || 7}_days`;
            return source?.forecast_ranges?.[key] || source?.[key] || null;
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

        function initializeStaticStats(stats) {
            const safeStats = stats || {};
            const totalResidentsEl = document.getElementById('totalResidents');
            if (totalResidentsEl) totalResidentsEl.innerText = numberOrZero(safeStats.totalResidents).toLocaleString();

            const pendingEl = document.getElementById('pendingRequests');
            if (pendingEl) pendingEl.innerText = numberOrZero(safeStats.pendingRequests).toLocaleString();

            const openReportsEl = document.getElementById('openReports');
            if (openReportsEl) openReportsEl.innerText = numberOrZero(safeStats.openReports).toLocaleString();

            const upcomingEventsEl = document.getElementById('upcomingEvents');
            if (upcomingEventsEl) upcomingEventsEl.innerText = numberOrZero(safeStats.upcomingEvents).toLocaleString();

            renderServicePipelineChart(safeStats);
            renderReadinessChart(safeStats);
        }

        function limitedForecastEntries(map, fallbackDays = window.dashboardRangeDays || 7) {
            const days = Math.min(Math.max(Number(fallbackDays) || 7, 7), 90);
            const entries = sortForecastEntries(map || {}).slice(0, days);
            return entries.map((item) => ({
                label: shortDate(item.date),
                value: Math.round(numberOrZero(item.value) * 10) / 10
            }));
        }

        function buildDailyRequestsChart(data) {
            const applications = data?.applications || {};
            const dashboardCombined = data?.dashboard_overview?.combined_application_forecast || null;
            const mergedByDate = {};

            if (dashboardCombined && Object.keys(dashboardCombined).length) {
                Object.assign(mergedByDate, dashboardCombined);
            } else {
                const dailyMaps = [
                    applications.barangay_clearances?.daily_forecast || {},
                    applications.indigency_applications?.daily_forecast || {},
                    applications.residency_applications?.daily_forecast || {}
                ];
                dailyMaps.forEach((map) => {
                    Object.entries(map).forEach(([date, value]) => {
                        mergedByDate[date] = (mergedByDate[date] || 0) + numberOrZero(value);
                    });
                });
            }

            let entries = limitedForecastEntries(mergedByDate, window.dashboardRangeDays || 7);
            const isEmpty = !entries.length || !hasMeaningfulData(entries.map((item) => item.value));
            if (!entries.length) {
                entries = nextDateLabels(window.dashboardRangeDays || 7).map((label) => ({ label, value: 0 }));
            }

            setChartEmptyState('dailyRequestsChart', isEmpty, 'No request trend yet', 'Submit service requests to activate daily trend insights.');
            const canvas = document.getElementById('dailyRequestsChart');
            const currentType = canvas?.dataset.chartTypeCurrent || 'bar';
            renderChart('dailyRequestsChart', {
                type: currentType,
                data: {
                    labels: entries.map((item) => item.label),
                    datasets: [{
                        label: 'Requests',
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
                let entries = limitedForecastEntries(value?.daily_forecast || {}, window.dashboardRangeDays || 7);
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
                    <div class="dashboard-summary-line"><i class="fas fa-database me-1"></i>${numberOrZero(value?.historical_count)} historical records</div>
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
            let entries = limitedForecastEntries(blotter?.daily_forecast || {}, window.dashboardRangeDays || 7);
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
            let entries = limitedForecastEntries(announcements?.daily_forecast || {}, window.dashboardRangeDays || 7);
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
                    if (forecastData) {
                        initializeStaticStats(getOverviewStatsFromForecast(forecastData));
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

        function bindChartExpandButtons() {
            document.querySelectorAll('[data-chart-expand]').forEach((button) => {
                if (button.dataset.boundExpand === '1') return;
                button.dataset.boundExpand = '1';
                button.addEventListener('click', () => {
                    const chartId = button.dataset.chartExpand;
                    const canvas = document.getElementById(chartId);
                    const modalEl = document.getElementById('chartFocusModal');
                    const titleEl = document.getElementById('chartFocusTitle');
                    const imageEl = document.getElementById('chartFocusImage');
                    const fallbackEl = document.getElementById('chartFocusFallback');
                    if (titleEl) titleEl.innerHTML = `<i class="fas fa-chart-line me-2"></i>${button.dataset.chartTitle || 'Chart Preview'}`;
                    try {
                        if (!canvas) throw new Error('Missing canvas');
                        const url = canvas.toDataURL('image/png', 1.0);
                        if (imageEl) { imageEl.src = url; imageEl.classList.remove('d-none'); }
                        if (fallbackEl) fallbackEl.classList.add('d-none');
                    } catch (e) {
                        if (imageEl) imageEl.classList.add('d-none');
                        if (fallbackEl) fallbackEl.classList.remove('d-none');
                    }
                    if (modalEl && window.bootstrap) {
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    }
                });
            });
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
    </script>
</body>
</html>

