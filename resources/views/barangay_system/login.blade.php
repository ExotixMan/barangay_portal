<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Hulo Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-red: #c62828;
            --dark-red: #8e0000;
            --light-red: #ff5f52;
            --light-gray: #f5f5f5;
            --text-dark: #333333;
            --text-light: #777777;
        }

        body {
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background-image: linear-gradient(to bottom right, #fff5f5, #ffeaea);
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            box-shadow: 0 10px 30px rgba(198, 40, 40, 0.15);
            border-radius: 15px;
            overflow: hidden;
        }

        /* Left side - Branding */
        .branding-side {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .branding-side::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .branding-side::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 70px;
            height: 70px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .logo i {
            font-size: 36px;
            color: var(--primary-red);
        }

        .barangay-name {
            font-size: 24px;
            font-weight: 700;
        }

        .barangay-location {
            font-size: 16px;
            font-weight: 300;
            margin-top: 5px;
            opacity: 0.9;
        }

        .branding-text {
            margin-top: 40px;
            position: relative;
            z-index: 1;
        }

        .branding-text h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .branding-text p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .features {
            margin-top: 30px;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .feature-item i {
            margin-right: 12px;
            background-color: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Right side - Login Form */
        .login-side {
            flex: 1;
            background-color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-light);
            font-size: 16px;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--dark-red);
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, var(--primary-red), var(--dark-red));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 12px;
            margin-bottom: 25px;
        }

        .login-btn:hover {
            background: linear-gradient(to right, var(--dark-red), var(--primary-red));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 20px 0;
            color: var(--text-light);
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: #eee;
        }

        .divider::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: #eee;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .facebook {
            background-color: #3b5998;
        }

        .google {
            background-color: #db4437;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: var(--text-light);
        }

        .register-link a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .branding-side {
                padding: 40px 30px;
            }
            
            .login-side {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <form method="post" action="{{ route('login.res') }}">
    @csrf
    @method('post')
        <div class="container">
            <!-- Left side - Branding -->
            <div class="branding-side">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div>
                        <div class="barangay-name">BARANGAY HULO</div>
                        <div class="barangay-location">Hulong Duhat Malabon City</div>
                    </div>
                </div>
                
                <div class="branding-text">
                    <h2>Welcome to Barangay Hulo Portal</h2>
                    <p>Access the official online services and community resources of Barangay Hulo. Stay connected with local governance and community activities.</p>
                    <p>Login to access personalized services, request documents, and stay updated with barangay announcements.</p>
                </div>
                
                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-file-contract"></i>
                        <span>Request barangay documents online</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-bullhorn"></i>
                        <span>Receive important announcements</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Register for community events</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <span>Access 24/7 support services</span>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Barangay Hulo Official Portal © 2023 | All Rights Reserved</p>
                </div>
            </div>
            
            <!-- Right side - Login Form -->
            <div class="login-side">
                <div class="login-header">
                    <h1>Member Login</h1>
                    <p>Sign in to your Barangay Hulo account</p>
                </div>
                
                <div class="login-form" id="loginForm">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" class="form-control" name="username" placeholder="Enter your username or email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
        
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>
                    <div class="divider">Or sign in with</div>
                    
                    <div class="social-login">
                        <div class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="social-btn google">
                            <i class="fab fa-google"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="login-btn">Login</button>
                    </div>

                    <div class="register-link">
                        Don't have an account? <a href="#">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Barangay Hulo Portal</title>
</head>
<body>
    <form method="post" action="{{ route('login.res') }}">
        @csrf
        @method('post')
        <div class="login-body">

            <div class="login-form">

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
                
                <div class="divider"><span>OR</span></div>
                
                <a href="{{ route('register') }}">
                <button type="button" class="btn btn-outline">Register New Account</button>
                </a>
            </div>
            
            <div class="login-footer">
                <p>Don't have an account?</p>
                <a href="{{ route('register') }}" class="register-link">Create one now</a>
            </div>
        </div> 
    </form>
</body>
</html> --}}