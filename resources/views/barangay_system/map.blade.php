<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Interactive Map</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
        .history-hero {
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.9), rgba(122, 35, 35, 0.9)), 
                        url('https://images.unsplash.com/photo-1590691565924-90d0a14443a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&h=600&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 140px 0 80px;
            text-align: center;
            position: relative;
        }

        .history-hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .breadcrumb a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: white;
        }

        .breadcrumb span {
            color: rgba(255,255,255,0.6);
        }

        .history-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .history-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* =========================================== */
        /* INTERACTIVE MAP */
        /* =========================================== */
        .map-container-wrapper {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            margin-top: -2rem;
            position: relative;
            z-index: 1;
        }

        #barangay-map {
            width: 100%;
            height: 500px;
            border: none;
        }

        .map-controls {
            background: white;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .map-layer-btn {
            border: 2px solid #eee;
            background: white;
            color: #555;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-layer-btn.active,
        .map-layer-btn:hover {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border-color: #C62828;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.2);
        }

        /* Section Title Styling from Index */
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
            margin-bottom: 50px;
        }

        /* =========================================== */
        /* LOCATIONS SECTION - UPDATED WITH INDEX THEME */
        /* =========================================== */
        .locations-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .location-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-bottom: 25px;
            border: 2px solid transparent;
        }

        .location-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(198, 40, 40, 0.15);
            text-decoration: none;
            color: #333;
            border-color: #C62828;
        }

        .location-card::before {
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

        .location-card:hover::before {
            transform: scaleX(1);
        }

        .location-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(198, 40, 40, 0.05));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: #C62828;
            font-size: 1.5rem;
        }

        .location-card h5 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #C62828;
            font-weight: 600;
            min-height: 2.8rem;
        }

        .address-text {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
            min-height: 2.5rem;
        }

        .location-services {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 15px 0;
            min-height: 2.5rem;
        }

        .service-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .location-hours {
            color: #666;
            font-size: 0.85rem;
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 1.8rem;
        }

        .btn-view-map {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 15px;
        }

        .btn-view-map:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(198, 40, 40, 0.3);
            color: white;
        }

        /* =========================================== */
        /* CUSTOM MARKERS - UPDATED WITH INDEX THEME */
        /* =========================================== */
        .custom-marker {
            background: #C62828;
            border: 3px solid white;
            border-radius: 50%;
            width: 40px !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .marker-hall { background: #C62828; }
        .marker-health { background: #4CAF50; }
        .marker-school { background: #2196F3; }
        .marker-chapel { background: #FF9800; }
        .marker-police { background: #9C27B0; }
        .marker-evac { background: #795548; }
        .marker-market { background: #607D8B; }
        .marker-playground { background: #009688; }

        /* =========================================== */
        /* RESPONSIVE DESIGN - ADDED FOR MOBILE */
        /* =========================================== */

        @media (max-width: 1366px) {
            .history-hero h1 {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 768px) {
            .history-hero h1 {
                font-size: 2.2rem;
            }
            
            .history-hero {
                padding: 120px 0 60px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            #barangay-map {
                height: 400px;
            }
            
            .map-container-wrapper {
                margin-top: -1rem;
            }
            
            .map-controls {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .map-layer-btn {
                width: 100%;
                justify-content: center;
            }
            
            .locations-section {
                padding: 60px 0;
            }

            /* Mobile responsiveness for location cards */
            .location-card {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .location-card h5 {
                font-size: 1.1rem;
                min-height: auto;
            }
            
            .address-text {
                font-size: 0.85rem;
                min-height: auto;
            }
            
            .location-services {
                min-height: auto;
            }
            
            .location-hours {
                min-height: auto;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .history-hero h1 {
                font-size: 1.8rem;
            }
            
            .history-hero {
                padding: 120px 0 60px;
            }
            
            .logo-subtitle {
                font-size: 1rem;
            }
            
            .logo-title {
                display: none;
            }
            
            #barangay-map {
                height: 350px;
            }
            
            .location-card {
                margin-bottom: 15px;
                padding: 15px;
            }
            
            .address-badge {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
                flex-direction: column;
                text-align: center;
                gap: 5px;
            }
            
            .location-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .btn-view-map {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 400px) {
            .hero-content h1 {
                font-size: 1.6rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
            }
            
            #barangay-map {
                height: 300px;
            }
            
            .map-layer-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Mobile-specific adjustments for map section */
        @media (max-width: 768px) {
            .py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
        }

        /* Smooth scroll target for map */
        .map-target {
            position: absolute;
            top: -80px;
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
    <section class="history-hero">
        <div class="history-hero-content">
            <h1>Barangay Hulong Duhat Interactive Map</h1>
            <p>Explore important locations and navigate through our community with this interactive map.</p>
        </div>
    </section>

    <!-- Interactive Map Section -->
    <section class="py-5" id="interactive-map">
        <div class="map-target"></div>
        <div class="container">
            <div class="map-container-wrapper">
                <!-- Map Controls -->
                <div class="map-controls d-flex flex-wrap gap-2 justify-content-center">
                    <button class="map-layer-btn active" data-layer="streets">
                        <i class="fas fa-road me-2"></i> Street View
                    </button>
                    <button class="map-layer-btn" data-layer="satellite">
                        <i class="fas fa-satellite me-2"></i> Satellite
                    </button>
                    <button class="map-layer-btn" onclick="showAllLocations()">
                        <i class="fas fa-eye me-2"></i> Show All Locations
                    </button>
                </div>
                
                <!-- Map Container -->
                <div id="barangay-map"></div>
            </div>
        </div>
    </section>

    <!-- Important Locations Section -->
    <section class="locations-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Important Locations</h2>
                <p class="section-subtitle">Key establishments and facilities in Barangay Hulong Duhat</p>
            </div>
            
            <div class="row g-4">
                <!-- Barangay Hall -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Barangay Hall & Office</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                74 Gov Andres Gabriel St
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Government</span>
                                <span class="service-badge">Services</span>
                                <span class="service-badge">Permits</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Mon-Fri: 8:00AM-5:00PM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('hall')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Health Center -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Hulong Duhat Health Center</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Gov Andres Gabriel St
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Medical</span>
                                <span class="service-badge">Vaccination</span>
                                <span class="service-badge">Checkup</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Mon-Sat: 7:00AM-7:00PM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('health')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Elementary School -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Hulong Duhat Elementary School</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Naval St, near Barangay Hall
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Education</span>
                                <span class="service-badge">Public School</span>
                                <span class="service-badge">K-6</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Mon-Fri: 7:30AM-4:30PM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('school')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chapel -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-church"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">San Lorenzo Ruiz Chapel</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                31 A Gervacio St
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Religious</span>
                                <span class="service-badge">Worship</span>
                                <span class="service-badge">Community</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Mass: Sat 5PM, Sun 8AM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('chapel')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Police Outpost -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Barangay Police Outpost</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Gabriel St corner
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Security</span>
                                <span class="service-badge">Emergency</span>
                                <span class="service-badge">Assistance</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                24/7 Service Available
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('police')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Evacuation Center -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Multi-Purpose Evacuation Center</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Adjacent to Barangay Hall
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Emergency</span>
                                <span class="service-badge">Shelter</span>
                                <span class="service-badge">Events</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Open for Emergencies
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('evac')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Public Market -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Hulong Duhat Public Market</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Women's Club St Area
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Fresh Produce</span>
                                <span class="service-badge">Groceries</span>
                                <span class="service-badge">Food</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Daily: 5:00AM-8:00PM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('market')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Playground -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="location-card">
                        <div class="location-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="card-content">
                            <h5 class="fw-bold">Children's Playground & Park</h5>
                            <p class="address-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Near Elementary School
                            </p>
                            <div class="location-services">
                                <span class="service-badge">Recreation</span>
                                <span class="service-badge">Family</span>
                                <span class="service-badge">Exercise</span>
                            </div>
                            <p class="location-hours">
                                <i class="fas fa-clock me-1"></i>
                                Open: 6:00AM-8:00PM
                            </p>
                            <button class="btn-view-map" onclick="scrollToMapAndShowLocation('playground')">
                                <i class="fas fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Map initialization
        let map;
        let markers = {};
        let layers = {};
        let currentLayer = 'streets';
        let barangayBoundary;

        // Coordinates for Hulong Duhat, Malabon (approximate center)
        const BARANGAY_CENTER = [14.677913823133792, 120.94193632084244]; // Approximate coordinates for Hulong Duhat
        
        // Location coordinates (relative to center)
        const locations = {
            hall: {
                coords: [14.677913823133792, 120.94193632084244], // Barangay Hall - main address
                name: 'Barangay Hall',
                address: 'Gabriel 2, 425, 74 Gov Andres Gabriel St, Hulong Duhat, Malabon, Metro Manila',
                type: 'hall',
                icon: 'fa-landmark'
            },
            health: {
                coords: [14.677860632259058, 120.94163792509893],
                name: 'Health Center',
                address: 'MWHR+4MR, Don Basilio Bautista Blvd, Malabon, 1470 Metro Manila',
                type: 'health',
                icon: 'fa-clinic-medical'
            },
            school: {
                coords: [14.67703962222242, 120.94099488318462],
                name: 'Malabon Elementary School',
                address: 'MWGR+Q9X, Naval St, Malabon, Metro Manila',
                type: 'school',
                icon: 'fa-school'
            },
            chapel: {
                coords: [14.678660686615306, 120.94038622417362],
                name: 'Barangay Chapel',
                address: '31 A Gervacio St, Malabon, 1470 Metro Manila',
                type: 'chapel',
                icon: 'fa-church'
            },
            police: {
                coords: [14.677847, 120.942039],
                name: 'Police Outpost',
                address: '27, 1471 Don Basilio Bautista Blvd, Malabon, Metro Manila',
                type: 'police',
                icon: 'fa-shield-alt'
            },
            evac: {
                coords: [14.67723781254434, 120.94165939357208],
                name: 'Evacuation Area',
                address: 'Naval St, Malabon, Metro Manila',
                type: 'evac',
                icon: 'fa-home'
            },
            market: {
                coords: [14.676013456903119, 120.9412932266691],
                name: 'Public Market',
                address: 'Women\'s Club St, Malabon, Metro Manila',
                type: 'market',
                icon: 'fa-shopping-cart'
            },
            playground: {
                coords: [14.676231517438561, 120.94201588080502],
                name: 'Children\'s Playground',
                address: 'Women\'s Club St, Malabon, 1470 Metro Manila',
                type: 'playground',
                icon: 'fa-child'
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            setupEventListeners();
        });

        function initializeMap() {
            // Initialize map centered on Hulong Duhat
            map = L.map('barangay-map').setView(BARANGAY_CENTER, 18);
            
            // Define tile layers
            layers.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                minZoom: 16
            });
            
            layers.satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                minZoom: 16
            });
            
            // Add default layer
            layers.streets.addTo(map);
            
            // Create approximate barangay boundary (polygon around the center)
            const boundaryCoordinates = [
                [14.677626, 120.946067],
                [14.675282, 120.940417],
                [14.677820, 120.939894],
                [14.680481795397423, 120.93851439586514],
                [14.682998651828333, 120.9366379395298],
                [14.684251, 120.937283],
                [14.684374, 120.938723],
                [14.682156, 120.942751]
            ];
            
            barangayBoundary = L.polygon(boundaryCoordinates, {
                color: '#C62828',
                fillColor: '#C62828',
                fillOpacity: 0.1,
                weight: 3,
                dashArray: '5, 5'
            }).addTo(map);
            
            // Add markers for each location
            Object.keys(locations).forEach(key => {
                const loc = locations[key];
                const icon = L.divIcon({
                    html: `<div class="custom-marker marker-${loc.type}"><i class="fas ${loc.icon}"></i></div>`,
                    className: 'custom-marker-div',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });
                
                markers[key] = L.marker(loc.coords, { icon: icon })
                    .addTo(map)
                    .bindPopup(`
                        <div style="min-width: 200px;">
                            <h6 style="color: #C62828; margin: 0 0 5px 0;">${loc.name}</h6>
                            <p style="margin: 0 0 5px 0; font-size: 0.9rem;">${loc.address}</p>
                            <small style="color: #666;">Click the location cards to navigate</small>
                        </div>
                    `);
            });
            
            // Fit map to show all markers and boundary
            const group = new L.featureGroup([...Object.values(markers), barangayBoundary]);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        function setupEventListeners() {
            // Map layer switcher
            document.querySelectorAll('.map-layer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const layer = this.dataset.layer;
                    if (layer) {
                        // Update active button
                        document.querySelectorAll('.map-layer-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Switch layer
                        if (currentLayer !== layer) {
                            map.removeLayer(layers[currentLayer]);
                            layers[layer].addTo(map);
                            currentLayer = layer;
                            
                            // Re-add boundary and markers
                            barangayBoundary.addTo(map);
                            Object.values(markers).forEach(marker => marker.addTo(map));
                        }
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });
        }

        // Show all locations on map
        window.showAllLocations = function() {
            const group = new L.featureGroup([...Object.values(markers), barangayBoundary]);
            map.fitBounds(group.getBounds().pad(0.1));
        };

        // Show specific location
        window.showLocation = function(locationId) {
            if (markers[locationId]) {
                map.setView(markers[locationId].getLatLng(), 18);
                markers[locationId].openPopup();
                
                // Add animation to marker
                const marker = markers[locationId];
                const originalIcon = marker.options.icon;
                
                // Create a pulsating effect
                const pulseIcon = L.divIcon({
                    html: `<div class="custom-marker marker-${locations[locationId].type}" style="animation: pulse 1s infinite;"><i class="fas ${locations[locationId].icon}"></i></div>`,
                    className: 'custom-marker-div',
                    iconSize: [50, 50],
                    iconAnchor: [25, 50]
                });
                
                marker.setIcon(pulseIcon);
                
                // Return to original icon after 3 seconds
                setTimeout(() => {
                    marker.setIcon(originalIcon);
                }, 3000);
            }
        };

        window.scrollToMapAndShowLocation = function(locationId) {
            // First scroll to the map section
            const mapSection = document.getElementById('interactive-map');
            if (mapSection) {
                // Calculate offset for fixed navbar
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const mapSectionTop = mapSection.offsetTop - navbarHeight;
                
                // Smooth scroll to map
                window.scrollTo({
                    top: mapSectionTop,
                    behavior: 'smooth'
                });
                
                // Wait for scroll to complete, then show location
                setTimeout(() => {
                    window.showLocation(locationId);
                }, window.innerWidth <= 768 ? 400 : 500); // Faster on mobile
            } else {
                // Fallback if map section not found
                window.showLocation(locationId);
            }
        };

        // Add CSS for pulse animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);

        // Mobile-specific map adjustments
        function adjustMapForMobile() {
            if (window.innerWidth <= 768) {
                // Reduce zoom on mobile for better viewing
                if (map) {
                    map.setZoom(17);
                }
            }
        }

        // Initialize mobile adjustments
        adjustMapForMobile();
        window.addEventListener('resize', adjustMapForMobile);
    </script>
</body>
</html>