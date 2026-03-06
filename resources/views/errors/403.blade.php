{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Barangay Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            margin: 0 auto;
        }
        .error-icon {
            font-size: 5rem;
            color: #d32f2f;
            margin-bottom: 1rem;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #d32f2f;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 0 rgba(211, 47, 47, 0.2);
        }
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        .error-message {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        .btn-home {
            background: #d32f2f;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-home:hover {
            background: #b71c1c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
        }
        .btn-back {
            background: #f8f9fa;
            color: #666;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            margin-left: 1rem;
        }
        .btn-back:hover {
            background: #e9ecef;
            color: #333;
        }
        .permission-name {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            color: #d32f2f;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>
        
        @if(isset($exception) && $exception->getMessage())
            <div class="permission-name">
                <i class="fas fa-key me-2"></i>
                {{ $exception->getMessage() }}
            </div>
        @endif
        
        <div class="error-message">
            <p>You don't have permission to access this page.</p>
            <p class="small">If you believe this is a mistake, please contact your administrator.</p>
        </div>
        
        <div>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Go Back
            </a>
            <a href="{{ route('admin.dashboard.index') }}" class="btn-home">
                <i class="fas fa-home me-2"></i>Go to Dashboard
            </a>
        </div>
    </div>
</body>
</html>