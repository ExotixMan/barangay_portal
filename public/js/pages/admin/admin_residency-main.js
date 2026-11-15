const __cfg_admin_residency = JSON.parse(document.getElementById('js-config-admin-residency-main')?.dataset.jsConfig || '{}');

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');

            if (!bulkForm) {
                return;
            }

            const action = bulkForm.dataset.bulkAction === 'restore' ? 'restore' : 'archive';
            const isRestore = action === 'restore';
            const actionLabel = isRestore ? 'restore' : 'archive';
            const actionTitle = isRestore ? 'Bulk Restore' : 'Bulk Archive';

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: `Please select at least one application to ${actionLabel}.`,
                    confirmButtonColor: isRestore ? '#198754' : '#d33'
                });
                return;
            }

            Swal.fire({
                title: `Confirm ${actionTitle}`,
                text: `Are you sure you want to ${actionLabel} ${checkboxes.length} selected application(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isRestore ? '#198754' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isRestore ? 'Yes, Restore' : 'Yes, Archive'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                bulkForm.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());

                checkboxes.forEach((checkbox) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    bulkForm.appendChild(input);
                });

                bulkForm.submit();
            });
        }

        function exportCSV() {

            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const exportForm = document.getElementById('exportForm');

            if (checkboxes.length === 0) {

                Swal.fire({
                    icon: 'question',
                    title: 'Export All?',
                    text: 'No applications selected. Export all records?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Export All'
                }).then((result) => {

                    if (result.isConfirmed) {
                        exportForm.submit();
                    }

                });

                return;
            }

            Swal.fire({
                title: 'Export Selected?',
                text: `Export ${checkboxes.length} selected application(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Export'
            }).then((result) => {

                if (result.isConfirmed) {

                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        exportForm.appendChild(input);
                    });

                    exportForm.submit();
                }

            });
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.application-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function confirmDelete(event, message) {
            const text = message || 'You won\'t be able to revert this!';

            if (event && typeof event.preventDefault === 'function') {
                event.preventDefault();
            }

            const form = event && event.target && typeof event.target.closest === 'function'
                ? event.target.closest('form')
                : null;

            if (typeof Swal === 'undefined') {
                return AdminUI.unavailableConfirm(text);
            }

            if (!form) {
                return AdminUI.unavailableConfirm(text);
            }

            Swal.fire({
                title: 'Are you sure?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }

        document.getElementById('purpose')?.addEventListener('change', function() {
            const otherField = document.getElementById('otherPurposeField');
            if (this.value === 'other') {
                otherField.style.display = 'block';
            } else {
                otherField.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const residentSearchInput = document.getElementById('residentSearchInput');
            const residentSearchResults = document.getElementById('residentSearchResults');
            const clearResidentSearch = document.getElementById('clearResidentSearch');
            const residentSelect = document.getElementById('addResidentSelect');
            const selectedResidentCard = document.getElementById('selectedResidentCard');
            const selectedResidentName = document.getElementById('selectedResidentName');
            const selectedResidentId = document.getElementById('selectedResidentId');
            const selectedResidentAddress = document.getElementById('selectedResidentAddress');
            const changeResidentButton = document.getElementById('changeResidentBtn');
            const residentStatus = document.getElementById('addResidentStatus');
            const residentIdStatus = document.getElementById('addResidentIdStatus');
            const addForm = document.getElementById('addApplicationForm');

            if (!residentSearchInput || !residentSearchResults || !residentSelect || !addForm) {
                return;
            }
            const residents = __cfg_admin_residency.residents;

            const fields = {
                first_name: addForm.querySelector('[name="first_name"]'),
                middle_name: addForm.querySelector('[name="middle_name"]'),
                last_name: addForm.querySelector('[name="last_name"]'),
                suffix: addForm.querySelector('[name="suffix"]'),
                birthdate: addForm.querySelector('[name="birthdate"]'),
                gender: addForm.querySelector('[name="gender"]'),
                civil_status: addForm.querySelector('[name="civil_status"]'),
                birth_place: addForm.querySelector('[name="birth_place"]'),
                address: addForm.querySelector('[name="address"]'),
                contact_number: addForm.querySelector('[name="contact_number"]'),
                email: addForm.querySelector('[name="email"]')
            };

            const manualEntryNotice = document.createElement('div');
            manualEntryNotice.className = 'resident-autofill-status';
            manualEntryNotice.innerHTML = '<i class="fas fa-pen-to-square"></i><span>No resident selected. You may enter applicant details manually.</span>';
            manualEntryNotice.id = 'manualEntryNotice';

            const searchWrapper = residentSearchInput.closest('.resident-search-wrapper');
            if (searchWrapper) {
                searchWrapper.insertAdjacentElement('afterend', manualEntryNotice);
            }

            function normalize(value) {
                return String(value ?? '')
                    .toLowerCase()
                    .replace(/^#/, '')
                    .trim();
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function formatResidentAddress(value) {
                return value ? escapeHtml(value) : 'Address not recorded';
            }

            function updateSearchState(open) {
                residentSearchInput.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) {
                    residentSearchResults.classList.remove('d-none');
                } else {
                    residentSearchResults.classList.add('d-none');
                }
            }

            function renderResults(query) {
                const search = normalize(query);
                residentSearchResults.innerHTML = '';

                if (!search) {
                    updateSearchState(false);
                    return;
                }

                const matches = residents
                    .filter(function (resident) {
                        const name = normalize(resident.full_name);
                        const id = normalize(resident.id);
                        return name.includes(search) || id.includes(search);
                    })
                    .slice(0, 8);

                if (!matches.length) {
                    residentSearchResults.innerHTML = `
                        <div class="resident-no-results">
                            <div class="no-results-icon" aria-hidden="true">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div>
                                <strong>No resident found</strong>
                                <small>Try the resident's name or Resident ID.</small>
                            </div>
                        </div>
                    `;
                    updateSearchState(true);
                    return;
                }

                matches.forEach(function (resident) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'resident-result-item';
                    button.setAttribute('role', 'option');

                    button.innerHTML = `
                        <div class="resident-result-avatar" aria-hidden="true">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="resident-result-content">
                            <div class="resident-result-name">
                                ${escapeHtml(resident.full_name)}
                            </div>
                            <div class="resident-result-meta">
                                Resident ID #${escapeHtml(resident.id)}
                                <span>•</span>
                                ${formatResidentAddress(resident.address)}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right resident-result-arrow" aria-hidden="true"></i>
                    `;

                    button.addEventListener('click', function () {
                        selectResident(resident);
                    });

                    residentSearchResults.appendChild(button);
                });

                updateSearchState(true);
            }

            function setField(field, value) {
                if (!field) return;

                field.value = value ?? '';
                field.classList.add('resident-autofilled');
            }

            function clearField(field) {
                if (!field) return;

                field.value = '';
                field.classList.remove('resident-autofilled');
            }

            function setResidentStatus(message, type = '') {
                if (!residentStatus) return;

                residentStatus.className = 'resident-autofill-status' + (type ? ` ${type}` : '');
                residentStatus.innerHTML = message;
            }

            function updateValidIdStatus(resident) {
                if (!residentIdStatus) return;

                const hasFront = Boolean(resident?.valid_id);
                const hasBack = Boolean(resident?.valid_id_back);

                if (hasFront && hasBack) {
                    residentIdStatus.className = 'resident-document-status is-ready';
                    residentIdStatus.innerHTML = `
                        <i class="fas fa-circle-check me-1"></i>
                        <strong>Front and back Valid ID are already on file.</strong>
                        The saved files will be reused unless you upload replacements.
                    `;
                    return;
                }

                const missingSides = [!hasFront ? 'front' : '', !hasBack ? 'back' : ''].filter(Boolean).join(' and ');
                residentIdStatus.className = 'resident-document-status is-warning';
                residentIdStatus.innerHTML = `
                    <i class="fas fa-triangle-exclamation me-1"></i>
                    <strong>Valid ID ${missingSides} side is missing.</strong>
                    Upload the missing side before submitting this application.
                `;
            }

            function selectResident(resident) {
                residentSelect.value = resident.id;

                residentSearchInput.value = resident.full_name || '';
                clearResidentSearch.classList.remove('d-none');

                selectedResidentName.textContent = resident.full_name || 'Unnamed resident';
                selectedResidentId.textContent = `Resident ID #${resident.id}`;
                selectedResidentAddress.textContent = resident.address || 'Address not recorded';

                selectedResidentCard.classList.remove('d-none');
                updateSearchState(false);

                setField(fields.first_name, resident.first_name);
                setField(fields.middle_name, resident.middle_name);
                setField(fields.last_name, resident.last_name);
                setField(fields.suffix, resident.suffix);
                setField(fields.birthdate, resident.birthdate);
                setField(fields.gender, resident.gender);
                setField(fields.civil_status, resident.civil_status);
                setField(fields.birth_place, resident.birth_place);
                setField(fields.address, resident.address);
                setField(fields.contact_number, resident.contact);
                setField(fields.email, resident.email);

                setResidentStatus(
                    '<i class="fas fa-circle-check"></i><span>Resident information loaded from the registered profile. Review the application details before submitting.</span>',
                    'is-ready'
                );

                manualEntryNotice.classList.add('d-none');
                updateValidIdStatus(resident);
            }

            function clearResidentSelection({ clearFields = true } = {}) {
                residentSelect.value = '';
                residentSearchInput.value = '';
                clearResidentSearch.classList.add('d-none');

                selectedResidentCard.classList.add('d-none');
                updateSearchState(false);

                if (clearFields) {
                    Object.values(fields).forEach(clearField);
                }

                setResidentStatus(
                    '<i class="fas fa-circle-info"></i><span>Select a registered resident to automatically fill identity and contact details.</span>'
                );

                manualEntryNotice.classList.remove('d-none');

                if (residentIdStatus) {
                    residentIdStatus.className = 'resident-document-status d-none';
                    residentIdStatus.innerHTML = '';
                }
            }

            function initializeOldResident() {
                const oldResidentId = residentSelect.value;

                if (!oldResidentId) {
                    manualEntryNotice.classList.remove('d-none');
                    return;
                }

                const resident = residents.find(function (item) {
                    return String(item.id) === String(oldResidentId);
                });

                if (resident) {
                    selectResident(resident);
                } else {
                    residentSelect.value = '';
                }
            }

            residentSearchInput.addEventListener('input', function () {
                const value = this.value.trim();

                clearResidentSearch.classList.toggle('d-none', !value);

                if (residentSelect.value) {
                    residentSelect.value = '';
                    selectedResidentCard.classList.add('d-none');

                    Object.values(fields).forEach(function (field) {
                        if (field) {
                            field.classList.remove('resident-autofilled');
                        }
                    });

                    manualEntryNotice.classList.remove('d-none');
                }

                renderResults(value);
            });

            residentSearchInput.addEventListener('focus', function () {
                if (this.value.trim()) {
                    renderResults(this.value);
                }
            });

            clearResidentSearch.addEventListener('click', function () {
                clearResidentSelection();
                residentSearchInput.focus();
            });

            changeResidentButton.addEventListener('click', function () {
                clearResidentSelection();
                residentSearchInput.focus();
            });

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.resident-search-wrapper')) {
                    updateSearchState(false);
                }
            });

            residentSearchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    updateSearchState(false);
                }
            });

            function validateApplicationValidIds(event) {
                const selectedResident = residentSelect.value
                    ? residents.find(function (resident) {
                        return String(resident.id) === String(residentSelect.value);
                    })
                    : null;
                const frontInput = addForm.querySelector('[name="government_id"]');
                const backInput = addForm.querySelector('[name="government_id_back"]');
                const hasFront = Boolean(selectedResident?.valid_id) || Boolean(frontInput?.files?.length);
                const hasBack = Boolean(selectedResident?.valid_id_back) || Boolean(backInput?.files?.length);

                if (hasFront && hasBack) return true;

                event?.preventDefault();
                const missingSides = [!hasFront ? 'front' : '', !hasBack ? 'back' : ''].filter(Boolean).join(' and ');
                if (residentIdStatus) {
                    residentIdStatus.className = 'resident-document-status is-warning';
                    residentIdStatus.innerHTML = `
                        <i class="fas fa-triangle-exclamation me-1"></i>
                        <strong>Valid ID ${missingSides} side is required.</strong>
                        Use the resident's saved ID or upload the missing side before submitting.
                    `;
                }
                (!hasFront ? frontInput : backInput)?.focus();
                return false;
            }

            addForm.addEventListener('submit', validateApplicationValidIds);

            ['government_id', 'government_id_back'].forEach(function (name) {
                addForm.querySelector(`[name="${name}"]`)?.addEventListener('change', function () {
                    const selectedResident = residentSelect.value
                        ? residents.find(function (resident) { return String(resident.id) === String(residentSelect.value); })
                        : null;
                    const frontInput = addForm.querySelector('[name="government_id"]');
                    const backInput = addForm.querySelector('[name="government_id_back"]');
                    const hasFront = Boolean(selectedResident?.valid_id) || Boolean(frontInput?.files?.length);
                    const hasBack = Boolean(selectedResident?.valid_id_back) || Boolean(backInput?.files?.length);
                    if (!residentIdStatus) return;
                    if (hasFront && hasBack) {
                        residentIdStatus.className = 'resident-document-status is-ready';
                        residentIdStatus.innerHTML = '<i class="fas fa-circle-check me-1"></i><strong>Both Valid ID sides are ready.</strong> Saved files and/or selected uploads will be used.';
                    } else {
                        const missingSides = [!hasFront ? 'front' : '', !hasBack ? 'back' : ''].filter(Boolean).join(' and ');
                        residentIdStatus.className = 'resident-document-status is-warning';
                        residentIdStatus.innerHTML = `<i class="fas fa-triangle-exclamation me-1"></i><strong>Still missing the ${missingSides} side.</strong>`;
                    }
                });
            });

            initializeOldResident();
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
                window.confirmDelete = function(event, message) {
                    return AdminUI.unavailableConfirm(message || 'Are you sure?');
                };
            }

            const checkboxes = document.querySelectorAll('.application-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');

            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                });
            }

            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                        submitBtn.disabled = true;
                    }
                });
            }

            setTimeout(() => {
                document.querySelectorAll('.alert-success').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            if (__cfg_admin_residency.showAddModal) {
                const addModal = new bootstrap.Modal(document.getElementById('addApplicationModal'));
                addModal.show();
            }

            const addForm = document.getElementById('addApplicationForm');
            if (addForm) {
                const contact = document.querySelector('input[name="contact_number"]');
                if (contact) {
                    contact.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '');
                        if (this.value.length > 11) {
                            this.value = this.value.slice(0, 11);
                        }
                        if (this.value.length > 0 && !this.value.startsWith('09')) {
                            this.setCustomValidity('Contact number must start with 09');
                            this.classList.add('is-invalid');
                        }
                        else if (this.value.length !== 11) {
                            this.setCustomValidity('Contact number must be exactly 11 digits');
                            this.classList.add('is-invalid');
                        }
                        else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                const birthdate = document.querySelector('input[name="birthdate"]');
                if (birthdate) {
                    birthdate.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const today = new Date();
                        if (selectedDate > today) {
                            this.setCustomValidity('Birthdate cannot be in the future');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                const years = document.querySelector('input[name="years_residing"]');
                if (years) {
                    years.addEventListener('input', function() {
                        const val = parseInt(this.value);
                        if (val < 0) {
                            this.setCustomValidity('Years cannot be negative');
                            this.classList.add('is-invalid');
                        } else if (val > 120) {
                            this.setCustomValidity('Years cannot exceed 120');
                            this.classList.add('is-invalid');
                        } else {
                            this.setCustomValidity('');
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                const fileInputs = addForm.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const fileSize = this.files[0].size / 1024 / 1024;
                            if (fileSize > 5) {
                                this.setCustomValidity('File size must not exceed 5MB');
                                this.classList.add('is-invalid');
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        }
                    });
                });
            }
        });

        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            zoomImage.src = imageUrl;

            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }
        if (__cfg_admin_residency.canUpdate) {
            __cfg_admin_residency.applicationIds.forEach(function (editIdFromConfig) {
            (function(editId) {
                const editForm = document.getElementById('editApplicationForm' + editId);
                if (editForm) {
                    const purposeSelect = document.getElementById('edit_purpose_' + editId);
                    const otherField = document.getElementById('edit_otherPurposeField_' + editId);
                    if (purposeSelect && otherField) {
                        purposeSelect.addEventListener('change', function() {
                            if (this.value === 'other') {
                                otherField.style.display = 'block';
                            } else {
                                otherField.style.display = 'none';
                            }
                        });
                    }

                    const contact = document.getElementById('edit_contact_number_' + editId);
                    if (contact) {
                        contact.addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                            if (this.value.length > 11) {
                                this.value = this.value.slice(0, 11);
                            }
                            if (this.value.length > 0 && !this.value.startsWith('09')) {
                                this.setCustomValidity('Contact number must start with 09');
                                this.classList.add('is-invalid');
                            }
                            else if (this.value.length > 0 && this.value.length !== 11) {
                                this.setCustomValidity('Contact number must be exactly 11 digits');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must be exactly 11 digits';
                            }
                            else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const birthdate = document.getElementById('edit_birthdate_' + editId);
                    if (birthdate) {
                        birthdate.addEventListener('change', function() {
                            const selectedDate = new Date(this.value);
                            const today = new Date();
                            if (selectedDate > today) {
                                this.setCustomValidity('Birthdate cannot be in the future');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Birthdate cannot be in the future';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const email = document.getElementById('edit_email_' + editId);
                    if (email) {
                        email.addEventListener('input', function() {
                            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailPattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Please enter a valid email address');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Please enter a valid email address';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const household = document.getElementById('edit_household_members_' + editId);
                    if (household) {
                        household.addEventListener('input', function() {
                            this.value = this.value.replace(/\D/g, '');
                            const raw = this.value;
                            if (!/^(?:[1-9]|1\d|20)$/.test(raw)) {
                                this.setCustomValidity('Household members must be between 1 and 20');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Household members must be between 1 and 20';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const primaryProof = document.getElementById('edit_primary_proof_' + editId);
                    if (primaryProof) {
                        primaryProof.addEventListener('change', function() {
                            if (this.files && this.files[0]) {
                                const fileSize = this.files[0].size / 1024 / 1024;
                                if (fileSize > 5) {
                                    this.setCustomValidity('File size must not exceed 5MB');
                                    this.classList.add('is-invalid');

                                    let feedback = this.nextElementSibling;
                                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        this.parentNode.appendChild(feedback);
                                    }
                                    feedback.textContent = 'File size must not exceed 5MB';
                                } else {
                                    this.setCustomValidity('');
                                    this.classList.remove('is-invalid');
                                }
                            }
                        });
                    }

                    const govId = document.getElementById('edit_government_id_' + editId);
                    if (govId) {
                        govId.addEventListener('change', function() {
                            if (this.files && this.files[0]) {
                                const fileSize = this.files[0].size / 1024 / 1024;
                                if (fileSize > 5) {
                                    this.setCustomValidity('File size must not exceed 5MB');
                                    this.classList.add('is-invalid');

                                    let feedback = this.nextElementSibling;
                                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        this.parentNode.appendChild(feedback);
                                    }
                                    feedback.textContent = 'File size must not exceed 5MB';
                                } else {
                                    this.setCustomValidity('');
                                    this.classList.remove('is-invalid');
                                }
                            }
                        });
                    }

                    const firstName = document.getElementById('edit_first_name_' + editId);
                    if (firstName) {
                        firstName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('First name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'First name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const lastName = document.getElementById('edit_last_name_' + editId);
                    if (lastName) {
                        lastName.addEventListener('input', function() {
                            const namePattern = /^[a-zA-Z\s\-]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Last name can only contain letters, spaces, and hyphens');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Last name can only contain letters, spaces, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }
                }
            })(String(editIdFromConfig));
            });
        }
