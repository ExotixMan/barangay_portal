@extends('admin.layouts.admin')

@section('title', 'Residents')
@section('page_title', 'Residents')
@section('page_icon', 'fas fa-users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/residency-applications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/residents.css') }}">

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
                    Reset
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
                    <div class="col-12 col-lg-4">
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
                    <div class="col-6 col-lg-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deactivated</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-2">
                        <select name="age_range" class="form-select">
                            <option value="">All Ages</option>
                            <option value="minor" {{ request('age_range') == 'minor' ? 'selected' : '' }}>Minor (&lt;18)</option>
                            <option value="adult" {{ request('age_range') == 'adult' ? 'selected' : '' }}>Adult (18-59)</option>
                            <option value="senior" {{ request('age_range') == 'senior' ? 'selected' : '' }}>Senior (60+)</option>
                        </select>
                    </div>
                    <div class="col-8 col-lg-2">
                        <select name="classification" class="form-select">
                            <option value="">All classifications</option>
                            <option value="student" {{ request('classification') === 'student' ? 'selected' : '' }}>Student · Verified</option>
                            <option value="student_pending" {{ request('classification') === 'student_pending' ? 'selected' : '' }}>Student · Pending</option>
                            <option value="pwd" {{ request('classification') === 'pwd' ? 'selected' : '' }}>PWD · Verified</option>
                            <option value="pwd_pending" {{ request('classification') === 'pwd_pending' ? 'selected' : '' }}>PWD · Pending</option>
                            <option value="senior" {{ request('classification') === 'senior' ? 'selected' : '' }}>Senior Citizen</option>
                        </select>
                    </div>
                    <div class="col-4 col-lg-2">
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
                    <button type="button" data-click-call="bulkDelete" class="btn btn-outline-danger d-flex align-items-center gap-2" title="Bulk Deactivate">
                        <i class="fas fa-trash-alt"></i>
                        <span class="d-none d-sm-inline">Bulk Deactivate</span>
                    </button>
                </form>
                @endif
                @admin_can('export_residents')
                <form id="exportForm" method="POST" action="{{ route('admin.residents.export') }}" style="display: inline;">
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
                                <input type="checkbox" id="selectAll" class="form-check-input table-checkbox" data-click-call="toggleSelectAll" aria-label="Select all residents">
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
                                            @php $residentProfilePhotoUrl = str_starts_with($resident->profile_photo, 'private/') ? route('admin.secure.file', ['path' => $resident->profile_photo]) : asset($resident->profile_photo); @endphp
                                            <img src="{{ $residentProfilePhotoUrl }}"
                                                 alt="{{ $resident->full_name }}"
                                                 class="resident-photo-trigger"
                                                 role="button"
                                                 tabindex="0"
                                                 title="View {{ $resident->full_name }} profile photo"
                                                 data-secure-image-viewer="{{ $residentProfilePhotoUrl }}"
                                                 data-secure-image-title="{{ $resident->full_name }} · Profile Photo"
                                                 data-error-action="remove">
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
                                    @if((int) $resident->age >= 60)
                                        <span class="badge resident-classification-badge is-senior"><i class="fas fa-person-cane me-1"></i>Senior</span>
                                    @endif
                                    @if($resident->is_student)
                                        <span class="badge resident-classification-badge is-student"><i class="fas fa-graduation-cap me-1"></i>Student Verified</span>
                                    @elseif(($resident->student_status ?? 'not_declared') === 'pending')
                                        <span class="badge resident-classification-badge is-pending"><i class="fas fa-graduation-cap me-1"></i>Student Pending</span>
                                    @endif
                                    @if($resident->is_pwd)
                                        <span class="badge resident-classification-badge is-pwd"><i class="fas fa-wheelchair me-1"></i>PWD Verified</span>
                                    @elseif(($resident->pwd_status ?? 'not_declared') === 'pending')
                                        <span class="badge resident-classification-badge is-pending"><i class="fas fa-wheelchair me-1"></i>PWD Pending</span>
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
                                                    <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" class="m-0" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Deactivate this resident account? Their record and transactions will remain archived.']) }}">
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
                                                    <form method="POST" action="{{ route('admin.residents.restore', $resident->id) }}" class="m-0" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this resident?']) }}">
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

                    <form method="POST" action="{{ route('admin.residents.store') }}" id="addResidentForm" class="modern-edit-form" enctype="multipart/form-data">
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
                                <span class="edit-section-icon"><i class="fas fa-user-tag"></i></span>
                                <div>
                                    <div class="edit-section-title">Resident Classification</div>
                                    <p class="edit-section-help">Student and PWD claims stay pending until evidence is reviewed. Senior Citizen status is automatic from birthdate at age 60+.</p>
                                </div>
                            </div>
                            <div class="edit-section-body">
                                <div class="classification-admin-choice-grid">
                                    <div class="classification-admin-choice">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="declare_student"
                                                   value="1"
                                                   id="add_declare_student"
                                                   {{ session('form_type') == 'add' && old('declare_student') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="add_declare_student">
                                                <strong><i class="fas fa-graduation-cap me-1"></i>Student</strong>
                                                <span>Resident claims they are currently enrolled.</span>
                                            </label>
                                        </div>

                                        <div class="classification-admin-proof {{ session('form_type') == 'add' && old('declare_student') ? '' : 'd-none' }}" id="addStudentProofPanel">
                                            <label for="add_student_proof" class="form-label">Student Proof <span class="text-danger">*</span></label>
                                            <input type="file"
                                                   class="form-control @if(session('form_type') == 'add') @error('student_proof') is-invalid @enderror @endif"
                                                   name="student_proof"
                                                   id="add_student_proof"
                                                   accept="image/jpeg,image/png,application/pdf">
                                            <small class="text-muted">Current school ID, registration form, or certificate of enrollment · max 5 MB.</small>
                                            @if(session('form_type') == 'add') @error('student_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                        </div>
                                    </div>

                                    <div class="classification-admin-choice">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="declare_pwd"
                                                   value="1"
                                                   id="add_declare_pwd"
                                                   {{ session('form_type') == 'add' && old('declare_pwd') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="add_declare_pwd">
                                                <strong><i class="fas fa-wheelchair me-1"></i>Person with Disability (PWD)</strong>
                                                <span>Official PWD ID card is required for verification.</span>
                                            </label>
                                        </div>

                                        <div class="classification-admin-proof {{ session('form_type') == 'add' && old('declare_pwd') ? '' : 'd-none' }}" id="addPwdProofPanel">
                                            <div class="edit-form-grid">
                                                <div class="edit-col-6">
                                                    <label for="add_pwd_id_front" class="form-label">PWD ID Front <span class="text-danger">*</span></label>
                                                    <input type="file"
                                                           class="form-control @if(session('form_type') == 'add') @error('pwd_id_front') is-invalid @enderror @endif"
                                                           name="pwd_id_front"
                                                           id="add_pwd_id_front"
                                                           accept="image/jpeg,image/png,application/pdf">
                                                    @if(session('form_type') == 'add') @error('pwd_id_front') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                                <div class="edit-col-6">
                                                    <label for="add_pwd_id_back" class="form-label">PWD ID Back <span class="text-danger">*</span></label>
                                                    <input type="file"
                                                           class="form-control @if(session('form_type') == 'add') @error('pwd_id_back') is-invalid @enderror @endif"
                                                           name="pwd_id_back"
                                                           id="add_pwd_id_back"
                                                           accept="image/jpeg,image/png,application/pdf">
                                                    @if(session('form_type') == 'add') @error('pwd_id_back') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                                </div>
                                            </div>
                                            <small class="text-muted">Both sides are required · max 5 MB each · front and back cannot be the same picture.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="classification-senior-note">
                                    <i class="fas fa-person-cane"></i>
                                    <div>
                                        <strong>Senior Citizen is automatic</strong>
                                        <span>Do not manually flag Senior status. The system uses the resident's birthdate and recognizes age 60+ automatically.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="edit-section-card">
                            <div class="edit-section-head">
                                <span class="edit-section-icon"><i class="fas fa-id-card-clip"></i></span>
                                <div>
                                    <div class="edit-section-title">Valid ID</div>
                                    <p class="edit-section-help">Upload clear front and back copies. The new resident's ID will be marked for verification after the account is created.</p>
                                </div>
                            </div>
                            <div class="edit-section-body">
                                <div class="edit-form-grid">
                                    <div class="edit-col-6">
                                        <label for="add_valid_id" class="form-label">Valid ID Front <span class="text-danger">*</span></label>
                                        <input type="file"
                                               class="form-control @if(session('form_type') == 'add') @error('valid_id') is-invalid @enderror @endif"
                                               name="valid_id"
                                               id="add_valid_id"
                                               accept="image/jpeg,image/png,application/pdf"
                                               required>
                                        <small class="text-muted">JPG, JPEG, PNG, or PDF. Maximum 5 MB.</small>
                                        @if(session('form_type') == 'add') @error('valid_id') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                    </div>
                                    <div class="edit-col-6">
                                        <label for="add_valid_id_back" class="form-label">Valid ID Back <span class="text-danger">*</span></label>
                                        <input type="file"
                                               class="form-control @if(session('form_type') == 'add') @error('valid_id_back') is-invalid @enderror @endif"
                                               name="valid_id_back"
                                               id="add_valid_id_back"
                                               accept="image/jpeg,image/png,application/pdf"
                                               required>
                                        <small class="text-muted">JPG, JPEG, PNG, or PDF. Maximum 5 MB.</small>
                                        @if(session('form_type') == 'add') @error('valid_id_back') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
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
                                        <input type="password" class="form-control @if(session('form_type') == 'add') @error('password') is-invalid @enderror @endif" name="password" id="add_password" required minlength="8" data-no-spaces>
                                        <small class="text-muted">Minimum 8 characters. No spaces allowed.</small>
                                        @if(session('form_type') == 'add') @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror @endif
                                    </div>
                                    <div class="edit-col-6">
                                        <label for="add_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" id="add_password_confirmation" required data-no-spaces>
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
                                                            <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" data-show-target="editResidentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-error-action="document-empty">
                                                            <div id="editResidentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-hide-self style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
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
                                    @php $residentProfilePhotoUrl = str_starts_with($resident->profile_photo, 'private/') ? route('admin.secure.file', ['path' => $resident->profile_photo]) : asset($resident->profile_photo); @endphp
                                    <img src="{{ $residentProfilePhotoUrl }}"
                                         alt="{{ $resident->full_name }}"
                                         class="resident-photo-trigger"
                                         role="button"
                                         tabindex="0"
                                         title="View full profile photo"
                                         data-secure-image-viewer="{{ $residentProfilePhotoUrl }}"
                                         data-secure-image-title="{{ $resident->full_name }} · Profile Photo"
                                         data-error-action="remove">
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
                                                                            <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" data-show-target="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-error-action="document-empty">
                                                                            <div id="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-hide-self style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
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

                                                            <form method="POST" action="{{ route('admin.residents.verifyId', $resident->id) }}"
                                                                id="approveIdForm{{ $resident->id }}"
                                                                data-submit-call="confirmApproveId" data-submit-pass="event,args" data-submit-args="{{ json_encode([$resident->full_name]) }}">
                                                                @csrf
                                                                <input type="hidden" name="notify_email" value="{{ $resident->email ? '1' : '0' }}">
                                                                <button type="submit" class="btn btn-success w-100">
                                                                    <i class="fas fa-check me-1"></i>Approve ID
                                                                </button>
                                                            </form>

                                                            <form
                                                                method="POST"
                                                                action="{{ route('admin.residents.rejectId', $resident->id) }}"
                                                                class="mt-3"
                                                                id="rejectIdForm{{ $resident->id }}"
                                                                data-submit-call="confirmRejectId" data-submit-pass="event,args" data-submit-args="{{ json_encode([$resident->full_name]) }}"
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

                                @php
                                    $adminPwdStatus = strtolower((string) ($resident->pwd_status ?? 'not_declared'));
                                    $adminStudentStatus = strtolower((string) ($resident->student_status ?? 'not_declared'));
                                    $adminClassificationLabel = function (string $status): string {
                                        return match ($status) {
                                            'verified' => 'Verified',
                                            'pending' => 'For verification',
                                            'rejected' => 'Rejected',
                                            default => 'Not declared',
                                        };
                                    };
                                    $adminClassificationBadge = function (string $status): string {
                                        return match ($status) {
                                            'verified' => 'bg-success-subtle text-success',
                                            'pending' => 'bg-warning-subtle text-warning',
                                            'rejected' => 'bg-danger-subtle text-danger',
                                            default => 'bg-secondary-subtle text-secondary',
                                        };
                                    };
                                @endphp

                                <div class="detail-section-card resident-classification-review-card">
                                    <div class="detail-section-title classification-review-title">
                                        <div>
                                            <i class="fas fa-user-tag"></i>
                                            Resident Classification
                                        </div>
                                        <span class="classification-review-caption">Evidence-based verification</span>
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="classification-status-strip">
                                            <div class="classification-status-item">
                                                <span class="classification-status-icon"><i class="fas fa-person-cane"></i></span>
                                                <div class="classification-status-copy">
                                                    <strong>Senior Citizen</strong>
                                                    <span>Automatic from birthdate · Age {{ $resident->age }}</span>
                                                </div>
                                                <span class="badge {{ (int) $resident->age >= 60 ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ (int) $resident->age >= 60 ? 'Qualified' : 'Not qualified' }}
                                                </span>
                                            </div>

                                            <div class="classification-status-item">
                                                <span class="classification-status-icon"><i class="fas fa-graduation-cap"></i></span>
                                                <div class="classification-status-copy">
                                                    <strong>Student</strong>
                                                    <span>School evidence</span>
                                                </div>
                                                <span class="badge {{ $adminClassificationBadge($adminStudentStatus) }}">{{ $adminClassificationLabel($adminStudentStatus) }}</span>
                                            </div>

                                            <div class="classification-status-item">
                                                <span class="classification-status-icon"><i class="fas fa-wheelchair"></i></span>
                                                <div class="classification-status-copy">
                                                    <strong>PWD</strong>
                                                    <span>Official PWD ID</span>
                                                </div>
                                                <span class="badge {{ $adminClassificationBadge($adminPwdStatus) }}">{{ $adminClassificationLabel($adminPwdStatus) }}</span>
                                            </div>
                                        </div>

                                        <div class="classification-review-list">
                                            @if($resident->student_proof || $adminStudentStatus !== 'not_declared')
                                            <section class="classification-review-panel">
                                                <div class="classification-review-panel-head">
                                                    <div class="classification-review-panel-title">
                                                        <span class="classification-review-panel-icon"><i class="fas fa-graduation-cap"></i></span>
                                                        <div>
                                                            <strong>Student Verification</strong>
                                                            <span>Review the submitted school proof before making a decision.</span>
                                                        </div>
                                                    </div>
                                                    <span class="badge {{ $adminClassificationBadge($adminStudentStatus) }}">{{ $adminClassificationLabel($adminStudentStatus) }}</span>
                                                </div>

                                                <div class="classification-review-panel-body">
                                                    @if($resident->student_proof)
                                                        @php
                                                            $studentExt = strtolower(pathinfo((string) $resident->student_proof, PATHINFO_EXTENSION));
                                                            $studentIsImage = in_array($studentExt, ['jpg','jpeg','png','webp']);
                                                            $studentProofUrl = route('admin.secure.file', ['path' => $resident->student_proof]);
                                                        @endphp
                                                        <div class="classification-review-evidence single">
                                                            <div class="classification-review-document">
                                                                <div class="classification-review-document-head">
                                                                    <strong><i class="fas fa-file-shield me-1"></i>Student Proof</strong>
                                                                    <span>{{ strtoupper($studentExt ?: 'FILE') }}</span>
                                                                </div>
                                                                @if($studentIsImage)
                                                                    <button type="button"
                                                                            class="classification-evidence-viewer classification-review-image"
                                                                            data-secure-image-viewer="{{ $studentProofUrl }}"
                                                                            data-secure-image-title="{{ $resident->full_name }} · Student Proof">
                                                                        <img src="{{ $studentProofUrl }}" alt="Student proof for {{ $resident->full_name }}">
                                                                        <span><i class="fas fa-magnifying-glass-plus"></i>Open full proof</span>
                                                                    </button>
                                                                @else
                                                                    <div class="classification-review-file">
                                                                        <i class="fas fa-file-pdf"></i>
                                                                        <div><strong>Student proof document</strong><span>Open the submitted document in a new tab.</span></div>
                                                                        <a href="{{ $studentProofUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-up-right-from-square me-1"></i>View file</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="classification-review-empty">
                                                            <i class="fas fa-file-circle-xmark"></i>
                                                            <div><strong>No Student proof submitted</strong><span>There is no evidence to review for this classification.</span></div>
                                                        </div>
                                                    @endif

                                                    @if($adminStudentStatus === 'rejected' && $resident->student_rejection_reason)
                                                        <div class="classification-review-remarks">
                                                            <i class="fas fa-message"></i>
                                                            <div><strong>Rejection remarks</strong><span>{{ $resident->student_rejection_reason }}</span></div>
                                                        </div>
                                                    @endif

                                                    @if(auth('admin')->user()->hasPermission('update_residents'))
                                                        @if($resident->student_proof && $adminStudentStatus === 'pending')
                                                            <div class="classification-review-actions valid-id-actions">
                                                                <form method="POST"
                                                                      action="{{ route('admin.residents.classification.update', [$resident->id, 'student', 'verified']) }}"
                                                                      data-submit-call="confirmApproveClassification"
                                                                      data-submit-pass="event,args"
                                                                      data-submit-args="{{ json_encode([$resident->full_name, 'Student']) }}">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-1"></i>Approve Student</button>
                                                                </form>
                                                                <form method="POST"
                                                                      action="{{ route('admin.residents.classification.update', [$resident->id, 'student', 'rejected']) }}"
                                                                      data-submit-call="confirmRejectClassification"
                                                                      data-submit-pass="event,args"
                                                                      data-submit-args="{{ json_encode([$resident->full_name, 'Student']) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="remarks" class="classification-rejection-input">
                                                                    <button type="submit" class="btn btn-outline-danger w-100"><i class="fas fa-xmark me-1"></i>Reject Student</button>
                                                                </form>
                                                            </div>
                                                        @elseif($adminStudentStatus === 'verified')
                                                            <div class="classification-review-state success"><i class="fas fa-circle-check"></i><span>Student classification has been verified. No action is needed.</span></div>
                                                        @elseif($adminStudentStatus === 'rejected')
                                                            <div class="classification-review-state neutral"><i class="fas fa-circle-info"></i><span>This Student claim was rejected. Wait for the resident to submit new evidence.</span></div>
                                                        @elseif(!$resident->student_proof)
                                                            <div class="classification-review-state neutral"><i class="fas fa-circle-info"></i><span>Approval is unavailable until the resident submits Student evidence.</span></div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </section>
                                            @endif

                                            @if($resident->pwd_id_front || $resident->pwd_id_back || $adminPwdStatus !== 'not_declared')
                                            <section class="classification-review-panel">
                                                <div class="classification-review-panel-head">
                                                    <div class="classification-review-panel-title">
                                                        <span class="classification-review-panel-icon"><i class="fas fa-wheelchair"></i></span>
                                                        <div>
                                                            <strong>PWD Verification</strong>
                                                            <span>Review both sides of the official PWD ID before making a decision.</span>
                                                        </div>
                                                    </div>
                                                    <span class="badge {{ $adminClassificationBadge($adminPwdStatus) }}">{{ $adminClassificationLabel($adminPwdStatus) }}</span>
                                                </div>

                                                <div class="classification-review-panel-body">
                                                    @if($resident->pwd_id_front || $resident->pwd_id_back)
                                                        <div class="classification-review-evidence">
                                                            @foreach([
                                                                ['path' => $resident->pwd_id_front, 'label' => 'PWD ID Front', 'icon' => 'fa-id-card'],
                                                                ['path' => $resident->pwd_id_back, 'label' => 'PWD ID Back', 'icon' => 'fa-id-card-clip'],
                                                            ] as $pwdDoc)
                                                                @php
                                                                    $pwdPath = $pwdDoc['path'];
                                                                    $pwdExt = strtolower(pathinfo((string) $pwdPath, PATHINFO_EXTENSION));
                                                                    $pwdIsImage = in_array($pwdExt, ['jpg','jpeg','png','webp']);
                                                                    $pwdUrl = $pwdPath ? route('admin.secure.file', ['path' => $pwdPath]) : null;
                                                                @endphp
                                                                <div class="classification-review-document">
                                                                    <div class="classification-review-document-head">
                                                                        <strong><i class="fas {{ $pwdDoc['icon'] }} me-1"></i>{{ $pwdDoc['label'] }}</strong>
                                                                        <span>{{ $pwdPath ? strtoupper($pwdExt ?: 'FILE') : 'Missing' }}</span>
                                                                    </div>
                                                                    @if($pwdPath)
                                                                        @if($pwdIsImage)
                                                                            <button type="button"
                                                                                    class="classification-evidence-viewer classification-review-image"
                                                                                    data-secure-image-viewer="{{ $pwdUrl }}"
                                                                                    data-secure-image-title="{{ $resident->full_name }} · {{ $pwdDoc['label'] }}">
                                                                                <img src="{{ $pwdUrl }}" alt="{{ $pwdDoc['label'] }} for {{ $resident->full_name }}">
                                                                                <span><i class="fas fa-magnifying-glass-plus"></i>Open full image</span>
                                                                            </button>
                                                                        @else
                                                                            <div class="classification-review-file compact">
                                                                                <i class="fas fa-file-pdf"></i>
                                                                                <a href="{{ $pwdUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">View file</a>
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <div class="classification-review-document-missing"><i class="fas fa-circle-info"></i><span>Not uploaded</span></div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="classification-review-empty">
                                                            <i class="fas fa-id-card"></i>
                                                            <div><strong>No PWD ID submitted</strong><span>The resident has not provided PWD evidence.</span></div>
                                                        </div>
                                                    @endif

                                                    @if($adminPwdStatus === 'rejected' && $resident->pwd_rejection_reason)
                                                        <div class="classification-review-remarks">
                                                            <i class="fas fa-message"></i>
                                                            <div><strong>Rejection remarks</strong><span>{{ $resident->pwd_rejection_reason }}</span></div>
                                                        </div>
                                                    @endif

                                                    @if(auth('admin')->user()->hasPermission('update_residents'))
                                                        @if($resident->pwd_id_front && $resident->pwd_id_back && $adminPwdStatus === 'pending')
                                                            <div class="classification-review-actions valid-id-actions">
                                                                <form method="POST"
                                                                      action="{{ route('admin.residents.classification.update', [$resident->id, 'pwd', 'verified']) }}"
                                                                      data-submit-call="confirmApproveClassification"
                                                                      data-submit-pass="event,args"
                                                                      data-submit-args="{{ json_encode([$resident->full_name, 'PWD']) }}">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-1"></i>Approve PWD</button>
                                                                </form>
                                                                <form method="POST"
                                                                      action="{{ route('admin.residents.classification.update', [$resident->id, 'pwd', 'rejected']) }}"
                                                                      data-submit-call="confirmRejectClassification"
                                                                      data-submit-pass="event,args"
                                                                      data-submit-args="{{ json_encode([$resident->full_name, 'PWD']) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="remarks" class="classification-rejection-input">
                                                                    <button type="submit" class="btn btn-outline-danger w-100"><i class="fas fa-xmark me-1"></i>Reject PWD</button>
                                                                </form>
                                                            </div>
                                                        @elseif($adminPwdStatus === 'verified')
                                                            <div class="classification-review-state success"><i class="fas fa-circle-check"></i><span>PWD classification has been verified. No action is needed.</span></div>
                                                        @elseif($adminPwdStatus === 'rejected')
                                                            <div class="classification-review-state neutral"><i class="fas fa-circle-info"></i><span>This PWD claim was rejected. Wait for the resident to submit new evidence.</span></div>
                                                        @elseif(!$resident->pwd_id_front || !$resident->pwd_id_back)
                                                            <div class="classification-review-state neutral"><i class="fas fa-circle-info"></i><span>Approval is unavailable until both sides of the PWD ID are submitted.</span></div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </section>
                                            @endif

                                            @if(!$resident->student_proof && !$resident->pwd_id_front && !$resident->pwd_id_back && $adminStudentStatus === 'not_declared' && $adminPwdStatus === 'not_declared')
                                                <div class="classification-review-empty classification-review-empty-wide">
                                                    <i class="fas fa-shield-halved"></i>
                                                    <div><strong>No classification evidence to review</strong><span>This resident has not declared Student or PWD status. Senior Citizen qualification remains automatic from birthdate.</span></div>
                                                </div>
                                            @endif
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
                                                                    <img src="{{ asset($path) }}" alt="{{ $doc['label'] }}" class="document-thumb" data-show-target="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-error-action="document-empty">
                                                                    <div id="residentDocFull{{ $resident->id }}_{{ $doc['field'] }}" data-hide-self style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
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

                            @if(auth('admin')->user()->hasPermission('update_residents'))
                            <div class="tab-pane fade edit-info-pane" id="r-edit-panel-{{ $resident->id }}" role="tabpanel">
                                <div class="edit-info-banner">
                                    <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="edit-info-title">Edit information for {{ $resident->full_name }}</div>
                                        <div class="edit-info-subtitle">ID #{{ $resident->id }} &middot; Update the resident's name, contact, and address quickly.</div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" id="quickEditResidentForm{{ $resident->id }}" class="quick-edit-form modern-edit-form" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $resident->id }}">

                                    @if(session('form_type') === 'edit_' . $resident->id && $errors->any())
                                        <div class="alert alert-danger mb-3" role="alert">
                                            <strong><i class="fas fa-triangle-exclamation me-1"></i>Please fix the following errors:</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

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
                                                    <input type="file" name="valid_id" class="form-control @if(session('form_type') === 'edit_' . $resident->id) @error('valid_id') is-invalid @enderror @endif" accept="image/jpeg,image/png,application/pdf">
                                                    <small class="text-muted">Leave empty to keep the current front.</small>
                                                    @if(session('form_type') === 'edit_' . $resident->id)
                                                        @error('valid_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    @endif
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label">Replace back ID</label>
                                                    <input type="file" name="valid_id_back" class="form-control @if(session('form_type') === 'edit_' . $resident->id) @error('valid_id_back') is-invalid @enderror @endif" accept="image/jpeg,image/png,application/pdf">
                                                    <small class="text-muted">Leave empty to keep the current back.</small>
                                                    @if(session('form_type') === 'edit_' . $resident->id)
                                                        @error('valid_id_back') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    @endif
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label" for="quick_edit_valid_id_status_{{ $resident->id }}">Verification status</label>
                                                    @php
                                                        $quickEditStatus = session('form_type') === 'edit_' . $resident->id
                                                            ? old('valid_id_status', '')
                                                            : '';
                                                    @endphp
                                                    <select name="valid_id_status"
                                                            id="quick_edit_valid_id_status_{{ $resident->id }}"
                                                            class="form-select @if(session('form_type') === 'edit_' . $resident->id) @error('valid_id_status') is-invalid @enderror @endif">
                                                        <option value="" {{ $quickEditStatus === '' ? 'selected' : '' }}>Keep current status</option>
                                                        <option value="for_verification" {{ $quickEditStatus === 'for_verification' ? 'selected' : '' }}>For verification</option>
                                                        <option value="verified" {{ $quickEditStatus === 'verified' ? 'selected' : '' }}>Approved / Verified</option>
                                                        <option value="rejected" {{ $quickEditStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                    @if(session('form_type') === 'edit_' . $resident->id)
                                                        @error('valid_id_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    @endif
                                                </div>
                                                <div class="edit-field edit-col-6">
                                                    <label class="form-label" for="quick_edit_valid_id_remarks_{{ $resident->id }}">
                                                        Rejection remarks <span class="text-danger d-none" data-valid-id-remarks-required>*</span>
                                                    </label>
                                                    <textarea name="valid_id_rejection_reason"
                                                              id="quick_edit_valid_id_remarks_{{ $resident->id }}"
                                                              class="form-control @if(session('form_type') === 'edit_' . $resident->id) @error('valid_id_rejection_reason') is-invalid @enderror @endif"
                                                              rows="2"
                                                              maxlength="500"
                                                              placeholder="Required only when rejecting.">{{ session('form_type') === 'edit_' . $resident->id ? old('valid_id_rejection_reason', '') : '' }}</textarea>
                                                    <div class="invalid-feedback" data-valid-id-remarks-feedback>
                                                        @if(session('form_type') === 'edit_' . $resident->id)
                                                            @error('valid_id_rejection_reason'){{ $message }}@enderror
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">Remarks are mandatory when the status is Rejected.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif

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
                                                <form method="POST" action="{{ route('admin.residents.update', $resident->id) }}" class="correction-resolve-form resident-correction-decision" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Save this decision and notify the resident by email?']) }}">
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

    <div class="modal fade resident-photo-viewer-modal" id="residentSecureImageViewerModal" tabindex="-1" aria-labelledby="residentSecureImageViewerTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div class="modal-kicker"><i class="fas fa-expand me-1"></i> Secure Image Viewer</div>
                        <h5 class="modal-title" id="residentSecureImageViewerTitle">Resident Image</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body resident-secure-image-stage">
                    <img id="residentSecureImageViewerImg" src="" alt="Resident document preview">
                </div>
                <div class="modal-footer">
                    <small class="text-muted me-auto"><i class="fas fa-shield-halved me-1"></i>Visible only to authenticated administrators with access to this page.</small>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
@include('shared.js-config', [
    'id' => 'js-config-admin-residents-main',
    'data' => [
        'errorFormType' => $errors->any() ? session('form_type') : null,
        'canUpdate' => auth('admin')->user()->hasPermission('update_residents'),
        'residentIds' => $residents->pluck('id')->values(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_resident-main.js') }}"></script>
@endpush
