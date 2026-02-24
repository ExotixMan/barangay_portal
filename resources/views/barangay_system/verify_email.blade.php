<h3>Please verify your email address</h3>

<p>
    A verification link has been sent to your email.
</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">Resend Email</button>
</form>