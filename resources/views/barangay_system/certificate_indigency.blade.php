<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Barangay Hulong Duhat · Certificate of Indigency</title>

    <!-- Bootstrap 5 CSS (exact same as clearance) -->
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

        /* PROCESS STEPS */
        .process-steps {
            padding: 80px 0;
            background: #f8f9fa;
            width: 100%;
        }
        .process-steps-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            font-size: 2.2rem;
            font-weight: 700;
            color: #C62828;
        }
        .steps-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }
        .step-card {
            background: white;
            border-radius: 15px;
            padding: 35px 25px;
            text-align: center;
            position: relative;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s;
            border: 1px solid #f0f0f0;
        }
        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(198,40,40,0.15);
            border-color: #C62828;
        }
        .step-number {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px; height: 30px;
            background: #C62828;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .step-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }
        .step-card h3 { font-size: 1.3rem; font-weight: 600; margin-bottom: 10px; }
        .step-card p { color: #666; font-size: 0.95rem; }

        /* BENEFITS  */
        .benefits-section {
            padding: 80px 0;
            background: white;
        }
        .benefits-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .benefits-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
        }
        .benefit-card {
            flex: 0 1 260px;
            background: white;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            transition: 0.3s;
            border: 1px solid #eee;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .benefit-card:hover {
            transform: translateY(-5px);
            border-color: #C62828;
            box-shadow: 0 15px 30px rgba(198,40,40,0.1);
        }
        .benefit-icon {
            width: 70px; height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }
        .benefit-icon.medical { 
            background: linear-gradient(135deg, #2196F3, #1976D2); 
        }
        .benefit-icon.education { 
            background: linear-gradient(135deg, #4CAF50, #2E7D32); 
        }
        .benefit-icon.financial { 
            background: linear-gradient(135deg, #FF9800, #F57C00); 
        }
        .benefit-icon.legal { 
            background: linear-gradient(135deg, #9C27B0, #7B1FA2); 
        }
        .benefit-card h3 { 
            font-size: 1.2rem; font-weight: 600; margin-bottom: 10px; 
        }
        .benefit-card p { 
            color: #666; font-size: 0.9rem; margin-bottom: 15px; 
        }
        .benefit-list { 
            list-style: none; padding: 0; margin: 0; text-align: left; 
        }
        .benefit-list li { 
            font-size: 0.85rem; color: #666; padding: 4px 0; display: flex; gap: 8px; 
        }
        .benefit-list li i { 
            color: #4CAF50; 
        }

        /* REQUIREMENTS SECTION */
        .requirements-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .requirements-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
        .requirements-card, .info-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .card-header i { 
            font-size: 2rem; 
            color: #C62828; 
        }
        .card-header h2 { 
            font-size: 1.8rem; 
            color: #C62828; 
            margin: 0; 
        }
        .requirements-list { 
            display: flex; 
            flex-direction: column; 
            gap: 20px; 
        }
        .requirement-item { 
            display: flex; 
            gap: 15px; 
            align-items: flex-start; 
        }
        .requirement-item i { 
            color: #4CAF50; 
            font-size: 1.2rem; 
            margin-top: 3px; 
        }
        .requirement-content h4 { 
            font-size: 1.1rem; 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 8px; 
        }
        .requirement-content p, 
        .id-list li { 
            color: #666; 
            font-size: 0.95rem; 
        }
        .id-list { 
            list-style: none; 
            margin: 10px 0 0;
            padding: 0; 
        }
        .id-list li { 
            padding: 3px 0 3px 15px; 
            position: relative; 
        }
        .id-list li::before { 
            content: "•"; 
            color: #C62828; 
            position: absolute; 
            left: 0; 
        }
        .info-content { 
            display: flex; 
            flex-direction: column; 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .info-item { 
            display: flex; 
            gap: 15px; 
            align-items: flex-start; 
        }
        .info-item i { 
            color: #C62828; 
            font-size: 1.2rem; 
        }
        .info-item h4 { 
            font-size: 1.1rem; 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 5px; 
        }
        .emergency-notice {
            background: rgba(198,40,40,0.1);
            border: 1px solid rgba(198,40,40,0.2);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .emergency-notice i { 
            color: #C62828; 
        }
        .emergency-notice p { 
            color: #C62828; 
            font-weight: 500; 
            margin: 0; 
        }

        /* APPLICATION FORM SECTION  */
        .application-form-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .application-form-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .form-header h2 {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        /* IMPORTANCE NOTICE MODAL */
        .notice-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        
        .notice-modal {
            background: white;
            border-radius: 20px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 40px;
            position: relative;
            box-shadow: 0 20px 60px rgba(198,40,40,0.3);
            animation: modalSlideIn 0.4s ease;
            border-top: 8px solid #C62828;
        }
        
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .notice-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 2rem;
            color: #999;
            cursor: pointer;
            transition: 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .notice-modal-close:hover {
            color: #C62828;
            background: rgba(198,40,40,0.1);
        }
        
        .notice-modal-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .notice-modal-icon {
            width: 80px;
            height: 80px;
            background: #C62828;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            flex-shrink: 0;
        }
        
        .notice-modal-title {
            color: #C62828;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        
        .notice-modal-subtitle {
            color: #666;
            font-size: 1rem;
            margin-top: 5px;
        }
        
        .notice-modal-body {
            margin-bottom: 30px;
        }
        
        .notice-modal-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .notice-modal-section h4 {
            color: #C62828;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notice-modal-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .notice-modal-section li {
            padding: 8px 0 8px 25px;
            position: relative;
            color: #555;
        }
        
        .notice-modal-section li:before {
            content: "•";
            color: #C62828;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .legal-warning {
            background: rgba(198,40,40,0.1);
            border-left: 5px solid #C62828;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .legal-warning p {
            color: #C62828;
            font-weight: 500;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .legal-warning i {
            font-size: 1.5rem;
        }
        
        .acknowledgment-box {
            background: white;
            border: 2px solid #C62828;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .checkbox-container input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 3px;
            accent-color: #C62828;
            cursor: pointer;
        }
        
        .checkbox-label {
            color: #333;
            font-size: 0.95rem;
        }
        
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }
        
        .btn-modal-primary {
            background: #C62828;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .btn-modal-primary:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }
        
        .btn-modal-secondary {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .btn-modal-secondary:hover {
            background: #e9ecef;
            border-color: #C62828;
            color: #C62828;
        }

        /*  NOTICE CARD  */
        .notice-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .notice-card {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            display: flex;
            gap: 25px;
            border: 1px solid #eee;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .notice-icon {
            width: 70px; height: 70px;
            background: #FF9800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
            font-size: 2rem;
        }

        .notice-content h3 { 
            font-size: 1.3rem; 
            color: #333; 
            margin-bottom: 12px; 
        }

        .notice-content p { 
            color: #666; 
            font-size: 0.95rem; 
        }

        .notice-buttons { 
            display: flex; 
            gap: 15px; 
            margin-top: 20px; 
        }

        .notice-btn {
            padding: 8px 16px;
            border: 1px solid #C62828;
            border-radius: 6px;
            color: #C62828;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .notice-btn:hover { 
            background: #C62828; 
            color: white; 
        }
        
        /* Responsiveness */
        @media (max-width: 1366px) {
            .container { 
                max-width: 1280px; 
                padding: 0 25px; 
            }

            .content-grid { 
                gap: 30px; 
            }
        }
        @media (max-width: 1200px) {
            .steps-container { 
                grid-template-columns: repeat(2,1fr); 
            }

            .benefit-card { 
                flex: 0 1 280px; 
            }
        }
        @media (max-width: 992px) {
            .content-grid { 
                grid-template-columns: 1fr; 
            }
            
            .notice-card { 
                flex-direction: column; 
                text-align: center; 
            }

            .notice-buttons { 
                justify-content: center; 
            }
            
            .notice-modal {
                padding: 30px 20px;
                width: 95%;
            }
            
            .notice-modal-header {
                flex-direction: column;
                text-align: center;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }
        @media (max-width: 768px) {
            .steps-container { 
                grid-template-columns: 1fr; 
            }
            .form-header h2 { 
                font-size: 2rem; 
                flex-direction: column; 
            }
        }
        @media (max-width: 576px) {
            .process-steps, 
            .requirements-section, 
            .application-form-section{ 
                padding: 60px 0; 
            }

            .container { padding: 0 15px; }
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

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-hands-helping bigicon"></i> Certificate of Indigency</h1>
                <p>Official document for financial assistance, medical aid, and social welfare programs. Dignified, confidential, and hassle‑free application.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Processing: 2‑3 Business Days</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-peso-sign"></i>
                        <span>Fee: ₱25.00</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Social Welfare Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content" id="main-content">
        <!-- PROCESS STEPS – 4 steps, exactly like clearance -->
        <section class="process-steps">
            <div class="process-steps-content">
                <h2 class="section-title">How to Get Your Certificate</h2>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon"><i class="fas fa-user-edit"></i></div>
                        <h3>Fill Out Form</h3>
                        <p>Complete online application with family composition and income details</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon"><i class="fas fa-file-upload"></i></div>
                        <h3>Upload Requirements</h3>
                        <p>Submit valid ID, proof of residency, and income documents</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <h3>Social Worker Review</h3>
                        <p>Assessment and verification (2‑3 days, interview if needed)</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon"><i class="fas fa-file-download"></i></div>
                        <h3>Pickup Certificate</h3>
                        <p>Pay ₱25.00 at barangay hall and receive your certificate</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- BENEFITS – SINGLE ROW, all four cards in one row, centered -->
        <section class="benefits-section">
            <div class="container">
                <h2 class="section-title">Benefits & Assistance Programs</h2>
                <div class="benefits-row">
                    <div class="benefit-card">
                        <div class="benefit-icon medical"><i class="fas fa-heartbeat"></i></div>
                        <h3>Medical Assistance</h3>
                        <p>Free or subsidized services, PhilHealth, medicine aid</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check"></i> Hospital discounts</li>
                            <li><i class="fas fa-check"></i> Medicine assistance</li>
                        </ul>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon education"><i class="fas fa-graduation-cap"></i></div>
                        <h3>Educational Support</h3>
                        <p>Scholarships, school fee discounts, supplies</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check"></i> Scholarship grants</li>
                            <li><i class="fas fa-check"></i> Fee waivers</li>
                        </ul>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon financial"><i class="fas fa-hand-holding-usd"></i></div>
                        <h3>Financial Aid</h3>
                        <p>4Ps, livelihood training, emergency cash aid</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check"></i> 4Ps program</li>
                            <li><i class="fas fa-check"></i> Livelihood support</li>
                        </ul>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon legal"><i class="fas fa-balance-scale"></i></div>
                        <h3>Legal Assistance</h3>
                        <p>Free legal aid, court fee exemptions, document help</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check"></i> Legal counsel</li>
                            <li><i class="fas fa-check"></i> Fee waivers</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- REQUIREMENTS & INFO – two column grid, identical to clearance .content-grid -->
        <section class="requirements-section">
            <div class="container">
                <div class="content-grid">
                    <!-- LEFT: Requirements card -->
                    <div class="requirements-card">
                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i>
                            <h2>Requirements</h2>
                        </div>
                        <div class="requirements-list">
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Valid ID & Proof of Residency</h4>
                                    <ul class="id-list">
                                        <li>Government ID (Passport, Driver’s, UMID)</li>
                                        <li>Barangay ID / Voter’s ID</li>
                                        <li>Utility bill (last 3 months)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Income Documents</h4>
                                    <p>Certificate of employment, latest payslip, or affidavit of no income</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Family Composition</h4>
                                    <p>List of household members with age & relationship</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Special Cases</h4>
                                    <p>PWD ID, Senior Citizen ID, medical certificate, birth certificates (if applicable)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT: Info card (important notes) -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="fas fa-info-circle"></i>
                            <h2>Important Info</h2>
                        </div>
                        <div class="info-content">
                            <div class="info-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <h4>Assessment Criteria</h4>
                                    <p>Family income, size, expenses, and special circumstances. Threshold: ~₱8k‑15k monthly depending on members.</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <h4>Validity</h4>
                                    <p>3 months. Renew with updated documents if needed.</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-user-shield"></i>
                                <div>
                                    <h4>Confidentiality</h4>
                                    <p>All information is treated with utmost confidentiality and dignity.</p>
                                </div>
                            </div>
                        </div>
                        <div class="emergency-notice">
                            <i class="fas fa-bell"></i>
                            <p>False information is punishable under Philippine law.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- APPLICATION BUTTON – exactly like clearance "Ready to Apply?" -->
        <section class="application-form-section" id="apply-form">
            <div class="container">
                <div class="form-header">
                    <h2><i class="fas fa-file-signature"></i> Ready to Apply?</h2>
                    <p>Click the button below to start your Certificate of Indigency application</p>
                </div>
                <div class="apply-button-container" style="text-align: center; padding: 40px 20px;">
                    <button onclick="showNoticeModal()" class="btn-apply-now"  style="border: none; display: inline-block; padding: 20px 50px; background: #C62828; color: white; text-decoration: none; border-radius: 12px; font-size: 1.2rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);">
                        <i class="fas fa-hands-helping"></i> Apply for Certificate Now
                    </button>
                    <p style="margin-top: 20px; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> Processing Time: 2-3 Business Days · Fee: ₱25.00
                    </p>
                </div>
            </div>
        </section>

        <!-- FAQ SECTION – exact copy of clearance structure, indigency questions -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How is indigency determined?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Indigency is assessed based on family income, household size, monthly expenses, and special circumstances (PWD, senior, illness). Our social worker evaluates using poverty threshold guidelines.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What if I have no formal income?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You may submit an affidavit of no income or a barangay certification. A home visit or interview may be conducted to verify your situation.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long is the certificate valid?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>The Certificate of Indigency is valid for three (3) months. Some programs may require a recently issued certificate (within 30 days).</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can students or senior citizens apply?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes. Students need family income documents; senior citizens can present their OSCA ID. The certificate is often used for scholarships or medical assistance.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Do I need an interview?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Not always. First-time applicants or unclear income cases may require a brief interview with the barangay social worker. You will be contacted if needed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- IMPORTANCE NOTICE MODAL -->
    <div id="noticeModalOverlay" class="notice-modal-overlay">
        <div class="notice-modal">
            <button onclick="closeNoticeModal()" class="notice-modal-close">&times;</button>
            
            <div class="notice-modal-header">
                <div class="notice-modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h2 class="notice-modal-title">IMPORTANT NOTICE</h2>
                    <p class="notice-modal-subtitle">Please read carefully before proceeding with your application</p>
                </div>
            </div>
            
            <div class="notice-modal-body">
                <div class="legal-warning">
                    <p>
                        <i class="fas fa-gavel"></i>
                        <strong>REPUBLIC ACT NO. 11313 (Safe Spaces Act) & OTHER APPLICABLE LAWS</strong>
                    </p>
                </div>
                
                <div class="notice-modal-section">
                    <h4><i class="fas fa-exclamation-circle"></i> Legal Consequences of False Information</h4>
                    <ul>
                        <li>Providing false information or submitting falsified documents is punishable under Philippine law</li>
                        <li>Penalties may include imprisonment, fines, or both as determined by the court</li>
                        <li>False declarations may result in permanent disqualification from barangay services</li>
                        <li>Legal action may be pursued against individuals who misrepresent their indigent status</li>
                        <li>The barangay reserves the right to verify all submitted information through home visits and interviews</li>
                    </ul>
                </div>
                
                <div class="notice-modal-section">
                    <h4><i class="fas fa-shield-alt"></i> Confidentiality & Data Privacy</h4>
                    <ul>
                        <li>All personal information submitted will be processed in accordance with the Data Privacy Act of 2012 (RA 10173)</li>
                        <li>Your information will only be used for assessment and verification purposes</li>
                        <li>The barangay implements strict security measures to protect your data</li>
                        <li>Information will not be shared with third parties without your consent, except as required by law</li>
                    </ul>
                </div>
                
                <div class="notice-modal-section">
                    <h4><i class="fas fa-clock"></i> Application Requirements Reminder</h4>
                    <ul>
                        <li>Valid government-issued ID or Barangay ID</li>
                        <li>Proof of residency (utility bill within last 3 months)</li>
                        <li>Income documents (employment certificate, payslip, or affidavit of no income)</li>
                        <li>Complete list of household members with age and relationship</li>
                        <li>Special documents for PWD, Senior Citizen, or medical cases (if applicable)</li>
                    </ul>
                </div>
                
                <div class="acknowledgment-box">
                    <div class="checkbox-container">
                        <input type="checkbox" id="acknowledgeCheckbox">
                        <label for="acknowledgeCheckbox" class="checkbox-label">
                            <strong>I acknowledge that I have read and understood the importance notice above. I certify that all information I will provide is true and correct, and I understand the legal consequences of providing false information or falsified documents. I agree to the terms and conditions set forth by Barangay Hulong Duhat for the issuance of Certificate of Indigency.</strong>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button onclick="closeNoticeModal()" class="btn-modal-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button id="proceedButton" onclick="proceedToForm()" class="btn-modal-primary" disabled>
                    <i class="fas fa-check-circle"></i> I Understand, Proceed to Form
                </button>
            </div>
        </div>
    </div>

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

    <!-- BACK TO TOP BUTTON -->
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>

    <script>
        // Modal Functions
        function showNoticeModal() {
            document.getElementById('noticeModalOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
        
        function closeNoticeModal() {
            document.getElementById('noticeModalOverlay').style.display = 'none';
            document.body.style.overflow = 'auto'; // Enable scrolling
        }
        
        function proceedToForm() {
            // Check if checkbox is checked
            const checkbox = document.getElementById('acknowledgeCheckbox');
            if (checkbox.checked) {
                // Redirect to the application form
                window.location.href = '{{ route('indigency.form') }}';
            }
        }
        
        // Enable/Disable proceed button based on checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('acknowledgeCheckbox');
            const proceedButton = document.getElementById('proceedButton');
            
            if (checkbox && proceedButton) {
                checkbox.addEventListener('change', function() {
                    proceedButton.disabled = !this.checked;
                });
            }
            
            // Close modal when clicking outside
            const overlay = document.getElementById('noticeModalOverlay');
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeNoticeModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay.style.display === 'flex') {
                    closeNoticeModal();
                }
            });
        });
    </script>
</body>
</html>