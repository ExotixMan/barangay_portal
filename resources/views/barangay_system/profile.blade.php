<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – Barangay Hulo Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floating-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        /* ── Page header ── */
        .profile-hero {
            background: linear-gradient(135deg, #c62828 0%, #8e0000 100%);
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .profile-hero::before {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0; right: 0;
            height: 60px;
            background: #f4f6f9;
            clip-path: ellipse(55% 100% at 50% 100%);
        }
        .profile-hero h1 { color: #fff; font-weight: 700; font-size: 2rem; margin-bottom: 6px; }
        .profile-hero p  { color: rgba(255,255,255,.75); font-size: .92rem; margin: 0; }
        .profile-hero .hero-icon {
            width: 72px; height: 72px;
            background: rgba(255,255,255,.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #fff;
            margin: 0 auto 16px;
            border: 2px solid rgba(255,255,255,.3);
        }

        /* ── Layout ── */
        .profile-layout {
            margin-top: -55px;
            padding-bottom: 60px;
            position: relative;
            z-index: 1;
        }
        .profile-layout > .container {
            max-width: 1140px;
        }

        /* ── Sidebar card ── */
        .profile-sidebar {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
            padding: 32px 24px;
            text-align: center;
            position: sticky;
            top: 90px;
        }
        .avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 16px;
        }
        .avatar-img {
            width: 110px; height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #c62828;
            box-shadow: 0 6px 20px rgba(198,40,40,.25);
        }
        .avatar-initials {
            width: 110px; height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg,#c62828,#8e0000);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.4rem; font-weight: 700; color: #fff;
            border: 4px solid rgba(255,255,255,.4);
            box-shadow: 0 6px 20px rgba(198,40,40,.25);
        }
        .avatar-edit-btn {
            position: absolute;
            bottom: 2px; right: 2px;
            width: 32px; height: 32px;
            background: #c62828; color: #fff;
            border: 2px solid #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
            cursor: pointer; transition: background .2s;
        }
        .avatar-edit-btn:hover { background: #8e0000; }

        .profile-name   { font-size: 1.15rem; font-weight: 700; color: #1c2630; margin-bottom: 2px; }
        .profile-username { font-size: .85rem; color: #888; margin-bottom: 12px; }

        .verified-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: .78rem; padding: 4px 12px;
            border-radius: 20px; font-weight: 600;
        }
        .verified-badge.verified   { background: #dcf6e8; color: #1a7a45; }
        .verified-badge.unverified { background: #fff3cd; color: #856404; }

        .sidebar-divider { border-color: #f0f0f0; margin: 18px 0; }

        .sidebar-stat {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 0;
            font-size: .88rem; color: #555;
            border-bottom: 1px solid #f5f5f5;
        }
        .sidebar-stat:last-child { border-bottom: none; }
        .sidebar-stat .val {
            font-weight: 700; color: #c62828;
            background: #fff0f0; padding: 2px 10px;
            border-radius: 12px; font-size: .82rem;
        }

        .sidebar-nav { margin-top: 20px; }
        .sidebar-nav .nav-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            font-size: .88rem; font-weight: 500; color: #555;
            cursor: pointer; transition: all .2s;
            border: none; background: none; width: 100%; text-align: left;
        }
        .sidebar-nav .nav-pill:hover,
        .sidebar-nav .nav-pill.active {
            background: #fff0f0; color: #c62828;
        }
        .sidebar-nav .nav-pill i { width: 18px; text-align: center; }

        /* ── Main panel ── */
        .profile-panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .panel-header {
            padding: 24px 28px 0;
            border-bottom: 2px solid #f5f5f5;
        }
        .panel-header h5 { font-weight: 700; color: #1c2630; margin-bottom: 0; }
        .panel-header p  { font-size: .85rem; color: #888; margin-bottom: 0; }

        .tab-pane-body { padding: 28px; }

        /* ── Form styles ── */
        .profile-label {
            font-size: .85rem; font-weight: 600; color: #444; margin-bottom: 5px;
        }
        .profile-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: .7rem 1rem;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
            font-family: 'Poppins', sans-serif;
        }
        .profile-control:focus {
            border-color: #c62828;
            box-shadow: 0 0 0 3px rgba(198,40,40,.12);
            outline: none;
        }
        .profile-control:disabled, .profile-control[readonly] {
            background: #f8f9fa; color: #888; cursor: not-allowed;
        }
        .profile-control.is-invalid { border-color: #dc3545; }

        .btn-profile-save {
            background: linear-gradient(135deg,#c62828,#8e0000);
            color: #fff; border: none;
            padding: .75rem 2rem;
            border-radius: 50px; font-weight: 600;
            font-size: .9rem; cursor: pointer;
            transition: all .25s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-profile-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(198,40,40,.3);
        }
        .btn-profile-cancel {
            background: #fff; color: #555;
            border: 2px solid #e9ecef;
            padding: .75rem 2rem;
            border-radius: 50px; font-weight: 500;
            font-size: .9rem; cursor: pointer;
            transition: all .25s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-profile-cancel:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        /* ── Info card rows (read-only view) ── */
        .info-row {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 14px 0; border-bottom: 1px solid #f5f5f5;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .info-icon {
            width: 34px; height: 34px; min-width: 34px;
            background: #fff0f0; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #c62828; font-size: .8rem;
        }
        .info-row .info-label { font-size: .75rem; color: #999; margin-bottom: 1px; }
        .info-row .info-val   { font-size: .9rem; font-weight: 600; color: #1c2630; }

        /* ── Request history table ── */
        .req-table thead th { background: #f8f9fa; font-size: .8rem; font-weight: 600; color: #555; border: none; padding: 12px 14px; }
        .req-table tbody td { font-size: .85rem; padding: 12px 14px; vertical-align: middle; border-color: #f5f5f5; }
        .req-table tbody tr:hover { background: #fafafa; }
        .status-badge {
            font-size: .75rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap;
        }
        .status-badge.pending    { background: #fff3cd; color: #856404; }
        .status-badge.approved   { background: #dcf6e8; color: #1a7a45; }
        .status-badge.rejected   { background: #fde8e8; color: #9b1c1c; }
        .status-badge.processing { background: #dbeafe; color: #1e40af; }
        .status-badge.completed  { background: #d1fae5; color: #065f46; }
        .req-type-icon {
            width: 30px; height: 30px;
            background: #fff0f0; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #c62828; font-size: .75rem; margin-right: 8px;
        }

        /* ── Password strength ── */
        .pw-meter { height: 5px; background: #e9ecef; border-radius: 3px; overflow: hidden; margin: 6px 0 3px; }
        .pw-meter-fill { height: 100%; width: 0%; background: #c62828; transition: all .3s; border-radius: 3px; }
        .pw-hint { font-size: .78rem; color: #888; }

        /* ── ID card ── */
        .id-preview-card {
            border-radius: 14px; overflow: hidden;
            border: 2px dashed #e9ecef;
            background: #f8f9fa;
            text-align: center; padding: 24px;
        }
        .id-preview-card img   { max-width: 100%; max-height: 280px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .id-preview-card .id-placeholder { color: #aaa; }
        .id-preview-card .id-placeholder i { font-size: 3.5rem; margin-bottom: 10px; }

        /* ── Alert flash ── */
        .profile-alert {
            border-radius: 10px; padding: 12px 18px;
            font-size: .88rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
            border: none; margin-bottom: 20px;
        }
        .profile-alert.success { background: #dcf6e8; color: #1a7a45; }
        .profile-alert.danger  { background: #fde8e8; color: #9b1c1c; }

        /* ── Stat counters in main panel ── */
        .stat-counter-card {
            background: #f8f9fa; border-radius: 14px;
            padding: 20px; text-align: center;
        }
        .stat-counter-card .sc-icon {
            width: 48px; height: 48px;
            background: #fff0f0; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #c62828; font-size: 1.2rem; margin: 0 auto 10px;
        }
        .stat-counter-card .sc-num { font-size: 1.8rem; font-weight: 700; color: #c62828; line-height: 1; }
        .stat-counter-card .sc-lbl { font-size: .8rem; color: #777; margin-top: 3px; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .profile-sidebar { position: static; margin-bottom: 24px; }
        }
        @media (max-width: 576px) {
            .tab-pane-body { padding: 18px; }
            .panel-header  { padding: 18px 18px 0; }
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
                                    <li><a class="dropdown-link active" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
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
                                    <li><a class="dropdown-link" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a></li>
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


    {{-- ══════════════════ HERO ══════════════════ --}}
    <section class="profile-hero">
        <div class="container">
            <div class="hero-icon"><i class="fas fa-user"></i></div>
            <h1>My Profile</h1>
            <p>Manage your personal information and account settings</p>
        </div>
    </section>

    {{-- ══════════════════ BODY ══════════════════ --}}
    <div class="profile-layout">
        <div class="container">

            {{-- Flash alerts (outside tabs so they always show) --}}
            @if(session('success_info'))
                <div class="profile-alert success mb-3"><i class="fas fa-check-circle"></i> {{ session('success_info') }}</div>
            @endif
            @if(session('success_account'))
                <div class="profile-alert success mb-3"><i class="fas fa-check-circle"></i> {{ session('success_account') }}</div>
            @endif
            @if(session('success_password'))
                <div class="profile-alert success mb-3"><i class="fas fa-check-circle"></i> {{ session('success_password') }}</div>
            @endif
            @if(session('success_photo'))
                <div class="profile-alert success mb-3"><i class="fas fa-check-circle"></i> {{ session('success_photo') }}</div>
            @endif

            <div class="row g-4" id="profileRoot">

                {{-- ──────── SIDEBAR ──────── --}}
                <div class="col-lg-3">
                    <div class="profile-sidebar">

                        {{-- Avatar --}}
                        <div class="avatar-wrap">
                            @if($resident->profile_photo)
                                <img src="{{ asset($resident->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                            @else
                                <div class="avatar-initials">
                                    {{ strtoupper(substr($resident->firstname,0,1)) }}{{ strtoupper(substr($resident->lastname,0,1)) }}
                                </div>
                            @endif
                            <label for="quickPhotoInput" class="avatar-edit-btn" title="Change photo"><i class="fas fa-camera"></i></label>
                        </div>

                        {{-- Quick photo form --}}
                        <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" id="quickPhotoForm">
                            @csrf
                            <input type="file" id="quickPhotoInput" name="profile_photo" accept="image/jpeg,image/png,image/webp" class="d-none">
                        </form>

                        <div class="profile-name">{{ $resident->firstname }} {{ $resident->lastname }}{{ $resident->suffix ? ', '.$resident->suffix : '' }}</div>
                        <div class="profile-username">{{ $resident->username }}</div>

                        @if($resident->email_verified_at)
                            <span class="verified-badge verified"><i class="fas fa-check-circle"></i> Email Verified</span>
                        @else
                            <span class="verified-badge unverified"><i class="fas fa-exclamation-circle"></i> Email Unverified</span>
                        @endif

                        <hr class="sidebar-divider">

                        <div class="sidebar-stat">
                            <span><i class="fas fa-calendar me-1 text-muted"></i> Member Since</span>
                            <span class="val">{{ $resident->created_at->format('M Y') }}</span>
                        </div>
                        <div class="sidebar-stat">
                            <span><i class="fas fa-file-alt me-1 text-muted"></i> Total Requests</span>
                            <span class="val">{{ $totalRequests }}</span>
                        </div>

                        <hr class="sidebar-divider">

                        <nav class="sidebar-nav" id="sidebarNav">
                            <button class="nav-pill active" data-tab="info">
                                <i class="fas fa-user"></i> Personal Info
                            </button>
                            <button class="nav-pill" data-tab="account">
                                <i class="fas fa-cog"></i> Account Settings
                            </button>
                            <button class="nav-pill" data-tab="requests">
                                <i class="fas fa-file-alt"></i> Request History
                            </button>
                            <button class="nav-pill" data-tab="valid-id">
                                <i class="fas fa-id-card"></i> My Valid ID
                            </button>
                        </nav>

                        <hr class="sidebar-divider">
                        <a href="{{ route('logout.res') }}" class="btn btn-sm w-100 mt-1"
                           style="border:2px solid #fde8e8;color:#c62828;border-radius:50px;font-weight:600;">
                            <i class="fas fa-sign-out-alt me-1"></i> Sign Out
                        </a>
                    </div>
                </div>

                {{-- ──────── MAIN PANEL ──────── --}}
                <div class="col-lg-9">
                    <div class="profile-panel">

                        {{-- ── Tab 1: Personal info ── --}}
                        <div class="tab-content" id="tab-info">
                            <div class="panel-header pb-3">
                                <h5><i class="fas fa-user me-2 text-danger"></i>Personal Information</h5>
                                <p>Update your name, contact details, and address.</p>
                            </div>
                            <div class="tab-pane-body">

                                @if($errors->hasAny(['firstname','middlename','lastname','suffix','birthdate','contact','address']))
                                    <div class="profile-alert danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <div>
                                            @foreach(['firstname','middlename','lastname','suffix','birthdate','contact','address'] as $field)
                                                @foreach($errors->get($field) as $e)
                                                    <div>{{ $e }}</div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('profile.info') }}" id="infoForm">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="profile-label">First Name *</label>
                                            <input type="text" name="firstname" value="{{ old('firstname', $resident->firstname) }}"
                                                class="form-control profile-control @error('firstname') is-invalid @enderror"
                                                required placeholder="First Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="profile-label">Middle Name</label>
                                            <input type="text" name="middlename" value="{{ old('middlename', $resident->middlename) }}"
                                                class="form-control profile-control @error('middlename') is-invalid @enderror"
                                                placeholder="Middle Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="profile-label">Last Name *</label>
                                            <input type="text" name="lastname" value="{{ old('lastname', $resident->lastname) }}"
                                                class="form-control profile-control @error('lastname') is-invalid @enderror"
                                                required placeholder="Last Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="profile-label">Suffix</label>
                                            <select name="suffix" class="form-select profile-control @error('suffix') is-invalid @enderror">
                                                <option value="">None</option>
                                                @foreach(['Jr.','Sr.','II','III','IV','V'] as $sfx)
                                                    <option value="{{ $sfx }}" {{ old('suffix', $resident->suffix) === $sfx ? 'selected' : '' }}>{{ $sfx }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="profile-label">Date of Birth *</label>
                                            <input type="date" name="birthdate"
                                                value="{{ old('birthdate', $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('Y-m-d') : '') }}"
                                                class="form-control profile-control @error('birthdate') is-invalid @enderror"
                                                max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="profile-label">Contact Number *</label>
                                            <div class="input-group @error('contact') is-invalid @enderror">
                                                <span class="input-group-text" style="border:2px solid #e9ecef; border-right:none; background:#f8f9fa; border-radius:10px 0 0 10px; font-weight:600; color:#555; font-size:.88rem;">
                                                    +63
                                                </span>
                                                <input type="tel" name="contact" id="contactInput"
                                                    value="{{ old('contact', $resident->contact ? ltrim($resident->contact, '0') : '') }}"
                                                    class="form-control profile-control @error('contact') is-invalid @enderror"
                                                    style="border-left:none; border-radius:0 10px 10px 0;"
                                                    placeholder="9XXXXXXXXX" maxlength="10" inputmode="numeric"
                                                    pattern="[0-9]{10}" required>
                                            </div>
                                            @error('contact')<div class="invalid-feedback d-block" style="font-size:.78rem;">{{ $message }}</div>@enderror
                                            <small class="text-muted" style="font-size:.75rem;">e.g. 9994086683</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="profile-label">Complete Address *</label>
                                            <textarea name="address" rows="3"
                                                class="form-control profile-control @error('address') is-invalid @enderror"
                                                placeholder="House #, Street, Zone, Barangay Hulo, Malabon City" required>{{ old('address', $resident->address) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 mt-4">
                                        <button type="submit" class="btn-profile-save">
                                            <i class="fas fa-save me-2"></i>Save Changes
                                        </button>
                                        <button type="reset" class="btn-profile-cancel">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ── Tab 2: Account settings ── --}}
                        <div class="tab-content d-none" id="tab-account">
                            <div class="panel-header pb-3">
                                <h5><i class="fas fa-cog me-2 text-danger"></i>Account Settings</h5>
                                <p>Update your username, email, or change your password.</p>
                            </div>
                            <div class="tab-pane-body">

                                @if($errors->hasAny(['username','email']))
                                    <div class="profile-alert danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <div>
                                            @foreach(['username','email'] as $field)
                                                @foreach($errors->get($field) as $e)
                                                    <div>{{ $e }}</div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Username & Email --}}
                                <form method="POST" action="{{ route('profile.account') }}">
                                    @csrf
                                    <h6 class="fw-600 mb-3" style="color:#1c2630; font-size:.95rem;">Login Credentials</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="profile-label">Username *</label>
                                            <div class="input-group">
                                                <span class="input-group-text" style="border:2px solid #e9ecef; border-right:none; background:#f8f9fa; border-radius:10px 0 0 10px;">
                                                    <i class="fas fa-at text-muted"></i>
                                                </span>
                                                <input type="text" name="username" value="{{ old('username', $resident->username) }}"
                                                    class="form-control profile-control @error('username') is-invalid @enderror"
                                                    style="border-left:none; border-radius:0 10px 10px 0;"
                                                    placeholder="username" required autocomplete="username">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="profile-label">Email Address *</label>
                                            <div class="input-group">
                                                <span class="input-group-text" style="border:2px solid #e9ecef; border-right:none; background:#f8f9fa; border-radius:10px 0 0 10px;">
                                                    <i class="fas fa-envelope text-muted"></i>
                                                </span>
                                                <input type="email" name="email" value="{{ old('email', $resident->email) }}"
                                                    class="form-control profile-control @error('email') is-invalid @enderror"
                                                    style="border-left:none; border-radius:0 10px 10px 0;"
                                                    placeholder="email@example.com" required autocomplete="email">
                                            </div>
                                            @if(!$resident->email_verified_at)
                                                <small class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Email not verified.
                                                    <button type="submit" form="resendVerifyForm" class="btn btn-link btn-sm p-0" style="color:#c62828;font-size:.8rem;">Resend verification</button>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="submit" class="btn-profile-save">
                                        <i class="fas fa-save me-2"></i>Update Credentials
                                    </button>
                                </form>

                                {{-- Resend form must live OUTSIDE the credentials form --}}
                                @if(!$resident->email_verified_at)
                                    <form id="resendVerifyForm" method="POST" action="{{ route('verification.send') }}" class="d-none">
                                        @csrf
                                    </form>
                                @endif

                                <hr style="border-color:#f0f0f0; margin: 28px 0;">

                                {{-- Change Password --}}
                                @if($errors->hasAny(['current_password','new_password']))
                                    <div class="profile-alert danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <div>
                                            @foreach(['current_password','new_password'] as $field)
                                                @foreach($errors->get($field) as $e)
                                                    <div>{{ $e }}</div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                                    @csrf
                                    <h6 class="fw-600 mb-3" style="color:#1c2630; font-size:.95rem;">Change Password</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="profile-label">Current Password *</label>
                                            <div class="position-relative">
                                                <input type="password" name="current_password" id="currentPw"
                                                    class="form-control profile-control @error('current_password') is-invalid @enderror"
                                                    placeholder="Enter current password" autocomplete="current-password" style="padding-right:44px;">
                                                <button type="button" class="toggle-pw" data-target="currentPw">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="profile-label">New Password *</label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password" id="newPw"
                                                    class="form-control profile-control @error('new_password') is-invalid @enderror"
                                                    placeholder="New password (min. 8 chars)" autocomplete="new-password" style="padding-right:44px;">
                                                <button type="button" class="toggle-pw" data-target="newPw">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="pw-meter"><div class="pw-meter-fill" id="pwMeterFill"></div></div>
                                            <span class="pw-hint" id="pwStrengthHint">Password strength</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="profile-label">Confirm New Password *</label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password_confirmation" id="confirmPw"
                                                    class="form-control profile-control"
                                                    placeholder="Re-enter new password" autocomplete="new-password" style="padding-right:44px;">
                                                <button type="button" class="toggle-pw" data-target="confirmPw">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="pw-hint" id="pwMatchHint"></span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn-profile-save">
                                            <i class="fas fa-lock me-2"></i>Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ── Tab 3: Request history ── --}}
                        <div class="tab-content d-none" id="tab-requests">
                            <div class="panel-header pb-3">
                                <h5><i class="fas fa-file-alt me-2 text-danger"></i>Request History</h5>
                                <p>All document requests associated with your email address.</p>
                            </div>
                            <div class="tab-pane-body">
                                {{-- Summary counters --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-6 col-md-3">
                                        <div class="stat-counter-card">
                                            <div class="sc-icon"><i class="fas fa-certificate"></i></div>
                                            <div class="sc-num">{{ $clearanceCount }}</div>
                                            <div class="sc-lbl">Clearances</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="stat-counter-card">
                                            <div class="sc-icon"><i class="fas fa-house-user"></i></div>
                                            <div class="sc-num">{{ $residencyCount }}</div>
                                            <div class="sc-lbl">Residency</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="stat-counter-card">
                                            <div class="sc-icon"><i class="fas fa-hands-helping"></i></div>
                                            <div class="sc-num">{{ $indigencyCount }}</div>
                                            <div class="sc-lbl">Indigency</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="stat-counter-card">
                                            <div class="sc-icon"><i class="fas fa-clipboard-list"></i></div>
                                            <div class="sc-num">{{ $blotterCount }}</div>
                                            <div class="sc-lbl">Blotter</div>
                                        </div>
                                    </div>
                                </div>

                                @if($recentRequests->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table req-table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Reference #</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Date Submitted</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentRequests as $req)
                                                    <tr>
                                                        <td><code style="font-size:.8rem;">{{ $req->reference_number }}</code></td>
                                                        <td>
                                                            <span class="req-type-icon">
                                                                @switch($req->type)
                                                                    @case('Barangay Clearance') <i class="fas fa-certificate"></i> @break
                                                                    @case('Certificate of Residency') <i class="fas fa-house-user"></i> @break
                                                                    @case('Certificate of Indigency') <i class="fas fa-hands-helping"></i> @break
                                                                    @default <i class="fas fa-clipboard-list"></i>
                                                                @endswitch
                                                            </span>
                                                            {{ $req->type }}
                                                        </td>
                                                        <td>
                                                            <span class="status-badge {{ strtolower($req->status) }}">
                                                                {{ ucfirst($req->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($req->created_at)->format('M d, Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('track_request') }}?ref={{ $req->reference_number }}"
                                                               class="btn btn-sm" style="background:#fff0f0;color:#c62828;border-radius:8px;font-size:.78rem;font-weight:600;border:1px solid #fdd;">
                                                                <i class="fas fa-search me-1"></i>Track
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox" style="font-size:3rem; color:#e9ecef;"></i>
                                        <p class="mt-3 text-muted">No requests found. <a href="{{ route('services') }}" class="text-danger fw-600">Request a document</a></p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- ── Tab 4: Valid ID ── --}}
                        <div class="tab-content d-none" id="tab-valid-id">
                            <div class="panel-header pb-3">
                                <h5><i class="fas fa-id-card me-2 text-danger"></i>My Valid ID</h5>
                                <p>Your registered government-issued identification document.</p>
                            </div>
                            <div class="tab-pane-body">
                                <div class="row g-4">
                                    <div class="col-md-7">
                                        <div class="id-preview-card">
                                            @if($resident->valid_id)
                                                @php $ext = pathinfo($resident->valid_id, PATHINFO_EXTENSION); @endphp
                                                @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                                                    <img src="{{ asset($resident->valid_id) }}" alt="Valid ID">
                                                @else
                                                    <div class="id-placeholder">
                                                        <i class="fas fa-file-pdf" style="color:#c62828;"></i>
                                                        <p class="mt-2 mb-3 fw-600">PDF Document</p>
                                                        <a href="{{ asset($resident->valid_id) }}" target="_blank"
                                                           class="btn-profile-save text-decoration-none" style="display:inline-block;">
                                                            <i class="fas fa-eye me-1"></i>View PDF
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="id-placeholder">
                                                    <i class="fas fa-id-card"></i>
                                                    <p class="text-muted mt-2">No ID uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                        @if($resident->valid_id_verified)
                                            <div class="mt-2 text-center">
                                                <span class="verified-badge verified"><i class="fas fa-check-circle me-1"></i>ID Verified</span>
                                            </div>
                                        @else
                                            <div class="mt-2 text-center">
                                                <span class="verified-badge unverified"><i class="fas fa-clock me-1"></i>Pending Verification</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <h6 class="fw-600 mb-3" style="color:#1c2630;">Update Valid ID</h6>
                                        <p style="font-size:.85rem; color:#666;">
                                            Upload a new government-issued ID. Acceptable formats are JPG, PNG, and PDF.<br>
                                            <strong>Max size:</strong> 5MB<br>
                                            <strong>Note:</strong> Your ID will need to be re-verified by the barangay staff after update.
                                        </p>

                                        @if($errors->has('valid_id'))
                                            <div class="profile-alert danger mb-3">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('valid_id') }}
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('profile.id') }}" enctype="multipart/form-data" id="idUploadForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="profile-label">Choose New ID File</label>
                                                <input type="file" name="valid_id" id="validIdInput"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    class="form-control profile-control" required>
                                                <small class="text-muted" style="font-size:.78rem;">JPG, PNG, or PDF · Max 5MB</small>
                                            </div>
                                            <button type="submit" class="btn-profile-save">
                                                <i class="fas fa-upload me-2"></i>Upload New ID
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /profile-panel --}}
                </div>{{-- /col-lg-9 --}}

            </div>{{-- /row --}}
        </div>{{-- /container --}}
    </div>{{-- /profile-layout --}}

    {{-- ══════════════════ FAB + Chat ══════════════════ --}}
    <div class="chat-modal" id="chatModal">
        <div class="chat-modal-content">
            <div class="chat-modal-header">
                <div class="chat-modal-title">InfoHulo Assistant</div>
                <button class="chat-modal-close" id="closeChat"><i class="fas fa-times"></i></button>
            </div>
            <div class="chat-modal-body">
                <iframe id="chatIframe" src="https://app.chaindesk.ai/agents/cmjoevt2d04giiz0r9u2i0zcb/iframe" frameborder="0" allow="clipboard-write"></iframe>
            </div>
        </div>
    </div>

    <div class="fab-container">
        <div class="speed-dial" id="speedDial">
            <button class="fab-action" id="darkModeBtn" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
            <button class="fab-action" id="chatBtn" title="Chat with Assistant"><i class="fas fa-comment-dots"></i></button>
        </div>
        <button class="fab-main" id="fabMain"><i class="fas fa-gear"></i></button>
    </div>
    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

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

    {{-- ══════════════════ SCRIPTS ══════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/floating-actions.js') }}"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <style>
        .toggle-pw {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #aaa; cursor: pointer;
            padding: 4px; line-height:1;
        }
        .toggle-pw:hover { color: #c62828; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // ── Sidebar tab switching ──────────────────────────────────
        const pills = document.querySelectorAll('#sidebarNav .nav-pill');
        const panels = document.querySelectorAll('.tab-content');

        function activateTab(tabId) {
            panels.forEach(p => p.classList.add('d-none'));
            pills.forEach(p => p.classList.remove('active'));
            const panel = document.getElementById('tab-' + tabId);
            if (panel) panel.classList.remove('d-none');
            const pill = document.querySelector(`.nav-pill[data-tab="${tabId}"]`);
            if (pill) pill.classList.add('active');
        }

        pills.forEach(pill => {
            pill.addEventListener('click', () => activateTab(pill.dataset.tab));
        });

        // Auto-open correct tab on server-error redirect
        @if($errors->hasAny(['username','email','current_password','new_password']))
            activateTab('account');
        @elseif($errors->hasAny(['firstname','middlename','lastname','suffix','birthdate','contact','address']))
            activateTab('info');
        @elseif($errors->has('valid_id'))
            activateTab('valid-id');
        @elseif(session('open_tab'))
            activateTab('{{ session("open_tab") }}');
        @endif

        // ── Quick photo upload via sidebar camera button ──────────
        const quickInput = document.getElementById('quickPhotoInput');
        const quickForm  = document.getElementById('quickPhotoForm');
        if (quickInput && quickForm) {
            quickInput.addEventListener('change', () => {
                if (quickInput.files.length > 0) quickForm.submit();
            });
        }

        // ── Password visibility toggles ──────────────────────────
        document.querySelectorAll('.toggle-pw').forEach(btn => {
            btn.addEventListener('click', () => {
                const inp = document.getElementById(btn.dataset.target);
                if (!inp) return;
                const showing = inp.type === 'text';
                inp.type = showing ? 'password' : 'text';
                btn.querySelector('i').classList.toggle('fa-eye', showing);
                btn.querySelector('i').classList.toggle('fa-eye-slash', !showing);
            });
        });

        // ── Password strength + match ─────────────────────────────
        const newPwInput   = document.getElementById('newPw');
        const confirmPwInput = document.getElementById('confirmPw');
        const fill       = document.getElementById('pwMeterFill');
        const strengthHint = document.getElementById('pwStrengthHint');
        const matchHint    = document.getElementById('pwMatchHint');

        function evalStrength(pw) {
            let s = 0;
            if (pw.length >= 8) s++;
            if (/[A-Z]/.test(pw)) s++;
            if (/[a-z]/.test(pw)) s++;
            if (/\d/.test(pw)) s++;
            if (/[^A-Za-z0-9]/.test(pw)) s++;
            if (!pw) return { w: 0, label: '', color: '#e9ecef' };
            if (s <= 2) return { w: 30, label: 'Weak', color: '#ef4444' };
            if (s <= 3) return { w: 60, label: 'Fair', color: '#f59e0b' };
            if (s <= 4) return { w: 82, label: 'Good', color: '#3b82f6' };
            return { w: 100, label: 'Strong', color: '#22c55e' };
        }

        if (newPwInput && fill) {
            newPwInput.addEventListener('input', () => {
                const { w, label, color } = evalStrength(newPwInput.value);
                fill.style.width = w + '%';
                fill.style.background = color;
                strengthHint.textContent = label ? 'Strength: ' + label : 'Password strength';
                strengthHint.style.color = color !== '#e9ecef' ? color : '#888';
                checkMatch();
            });
        }
        if (confirmPwInput) {
            confirmPwInput.addEventListener('input', checkMatch);
        }
        function checkMatch() {
            if (!newPwInput || !confirmPwInput || !matchHint) return;
            if (!confirmPwInput.value) { matchHint.textContent = ''; return; }
            const ok = newPwInput.value === confirmPwInput.value;
            matchHint.textContent = ok ? '✓ Passwords match' : '✗ Passwords do not match';
            matchHint.style.color = ok ? '#22c55e' : '#ef4444';
        }

        // ── Contact: +63 prefix, digits only, 10 chars ─────────────
        const contactInput = document.getElementById('contactInput');
        if (contactInput) {
            // Strip leading 0 on load (in case stored as 09XXXXXXXXX)
            if (/^0/.test(contactInput.value)) {
                contactInput.value = contactInput.value.slice(1);
            }
            contactInput.addEventListener('input', () => {
                let v = contactInput.value.replace(/\D/g, '');
                // Remove leading 0 if typed
                if (v.startsWith('0')) v = v.slice(1);
                contactInput.value = v.slice(0, 10);
            });
            // Before submit: prepend 0 to store as 09XXXXXXXXX
            const infoForm = document.getElementById('infoForm');
            if (infoForm) {
                infoForm.addEventListener('submit', () => {
                    if (contactInput.value.length === 10) {
                        contactInput.value = '0' + contactInput.value;
                    }
                });
            }
        }

        // ── Username: no spaces ───────────────────────────────────
        const userInput = document.querySelector('input[name="username"]');
        if (userInput) {
            userInput.addEventListener('input', () => {
                userInput.value = userInput.value.replace(/\s/g,'');
            });
        }

        // ── Auto-dismiss flash alerts ────────────────────────────
        document.querySelectorAll('.profile-alert').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity .5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }, 5000);
        });
    });
    </script>
</body>
</html>
