document.addEventListener('DOMContentLoaded', function () {
            const tabLinks = document.querySelectorAll('.tab-link');
            tabLinks.forEach(link => {
                if (link.textContent.toLowerCase().includes('roles')) {
                    link.click();
                }
            });

            const modalEl = document.getElementById('addRoleModal');
            if (modalEl) {
                new bootstrap.Modal(modalEl).show();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const userSearch = document.getElementById('globalSearch');
            if (!userSearch) return;

            document.addEventListener('keydown', function(event) {
                if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                    event.preventDefault();
                    userSearch.focus();
                    userSearch.select();
                }
            });
        });
