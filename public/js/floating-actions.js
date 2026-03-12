// Existing code remains the same until the dark mode section
initBackToTop();
initFloatingActionButton();
initChatModal();

const backToTopBtn = document.getElementById('backToTop');
const body = document.body;

window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
        backToTopBtn.classList.add('visible');
        body.classList.add('back-to-top-visible');
    } else {
        backToTopBtn.classList.remove('visible');
        body.classList.remove('back-to-top-visible');
    }
});

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

function initFloatingActionButton() {
    const fabMain = document.getElementById('fabMain');
    const speedDial = document.getElementById('speedDial');
    const darkModeBtn = document.getElementById('darkModeBtn');
    const translateBtn = document.getElementById('translateBtn');
    const chatBtn = document.getElementById('chatBtn');
    
    let isSpeedDialOpen = false;
    
    // Check initial dark mode state from localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (darkModeBtn) {
        if (isDarkMode) {
            darkModeBtn.innerHTML = '<i class="fas fa-sun"></i>';
            darkModeBtn.title = 'Toggle Light Mode';
        } else {
            darkModeBtn.innerHTML = '<i class="fas fa-moon"></i>';
            darkModeBtn.title = 'Toggle Dark Mode';
        }
    }
    
    // Toggle speed dial
    if (fabMain && speedDial) {
        fabMain.addEventListener('click', function(event) {
            event.stopPropagation();
            isSpeedDialOpen = !isSpeedDialOpen;
            
            if (isSpeedDialOpen) {
                speedDial.classList.add('active');
                fabMain.classList.add('active');
                fabMain.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                speedDial.classList.remove('active');
                fabMain.classList.remove('active');
                fabMain.innerHTML = '<i class="fas fa-gear"></i>';
            }
        });
    }
    
    // Dark/Light mode toggle - UPDATED to use the global functions
    if (darkModeBtn) {
        darkModeBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            
            // Use the global toggle function from dark-mode.js
            if (window.toggleDarkMode) {
                window.toggleDarkMode();
            } else {
                // Fallback if dark-mode.js hasn't loaded
                const isDark = !document.body.classList.contains('dark-mode');
                if (isDark) {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'true');
                    darkModeBtn.innerHTML = '<i class="fas fa-sun"></i>';
                    darkModeBtn.title = 'Toggle Light Mode';
                } else {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'false');
                    darkModeBtn.innerHTML = '<i class="fas fa-moon"></i>';
                    darkModeBtn.title = 'Toggle Dark Mode';
                }
            }
            
            // Close speed dial after clicking
            closeSpeedDial();
        });
    }
    
    // Translation button
    if (translateBtn) {
        translateBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            
            /// Detect current language
            const currentLang = document.documentElement.lang || 'en';

            // Toggle language
            const newLang = currentLang === 'en' ? 'tl' : 'en';

            // Redirect (NO Blade syntax)
            window.location.href = "/switch-language?lang=" + newLang;
            
            closeSpeedDial();
        });
    }
    
    // Chat modal functionality
    if (chatBtn) {
        chatBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            console.log('Chat button clicked');
            
            // Open chat modal
            const chatModal = document.getElementById('chatModal');
            if (chatModal) {
                console.log('Modal found, adding active class');
                chatModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('Chat modal not found!');
            }
            
            // Close speed dial after clicking
            closeSpeedDial();
        });
    }
    
    // Close speed dial when clicking outside
    document.addEventListener('click', function(event) {
        if (isSpeedDialOpen && 
            !fabMain.contains(event.target) && 
            !speedDial.contains(event.target)) {
            closeSpeedDial();
        }
    });
    
    // Helper function to close speed dial
    function closeSpeedDial() {
        if (speedDial) {
            speedDial.classList.remove('active');
        }
        if (fabMain) {
            fabMain.classList.remove('active');
            fabMain.innerHTML = '<i class="fas fa-gear"></i>';
        }
        isSpeedDialOpen = false;
    }
}

function initChatModal() {
    const chatModal = document.getElementById('chatModal');
    const closeChatBtn = document.getElementById('closeChat');
    
    // Close modal when clicking close button
    if (closeChatBtn) {
        console.log('Adding click listener to close button');
        closeChatBtn.addEventListener('click', function(event) {
            console.log('Close button clicked');
            event.stopPropagation();
            chatModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    } else {
        console.error('Close button not found!');
    }
    
    // Close modal when clicking outside content
    chatModal.addEventListener('click', function(event) {
        console.log('Modal clicked, target:', event.target);
        console.log('Is modal itself?', event.target === chatModal);
        
        if (event.target === chatModal) {
            console.log('Closing modal (clicked outside)');
            chatModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        console.log('Key pressed:', event.key);
        if (event.key === 'Escape' && chatModal.classList.contains('active')) {
            console.log('Closing modal (Escape key)');
            chatModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}