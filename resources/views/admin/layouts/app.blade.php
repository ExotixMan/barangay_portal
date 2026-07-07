@php
    $adminSettings = app(\App\Services\AdminSettingsService::class);

    $portalName = $adminSettings->get('general.portal_name', config('app.name', 'Laravel'));
    $primaryColor = $adminSettings->get('general.primary_color', '#d32f2f');
    $secondaryColor = $adminSettings->get('general.secondary_color', '#c62828');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ $portalName }}</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}">

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --primary: {{ $primaryColor }};
            --secondary-color: #64748b;
            --success-color: #2e7d32;
            --info-color: #0288d1;
            --warning-color: #ed6c02;
            --danger-color: {{ $secondaryColor }};
            --card-shadow: 0 16px 40px rgba(31, 41, 55, .06);
            --border-color: #e8edf3;
            --primary-light: #fff0f0;
            --primary-dark: {{ $secondaryColor }};
            --surface-bg: #f6f7fb;
        }

        html {
            font-size: 70%;
        }

        body {
            background: linear-gradient(180deg, #f8fafc 0%, #f3f6fb 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #172033;
            overflow-x: hidden;
        }

        .admin-shell {
            min-height: 100vh;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 290px;
            transition: margin-left 0.25s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 92px;
        }

        .admin-content-shell {
            padding-top: 1.25rem !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.info { border-left-color: var(--info-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }

            body.sidebar-collapsed .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        @if(Auth::guard('admin')->check())
            @include('admin.layouts.sidebar')
        @endif

        <main class="main-content" id="mainContent">
            @if(Auth::guard('admin')->check())
                @include('admin.layouts.navbar')
            @endif

            <div class="container-fluid py-4 px-3 px-lg-4 admin-content-shell">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const body = document.body;
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem(
                'adminSidebarCollapsed',
                body.classList.contains('sidebar-collapsed') ? 'true' : 'false'
            );
        }

        function toggleMobileSidebar() {
            sidebar?.classList.add('show');
            sidebarOverlay?.classList.add('show');
        }

        function closeMobileSidebar() {
            sidebar?.classList.remove('show');
            sidebarOverlay?.classList.remove('show');
        }

        function toggleDropdown(button) {
            const submenu = button.nextElementSibling;

            button.classList.toggle('active');

            if (submenu && submenu.classList.contains('submenu')) {
                submenu.classList.toggle('show');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (localStorage.getItem('adminSidebarCollapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            document.querySelectorAll('.dropdown-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    toggleDropdown(this);
                });
            });

            document.getElementById('collapseBtn')?.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                toggleSidebar();
            });

            document.querySelectorAll('.sidebar a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 991) {
                        closeMobileSidebar();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>