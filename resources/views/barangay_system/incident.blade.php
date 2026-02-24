<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Blotter Report</title>
    
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

        /* Action Cards Section */
        .action-cards-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .action-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .card-icon {
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

        .action-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .action-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .card-btn {
            background: none;
            border: 2px solid #C62828;
            color: #C62828;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
        }

        .card-btn:hover {
            background: #C62828;
            color: white;
        }

        .card-btn.emergency {
            background: #C62828;
            color: white;
            border-color: #C62828;
        }

        .card-btn.emergency:hover {
            background: #b71c1c;
            border-color: #b71c1c;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #C62828;
            margin-bottom: 15px;
        }

        .section-header h2 i {
            margin-right: 10px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .stat-box {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.1);
            border-color: rgba(198, 40, 40, 0.2);
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(198, 40, 40, 0.2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #C62828;
        }

        .stat-content h3 {
            font-size: 1rem;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #C62828;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-trend {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }

        .text-success {
            color: #4CAF50;
        }

        /* Apply Section */
        .apply-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .apply-button-container {
            text-align: center;
            padding: 40px 20px;
        }

        .btn-apply-now {
            display: inline-block;
            padding: 20px 50px;
            background: #C62828;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);
        }

        .btn-apply-now:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(198, 40, 40, 0.4);
            color: white;
        }

        /* Track Section */
        .track-section {
            padding: 60px 0;
            background: white;
        }

        .track-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .track-input-group {
            display: flex;
            gap: 15px;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #eee;
        }

        .input-with-icon {
            flex: 1;
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #C62828;
        }

        .input-with-icon input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .input-with-icon input:focus {
            outline: none;
            border-color: #C62828;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.1);
        }

        .track-btn {
            padding: 15px 30px;
            background: #C62828;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .track-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        /* History Section */
        .history-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .history-filters {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .filter-group label i {
            color: #C62828;
            margin-right: 5px;
        }

        .filter-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #C62828;
        }

        .reports-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .reports-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table th {
            background: #C62828;
            color: white;
            padding: 15px;
            font-weight: 600;
            text-align: left;
        }

        .reports-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .reports-table tr:last-child td {
            border-bottom: none;
        }

        .reports-table tr:hover td {
            background: #f8f9fa;
        }

        .no-reports {
            text-align: center;
            padding: 60px 20px;
        }

        .no-reports i {
            font-size: 4rem;
            color: #C62828;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-reports h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .no-reports p {
            color: #666;
            margin-bottom: 20px;
        }

        .btn-new-report {
            padding: 12px 30px;
            background: #C62828;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-new-report:hover {
            background: #b71c1c;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .action-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .history-filters {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
        }

        @media (max-width: 768px) {    
            .action-cards-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-box {
                padding: 20px;
            }
            
            .track-input-group {
                flex-direction: column;
            }
            
            .track-btn {
                width: 100%;
            }
            
            .reports-table {
                overflow-x: auto;
            }
            
            .reports-table table {
                min-width: 800px;
            }
            
            .section-header h2 {
                font-size: 1.8rem;
            }
            
            .btn-apply-now {
                padding: 15px 30px;
                font-size: 1rem;
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .action-card {
                padding: 25px 20px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .section-header h2 {
                font-size: 1.5rem;
            }
            
            .section-header p {
                font-size: 1rem;
            }
            
            .track-input-group {
                padding: 15px;
            }
            
            .input-with-icon input {
                padding: 12px 12px 12px 40px;
            }
            
            .track-btn {
                padding: 12px 20px;
            }
            
            .back-to-top-custom {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        @media (max-width: 360px) {  
            .action-card h3 {
                font-size: 1.1rem;
            }
            
            .action-card p {
                font-size: 0.85rem;
            }
            
            .card-btn {
                padding: 8px 15px;
                font-size: 0.9rem;
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
                        <a class="nav-link dropdown-toggle active" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
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
                <h1><i class="fas fa-clipboard-list bigicon"></i> Incident Report</h1>
                <p>File your incident report anytime, anywhere. Fast, secure, and easy.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-shield-alt"></i>
                        <span>24/7 Incident Reporting</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Response: Within 24 Hours</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-user-secret"></i>
                        <span>Confidential Reporting</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Quick Action Cards -->
        <section class="action-cards-section">
            <div class="container">
                <div class="action-cards-grid">
                    <div class="action-card">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>File New Report</h3>
                        <p>Report incidents, disputes, or concerns in our community</p>
                        <a href="{{ route('incident.form') }}" class="card-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-plus-circle"></i> Start New Report
                        </a>
                    </div>
                    <div class="action-card">
                        <div class="card-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Track Report Status</h3>
                        <p>Check the status of your submitted blotter reports</p>
                        <button class="card-btn" id="trackReportBtn">
                            <i class="fas fa-search"></i> Track Status
                        </button>
                    </div>
                    <div class="action-card">
                        <div class="card-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>View History</h3>
                        <p>Access your previous blotter reports and resolutions</p>
                        <button class="card-btn" id="viewHistoryBtn">
                            <i class="fas fa-history"></i> View History
                        </button>
                    </div>
                    <div class="action-card">
                        <div class="card-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Emergency Contact</h3>
                        <p>Immediate assistance for urgent situations</p>
                        <button class="card-btn emergency" id="emergencyBtn">
                            <i class="fas fa-phone-alt"></i> Call Emergency
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Incident Statistics -->
        <section class="stats-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-chart-bar"></i> Incident Statistics</h2>
                    <p>Current status of blotter reports in Barangay Hulong Duhat</p>
                </div>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Active Cases</h3>
                            <div class="stat-number">24</div>
                            <p class="stat-trend"><i class="fas fa-arrow-down text-success"></i> 12% from last month</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Resolved Cases</h3>
                            <div class="stat-number">156</div>
                            <p class="stat-trend"><i class="fas fa-arrow-up text-success"></i> 8% resolution rate</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Under Mediation</h3>
                            <div class="stat-number">18</div>
                            <p class="stat-trend"><i class="fas fa-clock"></i> Average: 7 days</p>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Response Rate</h3>
                            <div class="stat-number">98%</div>
                            <p class="stat-trend"><i class="fas fa-thumbs-up"></i> Within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Apply for Report Section -->
        <section class="apply-section" id="applySection">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-file-medical"></i> File New Blotter Report</h2>
                    <p>Click the button below to start your Blotter Report application</p>
                </div>

                <div class="apply-button-container">
                    <a href="{{ route('incident.form') }}" class="btn-apply-now">
                        <i class="fas fa-clipboard-list"></i> File Blotter Report Now
                    </a>
                    <p style="margin-top: 20px; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> Response Time: Within 24 Hours
                    </p>
                </div>
            </div>
        </section>

        <!-- Track Report Section -->
        <section class="track-section" id="trackSection">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-search"></i> Track Report Status</h2>
                    <p>Enter your reference number to check the status of your blotter report</p>
                </div>
                
                <div class="track-form">
                    <div class="track-input-group">
                        <div class="input-with-icon">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" id="trackReference" placeholder="Enter reference number (e.g., BL-2024-001234)">
                        </div>
                        <button class="track-btn" id="checkStatusBtn">
                            <i class="fas fa-search"></i> Check Status
                        </button>
                    </div>
                    
                    <div class="track-result" id="trackResult">
                        <!-- Results will be displayed here -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Report History Section -->
        <section class="history-section" id="historySection">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-history"></i> Report History</h2>
                    <p>View your previous blotter reports and their status</p>
                </div>
                
                <div class="history-filters">
                    <div class="filter-group">
                        <label for="filterStatus"><i class="fas fa-filter"></i> Filter by Status</label>
                        <select id="filterStatus">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="investigating">Under Investigation</option>
                            <option value="mediation">In Mediation</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filterDate"><i class="fas fa-calendar"></i> Filter by Date</label>
                        <select id="filterDate">
                            <option value="all">All Time</option>
                            <option value="week">Last 7 Days</option>
                            <option value="month">Last 30 Days</option>
                            <option value="quarter">Last 3 Months</option>
                            <option value="year">Last Year</option>
                        </select>
                    </div>
                </div>
                
                <div class="reports-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference No.</th>
                                <th>Date Filed</th>
                                <th>Incident Type</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reportsTableBody">
                            <!-- Reports will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="no-reports" id="noReportsMessage" style="display: none;">
                    <i class="fas fa-clipboard"></i>
                    <h3>No Reports Found</h3>
                    <p>You haven't filed any blotter reports yet.</p>
                    <button class="btn-new-report" id="startNewFromHistory">
                        <i class="fas fa-plus"></i> File New Report
                    </button>
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
                            <span>What types of incidents should I report?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Report community disputes, security concerns, public safety issues, property conflicts, noise complaints, and any barangay-related incidents requiring official documentation.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long does it take to process a report?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Reports are reviewed within 24 hours. Simple cases may be resolved in 3-5 days, while complex cases requiring mediation may take 1-2 weeks.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I report anonymously?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can submit anonymous reports. However, anonymous reports may limit investigation effectiveness as officials cannot follow up for additional details.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What happens after I submit a report?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Your report is reviewed, assigned to an official, investigated, and appropriate action is taken. You'll receive updates via email or SMS if provided.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I withdraw a report?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can request to withdraw a report. Contact the barangay office with your reference number. Note: Some reports involving public safety may still require action.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Is there a cost to file a blotter report?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>No, filing a blotter report is a free service provided by the barangay. However, certain cases requiring mediation may involve minimal administrative fees.</p>
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
                            <a href="{{ route('incident') }}" class="footer-link active">
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

    <script>
        // // Track Report Button
        // document.getElementById('trackReportBtn').addEventListener('click', function() {
        //     document.getElementById('trackSection').style.display = 'block';
        //     document.getElementById('historySection').style.display = 'none';
        //     document.getElementById('applySection').style.display = 'none';
            
        //     // Smooth scroll to track section
        //     document.getElementById('trackSection').scrollIntoView({ behavior: 'smooth' });
        // });

        // // View History Button
        // document.getElementById('viewHistoryBtn').addEventListener('click', function() {
        //     document.getElementById('historySection').style.display = 'block';
        //     document.getElementById('trackSection').style.display = 'none';
        //     document.getElementById('applySection').style.display = 'none';
            
        //     // Smooth scroll to history section
        //     document.getElementById('historySection').scrollIntoView({ behavior: 'smooth' });
        // });

        // // Emergency Button  
        // document.getElementById('emergencyBtn').addEventListener('click', function() {
        //     if (confirm('For emergency situations, please call 911 immediately.\n\nClick OK to call 911.')) {
        //         window.location.href = 'tel:911';
        //     }
        // });

        // // Start New Report from History
        // document.getElementById('startNewFromHistory').addEventListener('click', function() {
        //     window.location.href = 'blotter_report_form.html';
        // });

        // Check Status Button
        document.getElementById('checkStatusBtn').addEventListener('click', function() {
            const reference = document.getElementById('trackReference').value.trim();
            
            if (!reference) {
                alert('Please enter a reference number');
                return;
            }
            
            // Simulate status check
            const trackResult = document.getElementById('trackResult');
            trackResult.style.display = 'block';
            trackResult.innerHTML = `
                <div class="stat-box" style="margin-top: 20px;">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Report Status: ${reference}</h3>
                        <div class="stat-number">Under Investigation</div>
                        <p class="stat-trend">Last updated: ${new Date().toLocaleDateString()}</p>
                        <p style="margin-top: 10px; color: #666;">Your report is currently being reviewed by barangay officials.</p>
                    </div>
                </div>
            `;
        });

        // Filter functionality (simulated)
        document.getElementById('filterStatus').addEventListener('change', function() {
            // In a real application, this would filter the table
            console.log('Filter by status:', this.value);
        });

        document.getElementById('filterDate').addEventListener('change', function() {
            // In a real application, this would filter the table
            console.log('Filter by date:', this.value);
        });
    </script>
</body>
</html>