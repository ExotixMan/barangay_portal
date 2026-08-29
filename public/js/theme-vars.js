(function () {
    'use strict';

    const root = document.documentElement;
    const primary = document.querySelector('meta[name="app-theme-primary"]')?.content || '#d32f2f';
    const secondary = document.querySelector('meta[name="app-theme-secondary"]')?.content || '#b71c1c';

    root.style.setProperty('--saved-theme-primary', primary);
    root.style.setProperty('--saved-theme-secondary', secondary);
    root.style.setProperty('--bs-primary', primary);
    root.style.setProperty('--bs-link-color', primary);
    root.style.setProperty('--bs-link-hover-color', secondary);
})();
