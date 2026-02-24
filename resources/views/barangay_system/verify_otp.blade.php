<form method="POST" action="{{ route('otp.verify') }}">
    @csrf

    <input type="hidden" name="resident_id" value="{{ session('resident_id') }}">

    <h3>Enter OTP sent to your phone</h3>

    <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>

    <button type="submit">Verify OTP</button>
</form>