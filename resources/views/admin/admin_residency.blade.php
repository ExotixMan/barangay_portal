@extends('admin.layouts.admin')

@section('title', 'Residency Applications')
@section('page_title', 'Residency Applications')
@section('page_icon', 'fas fa-file-alt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/residency-applications.css') }}">

@endpush

@section('content')
    <div class="residency-page">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show residency-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show residency-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
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
                        <div class="residency-hero-icon"><i class="fas fa-file-signature"></i></div>
                        <div class="residency-kicker">Registry workflow</div>
                        <h2>Review, verify, update, and release residency applications with less clutter.</h2>
                        <p>Track pending reviews, approved certificates, archived records, and document generation in one consistent workspace.</p>
                    </div>
                    <div class="residency-hero-actions">
                        @admin_can('create_residency')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                            <i class="fas fa-plus"></i>
                            New Application
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.residency.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset
                        </a>
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-layer-group"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Approved: <strong>{{ $approved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-box-archive"></i> Rejected: <strong>{{ $rejected_count ?? 0 }}</strong></span>
                </div>
            </section>

            <div class="row g-3 g-lg-4 mb-4 residency-stats">
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
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Processing</div>
                            <div class="stat-number">{{ $processing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Pending review
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

            <div class="card border-0 mb-4 residency-filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.residency.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Applications</h6>
                                    <p>Search references, residents, contact details, status, and residency type.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_residency')
                                    <a href="#" class="btn btn-danger flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">New Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.residency.index') }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
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
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="residency_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="owner" {{ request('residency_type') == 'owner' ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                    <option value="renter" {{ request('residency_type') == 'renter' ? 'selected' : '' }}>Renter / Tenant</option>
                                    <option value="family" {{ request('residency_type') == 'family' ? 'selected' : '' }}>Living with Family</option>
                                    <option value="relative" {{ request('residency_type') == 'relative' ? 'selected' : '' }}>Living with Relative</option>
                                    <option value="boarder" {{ request('residency_type') == 'boarder' ? 'selected' : '' }}>Boarder / Lodger</option>
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
                        $isArchivedView = request('status') === 'archived';
                    @endphp
                    @if(auth('admin')->user()->hasAnyPermission(['delete_residency', 'export_residency']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_residency')
                        <form
                            id="bulkForm"
                            method="POST"
                            action="{{ $isArchivedView ? route('admin.residency.bulkRestore') : route('admin.residency.bulkDelete') }}"
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

                        @admin_can('export_residency')
                        <form id="exportForm" method="POST" action="{{ route('admin.residency.export') }}" style="display: inline;">
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

            <div class="card border-0 residency-table-card">
                <div class="residency-table-head">
                    <div class="table-head-title">
                        <span class="table-head-icon"><i class="fas fa-table-list"></i></span>
                        <div class="min-w-0">
                            <span class="table-kicker"><i class="fas fa-database"></i> Application records</span>
                            <h3>Residency application queue</h3>
                            <p>Review applicant contact, residency details, purpose, household information, status, and available actions.</p>
                        </div>
                    </div>
                    <span class="table-count-pill"><i class="fas fa-list"></i>{{ $applications->total() }} records</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="residencyTable">
                            <colgroup>
                                <col class="col-select">
                                <col class="col-applicant">
                                <col class="col-contact">
                                <col class="col-residency">
                                <col class="col-purpose">
                                <col class="col-status">
                                <col class="col-actions">
                            </colgroup>
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox"
                                               id="selectAll"
                                               class="form-check-input table-checkbox"
                                               data-click-call="toggleSelectAll"
                                               aria-label="Select all residency applications">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Applicant / Reference
                                            @if(request('sort') == 'created_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Contact</th>
                                    <th>Residency Details</th>
                                    <th>Purpose / Household</th>
                                    <th>Status / Submitted</th>
                                    <th class="text-end pe-3 actions-heading"><span class="visually-hidden">Application controls</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($applications as $app)
                                    @php
                                        $residencyTypeLabel = match($app->residency_type) {
                                            'owner' => 'Homeowner / Property Owner',
                                            'renter' => 'Renter / Tenant',
                                            'family' => 'Living with Family',
                                            'relative' => 'Living with Relative',
                                            'boarder' => 'Boarder / Lodger',
                                            default => $app->residency_type
                                                ? ucwords(str_replace('_', ' ', $app->residency_type))
                                                : 'N/A',
                                        };

                                        $yearsResidingLabel = match($app->years_residing) {
                                            'less1' => 'Less than 1 year',
                                            '1-3' => '1–3 years',
                                            '3-5' => '3–5 years',
                                            '5-10' => '5–10 years',
                                            '10-20' => '10–20 years',
                                            '20+' => 'More than 20 years',
                                            default => $app->years_residing ?: 'N/A',
                                        };

                                        $purposeLabel = match($app->purpose) {
                                            'government' => 'Government Transaction',
                                            'school' => 'School Enrollment',
                                            'employment' => 'Employment',
                                            'legal' => 'Legal / Court Requirement',
                                            'bank' => 'Bank Transaction',
                                            'scholarship' => 'Scholarship',
                                            'pwd' => 'PWD Application',
                                            'senior' => 'Senior Citizen ID Application',
                                            'other' => $app->purpose_other ?: 'Other',
                                            default => $app->purpose
                                                ? ucwords(str_replace('_', ' ', $app->purpose))
                                                : 'N/A',
                                        };
                                    @endphp

                                    <tr class="application-row {{ $app->deleted_at ? 'is-archived' : '' }}">
                                        <td class="ps-4">
                                            <input type="checkbox"
                                                   value="{{ $app->id }}"
                                                   class="form-check-input table-checkbox application-checkbox"
                                                   aria-label="Select application {{ $app->reference_number }}">
                                        </td>

                                        <td class="ps-0 applicant-cell">
                                            <div class="application-primary">
                                                <div class="application-avatar">
                                                    {{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="application-name">
                                                        {{ trim($app->first_name . ' ' . $app->last_name) }}
                                                    </div>
                                                    <div class="application-ref">
                                                        <span class="ref-pill">
                                                            <i class="fas fa-hashtag"></i>{{ $app->reference_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="contact-cell">
                                            <div class="info-stack">
                                                <span class="info-main contact-email"
                                                      title="{{ $app->email ?: 'No email' }}">
                                                    {{ $app->email ?: 'No email' }}
                                                </span>
                                                <span class="info-sub">
                                                    <i class="fas fa-phone me-1"></i>{{ $app->contact_number ?: 'No contact number' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="residency-details-cell">
                                            <div class="info-stack">
                                                <span class="info-main">{{ $residencyTypeLabel }}</span>
                                                <span class="info-sub">
                                                    <i class="fas fa-house-user me-1"></i>{{ $yearsResidingLabel }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="purpose-household-cell">
                                            <div class="info-stack">
                                                <span class="info-main purpose-text" title="{{ $purposeLabel }}">
                                                    {{ $purposeLabel }}
                                                </span>
                                                <span class="info-sub">
                                                    <i class="fas fa-users me-1"></i>
                                                    @if(is_null($app->household_members))
                                                        Household not provided
                                                    @else
                                                        {{ number_format((int) $app->household_members) }}
                                                        household member{{ (int) $app->household_members === 1 ? '' : 's' }}
                                                    @endif
                                                </span>
                                            </div>
                                        </td>

                                        <td class="status-submitted-cell">
                                            <div class="status-cell">
                                                @if($app->deleted_at)
                                                    <span class="badge bg-danger-subtle text-danger">Archived</span>
                                                @elseif($app->status == 'approved')
                                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                                @elseif($app->status == 'ready_pickup')
                                                    <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                                @elseif($app->status == 'claimed')
                                                    <span class="badge bg-success-subtle text-success">Claimed</span>
                                                @elseif($app->status == 'rejected')
                                                    <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                                @elseif($app->status == 'pending')
                                                    <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                                                @elseif($app->status == 'under_review')
                                                    <span class="badge bg-primary-subtle text-primary">Under Review</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">Processing</span>
                                                @endif

                                                <span class="status-date">
                                                    <i class="far fa-calendar me-1"></i>
                                                    {{ $app->created_at ? $app->created_at->format('M d, Y h:i A') : 'N/A' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-end pe-3 actions-cell">
                                            <div class="action-buttons" aria-label="Controls for {{ $app->reference_number }}">
                                                <button type="button"
                                                        class="btn btn-sm action-btn action-primary action-view"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $app->id }}"
                                                        title="View full details"
                                                        aria-label="View application {{ $app->reference_number }}">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="action-label">View</span>
                                                </button>

                                                @if(!$app->deleted_at && (auth('admin')->user()->hasPermission('update_residency') || auth('admin')->user()->hasPermission('approve_residency') || auth('admin')->user()->hasPermission('reject_residency')))
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-primary action-update"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateStatusModal{{ $app->id }}"
                                                            title="Edit status"
                                                            aria-label="Edit application status for {{ $app->reference_number }}">
                                                        <i class="fas fa-pen"></i>
                                                        <span class="action-label">Edit</span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-primary action-update"
                                                            disabled
                                                            title="Edit unavailable"
                                                            aria-label="Edit unavailable">
                                                        <i class="fas fa-pen"></i>
                                                        <span class="action-label">Edit</span>
                                                    </button>
                                                @endif

                                                <div class="dropdown action-more-dropdown">
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-more-toggle icon-only"
                                                            data-bs-toggle="dropdown"
                                                            data-bs-boundary="viewport"
                                                            data-bs-auto-close="outside"
                                                            aria-expanded="false"
                                                            title="More options"
                                                            aria-label="More options for {{ $app->reference_number }}">
                                                        <i class="fas fa-ellipsis-vertical"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end action-menu">
                                                        @if(!$app->deleted_at && auth('admin')->user()->hasPermission('generate_residency_document') && in_array($app->status, ['approved', 'ready_pickup', 'claimed']))
                                                            <button type="button"
                                                                    class="dropdown-item action-menu-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#docsModal{{ $app->id }}">
                                                                <span class="action-menu-icon action-menu-icon-docs">
                                                                    <i class="fas fa-file-lines"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Generate documents</strong>
                                                                    <small>Create or download certificate files</small>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span class="dropdown-item action-menu-item disabled"
                                                                  aria-disabled="true"
                                                                  title="Documents are available after approval">
                                                                <span class="action-menu-icon action-menu-icon-docs">
                                                                    <i class="fas fa-file-lines"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Documents unavailable</strong>
                                                                    <small>Approve the application first</small>
                                                                </span>
                                                            </span>
                                                        @endif

                                                        <div class="dropdown-divider"></div>

                                                        @if($app->deleted_at && auth('admin')->user()->hasPermission('delete_residency'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.residency.restore', $app->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this application?']) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item action-menu-item">
                                                                    <span class="action-menu-icon action-menu-icon-restore">
                                                                        <i class="fas fa-rotate-left"></i>
                                                                    </span>
                                                                    <span class="action-menu-copy">
                                                                        <strong>Restore application</strong>
                                                                        <small>Return this record to the active list</small>
                                                                    </span>
                                                                </button>
                                                            </form>
                                                        @elseif(auth('admin')->user()->hasPermission('delete_residency'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.residency.destroy', $app->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this application?']) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item action-menu-item action-menu-danger">
                                                                    <span class="action-menu-icon action-menu-icon-archive">
                                                                        <i class="fas fa-box-archive"></i>
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
                                                                    <i class="fas fa-box-archive"></i>
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
                                                <i class="fas fa-file-alt fa-4x text-muted mb-3 opacity-50"></i>
                                                <h5 class="text-muted">No applications found</h5>
                                                <p class="text-muted mb-3 small">Try adjusting your search or filter.</p>
                                                @admin_can('create_residency')
                                                    <button class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addApplicationModal">
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
                            {{ $applications->firstItem() ?? 0 }}-{{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $applications->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>

            @admin_can('create_residency')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">
                                    <i class="fas fa-file-alt me-2"></i>
                                    New Residency Application
                                </h5>
                                <p class="mb-0 mt-1 text-muted small">Complete all 5 steps below. Fields marked <span class="text-danger">*</span> are required.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.residency.store') }}" enctype="multipart/form-data" id="addApplicationForm">
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
                                            <label class="form-label"><i class="fas fa-map-pin"></i>Birth Place</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('birth_place') is-invalid @enderror @endif"
                                                   name="birth_place" id="add_birth_place" value="{{ session('form_type') == 'add' ? old('birth_place') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('birth_place')
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
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Contact Information</h6>
                                            <p>How the applicant can be reached and their current address.</p>
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
                                            <h6>Residency Details</h6>
                                            <p>These values describe the basis of the residency certificate.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-hourglass-half"></i>Years Residing <span class="text-danger">*</span></label>
                                            <select class="form-control @if(session('form_type') == 'add') @error('years_residing') is-invalid @enderror @endif"
                                                   name="years_residing" id="add_years_residing" required>
                                                <option value="">Select length</option>
                                                <option value="less1" {{ (session('form_type') == 'add' && old('years_residing') == 'less1') ? 'selected' : '' }}>Less than 1 year</option>
                                                <option value="1-3" {{ (session('form_type') == 'add' && old('years_residing') == '1-3') ? 'selected' : '' }}>1 - 3 years</option>
                                                <option value="3-5" {{ (session('form_type') == 'add' && old('years_residing') == '3-5') ? 'selected' : '' }}>3 - 5 years</option>
                                                <option value="5-10" {{ (session('form_type') == 'add' && old('years_residing') == '5-10') ? 'selected' : '' }}>5 - 10 years</option>
                                                <option value="10-20" {{ (session('form_type') == 'add' && old('years_residing') == '10-20') ? 'selected' : '' }}>10 - 20 years</option>
                                                <option value="20+" {{ (session('form_type') == 'add' && old('years_residing') == '20+') ? 'selected' : '' }}>More than 20 years</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('years_residing')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-house-user"></i>Residency Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('residency_type') is-invalid @enderror @endif"
                                                    name="residency_type" id="add_residency_type" required>
                                                <option value="">Select status</option>
                                                <option value="owner" {{ (session('form_type') == 'add' && old('residency_type') == 'owner') ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                                <option value="renter" {{ (session('form_type') == 'add' && old('residency_type') == 'renter') ? 'selected' : '' }}>Renter / Tenant</option>
                                                <option value="family" {{ (session('form_type') == 'add' && old('residency_type') == 'family') ? 'selected' : '' }}>Living with Family</option>
                                                <option value="relative" {{ (session('form_type') == 'add' && old('residency_type') == 'relative') ? 'selected' : '' }}>Living with Relative</option>
                                                <option value="boarder" {{ (session('form_type') == 'add' && old('residency_type') == 'boarder') ? 'selected' : '' }}>Boarder / Lodger</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('residency_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><i class="fas fa-users"></i>Household Members</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'add') @error('household_members') is-invalid @enderror @endif"
                                                   name="household_members" id="add_household_members" value="{{ session('form_type') == 'add' ? old('household_members') : '' }}" min="1" max="20" placeholder="e.g., 4" required>
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
                                                <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government Transaction (PSA, DFA, Passport)</option>
                                                <option value="school" {{ (session('form_type') == 'add' && old('purpose') == 'school') ? 'selected' : '' }}>School Enrollment</option>
                                                <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment</option>
                                                <option value="legal" {{ (session('form_type') == 'add' && old('purpose') == 'legal') ? 'selected' : '' }}>Legal/Court Requirement</option>
                                                <option value="bank" {{ (session('form_type') == 'add' && old('purpose') == 'bank') ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship</option>
                                                <option value="pwd" {{ (session('form_type') == 'add' && old('purpose') == 'pwd') ? 'selected' : '' }}>PWD Application</option>
                                                <option value="senior" {{ (session('form_type') == 'add' && old('purpose') == 'senior') ? 'selected' : '' }}>Senior Citizen ID Application</option>
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
                                                    <label class="form-label mb-1">Primary Proof of Residency <span class="text-danger">*</span></label>
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
                                                    <input type="file" class="form-control @if(session('form_type') == 'add') @error('government_id') is-invalid @enderror @endif"
                                                           name="government_id" id="add_government_id" accept="image/*,.pdf">
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB. A selected resident's saved ID is reused automatically.</small>
                                                    @if(session('form_type') == 'add')
                                                        @error('government_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="document-upload-card h-100">
                                                <div class="document-upload-icon" aria-hidden="true"><i class="fas fa-id-card"></i></div>
                                                <div class="flex-grow-1">
                                                    <label class="form-label mb-1">Valid ID - Back</label>
                                                    <input type="file" class="form-control @if(session('form_type') == 'add') @error('government_id_back') is-invalid @enderror @endif"
                                                           name="government_id_back" id="add_government_id_back" accept="image/*,.pdf">
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB. Required for manual applications.</small>
                                                    @if(session('form_type') == 'add')
                                                        @error('government_id_back')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <button type="submit" form="addApplicationForm" class="btn btn-success" id="submitAddForm">
                                <i class="fas fa-save me-2"></i>Save Application
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_residency'))
                @foreach($applications as $app)
                <div class="modal fade" id="editApplicationModal{{ $app->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Residency Application - {{ $app->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.residency.update', $app->id) }}" enctype="multipart/form-data" id="editApplicationForm{{ $app->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $app->id }}">

                                    <div class="row g-3">

                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Personal Information</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('first_name') is-invalid @enderror @endif"
                                                name="first_name" id="edit_first_name_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('first_name', $app->first_name) : $app->first_name }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('middle_name') is-invalid @enderror @endif"
                                                name="middle_name" id="edit_middle_name_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('middle_name', $app->middle_name) : $app->middle_name }}">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('last_name') is-invalid @enderror @endif"
                                                name="last_name" id="edit_last_name_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('last_name', $app->last_name) : $app->last_name }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('suffix') is-invalid @enderror @endif"
                                                    name="suffix" id="edit_suffix_{{ $app->id }}">
                                                <option value="">None</option>
                                                <option value="Jr." {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                <option value="Sr." {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                <option value="II" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                                <option value="III" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                                <option value="IV" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                                <option value="V" {{ (session('form_type') == 'edit_' . $app->id ? old('suffix', $app->suffix) : $app->suffix) == 'V' ? 'selected' : '' }}>V</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('suffix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('birthdate') is-invalid @enderror @endif"
                                                name="birthdate" id="edit_birthdate_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('birthdate', $app->birthdate) : $app->birthdate }}"
                                                required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birthdate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('birth_place') is-invalid @enderror @endif"
                                                name="birth_place" id="edit_birth_place_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('birth_place', $app->birth_place) : $app->birth_place }}"
                                                required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birth_place')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('gender') is-invalid @enderror @endif"
                                                    name="gender" id="edit_gender_{{ $app->id }}" required>
                                                <option value="">Select gender</option>
                                                <option value="male" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                <option value="prefer not to say" {{ (session('form_type') == 'edit_' . $app->id ? old('gender', $app->gender) : $app->gender) == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('civil_status') is-invalid @enderror @endif"
                                                    name="civil_status" id="edit_civil_status_{{ $app->id }}" required>
                                                <option value="">Select status</option>
                                                <option value="single" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="widowed" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                                                <option value="divorced" {{ (session('form_type') == 'edit_' . $app->id ? old('civil_status', $app->civil_status) : $app->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('civil_status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Contact Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('email') is-invalid @enderror @endif"
                                                name="email" id="edit_email_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('email', $app->email) : $app->email }}"
                                                placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('contact_number') is-invalid @enderror @endif"
                                                name="contact_number" id="edit_contact_number_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('contact_number', $app->contact_number) : $app->contact_number }}"
                                                placeholder="09XXXXXXXXX" maxlength="11" required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('contact_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('address') is-invalid @enderror @endif"
                                                    name="address" id="edit_address_{{ $app->id }}"
                                                    rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $app->id ? old('address', $app->address) : $app->address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Residency Details</h6>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Years Residing <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('years_residing') is-invalid @enderror @endif"
                                                name="years_residing" id="edit_years_residing_{{ $app->id }}" required>
                                                <option value="">Select length</option>
                                                <option value="less1" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == 'less1' ? 'selected' : '' }}>Less than 1 year</option>
                                                <option value="1-3" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '1-3' ? 'selected' : '' }}>1 - 3 years</option>
                                                <option value="3-5" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '3-5' ? 'selected' : '' }}>3 - 5 years</option>
                                                <option value="5-10" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '5-10' ? 'selected' : '' }}>5 - 10 years</option>
                                                <option value="10-20" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '10-20' ? 'selected' : '' }}>10 - 20 years</option>
                                                <option value="20+" {{ (session('form_type') == 'edit_' . $app->id ? old('years_residing', $app->years_residing) : $app->years_residing) == '20+' ? 'selected' : '' }}>More than 20 years</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('years_residing')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Residency Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('residency_type') is-invalid @enderror @endif"
                                                    name="residency_type" id="edit_residency_type_{{ $app->id }}" required>
                                                <option value="">Select type</option>
                                                <option value="owner" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'owner' ? 'selected' : '' }}>Homeowner / Property Owner</option>
                                                <option value="renter" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'renter' ? 'selected' : '' }}>Renter / Tenant</option>
                                                <option value="family" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'family' ? 'selected' : '' }}>Living with Family</option>
                                                <option value="relative" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'relative' ? 'selected' : '' }}>Living with Relative</option>
                                                <option value="boarder" {{ (session('form_type') == 'edit_' . $app->id ? old('residency_type', $app->residency_type) : $app->residency_type) == 'boarder' ? 'selected' : '' }}>Boarder / Lodger</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('residency_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Household Members <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('household_members') is-invalid @enderror @endif"
                                                name="household_members" id="edit_household_members_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('household_members', $app->household_members) : $app->household_members }}"
                                                min="1" max="20" placeholder="e.g., 4" required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('household_members')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('purpose') is-invalid @enderror @endif"
                                                    name="purpose" id="edit_purpose_{{ $app->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="government" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'government' ? 'selected' : '' }}>Government Transaction (PSA, DFA, Passport)</option>
                                                <option value="school" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'school' ? 'selected' : '' }}>School Enrollment</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'employment' ? 'selected' : '' }}>Employment</option>
                                                <option value="legal" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'legal' ? 'selected' : '' }}>Legal/Court Requirement</option>
                                                <option value="bank" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                <option value="pwd" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'pwd' ? 'selected' : '' }}>PWD Application</option>
                                                <option value="senior" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'senior' ? 'selected' : '' }}>Senior Citizen ID Application</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('purpose')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12" id="edit_otherPurposeField_{{ $app->id }}" style="{{ (session('form_type') == 'edit_' . $app->id && old('purpose', $app->purpose) == 'other') ? 'display:block;' : 'display:none;' }}">
                                            <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('purpose_other') is-invalid @enderror @endif"
                                                name="purpose_other" id="edit_purpose_other_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('purpose_other', $app->purpose_other) : $app->purpose_other }}"
                                                placeholder="Please specify">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('purpose_other')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current files)</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Primary Proof of Residency</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('primary_proof') is-invalid @enderror @endif"
                                                name="primary_proof" id="edit_primary_proof_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('primary_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Valid ID - Front</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('government_id') is-invalid @enderror @endif"
                                                name="government_id" id="edit_government_id_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('government_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Valid ID - Back</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('government_id_back') is-invalid @enderror @endif"
                                                name="government_id_back" id="edit_government_id_back_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('government_id_back')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        @if($app->primary_proof)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current primary proof: {{ basename($app->primary_proof) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($app->government_id)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current government ID: {{ basename($app->government_id) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($app->government_id_back)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current Valid ID back: {{ basename($app->government_id_back) }}
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editApplicationForm{{ $app->id }}" class="btn btn-primary" id="submitEditForm{{ $app->id }}">
                                        <i class="fas fa-save me-2"></i>Update Application
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($applications as $app)
            <div class="modal fade" id="viewModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">
                                    {{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}
                                </div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $app->first_name }} {{ $app->last_name }}</div>
                                    <div class="view-modal-sub">{{ $app->reference_number }} - Residency Certificate</div>
                                    <div class="mt-1">
                                        @if($app->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($app->status == 'ready_pickup')
                                            <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                        @elseif($app->status == 'claimed')
                                            <span class="badge bg-success-subtle text-success">Claimed</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @elseif($app->status == 'pending')
                                            <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                                        @elseif($app->status == 'under_review')
                                            <span class="badge bg-primary-subtle text-primary">Under Review</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $app->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $app->id }}" type="button" role="tab" aria-controls="details-panel-{{ $app->id }}" aria-selected="true">Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $app->id }}" type="button" role="tab" aria-controls="edit-panel-{{ $app->id }}" aria-selected="false">Edit info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="history-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $app->id }}" type="button" role="tab" aria-controls="history-panel-{{ $app->id }}" aria-selected="false">History</button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details-panel-{{ $app->id }}" role="tabpanel" aria-labelledby="details-tab-{{ $app->id }}">
                                    <div class="application-modal-summary">
                                        <div class="modal-summary-card is-primary">
                                            <span class="modal-summary-label">Applicant</span>
                                            <span class="modal-summary-value">{{ $app->first_name }} {{ $app->middle_name }} {{ $app->last_name }} {{ $app->suffix }}</span>
                                            <span class="modal-summary-help"><i class="fas fa-hashtag me-1"></i>{{ $app->reference_number }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Current Status</span>
                                            <span class="modal-summary-value">
                                                @if($app->deleted_at) Archived
                                                @elseif($app->status == 'ready_pickup') Ready for Pickup
                                                @else {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                                @endif
                                            </span>
                                            <span class="modal-summary-help">Application progress</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Residency</span>
                                            <span class="modal-summary-value">{{ $app->years_residing ?: 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $app->residency_type ? ucwords(str_replace('_', ' ', $app->residency_type)) : 'N/A' }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Submitted</span>
                                            <span class="modal-summary-value">{{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $app->created_at ? $app->created_at->format('h:i A') : '' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-user-check"></i>Important applicant information</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Birthdate</span>
                                                <div class="detail-value">{{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('F d, Y') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Gender / Civil status</span>
                                                <div class="detail-value">{{ ucfirst($app->gender) }} / {{ ucfirst($app->civil_status) }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Email</span>
                                                <div class="detail-value">{{ $app->email ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Contact number</span>
                                                <div class="detail-value">{{ $app->contact_number ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Address</span>
                                                <div class="detail-value">{{ $app->address ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-house-user"></i>Residency request</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Residency type</span>
                                                <div class="detail-value">{{ $app->residency_type ? ucwords(str_replace('_', ' ', $app->residency_type)) : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Years residing</span>
                                                <div class="detail-value">{{ $app->years_residing ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Household members</span>
                                                <div class="detail-value">{{ $app->household_members ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Purpose</span>
                                                <div class="detail-value">{{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }} {{ $app->purpose_other ? '(' . $app->purpose_other . ')' : '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-folder-open"></i>Required documents</div>
                                        <div class="document-preview-grid">
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-house me-1"></i>Proof of Residency</span></div>
                                                <div class="doc-body">
                                                    @if($app->primary_proof)
                                                        @if(in_array(strtolower(pathinfo($app->primary_proof, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']))
                                                            <img src="{{ route('admin.secure.file', ['path' => $app->primary_proof]) }}" alt="Proof of residency" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->primary_proof])]) }}">
                                                        @else
                                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.secure.file', ['path' => $app->primary_proof]) }}" target="_blank" rel="noopener"><i class="fas fa-file-pdf me-1"></i>View document</a>
                                                        @endif
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No proof uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Front</span></div>
                                                <div class="doc-body">
                                                    @if($app->government_id)
                                                        @if(in_array(strtolower(pathinfo($app->government_id, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']))
                                                            <img src="{{ route('admin.secure.file', ['path' => $app->government_id]) }}" alt="Valid ID front" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->government_id])]) }}">
                                                        @else
                                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.secure.file', ['path' => $app->government_id]) }}" target="_blank" rel="noopener"><i class="fas fa-file-pdf me-1"></i>View document</a>
                                                        @endif
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No valid ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Back</span></div>
                                                <div class="doc-body">
                                                    @if($app->government_id_back)
                                                        @if(in_array(strtolower(pathinfo($app->government_id_back, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']))
                                                            <img src="{{ route('admin.secure.file', ['path' => $app->government_id_back]) }}" alt="Valid ID back" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->government_id_back])]) }}">
                                                        @else
                                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.secure.file', ['path' => $app->government_id_back]) }}" target="_blank" rel="noopener"><i class="fas fa-file-pdf me-1"></i>View document</a>
                                                        @endif
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No back ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade edit-info-pane" id="edit-panel-{{ $app->id }}" role="tabpanel" aria-labelledby="edit-tab-{{ $app->id }}">
                                    <div class="edit-info-banner">
                                        <div class="application-avatar">
                                            {{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="edit-info-title">Edit information for {{ $app->first_name }} {{ $app->last_name }}</div>
                                            <div class="edit-info-subtitle">
                                                Reference {{ $app->reference_number }} · Update applicant details, residency profile, purpose, and replacement documents.
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.residency.update', $app->id) }}" id="quickEditForm{{ $app->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
                                        @csrf
                                        @method('PUT')

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Personal Information</div>
                                                    <p class="edit-section-help">Keep the applicant identity accurate for document generation.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-user"></i>First Name</label>
                                                        <input type="text" name="first_name" class="form-control" value="{{ $app->first_name }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-user"></i>Middle Name</label>
                                                        <input type="text" name="middle_name" class="form-control" value="{{ $app->middle_name }}">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-user"></i>Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" value="{{ $app->last_name }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-3">
                                                        <label class="form-label"><i class="fas fa-signature"></i>Suffix</label>
                                                        <select class="form-select" name="suffix">
                                                            <option value="">None</option>
                                                            <option value="Jr." {{ $app->suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                            <option value="Sr." {{ $app->suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                            <option value="II" {{ $app->suffix == 'II' ? 'selected' : '' }}>II</option>
                                                            <option value="III" {{ $app->suffix == 'III' ? 'selected' : '' }}>III</option>
                                                            <option value="IV" {{ $app->suffix == 'IV' ? 'selected' : '' }}>IV</option>
                                                            <option value="V" {{ $app->suffix == 'V' ? 'selected' : '' }}>V</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-3">
                                                        <label class="form-label"><i class="fas fa-calendar"></i>Birthdate</label>
                                                        <input type="date" name="birthdate" class="form-control" value="{{ $app->birthdate }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-3">
                                                        <label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label>
                                                        <select class="form-select" name="gender" required>
                                                            <option value="male" {{ $app->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $app->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ $app->gender == 'other' ? 'selected' : '' }}>Other</option><option value="prefer not to say" {{ $app->gender == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-3">
                                                        <label class="form-label"><i class="fas fa-ring"></i>Civil Status</label>
                                                        <select class="form-select" name="civil_status" required>
                                                            <option value="single" {{ $app->civil_status == 'single' ? 'selected' : '' }}>Single</option>
                                                            <option value="married" {{ $app->civil_status == 'married' ? 'selected' : '' }}>Married</option>
                                                            <option value="widowed" {{ $app->civil_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                            <option value="separated" {{ $app->civil_status == 'separated' ? 'selected' : '' }}>Separated</option>
                                                            <option value="divorced" {{ $app->civil_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Birth Place</label>
                                                        <input type="text" name="birth_place" class="form-control" value="{{ $app->birth_place }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Contact Information</div>
                                                    <p class="edit-section-help">Used for notification and application follow-up.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $app->email }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Contact Number</label>
                                                        <input type="text" name="contact_number" class="form-control" value="{{ $app->contact_number }}" maxlength="11" required>
                                                        <small>Use Philippine mobile format, for example 09XXXXXXXXX.</small>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Complete Address</label>
                                                        <textarea name="address" class="form-control" rows="2" required>{{ $app->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-house-user"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Residency Details</div>
                                                    <p class="edit-section-help">These values describe the basis of the residency certificate.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-hourglass-half"></i>Years Residing</label>
                                                        <select class="form-select" name="years_residing" required>
                                                            <option value="less1" {{ $app->years_residing == 'less1' ? 'selected' : '' }}>Less than 1 year</option>
                                                            <option value="1-3" {{ $app->years_residing == '1-3' ? 'selected' : '' }}>1 - 3 years</option>
                                                            <option value="3-5" {{ $app->years_residing == '3-5' ? 'selected' : '' }}>3 - 5 years</option>
                                                            <option value="5-10" {{ $app->years_residing == '5-10' ? 'selected' : '' }}>5 - 10 years</option>
                                                            <option value="10-20" {{ $app->years_residing == '10-20' ? 'selected' : '' }}>10 - 20 years</option>
                                                            <option value="20+" {{ $app->years_residing == '20+' ? 'selected' : '' }}>More than 20 years</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-home"></i>Residency Type</label>
                                                        <select class="form-select" name="residency_type" required>
                                                            <option value="owner" {{ $app->residency_type == 'owner' ? 'selected' : '' }}>Owner</option>
                                                            <option value="renter" {{ $app->residency_type == 'renter' ? 'selected' : '' }}>Renter</option>
                                                            <option value="family" {{ $app->residency_type == 'family' ? 'selected' : '' }}>Family</option>
                                                            <option value="relative" {{ $app->residency_type == 'relative' ? 'selected' : '' }}>Relative</option>
                                                            <option value="boarder" {{ $app->residency_type == 'boarder' ? 'selected' : '' }}>Boarder</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-users"></i>Household Members</label>
                                                        <input type="number" name="household_members" class="form-control" min="1" max="20" value="{{ $app->household_members }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-bullseye"></i>Purpose</label>
                                                        <select class="form-select" name="purpose" id="edit_purpose_{{ $app->id }}" required>
                                                            <option value="government" {{ $app->purpose == 'government' ? 'selected' : '' }}>Government</option>
                                                            <option value="school" {{ $app->purpose == 'school' ? 'selected' : '' }}>School</option>
                                                            <option value="employment" {{ $app->purpose == 'employment' ? 'selected' : '' }}>Employment</option>
                                                            <option value="legal" {{ $app->purpose == 'legal' ? 'selected' : '' }}>Legal</option>
                                                            <option value="bank" {{ $app->purpose == 'bank' ? 'selected' : '' }}>Bank</option>
                                                            <option value="scholarship" {{ $app->purpose == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                            <option value="pwd" {{ $app->purpose == 'pwd' ? 'selected' : '' }}>PWD</option>
                                                            <option value="senior" {{ $app->purpose == 'senior' ? 'selected' : '' }}>Senior</option>
                                                            <option value="other" {{ $app->purpose == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-6" id="edit_otherPurposeField_{{ $app->id }}" style="{{ $app->purpose === 'other' ? 'display:block;' : 'display:none;' }}">
                                                        <label class="form-label"><i class="fas fa-pen-line"></i>Purpose Other</label>
                                                        <input type="text" name="purpose_other" id="edit_purpose_other_{{ $app->id }}" class="form-control" value="{{ $app->purpose_other }}" placeholder="Please specify">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-folder-open"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Documents</div>
                                                    <p class="edit-section-help">Leave uploads empty to keep the current submitted files.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-file-image"></i>Proof of Residency</label>
                                                            <input type="file" name="primary_proof" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF. Leave empty to keep current file.</small>
                                                            @if($app->primary_proof)
                                                                <span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($app->primary_proof) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Front</label>
                                                            <input type="file" name="government_id" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF. Leave empty to keep current file.</small>
                                                            @if($app->government_id)
                                                                <span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($app->government_id) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Back</label>
                                                            <input type="file" name="government_id_back" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF. Leave empty to keep current file.</small>
                                                            @if($app->government_id_back)
                                                                <span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($app->government_id_back) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <span class="edit-required-note"><i class="fas fa-shield-halved"></i>Review details before saving. The update may affect generated certificates.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history-panel-{{ $app->id }}" role="tabpanel" aria-labelledby="history-tab-{{ $app->id }}">
                                    <div class="mb-2">Recent status: @if($app->status == 'approved')<span class="badge bg-success-subtle text-success">Approved</span>@elseif($app->status == 'rejected')<span class="badge bg-danger-subtle text-danger">Rejected</span>@elseif($app->status == 'ready_pickup')<span class="badge bg-info-subtle text-info">Ready for Pickup</span>@elseif($app->status == 'claimed')<span class="badge bg-primary-subtle text-primary">Claimed</span>@elseif($app->status == 'under_review')<span class="badge bg-secondary-subtle text-secondary">Under Review</span>@else<span class="badge bg-warning-subtle text-warning">Processing</span>@endif</div>
                                    <div id="historyList{{ $app->id }}" class="small text-muted">Loading history...</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="viewFooterDetails{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-switch-tab="edit" data-app-id="{{ $app->id }}">Edit info</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterEdit{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-switch-tab="details" data-app-id="{{ $app->id }}">Cancel</button>
                            <button type="submit" form="quickEditForm{{ $app->id }}" class="btn btn-primary">Save changes</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterHistory{{ $app->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="docsModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-file-lines me-2"></i>Document Actions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if(in_array($app->status, ['approved', 'ready_pickup', 'claimed']))
                            <div class="d-grid gap-2">
                                <form method="GET" action="{{ route('admin.residency.document.residency_only') }}" target="_blank">
                                    <input type="hidden" name="id" value="{{ $app->id }}">
                                    <button type="submit" name="action" value="download" class="btn btn-outline-primary w-100"><i class="fas fa-download me-2"></i>Download Word</button>
                                </form>
                                <form method="GET" action="{{ route('admin.residency.document.residency_only') }}" target="_blank">
                                    <input type="hidden" name="id" value="{{ $app->id }}">
                                    <button type="submit" name="action" value="print" class="btn btn-outline-success w-100"><i class="fas fa-print me-2"></i>Print PDF</button>
                                </form>
                            </div>
                            @else
                            <p class="mb-0 text-muted">Documents are available only when status is Approved, Ready for Pickup, or Claimed.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-arrows-rotate"></i>Update application status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.residency.status', $app->id) }}" data-submit-call="handleUpdateStatusSubmit" data-submit-pass="event,self">
                            @csrf
                            <div class="modal-body">
                                <div class="status-flow-card">
                                    <div class="status-flow-person">
                                        <div class="application-avatar">{{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="application-name">{{ $app->first_name }} {{ $app->last_name }}</div>
                                            <div class="application-ref"><span class="ref-pill"><i class="fas fa-hashtag"></i>{{ $app->reference_number }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">New status</label>
                                    <input type="hidden" name="status" value="{{ $app->status }}" class="selected-status-input">
                                    <div class="status-grid" data-current-status="{{ $app->status }}">
                                        <button type="button" class="status-option" data-status="pending"><span class="dot" style="background:#ed6c02;"></span>Pending</button>
                                        <button type="button" class="status-option" data-status="under_review"><span class="dot" style="background:#0288d1;"></span>Under review</button>
                                        <button type="button" class="status-option" data-status="processing"><span class="dot" style="background:#5e35b1;"></span>Processing</button>
                                        <button type="button" class="status-option" data-status="approved"><span class="dot" style="background:#2e7d32;"></span>Approved</button>
                                        <button type="button" class="status-option" data-status="ready_pickup"><span class="dot" style="background:#00acc1;"></span>Ready for pickup</button>
                                        <button type="button" class="status-option" data-status="claimed"><span class="dot" style="background:#43a047;"></span>Claimed</button>
                                        <button type="button" class="status-option" data-status="rejected" style="grid-column: span 3;"><span class="dot" style="background:#d32f2f;"></span>Rejected</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Remarks <span class="text-muted fw-normal">(optional - required if rejected, max 40 chars)</span></label>
                                    <textarea class="form-control" name="remarks" rows="3" maxlength="40" placeholder="Add a note about this status change..."></textarea>
                                </div>
                                <div class="{{ in_array($app->status, ['approved', 'ready_pickup', 'rejected']) ? '' : 'd-none' }}" data-notify-wrap data-has-email="{{ $app->email ? '1' : '0' }}" data-has-sms="{{ $app->contact_number ? '1' : '0' }}">
                                    <label class="form-label text-uppercase fw-semibold small">Notify applicant</label>
                                    <div class="notify-row">
                                        <div class="notify-label">
                                            <strong>Send email</strong>
                                            <small>{{ $app->email ?: 'No email available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" checked {{ !$app->email ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="notify-row mb-0">
                                        <div class="notify-label">
                                            <strong>Send SMS</strong>
                                            <small>{{ $app->contact_number ?: 'No mobile number available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_sms" value="1" checked {{ !$app->contact_number ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Notifications are available for Approved, Ready for Pickup, and Rejected status only.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm &amp; save</button>
                            </div>
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
            <script src="{{ asset('js/pages/admin/admin_residency-part1.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-residency-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_residency-dynamic1.js') }}"></script>
            @endif
    </div>
@endsection

@push('scripts')
@php
                $residentsForJs = ($residents ?? collect())->map(function ($resident) {
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
    'id' => 'js-config-admin-residency-main',
    'data' => [
        'residents' => $residentsForJs,
        'showAddModal' => session('form_type') === 'add' && ($errors->any() || session('error')),
        'canUpdate' => auth('admin')->user()->hasPermission('update_residency'),
        'applicationIds' => $applications->pluck('id')->values(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_residency-main.js') }}"></script>
<script src="{{ asset('js/pages/admin/admin_residency-part2.js') }}"></script>
@include('shared.js-config', [
    'id' => 'js-config-admin-admin-residency-2',
    'data' => [
        'v1' => e(route('admin.notifications.remarksHistory')),
    ],
])
<script src="{{ asset('js/pages/admin/admin_residency-dynamic2.js') }}"></script>
@endpush
