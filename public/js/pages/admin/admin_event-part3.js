document.addEventListener('DOMContentLoaded', function () {
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');
            const checkboxes = () => Array.from(document.querySelectorAll('.event-checkbox'));

            function updateSelectedCount() {
                if (!selectedCount) {
                    return;
                }

                const count = checkboxes().filter(cb => cb.checked).length;
                selectedCount.innerHTML = count ? `<i class="fas fa-square-check me-1"></i>${count} event${count === 1 ? '' : 's'} selected` : '<i class="fas fa-square-check me-1"></i>No events selected';

                if (selectAll) {
                    const total = checkboxes().length;
                    selectAll.checked = total > 0 && count === total;
                    selectAll.indeterminate = count > 0 && count < total;
                }
            }

            checkboxes().forEach(cb => cb.addEventListener('change', updateSelectedCount));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes().forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            updateSelectedCount();

            document.querySelectorAll('.events-alert').forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 250);
                }, 4500);
            });
        });
