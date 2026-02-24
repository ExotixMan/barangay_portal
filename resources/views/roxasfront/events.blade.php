<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat · Events & Projects</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css')}}">
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

        /* filter tabs – scrollable on mobile */
        .filter-section {
            background: white;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .filter-tabs {
            display: flex;
            flex-wrap: nowrap;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
        }
        .filter-tabs::-webkit-scrollbar {
            height: 4px;
            background: #e2e8f0;
        }
        .filter-tabs::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }
        .filter-btn {
            padding: 0.5rem 1.2rem;
            border: 1px solid #cbd5e1;
            background: white;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
            transition: 0.2s;
            white-space: nowrap;
        }
        .filter-btn.active {
            background: #0047ab;
            border-color: #0047ab;
            color: white;
        }

        /* section titles */
        .section-title {
            font-weight: 700;
            font-size: clamp(1.8rem, 5vw, 2.2rem);
            color: #0f2b45;
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            color: #5f6c80;
            margin-bottom: 2.5rem;
            font-size: 1rem;
        }

        /* event card */
        .event-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: 0.2s;
            border: 1px solid #eef2f6;
            overflow: hidden;
        }
        .event-date-badge {
            background: #0047ab;
            color: white;
            text-align: center;
            padding: 0.75rem 0;
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .event-date-badge .month { font-size: 0.9rem; font-weight: 500; text-transform: uppercase; }
        .event-date-badge .day { font-size: 2rem; font-weight: 700; margin: -0.2rem 0; }
        .event-date-badge .year { font-size: 0.8rem; opacity: 0.9; }
        .event-content {
            padding: 1.5rem 1.2rem 1.8rem;
        }
        .event-category {
            display: inline-block;
            background: #e6f0ff;
            color: #0047ab;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .event-content h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
        }
        .event-content p {
            color: #475569;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .event-details .detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 0.3rem;
        }
        .event-details i {
            width: 1.2rem;
            color: #0047ab;
        }
        .event-btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.4rem 1.2rem;
            background: #f1f5f9;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            color: #0f2b45;
            transition: 0.2s;
        }
        .event-btn:hover { background: #0047ab; color: white; }

        /* project card */
        .project-card {
            background: white;
            border-radius: 24px;
            padding: 1.8rem 1.5rem;
            height: 100%;
            border: 1px solid #eef2f6;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        }
        .project-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff7e5;
            color: #b45b0f;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.2rem;
        }
        .project-status i { font-size: 0.9rem; }
        .project-content h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
        }
        .project-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 16px;
            margin: 1.2rem 0;
        }
        .info-item {
            display: flex;
            gap: 0.6rem;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .info-item i { width: 1.3rem; color: #0047ab; }
        .progress-section {
            margin-top: 1rem;
        }
        .progress-header {
            display: flex;
            justify-content: space-between;
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        .progress-bar-custom {
            background: #e2e8f0;
            height: 8px;
            border-radius: 20px;
            overflow: hidden;
        }
        .progress-fill {
            background: #0047ab;
            height: 8px;
            border-radius: 20px;
        }

        /* completed card */
        .completed-card {
            background: white;
            padding: 2rem 1.5rem;
            text-align: center;
            border-radius: 24px;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            border: 1px solid #eef2f6;
        }
        .completed-icon i {
            font-size: 3rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
        .completed-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .completed-card p {
            color: #5f6c80;
            font-size: 0.95rem;
            margin: 0.8rem 0;
        }
        .completed-info span {
            background: #f1f5f9;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
        }

        /* timeline responsive */
        .past-events {
            background: white;
        }
        .events-timeline {
            max-width: 800px;
            margin: 0 auto;
        }
        .timeline-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 20px;
            margin-bottom: 1rem;
        }
        .timeline-date {
            background: #0047ab;
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            width: fit-content;
            font-weight: 600;
        }
        .timeline-date .month { font-size: 0.9rem; }
        .timeline-date .day { font-size: 1.2rem; }
        .timeline-content h3 { font-size: 1.2rem; font-weight: 600; }
        .attendees { color: #5f6c80; font-size: 0.9rem; }

        /* cta */
        .events-cta {
            background: linear-gradient(135deg, #0b2b4f 0%, #143b66 100%);
            color: white;
            text-align: center;
            padding: 3.5rem 1.5rem;
        }
        .cta-content h2 { font-weight: 700; font-size: clamp(1.8rem,5vw,2.5rem); }
        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        .cta-btn {
            padding: 0.8rem 2rem;
            border-radius: 40px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }
        .cta-btn.primary {
            background: white;
            color: #0047ab;
        }
        .cta-btn.secondary {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        
        @media (min-width: 768px) {
            .timeline-item { flex-direction: row; align-items: center; gap: 2rem; }
            .timeline-date { flex-direction: column; min-width: 100px; text-align: center; background: transparent; color: #0047ab; }
            .timeline-date .month { font-size: 1rem; }
            .timeline-date .day { font-size: 2rem; font-weight: 700; line-height: 1; }
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

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-calendar-alt"></i> Events & Projects</h1>
                <p>Stay updated with community events, ongoing projects, and upcoming activities in Barangay Hulong Duhat.</p>
                <div class="hero-stats">
                    <div class="stat"><i class="fas fa-calendar-check"></i> <span>Upcoming Events</span></div>
                    <div class="stat"><i class="fas fa-tasks"></i> <span>Ongoing Projects</span></div>
                    <div class="stat"><i class="fas fa-check-double"></i> <span>Completed Initiatives</span></div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content" id="main-content">
        <!-- Filter (horizontal scroll on mobile) -->
        <section class="filter-section">
            <div class="container">
                <div class="filter-tabs">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="upcoming">Upcoming Events</button>
                    <button class="filter-btn" data-filter="ongoing">Ongoing Projects</button>
                    <button class="filter-btn" data-filter="completed">Completed</button>
                </div>
            </div>
        </section>

        <!-- Upcoming Events (bootstrap row g-4) -->
        <section class="upcoming-events py-5">
            <div class="container">
                <h2 class="section-title">Upcoming Events</h2>
                <p class="section-subtitle">Mark your calendars for these upcoming community activities.</p>
                <div class="row g-4">
                    <!-- card 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="event-card">
                            <div class="event-date-badge"><span class="month">Mar</span><span class="day">15</span><span class="year">2026</span></div>
                            <div class="event-content">
                                <span class="event-category upcoming">Upcoming</span>
                                <h3>Community General Assembly</h3>
                                <p>Annual gathering to discuss barangay matters, budget, and concerns.</p>
                                <div class="event-details"><div class="detail"><i class="fas fa-map-marker-alt"></i><span>Barangay Covered Court</span></div><div class="detail"><i class="fas fa-clock"></i><span>9:00 AM - 12:00 PM</span></div></div>
                                <a href="#" class="event-btn"><i class="fas fa-info-circle"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="event-card">
                            <div class="event-date-badge"><span class="month">Mar</span><span class="day">20</span><span class="year">2026</span></div>
                            <div class="event-content">
                                <span class="event-category upcoming">Upcoming</span>
                                <h3>Monthly Clean-up Drive</h3>
                                <p>Join us in keeping our barangay clean and green.</p>
                                <div class="event-details"><div class="detail"><i class="fas fa-map-marker-alt"></i><span>All Zones</span></div><div class="detail"><i class="fas fa-clock"></i><span>6:00 AM - 9:00 AM</span></div></div>
                                <a href="#" class="event-btn"><i class="fas fa-info-circle"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="event-card">
                            <div class="event-date-badge"><span class="month">Apr</span><span class="day">05</span><span class="year">2026</span></div>
                            <div class="event-content">
                                <span class="event-category upcoming">Upcoming</span>
                                <h3>Free Medical Check-up</h3>
                                <p>Free health screening for senior citizens, PWDs, and indigents.</p>
                                <div class="event-details"><div class="detail"><i class="fas fa-map-marker-alt"></i><span>Barangay Health Center</span></div><div class="detail"><i class="fas fa-clock"></i><span>8:00 AM - 4:00 PM</span></div></div>
                                <a href="#" class="event-btn"><i class="fas fa-info-circle"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ongoing Projects -->
        <section class="ongoing-projects py-5 bg-light">
            <div class="container">
                <h2 class="section-title">Ongoing Projects</h2>
                <p class="section-subtitle">Current initiatives for our community's development.</p>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="project-card">
                            <div class="project-status ongoing"><i class="fas fa-spinner fa-spin"></i> Ongoing</div>
                            <h3>Road Rehabilitation</h3>
                            <p>Repair of major roads for safer travel.</p>
                            <div class="project-info">
                                <div class="info-item"><i class="fas fa-calendar-alt"></i><span>Started: Jan 15, 2026</span></div>
                                <div class="info-item"><i class="fas fa-calendar-check"></i><span>Expected: Apr 2026</span></div>
                                <div class="info-item"><i class="fas fa-map-marker-alt"></i><span>M. Blas Street</span></div>
                            </div>
                            <div class="progress-section"><div class="progress-header"><span>Progress</span><span>65%</span></div><div class="progress-bar-custom"><div class="progress-fill" style="width:65%"></div></div></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="project-card">
                            <div class="project-status ongoing"><i class="fas fa-spinner fa-spin"></i> Ongoing</div>
                            <h3>Drainage Improvement</h3>
                            <p>Flood prevention and sanitation upgrade.</p>
                            <div class="project-info">
                                <div class="info-item"><i class="fas fa-calendar-alt"></i><span>Started: Feb 1, 2026</span></div>
                                <div class="info-item"><i class="fas fa-calendar-check"></i><span>Expected: May 2026</span></div>
                                <div class="info-item"><i class="fas fa-map-marker-alt"></i><span>Zone 1,2,3</span></div>
                            </div>
                            <div class="progress-section"><div class="progress-header"><span>Progress</span><span>40%</span></div><div class="progress-bar-custom"><div class="progress-fill" style="width:40%"></div></div></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="project-card">
                            <div class="project-status ongoing"><i class="fas fa-spinner fa-spin"></i> Ongoing</div>
                            <h3>Barangay Hall Renovation</h3>
                            <p>Modernization of facilities.</p>
                            <div class="project-info">
                                <div class="info-item"><i class="fas fa-calendar-alt"></i><span>Started: Dec 2025</span></div>
                                <div class="info-item"><i class="fas fa-calendar-check"></i><span>Expected: Mar 2026</span></div>
                                <div class="info-item"><i class="fas fa-map-marker-alt"></i><span>Hall Complex</span></div>
                            </div>
                            <div class="progress-section"><div class="progress-header"><span>Progress</span><span>85%</span></div><div class="progress-bar-custom"><div class="progress-fill" style="width:85%"></div></div></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="project-card">
                            <div class="project-status ongoing"><i class="fas fa-spinner fa-spin"></i> Ongoing</div>
                            <h3>Street Lighting Installation</h3>
                            <p>LED lights for visibility and safety.</p>
                            <div class="project-info">
                                <div class="info-item"><i class="fas fa-calendar-alt"></i><span>Started: Jan 2026</span></div>
                                <div class="info-item"><i class="fas fa-calendar-check"></i><span>Expected: Mar 2026</span></div>
                                <div class="info-item"><i class="fas fa-map-marker-alt"></i><span>All Zones</span></div>
                            </div>
                            <div class="progress-section"><div class="progress-header"><span>Progress</span><span>70%</span></div><div class="progress-bar-custom"><div class="progress-fill" style="width:70%"></div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Completed Projects -->
        <section class="completed-projects py-5">
            <div class="container">
                <h2 class="section-title">Completed Projects</h2>
                <p class="section-subtitle">Successfully completed initiatives.</p>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6"><div class="completed-card"><div class="completed-icon"><i class="fas fa-check-circle"></i></div><h3>Basketball Court Renovation</h3><p>New flooring, hoops, seating.</p><div class="completed-info"><span><i class="fas fa-calendar-check"></i> Dec 2025</span></div></div></div>
                    <div class="col-lg-4 col-md-6"><div class="completed-card"><div class="completed-icon"><i class="fas fa-check-circle"></i></div><h3>Health Center Equipment</h3><p>New medical equipment.</p><div class="completed-info"><span><i class="fas fa-calendar-check"></i> Nov 2025</span></div></div></div>
                    <div class="col-lg-4 col-md-6"><div class="completed-card"><div class="completed-icon"><i class="fas fa-check-circle"></i></div><h3>CCTV Installation</h3><p>Strategic peace & order cameras.</p><div class="completed-info"><span><i class="fas fa-calendar-check"></i> Oct 2025</span></div></div></div>
                </div>
            </div>
        </section>

        <!-- Past Events Timeline (responsive) -->
        <section class="past-events py-5">
            <div class="container">
                <h2 class="section-title">Past Events</h2>
                <p class="section-subtitle">A look back at recent activities.</p>
                <div class="events-timeline">
                    <div class="timeline-item"><div class="timeline-date"><span class="month">Feb</span><span class="day">14</span></div><div class="timeline-content"><h3>Valentine's Day Celebration</h3><p>Games and prizes for couples & families.</p><span class="attendees"><i class="fas fa-users"></i> 250+ Attendees</span></div></div>
                    <div class="timeline-item"><div class="timeline-date"><span class="month">Feb</span><span class="day">09</span></div><div class="timeline-content"><h3>Distribution of Ayuda</h3><p>Relief goods and financial assistance.</p><span class="attendees"><i class="fas fa-users"></i> 500+ Beneficiaries</span></div></div>
                    <div class="timeline-item"><div class="timeline-date"><span class="month">Jan</span><span class="day">25</span></div><div class="timeline-content"><h3>Barangay Fiesta</h3><p>Cultural performances and food fair.</p><span class="attendees"><i class="fas fa-users"></i> 1,000+ Attendees</span></div></div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="events-cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Want to Get Involved?</h2>
                    <p>Join our community events and be part of progress.</p>
                    <div class="cta-buttons"><a href="announcement.html" class="cta-btn primary"><i class="fas fa-bullhorn"></i> View Announcements</a><a href="homepage.html#contact" class="cta-btn secondary"><i class="fas fa-phone"></i> Contact Us</a></div>
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

</body>
</html>