(function() {
    function enableDarkMode() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'true');

        updateDarkModeButton(true);

        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDark: true } }));
    }

    function disableDarkMode() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'false');

        updateDarkModeButton(false);

        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDark: false } }));
    }

    function toggleDarkMode() {
        if (document.body.classList.contains('dark-mode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    }

    window.enableDarkMode = enableDarkMode;
    window.disableDarkMode = disableDarkMode;
    window.toggleDarkMode = toggleDarkMode;

    const savedDarkMode = localStorage.getItem('darkMode') === 'true';

    if (savedDarkMode) {
        enableDarkMode();
    }

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

    document.addEventListener('darkModeToggle', function() {
        toggleDarkMode();
    });
})();
