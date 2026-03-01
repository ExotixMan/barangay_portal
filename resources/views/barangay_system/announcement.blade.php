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
    
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
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
            filter: brightness(0.7);
        }

        .carousel-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            color: white;
            padding: 40px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .carousel-item:hover .carousel-content {
            transform: translateY(-5px);
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

        .header-left h2 {
            font-size: 2.2rem;
            color: #C62828;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Announcement Cards */
        .announcement-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            position: relative;
            margin-bottom: 30px;
            height: 100%;
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
            background: #C62828;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .announcement-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .announcement-content {
            padding: 25px;
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
        }

        .announcement-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
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
                            <li><a class="dropdown-link dropdown-item-custom" href="{{ route('services') }}"><i class="fas fa-list"></i> All Services</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-link" href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="{{ route('residency')}}"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
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
                <h1><i class="fas fa-bullhorn bigicon"></i> Community Announcements</h1>
                <p>Stay updated with the latest news, events, and important information from Barangay Hulong Duhat</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ $announcements->total() }} Active Announcements</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-eye"></i>
                        <span>Updated Daily</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-bell"></i>
                        <span>Get Notifications</span>
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
                    <h2>Featured Announcements</h2>
                    <p>Most important updates you need to know</p>
                </div>
                
                <div class="carousel-container">
                    <div class="carousel" id="announcementCarousel">

                        @foreach($featured as $key => $item)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset('announcement_pic/'.$item->image) }}" alt="{{ $item->title }}" class="carousel-image">
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
                                    <a href="{{ route('announcements.show',$item->slug) }}" class="read-more-btn">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
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
                            <h2><i class="fas fa-list-alt"></i> Latest Announcements</h2>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="header-right">
                            <form method="GET" action="{{ route('announcements') }}#announcements" class="d-flex gap-3 w-100">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search announcements..."
                                    >
                                </div>

                                <div class="sort-dropdown">
                                    <select name="sort" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>
                                            Newest First
                                        </option>

                                        <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>
                                            Oldest First
                                        </option>

                                        <option value="important" {{ request('sort')=='important'?'selected':'' }}>
                                            Most Important
                                        </option>

                                        <option value="title" {{ request('sort')=='title'?'selected':'' }}>
                                            Title (A-Z)
                                        </option>
                                    </select>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="row" id="announcementsGrid">
                    {{-- Announcement card --}}
                    @foreach($announcements as $item)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="announcement-card {{ $item->is_featured ? 'featured' : '' }}">
                                @if($item->is_featured)
                                    <span class="featured-badge">Featured</span>
                                @endif
                                <img src="{{ asset('announcement_pic/'.$item->image) }}" alt="{{ $item->title }}" class="announcement-image">
                                <div class="announcement-content">
                                    <span class="announcement-category {{ $item->category }}">{{ ucfirst($item->category) }}</span>
                                    <h3>{{ $item->title }}</h3>
                                    <p>{{ Str::limit($item->content,120) }}</p>
                                    <div class="announcement-meta">
                                        <div class="meta-info">
                                            <span class="meta-date">{{ date('M d, Y', strtotime($item->published_at)) }}</span>
                                            <span class="meta-views"><i class="fas fa-eye"></i> {{ $item->views }}</span>
                                        </div>
                                        <a href="{{ route('announcements.show',$item->slug) }}" class="view-btn">
                                            View <i class="fas fa-arrow-right"></i>
                                        </a>
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
                    <h3>No announcements found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                    <button class="reset-filters" id="resetFilters">
                        <i class="fas fa-redo"></i>
                        Reset All Filters
                    </button>
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

        });
        </script>

</body>
</html>