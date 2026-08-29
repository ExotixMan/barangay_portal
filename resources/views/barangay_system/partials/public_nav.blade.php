<header class="bhd-nav">
    <div class="bhd-shell bhd-nav-inner">
        <a href="{{ route('barangay_system.index') }}" class="bhd-brand" aria-label="{{ __('messages.blade_public_nav_blade_barangay_hulong_duhat_home') }}">
            <span class="bhd-brand-mark">
                <img src="{{ asset('Images/logo.png') }}" alt="{{ __('messages.blade_login_blade_barangay_logo') }}">
            </span>

            <span>
                <strong>{{ __('messages.nav_brand_part1') }}</strong>
                <span>{{ __('messages.nav_brand_part2') }} {{ __('messages.nav_portal') }}</span>
            </span>
        </a>

        <nav class="bhd-links" aria-label="{{ __('messages.blade_public_nav_blade_primary_navigation') }}">
            <a href="{{ route('barangay_system.index') }}" class="{{ $activePage === 'home' ? 'active' : '' }}">
                <i class="fas fa-home"></i> {{ __('messages.nav_home') }}
            </a>

            <div class="bhd-drop">
                <button type="button" class="{{ in_array($activePage, ['history', 'mission_vision', 'officials', 'map']) ? 'active' : '' }}">
                    <i class="fas fa-circle-info"></i>
                    {{ __('messages.nav_about') }}
                    <i class="fas fa-chevron-down small"></i>
                </button>

                <div class="bhd-drop-menu">
                    <a href="{{ route('history') }}" class="{{ $activePage === 'history' ? 'active' : '' }}"><i class="fas fa-history"></i> {{ __('messages.nav_history') }}</a>
                    <a href="{{ route('mission_vision') }}" class="{{ $activePage === 'mission_vision' ? 'active' : '' }}"><i class="fas fa-bullseye"></i> {{ __('messages.nav_mission_vision') }}</a>
                    <a href="{{ route('officials') }}" class="{{ $activePage === 'officials' ? 'active' : '' }}"><i class="fas fa-users"></i> {{ __('messages.nav_barangay_officials') }}</a>
                    <a href="{{ route('map') }}" class="{{ $activePage === 'map' ? 'active' : '' }}"><i class="fas fa-map"></i> {{ __('messages.nav_barangay_map') }}</a>
                </div>
            </div>

            <div class="bhd-drop">
                <button type="button" class="{{ in_array($activePage, ['services', 'clearance', 'residency', 'indigency']) ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    {{ __('messages.nav_services_dropdown') }}
                    <i class="fas fa-chevron-down small"></i>
                </button>

                <div class="bhd-drop-menu">
                    <a href="{{ route('services') }}" class="{{ $activePage === 'services' ? 'active' : '' }}"><i class="fas fa-list"></i> {{ __('messages.nav_all_services') }}</a>
                    <a href="{{ route('clearance') }}" class="{{ $activePage === 'clearance' ? 'active' : '' }}"><i class="fas fa-certificate"></i> {{ __('messages.nav_barangay_clearance') }}</a>
                    <a href="{{ route('residency') }}" class="{{ $activePage === 'residency' ? 'active' : '' }}"><i class="fas fa-house-user"></i> {{ __('messages.nav_certificate_residency') }}</a>
                    <a href="{{ route('indigency') }}" class="{{ $activePage === 'indigency' ? 'active' : '' }}"><i class="fas fa-hands-helping"></i> {{ __('messages.nav_certificate_indigency') }}</a>
                </div>
            </div>

            <div class="bhd-drop">
                <button type="button" class="{{ in_array($activePage, ['announcements', 'events']) ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    {{ __('messages.blade_public_nav_blade_updates') }}
                    <i class="fas fa-chevron-down small"></i>
                </button>

                <div class="bhd-drop-menu">
                    <a href="{{ route('announcements') }}" class="{{ $activePage === 'announcements' ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i> {{ __('messages.nav_announcements') }}
                    </a>

                    <a href="{{ route('events_project') }}">
                        <i class="fas fa-calendar-days"></i> {{ __('messages.nav_events_projects') }}
                    </a>
                </div>
            </div>

            <a href="{{ route('incident') }}" class="{{ $activePage === 'incident' ? 'active' : '' }}">
                <i class="fas fa-triangle-exclamation"></i> {{ __('messages.nav_report_dropdown') }}
            </a>

            <a href="{{ route('contacts') }}" class="{{ $activePage === 'contacts' ? 'active' : '' }}">
                <i class="fas fa-phone"></i> {{ __('messages.nav_contact') }}
            </a>
        </nav>

        <div class="bhd-nav-actions">
            <a href="{{ route('track_request') }}" class="bhd-btn bhd-btn-line">
                <i class="fas fa-magnifying-glass"></i> {{ __('messages.nav_track_request') }}
            </a>

            @auth
                <div class="bhd-drop">
                    <button type="button" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::user()->name ?? Auth::user()->firstname ?? 'User' }}
                        <i class="fas fa-chevron-down small"></i>
                    </button>

                    <div class="bhd-drop-menu bhd-user-menu">
                        <a href="{{ route('profile') }}" class="{{ $activePage === 'profile' ? 'active' : '' }}">
                            <i class="fas fa-id-card"></i> {{ __('messages.nav_my_profile') }}
                        </a>
                        <a href="{{ route('logout.res') }}" class="{{ $activePage === 'logout' ? 'active' : '' }}">
                            <i class="fas fa-right-from-bracket"></i> {{ __('messages.nav_logout') }}
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login.res') }}" class="bhd-btn bhd-btn-primary">
                    <i class="fas fa-right-to-bracket"></i> {{ __('messages.nav_login') }}
                </a>
            @endauth
        </div>

        <button class="bhd-mobile-toggle" type="button" id="bhdMenuBtn" aria-label="{{ __('messages.blade_public_nav_blade_open_navigation_menu') }}" aria-expanded="false" aria-controls="bhdMobilePanel">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="bhd-mobile-panel" id="bhdMobilePanel">
        <div class="bhd-shell bhd-mobile-menu">
            <a href="{{ route('barangay_system.index') }}" class="bhd-mobile-direct">
                <i class="fas fa-home"></i>
                <span>{{ __('messages.nav_home') }}</span>
            </a>

            <a href="{{ route('track_request') }}" class="bhd-mobile-direct primary">
                <i class="fas fa-magnifying-glass"></i>
                <span>{{ __('messages.nav_track_request') }}</span>
            </a>

            <details class="bhd-mobile-dropdown">
                <summary>
                    <span><i class="fas fa-folder-open"></i> {{ __('messages.nav_services_dropdown') }}</span>
                    <i class="fas fa-chevron-down bhd-dropdown-chevron"></i>
                </summary>

                <div class="bhd-mobile-submenu">
                    <a href="{{ route('services') }}"><i class="fas fa-list"></i> {{ __('messages.nav_all_services') }}</a>
                    <a href="{{ route('clearance') }}"><i class="fas fa-certificate"></i> {{ __('messages.nav_barangay_clearance') }}</a>
                    <a href="{{ route('residency') }}"><i class="fas fa-house-user"></i> {{ __('messages.nav_certificate_residency') }}</a>
                    <a href="{{ route('indigency') }}"><i class="fas fa-hands-helping"></i> {{ __('messages.nav_certificate_indigency') }}</a>
                    <a href="{{ route('incident') }}"><i class="fas fa-triangle-exclamation"></i> {{ __('messages.nav_blotter_report') }}</a>
                </div>
            </details>

            <details class="bhd-mobile-dropdown">
                <summary>
                    <span><i class="fas fa-circle-info"></i> {{ __('messages.nav_about') }}</span>
                    <i class="fas fa-chevron-down bhd-dropdown-chevron"></i>
                </summary>

                <div class="bhd-mobile-submenu">
                    <a href="{{ route('history') }}"><i class="fas fa-history"></i> {{ __('messages.nav_history') }}</a>
                    <a href="{{ route('mission_vision') }}"><i class="fas fa-bullseye"></i> {{ __('messages.nav_mission_vision') }}</a>
                    <a href="{{ route('officials') }}"><i class="fas fa-users"></i> {{ __('messages.nav_barangay_officials') }}</a>
                    <a href="{{ route('map') }}"><i class="fas fa-map"></i> {{ __('messages.nav_barangay_map') }}</a>
                </div>
            </details>

            <details class="bhd-mobile-dropdown">
                <summary>
                    <span><i class="fas fa-bullhorn"></i> {{ __('messages.blade_public_nav_blade_updates') }}</span>
                    <i class="fas fa-chevron-down bhd-dropdown-chevron"></i>
                </summary>

                <div class="bhd-mobile-submenu">
                    <a href="{{ route('announcements') }}"><i class="fas fa-bullhorn"></i> {{ __('messages.nav_announcements') }}</a>
                    <a href="{{ route('events_project') }}"><i class="fas fa-calendar-days"></i> {{ __('messages.nav_events_projects') }}</a>
                    <a href="{{ route('contacts') }}"><i class="fas fa-phone"></i> {{ __('messages.nav_contact') }}</a>
                </div>
            </details>

            <details class="bhd-mobile-dropdown">
                <summary>
                    <span><i class="fas fa-user-circle"></i> {{ __('messages.blade_public_nav_blade_account') }}</span>
                    <i class="fas fa-chevron-down bhd-dropdown-chevron"></i>
                </summary>

                <div class="bhd-mobile-submenu">
                    @auth
                        <a href="{{ route('profile') }}"><i class="fas fa-id-card"></i> {{ __('messages.nav_my_profile') }}</a>
                        <a href="{{ route('logout.res') }}"><i class="fas fa-right-from-bracket"></i> {{ __('messages.nav_logout') }}</a>
                    @else
                        <a href="{{ route('login.res') }}"><i class="fas fa-right-to-bracket"></i> {{ __('messages.nav_login') }}</a>
                    @endauth
                </div>
            </details>
        </div>
    </div>
</header>
