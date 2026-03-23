<nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center py-4 border-bottom">
            <i class="fas fa-shield-alt fa-3x text-primary mb-2"></i>
            <h5 class="fw-bold mb-0">Admin Panel</h5>
            <small class="text-muted">{{ config('app.name') }}</small>
        </div>
        
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard.index') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            @admin_can('view_users')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Users
                </a>
            </li>
            @endadmin_can
            
            @admin_can('view_roles')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
                   href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-tag me-2"></i>
                    Roles & Permissions
                </a>
            </li>
            @endadmin_can

            @admin_can('view_users')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}" 
                   href="{{ route('admin.backup.index') }}">
                    <i class="fas fa-database me-2"></i>
                    Backup Settings
                </a>
            </li>
            @endadmin_can
            
            @if(Route::has('admin.activities'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.activities') ? 'active' : '' }}" 
                   href="{{ route('admin.activities') }}">
                    <i class="fas fa-history me-2"></i>
                    Activity Logs
                </a>
            </li>
            @endif
            
            <li class="nav-item mt-4">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
        
        @admin_role('super_admin')
        <div class="bg-light rounded p-3 m-3">
            <small class="text-uppercase text-muted">System Info</small>
            <div class="small mt-2">
                <i class="fas fa-database me-2"></i> v1.0.0
            </div>
        </div>
        @endadmin_role
    </div>
</nav>

<style>
    .sidebar {
        min-height: 100vh;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
    }
    
    .sidebar .nav-link {
        color: #3a3b45;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .sidebar .nav-link:hover {
        background-color: #eaecf4;
        color: #4e73df;
    }
    
    .sidebar .nav-link.active {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
        color: white;
    }
    
    .sidebar .nav-link i {
        width: 1.5rem;
        font-size: 1rem;
    }
</style>