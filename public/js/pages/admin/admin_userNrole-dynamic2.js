const __cfg_admin_admin_userNrole_2 = JSON.parse(document.getElementById('js-config-admin-admin-usernrole-2')?.dataset.jsConfig || '{}');

            document.addEventListener('DOMContentLoaded', function () {
                const tabLinks = document.querySelectorAll('.tab-link');
                tabLinks.forEach(link => {
                    if (link.textContent.toLowerCase().includes('users')) {
                        link.click();
                    }
                });

                const modalEl = document.getElementById('userPermissionsModal' + __cfg_admin_admin_userNrole_2.v1 + '');
                if (modalEl) {
                    new bootstrap.Modal(modalEl).show();
                }
            });
