@extends('admin.layouts.admin')

@section('title', 'Project Management')
@section('page_title', 'Project Management')
@section('page_icon', 'fas fa-project-diagram')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/projects.css') }}">

    <style>
        /* Project admin UX refresh — presentation only */
        .projects-page {
            --prj-primary: #4338ca;
            --prj-primary-soft: #eef2ff;
            --prj-border: #e5e7eb;
            --prj-border-strong: #d8dee8;
            --prj-text: #172033;
            --prj-muted: #667085;
            --prj-soft: #f8fafc;
            --prj-success: #15803d;
            --prj-warning: #a16207;
            --prj-danger: #b42318;
        }

        /* CRUD pages should prioritize work, not dashboard decoration */
        .projects-page .projects-hero,
        .projects-page .projects-stats-row {
            display: none !important;
        }

        .projects-page .projects-panel {
            overflow: visible;
            border: 1px solid var(--prj-border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }

        .projects-page .projects-filter-panel {
            overflow: visible;
            margin-bottom: 18px;
        }

        .projects-page .projects-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--prj-border);
            background: #fff;
            border-radius: 16px 16px 0 0;
        }

        .projects-page .projects-panel-title {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-width: 0;
        }

        .projects-page .projects-panel-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
            background: var(--prj-primary-soft);
            color: var(--prj-primary);
        }

        .projects-page .projects-panel-title h3 {
            margin: 0;
            color: var(--prj-text);
            font-size: .98rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .projects-page .section-kicker,
        .projects-page .panel-copy { display: none; }

        .projects-page .projects-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .projects-page .projects-filter-body { padding: 14px 18px 16px; }

        .projects-page .projects-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(300px, 1.8fr) minmax(150px, .65fr) minmax(150px, .65fr) auto;
            gap: 10px;
            align-items: center;
        }

        .projects-page .projects-search-group {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .projects-page .projects-search-field {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .projects-page .projects-search-field > i {
            position: absolute;
            left: 13px;
            top: 50%;
            z-index: 2;
            transform: translateY(-50%);
            color: #98a2b3;
            pointer-events: none;
        }

        .projects-page .projects-search-field .form-control { padding-left: 38px; }

        .projects-page .form-control,
        .projects-page .form-select {
            min-height: 42px;
            border-color: var(--prj-border-strong);
            border-radius: 10px;
            color: var(--prj-text);
            font-size: .86rem;
            box-shadow: none;
        }

        .projects-page .form-control:focus,
        .projects-page .form-select:focus {
            border-color: var(--prj-primary);
            box-shadow: 0 0 0 .2rem rgba(67, 56, 202, .09);
        }

        .projects-page .btn {
            min-height: 40px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
        }

        .projects-page .btn i { margin-right: .38rem; }
        .projects-page .icon-only i { margin-right: 0; }

        .projects-page .projects-bulk-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--prj-border);
        }

        .projects-page .selected-count {
            color: var(--prj-muted);
            font-size: .78rem;
            font-weight: 700;
        }

        .projects-page table { margin: 0; }

        .projects-page .table thead th {
            padding-top: 11px;
            padding-bottom: 11px;
            border-bottom: 1px solid var(--prj-border);
            background: #f8fafc;
            color: #475467;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .projects-page .table tbody td {
            padding-top: 12px;
            padding-bottom: 12px;
            border-color: #eef1f5;
            color: var(--prj-text);
            font-size: .83rem;
            vertical-align: middle;
        }

        .projects-page .table tbody tr:hover { background: #fafbff; }

        .projects-page .project-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border: 1px solid var(--prj-border);
            border-radius: 10px;
            background: var(--prj-soft);
        }

        .projects-page .project-title-text {
            max-width: 330px;
            overflow: hidden;
            color: var(--prj-text);
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 750;
        }

        .projects-page .project-mobile-meta {
            display: none;
            margin-top: 4px;
            color: var(--prj-muted);
            font-size: .7rem;
            line-height: 1.45;
        }

        .projects-page .project-mobile-meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-right: 8px;
        }

        .projects-page .project-progress-wrap {
            min-width: 150px;
        }

        .projects-page .project-progress-track {
            height: 7px;
            overflow: hidden;
            border-radius: 999px;
            background: #eef2f6;
        }

        .projects-page .project-progress-track .progress-bar {
            min-width: 0;
            border-radius: 999px;
            font-size: 0;
        }

        .projects-page .project-progress-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 5px;
            color: var(--prj-muted);
            font-size: .7rem;
            font-weight: 700;
        }

        .projects-page .row-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .projects-page .action-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-height: 34px;
            padding: 0 10px;
        }

        .projects-page .action-more-btn {
            width: 34px;
            min-height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .projects-page .action-dropdown .dropdown-menu {
            width: 250px;
            padding: 7px;
            border: 1px solid var(--prj-border);
            border-radius: 12px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, .14);
            z-index: 1090;
        }

        .projects-page .action-dropdown .dropdown-item,
        .projects-page .action-dropdown form .dropdown-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 9px 10px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #344054;
            text-align: left;
            white-space: normal;
        }

        .projects-page .action-dropdown .dropdown-item:hover { background: #f8fafc; }

        .projects-page .action-menu-icon {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 8px;
            background: #f2f4f7;
        }

        .projects-page .action-menu-copy strong {
            display: block;
            color: inherit;
            font-size: .77rem;
            line-height: 1.25;
        }

        .projects-page .action-menu-copy small {
            display: block;
            margin-top: 2px;
            color: #667085;
            font-size: .67rem;
            line-height: 1.35;
        }

        .projects-page .action-edit { color: #1d4ed8 !important; }
        .projects-page .action-edit .action-menu-icon { background: #eff6ff; color: #2563eb; }
        .projects-page .action-restore { color: #166534 !important; background: #f0fdf4 !important; }
        .projects-page .action-restore .action-menu-icon { background: #dcfce7; color: #15803d; }
        .projects-page .action-archive { color: #b42318 !important; }
        .projects-page .action-archive .action-menu-icon { background: #fff1f2; color: #e11d48; }

        /* Modal system */
        .projects-page .modal { --bs-modal-zindex: 1060; }
        .projects-page .modal-dialog { max-width: 900px; }
        .projects-page .modal-dialog.modal-xl { max-width: 980px; }

        .projects-page .modal-content {
            overflow: hidden;
            border: 1px solid rgba(208, 213, 221, .85);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 22px 60px rgba(15, 23, 42, .18);
        }

        .projects-page .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--prj-border);
            background: #fff;
        }

        .projects-page .modal-heading {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-width: 0;
        }

        .projects-page .modal-heading-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 11px;
            background: var(--prj-primary-soft);
            color: var(--prj-primary);
        }

        .projects-page .modal-heading-copy { min-width: 0; }

        .projects-page .modal-title {
            margin: 0;
            color: var(--prj-text);
            font-size: 1rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .projects-page .modal-subtitle {
            display: block;
            margin-top: 3px;
            color: var(--prj-muted);
            font-size: .76rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .projects-page .modal-header .btn-close {
            width: 32px;
            height: 32px;
            padding: 0;
            margin: 0;
            flex: 0 0 auto;
            border-radius: 9px;
            background-size: 12px;
            opacity: .65;
        }

        .projects-page .modal-header .btn-close:hover {
            background-color: #f2f4f7;
            opacity: 1;
        }

        .projects-page .modal-body {
            padding: 18px 20px;
            background: #f8fafc;
        }

        .projects-page .modal-form-intro {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            color: var(--prj-muted);
            font-size: .76rem;
        }

        .projects-page .form-step-card,
        .projects-page .detail-section-card {
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid var(--prj-border);
            border-radius: 14px;
            background: #fff;
        }

        .projects-page .form-step-card:last-child,
        .projects-page .detail-section-card:last-child { margin-bottom: 0; }

        .projects-page .form-section-heading {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 14px;
        }

        .projects-page .section-step {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: var(--prj-primary-soft);
            color: var(--prj-primary);
            font-size: .76rem;
            font-weight: 900;
        }

        .projects-page .form-section-heading h6,
        .projects-page .detail-section-title {
            margin: 0;
            color: var(--prj-text);
            font-size: .87rem;
            font-weight: 850;
        }

        .projects-page .form-section-heading p {
            margin: 2px 0 0;
            color: var(--prj-muted);
            font-size: .72rem;
            line-height: 1.45;
        }

        .projects-page .modal .form-label {
            margin-bottom: 6px;
            color: #344054;
            font-size: .76rem;
            font-weight: 800;
        }

        .projects-page .modal .form-label > i {
            width: 15px;
            margin-right: 5px;
            color: #98a2b3;
            font-size: .74rem;
            text-align: center;
        }

        .projects-page .modal .form-control,
        .projects-page .modal .form-select {
            min-height: 44px;
            border-radius: 10px;
            background-color: #fff;
        }

        .projects-page .modal textarea.form-control {
            min-height: auto;
            line-height: 1.55;
            resize: vertical;
        }

        .projects-page .field-hint,
        .projects-page .modal small.text-muted {
            display: block;
            margin-top: 5px;
            color: var(--prj-muted) !important;
            font-size: .7rem;
            line-height: 1.45;
        }

        .projects-page .timeline-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .projects-page .status-progress-grid {
            display: grid;
            grid-template-columns: minmax(220px, .75fr) minmax(320px, 1.25fr);
            gap: 14px;
            align-items: stretch;
        }

        .projects-page .progress-control-card {
            height: 100%;
            padding: 12px 14px;
            border: 1px solid var(--prj-border);
            border-radius: 12px;
            background: #fbfcfe;
        }

        .projects-page .progress-control-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .projects-page .progress-value-pill {
            min-width: 48px;
            padding: 5px 9px;
            border-radius: 999px;
            background: var(--prj-primary-soft);
            color: var(--prj-primary);
            font-size: .73rem;
            font-weight: 850;
            text-align: center;
        }

        .projects-page .form-range { margin: 6px 0 0; }

        .projects-page .progress-scale {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            color: var(--prj-muted);
            font-size: .65rem;
            font-weight: 700;
        }

        .projects-page .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 20px;
            border-top: 1px solid var(--prj-border);
            background: #fff;
        }

        .projects-page .modal-footer .btn { min-width: 112px; }

        /* View project */
        .projects-page .project-view-hero {
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid var(--prj-border);
            border-radius: 14px;
            background: #fff;
        }

        .projects-page .project-view-title {
            margin: 0;
            overflow-wrap: anywhere;
            color: var(--prj-text);
            font-size: 1.12rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .projects-page .project-view-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .projects-page .view-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .projects-page .view-summary-item {
            padding: 11px 12px;
            border: 1px solid var(--prj-border);
            border-radius: 11px;
            background: #fff;
        }

        .projects-page .view-summary-label {
            display: block;
            margin-bottom: 3px;
            color: var(--prj-muted);
            font-size: .67rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .projects-page .view-summary-value {
            display: block;
            overflow-wrap: anywhere;
            color: var(--prj-text);
            font-size: .82rem;
            font-weight: 800;
            line-height: 1.35;
        }

        .projects-page .view-progress-card {
            padding: 13px;
            margin-bottom: 14px;
            border: 1px solid var(--prj-border);
            border-radius: 12px;
            background: #fff;
        }

        .projects-page .view-progress-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
            color: #344054;
            font-size: .76rem;
            font-weight: 800;
        }

        .projects-page .view-progress-card .progress {
            height: 8px !important;
            border-radius: 999px;
            background: #eef2f6;
        }

        .projects-page .view-progress-card .progress-bar {
            border-radius: 999px;
            font-size: 0;
        }

        .projects-page .project-description-box {
            padding: 14px;
            border: 1px solid var(--prj-border);
            border-radius: 11px;
            background: #fff;
            color: #344054;
            font-size: .84rem;
            line-height: 1.65;
            overflow-wrap: anywhere;
        }

        /* Semantic action colors */
        .projects-page .btn-success {
            border-color: #15803d;
            background: #15803d;
        }
        .projects-page .btn-success:hover {
            border-color: #166534;
            background: #166534;
        }
        .projects-page .btn-outline-success {
            border-color: #86efac;
            background: #f0fdf4;
            color: #166534;
        }
        .projects-page .btn-outline-success:hover {
            border-color: #16a34a;
            background: #dcfce7;
            color: #14532d;
        }
        .projects-page .btn-outline-danger {
            border-color: #fda4af;
            background: #fff;
            color: #b42318;
        }
        .projects-page .btn-outline-danger:hover {
            border-color: #e11d48;
            background: #fff1f2;
            color: #9f1239;
        }

        @media (max-width: 1199.98px) {
            .projects-page .projects-filter-toolbar {
                grid-template-columns: minmax(250px, 1.4fr) repeat(2, minmax(135px, .7fr));
            }
            .projects-page .projects-filter-toolbar .filter-submit-btn { grid-column: 3; }
            .projects-page .view-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 767.98px) {
            .projects-page { padding-bottom: 14px; }

            .projects-page .projects-panel { border-radius: 13px; }
            .projects-page .projects-panel-header {
                align-items: flex-start;
                padding: 13px 14px;
            }
            .projects-page .projects-panel-icon { display: none; }
            .projects-page .projects-panel-title h3 { font-size: .9rem; }
            .projects-page .projects-count-pill { font-size: .7rem; }

            .projects-page .projects-filter-body { padding: 12px 14px 14px; }
            .projects-page .projects-filter-toolbar {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 9px;
            }
            .projects-page .projects-search-group { width: 100%; }
            .projects-page .search-submit-btn { flex: 0 0 auto; }
            .projects-page .projects-filter-toolbar > .form-select,
            .projects-page .projects-filter-toolbar > .btn { width: 100%; }

            .projects-page .projects-bulk-row {
                align-items: flex-start;
                flex-direction: column;
            }
            .projects-page .projects-bulk-row .btn { width: 100%; }

            /* Compact mobile project table: selection + project summary + actions */
            .projects-page #projectsTable th:nth-child(3),
            .projects-page #projectsTable td:nth-child(3),
            .projects-page #projectsTable th:nth-child(4),
            .projects-page #projectsTable td:nth-child(4),
            .projects-page #projectsTable th:nth-child(5),
            .projects-page #projectsTable td:nth-child(5),
            .projects-page #projectsTable th:nth-child(6),
            .projects-page #projectsTable td:nth-child(6),
            .projects-page #projectsTable th:nth-child(7),
            .projects-page #projectsTable td:nth-child(7) {
                display: none !important;
            }

            .projects-page #projectsTable th:first-child,
            .projects-page #projectsTable td:first-child {
                width: 38px;
                padding-left: 12px !important;
            }
            .projects-page #projectsTable th:nth-child(2),
            .projects-page #projectsTable td:nth-child(2) { padding-left: 0 !important; }
            .projects-page #projectsTable th:last-child,
            .projects-page #projectsTable td:last-child {
                width: 76px;
                padding-right: 12px !important;
            }

            .projects-page .project-title-text { max-width: min(53vw, 280px); }
            .projects-page .project-mobile-meta { display: block; }
            .projects-page .action-view-btn .action-label { display: none; }
            .projects-page .action-view-btn { width: 34px; padding: 0; }
            .projects-page .action-view-btn i { margin: 0; }

            .projects-page .modal-dialog {
                width: calc(100% - 16px);
                max-width: none;
                margin: 8px auto;
                min-height: calc(100% - 16px);
            }

            .projects-page .modal-content {
                max-height: calc(100vh - 16px);
                border-radius: 14px;
            }

            .projects-page .modal-header { padding: 14px; gap: 10px; }
            .projects-page .modal-heading-icon { width: 36px; height: 36px; border-radius: 10px; }
            .projects-page .modal-title { font-size: .93rem; }
            .projects-page .modal-subtitle { font-size: .69rem; }
            .projects-page .modal-body { padding: 12px; }
            .projects-page .form-step-card,
            .projects-page .detail-section-card { padding: 13px; margin-bottom: 10px; border-radius: 12px; }
            .projects-page .form-section-heading { margin-bottom: 11px; }
            .projects-page .section-step { width: 29px; height: 29px; }

            .projects-page .timeline-grid,
            .projects-page .status-progress-grid { grid-template-columns: 1fr; }
            .projects-page .progress-control-card { height: auto; }
            .projects-page .view-summary { grid-template-columns: 1fr 1fr; gap: 8px; }

            .projects-page .modal-footer {
                padding: 11px 12px;
                gap: 7px;
            }
            .projects-page .modal-footer .btn {
                min-width: 0;
                flex: 1;
            }

            .projects-page .action-dropdown .dropdown-menu {
                width: min(250px, calc(100vw - 24px));
            }
        }

        @media (max-width: 420px) {
            .projects-page .projects-panel-header { gap: 8px; }
            .projects-page .projects-panel-actions { width: auto; }
            .projects-page .projects-panel-actions .btn {
                min-height: 36px;
                padding: .4rem .55rem;
            }
            .projects-page .projects-search-group { flex-direction: column; }
            .projects-page .projects-search-group .btn { width: 100%; }
            .projects-page .project-icon { width: 34px; height: 34px; }
            .projects-page .project-title-text { max-width: 49vw; font-size: .78rem; }
            .projects-page .project-mobile-meta { font-size: .65rem; }
            .projects-page .view-summary { grid-template-columns: 1fr; }
            .projects-page .modal-heading-icon { display: none; }
            .projects-page .modal-footer { flex-direction: column-reverse; }
            .projects-page .modal-footer .btn { width: 100%; flex: none; }
        }

        html[data-theme="dark"] .projects-page,
        body.dark-mode .projects-page {
            --prj-border: #2b3443;
            --prj-border-strong: #3a4658;
            --prj-text: #e5e7eb;
            --prj-muted: #9ca3af;
            --prj-soft: #111827;
        }

        html[data-theme="dark"] .projects-page .projects-panel,
        html[data-theme="dark"] .projects-page .projects-panel-header,
        html[data-theme="dark"] .projects-page .modal-content,
        html[data-theme="dark"] .projects-page .modal-header,
        html[data-theme="dark"] .projects-page .modal-footer,
        html[data-theme="dark"] .projects-page .form-step-card,
        html[data-theme="dark"] .projects-page .detail-section-card,
        html[data-theme="dark"] .projects-page .project-view-hero,
        html[data-theme="dark"] .projects-page .view-summary-item,
        html[data-theme="dark"] .projects-page .view-progress-card,
        html[data-theme="dark"] .projects-page .project-description-box,
        body.dark-mode .projects-page .projects-panel,
        body.dark-mode .projects-page .projects-panel-header,
        body.dark-mode .projects-page .modal-content,
        body.dark-mode .projects-page .modal-header,
        body.dark-mode .projects-page .modal-footer,
        body.dark-mode .projects-page .form-step-card,
        body.dark-mode .projects-page .detail-section-card,
        body.dark-mode .projects-page .project-view-hero,
        body.dark-mode .projects-page .view-summary-item,
        body.dark-mode .projects-page .view-progress-card,
        body.dark-mode .projects-page .project-description-box {
            background: #111827;
        }

        html[data-theme="dark"] .projects-page .modal-body,
        body.dark-mode .projects-page .modal-body { background: #0b1220; }

        html[data-theme="dark"] .projects-page .form-control,
        html[data-theme="dark"] .projects-page .form-select,
        html[data-theme="dark"] .projects-page .progress-control-card,
        body.dark-mode .projects-page .form-control,
        body.dark-mode .projects-page .form-select,
        body.dark-mode .projects-page .progress-control-card {
            border-color: var(--prj-border-strong);
            background: #0f172a;
            color: var(--prj-text);
        }

        html[data-theme="dark"] .projects-page .table thead th,
        body.dark-mode .projects-page .table thead th { background: #0f172a; color: #aab4c3; }

        html[data-theme="dark"] .projects-page .table tbody td,
        body.dark-mode .projects-page .table tbody td { color: var(--prj-text); }
    </style>
@endpush

@section('content')
            <div class="projects-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="projects-hero">
                <div class="projects-hero-content">
                    <div class="min-w-0">
                        <div class="projects-hero-icon"><i class="fas fa-project-diagram"></i></div>
                        <div class="projects-kicker">Project Control Center</div>
                        <h2>Track community projects with a cleaner workflow.</h2>
                        <p>Review project status, filter by progress, update milestones, and archive completed records without cluttering the page.</p>
                    </div>
                    <div class="projects-hero-actions">
                        @admin_can('create_projects')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                            <i class="fas fa-plus"></i>Add Project
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 projects-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-project-diagram me-1"></i>All projects
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Ongoing</div>
                            <div class="stat-number">{{ $ongoing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-hourglass me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-hourglass"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Completed</div>
                            <div class="stat-number">{{ $completed_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>Finished
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Avg Progress</div>
                            <div class="stat-number">{{ $avg_progress ?? 0 }}%</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-chart-line me-1"></i>Overall
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - UX Improved -->
            <div class="projects-panel projects-filter-panel">
                <form method="GET" action="{{ route('admin.projects.index') }}" id="searchForm">
                    <div class="projects-panel-header">
                        <div class="projects-panel-title">
                            <div class="projects-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-hard-hat"></i> Project Directory</div>
                                <h3>Search and filter projects</h3>
                                <p class="panel-copy">Find projects by title, description, or location. Use status and progress filters to narrow the table.</p>
                            </div>
                        </div>

                        <div class="projects-panel-actions">
                            @admin_can('create_projects')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                <i class="fas fa-plus"></i>Add Project
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="projects-filter-body">
                        <div class="projects-filter-toolbar">
                            <div class="projects-search-group">
                                <div class="projects-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search projects">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="progress" class="form-select" aria-label="Filter by progress">
                                <option value="">All progress</option>
                                <option value="0-25" {{ request('progress') == '0-25' ? 'selected' : '' }}>0-25%</option>
                                <option value="26-50" {{ request('progress') == '26-50' ? 'selected' : '' }}>26-50%</option>
                                <option value="51-75" {{ request('progress') == '51-75' ? 'selected' : '' }}>51-75%</option>
                                <option value="76-100" {{ request('progress') == '76-100' ? 'selected' : '' }}>76-100%</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_projects'))
                        <div class="projects-bulk-row">
                            <div class="selected-count" id="selectedProjectsCount">
                                <i class="fas fa-square-check me-1"></i>No projects selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_projects'))
            <form id="bulkForm" method="POST" action="{{ route('admin.projects.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Projects Table - UX Improved -->
            <div class="projects-panel">
                <div class="projects-panel-header">
                    <div class="projects-panel-title">
                        <div class="projects-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Project Records</div>
                            <h3>Projects List</h3>
                            <p class="panel-copy">Sort records, open details, and edit project information without leaving this page.</p>
                        </div>
                    </div>
                    <div class="projects-panel-actions">
                        <span class="projects-count-pill"><i class="fas fa-folder-open"></i>{{ isset($projects) ? $projects->total() : 0 }} projects</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="projectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Location
                                            @if(request('sort') == 'location')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Start Date
                                            @if(request('sort') == 'start_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'expected_completion', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Expected Completion
                                            @if(request('sort') == 'expected_completion')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th width="200">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Progress
                                            @if(request('sort') == 'progress')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($projects as $project)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $project->id }}" class="project-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="project-icon me-2">
                                                <i class="fas fa-hard-hat text-primary"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="project-title-text" title="{{ $project->title }}">{{ Str::limit($project->title, 42) }}</div>
                                                <div class="project-mobile-meta">
                                                    <span><i class="fas fa-location-dot"></i>{{ Str::limit($project->location ?? 'No location', 26) }}</span>
                                                    <span>
                                                        @if($project->deleted_at)
                                                            <i class="fas fa-box-archive"></i>Archived
                                                        @elseif($project->status == 'ongoing')
                                                            <i class="fas fa-hourglass-half"></i>Ongoing · {{ $project->progress }}%
                                                        @else
                                                            <i class="fas fa-circle-check"></i>Completed · {{ $project->progress }}%
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="location-badge">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($project->location, 20) ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->start_date 
                                                ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->expected_completion 
                                                ? \Carbon\Carbon::parse($project->expected_completion)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </td>
                                    <td class="progress-cell">
                                        <div class="project-progress-wrap">
                                            <div class="progress project-progress-track">
                                                <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                    role="progressbar"
                                                    style="width: {{ $project->progress }}%;"
                                                    aria-valuenow="{{ $project->progress }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <div class="project-progress-label">
                                                <span>{{ $project->status == 'completed' ? 'Completed' : 'Progress' }}</span>
                                                <strong>{{ $project->progress }}%</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="row-actions" aria-label="Actions for {{ $project->title }}">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $project->id }}"
                                                    title="View project details">
                                                <i class="fas fa-eye"></i><span class="action-label">View</span>
                                            </button>

                                            <div class="dropdown action-dropdown">
                                                <button type="button"
                                                        class="btn btn-sm btn-light-soft action-more-btn icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @if(!$project->deleted_at)
                                                        @admin_can('update_projects')
                                                        <button type="button"
                                                                class="dropdown-item action-edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editProjectModal{{ $project->id }}">
                                                            <span class="action-menu-icon"><i class="fas fa-pen"></i></span>
                                                            <span class="action-menu-copy">
                                                                <strong>Edit project</strong>
                                                                <small>Update project details and progress</small>
                                                            </span>
                                                        </button>
                                                        @endadmin_can
                                                    @endif

                                                    @admin_can('delete_projects')
                                                        @if($project->deleted_at)
                                                        <form method="POST" action="{{ route('admin.projects.restore', $project->id) }}" onsubmit="return confirmDelete(event, 'Restore this project?')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item action-restore">
                                                                <span class="action-menu-icon"><i class="fas fa-rotate-left"></i></span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Restore project</strong>
                                                                    <small>Return this project to the active list</small>
                                                                </span>
                                                            </button>
                                                        </form>
                                                        @else
                                                        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" onsubmit="return confirmDelete(event, 'Archive this project?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item action-archive">
                                                                <span class="action-menu-icon"><i class="fas fa-box-archive"></i></span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Archive project</strong>
                                                                    <small>Remove it from the active project list</small>
                                                                </span>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    @endadmin_can
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div>
                                                <div class="empty-state-icon"><i class="fas fa-project-diagram"></i></div>
                                                <h4>No projects found</h4>
                                                <p>No project records match your current search and filters.</p>
                                                @admin_can('create_projects')
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                                    <i class="fas fa-plus"></i>Add Project
                                                </button>
                                                @endadmin_can
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination - Mobile Responsive -->
                    @if(isset($projects) && $projects->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $projects->firstItem() ?? 0 }}-{{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $projects->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Project Modal -->
            @admin_can('create_projects')
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-hard-hat"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Project</h5>
                                    <span class="modal-subtitle">Create a project record in three clear sections. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.projects.store') }}" id="addProjectForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="modal-form-intro">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Keep the project title and description clear so administrators can recognize the work quickly.</span>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Project Information</h6>
                                            <p>Describe the project and its intended work.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Project Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="e.g. Drainage Rehabilitation Phase 1" required>
                                            <span class="field-hint">Use a short, specific project name that is easy to identify.</span>
                                            @if(session('form_type') == 'add')
                                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif"
                                                      name="description" rows="5" placeholder="Write project description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                            <span class="field-hint">Include the scope, expected outcome, and important details.</span>
                                            @if(session('form_type') == 'add')
                                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Timeline &amp; Location</h6>
                                            <p>Set the project schedule and where the work will take place.</p>
                                        </div>
                                    </div>
                                    <div class="timeline-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-day"></i>Start Date</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('start_date') is-invalid @enderror @endif"
                                                   name="start_date" value="{{ session('form_type') == 'add' ? old('start_date') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-check"></i>Expected Completion</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('expected_completion') is-invalid @enderror @endif"
                                                   name="expected_completion" value="{{ session('form_type') == 'add' ? old('expected_completion') : '' }}">
                                            <span class="field-hint">Must not be earlier than the start date.</span>
                                            @if(session('form_type') == 'add')
                                                @error('expected_completion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label"><i class="fas fa-location-dot"></i>Location</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif"
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="e.g. Zone 3, Hulong Duhat">
                                        @if(session('form_type') == 'add')
                                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        @endif
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Status &amp; Progress</h6>
                                            <p>Keep project status consistent with its completion percentage.</p>
                                        </div>
                                    </div>
                                    <div class="status-progress-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-circle-half-stroke"></i>Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" name="status" required>
                                                <option value="">Select status</option>
                                                <option value="ongoing" {{ (session('form_type') == 'add' && old('status') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (session('form_type') == 'add' && old('status') == 'completed') ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <span class="field-hint">Completed is valid only when progress reaches 100%.</span>
                                            @if(session('form_type') == 'add')
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="progress-control-card">
                                            <div class="progress-control-head">
                                                <label class="form-label mb-0"><i class="fas fa-chart-line"></i>Progress <span class="text-danger">*</span></label>
                                                <span class="progress-value-pill" id="addProgressValue">{{ old('progress', 0) }}%</span>
                                            </div>
                                            <input type="range" class="form-range" id="addProgressRange" min="0" max="100" step="1" value="{{ old('progress', 0) }}">
                                            <div class="progress-scale"><span>0%</span><span>50%</span><span>100%</span></div>
                                            <input type="hidden" name="progress" id="addProgressHidden" value="{{ old('progress', 0) }}">
                                            @if(session('form_type') == 'add')
                                                @error('progress')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Cancel</button>
                            <button type="submit" form="addProjectForm" class="btn btn-success" id="submitAddForm"><i class="fas fa-save"></i>Save Project</button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Project Modals -->
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Project</h5>
                                        <span class="modal-subtitle">Update {{ $project->title }} while keeping its dates, status, and progress consistent.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" id="editProjectForm{{ $project->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $project->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Project Information</h6>
                                                <p>Update the project name and description.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Project Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $project->id ? old('title', $project->title) : $project->title }}" required>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('description') is-invalid @enderror @endif"
                                                          name="description" rows="5" required>{{ session('form_type') == 'edit_' . $project->id ? old('description', $project->description) : $project->description }}</textarea>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">2</div>
                                            <div>
                                                <h6>Timeline &amp; Location</h6>
                                                <p>Adjust dates and location without changing the existing workflow.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-day"></i>Start Date</label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('start_date') is-invalid @enderror @endif"
                                                       name="start_date" value="{{ session('form_type') == 'edit_' . $project->id ? old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') : ($project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-check"></i>Expected Completion</label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('expected_completion') is-invalid @enderror @endif"
                                                       name="expected_completion" value="{{ session('form_type') == 'edit_' . $project->id ? old('expected_completion', $project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') : ($project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') }}">
                                                <span class="field-hint">Must not be earlier than the start date.</span>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('expected_completion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label"><i class="fas fa-location-dot"></i>Location</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('location') is-invalid @enderror @endif"
                                                   name="location" value="{{ session('form_type') == 'edit_' . $project->id ? old('location', $project->location) : $project->location }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Status &amp; Progress</h6>
                                                <p>Progress automatically keeps the status aligned with completion.</p>
                                            </div>
                                        </div>
                                        <div class="status-progress-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-circle-half-stroke"></i>Status <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $project->id) @error('status') is-invalid @enderror @endif"
                                                        name="status" id="edit_status_{{ $project->id }}" required>
                                                    <option value="">Select status</option>
                                                    <option value="ongoing" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                    <option value="completed" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                <span class="field-hint">Completed is valid only when progress reaches 100%.</span>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div class="progress-control-card">
                                                <div class="progress-control-head">
                                                    <label class="form-label mb-0"><i class="fas fa-chart-line"></i>Progress <span class="text-danger">*</span></label>
                                                    <span class="progress-value-pill" id="editProgressValue{{ $project->id }}">{{ $project->progress }}%</span>
                                                </div>
                                                <input type="range" class="form-range" id="editProgressRange{{ $project->id }}" min="0" max="100" step="1" value="{{ $project->progress }}">
                                                <div class="progress-scale"><span>0%</span><span>50%</span><span>100%</span></div>
                                                <input type="hidden" name="progress" id="editProgressHidden{{ $project->id }}" value="{{ $project->progress }}">
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('progress')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Cancel</button>
                                <button type="submit" form="editProjectForm{{ $project->id }}" class="btn btn-primary" id="submitEditForm{{ $project->id }}"><i class="fas fa-save"></i>Update Project</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Project Modals -->
            @foreach($projects as $project)
            <div class="modal fade" id="viewModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-hard-hat"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Project Details</h5>
                                    <span class="modal-subtitle">Review project status, timeline, progress, location, and description.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="project-view-hero">
                                <h4 class="project-view-title">{{ $project->title }}</h4>
                                <div class="project-view-meta">
                                    @if($project->deleted_at)
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-box-archive me-1"></i>Archived</span>
                                    @elseif($project->status == 'ongoing')
                                        <span class="badge bg-warning-subtle text-warning"><i class="fas fa-hourglass-half me-1"></i>Ongoing</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success"><i class="fas fa-circle-check me-1"></i>Completed</span>
                                    @endif
                                    @if($project->is_overdue)
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-triangle-exclamation me-1"></i>Overdue</span>
                                    @endif
                                </div>
                            </div>

                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Location</span>
                                    <span class="view-summary-value">{{ $project->location ?: 'Not specified' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Start Date</span>
                                    <span class="view-summary-value">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Expected Completion</span>
                                    <span class="view-summary-value">{{ $project->expected_completion ? $project->expected_completion->format('M d, Y') : 'Not set' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Last Updated</span>
                                    <span class="view-summary-value">{{ $project->updated_at ? $project->updated_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="view-progress-card">
                                <div class="view-progress-head">
                                    <span><i class="fas fa-chart-line me-1"></i>Project Progress</span>
                                    <strong>{{ $project->progress }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                         role="progressbar"
                                         style="width: {{ $project->progress }}%;"
                                         aria-valuenow="{{ $project->progress }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2"></i>Project Description</div>
                                <div class="project-description-box">{!! nl2br(e($project->description)) !!}</div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-clock-rotate-left me-2"></i>Record Information</div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <span class="view-summary-label">Created At</span>
                                        <span class="view-summary-value">{{ $project->created_at ? $project->created_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="view-summary-label">Last Updated</span>
                                        <span class="view-summary-value">{{ $project->updated_at ? $project->updated_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addProjectModal'));
                    addModal.show();
                });
            </script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editProjectModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
            </div>
@endsection

@push('scripts')
<script>
        // Bulk Archive function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.project-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');
            const idsContainer = document.getElementById('bulkIdsContainer');

            if (!bulkForm || !idsContainer) {
                alert('Bulk Archive form is unavailable. Please refresh the page.');
                return;
            }

            if (checkboxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one project to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one project to archive.');
                }
                return;
            }

            // Clear previous ids to avoid duplicate submissions.
            idsContainer.innerHTML = '';
            checkboxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                idsContainer.appendChild(input);
            });

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirm Bulk Archive',
                    text: `Are you sure you want to archive ${checkboxes.length} selected project(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Archive'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkForm.submit();
                    }
                });
            } else {
                if (confirm(`Are you sure you want to archive ${checkboxes.length} project(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.project-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof updateSelectedProjectsCount === 'function') {
                updateSelectedProjectsCount();
            }
        }

        // Confirm delete with SweetAlert
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            if (typeof Swal !== 'undefined') {
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
            } else {
                if (confirm(message || 'Are you sure?')) {
                    form.submit();
                }
            }
            
            return false;
        }

        // Update progress range display for add modal
        document.getElementById('addProgressRange')?.addEventListener('input', function() {
            document.getElementById('addProgressValue').textContent = this.value + '%';
            document.getElementById('addProgressHidden').value = this.value;
            
            // Auto-select status based on progress
            const statusSelect = document.querySelector('select[name="status"]');
            if (this.value == 100) {
                statusSelect.value = 'completed';
            } else {
                statusSelect.value = 'ongoing';
            }
        });

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.project-checkbox');
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

            // Initialize progress bars with gradient
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                if (!bar.classList.contains('bg-success')) {
                    bar.style.background = 'var(--primary-gradient)';
                }
            });

            // Setup edit modal progress listeners
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                (function(projectId) {
                    const range = document.getElementById('editProgressRange' + projectId);
                    const value = document.getElementById('editProgressValue' + projectId);
                    const hidden = document.getElementById('editProgressHidden' + projectId);
                    const statusSelect = document.getElementById('edit_status_' + projectId);
                    
                    if (range && value && hidden && statusSelect) {
                        range.addEventListener('input', function() {
                            value.textContent = this.value + '%';
                            hidden.value = this.value;
                            
                            // Auto-select status based on progress
                            if (this.value == 100) {
                                statusSelect.value = 'completed';
                            } else {
                                statusSelect.value = 'ongoing';
                            }
                        });
                    }
                })('{{ $project->id }}');
                @endforeach
            @endif
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.insertAdjacentElement('afterend', fb); }
            fb.textContent = msg;
            fb.style.display = 'block';
        }
        function cf(el) {
            if (!el) return;
            el.classList.remove('is-invalid');
            const fb = el.parentElement.querySelector('.invalid-feedback');
            if (fb) {
                fb.textContent = '';
                fb.style.display = 'none';
            }
        }

        function attachProjectValidation(form) {
            if (!form) return;
            const title              = form.querySelector('[name="title"]');
            const description        = form.querySelector('[name="description"]');
            const status             = form.querySelector('[name="status"]');
            const startDate          = form.querySelector('[name="start_date"]');
            const expectedCompletion = form.querySelector('[name="expected_completion"]');
            const progressHidden     = form.querySelector('input[type="hidden"][name="progress"]');

            function hasFourDigitYear(dateValue) {
                return /^\d{4}-\d{2}-\d{2}$/.test(dateValue);
            }

            function getProgressValue() {
                const raw = progressHidden ? progressHidden.value : '0';
                const parsed = parseInt(raw, 10);
                return Number.isNaN(parsed) ? 0 : parsed;
            }

            function validateStatusVsProgress() {
                if (!status) return true;
                const progress = getProgressValue();
                if (status.value === 'completed' && progress !== 100) {
                    sf(status, 'Status can only be Completed when progress is 100%.');
                    return false;
                }
                if (status.value) {
                    cf(status);
                }
                return true;
            }

            function checkDateFieldFormat(field) {
                if (!field || !field.value) return true;
                if (!hasFourDigitYear(field.value)) {
                    sf(field, 'Date must use a 4-digit year (YYYY-MM-DD).');
                    return false;
                }
                return true;
            }

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (description) {
                description.addEventListener('input', function() { cf(this); }, {once: true});
                description.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Description is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (status) status.addEventListener('change', function() {
                if (!this.value) {
                    sf(this, 'Please select a status.');
                } else {
                    validateStatusVsProgress();
                }
            });

            if (progressHidden) {
                progressHidden.addEventListener('input', validateStatusVsProgress);
                progressHidden.addEventListener('change', validateStatusVsProgress);
            }

            if (startDate) {
                startDate.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion) {
                expectedCompletion.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion && startDate) {
                const checkDates = function() {
                    if (!checkDateFieldFormat(startDate) || !checkDateFieldFormat(expectedCompletion)) {
                        return;
                    }
                    if (expectedCompletion.value && startDate.value && expectedCompletion.value < startDate.value) {
                        sf(expectedCompletion, 'Completion date must not be before the start date.');
                    } else cf(expectedCompletion);
                };
                expectedCompletion.addEventListener('change', checkDates);
                startDate.addEventListener('change', checkDates);
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                    else { cf(title); }
                }
                if (description) {
                    if (!description.value.trim()) { sf(description, 'Description is required.'); valid = false; }
                    else if (description.value.trim().length < 10) { sf(description, 'Description must be at least 10 characters.'); valid = false; }
                    else { cf(description); }
                }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                else if (status && !validateStatusVsProgress()) { valid = false; }
                else if (status) { cf(status); }

                if (startDate && !checkDateFieldFormat(startDate)) {
                    valid = false;
                }
                if (expectedCompletion && !checkDateFieldFormat(expectedCompletion)) {
                    valid = false;
                }

                if (startDate && expectedCompletion && startDate.value && expectedCompletion.value && expectedCompletion.value < startDate.value) {
                    sf(expectedCompletion, 'Completion date must not be before the start date.'); valid = false;
                }
                else if (expectedCompletion) {
                    cf(expectedCompletion);
                }
                if (!valid) e.preventDefault();
            });
        }

        attachProjectValidation(document.getElementById('addProjectForm'));
        document.querySelectorAll('[id^="editProjectForm"]').forEach(attachProjectValidation);
    });

// UX helper: keep the selected counter updated for bulk actions.
        function updateSelectedProjectsCount() {
            const selected = document.querySelectorAll('.project-checkbox:checked').length;
            const counter = document.getElementById('selectedProjectsCount');
            if (!counter) return;
            counter.innerHTML = selected > 0
                ? `<i class="fas fa-square-check me-1"></i>${selected} project${selected === 1 ? '' : 's'} selected`
                : '<i class="fas fa-square-check me-1"></i>No projects selected';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.project-checkbox, #selectAll').forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelectedProjectsCount);
            });
            updateSelectedProjectsCount();

            document.querySelectorAll('.modal-body .row.g-3').forEach((row) => {
                if (!row.closest('.modal-body')) return;
                row.classList.add('modal-form-grid');
            });

            document.querySelectorAll('.modal-body textarea[name="description"]').forEach((textarea) => {
                if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList?.contains('field-hint')) {
                    textarea.insertAdjacentHTML('afterend', '<span class="field-hint">Use short, resident-friendly wording. Include scope, location, and expected outcome when possible.</span>');
                }
            });
        });
    </script>

@endpush
