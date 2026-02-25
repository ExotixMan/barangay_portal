<!DOCTYPE html>
<html lang="en">

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

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">


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
            margin-bottom: 5px;
            font-weight: 600;
        }

        .official-info .position {
            display: block;
            color: #C62828;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .official-info .committee {
            display: block;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 15px;
            padding: 5px 12px;
            background: #f8f9fa;
            border-radius: 15px;
            display: inline-block;
        }

        .official-info p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .contact-info a {
            width: 40px;
            height: 40px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #C62828;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-info a:hover {
            background: #C62828;
            color: white;
            transform: translateY(-3px);
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
            
            .contact-info a {
                width: 44px;
                height: 44px;
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
                <h1 class="flex-column flex-sm-row"><i class="fas fa-users mb-3 mb-sm-0"></i> Barangay Officials</h1>
                <p>Meet the dedicated leaders serving Barangay Hulong Duhat. Our officials work tirelessly to ensure the welfare and progress of our community.</p>
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
        <!-- Punong Barangay Section -->
        <section class="captain-section">
            <div class="container">
                <h2 class="section-title">Punong Barangay</h2>
                <p class="section-subtitle">The head of Barangay Hulong Duhat, leading our community with dedication and integrity.</p>
                
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
                        <h3>Hon. Wen Santos dela Cruz</h3>
                        <span class="position">Punong Barangay</span>
                        <p class="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        <div class="captain-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Term: 2023 - 2026</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span>captain@barangayhulo.gov.ph</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <span>(02) 987-6543</span>
                            </div>
                        </div>
                        <div class="captain-message">
                            <h4><i class="fas fa-quote-left"></i> Message from the Captain</h4>
                            <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Maecenas sed enim ut sem viverra aliquet eget sit amet."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sangguniang Barangay Section -->
        <section class="kagawad-section">
            <div class="container">
                <h2 class="section-title">Sangguniang Barangay</h2>
                <p class="section-subtitle">The legislative body of the barangay, working together to create policies for community development.</p>
                
                <div class="row g-3 g-lg-4">
                    <!-- Kagawad 1 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Maria Santos</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Health</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:maria@barangayhulo.gov.ph" aria-label="Email Maria Santos"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Maria Santos"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 2 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Pedro Reyes</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Peace & Order</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:pedro@barangayhulo.gov.ph" aria-label="Email Pedro Reyes"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Pedro Reyes"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 3 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Ana Garcia</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Education</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:ana@barangayhulo.gov.ph" aria-label="Email Ana Garcia"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Ana Garcia"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 4 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Jose Mendoza</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Infrastructure</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:jose@barangayhulo.gov.ph" aria-label="Email Jose Mendoza"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Jose Mendoza"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 5 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Rosa Villanueva</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Women & Family</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:rosa@barangayhulo.gov.ph" aria-label="Email Rosa Villanueva"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Rosa Villanueva"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 6 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Roberto Cruz</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Environment</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:roberto@barangayhulo.gov.ph" aria-label="Email Roberto Cruz"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Roberto Cruz"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kagawad 7 -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="official-card">
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Lorna Bautista</h3>
                                <span class="position">Kagawad</span>
                                <span class="committee">Committee on Budget & Finance</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</p>
                                <div class="contact-info">
                                    <a href="mailto:lorna@barangayhulo.gov.ph" aria-label="Email Lorna Bautista"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Lorna Bautista"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Other Officials Section -->
        <section class="other-officials">
            <div class="container">
                <h2 class="section-title">Other Officials</h2>
                <p class="section-subtitle">Key personnel supporting the barangay administration.</p>
                
                <div class="row g-4 justify-content-center">
                    <!-- SK Chairman -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="special-official-card sk">
                            <div class="card-header-badge">
                                <i class="fas fa-star"></i> SK Chairman
                            </div>
                            <div class="official-image">
                                <div class="image-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="official-info">
                                <h3>Hon. Miguel Ramos</h3>
                                <span class="position">SK Chairman</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                                <div class="contact-info">
                                    <a href="mailto:sk@barangayhulo.gov.ph" aria-label="Email SK Chairman"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call SK Chairman"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <h3>Elena Fernandez</h3>
                                <span class="position">Barangay Secretary</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                                <div class="contact-info">
                                    <a href="mailto:secretary@barangayhulo.gov.ph" aria-label="Email Secretary"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Secretary"><i class="fas fa-phone"></i></a>
                                </div>
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
                                <h3>Carmen Aquino</h3>
                                <span class="position">Barangay Treasurer</span>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                                <div class="contact-info">
                                    <a href="mailto:treasurer@barangayhulo.gov.ph" aria-label="Email Treasurer"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:+639123456789" aria-label="Call Treasurer"><i class="fas fa-phone"></i></a>
                                </div>
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
                <p class="section-subtitle">Understanding how our barangay government is organized.</p>
                
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
                    <div class="org-level level-3 flex-column flex-sm-row">
                        <div class="org-box kagawad-box mb-3 mb-sm-0">
                            <i class="fas fa-users"></i>
                            <h4>Sangguniang Barangay</h4>
                            <p>7 Kagawads (Legislative Body)</p>
                        </div>
                        <div class="org-box sk-box">
                            <i class="fas fa-star"></i>
                            <h4>SK Chairman</h4>
                            <p>Youth Representative</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
</body>
</html>