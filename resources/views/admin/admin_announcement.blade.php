@extends('admin.layouts.admin')

@section('title', 'Announcement Management')
@section('page_title', 'Announcement Management')
@section('page_icon', 'fas fa-bullhorn')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/announcements.css') }}">
@endpush

@section('content')
            <div class="announcements-page">

            <!-- Success/Error Messages -->
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



            <section class="announcements-hero">
                <div class="announcements-hero-content">
                    <div class="min-w-0">
                        <div class="announcements-hero-icon"><i class="fas fa-bullhorn"></i></div>
                        <div class="announcements-kicker">Announcement Center</div>
                        <h2>Publish barangay announcements with a cleaner workflow.</h2>
                        <p>Manage public advisories, featured posts, drafts, and archived records using the same compact design used in Projects.</p>
                    </div>
                    <div class="announcements-hero-actions">
                        @admin_can('create_announcements')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            <i class="fas fa-plus"></i>Add Announcement
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                        @admin_can('create_announcements')
                        <button type="button" class="btn btn-light-soft" data-bs-toggle="modal" data-bs-target="#bulkNotifyResidentsModal">
                            <i class="fas fa-paper-plane"></i>Notify Residents
                        </button>
                        @endadmin_can
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 announcements-stats-row">
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
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Published</div>
                            <div class="stat-number">{{ $published_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-globe me-1"></i>Live
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Drafts</div>
                            <div class="stat-number">{{ $draft_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-pen me-1"></i>In progress
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-pen"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label text-muted mb-1">Featured</div>
                            <div class="stat-number">{{ $featured_count ?? 0 }}</div>
                            <small class="text-warning mt-2 d-none d-sm-block">
                                <i class="fas fa-star me-1"></i>Important
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - UX Improved -->
            <div class="announcements-panel announcements-filter-panel">
                <form method="GET" action="{{ route('admin.announcements.index') }}" id="searchForm">
                    <div class="announcements-panel-header">
                        <div class="announcements-panel-title">
                            <div class="announcements-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-bullhorn"></i> Announcement Directory</div>
                                <h3>Search and filter announcements</h3>
                                <p class="panel-copy">Find announcements by title or content, then filter by status, category, or featured visibility.</p>
                            </div>
                        </div>

                        <div class="announcements-panel-actions">
                            @admin_can('create_announcements')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                                <i class="fas fa-plus"></i>Add Announcement
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
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
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

            <!-- Announcements Table - UX Improved -->
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
                                        <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
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
                                                    <button type="button" class="photo-view-btn" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View announcement photo">
                                                        <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="zoomable-image">
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
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_announcements can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $ann->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_announcements permission) -->
                                            @if(!$ann->deleted_at)
                                            @admin_can('update_announcements')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal{{ $ann->id }}" title="Edit Announcement">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif

                                            <!-- Feature/Unfeature (requires feature_announcements permission) -->
                                            @if(!$ann->deleted_at && $ann->status == 'published' && auth('admin')->user()->hasPermission('feature_announcements'))
                                                <form method="POST" action="{{ route('admin.announcements.toggle-feature', $ann->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="{{ $ann->is_featured ? 'Remove Featured' : 'Mark as Featured' }}" onclick="return confirm('{{ $ann->is_featured ? 'Remove featured status?' : 'Mark as featured?' }}')">
                                                        <i class="fas fa-star{{ $ann->is_featured ? '' : '-o' }}"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Archive / Restore / Publish action -->
                                            @admin_can('delete_announcements')
                                            @if($ann->deleted_at)
                                            <form method="POST" action="{{ route('admin.announcements.restore', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this announcement from the deleted bin?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore from deleted bin" aria-label="Restore from deleted bin">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @elseif($ann->status == 'archived')
                                            <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Publish this archived announcement again?')">
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
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Publish again" aria-label="Publish again">
                                                    <i class="fas fa-globe"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $ann->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this announcement?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive" aria-label="Archive">
                                                    <i class="fas fa-box-archive"></i>
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

                    <!-- Pagination - Mobile Responsive -->
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

            <!-- Add Announcement Modal with Validation - Only if user has create_announcements permission -->
            @admin_can('create_announcements')
            <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-bullhorn"></i>
                                <span class="modal-title-stack">
                                    <span>New Announcement</span>
                                    <span class="modal-subtitle">Create a clear public advisory with category, status, publish date, featured setting, and optional image.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" id="addAnnouncementForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                        <small class="field-hint">Use a short title residents can understand quickly.</small>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('content') is-invalid @enderror @endif" 
                                                  name="content" rows="6" placeholder="Write your announcement content here..." required>{{ session('form_type') == 'add' ? old('content') : '' }}</textarea>
                                        <small class="field-hint">Keep the message direct. Include dates, requirements, or contact details when needed.</small>
                                        @if(session('form_type') == 'add')
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('category') is-invalid @enderror @endif" 
                                                name="category" required>
                                            <option value="">Select category</option>
                                            <option value="important" {{ (session('form_type') == 'add' && old('category') == 'important') ? 'selected' : '' }}>Important</option>
                                            <option value="events" {{ (session('form_type') == 'add' && old('category') == 'events') ? 'selected' : '' }}>Events</option>
                                            <option value="health" {{ (session('form_type') == 'add' && old('category') == 'health') ? 'selected' : '' }}>Health</option>
                                            <option value="services" {{ (session('form_type') == 'add' && old('category') == 'services') ? 'selected' : '' }}>Services</option>
                                            <option value="other" {{ (session('form_type') == 'add' && old('category') == 'other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('status') is-invalid @enderror @endif" 
                                                name="status" required>
                                            <option value="">Select status</option>
                                            <option value="published" {{ (session('form_type') == 'add' && old('status') == 'published') ? 'selected' : '' }}>Published</option>
                                            <option value="draft" {{ (session('form_type') == 'add' && old('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                            <option value="archived" {{ (session('form_type') == 'add' && old('status') == 'archived') ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Published Date</label>
                                        <input type="datetime-local" class="form-control @if(session('form_type') == 'add') @error('published_at') is-invalid @enderror @endif" 
                                               name="published_at" value="{{ session('form_type') == 'add' ? old('published_at') : '' }}">
                                        <small class="text-muted">Leave empty for immediate publishing</small>
                                        @if(session('form_type') == 'add')
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Featured</label>
                                        <div class="form-check mt-2 d-flex align-items-center gap-2">
                                            <input type="checkbox" class="form-check-input @if(session('form_type') == 'add') @error('is_featured') is-invalid @enderror @endif" 
                                                   name="is_featured" id="is_featured" value="1" {{ (session('form_type') == 'add' && old('is_featured')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Mark as featured announcement</label>
                                        </div>
                                        @if(session('form_type') == 'add')
                                            @error('is_featured')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Announcement Image</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Featured Image</label>
                                        <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif" 
                                               name="image" accept="image/*" onchange="previewImage(this, 'addImagePreview')">
                                        <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                        @if(session('form_type') == 'add')
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                        <div id="addImagePreview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addAnnouncementForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Announcement Modals with Validation - Only if user has update_announcements permission -->
            @if(auth('admin')->user()->hasPermission('update_announcements'))
                @foreach($announcements as $ann)
                <div class="modal fade" id="editAnnouncementModal{{ $ann->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Announcement</span>
                                        <span class="modal-subtitle">Update the content, visibility, schedule, and image without changing the existing workflow.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.announcements.update', $ann->id) }}" enctype="multipart/form-data" id="editAnnouncementForm{{ $ann->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $ann->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $ann->id ? old('title', $ann->title) : $ann->title }}" required>
                                            <small class="field-hint">Use a clear, resident-friendly title.</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Content <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('content') is-invalid @enderror @endif" 
                                                      name="content" rows="6" required>{{ session('form_type') == 'edit_' . $ann->id ? old('content', $ann->content) : $ann->content }}</textarea>
                                            <small class="field-hint">Review the full message before saving changes.</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('content')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('category') is-invalid @enderror @endif" 
                                                    name="category" required>
                                                <option value="">Select category</option>
                                                <option value="important" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'important' ? 'selected' : '' }}>Important</option>
                                                <option value="events" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'events' ? 'selected' : '' }}>Events</option>
                                                <option value="health" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'health' ? 'selected' : '' }}>Health</option>
                                                <option value="services" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'services' ? 'selected' : '' }}>Services</option>
                                                <option value="other" {{ (session('form_type') == 'edit_' . $ann->id ? old('category', $ann->category) : $ann->category) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $ann->id) @error('status') is-invalid @enderror @endif" 
                                                    name="status" required>
                                                <option value="">Select status</option>
                                                <option value="published" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="archived" {{ (session('form_type') == 'edit_' . $ann->id ? old('status', $ann->status) : $ann->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Published Date</label>
                                            <input type="datetime-local" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('published_at') is-invalid @enderror @endif" 
                                                   name="published_at" value="{{ session('form_type') == 'edit_' . $ann->id ? old('published_at', $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') : ($ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('Y-m-d\TH:i') : '') }}">
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('published_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Featured</label>
                                            <div class="form-check mt-2 d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input @if(session('form_type') == 'edit_' . $ann->id) @error('is_featured') is-invalid @enderror @endif" 
                                                       name="is_featured" id="edit_is_featured_{{ $ann->id }}" value="1" 
                                                       {{ (session('form_type') == 'edit_' . $ann->id ? old('is_featured', $ann->is_featured) : $ann->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edit_is_featured_{{ $ann->id }}">Mark as featured announcement</label>
                                            </div>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('is_featured')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Image -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Announcement Image</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Featured Image (Leave empty to keep current)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $ann->id) @error('image') is-invalid @enderror @endif" 
                                                   name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $ann->id }}')">
                                            <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                            @if(session('form_type') == 'edit_' . $ann->id)
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="editImagePreview{{ $ann->id }}" class="mt-2">
                                                @if($ann->image)
                                                    <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View current photo">
                                                        <img src="{{ asset($ann->image) }}" alt="Current image" class="image-preview zoomable-image">
                                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                                                    </button>
                                                    <small class="d-block text-muted mt-2">Current image</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="editAnnouncementForm{{ $ann->id }}" class="btn btn-primary" id="submitEditForm{{ $ann->id }}">
                                        <i class="fas fa-save me-2"></i>Update Announcement
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($announcements as $ann)
            <div class="modal fade" id="viewModal{{ $ann->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-bullhorn"></i>
                                <span class="modal-title-stack">
                                    <span>Announcement Details</span>
                                    <span class="modal-subtitle">Review the announcement content, metadata, featured status, views, and publish timeline.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                @if($ann->image)
                                <div class="col-12 text-center">
                                    <button type="button" class="photo-view-btn photo-view-main" onclick="openZoomModal('{{ asset($ann->image) }}')" title="View full announcement photo">
                                        <img src="{{ asset($ann->image) }}" alt="{{ $ann->title }}" class="modal-image-hero zoomable-image">
                                        <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                                    </button>
                                </div>
                                @endif
                                
                                <div class="col-12">
                                    <h4 class="fw-bold">{{ $ann->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Category</label>
                                    <p><span class="badge bg-info-subtle text-info">{{ ucfirst($ann->category) }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <p>
                                        @if($ann->status == 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @elseif($ann->status == 'draft')
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Archived</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Featured</label>
                                    <p>
                                        @if($ann->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">Featured</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Views</label>
                                    <p><span class="views-badge"><i class="fas fa-eye me-1"></i>{{ number_format($ann->views) }}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Published At</label>
                                    <p>{{ $ann->published_at ? \Carbon\Carbon::parse($ann->published_at)->format('F d, Y h:i A') : 'Not published' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p>{{ $ann->created_at ? $ann->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Content</label>
                                    <div class="description-box">
                                        {!! nl2br(e($ann->content)) !!}
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

            @admin_can('create_announcements')
            <div class="modal fade" id="bulkNotifyResidentsModal" tabindex="-1" aria-labelledby="bulkNotifyResidentsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST"
                            action="{{ route('admin.announcements.bulkNotifyResidents') }}"
                            id="bulkNotifyResidentsForm"
                            onsubmit="return confirmBulkResidentNotification(event)">
                            @csrf
                            <input type="hidden" name="form_type" value="bulk_notify">

                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkNotifyResidentsModalLabel">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="modal-title-stack">
                                        <span>Bulk Notify Residents</span>
                                        <span class="modal-subtitle">
                                            Send one clear SMS or email blast to residents for ayuda, emergency advisories, schedules, and barangay reminders.
                                        </span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
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

                                <div class="sms-credit-box mb-3"
                                    id="smsCreditBox"
                                    data-credit-balance="{{ $semaphoreAccount['credit_balance'] ?? 0 }}">
                                    <div class="sms-credit-main">
                                        <div>
                                            <span class="sms-credit-label">Semaphore SMS Credits</span>
                                            <strong id="smsAvailableCredits">
                                                {{ number_format((float) ($semaphoreAccount['credit_balance'] ?? 0), 2) }}
                                            </strong>
                                            <small>
                                                {{ $semaphoreAccount['success'] ?? false
                                                    ? 'Live balance from Semaphore account.'
                                                    : ($semaphoreAccount['message'] ?? 'Unable to load Semaphore balance.') }}
                                            </small>
                                        </div>

                                        <div class="sms-credit-status" id="smsCreditStatusBadge">
                                            Checking estimate
                                        </div>
                                    </div>

                                    <div class="sms-credit-details">
                                        <span>SMS recipients: <strong id="smsEstimatedRecipients">0</strong></span>
                                        <span>SMS segments: <strong id="smsEstimatedSegments">0</strong></span>
                                        <span>Credits needed: <strong id="smsEstimatedCredits">0</strong></span>
                                        <span>After send: <strong id="smsRemainingCredits">0</strong></span>
                                    </div>

                                    <input type="hidden" name="estimated_sms_credits" id="estimatedSmsCreditsInput" value="0">
                                </div>

                                <div class="row g-3 modal-form-grid">
                                    <div class="col-12">
                                        <div class="modal-section-title">
                                            <i class="fas fa-sliders"></i>Notification setup
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Send through <span class="text-danger">*</span></label>
                                        <select name="channel" id="notifyChannel" class="form-select" required>
                                            <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS only</option>
                                            <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email only</option>
                                            <option value="both" {{ old('channel') === 'both' ? 'selected' : '' }}>SMS and Email</option>
                                        </select>
                                        <small class="field-hint">Choose SMS for urgent notices. Use email for longer details.</small>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Audience <span class="text-danger">*</span></label>
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
                                        <small class="field-hint">The system automatically skips missing email/mobile contacts depending on the channel.</small>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <label class="form-label">Template</label>
                                        <select id="notifyTemplate" class="form-select">
                                            <option value="">Write custom message</option>
                                            <option value="ayuda">Ayuda Distribution</option>
                                            <option value="emergency">Emergency Advisory</option>
                                            <option value="documents">Document Release Reminder</option>
                                            <option value="event">Barangay Event</option>
                                        </select>
                                        <small class="field-hint">Templates are editable after selection.</small>
                                    </div>

                                    <div class="col-12" id="selectedResidentsBox" style="display: none;">
                                        <label class="form-label">Choose residents <span class="text-danger">*</span></label>

                                        <input type="text"
                                            id="residentPickerSearch"
                                            class="form-control mb-2"
                                            placeholder="Search resident name, email, or mobile number...">

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
                                                        <small>
                                                            {{ $resident->contact ?: 'No mobile' }} · {{ $resident->email ?: 'No email' }}
                                                        </small>
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

                                    <div class="col-12">
                                        <label class="form-label">Email subject</label>
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
                                        <label class="form-label">Message <span class="text-danger">*</span></label>
                                        <textarea name="message"
                                                id="notifyMessage"
                                                rows="7"
                                                class="form-control @if(session('form_type') === 'bulk_notify') @error('message') is-invalid @enderror @endif"
                                                required
                                                maxlength="1000"
                                                placeholder="Write a clear message for residents...">{{ session('form_type') === 'bulk_notify' ? old('message') : '' }}</textarea>

                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-2">
                                            <small class="field-hint mb-0">
                                                Available placeholders: <strong>{name}</strong>, <strong>{firstname}</strong>, <strong>{barangay}</strong>
                                            </small>
                                            <small class="field-hint mb-0" id="notifyCharCounter">0 / 1000 characters</small>
                                        </div>

                                        @if(session('form_type') === 'bulk_notify')
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <div class="notification-preview-box">
                                            <div class="notification-preview-header">
                                                <span><i class="fas fa-eye"></i> Resident preview</span>
                                                <span id="notifyPreviewChannel">SMS</span>
                                            </div>
                                            <p id="notifyPreviewText">
                                                Your message preview will appear here.
                                            </p>
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

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">
                                    <i class="fas fa-xmark"></i>Cancel
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

            <!-- Global Image Zoom Modal -->
            <div class="modal fade image-zoom-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-image"></i>
                                <span class="modal-title-stack">
                                    <span>Announcement Photo</span>
                                    <span class="modal-subtitle">View the uploaded image in a larger preview.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="zoomed-image" alt="Announcement photo preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var addModal = new bootstrap.Modal(document.getElementById('addAnnouncementModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal{{ $editId }}'));
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
            const checkboxes = document.querySelectorAll('.announcement-checkbox:checked');
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
                        text: 'Please select at least one announcement to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one announcement to archive.');
                }
                return;
            }

            // Clear old ids so repeated operations do not duplicate inputs.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected announcement(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} announcement(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof refreshAnnouncementSelection === 'function') {
                refreshAnnouncementSelection();
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

        // Preview image before upload
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <button type="button" class="photo-view-btn w-100" onclick="openZoomModal('${e.target.result}')" title="View selected photo">
                            <img src="${e.target.result}" class="announcement-image-preview zoomable-image" alt="Selected photo preview">
                            <span class="zoom-hint"><i class="fas fa-magnifying-glass-plus"></i>View photo</span>
                        </button>`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.announcement-checkbox');
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
            const zoomModalEl = document.getElementById('globalImageZoomModal');

            if (!zoomImage || !zoomModalEl || !imageUrl) {
                return;
            }

            zoomImage.src = imageUrl;
            const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalEl);
            zoomModal.show();
        }
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

        function attachAnnouncementValidation(form) {
            if (!form) return;
            const title    = form.querySelector('[name="title"]');
            const content  = form.querySelector('[name="content"]');
            const category = form.querySelector('[name="category"]');
            const status   = form.querySelector('[name="status"]');
            const image    = form.querySelector('[name="image"]');

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (content) {
                content.addEventListener('input', function() { cf(this); }, {once: true});
                content.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Content is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Content must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (category) category.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a category.'); else cf(this);
            });
            if (status) status.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a status.'); else cf(this);
            });
            if (image) image.addEventListener('change', function() {
                if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                    sf(this, 'Image must be less than 5MB.'); this.value = '';
                } else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                }
                if (content) {
                    if (!content.value.trim()) { sf(content, 'Content is required.'); valid = false; }
                    else if (content.value.trim().length < 10) { sf(content, 'Content must be at least 10 characters.'); valid = false; }
                }
                if (category && !category.value) { sf(category, 'Please select a category.'); valid = false; }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); valid = false;
                }
                if (!valid) e.preventDefault();
            });
        }

        attachAnnouncementValidation(document.getElementById('addAnnouncementForm'));
        document.querySelectorAll('[id^="editAnnouncementForm"]').forEach(attachAnnouncementValidation);
    });
    </script>

    <!-- SweetAlert2 for better alerts (optional) -->
<script>
        function refreshAnnouncementSelection() {
            const checkboxes = Array.from(document.querySelectorAll('.announcement-checkbox'));
            const checked = checkboxes.filter(cb => cb.checked);
            const selectAll = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedAnnouncementsCount');

            if (selectAll) {
                selectAll.checked = checked.length > 0 && checked.length === checkboxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            if (selectedCount) {
                selectedCount.innerHTML = checked.length > 0
                    ? `<i class="fas fa-square-check me-1"></i>${checked.length} announcement${checked.length === 1 ? '' : 's'} selected`
                    : '<i class="fas fa-square-check me-1"></i>No announcements selected';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.announcement-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', refreshAnnouncementSelection);
            });
            refreshAnnouncementSelection();
        });
    </script>
    @if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bulkNotifyModal = new bootstrap.Modal(document.getElementById('bulkNotifyResidentsModal'));
        bulkNotifyModal.show();
    });
</script>
@endif

@if ($errors->any() && session('form_type') === 'bulk_notify')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('bulkNotifyResidentsModal');
    if (modalEl) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
});
</script>
@endif

<script>
window.smsAudienceCounts = @json($smsAudienceCounts ?? []);
window.semaphoreCreditBalance = Number(@json($semaphoreAccount['credit_balance'] ?? 0));

document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('notifyTemplate');
    const subjectInput = document.getElementById('notifySubject');
    const messageInput = document.getElementById('notifyMessage');
    const channelSelect = document.getElementById('notifyChannel');
    const audienceSelect = document.getElementById('notifyAudience');
    const counter = document.getElementById('notifyCharCounter');
    const previewText = document.getElementById('notifyPreviewText');
    const previewChannel = document.getElementById('notifyPreviewChannel');
    const selectedResidentsBox = document.getElementById('selectedResidentsBox');
    const residentPickerSearch = document.getElementById('residentPickerSearch');
    const selectedResidentCounter = document.getElementById('selectedResidentCounter');

    const smsEstimatedRecipients = document.getElementById('smsEstimatedRecipients');
    const smsEstimatedSegments = document.getElementById('smsEstimatedSegments');
    const smsEstimatedCredits = document.getElementById('smsEstimatedCredits');
    const smsRemainingCredits = document.getElementById('smsRemainingCredits');
    const smsCreditStatusBadge = document.getElementById('smsCreditStatusBadge');
    const estimatedSmsCreditsInput = document.getElementById('estimatedSmsCreditsInput');

    const templates = {
        ayuda: {
            subject: 'Ayuda Distribution Notice',
            message: 'Magandang araw, {name}. May ayuda distribution po ang Barangay Hulong Duhat sa [DATE], [TIME], sa [VENUE]. Magdala po ng valid ID at requirements. Salamat po.'
        },
        emergency: {
            subject: 'Emergency Barangay Advisory',
            message: 'Magandang araw, {name}. Paalala mula sa Barangay Hulong Duhat: [WRITE EMERGENCY DETAILS HERE]. Para sa agarang tulong, makipag-ugnayan sa barangay office. Ingat po.'
        },
        documents: {
            subject: 'Barangay Document Release Reminder',
            message: 'Magandang araw, {name}. Ang inyong barangay document/request ay maaari nang i-follow up o kunin sa barangay office. Magdala po ng valid ID at reference number kung meron. Salamat po.'
        },
        event: {
            subject: 'Barangay Event Announcement',
            message: 'Magandang araw, {name}. Inaanyayahan po kayo sa barangay event: [EVENT NAME] sa [DATE], [TIME], sa [VENUE]. Kita-kits po.'
        }
    };

    function smsLength(message) {
        return Array.from(message || '').length;
    }

    function smsSegments(message) {
        const length = smsLength(message);
        return Math.max(1, Math.ceil(length / 160));
    }

    function selectedResidentCount() {
        return document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    }

    function estimatedSmsRecipients() {
        const channel = channelSelect?.value || 'sms';

        if (channel === 'email') {
            return 0;
        }

        const audience = audienceSelect?.value || 'all';

        if (audience === 'selected') {
            return selectedResidentCount();
        }

        return Number(window.smsAudienceCounts?.[audience] || 0);
    }

    function refreshSelectedResidentBox() {
        if (!audienceSelect || !selectedResidentsBox) return;

        selectedResidentsBox.style.display = audienceSelect.value === 'selected' ? 'block' : 'none';
        refreshSelectedResidentCounter();
        refreshSmsCreditEstimate();
    }

    function refreshSelectedResidentCounter() {
        if (!selectedResidentCounter) return;

        const selected = selectedResidentCount();
        selectedResidentCounter.textContent = `${selected} resident${selected === 1 ? '' : 's'} selected`;
    }

    function refreshSmsCreditEstimate() {
        const message = messageInput?.value || '';
        const channel = channelSelect?.value || 'sms';
        const recipients = estimatedSmsRecipients();
        const segments = channel === 'email' ? 0 : smsSegments(message);
        const neededCredits = channel === 'email' ? 0 : recipients * segments * 2;
        const availableCredits = Number(window.semaphoreCreditBalance || 0);
        const remainingCredits = availableCredits - neededCredits;

        if (smsEstimatedRecipients) smsEstimatedRecipients.textContent = recipients.toLocaleString();
        if (smsEstimatedSegments) smsEstimatedSegments.textContent = segments.toLocaleString();
        if (smsEstimatedCredits) smsEstimatedCredits.textContent = neededCredits.toLocaleString();
        if (smsRemainingCredits) smsRemainingCredits.textContent = remainingCredits.toLocaleString();
        if (estimatedSmsCreditsInput) estimatedSmsCreditsInput.value = neededCredits;

        if (smsCreditStatusBadge) {
            smsCreditStatusBadge.classList.remove('is-ok', 'is-danger');

            if (channel === 'email') {
                smsCreditStatusBadge.textContent = 'Email only';
                smsCreditStatusBadge.classList.add('is-ok');
            } else if (recipients > 1000) {
                smsCreditStatusBadge.textContent = 'Over 1,000 SMS limit';
                smsCreditStatusBadge.classList.add('is-danger');
            } else if (neededCredits <= availableCredits) {
                smsCreditStatusBadge.textContent = 'Enough credits';
                smsCreditStatusBadge.classList.add('is-ok');
            } else {
                smsCreditStatusBadge.textContent = 'Insufficient credits';
                smsCreditStatusBadge.classList.add('is-danger');
            }
        }
    }

    function updatePreview() {
        if (!messageInput || !previewText) return;

        const message = messageInput.value.trim();
        const channel = channelSelect?.value || 'sms';

        if (counter) {
            const length = smsLength(messageInput.value);
            const segments = smsSegments(messageInput.value);
            counter.textContent = `${length} / 1000 characters · ${segments} SMS segment${segments === 1 ? '' : 's'}`;
        }

        if (previewChannel) {
            previewChannel.textContent = channel === 'both'
                ? 'SMS + Email'
                : channel.toUpperCase();
        }

        previewText.textContent = message
            ? message
                .replaceAll('{name}', 'Juan Dela Cruz')
                .replaceAll('{firstname}', 'Juan')
                .replaceAll('{lastname}', 'Dela Cruz')
                .replaceAll('{barangay}', 'Hulong Duhat')
            : 'Your message preview will appear here.';

        refreshSmsCreditEstimate();
    }

    templateSelect?.addEventListener('change', function () {
        const selected = templates[this.value];

        if (!selected) return;

        if (subjectInput) subjectInput.value = selected.subject;
        if (messageInput) messageInput.value = selected.message;

        updatePreview();
    });

    residentPickerSearch?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        document.querySelectorAll('.resident-picker-item').forEach(function (item) {
            const haystack = item.dataset.search || '';
            item.style.display = haystack.includes(keyword) ? 'flex' : 'none';
        });
    });

    document.querySelectorAll('input[name="resident_ids[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            refreshSelectedResidentCounter();
            refreshSmsCreditEstimate();
        });
    });

    messageInput?.addEventListener('input', updatePreview);
    channelSelect?.addEventListener('change', updatePreview);
    audienceSelect?.addEventListener('change', refreshSelectedResidentBox);

    refreshSelectedResidentBox();
    updatePreview();
});

function confirmBulkResidentNotification(event) {
    event.preventDefault();

    const form = event.target;
    const channel = document.getElementById('notifyChannel')?.value || 'sms';
    const audienceSelect = document.getElementById('notifyAudience');
    const audience = audienceSelect?.selectedOptions[0]?.textContent || 'selected audience';
    const audienceValue = audienceSelect?.value || 'all';
    const message = document.getElementById('notifyMessage')?.value.trim() || '';
    const selectedResidents = document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    const neededCredits = Number(document.getElementById('estimatedSmsCreditsInput')?.value || 0);
    const availableCredits = Number(window.semaphoreCreditBalance || 0);
    const estimatedRecipients = Number(document.getElementById('smsEstimatedRecipients')?.textContent.replaceAll(',', '') || 0);

    if (!message) {
        alert('Please write a message before sending.');
        return false;
    }

    if (audienceValue === 'selected' && selectedResidents === 0) {
        alert('Please select at least one resident.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && estimatedRecipients > 1000) {
        alert('SMS bulk notification is limited to 1,000 mobile numbers only.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && neededCredits > availableCredits) {
        alert(`Not enough SMS credits. Needed: ${neededCredits}, Available: ${availableCredits}`);
        return false;
    }

    const extraText = audienceValue === 'selected'
        ? `${selectedResidents} selected resident${selectedResidents === 1 ? '' : 's'}`
        : audience;

    const confirmText = `Send this ${channel.toUpperCase()} notification to ${extraText}? Estimated SMS credits: ${neededCredits}.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send bulk notification?',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#667085',
            confirmButtonText: 'Yes, send now'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    if (confirm(confirmText)) {
        form.submit();
    }

    return false;
}
</script>
@endpush
