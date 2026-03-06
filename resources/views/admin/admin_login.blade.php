<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
        }
        
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            border-radius: 1rem 1rem 0 0;
            padding: 2rem;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 2.5rem;
            background: white;
            border-radius: 0 0 1rem 1rem;
        }
        
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b7b9cc;
        }
        
        .input-icon input {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1><i class="fas fa-shield-alt me-2"></i>Admin Login</h1>
            <p>Enter your credentials to access the admin panel</p>
        </div>
        
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first('email') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="admin@example.com" required autofocus>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" 
                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                    
                    @if (Route::has('admin.password.request'))
                        <a href="{{ route('admin.password.request') }}" class="text-decoration-none">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-login text-white w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                </button>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center text-muted small">
                <i class="fas fa-lock me-1"></i>
                Secure Admin Access Only
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>