@php
    $themeSettings = app(\App\Services\AdminSettingsService::class)->all();

    $themePrimary = strtolower(trim((string) data_get(
        $themeSettings,
        'general.primary_color',
        '#d32f2f'
    )));

    $themeSecondary = strtolower(trim((string) data_get(
        $themeSettings,
        'general.secondary_color',
        '#b71c1c'
    )));

    if (! preg_match('/^#[0-9a-f]{6}$/i', $themePrimary)) {
        $themePrimary = '#d32f2f';
    }

    if (! preg_match('/^#[0-9a-f]{6}$/i', $themeSecondary)) {
        $themeSecondary = '#b71c1c';
    }
@endphp

<style id="dynamic-system-theme">
    :root {
        /*
         * Saved values
         */
        --saved-theme-primary: {{ $themePrimary }};
        --saved-theme-secondary: {{ $themeSecondary }};

        /*
         * Public layout variable names
         */
        --theme-primary: {{ $themePrimary }};
        --theme-secondary: {{ $themeSecondary }};
        --brand: {{ $themePrimary }};
        --brand-dark: {{ $themeSecondary }};
        --brand-soft: color-mix(
            in srgb,
            {{ $themePrimary }} 12%,
            transparent
        );

        /*
         * Admin layout variable names
         */
        --primary: {{ $themePrimary }};
        --primary-dark: {{ $themeSecondary }};
        --primary-soft: color-mix(
            in srgb,
            {{ $themePrimary }} 10%,
            transparent
        );

        /*
         * Bootstrap variables
         */
        --bs-primary: {{ $themePrimary }};
        --bs-link-color: {{ $themePrimary }};
        --bs-link-hover-color: {{ $themeSecondary }};
    }

    body.dark-mode,
    body.dark,
    html.dark-mode body,
    html[data-theme="dark"] body,
    html[data-bs-theme="dark"] body {
        --brand: {{ $themePrimary }};
        --brand-dark: {{ $themeSecondary }};
        --primary: {{ $themePrimary }};
        --primary-dark: {{ $themeSecondary }};

        --brand-soft: color-mix(
            in srgb,
            {{ $themePrimary }} 18%,
            transparent
        );

        --primary-soft: color-mix(
            in srgb,
            {{ $themePrimary }} 18%,
            transparent
        );
    }
</style>