const __cfg_admin_admin_project_1 = JSON.parse(document.getElementById('js-config-admin-admin-project-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editProjectModal' + __cfg_admin_admin_project_1.v1 + ''));
                        editModal.show();
                    });
