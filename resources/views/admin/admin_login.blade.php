<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin / MA Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }
        .login-card {
            position: relative;
            max-width: 480px;
            width: 100%;
            padding: 100px 40px 40px 40px;
            margin: 0 20px;
            border: 2px solid #e3342f;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .login-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.25);
        }
        .avatar {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e3342f;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 48px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .card-header {
            text-align: center;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: #333;
            letter-spacing: 1px;
        }
        .input-group-text {
            background-color: #e3342f;
            color: #fff;
            border: none;
            width: 50px;
            justify-content: center;
        }
        .form-control {
            border-left: none;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: #e3342f;
            box-shadow: 0 0 0 0.2rem rgba(227, 52, 47, 0.25);
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .remember-forgot {
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 25px;
            width: 100%;
        }
        .remember-forgot a {
            font-size: 0.9rem;
            color: #e3342f;
            text-decoration: none;
        }
        .remember-forgot a:hover {
            text-decoration: underline;
        }
        .btn-primary {
            background-color: #e3342f;
            border-color: #e3342f;
            font-weight: 600;
            padding: 0.75rem;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background-color: #cc1f1a;
            border-color: #cc1f1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 52, 47, 0.4);
        }
        .btn-primary:focus {
            box-shadow: 0 0 0 .2rem rgba(227, 52, 47, 0.5);
        }
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #e3342f;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #e3342f;
        }
        .form-check-input:checked {
            background-color: #e3342f;
            border-color: #e3342f;
        }
        hr {
            margin: 1.5rem 0;
            opacity: 0.2;
        }
        .secure-text {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
        }
        .secure-text i {
            color: #e3342f;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="avatar">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <div class="card-header">
        ADMIN LOGIN
    </div>
    
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first('email') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Email Address" 
                       required 
                       autofocus>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" 
                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Password" 
                       required>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            
            <div class="remember-forgot">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" 
                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                
                @if (Route::has('admin.password.request'))
                    <a href="{{ route('admin.password.request') }}" id="forgotLink">
                        Forgot Password?
                    </a>
                @endif
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
                </button>
            </div>
            
            <hr>
            
            <div class="secure-text">
                <i class="bi bi-shield-check"></i>
                Secure Admin Access Only
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>