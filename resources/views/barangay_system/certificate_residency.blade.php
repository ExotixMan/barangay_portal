<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Barangay Hulong Duhat · Certificate of Residency</title>
    
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
            position: relative;
            width: 100%;
        }

        .process-steps-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
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
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
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
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .step-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* === REQUIREMENTS SECTION – two column grid, exact copy of clearance style === */
        .requirements-section {
            padding: 80px 0;
            background: white;
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
            transition: all 0.3s ease;
        }

        .requirements-card:hover, .info-card:hover {
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.1);
            border-color: rgba(198, 40, 40, 0.2);
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

        .requirements-card h2, .info-card h2 {
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
            flex-shrink: 0;
        }

        .requirement-content h4 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .requirement-content p, .id-list li {
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
            margin-top: 3px;
        }

        .info-item h4 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-item p {
            color: #666;
            font-size: 0.95rem;
        }

        .emergency-notice {
            background: rgba(198, 40, 40, 0.1);
            border: 1px solid rgba(198, 40, 40, 0.2);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .emergency-notice i {
            color: #C62828;
            font-size: 1.2rem;
        }

        .emergency-notice p {
            color: #C62828;
            font-weight: 500;
            margin: 0;
        }

        /* === APPLICATION FORM SECTION — same full-width style, apply button exactly as clearance === */
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

        .form-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Big red apply button – exact same as clearance */
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
            border: none;
            cursor: pointer;
        }

        .btn-apply-now i {
            margin-right: 10px;
        }

        .btn-apply-now:hover {
            background: #b71c1c;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(198, 40, 40, 0.4);
        }

        /* RESPONSIVE GRID */
        @media (max-width: 1366px) {
            .container { 
                max-width: 1280px; padding: 0 25px; 
            }
            .content-grid { 
                gap: 30px; 
            }
        }

        @media (max-width: 1200px) {
            .steps-container { 
                grid-template-columns: repeat(2, 1fr); 
            }
        }

        @media (max-width: 992px) {
            .content-grid { 
                grid-template-columns: 1fr; 
            }
            .steps-container { 
                gap: 20px; 
            }
        }

        @media (max-width: 768px) {
            .steps-container { 
                grid-template-columns: 1fr; 
            }
            .form-header h2 { 
                font-size: 2rem; 
                flex-direction: column; 
                gap: 10px; 
            }
        }

        @media (max-width: 576px) {
            .process-steps, 
            .requirements-section, 
            .application-form-section { 
                padding: 60px 0; 
            }
            .container { 
                padding: 0 15px; 
            }
        }
        
        .hidden-form-dummy { 
            display: none; 
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

    <!-- HERO SECTION  -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-house-user bigicon"></i> Certificate of Residency</h1>
                <p>Official proof of residency in Barangay Hulong Duhat. Fast, secure, and hassle‑free online request.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Processing: 1-2 Business Days</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-peso-sign"></i>
                        <span>Fee: ₱50.00</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-check-circle"></i>
                        <span>Walk‑in pickup or authorize</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT – exactly same layout as clearance -->
    <main class="main-content" id="main-content">
        
        <!-- PROCESS STEPS (4 steps) – full width, exact duplicate of clearance style -->
        <section class="process-steps">
            <div class="process-steps-content">
                <h2 class="section-title">How to Get Your Certificate</h2>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon"><i class="fas fa-user-edit"></i></div>
                        <h3>Fill Out Form</h3>
                        <p>Complete the online application with accurate personal & residency details</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon"><i class="fas fa-file-upload"></i></div>
                        <h3>Upload Requirements</h3>
                        <p>Submit proof of residency and valid ID</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                        <h3>Pay at Barangay Hall</h3>
                        <p>Pay ₱50.00 in cash when you pick up</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon"><i class="fas fa-file-download"></i></div>
                        <h3>Receive Certificate</h3>
                        <p>Get your official Certificate of Residency</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- REQUIREMENTS + INFO CARDS – two column grid, identical to clearance -->
        <section class="requirements-section">
            <div class="container">
                <div class="content-grid">
                    <!-- LEFT: requirements card -->
                    <div class="requirements-card">
                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i>
                            <h2>Requirements</h2>
                        </div>
                        <div class="requirements-list">
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Primary Proof of Residency (any)</h4>
                                    <ul class="id-list">
                                        <li>Barangay ID / Cedula</li>
                                        <li>Voter's ID with Barangay Hulo address</li>
                                        <li>Utility bill (electric, water, internet) – last 3 months</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Government‑issued ID</h4>
                                    <p>Passport, Driver’s License, UMID, Postal ID, or any valid ID</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>For renters</h4>
                                    <p>Lease contract or notarized affidavit of residency</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Family application</h4>
                                    <p>Proof of relationship (birth/marriage certificate) for each member</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT: info card -->
                    <div class="info-card">
                        <div class="card-header">
                            <i class="fas fa-info-circle"></i>
                            <h2>Important Information</h2>
                        </div>
                        <div class="info-content">
                            <div class="info-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <h4>Processing & Validity</h4>
                                    <p>1-2 business days verification. Certificate valid for 6 months.</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <h4>Office Hours</h4>
                                    <p>Monday - Friday: 8:00 AM - 5:00 PM<br>Saturday: 8:00 AM - 12:00 PM</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-file-signature"></i>
                                <div>
                                    <h4>No proof? No problem</h4>
                                    <p>Two barangay officials can vouch for your residency. Visit hall for assistance.</p>
                                </div>
                            </div>
                        </div>
                        <div class="emergency-notice">
                            <i class="fas fa-bell"></i>
                            <p>For urgent concerns, visit the barangay hall during office hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- APPLICATION SECTION – BIG RED BUTTON, exactly like clearance "Ready to Apply?" -->
        <section class="application-form-section" id="apply-form">
            <div class="container">
                <div class="form-header">
                    <h2><i class="fas fa-file-signature"></i> Ready to Apply?</h2>
                    <p>Click the button below to start your Certificate of Residency application</p>
                </div>
                <div class="apply-button-container" style="text-align: center; padding: 40px 20px;">
                    <a href="{{ route('residency.form')}}" class="btn-apply-now">
                        <i class="fas fa-house-user"></i> Apply for Certificate Now
                    </a>
                    <p style="margin-top: 20px; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> Processing Time: 1-2 Business Days · Fee: ₱50.00
                    </p>
                </div>
                <div style="display: none;"></div>
            </div>
        </section>

        <!-- FAQ SECTION  -->
        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long is the Certificate of Residency valid?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>The certificate is valid for six (6) months from the date of issuance. Some offices may require one issued within the last 3 months.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>I'm renting, can I still apply?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Absolutely. Just present your lease contract or any utility bill under your name. If bills are under the owner's name, an authorization letter will suffice.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can I apply for my whole family in one request?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes. Use the family application option. Submit proof of relationship (birth/marriage certificates) and pay ₱50.00 per certificate.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What if I don't have any bill or ID under my name?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Two barangay officials or your landlord can vouch for you. Please visit the barangay hall to process an affidavit of residency.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Can a representative pick up the certificate?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, with an authorization letter, photocopy of your valid ID, and the representative's valid ID. Don't forget the reference number.</p>
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

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/faq.js') }}"></script>
</body>
</html>