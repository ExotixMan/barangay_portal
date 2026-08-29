@extends('admin.layouts.admin')

@section('title', 'Announcement Management')
@section('page_title', 'Announcement Management')
@section('page_icon', 'fas fa-bullhorn')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/announcements.css') }}">
    <style>
        /* Announcement admin UX refresh — presentation only */
        .announcements-page {
            --ann-primary: #4338ca;
            --ann-primary-soft: #eef2ff;
            --ann-border: #e5e7eb;
            --ann-border-strong: #d8dee8;
            --ann-text: #172033;
            --ann-muted: #667085;
            --ann-soft: #f8fafc;
            --ann-success: #15803d;
            --ann-warning: #a16207;
            --ann-danger: #b42318;
        }

        .announcements-page .announcements-hero,
        .announcements-page .announcements-stats-row {
            display: none !important;
        }

        .announcements-page .announcements-panel {
            border: 1px solid var(--ann-border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
            overflow: hidden;
        }

        .announcements-page .announcements-filter-panel {
            margin-bottom: 18px;
        }

        .announcements-page .announcements-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--ann-border);
            background: #fff;
        }

        .announcements-page .announcements-panel-title {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-width: 0;
        }

        .announcements-page .announcements-panel-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
            background: var(--ann-primary-soft);
            color: var(--ann-primary);
        }

        .announcements-page .announcements-panel-title h3 {
            margin: 0;
            color: var(--ann-text);
            font-size: .98rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .announcements-page .section-kicker,
        .announcements-page .panel-copy {
            display: none;
        }

        .announcements-page .announcements-panel-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .announcements-page .announcements-filter-body {
            padding: 14px 18px 16px;
        }

        .announcements-page .announcements-filter-toolbar {
            display: grid;
            grid-template-columns: minmax(260px, 1.8fr) repeat(3, minmax(140px, .7fr)) auto;
            gap: 10px;
            align-items: center;
        }

        .announcements-page .announcements-search-group {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .announcements-page .announcements-search-field {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .announcements-page .announcements-search-field > i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #98a2b3;
            pointer-events: none;
        }

        .announcements-page .announcements-search-field .form-control {
            padding-left: 38px;
        }

        .announcements-page .form-control,
        .announcements-page .form-select {
            min-height: 42px;
            border-color: var(--ann-border-strong);
            border-radius: 10px;
            color: var(--ann-text);
            font-size: .86rem;
            box-shadow: none;
        }

        .announcements-page .form-control:focus,
        .announcements-page .form-select:focus {
            border-color: var(--ann-primary);
            box-shadow: 0 0 0 .2rem rgba(67, 56, 202, .09);
        }

        .announcements-page .btn {
            min-height: 40px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .82rem;
        }

        .announcements-page .btn i {
            margin-right: .38rem;
        }

        .announcements-page .icon-only i,
        .announcements-page .btn-close i {
            margin-right: 0;
        }

        .announcements-page .announcements-bulk-row {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--ann-border);
        }

        .announcements-page .selected-count {
            color: var(--ann-muted);
            font-size: .78rem;
            font-weight: 700;
        }

        .announcements-page table {
            margin: 0;
        }

        .announcements-page .table thead th {
            padding-top: 11px;
            padding-bottom: 11px;
            border-bottom: 1px solid var(--ann-border);
            background: #f8fafc;
            color: #475467;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .announcements-page .table tbody td {
            padding-top: 12px;
            padding-bottom: 12px;
            border-color: #eef1f5;
            color: var(--ann-text);
            font-size: .83rem;
            vertical-align: middle;
        }

        .announcements-page .table tbody tr:hover {
            background: #fafbff;
        }

        .announcements-page .announcement-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            overflow: hidden;
            border: 1px solid var(--ann-border);
            border-radius: 10px;
            background: var(--ann-soft);
        }

        .announcements-page .announcement-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .announcements-page .views-badge,
        .announcements-page .date-badge,
        .announcements-page .announcements-count-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #475467;
            font-size: .75rem;
            font-weight: 700;
        }

        .announcements-page .table .badge {
            padding: .42rem .58rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 800;
        }

        .announcements-page .action-cluster {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .announcements-page .action-view-btn {
            min-height: 34px;
            padding: .38rem .65rem;
            border-color: #d0d5dd;
            color: #344054;
            background: #fff;
        }

        .announcements-page .action-more-toggle {
            width: 34px;
            min-height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-color: #d0d5dd;
            color: #475467;
            background: #fff;
        }

        .announcements-page .action-more-toggle i {
            margin: 0;
        }

        .announcements-page .action-menu {
            min-width: 240px;
            padding: 7px;
            border: 1px solid var(--ann-border);
            border-radius: 12px;
            box-shadow: 0 16px 38px rgba(16, 24, 40, .14);
        }

        .announcements-page .action-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 40px;
            padding: 8px 10px;
            border-radius: 8px;
            color: #344054;
            font-size: .8rem;
            font-weight: 700;
        }

        .announcements-page .action-menu .dropdown-item:hover {
            background: #f8fafc;
        }

        .announcements-page .action-menu .dropdown-item i {
            width: 16px;
            color: #667085;
            text-align: center;
        }

        .announcements-page .action-menu .dropdown-item.text-danger i {
            color: var(--ann-danger);
        }

        .announcements-page .action-menu form {
            margin: 0;
        }

        /* Consistent modal system */
        .announcements-page .modal-dialog {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .announcements-page .modal-content {
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .20);
        }

        .announcements-page .modal-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--ann-border);
            background: #fff;
        }

        .announcements-page .modal-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .announcements-page .modal-heading-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 11px;
            background: var(--ann-primary-soft);
            color: var(--ann-primary);
        }

        .announcements-page .modal-heading-copy {
            min-width: 0;
        }

        .announcements-page .modal-title {
            margin: 0;
            color: var(--ann-text);
            font-size: 1rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .announcements-page .modal-subtitle {
            display: block;
            margin-top: 3px;
            color: var(--ann-muted);
            font-size: .76rem;
            font-weight: 500;
            line-height: 1.45;
        }

        .announcements-page .modal-header .btn-close {
            width: 32px;
            height: 32px;
            padding: 0;
            margin: 0;
            flex: 0 0 auto;
            border-radius: 9px;
            background-size: 12px;
            opacity: .65;
        }

        .announcements-page .modal-header .btn-close:hover {
            background-color: #f2f4f7;
            opacity: 1;
        }

        .announcements-page .modal-body {
            padding: 18px 20px;
            background: #f8fafc;
        }

        .announcements-page .modal-form-intro {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            color: var(--ann-muted);
            font-size: .76rem;
        }

        .announcements-page .form-step-card,
        .announcements-page .detail-section-card {
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid var(--ann-border);
            border-radius: 14px;
            background: #fff;
        }

        .announcements-page .form-step-card:last-child,
        .announcements-page .detail-section-card:last-child {
            margin-bottom: 0;
        }

        .announcements-page .form-section-heading {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 14px;
        }

        .announcements-page .section-step {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: var(--ann-primary-soft);
            color: var(--ann-primary);
            font-size: .76rem;
            font-weight: 900;
        }

        .announcements-page .form-section-heading h6,
        .announcements-page .detail-section-title {
            margin: 0;
            color: var(--ann-text);
            font-size: .87rem;
            font-weight: 850;
        }

        .announcements-page .form-section-heading p {
            margin: 2px 0 0;
            color: var(--ann-muted);
            font-size: .72rem;
            line-height: 1.45;
        }

        .announcements-page .modal .form-label {
            margin-bottom: 6px;
            color: #344054;
            font-size: .76rem;
            font-weight: 800;
        }

        .announcements-page .modal .form-label > i {
            width: 15px;
            margin-right: 5px;
            color: #98a2b3;
            font-size: .74rem;
            text-align: center;
        }

        .announcements-page .modal .form-control,
        .announcements-page .modal .form-select {
            min-height: 44px;
            border-radius: 10px;
            background-color: #fff;
        }

        .announcements-page .modal textarea.form-control {
            min-height: auto;
            line-height: 1.55;
            resize: vertical;
        }

        .announcements-page .field-hint,
        .announcements-page .modal .text-muted.small,
        .announcements-page .modal small.text-muted {
            display: block;
            margin-top: 5px;
            color: var(--ann-muted) !important;
            font-size: .7rem;
            line-height: 1.45;
        }

        .announcements-page .publish-setting-card {
            height: 100%;
            padding: 12px 13px;
            border: 1px solid var(--ann-border);
            border-radius: 11px;
            background: #fbfcfe;
        }

        .announcements-page .publish-setting-card .form-check {
            margin: 0;
            padding-left: 0;
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .announcements-page .publish-setting-card .form-check-input {
            width: 2.1rem;
            height: 1.1rem;
            margin: 2px 0 0;
            flex: 0 0 auto;
        }

        .announcements-page .upload-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px;
            border: 1px dashed #cfd6e3;
            border-radius: 12px;
            background: #fbfcfe;
        }

        .announcements-page .upload-card-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 10px;
            background: var(--ann-primary-soft);
            color: var(--ann-primary);
        }

        .announcements-page .image-preview,
        .announcements-page .modal-image-hero {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid var(--ann-border);
        }

        .announcements-page .photo-view-btn {
            padding: 0;
            border: 0;
            background: transparent;
        }

        .announcements-page .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 20px;
            border-top: 1px solid var(--ann-border);
            background: #fff;
        }

        .announcements-page .modal-footer .btn {
            min-width: 112px;
        }

        .announcements-page .view-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .announcements-page .view-summary-item {
            padding: 11px 12px;
            border: 1px solid var(--ann-border);
            border-radius: 11px;
            background: #fff;
        }

        .announcements-page .view-summary-label {
            display: block;
            margin-bottom: 3px;
            color: var(--ann-muted);
            font-size: .67rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .announcements-page .view-summary-value {
            display: block;
            color: var(--ann-text);
            font-size: .82rem;
            font-weight: 800;
            line-height: 1.35;
        }

        .announcements-page .announcement-view-title {
            margin: 0 0 8px;
            color: var(--ann-text);
            font-size: 1.2rem;
            font-weight: 850;
            line-height: 1.35;
        }

        .announcements-page .announcement-content-box {
            padding: 14px;
            border: 1px solid var(--ann-border);
            border-radius: 11px;
            background: #fff;
            color: #344054;
            font-size: .84rem;
            line-height: 1.65;
            white-space: normal;
        }

        /* Bulk notify modal */
        .announcements-page #bulkNotifyResidentsModal .modal-form-grid > .col-12 > hr {
            margin-top: 4px !important;
            border-color: var(--ann-border);
            opacity: 1;
        }

        .announcements-page #bulkNotifyResidentsModal .modal-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--ann-text);
            font-size: .88rem;
            font-weight: 850;
        }

        .announcements-page .notification-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .announcements-page .notification-summary-card {
            padding: 10px 11px;
            border: 1px solid var(--ann-border);
            border-radius: 10px;
            background: #fff;
        }

        .announcements-page .notification-summary-card span,
        .announcements-page .sms-credit-label {
            display: block;
            color: var(--ann-muted);
            font-size: .68rem;
            font-weight: 700;
        }

        .announcements-page .notification-summary-card strong {
            display: block;
            margin-top: 2px;
            color: var(--ann-text);
            font-size: 1rem;
            font-weight: 850;
        }

        .announcements-page .resident-picker-list {
            max-height: 230px;
            overflow-y: auto;
            padding: 6px;
            border: 1px solid var(--ann-border);
            border-radius: 11px;
            background: #fff;
        }

        .announcements-page .resident-picker-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 9px;
            border-radius: 8px;
            cursor: pointer;
        }

        .announcements-page .resident-picker-item:hover {
            background: #f8fafc;
        }

        .announcements-page .resident-picker-item strong,
        .announcements-page .resident-picker-item small {
            display: block;
        }

        .announcements-page .resident-picker-item strong {
            color: #344054;
            font-size: .78rem;
        }

        .announcements-page .resident-picker-item small {
            margin-top: 2px;
            color: var(--ann-muted);
            font-size: .68rem;
        }

        .announcements-page .notification-preview-box,
        .announcements-page .sms-credit-box,
        .announcements-page .notification-warning-box {
            border: 1px solid var(--ann-border);
            border-radius: 11px;
            background: #fff;
        }

        .announcements-page .notification-preview-box,
        .announcements-page .sms-credit-box {
            padding: 13px;
        }

        .announcements-page .notification-warning-box {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 11px 12px;
            background: #fffbeb;
            border-color: #fde68a;
            color: #854d0e;
            font-size: .74rem;
        }

        .announcements-page .notification-preview-header,
        .announcements-page .sms-credit-main,
        .announcements-page .sms-credit-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .announcements-page .notification-preview-header {
            margin-bottom: 8px;
            color: #475467;
            font-size: .73rem;
            font-weight: 800;
        }

        .announcements-page .notification-preview-box p {
            margin: 0;
            color: #344054;
            font-size: .82rem;
            line-height: 1.55;
            white-space: pre-wrap;
        }

        .announcements-page .sms-credit-main strong {
            display: block;
            margin-top: 2px;
            color: var(--ann-text);
            font-size: 1.1rem;
        }

        .announcements-page .sms-credit-main small {
            margin-top: 2px;
        }

        .announcements-page .sms-credit-status {
            padding: .38rem .55rem;
            border-radius: 999px;
            background: #f2f4f7;
            color: #475467;
            font-size: .68rem;
            font-weight: 800;
        }

        .announcements-page .sms-credit-status.is-ok {
            background: #ecfdf3;
            color: #027a48;
        }

        .announcements-page .sms-credit-status.is-danger {
            background: #fef3f2;
            color: #b42318;
        }

        .announcements-page .sms-credit-details {
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-top: 11px;
            padding-top: 10px;
            border-top: 1px solid var(--ann-border);
            color: #475467;
            font-size: .7rem;
        }

        .announcements-page .resident-picker-search > i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #98a2b3;
            pointer-events: none;
        }

        .announcements-page .resident-picker-search .form-control {
            padding-left: 38px;
        }

        .announcements-page .announcement-icon .photo-view-btn {
            width: 100%;
            height: 100%;
        }

        .announcements-page .photo-view-btn {
            position: relative;
            display: block;
        }

        .announcements-page .zoom-hint {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            color: var(--ann-muted);
            font-size: .69rem;
            font-weight: 700;
        }

        .announcements-page .image-zoom-modal .modal-body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 360px;
            padding: 16px;
            background: #101828;
        }

        .announcements-page .zoomed-image {
            display: block;
            max-width: 100%;
            max-height: 72vh;
            object-fit: contain;
            border-radius: 10px;
        }

        @media (max-width: 1199.98px) {
            .announcements-page .announcements-filter-toolbar {
                grid-template-columns: minmax(240px, 1.5fr) repeat(2, minmax(140px, .7fr));
            }

            .announcements-page .filter-submit-btn {
                width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            .announcements-page .announcements-panel-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .announcements-page .announcements-panel-actions,
            .announcements-page .announcements-panel-actions .btn,
            .announcements-page .announcements-panel-actions a.btn {
                width: 100%;
            }

            .announcements-page .announcements-panel-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .announcements-page .announcements-panel-actions .btn-primary:first-child {
                grid-column: 1 / -1;
            }

            .announcements-page .announcements-filter-toolbar {
                grid-template-columns: 1fr 1fr;
            }

            .announcements-page .announcements-search-group {
                grid-column: 1 / -1;
            }

            .announcements-page .filter-submit-btn {
                grid-column: 1 / -1;
            }

            .announcements-page .modal-dialog {
                margin: .6rem;
            }

            .announcements-page .modal-header,
            .announcements-page .modal-body,
            .announcements-page .modal-footer {
                padding-left: 14px;
                padding-right: 14px;
            }

            .announcements-page .view-summary,
            .announcements-page .notification-summary-grid {
                grid-template-columns: 1fr 1fr;
            }

            .announcements-page .modal-footer {
                flex-direction: column-reverse;
            }

            .announcements-page .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .announcements-page .announcements-filter-toolbar {
                grid-template-columns: 1fr;
            }

            .announcements-page .announcements-search-group {
                display: grid;
                grid-template-columns: 1fr auto;
            }

            .announcements-page .announcements-panel-actions {
                grid-template-columns: 1fr;
            }

            .announcements-page .announcements-panel-actions .btn-primary:first-child {
                grid-column: auto;
            }

            .announcements-page .view-summary,
            .announcements-page .notification-summary-grid {
                grid-template-columns: 1fr;
            }

            .announcements-page .upload-card {
                flex-direction: column;
            }
        }

        [data-theme="dark"] .announcements-page,
        body.dark-mode .announcements-page {
            --ann-border: #2b3445;
            --ann-border-strong: #3a4558;
            --ann-text: #f2f4f7;
            --ann-muted: #98a2b3;
            --ann-soft: #182230;
        }

        [data-theme="dark"] .announcements-page .announcements-panel,
        [data-theme="dark"] .announcements-page .announcements-panel-header,
        [data-theme="dark"] .announcements-page .modal-content,
        [data-theme="dark"] .announcements-page .modal-header,
        [data-theme="dark"] .announcements-page .modal-footer,
        [data-theme="dark"] .announcements-page .form-step-card,
        [data-theme="dark"] .announcements-page .detail-section-card,
        [data-theme="dark"] .announcements-page .view-summary-item,
        [data-theme="dark"] .announcements-page .notification-summary-card,
        [data-theme="dark"] .announcements-page .resident-picker-list,
        [data-theme="dark"] .announcements-page .notification-preview-box,
        [data-theme="dark"] .announcements-page .sms-credit-box,
        body.dark-mode .announcements-page .announcements-panel,
        body.dark-mode .announcements-page .announcements-panel-header,
        body.dark-mode .announcements-page .modal-content,
        body.dark-mode .announcements-page .modal-header,
        body.dark-mode .announcements-page .modal-footer,
        body.dark-mode .announcements-page .form-step-card,
        body.dark-mode .announcements-page .detail-section-card,
        body.dark-mode .announcements-page .view-summary-item,
        body.dark-mode .announcements-page .notification-summary-card,
        body.dark-mode .announcements-page .resident-picker-list,
        body.dark-mode .announcements-page .notification-preview-box,
        body.dark-mode .announcements-page .sms-credit-box {
            background: #111827;
        }

        [data-theme="dark"] .announcements-page .modal-body,
        body.dark-mode .announcements-page .modal-body {
            background: #0f172a;
        }

        [data-theme="dark"] .announcements-page .form-control,
        [data-theme="dark"] .announcements-page .form-select,
        [data-theme="dark"] .announcements-page .publish-setting-card,
        [data-theme="dark"] .announcements-page .upload-card,
        body.dark-mode .announcements-page .form-control,
        body.dark-mode .announcements-page .form-select,
        body.dark-mode .announcements-page .publish-setting-card,
        body.dark-mode .announcements-page .upload-card {
            background-color: #182230;
            border-color: #3a4558;
            color: #f2f4f7;
        }
    </style>
@endpush

@section('content')
            <div class="announcements-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif



            <!-- Filters - UX Improved -->
            <div class="announcements-panel announcements-filter-panel">
                <form method="GET" action="{{ route('admin.announcements.index') }}" id="searchForm">
                    <div class="announcements-panel-header">
                        <div class="announcements-panel-title">
                            <div class="announcements-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-bullhorn"></i> Announcement Directory</div>
                                <h3>Announcements</h3>
                                <p class="panel-copy">Search, filter, publish, and manage resident-facing announcements.</p>
                            </div>
                        </div>

                        <div class="announcements-panel-actions">
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                <i class="fas fa-plus"></i>Add Announcement
                            </button>
                            @endadmin_can
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#bulkNotifyResidentsModal">
                                <i class="fas fa-paper-plane"></i>Notify Residents
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="announcements-filter-body">
                        <div class="announcements-filter-toolbar">
                            <div class="announcements-search-group">
                                <div class="announcements-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, content, or category..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search announcements">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted bin</option>
                            </select>

                            <select name="category" class="form-select" aria-label="Filter by category">
                                <option value="">All categories</option>
                                <option value="important" {{ request('category') == 'important' ? 'selected' : '' }}>Important</option>
                                <option value="events" {{ request('category') == 'events' ? 'selected' : '' }}>Events</option>
                                <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>Health</option>
                                <option value="services" {{ request('category') == 'services' ? 'selected' : '' }}>Services</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>

                            <select name="featured" class="form-select" aria-label="Filter by featured status">
                                <option value="">All featured</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured only</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not featured</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_announcements'))
                        <div class="announcements-bulk-row">
                            <div class="selected-count" id="selectedAnnouncementsCount">
                                <i class="fas fa-square-check me-1"></i>No announcements selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_announcements'))
            <form id="bulkForm" method="POST" action="{{ route('admin.announcements.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Announcements Table - UX Improved -->
            <div class="announcements-panel">
                <div class="announcements-panel-header">
                    <div class="announcements-panel-title">
                        <div class="announcements-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Announcement Records</div>
                            <h3>Announcements List</h3>
                            <p class="panel-copy">Sort records, view full details, edit drafts, and archive outdated announcements.</p>
                        </div>
                    </div>
                    <div class="announcements-panel-actions">
                        <span class="announcements-count-pill"><i class="fas fa-bullhorn"></i>{{ isset($announcements) ? $announcements->total() : 0 }} announcements</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="announcementsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Category
                                            @if(request('sort') == 'category')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_featured', 'direction' => request('sort') === 'is_featured' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Featured
                                            @if(request('sort') == 'is_featured')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'direction' => request('sort') === 'views' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Views
                                            @if(request('sort') == 'views')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'direction' => request('sort') === 'published_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Published At
                                            @if(request('sort') == 'published_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
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
                                @forelse($announcements as $ann)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $ann->id }}" class="announcement-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="announcement-icon me-2">
                                                @if($ann->image)
                                                    <button type="button" class="photo-view-btn" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View announcement photo">
                                                        <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="zoomable-image">
                                                    </button>
                                                @else
                                                    <i class="fas fa-bullhorn text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($ann->title, 40) }}</div>
                                                <small class="text-muted d-lg-none">{{ \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($ann->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="fas fa-star me-1"></i>Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="views-badge">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        @if($ann->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($ann->status == 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @elseif($ann->status == 'draft')
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-cluster">
                                            <button type="button"
                                                    class="btn btn-sm action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $ann->id }}"
                                                    title="View announcement details">
                                                <i class="fas fa-eye"></i>View
                                            </button>

                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn-sm action-more-toggle icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions"
                                                        aria-label="More actions for {{ $ann->title }}">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end action-menu">
                                                    @if(!$ann->deleted_at)
                                                    @admin_can('update_announcements')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal{{ $ann->id }}">
                                                        <i class="fas fa-pen"></i><span>Edit announcement</span>
                                                    </button>
                                                    @endadmin_can
                                                    @endif

                                                    @if(!$ann->deleted_at && $ann->status == 'published' && auth('admin')->user()->hasPermission('feature_announcements'))
                                                    <form method="POST" action="{{ route('admin.announcements.toggle-feature', $ann->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('{{ $ann->is_featured ? 'Remove featured status?' : 'Mark as featured?' }}')">
                                                            <i class="fas fa-star"></i>
                                                            <span>{{ $ann->is_featured ? 'Remove featured' : 'Mark as featured' }}</span>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    @admin_can('delete_announcements')
                                                    @if($ann->deleted_at)
                                                    <form method="POST" action="{{ route('admin.announcements.restore', $ann->id) }}" onsubmit="return confirmDelete(event, 'Restore this announcement from the deleted bin?')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-rotate-left"></i><span>Restore announcement</span>
                                                        </button>
                                                    </form>
                                                    @elseif($ann->status == 'archived')
                                                    <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" onsubmit="return confirmDelete(event, 'Publish this archived announcement again?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="title" value="{{ $ann->title }}">
                                                        <textarea name="content" class="d-none">{{ $ann->content }}</textarea>
                                                        <input type="hidden" name="category" value="{{ $ann->category }}">
                                                        <input type="hidden" name="status" value="published">
                                                        <input type="hidden" name="published_at" value="{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}">
                                                        @if($ann->is_featured)
                                                            <input type="hidden" name="is_featured" value="1">
                                                        @endif
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-globe"></i><span>Publish again</span>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" onsubmit="return confirmDelete(event, 'Archive this announcement?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-box-archive"></i><span>Archive announcement</span>
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
                                                <div class="empty-state-icon"><i class="fas fa-bullhorn"></i></div>
                                                <h4>No announcements found</h4>
                                                <p>No announcement records match the current search and filters.</p>
                                                @admin_can('create_announcements')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                                    <i class="fas fa-plus"></i>Add Announcement
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
                    @if(isset($announcements) && $announcements->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $announcements->firstItem() ?? 0 }}-{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $announcements->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Announcement Modal with Validation - Only if user has create_announcements permission -->
            @admin_can('create_announcements')
            <div class="modal fade announcement-form-modal" id="addAnnouncementModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-bullhorn"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Announcement</h5>
                                    <span class="modal-subtitle">Create a resident-facing announcement. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" id="addAnnouncementForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="modal-form-intro">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Use a clear title and message first, then choose publishing settings and optional media.</span>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Announcement Content</h6>
                                            <p>Write the title and public message residents will read.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="Example: Scheduled Water Interruption" required>
                                            <small class="field-hint">Keep it short and specific so residents understand the topic at a glance.</small>
                                            @if(session('form_type') == 'add')
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('content') is-invalid @enderror @endif"
                                                      name="content" rows="7" placeholder="Write the announcement details, dates, requirements, and contact information..." required>{{ session('form_type') == 'add' ? old('content') : '' }}</textarea>
                                            <small class="field-hint">Put the most important information first and keep instructions easy to scan.</small>
                                            @if(session('form_type') == 'add')
                                                @error('content')
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
                                            <h6>Publishing Settings</h6>
                                            <p>Choose where the announcement belongs and when it should be visible.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-folder"></i>Category <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('category') is-invalid @enderror @endif" name="category" required>
                                                <option value="">Select category</option>
                                                <option value="important" {{ (session('form_type') == 'add' && old('category') == 'important') ? 'selected' : '' }}>Important</option>
                                                <option value="events" {{ (session('form_type') == 'add' && old('category') == 'events') ? 'selected' : '' }}>Events</option>
                                                <option value="health" {{ (session('form_type') == 'add' && old('category') == 'health') ? 'selected' : '' }}>Health</option>
                                                <option value="services" {{ (session('form_type') == 'add' && old('category') == 'services') ? 'selected' : '' }}>Services</option>
                                                <option value="other" {{ (session('form_type') == 'add' && old('category') == 'other') ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-circle-check"></i>Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" name="status" required>
                                                <option value="">Select status</option>
                                                <option value="published" {{ (session('form_type') == 'add' && old('status') == 'published') ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ (session('form_type') == 'add' && old('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                                <option value="archived" {{ (session('form_type') == 'add' && old('status') == 'archived') ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-calendar-days"></i>Published Date</label>
                                            <input type="datetime-local" class="form-control @if(session('form_type') == 'add') @error('published_at') is-invalid @enderror @endif"
                                                   name="published_at" value="{{ session('form_type') == 'add' ? old('published_at') : '' }}">
                                            <small class="field-hint">Leave empty for immediate publishing.</small>
                                            @if(session('form_type') == 'add')
                                                @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-star"></i>Featured</label>
                                            <div class="publish-setting-card">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input @if(session('form_type') == 'add') @error('is_featured') is-invalid @enderror @endif"
                                                           name="is_featured" id="is_featured" value="1" {{ (session('form_type') == 'add' && old('is_featured')) ? 'checked' : '' }}>
                                                    <div>
                                                        <label class="form-check-label fw-semibold" for="is_featured">Highlight this announcement</label>
                                                        <small class="field-hint mb-0">Use featured status only for notices that need extra visibility.</small>
                                                    </div>
                                                </div>
                                                @if(session('form_type') == 'add')
                                                    @error('is_featured')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Announcement Image</h6>
                                            <p>Add an optional image when it helps residents understand the notice faster.</p>
                                        </div>
                                    </div>
                                    <div class="upload-card">
                                        <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                        <div class="flex-grow-1 min-w-0">
                                            <label class="form-label">Featured Image</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif"
                                                   name="image" accept="image/*" onchange="previewImage(this, 'addImagePreview')">
                                            <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                            @if(session('form_type') == 'add')
                                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                            <div id="addImagePreview" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Cancel
                            </button>
                            <button type="submit" form="addAnnouncementForm" class="btn btn-primary" id="submitAddForm">
                                <i class="fas fa-save"></i>Save Announcement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Announcement Modals with Validation - Only if user has update_announcements permission -->
            @if(auth('admin')->user()->hasPermission('update_announcements'))
                @foreach($announcements as $ann)
                <div class="modal fade announcement-form-modal" id="editAnnouncementModal{{ $ann->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Announcement</h5>
                                        <span class="modal-subtitle">Update the content, publishing settings, or image for this announcement.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" enctype="multipart/form-data" id="editAnnouncementForm{{ $ann->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $ann->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Announcement Content</h6>
                                                <p>Review the resident-facing title and message.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $ann->id ? old('title', $ann->title) : $ann->title }}" required>
                                                <small class="field-hint">Keep the title specific and easy to recognize in the announcement list.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Content <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('content') is-invalid @enderror @endif"
                                                          name="content" rows="7" required>{{ session('form_type') == 'edit_' . $ann->id ? old('content', $ann->content) : $ann->content }}</textarea>
                                                <small class="field-hint">Check dates, requirements, locations, and contact details before saving.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">2</div>
                                            <div>
                                                <h6>Publishing Settings</h6>
                                                <p>Change category, visibility, schedule, or featured status.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-folder"></i>Category <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('category') is-invalid @enderror @endif" name="category" required>
                                                    <option value="">Select category</option>
                                                    <option value="important" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'important' ? 'selected' : '' }}>Important</option>
                                                    <option value="events" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'events' ? 'selected' : '' }}>Events</option>
                                                    <option value="health" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'health' ? 'selected' : '' }}>Health</option>
                                                    <option value="services" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'services' ? 'selected' : '' }}>Services</option>
                                                    <option value="other" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-circle-check"></i>Status <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('status') is-invalid @enderror @endif" name="status" required>
                                                    <option value="">Select status</option>
                                                    <option value="published" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                    <option value="draft" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="archived" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                                </select>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-calendar-days"></i>Published Date</label>
                                                <input type="datetime-local" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('published_at') is-invalid @enderror @endif"
                                                       name="published_at" value="{{ session('form_type') == 'edit_' . $ann->id ? old('published_at', $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') : ($ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') }}">
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-star"></i>Featured</label>
                                                <div class="publish-setting-card">
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input @if(session('form_type') == 'edit_' . $ann->id) @error('is_featured') is-invalid @enderror @endif"
                                                               name="is_featured" id="edit_is_featured_{{ $ann->id }}" value="1"
                                                               {{ (session('form_type') == 'edit_' . $ann->id ? old('is_featured', $ann->is_featured) : $ann->is_featured) ? 'checked' : '' }}>
                                                        <div>
                                                            <label class="form-check-label fw-semibold" for="edit_is_featured_{{ $ann->id }}">Highlight this announcement</label>
                                                            <small class="field-hint mb-0">Keep this enabled only while the notice needs extra visibility.</small>
                                                        </div>
                                                    </div>
                                                    @if(session('form_type') == 'edit_' . $ann->id)
                                                        @error('is_featured')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Announcement Image</h6>
                                                <p>Leave the upload empty to keep the current image.</p>
                                            </div>
                                        </div>
                                        <div class="upload-card">
                                            <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                            <div class="flex-grow-1 min-w-0">
                                                <label class="form-label">Featured Image</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('image') is-invalid @enderror @endif"
                                                       name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $ann->id }}')">
                                                <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                                <div id="editImagePreview{{ $ann->id }}" class="mt-2">
                                                    @if($ann->image)
                                                    <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View current photo">
                                                        <img src="{{ asset($ann->image) }}" alt="Current image" class="image-preview zoomable-image">
                                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View current image</span>
                                                    </button>
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
                                <button type="submit" form="editAnnouncementForm{{ $ann->id }}" class="btn btn-primary" id="submitEditForm{{ $ann->id }}">
                                    <i class="fas fa-save"></i>Update Announcement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($announcements as $ann)
            <div class="modal fade announcement-view-modal" id="viewModal{{ $ann->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-bullhorn"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Announcement Details</h5>
                                    <span class="modal-subtitle">Review the published content and visibility information in one place.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Status</span>
                                    <span class="view-summary-value">
                                        @if($ann->status == 'published') Published
                                        @elseif($ann->status == 'draft') Draft
                                        @else Archived
                                        @endif
                                    </span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Category</span>
                                    <span class="view-summary-value">{{ ucfirst($ann->category) }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Visibility</span>
                                    <span class="view-summary-value">{{ $ann->is_featured ? 'Featured' : 'Standard' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Views</span>
                                    <span class="view-summary-value">{{ number_format($ann->views) }}</span>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="row g-3 align-items-start">
                                    @if($ann->image)
                                    <div class="col-12 col-lg-4">
                                        <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View full announcement photo">
                                            <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="modal-image-hero zoomable-image">
                                            <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View larger image</span>
                                        </button>
                                    </div>
                                    @endif

                                    <div class="col-12 {{ $ann->image ? 'col-lg-8' : '' }}">
                                        <h4 class="announcement-view-title">{{ $ann->title }}</h4>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span>
                                            @if($ann->status == 'published')
                                                <span class="badge bg-success-subtle text-success">Published</span>
                                            @elseif($ann->status == 'draft')
                                                <span class="badge bg-warning-subtle text-warning">Draft</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                            @endif
                                            @if($ann->is_featured)
                                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-star"></i>Featured</span>
                                            @endif
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <span class="view-summary-label">Published At</span>
                                                <span class="view-summary-value">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('F d, Y h:i A') : 'Not published' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <span class="view-summary-label">Created At</span>
                                                <span class="view-summary-value">{{ $ann->created_at ? $ann->created_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2"></i>Announcement Content</div>
                                <div class="announcement-content-box">{!! nl2br(e($ann->content)) !!}</div>
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

            @admin_can('create_announcements')
            <div class="modal fade announcement-form-modal notification-modal" id="bulkNotifyResidentsModal" tabindex="-1" aria-labelledby="bulkNotifyResidentsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('admin.announcements.bulkNotifyResidents') }}"
                              id="bulkNotifyResidentsForm"
                              onsubmit="return confirmBulkResidentNotification(event)">
                            @csrf
                            <input type="hidden" name="form_type" value="bulk_notify">

                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title" id="bulkNotifyResidentsModalLabel">Notify Residents</h5>
                                        <span class="modal-subtitle">Choose recipients, compose the message, review delivery estimates, then send.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Recipients &amp; Delivery</h6>
                                            <p>Choose the delivery channel and which residents should receive the notice.</p>
                                        </div>
                                    </div>

                                    <div class="notification-summary-grid mb-3">
                                        <div class="notification-summary-card">
                                            <span>Total residents</span>
                                            <strong>{{ number_format($residentNotificationStats['all'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>With email</span>
                                            <strong>{{ number_format($residentNotificationStats['email'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>With mobile</span>
                                            <strong>{{ number_format($residentNotificationStats['sms'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>Verified</span>
                                            <strong>{{ number_format($residentNotificationStats['verified'] ?? 0) }}</strong>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6">
                                            <label class="form-label"><i class="fas fa-paper-plane"></i>Send Through <span class="text-danger">*</span></label>
                                            <select name="channel" id="notifyChannel" class="form-select" required>
                                                <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS only</option>
                                                <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email only</option>
                                                <option value="both" {{ old('channel') === 'both' ? 'selected' : '' }}>SMS and Email</option>
                                            </select>
                                            <small class="field-hint">Choose SMS for urgent notices. Use email for longer details.</small>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <label class="form-label"><i class="fas fa-users"></i>Audience <span class="text-danger">*</span></label>
                                            <select name="audience" id="notifyAudience" class="form-select" required>
                                                <option value="all" {{ old('audience') === 'all' ? 'selected' : '' }}>All residents</option>
                                                <option value="selected" {{ old('audience') === 'selected' ? 'selected' : '' }}>Selected residents only</option>
                                                <option value="verified" {{ old('audience') === 'verified' ? 'selected' : '' }}>Verified residents</option>
                                                <option value="unverified" {{ old('audience') === 'unverified' ? 'selected' : '' }}>Unverified residents</option>
                                                <option value="senior" {{ old('audience') === 'senior' ? 'selected' : '' }}>Senior citizens</option>
                                                <option value="male" {{ old('audience') === 'male' ? 'selected' : '' }}>Male residents</option>
                                                <option value="female" {{ old('audience') === 'female' ? 'selected' : '' }}>Female residents</option>
                                                <option value="with_valid_id" {{ old('audience') === 'with_valid_id' ? 'selected' : '' }}>Residents with valid ID</option>
                                                <option value="email_ready" {{ old('audience') === 'email_ready' ? 'selected' : '' }}>Residents with email</option>
                                                <option value="sms_ready" {{ old('audience') === 'sms_ready' ? 'selected' : '' }}>Residents with mobile number</option>
                                            </select>
                                            <small class="field-hint">The system skips missing contact details that cannot use the selected channel.</small>
                                        </div>

                                        <div class="col-12" id="selectedResidentsBox" style="display: none;">
                                            <label class="form-label"><i class="fas fa-user-check"></i>Choose Residents <span class="text-danger">*</span></label>

                                            <div class="resident-picker-search mb-2 position-relative">
                                                <i class="fas fa-search"></i>
                                                <input type="text"
                                                       id="residentPickerSearch"
                                                       class="form-control"
                                                       placeholder="Search resident name, email, or mobile number...">
                                            </div>

                                            <div class="resident-picker-list">
                                                @forelse($residentsForNotification ?? [] as $resident)
                                                    @php
                                                        $residentFullName = trim(collect([
                                                            $resident->firstname ?? '',
                                                            $resident->middlename ?? '',
                                                            $resident->lastname ?? '',
                                                            $resident->suffix ?? '',
                                                        ])->filter()->implode(' '));

                                                        $searchText = strtolower($residentFullName . ' ' . ($resident->email ?? '') . ' ' . ($resident->contact ?? ''));
                                                    @endphp

                                                    <label class="resident-picker-item" data-search="{{ $searchText }}">
                                                        <input type="checkbox"
                                                               name="resident_ids[]"
                                                               value="{{ $resident->id }}"
                                                               {{ in_array($resident->id, old('resident_ids', [])) ? 'checked' : '' }}>
                                                        <span>
                                                            <strong>{{ $residentFullName ?: 'Resident #' . $resident->id }}</strong>
                                                            <small>{{ $resident->contact ?: 'No mobile' }} · {{ $resident->email ?: 'No email' }}</small>
                                                        </span>
                                                    </label>
                                                @empty
                                                    <div class="notification-warning-box">
                                                        <i class="fas fa-circle-info"></i>
                                                        <div>No residents found for manual selection.</div>
                                                    </div>
                                                @endforelse
                                            </div>

                                            <small class="field-hint" id="selectedResidentCounter">0 residents selected</small>

                                            @if(session('form_type') === 'bulk_notify')
                                                @error('resident_ids')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Compose Message</h6>
                                            <p>Start from a template or write a custom message, then review the subject and message body.</p>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-4">
                                            <label class="form-label"><i class="fas fa-wand-magic-sparkles"></i>Template</label>
                                            <select id="notifyTemplate" class="form-select">
                                                <option value="">Write custom message</option>
                                                <option value="ayuda">Ayuda Distribution</option>
                                                <option value="emergency">Emergency Advisory</option>
                                                <option value="documents">Document Release Reminder</option>
                                                <option value="event">Barangay Event</option>
                                            </select>
                                            <small class="field-hint">Templates remain editable after selection.</small>
                                        </div>

                                        <div class="col-12 col-lg-8">
                                            <label class="form-label"><i class="fas fa-envelope"></i>Email Subject</label>
                                            <input type="text"
                                                   name="subject"
                                                   id="notifySubject"
                                                   class="form-control @if(session('form_type') === 'bulk_notify') @error('subject') is-invalid @enderror @endif"
                                                   value="{{ session('form_type') === 'bulk_notify' ? old('subject') : '' }}"
                                                   placeholder="Example: Ayuda Distribution Notice">
                                            <small class="field-hint">Required only when sending email or both SMS and email.</small>
                                            @if(session('form_type') === 'bulk_notify')
                                                @error('subject')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-message"></i>Message <span class="text-danger">*</span></label>
                                            <textarea name="message"
                                                      id="notifyMessage"
                                                      rows="7"
                                                      class="form-control @if(session('form_type') === 'bulk_notify') @error('message') is-invalid @enderror @endif"
                                                      required
                                                      maxlength="1000"
                                                      placeholder="Write a clear message for residents...">{{ session('form_type') === 'bulk_notify' ? old('message') : '' }}</textarea>

                                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-2">
                                                <small class="field-hint mb-0">Available placeholders: <strong>{name}</strong>, <strong>{firstname}</strong>, <strong>{barangay}</strong></small>
                                                <small class="field-hint mb-0" id="notifyCharCounter">0 / 1000 characters</small>
                                            </div>

                                            @if(session('form_type') === 'bulk_notify')
                                                @error('message')
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
                                            <h6>Review &amp; Send</h6>
                                            <p>Preview the resident message and verify the estimated SMS credit usage before sending.</p>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="notification-preview-box">
                                                <div class="notification-preview-header">
                                                    <span><i class="fas fa-eye"></i> Resident preview</span>
                                                    <span id="notifyPreviewChannel">SMS</span>
                                                </div>
                                                <p id="notifyPreviewText">Your message preview will appear here.</p>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="sms-credit-box"
                                                 id="smsCreditBox"
                                                 data-credit-balance="{{ $semaphoreAccount['credit_balance'] ?? 0 }}">
                                                <div class="sms-credit-main">
                                                    <div>
                                                        <span class="sms-credit-label">Semaphore SMS Credits</span>
                                                        <strong id="smsAvailableCredits">{{ number_format((float) ($semaphoreAccount['credit_balance'] ?? 0), 2) }}</strong>
                                                        <small>
                                                            {{ $semaphoreAccount['success'] ?? false
                                                                ? 'Live balance from Semaphore account.'
                                                                : ($semaphoreAccount['message'] ?? 'Unable to load Semaphore balance.') }}
                                                        </small>
                                                    </div>

                                                    <div class="sms-credit-status" id="smsCreditStatusBadge">Checking estimate</div>
                                                </div>

                                                <div class="sms-credit-details">
                                                    <span>SMS recipients: <strong id="smsEstimatedRecipients">0</strong></span>
                                                    <span>SMS segments: <strong id="smsEstimatedSegments">0</strong></span>
                                                    <span>Credits needed: <strong id="smsEstimatedCredits">0</strong></span>
                                                    <span>After send: <strong id="smsRemainingCredits">0</strong></span>
                                                </div>

                                                <input type="hidden" name="estimated_sms_credits" id="estimatedSmsCreditsInput" value="0">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="notification-warning-box">
                                                <i class="fas fa-circle-info"></i>
                                                <div>
                                                    <strong>Review before sending.</strong>
                                                    Bulk notifications are sent immediately and each result will be saved in notification logs.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>Send Notification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Global Image Zoom Modal -->
            <div class="modal fade image-zoom-modal announcement-view-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-image"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Announcement Photo</h5>
                                    <span class="modal-subtitle">Large preview of the uploaded announcement image.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="zoomed-image" alt="Announcement photo preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addAnnouncementModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal{{ $editId }}'));
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
            const checkboxes = document.querySelectorAll('.announcement-checkbox:checked');
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
                        text: 'Please select at least one announcement to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one announcement to archive.');
                }
                return;
            }

            // Clear old ids so repeated operations do not duplicate inputs.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected announcement(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} announcement(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof refreshAnnouncementSelection === 'function') {
                refreshAnnouncementSelection();
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

        // Preview image before upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('${e.target.result}')" title="View selected photo">
                            <img src="${e.target.result}" class="announcement-image-preview zoomable-image" alt="Selected photo preview">
                            <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                        </button>`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
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
        });

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
            const zoomModalEl = document.getElementById('globalImageZoomModal');

            if (!zoomImage || !zoomModalEl || !imageUrl) {
                return;
            }

            zoomImage.src = imageUrl;
            const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalEl);
            zoomModal.show();
        }
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

        function attachAnnouncementValidation(form) {
            if (!form) return;
            const title    = form.querySelector('[name="title"]');
            const content  = form.querySelector('[name="content"]');
            const category = form.querySelector('[name="category"]');
            const status   = form.querySelector('[name="status"]');
            const image    = form.querySelector('[name="image"]');

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (content) {
                content.addEventListener('input', function() { cf(this); }, {once: true});
                content.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Content is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Content must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (category) category.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a category.'); else cf(this);
            });
            if (status) status.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a status.'); else cf(this);
            });
            if (image) image.addEventListener('change', function() {
                if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                    sf(this, 'Image must be less than 5MB.'); this.value = '';
                } else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                }
                if (content) {
                    if (!content.value.trim()) { sf(content, 'Content is required.'); valid = false; }
                    else if (content.value.trim().length < 10) { sf(content, 'Content must be at least 10 characters.'); valid = false; }
                }
                if (category && !category.value) { sf(category, 'Please select a category.'); valid = false; }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); valid = false;
                }
                if (!valid) e.preventDefault();
            });
        }

        attachAnnouncementValidation(document.getElementById('addAnnouncementForm'));
        document.querySelectorAll('[id^="editAnnouncementForm"]').forEach(attachAnnouncementValidation);
    });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
<script>
        function refreshAnnouncementSelection() {
            const checkboxes = Array.from(document.querySelectorAll('.announcement-checkbox'));
            const checked = checkboxes.filter(cb => cb.checked);
            const selectAll = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedAnnouncementsCount');

            if (selectAll) {
                selectAll.checked = checked.length > 0 && checked.length === checkboxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            if (selectedCount) {
                selectedCount.innerHTML = checked.length > 0
                    ? `<i class="fas fa-square-check me-1"></i>${checked.length} announcement${checked.length === 1 ? '' : 's'} selected`
                    : '<i class="fas fa-square-check me-1"></i>No announcements selected';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.announcement-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', refreshAnnouncementSelection);
            });
            refreshAnnouncementSelection();
        });
    </script>
    @if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bulkNotifyModal = new bootstrap.Modal(document.getElementById('bulkNotifyResidentsModal'));
        bulkNotifyModal.show();
    });
</script>
@endif

@if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('bulkNotifyResidentsModal');
    if (modalEl) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
});
</script>
@endif

<script>
window.smsAudienceCounts = @json($smsAudienceCounts ?? []);
window.semaphoreCreditBalance = Number(@json($semaphoreAccount['credit_balance'] ?? 0));

document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('notifyTemplate');
    const subjectInput = document.getElementById('notifySubject');
    const messageInput = document.getElementById('notifyMessage');
    const channelSelect = document.getElementById('notifyChannel');
    const audienceSelect = document.getElementById('notifyAudience');
    const counter = document.getElementById('notifyCharCounter');
    const previewText = document.getElementById('notifyPreviewText');
    const previewChannel = document.getElementById('notifyPreviewChannel');
    const selectedResidentsBox = document.getElementById('selectedResidentsBox');
    const residentPickerSearch = document.getElementById('residentPickerSearch');
    const selectedResidentCounter = document.getElementById('selectedResidentCounter');

    const smsEstimatedRecipients = document.getElementById('smsEstimatedRecipients');
    const smsEstimatedSegments = document.getElementById('smsEstimatedSegments');
    const smsEstimatedCredits = document.getElementById('smsEstimatedCredits');
    const smsRemainingCredits = document.getElementById('smsRemainingCredits');
    const smsCreditStatusBadge = document.getElementById('smsCreditStatusBadge');
    const estimatedSmsCreditsInput = document.getElementById('estimatedSmsCreditsInput');

    const templates = {
        ayuda: {
            subject: 'Ayuda Distribution Notice',
            message: 'Magandang araw, {name}. May ayuda distribution po ang Barangay Hulong Duhat sa [DATE], [TIME], sa [VENUE]. Magdala po ng valid ID at requirements. Salamat po.'
        },
        emergency: {
            subject: 'Emergency Barangay Advisory',
            message: 'Magandang araw, {name}. Paalala mula sa Barangay Hulong Duhat: [WRITE EMERGENCY DETAILS HERE]. Para sa agarang tulong, makipag-ugnayan sa barangay office. Ingat po.'
        },
        documents: {
            subject: 'Barangay Document Release Reminder',
            message: 'Magandang araw, {name}. Ang inyong barangay document/request ay maaari nang i-follow up o kunin sa barangay office. Magdala po ng valid ID at reference number kung meron. Salamat po.'
        },
        event: {
            subject: 'Barangay Event Announcement',
            message: 'Magandang araw, {name}. Inaanyayahan po kayo sa barangay event: [EVENT NAME] sa [DATE], [TIME], sa [VENUE]. Kita-kits po.'
        }
    };

    function smsLength(message) {
        return Array.from(message || '').length;
    }

    function smsSegments(message) {
        const length = smsLength(message);
        return Math.max(1, Math.ceil(length / 160));
    }

    function selectedResidentCount() {
        return document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    }

    function estimatedSmsRecipients() {
        const channel = channelSelect?.value || 'sms';

        if (channel === 'email') {
            return 0;
        }

        const audience = audienceSelect?.value || 'all';

        if (audience === 'selected') {
            return selectedResidentCount();
        }

        return Number(window.smsAudienceCounts?.[audience] || 0);
    }

    function refreshSelectedResidentBox() {
        if (!audienceSelect || !selectedResidentsBox) return;

        selectedResidentsBox.style.display = audienceSelect.value === 'selected' ? 'block' : 'none';
        refreshSelectedResidentCounter();
        refreshSmsCreditEstimate();
    }

    function refreshSelectedResidentCounter() {
        if (!selectedResidentCounter) return;

        const selected = selectedResidentCount();
        selectedResidentCounter.textContent = `${selected} resident${selected === 1 ? '' : 's'} selected`;
    }

    function refreshSmsCreditEstimate() {
        const message = messageInput?.value || '';
        const channel = channelSelect?.value || 'sms';
        const recipients = estimatedSmsRecipients();
        const segments = channel === 'email' ? 0 : smsSegments(message);
        const neededCredits = channel === 'email' ? 0 : recipients * segments * 2;
        const availableCredits = Number(window.semaphoreCreditBalance || 0);
        const remainingCredits = availableCredits - neededCredits;

        if (smsEstimatedRecipients) smsEstimatedRecipients.textContent = recipients.toLocaleString();
        if (smsEstimatedSegments) smsEstimatedSegments.textContent = segments.toLocaleString();
        if (smsEstimatedCredits) smsEstimatedCredits.textContent = neededCredits.toLocaleString();
        if (smsRemainingCredits) smsRemainingCredits.textContent = remainingCredits.toLocaleString();
        if (estimatedSmsCreditsInput) estimatedSmsCreditsInput.value = neededCredits;

        if (smsCreditStatusBadge) {
            smsCreditStatusBadge.classList.remove('is-ok', 'is-danger');

            if (channel === 'email') {
                smsCreditStatusBadge.textContent = 'Email only';
                smsCreditStatusBadge.classList.add('is-ok');
            } else if (recipients > 1000) {
                smsCreditStatusBadge.textContent = 'Over 1,000 SMS limit';
                smsCreditStatusBadge.classList.add('is-danger');
            } else if (neededCredits <= availableCredits) {
                smsCreditStatusBadge.textContent = 'Enough credits';
                smsCreditStatusBadge.classList.add('is-ok');
            } else {
                smsCreditStatusBadge.textContent = 'Insufficient credits';
                smsCreditStatusBadge.classList.add('is-danger');
            }
        }
    }

    function updatePreview() {
        if (!messageInput || !previewText) return;

        const message = messageInput.value.trim();
        const channel = channelSelect?.value || 'sms';

        if (counter) {
            const length = smsLength(messageInput.value);
            const segments = smsSegments(messageInput.value);
            counter.textContent = `${length} / 1000 characters · ${segments} SMS segment${segments === 1 ? '' : 's'}`;
        }

        if (previewChannel) {
            previewChannel.textContent = channel === 'both'
                ? 'SMS + Email'
                : channel.toUpperCase();
        }

        previewText.textContent = message
            ? message
                .replaceAll('{name}', 'Juan Dela Cruz')
                .replaceAll('{firstname}', 'Juan')
                .replaceAll('{lastname}', 'Dela Cruz')
                .replaceAll('{barangay}', 'Hulong Duhat')
            : 'Your message preview will appear here.';

        refreshSmsCreditEstimate();
    }

    templateSelect?.addEventListener('change', function () {
        const selected = templates[this.value];

        if (!selected) return;

        if (subjectInput) subjectInput.value = selected.subject;
        if (messageInput) messageInput.value = selected.message;

        updatePreview();
    });

    residentPickerSearch?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        document.querySelectorAll('.resident-picker-item').forEach(function (item) {
            const haystack = item.dataset.search || '';
            item.style.display = haystack.includes(keyword) ? 'flex' : 'none';
        });
    });

    document.querySelectorAll('input[name="resident_ids[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            refreshSelectedResidentCounter();
            refreshSmsCreditEstimate();
        });
    });

    messageInput?.addEventListener('input', updatePreview);
    channelSelect?.addEventListener('change', updatePreview);
    audienceSelect?.addEventListener('change', refreshSelectedResidentBox);

    refreshSelectedResidentBox();
    updatePreview();
});

function confirmBulkResidentNotification(event) {
    event.preventDefault();

    const form = event.target;
    const channel = document.getElementById('notifyChannel')?.value || 'sms';
    const audienceSelect = document.getElementById('notifyAudience');
    const audience = audienceSelect?.selectedOptions[0]?.textContent || 'selected audience';
    const audienceValue = audienceSelect?.value || 'all';
    const message = document.getElementById('notifyMessage')?.value.trim() || '';
    const selectedResidents = document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    const neededCredits = Number(document.getElementById('estimatedSmsCreditsInput')?.value || 0);
    const availableCredits = Number(window.semaphoreCreditBalance || 0);
    const estimatedRecipients = Number(document.getElementById('smsEstimatedRecipients')?.textContent.replaceAll(',', '') || 0);

    if (!message) {
        alert('Please write a message before sending.');
        return false;
    }

    if (audienceValue === 'selected' && selectedResidents === 0) {
        alert('Please select at least one resident.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && estimatedRecipients > 1000) {
        alert('SMS bulk notification is limited to 1,000 mobile numbers only.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && neededCredits > availableCredits) {
        alert(`Not enough SMS credits. Needed: ${neededCredits}, Available: ${availableCredits}`);
        return false;
    }

    const extraText = audienceValue === 'selected'
        ? `${selectedResidents} selected resident${selectedResidents === 1 ? '' : 's'}`
        : audience;

    const confirmText = `Send this ${channel.toUpperCase()} notification to ${extraText}? Estimated SMS credits: ${neededCredits}.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send bulk notification?',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#667085',
            confirmButtonText: 'Yes, send now'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    if (confirm(confirmText)) {
        form.submit();
    }

    return false;
}
</script>
@endpush