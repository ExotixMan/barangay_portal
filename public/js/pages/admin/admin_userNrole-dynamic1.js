const __cfg_admin_admin_userNrole_1 = JSON.parse(document.getElementById('js-config-admin-admin-usernrole-1')?.dataset.jsConfig || '{}');

            document.addEventListener('DOMContentLoaded', function () {
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(link => {
                    if (link.textContent.toLowerCase().includes('roles')) {
                        link.click();
                    }
                });

                const modalEl = document.getElementById('editRoleModal' + __cfg_admin_admin_userNrole_1.v1 + '');
                if (modalEl) {
                    new bootstrap.Modal(modalEl).show();
                }
            });
