<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Hulo - Online Services Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/main.css">    
</head>
<body>
    <!-- Accessibility Tools -->
    <div class="accessibility-tools">
        <button class="text-size-btn" id="increase-text">A+</button>
        <button class="text-size-btn" id="decrease-text">A-</button>
        <button class="text-size-btn" id="high-contrast-toggle">High Contrast</button>
    </div>

    <!-- Header Section -->
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="Images/logo.jpg" alt="Barangay Hulo Logo" class="logo-image">
                <div class="logo-text">Barangay Hulong Duhat Portal</div>
            </div>
            
            <nav>
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="#">Track Request</a></li>
                    <li><a href="#">Announcements</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
            
            <div class="user-actions">
                <a href="{{ route('login') }}" style="text-decoration: none;"><button class="btn btn-outline">Login</button></a>
                <a href="{{ route('register') }}" style="text-decoration: none;"><button class="btn btn-primary">Register</button></a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Hero Section -->
        <section class="hero">
            <h1>Welcome to Barangay Hulo Online Services</h1>
            <p>Access barangay services anytime, anywhere. Request certificates, file complaints, and track your requests online with our convenient portal.</p>
            <button class="btn btn-primary">Get Started</button>
        </section>

<!-- Services Section -->
<h2 style="margin-bottom: 1.5rem; color: var(--primary); text-align: center;">Our Services</h2>
<div class="services-grid">
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-certificate"></i>
        </div>
        <div class="service-content">
            <h3>Barangay Clearance</h3>
            <p>Request your barangay clearance online for employment, business permits, and other requirements.</p>
            <a href="services/clearance.html" class="btn btn-primary">Request Now</a>
        </div>
    </div>
    
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-hand-holding-heart"></i>
        </div>
        <div class="service-content">
            <h3>Indigency Certificate</h3>
            <p>Apply for a certificate of indigency to access government assistance programs and benefits.</p>
            <a href="services/indigency.html" class="btn btn-primary">Request Now</a>
        </div>
    </div>
    
    <div class="service-card">
        <div class="service-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="service-content">
            <h3>Complaint/Blotter Report</h3>
            <p>File complaints or incident reports online for faster and more transparent handling.</p>
            <a href="services/complaint.html" class="btn btn-primary">File Report</a>
        </div>
    </div>
</div>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <h2>How to Use the Online Portal</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Create an Account</h3>
                    <p>Register with your personal information to verify your residency in Barangay Hulo.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Request a Service</h3>
                    <p>Choose the service you need and fill out the required information in our online forms.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Track Your Request</h3>
                    <p>Monitor the status of your request through your personal dashboard.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Receive Certificate</h3>
                    <p>Get notified via email when your document is ready for pickup or download.</p>
                </div>
            </div>
        </section>

        <!-- Announcements Section -->
        <section class="announcements">
            <h2>Latest Announcements</h2>
            
            <div class="announcement-item">
                <div class="announcement-date"><i class="far fa-calendar-alt"></i> October 15, 2023</div>
                <h3>New Online Portal Launch</h3>
                <p>Barangay Hulo is proud to announce the launch of our new online services portal. Residents can now request documents and file reports online.</p>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-date"><i class="far fa-calendar-alt"></i> October 10, 2023</div>
                <h3>Community Clean-up Drive</h3>
                <p>Join us on October 20 for our monthly community clean-up drive. Meet at the barangay hall at 7:00 AM.</p>
            </div>
            
            <div class="announcement-item">
                <div class="announcement-date"><i class="far fa-calendar-alt"></i> October 5, 2023</div>
                <h3>Health Center Schedule</h3>
                <p>The barangay health center will have extended hours every Wednesday for free blood pressure monitoring.</p>
            </div>
        </section>
    </main>

<!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Barangay Hulo</h3>
                <p>City of Malabon, Philippines</p>
                <p>Working towards a better community through efficient and transparent services.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="#">Track Request</a></li>
                    <li><a href="#">Announcements</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact Information</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>1 M. Blas St, Malabon, 1471 Metro Manila</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>(02) 123-4567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@barangayhulo.gov.ph</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2025 Barangay Hulomg Duhat, Malabon City. All rights reserved.</p>
        </div>
    </footer>
    <script src="/js/main.js"></script>
</body>
</html>