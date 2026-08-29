const __cfg_admin_admin_clearance_1 = JSON.parse(document.getElementById('js-config-admin-admin-clearance-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editClearanceModal' + __cfg_admin_admin_clearance_1.v1 + ''));
                        editModal.show();
                    });
