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
            <script>
                window.adminNotificationConfig = {
                    adminId: @json($adminUser->getKey()),

                    feedUrl: @json(
                        route('admin.notifications.feed', [], false)
                    ),

                    readAllUrl: @json(
                        route('admin.notifications.read-all', [], false)
                    ),

                    readUrlTemplate: @json(
                        route(
                            'admin.notifications.read',
                            ['notification' => '__NOTIFICATION_ID__'],
                            false
                        )
                    ),

                    openUrlTemplate: @json(
                        route(
                            'admin.notifications.open',
                            ['notification' => '__NOTIFICATION_ID__'],
                            false
                        )
                    ),

                    csrfToken: @json(csrf_token())
                };
            </script>
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
