@extends('admin.layouts.admin')

@section('title', 'Backup Settings')
@section('page_title', 'Backup Settings')
@section('page_icon', 'fas fa-database')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/backup-settings.css') }}">
@endpush

@section('content')
            @php
                $latestBackup = $backups->isNotEmpty() ? $backups->first() : null;
                $totalSizeMb = $backups->sum('size') / 1024 / 1024;
                $displayTz = $displayTimezone ?? 'Asia/Manila';
            @endphp

            <div class="backup-page">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show backup-alert" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('restore_status') && session('restore_message'))
                    <div id="restoreResultPanel" class="alert {{ session('restore_status') === 'success' ? 'alert-success' : 'alert-danger' }} backup-alert border-0 mb-4" role="alert">
                        <div class="d-flex flex-column gap-1">
                            <div class="fw-bold">
                                <i class="fas {{ session('restore_status') === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' }} me-2"></i>
                                Restore Result
                            </div>
                            <div>{{ session('restore_message') }}</div>
                            <small class="opacity-75">Time: {{ session('restore_time') }}</small>
                        </div>
                    </div>
                @endif

                <section class="backup-hero">
                    <div class="hero-panel">
                        <div class="hero-content">
                            <div class="hero-icon"><i class="fas fa-shield-halved"></i></div>
                            <div class="hero-kicker">Backup Control Center</div>
                            <h2>Protect your barangay records.</h2>
                            <p>Create full archive backups, restore verified ZIP files, and manage existing backup copies in a cleaner workspace designed for quick admin decisions.</p>
                            <div class="hero-actions">
                                <form method="POST" action="{{ route('admin.backup.store') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i>Create New Backup
                                    </button>
                                </form>
                                <a href="#backupArchives" class="btn btn-light-soft">
                                    <i class="fas fa-folder-open"></i>View Archives
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="backup-stats-grid" aria-label="Backup overview">
                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-box-archive"></i></div>
                        <div>
                            <span>Total Backups</span>
                            <strong>{{ $backups->count() }}</strong>
                            <small>Available archive files in storage.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <span>Backup Type</span>
                            <strong style="font-size:1.25rem;">Full Archive</strong>
                            <small>Database, uploads, and managed documents.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-hard-drive"></i></div>
                        <div>
                            <span>Total Storage</span>
                            <strong style="font-size:1.35rem;">{{ number_format($totalSizeMb, 2) }} MB</strong>
                            <small>Combined size of listed backup files.</small>
                        </div>
                    </div>

                    <div class="backup-stat">
                        <div class="backup-stat-icon"><i class="fas fa-clock-rotate-left"></i></div>
                        <div>
                            <span>Latest Backup</span>
                            <strong style="font-size:1.05rem;">
                                {{ $latestBackup ? $latestBackup['modified_relative'] : 'No backup yet' }}
                            </strong>
                            <small>{{ $latestBackup ? $latestBackup['modified_at'] : 'Create your first full archive.' }}</small>
                        </div>
                    </div>
                </section>

                <div class="schedule-panel">
                    <div class="schedule-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h3>Automatic weekly backup <span class="schedule-chip"><i class="fas fa-calendar-check"></i>Every Sunday, 12:00 AM</span></h3>
                        <p>Your system is scheduled to create a backup using {{ $displayTz }} timezone.</p>
                    </div>
                </div>

                <section class="backup-workspace">
                    <aside class="restore-panel" id="restoreBackup">
                        <div class="panel-header">
                            <div class="restore-icon"><i class="fas fa-rotate-left"></i></div>
                            <div>
                                <div class="section-kicker">Restore</div>
                                <h3>Restore backup archive</h3>
                                <p>Upload a trusted backup ZIP file before restoring database and backup-managed files.</p>
                            </div>
                        </div>

                        <div class="restore-body">
                            <div class="restore-warning">
                                <i class="fas fa-triangle-exclamation mt-1"></i>
                                <div>This action overwrites current database data and backup-managed files. Use only a verified backup file.</div>
                            </div>

                            <form method="POST" action="{{ route('admin.backup.restore') }}" enctype="multipart/form-data" onsubmit="return validateUploadAndConfirmRestore(event, this);">
                                @csrf
                                <label class="restore-dropzone" id="restoreDropzone" for="restoreArchiveInput">
                                    <input type="file" name="backup_archive" id="restoreArchiveInput" accept=".zip" required>
                                    <span class="restore-upload-icon"><i class="fas fa-file-zipper"></i></span>
                                    <strong id="restoreFileLabel">Choose backup ZIP file</strong>
                                    <span>Click to browse or drag a .zip archive here.</span>
                                    <small>Accepted: .zip only, maximum 500 MB</small>
                                </label>
                                <div id="restoreValidationMessage" class="form-text"></div>
                                <button type="submit" class="btn btn-warning restore-submit-btn">
                                    <i class="fas fa-upload"></i>Upload and Restore
                                </button>
                            </form>

                            <div class="backup-checklist">
                                <div><i class="fas fa-check-circle"></i><span>Confirm the file came from this system.</span></div>
                                <div><i class="fas fa-check-circle"></i><span>Download the latest backup before restoring older data.</span></div>
                                <div><i class="fas fa-check-circle"></i><span>Restore during low system activity when possible.</span></div>
                            </div>
                        </div>
                    </aside>

                    <section class="archive-panel" id="backupArchives">
                        <div class="archive-header">
                            <div>
                                <div class="section-kicker"><i class="fas fa-folder-open"></i> Archives</div>
                                <h3>Available backups</h3>
                                <p>Review file age, size, and actions before downloading, restoring, or deleting an archive.</p>
                            </div>
                            <span class="archive-count"><i class="fas fa-box-archive"></i>{{ $backups->count() }} files</span>
                        </div>

                        @if($backups->isEmpty())
                            <div class="empty-state">
                                <div>
                                    <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                                    <h4>No backups found yet</h4>
                                    <p>Create a new backup to protect your database, uploads, and generated documents.</p>
                                </div>
                            </div>
                        @else
                            <div class="archive-list">
                                @foreach($backups as $backup)
                                    <div class="archive-row">
                                        <div class="archive-file">
                                            <div class="archive-icon"><i class="fas fa-file-zipper"></i></div>
                                            <div class="min-w-0">
                                                <strong title="{{ $backup['name'] }}">{{ $backup['name'] }}</strong>
                                                <small>Full system archive</small>
                                            </div>
                                        </div>

                                        <div class="archive-meta">
                                            <strong>{{ $backup['modified_at'] }}</strong>
                                            <span>{{ $backup['modified_relative'] }} · {{ $displayTz }}</span>
                                        </div>

                                        <div class="archive-size-cell">
                                            <span class="archive-size-pill">{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</span>
                                        </div>

                                        <div class="archive-actions">
                                            <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm btn-outline-primary" title="Download backup">
                                                <i class="fas fa-download"></i><span>Download</span>
                                            </a>

                                            <form method="POST" action="{{ route('admin.backup.restore') }}" onsubmit="return confirmRestore(event, this);">
                                                @csrf
                                                <input type="hidden" name="file" value="{{ $backup['name'] }}">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Restore backup">
                                                    <i class="fas fa-rotate-left"></i><span>Restore</span>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" onsubmit="return confirmDelete(event, this, 'Delete this backup file? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete backup">
                                                    <i class="fas fa-trash"></i><span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </section>
            </div>

<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="modal-warning-icon"><i class="fas fa-triangle-exclamation"></i></div>
                        <div class="min-w-0">
                            <h5 class="modal-title" id="confirmActionTitle">Confirm action</h5>
                            <p class="mt-2 mb-0" id="confirmActionMessage">Are you sure you want to continue?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light-soft" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionButton">Continue</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
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
                return window.confirm(message || 'Are you sure you want to continue?');
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
                document.querySelectorAll('.backup-alert.alert-dismissible').forEach(function (alertEl) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
                    bsAlert.close();
                });
            }, 6000);
        });
    </script>
@endpush
