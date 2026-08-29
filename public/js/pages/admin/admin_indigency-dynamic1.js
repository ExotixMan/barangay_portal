const __cfg_admin_admin_indigency_1 = JSON.parse(document.getElementById('js-config-admin-admin-indigency-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editIndigencyModal' + __cfg_admin_admin_indigency_1.v1 + ''));
                        editModal.show();
                    });
