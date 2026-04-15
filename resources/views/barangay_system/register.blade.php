<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Barangay Hulo Portal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #c62828;
            --primary-light: #ff5f52;
            --primary-dark: #8e0000;
            --accent: #2e9e5b;
            --danger: #c23939;
            --text-dark: #1c2630;
            --text-soft: #5c6773;
            --text-light: #7e8894;
            --surface: #ffffff;
            --surface-soft: #f8f9fa;
            --border: #e5e9f0;
            --border-dark: #dde6ef;
            --focus: rgba(198, 40, 40, 0.2);
            --shadow: 0 12px 28px rgba(14, 38, 58, 0.12);
            --shadow-hover: 0 18px 32px rgba(14, 38, 58, 0.16);
            --radius: 16px;
            --radius-sm: 10px;
            --transition: 0.25s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(145deg, #fff5f5 0%, #ffe7e7 100%);
            min-height: 100vh;
            color: var(--text-dark);
            line-height: 1.5;
            padding: 2rem 1rem;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 300px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            clip-path: polygon(0 0, 100% 0, 100% 60%, 0 100%);
            z-index: 0;
        }

        /* Back Button */
        .back-button-container {
            position: relative;
            z-index: 2;
            max-width: 1000px;
            margin: 0 auto 20px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 40px;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all var(--transition);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .btn-back:hover {
            background: var(--primary);
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 8px 20px rgba(198, 40, 40, 0.3);
            border-color: var(--primary);
        }

        /* Page Header */
        .form-page-header {
            position: relative;
            z-index: 2;
            color: white;
            padding: 0 1rem;
            text-align: center;
            justify-content: center;
            justify-items: center;
        }

        .form-page-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-page-header h1 i {
            margin-right: 12px;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 50%;
            font-size: 1.8rem;
        }

        .form-page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 500px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Main Container */
        .main-container {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Form Container */
        .form-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            padding: 2.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Alerts */
        .alert {
            border-radius: var(--radius-sm);
            margin-bottom: 2rem;
            padding: 1rem 1.2rem;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: #dcf6e8;
            color: #165839;
            border-left: 4px solid #1e8f55;
        }

        .alert-danger {
            background: #f9dfdf;
            color: #8a2929;
            border-left: 4px solid var(--danger);
        }

        .alert ul {
            margin-left: 1.2rem;
            margin-bottom: 0;
        }

        .btn-close {
            filter: none;
            opacity: 0.8;
        }

        /* Progress Bar */
        .form-progress {
            margin-bottom: 2.5rem;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            position: relative;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
            text-align: center;
        }

        .step-circle {
            width: 48px;
            height: 48px;
            background: white;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-soft);
            margin-bottom: 8px;
            transition: all var(--transition);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .step span {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-soft);
        }

        .step.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 8px 16px rgba(198, 40, 40, 0.3);
            transform: scale(1.05);
        }

        .step.active span {
            color: var(--primary);
            font-weight: 600;
        }

        .progress-bar {
            height: 6px;
            background: var(--border);
            border-radius: 6px;
            position: relative;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .progress-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 6px;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }

        /* Form Steps */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-step h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--border);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
        }

        .form-step h3 i {
            color: var(--primary);
            margin-right: 10px;
            font-size: 1.6rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-group label i {
            color: var(--primary);
            margin-right: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1.2rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            transition: all var(--transition);
            background: white;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--focus);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-image: none;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(194, 57, 57, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-hint {
            display: block;
            margin-top: 0.4rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .form-hint.good {
            color: #1e8f55;
        }

        .form-hint.bad {
            color: var(--danger);
        }

        .error-message {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(194, 57, 57, 0.1);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
        }

        .error-message::before {
            content: "⚠️";
            font-size: 0.8rem;
        }

        /* Password Input */
        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper .form-control {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 8px;
            transition: all var(--transition);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
            background: var(--surface-soft);
        }

        /* Password Strength */
        .password-meter {
            height: 6px;
            background: var(--border);
            border-radius: 6px;
            overflow: hidden;
            margin: 0.5rem 0 0.25rem;
        }

        .password-meter-fill {
            height: 100%;
            width: 10%;
            background: #d16060;
            transition: all var(--transition);
        }

        .password-checklist {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .password-checklist li {
            color: var(--text-light);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-checklist li::before {
            content: "○";
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .password-checklist li.is-complete {
            color: #1e8f55;
        }

        .password-checklist li.is-complete::before {
            content: "✓";
            color: #1e8f55;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-input {
            position: relative;
            display: flex;
            align-items: center;
            border: 2px dashed var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface-soft);
            padding: 0.5rem;
            cursor: pointer;
            transition: all var(--transition);
        }

        .file-upload-input:hover {
            border-color: var(--primary);
            background: rgba(198, 40, 40, 0.05);
        }

        .file-upload-input input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-button {
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 500;
            margin-right: 1rem;
            pointer-events: none;
        }

        .file-upload-text {
            color: var(--text-soft);
            font-size: 0.9rem;
            flex: 1;
        }

        .file-upload-info {
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .file-upload-info i {
            color: var(--primary);
        }

        /* Benefits Section */
        .benefits-section {
            background: linear-gradient(145deg, var(--surface-soft) 0%, #ffffff 100%);
            padding: 1.8rem;
            border-radius: var(--radius);
            margin: 2rem 0;
            border: 1px solid var(--border);
        }

        .benefits-section h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
        }

        .benefits-section h4 i {
            color: var(--primary);
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .benefits-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .benefit-item {
            flex: 1;
            min-width: 150px;
            text-align: center;
            padding: 1.2rem;
            background: white;
            border-radius: var(--radius-sm);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all var(--transition);
        }

        .benefit-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .benefit-item i {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 0.8rem;
        }

        .benefit-item h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--text-dark);
        }

        .benefit-item p {
            font-size: 0.8rem;
            color: var(--text-soft);
            margin: 0;
        }

        /* Terms Section */
        .terms-section {
            margin: 1.5rem 0;
            padding: 1.2rem;
            background: var(--surface-soft);
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            position: relative;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 0;
        }

        .terms-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: relative;
            display: inline-block;
            width: 22px;
            height: 22px;
            background: white;
            border: 2px solid var(--border);
            border-radius: 6px;
            flex-shrink: 0;
            transition: all var(--transition);
        }

        .terms-checkbox:hover input~.checkmark {
            border-color: var(--primary);
        }

        .terms-checkbox input:checked~.checkmark {
            background: var(--primary);
            border-color: var(--primary);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .terms-checkbox input:checked~.checkmark:after {
            display: block;
        }

        .terms-checkbox a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .captcha-section {
            margin: 1.5rem 0;
            padding: 1.2rem;
            background: var(--surface-soft);
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .captcha-box {
            display: inline-block;
            max-width: 100%;
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2.5rem;
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
        }

        .btn-prev,
        .btn-next,
        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 40px;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition);
            min-width: 160px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-prev {
            background: white;
            border: 2px solid var(--border);
            color: var(--text-dark);
            text-decoration: none;
            margin-right: auto;
        }

        .btn-prev:hover {
            background: var(--surface-soft);
            border-color: var(--text-light);
            transform: translateX(-3px);
        }

        .btn-next,
        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: 2px solid transparent;
            margin-left: auto;
        }

        .btn-next:hover,
        .btn-submit:hover {
            transform: translateX(3px);
            box-shadow: 0 8px 20px rgba(198, 40, 40, 0.4);
        }

        .btn-submit {
            position: relative;
            min-width: 180px;
        }

        .btn-submit .spinner {
            display: none;
            gap: 5px;
        }

        .btn-submit.loading .btn-text {
            opacity: 0.8;
        }

        .btn-submit.loading .spinner {
            display: inline-flex;
        }

        .spinner-dot {
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .spinner-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .spinner-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }

        /* Shake Animation */
        .shake {
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-5px);
            }

            40% {
                transform: translateX(5px);
            }

            60% {
                transform: translateX(-3px);
            }

            80% {
                transform: translateX(3px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .form-page-header h1 {
                font-size: 1.8rem;
            }

            .form-page-header h1 i {
                font-size: 1.4rem;
                padding: 8px;
            }

            .step span {
                font-size: 0.85rem;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .password-checklist {
                grid-template-columns: 1fr;
            }

            .benefits-grid {
                flex-direction: column;
                gap: 1rem;
            }

            .benefit-item {
                min-width: 100%;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-prev,
            .btn-next,
            .btn-submit {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .form-page-header h1 {
                font-size: 1.5rem;
            }

            .form-page-header p {
                font-size: 0.95rem;
            }

            .step span {
                display: none;
            }

            .step-circle {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .form-step h3 {
                font-size: 1.2rem;
            }

            .form-step h3 i {
                font-size: 1.3rem;
            }

            .file-upload-input {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .file-upload-button {
                margin-right: 0;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
@include('chatbot.embed')

<body>
    <!-- Back Button -->
    <div class="back-button-container">
        <a href="{{ route('login') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> {{ __('messages.register_back_btn') }}
        </a>
    </div>

    <!-- Page Header -->
    <div class="form-page-header">
        <h1><i class="fas fa-user-plus"></i> {{ __('messages.register_page_title') }}</h1>
        <p>{{ __('messages.register_page_subtitle') }}</p>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Registration Form -->
        <div class="form-container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @php
                $recaptchaSiteKey = config('services.recaptcha.site_key');
            @endphp

            <form method="POST" action="{{ route('register.res') }}" id="registerForm" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>{{ __('messages.register_step1_title') }}</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>{{ __('messages.register_step2_title') }}</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <!-- Step 1: Personal Details -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user"></i> {{ __('messages.register_step1_heading') }}</h3>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_first_name_label') }} *
                                </label>
                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required
                                    placeholder="Enter your first name" class="form-control @error('firstname') is-invalid @enderror">
                                <div class="form-hint">As it appears on your ID</div>
                                @error('firstname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middlename">
                                    <i class="fas fa-user"></i> {{ __('messages.register_middle_name_label') }}
                                </label>
                                <input type="text" id="middlename" name="middlename" value="{{ old('middlename') }}"
                                    placeholder="Enter your middle name" class="form-control @error('middlename') is-invalid @enderror">
                                <div class="form-hint">Optional, but helps with verification</div>
                                @error('middlename')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">
                                    <i class="fas fa-user"></i> {{ __('messages.register_last_name_label') }} *
                                </label>
                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                                    placeholder="Enter your last name" class="form-control @error('lastname') is-invalid @enderror">
                                @error('lastname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="suffix">
                                    <i class="fas fa-user-tag"></i> {{ __('messages.register_suffix_label') }}
                                </label>
                                <select id="suffix" name="suffix" class="form-control @error('suffix') is-invalid @enderror">
                                    <option value="" {{ old('suffix') == '' ? 'selected' : '' }}>None</option>
                                    <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                                    <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                                </select>
                                <div class="form-hint">Optional, select if applicable</div>
                                @error('suffix')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthdate">
                                    <i class="fas fa-birthday-cake"></i> {{ __('messages.register_dob_label') }} *
                                </label>
                                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                    class="form-control @error('birthdate') is-invalid @enderror">
                                @error('birthdate')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact">
                                    <i class="fas fa-phone"></i> {{ __('messages.register_contact_label') }} *
                                </label>
                                <input type="tel" id="contact" name="contact" value="{{ old('contact') }}" required
                                    placeholder="09XX XXX XXXX" class="form-control @error('contact') is-invalid @enderror"
                                    pattern="[0-9]{11}" maxlength="11" inputmode="numeric">
                                <div class="form-hint">Use 11 digits only (e.g., 09123456789)</div>
                                @error('contact')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> {{ __('messages.register_email_label') }} *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="your.email@example.com" class="form-control @error('email') is-invalid @enderror"
                            autocomplete="email">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valid_id">
                            <i class="fas fa-id-card"></i> {{ __('messages.register_valid_id_label') }} *
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-upload-input" id="fileUploadBox">
                                <span class="file-upload-button">Choose File</span>
                                <span class="file-upload-text" id="fileUploadText">No file chosen</span>
                                <input type="file" id="valid_id" name="valid_id"
                                    accept=".jpg,.jpeg,.png,.pdf" required
                                    class="form-control @error('valid_id') is-invalid @enderror">
                            </div>
                            <div class="file-upload-info">
                                <i class="fas fa-info-circle"></i>
                                <span>Accepted formats: JPG, PNG, PDF (Max: 5MB)</span>
                            </div>
                        </div>
                        <div class="form-hint">Upload a clear photo or scan of your government-issued ID</div>
                        @error('valid_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-home"></i> {{ __('messages.register_address_label') }} *
                        </label>
                        <textarea id="address" name="address" required rows="3"
                            placeholder="House #, Street, Zone, Barangay Hulo, Malabon City"
                            class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        <div class="form-hint">Include specific landmarks for accurate verification. You may use #, commas, periods, and hyphens.</div>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('login') }}" class="btn-prev">
                            <i class="fas fa-times"></i> {{ __('messages.register_cancel_btn') }}
                        </a>
                        <button type="button" class="btn-next" data-next="step2" id="nextToStep2">
                            {{ __('messages.register_next_btn') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Account Setup -->
                <div class="form-step" id="step2">
                    <h3><i class="fas fa-lock"></i> {{ __('messages.register_step2_title') }}</h3>

                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user-circle"></i> {{ __('messages.register_username_label') }} *
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            placeholder="Choose a username" class="form-control @error('username') is-invalid @enderror"
                            autocomplete="username">
                        <div class="form-hint" id="usernameHint">This will be your login username</div>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_password_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password" name="password" required
                                        placeholder="Create a password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password"
                                        onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                    <button type="button" class="toggle-password" data-target="password"
                                        aria-label="Show or hide password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>

                                <!-- Password Strength Meter -->
                                <div class="password-meter">
                                    <div class="password-meter-fill" id="passwordMeterFill"></div>
                                </div>
                                <small class="form-hint" id="passwordStrengthText">Password strength: too weak</small>

                                <ul class="password-checklist">
                                    <li data-rule="length">At least 8 characters</li>
                                    <li data-rule="number">Contains a number</li>
                                    <li data-rule="upper">Contains uppercase letter</li>
                                    <li data-rule="special">Contains special character</li>
                                    <li style="grid-column: 1 / -1;"><strong>No spaces allowed</strong></li>
                                </ul>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock"></i> {{ __('messages.register_confirm_label') }} *
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required placeholder="Re-enter your password"
                                        class="form-control"
                                        autocomplete="new-password"
                                        onkeypress="if(event.key === ' ') { event.preventDefault(); return false; }">
                                    <button type="button" class="toggle-password" data-target="password_confirmation"
                                        aria-label="Show or hide confirm password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-hint" id="passwordMatchText">Passwords must match</small>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> Benefits of Registration</h4>
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <i class="fas fa-clock"></i>
                                <h5>Online Services</h5>
                                <p>Access barangay services online</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <h5>Track Requests</h5>
                                <p>Monitor your application status</p>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-bolt"></i>
                                <h5>Fast Processing</h5>
                                <p>Skip the lines, apply online</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of
                                Service</a> and <a href="#" data-bs-toggle="modal"
                                data-bs-target="#privacyModal">Privacy Policy</a>
                        </label>
                        @error('terms')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="captcha-section">
                        <label for="recaptchaBox" class="mb-2">
                            <i class="fas fa-shield-alt"></i> Security Check *
                        </label>

                        @if (!empty($recaptchaSiteKey))
                            <div class="captcha-box" id="recaptchaBox">
                                <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                            </div>
                        @else
                            <div class="error-message">CAPTCHA is not configured. Please contact support.</div>
                        @endif

                        @error('g-recaptcha-response')
                            <div class="error-message" id="captchaError">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-prev" data-prev="step1">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.register_prev_btn') }}
                        </button>
                        <button type="submit" class="btn-submit" id="createAccountBtn">
                            <span class="btn-text">{{ __('messages.register_submit_btn') }}</span>
                            <div class="spinner">
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                                <div class="spinner-dot"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">{{ __('messages.terms_modal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Barangay Hulo Online Portal Terms of Service</h6>
                    <p>Last updated: {{ date('F d, Y') }}</p>

                    <h6>1. Acceptance of Terms</h6>
                    <p>By accessing and using the Barangay Hulo Online Portal, you agree to be bound by these Terms and
                        Conditions. If you do not agree with any part of these terms, you may not use our services.</p>

                    <h6>2. User Registration</h6>
                    <p>You must provide accurate and complete information during registration. You are responsible for
                        maintaining the confidentiality of your account credentials.</p>

                    <h6>3. Eligibility</h6>
                    <p>You must be at least 18 years old to register. By registering, you confirm that you are a
                        resident of Barangay Hulo or have legitimate reasons to access barangay services.</p>

                    <h6>4. Valid ID Requirement</h6>
                    <p>You are required to upload a valid government-issued ID for verification purposes. This helps us
                        ensure the security and authenticity of all registered accounts.</p>

                    <h6>5. Privacy and Data Protection</h6>
                    <p>Your personal information will be collected, stored, and processed in accordance with the Data
                        Privacy Act of 2012 and our Privacy Policy. We are committed to protecting your privacy and
                        ensuring the security of your data.</p>

                    <h6>6. Acceptable Use</h6>
                    <p>You agree to use the portal only for lawful purposes. You may not use the portal to:</p>
                    <ul>
                        <li>Violate any laws or regulations</li>
                        <li>Impersonate any person or entity</li>
                        <li>Submit false or misleading information</li>
                        <li>Interfere with the proper functioning of the portal</li>
                    </ul>

                    <h6>7. Account Termination</h6>
                    <p>We reserve the right to suspend or terminate your account if we suspect any violation of these
                        terms or unauthorized use of the portal.</p>

                    <h6>8. Changes to Terms</h6>
                    <p>We may modify these terms at any time. Continued use of the portal after changes constitutes
                        acceptance of the modified terms.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">{{ __('messages.register_privacy_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Barangay Hulo Privacy Policy</h6>
                    <p>Last updated: {{ date('F d, Y') }}</p>

                    <h6>1. Information We Collect</h6>
                    <p>We collect personal information including but not limited to:</p>
                    <ul>
                        <li>Full name</li>
                        <li>Date of birth</li>
                        <li>Contact number</li>
                        <li>Email address</li>
                        <li>Complete address</li>
                        <li>Valid ID (image/PDF)</li>
                        <li>Username and password</li>
                    </ul>

                    <h6>2. How We Use Your Information</h6>
                    <p>Your information is used to:</p>
                    <ul>
                        <li>Process your requests and applications</li>
                        <li>Verify your identity and residency</li>
                        <li>Communicate important announcements</li>
                        <li>Improve our services</li>
                        <li>Comply with legal requirements</li>
                    </ul>

                    <h6>3. Data Protection</h6>
                    <p>We implement appropriate technical and organizational measures to protect your personal
                        information against unauthorized access, alteration, disclosure, or destruction.</p>

                    <h6>4. Data Sharing</h6>
                    <p>We do not sell or rent your personal information. Information may be shared only with authorized
                        barangay personnel and government agencies as required by law.</p>

                    <h6>5. Your Rights</h6>
                    <p>You have the right to access, correct, or request deletion of your personal information. Contact
                        the Barangay Hall to exercise these rights.</p>

                    <h6>6. Contact Information</h6>
                    <p>
                        Barangay Hulo Hall<br>
                        Hulong Duhat, Malabon City<br>
                        Email: barangayhulongduhat@gmail.com<br>
                        Tel: 8-281-1373
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    @if (!empty($recaptchaSiteKey))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("registerForm");
            if (!form) return;

            // Get form elements
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');
            const submitBtn = document.getElementById('createAccountBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const meterFill = document.getElementById('passwordMeterFill');
            const strengthText = document.getElementById('passwordStrengthText');
            const matchText = document.getElementById('passwordMatchText');
            const termsCheckbox = document.getElementById('terms');
            const passwordChecklist = Array.from(document.querySelectorAll(".password-checklist li"));
            const usernameHint = document.getElementById('usernameHint');

            const contactInput = document.getElementById("contact");
            const birthdateInput = document.getElementById("birthdate");
            const validIdInput = document.getElementById("valid_id");
            const fileUploadText = document.getElementById("fileUploadText");
            const recaptchaBox = document.getElementById("recaptchaBox");
            const recaptchaEnabled = {!! !empty($recaptchaSiteKey) ? 'true' : 'false' !!};

            let usernameCheckTimer = null;
            let usernameCheckSeq = 0;
            let isProgrammaticSubmit = false;
            const usernameCheckState = {
                value: '',
                status: 'idle',
                message: '',
            };

            let currentStep = 1;
            const totalSteps = 2;

            // ============= SIMPLE NAME VALIDATION - WITH ACCENTED CHARACTER SUPPORT =============
            const nameFields = ['firstname', 'middlename', 'lastname'];

            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    // Validate on blur (when user leaves the field)
                    field.addEventListener('blur', function() {
                        validateNameField(this);
                    });

                    // Clear error when user starts typing
                    field.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                        const existingError = this.parentNode.querySelector('.error-message');
                        if (existingError) {
                            existingError.remove();
                        }
                    });
                }
            });

            // SIMPLE name validation function that works with accented characters
            function validateNameField(field) {
                const fieldValue = field.value.trim();
                const fieldName = field.id.charAt(0).toUpperCase() + field.id.slice(1);
                
                // Clear any existing error
                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Check if field is required and empty
                if (field.hasAttribute('required') && !fieldValue) {
                    showFieldError(field, `${fieldName} is required`);
                    return false;
                }
                
                // If field has value, validate format
                if (fieldValue) {
                    const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;
                    
                    if (!nameRegex.test(fieldValue)) {
                        showFieldError(field, `${fieldName} may only contain letters, spaces, dots, apostrophes, and hyphens`);
                        return false;
                    }
                    
                    // Check minimum length
                    if (fieldValue.length < 2) {
                        showFieldError(field, `${fieldName} must be at least 2 characters long`);
                        return false;
                    }
                }
                
                return true;
            }

            // ============= USERNAME VALIDATION - NO SPACES ALLOWED =============
            const usernameField = document.getElementById('username');
            if (usernameField) {
                // Automatically remove spaces while typing
                usernameField.addEventListener('input', function(e) {
                    // Replace spaces with empty string
                    this.value = this.value.replace(/\s/g, '');

                    // Clear error
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.error-message');
                    if (existingError) {
                        existingError.remove();
                    }

                    if (usernameHint) {
                        usernameHint.textContent = 'Checking username availability...';
                        usernameHint.classList.remove('good', 'bad');
                    }

                    if (usernameCheckTimer) {
                        clearTimeout(usernameCheckTimer);
                    }

                    usernameCheckTimer = setTimeout(() => {
                        checkUsernameAvailability(this.value);
                    }, 350);
                });

                usernameField.addEventListener('blur', function() {
                    validateUsernameField(this);
                    checkUsernameAvailability(this.value, true);
                });
            }

            async function checkUsernameAvailability(rawUsername, force = false) {
                const username = String(rawUsername || '').trim().toLowerCase();

                if (!force && username === usernameCheckState.value && (usernameCheckState.status === 'available' || usernameCheckState.status === 'unavailable')) {
                    return usernameCheckState.status === 'available';
                }

                if (username === '') {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'idle';
                    usernameCheckState.message = '';
                    if (usernameHint) {
                        usernameHint.textContent = 'This will be your login username';
                        usernameHint.classList.remove('good', 'bad');
                    }
                    return false;
                }

                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(username) || username.length < 4) {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'invalid';
                    usernameCheckState.message = 'Username format is invalid';
                    if (usernameHint) {
                        usernameHint.textContent = username.length < 4
                            ? 'Username must be at least 4 characters long'
                            : 'Only letters, numbers, underscore, dot, and hyphen are allowed';
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }

                const requestId = ++usernameCheckSeq;
                usernameCheckState.value = username;
                usernameCheckState.status = 'checking';
                usernameCheckState.message = 'Checking username availability...';

                if (usernameHint) {
                    usernameHint.textContent = 'Checking username availability...';
                    usernameHint.classList.remove('good', 'bad');
                }

                try {
                    const response = await fetch(`{{ route('register.check-username') }}?username=${encodeURIComponent(username)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    if (response.ok && data.available) {
                        usernameCheckState.status = 'available';
                        usernameCheckState.message = data.message || 'Username is available';
                        if (usernameHint) {
                            usernameHint.textContent = usernameCheckState.message;
                            usernameHint.classList.remove('bad');
                            usernameHint.classList.add('good');
                        }
                        return true;
                    }

                    usernameCheckState.status = 'unavailable';
                    usernameCheckState.message = data.message || 'Username is already taken';
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                } catch (error) {
                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    usernameCheckState.status = 'error';
                    usernameCheckState.message = 'Unable to check username right now';
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }
            }

            function validateUsernameField(field) {
                const fieldValue = field.value.trim();
                
                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                if (!fieldValue) {
                    showFieldError(field, 'Username is required');
                    return false;
                }
                
                // Check for valid characters (letters, numbers, underscore, dot, hyphen) - NO SPACES
                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(fieldValue)) {
                    showFieldError(field, 'Username may only contain letters, numbers, underscores (_), dots (.), and hyphens (-)');
                    return false;
                }
                
                if (fieldValue.length < 4) {
                    showFieldError(field, 'Username must be at least 4 characters long');
                    return false;
                }
                
                return true;
            }

            // ============= PASSWORD MATCH VALIDATION - NO DUPLICATE ERRORS =============
            function updatePasswordMatchUI() {
                if (!passwordInput || !confirmPasswordInput || !matchText) return true;

                const password = passwordInput.value;
                const confirm = confirmPasswordInput.value;

                // Remove any existing error messages for confirm password
                const confirmGroup = confirmPasswordInput.closest('.form-group');
                if (confirmGroup) {
                    const existingErrors = confirmGroup.querySelectorAll('.error-message:not(#passwordMatchText)');
                    existingErrors.forEach(error => error.remove());
                }

                if (!confirm) {
                    matchText.textContent = "Passwords must match";
                    matchText.classList.remove("good", "bad");
                    confirmPasswordInput.classList.remove("is-invalid");
                    return false;
                }

                const matched = password === confirm;
                
                matchText.textContent = matched ? "Passwords match" : "Passwords do not match";
                matchText.classList.toggle("good", matched);
                matchText.classList.toggle("bad", !matched);
                confirmPasswordInput.classList.toggle("is-invalid", !matched);
                
                return matched;
            }

            // ============= MAIN VALIDATION FUNCTION =============
            function validateStep(stepNumber) {
                let isValid = true;
                const currentStepElement = document.getElementById(`step${stepNumber}`);

                // Clear previous error messages
                const existingErrors = currentStepElement.querySelectorAll('.error-message:not(.alert *):not(#passwordMatchText)');
                existingErrors.forEach(error => error.remove());

                // Reset field styles
                const allFields = currentStepElement.querySelectorAll('.form-control');
                allFields.forEach(field => field.classList.remove('is-invalid'));

                // Validate required fields
                const requiredFields = currentStepElement.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (!field.checked) {
                            isValid = false;
                            showFieldError(field, 'You must agree to continue');
                        }
                        return;
                    }

                    if (field.type === 'file') {
                        if (!field.files || field.files.length === 0) {
                            isValid = false;
                            showFieldError(field, 'Please upload your valid ID');
                        } else {
                            const fileSize = field.files[0].size;
                            if (fileSize > 5242880) {
                                isValid = false;
                                showFieldError(field, 'File size must not exceed 5MB');
                            }

                            const fileType = field.files[0].type;
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                            if (!allowedTypes.includes(fileType)) {
                                isValid = false;
                                showFieldError(field, 'File must be JPG, PNG, or PDF');
                            }
                        }
                        return;
                    }

                    const fieldValue = field.value.trim();

                    if (!fieldValue) {
                        isValid = false;
                        showFieldError(field, 'This field is required');
                        return;
                    }

                    // NAME FIELD VALIDATION
                    if (['firstname', 'middlename', 'lastname'].includes(field.id)) {
                        const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;
                        if (!nameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, `${field.id.charAt(0).toUpperCase() + field.id.slice(1)} may only contain letters, spaces, dots, apostrophes, and hyphens`);
                        } else if (fieldValue.length < 2) {
                            isValid = false;
                            showFieldError(field, `${field.id.charAt(0).toUpperCase() + field.id.slice(1)} must be at least 2 characters long`);
                        }
                    }

                    // USERNAME VALIDATION
                    if (field.id === 'username') {
                        const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                        if (!usernameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, 'Username may only contain letters, numbers, underscores (_), dots (.), and hyphens (-)');
                        } else if (fieldValue.length < 4) {
                            isValid = false;
                            showFieldError(field, 'Username must be at least 4 characters long');
                        } else if (usernameCheckState.value === fieldValue.toLowerCase() && usernameCheckState.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, 'Username is already taken');
                        }
                    }

                    // Address minimum length
                    if (field.id === 'address' && fieldValue.length < 10) {
                        isValid = false;
                        showFieldError(field, 'Address must be at least 10 characters long');
                    }

                    // Email validation
                    if (field.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid email address');
                        }
                    }

                    // Phone number validation
                    if (field.id === 'contact') {
                        const phoneRegex = /^09\d{9}$/;
                        if (!phoneRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid Philippine mobile number (09XXXXXXXXX)');
                        }
                    }

                    // Birthdate validation
                    if (field.id === 'birthdate') {
                        const birthDate = new Date(fieldValue);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (birthDate > today) {
                            isValid = false;
                            showFieldError(field, 'Date of birth cannot be in the future');
                        } else {
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();

                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }

                            if (age < 18) {
                                isValid = false;
                                showFieldError(field, 'You must be at least 18 years old to register');
                            }
                        }
                    }
                });

                // Step 2 specific validations
                if (stepNumber === 2) {
                    if (!validatePasswordStrength()) isValid = false;
                    if (!updatePasswordMatchUI()) {
                        isValid = false;
                    }
                    
                    if (termsCheckbox && !termsCheckbox.checked) {
                        isValid = false;
                        showFieldError(termsCheckbox, 'You must agree to the terms and conditions');
                    }
                }

                return isValid;
            }

            // Show error message (prevents duplicates)
            function showFieldError(field, message) {
                field.classList.add('is-invalid');

                const parent = field.type === 'checkbox' ? field.closest('.terms-section') : 
                            field.type === 'file' ? field.closest('.file-upload-wrapper') : 
                            field.parentNode;
                
                if (parent) {
                    const existingError = parent.querySelector('.error-message:not(#passwordMatchText)');
                    if (!existingError) {
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = message;
                        parent.appendChild(errorMessage);
                    }
                }
            }

            // ============= OTHER FUNCTIONS (unchanged) =============
            function clearCaptchaError() {
                const existingError = document.getElementById('captchaError');
                if (existingError) {
                    existingError.remove();
                }
            }

            function showCaptchaError(message) {
                clearCaptchaError();
                const parent = recaptchaBox ? recaptchaBox.parentNode : document.querySelector('.captcha-section');
                if (!parent) {
                    return;
                }

                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.id = 'captchaError';
                errorMessage.textContent = message;
                parent.appendChild(errorMessage);
            }
            
            // Set max date for birthdate
            if (birthdateInput) {
                const today = new Date();
                const minAge = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                birthdateInput.max = minAge.toISOString().split("T")[0];
            }

            // File upload display
            if (validIdInput) {
                validIdInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name || 'No file chosen';
                    fileUploadText.textContent = fileName;

                    if (e.target.files[0] && e.target.files[0].size > 5242880) {
                        alert('File is too large. Maximum size is 5MB.');
                        e.target.value = '';
                        fileUploadText.textContent = 'No file chosen';
                    }

                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (e.target.files[0] && !allowedTypes.includes(e.target.files[0].type)) {
                        alert('Invalid file type. Please upload JPG, PNG, or PDF only.');
                        e.target.value = '';
                        fileUploadText.textContent = 'No file chosen';
                    }
                });
            }

            // Next button functionality
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = this.getAttribute('data-next');

                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            // Previous button functionality
            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const prevStep = this.getAttribute('data-prev');
                    if (!prevStep) return; // no data-prev = natural link (Cancel)
                    e.preventDefault();
                    goToStep(prevStep);
                });
            });

            function goToStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
                currentStep = parseInt(stepId.replace('step', ''));
                updateProgress();
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                if (progressFill) progressFill.style.width = `${progressPercentage}%`;

                progressSteps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active');
                    if (stepNumber === currentStep) {
                        step.classList.add('active');
                    }
                });
            }

            // Password strength evaluation
            function evaluatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score += 1;
                if (/[A-Z]/.test(password)) score += 1;
                if (/[a-z]/.test(password)) score += 1;
                if (/\d/.test(password)) score += 1;
                if (/[^A-Za-z0-9]/.test(password)) score += 1;

                if (password.length === 0) return {
                    score: 0,
                    label: "too weak",
                    color: "#d16060",
                    width: 10
                };
                if (score <= 2) return {
                    score,
                    label: "weak",
                    color: "#d16060",
                    width: 35
                };
                if (score <= 3) return {
                    score,
                    label: "fair",
                    color: "#d38e2a",
                    width: 60
                };
                if (score <= 4) return {
                    score,
                    label: "good",
                    color: "#2c7dc8",
                    width: 82
                };
                return {
                    score,
                    label: "strong",
                    color: "#1e8f55",
                    width: 100
                };
            }

            function validatePasswordStrength() {
                if (!passwordInput) return true;
                const password = passwordInput.value;
                const { label } = evaluatePasswordStrength(password);

                const existingError = passwordInput.closest('.form-group')?.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (password && (label === "too weak" || label === "weak")) {
                    showFieldError(passwordInput, 'Password is too weak. Please make it stronger.');
                    return false;
                }
                return true;
            }

            function updatePasswordUI() {
                if (!passwordInput || !meterFill || !strengthText) return;

                const {
                    label,
                    color,
                    width
                } = evaluatePasswordStrength(passwordInput.value);
                meterFill.style.width = `${width}%`;
                meterFill.style.backgroundColor = color;
                strengthText.textContent = `Password strength: ${label}`;
                strengthText.classList.toggle("good", label === "good" || label === "strong");
                strengthText.classList.toggle("bad", label === "weak" || label === "too weak");

                passwordChecklist.forEach((item) => {
                    const rule = item.dataset.rule;
                    if (rule) {
                        const rules = {
                            length: passwordInput.value.length >= 8,
                            number: /\d/.test(passwordInput.value),
                            upper: /[A-Z]/.test(passwordInput.value),
                            special: /[^A-Za-z0-9]/.test(passwordInput.value),
                        };
                        item.classList.toggle("is-complete", rules[rule] || false);
                    }
                });
            }

            // Sanitize contact number
            if (contactInput) {
                contactInput.addEventListener("input", () => {
                    contactInput.value = contactInput.value.replace(/\D/g, "").slice(0, 11);
                });
            }

            // Password event listeners
            if (passwordInput) {
                passwordInput.addEventListener("input", () => {
                    // Remove any spaces that may have been typed or pasted
                    passwordInput.value = passwordInput.value.replace(/\s/g, "");
                    updatePasswordUI();
                    updatePasswordMatchUI();
                });
            }

            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener("input", () => {
                    // Remove any spaces that may have been typed or pasted
                    confirmPasswordInput.value = confirmPasswordInput.value.replace(/\s/g, "");
                    updatePasswordMatchUI();
                });
            }

            // Toggle password visibility
            document.querySelectorAll(".toggle-password").forEach((toggle) => {
                toggle.addEventListener("click", () => {
                    const targetId = toggle.getAttribute("data-target");
                    const input = targetId ? document.getElementById(targetId) : null;
                    if (!input) return;

                    const showing = input.type === "text";
                    input.type = showing ? "password" : "text";
                    const icon = toggle.querySelector("i");
                    if (icon) {
                        icon.classList.toggle("fa-eye", showing);
                        icon.classList.toggle("fa-eye-slash", !showing);
                    }
                });
            });

            // Form submission
            if (form) {
                form.addEventListener("submit", async (event) => {
                    if (isProgrammaticSubmit) {
                        return;
                    }

                    event.preventDefault();

                    if (!validateStep(currentStep)) {
                        document.querySelectorAll('.is-invalid').forEach(field => {
                            const group = field.closest('.form-group') || field.closest('.file-upload-wrapper');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                        });
                        return;
                    }

                    if (usernameField) {
                        const usernameAvailable = await checkUsernameAvailability(usernameField.value, true);
                        if (!usernameAvailable) {
                            showFieldError(usernameField, usernameCheckState.message || 'Username is already taken');
                            const group = usernameField.closest('.form-group');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                            return;
                        }
                    }

                    if (recaptchaEnabled) {
                        const captchaToken = window.grecaptcha ? window.grecaptcha.getResponse() : '';
                        if (!captchaToken) {
                            showCaptchaError('Please complete the CAPTCHA verification');
                            const captchaSection = document.querySelector('.captcha-section');
                            if (captchaSection) {
                                captchaSection.classList.add('shake');
                                setTimeout(() => captchaSection.classList.remove('shake'), 400);
                            }
                            return;
                        }

                        clearCaptchaError();
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add("loading");
                        const btnText = submitBtn.querySelector(".btn-text");
                        if (btnText) btnText.textContent = "Creating account...";
                    }

                    isProgrammaticSubmit = true;
                    form.submit();
                });
            }

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            // Initialize
            updateProgress();
            updatePasswordUI();
            updatePasswordMatchUI();

            // Auto-navigate to step 2 if server returned errors for step 2 fields
            const hasStep2Errors = {!! $errors->hasAny(['username', 'password', 'password_confirmation', 'terms', 'g-recaptcha-response']) ? 'true' : 'false' !!};
            if (hasStep2Errors) {
                goToStep('step2');
            }
        });
    </script>
</body>

</html>