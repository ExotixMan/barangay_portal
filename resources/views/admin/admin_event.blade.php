@extends('admin.layouts.admin')

@section('title', 'Event Management')
@section('page_title', 'Event Management')
@section('page_icon', 'fas fa-calendar')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/events.css') }}">

    <style>
        /* Event admin UX refresh — presentation only */
        .events-page {
            --evt-primary: #4338ca;
            --evt-primary-soft: #eef2ff;
            --evt-border: #e5e7eb;
            --evt-border-strong: #d8dee8;
            --evt-text: #172033;
            --evt-muted: #667085;
            --evt-soft: #f8fafc;
            --evt-success: #15803d;
            --evt-warning: #a16207;
            --evt-danger: #b42318;
        }

        /* Remove duplicated dashboard-like content from a CRUD workspace */
        .events-page .events-hero,
        .events-page .events-stats-row {
            display: none !important;
        }

        .events-page .events-panel {
            overflow: hidden;
            border: 1px solid var(--evt-border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }

        .events-page .events-filter-panel { margin-bottom: 18px; }

        .events-page .events-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--evt-border);
            background: #fff;
        }

        .events-page .events-panel-title {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-width: 0;
        }

        .events-page .events-panel-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
            background: var(--evt-primary-soft);
            color: var(--evt-primary);
        }

        .events-page .events-panel-title h3 {
            margin: 0;
            color: var(--evt-text);
            font-size: .98rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .events-page .section-kicker,
        .events-page .events-panel-copy { display: none; }

        .events-page .events-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .events-page .events-filter-body { padding: 14px 18px 16px; }

        .events-page .events-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(280px, 1.8fr) minmax(150px, .65fr) minmax(150px, .65fr) auto;
            gap: 10px;
            align-items: center;
        }

        .events-page .events-search-group {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .events-page .events-search-control {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .events-page .events-search-control > i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #98a2b3;
            pointer-events: none;
        }

        .events-page .events-search-control .form-control { padding-left: 38px; }

        .events-page .form-control,
        .events-page .form-select {
            min-height: 42px;
            border-color: var(--evt-border-strong);
            border-radius: 10px;
            color: var(--evt-text);
            font-size: .86rem;
            box-shadow: none;
        }

        .events-page .form-control:focus,
        .events-page .form-select:focus {
            border-color: var(--evt-primary);
            box-shadow: 0 0 0 .2rem rgba(67, 56, 202, .09);
        }

        .events-page .btn {
            min-height: 40px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
        }

        .events-page .btn i { margin-right: .38rem; }
        .events-page .icon-only i,
        .events-page .btn-close i { margin-right: 0; }

        .events-page .events-bulk-row {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--evt-border);
        }

        .events-page .selected-count {
            color: var(--evt-muted);
            font-size: .78rem;
            font-weight: 700;
        }

        .events-page table { margin: 0; }

        .events-page .table thead th {
            padding-top: 11px;
            padding-bottom: 11px;
            border-bottom: 1px solid var(--evt-border);
            background: #f8fafc;
            color: #475467;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .events-page .table tbody td {
            padding-top: 12px;
            padding-bottom: 12px;
            border-color: #eef1f5;
            color: var(--evt-text);
            font-size: .83rem;
            vertical-align: middle;
        }

        .events-page .table tbody tr:hover { background: #fafbff; }

        .events-page .event-thumb {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            overflow: hidden;
            border: 1px solid var(--evt-border);
            border-radius: 10px;
            background: var(--evt-soft);
        }

        .events-page .event-thumb img {
            width: 100% !important;
            height: 100% !important;
            border-radius: 0 !important;
            object-fit: cover;
        }

        .events-page .event-title-text {
            max-width: 320px;
            overflow: hidden;
            color: var(--evt-text);
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .events-page .event-mobile-meta {
            display: none;
            margin-top: 4px;
            color: var(--evt-muted);
            font-size: .7rem;
            line-height: 1.45;
        }

        .events-page .event-mobile-meta span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-right: 8px;
        }

        .events-page .location-text {
            display: inline-block;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        .events-page .attendees-badge,
        .events-page .events-count-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #475467;
            font-size: .75rem;
            font-weight: 700;
        }

        .events-page .table .badge {
            padding: .42rem .58rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 800;
        }

        /* Compact row actions */
        .events-page .action-cluster {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .events-page .action-view-btn {
            min-height: 34px;
            padding: .38rem .65rem;
            border-color: #d0d5dd;
            background: #fff;
            color: #344054;
        }

        .events-page .action-more-toggle {
            width: 34px;
            min-height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-color: #d0d5dd;
            background: #fff;
            color: #475467;
        }

        .events-page .action-more-toggle i { margin: 0; }

        .events-page .action-menu {
            min-width: 238px;
            padding: 7px;
            border: 1px solid var(--evt-border);
            border-radius: 12px;
            box-shadow: 0 16px 38px rgba(16, 24, 40, .14);
        }

        .events-page .action-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            padding: 8px 10px;
            border-radius: 8px;
            color: #344054;
            font-size: .8rem;
            font-weight: 750;
        }

        .events-page .action-menu .dropdown-item:hover { background: #f8fafc; }
        .events-page .action-menu form { margin: 0; }
        .events-page .action-menu .dropdown-item i {
            width: 18px;
            margin: 0;
            text-align: center;
        }
        .events-page .action-menu .action-menu-edit i { color: #2563eb; }
        .events-page .action-menu .action-menu-positive {
            color: #166534;
            background: #f0fdf4;
        }
        .events-page .action-menu .action-menu-positive:hover { background: #dcfce7; }
        .events-page .action-menu .action-menu-positive i { color: #15803d; }
        .events-page .action-menu .action-menu-danger { color: #b42318; }
        .events-page .action-menu .action-menu-danger i { color: #b42318; }
        .events-page .action-menu .action-menu-danger:hover { background: #fff1f2; }

        /* Consistent modal system */
        .events-page .modal-dialog {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .events-page .modal-content {
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .20);
        }

        .events-page .modal-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--evt-border);
            background: #fff;
        }

        .events-page .modal-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .events-page .modal-heading-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 11px;
            background: var(--evt-primary-soft);
            color: var(--evt-primary);
        }

        .events-page .modal-heading-copy { min-width: 0; }

        .events-page .modal-title {
            margin: 0;
            color: var(--evt-text);
            font-size: 1rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .events-page .modal-subtitle {
            display: block;
            margin-top: 3px;
            color: var(--evt-muted);
            font-size: .76rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .events-page .modal-header .btn-close {
            width: 32px;
            height: 32px;
            padding: 0;
            margin: 0;
            flex: 0 0 auto;
            border-radius: 9px;
            background-size: 12px;
            opacity: .65;
        }

        .events-page .modal-header .btn-close:hover {
            background-color: #f2f4f7;
            opacity: 1;
        }

        .events-page .modal-body {
            padding: 18px 20px;
            background: #f8fafc;
        }

        .events-page .modal-form-intro {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            color: var(--evt-muted);
            font-size: .76rem;
        }

        .events-page .form-step-card,
        .events-page .detail-section-card {
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid var(--evt-border);
            border-radius: 14px;
            background: #fff;
        }

        .events-page .form-step-card:last-child,
        .events-page .detail-section-card:last-child { margin-bottom: 0; }

        .events-page .form-section-heading {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 14px;
        }

        .events-page .section-step {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: var(--evt-primary-soft);
            color: var(--evt-primary);
            font-size: .76rem;
            font-weight: 900;
        }

        .events-page .form-section-heading h6,
        .events-page .detail-section-title {
            margin: 0;
            color: var(--evt-text);
            font-size: .87rem;
            font-weight: 850;
        }

        .events-page .form-section-heading p {
            margin: 2px 0 0;
            color: var(--evt-muted);
            font-size: .72rem;
            line-height: 1.45;
        }

        .events-page .modal .form-label {
            margin-bottom: 6px;
            color: #344054;
            font-size: .76rem;
            font-weight: 800;
        }

        .events-page .modal .form-label > i {
            width: 15px;
            margin-right: 5px;
            color: #98a2b3;
            font-size: .74rem;
            text-align: center;
        }

        .events-page .modal .form-control,
        .events-page .modal .form-select {
            min-height: 44px;
            border-radius: 10px;
            background-color: #fff;
        }

        .events-page .modal textarea.form-control {
            min-height: auto;
            line-height: 1.55;
            resize: vertical;
        }

        .events-page .field-hint,
        .events-page .modal small.text-muted {
            display: block;
            margin-top: 5px;
            color: var(--evt-muted) !important;
            font-size: .7rem;
            line-height: 1.45;
        }

        .events-page .schedule-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
        }

        .events-page .details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
        }

        .events-page .upload-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px;
            border: 1px dashed #cfd6e3;
            border-radius: 12px;
            background: #fbfcfe;
        }

        .events-page .upload-card-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
            background: var(--evt-primary-soft);
            color: var(--evt-primary);
        }

        .events-page .image-preview {
            width: 100%;
            max-height: 260px;
            margin-top: 10px;
            object-fit: cover;
            border: 1px solid var(--evt-border);
            border-radius: 12px;
        }

        .events-page .image-preview-button {
            width: 100%;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .events-page .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 20px;
            border-top: 1px solid var(--evt-border);
            background: #fff;
        }

        .events-page .modal-footer .btn { min-width: 112px; }

        /* View modal */
        .events-page .event-view-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 14px;
            border: 1px solid var(--evt-border);
            border-radius: 14px;
            background: #fff;
        }

        .events-page .event-view-hero img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            display: block;
        }

        .events-page .event-view-title-block { padding: 14px 15px; }
        .events-page .event-detail-title {
            margin: 0;
            color: var(--evt-text);
            font-size: 1.12rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .events-page .event-view-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 7px;
        }

        .events-page .view-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .events-page .view-summary-item {
            padding: 11px 12px;
            border: 1px solid var(--evt-border);
            border-radius: 11px;
            background: #fff;
        }

        .events-page .view-summary-label {
            display: block;
            margin-bottom: 3px;
            color: var(--evt-muted);
            font-size: .67rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .events-page .view-summary-value {
            display: block;
            overflow-wrap: anywhere;
            color: var(--evt-text);
            font-size: .82rem;
            font-weight: 800;
            line-height: 1.35;
        }

        .events-page .event-description-box {
            padding: 14px;
            border: 1px solid var(--evt-border);
            border-radius: 11px;
            background: #fff;
            color: #344054;
            font-size: .84rem;
            line-height: 1.65;
        }

        .events-page .image-zoom-modal .modal-body {
            padding: 12px;
            background: #0f172a;
        }

        .events-page .image-zoom-modal #zoomedImage {
            display: block;
            width: auto;
            max-width: 100%;
            max-height: 76vh;
            margin: 0 auto;
            object-fit: contain;
            border-radius: 10px;
        }

        /* Semantic action colors */
        .events-page .btn-success {
            border-color: #15803d;
            background: #15803d;
        }
        .events-page .btn-success:hover {
            border-color: #166534;
            background: #166534;
        }
        .events-page .btn-outline-success {
            border-color: #86efac;
            background: #f0fdf4;
            color: #166534;
        }
        .events-page .btn-outline-success:hover {
            border-color: #16a34a;
            background: #dcfce7;
            color: #14532d;
        }
        .events-page .btn-outline-danger {
            border-color: #fda4af;
            background: #fff;
            color: #b42318;
        }
        .events-page .btn-outline-danger:hover {
            border-color: #e11d48;
            background: #fff1f2;
            color: #9f1239;
        }

        @media (max-width: 1199.98px) {
            .events-page .events-filter-toolbar {
                grid-template-columns: minmax(240px, 1.4fr) repeat(2, minmax(135px, .7fr));
            }
            .events-page .events-filter-toolbar .filter-submit-btn { grid-column: 3; }
        }

        @media (max-width: 767.98px) {
            .events-page { padding-bottom: 14px; }

            .events-page .events-panel { border-radius: 13px; }
            .events-page .events-panel-header {
                align-items: flex-start;
                padding: 13px 14px;
            }
            .events-page .events-panel-icon { display: none; }
            .events-page .events-panel-title h3 { font-size: .9rem; }
            .events-page .events-panel-actions .events-count-pill { font-size: .7rem; }

            .events-page .events-filter-body { padding: 12px 14px 14px; }
            .events-page .events-filter-toolbar { display: flex; flex-direction: column; align-items: stretch; gap: 9px; }
            .events-page .events-search-group { width: 100%; }
            .events-page .events-search-btn { flex: 0 0 auto; }
            .events-page .events-filter-toolbar > .form-select,
            .events-page .events-filter-toolbar > .btn { width: 100%; }

            .events-page .events-bulk-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            /* Keep the event table useful without horizontal clutter */
            .events-page #eventsTable th:nth-child(5),
            .events-page #eventsTable td:nth-child(5),
            .events-page #eventsTable th:nth-child(7),
            .events-page #eventsTable td:nth-child(7) {
                display: none !important;
            }

            .events-page #eventsTable th:first-child,
            .events-page #eventsTable td:first-child { width: 38px; padding-left: 12px !important; }
            .events-page #eventsTable th:nth-child(2),
            .events-page #eventsTable td:nth-child(2) { padding-left: 0 !important; }
            .events-page #eventsTable th:last-child,
            .events-page #eventsTable td:last-child { width: 76px; padding-right: 12px !important; }

            .events-page .event-title-text { max-width: min(46vw, 260px); }
            .events-page .event-mobile-meta { display: block; }
            .events-page .action-view-btn .action-label { display: none; }
            .events-page .action-view-btn { width: 34px; padding: 0; }
            .events-page .action-view-btn i { margin: 0; }

            .events-page .modal-dialog {
                width: calc(100% - 16px);
                max-width: none;
                margin: 8px auto;
                min-height: calc(100% - 16px);
            }

            .events-page .modal-content {
                max-height: calc(100vh - 16px);
                border-radius: 14px;
            }

            .events-page .modal-header { padding: 14px; gap: 10px; }
            .events-page .modal-heading-icon { width: 36px; height: 36px; border-radius: 10px; }
            .events-page .modal-title { font-size: .93rem; }
            .events-page .modal-subtitle { font-size: .69rem; }
            .events-page .modal-body { padding: 12px; }
            .events-page .form-step-card,
            .events-page .detail-section-card { padding: 13px; margin-bottom: 10px; border-radius: 12px; }
            .events-page .form-section-heading { margin-bottom: 11px; }
            .events-page .section-step { width: 29px; height: 29px; }
            .events-page .schedule-grid,
            .events-page .details-grid { grid-template-columns: 1fr; gap: 10px; }
            .events-page .upload-card { flex-direction: column; }
            .events-page .view-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
            .events-page .modal-footer { padding: 11px 12px; }
            .events-page .modal-footer .btn { min-width: 0; flex: 1; }

            .events-page .action-menu {
                min-width: min(230px, calc(100vw - 28px));
                max-width: calc(100vw - 28px);
            }
        }

        @media (max-width: 575.98px) {
            .events-page .events-panel-header { flex-direction: column; gap: 10px; }
            .events-page .events-panel-actions { width: 100%; justify-content: space-between; }
            .events-page .events-panel-actions .btn { flex: 1; }
            .events-page .events-search-group { flex-direction: column; }
            .events-page .events-search-btn { width: 100%; }
            .events-page .events-bulk-row { align-items: stretch; flex-direction: column; }
            .events-page .events-bulk-row .btn { width: 100%; }
            .events-page .event-title-text { max-width: 42vw; }
            .events-page .view-summary { grid-template-columns: 1fr; }
            .events-page .modal-heading-icon { display: none; }
            .events-page .modal-footer { flex-direction: column-reverse; }
            .events-page .modal-footer .btn { width: 100%; }
        }

        /* Dark mode support */
        [data-theme="dark"] .events-page .events-panel,
        [data-theme="dark"] .events-page .events-panel-header,
        [data-theme="dark"] .events-page .modal-content,
        [data-theme="dark"] .events-page .modal-header,
        [data-theme="dark"] .events-page .modal-footer,
        [data-theme="dark"] .events-page .form-step-card,
        [data-theme="dark"] .events-page .detail-section-card,
        [data-theme="dark"] .events-page .view-summary-item,
        [data-theme="dark"] .events-page .event-description-box,
        body.dark-mode .events-page .events-panel,
        body.dark-mode .events-page .events-panel-header,
        body.dark-mode .events-page .modal-content,
        body.dark-mode .events-page .modal-header,
        body.dark-mode .events-page .modal-footer,
        body.dark-mode .events-page .form-step-card,
        body.dark-mode .events-page .detail-section-card,
        body.dark-mode .events-page .view-summary-item,
        body.dark-mode .events-page .event-description-box {
            border-color: #2f3b4f;
            background: #182230;
        }

        [data-theme="dark"] .events-page .modal-body,
        body.dark-mode .events-page .modal-body { background: #111827; }

        [data-theme="dark"] .events-page .events-panel-title h3,
        [data-theme="dark"] .events-page .modal-title,
        [data-theme="dark"] .events-page .form-section-heading h6,
        [data-theme="dark"] .events-page .event-detail-title,
        [data-theme="dark"] .events-page .view-summary-value,
        body.dark-mode .events-page .events-panel-title h3,
        body.dark-mode .events-page .modal-title,
        body.dark-mode .events-page .form-section-heading h6,
        body.dark-mode .events-page .event-detail-title,
        body.dark-mode .events-page .view-summary-value { color: #f8fafc; }

        [data-theme="dark"] .events-page .form-control,
        [data-theme="dark"] .events-page .form-select,
        body.dark-mode .events-page .form-control,
        body.dark-mode .events-page .form-select {
            border-color: #3b475a;
            background-color: #111827;
            color: #f8fafc;
        }
    </style>
@endpush

@section('content')
            <div class="events-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="events-hero">
                <div class="events-hero-content">
                    <div class="min-w-0">
                        <div class="events-hero-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="events-kicker">Event Control Center</div>
                        <h2>Manage community events with a cleaner workflow.</h2>
                        <p>Review upcoming and past activities, filter schedules, manage event images, and archive records without cluttering the page.</p>
                    </div>
                    <div class="events-hero-actions">
                        @admin_can('create_events')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-plus"></i>Add Event
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 events-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-calendar me-1"></i>All events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Upcoming</div>
                            <div class="stat-number">{{ $upcoming_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Future events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Past</div>
                            <div class="stat-number">{{ $past_count ?? 0 }}</div>
                            <small class="text-secondary mt-2 d-none d-sm-block">
                                <i class="fas fa-history me-1"></i>Completed
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Attendees</div>
                            <div class="stat-number">{{ $total_attendees ?? 0 }}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-users me-1"></i>Total participants
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Compact UX -->
            <div class="events-panel events-filter-panel">
                <form method="GET" action="{{ route('admin.events.index') }}" id="searchForm">
                    <div class="events-panel-header">
                        <div class="events-panel-title">
                            <div class="events-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-calendar"></i> Event Directory</div>
                                <h3>Search and filter events</h3>
                                <p class="events-panel-copy">Search by title, description, or location. Use filters only when you need a narrower list.</p>
                            </div>
                        </div>

                        <div class="events-panel-actions">
                            @admin_can('create_events')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i>Add Event
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="events-filter-body">
                        <div class="events-filter-toolbar">
                            <div class="events-search-group">
                                <div class="events-search-control">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search events">
                                </div>
                                <button class="btn btn-primary events-search-btn" type="submit">
                                    <i class="fas fa-search"></i><span>Search</span>
                                </button>
                            </div>

                            <select name="type" class="form-select" aria-label="Filter by type">
                                <option value="">All types</option>
                                <option value="upcoming" {{ request('type') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('type') == 'past' ? 'selected' : '' }}>Past</option>
                                <option value="deleted" {{ request('type') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="month" class="form-select" aria-label="Filter by month">
                                <option value="">All months</option>
                                <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_events'))
                        <div class="events-bulk-row">
                            <div class="selected-count" id="selectedCount">
                                <i class="fas fa-square-check me-1"></i>No events selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_events'))
            <form id="bulkForm" method="POST" action="{{ route('admin.events.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Events Table - Mobile Responsive with Horizontal Scroll -->
            <div class="events-panel events-table-panel">
                <div class="events-panel-header">
                    <div class="events-panel-title">
                        <div class="events-panel-icon"><i class="fas fa-calendar-days"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-list"></i> Results</div>
                            <h3>Event List</h3>
                            <p class="events-panel-copy">View, edit, archive, or restore event records from one clean table.</p>
                        </div>
                    </div>
                    <div class="events-panel-actions">
                        <span class="events-count-pill">
                            <i class="fas fa-calendar"></i>
                            @if(isset($events) && method_exists($events, 'total'))
                                {{ $events->total() }} events
                            @else
                                {{ count($events ?? []) }} events
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="eventsTable">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'event_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Date
                                            @if(request('sort') == 'event_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Time</th>
                                    <th>
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
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'attendees', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Attendees
                                            @if(request('sort') == 'attendees')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Type
                                            @if(request('sort') == 'type')
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
                                @forelse($events as $event)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $event->id }}" class="event-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="event-thumb me-2">
                                                @if($event->image)
                                                    <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold event-title-text">{{ Str::limit($event->title, 30) }}</div>
                                                <div class="event-mobile-meta d-md-none">
                                                    <span><i class="far fa-calendar"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                                    <span><i class="fas fa-location-dot"></i>{{ Str::limit($event->location ?? 'No location', 22) }}</span>
                                                    <span>
                                                        @if($event->deleted_at)
                                                            <i class="fas fa-box-archive"></i>Archived
                                                        @elseif($event->type == 'upcoming')
                                                            <i class="fas fa-clock"></i>Upcoming
                                                        @else
                                                            <i class="fas fa-check"></i>Past
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="location-text" title="{{ $event->location }}">
                                            {{ $event->location ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="attendees-badge">
                                            <i class="fas fa-users me-1"></i>{{ number_format($event->attendees ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($event->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-cluster">
                                            <button type="button"
                                                    class="btn btn-sm action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $event->id }}"
                                                    title="View event details">
                                                <i class="fas fa-eye"></i><span class="action-label">View</span>
                                            </button>

                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn-sm action-more-toggle icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end action-menu">
                                                    @if(!$event->deleted_at)
                                                        @admin_can('update_events')
                                                        <button type="button"
                                                                class="dropdown-item action-menu-edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editEventModal{{ $event->id }}">
                                                            <i class="fas fa-pen"></i>
                                                            <span>Edit Event</span>
                                                        </button>
                                                        @endadmin_can
                                                    @endif

                                                    @admin_can('delete_events')
                                                    @if($event->deleted_at)
                                                        <form method="POST" action="{{ route('admin.events.restore', $event->id) }}" onsubmit="return confirmDelete(event, 'Restore this event?')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item action-menu-positive">
                                                                <i class="fas fa-rotate-left"></i>
                                                                <span>Restore Event</span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirmDelete(event, 'Archive this event?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item action-menu-danger">
                                                                <i class="fas fa-box-archive"></i>
                                                                <span>Archive Event</span>
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
                                                <div class="empty-state-icon"><i class="fas fa-calendar-xmark"></i></div>
                                                <h4>No events found</h4>
                                                <p>No event records match the current search and filters.</p>
                                                @admin_can('create_events')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                                    <i class="fas fa-plus"></i>Add Event
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
                    @if(isset($events) && $events->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Event Modal with Validation - Only if user has create_events permission -->
            @admin_can('create_events')
            <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-calendar-plus"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Event</h5>
                                    <span class="modal-subtitle">Create the event in three clear sections. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="addEventForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Event Information</h6>
                                            <p>Use a clear title and description that residents can understand quickly.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Event Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif"
                                                      name="description" rows="4" placeholder="Write event description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                            <small class="field-hint">Include the purpose, important instructions, or other details residents should know.</small>
                                            @if(session('form_type') == 'add')
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Schedule &amp; Event Details</h6>
                                            <p>Set when and where the event happens, then record its current type and expected attendance.</p>
                                        </div>
                                    </div>

                                    <div class="schedule-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-day"></i>Event Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('event_date') is-invalid @enderror @endif"
                                                   name="event_date" value="{{ session('form_type') == 'add' ? old('event_date') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('event_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-clock"></i>Start Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'add') @error('start_time') is-invalid @enderror @endif"
                                                   name="start_time" value="{{ session('form_type') == 'add' ? old('start_time') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('start_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-clock"></i>End Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'add') @error('end_time') is-invalid @enderror @endif"
                                                   name="end_time" value="{{ session('form_type') == 'add' ? old('end_time') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('end_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>

                                    <div class="details-grid mt-3">
                                        <div>
                                            <label class="form-label"><i class="fas fa-location-dot"></i>Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif"
                                                   name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="Venue or address" required>
                                            @if(session('form_type') == 'add')
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-users"></i>Attendees</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'add') @error('attendees') is-invalid @enderror @endif"
                                                   name="attendees" value="{{ session('form_type') == 'add' ? old('attendees', 0) : 0 }}" min="0">
                                            @if(session('form_type') == 'add')
                                                @error('attendees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-tag"></i>Event Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('type') is-invalid @enderror @endif" name="type" required>
                                                <option value="">Select type</option>
                                                <option value="upcoming" {{ (session('form_type') == 'add' && old('type') == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                                                <option value="past" {{ (session('form_type') == 'add' && old('type') == 'past') ? 'selected' : '' }}>Past</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Event Image</h6>
                                            <p>Add an optional banner or photo so residents can recognize the event more easily.</p>
                                        </div>
                                    </div>
                                    <div class="upload-card">
                                        <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                        <div class="flex-grow-1 min-w-0">
                                            <label class="form-label">Event Banner/Image</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif"
                                                   name="image" accept="image/*" onchange="previewImage(this, 'addImagePreview')">
                                            <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                            @if(session('form_type') == 'add')
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="addImagePreview"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Cancel
                            </button>
                            <button type="submit" form="addEventForm" class="btn btn-success" id="submitAddForm">
                                <i class="fas fa-save"></i>Save Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Event Modals with Validation - Only if user has update_events permission -->
            @if(auth('admin')->user()->hasPermission('update_events'))
                @foreach($events as $event)
                <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Event</h5>
                                        <span class="modal-subtitle">Update the event information without changing the existing event workflow.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" id="editEventForm{{ $event->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $event->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Event Information</h6>
                                                <p>Review the title and description shown to residents.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Event Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $event->id ? old('title', $event->title) : $event->title }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('description') is-invalid @enderror @endif"
                                                          name="description" rows="4" required>{{ session('form_type') == 'edit_' . $event->id ? old('description', $event->description) : $event->description }}</textarea>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">2</div>
                                            <div>
                                                <h6>Schedule &amp; Event Details</h6>
                                                <p>Keep the event date, time, venue, attendance, and type accurate.</p>
                                            </div>
                                        </div>

                                        <div class="schedule-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-day"></i>Event Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('event_date') is-invalid @enderror @endif"
                                                       name="event_date" value="{{ session('form_type') == 'edit_' . $event->id ? old('event_date', $event->event_date->format('Y-m-d')) : $event->event_date->format('Y-m-d') }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('event_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-clock"></i>Start Time</label>
                                                <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('start_time') is-invalid @enderror @endif"
                                                       name="start_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('start_time', $event->start_time) : $event->start_time }}">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('start_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-clock"></i>End Time</label>
                                                <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('end_time') is-invalid @enderror @endif"
                                                       name="end_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('end_time', $event->end_time) : $event->end_time }}">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('end_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <div class="details-grid mt-3">
                                            <div>
                                                <label class="form-label"><i class="fas fa-location-dot"></i>Location <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('location') is-invalid @enderror @endif"
                                                       name="location" value="{{ session('form_type') == 'edit_' . $event->id ? old('location', $event->location) : $event->location }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('location')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-users"></i>Attendees</label>
                                                <input type="number" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('attendees') is-invalid @enderror @endif"
                                                       name="attendees" value="{{ session('form_type') == 'edit_' . $event->id ? old('attendees', $event->attendees) : $event->attendees }}" min="0">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('attendees')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-tag"></i>Event Type <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $event->id) @error('type') is-invalid @enderror @endif" name="type" required>
                                                    <option value="">Select type</option>
                                                    <option value="upcoming" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                    <option value="past" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'past' ? 'selected' : '' }}>Past</option>
                                                </select>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Event Image</h6>
                                                <p>Upload a replacement only when the current event image needs to change.</p>
                                            </div>
                                        </div>
                                        <div class="upload-card">
                                            <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                            <div class="flex-grow-1 min-w-0">
                                                <label class="form-label">Event Banner/Image</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('image') is-invalid @enderror @endif"
                                                       name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $event->id }}')">
                                                <small class="field-hint">Leave empty to keep the current image. JPG, PNG, or GIF; maximum 5MB.</small>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                                <div id="editImagePreview{{ $event->id }}">
                                                    @if($event->image)
                                                        <button type="button" class="image-preview-button" onclick="openZoomModal('{{ asset($event->image) }}')" title="View current event image">
                                                            <img src="{{ asset($event->image) }}" alt="Current image" class="image-preview">
                                                        </button>
                                                        <small class="field-hint">Current image — click to preview.</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i>Cancel
                                </button>
                                <button type="submit" form="editEventForm{{ $event->id }}" class="btn btn-primary" id="submitEditForm{{ $event->id }}">
                                    <i class="fas fa-save"></i>Update Event
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($events as $event)
            <div class="modal fade" id="viewModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-calendar-days"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Event Details</h5>
                                    <span class="modal-subtitle">Review the event schedule, venue, attendance, status, image, and description.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body event-view-body">
                            <div class="event-view-hero">
                                @if($event->image)
                                    <button type="button" class="image-preview-button" onclick="openZoomModal('{{ asset($event->image) }}')" title="View event image">
                                        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}">
                                    </button>
                                @endif
                                <div class="event-view-title-block">
                                    <h4 class="event-detail-title">{{ $event->title }}</h4>
                                    <div class="event-view-meta">
                                        @if($event->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                        <span class="text-muted small"><i class="fas fa-location-dot me-1"></i>{{ $event->location }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Event Date</span>
                                    <span class="view-summary-value">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Event Time</span>
                                    <span class="view-summary-value">
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @else
                                            Not specified
                                        @endif
                                    </span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Attendees</span>
                                    <span class="view-summary-value">{{ number_format($event->attendees ?? 0) }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Created</span>
                                    <span class="view-summary-value">{{ $event->created_at ? $event->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2 text-primary"></i>Description</div>
                                <div class="event-description-box">{!! nl2br(e($event->description)) !!}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Global Image Zoom Modal -->
            <div class="modal fade image-zoom-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-image"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Image Preview</h5>
                                    <span class="modal-subtitle">View the uploaded event image at a larger size.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="img-fluid" alt="Uploaded image preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editEventModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
            </div>
@endsection

@push('scripts')
<!-- SweetAlert2 for better alerts -->
<script>
        // Bulk Archive function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.event-checkbox:checked');
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
                        text: 'Please select at least one event to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one event to archive.');
                }
                return;
            }

            // Clear old ids so repeated bulk operations do not accumulate inputs.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected event(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} event(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.event-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
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

        // Preview image before upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="image-preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
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

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            zoomImage.src = imageUrl;
            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { 
                fb = document.createElement('div'); 
                fb.className = 'invalid-feedback'; 
                el.insertAdjacentElement('afterend', fb); 
            }
            fb.textContent = msg;
        }
        
        function cf(el) { 
            if (el) {
                el.classList.remove('is-invalid');
                // Also remove any existing error message
                const fb = el.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.remove();
            }
        }

        function attachEventValidation(form) {
            if (!form) return;
            const title       = form.querySelector('[name="title"]');
            const description = form.querySelector('[name="description"]');
            const eventDate   = form.querySelector('[name="event_date"]');
            const location    = form.querySelector('[name="location"]');
            const type        = form.querySelector('[name="type"]');
            const image       = form.querySelector('[name="image"]');

            if (title) {
                title.removeEventListener('input', title._handler);
                title._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('input', title._handler);
                
                // Also validate on blur
                title.removeEventListener('blur', title._blurHandler);
                title._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('blur', title._blurHandler);
            }
            
            if (description) {
                description.removeEventListener('input', description._handler);
                description._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('input', description._handler);
                
                description.removeEventListener('blur', description._blurHandler);
                description._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('blur', description._blurHandler);
            }
            
            if (eventDate) {
                eventDate.removeEventListener('change', eventDate._handler);
                eventDate._handler = function() {
                    if (!this.value) {
                        sf(this, 'Event date is required.');
                    } else {
                        cf(this);
                    }
                };
                eventDate.addEventListener('change', eventDate._handler);
            }
            
            if (location) {
                location.removeEventListener('input', location._handler);
                location._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('input', location._handler);
                
                location.removeEventListener('blur', location._blurHandler);
                location._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('blur', location._blurHandler);
            }
            
            if (type) {
                type.removeEventListener('change', type._handler);
                type._handler = function() {
                    if (!this.value) {
                        sf(this, 'Please select an event type.');
                    } else {
                        cf(this);
                    }
                };
                type.addEventListener('change', type._handler);
            }
            
            if (image) {
                image.removeEventListener('change', image._handler);
                image._handler = function() {
                    if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                        sf(this, 'Image must be less than 5MB.'); 
                        this.value = '';
                    } else {
                        cf(this);
                    }
                };
                image.addEventListener('change', image._handler);
            }

            // Remove any existing submit listener
            form.removeEventListener('submit', form._submitHandler);
            
            form._submitHandler = function(e) {
                let valid = true;
                
                // Title validation
                if (title) {
                    if (!title.value.trim()) { 
                        sf(title, 'Title is required.'); 
                        valid = false; 
                    } else if (title.value.trim().length < 3) { 
                        sf(title, 'Title must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(title);
                    }
                }
                
                // Description validation
                if (description) {
                    if (!description.value.trim()) { 
                        sf(description, 'Description is required.'); 
                        valid = false; 
                    } else if (description.value.trim().length < 10) { 
                        sf(description, 'Description must be at least 10 characters.'); 
                        valid = false; 
                    } else {
                        cf(description);
                    }
                }
                
                // Event date validation
                if (eventDate && !eventDate.value) { 
                    sf(eventDate, 'Event date is required.'); 
                    valid = false; 
                } else if (eventDate && eventDate.value) {
                    cf(eventDate);
                }
                
                // Location validation
                if (location) {
                    if (!location.value.trim()) { 
                        sf(location, 'Location is required.'); 
                        valid = false; 
                    } else if (location.value.trim().length < 3) { 
                        sf(location, 'Location must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(location);
                    }
                }
                
                // Type validation
                if (type && !type.value) { 
                    sf(type, 'Please select an event type.'); 
                    valid = false; 
                } else if (type && type.value) {
                    cf(type);
                }
                
                // Image validation
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); 
                    valid = false;
                } else if (image) {
                    cf(image);
                }
                
                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };
            
            form.addEventListener('submit', form._submitHandler);
        }

        // Attach validation to add form
        attachEventValidation(document.getElementById('addEventForm'));
        
        // Attach validation to all edit forms
        document.querySelectorAll('[id^="editEventForm"]').forEach(attachEventValidation);
        
        // Also handle form reset when modal is closed
        const addModal = document.getElementById('addEventModal');
        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function () {
                const form = document.getElementById('addEventForm');
                if (form) {
                    form.reset();
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        }
        
        // Handle edit modals reset
        document.querySelectorAll('[id^="editEventModal"]').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');
            const checkboxes = () => Array.from(document.querySelectorAll('.event-checkbox'));

            function updateSelectedCount() {
                if (!selectedCount) {
                    return;
                }

                const count = checkboxes().filter(cb => cb.checked).length;
                selectedCount.innerHTML = count ? `<i class="fas fa-square-check me-1"></i>${count} event${count === 1 ? '' : 's'} selected` : '<i class="fas fa-square-check me-1"></i>No events selected';

                if (selectAll) {
                    const total = checkboxes().length;
                    selectAll.checked = total > 0 && count === total;
                    selectAll.indeterminate = count > 0 && count < total;
                }
            }

            checkboxes().forEach(cb => cb.addEventListener('change', updateSelectedCount));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes().forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            updateSelectedCount();

            document.querySelectorAll('.events-alert').forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 250);
                }, 4500);
            });
        });
    </script>
@endpush
