<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    
    <style>
        body {
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 1%;
            font-family: 'Nunito', sans-serif;
        }
        .login-card {
            position: relative;
            max-width: 400px;
            width: 100%;
            padding: 70px 30px 30px 30px;
            margin: 0 15px;
            border: 2px solid #e3342f;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .avatar {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e3342f;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 38px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .card-header {
            text-align: center;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: #333;
            letter-spacing: 0.5px;
        }
        .input-group-text {
            background-color: #e3342f;
            color: #fff;
            border: none;
            width: 45px;
            justify-content: center;
            padding: 0.5rem;
        }
        .form-control {
            border-left: none;
            padding: 0.6rem 0.8rem;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #e3342f;
            box-shadow: 0 0 0 0.2rem rgba(227, 52, 47, 0.25);
        }
        .form-control.is-invalid {
            border-color: #e3342f;
            background-image: none;
        }
        .input-group {
            margin-bottom: 0.25rem;
        }
        .btn-primary {
            background-color: #e3342f;
            border-color: #e3342f;
            font-weight: 600;
            padding: 0.6rem;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }
        .btn-primary:hover {
            background-color: #cc1f1a;
            border-color: #cc1f1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(227, 52, 47, 0.3);
        }
        .btn-primary:focus {
            box-shadow: 0 0 0 .2rem rgba(227, 52, 47, 0.5);
        }
        .alert {
            border-radius: 6px;
            border: none;
            margin-bottom: 1.2rem;
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 3px solid #e3342f;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }
        .field-error {
            display: block;
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
            color: #e3342f;
            padding-left: 48px;
        }
        .form-check-input:checked {
            background-color: #e3342f;
            border-color: #e3342f;
        }
        .form-check-input {
            margin-top: 0.15rem;
        }
        hr {
            margin: 1rem 0;
            opacity: 0.15;
        }
        .secure-text {
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
        }
        .secure-text i {
            color: #e3342f;
            margin-right: 4px;
            font-size: 0.75rem;
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
    
    <div class="card-body p-0">
        <!-- Show a single summary error if needed -->
        @if($errors->any() && !$errors->has('login') && !$errors->has('password'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="text" 
                       class="form-control @error('login') is-invalid @enderror" 
                       id="login" 
                       name="login" 
                       value="{{ old('login') }}" 
                       placeholder="Email or Username" 
                       required 
                       autofocus>
            </div>
            @error('login')
                <div class="field-error">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Password" 
                       required>
            </div>
            @error('password')
                <div class="field-error">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
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
