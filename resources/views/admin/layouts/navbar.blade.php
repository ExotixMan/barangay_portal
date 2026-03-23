<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4 px-4">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                       id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px;">
                            <span>{{ Auth::guard('admin')->user()->initials ?? 'A' }}</span>
                        </div>
                        <div>
                            <div class="fw-bold small">{{ Auth::guard('admin')->user()->full_name ?? 'Admin User' }}</div>
                            <div class="text-muted small">{{ Auth::guard('admin')->user()->role->display_name ?? 'Admin' }}</div>
                        </div>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        @if(Route::has('admin.profile'))
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="fas fa-user fa-sm fa-fw me-2"></i> Profile
                        </a>
                        @endif
                        @if(Route::has('admin.settings'))
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="fas fa-cog fa-sm fa-fw me-2"></i> Settings
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Logout
                        </a>
                        
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>