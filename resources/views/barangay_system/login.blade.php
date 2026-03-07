<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login - Barangay Hulo Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --light-red: #ff5f52;
            --light-gray: #f5f5f5;
            --text-dark: #333333;
            --text-light: #777777;
        }

        body {
            min-height: 100vh;
            overflow-y: auto;
            background: url('/Images/bgnew.png') center center / cover no-repeat fixed;
            font-family: 'Assistant', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        /* Main Container */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 500px;
            height: auto;
            margin: auto;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        /* Login Panel */
        .login-panel {
            flex: 1;
            background: white;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 0;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 12px;
            width: 100%;
        }

        .logo-img {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-red);
            padding: 4px;
            background: white;
            margin-bottom: 5px;
            box-shadow: 0 4px 10px rgba(179, 0, 0, 0.2);
        }

        .logo-section h1 {
            font-size: 1.7rem;
            font-weight: 900;
            color: var(--primary-red);
            margin-bottom: 0px;
            line-height: 1.2;
        }

        .logo-section p {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        /* Alert Messages */
        .alert {
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 12px;
            border-left: 4px solid;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 380px;
        }

        .alert-danger {
            background: #fff2f2;
            border-left-color: var(--primary-red);
            color: #721c24;
        }

        .alert-success {
            background: #f0fff4;
            border-left-color: #28a745;
            color: #155724;
        }

        /* Form Styles */
        #loginForm {
            width: 100%;
            max-width: 380px;
        }

        .form-floating {
            margin-bottom: 12px;
        }

        .form-floating > .form-control {
            border-left: 3px solid var(--primary-red);
            border-radius: 8px;
            height: 48px;
            padding-left: 40px;
            font-family: 'Assistant', sans-serif;
            font-size: 0.85rem;
        }

        .form-floating > .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-red);
        }

        .form-floating > .form-control.is-invalid {
            border-color: var(--primary-red);
            border-left: 3px solid var(--primary-red);
        }

        .form-floating > label {
            padding-left: 40px;
            font-weight: 500;
            color: #555;
            font-size: 0.8rem;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: var(--primary-red);
            font-size: 15px;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
        }

        .password-toggle:hover {
            color: var(--primary-red);
        }

        /* Options Row */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2px 0 12px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            font-size: 0.8rem;
        }

        .remember-check input {
            width: 14px;
            height: 14px;
            accent-color: var(--primary-red);
        }

        .forgot-link {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.75rem;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--dark-red);
            text-decoration: underline;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading .btn-text {
            opacity: 0;
        }

        .btn-login .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            gap: 5px;
        }

        .btn-login.loading .spinner {
            display: flex;
        }

        .spinner-dot {
            width: 7px;
            height: 7px;
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

        /* Divider */
        .divider {
            text-align: center;
            position: relative;
            margin: 10px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #eee;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .divider span {
            background: white;
            padding: 0 10px;
            color: #999;
            font-size: 0.75rem;
        }

        /* Social Login */
        .social-login {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .social-btn.facebook { background: #3b5998; }
        .social-btn.google { background: #db4437; }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 6px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }

        .register-link p {
            color: #666;
            margin-bottom: 0px;
            font-size: 0.75rem;
        }

        .register-link a {
            color: var(--primary-red);
            font-weight: 700;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
            display: inline-block;
        }

        .register-link a:hover {
            color: var(--dark-red);
            text-decoration: underline;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            border-bottom: 1px solid #e5e5e5;
            padding: 18px 22px;
        }

        .modal-header h2 {
            color: var(--primary-red);
            font-size: 1.4rem;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.6rem;
            color: #999;
            cursor: pointer;
        }

        .modal-body {
            padding: 22px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-body h3 {
            color: var(--primary-red);
            font-size: 1.1rem;
            margin: 15px 0 8px 0;
        }

        .modal-body h4 {
            color: var(--text-dark);
            font-size: 1rem;
            margin: 12px 0 5px 0;
        }

        .modal-footer {
            border-top: 1px solid #e5e5e5;
            padding: 12px 22px;
        }

        .btn-decline {
            background: #e5e5e5;
            color: #333;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-accept {
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            border: none;
            padding: 8px 22px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .login-panel {
                padding: 30px 25px;
            }

            .logo-img {
                width: 55px;
                height: 55px;
            }

            .logo-section h1 {
                font-size: 1.4rem;
            }

            .form-floating > .form-control {
                height: 42px;
                font-size: 0.8rem;
            }

            .btn-login {
                height: 42px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Panel -->
        <div class="login-panel">
            <div class="logo-section">
                <img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" class="logo-img">
                <h1>Login</h1>
                <p>Barangay Hulo<br>Malabon City</p>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display Error Message -->
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Custom Login Error Alert (for wrong username/password) -->
            <div id="loginErrorAlert" class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="loginErrorMessage">Invalid username or password. Please try again.</span>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.res') }}" id="loginForm">
                @csrf
                @method('post')

                <!-- Username Field -->
                <div class="form-floating mb-3 position-relative">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" placeholder="Username" 
                           value="{{ old('username') }}" required autofocus>
                    <label for="username">Username or Email</label>
                </div>

                <!-- Password Field -->
                <div class="form-floating mb-2 position-relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>

                <!-- Forgot Password -->
                <div class="options-row justify-content-end">
                    <a href="{{ route('resident.password.request') }}" class="forgot-link">
                        <i class="fas fa-lock me-1"></i>Forgot Password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login" id="loginButton">
                    <span class="btn-text">Login</span>
                    <div class="spinner">
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                        <div class="spinner-dot"></div>
                    </div>
                </button>

                <!-- Register Link (triggers modal) -->
                <div class="register-link">
                    <p>Don't have an account?</p>
                    <a href="#" id="createAccountLink">
                        <i class="fas fa-user-plus me-1"></i>Create an Account
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Terms and Conditions</h2>
                    <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Barangay Hulo Online Portal</h3>
                    <p>By creating an account, you agree to the following terms and conditions:</p>
                    
                    <h4>1. User Responsibilities</h4>
                    <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
                    
                    <h4>2. Accurate Information</h4>
                    <p>You must provide accurate, current, and complete information during registration. You confirm that you are a resident of Barangay Hulo or have valid reasons to access barangay services.</p>
                    
                    <h4>3. Privacy Policy</h4>
                    <p>Your personal information will be handled in accordance with our Privacy Policy and the Data Privacy Act of 2012.</p>
                    
                    <h4>4. Acceptable Use</h4>
                    <p>You agree to use the portal only for lawful purposes and in accordance with barangay regulations.</p>
                    
                    <p class="mt-3"><strong>By clicking "I Agree", you confirm that you have read, understood, and accepted these terms and conditions.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-decline" data-bs-dismiss="modal">Decline</button>
                    <button type="button" class="btn-accept" id="acceptTerms">I Agree</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            const toggle = document.getElementById('togglePassword');
            const pwd = document.getElementById('password');
            if (toggle && pwd) {
                toggle.addEventListener('click', function() {
                    const type = pwd.type === 'password' ? 'text' : 'password';
                    pwd.type = type;
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Login button loading state
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginButton');
            
            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.disabled = true;
                });
            }

            // Terms modal trigger
            const createLink = document.getElementById('createAccountLink');
            const modalEl = document.getElementById('termsModal');
            
            if (createLink && modalEl) {
                const termsModal = new bootstrap.Modal(modalEl);
                
                createLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    termsModal.show();
                });

                // Handle accept terms - redirect to registration
                document.getElementById('acceptTerms')?.addEventListener('click', function() {
                    termsModal.hide();
                    window.location.href = "{{ route('register') }}";
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

            // =============================================
            // FIXED: Client-side validation for empty fields
            // =============================================
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const loginErrorAlert = document.getElementById('loginErrorAlert');
            const loginErrorMessage = document.getElementById('loginErrorMessage');

            // Function to show error message
            function showLoginError(message) {
                loginErrorMessage.textContent = message;
                loginErrorAlert.style.display = 'flex';
                loginErrorAlert.style.opacity = '1';
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    loginErrorAlert.style.transition = 'opacity 0.5s ease';
                    loginErrorAlert.style.opacity = '0';
                    setTimeout(() => {
                        loginErrorAlert.style.display = 'none';
                        loginErrorAlert.style.opacity = '1'; // Reset opacity for next time
                        loginErrorAlert.style.transition = ''; // Remove transition
                    }, 500);
                }, 5000);
            }

            // Add form submit validation
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Check if username is empty
                    if (!usernameInput.value.trim()) {
                        e.preventDefault();
                        showLoginError('Username is required.');
                        loginBtn.classList.remove('loading');
                        loginBtn.disabled = false;
                        usernameInput.focus();
                        return false;
                    }
                    
                    // Check if password is empty
                    if (!passwordInput.value) {
                        e.preventDefault();
                        showLoginError('Password is required.');
                        loginBtn.classList.remove('loading');
                        loginBtn.disabled = false;
                        passwordInput.focus();
                        return false;
                    }
                });
            }

            // =============================================
            // FIXED: Check for login errors from session
            // =============================================
            @if(session('error'))
                // Show custom error for any session error (most likely wrong credentials)
                showLoginError('{{ session('error') }}');
            @elseif($errors->any())
                // Check if error is related to credentials
                @foreach($errors->all() as $error)
                    @if(strpos(strtolower($error), 'credential') !== false || 
                        strpos(strtolower($error), 'password') !== false || 
                        strpos(strtolower($error), 'username') !== false ||
                        strpos(strtolower($error), 'email') !== false ||
                        strpos(strtolower($error), 'login') !== false)
                        showLoginError('{{ $error }}');
                    @endif
                @endforeach
            @endif
        });
    </script>
</body>
</html>