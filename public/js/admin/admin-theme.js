(function () {
    'use strict';

    const STORAGE_KEY = 'bisig-admin-theme';
    const DARK = 'dark';
    const LIGHT = 'light';
    const root = document.documentElement;
    const systemTheme = window.matchMedia
        ? window.matchMedia('(prefers-color-scheme: dark)')
        : null;

    function savedTheme() {
        try {
            const value = window.localStorage.getItem(STORAGE_KEY);
            return value === DARK || value === LIGHT ? value : null;
        } catch (error) {
            return null;
        }
    }

    function currentTheme() {
        const value = root.dataset.theme;
        if (value === DARK || value === LIGHT) {
            return value;
        }

        return savedTheme() || (systemTheme && systemTheme.matches ? DARK : LIGHT);
    }

    function setThemeColor(theme) {
        const meta = document.getElementById('adminThemeColor');
        if (meta) {
            meta.setAttribute('content', theme === DARK ? '#080d18' : '#f8fafc');
        }
    }

    function updateToggle(theme) {
        const button = document.getElementById('adminThemeToggle');
        if (!button) {
            return;
        }

        const darkActive = theme === DARK;
        const icon = button.querySelector('[data-theme-icon]');
        const text = button.querySelector('[data-theme-text]');
        const nextLabel = darkActive ? 'Light mode' : 'Dark mode';

        button.setAttribute('aria-pressed', String(darkActive));
        button.setAttribute('aria-label', `Switch to ${nextLabel.toLowerCase()}`);
        button.setAttribute('title', `Switch to ${nextLabel.toLowerCase()}`);

        if (icon) {
            icon.className = darkActive ? 'fas fa-sun' : 'fas fa-moon';
        }

        if (text) {
            text.textContent = nextLabel;
        }
    }

    function chartInstances() {
        if (!window.Chart || !window.Chart.instances) {
            return [];
        }

        const instances = window.Chart.instances;

        if (typeof instances.values === 'function') {
            return Array.from(instances.values());
        }

        return Object.values(instances);
    }

    function ensureObject(parent, key) {
        if (!parent[key] || typeof parent[key] !== 'object') {
            parent[key] = {};
        }
        return parent[key];
    }

    function themeChart(chart, theme) {
        if (!chart || !chart.options) {
            return;
        }

        const darkActive = theme === DARK;
        const textColor = darkActive ? '#dce4ef' : '#475467';
        const titleColor = darkActive ? '#f7f9fc' : '#111827';
        const gridColor = darkActive ? 'rgba(148, 163, 184, .16)' : 'rgba(71, 84, 103, .12)';
        const tooltipBg = darkActive ? 'rgba(11, 18, 32, .96)' : 'rgba(17, 24, 39, .94)';
        const tooltipBorder = darkActive ? '#35435b' : 'rgba(255, 255, 255, .14)';

        const plugins = ensureObject(chart.options, 'plugins');
        const legend = ensureObject(plugins, 'legend');
        const labels = ensureObject(legend, 'labels');
        labels.color = textColor;

        const title = ensureObject(plugins, 'title');
        if (title.display) {
            title.color = titleColor;
        }

        const tooltip = ensureObject(plugins, 'tooltip');
        tooltip.backgroundColor = tooltipBg;
        tooltip.titleColor = '#ffffff';
        tooltip.bodyColor = darkActive ? '#e7edf6' : '#f8fafc';
        tooltip.borderColor = tooltipBorder;
        tooltip.borderWidth = 1;

        if (plugins.datalabels && typeof plugins.datalabels === 'object') {
            plugins.datalabels.color = textColor;
        }

        if (chart.options.scales && typeof chart.options.scales === 'object') {
            Object.values(chart.options.scales).forEach((scale) => {
                if (!scale || typeof scale !== 'object') {
                    return;
                }

                const ticks = ensureObject(scale, 'ticks');
                const grid = ensureObject(scale, 'grid');
                const border = ensureObject(scale, 'border');

                ticks.color = textColor;
                grid.color = gridColor;
                border.color = gridColor;

                if (scale.title && scale.title.display) {
                    scale.title.color = textColor;
                }
            });
        }

        try {
            chart.update('none');
        } catch (error) {
            try {
                chart.update();
            } catch (ignored) {
                // A chart can be in the middle of being destroyed during navigation.
            }
        }
    }

    function updateCharts(theme) {
        if (!window.Chart) {
            return;
        }

        const darkActive = theme === DARK;
        window.Chart.defaults.color = darkActive ? '#dce4ef' : '#475467';
        window.Chart.defaults.borderColor = darkActive
            ? 'rgba(148, 163, 184, .16)'
            : 'rgba(71, 84, 103, .12)';

        chartInstances().forEach((chart) => themeChart(chart, theme));
    }

    function persist(theme) {
        try {
            window.localStorage.setItem(STORAGE_KEY, theme);
        } catch (error) {
            // Local storage can be unavailable in private or restricted contexts.
        }
    }

    function applyTheme(theme, options) {
        const settings = Object.assign({ persist: false, announce: true }, options || {});
        const normalized = theme === DARK ? DARK : LIGHT;

        root.dataset.theme = normalized;
        root.setAttribute('data-bs-theme', normalized);
        root.classList.toggle('dark-mode', normalized === DARK);
        root.style.colorScheme = normalized;

        if (document.body) {
            document.body.dataset.theme = normalized;
            document.body.classList.toggle('dark-mode', normalized === DARK);
        }

        if (settings.persist) {
            persist(normalized);
        }

        setThemeColor(normalized);
        updateToggle(normalized);
        updateCharts(normalized);

        window.setTimeout(function () {
            updateCharts(normalized);
        }, 120);

        if (settings.announce) {
            document.dispatchEvent(new CustomEvent('admin:theme-changed', {
                detail: { theme: normalized }
            }));
        }
    }

    function toggleTheme() {
        applyTheme(currentTheme() === DARK ? LIGHT : DARK, {
            persist: true,
            announce: true
        });
    }

    function initialize() {
        applyTheme(currentTheme(), { persist: false, announce: false });

        const button = document.getElementById('adminThemeToggle');
        if (button && button.dataset.themeBound !== '1') {
            button.dataset.themeBound = '1';
            button.addEventListener('click', toggleTheme);
        }

        window.setTimeout(function () {
            updateCharts(currentTheme());
        }, 350);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
    } else {
        initialize();
    }

    window.addEventListener('load', function () {
        updateCharts(currentTheme());
    });

    if (systemTheme) {
        const handleSystemChange = function (event) {
            if (!savedTheme()) {
                applyTheme(event.matches ? DARK : LIGHT, {
                    persist: false,
                    announce: true
                });
            }
        };

        if (typeof systemTheme.addEventListener === 'function') {
            systemTheme.addEventListener('change', handleSystemChange);
        } else if (typeof systemTheme.addListener === 'function') {
            systemTheme.addListener(handleSystemChange);
        }
    }

    window.AdminTheme = Object.freeze({
        get: currentTheme,
        set: function (theme) {
            applyTheme(theme, { persist: true, announce: true });
        },
        toggle: toggleTheme,
        refreshCharts: function () {
            updateCharts(currentTheme());
        },
        clearSavedPreference: function () {
            try {
                window.localStorage.removeItem(STORAGE_KEY);
            } catch (error) {
                // Ignore restricted storage errors.
            }

            applyTheme(systemTheme && systemTheme.matches ? DARK : LIGHT, {
                persist: false,
                announce: true
            });
        }
    });
})();
