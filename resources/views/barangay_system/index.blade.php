<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Home</title>
    
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

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('Images/homepage-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            padding-top: 80px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            color: rgba(255,255,255,0.9);
            animation: slideUp 1s ease;
        }

        .hero-actions {
            margin-bottom: 60px;
            animation: slideUp 1.2s ease;
        }

        .get-started-btn {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(198, 40, 40, 0.4);
        }

        .get-started-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(198, 40, 40, 0.6);
        }

        /* Quick Stats */
        .quick-stats {
            animation: slideUp 1.4s ease;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item i {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Quick Access Section */
        .quick-access {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 50px;
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

        .quick-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-decoration: none;
            color: #333;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-bottom: 25px;
        }

        .quick-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(198, 40, 40, 0.15);
            text-decoration: none;
            color: #333;
        }

        .quick-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #C62828;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .quick-card:hover::before {
            transform: scaleX(1);
        }

        .quick-icon {
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

        .quick-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #C62828;
        }

        .quick-card p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .quick-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: auto;
        }

        /* About Section */
        .about-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 100px 0;
        }

        .about-section .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        .text-content h2 {
            font-size: 2.8rem;
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

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
            color: #555;
        }

        .feature-item i {
            color: #C62828;
            font-size: 1.2rem;
        }

        .section-btn {
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .section-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(198, 40, 40, 0.3);
            color: white;
            text-decoration: none;
        }

        .section-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #C62828, #d32f2f);
            color: white;
            padding: 15px 35px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Services & Community Cards */
        .services-community {
            background: #ffffff;
            padding: 100px 0;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-bottom: 30px;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }

        .service-card,
        .community-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            border: 1px solid #e8e8e8;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .service-card:hover,
        .community-card:hover {
            box-shadow: 0 15px 40px rgba(198, 40, 40, 0.12);
            border-color: #C62828;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-icon {
            font-size: 2rem;
            color: #C62828;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.1), rgba(198, 40, 40, 0.05));
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .service-card h2,
        .community-card h2 {
            font-size: 1.8rem;
            color: #C62828;
            margin: 0;
            font-weight: 700;
        }

        .service-card > p,
        .community-card > p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        .service-list {
            list-style: none;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .service-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            font-size: 0.95rem;
        }

        .service-list li i {
            color: #4CAF50;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .community-highlights {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .highlight {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            font-size: 0.95rem;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border-left: 3px solid #C62828;
        }

        .highlight i {
            color: #C62828;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .highlight span {
            flex: 1;
        }

        /* Announcements & Events */
        .announcements-events {
            background: linear-gradient(135deg, #C62828 0%, #d32f2f 100%);
            padding: 100px 0;
            color: white;
        }

        .announcements-events .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .section-header h2 {
            color: #333;
            font-size: 2.8rem;
            margin: 0;
        }

        .view-all {
            color: #C62828;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            gap: 12px;
            color: #C62828;
            text-decoration: none;
        }

        .announcement-counter,
        .events-counter {
            background: #C62828;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .announcement-item {
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .announcement-item:hover {
            border-color: #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .date {
            color: #C62828;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .announcement-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .announcement-badge.important {
            background: #ffebee;
            color: #C62828;
        }

        .announcement-badge.new {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .announcement-badge.update {
            background: #e3f2fd;
            color: #1565c0;
        }

        .announcement-item h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 12px;
        }

        .announcement-item p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .read-more {
            color: #C62828;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .read-more:hover {
            gap: 12px;
            color: #C62828;
            text-decoration: none;
        }

        .event-item {
            display: flex;
            gap: 25px;
            align-items: flex-start;
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .event-item:hover {
            border-color: #C62828;
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }

        .event-date {
            background: #C62828;
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            min-width: 85px;
            flex-shrink: 0;
        }

        .event-date .month {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .event-date .day {
            display: block;
            font-size: 2rem;
            font-weight: bold;
            line-height: 1;
            margin-top: 5px;
        }

        .event-details h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .event-location,
        .event-time {
            font-size: 0.95rem;
            color: #666;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .event-status.ongoing {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .event-status.upcoming {
            background: #e3f2fd;
            color: #1565c0;
        }

        .navigation-arrows {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .dots {
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #ddd;
            border-radius: 50%;
            cursor: pointer;
        }

        .dot.active {
            background: #C62828;
        }

        .arrow-btn {
            background: #f8f9fa;
            color: #C62828;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arrow-btn:hover {
            background: #C62828;
            color: white;
            transform: scale(1.1);
        }

        /* Report & Contact Section */
        .report-contact {
            background: #f8f9fa;
            padding: 100px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-contact .container {
            background: white;
            border-radius: 25px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            max-width: 700px;
            width: 100%;
        }

        .report-card h2,
        .contact-card h2 {
            font-size: 2.2rem;
            color: #C62828;
            margin: 0;
            text-align: center;
        }

        .report-card p {
            margin: 0 0 20px 0;
            text-align: center;
        }

        .report-types {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
            justify-content: center;
        }

        .report-type {
            background: #f8f9fa;
            color: #555;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .report-type:hover {
            background: #C62828;
            color: white;
            border-color: #C62828;
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

        /* Footer Styles */
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .quick-card {
                margin-bottom: 20px;
            }
            
            .about-section .container,
            .services-community .container,
            .announcements-events .container,
            .report-contact .container {
                padding: 30px 20px;
            }
            
            .footer-bottom-container {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }
            
            .logo-subtitle {
                font-size: 1rem;
            }
            
            .logo-title {
                display: none;
            }
            
            .quick-stats .col-4 {
                margin-bottom: 20px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .section-btn {
                width: 100%;
                justify-content: center;
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
                        <a class="nav-link active" href="{{ route('barangay_system.index') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-link" href="{{ route('history') }}"><i class="fas fa-history"></i> History</a></li>
                            <li><a class="dropdown-link" href="#mission"><i class="fas fa-bullseye"></i> Mission/Vision</a></li>
                            <li><a class="dropdown-link" href="{{ route('map') }}"><i class="fas fa-map"></i> Barangay Map</a></li>
                            <li><a class="dropdown-link" href="#officials"><i class="fas fa-users"></i> Barangay Officials</a></li>
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
                            <li><a class="dropdown-link" href="announcement.html"><i class="fas fa-bullhorn"></i> Announcements</a></li>
                            <li><a class="dropdown-link" href="#events"><i class="fas fa-calendar-alt"></i> Events/Projects</a></li>
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
                        <a class="nav-link" href="#contact"><i class="fas fa-phone"></i> Contact</a>
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
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="mb-4">Barangay Hulo Online Services</h1>
                <p class="mb-5">Access barangay services anytime, anywhere. Fast, convenient, and secure.</p>
                <div class="hero-actions mb-5">
                    <a href="#main-content"><button class="get-started-btn"><i class="fas fa-rocket"></i> Get Started</button></a>
                </div>
                
                <!-- Quick Stats -->
                <div class="row quick-stats justify-content-center text-center">
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-file-alt mb-3"></i>
                        <span class="stat-number">1,234</span>
                        <span class="stat-label">Services Rendered</span>
                    </div>
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-users mb-3"></i>
                        <span class="stat-number">12,000</span>
                        <span class="stat-label">Active Residents</span>
                    </div>
                    <div class="col-4 col-md-4 stat-item">
                        <i class="fas fa-check-circle mb-3"></i>
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfaction Rate</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Quick Access Cards -->
        <section class="quick-access">
            <div class="container">
                <h2 class="section-title">Quick Access</h2>
                <div class="row quick-access-grid">
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('clearance.req') }}" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3>Request Clearance</h3>
                            <p>Get your barangay clearance online</p>
                            <span class="quick-badge">Fast Process</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="#" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3>View Events</h3>
                            <p>Upcoming community activities</p>
                            <span class="quick-badge">New</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="#" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h3>Announcements</h3>
                            <p>Latest barangay updates</p>
                            <span class="quick-badge">Latest</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="#" class="quick-card">
                            <div class="quick-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>Report Issue</h3>
                            <p>Submit concerns online</p>
                            <span class="quick-badge">24/7</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="text-content">
                            <h2>About Barangay Hulo</h2>
                            <p>Learn more about the history and culture of Barangay Hulo, including our mission and vision as a community. This section also introduces our barangay officials and their roles, so residents know who to approach for assistance.</p>
                            <div class="feature-list">
                                <div class="feature-item">
                                    <i class="fas fa-history"></i>
                                    <span>Rich History Since 1901</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-award"></i>
                                    <span>National Award Winning</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-user-friends"></i>
                                    <span>12 Dedicated Officials</span>
                                </div>
                            </div>
                            <a href="history.html" class="section-btn"><i class="fas fa-book-open"></i> Learn more about Barangay Hulo</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image-content position-relative">
                            <img src="Images/about1.jpg" alt="Barangay Hall" class="section-image img-fluid" loading="lazy">
                            <div class="image-overlay">
                                <p>Visit our barangay hall for personalized assistance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services & Community Section -->
        <section class="services-community" id="services">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="service-card card-hover">
                            <div class="card-header">
                                <i class="fas fa-concierge-bell card-icon"></i>
                                <h2>Services</h2>
                            </div>
                            <p>Access a wide range of services without the need to personally visit the barangay hall. Residents can request official documents such as Barangay Clearance, Certificates (Residency, Indigency), and Business Permits.</p>
                            <ul class="service-list">
                                <li><i class="fas fa-check-circle"></i> Online document requests</li>
                                <li><i class="fas fa-check-circle"></i> Real-time status tracking</li>
                                <li><i class="fas fa-check-circle"></i> Digital payment options</li>
                                <li><i class="fas fa-check-circle"></i> Appointment scheduling</li>
                            </ul>
                            <a href="{{ route('barangay_system.services') }}"><button class="section-btn w-100"><i class="fas fa-file-download"></i> Request Services</button></a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="community-card card-hover">
                            <div class="card-header">
                                <i class="fas fa-users card-icon"></i>
                                <h2>Community</h2>
                            </div>
                            <p>Stay informed about what's happening in Barangay Hulo. This section features barangay announcements, important ordinances and resolutions, and updates on ongoing and upcoming projects.</p>
                            <div class="community-highlights">
                                <div class="highlight">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>Upcoming: Community Clean-up on July 20</span>
                                </div>
                                <div class="highlight">
                                    <i class="fas fa-chart-line"></i>
                                    <span>3 Ongoing Projects</span>
                                </div>
                            </div>
                            <button class="section-btn w-100"><i class="fas fa-bell"></i> Stay Updated</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements & Events Section -->
        <section class="announcements-events">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="section-header">Latest Updates</h2>
                    <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="announcements-card card-hover">
                            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                                <h2 class="m-0"><i class="fas fa-bullhorn"></i> Announcements</h2>
                                <div class="announcement-counter">3 New</div>
                            </div>
                            <div class="announcement-list">
                                <div class="announcement-item">
                                    <div class="announcement-header">
                                        <div class="date">July 09, 2025</div>
                                        <span class="announcement-badge important">Important</span>
                                    </div>
                                    <h3>Distribution of Ayuda</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                </div>
                                <div class="announcement-item">
                                    <div class="announcement-header">
                                        <div class="date">July 08, 2025</div>
                                        <span class="announcement-badge new">New</span>
                                    </div>
                                    <h3>Health Check-up Schedule</h3>
                                    <p>Free medical check-up for senior citizens and PWDs at the barangay health center.</p>
                                    <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="events-card card-hover">
                            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                                <h2 class="m-0"><i class="fas fa-calendar-alt"></i> Upcoming Events</h2>
                                <div class="events-counter">5 Events</div>
                            </div>
                            <div class="events-list">
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="month">July</span>
                                        <span class="day">09</span>
                                    </div>
                                    <div class="event-details">
                                        <h3>Distribution of Ayuda</h3>
                                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> Hulong Duhat Barangay Hall</p>
                                        <p class="event-time"><i class="fas fa-clock"></i> 10:30 am - 6:00pm</p>
                                        <span class="event-status ongoing">Ongoing</span>
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="month">July</span>
                                        <span class="day">15</span>
                                    </div>
                                    <div class="event-details">
                                        <h3>Community General Assembly</h3>
                                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> Barangay Covered Court</p>
                                        <p class="event-time"><i class="fas fa-clock"></i> 9:00 am - 12:00pm</p>
                                        <span class="event-status upcoming">Upcoming</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Report & Contact Section -->
        <section class="report-contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="report-card card-hover">
                            <div class="card-header text-center mb-4">
                                <i class="fas fa-exclamation-circle card-icon mb-3"></i>
                                <h2>Report a Concern</h2>
                            </div>
                            <p class="text-center">A safe and secure way for residents to submit complaints, incidents, or community concerns. Whether it's about safety, public order, or local issues.</p>
                            <div class="report-types">
                                <span class="report-type">Noise Complaint</span>
                                <span class="report-type">Street Lights</span>
                                <span class="report-type">Garbage Issue</span>
                                <span class="report-type">Safety Concern</span>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('barangay_system.incident') }}"><button class="section-btn"><i class="fas fa-paper-plane"></i> Report Now</button></a>
                            </div>
                        </div>
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
                    <a href="user_login.html" class="admin-login">
                        <i class="fas fa-sign-in-alt"></i> Staff Login
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Homepage Script - Organized and Optimized
        document.addEventListener('DOMContentLoaded', function() {
            // ===================================
            // SCROLL EFFECTS
            // ===================================
            
            // Navbar scroll effect and back to top button
            initScrollEffects();
            
            // Back to top button click
            initBackToTop();
            
            // ===================================
            // CAROUSEL FUNCTIONALITY
            // ===================================
            
            // Announcement and events carousel
            initCarousels();
            
            // ===================================
            // CARD ANIMATIONS
            // ===================================
            
            // Card hover effects
            initCardEffects();
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

        function initCarousels() {
            // Announcement carousel dots
            const announcementDots = document.querySelectorAll('.announcements-card .dot');
            
            announcementDots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    announcementDots.forEach(d => d.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Event carousel dots
            const eventDots = document.querySelectorAll('.events-card .dot');
            
            eventDots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    eventDots.forEach(d => d.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }

        function initCardEffects() {
            const cards = document.querySelectorAll('.card-hover');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
    </script>
</body>
</html>