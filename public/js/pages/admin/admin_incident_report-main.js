const __cfg_admin_incidents = JSON.parse(document.getElementById('js-config-admin-incidents-main')?.dataset.jsConfig || '{}');

        function bulkAction(action) {
            const checkboxes = document.querySelectorAll('.application-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');
            const isRestore = action === 'restore';
            const actionLabel = isRestore ? 'restore' : 'archive';
            const actionTitle = isRestore ? 'Bulk Restore' : 'Bulk Archive';
            const confirmColor = isRestore ? '#198754' : '#d33';

            if (!bulkForm) {
                return;
            }

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: `Please select at least one incident report to ${actionLabel}.`,
                    confirmButtonColor: confirmColor
                });

                return;
            }

            Swal.fire({
                title: `Confirm ${actionTitle}`,
                text: `Are you sure you want to ${actionLabel} ${checkboxes.length} selected incident report(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: isRestore ? 'Yes, Restore' : 'Yes, Archive'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                bulkForm.querySelectorAll('input[data-bulk-id]').forEach(input => input.remove());

                checkboxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    input.dataset.bulkId = 'true';
                    bulkForm.appendChild(input);
                });

                bulkForm.submit();
            });
        }

        function exportCSV() {
            return AdminExport.open();
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.application-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: message || 'You won\'t be able to revert this!',
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

        async function viewRemarksHistory(requestType, requestId, referenceNumber) {
            try {
                const params = new URLSearchParams({
                    request_type: requestType,
                    request_id: String(requestId)
                });
                const response = await fetch(__cfg_admin_incidents.remarksHistoryUrl + "?" + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load remarks history.');
                }

                const payload = await response.json();
                const history = Array.isArray(payload.history) ? payload.history : [];

                if (history.length === 0) {
                    AdminUI.notify('No saved remarks found for this request yet.');
                    return;
                }

                const details = history
                    .map(function(item, index) {
                        const channel = item.channel ? item.channel.toUpperCase() : 'N/A';
                        const status = item.status ? item.status.toUpperCase() : 'N/A';
                        return (index + 1) + '. [' + status + '] ' + item.remarks + '\n   by ' + item.admin_name + ' via ' + channel + ' on ' + item.created_at;
                    })
                    .join('\n\n');

                AdminUI.notify('Remarks History - ' + referenceNumber + '\n\n' + details);
            } catch (error) {
                AdminUI.notify(error.message || 'Unable to load remarks history right now.');
            }
        }

        function prepareRemarksAndConfirm(event, form, confirmMessage) {
            return AdminUI.confirmSubmission(event, form, confirmMessage, {requestRemarks: true});
        }

        async function loadApplicationHistory(appId) {
            const list = document.getElementById('historyList' + appId);
            if (!list) {
                return;
            }

            list.innerHTML = 'Loading history...';

            try {
                const params = new URLSearchParams({
                    request_type: 'incident',
                    request_id: String(appId)
                });

                const response = await fetch(__cfg_admin_incidents.remarksHistoryUrl + "?" + params.toString(), {
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
                    list.innerHTML = '<div class="text-muted">No activity found for this report yet.</div>';
                    return;
                }

                list.innerHTML = timeline.map(function(item) {
                    const details = item.details ? '<div class="text-muted mt-1">' + AdminUI.escape(item.details) + '</div>' : '';
                    const actor = item.actor ? '<div class="small text-muted">' + AdminUI.escape(item.actor) + ' - ' + AdminUI.escape(item.created_at || '') + '</div>' : '<div class="small text-muted">' + AdminUI.escape(item.created_at || '') + '</div>';
                    return '<div class="activity-item py-2 border-bottom">' +
                        '<div class="fw-semibold">' + AdminUI.escape(item.title || 'Activity') + '</div>' +
                        actor +
                        details +
                        '</div>';
                }).join('');
            } catch (error) {
                list.innerHTML = '<div class="text-danger">Unable to load history right now.</div>';
            }
        }

        function toggleIncidentNotifyOptions(form, status) {
            const notifyWrap = form.querySelector('[data-notify-wrap]');
            if (!notifyWrap) {
                return;
            }

            const emailToggle = form.querySelector('input[name="notify_email"]');
            const smsToggle = form.querySelector('input[name="notify_sms"]');
            const hasEmail = notifyWrap.getAttribute('data-has-email') === '1';
            const hasSms = notifyWrap.getAttribute('data-has-sms') === '1';
            const allowNotify = ['resolved', 'dropped'].includes(String(status || '').toLowerCase());

            notifyWrap.classList.toggle('d-none', !allowNotify);

            if (emailToggle) {
                const canUseEmail = allowNotify && hasEmail && !emailToggle.hasAttribute('data-permission-disabled');
                emailToggle.disabled = !canUseEmail;
                emailToggle.checked = canUseEmail;
            }

            if (smsToggle) {
                const canUseSms = allowNotify && hasSms && !smsToggle.hasAttribute('data-permission-disabled');
                smsToggle.disabled = !canUseSms;
                smsToggle.checked = canUseSms;
            }
        }

        function handleIncidentStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-incident-status');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';
            const remarks = remarksInput ? String(remarksInput.value || '').trim() : '';

            if (!status) {
                event.preventDefault();
                AdminUI.notify('Please select a status.');
                return false;
            }

            if (remarks.length > 40) {
                event.preventDefault();
                AdminUI.notify('Remarks must not exceed 40 characters.');
                return false;
            }

            if (status === 'dropped' && remarks === '') {
                event.preventDefault();
                AdminUI.notify('Remarks are required when status is Dropped.');
                return false;
            }

            const processingUrl = form.getAttribute('data-processing-url');
            const approveUrl = form.getAttribute('data-approve-url');
            const rejectUrl = form.getAttribute('data-reject-url');
            if (status === 'processing') {
                form.action = processingUrl;
            } else if (status === 'dropped') {
                form.action = rejectUrl;
            } else {
                form.action = approveUrl;
            }

            return AdminUI.confirmSubmission(event, form, 'Confirm status update to ' + status.charAt(0).toUpperCase() + status.slice(1) + '?');
        }

        function switchViewTab(appId, tabName) {
            const tabButton = document.getElementById(tabName + '-tab-' + appId);
            if (tabButton) {
                bootstrap.Tab.getOrCreateInstance(tabButton).show();
            }
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

            function normalizePhoneValue(value) {
                let cleaned = (value || '').replace(/[^0-9]/g, '').slice(0, 12);
                if (cleaned.startsWith('63') && cleaned.length === 12) {
                    cleaned = '0' + cleaned.slice(2);
                }
                return cleaned.slice(0, 11);
            }

            function bindClearInvalidOnEdit(form) {
                if (!form) return;

                form.querySelectorAll('input, textarea, select').forEach(function(field) {
                    const clearState = function() {
                        field.classList.remove('is-invalid');
                        field.setCustomValidity('');
                    };

                    field.addEventListener('input', clearState);
                    field.addEventListener('change', clearState);
                });

                form.querySelectorAll('input[name$="Contact"], input[name*="[contact]"]').forEach(function(field) {
                    field.addEventListener('input', function() {
                        this.value = normalizePhoneValue(this.value);
                    });
                    field.addEventListener('blur', function() {
                        this.value = normalizePhoneValue(this.value);
                    });
                });
            }

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

            document.querySelectorAll('[id^="updateStatusModal"]').forEach(function(modalEl) {
                const form = modalEl.querySelector('form');
                const statusInput = form ? form.querySelector('.selected-incident-status') : null;
                if (!form || !statusInput) {
                    return;
                }

                modalEl.querySelectorAll('.status-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const nextStatus = btn.getAttribute('data-status');
                        if (!nextStatus) {
                            return;
                        }

                        modalEl.querySelectorAll('.status-option').forEach(function(other) {
                            other.classList.remove('active');
                        });
                        btn.classList.add('active');
                        statusInput.value = nextStatus;
                        toggleIncidentNotifyOptions(form, nextStatus);
                    });
                });

                modalEl.addEventListener('shown.bs.modal', function() {
                    const gridEl = modalEl.querySelector('.status-grid');
                    const current = ((gridEl ? gridEl.getAttribute('data-current-status') : '') || '').toLowerCase();
                    const buttons = Array.from(modalEl.querySelectorAll('.status-option'));
                    const activeButton = buttons.find(function(btn) {
                        return btn.getAttribute('data-status') === current;
                    }) || buttons[0];

                    if (activeButton) {
                        buttons.forEach(function(btn) { btn.classList.remove('active'); });
                        activeButton.classList.add('active');
                        statusInput.value = activeButton.getAttribute('data-status') || 'processing';
                    }

                    toggleIncidentNotifyOptions(form, statusInput.value);
                });
            });

            function setFieldValidity(field, message) {
                if (!field) return;
                let feedback = field.parentNode.querySelector('.invalid-feedback.dynamic-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback dynamic-feedback';
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

            function attachIncidentQuickEditValidation(form) {
                if (!form) return;

                const namePattern = /^[A-Za-z\s\-\.',]+$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const complainantName = form.querySelector('input[name="complainantName"]');
                const complainantContact = form.querySelector('input[name="complainantContact"]');
                const complainantEmail = form.querySelector('input[name="complainantEmail"]');
                const complainantAddress = form.querySelector('input[name="complainantAddress"]');
                const incidentDate = form.querySelector('input[name="incidentDate"]');
                const incidentTime = form.querySelector('input[name="incidentTime"]');
                const incidentLocation = form.querySelector('input[name="incidentLocation"]');
                const incidentDescription = form.querySelector('textarea[name="incidentDescription"]');
                const respondentContact = form.querySelector('input[name="respondentContact"]');

                const validateName = function(field, label, required) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v && required) {
                        setFieldValidity(field, label + ' is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (v.length < 2 || !namePattern.test(v)) {
                        setFieldValidity(field, label + ' must be valid letters and punctuation only.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validatePhone = function(field, required) {
                    if (!field) return true;
                    field.value = (field.value || '').replace(/[^0-9]/g, '').slice(0, 12);
                    if (field.value.startsWith('63') && field.value.length === 12) {
                        field.value = '0' + field.value.slice(2);
                    }
                    const v = field.value;
                    if (!v && required) {
                        setFieldValidity(field, 'Contact number is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (!/^09[0-9]{9}$/.test(v)) {
                        setFieldValidity(field, 'Contact number must be 11 digits and start with 09.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateEmail = function(field) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v) {
                        setFieldValidity(field, 'Email is required.');
                        return false;
                    }
                    if (!emailPattern.test(v)) {
                        setFieldValidity(field, 'Please enter a valid email address.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateText = function(field, label, minLen, required) {
                    if (!field) return true;
                    const v = field.value.trim();
                    if (!v && required) {
                        setFieldValidity(field, label + ' is required.');
                        return false;
                    }
                    if (!v && !required) {
                        setFieldValidity(field, '');
                        return true;
                    }
                    if (v.length < minLen) {
                        setFieldValidity(field, label + ' must be at least ' + minLen + ' characters.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateDate = function(field) {
                    if (!field) return true;
                    if (!field.value) {
                        setFieldValidity(field, 'Incident date is required.');
                        return false;
                    }
                    const selected = new Date(field.value + 'T00:00:00');
                    const today = new Date();
                    today.setHours(23, 59, 59, 999);
                    if (selected > today) {
                        setFieldValidity(field, 'Incident date cannot be in the future.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                const validateTime = function(field) {
                    if (!field) return true;
                    if (!field.value) {
                        setFieldValidity(field, 'Incident time is required.');
                        return false;
                    }
                    setFieldValidity(field, '');
                    return true;
                };

                if (complainantName) complainantName.addEventListener('input', function() { validateName(complainantName, 'Complainant name', true); });
                if (complainantContact) complainantContact.addEventListener('input', function() { validatePhone(complainantContact, true); });
                if (respondentContact) respondentContact.addEventListener('input', function() { validatePhone(respondentContact, false); });
                if (complainantEmail) complainantEmail.addEventListener('input', function() { validateEmail(complainantEmail); });
                if (complainantAddress) complainantAddress.addEventListener('input', function() { validateText(complainantAddress, 'Complainant address', 8, true); });
                if (incidentDate) incidentDate.addEventListener('change', function() { validateDate(incidentDate); });
                if (incidentTime) incidentTime.addEventListener('change', function() { validateTime(incidentTime); });
                if (incidentLocation) incidentLocation.addEventListener('input', function() { validateText(incidentLocation, 'Incident location', 3, true); });
                if (incidentDescription) incidentDescription.addEventListener('input', function() { validateText(incidentDescription, 'Incident description', 10, true); });

                form.addEventListener('input', function(event) {
                    if (event.target.matches('input[name*="[contact]"]')) {
                        validatePhone(event.target, false);
                    }
                    if (event.target.matches('input[name*="[name]"]')) {
                        validateName(event.target, 'Witness name', false);
                    }
                });

                form.addEventListener('change', function(event) {
                    const target = event.target;
                    if (!(target instanceof HTMLInputElement)) {
                        return;
                    }

                    if (!target.matches('input[type="file"]')) {
                        return;
                    }

                    const files = Array.from(target.files || []);
                    if (files.length === 0) {
                        setFieldValidity(target, '');
                        return;
                    }

                    let limitMb = 5;
                    if (target.name === 'photos[]') limitMb = 10;
                    if (target.name === 'videos[]') limitMb = 50;

                    const tooLarge = files.find(function(file) {
                        return (file.size / (1024 * 1024)) > limitMb;
                    });
                    if (tooLarge) {
                        setFieldValidity(target, 'Each file must be below ' + limitMb + 'MB.');
                        return;
                    }

                    setFieldValidity(target, '');
                });

                form.addEventListener('submit', function(event) {
                    const valid = [
                        validateName(complainantName, 'Complainant name', true),
                        validatePhone(complainantContact, true),
                        validatePhone(respondentContact, false),
                        validateEmail(complainantEmail),
                        validateText(complainantAddress, 'Complainant address', 8, true),
                        validateDate(incidentDate),
                        validateTime(incidentTime),
                        validateText(incidentLocation, 'Incident location', 3, true),
                        validateText(incidentDescription, 'Incident description', 10, true)
                    ].every(Boolean);

                    if (!valid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            }

            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                bindClearInvalidOnEdit(form);
                attachIncidentQuickEditValidation(form);
            });

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

            const addForm = document.getElementById('addIncidentForm');
            if (addForm) {
                bindClearInvalidOnEdit(addForm);

                function sf(el, msg) {
                    if (!el) return;
                    el.classList.add('is-invalid');
                    let fb = el.parentElement.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.insertAdjacentElement('afterend', fb); }
                    fb.textContent = msg;
                }
                function cf(el) { if (el) { el.classList.remove('is-invalid'); el.setCustomValidity(''); } }

                const complainantName    = addForm.querySelector('[name="complainantName"]');
                const contact            = addForm.querySelector('[name="complainantContact"]');
                const complainantEmail   = addForm.querySelector('[name="complainantEmail"]');
                const incidentDate       = addForm.querySelector('[name="incidentDate"]');
                const incidentLocation   = addForm.querySelector('[name="incidentLocation"]');
                const incidentDescription = addForm.querySelector('[name="incidentDescription"]');

                if (complainantName) {
                    complainantName.addEventListener('input', function() { cf(this); }, {once: true});
                    complainantName.addEventListener('input', function() {
                        const v = this.value.trim();
                        if (!v) sf(this, 'Complainant name is required.');
                        else if (v.length < 2) sf(this, 'Name must be at least 2 characters.');
                        else if (!/^[A-Za-z\s\-\.',]+$/.test(v)) sf(this, 'Name must contain only letters, spaces, apostrophes, commas, hyphens, or periods.');
                        else cf(this);
                    });
                }
                if (contact) {
                    contact.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
                        if (this.value.startsWith('63') && this.value.length === 12) {
                            this.value = '0' + this.value.slice(2);
                        }
                        if (this.value.length > 0 && !this.value.startsWith('09')) sf(this, 'Contact must start with 09.');
                        else if (this.value.length > 0 && this.value.length !== 11) sf(this, 'Contact must be exactly 11 digits.');
                        else cf(this);
                    });
                }
                if (complainantEmail) {
                    complainantEmail.addEventListener('input', function() {
                        const v = this.value.trim();
                        if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) sf(this, 'Enter a valid email address.');
                        else cf(this);
                    });
                }
                if (incidentDate) {
                    incidentDate.addEventListener('change', function() {
                        const selected = new Date(this.value);
                        const today = new Date(); today.setHours(23, 59, 59, 999);
                        if (!this.value) sf(this, 'Incident date is required.');
                        else if (selected > today) sf(this, 'Incident date cannot be in the future.');
                        else cf(this);
                    });
                }
                if (incidentLocation) {
                    incidentLocation.addEventListener('input', function() { cf(this); }, {once: true});
                    incidentLocation.addEventListener('input', function() {
                        if (!this.value.trim()) sf(this, 'Incident location is required.');
                        else if (this.value.trim().length < 3) sf(this, 'Location must be at least 3 characters.');
                        else cf(this);
                    });
                }
                if (incidentDescription) {
                    incidentDescription.addEventListener('input', function() { cf(this); }, {once: true});
                    incidentDescription.addEventListener('input', function() {
                        if (!this.value.trim()) sf(this, 'Description is required.');
                        else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                        else cf(this);
                    });
                }

                addForm.addEventListener('submit', function(e) {
                    let valid = true;
                    if (complainantName) {
                        const v = complainantName.value.trim();
                        if (!v) { sf(complainantName, 'Complainant name is required.'); valid = false; }
                        else if (v.length < 2) { sf(complainantName, 'Name must be at least 2 characters.'); valid = false; }
                        else if (!/^[A-Za-z\s\-\.',]+$/.test(v)) { sf(complainantName, 'Name must contain only letters, spaces, apostrophes, commas, hyphens, or periods.'); valid = false; }
                    }
                    if (contact) {
                        if (!contact.value) { sf(contact, 'Contact number is required.'); valid = false; }
                        else if (!contact.value.startsWith('09') || contact.value.length !== 11) { sf(contact, 'Contact must be 11 digits starting with 09.'); valid = false; }
                    }
                    if (complainantEmail) {
                        const emailValue = complainantEmail.value.trim();
                        if (!emailValue) {
                            sf(complainantEmail, 'Email is required.');
                            valid = false;
                        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                            sf(complainantEmail, 'Enter a valid email address.'); valid = false;
                        }
                    }
                    if (incidentDate) {
                        const selected = new Date(incidentDate.value);
                        const today = new Date(); today.setHours(23, 59, 59, 999);
                        if (!incidentDate.value) { sf(incidentDate, 'Incident date is required.'); valid = false; }
                        else if (selected > today) { sf(incidentDate, 'Incident date cannot be in the future.'); valid = false; }
                    }
                    if (incidentLocation) {
                        if (!incidentLocation.value.trim()) { sf(incidentLocation, 'Incident location is required.'); valid = false; }
                        else if (incidentLocation.value.trim().length < 3) { sf(incidentLocation, 'Location must be at least 3 characters.'); valid = false; }
                    }
                    if (incidentDescription) {
                        if (!incidentDescription.value.trim()) { sf(incidentDescription, 'Description is required.'); valid = false; }
                        else if (incidentDescription.value.trim().length < 10) { sf(incidentDescription, 'Description must be at least 10 characters.'); valid = false; }
                    }
                    if (!valid) e.preventDefault();
                });
            }
        });

        let searchTimeout;
        const globalSearch = document.getElementById('globalSearch');
        if (globalSearch) {
            globalSearch.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            zoomImage.src = imageUrl;

            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        let witnessCount = 0;

        function addWitnessField() {
            witnessCount++;

            const container = document.getElementById('witnesses-container');
            const witnessHtml = `
                <div class="witness-card mb-3" id="witness-${witnessCount}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeWitnessField(${witnessCount})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${witnessCount}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Witness Statement</label>
                            <textarea class="form-control" name="witnesses[${witnessCount}][statement]" rows="2" placeholder="What did the witness see/hear?"></textarea>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', witnessHtml);

            const newContact = document.querySelector(`#witness-${witnessCount} input[name="witnesses[${witnessCount}][contact]"]`);
            if (newContact) {
                newContact.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 12) {
                        this.value = this.value.slice(0, 12);
                    }
                    if (this.value.startsWith('63') && this.value.length === 12) {
                        this.value = '0' + this.value.slice(2);
                    }
                });
            }
        }

        function removeWitnessField(id) {
            const witnessElement = document.getElementById(`witness-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.querySelector('input[name="photos"]');
            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024;
                        if (fileSize > 10) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Photo must be less than 10MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }

            const videoInput = document.querySelector('input[name="videos"]');
            if (videoInput) {
                videoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024;
                        if (fileSize > 50) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Video must be less than 50MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }

            const docInput = document.querySelector('input[name="documents"]');
            if (docInput) {
                docInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size / 1024 / 1024;
                        if (fileSize > 5) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Document must be less than 5MB'
                            });
                            this.value = '';
                        }
                    }
                });
            }
        });

        if (__cfg_admin_incidents.canUpdate) {
            __cfg_admin_incidents.incidentIds.forEach(function (editIdFromConfig) {
            (function(editId) {
                const editForm = document.getElementById('editIncidentForm' + editId);
                if (editForm) {
                    bindClearInvalidOnEdit(editForm);

                    const contact = document.getElementById('edit_complainant_contact_' + editId);
                    if (contact) {
                        contact.addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                            if (this.value.length > 12) {
                                this.value = this.value.slice(0, 12);
                            }
                            if (this.value.startsWith('63') && this.value.length === 12) {
                                this.value = '0' + this.value.slice(2);
                            }
                            if (this.value.length > 0 && !this.value.startsWith('09')) {
                                this.setCustomValidity('Contact number must start with 09');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Contact number must start with 09';
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

                    const incidentDate = document.getElementById('edit_incident_date_' + editId);
                    if (incidentDate) {
                        incidentDate.addEventListener('change', function() {
                            const selectedDate = new Date(this.value);
                            const today = new Date();
                            if (selectedDate > today) {
                                this.setCustomValidity('Incident date cannot be in the future');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Incident date cannot be in the future';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const email = document.getElementById('edit_complainant_email_' + editId);
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

                    const complainantName = document.getElementById('edit_complainant_name_' + editId);
                    if (complainantName) {
                        complainantName.addEventListener('input', function() {
                            const namePattern = /^[A-Za-z\s\-\.',]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens';
                            } else {
                                this.setCustomValidity('');
                                this.classList.remove('is-invalid');
                            }
                        });
                    }

                    const respondentName = document.getElementById('edit_respondent_name_' + editId);
                    if (respondentName) {
                        respondentName.addEventListener('input', function() {
                            const namePattern = /^[A-Za-z\s\-\.',]+$/;
                            if (!namePattern.test(this.value) && this.value.length > 0) {
                                this.setCustomValidity('Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens');
                                this.classList.add('is-invalid');

                                let feedback = this.nextElementSibling;
                                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    this.parentNode.appendChild(feedback);
                                }
                                feedback.textContent = 'Name can only contain letters, spaces, apostrophes, commas, periods, and hyphens';
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

        let editWitnessCount = {};

        function addEditWitnessField(blotterId) {
            if (!editWitnessCount[blotterId]) {
                editWitnessCount[blotterId] = document.querySelectorAll(`#edit-witnesses-container-${blotterId} .witness-card`).length;
            }
            editWitnessCount[blotterId]++;

            const container = document.getElementById(`edit-witnesses-container-${blotterId}`);
            const witnessHtml = `
                <div class="witness-card mb-3" id="edit-witness-${blotterId}-${editWitnessCount[blotterId]}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditWitnessField(${blotterId}, ${editWitnessCount[blotterId]})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="witnesses[${editWitnessCount[blotterId]}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Witness Statement</label>
                            <textarea class="form-control" name="witnesses[${editWitnessCount[blotterId]}][statement]" rows="2" placeholder="What did the witness see/hear?"></textarea>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', witnessHtml);
        }

        function removeEditWitnessField(blotterId, id) {
            const witnessElement = document.getElementById(`edit-witness-${blotterId}-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        var quickEditWitnessCount = window.quickEditWitnessCount || {};
        window.quickEditWitnessCount = quickEditWitnessCount;

        function resolveQuickEditWitnessContainer(blotterId, triggerElement) {
            let container = document.getElementById(`quick-edit-witnesses-container-${blotterId}`)
                || document.getElementById(`edit-witnesses-container-${blotterId}`);

            if (!container && triggerElement) {
                const modal = triggerElement.closest('.modal');
                if (modal) {
                    container = modal.querySelector(`#quick-edit-witnesses-container-${blotterId}`)
                        || modal.querySelector('#quick-edit-witnesses-container-' + blotterId)
                        || modal.querySelector('[id^="quick-edit-witnesses-container-"]')
                        || modal.querySelector('[id^="edit-witnesses-container-"]');
                }
            }

            if (!container) {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    container = activeModal.querySelector('[id^="quick-edit-witnesses-container-"]')
                        || activeModal.querySelector('[id^="edit-witnesses-container-"]');
                }
            }

            return container;
        }

        function addQuickEditWitnessField(blotterId, triggerElement) {
            const witnessCountState = window.quickEditWitnessCount || (window.quickEditWitnessCount = {});
            const container = resolveQuickEditWitnessContainer(blotterId, triggerElement);
            if (!container) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to add witness',
                        text: 'Witness fields container was not found. Please reopen the modal and try again.'
                    });
                }
                return false;
            }

            if (!witnessCountState[blotterId]) {
                witnessCountState[blotterId] = container.querySelectorAll('.witness-card').length;
            }
            witnessCountState[blotterId]++;

            const idx = witnessCountState[blotterId];

            const witnessHtml = `
                <div class="witness-card mb-3" id="quick-edit-witness-${blotterId}-${idx}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-semibold">Witness</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-quick-witness" data-blotter-id="${blotterId}" data-witness-id="${idx}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Witness Name</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][name]" placeholder="Full name" minlength="2" maxlength="255" pattern="[A-Za-z .,'\\-]+" title="Use letters, spaces, apostrophes, commas, periods, and hyphens only.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Witness Contact</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][contact]" placeholder="09XXXXXXXXX" inputmode="numeric" pattern="09[0-9]{9}" minlength="11" maxlength="11" title="Enter 11 digits starting with 09.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Witness Statement</label>
                            <input type="text" class="form-control" name="witnesses[${idx}][statement]" placeholder="What did the witness see/hear?">
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', witnessHtml);
            return true;
        }

        function removeQuickEditWitnessField(blotterId, id) {
            const witnessElement = document.getElementById(`quick-edit-witness-${blotterId}-${id}`);
            if (witnessElement) {
                witnessElement.remove();
            }
        }

        document.addEventListener('click', function(event) {
            const addBtn = event.target.closest('.js-add-quick-witness');
            if (addBtn) {
                event.preventDefault();
                const blotterId = Number(addBtn.getAttribute('data-blotter-id'));
                if (!Number.isNaN(blotterId) && blotterId > 0) {
                    addQuickEditWitnessField(blotterId, addBtn);
                }
                return;
            }

            const removeBtn = event.target.closest('.js-remove-quick-witness');
            if (removeBtn) {
                event.preventDefault();
                const blotterId = Number(removeBtn.getAttribute('data-blotter-id'));
                const witnessId = Number(removeBtn.getAttribute('data-witness-id'));
                if (!Number.isNaN(blotterId) && !Number.isNaN(witnessId) && blotterId > 0 && witnessId >= 0) {
                    removeQuickEditWitnessField(blotterId, witnessId);
                }
            }
        });

        function removeEvidence(evidenceId) {
            Swal.fire({
                title: 'Remove Evidence?',
                text: 'Are you sure you want to remove this evidence?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/evidence/${evidenceId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': __cfg_admin_incidents.csrfToken,
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
                }
            });
        }
