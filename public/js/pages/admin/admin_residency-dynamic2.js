const __cfg_admin_admin_residency_2 = JSON.parse(document.getElementById('js-config-admin-admin-residency-2')?.dataset.jsConfig || '{}');

        async function loadApplicationHistory(appId) {
            const list = document.getElementById('historyList' + appId);
            if (!list) {
                return;
            }

            list.innerHTML = 'Loading history...';

            try {
                const params = new URLSearchParams({
                    request_type: 'residency',
                    request_id: String(appId)
                });

                const response = await fetch("" + __cfg_admin_admin_residency_2.v1 + "?" + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load history.');
                }

                const payload = await response.json();
                const timeline = Array.isArray(payload.timeline) ? payload.timeline : [];

                if (timeline.length === 0) {
                    list.innerHTML = '<div class="text-muted">No activity found for this application yet.</div>';
                    return;
                }

                list.innerHTML = timeline.map(function(item) {
                    const details = item.details ? '<div class="text-muted mt-1">' + item.details + '</div>' : '';
                    const actor = item.actor ? '<div class="small text-muted">' + item.actor + ' � ' + (item.created_at || '') + '</div>' : '<div class="small text-muted">' + (item.created_at || '') + '</div>';
                    return '<div class="activity-item py-2 border-bottom">' +
                        '<div class="fw-semibold">' + (item.title || 'Activity') + '</div>' +
                        actor +
                        details +
                        '</div>';
                }).join('');
            } catch (error) {
                list.innerHTML = '<div class="text-danger">Unable to load history right now.</div>';
            }
        }

        function switchViewTab(appId, tabName) {
            const tabButton = document.getElementById(tabName + '-tab-' + appId);
            if (!tabButton) {
                return;
            }

            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }

        function toggleViewFooter(appId, activeTab) {
            const detailsFooter = document.getElementById('viewFooterDetails' + appId);
            const editFooter = document.getElementById('viewFooterEdit' + appId);
            const historyFooter = document.getElementById('viewFooterHistory' + appId);

            if (!detailsFooter || !editFooter || !historyFooter) {
                return;
            }

            detailsFooter.classList.add('d-none');
            editFooter.classList.add('d-none');
            historyFooter.classList.add('d-none');

            if (activeTab === 'details') {
                detailsFooter.classList.remove('d-none');
            } else if (activeTab === 'edit') {
                editFooter.classList.remove('d-none');
            } else {
                historyFooter.classList.remove('d-none');
            }
        }

        function toggleNotifyOptions(form, status) {
            const notifyWrap = form.querySelector('[data-notify-wrap]');
            if (!notifyWrap) return;

            const emailToggle = form.querySelector('input[name="notify_email"]');
            const smsToggle = form.querySelector('input[name="notify_sms"]');
            const hasEmail = notifyWrap.getAttribute('data-has-email') === '1';
            const hasSms = notifyWrap.getAttribute('data-has-sms') === '1';
            const allowNotify = ['approved', 'ready_pickup', 'rejected'].includes(String(status || '').toLowerCase());

            notifyWrap.classList.toggle('d-none', !allowNotify);

            if (emailToggle) {
                emailToggle.disabled = !allowNotify || !hasEmail;
                emailToggle.checked = allowNotify && hasEmail;
            }
            if (smsToggle) {
                smsToggle.disabled = !allowNotify || !hasSms;
                smsToggle.checked = allowNotify && hasSms;
            }
        }

        function setQuickEditValidity(field, message) {
            if (!field) return;

            let feedback = field.parentNode.querySelector('.invalid-feedback.quick-edit-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback quick-edit-feedback';
                field.parentNode.appendChild(feedback);
            }

            field.setCustomValidity(message || '');
            if (message) {
                field.classList.add('is-invalid');
                feedback.textContent = message;
            } else {
                field.classList.remove('is-invalid');
                feedback.textContent = '';
            }
        }

        function attachResidencyQuickEditValidation(form) {
            const firstName = form.querySelector('input[name="first_name"]');
            const middleName = form.querySelector('input[name="middle_name"]');
            const lastName = form.querySelector('input[name="last_name"]');
            const birthdate = form.querySelector('input[name="birthdate"]');
            const birthPlace = form.querySelector('input[name="birth_place"]');
            const email = form.querySelector('input[name="email"]');
            const contact = form.querySelector('input[name="contact_number"]');
            const address = form.querySelector('textarea[name="address"]');
            const household = form.querySelector('input[name="household_members"]');
            const years = form.querySelector('select[name="years_residing"]');
            const residencyType = form.querySelector('select[name="residency_type"]');
            const purpose = form.querySelector('select[name="purpose"]');
            const purposeOtherWrap = form.querySelector('[id^="edit_otherPurposeField_"]');
            const purposeOther = form.querySelector('input[name="purpose_other"]');
            const fileInputs = form.querySelectorAll('input[type="file"]');

            const namePattern = /^[A-Za-z][A-Za-z .'-]*$/;
            const textPattern = /^[A-Za-z0-9][A-Za-z0-9 .,'"()\-\/]*$/;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const allowedMime = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

            function togglePurposeOther() {
                if (!purpose || !purposeOtherWrap || !purposeOther) return;
                const isOther = purpose.value === 'other';
                purposeOtherWrap.style.display = isOther ? 'block' : 'none';
                purposeOther.required = isOther;
                if (!isOther) {
                    purposeOther.value = '';
                    setQuickEditValidity(purposeOther, '');
                }
            }

            function validateName(field, label, required) {
                if (!field) return true;
                const value = field.value.trim();
                if (!value && required) {
                    setQuickEditValidity(field, label + ' is required.');
                    return false;
                }
                if (!value && !required) {
                    setQuickEditValidity(field, '');
                    return true;
                }
                if (value.length < 2 || value.length > 50 || !namePattern.test(value)) {
                    setQuickEditValidity(field, label + ' must be 2-50 characters and contain letters only.');
                    return false;
                }
                setQuickEditValidity(field, '');
                return true;
            }

            function validateBirthdate() {
                if (!birthdate) return true;
                if (!birthdate.value) {
                    setQuickEditValidity(birthdate, 'Birthdate is required.');
                    return false;
                }
                const selectedDate = new Date(birthdate.value + 'T00:00:00');
                const today = new Date();
                if (selectedDate > today) {
                    setQuickEditValidity(birthdate, 'Birthdate cannot be in the future.');
                    return false;
                }
                setQuickEditValidity(birthdate, '');
                return true;
            }

            function validateEmail() {
                if (!email) return true;
                const value = email.value.trim();
                if (!value) {
                    setQuickEditValidity(email, 'Email is required.');
                    return false;
                }
                if (!emailPattern.test(value)) {
                    setQuickEditValidity(email, 'Please enter a valid email address.');
                    return false;
                }
                setQuickEditValidity(email, '');
                return true;
            }

            function validateContact() {
                if (!contact) return true;
                contact.value = contact.value.replace(/\D/g, '').slice(0, 11);
                if (!contact.value) {
                    setQuickEditValidity(contact, 'Contact number is required.');
                    return false;
                }
                if (!/^09\d{9}$/.test(contact.value)) {
                    setQuickEditValidity(contact, 'Use format 09XXXXXXXXX.');
                    return false;
                }
                setQuickEditValidity(contact, '');
                return true;
            }

            function validateBirthPlace() {
                if (!birthPlace) return true;
                const value = birthPlace.value.trim();
                if (value.length < 3 || value.length > 500 || !textPattern.test(value)) {
                    setQuickEditValidity(birthPlace, 'Birth place must be 3-500 valid characters.');
                    return false;
                }
                setQuickEditValidity(birthPlace, '');
                return true;
            }

            function validateAddress() {
                if (!address) return true;
                const value = address.value.trim();
                if (value.length < 8) {
                    setQuickEditValidity(address, 'Address must be at least 8 characters.');
                    return false;
                }
                setQuickEditValidity(address, '');
                return true;
            }

            function validateHousehold() {
                if (!household) return true;
                household.value = household.value.replace(/\D/g, '');
                const raw = household.value;
                const value = Number(raw);
                if (!/^(?:[1-9]|1\d|20)$/.test(raw) || Number.isNaN(value)) {
                    setQuickEditValidity(household, 'Household members must be between 1 and 20.');
                    return false;
                }
                setQuickEditValidity(household, '');
                return true;
            }

            function validateYears() {
                if (!years) return true;
                if (!years.value) {
                    setQuickEditValidity(years, 'Years residing is required.');
                    return false;
                }
                setQuickEditValidity(years, '');
                return true;
            }

            function validateResidencyType() {
                if (!residencyType) return true;
                if (!residencyType.value) {
                    setQuickEditValidity(residencyType, 'Residency type is required.');
                    return false;
                }
                setQuickEditValidity(residencyType, '');
                return true;
            }

            function validatePurposeOther() {
                if (!purpose || !purposeOther) return true;
                if (purpose.value === 'other' && purposeOther.value.trim().length < 3) {
                    setQuickEditValidity(purposeOther, 'Please specify at least 3 characters for other purpose.');
                    return false;
                }
                setQuickEditValidity(purposeOther, '');
                return true;
            }

            function validateFileInput(fileInput) {
                if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                    setQuickEditValidity(fileInput, '');
                    return true;
                }

                const file = fileInput.files[0];
                const sizeMb = file.size / (1024 * 1024);
                const extension = (file.name.split('.').pop() || '').toLowerCase();
                const mimeOk = file.type ? allowedMime.includes(file.type) : true;
                const extOk = allowedExt.includes(extension);

                if (!mimeOk || !extOk) {
                    setQuickEditValidity(fileInput, 'Only PDF, JPG, PNG, GIF, or WEBP files are allowed.');
                    return false;
                }

                if (sizeMb > 5) {
                    setQuickEditValidity(fileInput, 'File size must not exceed 5MB.');
                    return false;
                }

                setQuickEditValidity(fileInput, '');
                return true;
            }

            firstName?.addEventListener('input', function() { validateName(firstName, 'First name', true); });
            middleName?.addEventListener('input', function() { validateName(middleName, 'Middle name', false); });
            lastName?.addEventListener('input', function() { validateName(lastName, 'Last name', true); });
            birthdate?.addEventListener('change', validateBirthdate);
            birthPlace?.addEventListener('input', validateBirthPlace);
            email?.addEventListener('input', validateEmail);
            contact?.addEventListener('input', validateContact);
            address?.addEventListener('input', validateAddress);
            household?.addEventListener('input', validateHousehold);
            years?.addEventListener('change', validateYears);
            residencyType?.addEventListener('change', validateResidencyType);
            purpose?.addEventListener('change', function() {
                togglePurposeOther();
                validatePurposeOther();
            });
            purposeOther?.addEventListener('input', validatePurposeOther);
            fileInputs.forEach(function(fileInput) {
                fileInput.addEventListener('change', function() {
                    validateFileInput(fileInput);
                });
            });

            togglePurposeOther();

            form.addEventListener('submit', function(event) {
                const isValid = [
                    validateName(firstName, 'First name', true),
                    validateName(middleName, 'Middle name', false),
                    validateName(lastName, 'Last name', true),
                    validateBirthdate(),
                    validateBirthPlace(),
                    validateEmail(),
                    validateContact(),
                    validateAddress(),
                    validateHousehold(),
                    validateYears(),
                    validateResidencyType(),
                    validatePurposeOther(),
                    ...Array.from(fileInputs).map(validateFileInput)
                ].every(Boolean);

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                attachResidencyQuickEditValidation(form);
            });

            document.querySelectorAll('.status-grid').forEach(function(grid) {
                const input = grid.closest('form')?.querySelector('.selected-status-input');
                const form = grid.closest('form');
                const current = grid.getAttribute('data-current-status') || '';

                function activateStatus(status) {
                    grid.querySelectorAll('.status-option').forEach(function(btn) {
                        btn.classList.toggle('active', btn.getAttribute('data-status') === status);
                    });

                    if (input) {
                        input.value = status;
                    }
                    if (form) {
                        toggleNotifyOptions(form, status);
                    }
                }

                grid.querySelectorAll('.status-option').forEach(function(button) {
                    button.addEventListener('click', function() {
                        activateStatus(button.getAttribute('data-status'));
                    });
                });

                activateStatus(current);
            });

            document.querySelectorAll('[id^="viewModal"]').forEach(function(modalEl) {
                const appId = modalEl.id.replace('viewModal', '');

                modalEl.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function() {
                        if (tabBtn.id.indexOf('details-tab-') === 0) {
                            toggleViewFooter(appId, 'details');
                        } else if (tabBtn.id.indexOf('edit-tab-') === 0) {
                            toggleViewFooter(appId, 'edit');
                        } else {
                            toggleViewFooter(appId, 'history');
                            loadApplicationHistory(appId);
                        }
                    });
                });

                modalEl.addEventListener('shown.bs.modal', function() {
                    toggleViewFooter(appId, 'details');
                });
            });

            document.querySelectorAll('[data-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const appId = button.getAttribute('data-app-id');
                    const tabName = button.getAttribute('data-switch-tab');
                    switchViewTab(appId, tabName);
                });
            });
        });

        function prepareRemarksAndConfirm(event, form, confirmMessage) {
            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function handleUpdateStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-status-input');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = String(statusInput?.value || '').toLowerCase();
            const remarks = String(remarksInput?.value || '').trim();

            if (remarks.length > 40) {
                event.preventDefault();
                alert('Remarks must not exceed 40 characters.');
                return false;
            }

            if (status === 'rejected' && remarks === '') {
                event.preventDefault();
                alert('Remarks are required when status is Rejected.');
                return false;
            }

            return confirmDelete(event, 'Confirm status update?');
        }
