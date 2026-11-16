const __cfg_profile_tabs = JSON.parse(document.getElementById('js-config-profile-tabs')?.dataset.jsConfig || '{}');

document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#profileTabs .bhd-profile-tab');
    const panels = document.querySelectorAll('.bhd-profile-panel');

    function activateTab(tabId, options = {}) {
        const { focus = false, reveal = false } = options;

        panels.forEach(panel => {
            panel.classList.remove('active');
            panel.hidden = true;
        });

        tabs.forEach(tab => {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
            tab.setAttribute('tabindex', '-1');
        });

        const targetPanel = document.getElementById('tab-' + tabId);
        const targetTab = document.querySelector('.bhd-profile-tab[data-tab="' + tabId + '"]');

        if (targetPanel) {
            targetPanel.hidden = false;
            targetPanel.classList.add('active');
        }

        if (targetTab) {
            targetTab.classList.add('active');
            targetTab.setAttribute('aria-selected', 'true');
            targetTab.setAttribute('tabindex', '0');

            if (focus) targetTab.focus({ preventScroll: true });

            if (reveal && window.innerWidth <= 575) {
                const behavior = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';
                targetTab.scrollIntoView({ behavior, block: 'nearest', inline: 'center' });
            }
        }
    }

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', function () {
            activateTab(tab.dataset.tab, { reveal: true });
        });

        tab.addEventListener('keydown', function (event) {
            let nextIndex = null;

            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') nextIndex = (index + 1) % tabs.length;
            if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') nextIndex = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = tabs.length - 1;

            if (nextIndex === null) return;

            event.preventDefault();
            const nextTab = tabs[nextIndex];
            activateTab(nextTab.dataset.tab, { focus: true, reveal: true });
        });
    });

    const initiallyActiveTab = document.querySelector('#profileTabs .bhd-profile-tab.active');
    if (initiallyActiveTab) activateTab(initiallyActiveTab.dataset.tab);

    if (__cfg_profile_tabs.initialTab) {
        activateTab(__cfg_profile_tabs.initialTab);
    }

    const correctionFieldSelect = document.getElementById('correctionFieldSelect');
    const correctionCurrentValuePreview = document.getElementById('correctionCurrentValuePreview');
    const correctionCurrentValueHidden = document.getElementById('correctionCurrentValueHidden');

    function syncCorrectionCurrentValue() {
        if (!correctionFieldSelect || !correctionCurrentValuePreview || !correctionCurrentValueHidden) return;

        const selectedOption = correctionFieldSelect.options[correctionFieldSelect.selectedIndex];
        const currentValue = selectedOption ? (selectedOption.dataset.current || '') : '';

        correctionCurrentValuePreview.value = currentValue || __cfg_profile_tabs.notAvailable;
        correctionCurrentValueHidden.value = currentValue;
    }

    if (correctionFieldSelect) {
        correctionFieldSelect.addEventListener('change', syncCorrectionCurrentValue);
        syncCorrectionCurrentValue();
    }

    const profileCorrectionCard = document.getElementById('profileCorrectionCard');
    const profileCorrectionToggle = document.getElementById('profileCorrectionToggle');
    const profileCorrectionBody = document.getElementById('profileCorrectionBody');

    if (profileCorrectionCard && profileCorrectionToggle && profileCorrectionBody) {
        profileCorrectionToggle.addEventListener('click', function () {
            const collapsed = profileCorrectionCard.classList.toggle('is-collapsed');
            profileCorrectionToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    }

    const profileCorrectionHistoryToggle = document.getElementById('profileCorrectionHistoryToggle');
    const profileCorrectionHistory = document.getElementById('profileCorrectionHistory');

    if (profileCorrectionHistoryToggle && profileCorrectionHistory) {
        profileCorrectionHistoryToggle.addEventListener('click', function () {
            const open = profileCorrectionHistory.classList.toggle('is-open');
            profileCorrectionHistoryToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    document.querySelectorAll('[data-history-target]').forEach(function (button) {
        button.addEventListener('click', function () {
            const detail = document.getElementById(button.getAttribute('data-history-target'));
            if (!detail) return;

            const open = detail.classList.toggle('is-open');
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    if (__cfg_profile_tabs.showCorrectionModal) {
        const correctionModalElement = document.getElementById('profileCorrectionModal');
        if (correctionModalElement && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(correctionModalElement).show();
        }
    }

    const quickInput = document.getElementById('quickPhotoInput');
    const quickForm = document.getElementById('quickPhotoForm');

    const quickPhotoTrigger = document.querySelector('label[for="quickPhotoInput"]');

    if (quickPhotoTrigger && quickInput) {
        quickPhotoTrigger.addEventListener('keydown', function (event) {
            if (event.key !== 'Enter' && event.key !== ' ') return;
            event.preventDefault();
            quickInput.click();
        });
    }

    if (quickInput && quickForm) {
        quickInput.addEventListener('change', function () {
            if (!quickInput.files.length) return;

            if (quickInput.files[0].size > 5 * 1024 * 1024) {
                PortalFeedback.error(__cfg_profile_tabs.v1);
                quickInput.value = '';
                return;
            }

            quickForm.submit();
        });
    }

    document.querySelectorAll('.bhd-toggle-pw').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;

            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('i').classList.toggle('fa-eye', showing);
            btn.querySelector('i').classList.toggle('fa-eye-slash', !showing);
        });
    });

    const newPwInput = document.getElementById('newPw');
    const confirmPwInput = document.getElementById('confirmPw');
    const fill = document.getElementById('pwMeterFill');
    const strengthHint = document.getElementById('pwStrengthHint');
    const matchHint = document.getElementById('pwMatchHint');

    function evalStrength(password) {
        let score = 0;

        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        if (!password) return { width: 0, label: '', color: '#e9ecef' };
        if (score <= 2) return { width: 30, label: __cfg_profile_tabs.v2, color: '#ef4444' };
        if (score <= 3) return { width: 60, label: __cfg_profile_tabs.v3, color: '#f59e0b' };
        if (score <= 4) return { width: 82, label: __cfg_profile_tabs.v4, color: '#3b82f6' };
        return { width: 100, label: __cfg_profile_tabs.v5, color: '#22c55e' };
    }

    function checkMatch() {
        if (!newPwInput || !confirmPwInput || !matchHint) return;

        if (!confirmPwInput.value) {
            matchHint.textContent = '';
            return;
        }

        const ok = newPwInput.value === confirmPwInput.value;
        matchHint.textContent = ok ? `✓ ${__cfg_profile_tabs.v6}` : `✗ ${__cfg_profile_tabs.v7}`;
        matchHint.style.color = ok ? '#22c55e' : '#ef4444';
    }

    if (newPwInput && fill) {
        newPwInput.addEventListener('input', function () {
            newPwInput.value = newPwInput.value.replace(/\s/g, '');
            const result = evalStrength(newPwInput.value);
            fill.style.width = result.width + '%';
            fill.style.background = result.color;
            strengthHint.textContent = result.label ? __cfg_profile_tabs.v8.replace(':strength', result.label) : __cfg_profile_tabs.v9;
            strengthHint.style.color = result.color !== '#e9ecef' ? result.color : '#888';
            checkMatch();
        });
    }

    if (confirmPwInput) {
        confirmPwInput.addEventListener('input', function () {
            confirmPwInput.value = confirmPwInput.value.replace(/\s/g, '');
            checkMatch();
        });
    }

    const contactInput = document.getElementById('contactInput');

    if (contactInput) {
        if (/^0/.test(contactInput.value)) {
            contactInput.value = contactInput.value.slice(1);
        }

        contactInput.addEventListener('input', function () {
            let value = contactInput.value.replace(/\D/g, '');

            if (value.startsWith('0')) value = value.slice(1);
            contactInput.value = value.slice(0, 10);
        });

        const infoForm = document.getElementById('infoForm');

        if (infoForm) {
            infoForm.addEventListener('submit', function () {
                if (contactInput.value.length === 10) {
                    contactInput.value = '0' + contactInput.value;
                }
            });
        }
    }

    const usernameInput = document.querySelector('input[name="username"]');

    if (usernameInput) {
        usernameInput.addEventListener('input', function () {
            usernameInput.value = usernameInput.value.replace(/\s/g, '');
        });
    }

    document.querySelectorAll('.bhd-alert').forEach(alert => {
        setTimeout(function () {
            alert.style.transition = 'opacity .5s ease';
            alert.style.opacity = '0';

            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 5000);
    });

    const idViewerTriggers = document.querySelectorAll('[data-id-viewer]');
    const idImageViewModalEl = document.getElementById('idImageViewModal');
    const idImageViewModalImg = document.getElementById('idImageViewModalImg');
    const idImageViewModalLabel = document.getElementById('idImageViewModalLabel');

    idViewerTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const src = trigger.getAttribute('data-img-src');
            if (!src || !idImageViewModalEl) return;

            if (idImageViewModalImg) idImageViewModalImg.src = src;
            if (idImageViewModalLabel) {
                idImageViewModalLabel.textContent = trigger.getAttribute('data-img-label') || idImageViewModalLabel.textContent;
            }

            if (window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(idImageViewModalEl).show();
            }
        });

        trigger.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                trigger.click();
            }
        });
    });
});
