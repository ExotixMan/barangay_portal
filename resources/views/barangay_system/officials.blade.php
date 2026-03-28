<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Barangay Officials</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">


    <style>
        /* Section Styling */
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 20px;
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

        .section-subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 30px auto 50px;
        }

        .officials-overview {
            margin-top: -35px;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .overview-item {
            background: white;
            border: 1px solid #f2dede;
            border-radius: 14px;
            padding: 16px 14px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }

        .overview-item .count {
            display: block;
            font-size: 1.55rem;
            line-height: 1.1;
            font-weight: 700;
            color: #C62828;
            margin-bottom: 4px;
        }

        .overview-item .label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
            color: #6b6b6b;
        }

        /* Captain Section */
        .captain-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .captain-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            max-width: 1000px;
            margin: 0 auto;
        }

        .captain-image {
            width: 350px;
            min-width: 350px;
            background: linear-gradient(135deg, #C62828, #7a2323);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 40px;
        }

        .captain-image .image-placeholder {
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid white;
        }

        .captain-image .image-placeholder i {
            font-size: 5rem;
            color: white;
        }

        .captain-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: #FFD700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7a2323;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .captain-info {
            padding: 40px;
            flex: 1;
        }

        .captain-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .captain-info .position {
            display: inline-block;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .captain-info .bio {
            color: #666;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .captain-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .captain-details .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
        }

        .captain-details .detail-item i {
            color: #C62828;
            width: 20px;
        }

        .captain-message {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #C62828;
        }

        .captain-message h4 {
            color: #C62828;
            font-size: 1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .captain-message p {
            color: #555;
            font-style: italic;
            line-height: 1.7;
            margin: 0;
        }

        .captain-highlight {
            margin-top: 20px;
            padding: 14px 18px;
            background: #fff5f5;
            border: 1px solid #f5d2d2;
            border-radius: 12px;
            color: #8f2a2a;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .captain-focus {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .focus-chip {
            background: #f8f9fa;
            color: #4b4b4b;
            border: 1px solid #ececec;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 8px 12px;
            letter-spacing: 0.03em;
        }

        /* Kagawad Section */
        .kagawad-section {
            padding: 80px 0;
            background: white;
        }

        .official-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #f0f0f0;
        }

        .official-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .official-image {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            padding: 30px;
            display: flex;
            justify-content: center;
        }

        .official-image .image-placeholder {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid white;
        }

        .official-image .image-placeholder i {
            font-size: 3rem;
            color: white;
        }

        .official-info {
            padding: 25px;
            text-align: center;
        }

        .official-info h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .official-info .position {
            display: inline-block;
            color: #C62828;
            font-weight: 600;
            font-size: 0.88rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: #fff3f3;
            border: 1px solid #f5d2d2;
            border-radius: 999px;
            padding: 8px 14px;
        }

        .official-seat {
            margin-top: 14px;
            border-top: 1px solid #f1f1f1;
            padding-top: 12px;
            font-size: 0.77rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #8c8c8c;
            font-weight: 600;
        }

        /* Other Officials Section */
        .other-officials {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .special-official-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #f0f0f0;
            position: relative;
        }

        .special-official-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .card-header-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1;
        }

        .special-official-card.sk .card-header-badge {
            background: #2196F3;
            color: white;
        }

        .special-official-card.secretary .card-header-badge {
            background: #9C27B0;
            color: white;
        }

        .special-official-card.treasurer .card-header-badge {
            background: #FF9800;
            color: white;
        }

        .special-official-card.sk:hover {
            border-color: #2196F3;
        }

        .special-official-card.secretary:hover {
            border-color: #9C27B0;
        }

        .special-official-card.treasurer:hover {
            border-color: #FF9800;
        }

        .special-official-card .official-image {
            background: linear-gradient(135deg, #455A64, #607D8B);
            padding: 50px 30px 30px;
        }

        .special-official-card.sk .official-image {
            background: linear-gradient(135deg, #1976D2, #2196F3);
        }

        .special-official-card.secretary .official-image {
            background: linear-gradient(135deg, #7B1FA2, #9C27B0);
        }

        .special-official-card.treasurer .official-image {
            background: linear-gradient(135deg, #F57C00, #FF9800);
        }

        /* Organizational Structure Section */
        .org-structure {
            padding: 80px 0;
            background: white;
        }

        .org-chart {
            max-width: 900px;
            margin: 50px auto 0;
            justify-items: center;
        }

        .org-level {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .org-connector {
            width: 3px;
            height: 40px;
            background: #C62828;
            margin: 0 auto;
        }

        .org-box {
            background: white;
            padding: 25px 35px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 2px solid #f0f0f0;
            min-width: 200px;
            transition: all 0.3s ease;
        }

        .org-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .org-box i {
            font-size: 2rem;
            color: #C62828;
            margin-bottom: 10px;
        }

        .org-box h4 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .org-box p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .org-box.captain-box {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border-color: #C62828;
        }

        .org-box.captain-box i,
        .org-box.captain-box h4,
        .org-box.captain-box p {
            color: white;
        }

        .org-box.kagawad-box {
            background: #f8f9fa;
        }

        .org-box.sk-box {
            background: #E3F2FD;
        }

        .org-box.sk-box i {
            color: #1976D2;
        }

        /* Contact Section */
        .officials-contact {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .contact-card {
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: white;
            padding: 50px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.3);
        }

        .contact-card h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .contact-card p {
            opacity: 0.9;
            font-size: 1.1rem;
            margin: 0;
        }

        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #C62828;
            padding: 15px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }

        .contact-btn:hover {
            background: #f8f9fa;
            color: #C62828;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Tablet */
        @media (max-width: 991.98px) {
            .overview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .section-title {
                font-size: 2rem;
            }

            .captain-card {
                flex-direction: column;
            }

            .captain-image {
                width: 100%;
                min-width: auto;
                padding: 50px;
            }

            .captain-info {
                padding: 30px;
            }

            .captain-info h3 {
                font-size: 1.7rem;
            }

            .org-level {
                gap: 20px;
            }

            .org-box {
                min-width: 150px;
                padding: 20px 25px;
            }

            .contact-card {
                padding: 40px;
                text-align: center;
            }

            .contact-card .text-lg-end {
                text-align: center !important;
                margin-top: 25px;
            }
        }

        /* Mobile */
        @media (max-width: 767.98px) {
            .officials-overview {
                margin-top: -20px;
                margin-bottom: 30px;
            }

            .captain-section,
            .kagawad-section,
            .other-officials,
            .org-structure,
            .officials-contact {
                padding: 50px 0;
            }

            .section-title {
                font-size: 1.7rem;
            }

            .section-subtitle {
                font-size: 1rem;
                margin: 20px auto 40px;
                padding: 0 15px;
            }

            .captain-image {
                padding: 40px;
            }

            .captain-image .image-placeholder {
                width: 150px;
                height: 150px;
            }

            .captain-image .image-placeholder i {
                font-size: 4rem;
            }

            .captain-badge {
                top: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .captain-info {
                padding: 25px 20px;
            }

            .captain-info h3 {
                font-size: 1.5rem;
            }

            .captain-details {
                flex-direction: column;
                gap: 12px;
            }

            .captain-message {
                padding: 20px;
            }

            .official-image {
                padding: 25px;
            }

            .official-image .image-placeholder {
                width: 100px;
                height: 100px;
            }

            .official-image .image-placeholder i {
                font-size: 2.5rem;
            }

            .official-info {
                padding: 20px;
            }

            .official-info h3 {
                font-size: 1.1rem;
            }

            .special-official-card .official-image {
                padding: 45px 25px 25px;
            }

            .org-level {
                flex-direction: column;
                gap: 15px;
            }

            .org-box {
                width: 100%;
                max-width: 280px;
            }

            .org-connector {
                height: 25px;
            }

            .contact-card {
                padding: 30px 20px;
            }

            .contact-card h2 {
                font-size: 1.5rem;
            }

            .contact-card p {
                font-size: 1rem;
            }

            .contact-btn {
                width: 100%;
                justify-content: center;
                padding: 14px 30px;
            }
        }

        /* Small Mobile */
        @media (max-width: 575.98px) {
            .overview-grid {
                grid-template-columns: 1fr;
            }

            .officials-hero h1 {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .captain-image .image-placeholder {
                width: 120px;
                height: 120px;
            }

            .captain-image .image-placeholder i {
                font-size: 3rem;
            }

            .captain-info h3 {
                font-size: 1.3rem;
            }

            .org-box i {
                font-size: 1.5rem;
            }

            .org-box h4 {
                font-size: 1rem;
            }
        }

        @media (max-width: 767.98px) {
            .container {
                padding-left: 20px;
                padding-right: 20px;
            }
            
            .row {
                margin-left: -10px;
                margin-right: -10px;
            }
            
            [class*="col-"] {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
        
        .image-placeholder {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 575.98px) {
            .contact-btn{
                min-height: 44px;
                padding: 12px 15px;
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
                        <a class="nav-link" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
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
                            <li><a class="dropdown-link dropdown-item-custom" href="{{ route('services') }}"><i class="fas fa-list"></i> All Services</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="{{ route('residency')}}"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('track_request') }}"><i class="fas fa-search"></i> Track Request</a></li>
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
                            <li><a class="dropdown-link" href="{{ route('incident') }}"><i class="fas fa-clipboard-list"></i> Incident Report</a></li>
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
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
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
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
                                    <li><hr class="dropdown-divider bg-secondary"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.res') }}">
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
                <h1 class="flex-column flex-sm-row"><i class="fas fa-users mb-3 mb-sm-0"></i> {{ __('messages.officials_hero_title') }}</h1>
                <p>{{ __('messages.officials_hero_subtitle') }}</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-user-tie"></i>
                        <span>Elected Officials</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-handshake"></i>
                        <span>Public Servants</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-heart"></i>
                        <span>Community Leaders</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <section class="officials-overview">
            <div class="container">
                <div class="overview-grid">
                    <div class="overview-item">
                        <span class="count">{{ $officialCounts['total'] ?? 0 }}</span>
                        <span class="label">Total Officials</span>
                    </div>
                    <div class="overview-item">
                        <span class="count">{{ $officialCounts['kagawads'] ?? 0 }}</span>
                        <span class="label">Sangguniang Members</span>
                    </div>
                    <div class="overview-item">
                        <span class="count">{{ $officialCounts['appointed'] ?? 0 }}</span>
                        <span class="label">Appointed Officers</span>
                    </div>
                    <div class="overview-item">
                        <span class="count">2023-2026</span>
                        <span class="label">Current Term</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Punong Barangay Section -->
        <section class="captain-section">
            <div class="container">
                <h2 class="section-title">Punong Barangay</h2>
                <p class="section-subtitle">{{ __('messages.officials_punong_desc') }}</p>
                
                <div class="captain-card">
                    <div class="captain-image">
                        <div class="image-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="captain-badge">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="captain-info">
                        <h3>{{ $captain ? 'KAP. ' . strtoupper($captain->full_name) : 'KAP. TO BE ANNOUNCED' }}</h3>
                        <span class="position">Punong Barangay</span>
                        <div class="captain-highlight">
                            <i class="fas fa-calendar-check"></i>
                            <span>Public Service Term: 2023 - 2026</span>
                        </div>
                        <div class="captain-focus">
                            <span class="focus-chip">Governance</span>
                            <span class="focus-chip">Community Safety</span>
                            <span class="focus-chip">Public Service Delivery</span>
                            <span class="focus-chip">Barangay Development</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sangguniang Barangay Section -->
        <section class="kagawad-section">
            <div class="container">
                <h2 class="section-title">Sangguniang Barangay</h2>
                <p class="section-subtitle">{{ __('messages.officials_sangunian_desc') }}</p>
                
                <div class="row g-3 g-lg-4">
                    @forelse ($kagawads as $index => $kagawad)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="official-card">
                                <div class="official-image">
                                    <div class="image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="official-info">
                                    <h3>{{ 'KAG. ' . strtoupper($kagawad->full_name) }}</h3>
                                    <span class="position">Kagawad</span>
                                    <div class="official-seat">Council Seat {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border text-center mb-0">
                                No Sangguniang Barangay members are currently assigned.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Other Officials Section -->
        <section class="other-officials">
            <div class="container">
                <h2 class="section-title">Other Officials</h2>
                <p class="section-subtitle">{{ __('messages.officials_other_officials') }}</p>
                
                <div class="row g-4 justify-content-center">
                    <!-- Barangay Secretary -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="special-official-card secretary">
                            <div class="card-header-badge">
                                <i class="fas fa-file-alt"></i> Secretary
                            </div>
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>{{ $secretary ? 'SEC. ' . strtoupper($secretary->full_name) : 'SEC. TO BE ANNOUNCED' }}</h3>
                                <span class="position">Barangay Secretary</span>
                                <div class="official-seat">Administrative Office</div>
                            </div>
                        </div>
                    </div>

                    <!-- Barangay Treasurer -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="special-official-card treasurer">
                            <div class="card-header-badge">
                                <i class="fas fa-coins"></i> Treasurer
                            </div>
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>{{ $treasurer ? 'TREAS. ' . strtoupper($treasurer->full_name) : 'TREAS. TO BE ANNOUNCED' }}</h3>
                                <span class="position">Barangay Treasurer</span>
                                <div class="official-seat">Finance Office</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Organizational Structure Section -->
        <section class="org-structure">
            <div class="container">
                <h2 class="section-title">Organizational Structure</h2>
                <p class="section-subtitle">{{ __('messages.officials_organizational_chart') }}</p>
                
                <div class="org-chart">
                    <div class="org-level level-1">
                        <div class="org-box captain-box">
                            <i class="fas fa-crown"></i>
                            <h4>Punong Barangay</h4>
                            <p>Chief Executive</p>
                        </div>
                    </div>
                    <div class="org-connector"></div>
                    <div class="org-level level-2 flex-column flex-sm-row">
                        <div class="org-box mb-3 mb-sm-0">
                            <i class="fas fa-file-alt"></i>
                            <h4>Secretary</h4>
                            <p>Records & Documentation</p>
                        </div>
                        <div class="org-box">
                            <i class="fas fa-coins"></i>
                            <h4>Treasurer</h4>
                            <p>Financial Management</p>
                        </div>
                    </div>
                    <div class="org-connector"></div>
                    <div class="org-level level-3">
                        <div class="org-box kagawad-box mb-3 mb-sm-0">
                            <i class="fas fa-users"></i>
                            <h4>Sangguniang Barangay</h4>
                            <p>7 Kagawads (Legislative Body)</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    </div>

    @include('barangay_system.partials.fab_footer')

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>
</body>
</html>
