(function () {
    'use strict';

    try {
        const savedTheme = localStorage.getItem('bisig-admin-theme');
        const systemDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
        const theme = ['dark', 'light'].includes(savedTheme)
            ? savedTheme
            : (systemDark ? 'dark' : 'light');
        const allowedFontSizes = ['small', 'normal', 'large', 'x-large'];
        const savedFontSize = localStorage.getItem('bisig-admin-font-size');

        document.documentElement.dataset.theme = theme;
        document.documentElement.classList.toggle('dark-mode', theme === 'dark');
        document.documentElement.style.colorScheme = theme;
        document.documentElement.dataset.fontSize = allowedFontSizes.includes(savedFontSize)
            ? savedFontSize
            : 'normal';
    } catch (error) {
        document.documentElement.dataset.theme = 'light';
        document.documentElement.dataset.fontSize = 'normal';
    }
})();
