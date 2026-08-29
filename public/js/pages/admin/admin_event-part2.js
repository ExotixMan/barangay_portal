function bulkDelete() {
            const checkboxes = document.querySelectorAll('.event-checkbox:checked');
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
                        text: 'Please select at least one event to archive.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    alert('Please select at least one event to archive.');
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
                    text: `Are you sure you want to archive ${checkboxes.length} selected event(s)?`,
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
                if (confirm(`Are you sure you want to archive ${checkboxes.length} event(s)?`)) {
                    bulkForm.submit();
                }
            }
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.event-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
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

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="image-preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
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

        let searchTimeout;
        document.getElementById('globalSearch')?.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        function openZoomModal(imageUrl) {
            const zoomImage = document.getElementById('zoomedImage');
            zoomImage.src = imageUrl;
            const zoomModal = new bootstrap.Modal(document.getElementById('globalImageZoomModal'));
            zoomModal.show();
        }

        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                el.insertAdjacentElement('afterend', fb);
            }
            fb.textContent = msg;
        }

        function cf(el) {
            if (el) {
                el.classList.remove('is-invalid');

                const fb = el.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.remove();
            }
        }

        function attachEventValidation(form) {
            if (!form) return;
            const title       = form.querySelector('[name="title"]');
            const description = form.querySelector('[name="description"]');
            const eventDate   = form.querySelector('[name="event_date"]');
            const location    = form.querySelector('[name="location"]');
            const type        = form.querySelector('[name="type"]');
            const image       = form.querySelector('[name="image"]');

            if (title) {
                title.removeEventListener('input', title._handler);
                title._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('input', title._handler);

                title.removeEventListener('blur', title._blurHandler);
                title._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Title is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Title must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                title.addEventListener('blur', title._blurHandler);
            }

            if (description) {
                description.removeEventListener('input', description._handler);
                description._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('input', description._handler);

                description.removeEventListener('blur', description._blurHandler);
                description._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Description is required.');
                    } else if (this.value.trim().length < 10) {
                        sf(this, 'Description must be at least 10 characters.');
                    } else {
                        cf(this);
                    }
                };
                description.addEventListener('blur', description._blurHandler);
            }

            if (eventDate) {
                eventDate.removeEventListener('change', eventDate._handler);
                eventDate._handler = function() {
                    if (!this.value) {
                        sf(this, 'Event date is required.');
                    } else {
                        cf(this);
                    }
                };
                eventDate.addEventListener('change', eventDate._handler);
            }

            if (location) {
                location.removeEventListener('input', location._handler);
                location._handler = function() {
                    cf(this);
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('input', location._handler);

                location.removeEventListener('blur', location._blurHandler);
                location._blurHandler = function() {
                    if (!this.value.trim()) {
                        sf(this, 'Location is required.');
                    } else if (this.value.trim().length < 3) {
                        sf(this, 'Location must be at least 3 characters.');
                    } else {
                        cf(this);
                    }
                };
                location.addEventListener('blur', location._blurHandler);
            }

            if (type) {
                type.removeEventListener('change', type._handler);
                type._handler = function() {
                    if (!this.value) {
                        sf(this, 'Please select an event type.');
                    } else {
                        cf(this);
                    }
                };
                type.addEventListener('change', type._handler);
            }

            if (image) {
                image.removeEventListener('change', image._handler);
                image._handler = function() {
                    if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                        sf(this, 'Image must be less than 5MB.');
                        this.value = '';
                    } else {
                        cf(this);
                    }
                };
                image.addEventListener('change', image._handler);
            }

            form.removeEventListener('submit', form._submitHandler);

            form._submitHandler = function(e) {
                let valid = true;

                if (title) {
                    if (!title.value.trim()) {
                        sf(title, 'Title is required.');
                        valid = false;
                    } else if (title.value.trim().length < 3) {
                        sf(title, 'Title must be at least 3 characters.');
                        valid = false;
                    } else {
                        cf(title);
                    }
                }

                if (description) {
                    if (!description.value.trim()) {
                        sf(description, 'Description is required.');
                        valid = false;
                    } else if (description.value.trim().length < 10) {
                        sf(description, 'Description must be at least 10 characters.');
                        valid = false;
                    } else {
                        cf(description);
                    }
                }

                if (eventDate && !eventDate.value) {
                    sf(eventDate, 'Event date is required.');
                    valid = false;
                } else if (eventDate && eventDate.value) {
                    cf(eventDate);
                }

                if (location) {
                    if (!location.value.trim()) {
                        sf(location, 'Location is required.');
                        valid = false;
                    } else if (location.value.trim().length < 3) {
                        sf(location, 'Location must be at least 3 characters.');
                        valid = false;
                    } else {
                        cf(location);
                    }
                }

                if (type && !type.value) {
                    sf(type, 'Please select an event type.');
                    valid = false;
                } else if (type && type.value) {
                    cf(type);
                }

                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.');
                    valid = false;
                } else if (image) {
                    cf(image);
                }

                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();

                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };

            form.addEventListener('submit', form._submitHandler);
        }

        attachEventValidation(document.getElementById('addEventForm'));

        document.querySelectorAll('[id^="editEventForm"]').forEach(attachEventValidation);

        const addModal = document.getElementById('addEventModal');
        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function () {
                const form = document.getElementById('addEventForm');
                if (form) {
                    form.reset();
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        }

        document.querySelectorAll('[id^="editEventModal"]').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    const invalidFields = form.querySelectorAll('.is-invalid');
                    invalidFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        const fb = field.parentElement.querySelector('.invalid-feedback');
                        if (fb) fb.remove();
                    });
                }
            });
        });
    });
