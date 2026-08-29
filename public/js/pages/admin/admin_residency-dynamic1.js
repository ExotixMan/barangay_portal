const __cfg_admin_admin_residency_1 = JSON.parse(document.getElementById('js-config-admin-admin-residency-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editApplicationModal' + __cfg_admin_admin_residency_1.v1 + ''));
                        editModal.show();
                    });
