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

        function attachAnnouncementValidation(form) {
            if (!form) return;
            const title    = form.querySelector('[name="title"]');
            const content  = form.querySelector('[name="content"]');
            const category = form.querySelector('[name="category"]');
            const status   = form.querySelector('[name="status"]');
            const image    = form.querySelector('[name="image"]');

            if (title) {
                title.addEventListener('input', function() { cf(this); }, {once: true});
                title.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Title is required.');
                    else if (this.value.trim().length < 3) sf(this, 'Title must be at least 3 characters.');
                    else cf(this);
                });
            }
            if (content) {
                content.addEventListener('input', function() { cf(this); }, {once: true});
                content.addEventListener('input', function() {
                    if (!this.value.trim()) sf(this, 'Content is required.');
                    else if (this.value.trim().length < 10) sf(this, 'Content must be at least 10 characters.');
                    else cf(this);
                });
            }
            if (category) category.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a category.'); else cf(this);
            });
            if (status) status.addEventListener('change', function() {
                if (!this.value) sf(this, 'Please select a status.'); else cf(this);
            });
            if (image) image.addEventListener('change', function() {
                if (this.files && this.files[0] && this.files[0].size / 1024 / 1024 > 5) {
                    sf(this, 'Image must be less than 5MB.'); this.value = '';
                } else cf(this);
            });

            form.addEventListener('submit', function(e) {
                let valid = true;
                if (title) {
                    if (!title.value.trim()) { sf(title, 'Title is required.'); valid = false; }
                    else if (title.value.trim().length < 3) { sf(title, 'Title must be at least 3 characters.'); valid = false; }
                }
                if (content) {
                    if (!content.value.trim()) { sf(content, 'Content is required.'); valid = false; }
                    else if (content.value.trim().length < 10) { sf(content, 'Content must be at least 10 characters.'); valid = false; }
                }
                if (category && !category.value) { sf(category, 'Please select a category.'); valid = false; }
                if (status && !status.value) { sf(status, 'Please select a status.'); valid = false; }
                if (image && image.files && image.files[0] && image.files[0].size / 1024 / 1024 > 5) {
                    sf(image, 'Image must be less than 5MB.'); valid = false;
                }
                if (!valid) e.preventDefault();
            });
        }

        attachAnnouncementValidation(document.getElementById('addAnnouncementForm'));
        document.querySelectorAll('[id^="editAnnouncementForm"]').forEach(attachAnnouncementValidation);
    });
