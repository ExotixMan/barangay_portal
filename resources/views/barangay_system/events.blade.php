<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Events & Projects</title>
    
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

        /* Filter Section */
        .filter-section {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: white;
            border: 2px solid #e0e0e0;
            padding: 12px 28px;
            border-radius: 25px;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            border-color: #C62828;
            color: #C62828;
        }

        .filter-btn.active {
            background: #C62828;
            border-color: #C62828;
            color: white;
        }

        /* Upcoming Events Section */
        .upcoming-events {
            padding: 80px 0;
            background: white;
        }

        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .event-date-badge {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .event-date-badge .month {
            display: block;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }

        .event-date-badge .day {
            display: block;
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
            margin: 5px 0;
        }

        .event-date-badge .year {
            display: block;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .event-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .event-category {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
            width: fit-content;
        }

        .event-category.upcoming {
            background: #E3F2FD;
            color: #1976D2;
        }

        .event-category.ongoing {
            background: #FFF3E0;
            color: #F57C00;
        }

        .event-content h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .event-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .event-details {
            margin-bottom: 20px;
        }

        .event-details .detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            color: #555;
            font-size: 0.9rem;
        }

        .event-details .detail i {
            color: #C62828;
            width: 18px;
        }

        .event-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            justify-content: center;
            margin-top: auto;
        }

        .event-btn:hover {
            background: linear-gradient(135deg, #a02020, #C62828);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(198, 40, 40, 0.3);
        }

        /* Ongoing Projects Section */
        .ongoing-projects {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .project-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            height: 100%;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.12);
            border-color: #C62828;
        }

        .project-status {
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .project-status.ongoing {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .project-status.completed {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .project-content {
            padding: 25px;
        }

        .project-content h3 {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .project-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .project-info {
            margin-bottom: 25px;
        }

        .project-info .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #555;
            font-size: 0.95rem;
        }

        .project-info .info-item i {
            color: #C62828;
            width: 20px;
        }

        .progress-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .progress-bar-custom {
            height: 12px;
            background: #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        /* Completed Projects Section */
        .completed-projects {
            padding: 80px 0;
            background: white;
        }

        .completed-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #f0f0f0;
        }

        .completed-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(76, 175, 80, 0.15);
            border-color: #4CAF50;
        }

        .completed-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .completed-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .completed-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .completed-info {
            color: #4CAF50;
            font-weight: 500;
        }

        .completed-info i {
            margin-right: 5px;
        }

        /* Past Events Timeline */
        .past-events {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .events-timeline {
            max-width: 800px;
            margin: 50px auto 0;
            position: relative;
        }

        .events-timeline::before {
            content: '';
            position: absolute;
            left: 50px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #C62828;
        }

        .timeline-item {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-date {
            width: 100px;
            min-width: 100px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .timeline-date .month {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .timeline-date .day {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .timeline-content {
            background: white;
            padding: 25px;
            border-radius: 15px;
            flex: 1;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }

        .timeline-content h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .timeline-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .attendees {
            color: #C62828;
            font-weight: 500;
        }

        .attendees i {
            margin-right: 5px;
        }

        /* Tablet */
        @media (max-width: 991.98px) {
            .section-title {
                font-size: 2rem;
            }

            .filter-tabs {
                gap: 10px;
            }

            .filter-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .events-timeline::before {
                left: 40px;
            }

            .timeline-date {
                width: 80px;
                min-width: 80px;
                padding: 12px;
            }

            .timeline-date .day {
                font-size: 1.5rem;
            }
        }

        /* Mobile */
        @media (max-width: 767.98px) {
            .filter-tabs {
                flex-direction: column;
                gap: 10px;
            }

            .filter-btn {
                width: 100%;
            }

            .upcoming-events,
            .ongoing-projects,
            .completed-projects,
            .past-events,
            .events-cta {
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

            .event-date-badge {
                padding: 20px;
            }

            .event-date-badge .day {
                font-size: 2.5rem;
            }

            .event-content {
                padding: 20px;
            }

            .event-content h3 {
                font-size: 1.2rem;
            }

            .project-content {
                padding: 20px;
            }

            .project-content h3 {
                font-size: 1.2rem;
            }

            .progress-section {
                padding: 15px;
            }

            .completed-card {
                padding: 25px 20px;
            }

            .events-timeline::before {
                display: none;
            }

            .timeline-item {
                flex-direction: column;
                gap: 15px;
            }

            .timeline-date {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 12px;
            }

            .timeline-date .month,
            .timeline-date .day {
                display: inline;
                font-size: 1.2rem;
            }

            .timeline-content {
                padding: 20px;
            }

            .timeline-content h3 {
                font-size: 1.1rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 575.98px) {
            .section-title {
                font-size: 1.5rem;
            }

            .event-date-badge .day {
                font-size: 2rem;
            }

            .event-btn {
                width: 100%;
            }

            .completed-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
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

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-calendar-alt"></i> Events & Projects</h1>
                <p>Stay updated with community events, ongoing projects, and upcoming activities in Barangay Hulong Duhat.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-calendar-check"></i>
                        <span>Upcoming Events</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-tasks"></i>
                        <span>Ongoing Projects</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-check-double"></i>
                        <span>Completed Initiatives</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Upcoming Events Section - row/grid already uses Bootstrap, ensure container -->
        <section class="upcoming-events">
            <div class="container">
                <h2 class="section-title">Upcoming Events</h2>
                <p class="section-subtitle">Mark your calendars for these upcoming community activities.</p>
                
                <div class="row g-4">
                    @forelse($upcomingEvents as $event)
                        <div class="col-lg-4 col-md-6">
                            <div class="event-card">
                                <div class="event-date-badge">
                                    <span class="month">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                                    <span class="day">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                    <span class="year">{{ \Carbon\Carbon::parse($event->event_date)->format('Y') }}</span>
                                </div>
                                <div class="event-content">
                                    <span class="event-category upcoming">Upcoming</span>
                                    <h3>{{ $event->title }}</h3>
                                    <p>{{ $event->description }}</p>
                                    <div class="details">
                                        <div class="detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $event->location }}</span>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                                        </div>
                                    </div>
                                    <a href="#" class="event-btn">
                                        <i class="fas fa-info-circle"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No upcoming events available.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Ongoing Projects Section - Bootstrap row/grid already applied -->
        <section class="ongoing-projects">
            <div class="container">
                <h2 class="section-title">Ongoing Projects</h2>
                <p class="section-subtitle">Current initiatives being implemented for our community's development.</p>
                
                <div class="row g-4">
                    @foreach($ongoingProjects as $project)
                        <div class="col-lg-6">
                            <div class="project-card">
                                <div class="project-status ongoing">
                                    <i class="fas fa-spinner fa-spin"></i> Ongoing
                                </div>
                                <div class="project-content">
                                    <h3>{{ $project->title }}</h3>
                                    <p>{{ $project->description }}</p>
                                    <div class="project-info">
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ $project->start_date }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <span>Expected Completion: {{ $project->expected_completion }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>Location: {{ $project->location }}</span>
                                        </div>
                                    </div>
                                    <div class="progress-section">
                                        <div class="progress-header">
                                            <span>Progress</span>
                                            <span>{{ $project->progress }}%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill" style="width: {{ $project->progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Completed Projects Section -->
        <section class="completed-projects">
            <div class="container">
                <h2 class="section-title">Completed Projects</h2>
                <p class="section-subtitle">Successfully completed initiatives that have benefited our community.</p>
                
                <div class="row g-4">
                    @foreach($completedProjects as $project)
                        <div class="col-lg-4 col-md-6">
                            <div class="completed-card">
                                <div class="completed-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3>{{ $project->title }}</h3>
                                <p>{{ $project->description }}</p>
                                <div class="completed-info">
                                    <span><i class="fas fa-calendar-check"></i> Completed: {{ $project->expected_completion }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Past Events Section -->
        <section class="past-events">
            <div class="container">
                <h2 class="section-title">Past Events</h2>
                <p class="section-subtitle">A look back at our recent community events and activities.</p>
                
                <div class="events-timeline">
                    @foreach($pastEvents as $event)
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <span class="month">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                                <span class="day">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                            </div>
                            <div class="timeline-content">
                                <h3>{{ $event->title }}</h3>
                                <p>{{ $event->description }}</p>
                                @if($event->attendees)
                                    <span class="attendees">
                                        <i class="fas fa-users"></i> {{ $event->attendees }} Attendees
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
    <script src="{{ asset('js/faq.js') }}"></script>
</body>
</html>