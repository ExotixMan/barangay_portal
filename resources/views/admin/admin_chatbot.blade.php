@extends('admin.layouts.admin')

@section('title', 'Chatbot Management')
@section('page_title', 'Chatbot Management')
@section('page_icon', 'fas fa-robot')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/chatbot.css') }}">

    <style>
        /* Chatbot Management UX refresh
           Presentation-only overrides. Existing routes, IDs, permissions and JS hooks are preserved. */
        .chatbot-page {
            --cb-primary: #4f46e5;
            --cb-primary-soft: #eef2ff;
            --cb-success: #15803d;
            --cb-success-soft: #ecfdf3;
            --cb-danger: #dc2626;
            --cb-danger-soft: #fef2f2;
            --cb-warning: #a16207;
            --cb-warning-soft: #fffbeb;
            --cb-text: #172033;
            --cb-muted: #687386;
            --cb-border: #e3e8ef;
            --cb-surface: #ffffff;
            --cb-surface-soft: #f8fafc;
            --cb-shadow: 0 8px 24px rgba(15, 23, 42, .06);
            display: flex;
            flex-direction: column;
            gap: 16px;
            min-width: 0;
        }

        .chatbot-page *,
        .chatbot-page *::before,
        .chatbot-page *::after {
            box-sizing: border-box;
        }

        .chatbot-page .chatbot-alert {
            margin: 0;
            border: 1px solid transparent;
            border-radius: 12px;
            box-shadow: none;
        }

        /* Compact page heading instead of a large hero */
        .chatbot-page .chatbot-hero {
            margin: 0;
        }

        .chatbot-page .hero-panel {
            border: 1px solid var(--cb-border);
            border-radius: 16px;
            background: var(--cb-surface);
            box-shadow: none;
            overflow: hidden;
        }

        .chatbot-page .hero-content {
            min-height: 0;
            padding: 18px 20px;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            grid-template-areas:
                "icon kicker actions"
                "icon title actions"
                "icon copy actions";
            align-items: center;
            column-gap: 14px;
            row-gap: 2px;
        }

        .chatbot-page .hero-icon {
            grid-area: icon;
            width: 44px;
            height: 44px;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--cb-primary-soft);
            color: var(--cb-primary);
            font-size: 1.05rem;
        }

        .chatbot-page .hero-kicker {
            grid-area: kicker;
            margin: 0;
            color: var(--cb-primary);
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        .chatbot-page .hero-content h2 {
            grid-area: title;
            margin: 0;
            color: var(--cb-text);
            font-size: 1.05rem;
            line-height: 1.35;
            font-weight: 800;
        }

        .chatbot-page .hero-content > p {
            grid-area: copy;
            margin: 2px 0 0;
            max-width: 760px;
            color: var(--cb-muted);
            font-size: .8rem;
            line-height: 1.5;
        }

        .chatbot-page .hero-actions {
            grid-area: actions;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin: 0;
        }

        .chatbot-page .hero-actions .btn {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            white-space: nowrap;
        }

        /* Operational metric strip */
        .chatbot-page .chatbot-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin: 0;
        }

        .chatbot-page .chatbot-stat {
            min-width: 0;
            min-height: 76px;
            padding: 12px 14px;
            display: grid;
            grid-template-columns: 36px minmax(0, 1fr);
            align-items: center;
            gap: 10px;
            border: 1px solid var(--cb-border);
            border-radius: 13px;
            background: var(--cb-surface);
            box-shadow: none;
        }

        .chatbot-page .chatbot-stat-icon {
            width: 36px;
            height: 36px;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--cb-surface-soft);
            color: var(--cb-primary);
            font-size: .95rem;
        }

        .chatbot-page .chatbot-stat span {
            display: block;
            color: var(--cb-muted);
            font-size: .68rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .chatbot-page .chatbot-stat strong {
            display: inline-block;
            margin-top: 2px;
            color: var(--cb-text);
            font-size: 1.15rem;
            line-height: 1.05;
            font-weight: 850;
        }

        .chatbot-page .chatbot-stat small {
            display: none;
        }

        .chatbot-page .training-note {
            margin: 0;
            padding: 11px 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #fde7b0;
            border-radius: 12px;
            background: #fffdf5;
            box-shadow: none;
        }

        .chatbot-page .training-note .panel-icon {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            border-radius: 9px;
            background: var(--cb-warning-soft);
            color: var(--cb-warning);
        }

        .chatbot-page .training-note h3 {
            margin: 0 0 2px;
            color: var(--cb-text);
            font-size: .8rem;
            font-weight: 800;
        }

        .chatbot-page .training-note p {
            margin: 0;
            color: var(--cb-muted);
            font-size: .75rem;
            line-height: 1.45;
        }

        /* Tabs */
        .chatbot-page .tab-navigation {
            width: fit-content;
            max-width: 100%;
            margin: 0;
            padding: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            border: 1px solid var(--cb-border);
            border-radius: 12px;
            background: var(--cb-surface-soft);
            overflow-x: auto;
            scrollbar-width: none;
        }

        .chatbot-page .tab-navigation::-webkit-scrollbar {
            display: none;
        }

        .chatbot-page .tab-link {
            min-height: 36px;
            padding: 7px 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: var(--cb-muted);
            font-size: .78rem;
            font-weight: 750;
            white-space: nowrap;
        }

        .chatbot-page .tab-link:hover {
            color: var(--cb-text);
            background: #fff;
        }

        .chatbot-page .tab-link.active {
            color: var(--cb-primary);
            background: #fff;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .08);
        }

        .chatbot-page .chatbot-workspace,
        .chatbot-page .tab-content {
            min-width: 0;
        }

        .chatbot-page .tab-content:not(.active) {
            display: none;
        }

        .chatbot-page .chatbot-panel {
            border: 1px solid var(--cb-border);
            border-radius: 16px;
            background: var(--cb-surface);
            box-shadow: var(--cb-shadow);
            overflow: hidden;
        }

        .chatbot-page .panel-header {
            padding: 15px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border-bottom: 1px solid var(--cb-border);
            background: #fff;
        }

        .chatbot-page .panel-title {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .chatbot-page .panel-title .panel-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--cb-primary-soft);
            color: var(--cb-primary);
        }

        .chatbot-page .section-kicker {
            margin-bottom: 1px;
            color: var(--cb-primary);
            font-size: .66rem;
            font-weight: 800;
            letter-spacing: .055em;
            text-transform: uppercase;
        }

        .chatbot-page .panel-title h3 {
            margin: 0;
            color: var(--cb-text);
            font-size: .96rem;
            font-weight: 820;
        }

        .chatbot-page .panel-copy {
            margin: 2px 0 0;
            color: var(--cb-muted);
            font-size: .74rem;
            line-height: 1.4;
        }

        .chatbot-page .chatbot-count {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 5px 9px;
            border: 1px solid var(--cb-border);
            border-radius: 999px;
            background: var(--cb-surface-soft);
            color: var(--cb-muted);
            font-size: .7rem;
            font-weight: 750;
            white-space: nowrap;
        }

        /* Search/filter workspace */
        .chatbot-page .knowledge-toolbar {
            padding: 12px 14px;
            display: grid;
            grid-template-columns: minmax(240px, 1fr) auto minmax(160px, .38fr) minmax(130px, .28fr) auto auto;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--cb-border);
            background: var(--cb-surface-soft);
        }

        .chatbot-page .search-control,
        .chatbot-page .filter-control {
            position: relative;
            min-width: 0;
        }

        .chatbot-page .search-control > i,
        .chatbot-page .filter-control > i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #98a2b3;
            pointer-events: none;
        }

        .chatbot-page .search-control .form-control,
        .chatbot-page .filter-control .form-select {
            min-height: 38px;
            padding-left: 34px;
            border-color: #d7dde6;
            border-radius: 10px;
            background-color: #fff;
            font-size: .76rem;
        }

        .chatbot-page .filter-control .form-select {
            padding-right: 30px;
        }

        .chatbot-page .knowledge-toolbar .btn {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 10px;
            white-space: nowrap;
        }

        /* Tables */
        .chatbot-page .table-responsive {
            overflow-x: auto;
        }

        .chatbot-page .table {
            margin: 0;
            min-width: 760px;
        }

        .chatbot-page .table thead th {
            padding: 10px 12px;
            border-bottom: 1px solid var(--cb-border);
            background: #f8fafc;
            color: #667085;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .chatbot-page .table tbody td {
            padding: 12px;
            border-color: #edf0f4;
            color: var(--cb-text);
            font-size: .77rem;
            line-height: 1.45;
            vertical-align: middle;
        }

        .chatbot-page .table tbody tr:hover > td {
            background: #fbfcff;
        }

        .chatbot-page .kb-question {
            color: #24324a;
            font-weight: 750;
        }

        .chatbot-page .kb-answer {
            max-width: 440px;
            color: var(--cb-muted);
        }

        .chatbot-page .keyword-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .chatbot-page .keyword-chip,
        .chatbot-page .keyword-more,
        .chatbot-page .badge-category,
        .chatbot-page .usage-pill,
        .chatbot-page .session-pill {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            border-radius: 999px;
            font-size: .67rem;
            font-weight: 750;
            line-height: 1.2;
        }

        .chatbot-page .keyword-chip {
            padding: 4px 7px;
            background: #f2f4f7;
            color: #475467;
        }

        .chatbot-page .keyword-more {
            padding: 4px 7px;
            background: var(--cb-primary-soft);
            color: var(--cb-primary);
        }

        .chatbot-page .badge-category {
            padding: 5px 8px;
            background: #eef6ff;
            color: #175cd3;
            text-transform: capitalize;
        }

        .chatbot-page .usage-pill {
            min-width: 28px;
            justify-content: center;
            padding: 5px 7px;
            background: #f2f4f7;
            color: #475467;
        }

        .chatbot-page .table .btn-group {
            gap: 5px;
        }

        .chatbot-page .table .btn-group > .btn {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px !important;
        }

        .chatbot-page .btn-edit {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #2563eb;
        }

        .chatbot-page .btn-edit:hover {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
        }

        .chatbot-page .btn-delete {
            border-color: #fecaca;
            background: var(--cb-danger-soft);
            color: var(--cb-danger);
        }

        .chatbot-page .btn-delete:hover {
            border-color: var(--cb-danger);
            background: var(--cb-danger);
            color: #fff;
        }

        .chatbot-page .session-control {
            display: flex;
            align-items: center;
            gap: 5px;
            min-width: 0;
        }

        .chatbot-page .session-pill {
            min-width: 0;
            padding: 5px 8px;
            background: #f2f4f7;
            color: #475467;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chatbot-page .session-copy {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--cb-border);
            border-radius: 8px;
            background: #fff;
            color: var(--cb-muted);
        }

        .chatbot-page .session-copy:hover {
            color: var(--cb-primary);
            border-color: #c7d2fe;
            background: var(--cb-primary-soft);
        }

        .chatbot-page .query-time {
            color: var(--cb-muted);
            white-space: nowrap;
        }

        .chatbot-page .unmatched-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chatbot-page .unmatched-actions .btn {
            width: auto;
            min-height: 34px;
            height: auto;
            padding: 6px 9px;
            gap: 5px;
            border-radius: 9px !important;
            font-size: .7rem;
            font-weight: 750;
        }

        .chatbot-page .btn-train {
            border: 1px solid #bbf7d0;
            background: var(--cb-success-soft);
            color: var(--cb-success);
        }

        .chatbot-page .btn-train:hover {
            border-color: var(--cb-success);
            background: var(--cb-success);
            color: #fff;
        }

        .chatbot-page .btn-dismiss {
            border: 1px solid #e4e7ec;
            background: #fff;
            color: #667085;
        }

        .chatbot-page .btn-dismiss:hover {
            border-color: #fda4af;
            background: #fff1f2;
            color: #be123c;
        }

        .chatbot-page .empty-state {
            padding: 38px 18px;
            text-align: center;
        }

        .chatbot-page .empty-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--cb-surface-soft);
            color: #98a2b3;
            font-size: 1.05rem;
        }

        .chatbot-page .empty-state h4 {
            margin: 0 0 4px;
            color: var(--cb-text);
            font-size: .9rem;
            font-weight: 800;
        }

        .chatbot-page .empty-state p {
            margin: 0;
            color: var(--cb-muted);
            font-size: .75rem;
        }

        /* Permission state */
        .permission-panel {
            padding: 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid #fed7aa;
            border-radius: 14px;
            background: #fffaf5;
            color: #7c2d12;
        }

        .permission-panel > i {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #ffedd5;
        }

        /* Consistent Add/Edit modal system */
        #addModal .modal-dialog,
        #editModal .modal-dialog {
            width: min(940px, calc(100vw - 32px));
            max-width: 940px;
            margin-left: auto;
            margin-right: auto;
        }

        #addModal .modal-content,
        #editModal .modal-content {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(15, 23, 42, .18);
        }

        #addModal .modal-header,
        #editModal .modal-header {
            min-height: 72px;
            padding: 15px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #e7ebf0;
            background: #fff;
        }

        #addModal .modal-heading,
        #editModal .modal-heading {
            min-width: 0;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 11px;
        }

        #addModal .knowledge-modal-icon,
        #editModal .knowledge-modal-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--cb-primary-soft, #eef2ff);
            color: var(--cb-primary, #4f46e5);
            font-size: 1rem;
        }

        #addModal .modal-title,
        #editModal .modal-title {
            margin: 0;
            color: #172033;
            font-size: 1rem;
            font-weight: 850;
            line-height: 1.3;
        }

        #addModal .modal-subtitle,
        #editModal .modal-subtitle {
            margin: 2px 0 0;
            max-width: 680px;
            color: #687386;
            font-size: .74rem;
            line-height: 1.4;
        }

        #addModal .btn-close,
        #editModal .btn-close {
            flex: 0 0 auto;
            margin: 0;
        }

        #addModal .modal-body,
        #editModal .modal-body {
            padding: 16px 18px 18px;
            background: #f8fafc;
        }

        #addModal .knowledge-form,
        #editModal .knowledge-form {
            margin: 0;
        }

        #addModal .knowledge-form-grid,
        #editModal .knowledge-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
            gap: 12px;
        }

        #addModal .knowledge-form-panel,
        #editModal .knowledge-form-panel {
            min-width: 0;
            padding: 15px;
            border: 1px solid #e1e6ed;
            border-radius: 14px;
            background: #fff;
        }

        #addModal .knowledge-form-panel.is-soft,
        #editModal .knowledge-form-panel.is-soft {
            background: #fbfcff;
        }

        #addModal .knowledge-form-panel-title,
        #editModal .knowledge-form-panel-title {
            margin-bottom: 13px;
            display: flex;
            align-items: center;
            gap: 9px;
            color: #172033;
            font-size: .82rem;
            font-weight: 830;
        }

        #addModal .knowledge-form-panel-title::before,
        #editModal .knowledge-form-panel-title::before {
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: .68rem;
            font-weight: 850;
        }

        #addModal .knowledge-form-panel:first-child .knowledge-form-panel-title::before,
        #editModal .knowledge-form-panel:first-child .knowledge-form-panel-title::before {
            content: "1";
        }

        #addModal .knowledge-form-panel:nth-child(2) .knowledge-form-panel-title::before,
        #editModal .knowledge-form-panel:nth-child(2) .knowledge-form-panel-title::before {
            content: "2";
        }

        #addModal .knowledge-form-panel-title > i,
        #editModal .knowledge-form-panel-title > i {
            display: none;
        }

        #addModal .form-label,
        #editModal .form-label {
            margin-bottom: 6px;
            color: #344054;
            font-size: .73rem;
            font-weight: 760;
        }

        #addModal .form-control,
        #addModal .form-select,
        #editModal .form-control,
        #editModal .form-select {
            min-height: 42px;
            border-color: #d6dde6;
            border-radius: 10px;
            background-color: #fff;
            color: #172033;
            font-size: .78rem;
        }

        #addModal textarea.form-control,
        #editModal textarea.form-control {
            min-height: 164px;
            resize: vertical;
            line-height: 1.5;
        }

        #addModal .form-control:focus,
        #addModal .form-select:focus,
        #editModal .form-control:focus,
        #editModal .form-select:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 .2rem rgba(79, 70, 229, .09);
        }

        #addModal .form-hint,
        #editModal .form-hint {
            display: block;
            margin-top: 5px;
            color: #7b8494;
            font-size: .69rem;
            line-height: 1.4;
        }

        #addModal .knowledge-tip,
        #editModal .knowledge-tip {
            margin-top: 13px;
            padding: 10px 11px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            border: 1px solid #fde7b0;
            border-radius: 10px;
            background: #fffdf5;
            color: #7c5b13;
            font-size: .7rem;
            line-height: 1.45;
        }

        #addModal .modal-footer,
        #editModal .modal-footer {
            min-height: 66px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-top: 1px solid #e7ebf0;
            background: #fff;
        }

        #addModal .modal-safe-note,
        #editModal .modal-safe-note {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #697386;
            font-size: .7rem;
            line-height: 1.35;
        }

        #addModal .modal-footer-actions,
        #editModal .modal-footer-actions {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #addModal .modal-footer .btn,
        #editModal .modal-footer .btn {
            min-height: 38px;
            padding: 7px 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border-radius: 10px;
            font-size: .76rem;
            font-weight: 760;
        }

        #addModal #save-add {
            border-color: #16a34a;
            background: #16a34a;
            color: #fff;
        }

        #addModal #save-add:hover {
            border-color: #15803d;
            background: #15803d;
        }

        #editModal #save-edit {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
        }

        #editModal #save-edit:hover {
            border-color: #1d4ed8;
            background: #1d4ed8;
        }

        /* Mobile / tablet */
        @media (max-width: 991.98px) {
            .chatbot-page .hero-content {
                grid-template-columns: auto minmax(0, 1fr);
                grid-template-areas:
                    "icon kicker"
                    "icon title"
                    "icon copy"
                    "actions actions";
            }

            .chatbot-page .hero-actions {
                margin-top: 9px;
                justify-content: flex-start;
            }

            .chatbot-page .chatbot-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chatbot-page .knowledge-toolbar {
                grid-template-columns: minmax(0, 1fr) auto minmax(170px, .55fr) minmax(140px, .45fr) auto;
            }

            .chatbot-page .knowledge-add-btn {
                grid-column: 1 / -1;
                width: 100%;
            }

            #addModal .knowledge-form-grid,
            #editModal .knowledge-form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .chatbot-page {
                gap: 12px;
            }

            .chatbot-page .hero-content {
                padding: 14px;
                grid-template-columns: 38px minmax(0, 1fr);
                column-gap: 10px;
            }

            .chatbot-page .hero-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
            }

            .chatbot-page .hero-content h2 {
                font-size: .93rem;
            }

            .chatbot-page .hero-content > p {
                font-size: .73rem;
            }

            .chatbot-page .hero-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
                width: 100%;
            }

            .chatbot-page .hero-actions .btn {
                width: 100%;
                min-width: 0;
                padding-inline: 9px;
                font-size: .73rem;
            }

            .chatbot-page .chatbot-stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .chatbot-page .chatbot-stat {
                min-height: 66px;
                padding: 10px;
                grid-template-columns: 30px minmax(0, 1fr);
                gap: 8px;
            }

            .chatbot-page .chatbot-stat-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                font-size: .8rem;
            }

            .chatbot-page .chatbot-stat strong {
                font-size: 1rem;
            }

            .chatbot-page .training-note {
                padding: 10px 11px;
            }

            .chatbot-page .tab-navigation {
                width: 100%;
            }

            .chatbot-page .tab-link {
                flex: 1 0 auto;
            }

            .chatbot-page .panel-header {
                padding: 12px;
                align-items: flex-start;
            }

            .chatbot-page .panel-title .panel-icon {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
            }

            .chatbot-page .panel-copy {
                display: none;
            }

            .chatbot-page .knowledge-toolbar {
                padding: 10px;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .chatbot-page .search-control {
                grid-column: 1 / -1;
            }

            .chatbot-page .search-submit-btn {
                grid-column: 1 / -1;
                width: 100%;
            }

            .chatbot-page .filter-control {
                min-width: 0;
            }

            .chatbot-page .filter-reset-btn {
                width: 100%;
            }

            .chatbot-page .knowledge-add-btn {
                grid-column: auto;
                width: 100%;
            }

            /* Convert knowledge and unmatched tables to stacked records on phones */
            .chatbot-page .table-responsive {
                overflow: visible;
            }

            .chatbot-page #knowledge-table,
            .chatbot-page #unmatched-table {
                width: 100%;
                min-width: 0;
                border: 0;
            }

            .chatbot-page #knowledge-table thead,
            .chatbot-page #unmatched-table thead {
                display: none;
            }

            .chatbot-page #knowledge-table tbody,
            .chatbot-page #unmatched-table tbody {
                display: block;
                padding: 8px;
            }

            .chatbot-page #knowledge-table tbody tr[data-id],
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id] {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0;
                margin-bottom: 8px;
                border: 1px solid var(--cb-border);
                border-radius: 12px;
                background: #fff;
                overflow: hidden;
            }

            .chatbot-page #knowledge-table tbody tr[data-id]:last-child,
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id]:last-child {
                margin-bottom: 0;
            }

            .chatbot-page #knowledge-table tbody tr[data-id] > td,
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id] > td {
                width: 100% !important;
                padding: 9px 11px;
                display: grid;
                grid-template-columns: 88px minmax(0, 1fr);
                align-items: start;
                gap: 9px;
                border: 0;
                border-bottom: 1px solid #edf0f4;
                background: #fff;
            }

            .chatbot-page #knowledge-table tbody tr[data-id] > td:last-child,
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id] > td:last-child {
                border-bottom: 0;
            }

            .chatbot-page #knowledge-table tbody tr[data-id] > td::before,
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id] > td::before {
                content: attr(data-label);
                color: #7b8494;
                font-size: .65rem;
                font-weight: 800;
                letter-spacing: .025em;
                text-transform: uppercase;
            }

            .chatbot-page #knowledge-table .kb-question,
            .chatbot-page #unmatched-table .unmatched-query {
                font-size: .78rem;
            }

            .chatbot-page #knowledge-table .kb-answer {
                max-width: none;
            }

            .chatbot-page #knowledge-table .btn-group,
            .chatbot-page #unmatched-table .unmatched-actions {
                justify-content: flex-start;
            }

            .chatbot-page #knowledge-table tbody tr:not([data-id]) td,
            .chatbot-page #unmatched-table tbody tr:not([data-unmatched-id]) td {
                display: block;
                padding: 0;
                border: 0;
            }

            #addModal .modal-dialog,
            #editModal .modal-dialog {
                width: calc(100vw - 16px);
                max-width: none;
                margin: 8px auto;
                min-height: calc(100vh - 16px);
            }

            #addModal .modal-content,
            #editModal .modal-content {
                max-height: calc(100vh - 16px);
                border-radius: 14px;
            }

            #addModal .modal-header,
            #editModal .modal-header {
                min-height: 0;
                padding: 12px;
                align-items: flex-start;
            }

            #addModal .knowledge-modal-icon,
            #editModal .knowledge-modal-icon {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
                border-radius: 9px;
            }

            #addModal .modal-title,
            #editModal .modal-title {
                font-size: .9rem;
            }

            #addModal .modal-subtitle,
            #editModal .modal-subtitle {
                font-size: .69rem;
            }

            #addModal .modal-body,
            #editModal .modal-body {
                padding: 10px;
            }

            #addModal .knowledge-form-grid,
            #editModal .knowledge-form-grid {
                gap: 9px;
            }

            #addModal .knowledge-form-panel,
            #editModal .knowledge-form-panel {
                padding: 12px;
                border-radius: 12px;
            }

            #addModal .modal-footer,
            #editModal .modal-footer {
                padding: 10px;
                align-items: stretch;
                flex-direction: column;
            }

            #addModal .modal-safe-note,
            #editModal .modal-safe-note {
                width: 100%;
            }

            #addModal .modal-footer-actions,
            #editModal .modal-footer-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            #addModal .modal-footer .btn,
            #editModal .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 479.98px) {
            .chatbot-page .hero-actions {
                grid-template-columns: 1fr;
            }

            .chatbot-page .chatbot-stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .chatbot-page .chatbot-stat {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .chatbot-page .chatbot-stat-icon {
                display: none;
            }

            .chatbot-page .knowledge-toolbar {
                grid-template-columns: 1fr;
            }

            .chatbot-page .search-control,
            .chatbot-page .search-submit-btn,
            .chatbot-page .knowledge-add-btn {
                grid-column: auto;
            }

            .chatbot-page #knowledge-table tbody tr[data-id] > td,
            .chatbot-page #unmatched-table tbody tr[data-unmatched-id] > td {
                grid-template-columns: 74px minmax(0, 1fr);
                padding: 8px 9px;
            }

            #addModal .modal-footer-actions,
            #editModal .modal-footer-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Dark mode compatibility */
        [data-theme="dark"] .chatbot-page {
            --cb-text: #e5e7eb;
            --cb-muted: #9ca3af;
            --cb-border: #303744;
            --cb-surface: #171b24;
            --cb-surface-soft: #202631;
            --cb-shadow: none;
        }

        [data-theme="dark"] .chatbot-page .hero-panel,
        [data-theme="dark"] .chatbot-page .chatbot-stat,
        [data-theme="dark"] .chatbot-page .chatbot-panel,
        [data-theme="dark"] .chatbot-page .panel-header,
        [data-theme="dark"] .chatbot-page .table tbody td,
        [data-theme="dark"] .chatbot-page #knowledge-table tbody tr[data-id],
        [data-theme="dark"] .chatbot-page #unmatched-table tbody tr[data-unmatched-id],
        [data-theme="dark"] .chatbot-page #knowledge-table tbody tr[data-id] > td,
        [data-theme="dark"] .chatbot-page #unmatched-table tbody tr[data-unmatched-id] > td {
            background: var(--cb-surface);
        }

        [data-theme="dark"] .chatbot-page .knowledge-toolbar,
        [data-theme="dark"] .chatbot-page .table thead th,
        [data-theme="dark"] .chatbot-page .tab-navigation {
            background: var(--cb-surface-soft);
        }

        [data-theme="dark"] .chatbot-page .tab-link.active,
        [data-theme="dark"] .chatbot-page .tab-link:hover,
        [data-theme="dark"] .chatbot-page .search-control .form-control,
        [data-theme="dark"] .chatbot-page .filter-control .form-select,
        [data-theme="dark"] .chatbot-page .session-copy {
            background: #171b24;
            color: #e5e7eb;
        }

        [data-theme="dark"] #addModal .modal-content,
        [data-theme="dark"] #editModal .modal-content,
        [data-theme="dark"] #addModal .modal-header,
        [data-theme="dark"] #editModal .modal-header,
        [data-theme="dark"] #addModal .modal-footer,
        [data-theme="dark"] #editModal .modal-footer,
        [data-theme="dark"] #addModal .knowledge-form-panel,
        [data-theme="dark"] #editModal .knowledge-form-panel,
        [data-theme="dark"] #addModal .knowledge-form-panel.is-soft,
        [data-theme="dark"] #editModal .knowledge-form-panel.is-soft {
            background: #171b24;
            border-color: #303744;
        }

        [data-theme="dark"] #addModal .modal-body,
        [data-theme="dark"] #editModal .modal-body {
            background: #11151d;
        }

        [data-theme="dark"] #addModal .modal-title,
        [data-theme="dark"] #editModal .modal-title,
        [data-theme="dark"] #addModal .knowledge-form-panel-title,
        [data-theme="dark"] #editModal .knowledge-form-panel-title,
        [data-theme="dark"] #addModal .form-label,
        [data-theme="dark"] #editModal .form-label {
            color: #e5e7eb;
        }

        [data-theme="dark"] #addModal .modal-subtitle,
        [data-theme="dark"] #editModal .modal-subtitle,
        [data-theme="dark"] #addModal .form-hint,
        [data-theme="dark"] #editModal .form-hint,
        [data-theme="dark"] #addModal .modal-safe-note,
        [data-theme="dark"] #editModal .modal-safe-note {
            color: #9ca3af;
        }

        [data-theme="dark"] #addModal .form-control,
        [data-theme="dark"] #addModal .form-select,
        [data-theme="dark"] #editModal .form-control,
        [data-theme="dark"] #editModal .form-select {
            border-color: #374151;
            background-color: #11151d;
            color: #e5e7eb;
        }
    </style>

