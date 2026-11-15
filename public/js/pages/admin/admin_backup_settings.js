var pendingActionForm = null;
        var confirmModalInstance = null;
        var isSubmittingConfirmedAction = false;

        function showConfirmModal(form, title, message, confirmLabel) {
            pendingActionForm = form;

            var titleEl = document.getElementById('confirmActionTitle');
            var messageEl = document.getElementById('confirmActionMessage');
            var confirmBtn = document.getElementById('confirmActionButton');
            var modalEl = document.getElementById('confirmActionModal');

            if (!modalEl || !confirmBtn) {
                return AdminUI.unavailableConfirm(message || 'Are you sure you want to continue?');
            }

            if (titleEl) titleEl.textContent = title || 'Confirm action';
            if (messageEl) messageEl.textContent = message || 'Are you sure you want to continue?';
            if (confirmBtn) confirmBtn.textContent = confirmLabel || 'Continue';

            confirmModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            confirmModalInstance.show();
            return false;
        }

        function confirmDelete(event, form, message) {
            if (isSubmittingConfirmedAction) {
                return true;
            }

            event.preventDefault();
            return showConfirmModal(
                form,
                'Delete backup file?',
                message || 'Delete this backup file? This cannot be undone.',
                'Delete Backup'
            );
        }

        function confirmRestore(event, form) {
            if (isSubmittingConfirmedAction) {
                return true;
            }

            event.preventDefault();
            return showConfirmModal(
                form,
                'Restore backup archive?',
                'This will overwrite the current database and backup-managed files with the selected archive. Continue only if the backup file is verified.',
                'Restore Backup'
            );
        }

        function validateUploadAndConfirmRestore(event, form) {
            var fileInput = form.querySelector('input[name="backup_archive"]');
            var validationMessage = document.getElementById('restoreValidationMessage');

            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Please select a backup zip file first.';
                }
                return false;
            }

            var file = fileInput.files[0];
            var maxBytes = 500 * 1024 * 1024;
            var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
            var isZipByName = /\.zip$/i.test(file.name || '');

            if (!isZipByType && !isZipByName) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                }
                return false;
            }

            if (file.size > maxBytes) {
                event.preventDefault();
                if (validationMessage) {
                    validationMessage.className = 'form-text text-danger';
                    validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                }
                return false;
            }

            if (validationMessage) {
                validationMessage.className = 'form-text text-success';
                validationMessage.textContent = 'File is valid and ready to restore.';
            }

            return confirmRestore(event, form);
        }

        function updateRestoreFileState(fileInput) {
            var validationMessage = document.getElementById('restoreValidationMessage');
            var fileLabel = document.getElementById('restoreFileLabel');

            if (!fileInput || !validationMessage) {
                return;
            }

            if (!fileInput.files || fileInput.files.length === 0) {
                validationMessage.className = 'form-text';
                validationMessage.textContent = '';
                if (fileLabel) fileLabel.textContent = 'Choose backup ZIP file';
                return;
            }

            var file = fileInput.files[0];
            var maxBytes = 500 * 1024 * 1024;
            var isZipByType = file.type === 'application/zip' || file.type === 'application/x-zip-compressed' || file.type === 'multipart/x-zip';
            var isZipByName = /\.zip$/i.test(file.name || '');

            if (fileLabel) {
                fileLabel.textContent = file.name || 'Selected backup file';
            }

            if (!isZipByType && !isZipByName) {
                validationMessage.className = 'form-text text-danger';
                validationMessage.textContent = 'Invalid file type. Please upload a .zip backup file.';
                return;
            }

            if (file.size > maxBytes) {
                validationMessage.className = 'form-text text-danger';
                validationMessage.textContent = 'Backup file is too large. Maximum allowed size is 500 MB.';
                return;
            }

            validationMessage.className = 'form-text text-success';
            validationMessage.textContent = 'File is valid and ready to restore.';
        }

        document.addEventListener('DOMContentLoaded', function () {
            var fileInput = document.getElementById('restoreArchiveInput');
            var restoreDropzone = document.getElementById('restoreDropzone');
            var restoreResultPanel = document.getElementById('restoreResultPanel');
            var confirmBtn = document.getElementById('confirmActionButton');

            if (restoreResultPanel) {
                restoreResultPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    updateRestoreFileState(fileInput);
                });
            }

            if (restoreDropzone && fileInput) {
                ['dragenter', 'dragover'].forEach(function (eventName) {
                    restoreDropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        restoreDropzone.classList.add('is-dragover');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    restoreDropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        restoreDropzone.classList.remove('is-dragover');
                    });
                });

                restoreDropzone.addEventListener('drop', function (event) {
                    if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                        fileInput.files = event.dataTransfer.files;
                        updateRestoreFileState(fileInput);
                    }
                });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    if (!pendingActionForm) {
                        return;
                    }

                    isSubmittingConfirmedAction = true;
                    if (confirmModalInstance) {
                        confirmModalInstance.hide();
                    }
                    pendingActionForm.submit();
                });
            }

            setTimeout(function () {
                document.querySelectorAll('.backup-alert.alert-dismissible.alert-success').forEach(function (alertEl) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
                    bsAlert.close();
                });
            }, 6000);
        });
