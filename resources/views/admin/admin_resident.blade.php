@extends('admin.layouts.admin')

@section('title', 'Residents')
@section('page_title', 'Residents')
@section('page_icon', 'fas fa-users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/residents.css') }}">
@endpush

@section('hero_actions')
    @admin_can('create_residents')
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResidentModal">
            <i class="fas fa-plus"></i>
            <span>Add Resident</span>
        </a>
    @endadmin_can

    @admin_can('export_residents')
        <button type="button" onclick="exportCSV()" class="btn btn-light-soft">
            <i class="fas fa-file-csv"></i>
            <span>Export CSV</span>
        </button>
    @endadmin_can
@endsection

@section('content')
    @include('admin.layouts.alerts')


    @include('admin.layouts.page-hero', [
        'icon' => 'fas fa-users',
        'kicker' => 'Resident registry',
        'heading' => 'Manage resident profiles, account status, valid IDs, and contact records in one clean workspace.',
        'description' => 'Search, verify, update, and manage resident profiles with the same clean workflow used in residency applications.',
        'meta' => [
            ['icon' => 'fas fa-users', 'label' => 'Total', 'value' => number_format($totalResidents)],
            ['icon' => 'fas fa-user-check', 'label' => 'Active', 'value' => number_format($activeResidents)],
            ['icon' => 'fas fa-user-plus', 'label' => 'New', 'value' => number_format($newResidents)],
            ['icon' => 'fas fa-user-shield', 'label' => 'Seniors', 'value' => number_format($seniorResidents)],
        ],
    ])


                <!-- Statistics -->
    <div class="admin-stats">
        @include('admin.layouts.stat-card', [
            'label' => 'Total',
            'value' => number_format($totalResidents),
            'helper' => ($growthPercentage >= 0 ? '+' : '') . round($growthPercentage, 1) . '% from last month',
            'helperIcon' => $growthPercentage >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down',
            'helperClass' => $growthPercentage >= 0 ? 'text-success' : 'text-danger',
            'icon' => 'fas fa-users',
        ])

        @include('admin.layouts.stat-card', [
            'label' => 'Active',
            'value' => number_format($activeResidents),
            'helper' => round($activePercentage, 1) . '% active',
            'helperIcon' => 'fas fa-check-circle',
            'helperClass' => 'text-success',
            'icon' => 'fas fa-user-check',
            'tone' => 'success',
        ])

        @include('admin.layouts.stat-card', [
            'label' => 'New',
            'value' => number_format($newResidents),
            'helper' => '+' . number_format($yesterdayCount) . ' from yesterday',
            'helperIcon' => 'fas fa-calendar',
            'helperClass' => 'text-info',
            'icon' => 'fas fa-user-plus',
            'tone' => 'info',
        ])

        @include('admin.layouts.stat-card', [
            'label' => 'Senior',
            'value' => number_format($seniorResidents),
            'helper' => round($seniorPercentage, 1) . '% of total',
            'helperIcon' => 'fas fa-heart',
            'helperClass' => 'text-warning',
            'icon' => 'fas fa-user',
            'tone' => 'warning',
        ])
    </div>

    <!-- Filters - Mobile Responsive -->
                <div class="card border-0 mb-4 admin-filter-card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.residents.index') }}" id="searchForm">
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-12 col-md-6">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="fas fa-filter me-2 text-primary"></i>
                                        <span>Filter Residents</span>
                                    </h6>
                                    <p class="text-muted small mb-0 mt-2">Find residents by name, username, email, status, or age group.</p>
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
                        
                            <!-- Search and Filter Row -->
                            <div class="row g-3">
                                <div class="col-12 col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-@.]/g,'')" id="globalSearch" class="form-control border-start-0 ps-0" placeholder="Search residents..." value="{{ request('search') }}">
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

                        <!-- Bulk Actions -->
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

                <!-- Residents Table - Residency Style Compact View -->
                <div class="card border-0 admin-table-card">
                    <div class="card-body p-0">
                        <div class="admin-table-head">
                            <div class="table-head-title">
                                <span class="table-head-icon"><i class="fas fa-users-viewfinder"></i></span>
                                <div class="min-w-0">
                                    <span class="table-kicker"><i class="fas fa-list-check"></i> Records table</span>
                                    <h3>Resident directory</h3>
                                    <p>Compact view for resident identity, contact details, verification state, and account actions.</p>
                                </div>
                            </div>
                            <span class="records-count-badge"><i class="fas fa-database"></i>{{ $residents->total() }} records</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="residentsTable">
                                <thead>
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
                                        <th>Contact Details</th>
                                        <th>Age / Address</th>
                                        <th>Status / Verification</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($residents as $resident)
                                    <tr class="resident-row {{ $resident->deleted_at ? 'is-deactivated' : '' }}">
                                        <td class="ps-4">
                                            <input type="checkbox" value="{{ $resident->id }}" class="form-check-input table-checkbox resident-checkbox" aria-label="Select {{ $resident->full_name }}">
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
                                                <span class="info-main">
                                                    {{ $resident->age }} years old
                                                </span>

                                                <div class="info-sub resident-address"
                                                    title="{{ $resident->address ?? 'N/A' }}">
                                                    <i class="fas fa-location-dot" aria-hidden="true"></i>

                                                    <span class="resident-address-text">
                                                        {{ $resident->address ?? 'N/A' }}
                                                    </span>
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
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="action-buttons" aria-label="Actions for {{ $resident->full_name }}">
                                                <button type="button"
                                                        class="btn btn-sm action-btn action-view"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewResidentModal{{ $resident->id }}"
                                                        title="View resident details"
                                                        aria-label="View details for {{ $resident->full_name }}">
                                                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                                    <span class="action-label">View</span>
                                                </button>

                                                @if(!$resident->deleted_at && auth('admin')->user()->hasPermission('update_residents'))
                                                    <button type="button"
                                                            class="btn btn-sm action-btn action-update"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editResidentModal{{ $resident->id }}"
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
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end resident-action-menu" aria-labelledby="residentActions{{ $resident->id }}">
                                                        <li>
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activityModal{{ $resident->id }}">
                                                                <span class="dropdown-item-icon is-info"><i class="fas fa-clock-rotate-left" aria-hidden="true"></i></span>
                                                                <span>
                                                                    <strong>Activity log</strong>
                                                                    <small>Review recent account activity</small>
                                                                </span>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileCorrectionModal{{ $resident->id }}">
                                                                <span class="dropdown-item-icon is-neutral"><i class="fas fa-user-pen" aria-hidden="true"></i></span>
                                                                <span>
                                                                    <strong>Profile corrections</strong>
                                                                    <small>Review submitted concerns</small>
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

                <!-- Add Resident Modal with Residency-style Design -->
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
                                                    <input type="date" class="form-control @if(session('form_type') == 'add') @error('birthdate') is-invalid @enderror @endif" name="birthdate" id="add_birthdate" value="{{ session('form_type') == 'add' ? old('birthdate') : '' }}" required max="{{ now()->subYears(18)->format('Y-m-d') }}">
                                                    <small class="text-muted">Resident must be 18 years old or above.</small>
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

                <!-- Activity Log Modals - Residency-style Timeline -->
                @foreach($residents as $resident)
                <div class="modal fade" id="activityModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-history"></i>
                                    Activity Log
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="edit-info-banner">
                                    <div class="application-avatar">{{ strtoupper(substr($resident->firstname ?? 'R', 0, 1)) }}{{ strtoupper(substr($resident->lastname ?? '', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="edit-info-title">{{ $resident->full_name }}</div>
                                        <div class="edit-info-subtitle">Recent changes and account activity for this resident record.</div>
                                    </div>
                                </div>

                                <div class="detail-section-card mb-0">
                                    <div class="detail-section-head">
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

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Profile Correction Concern Modals -->
                @foreach($residents as $resident)
                    @php
                        $profileCorrectionConcerns = $resident->profileCorrectionConcerns
                            ->sortByDesc('created_at')
                            ->values();
                        $pendingCorrectionCount = $profileCorrectionConcerns
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    <div class="modal fade" id="profileCorrectionModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-pen"></i>
                                        Profile Correction Concerns
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
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

                                    @forelse($profileCorrectionConcerns as $concern)
                                        @php
                                            $concernStatus = strtolower((string) $concern->status);
                                            $concernBadgeClass = match($concernStatus) {
                                                'approved', 'resolved', 'completed' => 'bg-success-subtle text-success',
                                                'rejected', 'declined' => 'bg-danger-subtle text-danger',
                                                'under_review', 'in_review', 'reviewing' => 'bg-info-subtle text-info',
                                                default => 'bg-warning-subtle text-warning',
                                            };
                                            $concernStatusLabel = str($concernStatus ?: 'pending')->replace('_', ' ')->title();
                                        @endphp

                                        <div class="detail-section-card {{ !$loop->last ? 'mb-3' : 'mb-0' }}">
                                            <div class="detail-section-head">
                                                <span class="detail-section-icon"><i class="fas fa-file-pen"></i></span>
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="detail-section-title">{{ $concern->formatted_id }}</div>
                                                        <span class="badge {{ $concernBadgeClass }}">{{ $concernStatusLabel }}</span>
                                                    </div>
                                                    <p class="detail-section-help mb-0">
                                                        Submitted {{ optional($concern->created_at)->format('F d, Y h:i A') ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="detail-section-body">
                                                <div class="detail-grid">
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="fas fa-layer-group"></i>Service</div>
                                                        <div class="detail-value">{{ $concern->service ?: 'Resident Profile' }}</div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="fas fa-pen-to-square"></i>Field</div>
                                                        <div class="detail-value">{{ $concern->field_name }}</div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="fas fa-clock-rotate-left"></i>Current Value</div>
                                                        <div class="detail-value text-break">{{ $concern->current_value ?: 'Not provided' }}</div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="fas fa-circle-check"></i>Requested Correction</div>
                                                        <div class="detail-value text-break">{{ $concern->correct_value }}</div>
                                                    </div>
                                                    <div class="detail-item is-wide">
                                                        <div class="detail-label"><i class="fas fa-message"></i>Resident Message</div>
                                                        <div class="detail-value text-break">{{ $concern->message ?: 'No additional message.' }}</div>
                                                    </div>
                                                    <div class="detail-item is-wide">
                                                        <div class="detail-label"><i class="fas fa-user-check"></i>Review Information</div>
                                                        <div class="detail-value">
                                                            @if($concern->reviewed_at)
                                                                Reviewed {{ $concern->reviewed_at->format('F d, Y h:i A') }}
                                                            @else
                                                                Not reviewed yet
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="document-empty">
                                            <span><i class="fas fa-circle-info me-1"></i>No profile correction concern has been submitted by this resident.</span>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

               <!-- Edit Resident Modals with Residency-style Design -->
                @if(auth('admin')->user()->hasPermission('update_residents'))
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
                                            <div class="edit-info-subtitle">Update resident identity, contact, and address information using the same form pattern as residency applications.</div>
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
                                                    $editIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : (($resident->valid_id || $resident->valid_id_back) ? 'for_verification' : 'for_verification'));
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

                <!-- View Resident Modals - Residency-style Details -->
                @foreach($residents as $resident)
                <div class="modal fade" id="viewResidentModal{{ $resident->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fa-solid fa-user"></i>
                                    Resident Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="application-modal-summary">
                                    <div class="modal-summary-card is-primary">
                                        <span class="summary-label">Resident</span>
                                        <span class="summary-value">{{ $resident->full_name }}</span>
                                        <span class="summary-sub"><i class="fas fa-user-tag me-1"></i>{{ $resident->username }} · ID #{{ $resident->id }}</span>
                                    </div>
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Age</span>
                                        <span class="summary-value">{{ $resident->age }}</span>
                                        <span class="summary-sub">years old</span>
                                    </div>
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Account</span>
                                        <span class="summary-value">{{ $resident->deleted_at ? 'Deactivated' : 'Active' }}</span>
                                        <span class="summary-sub">Current status</span>
                                    </div>
                                    @php
                                        $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back);
                                        $viewIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                        $viewIdStatusLabel = match($viewIdStatus) {
                                            'verified' => 'Verified',
                                            'rejected' => 'Rejected',
                                            'for_verification' => 'For Review',
                                            default => 'None',
                                        };
                                    @endphp
                                    <div class="modal-summary-card">
                                        <span class="summary-label">Valid ID</span>
                                        <span class="summary-value">{{ $viewIdStatusLabel }}</span>
                                        <span class="summary-sub">Identity check</span>
                                    </div>
                                </div>

                                <div class="detail-section-card">
                                    <div class="detail-section-head">
                                        <span class="detail-section-icon"><i class="fas fa-id-card"></i></span>
                                        <div>
                                            <div class="detail-section-title">Personal Information</div>
                                            <p class="detail-section-help">Basic resident identity details.</p>
                                        </div>
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-user"></i>First Name</div>
                                                <div class="detail-value">{{ $resident->firstname }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-user"></i>Middle Name</div>
                                                <div class="detail-value">{{ $resident->middlename ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-user"></i>Last Name</div>
                                                <div class="detail-value">{{ $resident->lastname }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-tag"></i>Suffix</div>
                                                <div class="detail-value">{{ $resident->suffix ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-cake-candles"></i>Birthdate</div>
                                                <div class="detail-value">{{ $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-calendar"></i>Age</div>
                                                <div class="detail-value">{{ $resident->age }} years old</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-venus-mars"></i>Gender</div>
                                                <div class="detail-value">{{ $resident->gender ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-ring"></i>Civil Status</div>
                                                <div class="detail-value">{{ $resident->civil_status ?: 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item is-wide">
                                                <div class="detail-label"><i class="fas fa-location-dot"></i>Place of Birth</div>
                                                <div class="detail-value">{{ $resident->place_of_birth ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section-card">
                                    <div class="detail-section-head">
                                        <span class="detail-section-icon"><i class="fas fa-address-book"></i></span>
                                        <div>
                                            <div class="detail-section-title">Contact Information</div>
                                            <p class="detail-section-help">Communication and address details.</p>
                                        </div>
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-envelope"></i>Email</div>
                                                <div class="detail-value">{{ $resident->email ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="fas fa-phone"></i>Contact Number</div>
                                                <div class="detail-value">{{ $resident->contact ?? 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item is-wide">
                                                <div class="detail-label"><i class="fas fa-map-marker-alt"></i>Complete Address</div>
                                                <div class="detail-value">{{ $resident->address ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section-card">
                                    <div class="detail-section-head">
                                        <span class="detail-section-icon"><i class="fas fa-id-card-clip"></i></span>
                                        <div>
                                            <div class="detail-section-title">Profile Photo & Valid ID Review</div>
                                            <p class="detail-section-help">Uploaded identity assets, approval status, rejection remarks, and admin decision controls.</p>
                                        </div>
                                    </div>
                                    <div class="detail-section-body">
                                        @php
                                            $hasAnyValidId = !empty($resident->valid_id) || !empty($resident->valid_id_back);
                                            $viewIdStatus = $resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : ($hasAnyValidId ? 'for_verification' : 'missing'));
                                            $viewDocuments = [
                                                ['field' => 'profile_photo', 'label' => 'Profile Photo', 'icon' => 'fa-user-circle'],
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

                                        <div class="detail-section-card mb-0 mt-3">
                                            <div class="detail-section-head">
                                                <span class="detail-section-icon"><i class="fas fa-user-shield"></i></span>
                                                <div>
                                                    <div class="detail-section-title">Verification Decision</div>
                                                    <p class="detail-section-help">Approve only if the front and back ID images are clear and match the resident record.</p>
                                                </div>
                                            </div>
                                            <div class="detail-section-body">
                                                <div class="detail-grid">
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="fas fa-clipboard-check"></i>Current ID Status</div>
                                                        <div class="detail-value">
                                                            @if($viewIdStatus === 'verified')
                                                                <span class="badge bg-success-subtle text-success"><i class="fas fa-check me-1"></i>Verified</span>
                                                            @elseif($viewIdStatus === 'rejected')
                                                                <span class="badge bg-danger-subtle text-danger"><i class="fas fa-xmark me-1"></i>Rejected</span>
                                                            @elseif($viewIdStatus === 'for_verification')
                                                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-hourglass-half me-1"></i>For verification</span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary"><i class="fas fa-circle-info me-1"></i>No valid ID</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label"><i class="far fa-calendar-check"></i>Verified At</div>
                                                        <div class="detail-value">{{ $resident->valid_id_verified_at ? $resident->valid_id_verified_at->format('F d, Y h:i A') : 'Not verified yet' }}</div>
                                                    </div>
                                                    <div class="detail-item is-wide">
                                                        <div class="detail-label"><i class="fas fa-message"></i>Remarks</div>
                                                        <div class="detail-value">{{ $resident->valid_id_rejection_reason ?: 'No remarks.' }}</div>
                                                    </div>
                                                </div>

                                                @if(auth('admin')->user()->hasPermission('update_residents'))
                                                    @if(!empty($resident->valid_id) || !empty($resident->valid_id_back))
                                                        <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                                                            <form method="POST" action="{{ route('admin.residents.verifyId', $resident->id) }}" class="flex-fill" onsubmit="return confirm('Approve and verify the valid ID for {{ addslashes($resident->full_name) }}?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success w-100">
                                                                    <i class="fas fa-check me-1"></i>Approve ID
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <form method="POST" action="{{ route('admin.residents.rejectId', $resident->id) }}" class="mt-3" onsubmit="return confirm('Reject the valid ID for {{ addslashes($resident->full_name) }}?')">
                                                            @csrf
                                                            <label class="form-label">Reject Remarks <span class="text-danger">*</span></label>
                                                            <textarea name="valid_id_rejection_reason" class="form-control @error('valid_id_rejection_reason') is-invalid @enderror" rows="3" maxlength="500" required placeholder="Example: Back ID is blurry, photo does not match, expired ID, or details are unreadable.">{{ old('valid_id_rejection_reason') }}</textarea>
                                                            @error('valid_id_rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            <button type="submit" class="btn btn-outline-danger w-100 mt-2">
                                                                <i class="fas fa-xmark me-1"></i>Reject ID
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="alert alert-danger mb-0 mt-3">
                                                            <i class="fas fa-triangle-exclamation me-1"></i>No valid ID image is uploaded yet, so this resident cannot be approved.
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section-card mb-0">
                                    <div class="detail-section-head">
                                        <span class="detail-section-icon"><i class="fas fa-cog"></i></span>
                                        <div>
                                            <div class="detail-section-title">Account Information</div>
                                            <p class="detail-section-help">Record timestamps and active/deactivated status.</p>
                                        </div>
                                    </div>
                                    <div class="detail-section-body">
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="far fa-calendar-alt"></i>Registered At</div>
                                                <div class="detail-value">{{ $resident->created_at ? $resident->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label"><i class="far fa-clock"></i>Last Updated</div>
                                                <div class="detail-value">{{ $resident->updated_at ? $resident->updated_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                            </div>
                                            <div class="detail-item is-wide">
                                                <div class="detail-label"><i class="fas fa-circle-check"></i>Status</div>
                                                <div class="detail-value">
                                                    @if(!$resident->deleted_at)
                                                        <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-times-circle me-1"></i>Deactivated</span>
                                                        <small class="text-muted d-block mt-1">Deactivated at: {{ $resident->deleted_at->format('F d, Y h:i A') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
@endsection

@push('scripts')
<script>
// Bulk Archive function
        function bulkDelete() {

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one resident to deactivate.',
                    confirmButtonColor: '#d33'
                });

                return;
            }

            // SweetAlert Confirmation
            Swal.fire({
                title: 'Confirm Bulk Deactivate',
                text: `Are you sure you want to deactivate ${checkboxes.length} selected resident(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Deactivate'
            }).then((result) => {

                if (result.isConfirmed) {

                    // Attach selected IDs
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkForm.appendChild(input);
                    });

                    bulkForm.submit();
                }

            });
        }

        // Export CSV function
        function exportCSV() {

            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            // If nothing selected → Ask to export all
            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No residents selected. Export all records?',
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
                text: `Export ${checkboxes.length} selected resident(s)?`,
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
            const checkboxes = document.querySelectorAll('.resident-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                checkbox.closest('tr')?.classList.toggle('is-selected', checkbox.checked);
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

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swal if not loaded
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return confirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.resident-checkbox');
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

            // Show appropriate modal if there are validation errors
            @if ($errors->any() && session('form_type'))
                @if(session('form_type') == 'add')
                    var addModal = new bootstrap.Modal(document.getElementById('addResidentModal'));
                    addModal.show();
                @elseif(Str::startsWith(session('form_type'), 'edit_'))
                    var editId = '{{ session('form_type') }}'.replace('edit_', '');
                    var editModal = new bootstrap.Modal(document.getElementById('editResidentModal' + editId));
                    editModal.show();
                @endif
            @endif
        });

        // Auto-submit search after typing (optional)
        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
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
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
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

            // Clear any server-side is-invalid marks as soon as the user starts typing
            addForm.querySelectorAll('.is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }, { once: true });
            });

            // First name
            const fn = document.getElementById('add_firstname');
            if (fn) fn.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'First name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'First name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'First name must be at least 2 characters.');
                setField(this, '');
            });

            // Last name
            const ln = document.getElementById('add_lastname');
            if (ln) ln.addEventListener('input', function() {
                if (!this.value.trim())              return setField(this, 'Last name is required.');
                if (!namePattern.test(this.value))   return setField(this, 'Last name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2)    return setField(this, 'Last name must be at least 2 characters.');
                setField(this, '');
            });

            // Username
            const un = document.getElementById('add_username');
            if (un) un.addEventListener('input', function() {
                if (!this.value)                          return setField(this, 'Username is required.');
                if (!usernamePattern.test(this.value))    return setField(this, 'Username can only contain letters, numbers, and underscores.');
                if (this.value.length < 3)                return setField(this, 'Username must be at least 3 characters.');
                setField(this, '');
            });

            // Email
            const em = document.getElementById('add_email');
            if (em) em.addEventListener('input', function() {
                if (!this.value)                        return setField(this, 'Email is required.');
                if (!emailPattern.test(this.value))     return setField(this, 'Please enter a valid email address.');
                setField(this, '');
            });

            // Contact
            const contact = document.getElementById('add_contact');
            if (contact) contact.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                if (!this.value)                        return setField(this, 'Contact number is required.');
                if (!this.value.startsWith('09'))       return setField(this, 'Contact number must start with 09.');
                if (this.value.length !== 11)           return setField(this, 'Contact number must be exactly 11 digits.');
                setField(this, '');
            });

            // Birthdate
            const bd = document.getElementById('add_birthdate');
            if (bd) bd.addEventListener('change', function() {
                const sel = new Date(this.value);
                const today = new Date(); today.setHours(0,0,0,0);
                const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                const age = calculateAge(this.value);
                if (sel >= today)    return setField(this, 'Birthdate must be in the past.');
                if (sel < minDate)   return setField(this, 'Please enter a valid birthdate.');
                if (age === null || age < 18) return setField(this, 'Resident must be 18 years old or above.');
                setField(this, '');
            });

            // Password strength
            const pw  = document.getElementById('add_password');
            const pwc = document.getElementById('add_password_confirmation');

            function validatePw() {
                // Remove spaces from password
                pw.value = pw.value.replace(/\s/g, "");
                if (!pw.value)                          return setField(pw, 'Password is required.');
                if (pw.value.length < 8)                return setField(pw, 'Password must be at least 8 characters.');
                if (!/[A-Z]/.test(pw.value))            return setField(pw, 'Password must contain at least one uppercase letter.');
                if (!/[a-z]/.test(pw.value))            return setField(pw, 'Password must contain at least one lowercase letter.');
                if (!/[0-9]/.test(pw.value))            return setField(pw, 'Password must contain at least one number.');
                if (!/[^A-Za-z0-9]/.test(pw.value))    return setField(pw, 'Password must contain at least one special character.');
                setField(pw, '');
                if (pwc.value) validatePwc();
            }
            function validatePwc() {
                // Remove spaces from confirm password
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
        // Real-time validation for edit forms
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

                // Clear server-side errors on first keystroke
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
                    if (!this.value.trim())            return sf(this, 'First name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'First name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'First name must be at least 2 characters.');
                    sf(this, '');
                });

                const ln = document.getElementById('edit_lastname_' + id);
                if (ln) ln.addEventListener('input', function() {
                    if (!this.value.trim())            return sf(this, 'Last name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'Last name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2)  return sf(this, 'Last name must be at least 2 characters.');
                    sf(this, '');
                });

                const un = document.getElementById('edit_username_' + id);
                if (un) un.addEventListener('input', function() {
                    if (!this.value)                        return sf(this, 'Username is required.');
                    if (!usernamePattern.test(this.value))  return sf(this, 'Username can only contain letters, numbers, and underscores.');
                    if (this.value.length < 3)              return sf(this, 'Username must be at least 3 characters.');
                    sf(this, '');
                });

                const em = document.getElementById('edit_email_' + id);
                if (em) em.addEventListener('input', function() {
                    if (!this.value)                    return sf(this, 'Email is required.');
                    if (!emailPattern.test(this.value)) return sf(this, 'Please enter a valid email address.');
                    sf(this, '');
                });

                const ct = document.getElementById('edit_contact_' + id);
                if (ct) ct.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (!this.value)                  return sf(this, 'Contact number is required.');
                    if (!this.value.startsWith('09')) return sf(this, 'Contact number must start with 09.');
                    if (this.value.length !== 11)     return sf(this, 'Contact number must be exactly 11 digits.');
                    sf(this, '');
                });

                const bd = document.getElementById('edit_birthdate_' + id);
                if (bd) bd.addEventListener('change', function() {
                    const sel = new Date(this.value);
                    const today = new Date(); today.setHours(0,0,0,0);
                    const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                    if (sel >= today)   return sf(this, 'Birthdate must be in the past.');
                    if (sel < minDate)  return sf(this, 'Please enter a valid birthdate.');
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