@endpush

@section('content')
            @admin_can('view_content')
            @php
                $pendingUnmatched = collect($unmatched ?? [])
                    ->filter(fn ($item) => empty($item['resolved']))
                    ->values();
                $pendingUnmatchedCount = $pendingUnmatched->count();
            @endphp

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
                            <div class="hero-kicker">Knowledge &amp; Training</div>
                            <h2>Manage chatbot answers and training from one workspace.</h2>
                            <p>Maintain resident-facing answers, organize keywords, and review questions the chatbot could not match.</p>

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
                            <strong id="stat-unmatched">{{ $pendingUnmatchedCount }}</strong>
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
                    <button class="tab-link active" id="knowledge-tab" data-tab="knowledge" type="button" role="tab" aria-selected="true" aria-controls="tab-knowledge">
                        <i class="fas fa-database"></i>Knowledge Base
                    </button>
                    <button class="tab-link" id="unmatched-tab" data-tab="unmatched" type="button" role="tab" aria-selected="false" aria-controls="tab-unmatched">
                        <i class="fas fa-circle-question"></i>Unmatched Queries
                    </button>
                </div>

                <div class="chatbot-workspace">
                    <div id="tab-knowledge" class="tab-content active" role="tabpanel" aria-labelledby="knowledge-tab">
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

                                <button class="btn btn-light-soft btn-sm filter-reset-btn" id="kb-filter-reset" type="button" title="Reset filters" aria-label="Reset filters">
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
                                            <td class="kb-question" data-label="Question">{{ Str::limit($k['question'], 90) }}</td>
                                            <td class="kb-answer" data-label="Answer">{{ Str::limit($k['answer'], 130) }}</td>
                                            @php
                                                $keywordList = collect(explode(',', (string) ($k['keywords'] ?? '')))
                                                    ->map(fn ($keyword) => trim($keyword))
                                                    ->filter()
                                                    ->values();
                                            @endphp
                                            <td data-label="Keywords" title="{{ $keywordList->implode(', ') }}">
                                                <div class="keyword-list">
                                                    @forelse($keywordList->take(3) as $keyword)
                                                        <span class="keyword-chip">{{ Str::limit($keyword, 22) }}</span>
                                                    @empty
                                                        <span class="keyword-empty">No keywords</span>
                                                    @endforelse
                                                    @if($keywordList->count() > 3)
                                                        <span class="keyword-more">+{{ $keywordList->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td data-label="Category"><span class="badge-category">{{ $k['category'] ?? 'general' }}</span></td>
                                            <td data-label="Uses"><span class="usage-pill">{{ $k['usage_count'] ?? 0 }}</span></td>
                                            <td data-label="Actions">
                                                <div class="btn-group btn-group-sm">
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-outline-primary btn-edit" data-id="{{ $k['id'] }}" data-question="{{ $k['question'] }}" data-answer="{{ $k['answer'] }}" data-keywords="{{ $k['keywords'] ?? '' }}" data-category="{{ $k['category'] ?? 'general' }}" title="Edit knowledge" aria-label="Edit knowledge" type="button">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('delete_content')
                                                    <button class="btn btn-outline-danger btn-delete" data-id="{{ $k['id'] }}" title="Delete knowledge" aria-label="Delete knowledge" type="button">
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

                    <div id="tab-unmatched" class="tab-content" role="tabpanel" aria-labelledby="unmatched-tab">
                        <div class="chatbot-panel">
                            <div class="panel-header">
                                <div class="panel-title">
                                    <div class="panel-icon"><i class="bi bi-question-circle-fill"></i></div>
                                    <div class="min-w-0">
                                        <div class="section-kicker"><i class="bi bi-magic"></i> Training Queue</div>
                                        <h3>Unmatched Queries</h3>
                                        <p class="panel-copy">Train useful questions into knowledge, or dismiss entries that are unrelated to barangay services.</p>
                                    </div>
                                </div>

                                <div class="panel-actions">
                                    <span class="chatbot-count" id="unmatched-count">{{ $pendingUnmatchedCount }} pending</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle unmatched-table" id="unmatched-table">
                                    <thead>
                                        <tr>
                                            <th style="width:42%">Query</th>
                                            <th style="width:18%">Session</th>
                                            <th style="width:16%">Time</th>
                                            <th style="width:24%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unmatched-tbody">
                                        @forelse($pendingUnmatched as $u)
                                        <tr class="unmatched-row" data-unmatched-id="{{ $u['id'] }}">
                                            <td class="unmatched-query" data-label="Query" title="{{ $u['query'] }}">{{ $u['query'] }}</td>
                                            <td data-label="Session">
                                                <div class="session-control">
                                                    <span class="session-pill" title="{{ $u['session_id'] ?? 'No session' }}">
                                                        {{ Str::limit($u['session_id'] ?? 'No session', 16) }}
                                                    </span>
                                                    @if(!empty($u['session_id']))
                                                    <button class="session-copy" type="button"
                                                            data-session="{{ $u['session_id'] }}"
                                                            title="Copy full session ID"
                                                            aria-label="Copy full session ID">
                                                        <i class="bi bi-copy"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td data-label="Time"><small class="query-time">{{ \Carbon\Carbon::parse($u['created_at'])->diffForHumans() }}</small></td>
                                            <td data-label="Actions">
                                                <div class="btn-group btn-group-sm unmatched-actions">
                                                    @admin_can('create_content')
                                                    <button class="btn btn-train"
                                                            data-id="{{ $u['id'] }}"
                                                            data-query="{{ $u['query'] }}"
                                                            title="Create a knowledge item from this query"
                                                            type="button">
                                                        <i class="bi bi-plus-lg"></i>Train Bot
                                                    </button>
                                                    @endadmin_can
                                                    @admin_can('edit_content')
                                                    <button class="btn btn-dismiss"
                                                            data-id="{{ $u['id'] }}"
                                                            data-query="{{ $u['query'] }}"
                                                            title="Dismiss this unrelated or unhelpful query"
                                                            type="button">
                                                        <i class="bi bi-x-lg"></i>Dismiss
                                                    </button>
                                                    @endadmin_can
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="empty-unmatched-row">
                                            <td colspan="4">
                                                <div class="empty-state">
                                                    <div>
                                                        <div class="empty-icon"><i class="bi bi-check2-circle"></i></div>
                                                        <h4>Training queue is clear</h4>
                                                        <p>There are no pending unmatched questions to review.</p>
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

@admin_can('create_content')
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addKnowledgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-knowledge modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header knowledge-modal-header">
                    <div class="modal-heading">
                        <span class="knowledge-modal-icon"><i class="bi bi-plus-circle"></i></span>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="addKnowledgeTitle">Add Knowledge</h5>
                            <p class="modal-subtitle" id="addKnowledgeSubtitle">Create one clear resident-facing answer, then organize it so the chatbot can match related questions.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-form" class="knowledge-form">
                        <input type="hidden" name="source_unmatched_id" id="source-unmatched-id" value="">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Answer Content</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" required autocomplete="off" placeholder="e.g. How do I track my Barangay Clearance request?">
                                    <span class="form-hint">Use the same wording residents commonly ask.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" rows="7" required placeholder="Write a short, complete answer with clear steps..."></textarea>
                                    <span class="form-hint">Keep the answer direct, accurate, and easy to scan.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization &amp; Matching</div>
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
                    <span class="modal-safe-note" id="addKnowledgeSafeNote"><i class="bi bi-shield-check"></i> Saved items become available to chatbot training.</span>
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
                            <p class="modal-subtitle">Review the answer first, then update its category and search keywords when needed.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" class="knowledge-form">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="knowledge-form-grid">
                            <div class="knowledge-form-panel">
                                <div class="knowledge-form-panel-title"><i class="bi bi-chat-square-text"></i> Answer Content</div>
                                <div class="mb-3">
                                    <label class="form-label">Question / Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="question" id="edit-question" required autocomplete="off">
                                    <span class="form-hint">Edit the resident-facing question or topic title.</span>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="answer" id="edit-answer" rows="7" required></textarea>
                                    <span class="form-hint">Make sure the answer is still correct before updating.</span>
                                </div>
                            </div>

                            <div class="knowledge-form-panel is-soft">
                                <div class="knowledge-form-panel-title"><i class="bi bi-sliders"></i> Organization &amp; Matching</div>
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
@endsection

@push('scripts')
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
                document.querySelectorAll('.tab-link[data-tab]').forEach(link => {
                    const isActive = link.dataset.tab === tab;
                    link.classList.toggle('active', isActive);
                    link.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.toggle('active', content.id === 'tab-' + tab);
                });
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

            function updateUnmatchedCount() {
                const pendingRows = document.querySelectorAll('#unmatched-tbody tr[data-unmatched-id]').length;
                const stat = document.getElementById('stat-unmatched');
                const count = document.getElementById('unmatched-count');

                if (stat) {
                    stat.textContent = String(pendingRows);
                }

                if (count) {
                    count.textContent = `${pendingRows} pending`;
                }

                if (pendingRows === 0) {
                    const tbody = document.getElementById('unmatched-tbody');
                    if (tbody) {
                        tbody.innerHTML = `
                            <tr class="empty-unmatched-row">
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div>
                                            <div class="empty-icon"><i class="bi bi-check2-circle"></i></div>
                                            <h4>Training queue is clear</h4>
                                            <p>There are no pending unmatched questions to review.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    }
                }
            }

            async function markUnmatchedProcessed(id) {
                return api('PATCH', withId(chatbotRoutes.unmatchedResolveTemplate, id));
            }

            function setAddModalContext(unmatchedId = '', query = '') {
                const addForm = document.getElementById('add-form');
                const sourceInput = document.getElementById('source-unmatched-id');
                const title = document.getElementById('addKnowledgeTitle');
                const subtitle = document.getElementById('addKnowledgeSubtitle');
                const safeNote = document.getElementById('addKnowledgeSafeNote');
                const saveButton = document.getElementById('save-add');

                if (addForm) {
                    addForm.reset();
                    clearFormErrors(addForm);
                }

                if (sourceInput) {
                    sourceInput.value = unmatchedId;
                }

                if (addForm && query) {
                    const questionInput = addForm.querySelector('[name="question"]');
                    if (questionInput) {
                        questionInput.value = query;
                    }
                }

                const isTraining = Boolean(unmatchedId);
                if (title) {
                    title.textContent = isTraining ? 'Train Chatbot' : 'Add Knowledge';
                }
                if (subtitle) {
                    subtitle.textContent = isTraining
                        ? 'Create the correct answer for this unmatched question. Saving it will remove the query from the training queue.'
                        : 'Create a clear chatbot answer residents can find through related keywords and category filtering.';
                }
                if (safeNote) {
                    safeNote.innerHTML = isTraining
                        ? '<i class="bi bi-check2-circle"></i> Saving also marks the unmatched query as processed.'
                        : '<i class="bi bi-shield-check"></i> Saved items become available to chatbot training.';
                }
                if (saveButton) {
                    saveButton.innerHTML = isTraining
                        ? '<i class="bi bi-save"></i>Save &amp; Mark Trained'
                        : '<i class="bi bi-save"></i>Save Knowledge';
                }
            }

            function copyText(value) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(value);
                }

                return new Promise((resolve, reject) => {
                    const textarea = document.createElement('textarea');
                    textarea.value = value;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    const copied = document.execCommand('copy');
                    textarea.remove();
                    copied ? resolve() : reject(new Error('Copy failed'));
                });
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

            const addModalElement = document.getElementById('addModal');
            document.querySelectorAll('[data-bs-target="#addModal"]').forEach(button => {
                button.addEventListener('click', () => setAddModalContext());
            });
            addModalElement?.addEventListener('hidden.bs.modal', () => setAddModalContext());

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

                    const sourceUnmatchedId = (document.getElementById('source-unmatched-id')?.value || '').trim();
                    const data = formData(addForm);
                    delete data.source_unmatched_id;

                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>Saving...';

                    try {
                        const res = await api('POST', chatbotRoutes.knowledgeStore, data);
                        if (!res.success) {
                            toast(res.message || 'Unable to save knowledge.', 'danger');
                            return;
                        }

                        let queryProcessed = !sourceUnmatchedId;
                        if (sourceUnmatchedId) {
                            try {
                                const processResult = await markUnmatchedProcessed(sourceUnmatchedId);
                                queryProcessed = Boolean(processResult.success);
                            } catch (error) {
                                queryProcessed = false;
                            }
                        }

                        bootstrap.Modal.getInstance(document.getElementById('addModal'))?.hide();

                        if (sourceUnmatchedId && !queryProcessed) {
                            toast('Knowledge was saved, but the query remains in the queue. You can dismiss it manually.', 'warning');
                        } else {
                            toast(sourceUnmatchedId ? 'Knowledge saved and query marked as trained.' : 'Knowledge saved!');
                        }

                        setTimeout(() => location.reload(), 900);
                    } catch (error) {
                        if (error.status === 422) {
                            applyServerErrors(addForm, error.payload?.errors);
                            toast('Please fix the validation errors first.', 'warning');
                            return;
                        }
                        toast('Network error', 'danger');
                    } finally {
                        this.disabled = false;
                        this.innerHTML = originalHtml;
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
                    setAddModalContext(this.dataset.id || '', this.dataset.query || '');
                    new bootstrap.Modal(document.getElementById('addModal')).show();
                });
            });

            document.querySelectorAll('.btn-dismiss').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id = this.dataset.id;
                    const query = this.dataset.query || 'this query';

                    if (!confirm(`Dismiss “${query}”? It will be removed from the pending training queue without adding a chatbot answer.`)) {
                        return;
                    }

                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>Removing...';

                    try {
                        const res = await markUnmatchedProcessed(id);
                        if (!res.success) {
                            toast(res.message || 'Unable to dismiss the query.', 'danger');
                            return;
                        }

                        this.closest('tr')?.remove();
                        updateUnmatchedCount();
                        toast('Query dismissed from the training queue.');
                    } catch (error) {
                        toast('Network error', 'danger');
                    } finally {
                        if (document.body.contains(this)) {
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                        }
                    }
                });
            });

            document.querySelectorAll('.session-copy').forEach(button => {
                button.addEventListener('click', async function () {
                    const sessionId = this.dataset.session || '';
                    if (!sessionId) {
                        return;
                    }

                    try {
                        await copyText(sessionId);
                        toast('Session ID copied.');
                    } catch (error) {
                        toast('Unable to copy the session ID.', 'danger');
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
@endpush
