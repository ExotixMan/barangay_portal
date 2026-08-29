document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('bulkNotifyResidentsModal');
    if (modalEl) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
});
