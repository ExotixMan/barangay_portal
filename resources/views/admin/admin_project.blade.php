@extends('admin.layouts.admin')

@section('title', 'Project Management')
@section('page_title', 'Project Management')
@section('page_icon', 'fas fa-project-diagram')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/projects.css') }}">

@endpush

@section('content')
            <div class="projects-page">

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
                            <button type="button" data-click-call="bulkDelete" class="btn btn-outline-danger" title="Bulk Archive">
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
                                        <input type="checkbox" id="selectAll" data-click-call="toggleSelectAll">
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
                                            <div class="min-w-0">
                                                <div class="project-title-text" title="{{ $project->title }}">{{ Str::limit($project->title, 42) }}</div>
                                                <div class="project-mobile-meta">
                                                    <span><i class="fas fa-location-dot"></i>{{ Str::limit($project->location ?? 'No location', 26) }}</span>
                                                    <span>
                                                        @if($project->deleted_at)
                                                            <i class="fas fa-box-archive"></i>Archived
                                                        @elseif($project->status == 'ongoing')
                                                            <i class="fas fa-hourglass-half"></i>Ongoing · {{ $project->progress }}%
                                                        @else
                                                            <i class="fas fa-circle-check"></i>Completed · {{ $project->progress }}%
                                                        @endif
                                                    </span>
                                                </div>
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
                                        <div class="project-progress-wrap">
                                            <div class="progress project-progress-track">
                                                <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                                    role="progressbar"
                                                    style="width: {{ $project->progress }}%;"
                                                    aria-valuenow="{{ $project->progress }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <div class="project-progress-label">
                                                <span>{{ $project->status == 'completed' ? 'Completed' : 'Progress' }}</span>
                                                <strong>{{ $project->progress }}%</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="row-actions" aria-label="Actions for {{ $project->title }}">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $project->id }}"
                                                    title="View project details">
                                                <i class="fas fa-eye"></i><span class="action-label">View</span>
                                            </button>

                                            <div class="dropdown action-dropdown">
                                                <button type="button"
                                                        class="btn btn-sm btn-light-soft action-more-btn icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @if(!$project->deleted_at)
                                                        @admin_can('update_projects')
                                                        <button type="button"
                                                                class="dropdown-item action-edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editProjectModal{{ $project->id }}">
                                                            <span class="action-menu-icon"><i class="fas fa-pen"></i></span>
                                                            <span class="action-menu-copy">
                                                                <strong>Edit project</strong>
                                                                <small>Update project details and progress</small>
                                                            </span>
                                                        </button>
                                                        @endadmin_can
                                                    @endif

                                                    @admin_can('delete_projects')
                                                        @if($project->deleted_at)
                                                        <form method="POST" action="{{ route('admin.projects.restore', $project->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this project?']) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item action-restore">
                                                                <span class="action-menu-icon"><i class="fas fa-rotate-left"></i></span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Restore project</strong>
                                                                    <small>Return this project to the active list</small>
                                                                </span>
                                                            </button>
                                                        </form>
                                                        @else
                                                        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this project?']) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item action-archive">
                                                                <span class="action-menu-icon"><i class="fas fa-box-archive"></i></span>
                                                                <span class="action-menu-copy">
                                                                    <strong>Archive project</strong>
                                                                    <small>Remove it from the active project list</small>
                                                                </span>
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

            @admin_can('create_projects')
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-hard-hat"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Project</h5>
                                    <span class="modal-subtitle">Create a project record in three clear sections. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.projects.store') }}" id="addProjectForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="modal-form-intro">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Keep the project title and description clear so administrators can recognize the work quickly.</span>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Project Information</h6>
                                            <p>Describe the project and its intended work.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Project Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="e.g. Drainage Rehabilitation Phase 1" required>
                                            <span class="field-hint">Use a short, specific project name that is easy to identify.</span>
                                            @if(session('form_type') == 'add')
                                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif"
                                                      name="description" rows="5" placeholder="Write project description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                            <span class="field-hint">Include the scope, expected outcome, and important details.</span>
                                            @if(session('form_type') == 'add')
                                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Timeline &amp; Location</h6>
                                            <p>Set the project schedule and where the work will take place.</p>
                                        </div>
                                    </div>
                                    <div class="timeline-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-day"></i>Start Date</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('start_date') is-invalid @enderror @endif"
                                                   name="start_date" value="{{ session('form_type') == 'add' ? old('start_date') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-check"></i>Expected Completion</label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('expected_completion') is-invalid @enderror @endif"
                                                   name="expected_completion" value="{{ session('form_type') == 'add' ? old('expected_completion') : '' }}">
                                            <span class="field-hint">Must not be earlier than the start date.</span>
                                            @if(session('form_type') == 'add')
                                                @error('expected_completion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label"><i class="fas fa-location-dot"></i>Location</label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif"
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="e.g. Zone 3, Hulong Duhat">
                                        @if(session('form_type') == 'add')
                                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        @endif
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Status &amp; Progress</h6>
                                            <p>Keep project status consistent with its completion percentage.</p>
                                        </div>
                                    </div>
                                    <div class="status-progress-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-circle-half-stroke"></i>Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" name="status" required>
                                                <option value="">Select status</option>
                                                <option value="ongoing" {{ (session('form_type') == 'add' && old('status') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (session('form_type') == 'add' && old('status') == 'completed') ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <span class="field-hint">Completed is valid only when progress reaches 100%.</span>
                                            @if(session('form_type') == 'add')
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                        <div class="progress-control-card">
                                            <div class="progress-control-head">
                                                <label class="form-label mb-0"><i class="fas fa-chart-line"></i>Progress <span class="text-danger">*</span></label>
                                                <span class="progress-value-pill" id="addProgressValue">{{ old('progress', 0) }}%</span>
                                            </div>
                                            <input type="range" class="form-range" id="addProgressRange" min="0" max="100" step="1" value="{{ old('progress', 0) }}">
                                            <div class="progress-scale"><span>0%</span><span>50%</span><span>100%</span></div>
                                            <input type="hidden" name="progress" id="addProgressHidden" value="{{ old('progress', 0) }}">
                                            @if(session('form_type') == 'add')
                                                @error('progress')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Cancel</button>
                            <button type="submit" form="addProjectForm" class="btn btn-success" id="submitAddForm"><i class="fas fa-save"></i>Save Project</button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_projects'))
                @foreach($projects as $project)
                <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Project</h5>
                                        <span class="modal-subtitle">Update {{ $project->title }} while keeping its dates, status, and progress consistent.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" id="editProjectForm{{ $project->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $project->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Project Information</h6>
                                                <p>Update the project name and description.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Project Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $project->id ? old('title', $project->title) : $project->title }}" required>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('description') is-invalid @enderror @endif"
                                                          name="description" rows="5" required>{{ session('form_type') == 'edit_' . $project->id ? old('description', $project->description) : $project->description }}</textarea>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">2</div>
                                            <div>
                                                <h6>Timeline &amp; Location</h6>
                                                <p>Adjust dates and location without changing the existing workflow.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-day"></i>Start Date</label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('start_date') is-invalid @enderror @endif"
                                                       name="start_date" value="{{ session('form_type') == 'edit_' . $project->id ? old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') : ($project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-check"></i>Expected Completion</label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('expected_completion') is-invalid @enderror @endif"
                                                       name="expected_completion" value="{{ session('form_type') == 'edit_' . $project->id ? old('expected_completion', $project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') : ($project->expected_completion ? $project->expected_completion->format('Y-m-d') : '') }}">
                                                <span class="field-hint">Must not be earlier than the start date.</span>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('expected_completion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label"><i class="fas fa-location-dot"></i>Location</label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $project->id) @error('location') is-invalid @enderror @endif"
                                                   name="location" value="{{ session('form_type') == 'edit_' . $project->id ? old('location', $project->location) : $project->location }}">
                                            @if(session('form_type') == 'edit_' . $project->id)
                                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Status &amp; Progress</h6>
                                                <p>Progress automatically keeps the status aligned with completion.</p>
                                            </div>
                                        </div>
                                        <div class="status-progress-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-circle-half-stroke"></i>Status <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $project->id) @error('status') is-invalid @enderror @endif"
                                                        name="status" id="edit_status_{{ $project->id }}" required>
                                                    <option value="">Select status</option>
                                                    <option value="ongoing" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                    <option value="completed" {{ (session('form_type') == 'edit_' . $project->id ? old('status', $project->status) : $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                <span class="field-hint">Completed is valid only when progress reaches 100%.</span>
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                            <div class="progress-control-card">
                                                <div class="progress-control-head">
                                                    <label class="form-label mb-0"><i class="fas fa-chart-line"></i>Progress <span class="text-danger">*</span></label>
                                                    <span class="progress-value-pill" id="editProgressValue{{ $project->id }}">{{ $project->progress }}%</span>
                                                </div>
                                                <input type="range" class="form-range" id="editProgressRange{{ $project->id }}" min="0" max="100" step="1" value="{{ $project->progress }}">
                                                <div class="progress-scale"><span>0%</span><span>50%</span><span>100%</span></div>
                                                <input type="hidden" name="progress" id="editProgressHidden{{ $project->id }}" value="{{ $project->progress }}">
                                                @if(session('form_type') == 'edit_' . $project->id)
                                                    @error('progress')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Cancel</button>
                                <button type="submit" form="editProjectForm{{ $project->id }}" class="btn btn-primary" id="submitEditForm{{ $project->id }}"><i class="fas fa-save"></i>Update Project</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($projects as $project)
            <div class="modal fade" id="viewModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-hard-hat"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Project Details</h5>
                                    <span class="modal-subtitle">Review project status, timeline, progress, location, and description.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="project-view-hero">
                                <h4 class="project-view-title">{{ $project->title }}</h4>
                                <div class="project-view-meta">
                                    @if($project->deleted_at)
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-box-archive me-1"></i>Archived</span>
                                    @elseif($project->status == 'ongoing')
                                        <span class="badge bg-warning-subtle text-warning"><i class="fas fa-hourglass-half me-1"></i>Ongoing</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success"><i class="fas fa-circle-check me-1"></i>Completed</span>
                                    @endif
                                    @if($project->is_overdue)
                                        <span class="badge bg-danger-subtle text-danger"><i class="fas fa-triangle-exclamation me-1"></i>Overdue</span>
                                    @endif
                                </div>
                            </div>

                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Location</span>
                                    <span class="view-summary-value">{{ $project->location ?: 'Not specified' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Start Date</span>
                                    <span class="view-summary-value">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Expected Completion</span>
                                    <span class="view-summary-value">{{ $project->expected_completion ? $project->expected_completion->format('M d, Y') : 'Not set' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Last Updated</span>
                                    <span class="view-summary-value">{{ $project->updated_at ? $project->updated_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="view-progress-card">
                                <div class="view-progress-head">
                                    <span><i class="fas fa-chart-line me-1"></i>Project Progress</span>
                                    <strong>{{ $project->progress }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $project->status == 'completed' ? 'bg-success' : '' }}"
                                         role="progressbar"
                                         style="width: {{ $project->progress }}%;"
                                         aria-valuenow="{{ $project->progress }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2"></i>Project Description</div>
                                <div class="project-description-box">{!! nl2br(e($project->description)) !!}</div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-clock-rotate-left me-2"></i>Record Information</div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <span class="view-summary-label">Created At</span>
                                        <span class="view-summary-value">{{ $project->created_at ? $project->created_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <span class="view-summary-label">Last Updated</span>
                                        <span class="view-summary-value">{{ $project->updated_at ? $project->updated_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if ($errors->any() && session('form_type') == 'add')
            <script src="{{ asset('js/pages/admin/admin_project-part1.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-project-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_project-dynamic1.js') }}"></script>
            @endif
            </div>
@endsection

@push('scripts')
@include('shared.js-config', [
    'id' => 'js-config-admin-projects',
    'data' => [
        'canUpdate' => auth('admin')->user()->hasPermission('update_projects'),
        'projectIds' => $projects->pluck('id')->values(),
    ],
])
<script src="{{ asset('js/pages/admin/admin_project-main.js') }}"></script>

    <script src="{{ asset('js/pages/admin/admin_project-part2.js') }}"></script>

@endpush
