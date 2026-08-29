@extends('admin.layouts.admin')

@section('title', 'Event Management')
@section('page_title', 'Event Management')
@section('page_icon', 'fas fa-calendar')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/events.css') }}">

@endpush

@section('content')
            <div class="events-page">

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
                            <button type="button" data-click-call="bulkDelete" class="btn btn-outline-danger" title="Bulk Archive">
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
                                                <div class="fw-semibold event-title-text">{{ Str::limit($event->title, 30) }}</div>
                                                <div class="event-mobile-meta d-md-none">
                                                    <span><i class="far fa-calendar"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                                    <span><i class="fas fa-location-dot"></i>{{ Str::limit($event->location ?? 'No location', 22) }}</span>
                                                    <span>
                                                        @if($event->deleted_at)
                                                            <i class="fas fa-box-archive"></i>Archived
                                                        @elseif($event->type == 'upcoming')
                                                            <i class="fas fa-clock"></i>Upcoming
                                                        @else
                                                            <i class="fas fa-check"></i>Past
                                                        @endif
                                                    </span>
                                                </div>
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
                                        <div class="action-cluster">
                                            <button type="button"
                                                    class="btn btn-sm action-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $event->id }}"
                                                    title="View event details">
                                                <i class="fas fa-eye"></i><span class="action-label">View</span>
                                            </button>

                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn-sm action-more-toggle icon-only"
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        aria-expanded="false"
                                                        title="More actions">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end action-menu">
                                                    @if(!$event->deleted_at)
                                                        @admin_can('update_events')
                                                        <button type="button"
                                                                class="dropdown-item action-menu-edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editEventModal{{ $event->id }}">
                                                            <i class="fas fa-pen"></i>
                                                            <span>Edit Event</span>
                                                        </button>
                                                        @endadmin_can
                                                    @endif

                                                    @admin_can('delete_events')
                                                    @if($event->deleted_at)
                                                        <form method="POST" action="{{ route('admin.events.restore', $event->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Restore this event?']) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item action-menu-positive">
                                                                <i class="fas fa-rotate-left"></i>
                                                                <span>Restore Event</span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" data-submit-call="confirmDelete" data-submit-pass="event,args" data-submit-args="{{ json_encode(['Archive this event?']) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item action-menu-danger">
                                                                <i class="fas fa-box-archive"></i>
                                                                <span>Archive Event</span>
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

            @admin_can('create_events')
            <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-calendar-plus"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">New Event</h5>
                                    <span class="modal-subtitle">Create the event in three clear sections. Fields marked <span class="text-danger">*</span> are required.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="addEventForm">
                                @csrf
                                <input type="hidden" name="form_type" value="add">

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">1</div>
                                        <div>
                                            <h6>Event Information</h6>
                                            <p>Use a clear title and description that residents can understand quickly.</p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-heading"></i>Event Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('title') is-invalid @enderror @endif"
                                                   name="title" value="{{ session('form_type') == 'add' ? old('title') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @if(session('form_type') == 'add') @error('description') is-invalid @enderror @endif"
                                                      name="description" rows="4" placeholder="Write event description here..." required>{{ session('form_type') == 'add' ? old('description') : '' }}</textarea>
                                            <small class="field-hint">Include the purpose, important instructions, or other details residents should know.</small>
                                            @if(session('form_type') == 'add')
                                                @error('description')
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
                                            <h6>Schedule &amp; Event Details</h6>
                                            <p>Set when and where the event happens, then record its current type and expected attendance.</p>
                                        </div>
                                    </div>

                                    <div class="schedule-grid">
                                        <div>
                                            <label class="form-label"><i class="fas fa-calendar-day"></i>Event Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @if(session('form_type') == 'add') @error('event_date') is-invalid @enderror @endif"
                                                   name="event_date" value="{{ session('form_type') == 'add' ? old('event_date') : '' }}" required>
                                            @if(session('form_type') == 'add')
                                                @error('event_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-clock"></i>Start Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'add') @error('start_time') is-invalid @enderror @endif"
                                                   name="start_time" value="{{ session('form_type') == 'add' ? old('start_time') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('start_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-clock"></i>End Time</label>
                                            <input type="time" class="form-control @if(session('form_type') == 'add') @error('end_time') is-invalid @enderror @endif"
                                                   name="end_time" value="{{ session('form_type') == 'add' ? old('end_time') : '' }}">
                                            @if(session('form_type') == 'add')
                                                @error('end_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>

                                    <div class="details-grid mt-3">
                                        <div>
                                            <label class="form-label"><i class="fas fa-location-dot"></i>Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @if(session('form_type') == 'add') @error('location') is-invalid @enderror @endif"
                                                   name="location" value="{{ session('form_type') == 'add' ? old('location') : '' }}" placeholder="Venue or address" required>
                                            @if(session('form_type') == 'add')
                                                @error('location')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-users"></i>Attendees</label>
                                            <input type="number" class="form-control @if(session('form_type') == 'add') @error('attendees') is-invalid @enderror @endif"
                                                   name="attendees" value="{{ session('form_type') == 'add' ? old('attendees', 0) : 0 }}" min="0">
                                            @if(session('form_type') == 'add')
                                                @error('attendees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                        </div>
                                        <div>
                                            <label class="form-label"><i class="fas fa-tag"></i>Event Type <span class="text-danger">*</span></label>
                                            <select class="form-select @if(session('form_type') == 'add') @error('type') is-invalid @enderror @endif" name="type" required>
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
                                    </div>
                                </div>

                                <div class="form-step-card">
                                    <div class="form-section-heading">
                                        <div class="section-step">3</div>
                                        <div>
                                            <h6>Event Image</h6>
                                            <p>Add an optional banner or photo so residents can recognize the event more easily.</p>
                                        </div>
                                    </div>
                                    <div class="upload-card">
                                        <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                        <div class="flex-grow-1 min-w-0">
                                            <label class="form-label">Event Banner/Image</label>
                                            <input type="file" class="form-control @if(session('form_type') == 'add') @error('image') is-invalid @enderror @endif"
                                                   name="image" accept="image/*" data-change-call="previewImage" data-change-pass="self,args" data-change-args="{{ json_encode(['addImagePreview']) }}">
                                            <small class="field-hint">JPG, PNG, or GIF. Maximum file size: 5MB.</small>
                                            @if(session('form_type') == 'add')
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            @endif
                                            <div id="addImagePreview"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>Cancel
                            </button>
                            <button type="submit" form="addEventForm" class="btn btn-success" id="submitAddForm">
                                <i class="fas fa-save"></i>Save Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endadmin_can

            @if(auth('admin')->user()->hasPermission('update_events'))
                @foreach($events as $event)
                <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-heading">
                                    <div class="modal-heading-icon"><i class="fas fa-pen"></i></div>
                                    <div class="modal-heading-copy">
                                        <h5 class="modal-title">Edit Event</h5>
                                        <span class="modal-subtitle">Update the event information without changing the existing event workflow.</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" id="editEventForm{{ $event->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="form_type" value="edit_{{ $event->id }}">

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">1</div>
                                            <div>
                                                <h6>Event Information</h6>
                                                <p>Review the title and description shown to residents.</p>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-heading"></i>Event Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('title') is-invalid @enderror @endif"
                                                       name="title" value="{{ session('form_type') == 'edit_' . $event->id ? old('title', $event->title) : $event->title }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label"><i class="fas fa-align-left"></i>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('description') is-invalid @enderror @endif"
                                                          name="description" rows="4" required>{{ session('form_type') == 'edit_' . $event->id ? old('description', $event->description) : $event->description }}</textarea>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('description')
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
                                                <h6>Schedule &amp; Event Details</h6>
                                                <p>Keep the event date, time, venue, attendance, and type accurate.</p>
                                            </div>
                                        </div>

                                        <div class="schedule-grid">
                                            <div>
                                                <label class="form-label"><i class="fas fa-calendar-day"></i>Event Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('event_date') is-invalid @enderror @endif"
                                                       name="event_date" value="{{ session('form_type') == 'edit_' . $event->id ? old('event_date', $event->event_date->format('Y-m-d')) : $event->event_date->format('Y-m-d') }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('event_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-clock"></i>Start Time</label>
                                                <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('start_time') is-invalid @enderror @endif"
                                                       name="start_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('start_time', $event->start_time) : $event->start_time }}">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('start_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-clock"></i>End Time</label>
                                                <input type="time" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('end_time') is-invalid @enderror @endif"
                                                       name="end_time" value="{{ session('form_type') == 'edit_' . $event->id ? old('end_time', $event->end_time) : $event->end_time }}">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('end_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>

                                        <div class="details-grid mt-3">
                                            <div>
                                                <label class="form-label"><i class="fas fa-location-dot"></i>Location <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('location') is-invalid @enderror @endif"
                                                       name="location" value="{{ session('form_type') == 'edit_' . $event->id ? old('location', $event->location) : $event->location }}" required>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('location')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-users"></i>Attendees</label>
                                                <input type="number" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('attendees') is-invalid @enderror @endif"
                                                       name="attendees" value="{{ session('form_type') == 'edit_' . $event->id ? old('attendees', $event->attendees) : $event->attendees }}" min="0">
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('attendees')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div>
                                                <label class="form-label"><i class="fas fa-tag"></i>Event Type <span class="text-danger">*</span></label>
                                                <select class="form-select @if(session('form_type') == 'edit_' . $event->id) @error('type') is-invalid @enderror @endif" name="type" required>
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
                                        </div>
                                    </div>

                                    <div class="form-step-card">
                                        <div class="form-section-heading">
                                            <div class="section-step">3</div>
                                            <div>
                                                <h6>Event Image</h6>
                                                <p>Upload a replacement only when the current event image needs to change.</p>
                                            </div>
                                        </div>
                                        <div class="upload-card">
                                            <div class="upload-card-icon"><i class="fas fa-image"></i></div>
                                            <div class="flex-grow-1 min-w-0">
                                                <label class="form-label">Event Banner/Image</label>
                                                <input type="file" class="form-control @if(session('form_type') == 'edit_' . $event->id) @error('image') is-invalid @enderror @endif"
                                                       name="image" accept="image/*" data-change-call="previewImage" data-change-pass="self,args" data-change-args="{{ json_encode(['editImagePreview' . $event->id]) }}">
                                                <small class="field-hint">Leave empty to keep the current image. JPG, PNG, or GIF; maximum 5MB.</small>
                                                @if(session('form_type') == 'edit_' . $event->id)
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                @endif
                                                <div id="editImagePreview{{ $event->id }}">
                                                    @if($event->image)
                                                        <button type="button" class="image-preview-button" data-click-call="openZoomModal" data-click-args="{{ json_encode([asset($event->image)]) }}" title="View current event image">
                                                            <img src="{{ asset($event->image) }}" alt="Current image" class="image-preview">
                                                        </button>
                                                        <small class="field-hint">Current image — click to preview.</small>
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
                                <button type="submit" form="editEventForm{{ $event->id }}" class="btn btn-primary" id="submitEditForm{{ $event->id }}">
                                    <i class="fas fa-save"></i>Update Event
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @foreach($events as $event)
            <div class="modal fade" id="viewModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-calendar-days"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Event Details</h5>
                                    <span class="modal-subtitle">Review the event schedule, venue, attendance, status, image, and description.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body event-view-body">
                            <div class="event-view-hero">
                                @if($event->image)
                                    <button type="button" class="image-preview-button" data-click-call="openZoomModal" data-click-args="{{ json_encode([asset($event->image)]) }}" title="View event image">
                                        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}">
                                    </button>
                                @endif
                                <div class="event-view-title-block">
                                    <h4 class="event-detail-title">{{ $event->title }}</h4>
                                    <div class="event-view-meta">
                                        @if($event->deleted_at)
                                            <span class="badge bg-danger-subtle text-danger">Archived</span>
                                        @elseif($event->type == 'upcoming')
                                            <span class="badge bg-success-subtle text-success">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Past</span>
                                        @endif
                                        <span class="text-muted small"><i class="fas fa-location-dot me-1"></i>{{ $event->location }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="view-summary">
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Event Date</span>
                                    <span class="view-summary-value">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Event Time</span>
                                    <span class="view-summary-value">
                                        @if($event->start_time && $event->end_time)
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @else
                                            Not specified
                                        @endif
                                    </span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Attendees</span>
                                    <span class="view-summary-value">{{ number_format($event->attendees ?? 0) }}</span>
                                </div>
                                <div class="view-summary-item">
                                    <span class="view-summary-label">Created</span>
                                    <span class="view-summary-value">{{ $event->created_at ? $event->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="detail-section-card">
                                <div class="detail-section-title mb-2"><i class="fas fa-align-left me-2 text-primary"></i>Description</div>
                                <div class="event-description-box">{!! nl2br(e($event->description)) !!}</div>
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

            <div class="modal fade image-zoom-modal" id="globalImageZoomModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-heading">
                                <div class="modal-heading-icon"><i class="fas fa-image"></i></div>
                                <div class="modal-heading-copy">
                                    <h5 class="modal-title">Image Preview</h5>
                                    <span class="modal-subtitle">View the uploaded event image at a larger size.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="zoomedImage" class="img-fluid" alt="Uploaded image preview">
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any() && session('form_type') == 'add')
            <script src="{{ asset('js/pages/admin/admin_event-part1.js') }}"></script>
            @endif

            @if ($errors->any() && session('form_type') && Str::startsWith(session('form_type'), 'edit_'))
                @php
                    $editId = str_replace('edit_', '', session('form_type'));
                @endphp
                @include('shared.js-config', [
    'id' => 'js-config-admin-admin-event-1',
    'data' => [
        'v1' => e($editId),
    ],
])
<script src="{{ asset('js/pages/admin/admin_event-dynamic1.js') }}"></script>
            @endif
            </div>
@endsection

@push('scripts')

<script src="{{ asset('js/pages/admin/admin_event-part2.js') }}"></script>

    <script src="{{ asset('js/pages/admin/admin_event-part3.js') }}"></script>
@endpush
