<!-- Floating Action Button with Speed Dial -->
<div class="fab-container">
    <div class="speed-dial" id="speedDial">
        <button class="fab-action" id="translateBtn" title="{{ __('messages.translate_text') }}">
            @if(app()->getLocale() == 'en')
                <span>Filipino</span>
            @else
                <span>English</span>
            @endif
        </button>
        <button class="fab-action" id="darkModeBtn" title="{{ __('messages.toggle_dark') }}">
            <i class="fas fa-moon"></i>
        </button>
        <button class="fab-action" id="chatBtn" title="{{ __('messages.chat_assistant') }}">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>
    <button class="fab-main" id="fabMain">
        <i class="fas fa-gear"></i>
    </button>
</div>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="{{ __('messages.back_to_top') }}">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Footer Section -->
<footer>
    <div class="container footer-container">
        <div class="row">
            <!-- Logo & Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="logo-circle">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div class="logo-text">
                            <h3>Barangay Hulo</h3>
                            <p class="tagline">Serving Our Community</p>
                        </div>
                    </div>

                    <div class="contact-info-simple">
                        <div class="contact-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>1 M. Blas St, Barangay Hulong Duhat, Malabon City, Metro Manila</span>
                        </div>
                        <div class="contact-row">
                            <i class="fas fa-phone"></i>
                            <a href="tel:82811373">8-281-1373</a>
                        </div>
                        <div class="contact-row">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:barangayhulongduhat@gmail.com">barangayhulongduhat@gmail.com</a>
                        </div>
                        <div class="contact-row">
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri: 8:00 AM - 5:00 PM</span>
                        </div>
                    </div>

                    <div class="social-links-simple">
                        <div class="social-icons">
                            <a href="https://www.facebook.com/BarangayHulongDuhatOfficial" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="mailto:barangayhulongduhat@gmail.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <h3>Quick Access</h3>
                    <div class="footer-links-list">
                        <a href="{{ route('barangay_system.index') }}" class="footer-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="{{ route('announcements') }}" class="footer-link">
                            <i class="fas fa-bullhorn"></i> Announcements
                        </a>
                        <a href="{{ route('history') }}" class="footer-link">
                            <i class="fas fa-history"></i> Barangay History
                        </a>
                        <a href="{{ route('track_request') }}" class="footer-link">
                            <i class="fas fa-search"></i> Track Request
                        </a>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <h3>Services</h3>
                    <div class="footer-links-list">
                        <a href="{{ route('clearance') }}" class="footer-link">
                            <i class="fas fa-certificate"></i> Barangay Clearance
                        </a>
                        <a href="{{ route('residency') }}" class="footer-link">
                            <i class="fas fa-house-user"></i> Certificate of Residency
                        </a>
                        <a href="{{ route('indigency') }}" class="footer-link">
                            <i class="fas fa-hands-helping"></i> Certificate of Indigency
                        </a>
                        <a href="{{ route('incident') }}" class="footer-link">
                            <i class="fas fa-clipboard-list"></i> Incident Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Emergency & Support -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <h3>Emergency Contacts</h3>
                    <div class="emergency-contacts-simple">
                        <div class="emergency-item">
                            <i class="fas fa-ambulance"></i>
                            <div class="emergency-details">
                                <span class="emergency-label">Emergency</span>
                                <a href="tel:911" class="emergency-number">911</a>
                            </div>
                        </div>
                        <div class="emergency-item">
                            <i class="fas fa-shield-alt"></i>
                            <div class="emergency-details">
                                <span class="emergency-label">Police</span>
                                <a href="tel:0285550102" class="emergency-number">02-8555-0102</a>
                            </div>
                        </div>
                        <div class="emergency-item">
                            <i class="fas fa-first-aid"></i>
                            <div class="emergency-details">
                                <span class="emergency-label">Health Center</span>
                                <a href="tel:0232135043" class="emergency-number">02-3213-5043</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom-container">
            <div class="copyright-info">
                <p>&copy; 2026 Barangay Hulo, Malabon City. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
