document.addEventListener('DOMContentLoaded', function () {
        const referenceInput = document.getElementById('referenceNumber');
        const clearReference = document.getElementById('clearReference');
        const copyButtons = document.querySelectorAll('[data-copy-target]');
        const filterButtons = document.querySelectorAll('#filterTabs .bhd-filter-btn');
        const rows = document.querySelectorAll('#requestsList .request-row');
        const emptyFilterMessage = document.getElementById('emptyFilterMessage');

        if (referenceInput && clearReference) {
            const toggleClear = () => {
                clearReference.classList.toggle('show', referenceInput.value.trim().length > 0);
            };

            referenceInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase().replace(/\s+/g, '');
                toggleClear();
            });

            clearReference.addEventListener('click', function () {
                referenceInput.value = '';
                referenceInput.focus();
                toggleClear();
            });

            toggleClear();
        }

        copyButtons.forEach(function (button) {
            button.addEventListener('click', async function () {
                const target = document.getElementById(button.dataset.copyTarget);
                if (!target) return;

                const value = target.textContent.trim();

                try {
                    await navigator.clipboard.writeText(value);
                    const original = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => button.innerHTML = original, 1400);
                } catch (error) {
                    const range = document.createRange();
                    range.selectNodeContents(target);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            });
        });

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                button.classList.add('active');

                const filter = button.dataset.filter;
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const type = row.dataset.type || '';
                    const status = row.dataset.status || '';

                    const show =
                        filter === 'all' ||
                        filter === type ||
                        filter === status;

                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });

                if (emptyFilterMessage) {
                    emptyFilterMessage.style.display = visibleCount === 0 && rows.length > 0 ? 'block' : 'none';
                }
            });
        });

        if (window.location.hash === '#trackResult') {
            document.getElementById('trackResult')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
