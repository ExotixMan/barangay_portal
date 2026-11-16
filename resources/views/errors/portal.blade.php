<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} · {{ $title }} | BISIG Barangay Portal</title>
    <link rel="stylesheet" href="{{ asset('css/pages/errors/portal.css') }}?v={{ filemtime(public_path('css/pages/errors/portal.css')) }}">
    @include('shared.public-theme', ['themeContext' => request()->routeIs('admin.*') || request()->is(trim((string) env('ADMIN_PATH', 'bisig-admin-panel'), '/').'/*') ? 'admin' : 'public'])
</head>
<body>
    <main class="error-container" aria-labelledby="error-title">
        <p class="error-brand">BISIG · Barangay Hulong Duhat</p>
        <p class="error-code">{{ $code }}</p>
        <h1 id="error-title" class="error-title">{{ $title }}</h1>
        <p class="error-message">{{ $description }}</p>
        <nav class="error-actions" aria-label="Recovery options">
            <a class="btn-home" href="{{ url('/') }}">Go to home</a>
            <a class="btn-back" href="{{ url($recoveryPath) }}">{{ $recoveryLabel }}</a>
        </nav>
    </main>
</body>
</html>
