<!DOCTYPE html>
<html lang="en">
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
                <h1 class="d-flex flex-column flex-sm-row"><i class="fas fa-concierge-bell bigicon"></i> <span>Barangay Services</span></h1>
                <p>Access a wide range of barangay services online. Request documents, track applications, and get assistance without leaving your home.</p>
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
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">Choose from our available barangay services below. Each service can be requested online for your convenience.</p>
                
                <div class="row g-4">
                    <!-- Barangay Clearance -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="service-content">
                                <h3>Barangay Clearance</h3>
                                <p>Official document certifying that you are a person of good moral character and have no pending case or derogatory record in the barangay.</p>
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-peso-sign"></i>
                                        <span>Fee: ₱100.00</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>1-3 Business Days</span>
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
                                <a href="barangay_clearance.html" class="service-btn">
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
                                <p>Official proof that you are a bonafide resident of Barangay Hulong Duhat, required for various government and private transactions.</p>
                                <div class="service-details">
                                    <div class="detail-item">
                                        <i class="fas fa-peso-sign"></i>
                                        <span>Fee: ₱50.00</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>1-2 Business Days</span>
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
                                <a href="certificate_residency.html" class="service-btn">
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
                                        <span>1-2 Business Days</span>
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
                                <a href="certificate_indigency.html" class="service-btn">
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
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Follow these simple steps to request any barangay document online.</p>
                
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3>Create Account</h3>
                        <p>Register for an account using your valid information</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3>Fill Out Form</h3>
                        <p>Complete the online application form accurately</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <h3>Submit Requirements</h3>
                        <p>Upload necessary documents and submit your request</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon">
                            <i class="fas fa-file-download"></i>
                        </div>
                        <h3>Claim Document</h3>
                        <p>Pick up or receive your document once approved</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Services Section -->
        <section class="additional-services">
            <div class="container">
                <h2 class="section-title">Other Services</h2>
                <p class="section-subtitle">Explore other services available at Barangay Hulong Duhat.</p>
                
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="additional-card">
                            <div class="card-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3>Blotter Report</h3>
                            <p>File complaints, report incidents, or document disputes for official record and mediation.</p>
                            <a href="blotter_report.html" class="card-link">
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
                            <p>Check the status of your document request using your reference number.</p>
                            <a href="#" class="card-link">
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
                            <p>Need assistance? Contact our office or visit us for personalized help.</p>
                            <a href="homepage.html#contact" class="card-link">
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
                            <span>What are the requirements for requesting documents?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Basic requirements include a valid ID, proof of residency (utility bill or voter's ID), and a completed application form. Specific requirements may vary per document type.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does it take to process my request?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Processing times vary: Barangay Clearance takes 1-3 business days, while Certificate of Residency and Indigency typically take 1-2 business days.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I request documents for someone else?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can request on behalf of immediate family members. You'll need to provide an authorization letter and valid IDs of both parties.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What payment methods are accepted?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>We accept cash payments at the barangay hall. Online payment options through GCash and bank transfer are also available for select services.</p>
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
    <script src="{{ asset('js/faq.js') }}"></script>
</body>
</html>