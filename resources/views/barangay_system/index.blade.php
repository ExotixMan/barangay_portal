<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Home</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
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

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('Images/homepage-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            padding-top: 80px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            color: rgba(255,255,255,0.9);
            animation: slideUp 1s ease;
        }

        .hero-actions {
            margin-bottom: 60px;
            animation: slideUp 1.2s ease;
        }

        .get-started-btn {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .get-started-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.6);
        }

        /* Quick Stats */
        .quick-stats {
            animation: slideUp 1.4s ease;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item i {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Quick Access Section */
        .quick-access {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
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

        .quick-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-decoration: none;
            color: #333;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-bottom: 25px;
        }

        .quick-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(198, 40, 40, 0.15);
            text-decoration: none;
            color: #333;
        }

        .quick-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #C62828;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .quick-card:hover::before {
            transform: scaleX(1);
        }

        .quick-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }

        .quick-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #C62828;
        }

        .quick-card p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .quick-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: auto;
        }

        /* About Section */
        .about-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 100px 0;
        }

        .about-section .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        .text-content h2 {
            font-size: 2.8rem;
            color: #C62828;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .text-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
            color: #555;
        }

        .feature-item i {
            color: #C62828;
            font-size: 1.2rem;
        }

        .snc-links {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 20px 0 0px 30px;
        }

        .section-links {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .section-btn {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .section-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(198, 40, 40, 0.3);
            color: white;
            text-decoration: none;
        }

        .section-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;   
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 5px 35px;
            border-radius: 0 0 20px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .image-overlay p {
            margin: 0;
        }

        /* Services & Community Cards */
        .services-community {
            background: #C62828;
            padding: 100px 0;
        }

        .card-hover {
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-bottom: 30px;
        }

        .service-card,
        .community-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            border: 1px solid #e8e8e8;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-icon {
            font-size: 2rem;
            color: #C62828;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(198, 40, 40, 0.05));
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .service-card h2,
        .community-card h2 {
            font-size: 1.8rem;
            color: #C62828;
            margin: 0;
            font-weight: 700;
        }

        .service-card > p,
        .community-card > p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        .service-list {
            list-style: none;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .service-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            font-size: 0.95rem;
        }

        .service-list li i {
            color: #4CAF50;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .community-highlights {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .highlight {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            font-size: 0.95rem;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border-left: 3px solid #C62828;
        }

        .highlight i {
            color: #C62828;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .highlight span {
            flex: 1;
        }

        /* Announcements & Events Section */
        .announcements-events {
            background: linear-gradient(135deg, #C62828 0%, #d32f2f 100%);
            padding: 100px 0;
        }

        .announcements-events .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .ann {
            color: #333;
        }

        .latann {
            color: #C62828;
            font-size: 2.5rem;
            text-decoration: underline;
            margin: 0;
        }

        .view-all {
            color: #C62828;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            gap: 12px;
            color: #C62828;
            text-decoration: none;
        }

        .announcement-counter,
        .events-counter {
            background: #C62828;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .announcement-carousel,
        .events-carousel {
            position: relative;
        }

        .announcement-carousel .carousel-inner,
        .events-carousel .carousel-inner {
            background: transparent;
        }

        .announcement-grid,
        .events-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-height: 550px;
        }

        .announcement-item {
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            height: auto;
            min-height: 160px;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .announcement-item:hover {
            border-color: #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .date {
            color: #C62828;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .announcement-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .announcement-badge.important {
            background: #ffebee;
            color: #C62828;
        }

        .announcement-badge.new {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .announcement-badge.update {
            background: #e3f2fd;
            color: #1565c0;
        }

        .announcement-item h3 {
            color: #333;
            font-size: 1.2rem; /* Increased font size */
            margin: 8px 0;
            white-space: normal; /* Allow text to wrap */
            overflow: visible;
            text-overflow: clip;
            line-height: 1.4;
        }

        .announcement-item p {
            color: #666;
            font-size: 0.95rem;
            margin: 0 0 10px 0;
            display: block; /* Remove webkit clamping */
            -webkit-line-clamp: unset;
            -webkit-box-orient: unset;
            overflow: visible;
            line-height: 1.5;
            height: auto; /* Remove fixed height */
            max-height: none;
        }

        .read-more {
            color: #C62828;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .read-more:hover {
            gap: 12px;
            color: #C62828;
            text-decoration: none;
        }

        /* Fixed height for event items - Fully readable */
        .event-item {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            height: auto; /* Remove fixed height */
            min-height: 160px; /* Set minimum height instead */
            margin: 0;
        }

        .event-item:hover {
            border-color: #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }

        .event-date {
            background: #C62828;
            color: white;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            min-width: 85px; /* Slightly increased */
            flex-shrink: 0;
        }

        .event-date .month {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .event-date .day {
            display: block;
            font-size: 2rem;
            font-weight: bold;
            line-height: 1;
            margin-top: 5px;
        }

        .event-details {
            flex: 1;
            overflow: visible; /* Allow content to be visible */
        }

        .event-details h3 {
            color: #333;
            font-size: 1.2rem; /* Increased font size */
            margin: 0 0 8px 0;
            white-space: normal; /* Allow text to wrap */
            overflow: visible;
            text-overflow: clip;
            line-height: 1.4;
        }

        .event-location,
        .event-time {
            font-size: 0.95rem;
            color: #666;
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: normal; /* Allow text to wrap */
            overflow: visible;
            text-overflow: clip;
            line-height: 1.4;
        }

        .event-location i,
        .event-time i {
            font-size: 0.85rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .event-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .event-status.ongoing {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .event-status.upcoming {
            background: #e3f2fd;
            color: #1565c0;
        }

        /* Navigation Controls */
        .navigation-arrows {
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .dots {
            display: flex;
            gap: 8px;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: #ddd;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
        }

        .dot:hover {
            background: #C62828;
            transform: scale(1.2);
        }

        .dot.active {
            background: #C62828;
            width: 25px;
            border-radius: 10px;
        }

        .arrow-btn {
            background: #f8f9fa;
            color: #C62828;
            border: 1px solid #eee;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arrow-btn:hover {
            background: #C62828;
            color: white;
            transform: scale(1.1);
            border-color: #C62828;
        }

        .arrow-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.3);
        }

        /* Report & Contact Section */
        .report-contact {
            background: #f8f9fa;
            padding: 100px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-contact .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            max-width: 700px;
            width: 100%;
        }

        .report-card h2,
        .contact-card h2 {
            font-size: 2.2rem;
            color: #C62828;
            margin: 0;
            text-align: center;
        }

        .report-card p {
            margin: 0 0 20px 0;
            text-align: center;
        }

        .report-types {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
            justify-content: center;
        }

        .report-type {
            background: #f8f9fa;
            color: #555;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .quick-card {
                margin-bottom: 20px;
            }
            
            .about-section .container,
            .services-community .container,
            .container,
            .report-contact .container {
                padding: 30px 20px;
            }

            .announcement-grid,
            .events-grid {
                min-height: auto;
            }
            
            .announcement-item,
            .event-item {
                height: auto;
                min-height: 140px;
                padding: 15px;
            }
            
            .event-date {
                min-width: 75px;
                padding: 12px;
            }
            
            .event-date .day {
                font-size: 1.8rem;
            }
            
            .announcement-item h3,
            .event-details h3 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }
            
            .logo-subtitle {
                font-size: 1rem;
            }
            
            .logo-title {
                display: none;
            }
            
            .quick-stats .col-4 {
                margin-bottom: 20px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .section-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <div class="logo"></div>
                <div class="logo-text d-none d-md-block">
                    <span class="logo-title d-block">Barangay</span>
                    <span class="logo-subtitle d-block">Hulong Duhat Portal</span>
                </div>
                <div class="logo-text d-md-none">
                    <span class="logo-subtitle">Hulong Duhat</span>
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
                        <a class="nav-link active" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('history') }}"><i class="fas fa-history"></i> History</a></li>
                            <li><a class="dropdown-link" href="{{ route('mission_vision')}}"><i class="fas fa-bullseye"></i> Mission/Vision</a></li>
                            <li><a class="dropdown-link" href="{{ route('map') }}"><i class="fas fa-map"></i> Barangay Map</a></li>
                            <li><a class="dropdown-link" href="{{ route('officials') }}"><i class="fas fa-users"></i> Barangay Officials</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-concierge-bell"></i> Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="{{ route('residency')}}"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Community
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('announcements') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-link" href="{{ route('events_project') }}"><i class="fas fa-calendar-alt"></i> Events/Projects</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-circle"></i> Report
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('incident') }}"><i class="fas fa-clipboard-list"></i> Blotter Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts') }}"><i class="fas fa-phone"></i> Contact</a>
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
                                    <li><a class="dropdown-link" href=""><i class="fas fa-id-card"></i> My Profile</a></li>
                                    <li><a class="dropdown-link" href=""><i class="fas fa-file-alt"></i> My Requests</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <form id="logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="login-btn ms-2">
                                <i class="fas fa-sign-in-alt"></i> Log In
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
                                    <li><a class="dropdown-link" href=""><i class="fas fa-id-card"></i> My Profile</a></li>
                                    <li><a class="dropdown-link" href=""><i class="fas fa-file-alt"></i> My Requests</a></li>
                                    <li><hr class="dropdown-divider bg-secondary"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}"
                                        onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <form id="mobile-logout-form" action="{{ route('logout.res') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login.res') }}" class="nav-link">
                                <i class="fas fa-sign-in-alt"></i> Log In
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
                <h1 class="mb-4">Barangay Hulo Online Services</h1>
                <p class="mb-5">Access barangay services anytime, anywhere. Fast, convenient, and secure.</p>
                <div class="hero-actions mb-5">
                    <a href="#main-content"><button class="get-started-btn"><i class="fa-regular fa-hand-pointer"></i> Get Started</button></a>
                </div>
                
                <!-- Quick Stats -->
                <div class="row quick-stats justify-content-center text-center">
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-file-alt mb-3"></i>
                        <span class="stat-number">1,234</span>
                        <span class="stat-label">Services Rendered</span>
                    </div>
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-users mb-3"></i>
                        <span class="stat-number">12,000</span>
                        <span class="stat-label">Active Residents</span>
                    </div>
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-check-circle mb-3"></i>
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfaction Rate</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Quick Access Cards -->
        <section class="quick-access">
            <div class="container">
                <h2 class="section-title">Quick Access</h2>
                <div class="row quick-access-grid">
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('clearance') }}" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3>Request Clearance</h3>
                            <p>Get your barangay clearance online</p>
                            <span class="quick-badge">Fast Process</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('events_project') }}" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3>View Events</h3>
                            <p>Upcoming community activities</p>
                            <span class="quick-badge">New</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('announcements') }}" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h3>Announcements</h3>
                            <p>Latest barangay updates</p>
                            <span class="quick-badge">Latest</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('incident') }}" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>Report Issue</h3>
                            <p>Submit concerns online</p>
                            <span class="quick-badge">24/7</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements & Events Section -->
        <section class="announcements-events">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="section-header latann">Latest Updates</h2>
                    <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="announcements-card card-hover">
                            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                                <h2 class="m-0 ann"><i class="fas fa-bullhorn"></i> Announcements</h2>
                                <div class="announcement-counter">9+ New</div>
                            </div>
                            
                            <!-- Announcement Carousel with fixed height -->
                            <div id="announcementCarousel" class="carousel slide announcement-carousel" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Page 1 -->
                                    <div class="carousel-item active">
                                        <div class="announcement-grid">
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 09, 2025</div>
                                                    <span class="announcement-badge important">Important</span>
                                                </div>
                                                <h3>Distribution of Ayuda</h3>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 08, 2025</div>
                                                    <span class="announcement-badge new">New</span>
                                                </div>
                                                <h3>Health Check-up Schedule</h3>
                                                <p>Free medical check-up for senior citizens and PWDs at the barangay health center.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 07, 2025</div>
                                                    <span class="announcement-badge update">Update</span>
                                                </div>
                                                <h3>Barangay Hall Renovation</h3>
                                                <p>Temporary closure of some offices due to renovation. Please use online services.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Page 2 -->
                                    <div class="carousel-item">
                                        <div class="announcement-grid">
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 06, 2025</div>
                                                    <span class="announcement-badge important">Important</span>
                                                </div>
                                                <h3>Voter's Registration</h3>
                                                <p>COMELEC registration for upcoming barangay elections at the barangay hall.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 05, 2025</div>
                                                    <span class="announcement-badge new">New</span>
                                                </div>
                                                <h3>Free Webinar for Seniors</h3>
                                                <p>Online safety and digital literacy training for senior citizens.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 04, 2025</div>
                                                    <span class="announcement-badge update">Update</span>
                                                </div>
                                                <h3>Water Interruption Advisory</h3>
                                                <p>Scheduled water interruption in selected areas on July 10 from 8:00 AM to 5:00 PM.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Page 3 -->
                                    <div class="carousel-item">
                                        <div class="announcement-grid">
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 03, 2025</div>
                                                    <span class="announcement-badge important">Important</span>
                                                </div>
                                                <h3>Tax Payment Deadline</h3>
                                                <p>Last day for real property tax payment without penalties and interest charges.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 02, 2025</div>
                                                    <span class="announcement-badge new">New</span>
                                                </div>
                                                <h3>Job Fair Announcement</h3>
                                                <p>Local job fair with 20+ companies at Barangay Covered Court. Bring multiple resumes.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                            <div class="announcement-item">
                                                <div class="announcement-header">
                                                    <div class="date">July 01, 2025</div>
                                                    <span class="announcement-badge update">Update</span>
                                                </div>
                                                <h3>New Online Services</h3>
                                                <p>Additional online services now available for residents including online payments.</p>
                                                <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Carousel Controls -->
                                <div class="navigation-arrows mt-4 d-flex justify-content-between align-items-center">
                                    <div class="dots">
                                        <button type="button" data-bs-target="#announcementCarousel" data-bs-slide-to="0" class="dot active" aria-current="true" aria-label="Page 1"></button>
                                        <button type="button" data-bs-target="#announcementCarousel" data-bs-slide-to="1" class="dot" aria-label="Page 2"></button>
                                        <button type="button" data-bs-target="#announcementCarousel" data-bs-slide-to="2" class="dot" aria-label="Page 3"></button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="arrow-btn" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="arrow-btn" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="events-card card-hover">
                            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                                <h2 class="m-0 ann"><i class="fas fa-calendar-alt"></i> Upcoming Events</h2>
                                <div class="events-counter">9+ Events</div>
                            </div>
                            
                            <!-- Events Carousel with fixed height -->
                            <div id="eventsCarousel" class="carousel slide events-carousel" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Page 1 -->
                                    <div class="carousel-item active">
                                        <div class="events-grid">
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">09</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Distribution of Ayuda</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Hall</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 10:30 am - 6:00pm</p>
                                                    <span class="event-status ongoing">Ongoing</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">15</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Community Assembly</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Covered Court</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 9:00 am - 12:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">20</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Clean-up Drive</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Hulo</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 7:00 am - 11:00am</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Page 2 -->
                                    <div class="carousel-item">
                                        <div class="events-grid">
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">22</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Health & Wellness Seminar</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Health Center</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 8:00 am - 12:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">25</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Youth Sports Festival</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Sports Complex</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 8:00 am - 5:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">July</span>
                                                    <span class="day">28</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Senior Citizens Day</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Hall</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 9:00 am - 3:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Page 3 -->
                                    <div class="carousel-item">
                                        <div class="events-grid">
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">Aug</span>
                                                    <span class="day">01</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Blood Letting Activity</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Hall</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 8:00 am - 5:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">Aug</span>
                                                    <span class="day">05</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Disaster Preparedness Drill</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Plaza</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 7:00 am - 11:00am</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                            <div class="event-item">
                                                <div class="event-date">
                                                    <span class="month">Aug</span>
                                                    <span class="day">10</span>
                                                </div>
                                                <div class="event-details">
                                                    <h3>Parents-Teachers Meeting</h3>
                                                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Elementary School</p>
                                                    <p class="event-time"><i class="fas fa-clock"></i> 2:00 pm - 5:00pm</p>
                                                    <span class="event-status upcoming">Upcoming</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Carousel Controls -->
                                <div class="navigation-arrows mt-4 d-flex justify-content-between align-items-center">
                                    <div class="dots">
                                        <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="0" class="dot active" aria-current="true" aria-label="Page 1"></button>
                                        <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="1" class="dot" aria-label="Page 2"></button>
                                        <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="2" class="dot" aria-label="Page 3"></button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="arrow-btn" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="arrow-btn" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="text-content">
                            <h2>About Barangay Hulo</h2>
                            <p>{{ __('messages.learn_more') }}</p>
                            <div class="feature-list">
                                <div class="feature-item">
                                    <i class="fas fa-history"></i>
                                    <span>Rich History Since 1901</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-award"></i>
                                    <span>National Award Winning</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-user-friends"></i>
                                    <span>12 Dedicated Officials</span>
                                </div>
                            </div>
                            <a href={{ route('history')}} class='section-links'><i class="fa-solid fa-up-right-from-square"></i> Learn more about Barangay Hulo</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image-content position-relative">
                            <img src="Images/about1.jpg" alt="Barangay Hall" class="section-image img-fluid" loading="lazy">
                            <div class="image-overlay">
                                <p>Visit our barangay hall for personalized assistance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services & Community Section -->
        <section class="services-community" id="services">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="service-card card-hover">
                            <div class="card-header">
                                <i class="fas fa-concierge-bell card-icon"></i>
                                <h2>Services</h2>
                            </div>
                            <p>{{ __('messages.services_info') }}</p>
                            <ul class="service-list">
                                <li><i class="fas fa-check-circle"></i> Online document requests</li>
                                <li><i class="fas fa-check-circle"></i> Real-time status tracking</li>
                                <li><i class="fas fa-check-circle"></i> Digital payment options</li>
                                <li><i class="fas fa-check-circle"></i> Appointment scheduling</li>
                            </ul>
                            <a href="{{ route('barangay_system.services') }}" class='snc-links'><i class="fa-solid fa-up-right-from-square"></i> Request Services</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="community-card card-hover">
                            <div class="card-header">
                                <i class="fas fa-users card-icon"></i>
                                <h2>Community</h2>
                            </div>
                            <p>{{ __('messages.community_info') }}</p>
                            <div class="community-highlights">
                                <div class="highlight">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>Upcoming: Community Clean-up on July 20</span>
                                </div>
                                <div class="highlight">
                                    <i class="fas fa-chart-line"></i>
                                    <span>3 Ongoing Projects</span>
                                </div>
                            </div>
                            <a href='announcements' class='snc-links'><i class="fa-solid fa-up-right-from-square"></i> Stay Updated</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Report & Contact Section -->
        <section class="report-contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="report-card card-hover">
                            <div class="card-header text-center mb-4">
                                <i class="fas fa-exclamation-circle card-icon mb-3"></i>
                                <h2>Report a Concern</h2>
                            </div>
                            <p class="text-center">{{ __('messages.incident_info') }}</p>
                            <div class="report-types">
                                <span class="report-type">Noise Complaint</span>
                                <span class="report-type">Street Lights</span>
                                <span class="report-type">Garbage Issue</span>
                                <span class="report-type">Safety Concern</span>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('incident') }}"><button class="section-btn"><i class="fas fa-paper-plane"></i> Report Now</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Chat Modal -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-modal-content">
            <div class="chat-modal-header">
                <div class="chat-modal-title">
                    InfoHulo Assistant
                </div>
                <button class="chat-modal-close" id="closeChat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-modal-body">
                <!-- Option 1: Iframe Method -->
                <iframe
                    id="chatIframe"
                    src="https://app.chaindesk.ai/agents/cmjoevt2d04giiz0r9u2i0zcb/iframe"
                    frameborder="0"
                    allow="clipboard-write"
                ></iframe>
            </div>
        </div>
    </div>

    <!-- Floating Action Button with Speed Dial -->
    <div class="fab-container">
        <div class="speed-dial" id="speedDial">
            <button class="fab-action" id="translateBtn" title="Translate Text">
                @if(app()->getLocale() == 'en')
                    <span>Filipino</span>
                @else
                    <span>English</span>
                @endif
            </button>
            <button class="fab-action" id="darkModeBtn" title="Toggle Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
            <button class="fab-action" id="chatBtn" title="Chat with Assistant">
                <i class="fas fa-comment-dots"></i>
            </button>
        </div>
        <button class="fab-main" id="fabMain">
            <i class="fas fa-gear"></i>
        </button>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Footer Section -->
    <footer>
        <div class="container footer-container">
            <div class="row">
                <!-- Logo & Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <div class="logo-circle">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <div class="logo-text">
                                <h3>Barangay Hulo</h3>
                                <p class="tagline">Serving Our Community</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-simple">
                            <div class="contact-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>1 M. Blas St, Malabon, Metro Manila</span>
                            </div>
                            <div class="contact-row">
                                <i class="fas fa-phone"></i>
                                <a href="tel:+6329876543">(02) 987-6543</a>
                            </div>
                            <div class="contact-row">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:info@barangayhulo.gov.ph">info@barangayhulo.gov.ph</a>
                            </div>
                            <div class="contact-row">
                                <i class="fas fa-clock"></i>
                                <span>Mon-Fri: 8:00 AM - 5:00 PM</span>
                            </div>
                        </div>

                        <div class="social-links-simple">
                            <div class="social-icons">
                                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Access Links -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h3>Quick Access</h3>
                        <div class="footer-links-list">
                            <a href="{{ route('barangay_system.index') }}" class="footer-link">
                                <i class="fas fa-home"></i> Home
                            </a>
                            <a href="{{ route('announcements') }}" class="footer-link">
                                <i class="fas fa-bullhorn"></i> Announcements
                            </a>
                            <a href="{{ route('history') }}" class="footer-link">
                                <i class="fas fa-history"></i> Barangay History
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fas fa-search"></i> Track Request
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h3>Services</h3>
                        <div class="footer-links-list">
                            <a href="{{ route('clearance') }}" class="footer-link">
                                <i class="fas fa-certificate"></i> Barangay Clearance
                            </a>
                            <a href="{{ route('residency') }}" class="footer-link">
                                <i class="fas fa-house-user"></i> Certificate of Residency
                            </a>
                            <a href="{{ route('indigency') }}" class="footer-link">
                                <i class="fas fa-hands-helping"></i> Certificate of Indigency
                            </a>
                            <a href="{{ route('incident') }}" class="footer-link">
                                <i class="fas fa-clipboard-list"></i> Blotter Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Emergency & Support -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h3>Emergency Contacts</h3>
                        <div class="emergency-contacts-simple">
                            <div class="emergency-item">
                                <i class="fas fa-ambulance"></i>
                                <div class="emergency-details">
                                    <span class="emergency-label">Emergency</span>
                                    <a href="tel:911" class="emergency-number">911</a>
                                </div>
                            </div>
                            <div class="emergency-item">
                                <i class="fas fa-shield-alt"></i>
                                <div class="emergency-details">
                                    <span class="emergency-label">Police</span>
                                    <a href="tel:+6329876543" class="emergency-number">(02) 987-6543</a>
                                </div>
                            </div>
                            <div class="emergency-item">
                                <i class="fas fa-first-aid"></i>
                                <div class="emergency-details">
                                    <span class="emergency-label">Health Center</span>
                                    <a href="tel:+6327654321" class="emergency-number">(02) 765-4321</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <div class="copyright-info">
                    <p>&copy; 2025 Barangay Hulo, Malabon City. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="#"]:not([data-bs-toggle])').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var carousels = document.querySelectorAll('.carousel');
            carousels.forEach(function(carousel) {
                if (!carousel.classList.contains('carousel-initialized')) {
                    carousel.classList.add('carousel-initialized');
                    carousel.addEventListener('slide.bs.carousel', function(e) {
                        console.log('Sliding to: ' + e.to);
                    });
                    
                    carousel.addEventListener('slid.bs.carousel', function(e) {
                        var dots = this.querySelectorAll('.dot');
                        dots.forEach(function(dot, index) {
                            if (index === e.to) {
                                dot.classList.add('active');
                            } else {
                                dot.classList.remove('active');
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>