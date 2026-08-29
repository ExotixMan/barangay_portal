const __cfg_admin_admin_announcement_1 = JSON.parse(document.getElementById('js-config-admin-admin-announcement-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal' + __cfg_admin_admin_announcement_1.v1 + ''));
                        editModal.show();
                    });
