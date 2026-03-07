<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Barangay Hulo Portal</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Assistant -->
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@300;400;600;700;900&display=swap" rel="stylesheet">

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

        .reset-card {
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

        .reset-title {
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

        .reset-subtitle {
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
            margin-bottom: 20px;
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

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-red);
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

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.75rem;
            margin-top: 5px;
            text-align: right;
            color: #777;
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
            margin: 25px 0 15px;
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
        }

        .back-login i {
            margin-right: 5px;
        }

        .back-login:hover {
            color: var(--dark-red);
            text-decoration: underline;
            transform: translateX(-3px);
        }

        /* Requirements List */
        .requirements {
            text-align: left;
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        .requirements p {
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .requirements ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .requirements li {
            color: #777;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .requirements li i {
            font-size: 0.7rem;
            color: #999;
        }

        .requirements li.valid i {
            color: #28a745;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .reset-card {
                padding: 30px 20px;
            }

            .logo-img {
                width: 60px;
                height: 60px;
            }

            .reset-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <!-- Logo -->
        <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo-img">
        
        <h1 class="reset-title">Reset Password</h1>
        <div class="barangay-name">Barangay Hulo • Malabon City</div>
        
        <p class="reset-subtitle">
            <i class="fas fa-key text-muted me-2"></i>
            Please enter your new password below.
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

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('resident.password.update') }}" id="resetForm">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Field (Hidden style but visible) -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $email) }}" 
                           placeholder="Enter your email"
                           readonly
                           required>
                </div>
            </div>

            <!-- New Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Enter new password"
                           required>
                    <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <!-- Password Strength Indicator -->
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="strength-text" id="strengthText">Enter password</div>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Confirm new password"
                           required>
                    <button type="button" class="password-toggle" id="toggleConfirmPassword" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="passwordMatchError" style="display: none;">
                    Passwords do not match
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="requirements">
                <p><i class="fas fa-shield-alt me-2"></i>Password must contain:</p>
                <ul>
                    <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                    <li id="req-uppercase"><i class="fas fa-circle"></i> At least one uppercase letter</li>
                    <li id="req-lowercase"><i class="fas fa-circle"></i> At least one lowercase letter</li>
                    <li id="req-number"><i class="fas fa-circle"></i> At least one number</li>
                    <li id="req-special"><i class="fas fa-circle"></i> At least one special character</li>
                </ul>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text">Reset Password</span>
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
            const resetForm = document.getElementById('resetForm');
            const submitBtn = document.getElementById('submitBtn');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirm = document.getElementById('toggleConfirmPassword');
            
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.type === 'password' ? 'text' : 'password';
                    password.type = type;
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            if (toggleConfirm && confirmPassword) {
                toggleConfirm.addEventListener('click', function() {
                    const type = confirmPassword.type === 'password' ? 'text' : 'password';
                    confirmPassword.type = type;
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Password strength checker
            if (password) {
                password.addEventListener('input', function() {
                    const val = this.value;
                    const strengthBar = document.getElementById('strengthBar');
                    const strengthText = document.getElementById('strengthText');
                    
                    // Check requirements
                    const hasLength = val.length >= 8;
                    const hasUpper = /[A-Z]/.test(val);
                    const hasLower = /[a-z]/.test(val);
                    const hasNumber = /[0-9]/.test(val);
                    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);
                    
                    // Update requirement indicators
                    document.getElementById('req-length').className = hasLength ? 'valid' : '';
                    document.getElementById('req-uppercase').className = hasUpper ? 'valid' : '';
                    document.getElementById('req-lowercase').className = hasLower ? 'valid' : '';
                    document.getElementById('req-number').className = hasNumber ? 'valid' : '';
                    document.getElementById('req-special').className = hasSpecial ? 'valid' : '';
                    
                    // Update icons
                    document.querySelectorAll('.requirements li').forEach(li => {
                        const icon = li.querySelector('i');
                        if (li.classList.contains('valid')) {
                            icon.className = 'fas fa-check-circle';
                            icon.style.color = '#28a745';
                        } else {
                            icon.className = 'fas fa-circle';
                            icon.style.color = '#999';
                        }
                    });
                    
                    // Calculate strength
                    const strength = [hasLength, hasUpper, hasLower, hasNumber, hasSpecial].filter(Boolean).length;
                    
                    // Update strength bar
                    strengthBar.style.width = (strength * 20) + '%';
                    
                    if (val.length === 0) {
                        strengthBar.style.background = '#e0e0e0';
                        strengthText.textContent = 'Enter password';
                    } else if (strength <= 2) {
                        strengthBar.style.background = '#dc3545';
                        strengthText.textContent = 'Weak password';
                    } else if (strength <= 4) {
                        strengthBar.style.background = '#ffc107';
                        strengthText.textContent = 'Medium password';
                    } else {
                        strengthBar.style.background = '#28a745';
                        strengthText.textContent = 'Strong password';
                    }
                });
            }

            // Password match validation
            if (confirmPassword && password) {
                function checkPasswordMatch() {
                    const matchError = document.getElementById('passwordMatchError');
                    if (password.value && confirmPassword.value) {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.classList.add('is-invalid');
                            matchError.style.display = 'block';
                            return false;
                        } else {
                            confirmPassword.classList.remove('is-invalid');
                            matchError.style.display = 'none';
                            return true;
                        }
                    }
                    return true;
                }

                confirmPassword.addEventListener('input', checkPasswordMatch);
                password.addEventListener('input', checkPasswordMatch);
            }

            // Form submission with validation
            if (resetForm && submitBtn) {
                resetForm.addEventListener('submit', function(e) {
                    // Validate password match
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        confirmPassword.classList.add('is-invalid');
                        document.getElementById('passwordMatchError').style.display = 'block';
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