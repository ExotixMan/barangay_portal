const __cfg_admin_admin_clearance_2 = JSON.parse(document.getElementById('js-config-admin-admin-clearance-2')?.dataset.jsConfig || '{}');

        async function viewRemarksHistory(requestType, requestId, referenceNumber) {
            try {
                const params = new URLSearchParams({
                    request_type: requestType,
                    request_id: String(requestId)
                });
                const response = await fetch("" + __cfg_admin_admin_clearance_2.v1 + "?" + params.toString(), {
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
                    alert('No saved remarks found for this request yet.');
                    return;
                }

                const details = history
                    .map(function(item, index) {
                        const channel = item.channel ? item.channel.toUpperCase() : 'N/A';
                        const status = item.status ? item.status.toUpperCase() : 'N/A';
                        return (index + 1) + '. [' + status + '] ' + item.remarks + '\n   by ' + item.admin_name + ' via ' + channel + ' on ' + item.created_at;
                    })
                    .join('\n\n');

                alert('Remarks History - ' + referenceNumber + '\n\n' + details);
            } catch (error) {
                alert(error.message || 'Unable to load remarks history right now.');
            }
        }

        function prepareRemarksAndConfirm(event, form, confirmMessage) {
            const statusInput = form.querySelector('input[name="status"]');
            const remarksInput = form.querySelector('input[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';

            if ((status === 'rejected' || status === 'dropped') && remarksInput) {
                const remarks = prompt('Please enter remarks before sending this notification:');
                if (remarks === null) {
                    event.preventDefault();
                    return false;
                }
                const trimmedRemarks = remarks.trim();
                if (trimmedRemarks === '') {
                    event.preventDefault();
                    alert('Remarks are required for rejected or dropped notifications.');
                    return false;
                }
                if (trimmedRemarks.length > 40) {
                    event.preventDefault();
                    alert('Remarks must not exceed 40 characters.');
                    return false;
                }
                remarksInput.value = trimmedRemarks;
            }

            if (!confirm(confirmMessage)) {
                event.preventDefault();
                return false;
            }

            return true;
        }

        function setViewFooter(appId, tab) {
            ['Details', 'Edit', 'History'].forEach(function(label) {
                const el = document.getElementById('viewFooter' + label + appId);
                if (el) el.classList.add('d-none');
            });

            const map = {
                details: 'viewFooterDetails' + appId,
                edit: 'viewFooterEdit' + appId,
                history: 'viewFooterHistory' + appId
            };
            const target = document.getElementById(map[tab] || map.details);
            if (target) target.classList.remove('d-none');
        }

        function renderHistoryList(container, payload) {
            if (!container) return;
            const timeline = Array.isArray(payload.timeline) ? payload.timeline : [];
            if (timeline.length === 0) {
                container.innerHTML = '<div class="text-muted">No activity found for this application yet.</div>';
                return;
            }
            container.innerHTML = timeline.map(function(item) {
                const title = (item.title || item.label || 'Activity').toString();
                const details = item.details ? '<div class="text-muted mt-1">' + item.details + '</div>' : (item.description ? '<div class="text-muted mt-1">' + item.description + '</div>' : '');
                const actor = item.actor ? '<div class="small text-muted">' + item.actor + ' - ' + (item.created_at || item.date || '') + '</div>' : '<div class="small text-muted">' + (item.created_at || item.date || '') + '</div>';
                return '<div class="activity-item py-2 border-bottom">' +
                    '<div class="fw-semibold">' + title + '</div>' +
                    actor +
                    details +
                    '</div>';
            }).join('');
        }

        async function loadHistoryForClearance(appId, referenceNumber) {
            const container = document.getElementById('historyList' + appId);
            if (!container) return;

            container.innerHTML = 'Loading history...';
            try {
                const params = new URLSearchParams({
                    request_type: 'clearance',
                    request_id: String(appId)
                });

                const response = await fetch("" + __cfg_admin_admin_clearance_2.v1 + "?" + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Failed to load history.');
                const payload = await response.json();
                renderHistoryList(container, payload || {});
            } catch (error) {
                container.innerHTML = '<div class="text-danger">' + (error.message || 'Unable to load history.') + '</div>';
            }
        }

        function handleUpdateStatusSubmit(event, form) {
            const statusInput = form.querySelector('.selected-status-input');
            const remarksInput = form.querySelector('textarea[name="remarks"]');
            const status = statusInput ? String(statusInput.value || '').toLowerCase() : '';
            const remarks = remarksInput ? String(remarksInput.value || '').trim() : '';

            if (remarks.length > 40) {
                event.preventDefault();
                alert('Remarks must not exceed 40 characters.');
                return false;
            }

            if (status === 'rejected' && remarks.length === 0) {
                event.preventDefault();
                alert('Remarks are required when status is Rejected.');
                return false;
            }
            return true;
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

        document.addEventListener('DOMContentLoaded', function() {
            function setQuickEditValidity(field, message) {
                if (!field) {
                    return;
                }

                let feedback = field.parentNode ? field.parentNode.querySelector('.invalid-feedback.quick-edit-feedback') : null;
                field.setCustomValidity(message || '');

                if (message) {
                    field.classList.add('is-invalid');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback quick-edit-feedback';
                        field.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = message;
                } else {
                    field.classList.remove('is-invalid');
                    if (feedback) {
                        feedback.remove();
                    }
                }
            }

            function attachClearanceQuickEditValidation(form) {
                const firstName = form.querySelector('input[name="first_name"]');
                const middleName = form.querySelector('input[name="middle_name"]');
                const lastName = form.querySelector('input[name="last_name"]');
                const birthdate = form.querySelector('input[name="birthdate"]');
                const email = form.querySelector('input[name="email"]');
                const contact = form.querySelector('input[name="contact_number"]');
                const address = form.querySelector('textarea[name="address"]');
                const purpose = form.querySelector('select[name="purpose"]');
                const purposeOtherWrap = form.querySelector('.quick-purpose-other-field');
                const purposeOther = form.querySelector('input[name="purpose_other"]');
                const fee = form.querySelector('input[name="fee"]');
                const files = form.querySelectorAll('input[type="file"]');

                const namePattern = /^[A-Za-z][A-Za-z .'-]*$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const allowedMime = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                const allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

                function validateName(field, label, required) {
                    if (!field) {
                        return true;
                    }

                    const value = field.value.trim();

                    if (!value) {
                        if (required) {
                            setQuickEditValidity(field, label + ' is required.');
                            return false;
                        }

                        setQuickEditValidity(field, '');
                        return true;
                    }

                    if (value.length < 2 || value.length > 255 || !namePattern.test(value)) {
                        setQuickEditValidity(field, label + ' can only contain letters, spaces, apostrophes, periods, and hyphens.');
                        return false;
                    }

                    setQuickEditValidity(field, '');
                    return true;
                }

                function validateBirthdate() {
                    if (!birthdate) {
                        return true;
                    }

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
                    if (!email) {
                        return true;
                    }

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
                    if (!contact) {
                        return true;
                    }

                    contact.value = contact.value.replace(/\D/g, '').slice(0, 11);

                    if (!contact.value) {
                        setQuickEditValidity(contact, 'Contact number is required.');
                        return false;
                    }

                    if (!/^09\d{9}$/.test(contact.value)) {
                        setQuickEditValidity(contact, 'Contact number must start with 09 and contain exactly 11 digits.');
                        return false;
                    }

                    setQuickEditValidity(contact, '');
                    return true;
                }

                function validateAddress() {
                    if (!address) {
                        return true;
                    }

                    const value = address.value.trim();

                    if (!value) {
                        setQuickEditValidity(address, 'Address is required.');
                        return false;
                    }

                    if (value.length < 8 || value.length > 500) {
                        setQuickEditValidity(address, 'Address must be between 8 and 500 characters.');
                        return false;
                    }

                    setQuickEditValidity(address, '');
                    return true;
                }

                function validatePurposeOther() {
                    if (!purpose || !purposeOther) {
                        return true;
                    }

                    const isOther = purpose.value === 'other';
                    const value = purposeOther.value.trim();

                    if (!isOther) {
                        setQuickEditValidity(purposeOther, '');
                        return true;
                    }

                    if (!value) {
                        setQuickEditValidity(purposeOther, 'Purpose (Other) is required.');
                        return false;
                    }

                    if (value.length > 255) {
                        setQuickEditValidity(purposeOther, 'Purpose (Other) must not exceed 255 characters.');
                        return false;
                    }

                    setQuickEditValidity(purposeOther, '');
                    return true;
                }

                function validateFee() {
                    if (!fee) {
                        return true;
                    }

                    const value = fee.value.trim();

                    if (!value) {
                        setQuickEditValidity(fee, '');
                        return true;
                    }

                    if (!/^(?:0|[1-9]\d{0,4})(?:\.\d{1,2})?$/.test(value)) {
                        setQuickEditValidity(fee, 'Fee must be a plain number with up to 2 decimals.');
                        return false;
                    }

                    setQuickEditValidity(fee, '');
                    return true;
                }

                function validateFile(fileInput) {
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

                function togglePurposeOther() {
                    if (!purpose || !purposeOtherWrap || !purposeOther) {
                        return;
                    }

                    const isOther = purpose.value === 'other';
                    purposeOtherWrap.classList.toggle('d-none', !isOther);
                    purposeOther.required = isOther;
                    if (!isOther) {
                        purposeOther.value = '';
                        setQuickEditValidity(purposeOther, '');
                    }
                }

                firstName?.addEventListener('input', function() { validateName(firstName, 'First name', true); });
                middleName?.addEventListener('input', function() { validateName(middleName, 'Middle name', false); });
                lastName?.addEventListener('input', function() { validateName(lastName, 'Last name', true); });
                birthdate?.addEventListener('change', validateBirthdate);
                email?.addEventListener('input', validateEmail);
                contact?.addEventListener('input', validateContact);
                address?.addEventListener('input', validateAddress);
                purpose?.addEventListener('change', function() {
                    togglePurposeOther();
                    validatePurposeOther();
                });
                purposeOther?.addEventListener('input', validatePurposeOther);
                fee?.addEventListener('input', validateFee);
                files.forEach(function(fileInput) {
                    fileInput.addEventListener('change', function() {
                        validateFile(fileInput);
                    });
                });

                togglePurposeOther();

                form.addEventListener('submit', function(event) {
                    const isValid = [
                        validateName(firstName, 'First name', true),
                        validateName(middleName, 'Middle name', false),
                        validateName(lastName, 'Last name', true),
                        validateBirthdate(),
                        validateEmail(),
                        validateContact(),
                        validateAddress(),
                        validatePurposeOther(),
                        validateFee(),
                        ...Array.from(files).map(validateFile)
                    ].every(Boolean);

                    if (!isValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            }

            document.querySelectorAll('form[id^="quickEditForm"]').forEach(function(form) {
                attachClearanceQuickEditValidation(form);
            });

            document.querySelectorAll('[id^="viewModal"]').forEach(function(modalEl) {
                modalEl.addEventListener('shown.bs.modal', function() {
                    const appId = modalEl.id.replace('viewModal', '');
                    setViewFooter(appId, 'details');
                });
            });

            document.querySelectorAll('[data-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const appId = button.getAttribute('data-app-id');
                    const targetTab = button.getAttribute('data-switch-tab');
                    const tabTrigger = document.querySelector('#' + targetTab + '-tab-' + appId);
                    if (tabTrigger) {
                        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                    }
                });
            });

            document.querySelectorAll('[id^="details-tab-"], [id^="edit-tab-"], [id^="history-tab-"]').forEach(function(tabBtn) {
                tabBtn.addEventListener('shown.bs.tab', function(event) {
                    const id = event.target.id;
                    const appId = id.split('-').pop();
                    if (id.indexOf('details-tab-') === 0) setViewFooter(appId, 'details');
                    if (id.indexOf('edit-tab-') === 0) setViewFooter(appId, 'edit');
                    if (id.indexOf('history-tab-') === 0) {
                        setViewFooter(appId, 'history');
                        loadHistoryForClearance(appId, '');
                    }
                });
            });

            document.querySelectorAll('.status-grid').forEach(function(grid) {
                const input = grid.closest('form').querySelector('.selected-status-input');
                const form = grid.closest('form');
                const current = (grid.getAttribute('data-current-status') || '').toLowerCase();

                const activate = function(value) {
                    grid.querySelectorAll('.status-option').forEach(function(option) {
                        option.classList.toggle('active', option.getAttribute('data-status') === value);
                    });
                    if (input) input.value = value;
                    if (form) toggleNotifyOptions(form, value);
                };

                if (current) activate(current);

                grid.querySelectorAll('.status-option').forEach(function(option) {
                    option.addEventListener('click', function() {
                        activate(option.getAttribute('data-status'));
                    });
                });
            });

            var tableResponsive = document.querySelector('.table-responsive');
            if (!tableResponsive) return;

            tableResponsive.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(toggle) {
                new bootstrap.Dropdown(toggle, {
                    popperConfig: function(defaultConfig) {
                        defaultConfig.strategy = 'fixed';
                        return defaultConfig;
                    }
                });
            });
        });
