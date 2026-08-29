const __cfg_barangay_system_clearance_form_1 = JSON.parse(document.getElementById('js-config-barangay-system-clearance-form-1')?.dataset.jsConfig || '{}');

        document.addEventListener('DOMContentLoaded', function() {
            const profileData = __cfg_barangay_system_clearance_form_1.v1;
            const needsPrimaryProof = __cfg_barangay_system_clearance_form_1.v2;
            const needsValidIdUpload = __cfg_barangay_system_clearance_form_1.v3;
            const savedValidIdLabel = __cfg_barangay_system_clearance_form_1.v4;

            const form = document.getElementById('requirementForm');
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next[data-next]');
            const prevButtons = document.querySelectorAll('.btn-prev[data-prev]');
            const purposeSelect = document.getElementById('purpose');
            const purposeOther = document.getElementById('purposeOther');
            const termsCheckbox = document.getElementById('terms');
            const privacyCheckbox = document.getElementById('privacy');
            const pickupCheckbox = document.getElementById('pickup');
            const profileConfirmed = document.getElementById('profileConfirmed');

            let currentStep = 1;
            const totalSteps = 3;

            if (purposeSelect && purposeOther) {
                togglePurposeOther();

                purposeSelect.addEventListener('change', function() {
                    togglePurposeOther();
                });
            }

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
                    e.preventDefault();

                    const prevStep = this.getAttribute('data-prev');
                    goToStep(prevStep);
                });
            });

            if (form) {
                form.addEventListener('submit', function(e) {

                    if (!validateStep(1)) {
                        e.preventDefault();
                        goToStep('step1');
                        return;
                    }

                    if (!validateStep(2)) {
                        e.preventDefault();
                        goToStep('step2');
                        return;
                    }

                    if (!validateStep(3)) {
                        e.preventDefault();
                        return;
                    }

                    const submitButton = form.querySelector('.btn-submit');

                    if (submitButton) {
                        submitButton.classList.add('loading');
                        submitButton.disabled = true;
                    }
                });
            }

            [termsCheckbox, privacyCheckbox, pickupCheckbox].forEach(function(cb) {
                if (!cb) return;

                cb.addEventListener('change', function() {
                    if (termsCheckbox.checked && privacyCheckbox.checked && pickupCheckbox.checked) {
                        document.getElementById('termsError').style.display = 'none';
                    }
                });
            });

            if (profileConfirmed) {
                profileConfirmed.addEventListener('change', function() {
                    if (!this.checked) return;

                    this.classList.remove('is-invalid');

                    const checkboxLabel = this.closest('.terms-checkbox');
                    const errorMessage = checkboxLabel?.nextElementSibling;

                    if (errorMessage?.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                });
            }

            setupFileUpload('primaryProofUpload', 'primaryProof', 'primaryProofPreview');
            setupFileUpload('validIdUpload', 'validId', 'validIdPreview');
            setupFileUpload('validIdBackUpload', 'validIdBack', 'validIdBackPreview');

            updateProgress();

            function togglePurposeOther() {
                if (purposeSelect.value === 'other') {
                    purposeOther.style.display = 'block';
                    purposeOther.required = true;
                } else {
                    purposeOther.style.display = 'none';
                    purposeOther.required = false;
                    purposeOther.value = '';
                }
            }

            function goToStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));

                const targetStep = document.getElementById(stepId);

                if (!targetStep) return;

                targetStep.classList.add('active');
                currentStep = parseInt(stepId.replace('step', ''), 10);

                updateProgress();

                if (currentStep === 3) {
                    updateReview();
                }

                window.scrollTo({
                    top: document.getElementById('apply-form').offsetTop - 20,
                    behavior: 'smooth'
                });
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;

                if (progressFill) {
                    progressFill.style.width = `${progressPercentage}%`;
                }

                progressSteps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'), 10);
                    step.classList.toggle('active', stepNumber === currentStep);
                });
            }

            function clearErrors(container) {
                if (!container) return;

                container.querySelectorAll('.error-message').forEach(error => error.remove());

                container.querySelectorAll('input, select, textarea').forEach(field => {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                });

                container.querySelectorAll('.upload-area').forEach(uploadArea => {
                    uploadArea.style.borderColor = '';
                    uploadArea.style.backgroundColor = '';
                });
            }

            function validateFileInput(fileInput, uploadArea, label) {
                if (!fileInput || !uploadArea) {
                    return false;
                }

                if (!fileInput.files || fileInput.files.length === 0) {
                    showFieldError(
                        fileInput,
                        `${label}: ${__cfg_barangay_system_clearance_form_1.v5}`
                    );
                    return false;
                }

                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024;
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                const extension = (file.name.split('.').pop() || '').toLowerCase();

                if (!allowedExtensions.includes(extension)) {
                    showFieldError(
                        fileInput,
                        __cfg_barangay_system_clearance_form_1.v6
                    );
                    return false;
                }

                if (file.size > maxSize) {
                    showFieldError(
                        fileInput,
                        __cfg_barangay_system_clearance_form_1.v7
                    );
                    return false;
                }

                return true;
            }

            function validateStep(stepNumber) {
                const currentStepElement = document.getElementById(`step${stepNumber}`);

                if (!currentStepElement) {
                    return false;
                }

                clearErrors(currentStepElement);

                let isValid = true;

                if (stepNumber === 1) {
                    if (!profileConfirmed || !profileConfirmed.checked) {
                        showFieldError(
                            profileConfirmed,
                            __cfg_barangay_system_clearance_form_1.v8
                        );
                        isValid = false;
                    }
                }

                if (stepNumber === 2) {
                    if (needsPrimaryProof) {
                        const primaryProofInput = document.getElementById('primaryProof');
                        const primaryProofUpload = document.getElementById('primaryProofUpload');

                        if (!validateFileInput(
                            primaryProofInput,
                            primaryProofUpload,
                            __cfg_barangay_system_clearance_form_1.v9
                        )) {
                            isValid = false;
                        }
                    }

                    if (needsValidIdUpload) {
                        const validIdInput = document.getElementById('validId');
                        const validIdUpload = document.getElementById('validIdUpload');
                        const validIdBackInput = document.getElementById('validIdBack');
                        const validIdBackUpload = document.getElementById('validIdBackUpload');

                        if (!validateFileInput(
                            validIdInput,
                            validIdUpload,
                            __cfg_barangay_system_clearance_form_1.v10
                        )) {
                            isValid = false;
                        }

                        if (!validateFileInput(
                            validIdBackInput,
                            validIdBackUpload,
                            'Please upload the back side of your valid ID.'
                        )) {
                            isValid = false;
                        }
                    }

                    if (!purposeSelect || purposeSelect.value.trim() === '') {
                        showFieldError(
                            purposeSelect,
                            __cfg_barangay_system_clearance_form_1.v11
                        );
                        isValid = false;
                    }

                    if (
                        purposeSelect &&
                        purposeSelect.value === 'other' &&
                        purposeOther &&
                        purposeOther.value.trim() === ''
                    ) {
                        showFieldError(
                            purposeOther,
                            __cfg_barangay_system_clearance_form_1.v11
                        );
                        isValid = false;
                    }
                }

                if (stepNumber === 3) {
                    const allConfirmed =
                        termsCheckbox?.checked &&
                        privacyCheckbox?.checked &&
                        pickupCheckbox?.checked;

                    const termsError = document.getElementById('termsError');

                    if (!allConfirmed) {
                        if (termsError) {
                            termsError.style.display = 'block';
                            termsError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }

                        isValid = false;
                    } else if (termsError) {
                        termsError.style.display = 'none';
                    }
                }

                return isValid;
            }

            function showFieldError(field, message) {
                if (!field) return;

                const container =
                    field.closest('.upload-group, .form-group, .terms-checkbox, .locked-profile-card') ||
                    field.parentNode;

                if (!container) return;

                const existingError = container.querySelector('.error-message');

                if (existingError) {
                    existingError.textContent = message;
                    return;
                }

                field.classList.add('is-invalid');

                if (field.type === 'file') {
                    const uploadArea = container.querySelector('.upload-area');

                    if (uploadArea) {
                        uploadArea.style.borderColor = '#ff4444';
                        uploadArea.style.backgroundColor = 'rgba(255, 68, 68, 0.05)';
                    }
                } else if (field.type !== 'checkbox') {
                    field.style.borderColor = '#ff4444';
                    field.style.boxShadow = '0 0 0 3px rgba(255, 68, 68, 0.1)';
                }

                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.textContent = message;

                if (field.type === 'checkbox') {
                    const checkboxLabel = field.closest('.terms-checkbox');

                    if (checkboxLabel) {
                        checkboxLabel.insertAdjacentElement('afterend', errorMessage);
                        return;
                    }
                }

                container.appendChild(errorMessage);
            }

            function setupFileUpload(uploadAreaId, fileInputId, previewId) {
                const uploadArea = document.getElementById(uploadAreaId);
                const fileInput = document.getElementById(fileInputId);
                const previewArea = document.getElementById(previewId);

                if (!uploadArea || !fileInput || !previewArea) return;

                uploadArea.addEventListener('click', () => fileInput.click());

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, unhighlight, false);
                });

                uploadArea.addEventListener('drop', handleDrop, false);

                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        handleFile(this.files[0]);
                    }
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight() {
                    uploadArea.style.borderColor = '#C62828';
                    uploadArea.style.backgroundColor = 'rgba(198, 40, 40, 0.05)';
                }

                function unhighlight() {
                    uploadArea.style.borderColor = '#ddd';
                    uploadArea.style.backgroundColor = '#fafafa';
                }

                function handleDrop(e) {
                    const files = e.dataTransfer.files;

                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFile(files[0]);
                    }
                }

                function handleFile(file) {
                    const maxSize = 5 * 1024 * 1024;
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    const validExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];

                    const fileType = file.type.toLowerCase();
                    const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));

                    if (file.size > maxSize) {
                        alert(__cfg_barangay_system_clearance_form_1.v7);
                        fileInput.value = '';
                        return;
                    }

                    if (!validTypes.includes(fileType) && !validExtensions.includes(fileExtension)) {
                        alert(__cfg_barangay_system_clearance_form_1.v12);
                        fileInput.value = '';
                        return;
                    }

                    let fileIcon = 'fa-file';

                    if (file.type.includes('image')) {
                        fileIcon = 'fa-file-image';
                    } else if (file.type.includes('pdf')) {
                        fileIcon = 'fa-file-pdf';
                    }

                    previewArea.innerHTML = `
                        <div class="file-preview">
                            <i class="fas ${fileIcon}"></i>
                            <div class="file-info">
                                <div class="file-name">${escapeHtml(file.name)}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" data-file-input="${fileInputId}" aria-label="${__cfg_barangay_system_clearance_form_1.v13}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    previewArea.classList.add('active');

                    const removeButton = previewArea.querySelector('.remove-file');

                    if (removeButton) {
                        removeButton.addEventListener('click', function(e) {
                            e.stopPropagation();
                            removeFile(fileInputId);
                        });
                    }

                    uploadArea.style.borderColor = '#ddd';
                    uploadArea.style.backgroundColor = '#fafafa';

                    const errorMessage = uploadArea.closest('.upload-group')?.querySelector('.error-message');

                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            }

            function removeFile(fileInputId) {
                const fileInput = document.getElementById(fileInputId);
                const previewArea = document.getElementById(`${fileInputId}Preview`);
                const uploadArea = document.getElementById(`${fileInputId}Upload`);

                if (fileInput) fileInput.value = '';

                if (previewArea) {
                    previewArea.innerHTML = '';
                    previewArea.classList.remove('active');
                }

                if (uploadArea) {
                    uploadArea.style.borderColor = '#ddd';
                    uploadArea.style.backgroundColor = '#fafafa';
                }
            }

            function updateReview() {
                const personalInfo = document.getElementById('reviewPersonal');
                const documentInfo = document.getElementById('reviewDocuments');

                if (personalInfo) {
                    personalInfo.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v14}</div>
                            <div class="review-value">${escapeHtml(profileData.fullName)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v15}</div>
                            <div class="review-value">${escapeHtml(profileData.birthdate)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v16}</div>
                            <div class="review-value">${escapeHtml(profileData.gender)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v17}</div>
                            <div class="review-value">${escapeHtml(profileData.address)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v18}</div>
                            <div class="review-value">${escapeHtml(profileData.contact)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v19}</div>
                            <div class="review-value">${escapeHtml(profileData.email)}</div>
                        </div>
                    `;
                }

                const purposeValue = purposeSelect.value;
                let purposeText = __cfg_barangay_system_clearance_form_1.v20;

                if (purposeValue === 'other') {
                    purposeText = purposeOther.value || 'Other';
                } else if (purposeValue) {
                    purposeText = purposeSelect.options[purposeSelect.selectedIndex].text;
                }

                const primaryProofInput = document.getElementById('primaryProof');
                const validIdInput = document.getElementById('validId');

                const proofText = needsPrimaryProof
                    ? getFileDisplayName(primaryProofInput, __cfg_barangay_system_clearance_form_1.v21)
                    : __cfg_barangay_system_clearance_form_1.v22;

                const validIdText = needsValidIdUpload
                    ? getFileDisplayName(validIdInput, __cfg_barangay_system_clearance_form_1.v23)
                    : savedValidIdLabel;

                if (documentInfo) {
                    documentInfo.innerHTML = `
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v24}</div>
                            <div class="review-value">${escapeHtml(purposeText)}</div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v25}</div>
                            <div class="review-value">
                                <i class="fas fa-file-alt"></i> ${escapeHtml(proofText)}
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="review-label">${__cfg_barangay_system_clearance_form_1.v26}</div>
                            <div class="review-value">
                                <i class="fas fa-id-card"></i> ${escapeHtml(validIdText)}
                            </div>
                        </div>
                    `;
                }
            }

            function getFileDisplayName(input, emptyText) {
                if (input && input.files && input.files.length > 0) {
                    return input.files[0].name;
                }

                return emptyText;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
        });
