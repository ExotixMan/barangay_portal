@extends('admin.layouts.admin')

@section('title', 'Residents')
@section('page_title', 'Residents')
@section('page_icon', 'fas fa-users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/residency-applications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/residents.css') }}">
    <style>
        .resident-correction-list { display: grid; gap: 10px; }
        .resident-correction-item { overflow: hidden; border: 1px solid var(--line, #dee2e6); border-radius: 14px; background: var(--surface, #fff); }
        .resident-correction-summary { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; padding: 12px 14px; }
        .resident-correction-summary-main { min-width: 0; }
        .resident-correction-id { color: var(--ink, #172033); font-size: .82rem; font-weight: 800; }
        .resident-correction-field { color: var(--muted, #6c757d); font-size: .76rem; margin-top: 2px; }
        .resident-correction-meta { color: var(--muted, #6c757d); font-size: .7rem; margin-top: 4px; }
        .resident-correction-summary-side { display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap; }
        .resident-correction-toggle { border: 0; background: transparent; color: var(--brand, #b8322a); font-size: .74rem; font-weight: 800; padding: 4px 0; }
        .resident-correction-toggle i { transition: transform .18s ease; }
        .resident-correction-toggle[aria-expanded="true"] i { transform: rotate(180deg); }
        .resident-correction-details { display: none; padding: 0 14px 12px; }
        .resident-correction-details.is-open { display: block; }
        .resident-correction-detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; padding: 10px; border-radius: 10px; background: var(--surface-soft, #f8f9fa); }
        .resident-correction-detail { min-width: 0; }
        .resident-correction-detail.is-wide { grid-column: 1 / -1; }
        .resident-correction-detail-label { color: var(--muted, #6c757d); font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; }
        .resident-correction-detail-value { color: var(--ink-soft, #343a40); font-size: .78rem; overflow-wrap: anywhere; margin-top: 2px; }
        .resident-correction-decision { margin: 12px 14px 14px; padding-top: 12px; border-top: 1px solid var(--line, #dee2e6); }
        @media (max-width: 575px) {
            .resident-correction-summary { grid-template-columns: 1fr; gap: 8px; }
            .resident-correction-summary-side { justify-content: space-between; }
            .resident-correction-detail-grid { grid-template-columns: 1fr; }
            .resident-correction-detail.is-wide { grid-column: auto; }
        }

        /* Keep the resident view modal on the same compact system as the edit modal. */
        .modal[id^="viewResidentModal"] .modal-body { background: #fbfdff; }
        .modal[id^="viewResidentModal"] .view-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            padding: .35rem;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #f8fafc;
        }
        .modal[id^="viewResidentModal"] .view-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 10px;
            color: var(--muted);
            font-size: .78rem;
            font-weight: 800;
            padding: .5rem .75rem;
        }
        .modal[id^="viewResidentModal"] .view-tabs .nav-link:hover,
        .modal[id^="viewResidentModal"] .view-tabs .nav-link.active {
            color: var(--primary-dark);
            background: #fff;
            border-color: var(--line);
            box-shadow: var(--shadow-xs);
        }
        .modal[id^="viewResidentModal"] .application-modal-summary { gap: .6rem; margin-bottom: .8rem; }
        .modal[id^="viewResidentModal"] .modal-summary-card,
        .modal[id^="viewResidentModal"] .detail-section-card,
        .modal[id^="viewResidentModal"] .edit-section-card {
            border-radius: 15px;
            box-shadow: var(--shadow-xs);
        }
        .modal[id^="viewResidentModal"] .modal-summary-card { padding: .72rem; }
        .modal[id^="viewResidentModal"] .summary-status-value { font-weight: 900; }
        .modal[id^="viewResidentModal"] .summary-status-value.is-success { color: #198754; }
        .modal[id^="viewResidentModal"] .summary-status-value.is-danger { color: #dc3545; }
        .modal[id^="viewResidentModal"] .summary-status-value.is-warning { color: #b26a00; }
        .modal[id^="viewResidentModal"] .summary-status-value.is-neutral { color: var(--muted); }
        .modal[id^="viewResidentModal"] .detail-section-card { padding: 0; margin-bottom: .65rem; }
        .modal[id^="viewResidentModal"] .detail-section-head { padding: .7rem .8rem; }
        .modal[id^="viewResidentModal"] .detail-section-body { padding: .75rem; }
        .modal[id^="viewResidentModal"] .detail-grid { gap: .55rem; }
        .modal[id^="viewResidentModal"] .detail-item { padding: .6rem; border-radius: 11px; }
        .modal[id^="viewResidentModal"] .detail-section-title { color: var(--text); }
        .modal[id^="viewResidentModal"] .detail-section-title > i { color: var(--primary); }
        .modal[id^="viewResidentModal"] .edit-info-banner { margin-bottom: .7rem; padding: .72rem; border-radius: 15px; }
        .modal[id^="viewResidentModal"] .modern-edit-form { padding: .75rem; gap: .65rem; border-radius: 16px; }
        .modal[id^="viewResidentModal"] .edit-section-head,
        .modal[id^="viewResidentModal"] .edit-section-body { padding: .72rem .8rem; }
        .modal[id^="viewResidentModal"] .document-preview-grid { gap: .6rem; }

        @media (max-width: 575px) {
            .modal[id^="viewResidentModal"] .view-tabs { display: grid; grid-template-columns: 1fr 1fr; }
            .modal[id^="viewResidentModal"] .view-tabs .nav-link { width: 100%; }
        }
    </style>
@endpush



@section('content')
    @include('admin.layouts.alerts')

    <div class="residency-page">

    <section class="residency-hero">
        <div class="residency-hero-content">
            <div class="min-w-0">
                <div class="residency-hero-icon"><i class="fas fa-users"></i></div>
                <div class="residency-kicker">Resident registry</div>
                <h2>Manage, verify, update, and maintain resident records in one consistent workspace.</h2>
                <p>Review resident identity, contact details, account status, valid ID verification, profile corrections, and activity history without leaving the resident registry.</p>
            </div>
            <div class="residency-hero-actions">
                @admin_can('create_residents')
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                    <i class="fas fa-plus"></i>
                    Add Resident
                </a>
                @endadmin_can
                <a href="{{ route('admin.residents.index') }}" class="btn btn-light-soft">
                    <i class="fas fa-rotate"></i>
                    Reset View
                </a>
            </div>
        </div>
        <div class="residency-hero-meta">
            <span><i class="fas fa-users"></i> Total: <strong>{{ number_format($totalResidents) }}</strong></span>
            <span><i class="fas fa-user-check"></i> Active: <strong>{{ number_format($activeResidents) }}</strong></span>
            <span><i class="fas fa-user-plus"></i> New: <strong>{{ number_format($newResidents) }}</strong></span>
            <span><i class="fas fa-user-shield"></i> Seniors: <strong>{{ number_format($seniorResidents) }}</strong></span>
        </div>
    </section>

    <!-- Statistics -->
    <div class="row g-3 g-lg-4 mb-4 residency-stats">
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-muted mb-1">Total</div>
                    <div class="stat-number">{{ number_format($totalResidents) }}</div>
                    <small class="text-success mt-2 d-none d-sm-block">
                        <i class="fas fa-arrow-up me-1"></i>{{ ($growthPercentage >= 0 ? '+' : '') . round($growthPercentage, 1) }}% from last month
                    </small>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-muted mb-1">Active</div>
                    <div class="stat-number">{{ number_format($activeResidents) }}</div>
                    <small class="text-success mt-2 d-none d-sm-block">
                        <i class="fas fa-check-circle me-1"></i>{{ round($activePercentage, 1) }}% active
                    </small>
                </div>
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-muted mb-1">New</div>
                    <div class="stat-number">{{ number_format($newResidents) }}</div>
                    <small class="text-info mt-2 d-none d-sm-block">
                        <i class="fas fa-calendar me-1"></i>+{{ number_format($yesterdayCount) }} from yesterday
                    </small>
                </div>
                <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label text-muted mb-1">Senior</div>
                    <div class="stat-number">{{ number_format($seniorResidents) }}</div>
                    <small class="text-warning mt-2 d-none d-sm-block">
                        <i class="fas fa-heart me-1"></i>{{ round($seniorPercentage, 1) }}% of total
                    </small>
                </div>
                <div class="stat-icon"><i class="fas fa-user"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 mb-4 residency-filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.residents.index') }}" id="searchForm">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-12 col-md-6">
                        <div class="filter-title-block">
                            <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Residents</h6>
                            <p>Search resident names, IDs, usernames, email addresses, account status, or age group.</p>
                        </div>
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

                <div class="row g-3">
                    <div class="col-12 col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search residents..." value="{{ request('search') }}">
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

    <!-- Residents Table -->
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
                <span class="table-count-pill"><i class="fas fa-list"></i>{{ $residents->total() }} records</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0" id="residentsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="ps-4">
                                <input type="checkbox" id="selectAll" class="form-check-input table-checkbox" onclick="toggleSelectAll()" aria-label="Select all residents">
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
                            <th>Contact</th>
                            <th>Personal / Address</th>
                            <th>Status / Verification</th>
                            <th class="text-end pe-3 actions-heading"><span class="visually-hidden">Application controls</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        @php
                            $residentHasCorrection = $withCorrection->contains('resident_id', $resident->id);
                        @endphp
                        <tr class="resident-row {{ $resident->deleted_at ? 'is-deactivated' : '' }}">
                            <td class="ps-4">
                                <input type="checkbox" value="{{ $resident->id }}" class="form-check-input table-checkbox resident-checkbox" aria-label="Select {{ $resident->full_name }}" {{ $resident->deleted_at ? 'disabled' : '' }}>
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
                                            <span class="resident-username"><i class="fas fa-at" aria-hidden="true"></i>{{ $resident->username }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="info-stack">
                                    <span class="info-main">{{ $resident->email ?? 'N/A' }}</span>
                                    <span class="info-sub"><i class="fas fa-phone me-1"></i>{{ $resident->contact ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="resident-address-cell">
                                <div class="info-stack">
                                    <span class="info-main">{{ $resident->age }} years old</span>
                                    <div class="info-sub resident-address" title="{{ $resident->address ?? 'N/A' }}">
                                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                                        <span class="resident-address-text">{{ $resident->address ?? 'N/A' }}</span>
                                    </div>
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
                                        $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back);
                                        $residentIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                    @endphp
                                    @if(!$hasAnyValidId)
                                        <span class="badge bg-secondary-subtle text-secondary"><i class="fas fa-id-card me-1"></i>No ID</span>
                                    @elseif($residentIdStatus === 'verified')
                                        <span class="badge bg-success-subtle text-success"><i class="fas fa-id-card me-1"></i>ID Verified</span>
                                    @elseif($residentIdStatus === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-id-card me-1"></i>ID Rejected</span>
                                    @else
                                        <span class="badge resident-status-badge is-warning"><i class="fas fa-id-card me-1"></i>ID Pending Review</span>
                                    @endif
                                    @if($residentHasCorrection)
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-user-pen me-1"></i>Correction Pending</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="action-buttons" aria-label="Actions for {{ $resident->full_name }}">
                                    <button type="button"
                                            class="btn btn-sm action-btn action-primary action-view"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewResidentModal{{ $resident->id }}"
                                            data-view-tab="details"
                                            title="View resident details"
                                            aria-label="View details for {{ $resident->full_name }}">
                                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                        <span class="action-label">View</span>
                                    </button>

                                    @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('update_residents'))
                                        <button type="button"
                                                class="btn btn-sm action-btn action-primary action-update"
                                                data-bs-toggle="modal"
                                            data-bs-target="#viewResidentModal{{ $resident->id }}"
                                            data-view-tab="edit"
                                                title="Edit resident"
                                                aria-label="Edit {{ $resident->full_name }}">
                                            <i class="fas fa-pen" aria-hidden="true"></i>
                                            <span class="action-label">Edit</span>
                                        </button>
                                    @endif

                                    <div class="dropdown dropstart resident-action-dropdown">
                                        <button type="button"
                                                class="btn btn-sm action-btn action-more icon-only"
                                                id="residentActions{{ $resident->id }}"
                                                data-bs-toggle="dropdown"
                                                data-bs-boundary="viewport"
                                                aria-expanded="false"
                                                aria-label="More actions for {{ $resident->full_name }}"
                                                title="More actions">
                                            <i class="fas fa-ellipsis-vertical" aria-hidden="true"></i>
                                            @if($residentHasCorrection)
                                                <span class="resident-correction-badge"></span>
                                            @endif
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end resident-action-menu" aria-labelledby="residentActions{{ $resident->id }}">
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewResidentModal{{ $resident->id }}" data-view-tab="history">
                                                    <span class="dropdown-item-icon is-info"><i class="fas fa-clock-rotate-left" aria-hidden="true"></i></span>
                                                    <span>
                                                        <strong>Activity log</strong>
                                                        <small>Review recent account activity</small>
                                                    </span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewResidentModal{{ $resident->id }}" data-view-tab="corrections">
                                                    <span class="dropdown-item-icon is-neutral"><i class="fas fa-user-pen" aria-hidden="true"></i></span>
                                                    <span>
                                                        @if($residentHasCorrection)
                                                            <strong style="color: #dc2626;">&bull; Profile corrections</strong>
                                                            <small style="color: #dc2626;">Review submitted concerns</small>
                                                        @else
                                                            <strong>Profile corrections</strong>
                                                            <small>View or resolve concerns</small>
                                                        @endif
                                                    </span>
                                                </button>
                                            </li>

                                            @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('delete_residents'))
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" class="m-0" onsubmit="return confirmDelete(event, 'Deactivate this resident account? Their record and transactions will remain archived.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item resident-action-danger">
                                                            <span class="dropdown-item-icon is-danger"><i class="fas fa-user-slash" aria-hidden="true"></i></span>
                                                            <span>
                                                                <strong>Deactivate account</strong>
                                                                <small>Keep the record archived</small>
                                                            </span>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            @if($resident->deleted_at && auth('admin')->user()->hasPermission('restore_residents'))
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.residents.restore', $resident->id) }}" class="m-0" onsubmit="return confirmDelete(event, 'Restore this resident?')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item resident-action-restore">
                                                            <span class="dropdown-item-icon is-success"><i class="fas fa-rotate-left" aria-hidden="true"></i></span>
                                                            <span>
                                                                <strong>Restore account</strong>
                                                                <small>Return access to the resident</small>
                                                            </span>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
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

    <!-- Add Resident Modal -->
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
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" name="birthdate" id="add_birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ date('Y-m-d') }}">
                                        <small class="text-muted">Enter the resident's actual date of birth.</small>
                                        @if(session('form_type') == 'add') @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                    </div>
                                    <div class="edit-col-6">
                                        <label for="add_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('gender') is-invalid @enderror @endif" name="gender" id="add_gender" required>
                                            <option value="">Select gender</option>
                                            <option value="Male" {{ session('form_type') == 'add' && old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ session('form_type') == 'add' && old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ session('form_type') == 'add' && old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                            <option value="Prefer not to say" {{ session('form_type') == 'add' && old('gender') === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                        </select>
                                        @if(session('form_type') == 'add') @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                    </div>
                                    <div class="edit-col-6">
                                        <label for="add_civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('civil_status') is-invalid @enderror @endif" name="civil_status" id="add_civil_status" required>
                                            <option value="">Select civil status</option>
                                            @foreach(['Single', 'Married', 'Widowed', 'Separated', 'Divorced'] as $civilStatus)
                                                <option value="{{ $civilStatus }}" {{ session('form_type') == 'add' && old('civil_status') === $civilStatus ? 'selected' : '' }}>{{ $civilStatus }}</option>
                                            @endforeach
                                        </select>
                                        @if(session('form_type') == 'add') @error('civil_status') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                    </div>
                                    <div class="edit-col-12">
                                        <label for="add_place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('place_of_birth') is-invalid @enderror @endif" name="place_of_birth" id="add_place_of_birth" value="{{ session('form_type') == 'add' ? old('place_of_birth') : '' }}" required maxlength="255" placeholder="City/Municipality, Province">
                                        @if(session('form_type') == 'add') @error('place_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
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

    <!-- The view modal now owns the single resident edit workflow. -->
    @if(false)
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
                                <div class="edit-info-subtitle">Update resident identity, contact, address, and valid ID review using the same form pattern as residency applications.</div>
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
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('birthdate') is-invalid @enderror @endif" name="birthdate" id="edit_birthdate_{{ $resident->id }}" value="{{ session('form_type') == 'edit_' . $resident->id ? old('birthdate', $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('Y-m-d') : '') : ($resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('Y-m-d') : '') }}" required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $resident->id) @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                        </div>
                                        <div class="edit-col-6">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $resident->id) @error('gender') is-invalid @enderror @endif" name="gender" id="edit_gender_{{ $resident->id }}" required>
                                                <option value="">Select gender</option>
                                                @foreach(['Male', 'Female', 'Other', 'Prefer not to say'] as $genderOption)
                                                    <option value="{{ $genderOption }}" {{ old('gender', $resident->gender) === $genderOption ? 'selected' : '' }}>{{ $genderOption }}</option>
                                                @endforeach
                                            </select>
                                            @if(session('form_type') == 'edit_' . $resident->id) @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                        </div>
                                        <div class="edit-col-6">
                                            <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $resident->id) @error('civil_status') is-invalid @enderror @endif" name="civil_status" id="edit_civil_status_{{ $resident->id }}" required>
                                                <option value="">Select civil status</option>
                                                @foreach(['Single', 'Married', 'Widowed', 'Separated', 'Divorced'] as $civilStatus)
                                                    <option value="{{ $civilStatus }}" {{ old('civil_status', $resident->civil_status) === $civilStatus ? 'selected' : '' }}>{{ $civilStatus }}</option>
                                                @endforeach
                                            </select>
                                            @if(session('form_type') == 'edit_' . $resident->id) @error('civil_status') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                        </div>
                                        <div class="edit-col-12">
                                            <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $resident->id) @error('place_of_birth') is-invalid @enderror @endif" name="place_of_birth" id="edit_place_of_birth_{{ $resident->id }}" value="{{ old('place_of_birth', $resident->place_of_birth) }}" required maxlength="255" placeholder="City/Municipality, Province">
                                            @if(session('form_type') == 'edit_' . $resident->id) @error('place_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
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
                                        $editIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : (($resident->valid_id || $resident->valid_id_back) ? 'for_verification' : null));
                                        $editDocuments = [
                                            ['field' => 'valid_id', 'label' => 'Front ID', 'accept' => 'image/*,.pdf'],
                                            ['field' => 'valid_id_back', 'label' => 'Back ID', 'accept' => 'image/*,.pdf'],
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
                                                <option value="" {{ old('valid_id_status', $editIdStatus) === null || old('valid_id_status', $editIdStatus) === '' ? 'selected' : '' }}>No ID Uploaded</option>
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

    <!-- View Resident Modals - Residency-style tabbed (Details / Edit info / Corrections / History) -->
    @foreach($residents as $resident)
        @php
            $profileCorrectionConcerns = $resident->profileCorrectionConcerns->sortByDesc('created_at')->values();
            $pendingCorrectionCount = $profileCorrectionConcerns->filter(function ($concern) {
                return in_array(strtolower(trim((string) $concern->status)), ['pending', 'reviewed', 'under_review', 'in_review', 'reviewing'], true);
            })->count();
            $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back);
            $viewIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
            $viewIdStatusLabel = match($viewIdStatus) {
                'verified' => 'Verified', 'rejected' => 'Rejected', 'for_verification' => 'For Review', default => 'None',
            };
            $accountStatusClass = $resident->deleted_at ? 'is-danger' : 'is-success';
            $validIdStatusClass = match($viewIdStatus) {
                'verified' => 'is-success',
                'rejected' => 'is-danger',
                'for_verification' => 'is-warning',
                default => 'is-neutral',
            };
        @endphp
        <div class="modal fade" id="viewResidentModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="view-modal-head w-100">
                            <div class="view-modal-avatar">
                                @if($resident->profile_photo)
                                    <img src="{{ asset($resident->profile_photo) }}" alt="{{ $resident->full_name }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;" onerror="this.remove();">
                                @else
                                    {{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}
                                @endif
                            </div>
                            <div class="view-modal-meta">
                                <div class="view-modal-name">{{ $resident->full_name }}</div>
                                <div class="view-modal-sub">{{ $resident->username }} &middot; ID #{{ $resident->id }}</div>
                                <div class="mt-1">
                                    @if($resident->deleted_at)
                                        <span class="badge bg-danger-subtle text-danger">Deactivated</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @endif
                                    @if($pendingCorrectionCount)
                                        <span class="badge bg-warning-subtle text-warning">{{ $pendingCorrectionCount }} correction{{ $pendingCorrectionCount === 1 ? '' : 's' }} pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav view-tabs mb-3" id="residentViewTabs{{ $resident->id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="r-details-tab-{{ $resident->id }}" data-bs-toggle="pill" data-bs-target="#r-details-panel-{{ $resident->id }}" type="button" role="tab" aria-selected="true">Details</button>
                            </li>
                            @if(auth('admin')->user()->hasPermission('update_residents'))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="r-edit-tab-{{ $resident->id }}" data-bs-toggle="pill" data-bs-target="#r-edit-panel-{{ $resident->id }}" type="button" role="tab" aria-selected="false">Edit info</button>
                            </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="r-corrections-tab-{{ $resident->id }}" data-bs-toggle="pill" data-bs-target="#r-corrections-panel-{{ $resident->id }}" type="button" role="tab" aria-selected="false">
                                    Corrections
                                    @if($pendingCorrectionCount)
                                        <span class="badge bg-danger rounded-pill ms-1">{{ $pendingCorrectionCount }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="r-history-tab-{{ $resident->id }}" data-bs-toggle="pill" data-bs-target="#r-history-panel-{{ $resident->id }}" type="button" role="tab" aria-selected="false">History</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- DETAILS TAB -->
                            <div class="tab-pane fade show active" id="r-details-panel-{{ $resident->id }}" role="tabpanel">
                                <div class="application-modal-summary">
                                    <div class="modal-summary-card is-primary">
                                        <span class="summary-label">Resident</span>
                                        <span class="summary-value">{{ $resident->full_name }}</span>
                                        <span class="summary-sub"><i class="fas fa-user-tag me-1"></i>{{ $resident->username }} &middot; ID #{{ $resident->id }}</span>
                                    </div>
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Age</span>
                                        <span class="summary-value">{{ $resident->age }}</span>
                                        <span class="summary-sub">years old</span>
                                    </div>
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Account</span>
                                        <span class="summary-value summary-status-value {{ $accountStatusClass }}">{{ $resident->deleted_at ? 'Deactivated' : 'Active' }}</span>
                                        <span class="summary-sub">Current status</span>
                                    </div>
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Valid ID</span>
                                        <span class="summary-value summary-status-value {{ $validIdStatusClass }}">{{ $viewIdStatusLabel }}</span>
                                        <span class="summary-sub">Identity check</span>
                                    </div>
                                </div>
                                @if($resident->valid_id_status !== 'verified')
                                    <div class="detail-section-card">
                                        <div class="detail-section-title d-flex justify-content-between">
                                            <div>
                                                <i class="fas fa-user-shield"></i>
                                                Verification Decision
                                                @if($viewIdStatus === 'verified')
                                                    <span class="badge bg-success-subtle text-success">Verified</span>
                                                @elseif($viewIdStatus === 'rejected')
                                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                                @elseif($viewIdStatus === 'for_verification')
                                                    <span class="badge bg-warning-subtle text-warning">For verification</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">No valid ID</span>
                                                @endif
                                            </div>
                                            <div style="text-align: right;">
                                                {{ $resident->valid_id_verified_at ? $resident->valid_id_verified_at->format('F d, Y') : '' }}
                                            </div>
                                        </div>
                                        <div class="detail-section-body">
                                            <div class="detail-grid">
                                                <div class="detail-item is-wide">
                                                    <div class="detail-label"><i class="fas fa-file-invoice"></i>Uploaded Documents</div>
                                                    @php
                                                        $viewDocuments = [
                                                            ['field' => 'valid_id', 'label' => 'Valid ID Front', 'icon' => 'fa-id-card'],
                                                            ['field' => 'valid_id_back', 'label' => 'Valid ID Back', 'icon' => 'fa-id-card-clip'],
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
                                                                </div>
                                                                <div class="document-preview-body">
                                                                    @if($path)
                                                                        @if($isImage)
                                                                            <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" onclick="document.getElementById('residentDocFull{{ $resident->id }}_{{ $doc['field'] }}').style.display='flex'" onerror="this.outerHTML='<div class=\'document-empty\'>Image unavailable</div>'">
                                                                            <div id="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                                                                                <img src="{{ asset($path) }}" style="max-width:90vw;max-height:90vh;border-radius:12px;">
                                                                            </div>
                                                                        @else
                                                                            <a href="{{ asset($path) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf me-1"></i>View File</a>
                                                                        @endif
                                                                    @else
                                                                        <div class="document-empty"><span><i class="fas fa-circle-info me-1"></i>Not uploaded</span></div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @if($resident->valid_id_status === 'rejected')
                                                    <div class="detail-item is-wide">
                                                        <div class="detail-label"><i class="fas fa-message"></i>Rejection Remarks</div>
                                                        <div class="detail-value">{{ $resident->valid_id_rejection_reason ?: 'No remarks.' }}</div>
                                                    </div>
                                                @endif
                                            </div>

                                            @if(auth('admin')->user()->hasPermission('update_residents'))
                                                @if(!$hasAnyValidId)
                                                    <div class="alert alert-danger mb-0 mt-3">
                                                        <i class="fas fa-triangle-exclamation me-1"></i>No valid ID image is uploaded yet, so this resident cannot be approved.
                                                    </div>
                                                    @elseif($viewIdStatus === 'for_verification')
                                                        <div class="valid-id-actions mt-3">
                                                            {{-- Approve ID --}}
                                                            <form method="POST" action="{{ route('admin.residents.verifyId', $resident->id) }}"
                                                                id="approveIdForm{{ $resident->id }}"
                                                                onsubmit="return confirmApproveId(event, '{{ addslashes($resident->full_name) }}')">
                                                                @csrf
                                                                <input type="hidden" name="notify_email" value="{{ $resident->email ? '1' : '0' }}">
                                                                <button type="submit" class="btn btn-success w-100">
                                                                    <i class="fas fa-check me-1"></i>Approve ID
                                                                </button>
                                                            </form>

                                                            {{-- Reject ID --}}
                                                            <form
                                                                method="POST"
                                                                action="{{ route('admin.residents.rejectId', $resident->id) }}"
                                                                class="mt-3"
                                                                id="rejectIdForm{{ $resident->id }}"
                                                                onsubmit="return confirmRejectId(event, '{{ addslashes($resident->full_name) }}')"
                                                            >
                                                                @csrf
                                                                <input
                                                                    type="hidden"
                                                                    name="valid_id_rejection_reason"
                                                                    id="rejectReasonInput{{ $resident->id }}"
                                                                >

                                                                @error('valid_id_rejection_reason')
                                                                    <div class="invalid-feedback d-block mb-2">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror

                                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                                    <i class="fas fa-xmark me-1"></i>Reject ID
                                                                </button>
                                                            </form>
                                                        </div>
                                                @elseif($viewIdStatus === 'verified')
                                                    <div class="alert alert-success mb-0 mt-3">
                                                        <i class="fas fa-circle-check me-1"></i>This valid ID has already been verified. No action needed.
                                                    </div>
                                                @elseif($viewIdStatus === 'rejected')
                                                    <div class="alert alert-secondary mb-0 mt-3">
                                                        <i class="fas fa-circle-info me-1"></i>This valid ID was rejected. Ask the resident to re-upload a valid ID for another review.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="detail-section-card">
                                    <div class="detail-section-title">
                                        <i class="fas fa-id-card"></i>
                                        Personal Information
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="detail-grid">
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-user"></i>First Name</div><div class="detail-value">{{ $resident->firstname }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-user"></i>Middle Name</div><div class="detail-value">{{ $resident->middlename ?? 'N/A' }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-user"></i>Last Name</div><div class="detail-value">{{ $resident->lastname }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-tag"></i>Suffix</div><div class="detail-value">{{ $resident->suffix ?? 'N/A' }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-cake-candles"></i>Birthdate</div><div class="detail-value">{{ $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') : 'N/A' }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-calendar"></i>Age</div><div class="detail-value">{{ $resident->age }} years old</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-venus-mars"></i>Gender</div><div class="detail-value">{{ $resident->gender ?: 'N/A' }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-ring"></i>Civil Status</div><div class="detail-value">{{ $resident->civil_status ?: 'N/A' }}</div></div>
                                            <div class="detail-item is-wide"><div class="detail-label"><i class="fas fa-location-dot"></i>Place of Birth</div><div class="detail-value">{{ $resident->place_of_birth ?: 'N/A' }}</div></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section-card">
                                    <div class="detail-section-title">
                                        <i class="fas fa-address-book"></i>
                                        Contact Information
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="detail-grid">
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-envelope"></i>Email</div><div class="detail-value">{{ $resident->email ?? 'N/A' }}</div></div>
                                            <div class="detail-item"><div class="detail-label"><i class="fas fa-phone"></i>Contact Number</div><div class="detail-value">{{ $resident->contact ?? 'N/A' }}</div></div>
                                            <div class="detail-item is-wide"><div class="detail-label"><i class="fas fa-map-marker-alt"></i>Complete Address</div><div class="detail-value">{{ $resident->address ?? 'N/A' }}</div></div>
                                        </div>
                                    </div>
                                </div>

                                @if($resident->valid_id_status === 'verified')
                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title">
                                            <i class="fas fa-id-card-clip"></i>
                                            Valid ID Review
                                        </div>
                                        <div class="detail-section-body">
                                            @php
                                                $viewDocuments = [
                                                    ['field' => 'valid_id', 'label' => 'Valid ID Front', 'icon' => 'fa-id-card'],
                                                    ['field' => 'valid_id_back', 'label' => 'Valid ID Back', 'icon' => 'fa-id-card-clip'],
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
                                                            @if(in_array($doc['field'], ['valid_id', 'valid_id_back']) && $path)
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
                                                                    <a href="{{ asset($path) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf me-1"></i>View File</a>
                                                                @endif
                                                            @else
                                                                <div class="document-empty"><span><i class="fas fa-circle-info me-1"></i>Not uploaded</span></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- EDIT INFO TAB -->
                            @if(auth('admin')->user()->hasPermission('update_residents'))
                            <div class="tab-pane fade edit-info-pane" id="r-edit-panel-{{ $resident->id }}" role="tabpanel">
                                <div class="edit-info-banner">
                                    <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="edit-info-title">Edit information for {{ $resident->full_name }}</div>
                                        <div class="edit-info-subtitle">ID #{{ $resident->id }} &middot; Update the resident's name, contact, and address quickly.</div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" id="quickEditResidentForm{{ $resident->id }}" class="quick-edit-form modern-edit-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="quick_edit_{{ $resident->id }}">

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-user"></i></span>
                                            <div>
                                                <div class="edit-section-title">Personal Information</div>
                                                <p class="edit-section-help">Keep the resident's identity accurate for document generation.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-field edit-col-4">
                                                    <label class="form-label"><i class="fas fa-user"></i>First Name</label>
                                                    <input type="text" name="firstname" class="form-control" value="{{ $resident->firstname }}" required>
                                                </div>
                                                <div class="edit-field edit-col-4">
                                                    <label class="form-label"><i class="fas fa-user"></i>Middle Name</label>
                                                    <input type="text" name="middlename" class="form-control" value="{{ $resident->middlename }}">
                                                </div>
                                                <div class="edit-field edit-col-4">
                                                    <label class="form-label"><i class="fas fa-user"></i>Last Name</label>
                                                    <input type="text" name="lastname" class="form-control" value="{{ $resident->lastname }}" required>
                                                </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-at"></i>Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ $resident->username }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-cake-candles"></i>Birthdate</label>
                                                        <input type="date" name="birthdate" class="form-control" value="{{ $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('Y-m-d') : '' }}" max="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label>
                                                        <select name="gender" class="form-select">
                                                            <option value="">Not specified</option>
                                                            @foreach(['Male', 'Female', 'Other', 'Prefer not to say'] as $genderOption)
                                                                <option value="{{ $genderOption }}" {{ $resident->gender === $genderOption ? 'selected' : '' }}>{{ $genderOption }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-ring"></i>Civil status</label>
                                                        <select name="civil_status" class="form-select">
                                                            <option value="">Not specified</option>
                                                            @foreach(['Single', 'Married', 'Widowed', 'Separated', 'Divorced'] as $civilStatus)
                                                                <option value="{{ $civilStatus }}" {{ $resident->civil_status === $civilStatus ? 'selected' : '' }}>{{ $civilStatus }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Place of birth</label>
                                                        <input type="text" name="place_of_birth" class="form-control" value="{{ $resident->place_of_birth }}" maxlength="255">
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                            <div>
                                                <div class="edit-section-title">Contact Information</div>
                                                <p class="edit-section-help">Used for notifications, including correction updates.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $resident->email }}" required>
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label"><i class="fas fa-phone"></i>Contact Number</label>
                                                    <input type="text" name="contact" class="form-control" value="{{ $resident->contact }}" maxlength="11" required>
                                                </div>
                                                <div class="edit-field edit-col-12">
                                                    <label class="form-label"><i class="fas fa-map-marker-alt"></i>Complete Address</label>
                                                    <textarea name="address" class="form-control" rows="2" required>{{ $resident->address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="edit-section-card">
                                        <div class="edit-section-head">
                                            <span class="edit-section-icon"><i class="fas fa-id-card-clip"></i></span>
                                            <div>
                                                <div class="edit-section-title">Valid ID verification</div>
                                                <p class="edit-section-help">Replace either side or update the verification status.</p>
                                            </div>
                                        </div>
                                        <div class="edit-section-body">
                                            <div class="edit-form-grid">
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label">Replace front ID</label>
                                                    <input type="file" name="valid_id" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf">
                                                    <small class="text-muted">Leave empty to keep the current front.</small>
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label">Replace back ID</label>
                                                    <input type="file" name="valid_id_back" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf">
                                                    <small class="text-muted">Leave empty to keep the current back.</small>
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label">Verification status</label>
                                                    <select name="valid_id_status" class="form-select">
                                                        <option value="">Keep current status</option>
                                                        <option value="for_verification">For verification</option>
                                                        <option value="verified">Approved / Verified</option>
                                                        <option value="rejected">Rejected</option>
                                                    </select>
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label">Rejection remarks</label>
                                                    <textarea name="valid_id_rejection_reason" class="form-control" rows="2" maxlength="500" placeholder="Required only when rejecting."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif

                            <!-- CORRECTIONS TAB -->
                            <div class="tab-pane fade" id="r-corrections-panel-{{ $resident->id }}" role="tabpanel">
                                <div class="edit-info-banner">
                                    <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="edit-info-title">{{ $resident->full_name }}</div>
                                        <div class="edit-info-subtitle">
                                            Review profile corrections submitted by this resident.
                                            {{ $pendingCorrectionCount }} pending concern{{ $pendingCorrectionCount === 1 ? '' : 's' }}.
                                        </div>
                                    </div>
                                </div>

                                <div class="resident-correction-list">
                                @forelse($profileCorrectionConcerns as $concern)
                                    @php
                                        $concernStatus = strtolower(trim((string) $concern->status));
                                        $isConcernOpen = in_array($concernStatus, ['pending', 'reviewed', 'under_review', 'in_review', 'reviewing', ''], true);
                                        $concernBadgeClass = match($concernStatus) {
                                            'resolved', 'corrected', 'approved', 'completed' => 'bg-success-subtle text-success',
                                            'rejected', 'declined' => 'bg-danger-subtle text-danger',
                                            'reviewed', 'under_review', 'in_review', 'reviewing' => 'bg-info-subtle text-info',
                                            default => 'bg-warning-subtle text-warning',
                                        };
                                        $concernStatusLabel = match($concernStatus) {
                                            'resolved', 'corrected' => 'Resolved',
                                            'reviewed', 'under_review', 'in_review', 'reviewing' => 'Under review',
                                            'rejected', 'declined' => 'Rejected',
                                            default => 'Pending',
                                        };
                                    @endphp
                                    <article class="resident-correction-item">
                                        <div class="resident-correction-summary">
                                            <div class="resident-correction-summary-main">
                                                <div class="resident-correction-id"><i class="fas fa-file-pen me-1"></i>{{ $concern->formatted_id }}</div>
                                                <div class="resident-correction-field">{{ str($concern->field_name)->replace('_', ' ')->title() }} · {{ $concern->service ?: 'Resident Profile' }}</div>
                                                <div class="resident-correction-meta">Submitted {{ optional($concern->created_at)->format('M d, Y h:i A') ?? 'N/A' }}</div>
                                            </div>
                                            <div class="resident-correction-summary-side">
                                                <span class="badge {{ $concernBadgeClass }}">{{ $concernStatusLabel }}</span>
                                                <button type="button" class="resident-correction-toggle" data-correction-target="correctionDetails{{ $concern->id }}" aria-expanded="false" aria-controls="correctionDetails{{ $concern->id }}">
                                                    Details <i class="fas fa-chevron-down ms-1"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="resident-correction-details" id="correctionDetails{{ $concern->id }}">
                                            <div class="resident-correction-detail-grid">
                                                <div class="resident-correction-detail"><div class="resident-correction-detail-label">Current value</div><div class="resident-correction-detail-value">{{ $concern->current_value ?: 'Not provided' }}</div></div>
                                                <div class="resident-correction-detail"><div class="resident-correction-detail-label">Requested correction</div><div class="resident-correction-detail-value">{{ $concern->correct_value }}</div></div>
                                                <div class="resident-correction-detail is-wide"><div class="resident-correction-detail-label">Resident message</div><div class="resident-correction-detail-value">{{ $concern->message ?: 'No additional message.' }}</div></div>
                                                <div class="resident-correction-detail is-wide"><div class="resident-correction-detail-label">Review remarks</div><div class="resident-correction-detail-value">{{ $concern->remarks ?: ($concern->reviewed_at ? 'No remarks added.' : 'Not reviewed yet.') }}</div></div>
                                            </div>

                                            @if(auth('admin')->user()->hasPermission('update_residents') && $isConcernOpen)
                                                <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" class="correction-resolve-form resident-correction-decision" onsubmit="return confirmDelete(event, 'Save this decision and notify the resident by email?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="correction_id" value="{{ $concern->id }}">
                                                    <div class="row g-2 align-items-end">
                                                        <div class="col-12 col-md-4">
                                                            <label class="form-label small text-uppercase fw-semibold">Decision</label>
                                                            <select name="resolution_status" class="form-select" required>
                                                                <option value="">Select decision</option>
                                                                <option value="resolved">Mark corrected / approved</option>
                                                                <option value="rejected">Reject request</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-md-8">
                                                            <label class="form-label small text-uppercase fw-semibold">Remarks <span class="text-danger">*</span></label>
                                                            <textarea name="remarks" class="form-control" rows="2" maxlength="500" required placeholder="Explain the approval or rejection."></textarea>
                                                        </div>
                                                        <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="notify_email" value="1" id="notify_email_{{ $concern->id }}" checked {{ !$resident->email ? 'disabled' : '' }}>
                                                                <label class="form-check-label small" for="notify_email_{{ $concern->id }}">Notify resident by email</label>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i>Save decision</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="document-empty">
                                        <span><i class="fas fa-circle-info me-1"></i>No profile correction concern has been submitted by this resident.</span>
                                    </div>
                                @endforelse
                                </div>
                            </div>

                            <!-- HISTORY TAB -->
                            <div class="tab-pane fade" id="r-history-panel-{{ $resident->id }}" role="tabpanel">
                                <div class="detail-section-card mb-0">
                                    <div class="detail-section-title">
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
                        </div>
                    </div>

                    <div class="modal-footer" id="r-viewFooterDetails{{ $resident->id }}">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        @if(auth('admin')->user()->hasPermission('update_residents'))
                        <button type="button" class="btn btn-primary" data-resident-switch-tab="edit" data-resident-id="{{ $resident->id }}">Edit info</button>
                        @endif
                    </div>
                    @if(auth('admin')->user()->hasPermission('update_residents'))
                    <div class="modal-footer d-none" id="r-viewFooterEdit{{ $resident->id }}">
                        <button type="button" class="btn btn-outline-secondary" data-resident-switch-tab="details" data-resident-id="{{ $resident->id }}">Cancel</button>
                        <button type="submit" form="quickEditResidentForm{{ $resident->id }}" class="btn btn-primary">Save changes</button>
                    </div>
                    @endif
                    <div class="modal-footer d-none" id="r-viewFooterCorrections{{ $resident->id }}">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    <div class="modal-footer d-none" id="r-viewFooterHistory{{ $resident->id }}">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@endsection

@push('scripts')
<script>
        // Bulk Deactivate
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.resident-checkbox:checked:not(:disabled)');
            const bulkForm = document.getElementById('bulkForm');
            if (checkboxes.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one resident to deactivate.', confirmButtonColor: '#d33' });
                return;
            }
            Swal.fire({
                title: 'Confirm Bulk Deactivate',
                text: `Are you sure you want to deactivate ${checkboxes.length} selected resident(s)?`,
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, Deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                        bulkForm.appendChild(input);
                    });
                    bulkForm.submit();
                }
            });
        }

        // Export CSV
        function exportCSV() {
            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const exportForm = document.getElementById('exportForm');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'question', title: 'Export All?', text: 'No residents selected. Export all records?',
                    showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, Export All'
                }).then((result) => { if (result.isConfirmed) exportForm.submit(); });
                return;
            }
            Swal.fire({
                title: 'Export Selected?', text: `Export ${checkboxes.length} selected resident(s)?`, icon: 'question',
                showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, Export'
            }).then((result) => {
                if (result.isConfirmed) {
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                        exportForm.appendChild(input);
                    });
                    exportForm.submit();
                }
            });
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.resident-checkbox:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                checkbox.closest('tr')?.classList.toggle('is-selected', checkbox.checked);
            });
            selectAllCheckbox.indeterminate = false;
        }

        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (typeof Swal === 'undefined') {
                const ok = confirm(message || 'Are you sure?');
                if (ok) form.submit();
                return false;
            }
            Swal.fire({
                title: 'Are you sure?', text: message || 'You won\'t be able to revert this!', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, proceed!'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
            return false;
        }

        function confirmApproveId(event, residentName) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (typeof Swal === 'undefined') {
                if (confirm('Approve and verify the valid ID for ' + residentName + '?')) form.submit();
                return false;
            }
            Swal.fire({
                title: 'Approve this ID?',
                text: `Verify the valid ID for ${residentName}? This can't be undone.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Approve'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
            return false;
        }

        // Reject Valid ID (collects required remarks before submitting)
        function confirmRejectId(event, residentName) {
            event.preventDefault();
            const form = event.target.closest('form');
            const reasonInput = form.querySelector('input[name="valid_id_rejection_reason"]');

            const parentModal = form.closest('.modal.show') || form.closest('.modal');

            if (typeof Swal === 'undefined') {
                const reason = prompt('Enter rejection remarks for ' + residentName + ':');
                if (reason && reason.trim().length > 0) {
                    reasonInput.value = reason.trim();
                    form.submit();
                }
                return false;
            }

            Swal.fire({
                title: 'Reject this ID?',
                html: `Provide remarks for rejecting the valid ID of <strong>${residentName}</strong>:`,
                input: 'textarea',
                inputPlaceholder: 'Example: Back ID is blurry, photo does not match, expired ID, or details are unreadable.',
                inputAttributes: { 'aria-label': 'Rejection remarks' },
                target: parentModal || document.body,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirm Reject',
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Rejection remarks are required.';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    reasonInput.value = result.value.trim();
                    form.submit();
                }
            });
            return false;
        }

        // ---- View modal tab / footer switching (resident) ----
        function switchResidentViewTab(residentId, tabName) {
            const tabButton = document.getElementById('r-' + tabName + '-tab-' + residentId);
            if (!tabButton) return;
            new bootstrap.Tab(tabButton).show();
        }

        function toggleResidentViewFooter(residentId, activeTab) {
            ['Details', 'Edit', 'Corrections', 'History'].forEach(function(name) {
                const el = document.getElementById('r-viewFooter' + name + residentId);
                if (el) el.classList.add('d-none');
            });
            const map = { details: 'Details', edit: 'Edit', corrections: 'Corrections', history: 'History' };
            const target = document.getElementById('r-viewFooter' + (map[activeTab] || 'Details') + residentId);
            if (target) target.classList.remove('d-none');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
            }

            const checkboxes = document.querySelectorAll('.resident-checkbox:not(:disabled)');
            const selectAllCheckbox = document.getElementById('selectAll');
            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        this.closest('tr')?.classList.toggle('is-selected', this.checked);
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                    checkbox.closest('tr')?.classList.toggle('is-selected', checkbox.checked);
                });
            }

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

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            @if ($errors->any() && session('form_type'))
                @if(session('form_type') == 'add')
                    var addModal = new bootstrap.Modal(document.getElementById('addResidentModal'));
                    addModal.show();
                @elseif(Str::startsWith(session('form_type'), 'edit_'))
                    var editId = '{{ session('form_type') }}'.replace('edit_', '');
                    var editModal = document.getElementById('viewResidentModal' + editId);
                    if (editModal) {
                        var editViewModal = bootstrap.Modal.getOrCreateInstance(editModal);
                        editViewModal.show();
                        switchResidentViewTab(editId, 'edit');
                        toggleResidentViewFooter(editId, 'edit');
                    }
                @endif
            @endif

            // Wire up view-modal tab targeting (data-view-tab on triggers) and footer sync
            document.querySelectorAll('[id^="viewResidentModal"]').forEach(function(modalEl) {
                const residentId = modalEl.id.replace('viewResidentModal', '');

                modalEl.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function() {
                        if (tabBtn.id.indexOf('r-details-tab-') === 0) toggleResidentViewFooter(residentId, 'details');
                        else if (tabBtn.id.indexOf('r-edit-tab-') === 0) toggleResidentViewFooter(residentId, 'edit');
                        else if (tabBtn.id.indexOf('r-corrections-tab-') === 0) toggleResidentViewFooter(residentId, 'corrections');
                        else toggleResidentViewFooter(residentId, 'history');
                    });
                });

                modalEl.addEventListener('show.bs.modal', function(ev) {
                    const trigger = ev.relatedTarget;
                    const requestedTab = trigger ? trigger.getAttribute('data-view-tab') : null;
                    if (requestedTab) {
                        switchResidentViewTab(residentId, requestedTab);
                        toggleResidentViewFooter(residentId, requestedTab);
                    } else {
                        switchResidentViewTab(residentId, 'details');
                        toggleResidentViewFooter(residentId, 'details');
                    }
                });
            });

            document.querySelectorAll('[data-resident-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const residentId = button.getAttribute('data-resident-id');
                    const tabName = button.getAttribute('data-resident-switch-tab');
                    switchResidentViewTab(residentId, tabName);
                });
            });

            // Correction resolve forms: require remarks; toggle notify email disabled state if no email
            document.querySelectorAll('.correction-resolve-form').forEach(function(form) {
                const select = form.querySelector('select[name="resolution_status"]');
                const remarks = form.querySelector('textarea[name="remarks"]');
                if (select && remarks) {
                    select.addEventListener('change', function() {
                        remarks.placeholder = this.value === 'rejected'
                            ? 'Explain why this correction request was not approved.'
                            : 'Explain what was corrected on the resident profile.';
                    });
                }
            });

            document.querySelectorAll('[data-correction-target]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const details = document.getElementById(button.getAttribute('data-correction-target'));
                    if (!details) return;

                    const isOpen = details.classList.toggle('is-open');
                    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
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
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
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

            addForm.querySelectorAll('.is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }, { once: true });
            });

            const fn = document.getElementById('add_firstname');
            if (fn) fn.addEventListener('input', function() {
                if (!this.value.trim()) return setField(this, 'First name is required.');
                if (!namePattern.test(this.value)) return setField(this, 'First name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2) return setField(this, 'First name must be at least 2 characters.');
                setField(this, '');
            });

            const ln = document.getElementById('add_lastname');
            if (ln) ln.addEventListener('input', function() {
                if (!this.value.trim()) return setField(this, 'Last name is required.');
                if (!namePattern.test(this.value)) return setField(this, 'Last name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2) return setField(this, 'Last name must be at least 2 characters.');
                setField(this, '');
            });

            const un = document.getElementById('add_username');
            if (un) un.addEventListener('input', function() {
                if (!this.value) return setField(this, 'Username is required.');
                if (!usernamePattern.test(this.value)) return setField(this, 'Username can only contain letters, numbers, and underscores.');
                if (this.value.length < 3) return setField(this, 'Username must be at least 3 characters.');
                setField(this, '');
            });

            const em = document.getElementById('add_email');
            if (em) em.addEventListener('input', function() {
                if (!this.value) return setField(this, 'Email is required.');
                if (!emailPattern.test(this.value)) return setField(this, 'Please enter a valid email address.');
                setField(this, '');
            });

            const contact = document.getElementById('add_contact');
            if (contact) contact.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                if (!this.value) return setField(this, 'Contact number is required.');
                if (!this.value.startsWith('09')) return setField(this, 'Contact number must start with 09.');
                if (this.value.length !== 11) return setField(this, 'Contact number must be exactly 11 digits.');
                setField(this, '');
            });

            const bd = document.getElementById('add_birthdate');
            if (bd) bd.addEventListener('change', function() {
                const sel = new Date(this.value);
                const today = new Date(); today.setHours(0,0,0,0);
                const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                const age = calculateAge(this.value);
                if (sel >= today) return setField(this, 'Birthdate must be in the past.');
                if (sel < minDate) return setField(this, 'Please enter a valid birthdate.');
                if (age === null || age < 0) return setField(this, 'Please enter a valid birthdate.');
                setField(this, '');
            });

            const pw  = document.getElementById('add_password');
            const pwc = document.getElementById('add_password_confirmation');

            function validatePw() {
                pw.value = pw.value.replace(/\s/g, "");
                if (!pw.value) return setField(pw, 'Password is required.');
                if (pw.value.length < 8) return setField(pw, 'Password must be at least 8 characters.');
                if (!/[A-Z]/.test(pw.value)) return setField(pw, 'Password must contain at least one uppercase letter.');
                if (!/[a-z]/.test(pw.value)) return setField(pw, 'Password must contain at least one lowercase letter.');
                if (!/[0-9]/.test(pw.value)) return setField(pw, 'Password must contain at least one number.');
                if (!/[^A-Za-z0-9]/.test(pw.value)) return setField(pw, 'Password must contain at least one special character.');
                setField(pw, '');
                if (pwc.value) validatePwc();
            }
            function validatePwc() {
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

        // Real-time validation for full edit forms
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
                    if (!this.value.trim()) return sf(this, 'First name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'First name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2) return sf(this, 'First name must be at least 2 characters.');
                    sf(this, '');
                });

                const ln = document.getElementById('edit_lastname_' + id);
                if (ln) ln.addEventListener('input', function() {
                    if (!this.value.trim()) return sf(this, 'Last name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'Last name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2) return sf(this, 'Last name must be at least 2 characters.');
                    sf(this, '');
                });

                const un = document.getElementById('edit_username_' + id);
                if (un) un.addEventListener('input', function() {
                    if (!this.value) return sf(this, 'Username is required.');
                    if (!usernamePattern.test(this.value)) return sf(this, 'Username can only contain letters, numbers, and underscores.');
                    if (this.value.length < 3) return sf(this, 'Username must be at least 3 characters.');
                    sf(this, '');
                });

                const em = document.getElementById('edit_email_' + id);
                if (em) em.addEventListener('input', function() {
                    if (!this.value) return sf(this, 'Email is required.');
                    if (!emailPattern.test(this.value)) return sf(this, 'Please enter a valid email address.');
                    sf(this, '');
                });

                const ct = document.getElementById('edit_contact_' + id);
                if (ct) ct.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (!this.value) return sf(this, 'Contact number is required.');
                    if (!this.value.startsWith('09')) return sf(this, 'Contact number must start with 09.');
                    if (this.value.length !== 11) return sf(this, 'Contact number must be exactly 11 digits.');
                    sf(this, '');
                });

                const bd = document.getElementById('edit_birthdate_' + id);
                if (bd) bd.addEventListener('change', function() {
                    const sel = new Date(this.value);
                    const today = new Date(); today.setHours(0,0,0,0);
                    const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                    if (sel >= today) return sf(this, 'Birthdate must be in the past.');
                    if (sel < minDate) return sf(this, 'Please enter a valid birthdate.');
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
@endpush