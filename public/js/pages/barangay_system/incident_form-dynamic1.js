const __cfg_barangay_system_incident_form_1 = JSON.parse(document.getElementById('js-config-barangay-system-incident-form-1')?.dataset.jsConfig || '{}');

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('blotterForm');
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const progressFill = document.getElementById('progressFill');
            const progressSteps = Array.from(document.querySelectorAll('.step'));
            const nextButtons = Array.from(document.querySelectorAll('.btn-next'));
            const prevButtons = Array.from(document.querySelectorAll('.btn-prev[data-prev]'));
            const declareTruth = document.getElementById('declareTruth');
            const agreePrivacy = document.getElementById('agreePrivacy');
            const consentProcessing = document.getElementById('consentProcessing');
            const termsError = document.getElementById('termsError');
            const addWitnessBtn = document.getElementById('addWitnessBtn');
            const witnessesList = document.getElementById('witnessesList');
            const witnessCountHint = document.getElementById('witnessCountHint');
            const totalSteps = 4;
            const MAX_WITNESSES = 5;
            let currentStep = 1;

            const FILE_RULES = {
                photos: { uploadAreaId: 'photoUpload', previewId: 'photoPreview', maxSizeMB: 10, allowedExtensions: ['jpg', 'jpeg', 'png'], allowedMimeTypes: ['image/jpeg', 'image/jpg', 'image/png'], label: 'photo' },
                videos: { uploadAreaId: 'videoUpload', previewId: 'videoPreview', maxSizeMB: 50, allowedExtensions: ['mp4', 'avi', 'mov'], allowedMimeTypes: ['video/mp4', 'video/avi', 'video/x-msvideo', 'video/quicktime'], label: 'video' },
                documents: { uploadAreaId: 'docUpload', previewId: 'docPreview', maxSizeMB: 5, allowedExtensions: ['pdf', 'doc', 'docx'], allowedMimeTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], label: 'document' }
            };

            initializeDateTimeDefaults();
            initializeRealtimeValidation();
            initializeWitnesses();
            initializeFileUploads();
            initializeNavigation();
            initializeSubmit();
            updateProgress();

            function byId(id) { return document.getElementById(id); }
            function getValue(id) { const field = byId(id); return field ? field.value.trim() : ''; }

            function initializeDateTimeDefaults() {
                const now = new Date();
                const todayIso = toDateInputValue(now);
                const incidentDateInput = byId('incidentDate');
                const incidentTimeInput = byId('incidentTime');
                if (incidentDateInput) { incidentDateInput.max = todayIso; if (!incidentDateInput.value) incidentDateInput.value = todayIso; }
                if (incidentTimeInput && !incidentTimeInput.value) incidentTimeInput.value = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            }

            function initializeRealtimeValidation() {
                ['incidentDate','incidentTime','incidentLocation','incidentDescription','immediateAction','complainantName','complainantContact','complainantAddress','complainantEmail','respondentName','respondentContact','respondentAddress','respondentDescription','additionalInfo'].forEach(id => {
                    const field = byId(id);
                    if (!field) return;
                    field.addEventListener('input', () => clearFieldError(field));
                    field.addEventListener('change', () => clearFieldError(field));
                });
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        const group = radio.closest('.form-group')?.querySelector('.report-type-grid, .confidentiality-options');
                        if (group) clearFieldError(group);
                    });
                });
            }

            function initializeWitnesses() {
                updateWitnessUi();
                if (!addWitnessBtn) return;
                addWitnessBtn.addEventListener('click', function() {
                    const currentWitnesses = getWitnessItems();
                    if (currentWitnesses.length >= MAX_WITNESSES) {
                        showFieldError(witnessesList || addWitnessBtn, __cfg_barangay_system_incident_form_1.v1.replace(':max', MAX_WITNESSES));
                        updateWitnessUi();
                        return;
                    }
                    const witnessId = `witness-${Date.now()}`;
                    const witnessNumber = currentWitnesses.length + 1;
                    const witnessHtml = `
                        <div class="witness-item" id="${witnessId}">
                            <div class="witness-header">
                                <span class="witness-number">${__cfg_barangay_system_incident_form_1.v2.replace(':number', witnessNumber)}</span>
                                <button type="button" class="remove-witness" data-remove-witness="${witnessId}" aria-label="${__cfg_barangay_system_incident_form_1.v3}"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="form-grid row g-3">
                                <div class="form-group col-md-12">
                                    <label><i class="fas fa-user"></i> ${__cfg_barangay_system_incident_form_1.v4}</label>
                                    <input name="witnesses[][name]" type="text" class="form-control witness-name" maxlength="120" placeholder="${__cfg_barangay_system_incident_form_1.v5}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label><i class="fas fa-phone"></i> ${__cfg_barangay_system_incident_form_1.v6}</label>
                                    <input name="witnesses[][contact]" type="text" class="form-control witness-contact" maxlength="11" placeholder="09XX XXX XXXX">
                                </div>
                                <div class="form-group full-width">
                                    <label><i class="fas fa-comment-dots"></i> ${__cfg_barangay_system_incident_form_1.v7}</label>
                                    <textarea name="witnesses[][statement]" class="form-control witness-statement" maxlength="1000" placeholder="${__cfg_barangay_system_incident_form_1.v8}"></textarea>
                                    <div class="form-hint">${__cfg_barangay_system_incident_form_1.v9}</div>
                                </div>
                            </div>
                        </div>`;
                    witnessesList.insertAdjacentHTML('beforeend', witnessHtml);
                    const newWitness = byId(witnessId);
                    newWitness.querySelector('[data-remove-witness]').addEventListener('click', function() { newWitness.remove(); renumberWitnesses(); updateWitnessUi(); });
                    newWitness.querySelectorAll('input, textarea').forEach(field => field.addEventListener('input', () => validateWitnessItem(newWitness, false)));
                    updateWitnessUi();
                });
            }

            function getWitnessItems() { return Array.from(document.querySelectorAll('.witness-item')); }
            function renumberWitnesses() { getWitnessItems().forEach((item, index) => { const label = item.querySelector('.witness-number'); if (label) label.textContent = __cfg_barangay_system_incident_form_1.v2.replace(':number', index + 1); }); }
            function updateWitnessUi() {
                const count = getWitnessItems().length;
                if (witnessCountHint) witnessCountHint.textContent = __cfg_barangay_system_incident_form_1.v10.replace(':count', count).replace(':max', MAX_WITNESSES);
                if (addWitnessBtn) {
                    addWitnessBtn.disabled = count >= MAX_WITNESSES;
                    addWitnessBtn.innerHTML = count >= MAX_WITNESSES ? `<i class="fas fa-circle-check"></i> ${__cfg_barangay_system_incident_form_1.v11}` : `<i class="fas fa-user-plus"></i> ${__cfg_barangay_system_incident_form_1.v12}`;
                }
            }

            function initializeFileUploads() { Object.entries(FILE_RULES).forEach(([inputId, rules]) => setupFileUpload(inputId, rules)); }
            function setupFileUpload(fileInputId, rules) {
                const uploadArea = byId(rules.uploadAreaId), fileInput = byId(fileInputId), previewArea = byId(rules.previewId);
                if (!uploadArea || !fileInput || !previewArea) return;
                uploadArea.addEventListener('click', () => fileInput.click());
                ['dragenter','dragover','dragleave','drop'].forEach(eventName => uploadArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }));
                ['dragenter','dragover'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.add('highlight')));
                ['dragleave','drop'].forEach(eventName => uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('highlight')));
                uploadArea.addEventListener('drop', e => { if (e.dataTransfer.files.length > 0) { fileInput.files = e.dataTransfer.files; handleFiles(fileInput, rules); } });
                fileInput.addEventListener('change', () => handleFiles(fileInput, rules));
            }
            function handleFiles(fileInput, rules) {
                const uploadArea = byId(rules.uploadAreaId), previewArea = byId(rules.previewId), files = Array.from(fileInput.files || []);
                clearFieldError(uploadArea); uploadArea.classList.remove('error'); previewArea.innerHTML = '';
                const validationErrors = validateFiles(files, rules);
                if (validationErrors.length) { fileInput.value = ''; uploadArea.classList.add('error'); showFieldError(uploadArea, validationErrors[0]); previewArea.classList.remove('active'); return; }
                if (!files.length) { previewArea.classList.remove('active'); return; }
                previewArea.classList.add('active');
                files.forEach(file => {
                    const filePreview = document.createElement('div');
                    filePreview.className = 'file-preview';
                    filePreview.innerHTML = `<i class="fas ${getFileIcon(file)}"></i><div class="file-info"><div class="file-name">${escapeHtml(file.name)}</div><div class="file-size">${formatFileSize(file.size)}</div></div><button type="button" class="remove-file" aria-label="${__cfg_barangay_system_incident_form_1.v13}"><i class="fas fa-times"></i></button>`;
                    filePreview.querySelector('.remove-file').addEventListener('click', e => { e.stopPropagation(); fileInput.value = ''; previewArea.innerHTML = ''; previewArea.classList.remove('active'); uploadArea.classList.remove('error','highlight'); clearFieldError(uploadArea); });
                    previewArea.appendChild(filePreview);
                });
            }
            function validateFiles(files, rules) {
                const errors = [], maxSize = rules.maxSizeMB * 1024 * 1024;
                files.forEach(file => {
                    const extension = file.name.split('.').pop().toLowerCase();
                    const validExtension = rules.allowedExtensions.includes(extension);
                    const validMime = !file.type || rules.allowedMimeTypes.includes(file.type);
                    if (!validExtension || !validMime) errors.push(__cfg_barangay_system_incident_form_1.v14.replace(':label', rules.label).replace(':file', file.name));
                    if (file.size > maxSize) errors.push(__cfg_barangay_system_incident_form_1.v15.replace(':file', file.name).replace(':size', rules.maxSizeMB));
                });
                return errors;
            }

            function initializeNavigation() {
                nextButtons.forEach(button => button.addEventListener('click', function(e) { e.preventDefault(); const nextStep = this.getAttribute('data-next'); if (validateStep(currentStep, true)) goToStep(nextStep); }));
                prevButtons.forEach(button => button.addEventListener('click', function(e) { e.preventDefault(); goToStep(this.getAttribute('data-prev')); }));
            }
            function initializeSubmit() {
                if (!form) return;
                form.addEventListener('submit', function(e) {
                    for (let step = 1; step <= totalSteps; step++) {
                        if (!validateStep(step, false)) { e.preventDefault(); goToStep(`step${step}`); validateStep(step, true); return; }
                    }
                    const submitButton = byId('submitBlotter');
                    if (submitButton) { submitButton.classList.add('loading'); submitButton.disabled = true; }
                });
                [declareTruth, agreePrivacy, consentProcessing].forEach(cb => { if (cb) cb.addEventListener('change', validateTermsQuietly); });
            }

            function validateStep(stepNumber, shouldScroll = true) {
                const stepElement = byId(`step${stepNumber}`);
                if (!stepElement) return true;
                clearStepErrors(stepElement);
                let isValid = true;
                if (stepNumber === 1) isValid = validateIncidentInfo() && isValid;
                if (stepNumber === 2) isValid = validateParties() && isValid;
                if (stepNumber === 3) isValid = validateEvidenceAndPreference() && isValid;
                if (stepNumber === 4) isValid = validateTerms() && isValid;
                if (!isValid && shouldScroll) scrollToFirstError(stepElement);
                return isValid;
            }
            function validateIncidentInfo() {
                let valid = true;
                const selectedReportType = document.querySelector('input[name="reportType"]:checked');
                const reportTypeGrid = document.querySelector('.report-type-grid');
                const incidentDate = byId('incidentDate'), incidentTime = byId('incidentTime'), incidentLocation = byId('incidentLocation'), incidentDescription = byId('incidentDescription'), immediateAction = byId('immediateAction');
                if (!selectedReportType) { showFieldError(reportTypeGrid, __cfg_barangay_system_incident_form_1.v16); valid = false; }
                if (!requireField(incidentDate, __cfg_barangay_system_incident_form_1.v17)) valid = false;
                if (!requireField(incidentTime, __cfg_barangay_system_incident_form_1.v18)) valid = false;
                if (incidentDate.value) {
                    const selectedDate = parseLocalDate(incidentDate.value), now = new Date(), todayOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    if (selectedDate > todayOnly) { showFieldError(incidentDate, __cfg_barangay_system_incident_form_1.v19); valid = false; }
                    if (incidentTime.value && sameDate(selectedDate, todayOnly)) {
                        const selectedDateTime = new Date(`${incidentDate.value}T${incidentTime.value}`);
                        if (selectedDateTime > now) { showFieldError(incidentTime, __cfg_barangay_system_incident_form_1.v20); valid = false; }
                    }
                }
                if (!validateTextLength(incidentLocation, 8, 255, __cfg_barangay_system_incident_form_1.v21, __cfg_barangay_system_incident_form_1.v22)) valid = false;
                if (!validateTextLength(incidentDescription, 20, 5000, __cfg_barangay_system_incident_form_1.v23, __cfg_barangay_system_incident_form_1.v24)) valid = false;
                if (immediateAction.value.trim().length > 2000) { showFieldError(immediateAction, __cfg_barangay_system_incident_form_1.v25); valid = false; }
                return valid;
            }
            function validateParties() {
                let valid = true;
                const complainantName = byId('complainantName'), complainantContact = byId('complainantContact'), complainantAddress = byId('complainantAddress'), complainantEmail = byId('complainantEmail');
                const respondentName = byId('respondentName'), respondentContact = byId('respondentContact'), respondentAddress = byId('respondentAddress'), respondentDescription = byId('respondentDescription');
                if (!validatePersonName(complainantName, true, __cfg_barangay_system_incident_form_1.v26)) valid = false;
                if (!validatePhone(complainantContact, true)) valid = false;
                if (!validateTextLength(complainantAddress, 10, 255, __cfg_barangay_system_incident_form_1.v27, __cfg_barangay_system_incident_form_1.v28)) valid = false;
                if (!validateEmail(complainantEmail, false)) valid = false;
                if (!validatePersonName(respondentName, false)) valid = false;
                if (!validatePhone(respondentContact, false)) valid = false;
                if (respondentAddress.value.trim() && !validateTextLength(respondentAddress, 5, 255, __cfg_barangay_system_incident_form_1.v29, __cfg_barangay_system_incident_form_1.v30)) valid = false;
                if (respondentDescription.value.trim().length > 1000) { showFieldError(respondentDescription, __cfg_barangay_system_incident_form_1.v31); valid = false; }
                const respondentHasSupportingDetails = respondentContact.value.trim() || respondentAddress.value.trim();
                const respondentHasIdentity = respondentName.value.trim() || respondentDescription.value.trim();
                if (respondentHasSupportingDetails && !respondentHasIdentity) { showFieldError(respondentDescription, __cfg_barangay_system_incident_form_1.v32); valid = false; }
                const witnessItems = getWitnessItems();
                if (witnessItems.length > MAX_WITNESSES) { showFieldError(witnessesList, __cfg_barangay_system_incident_form_1.v1.replace(':max', MAX_WITNESSES)); valid = false; }
                witnessItems.forEach(item => { if (!validateWitnessItem(item, true)) valid = false; });
                return valid;
            }
            function validateEvidenceAndPreference() {
                let valid = true;
                const selectedConfidentiality = document.querySelector('input[name="confidentiality"]:checked'), confidentialityOptions = document.querySelector('.confidentiality-options'), additionalInfo = byId('additionalInfo');
                Object.entries(FILE_RULES).forEach(([inputId, rules]) => {
                    const input = byId(inputId), uploadArea = byId(rules.uploadAreaId);
                    if (!input || !uploadArea) return;
                    const errors = validateFiles(Array.from(input.files || []), rules);
                    if (errors.length) { uploadArea.classList.add('error'); showFieldError(uploadArea, errors[0]); valid = false; }
                });
                if (additionalInfo.value.trim().length > 2000) { showFieldError(additionalInfo, __cfg_barangay_system_incident_form_1.v33); valid = false; }
                if (!selectedConfidentiality) { showFieldError(confidentialityOptions, __cfg_barangay_system_incident_form_1.v34); valid = false; }
                return valid;
            }
            function validateTerms() {
                const accepted = declareTruth?.checked && agreePrivacy?.checked && consentProcessing?.checked;
                if (!accepted) { if (termsError) termsError.style.display = 'block'; return false; }
                if (termsError) termsError.style.display = 'none'; return true;
            }
            function validateTermsQuietly() { if (termsError) termsError.style.display = (declareTruth?.checked && agreePrivacy?.checked && consentProcessing?.checked) ? 'none' : 'block'; }
            function validateWitnessItem(item, showEmptyErrors) {
                const nameInput = item.querySelector('.witness-name'), contactInput = item.querySelector('.witness-contact'), statementInput = item.querySelector('.witness-statement');
                const name = nameInput.value.trim(), contact = contactInput.value.trim(), statement = statementInput.value.trim();
                let valid = true;
                clearFieldError(nameInput); clearFieldError(contactInput); clearFieldError(statementInput);
                if (!name && !statement) { if (showEmptyErrors || contact) { showFieldError(nameInput, __cfg_barangay_system_incident_form_1.v35); showFieldError(statementInput, __cfg_barangay_system_incident_form_1.v36); } return false; }
                if (name && !validatePersonName(nameInput, false, '', __cfg_barangay_system_incident_form_1.v37)) valid = false;
                if (statement && statement.length < 5) { showFieldError(statementInput, __cfg_barangay_system_incident_form_1.v38); valid = false; }
                if (statement.length > 1000) { showFieldError(statementInput, __cfg_barangay_system_incident_form_1.v39); valid = false; }
                if (!validatePhone(contactInput, false)) valid = false;
                return valid;
            }
            function requireField(field, message) { if (!field || !field.value.trim()) { showFieldError(field, message); return false; } return true; }
            function validateTextLength(field, min, max, minMessage, maxMessage) {
                const value = field.value.trim();
                if (!value) { showFieldError(field, __cfg_barangay_system_incident_form_1.v40); return false; }
                if (value.length < min) { showFieldError(field, minMessage); return false; }
                if (value.length > max) { showFieldError(field, maxMessage); return false; }
                return true;
            }
            function validatePersonName(field, required = false, requiredMessage = __cfg_barangay_system_incident_form_1.v41, invalidMessage = __cfg_barangay_system_incident_form_1.v42) {
                const value = field.value.trim(), nameRegex = /^[\p{L}\s'.,-]+$/u;
                if (!value) { if (required) showFieldError(field, requiredMessage); return !required; }
                if (value.length < 2) { showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v43 + '); return false; }
                if (value.length > 120) { showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v44 + '); return false; }
                if (!nameRegex.test(value)) { showFieldError(field, invalidMessage); return false; }
                return true;
            }
            function validatePhone(field, required = false) {
                const value = field.value.replace(/\s|-/g, '').trim(), phoneRegex = /^09\d{9}$/;
                if (!value) { if (required) showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v45 + '); return !required; }
                if (!phoneRegex.test(value)) { showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v46 + '); return false; }
                field.value = value; return true;
            }
            function validateEmail(field, required = false) {
                const value = field.value.trim(), emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) { if (required) showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v47 + '); return !required; }
                if (value.length > 255 || !emailRegex.test(value)) { showFieldError(field, ' + __cfg_barangay_system_incident_form_1.v48 + '); return false; }
                return true;
            }
            function clearStepErrors(stepElement) {
                stepElement.querySelectorAll('.error-message').forEach(error => error.remove());
                stepElement.querySelectorAll('.error').forEach(field => { field.classList.remove('error'); field.style.borderColor = ''; field.style.boxShadow = ''; });
                if (termsError && stepElement.contains(termsError)) termsError.style.display = 'none';
            }
            function clearFieldError(field) {
                if (!field) return;
                field.classList.remove('error'); field.style.borderColor = ''; field.style.boxShadow = '';
                const container = getErrorContainer(field);
                if (container) container.querySelectorAll(':scope > .error-message').forEach(error => error.remove());
            }
            function showFieldError(field, message) {
                if (!field) return;
                field.classList.add('error'); field.style.borderColor = '#dc2626'; field.style.boxShadow = '0 0 0 4px rgba(220, 38, 38, 0.10)';
                const container = getErrorContainer(field); if (!container) return;
                const duplicate = Array.from(container.querySelectorAll(':scope > .error-message')).some(error => error.textContent === message);
                if (duplicate) return;
                const errorMessage = document.createElement('div'); errorMessage.className = 'error-message'; errorMessage.textContent = message; container.appendChild(errorMessage);
            }
            function getErrorContainer(field) {
                if (!field) return null;
                if (field.classList.contains('report-type-grid') || field.classList.contains('confidentiality-options') || field.classList.contains('upload-area') || field.classList.contains('witnesses-list')) return field.parentElement;
                return field.closest('.form-group') || field.parentElement;
            }
            function scrollToFirstError(stepElement) { const firstError = stepElement.querySelector('.error-message, .error'); if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
            function goToStep(stepId) { steps.forEach(step => step.classList.remove('active')); const targetStep = byId(stepId); if (!targetStep) return; targetStep.classList.add('active'); currentStep = parseInt(stepId.replace('step',''),10); updateProgress(); if (currentStep === 4) updateReview(); targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            function updateProgress() { const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100; if (progressFill) progressFill.style.width = `${progressPercentage}%`; progressSteps.forEach(step => { const stepNumber = parseInt(step.getAttribute('data-step'),10); step.classList.toggle('active', stepNumber === currentStep); }); }
            function updateReview() {
                const selectedReport = document.querySelector('input[name="reportType"]:checked'), selectedConfidentiality = document.querySelector('input[name="confidentiality"]:checked');
                const reportType = selectedReport ? selectedReport.closest('.report-type-option')?.querySelector('h4')?.textContent.trim() || selectedReport.value : ' + __cfg_barangay_system_incident_form_1.v49 + ';
                const confidentialityMap = { low: ' + __cfg_barangay_system_incident_form_1.v50 + ', medium: 'Confidential Report', high: ' + __cfg_barangay_system_incident_form_1.v51 + ' };
                const incidentReview = byId('reviewIncident'), partiesReview = byId('reviewParties'), evidenceReview = byId('reviewEvidence');
                if (incidentReview) incidentReview.innerHTML = reviewRows([[' + __cfg_barangay_system_incident_form_1.v52 + ', reportType], [' + __cfg_barangay_system_incident_form_1.v53 + ', ' + __cfg_barangay_system_incident_form_1.v54 + '.replace(':date', formatDate(getValue('incidentDate'))).replace(':time', getValue('incidentTime') || ' + __cfg_barangay_system_incident_form_1.v55 + ')], [' + __cfg_barangay_system_incident_form_1.v56 + ', getValue('incidentLocation') || ' + __cfg_barangay_system_incident_form_1.v55 + '], [' + __cfg_barangay_system_incident_form_1.v57 + ', summarize(getValue('incidentDescription'), 140)]]);
                if (partiesReview) partiesReview.innerHTML = reviewRows([[' + __cfg_barangay_system_incident_form_1.v58 + ', getValue('complainantName') || ' + __cfg_barangay_system_incident_form_1.v55 + '], [' + __cfg_barangay_system_incident_form_1.v6 + ', getValue('complainantContact') || ' + __cfg_barangay_system_incident_form_1.v55 + '], [' + __cfg_barangay_system_incident_form_1.v59 + ', getValue('respondentName') || getValue('respondentDescription') || ' + __cfg_barangay_system_incident_form_1.v55 + '], [' + __cfg_barangay_system_incident_form_1.v60 + ', ' + __cfg_barangay_system_incident_form_1.v61 + '.replace(':count', getWitnessItems().length)]]);
                if (evidenceReview) evidenceReview.innerHTML = reviewRows([[' + __cfg_barangay_system_incident_form_1.v62 + ', ' + __cfg_barangay_system_incident_form_1.v63 + '.replace(':count', byId('photos')?.files.length || 0)], [' + __cfg_barangay_system_incident_form_1.v64 + ', ' + __cfg_barangay_system_incident_form_1.v63 + '.replace(':count', byId('videos')?.files.length || 0)], [' + __cfg_barangay_system_incident_form_1.v65 + ', ' + __cfg_barangay_system_incident_form_1.v63 + '.replace(':count', byId('documents')?.files.length || 0)], [' + __cfg_barangay_system_incident_form_1.v66 + ', selectedConfidentiality ? confidentialityMap[selectedConfidentiality.value] : ' + __cfg_barangay_system_incident_form_1.v49 + ']]);
            }
            function reviewRows(rows) { return rows.map(([label, value]) => `<div class="review-item"><div class="review-label">${escapeHtml(label)}</div><div class="review-value">${escapeHtml(value)}</div></div>`).join(''); }
            function summarize(text, limit) { if (!text) return ' + __cfg_barangay_system_incident_form_1.v55 + '; return text.length > limit ? `${text.substring(0, limit)}...` : text; }
            function formatDate(dateString) { if (!dateString) return ' + __cfg_barangay_system_incident_form_1.v55 + '; const date = parseLocalDate(dateString); if (Number.isNaN(date.getTime())) return 'Invalid date'; return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }); }
            function parseLocalDate(dateString) { const [year, month, day] = dateString.split('-').map(Number); return new Date(year, month - 1, day); }
            function sameDate(a, b) { return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate(); }
            function toDateInputValue(date) { return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`; }
            function getFileIcon(file) { const name = file.name.toLowerCase(); if (file.type.includes('image') || name.match(/\.(jpg|jpeg|png)$/)) return 'fa-file-image'; if (file.type.includes('video') || name.match(/\.(mp4|avi|mov)$/)) return 'fa-file-video'; if (name.endsWith('.pdf')) return 'fa-file-pdf'; if (name.match(/\.(doc|docx)$/)) return 'fa-file-word'; return 'fa-file'; }
            function formatFileSize(bytes) { if (bytes === 0) return '0 Bytes'; const k = 1024, sizes = ['Bytes','KB','MB','GB'], i = Math.floor(Math.log(bytes) / Math.log(k)); return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`; }
            function escapeHtml(value) { return String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }
        });
