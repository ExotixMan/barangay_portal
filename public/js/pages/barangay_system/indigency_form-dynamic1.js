const __cfg_barangay_system_indigency_form_1 = JSON.parse(document.getElementById('js-config-barangay-system-indigency-form-1')?.dataset.jsConfig || '{}');

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('requirementForm');
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next[data-next]');
            const prevButtons = document.querySelectorAll('.btn-prev[data-prev]');

            const profileConfirmed = document.getElementById('profileConfirmed');
            const monthlyIncome = document.getElementById('monthlyIncome');
            const householdMembers = document.getElementById('householdMembers');
            const purposeSelect = document.getElementById('purpose');
            const purposeOther = document.getElementById('purposeOther');
            const termsCheckbox = document.getElementById('terms');
            const privacyCheckbox = document.getElementById('privacy');
            const pickupCheckbox = document.getElementById('pickup');

            const needsPrimaryProofUpload = __cfg_barangay_system_indigency_form_1.v1;
            const needsValidIdUpload = __cfg_barangay_system_indigency_form_1.v2;
            const savedValidIdLabel = __cfg_barangay_system_indigency_form_1.v3;
            const savedResidencyProofLabel = __cfg_barangay_system_indigency_form_1.v4;
            const profileReviewData = __cfg_barangay_system_indigency_form_1.v5;

            let currentStep = 1;
            const totalSteps = 3;

            function clearErrors(scope = document) {
                scope.querySelectorAll('.error-message.dynamic-error, .upload-js-error').forEach(error => error.remove());

                scope.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                });

                scope.querySelectorAll('.upload-area').forEach(area => {
                    area.style.borderColor = '';
                    area.style.backgroundColor = '';
                });
            }

            function showFieldError(field, message) {
                if (!field) {
                    return;
                }

                field.classList.add('is-invalid');
                field.style.borderColor = '#ff4444';

                const error = document.createElement('div');
                error.className = 'error-message dynamic-error';
                error.textContent = message;

                const container = field.closest('.form-group, .locked-profile-card, .upload-group') || field.parentElement;
                container.appendChild(error);
            }

            function showUploadError(uploadArea, message) {
                if (!uploadArea) {
                    return;
                }

                uploadArea.style.borderColor = '#ff4444';
                uploadArea.style.backgroundColor = 'rgba(255, 68, 68, 0.05)';

                const error = document.createElement('div');
                error.className = 'error-message dynamic-error upload-js-error';
                error.style.textAlign = 'center';
                error.textContent = message;

                const container = uploadArea.closest('.upload-group') || uploadArea.parentElement;
                container.appendChild(error);
            }

            function validateHouseholdField(input) {
                if (!input) {
                    return false;
                }

                const value = String(input.value || '').trim();
                const numberValue = Number.parseInt(value, 10);

                if (value === '' || Number.isNaN(numberValue) || numberValue < 1 || numberValue > 20) {
                    showFieldError(input, __cfg_barangay_system_indigency_form_1.v6);
                    return false;
                }

                return true;
            }

            function validateFileInput(fileInput, uploadArea, label) {
                if (!fileInput || !uploadArea) {
                    return false;
                }

                if (!fileInput.files || fileInput.files.length === 0) {
                    showUploadError(uploadArea, __cfg_barangay_system_indigency_form_1.v7.replace(':label', label));
                    return false;
                }

                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024;
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                const extension = (file.name.split('.').pop() || '').toLowerCase();

                if (!allowedExtensions.includes(extension)) {
                    showUploadError(uploadArea, __cfg_barangay_system_indigency_form_1.v8);
                    return false;
                }

                if (file.size > maxSize) {
                    showUploadError(uploadArea, __cfg_barangay_system_indigency_form_1.v9);
                    return false;
                }

                return true;
            }

            function validateStep(stepNumber) {
                const currentStepElement = document.getElementById(`step${stepNumber}`);
                clearErrors(currentStepElement || document);

                let isValid = true;

                if (stepNumber === 1) {
                    if (!profileConfirmed || !profileConfirmed.checked) {
                        showFieldError(profileConfirmed, __cfg_barangay_system_indigency_form_1.v10);
                        isValid = false;
                    }

                    if (!monthlyIncome || monthlyIncome.value.trim() === '') {
                        showFieldError(monthlyIncome, __cfg_barangay_system_indigency_form_1.v11);
                        isValid = false;
                    }

                    if (!validateHouseholdField(householdMembers)) {
                        isValid = false;
                    }
                }

                if (stepNumber === 2) {
                    if (needsPrimaryProofUpload) {
                        const primaryProofInput = document.getElementById('primaryProof');
                        const primaryProofUpload = document.getElementById('primaryProofUpload');

                        if (!validateFileInput(primaryProofInput, primaryProofUpload, __cfg_barangay_system_indigency_form_1.v12)) {
                            isValid = false;
                        }
                    }

                    if (needsValidIdUpload) {
                        const validIdInput = document.getElementById('validId');
                        const validIdUpload = document.getElementById('validIdUpload');
                        const validIdBackInput = document.getElementById('validIdBack');
                        const validIdBackUpload = document.getElementById('validIdBackUpload');

                        if (!validateFileInput(validIdInput, validIdUpload, __cfg_barangay_system_indigency_form_1.v13)) {
                            isValid = false;
                        }

                        if (!validateFileInput(validIdBackInput, validIdBackUpload, 'Please upload the back side of your valid ID.')) {
                            isValid = false;
                        }
                    }

                    if (!purposeSelect || purposeSelect.value.trim() === '') {
                        showFieldError(purposeSelect, __cfg_barangay_system_indigency_form_1.v14);
                        isValid = false;
                    }

                    if (purposeSelect && purposeSelect.value === 'other' && purposeOther && purposeOther.value.trim() === '') {
                        showFieldError(purposeOther, __cfg_barangay_system_indigency_form_1.v15);
                        isValid = false;
                    }
                }

                if (stepNumber === 3) {
                    if (!termsCheckbox || !termsCheckbox.checked || !privacyCheckbox || !privacyCheckbox.checked || !pickupCheckbox || !pickupCheckbox.checked) {
                        const termsError = document.getElementById('termsError');

                        if (termsError) {
                            termsError.style.display = 'block';
                        }

                        isValid = false;
                    }
                }

                if (!isValid) {
                    const firstError = currentStepElement?.querySelector('.error-message, #termsError');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return isValid;
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;

                if (progressFill) {
                    progressFill.style.width = `${progressPercentage}%`;
                }

                progressSteps.forEach(step => {
                    const stepNumber = Number.parseInt(step.getAttribute('data-step'), 10);
                    step.classList.toggle('active', stepNumber === currentStep);
                });
            }

            function showStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));

                const targetStep = document.getElementById(stepId);

                if (!targetStep) {
                    return;
                }

                targetStep.classList.add('active');
                currentStep = Number.parseInt(stepId.replace('step', ''), 10);
                updateProgress();

                if (currentStep === 3) {
                    updateReview();
                }

                targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            function formatFileSize(bytes) {
                if (!bytes) {
                    return '0 KB';
                }

                if (bytes < 1024 * 1024) {
                    return `${(bytes / 1024).toFixed(1)} KB`;
                }

                return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
            }

            function setupFileUpload(uploadAreaId, fileInputId, previewId) {
                const uploadArea = document.getElementById(uploadAreaId);
                const fileInput = document.getElementById(fileInputId);
                const preview = document.getElementById(previewId);

                if (!uploadArea || !fileInput || !preview) {
                    return;
                }

                function renderPreview(file) {
                    const extension = (file.name.split('.').pop() || '').toLowerCase();
                    const icon = extension === 'pdf' ? 'fa-file-pdf' : 'fa-file-image';

                    preview.classList.add('active');
                    preview.innerHTML = `
                        <div class="file-preview">
                            <i class="fas ${icon}"></i>
                            <div class="file-info">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" aria-label="${__cfg_barangay_system_indigency_form_1.v16}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    const removeButton = preview.querySelector('.remove-file');

                    if (removeButton) {
                        removeButton.addEventListener('click', function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            fileInput.value = '';
                            preview.innerHTML = '';
                            preview.classList.remove('active');
                            clearErrors(uploadArea.closest('.upload-group') || document);
                        });
                    }
                }

                function handleFile(file) {
                    clearErrors(uploadArea.closest('.upload-group') || document);

                    if (!file) {
                        return;
                    }

                    const tempValid = validateFileInput(fileInput, uploadArea, __cfg_barangay_system_indigency_form_1.v17);

                    if (tempValid) {
                        renderPreview(file);
                    } else {
                        fileInput.value = '';
                        preview.innerHTML = '';
                        preview.classList.remove('active');
                    }
                }

                uploadArea.addEventListener('click', function () {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function () {
                    handleFile(this.files && this.files.length ? this.files[0] : null);
                });

                uploadArea.addEventListener('dragover', function (event) {
                    event.preventDefault();
                    uploadArea.style.borderColor = 'var(--cf-brand)';
                });

                uploadArea.addEventListener('dragleave', function () {
                    uploadArea.style.borderColor = '';
                });

                uploadArea.addEventListener('drop', function (event) {
                    event.preventDefault();

                    const file = event.dataTransfer.files && event.dataTransfer.files.length
                        ? event.dataTransfer.files[0]
                        : null;

                    if (!file) {
                        return;
                    }

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    handleFile(file);
                });
            }

            function updateReview() {
                const reviewPersonal = document.getElementById('reviewPersonal');
                const reviewDocuments = document.getElementById('reviewDocuments');

                if (reviewPersonal) {
                    reviewPersonal.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v18}</div>
                            <div class="review-value">${profileReviewData.fullName || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v20}</div>
                            <div class="review-value">${profileReviewData.birthdate || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v21}</div>
                            <div class="review-value">${profileReviewData.gender || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v22}</div>
                            <div class="review-value">${profileReviewData.address || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v23}</div>
                            <div class="review-value">${profileReviewData.contact || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v24}</div>
                            <div class="review-value">${profileReviewData.email || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v25}</div>
                            <div class="review-value">${monthlyIncome?.options[monthlyIncome.selectedIndex]?.text || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v26}</div>
                            <div class="review-value">${householdMembers?.value || __cfg_barangay_system_indigency_form_1.v19}</div>
                        </div>
                    `;
                }

                if (reviewDocuments) {
                    const primaryProofInput = document.getElementById('primaryProof');
                    const validIdInput = document.getElementById('validId');

                    const primaryProofText = needsPrimaryProofUpload
                        ? (primaryProofInput?.files?.length ? primaryProofInput.files[0].name : __cfg_barangay_system_indigency_form_1.v27)
                        : savedResidencyProofLabel;

                    const validIdText = needsValidIdUpload
                        ? (validIdInput?.files?.length ? validIdInput.files[0].name : __cfg_barangay_system_indigency_form_1.v27)
                        : __cfg_barangay_system_indigency_form_1.v28.replace(':label', savedValidIdLabel);

                    const purposeText = purposeSelect?.options[purposeSelect.selectedIndex]?.text || __cfg_barangay_system_indigency_form_1.v19;
                    const otherText = purposeSelect?.value === 'other' && purposeOther?.value
                        ? `<div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v29}</div>
                            <div class="review-value">${purposeOther.value}</div>
                        </div>`
                        : '';

                    reviewDocuments.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v30}</div>
                            <div class="review-value">${purposeText}</div>
                        </div>
                        ${otherText}
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v31}</div>
                            <div class="review-value">
                                <i class="fas fa-${needsPrimaryProofUpload ? 'file-circle-check' : 'lock'}"></i>
                                ${primaryProofText}
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_indigency_form_1.v32}</div>
                            <div class="review-value">
                                <i class="fas fa-${needsValidIdUpload ? 'file-circle-check' : 'lock'}"></i>
                                ${validIdText}
                            </div>
                        </div>
                    `;
                }
            }

            if (purposeSelect && purposeOther) {
                purposeSelect.addEventListener('change', function () {
                    if (this.value === 'other') {
                        purposeOther.style.display = 'block';
                        purposeOther.required = true;
                    } else {
                        purposeOther.style.display = 'none';
                        purposeOther.required = false;
                        purposeOther.value = '';
                    }

                    clearErrors(purposeOther.closest('.form-group') || document);
                });
            }

            if (householdMembers) {
                householdMembers.addEventListener('keydown', function (event) {
                    if (['e', 'E', '+', '-', '.'].includes(event.key)) {
                        event.preventDefault();
                    }
                });

                householdMembers.addEventListener('input', function () {
                    this.value = this.value.replace(/[^\d]/g, '');
                    clearErrors(this.closest('.form-group') || document);
                });
            }

            [profileConfirmed, monthlyIncome, termsCheckbox, privacyCheckbox, pickupCheckbox].forEach(field => {
                if (field) {
                    field.addEventListener('change', function () {
                        clearErrors(field.closest('.form-group, .locked-profile-card, .terms-section') || document);

                        const termsError = document.getElementById('termsError');
                        if (termsError) {
                            termsError.style.display = 'none';
                        }
                    });
                }
            });

            if (document.getElementById('primaryProofUpload') && document.getElementById('primaryProof')) {
                setupFileUpload('primaryProofUpload', 'primaryProof', 'primaryProofPreview');
            }

            if (document.getElementById('validIdUpload') && document.getElementById('validId')) {
                setupFileUpload('validIdUpload', 'validId', 'validIdPreview');
            }

            if (document.getElementById('validIdBackUpload') && document.getElementById('validIdBack')) {
                setupFileUpload('validIdBackUpload', 'validIdBack', 'validIdBackPreview');
            }

            nextButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const nextStep = this.getAttribute('data-next');

                    if (validateStep(currentStep)) {
                        showStep(nextStep);
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const prevStep = this.getAttribute('data-prev');
                    showStep(prevStep);
                });
            });

            if (form) {
                form.addEventListener('submit', function (event) {
                    const termsError = document.getElementById('termsError');

                    if (termsError) {
                        termsError.style.display = 'none';
                    }

                    if (!validateStep(3)) {
                        event.preventDefault();
                        return;
                    }

                    const submitButton = form.querySelector('.btn-submit');

                    if (submitButton) {
                        submitButton.classList.add('loading');
                        submitButton.disabled = true;
                    }
                });
            }

            updateProgress();
            const summary = document.getElementById('submissionErrors');
            if (summary) {
                let firstField;
                summary.querySelectorAll('[data-error-field]').forEach(item => {
                    const field = form?.elements.namedItem(item.dataset.errorField);
                    if (!(field instanceof HTMLElement)) return;
                    field.classList.add('is-invalid'); field.setAttribute('aria-invalid', 'true');
                    const error = document.createElement('p');
                    error.className = 'portal-field-feedback'; error.textContent = item.textContent;
                    error.id = `server-error-${item.dataset.errorField}`;
                    field.setAttribute('aria-describedby', error.id);
                    field.closest('.form-group, .upload-group, .terms-section')?.append(error);
                    firstField ||= field;
                });
                const step = firstField?.closest('.form-step');
                if (step) showStep(step.id);
                summary.focus();
            }
        });
