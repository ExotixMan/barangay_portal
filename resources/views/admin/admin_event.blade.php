@extends('admin.layouts.admin')

@section('title', 'Event Management')
@section('page_title', 'Event Management')
@section('page_icon', 'fas fa-calendar')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/events.css') }}">
@endpush

@section('content')
            <div class="events-page">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any() && !session('form_type'))
                <div class="alert alert-danger alert-dismissible fade show events-alert" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <section class="events-hero">
                <div class="events-hero-content">
                    <div class="min-w-0">
                        <div class="events-hero-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="events-kicker">Event Control Center</div>
                        <h2>Manage community events with a cleaner workflow.</h2>
                        <p>Review upcoming and past activities, filter schedules, manage event images, and archive records without cluttering the page.</p>
                    </div>
                    <div class="events-hero-actions">
                        @admin_can('create_events')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-plus"></i>Add Event
                        </button>
                        @endadmin_can
                        <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                            <i class="fas fa-rotate"></i>Reset View
                        </a>
                    </div>
                </div>
            </section>

            <!-- Stats Cards - Mobile Responsive -->
            <div class="row g-3 g-lg-4 mb-4 events-stats-row">
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Total</div>
                            <div class="stat-number">{{ $total_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-calendar me-1"></i>All events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Upcoming</div>
                            <div class="stat-number">{{ $upcoming_count ?? 0 }}</div>
                            <small class="text-success mt-2 d-none d-sm-block">
                                <i class="fas fa-clock me-1"></i>Future events
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Past</div>
                            <div class="stat-number">{{ $past_count ?? 0 }}</div>
                            <small class="text-secondary mt-2 d-none d-sm-block">
                                <i class="fas fa-history me-1"></i>Completed
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div>
                            <div class="stat-label text-muted mb-1">Attendees</div>
                            <div class="stat-number">{{ $total_attendees ?? 0 }}</div>
                            <small class="text-info mt-2 d-none d-sm-block">
                                <i class="fas fa-users me-1"></i>Total participants
                            </small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Compact UX -->
            <div class="events-panel events-filter-panel">
                <form method="GET" action="{{ route('admin.events.index') }}" id="searchForm">
                    <div class="events-panel-header">
                        <div class="events-panel-title">
                            <div class="events-panel-icon"><i class="fas fa-filter"></i></div>
                            <div class="min-w-0">
                                <div class="section-kicker"><i class="fas fa-calendar"></i> Event Directory</div>
                                <h3>Search and filter events</h3>
                                <p class="events-panel-copy">Search by title, description, or location. Use filters only when you need a narrower list.</p>
                            </div>
                        </div>

                        <div class="events-panel-actions">
                            @admin_can('create_events')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i>Add Event
                            </button>
                            @endadmin_can
                            <a href="{{ route('admin.events.index') }}" class="btn btn-light-soft">
                                <i class="fas fa-rotate"></i>Reset
                            </a>
                        </div>
                    </div>

                    <div class="events-filter-body">
                        <div class="events-filter-toolbar">
                            <div class="events-search-group">
                                <div class="events-search-control">
                                    <i class="fas fa-search"></i>
                                    <input type="text"
                                           name="search"
                                           id="globalSearch"
                                           class="form-control"
                                           placeholder="Search title, description, or location..."
                                           value="{{ request('search') }}"
                                           autocomplete="off"
                                           aria-label="Search events">
                                </div>
                                <button class="btn btn-primary events-search-btn" type="submit">
                                    <i class="fas fa-search"></i><span>Search</span>
                                </button>
                            </div>

                            <select name="type" class="form-select" aria-label="Filter by type">
                                <option value="">All types</option>
                                <option value="upcoming" {{ request('type') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('type') == 'past' ? 'selected' : '' }}>Past</option>
                                <option value="deleted" {{ request('type') == 'deleted' ? 'selected' : '' }}>Archived</option>
                            </select>

                            <select name="month" class="form-select" aria-label="Filter by month">
                                <option value="">All months</option>
                                <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                            </select>

                            <button type="submit" class="btn btn-light-soft filter-submit-btn">
                                <i class="fas fa-filter"></i>Apply filters
                            </button>
                        </div>

                        @if(auth('admin')->user()->hasPermission('delete_events'))
                        <div class="events-bulk-row">
                            <div class="selected-count" id="selectedCount">
                                <i class="fas fa-square-check me-1"></i>No events selected
                            </div>
                            <button type="button" onclick="bulkDelete()" class="btn btn-outline-danger" title="Bulk Archive">
                                <i class="fas fa-trash-alt"></i>Archive Selected
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            @if(auth('admin')->user()->hasPermission('delete_events'))
            <form id="bulkForm" method="POST" action="{{ route('admin.events.bulkDelete') }}" class="d-none">
                @csrf
                <div id="bulkIdsContainer"></div>
            </form>
            @endif

            <!-- Events Table - Mobile Responsive with Horizontal Scroll -->
            <div class="events-panel events-table-panel">
                <div class="events-panel-header">
                    <div class="events-panel-title">
                        <div class="events-panel-icon"><i class="fas fa-calendar-days"></i></div>
                        <div class="min-w-0">
                            <div class="section-kicker"><i class="fas fa-list"></i> Results</div>
                            <h3>Event List</h3>
                            <p class="events-panel-copy">View, edit, archive, or restore event records from one clean table.</p>
                        </div>
                    </div>
                    <div class="events-panel-actions">
                        <span class="events-count-pill">
                            <i class="fas fa-calendar"></i>
                            @if(isset($events) && method_exists($events, 'total'))
                                {{ $events->total() }} events
                            @else
                                {{ count($events ?? []) }} events
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="eventsTable">
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
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'event_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Date
                                            @if(request('sort') == 'event_date')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="d-none d-lg-table-cell">Time</th>
                                    <th>
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
                                    <th class="d-none d-md-table-cell">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'attendees', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Attendees
                                            @if(request('sort') == 'attendees')
                                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort text-muted ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="text-decoration-none text-dark">
                                            Type
                                            @if(request('sort') == 'type')
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
                                @forelse($events as $event)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" value="{{ $event->id }}" class="event-checkbox">
                                    </td>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="event-thumb me-2">
                                                @if($event->image)
                                                    <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                                @else
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($event->title, 30) }}</div>
                                                <small class="text-muted d-md-none">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="location-text" title="{{ $event->location }}">
                                            {{ $event->location ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <span class="attendees-badge">
                                            <i class="fas fa-users me-1"></i>{{ number_format($event->attendees ?? 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($event->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 gap-sm-2 justify-content-end">
                                            <!-- View (everyone with view_events can view) -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $event->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Edit (requires update_events permission) -->
                                            @if(!$event->deleted_at)
                                            @admin_can('update_events')
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEventModal{{ $event->id }}" title="Edit Event">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endadmin_can
                                            @endif

                                            <!-- Delete (requires delete_events permission) -->
                                            @admin_can('delete_events')
                                            @if($event->deleted_at)
                                            <form method="POST" action="{{ route('admin.events.restore', $event->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Restore this event?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="fas fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" style="display: inline;" onsubmit="return confirmDelete(event, 'Archive this event?')">
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
                                                <div class="empty-state-icon"><i class="fas fa-calendar-xmark"></i></div>
                                                <h4>No events found</h4>
                                                <p>No event records match the current search and filters.</p>
                                                @admin_can('create_events')
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                                    <i class="fas fa-plus"></i>Add Event
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
                    @if(isset($events) && $events->total() > 0)
                    <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <small class="text-muted order-2 order-md-1">
                            <i class="fas fa-list me-1"></i>
                            {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }}
                        </small>

                        <nav class="order-1 order-md-2">
                            {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Event Modal with Validation - Only if user has create_events permission -->
            @admin_can('create_events')
            <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-plus"></i>
                                <span class="modal-title-stack">
                                    <span>New Event</span>
                                    <span class="modal-subtitle">Add a clear event record with schedule, location, attendees, type, and image.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="addEventForm">
                            @csrf
                            <input type="hidden" name="form_type" value="add">
                                <div class="row g-3">
                                    <!-- Basic Information -->
                                    <div class="col-12">
                                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif" 
                                               name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif" 
                                                  name="description" rows="4" placeholder="Write event description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                        @if(session('form_type') == 'add')
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Date and Time -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @if(session('form_type') == 'add') @error('event_date') is-invalid @enderror @endif" 
                                               name="event_date" value="{{ session('form_type') == 'add' ? old('event_date') : '' }}" required>
                                        @if(session('form_type') == 'add')
                                            @error('event_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('start_time') is-invalid @enderror @endif" 
                                               name="start_time" value="{{ session('form_type') == 'add' ? old('start_time') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" class="form-control @if(session('form_type') == 'add') @error('end_time') is-invalid @enderror @endif" 
                                               name="end_time" value="{{ session('form_type') == 'add' ? old('end_time') : '' }}">
                                        @if(session('form_type') == 'add')
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Location -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif" 
                                               name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="Venue or address" required>
                                        @if(session('form_type') == 'add')
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Attendees</label>
                                        <input type="number" class="form-control @if(session('form_type') == 'add') @error('attendees') is-invalid @enderror @endif" 
                                               name="attendees" value="{{ session('form_type') == 'add' ? old('attendees', 0) : 0 }}" min="0">
                                        @if(session('form_type') == 'add')
                                            @error('attendees')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                        <select class="form-select @if(session('form_type') == 'add') @error('type') is-invalid @enderror @endif" 
                                                name="type" required>
                                            <option value="">Select type</option>
                                            <option value="upcoming" {{ (session('form_type') == 'add' && old('type') == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                                            <option value="past" {{ (session('form_type') == 'add' && old('type') == 'past') ? 'selected' : '' }}>Past</option>
                                        </select>
                                        @if(session('form_type') == 'add')
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 mt-3">
                                        <h6 class="fw-semibold text-primary">Event Image</h6>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Event Banner/Image</label>
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
                                <button type="submit" form="addEventForm" class="btn btn-success" id="submitAddForm">
                                    <i class="fas fa-save me-2"></i>Save Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endadmin_can

            <!-- Edit Event Modals with Validation - Only if user has update_events permission -->
            @if(auth('admin')->user()->hasPermission('update_events'))
                @foreach($events as $event)
                <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-edit"></i>
                                    <span class="modal-title-stack">
                                        <span>Edit Event</span>
                                        <span class="modal-subtitle">Update event details, schedule, location, attendees, type, and image.</span>
                                    </span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" id="editEventForm{{ $event->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="form_type" value="edit_{{ $event->id }}">

                                    <div class="row g-3">
                                        <!-- Basic Information -->
                                        <div class="col-12">
                                            <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('title') is-invalid @enderror @endif" 
                                                   name="title" value="{{ session('form_type') == 'edit_' . $event->id ? old('title', $event->title) : $event->title }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('description') is-invalid @enderror @endif" 
                                                      name="description" rows="4" required>{{ session('form_type') == 'edit_' . $event->id ? old('description', $event->description) : $event->description }}</textarea>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Date and Time -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('event_date') is-invalid @enderror @endif" 
                                                   name="event_date" value="{{ session('form_type') == 'edit_' . $event->id ? old('event_date', $event->event_date->format('Y-m-d')) : $event->event_date->format('Y-m-d') }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('event_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('start_time') is-invalid @enderror @endif" 
                                                   name="start_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('start_time', $event->start_time) : $event->start_time }}">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('start_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">End Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('end_time') is-invalid @enderror @endif" 
                                                   name="end_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('end_time', $event->end_time) : $event->end_time }}">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('end_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Location -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('location') is-invalid @enderror @endif" 
                                                   name="location" value="{{ session('form_type') == 'edit_' . $event->id ? old('location', $event->location) : $event->location }}" required>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Attendees</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('attendees') is-invalid @enderror @endif" 
                                                   name="attendees" value="{{ session('form_type') == 'edit_' . $event->id ? old('attendees', $event->attendees) : $event->attendees }}" min="0">
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('attendees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'edit_' . $event->id) @error('type') is-invalid @enderror @endif" 
                                                    name="type" required>
                                                <option value="">Select type</option>
                                                <option value="upcoming" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                <option value="past" {{ (session('form_type') == 'edit_' . $event->id ? old('type', $event->type) : $event->type) == 'past' ? 'selected' : '' }}>Past</option>
                                            </select>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>

                                        <!-- Image -->
                                        <div class="col-12 mt-3">
                                            <h6 class="fw-semibold text-primary">Event Image</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Event Banner/Image (Leave empty to keep current)</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('image') is-invalid @enderror @endif" 
                                                   name="image" accept="image/*" onchange="previewImage(this, 'editImagePreview{{ $event->id }}')">
                                            <small class="text-muted">Upload image (JPG, PNG, GIF - Max: 5MB)</small>
                                            @if(session('form_type') == 'edit_' . $event->id)
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="editImagePreview{{ $event->id }}" class="mt-2">
                                                @if($event->image)
                                                    <img src="{{ asset($event->image) }}" alt="Current image" class="image-preview">
                                                    <small class="d-block text-muted">Current image</small>
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
                                    <button type="submit" form="editEventForm{{ $event->id }}" class="btn btn-primary" id="submitEditForm{{ $event->id }}">
                                        <i class="fas fa-save me-2"></i>Update Event
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- View Modals (always visible since they just show data) -->
            @foreach($events as $event)
            <div class="modal fade" id="viewModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="modal-title-stack">
                                    <span>Event Details</span>
                                    <span class="modal-subtitle">Review event schedule, location, attendees, image, and description.</span>
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body event-view-body">
                            <div class="row g-3">
                                @if($event->image)
                                <div class="col-12 text-center">
                                    <div class="event-image-card">
                                        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="img-fluid rounded">
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-12">
                                    <h4 class="fw-bold event-detail-title">{{ $event->title }}</h4>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Date</label>
                                    <p><i class="fas fa-calendar me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                                </div>
                                
                                @if($event->start_time && $event->end_time)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Time</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</p>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Location</label>
                                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $event->location }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Attendees</label>
                                    <p><span class="attendees-badge"><i class="fas fa-users me-1"></i>{{ number_format($event->attendees ?? 0) }}</span></p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Event Type</label>
                                    <p>
                                        @if($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created At</label>
                                    <p><i class="fas fa-clock me-2 text-primary"></i>{{ $event->created_at ? $event->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-muted">Description</label>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($event->description)) !!}
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
                    var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
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
                        var editModal = new bootstrap.Modal(document.getElementById('editEventModal{{ $editId }}'));
                        editModal.show();
                    });
                </script>
            @endif
            </div>
@endsection

@push('scripts')
<!-- SweetAlert2 for better alerts -->
<script>
        // Bulk Archive function
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.event-checkbox:checked');
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
                        text: 'Please select at least one event to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one event to archive.');
                }
                return;
            }

            // Clear old ids so repeated bulk operations do not accumulate inputs.
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected event(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} event(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        // Select all checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.event-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
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
                    preview.innerHTML = `<img src="${e.target.result}" class="image-preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        // Update select all checkbox when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
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

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { 
                fb = document.createElement('div'); 
                fb.className = 'invalid-feedback'; 
                el.insertAdjacentElement('afterend', fb); 
            }
            fb.textContent = msg;
        }
        
        function cf(el) { 
            if (el) {
                el.classList.remove('is-invalid');
                // Also remove any existing error message
                const fb = el.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.remove();
            }
        }

        function attachEventValidation(form) {
            if (!form) return;
            const title       = form.querySelector('[name="title"]');
            const description = form.querySelector('[name="description"]');
            const eventDate   = form.querySelector('[name="event_date"]');
            const location    = form.querySelector('[name="location"]');
            const type        = form.querySelector('[name="type"]');
            const image       = form.querySelector('[name="image"]');

            if (title) {
                title.removeEventListener('input', title._handler);
                title._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('input', title._handler);
                
                // Also validate on blur
                title.removeEventListener('blur', title._blurHandler);
                title._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('blur', title._blurHandler);
            }
            
            if (description) {
                description.removeEventListener('input', description._handler);
                description._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('input', description._handler);
                
                description.removeEventListener('blur', description._blurHandler);
                description._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('blur', description._blurHandler);
            }
            
            if (eventDate) {
                eventDate.removeEventListener('change', eventDate._handler);
                eventDate._handler = function() {
                    if (!this.value) {
                        sf(this, 'Event date is required.');
                    } else {
                        cf(this);
                    }
                };
                eventDate.addEventListener('change', eventDate._handler);
            }
            
            if (location) {
                location.removeEventListener('input', location._handler);
                location._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('input', location._handler);
                
                location.removeEventListener('blur', location._blurHandler);
                location._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('blur', location._blurHandler);
            }
            
            if (type) {
                type.removeEventListener('change', type._handler);
                type._handler = function() {
                    if (!this.value) {
                        sf(this, 'Please select an event type.');
                    } else {
                        cf(this);
                    }
                };
                type.addEventListener('change', type._handler);
            }
            
            if (image) {
                image.removeEventListener('change', image._handler);
                image._handler = function() {
                    if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                        sf(this, 'Image must be less than 5MB.'); 
                        this.value = '';
                    } else {
                        cf(this);
                    }
                };
                image.addEventListener('change', image._handler);
            }

            // Remove any existing submit listener
            form.removeEventListener('submit', form._submitHandler);
            
            form._submitHandler = function(e) {
                let valid = true;
                
                // Title validation
                if (title) {
                    if (!title.value.trim()) { 
                        sf(title, 'Title is required.'); 
                        valid = false; 
                    } else if (title.value.trim().length < 3) { 
                        sf(title, 'Title must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(title);
                    }
                }
                
                // Description validation
                if (description) {
                    if (!description.value.trim()) { 
                        sf(description, 'Description is required.'); 
                        valid = false; 
                    } else if (description.value.trim().length < 10) { 
                        sf(description, 'Description must be at least 10 characters.'); 
                        valid = false; 
                    } else {
                        cf(description);
                    }
                }
                
                // Event date validation
                if (eventDate && !eventDate.value) { 
                    sf(eventDate, 'Event date is required.'); 
                    valid = false; 
                } else if (eventDate && eventDate.value) {
                    cf(eventDate);
                }
                
                // Location validation
                if (location) {
                    if (!location.value.trim()) { 
                        sf(location, 'Location is required.'); 
                        valid = false; 
                    } else if (location.value.trim().length < 3) { 
                        sf(location, 'Location must be at least 3 characters.'); 
                        valid = false; 
                    } else {
                        cf(location);
                    }
                }
                
                // Type validation
                if (type && !type.value) { 
                    sf(type, 'Please select an event type.'); 
                    valid = false; 
                } else if (type && type.value) {
                    cf(type);
                }
                
                // Image validation
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); 
                    valid = false;
                } else if (image) {
                    cf(image);
                }
                
                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };
            
            form.addEventListener('submit', form._submitHandler);
        }

        // Attach validation to add form
        attachEventValidation(document.getElementById('addEventForm'));
        
        // Attach validation to all edit forms
        document.querySelectorAll('[id^="editEventForm"]').forEach(attachEventValidation);
        
        // Also handle form reset when modal is closed
        const addModal = document.getElementById('addEventModal');
        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function () {
                const form = document.getElementById('addEventForm');
                if (form) {
                    form.reset();
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        }
        
        // Handle edit modals reset
        document.querySelectorAll('[id^="editEventModal"]').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');
            const checkboxes = () => Array.from(document.querySelectorAll('.event-checkbox'));

            function updateSelectedCount() {
                if (!selectedCount) {
                    return;
                }

                const count = checkboxes().filter(cb => cb.checked).length;
                selectedCount.innerHTML = count ? `<i class="fas fa-square-check me-1"></i>${count} event${count === 1 ? '' : 's'} selected` : '<i class="fas fa-square-check me-1"></i>No events selected';

                if (selectAll) {
                    const total = checkboxes().length;
                    selectAll.checked = total > 0 && count === total;
                    selectAll.indeterminate = count > 0 && count < total;
                }
            }

            checkboxes().forEach(cb => cb.addEventListener('change', updateSelectedCount));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes().forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            updateSelectedCount();

            document.querySelectorAll('.events-alert').forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 250);
                }, 4500);
            });
        });
    </script>
@endpush
