(function () {
    'use strict';

    function parseArgs(element, prefix) {
        const raw = element.dataset[prefix + 'Args'];
        if (!raw) return [];
        try {
            const value = JSON.parse(raw);
            return Array.isArray(value) ? value : [];
        } catch (error) {
            console.error('Invalid action arguments:', error);
            return [];
        }
    }

    function callAction(element, event, prefix) {
        const name = element.dataset[prefix + 'Call'];
        if (!name) return undefined;

        const fn = window[name];
        if (typeof fn !== 'function') {
            console.error(`Action function not found: ${name}`);
            return undefined;
        }

        const staticArgs = parseArgs(element, prefix);
        const signature = (element.dataset[prefix + 'Pass'] || 'args').split(',').map(value => value.trim()).filter(Boolean);
        const args = [];

        signature.forEach(function (part) {
            if (part === 'event') args.push(event);
            else if (part === 'self') args.push(element);
            else if (part === 'args') args.push(...staticArgs);
        });

        return fn(...args);
    }

    function preventWhenFalse(result, event) {
        if (result !== false) return;
        event.preventDefault();
        event.stopPropagation();
    }

    document.addEventListener('click', function (event) {
        const element = event.target.closest('[data-click-call], [data-confirm], [data-show-target], [data-hide-self], [data-browser-action], [data-submit-target], [data-stop-propagation]');
        if (!element) return;

        if (element.hasAttribute('data-stop-propagation')) {
            event.stopPropagation();
        }

        if (element.dataset.confirm && !window.confirm(element.dataset.confirm)) {
            event.preventDefault();
            event.stopPropagation();
            return;
        }

        if (element.dataset.showTarget) {
            const target = document.getElementById(element.dataset.showTarget);
            if (target) target.style.display = element.dataset.showDisplay || 'flex';
        }

        if (element.hasAttribute('data-hide-self')) {
            element.style.display = 'none';
        }

        if (element.dataset.browserAction === 'print') window.print();
        if (element.dataset.browserAction === 'close') window.close();

        if (element.dataset.submitTarget) {
            document.getElementById(element.dataset.submitTarget)?.submit();
        }

        preventWhenFalse(callAction(element, event, 'click'), event);
    });

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form');
        if (!form) return;

        if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) {
            event.preventDefault();
            return;
        }

        preventWhenFalse(callAction(form, event, 'submit'), event);
    });

    document.addEventListener('change', function (event) {
        const element = event.target;
        if (!(element instanceof Element)) return;

        if (element.hasAttribute('data-submit-parent-form')) {
            element.form?.submit();
        }

        preventWhenFalse(callAction(element, event, 'change'), event);
    });

    document.addEventListener('input', function (event) {
        const element = event.target;
        if (!(element instanceof HTMLInputElement)) return;

        if (element.dataset.inputSanitize === 'admin-search') {
            element.value = element.value.replace(/[^a-zA-Z0-9\s\-@.]/g, '');
        }

        preventWhenFalse(callAction(element, event, 'input'), event);
    });

    document.addEventListener('keypress', function (event) {
        const element = event.target;
        if (!(element instanceof HTMLInputElement)) return;

        if (element.hasAttribute('data-no-spaces') && event.key === ' ') {
            event.preventDefault();
        }
    });

    document.addEventListener('error', function (event) {
        const element = event.target;
        if (!(element instanceof HTMLImageElement)) return;

        if (element.dataset.errorAction === 'remove') {
            element.remove();
        } else if (element.dataset.errorAction === 'document-empty') {
            const replacement = document.createElement('div');
            replacement.className = 'document-empty';
            replacement.textContent = 'Image unavailable';
            element.replaceWith(replacement);
        }
    }, true);
})();
