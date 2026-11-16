(function() {
    const adminContext = document.currentScript?.dataset.themeContext === 'admin';
    const storageKey = adminContext ? 'bisig-admin-theme' : 'darkMode';

    function saveTheme(value) {
        try {
            localStorage.setItem(storageKey, adminContext ? (value === 'true' ? 'dark' : 'light') : value);
        } catch (error) {
            // Theme switching still works when browser storage is unavailable.
        }
    }

    function enableDarkMode() {
        document.body.classList.add('dark-mode');
        document.documentElement.style.colorScheme = 'dark';
        saveTheme('true');

        updateDarkModeButton(true);

        document.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { isDark: true } }));
    }

    function disableDarkMode() {
        document.body.classList.remove('dark-mode');
        document.documentElement.style.colorScheme = 'light';
        saveTheme('false');

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

    let savedDarkMode = false;
    try {
        savedDarkMode = localStorage.getItem(storageKey) === (adminContext ? 'dark' : 'true');
    } catch (error) {
        // Use the default light theme without persistent browser storage.
    }

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
