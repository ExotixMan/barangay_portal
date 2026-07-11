@extends('admin.layouts.admin')

@section('title', 'Project Management')
@section('page_title', 'Project Management')
@section('page_icon', 'fas fa-project-diagram')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/projects.css') }}">
@endpush

@section('content')
            <div class="projects-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show projects-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="projects-hero">
                <div class="projects-hero-content">
                    <div class="min-w-0">
                        <div class="projects-hero-icon"><i class="fas fa-project-diagram"></i></div>
                        <div class="projects-kicker">Project Control Center</div>
                        <h2>Track community projects with a cleaner workflow.</h2>
                        <p>Review project status, filter by progress, update milestones, and archive completed records without cluttering the page.</p>
                    </div>
                    <div class="projects-hero-actions">
                        @admin_can('create_projects')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                            <i class="fas fa-plus"></i>Add Project
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 projects-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-project-diagram me-1"></i>All projects
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Ongoing</div>
                            <div class="stat-number">{{ $ongoing_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-hourglass me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-hourglass"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Completed</div>
                            <div class="stat-number">{{ $completed_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-check-circle me-1"></i>Finished
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
                            <div class="stat-label text-muted mb-1">Avg Progress</div>
                            <div class="stat-number">{{ $avg_progress ?? 0 }}%</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-chart-line me-1"></i>Overall
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - UX Improved -->
            <div class="projects-panel projects-filter-panel">
                <form method="GET" action="{{ route('admin.projects.index') }}" id="searchForm">
                    <div class="projects-panel-header">
                        <div class="projects-panel-title">
                            <div class="projects-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-hard-hat"></i> Project Directory</div>
                                <h3>Search and filter projects</h3>
                                <p class="panel-copy">Find projects by title, description, or location. Use status and progress filters to narrow the table.</p>
                            </div>
                        </div>

                        <div class="projects-panel-actions">
                            @admin_can('create_projects')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                <i class="fas fa-plus"></i>Add Project
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="projects-filter-body">
                        <div class="projects-filter-toolbar">
                            <div class="projects-search-group">
                                <div class="projects-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search projects">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="progress" class="form-select" aria-label="Filter by progress">
                                <option value="">All progress</option>
                                <option value="0-25" {{ request('progress') == '0-25' ? 'selected' : '' }}>0-25%</option>
                                <option value="26-50" {{ request('progress') == '26-50' ? 'selected' : '' }}>26-50%</option>
                                <option value="51-75" {{ request('progress') == '51-75' ? 'selected' : '' }}>51-75%</option>
                                <option value="76-100" {{ request('progress') == '76-100' ? 'selected' : '' }}>76-100%</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_projects'))
                        <div class="projects-bulk-row">
                            <div class="selected-count" id="selectedProjectsCount">
                                <i class="fas fa-square-check me-1"></i>No projects selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_projects'))
            <form id="bulkForm" method="POST" action="{{ route('admin.projects.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Projects Table - UX Improved -->
            <div class="projects-panel">
                <div class="projects-panel-header">
                    <div class="projects-panel-title">
                        <div class="projects-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Project Records</div>
                            <h3>Projects List</h3>
                            <p class="panel-copy">Sort records, open details, and edit project information without leaving this page.</p>
                        </div>
                    </div>
                    <div class="projects-panel-actions">
                        <span class="projects-count-pill"><i class="fas fa-folder-open"></i>{{ isset($projects) ? $projects->total() : 0 }} projects</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="projectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'location', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Location
                                            @if(request('sort') == 'location')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Start Date
                                            @if(request('sort') == 'start_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'expected_completion', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Expected Completion
                                            @if(request('sort') == 'expected_completion')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th width="200">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Progress
                                            @if(request('sort') == 'progress')
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
                                @forelse($projects as $project)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $project->id }}" class="project-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="project-icon me-2">
                                                <i class="fas fa-hard-hat text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($project->title, 30) }}</div>
                                                <small class="text-muted d-md-none">{{ $project->location ?? 'No location' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="location-badge">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($project->location, 20) ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->start_date 
                                                ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">
                                            {{ $project->expected_completion 
                                                ? \Carbon\Carbon::parse($project->expected_completion)->format('M d, Y')
                                                : '-' }}
                                        </span>
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </td>
                                    <td class="progress-cell">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                    role="progressbar"
                                                    style="width: {{ $project->progress }}%;"
                                                    aria-valuenow="{{ $project->progress }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $project->progress }}%
                                                </div>
                                            </div>
                                            <small class="fw-semibold">{{ $project->progress }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_projects can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $project->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_projects permission) -->
                                            @if(!$project->deleted_at)
                                            @admin_can('update_projects')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}" title="Edit Project">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif


                                            <!-- Delete (requires delete_projects permission) -->
                                            @admin_can('delete_projects')
                                            @if($project->deleted_at)
                                            <form method="POST" action="{{ route('admin.projects.restore', $project->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this project?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this project?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endadmin_can
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div>
                                                <div class="empty-state-icon"><i class="fas fa-project-diagram"></i></div>
                                                <h4>No projects found</h4>
                                                <p>No project records match your current search and filters.</p>
                                                @admin_can('create_projects')
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                                    <i class="fas fa-plus"></i>Add Project
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
                    @if(isset($projects) && $projects->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $projects->firstItem() ?? 0 }}-{{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $projects->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Project Modal with Validation - Only if user has create_projects permission -->
            @admin_can('create_projects')
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat"></i>
                                <span class="modal-title-stack">
                                    <span>New Project</span>
                                    <span class="modal-subtitle">Add a clear project record with status, dates, location, and progress.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.projects.store') }}" id="addProjectForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="e.g. Drainage Rehabilitation Phase 1" required>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif" 
                                                  name="description" rows="4" placeholder="Write project description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Dates -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('start_date') is-invalid @enderror @endif" 
                                               name="start_date" value="{{ session('form_type') == 'add' ? old('start_date') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Expected Completion</label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('expected_completion') is-invalid @enderror @endif" 
                                               name="expected_completion" value="{{ session('form_type') == 'add' ? old('expected_completion') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('expected_completion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif" 
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="e.g. Zone 3, Hulong Duhat">
                                        @if(session('form_type') == 'add')
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Status and Progress -->
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" 
                                                name="status" required>
                                            <option value="">Select status</option>
                                            <option value="ongoing" {{ (session('form_type') == 'add' && old('status') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                            <option value="completed" {{ (session('form_type') == 'add' && old('status') == 'completed') ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                                        <input type="range" class="form-range" id="addProgressRange" min="0" max="100" step="1" value="{{ old('progress', 0) }}">
                                        <div class="text-center mt-2">
                                            <span class="badge bg-primary" id="addProgressValue">0%</span>
                                        </div>
                                        <input type="hidden" name="progress" id="addProgressHidden" value="{{ old('progress', 0) }}">
                                        @if(session('form_type') == 'add')
                                            @error('progress')
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
                                <button type="submit" form="addProjectForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Project
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Project Modals with Validation - Only if user has update_projects permission -->
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Project</span>
                                        <span class="modal-subtitle">Update project details and keep progress aligned with the selected status.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" id="editProjectForm{{ $project->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $project->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $project->id ? old('title', $project->title) : $project->title }}" required>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('description') is-invalid @enderror @endif" 
                                                      name="description" rows="4" required>{{ session('form_type') == 'edit_' . $project->id ? old('description', $project->description) : $project->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Dates -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('start_date') is-invalid @enderror @endif" 
                                                   name="start_date" value="{{ session('form_type') == 'edit_' . $project->id ? old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') : ($project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Expected Completion</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('expected_completion') is-invalid @enderror @endif" 
                                                   name="expected_completion" value="{{ session('form_type') == 'edit_' . $project->id ? old('expected_completion', $project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') : ($project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('expected_completion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Location -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Location</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('location') is-invalid @enderror @endif" 
                                                   name="location" value="{{ session('form_type') == 'edit_' . $project->id ? old('location', $project->location) : $project->location }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Status and Progress -->
                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $project->id) @error('status') is-invalid @enderror @endif" 
                                                    name="status" id="edit_status_{{ $project->id }}" required>
                                                <option value="">Select status</option>
                                                <option value="ongoing" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                                            <input type="range" class="form-range" id="editProgressRange{{ $project->id }}" min="0" max="100" step="1" value="{{ $project->progress }}">
                                            <div class="text-center mt-2">
                                                <span class="badge bg-primary" id="editProgressValue{{ $project->id }}">{{ $project->progress }}%</span>
                                            </div>
                                            <input type="hidden" name="progress" id="editProgressHidden{{ $project->id }}" value="{{ $project->progress }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('progress')
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
                                    <button type="submit" form="editProjectForm{{ $project->id }}" class="btn btn-primary" id="submitEditForm{{ $project->id }}">
                                        <i class="fas fa-save me-2"></i>Update Project
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($projects as $project)
            <div class="modal fade" id="viewModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-hard-hat"></i>
                                <span class="modal-title-stack">
                                    <span>Project Details</span>
                                    <span class="modal-subtitle">Review project timeline, location, progress, and description.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h4 class="fw-bold">{{ $project->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($project->status == 'ongoing')
                                            <span class="badge bg-warning-subtle text-warning">Ongoing</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Progress</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                role="progressbar"
                                                style="width: {{ $project->progress }}%;">
                                                {{ $project->progress }}%
                                            </div>
                                        </div>
                                        <span class="fw-semibold">{{ $project->progress }}%</span>
                                    </div>
                                </div>

                                @if($project->location)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Location</label>
                                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $project->location }}</p>
                                </div>
                                @endif

                                @if($project->start_date)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Start Date</label>
                                    <p><i class="fas fa-calendar me-2 text-primary"></i>{{ $project->start_date->format('F d, Y') }}</p>
                                </div>
                                @endif

                                @if($project->expected_completion)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Expected Completion</label>
                                    <p>
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        {{ $project->expected_completion->format('F d, Y') }}
                                        @if($project->is_overdue)
                                            <span class="badge bg-danger-subtle text-danger ms-2">Overdue</span>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ $project->created_at ? $project->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <p><i class="fas fa-sync me-2 text-primary"></i>{{ $project->updated_at ? $project->updated_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-muted">Description</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($project->description)) !!}
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


            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addProjectModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editProjectModal{{ $editId }}'));
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
            const checkboxes = document.querySelectorAll('.project-checkbox:checked');
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
                        text: 'Please select at least one project to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one project to archive.');
                }
                return;
            }

            // Clear previous ids to avoid duplicate submissions.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected project(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} project(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.project-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof updateSelectedProjectsCount === 'function') {
                updateSelectedProjectsCount();
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

        // Update progress range display for add modal
        document.getElementById('addProgressRange')?.addEventListener('input', function() {
            document.getElementById('addProgressValue').textContent = this.value + '%';
            document.getElementById('addProgressHidden').value = this.value;
            
            // Auto-select status based on progress
            const statusSelect = document.querySelector('select[name="status"]');
            if (this.value == 100) {
                statusSelect.value = 'completed';
            } else {
                statusSelect.value = 'ongoing';
            }
        });

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.project-checkbox');
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

            // Initialize progress bars with gradient
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                if (!bar.classList.contains('bg-success')) {
                    bar.style.background = 'var(--primary-gradient)';
                }
            });

            // Setup edit modal progress listeners
            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                (function(projectId) {
                    const range = document.getElementById('editProgressRange' + projectId);
                    const value = document.getElementById('editProgressValue' + projectId);
                    const hidden = document.getElementById('editProgressHidden' + projectId);
                    const statusSelect = document.getElementById('edit_status_' + projectId);
                    
                    if (range && value && hidden && statusSelect) {
                        range.addEventListener('input', function() {
                            value.textContent = this.value + '%';
                            hidden.value = this.value;
                            
                            // Auto-select status based on progress
                            if (this.value == 100) {
                                statusSelect.value = 'completed';
                            } else {
                                statusSelect.value = 'ongoing';
                            }
                        });
                    }
                })('{{ $project->id }}');
                @endforeach
            @endif
        });
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

        function attachProjectValidation(form) {
            if (!form) return;
            const title              = form.querySelector('[name="title"]');
            const description        = form.querySelector('[name="description"]');
            const status             = form.querySelector('[name="status"]');
            const startDate          = form.querySelector('[name="start_date"]');
            const expectedCompletion = form.querySelector('[name="expected_completion"]');
            const progressHidden     = form.querySelector('input[type="hidden"][name="progress"]');

            function hasFourDigitYear(dateValue) {
                return /^\d{4}-\d{2}-\d{2}$/.test(dateValue);
            }

            function getProgressValue() {
                const raw = progressHidden ? progressHidden.value : '0';
                const parsed = parseInt(raw, 10);
                return Number.isNaN(parsed) ? 0 : parsed;
            }

            function validateStatusVsProgress() {
                if (!status) return true;
                const progress = getProgressValue();
                if (status.value === 'completed' && progress !== 100) {
                    sf(status, 'Status can only be Completed when progress is 100%.');
                    return false;
                }
                if (status.value) {
                    cf(status);
                }
                return true;
            }

            function checkDateFieldFormat(field) {
                if (!field || !field.value) return true;
                if (!hasFourDigitYear(field.value)) {
                    sf(field, 'Date must use a 4-digit year (YYYY-MM-DD).');
                    return false;
                }
                return true;
            }

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (description) {
                description.addEventListener('input', function() { cf(this); }, {once: true});
                description.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Description is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (status) status.addEventListener('change', function() {
                if (!this.value) {
                    sf(this, 'Please select a status.');
                } else {
                    validateStatusVsProgress();
                }
            });

            if (progressHidden) {
                progressHidden.addEventListener('input', validateStatusVsProgress);
                progressHidden.addEventListener('change', validateStatusVsProgress);
            }

            if (startDate) {
                startDate.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion) {
                expectedCompletion.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion && startDate) {
                const checkDates = function() {
                    if (!checkDateFieldFormat(startDate) || !checkDateFieldFormat(expectedCompletion)) {
                        return;
                    }
                    if (expectedCompletion.value && startDate.value && expectedCompletion.value < startDate.value) {
                        sf(expectedCompletion, 'Completion date must not be before the start date.');
                    } else cf(expectedCompletion);
                };
                expectedCompletion.addEventListener('change', checkDates);
                startDate.addEventListener('change', checkDates);
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                    else { cf(title); }
                }
                if (description) {
                    if (!description.value.trim()) { sf(description, 'Description is required.'); valid = false; }
                    else if (description.value.trim().length < 10) { sf(description, 'Description must be at least 10 characters.'); valid = false; }
                    else { cf(description); }
                }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                else if (status && !validateStatusVsProgress()) { valid = false; }
                else if (status) { cf(status); }

                if (startDate && !checkDateFieldFormat(startDate)) {
                    valid = false;
                }
                if (expectedCompletion && !checkDateFieldFormat(expectedCompletion)) {
                    valid = false;
                }

                if (startDate && expectedCompletion && startDate.value && expectedCompletion.value && expectedCompletion.value < startDate.value) {
                    sf(expectedCompletion, 'Completion date must not be before the start date.'); valid = false;
                }
                else if (expectedCompletion) {
                    cf(expectedCompletion);
                }
                if (!valid) e.preventDefault();
            });
        }

        attachProjectValidation(document.getElementById('addProjectForm'));
        document.querySelectorAll('[id^="editProjectForm"]').forEach(attachProjectValidation);
    });

// UX helper: keep the selected counter updated for bulk actions.
        function updateSelectedProjectsCount() {
            const selected = document.querySelectorAll('.project-checkbox:checked').length;
            const counter = document.getElementById('selectedProjectsCount');
            if (!counter) return;
            counter.innerHTML = selected > 0
                ? `<i class="fas fa-square-check me-1"></i>${selected} project${selected === 1 ? '' : 's'} selected`
                : '<i class="fas fa-square-check me-1"></i>No projects selected';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.project-checkbox, #selectAll').forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelectedProjectsCount);
            });
            updateSelectedProjectsCount();

            document.querySelectorAll('.modal-body .row.g-3').forEach((row) => {
                if (!row.closest('.modal-body')) return;
                row.classList.add('modal-form-grid');
            });

            document.querySelectorAll('.modal-body textarea[name="description"]').forEach((textarea) => {
                if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList?.contains('field-hint')) {
                    textarea.insertAdjacentHTML('afterend', '<span class="field-hint">Use short, resident-friendly wording. Include scope, location, and expected outcome when possible.</span>');
                }
            });
        });
    </script>

@endpush
