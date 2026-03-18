<form method="POST" action="{{ route('otp.verify') }}">
    @csrf

    <input type="hidden" name="resident_id" value="{{ session('resident_id') }}">

    <h3>{{ __('messages.otp_title') }}</h3>

    <input type="text" name="otp" placeholder="{{ __('messages.otp_placeholder') }}" required>

    <button type="submit">{{ __('messages.otp_verify_btn') }}</button>
</form>