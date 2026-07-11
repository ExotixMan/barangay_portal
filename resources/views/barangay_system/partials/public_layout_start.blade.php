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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barangay-public.css') }}">

    @stack('styles')
    @include('barangay_system.partials.theme-colors')
</head>
<body>

@include('chatbot.embed')

@include('barangay_system.partials.public_nav', ['activePage' => $activePage])

<main id="main-content">