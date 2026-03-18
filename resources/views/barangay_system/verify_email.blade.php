<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Email Verification</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">

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

        <h3 class="verify-title">{{ __('messages.verify_email_title') }}</h3>

        <p class="verify-text">
        {{ __('messages.verify_email_message') }}<br>
        Please check your inbox and click the link to activate your account.
        </p>

        @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
        {{ __('messages.verify_email_resent') }}
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit" class="resend-btn w-100">
        Resend Verification Email
        </button>

        </form>

        <a href="{{ route('login') }}" class="back-login">
        {{ __('messages.verify_email_back_btn') }}
        </a>

    </div>
    @if (session('success'))
        <script>
        localStorage.setItem("verify_timer", 60);
        </script>
    @endif

    <script>
        let btn = document.querySelector('.resend-btn');
        let seconds = localStorage.getItem("verify_timer") || 0;

        // Function to start countdown
        function startTimer(sec) {
            btn.disabled = true;
            let timer = setInterval(() => {
                sec--;
                btn.innerText = "Resend Email (" + sec + "s)";
                localStorage.setItem("verify_timer", sec);

                if (sec <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    btn.innerText = "Resend Verification Email";
                    localStorage.removeItem("verify_timer");
                }
            }, 1000);
        }

        if (seconds > 0) {
            startTimer(parseInt(seconds));
        }
        
        btn.closest('form').addEventListener('submit', function(e) {
            startTimer(60);
        });
    </script>

</body>
</html>