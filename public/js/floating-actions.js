initBackToTop();
initFloatingActionButton();

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

    if (darkModeBtn) {
        darkModeBtn.addEventListener('click', function(event) {
            event.stopPropagation();

            if (window.toggleDarkMode) {
                window.toggleDarkMode();
            } else {

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

            closeSpeedDial();
        });
    }

    if (translateBtn) {
        translateBtn.addEventListener('click', function(event) {
            event.stopPropagation();

            const currentLang = document.documentElement.lang || 'en';

            const newLang = currentLang === 'en' ? 'tl' : 'en';

            window.location.href = "/switch-language?lang=" + newLang;

            closeSpeedDial();
        });
    }

    const hasHuloBotEmbed = !!document.getElementById('hulobot-modal');
    if (chatBtn && !hasHuloBotEmbed) {
        chatBtn.addEventListener('click', function(event) {
            event.stopPropagation();

            const chatModal = document.getElementById('chatModal');
            if (chatModal) {
                chatModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            closeSpeedDial();
        });
    }

    document.addEventListener('click', function(event) {
        if (isSpeedDialOpen && fabMain && speedDial &&
            !fabMain.contains(event.target) &&
            !speedDial.contains(event.target)) {
            closeSpeedDial();
        }
    });

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
