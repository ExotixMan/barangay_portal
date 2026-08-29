document.addEventListener('DOMContentLoaded', function() {
            window.isStrongPassword = function(value) {
            return value.length >= 8
                && /[A-Z]/.test(value)
                && /[a-z]/.test(value)
                && /[0-9]/.test(value)
                && /[^A-Za-z0-9]/.test(value);
            };

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
                if (!fb) {
                    fb = document.createElement('div');
                    fb.className = 'invalid-feedback';
                    fb.dataset.generated = 'true';
                    el.insertAdjacentElement('afterend', fb);
                }
            fb.textContent = msg;
        }
            function cf(el) {
                if (!el) return;
                el.classList.remove('is-invalid');
                const fb = el.parentElement.querySelector('.invalid-feedback[data-generated="true"]');
                if (fb) {
                    fb.remove();
                }
            }

        function attachUserValidation(form) {
            if (!form) return;
            const firstName = form.querySelector('[name="first_name"]');
            const middleInitial = form.querySelector('[name="middle_initial"]');
            const lastName  = form.querySelector('[name="last_name"]');
            const email     = form.querySelector('[name="email"]');
            const contact   = form.querySelector('[name="contact_number"]');
            const username  = form.querySelector('[name="username"]');
            const password  = form.querySelector('[name="password"]');
            const roleId    = form.querySelector('[name="role_id"]');

            const namePattern = /^[a-zA-Z\s\-\.]+$/;
            const middleInitialPattern = /^[A-Za-z]\.?$/;

            if (firstName) {
                firstName.addEventListener('input', function() { cf(this); }, {once: true});
                firstName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'First name is required.');
                    else if (v.length < 2) sf(this, 'First name must be at least 2 characters.');
                    else if (!namePattern.test(v)) sf(this, 'First name can only contain letters, spaces, hyphens, or periods.');
                    else cf(this);
                });
            }
            if (lastName) {
                lastName.addEventListener('input', function() { cf(this); }, {once: true});
                lastName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Last name is required.');
                    else if (v.length < 2) sf(this, 'Last name must be at least 2 characters.');
                    else if (!namePattern.test(v)) sf(this, 'Last name can only contain letters, spaces, hyphens, or periods.');
                    else cf(this);
                });
            }
            if (middleInitial) {
                middleInitial.addEventListener('input', function() {
                    this.value = this.value.replace(/[^A-Za-z\.]/g, '').slice(0, 3).toUpperCase();
                    const v = this.value.trim();
                    if (!v) cf(this);
                    else if (!middleInitialPattern.test(v)) sf(this, 'Middle initial must be one letter, optional period (e.g. A or A.).');
                    else cf(this);
                });
            }
            if (email) {
                email.addEventListener('input', function() { cf(this); }, {once: true});
                email.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Email is required.');
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) sf(this, 'Enter a valid email address.');
                    else cf(this);
                });
            }
            if (contact) {
                contact.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
                    if (this.value.length > 0) {
                        if (!this.value.startsWith('09')) sf(this, 'Contact must start with 09.');
                        else if (this.value.length !== 11) sf(this, 'Contact must be exactly 11 digits.');
                        else cf(this);
                    } else cf(this);
                });
            }
            if (username) {
                username.addEventListener('input', function() { cf(this); }, {once: true});
                username.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Username is required.');
                    else if (v.length < 3) sf(this, 'Username must be at least 3 characters.');
                    else if (!/^[a-zA-Z0-9_.\-]+$/.test(v)) sf(this, 'Username can only contain letters, numbers, underscores, dots, or hyphens.');
                    else cf(this);
                });
            }
            if (password) {
                password.addEventListener('input', function() { cf(this); }, {once: true});
                password.addEventListener('input', function() {
                    if (!this.value) sf(this, 'Password is required.');
                    else if (!window.isStrongPassword(this.value)) sf(this, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.');
                    else cf(this);
                });
            }
            if (roleId) roleId.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a role.'); else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (firstName) {
                    const v = firstName.value.trim();
                    if (!v) { sf(firstName, 'First name is required.'); valid = false; }
                    else if (v.length < 2) { sf(firstName, 'First name must be at least 2 characters.'); valid = false; }
                    else if (!namePattern.test(v)) { sf(firstName, 'First name can only contain letters, spaces, hyphens, or periods.'); valid = false; }
                }
                if (lastName) {
                    const v = lastName.value.trim();
                    if (!v) { sf(lastName, 'Last name is required.'); valid = false; }
                    else if (v.length < 2) { sf(lastName, 'Last name must be at least 2 characters.'); valid = false; }
                    else if (!namePattern.test(v)) { sf(lastName, 'Last name can only contain letters, spaces, hyphens, or periods.'); valid = false; }
                }
                if (middleInitial) {
                    const v = middleInitial.value.trim();
                    if (v && !middleInitialPattern.test(v)) {
                        sf(middleInitial, 'Middle initial must be one letter, optional period (e.g. A or A.).');
                        valid = false;
                    }
                }
                if (email) {
                    const v = email.value.trim();
                    if (!v) { sf(email, 'Email is required.'); valid = false; }
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { sf(email, 'Enter a valid email address.'); valid = false; }
                }
                if (contact && contact.value.length > 0) {
                    if (!contact.value.startsWith('09') || contact.value.length !== 11) {
                        sf(contact, 'Contact must be 11 digits starting with 09.'); valid = false;
                    }
                }
                if (username) {
                    const v = username.value.trim();
                    if (!v) { sf(username, 'Username is required.'); valid = false; }
                    else if (v.length < 3) { sf(username, 'Username must be at least 3 characters.'); valid = false; }
                    else if (!/^[a-zA-Z0-9_.\-]+$/.test(v)) { sf(username, 'Username can only contain letters, numbers, underscores, dots, or hyphens.'); valid = false; }
                }
                if (password) {
                    if (!password.value) { sf(password, 'Password is required.'); valid = false; }
                    else if (!window.isStrongPassword(password.value)) { sf(password, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.'); valid = false; }
                }
                if (roleId && !roleId.value) { sf(roleId, 'Please select a role.'); valid = false; }
                if (!valid) e.preventDefault();
            });
        }

        const resetForm = document.getElementById('resetPasswordForm');
        if (resetForm) {
            const pwField    = document.getElementById('resetPassword');
            const pwConfirm  = document.getElementById('resetPasswordConfirm');

            if (pwField) {
                pwField.addEventListener('input', function() { cf(this); }, {once: true});
                pwField.addEventListener('input', function() {

                    this.value = this.value.replace(/\s/g, "");
                    if (!this.value) sf(this, 'Password is required.');
                    else if (!window.isStrongPassword(this.value)) sf(this, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.');
                    else cf(this);
                    if (pwConfirm && pwConfirm.value) {
                        if (pwConfirm.value !== this.value) sf(pwConfirm, 'Passwords do not match.');
                        else cf(pwConfirm);
                    }
                });
            }
            if (pwConfirm) {
                pwConfirm.addEventListener('input', function() {

                    this.value = this.value.replace(/\s/g, "");
                    if (!this.value) sf(this, 'Please confirm your password.');
                    else if (pwField && this.value !== pwField.value) sf(this, 'Passwords do not match.');
                    else cf(this);
                });
            }
            resetForm.addEventListener('submit', function(e) {
                let valid = true;
                if (pwField) {
                    if (!pwField.value) { sf(pwField, 'Password is required.'); valid = false; }
                    else if (!window.isStrongPassword(pwField.value)) { sf(pwField, 'Password must be 8+ chars with uppercase, lowercase, number, and special character.'); valid = false; }
                }
                if (pwConfirm) {
                    if (!pwConfirm.value) { sf(pwConfirm, 'Please confirm your password.'); valid = false; }
                    else if (pwField && pwConfirm.value !== pwField.value) { sf(pwConfirm, 'Passwords do not match.'); valid = false; }
                }
                if (!valid) e.preventDefault();
            });
        }

        function attachRoleValidation(form) {
            if (!form) return;
            const roleName = form.querySelector('[name="name"]');

            if (roleName && !roleName.readOnly) {
                roleName.addEventListener('input', function() { cf(this); }, {once: true});
                roleName.addEventListener('input', function() {
                    const v = this.value.trim();
                    if (!v) sf(this, 'Role name is required.');
                    else if (v.length < 2) sf(this, 'Role name must be at least 2 characters.');
                    else if (/\s/.test(v)) sf(this, 'Role name must not contain spaces.');
                    else if (!/^[a-z_]+$/.test(v)) sf(this, 'Role name must be lowercase letters and underscores only.');
                    else cf(this);
                });
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (roleName && !roleName.readOnly) {
                    const v = roleName.value.trim();
                    if (!v) { sf(roleName, 'Role name is required.'); valid = false; }
                    else if (v.length < 2) { sf(roleName, 'Role name must be at least 2 characters.'); valid = false; }
                    else if (/\s/.test(v)) { sf(roleName, 'Role name must not contain spaces.'); valid = false; }
                    else if (!/^[a-z_]+$/.test(v)) { sf(roleName, 'Role name must be lowercase letters and underscores only.'); valid = false; }
                }
                if (!valid) e.preventDefault();
            });
        }

        attachUserValidation(document.getElementById('addUserForm'));
        document.querySelectorAll('[id^="editUserForm"]').forEach(attachUserValidation);

        attachRoleValidation(document.getElementById('addRoleForm'));
        document.querySelectorAll('[id^="editRoleForm"]').forEach(attachRoleValidation);
    });
