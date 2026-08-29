const __cfg_barangay_system_login_1 = JSON.parse(document.getElementById('js-config-barangay-system-login-1')?.dataset.jsConfig || '{}');

        document.addEventListener('DOMContentLoaded', function() {

            const toggle = document.getElementById('togglePassword');
            const pwd = document.getElementById('password');
            if (toggle && pwd) {
                toggle.addEventListener('click', function() {
                    const type = pwd.type === 'password' ? 'text' : 'password';
                    pwd.type = type;
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginButton');

            const clientAlert = document.getElementById('clientErrorAlert');
            const clientMessage = document.getElementById('clientErrorMessage');
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('password');

            function hideServerAlerts() {
                const serverAlerts = document.querySelectorAll('.alert:not(#clientErrorAlert)');
                serverAlerts.forEach(alert => {
                    alert.style.display = 'none';
                });
            }

            function showClientError(message) {
                hideServerAlerts();
                clientMessage.textContent = message;
                clientAlert.style.display = 'flex';

                setTimeout(() => {
                    clientAlert.style.transition = 'opacity 0.5s ease';
                    clientAlert.style.opacity = '0';
                    setTimeout(() => {
                        clientAlert.style.display = 'none';
                        clientAlert.style.opacity = '1';
                        clientAlert.style.transition = '';
                    }, 500);
                }, 5000);
            }

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    let hasError = false;

                    if (!loginInput.value.trim()) {
                        e.preventDefault();
                        hasError = true;
                        showClientError('' + __cfg_barangay_system_login_1.v1 + '');
                        loginInput.focus();
                    }
                    else if (!passwordInput.value) {
                        e.preventDefault();
                        hasError = true;
                        showClientError('' + __cfg_barangay_system_login_1.v2 + '');
                        passwordInput.focus();
                    }

                    if (!hasError && loginBtn) {
                        loginBtn.classList.add('loading');
                        loginBtn.disabled = true;
                    }
                });
            }

            if (loginInput) {
                loginInput.addEventListener('input', function() {
                    clientAlert.style.display = 'none';
                });
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    clientAlert.style.display = 'none';
                });
            }

            const createLink = document.getElementById('createAccountLink');
            const modalEl = document.getElementById('termsModal');

            if (createLink && modalEl) {
                const termsModal = new bootstrap.Modal(modalEl);

                createLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    const cb = document.getElementById('privacyCheckbox');
                    if (cb) {
                        cb.checked = false;
                        document.getElementById('acceptTerms').disabled = true;
                    }
                    termsModal.show();
                });

                const privacyCheckbox = document.getElementById('privacyCheckbox');
                const acceptBtn = document.getElementById('acceptTerms');
                if (privacyCheckbox && acceptBtn) {
                    privacyCheckbox.addEventListener('change', function() {
                        acceptBtn.disabled = !this.checked;
                    });
                }

                acceptBtn?.addEventListener('click', function() {

                    this.blur();
                    document.getElementById('createAccountLink')?.focus();
                    termsModal.hide();
                    window.location.href = "" + __cfg_barangay_system_login_1.v3 + "";
                });
            }

            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
