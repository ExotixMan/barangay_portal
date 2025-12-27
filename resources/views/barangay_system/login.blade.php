<!DOCTYPE html>
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
</html>