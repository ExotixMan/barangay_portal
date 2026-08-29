@extends('admin.layouts.admin')

@section('title', 'Incident Reports')
@section('page_title', 'Incident Reports')
@section('page_icon', 'fas fa-book')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/incident-reports.css') }}">

@endpush

@section('content')
            <div class="residency-page incident-page">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                        <div class="residency-hero-icon"><i class="fas fa-book-open"></i></div>
                        <div class="residency-kicker">Incident report management</div>
                        <h2>Review incident reports with a cleaner, focused workspace.</h2>
                        <p>Keep the table easy to scan while still giving access to full case details, parties involved, evidence, witnesses, status updates, and archive actions from the modals.</p>
                    </div>

                    <div class="residency-hero-actions">
                        @admin_can('create_blotter')
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                            <i class="fas fa-plus"></i>
                            New Incident
                        </a>
                        @endadmin_can
                        <a href="{{ route('admin.blotter.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>
                            Reset View
                        </a>
                    </div>
                </div>
                <div class="residency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Resolved: <strong>{{ $resolved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Dropped: <strong>{{ $dropped_count ?? 0 }}</strong></span>
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
                            <i class="fas fa-book"></i>
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
                            <div class="stat-label text-muted mb-1">Resolved</div>
                            <div class="stat-number">{{ $resolved_count ?? 0 }}</div>
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
                            <div class="stat-label text-muted mb-1">Dropped</div>
                            <div class="stat-number">{{ $dropped_count ?? 0 }}</div>
                            <small class="text-danger mt-2 d-none d-sm-block">
                                <i class="fas fa-times-circle me-1"></i>Not pursued
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
                    <form method="GET" action="{{ route('admin.blotter.index') }}" id="searchForm">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-12 col-md-6">
                                <div class="filter-title-block">
                                    <h6><span class="filter-title-icon"><i class="fas fa-filter"></i></span> Filter Incident Reports</h6>
                                    <p>Search references, report type, parties involved, confidentiality, and status.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    @admin_can('create_blotter')
                                    <a href="#" class="btn btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#addIncidentModal">
                                        <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Add Incident Report</span>
                                    </a>
                                    @endadmin_can
                                    <a href="{{ route('admin.blotter.index') }}" class="btn btn-light-soft flex-fill flex-md-grow-0">
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
                                    <input type="text" name="search" data-input-sanitize="admin-search" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search reference or complainant..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search d-sm-none"></i>
                                        <span class="d-none d-sm-inline">Search</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <select name="report_type" class="form-select">
                                    <option value="">All Report Type</option>
                                    <option value="dispute" {{ request('report_type') == 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                    <option value="security" {{ request('report_type') == 'security' ? 'selected' : '' }}>Security Concern</option>
                                    <option value="public" {{ request('report_type') == 'public' ? 'selected' : '' }}>Public Safety</option>
                                    <option value="other" {{ request('report_type') == 'other' ? 'selected' : '' }}>Other Concerns</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(auth('admin')->user()->hasAnyPermission(['delete_blotter', 'restore_blotter', 'export_blotter']))
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        @if(request('status') === 'archived')
                            @admin_can('restore_blotter')
                            <form id="bulkForm" method="POST" action="{{ route('admin.blotter.bulkRestore') }}" style="display: inline;">
                                @csrf
                                <button type="button"
                                        data-click-call="bulkAction" data-click-args='["restore"]'
                                        class="btn btn-outline-success d-flex align-items-center gap-2"
                                        title="Bulk Restore">
                                    <i class="fas fa-rotate-left"></i>
                                    <span class="d-none d-sm-inline">Bulk Restore</span>
                                </button>
                            </form>
                            @endadmin_can
                        @else
                            @admin_can('delete_blotter')
                            <form id="bulkForm" method="POST" action="{{ route('admin.blotter.bulkDelete') }}" style="display: inline;">
                                @csrf
                                <button type="button"
                                        data-click-call="bulkAction" data-click-args='["archive"]'
                                        class="btn btn-outline-danger d-flex align-items-center gap-2"
                                        title="Bulk Archive">
                                    <i class="fas fa-box-archive"></i>
                                    <span class="d-none d-sm-inline">Bulk Archive</span>
                                </button>
                            </form>
                            @endadmin_can
                        @endif

                        @admin_can('export_blotter')
                        <form id="exportForm" method="POST" action="{{ route('admin.blotter.export') }}" style="display: inline;">
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

            <div class="card border-0 residency-table-card incident-table-card">
                <div class="card-body p-0">
                    <div class="residency-table-head">
                        <div class="table-head-title">
                            <span class="table-head-icon"><i class="fas fa-list-check"></i></span>
                            <div>
                                <span class="table-kicker">Case queue</span>
                                <h3>Incident reports</h3>
                                <p>Scan the essential case details, then use View or the three-dot menu for status and archive controls.</p>
                            </div>
                        </div>
                        <span class="table-count-pill"><i class="fas fa-book"></i>{{ $incidents->total() ?? 0 }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="blotterTable">
                            <colgroup>
                                <col class="col-select">
                                <col class="col-incident">
                                <col class="col-parties">
                                <col class="col-location">
                                <col class="col-classification">
                                <col class="col-status">
                                <col class="col-actions">
                            </colgroup>
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox"
                                               id="selectAll"
                                               data-click-call="toggleSelectAll"
                                               aria-label="Select all incident reports">
                                    </th>

                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'reference_number', 'direction' => request('sort') === 'reference_number' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Incident / Reference
                                            @if(request('sort') === 'reference_number')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'complainant_name', 'direction' => request('sort') === 'complainant_name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Parties
                                            @if(request('sort') === 'complainant_name')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location', 'direction' => request('sort') === 'location' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Location
                                            @if(request('sort') === 'location')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'report_type', 'direction' => request('sort') === 'report_type' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Type / Privacy
                                            @if(request('sort') === 'report_type')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            Status / Submitted
                                            @if(request('sort') === 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th class="text-end pe-4 actions-heading"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($incidents as $blotter)
                                    <tr class="incident-row {{ $blotter->deleted_at ? 'is-archived' : '' }}">
                                        <td class="ps-4">
                                            <input type="checkbox"
                                                   value="{{ $blotter->id }}"
                                                   class="application-checkbox"
                                                   aria-label="Select incident {{ $blotter->reference_number }}">
                                        </td>

                                        <td class="ps-0">
                                            <div class="application-primary incident-reference-block">
                                                <div class="application-avatar">
                                                    <i class="fas fa-book-open" aria-hidden="true"></i>
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="application-name"
                                                         title="{{ $blotter->reference_number }}">
                                                        {{ $blotter->reference_number ?: 'No reference number' }}
                                                    </div>

                                                    <div class="application-muted-line incident-occurrence"
                                                         title="Occurred {{ $blotter->incident_schedule_label }}">
                                                        <i class="far fa-calendar" aria-hidden="true"></i>
                                                        <span>Occurred {{ $blotter->incident_schedule_label }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="party-stack">
                                                <div class="party-line">
                                                    <i class="fas fa-user" aria-hidden="true"></i>
                                                    <span title="{{ $blotter->complainant_name ?: 'N/A' }}">
                                                        <span class="party-label">Complainant</span>
                                                        <strong>{{ $blotter->complainant_name ?: 'N/A' }}</strong>
                                                    </span>
                                                </div>

                                                <div class="party-line">
                                                    <i class="fas fa-user-shield" aria-hidden="true"></i>
                                                    <span title="{{ $blotter->respondent_name ?: 'N/A' }}">
                                                        <span class="party-label">Respondent</span>
                                                        <strong>{{ $blotter->respondent_name ?: 'N/A' }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="incident-location-cell">
                                            <div class="location-display"
                                                 title="{{ $blotter->location ?: 'No location set' }}">
                                                <i class="fas fa-location-dot" aria-hidden="true"></i>
                                                <span class="location-text">
                                                    {{ $blotter->location ?: 'No location set' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="info-stack incident-classification-stack">
                                                <span class="incident-type-pill"
                                                      title="Report type: {{ $blotter->report_type_label }}">
                                                    <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
                                                    {{ $blotter->report_type_label }}
                                                </span>

                                                <span class="confidentiality-pill confidentiality-{{ $blotter->confidentiality }}"
                                                      title="Privacy: {{ $blotter->confidentiality_label }}">
                                                    <i class="fas fa-shield-halved" aria-hidden="true"></i>
                                                    {{ $blotter->confidentiality_label }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="status-cell">
                                                @if($blotter->deleted_at)
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="fas fa-box-archive me-1" aria-hidden="true"></i>Archived
                                                    </span>
                                                @elseif($blotter->status === 'resolved')
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Resolved
                                                    </span>
                                                @elseif($blotter->status === 'dropped')
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="fas fa-times-circle me-1" aria-hidden="true"></i>Dropped
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="fas fa-clock me-1" aria-hidden="true"></i>Processing
                                                    </span>
                                                @endif

                                                <span class="status-date"
                                                      title="Submitted {{ $blotter->created_at ? $blotter->created_at->format('M d, Y h:i A') : 'N/A' }}">
                                                    <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                                    {{ $blotter->created_at ? $blotter->created_at->format('M d, Y h:i A') : 'N/A' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="text-end pe-4 actions-cell">
                                            <div class="action-buttons"
                                                 aria-label="Controls for incident {{ $blotter->reference_number }}">
                                                <button type="button"
                                                        class="btn btn-sm action-btn action-primary action-view"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $blotter->id }}"
                                                        title="View incident details"
                                                        aria-label="View incident {{ $blotter->reference_number }}">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                    <span class="action-label">View</span>
                                                </button>

                                                <div class="dropdown action-more-dropdown">
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-more-toggle icon-only"
                                                            data-bs-toggle="dropdown"
                                                            data-bs-boundary="viewport"
                                                            aria-expanded="false"
                                                            title="More options"
                                                            aria-label="More options for incident {{ $blotter->reference_number }}">
                                                        <i class="fas fa-ellipsis-vertical" aria-hidden="true"></i>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-end action-menu">
                                                        @if(!$blotter->deleted_at && (auth('admin')->user()->hasPermission('approve_blotter') || auth('admin')->user()->hasPermission('reject_blotter')))
                                                            <button type="button"
                                                                    class="dropdown-item action-menu-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updateStatusModal{{ $blotter->id }}">
                                                                <span class="action-menu-icon action-menu-icon-status">
                                                                    <i class="fas fa-arrows-rotate" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Update case status</strong>
                                                                    <small>Mark as processing, resolved, or dropped</small>
                                                                </span>
                                                            </button>
                                                        @else
                                                            <span class="dropdown-item action-menu-item disabled" aria-disabled="true">
                                                                <span class="action-menu-icon action-menu-icon-status">
                                                                    <i class="fas fa-arrows-rotate" aria-hidden="true"></i>
                                                                </span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Status update unavailable</strong>
                                                                    <small>The report is archived or permission is missing</small>
                                                                </span>
                                                            </span>
                                                        @endif

                                                        <div class="dropdown-divider"></div>

                                                        @if(!$blotter->deleted_at && auth('admin')->user()->hasPermission('delete_blotter'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.blotter.destroy', $blotter->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this incident report?']) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item action-menu-item action-menu-danger">
                                                                    <span class="action-menu-icon action-menu-icon-archive">
                                                                        <i class="fas fa-box-archive" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="action-menu-copy">
                                                                        <strong>Archive incident</strong>
                                                                        <small>Keep the report without permanently deleting it</small>
                                                                    </span>
                                                                </button>
                                                            </form>
                                                        @elseif($blotter->deleted_at && auth('admin')->user()->hasPermission('restore_blotter'))
                                                            <form method="POST"
                                                                  action="{{ route('admin.blotter.restore', $blotter->id) }}"
                                                                  class="action-menu-form"
                                                                  data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this incident report?']) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item action-menu-item">
                                                                    <span class="action-menu-icon action-menu-icon-restore">
                                                                        <i class="fas fa-rotate-left" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="action-menu-copy">
                                                                        <strong>Restore incident</strong>
                                                                        <small>Return this report to the active case list</small>
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
                                                                    <small>You do not have permission for this action</small>
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
                                            <div class="empty-state-box">
                                                <div class="empty-state-icon"><i class="fas fa-book"></i></div>
                                                <h5>No incident reports found</h5>
                                                <p>Try adjusting your search or filters, or add a new report.</p>
                                                @admin_can('create_blotter')
                                                    <button class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addIncidentModal">
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

                    @if(isset($incidents) && $incidents->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $incidents->firstItem() ?? 0 }}-{{ $incidents->lastItem() ?? 0 }} of {{ $incidents->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $incidents->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            @admin_can('create_blotter')
            <div class="modal fade" id="addIncidentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    New Incident Report
                                </h5>
                                <p class="mb-0 mt-1 text-muted small">Complete all 4 steps below. Fields marked <span class="text-danger">*</span> are required.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.blotter.store') }}" id="addIncidentForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Incident Details</h6>
                                            <p>Record what happened, when it happened, where it occurred, and how the case should be classified.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Report Type <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('reportType') is-invalid @enderror @endif"
                                                name="reportType" required>
                                            <option value="">Select type</option>
                                            <option value="dispute" {{ (session('form_type') == 'add' && old('reportType') == 'dispute') ? 'selected' : '' }}>Community Dispute</option>
                                            <option value="security" {{ (session('form_type') == 'add' && old('reportType') == 'security') ? 'selected' : '' }}>Security Concern</option>
                                            <option value="public" {{ (session('form_type') == 'add' && old('reportType') == 'public') ? 'selected' : '' }}>Public Safety</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('reportType') == 'other') ? 'selected' : '' }}>Other Concern</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('reportType')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Confidentiality <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('confidentiality') is-invalid @enderror @endif"
                                                name="confidentiality" required>
                                            <option value="">Select confidentiality</option>
                                            <option value="low" {{ (session('form_type') == 'add' && old('confidentiality') == 'low') ? 'selected' : '' }}>Public Report</option>
                                            <option value="medium" {{ (session('form_type') == 'add' && old('confidentiality') == 'medium') ? 'selected' : '' }}>Confidential Report</option>
                                            <option value="high" {{ (session('form_type') == 'add' && old('confidentiality') == 'high') ? 'selected' : '' }}>Anonymous Report</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('confidentiality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('incidentDate') is-invalid @enderror @endif"
                                            name="incidentDate" value="{{ session('form_type') == 'add' ? old('incidentDate') : '' }}" required max="{{ date('Y-m-d') }}">
                                        @if(session('form_type') == 'add')
                                            @error('incidentDate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Incident Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('incidentTime') is-invalid @enderror @endif"
                                            name="incidentTime" value="{{ session('form_type') == 'add' ? old('incidentTime') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('incidentTime')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('incidentLocation') is-invalid @enderror @endif"
                                            name="incidentLocation" value="{{ session('form_type') == 'add' ? old('incidentLocation') : '' }}" placeholder="Incident location" required>
                                        @if(session('form_type') == 'add')
                                            @error('incidentLocation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('incidentDescription') is-invalid @enderror @endif"
                                                name="incidentDescription" rows="3" placeholder="Describe the incident..." required>{{ session('form_type') == 'add' ? old('incidentDescription') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('incidentDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Immediate Action Taken</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('immediateAction') is-invalid @enderror @endif"
                                                name="immediateAction" rows="2" placeholder="Any immediate action taken...">{{ session('form_type') == 'add' ? old('immediateAction') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('immediateAction')
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
                                            <h6>Complainant Information</h6>
                                            <p>Enter the complainant’s contact details and current address for case follow-up.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('complainantName') is-invalid @enderror @endif"
                                            name="complainantName" value="{{ session('form_type') == 'add' ? old('complainantName') : '' }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only." required>
                                        @if(session('form_type') == 'add')
                                            @error('complainantName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('complainantContact') is-invalid @enderror @endif"
                                            name="complainantContact" value="{{ session('form_type') == 'add' ? old('complainantContact') : '' }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09." required>
                                        <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                        @if(session('form_type') == 'add')
                                            @error('complainantContact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('complainantAddress') is-invalid @enderror @endif"
                                                name="complainantAddress" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'add' ? old('complainantAddress') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('complainantAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('complainantEmail') is-invalid @enderror @endif"
                                            name="complainantEmail" value="{{ session('form_type') == 'add' ? old('complainantEmail') : '' }}" placeholder="email@example.com" required>
                                        @if(session('form_type') == 'add')
                                            @error('complainantEmail')
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
                                            <h6>Respondent Information</h6>
                                            <p>Add respondent information when available. These fields remain optional.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('respondentName') is-invalid @enderror @endif"
                                            name="respondentName" value="{{ session('form_type') == 'add' ? old('respondentName') : '' }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                        @if(session('form_type') == 'add')
                                            @error('respondentName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Respondent Contact</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('respondentContact') is-invalid @enderror @endif"
                                            name="respondentContact" value="{{ session('form_type') == 'add' ? old('respondentContact') : '' }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                        @if(session('form_type') == 'add')
                                            @error('respondentContact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Respondent Address</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('respondentAddress') is-invalid @enderror @endif"
                                                name="respondentAddress" rows="2" placeholder="Complete address">{{ session('form_type') == 'add' ? old('respondentAddress') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('respondentAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Respondent Description</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('respondentDescription') is-invalid @enderror @endif"
                                                name="respondentDescription" rows="2" placeholder="Description of respondent (if any)">{{ session('form_type') == 'add' ? old('respondentDescription') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('respondentDescription')
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
                                            <h6>Supporting Information</h6>
                                            <p>Add witnesses, supporting evidence, and any additional case information before saving the report.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                    @if(auth('admin')->user()->hasPermission('add_witness'))
                                    <div class="col-12 incident-witness-section">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="modal-section-title"><i class="fas fa-users"></i> Witnesses</div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-click-call="addWitnessField">
                                                <i class="fas fa-plus me-1"></i>Add Witness
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mb-3">Add witnesses to support this incident report</small>

                                        <div id="witnesses-container">

                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-12 incident-evidence-section">
                                        <div class="supporting-subsection-title"><i class="fas fa-paperclip"></i> Evidence Upload</div>

                                        <div class="incident-upload-grid">

                                        <div class="incident-upload-card">
                                            <label class="form-label">Photos (Max: 10MB each, JPG/PNG only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('photos') is-invalid @enderror @endif"
                                                name="photos[]" accept="image/jpeg,image/jpg,image/png">
                                            <small class="text-muted">Upload photo evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('photos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="incident-upload-card">
                                            <label class="form-label">Videos (Max: 50MB each, MP4/AVI/MOV only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('videos') is-invalid @enderror @endif"
                                                name="videos[]" accept="video/mp4,video/avi,video/quicktime">
                                            <small class="text-muted">Upload video evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('videos')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="incident-upload-card">
                                            <label class="form-label">Documents (Max: 5MB each, PDF/DOC/DOCX only)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('documents') is-invalid @enderror @endif"
                                                name="documents[]" accept=".pdf,.doc,.docx">
                                            <small class="text-muted">Upload document evidence (optional)</small>
                                            @if(session('form_type') == 'add')
                                                @error('documents')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-12 incident-additional-section">
                                        <label class="form-label">Additional Information (Optional)</label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('additionalInfo') is-invalid @enderror @endif"
                                                name="additionalInfo" rows="2" placeholder="Any additional information...">{{ session('form_type') == 'add' ? old('additionalInfo') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('additionalInfo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addIncidentForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_blotter'))
                @foreach($incidents as $blotter)
                <div class="modal fade" id="editIncidentModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Edit Incident Report - {{ $blotter->reference_number }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.blotter.update', $blotter->id) }}" id="editIncidentForm{{ $blotter->id }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $blotter->id }}">

                                    <div class="row g-3">

                                        <div class="col-12">
                                            <h6 class="fw-semibold text-primary">Incident Details</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Report Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $blotter->id) @error('reportType') is-invalid @enderror @endif"
                                                    name="reportType" required>
                                                <option value="">Select type</option>
                                                <option value="dispute" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                                <option value="security" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'security' ? 'selected' : '' }}>Security Concern</option>
                                                <option value="public" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'public' ? 'selected' : '' }}>Public Safety</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $blotter->id ? old('reportType', $blotter->report_type) : $blotter->report_type) == 'other' ? 'selected' : '' }}>Other Concern</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('reportType')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Confidentiality <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $blotter->id) @error('confidentiality') is-invalid @enderror @endif"
                                                    name="confidentiality" required>
                                                <option value="">Select confidentiality</option>
                                                <option value="low" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'low' ? 'selected' : '' }}>Public Report</option>
                                                <option value="medium" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'medium' ? 'selected' : '' }}>Confidential Report</option>
                                                <option value="high" {{ (session('form_type') == 'edit_' . $blotter->id ? old('confidentiality', $blotter->confidentiality) : $blotter->confidentiality) == 'high' ? 'selected' : '' }}>Anonymous Report</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('confidentiality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentDate') is-invalid @enderror @endif"
                                                id="edit_incident_date_{{ $blotter->id }}"
                                                name="incidentDate" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentDate', \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d')) : \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentDate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Incident Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentTime') is-invalid @enderror @endif"
                                                name="incidentTime" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentTime', $blotter->incident_time) : $blotter->incident_time }}" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentTime')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentLocation') is-invalid @enderror @endif"
                                                name="incidentLocation" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentLocation', $blotter->location) : $blotter->location }}" placeholder="Incident location" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentLocation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('incidentDescription') is-invalid @enderror @endif"
                                                    name="incidentDescription" rows="3" placeholder="Describe the incident..." required>{{ session('form_type') == 'edit_' . $blotter->id ? old('incidentDescription', $blotter->description) : $blotter->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('incidentDescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Immediate Action Taken</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('immediateAction') is-invalid @enderror @endif"
                                                    name="immediateAction" rows="2" placeholder="Any immediate action taken...">{{ session('form_type') == 'edit_' . $blotter->id ? old('immediateAction', $blotter->immediate_action) : $blotter->immediate_action }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('immediateAction')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Complainant Information</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantName') is-invalid @enderror @endif"
                                                id="edit_complainant_name_{{ $blotter->id }}"
                                                name="complainantName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantName', $blotter->complainant_name) : $blotter->complainant_name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only." required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantContact') is-invalid @enderror @endif"
                                                id="edit_complainant_contact_{{ $blotter->id }}"
                                                name="complainantContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantContact', $blotter->complainant_contact) : $blotter->complainant_contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09." required>
                                            <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantContact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantAddress') is-invalid @enderror @endif"
                                                    name="complainantAddress" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantAddress', $blotter->complainant_address) : $blotter->complainant_address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantAddress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('complainantEmail') is-invalid @enderror @endif"
                                                id="edit_complainant_email_{{ $blotter->id }}"
                                                name="complainantEmail" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('complainantEmail', $blotter->complainant_email) : $blotter->complainant_email }}" placeholder="email@example.com" required>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('complainantEmail')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Respondent Information (Optional)</h6>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Respondent Name</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentName') is-invalid @enderror @endif"
                                                id="edit_respondent_name_{{ $blotter->id }}"
                                                name="respondentName" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentName', $blotter->respondent_name) : $blotter->respondent_name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Respondent Contact</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentContact') is-invalid @enderror @endif"
                                                name="respondentContact" value="{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentContact', $blotter->respondent_contact) : $blotter->respondent_contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentContact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Respondent Address</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentAddress') is-invalid @enderror @endif"
                                                    name="respondentAddress" rows="2" placeholder="Complete address">{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentAddress', $blotter->respondent_address) : $blotter->respondent_address }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentAddress')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Respondent Description</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('respondentDescription') is-invalid @enderror @endif"
                                                    name="respondentDescription" rows="2" placeholder="Description of respondent (if any)">{{ session('form_type') == 'edit_' . $blotter->id ? old('respondentDescription', $blotter->respondent_description) : $blotter->respondent_description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('respondentDescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        @if(auth('admin')->user()->hasPermission('add_witness'))
                                        <div class="col-12 mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="modal-section-title"><i class="fas fa-users"></i> Witnesses</div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-click-call="addEditWitnessField" data-click-args="{{ json_encode([$blotter->id]) }}">
                                                    <i class="fas fa-plus me-1"></i>Add Witness
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mb-3">Add witnesses to support this incident report</small>

                                            <div id="edit-witnesses-container-{{ $blotter->id }}">
                                                @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                                    @foreach($blotter->witnesses as $index => $witness)
                                                    <div class="witness-card mb-3" id="edit-witness-{{ $blotter->id }}-{{ $index }}">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-semibold">Witness</h6>
                                                            @if(auth('admin')->user()->hasPermission('delete_witness'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-click-call="removeEditWitnessField" data-click-args="{{ json_encode([$blotter->id, $index]) }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Witness Name</label>
                                                                <input type="text" class="form-control" name="witnesses[{{ $index }}][name]" value="{{ $witness->name }}" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Contact Number</label>
                                                                <input type="text" class="form-control" name="witnesses[{{ $index }}][contact]" value="{{ $witness->contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Witness Statement</label>
                                                                <textarea class="form-control" name="witnesses[{{ $index }}][statement]" rows="2" placeholder="What did the witness see/hear?">{{ $witness->statement }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary mb-3">Evidence Upload</h6>

                                            @if($blotter->files && $blotter->files->count() > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Existing Files</label>
                                                <div class="row g-2">
                                                    @foreach($blotter->files as $file)
                                                    <div class="col-md-4">
                                                        <div class="border rounded p-2 position-relative">
                                                            @php
                                                                $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                                                $fileUrl = asset($file->file_path);
                                                            @endphp

                                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                <img src="{{ $fileUrl }}" class="img-fluid rounded" style="max-height: 100px;" data-click-call="openZoomModal" data-click-args="{{ json_encode([$fileUrl]) }}">
                                                            @else
                                                                <i class="fas fa-file fa-3x text-muted"></i>
                                                            @endif

                                                            <div class="mt-1 small">
                                                                <span class="text-truncate d-block">{{ basename($file->file_path) }}</span>
                                                                <span class="badge bg-info">{{ $file->file_type }}</span>
                                                            </div>
                                                            @if(auth('admin')->user()->hasPermission('delete_evidence'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" data-click-call="removeFile" data-click-args="{{ json_encode([$file->id]) }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label">Photos (Max: 10MB each, JPG/PNG only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('photos.*') is-invalid @enderror @endif"
                                                    name="photos[]" multiple accept="image/jpeg,image/jpg,image/png">
                                                <small class="text-muted">You can select multiple photos</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('photos.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Videos (Max: 50MB each, MP4/AVI/MOV only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('videos.*') is-invalid @enderror @endif"
                                                    name="videos[]" multiple accept="video/mp4,video/avi,video/quicktime">
                                                <small class="text-muted">You can select multiple videos</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('videos.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Documents (Max: 5MB each, PDF/DOC/DOCX only)</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('documents.*') is-invalid @enderror @endif"
                                                    name="documents[]" multiple accept=".pdf,.doc,.docx">
                                                <small class="text-muted">You can select multiple documents</small>
                                                @if(session('form_type') == 'edit_' . $blotter->id)
                                                    @error('documents.*')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label">Additional Information (Optional)</label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $blotter->id) @error('additionalInfo') is-invalid @enderror @endif"
                                                    name="additionalInfo" rows="2" placeholder="Any additional information...">{{ session('form_type') == 'edit_' . $blotter->id ? old('additionalInfo', $blotter->additional_info) : $blotter->additional_info }}</textarea>
                                            @if(session('form_type') == 'edit_' . $blotter->id)
                                                @error('additionalInfo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editIncidentForm{{ $blotter->id }}" class="btn btn-primary" id="submitEditForm{{ $blotter->id }}">
                                        <i class="fas fa-save me-2"></i>Update Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($incidents as $blotter)
            <div class="modal fade" id="viewModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="view-modal-head w-100">
                                <div class="view-modal-avatar">
                                    {{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}
                                </div>
                                <div class="view-modal-meta">
                                    <div class="view-modal-name">{{ $blotter->complainant_name }}</div>
                                    <div class="view-modal-sub">{{ $blotter->reference_number }} - Incident Report</div>
                                    <div class="mt-1 d-flex flex-wrap gap-2">
                                        @if($blotter->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($blotter->status == 'resolved')
                                            <span class="badge bg-success-subtle text-success">Resolved</span>
                                        @elseif($blotter->status == 'dropped')
                                            <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Processing</span>
                                        @endif
                                        <span class="badge bg-info-subtle text-info">{{ $blotter->report_type_label }}</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav view-tabs mb-3" id="viewTabs{{ $blotter->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#details-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="details-panel-{{ $blotter->id }}" aria-selected="true">Details</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#edit-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="edit-panel-{{ $blotter->id }}" aria-selected="false">Edit info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="history-tab-{{ $blotter->id }}" data-bs-toggle="pill" data-bs-target="#history-panel-{{ $blotter->id }}" type="button" role="tab" aria-controls="history-panel-{{ $blotter->id }}" aria-selected="false">History</button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="details-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="details-tab-{{ $blotter->id }}">
                                    <div class="application-modal-summary">
                                        <div class="modal-summary-card is-primary">
                                            <span class="modal-summary-label">Complainant</span>
                                            <span class="modal-summary-value">{{ $blotter->complainant_name }}</span>
                                            <span class="modal-summary-help"><i class="fas fa-hashtag me-1"></i>{{ $blotter->reference_number }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Current Status</span>
                                            <span class="modal-summary-value">
                                                @if($blotter->deleted_at) Archived
                                                @elseif($blotter->status == 'resolved') Resolved
                                                @elseif($blotter->status == 'dropped') Dropped
                                                @else Processing
                                                @endif
                                            </span>
                                            <span class="modal-summary-help">Incident progress</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Incident Date</span>
                                            <span class="modal-summary-value">{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : 'No time recorded' }}</span>
                                        </div>
                                        <div class="modal-summary-card">
                                            <span class="modal-summary-label">Submitted</span>
                                            <span class="modal-summary-value">{{ $blotter->created_at ? $blotter->created_at->format('M d, Y') : 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $blotter->created_at ? $blotter->created_at->format('h:i A') : '' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-triangle-exclamation"></i>Incident details</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Report type</span>
                                                <div class="detail-value">{{ $blotter->report_type_label }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Confidentiality</span>
                                                <div class="detail-value">{{ $blotter->confidentiality_label }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Incident date</span>
                                                <div class="detail-value">{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Incident time</span>
                                                <div class="detail-value">{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Location</span>
                                                <div class="detail-value">{{ $blotter->location ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Description</span>
                                                <div class="detail-value">{{ $blotter->description ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Immediate action</span>
                                                <div class="detail-value">{{ $blotter->immediate_action ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-users"></i>People involved</div>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant</span>
                                                <div class="detail-value">{{ $blotter->complainant_name ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant contact</span>
                                                <div class="detail-value">{{ $blotter->complainant_contact ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Complainant email</span>
                                                <div class="detail-value">{{ $blotter->complainant_email ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Respondent</span>
                                                <div class="detail-value">{{ $blotter->respondent_name ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Complainant address</span>
                                                <div class="detail-value">{{ $blotter->complainant_address ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item" style="grid-column: 1 / -1;">
                                                <span class="detail-label">Respondent address / description</span>
                                                <div class="detail-value">{{ $blotter->respondent_address ?: 'N/A' }} {{ $blotter->respondent_description ? ' - ' . $blotter->respondent_description : '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-section-card">
                                        <div class="detail-section-title"><i class="fas fa-user-group"></i>Witnesses</div>
                                        @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                            <div class="incident-witness-list">
                                                @foreach($blotter->witnesses as $witness)
                                                <div class="incident-witness-card">
                                                    <div class="incident-witness-avatar">{{ strtoupper(substr($witness->name ?: 'W', 0, 1)) }}</div>
                                                    <div class="min-w-0">
                                                        <div class="incident-witness-name">{{ $witness->name ?: 'Unnamed witness' }}</div>
                                                        <div class="incident-witness-meta">{{ $witness->contact ?: 'No contact' }}</div>
                                                        <div class="incident-witness-statement">{{ $witness->statement ?: 'No statement provided.' }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="empty-doc-note"><i class="fas fa-user-slash"></i><span>No witnesses recorded.</span></div>
                                        @endif
                                    </div>

                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-folder-open"></i>Evidence and documents</div>
                                        @if($blotter->files && $blotter->files->count() > 0)
                                            <div class="incident-file-grid">
                                                @foreach($blotter->files as $file)
                                                <div class="incident-file-card">
                                                    <div class="incident-file-icon"><i class="fas fa-file-lines"></i></div>
                                                    <div class="incident-file-info min-w-0">
                                                        <div class="incident-file-name">{{ basename($file->file_path) }}</div>
                                                        <div class="incident-file-type">{{ ucfirst($file->file_type) }}</div>
                                                    </div>
                                                    <a href="{{ asset($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Open
                                                    </a>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No uploaded evidence available for this incident report.</span></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade edit-info-pane" id="edit-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="edit-tab-{{ $blotter->id }}">
                                    <div class="edit-info-banner">
                                        <div class="application-avatar">{{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="edit-info-title">Edit incident report for {{ $blotter->complainant_name }}</div>
                                            <div class="edit-info-subtitle">
                                                Reference {{ $blotter->reference_number }} · Update incident classification, involved parties, witnesses, and supporting files.
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.blotter.update', $blotter->id) }}" id="quickEditForm{{ $blotter->id }}" enctype="multipart/form-data" class="quick-edit-form modern-edit-form">
                                        @csrf
                                        @method('PUT')

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-triangle-exclamation"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Incident Details</div>
                                                    <p class="edit-section-help">Keep the report classification, date, time, and narrative accurate.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-list-check"></i>Report Type</label>
                                                        <select class="form-select" name="reportType" required>
                                                            <option value="dispute" {{ $blotter->report_type === 'dispute' ? 'selected' : '' }}>Community Dispute</option>
                                                            <option value="security" {{ $blotter->report_type === 'security' ? 'selected' : '' }}>Security Concern</option>
                                                            <option value="public" {{ $blotter->report_type === 'public' ? 'selected' : '' }}>Public Safety</option>
                                                            <option value="other" {{ $blotter->report_type === 'other' ? 'selected' : '' }}>Other Concern</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-shield-halved"></i>Confidentiality</label>
                                                        <select class="form-select" name="confidentiality" required>
                                                            <option value="low" {{ $blotter->confidentiality === 'low' ? 'selected' : '' }}>Public Report</option>
                                                            <option value="medium" {{ $blotter->confidentiality === 'medium' ? 'selected' : '' }}>Confidential Report</option>
                                                            <option value="high" {{ $blotter->confidentiality === 'high' ? 'selected' : '' }}>Anonymous Report</option>
                                                        </select>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-calendar"></i>Incident Date</label>
                                                        <input type="date" class="form-control" name="incidentDate" value="{{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('Y-m-d') : '' }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-clock"></i>Incident Time</label>
                                                        <input type="time" class="form-control" name="incidentTime" value="{{ $blotter->incident_time ? \Carbon\Carbon::parse($blotter->incident_time)->format('H:i') : '' }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-location-dot"></i>Incident Location</label>
                                                        <input type="text" class="form-control" name="incidentLocation" value="{{ $blotter->location }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-align-left"></i>Incident Description</label>
                                                        <textarea class="form-control" name="incidentDescription" rows="3" required>{{ $blotter->description }}</textarea>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-bolt"></i>Immediate Action</label>
                                                        <textarea class="form-control" name="immediateAction" rows="2">{{ $blotter->immediate_action }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Complainant Information</div>
                                                    <p class="edit-section-help">Used for follow-up, notices, and official report records.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-user"></i>Complainant Name</label>
                                                        <input type="text" class="form-control" name="complainantName" value="{{ $blotter->complainant_name }}" required>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Complainant Contact</label>
                                                        <input type="text" class="form-control" name="complainantContact" value="{{ $blotter->complainant_contact }}" maxlength="11" required>
                                                        <small>Use Philippine mobile format, for example 09XXXXXXXXX.</small>
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Complainant Email</label>
                                                        <input type="email" class="form-control" name="complainantEmail" value="{{ $blotter->complainant_email }}">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Complainant Address</label>
                                                        <input type="text" class="form-control" name="complainantAddress" value="{{ $blotter->complainant_address }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-user-shield"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Respondent Information</div>
                                                    <p class="edit-section-help">Add known respondent details if available.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-user"></i>Respondent Name</label>
                                                        <input type="text" class="form-control" name="respondentName" value="{{ $blotter->respondent_name }}">
                                                    </div>
                                                    <div class="edit-field edit-col-6">
                                                        <label class="form-label"><i class="fas fa-phone"></i>Respondent Contact</label>
                                                        <input type="text" class="form-control" name="respondentContact" value="{{ $blotter->respondent_contact }}" maxlength="11">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Respondent Address</label>
                                                        <input type="text" class="form-control" name="respondentAddress" value="{{ $blotter->respondent_address }}">
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-align-left"></i>Respondent Description</label>
                                                        <textarea class="form-control" name="respondentDescription" rows="2">{{ $blotter->respondent_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-users"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Witnesses</div>
                                                    <p class="edit-section-help">Record statements and contacts for supporting testimony.</p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary ms-auto js-add-quick-witness" data-blotter-id="{{ $blotter->id }}" data-click-call="addQuickEditWitnessField" data-click-pass="args,self" data-click-args="{{ json_encode([$blotter->id]) }}">
                                                    <i class="fas fa-plus me-1"></i>Add Witness
                                                </button>
                                            </div>
                                            <div class="edit-section-body">
                                                <div id="quick-edit-witnesses-container-{{ $blotter->id }}" class="incident-edit-witness-list">
                                                    @if($blotter->witnesses && $blotter->witnesses->count() > 0)
                                                        @foreach($blotter->witnesses as $witnessIndex => $witness)
                                                        <div class="witness-card edit-file-card" id="quick-edit-witness-{{ $blotter->id }}-{{ $witnessIndex }}">
                                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                                <strong>Witness {{ $witnessIndex + 1 }}</strong>
                                                                @if(auth('admin')->user()->hasPermission('delete_witness'))
                                                                <button type="button" class="btn btn-sm btn-outline-danger js-remove-quick-witness" data-blotter-id="{{ $blotter->id }}" data-witness-id="{{ $witnessIndex }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                            <div class="edit-form-grid">
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-user"></i>Name</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][name]" value="{{ $witness->name }}">
                                                                </div>
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-phone"></i>Contact</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][contact]" value="{{ $witness->contact }}" maxlength="11" inputmode="numeric">
                                                                </div>
                                                                <div class="edit-field edit-col-4">
                                                                    <label class="form-label"><i class="fas fa-comment-dots"></i>Statement</label>
                                                                    <input type="text" class="form-control" name="witnesses[{{ $witnessIndex }}][statement]" value="{{ $witness->statement }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    @else
                                                        <span class="edit-required-note"><i class="fas fa-circle-info"></i>No witnesses yet. Use Add Witness if needed.</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="edit-section-card">
                                            <div class="edit-section-head">
                                                <span class="edit-section-icon"><i class="fas fa-folder-open"></i></span>
                                                <div>
                                                    <div class="edit-section-title">Evidence Uploads</div>
                                                    <p class="edit-section-help">Leave uploads empty to keep existing files.</p>
                                                </div>
                                            </div>
                                            <div class="edit-section-body">
                                                <div class="edit-form-grid">
                                                    <div class="edit-field edit-col-12">
                                                        <label class="form-label"><i class="fas fa-note-sticky"></i>Additional Info</label>
                                                        <textarea class="form-control" name="additionalInfo" rows="2">{{ $blotter->additional_info }}</textarea>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-image"></i>Photos</label>
                                                            <input type="file" class="form-control" name="photos[]" accept="image/*" multiple>
                                                            <small>Upload images related to this incident.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-video"></i>Videos</label>
                                                            <input type="file" class="form-control" name="videos[]" accept="video/*" multiple>
                                                            <small>Upload video evidence if available.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-4">
                                                        <div class="edit-file-card">
                                                            <label class="form-label"><i class="fas fa-file-lines"></i>Documents</label>
                                                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.doc,.docx" multiple>
                                                            <small>Upload PDF or Word documents.</small>
                                                        </div>
                                                    </div>
                                                    <div class="edit-field edit-col-12">
                                                        <span class="edit-required-note"><i class="fas fa-shield-halved"></i>Review details before saving. These records may be used for official blotter documentation.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="history-panel-{{ $blotter->id }}" role="tabpanel" aria-labelledby="history-tab-{{ $blotter->id }}">
                                    <div class="detail-section-card mb-0">
                                        <div class="detail-section-title"><i class="fas fa-clock-rotate-left"></i>Status history</div>
                                        <div class="mb-3">
                                            <span class="detail-label">Recent status</span>
                                            @if($blotter->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger">Archived</span>
                                            @elseif($blotter->status == 'resolved')
                                                <span class="badge bg-success-subtle text-success">Resolved</span>
                                            @elseif($blotter->status == 'dropped')
                                                <span class="badge bg-danger-subtle text-danger">Dropped</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                                            @endif
                                        </div>
                                        <div id="historyList{{ $blotter->id }}" class="small text-muted">Loading history...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="viewFooterDetails{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-switch-tab="edit" data-app-id="{{ $blotter->id }}">Edit info</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterEdit{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-switch-tab="details" data-app-id="{{ $blotter->id }}">Cancel</button>
                            <button type="submit" form="quickEditForm{{ $blotter->id }}" class="btn btn-primary">Save changes</button>
                        </div>
                        <div class="modal-footer d-none" id="viewFooterHistory{{ $blotter->id }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="updateStatusModal{{ $blotter->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-pen-to-square me-2"></i>Update incident status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.blotter.approve', $blotter->id) }}" data-processing-url="{{ route('admin.blotter.processing', $blotter->id) }}" data-approve-url="{{ route('admin.blotter.approve', $blotter->id) }}" data-reject-url="{{ route('admin.blotter.reject', $blotter->id) }}" data-submit-call="handleIncidentStatusSubmit" data-submit-pass="event,self">
                            @csrf
                            <div class="modal-body">
                                <div class="status-flow-card">
                                    <div class="status-flow-person">
                                        <div class="application-avatar">{{ strtoupper(substr($blotter->complainant_name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <div class="fw-bold text-truncate">{{ $blotter->complainant_name }}</div>
                                            <div class="small text-muted">{{ $blotter->reference_number }} · Current: {{ ucwords(str_replace('_', ' ', $blotter->status)) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">New status</label>
                                    <input type="hidden" name="status" value="{{ $blotter->status }}" class="selected-incident-status">
                                    <div class="status-grid" data-current-status="{{ $blotter->status }}">
                                        <button type="button" class="status-option" data-status="processing"><span class="dot" style="background:#5e35b1;"></span>Processing</button>
                                        @if(auth('admin')->user()->hasPermission('approve_blotter'))
                                        <button type="button" class="status-option" data-status="resolved"><span class="dot" style="background:#2e7d32;"></span>Resolved</button>
                                        @endif
                                        @if(auth('admin')->user()->hasPermission('reject_blotter'))
                                        <button type="button" class="status-option" data-status="dropped"><span class="dot" style="background:#d32f2f;"></span>Dropped</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-uppercase fw-semibold small">Remarks <span class="text-muted fw-normal">(required if dropped, max 40 chars)</span></label>
                                    <textarea class="form-control" name="remarks" rows="3" maxlength="40" placeholder="Add a note about this status change..."></textarea>
                                </div>

                                <div data-notify-wrap data-has-email="{{ $blotter->complainant_email ? '1' : '0' }}" data-has-sms="{{ $blotter->complainant_contact ? '1' : '0' }}">
                                    <label class="form-label text-uppercase fw-semibold small">Notify complainant</label>
                                    <div class="notify-row">
                                        <div class="notify-label">
                                            <strong>Send email</strong>
                                            <small>{{ $blotter->complainant_email ?: 'No email available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_email" value="1" {{ auth('admin')->user()->hasPermission('send_email') ? 'checked' : '' }} {{ !$blotter->complainant_email || !auth('admin')->user()->hasPermission('send_email') ? 'disabled' : '' }} {{ !auth('admin')->user()->hasPermission('send_email') ? 'data-permission-disabled="1"' : '' }}>
                                        </div>
                                    </div>
                                    <div class="notify-row mb-0">
                                        <div class="notify-label">
                                            <strong>Send SMS</strong>
                                            <small>{{ $blotter->complainant_contact ?: 'No mobile number available' }}</small>
                                        </div>
                                        <div class="form-check form-switch notify-switch">
                                            <input class="form-check-input" type="checkbox" name="notify_sms" value="1" {{ auth('admin')->user()->hasPermission('send_sms') ? 'checked' : '' }} {{ !$blotter->complainant_contact || !auth('admin')->user()->hasPermission('send_sms') ? 'disabled' : '' }} {{ !auth('admin')->user()->hasPermission('send_sms') ? 'data-permission-disabled="1"' : '' }}>
                                        </div>
                                    </div>
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
            <script src="{{ asset('js/pages/admin/admin_incident_report.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-incident-report-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_incident_report-dynamic1.js') }}"></script>
            @endif
            </div>
@endsection

@push('scripts')
@include('shared.js-config', [
    'id' => 'js-config-admin-incidents-main',
    'data' => [
        'canUpdate' => auth('admin')->user()->hasPermission('update_blotter'),
        'incidentIds' => $incidents->pluck('id')->values(),
        'remarksHistoryUrl' => route('admin.notifications.remarksHistory'),
        'csrfToken' => csrf_token(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_incident_report-main.js') }}"></script>

@endpush
