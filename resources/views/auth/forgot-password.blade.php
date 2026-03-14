<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Barangay Hulo Portal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Assistant -->
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-red: #b30000;
            --dark-red: #800000;
        }

        body {
            min-height: 100vh;
            background: url('/Images/bgnew.png') center/cover no-repeat fixed;
            font-family: 'Assistant', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .forgot-card {
            max-width: 450px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-red);
            padding: 4px;
            background: white;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(179, 0, 0, 0.2);
        }

        .forgot-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--primary-red);
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .barangay-name {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .forgot-subtitle {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        /* Alert Messages */
        .alert {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-left: 4px solid;
            font-size: 0.9rem;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: #f0fff4;
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: #fff2f2;
            border-left-color: var(--primary-red);
            color: #721c24;
        }

        /* Form Styles */
        .form-group {
            text-align: left;
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-red);
            font-size: 16px;
            z-index: 10;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 10px 15px 10px 45px;
            border: 1px solid #e0e0e0;
            border-left: 3px solid var(--primary-red);
            border-radius: 8px;
            font-family: 'Assistant', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(179, 0, 0, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--primary-red);
            border-left: 3px solid var(--primary-red);
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit.loading .btn-text {
            opacity: 0;
        }

        .btn-submit .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            gap: 5px;
        }

        .btn-submit.loading .spinner {
            display: flex;
        }

        .spinner-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite;
        }

        .spinner-dot:nth-child(2) { animation-delay: 0.2s; }
        .spinner-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes pulse {
            0%, 100% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        /* Back to Login Link */
        .back-login {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 5px;
        }

        .back-login i {
            margin-right: 5px;
        }

        .back-login:hover {
            color: var(--dark-red);
            text-decoration: underline;
            transform: translateX(-3px);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .forgot-card {
                padding: 30px 20px;
            }

            .logo-img {
                width: 60px;
                height: 60px;
            }

            .forgot-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-card">
        <!-- Logo -->
        <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo-img">
        
        <h1 class="forgot-title">Forgot Password</h1>
        <div class="barangay-name">Barangay Hulo • Malabon City</div>
        
        <p class="forgot-subtitle">
            <i class="fas fa-lock text-muted me-2"></i>
            Enter your email address and we'll send you a link to reset your password.
        </p>

        <!-- Status Message -->
        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('resident.password.email') }}" id="forgotForm">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Enter your email"
                           required 
                           autofocus>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text">Send Password Reset Link</span>
                <div class="spinner">
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                    <div class="spinner-dot"></div>
                </div>
            </button>

            <a href="{{ route('login') }}" class="back-login">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');
            
            // Loading state on form submit
            if (forgotForm && submitBtn) {
                forgotForm.addEventListener('submit', function(e) {
                    // Client-side validation
                    if (!emailInput.value.trim()) {
                        e.preventDefault();
                        alert('Please enter your email address.');
                        return false;
                    }
                    
                    // Add loading state
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                });
            }

            // Auto-hide alerts after 5 seconds
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
        });
    </script>
</body>
</html>