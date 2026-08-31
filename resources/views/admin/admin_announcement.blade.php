@extends('admin.layouts.admin')

@section('title', 'Announcement Management')
@section('page_title', 'Announcement Management')
@section('page_icon', 'fas fa-bullhorn')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/announcements.css') }}">

@endpush

@section('content')
            <div class="announcements-page">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show announcements-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="announcements-panel announcements-filter-panel">
                <form method="GET" action="{{ route('admin.announcements.index') }}" id="searchForm">
                    <div class="announcements-panel-header">
                        <div class="announcements-panel-title">
                            <div class="announcements-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-bullhorn"></i> Announcement Directory</div>
                                <h3>Announcements</h3>
                                <p class="panel-copy">Search, filter, publish, and manage resident-facing announcements.</p>
                            </div>
                        </div>

                        <div class="announcements-panel-actions">
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                <i class="fas fa-plus"></i>Add Announcement
                            </button>
                            @endadmin_can
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#bulkNotifyResidentsModal">
                                <i class="fas fa-paper-plane"></i>Notify Residents
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="announcements-filter-body">
                        <div class="announcements-filter-toolbar">
                            <div class="announcements-search-group">
                                <div class="announcements-search-field">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, content, or category..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search announcements">
                                </div>
                                <button type="submit" class="btn btn-primary search-submit-btn">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </div>

                            <select name="status" class="form-select" aria-label="Filter by status">
                                <option value="">All status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted bin</option>
                            </select>

                            <select name="category" class="form-select" aria-label="Filter by category">
                                <option value="">All categories</option>
                                <option value="important" {{ request('category') == 'important' ? 'selected' : '' }}>Important</option>
                                <option value="events" {{ request('category') == 'events' ? 'selected' : '' }}>Events</option>
                                <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>Health</option>
                                <option value="services" {{ request('category') == 'services' ? 'selected' : '' }}>Services</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>

                            <select name="featured" class="form-select" aria-label="Filter by featured status">
                                <option value="">All featured</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured only</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not featured</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_announcements'))
                        <div class="announcements-bulk-row">
                            <div class="selected-count" id="selectedAnnouncementsCount">
                                <i class="fas fa-square-check me-1"></i>No announcements selected
                            </div>
                            <button type="button" data-click-call="bulkDelete" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_announcements'))
            <form id="bulkForm" method="POST" action="{{ route('admin.announcements.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <div class="announcements-panel">
                <div class="announcements-panel-header">
                    <div class="announcements-panel-title">
                        <div class="announcements-panel-icon"><i class="fas fa-list-check"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-table"></i> Announcement Records</div>
                            <h3>Announcements List</h3>
                            <p class="panel-copy">Sort records, view full details, edit drafts, and archive outdated announcements.</p>
                        </div>
                    </div>
                    <div class="announcements-panel-actions">
                        <span class="announcements-count-pill"><i class="fas fa-bullhorn"></i>{{ isset($announcements) ? $announcements->total() : 0 }} announcements</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="announcementsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="ps-4">
                                        <input type="checkbox" id="selectAll" data-click-call="toggleSelectAll">
                                    </th>
                                    <th class="ps-0">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Title
                                            @if(request('sort') == 'title')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Category
                                            @if(request('sort') == 'category')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'is_featured', 'direction' => request('sort') === 'is_featured' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Featured
                                            @if(request('sort') == 'is_featured')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'direction' => request('sort') === 'views' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Views
                                            @if(request('sort') == 'views')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'direction' => request('sort') === 'published_at' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Published At
                                            @if(request('sort') == 'published_at')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc', 'page' => 1]) }}"
                                        class="text-decoration-none text-dark">
                                            Status
                                            @if(request('sort') == 'status')
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
                                @forelse($announcements as $ann)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $ann->id }}" class="announcement-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="announcement-icon me-2">
                                                @if($ann->image)
                                                    <button type="button" class="photo-view-btn" data-click-call="openZoomModal" data-click-args="{{ json_encode([$ann->image_url]) }}" title="View announcement photo">
                                                        <img src="{{ $ann->image_url }}" alt="{{ $ann->title }}" class="zoomable-image">
                                                    </button>
                                                @else
                                                    <i class="fas fa-bullhorn text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($ann->title, 40) }}</div>
                                                <small class="text-muted d-lg-none">{{ \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($ann->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="fas fa-star me-1"></i>Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="views-badge">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}
                                        </span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="date-badge">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('M d, Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        @if($ann->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($ann->status == 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @elseif($ann->status == 'draft')
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-cluster">
                                            <button type="button"
                                                    class="btn btn-sm action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $ann->id }}"
                                                    title="View announcement details">
                                                <i class="fas fa-eye"></i>View
                                            </button>

                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn-sm action-more-toggle icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions"
                                                        aria-label="More actions for {{ $ann->title }}">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end action-menu">
                                                    @if(!$ann->deleted_at)
                                                    @admin_can('update_announcements')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal{{ $ann->id }}">
                                                        <i class="fas fa-pen"></i><span>Edit announcement</span>
                                                    </button>
                                                    @endadmin_can
                                                    @endif

                                                    @if(!$ann->deleted_at && $ann->status == 'published' && auth('admin')->user()->hasPermission('feature_announcements'))
                                                    <form method="POST" action="{{ route('admin.announcements.toggle-feature', $ann->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" data-confirm="{{ $ann->is_featured ? 'Remove featured status?' : 'Mark as featured?' }}">
                                                            <i class="fas fa-star"></i>
                                                            <span>{{ $ann->is_featured ? 'Remove featured' : 'Mark as featured' }}</span>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    @admin_can('delete_announcements')
                                                    @if($ann->deleted_at)
                                                    <form method="POST" action="{{ route('admin.announcements.restore', $ann->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this announcement from the deleted bin?']) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-rotate-left"></i><span>Restore announcement</span>
                                                        </button>
                                                    </form>
                                                    @elseif($ann->status == 'archived')
                                                    <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Publish this archived announcement again?']) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="title" value="{{ $ann->title }}">
                                                        <textarea name="content" class="d-none">{{ $ann->content }}</textarea>
                                                        <input type="hidden" name="category" value="{{ $ann->category }}">
                                                        <input type="hidden" name="status" value="published">
                                                        <input type="hidden" name="published_at" value="{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}">
                                                        @if($ann->is_featured)
                                                            <input type="hidden" name="is_featured" value="1">
                                                        @endif
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-globe"></i><span>Publish again</span>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this announcement?']) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-box-archive"></i><span>Archive announcement</span>
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
                                                <div class="empty-state-icon"><i class="fas fa-bullhorn"></i></div>
                                                <h4>No announcements found</h4>
                                                <p>No announcement records match the current search and filters.</p>
                                                @admin_can('create_announcements')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                                    <i class="fas fa-plus"></i>Add Announcement
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

                    @if(isset($announcements) && $announcements->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $announcements->firstItem() ?? 0 }}-{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $announcements->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            @admin_can('create_announcements')
            <div class="modal fade announcement-form-modal" id="addAnnouncementModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-bullhorn"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Announcement</h5>
                                    <span class="modal-subtitle">Create a resident-facing announcement. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" id="addAnnouncementForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="modal-form-intro">
                                    <i class="fas fa-circle-info"></i>
                                    <span>Use a clear title and message first, then choose publishing settings and optional media.</span>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Announcement Content</h6>
                                            <p>Write the title and public message residents will read.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" placeholder="Example: Scheduled Water Interruption" required>
                                            <small class="field-hint">Keep it short and specific so residents understand the topic at a glance.</small>
                                            @if(session('form_type') == 'add')
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('content') is-invalid @enderror @endif"
                                                      name="content" rows="7" placeholder="Write the announcement details, dates, requirements, and contact information..." required>{{ session('form_type') == 'add' ? old('content') : '' }}</textarea>
                                            <small class="field-hint">Put the most important information first and keep instructions easy to scan.</small>
                                            @if(session('form_type') == 'add')
                                                @error('content')
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
                                            <h6>Publishing Settings</h6>
                                            <p>Choose where the announcement belongs and when it should be visible.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-folder"></i>Category <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('category') is-invalid @enderror @endif" name="category" required>
                                                <option value="">Select category</option>
                                                <option value="important" {{ (session('form_type') == 'add' && old('category') == 'important') ? 'selected' : '' }}>Important</option>
                                                <option value="events" {{ (session('form_type') == 'add' && old('category') == 'events') ? 'selected' : '' }}>Events</option>
                                                <option value="health" {{ (session('form_type') == 'add' && old('category') == 'health') ? 'selected' : '' }}>Health</option>
                                                <option value="services" {{ (session('form_type') == 'add' && old('category') == 'services') ? 'selected' : '' }}>Services</option>
                                                <option value="other" {{ (session('form_type') == 'add' && old('category') == 'other') ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-circle-check"></i>Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" name="status" required>
                                                <option value="">Select status</option>
                                                <option value="published" {{ (session('form_type') == 'add' && old('status') == 'published') ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ (session('form_type') == 'add' && old('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                                <option value="archived" {{ (session('form_type') == 'add' && old('status') == 'archived') ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @if(session('form_type') == 'add')
                                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-calendar-days"></i>Published Date</label>
                                            <input type="datetime-local" class="form-control @if(session('form_type') == 'add') @error('published_at') is-invalid @enderror @endif"
                                                   name="published_at" value="{{ session('form_type') == 'add' ? old('published_at') : '' }}">
                                            <small class="field-hint">Leave empty for immediate publishing.</small>
                                            @if(session('form_type') == 'add')
                                                @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><i class="fas fa-star"></i>Featured</label>
                                            <div class="publish-setting-card">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input @if(session('form_type') == 'add') @error('is_featured') is-invalid @enderror @endif"
                                                           name="is_featured" id="is_featured" value="1" {{ (session('form_type') == 'add' && old('is_featured')) ? 'checked' : '' }}>
                                                    <div>
                                                        <label class="form-check-label fw-semibold" for="is_featured">Highlight this announcement</label>
                                                        <small class="field-hint mb-0">Use featured status only for notices that need extra visibility.</small>
                                                    </div>
                                                </div>
                                                @if(session('form_type') == 'add')
                                                    @error('is_featured')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Announcement Image</h6>
                                            <p>Add an optional image when it helps residents understand the notice faster.</p>
                                        </div>
                                    </div>
                                    <div class="upload-card">
                                        <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                        <div class="flex-grow-1 min-w-0">
                                            <label class="form-label">Featured Image</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif"
                                                   name="image" accept="image/*" data-change-call="previewImage" data-change-pass="self,args" data-change-args="{{ json_encode(['addImagePreview']) }}">
                                            <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                            @if(session('form_type') == 'add')
                                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @endif
                                            <div id="addImagePreview" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Cancel
                            </button>
                            <button type="submit" form="addAnnouncementForm" class="btn btn-primary" id="submitAddForm">
                                <i class="fas fa-save"></i>Save Announcement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_announcements'))
                @foreach($announcements as $ann)
                <div class="modal fade announcement-form-modal" id="editAnnouncementModal{{ $ann->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Announcement</h5>
                                        <span class="modal-subtitle">Update the content, publishing settings, or image for this announcement.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" enctype="multipart/form-data" id="editAnnouncementForm{{ $ann->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $ann->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Announcement Content</h6>
                                                <p>Review the resident-facing title and message.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $ann->id ? old('title', $ann->title) : $ann->title }}" required>
                                                <small class="field-hint">Keep the title specific and easy to recognize in the announcement list.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Content <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('content') is-invalid @enderror @endif"
                                                          name="content" rows="7" required>{{ session('form_type') == 'edit_' . $ann->id ? old('content', $ann->content) : $ann->content }}</textarea>
                                                <small class="field-hint">Check dates, requirements, locations, and contact details before saving.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">2</div>
                                            <div>
                                                <h6>Publishing Settings</h6>
                                                <p>Change category, visibility, schedule, or featured status.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-folder"></i>Category <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('category') is-invalid @enderror @endif" name="category" required>
                                                    <option value="">Select category</option>
                                                    <option value="important" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'important' ? 'selected' : '' }}>Important</option>
                                                    <option value="events" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'events' ? 'selected' : '' }}>Events</option>
                                                    <option value="health" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'health' ? 'selected' : '' }}>Health</option>
                                                    <option value="services" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'services' ? 'selected' : '' }}>Services</option>
                                                    <option value="other" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-circle-check"></i>Status <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('status') is-invalid @enderror @endif" name="status" required>
                                                    <option value="">Select status</option>
                                                    <option value="published" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                    <option value="draft" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="archived" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                                </select>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-calendar-days"></i>Published Date</label>
                                                <input type="datetime-local" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('published_at') is-invalid @enderror @endif"
                                                       name="published_at" value="{{ session('form_type') == 'edit_' . $ann->id ? old('published_at', $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') : ($ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') }}">
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label"><i class="fas fa-star"></i>Featured</label>
                                                <div class="publish-setting-card">
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input @if(session('form_type') == 'edit_' . $ann->id) @error('is_featured') is-invalid @enderror @endif"
                                                               name="is_featured" id="edit_is_featured_{{ $ann->id }}" value="1"
                                                               {{ (session('form_type') == 'edit_' . $ann->id ? old('is_featured', $ann->is_featured) : $ann->is_featured) ? 'checked' : '' }}>
                                                        <div>
                                                            <label class="form-check-label fw-semibold" for="edit_is_featured_{{ $ann->id }}">Highlight this announcement</label>
                                                            <small class="field-hint mb-0">Keep this enabled only while the notice needs extra visibility.</small>
                                                        </div>
                                                    </div>
                                                    @if(session('form_type') == 'edit_' . $ann->id)
                                                        @error('is_featured')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Announcement Image</h6>
                                                <p>Leave the upload empty to keep the current image.</p>
                                            </div>
                                        </div>
                                        <div class="upload-card">
                                            <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                            <div class="flex-grow-1 min-w-0">
                                                <label class="form-label">Featured Image</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('image') is-invalid @enderror @endif"
                                                       name="image" accept="image/*" data-change-call="previewImage" data-change-pass="self,args" data-change-args="{{ json_encode(['editImagePreview' . $ann->id]) }}">
                                                <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                                @if(session('form_type') == 'edit_' . $ann->id)
                                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                @endif
                                                <div id="editImagePreview{{ $ann->id }}" class="mt-2">
                                                    @if($ann->image)
                                                    <button type="button" class="photo-view-btn w-100" data-click-call="openZoomModal" data-click-args="{{ json_encode([$ann->image_url]) }}" title="View current photo">
                                                        <img src="{{ $ann->image_url }}" alt="Current image" class="image-preview zoomable-image">
                                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View current image</span>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i>Cancel
                                </button>
                                <button type="submit" form="editAnnouncementForm{{ $ann->id }}" class="btn btn-primary" id="submitEditForm{{ $ann->id }}">
                                    <i class="fas fa-save"></i>Update Announcement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($announcements as $ann)
            <div class="modal fade announcement-view-modal" id="viewModal{{ $ann->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-bullhorn"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Announcement Details</h5>
                                    <span class="modal-subtitle">Review the published content and visibility information in one place.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Status</span>
                                    <span class="view-summary-value">
                                        @if($ann->status == 'published') Published
                                        @elseif($ann->status == 'draft') Draft
                                        @else Archived
                                        @endif
                                    </span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Category</span>
                                    <span class="view-summary-value">{{ ucfirst($ann->category) }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Visibility</span>
                                    <span class="view-summary-value">{{ $ann->is_featured ? 'Featured' : 'Standard' }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Views</span>
                                    <span class="view-summary-value">{{ number_format($ann->views) }}</span>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="row g-3 align-items-start">
                                    @if($ann->image)
                                    <div class="col-12 col-lg-4">
                                        <button type="button" class="photo-view-btn w-100" data-click-call="openZoomModal" data-click-args="{{ json_encode([$ann->image_url]) }}" title="View full announcement photo">
                                            <img src="{{ $ann->image_url }}" alt="{{ $ann->title }}" class="modal-image-hero zoomable-image">
                                            <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View larger image</span>
                                        </button>
                                    </div>
                                    @endif

                                    <div class="col-12 {{ $ann->image ? 'col-lg-8' : '' }}">
                                        <h4 class="announcement-view-title">{{ $ann->title }}</h4>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span>
                                            @if($ann->status == 'published')
                                                <span class="badge bg-success-subtle text-success">Published</span>
                                            @elseif($ann->status == 'draft')
                                                <span class="badge bg-warning-subtle text-warning">Draft</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                            @endif
                                            @if($ann->is_featured)
                                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-star"></i>Featured</span>
                                            @endif
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <span class="view-summary-label">Published At</span>
                                                <span class="view-summary-value">{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('F d, Y h:i A') : 'Not published' }}</span>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <span class="view-summary-label">Created At</span>
                                                <span class="view-summary-value">{{ $ann->created_at ? $ann->created_at->format('F d, Y h:i A') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2"></i>Announcement Content</div>
                                <div class="announcement-content-box">{!! nl2br(e($ann->content)) !!}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @admin_can('create_announcements')
            <div class="modal fade announcement-form-modal notification-modal" id="bulkNotifyResidentsModal" tabindex="-1" aria-labelledby="bulkNotifyResidentsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('admin.announcements.bulkNotifyResidents') }}"
                              id="bulkNotifyResidentsForm"
                              data-submit-call="confirmBulkResidentNotification" data-submit-pass="event">
                            @csrf
                            <input type="hidden" name="form_type" value="bulk_notify">

                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-paper-plane"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title" id="bulkNotifyResidentsModalLabel">Notify Residents</h5>
                                        <span class="modal-subtitle">Choose recipients, compose the message, review delivery estimates, then send.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Recipients &amp; Delivery</h6>
                                            <p>Choose the delivery channel and which residents should receive the notice.</p>
                                        </div>
                                    </div>

                                    <div class="notification-summary-grid mb-3">
                                        <div class="notification-summary-card">
                                            <span>Total residents</span>
                                            <strong>{{ number_format($residentNotificationStats['all'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>With email</span>
                                            <strong>{{ number_format($residentNotificationStats['email'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>With mobile</span>
                                            <strong>{{ number_format($residentNotificationStats['sms'] ?? 0) }}</strong>
                                        </div>
                                        <div class="notification-summary-card">
                                            <span>Verified</span>
                                            <strong>{{ number_format($residentNotificationStats['verified'] ?? 0) }}</strong>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6">
                                            <label class="form-label"><i class="fas fa-paper-plane"></i>Send Through <span class="text-danger">*</span></label>
                                            <select name="channel" id="notifyChannel" class="form-select" required>
                                                <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS only</option>
                                                <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email only</option>
                                                <option value="both" {{ old('channel') === 'both' ? 'selected' : '' }}>SMS and Email</option>
                                            </select>
                                            <small class="field-hint">Choose SMS for urgent notices. Use email for longer details.</small>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <label class="form-label"><i class="fas fa-users"></i>Audience <span class="text-danger">*</span></label>
                                            <select name="audience" id="notifyAudience" class="form-select" required>
                                                <option value="all" {{ old('audience') === 'all' ? 'selected' : '' }}>All residents</option>
                                                <option value="selected" {{ old('audience') === 'selected' ? 'selected' : '' }}>Selected residents only</option>
                                                <option value="verified" {{ old('audience') === 'verified' ? 'selected' : '' }}>Verified residents</option>
                                                <option value="unverified" {{ old('audience') === 'unverified' ? 'selected' : '' }}>Unverified residents</option>
                                                <option value="senior" {{ old('audience') === 'senior' ? 'selected' : '' }}>Senior citizens</option>
                                                <option value="male" {{ old('audience') === 'male' ? 'selected' : '' }}>Male residents</option>
                                                <option value="female" {{ old('audience') === 'female' ? 'selected' : '' }}>Female residents</option>
                                                <option value="with_valid_id" {{ old('audience') === 'with_valid_id' ? 'selected' : '' }}>Residents with valid ID</option>
                                                <option value="email_ready" {{ old('audience') === 'email_ready' ? 'selected' : '' }}>Residents with email</option>
                                                <option value="sms_ready" {{ old('audience') === 'sms_ready' ? 'selected' : '' }}>Residents with mobile number</option>
                                            </select>
                                            <small class="field-hint">The system skips missing contact details that cannot use the selected channel.</small>
                                        </div>

                                        <div class="col-12" id="selectedResidentsBox" style="display: none;">
                                            <label class="form-label"><i class="fas fa-user-check"></i>Choose Residents <span class="text-danger">*</span></label>

                                            <div class="resident-picker-search mb-2 position-relative">
                                                <i class="fas fa-search"></i>
                                                <input type="text"
                                                       id="residentPickerSearch"
                                                       class="form-control"
                                                       placeholder="Search resident name, email, or mobile number...">
                                            </div>

                                            <div class="resident-picker-list">
                                                @forelse($residentsForNotification ?? [] as $resident)
                                                    @php
                                                        $residentFullName = trim(collect([
                                                            $resident->firstname ?? '',
                                                            $resident->middlename ?? '',
                                                            $resident->lastname ?? '',
                                                            $resident->suffix ?? '',
                                                        ])->filter()->implode(' '));

                                                        $searchText = strtolower($residentFullName . ' ' . ($resident->email ?? '') . ' ' . ($resident->contact ?? ''));
                                                    @endphp

                                                    <label class="resident-picker-item" data-search="{{ $searchText }}">
                                                        <input type="checkbox"
                                                               name="resident_ids[]"
                                                               value="{{ $resident->id }}"
                                                               {{ in_array($resident->id, old('resident_ids', [])) ? 'checked' : '' }}>
                                                        <span>
                                                            <strong>{{ $residentFullName ?: 'Resident #' . $resident->id }}</strong>
                                                            <small>{{ $resident->contact ?: 'No mobile' }} · {{ $resident->email ?: 'No email' }}</small>
                                                        </span>
                                                    </label>
                                                @empty
                                                    <div class="notification-warning-box">
                                                        <i class="fas fa-circle-info"></i>
                                                        <div>No residents found for manual selection.</div>
                                                    </div>
                                                @endforelse
                                            </div>

                                            <small class="field-hint" id="selectedResidentCounter">0 residents selected</small>

                                            @if(session('form_type') === 'bulk_notify')
                                                @error('resident_ids')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">2</div>
                                        <div>
                                            <h6>Compose Message</h6>
                                            <p>Start from a template or write a custom message, then review the subject and message body.</p>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12 col-lg-4">
                                            <label class="form-label"><i class="fas fa-wand-magic-sparkles"></i>Template</label>
                                            <select id="notifyTemplate" class="form-select">
                                                <option value="">Write custom message</option>
                                                <option value="ayuda">Ayuda Distribution</option>
                                                <option value="emergency">Emergency Advisory</option>
                                                <option value="documents">Document Release Reminder</option>
                                                <option value="event">Barangay Event</option>
                                            </select>
                                            <small class="field-hint">Templates remain editable after selection.</small>
                                        </div>

                                        <div class="col-12 col-lg-8">
                                            <label class="form-label"><i class="fas fa-envelope"></i>Email Subject</label>
                                            <input type="text"
                                                   name="subject"
                                                   id="notifySubject"
                                                   class="form-control @if(session('form_type') === 'bulk_notify') @error('subject') is-invalid @enderror @endif"
                                                   value="{{ session('form_type') === 'bulk_notify' ? old('subject') : '' }}"
                                                   placeholder="Example: Ayuda Distribution Notice">
                                            <small class="field-hint">Required only when sending email or both SMS and email.</small>
                                            @if(session('form_type') === 'bulk_notify')
                                                @error('subject')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-message"></i>Message <span class="text-danger">*</span></label>
                                            <textarea name="message"
                                                      id="notifyMessage"
                                                      rows="7"
                                                      class="form-control @if(session('form_type') === 'bulk_notify') @error('message') is-invalid @enderror @endif"
                                                      required
                                                      maxlength="1000"
                                                      placeholder="Write a clear message for residents...">{{ session('form_type') === 'bulk_notify' ? old('message') : '' }}</textarea>

                                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-2">
                                                <small class="field-hint mb-0">Available placeholders: <strong>{name}</strong>, <strong>{firstname}</strong>, <strong>{barangay}</strong></small>
                                                <small class="field-hint mb-0" id="notifyCharCounter">0 / 1000 characters</small>
                                            </div>

                                            @if(session('form_type') === 'bulk_notify')
                                                @error('message')
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
                                            <h6>Review &amp; Send</h6>
                                            <p>Preview the resident message and verify the estimated SMS credit usage before sending.</p>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="notification-preview-box">
                                                <div class="notification-preview-header">
                                                    <span><i class="fas fa-eye"></i> Resident preview</span>
                                                    <span id="notifyPreviewChannel">SMS</span>
                                                </div>
                                                <p id="notifyPreviewText">Your message preview will appear here.</p>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="sms-credit-box"
                                                 id="smsCreditBox"
                                                 data-credit-balance="{{ $semaphoreAccount['credit_balance'] ?? 0 }}">
                                                <div class="sms-credit-main">
                                                    <div>
                                                        <span class="sms-credit-label">Semaphore SMS Credits</span>
                                                        <strong id="smsAvailableCredits">{{ number_format((float) ($semaphoreAccount['credit_balance'] ?? 0), 2) }}</strong>
                                                        <small>
                                                            {{ $semaphoreAccount['success'] ?? false
                                                                ? 'Live balance from Semaphore account.'
                                                                : ($semaphoreAccount['message'] ?? 'Unable to load Semaphore balance.') }}
                                                        </small>
                                                    </div>

                                                    <div class="sms-credit-status" id="smsCreditStatusBadge">Checking estimate</div>
                                                </div>

                                                <div class="sms-credit-details">
                                                    <span>SMS recipients: <strong id="smsEstimatedRecipients">0</strong></span>
                                                    <span>SMS segments: <strong id="smsEstimatedSegments">0</strong></span>
                                                    <span>Credits needed: <strong id="smsEstimatedCredits">0</strong></span>
                                                    <span>After send: <strong id="smsRemainingCredits">0</strong></span>
                                                </div>

                                                <input type="hidden" name="estimated_sms_credits" id="estimatedSmsCreditsInput" value="0">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="notification-warning-box">
                                                <i class="fas fa-circle-info"></i>
                                                <div>
                                                    <strong>Review before sending.</strong>
                                                    Bulk notifications are sent immediately and each result will be saved in notification logs.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>Send Notification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <div class="modal fade image-zoom-modal announcement-view-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-image"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Announcement Photo</h5>
                                    <span class="modal-subtitle">Large preview of the uploaded announcement image.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="zoomed-image" alt="Announcement photo preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script src="{{ asset('js/pages/admin/admin_announcement-part1.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-announcement-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_announcement-dynamic1.js') }}"></script>
            @endif
            </div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/admin/admin_announcement-part2.js') }}"></script>

    <script src="{{ asset('js/pages/admin/admin_announcement-part3.js') }}"></script>

<script src="{{ asset('js/pages/admin/admin_announcement-part4.js') }}"></script>
    @if ($errors->any() && session('form_type') === 'bulk_notify')
<script src="{{ asset('js/pages/admin/admin_announcement-part5.js') }}"></script>
@endif

@if ($errors->any() && session('form_type') === 'bulk_notify')
<script src="{{ asset('js/pages/admin/admin_announcement-part6.js') }}"></script>
@endif

@include('shared.js-config', [
    'id' => 'js-config-admin-admin-announcement-2',
    'data' => [
        'v1' => $smsAudienceCounts ?? [],
        'v2' => $semaphoreAccount['credit_balance'] ?? 0,
    ],
])
<script src="{{ asset('js/pages/admin/admin_announcement-dynamic2.js') }}"></script>
@endpush
