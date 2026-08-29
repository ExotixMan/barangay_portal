<footer class="bhd-footer">
    <div class="bhd-shell">
        <div class="bhd-footer-grid">
            <div>
                <div class="bhd-footer-brand">
                    <span class="bhd-brand-mark">
                        <img src="{{ asset('Images/logo.png') }}" alt="{{ __('messages.blade_login_blade_barangay_logo') }}">
                    </span>

                    <div>
                        <strong>{{ __('messages.footer_brand_name') }}</strong>
                        <p>{{ __('messages.blade_public_footer_blade_official_resident_service_portal_for_requests_updates_e') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h4>{{ __('messages.services') }}</h4>
                <a href="{{ route('clearance') }}">{{ __('messages.nav_barangay_clearance') }}</a>
                <a href="{{ route('residency') }}">{{ __('messages.nav_certificate_residency') }}</a>
                <a href="{{ route('indigency') }}">{{ __('messages.nav_certificate_indigency') }}</a>
                <a href="{{ route('track_request') }}">{{ __('messages.nav_track_request') }}</a>
            </div>

            <div>
                <h4>{{ __('messages.nav_brand_part1') }}</h4>
                <a href="{{ route('history') }}">{{ __('messages.nav_history') }}</a>
                <a href="{{ route('mission_vision') }}">{{ __('messages.mv_hero_title') }}</a>
                <a href="{{ route('officials') }}">{{ __('messages.blade_history_blade_officials') }}</a>
                <a href="{{ route('map') }}">{{ __('messages.nav_barangay_map') }}</a>
            </div>

            <div class="bhd-footer-contact-col">
                <h4>{{ __('messages.nav_contact') }}</h4>

                <div class="bhd-footer-contact">
                    <div class="bhd-contact-item">
                        <i class="fas fa-location-dot"></i>
                        <span>{{ __('messages.blade_contacts_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city_metro_manil') }}</span>
                    </div>

                    <div class="bhd-contact-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:82811373">8-281-1373</a>
                    </div>

                    <div class="bhd-contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:barangayhulongduhat@gmail.com">{{ __('messages.blade_contacts_blade_barangayhulongduhat_gmail_com') }}</a>
                    </div>

                    <div class="bhd-contact-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ __('messages.footer_hours') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bhd-footer-bottom">
            <span>© {{ now()->year }} Barangay Hulong Duhat. All rights reserved.</span>
            <span>{{ __('messages.blade_public_footer_blade_open_mon_fri_8_00_am_5_00_pm') }}</span>
        </div>
    </div>
</footer>
