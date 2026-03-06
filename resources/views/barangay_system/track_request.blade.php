<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Track Request</title>
    
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
        }

        /* Full Width Background Sections */
        .section-bg {
            background: #f8f9fa;
            width: 100%;
            padding: 80px 0;
        }

        /* Track Form Section */
        .track-form-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .track-form-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header .form-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
            border: 3px solid #C62828;
        }

        .form-header .form-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-header h2 {
            font-size: 2rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-group label i {
            color: #C62828;
            margin-right: 5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .form-control {
            padding: 15px 50px 15px 20px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            width: 100%;
        }

        .input-wrapper .form-control:focus {
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
            outline: none;
        }

        .input-wrapper .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 1.2rem;
        }

        .form-text {
            display: block;
            margin-top: 8px;
            color: #999;
            font-size: 0.85rem;
        }

        .track-form-card select.form-control {
            padding: 15px 20px;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #333;
            cursor: pointer;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .track-form-card select.form-control:focus {
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
        }

        .btn-track {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.15rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        }

        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
        }

        .btn-track:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Track Result Section */
        .track-result-section {
            padding: 0 0 80px;
            background: #f8f9fa;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .result-header {
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: white;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .result-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .status-badge.processing {
            background: rgba(255, 152, 0, 0.2);
            color: #FFE0B2;
            border: 1px solid rgba(255, 152, 0, 0.3);
        }

        .status-badge.completed {
            background: rgba(76, 175, 80, 0.2);
            color: #C8E6C9;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.ready {
            background: rgba(33, 150, 243, 0.2);
            color: #BBDEFB;
            border: 1px solid rgba(33, 150, 243, 0.3);
        }

        .result-body {
            padding: 40px;
        }

        .detail-item {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item .label {
            display: block;
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 5px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item .value {
            display: block;
            font-size: 1.05rem;
            color: #333;
            font-weight: 600;
        }

        /* Progress Timeline */
        .progress-timeline {
            padding: 40px;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        .progress-timeline h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-timeline h3 i {
            color: #C62828;
        }

        .timeline {
            position: relative;
            padding-left: 40px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e0e0e0;
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            position: absolute;
            left: -40px;
            z-index: 1;
            flex-shrink: 0;
        }

        .timeline-item.completed .timeline-marker {
            background: #4CAF50;
            color: white;
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }

        .timeline-item.active .timeline-marker {
            background: #FF9800;
            color: white;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        .timeline-item.pending .timeline-marker {
            background: #e0e0e0;
            color: #999;
        }

        .timeline-content {
            margin-left: 0;
        }

        .timeline-content h4 {
            font-size: 1rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .timeline-content .time {
            font-size: 0.8rem;
            color: #999;
            font-style: italic;
        }

        .timeline-item.pending .timeline-content h4,
        .timeline-item.pending .timeline-content p {
            color: #bbb;
        }

        /* Result Actions */
        .result-actions {
            padding: 30px 40px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-print,
        .btn-new-track {
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .btn-new-track {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #eee;
        }

        .btn-new-track:hover {
            background: #eee;
            transform: translateY(-2px);
        }

        /* Status Legend Section */
        .status-legend-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 2.2rem;
            font-weight: 700;
            color: #C62828;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            margin-top: -35px;
            margin-bottom: 50px;
        }

        .status-card {
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
            height: 100%;
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.2);
        }

        .status-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }

        .status-card.submitted .status-icon {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .status-card.processing .status-icon {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .status-card.ready .status-icon {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .status-card.completed-status .status-icon {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
        }

        .status-card h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .status-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Quick Links Section */
        .quick-links-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .quick-link-card {
            display: block;
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #eee;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .quick-link-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.12);
            border-color: rgba(198, 40, 40, 0.2);
            color: #333;
            text-decoration: none;
        }

        .quick-link-card .card-icon {
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
            transition: all 0.3s ease;
        }

        .quick-link-card:hover .card-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(198, 40, 40, 0.3);
        }

        .quick-link-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .quick-link-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .link-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #f8f9fa;
            border-radius: 50%;
            color: #C62828;
            transition: all 0.3s ease;
        }

        .quick-link-card:hover .link-arrow {
            background: #C62828;
            color: white;
            transform: translateX(5px);
        }

        /* Responsive Design */
        @media (max-width: 1366px) {

            
            .track-form-card {
                padding: 40px;
            }
            
            .result-body,
            .progress-timeline,
            .result-actions {
                padding: 30px;
            }
            
        }

        @media (max-width: 1200px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section {
                padding: 70px 0;
            }
        }

        @media (max-width: 992px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section {
                padding: 60px 0;
            }
            
            .track-form-card {
                padding: 35px;
            }
            
            .result-header {
                flex-direction: column;
                text-align: center;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 768px) {   
            .track-form-card {
                padding: 25px;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .input-wrapper .form-control {
                font-size: 1rem;
                padding: 12px 45px 12px 16px;
            }
            
            .btn-track {
                font-size: 1rem;
                padding: 14px;
            }
            
            .result-body {
                padding: 20px;
            }
            
            .progress-timeline {
                padding: 25px;
            }
            
            .result-actions {
                padding: 20px;
                flex-direction: column;
            }
            
            .btn-print,
            .btn-new-track {
                width: 100%;
                justify-content: center;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
                margin-top: -25px;
            }
            
            .status-card {
                padding: 25px 20px;
            }
            
            .timeline {
                padding-left: 35px;
            }
            
            .timeline-marker {
                width: 28px;
                height: 28px;
                left: -35px;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .track-form-section,
            .status-legend-section,
            .quick-links-section,
            .track-result-section {
                padding: 45px 0;
            }
            
            .track-result-section {
                padding: 0 0 45px;
            }
            
            .track-form-card {
                padding: 20px;
                border-radius: 15px;
            }
            
            .form-header .form-icon {
                width: 60px;
                height: 60px;
            }
            
            .form-header h2 {
                font-size: 1.3rem;
            }
            
            .result-card {
                border-radius: 15px;
            }
            
            .result-header {
                padding: 20px;
            }
            
            .result-header h2 {
                font-size: 1.2rem;
            }
            
            .container {
                padding: 0 15px;
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
                <h1><i class="fas fa-search-location bigicon"></i> Track Your Request</h1>
                <p>Monitor the status of your document requests in real-time. Enter your reference number to get instant updates on your application.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Real-time Updates</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-bell"></i>
                        <span>Status Notifications</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Tracking</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Track Form Section -->
        <section class="track-form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="track-form-card">
                            <div class="form-header">
                                <div class="form-icon">
                                    <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Hulo Logo">
                                </div>
                                <h2>Enter Your Reference Number</h2>
                                <p>Your reference number was provided when you submitted your request</p>
                            </div>
                            <form id="trackForm">
                            @csrf
                                <div class="form-group">
                                    <label for="referenceNumber">
                                        <i class="fas fa-hashtag"></i> Reference Number
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" class="form-control" id="referenceNumber" placeholder="e.g., BRG-2026-00001" required>
                                        <span class="input-icon"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <small class="form-text">Format: BRG-YYYY-XXXXX (e.g., BRG-2026-00001)</small>
                                </div>
                                <div class="form-group">
                                    <label for="requestType">
                                        <i class="fas fa-file-alt"></i> Document Type (Optional)
                                    </label>
                                    <select class="form-control" id="requestType">
                                        <option value="">Select document type</option>
                                        <option value="clearance">Barangay Clearance</option>
                                        <option value="residency">Certificate of Residency</option>
                                        <option value="indigency">Certificate of Indigency</option>
                                        <option value="blotter">Blotter Report</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-track">
                                    <i class="fas fa-search"></i> Track Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Track Result Section (Hidden by default) -->
        <section class="track-result-section" id="trackResult" style="display: none;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="result-card">
                            <div class="result-header">
                                <div class="status-badge" id="resultStatusBadge">
                                    <i class="fas fa-hourglass-half"></i> Processing
                                </div>
                                <h2>Request Details</h2>
                            </div>
                            <div class="result-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Reference Number</span>
                                            <span class="value" id="resultRefNumber">BRG-2026-00001</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Document Type</span>
                                            <span class="value" id="resultDocType">Barangay Clearance</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Applicant Name</span>
                                            <span class="value" id="resultName">Juan Dela Cruz</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="label">Date Submitted</span>
                                            <span class="value" id="resultDate">March 1, 2026</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Expected Completion</span>
                                            <span class="value" id="resultExpected">March 4, 2026</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="label">Processing Fee</span>
                                            <span class="value" id="resultFee">₱100.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Timeline -->
                            <div class="progress-timeline">
                                <h3><i class="fas fa-tasks"></i> Request Progress</h3>
                                <div class="timeline" id="timeline">
                                    <!-- Timeline items will be populated dynamically -->
                                </div>
                            </div>

                            <div class="result-actions">
                                <button class="btn-print" onclick="printRequestDetails()">
                                    <i class="fas fa-print"></i> Print Details
                                </button>
                                <button class="btn-new-track" onclick="resetForm()">
                                    <i class="fas fa-redo"></i> Track Another
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Status Legend Section -->
        <section class="status-legend-section">
            <div class="container">
                <h2 class="section-title">Status Guide</h2>
                <p class="section-subtitle">Understanding your request status</p>
                
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="status-card submitted">
                            <div class="status-icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h3>Submitted</h3>
                            <p>Your request has been received and is waiting for initial review.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="status-card processing">
                            <div class="status-icon">
                                <i class="fas fa-cog fa-spin"></i>
                            </div>
                            <h3>Processing</h3>
                            <p>Your documents are being verified and your request is being processed.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="status-card ready">
                            <div class="status-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>Ready</h3>
                            <p>Your document is ready for pickup at the barangay hall or download.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="status-card completed-status">
                            <div class="status-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <h3>Completed</h3>
                            <p>Your request has been fulfilled and the document has been released.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Links Section -->
        <section class="quick-links-section">
            <div class="container">
                <h2 class="section-title">Apply for Documents</h2>
                <p class="section-subtitle">Need to submit a new request? Choose a service below.</p>
                
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('clearance') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h3>Barangay Clearance</h3>
                            <p>Official document certifying good moral character and residency</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('residency') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-house-user"></i>
                            </div>
                            <h3>Certificate of Residency</h3>
                            <p>Proof of residence within the barangay jurisdiction</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('indigency') }}" class="quick-link-card">
                            <div class="card-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h3>Certificate of Indigency</h3>
                            <p>Document for financial assistance and government programs</p>
                            <span class="link-arrow"><i class="fas fa-arrow-right"></i></span>
                        </a>
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
                            <span>Where can I find my reference number?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Your reference number was provided when you submitted your request. It was sent to your email and also displayed on the confirmation page. The format is BRG-YYYY-XXXXX (e.g., BRG-2026-00001).</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does processing usually take?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Processing times vary by document type: Barangay Clearance (1-3 days), Certificate of Residency (1-2 days), Certificate of Indigency (1-2 days). Times may vary during peak periods.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What should I do if my request is taking too long?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>If your request exceeds the expected processing time, please contact our office at (02) 987-6543 or visit the barangay hall with your reference number for assistance.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I cancel or modify my request?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can request cancellation or modification before your request reaches the "Processing" stage. Please contact our office with your reference number to make changes.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What if I lost my reference number?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Visit the barangay hall with a valid ID and provide your full name and date of application. Our staff can look up your request in our system and provide your reference number.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>
    
    <script>
        // Store the current request data
        let currentRequestData = null;

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'submitted': return 'submitted';
                case 'processing': return 'processing';
                case 'ready': return 'ready';
                case 'completed': return 'completed-status';
                default: return 'processing';
            }
        }

        function getStatusIcon(status) {
            switch(status.toLowerCase()) {
                case 'submitted': return 'fa-paper-plane';
                case 'processing': return 'fa-cog fa-spin';
                case 'ready': return 'fa-check-circle';
                case 'completed': return 'fa-flag-checkered';
                default: return 'fa-hourglass-half';
            }
        }

        function populateTimeline(data) {
            const timeline = document.getElementById('timeline');
            if (!timeline) return;

            // Clear timeline first
            timeline.innerHTML = '';

            const timelineData = data.timeline || {};
            const statusLower = data.status.toLowerCase();
            const typeLower = data.type.toLowerCase();

            // If the request is rejected or dropped, skip timeline
            if (statusLower === 'rejected' || statusLower === 'dropped') {
                timeline.innerHTML = `
                    <div class="timeline-item rejected">
                        <div class="timeline-marker">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Status: ${data.status}</h4>
                            <p>Your request has been ${data.status.toLowerCase()}.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Define timeline steps
            const steps = [
                { key: 'submitted', label: 'Request Submitted', description: 'Your application has been received' },
                { key: 'verified', label: 'Documents Verified', description: 'Submitted documents have been verified' },
                { key: 'processing', label: 'Processing', description: 'Your request is being processed' },
                { key: 'ready', label: 'Ready for Release', description: 'Document ready for pickup/download' },
                { key: 'completed', label: 'Completed', description: 'Request has been completed' }
            ];

            // Remove "ready" step for Blotter Reports
            if (typeLower.includes('blotter')) {
                steps.splice(3, 1); // Remove the 'ready' step
            }

            let timelineHtml = '';

            steps.forEach((step) => {
                const stepData = timelineData[step.key] || {};
                const status = stepData.status || 'processing';
                const dateTime = stepData.date_time || (status === 'processing' ? 'Pending' : '');

                let icon = '<i class="fas fa-circle"></i>';
                if (status === 'completed') icon = '<i class="fas fa-check"></i>';
                else if (status === 'processing') icon = '<i class="fas fa-spinner fa-spin"></i>';
                else if (status === 'rejected' || status === 'dropped') icon = '<i class="fas fa-times-circle"></i>';

                timelineHtml += `
                    <div class="timeline-item ${status}">
                        <div class="timeline-marker">
                            ${icon}
                        </div>
                        <div class="timeline-content">
                            <h4>${step.label}</h4>
                            <p>${step.description}</p>
                            <span class="time">${dateTime}</span>
                        </div>
                    </div>
                `;
            });

            timeline.innerHTML = timelineHtml;
        }

        document.getElementById('trackForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const refNumber = document.getElementById('referenceNumber').value;

            const submitBtn = this.querySelector('.btn-track');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
            submitBtn.disabled = true;

            fetch("{{ route('track.request.search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    reference_number: refNumber
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.error){
                    alert("Reference number not found");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-search"></i> Track Request';
                    return;
                }

                // Store the current request data
                currentRequestData = data;

                // Update status badge
                const statusBadge = document.getElementById('resultStatusBadge');
                statusBadge.className = `status-badge ${getStatusClass(data.status)}`;
                statusBadge.innerHTML = `<i class="fas ${getStatusIcon(data.status)}"></i> ${data.status}`;

                // Update request details
                document.getElementById('resultRefNumber').textContent = data.reference;
                document.getElementById('resultDocType').textContent = data.type;
                document.getElementById('resultName').textContent = data.name;
                document.getElementById('resultDate').textContent = data.date;
                document.getElementById('resultExpected').textContent = data.expected_completion || 'TBD';
                document.getElementById('resultFee').textContent = data.amount ? `₱${data.amount}` : '₱100.00';

                // Populate timeline
                populateTimeline(data);
                
                // Show the result section
                document.getElementById('trackResult').style.display = 'block';

                // Scroll to result
                document.getElementById('trackResult').scrollIntoView({ behavior: 'smooth' });

                submitBtn.innerHTML = '<i class="fas fa-search"></i> Track Request';
                submitBtn.disabled = false;
            })
            .catch(err => {
                console.error('Error:', err);
                alert("Server error. Please try again.");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-search"></i> Track Request';
            });
        });

        function resetForm() {
            document.getElementById('trackForm').reset();
            document.getElementById('trackResult').style.display = 'none';
            currentRequestData = null;
            document.getElementById('referenceNumber').focus();
        }

        function printRequestDetails() {
            if (!currentRequestData) {
                alert('No request data to print');
                return;
            }

            const applicantName = currentRequestData.name;
            const referenceNum = currentRequestData.reference;
            const service = currentRequestData.type;
            const currentDate = currentRequestData.date;
            const amount = currentRequestData.amount;
            const status = currentRequestData.status.toLowerCase();

            // Handle rejected or dropped requests
            if (status === 'rejected' || status === 'dropped') {
                alert(`Your request has been ${status}.`);
                return;
            }

            QRCode.toDataURL(referenceNum)
            .then((qrDataUrl) => {
                const trackContent = 
                `<!DOCTYPE html>
                <html>
                <head>
                    <title>${ service } Application Receipt</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 30px; 
                            line-height: 1.5; 
                        }

                        .header { 
                            text-align: center; 
                            margin-bottom: 20px; 
                            border-bottom: 2px solid #C62828; 
                            padding-bottom: 15px; 
                        }

                        .header h1 { 
                            color: #C62828; 
                            margin-bottom: 5px; 
                            font-size: 22px; 
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            gap: 10px;
                        }

                        .header h1 i {
                            font-size: 20px;
                        }

                        .header .subtitle { 
                            color: #666; 
                            font-size: 13px; 
                        }

                        .details { 
                            margin: 20px 0; 
                        }

                        .detail-row { 
                            display: flex; 
                            margin: 6px 0; 
                            padding: 4px 0; 
                            border-bottom: 1px solid #eee; 
                        }

                        .detail-label { 
                            font-weight: bold; 
                            color: #333; 
                            width: 170px; 
                        }

                        .detail-value { 
                            color: #444; 
                            flex: 1; 
                        }

                        .qr-code { 
                            text-align: center; 
                            margin: 15px 0; 
                        }

                        .qr-code img { 
                            width: 130px; 
                            height: 130px; 
                            margin: 10px auto; 
                            display: block;
                            border: 1px solid #ddd; 
                            padding: 8px; 
                            background: white; 
                        }

                        .instructions { 
                            margin-top: 15px; 
                            padding: 15px; 
                            background: #f8f9fa; 
                            border-radius: 8px; 
                            font-size: 13px;
                        }

                        .instructions h3 {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            color: #C62828;
                        }

                        .footer { 
                            text-align: center; 
                            margin-top: 20px; 
                            color: #666; 
                            font-size: 11px; 
                            border-top: 1px solid #eee; 
                            padding-top: 10px; 
                        }
                        @page {
                            size: 8.5in 11in;
                            margin: 0.5in;
                        }

                        @media print {
                            body {
                                margin: 0;
                                font-size: 12px;
                            }

                            .no-print {
                                display: none !important;
                            }

                            html, body {
                                width: 8.5in;
                                height: 11in;
                            }

                            * {
                                page-break-inside: avoid !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1><i class="fas fa-file-signature"></i> ${ service } Application Receipt</h1>
                        <p class="subtitle">Barangay Hulong Duhat, Malabon City</p>
                    </div>
                    
                    <div class="details">
                        <h3>Application Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Reference Number:</div>
                            <div class="detail-value"><strong>${referenceNum}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Applicant Name:</div>
                            <div class="detail-value">${applicantName}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date Submitted:</div>
                            <div class="detail-value">${currentDate}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span style="color: #C62828; font-weight: 600;">Submitted for ${ status }</span></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount to Pay:</div>
                            <div class="detail-value"><strong>₱${ amount }.00</strong></div>
                        </div>
                    </div>
                    
                    <div class="qr-code">
                        <p><i class="fas fa-qrcode"></i> Scan to Verify Application Status</p>
                        <img src="${qrDataUrl}" alt="QR Code for ${referenceNum}">
                        <p style="font-size: 12px; color: #666; margin-top: 10px;">Reference: ${referenceNum}</p>
                    </div>
                    
                    <div class="instructions">
                        <h3><i class="fas fa-clipboard-list"></i> Next Steps</h3>
                        <div class="step"><strong>1.</strong> Wait for processing confirmation (1-3 business days)</div>
                        <div class="step"><strong>2.</strong> Visit Barangay Hall during office hours</div>
                        <div class="step"><strong>3.</strong> Present this receipt and valid ID</div>
                        <div class="step"><strong>4.</strong> Pay ₱${ amount }.00 at the cashier</div>
                        <div class="step"><strong>5.</strong> Receive your ${ service }</div>
                        
                        <div class="important-note">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Important Note:</strong> Bring this receipt and the same valid ID you used during application when claiming your ${ service }. Payment must be made in cash at the barangay hall.
                        </div>
                        
                        <p><strong><i class="fas fa-map-marker-alt"></i> Barangay Hall Location:</strong><br>
                        1 M. Blas St, Malabon, Metro Manila<br>
                        <strong><i class="fas fa-clock"></i> Office Hours:</strong><br>
                        Monday - Friday: 8:00 AM - 5:00 PM<br>
                        Saturday: 8:00 AM - 12:00 PM<br>
                        <strong><i class="fas fa-phone"></i> Contact:</strong> (02) 123-4567</p>
                    </div>
                    
                    <div class="footer">
                        <p>This is an electronically generated receipt. No signature is required.</p>
                        <p>Barangay Hulong Duhat Online Services © ${new Date().getFullYear()}</p>
                    </div>
                    
                    <div class="no-print" style="text-align: center; margin-top: 30px;">
                        <button onclick="window.print()" style="padding: 12px 25px; background: #C62828; color: white; border: none; border-radius: 8px; cursor: pointer; margin: 5px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-print"></i> Print Receipt
                        </button>
                        <button onclick="window.close()" style="padding: 12px 25px; background: #666; color: white; border: none; border-radius: 8px; cursor: pointer; margin: 5px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-times"></i> Close Window
                        </button>
                    </div>
                </body>
                </html>
                `;

                const trackWindow = window.open('', '_blank', 'width=800,height=600');

                if(trackWindow){
                    trackWindow.document.write(trackContent);
                    trackWindow.document.close();
                }else{
                    alert('Please allow popups');
                }
            });
        }
    </script>
</body>
</html>