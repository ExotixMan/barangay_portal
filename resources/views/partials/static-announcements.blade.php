<!-- Static fallback announcements when no data is available -->
<div class="carousel-item active">
    <div class="announcement-grid">
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y') }}</div>
                <span class="announcement-badge important">Important</span>
            </div>
            <h3>Barangay Hall Schedule</h3>
            <p>The barangay hall will be open from 8:00 AM to 5:00 PM, Monday to Friday. Closed on weekends and holidays.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-1 day')) }}</div>
                <span class="announcement-badge health">Health</span>
            </div>
            <h3>Free Medical Mission</h3>
            <p>Free check-up and medicines this Saturday at the Barangay Health Center. First come, first served.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-2 days')) }}</div>
                <span class="announcement-badge services">Services</span>
            </div>
            <h3>Online Service Update</h3>
            <p>You can now request barangay clearances online through our new portal system.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <div class="announcement-grid">
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-3 days')) }}</div>
                <span class="announcement-badge infrastructure">Infrastructure</span>
            </div>
            <h3>Road Repair Schedule</h3>
            <p>Road repairs along M. Blas St. will begin next week. Please expect traffic delays.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-4 days')) }}</div>
                <span class="announcement-badge events">Community Event</span>
            </div>
            <h3>Barangay Assembly</h3>
            <p>Monthly barangay assembly this Friday at the covered court. All residents are encouraged to attend.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-5 days')) }}</div>
                <span class="announcement-badge important">Reminder</span>
            </div>
            <h3>Waste Segregation Reminder</h3>
            <p>Please follow proper waste segregation. Collection schedule: Mon/Wed/Fri for biodegradable, Tue/Thu for non-biodegradable.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="carousel-item">
    <div class="announcement-grid">
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-6 days')) }}</div>
                <span class="announcement-badge health">Health Advisory</span>
            </div>
            <h3>Dengue Prevention</h3>
            <p>Practice the 4S strategy: Search and destroy, Self-protection, Seek early consultation, Say yes to fogging.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-7 days')) }}</div>
                <span class="announcement-badge services">Holiday Schedule</span>
            </div>
            <h3>Holiday Office Hours</h3>
            <p>Please be advised of modified office hours for the upcoming holiday.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="announcement-item">
            <div class="announcement-header">
                <div class="date">{{ date('M d, Y', strtotime('-8 days')) }}</div>
                <span class="announcement-badge">General</span>
            </div>
            <h3>New Online Features</h3>
            <p>Check out our new website features including document tracking and online appointments.</p>
            <a href="#" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>