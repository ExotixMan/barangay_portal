@extends('admin.layouts.admin')

@section('title', 'Indigency Applications')
@section('page_title', 'Indigency Applications')
@section('page_icon', 'fas fa-file-invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/indigency-applications.css') }}">
@endpush

@section('content')
<div class="residency-page">

            <!-- Success/Error Messages -->
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
                        <div class="residency-hero-icon"><i class="fas fa-file-invoice"></i></div>
                        <div class="residency-kicker">Indigency application management</div>
                        <h2>Review assistance requests with only the details that matter.</h2>
                        <p>Focus on the applicant, income bracket, household size, purpose, status, and next action. Full personal details and documents stay inside the modal for cleaner management.</p>
                    </div>

                    <div class="residency-hero-actions">
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
                <div class="residency-hero-meta">
                    <span><i class="fas fa-list-check"></i> Total: <strong>{{ $total_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-clock"></i> Processing: <strong>{{ $processing_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-check-circle"></i> Approved: <strong>{{ $approved_count ?? 0 }}</strong></span>
                    <span><i class="fas fa-times-circle"></i> Rejected: <strong>{{ $rejected_count ?? 0 }}</strong></span>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
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

            <!-- Filters - Mobile Responsive -->
            <div class="card border-0 mb-4 residency-filter-card">
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
                        
                        <!-- Search and Filter Row -->
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search by name, reference, email..." value="{{ request('search') }}">
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

                    <!-- Bulk Actions - Archive normally; restore when the Archived status filter is active -->
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
                                onclick="bulkDelete()"
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

            <!-- Indigency Table - Simplified Management View -->
            <div class="card border-0 residency-table-card">
                <div class="card-body p-0">
                    <div class="residency-table-head">
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
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Applicant / Reference
                                            @if(request('sort') == 'created_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Income / Household</th>
                                    <th>Purpose / Contact</th>
                                    <th>Status / Submitted</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($indigency as $ind)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $ind->id }}" class="application-checkbox">
                                    </td>

                                    <td class="ps-0">
                                        <div class="application-primary">
                                            <div class="application-avatar">
                                                {{ strtoupper(substr($ind->first_name, 0, 1) . substr($ind->last_name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="application-name">{{ $ind->first_name }} {{ $ind->last_name }} {{ $ind->suffix }}</div>
                                                <div class="application-ref">
                                                    <span class="ref-pill"><i class="fas fa-hashtag"></i>{{ $ind->reference_number }}</span>
                                                    <span>{{ $ind->created_at ? $ind->created_at->format('M d, Y') : 'N/A' }}</span>
                                                </div>
                                                <div class="application-muted-line d-lg-none">
                                                    <i class="fas fa-phone"></i>{{ $ind->contact_number ?: 'No contact' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Financial profile</span>
                                            <span class="income-badge"><i class="fas fa-wallet"></i>{{ $ind->monthly_income ?: 'N/A' }}</span>
                                            <span class="info-sub"><i class="fas fa-users me-1"></i>{{ $ind->household_members ?: 'N/A' }} household member(s)</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="info-stack">
                                            <span class="info-label-mini">Purpose</span>
                                            <span class="info-main">
                                                {{ $ind->purpose ? ucwords(str_replace('_', ' ', $ind->purpose)) : 'N/A' }}
                                            </span>
                                            @if($ind->purpose_other)
                                                <span class="info-sub">{{ $ind->purpose_other }}</span>
                                            @endif
                                            <span class="info-sub"><i class="fas fa-phone me-1"></i>{{ $ind->contact_number ?: 'No contact' }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="status-cell">
                                            @if($ind->deleted_at)
                                                <span class="badge bg-danger-subtle text-danger">Archived</span>
                                            @elseif($ind->status == 'approved')
                                                <span class="badge bg-success-subtle text-success">Approved</span>
                                            @elseif($ind->status == 'ready_pickup')
                                                <span class="badge bg-info-subtle text-info">Ready for Pickup</span>
                                            @elseif($ind->status == 'claimed')
                                                <span class="badge bg-success-subtle text-success">Claimed</span>
                                            @elseif($ind->status == 'rejected')
                                                <span class="badge bg-danger-subtle text-danger">Rejected</span>
                                            @elseif($ind->status == 'pending')
                                                <span class="badge bg-secondary-subtle text-secondary">Pending</span>
                                            @elseif($ind->status == 'under_review')
                                                <span class="badge bg-primary-subtle text-primary">Under Review</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Processing</span>
                                            @endif
                                            <span class="status-date"><i class="far fa-calendar me-1"></i>{{ $ind->created_at ? $ind->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                        </div>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm action-btn action-view icon-only" data-bs-toggle="modal" data-bs-target="#viewModal{{ $ind->id }}" title="View full details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if(!$ind->deleted_at && (auth('admin')->user()->hasPermission('update_indigency') || auth('admin')->user()->hasPermission('approve_indigency') || auth('admin')->user()->hasPermission('reject_indigency')))
                                            <button type="button" class="btn btn-sm action-btn action-update" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $ind->id }}" title="Update status">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-update" disabled title="Update unavailable">
                                                <i class="fas fa-pen"></i>
                                                <span class="action-label">Update</span>
                                            </button>
                                            @endif

                                            @if(!$ind->deleted_at && auth('admin')->user()->hasPermission('generate_indigency_document') && in_array($ind->status, ['approved', 'ready_pickup', 'claimed']))
                                            <button type="button" class="btn btn-sm action-btn action-docs icon-only" data-bs-toggle="modal" data-bs-target="#docsModal{{ $ind->id }}" title="Generate documents">
                                                <i class="fas fa-file-lines"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-docs icon-only" disabled title="Documents unavailable">
                                                <i class="fas fa-file-lines"></i>
                                            </button>
                                            @endif

                                            @if($ind->deleted_at && auth('admin')->user()->hasPermission('delete_indigency'))
                                            <form method="POST" action="{{ route('admin.indigency.restore', $ind->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this application?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm action-btn action-update icon-only" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @elseif(auth('admin')->user()->hasPermission('delete_indigency'))
                                            <form method="POST" action="{{ route('admin.indigency.destroy', $ind->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this application?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm action-btn action-archive icon-only" title="Archive">
                                                    <i class="fas fa-box-archive"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm action-btn action-archive icon-only" disabled title="Archive unavailable">
                                                <i class="fas fa-box-archive"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-file-invoice fa-4x text-muted mb-3 opacity-50"></i>
                                            <h5 class="text-muted">No applications found</h5>
                                            <p class="text-muted mb-3 small">Try adjusting your search or filter</p>
                                            @admin_can('create_indigency')
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
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

                    <!-- Pagination - Mobile Responsive -->
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

            <!-- Add Application Modal with Validation - Only if user has create_indigency permission -->
            @admin_can('create_indigency')
            <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-file-invoice me-2"></i>
                                New Indigency Application
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.indigency.store') }}" enctype="multipart/form-data" id="addApplicationForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Personal Information -->
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-primary">Personal Information</h6>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('first_name') is-invalid @enderror @endif" 
                                               name="first_name" value="{{ session('form_type') == 'add' ? old('first_name') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('middle_name') is-invalid @enderror @endif" 
                                               name="middle_name" value="{{ session('form_type') == 'add' ? old('middle_name') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('last_name') is-invalid @enderror @endif" 
                                               name="last_name" value="{{ session('form_type') == 'add' ? old('last_name') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Suffix</label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('suffix') is-invalid @enderror @endif" 
                                                name="suffix">
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
                                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" 
                                               name="birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ date('Y-m-d') }}">
                                        @if(session('form_type') == 'add')
                                            @error('birthdate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('gender') is-invalid @enderror @endif" 
                                                name="gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male" {{ (session('form_type') == 'add' && old('gender') == 'male') ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ (session('form_type') == 'add' && old('gender') == 'female') ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('gender') == 'other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Contact Information</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @if(session('form_type') == 'add') @error('email') is-invalid @enderror @endif" 
                                               name="email" value="{{ session('form_type') == 'add' ? old('email') : '' }}" placeholder="email@example.com" required>
                                        @if(session('form_type') == 'add')
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('contact_number') is-invalid @enderror @endif" 
                                               name="contact_number" value="{{ session('form_type') == 'add' ? old('contact_number') : '' }}" placeholder="09XXXXXXXXX" maxlength="11" required>
                                        <small class="text-muted">Format: 09XXXXXXXXX (11 digits)</small>
                                        @if(session('form_type') == 'add')
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('address') is-invalid @enderror @endif" 
                                                  name="address" rows="2" placeholder="Complete address" required>{{ session('form_type') == 'add' ? old('address') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Indigency Details -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Indigency Details</h6>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
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
                                        <label class="form-label">Household Members <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @if(session('form_type') == 'add') @error('household_members') is-invalid @enderror @endif" 
                                               name="household_members" value="{{ session('form_type') == 'add' ? old('household_members') : '' }}" min="1" max="20" placeholder="e.g., 4" required>
                                        @if(session('form_type') == 'add')
                                            @error('household_members')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Purpose -->
                                    <div class="col-12">
                                        <label class="form-label">Purpose <span class="text-danger">*</span></label>
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
                                        <label class="form-label">Specify Other Purpose <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('purpose_other') is-invalid @enderror @endif" 
                                               name="purpose_other" value="{{ session('form_type') == 'add' ? old('purpose_other') : '' }}" placeholder="Please specify">
                                        @if(session('form_type') == 'add')
                                            @error('purpose_other')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Documents -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Required Documents</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Proof of Residency <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('primary_proof') is-invalid @enderror @endif" 
                                               name="primary_proof" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('primary_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Valid ID <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('valid_id_path') is-invalid @enderror @endif" 
                                               name="valid_id_path" accept="image/*,.pdf" required>
                                        <small class="text-muted">Upload image or PDF (Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('valid_id_path')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
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

            <!-- Edit Application Modals with Validation - Only if user has update_indigency permission -->
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
                                        <!-- Personal Information -->
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
                                                value="{{ session('form_type') == 'edit_' . $ind->id ? old('birthdate', $ind->birthdate) : $ind->birthdate }}" 
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
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Contact Information -->
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

                                        <!-- Indigency Details -->
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

                                        <!-- Purpose -->
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

                                        <!-- Documents -->
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
                                            <label class="form-label">Valid ID</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ind->id) @error('valid_id_path') is-invalid @enderror @endif" 
                                                name="valid_id_path" id="edit_valid_id_path_{{ $ind->id }}" accept="image/*,.pdf">
                                            <small class="text-muted">Upload image or PDF (Max: 5MB) - Leave empty to keep current file</small>
                                            @if(session('form_type') == 'edit_' . $ind->id)
                                                @error('valid_id_path')
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

            <!-- View/Docs/Update Modals -->
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
                                            <span class="modal-summary-value">{{ $ind->monthly_income ?: 'N/A' }}</span>
                                            <span class="modal-summary-help">{{ $ind->household_members ?: 'N/A' }} household member(s)</span>
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
                                            <div class="detail-item"><span class="detail-label">Monthly income</span><div class="detail-value">{{ $ind->monthly_income ?: 'N/A' }}</div></div>
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
                                                        <img src="{{ asset($ind->primary_proof) }}" alt="Proof of residency" onclick="openZoomModal('{{ asset($ind->primary_proof) }}')">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-file-circle-xmark"></i><span>No proof uploaded</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="document-preview-card">
                                                <div class="doc-head"><span><i class="fas fa-id-card me-1"></i>Valid ID</span></div>
                                                <div class="doc-body">
                                                    @if($ind->valid_id_path)
                                                        <img src="{{ asset($ind->valid_id_path) }}" alt="Valid ID" onclick="openZoomModal('{{ asset($ind->valid_id_path) }}')">
                                                    @else
                                                        <div class="empty-doc-note"><i class="fas fa-id-card-clip"></i><span>No valid ID uploaded</span></div>
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
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-calendar"></i>Birthdate</label><input type="date" name="birthdate" class="form-control" value="{{ $ind->birthdate }}" required max="{{ date('Y-m-d') }}"></div>
                                                <div class="edit-field edit-col-4"><label class="form-label"><i class="fas fa-venus-mars"></i>Gender</label><select class="form-select" name="gender" required><option value="male" {{ $ind->gender == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ $ind->gender == 'female' ? 'selected' : '' }}>Female</option><option value="other" {{ $ind->gender == 'other' ? 'selected' : '' }}>Other</option></select></div>
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
                                                <div class="edit-field edit-col-6"><div class="edit-file-card"><label class="form-label"><i class="fas fa-id-card"></i>Valid ID</label><input type="file" name="valid_id_path" class="form-control" accept="image/*,.pdf"><small>Upload image or PDF. Leave empty to keep current file.</small>@if($ind->valid_id_path)<span class="edit-current-file"><i class="fas fa-circle-info"></i>Current: {{ basename($ind->valid_id_path) }}</span>@endif</div></div>
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
                        <form method="POST" action="{{ route('admin.indigency.status', $ind->id) }}" onsubmit="return handleUpdateStatusSubmit(event, this)">
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

            <!-- Global Image Zoom Modal -->
            <div class="modal fade" id="globalImageZoomModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addApplicationModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editIndigencyModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
                    </div>
@endsection

@push('scripts')
<script>
        // Archives records normally, but restores them when the Archived filter is active.
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (!bulkForm) {
                return;
            }

            const action = bulkForm.dataset.bulkAction === 'restore' ? 'restore' : 'archive';
            const isRestore = action === 'restore';
            const actionLabel = isRestore ? 'restore' : 'archive';
            const actionTitle = isRestore ? 'Bulk Restore' : 'Bulk Archive';

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: `Please select at least one application to ${actionLabel}.`,
                    confirmButtonColor: isRestore ? '#198754' : '#d33'
                });
                return;
            }

            Swal.fire({
                title: `Confirm ${actionTitle}`,
                text: `Are you sure you want to ${actionLabel} ${checkboxes.length} selected application(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isRestore ? '#198754' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isRestore ? 'Yes, Restore' : 'Yes, Archive'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                // Prevent duplicate IDs when the action is triggered more than once.
                bulkForm.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());

                checkboxes.forEach((checkbox) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    bulkForm.appendChild(input);
                });

                bulkForm.submit();
            });
        }

        // Export CSV function
        function exportCSV() {

            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            // If nothing selected → Ask to export all
            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No applications selected. Export all records?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Export All'
                }).then((result) => {

                    if (result.isConfirmed) {
                        exportForm.submit();
                    }

                });

                return;
            }

            // If selected → Confirm export selected
            Swal.fire({
                title: 'Export Selected?',
                text: `Export ${checkboxes.length} selected application(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Export'
            }).then((result) => {

                if (result.isConfirmed) {

                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        exportForm.appendChild(input);
                    });

                    exportForm.submit();
                }

            });
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.application-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Confirm delete with SweetAlert
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            
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
            
            return false;
        }

        // Show/hide other purpose field
        document.getElementById('purpose')?.addEventListener('change', function() {
            const otherField = document.getElementById('otherPurposeField');
            if (this.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }
        });

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swal if not loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return confirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.application-checkbox');
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

            // Show modal if there are validation errors
            @if ($errors->any() && session('form_type') == 'add')
                var addModal = new bootstrap.Modal(document.getElementById('addApplicationModal'));
                addModal.show();
            @endif

            // Real-time validation for add form
            const addForm = document.getElementById('addApplicationForm');
            if (addForm) {
                // Contact number validation
                const contact = document.querySelector('input[name="contact_number"]');
                if (contact) {
                    contact.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '');
                        if (this.value.length > 11) {
                            this.value = this.value.slice(0, 11);
                        }
                        if (this.value.length > 0 && !this.value.startsWith('09')) {
                            this.setCustomValidity('Contact number must start with 09');
                            this.classList.add('is-invalid');
                        }
                        else if (this.value.length > 0 && this.value.length !== 11) {
                            this.setCustomValidity('Contact number must be exactly 11 digits');
                            this.classList.add('is-invalid');
                        }
                        else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // Birthdate validation
                const birthdate = document.querySelector('input[name="birthdate"]');
                if (birthdate) {
                    birthdate.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const today = new Date();
                        if (selectedDate > today) {
                            this.setCustomValidity('Birthdate cannot be in the future');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // Monthly income validation
                const income = document.querySelector('input[name="monthly_income"]');
                if (income) {
                    income.addEventListener('input', function() {
                        const val = parseFloat(this.value);
                        if (val < 0) {
                            this.setCustomValidity('Monthly income cannot be negative');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // Household members validation
                const household = document.querySelector('input[name="household_members"]');
                if (household) {
                    household.addEventListener('input', function() {
                        this.value = this.value.replace(/\D/g, '');
                        const raw = this.value;
                        if (!/^(?:[1-9]|1\d|20)$/.test(raw)) {
                            this.setCustomValidity('Household members must be between 1 and 20');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                // File size validation
                const fileInputs = addForm.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const fileSize = this.files[0].size / 1024 / 1024; // in MB
                            if (fileSize > 5) {
                                this.setCustomValidity('File size must not exceed 5MB');
                                this.classList.add('is-invalid');
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        }
                    });
                });
            }
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
            zoomImage.src = imageUrl;

            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        // Real-time validation for edit forms
        @if(auth('admin')->user()->hasPermission('update_indigency'))
            @foreach($indigency as $ind)
            (function(editId) {
                const editForm = document.getElementById('editIndigencyForm' + editId);
                if (editForm) {
                    // Purpose other field toggle
                    const purposeSelect = document.getElementById('edit_purpose_' + editId);
                    const otherField = document.getElementById('edit_otherPurposeField_' + editId);
                    if (purposeSelect && otherField) {
                        purposeSelect.addEventListener('change', function() {
                            if (this.value === 'other') {
                                otherField.style.display = 'block';
                            } else {
                                otherField.style.display = 'none';
                            }
                        });
                    }

                    // Contact number validation
                    const contact = document.getElementById('edit_contact_number_' + editId);
                    if (contact) {
                        contact.addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                            if (this.value.length > 11) {
                                this.value = this.value.slice(0, 11);
                            }
                            if (this.value.length > 0 && !this.value.startsWith('09')) {
                                this.setCustomValidity('Contact number must start with 09');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must start with 09';
                            }
                            else if (this.value.length > 0 && this.value.length !== 11) {
                                this.setCustomValidity('Contact number must be exactly 11 digits');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must be exactly 11 digits';
                            }
                            else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Birthdate validation
                    const birthdate = document.getElementById('edit_birthdate_' + editId);
                    if (birthdate) {
                        birthdate.addEventListener('change', function() {
                            const selectedDate = new Date(this.value);
                            const today = new Date();
                            if (selectedDate > today) {
                                this.setCustomValidity('Birthdate cannot be in the future');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Birthdate cannot be in the future';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Email validation
                    const email = document.getElementById('edit_email_' + editId);
                    if (email) {
                        email.addEventListener('input', function() {
                            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailPattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Please enter a valid email address');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Please enter a valid email address';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Monthly income validation
                    const income = document.getElementById('edit_monthly_income_' + editId);
                    if (income) {
                        income.addEventListener('input', function() {
                            const val = parseFloat(this.value);
                            if (val < 0) {
                                this.setCustomValidity('Monthly income cannot be negative');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Monthly income cannot be negative';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // Household members validation
                    const household = document.getElementById('edit_household_members_' + editId);
                    if (household) {
                        household.addEventListener('input', function() {
                            this.value = this.value.replace(/\D/g, '');
                            const raw = this.value;
                            if (!/^(?:[1-9]|1\d|20)$/.test(raw)) {
                                this.setCustomValidity('Household members must be between 1 and 20');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Household members must be between 1 and 20';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    // File size validation
                    const validId = document.getElementById('edit_valid_id_path_' + editId);
                    if (validId) {
                        validId.addEventListener('change', function() {
                            if (this.files && this.files[0]) {
                                const fileSize = this.files[0].size / 1024 / 1024; // in MB
                                if (fileSize > 5) {
                                    this.setCustomValidity('File size must not exceed 5MB');
                                    this.classList.add('is-invalid');
                                    
                                    let feedback = this.nextElementSibling;
                                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        this.parentNode.appendChild(feedback);
                                    }
                                    feedback.textContent = 'File size must not exceed 5MB';
                                } else {
                                    this.setCustomValidity('');
                                    this.classList.remove('is-invalid');
                                }
                            }
                        });
                    }

                    // Name validation (letters, spaces, hyphens only)
                    const firstName = document.getElementById('edit_first_name_' + editId);
                    if (firstName) {
                        firstName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('First name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'First name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const lastName = document.getElementById('edit_last_name_' + editId);
                    if (lastName) {
                        lastName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Last name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');
                                
                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Last name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }
                }
            })('{{ $ind->id }}');
            @endforeach
        @endif
    </script>

<script>
        async function loadApplicationHistory(appId) {
            const list = document.getElementById('historyList' + appId);
            if (!list) return;
            list.innerHTML = 'Loading history...';
            try {
                const params = new URLSearchParams({ request_type: 'indigency', request_id: String(appId) });
                const response = await fetch("{{ route('admin.notifications.remarksHistory') }}?" + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Failed to load history.');
                const payload = await response.json();
                const timeline = Array.isArray(payload.timeline) ? payload.timeline : [];
                if (timeline.length === 0) {
                    list.innerHTML = '<div class="text-muted">No activity found for this application yet.</div>';
                    return;
                }
                list.innerHTML = timeline.map(function(item) {
                    const details = item.details ? '<div class="text-muted mt-1">' + item.details + '</div>' : '';
                    const actor = item.actor ? '<div class="small text-muted">' + item.actor + ' � ' + (item.created_at || '') + '</div>' : '<div class="small text-muted">' + (item.created_at || '') + '</div>';
                    return '<div class="activity-item py-2 border-bottom"><div class="fw-semibold">' + (item.title || 'Activity') + '</div>' + actor + details + '</div>';
                }).join('');
            } catch (error) {
                list.innerHTML = '<div class="text-danger">Unable to load history right now.</div>';
            }
        }

        function switchViewTab(appId, tabName) {
            const tabButton = document.getElementById(tabName + '-tab-' + appId);
            if (!tabButton) return;
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }

        function toggleViewFooter(appId, activeTab) {
            const detailsFooter = document.getElementById('viewFooterDetails' + appId);
            const editFooter = document.getElementById('viewFooterEdit' + appId);
            const historyFooter = document.getElementById('viewFooterHistory' + appId);
            if (!detailsFooter || !editFooter || !historyFooter) return;
            detailsFooter.classList.add('d-none');
            editFooter.classList.add('d-none');
            historyFooter.classList.add('d-none');
            if (activeTab === 'details') detailsFooter.classList.remove('d-none');
            else if (activeTab === 'edit') editFooter.classList.remove('d-none');
            else historyFooter.classList.remove('d-none');
        }

        function toggleNotifyOptions(form, status) {
            const notifyWrap = form.querySelector('[data-notify-wrap]');
            if (!notifyWrap) return;

            const emailToggle = form.querySelector('input[name="notify_email"]');
            const smsToggle = form.querySelector('input[name="notify_sms"]');
            const hasEmail = notifyWrap.getAttribute('data-has-email') === '1';
            const hasSms = notifyWrap.getAttribute('data-has-sms') === '1';
            const allowNotify = ['approved', 'ready_pickup', 'rejected'].includes(String(status || '').toLowerCase());

            notifyWrap.classList.toggle('d-none', !allowNotify);

            if (emailToggle) {
                emailToggle.disabled = !allowNotify || !hasEmail;
                emailToggle.checked = allowNotify && hasEmail;
            }
            if (smsToggle) {
                smsToggle.disabled = !allowNotify || !hasSms;
                smsToggle.checked = allowNotify && hasSms;
            }
        }

        function handleUpdateStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-status-input');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = String(statusInput?.value || '').toLowerCase();
            const remarks = String(remarksInput?.value || '').trim();
            if (remarks.length > 40) {
                event.preventDefault();
                alert('Remarks must not exceed 40 characters.');
                return false;
            }
            if (status === 'rejected' && remarks === '') {
                event.preventDefault();
                alert('Remarks are required when status is Rejected.');
                return false;
            }
            return confirmDelete(event, 'Confirm status update?');
        }

        function prepareRemarksAndConfirm(event, form, confirmMessage) {
            const statusInput = form.querySelector('input[name="status"]');
            const remarksInput = form.querySelector('input[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';

            if ((status === 'rejected' || status === 'dropped') && remarksInput) {
                const remarks = prompt('Please enter remarks before sending this notification:');
                if (remarks === null) {
                    event.preventDefault();
                    return false;
                }
                const trimmedRemarks = remarks.trim();
                if (trimmedRemarks === '') {
                    event.preventDefault();
                    alert('Remarks are required for rejected or dropped notifications.');
                    return false;
                }
                if (trimmedRemarks.length > 40) {
                    event.preventDefault();
                    alert('Remarks must not exceed 40 characters.');
                    return false;
                }
                remarksInput.value = trimmedRemarks;
            }

            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-grid').forEach(function(grid) {
                const input = grid.closest('form')?.querySelector('.selected-status-input');
                const form = grid.closest('form');
                const current = grid.getAttribute('data-current-status') || '';
                function activateStatus(status) {
                    grid.querySelectorAll('.status-option').forEach(function(btn) {
                        btn.classList.toggle('active', btn.getAttribute('data-status') === status);
                    });
                    if (input) input.value = status;
                    if (form) toggleNotifyOptions(form, status);
                }
                grid.querySelectorAll('.status-option').forEach(function(button) {
                    button.addEventListener('click', function() {
                        activateStatus(button.getAttribute('data-status'));
                    });
                });
                activateStatus(current);
            });

            document.querySelectorAll('[id^="viewModal"]').forEach(function(modalEl) {
                const appId = modalEl.id.replace('viewModal', '');
                modalEl.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function() {
                        if (tabBtn.id.indexOf('details-tab-') === 0) toggleViewFooter(appId, 'details');
                        else if (tabBtn.id.indexOf('edit-tab-') === 0) toggleViewFooter(appId, 'edit');
                        else {
                            toggleViewFooter(appId, 'history');
                            loadApplicationHistory(appId);
                        }
                    });
                });
                modalEl.addEventListener('shown.bs.modal', function() {
                    toggleViewFooter(appId, 'details');
                });
            });

            document.querySelectorAll('[data-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const appId = button.getAttribute('data-app-id');
                    const tabName = button.getAttribute('data-switch-tab');
                    switchViewTab(appId, tabName);
                });
            });

            function setFieldValidity(field, message) {
                if (!field) return;
                let feedback = field.parentNode.querySelector('.invalid-feedback.dynamic-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback dynamic-feedback';
                    field.parentNode.appendChild(feedback);
                }

                field.setCustomValidity(message || '');
                if (message) {
                    field.classList.add('is-invalid');
                    feedback.textContent = message;
                } else {
                    field.classList.remove('is-invalid');
                    feedback.textContent = '';
                }
            }

            function attachQuickEditValidation(form) {
                const firstName = form.querySelector('input[name="first_name"]');
                const middleName = form.querySelector('input[name="middle_name"]');
                const lastName = form.querySelector('input[name="last_name"]');
                const birthdate = form.querySelector('input[name="birthdate"]');
                const email = form.querySelector('input[name="email"]');
                const contact = form.querySelector('input[name="contact_number"]');
                const address = form.querySelector('textarea[name="address"]');
                const household = form.querySelector('input[name="household_members"]');
                const purpose = form.querySelector('select[name="purpose"]');
                const purposeOther = form.querySelector('input[name="purpose_other"]');
                const fileInputs = form.querySelectorAll('input[type="file"]');

                const namePattern = /^[A-Za-z][A-Za-z .'-]*$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const allowedMime = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                const allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

                function validateName(field, label, required) {
                    if (!field) return true;
                    const val = field.value.trim();
                    if (!val && required) {
                        setFieldValidity(field, label + ' is required.');
                        return false;
                    }
                    if (!val && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (val.length < 2 || val.length > 50 || !namePattern.test(val)) {
                        setFieldValidity(field, label + ' must be 2-50 characters and contain letters only.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                }

                function validateBirthdate() {
                    if (!birthdate) return true;
                    if (!birthdate.value) {
                        setFieldValidity(birthdate, 'Birthdate is required.');
                        return false;
                    }
                    const selected = new Date(birthdate.value + 'T00:00:00');
                    const today = new Date();
                    if (selected > today) {
                        setFieldValidity(birthdate, 'Birthdate cannot be in the future.');
                        return false;
                    }
                    setFieldValidity(birthdate, '');
                    return true;
                }

                function validateEmail() {
                    if (!email) return true;
                    const val = email.value.trim();
                    if (!val) {
                        setFieldValidity(email, 'Email is required.');
                        return false;
                    }
                    if (!emailPattern.test(val)) {
                        setFieldValidity(email, 'Please enter a valid email address.');
                        return false;
                    }
                    setFieldValidity(email, '');
                    return true;
                }

                function validateContact() {
                    if (!contact) return true;
                    contact.value = contact.value.replace(/\D/g, '').slice(0, 11);
                    if (!contact.value) {
                        setFieldValidity(contact, 'Contact number is required.');
                        return false;
                    }
                    if (!/^09\d{9}$/.test(contact.value)) {
                        setFieldValidity(contact, 'Use format 09XXXXXXXXX.');
                        return false;
                    }
                    setFieldValidity(contact, '');
                    return true;
                }

                function validateAddress() {
                    if (!address) return true;
                    if (address.value.trim().length < 8) {
                        setFieldValidity(address, 'Address must be at least 8 characters.');
                        return false;
                    }
                    setFieldValidity(address, '');
                    return true;
                }

                function validateHousehold() {
                    if (!household) return true;
                    household.value = household.value.replace(/\D/g, '');
                    const raw = household.value;
                    const val = Number(raw);
                    if (!/^(?:[1-9]|1\d|20)$/.test(raw) || Number.isNaN(val)) {
                        setFieldValidity(household, 'Household members must be between 1 and 20.');
                        return false;
                    }
                    setFieldValidity(household, '');
                    return true;
                }

                function validatePurposeOther() {
                    if (!purpose || !purposeOther) return true;
                    if (purpose.value === 'other' && purposeOther.value.trim().length < 3) {
                        setFieldValidity(purposeOther, 'Specify at least 3 characters for other purpose.');
                        return false;
                    }
                    setFieldValidity(purposeOther, '');
                    return true;
                }

                function validateFileInput(fileInput) {
                    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                        setFieldValidity(fileInput, '');
                        return true;
                    }
                    const file = fileInput.files[0];
                    const fileSizeMb = file.size / (1024 * 1024);
                    const extension = (file.name.split('.').pop() || '').toLowerCase();
                    const mimeOk = file.type ? allowedMime.includes(file.type) : true;
                    const extOk = allowedExt.includes(extension);

                    if (!mimeOk || !extOk) {
                        setFieldValidity(fileInput, 'Only PDF, JPG, PNG, GIF, or WEBP files are allowed.');
                        return false;
                    }
                    if (fileSizeMb > 5) {
                        setFieldValidity(fileInput, 'File size must not exceed 5MB.');
                        return false;
                    }
                    setFieldValidity(fileInput, '');
                    return true;
                }

                firstName?.addEventListener('input', function() { validateName(firstName, 'First name', true); });
                middleName?.addEventListener('input', function() { validateName(middleName, 'Middle name', false); });
                lastName?.addEventListener('input', function() { validateName(lastName, 'Last name', true); });
                birthdate?.addEventListener('change', validateBirthdate);
                email?.addEventListener('input', validateEmail);
                contact?.addEventListener('input', validateContact);
                address?.addEventListener('input', validateAddress);
                household?.addEventListener('input', validateHousehold);
                purpose?.addEventListener('change', validatePurposeOther);
                purposeOther?.addEventListener('input', validatePurposeOther);
                fileInputs.forEach(function(fileInput) {
                    fileInput.addEventListener('change', function() {
                        validateFileInput(fileInput);
                    });
                });

                form.addEventListener('submit', function(event) {
                    const isValid = [
                        validateName(firstName, 'First name', true),
                        validateName(middleName, 'Middle name', false),
                        validateName(lastName, 'Last name', true),
                        validateBirthdate(),
                        validateEmail(),
                        validateContact(),
                        validateAddress(),
                        validateHousehold(),
                        validatePurposeOther(),
                        ...Array.from(fileInputs).map(validateFileInput)
                    ].every(Boolean);

                    if (!isValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            }

            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                attachQuickEditValidation(form);
            });

            var tableResponsive = document.querySelector('.table-responsive');
            if (!tableResponsive) return;

            tableResponsive.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(toggle) {
                new bootstrap.Dropdown(toggle, {
                    popperConfig: function(defaultConfig) {
                        defaultConfig.strategy = 'fixed';
                        return defaultConfig;
                    }
                });
            });
        });
    </script>
@endpush
