@component('mail::message')
<!-- Logo at the top -->
<img src="{{ asset('Images/logo.jpg') }}" alt="Barangay Logo" style="width:100px; display:block; margin:auto;">

# Hello {{ $notifiable->name }},

You requested a password reset for your Barangay account.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.  
If you did not request a password reset, please ignore this email.

Thanks,<br>
**Barangay Admin**
@endcomponent