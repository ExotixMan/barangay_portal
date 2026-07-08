<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatbot Management - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --radius-xl: 22px;
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

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
            background: var(--page-bg);
            color: var(--text);
            overflow-x: hidden;
        }

        .min-w-0 {
            min-width: 0 !important;
        }

        .title-icon,
        .hero-icon,
        .chatbot-stat-icon,
        .panel-icon,
        .empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin: 0;
            color: var(--text);
            font-size: 1.28rem;
            font-weight: 850;
            letter-spacing: -.035em;
        }

        .title-icon {
            width: 40px;
            height: 40px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 12px;
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

        .profile-badge {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .38rem .5rem .38rem .78rem;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 999px;
            box-shadow: var(--shadow-xs);
            color: var(--text);
            font-weight: 700;
        }

        #userDropdown {
            color: var(--text);
        }

        .role-badge {
            padding: .28rem .65rem;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 800;
        }

        .chatbot-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .chatbot-alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .chatbot-alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .hero-panel,
        .chatbot-stat,
        .chatbot-panel,
        .training-note,
        .permission-panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xs);
        }

        .chatbot-hero {
            margin-bottom: 1rem;
        }

        .hero-panel {
            position: relative;
            overflow: hidden;
            min-height: auto;
            padding: clamp(1.15rem, 2vw, 1.65rem);
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(135deg, rgba(211, 47, 47, .04), transparent 42%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 920px;
        }

        .hero-icon {
            width: 46px;
            height: 46px;
            margin-bottom: .8rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .14);
            border-radius: 14px;
            font-size: 1.15rem;
        }

        .hero-kicker,
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--primary-dark);
            font-size: .73rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .hero-panel h2 {
            margin: .55rem 0 .55rem;
            color: var(--text);
            font-size: clamp(1.75rem, 3vw, 2.55rem);
            font-weight: 850;
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .hero-panel p,
        .panel-copy,
        .empty-state p,
        .training-note p,
        .permission-panel p {
            color: var(--muted);
            line-height: 1.65;
        }

        .hero-panel p {
            max-width: 760px;
            margin: 0;
            font-size: .96rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            margin-top: 1.15rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .48rem;
            min-height: 42px;
            padding: .65rem .95rem;
            border-radius: 12px;
            font-weight: 760;
            letter-spacing: -.01em;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease, border-color .16s ease, color .16s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:focus-visible,
        .form-control:focus,
        .form-select:focus {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
            box-shadow: none;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 12px 26px rgba(211, 47, 47, .22);
        }

        .btn-light-soft,
        .btn-secondary,
        .btn-outline-secondary {
            color: var(--text);
            background: var(--card);
            border: 1px solid var(--line-strong);
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover,
        .btn-secondary:hover,
        .btn-outline-secondary:hover {
            color: var(--primary-dark);
            background: #fffafa;
            border-color: rgba(211, 47, 47, .22);
        }

        .btn-outline-primary {
            color: var(--primary);
            background: var(--card);
            border-color: rgba(211, 47, 47, .25);
        }

        .btn-outline-primary:hover {
            color: #ffffff;
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-danger {
            color: var(--danger);
            background: var(--card);
            border-color: rgba(220, 38, 38, .25);
        }

        .btn-outline-danger:hover {
            color: #ffffff;
            background: var(--danger);
            border-color: var(--danger);
        }

        .btn-outline-success {
            color: var(--success);
            background: var(--card);
            border-color: rgba(22, 128, 60, .25);
        }

        .btn-outline-success:hover {
            color: #ffffff;
            background: var(--success);
            border-color: var(--success);
        }

        .btn-sm,
        .btn-group-sm > .btn {
            min-height: 36px;
            padding: .5rem .68rem;
            border-radius: 11px;
            font-size: .82rem;
        }

        .chatbot-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-bottom: .9rem;
        }

        .chatbot-stat {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: 1rem;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .chatbot-stat:hover,
        .chatbot-panel:hover {
            border-color: var(--line-strong);
            box-shadow: var(--shadow-sm);
        }

        .chatbot-stat:hover {
            transform: translateY(-1px);
        }

        .chatbot-stat-icon {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 13px;
            font-size: 1rem;
        }

        .chatbot-stat span {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .chatbot-stat strong {
            display: block;
            margin-top: .28rem;
            color: var(--text);
            font-size: clamp(1.3rem, 2vw, 1.75rem);
            font-weight: 850;
            line-height: 1.1;
            letter-spacing: -.045em;
            overflow-wrap: anywhere;
        }

        .chatbot-stat small {
            display: block;
            margin-top: .42rem;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 600;
            line-height: 1.45;
        }

        .training-note {
            display: flex;
            align-items: center;
            gap: .9rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
        }

        .training-note .panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid #dbeafe;
            border-radius: 14px;
        }

        .training-note h3 {
            margin: 0 0 .2rem;
            color: var(--text);
            font-size: 1rem;
            font-weight: 820;
        }

        .training-note p {
            margin: 0;
            font-size: .88rem;
        }

        .chatbot-workspace {
            display: grid;
            gap: 1rem;
            align-items: start;
        }

        .chatbot-panel {
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .panel-title {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            min-width: 0;
        }

        .panel-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .panel-header h3 {
            margin: .16rem 0 .15rem;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .panel-copy {
            margin: 0;
            font-size: .86rem;
        }

        .panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .knowledge-toolbar {
            display: grid;
            grid-template-columns: minmax(280px, 1fr) auto minmax(170px, 220px) minmax(150px, 190px) auto auto;
            align-items: center;
            gap: .6rem;
            padding: .9rem 1rem;
            background: #ffffff;
            border-bottom: 1px solid var(--line);
        }

        .search-control,
        .filter-control {
            position: relative;
            min-width: 0;
        }

        .search-control i,
        .filter-control i {
            position: absolute;
            top: 50%;
            left: .85rem;
            z-index: 2;
            color: var(--muted-2);
            font-size: .9rem;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .search-control .form-control,
        .filter-control .form-select {
            width: 100%;
            min-height: 42px;
            padding-left: 2.35rem;
        }

        .filter-control .form-select {
            padding-right: 2.1rem;
            color: #344054;
        }

        .search-submit-btn {
            min-width: 96px;
            white-space: nowrap;
        }

        .filter-reset-btn {
            min-width: 42px;
            padding-inline: .75rem;
        }

        .knowledge-add-btn {
            min-width: 76px;
            white-space: nowrap;
        }

        .chatbot-count {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            width: fit-content;
            padding: .42rem .68rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .tab-navigation {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: 1rem;
            padding: .35rem;
            max-width: 100%;
            overflow-x: auto;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 999px;
            box-shadow: var(--shadow-xs);
            scrollbar-width: thin;
        }

        .tab-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            min-height: 38px;
            padding: .55rem .85rem;
            color: var(--muted);
            background: transparent;
            border: 0;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 820;
            white-space: nowrap;
            transition: background .16s ease, color .16s ease, box-shadow .16s ease;
            cursor: pointer;
        }

        .tab-link:hover {
            color: var(--primary-dark);
            background: var(--primary-soft);
        }

        .tab-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 10px 22px rgba(211, 47, 47, .18);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn .16s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        }

        .form-control::placeholder {
            color: var(--muted-2);
        }

        textarea.form-control {
            min-height: 150px;
            line-height: 1.65;
            resize: vertical;
        }

        #kb-search {
            width: 100% !important;
        }

        .filter-empty-state {
            border-top: 1px solid var(--line);
        }

        .filter-empty-state.d-none {
            display: none !important;
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

        .table tbody td {
            vertical-align: middle;
            padding: .95rem;
            color: var(--text);
            border-bottom: 1px solid var(--line);
            font-size: .86rem;
        }

        .table tbody tr {
            transition: background .16s ease;
        }

        .table tbody tr:hover {
            background: #fcfcfd;
        }

        .kb-question {
            color: var(--text);
            font-weight: 820;
            line-height: 1.4;
            white-space: normal;
            min-width: 220px;
        }

        .kb-answer {
            max-width: 440px;
            color: var(--muted) !important;
            font-size: .83rem !important;
            line-height: 1.55;
            white-space: normal !important;
        }

        .keyword-text {
            display: block;
            max-width: 180px;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 650;
            line-height: 1.45;
            white-space: normal;
        }

        .badge-category,
        .status-badge,
        .usage-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            border-radius: 999px;
            white-space: nowrap;
            font-weight: 820;
        }

        .badge-category {
            padding: .4rem .62rem;
            color: var(--primary-dark) !important;
            background: var(--primary-soft) !important;
            border: 1px solid rgba(211, 47, 47, .12);
            font-size: .74rem;
        }

        .usage-pill {
            min-width: 34px;
            padding: .38rem .58rem;
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
            font-size: .75rem;
        }

        .status-badge {
            padding: .4rem .62rem;
            font-size: .74rem;
        }

        .status-badge.is-resolved {
            color: var(--success);
            background: var(--success-soft);
            border: 1px solid rgba(22, 128, 60, .14);
        }

        .status-badge.is-pending {
            color: #9a3412;
            background: var(--warning-soft);
            border: 1px solid #fed7aa;
        }

        .unmatched-row td {
            background: #fffdfb;
        }

        .resolved-row td {
            color: #667085;
            background: #fcfcfd;
        }

        .unmatched-query {
            max-width: 420px;
            color: var(--text);
            font-weight: 820;
            line-height: 1.45;
            white-space: normal;
        }

        .session-pill {
            display: inline-flex;
            align-items: center;
            max-width: 140px;
            padding: .34rem .55rem;
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 760;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-group {
            gap: .42rem;
        }

        .btn-group > .btn {
            border-radius: 11px !important;
        }

        .empty-state {
            display: grid;
            place-items: center;
            min-height: 240px;
            padding: 2rem;
            text-align: center;
            color: var(--muted);
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 20px;
            font-size: 1.35rem;
        }

        .empty-state h4 {
            margin-bottom: .35rem;
            color: var(--text);
            font-size: 1rem;
            font-weight: 820;
        }

        .empty-state p {
            max-width: 360px;
            margin: 0 auto;
            font-size: .88rem;
        }

        .permission-panel {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            padding: 1rem;
            color: #7c2d12;
            background: var(--warning-soft);
            border-color: #fed7aa;
        }

        .permission-panel i {
            margin-top: .18rem;
        }

        .modal-dialog-knowledge {
            max-width: 920px;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: var(--radius-xl);
            box-shadow: 0 24px 70px rgba(16, 24, 40, .18);
        }

        .knowledge-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem;
            background:
                radial-gradient(circle at top right, rgba(211, 47, 47, .08), transparent 34%),
                linear-gradient(180deg, #ffffff, #fbfdff);
            border-bottom: 1px solid var(--line);
        }

        .modal-heading {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            min-width: 0;
        }

        .knowledge-modal-icon {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 15px;
            font-size: 1.15rem;
        }

        .modal-title {
            margin: .08rem 0 .15rem;
            color: var(--text);
            font-size: 1.08rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .modal-subtitle {
            max-width: 620px;
            margin: 0;
            color: var(--muted);
            font-size: .86rem;
            line-height: 1.55;
        }

        .modal-body {
            padding: 1rem;
            background: #ffffff;
        }

        .knowledge-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(260px, .75fr);
            gap: .9rem;
        }

        .knowledge-form-panel {
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
        }

        .knowledge-form-panel.is-soft {
            background: #fbfdff;
        }

        .knowledge-form-panel-title {
            display: flex;
            align-items: center;
            gap: .48rem;
            margin-bottom: .75rem;
            color: var(--text);
            font-size: .88rem;
            font-weight: 850;
            letter-spacing: -.015em;
        }

        .knowledge-form-panel-title i {
            color: var(--primary);
        }

        .knowledge-tip {
            display: flex;
            align-items: flex-start;
            gap: .62rem;
            margin-top: .9rem;
            padding: .78rem;
            color: #075985;
            background: var(--info-soft);
            border: 1px solid #dbeafe;
            border-radius: 14px;
            font-size: .8rem;
            line-height: 1.5;
        }

        .knowledge-tip i {
            margin-top: .14rem;
        }

        .knowledge-modal-footer {
            display: flex;
            justify-content: space-between;
            gap: .65rem;
            padding: 0 1rem 1rem;
            background: #ffffff;
            border-top: 0;
        }

        .modal-footer-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .55rem;
        }

        .modal-safe-note {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 650;
        }

        .form-label {
            color: #344054;
            font-size: .82rem;
            font-weight: 820 !important;
        }

        .form-hint {
            display: block;
            margin-top: .35rem;
            color: var(--muted-2);
            font-size: .75rem;
            font-weight: 650;
            line-height: 1.4;
        }

        .is-invalid {
            border-color: var(--danger) !important;
            background-image: none !important;
        }

        .is-invalid:focus {
            border-color: var(--danger) !important;
            outline-color: rgba(220, 38, 38, .16);
        }

        .invalid-feedback {
            display: block;
            margin-top: .35rem;
            color: var(--danger);
            font-size: .76rem;
            font-weight: 760;
        }

        .chatbot-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: min(340px, calc(100vw - 32px));
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
        }

        .alert-warning.chatbot-toast {
            color: #7c2d12;
            background: var(--warning-soft);
            border-color: #fed7aa;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        @media (max-width: 1199px) {
            .chatbot-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .panel-header {
                flex-direction: column;
            }

            .panel-actions,
            .panel-actions .btn,
            #kb-search {
                width: 100% !important;
            }

            .knowledge-toolbar {
                grid-template-columns: minmax(0, 1fr) auto;
            }

            .knowledge-toolbar .filter-control,
            .knowledge-toolbar .filter-reset-btn,
            .knowledge-toolbar .knowledge-add-btn {
                width: 100%;
            }

            .knowledge-form-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .knowledge-modal-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .modal-footer-actions,
            .modal-footer-actions .btn {
                width: 100%;
            }

            .table {
                min-width: 820px;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: inline-flex;
            }

            .profile-badge span:not(.role-badge) {
                display: none;
            }

            .header {
                padding: .85rem 1rem;
            }

            .page-title {
                font-size: 1.02rem;
            }

            .chatbot-page {
                max-width: 100%;
            }

            .hero-panel,
            .chatbot-stat,
            .chatbot-panel,
            .training-note {
                border-radius: 16px;
            }

            .hero-panel,
            .training-note,
            .panel-header,
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: .9rem;
            }

            .hero-actions,
            .hero-actions form,
            .hero-actions .btn {
                width: 100%;
            }

            .chatbot-stats-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .chatbot-stat {
                min-height: auto;
            }

            .training-note {
                align-items: flex-start;
            }

            .tab-navigation {
                width: 100%;
                border-radius: 16px;
            }

            .tab-link {
                flex: 1 0 auto;
            }

            .knowledge-toolbar {
                grid-template-columns: minmax(0, 1fr);
                padding: .9rem;
            }

            .search-submit-btn,
            .filter-reset-btn,
            .knowledge-add-btn {
                width: 100%;
            }

            .btn-group {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
            }
        }

        @media (max-width: 575px) {
            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .role-badge {
                display: none;
            }

            .title-icon,
            .mobile-menu-btn {
                width: 38px;
                height: 38px;
            }

            .hero-panel h2 {
                font-size: 1.75rem;
            }
        }
    

/* ==========================================================
           POLISHED MOBILE UX PATCH - Chatbot Management
           Desktop remains unchanged. Phones get cleaner cards/tabs/forms.
        ========================================================== */
        @media (max-width: 767.98px) {
            html {
                font-size: 68%;
            }

            .main-content {
                min-width: 0;
            }

            .header {
                position: sticky;
                top: 0;
                z-index: 20;
                padding: .78rem .9rem !important;
                background: rgba(255, 255, 255, .96);
                backdrop-filter: blur(10px);
            }

            .header > div {
                min-width: 0;
            }

            .mobile-menu-btn {
                display: inline-flex !important;
                flex: 0 0 40px;
                width: 40px;
                height: 40px;
                padding: 0;
            }

            .page-title {
                min-width: 0;
                font-size: 1rem !important;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .title-icon,
            .hero-icon,
            .chatbot-stat-icon,
            .panel-icon,
            .empty-icon {
                width: 38px !important;
                height: 38px !important;
                border-radius: 12px !important;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .78rem !important;
            }

            .chatbot-page {
                max-width: 100%;
            }

            .hero-panel,
            .chatbot-stat,
            .chatbot-panel,
            .training-note,
            .permission-panel {
                border-radius: 18px !important;
            }

            .hero-panel {
                padding: 1rem !important;
            }

            .hero-panel h2 {
                font-size: 1.52rem !important;
                line-height: 1.12 !important;
                letter-spacing: -.035em !important;
            }

            .hero-panel p {
                font-size: .88rem !important;
                line-height: 1.55 !important;
            }

            .hero-actions {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .55rem !important;
                width: 100%;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .chatbot-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .65rem !important;
                margin-bottom: .85rem !important;
            }

            .chatbot-stat {
                padding: .85rem !important;
                gap: .65rem !important;
            }

            .chatbot-stat span {
                font-size: .66rem !important;
            }

            .chatbot-stat strong {
                font-size: 1.26rem !important;
            }

            .chatbot-stat small {
                display: none !important;
            }

            .training-note {
                align-items: flex-start !important;
                padding: .95rem !important;
                gap: .75rem !important;
            }

            .training-note h3 {
                font-size: .95rem !important;
            }

            .training-note p {
                font-size: .82rem !important;
                line-height: 1.5 !important;
            }

            .tab-navigation {
                display: grid !important;
                grid-auto-flow: column;
                grid-auto-columns: minmax(132px, 1fr);
                width: 100%;
                padding: .35rem !important;
                border-radius: 16px !important;
                overflow-x: auto;
            }

            .tab-link {
                min-height: 40px;
                padding: .55rem .75rem !important;
                border-radius: 12px !important;
                font-size: .78rem !important;
            }

            .panel-header {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: .8rem !important;
                padding: .95rem !important;
            }

            .panel-title {
                gap: .65rem !important;
            }

            .panel-copy {
                font-size: .82rem !important;
                line-height: 1.55 !important;
            }

            .panel-actions {
                display: grid !important;
                grid-template-columns: 1fr !important;
                width: 100%;
                gap: .55rem !important;
                justify-content: stretch !important;
            }

            .panel-actions .btn,
            .panel-actions .chatbot-count {
                width: 100%;
                justify-content: center;
            }

            .knowledge-toolbar {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .6rem !important;
                padding: .85rem !important;
            }

            .search-submit-btn,
            .filter-reset-btn,
            .knowledge-add-btn,
            .knowledge-toolbar .btn {
                width: 100%;
            }

            .search-control .form-control,
            .filter-control .form-select {
                min-height: 44px;
            }

            .chatbot-panel .table-responsive {
                overflow: visible !important;
                border-radius: 0 !important;
            }

            #knowledge-table,
            .chatbot-panel .table-responsive > table.table {
                display: block !important;
                min-width: 0 !important;
                width: 100% !important;
                border-collapse: separate !important;
                background: transparent !important;
            }

            #knowledge-table thead,
            .chatbot-panel .table-responsive > table.table thead {
                display: none !important;
            }

            #knowledge-table tbody,
            .chatbot-panel .table-responsive > table.table tbody {
                display: grid !important;
                gap: .78rem !important;
                padding: .78rem !important;
                background: #f8fafc !important;
            }

            #knowledge-table tbody tr,
            .chatbot-panel .table-responsive > table.table tbody tr {
                display: grid !important;
                grid-template-columns: 1fr;
                gap: .65rem;
                padding: .95rem !important;
                background: #ffffff;
                border: 1px solid var(--line);
                border-radius: 18px;
                box-shadow: var(--shadow-xs);
            }

            #knowledge-table tbody tr:hover,
            .chatbot-panel .table-responsive > table.table tbody tr:hover {
                background: #ffffff !important;
                box-shadow: var(--shadow-sm);
            }

            #knowledge-table tbody td,
            .chatbot-panel .table-responsive > table.table tbody td {
                display: block !important;
                width: 100% !important;
                padding: 0 !important;
                border: 0 !important;
                color: var(--text);
                font-size: .86rem;
                text-align: left !important;
                white-space: normal !important;
            }

            #knowledge-table .kb-question,
            .chatbot-panel .unmatched-query {
                color: var(--text);
                font-size: .96rem;
                font-weight: 850;
                line-height: 1.35;
                word-break: break-word;
            }

            #knowledge-table .kb-answer,
            #knowledge-table .keyword-text {
                max-width: 100% !important;
                color: var(--muted) !important;
                font-size: .82rem !important;
                line-height: 1.55 !important;
                word-break: break-word;
            }

            #knowledge-table tbody td:nth-child(2),
            #knowledge-table tbody td:nth-child(3),
            #knowledge-table tbody td:nth-child(4),
            #knowledge-table tbody td:nth-child(5),
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(2),
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(3),
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(4) {
                padding: .55rem .7rem !important;
                background: #f9fafb;
                border: 1px solid var(--line) !important;
                border-radius: 12px;
            }

            #knowledge-table tbody td:nth-child(2)::before { content: "Answer"; }
            #knowledge-table tbody td:nth-child(3)::before { content: "Keywords"; }
            #knowledge-table tbody td:nth-child(4)::before { content: "Category"; }
            #knowledge-table tbody td:nth-child(5)::before { content: "Uses"; }
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(2)::before { content: "Session"; }
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(3)::before { content: "Time"; }
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(4)::before { content: "Status"; }

            #knowledge-table tbody td:nth-child(2)::before,
            #knowledge-table tbody td:nth-child(3)::before,
            #knowledge-table tbody td:nth-child(4)::before,
            #knowledge-table tbody td:nth-child(5)::before,
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(2)::before,
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(3)::before,
            .chatbot-panel .table-responsive > table.table:not(#knowledge-table) tbody td:nth-child(4)::before {
                display: block;
                margin-bottom: .28rem;
                color: var(--muted);
                font-size: .68rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            #knowledge-table tbody td:last-child,
            .chatbot-panel .table-responsive > table.table tbody td:last-child {
                padding-top: .72rem !important;
                border-top: 1px dashed var(--line-strong) !important;
            }

            #knowledge-table .btn-group,
            .chatbot-panel .table-responsive > table.table .btn-group {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .55rem !important;
                width: 100%;
            }

            #knowledge-table .btn-group .btn,
            .chatbot-panel .table-responsive > table.table .btn-group .btn {
                width: 100%;
                min-height: 40px;
                border-radius: 11px !important;
                font-size: .78rem;
            }

            #knowledge-table .btn[title]::after {
                content: attr(title);
                margin-left: .3rem;
            }

            #knowledge-table tbody tr:has(td[colspan]),
            .chatbot-panel .table-responsive > table.table tbody tr:has(td[colspan]) {
                display: block !important;
                padding: 0 !important;
                border: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            #knowledge-table tbody td[colspan],
            .chatbot-panel .table-responsive > table.table tbody td[colspan] {
                display: block !important;
                padding: 0 !important;
            }

            .empty-state {
                min-height: 210px;
                padding: 1.4rem !important;
                border: 1px dashed var(--line-strong);
                border-radius: 18px;
                background: #ffffff;
            }

            .modal-dialog {
                margin: .65rem !important;
            }

            .modal-content {
                max-height: calc(100dvh - 1.3rem);
                border-radius: 18px !important;
            }

            .modal-header {
                position: sticky;
                top: 0;
                z-index: 3;
                padding: .9rem !important;
                background: #ffffff !important;
                border-bottom: 1px solid var(--line) !important;
            }

            .modal-title {
                font-size: .96rem !important;
                line-height: 1.25;
            }

            .modal-body {
                max-height: calc(100dvh - 155px) !important;
                padding: .9rem !important;
            }

            .modal-footer {
                position: sticky;
                bottom: 0;
                z-index: 3;
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .55rem !important;
                padding: .8rem .9rem !important;
                background: #ffffff !important;
                border-top: 1px solid var(--line) !important;
            }

            .modal-footer .btn,
            .modal-footer form {
                width: 100%;
            }
        }

        @media (max-width: 389.98px) {
            .chatbot-stats-grid {
                grid-template-columns: 1fr !important;
            }

            #knowledge-table .btn-group,
            .chatbot-panel .table-responsive > table.table .btn-group {
                grid-template-columns: 1fr !important;
            }
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
        <a href="{{ route('admin.chatbot.index') }}" class="active" onclick="handleLinkClick(event, this)">
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
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()" type="button" aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    <span class="title-icon"><i class="fas fa-robot"></i></span>
                    Chatbot Management
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            @admin_can('view_content')
            <div class="chatbot-page">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show chatbot-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show chatbot-alert" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <section class="chatbot-hero">
                    <div class="hero-panel">
                        <div class="hero-content">
                            <div class="hero-icon"><i class="fas fa-robot"></i></div>
                            <div class="hero-kicker"><i class="fas fa-circle-check"></i> Chatbot Control Center</div>
                            <h2>Keep chatbot answers clean, useful, and easy to train.</h2>
                            <p>Manage the bot knowledge base, review unanswered questions, and improve future responses with the same minimal card layout used in Backup Settings.</p>

                            <div class="hero-actions">
                                @admin_can('create_content')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
                                    <i class="bi bi-plus-lg"></i>Add Knowledge
                                </button>
                                @endadmin_can
                                <button class="btn btn-light-soft" type="button" data-tab-jump="unmatched">
                                    <i class="bi bi-magic"></i>Review Training Queue
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="chatbot-stats-grid">
                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-database-fill"></i></div>
                        <div class="min-w-0">
                            <span>Knowledge Items</span>
                            <strong id="stat-knowledge">{{ $stats['knowledge_count'] ?? 0 }}</strong>
                            <small>Total saved answers available to the bot.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                        <div class="min-w-0">
                            <span>Conversations</span>
                            <strong id="stat-conv">{{ $stats['conversation_count'] ?? 0 }}</strong>
                            <small>Recorded user chatbot sessions.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-send-fill"></i></div>
                        <div class="min-w-0">
                            <span>Total Messages</span>
                            <strong id="stat-msg">{{ $stats['message_count'] ?? 0 }}</strong>
                            <small>Messages handled across conversations.</small>
                        </div>
                    </div>

                    <div class="chatbot-stat">
                        <div class="chatbot-stat-icon"><i class="bi bi-question-circle-fill"></i></div>
                        <div class="min-w-0">
                            <span>Unmatched Queries</span>
                            <strong id="stat-unmatched">{{ $stats['unmatched_count'] ?? 0 }}</strong>
                            <small>Questions that need training review.</small>
                        </div>
                    </div>
                </div>

                <div class="training-note">
                    <div class="panel-icon"><i class="bi bi-lightbulb-fill"></i></div>
                    <div class="min-w-0">
                        <h3>Training reminder</h3>
                        <p>Prioritize unmatched questions with repeated resident intent, then convert them into short, clear knowledge items with searchable keywords.</p>
                    </div>
                </div>

                <div class="tab-navigation" role="tablist" aria-label="Chatbot management tabs">
                    <button class="tab-link active" data-tab="knowledge" type="button">
                        <i class="fas fa-database"></i>Knowledge Base
                    </button>
                    <button class="tab-link" data-tab="unmatched" type="button">
                        <i class="fas fa-circle-question"></i>Unmatched Queries
                    </button>
                </div>

                <div class="chatbot-workspace">
                    <div id="tab-knowledge" class="tab-content active">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-database-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-stars"></i> Bot Knowledge</div>
                                        <h3>Knowledge Base</h3>
                                        <p class="panel-copy">Search, edit, and organize chatbot answers by category and keywords.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count" id="kb-count">{{ count($knowledge) }} items</span>
                                </div>
                            </div>

                            <div class="knowledge-toolbar" aria-label="Knowledge base search and filters">
                                <div class="search-control">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="form-control form-control-sm" id="kb-search" placeholder="Search question, answer, keyword...">
                                </div>

                                <button class="btn btn-primary btn-sm search-submit-btn" id="kb-search-btn" type="button">
                                    <i class="bi bi-search"></i>Search
                                </button>

                                <div class="filter-control">
                                    <i class="bi bi-funnel"></i>
                                    <select class="form-select form-select-sm" id="kb-category-filter" aria-label="Filter by category">
                                        <option value="">All categories</option>
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                </div>

                                <div class="filter-control">
                                    <i class="bi bi-activity"></i>
                                    <select class="form-select form-select-sm" id="kb-usage-filter" aria-label="Filter by usage">
                                        <option value="">All usage</option>
                                        <option value="used">Used</option>
                                        <option value="unused">Unused</option>
                                    </select>
                                </div>

                                <button class="btn btn-light-soft btn-sm filter-reset-btn" id="kb-filter-reset" type="button" title="Reset filters">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>

                                @admin_can('create_content')
                                <button class="btn btn-primary btn-sm knowledge-add-btn" data-bs-toggle="modal" data-bs-target="#addModal" type="button">
                                    <i class="bi bi-plus-lg"></i>Add
                                </button>
                                @endadmin_can
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="knowledge-table">
                                    <thead>
                                        <tr>
                                            <th style="width:26%">Question</th>
                                            <th style="width:34%">Answer</th>
                                            <th style="width:16%">Keywords</th>
                                            <th style="width:10%">Category</th>
                                            <th style="width:6%">Uses</th>
                                            <th style="width:8%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="knowledge-tbody">
                                        @forelse($knowledge as $k)
                                        <tr data-id="{{ $k['id'] }}" data-category="{{ strtolower($k['category'] ?? 'general') }}" data-usage="{{ $k['usage_count'] ?? 0 }}">
                                            <td class="kb-question">{{ Str::limit($k['question'], 90) }}</td>
                                            <td class="kb-answer">{{ Str::limit($k['answer'], 130) }}</td>
                                            <td><span class="keyword-text">{{ Str::limit($k['keywords'] ?? 'No keywords', 70) }}</span></td>
                                            <td><span class="badge-category">{{ $k['category'] ?? 'general' }}</span></td>
                                            <td><span class="usage-pill">{{ $k['usage_count'] ?? 0 }}</span></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-outline-secondary btn-edit" data-id="{{ $k['id'] }}" data-question="{{ addslashes($k['question']) }}" data-answer="{{ addslashes($k['answer']) }}" data-keywords="{{ addslashes($k['keywords'] ?? '') }}" data-category="{{ $k['category'] ?? 'general' }}" title="Edit" type="button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('delete_content')
                                                    <button class="btn btn-outline-danger btn-delete" data-id="{{ $k['id'] }}" title="Delete" type="button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <div>
                                                        <div class="empty-icon"><i class="bi bi-database-add"></i></div>
                                                        <h4>No knowledge items yet</h4>
                                                        <p>Add the first answer so the chatbot can respond to common resident questions.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="filter-empty-state d-none" id="kb-filter-empty">
                                <div class="empty-state">
                                    <div>
                                        <div class="empty-icon"><i class="bi bi-search"></i></div>
                                        <h4>No matching knowledge items</h4>
                                        <p>Try a different search term, category, or usage filter.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-unmatched" class="tab-content">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-question-circle-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-magic"></i> Training Queue</div>
                                        <h3>Unmatched Queries</h3>
                                        <p class="panel-copy">Review questions the bot could not answer and turn useful ones into knowledge items.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count">{{ $stats['unmatched_count'] ?? count($unmatched) }} pending</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:36%">Query</th>
                                            <th style="width:16%">Session</th>
                                            <th style="width:16%">Time</th>
                                            <th style="width:12%">Status</th>
                                            <th style="width:20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($unmatched as $u)
                                        <tr class="{{ $u['resolved'] ? 'resolved-row' : 'unmatched-row' }}" data-unmatched-id="{{ $u['id'] }}">
                                            <td class="unmatched-query" title="{{ $u['query'] }}">{{ $u['query'] }}</td>
                                            <td><span class="session-pill">{{ Str::limit($u['session_id'] ?? 'No session', 16) }}</span></td>
                                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</small></td>
                                            <td>
                                                @if($u['resolved'])
                                                    <span class="status-badge is-resolved">Resolved</span>
                                                @else
                                                    <span class="status-badge is-pending">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('create_content')
                                                    <button class="btn btn-outline-primary btn-train" data-query="{{ addslashes($u['query']) }}" type="button">
                                                        <i class="bi bi-plus"></i>Train Bot
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('edit_content')
                                                    @if(!$u['resolved'])
                                                        <button class="btn btn-outline-success btn-resolve" data-id="{{ $u['id'] }}" type="button">
                                                            <i class="bi bi-check2"></i>Resolve
                                                        </button>
                                                    @endif
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="empty-state">
                                                    <div>
                                                        <div class="empty-icon"><i class="bi bi-check2-circle"></i></div>
                                                        <h4>No unmatched queries</h4>
                                                        <p>Your chatbot is currently answering all recorded questions successfully.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="permission-panel">
                <i class="fas fa-lock"></i>
                <div>
                    <strong>You do not have permission to view chatbot management.</strong>
                    <p class="mb-0">Ask a system administrator to enable the required chatbot content permission for your account.</p>
                </div>
            </div>
            @endadmin_can
        </main>
    </div>

    @admin_can('create_content')
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addKnowledgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-knowledge modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header knowledge-modal-header">
                    <div class="modal-heading">
                        <span class="knowledge-modal-icon"><i class="bi bi-plus-circle"></i></span>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="addKnowledgeTitle">Add Knowledge</h5>
                            <p class="modal-subtitle">Create a clear chatbot answer residents can find through related keywords and category filtering.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form" class="knowledge-form">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Main answer</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" required placeholder="e.g. How do I track my Barangay Clearance request?">
                                    <span class="form-hint">Use the same wording residents commonly ask.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" rows="7" required placeholder="Write a short, complete answer with clear steps..."></textarea>
                                    <span class="form-hint">Keep the answer direct, accurate, and easy to scan.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization</div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <span class="form-hint">This powers the table filter.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="keywords" placeholder="clearance, reference id, status">
                                    <span class="form-hint">Separate keywords with commas.</span>
                                </div>
                                <div class="knowledge-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div>Add important aliases residents may use, like “certificate,” “request ID,” or “track status.”</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer knowledge-modal-footer">
                    <span class="modal-safe-note"><i class="bi bi-shield-check"></i> Saved items become available to chatbot training.</span>
                    <div class="modal-footer-actions">
                        <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save-add"><i class="bi bi-save"></i>Save Knowledge</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can

    @admin_can('edit_content')
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editKnowledgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-knowledge modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header knowledge-modal-header">
                    <div class="modal-heading">
                        <span class="knowledge-modal-icon"><i class="bi bi-pencil-square"></i></span>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="editKnowledgeTitle">Edit Knowledge</h5>
                            <p class="modal-subtitle">Update the chatbot answer, keywords, and category without changing the existing management flow.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" class="knowledge-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Main answer</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" id="edit-question" required>
                                    <span class="form-hint">Edit the resident-facing question or topic title.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" id="edit-answer" rows="7" required></textarea>
                                    <span class="form-hint">Make sure the answer is still correct before updating.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization</div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category" id="edit-category">
                                        <option value="general">General</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Residency</option>
                                        <option value="indigency">Indigency</option>
                                        <option value="blotter">Incident Reports</option>
                                        <option value="tracking">Request Tracking</option>
                                        <option value="announcements">Announcements</option>
                                        <option value="events">Events</option>
                                        <option value="projects">Projects</option>
                                        <option value="portal">Portal Account</option>
                                        <option value="support">Support</option>
                                    </select>
                                    <span class="form-hint">Changing this updates where the item appears in the filter.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="keywords" id="edit-keywords" placeholder="clearance, reference id, status">
                                    <span class="form-hint">Use comma-separated search terms.</span>
                                </div>
                                <div class="knowledge-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div>Use simple words residents type naturally, not only official document names.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer knowledge-modal-footer">
                    <span class="modal-safe-note"><i class="bi bi-clock-history"></i> Updates apply after saving.</span>
                    <div class="modal-footer-actions">
                        <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save-edit"><i class="bi bi-save"></i>Update Knowledge</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endadmin_can

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script>
        (function () {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const chatbotRoutes = {
                knowledgeStore: @json(route('admin.chatbot.knowledge.store')),
                knowledgeUpdateTemplate: @json(route('admin.chatbot.knowledge.update', ['id' => '__ID__'])),
                knowledgeDeleteTemplate: @json(route('admin.chatbot.knowledge.delete', ['id' => '__ID__'])),
                unmatchedResolveTemplate: @json(route('admin.chatbot.unmatched.resolve', ['id' => '__ID__']))
            };

            function activateTab(tab) {
                document.querySelectorAll('.tab-link[data-tab]').forEach(l => l.classList.toggle('active', l.dataset.tab === tab));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                const panel = document.getElementById('tab-' + tab);
                if (panel) {
                    panel.classList.add('active');
                }
            }

            document.querySelectorAll('.tab-link[data-tab]').forEach(link => {
                link.addEventListener('click', function () {
                    activateTab(this.dataset.tab);
                });
            });

            document.querySelectorAll('[data-tab-jump]').forEach(button => {
                button.addEventListener('click', function () {
                    activateTab(this.dataset.tabJump);
                    document.querySelector('.tab-navigation')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            const allowedCategories = ['general', 'clearance', 'residency', 'indigency', 'blotter', 'tracking', 'announcements', 'events', 'projects', 'portal', 'support'];

            function setFieldError(input, message) {
                if (!input) {
                    return;
                }
                input.classList.add('is-invalid');
                let feedback = input.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    input.insertAdjacentElement('afterend', feedback);
                }
                feedback.textContent = message;
            }

            function clearFieldError(input) {
                if (!input) {
                    return;
                }
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }

            function clearFormErrors(form) {
                if (!form) {
                    return;
                }
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }

            function validateKnowledgeForm(form) {
                if (!form) {
                    return false;
                }

                clearFormErrors(form);

                const question = form.querySelector('[name="question"]');
                const answer = form.querySelector('[name="answer"]');
                const keywords = form.querySelector('[name="keywords"]');
                const category = form.querySelector('[name="category"]');

                let valid = true;

                const qVal = (question?.value || '').trim();
                if (!qVal) {
                    setFieldError(question, 'Question is required.');
                    valid = false;
                } else if (qVal.length < 6) {
                    setFieldError(question, 'Question must be at least 6 characters.');
                    valid = false;
                } else if (qVal.length > 500) {
                    setFieldError(question, 'Question must not exceed 500 characters.');
                    valid = false;
                }

                const aVal = (answer?.value || '').trim();
                if (!aVal) {
                    setFieldError(answer, 'Answer is required.');
                    valid = false;
                } else if (aVal.length < 10) {
                    setFieldError(answer, 'Answer must be at least 10 characters.');
                    valid = false;
                } else if (aVal.length > 5000) {
                    setFieldError(answer, 'Answer must not exceed 5000 characters.');
                    valid = false;
                }

                const kVal = (keywords?.value || '').trim();
                if (kVal.length > 500) {
                    setFieldError(keywords, 'Keywords must not exceed 500 characters.');
                    valid = false;
                } else if (kVal && !/^[a-zA-Z0-9,\s\-]+$/.test(kVal)) {
                    setFieldError(keywords, 'Keywords can only contain letters, numbers, commas, spaces, and hyphens.');
                    valid = false;
                }

                const cVal = (category?.value || '').trim();
                if (!allowedCategories.includes(cVal)) {
                    setFieldError(category, 'Please select a valid category.');
                    valid = false;
                }

                return valid;
            }

            function applyServerErrors(form, errors) {
                if (!form || !errors) {
                    return;
                }
                Object.entries(errors).forEach(([field, messages]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input && Array.isArray(messages) && messages.length) {
                        setFieldError(input, messages[0]);
                    }
                });
            }

            async function api(method, url, body) {
                const opts = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                };
                if (body) {
                    opts.body = JSON.stringify(body);
                }
                const res = await fetch(url, opts);
                const payload = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const error = new Error(payload.message || `HTTP ${res.status}`);
                    error.status = res.status;
                    error.payload = payload;
                    throw error;
                }
                return payload;
            }

            function withId(template, id) {
                return template.replace('__ID__', String(id));
            }

            function formData(formEl) {
                const fd = new FormData(formEl);
                return Object.fromEntries(fd.entries());
            }

            function toast(msg, type = 'success') {
                const el = document.createElement('div');
                const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'x-circle');
                el.className = `alert alert-${type} fade show chatbot-toast`;
                el.setAttribute('role', 'alert');
                el.innerHTML = `<i class="bi bi-${icon} me-2"></i>${msg}`;
                document.body.appendChild(el);

                setTimeout(() => {
                    el.classList.remove('show');
                    setTimeout(() => el.remove(), 250);
                }, 3500);
            }

            ['add-form', 'edit-form', 'quick-add-form'].forEach(formId => {
                const form = document.getElementById(formId);
                if (!form) {
                    return;
                }
                ['question', 'answer', 'keywords', 'category'].forEach(fieldName => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.addEventListener('input', () => clearFieldError(input));
                        input.addEventListener('change', () => clearFieldError(input));
                    }
                });
            });

            const quickAddForm = document.getElementById('quick-add-form');
            if (quickAddForm) {
            quickAddForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (!validateKnowledgeForm(this)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const data = formData(this);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge added!');
                        this.reset();
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(this, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            const saveAddBtn = document.getElementById('save-add');
            if (saveAddBtn) {
            saveAddBtn.addEventListener('click', async function () {
                const addForm = document.getElementById('add-form');
                if (!validateKnowledgeForm(addForm)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const data = formData(addForm);
                try {
                    const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                    if (res.success) {
                        toast('Knowledge saved!');
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(addForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit-id').value = this.dataset.id;
                    document.getElementById('edit-question').value = this.dataset.question;
                    document.getElementById('edit-answer').value = this.dataset.answer;
                    document.getElementById('edit-keywords').value = this.dataset.keywords;
                    document.getElementById('edit-category').value = this.dataset.category;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
            });

            const saveEditBtn = document.getElementById('save-edit');
            if (saveEditBtn) {
            saveEditBtn.addEventListener('click', async function () {
                const editForm = document.getElementById('edit-form');
                if (!validateKnowledgeForm(editForm)) {
                    toast('Please fix the validation errors first.', 'warning');
                    return;
                }
                const id = document.getElementById('edit-id').value;
                const data = formData(editForm);
                try {
                    const res = await api('PUT', withId(chatbotRoutes.knowledgeUpdateTemplate, id), data);
                    if (res.success) {
                        toast('Updated!');
                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toast(res.message || 'Error', 'danger');
                    }
                } catch (error) {
                    if (error.status === 422) {
                        applyServerErrors(editForm, error.payload?.errors);
                        toast('Please fix the validation errors first.', 'warning');
                        return;
                    }
                    toast('Network error', 'danger');
                }
            });
            }

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async function () {
                    if (!confirm('Delete this knowledge item? This cannot be undone.')) {
                        return;
                    }
                    const id = this.dataset.id;
                    try {
                        const res = await api('DELETE', withId(chatbotRoutes.knowledgeDeleteTemplate, id));
                        if (res.success) {
                            this.closest('tr').remove();
                            toast('Deleted.');
                        } else {
                            toast('Delete failed.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
                    }
                });
            });

            document.querySelectorAll('.btn-train').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('add-form').querySelector('[name="question"]').value = this.dataset.query;
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                });
            });

            document.querySelectorAll('.btn-resolve').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id = this.dataset.id;
                    try {
                        const res = await api('PATCH', withId(chatbotRoutes.unmatchedResolveTemplate, id));
                        if (res.success) {
                            const row = this.closest('tr');
                            if (row) {
                                row.classList.remove('unmatched-row');
                                row.classList.add('resolved-row');
                                const statusCell = row.children[3];
                                if (statusCell) {
                                    statusCell.innerHTML = '<span class="badge bg-success-subtle text-success">Resolved</span>';
                                }
                            }
                            this.remove();
                            toast('Marked as resolved.');
                        } else {
                            toast('Unable to mark as resolved.', 'danger');
                        }
                    } catch {
                        toast('Network error', 'danger');
                    }
                });
            });

            const kbSearch = document.getElementById('kb-search');
            const kbSearchBtn = document.getElementById('kb-search-btn');
            const kbCategoryFilter = document.getElementById('kb-category-filter');
            const kbUsageFilter = document.getElementById('kb-usage-filter');
            const kbFilterReset = document.getElementById('kb-filter-reset');
            const kbFilterEmpty = document.getElementById('kb-filter-empty');
            const kbCount = document.getElementById('kb-count');

            function applyKnowledgeFilters() {
                const q = (kbSearch?.value || '').trim().toLowerCase();
                const category = (kbCategoryFilter?.value || '').trim().toLowerCase();
                const usage = (kbUsageFilter?.value || '').trim();
                const rows = Array.from(document.querySelectorAll('#knowledge-tbody tr[data-id]'));
                let visibleCount = 0;

                rows.forEach(tr => {
                    const text = tr.textContent.toLowerCase();
                    const rowCategory = (tr.dataset.category || 'general').toLowerCase();
                    const rowUsage = Number.parseInt(tr.dataset.usage || '0', 10) || 0;

                    const matchesSearch = !q || text.includes(q);
                    const matchesCategory = !category || rowCategory === category;
                    const matchesUsage = !usage || (usage === 'used' ? rowUsage > 0 : rowUsage === 0);
                    const shouldShow = matchesSearch && matchesCategory && matchesUsage;

                    tr.style.display = shouldShow ? '' : 'none';
                    if (shouldShow) {
                        visibleCount += 1;
                    }
                });

                const hasActiveFilter = Boolean(q || category || usage);
                if (kbCount && rows.length > 0) {
                    kbCount.textContent = hasActiveFilter ? `${visibleCount} shown` : `${rows.length} items`;
                }

                if (kbFilterEmpty) {
                    kbFilterEmpty.classList.toggle('d-none', rows.length === 0 || visibleCount > 0);
                }
            }

            if (kbSearchBtn) {
                kbSearchBtn.addEventListener('click', applyKnowledgeFilters);
            }

            if (kbSearch) {
                kbSearch.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        applyKnowledgeFilters();
                    }
                });
            }

            [kbCategoryFilter, kbUsageFilter].forEach(control => {
                if (control) {
                    control.addEventListener('change', applyKnowledgeFilters);
                }
            });

            if (kbFilterReset) {
                kbFilterReset.addEventListener('click', function () {
                    if (kbSearch) kbSearch.value = '';
                    if (kbCategoryFilter) kbCategoryFilter.value = '';
                    if (kbUsageFilter) kbUsageFilter.value = '';
                    applyKnowledgeFilters();
                });
            }
        })();
    </script>
</body>
</html>

