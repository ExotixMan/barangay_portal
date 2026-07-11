@php
    $admin = Auth::guard('admin')->user();

    $registryOpen = request()->routeIs(
        'admin.residents.*',
        'admin.residency.*',
        'admin.indigency.*'
    );

    $recordsOpen = request()->routeIs(
        'admin.clearance.*',
        'admin.blotter.*'
    );

    $communityOpen = request()->routeIs(
        'admin.announcements.*',
        'admin.events.*',
        'admin.projects.*'
    );

    $hasRegistryAccess = $admin?->hasAnyPermission([
        'view_residents',
        'view_residency',
        'view_indigency',
    ]) ?? false;

    $hasRecordsAccess = $admin?->hasAnyPermission([
        'view_clearance',
        'view_blotter',
    ]) ?? false;

    $hasCommunityAccess = $admin?->hasAnyPermission([
        'view_announcements',
        'view_events',
        'view_projects',
    ]) ?? false;
@endphp

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<aside class="sidebar" id="sidebar" onclick="handleSidebarClick(event)">
    <div class="brand">
        <div class="brand-left">
            <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo">
            <div class="brand-text">
                <div class="brand-title">BISIG Hulong Duhat</div>
                <div class="brand-sub">Administrator</div>
            </div>
        </div>
        <i class="fas fa-chevron-left toggle-btn"
           id="collapseBtn"
           title="Close sidebar"
           onclick="handleToggleButtonClick(event)"></i>
    </div>

    @admin_can('view_dashboard')
        <a href="{{ route('admin.dashboard.index') }}"
           class="{{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}"
           onclick="handleLinkClick(event, this)">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
    @endadmin_can

    <div class="menu-section">Administrative</div>

    @if($hasRegistryAccess)
        <div class="dropdown-btn {{ $registryOpen ? 'active' : '' }}"
             onclick="handleDropdownClick(event, this)">
            <i class="fas fa-users"></i>
            <span>Registry</span>
            <i class="fas fa-chevron-down"></i>
        </div>

        <div class="submenu {{ $registryOpen ? 'show' : '' }}" id="registrySubmenu">
            @admin_can('view_residents')
                <a href="{{ route('admin.residents.index') }}"
                   class="{{ request()->routeIs('admin.residents.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-user"></i>
                    <span>Residents</span>
                </a>
            @endadmin_can

            @admin_can('view_residency')
                <a href="{{ route('admin.residency.index') }}"
                   class="{{ request()->routeIs('admin.residency.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-file-alt"></i>
                    <span>Residency Applications</span>
                </a>
            @endadmin_can

            @admin_can('view_indigency')
                <a href="{{ route('admin.indigency.index') }}"
                   class="{{ request()->routeIs('admin.indigency.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-file-invoice"></i>
                    <span>Indigency</span>
                </a>
            @endadmin_can
        </div>
    @endif

    <div class="menu-section">Legal</div>

    @if($hasRecordsAccess)
        <div class="dropdown-btn {{ $recordsOpen ? 'active' : '' }}"
             onclick="handleDropdownClick(event, this)">
            <i class="fas fa-scale-balanced"></i>
            <span>Records</span>
            <i class="fas fa-chevron-down"></i>
        </div>

        <div class="submenu {{ $recordsOpen ? 'show' : '' }}" id="recordsSubmenu">
            @admin_can('view_clearance')
                <a href="{{ route('admin.clearance.index') }}"
                   class="{{ request()->routeIs('admin.clearance.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-file-contract"></i>
                    <span>Clearances</span>
                </a>
            @endadmin_can

            @admin_can('view_blotter')
                <a href="{{ route('admin.blotter.index') }}"
                   class="{{ request()->routeIs('admin.blotter.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-book"></i>
                    <span>Incident Reports</span>
                </a>
            @endadmin_can
        </div>
    @endif

    <div class="menu-section">Community</div>

    @if($hasCommunityAccess)
        <div class="dropdown-btn {{ $communityOpen ? 'active' : '' }}"
             onclick="handleDropdownClick(event, this)">
            <i class="fas fa-bullhorn"></i>
            <span>Community</span>
            <i class="fas fa-chevron-down"></i>
        </div>

        <div class="submenu {{ $communityOpen ? 'show' : '' }}" id="communitySubmenu">
            @admin_can('view_announcements')
                <a href="{{ route('admin.announcements.index') }}"
                   class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            @endadmin_can

            @admin_can('view_events')
                <a href="{{ route('admin.events.index') }}"
                   class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-calendar"></i>
                    <span>Events</span>
                </a>
            @endadmin_can

            @admin_can('view_projects')
                <a href="{{ route('admin.projects.index') }}"
                   class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
                   onclick="handleSubmenuClick(event)">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projects</span>
                </a>
            @endadmin_can
        </div>
    @endif

    <div class="menu-section">System</div>

    @admin_can('view_users')
        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           onclick="handleLinkClick(event, this)">
            <i class="fas fa-user-shield"></i>
            <span>Users & Roles</span>
        </a>
    @endadmin_can

    @admin_can('manage_chatbot')
        <a href="{{ route('admin.chatbot.index') }}"
           class="{{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}"
           onclick="handleLinkClick(event, this)">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
        </a>
    @endadmin_can

    @admin_can('view_backup')
        <a href="{{ route('admin.backup.index') }}"
           class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}"
           onclick="handleLinkClick(event, this)">
            <i class="fas fa-database"></i>
            <span>Backup Settings</span>
        </a>
    @endadmin_can

    @admin_can('view_settings')
        <a href="{{ route('admin.settings.index') }}" 
            class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
            onclick="handleLinkClick(event, this)">
            <i class="fas fa-cog"></i>
            <span>System Settings</span>
        </a>
    @endadmin_can

    <div class="sidebar-account">
        <div class="sidebar-user-card">
            <div class="sidebar-user-icon">
                <i class="fas fa-user-circle"></i>
            </div>

            <div class="sidebar-user-details">
                <div class="sidebar-user-name"
                    title="{{ $admin?->full_name ?? 'Administrator' }}">
                    {{ $admin?->full_name ?? 'Administrator' }}
                </div>

                <div class="sidebar-user-role-row">
                    <span class="sidebar-user-role"
                        title="{{ $admin?->getRoleDisplayName() ?? 'Admin' }}">
                        {{ $admin?->getRoleDisplayName() ?? 'Admin' }}
                    </span>
                </div>

                <div class="sidebar-user-email"
                    title="{{ $admin?->email ?? '' }}">
                    {{ $admin?->email ?? '' }}
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
