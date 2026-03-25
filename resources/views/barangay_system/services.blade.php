<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Services</title>
    
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
            background: #fff;
        }

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

        /* Services Overview Section */
        .services-overview {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .service-icon {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            padding: 40px;
            text-align: center;
        }

        .service-icon i {
            font-size: 4rem;
            color: white;
        }

        .service-content {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .service-content h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .service-content p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .service-details {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #555;
        }

        .detail-item i {
            color: #C62828;
        }

        .service-uses {
            background: #fafafa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            flex: 1;
        }

        .service-uses h4 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .service-uses ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-uses li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            color: #555;
            font-size: 0.95rem;
        }

        .service-uses li i {
            color: #4CAF50;
            font-size: 0.85rem;
        }

        .service-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 14px 28px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            justify-content: center;
            margin-top: auto;
        }

        .service-btn:hover {
            background: linear-gradient(135deg, #a02020, #C62828);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(198, 40, 40, 0.3);
        }

        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background: white;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-top: 50px;
        }

        .step-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 35px 25px;
            text-align: center;
            position: relative;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(198, 40, 40, 0.12);
            border-color: #C62828;
            background: white;
        }

        .step-number {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background: #C62828;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .step-icon {
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

        .step-card h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .step-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Additional Services Section */
        .additional-services {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .additional-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .additional-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(198, 40, 40, 0.12);
            border-color: #C62828;
        }

        .additional-card .card-icon {
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

        .additional-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .additional-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #C62828;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .card-link:hover {
            color: #a02020;
            gap: 12px;
        }

        /* CTA Section */
        .services-cta {
            padding: 80px 0;
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: white;
        }

        .cta-content {
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }

        .cta-btn.primary {
            background: white;
            color: #C62828;
        }

        .cta-btn.primary:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .cta-btn.secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .cta-btn.secondary:hover {
            background: white;
            color: #C62828;
            transform: translateY(-3px);
        }

        /* =========================================== */
        /* RESPONSIVE STYLES */
        /* =========================================== */

        /* Tablet */
        @media (max-width: 991.98px) {
            .section-title {
                font-size: 2rem;
            }

            .steps-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }

            .service-icon {
                padding: 30px;
            }

            .service-icon i {
                font-size: 3rem;
            }

            .cta-content h2 {
                font-size: 2rem;
            }
        }

        /* Mobile */
        @media (max-width: 767.98px) {
            .services-overview,
            .how-it-works,
            .additional-services,
            .services-faq,
            .services-cta {
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

            .steps-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .step-card {
                padding: 30px 20px;
            }

            .service-content {
                padding: 25px 20px;
            }

            .service-content h3 {
                font-size: 1.3rem;
            }

            .service-details {
                flex-direction: column;
                gap: 10px;
            }

            .detail-item {
                justify-content: center;
            }

            .service-uses {
                padding: 15px;
            }

            .additional-card {
                padding: 25px 20px;
            }

            .cta-content h2 {
                font-size: 1.7rem;
            }

            .cta-content p {
                font-size: 1rem;
            }

            .cta-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .cta-btn {
                width: 100%;
                justify-content: center;
                padding: 14px 30px;
            }
        }

        /* Small Mobile */
        @media (max-width: 575.98px) {
            .section-title {
                font-size: 1.5rem;
            }

            .service-icon {
                padding: 25px;
            }

            .service-icon i {
                font-size: 2.5rem;
            }

            .service-content h3 {
                font-size: 1.2rem;
            }

            .service-btn {
                width: 100%;
            }

            .step-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .additional-card .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Ensure images and icons don't overflow */
        img, svg, i {
            max-width: 100%;
        }
        
        /* Fix for mobile dropdowns */
        @media (max-width: 991.98px) {
            .dropdown-menu-custom {
                background: rgba(255, 255, 255, 0.95);
                border: none;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            
            .navbar-custom {
                padding: 10px 0;
            }
            
            .nav-logo-custom {
                margin-left: 10px;
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
                        <a class="nav-link dropdown-toggle active" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
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
                <h1 class="d-flex flex-column flex-sm-row"><i class="fas fa-concierge-bell bigicon"></i> <span>{{ __('messages.services_hero_title')}}</span></h1>
                <p>{{ __('messages.services_hero_subtitle') }}</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-file-alt"></i>
                        <span>Multiple Document Types</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Fast Processing</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure & Verified</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Services Overview Section -->
        <section class="services-overview">
            <div class="container">
                <h2 class="section-title">{{ __('messages.services_service_title') }}</h2>
                <p class="section-subtitle">{{ __('messages.services_service_subtitle') }}</p>
                
                <div class="row g-4">
                    <!-- Barangay Clearance -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="service-content">
                                <h3>Barangay Clearance</h3>
                                <p>{{ __('messages.services_clearance_desc') }}</p>
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-peso-sign"></i>
                                        <span>Fee: Depending on purpose</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ __('messages.services_clearance_duration') }}</span>
                                    </div>
                                </div>
                                <div class="service-uses">
                                    <h4>Common Uses:</h4>
                                    <ul>
                                        <li><i class="fas fa-check"></i> Employment requirements</li>
                                        <li><i class="fas fa-check"></i> Business permit applications</li>
                                        <li><i class="fas fa-check"></i> NBI clearance processing</li>
                                        <li><i class="fas fa-check"></i> Loan applications</li>
                                    </ul>
                                </div>
                                <a href="{{ route('clearance')}}" class="service-btn">
                                    <i class="fas fa-arrow-right"></i> Request Now
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate of Residency -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-house-user"></i>
                            </div>
                            <div class="service-content">
                                <h3>Certificate of Residency</h3>
                                <p>{{ __('messages.services_residency_desc') }}</p>
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-peso-sign"></i>
                                        <span>Fee: Free</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ __('messages.services_residency_duration') }}</span>
                                    </div>
                                </div>
                                <div class="service-uses">
                                    <h4>Common Uses:</h4>
                                    <ul>
                                        <li><i class="fas fa-check"></i> Passport applications</li>
                                        <li><i class="fas fa-check"></i> School enrollment</li>
                                        <li><i class="fas fa-check"></i> Government ID applications</li>
                                        <li><i class="fas fa-check"></i> Voter registration</li>
                                    </ul>
                                </div>
                                <a href="{{ route('residency') }}" class="service-btn">
                                    <i class="fas fa-arrow-right"></i> Request Now
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate of Indigency -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="service-content">
                                <h3>Certificate of Indigency</h3>
                                <p>Document certifying that a resident belongs to the indigent sector and may qualify for government assistance programs and discounts.</p>
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-peso-sign"></i>
                                        <span>Fee: FREE</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>1-3 Business Days</span>
                                    </div>
                                </div>
                                <div class="service-uses">
                                    <h4>Common Uses:</h4>
                                    <ul>
                                        <li><i class="fas fa-check"></i> Medical assistance</li>
                                        <li><i class="fas fa-check"></i> Educational scholarships</li>
                                        <li><i class="fas fa-check"></i> Burial assistance</li>
                                        <li><i class="fas fa-check"></i> Government aid programs</li>
                                    </ul>
                                </div>
                                <a href="{{ route('indigency') }}" class="service-btn">
                                    <i class="fas fa-arrow-right"></i> Request Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">{{ __('messages.services_how_it_works_title') }}</h2>
                <p class="section-subtitle">{{ __('messages.services_how_it_works_subtitle') }}</p>
                
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>{{ __('messages.services_step1_title') }}</h3>
                        <p>{{ __('messages.services_step1_desc') }}</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3>{{ __('messages.services_step2_title') }}</h3>
                        <p>{{ __('messages.services_step2_desc') }}</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <h3>{{ __('messages.services_step3_title') }}</h3>
                        <p>{{ __('messages.services_step3_desc') }}</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon">
                            <i class="fas fa-file-download"></i>
                        </div>
                        <h3>{{ __('messages.services_step4_title') }}</h3>
                        <p>{{ __('messages.services_step4_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Services Section -->
        <section class="additional-services">
            <div class="container">
                <h2 class="section-title">{{ __('messages.services_other_title') }}</h2>
                <p class="section-subtitle">{{ __('messages.services_other_subtitle') }}.</p>
                
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="additional-card">
                            <div class="card-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3>Incident Report</h3>
                            <p>{{ __('messages.services_blotter_card_desc') }}</p>
                            <a href="{{ route('incident') }}" class="card-link">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="additional-card">
                            <div class="card-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3>Track Request</h3>
                            <p>{{ __('messages.services_track_card_desc') }}</p>
                            <a href="{{ route('track_request') }}" class="card-link">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="additional-card">
                            <div class="card-icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h3>Help & Support</h3>
                            <p>{{ __('messages.services_help_card_desc') }}</p>
                            <a href="{{ route('contacts') }}" class="card-link">
                                Contact Us <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
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
                            <span>What can I request through this Barangay Hulo Online Portal?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You can submit requests for Barangay Clearance, Certificate of Residency, and Certificate of Indigency directly in the portal. You can also file Incident Reports and use Track Request to monitor your application status.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How does this system process my request?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>After you submit the online form and required files, your request is forwarded to barangay staff for review. The portal records your reference details, and you can check progress anytime in the Track Request page.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How can I track updates from the system?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Use the Track Request feature to view the current status of your submitted application. For community updates, the portal also provides Announcements and Events/Projects pages so residents can stay informed.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What else can I do in the portal besides document requests?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Aside from requesting documents, you can submit Incident Reports, browse barangay announcements, check community events and projects, and access contact details for barangay assistance.</p>
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
    <script src="{{ asset('js/faq.js') }}"></script>
</body>
</html>
