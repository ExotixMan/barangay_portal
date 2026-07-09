<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Residents - Admin</title>
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">

    <style>
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

        * { box-sizing: border-box; }

        html {
            font-size: 70%;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--page-bg);
            color: var(--text);
            overflow-x: hidden;
        }

        .min-w-0 { min-width: 0 !important; }

        .residency-hero-icon,
        .stat-icon,
        .table-head-icon,
        .empty-state-icon,
        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            color: var(--text);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow-xs);
        }

        .mobile-menu-btn:hover {
            color: var(--primary-dark);
            border-color: rgba(211, 47, 47, .22);
            background: #fffafa;
        }

        .residency-page {
            width: 100%;
            max-width: 1380px;
            margin: 0 auto;
        }

        .residency-alert,
        .alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            color: var(--success);
            background: var(--success-soft);
            border: 1px solid rgba(22, 128, 60, .14);
        }

        .alert-danger {
            color: var(--danger);
            background: var(--danger-soft);
            border: 1px solid rgba(220, 38, 38, .16);
        }

        .alert-danger ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .alert-danger li {
            position: relative;
            padding: .25rem 0 .25rem 1.15rem;
            color: #991b1b;
            font-weight: 650;
        }

        .alert-danger li::before {
            content: '';
            position: absolute;
            left: 0;
            top: .78rem;
            width: 6px;
            height: 6px;
            background: var(--danger);
            border-radius: 999px;
        }

        /* HERO */
        .residency-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
            padding: clamp(1rem, 1.8vw, 1.55rem);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xs);
        }

        .residency-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 95% 0%, rgba(211,47,47,.11), transparent 32%),
                radial-gradient(circle at 10% 100%, rgba(3,105,161,.08), transparent 30%),
                linear-gradient(135deg, rgba(211,47,47,.04), transparent 42%);
            pointer-events: none;
        }

        .residency-hero-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 1rem;
        }

        .residency-hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.14);
            border-radius: 15px;
            font-size: 1.05rem;
        }

        .residency-kicker,
        .table-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .residency-hero h2 {
            margin: .45rem 0 .4rem;
            color: var(--text);
            font-size: clamp(1.55rem, 2.4vw, 2.25rem);
            font-weight: 850;
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .residency-hero p {
            max-width: 760px;
            margin: 0;
            color: var(--muted);
            font-size: .93rem;
            line-height: 1.6;
        }

        .residency-hero-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .6rem;
        }

        /* KPI CARDS */
        .residency-stats,
        .residency-page > .row.g-3.g-lg-4.mb-4 {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem !important;
            margin-bottom: 1rem !important;
        }

        .residency-page > .row.g-3.g-lg-4.mb-4 > [class*="col-"] {
            width: auto !important;
            max-width: none !important;
            flex: unset !important;
            padding: 0 !important;
        }

        .stat-card {
            display: flex !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            gap: .85rem;
            height: 100%;
            padding: 1rem;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xs);
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .stat-card:hover {
            transform: translateY(-1px);
            border-color: var(--line-strong);
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            color: var(--muted) !important;
            font-size: .73rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .stat-number {
            margin-top: .18rem;
            color: var(--text);
            font-size: clamp(1.5rem, 2.2vw, 2.05rem);
            font-weight: 850;
            line-height: 1;
            letter-spacing: -.055em;
        }

        .stat-card small {
            display: inline-flex;
            align-items: center;
            gap: .28rem;
            margin-top: .7rem !important;
            color: var(--muted) !important;
            font-size: .78rem;
            font-weight: 650;
            line-height: 1.45;
        }

        .stat-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
            font-size: 1rem;
        }

        .residency-page > .row.g-3.g-lg-4.mb-4 > div:nth-child(2) .stat-icon {
            color: var(--success);
            background: var(--success-soft);
            border-color: rgba(22,128,60,.14);
        }

        .residency-page > .row.g-3.g-lg-4.mb-4 > div:nth-child(3) .stat-icon {
            color: var(--info);
            background: var(--info-soft);
            border-color: #dbeafe;
        }

        .residency-page > .row.g-3.g-lg-4.mb-4 > div:nth-child(4) .stat-icon {
            color: var(--warning);
            background: var(--warning-soft);
            border-color: #fed7aa;
        }

        /* PANELS */
        .card,
        .residency-filter-card,
        .residency-table-card {
            overflow: hidden;
            background: var(--card);
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
            transition: box-shadow .16s ease, border-color .16s ease, transform .16s ease;
        }

        .card:hover,
        .residency-filter-card:hover,
        .residency-table-card:hover {
            border-color: var(--line-strong) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .residency-filter-card .card-body {
            padding: 1rem;
        }

        .panel-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            margin-bottom: .9rem;
        }

        .table-head-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .table-head-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .table-head-title h3,
        .residency-filter-card h6 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .table-head-title p {
            margin: 0;
            color: var(--muted);
            font-size: .86rem;
            line-height: 1.55;
        }

        .residency-filter-card .row.g-3.align-items-center.mb-3 {
            margin-bottom: .9rem !important;
            padding-bottom: .9rem;
            border-bottom: 1px solid var(--line);
        }

        .residency-filter-card .row.g-3.align-items-center.mb-3 h6 {
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .residency-filter-card .row.g-3.align-items-center.mb-3 h6 i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            margin: 0 !important;
            color: var(--primary) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 12px;
        }

        .form-control,
        .form-select {
            min-height: 44px;
            color: var(--text);
            background-color: #ffffff;
            border: 1px solid var(--line-strong);
            border-radius: 12px;
            font-size: .9rem;
            font-weight: 650;
            transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
        }

        .form-control::placeholder { color: var(--muted-2); }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(211, 47, 47, .36);
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none;
        }

        .input-group {
            border-radius: 12px;
        }

        .input-group-text {
            min-height: 44px;
            color: var(--muted-2);
            background: #ffffff !important;
            border: 1px solid var(--line-strong);
            border-right: 0;
            border-radius: 12px 0 0 12px !important;
        }

        .input-group .form-control {
            border-left: 0 !important;
            border-radius: 0 !important;
        }

        .input-group .btn {
            border-radius: 0 12px 12px 0 !important;
        }

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
        }

        textarea.form-control {
            min-height: 120px;
            line-height: 1.65;
            resize: vertical;
        }

        .is-invalid {
            border-color: var(--danger) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            border-color: var(--danger) !important;
            outline-color: rgba(220, 38, 38, .16);
            box-shadow: none !important;
        }

        .invalid-feedback {
            display: block;
            margin-top: .35rem;
            color: var(--danger);
            font-size: .76rem;
            font-weight: 760;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* BUTTONS */
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

        .btn:focus-visible {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none;
        }

        .btn-primary,
        .btn-danger {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 10px 22px rgba(211,47,47,.18);
        }

        .btn-primary:hover,
        .btn-danger:hover {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }

        .btn-success {
            color: #ffffff !important;
            background: var(--success) !important;
            border-color: var(--success) !important;
            box-shadow: 0 10px 22px rgba(22,128,60,.16);
        }

        .btn-light-soft,
        .btn-secondary,
        .btn-outline-secondary {
            color: var(--text) !important;
            background: #ffffff !important;
            border: 1px solid var(--line-strong) !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .btn-secondary:hover,
        .btn-outline-secondary:hover {
            color: var(--primary-dark) !important;
            background: #fffafa !important;
            border-color: rgba(211,47,47,.22) !important;
        }

        .btn-outline-primary {
            color: var(--primary) !important;
            background: var(--card) !important;
            border-color: rgba(211, 47, 47, .25) !important;
        }

        .btn-outline-primary:hover {
            color: #ffffff !important;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .btn-outline-info {
            color: var(--info) !important;
            background: var(--info-soft) !important;
            border-color: #dbeafe !important;
        }

        .btn-outline-info:hover {
            color: #ffffff !important;
            background: var(--info) !important;
            border-color: var(--info) !important;
        }

        .btn-outline-success {
            color: var(--success) !important;
            background: var(--success-soft) !important;
            border-color: rgba(22, 128, 60, .18) !important;
        }

        .btn-outline-success:hover {
            color: #ffffff !important;
            background: var(--success) !important;
            border-color: var(--success) !important;
        }

        .btn-outline-danger {
            color: var(--danger) !important;
            background: var(--danger-soft) !important;
            border-color: rgba(220, 38, 38, .18) !important;
        }

        .btn-outline-danger:hover {
            color: #ffffff !important;
            background: var(--danger) !important;
            border-color: var(--danger) !important;
        }

        .btn-sm,
        .btn-group-sm > .btn {
            min-height: 36px;
            padding: .5rem .68rem;
            border-radius: 11px;
            font-size: .82rem;
        }

        /* TABLE */
        .residency-table-card .card-body { background: #ffffff; }

        .residency-table-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, #ffffff, #fbfdff);
        }

        .residency-table-head h3 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .residency-table-head p {
            margin: 0;
            color: var(--muted);
            font-size: .86rem;
            line-height: 1.55;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 920px;
            margin-bottom: 0;
        }

        .table thead th {
            padding: .85rem .95rem;
            color: #475467;
            background: #f9fafb;
            border-bottom: 1px solid var(--line);
            font-size: .72rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .07em;
            white-space: nowrap;
        }

        .table thead th a {
            color: #475467 !important;
            font-weight: 850;
        }

        .table tbody td {
            vertical-align: middle;
            padding: .95rem;
            color: var(--text);
            border-bottom: 1px solid var(--line);
            font-size: .86rem;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: background .16s ease;
        }

        .table tbody tr:hover { background: #fcfcfd; }

        .table-danger,
        .table-danger > * {
            --bs-table-bg: #fff7f7;
            --bs-table-striped-bg: #fff7f7;
            --bs-table-hover-bg: #fff1f2;
        }

        .table .rounded-circle,
        .resident-avatar {
            border: 1px solid var(--line) !important;
            box-shadow: var(--shadow-xs);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            border-radius: 999px;
            white-space: nowrap;
            font-weight: 820;
            padding: .4rem .62rem;
            font-size: .74rem;
        }

        .badge.bg-success-subtle {
            color: var(--success) !important;
            background: var(--success-soft) !important;
            border: 1px solid rgba(22, 128, 60, .14);
        }

        .badge.bg-danger-subtle {
            color: var(--danger) !important;
            background: var(--danger-soft) !important;
            border: 1px solid rgba(220, 38, 38, .14);
        }

        .pagination {
            gap: .35rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            min-width: 36px;
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px !important;
            border: 1px solid var(--line);
            color: var(--primary);
            font-size: .82rem;
            font-weight: 800;
        }

        .page-link:hover {
            color: var(--primary-dark);
            background: var(--primary-soft);
            border-color: rgba(211,47,47,.22);
        }

        .page-item.active .page-link {
            color: #ffffff;
            background: var(--primary);
            border-color: var(--primary);
        }

        /* EMPTY */
        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 20px;
            font-size: 1.35rem;
        }

        /* MODALS */
        .modal-backdrop.show { opacity: .38; }

        .modal-dialog {
            padding: .75rem;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: var(--radius-xl);
            box-shadow: 0 24px 70px rgba(16, 24, 40, .18);
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(180deg, #ffffff, #fbfdff);
            border-bottom: 1px solid var(--line);
            border-radius: 0;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: .62rem;
            margin: .08rem 0 .15rem;
            color: var(--text);
            font-size: 1.08rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .modal-title i {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211,47,47,.12);
            border-radius: 14px;
            font-size: .95rem;
        }

        .modal-header .btn-close {
            width: 38px;
            height: 38px;
            padding: 0;
            margin: 0;
            background-color: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow-xs);
            opacity: 1;
            filter: none;
        }

        .modal-body {
            padding: 1rem;
            background: #ffffff;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: .55rem;
            padding: 1rem;
            background: #ffffff;
            border-top: 1px solid var(--line);
        }

        .modal-body h6.fw-semibold {
            display: flex;
            align-items: center;
            gap: .48rem;
            margin: .25rem 0 .85rem;
            padding: .75rem .85rem !important;
            color: var(--primary-dark) !important;
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12) !important;
            border-radius: 14px;
            font-size: .88rem;
            font-weight: 850;
        }

        .modal-body label.form-label.text-muted {
            display: block;
            color: var(--muted) !important;
            font-size: .72rem !important;
            font-weight: 850 !important;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .modal-body label.form-label.text-muted + p,
        .modal-body .col-md-6 > p,
        .modal-body .col-12 > p {
            margin: .15rem 0 .6rem;
            color: var(--text);
            font-weight: 720;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .activity-item {
            position: relative;
            margin-bottom: .75rem;
            padding: .85rem .9rem .85rem 1rem;
            background: #fbfdff;
            border: 1px solid var(--line);
            border-left: 4px solid var(--primary);
            border-radius: 14px;
        }

        .activity-time {
            color: var(--muted);
            font-size: .78rem;
            font-weight: 650;
        }

        .img-thumbnail {
            border: 1px solid var(--line);
            border-radius: 16px !important;
            box-shadow: var(--shadow-xs);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--line);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        @media (max-width: 1199px) {
            .residency-stats,
            .residency-page > .row.g-3.g-lg-4.mb-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .residency-hero-content {
                grid-template-columns: minmax(0, 1fr);
            }

            .residency-hero-actions {
                justify-content: flex-start;
            }

            .panel-heading,
            .residency-table-head {
                flex-direction: column;
                align-items: stretch;
            }

            .residency-filter-card .row.g-3.align-items-center.mb-3 > div:last-child,
            .residency-filter-card .row.g-3.align-items-center.mb-3 > div:last-child .d-flex,
            .residency-filter-card .row.g-3.align-items-center.mb-3 > div:last-child .btn,
            .residency-filter-card form[action*="residents"] + div,
            .residency-filter-card form[action*="residents"] + div form,
            .residency-filter-card form[action*="residents"] + div .btn {
                width: 100%;
            }

            .table { min-width: 860px; }
        }

        @media (max-width: 768px) {
            html { font-size: 78%; }

            .mobile-menu-btn { display: inline-flex; }

            .header {
                position: sticky;
                top: 0;
                z-index: 20;
                padding: .85rem 1rem !important;
            }

            .page-title {
                min-width: 0;
                font-size: 1.02rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .title-icon,
            .mobile-menu-btn {
                width: 38px;
                height: 38px;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .residency-page { max-width: 100%; }

            .residency-hero,
            .stat-card,
            .card,
            .residency-filter-card,
            .residency-table-card {
                border-radius: 16px !important;
            }

            .residency-stats,
            .residency-page > .row.g-3.g-lg-4.mb-4 {
                grid-template-columns: minmax(0, 1fr);
            }

            .stat-card { min-height: auto; }

            .residency-hero-actions,
            .residency-hero-actions .btn,
            .residency-filter-card .btn,
            .modal-footer .btn {
                width: 100%;
            }

            .residency-filter-card .row.g-3.align-items-center.mb-3 .d-flex,
            .residency-filter-card form[action*="residents"] + div {
                flex-direction: column;
                align-items: stretch;
            }

            .modal-dialog {
                margin: .45rem;
                padding: 0;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: .9rem;
            }

            .modal-footer {
                flex-direction: column-reverse;
            }

            .modal-title {
                font-size: .98rem;
                line-height: 1.25;
            }

            .modal-title i {
                width: 38px;
                height: 38px;
            }

            input[type="checkbox"] {
                width: 22px;
                height: 22px;
            }

            .table td .btn-sm {
                min-height: 36px;
                min-width: 36px;
                padding: .5rem;
            }
        }

        @media (max-width: 575px) {
            .residency-hero h2 { font-size: 1.55rem; }
            .residency-hero-icon { width: 40px; height: 40px; }
            .input-group { display: grid; grid-template-columns: 44px 1fr; }
            .input-group .btn { grid-column: 1 / -1; border-radius: 12px !important; margin-top: .55rem; }
            .input-group .form-control { border-radius: 0 12px 12px 0 !important; }
        }
    

        /* ==========================================================
           RESIDENTS PAGE - RESIDENCY APPLICATION DESIGN PATCH
           Keeps residents data/actions but matches residency UI + modals.
        ========================================================== */
        .residency-page { width: 100%; max-width: 1380px; margin: 0 auto; }

        .residency-alert,
        .alert {
            border: 0 !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .residency-hero,
        .residency-filter-card,
        .residency-table-card,
        .modal-content {
            background: var(--card) !important;
            border: 1px solid var(--line) !important;
            border-radius: var(--radius-xl) !important;
            box-shadow: var(--shadow-xs) !important;
        }

        .residency-hero { position: relative; overflow: hidden; padding: clamp(1rem, 1.8vw, 1.6rem); margin-bottom: 1rem; }
        .residency-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 96% 0%, rgba(211,47,47,.11), transparent 31%),
                radial-gradient(circle at 10% 100%, rgba(3,105,161,.08), transparent 30%),
                linear-gradient(135deg, rgba(211,47,47,.04), transparent 42%);
            pointer-events: none;
        }
        .residency-hero-content { position: relative; z-index: 1; display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 1rem; align-items: center; }
        .residency-hero-icon,
        .table-head-icon,
        .modal-heading-icon,
        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .residency-hero-icon { width: 46px; height: 46px; margin-bottom: .75rem; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.14); border-radius: 15px; font-size: 1.05rem; }
        .residency-kicker,
        .table-kicker,
        .modal-section-title { display: inline-flex; align-items: center; gap: .45rem; color: var(--primary-dark); font-size: .73rem; font-weight: 850; text-transform: uppercase; letter-spacing: .09em; }
        .residency-hero h2 { margin: .45rem 0 .4rem; color: var(--text); font-size: clamp(1.55rem, 2.4vw, 2.25rem); font-weight: 850; line-height: 1.08; letter-spacing: -.055em; }
        .residency-hero p { max-width: 790px; margin: 0; color: var(--muted); font-size: .93rem; line-height: 1.6; }
        .residency-hero-actions { display: flex; justify-content: flex-end; flex-wrap: wrap; gap: .55rem; }
        .residency-hero-meta { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: .65rem; margin-top: 1rem; color: var(--muted); font-size: .8rem; font-weight: 700; }
        .residency-hero-meta span { display: inline-flex; align-items: center; gap: .42rem; padding: .42rem .68rem; background: rgba(255,255,255,.75); border: 1px solid var(--line); border-radius: 999px; }

        .residency-stats,
        .residency-page > .row.g-3.g-lg-4.mb-4 { margin-bottom: 1rem !important; }
        .stat-card { border: 1px solid var(--line) !important; border-radius: var(--radius-xl) !important; box-shadow: var(--shadow-xs) !important; }
        .stat-card::before { display: none !important; }
        .stat-card:hover,
        .residency-filter-card:hover,
        .residency-table-card:hover { transform: translateY(-1px) !important; border-color: var(--line-strong) !important; box-shadow: var(--shadow-sm) !important; }

        .residency-filter-card { overflow: hidden; margin-bottom: 1rem !important; }
        .residency-filter-card .card-body { padding: 1rem !important; }

        .residency-table-card { overflow: hidden; }
        .residency-table-head { display: flex; align-items: flex-start; justify-content: space-between; gap: .9rem; padding: 1rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #ffffff, #fbfdff); }
        .table-head-title { display: flex; align-items: flex-start; gap: .8rem; min-width: 0; }
        .table-head-icon { flex: 0 0 auto; width: 44px; height: 44px; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 14px; }
        .residency-table-head h3 { margin: .15rem 0 .12rem; color: var(--text); font-size: 1.02rem; font-weight: 850; letter-spacing: -.025em; }
        .residency-table-head p { margin: 0; color: var(--muted); font-size: .84rem; font-weight: 600; line-height: 1.5; }
        #residentsTable { min-width: 820px !important; }
        .residency-table-card .table thead th { padding: .85rem .95rem !important; color: #475467 !important; background: #f9fafb !important; border-bottom: 1px solid var(--line) !important; font-size: .72rem !important; font-weight: 850 !important; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
        .residency-table-card .table tbody td { padding: .95rem !important; color: var(--text) !important; border-bottom: 1px solid var(--line) !important; vertical-align: middle; }
        .application-primary { display: flex; align-items: center; gap: .75rem; min-width: 230px; }
        .application-avatar { flex: 0 0 auto; width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; color: var(--primary-dark); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.14); border-radius: 14px; font-weight: 850; overflow: hidden; }
        .application-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .application-name { color: var(--text); font-weight: 850; line-height: 1.25; }
        .application-ref,
        .application-muted-line { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; margin-top: .18rem; color: var(--muted); font-size: .76rem; font-weight: 650; line-height: 1.35; }
        .ref-pill { display: inline-flex; align-items: center; gap: .32rem; width: fit-content; max-width: 100%; padding: .28rem .5rem; color: #344054; background: #f9fafb; border: 1px solid var(--line); border-radius: 999px; font-size: .72rem; font-weight: 850; white-space: nowrap; }
        .info-stack { display: grid; gap: .25rem; min-width: 150px; }
        .info-label-mini { color: var(--muted-2); font-size: .68rem; font-weight: 850; text-transform: uppercase; letter-spacing: .08em; }
        .info-main { color: var(--text); font-size: .84rem; font-weight: 820; line-height: 1.35; overflow-wrap: anywhere; }
        .info-sub { color: var(--muted); font-size: .76rem; font-weight: 650; line-height: 1.35; overflow-wrap: anywhere; }
        .status-cell { display: grid; gap: .35rem; justify-items: flex-start; }
        .empty-state-box { padding: 2.6rem 1rem; text-align: center; }
        .empty-state-icon { width: 64px; height: 64px; margin: 0 auto 1rem; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 20px; font-size: 1.35rem; }

        .action-buttons { display: flex; gap: .35rem; justify-content: flex-end; align-items: center; }
        .action-btn { min-height: 34px; border-radius: 10px; border: 1px solid #4a4f57; background: #2f343b; color: #f1f3f5; padding: 0 .55rem; display: inline-flex; align-items: center; justify-content: center; gap: .35rem; }
        .action-btn.icon-only { width: 34px; padding: 0; gap: 0; }
        .action-label { font-size: .73rem; font-weight: 700; line-height: 1; }
        .action-btn.action-view { background: #2a2f36; border-color: #4a4f57; color: #ffffff; }
        .action-btn.action-update { background: #d5f0ad; border-color: #a9d772; color: #2f4a11; }
        .action-btn.action-docs { background: #d7ecff; border-color: #95c5f2; color: #0a4d86; }
        .action-btn.action-archive { background: #ffdfe2; border-color: #f1a8af; color: #9f1f2e; }
        .action-divider { color: #9aa0a6; font-size: .9rem; line-height: 1; user-select: none; }

        .modal-backdrop.show { opacity: .38; }
        .modal-dialog { padding: .75rem; }
        .modal-content { overflow: hidden; border: 0 !important; border-radius: var(--radius-xl) !important; box-shadow: 0 24px 70px rgba(16,24,40,.18) !important; }
        .modal-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1rem !important; color: var(--text) !important; background: radial-gradient(circle at top right, rgba(211,47,47,.08), transparent 34%), linear-gradient(180deg, #ffffff, #fbfdff) !important; border-bottom: 1px solid var(--line) !important; border-radius: 0 !important; }
        .modal-title { display: flex; align-items: center; gap: .62rem; margin: .08rem 0 .15rem; color: var(--text) !important; font-size: 1.08rem; font-weight: 850; letter-spacing: -.025em; }
        .modal-title i { width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; color: var(--primary) !important; background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 14px; font-size: .95rem; }
        .modal-header .btn-close { width: 38px; height: 38px; padding: 0; margin: 0; background-color: #ffffff; border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--shadow-xs); opacity: 1; filter: none !important; }
        .modal-body { padding: 1rem !important; background: #ffffff; }
        .modal-footer { display: flex; justify-content: flex-end; gap: .55rem; padding: 1rem !important; background: #ffffff; border-top: 1px solid var(--line) !important; }

        .application-modal-summary { display: grid; grid-template-columns: 1.35fr repeat(3, minmax(0,.75fr)); gap: .75rem; margin-bottom: 1rem; }
        .modal-summary-card,
        .detail-section-card,
        .document-preview-card,
        .status-flow-card { background: #ffffff; border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow-xs); }
        .modal-summary-card { display: grid; align-content: start; gap: .25rem; padding: .85rem; }
        .modal-summary-card.is-primary { background: linear-gradient(135deg, var(--primary-soft), #ffffff 58%); border-color: rgba(211,47,47,.16); }
        .summary-label { color: var(--muted); font-size: .7rem; font-weight: 850; text-transform: uppercase; letter-spacing: .07em; }
        .summary-value { color: var(--text); font-size: .92rem; font-weight: 850; line-height: 1.25; overflow-wrap: anywhere; }
        .summary-sub { color: var(--muted); font-size: .78rem; font-weight: 650; line-height: 1.35; }
        .detail-section-card { overflow: hidden; margin-bottom: .85rem; }
        .detail-section-head { display: flex; align-items: center; gap: .6rem; padding: .85rem .9rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #ffffff, #fbfdff); }
        .detail-section-icon { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 13px; }
        .detail-section-title { margin: 0; color: var(--text); font-size: .94rem; font-weight: 850; letter-spacing: -.018em; }
        .detail-section-help { margin: .1rem 0 0; color: var(--muted); font-size: .78rem; font-weight: 650; }
        .detail-section-body { padding: .9rem; }
        .detail-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: .72rem; }
        .detail-item { min-width: 0; padding: .72rem; background: #fbfdff; border: 1px solid var(--line); border-radius: 14px; }
        .detail-item.is-wide { grid-column: 1 / -1; }
        .detail-label { display: flex; align-items: center; gap: .38rem; margin-bottom: .32rem; color: var(--muted); font-size: .7rem; font-weight: 850; text-transform: uppercase; letter-spacing: .07em; }
        .detail-value { color: var(--text); font-size: .86rem; font-weight: 720; line-height: 1.5; overflow-wrap: anywhere; }
        .document-preview-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: .75rem; }
        .document-preview-card { overflow: hidden; }
        .document-preview-head { display: flex; align-items: center; justify-content: space-between; gap: .6rem; padding: .75rem .8rem; border-bottom: 1px solid var(--line); background: #fbfdff; }
        .document-preview-title { display: flex; align-items: center; gap: .45rem; margin: 0; color: var(--text); font-size: .84rem; font-weight: 850; }
        .document-preview-body { padding: .8rem; }
        .document-thumb { width: 100%; max-height: 190px; object-fit: cover; border: 1px solid var(--line); border-radius: 14px; cursor: zoom-in; box-shadow: var(--shadow-xs); }
        .document-empty { display: grid; place-items: center; min-height: 120px; color: var(--muted); background: #f9fafb; border: 1px dashed var(--line-strong); border-radius: 14px; font-size: .84rem; font-weight: 700; }
        .edit-info-banner { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: .75rem; align-items: center; margin-bottom: .9rem; padding: .85rem; background: radial-gradient(circle at top right, rgba(211,47,47,.08), transparent 34%), linear-gradient(180deg,#ffffff,#fbfdff); border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow-xs); }
        .edit-info-banner .application-avatar { width: 46px; height: 46px; border-radius: 15px; }
        .edit-info-title { color: var(--text); font-size: .98rem; font-weight: 850; line-height: 1.25; letter-spacing: -.02em; }
        .edit-info-subtitle { margin-top: .18rem; color: var(--muted); font-size: .8rem; font-weight: 650; line-height: 1.45; }
        .modern-edit-form { display: grid; gap: .85rem; padding: .95rem; background: #fbfdff; border: 1px solid var(--line); border-radius: 20px; }
        .edit-section-card { overflow: hidden; background: #ffffff; border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow-xs); }
        .edit-section-head { display: flex; align-items: flex-start; gap: .65rem; padding: .85rem .9rem; background: linear-gradient(180deg,#ffffff,#fbfdff); border-bottom: 1px solid var(--line); }
        .edit-section-icon { flex: 0 0 auto; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; color: var(--primary); background: var(--primary-soft); border: 1px solid rgba(211,47,47,.12); border-radius: 13px; }
        .edit-section-title { margin: .05rem 0 .12rem; color: var(--text); font-size: .92rem; font-weight: 850; letter-spacing: -.018em; }
        .edit-section-help { margin: 0; color: var(--muted); font-size: .78rem; font-weight: 650; line-height: 1.45; }
        .edit-section-body { padding: .9rem; }
        .edit-form-grid { display: grid; grid-template-columns: repeat(12,minmax(0,1fr)); gap: .75rem; }
        .edit-col-3 { grid-column: span 3; }
        .edit-col-4 { grid-column: span 4; }
        .edit-col-6 { grid-column: span 6; }
        .edit-col-12 { grid-column: span 12; }
        .activity-timeline { display: grid; gap: .65rem; }
        .activity-item { position: relative; margin: 0; padding: .85rem .9rem .85rem 1rem; background: #fbfdff; border: 1px solid var(--line); border-left: 4px solid var(--primary); border-radius: 14px; }
        .activity-time { color: var(--muted); font-size: .78rem; font-weight: 650; }

        @media (max-width: 991px) {
            .residency-hero-content { grid-template-columns: minmax(0, 1fr); }
            .residency-hero-actions { justify-content: flex-start; }
            .residency-table-head { flex-direction: column; align-items: stretch; }
            .application-modal-summary { grid-template-columns: repeat(2,minmax(0,1fr)); }
            .detail-grid,
            .document-preview-grid { grid-template-columns: minmax(0,1fr); }
            .edit-col-3,
            .edit-col-4,
            .edit-col-6 { grid-column: span 6; }
        }
        @media (max-width: 768px) {
            #residentsTable { min-width: 720px !important; }
            .application-modal-summary { grid-template-columns: minmax(0,1fr); }
            .edit-col-3,
            .edit-col-4,
            .edit-col-6,
            .edit-col-12 { grid-column: span 12; }
            .modal-footer { flex-direction: column-reverse; }
            .modal-footer .btn { width: 100%; }
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
        <a href="{{ route('admin.dashboard.index') }}" onclick="handleLinkClick(event, this)">
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
        <div class="dropdown-btn active" onclick="handleDropdownClick(event, this)">
            <i class="fas fa-users"></i>
            <span>Registry</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="submenu show" id="registrySubmenu">
            @admin_can('view_residents')
            <a href="{{ route('admin.residents.index') }}" class="active" onclick="handleSubmenuClick(event)"><i class="fas fa-user"></i> <span>Residents</span></a>
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
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-users"></i></span>
                    Residents
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            <div class="residency-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show residency-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show residency-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <section class="residency-hero">
                <div class="residency-hero-content">
                    <div class="min-w-0">
                        <div class="residency-hero-icon"><i class="fas fa-users"></i></div>
                        <div class="residency-kicker"><i class="fas fa-circle-check"></i> Resident registry</div>
                        <h2>Manage resident profiles, account status, valid IDs, and contact records in one clean workspace.</h2>
                        <p>Search, verify, update, and manage resident profiles with the same clean workflow used in residency applications.</p>
                    </div>
                    <div class="residency-hero-actions">
                        @admin_can('create_residents')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                            <i class="fas fa-plus"></i>
                            <span>Add Resident</span>
                        </a>
                        @endadmin_can
                        @admin_can('export_residents')
                        <button type="button" onclick="exportCSV()" class="btn btn-light-soft">
                            <i class="fas fa-file-csv"></i>
                            <span>Export CSV</span>
                        </button>
                        @endadmin_can
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-users"></i> Total: <strong>{{ number_format($totalResidents) }}</strong></span>
                    <span><i class="fas fa-user-check"></i> Active: <strong>{{ number_format($activeResidents) }}</strong></span>
                    <span><i class="fas fa-user-plus"></i> New: <strong>{{ number_format($newResidents) }}</strong></span>
                    <span><i class="fas fa-user-shield"></i> Seniors: <strong>{{ number_format($seniorResidents) }}</strong></span>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 residency-stats">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ number_format($totalResidents) }}</div>
                            <small class="text-success mt-2 d-none d-sm-block
                                {{ $growthPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas 
                                    {{ $growthPercentage >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} 
                                    me-1"></i> {{ $growthPercentage >= 0 ? '+' : '' }}{{ round($growthPercentage, 1) }}% from last month
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Active</div>
                            <div class="stat-number">{{ number_format($activeResidents)}}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>{{ round($activePercentage, 1)}}% active
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">New</div>
                            <div class="stat-number">{{ number_format($newResidents)}}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-calendar me-1"></i>+{{ number_format($yesterdayCount)}} from yesterday
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Senior</div>
                            <div class="stat-number">{{ number_format($seniorResidents)}}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-heart me-1"></i>{{ round($seniorPercentage, 1)}}% of total
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4 residency-filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.residents.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-filter me-2 text-primary"></i>
                                    <span>Filter Residents</span>
                                </h6>
                                <p class="text-muted small mb-0 mt-2">Find residents by name, username, email, status, or age group.</p>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_residents')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Resident</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
                                        <i class="fas fa-rotate"></i><span class="d-none d-sm-inline ms-2">Reset</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search and Filter Row -->
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search residents..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deactivated</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="age_range" class="form-select">
                                    <option value="">All Ages</option>
                                    <option value="minor" {{ request('age_range') == 'minor' ? 'selected' : '' }}>Minor (&lt;18)</option>
                                    <option value="adult" {{ request('age_range') == 'adult' ? 'selected' : '' }}>Adult (18-59)</option>
                                    <option value="senior" {{ request('age_range') == 'senior' ? 'selected' : '' }}>Senior (60+)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    @if(auth('admin')->user()->hasAnyPermission(['delete_residents', 'bulk_delete_residents', 'export_residents']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @if(auth('admin')->user()->hasAnyPermission(['delete_residents', 'bulk_delete_residents']))
                        <form id="bulkForm" method="POST" action="{{ route('admin.residents.bulkDelete') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Deactivate">
                                <i class="fas fa-trash-alt"></i>
                                <span class="d-none d-sm-inline">Bulk Deactivate</span>
                            </button>
                        </form>
                        @endif

                        @admin_can('export_residents')
                        <form id="exportForm" method="POST" action="{{ route('admin.residents.export') }}" style="display: inline;">
                            @csrf
                            <button type="button" onclick="exportCSV()" class="btn btn-outline-success d-flex align-items-center gap-2" title="Export CSV">
                                <i class="fas fa-file-csv"></i>
                                <span class="d-none d-sm-inline">Export</span>
                            </button>
                        </form>
                        @endadmin_can
                    </div>
                    @endif
                </div>
            </div>

            <!-- Residents Table - Residency Style Compact View -->
            <div class="card border-0 residency-table-card">
                <div class="card-body p-0">
                    <div class="residency-table-head">
                        <div class="table-head-title">
                            <span class="table-head-icon"><i class="fas fa-users-viewfinder"></i></span>
                            <div class="min-w-0">
                                <span class="table-kicker"><i class="fas fa-list-check"></i> Records table</span>
                                <h3>Resident directory</h3>
                                <p>Compact view for resident identity, contact details, verification state, and account actions.</p>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark"><i class="fas fa-database me-1"></i>{{ $residents->total() }} records</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="residentsTable">
                            <thead>
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'firstname', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Resident / ID
                                            @if(request('sort') == 'firstname')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Contact Details</th>
                                    <th>Age / Address</th>
                                    <th>Status / Verification</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($residents as $resident)
                                <tr class="{{ $resident->deleted_at ? 'table-danger' : '' }}">
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $resident->id }}" class="resident-checkbox">
                                    </td>
                                    <td>
                                        <div class="application-primary">
                                            <div class="application-avatar">
                                                @if($resident->profile_photo)
                                                    <img src="{{ asset($resident->profile_photo) }}" alt="{{ $resident->full_name }}" onerror="this.remove();">
                                                @else
                                                    <span>{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="application-name">{{ $resident->full_name }}</div>
                                                <div class="application-ref">
                                                    <span class="ref-pill"><i class="fas fa-hashtag"></i>ID {{ $resident->id }}</span>
                                                    <span>@</i>{{ $resident->username }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Email</span>
                                            <span class="info-main">{{ $resident->email ?? 'N/A' }}</span>
                                            <span class="info-sub"><i class="fas fa-phone me-1"></i>{{ $resident->contact ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Age</span>
                                            <span class="info-main">{{ $resident->age }} years old</span>
                                            <span class="info-sub"><i class="fas fa-location-dot me-1"></i>{{ Str::limit($resident->address, 36) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-cell">
                                            @if($resident->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger"><i class="fas fa-user-slash me-1"></i>Deactivated</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success"><i class="fas fa-circle-check me-1"></i>Active</span>
                                            @endif

                                            @php
                                                $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back) || !empty($resident->valid_id_selfie);
                                                $residentIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                            @endphp

                                            @if(!$hasAnyValidId)
                                                <span class="badge bg-secondary-subtle text-secondary"><i class="fas fa-id-card me-1"></i>No ID</span>
                                            @elseif($residentIdStatus === 'verified')
                                                <span class="badge bg-success-subtle text-success"><i class="fas fa-id-card me-1"></i>ID Verified</span>
                                            @elseif($residentIdStatus === 'rejected')
                                                <span class="badge bg-danger-subtle text-danger"><i class="fas fa-id-card me-1"></i>ID Rejected</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-id-card me-1"></i>ID For Review</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm action-btn action-view icon-only" data-bs-toggle="modal" data-bs-target="#viewResidentModal{{ $resident->id }}" title="View details">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>

                                            @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('update_residents'))
                                            <button type="button" class="btn btn-sm action-btn action-update" data-bs-toggle="modal" data-bs-target="#editResidentModal{{ $resident->id }}" title="Edit resident">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Edit</span>
                                            </button>
                                            @endif

                                            <button type="button" class="btn btn-sm action-btn action-docs icon-only" data-bs-toggle="modal" data-bs-target="#activityModal{{ $resident->id }}" title="Activity log">
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </button>

                                            <span class="action-divider">|</span>

                                            @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('delete_residents'))
                                                <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Deactivate this resident account? Their record and transactions will remain archived.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm action-btn action-archive icon-only" title="Deactivate">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($resident->deleted_at && auth('admin')->user()->hasPermission('restore_residents'))
                                                <form method="POST" action="{{ route('admin.residents.restore', $resident->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this resident?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm action-btn action-update icon-only" title="Restore">
                                                        <i class="fas fa-rotate-left"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state-box">
                                            <div class="empty-state-icon"><i class="fas fa-users"></i></div>
                                            <h5 class="text-muted">No residents found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter.</p>
                                            @admin_can('create_residents')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                                                <i class="fas fa-plus me-2"></i>Add New
                                            </button>
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $residents->firstItem() ?? 0 }}-{{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $residents->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Add Resident Modal with Residency-style Design -->
            @admin_can('create_residents')
            <div class="modal fade" id="addResidentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-plus"></i>
                                Add New Resident
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="edit-info-banner">
                                <div class="application-avatar"><i class="fas fa-user-plus"></i></div>
                                <div class="min-w-0">
                                    <div class="edit-info-title">Create a resident profile</div>
                                    <div class="edit-info-subtitle">Enter identity, contact, account, and address details using the same clean modal style as residency applications.</div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.residents.store') }}" id="addResidentForm" class="modern-edit-form">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="edit-section-card">
                                    <div class="edit-section-head">
                                        <span class="edit-section-icon"><i class="fas fa-id-card"></i></span>
                                        <div>
                                            <div class="edit-section-title">Personal Information</div>
                                            <p class="edit-section-help">Basic identity details for the resident record.</p>
                                        </div>
                                    </div>
                                    <div class="edit-section-body">
                                        <div class="edit-form-grid">
                                            <div class="edit-col-6">
                                                <label for="add_firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'add') @error('firstname') is-invalid @enderror @endif" name="firstname" id="add_firstname" value="{{ session('form_type') == 'add' ? old('firstname') : '' }}" required placeholder="Enter first name">
                                                @if(session('form_type') == 'add') @error('firstname') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_middlename" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control @if(session('form_type') == 'add') @error('middlename') is-invalid @enderror @endif" name="middlename" id="add_middlename" value="{{ session('form_type') == 'add' ? old('middlename') : '' }}" placeholder="Enter middle name (optional)">
                                                @if(session('form_type') == 'add') @error('middlename') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'add') @error('lastname') is-invalid @enderror @endif" name="lastname" id="add_lastname" value="{{ session('form_type') == 'add' ? old('lastname') : '' }}" required placeholder="Enter last name">
                                                @if(session('form_type') == 'add') @error('lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_suffix" class="form-label">Suffix</label>
                                                <select class="form-select @if(session('form_type') == 'add') @error('suffix') is-invalid @enderror @endif" name="suffix" id="add_suffix">
                                                    <option value="">None</option>
                                                    <option value="Jr." {{ session('form_type') == 'add' && old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                    <option value="Sr." {{ session('form_type') == 'add' && old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                    <option value="II" {{ session('form_type') == 'add' && old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                                    <option value="III" {{ session('form_type') == 'add' && old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                                    <option value="IV" {{ session('form_type') == 'add' && old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                                                    <option value="V" {{ session('form_type') == 'add' && old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                                                </select>
                                                @if(session('form_type') == 'add') @error('suffix') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" name="birthdate" id="add_birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                                <small class="text-muted">Resident must be 18 years old or above.</small>
                                                @if(session('form_type') == 'add') @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="edit-section-card">
                                    <div class="edit-section-head">
                                        <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                        <div>
                                            <div class="edit-section-title">Contact & Address</div>
                                            <p class="edit-section-help">Contact channels and full address for future document requests.</p>
                                        </div>
                                    </div>
                                    <div class="edit-section-body">
                                        <div class="edit-form-grid">
                                            <div class="edit-col-6">
                                                <label for="add_email" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @if(session('form_type') == 'add') @error('email') is-invalid @enderror @endif" name="email" id="add_email" value="{{ session('form_type') == 'add' ? old('email') : '' }}" required placeholder="email@example.com">
                                                @if(session('form_type') == 'add') @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'add') @error('contact') is-invalid @enderror @endif" name="contact" id="add_contact" value="{{ session('form_type') == 'add' ? old('contact') : '' }}" required placeholder="09XXXXXXXXX" maxlength="11">
                                                <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                                @if(session('form_type') == 'add') @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-12">
                                                <label for="add_address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'add') @error('address') is-invalid @enderror @endif" name="address" id="add_address" rows="2" required placeholder="House/Block/Lot No., Street, Subdivision, Barangay">{{ session('form_type') == 'add' ? old('address') : '' }}</textarea>
                                                @if(session('form_type') == 'add') @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="edit-section-card">
                                    <div class="edit-section-head">
                                        <span class="edit-section-icon"><i class="fas fa-lock"></i></span>
                                        <div>
                                            <div class="edit-section-title">Account Credentials</div>
                                            <p class="edit-section-help">Login username and secure password for the resident portal.</p>
                                        </div>
                                    </div>
                                    <div class="edit-section-body">
                                        <div class="edit-form-grid">
                                            <div class="edit-col-6">
                                                <label for="add_username" class="form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'add') @error('username') is-invalid @enderror @endif" name="username" id="add_username" value="{{ session('form_type') == 'add' ? old('username') : '' }}" required placeholder="Choose a username">
                                                @if(session('form_type') == 'add') @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control @if(session('form_type') == 'add') @error('password') is-invalid @enderror @endif" name="password" id="add_password" required minlength="8" onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                                <small class="text-muted">Minimum 8 characters. No spaces allowed.</small>
                                                @if(session('form_type') == 'add') @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                            </div>
                                            <div class="edit-col-6">
                                                <label for="add_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password_confirmation" class="form-control" id="add_password_confirmation" required onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                                <div class="invalid-feedback" id="add_pwc_feedback"></div>
                                            </div>
                                            <div class="edit-col-12">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" id="toggle_add_passwords">
                                                    <label class="form-check-label" for="toggle_add_passwords">Show password and confirm password</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" form="addResidentForm" class="btn btn-success" id="submitAddForm">
                                <i class="fas fa-save me-2"></i>Save Resident
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Activity Log Modals - Residency-style Timeline -->
            @foreach($residents as $resident)
            <div class="modal fade" id="activityModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-history"></i>
                                Activity Log
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="edit-info-banner">
                                <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                <div class="min-w-0">
                                    <div class="edit-info-title">{{ $resident->full_name }}</div>
                                    <div class="edit-info-subtitle">Recent changes and account activity for this resident record.</div>
                                </div>
                            </div>

                            <div class="detail-section-card mb-0">
                                <div class="detail-section-head">
                                    <span class="detail-section-icon"><i class="fas fa-clock-rotate-left"></i></span>
                                    <div>
                                        <div class="detail-section-title">Timeline</div>
                                        <p class="detail-section-help">Latest recorded actions for this resident.</p>
                                    </div>
                                </div>
                                <div class="detail-section-body">
                                    <div class="activity-timeline">
                                        @forelse($resident->activities as $activity)
                                            <div class="activity-item">
                                                <strong class="small">{{ $activity->action }}</strong>
                                                <div class="small text-muted mb-1">{{ $activity->description }}</div>
                                                <div class="activity-time small">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $activity->created_at->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        @empty
                                            <div class="document-empty">
                                                <span><i class="fas fa-history me-1"></i>No activity found for this resident</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

           <!-- Edit Resident Modals with Residency-style Design -->
            @if(auth('admin')->user()->hasPermission('update_residents'))
                @foreach($residents as $resident)
                <div class="modal fade" id="editResidentModal{{ $resident->id }}" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit"></i>
                                    Edit Resident
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="edit-info-banner">
                                    <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="edit-info-title">{{ $resident->full_name }}</div>
                                        <div class="edit-info-subtitle">Update resident identity, contact, and address information using the same form pattern as residency applications.</div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" id="editResidentForm{{ $resident->id }}" class="modern-edit-form" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $resident->id }}">

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-id-card"></i></span>
                                            <div>
                                                <div class="edit-section-title">Personal Information</div>
                                                <p class="edit-section-help">Core identity details displayed across resident records and request workflows.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-col-6">
                                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('firstname') is-invalid @enderror @endif" name="firstname" id="edit_firstname_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('firstname', $resident->firstname) : $resident->firstname }}" required>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('firstname') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('middlename') is-invalid @enderror @endif" name="middlename" id="edit_middlename_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('middlename', $resident->middlename) : $resident->middlename }}">
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('middlename') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('lastname') is-invalid @enderror @endif" name="lastname" id="edit_lastname_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('lastname', $resident->lastname) : $resident->lastname }}" required>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label class="form-label">Suffix</label>
                                                    <select class="form-select @if(session('form_type') == 'edit_' . $resident->id) @error('suffix') is-invalid @enderror @endif" name="suffix" id="edit_suffix_{{ $resident->id }}">
                                                        <option value="">None</option>
                                                        <option value="Jr." {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                        <option value="Sr." {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                        <option value="II" {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                                        <option value="III" {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                        <option value="IV" {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                                        <option value="V" {{ (session('form_type') == 'edit_' . $resident->id ? old('suffix', $resident->suffix) : $resident->suffix) == 'V' ? 'selected' : '' }}>V</option>
                                                    </select>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('suffix') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('birthdate') is-invalid @enderror @endif" name="birthdate" id="edit_birthdate_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('birthdate', $resident->birthdate) : $resident->birthdate }}" required max="{{ date('Y-m-d') }}">
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                            <div>
                                                <div class="edit-section-title">Contact & Address</div>
                                                <p class="edit-section-help">Keep contact records accurate for notifications and barangay transactions.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-col-6">
                                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('email') is-invalid @enderror @endif" name="email" id="edit_email_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('email', $resident->email) : $resident->email }}" required>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('contact') is-invalid @enderror @endif" name="contact" id="edit_contact_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('contact', $resident->contact) : $resident->contact }}" required maxlength="11">
                                                    <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-12">
                                                    <label class="form-label">Complete Address <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('address') is-invalid @enderror @endif" name="address" id="edit_address_{{ $resident->id }}" rows="2" required>{{ session('form_type') == 'edit_' . $resident->id ? old('address', $resident->address) : $resident->address }}</textarea>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-user-lock"></i></span>
                                            <div>
                                                <div class="edit-section-title">Account</div>
                                                <p class="edit-section-help">Username used for resident portal login.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-col-6">
                                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('username') is-invalid @enderror @endif" name="username" id="edit_username_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('username', $resident->username) : $resident->username }}" required>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-id-card-clip"></i></span>
                                            <div>
                                                <div class="edit-section-title">Valid ID Review</div>
                                                <p class="edit-section-help">View or replace uploaded ID scans, then approve, reject, or return them for verification.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            @php
                                                $editIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : (($resident->valid_id || $resident->valid_id_back || $resident->valid_id_selfie) ? 'for_verification' : 'for_verification'));
                                                $editDocuments = [
                                                    ['field' => 'valid_id', 'label' => 'Front ID', 'accept' => 'image/*,.pdf'],
                                                    ['field' => 'valid_id_back', 'label' => 'Back ID', 'accept' => 'image/*,.pdf'],
                                                    ['field' => 'valid_id_selfie', 'label' => 'Selfie with ID', 'accept' => 'image/*'],
                                                ];
                                            @endphp

                                            <div class="document-preview-grid mb-3">
                                                @foreach($editDocuments as $doc)
                                                    @php
                                                        $path = $resident->{$doc['field']};
                                                        $ext = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
                                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                                                    @endphp
                                                    <div class="document-preview-card">
                                                        <div class="document-preview-head">
                                                            <h6 class="document-preview-title"><i class="fas fa-image"></i>{{ $doc['label'] }}</h6>
                                                        </div>
                                                        <div class="document-preview-body">
                                                            @if($path)
                                                                @if($isImage)
                                                                    <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" onclick="document.getElementById('editResidentDocFull{{ $resident->id }}_{{ $doc['field'] }}').style.display='flex'" onerror="this.outerHTML='<div class=\'document-empty\'>Image unavailable</div>'">
                                                                    <div id="editResidentDocFull{{ $resident->id }}_{{ $doc['field'] }}" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                                                                        <img src="{{ asset($path) }}" style="max-width:90vw;max-height:90vh;border-radius:12px;">
                                                                    </div>
                                                                @else
                                                                    <a href="{{ asset($path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                        <i class="fas fa-file-pdf me-1"></i>View File
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <div class="document-empty"><span><i class="fas fa-circle-info me-1"></i>Not uploaded</span></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="edit-form-grid">
                                                @foreach($editDocuments as $doc)
                                                    <div class="edit-col-4">
                                                        <label class="form-label">Replace {{ $doc['label'] }}</label>
                                                        <input type="file" name="{{ $doc['field'] }}" accept="{{ $doc['accept'] }}" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error($doc['field']) is-invalid @enderror @endif">
                                                        <small class="text-muted">Leave empty to keep current file.</small>
                                                        @if(session('form_type') == 'edit_' . $resident->id) @error($doc['field']) <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                    </div>
                                                @endforeach

                                                <div class="edit-col-6">
                                                    <label class="form-label">Valid ID Status</label>
                                                    <select name="valid_id_status" id="edit_valid_id_status_{{ $resident->id }}" class="form-select @if(session('form_type') == 'edit_' . $resident->id) @error('valid_id_status') is-invalid @enderror @endif">
                                                        <option value="for_verification" {{ old('valid_id_status', $editIdStatus) === 'for_verification' ? 'selected' : '' }}>For verification</option>
                                                        <option value="verified" {{ old('valid_id_status', $editIdStatus) === 'verified' ? 'selected' : '' }}>Approved / Verified</option>
                                                        <option value="rejected" {{ old('valid_id_status', $editIdStatus) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('valid_id_status') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>

                                                <div class="edit-col-12">
                                                    <label class="form-label">Remarks / Rejection Reason</label>
                                                    <textarea name="valid_id_rejection_reason" id="edit_valid_id_remarks_{{ $resident->id }}" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('valid_id_rejection_reason') is-invalid @enderror @endif" rows="3" maxlength="500" placeholder="Required when status is Rejected. Example: Blurry back ID, name unreadable, expired ID.">{{ old('valid_id_rejection_reason', $resident->valid_id_rejection_reason) }}</textarea>
                                                    @if(session('form_type') == 'edit_' . $resident->id) @error('valid_id_rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="editResidentForm{{ $resident->id }}" class="btn btn-primary" id="submitEditForm{{ $resident->id }}">
                                    <i class="fas fa-save me-2"></i>Update Resident
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Resident Modals - Residency-style Details -->
            @foreach($residents as $resident)
            <div class="modal fade" id="viewResidentModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-user"></i>
                                Resident Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="application-modal-summary">
                                <div class="modal-summary-card is-primary">
                                    <span class="summary-label">Resident</span>
                                    <span class="summary-value">{{ $resident->full_name }}</span>
                                    <span class="summary-sub"><i class="fas fa-user-tag me-1"></i>{{ $resident->username }} · ID #{{ $resident->id }}</span>
                                </div>
                                <div class="modal-summary-card">
                                    <span class="summary-label">Age</span>
                                    <span class="summary-value">{{ $resident->age }}</span>
                                    <span class="summary-sub">years old</span>
                                </div>
                                <div class="modal-summary-card">
                                    <span class="summary-label">Account</span>
                                    <span class="summary-value">{{ $resident->deleted_at ? 'Deactivated' : 'Active' }}</span>
                                    <span class="summary-sub">Current status</span>
                                </div>
                                @php
                                    $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back) || !empty($resident->valid_id_selfie);
                                    $viewIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                    $viewIdStatusLabel = match($viewIdStatus) {
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                        'for_verification' => 'For Review',
                                        default => 'None',
                                    };
                                @endphp
                                <div class="modal-summary-card">
                                    <span class="summary-label">Valid ID</span>
                                    <span class="summary-value">{{ $viewIdStatusLabel }}</span>
                                    <span class="summary-sub">Identity check</span>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-head">
                                    <span class="detail-section-icon"><i class="fas fa-id-card"></i></span>
                                    <div>
                                        <div class="detail-section-title">Personal Information</div>
                                        <p class="detail-section-help">Basic resident identity details.</p>
                                    </div>
                                </div>
                                <div class="detail-section-body">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-user"></i>First Name</div>
                                            <div class="detail-value">{{ $resident->firstname }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-user"></i>Middle Name</div>
                                            <div class="detail-value">{{ $resident->middlename ?? 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-user"></i>Last Name</div>
                                            <div class="detail-value">{{ $resident->lastname }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-tag"></i>Suffix</div>
                                            <div class="detail-value">{{ $resident->suffix ?? 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-cake-candles"></i>Birthdate</div>
                                            <div class="detail-value">{{ $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') : 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-calendar"></i>Age</div>
                                            <div class="detail-value">{{ $resident->age }} years old</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-head">
                                    <span class="detail-section-icon"><i class="fas fa-address-book"></i></span>
                                    <div>
                                        <div class="detail-section-title">Contact Information</div>
                                        <p class="detail-section-help">Communication and address details.</p>
                                    </div>
                                </div>
                                <div class="detail-section-body">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-envelope"></i>Email</div>
                                            <div class="detail-value">{{ $resident->email ?? 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="fas fa-phone"></i>Contact Number</div>
                                            <div class="detail-value">{{ $resident->contact ?? 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item is-wide">
                                            <div class="detail-label"><i class="fas fa-map-marker-alt"></i>Complete Address</div>
                                            <div class="detail-value">{{ $resident->address ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-head">
                                    <span class="detail-section-icon"><i class="fas fa-id-card-clip"></i></span>
                                    <div>
                                        <div class="detail-section-title">Profile Photo & Valid ID Review</div>
                                        <p class="detail-section-help">Uploaded identity assets, approval status, rejection remarks, and admin decision controls.</p>
                                    </div>
                                </div>
                                <div class="detail-section-body">
                                    @php
                                        $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back) || !empty($resident->valid_id_selfie);
                                        $viewIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                        $viewDocuments = [
                                            ['field' => 'profile_photo', 'label' => 'Profile Photo', 'icon' => 'fa-user-circle'],
                                            ['field' => 'valid_id', 'label' => 'Valid ID Front', 'icon' => 'fa-id-card'],
                                            ['field' => 'valid_id_back', 'label' => 'Valid ID Back', 'icon' => 'fa-id-card-clip'],
                                            ['field' => 'valid_id_selfie', 'label' => 'Selfie with ID', 'icon' => 'fa-camera'],
                                        ];
                                    @endphp

                                    <div class="document-preview-grid">
                                        @foreach($viewDocuments as $doc)
                                            @php
                                                $path = $resident->{$doc['field']};
                                                $ext = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                                            @endphp
                                            <div class="document-preview-card">
                                                <div class="document-preview-head">
                                                    <h6 class="document-preview-title"><i class="fas {{ $doc['icon'] }}"></i>{{ $doc['label'] }}</h6>
                                                    @if(in_array($doc['field'], ['valid_id', 'valid_id_back', 'valid_id_selfie']) && $path)
                                                        @if($viewIdStatus === 'verified')
                                                            <span class="badge bg-success-subtle text-success">Verified</span>
                                                        @elseif($viewIdStatus === 'rejected')
                                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning">For Review</span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="document-preview-body">
                                                    @if($path)
                                                        @if($isImage)
                                                            <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" onclick="document.getElementById('residentDocFull{{ $resident->id }}_{{ $doc['field'] }}').style.display='flex'" onerror="this.outerHTML='<div class=\'document-empty\'>Image unavailable</div>'">
                                                            <div id="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                                                                <img src="{{ asset($path) }}" style="max-width:90vw;max-height:90vh;border-radius:12px;">
                                                            </div>
                                                        @else
                                                            <a href="{{ asset($path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fas fa-file-pdf me-1"></i>View File
                                                            </a>
                                                        @endif
                                                    @else
                                                        <div class="document-empty"><span><i class="fas fa-circle-info me-1"></i>Not uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="detail-section-card mb-0 mt-3">
                                        <div class="detail-section-head">
                                            <span class="detail-section-icon"><i class="fas fa-user-shield"></i></span>
                                            <div>
                                                <div class="detail-section-title">Verification Decision</div>
                                                <p class="detail-section-help">Approve only if the front and back ID images are clear and match the resident record.</p>
                                            </div>
                                        </div>
                                        <div class="detail-section-body">
                                            <div class="detail-grid">
                                                <div class="detail-item">
                                                    <div class="detail-label"><i class="fas fa-clipboard-check"></i>Current ID Status</div>
                                                    <div class="detail-value">
                                                        @if($viewIdStatus === 'verified')
                                                            <span class="badge bg-success-subtle text-success"><i class="fas fa-check me-1"></i>Verified</span>
                                                        @elseif($viewIdStatus === 'rejected')
                                                            <span class="badge bg-danger-subtle text-danger"><i class="fas fa-xmark me-1"></i>Rejected</span>
                                                        @elseif($viewIdStatus === 'for_verification')
                                                            <span class="badge bg-warning-subtle text-warning"><i class="fas fa-hourglass-half me-1"></i>For verification</span>
                                                        @else
                                                            <span class="badge bg-secondary-subtle text-secondary"><i class="fas fa-circle-info me-1"></i>No valid ID</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <div class="detail-label"><i class="far fa-calendar-check"></i>Verified At</div>
                                                    <div class="detail-value">{{ $resident->valid_id_verified_at ? $resident->valid_id_verified_at->format('F d, Y h:i A') : 'Not verified yet' }}</div>
                                                </div>
                                                <div class="detail-item is-wide">
                                                    <div class="detail-label"><i class="fas fa-message"></i>Remarks</div>
                                                    <div class="detail-value">{{ $resident->valid_id_rejection_reason ?: 'No remarks.' }}</div>
                                                </div>
                                            </div>

                                            @if(auth('admin')->user()->hasPermission('update_residents'))
                                                @if(!empty($resident->valid_id) || !empty($resident->valid_id_back))
                                                    <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                                                        <form method="POST" action="{{ route('admin.residents.verifyId', $resident->id) }}" class="flex-fill" onsubmit="return confirm('Approve and verify the valid ID for {{ addslashes($resident->full_name) }}?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success w-100">
                                                                <i class="fas fa-check me-1"></i>Approve ID
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <form method="POST" action="{{ route('admin.residents.rejectId', $resident->id) }}" class="mt-3" onsubmit="return confirm('Reject the valid ID for {{ addslashes($resident->full_name) }}?')">
                                                        @csrf
                                                        <label class="form-label">Reject Remarks <span class="text-danger">*</span></label>
                                                        <textarea name="valid_id_rejection_reason" class="form-control @error('valid_id_rejection_reason') is-invalid @enderror" rows="3" maxlength="500" required placeholder="Example: Back ID is blurry, photo does not match, expired ID, or details are unreadable.">{{ old('valid_id_rejection_reason') }}</textarea>
                                                        @error('valid_id_rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                        <button type="submit" class="btn btn-outline-danger w-100 mt-2">
                                                            <i class="fas fa-xmark me-1"></i>Reject ID
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="alert alert-danger mb-0 mt-3">
                                                        <i class="fas fa-triangle-exclamation me-1"></i>No valid ID image is uploaded yet, so this resident cannot be approved.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section-card mb-0">
                                <div class="detail-section-head">
                                    <span class="detail-section-icon"><i class="fas fa-cog"></i></span>
                                    <div>
                                        <div class="detail-section-title">Account Information</div>
                                        <p class="detail-section-help">Record timestamps and active/deactivated status.</p>
                                    </div>
                                </div>
                                <div class="detail-section-body">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="far fa-calendar-alt"></i>Registered At</div>
                                            <div class="detail-value">{{ $resident->created_at ? $resident->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label"><i class="far fa-clock"></i>Last Updated</div>
                                            <div class="detail-value">{{ $resident->updated_at ? $resident->updated_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                        </div>
                                        <div class="detail-item is-wide">
                                            <div class="detail-label"><i class="fas fa-circle-check"></i>Status</div>
                                            <div class="detail-value">
                                                @if(!$resident->deleted_at)
                                                    <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger"><i class="fas fa-times-circle me-1"></i>Deactivated</span>
                                                    <small class="text-muted d-block mt-1">Deactivated at: {{ $resident->deleted_at->format('F d, Y h:i A') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>

    <script>
        // Bulk Archive function
        function bulkDelete() {

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one resident to deactivate.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Deactivate',
                text: `Are you sure you want to deactivate ${checkboxes.length} selected resident(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Deactivate'
            }).then((result) => {

                if (result.isConfirmed) {

                    // Attach selected IDs
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkForm.appendChild(input);
                    });

                    bulkForm.submit();
                }

            });
        }

        // Export CSV function
        function exportCSV() {

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            // If nothing selected → Ask to export all
            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No residents selected. Export all records?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Export All'
                }).then((result) => {

                    if (result.isConfirmed) {
                        exportForm.submit();
                    }

                });

                return;
            }

            // If selected → Confirm export selected
            Swal.fire({
                title: 'Export Selected?',
                text: `Export ${checkboxes.length} selected resident(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Export'
            }).then((result) => {

                if (result.isConfirmed) {

                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        exportForm.appendChild(input);
                    });

                    exportForm.submit();
                }

            });
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.resident-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Confirm delete with SweetAlert
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: message || 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            
            return false;
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swal if not loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return confirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.resident-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                });
            }

            // Add loading animation to filter form
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                        submitBtn.disabled = true;
                    }
                });
            }

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Show appropriate modal if there are validation errors
            @if ($errors->any() && session('form_type'))
                @if(session('form_type') == 'add')
                    var addModal = new bootstrap.Modal(document.getElementById('addResidentModal'));
                    addModal.show();
                @elseif(Str::startsWith(session('form_type'), 'edit_'))
                    var editId = '{{ session('form_type') }}'.replace('edit_', '');
                    var editModal = new bootstrap.Modal(document.getElementById('editResidentModal' + editId));
                    editModal.show();
                @endif
            @endif
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Real-time validation for add form
        document.addEventListener('DOMContentLoaded', function() {
            const addForm = document.getElementById('addResidentForm');
            if (!addForm) return;

            const namePattern     = /^[a-zA-Z\s\-\.]+$/;
            const usernamePattern = /^[a-zA-Z0-9_]+$/;
            const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function calculateAge(birthDateValue) {
                if (!birthDateValue) return null;
                const birthDate = new Date(birthDateValue);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            function setField(el, msg) {
                if (msg) {
                    el.setCustomValidity(msg);
                    el.classList.add('is-invalid');
                    let fb = el.parentNode.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.parentNode.appendChild(fb); }
                    fb.textContent = msg;
                } else {
                    el.setCustomValidity('');
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }
            }

            // Clear any server-side is-invalid marks as soon as the user starts typing
            addForm.querySelectorAll('.is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }, { once: true });
            });

            // First name
            const fn = document.getElementById('add_firstname');
            if (fn) fn.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'First name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'First name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'First name must be at least 2 characters.');
                setField(this, '');
            });

            // Last name
            const ln = document.getElementById('add_lastname');
            if (ln) ln.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'Last name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'Last name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'Last name must be at least 2 characters.');
                setField(this, '');
            });

            // Username
            const un = document.getElementById('add_username');
            if (un) un.addEventListener('input', function() {
                if (!this.value)                          return setField(this, 'Username is required.');
                if (!usernamePattern.test(this.value))    return setField(this, 'Username can only contain letters, numbers, and underscores.');
                if (this.value.length < 3)                return setField(this, 'Username must be at least 3 characters.');
                setField(this, '');
            });

            // Email
            const em = document.getElementById('add_email');
            if (em) em.addEventListener('input', function() {
                if (!this.value)                        return setField(this, 'Email is required.');
                if (!emailPattern.test(this.value))     return setField(this, 'Please enter a valid email address.');
                setField(this, '');
            });

            // Contact
            const contact = document.getElementById('add_contact');
            if (contact) contact.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                if (!this.value)                        return setField(this, 'Contact number is required.');
                if (!this.value.startsWith('09'))       return setField(this, 'Contact number must start with 09.');
                if (this.value.length !== 11)           return setField(this, 'Contact number must be exactly 11 digits.');
                setField(this, '');
            });

            // Birthdate
            const bd = document.getElementById('add_birthdate');
            if (bd) bd.addEventListener('change', function() {
                const sel = new Date(this.value);
                const today = new Date(); today.setHours(0,0,0,0);
                const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                const age = calculateAge(this.value);
                if (sel >= today)    return setField(this, 'Birthdate must be in the past.');
                if (sel < minDate)   return setField(this, 'Please enter a valid birthdate.');
                if (age === null || age < 18) return setField(this, 'Resident must be 18 years old or above.');
                setField(this, '');
            });

            // Password strength
            const pw  = document.getElementById('add_password');
            const pwc = document.getElementById('add_password_confirmation');

            function validatePw() {
                // Remove spaces from password
                pw.value = pw.value.replace(/\s/g, "");
                if (!pw.value)                          return setField(pw, 'Password is required.');
                if (pw.value.length < 8)                return setField(pw, 'Password must be at least 8 characters.');
                if (!/[A-Z]/.test(pw.value))            return setField(pw, 'Password must contain at least one uppercase letter.');
                if (!/[a-z]/.test(pw.value))            return setField(pw, 'Password must contain at least one lowercase letter.');
                if (!/[0-9]/.test(pw.value))            return setField(pw, 'Password must contain at least one number.');
                if (!/[^A-Za-z0-9]/.test(pw.value))    return setField(pw, 'Password must contain at least one special character.');
                setField(pw, '');
                if (pwc.value) validatePwc();
            }
            function validatePwc() {
                // Remove spaces from confirm password
                pwc.value = pwc.value.replace(/\s/g, "");
                if (pwc.value !== pw.value) return setField(pwc, 'Passwords do not match.');
                setField(pwc, '');
            }
            if (pw)  pw.addEventListener('input', validatePw);
            if (pwc) pwc.addEventListener('input', validatePwc);

            const toggleAddPasswords = document.getElementById('toggle_add_passwords');
            if (toggleAddPasswords && pw && pwc) {
                toggleAddPasswords.addEventListener('change', function() {
                    const inputType = this.checked ? 'text' : 'password';
                    pw.type = inputType;
                    pwc.type = inputType;
                });
            }
        });
        // Real-time validation for edit forms
        @if(auth('admin')->user()->hasPermission('update_residents'))
            @foreach($residents as $resident)
            (function(id) {
                const form = document.getElementById('editResidentForm' + id);
                if (!form) return;

                function sf(el, msg) {
                    if (!el) return;
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (msg) {
                        el.setCustomValidity(msg);
                        el.classList.add('is-invalid');
                        if (fb) fb.textContent = msg;
                    } else {
                        el.setCustomValidity('');
                        el.classList.remove('is-invalid');
                        if (fb) fb.textContent = '';
                    }
                }

                // Clear server-side errors on first keystroke
                form.querySelectorAll('.is-invalid').forEach(function(el) {
                    el.addEventListener('input', function() {
                        el.classList.remove('is-invalid');
                        const fb = el.parentNode.querySelector('.invalid-feedback');
                        if (fb) fb.textContent = '';
                    }, { once: true });
                });

                const namePattern     = /^[a-zA-Z\s\-\.]+$/;
                const usernamePattern = /^[a-zA-Z0-9_]+$/;
                const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const fn = document.getElementById('edit_firstname_' + id);
                if (fn) fn.addEventListener('input', function() {
                    if (!this.value.trim())            return sf(this, 'First name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'First name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'First name must be at least 2 characters.');
                    sf(this, '');
                });

                const ln = document.getElementById('edit_lastname_' + id);
                if (ln) ln.addEventListener('input', function() {
                    if (!this.value.trim())            return sf(this, 'Last name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'Last name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'Last name must be at least 2 characters.');
                    sf(this, '');
                });

                const un = document.getElementById('edit_username_' + id);
                if (un) un.addEventListener('input', function() {
                    if (!this.value)                        return sf(this, 'Username is required.');
                    if (!usernamePattern.test(this.value))  return sf(this, 'Username can only contain letters, numbers, and underscores.');
                    if (this.value.length < 3)              return sf(this, 'Username must be at least 3 characters.');
                    sf(this, '');
                });

                const em = document.getElementById('edit_email_' + id);
                if (em) em.addEventListener('input', function() {
                    if (!this.value)                    return sf(this, 'Email is required.');
                    if (!emailPattern.test(this.value)) return sf(this, 'Please enter a valid email address.');
                    sf(this, '');
                });

                const ct = document.getElementById('edit_contact_' + id);
                if (ct) ct.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (!this.value)                  return sf(this, 'Contact number is required.');
                    if (!this.value.startsWith('09')) return sf(this, 'Contact number must start with 09.');
                    if (this.value.length !== 11)     return sf(this, 'Contact number must be exactly 11 digits.');
                    sf(this, '');
                });

                const bd = document.getElementById('edit_birthdate_' + id);
                if (bd) bd.addEventListener('change', function() {
                    const sel = new Date(this.value);
                    const today = new Date(); today.setHours(0,0,0,0);
                    const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                    if (sel >= today)   return sf(this, 'Birthdate must be in the past.');
                    if (sel < minDate)  return sf(this, 'Please enter a valid birthdate.');
                    sf(this, '');
                });

                const idStatus = document.getElementById('edit_valid_id_status_' + id);
                const idRemarks = document.getElementById('edit_valid_id_remarks_' + id);
                function syncValidIdRemarks() {
                    if (!idStatus || !idRemarks) return;
                    const isRejected = idStatus.value === 'rejected';
                    idRemarks.required = isRejected;
                    idRemarks.placeholder = isRejected
                        ? 'Required: explain why the valid ID is rejected.'
                        : 'Optional remarks for this valid ID review.';
                    if (!isRejected) sf(idRemarks, '');
                }
                if (idStatus) {
                    idStatus.addEventListener('change', syncValidIdRemarks);
                    syncValidIdRemarks();
                }

            })('{{ $resident->id }}');
            @endforeach
        @endif
    </script>

    <!-- SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>

