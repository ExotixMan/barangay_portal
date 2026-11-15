@extends('admin.layouts.admin')

@section('title', 'Clearance Applications')
@section('page_title', 'Clearance Applications')
@section('page_icon', 'fas fa-file-contract')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/clearance-applications.css') }}">

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
                        <div class="residency-hero-icon"><i class="fas fa-file-contract"></i></div>
                        <div class="residency-kicker">Clearance application management</div>
                        <h2>Review clearance requests with a cleaner, focused workspace.</h2>
                        <p>Keep the table easy to scan while still giving access to full applicant details, uploaded documents, status updates, and certificate generation from the action modals.</p>
                    </div>

                    <div class="residency-hero-actions">
                        @admin_can('create_clearance')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                            <i class="fas fa-plus"></i>
                            New Application
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.clearance.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset
                        </a>
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Approved: <strong>{{ $approved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Rejected: <strong>{{ $rejected_count ?? 0 }}</strong></span>
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
                            <i class="fas fa-file-contract"></i>
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

            <div class="card border-0 mb-4 residency-filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.clearance.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Applications</h6>
                                    <p>Search by applicant, reference number, status, or purpose.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_clearance')
                                    <a href="#" class="btn btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">New Application</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.clearance.index') }}" class="btn btn-light-soft flex-fill flex-md-grow-0">
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

                            <div class="col-6 col-md-3">
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

                            <div class="col-6 col-md-2">
                                <select name="purpose" class="form-select">
                                    <option value="">All Purposes</option>
                                    <option value="employment"  {{ request('purpose') == 'employment' ? 'selected' : '' }}>Employment</option>
                                    <option value="business" {{ request('purpose') == 'business' ? 'selected' : '' }}>Business Permit</option>
                                    <option value="scholarship" {{ request('purpose') == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                    <option value="travel" {{ request('purpose') == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                    <option value="bank" {{ request('purpose') == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                    <option value="government" {{ request('purpose') == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                    <option value="school" {{ request('purpose') == 'school' ? 'selected' : '' }}>School Requirement</option>
                                    <option value="other" {{ request('purpose') == 'other' ? 'selected' : '' }}>Other</option>
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
                    @if(auth('admin')->user()->hasPermission('delete_clearance') || auth('admin')->user()->hasPermission('export_clearance'))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @admin_can('delete_clearance')
                        <form
                            id="bulkForm"
                            method="POST"
                            action="{{ $isArchivedView ? route('admin.clearance.bulkRestore') : route('admin.clearance.bulkDelete') }}"
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

                        @admin_can('export_clearance')
                        <form id="exportForm" method="POST" action="{{ route('admin.clearance.export') }}" style="display: inline;">
                            @csrf
                            <button type="button" data-click-call="exportCSV" class="btn btn-outline-success d-flex align-items-center gap-2" title="Export Excel or CSV">
                                <i class="fas fa-file-export"></i>
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
                            <h3>Clearance application queue</h3>
                            <p>Use View and Edit for common tasks. Documents and archive controls are grouped under More.</p>
                        </div>
                    </div>
                    <span class="table-count-pill"><i class="fas fa-list"></i>{{ $clearances->total() ?? 0 }} records</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="clearanceTable">
                            <colgroup>
                                <col class="col-select">
                                <col class="col-applicant">
                                <col class="col-contact">
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
                                               aria-label="Select all clearance applications">
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
                                    <th>Purpose / Fee</th>
                                    <th>Status / Submitted</th>
                                    <th class="text-end pe-4 actions-heading"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($clearances as $app)
                                    @php
                                        $applicantName = collect([
                                            $app->first_name,
                                            $app->middle_name,
                                            $app->last_name,
                                            $app->suffix,
                                        ])->filter()->implode(' ');

                                        $purposeLabel = match ((string) $app->purpose) {
                                            'employment' => 'Employment',
                                            'business' => 'Business Permit',
                                            'scholarship' => 'Scholarship',
                                            'travel' => 'Travel / Abroad',
                                            'bank' => 'Bank Transaction',
                                            'government' => 'Government Transaction',
                                            'school' => 'School Requirement',
                                            'other' => $app->purpose_other ?: 'Other Purpose',
                                            default => 'N/A',
                                        };

                                        $feeLabel = is_null($app->fee)
                                            ? 'To be determined'
                                            : '₱' . number_format((float) $app->fee, 2);
                                    @endphp

                                    <tr class="clearance-row {{ $app->deleted_at ? 'is-archived' : '' }}">
                                        <td class="ps-4">
                                            <input type="checkbox"
                                                   value="{{ $app->id }}"
                                                   class="form-check-input table-checkbox application-checkbox"
                                                   aria-label="Select {{ $applicantName }}">
                                        </td>

                                        <td class="ps-0">
                                            <div class="application-primary">
                                                <div class="application-avatar" aria-hidden="true">
                                                    {{ strtoupper(substr($app->first_name ?? 'C', 0, 1) . substr($app->last_name ?? '', 0, 1)) }}
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="application-name" title="{{ $applicantName }}">
                                                        {{ $applicantName ?: 'Unnamed Applicant' }}
                                                    </div>

                                                    <div class="application-ref">
                                                        <span class="ref-pill" title="{{ $app->reference_number }}">
                                                            <i class="fas fa-hashtag" aria-hidden="true"></i>
                                                            <span>{{ $app->reference_number ?: 'No reference' }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="info-stack clearance-contact">
                                                <span class="info-main" title="{{ $app->email ?: 'No email' }}">
                                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                                    <span>{{ $app->email ?: 'No email' }}</span>
                                                </span>
                                                <span class="info-sub" title="{{ $app->contact_number ?: 'No contact number' }}">
                                                    <i class="fas fa-phone" aria-hidden="true"></i>
                                                    <span>{{ $app->contact_number ?: 'No contact number' }}</span>
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="info-stack clearance-purpose">
                                                <span class="info-main" title="{{ $purposeLabel }}">
                                                    {{ $purposeLabel }}
                                                </span>
                                                <span class="info-sub fee-display {{ is_null($app->fee) ? 'is-pending' : '' }}"
                                                      title="{{ $feeLabel }}">
                                                    <i class="fas fa-receipt" aria-hidden="true"></i>
                                                    <span>{{ $feeLabel }}</span>
                                                </span>
                                            </div>
                                        </td>

                                        <td>
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

                                                <span class="status-date"
                                                      title="{{ $app->created_at ? $app->created_at->format('F d, Y h:i A') : 'N/A' }}">
                                                    <i class="fas fa-calendar-day" aria-hidden="true"></i>
                                                    <span>{{ $app->created_at ? $app->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-end pe-4 actions-cell">
                                            <div class="action-buttons" aria-label="Controls for {{ $applicantName }}">
                                                <button type="button"
                                                        class="btn btn-sm action-btn action-primary action-view"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $app->id }}"
                                                        title="View full details"
                                                        aria-label="View full details for {{ $applicantName }}">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                    <span class="action-label">View</span>
                                                </button>

                                                @if(!$app->deleted_at && (auth('admin')->user()->hasPermission('update_clearance') || auth('admin')->user()->hasPermission('approve_clearance') || auth('admin')->user()->hasPermission('reject_clearance')))
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-primary action-update"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateStatusModal{{ $app->id }}"
                                                            title="Edit application status"
                                                            aria-label="Edit application status for {{ $applicantName }}">
                                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-primary action-update"
                                                            disabled
                                                            title="Edit unavailable"
                                                            aria-label="Edit unavailable for {{ $applicantName }}">
                                                        <i class="fas fa-pen" aria-hidden="true"></i>
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
                                                            aria-label="More options for {{ $applicantName }}">
                                                        <i class="fas fa-ellipsis-vertical" aria-hidden="true"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end action-menu">
                                                        @if(!$app->deleted_at && auth('admin')->user()->hasPermission('generate_clearance_document') && in_array($app->status, ['approved', 'ready_pickup', 'claimed']))
                                                            <button type="button"
                                                                    class="dropdown-item action-menu-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#docsModal{{ $app->id }}">
                                                                <span class="action-menu-icon action-menu-icon-docs">
                                                                    <i class="fas fa-file-lines" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Generate documents</strong>
                                                                    <small>Create or download clearance files</small>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span class="dropdown-item action-menu-item disabled"
                                                                  aria-disabled="true"
                                                                  title="Documents are available after approval">
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

                                                        @if($app->deleted_at && auth('admin')->user()->hasPermission('delete_clearance'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.clearance.restore', $app->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this application?']) }}">
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
                                                        @elseif(auth('admin')->user()->hasPermission('delete_clearance'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.clearance.destroy', $app->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this application?']) }}">
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
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state py-4">
                                                <span class="empty-state-icon mb-3"><i class="fas fa-file-contract"></i></span>
                                                <h5>No clearance applications found</h5>
                                                <p>Try adjusting your search/filter or create a new clearance application.</p>
                                                @admin_can('create_clearance')
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                                                        <i class="fas fa-plus me-2"></i>New Application
                                                    </button>
                                                @endadmin_can
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($clearances) && $clearances->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $clearances->firstItem() ?? 0 }}-{{ $clearances->lastItem() ?? 0 }} of {{ $clearances->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $clearances->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            @admin_can('create_clearance')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">
                                    <i class="fas fa-file-contract me-2"></i>
                                    New Barangay Clearance Application
                                </h5>
                                <p class="mb-0 mt-1 text-muted small">Complete all 4 steps below. Fields marked <span class="text-danger">*</span> are required.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.clearance.store') }}" enctype="multipart/form-data" id="addApplicationForm">
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
                                            <h6>Clearance Details</h6>
                                            <p>These values describe the basis of the clearance certificate.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-bullseye"></i>Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('purpose') is-invalid @enderror @endif"
                                                    name="purpose" id="purpose" required>
                                                <option value="">Select purpose</option>
                                                <option value="employment" {{ (session('form_type') == 'add' && old('purpose') == 'employment') ? 'selected' : '' }}>Employment</option>
                                                <option value="business" {{ (session('form_type') == 'add' && old('purpose') == 'business') ? 'selected' : '' }}>Business Permit</option>
                                                <option value="scholarship" {{ (session('form_type') == 'add' && old('purpose') == 'scholarship') ? 'selected' : '' }}>Scholarship</option>
                                                <option value="travel" {{ (session('form_type') == 'add' && old('purpose') == 'travel') ? 'selected' : '' }}>Travel/Abroad</option>
                                                <option value="bank" {{ (session('form_type') == 'add' && old('purpose') == 'bank') ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="government" {{ (session('form_type') == 'add' && old('purpose') == 'government') ? 'selected' : '' }}>Government Transaction</option>
                                                <option value="school" {{ (session('form_type') == 'add' && old('purpose') == 'school') ? 'selected' : '' }}>School Requirement</option>
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
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-coins"></i>Fee (PHP)</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'add') @error('fee') is-invalid @enderror @endif"
                                                   name="fee" id="add_fee" value="{{ session('form_type') == 'add' ? old('fee') : '' }}"
                                                   min="0" step="0.01" placeholder="Leave empty = Depending on purpose">
                                            <small class="text-muted">Leave blank if fee depends on purpose.</small>
                                            @if(session('form_type') == 'add')
                                                @error('fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-1">
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
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB. Saved resident ID is reused automatically.</small>
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
                                                    <small class="text-muted">JPG, JPEG, PNG or PDF • Maximum 5MB. Required for manual applications.</small>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_clearance'))
                @foreach($clearances as $app)
                <div class="modal fade" id="editClearanceModal{{ $app->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Clearance Application - {{ $app->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.clearance.update', $app->id) }}" enctype="multipart/form-data" id="editClearanceForm{{ $app->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $app->id }}">

                                    <div class="row g-3">

                                        <div class="col-12">
                                            <h6 class="modal-section-title"><i class="fas fa-user"></i> Personal Information</h6>
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
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('birthdate', $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('Y-m-d') : '') : ($app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('Y-m-d') : '') }}"
                                                required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birthdate')
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
                                                @error('civil_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('birth_place') is-invalid @enderror @endif"
                                                   name="birth_place" id="edit_birth_place_{{ $app->id }}"
                                                   value="{{ session('form_type') == 'edit_' . $app->id ? old('birth_place', $app->birth_place) : $app->birth_place }}" required>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('birth_place')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="modal-section-title"><i class="fas fa-address-book"></i> Contact Information</h6>
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
                                            <h6 class="modal-section-title"><i class="fas fa-file-contract"></i> Clearance Details</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $app->id) @error('purpose') is-invalid @enderror @endif"
                                                    name="purpose" id="edit_purpose_{{ $app->id }}" required>
                                                <option value="">Select purpose</option>
                                                <option value="employment" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'employment' ? 'selected' : '' }}>Employment</option>
                                                <option value="business" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'business' ? 'selected' : '' }}>Business Permit</option>
                                                <option value="scholarship" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                <option value="travel" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                                <option value="bank" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                <option value="government" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                                <option value="school" {{ (session('form_type') == 'edit_' . $app->id ? old('purpose', $app->purpose) : $app->purpose) == 'school' ? 'selected' : '' }}>School Requirement</option>
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
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Fee (PHP)</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('fee') is-invalid @enderror @endif"
                                                name="fee" id="edit_fee_{{ $app->id }}"
                                                value="{{ session('form_type') == 'edit_' . $app->id ? old('fee', $app->fee) : $app->fee }}"
                                                min="0" step="0.01" placeholder="Leave empty = Depending on purpose">
                                            <small class="text-muted">Leave blank if fee depends on purpose.</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Documents (Leave empty to keep current file)</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Proof of Residency</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('primary_proof') is-invalid @enderror @endif"
                                                name="primary_proof" id="edit_primary_proof_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('primary_proof')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID - Front</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('valid_id_path') is-invalid @enderror @endif"
                                                name="valid_id_path" id="edit_valid_id_path_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('valid_id_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Valid ID - Back</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $app->id) @error('valid_id_back_path') is-invalid @enderror @endif"
                                                name="valid_id_back_path" id="edit_valid_id_back_path_{{ $app->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $app->id)
                                                @error('valid_id_back_path')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        @if($app->primary_proof)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current proof of residency: {{ basename($app->primary_proof) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($app->valid_id_path)
                                        <div class="col-12">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>Current file: {{ basename($app->valid_id_path) }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($app->valid_id_back_path)
                                        <div class="col-12"><small class="text-info"><i class="fas fa-info-circle me-1"></i>Current Valid ID back: {{ basename($app->valid_id_back_path) }}</small></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitEditForm{{ $app->id }}">
                                        <i class="fas fa-save me-2"></i>Update Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($clearances as $app)
            <div class="modal fade" id="viewModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">{{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}</div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $app->first_name }} {{ $app->last_name }}</div>
                                    <div class="view-modal-sub">{{ $app->reference_number }} - Clearance Certificate</div>
                                    <div class="mt-1">
                                        @if($app->status == 'approved')
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                        @elseif($app->status == 'ready_pickup')
                                            <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                        @elseif($app->status == 'claimed')
                                            <span class="badge bg-primary-subtle text-primary">Claimed</span>
                                        @elseif($app->status == 'under_review')
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
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $app->id }}" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active" id="details-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $app->id }}" type="button">Details</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="edit-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $app->id }}" type="button">Edit info</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab-{{ $app->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $app->id }}" type="button">History</button></li>
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
                                            <span class="modal-summary-label">Purpose</span>
                                            <span class="modal-summary-value">{{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $app->purpose_other ?: 'Barangay clearance' }}</span>
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
                                                <span class="detail-label">Gender</span>
                                                <div class="detail-value">{{ ucfirst($app->gender) }}</div>
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
                                        <div class="detail-section-title"><i class="fas fa-file-contract"></i>Clearance request</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Purpose</span>
                                                <div class="detail-value">{{ $app->purpose ? ucwords(str_replace('_', ' ', $app->purpose)) : 'N/A' }} {{ $app->purpose_other ? '(' . $app->purpose_other . ')' : '' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Fee</span>
                                                <div class="detail-value">{{ is_null($app->fee) ? 'Depending on purpose' : 'PHP ' . number_format((float) $app->fee, 2) }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Reference number</span>
                                                <div class="detail-value">{{ $app->reference_number }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Date submitted</span>
                                                <div class="detail-value">{{ $app->created_at ? $app->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
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
                                                        <img src="{{ route('admin.secure.file', ['path' => $app->primary_proof]) }}" alt="Proof of residency" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->primary_proof])]) }}">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No proof uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Front</span></div>
                                                <div class="doc-body">
                                                    @if($app->valid_id_path)
                                                        <img src="{{ route('admin.secure.file', ['path' => $app->valid_id_path]) }}" alt="Valid ID front" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->valid_id_path])]) }}">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No valid ID uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID - Back</span></div>
                                                <div class="doc-body">
                                                    @if($app->valid_id_back_path)
                                                        <img src="{{ route('admin.secure.file', ['path' => $app->valid_id_back_path]) }}" alt="Valid ID back" data-click-call="openZoomModal" data-click-args="{{ json_encode([route('admin.secure.file', ['path' => $app->valid_id_back_path])]) }}">
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
                                                Reference {{ $app->reference_number }} · Update applicant details, clearance purpose, fee, and replacement documents.
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.clearance.update', $app->id) }}" id="quickEditForm{{ $app->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
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
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>First Name</label>
                                                        <input type="text" name="first_name" class="form-control" value="{{ $app->first_name }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>Middle Name</label>
                                                        <input type="text" name="middle_name" class="form-control" value="{{ $app->middle_name }}" maxlength="255">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-id-badge"></i>Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" value="{{ $app->last_name }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
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
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-cake-candles"></i>Birthdate</label>
                                                        <input type="date" name="birthdate" class="form-control" value="{{ $app->birthdate ? \Carbon\Carbon::parse($app->birthdate)->format('Y-m-d') : '' }}" required max="{{ date('Y-m-d') }}">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label>
                                                        <select class="form-select" name="gender" required>
                                                            <option value="male" {{ $app->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $app->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ $app->gender == 'other' ? 'selected' : '' }}>Other</option><option value="prefer not to say" {{ $app->gender == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-ring"></i>Civil Status</label>
                                                        <select class="form-select" name="civil_status" required>
                                                            <option value="single" {{ $app->civil_status == 'single' ? 'selected' : '' }}>Single</option>
                                                            <option value="married" {{ $app->civil_status == 'married' ? 'selected' : '' }}>Married</option>
                                                            <option value="widowed" {{ $app->civil_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                            <option value="separated" {{ $app->civil_status == 'separated' ? 'selected' : '' }}>Separated</option>
                                                            <option value="divorced" {{ $app->civil_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-location-dot"></i>Place of Birth</label><input type="text" name="birth_place" class="form-control" value="{{ $app->birth_place }}" required></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-address-book"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Contact Information</div>
                                                    <p class="edit-section-help">These details are used for follow-up and notifications.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $app->email }}" maxlength="255" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Contact Number</label>
                                                        <input type="text" name="contact_number" class="form-control" value="{{ $app->contact_number }}" maxlength="11" inputmode="numeric" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Address</label>
                                                        <textarea name="address" class="form-control" rows="2" maxlength="500" required>{{ $app->address }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-file-contract"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Clearance Details</div>
                                                    <p class="edit-section-help">Update the purpose and fee shown in the request queue and certificate workflow.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-clipboard-check"></i>Purpose</label>
                                                        <select class="form-select" name="purpose" required>
                                                            <option value="employment" {{ $app->purpose == 'employment' ? 'selected' : '' }}>Employment</option>
                                                            <option value="business" {{ $app->purpose == 'business' ? 'selected' : '' }}>Business Permit</option>
                                                            <option value="scholarship" {{ $app->purpose == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                                            <option value="travel" {{ $app->purpose == 'travel' ? 'selected' : '' }}>Travel/Abroad</option>
                                                            <option value="bank" {{ $app->purpose == 'bank' ? 'selected' : '' }}>Bank Transaction</option>
                                                            <option value="government" {{ $app->purpose == 'government' ? 'selected' : '' }}>Government Transaction</option>
                                                            <option value="school" {{ $app->purpose == 'school' ? 'selected' : '' }}>School Requirement</option>
                                                            <option value="other" {{ $app->purpose == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-4 quick-purpose-other-field {{ $app->purpose === 'other' ? '' : 'd-none' }}">
                                                        <label class="form-label"><i class="fas fa-pen-line"></i>Purpose (Other)</label>
                                                        <input type="text" name="purpose_other" class="form-control" value="{{ $app->purpose_other }}" maxlength="255">
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <label class="form-label"><i class="fas fa-peso-sign"></i>Fee (PHP)</label>
                                                        <input type="number" name="fee" class="form-control" min="0" step="0.01" inputmode="decimal" value="{{ $app->fee }}" placeholder="Leave empty if depends on purpose">
                                                        <small>Leave empty when the fee depends on purpose.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-folder-open"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Documents</div>
                                                    <p class="edit-section-help">Upload only when replacing the current file. Existing uploads remain if left empty.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-house"></i>Proof of Residency</label>
                                                        <div class="edit-file-card">
                                                            <input type="file" name="primary_proof" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF, maximum 5MB.</small>
                                                            @if($app->primary_proof)
                                                                <span class="edit-current-file"><i class="fas fa-paperclip"></i>{{ basename($app->primary_proof) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Front</label>
                                                        <div class="edit-file-card">
                                                            <input type="file" name="valid_id_path" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF, maximum 5MB.</small>
                                                            @if($app->valid_id_path)
                                                                <span class="edit-current-file"><i class="fas fa-paperclip"></i>{{ basename($app->valid_id_path) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-id-card"></i>Valid ID - Back</label>
                                                        <div class="edit-file-card">
                                                            <input type="file" name="valid_id_back_path" class="form-control" accept="image/*,.pdf">
                                                            <small>Upload image or PDF, maximum 5MB.</small>
                                                            @if($app->valid_id_back_path)
                                                                <span class="edit-current-file"><i class="fas fa-paperclip"></i>{{ basename($app->valid_id_back_path) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="edit-required-note"><i class="fas fa-circle-info"></i>Leave file fields empty to keep current documents.</div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history-panel-{{ $app->id }}" role="tabpanel">
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
                <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-file-lines me-2"></i>Document Actions</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">@if(in_array($app->status, ['approved', 'ready_pickup', 'claimed']))<div class="d-grid gap-2"><form method="GET" action="{{ route('admin.clearance.document.clearance_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $app->id }}"><button type="submit" name="action" value="download" class="btn btn-outline-primary w-100">Download Word</button></form><form method="GET" action="{{ route('admin.clearance.document.clearance_only') }}" target="_blank"><input type="hidden" name="id" value="{{ $app->id }}"><button type="submit" name="action" value="print" class="btn btn-outline-success w-100">Print PDF</button></form></div>@else<p class="mb-0 text-muted">Documents are available only when status is Approved, Ready for Pickup, or Claimed.</p>@endif</div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div></div></div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-arrows-rotate"></i>Update application status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.clearance.status', $app->id) }}" data-submit-call="handleUpdateStatusSubmit" data-submit-pass="event,self">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-2 small text-muted">{{ $app->first_name }} {{ $app->last_name }} - {{ $app->reference_number }}</div>
                                <div class="mb-2 small"><span class="text-muted">Purpose:</span> <span class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $app->purpose)) }}{{ $app->purpose_other ? ' (' . $app->purpose_other . ')' : '' }}</span></div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Fee (PHP)</label>
                                    <input type="number" class="form-control" name="fee" min="0" step="0.01" value="{{ $app->fee }}" placeholder="Leave empty if depends on purpose">
                                    <small class="text-muted">This fee will be saved when updating status.</small>
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
            <script src="{{ asset('js/pages/admin/admin_clearance.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-clearance-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_clearance-dynamic1.js') }}"></script>
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
    'id' => 'js-config-admin-clearance-main',
    'data' => [
        'residents' => $residentsForJs,
        'showAddModal' => $errors->any() && session('form_type') === 'add',
        'canUpdate' => auth('admin')->user()->hasPermission('update_clearance'),
        'applicationIds' => $clearances->pluck('id')->values(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_clearance-main.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-admin-admin-clearance-2',
    'data' => [
        'v1' => e(route('admin.notifications.remarksHistory')),
    ],
])
<script src="{{ asset('js/pages/admin/admin_clearance-dynamic2.js') }}"></script>

@endpush
