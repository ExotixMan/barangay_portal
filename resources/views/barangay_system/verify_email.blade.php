<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Email Verification</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

    body{
        background:url('/Images/bgnew.png') center/cover no-repeat;
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        font-family:'Segoe UI', sans-serif;
    }

    .verify-card{
        max-width:500px;
        width:100%;
        background:white;
        border-radius:18px;
        padding:40px;
        text-align:center;
        box-shadow:0 15px 35px rgba(0,0,0,0.2);
    }

    .logo{
        width:80px;
        margin-bottom:15px;
    }

    .verify-title{
        font-weight:700;
        color:#c4161c;
    }

    .verify-text{
        color:#555;
    }

    .resend-btn{
        background:#c4161c;
        border:none;
        padding:12px 25px;
        border-radius:8px;
        color:white;
        font-weight:600;
    }

    .resend-btn:hover{
        background:#a10f14;
    }

    .back-login{
        margin-top:20px;
        display:block;
        color:#c4161c;
        text-decoration:none;
    }

    </style>

</head>
<body>

    <div class="verify-card">

        <img src="{{ asset('Images/logo.jpg') }}" class="logo">

        <h3 class="verify-title">Verify Your Email</h3>

        <p class="verify-text">
        A verification link has been sent to your email address.<br>
        Please check your inbox and click the link to activate your account.
        </p>

        @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
        A new verification link has been sent to your email.
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit" class="resend-btn w-100">
        Resend Verification Email
        </button>

        </form>

        <a href="{{ route('login') }}" class="back-login">
        Back to Login
        </a>

    </div>

    <script>   
        let seconds = 60;
        let btn = document.querySelector('.resend-btn');

        btn.disabled = true;

        let timer = setInterval(()=>{
        seconds--;

        btn.innerText = "Resend Email ("+seconds+"s)";

        if(seconds <= 0){
        clearInterval(timer);
        btn.disabled = false;
        btn.innerText = "Resend Verification Email";
        }

        },1000); 
    </script>

</body>
</html>