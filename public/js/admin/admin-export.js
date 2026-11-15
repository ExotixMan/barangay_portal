(function () {
    'use strict';
    let pending = false;
    async function open() {
        const form = document.getElementById('exportForm');
        if (!form || pending) return;
        if (typeof Swal === 'undefined') { AdminUI.unavailableConfirm(); return; }
        const selected = [...new Set(Array.from(document.querySelectorAll('.resident-checkbox:checked:not(:disabled), .application-checkbox:checked:not(:disabled)'), field => field.value))];
        pending = true;
        try {
            const result = await Swal.fire({
                title: 'Export records',
                text: selected.length ? `Download ${selected.length} selected record(s).` : 'No rows selected. Download all records in this module, excluding archived records.',
                input: 'select', inputLabel: 'File format',
                inputOptions: {xlsx: 'Excel workbook (.xlsx)', csv: 'CSV file (.csv)'}, inputValue: 'xlsx',
                footer: 'Excel preserves phone numbers such as 09123456789. When importing CSV into Excel, set Contact Number to Text.',
                showCancelButton: true, confirmButtonText: 'Download', cancelButtonText: 'Cancel',
                confirmButtonColor: '#b4232d', focusCancel: true
            });
            if (!result.isConfirmed) return;
            // Downloads leave the page open. Replace previous selections so a
            // second export cannot accidentally contain the first export's IDs.
            form.querySelectorAll('[name="ids[]"], [name="export_format"]').forEach(field => field.remove());
            for (const [name, value] of [['export_format', result.value], ...selected.map(id => ['ids[]', id])]) {
                const field = document.createElement('input');
                field.type = 'hidden'; field.name = name; field.value = value; form.appendChild(field);
            }
            HTMLFormElement.prototype.submit.call(form);
        } catch (error) {
            AdminUI.notify('The export could not be started. Reload the page and try again.');
        } finally { pending = false; }
    }
    window.AdminExport = {open};
})();
