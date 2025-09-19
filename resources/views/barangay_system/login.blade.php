<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Hulo Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login.css">   
</head>
<body>
    <div class="accessibility-tools">
        <button class="text-size-btn" id="increase-text">A+</button>
        <button class="text-size-btn" id="decrease-text">A-</button>
    </div>

    <div class="login-container">
        <div class="login-card">

            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <img src="Images/logo.jpg" alt="Barangay Logo">
                    </div>
                    <h1 class="barangay-name">Barangay Hulong Duhat</h1>
                    <p class="barangay-subtitle">Malabon City</p>
                </div>
            </div>
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
                                <input type="password" id="password" name="password"     placeholder="Enter your password" required>
                            </div>
                        </div>
                        
                        <div class="remember-forgot">
                            <label class="remember-me">
                                <input type="checkbox" id="remember"> Remember me
                            </label>
                            <a href="#" class="forgot-password">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Login</button>
                        
                        <div class="divider"><span>OR</span></div>
                        
                        <a href="register.html">
                        <button type="button" class="btn btn-outline">Register New Account</button>
                        </a>
                    </div>
                    
                    <div class="login-footer">
                        <p>Don't have an account?</p>
                        <a href="{{ route('register') }}" class="register-link">Create one now</a>
                    </div>
                </div> 
            </form>
        </div> 
    </div> 
</body>
</html>