{{-- Load after page styles so light surfaces cannot override the saved theme. --}}
<link rel="stylesheet" href="{{ asset('css/form-feedback.css') }}?v={{ filemtime(public_path('css/form-feedback.css')) }}">
<script src="{{ asset('js/form-feedback.js') }}?v={{ filemtime(public_path('js/form-feedback.js')) }}"></script>
<link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}?v={{ filemtime(public_path('css/dark-mode.css')) }}" media="screen">
<script src="{{ asset('js/dark-mode.js') }}?v={{ filemtime(public_path('js/dark-mode.js')) }}" data-theme-context="{{ $themeContext ?? 'public' }}" defer></script>
