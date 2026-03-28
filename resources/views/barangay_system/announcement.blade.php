<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Announcements</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            scroll-behavior: smooth;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #C62828;
            border-radius: 2px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 20px auto 0;
        }

        /* Featured Section */
        .featured-section {
            padding: 60px 0;
            background: white;
        }

        .carousel-container {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
        }

        .carousel {
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 400px;
            position: relative;
        }

        .carousel-item {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100%;
            display: none;
        }

        .carousel-item.active {
            display: block;
        }

        .carousel-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .carousel-item:hover .carousel-image {
            transform: scale(1.05);
        }

        .carousel-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            color: white;
            padding: 40px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .carousel-item:hover .carousel-content {
            background: linear-gradient(transparent, rgba(198, 40, 40, 0.95));
            padding-bottom: 50px;
        }

        .carousel-content .read-more-btn {
            pointer-events: auto;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .carousel-item:hover .carousel-content .read-more-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .category-badge {
            display: inline-block;
            padding: 6px 15px;
            background: #C62828;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .carousel-content h3 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.2;
            color: white;
        }

        .carousel-content p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .carousel-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .meta-item i {
            color: #FFCDD2;
        }

        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #C62828;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .read-more-btn:hover {
            background: #f8f9fa;
            transform: translateX(5px);
            text-decoration: none;
            color: #C62828;
        }

        /* Carousel Controls */
        .carousel-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .carousel-btn {
            width: 50px;
            height: 50px;
            background: white;
            border: 2px solid #eee;
            border-radius: 50%;
            color: #C62828;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-btn:hover {
            background: #C62828;
            color: white;
            border-color: #C62828;
            transform: scale(1.1);
        }

        .carousel-dots {
            display: flex;
            gap: 10px;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            background: #ddd;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: #C62828;
            transform: scale(1.2);
        }

        /* Announcements Grid Section */
        .announcements-grid-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .announcements-grid-section .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left h2 {
            font-size: 2.2rem;
            color: #C62828;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Refresh Button Styles */
        .refresh-btn {
            width: 40px;
            height: 40px;
            min-width: 40px;
            max-width: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border: none;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(198, 40, 40, 0.3);
            padding: 0;
            margin: 0;
            line-height: 1;
        }

        .refresh-btn:hover {
            transform: rotate(180deg);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.4);
        }

        .refresh-btn i {
            transition: transform 0.5s ease;
        }

        .refresh-btn:hover i {
            transform: rotate(180deg);
        }

        .refresh-btn:active {
            transform: scale(0.95);
        }

        .result-count {
            color: #666;
            font-size: 0.95rem;
        }

        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: flex-end;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #eee;
            border-radius: 30px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .search-box input:focus {
            outline: none;
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        .sort-dropdown {
            position: relative;
        }

        .sort-dropdown select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 30px;
            font-size: 0.95rem;
            color: #333;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
        }

        .sort-dropdown select:focus {
            outline: none;
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fixed Card Styles - Equal height and balanced */
        .announcement-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .announcement-card.featured {
            border: 2px solid #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.2);
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #C62828, #8B0000);
            color: white;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            z-index: 2;
            box-shadow: 0 6px 16px rgba(139, 0, 0, 0.35);
        }

        .featured-badge-inline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #C62828, #8B0000);
            color: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.25);
        }

        .announcement-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .announcement-card:hover .announcement-image {
            transform: scale(1.05);
        }

        .announcement-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .announcement-category {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(198, 40, 40, 0.1);
            color: #C62828;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            align-self: flex-start;
        }

        .announcement-category.important {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }

        .announcement-category.events {
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }

        .announcement-category.health {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .announcement-category.services {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }

        .announcement-category.infrastructure {
            background: rgba(156, 39, 176, 0.1);
            color: #9C27B0;
        }

        .announcement-content h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
            line-height: 1.4;
            min-height: 3.6rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .announcement-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .announcement-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }

        .meta-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .meta-date {
            font-size: 0.85rem;
            color: #666;
        }

        .meta-views {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .meta-views i {
            color: #C62828;
        }

        .view-btn {
            padding: 8px 15px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }

        .view-btn:hover {
            transform: translateX(3px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Ensure cards in same row have equal height */
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-lg-4, .col-md-6 {
            display: flex;
            flex-direction: column;
        }

        .col-lg-4 > *, .col-md-6 > * {
            flex: 1;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 4px;
            color: #C62828;
        }

        .pagination .page-item.active .page-link {
            background-color: #C62828;
            color: white;
            border-color: #C62828;
        }

        .announcement-detail-meta {
            background: #f8f9fa;
            border-radius: 12px;
        }

        .announcement-detail-meta span {
            color: #555;
            font-size: 0.92rem;
        }

        .announcement-detail-image-wrap {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            margin-bottom: 18px;
            background: #f3f4f6;
        }

        .announcement-detail-image {
            width: 100%;
            height: auto;
            max-height: 70vh;
            object-fit: contain;
            display: block;
        }

        /* Page-local dark mode fix for announcement action buttons */
        body.dark-mode .read-more-btn,
        body.dark-mode .view-btn {
            background: linear-gradient(135deg, #e53935, #b71c1c) !important;
            color: #fff !important;
            border: 1px solid rgba(229, 57, 53, 0.7) !important;
        }

        body.dark-mode .read-more-btn:hover,
        body.dark-mode .view-btn:hover {
            background: linear-gradient(135deg, #ef5350, #c62828) !important;
            color: #fff !important;
            box-shadow: 0 8px 20px rgba(229, 57, 53, 0.35) !important;
            text-decoration: none;
        }

        body.dark-mode .announcement-detail-meta {
            background: var(--dm-bg-tertiary) !important;
            border: 1px solid var(--dm-border-color);
        }

        body.dark-mode .announcement-detail-meta span {
            color: var(--dm-text-secondary) !important;
        }

        /* Load More Button */
        .load-more-container {
            text-align: center;
            margin: 40px 0 20px;
        }

        .load-more-btn {
            padding: 15px 40px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .load-more-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.4);
        }

        .load-more-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* No Results Message */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 20px;
            display: none;
            margin: 20px 0;
        }

        .no-results i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .reset-filters {
            padding: 12px 25px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .reset-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        /* Keyframe for refresh spin */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .announcement-content p {
                -webkit-line-clamp: 2;
            }
        }

        @media (max-width: 992px) {
            .carousel {
                height: 350px;
            }
            
            .carousel-content {
                padding: 30px;
            }
            
            .carousel-content h3 {
                font-size: 1.7rem;
            }
            
            .header-right {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-box {
                width: 100%;
            }
            
            .announcements-grid-section .container {
                padding: 40px 30px;
            }
        }

        @media (max-width: 768px) {
            .carousel {
                height: 300px;
            }
            
            .carousel-content {
                padding: 20px;
            }
            
            .carousel-content h3 {
                font-size: 1.5rem;
            }
            
            .announcements-grid-section .section-header {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }
            
            .header-left h2 {
                font-size: 1.8rem;
            }

            .col-lg-6 .d-flex {
                flex-wrap: wrap;
            }
            
            .refresh-btn {
                width: 36px;
                height: 36px;
                min-width: 36px;
                max-width: 36px;
                font-size: 1rem;
            }

            .featured-badge {
                top: 10px;
                right: 10px;
                font-size: 0.7rem;
                padding: 5px 10px;
            }
        }

        @media (max-width: 576px) {
            .section-header h2 {
                font-size: 2rem;
            }
            
            .carousel {
                height: 250px;
            }
            
            .carousel-content h3 {
                font-size: 1.3rem;
            }
            
            .carousel-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .meta-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .announcements-grid-section .container {
                padding: 30px 20px;
            }
            
            .header-right {
                width: 100%;
            }
            
            .sort-dropdown select {
                min-width: 100%;
            }
        }
    </style>
</head>
@include('chatbot.embed')
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <div class="logo"></div>
                <div class="logo-text d-none d-md-block">
                    <span class="logo-title d-block">{{ __('messages.nav_brand_part1') }}</span>
                    <span class="logo-subtitle d-block">{{ __('messages.nav_brand_part2') }} Portal</span>
                </div>
                <div class="logo-text d-md-none">
                    <span class="logo-subtitle">{{ __('messages.nav_brand_part2') }}</span>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Main Navigation -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav w-100 justify-content-around mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> {{ __('messages.nav_home') }}</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> {{ __('messages.nav_about') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('history') }}"><i class="fas fa-history"></i> {{ __('messages.nav_history') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('mission_vision')}}"><i class="fas fa-bullseye"></i> {{ __('messages.nav_mission_vision') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('map') }}"><i class="fas fa-map"></i> {{ __('messages.nav_barangay_map') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('officials') }}"><i class="fas fa-users"></i> {{ __('messages.nav_barangay_officials') }}</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-concierge-bell"></i> {{ __('messages.nav_services_dropdown') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link dropdown-item-custom" href="{{ route('services') }}"><i class="fas fa-list"></i> {{ __('messages.nav_all_services') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> {{ __('messages.nav_barangay_clearance') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('residency')}}"><i class="fas fa-house-user"></i> {{ __('messages.nav_certificate_residency') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> {{ __('messages.nav_certificate_indigency') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('track_request') }}"><i class="fas fa-search"></i> {{ __('messages.nav_track_request') }}</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> {{ __('messages.nav_community_dropdown') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('announcements') }}"><i class="fas fa-bullhorn"></i> {{ __('messages.nav_announcements') }}</a></li>
                            <li><a class="dropdown-link" href="{{ route('events_project') }}"><i class="fas fa-calendar-alt"></i> {{ __('messages.nav_events_projects') }}</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-circle"></i> {{ __('messages.nav_report_dropdown') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('incident') }}"><i class="fas fa-clipboard-list"></i> {{ __('messages.nav_blotter_report') }}</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts') }}"><i class="fas fa-phone"></i> {{ __('messages.nav_contact') }}</a>
                    </li>
                    
                    <!-- LogIn-LogOut Actions -->
                    <li class="nav-item d-none d-lg-block">
                        @auth
                            <div class="dropdown d-inline-block">
                                <button class="btn user-dropdown-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> {{ __('messages.nav_my_profile') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.nav_logout') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <form id="logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="login-btn ms-2">
                                <i class="fas fa-sign-in-alt"></i> {{ __('messages.nav_login') }}
                            </a>
                        @endauth
                    </li>

                    {{-- Mobile --}}
                    <li class="nav-item d-lg-none mt-3 pt-2 border-top">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-link nav-link dropdown-toggle w-100 text-start" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                                </button>
                                <ul class="dropdown-menu border-0 ps-3" aria-labelledby="mobileUserDropdown">
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> {{ __('messages.nav_my_profile') }}</a></li>
                                    <li><hr class="dropdown-divider bg-secondary"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}"
                                        onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.nav_logout') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="mobile-logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i> {{ __('messages.nav_login') }}
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-bullhorn bigicon"></i> {{ __('messages.announcements_hero_title') }}</h1>
                <p>{{ __('messages.announcements_hero_subtitle') }}</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ $announcements->total() }} {{ __('messages.announcements_stat_active') }}</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-eye"></i>
                        <span>{{ __('messages.announcements_stat_updated') }}</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('messages.announcements_stat_notifications') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php use Illuminate\Support\Str; @endphp
    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Featured Announcements Carousel -->
        <section class="featured-section">
            <div class="container">
                <div class="section-header">
                    <h2>{{ __('messages.announcements_featured_title') }}</h2>
                    <p>{{ __('messages.announcements_featured_subtitle') }}</p>
                </div>
                
                <div class="carousel-container">
                    <div class="carousel" id="announcementCarousel">

                        @foreach($featured as $key => $item)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="carousel-image">
                                <div class="carousel-content">
                                    <span class="category-badge">{{ ucfirst($item->category) }}</span>
                                    <h3>{{ $item->title }}</h3>
                                    <p>{{ Str::limit($item->content,150) }}</p>
                                    <div class="carousel-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ date('M d, Y', strtotime($item->published_at)) }}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="read-more-btn" data-slug="{{ $item->slug }}" data-bs-toggle="modal" data-bs-target="#announcementDetailModal{{ $item->id }}">
                                        <i class="fas fa-info-circle"></i> Learn More
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Carousel Controls -->
                    <div class="carousel-controls">
                        <button class="carousel-btn prev" aria-label="Previous announcement">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="carousel-dots">
                            @foreach($featured as $key => $item)
                                <span class="carousel-dot {{ $key==0?'active':'' }}" data-index="{{ $key }}">
                                </span>
                            @endforeach
                        </div>
                        
                        <button class="carousel-btn next" aria-label="Next announcement">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements Grid -->
        <section class="announcements-grid-section" id="announcements">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="header-left">
                            <h2><i class="fas fa-list-alt"></i> {{ __('messages.announcements_grid_title') }}</h2>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center gap-2 justify-content-end">
                            <form method="GET" action="{{ route('announcements') }}#announcements" class="d-flex gap-2 flex-wrap flex-sm-nowrap">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="{{ __('messages.announcements_search_placeholder') }}"
                                    >
                                </div>

                                <div class="sort-dropdown">
                                    <select name="sort" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>
                                            {{ __('messages.announcements_sort_newest') }}
                                        </option>

                                        <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>
                                            {{ __('messages.announcements_sort_oldest') }}
                                        </option>

                                        <option value="important" {{ request('sort')=='important'?'selected':'' }}>
                                            {{ __('messages.announcements_sort_important') }}
                                        </option>

                                        <option value="title" {{ request('sort')=='title'?'selected':'' }}>
                                            {{ __('messages.announcements_sort_title_az') }}
                                        </option>
                                    </select>
                                </div>
                            </form>

                            <button class="refresh-btn" id="refreshAnnouncements" title="Refresh Announcements">
                                <i class="fas fa-sync-alt"></i>
                            </button>

                        </div>
                    </div>
                </div>
                
                <div class="row" id="announcementsGrid">
                    {{-- Announcement card --}}
                    @foreach($announcements as $item)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="announcement-card {{ $item->is_featured ? 'featured' : '' }}">
                                @if($item->is_featured)
                                    <span class="featured-badge"><i class="fas fa-star me-1"></i>{{ __('messages.announcements_badge_featured') }}</span>
                                @endif
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="announcement-image">
                                <div class="announcement-content">
                                    <span class="announcement-category {{ $item->category }}">{{ ucfirst($item->category) }}</span>
                                    <h3>{{ $item->title }}</h3>
                                    <p>{{ Str::limit($item->content,120) }}</p>
                                    <div class="announcement-meta">
                                        <div class="meta-info">
                                            <span class="meta-date">{{ date('M d, Y', strtotime($item->published_at)) }}</span>
                                        </div>
                                        <button type="button" class="view-btn" data-slug="{{ $item->slug }}" data-bs-toggle="modal" data-bs-target="#announcementDetailModal{{ $item->id }}">
                                            <i class="fas fa-info-circle"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Load More Button -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $announcements->appends(request()->query())->fragment('announcements')->links() }}
                </div>

                <!-- No Results Message -->
                <div class="no-results" id="noResultsMessage">
                    <i class="fas fa-search"></i>
                    <h3>{{ __('messages.announcements_empty_title') }}</h3>
                    <p>{{ __('messages.announcements_empty_desc') }}</p>
                    <button class="reset-filters" id="resetFilters">
                        <i class="fas fa-redo"></i>
                        {{ __('messages.announcements_reset_filters') }}
                    </button>
                </div>
            </div>
        </section>
    </main>

    @php
        $modalAnnouncements = $announcements->getCollection()->merge($featured)->unique('id');
    @endphp

    @foreach($modalAnnouncements as $item)
    <div class="modal fade" id="announcementDetailModal{{ $item->id }}" data-slug="{{ $item->slug }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #C62828, #8B0000); color: #fff;">
                    <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>{{ $item->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="announcement-category {{ $item->category }}">{{ ucfirst($item->category) }}</span>
                        @if($item->is_featured)
                            <span class="featured-badge-inline"><i class="fas fa-star"></i>{{ __('messages.announcements_badge_featured') }}</span>
                        @endif
                    </div>

                    <div class="announcement-detail-image-wrap">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="announcement-detail-image">
                    </div>

                    <p style="white-space: pre-line; color: #444; line-height: 1.8;">{{ $item->content }}</p>

                    <div class="mt-4 p-3 announcement-detail-meta">
                        <div class="d-flex flex-wrap gap-3">
                            <span><i class="fas fa-calendar-alt me-2" style="color: #C62828;"></i>{{ date('F d, Y', strtotime($item->published_at)) }}</span>
                            <span><i class="fas fa-tag me-2" style="color: #C62828;"></i>{{ ucfirst($item->category) }}</span>
                            <span><i class="fas fa-star me-2" style="color: #C62828;"></i>{{ $item->is_featured ? __('messages.announcements_badge_featured') : 'Regular' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@include('barangay_system.partials.fab_footer')

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>  

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const items = document.querySelectorAll(".carousel-item");
            const dots = document.querySelectorAll(".carousel-dot");
            const prevBtn = document.querySelector(".carousel-btn.prev");
            const nextBtn = document.querySelector(".carousel-btn.next");

            let current = 0;
            const total = items.length;

            function showSlide(index) {
                items.forEach(item => item.classList.remove("active"));
                dots.forEach(dot => dot.classList.remove("active"));

                items[index].classList.add("active");
                dots[index].classList.add("active");

                current = index;
            }

            function nextSlide() {
                current = (current + 1) % total;
                showSlide(current);
            }

            function prevSlide() {
                current = (current - 1 + total) % total;
                showSlide(current);
            }

            // Buttons
            nextBtn.addEventListener("click", nextSlide);
            prevBtn.addEventListener("click", prevSlide);

            // Dots
            dots.forEach((dot, index) => {
                dot.addEventListener("click", () => {
                    showSlide(index);
                });
            });

            // Auto slide (optional)
            setInterval(nextSlide, 6000);

            // Refresh button functionality
            const refreshBtn = document.getElementById('refreshAnnouncements');

            if (refreshBtn) {
                refreshBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const icon = this.querySelector('i');
                    
                    // Add spinning animation
                    icon.style.animation = 'spin 0.5s ease-in-out';
                    
                    // Get current URL
                    const url = new URL(window.location.href);
                    
                    // Add a random parameter to bypass cache but keep existing filters
                    url.searchParams.set('_', Date.now());
                    
                    // Redirect to same URL with cache-busting parameter
                    setTimeout(() => {
                        window.location.href = url.toString();
                    }, 300);
                });

                // Remove animation after it completes
                refreshBtn.addEventListener('animationend', function() {
                    const icon = this.querySelector('i');
                    icon.style.animation = '';
                });
            }

            // Reset filters button functionality
            const resetFilters = document.getElementById('resetFilters');
            if (resetFilters) {
                resetFilters.addEventListener('click', function() {
                    window.location.href = window.location.pathname + '#announcements';
                });
            }

            // Check if there are no results and show message
            const announcementsGrid = document.getElementById('announcementsGrid');
            const noResultsMessage = document.getElementById('noResultsMessage');

            // Count a view when resident opens announcement details modal.
            document.querySelectorAll('.modal[id^="announcementDetailModal"]').forEach((modalEl) => {
                modalEl.addEventListener('shown.bs.modal', () => {
                    const slug = modalEl.getAttribute('data-slug');
                    if (!slug) {
                        return;
                    }

                    fetch(`{{ url('/announcements') }}/${encodeURIComponent(slug)}/track-view`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    }).catch(() => {
                        // Fail silently to avoid affecting modal UX.
                    });
                });
            });
            
            if (announcementsGrid && announcementsGrid.children.length === 0 && noResultsMessage) {
                noResultsMessage.style.display = 'block';
            }
        });
    </script>

</body>
</html>