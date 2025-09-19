<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Barangay Hulo Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/register.css">   
</head>
<body>
    <div class="accessibility-tools">
        <button class="text-size-btn" id="increase-text">A+</button>
        <button class="text-size-btn" id="decrease-text">A-</button>
    </div>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo-container">
                    <div class="logo-circle">
                        <img src="/Images/logo.jpg" alt="Barangay Logo">
                    </div>
                    <h1 class="barangay-name">Barangay Hulong Duhat</h1>
                    <p class="barangay-subtitle">Malabon City</p>
                </div>
            </div>
            
            <form method="post" action="{{ route('register.res') }}" enctype="multipart/form-data" class="register-form" id="registrationForm">
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
                    
                    <div class="verification-section">
                        <h3><i class="fas fa-id-card"></i> Proof of Residency</h3>
                        <p>To verify you are a resident of Barangay Hulo, please upload one of the following documents:</p>
                        <ul style="margin: 0.5rem 0 1rem 1.5rem; color: var(--gray);">
                            <li>Barangay ID</li>
                            <li>Utility Bill (electricity, water, etc.)</li>
                            <li>Lease Agreement or Land Title</li>
                        </ul>
                        
                        <div class="file-upload" id="proof-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload proof of residency</p>
                            <span style="font-size: 0.8rem; color: var(--gray);">Max file size: 5MB (PDF, JPG, PNG)</span>
                            <input type="file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        
                        <div id="uploaded-file-container" style="display: none;">
                            <div class="uploaded-file">
                                <i class="fas fa-file-pdf"></i>
                                <span id="uploaded-file-name">document.pdf</span>
                                <button type="button" id="remove-file"><i class="fas fa-times"></i></button>
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
                    
                    <button type="button" class="btn btn-outline" id="back-to-login">Back to Login</button>
                
                    <div class="register-footer">
                        <p>Already have an account?</p>
                        <a href="login.html" class="login-link">Sign in here</a>
                    </div>
                </div>    
            </form>
        </div>
    </div>
</body>
</html>