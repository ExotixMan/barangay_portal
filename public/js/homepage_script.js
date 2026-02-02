// Homepage Script - Organized and Optimized
document.addEventListener('DOMContentLoaded', function() {
    // ===================================
    // NAVIGATION FUNCTIONALITY
    // ===================================
    
    // Set active nav link based on current page
    setActiveNavLink();
    
    // Mobile menu toggle
    initMobileMenu();
    
    // Mobile dropdown toggle
    initMobileDropdowns();
    
    // ===================================
    // SCROLL EFFECTS
    // ===================================
    
    // Navbar scroll effect and back to top button
    initScrollEffects();
    
    // Back to top button click
    initBackToTop();
    
    // ===================================
    // CAROUSEL FUNCTIONALITY
    // ===================================
    
    // Announcement and events carousel
    initCarousels();
    
    // ===================================
    // CARD ANIMATIONS
    // ===================================
    
    // Card hover effects
    initCardEffects();
    
    // Quick card staggered animations
    initQuickCardAnimations();
});

// ===================================
// FUNCTION DEFINITIONS
// ===================================

function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'homepage.html';
    const navLinks = document.querySelectorAll('.nav-link');
    const dropdownLinks = document.querySelectorAll('.dropdown-link');
    
    // Remove active class from all links
    navLinks.forEach(link => link.classList.remove('active'));
    dropdownLinks.forEach(link => link.classList.remove('active'));
    
    // Check dropdown links first (for subpages like history.html)
    dropdownLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
            // Also mark parent dropdown as active
            const parentDropdown = link.closest('.nav-dropdown');
            if (parentDropdown) {
                const parentLink = parentDropdown.querySelector('.nav-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
    });
    
    // Check main nav links
    navLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
        }
    });
    
    // Default: set Home as active if no other page is detected
    if (!document.querySelector('.nav-link.active')) {
        const homeLink = document.querySelector('a[href="homepage.html"]') || 
                        document.querySelector('a[href="index.html"]') ||
                        document.querySelector('a[href="#"]') && window.location.pathname === '/';
        if (homeLink) {
            homeLink.classList.add('active');
        }
    }
}

function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMain = document.querySelector('.nav-main');
    
    if (mobileMenuBtn && navMain) {
        mobileMenuBtn.addEventListener('click', function() {
            navMain.classList.toggle('active');
            this.innerHTML = navMain.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            if (navMain && mobileMenuBtn && !navMain.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navMain.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                
                // Close all dropdowns
                const dropdownParents = document.querySelectorAll('.nav-dropdown');
                dropdownParents.forEach(item => {
                    item.classList.remove('active');
                });
            }
        }
    });
}

function initMobileDropdowns() {
    const dropdownParents = document.querySelectorAll('.nav-dropdown');
    
    dropdownParents.forEach(dropdown => {
        const link = dropdown.querySelector('.nav-link');
        
        if (link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    dropdownParents.forEach(item => {
                        if (item !== dropdown) {
                            item.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('active');
                }
            });
        }
    });
}

function initScrollEffects() {
    const navbar = document.querySelector('.navbar');
    const backToTopBtn = document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', function() {
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        if (backToTopBtn) {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        }
    });
}

function initBackToTop() {
    const backToTopBtn = document.querySelector('.back-to-top');
    
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

function initCarousels() {
    // Announcement carousel dots
    const announcementDots = document.querySelectorAll('.announcements-card .dot');
    
    announcementDots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            announcementDots.forEach(d => d.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Event carousel dots
    const eventDots = document.querySelectorAll('.events-card .dot');
    
    eventDots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            eventDots.forEach(d => d.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Announcement arrow buttons
    const announcementArrows = document.querySelectorAll('.announcements-card .arrow-btn');
    
    announcementArrows.forEach(arrow => {
        arrow.addEventListener('click', function() {
            const dots = this.parentElement.querySelectorAll('.dot');
            let activeIndex = Array.from(dots).findIndex(dot => dot.classList.contains('active'));
            
            if (this.innerHTML === '‹') {
                activeIndex = activeIndex > 0 ? activeIndex - 1 : dots.length - 1;
            } else {
                activeIndex = activeIndex < dots.length - 1 ? activeIndex + 1 : 0;
            }
            
            dots.forEach(dot => dot.classList.remove('active'));
            dots[activeIndex].classList.add('active');
        });
    });
    
    // Event arrow buttons
    const eventArrows = document.querySelectorAll('.events-card .arrow-btn');
    
    eventArrows.forEach(arrow => {
        arrow.addEventListener('click', function() {
            const dots = this.parentElement.querySelectorAll('.dot');
            let activeIndex = Array.from(dots).findIndex(dot => dot.classList.contains('active'));
            
            if (this.innerHTML === '‹') {
                activeIndex = activeIndex > 0 ? activeIndex - 1 : dots.length - 1;
            } else {
                activeIndex = activeIndex < dots.length - 1 ? activeIndex + 1 : 0;
            }
            
            dots.forEach(dot => dot.classList.remove('active'));
            dots[activeIndex].classList.add('active');
        });
    });
}

function initCardEffects() {
    const cards = document.querySelectorAll('.card-hover');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

function initQuickCardAnimations() {
    const quickCards = document.querySelectorAll('.quick-card');
    
    quickCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}