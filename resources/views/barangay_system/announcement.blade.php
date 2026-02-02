<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Announcements</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/homepage_style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* =========================================== */
        /* ANNOUNCEMENT PAGE STYLES */
        /* =========================================== */

        /* Announcement Hero */
        .announcement-hero {
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.9), rgba(122, 35, 35, 0.9)), url('Images/announcement-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0 80px;
            margin-top: 65px;
            position: relative;
        }

        .announcement-hero .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .announcement-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .announcement-hero h1 i {
            font-size: 2.5rem;
            background: white;
            color: #C62828;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .announcement-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.1);
            padding: 12px 24px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stat i {
            font-size: 1.2rem;
            color: #FFCDD2;
        }

        /* Categories Section */
        .categories-section {
            padding: 80px 0 40px;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .category-btn {
            background: #f8f9fa;
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 20px 15px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            width: 100%;
            margin-bottom: 15px;
        }

        .category-btn:hover {
            background: #f0f0f0;
            transform: translateY(-3px);
            border-color: #ddd;
        }

        .category-btn.active {
            background: #C62828;
            color: white;
            border-color: #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .category-btn i {
            font-size: 1.5rem;
        }

        .category-btn .count {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .category-btn.active .count {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Featured Section */
        .featured-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .carousel-container {
            position: relative;
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
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 40px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .carousel-item:hover .carousel-content {
            transform: translateY(-10px);
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

        .carousel-progress {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .progress-bar {
            flex: 1;
            height: 6px;
            background: #eee;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #C62828;
            width: 0%;
            transition: width 0.3s ease;
        }

        .auto-play {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #666;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #C62828;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Announcements Grid Section */
        .announcements-grid-section {
            padding: 80px 0;
            background: white;
        }

        .announcements-grid-section .section-header {
            text-align: left;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h2 {
            font-size: 2rem;
            color: #C62828;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-start;
        }

        .result-count {
            color: #666;
            font-size: 0.95rem;
        }

        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
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

        /* Announcements Grid */
        .announcement-card {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            position: relative;
            margin-bottom: 30px;
            height: 100%;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
            background: #C62828;
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
            background: #d32f2f;
            transform: translateX(3px);
            color: white;
            text-decoration: none;
        }

        /* Load More Button */
        .load-more-container {
            text-align: center;
            margin: 40px 0;
        }

        .load-more-btn {
            padding: 15px 30px;
            background: white;
            border: 2px solid #C62828;
            color: #C62828;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .load-more-btn:hover {
            background: #C62828;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .load-more-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .load-more-btn:disabled:hover {
            background: white;
            color: #C62828;
            box-shadow: none;
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
            background: #C62828;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .reset-filters:hover {
            background: #d32f2f;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .announcement-content p {
                -webkit-line-clamp: 2;
            }
        }

        @media (max-width: 992px) {
            .announcement-hero {
                padding: 100px 0 60px;
            }
            
            .announcement-hero h1 {
                font-size: 2.5rem;
            }
            
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
        }

        @media (max-width: 768px) {
            .announcement-hero h1 {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .announcement-hero h1 i {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .hero-stats {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .stat {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
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
        }

        @media (max-width: 576px) {
            .announcement-hero {
                padding: 80px 0 40px;
            }
            
            .announcement-hero h1 {
                font-size: 1.8rem;
            }
            
            .announcement-hero p {
                font-size: 1rem;
            }
            
            .section-header h2 {
                font-size: 2rem;
                flex-direction: column;
                gap: 10px;
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
        }
    </style>
</head>
<body>
    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>
    
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <div class="logo"></div>
                <div class="logo-text d-none d-md-block">
                    <span class="logo-title">Barangay</span>
                    <span class="logo-subtitle">Hulong Duhat Portal</span>
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
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.html"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="history.html"><i class="fas fa-history"></i> History</a></li>
                            <li><a class="dropdown-link" href="homepage.html#mission"><i class="fas fa-bullseye"></i> Mission/Vision</a></li>
                            <li><a class="dropdown-link" href="map.html"><i class="fas fa-map"></i> Barangay Map</a></li>
                            <li><a class="dropdown-link" href="homepage.html#officials"><i class="fas fa-users"></i> Barangay Officials</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-concierge-bell"></i> Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="barangay_clearance.html"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="certificate_residency.html"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="certificate_indigency.html"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Community
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link active" href="announcement.html"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-link" href="homepage.html#events"><i class="fas fa-calendar-alt"></i> Events/Projects</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-circle"></i> Report
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="blotter_report.html"><i class="fas fa-clipboard-list"></i> Blotter Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.html#contact"><i class="fas fa-phone"></i> Contact</a>
                    </li>
                    
                    <!-- Desktop Actions -->
                    <li class="nav-item d-none d-lg-block">
                        <a href="user_login.html" class="login-btn ms-2"><i class="fas fa-sign-in-alt"></i> Log In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="announcement-hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-bullhorn"></i> Community Announcements</h1>
                <p>Stay updated with the latest news, events, and important information from Barangay Hulong Duhat</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-newspaper"></i>
                        <span>24 Active Announcements</span>
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

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <div class="container">
            <!-- Announcement Categories -->
            <section class="categories-section">
                <div class="section-header">
                    <h2><i class="fas fa-tags"></i> Browse by Category</h2>
                    <p>Filter announcements by type or importance</p>
                </div>
                
                <div class="row">
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn active" data-category="all">
                            <i class="fas fa-layer-group"></i>
                            <span>All Announcements</span>
                            <span class="count">24</span>
                        </button>
                    </div>
                    
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn" data-category="important">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Important</span>
                            <span class="count">8</span>
                        </button>
                    </div>
                    
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn" data-category="events">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Events</span>
                            <span class="count">6</span>
                        </button>
                    </div>
                    
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn" data-category="health">
                            <i class="fas fa-heartbeat"></i>
                            <span>Health & Safety</span>
                            <span class="count">5</span>
                        </button>
                    </div>
                    
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn" data-category="services">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Services Update</span>
                            <span class="count">3</span>
                        </button>
                    </div>
                    
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <button class="category-btn" data-category="infrastructure">
                            <i class="fas fa-hard-hat"></i>
                            <span>Infrastructure</span>
                            <span class="count">2</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- Featured Announcements Carousel -->
        <section class="featured-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Featured Announcements</h2>
                    <p>Most important updates you need to know</p>
                </div>
                
                <div class="carousel-container">
                    <div class="carousel" id="announcementCarousel">
                        <!-- Carousel items will be dynamically added -->
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/1200x400" alt="Featured announcement 1" class="carousel-image">
                            <div class="carousel-content">
                                <span class="category-badge">Important</span>
                                <h3>Distribution of Ayuda for Senior Citizens</h3>
                                <p>Important update about the upcoming distribution of ayuda for senior citizens in Barangay Hulong Duhat...</p>
                                <div class="carousel-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>July 09, 2025</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Barangay Admin</span>
                                    </div>
                                </div>
                                <a href="#" class="read-more-btn">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/1200x400" alt="Featured announcement 2" class="carousel-image">
                            <div class="carousel-content">
                                <span class="category-badge">Events</span>
                                <h3>Community General Assembly Meeting</h3>
                                <p>Join us for the monthly community assembly to discuss important barangay matters and upcoming projects...</p>
                                <div class="carousel-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>July 15, 2025</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>Barangay Chairman</span>
                                    </div>
                                </div>
                                <a href="#" class="read-more-btn">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carousel Controls -->
                    <div class="carousel-controls">
                        <button class="carousel-btn prev" aria-label="Previous announcement">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="carousel-dots">
                            <span class="carousel-dot active" data-index="0"></span>
                            <span class="carousel-dot" data-index="1"></span>
                            <span class="carousel-dot" data-index="2"></span>
                        </div>
                        
                        <button class="carousel-btn next" aria-label="Next announcement">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="carousel-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" id="carouselProgress"></div>
                        </div>
                        <div class="auto-play">
                            <span>Auto-play</span>
                            <label class="switch">
                                <input type="checkbox" id="autoPlayToggle" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <!-- Announcements Grid -->
            <section class="announcements-grid-section">
                <div class="row mb-4">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="header-left">
                            <h2><i class="fas fa-list-alt"></i> Latest Announcements</h2>
                            <p class="result-count">Showing <span id="visibleCount">24</span> of 24 announcements</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="header-right">
                            <div class="search-box mb-3">
                                <i class="fas fa-search"></i>
                                <input type="text" id="announcementSearch" placeholder="Search announcements...">
                            </div>
                            
                            <div class="sort-dropdown">
                                <select id="sortAnnouncements">
                                    <option value="newest">Sort by: Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="important">Most Important</option>
                                    <option value="title">Title (A-Z)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" id="announcementsGrid">
                    <!-- Announcement Cards -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="announcement-card featured">
                            <span class="featured-badge">Featured</span>
                            <img src="https://via.placeholder.com/400x200" alt="Announcement 1" class="announcement-image">
                            <div class="announcement-content">
                                <span class="announcement-category important">Important</span>
                                <h3>Distribution of Ayuda for Senior Citizens</h3>
                                <p>Important update about the upcoming distribution of ayuda for senior citizens in Barangay Hulong Duhat...</p>
                                <div class="announcement-meta">
                                    <div class="meta-info">
                                        <span class="meta-date">July 09, 2025</span>
                                        <span class="meta-views"><i class="fas fa-eye"></i> 245</span>
                                    </div>
                                    <a href="#" class="view-btn">
                                        View <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="announcement-card">
                            <img src="https://via.placeholder.com/400x200" alt="Announcement 2" class="announcement-image">
                            <div class="announcement-content">
                                <span class="announcement-category events">Events</span>
                                <h3>Community General Assembly Meeting</h3>
                                <p>Join us for the monthly community assembly to discuss important barangay matters and upcoming projects...</p>
                                <div class="announcement-meta">
                                    <div class="meta-info">
                                        <span class="meta-date">July 15, 2025</span>
                                        <span class="meta-views"><i class="fas fa-eye"></i> 189</span>
                                    </div>
                                    <a href="#" class="view-btn">
                                        View <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="announcement-card">
                            <img src="https://via.placeholder.com/400x200" alt="Announcement 3" class="announcement-image">
                            <div class="announcement-content">
                                <span class="announcement-category health">Health & Safety</span>
                                <h3>Free Medical Check-up Schedule</h3>
                                <p>Free medical check-up for senior citizens and PWDs at the barangay health center every Wednesday...</p>
                                <div class="announcement-meta">
                                    <div class="meta-info">
                                        <span class="meta-date">July 08, 2025</span>
                                        <span class="meta-views"><i class="fas fa-eye"></i> 312</span>
                                    </div>
                                    <a href="#" class="view-btn">
                                        View <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add more announcement cards as needed -->
                </div>
                
                <!-- Load More Button -->
                <div class="load-more-container">
                    <button class="load-more-btn" id="loadMoreBtn">
                        <i class="fas fa-sync-alt"></i>
                        <span>Load More Announcements</span>
                    </button>
                </div>
                
                <!-- No Results Message -->
                <div class="no-results" id="noResultsMessage" style="display: none;">
                    <i class="fas fa-search"></i>
                    <h3>No announcements found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                    <button class="reset-filters" id="resetFilters">
                        <i class="fas fa-redo"></i>
                        Reset All Filters
                    </button>
                </div>
            </section>
        </div>
    </main>

    <!-- Back to Top Button -->
    <button class="back-to-top" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Footer Section - User-Friendly -->
    <footer>
        <div class="container">
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
                            <a href="homepage.html" class="footer-link">
                                <i class="fas fa-home"></i> Home
                            </a>
                            <a href="announcement.html" class="footer-link">
                                <i class="fas fa-bullhorn"></i> Announcements
                            </a>
                            <a href="history.html" class="footer-link">
                                <i class="fas fa-history"></i> Barangay History
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fas fa-search"></i> Track Request
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fas fa-map-marked-alt"></i> Barangay Map
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fas fa-users"></i> Officials Directory
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h3>Services</h3>
                        <div class="footer-links-list">
                            <a href="barangay_clearance.html" class="footer-link">
                                <i class="fas fa-certificate"></i> Barangay Clearance
                            </a>
                            <a href="certificate_residency.html" class="footer-link">
                                <i class="fas fa-house-user"></i> Certificate of Residency
                            </a>
                            <a href="certificate_indigency.html" class="footer-link">
                                <i class="fas fa-hands-helping"></i> Certificate of Indigency
                            </a>
                            <a href="blotter_report.html" class="footer-link">
                                <i class="fas fa-clipboard-list"></i> Blotter Report
                            </a>
                            <a href="user_login.html" class="footer-link">
                                <i class="fas fa-sign-in-alt"></i> Login/Register
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Emergency & Support -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h3>Emergency Contacts</h3>
                        <div class="emergency-contacts-simple">
                            <div class="emergency-item critical">
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
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="copyright-info">
                            <p>&copy; 2025 Barangay Hulo, Malabon City. All rights reserved.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 text-md-end">
                        <div class="footer-bottom-actions">
                            <a href="user_login.html" class="admin-login">
                                <i class="fas fa-sign-in-alt"></i> Staff Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Your existing JavaScript files -->
    <script src="{{ asset('js/homepage_script.js') }}"></script>
    {{-- <script src="{{ asset('js/announcement_script.js') }}"></script> --}}
    
    <script>
        // Announcement Page Script - Integrated
        document.addEventListener('DOMContentLoaded', function() {
            // ===================================
            // CAROUSEL FUNCTIONALITY
            // ===================================
            
            let currentSlide = 0;
            let autoPlayInterval;
            let isAutoPlay = true;
            const carouselItems = document.querySelectorAll('.carousel-item');
            const dots = document.querySelectorAll('.carousel-dot');
            const totalSlides = carouselItems.length;
            const progressFill = document.getElementById('carouselProgress');
            const autoPlayToggle = document.getElementById('autoPlayToggle');
            
            // Initialize carousel
            function initCarousel() {
                if (carouselItems.length === 0) return;
                
                updateCarousel();
                startAutoPlay();
                
                // Progress bar update
                if (progressFill) {
                    setInterval(() => {
                        if (isAutoPlay) {
                            progressFill.style.width = `${(currentSlide + 1) / totalSlides * 100}%`;
                        }
                    }, 100);
                }
                
                // Navigation buttons
                document.querySelectorAll('.carousel-btn.prev').forEach(btn => {
                    btn.addEventListener('click', () => {
                        stopAutoPlay();
                        goToSlide(currentSlide - 1);
                        startAutoPlay();
                    });
                });
                
                document.querySelectorAll('.carousel-btn.next').forEach(btn => {
                    btn.addEventListener('click', () => {
                        stopAutoPlay();
                        goToSlide(currentSlide + 1);
                        startAutoPlay();
                    });
                });
                
                // Dots navigation
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        stopAutoPlay();
                        goToSlide(index);
                        startAutoPlay();
                    });
                });
                
                // Auto-play toggle
                if (autoPlayToggle) {
                    autoPlayToggle.addEventListener('change', function() {
                        isAutoPlay = this.checked;
                        if (isAutoPlay) {
                            startAutoPlay();
                        } else {
                            stopAutoPlay();
                        }
                    });
                }
            }
            
            function updateCarousel() {
                carouselItems.forEach((item, index) => {
                    item.classList.remove('active');
                    if (index === currentSlide) {
                        item.classList.add('active');
                    }
                });
                
                dots.forEach((dot, index) => {
                    dot.classList.remove('active');
                    if (index === currentSlide) {
                        dot.classList.add('active');
                    }
                });
            }
            
            function goToSlide(slideIndex) {
                currentSlide = (slideIndex + totalSlides) % totalSlides;
                updateCarousel();
            }
            
            function startAutoPlay() {
                if (!isAutoPlay) return;
                stopAutoPlay();
                autoPlayInterval = setInterval(() => {
                    goToSlide(currentSlide + 1);
                }, 5000);
            }
            
            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                }
            }
            
            // ===================================
            // FILTERING & SEARCH FUNCTIONALITY
            // ===================================
            
            const categoryButtons = document.querySelectorAll('.category-btn');
            const announcementCards = document.querySelectorAll('.announcement-card');
            const searchInput = document.getElementById('announcementSearch');
            const sortSelect = document.getElementById('sortAnnouncements');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const visibleCountSpan = document.getElementById('visibleCount');
            
            let visibleCards = 6;
            const cardsPerLoad = 6;
            let currentCategory = 'all';
            let currentSearch = '';
            
            function filterAnnouncements() {
                let visibleCount = 0;
                
                announcementCards.forEach((card, index) => {
                    const cardCategory = card.querySelector('.announcement-category')?.className?.split(' ')[1] || '';
                    const cardTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
                    const cardContent = card.querySelector('p')?.textContent.toLowerCase() || '';
                    
                    const matchesCategory = currentCategory === 'all' || cardCategory === currentCategory;
                    const matchesSearch = currentSearch === '' || 
                                         cardTitle.includes(currentSearch) || 
                                         cardContent.includes(currentSearch);
                    
                    if (matchesCategory && matchesSearch && index < visibleCards) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                if (visibleCountSpan) {
                    visibleCountSpan.textContent = visibleCount;
                }
                
                if (noResultsMessage) {
                    if (visibleCount === 0) {
                        noResultsMessage.style.display = 'block';
                    } else {
                        noResultsMessage.style.display = 'none';
                    }
                }
                
                if (loadMoreBtn) {
                    const totalMatches = Array.from(announcementCards).filter(card => {
                        const cardCategory = card.querySelector('.announcement-category')?.className?.split(' ')[1] || '';
                        const cardTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
                        const cardContent = card.querySelector('p')?.textContent.toLowerCase() || '';
                        
                        const matchesCategory = currentCategory === 'all' || cardCategory === currentCategory;
                        const matchesSearch = currentSearch === '' || 
                                             cardTitle.includes(currentSearch) || 
                                             cardContent.includes(currentSearch);
                        
                        return matchesCategory && matchesSearch;
                    }).length;
                    
                    if (visibleCards >= totalMatches) {
                        loadMoreBtn.style.display = 'none';
                    } else {
                        loadMoreBtn.style.display = 'block';
                    }
                }
            }
            
            function sortAnnouncements() {
                const sortBy = sortSelect?.value || 'newest';
                const container = document.getElementById('announcementsGrid');
                const cards = Array.from(announcementCards);
                
                cards.sort((a, b) => {
                    const aDate = new Date(a.querySelector('.meta-date')?.textContent || 0);
                    const bDate = new Date(b.querySelector('.meta-date')?.textContent || 0);
                    const aTitle = a.querySelector('h3')?.textContent.toLowerCase() || '';
                    const bTitle = b.querySelector('h3')?.textContent.toLowerCase() || '';
                    const aImportant = a.querySelector('.announcement-category.important');
                    const bImportant = b.querySelector('.announcement-category.important');
                    
                    switch(sortBy) {
                        case 'newest':
                            return bDate - aDate;
                        case 'oldest':
                            return aDate - bDate;
                        case 'important':
                            if (aImportant && !bImportant) return -1;
                            if (!aImportant && bImportant) return 1;
                            return bDate - aDate;
                        case 'title':
                            return aTitle.localeCompare(bTitle);
                        default:
                            return 0;
                    }
                });
                
                cards.forEach(card => {
                    container.appendChild(card);
                });
            }
            
            categoryButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    categoryButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    currentCategory = this.dataset.category;
                    visibleCards = cardsPerLoad;
                    filterAnnouncements();
                });
            });
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentSearch = this.value.toLowerCase();
                    visibleCards = cardsPerLoad;
                    filterAnnouncements();
                });
            }
            
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    sortAnnouncements();
                    filterAnnouncements();
                });
            }
            
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    visibleCards += cardsPerLoad;
                    filterAnnouncements();
                });
            }
            
            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', function() {
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('active');
                        if (btn.dataset.category === 'all') {
                            btn.classList.add('active');
                        }
                    });
                    
                    currentCategory = 'all';
                    currentSearch = '';
                    
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    
                    if (sortSelect) {
                        sortSelect.value = 'newest';
                    }
                    
                    visibleCards = cardsPerLoad;
                    sortAnnouncements();
                    filterAnnouncements();
                });
            }
            
            // ===================================
            // SCROLL EFFECTS
            // ===================================
            
            const navbar = document.querySelector('.navbar');
            const backToTopBtn = document.querySelector('.back-to-top');
            
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
            
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                });
                
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
            
            // ===================================
            // INITIALIZE
            // ===================================
            
            initCarousel();
            filterAnnouncements();
            sortAnnouncements();
        });
    </script>
</body>
</html>