const __cfg_admin_residents = JSON.parse(document.getElementById('js-config-admin-residents-main')?.dataset.jsConfig || '{}');

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.resident-checkbox:checked:not(:disabled)');
            const bulkForm = document.getElementById('bulkForm');
            if (checkboxes.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one resident to deactivate.', confirmButtonColor: '#d33' });
                return;
            }
            Swal.fire({
                title: 'Confirm Bulk Deactivate',
                text: `Are you sure you want to deactivate ${checkboxes.length} selected resident(s)?`,
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, Deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                        bulkForm.appendChild(input);
                    });
                    bulkForm.submit();
                }
            });
        }

        function exportCSV() {
            const checkboxes = document.querySelectorAll('.resident-checkbox:checked');
            const exportForm = document.getElementById('exportForm');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'question', title: 'Export All?', text: 'No residents selected. Export all records?',
                    showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, Export All'
                }).then((result) => { if (result.isConfirmed) exportForm.submit(); });
                return;
            }
            Swal.fire({
                title: 'Export Selected?', text: `Export ${checkboxes.length} selected resident(s)?`, icon: 'question',
                showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, Export'
            }).then((result) => {
                if (result.isConfirmed) {
                    checkboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                        exportForm.appendChild(input);
                    });
                    exportForm.submit();
                }
            });
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.resident-checkbox:not(:disabled)');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                checkbox.closest('tr')?.classList.toggle('is-selected', checkbox.checked);
            });
            selectAllCheckbox.indeterminate = false;
        }

        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (typeof Swal === 'undefined') {
                const ok = confirm(message || 'Are you sure?');
                if (ok) form.submit();
                return false;
            }
            Swal.fire({
                title: 'Are you sure?', text: message || 'You won\'t be able to revert this!', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, proceed!'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
            return false;
        }

        function confirmApproveId(event, residentName) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (typeof Swal === 'undefined') {
                if (confirm('Approve and verify the valid ID for ' + residentName + '?')) form.submit();
                return false;
            }
            Swal.fire({
                title: 'Approve this ID?',
                text: `Verify the valid ID for ${residentName}? This can't be undone.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Approve'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
            return false;
        }

        function confirmRejectId(event, residentName) {
            event.preventDefault();
            const form = event.target.closest('form');
            const reasonInput = form.querySelector('input[name="valid_id_rejection_reason"]');

            const parentModal = form.closest('.modal.show') || form.closest('.modal');

            if (typeof Swal === 'undefined') {
                const reason = prompt('Enter rejection remarks for ' + residentName + ':');
                if (reason && reason.trim().length > 0) {
                    reasonInput.value = reason.trim();
                    form.submit();
                }
                return false;
            }

            Swal.fire({
                title: 'Reject this ID?',
                html: `Provide remarks for rejecting the valid ID of <strong>${residentName}</strong>:`,
                input: 'textarea',
                inputPlaceholder: 'Example: Back ID is blurry, photo does not match, expired ID, or details are unreadable.',
                inputAttributes: { 'aria-label': 'Rejection remarks' },
                target: parentModal || document.body,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirm Reject',
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Rejection remarks are required.';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    reasonInput.value = result.value.trim();
                    form.submit();
                }
            });
            return false;
        }

        window.confirmApproveClassification = function(event, residentName, classificationLabel) {
            event.preventDefault();

            const form = event.target.closest('form');
            if (!form) return false;

            const label = classificationLabel || 'classification';
            const name = residentName || 'this resident';

            if (typeof Swal === 'undefined') {
                if (window.confirm(`Approve and verify the ${label} classification for ${name}?`)) {
                    form.submit();
                }
                return false;
            }

            Swal.fire({
                title: `Approve ${label}?`,
                html: `Verify <strong>${name}</strong> as <strong>${label}</strong> after reviewing the submitted evidence?`,
                icon: 'question',
                target: document.body,
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Approve ${label}`,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                heightAuto: false,
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        };

        window.confirmRejectClassification = function(event, residentName, classificationLabel) {
            event.preventDefault();

            const form = event.target.closest('form');
            if (!form) return false;

            const reasonInput = form.querySelector('input[name="remarks"]');
            const label = classificationLabel || 'classification';
            const name = residentName || 'this resident';

            if (!reasonInput) {
                console.error('Classification rejection remarks input was not found.');
                alert('Unable to open rejection remarks. Please refresh the page and try again.');
                return false;
            }

            if (typeof Swal === 'undefined') {
                const reason = window.prompt(
                    `Enter rejection remarks for ${name}'s ${label} evidence:`
                );

                if (reason === null) {
                    return false;
                }

                const remarks = reason.trim();

                if (!remarks) {
                    alert('Rejection remarks are required.');
                    return false;
                }

                if (remarks.length > 500) {
                    alert('Rejection remarks must not exceed 500 characters.');
                    return false;
                }

                reasonInput.value = remarks;
                form.submit();
                return false;
            }

            Swal.fire({
                title: `Reject ${label} evidence?`,
                html: `
                    <div style="text-align:left;">
                        <div style="margin-bottom:8px;">
                            You are rejecting the <strong>${label}</strong> evidence of
                            <strong>${name}</strong>.
                        </div>
                        <div style="font-size:.84rem;color:#667085;">
                            Enter a clear reason. The resident will see these remarks
                            and can use them when submitting corrected evidence.
                        </div>
                    </div>
                `,
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Rejection remarks',
                inputPlaceholder: label === 'PWD'
                    ? 'Example: PWD ID back is unreadable or does not match the front.'
                    : 'Example: School ID is expired or does not show current enrollment.',
                inputAttributes: {
                    'aria-label': `${label} rejection remarks`,
                    'maxlength': '500',
                    'rows': '5',
                    'autocomplete': 'off',
                },
                target: document.body,
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Reject ${label}`,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusConfirm: false,
                heightAuto: false,
                returnFocus: false,
                customClass: {
                    input: 'classification-reject-remarks-input',
                },
                inputValidator: (value) => {
                    const remarks = String(value || '').trim();

                    if (!remarks) {
                        return 'Rejection remarks are required.';
                    }

                    if (remarks.length > 500) {
                        return 'Rejection remarks must not exceed 500 characters.';
                    }

                    return undefined;
                },
                didOpen: () => {
                    const input = Swal.getInput();
                    if (input) {
                        input.focus();
                    }
                },
            }).then((result) => {
                if (!result.isConfirmed) return;

                const remarks = String(result.value || '').trim();

                if (!remarks) {
                    return;
                }

                reasonInput.value = remarks;

                // form.submit() intentionally bypasses the data-submit handler so
                // the confirmation dialog does not open a second time.
                form.submit();
            });

            return false;
        };

        function switchResidentViewTab(residentId, tabName) {
            const tabButton = document.getElementById('r-' + tabName + '-tab-' + residentId);
            if (!tabButton) return;
            new bootstrap.Tab(tabButton).show();
        }

        function toggleResidentViewFooter(residentId, activeTab) {
            ['Details', 'Edit', 'Corrections', 'History'].forEach(function(name) {
                const el = document.getElementById('r-viewFooter' + name + residentId);
                if (el) el.classList.add('d-none');
            });
            const map = { details: 'Details', edit: 'Edit', corrections: 'Corrections', history: 'History' };
            const target = document.getElementById('r-viewFooter' + (map[activeTab] || 'Details') + residentId);
            if (target) target.classList.remove('d-none');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal === 'undefined') {
                console.log('SweetAlert2 not loaded, using default confirm');
            }

            const checkboxes = document.querySelectorAll('.resident-checkbox:not(:disabled)');
            const selectAllCheckbox = document.getElementById('selectAll');
            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        this.closest('tr')?.classList.toggle('is-selected', this.checked);
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                    checkbox.closest('tr')?.classList.toggle('is-selected', checkbox.checked);
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
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            if (__cfg_admin_residents.errorFormType === 'add') {
                const addModal = new bootstrap.Modal(document.getElementById('addResidentModal'));
                addModal.show();
            } else if (__cfg_admin_residents.errorFormType?.startsWith('edit_')) {
                const editId = __cfg_admin_residents.errorFormType.replace('edit_', '');
                const editModal = document.getElementById('viewResidentModal' + editId);
                if (editModal) {
                    const editViewModal = bootstrap.Modal.getOrCreateInstance(editModal);
                    editViewModal.show();
                    switchResidentViewTab(editId, 'edit');
                    toggleResidentViewFooter(editId, 'edit');
                }
            }

            document.querySelectorAll('[id^="viewResidentModal"]').forEach(function(modalEl) {
                const residentId = modalEl.id.replace('viewResidentModal', '');

                modalEl.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tabBtn) {
                    tabBtn.addEventListener('shown.bs.tab', function() {
                        if (tabBtn.id.indexOf('r-details-tab-') === 0) toggleResidentViewFooter(residentId, 'details');
                        else if (tabBtn.id.indexOf('r-edit-tab-') === 0) toggleResidentViewFooter(residentId, 'edit');
                        else if (tabBtn.id.indexOf('r-corrections-tab-') === 0) toggleResidentViewFooter(residentId, 'corrections');
                        else toggleResidentViewFooter(residentId, 'history');
                    });
                });

                modalEl.addEventListener('show.bs.modal', function(ev) {
                    const trigger = ev.relatedTarget;
                    const requestedTab = trigger ? trigger.getAttribute('data-view-tab') : null;
                    if (requestedTab) {
                        switchResidentViewTab(residentId, requestedTab);
                        toggleResidentViewFooter(residentId, requestedTab);
                    } else {
                        switchResidentViewTab(residentId, 'details');
                        toggleResidentViewFooter(residentId, 'details');
                    }
                });
            });

            document.querySelectorAll('[data-resident-switch-tab]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const residentId = button.getAttribute('data-resident-id');
                    const tabName = button.getAttribute('data-resident-switch-tab');
                    switchResidentViewTab(residentId, tabName);
                });
            });

            document.querySelectorAll('.correction-resolve-form').forEach(function(form) {
                const select = form.querySelector('select[name="resolution_status"]');
                const remarks = form.querySelector('textarea[name="remarks"]');
                if (select && remarks) {
                    select.addEventListener('change', function() {
                        remarks.placeholder = this.value === 'rejected'
                            ? 'Explain why this correction request was not approved.'
                            : 'Explain what was corrected on the resident profile.';
                    });
                }
            });

            document.querySelectorAll('[data-correction-target]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const details = document.getElementById(button.getAttribute('data-correction-target'));
                    if (!details) return;

                    const isOpen = details.classList.toggle('is-open');
                    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addForm = document.getElementById('addResidentForm');
            if (!addForm) return;

            const namePattern     = /^[a-zA-Z\s\-\.]+$/;
            const usernamePattern = /^[a-zA-Z0-9_]+$/;
            const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function calculateAge(birthDateValue) {
                if (!birthDateValue) return null;
                const birthDate = new Date(birthDateValue);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
                return age;
            }

            function setField(el, msg) {
                if (msg) {
                    el.setCustomValidity(msg);
                    el.classList.add('is-invalid');
                    let fb = el.parentNode.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.parentNode.appendChild(fb); }
                    fb.textContent = msg;
                } else {
                    el.setCustomValidity('');
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }
            }

            addForm.querySelectorAll('.is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                }, { once: true });
            });

            const fn = document.getElementById('add_firstname');
            if (fn) fn.addEventListener('input', function() {
                if (!this.value.trim()) return setField(this, 'First name is required.');
                if (!namePattern.test(this.value)) return setField(this, 'First name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2) return setField(this, 'First name must be at least 2 characters.');
                setField(this, '');
            });

            const ln = document.getElementById('add_lastname');
            if (ln) ln.addEventListener('input', function() {
                if (!this.value.trim()) return setField(this, 'Last name is required.');
                if (!namePattern.test(this.value)) return setField(this, 'Last name can only contain letters, spaces, and hyphens.');
                if (this.value.trim().length < 2) return setField(this, 'Last name must be at least 2 characters.');
                setField(this, '');
            });

            const un = document.getElementById('add_username');
            if (un) un.addEventListener('input', function() {
                if (!this.value) return setField(this, 'Username is required.');
                if (!usernamePattern.test(this.value)) return setField(this, 'Username can only contain letters, numbers, and underscores.');
                if (this.value.length < 3) return setField(this, 'Username must be at least 3 characters.');
                setField(this, '');
            });

            const em = document.getElementById('add_email');
            if (em) em.addEventListener('input', function() {
                if (!this.value) return setField(this, 'Email is required.');
                if (!emailPattern.test(this.value)) return setField(this, 'Please enter a valid email address.');
                setField(this, '');
            });

            const contact = document.getElementById('add_contact');
            if (contact) contact.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                if (!this.value) return setField(this, 'Contact number is required.');
                if (!this.value.startsWith('09')) return setField(this, 'Contact number must start with 09.');
                if (this.value.length !== 11) return setField(this, 'Contact number must be exactly 11 digits.');
                setField(this, '');
            });

            ['add_valid_id', 'add_valid_id_back'].forEach(function(id) {
                const input = document.getElementById(id);
                if (!input) return;

                input.addEventListener('change', function() {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) return setField(this, 'Please select a valid ID file.');

                    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        this.value = '';
                        return setField(this, 'Valid ID must be JPG, JPEG, PNG, or PDF.');
                    }

                    if (file.size > 5 * 1024 * 1024) {
                        this.value = '';
                        return setField(this, 'Valid ID file must not exceed 5 MB.');
                    }

                    setField(this, '');
                });
            });


            const declareStudent = document.getElementById('add_declare_student');
            const declarePwd = document.getElementById('add_declare_pwd');
            const studentPanel = document.getElementById('addStudentProofPanel');
            const pwdPanel = document.getElementById('addPwdProofPanel');
            const studentProof = document.getElementById('add_student_proof');
            const pwdFront = document.getElementById('add_pwd_id_front');
            const pwdBack = document.getElementById('add_pwd_id_back');
            const validIdFront = document.getElementById('add_valid_id');
            const validIdBack = document.getElementById('add_valid_id_back');

            function syncClassificationFields() {
                const studentOn = !!declareStudent?.checked;
                const pwdOn = !!declarePwd?.checked;

                studentPanel?.classList.toggle('d-none', !studentOn);
                pwdPanel?.classList.toggle('d-none', !pwdOn);

                if (studentProof) studentProof.required = studentOn;
                if (pwdFront) pwdFront.required = pwdOn;
                if (pwdBack) pwdBack.required = pwdOn;

                if (!studentOn && studentProof) {
                    studentProof.value = '';
                    setField(studentProof, '');
                }

                if (!pwdOn) {
                    if (pwdFront) {
                        pwdFront.value = '';
                        setField(pwdFront, '');
                    }
                    if (pwdBack) {
                        pwdBack.value = '';
                        setField(pwdBack, '');
                    }
                }
            }

            declareStudent?.addEventListener('change', syncClassificationFields);
            declarePwd?.addEventListener('change', syncClassificationFields);
            syncClassificationFields();

            function validateClassificationFile(input, label) {
                const file = input?.files?.[0] || null;

                if (!file) {
                    if (input?.required) setField(input, `${label} is required.`);
                    return false;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    input.value = '';
                    setField(input, `${label} must be JPG, JPEG, PNG, or PDF.`);
                    return false;
                }

                if (file.size > 5 * 1024 * 1024) {
                    input.value = '';
                    setField(input, `${label} must not exceed 5 MB.`);
                    return false;
                }

                setField(input, '');
                return true;
            }

            studentProof?.addEventListener('change', function() {
                validateClassificationFile(this, 'Student proof');
            });

            pwdFront?.addEventListener('change', async function() {
                if (validateClassificationFile(this, 'PWD ID front')) {
                    await validateDistinctPair(pwdFront, pwdBack, 'PWD ID');
                }
            });

            pwdBack?.addEventListener('change', async function() {
                if (validateClassificationFile(this, 'PWD ID back')) {
                    await validateDistinctPair(pwdFront, pwdBack, 'PWD ID');
                }
            });

            async function imageFingerprint(file) {
                if (!file || !file.type.startsWith('image/')) return null;

                const bitmap = await createImageBitmap(file);
                const canvas = document.createElement('canvas');
                canvas.width = 16;
                canvas.height = 16;
                const ctx = canvas.getContext('2d', { willReadFrequently: true });
                ctx.drawImage(bitmap, 0, 0, 16, 16);
                bitmap.close?.();

                const data = ctx.getImageData(0, 0, 16, 16).data;
                const values = [];

                for (let i = 0; i < data.length; i += 4) {
                    values.push((data[i] * .299) + (data[i + 1] * .587) + (data[i + 2] * .114));
                }

                const average = values.reduce((sum, value) => sum + value, 0) / values.length;
                return values.map(value => value >= average ? 1 : 0);
            }

            function hashDistance(a, b) {
                if (!a || !b || a.length !== b.length) return 1;
                let diff = 0;

                for (let i = 0; i < a.length; i++) {
                    if (a[i] !== b[i]) diff++;
                }

                return diff / a.length;
            }

            async function filesLookSame(frontFile, backFile) {
                if (!frontFile || !backFile) return false;

                if (
                    frontFile.name === backFile.name &&
                    frontFile.size === backFile.size &&
                    frontFile.type === backFile.type
                ) {
                    return true;
                }

                if (!frontFile.type.startsWith('image/') || !backFile.type.startsWith('image/')) {
                    return false;
                }

                try {
                    const [a, b] = await Promise.all([
                        imageFingerprint(frontFile),
                        imageFingerprint(backFile),
                    ]);

                    return hashDistance(a, b) <= 0.05;
                } catch (error) {
                    console.warn('Could not compare resident proof images in browser:', error);
                    return false;
                }
            }

            async function validateDistinctPair(frontInput, backInput, label) {
                const frontFile = frontInput?.files?.[0] || null;
                const backFile = backInput?.files?.[0] || null;

                if (!frontFile || !backFile) return true;

                if (await filesLookSame(frontFile, backFile)) {
                    backInput.value = '';
                    setField(backInput, `${label} front and back appear to be the same picture. Please provide the actual back side.`);
                    backInput.focus();
                    return false;
                }

                setField(backInput, '');
                return true;
            }

            // The Admin Add Resident modal uses the same front/back protection as public registration/profile.
            validIdFront?.addEventListener('change', async function() {
                if (this.files?.length && validIdBack?.files?.length) {
                    await validateDistinctPair(validIdFront, validIdBack, 'Valid ID');
                }
            });

            validIdBack?.addEventListener('change', async function() {
                if (this.files?.length && validIdFront?.files?.length) {
                    await validateDistinctPair(validIdFront, validIdBack, 'Valid ID');
                }
            });

            addForm.addEventListener('submit', function(event) {
                if (declareStudent?.checked && !studentProof?.files?.length) {
                    event.preventDefault();
                    setField(studentProof, 'Student proof is required when Student is selected.');
                    studentProof?.focus();
                    return;
                }

                if (declarePwd?.checked && (!pwdFront?.files?.length || !pwdBack?.files?.length)) {
                    event.preventDefault();

                    if (!pwdFront?.files?.length) setField(pwdFront, 'PWD ID front is required.');
                    if (!pwdBack?.files?.length) setField(pwdBack, 'PWD ID back is required.');

                    (!pwdFront?.files?.length ? pwdFront : pwdBack)?.focus();
                    return;
                }

                const frontFile = validIdFront?.files?.[0] || null;
                const backFile = validIdBack?.files?.[0] || null;

                if (
                    frontFile && backFile &&
                    frontFile.name === backFile.name &&
                    frontFile.size === backFile.size &&
                    frontFile.type === backFile.type
                ) {
                    event.preventDefault();
                    setField(validIdBack, 'Valid ID front and back cannot be the same picture.');
                    validIdBack.focus();
                    return;
                }

                const pwdFrontFile = pwdFront?.files?.[0] || null;
                const pwdBackFile = pwdBack?.files?.[0] || null;

                if (
                    declarePwd?.checked &&
                    pwdFrontFile && pwdBackFile &&
                    pwdFrontFile.name === pwdBackFile.name &&
                    pwdFrontFile.size === pwdBackFile.size &&
                    pwdFrontFile.type === pwdBackFile.type
                ) {
                    event.preventDefault();
                    setField(pwdBack, 'PWD ID front and back cannot be the same picture.');
                    pwdBack.focus();
                }
            });

            const bd = document.getElementById('add_birthdate');
            if (bd) bd.addEventListener('change', function() {
                const sel = new Date(this.value);
                const today = new Date(); today.setHours(0,0,0,0);
                const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                const age = calculateAge(this.value);
                if (sel >= today) return setField(this, 'Birthdate must be in the past.');
                if (sel < minDate) return setField(this, 'Please enter a valid birthdate.');
                if (age === null || age < 0) return setField(this, 'Please enter a valid birthdate.');
                setField(this, '');
            });

            const pw  = document.getElementById('add_password');
            const pwc = document.getElementById('add_password_confirmation');

            function validatePw() {
                pw.value = pw.value.replace(/\s/g, "");
                if (!pw.value) return setField(pw, 'Password is required.');
                if (pw.value.length < 8) return setField(pw, 'Password must be at least 8 characters.');
                if (!/[A-Z]/.test(pw.value)) return setField(pw, 'Password must contain at least one uppercase letter.');
                if (!/[a-z]/.test(pw.value)) return setField(pw, 'Password must contain at least one lowercase letter.');
                if (!/[0-9]/.test(pw.value)) return setField(pw, 'Password must contain at least one number.');
                if (!/[^A-Za-z0-9]/.test(pw.value)) return setField(pw, 'Password must contain at least one special character.');
                setField(pw, '');
                if (pwc.value) validatePwc();
            }
            function validatePwc() {
                pwc.value = pwc.value.replace(/\s/g, "");
                if (pwc.value !== pw.value) return setField(pwc, 'Passwords do not match.');
                setField(pwc, '');
            }
            if (pw)  pw.addEventListener('input', validatePw);
            if (pwc) pwc.addEventListener('input', validatePwc);

            const toggleAddPasswords = document.getElementById('toggle_add_passwords');
            if (toggleAddPasswords && pw && pwc) {
                toggleAddPasswords.addEventListener('change', function() {
                    const inputType = this.checked ? 'text' : 'password';
                    pw.type = inputType;
                    pwc.type = inputType;
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[id^="quickEditResidentForm"]').forEach(function(form) {
                const status = form.querySelector('select[name="valid_id_status"]');
                const remarks = form.querySelector('textarea[name="valid_id_rejection_reason"]');

                if (!status || !remarks) return;

                const feedback = remarks.parentNode.querySelector('[data-valid-id-remarks-feedback]')
                    || remarks.parentNode.querySelector('.invalid-feedback');
                const requiredMarker = remarks
                    .closest('.edit-field')
                    ?.querySelector('[data-valid-id-remarks-required]');

                function setRemarksError(message) {
                    if (message) {
                        remarks.setCustomValidity(message);
                        remarks.classList.add('is-invalid');
                        if (feedback) feedback.textContent = message;
                    } else {
                        remarks.setCustomValidity('');
                        remarks.classList.remove('is-invalid');
                        if (feedback) feedback.textContent = '';
                    }
                }

                function syncQuickEditValidIdRemarks(showError = false) {
                    const isRejected = status.value === 'rejected';
                    remarks.required = isRejected;
                    remarks.setAttribute('aria-required', isRejected ? 'true' : 'false');

                    if (requiredMarker) {
                        requiredMarker.classList.toggle('d-none', !isRejected);
                    }

                    remarks.placeholder = isRejected
                        ? 'Required: explain why the valid ID is rejected.'
                        : 'Required only when rejecting.';

                    if (!isRejected) {
                        setRemarksError('');
                        return true;
                    }

                    if (!remarks.value.trim()) {
                        if (showError) {
                            setRemarksError('Remarks are required when rejecting a valid ID.');
                        } else {
                            remarks.setCustomValidity('Remarks are required when rejecting a valid ID.');
                        }
                        return false;
                    }

                    setRemarksError('');
                    return true;
                }

                status.addEventListener('change', function() {
                    syncQuickEditValidIdRemarks(status.value === 'rejected');
                    if (status.value === 'rejected' && !remarks.value.trim()) {
                        remarks.focus();
                    }
                });

                remarks.addEventListener('input', function() {
                    syncQuickEditValidIdRemarks(status.value === 'rejected' && !remarks.value.trim());
                });

                form.addEventListener('submit', function(event) {
                    if (!syncQuickEditValidIdRemarks(true)) {
                        event.preventDefault();
                        event.stopPropagation();
                        remarks.focus();
                        remarks.reportValidity();
                    }
                });

                syncQuickEditValidIdRemarks(
                    status.value === 'rejected' && remarks.classList.contains('is-invalid')
                );
            });
        });

        if (__cfg_admin_residents.canUpdate) {
            __cfg_admin_residents.residentIds.forEach(function (residentIdFromConfig) {
            (function(id) {
                const form = document.getElementById('editResidentForm' + id);
                if (!form) return;

                function sf(el, msg) {
                    if (!el) return;
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (msg) {
                        el.setCustomValidity(msg);
                        el.classList.add('is-invalid');
                        if (fb) fb.textContent = msg;
                    } else {
                        el.setCustomValidity('');
                        el.classList.remove('is-invalid');
                        if (fb) fb.textContent = '';
                    }
                }

                form.querySelectorAll('.is-invalid').forEach(function(el) {
                    el.addEventListener('input', function() {
                        el.classList.remove('is-invalid');
                        const fb = el.parentNode.querySelector('.invalid-feedback');
                        if (fb) fb.textContent = '';
                    }, { once: true });
                });

                const namePattern     = /^[a-zA-Z\s\-\.]+$/;
                const usernamePattern = /^[a-zA-Z0-9_]+$/;
                const emailPattern    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const fn = document.getElementById('edit_firstname_' + id);
                if (fn) fn.addEventListener('input', function() {
                    if (!this.value.trim()) return sf(this, 'First name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'First name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2) return sf(this, 'First name must be at least 2 characters.');
                    sf(this, '');
                });

                const ln = document.getElementById('edit_lastname_' + id);
                if (ln) ln.addEventListener('input', function() {
                    if (!this.value.trim()) return sf(this, 'Last name is required.');
                    if (!namePattern.test(this.value)) return sf(this, 'Last name can only contain letters, spaces, and hyphens.');
                    if (this.value.trim().length < 2) return sf(this, 'Last name must be at least 2 characters.');
                    sf(this, '');
                });

                const un = document.getElementById('edit_username_' + id);
                if (un) un.addEventListener('input', function() {
                    if (!this.value) return sf(this, 'Username is required.');
                    if (!usernamePattern.test(this.value)) return sf(this, 'Username can only contain letters, numbers, and underscores.');
                    if (this.value.length < 3) return sf(this, 'Username must be at least 3 characters.');
                    sf(this, '');
                });

                const em = document.getElementById('edit_email_' + id);
                if (em) em.addEventListener('input', function() {
                    if (!this.value) return sf(this, 'Email is required.');
                    if (!emailPattern.test(this.value)) return sf(this, 'Please enter a valid email address.');
                    sf(this, '');
                });

                const ct = document.getElementById('edit_contact_' + id);
                if (ct) ct.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (!this.value) return sf(this, 'Contact number is required.');
                    if (!this.value.startsWith('09')) return sf(this, 'Contact number must start with 09.');
                    if (this.value.length !== 11) return sf(this, 'Contact number must be exactly 11 digits.');
                    sf(this, '');
                });

                const bd = document.getElementById('edit_birthdate_' + id);
                if (bd) bd.addEventListener('change', function() {
                    const sel = new Date(this.value);
                    const today = new Date(); today.setHours(0,0,0,0);
                    const minDate = new Date(); minDate.setFullYear(minDate.getFullYear() - 120);
                    if (sel >= today) return sf(this, 'Birthdate must be in the past.');
                    if (sel < minDate) return sf(this, 'Please enter a valid birthdate.');
                    sf(this, '');
                });

                const idStatus = document.getElementById('edit_valid_id_status_' + id);
                const idRemarks = document.getElementById('edit_valid_id_remarks_' + id);
                function syncValidIdRemarks() {
                    if (!idStatus || !idRemarks) return;
                    const isRejected = idStatus.value === 'rejected';
                    idRemarks.required = isRejected;
                    idRemarks.placeholder = isRejected
                        ? 'Required: explain why the valid ID is rejected.'
                        : 'Optional remarks for this valid ID review.';
                    if (!isRejected) sf(idRemarks, '');
                }
                if (idStatus) {
                    idStatus.addEventListener('change', syncValidIdRemarks);
                    syncValidIdRemarks();
                }
            })(String(residentIdFromConfig));
            });
        }

// Reusable secure image viewer for resident profile photos and classification evidence.
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('residentSecureImageViewerModal');
    const imageEl = document.getElementById('residentSecureImageViewerImg');
    const titleEl = document.getElementById('residentSecureImageViewerTitle');

    if (!modalEl || !imageEl) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    function openSecureImageViewer(trigger) {
        const src = trigger?.getAttribute('data-secure-image-viewer');
        if (!src) return;

        imageEl.src = src;
        imageEl.alt = trigger.getAttribute('data-secure-image-title') || 'Resident image preview';
        if (titleEl) titleEl.textContent = trigger.getAttribute('data-secure-image-title') || 'Resident Image';
        modal.show();
    }

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-secure-image-viewer]');
        if (!trigger) return;
        event.preventDefault();
        openSecureImageViewer(trigger);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter' && event.key !== ' ') return;
        const trigger = event.target.closest?.('[data-secure-image-viewer]');
        if (!trigger) return;
        event.preventDefault();
        openSecureImageViewer(trigger);
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        imageEl.removeAttribute('src');
    });
});
