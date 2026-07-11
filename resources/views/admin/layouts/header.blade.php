@php
    $adminUser = auth('admin')->user();

    $recentAdminNotifications = $adminUser
        ? $adminUser->notifications()->latest()->limit(10)->get()
        : collect();

    $adminUnreadNotificationCount = $adminUser
        ? $adminUser->unreadNotifications()->count()
        : 0;
@endphp

<header class="header d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3 min-w-0">
        <button type="button"
                class="mobile-menu-btn"
                aria-label="Open navigation"
                onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <h1 class="page-title">
            <span class="title-icon"><i class="{{ $pageIcon }}"></i></span>
            <span>{{ $pageTitle }}</span>
        </h1>
    </div>

    <div class="header-controls">
        @hasSection('header_actions')
            <div class="header-actions">
                @yield('header_actions')
            </div>
        @endif

        @if ($adminUser)
            <div class="admin-notification" id="adminNotification">
                <button type="button"
                        class="admin-notification__toggle"
                        id="adminNotificationToggle"
                        aria-label="Open notifications"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <i class="fas fa-bell" aria-hidden="true"></i>

                    <span class="admin-notification__badge"
                          id="adminNotificationBadge"
                          data-count="{{ $adminUnreadNotificationCount }}"
                          @if ($adminUnreadNotificationCount === 0) hidden @endif>
                        {{ $adminUnreadNotificationCount > 99 ? '99+' : $adminUnreadNotificationCount }}
                    </span>
                </button>

                <section class="admin-notification__dropdown"
                         id="adminNotificationDropdown"
                         aria-label="Notifications"
                         hidden>
                    <div class="admin-notification__header">
                        <div>
                            <strong>Notifications</strong>
                            <span id="adminNotificationSummary">
                                {{ $adminUnreadNotificationCount }} unread
                            </span>
                        </div>

                        <div class="admin-notification__actions">
                            <button type="button"
                                    class="admin-notification__refresh"
                                    id="adminNotificationRefresh"
                                    title="Refresh notifications"
                                    aria-label="Refresh notifications">
                                <i class="fas fa-rotate"></i>
                            </button>

                            <button type="button"
                                    class="admin-notification__read-all"
                                    id="adminNotificationReadAll"
                                    @if ($adminUnreadNotificationCount === 0) disabled @endif>
                                Mark all as read
                            </button>
                        </div>
                    </div>

                    <div class="admin-notification__list" id="adminNotificationList">
                        @forelse ($recentAdminNotifications as $notification)
                            @php
                                $notificationData = is_array($notification->data)
                                    ? $notification->data
                                    : [];
                            @endphp

                            <a href="{{ route('admin.notifications.open', ['notification' => $notification->id]) }}"
                               class="admin-notification__item {{ is_null($notification->read_at) ? 'is-unread' : '' }}"
                               data-notification-id="{{ $notification->id }}">
                                <span class="admin-notification__icon" aria-hidden="true">
                                    <i class="{{ $notificationData['icon'] ?? 'fas fa-bell' }}"></i>
                                </span>

                                <span class="admin-notification__content">
                                    <strong>{{ $notificationData['title'] ?? 'New notification' }}</strong>
                                    <span>{{ $notificationData['message'] ?? '' }}</span>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </span>

                                @if (is_null($notification->read_at))
                                    <span class="admin-notification__unread-dot" aria-label="Unread"></span>
                                @endif
                            </a>
                        @empty
                            <div class="admin-notification__empty" id="adminNotificationEmpty">
                                <i class="far fa-bell-slash" aria-hidden="true"></i>
                                <strong>No notifications yet</strong>
                                <span>New resident requests and incident reports will appear here.</span>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="admin-notification-toast-container"
                 id="adminNotificationToastContainer"
                 aria-live="polite"
                 aria-atomic="true"></div>
        @endif

        <button type="button"
                class="theme-toggle-button"
                id="adminThemeToggle"
                aria-label="Switch to dark mode"
                aria-pressed="false"
                title="Switch to dark mode">
            <span class="theme-toggle-icon" aria-hidden="true">
                <i class="fas fa-moon" data-theme-icon></i>
            </span>
            <span class="theme-toggle-text" data-theme-text>Dark mode</span>
        </button>
    </div>
</header>

@if ($adminUser)
    <script>
        window.adminNotificationConfig = {
            adminId: @json($adminUser->getKey()),
            feedUrl: @json(route('admin.notifications.feed')),
            readAllUrl: @json(route('admin.notifications.read-all')),
            readUrlTemplate: @json(route('admin.notifications.read', ['notification' => '__NOTIFICATION_ID__'])),
            openUrlTemplate: @json(route('admin.notifications.open', ['notification' => '__NOTIFICATION_ID__'])),
            csrfToken: @json(csrf_token())
        };
    </script>
@endif
