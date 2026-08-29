const __cfg_admin_admin_incident_report_1 = JSON.parse(document.getElementById('js-config-admin-admin-incident-report-1')?.dataset.jsConfig || '{}');

                    document.addEventListener('DOMContentLoaded', function () {
                        var editModal = new bootstrap.Modal(document.getElementById('editIncidentModal' + __cfg_admin_admin_incident_report_1.v1 + ''));
                        editModal.show();
                    });
