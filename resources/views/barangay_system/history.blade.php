<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - History</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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

        /* Accessibility */
        .skip-to-main {
            position: absolute;
            top: -40px;
            left: 0;
            background: #C62828;
            color: white;
            padding: 8px;
            text-decoration: none;
            z-index: 9999;
        }

        .skip-to-main:focus {
            top: 0;
        }

        /* Custom CSS for maintaining your design */
        /* =========================================== */
        /* NAVBAR STYLES - FIXED HORIZONTAL LAYOUT */
        /* =========================================== */

        /* =============== MAIN NAVBAR =============== */
        .navbar {
            background: linear-gradient(135deg, #C62828, #7a2323);
            box-shadow: 0 4px 20px rgba(198, 40, 40, 0.2);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(198, 40, 40, 0.98);
            backdrop-filter: blur(10px);
        }

        /* =============== NAVBAR CONTAINER =============== */
        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0 15px;
        }

        /* =============== LOGO SECTION =============== */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .nav-logo .logo {
            width: 45px;
            height: 45px;
            background: #fff url('Images/logo.jpg') center/cover no-repeat;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logo-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.9);
            letter-spacing: 0.5px;
        }

        .logo-subtitle {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            white-space: nowrap;
        }

        /* =============== NAVIGATION LINKS =============== */
        .nav-link {
            color: white !important;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            padding: 8px 12px !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: white;
        }

        /* =============== DROPDOWN MENU =============== */
        .dropdown-menu {
            background: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 8px;
            border: 1px solid #eee;
            min-width: 200px;
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .dropdown-link:hover {
            background: #f8f9fa;
            color: #C62828;
            padding-left: 25px;
        }

        .login-btn {
            background: white;
            color: #C62828 !important;
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: #C62828;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(198, 40, 40, 0.3);
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: #d32f2f;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.4);
        }

        /* =========================================== */
        /* HISTORY PAGE STYLES - PROFESSIONAL & CLEAN */
        /* =========================================== */

        /* Fix Horizontal Scroll */
        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
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
            margin-top: 80px;
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

        /* Main Content */
        .history-main {
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 15px;
            font-weight: 700;
            position: relative;
            padding-bottom: 20px;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #C62828;
            border-radius: 2px;
        }

        .section-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 20px;
        }

        /* Introduction Section */
        .introduction-section {
            padding: 100px 0;
            background: #fff;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .text-content h2 {
            font-size: 2.2rem;
            color: #C62828;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .text-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }

        .key-facts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .fact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .fact-item:hover {
            background: #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .fact-item i {
            font-size: 1.5rem;
            color: #C62828;
        }

        .fact-item h4 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .fact-item p {
            font-size: 1.1rem;
            color: #333;
            font-weight: 700;
            margin: 0;
        }

        .image-content {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .section-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }

        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px;
            font-size: 0.9rem;
            text-align: center;
        }

        /* Timeline Section */
        .timeline-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #C62828;
            transform: translateX(-50%);
            opacity: 0.3;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 50px;
            position: relative;
        }

        .timeline-item:nth-child(odd) {
            flex-direction: row;
        }

        .timeline-item:nth-child(even) {
            flex-direction: row-reverse;
        }

        .timeline-year {
            background: #C62828;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1.2rem;
            font-weight: 700;
            z-index: 2;
            min-width: 100px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .timeline-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 45%;
            margin: 0 30px;
            position: relative;
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .timeline-icon {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .timeline-icon i {
            font-size: 1.3rem;
            color: #C62828;
        }

        .timeline-content h3 {
            color: #C62828;
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .timeline-content p {
            color: #666;
            line-height: 1.7;
            margin: 0;
            font-size: 1rem;
        }

        /* Heritage Section */
        .heritage-section {
            padding: 100px 0;
            background: white;
        }

        .heritage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .heritage-card {
            text-align: center;
            padding: 40px 30px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .heritage-card:hover {
            background: white;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transform: translateY(-10px);
        }

        .heritage-icon {
            width: 70px;
            height: 70px;
            background: #C62828;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .heritage-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .heritage-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .heritage-card p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            font-size: 1rem;
        }

        /* Landmarks Section */
        .landmarks-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .landmarks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .landmark-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .landmark-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .landmark-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .landmark-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }

        .landmark-content h3 {
            color: #C62828;
            font-size: 1.3rem;
            margin: 0;
            font-weight: 600;
        }

        .landmark-content p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            font-size: 1rem;
            flex: 1;
        }

        .landmark-year {
            display: block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            margin: 0 auto;
            width: fit-content;
        }

        /* Stats Section */
        .stats-section {
            padding: 100px 0;
            background: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            text-align: center;
            padding: 40px 30px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .stat-card:hover {
            background: white;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #C62828;
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
            font-weight: 600;
            flex: 1;
        }

        .stat-trend {
            font-size: 0.9rem;
            color: #4CAF50;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .stat-trend i {
            font-size: 0.8rem;
        }

        /* =========================================== */
        /* FOOTER STYLES */
        /* =========================================== */
        footer {
            background: linear-gradient(135deg, #C62828, #7a2323);
            color: #fff;
            padding: 40px 0 0;
            position: relative;
        }

        .footer-container {
            display: grid;
            gap: 25px;
            padding: 0 25px 30px;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-section h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0 0 12px;
            color: #fff;
            position: relative;
            padding-bottom: 8px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 35px;
            height: 2px;
            background: rgba(255,255,255,0.8);
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 5px;
        }

        .logo-circle {
            width: 45px;
            height: 45px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-circle i {
            font-size: 1.4rem;
            color: #C62828;
        }

        .logo-text h3 {
            font-size: 1.3rem;
            margin: 0 0 2px;
            padding: 0;
        }

        .logo-text h3::after {
            display: none;
        }

        .tagline {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }

        .contact-info-simple {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 10px 0;
        }

        .contact-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .contact-row i {
            color: rgba(255,255,255,0.9);
            width: 16px;
            text-align: center;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .contact-row span {
            color: rgba(255,255,255,0.85);
        }

        .contact-row a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-row a:hover {
            color: white;
            text-decoration: none;
        }

        .social-icons {
            display: flex;
            gap: 10px;
        }

        .social-icons a {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .emergency-contacts-simple {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .emergency-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .emergency-item:hover {
            background: rgba(255,255,255,0.08);
        }

        .emergency-item.critical {
            background: rgba(244, 67, 54, 0.1);
        }

        .emergency-item i {
            font-size: 1.2rem;
            color: white;
            width: 24px;
        }

        .emergency-details {
            flex: 1;
        }

        .emergency-label {
            display: block;
            font-size: 0.9rem;
            color: #ffffff;
            margin-bottom: 3px;
            font-weight: 500;
        }

        .emergency-number {
            color: #ffffff !important;
            font-size: 17.6px;
            font-weight: 700;
            text-decoration: none;
            display: block;
            margin-bottom: 3px;
        }

        .emergency-item.critical .emergency-number {
            color: #ffffff !important;
        }

        .emergency-details small {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 0;
            background: rgba(0, 0, 0, 0.1);
        }

        .footer-bottom-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .copyright-info p {
            color: rgba(255,255,255,0.8);
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .footer-bottom-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .admin-login {
            display: flex;
            align-items: center;
            gap: 6px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            padding: 8px 15px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .admin-login:hover {
            background: rgba(255,255,255,0.25);
            color: white;
            text-decoration: none;
        }

        .footer-links-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            padding: 8px 0;
        }

        .footer-link:hover {
            color: white;
            transform: translateX(5px);
            text-decoration: none;
        }

        .footer-link i {
            font-size: 0.9rem;
            color: #FFCDD2;
            width: 16px;
        }

        /* =========================================== */
        /* RESPONSIVE DESIGN */
        /* =========================================== */

        /* Tablet and Mobile Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                gap: 40px;
            }
            
            .timeline::before {
                left: 30px;
            }
            
            .timeline-item {
                flex-direction: row !important;
                padding-left: 60px;
            }
            
            .timeline-year {
                position: absolute;
                left: 0;
                transform: translateX(-50%);
            }
            
            .timeline-content {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 992px) {
            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .history-hero h1 {
                font-size: 2.2rem;
            }
            
            .history-hero {
                padding: 120px 0 60px;
            }
            
            .section-header h2 {
                font-size: 2rem;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .key-facts {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .heritage-grid,
            .landmarks-grid,
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .timeline-content {
                padding: 25px;
            }
            
            .footer-container {
                grid-template-columns: 1fr;
            }
            
            .logo-subtitle {
                font-size: 1rem;
            }
            
            .logo-title {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .history-hero h1 {
                font-size: 1.8rem;
            }
            
            .history-hero {
                padding: 120px 0 60px;
            }
            
            .timeline-year {
                font-size: 1rem;
                padding: 10px 20px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .footer-bottom-container {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Small Desktop - 1366x768 */
        @media (max-width: 1366px) {
            .container {
                max-width: 1280px;
                padding: 0 25px;
            }
            
            .history-hero h1 {
                font-size: 2.8rem;
            }
            
            .timeline-content {
                padding: 25px;
            }
            
            .timeline-year {
                font-size: 1.2rem;
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
                        <a class="nav-link" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link active" href="history.html"><i class="fas fa-history"></i> History</a></li>
                            <li><a class="dropdown-link" href="{{ route('barangay_system.index') }}#mission"><i class="fas fa-bullseye"></i> Mission/Vision</a></li>
                            <li><a class="dropdown-link" href="map.html"><i class="fas fa-map"></i> Barangay Map</a></li>
                            <li><a class="dropdown-link" href="{{ route('barangay_system.index') }}#officials"><i class="fas fa-users"></i> Barangay Officials</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-concierge-bell"></i> Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('clearance.req') }}"><i class="fas fa-certificate"></i> Barangay Clearance</a></li>
                            <li><a class="dropdown-link" href="certificate_residency.html"><i class="fas fa-house-user"></i> Certificate of Residency</a></li>
                            <li><a class="dropdown-link" href="{{ route('indigency.req') }}"><i class="fas fa-hands-helping"></i> Certificate of Indigency</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Community
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('barangay_system.announcement') }}"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-link" href="{{ route('barangay_system.index') }}#events"><i class="fas fa-calendar-alt"></i> Events/Projects</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exclamation-circle"></i> Report
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('barangay_system.incident') }}"><i class="fas fa-clipboard-list"></i> Blotter Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('barangay_system.index') }}#contact"><i class="fas fa-phone"></i> Contact</a>
                    </li>
                    
                    <!-- Desktop Actions -->
                    <li class="nav-item d-none d-lg-block">
                        <a href="{{ route('login.res') }}" class="login-btn ms-2"><i class="fas fa-sign-in-alt"></i> Log In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="history-hero">
        <div class="history-hero-content">
            <div class="breadcrumb">
                <a href="{{ route('barangay_system.index') }}">Home</a>
                <span>/</span>
                <span>History</span>
            </div>
            <h1>History of Barangay Hulong Duhat</h1>
            <p>Discover our journey from a small settlement to a thriving community</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="history-main" id="main-content">
        <!-- Introduction Section -->
        <section class="introduction-section">
            <div class="container">
                <div class="content-grid">
                    <div class="text-content">
                        <h2>Our Beginnings</h2>
                        <p>Barangay Hulong Duhat, named after the abundant Duhat trees along its riverbanks, has a rich history dating back to the early 20th century. What began as a small agricultural settlement has grown into one of Malabon's most vibrant communities.</p>
                        <p>Through decades of development and community effort, we have preserved our heritage while embracing progress and modernization.</p>
                        
                        <div class="key-facts">
                            <div class="fact-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <h4>Year Established</h4>
                                    <p>1950</p>
                                </div>
                            </div>
                            <div class="fact-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <h4>Population</h4>
                                    <p>12,850 Residents</p>
                                </div>
                            </div>
                            <div class="fact-item">
                                <i class="fas fa-map-marked-alt"></i>
                                <div>
                                    <h4>Area</h4>
                                    <p>15 Puroks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image-content">
                        <img src="Images/history.jpg" alt="Early Settlement" class="section-image" loading="lazy">
                        <div class="image-caption">Early farming community in the 1950s</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Timeline Section -->
        <section class="timeline-section">
            <div class="container">
                <div class="section-header">
                    <h2>Historical Timeline</h2>
                </div>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">1950</div>
                        <div class="timeline-content">
                            <div class="timeline-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <h3>Early Settlement</h3>
                            <p>Formation of the first farming community along the riverbanks. Families established the first residential areas.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">1975</div>
                        <div class="timeline-content">
                            <div class="timeline-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <h3>Official Recognition</h3>
                            <p>Barangay Hulong Duhat was officially recognized by the City of Malabon. First barangay hall was established.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">1990</div>
                        <div class="timeline-content">
                            <div class="timeline-icon">
                                <i class="fas fa-road"></i>
                            </div>
                            <h3>Infrastructure Development</h3>
                            <p>Major road networks and public facilities were constructed. Population growth accelerated.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2010</div>
                        <div class="timeline-content">
                            <div class="timeline-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>Modernization</h3>
                            <p>Improved public services, healthcare facilities, and community programs were implemented.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2023</div>
                        <div class="timeline-content">
                            <div class="timeline-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <h3>Digital Transformation</h3>
                            <p>Launch of online services and digital platforms for better community engagement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cultural Heritage Section -->
        <section class="heritage-section">
            <div class="container">
                <div class="section-header">
                    <h2>Cultural Heritage</h2>
                </div>
                
                <div class="heritage-grid">
                    <div class="heritage-card">
                        <div class="heritage-icon">
                            <i class="fas fa-glass-cheers"></i>
                        </div>
                        <h3>Annual Fiesta</h3>
                        <p>Celebrated every May 15th in honor of San Isidro Labrador, featuring community events and cultural activities.</p>
                    </div>
                    
                    <div class="heritage-card">
                        <div class="heritage-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Local Cuisine</h3>
                        <p>Known for traditional Filipino dishes and local specialties unique to our riverside community.</p>
                    </div>
                    
                    <div class="heritage-card">
                        <div class="heritage-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>Community Values</h3>
                        <p>Strong emphasis on bayanihan spirit, mutual assistance, and community cooperation.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Historical Landmarks Section -->
        <section class="landmarks-section">
            <div class="container">
                <div class="section-header">
                    <h2>Historical Landmarks</h2>
                </div>
                
                <div class="landmarks-grid">
                    <div class="landmark-card">
                        <img src="Images/chapel.webp" alt="San Lorenzo Ruiz Chapel" loading="lazy">
                        <div class="landmark-content">
                            <h3>Hulong Duhat Chapel</h3>
                            <p>The community chapel established in 1965, serving as the spiritual heart of our barangay. It has been the venue for masses, weddings, baptisms, and community gatherings for generations.</p>
                            <div class="landmark-year">Est. 1965</div>
                        </div>
                    </div>
                    
                    <div class="landmark-card">
                        <img src="Images/barangay.jfif" alt="First Barangay Hall" loading="lazy">
                        <div class="landmark-content">
                            <h3>First Barangay Hall</h3>
                            <p>The original administrative building constructed in 1978, now preserved as a historical site.</p>
                            <div class="landmark-year">Est. 1978</div>
                        </div>
                    </div>
                    
                    <div class="landmark-card">
                        <img src="Images/plaza.avif" alt="Community Plaza" loading="lazy">
                        <div class="landmark-content">
                            <h3>Hulong Duhat Plaza</h3>
                            <p>Central gathering place for community events, celebrations, and public assemblies.</p>
                            <div class="landmark-year">Est. 1985</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Growth Statistics Section -->
        <section class="stats-section">
            <div class="container">
                <div class="section-header">
                    <h2>Community Growth</h2>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">12,850</div>
                        <div class="stat-label">Total Residents</div>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> 15% since 2015</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">1,287</div>
                        <div class="stat-label">Households</div>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> 12% since 2015</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">85%</div>
                        <div class="stat-label">Infrastructure Development</div>
                        <div class="stat-trend"><i class="fas fa-arrow-up"></i> 25% since 2000</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Active Community Programs</div>
                        <div class="stat-trend"><i class="fas fa-plus"></i> 5 new since 2020</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
                            <a href="announcement.html" class="footer-link">
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
                            <a href="{{ route('clearance.req')}}" class="footer-link">
                                <i class="fas fa-certificate"></i> Barangay Clearance
                            </a>
                            <a href="certificate_residency.html" class="footer-link">
                                <i class="fas fa-house-user"></i> Certificate of Residency
                            </a>
                            <a href="{{ route('indigency.req')}}" class="footer-link">
                                <i class="fas fa-hands-helping"></i> Certificate of Indigency
                            </a>
                            <a href="{{ route('barangay_system.incident') }}" class="footer-link">
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
            <div class="container footer-bottom-container">
                <div class="copyright-info">
                    <p>&copy; 2025 Barangay Hulo, Malabon City. All rights reserved.</p>
                </div>
                
                <div class="footer-bottom-actions">
                    <a href="{{ route('login.res') }}" class="admin-login">
                        <i class="fas fa-sign-in-alt"></i> Staff Login
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // History Page JavaScript - Organized and Optimized
        document.addEventListener('DOMContentLoaded', function() {
            // ===================================
            // SCROLL EFFECTS
            // ===================================
            
            // Navbar scroll effect and back to top button
            initScrollEffects();
            
            // Back to top button click
            initBackToTop();
            
            // ===================================
            // ANIMATIONS
            // ===================================
            
            // Timeline item hover effects
            initTimelineEffects();
            
            // Scroll animations
            initScrollAnimations();
            
            // ===================================
            // ACCESSIBILITY
            // ===================================
            
            initAccessibility();
        });

        // ===================================
        // FUNCTION DEFINITIONS
        // ===================================

        function initScrollEffects() {
            const navbar = document.querySelector('.navbar');
            const backToTopBtn = document.querySelector('.back-to-top');
            
            window.addEventListener('scroll', function() {
                if (navbar) {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                }
                
                if (backToTopBtn) {
                    if (window.scrollY > 300) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                }
            });
        }

        function initBackToTop() {
            const backToTopBtn = document.querySelector('.back-to-top');
            
            if (backToTopBtn) {
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        }

        function initTimelineEffects() {
            // Add hover effects to timeline items
            const timelineItems = document.querySelectorAll('.timeline-item');
            
            timelineItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const year = this.querySelector('.timeline-year');
                    if (year) {
                        year.style.transform = 'scale(1.1)';
                        year.style.boxShadow = '0 8px 20px rgba(198, 40, 40, 0.4)';
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    const year = this.querySelector('.timeline-year');
                    if (year) {
                        year.style.transform = 'scale(1)';
                        year.style.boxShadow = '0 5px 15px rgba(198, 40, 40, 0.3)';
                    }
                });
            });
        }

        function initScrollAnimations() {
            // Add animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);
            
            // Observe cards
            document.querySelectorAll('.timeline-content, .heritage-card, .landmark-card, .stat-card, .fact-item').forEach(card => {
                observer.observe(card);
            });
            
            // Add CSS for animations
            const style = document.createElement('style');
            style.textContent = `
                .timeline-content,
                .heritage-card,
                .landmark-card,
                .stat-card,
                .fact-item {
                    opacity: 0;
                    transform: translateY(20px);
                    transition: opacity 0.6s ease, transform 0.6s ease;
                }
                
                .timeline-content.animate-in,
                .heritage-card.animate-in,
                .landmark-card.animate-in,
                .stat-card.animate-in,
                .fact-item.animate-in {
                    opacity: 1;
                    transform: translateY(0);
                }
                
                .timeline-content {
                    transition-delay: 0.1s;
                }
                
                .heritage-card:nth-child(2) {
                    transition-delay: 0.2s;
                }
                
                .heritage-card:nth-child(3) {
                    transition-delay: 0.3s;
                }
                
                .landmark-card:nth-child(2) {
                    transition-delay: 0.2s;
                }
                
                .landmark-card:nth-child(3) {
                    transition-delay: 0.3s;
                }
                
                .stat-card:nth-child(2) {
                    transition-delay: 0.1s;
                }
                
                .stat-card:nth-child(3) {
                    transition-delay: 0.2s;
                }
                
                .stat-card:nth-child(4) {
                    transition-delay: 0.3s;
                }
            `;
            document.head.appendChild(style);
            
            // Initialize animations
            setTimeout(() => {
                document.querySelectorAll('.timeline-content, .heritage-card, .landmark-card, .stat-card, .fact-item').forEach(card => {
                    if (card.getBoundingClientRect().top < window.innerHeight) {
                        card.classList.add('animate-in');
                    }
                });
            }, 100);
        }

        function initAccessibility() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    if (href !== '#' && href.startsWith('#') && document.querySelector(href)) {
                        e.preventDefault();
                        
                        const target = document.querySelector(href);
                        const headerOffset = 100;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Add keyboard navigation for cards
            document.querySelectorAll('.heritage-card, .landmark-card, .stat-card, .fact-item').forEach(card => {
                card.setAttribute('tabindex', '0');
                
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        }
    </script>
</body>
</html>