@extends('admin.layouts.admin')

@section('title', 'Indigency Applications')
@section('page_title', 'Indigency Applications')
@section('page_icon', 'fas fa-file-invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/indigency-applications.css') }}">

@endpush

@section('content')
<div class="indigency-page">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show indigency-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show indigency-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show indigency-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <section class="indigency-hero">
                <div class="indigency-hero-content">
                    <div class="min-w-0">
                        <div class="indigency-hero-icon"><i class="fas fa-file-invoice"></i></div>
                        <div class="indigency-kicker">Indigency application management</div>
                        <h2>Review assistance requests with only the details that matter.</h2>
                        <p>Focus on the applicant, income bracket, household size, purpose, status, and next action. Full personal details and documents stay inside the modal for cleaner management.</p>
                    </div>

                    <div class="indigency-hero-actions">
                        @admin_can('create_indigency')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                            <i class="fas fa-plus"></i>
                            New Application
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.indigency.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset View
                        </a>
                    </div>
                </div>
                <div class="indigency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Approved: <strong>{{ $approved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Rejected: <strong>{{ $rejected_count ?? 0 }}</strong></span>
                </div>
            </section>

            <div class="row g-3 g-lg-4 mb-4 indigency-stats">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-arrow-up me-1"></i>All time
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Processing</div>
                            <div class="stat-number">{{ $processing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Needs review
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Approved</div>
                            <div class="stat-number">{{ $approved_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>Completed
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
                            <div class="stat-label text-muted mb-1">Rejected</div>
                            <div class="stat-number">{{ $rejected_count ?? 0 }}</div>
                            <small class="text-danger mt-2 d-none d-sm-block">
                                <i class="fas fa-times-circle me-1"></i>Not approved
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-4 indigency-filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.indigency.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Applications</h6>
                                    <p>Search by applicant, reference number, status, or income bracket.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_indigency')
                                    <a href="#" class="btn btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">New Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.indigency.index') }}" class="btn btn-light-soft flex-fill flex-md-grow-0">
                                        <i class="fas fa-rotate"></i><span class="d-none d-sm-inline ms-2">Reset View</span>
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
                                    <input type="text" name="search" data-input-sanitize="admin-search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by name, reference, email..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="ready_pickup" {{ request('status') == 'ready_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                    <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="monthly_income" class="form-select">
                                    <option value="">All Income</option>
                                    <option value="below 5k" {{ request('monthly_income') == 'below 5k' ? 'selected' : '' }}>Below &#8369;5,000</option>
                                    <option value="5k-8k" {{ request('monthly_income') == '5k-8k' ? 'selected' : '' }}>&#8369;5,000 - &#8369;8,000</option>
                                    <option value="8k-10k" {{ request('monthly_income') == '8k-10k' ? 'selected' : '' }}>&#8369;8,001 - &#8369;10,000</option>
                                    <option value="10k-15k" {{ request('monthly_income') == '10k-15k' ? 'selected' : '' }}>&#8369;10,001 - &#8369;15,000</option>
                                    <option value="no income" {{ request('monthly_income') == 'no income' ? 'selected' : '' }}>No fixed income</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    @php
                        $isArchivedView = request('status') === 'deleted';
                    @endphp
                    @if(auth('admin')->user()->hasAnyPermission(['delete_indigency', 'export_indigency']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_indigency')
                        <form
                            id="bulkForm"
                            method="POST"
                            action="{{ $isArchivedView ? route('admin.indigency.bulkRestore') : route('admin.indigency.bulkDelete') }}"
                            data-bulk-action="{{ $isArchivedView ? 'restore' : 'archive' }}"
                            style="display: inline;"
                        >
                            @csrf
                            <button
                                type="button"
                                data-click-call="bulkDelete"
                                class="btn {{ $isArchivedView ? 'btn-outline-success' : 'btn-outline-danger' }} d-flex align-items-center gap-2"
                                title="{{ $isArchivedView ? 'Bulk Restore' : 'Bulk Archive' }}"
                            >
                                <i class="fas {{ $isArchivedView ? 'fa-rotate-left' : 'fa-box-archive' }}"></i>
                                <span class="d-none d-sm-inline">
                                    {{ $isArchivedView ? 'Bulk Restore' : 'Bulk Archive' }}
                                </span>
                            </button>
                        </form>
                        @endadmin_can

                        @admin_can('export_indigency')
                        <form id="exportForm" method="POST" action="{{ route('admin.indigency.export') }}" style="display: inline;">
                            @csrf
                            <button type="button" data-click-call="exportCSV" class="btn btn-outline-success d-flex align-items-center gap-2" title="Export CSV">
                                <i class="fas fa-file-csv"></i>
                                <span class="d-none d-sm-inline">Export</span>
                            </button>
                        </form>
                        @endadmin_can
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 indigency-table-card">
                <div class="card-body p-0">
                    <div class="indigency-table-head">
                        <div class="table-head-title">
                            <span class="table-head-icon"><i class="fas fa-list-check"></i></span>
                            <div>
                                <span class="table-kicker">Application queue</span>
                                <h3>Indigency requests</h3>
                                <p>Only the important management details are shown here. Open the modal for documents and full profile details.</p>
                            </div>
                        </div>
                        <span class="table-count-pill"><i class="fas fa-file-invoice"></i>{{ $indigency->total() ?? 0 }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="indigencyTable">
                            <colgroup>
                                <col class="col-select">
                                <col class="col-applicant">
                                <col class="col-contact">
                                <col class="col-income">
                                <col class="col-purpose">
                                <col class="col-status">
                                <col class="col-actions">
                            </colgroup>
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input
                                            type="checkbox"
                                            id="selectAll"
                                            class="form-check-input application-checkbox"
                                            data-click-call="toggleSelectAll"
                                            aria-label="Select all indigency applications"
                                        >
                                    </th>

                                    <th class="ps-0">
                                        <a
                                            href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-decoration-none text-dark"
                                        >
                                            Applicant / Reference

                                            @if(request('sort') == 'created_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>Contact</th>
                                    <th>Income / Household</th>
                                    <th>Purpose</th>
                                    <th>Status / Submitted</th>
                                    <th class="text-end pe-3 actions-heading"><span class="visually-hidden">Application controls</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($indigency as $ind)
                                    <tr class="application-row {{ $ind->deleted_at ? 'is-archived' : '' }}">
                                        <td class="ps-4">
                                            <input
                                                type="checkbox"
                                                value="{{ $ind->id }}"
                                                class="form-check-input application-checkbox"
                                                aria-label="Select {{ $ind->full_name }}"
                                            >
                                        </td>

                                        <td class="ps-0 applicant-cell">
                                            <div class="application-primary">
                                                <div class="application-avatar" aria-hidden="true">
                                                    {{ strtoupper(substr($ind->first_name ?: 'I', 0, 1) . substr($ind->last_name ?: '', 0, 1)) }}
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="application-name">
                                                        {{ $ind->full_name }}
                                                    </div>

                                                    <div class="application-ref">
                                                        <span class="ref-pill">
                                                            <i class="fas fa-hashtag" aria-hidden="true"></i>
                                                            {{ $ind->reference_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="contact-column contact-cell">
                                            <div class="info-stack">
                                                <span
                                                    class="info-main table-truncate"
                                                    title="{{ $ind->email ?: 'No email address' }}"
                                                >
                                                    <i class="fas fa-envelope me-1" aria-hidden="true"></i>
                                                    {{ $ind->email ?: 'No email address' }}
                                                </span>

                                                <span class="info-sub">
                                                    <i class="fas fa-phone me-1" aria-hidden="true"></i>
                                                    {{ $ind->contact_number ?: 'No contact number' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="income-household-column income-cell">
                                            <div class="info-stack">
                                                <span class="income-badge">
                                                    <i class="fas fa-wallet" aria-hidden="true"></i>
                                                    {{ $ind->monthly_income_label }}
                                                </span>

                                                <span class="info-sub">
                                                    <i class="fas fa-users me-1" aria-hidden="true"></i>
                                                    {{ $ind->household_label }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="purpose-column purpose-cell">
                                            <div class="info-stack">
                                                <span
                                                    class="info-main purpose-label"
                                                    title="{{ $ind->purpose_label }}"
                                                >
                                                    {{ $ind->purpose_label }}
                                                </span>

                                                @if($ind->purpose === 'other' && $ind->purpose_other)
                                                    <span
                                                        class="info-sub table-truncate"
                                                        title="{{ $ind->purpose_other }}"
                                                    >
                                                        {{ $ind->purpose_other }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="status-submitted-cell">
                                            <div class="status-cell">
                                                @if($ind->deleted_at)
                                                    <span class="badge bg-danger-subtle text-danger">Archived</span>
                                                @elseif($ind->status === 'approved')
                                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                                @elseif($ind->status === 'ready_pickup')
                                                    <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                                @elseif($ind->status === 'claimed')
                                                    <span class="badge bg-success-subtle text-success">Claimed</span>
                                                @elseif($ind->status === 'rejected')
                                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                                @elseif($ind->status === 'pending')
                                                    <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                                                @elseif($ind->status === 'under_review')
                                                    <span class="badge bg-primary-subtle text-primary">Under Review</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">Processing</span>
                                                @endif

                                                <span class="status-date">
                                                    <i class="far fa-calendar me-1" aria-hidden="true"></i>
                                                    {{ $ind->created_at ? $ind->created_at->format('M d, Y h:i A') : 'N/A' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-end pe-3 actions-cell">
                                            <div class="action-buttons" aria-label="Controls for {{ $ind->full_name }}">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm action-btn action-primary action-view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $ind->id }}"
                                                    title="View full details"
                                                    aria-label="View {{ $ind->full_name }}"
                                                >
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                    <span class="action-label">View</span>
                                                </button>

                                                @if(!$ind->deleted_at && (auth('admin')->user()->hasPermission('update_indigency') || auth('admin')->user()->hasPermission('approve_indigency') || auth('admin')->user()->hasPermission('reject_indigency')))
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm action-btn action-primary action-update"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updateStatusModal{{ $ind->id }}"
                                                        title="Edit application status"
                                                        aria-label="Edit status for {{ $ind->full_name }}"
                                                    >
                                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm action-btn action-primary action-update"
                                                        disabled
                                                        title="Edit unavailable"
                                                        aria-label="Edit unavailable for {{ $ind->full_name }}"
                                                    >
                                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </button>
                                                @endif

                                                <div class="dropdown action-more-dropdown">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm action-btn action-more-toggle icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        data-bs-auto-close="outside"
                                                        aria-expanded="false"
                                                        title="More options"
                                                        aria-label="More options for {{ $ind->full_name }}"
                                                    >
                                                        <i class="fas fa-ellipsis-vertical" aria-hidden="true"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end action-menu">
                                                        @if(!$ind->deleted_at && auth('admin')->user()->hasPermission('generate_indigency_document') && in_array($ind->status, ['approved', 'ready_pickup', 'claimed']))
                                                            <button
                                                                type="button"
                                                                class="dropdown-item action-menu-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#docsModal{{ $ind->id }}"
                                                            >
                                                                <span class="action-menu-icon action-menu-icon-docs">
                                                                    <i class="fas fa-file-lines" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Generate documents</strong>
                                                                    <small>Create or download certificate files</small>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span
                                                                class="dropdown-item action-menu-item disabled"
                                                                aria-disabled="true"
                                                                title="Documents are available after approval"
                                                            >
                                                                <span class="action-menu-icon action-menu-icon-docs">
                                                                    <i class="fas fa-file-lines" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Documents unavailable</strong>
                                                                    <small>Approve the application first</small>
                                                                </span>
                                                            </span>
                                                        @endif

                                                        <div class="dropdown-divider"></div>

                                                        @if($ind->deleted_at && auth('admin')->user()->hasPermission('delete_indigency'))
                                                            <form
                                                                method="POST"
                                                                action="{{ route('admin.indigency.restore', $ind->id) }}"
                                                                class="action-menu-form"
                                                                data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this application?']) }}"
                                                            >
                                                                @csrf
                                                                <button type="submit" class="dropdown-item action-menu-item">
                                                                    <span class="action-menu-icon action-menu-icon-restore">
                                                                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="action-menu-copy">
                                                                        <strong>Restore application</strong>
                                                                        <small>Return this record to the active list</small>
                                                                    </span>
                                                                </button>
                                                            </form>
                                                        @elseif(auth('admin')->user()->hasPermission('delete_indigency'))
                                                            <form
                                                                method="POST"
                                                                action="{{ route('admin.indigency.destroy', $ind->id) }}"
                                                                class="action-menu-form"
                                                                data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this application?']) }}"
                                                            >
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item action-menu-item action-menu-danger">
                                                                    <span class="action-menu-icon action-menu-icon-archive">
                                                                        <i class="fas fa-box-archive" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="action-menu-copy">
                                                                        <strong>Archive application</strong>
                                                                        <small>Keep the record without deleting it</small>
                                                                    </span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="dropdown-item action-menu-item disabled" aria-disabled="true">
                                                                <span class="action-menu-icon action-menu-icon-archive">
                                                                    <i class="fas fa-box-archive" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Archive unavailable</strong>
                                                                    <small>You do not have permission</small>
                                                                </span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="fas fa-file-invoice fa-4x text-muted mb-3 opacity-50"></i>
                                                <h5 class="text-muted">No applications found</h5>
                                                <p class="text-muted mb-3 small">Try adjusting your search or filter.</p>

                                                @admin_can('create_indigency')
                                                    <button
                                                        class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addApplicationModal"
                                                    >
                                                        <i class="fas fa-plus me-2"></i>
                                                        Add New
                                                    </button>
                                                @endadmin_can
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($indigency) && $indigency->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $indigency->firstItem() ?? 0 }}-{{ $indigency->lastItem() ?? 0 }} of {{ $indigency->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $indigency->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            @admin_can('create_indigency')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    New Indigency Application
                                </h5>
                                <p class="mb-0 mt-1 text-muted small">Complete all 5 steps below. Fields marked <span class="text-danger">*</span> are required.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.indigency.store') }}" enctype="multipart/form-data" id="addApplicationForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">

                                <div class="resident-picker-section mb-4">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Select Resident</h6>
                                            <p>Search by resident name or Resident ID.</p>
                                        </div>
                                    </div>

                                    <div class="resident-search-wrapper position-relative">
                                        <div class="resident-search-box">
                                            <i class="fas fa-search resident-search-icon" aria-hidden="true"></i>

                                            <input
                                                type="text"
                                                id="residentSearchInput"
                                                class="form-control"
                                                placeholder="Search by name or Resident ID..."
                                                autocomplete="off"
                                                aria-label="Search registered resident"
                                                aria-controls="residentSearchResults"
                                                aria-expanded="false"
                                            >

                                            <button
                                                type="button"
                                                id="clearResidentSearch"
                                                class="resident-search-clear d-none"
                                                aria-label="Clear resident search"
                                            >
                                                <i class="fas fa-times" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                        <div
                                            id="residentSearchResults"
                                            class="resident-search-results d-none"
                                            role="listbox"
                                            aria-label="Resident search results"
                                        ></div>
                                    </div>

                                    <input type="hidden" name="resident_id" id="addResidentSelect" value="{{ old('resident_id') }}">

                                    <div id="selectedResidentCard" class="selected-resident-card d-none">
                                        <div class="selected-resident-avatar" aria-hidden="true">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div class="selected-resident-content">
                                            <div class="selected-resident-name" id="selectedResidentName"></div>

                                            <div class="selected-resident-meta">
                                                <span id="selectedResidentId"></span>
                                                <span>•</span>
                                                <span class="resident-active-status">
                                                    <i class="fas fa-circle me-1" aria-hidden="true"></i>Active resident
                                                </span>
                                            </div>

                                            <div class="selected-resident-address" id="selectedResidentAddress"></div>
                                        </div>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary resident-change-btn"
                                            id="changeResidentBtn"
                                        >
                                            <i class="fas fa-rotate me-1"></i>
                                            Change
                                        </button>
                                    </div>

                                    <div class="resident-autofill-status" id="addResidentStatus">
                                        <i class="fas fa-circle-info" aria-hidden="true"></i>
                                        <span>Select a registered resident to automatically fill their information.</span>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Personal Information</h6>
                                            <p>Legal name and basic profile details as they should appear on the certificate.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-user"></i>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('first_name') is-invalid @enderror @endif"
                                                   name="first_name" id="add_first_name" value="{{ session('form_type') == 'add' ? old('first_name') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-user"></i>Middle Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('middle_name') is-invalid @enderror @endif"
                                                   name="middle_name" id="add_middle_name" value="{{ session('form_type') == 'add' ? old('middle_name') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-user"></i>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('last_name') is-invalid @enderror @endif"
                                                   name="last_name" id="add_last_name" value="{{ session('form_type') == 'add' ? old('last_name') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-signature"></i>Suffix</label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('suffix') is-invalid @enderror @endif"
                                                       name="suffix" id="add_suffix">
                                                <option value="">None</option>
                                                <option value="Jr." {{ (session('form_type') == 'add' && old('suffix') == 'Jr.') ? 'selected' : '' }}>Jr.</option>
                                                <option value="Sr." {{ (session('form_type') == 'add' && old('suffix') == 'Sr.') ? 'selected' : '' }}>Sr.</option>
                                                <option value="II" {{ (session('form_type') == 'add' && old('suffix') == 'II') ? 'selected' : '' }}>II</option>
                                                <option value="III" {{ (session('form_type') == 'add' && old('suffix') == 'III') ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'add' && old('suffix') == 'IV') ? 'selected' : '' }}>IV</option>
                                                <option value="V" {{ (session('form_type') == 'add' && old('suffix') == 'V') ? 'selected' : '' }}>V</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-calendar"></i>Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif"
                                                   name="birthdate" id="add_birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'add')
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-venus-mars"></i>Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('gender') is-invalid @enderror @endif"
                                                       name="gender" id="add_gender" required>
                                                <option value="">Select gender</option>
                                                <option value="male" {{ (session('form_type') == 'add' && old('gender') == 'male') ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ (session('form_type') == 'add' && old('gender') == 'female') ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ (session('form_type') == 'add' && old('gender') == 'other') ? 'selected' : '' }}>Other</option>
                                                <option value="prefer not to say" {{ (session('form_type') == 'add' && old('gender') == 'prefer not to say') ? 'selected' : '' }}>Prefer not to say</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-ring"></i>Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('civil_status') is-invalid @enderror @endif"
                                                    name="civil_status" id="add_civil_status" required>
                                                <option value="">Select status</option>
                                                <option value="single" {{ (session('form_type') == 'add' && old('civil_status') == 'single') ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ (session('form_type') == 'add' && old('civil_status') == 'married') ? 'selected' : '' }}>Married</option>
                                                <option value="widowed" {{ (session('form_type') == 'add' && old('civil_status') == 'widowed') ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ (session('form_type') == 'add' && old('civil_status') == 'separated') ? 'selected' : '' }}>Separated</option>
                                                <option value="divorced" {{ (session('form_type') == 'add' && old('civil_status') == 'divorced') ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('civil_status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-map-pin"></i>Place of Birth <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('birth_place') is-invalid @enderror @endif"
                                                   name="birth_place" id="add_birth_place" value="{{ session('form_type') == 'add' ? old('birth_place') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('birth_place')
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
                                            <h6>Contact Information</h6>
                                            <p>How the applicant should be reached and notified about this request.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-envelope"></i>Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'add') @error('email') is-invalid @enderror @endif"
                                                   name="email" id="add_email" value="{{ session('form_type') == 'add' ? old('email') : '' }}" placeholder="email@example.com" required>
                                            @if(session('form_type') == 'add')
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-phone"></i>Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('contact_number') is-invalid @enderror @endif"
                                                   name="contact_number" id="add_contact_number" value="{{ session('form_type') == 'add' ? old('contact_number') : '' }}" placeholder="09XXXXXXXXX" maxlength="11" required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'add')
                                                @error('contact_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-map-marker-alt"></i>Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('address') is-invalid @enderror @endif"
                                                      name="address" id="add_address" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'add' ? old('address') : '' }}</textarea>
                                            @if(session('form_type') == 'add')
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">4</div>
                                        <div>
                                            <h6>Indigency Details</h6>
                                            <p>These values describe the basis of the indigency certificate.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-wallet"></i>Monthly Income <span class="text-danger">*</span></label>
                                            <select class="form-control @if(session('form_type') == 'add') @error('monthly_income') is-invalid @enderror @endif"
                                                   name="monthly_income" value="{{ session('form_type') == 'add' ? old('monthly_income') : '' }}" required>
                                                   <option value="">Select income bracket</option>
                                                    <option value="below 5k" {{ (session('form_type') == 'add' && old('monthly_income') == 'below 5k') ? 'selected' : '' }}>Below &#8369;5,000</option>
                                                    <option value="5k-8k" {{ (session('form_type') == 'add' && old('monthly_income') == '5k-8k') ? 'selected' : '' }}>&#8369;5,000 - &#8369;8,000</option>
                                                    <option value="8k-10k" {{ (session('form_type') == 'add' && old('monthly_income') == '8k-10k') ? 'selected' : '' }}>&#8369;8,001 - &#8369;10,000</option>
                                                    <option value="10k-15k" {{ (session('form_type') == 'add' && old('monthly_income') == '10k-15k') ? 'selected' : '' }}>&#8369;10,001 - &#8369;15,000</option>
                                                    <option value="no income" {{ (session('form_type') == 'add' && old('monthly_income') == 'no income') ? 'selected' : '' }}>No fixed income</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('monthly_income')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-users"></i>Household Members <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @if(session('form_type') == 'add') @error('household_members') is-invalid @enderror @endif"
                                                   name="household_members" value="{{ session('form_type') == 'add' ? old('household_members') : '' }}" min="1" max="20" placeholder="e.g., 4" required>
                                            @if(session('form_type') == 'add')
                                                @error('household_members')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-bullseye"></i>Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('purpose') is-invalid @enderror @endif"
                                                    name="purpose" id="purpose" required>
                                                <option value="">Select purpose</option>
                                                <option value="medical" {{ (session('form_type') == 'add' && old('purpose') == 'medical') ? 'selected' : '' }}>Medical / Hospital assistance</option>
                                                <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship / Financial aid</option>
                                                <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government program (4Ps, AICS)</option>
                                                <option value="legal" {{ (session('form_type') == 'add' && old('purpose') == 'legal') ? 'selected' : '' }}>Legal assistance (PAO, etc.)</option>
                                                <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment requirement (low income proof)</option>
                                                <option value="burial" {{ (session('form_type') == 'add' && old('purpose') == 'burial') ? 'selected' : '' }}>Burial assistance</option>
                                                <option value="other" {{ (session('form_type') == 'add' && old('purpose') == 'other') ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('purpose')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12" id="otherPurposeField" style="{{ (session('form_type') == 'add' && old('purpose') == 'other') ? 'display:block;' : 'display:none;' }}">
                                            <label class="form-label"><i class="fas fa-pen-line"></i>Specify Other Purpose <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('purpose_other') is-invalid @enderror @endif"
                                                   name="purpose_other" value="{{ session('form_type') == 'add' ? old('purpose_other') : '' }}" placeholder="Please specify">
                                            @if(session('form_type') == 'add')
                                                @error('purpose_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">5</div>
                                        <div>
                                            <h6>Required Documents</h6>
                                            <p>Upload clear copies to support this application. JPG, PNG or PDF, max 5MB each.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="document-upload-card">
                                                <div class="document-upload-icon" aria-hidden="true">
                                                    <i class="fas fa-house-chimney"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-label mb-1">Proof of Residency <span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control @if(session('form_type') == 'add') @error('primary_proof') is-invalid @enderror @endif"
                                                           name="primary_proof" accept="image/*,.pdf" required>
                                                    <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                                    @if(session('form_type') == 'add')
                                                        @error('primary_proof')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="document-upload-card h-100">
                                                <div class="document-upload-icon" aria-hidden="true"><i class="fas fa-id-card"></i></div>
                                                <div class="flex-grow-1">
                                                    <label class="form-label mb-1">Valid ID - Front</label>
                                                    <input type="file" class="form-control @if(session('form_type') == 'add') @error('valid_id_path') is-invalid @enderror @endif"
                                                           name="valid_id_path" id="add_valid_id_path" accept="image/*,.pdf">
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB</small>
                                                    @if(session('form_type') == 'add')
                                                        @error('valid_id_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="document-upload-card h-100">
                                                <div class="document-upload-icon" aria-hidden="true"><i class="fas fa-id-card"></i></div>
                                                <div class="flex-grow-1">
                                                    <label class="form-label mb-1">Valid ID - Back</label>
                                                    <input type="file" class="form-control @if(session('form_type') == 'add') @error('valid_id_back_path') is-invalid @enderror @endif"
                                                           name="valid_id_back_path" id="add_valid_id_back_path" accept="image/*,.pdf">
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB</small>
                                                    @if(session('form_type') == 'add')
                                                        @error('valid_id_back_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div id="addResidentIdStatus" class="resident-document-status d-none"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" form="addApplicationForm" class="btn btn-primary" id="submitAddForm">
                                <i class="fas fa-save me-2"></i>Save Application
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_indigency'))
                @foreach($indigency as $ind)
                <div class="modal fade" id="editIndigencyModal{{ $ind->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Indigency Application - {{ $ind->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.indigency.update', $ind->id) }}" enctype="multipart/form-data" id="editIndigencyForm{{ $ind->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $ind->id }}">

                                    <div class="row g-3">

                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('first_name') is-invalid @enderror @endif"
                                                name="first_name" id="edit_first_name_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('first_name', $ind->first_name) : $ind->first_name }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('middle_name') is-invalid @enderror @endif"
                                                name="middle_name" id="edit_middle_name_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('middle_name', $ind->middle_name) : $ind->middle_name }}">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('last_name') is-invalid @enderror @endif"
                                                name="last_name" id="edit_last_name_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('last_name', $ind->last_name) : $ind->last_name }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('suffix') is-invalid @enderror @endif"
                                                    name="suffix" id="edit_suffix_{{ $ind->id }}">
                                                <option value="">None</option>
                                                <option value="Jr." {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                <option value="Sr." {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                <option value="II" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                                <option value="III" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                                <option value="V" {{ (session('form_type') == 'edit_' . $ind->id ? old('suffix', $ind->suffix) : $ind->suffix) == 'V' ? 'selected' : '' }}>V</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('birthdate') is-invalid @enderror @endif"
                                                name="birthdate" id="edit_birthdate_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id
                                                    ? old('birthdate', $ind->birthdate ? \Carbon\Carbon::parse($ind->birthdate)->format('Y-m-d') : '')
                                                    : ($ind->birthdate ? \Carbon\Carbon::parse($ind->birthdate)->format('Y-m-d') : '') }}"
                                                required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('gender') is-invalid @enderror @endif"
                                                    name="gender" id="edit_gender_{{ $ind->id }}" required>
                                                <option value="">Select gender</option>
                                                <option value="male" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                <option value="prefer not to say" {{ (session('form_type') == 'edit_' . $ind->id ? old('gender', $ind->gender) : $ind->gender) == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('civil_status') is-invalid @enderror @endif"
                                                    name="civil_status" id="edit_civil_status_{{ $ind->id }}" required>
                                                <option value="">Select status</option>
                                                <option value="single" {{ (session('form_type') == 'edit_' . $ind->id ? old('civil_status', $ind->civil_status) : $ind->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ (session('form_type') == 'edit_' . $ind->id ? old('civil_status', $ind->civil_status) : $ind->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="widowed" {{ (session('form_type') == 'edit_' . $ind->id ? old('civil_status', $ind->civil_status) : $ind->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ (session('form_type') == 'edit_' . $ind->id ? old('civil_status', $ind->civil_status) : $ind->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                                                <option value="divorced" {{ (session('form_type') == 'edit_' . $ind->id ? old('civil_status', $ind->civil_status) : $ind->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('civil_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('birth_place') is-invalid @enderror @endif"
                                                   name="birth_place" id="edit_birth_place_{{ $ind->id }}"
                                                   value="{{ session('form_type') == 'edit_' . $ind->id ? old('birth_place', $ind->birth_place) : $ind->birth_place }}" required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('birth_place')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Contact Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('email') is-invalid @enderror @endif"
                                                name="email" id="edit_email_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('email', $ind->email) : $ind->email }}"
                                                placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('contact_number') is-invalid @enderror @endif"
                                                name="contact_number" id="edit_contact_number_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('contact_number', $ind->contact_number) : $ind->contact_number }}"
                                                placeholder="09XXXXXXXXX" maxlength="11" required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('contact_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('address') is-invalid @enderror @endif"
                                                    name="address" id="edit_address_{{ $ind->id }}"
                                                    rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $ind->id ? old('address', $ind->address) : $ind->address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Indigency Details</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                            <select class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('monthly_income') is-invalid @enderror @endif"
                                                name="monthly_income" id="edit_monthly_income_{{ $ind->id }}" required>
                                                <option value="">Select income bracket</option>
                                                <option value="below 5k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == 'below 5k' ? 'selected' : '' }}>Below &#8369;5,000</option>
                                                <option value="5k-8k" {{ (session('form_type') == 'edit_'. $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '5k-8k' ? 'selected' : '' }}>&#8369;5,000 - &#8369;8,000</option>
                                                <option value="8k-10k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '8k-10k' ? 'selected' : '' }}>&#8369;8,001 - &#8369;10,000</option>
                                                <option value="10k-15k" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income) == '10k-15k' ? 'selected' : '' }}>&#8369;10,001 - &#8369;15,000</option>
                                                <option value="no income" {{ (session('form_type') == 'edit_' . $ind->id ? old('monthly_income', $ind->monthly_income) : $ind->monthly_income)  == 'no income' ? 'selected' : '' }}>No fixed income</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('monthly_income')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Household Members <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('household_members') is-invalid @enderror @endif"
                                                name="household_members" id="edit_household_members_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('household_members', $ind->household_members) : $ind->household_members }}"
                                                min="1" max="20" required>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('household_members')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ind->id) @error('purpose') is-invalid @enderror @endif"
                                                    name="purpose" id="edit_purpose_{{ $ind->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="medical" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'medical' ? 'selected' : '' }}>Medical / Hospital assistance</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship / Financial aid</option>
                                                <option value="government" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'government' ? 'selected' : '' }}>Government program (4Ps, AICS)</option>
                                                <option value="legal" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'legal' ? 'selected' : '' }}>Legal assistance (PAO, etc.)</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'employment' ? 'selected' : '' }}>Employment requirement (low income proof)</option>
                                                <option value="burial" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'burial' ? 'selected' : '' }}>Burial assistance</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ind->id ? old('purpose', $ind->purpose) : $ind->purpose) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('purpose')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12" id="edit_otherPurposeField_{{ $ind->id }}" style="{{ (session('form_type') == 'edit_' . $ind->id && old('purpose', $ind->purpose) == 'other') ? 'display:block;' : 'display:none;' }}">
                                            <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('purpose_other') is-invalid @enderror @endif"
                                                name="purpose_other" id="edit_purpose_other_{{ $ind->id }}"
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('purpose_other', $ind->purpose_other) : $ind->purpose_other }}"
                                                placeholder="Please specify">
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('purpose_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current file)</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Proof of Residency</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('primary_proof') is-invalid @enderror @endif"
                                                name="primary_proof" id="edit_primary_proof_{{ $ind->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('primary_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID - Front</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('valid_id_path') is-invalid @enderror @endif"
                                                name="valid_id_path" id="edit_valid_id_path_{{ $ind->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('valid_id_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID - Back</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('valid_id_back_path') is-invalid @enderror @endif"
                                                name="valid_id_back_path" id="edit_valid_id_back_path_{{ $ind->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('valid_id_back_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        @if($ind->primary_proof)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current proof of residency: {{ basename($ind->primary_proof) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($ind->valid_id_path)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current file: {{ basename($ind->valid_id_path) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($ind->valid_id_back_path)
                                        <div class="col-12"><small class="text-info"><i class="fas fa-info-circle me-1"></i>Current Valid ID back: {{ basename($ind->valid_id_back_path) }}</small></div>
                                        @endif
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editIndigencyForm{{ $ind->id }}" class="btn btn-primary" id="submitEditForm{{ $ind->id }}">
                                        <i class="fas fa-save me-2"></i>Update Application
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($indigency as $ind)
            <div class="modal fade" id="viewModal{{ $ind->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">{{ strtoupper(substr($ind->first_name, 0, 1) . substr($ind->last_name, 0, 1)) }}</div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $ind->first_name }} {{ $ind->last_name }}</div>
                                    <div class="view-modal-sub">{{ $ind->reference_number }} - Indigency Certificate</div>
                                    <div class="mt-1">
                                        @if($ind->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($ind->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @elseif($ind->status == 'ready_pickup')
                                            <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                        @elseif($ind->status == 'claimed')
                                            <span class="badge bg-primary-subtle text-primary">Claimed</span>
                                        @elseif($ind->status == 'under_review')
                                            <span class="badge bg-secondary-subtle text-secondary">Under Review</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $ind->id }}" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active" id="details-tab-{{ $ind->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $ind->id }}" type="button">Details</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="edit-tab-{{ $ind->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $ind->id }}" type="button">Edit info</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab-{{ $ind->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $ind->id }}" type="button">History</button></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details-panel-{{ $ind->id }}" role="tabpanel">
                                    <div class="application-modal-summary">
                                        <div class="modal-summary-card is-primary">
                                            <span class="modal-summary-label">Applicant</span>
                                            <span class="modal-summary-value">{{ $ind->first_name }} {{ $ind->middle_name }} {{ $ind->last_name }} {{ $ind->suffix }}</span>
                                            <span class="modal-summary-help"><i class="fas fa-hashtag me-1"></i>{{ $ind->reference_number }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Current Status</span>
                                            <span class="modal-summary-value">
                                                @if($ind->deleted_at) Archived
                                                @elseif($ind->status == 'ready_pickup') Ready for Pickup
                                                @else {{ ucwords(str_replace('_', ' ', $ind->status)) }}
                                                @endif
                                            </span>
                                            <span class="modal-summary-help">Application progress</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Income / Household</span>
                                            <span class="modal-summary-value">{{ $ind->monthly_income_label }}</span>
                                            <span class="modal-summary-help">{{ $ind->household_label }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Submitted</span>
                                            <span class="modal-summary-value">{{ $ind->created_at ? $ind->created_at->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $ind->created_at ? $ind->created_at->format('h:i A') : '' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-user-check"></i>Important applicant information</div>
                                        <div class="detail-grid">
                                            <div class="detail-item"><span class="detail-label">Birthdate</span><div class="detail-value">{{ $ind->birthdate ? \Carbon\Carbon::parse($ind->birthdate)->format('F d, Y') : 'N/A' }}</div></div>
                                            <div class="detail-item"><span class="detail-label">Gender</span><div class="detail-value">{{ ucfirst($ind->gender) }}</div></div>
                                            <div class="detail-item"><span class="detail-label">Email</span><div class="detail-value">{{ $ind->email ?: 'N/A' }}</div></div>
                                            <div class="detail-item"><span class="detail-label">Contact number</span><div class="detail-value">{{ $ind->contact_number ?: 'N/A' }}</div></div>
                                            <div class="detail-item" style="grid-column: 1 / -1;"><span class="detail-label">Address</span><div class="detail-value">{{ $ind->address ?: 'N/A' }}</div></div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-hand-holding-heart"></i>Indigency request</div>
                                        <div class="detail-grid">
                                            <div class="detail-item"><span class="detail-label">Monthly income</span><div class="detail-value">{{ $ind->monthly_income_label }}</div></div>
                                            <div class="detail-item"><span class="detail-label">Household members</span><div class="detail-value">{{ $ind->household_members ?: 'N/A' }}</div></div>
                                            <div class="detail-item" style="grid-column: 1 / -1;"><span class="detail-label">Purpose</span><div class="detail-value">{{ $ind->purpose ? ucwords(str_replace('_', ' ', $ind->purpose)) : 'N/A' }} {{ $ind->purpose_other ? '(' . $ind->purpose_other . ')' : '' }}</div></div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-folder-open"></i>Required documents</div>
                                        <div class="document-preview-grid">
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-house me-1"></i>Proof of Residency</span></div>
                                                <div class="doc-body">
                                                    @if($ind->primary_proof)
                                                        <img src="{{ route('admin.secure.file', ['path' => $ind->primary_proof]) }}" alt="Proof of residency" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $ind->primary_proof])]) }}">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No proof uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Front</span></div>
                                                <div class="doc-body">
                                                    @if($ind->valid_id_path)
                                                        <img src="{{ route('admin.secure.file', ['path' => $ind->valid_id_path]) }}" alt="Valid ID front" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $ind->valid_id_path])]) }}">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No valid ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Back</span></div>
                                                <div class="doc-body">
                                                    @if($ind->valid_id_back_path)
                                                        <img src="{{ route('admin.secure.file', ['path' => $ind->valid_id_back_path]) }}" alt="Valid ID back" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $ind->valid_id_back_path])]) }}">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No back ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade edit-info-pane" id="edit-panel-{{ $ind->id }}" role="tabpanel">
                                    <div class="edit-info-banner">
                                        <div class="application-avatar">{{ strtoupper(substr($ind->first_name, 0, 1) . substr($ind->last_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="edit-info-title">Edit information for {{ $ind->first_name }} {{ $ind->last_name }}</div>
                                            <div class="edit-info-subtitle">Reference {{ $ind->reference_number }} · Update applicant details, income profile, purpose, and replacement documents.</div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.indigency.update', $ind->id) }}" id="quickEditForm{{ $ind->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
                                        @csrf
                                        @method('PUT')

                                        <div class="edit-section-card">
                                            <div class="edit-section-head"><span class="edit-section-icon"><i class="fas fa-user"></i></span><div><div class="edit-section-title">Personal Information</div><p class="edit-section-help">Keep the applicant identity accurate for document generation.</p></div></div>
                                            <div class="edit-section-body"><div class="edit-form-grid">
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-user"></i>First Name</label><input type="text" name="first_name" class="form-control" value="{{ $ind->first_name }}" required></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-user"></i>Middle Name</label><input type="text" name="middle_name" class="form-control" value="{{ $ind->middle_name }}"></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-user"></i>Last Name</label><input type="text" name="last_name" class="form-control" value="{{ $ind->last_name }}" required></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-signature"></i>Suffix</label><select class="form-select" name="suffix"><option value="">None</option><option value="Jr." {{ $ind->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option><option value="Sr." {{ $ind->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option><option value="II" {{ $ind->suffix == 'II' ? 'selected' : '' }}>II</option><option value="III" {{ $ind->suffix == 'III' ? 'selected' : '' }}>III</option><option value="IV" {{ $ind->suffix == 'IV' ? 'selected' : '' }}>IV</option><option value="V" {{ $ind->suffix == 'V' ? 'selected' : '' }}>V</option></select></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-calendar"></i>Birthdate</label><input type="date" name="birthdate" class="form-control" value="{{ $ind->birthdate ? \Carbon\Carbon::parse($ind->birthdate)->format('Y-m-d') : '' }}" required max="{{ date('Y-m-d') }}"></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label><select class="form-select" name="gender" required><option value="male" {{ $ind->gender == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ $ind->gender == 'female' ? 'selected' : '' }}>Female</option><option value="other" {{ $ind->gender == 'other' ? 'selected' : '' }}>Other</option><option value="prefer not to say" {{ $ind->gender == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option></select></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-ring"></i>Civil Status</label><select class="form-select" name="civil_status" required><option value="single" {{ $ind->civil_status == 'single' ? 'selected' : '' }}>Single</option><option value="married" {{ $ind->civil_status == 'married' ? 'selected' : '' }}>Married</option><option value="widowed" {{ $ind->civil_status == 'widowed' ? 'selected' : '' }}>Widowed</option><option value="separated" {{ $ind->civil_status == 'separated' ? 'selected' : '' }}>Separated</option><option value="divorced" {{ $ind->civil_status == 'divorced' ? 'selected' : '' }}>Divorced</option></select></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-location-dot"></i>Place of Birth</label><input type="text" name="birth_place" class="form-control" value="{{ $ind->birth_place }}" required></div>
                                            </div></div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head"><span class="edit-section-icon"><i class="fas fa-address-book"></i></span><div><div class="edit-section-title">Contact Information</div><p class="edit-section-help">Used for applicant notification and request follow-up.</p></div></div>
                                            <div class="edit-section-body"><div class="edit-form-grid">
                                                <div class="edit-field edit-col-6"><label class="form-label"><i class="fas fa-envelope"></i>Email</label><input type="email" name="email" class="form-control" value="{{ $ind->email }}" required></div>
                                                <div class="edit-field edit-col-6"><label class="form-label"><i class="fas fa-phone"></i>Contact Number</label><input type="text" name="contact_number" class="form-control" value="{{ $ind->contact_number }}" maxlength="11" required><small>Use Philippine mobile format, for example 09XXXXXXXXX.</small></div>
                                                <div class="edit-field edit-col-12"><label class="form-label"><i class="fas fa-map-marker-alt"></i>Complete Address</label><textarea name="address" class="form-control" rows="2" required>{{ $ind->address }}</textarea></div>
                                            </div></div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head"><span class="edit-section-icon"><i class="fas fa-hand-holding-heart"></i></span><div><div class="edit-section-title">Indigency Details</div><p class="edit-section-help">These values describe the basis of the indigency certificate.</p></div></div>
                                            <div class="edit-section-body"><div class="edit-form-grid">
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-wallet"></i>Monthly Income</label><select class="form-select" name="monthly_income" required><option value="below 5k" {{ $ind->monthly_income == 'below 5k' ? 'selected' : '' }}>Below PHP 5,000</option><option value="5k-8k" {{ $ind->monthly_income == '5k-8k' ? 'selected' : '' }}>PHP 5,000 - PHP 8,000</option><option value="8k-10k" {{ $ind->monthly_income == '8k-10k' ? 'selected' : '' }}>PHP 8,001 - PHP 10,000</option><option value="10k-15k" {{ $ind->monthly_income == '10k-15k' ? 'selected' : '' }}>PHP 10,001 - PHP 15,000</option><option value="no income" {{ $ind->monthly_income == 'no income' ? 'selected' : '' }}>No fixed income</option></select></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-users"></i>Household Members</label><input type="number" name="household_members" class="form-control" min="1" max="20" value="{{ $ind->household_members }}" required></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-bullseye"></i>Purpose</label><select class="form-select" name="purpose" required><option value="medical" {{ $ind->purpose == 'medical' ? 'selected' : '' }}>Medical / Hospital assistance</option><option value="scholarship" {{ $ind->purpose == 'scholarship' ? 'selected' : '' }}>Scholarship / Financial aid</option><option value="government" {{ $ind->purpose == 'government' ? 'selected' : '' }}>Government program</option><option value="legal" {{ $ind->purpose == 'legal' ? 'selected' : '' }}>Legal assistance</option><option value="employment" {{ $ind->purpose == 'employment' ? 'selected' : '' }}>Employment requirement</option><option value="burial" {{ $ind->purpose == 'burial' ? 'selected' : '' }}>Burial assistance</option><option value="other" {{ $ind->purpose == 'other' ? 'selected' : '' }}>Other</option></select></div>
                                                <div class="edit-field edit-col-12"><label class="form-label"><i class="fas fa-pen-line"></i>Purpose Other</label><input type="text" name="purpose_other" class="form-control" value="{{ $ind->purpose_other }}" placeholder="Please specify if purpose is other"></div>
                                            </div></div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head"><span class="edit-section-icon"><i class="fas fa-folder-open"></i></span><div><div class="edit-section-title">Documents</div><p class="edit-section-help">Leave uploads empty to keep the current submitted files.</p></div></div>
                                            <div class="edit-section-body"><div class="edit-form-grid">
                                                <div class="edit-field edit-col-6"><div class="edit-file-card"><label class="form-label"><i class="fas fa-file-image"></i>Proof of Residency</label><input type="file" name="primary_proof" class="form-control" accept="image/*,.pdf"><small>Upload image or PDF. Leave empty to keep current file.</small>@if($ind->primary_proof)<span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($ind->primary_proof) }}</span>@endif</div></div>
                                                <div class="edit-field edit-col-6"><div class="edit-file-card"><label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Front</label><input type="file" name="valid_id_path" class="form-control" accept="image/*,.pdf"><small>Upload image or PDF. Leave empty to keep current file.</small>@if($ind->valid_id_path)<span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($ind->valid_id_path) }}</span>@endif</div></div>
                                                <div class="edit-field edit-col-6"><div class="edit-file-card"><label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Back</label><input type="file" name="valid_id_back_path" class="form-control" accept="image/*,.pdf"><small>Upload image or PDF. Leave empty to keep current file.</small>@if($ind->valid_id_back_path)<span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($ind->valid_id_back_path) }}</span>@endif</div></div>
                                                <div class="edit-field edit-col-12"><span class="edit-required-note"><i class="fas fa-circle-info"></i> Only replace documents when the applicant submitted corrected files.</span></div>
                                            </div></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="history-panel-{{ $ind->id }}" role="tabpanel">
                                    <div class="mb-2">Recent status: @if($ind->status == 'approved')<span class="badge bg-success-subtle text-success">Approved</span>@elseif($ind->status == 'rejected')<span class="badge bg-danger-subtle text-danger">Rejected</span>@elseif($ind->status == 'ready_pickup')<span class="badge bg-info-subtle text-info">Ready for Pickup</span>@elseif($ind->status == 'claimed')<span class="badge bg-primary-subtle text-primary">Claimed</span>@elseif($ind->status == 'under_review')<span class="badge bg-secondary-subtle text-secondary">Under Review</span>@else<span class="badge bg-warning-subtle text-warning">Processing</span>@endif</div>
                                    <div id="historyList{{ $ind->id }}" class="small text-muted">Loading history...</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="viewFooterDetails{{ $ind->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-switch-tab="edit" data-app-id="{{ $ind->id }}">Edit info</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterEdit{{ $ind->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-switch-tab="details" data-app-id="{{ $ind->id }}">Cancel</button>
                            <button type="submit" form="quickEditForm{{ $ind->id }}" class="btn btn-primary">Save changes</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterHistory{{ $ind->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="docsModal{{ $ind->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-file-lines me-2"></i>Document Actions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if(in_array($ind->status, ['approved', 'ready_pickup', 'claimed']))
                            <div class="d-grid gap-2">
                                <form method="GET" action="{{ route('admin.indigency.document.indigency_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $ind->id }}"><button type="submit" name="action" value="download" class="btn btn-outline-primary w-100">Download Word</button></form>
                                <form method="GET" action="{{ route('admin.indigency.document.indigency_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $ind->id }}"><button type="submit" name="action" value="print" class="btn btn-outline-success w-100">Print PDF</button></form>
                            </div>
                            @else
                            <p class="mb-0 text-muted">Documents are available only when status is Approved, Ready for Pickup, or Claimed.</p>
                            @endif
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $ind->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-pen-to-square me-2"></i>Update application status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.indigency.status', $ind->id) }}" data-submit-call="handleUpdateStatusSubmit" data-submit-pass="event,self">
                            @csrf
                            <div class="modal-body">
                                <div class="status-flow-card">
                                    <div class="status-flow-person">
                                        <div class="application-avatar">{{ strtoupper(substr($ind->first_name, 0, 1) . substr($ind->last_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="application-name">{{ $ind->first_name }} {{ $ind->last_name }}</div>
                                            <div class="application-ref"><span class="ref-pill"><i class="fas fa-hashtag"></i>{{ $ind->reference_number }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3"><label class="form-label text-uppercase fw-semibold small">New status</label><input type="hidden" name="status" value="{{ $ind->status }}" class="selected-status-input"><div class="status-grid" data-current-status="{{ $ind->status }}"><button type="button" class="status-option" data-status="pending"><span class="dot" style="background:#ed6c02;"></span>Pending</button><button type="button" class="status-option" data-status="under_review"><span class="dot" style="background:#0288d1;"></span>Under review</button><button type="button" class="status-option" data-status="processing"><span class="dot" style="background:#5e35b1;"></span>Processing</button><button type="button" class="status-option" data-status="approved"><span class="dot" style="background:#2e7d32;"></span>Approved</button><button type="button" class="status-option" data-status="ready_pickup"><span class="dot" style="background:#00acc1;"></span>Ready for pickup</button><button type="button" class="status-option" data-status="claimed"><span class="dot" style="background:#43a047;"></span>Claimed</button><button type="button" class="status-option" data-status="rejected" style="grid-column: span 3;"><span class="dot" style="background:#d32f2f;"></span>Rejected</button></div></div>
                                <div class="mb-3"><label class="form-label text-uppercase fw-semibold small">Remarks <span class="text-muted fw-normal">(optional - required if rejected, max 40 chars)</span></label><textarea class="form-control" name="remarks" rows="3" maxlength="40" placeholder="Add a note about this status change..."></textarea></div>
                                <div class="{{ in_array($ind->status, ['approved', 'ready_pickup', 'rejected']) ? '' : 'd-none' }}" data-notify-wrap data-has-email="{{ $ind->email ? '1' : '0' }}" data-has-sms="{{ $ind->contact_number ? '1' : '0' }}">
                                    <label class="form-label text-uppercase fw-semibold small">Notify applicant</label>
                                    <div class="notify-row">
                                        <div class="notify-label">
                                            <strong>Send email</strong>
                                            <small>{{ $ind->email ?: 'No email available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" checked {{ !$ind->email ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="notify-row mb-0">
                                        <div class="notify-label">
                                            <strong>Send SMS</strong>
                                            <small>{{ $ind->contact_number ?: 'No mobile number available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_sms" value="1" checked {{ !$ind->contact_number ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Notifications are available for Approved, Ready for Pickup, and Rejected status only.</small>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Confirm &amp; save</button></div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="modal fade image-zoom-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-image"></i>
                                <span class="modal-title-stack">
                                    <span>Image Preview</span>
                                    <span class="modal-subtitle">View the uploaded image in a larger preview.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="img-fluid" alt="Uploaded image preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script src="{{ asset('js/pages/admin/admin_indigency.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-indigency-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_indigency-dynamic1.js') }}"></script>
            @endif
                    </div>
@endsection

@push('scripts')
@php
                $residentsForJs = $residents->map(function ($resident) {
                    return [
                        'id' => $resident->id,
                        'first_name' => $resident->firstname,
                        'middle_name' => $resident->middlename,
                        'last_name' => $resident->lastname,
                        'suffix' => $resident->suffix,
                        'birthdate' => optional($resident->birthdate)->format('Y-m-d'),
                        'gender' => strtolower((string) $resident->gender),
                        'civil_status' => strtolower((string) $resident->civil_status),
                        'birth_place' => $resident->place_of_birth,
                        'address' => $resident->address,
                        'contact' => $resident->contact,
                        'email' => $resident->email,
                        'valid_id' => $resident->valid_id,
                        'valid_id_back' => $resident->valid_id_back,
                        'valid_id_status' => $resident->valid_id_status,
                        'valid_id_verified' => (bool) $resident->valid_id_verified,
                        'full_name' => $resident->full_name,
                    ];
                })->values();
@endphp
@include('shared.js-config', [
    'id' => 'js-config-admin-indigency-main',
    'data' => [
        'residents' => $residentsForJs,
        'showAddModal' => $errors->any() && session('form_type') === 'add',
        'canUpdate' => auth('admin')->user()->hasPermission('update_indigency'),
        'applicationIds' => $indigency->pluck('id')->values(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_indigency-main.js') }}"></script>

@include('shared.js-config', [
    'id' => 'js-config-admin-admin-indigency-2',
    'data' => [
        'v1' => e(route('admin.notifications.remarksHistory')),
    ],
])
<script src="{{ asset('js/pages/admin/admin_indigency-dynamic2.js') }}"></script>
@endpush
