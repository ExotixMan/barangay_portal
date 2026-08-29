@php
    $themeSettings = app(\App\Services\AdminSettingsService::class)->all();
    $themePrimary = strtolower(trim((string) data_get($themeSettings, 'general.primary_color', '#d32f2f')));
    $themeSecondary = strtolower(trim((string) data_get($themeSettings, 'general.secondary_color', '#b71c1c')));

    if (! preg_match('/^#[0-9a-f]{6}$/i', $themePrimary)) {
        $themePrimary = '#d32f2f';
    }

    if (! preg_match('/^#[0-9a-f]{6}$/i', $themeSecondary)) {
        $themeSecondary = '#b71c1c';
    }
@endphp
<meta name="app-theme-primary" content="{{ $themePrimary }}">
<meta name="app-theme-secondary" content="{{ $themeSecondary }}">
