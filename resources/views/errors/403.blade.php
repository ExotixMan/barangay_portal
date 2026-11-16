<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Barangay Portal</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/errors/403.css') }}">
    @include('shared.public-theme', ['themeContext' => $themeContext ?? (request()->routeIs('admin.*') ? 'admin' : 'public')])
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>

        @if(isset($exception) && $exception->getMessage())
            <div class="permission-name">
                <i class="fas fa-key me-2"></i>
                {{ $exception->getMessage() }}
            </div>
        @endif

        <div class="error-message">
            <p>You don't have permission to access this page.</p>
            <p class="small">If you believe this is a mistake, please contact your administrator.</p>
        </div>

        <div>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Go Back
            </a>
            <a href="{{ route('admin.dashboard.index') }}" class="btn-home">
                <i class="fas fa-home me-2"></i>Go to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
