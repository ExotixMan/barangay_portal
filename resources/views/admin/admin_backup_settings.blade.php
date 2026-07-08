<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Backup Settings - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        .backup-stat-icon,
        .restore-icon,
        .schedule-icon,
        .archive-icon,
        .empty-icon,
        .modal-warning-icon,
        .restore-upload-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .title-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
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

        .backup-page {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .backup-alert {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: opacity .25s ease, transform .25s ease;
        }

        .backup-alert.fade:not(.show) {
            opacity: 0;
            transform: translateY(-6px);
        }

        .hero-panel,
        .schedule-panel,
        .restore-panel,
        .backup-stat,
        .archive-panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xs);
        }

        .backup-hero {
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
        .schedule-panel p,
        .panel-header p,
        .archive-header p,
        .empty-state p {
            color: var(--muted);
            line-height: 1.65;
        }

        .hero-panel p {
            max-width: 720px;
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
        .restore-dropzone:focus-within {
            outline: 3px solid rgba(211, 47, 47, .16);
            outline-offset: 2px;
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

        .btn-light-soft {
            color: var(--text);
            background: var(--card);
            border: 1px solid var(--line-strong);
            box-shadow: var(--shadow-xs);
        }

        .btn-light-soft:hover {
            color: var(--primary-dark);
            background: #fffafa;
            border-color: rgba(211, 47, 47, .22);
        }

        .btn-warning {
            color: #7c2d12;
            background: var(--warning-soft);
            border-color: #fed7aa;
        }

        .btn-warning:hover {
            color: #7c2d12;
            background: #ffedd5;
            border-color: #fdba74;
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

        .backup-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
            margin-bottom: .9rem;
        }

        .backup-stat {
            display: flex;
            gap: .85rem;
            padding: 1rem;
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .backup-stat:hover,
        .archive-row:hover,
        .restore-panel:hover,
        .archive-panel:hover {
            border-color: var(--line-strong);
            box-shadow: var(--shadow-sm);
        }

        .backup-stat:hover,
        .archive-row:hover {
            transform: translateY(-1px);
        }

        .backup-stat-icon {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 13px;
            font-size: 1rem;
        }

        .backup-stat span {
            display: block;
            color: var(--muted);
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .backup-stat strong {
            display: block;
            margin-top: .28rem;
            color: var(--text);
            font-size: clamp(1.3rem, 2vw, 1.75rem);
            font-weight: 850;
            line-height: 1.1;
            letter-spacing: -.045em;
            overflow-wrap: anywhere;
        }

        .backup-stat small {
            display: block;
            margin-top: .42rem;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 600;
            line-height: 1.45;
        }

        .schedule-panel {
            display: flex;
            align-items: center;
            gap: .9rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
        }

        .schedule-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--info);
            background: var(--info-soft);
            border: 1px solid #dbeafe;
            border-radius: 14px;
        }

        .schedule-panel h3 {
            margin: 0 0 .2rem;
            color: var(--text);
            font-size: 1rem;
            font-weight: 820;
        }

        .schedule-panel p {
            margin: 0;
            font-size: .88rem;
        }

        .schedule-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            width: fit-content;
            padding: .42rem .65rem;
            border-radius: 999px;
            color: #075985;
            background: var(--info-soft);
            border: 1px solid #bae6fd;
            font-size: .76rem;
            font-weight: 800;
        }

        .backup-workspace {
            display: grid;
            grid-template-columns: minmax(300px, 385px) minmax(0, 1fr);
            gap: 1rem;
            align-items: start;
        }

        .restore-panel {
            position: sticky;
            top: 92px;
            overflow: hidden;
        }

        .panel-header,
        .archive-header {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: 1rem;
            border-bottom: 1px solid var(--line);
            background: var(--card);
        }

        .archive-header {
            justify-content: space-between;
        }

        .restore-icon {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 14px;
        }

        .panel-header h3,
        .archive-header h3 {
            margin: .18rem 0 0;
            color: var(--text);
            font-size: 1.02rem;
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .panel-header p,
        .archive-header p {
            margin: .25rem 0 0;
            font-size: .86rem;
        }

        .restore-body {
            padding: 1rem;
        }

        .restore-warning {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            margin-bottom: .9rem;
            padding: .8rem;
            color: #7c2d12;
            background: var(--warning-soft);
            border: 1px solid #fed7aa;
            border-radius: 14px;
            font-size: .84rem;
            line-height: 1.55;
        }

        .restore-dropzone {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 176px;
            padding: 1rem;
            color: var(--muted);
            background: #f9fafb;
            border: 1.5px dashed var(--line-strong);
            border-radius: var(--radius-lg);
            cursor: pointer;
            text-align: center;
            transition: border-color .16s ease, background .16s ease, transform .16s ease;
        }

        .restore-dropzone:hover,
        .restore-dropzone.is-dragover {
            background: var(--primary-soft);
            border-color: rgba(211, 47, 47, .45);
            transform: translateY(-1px);
        }

        .restore-dropzone input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .restore-upload-icon {
            width: 50px;
            height: 50px;
            margin-bottom: .75rem;
            color: var(--primary);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 15px;
            box-shadow: var(--shadow-xs);
            font-size: 1.15rem;
        }

        .restore-dropzone strong,
        .restore-dropzone span,
        .restore-dropzone small {
            display: block;
        }

        .restore-dropzone strong {
            color: var(--text);
            font-size: .94rem;
            font-weight: 820;
        }

        .restore-dropzone span {
            display: flex;
            margin-top: .22rem;
            font-size: .84rem;
            line-height: 1.45;
            justify-content: center;
            align-items: center;
        }

        .restore-dropzone small {
            margin-top: .45rem;
            color: var(--muted-2);
            font-size: .75rem;
            font-weight: 700;
        }

        #restoreValidationMessage {
            min-height: 22px;
            margin-top: .65rem;
            font-weight: 760;
        }

        .restore-submit-btn {
            width: 100%;
            margin-top: .65rem;
        }

        .backup-checklist {
            display: grid;
            gap: .58rem;
            margin-top: .95rem;
            padding-top: .95rem;
            border-top: 1px dashed var(--line-strong);
        }

        .backup-checklist div {
            display: flex;
            align-items: flex-start;
            gap: .55rem;
            color: var(--muted);
            font-size: .82rem;
            line-height: 1.45;
        }

        .backup-checklist i {
            margin-top: .14rem;
            color: var(--success);
        }

        .archive-panel {
            overflow: hidden;
        }

        .archive-count {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            flex: 0 0 auto;
            padding: .42rem .68rem;
            color: var(--primary-dark);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .archive-list {
            display: grid;
            gap: .72rem;
            padding: .9rem;
        }

        .archive-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            grid-template-areas:
                "file actions"
                "meta actions"
                "size actions";
            gap: .48rem 1rem;
            align-items: center;
            padding: .95rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
        }

        .archive-file {
            grid-area: file;
            display: flex;
            align-items: center;
            gap: .72rem;
            min-width: 0;
        }

        .archive-icon {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid rgba(211, 47, 47, .12);
            border-radius: 13px;
        }

        .archive-file strong,
        .archive-file small,
        .archive-meta span,
        .archive-meta strong {
            display: block;
        }

        .archive-file strong {
            max-width: 100%;
            color: var(--text);
            font-size: .9rem;
            font-weight: 820;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .archive-file small {
            margin-top: .12rem;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
        }

        .archive-meta {
            grid-area: meta;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .22rem .45rem;
            min-width: 0;
            padding-left: 54px;
        }

        .archive-meta strong {
            color: var(--text);
            font-size: .82rem;
            font-weight: 760;
            line-height: 1.35;
        }

        .archive-meta span {
            margin-top: 0;
            color: var(--muted);
            font-size: .76rem;
            font-weight: 650;
            line-height: 1.35;
        }

        .archive-size-cell {
            grid-area: size;
            padding-left: 54px;
        }

        .archive-size-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            padding: .38rem .62rem;
            color: #344054;
            background: #f9fafb;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 820;
            white-space: nowrap;
        }

        .archive-actions {
            grid-area: actions;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .42rem;
            white-space: nowrap;
        }

        .archive-actions .btn {
            min-height: 36px;
            padding: .5rem .68rem;
            font-size: .82rem;
        }

        .empty-state {
            display: grid;
            place-items: center;
            min-height: 300px;
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
            margin: 0;
            font-size: .88rem;
        }

        .modal-content {
            border: 0;
            border-radius: var(--radius-xl);
            box-shadow: 0 24px 70px rgba(16, 24, 40, .18);
        }

        .modal-warning-icon {
            flex: 0 0 auto;
            width: 52px;
            height: 52px;
            color: #9a3412;
            background: var(--warning-soft);
            border: 1px solid #fed7aa;
            border-radius: 16px;
            font-size: 1.2rem;
        }

        .modal-title {
            font-weight: 850;
            letter-spacing: -.025em;
        }

        .modal-body p {
            color: var(--muted);
            line-height: 1.65;
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
            .backup-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .backup-workspace {
                grid-template-columns: minmax(0, 1fr);
            }

            .restore-panel {
                position: relative;
                top: auto;
            }
        }

        @media (max-width: 991px) {
            .archive-row {
                grid-template-columns: minmax(0, 1fr);
                grid-template-areas:
                    "file"
                    "meta"
                    "size"
                    "actions";
                align-items: stretch;
            }

            .archive-meta,
            .archive-size-cell {
                padding-left: 54px;
            }

            .archive-actions {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                justify-content: stretch;
            }

            .archive-actions .btn,
            .archive-actions form {
                width: 100%;
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

            .backup-page {
                max-width: 100%;
            }

            .hero-panel,
            .schedule-panel,
            .restore-panel,
            .backup-stat,
            .archive-panel {
                border-radius: 16px;
            }

            .hero-panel,
            .schedule-panel,
            .panel-header,
            .restore-body,
            .archive-header,
            .archive-list {
                padding: .9rem;
            }

            .hero-actions,
            .hero-actions form,
            .hero-actions .btn {
                width: 100%;
            }

            .backup-stats-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .backup-stat {
                min-height: auto;
            }

            .schedule-panel {
                align-items: flex-start;
            }

            .archive-header {
                flex-direction: column;
            }

            .archive-meta,
            .archive-size-cell {
                padding-left: 0;
            }

            .archive-actions {
                grid-template-columns: minmax(0, 1fr);
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

            .restore-dropzone {
                min-height: 158px;
            }
        }
        /* ==========================================================
           Mobile responsiveness hardening
           Add this after the existing responsive rules so it overrides
           older desktop spacing without changing Laravel logic.
        ========================================================== */
        .main-content,
        .header,
        .backup-page,
        .backup-workspace,
        .archive-panel,
        .restore-panel {
            min-width: 0;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 0;
            margin: 0;
            line-height: 1.2;
        }

        .page-title .title-icon {
            flex: 0 0 auto;
        }

        .btn {
            white-space: normal;
        }

        .archive-file,
        .archive-meta,
        .archive-actions {
            min-width: 0;
        }

        .archive-actions form {
            margin: 0;
        }

        .schedule-panel h3 {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .45rem;
        }

        @media (max-width: 991.98px) {
            .main-content {
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
            }

            .header {
                position: sticky;
                top: 0;
                z-index: 1020;
                min-height: 64px;
                background: rgba(248, 250, 252, .96);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--line);
            }

            .header > .d-flex {
                min-width: 0;
                flex: 1 1 auto;
            }

            .archive-actions {
                gap: .5rem;
            }

            .archive-actions .btn {
                min-width: 0;
            }
        }

        @media (max-width: 767.98px) {
            html {
                font-size: 65%;
            }

            body {
                width: 100%;
                min-width: 0;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .85rem !important;
            }

            .header {
                padding: .75rem .85rem !important;
            }

            .page-title {
                flex: 1 1 auto;
                font-size: 1rem;
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .title-icon,
            .mobile-menu-btn {
                width: 38px;
                height: 38px;
                border-radius: 11px;
            }

            .backup-alert {
                margin-bottom: .75rem;
                padding: .85rem 1rem;
                border-radius: 14px;
                font-size: .88rem;
            }

            .hero-panel {
                padding: 1rem;
            }

            .hero-icon {
                width: 42px;
                height: 42px;
                margin-bottom: .7rem;
            }

            .hero-panel h2 {
                font-size: clamp(1.55rem, 8vw, 2rem);
                line-height: 1.08;
                letter-spacing: -.04em;
            }

            .hero-panel p {
                font-size: .9rem;
                line-height: 1.55;
            }

            .hero-actions {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: .55rem;
            }

            .hero-actions form,
            .hero-actions .btn {
                width: 100%;
            }

            .backup-stats-grid {
                gap: .65rem;
                margin-bottom: .75rem;
            }

            .backup-stat {
                align-items: flex-start;
                gap: .7rem;
                padding: .85rem;
            }

            .backup-stat-icon,
            .schedule-icon,
            .restore-icon,
            .archive-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            .backup-stat strong {
                font-size: 1.35rem !important;
                overflow-wrap: anywhere;
            }

            .schedule-panel {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr);
                align-items: flex-start;
                gap: .7rem;
                padding: .85rem;
            }

            .schedule-panel h3 {
                display: grid;
                gap: .4rem;
                font-size: .95rem;
                line-height: 1.35;
            }

            .schedule-chip {
                max-width: 100%;
                white-space: normal;
                line-height: 1.35;
            }

            .backup-workspace {
                gap: .85rem;
            }

            .panel-header,
            .archive-header {
                gap: .7rem;
            }

            .panel-header h3,
            .archive-header h3 {
                font-size: .98rem;
            }

            .panel-header p,
            .archive-header p,
            .schedule-panel p {
                font-size: .82rem;
                line-height: 1.5;
            }

            .restore-warning {
                padding: .75rem;
                font-size: .82rem;
            }

            .restore-dropzone {
                min-height: 150px;
                padding: .9rem;
            }

            .restore-upload-icon {
                width: 44px;
                height: 44px;
                margin-bottom: .6rem;
            }

            .archive-header {
                align-items: stretch;
            }

            .archive-count {
                width: 100%;
                justify-content: center;
            }

            .archive-list {
                gap: .6rem;
                padding: .75rem;
            }

            .archive-row {
                padding: .85rem;
                gap: .55rem;
            }

            .archive-file {
                align-items: flex-start;
            }

            .archive-file strong {
                display: -webkit-box;
                white-space: normal;
                overflow: hidden;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                line-height: 1.35;
            }

            .archive-file small,
            .archive-meta span,
            .archive-meta strong {
                font-size: .74rem;
            }

            .archive-meta {
                display: grid;
                gap: .18rem;
            }

            .archive-size-cell {
                display: flex;
            }

            .archive-size-pill {
                max-width: 100%;
            }

            .archive-actions {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                width: 100%;
                gap: .45rem;
                margin-top: .2rem;
            }

            .archive-actions .btn,
            .archive-actions form,
            .archive-actions button {
                width: 100%;
            }

            .archive-actions .btn {
                min-height: 40px;
                justify-content: center;
            }

            .modal-dialog {
                margin: .85rem;
            }

            .modal-body {
                padding: 1rem !important;
            }

            .modal-body > .d-flex {
                gap: .75rem !important;
            }

            .modal-warning-icon {
                width: 44px;
                height: 44px;
                border-radius: 14px;
            }

            .modal-footer {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: .55rem;
                padding: 0 1rem 1rem !important;
            }

            .modal-footer .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 420px) {
            html {
                font-size: 62.5%;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .65rem !important;
            }

            .header {
                padding: .65rem !important;
            }

            .page-title {
                font-size: .94rem;
                gap: .55rem;
            }

            .hero-kicker,
            .section-kicker {
                font-size: .66rem;
                letter-spacing: .07em;
            }

            .hero-panel,
            .schedule-panel,
            .restore-panel,
            .backup-stat,
            .archive-panel {
                border-radius: 14px;
            }

            .hero-panel,
            .schedule-panel,
            .panel-header,
            .restore-body,
            .archive-header,
            .archive-list {
                padding: .72rem;
            }

            .backup-stat {
                padding: .72rem;
            }

            .btn {
                min-height: 40px;
                padding: .58rem .75rem;
                font-size: .84rem;
            }

            .archive-row {
                padding: .72rem;
            }
        }


    

/* ==========================================================
           POLISHED MOBILE UX PATCH - Backup Settings
           Desktop remains unchanged. Phones get cleaner stacked panels.
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
            .backup-stat-icon,
            .restore-icon,
            .schedule-icon,
            .archive-icon {
                width: 38px !important;
                height: 38px !important;
                border-radius: 12px !important;
            }

            main.p-3,
            main.p-lg-4 {
                padding: .78rem !important;
            }

            .backup-page {
                max-width: 100%;
            }

            .hero-panel,
            .schedule-panel,
            .restore-panel,
            .backup-stat,
            .archive-panel {
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

            .hero-actions form,
            .hero-actions .btn {
                width: 100%;
            }

            .backup-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: .65rem !important;
                margin-bottom: .85rem !important;
            }

            .backup-stat {
                padding: .85rem !important;
                gap: .65rem !important;
            }

            .backup-stat span {
                font-size: .66rem !important;
            }

            .backup-stat strong {
                font-size: 1.18rem !important;
                line-height: 1.15 !important;
            }

            .backup-stat small {
                display: none !important;
            }

            .schedule-panel {
                align-items: flex-start !important;
                padding: .95rem !important;
                gap: .75rem !important;
            }

            .schedule-panel h3 {
                display: grid;
                gap: .45rem;
                font-size: .95rem !important;
                line-height: 1.35 !important;
            }

            .schedule-chip {
                max-width: 100%;
                white-space: normal;
                line-height: 1.3;
            }

            .schedule-panel p {
                font-size: .82rem !important;
                line-height: 1.5 !important;
            }

            .backup-workspace {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .85rem !important;
            }

            .restore-panel {
                position: relative !important;
                top: auto !important;
            }

            .panel-header,
            .archive-header {
                padding: .95rem !important;
                gap: .65rem !important;
            }

            .archive-header {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .archive-count {
                justify-content: center;
                width: fit-content;
            }

            .restore-body,
            .archive-list {
                padding: .85rem !important;
            }

            .restore-warning {
                padding: .75rem !important;
                font-size: .8rem !important;
            }

            .restore-dropzone {
                min-height: 150px !important;
                padding: .9rem !important;
                border-radius: 16px !important;
            }

            .restore-upload-icon {
                width: 44px !important;
                height: 44px !important;
            }

            .backup-checklist {
                gap: .5rem !important;
            }

            .archive-row {
                display: grid !important;
                grid-template-columns: 1fr !important;
                grid-template-areas:
                    "file"
                    "meta"
                    "size"
                    "actions" !important;
                gap: .72rem !important;
                padding: .95rem !important;
                border-radius: 18px !important;
            }

            .archive-file {
                align-items: flex-start !important;
            }

            .archive-file strong {
                font-size: .92rem !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                line-height: 1.3;
                word-break: break-word;
            }

            .archive-meta,
            .archive-size-cell {
                padding-left: 0 !important;
            }

            .archive-meta {
                display: grid !important;
                gap: .25rem !important;
                padding: .65rem .75rem !important;
                background: #f9fafb;
                border: 1px solid var(--line);
                border-radius: 12px;
            }

            .archive-meta::before {
                content: "Modified";
                color: var(--muted);
                font-size: .68rem;
                font-weight: 850;
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            .archive-size-cell {
                display: flex;
                justify-content: flex-start;
            }

            .archive-actions {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: .55rem !important;
                justify-content: stretch !important;
                padding-top: .72rem;
                border-top: 1px dashed var(--line-strong);
            }

            .archive-actions form,
            .archive-actions .btn {
                width: 100%;
            }

            .archive-actions .btn {
                min-height: 40px;
                padding: .55rem .45rem !important;
                font-size: .78rem;
            }

            .modal-dialog {
                margin: .65rem !important;
            }

            .modal-content {
                border-radius: 18px !important;
            }

            .modal-body {
                padding: 1rem !important;
            }

            .modal-body .d-flex {
                gap: .75rem !important;
            }

            .modal-footer {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: .55rem !important;
                padding: .8rem 1rem 1rem !important;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 389.98px) {
            .backup-stats-grid {
                grid-template-columns: 1fr !important;
            }

            .archive-actions {
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
        <a href="{{ route('admin.chatbot.index') }}" onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
        @endadmin_can

        @admin_can('view_backup')
        <a href="{{ route('admin.backup.index') }}"  class="active" onclick="handleLinkClick(event, this)">
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
                    <span class="title-icon"><i class="fas fa-database"></i></span>
                    Backup Settings
                </h1>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-3 p-lg-4">
            @php
                $latestBackup = $backups->isNotEmpty() ? $backups->first() : null;
                $totalSizeMb = $backups->sum('size') / 1024 / 1024;
                $displayTz = $displayTimezone ?? 'Asia/Manila';
            @endphp

            <div class="backup-page">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('restore_status') && session('restore_message'))
                    <div id="restoreResultPanel" class="alert {{ session('restore_status') === 'success' ? 'alert-success' : 'alert-danger' }} backup-alert border-0 mb-4" role="alert">
                        <div class="d-flex flex-column gap-1">
                            <div class="fw-bold">
                                <i class="fas {{ session('restore_status') === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' }} me-2"></i>
                                Restore Result
                            </div>
                            <div>{{ session('restore_message') }}</div>
                            <small class="opacity-75">Time: {{ session('restore_time') }}</small>
                        </div>
                    </div>
                @endif

                <section class="backup-hero">
                    <div class="hero-panel">
                        <div class="hero-content">
                            <div class="hero-icon"><i class="fas fa-shield-halved"></i></div>
                            <div class="hero-kicker"><i class="fas fa-circle-check"></i> Backup Control Center</div>
                            <h2>Protect your barangay records.</h2>
                            <p>Create full archive backups, restore verified ZIP files, and manage existing backup copies in a cleaner workspace designed for quick admin decisions.</p>
                            <div class="hero-actions">
                                <form method="POST" action="{{ route('admin.backup.store') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i>Create New Backup
                                    </button>
                                </form>
                                <a href="#backupArchives" class="btn btn-light-soft">
                                    <i class="fas fa-folder-open"></i>View Archives
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="backup-stats-grid" aria-label="Backup overview">
                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-box-archive"></i></div>
                        <div>
                            <span>Total Backups</span>
                            <strong>{{ $backups->count() }}</strong>
                            <small>Available archive files in storage.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <span>Backup Type</span>
                            <strong style="font-size:1.25rem;">Full Archive</strong>
                            <small>Database, uploads, and managed documents.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-hard-drive"></i></div>
                        <div>
                            <span>Total Storage</span>
                            <strong style="font-size:1.35rem;">{{ number_format($totalSizeMb, 2) }} MB</strong>
                            <small>Combined size of listed backup files.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-clock-rotate-left"></i></div>
                        <div>
                            <span>Latest Backup</span>
                            <strong style="font-size:1.05rem;">
                                {{ $latestBackup ? $latestBackup['modified_relative'] : 'No backup yet' }}
                            </strong>
                            <small>{{ $latestBackup ? $latestBackup['modified_at'] : 'Create your first full archive.' }}</small>
                        </div>
                    </div>
                </section>

                <div class="schedule-panel">
                    <div class="schedule-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h3>Automatic weekly backup <span class="schedule-chip"><i class="fas fa-calendar-check"></i>Every Sunday, 12:00 AM</span></h3>
                        <p>Your system is scheduled to create a backup using {{ $displayTz }} timezone.</p>
                    </div>
                </div>

                <section class="backup-workspace">
                    <aside class="restore-panel" id="restoreBackup">
                        <div class="panel-header">
                            <div class="restore-icon"><i class="fas fa-rotate-left"></i></div>
                            <div>
                                <div class="section-kicker">Restore</div>
                                <h3>Restore backup archive</h3>
                                <p>Upload a trusted backup ZIP file before restoring database and backup-managed files.</p>
                            </div>
                        </div>

                        <div class="restore-body">
                            <div class="restore-warning">
                                <i class="fas fa-triangle-exclamation mt-1"></i>
                                <div>This action overwrites current database data and backup-managed files. Use only a verified backup file.</div>
                            </div>

                            <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data" onsubmit="return validateUploadAndConfirmRestore(event, this);">
                                @csrf
                                <label class="restore-dropzone" id="restoreDropzone" for="restoreArchiveInput">
                                    <input type="file" name="backup_archive" id="restoreArchiveInput" accept=".zip" required>
                                    <span class="restore-upload-icon"><i class="fas fa-file-zipper"></i></span>
                                    <strong id="restoreFileLabel">Choose backup ZIP file</strong>
                                    <span>Click to browse or drag a .zip archive here.</span>
                                    <small>Accepted: .zip only, maximum 500 MB</small>
                                </label>
                                <div id="restoreValidationMessage" class="form-text"></div>
                                <button type="submit" class="btn btn-warning restore-submit-btn">
                                    <i class="fas fa-upload"></i>Upload and Restore
                                </button>
                            </form>

                            <div class="backup-checklist">
                                <div><i class="fas fa-check-circle"></i><span>Confirm the file came from this system.</span></div>
                                <div><i class="fas fa-check-circle"></i><span>Download the latest backup before restoring older data.</span></div>
                                <div><i class="fas fa-check-circle"></i><span>Restore during low system activity when possible.</span></div>
                            </div>
                        </div>
                    </aside>

                    <section class="archive-panel" id="backupArchives">
                        <div class="archive-header">
                            <div>
                                <div class="section-kicker"><i class="fas fa-folder-open"></i> Archives</div>
                                <h3>Available backups</h3>
                                <p>Review file age, size, and actions before downloading, restoring, or deleting an archive.</p>
                            </div>
                            <span class="archive-count"><i class="fas fa-box-archive"></i>{{ $backups->count() }} files</span>
                        </div>

                        @if($backups->isEmpty())
                            <div class="empty-state">
                                <div>
                                    <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                                    <h4>No backups found yet</h4>
                                    <p>Create a new backup to protect your database, uploads, and generated documents.</p>
                                </div>
                            </div>
                        @else
                            <div class="archive-list">
                                @foreach($backups as $backup)
                                    <div class="archive-row">
                                        <div class="archive-file">
                                            <div class="archive-icon"><i class="fas fa-file-zipper"></i></div>
                                            <div class="min-w-0">
                                                <strong title="{{ $backup['name'] }}">{{ $backup['name'] }}</strong>
                                                <small>Full system archive</small>
                                            </div>
                                        </div>

                                        <div class="archive-meta">
                                            <strong>{{ $backup['modified_at'] }}</strong>
                                            <span>{{ $backup['modified_relative'] }} · {{ $displayTz }}</span>
                                        </div>

                                        <div class="archive-size-cell">
                                            <span class="archive-size-pill">{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</span>
                                        </div>

                                        <div class="archive-actions">
                                            <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm btn-outline-primary" title="Download backup">
                                                <i class="fas fa-download"></i><span>Download</span>
                                            </a>

                                            <form method="POST" action="{{ route('admin.backup.restore') }}" onsubmit="return confirmRestore(event, this);">
                                                @csrf
                                                <input type="hidden" name="file" value="{{ $backup['name'] }}">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Restore backup">
                                                    <i class="fas fa-rotate-left"></i><span>Restore</span>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" onsubmit="return confirmDelete(event, this, 'Delete this backup file? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete backup">
                                                    <i class="fas fa-trash"></i><span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </section>
            </div>
        </main>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="modal-warning-icon"><i class="fas fa-triangle-exclamation"></i></div>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="confirmActionTitle">Confirm action</h5>
                            <p class="mt-2 mb-0" id="confirmActionMessage">Are you sure you want to continue?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionButton">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}"></script>
    <script>
        var pendingActionForm = null;
        var confirmModalInstance = null;
        var isSubmittingConfirmedAction = false;

        function showConfirmModal(form, title, message, confirmLabel) {
            pendingActionForm = form;

            var titleEl = document.getElementById('confirmActionTitle');
            var messageEl = document.getElementById('confirmActionMessage');
            var confirmBtn = document.getElementById('confirmActionButton');
            var modalEl = document.getElementById('confirmActionModal');

            if (!modalEl || !confirmBtn) {
                return window.confirm(message || 'Are you sure you want to continue?');
            }

            if (titleEl) titleEl.textContent = title || 'Confirm action';
            if (messageEl) messageEl.textContent = message || 'Are you sure you want to continue?';
            if (confirmBtn) confirmBtn.textContent = confirmLabel || 'Continue';

            confirmModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            confirmModalInstance.show();
            return false;
        }

        function confirmDelete(event, form, message) {
            if (isSubmittingConfirmedAction) {
                return true;
            }

            event.preventDefault();
            return showConfirmModal(
                form,
                'Delete backup file?',
                message || 'Delete this backup file? This cannot be undone.',
                'Delete Backup'
            );
        }

        function confirmRestore(event, form) {
            if (isSubmittingConfirmedAction) {
                return true;
            }

            event.preventDefault();
            return showConfirmModal(
                form,
                'Restore backup archive?',
                'This will overwrite the current database and backup-managed files with the selected archive. Continue only if the backup file is verified.',
                'Restore Backup'
            );
        }

        function validateUploadAndConfirmRestore(event, form) {
            var fileInput = form.querySelector('input[name="backup_archive"]');
            var validationMessage = document.getElementById('restoreValidationMessage');

            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Please select a backup zip file first.';
                }
                return false;
            }

            var file = fileInput.files[0];
            var maxBytes = 500 * 1024 * 1024;
            var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
            var isZipByName = /\.zip$/i.test(file.name || '');

            if (!isZipByType && !isZipByName) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                }
                return false;
            }

            if (file.size > maxBytes) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                }
                return false;
            }

            if (validationMessage) {
                validationMessage.className = 'form-text text-success';
                validationMessage.textContent = 'File is valid and ready to restore.';
            }

            return confirmRestore(event, form);
        }

        function updateRestoreFileState(fileInput) {
            var validationMessage = document.getElementById('restoreValidationMessage');
            var fileLabel = document.getElementById('restoreFileLabel');

            if (!fileInput || !validationMessage) {
                return;
            }

            if (!fileInput.files || fileInput.files.length === 0) {
                validationMessage.className = 'form-text';
                validationMessage.textContent = '';
                if (fileLabel) fileLabel.textContent = 'Choose backup ZIP file';
                return;
            }

            var file = fileInput.files[0];
            var maxBytes = 500 * 1024 * 1024;
            var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
            var isZipByName = /\.zip$/i.test(file.name || '');

            if (fileLabel) {
                fileLabel.textContent = file.name || 'Selected backup file';
            }

            if (!isZipByType && !isZipByName) {
                validationMessage.className = 'form-text text-danger';
                validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                return;
            }

            if (file.size > maxBytes) {
                validationMessage.className = 'form-text text-danger';
                validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                return;
            }

            validationMessage.className = 'form-text text-success';
            validationMessage.textContent = 'File is valid and ready to restore.';
        }

        document.addEventListener('DOMContentLoaded', function () {
            var fileInput = document.getElementById('restoreArchiveInput');
            var restoreDropzone = document.getElementById('restoreDropzone');
            var restoreResultPanel = document.getElementById('restoreResultPanel');
            var confirmBtn = document.getElementById('confirmActionButton');

            if (restoreResultPanel) {
                restoreResultPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    updateRestoreFileState(fileInput);
                });
            }

            if (restoreDropzone && fileInput) {
                ['dragenter', 'dragover'].forEach(function (eventName) {
                    restoreDropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        restoreDropzone.classList.add('is-dragover');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    restoreDropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        restoreDropzone.classList.remove('is-dragover');
                    });
                });

                restoreDropzone.addEventListener('drop', function (event) {
                    if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                        fileInput.files = event.dataTransfer.files;
                        updateRestoreFileState(fileInput);
                    }
                });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    if (!pendingActionForm) {
                        return;
                    }

                    isSubmittingConfirmedAction = true;
                    if (confirmModalInstance) {
                        confirmModalInstance.hide();
                    }
                    pendingActionForm.submit();
                });
            }

            setTimeout(function () {
                document.querySelectorAll('.backup-alert.alert-dismissible').forEach(function (alertEl) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
                    bsAlert.close();
                });
            }, 6000);
        });
    </script>
</body>
</html>
