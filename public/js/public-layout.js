document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('bhdMenuBtn');
        const mobilePanel = document.getElementById('bhdMobilePanel');

        if (menuButton && mobilePanel) {
            const mobileDropdowns = mobilePanel.querySelectorAll('details.bhd-mobile-dropdown');

            menuButton.addEventListener('click', function () {
                const isOpen = mobilePanel.classList.toggle('open');
                menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                menuButton.innerHTML = isOpen
                    ? '<i class="fas fa-xmark"></i>'
                    : '<i class="fas fa-bars"></i>';

                if (!isOpen) {
                    mobileDropdowns.forEach(function (dropdown) {
                        dropdown.removeAttribute('open');
                    });
                }
            });

            mobileDropdowns.forEach(function (dropdown) {
                dropdown.addEventListener('toggle', function () {
                    if (dropdown.open) {
                        mobileDropdowns.forEach(function (otherDropdown) {
                            if (otherDropdown !== dropdown) {
                                otherDropdown.removeAttribute('open');
                            }
                        });
                    }
                });
            });

            mobilePanel.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mobilePanel.classList.remove('open');
                    menuButton.setAttribute('aria-expanded', 'false');
                    menuButton.innerHTML = '<i class="fas fa-bars"></i>';

                    mobileDropdowns.forEach(function (dropdown) {
                        dropdown.removeAttribute('open');
                    });
                });
            });
        }

        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (event) {
                const target = document.querySelector(anchor.getAttribute('href'));

                if (target) {
                    event.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
