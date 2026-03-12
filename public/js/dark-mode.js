// Dark Mode Persistence
(function() {
    // Check for saved dark mode preference
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    
    // Apply dark mode on page load
    if (savedDarkMode) {
        enableDarkMode();
    }
    
    // Function to enable dark mode
    window.enableDarkMode = function() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'true');
        
        // Update dark mode button if it exists
        updateDarkModeButton(true);
        
        // Dispatch event for other scripts
        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDark: true } }));
    };
    
    // Function to disable dark mode
    window.disableDarkMode = function() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'false');
        
        // Update dark mode button if it exists
        updateDarkModeButton(false);
        
        // Dispatch event for other scripts
        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDark: false } }));
    };
    
    // Function to toggle dark mode
    window.toggleDarkMode = function() {
        if (document.body.classList.contains('dark-mode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    };
    
    // Helper function to update dark mode button
    function updateDarkModeButton(isDark) {
        const darkModeBtn = document.getElementById('darkModeBtn');
        if (darkModeBtn) {
            if (isDark) {
                darkModeBtn.innerHTML = '<i class="fas fa-sun"></i>';
                darkModeBtn.title = 'Toggle Light Mode';
            } else {
                darkModeBtn.innerHTML = '<i class="fas fa-moon"></i>';
                darkModeBtn.title = 'Toggle Dark Mode';
            }
        }
    }
    
    // Listen for dark mode button clicks (will be triggered from floating-actions.js)
    document.addEventListener('darkModeToggle', function() {
        toggleDarkMode();
    });
})();