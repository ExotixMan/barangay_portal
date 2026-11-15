(function () {
            function autoDismissSettingsAlerts() {
                document.querySelectorAll('.settings-alert.alert-dismissible.alert-success').forEach(function (alertEl) {
                    const isDanger = alertEl.classList.contains('alert-danger');
                    const delay = isDanger ? 8000 : 4500;

                    window.setTimeout(function () {
                        if (!document.body.contains(alertEl)) return;

                        if (window.bootstrap && window.bootstrap.Alert) {
                            window.bootstrap.Alert.getOrCreateInstance(alertEl).close();
                            return;
                        }

                        alertEl.classList.add('settings-alert-hiding');
                        window.setTimeout(function () {
                            if (alertEl.parentNode) {
                                alertEl.parentNode.removeChild(alertEl);
                            }
                        }, 500);
                    }, delay);
                });
            }

            autoDismissSettingsAlerts();

            const textarea = document.getElementById('requestTemplate');
            const preview = document.getElementById('templatePreview');
            const titleInput = document.getElementById('requestTitle');
            const footerInput = document.getElementById('requestFooter');
            const previewTitle = document.getElementById('templatePreviewTitle');
            const previewFooter = document.getElementById('templatePreviewFooter');
            const previewBarangay = document.getElementById('templatePreviewBarangay');
            const barangayInput = document.getElementById('general_barangay_name');
            const templateMeta = document.getElementById('templateMeta');
            const savebar = document.getElementById('settingsSavebar');
            const savebarStatusText = document.getElementById('savebarStatusText');
            const savebarHelpText = document.getElementById('savebarHelpText');
            const settingsForm = document.getElementById('settingsForm');
            const panels = Array.from(document.querySelectorAll('[data-settings-panel]'));
            const sideLinks = Array.from(document.querySelectorAll('.settings-side-link'));
            const nav = document.getElementById('settingsSideNav');
            const primaryColorInput = document.getElementById('general_primary_color');
            const secondaryColorInput = document.getElementById('general_secondary_color');
            const brandSwatchPrimary = document.getElementById('brandSwatchPrimary');
            const brandSwatchSecondary = document.getElementById('brandSwatchSecondary');
            const brandPreviewBtn = document.getElementById('brandPreviewBtn');

            const dirtyPanels = new Set();
            let isSubmitting = false;

            const brace = function (value) {
                return '{' + '{' + value + '}' + '}';
            };

            const sample = {};
            sample[brace('full_name')] = 'Juan Dela Cruz';
            sample[brace('request_type')] = 'Barangay Clearance';
            sample[brace('request_id')] = 'REQ-2026-0001';
            sample[brace('date_submitted')] = 'January 18, 2026';
            sample[brace('complete_address')] = '123 Main Street, Barangay Hulong Duhat, Malabon City';
            sample[brace('contact_number')] = '+63 912 345 6789';
            sample[brace('email_address')] = 'juan@example.com';
            sample[brace('purpose_of_request')] = 'Employment requirement';
            sample[brace('remarks')] = 'For release after verification';

            function renderTemplatePreview() {
                if (!textarea || !preview) return;

                let content = textarea.value || '';
                Object.entries(sample).forEach(function ([needle, replacement]) {
                    content = content.split(needle).join(replacement);
                });

                preview.textContent = content || 'Start typing your template body to see the live preview here.';

                if (previewTitle && titleInput) {
                    previewTitle.textContent = titleInput.value || 'Request Certificate';
                }

                if (previewFooter && footerInput) {
                    previewFooter.textContent = footerInput.value || '';
                }
            }

            function updateTemplateMeta() {
                if (!templateMeta || !textarea) return;
                const text = textarea.value || '';
                const trimmed = text.trim();
                const words = trimmed.length ? trimmed.split(/\s+/).length : 0;
                templateMeta.innerHTML = '<i class="fas fa-keyboard me-1"></i>' + words + ' words &middot; ' + text.length + ' characters';
            }

            function renderLetterhead() {
                if (previewBarangay) {
                    previewBarangay.textContent = (barangayInput && barangayInput.value) || 'Barangay';
                }
            }

            function renderBrandPreview() {
                if (!brandSwatchPrimary || !brandSwatchSecondary || !brandPreviewBtn) return;
                const primary = (primaryColorInput && primaryColorInput.value) || '#d32f2f';
                const secondary = (secondaryColorInput && secondaryColorInput.value) || '#b71c1c';
                brandSwatchPrimary.style.background = primary;
                brandSwatchSecondary.style.background = secondary;
                brandPreviewBtn.style.background = 'linear-gradient(135deg, ' + primary + ', ' + secondary + ')';
            }

            function updateNavDirtyDots() {
                sideLinks.forEach(function (link) {
                    const id = (link.getAttribute('href') || '').replace('#', '');
                    const dot = link.querySelector('.nav-dirty-dot');
                    if (dot) dot.classList.toggle('is-visible', dirtyPanels.has(id));
                });
            }

            function updateSavebarCopy() {
                if (!savebarStatusText || !savebarHelpText) return;
                const count = dirtyPanels.size;
                if (count === 0) {
                    savebarStatusText.textContent = 'All settings saved';
                    savebarHelpText.textContent = "You're all set — make edits above if you need to change anything.";
                } else {
                    savebarStatusText.textContent = count + ' section' + (count > 1 ? 's' : '') + ' with unsaved changes';
                    savebarHelpText.textContent = 'Review your edits, then save to apply them across the portal.';
                }
            }

            function markDirty(event) {
                if (savebar) savebar.classList.add('is-dirty');
                const target = event && event.target ? event.target : null;
                const panel = target ? target.closest('[data-settings-panel]') : null;
                if (panel) {
                    dirtyPanels.add(panel.id);
                }
                updateNavDirtyDots();
                updateSavebarCopy();
            }

            function setActiveLink(panelId) {
                sideLinks.forEach(function (link) {
                    const isActive = link.getAttribute('href') === '#' + panelId;
                    link.classList.toggle('active', isActive);
                    link.setAttribute('aria-current', isActive ? 'page' : 'false');

                    if (isActive && nav && window.innerWidth <= 1199) {
                        link.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                });
            }

            function showPanel(panelId, shouldScroll) {
                const selectedPanel = document.getElementById(panelId) || panels[0];
                if (!selectedPanel) return;

                panels.forEach(function (panel) {
                    panel.classList.toggle('is-active', panel === selectedPanel);
                });

                setActiveLink(selectedPanel.id);

                if (shouldScroll) {
                    selectedPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            function openFromHash(hash, shouldScroll) {
                const panelId = (hash || '').replace('#', '');
                if (!panelId) return false;

                const panel = document.getElementById(panelId);
                if (!panel || !panel.matches('[data-settings-panel]')) return false;

                showPanel(panelId, shouldScroll);
                return true;
            }

            sideLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    const hash = link.getAttribute('href');

                    if (!openFromHash(hash, true)) return;

                    event.preventDefault();
                    history.replaceState(null, '', hash);
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(function (link) {
                if (link.classList.contains('settings-side-link')) return;

                link.addEventListener('click', function (event) {
                    const hash = link.getAttribute('href');

                    if (!openFromHash(hash, true)) return;

                    event.preventDefault();
                    history.replaceState(null, '', hash);
                });
            });

            const firstInvalidField = document.querySelector('#settingsForm .is-invalid');
            if (firstInvalidField) {
                const panelWithError = firstInvalidField.closest('[data-settings-panel]');
                showPanel(panelWithError ? panelWithError.id : (panels[0] ? panels[0].id : ''), true);
            } else if (!openFromHash(window.location.hash, false) && panels[0]) {
                showPanel(panels[0].id, false);
            }

            window.addEventListener('hashchange', function () {
                openFromHash(window.location.hash, true);
            });

            if (textarea) {
                textarea.addEventListener('input', renderTemplatePreview);
                textarea.addEventListener('input', updateTemplateMeta);
            }
            if (titleInput) titleInput.addEventListener('input', renderTemplatePreview);
            if (footerInput) footerInput.addEventListener('input', renderTemplatePreview);
            if (barangayInput) barangayInput.addEventListener('input', renderLetterhead);
            if (primaryColorInput) primaryColorInput.addEventListener('input', renderBrandPreview);
            if (secondaryColorInput) secondaryColorInput.addEventListener('input', renderBrandPreview);

            renderTemplatePreview();
            updateTemplateMeta();
            renderLetterhead();
            renderBrandPreview();
            updateSavebarCopy();

            document.querySelectorAll('.placeholder-chip-v2[data-placeholder]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!textarea) return;

                    const insertText = button.getAttribute('data-placeholder') || '';
                    const start = textarea.selectionStart || 0;
                    const end = textarea.selectionEnd || 0;
                    const value = textarea.value || '';

                    textarea.value = value.substring(0, start) + insertText + value.substring(end);
                    textarea.focus();
                    textarea.setSelectionRange(start + insertText.length, start + insertText.length);
                    renderTemplatePreview();
                    updateTemplateMeta();
                    markDirty({ target: textarea });
                });
            });

            document.querySelectorAll('.toggle-secret').forEach(function (button) {
                button.addEventListener('click', function () {
                    const group = button.closest('.secret-group');
                    const input = group ? group.querySelector('[data-secret-field]') : null;
                    const icon = button.querySelector('i');

                    if (!input) return;

                    const shouldShow = input.type === 'password';
                    input.type = shouldShow ? 'text' : 'password';

                    if (icon) {
                        icon.classList.toggle('fa-eye', !shouldShow);
                        icon.classList.toggle('fa-eye-slash', shouldShow);
                    }
                });
            });

            const allowedAiProviders = ['gemini', 'groq', 'cloudflare'];

            function byId(id) {
                return document.getElementById(id);
            }

            function cleanValue(field) {
                return field ? String(field.value || '').trim() : '';
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function validationCard(field) {
                return field ? field.closest('.field-card-v2, .switch-card-v2, .template-editor-card, .doc-template-card') : null;
            }

            function clientErrorSelector(field) {
                return '[data-client-validation-for="' + CSS.escape(field.id || field.name || '') + '"]';
            }

            function clearClientError(field) {
                if (!field) return;

                const key = field.id || field.name || '';
                if (key) {
                    document.querySelectorAll('[data-client-validation-for="' + CSS.escape(key) + '"]').forEach(function (errorEl) {
                        errorEl.remove();
                    });
                }

                if (field.dataset.clientInvalid === '1') {
                    field.classList.remove('is-invalid');
                    delete field.dataset.clientInvalid;
                }

                const card = validationCard(field);
                if (card && !card.querySelector('[data-client-validation]') && !card.querySelector('.is-invalid:not([data-client-invalid])')) {
                    card.classList.remove('has-error');
                }
            }

            function setClientError(field, message) {
                if (!field || !message) return;

                clearClientError(field);

                field.dataset.clientInvalid = '1';
                field.classList.add('is-invalid');

                const card = validationCard(field);
                if (card) {
                    card.classList.add('has-error');
                }

                const key = field.id || field.name || '';
                const errorEl = document.createElement('small');
                errorEl.className = 'field-error';
                errorEl.setAttribute('data-client-validation', 'true');
                errorEl.setAttribute('data-client-validation-for', key);
                errorEl.innerHTML = '<i class="fas fa-circle-exclamation"></i>' + escapeHtml(message);

                const docUploadBox = card ? card.querySelector('.doc-upload-box') : null;
                const insertTarget = field.closest('.input-group') || docUploadBox || field;
                insertTarget.insertAdjacentElement('afterend', errorEl);
            }

            function resetClientValidation(form) {
                if (!form) return;
                form.querySelectorAll('[data-client-validation]').forEach(function (errorEl) {
                    errorEl.remove();
                });
                form.querySelectorAll('[data-client-invalid="1"]').forEach(function (field) {
                    field.classList.remove('is-invalid');
                    delete field.dataset.clientInvalid;
                });
                form.querySelectorAll('.has-error').forEach(function (card) {
                    if (!card.querySelector('.is-invalid:not([data-client-invalid])')) {
                        card.classList.remove('has-error');
                    }
                });

                const summary = document.getElementById('clientValidationSummary');
                if (summary) {
                    summary.remove();
                }
            }

            function showValidationSummary(messages) {
                const existing = document.getElementById('clientValidationSummary');
                if (existing) existing.remove();

                if (!settingsForm || !messages.length) return;

                const summary = document.createElement('div');
                summary.id = 'clientValidationSummary';
                summary.className = 'alert alert-danger settings-alert';
                summary.setAttribute('role', 'alert');

                const visibleMessages = messages.slice(0, 8).map(function (message) {
                    return '<li>' + escapeHtml(message) + '</li>';
                }).join('');

                const extraCount = messages.length > 8
                    ? '<li>And ' + (messages.length - 8) + ' more validation issue' + (messages.length - 8 > 1 ? 's' : '') + '.</li>'
                    : '';

                summary.innerHTML =
                    '<strong><i class="fas fa-shield-halved me-2"></i>Please fix these settings first:</strong>' +
                    '<ul class="mb-0 mt-2 ps-3">' + visibleMessages + extraCount + '</ul>';

                settingsForm.insertAdjacentElement('beforebegin', summary);
            }

            function addError(errors, field, message) {
                if (!field || !message) return;
                errors.push(message);
                setClientError(field, message);
            }

            function hasUnsafeText(value) {
                return /[<>]/.test(value) || /[\u0000-\u001F\u007F]/.test(value);
            }

            function validatePlainText(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = String(field.value || '');
                const trimmed = value.trim();

                if (options.required && trimmed === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (trimmed === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== trimmed) {
                    addError(errors, field, label + ' must not start or end with spaces.');
                    return false;
                }

                if (hasUnsafeText(trimmed)) {
                    addError(errors, field, label + ' contains unsafe characters.');
                    return false;
                }

                if (options.min && trimmed.length < options.min) {
                    addError(errors, field, label + ' must be at least ' + options.min + ' characters.');
                    return false;
                }

                if (options.max && trimmed.length > options.max) {
                    addError(errors, field, label + ' must not exceed ' + options.max + ' characters.');
                    return false;
                }

                if (options.pattern && !options.pattern.test(trimmed)) {
                    addError(errors, field, options.patternMessage || (label + ' format is invalid.'));
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateEmailField(errors, id, label, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== field.value || hasUnsafeText(value) || !emailPattern.test(value)) {
                    addError(errors, field, 'Enter a valid ' + label.toLowerCase() + '.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateUrlField(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = cleanValue(field);

                if (options.required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                try {
                    const url = new URL(value);

                    if (!['http:', 'https:'].includes(url.protocol)) {
                        addError(errors, field, label + ' must start with http:// or https://.');
                        return false;
                    }

                    if (options.httpsOnly && url.protocol !== 'https:') {
                        addError(errors, field, label + ' must use https:// for security.');
                        return false;
                    }

                    if (!url.hostname || /\s/.test(value) || hasUnsafeText(value)) {
                        addError(errors, field, label + ' is not a valid URL.');
                        return false;
                    }

                    clearClientError(field);
                    return true;
                } catch (error) {
                    addError(errors, field, label + ' is not a valid URL.');
                    return false;
                }
            }

            function validatePhoneField(errors, id, label) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                const digits = value.replace(/\D/g, '');
                const allowedFormat = /^[+0-9().\-\s]+$/.test(value);
                const looksLikePhMobile = /^09\d{9}$/.test(value.replace(/\s|-/g, '')) || /^\+639\d{9}$/.test(value.replace(/\s|-/g, ''));
                const validLength = digits.length >= 7 && digits.length <= 15;
                const repeatedOnly = /^(\d)\1+$/.test(digits);

                if (!allowedFormat || !validLength || repeatedOnly) {
                    addError(errors, field, label + ' must be a valid phone number.');
                    return false;
                }

                if ((digits.startsWith('09') || digits.startsWith('639')) && !looksLikePhMobile) {
                    addError(errors, field, label + ' must use a valid PH mobile format like 09XXXXXXXXX or +639XXXXXXXXX.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateColorField(errors, id, label) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                if (!/^#[0-9a-fA-F]{6}$/.test(value)) {
                    addError(errors, field, label + ' must be a valid hex color.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateIntegerField(errors, id, label, min, max, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return null;
                }

                if (value === '') {
                    clearClientError(field);
                    return null;
                }

                if (!/^\d+$/.test(value)) {
                    addError(errors, field, label + ' must be a whole number.');
                    return null;
                }

                const numberValue = Number(value);
                if (!Number.isSafeInteger(numberValue) || numberValue < min || numberValue > max) {
                    addError(errors, field, label + ' must be between ' + min + ' and ' + max + '.');
                    return null;
                }

                clearClientError(field);
                return numberValue;
            }

            function validateHostField(errors, id, label, required) {
                const field = byId(id);
                if (!field) return true;

                const value = cleanValue(field);
                const hostnamePattern = /^(localhost|(?:(?:25[0-5]|2[0-4]\d|1?\d?\d)(?:\.|$)){4}|(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,})$/;

                if (required && value === '') {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                if (value === '') {
                    clearClientError(field);
                    return true;
                }

                if (/^https?:\/\//i.test(value) || /[\/\s<>]/.test(value) || value.length > 253 || !hostnamePattern.test(value)) {
                    addError(errors, field, label + ' must be a valid host only, for example smtp.gmail.com.');
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function validateSecretField(errors, id, label, options) {
                const field = byId(id);
                if (!field) return true;

                options = options || {};
                const value = String(field.value || '');
                const trimmed = value.trim();

                const alreadyConfigured = field.dataset.secretConfigured === '1';

                if (options.required && trimmed === '' && !alreadyConfigured) {
                    addError(errors, field, label + ' is required.');
                    return false;
                }

                // Secret inputs are intentionally blank after saving. An existing
                // configured value satisfies a required secret without exposing it
                // back into the page or forcing the admin to re-enter it.
                if (trimmed === '') {
                    clearClientError(field);
                    return true;
                }

                if (value !== trimmed || /\s/.test(trimmed)) {
                    addError(errors, field, label + ' must not contain spaces.');
                    return false;
                }

                if (options.min && trimmed.length < options.min) {
                    addError(errors, field, label + ' looks too short.');
                    return false;
                }

                if (options.max && trimmed.length > options.max) {
                    addError(errors, field, label + ' is too long.');
                    return false;
                }

                if (options.pattern && !options.pattern.test(trimmed)) {
                    addError(errors, field, options.patternMessage || (label + ' format is invalid.'));
                    return false;
                }

                clearClientError(field);
                return true;
            }

            function parseFallbackProviders(field) {
                const value = cleanValue(field);
                if (value === '') return [];

                return value.split(',')
                    .map(function (item) { return item.trim().toLowerCase(); })
                    .filter(Boolean);
            }

            function validateSettingsForm() {
                if (!settingsForm) return true;

                settingsForm.setAttribute('novalidate', 'novalidate');
                resetClientValidation(settingsForm);

                const errors = [];

                validatePlainText(errors, 'general_portal_name', 'Portal name', {
                    required: true,
                    min: 3,
                    max: 80,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'Portal name may only contain letters, numbers, spaces, and common punctuation.'
                });
                validatePlainText(errors, 'general_barangay_name', 'Barangay name', {
                    required: true,
                    min: 3,
                    max: 100,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'Barangay name may only contain letters, numbers, spaces, and common punctuation.'
                });
                validateEmailField(errors, 'general_contact_email', 'Contact email', false);
                validatePhoneField(errors, 'general_contact_phone', 'Contact phone');
                validateUrlField(errors, 'general_website', 'Website', { required: false, httpsOnly: false });
                validateColorField(errors, 'general_primary_color', 'Primary color');
                validateColorField(errors, 'general_secondary_color', 'Secondary color');

                const smsProvider = byId('sms_provider');
                if (smsProvider && !['semaphore'].includes(cleanValue(smsProvider).toLowerCase())) {
                    addError(errors, smsProvider, 'SMS provider must be Semaphore.');
                }

                validateUrlField(errors, 'sms_api_base_url', 'SMS API endpoint', { required: true, httpsOnly: true });
                validateSecretField(errors, 'sms_api_key', 'SMS API key', { required: true, min: 20, max: 255 });
                validatePlainText(errors, 'sms_sender_name', 'SMS sender name', {
                    required: true,
                    min: 3,
                    max: 11,
                    pattern: /^[A-Z0-9]+$/,
                    patternMessage: 'SMS sender name must be 3 to 11 uppercase letters or numbers only.'
                });

                const smsDailyLimit = validateIntegerField(errors, 'sms_daily_limit', 'SMS daily limit', 0, 100000, true);
                const smsMonthlyLimit = validateIntegerField(errors, 'sms_monthly_limit', 'SMS monthly limit', 0, 1000000, true);
                if (smsDailyLimit !== null && smsMonthlyLimit !== null && smsMonthlyLimit > 0 && smsDailyLimit > smsMonthlyLimit) {
                    addError(errors, byId('sms_daily_limit'), 'SMS daily limit cannot be greater than the monthly limit.');
                }

                const emailMailerField = byId('email_mailer');
                const emailMailer = emailMailerField ? cleanValue(emailMailerField).toLowerCase() : '';
                const apiMailers = ['resend', 'postmark', 'mailgun', 'sendgrid'];

                validatePlainText(errors, 'email_mailer', 'Mailer', {
                    required: true,
                    min: 3,
                    max: 20,
                    pattern: /^(smtp|resend|postmark|mailgun|sendgrid|log)$/i,
                    patternMessage: 'Mailer must be smtp, resend, postmark, mailgun, sendgrid, or log.'
                });

                const smtpSelected = emailMailer === 'smtp';
                validateHostField(errors, 'email_host', 'SMTP host', smtpSelected);
                validateIntegerField(errors, 'email_port', 'SMTP port', 1, 65535, smtpSelected);
                validatePlainText(errors, 'email_encryption', 'Email encryption', {
                    required: smtpSelected,
                    max: 10,
                    pattern: /^(tls|ssl|starttls|none)$/i,
                    patternMessage: 'Email encryption must be tls, ssl, starttls, or none.'
                });
                validatePlainText(errors, 'email_username', 'SMTP username', {
                    required: smtpSelected,
                    min: 3,
                    max: 255,
                    pattern: /^[^\s<>]+$/,
                    patternMessage: 'SMTP username must not contain spaces or unsafe characters.'
                });
                validateSecretField(errors, 'email_password', 'SMTP password', { required: smtpSelected, min: 8, max: 255 });
                validateSecretField(errors, 'email_api_key', 'Email provider API key', { required: apiMailers.includes(emailMailer), min: 8, max: 1000 });
                validateEmailField(errors, 'email_from_address', 'From address', emailMailer !== 'log');
                validatePlainText(errors, 'email_from_name', 'From name', {
                    required: true,
                    min: 2,
                    max: 80,
                    pattern: /^[a-zA-Z0-9\s.,'()&\/-]+$/,
                    patternMessage: 'From name may only contain letters, numbers, spaces, and common punctuation.'
                });

                const aiProviderField = byId('ai_provider');
                const aiProvider = aiProviderField ? cleanValue(aiProviderField).toLowerCase() : '';
                if (aiProviderField && !allowedAiProviders.includes(aiProvider)) {
                    addError(errors, aiProviderField, 'Default AI provider is invalid.');
                }

                const fallbackField = byId('ai_fallback_providers');
                const fallbacks = fallbackField ? parseFallbackProviders(fallbackField) : [];
                if (fallbackField) {
                    const invalidFallbacks = fallbacks.filter(function (providerName) {
                        return !allowedAiProviders.includes(providerName);
                    });
                    const duplicatedFallbacks = fallbacks.filter(function (providerName, index) {
                        return fallbacks.indexOf(providerName) !== index;
                    });

                    if (invalidFallbacks.length) {
                        addError(errors, fallbackField, 'Fallback providers can only be gemini, groq, or cloudflare.');
                    } else if (duplicatedFallbacks.length) {
                        addError(errors, fallbackField, 'Fallback providers must not contain duplicates.');
                    } else {
                        clearClientError(fallbackField);
                    }
                }

                const activeKeyMap = {
                    gemini: ['ai_keys_gemini', 'Gemini key'],
                    groq: ['ai_keys_groq', 'Groq key'],
                    cloudflare: ['ai_keys_cloudflare', 'Cloudflare token']
                };

                Object.keys(activeKeyMap).forEach(function (providerName) {
                    const rule = activeKeyMap[providerName];
                    const isActive = aiProvider === providerName;
                    const isFallback = fallbacks.includes(providerName);
                    validateSecretField(errors, rule[0], rule[1], {
                        required: isActive || isFallback,
                        min: 10,
                        max: 500
                    });
                });

                const needsCloudflareAccount = aiProvider === 'cloudflare' || fallbacks.includes('cloudflare') || cleanValue(byId('ai_keys_cloudflare')) !== '';
                validatePlainText(errors, 'ai_cloudflare_account_id', 'Cloudflare Account ID', {
                    required: needsCloudflareAccount,
                    min: 32,
                    max: 32,
                    pattern: /^[a-fA-F0-9]{32}$/,
                    patternMessage: 'Cloudflare Account ID must be a 32-character hexadecimal ID.'
                });

                validateIntegerField(errors, 'security_session_timeout', 'Session timeout', 300, 86400, true);

                const captchaEnabledField = byId('security_captcha_enabled');
                const captchaEnabled = captchaEnabledField && captchaEnabledField.checked;
                validateSecretField(errors, 'security_recaptcha_site_key', 'reCAPTCHA site key', {
                    required: captchaEnabled,
                    min: 20,
                    max: 120,
                    pattern: /^[A-Za-z0-9_-]+$/,
                    patternMessage: 'reCAPTCHA site key must only contain letters, numbers, underscores, or hyphens.'
                });
                validateSecretField(errors, 'security_recaptcha_secret_key', 'reCAPTCHA secret key', {
                    required: captchaEnabled,
                    min: 20,
                    max: 120,
                    pattern: /^[A-Za-z0-9_-]+$/,
                    patternMessage: 'reCAPTCHA secret key must only contain letters, numbers, underscores, or hyphens.'
                });

                if (errors.length) {
                    showValidationSummary(errors);

                    const firstInvalid = settingsForm.querySelector('[data-client-invalid="1"]');
                    if (firstInvalid) {
                        const panelWithError = firstInvalid.closest('[data-settings-panel]');
                        if (panelWithError) {
                            showPanel(panelWithError.id, true);
                        } else {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        window.setTimeout(function () {
                            if (typeof firstInvalid.focus === 'function') {
                                firstInvalid.focus({ preventScroll: true });
                            }
                        }, 220);
                    }

                    if (savebar) {
                        savebar.classList.add('is-dirty');
                    }
                    if (savebarStatusText) {
                        savebarStatusText.textContent = 'Validation blocked saving';
                    }
                    if (savebarHelpText) {
                        savebarHelpText.textContent = 'Fix the highlighted fields first, then save again.';
                    }

                    return false;
                }

                return true;
            }

            function validateDocxUploadForm(form) {
                if (!form) return true;

                form.setAttribute('novalidate', 'novalidate');

                const input = document.querySelector('input[type="file"][form="' + CSS.escape(form.id) + '"]');
                if (!input) return true;

                clearClientError(input);

                const errors = [];
                const file = input.files && input.files.length ? input.files[0] : null;

                if (!file) {
                    addError(errors, input, 'Please choose a DOCX template before uploading.');
                    return false;
                }

                const fileName = file.name || '';
                const fileNameLower = fileName.toLowerCase();
                const validMimeTypes = [
                    '',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!fileNameLower.endsWith('.docx') || !validMimeTypes.includes(file.type)) {
                    addError(errors, input, 'Template file must be a valid .docx document.');
                    return false;
                }

                if (file.size > 5 * 1024 * 1024) {
                    addError(errors, input, 'Template file must not exceed 5 MB.');
                    return false;
                }

                if (/[<>:"/\\|?*\u0000-\u001F]/.test(fileName)) {
                    addError(errors, input, 'Template filename contains invalid characters.');
                    return false;
                }

                clearClientError(input);
                return true;
            }

            if (settingsForm) {
                settingsForm.setAttribute('novalidate', 'novalidate');
            }

            document.querySelectorAll('#settingsForm input, #settingsForm select, #settingsForm textarea').forEach(function (field) {
                field.addEventListener('change', markDirty);
                field.addEventListener('input', markDirty);
            });

            if (settingsForm) {
                settingsForm.addEventListener('submit', function (event) {
                    if (!validateSettingsForm()) {
                        event.preventDefault();
                        event.stopPropagation();
                        isSubmitting = false;
                        return;
                    }

                    isSubmitting = true;
                });
            }

            document.querySelectorAll('form[id^="templateUploadForm_"]').forEach(function (uploadForm) {
                uploadForm.setAttribute('novalidate', 'novalidate');

                uploadForm.addEventListener('submit', function (event) {
                    if (!validateDocxUploadForm(uploadForm)) {
                        event.preventDefault();
                        event.stopPropagation();

                        const input = document.querySelector('input[type="file"][form="' + CSS.escape(uploadForm.id) + '"]');
                        if (input) {
                            const panelWithError = input.closest('[data-settings-panel]');
                            if (panelWithError) {
                                showPanel(panelWithError.id, true);
                            }
                        }
                    }
                });
            });

            window.addEventListener('beforeunload', function (event) {
                if (isSubmitting) return;
                if (savebar && savebar.classList.contains('is-dirty')) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            });
        })();

        document.querySelectorAll('.doc-template-input').forEach((input) => {
            input.addEventListener('change', function () {
                const targetId = this.dataset.fileNameTarget;
                const target = document.getElementById(targetId);

                if (!target) return;

                if (this.files && this.files.length > 0) {
                    target.textContent = this.files[0].name;
                } else {
                    target.textContent = 'No file selected';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const syncBtn = document.getElementById('semaphoreSyncBtn');
            const syncForm = document.getElementById('semaphoreSyncForm');

            if (!syncBtn || !syncForm) {
                return;
            }

            syncBtn.addEventListener('click', function () {
                syncBtn.disabled = true;
                syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Syncing...';

                syncForm.submit();
            });
        });

// Email provider-aware settings UI.
document.addEventListener('DOMContentLoaded', function () {
    const provider = document.getElementById('email_mailer');
    if (!provider) return;

    const apiProviders = new Set(['resend', 'postmark', 'mailgun', 'sendgrid']);
    const groups = Array.from(document.querySelectorAll('[data-email-provider-group]'));

    function syncEmailProviderFields() {
        const selected = String(provider.value || 'smtp').toLowerCase();

        groups.forEach(function (group) {
            const type = group.getAttribute('data-email-provider-group');
            let visible = false;

            if (type === 'smtp') visible = selected === 'smtp';
            if (type === 'api') visible = apiProviders.has(selected);
            if (type === 'mailgun') visible = selected === 'mailgun';
            if (type === 'postmark') visible = selected === 'postmark';

            group.hidden = !visible;
            group.querySelectorAll('input, select, textarea').forEach(function (input) {
                input.disabled = !visible;
            });
        });
    }

    provider.addEventListener('change', syncEmailProviderFields);
    syncEmailProviderFields();
});
