(function () {
    function initBootstrapDropdowns() {
        const toggles = Array.from(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        if (!toggles.length) return;

        if (window.bootstrap && window.bootstrap.Dropdown) {
            toggles.forEach(function (toggle) {
                try {
                    const existing = window.bootstrap.Dropdown.getInstance(toggle);
                    if (existing) existing.dispose();

                    window.bootstrap.Dropdown.getOrCreateInstance(toggle, {
                        boundary: 'viewport',
                        popperConfig: function (defaultConfig) {
                            return Object.assign({}, defaultConfig, {
                                strategy: 'fixed'
                            });
                        }
                    });
                } catch (error) {
                    console.warn('Dropdown initialization failed for one control.', error);
                }
            });
            return;
        }

        // Safe fallback when the Bootstrap CDN is blocked/unavailable.
        toggles.forEach(function (toggle) {
            if (toggle.dataset.dropdownFallbackBound === '1') return;
            toggle.dataset.dropdownFallbackBound = '1';

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const menu = toggle.parentElement && toggle.parentElement.querySelector(':scope > .dropdown-menu');
                if (!menu) return;

                document.querySelectorAll('.dropdown-menu.show').forEach(function (openMenu) {
                    if (openMenu !== menu) openMenu.classList.remove('show');
                });
                document.querySelectorAll('[data-bs-toggle="dropdown"][aria-expanded="true"]').forEach(function (openToggle) {
                    if (openToggle !== toggle) openToggle.setAttribute('aria-expanded', 'false');
                });

                const opening = !menu.classList.contains('show');
                menu.classList.toggle('show', opening);
                toggle.setAttribute('aria-expanded', opening ? 'true' : 'false');

                if (opening) {
                    const rect = toggle.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.zIndex = '1085';
                    menu.style.top = Math.min(rect.bottom + 4, window.innerHeight - Math.min(menu.scrollHeight || 240, 320) - 8) + 'px';
                    menu.style.right = Math.max(8, window.innerWidth - rect.right) + 'px';
                    menu.style.left = 'auto';
                }
            });
        });

        if (!document.documentElement.dataset.dropdownFallbackDocumentBound) {
            document.documentElement.dataset.dropdownFallbackDocumentBound = '1';
            document.addEventListener('click', function (event) {
                if (event.target.closest('.dropdown')) return;
                document.querySelectorAll('.dropdown-menu.show').forEach(function (menu) {
                    menu.classList.remove('show');
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.right = '';
                    menu.style.left = '';
                });
                document.querySelectorAll('[data-bs-toggle="dropdown"][aria-expanded="true"]').forEach(function (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBootstrapDropdowns);
    } else {
        initBootstrapDropdowns();
    }

    document.addEventListener('shown.bs.modal', initBootstrapDropdowns);
})();
