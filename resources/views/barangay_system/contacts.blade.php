<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Contact Us</title>
    
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
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">

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

        /* Full Width Background Sections */
        .section-bg {
            background: #f8f9fa;
            width: 100%;
            padding: 80px 0;
        }

        /* Contact Info Cards */
        .contact-info-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 2.2rem;
            font-weight: 700;
            color: #C62828;
        }

        .contact-card {
            background: #fff;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.2);
        }

        .contact-card-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C62828, #7a2323);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .contact-card:hover .contact-card-icon {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(198, 40, 40, 0.3);
        }

        .contact-card-icon i {
            font-size: 1.6rem;
            color: #fff;
        }

        .contact-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 12px;
        }

        .contact-card p {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 6px;
        }

        .contact-card p a {
            color: #C62828;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .contact-card p a:hover {
            color: #7a2323;
            text-decoration: underline;
        }

        .card-link {
            display: inline-block;
            color: #C62828;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .card-link:hover {
            color: #7a2323;
            gap: 8px;
        }

        .card-link i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .card-link:hover i {
            transform: translateX(4px);
        }

        .card-note {
            display: block;
            font-size: 0.82rem;
            color: #888;
            margin-top: 10px;
            font-style: italic;
        }

        /* Contact Form Section */
        .contact-form-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .form-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .form-header h2 {
            font-size: 2rem;
            color: #C62828;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-header p {
            color: #666;
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-form-card {
            background: #fff;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #C62828;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 13px 16px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #333;
            width: 100%;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit-contact {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: #fff;
            border: none;
            padding: 14px 35px;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit-contact:hover {
            background: linear-gradient(135deg, #7a2323, #C62828);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        }

        .btn-submit-contact:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Success Message */
        .success-message {
            text-align: center;
            padding: 60px 20px;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease;
        }

        .success-icon i {
            font-size: 2.5rem;
            color: #fff;
        }

        .success-message h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
        }

        .success-message p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            max-width: 450px;
            margin: 0 auto 25px;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Departments Section */
        .departments-section {
            padding: 80px 0;
            background: #fff;
        }

        .department-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .department-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #C62828, #7a2323);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            transform-origin: top;
        }

        .department-card:hover::before {
            transform: scaleY(1);
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(198, 40, 40, 0.1);
        }

        .dept-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(122, 35, 35, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        .department-card:hover .dept-icon {
            background: linear-gradient(135deg, #C62828, #7a2323);
        }

        .dept-icon i {
            font-size: 1.3rem;
            color: #C62828;
            transition: color 0.3s ease;
        }

        .department-card:hover .dept-icon i {
            color: #fff;
        }

        .department-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 10px;
        }

        .department-card p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .dept-contact {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .dept-contact span {
            font-size: 0.85rem;
            color: #555;
        }

        .dept-contact span i {
            color: #C62828;
            width: 18px;
            margin-right: 6px;
            font-size: 0.8rem;
        }

        /* Social Media Section */
        .social-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .social-cards-single {
            max-width: 700px;
            margin: 40px auto 0;
        }

        .social-card-featured {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 45px 40px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #e0e8ff;
            background: #f0f4ff;
            color: #1a1a2e;
        }

        .social-card-featured:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 40px rgba(24, 119, 242, 0.15);
            border-color: #1877F2;
            color: #1a1a2e;
            text-decoration: none;
        }

        .social-featured-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #1877F2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(24, 119, 242, 0.25);
        }

        .social-card-featured:hover .social-featured-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 28px rgba(24, 119, 242, 0.35);
        }

        .social-featured-icon i {
            font-size: 2rem;
            color: #fff;
        }

        .social-featured-info h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .social-featured-info p {
            font-size: 1rem;
            color: #1877F2;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .social-featured-desc {
            display: block;
            font-size: 0.95rem;
            color: #666;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto;
        }

        .follow-btn-featured {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 22px;
            padding: 12px 32px;
            border-radius: 50px;
            background: #1877F2;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .social-card-featured:hover .follow-btn-featured {
            background: #0d65d9;
            box-shadow: 0 6px 20px rgba(24, 119, 242, 0.3);
        }

        @media (max-width: 1200px) {
            .contact-info-section,
            .contact-form-section,
            .departments-section,
            .social-section {
                padding: 70px 0;
            }
        }

        @media (max-width: 992px) {     
            .contact-info-section,
            .contact-form-section,
            .departments-section,
            .social-section {
                padding: 60px 0;
            }
            
            .contact-form-card {
                padding: 35px;
            }
            
            .social-card-featured {
                padding: 35px 30px;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-form-card {
                padding: 25px 20px;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .department-card {
                padding: 25px 20px;
            }
            
            .social-card-featured {
                padding: 30px 20px;
            }
            
            .social-featured-icon {
                width: 65px;
                height: 65px;
            }
            
            .social-featured-icon i {
                font-size: 1.6rem;
            }
            
            .social-featured-info h3 {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 1.6rem;
                margin-bottom: 40px;
            }
        }

        @media (max-width: 576px) {         
            .contact-info-section,
            .contact-form-section,
            .departments-section,
            .social-section {
                padding: 45px 0;
            }
            
            .contact-card {
                padding: 25px 18px;
            }
            
            .contact-card-icon {
                width: 60px;
                height: 60px;
            }
            
            .contact-card-icon i {
                font-size: 1.3rem;
            }
            
            .contact-form-card {
                padding: 20px 15px;
            }
            
            .form-header h2 {
                font-size: 1.3rem;
            }
            
            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 10px 14px;
                font-size: 0.9rem;
            }
            
            .btn-submit-contact {
                padding: 12px 25px;
                font-size: 0.95rem;
            }
            
            .social-card-featured {
                padding: 25px 15px;
            }
            
            .social-featured-icon {
                width: 60px;
                height: 60px;
            }
            
            .social-featured-icon i {
                font-size: 1.4rem;
            }
            
            .social-featured-info h3 {
                font-size: 1.1rem;
            }
            
            .social-featured-info p {
                font-size: 0.9rem;
            }
            
            .social-featured-desc {
                font-size: 0.88rem;
            }
            
            .follow-btn-featured {
                padding: 10px 24px;
                font-size: 0.9rem;
            }
            
            .dept-icon {
                width: 45px;
                height: 45px;
            }
            
            .dept-icon i {
                font-size: 1.1rem;
            }
            
            .department-card h3 {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.4rem;
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
                            <li><a class="dropdown-link" href="{{ route('services') }}"><i class="fas fa-list"></i> All Services</a></li>
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
                            <li><a class="dropdown-link" href="{{ route('incident') }}"><i class="fas fa-clipboard-list"></i> Blotter Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('contacts') }}"><i class="fas fa-phone"></i> Contact</a>
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
                <h1><i class="fas fa-headset bigicon"></i> Contact Us</h1>
                <p>Have questions or need assistance? We're here to help. Reach out to us through any of the channels below.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Mon-Fri: 8:00 AM - 5:00 PM</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-phone"></i>
                        <span>Quick Response Time</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-comments"></i>
                        <span>Multiple Channels</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">

        <!-- Contact Info Cards Section -->
        <section class="contact-info-section">
            <div class="container">
                <h2 class="section-title">Get In Touch</h2>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Visit Us</h3>
                            <p>1 M. Blas St, Barangay Hulong Duhat, Malabon City, Metro Manila</p>
                            <a href="{{ route('map') }}" class="card-link">View on Map <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3>Call Us</h3>
                            <p><a href="tel:+6329876543">(02) 987-6543</a></p>
                            <p><a href="tel:+6329871234">(02) 987-1234</a></p>
                            <span class="card-note">Available during office hours</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email Us</h3>
                            <p><a href="mailto:info@barangayhulo.gov.ph">info@barangayhulo.gov.ph</a></p>
                            <p><a href="mailto:support@barangayhulo.gov.ph">support@barangayhulo.gov.ph</a></p>
                            <span class="card-note">We reply within 24 hours</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Office Hours</h3>
                            <p>Monday - Friday<br>8:00 AM - 5:00 PM</p>
                            <p>Saturday<br>8:00 AM - 12:00 PM</p>
                            <span class="card-note">Closed on Sundays & Holidays</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Departments Section -->
        <section class="departments-section">
            <div class="container">
                <h2 class="section-title">Department Directory</h2>
                
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3>Barangay Captain's Office</h3>
                            <p>For concerns regarding barangay governance, ordinances, and executive matters.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 101</span>
                                <span><i class="fas fa-envelope"></i> captain@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3>Records & Documentation</h3>
                            <p>For document requests, barangay clearance, certifications, and record verification.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 102</span>
                                <span><i class="fas fa-envelope"></i> records@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>Peace & Order Office</h3>
                            <p>For security concerns, blotter reports, and peace and order matters.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 103</span>
                                <span><i class="fas fa-envelope"></i> peace@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h3>Health & Sanitation</h3>
                            <p>For health programs, medical missions, and sanitation concerns.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 104</span>
                                <span><i class="fas fa-envelope"></i> health@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h3>Social Welfare</h3>
                            <p>For social services, senior citizen concerns, and community assistance programs.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 105</span>
                                <span><i class="fas fa-envelope"></i> welfare@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="department-card">
                            <div class="dept-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h3>Treasury & Finance</h3>
                            <p>For payment inquiries, fee structures, and financial matters.</p>
                            <div class="dept-contact">
                                <span><i class="fas fa-phone"></i> (02) 987-6543 loc. 106</span>
                                <span><i class="fas fa-envelope"></i> treasury@barangayhulo.gov.ph</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Media Section -->
        <section class="social-section">
            <div class="container">
                <h2 class="section-title">Follow Us on Facebook</h2>
                
                <div class="social-cards-single">
                    <a href="#" class="social-card-featured">
                        <div class="social-featured-icon">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="social-featured-info">
                            <h3>Barangay Hulong Duhat</h3>
                            <p>@BarangayHulongDuhat</p>
                            <span class="social-featured-desc">Get the latest updates, announcements, and community news on our official Facebook page.</span>
                        </div>
                        <span class="follow-btn-featured"><i class="fab fa-facebook-f"></i> Follow Us on Facebook</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                
                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What are the office hours of the barangay hall?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>The barangay hall is open Monday to Friday from 8:00 AM to 5:00 PM, and Saturday from 8:00 AM to 12:00 PM. We are closed on Sundays and national holidays.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How can I file a complaint or concern?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You can file complaints through our online form, via email, or by visiting the barangay hall in person during office hours. For urgent matters, please contact our Peace & Order Office directly.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does it take to receive a response?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>We aim to respond to all inquiries within 24-48 hours during business days. Urgent matters are prioritized accordingly.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I request documents online?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can request Barangay Clearance, Certificate of Residency, and Certificate of Indigency through our <a href="{{ route('services') }}">Services page</a>.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Who should I contact for emergencies?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>For emergencies, call 911. For barangay-related emergencies, contact the Peace & Order Office at (02) 987-6543 loc. 103 or the Barangay Tanod hotline.</p>
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
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
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
                            <a href="{{ route('track_request') }}" class="footer-link">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>
    
</body>
</html>