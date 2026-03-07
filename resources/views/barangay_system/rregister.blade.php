<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Barangay Hulo Portal</title> 
</head>
<body> 
    <form method="post" action="{{ route('register.res') }}">
        @csrf
        @method('post')
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        <div class="register-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required autocomplete="email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="address">Complete Address</label>
                <div class="input-with-icon">
                    <i class="fas fa-home"></i>
                    <input type="text" id="address" name="address" placeholder="House No., Street, Zone" required autocomplete="street-address">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="birthdate">Date of Birth</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="contact" name="contact" placeholder="09xxxxxxxxx" required pattern="[0-9]{11}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" id="username" name="username" placeholder="Create a username" required autocomplete="username">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm-password" name="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                </div>
            </div>

            {{-- <div class="form-group">
                <label class="remember-me">
                    <input type="checkbox" id="terms" name="terms" required> 
                    I agree to the <a href="#" style="color: var(--primary);">Terms and Conditions</a> and confirm that all information provided is accurate.
                </label>
            </div> --}}
            
            <button type="submit" class="btn btn-primary">Create Account</button>
            
            <div class="divider"><span>OR</span></div>
            
            <a href="{{ route('login') }}">
            <button type="button" class="btn btn-outline" id="back-to-login">Back to Login</button>
            </a>
        
            <div class="register-footer">
                <p>Already have an account?</p>
                <a href="{{ route('login') }}" class="login-link">Sign in here</a>
            </div>
        </div>    
    </form>
</body>
</html>