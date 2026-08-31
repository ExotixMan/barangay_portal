const __cfg_admin_users_roles = JSON.parse(document.getElementById('js-config-admin-users-roles')?.dataset.jsConfig || '{}');

        function confirmReset() {
            return confirm('Are you sure you want to reset all permissions to default values? This action cannot be undone.');
        }

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;

            input.type = input.type === 'password' ? 'text' : 'password';
            const icon = input.closest('.input-group')?.querySelector('[data-click-call="togglePasswordVisibility"] i');
            if (icon) {
                icon.classList.toggle('fa-eye', input.type === 'password');
                icon.classList.toggle('fa-eye-slash', input.type !== 'password');
            }
        }

        function switchTab(tabName, event) {
            const panel = document.getElementById(tabName + 'Tab');
            const clicked = event?.currentTarget || Array.from(document.querySelectorAll('.tab-link')).find(function (link) {
                const args = link.getAttribute('data-click-args') || '';
                return args.includes('"' + tabName + '"');
            });

            if (!panel || !clicked) return;

            document.querySelectorAll('.tab-content').forEach(function (el) {
                el.classList.remove('active');
            });

            document.querySelectorAll('.tab-link').forEach(function (el) {
                el.classList.remove('active');
                el.setAttribute('aria-selected', 'false');
                el.setAttribute('tabindex', '-1');
            });

            panel.classList.add('active');
            clicked.classList.add('active');
            clicked.setAttribute('aria-selected', 'true');
            clicked.setAttribute('tabindex', '0');

            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
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

        function editRole(roleId) {
            Swal.fire({
                title: 'Edit Role',
                text: 'Opening role editor for ' + roleId,
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
        }

        function viewRoleMembers(roleId) {
            Swal.fire({
                title: 'Role Members',
                text: 'Viewing members of ' + roleId + ' role',
                icon: 'info',
                confirmButtonColor: '#3085d6'
            });
        }

        function savePermissions() {
            Swal.fire({
                title: 'Success!',
                text: 'Permissions saved successfully',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }

        function resetPermissions() {
            Swal.fire({
                title: 'Reset Permissions?',
                text: 'This will reset all permissions to default values',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire('Reset Complete', 'Permissions have been reset to default', 'success');
                }
            });
        }

        function promptNewPassword(userId, userName) {
            Swal.fire({
                title: 'Reset Password for ' + userName,
                html: `
                    <div class="mb-3 text-start">
                        <label class="form-label">New Password</label>
                        <input type="password" id="new_password_input" class="form-control" placeholder="Enter new password" minlength="8">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password_input" class="form-control" placeholder="Confirm new password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Reset Password',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                preConfirm: () => {
                    const password = document.getElementById('new_password_input');
                    const confirm = document.getElementById('confirm_password_input');

                    if (!password || !confirm) {
                        Swal.showValidationMessage('Error finding input fields');
                        return false;
                    }

                    if (!password.value) {
                        Swal.showValidationMessage('Password is required');
                        return false;
                    }
                    if (password.value.length < 8) {
                        Swal.showValidationMessage('Password must be at least 8 characters');
                        return false;
                    }
                    if (!window.isStrongPassword(password.value)) {
                        Swal.showValidationMessage('Password must include uppercase, lowercase, number, and special character');
                        return false;
                    }
                    if (password.value !== confirm.value) {
                        Swal.showValidationMessage('Passwords do not match');
                        return false;
                    }
                    return password.value;
                }
            }).then((result) => {
                if (result.isConfirmed) {

                    const hiddenInput = document.getElementById('new_password_' + userId);
                    const form = document.getElementById('resetPasswordForm_' + userId);

                    if (hiddenInput && form) {

                        hiddenInput.value = result.value;

                        form.submit();
                    } else {
                        console.error('Form elements not found:', {
                            hiddenInput: hiddenInput ? 'found' : 'not found',
                            form: form ? 'found' : 'not found',
                            userId: userId
                        });

                        Swal.fire({
                            title: 'Error',
                            text: 'Could not find form elements. Please try again or refresh the page.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                }
            });
        }

        function confirmResetPassword(event, form, userName) {
            event.preventDefault();

            const passwordInput = form.querySelector('input[name="new_password"]');

            if (!passwordInput.value) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please enter a new password',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }

            Swal.fire({
                title: 'Reset Password',
                html: `Are you sure you want to reset password for <strong>${userName}</strong>?<br><br>New password will be: <strong>${passwordInput.value}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }

        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const requested = (urlParams.get('tab') || 'users').toLowerCase();
            const allowed = ['users', 'roles', 'permissions', 'audit'];
            const tab = allowed.includes(requested) ? requested : 'users';

            const tabLinks = Array.from(document.querySelectorAll('.tab-link'));
            const target = tabLinks.find(function (link) {
                const args = link.getAttribute('data-click-args') || '';
                return args.includes('"' + tab + '"');
            });

            if (target) {
                target.click();
            } else {
                const users = tabLinks.find(function (link) {
                    return (link.getAttribute('data-click-args') || '').includes('"users"');
                });
                users?.click();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.view-log-details-btn');

            detailButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const detailsRaw = this.dataset.logDetails || '{}';
                    let prettyJson = '{}';

                    try {
                        const parsed = JSON.parse(detailsRaw);
                        prettyJson = JSON.stringify(parsed, null, 2);
                    } catch (error) {
                        prettyJson = detailsRaw;
                    }

                    const userEl = document.getElementById('auditDetailUser');
                    const timeEl = document.getElementById('auditDetailTime');
                    const actionEl = document.getElementById('auditDetailAction');
                    const moduleEl = document.getElementById('auditDetailModule');
                    const jsonEl = document.getElementById('auditDetailJson');

                    if (userEl) userEl.textContent = this.dataset.logUser || '-';
                    if (timeEl) timeEl.textContent = this.dataset.logTime || '-';
                    if (actionEl) actionEl.textContent = this.dataset.logAction || '-';
                    if (moduleEl) moduleEl.textContent = this.dataset.logModule || '-';
                    if (jsonEl) jsonEl.textContent = prettyJson;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            if (__cfg_admin_users_roles.passwordResetSuccess) {
                Swal.fire({
                    title: 'Success!',
                    text: __cfg_admin_users_roles.passwordResetSuccess,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 5000
                });
            }

            if (__cfg_admin_users_roles.passwordError) {
                Swal.fire({
                    title: 'Error!',
                    text: __cfg_admin_users_roles.passwordError,
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            }
        });
