const __cfg_admin_admin_event_1 = JSON.parse(document.getElementById('js-config-admin-admin-event-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editEventModal' + __cfg_admin_admin_event_1.v1 + ''));
                        editModal.show();
                    });
