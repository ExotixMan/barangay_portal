<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulong Duhat - Clearance</title>
    
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

        /* Process Steps - FULL WIDTH FIX */
        .process-steps {
            padding: 80px 0;
            background: #f8f9fa;
            position: relative;
            width: 100%;
        }

        /* This centers the content */
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

        .process-steps .section-title {
            text-align: center;
            margin-bottom: 60px;
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

        /* Requirements Section */
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
            height: 100%;
            display: flex;
            flex-direction: column;
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
            flex-shrink: 0;
        }

        .card-header i {
            font-size: 2rem;
            color: #C62828;
        }

        .requirements-card h2,
        .info-card h2 {
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

        .requirement-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        .id-list {
            list-style: none;
            margin: 10px 0 0;
            padding: 0;
        }

        .id-list li {
            color: #666;
            font-size: 0.95rem;
            padding: 3px 0;
            position: relative;
            padding-left: 15px;
        }

        .id-list li::before {
            content: "•";
            color: #C62828;
            position: absolute;
            left: 0;
        }

        .info-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            
            justify-content: space-evenly;
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
            flex-shrink: 0;
        }

        .info-item h4 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-item p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        .emergency-notice {
            background: rgba(198, 40, 40, 0.1);
            border: 1px solid rgba(198, 40, 40, 0.2);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0; /* Prevents notice from stretching */
            margin-top: auto; /* Pushes to bottom */
        }

        .emergency-notice i {
            color: #C62828;
            font-size: 1.2rem;
        }

        .emergency-notice p {
            color: #C62828;
            font-weight: 500;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Application Form Section */
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

        /* Form Progress */
        .form-progress {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            padding: 40px 30px;
            margin-bottom: 40px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: #eee;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            background: white;
            border: 3px solid #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #999;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: #C62828;
            border-color: #C62828;
            color: white;
            transform: scale(1.1);
        }

        .step span {
            font-size: 0.9rem;
            color: #999;
            font-weight: 500;
            text-align: center;
        }

        .step.active span {
            color: #C62828;
            font-weight: 600;
        }

        .progress-bar {
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #C62828, #d32f2f);
            width: 33.33%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Form Steps */
        .clearance-form {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-step h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .form-step h3 i {
            color: #C62828;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #C62828;
            font-size: 1rem;
        }

        .upload-size {
            font-size: 0.8rem;
            color: #999;
            font-weight: 400;
            margin-left: auto;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 13px 16px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #333;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #C62828;
            background: white;
            box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.12);
        }

        .form-hint {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        /* Upload Area */
        .upload-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 30px;
        }

        .upload-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 12px;
            padding: 50px 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .upload-area:hover {
            border-color: #C62828;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.02), rgba(198, 40, 40, 0.05));
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.1);
        }

        .upload-area i {
            font-size: 2.5rem;
            color: #C62828;
            margin-bottom: 15px;
        }

        .upload-area p {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }

        .upload-area p span {
            color: #C62828;
            font-weight: 600;
            text-decoration: underline;
        }

        .upload-preview {
            margin-top: 10px;
            display: none;
        }

        .upload-preview.active {
            display: block;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #e8f5e9;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #c8e6c9;
        }

        .file-preview i {
            color: #2e7d32;
            font-size: 1.2rem;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }

        .file-size {
            font-size: 0.8rem;
            color: #666;
        }

        .remove-file {
            background: none;
            border: none;
            color: #C62828;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Review Section */
        .review-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }

        .review-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }

        .review-card h4 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .review-card h4 i {
            color: #C62828;
        }

        .review-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .review-item {
            padding: 8px 0;
        }

        .review-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .review-value {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
        }

        .payment-summary {
            width: 100%;
            grid-column: 1 / -1;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-item.total {
            font-weight: 700;
            font-size: 1.1rem;
            color: #C62828;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        .pickup-instructions {
            grid-column: 1 / -1;
            margin-top: 20px;
        }

        .pickup-info {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            margin-bottom: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 12px;
            border: 1px solid #eee;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .pickup-info:hover {
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.1);
            border-color: rgba(198, 40, 40, 0.2);
        }

        .pickup-info:last-child {
            margin-bottom: 0;
        }

        .pickup-info i {
            color: #C62828;
            font-size: 1.2rem;
            margin-top: 3px;
        }

        .pickup-info h5 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pickup-info p {
            color: #666;
            font-size: 0.95rem;
            margin: 0 0 10px 0;
        }

        .pickup-info ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pickup-info li {
            color: #666;
            font-size: 0.95rem;
            padding: 3px 0;
            position: relative;
            padding-left: 15px;
        }

        .pickup-info li::before {
            content: "•";
            color: #C62828;
            position: absolute;
            left: 0;
        }

        /* Benefits Section */
        .benefits-section {
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, rgba(198, 40, 40, 0.05), rgba(46, 125, 50, 0.05));
            border-radius: 15px;
            border: 1px solid rgba(198, 40, 40, 0.1);
        }

        .benefits-section h4 {
            font-size: 1.3rem;
            color: #C62828;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .benefit-item {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .benefit-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(198, 40, 40, 0.15);
            border-color: #C62828;
        }

        .benefit-item i {
            font-size: 2rem;
            color: #C62828;
            margin-bottom: 15px;
        }

        .benefit-item h5 {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .benefit-item p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Terms Section */
        .terms-section {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            padding: 30px;
            margin: 40px 0;
            border: 1px solid #eee;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            font-size: 0.95rem;
            color: #333;
            line-height: 1.4;
        }

        .terms-checkbox:last-child {
            margin-bottom: 0;
        }

        .terms-checkbox input {
            display: none;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            flex-shrink: 0;
            margin-top: 3px;
            position: relative;
            transition: all 0.3s ease;
        }

        .terms-checkbox input:checked + .checkmark {
            background: #C62828;
            border-color: #C62828;
        }

        .checkmark::after {
            content: '';
            position: absolute;
            display: none;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .terms-checkbox input:checked + .checkmark::after {
            display: block;
        }

        .terms-checkbox a {
            color: #C62828;
            text-decoration: none;
            font-weight: 600;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(0.5);
                opacity: 0.5;
            }
        }

        /* Application Status */
        .application-status {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-top: 30px;
            display: none;
        }

        .application-status.active {
            display: block;
        }

        .status-content i {
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .status-content h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .status-content p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .status-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        /* Small Desktop - 1366x768 */
        @media (max-width: 1366px) {
            .container {
                max-width: 1280px;
                padding: 0 25px;
            }
            
            .info-cards-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            
            .content-grid {
                gap: 30px;
            }
            
            .form-steps {
                gap: 30px;
            }
            
            .requirements-box {
                padding: 25px;
            }
        }

        @media (max-width: 1200px) {
            .steps-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .benefits-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .clearance-form {
                padding: 30px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .review-content {
                grid-template-columns: 1fr;
            }
            
            .progress-steps {
                flex-direction: column;
                gap: 30px;
                align-items: flex-start;
            }
            
            .progress-steps::before {
                display: none;
            }
            
            .step {
                flex-direction: row;
                gap: 15px;
                width: 100%;
            }
            
            .step-circle {
                flex-shrink: 0;
            }
        }

        @media (max-width: 768px) {
            .steps-container {
                grid-template-columns: 1fr;
            }
            
            .benefits-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .application-status {
                padding: 30px 20px;
            }
            
            .status-actions {
                flex-direction: column;
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
            
            .clearance-form {
                padding: 20px;
            }
            
            .form-progress {
                padding: 20px;
            }
            
            .form-step h3 {
                font-size: 1.3rem;
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-certificate bigicon"></i> Barangay Clearance</h1>
                <p>Request your official barangay clearance online. Fast, secure, and convenient process.</p>
                <div class="hero-stats">
                    <div class="stat">
                        <i class="fas fa-clock"></i>
                        <span>Processing Time: 1-3 Business Days</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-peso-sign"></i>
                        <span>Fee: ₱100.00</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-check-circle"></i>
                        <span>100% Digital Process</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Process Steps -->
        <section class="process-steps">
            <div class="process-steps-content">
                <h2 class="section-title">How to Get Your Clearance</h2>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3>Fill Out Form</h3>
                        <p>Complete the online application form with accurate information</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <h3>Upload Requirements</h3>
                        <p>Submit required documents and identification</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3>Pay Fees</h3>
                        <p>Pay the processing fee online or at the barangay hall</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon">
                            <i class="fas fa-file-download"></i>
                        </div>
                        <h3>Receive Clearance</h3>
                        <p>Get your barangay clearance via email or collect at barangay</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Requirements -->
        <section class="requirements-section">
            <div class="container">
                <div class="content-grid">
                    <div class="requirements-card">
                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i>
                            <h2>Requirements</h2>
                        </div>
                        <div class="requirements-list">
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Valid ID (Any of the following)</h4>
                                    <ul class="id-list">
                                        <li>Philippine Passport</li>
                                        <li>Driver's License</li>
                                        <li>UMID Card</li>
                                        <li>Postal ID</li>
                                        <li>Voter's ID</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Proof of Residency</h4>
                                    <p>Latest utility bill (electricity, water, or internet) or Barangay Certificate of
                                        Residency</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Recent 1x1 ID Picture</h4>
                                    <p>White background, taken within the last 3 months</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <i class="fas fa-check-circle"></i>
                                <div class="requirement-content">
                                    <h4>Purpose of Clearance</h4>
                                    <p>Specify the purpose (employment, business permit, scholarship, etc.)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="card-header">
                            <i class="fas fa-info-circle"></i>
                            <h2>Important Information</h2>
                        </div>
                        <div class="info-content">
                            <div class="info-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <h4>Processing Time</h4>
                                    <p>Regular processing: 1-3 business days<br>Express processing (₱200): Same day</p>
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
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h4>Need Help?</h4>
                                    <p>Contact us at (02) 123-4567 or visit the barangay hall for assistance</p>
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

        <!-- Application Form Section -->
        <section class="application-form-section" id="apply-form">
            <div class="container">
                <div class="form-header">
                    <h2><i class="fas fa-file-signature"></i> Ready to Apply?</h2>
                    <p>Click the button below to start your Barangay Clearance application</p>
                </div>

                <div class="apply-button-container" style="text-align: center; padding: 40px 20px;">
                    <a href="{{ route('clearance.form') }}" class="btn-apply-now" style="display: inline-block; padding: 20px 50px; background: #C62828; color: white; text-decoration: none; border-radius: 12px; font-size: 1.2rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(198, 40, 40, 0.3);">
                        <i class="fas fa-file-signature"></i> Apply for Clearance Now
                    </a>
                    <p style="margin-top: 20px; color: #666; font-size: 0.95rem;">
                        <i class="fas fa-clock"></i> Processing Time: 3-5 Business Days
                    </p>
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
                            <span>How do I pay for my barangay clearance?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Payment of ₱100.00 is done in cash at the Barangay Hall when you pick up your clearance.
                                No online payment is required. Just bring your reference number and valid ID.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Do I need to wait in line at the barangay hall?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>No! Since you already submitted your application online, you can go directly to the
                                clearance counter. Show your reference number and ID, pay the fee, and collect your
                                clearance. This skips the regular application line.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What happens after I submit my application online?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Our staff will process your application within 1-3 business days. Once processed, you can
                                visit the barangay hall anytime during office hours to pay and collect your clearance.
                                You'll receive an SMS notification when it's ready for pickup.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How long is the barangay clearance valid?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Barangay clearance is typically valid for 6 months from the date of issuance. However,
                                some institutions may require a clearance issued within the last 3 months.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What if I can't pick it up myself?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You can authorize someone to pick it up for you. They need to bring: 1) Your valid ID
                                (photocopy), 2) Their valid ID, 3) Authorization letter, and 4) Your reference number.
                            </p>
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