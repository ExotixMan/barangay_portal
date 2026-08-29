document.addEventListener('DOMContentLoaded', function() {
        function sf(el, msg) {
            if (!el) return;
            el.classList.add('is-invalid');
            let fb = el.parentElement.querySelector('.invalid-feedback');
            if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; el.insertAdjacentElement('afterend', fb); }
            fb.textContent = msg;
            fb.style.display = 'block';
        }
        function cf(el) {
            if (!el) return;
            el.classList.remove('is-invalid');
            const fb = el.parentElement.querySelector('.invalid-feedback');
            if (fb) {
                fb.textContent = '';
                fb.style.display = 'none';
            }
        }

        function attachProjectValidation(form) {
            if (!form) return;
            const title              = form.querySelector('[name="title"]');
            const description        = form.querySelector('[name="description"]');
            const status             = form.querySelector('[name="status"]');
            const startDate          = form.querySelector('[name="start_date"]');
            const expectedCompletion = form.querySelector('[name="expected_completion"]');
            const progressHidden     = form.querySelector('input[type="hidden"][name="progress"]');

            function hasFourDigitYear(dateValue) {
                return /^\d{4}-\d{2}-\d{2}$/.test(dateValue);
            }

            function getProgressValue() {
                const raw = progressHidden ? progressHidden.value : '0';
                const parsed = parseInt(raw, 10);
                return Number.isNaN(parsed) ? 0 : parsed;
            }

            function validateStatusVsProgress() {
                if (!status) return true;
                const progress = getProgressValue();
                if (status.value === 'completed' && progress !== 100) {
                    sf(status, 'Status can only be Completed when progress is 100%.');
                    return false;
                }
                if (status.value) {
                    cf(status);
                }
                return true;
            }

            function checkDateFieldFormat(field) {
                if (!field || !field.value) return true;
                if (!hasFourDigitYear(field.value)) {
                    sf(field, 'Date must use a 4-digit year (YYYY-MM-DD).');
                    return false;
                }
                return true;
            }

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (description) {
                description.addEventListener('input', function() { cf(this); }, {once: true});
                description.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Description is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Description must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (status) status.addEventListener('change', function() {
                if (!this.value) {
                    sf(this, 'Please select a status.');
                } else {
                    validateStatusVsProgress();
                }
            });

            if (progressHidden) {
                progressHidden.addEventListener('input', validateStatusVsProgress);
                progressHidden.addEventListener('change', validateStatusVsProgress);
            }

            if (startDate) {
                startDate.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion) {
                expectedCompletion.addEventListener('change', function() {
                    if (checkDateFieldFormat(this)) cf(this);
                });
            }

            if (expectedCompletion && startDate) {
                const checkDates = function() {
                    if (!checkDateFieldFormat(startDate) || !checkDateFieldFormat(expectedCompletion)) {
                        return;
                    }
                    if (expectedCompletion.value && startDate.value && expectedCompletion.value < startDate.value) {
                        sf(expectedCompletion, 'Completion date must not be before the start date.');
                    } else cf(expectedCompletion);
                };
                expectedCompletion.addEventListener('change', checkDates);
                startDate.addEventListener('change', checkDates);
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                    else { cf(title); }
                }
                if (description) {
                    if (!description.value.trim()) { sf(description, 'Description is required.'); valid = false; }
                    else if (description.value.trim().length < 10) { sf(description, 'Description must be at least 10 characters.'); valid = false; }
                    else { cf(description); }
                }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                else if (status && !validateStatusVsProgress()) { valid = false; }
                else if (status) { cf(status); }

                if (startDate && !checkDateFieldFormat(startDate)) {
                    valid = false;
                }
                if (expectedCompletion && !checkDateFieldFormat(expectedCompletion)) {
                    valid = false;
                }

                if (startDate && expectedCompletion && startDate.value && expectedCompletion.value && expectedCompletion.value < startDate.value) {
                    sf(expectedCompletion, 'Completion date must not be before the start date.'); valid = false;
                }
                else if (expectedCompletion) {
                    cf(expectedCompletion);
                }
                if (!valid) e.preventDefault();
            });
        }

        attachProjectValidation(document.getElementById('addProjectForm'));
        document.querySelectorAll('[id^="editProjectForm"]').forEach(attachProjectValidation);
    });

        function updateSelectedProjectsCount() {
            const selected = document.querySelectorAll('.project-checkbox:checked').length;
            const counter = document.getElementById('selectedProjectsCount');
            if (!counter) return;
            counter.innerHTML = selected > 0
                ? `<i class="fas fa-square-check me-1"></i>${selected} project${selected === 1 ? '' : 's'} selected`
                : '<i class="fas fa-square-check me-1"></i>No projects selected';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.project-checkbox, #selectAll').forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelectedProjectsCount);
            });
            updateSelectedProjectsCount();

            document.querySelectorAll('.modal-body .row.g-3').forEach((row) => {
                if (!row.closest('.modal-body')) return;
                row.classList.add('modal-form-grid');
            });

            document.querySelectorAll('.modal-body textarea[name="description"]').forEach((textarea) => {
                if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList?.contains('field-hint')) {
                    textarea.insertAdjacentHTML('afterend', '<span class="field-hint">Use short, resident-friendly wording. Include scope, location, and expected outcome when possible.</span>');
                }
            });
        });
