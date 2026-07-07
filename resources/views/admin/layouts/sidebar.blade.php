<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-left">
            <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo">

            <div class="brand-text">
                <div class="brand-title">BISIG Hulong Duhat</div>
                <div class="brand-sub">Administrator</div>
            </div>
        </div>

        <button type="button" class="sidebar-toggle-btn" id="collapseBtn" title="Toggle sidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <nav class="sidebar-menu">
        @admin_can('view_dashboard')
            <a href="{{ route('admin.dashboard.index') }}"
               class="{{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        @endadmin_can

        <div class="menu-section">Administrative</div>

        @php
            $hasRegistryAccess = auth('admin')->user()->hasAnyPermission([
                'view_residents',
                'view_residency',
                'view_indigency'
            ]);

            $registryActive = request()->routeIs(
                'admin.residents.*',
                'admin.residency.*',
                'admin.indigency.*'
            );
        @endphp

        @if($hasRegistryAccess)
            <button type="button" class="dropdown-btn {{ $registryActive ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Registry</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </button>

            <div class="submenu {{ $registryActive ? 'show' : '' }}">
                @admin_can('view_residents')
                    <a href="{{ route('admin.residents.index') }}"
                       class="{{ request()->routeIs('admin.residents.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Residents</span>
                    </a>
                @endadmin_can

                @admin_can('view_residency')
                    <a href="{{ route('admin.residency.index') }}"
                       class="{{ request()->routeIs('admin.residency.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Residency Applications</span>
                    </a>
                @endadmin_can

                @admin_can('view_indigency')
                    <a href="{{ route('admin.indigency.index') }}"
                       class="{{ request()->routeIs('admin.indigency.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>Indigency</span>
                    </a>
                @endadmin_can
            </div>
        @endif

        <div class="menu-section">Legal</div>

        @php
            $hasRecordsAccess = auth('admin')->user()->hasAnyPermission([
                'view_clearance',
                'view_blotter'
            ]);

            $recordsActive = request()->routeIs(
                'admin.clearance.*',
                'admin.blotter.*'
            );
        @endphp

        @if($hasRecordsAccess)
            <button type="button" class="dropdown-btn {{ $recordsActive ? 'active' : '' }}">
                <i class="fas fa-scale-balanced"></i>
                <span>Records</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </button>

            <div class="submenu {{ $recordsActive ? 'show' : '' }}">
                @admin_can('view_clearance')
                    <a href="{{ route('admin.clearance.index') }}"
                       class="{{ request()->routeIs('admin.clearance.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>Clearances</span>
                    </a>
                @endadmin_can

                @admin_can('view_blotter')
                    <a href="{{ route('admin.blotter.index') }}"
                       class="{{ request()->routeIs('admin.blotter.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Incident Reports</span>
                    </a>
                @endadmin_can
            </div>
        @endif

        <div class="menu-section">Community</div>

        @php
            $hasCommunityAccess = auth('admin')->user()->hasAnyPermission([
                'view_announcements',
                'view_events',
                'view_projects'
            ]);

            $communityActive = request()->routeIs(
                'admin.announcements.*',
                'admin.events.*',
                'admin.projects.*'
            );
        @endphp

        @if($hasCommunityAccess)
            <button type="button" class="dropdown-btn {{ $communityActive ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Community</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </button>

            <div class="submenu {{ $communityActive ? 'show' : '' }}">
                @admin_can('view_announcements')
                    <a href="{{ route('admin.announcements.index') }}"
                       class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                @endadmin_can

                @admin_can('view_events')
                    <a href="{{ route('admin.events.index') }}"
                       class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        <span>Events</span>
                    </a>
                @endadmin_can

                @admin_can('view_projects')
                    <a href="{{ route('admin.projects.index') }}"
                       class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                @endadmin_can
            </div>
        @endif

        <div class="menu-section">System</div>

        @admin_can('view_users')
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Users & Roles</span>
            </a>
        @endadmin_can

        @admin_can('manage_chatbot')
            <a href="{{ route('admin.chatbot.index') }}"
               class="{{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}">
                <i class="fas fa-robot"></i>
                <span>Chatbot</span>
            </a>
        @endadmin_can

        @admin_can('view_backup')
            <a href="{{ route('admin.backup.index') }}"
               class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i>
                <span>Backup Settings</span>
            </a>
        @endadmin_can
    </nav>

    <div class="sidebar-account">
        <div class="sidebar-user-card">
            <div class="sidebar-user-icon">
                <i class="fas fa-user-circle"></i>
            </div>

            <div class="sidebar-user-details">
                <div class="sidebar-user-name">
                    {{ Auth::guard('admin')->user()->full_name }}
                </div>

                <div class="sidebar-user-role">
                    {{ Auth::guard('admin')->user()->getRoleDisplayName() }}
                </div>

                <div class="sidebar-user-email">
                    {{ Auth::guard('admin')->user()->email }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}" class="sidebar-logout-form">
            @csrf

            <button type="submit" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-logout-text">Logout</span>
            </button>
        </form>
    </div>
</aside>