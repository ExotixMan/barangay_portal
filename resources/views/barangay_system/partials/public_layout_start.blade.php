@php
    $adminSettings = app(\App\Services\AdminSettingsService::class);

    $portalName = $adminSettings->get('general.portal_name', config('app.name', 'Laravel'));

    $pageTitle = $pageTitle ?? 'Barangay Hulong Duhat';
    $activePage = $activePage ?? '';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - {{ $portalName }}</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barangay-public.css') }}">

    @include('shared.theme-meta')
    <script src="{{ asset('js/theme-vars.js') }}"></script>

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/form-feedback.css') }}">
    <script src="{{ asset('js/form-feedback.js') }}" defer></script>
    @include('shared.public-theme')
</head>
<body>

@include('chatbot.embed')

@include('barangay_system.partials.public_nav', ['activePage' => $activePage])

<main id="main-content">
