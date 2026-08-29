function refreshAnnouncementSelection() {
            const checkboxes = Array.from(document.querySelectorAll('.announcement-checkbox'));
            const checked = checkboxes.filter(cb => cb.checked);
            const selectAll = document.getElementById('selectAll');
            const selectedCount = document.getElementById('selectedAnnouncementsCount');

            if (selectAll) {
                selectAll.checked = checked.length > 0 && checked.length === checkboxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            if (selectedCount) {
                selectedCount.innerHTML = checked.length > 0
                    ? `<i class="fas fa-square-check me-1"></i>${checked.length} announcement${checked.length === 1 ? '' : 's'} selected`
                    : '<i class="fas fa-square-check me-1"></i>No announcements selected';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.announcement-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', refreshAnnouncementSelection);
            });
            refreshAnnouncementSelection();
        });
