@php
    $adminSettings = app(\App\Services\AdminSettingsService::class);

    $portalName = $adminSettings->get('general.portal_name', config('app.name', 'Laravel'));

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" id="adminThemeColor" content="#f8fafc">
    @include('shared.theme-meta')
    <script src="{{ asset('js/theme-vars.js') }}"></script>
    <script src="{{ asset('js/admin/admin-theme-init.js') }}"></script>

    <title>@yield('title', 'Admin') - {{ $portalName }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nav.css') }}?v={{ filemtime(public_path('css/admin/nav.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-notifications.css') }}">

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-ux-refresh.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-modal-system.css') }}">
</head>
<body>
    @include('admin.layouts.sidebar')

    <div class="main-content" id="mainContent">
        @include('admin.layouts.header', [
            'pageTitle' => trim($__env->yieldContent('page_title', 'Admin')),
            'pageIcon' => trim($__env->yieldContent('page_icon', 'fas fa-house')),
        ])

        <main class="p-3 p-lg-4">
            <div class="admin-page">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/nav.js') }}?v={{ filemtime(public_path('js/admin/nav.js')) }}"></script>
    <script src="{{ asset('js/admin/admin-dropdowns.js') }}?v={{ filemtime(public_path('js/admin/admin-dropdowns.js')) }}"></script>
    <script src="{{ asset('js/action-bindings.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/admin/admin-theme.js') }}"></script>

    @stack('scripts')

    <script src="{{ asset('js/admin/admin-accessibility.js') }}" defer></script>
    <script src="{{ asset('js/admin/admin-notifications.js') }}" defer></script>
</body>
</html>
