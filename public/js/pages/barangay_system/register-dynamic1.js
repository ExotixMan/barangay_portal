const __cfg_barangay_system_register_1 = JSON.parse(document.getElementById('js-config-barangay-system-register-1')?.dataset.jsConfig || '{}');

        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("registerForm");
            if (!form) return;

            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');
            const submitBtn = document.getElementById('createAccountBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const meterFill = document.getElementById('passwordMeterFill');
            const strengthText = document.getElementById('passwordStrengthText');
            const matchText = document.getElementById('passwordMatchText');
            const termsCheckbox = document.getElementById('terms');
            const passwordChecklist = Array.from(document.querySelectorAll(".password-checklist li"));
            const usernameHint = document.getElementById('usernameHint');
            const emailHint = document.getElementById('emailHint');
            const contactHint = document.getElementById('contactHint');

            const contactInput = document.getElementById("contact");
            const emailInput = document.getElementById("email");
            const birthdateInput = document.getElementById("birthdate");
            const uploadInputs = document.querySelectorAll(".js-file-input");
            const addressInput = document.getElementById("address");
            const addressPreviewText = document.getElementById("addressPreviewText");
            const addressFieldIds = ["house_no", "street", "purok", "barangay", "city", "nearest_landmark", "residency_status"];
            const recaptchaBox = document.getElementById("recaptchaBox");
            const recaptchaEnabled = String(__cfg_barangay_system_register_1.v1).toLowerCase() === 'true';

            let usernameCheckTimer = null;
            let usernameCheckSeq = 0;
            let isProgrammaticSubmit = false;
            const usernameCheckState = {
                value: '',
                status: 'idle',
                message: '',
            };

            let currentStep = 1;
            const totalSteps = 2;

            function getFieldValue(id) {
                const field = document.getElementById(id);
                return field ? field.value.trim() : '';
            }

            function buildResidentAddress() {
                if (!addressInput) return '';

                const houseNo = getFieldValue('house_no');
                const street = getFieldValue('street');
                const purok = getFieldValue('purok');
                const barangay = getFieldValue('barangay') || 'Hulong Duhat';
                const city = getFieldValue('city') || 'Malabon City';
                const landmark = getFieldValue('nearest_landmark');
                const residencyStatus = getFieldValue('residency_status');

                const addressParts = [
                    houseNo ? `${houseNo}` : '',
                    street,
                    purok,
                    barangay,
                    city,
                    landmark ? `Landmark: ${landmark}` : '',
                    residencyStatus ? __cfg_barangay_system_register_1.v2.replace(':status', residencyStatus) : '',
                ].filter(Boolean);

                const completeAddress = addressParts.join(', ');
                addressInput.value = completeAddress;

                if (addressPreviewText) {
                    addressPreviewText.textContent = completeAddress || __cfg_barangay_system_register_1.v3;
                }

                return completeAddress;
            }

            addressFieldIds.forEach((id) => {
                const field = document.getElementById(id);
                if (!field) return;
                field.addEventListener('input', buildResidentAddress);
                field.addEventListener('change', buildResidentAddress);
            });

            const nameFields = ['firstname', 'middlename', 'lastname'];

            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {

                    field.addEventListener('blur', function() {
                        validateNameField(this);
                    });

                    field.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                        const existingError = this.parentNode.querySelector('.error-message');
                        if (existingError) {
                            existingError.remove();
                        }
                    });
                }
            });

            function validateNameField(field) {
                const fieldValue = field.value.trim();
                const fieldName = field.id.charAt(0).toUpperCase() + field.id.slice(1);

                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (field.hasAttribute('required') && !fieldValue) {
                    showFieldError(field, __cfg_barangay_system_register_1.v4.replace(':field', fieldName));
                    return false;
                }

                if (fieldValue) {
                    const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;

                    if (!nameRegex.test(fieldValue)) {
                        showFieldError(field, __cfg_barangay_system_register_1.v5);
                        return false;
                    }

                    if (fieldValue.length < 2) {
                        showFieldError(field, __cfg_barangay_system_register_1.v6);
                        return false;
                    }
                }

                return true;
            }

            const usernameField = document.getElementById('username');
            if (usernameField) {
                usernameField.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\s/g, '');

                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.error-message');
                    if (existingError) {
                        existingError.remove();
                    }

                    if (usernameHint) {
                        usernameHint.textContent = __cfg_barangay_system_register_1.v7;
                        usernameHint.classList.remove('good', 'bad');
                    }

                    if (usernameCheckTimer) {
                        clearTimeout(usernameCheckTimer);
                    }

                    usernameCheckTimer = setTimeout(() => {
                        checkUsernameAvailability(this.value);
                    }, 350);
                });

                usernameField.addEventListener('blur', function() {
                    validateUsernameField(this);
                    checkUsernameAvailability(this.value, true);
                });
            }

            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    this.value = this.value.trim().toLowerCase();
                    checkAccountAvailability('email', this.value, emailHint, __cfg_barangay_system_register_1.v8);
                });

                emailInput.addEventListener('blur', function() {
                    checkAccountAvailability('email', this.value, emailHint, __cfg_barangay_system_register_1.v8, true);
                });
            }

            if (contactInput) {
                contactInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 11);
                    checkAccountAvailability('contact', this.value, contactHint, __cfg_barangay_system_register_1.v9);
                });

                contactInput.addEventListener('blur', function() {
                    checkAccountAvailability('contact', this.value, contactHint, __cfg_barangay_system_register_1.v9, true);
                });
            }

            async function checkUsernameAvailability(rawUsername, force = false) {
                const username = String(rawUsername || '').trim().toLowerCase();

                if (!force && username === usernameCheckState.value && (usernameCheckState.status === 'available' || usernameCheckState.status === 'unavailable')) {
                    return usernameCheckState.status === 'available';
                }

                if (username === '') {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'idle';
                    usernameCheckState.message = '';
                    if (usernameHint) {
                        usernameHint.textContent = __cfg_barangay_system_register_1.v10;
                        usernameHint.classList.remove('good', 'bad');
                    }
                    return false;
                }

                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(username) || username.length < 4) {
                    usernameCheckState.value = username;
                    usernameCheckState.status = 'invalid';
                    usernameCheckState.message = __cfg_barangay_system_register_1.v11;
                    if (usernameHint) {
                        usernameHint.textContent = username.length < 4
                            ? __cfg_barangay_system_register_1.v12
                            : __cfg_barangay_system_register_1.v13;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }

                const requestId = ++usernameCheckSeq;
                usernameCheckState.value = username;
                usernameCheckState.status = 'checking';
                usernameCheckState.message = __cfg_barangay_system_register_1.v7;

                if (usernameHint) {
                    usernameHint.textContent = __cfg_barangay_system_register_1.v7;
                    usernameHint.classList.remove('good', 'bad');
                }

                try {
                    const response = await fetch(`/register/check-availability?field=username&value=${encodeURIComponent(username)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const data = contentType.includes('application/json')
                        ? await response.json()
                        : { message: __cfg_barangay_system_register_1.v14 };

                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    if (response.ok && data.available) {
                        usernameCheckState.status = 'available';
                        usernameCheckState.message = data.message || __cfg_barangay_system_register_1.v15;
                        if (usernameHint) {
                            usernameHint.textContent = usernameCheckState.message;
                            usernameHint.classList.remove('bad');
                            usernameHint.classList.add('good');
                        }
                        return true;
                    }

                    usernameCheckState.status = 'unavailable';
                    usernameCheckState.message = data.message || __cfg_barangay_system_register_1.v16;
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                } catch (error) {
                    if (requestId !== usernameCheckSeq) {
                        return false;
                    }

                    usernameCheckState.status = 'error';
                    usernameCheckState.message = __cfg_barangay_system_register_1.v17;
                    if (usernameHint) {
                        usernameHint.textContent = usernameCheckState.message;
                        usernameHint.classList.remove('good');
                        usernameHint.classList.add('bad');
                    }
                    return false;
                }
            }

            const accountCheckSeq = {
                email: 0,
                contact: 0,
            };

            const accountCheckState = {
                email: { value: '', status: 'idle', message: '' },
                contact: { value: '', status: 'idle', message: '' },
            };

            async function checkAccountAvailability(field, rawValue, hintEl, label, force = false) {
                const normalized = field === 'contact'
                    ? String(rawValue || '').replace(/\D/g, '')
                    : String(rawValue || '').trim().toLowerCase();

                const defaultMessage = field === 'contact'
                    ? __cfg_barangay_system_register_1.v18
                    : __cfg_barangay_system_register_1.v19;

                if (!accountCheckState[field]) {
                    accountCheckState[field] = { value: '', status: 'idle', message: '' };
                    accountCheckSeq[field] = 0;
                }

                if (normalized === '') {
                    accountCheckState[field] = { value: normalized, status: 'idle', message: '' };
                    if (hintEl) {
                        hintEl.textContent = defaultMessage;
                        hintEl.classList.remove('good', 'bad');
                    }
                    return false;
                }

                if (field === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(normalized)) {
                    accountCheckState[field] = { value: normalized, status: 'invalid', message: __cfg_barangay_system_register_1.v20 };
                    if (hintEl) {
                        hintEl.textContent = __cfg_barangay_system_register_1.v20;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }

                if (field === 'contact' && !/^09\d{9}$/.test(normalized)) {
                    accountCheckState[field] = { value: normalized, status: 'invalid', message: __cfg_barangay_system_register_1.v21 };
                    if (hintEl) {
                        hintEl.textContent = normalized.length < 11
                            ? __cfg_barangay_system_register_1.v22
                            : __cfg_barangay_system_register_1.v21;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }

                if (!force && accountCheckState[field].value === normalized && ['available', 'unavailable'].includes(accountCheckState[field].status)) {
                    return accountCheckState[field].status === 'available';
                }

                const requestId = ++accountCheckSeq[field];
                accountCheckState[field] = { value: normalized, status: 'checking', message: __cfg_barangay_system_register_1.v23.replace(':field', label.toLowerCase()) };

                if (hintEl) {
                    hintEl.textContent = __cfg_barangay_system_register_1.v23.replace(':field', label.toLowerCase());
                    hintEl.classList.remove('good', 'bad');
                }

                try {
                    const response = await fetch(`/register/check-availability?field=${encodeURIComponent(field)}&value=${encodeURIComponent(normalized)}`, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const contentType = response.headers.get('content-type') || '';
                    const data = contentType.includes('application/json')
                        ? await response.json()
                        : { message: __cfg_barangay_system_register_1.v24.replace(':field', label) };

                    if (requestId !== accountCheckSeq[field]) {
                        return false;
                    }

                    if (response.ok && data.available) {
                        accountCheckState[field] = { value: normalized, status: 'available', message: data.message || __cfg_barangay_system_register_1.v25.replace(':field', label) };
                        if (hintEl) {
                            hintEl.textContent = accountCheckState[field].message;
                            hintEl.classList.remove('bad');
                            hintEl.classList.add('good');
                        }
                        return true;
                    }

                    accountCheckState[field] = { value: normalized, status: 'unavailable', message: data.message || __cfg_barangay_system_register_1.v26.replace(':field', label) };
                    if (hintEl) {
                        hintEl.textContent = accountCheckState[field].message;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                } catch (error) {
                    if (requestId !== accountCheckSeq[field]) {
                        return false;
                    }

                    accountCheckState[field] = {
                        value: normalized,
                        status: 'error',
                        message: __cfg_barangay_system_register_1.v27.replace(':field', label),
                    };

                    if (hintEl) {
                        hintEl.textContent = accountCheckState[field].message;
                        hintEl.classList.remove('good');
                        hintEl.classList.add('bad');
                    }
                    return false;
                }
            }

            function validateUsernameField(field) {
                const fieldValue = field.value.trim();

                field.classList.remove('is-invalid');
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (!fieldValue) {
                    showFieldError(field, __cfg_barangay_system_register_1.v28);
                    return false;
                }

                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (!usernameRegex.test(fieldValue)) {
                    showFieldError(field, __cfg_barangay_system_register_1.v29);
                    return false;
                }

                if (fieldValue.length < 4) {
                    showFieldError(field, __cfg_barangay_system_register_1.v12);
                    return false;
                }

                return true;
            }

            function updatePasswordMatchUI() {
                if (!passwordInput || !confirmPasswordInput || !matchText) return true;

                const password = passwordInput.value;
                const confirm = confirmPasswordInput.value;

                const confirmGroup = confirmPasswordInput.closest('.form-group');
                if (confirmGroup) {
                    const existingErrors = confirmGroup.querySelectorAll('.error-message:not(#passwordMatchText)');
                    existingErrors.forEach(error => error.remove());
                }

                if (!confirm) {
                    matchText.textContent = __cfg_barangay_system_register_1.v30;
                    matchText.classList.remove("good", "bad");
                    confirmPasswordInput.classList.remove("is-invalid");
                    return false;
                }

                const matched = password === confirm;

                matchText.textContent = matched ? __cfg_barangay_system_register_1.v31 : __cfg_barangay_system_register_1.v32;
                matchText.classList.toggle("good", matched);
                matchText.classList.toggle("bad", !matched);
                confirmPasswordInput.classList.toggle("is-invalid", !matched);

                return matched;
            }

            function validateStep(stepNumber) {
                let isValid = true;
                const currentStepElement = document.getElementById(`step${stepNumber}`);

                const existingErrors = currentStepElement.querySelectorAll('.error-message:not(.alert *):not(#passwordMatchText)');
                existingErrors.forEach(error => error.remove());

                buildResidentAddress();

                const allFields = currentStepElement.querySelectorAll('.form-control');
                allFields.forEach(field => field.classList.remove('is-invalid'));
                currentStepElement.querySelectorAll('.file-upload-wrapper.is-invalid, .id-auto-scan-wrapper.is-invalid').forEach(wrapper => wrapper.classList.remove('is-invalid'));

                const requiredFields = currentStepElement.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (field.type === 'checkbox') {
                        if (!field.checked) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v33);
                        }
                        return;
                    }

                    if (field.type === 'file') {
                        if (!field.files || field.files.length === 0) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v34);
                        } else {
                            const fileSize = field.files[0].size;
                            if (fileSize > 5242880) {
                                isValid = false;
                                showFieldError(field, __cfg_barangay_system_register_1.v35);
                            }

                            const fileType = field.files[0].type;
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                            if (!allowedTypes.includes(fileType)) {
                                isValid = false;
                                showFieldError(field, __cfg_barangay_system_register_1.v36);
                            }

                            if ((field.id === 'valid_id' || field.id === 'valid_id_back')
                                && field.dataset.uploadMode === 'scanner'
                                && !scannerState.qualityResults[field.id]?.passed) {
                                isValid = false;
                                showFieldError(field, field.id === 'valid_id_back'
                                    ? __cfg_barangay_system_register_1.v37
                                    : __cfg_barangay_system_register_1.v38);
                            }
                        }
                        return;
                    }

                    const fieldValue = field.value.trim();

                    if (!fieldValue) {
                        isValid = false;
                        showFieldError(field, __cfg_barangay_system_register_1.v39);
                        return;
                    }

                    if (['firstname', 'middlename', 'lastname'].includes(field.id)) {
                        const nameRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s'.-]+$/;
                        if (!nameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v5);
                        } else if (fieldValue.length < 2) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v6);
                        }
                    }

                    if (field.id === 'username') {
                        const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                        if (!usernameRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v29);
                        } else if (fieldValue.length < 4) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v12);
                        } else if (usernameCheckState.value === fieldValue.toLowerCase() && usernameCheckState.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v16);
                        }
                    }

                    if (field.id === 'address' && fieldValue.length < 10) {
                        isValid = false;
                        showFieldError(field, __cfg_barangay_system_register_1.v40);
                    }

                    if (field.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v20);
                        } else if (accountCheckState.email?.value === fieldValue.toLowerCase() && accountCheckState.email?.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, accountCheckState.email.message || __cfg_barangay_system_register_1.v41);
                        }
                    }

                    if (field.id === 'contact') {
                        const phoneRegex = /^09\d{9}$/;
                        if (!phoneRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v21);
                        } else if (accountCheckState.contact?.value === fieldValue && accountCheckState.contact?.status === 'unavailable') {
                            isValid = false;
                            showFieldError(field, accountCheckState.contact.message || __cfg_barangay_system_register_1.v42);
                        }
                    }

                    if (field.id === 'place_of_birth') {
                        const pobRegex = /^[A-Za-zÁáÀàÂâÄäÃãÅåÆæÇçÐðÉéÈèÊêËëÍíÌìÎîÏïÑñÓóÒòÔôÖöÕõØøÞþÚúÙùÛûÜüÝýŸÿ\s.,'-]+$/;

                        if (!pobRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v43);
                        } else if (fieldValue.length < 2) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v44);
                        }
                    }

                    if (field.id === 'birthdate') {
                        const birthDate = new Date(fieldValue);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (birthDate > today) {
                            isValid = false;
                            showFieldError(field, __cfg_barangay_system_register_1.v45);
                        } else {
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();

                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }

                            if (age < 18) {
                                isValid = false;
                                showFieldError(field, __cfg_barangay_system_register_1.v46);
                            }
                        }
                    }
                });

                if (stepNumber === 2) {
                    if (!validatePasswordStrength()) isValid = false;
                    if (!updatePasswordMatchUI()) {
                        isValid = false;
                    }

                    if (termsCheckbox && !termsCheckbox.checked) {
                        isValid = false;
                        showFieldError(termsCheckbox, __cfg_barangay_system_register_1.v47);
                    }
                }

                return isValid;
            }

            function showFieldError(field, message) {
                field.classList.add('is-invalid');

                const parent = field.type === 'checkbox' ? field.closest('.terms-section') :
                            field.type === 'file' ? (field.closest('.file-upload-wrapper') || document.getElementById('idAutoScanWrapper')) :
                            field.parentNode;

                if (field.type === 'file' && parent) {
                    parent.classList.add('is-invalid');
                }

                if (parent) {
                    const existingError = parent.querySelector('.error-message:not(#passwordMatchText)');
                    if (existingError) {
                        existingError.textContent = message;
                        existingError.dataset.clientError = 'true';
                        return;
                    }

                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'error-message';
                    errorMessage.dataset.clientError = 'true';
                    errorMessage.textContent = message;
                    parent.appendChild(errorMessage);
                }
            }

            function clearCaptchaError() {
                const existingError = document.getElementById('captchaError');
                if (existingError) {
                    existingError.remove();
                }
            }

            function showCaptchaError(message) {
                clearCaptchaError();
                const parent = recaptchaBox ? recaptchaBox.parentNode : document.querySelector('.captcha-section');
                if (!parent) {
                    return;
                }

                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.id = 'captchaError';
                errorMessage.textContent = message;
                parent.appendChild(errorMessage);
            }

            if (birthdateInput) {
                const today = new Date();
                const minAge = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                birthdateInput.max = minAge.toISOString().split("T")[0];
            }

            uploadInputs.forEach((input) => {
                input.addEventListener('change', function(e) {
                    const textId = this.getAttribute('data-file-text');
                    const textEl = textId ? document.getElementById(textId) : null;
                    const desktopFileText = document.querySelector(`[data-desktop-file-name="${this.id}"]`);
                    const defaultText = this.id === 'valid_id_back' ? __cfg_barangay_system_register_1.v48 : __cfg_barangay_system_register_1.v49;
                    const file = e.target.files[0] || null;
                    const wrapper = this.closest('.file-upload-wrapper');
                    const isScannerAssignment = this.dataset.scannerAssignment === '1';

                    delete this.dataset.scannerAssignment;

                    if (file) {
                        this.dataset.uploadMode = isScannerAssignment ? 'scanner' : 'manual';

                        if (!isScannerAssignment) {
                            delete scannerState.qualityResults[this.id];
                            delete scannerState.captureSignatures[this.id];
                        }
                    } else {
                        delete this.dataset.uploadMode;
                        delete scannerState.qualityResults[this.id];
                        delete scannerState.captureSignatures[this.id];
                    }

                    if (wrapper) {
                        wrapper.classList.remove('is-invalid');
                        wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                    }

                    if (textEl) {
                        textEl.textContent = file ? file.name : defaultText;
                    }

                    if (desktopFileText) {
                        desktopFileText.textContent = file
                            ? file.name
                            : (this.id === 'valid_id_back' ? 'Choose back image or PDF' : 'Choose front image or PDF');
                    }

                    updateSelectedFilePreview(this, file);

                    if (!file) {
                        updateAutoScanStatus();
                        return;
                    }

                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

                    if (file.size > 5242880) {
                        this.value = '';
                        delete this.dataset.uploadMode;
                        if (textEl) textEl.textContent = defaultText;
                        if (desktopFileText) {
                            desktopFileText.textContent = this.id === 'valid_id_back'
                                ? 'Choose back image or PDF'
                                : 'Choose front image or PDF';
                        }
                        showFieldError(this, __cfg_barangay_system_register_1.v35);
                        updateAutoScanStatus();
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        this.value = '';
                        delete this.dataset.uploadMode;
                        if (textEl) textEl.textContent = defaultText;
                        if (desktopFileText) {
                            desktopFileText.textContent = this.id === 'valid_id_back'
                                ? 'Choose back image or PDF'
                                : 'Choose front image or PDF';
                        }
                        showFieldError(this, __cfg_barangay_system_register_1.v36);
                        updateAutoScanStatus();
                        return;
                    }

                    updateAutoScanStatus();
                });
            });

            const scannerModalElement = document.getElementById('idScannerModal');
            const scannerModal = scannerModalElement ? new bootstrap.Modal(scannerModalElement) : null;
            const scannerVideo = document.getElementById('idScannerVideo');
            const scannerOverlay = document.getElementById('idScannerOverlay');
            const scannerWorkCanvas = document.getElementById('idScannerWorkCanvas');
            const scannerResultCanvas = document.getElementById('idScannerResultCanvas');
            const scannerStatus = document.getElementById('idScannerStatus');
            const scannerStatusText = document.getElementById('idScannerStatusText');
            const scannerTargetText = document.getElementById('idScannerTargetText');
            const scannerSideBanner = document.getElementById('idScannerSideBanner');
            const scannerStepText = document.getElementById('idScannerStepText');
            const scannerSideText = document.getElementById('idScannerSideText');
            const scannerSideHelp = document.getElementById('idScannerSideHelp');
            const scannerTransitionOverlay = document.getElementById('idScannerTransitionOverlay');
            const scannerTransitionTitle = document.getElementById('idScannerTransitionTitle');
            const scannerTransitionText = document.getElementById('idScannerTransitionText');
            const scannerTransitionCountdown = document.getElementById('idScannerTransitionCountdown');
            const scannerAutoCapture = document.getElementById('idScannerAutoCapture');
            const scannerManualCaptureBtn = document.getElementById('idScannerManualCaptureBtn');
            const scannerRetakeBtn = document.getElementById('idScannerRetakeBtn');
            const scanBothSidesBtn = document.getElementById('scanBothSidesBtn');

            const scannerFlowSides = [
                { inputId: 'valid_id', label: __cfg_barangay_system_register_1.v50, title: __cfg_barangay_system_register_1.v51 },
                { inputId: 'valid_id_back', label: __cfg_barangay_system_register_1.v52, title: __cfg_barangay_system_register_1.v51 },
            ];

            const SCANNER_STABLE_FRAME_TARGET = 12;
            const SCANNER_CANDIDATE_SAMPLE_INTERVAL_MS = 120;
            const SCANNER_MIN_GOOD_CANDIDATES = 4;
            const SCANNER_MAX_MOTION_DIFFERENCE = 0.045;
            const SCANNER_MIN_STABLE_DURATION_MS = 550;
            const SCANNER_AUTOFOCUS_SETTLE_MS = 500;

            const scannerState = {
                targetInput: null,
                targetLabel: __cfg_barangay_system_register_1.v50,
                scanMode: 'single',
                flowIndex: 0,
                stream: null,
                rafId: null,
                frameCallbackType: null,
                stableFrames: 0,
                stableStartedAt: 0,
                consecutiveClearFrames: 0,
                previousMotionSample: null,
                lastPoints: null,
                detectedPoints: null,
                isCapturing: false,
                isProcessing: false,
                isFlowActive: false,
                isWaitingForFlip: false,
                completedBothSides: false,
                qualityResults: {},
                captureSignatures: {},
                candidateFrames: [],
                detectionFrames: 0,
                lastCandidateAt: 0,
                lastCandidateMessage: '',
                flipTimerId: null,
                flipIntervalId: null,
            };

            function setScannerStatus(message, state = 'waiting') {
                if (scannerStatusText) scannerStatusText.textContent = message;
                if (!scannerStatus) return;
                scannerStatus.classList.toggle('is-ready', state === 'ready');
                scannerStatus.classList.toggle('is-error', state === 'error');
            }

            function updateScannerSideUi(inputId) {
                const isBack = inputId === 'valid_id_back';
                const total = scannerState.scanMode === 'both' ? 2 : 1;
                const current = scannerState.scanMode === 'both' ? (isBack ? 2 : 1) : 1;

                if (scannerStepText) scannerStepText.textContent = `${current} / ${total}`;
                if (scannerSideText) scannerSideText.textContent = isBack ? 'BACK SIDE' : 'FRONT SIDE';
                if (scannerSideHelp) {
                    scannerSideHelp.textContent = isBack
                        ? 'Show the whole back side. It may have fewer printed edges, so keep the card inside the guide.'
                        : 'Keep the entire ID visible. For laminated cards, tilt slightly to move glare away from the text.';
                }
                if (scannerSideBanner) scannerSideBanner.classList.toggle('is-back', isBack);
            }

            function hideScannerTransition() {
                if (scannerState.flipTimerId) window.clearTimeout(scannerState.flipTimerId);
                if (scannerState.flipIntervalId) window.clearInterval(scannerState.flipIntervalId);
                scannerState.flipTimerId = null;
                scannerState.flipIntervalId = null;
                if (scannerTransitionOverlay) scannerTransitionOverlay.classList.add('d-none');
            }

            function showBackSideTransition(onComplete) {
                hideScannerTransition();

                let seconds = 3;
                if (scannerTransitionTitle) scannerTransitionTitle.textContent = 'FRONT SIDE CAPTURED';
                if (scannerTransitionText) scannerTransitionText.textContent = 'Flip the ID now. The scanner will capture the BACK side next.';
                if (scannerTransitionCountdown) scannerTransitionCountdown.textContent = String(seconds);
                if (scannerTransitionOverlay) scannerTransitionOverlay.classList.remove('d-none');

                if (navigator.vibrate) navigator.vibrate([140, 80, 140]);

                scannerState.flipIntervalId = window.setInterval(() => {
                    seconds -= 1;
                    if (scannerTransitionCountdown) scannerTransitionCountdown.textContent = String(Math.max(1, seconds));
                }, 1000);

                scannerState.flipTimerId = window.setTimeout(() => {
                    hideScannerTransition();
                    onComplete();
                }, 3000);
            }

            function resetScannerDetection() {
                scannerState.stableFrames = 0;
                scannerState.stableStartedAt = 0;
                scannerState.consecutiveClearFrames = 0;
                scannerState.previousMotionSample = null;
                scannerState.lastPoints = null;
                scannerState.detectedPoints = null;
                scannerState.isProcessing = false;
                scannerState.candidateFrames = [];
                scannerState.detectionFrames = 0;
                scannerState.lastCandidateAt = 0;
                scannerState.lastCandidateMessage = '';

                if (scannerOverlay) {
                    const context = scannerOverlay.getContext('2d');
                    context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
                }
            }

            function setScannerTarget(inputId, label = 'ID side') {
                const targetInput = document.getElementById(inputId);
                if (!targetInput) return false;

                scannerState.targetInput = targetInput;
                scannerState.targetLabel = label;

                if (scannerTargetText) scannerTargetText.textContent = label;
                updateScannerSideUi(inputId);

                const title = document.getElementById('idScannerModalLabel');
                if (title) title.textContent = __cfg_barangay_system_register_1.v51;

                resetScannerDetection();
                return true;
            }

            function openScannerForSide(inputId, label = __cfg_barangay_system_register_1.v53, mode = 'single') {
                if (!scannerModal) return;

                scannerState.scanMode = mode;
                scannerState.flowIndex = inputId === 'valid_id_back' ? 1 : 0;

                if (!setScannerTarget(inputId, label)) return;
                scannerModal.show();
            }

            function openScannerForBothSides() {
                if (!scannerModal || scannerState.isFlowActive) return;

                resetBothIdScannerFiles();

                scannerState.scanMode = 'both';
                scannerState.flowIndex = 0;
                scannerState.isFlowActive = true;
                scannerState.isWaitingForFlip = false;
                scannerState.completedBothSides = false;
                updateScanButtonState();

                const firstSide = scannerFlowSides[0];
                if (!setScannerTarget(firstSide.inputId, firstSide.label)) {
                    scannerState.isFlowActive = false;
                    updateScanButtonState();
                    return;
                }

                updateAutoScanStatus();
                setScannerStatus(__cfg_barangay_system_register_1.v54, 'waiting');

                scannerModal.show();
            }

            function updateSelectedFilePreview(input, file) {
                const previewId = input.getAttribute('data-preview');
                const shellId = input.getAttribute('data-preview-shell');
                const preview = previewId ? document.getElementById(previewId) : null;
                const shell = shellId ? document.getElementById(shellId) : null;

                if (!preview || !shell) return;

                if (!file || !file.type.startsWith('image/')) {
                    preview.removeAttribute('src');
                    preview.classList.add('d-none');
                    shell.classList.add('is-empty');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                    shell.classList.remove('is-empty');
                };
                reader.readAsDataURL(file);
            }

            function updateScanButtonState() {
                if (!scanBothSidesBtn) return;

                const isActive = scannerState.isFlowActive === true;
                scanBothSidesBtn.disabled = isActive;

                if (isActive) {
                    scanBothSidesBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${__cfg_barangay_system_register_1.v55}`;
                    return;
                }

                if (hasValidScannerCaptures()) {
                    scanBothSidesBtn.innerHTML = `<i class="fas fa-rotate-right"></i> ${__cfg_barangay_system_register_1.v56}`;
                    return;
                }

                scanBothSidesBtn.innerHTML = `<i class="fas fa-camera-retro"></i> ${__cfg_barangay_system_register_1.v57}`;
            }

            function updateAutoScanStatus() {
                const frontInput = document.getElementById('valid_id');
                const backInput = document.getElementById('valid_id_back');
                const wrapper = document.getElementById('idAutoScanWrapper');
                const status = document.getElementById('idAutoScanStatus');
                const title = document.getElementById('idAutoScanStatusTitle');
                const text = document.getElementById('idAutoScanStatusText');

                const frontPassed = scannerState.qualityResults.valid_id?.passed === true;
                const backPassed = scannerState.qualityResults.valid_id_back?.passed === true;
                const frontReady = frontInput?.dataset.uploadMode === 'manual' || frontPassed;
                const backReady = backInput?.dataset.uploadMode === 'manual' || backPassed;
                const hasFront = !!frontInput?.files?.length && frontReady;
                const hasBack = !!backInput?.files?.length && backReady;

                if (wrapper && hasFront && hasBack) {
                    wrapper.classList.remove('is-invalid');
                    wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                }

                if (!status || !title || !text) {
                    updateScanButtonState();
                    return;
                }

                status.classList.toggle('is-complete', hasFront && hasBack);

                if (hasFront && hasBack) {
                    title.textContent = __cfg_barangay_system_register_1.v58;
                    text.textContent = __cfg_barangay_system_register_1.v59;
                } else if (hasFront) {
                    title.textContent = __cfg_barangay_system_register_1.v60;
                    text.textContent = __cfg_barangay_system_register_1.v61;
                } else if (hasBack) {
                    title.textContent = __cfg_barangay_system_register_1.v62;
                    text.textContent = __cfg_barangay_system_register_1.v63;
                } else {
                    title.textContent = __cfg_barangay_system_register_1.v64;
                    text.textContent = __cfg_barangay_system_register_1.v65;
                }

                updateScanButtonState();
            }

            function distanceBetweenPoints(a, b) {
                return Math.hypot(a.x - b.x, a.y - b.y);
            }

            function orderQuadPoints(points) {
                const ordered = points.slice().sort((a, b) => a.x - b.x);
                const left = ordered.slice(0, 2).sort((a, b) => a.y - b.y);
                const right = ordered.slice(2).sort((a, b) => a.y - b.y);

                const tl = left[0];
                const bl = left[1];
                const tr = right[0];
                const br = right[1];

                return [tl, tr, br, bl];
            }

            function arePointsStable(currentPoints, previousPoints) {
                if (!currentPoints || !previousPoints) return false;

                const current = orderQuadPoints(currentPoints);
                const previous = orderQuadPoints(previousPoints);
                const averageMovement = current.reduce((total, point, index) => {
                    return total + distanceBetweenPoints(point, previous[index]);
                }, 0) / 4;

                const cardWidth = Math.max(
                    distanceBetweenPoints(current[0], current[1]),
                    distanceBetweenPoints(current[3], current[2])
                );
                const cardHeight = Math.max(
                    distanceBetweenPoints(current[0], current[3]),
                    distanceBetweenPoints(current[1], current[2])
                );
                const cardDiagonal = Math.hypot(cardWidth, cardHeight);

                const movementTolerance = Math.max(6, Math.min(16, cardDiagonal * 0.012));
                return averageMovement <= movementTolerance;
            }

            function syncScannerCanvases() {
                if (!scannerVideo || !scannerOverlay || !scannerWorkCanvas) return;

                const width = scannerVideo.videoWidth || 1280;
                const height = scannerVideo.videoHeight || 720;

                [scannerOverlay, scannerWorkCanvas].forEach((canvas) => {
                    if (canvas.width !== width || canvas.height !== height) {
                        canvas.width = width;
                        canvas.height = height;
                    }
                });
            }

            function drawScannerOverlay(points) {
                if (!scannerOverlay || !scannerVideo) return;

                syncScannerCanvases();
                const context = scannerOverlay.getContext('2d');
                context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);

                if (!points || points.length !== 4) return;

                const ordered = orderQuadPoints(points);
                context.lineWidth = Math.max(4, scannerOverlay.width * 0.004);
                context.strokeStyle = '#22c55e';
                context.fillStyle = 'rgba(34, 197, 94, 0.12)';
                context.beginPath();
                context.moveTo(ordered[0].x, ordered[0].y);
                ordered.slice(1).forEach(point => context.lineTo(point.x, point.y));
                context.closePath();
                context.fill();
                context.stroke();
            }

            function getIdCardPoints() {
                if (!window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas) return null;

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                let source = null;
                let gray = null;
                let blurred = null;
                let equalized = null;
                let brightMask = null;
                let darkMask = null;
                let edgeMask = null;
                let largeKernel = null;
                let smallKernel = null;
                let openKernel = null;

                try {
                    source = cv.imread(scannerWorkCanvas);
                    gray = new cv.Mat();
                    blurred = new cv.Mat();
                    equalized = new cv.Mat();
                    brightMask = new cv.Mat();
                    darkMask = new cv.Mat();
                    edgeMask = new cv.Mat();

                    largeKernel = cv.getStructuringElement(cv.MORPH_RECT, new cv.Size(9, 9));
                    smallKernel = cv.getStructuringElement(cv.MORPH_RECT, new cv.Size(5, 5));
                    openKernel = cv.getStructuringElement(cv.MORPH_RECT, new cv.Size(3, 3));

                    cv.cvtColor(source, gray, cv.COLOR_RGBA2GRAY);
                    cv.GaussianBlur(gray, blurred, new cv.Size(5, 5), 0);
                    cv.equalizeHist(gray, equalized);

                    cv.threshold(blurred, brightMask, 0, 255, cv.THRESH_BINARY + cv.THRESH_OTSU);
                    cv.morphologyEx(brightMask, brightMask, cv.MORPH_CLOSE, largeKernel, new cv.Point(-1, -1), 2);
                    cv.morphologyEx(brightMask, brightMask, cv.MORPH_OPEN, openKernel);

                    cv.threshold(blurred, darkMask, 0, 255, cv.THRESH_BINARY_INV + cv.THRESH_OTSU);
                    cv.morphologyEx(darkMask, darkMask, cv.MORPH_CLOSE, largeKernel, new cv.Point(-1, -1), 2);
                    cv.morphologyEx(darkMask, darkMask, cv.MORPH_OPEN, openKernel);

                    cv.Canny(equalized, edgeMask, 35, 125);
                    cv.morphologyEx(edgeMask, edgeMask, cv.MORPH_CLOSE, smallKernel, new cv.Point(-1, -1), 2);

                    const frameArea = source.rows * source.cols;
                    const frameCenter = { x: source.cols / 2, y: source.rows / 2 };
                    const idealRatio = 1.586;
                    let bestPoints = null;
                    let bestScore = -Infinity;

                    const detectionPasses = [
                        { mat: brightMask, type: 'tone', retrieval: cv.RETR_EXTERNAL, bonus: 0.28 },
                        { mat: darkMask, type: 'tone', retrieval: cv.RETR_EXTERNAL, bonus: 0.18 },
                        { mat: edgeMask, type: 'edge', retrieval: cv.RETR_LIST, bonus: 0 },
                    ];

                    for (const pass of detectionPasses) {
                        const contours = new cv.MatVector();
                        const hierarchy = new cv.Mat();
                        const contourInput = pass.mat.clone();

                        try {
                            cv.findContours(contourInput, contours, hierarchy, pass.retrieval, cv.CHAIN_APPROX_SIMPLE);

                            for (let i = 0; i < contours.size(); i++) {
                                const contour = contours.get(i);
                                let approx = null;

                                try {
                                    const area = cv.contourArea(contour);
                                    const areaRatio = area / Math.max(1, frameArea);

                                    if (areaRatio < 0.025 || areaRatio > 0.84) continue;

                                    const rect = cv.boundingRect(contour);
                                    const widthCoverage = rect.width / Math.max(1, source.cols);
                                    const heightCoverage = rect.height / Math.max(1, source.rows);

                                    if (widthCoverage > 0.97 && heightCoverage > 0.97) continue;
                                    if (rect.width < source.cols * 0.14 || rect.height < source.rows * 0.08) continue;

                                    const rectArea = Math.max(1, rect.width * rect.height);
                                    const rectangularity = area / rectArea;
                                    const perimeter = cv.arcLength(contour, true);
                                    approx = new cv.Mat();
                                    cv.approxPolyDP(contour, approx, 0.02 * perimeter, true);

                                    let points = null;

                                    if (approx.rows === 4 && cv.isContourConvex(approx)) {
                                        points = [];
                                        for (let row = 0; row < 4; row++) {
                                            points.push({
                                                x: approx.data32S[row * 2],
                                                y: approx.data32S[row * 2 + 1],
                                            });
                                        }
                                    } else {
                                        const minimumRectangularity = pass.type === 'tone' ? 0.48 : 0.28;
                                        if (rectangularity < minimumRectangularity) continue;

                                        points = [
                                            { x: rect.x, y: rect.y },
                                            { x: rect.x + rect.width, y: rect.y },
                                            { x: rect.x + rect.width, y: rect.y + rect.height },
                                            { x: rect.x, y: rect.y + rect.height },
                                        ];
                                    }

                                    const ordered = orderQuadPoints(points);
                                    const topWidth = distanceBetweenPoints(ordered[0], ordered[1]);
                                    const bottomWidth = distanceBetweenPoints(ordered[3], ordered[2]);
                                    const leftHeight = distanceBetweenPoints(ordered[0], ordered[3]);
                                    const rightHeight = distanceBetweenPoints(ordered[1], ordered[2]);
                                    const detectedWidth = Math.max(topWidth, bottomWidth);
                                    const detectedHeight = Math.max(leftHeight, rightHeight);
                                    const ratio = Math.max(detectedWidth, detectedHeight) /
                                        Math.max(1, Math.min(detectedWidth, detectedHeight));

                                    if (ratio < 1.08 || ratio > 2.65) continue;

                                    const center = ordered.reduce((result, point) => ({
                                        x: result.x + point.x / 4,
                                        y: result.y + point.y / 4,
                                    }), { x: 0, y: 0 });

                                    const normalizedCenterDistance = Math.hypot(
                                        center.x - frameCenter.x,
                                        center.y - frameCenter.y
                                    ) / Math.max(1, Math.hypot(frameCenter.x, frameCenter.y));

                                    const centerScore = Math.max(0, 1 - normalizedCenterDistance);
                                    const ratioScore = Math.max(0, 1 - Math.abs(ratio - idealRatio) / 1.10);

                                    const touchesFrameEdges = [
                                        rect.x <= 2,
                                        rect.y <= 2,
                                        rect.x + rect.width >= source.cols - 2,
                                        rect.y + rect.height >= source.rows - 2,
                                    ].filter(Boolean).length;

                                    const score =
                                        (areaRatio * 5.0) +
                                        (ratioScore * 1.4) +
                                        (centerScore * 0.50) +
                                        (rectangularity * 0.70) +
                                        pass.bonus -
                                        (touchesFrameEdges * 0.12);

                                    if (score > bestScore) {
                                        bestScore = score;
                                        bestPoints = points;
                                    }
                                } finally {
                                    if (approx) approx.delete();
                                    contour.delete();
                                }
                            }
                        } finally {
                            contourInput.delete();
                            contours.delete();
                            hierarchy.delete();
                        }
                    }

                    return bestPoints;
                } catch (error) {
                    console.warn('ID scanner detection failed:', error);
                    return null;
                } finally {
                    if (source) source.delete();
                    if (gray) gray.delete();
                    if (blurred) blurred.delete();
                    if (equalized) equalized.delete();
                    if (brightMask) brightMask.delete();
                    if (darkMask) darkMask.delete();
                    if (edgeMask) edgeMask.delete();
                    if (largeKernel) largeKernel.delete();
                    if (smallKernel) smallKernel.delete();
                    if (openKernel) openKernel.delete();
                }
            }

            function getGuideFallbackPoints() {
                if (!scannerWorkCanvas) return null;

                const frameWidth = scannerWorkCanvas.width || scannerVideo?.videoWidth || 1280;
                const frameHeight = scannerWorkCanvas.height || scannerVideo?.videoHeight || 720;
                const targetRatio = 1.586;
                const maxWidth = frameWidth * 0.78;
                const maxHeight = frameHeight * 0.58;
                const width = Math.min(maxWidth, maxHeight * targetRatio);
                const height = width / targetRatio;
                const left = (frameWidth - width) / 2;
                const top = (frameHeight - height) / 2;

                return [
                    { x: left, y: top },
                    { x: left + width, y: top },
                    { x: left + width, y: top + height },
                    { x: left, y: top + height },
                ];
            }

            function cropGuideFallback() {
                if (!scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) return null;

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                const sourceWidth = scannerWorkCanvas.width;
                const sourceHeight = scannerWorkCanvas.height;
                const guidePoints = getGuideFallbackPoints();
                const cropX = Math.max(0, Math.round(guidePoints[0].x));
                const cropY = Math.max(0, Math.round(guidePoints[0].y));
                const cropWidth = Math.min(sourceWidth - cropX, Math.round(guidePoints[1].x - guidePoints[0].x));
                const cropHeight = Math.min(sourceHeight - cropY, Math.round(guidePoints[3].y - guidePoints[0].y));

                scannerResultCanvas.width = cropWidth;
                scannerResultCanvas.height = cropHeight;
                scannerResultCanvas.getContext('2d').drawImage(
                    scannerWorkCanvas,
                    cropX,
                    cropY,
                    cropWidth,
                    cropHeight,
                    0,
                    0,
                    cropWidth,
                    cropHeight
                );

                return scannerResultCanvas;
            }

            function cropDetectedCard(points) {
                if (!points || !window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) {
                    return cropGuideFallback();
                }

                syncScannerCanvases();
                const workContext = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
                workContext.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

                const ordered = orderQuadPoints(points);
                const outputWidth = Math.round(Math.max(
                    distanceBetweenPoints(ordered[0], ordered[1]),
                    distanceBetweenPoints(ordered[3], ordered[2])
                ));
                const outputHeight = Math.round(Math.max(
                    distanceBetweenPoints(ordered[0], ordered[3]),
                    distanceBetweenPoints(ordered[1], ordered[2])
                ));

                let source = null;
                let destination = null;
                let transform = null;
                let sourceTriangle = null;
                let destinationTriangle = null;

                try {
                    source = cv.imread(scannerWorkCanvas);
                    destination = new cv.Mat();
                    sourceTriangle = cv.matFromArray(4, 1, cv.CV_32FC2, [
                        ordered[0].x, ordered[0].y,
                        ordered[1].x, ordered[1].y,
                        ordered[2].x, ordered[2].y,
                        ordered[3].x, ordered[3].y,
                    ]);
                    destinationTriangle = cv.matFromArray(4, 1, cv.CV_32FC2, [
                        0, 0,
                        outputWidth - 1, 0,
                        outputWidth - 1, outputHeight - 1,
                        0, outputHeight - 1,
                    ]);
                    transform = cv.getPerspectiveTransform(sourceTriangle, destinationTriangle);
                    cv.warpPerspective(source, destination, transform, new cv.Size(outputWidth, outputHeight));

                    scannerResultCanvas.width = outputWidth;
                    scannerResultCanvas.height = outputHeight;
                    cv.imshow(scannerResultCanvas, destination);
                    return scannerResultCanvas;
                } catch (error) {
                    console.warn('ID scanner crop failed, using fallback crop:', error);
                    return cropGuideFallback();
                } finally {
                    if (source) source.delete();
                    if (destination) destination.delete();
                    if (transform) transform.delete();
                    if (sourceTriangle) sourceTriangle.delete();
                    if (destinationTriangle) destinationTriangle.delete();
                }
            }

            function clearScannerInput(inputId) {
                const input = document.getElementById(inputId);
                if (!input) return;

                try {
                    input.value = '';
                } catch (error) {
                    const transfer = new DataTransfer();
                    input.files = transfer.files;
                }

                delete input.dataset.uploadMode;
                delete input.dataset.scannerAssignment;

                const textId = input.getAttribute('data-file-text');
                const textEl = textId ? document.getElementById(textId) : null;
                if (textEl) {
                    textEl.textContent = inputId === 'valid_id_back'
                        ? __cfg_barangay_system_register_1.v48
                        : __cfg_barangay_system_register_1.v49;
                }

                const desktopFileText = document.querySelector(`[data-desktop-file-name="${inputId}"]`);
                if (desktopFileText) {
                    desktopFileText.textContent = inputId === 'valid_id_back'
                        ? 'Choose back image or PDF'
                        : 'Choose front image or PDF';
                }
            }

            function resetBothIdScannerFiles() {
                ['valid_id', 'valid_id_back'].forEach((inputId) => clearScannerInput(inputId));
                scannerState.qualityResults = {};
                scannerState.captureSignatures = {};

                const wrapper = document.getElementById('idAutoScanWrapper');
                if (wrapper) {
                    wrapper.classList.remove('is-invalid');
                    wrapper.querySelectorAll('.error-message').forEach(error => error.remove());
                }

                updateAutoScanStatus();
            }

            function hasValidScannerCaptures() {
                const frontInput = document.getElementById('valid_id');
                const backInput = document.getElementById('valid_id_back');

                return !!frontInput?.files?.length
                    && !!backInput?.files?.length
                    && frontInput?.dataset.uploadMode === 'scanner'
                    && backInput?.dataset.uploadMode === 'scanner'
                    && scannerState.qualityResults.valid_id?.passed === true
                    && scannerState.qualityResults.valid_id_back?.passed === true;
            }

            function polygonArea(points) {
                if (!points || points.length < 4) return 0;

                let area = 0;
                for (let i = 0; i < points.length; i++) {
                    const current = points[i];
                    const next = points[(i + 1) % points.length];
                    area += (current.x * next.y) - (next.x * current.y);
                }

                return Math.abs(area / 2);
            }

            function getCanvasAnalysis(canvas) {
                const maxWidth = 640;
                const ratio = canvas.width > 0 ? canvas.height / canvas.width : 1;
                const width = Math.min(maxWidth, Math.max(1, canvas.width));
                const height = Math.max(1, Math.round(width * ratio));

                const sampleCanvas = document.createElement('canvas');
                sampleCanvas.width = width;
                sampleCanvas.height = height;

                const sampleContext = sampleCanvas.getContext('2d', { willReadFrequently: true });
                sampleContext.imageSmoothingEnabled = true;
                sampleContext.drawImage(canvas, 0, 0, width, height);

                const imageData = sampleContext.getImageData(0, 0, width, height).data;
                const grays = new Float32Array(width * height);
                let total = 0;
                let tooDarkPixels = 0;
                let tooBrightPixels = 0;

                for (let i = 0, p = 0; i < imageData.length; i += 4, p++) {
                    const gray = (imageData[i] * 0.299) + (imageData[i + 1] * 0.587) + (imageData[i + 2] * 0.114);
                    grays[p] = gray;
                    total += gray;

                    if (gray < 28) tooDarkPixels++;
                    if (gray > 245) tooBrightPixels++;
                }

                const pixelCount = grays.length || 1;
                const brightness = total / pixelCount;

                let varianceTotal = 0;
                for (let i = 0; i < grays.length; i++) {
                    varianceTotal += Math.pow(grays[i] - brightness, 2);
                }
                const contrast = Math.sqrt(varianceTotal / pixelCount);

                let sharpnessTotal = 0;
                let sharpnessCount = 0;
                const step = 2;

                for (let y = 0; y < height - step; y += step) {
                    for (let x = 0; x < width - step; x += step) {
                        const index = y * width + x;
                        const right = y * width + (x + step);
                        const bottom = (y + step) * width + x;
                        sharpnessTotal += Math.abs(grays[index] - grays[right]) + Math.abs(grays[index] - grays[bottom]);
                        sharpnessCount += 2;
                    }
                }

                const sharpness = sharpnessCount ? sharpnessTotal / sharpnessCount : 0;

                let laplacianTotal = 0;
                let laplacianSquaredTotal = 0;
                let laplacianCount = 0;
                let edgePixels = 0;
                let centerEdgePixels = 0;
                let centerPixelCount = 0;

                const centerLeft = Math.floor(width * 0.12);
                const centerRight = Math.floor(width * 0.88);
                const centerTop = Math.floor(height * 0.16);
                const centerBottom = Math.floor(height * 0.84);

                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const index = y * width + x;
                        const laplacian =
                            (grays[index - width] + grays[index - 1] + grays[index + 1] + grays[index + width])
                            - (4 * grays[index]);

                        const absLaplacian = Math.abs(laplacian);
                        laplacianTotal += laplacian;
                        laplacianSquaredTotal += laplacian * laplacian;
                        laplacianCount++;

                        if (absLaplacian > 18) edgePixels++;

                        if (x >= centerLeft && x <= centerRight && y >= centerTop && y <= centerBottom) {
                            centerPixelCount++;
                            if (absLaplacian > 18) centerEdgePixels++;
                        }
                    }
                }

                const laplacianMean = laplacianCount ? laplacianTotal / laplacianCount : 0;
                const laplacianVariance = laplacianCount
                    ? Math.max(0, (laplacianSquaredTotal / laplacianCount) - (laplacianMean * laplacianMean))
                    : 0;

                const edgeDensity = laplacianCount ? edgePixels / laplacianCount : 0;
                const centerEdgeDensity = centerPixelCount ? centerEdgePixels / centerPixelCount : 0;
                const darkPixelRatio = tooDarkPixels / pixelCount;
                const brightPixelRatio = tooBrightPixels / pixelCount;

                return {
                    brightness,
                    contrast,
                    sharpness,
                    laplacianVariance,
                    edgeDensity,
                    centerEdgeDensity,
                    darkPixelRatio,
                    brightPixelRatio,
                    sampledWidth: width,
                    sampledHeight: height,
                };
            }

            function getCanvasSignature(canvas) {
                const size = 32;
                const signatureCanvas = document.createElement('canvas');
                signatureCanvas.width = size;
                signatureCanvas.height = size;

                const signatureContext = signatureCanvas.getContext('2d', { willReadFrequently: true });
                signatureContext.drawImage(canvas, 0, 0, size, size);

                const imageData = signatureContext.getImageData(0, 0, size, size).data;
                const pixels = [];
                const histogram = new Array(16).fill(0);
                let total = 0;

                for (let i = 0; i < imageData.length; i += 4) {
                    const gray = ((imageData[i] * 0.299) + (imageData[i + 1] * 0.587) + (imageData[i + 2] * 0.114)) / 255;
                    pixels.push(gray);
                    total += gray;
                    const bucket = Math.min(15, Math.floor(gray * 16));
                    histogram[bucket] += 1;
                }

                const mean = pixels.length ? total / pixels.length : 0;
                const bits = pixels.map(value => value >= mean ? 1 : 0);
                const normalizedHistogram = histogram.map(count => count / Math.max(1, pixels.length));

                let variance = 0;
                for (const value of pixels) {
                    variance += Math.pow(value - mean, 2);
                }
                const std = pixels.length ? Math.sqrt(variance / pixels.length) : 0;

                return {
                    pixels,
                    bits,
                    histogram: normalizedHistogram,
                    mean,
                    std,
                    width: canvas.width,
                    height: canvas.height,
                    ratio: Math.max(canvas.width, canvas.height) / Math.max(1, Math.min(canvas.width, canvas.height)),
                };
            }

            function getSignaturePixels(signature) {
                if (!signature) return null;
                return Array.isArray(signature) ? signature : signature.pixels;
            }

            function averageSignatureDifference(firstSignature, secondSignature) {
                const firstPixels = getSignaturePixels(firstSignature);
                const secondPixels = getSignaturePixels(secondSignature);

                if (!firstPixels || !secondPixels || firstPixels.length !== secondPixels.length) {
                    return 1;
                }

                let diff = 0;
                for (let i = 0; i < firstPixels.length; i++) {
                    diff += Math.abs(firstPixels[i] - secondPixels[i]);
                }

                return diff / firstPixels.length;
            }

            function signatureHammingDifference(firstSignature, secondSignature) {
                if (!firstSignature?.bits || !secondSignature?.bits || firstSignature.bits.length !== secondSignature.bits.length) {
                    return 1;
                }

                let differentBits = 0;
                for (let i = 0; i < firstSignature.bits.length; i++) {
                    if (firstSignature.bits[i] !== secondSignature.bits[i]) differentBits++;
                }

                return differentBits / firstSignature.bits.length;
            }

            function signatureCorrelation(firstSignature, secondSignature) {
                const firstPixels = getSignaturePixels(firstSignature);
                const secondPixels = getSignaturePixels(secondSignature);

                if (!firstPixels || !secondPixels || firstPixels.length !== secondPixels.length) {
                    return 0;
                }

                const firstMean = firstPixels.reduce((sum, value) => sum + value, 0) / firstPixels.length;
                const secondMean = secondPixels.reduce((sum, value) => sum + value, 0) / secondPixels.length;

                let numerator = 0;
                let firstVariance = 0;
                let secondVariance = 0;

                for (let i = 0; i < firstPixels.length; i++) {
                    const firstDelta = firstPixels[i] - firstMean;
                    const secondDelta = secondPixels[i] - secondMean;
                    numerator += firstDelta * secondDelta;
                    firstVariance += firstDelta * firstDelta;
                    secondVariance += secondDelta * secondDelta;
                }

                const denominator = Math.sqrt(firstVariance * secondVariance);
                return denominator ? numerator / denominator : 0;
            }

            function signatureHistogramDifference(firstSignature, secondSignature) {
                if (!firstSignature?.histogram || !secondSignature?.histogram || firstSignature.histogram.length !== secondSignature.histogram.length) {
                    return 1;
                }

                let diff = 0;
                for (let i = 0; i < firstSignature.histogram.length; i++) {
                    diff += Math.abs(firstSignature.histogram[i] - secondSignature.histogram[i]);
                }

                return diff / 2;
            }

            function looksLikeSameIdSide(firstSignature, secondSignature) {
                if (!firstSignature || !secondSignature) {
                    return { duplicate: false, confidence: 0, details: null };
                }

                const averageDifference = averageSignatureDifference(firstSignature, secondSignature);
                const hammingDifference = signatureHammingDifference(firstSignature, secondSignature);
                const correlation = signatureCorrelation(firstSignature, secondSignature);
                const histogramDifference = signatureHistogramDifference(firstSignature, secondSignature);
                const ratioDifference = Math.abs((firstSignature.ratio || 0) - (secondSignature.ratio || 0));

                let confidence = 0;
                if (averageDifference < 0.085) confidence += 0.35;
                if (hammingDifference < 0.18) confidence += 0.30;
                if (correlation > 0.86) confidence += 0.25;
                if (histogramDifference < 0.12) confidence += 0.10;
                if (ratioDifference > 0.18) confidence -= 0.15;

                const duplicate = confidence >= 0.90
                    || (averageDifference < 0.045 && hammingDifference < 0.13 && correlation > 0.96)
                    || (correlation > 0.985 && hammingDifference < 0.10);

                return {
                    duplicate,
                    confidence,
                    details: {
                        averageDifference,
                        hammingDifference,
                        correlation,
                        histogramDifference,
                        ratioDifference,
                    },
                };
            }

            function validateIdCaptureQuality(canvas, points, inputId) {
                if (!canvas || !points || points.length !== 4) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v66,
                    };
                }

                const ordered = orderQuadPoints(points);
                const detectedWidth = Math.max(
                    distanceBetweenPoints(ordered[0], ordered[1]),
                    distanceBetweenPoints(ordered[3], ordered[2])
                );
                const detectedHeight = Math.max(
                    distanceBetweenPoints(ordered[0], ordered[3]),
                    distanceBetweenPoints(ordered[1], ordered[2])
                );
                const detectedRatio = Math.max(detectedWidth, detectedHeight) / Math.max(1, Math.min(detectedWidth, detectedHeight));
                const detectedArea = polygonArea(ordered);
                const frameArea = Math.max(1, scannerWorkCanvas.width * scannerWorkCanvas.height);
                const areaRatio = detectedArea / frameArea;

                if (areaRatio < 0.055) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v67,
                    };
                }

                if (areaRatio > 0.88) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v68,
                    };
                }

                if (detectedRatio < 1.05 || detectedRatio > 2.85) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v69,
                    };
                }

                const cropShortSide = Math.min(canvas.width, canvas.height);
                const cropLongSide = Math.max(canvas.width, canvas.height);
                const cropRatio = cropLongSide / Math.max(1, cropShortSide);

                if (cropShortSide < 160 || cropLongSide < 260) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v70,
                    };
                }

                if (cropRatio < 1.05 || cropRatio > 2.85) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v71,
                    };
                }

                const analysis = getCanvasAnalysis(canvas);

                if (analysis.brightness < 35) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v72,
                    };
                }

                if (analysis.brightness > 246) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v73,
                    };
                }

                if (analysis.contrast < 12) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v74,
                        analysis,
                    };
                }

                if (analysis.darkPixelRatio > 0.46) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v75,
                        analysis,
                    };
                }

                if (analysis.brightPixelRatio > 0.43) {
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v76,
                        analysis,
                    };
                }

                const isBackSide = inputId === 'valid_id_back';
                const blurSignals = [
                    analysis.laplacianVariance < (isBackSide ? 42 : 58),
                    analysis.sharpness < (isBackSide ? 5.2 : 6.0),
                    analysis.edgeDensity < (isBackSide ? 0.008 : 0.011),
                    analysis.centerEdgeDensity < (isBackSide ? 0.005 : 0.008),
                ].filter(Boolean).length;

                const absoluteBlurFloor = isBackSide ? 30 : 42;
                const requiredBlurSignals = isBackSide ? 3 : 2;
                const isBlurry = analysis.laplacianVariance < absoluteBlurFloor
                    || blurSignals >= requiredBlurSignals;

                if (isBlurry) {
                    console.info('ID scan rejected as blurry:', analysis);
                    return {
                        passed: false,
                        message: __cfg_barangay_system_register_1.v77,
                        analysis,
                    };
                }

                const signature = getCanvasSignature(canvas);

                if (inputId === 'valid_id_back') {
                    const frontSignature = scannerState.captureSignatures.valid_id;
                    const sameSideCheck = looksLikeSameIdSide(frontSignature, signature);

                    if (sameSideCheck.duplicate) {
                        console.info('Back ID rejected because it looks like the front side again:', sameSideCheck.details);
                        return {
                            passed: false,
                            message: __cfg_barangay_system_register_1.v78,
                            duplicateFront: true,
                            duplicateDetails: sameSideCheck.details,
                        };
                    }
                }

                return {
                    passed: true,
                    message: __cfg_barangay_system_register_1.v79,
                    analysis,
                    signature,
                    areaRatio,
                    detectedRatio,
                    cropRatio,
                };
            }

            function rejectCurrentScannerCapture(inputId, message) {
                const sideLabel = inputId === 'valid_id_back' ? __cfg_barangay_system_register_1.v52 : __cfg_barangay_system_register_1.v50;
                scannerState.qualityResults[inputId] = {
                    passed: false,
                    message,
                };
                clearScannerInput(inputId);
                updateAutoScanStatus();
                resetScannerDetection();
                const nextInstruction = inputId === 'valid_id_back'
                    ? __cfg_barangay_system_register_1.v80
                    : __cfg_barangay_system_register_1.v81;

                setScannerStatus(__cfg_barangay_system_register_1.v82.replace(':side', sideLabel).replace(':message', message), 'error');

                window.setTimeout(() => {
                    scannerState.isCapturing = false;
                    scannerState.isWaitingForFlip = false;
                    if (scannerState.stream) {
                        setScannerStatus(`${nextInstruction}`, 'waiting');
                        scannerFrameLoop();
                    }
                }, 1400);
            }

            function cloneScannerPoints(points) {
                return Array.isArray(points) ? points.map(point => ({ x: point.x, y: point.y })) : null;
            }

            function cloneScannerCanvas(sourceCanvas) {
                if (!sourceCanvas) return null;

                const clone = document.createElement('canvas');
                clone.width = sourceCanvas.width;
                clone.height = sourceCanvas.height;
                const cloneContext = clone.getContext('2d');
                cloneContext.drawImage(sourceCanvas, 0, 0);
                return clone;
            }

            function getScannerQualityScore(qualityResult) {
                const analysis = qualityResult?.analysis;
                if (!qualityResult?.passed || !analysis) return 0;

                const brightnessScore = Math.max(0, 1 - (Math.abs(analysis.brightness - 145) / 145));
                const contrastScore = Math.min(1, analysis.contrast / 60);
                const sharpnessScore = Math.min(1.4, analysis.sharpness / 18);
                const laplacianScore = Math.min(1.6, analysis.laplacianVariance / 260);
                const edgeScore = Math.min(1.2, analysis.edgeDensity / 0.055);
                const centerEdgeScore = Math.min(1.2, analysis.centerEdgeDensity / 0.045);
                const sizeScore = Math.min(1.15, Math.max(0, (qualityResult.areaRatio || 0) / 0.34));

                return (
                    (brightnessScore * 0.9)
                    + (contrastScore * 1.0)
                    + (sharpnessScore * 1.2)
                    + (laplacianScore * 1.55)
                    + (edgeScore * 1.0)
                    + (centerEdgeScore * 1.0)
                    + (sizeScore * 0.75)
                );
            }

            function rememberScannerCandidate(canvas, points, qualityResult) {
                if (!canvas || !points || !qualityResult?.passed) return null;

                const candidate = {
                    canvas: cloneScannerCanvas(canvas),
                    points: cloneScannerPoints(points),
                    quality: qualityResult,
                    score: getScannerQualityScore(qualityResult),
                    createdAt: performance.now(),
                };

                scannerState.candidateFrames.push(candidate);
                scannerState.candidateFrames.sort((a, b) => b.score - a.score);
                scannerState.candidateFrames = scannerState.candidateFrames.slice(0, 5);

                return candidate;
            }

            function getBestScannerCandidate() {
                if (!scannerState.candidateFrames.length) return null;
                return scannerState.candidateFrames
                    .slice()
                    .sort((a, b) => b.score - a.score)[0];
            }

            function createMotionSample(canvas) {
                if (!canvas) return null;

                const width = 64;
                const height = 40;
                const sampleCanvas = document.createElement('canvas');
                sampleCanvas.width = width;
                sampleCanvas.height = height;

                const context = sampleCanvas.getContext('2d', { willReadFrequently: true });
                context.imageSmoothingEnabled = true;
                context.drawImage(canvas, 0, 0, width, height);

                const rgba = context.getImageData(0, 0, width, height).data;
                const grayscale = new Float32Array(width * height);

                for (let index = 0, pixel = 0; index < rgba.length; index += 4, pixel++) {
                    grayscale[pixel] = (
                        (rgba[index] * 0.299)
                        + (rgba[index + 1] * 0.587)
                        + (rgba[index + 2] * 0.114)
                    ) / 255;
                }

                return grayscale;
            }

            function calculateMotionDifference(previous, current) {
                if (!previous || !current || previous.length !== current.length) return 1;

                let difference = 0;
                for (let index = 0; index < current.length; index++) {
                    difference += Math.abs(current[index] - previous[index]);
                }

                return difference / current.length;
            }

            function resetMotionTracking(clearCandidates = true) {
                scannerState.previousMotionSample = null;
                scannerState.stableStartedAt = 0;
                scannerState.consecutiveClearFrames = 0;

                if (clearCandidates) {
                    scannerState.candidateFrames = [];
                }
            }

            function sampleMovingScannerFrame(points, pointsAreStable) {
                if (!scannerState.targetInput || !points || scannerState.isWaitingForFlip) return null;

                const now = performance.now();
                if (now - scannerState.lastCandidateAt < SCANNER_CANDIDATE_SAMPLE_INTERVAL_MS) {
                    return getBestScannerCandidate();
                }

                scannerState.lastCandidateAt = now;
                const canvas = cropDetectedCard(points);
                if (!canvas) return null;

                const currentMotionSample = createMotionSample(canvas);
                const previousMotionSample = scannerState.previousMotionSample;
                scannerState.previousMotionSample = currentMotionSample;

                const motionDifference = previousMotionSample
                    ? calculateMotionDifference(previousMotionSample, currentMotionSample)
                    : 0;

                const frameIsMoving = !pointsAreStable
                    || (previousMotionSample && motionDifference > SCANNER_MAX_MOTION_DIFFERENCE);

                if (frameIsMoving) {
                    resetMotionTracking(true);
                    scannerState.lastCandidateMessage = 'Movement detected. Hold the ID and phone steady.';
                    return null;
                }

                if (!scannerState.stableStartedAt) {
                    scannerState.stableStartedAt = now;
                }

                const qualityResult = validateIdCaptureQuality(canvas, points, scannerState.targetInput.id);
                if (!qualityResult.passed) {
                    scannerState.consecutiveClearFrames = 0;
                    scannerState.candidateFrames = [];
                    scannerState.lastCandidateMessage = qualityResult.message;
                    return null;
                }

                scannerState.consecutiveClearFrames++;
                scannerState.lastCandidateMessage = '';
                return rememberScannerCandidate(canvas, points, qualityResult);
            }

            function attachScannerImageToInput(canvas, prevalidatedQuality = null, prevalidatedPoints = null) {
                if (!canvas || !scannerState.targetInput) return;

                const currentInputId = scannerState.targetInput.id;
                const qualityResult = prevalidatedQuality || validateIdCaptureQuality(canvas, prevalidatedPoints || scannerState.detectedPoints, currentInputId);

                if (!qualityResult.passed) {
                    rejectCurrentScannerCapture(currentInputId, qualityResult.message);
                    return;
                }

                canvas.toBlob((blob) => {
                    if (!blob) {
                        setScannerStatus(__cfg_barangay_system_register_1.v83, 'error');
                        scannerState.isCapturing = false;
                        scannerState.isWaitingForFlip = false;
                        return;
                    }

                    try {
                        const side = currentInputId === 'valid_id_back' ? 'back' : 'front';
                        const sideLabel = currentInputId === 'valid_id_back' ? __cfg_barangay_system_register_1.v52 : __cfg_barangay_system_register_1.v50;
                        const file = new File([blob], `valid_id_${side}_scan_${Date.now()}.jpg`, { type: 'image/jpeg' });
                        const transfer = new DataTransfer();
                        transfer.items.add(file);
                        scannerState.targetInput.files = transfer.files;

                        scannerState.qualityResults[currentInputId] = qualityResult;
                        scannerState.captureSignatures[currentInputId] = qualityResult.signature;
                        scannerState.targetInput.dataset.scannerAssignment = '1';
                        scannerState.targetInput.dispatchEvent(new Event('change', { bubbles: true }));

                        updateAutoScanStatus();

                        if (scannerState.scanMode === 'both' && currentInputId === 'valid_id') {
                            scannerState.isWaitingForFlip = true;
                            setScannerStatus(__cfg_barangay_system_register_1.v84, 'ready');
                            resetScannerDetection();

                            showBackSideTransition(() => {
                                const nextSide = scannerFlowSides[1];
                                scannerState.flowIndex = 1;
                                setScannerTarget(nextSide.inputId, nextSide.label);
                                scannerState.isCapturing = false;
                                scannerState.isWaitingForFlip = false;
                                updateAutoScanStatus();
                                setScannerStatus(__cfg_barangay_system_register_1.v85, 'waiting');

                                if (scannerState.stream) {
                                    scannerFrameLoop();
                                }
                            });

                            return;
                        }

                        if (scannerState.scanMode === 'both') {
                            if (!hasValidScannerCaptures()) {
                                setScannerStatus(__cfg_barangay_system_register_1.v86.replace(':side', sideLabel), 'error');
                                scannerState.isCapturing = false;
                                return;
                            }

                            scannerState.completedBothSides = true;
                            scannerState.isFlowActive = false;
                            updateAutoScanStatus();
                            setScannerStatus(__cfg_barangay_system_register_1.v87, 'ready');
                        } else {
                            setScannerStatus(__cfg_barangay_system_register_1.v88.replace(':side', sideLabel), 'ready');
                        }

                        window.setTimeout(() => {
                            if (scannerModal) scannerModal.hide();

                            if (scannerState.scanMode === 'both' && currentStep === 1 && hasValidScannerCaptures() && validateStep(1)) {
                                goToStep('step2');
                            }
                        }, 750);
                    } catch (error) {
                        console.warn('Could not attach scanner file:', error);
                        setScannerStatus(__cfg_barangay_system_register_1.v89, 'error');
                        scannerState.isFlowActive = false;
                        updateScanButtonState();
                    } finally {
                        if (!(scannerState.scanMode === 'both' && currentInputId === 'valid_id')) {
                            scannerState.isCapturing = false;
                            scannerState.isWaitingForFlip = false;
                        }
                    }
                }, 'image/jpeg', 0.94);
            }

            function captureScannerFrame(force = false) {
                if (scannerState.isCapturing) return;

                if (scannerState.isWaitingForFlip) {
                    setScannerStatus(__cfg_barangay_system_register_1.v90, 'waiting');
                    return;
                }

                if (!scannerState.detectedPoints) {
                    if (!force) {
                        setScannerStatus(__cfg_barangay_system_register_1.v91, 'error');
                        return;
                    }

                    scannerState.detectedPoints = getGuideFallbackPoints();
                    if (!scannerState.detectedPoints) {
                        setScannerStatus(__cfg_barangay_system_register_1.v91, 'error');
                        return;
                    }
                    setScannerStatus('Using the guide area. Keep the entire card visible and avoid glare.', 'waiting');
                }

                scannerState.isCapturing = true;
                const sideLabel = scannerState.targetInput?.id === 'valid_id_back' ? __cfg_barangay_system_register_1.v52 : __cfg_barangay_system_register_1.v50;
                const bestCandidate = getBestScannerCandidate();

                if (bestCandidate) {
                    scannerState.detectedPoints = cloneScannerPoints(bestCandidate.points);
                    setScannerStatus(__cfg_barangay_system_register_1.v92.replace(':side', sideLabel), 'ready');
                    attachScannerImageToInput(bestCandidate.canvas, bestCandidate.quality, bestCandidate.points);
                    return;
                }

                setScannerStatus(__cfg_barangay_system_register_1.v93.replace(':side', sideLabel), 'ready');
                const canvas = cropDetectedCard(scannerState.detectedPoints);
                attachScannerImageToInput(canvas);
            }

            function scheduleScannerFrame() {
                if (!scannerVideo || !scannerState.stream || scannerState.isCapturing || scannerState.completedBothSides) return;

                if (typeof scannerVideo.requestVideoFrameCallback === 'function') {
                    scannerState.frameCallbackType = 'video';
                    scannerState.rafId = scannerVideo.requestVideoFrameCallback(scannerFrameLoop);
                } else {
                    scannerState.frameCallbackType = 'animation';
                    scannerState.rafId = requestAnimationFrame(scannerFrameLoop);
                }
            }

            function scannerFrameLoop() {
                if (!scannerState.stream || !scannerVideo || scannerState.isCapturing || scannerState.completedBothSides) return;

                scheduleScannerFrame();

                if (scannerVideo.readyState < 2 || scannerState.isProcessing) return;
                scannerState.isProcessing = true;

                try {
                    const points = getIdCardPoints();
                    drawScannerOverlay(points);

                    if (points) {
                        const pointsAreStableEnough = arePointsStable(points, scannerState.lastPoints);
                        scannerState.stableFrames = pointsAreStableEnough
                            ? scannerState.stableFrames + 1
                            : 0;
                        scannerState.detectionFrames++;
                        scannerState.lastPoints = points;
                        scannerState.detectedPoints = points;

                        const sideLabel = scannerState.targetInput?.id === 'valid_id_back'
                            ? __cfg_barangay_system_register_1.v52
                            : __cfg_barangay_system_register_1.v50;
                        const bestCandidate = sampleMovingScannerFrame(points, pointsAreStableEnough);
                        const goodFrameCount = scannerState.candidateFrames.length;
                        const progress = Math.min(
                            SCANNER_STABLE_FRAME_TARGET,
                            Math.max(scannerState.stableFrames, scannerState.consecutiveClearFrames * 2)
                        );

                        if (bestCandidate) {
                            setScannerStatus(__cfg_barangay_system_register_1.v94.replace(':side', sideLabel).replace(':progress', progress).replace(':target', SCANNER_STABLE_FRAME_TARGET), 'ready');
                        } else if (scannerState.lastCandidateMessage) {
                            setScannerStatus(__cfg_barangay_system_register_1.v95.replace(':side', sideLabel).replace(':message', scannerState.lastCandidateMessage), 'waiting');
                        } else {
                            setScannerStatus(__cfg_barangay_system_register_1.v96.replace(':side', sideLabel).replace(':progress', progress).replace(':target', SCANNER_STABLE_FRAME_TARGET), 'ready');
                        }

                        const stableDuration = scannerState.stableStartedAt
                            ? performance.now() - scannerState.stableStartedAt
                            : 0;

                        const canAutoCaptureBestFrame = scannerAutoCapture?.checked
                            && bestCandidate
                            && scannerState.stableFrames >= SCANNER_STABLE_FRAME_TARGET
                            && scannerState.consecutiveClearFrames >= SCANNER_MIN_GOOD_CANDIDATES
                            && goodFrameCount >= SCANNER_MIN_GOOD_CANDIDATES
                            && stableDuration >= SCANNER_MIN_STABLE_DURATION_MS;

                        if (canAutoCaptureBestFrame) {
                            captureScannerFrame(true);
                        }
                    } else {
                        scannerState.stableFrames = 0;
                        scannerState.detectionFrames = 0;
                        scannerState.lastPoints = null;
                        scannerState.detectedPoints = null;
                        resetMotionTracking(true);

                        if (!window.cvReady || typeof cv === 'undefined') {
                            setScannerStatus(__cfg_barangay_system_register_1.v97, 'waiting');
                        } else {
                            setScannerStatus('Place the whole ID inside the center guide. Keep all four corners visible. If no green outline appears, use “Capture inside guide”.', 'waiting');
                        }
                    }
                } finally {
                    scannerState.isProcessing = false;
                }
            }

            function stopScannerCamera() {
                hideScannerTransition();
                if (scannerManualCaptureBtn) scannerManualCaptureBtn.disabled = true;

                if (scannerState.rafId !== null) {
                    if (scannerState.frameCallbackType === 'video'
                        && typeof scannerVideo?.cancelVideoFrameCallback === 'function') {
                        scannerVideo.cancelVideoFrameCallback(scannerState.rafId);
                    } else {
                        cancelAnimationFrame(scannerState.rafId);
                    }

                    scannerState.rafId = null;
                    scannerState.frameCallbackType = null;
                }

                if (scannerState.stream) {
                    scannerState.stream.getTracks().forEach(track => track.stop());
                    scannerState.stream = null;
                }

                if (scannerVideo) scannerVideo.srcObject = null;
                if (scannerOverlay) {
                    const context = scannerOverlay.getContext('2d');
                    context.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
                }

                scannerState.stableFrames = 0;
                scannerState.stableStartedAt = 0;
                scannerState.consecutiveClearFrames = 0;
                scannerState.previousMotionSample = null;
                scannerState.lastPoints = null;
                scannerState.detectedPoints = null;
                scannerState.candidateFrames = [];
                scannerState.detectionFrames = 0;
                scannerState.lastCandidateAt = 0;
                scannerState.lastCandidateMessage = '';
                scannerState.isCapturing = false;
                scannerState.isProcessing = false;
            }

            async function startScannerCamera() {
                if (!scannerVideo || !navigator.mediaDevices?.getUserMedia) {
                    setScannerStatus(__cfg_barangay_system_register_1.v98, 'error');
                    return;
                }

                stopScannerCamera();
                scannerState.completedBothSides = false;
                setScannerStatus(__cfg_barangay_system_register_1.v99, 'waiting');

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: {
                            facingMode: { ideal: 'environment' },
                            width: { ideal: 1920 },
                            height: { ideal: 1080 },
                            frameRate: { ideal: 30, max: 30 },
                        },
                    });

                    scannerState.stream = stream;
                    scannerVideo.srcObject = stream;
                    await scannerVideo.play();

                    const videoTrack = stream.getVideoTracks()[0];
                    try {
                        const capabilities = videoTrack?.getCapabilities?.() || {};
                        const continuousSettings = {};

                        if (Array.isArray(capabilities.focusMode) && capabilities.focusMode.includes('continuous')) {
                            continuousSettings.focusMode = 'continuous';
                        }
                        if (Array.isArray(capabilities.exposureMode) && capabilities.exposureMode.includes('continuous')) {
                            continuousSettings.exposureMode = 'continuous';
                        }
                        if (Array.isArray(capabilities.whiteBalanceMode) && capabilities.whiteBalanceMode.includes('continuous')) {
                            continuousSettings.whiteBalanceMode = 'continuous';
                        }

                        if (Object.keys(continuousSettings).length) {
                            await videoTrack.applyConstraints({ advanced: [continuousSettings] });
                        }
                    } catch (cameraConstraintError) {
                        console.info('Continuous camera controls are not supported by this browser:', cameraConstraintError);
                    }

                    await new Promise(resolve => window.setTimeout(resolve, SCANNER_AUTOFOCUS_SETTLE_MS));

                    if (scannerManualCaptureBtn) scannerManualCaptureBtn.disabled = false;
                    syncScannerCanvases();
                    setScannerStatus(__cfg_barangay_system_register_1.v100, window.cvReady ? 'waiting' : 'waiting');
                    scannerFrameLoop();
                } catch (error) {
                    console.warn('Camera access failed:', error);
                    setScannerStatus(__cfg_barangay_system_register_1.v101, 'error');
                }
            }

            document.querySelectorAll('.btn-scan-id').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-scan-target');
                    const targetLabel = button.getAttribute('data-scan-label') || __cfg_barangay_system_register_1.v53;
                    openScannerForSide(targetId, targetLabel, 'single');
                });
            });

            if (scanBothSidesBtn) {
                scanBothSidesBtn.addEventListener('click', () => {
                    if (scannerState.isFlowActive) return;
                    openScannerForBothSides();
                });
            }

            if (scannerModalElement) {
                scannerModalElement.addEventListener('shown.bs.modal', startScannerCamera);
                scannerModalElement.addEventListener('hidden.bs.modal', () => {
                    stopScannerCamera();
                    scannerState.scanMode = 'single';
                    scannerState.flowIndex = 0;
                    scannerState.isFlowActive = false;
                    scannerState.isWaitingForFlip = false;
                    scannerState.completedBothSides = false;
                    updateScanButtonState();
                });
            }

            if (scannerManualCaptureBtn) {
                scannerManualCaptureBtn.addEventListener('click', () => captureScannerFrame(true));
            }

            if (scannerRetakeBtn) {
                scannerRetakeBtn.addEventListener('click', startScannerCamera);
            }

            document.addEventListener('opencv-ready', () => {
                if (scannerState.stream && !scannerState.isCapturing) {
                    setScannerStatus(__cfg_barangay_system_register_1.v102, 'waiting');
                }
            });

            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = this.getAttribute('data-next');

                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const prevStep = this.getAttribute('data-prev');
                    if (!prevStep) return;
                    e.preventDefault();
                    goToStep(prevStep);
                });
            });

            function goToStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
                currentStep = parseInt(stepId.replace('step', ''));
                updateProgress();
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                if (progressFill) progressFill.style.width = `${progressPercentage}%`;

                progressSteps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active');
                    if (stepNumber === currentStep) {
                        step.classList.add('active');
                    }
                });
            }

            function evaluatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score += 1;
                if (/[A-Z]/.test(password)) score += 1;
                if (/[a-z]/.test(password)) score += 1;
                if (/\d/.test(password)) score += 1;
                if (/[^A-Za-z0-9]/.test(password)) score += 1;

                if (password.length === 0) return {
                    score: 0,
                    label: "too weak",
                    color: "#d16060",
                    width: 10
                };
                if (score <= 2) return {
                    score,
                    label: "weak",
                    color: "#d16060",
                    width: 35
                };
                if (score <= 3) return {
                    score,
                    label: "fair",
                    color: "#d38e2a",
                    width: 60
                };
                if (score <= 4) return {
                    score,
                    label: "good",
                    color: "#2c7dc8",
                    width: 82
                };
                return {
                    score,
                    label: "strong",
                    color: "#1e8f55",
                    width: 100
                };
            }

            function validatePasswordStrength() {
                if (!passwordInput) return true;
                const password = passwordInput.value;
                const { label } = evaluatePasswordStrength(password);

                const existingError = passwordInput.closest('.form-group')?.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (password && (label === "too weak" || label === "weak")) {
                    showFieldError(passwordInput, __cfg_barangay_system_register_1.v103);
                    return false;
                }
                return true;
            }

            const passwordStrengthTranslations = {
                'too weak': __cfg_barangay_system_register_1.v104,
                'weak': __cfg_barangay_system_register_1.v105,
                'fair': __cfg_barangay_system_register_1.v106,
                'good': __cfg_barangay_system_register_1.v107,
                'strong': __cfg_barangay_system_register_1.v108,
            };

            function updatePasswordUI() {
                if (!passwordInput || !meterFill || !strengthText) return;

                const {
                    label,
                    color,
                    width
                } = evaluatePasswordStrength(passwordInput.value);
                meterFill.style.width = `${width}%`;
                meterFill.style.backgroundColor = color;
                strengthText.textContent = __cfg_barangay_system_register_1.v109.replace(':label', passwordStrengthTranslations[label] || label);
                strengthText.classList.toggle("good", label === "good" || label === "strong");
                strengthText.classList.toggle("bad", label === "weak" || label === "too weak");

                passwordChecklist.forEach((item) => {
                    const rule = item.dataset.rule;
                    if (rule) {
                        const rules = {
                            length: passwordInput.value.length >= 8,
                            number: /\d/.test(passwordInput.value),
                            upper: /[A-Z]/.test(passwordInput.value),
                            special: /[^A-Za-z0-9]/.test(passwordInput.value),
                        };
                        item.classList.toggle("is-complete", rules[rule] || false);
                    }
                });
            }

            if (contactInput) {
                contactInput.addEventListener("input", () => {
                    contactInput.value = contactInput.value.replace(/\D/g, "").slice(0, 11);
                });
            }

            if (passwordInput) {
                passwordInput.addEventListener("input", () => {
                    passwordInput.value = passwordInput.value.replace(/\s/g, "");
                    updatePasswordUI();
                    updatePasswordMatchUI();
                });
            }

            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener("input", () => {
                    confirmPasswordInput.value = confirmPasswordInput.value.replace(/\s/g, "");
                    updatePasswordMatchUI();
                });
            }

            document.querySelectorAll(".toggle-password").forEach((toggle) => {
                toggle.addEventListener("click", () => {
                    const targetId = toggle.getAttribute("data-target");
                    const input = targetId ? document.getElementById(targetId) : null;
                    if (!input) return;

                    const showing = input.type === "text";
                    input.type = showing ? "password" : "text";
                    const icon = toggle.querySelector("i");
                    if (icon) {
                        icon.classList.toggle("fa-eye", showing);
                        icon.classList.toggle("fa-eye-slash", !showing);
                    }
                });
            });

            if (form) {
                form.addEventListener("submit", async (event) => {
                    if (isProgrammaticSubmit) {
                        return;
                    }

                    event.preventDefault();

                    buildResidentAddress();

                    if (!validateStep(currentStep)) {
                        document.querySelectorAll('.is-invalid').forEach(field => {
                            const group = field.closest('.form-group') || field.closest('.file-upload-wrapper');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                        });
                        return;
                    }

                    if (usernameField) {
                        const usernameAvailable = await checkUsernameAvailability(usernameField.value, true);
                        if (!usernameAvailable) {
                            showFieldError(usernameField, usernameCheckState.message || __cfg_barangay_system_register_1.v16);
                            const group = usernameField.closest('.form-group');
                            if (group) {
                                group.classList.add('shake');
                                setTimeout(() => group.classList.remove('shake'), 400);
                            }
                            return;
                        }
                    }

                    if (recaptchaEnabled) {
                        const captchaToken = window.grecaptcha ? window.grecaptcha.getResponse() : '';
                        if (!captchaToken) {
                            showCaptchaError(__cfg_barangay_system_register_1.v110);
                            const captchaSection = document.querySelector('.captcha-section');
                            if (captchaSection) {
                                captchaSection.classList.add('shake');
                                setTimeout(() => captchaSection.classList.remove('shake'), 400);
                            }
                            return;
                        }

                        clearCaptchaError();
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add("loading");
                        const btnText = submitBtn.querySelector(".btn-text");
                        if (btnText) btnText.textContent = __cfg_barangay_system_register_1.v111;
                    }

                    isProgrammaticSubmit = true;
                    form.submit();
                });
            }

            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            buildResidentAddress();
            updateProgress();
            updatePasswordUI();
            updatePasswordMatchUI();

            const hasStep2Errors = String(__cfg_barangay_system_register_1.v112).toLowerCase() === 'true';
            if (hasStep2Errors) {
                goToStep('step2');
            }
        });
