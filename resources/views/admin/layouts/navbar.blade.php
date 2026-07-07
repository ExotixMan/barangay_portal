<header class="admin-navbar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div>
            <h1 class="page-title mb-0">
                @yield('page-icon')
                @yield('title', 'Admin Panel')
            </h1>

            @hasSection('subtitle')
                <div class="page-subtitle">
                    @yield('subtitle')
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="profile-badge">
            <i class="fas fa-user-circle"></i>

            <div class="profile-text d-none d-md-block">
                <div class="fw-semibold">
                    {{ Auth::guard('admin')->user()->full_name }}
                </div>

                <small>
                    {{ Auth::guard('admin')->user()->getRoleDisplayName() }}
                </small>
            </div>
        </div>
    </div>
</header>