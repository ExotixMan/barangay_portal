const __cfg_admin_projects = JSON.parse(document.getElementById('js-config-admin-projects')?.dataset.jsConfig || '{}');

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.project-checkbox:checked');
            const bulkForm = document.getElementById('bulkForm');
            const idsContainer = document.getElementById('bulkIdsContainer');

            if (!bulkForm || !idsContainer) {
                alert('Bulk Archive form is unavailable. Please refresh the page.');
                return;
            }

            if (checkboxes.length === 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one project to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one project to archive.');
                }
                return;
            }

            idsContainer.innerHTML = '';
            checkboxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                idsContainer.appendChild(input);
            });

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirm Bulk Archive',
                    text: `Are you sure you want to archive ${checkboxes.length} selected project(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Archive'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkForm.submit();
                    }
                });
            } else {
                if (confirm(`Are you sure you want to archive ${checkboxes.length} project(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.project-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            if (typeof updateSelectedProjectsCount === 'function') {
                updateSelectedProjectsCount();
            }
        }

        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message || 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(message || 'Are you sure?')) {
                    form.submit();
                }
            }

            return false;
        }

        document.getElementById('addProgressRange')?.addEventListener('input', function() {
            document.getElementById('addProgressValue').textContent = this.value + '%';
            document.getElementById('addProgressHidden').value = this.value;

            const statusSelect = document.querySelector('select[name="status"]');
            if (this.value == 100) {
                statusSelect.value = 'completed';
            } else {
                statusSelect.value = 'ongoing';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.project-checkbox');
            const selectAllCheckbox = document.getElementById('selectAll');

            if (checkboxes.length > 0 && selectAllCheckbox) {
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
                    });
                });
            }

            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
                        submitBtn.disabled = true;
                    }
                });
            }

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                if (!bar.classList.contains('bg-success')) {
                    bar.style.background = 'var(--primary-gradient)';
                }
            });

            if (__cfg_admin_projects.canUpdate) {
                __cfg_admin_projects.projectIds.forEach(function (projectIdFromConfig) {
                (function(projectId) {
                    const range = document.getElementById('editProgressRange' + projectId);
                    const value = document.getElementById('editProgressValue' + projectId);
                    const hidden = document.getElementById('editProgressHidden' + projectId);
                    const statusSelect = document.getElementById('edit_status_' + projectId);

                    if (range && value && hidden && statusSelect) {
                        range.addEventListener('input', function() {
                            value.textContent = this.value + '%';
                            hidden.value = this.value;

                            if (this.value == 100) {
                                statusSelect.value = 'completed';
                            } else {
                                statusSelect.value = 'ongoing';
                            }
                        });
                    }
                })(String(projectIdFromConfig));
                });
            }
        });
